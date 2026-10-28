<?php
declare(strict_types=1);

require_once __DIR__ . '/session.php';
sh_boot();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ../PRACTICAL-02/login.php');
    exit;
}
if (!sh_valid_csrf($_POST['csrf_token'] ?? null)) {
    header('Location: ../PRACTICAL-02/login.php?error=invalid');
    exit;
}

$identifier = $_POST['identifier'] ?? '';
$password = $_POST['password'] ?? '';
if (!is_string($identifier) || !is_string($password) || trim($identifier) === '' || $password === '') {
    header('Location: ../PRACTICAL-02/login.php?error=invalid');
    exit;
}
$identifier = trim($identifier);

try {
    $connection = studentHubMysqli();
    $stmt = $connection->prepare(
        'SELECT user_id, full_name, username, email, password_hash, role, is_active
         FROM users WHERE username = ? OR email = ? LIMIT 1'
    );
    $stmt->bind_param('ss', $identifier, $identifier);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user || !(bool)$user['is_active'] || !password_verify($password, (string)$user['password_hash'])) {
        try { sh_audit('login_failed'); } catch (Throwable) {}
        header('Location: ../PRACTICAL-02/login.php?error=invalid');
        exit;
    }

    session_regenerate_id(true);
    sh_set_user($user);
    $userId = (int)$user['user_id'];
    $update = $connection->prepare('UPDATE users SET last_login = NOW() WHERE user_id = ?');
    $update->bind_param('i', $userId);
    $update->execute();
    $update->close();
    sh_audit('login_success', $userId);

    if (($_POST['remember_me'] ?? '') === '1') {
        sh_clear_remember_cookie();
        sh_issue_remember_token($userId);
    } else {
        sh_clear_remember_cookie();
    }

    header('Location: ' . sh_role_destination((string)$user['role']));
    exit;
} catch (Throwable $exception) {
    error_log('StudentHub Practical 10 sign-in failed: ' . $exception->getMessage());
    header('Location: ../PRACTICAL-02/login.php?error=unavailable');
    exit;
}
