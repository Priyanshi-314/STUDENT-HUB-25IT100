<?php
declare(strict_types=1);

require_once __DIR__ . '/../PRACTICAL-09/db.php';

const STUDENTHUB_SESSION_TIMEOUT = 1200;
const STUDENTHUB_REMEMBER_SECONDS = 2592000;

function sh_boot(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_name('STUDENTHUBSESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();

    if (isset($_SESSION['auth_user']['user_id'], $_SESSION['last_activity'])
        && time() - (int)$_SESSION['last_activity'] > STUDENTHUB_SESSION_TIMEOUT) {
        $timedOutUser = (int)$_SESSION['auth_user']['user_id'];
        sh_clear_remember_cookie();
        try {
            sh_audit('session_timeout', $timedOutUser);
        } catch (Throwable $exception) {
            error_log('StudentHub session timeout audit failed: ' . $exception->getMessage());
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        session_start();
        $_SESSION['session_expired'] = true;
    } elseif (isset($_SESSION['auth_user'])) {
        $_SESSION['last_activity'] = time();
    }
}

function sh_csrf_token(): string
{
    sh_boot();
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function sh_valid_csrf(mixed $token): bool
{
    sh_boot();
    return is_string($token) && isset($_SESSION['csrf_token'])
        && hash_equals((string)$_SESSION['csrf_token'], $token);
}

function sh_audit(string $event, ?int $userId = null): void
{
    $stmt = studentHubMysqli()->prepare('INSERT INTO auth_audit_log (user_id, event_type) VALUES (?, ?)');
    $stmt->bind_param('is', $userId, $event);
    $stmt->execute();
    $stmt->close();
}

function sh_current_user(): ?array
{
    sh_boot();
    if (!isset($_SESSION['auth_user']) || !is_array($_SESSION['auth_user'])) {
        return null;
    }
    return $_SESSION['auth_user'];
}

function sh_set_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['auth_user'] = [
        'user_id' => (int)$user['user_id'],
        'full_name' => (string)$user['full_name'],
        'username' => (string)$user['username'],
        'email' => (string)$user['email'],
        'role' => (string)$user['role'],
    ];
    $_SESSION['last_activity'] = time();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    unset($_SESSION['session_expired']);
}

function sh_clear_remember_cookie(): void
{
    $cookie = $_COOKIE['studenthub_remember'] ?? '';
    if (is_string($cookie) && preg_match('/^([a-f0-9]{32}):[a-f0-9]{64}$/', $cookie, $parts)) {
        $stmt = studentHubMysqli()->prepare('DELETE FROM remember_tokens WHERE selector = ?');
        $stmt->bind_param('s', $parts[1]);
        $stmt->execute();
        $stmt->close();
    }
    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    setcookie('studenthub_remember', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    unset($_COOKIE['studenthub_remember']);
}

function sh_issue_remember_token(int $userId): void
{
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));
    $hash = hash('sha256', $validator);
    $expiry = date('Y-m-d H:i:s', time() + STUDENTHUB_REMEMBER_SECONDS);
    $stmt = studentHubMysqli()->prepare('INSERT INTO remember_tokens (user_id, selector, validator_hash, expires_at) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('isss', $userId, $selector, $hash, $expiry);
    $stmt->execute();
    $stmt->close();

    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    setcookie('studenthub_remember', $selector . ':' . $validator, [
        'expires' => time() + STUDENTHUB_REMEMBER_SECONDS,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function sh_restore_remembered_user(): bool
{
    $cookie = $_COOKIE['studenthub_remember'] ?? '';
    if (!is_string($cookie) || !preg_match('/^([a-f0-9]{32}):([a-f0-9]{64})$/', $cookie, $parts)) {
        return false;
    }

    $connection = studentHubMysqli();
    $stmt = $connection->prepare(
        'SELECT r.user_id, r.validator_hash, u.full_name, u.username, u.email, u.role, u.is_active
         FROM remember_tokens r JOIN users u ON u.user_id = r.user_id
         WHERE r.selector = ? AND r.expires_at > NOW() LIMIT 1'
    );
    $stmt->bind_param('s', $parts[1]);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user || !(bool)$user['is_active'] || !hash_equals((string)$user['validator_hash'], hash('sha256', $parts[2]))) {
        sh_clear_remember_cookie();
        return false;
    }

    $delete = $connection->prepare('DELETE FROM remember_tokens WHERE selector = ?');
    $delete->bind_param('s', $parts[1]);
    $delete->execute();
    $delete->close();
    sh_set_user($user);
    sh_issue_remember_token((int)$user['user_id']);
    $update = $connection->prepare('UPDATE users SET last_login = NOW() WHERE user_id = ?');
    $update->bind_param('i', $user['user_id']);
    $update->execute();
    $update->close();
    sh_audit('remember_login', (int)$user['user_id']);
    return true;
}

function sh_auth_user(): ?array
{
    $user = sh_current_user();
    if ($user !== null) {
        return $user;
    }
    return sh_restore_remembered_user() ? sh_current_user() : null;
}

function sh_role_destination(string $role): string
{
    return '/PRACTICAL-10/dashboard.php';
}

function sh_require_role(string $requiredRole): array
{
    sh_boot();
    $user = sh_auth_user();
    if ($user === null) {
        header('Location: /PRACTICAL-10/login.php' . (!empty($_SESSION['session_expired']) ? '?expired=1' : ''));
        exit;
    }
    if ($user['role'] !== $requiredRole) {
        header('Location: ' . sh_role_destination((string)$user['role']));
        exit;
    }
    $_SESSION['last_activity'] = time();
    return $user;
}

function sh_logout(): never
{
    sh_boot();
    $user = sh_current_user();
    if ($user !== null) {
        try {
            sh_audit('logout', (int)$user['user_id']);
        } catch (Throwable $exception) {
            error_log('StudentHub logout audit failed: ' . $exception->getMessage());
        }
    }
    sh_clear_remember_cookie();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    header('Location: /PRACTICAL-10/login.php?logged_out=1');
    exit;
}
