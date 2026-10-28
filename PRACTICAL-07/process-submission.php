<?php
declare(strict_types=1);

function cleanField(string $name, int $maxLength): string
{
    $value = $_POST[$name] ?? '';
    if (!is_string($value)) {
        return '';
    }
    $value = trim(strip_tags($value));
    return strlen($value) <= $maxLength ? $value : '';
}

function saveSubmission(string $type, array $payload): void
{
    $file = __DIR__ . '/storage/submissions.json';
    $handle = fopen($file, 'c+');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        if (is_resource($handle)) fclose($handle);
        throw new RuntimeException('Storage is unavailable.');
    }

    try {
        rewind($handle);
        $contents = stream_get_contents($handle);
        $records = json_decode($contents === false || trim($contents) === '' ? '[]' : $contents, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($records)) {
            throw new RuntimeException('Storage format is invalid.');
        }
        $records[] = [
            'id' => bin2hex(random_bytes(8)),
            'type' => $type,
            'received_at' => gmdate(DATE_ATOM),
            'data' => $payload,
        ];
        $json = json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        rewind($handle);
        if (!ftruncate($handle, 0) || fwrite($handle, $json . PHP_EOL) === false || !fflush($handle)) {
            throw new RuntimeException('Storage write failed.');
        }
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

$errors = [];
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$formType = $requestMethod === 'POST' ? cleanField('form_type', 30) : '';
$returnPage = $formType === 'contact' ? 'contact.html' : 'register.php';
$successMessage = '';

if ($requestMethod !== 'POST') {
    http_response_code(405);
    $errors[] = 'Submit the registration or contact form using POST.';
} elseif ($formType === 'registration') {
    $name = cleanField('name', 100);
    $email = strtolower(cleanField('email', 190));
    $phone = cleanField('phone', 20);
    $course = cleanField('course', 100);
    $year = cleanField('year', 1);
    $gender = cleanField('gender', 20);
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!preg_match('/^[\p{L}\p{M}][\p{L}\p{M} .\'-]{1,99}$/u', $name)) $errors[] = 'Enter a valid full name (2 to 100 characters).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (!preg_match('/^[6-9][0-9]{9}$/', $phone)) $errors[] = 'Enter a 10-digit Indian mobile number starting with 6, 7, 8, or 9.';
    if (!in_array($course, ['BCA', 'BSc IT', 'BTech CSE', 'BTech IT', 'BCom', 'BA'], true)) $errors[] = 'Choose a valid course.';
    if (!in_array($year, ['1', '2', '3', '4'], true)) $errors[] = 'Choose a valid year of study.';
    if (!in_array($gender, ['male', 'female', 'other', 'prefer-not'], true)) $errors[] = 'Choose a gender option or Prefer not to say.';
    if (!is_string($password) || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9\s])\S{8,}$/', $password)) $errors[] = 'Use at least 8 password characters with uppercase, lowercase, a number, and a symbol.';
    if (!is_string($confirm) || $confirm !== $password) $errors[] = 'Password and confirmation do not match.';
    if (($_POST['terms'] ?? '') !== 'accepted') $errors[] = 'Accept the demonstration form notice to continue.';

    $dob = cleanField('dob', 10);
    if ($dob !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) $errors[] = 'Enter a valid date of birth.';
    $address = cleanField('address', 1000);
    $department = cleanField('department', 80);
    $allowedHobbies = ['reading', 'sports', 'music', 'coding'];
    $submittedHobbies = $_POST['hobbies'] ?? [];
    $hobbyStrings = is_array($submittedHobbies) ? array_filter($submittedHobbies, 'is_string') : [];
    $hobbies = array_values(array_intersect($allowedHobbies, $hobbyStrings));

    $payload = [
        'name' => $name, 'email' => $email, 'mobile' => $phone, 'course' => $course,
        'year' => (int)$year, 'gender' => $gender, 'date_of_birth' => $dob ?: null,
        'department' => $department ?: null, 'address' => $address ?: null, 'interests' => $hobbies,
        'terms_accepted' => true,
    ];
    $successMessage = 'Registration details were validated and saved to the local JSON file. The password was checked but is never stored.';
} elseif ($formType === 'contact') {
    $name = cleanField('name', 100);
    $email = strtolower(cleanField('email', 190));
    $subject = cleanField('subject', 120);
    $message = cleanField('message', 3000);
    if (!preg_match('/^[\p{L}\p{M}][\p{L}\p{M} .\'-]{1,99}$/u', $name)) $errors[] = 'Enter a valid full name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($subject) < 3) $errors[] = 'Subject must contain at least 3 characters.';
    if (strlen($message) < 10) $errors[] = 'Message must contain at least 10 characters.';
    $payload = ['name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message];
    $successMessage = 'Your contact message was validated and saved to the local JSON file.';
} else {
    http_response_code(400);
    $errors[] = 'The submitted form type is not recognized.';
}

if (!$errors && $successMessage !== '') {
    try {
        saveSubmission($formType, $payload);
    } catch (Throwable $exception) {
        error_log('StudentHub Practical 7 storage error: ' . $exception->getMessage());
        http_response_code(500);
        $errors[] = 'The information passed validation, but could not be saved. Check storage permissions and try again.';
    }
}
if ($errors && http_response_code() < 400) http_response_code(422);
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$isSuccess = !$errors;
?>
<!doctype html>
<html lang="en-IN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StudentHub - Form Submission</title>
  <link rel="stylesheet" href="../PRACTICAL-03/css/style.css">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to main content</a>
<header><h1>🎓 StudentHub</h1><p>Secure form processing demonstration</p></header>
<nav aria-label="Primary navigation"><a href="../PRACTICAL-02/index.html">Home</a><a href="../PRACTICAL-02/register.php">Register</a><a href="../PRACTICAL-02/contact.html">Contact</a><a href="../PRACTICAL-02/dashboard.php">Dashboard</a></nav>
<main id="main-content">
  <section class="hero"><div class="heroText">
    <p class="smallTitle"><?= $isSuccess ? 'SAVED SUCCESSFULLY' : 'PLEASE REVIEW YOUR FORM' ?></p>
    <h2><?= $isSuccess ? 'Thank you, your form is complete.' : 'We could not process this submission.' ?></h2>
    <?php if ($isSuccess): ?><p><?= $escape($successMessage) ?></p><?php else: ?><ul><?php foreach ($errors as $error): ?><li><?= $escape($error) ?></li><?php endforeach; ?></ul><?php endif; ?>
    <div class="heroButtons"><a class="button-link" href="../PRACTICAL-02/<?= $escape($returnPage) ?>">Return to <?= $formType === 'contact' ? 'Contact' : 'Registration' ?></a><a class="button-link secondary-link" href="../PRACTICAL-02/index.html">StudentHub Home</a></div>
  </div><aside class="heroCard"><span aria-hidden="true">🔒</span><h3>Local practical demo</h3><p>Submitted values are validated and sanitized on the server. Credentials are not stored.</p></aside></section>
</main>
<footer><p>© 2026 StudentHub · Practical 7</p></footer>
</body>
</html>
