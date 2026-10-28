<?php
declare(strict_types=1);
require_once __DIR__ . '/../PRACTICAL-10/session.php';
$user = sh_require_role('student');
$csrfToken = sh_csrf_token();
?><!DOCTYPE html>
<html lang="en-IN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub - Dashboard</title>
  <link rel="stylesheet" href="../PRACTICAL-03/css/style.css">
</head>

<body>
<a class="skip-link" href="#main-content">Skip to main content</a>

    <header>
        <h1>StudentHub</h1>
        <p>Student Dashboard</p>
        <p>Signed in as <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></p>
        <form action="../PRACTICAL-10/logout.php" method="post"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"><button type="submit">Sign out</button></form>
    </header>

<nav aria-label="Primary navigation">
    <a href="index.html">Home</a>
    <a href="about.html">About</a>
    <a href="register.php">Register</a>
    <a href="login.php">Login</a>
    <a href="dashboard.php" aria-current="page">Dashboard</a>
    <a href="events.html">Events</a>
    <a href="profile.html">Profile</a>
    <a href="contact.html">Contact</a>
    <a href="admin.php">Admin</a>
    <a href="faq.html">FAQ</a>
    <a href="feedback.html">Feedback</a>
</nav>
    <main id="main-content">

        <section>
            <h2>Welcome to Your Dashboard</h2>
            <p>Welcome back, <?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?>. Your student resources and campus updates are gathered here.</p>
        </section>

        <section class="db-practical-link">
            <h2>Database Connection Practical</h2>
            <p>Check the local StudentHub MySQL setup for the schema and PDO connectivity practical.</p>
            <a href="../PRACTICAL-08/connection-test.php">Open the PDO connection check →</a>
        </section>

        <article>
            <h2>Student Services</h2>

            <ul>
                <li>My Profile</li>
                <li>Course Details</li>
                <li>Attendance</li>
                <li>Assignments</li>
                <li>Results</li>
                <li>Upcoming Events</li>
            </ul>
        </article>

        <aside>
            <h3>Latest Announcement</h3>
            <p>Portal announcement: check official college channels for current academic dates.</p>

            <h3>Upcoming Event</h3>
            <p>Web Development Workshop: sample event; schedule to be confirmed.</p>
        </aside>

</div>

    </main>

    <footer>
        <p>&copy; 2026 StudentHub. All Rights Reserved.</p>
        <p>Email: support@studenthub.com</p>
    </footer>

</body>

</html>
