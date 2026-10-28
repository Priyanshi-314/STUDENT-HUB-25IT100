<?php
declare(strict_types=1);
require_once __DIR__ . '/../PRACTICAL-10/session.php';
sh_boot();
if (($signedInUser = sh_auth_user()) !== null) {
    header('Location: ' . sh_role_destination((string)$signedInUser['role']));
    exit;
}
$csrfToken = sh_csrf_token();
$loginMessage = '';
if (!empty($_SESSION['session_expired']) || isset($_GET['expired'])) {
    $loginMessage = 'Your session expired after 20 minutes of inactivity. Please sign in again.';
} elseif (isset($_GET['registered'])) {
    $loginMessage = 'Account created successfully. Sign in with your username or email.';
} elseif (isset($_GET['error']) && $_GET['error'] === 'invalid') {
    $loginMessage = 'Username/email or password is incorrect.';
} elseif (isset($_GET['error']) && $_GET['error'] === 'unavailable') {
    $loginMessage = 'Sign in is temporarily unavailable. Check that MySQL is running.';
} elseif (isset($_GET['unauthorized'])) {
    $loginMessage = 'Sign in is required to view that page.';
} elseif (isset($_GET['logged_out'])) {
    $loginMessage = 'You have been securely signed out.';
}
?><!DOCTYPE html>
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
        <p>Student Login</p>
    </header>

<nav aria-label="Primary navigation">
    <a href="index.html">Home</a>
    <a href="about.html">About</a>
    <a href="register.php">Register</a>
    <a href="login.php" aria-current="page">Login</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="events.html">Events</a>
    <a href="profile.html">Profile</a>
    <a href="contact.html">Contact</a>
    <a href="admin.php">Admin</a>
    <a href="faq.html">FAQ</a>
    <a href="feedback.html">Feedback</a>
</nav>
    <main id="main-content">

        <section>

            <h2>Login</h2>

            <form action="../PRACTICAL-10/login.php" method="post">
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

            <p>Don't have an account? <a href="register.php">Register Here</a></p>

        </section>

    </main>

    <footer>
        <p>&copy; 2026 StudentHub. All Rights Reserved.</p>
        <p>Email: support@studenthub.com</p>
    </footer>

</body>

</html>
