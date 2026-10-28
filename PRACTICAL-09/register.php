<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../PRACTICAL-10/session.php';

$errors = [];
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    $errors[] = 'Submit this registration form using POST.';
} else {
    sh_boot();
    if (!sh_valid_csrf($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        $errors[] = 'This form expired or could not be verified. Reload Register and try again.';
    }

    $read = static function (string $key, int $max): string {
        $value = $_POST[$key] ?? '';
        if (!is_string($value)) return '';
        $value = trim(strip_tags($value));
        return mb_strlen($value, 'UTF-8') <= $max ? $value : '';
    };
    $name = $read('name', 100);
    $username = strtolower($read('username', 24));
    $email = strtolower($read('email', 190));
    $mobile = $read('phone', 10);
    $course = $read('course', 100);
    $year = $read('year', 1);
    $gender = $read('gender', 20);
    $password = $_POST['password'] ?? null;
    $confirm = $_POST['confirm'] ?? null;

    if (!preg_match('/^[\p{L}][\p{L}\p{M} .\'-]{1,59}$/u', $name)) $errors[] = 'Enter a valid full name (2 to 60 characters).';
    if (!preg_match('/^[a-z0-9._-]{4,24}$/', $username)) $errors[] = 'Use a username with 4 to 24 letters, numbers, dots, underscores, or hyphens.';
    if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/', $email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (!preg_match('/^[6-9][0-9]{9}$/', $mobile)) $errors[] = 'Enter a valid 10-digit Indian mobile number.';
    if (!in_array($course, ['BCA', 'BSc IT', 'BTech CSE', 'BTech IT', 'BCom', 'BA'], true)) $errors[] = 'Choose a valid course.';
    if (!in_array($year, ['1', '2', '3', '4'], true)) $errors[] = 'Choose a valid year of study.';
    if (!in_array($gender, ['male', 'female', 'other', 'prefer-not'], true)) $errors[] = 'Choose a gender option.';
    if (!is_string($password) || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9\s])\S{8,}$/', $password)) $errors[] = 'Use at least 8 password characters with uppercase, lowercase, a number, and a symbol.';
    if (!is_string($confirm) || !is_string($password) || !hash_equals($password, $confirm)) $errors[] = 'Password and confirmation do not match.';
    if (($_POST['terms'] ?? '') !== 'accepted') $errors[] = 'Confirm the account information and consent notice.';

    if (!$errors) {
        try {
            $connection = studentHubMysqli();
            $check = $connection->prepare('SELECT user_id FROM users WHERE email = ? OR username = ? LIMIT 1');
            $check->bind_param('ss', $email, $username);
            $check->execute();
            $check->store_result();
            $duplicate = $check->num_rows > 0;
            $check->close();
            if ($duplicate) {
                http_response_code(422);
                $errors[] = 'That email address or username is already registered. Try logging in or choose another.';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $studyYear = (int)$year;
                $connection->begin_transaction();
                $insert = $connection->prepare(
                    "INSERT INTO users (full_name, username, email, mobile, course, study_year, gender, password_hash, role)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'student')"
                );
                $insert->bind_param('sssssiss', $name, $username, $email, $mobile, $course, $studyYear, $gender, $passwordHash);
                $insert->execute();
                $userId = (int)$connection->insert_id;
                $insert->close();
                $audit = $connection->prepare("INSERT INTO auth_audit_log (user_id, event_type) VALUES (?, 'registration_created')");
                $audit->bind_param('i', $userId);
                $audit->execute();
                $audit->close();
                $connection->commit();
                header('Location: ../PRACTICAL-02/login.php?registered=1');
                exit;
            }
        } catch (mysqli_sql_exception $exception) {
            if (isset($connection) && $connection instanceof mysqli && $connection->errno === 1062) {
                try { $connection->rollback(); } catch (Throwable) {}
                http_response_code(422);
                $errors[] = 'That email address or username is already registered. Try logging in or choose another.';
            } else {
                if (isset($connection) && $connection instanceof mysqli) {
                    try { $connection->rollback(); } catch (Throwable) {}
                }
                error_log('StudentHub Practical 9 registration failed: ' . $exception->getMessage());
                http_response_code(500);
                $errors[] = 'Registration is temporarily unavailable. Check that the StudentHub database is running and try again.';
            }
        } catch (Throwable $exception) {
            if (isset($connection) && $connection instanceof mysqli) {
                try { $connection->rollback(); } catch (Throwable) {}
            }
            error_log('StudentHub Practical 9 registration error: ' . $exception->getMessage());
            http_response_code(500);
            $errors[] = 'Registration could not be completed. Please try again.';
        }
    } elseif (http_response_code() < 400) {
        http_response_code(422);
    }
}
?>
<!doctype html>
<html lang="en-IN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StudentHub - Registration result</title>
  <link rel="stylesheet" href="../PRACTICAL-03/css/style.css">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to main content</a>
<header><h1>🎓 StudentHub</h1><p>Secure student registration</p></header>
<nav aria-label="Primary navigation"><a href="../PRACTICAL-02/index.html">Home</a><a href="../PRACTICAL-02/register.php">Register</a><a href="../PRACTICAL-02/login.php">Login</a><a href="../PRACTICAL-02/contact.html">Contact</a></nav>
<main id="main-content">
  <section class="hero"><div class="heroText">
    <p class="smallTitle">ACCOUNT REGISTRATION</p>
    <h2><?= $errors ? 'Please review your registration' : 'Your account is ready' ?></h2>
    <?php if ($errors): ?><ul><?php foreach ($errors as $error): ?><li><?= $escape($error) ?></li><?php endforeach; ?></ul><?php else: ?><p>Your details were validated and saved using MySQLi. Your password was hashed before storage.</p><?php endif; ?>
    <div class="heroButtons"><a class="button-link" href="../PRACTICAL-02/<?= $errors ? 'register.php' : 'login.php' ?>"><?= $errors ? 'Return to Registration' : 'Continue to Login' ?></a><a class="button-link secondary-link" href="../PRACTICAL-02/index.html">StudentHub Home</a></div>
  </div><aside class="heroCard"><span aria-hidden="true">🔐</span><h3>Protected credentials</h3><p>Email and username are checked for duplicates. The password is stored only as a one-way hash.</p></aside></section>
</main>
<footer><p>© 2026 StudentHub · Practical 9</p></footer>
</body>
</html>
