<?php
declare(strict_types=1);
require_once __DIR__ . '/../PRACTICAL-10/session.php';
$user = sh_require_role('admin');
$csrfToken = sh_csrf_token();
?><!DOCTYPE html>
<html lang="en-IN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub - Admin</title>
  <link rel="stylesheet" href="../PRACTICAL-03/css/style.css">
</head>

<body>
<a class="skip-link" href="#main-content">Skip to main content</a>

    <header>
        <h1>StudentHub</h1>
        <p>Admin Panel</p>
    </header>

  <nav aria-label="Primary navigation">
    <a href="index.html">Home</a>
    <a href="about.html">About</a>
    <a href="register.php">Register</a>
    <a href="login.php">Login</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="events.html">Events</a>
    <a href="profile.html">Profile</a>
    <a href="contact.html">Contact</a>
    <a href="admin.php" aria-current="page">Admin</a>
    <a href="faq.html">FAQ</a>
    <a href="feedback.html">Feedback</a>
</nav>

    <main id="main-content">

        <section>
            <h2>Admin Dashboard</h2>
            <p>You are signed in as <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?> with the administrator role. This page is protected by server-side role checks.</p>
        </section>

        <article>
            <h2>Admin Functions</h2>

            <ul>
                <li>Manage Students</li>
                <li>Manage Events</li>
                <li>Manage Courses</li>
                <li>View Reports</li>
                <li>Manage Feedback</li>
            </ul>
        </article>

        <aside>
            <h3>Admin Notice</h3>
            <p>Check student registrations and approve pending requests.</p>
        </aside>

    </main>

    <footer>
        <p>&copy; 2026 StudentHub. All Rights Reserved.</p>
        <p>Email: support@studenthub.com</p>
        <form action="../PRACTICAL-10/logout.php" method="post"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"><button type="submit">Sign out</button></form>
    </footer>

</body>

</html>
