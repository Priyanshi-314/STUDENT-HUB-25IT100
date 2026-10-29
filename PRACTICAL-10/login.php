<?php
declare(strict_types=1);
require_once __DIR__ . '/session.php';
sh_boot();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!sh_valid_csrf($_POST['csrf_token'] ?? null)) {
        header('Location: /PRACTICAL-10/login.php?error=invalid'); exit;
    }
    $identifier = trim((string)($_POST['identifier'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if ($identifier === '' || $password === '') {
        header('Location: /PRACTICAL-10/login.php?error=invalid'); exit;
    }
    try {
        $db = studentHubMysqli();
        $stmt = $db->prepare('SELECT user_id, full_name, username, email, password_hash, role, is_active FROM users WHERE username=? OR email=? LIMIT 1');
        $stmt->bind_param('ss', $identifier, $identifier);
        $stmt->execute(); $user = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$user || !(bool)$user['is_active'] || !password_verify($password, (string)$user['password_hash'])) {
            try { sh_audit('login_failed'); } catch (Throwable $ignored) {}
            header('Location: /PRACTICAL-10/login.php?error=invalid'); exit;
        }
        sh_set_user($user);
        $uid = (int)$user['user_id'];
        try {
            $update = $db->prepare('UPDATE users SET last_login=NOW() WHERE user_id=?');
            $update->bind_param('i',$uid); $update->execute(); $update->close();
            sh_audit('login_success',$uid);
        } catch (Throwable $ignored) { error_log('StudentHub login audit/update failed'); }
        try {
            sh_clear_remember_cookie();
            if (($_POST['remember_me'] ?? '') === '1') sh_issue_remember_token($uid);
        } catch (Throwable $ignored) { error_log('StudentHub remember-me unavailable'); }
        header('Location: /PRACTICAL-10/dashboard.php'); exit;
    } catch (Throwable $exception) {
        error_log('StudentHub login: '.$exception->getMessage());
        header('Location: /PRACTICAL-10/login.php?error=unavailable'); exit;
    }
}
if (sh_auth_user() !== null) {
    header('Location: /PRACTICAL-10/dashboard.php'); exit;
}
$csrfToken = sh_csrf_token();
$loginMessage = '';
if (!empty($_SESSION['session_expired']) || isset($_GET['expired'])) $loginMessage = 'Your session expired. Please sign in again.';
elseif (isset($_GET['registered'])) $loginMessage = 'Account created successfully. Please sign in.';
elseif (($_GET['error'] ?? '') === 'invalid') $loginMessage = 'Username/email or password is incorrect, or the form expired.';
elseif (($_GET['error'] ?? '') === 'unavailable') $loginMessage = 'Login unavailable. Check MySQL and the database setup.';
elseif (isset($_GET['logged_out'])) $loginMessage = 'You have been securely signed out.';
?>?><!DOCTYPE html>
<html lang="en-IN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub - Login</title>
  <link rel="stylesheet" href="../PRACTICAL-03/css/style.css">
</head>

<body>
<a class="skip-link" href="#main-content">Skip to main content</a>

    <header>
        <h1>StudentHub</h1>
        <p>Secure Student Login · Practical 10</p>
    </header>

<nav aria-label="Primary navigation">
    <a href="/PRACTICAL-02/index.html">Home</a>
    <a href="/PRACTICAL-02/about.html">About</a>
    <a href="/PRACTICAL-02/register.php">Register</a>
    <a href="/PRACTICAL-10/login.php" aria-current="page">Login</a>
    <a href="/PRACTICAL-10/dashboard.php">Dashboard</a>
    <a href="/PRACTICAL-02/events.html">Events</a>
    <a href="/PRACTICAL-02/profile.html">Profile</a>
    <a href="/PRACTICAL-02/contact.html">Contact</a>
    <a href="/PRACTICAL-02/admin.php">Admin</a>
    <a href="/PRACTICAL-02/faq.html">FAQ</a>
    <a href="/PRACTICAL-02/feedback.html">Feedback</a>
</nav>
    <main id="main-content">

        <section>

            <h2>Login</h2>

            <form action="/PRACTICAL-10/login.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <?php if ($loginMessage !== ""): ?><p class="form-notice" role="status"><?= htmlspecialchars($loginMessage, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>

                <label for="identifier">Username or Email:</label><br>
                <input type="text" id="identifier" name="identifier" autocomplete="username" maxlength="190" required><br><br>

                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" autocomplete="current-password" required><br><br>

                <input type="checkbox" id="remember" name="remember_me" value="1">
                <label for="remember">Keep me signed in on this device</label>

                <br><br>

                <input type="submit" value="Login">
                <input type="reset" value="Clear">

            </form>

            <p>Don't have an account? <a href="/PRACTICAL-02/register.php">Register Here</a></p>

        </section>

    </main>

    <footer>
        <p>&copy; 2026 StudentHub. All Rights Reserved.</p>
        <p>Email: support@studenthub.com</p>
    </footer>

</body>

</html>
