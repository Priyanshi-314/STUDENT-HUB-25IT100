<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';

$errorMessage = '';
$matchingStudents = null;
try {
    $pdo = studentHubPdo();
    $query = $pdo->prepare('SELECT COUNT(*) FROM students WHERE program = :program');
    $query->execute(['program' => 'B.Tech Information Technology']);
    $matchingStudents = (int)$query->fetchColumn();
} catch (Throwable $exception) {
    error_log('StudentHub Practical 8 connection check failed: ' . $exception->getMessage());
    http_response_code(503);
    $errorMessage = 'The database is not ready. Start MySQL, import the Practical 8 SQL file, and check the local connection settings.';
}
?>
<!doctype html>
<html lang="en-IN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StudentHub - PDO Connection Check</title>
  <link rel="stylesheet" href="../PRACTICAL-03/css/style.css">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to main content</a>
<header><h1>🎓 StudentHub</h1><p>Practical 8 · MySQL and PDO connection</p></header>
<nav aria-label="Primary navigation"><a href="../PRACTICAL-02/index.html">Home</a><a href="../PRACTICAL-02/dashboard.html">Dashboard</a><a href="../PRACTICAL-02/events.html">Events</a></nav>
<main id="main-content">
  <section class="hero"><div class="heroText">
    <?php if ($errorMessage === ''): ?>
      <p class="smallTitle">CONNECTION SUCCESSFUL</p>
      <h2>StudentHub is connected to MySQL.</h2>
      <p>PDO connected successfully and the prepared query returned <?= $matchingStudents ?> sample Information Technology student record(s).</p>
      <p>The connection uses UTF-8, exception-based error handling, and native prepared statements.</p>
    <?php else: ?>
      <p class="smallTitle">CONNECTION PENDING</p>
      <h2>MySQL is not ready yet.</h2>
      <p><?= htmlspecialchars($errorMessage, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>
    <div class="heroButtons"><a class="button-link" href="../PRACTICAL-02/dashboard.html">Back to Dashboard</a><a class="button-link secondary-link" href="sql/studenthub.sql">View schema and seed SQL</a></div>
  </div><aside class="heroCard"><span aria-hidden="true">🗄️</span><h3>Normalized data model</h3><p>Students and events are stored once; registrations connect them through foreign keys.</p></aside></section>
</main>
<footer><p>© 2026 StudentHub · Practical 8</p></footer>
</body>
</html>
