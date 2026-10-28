# Practical 9: Secure User Registration with Database Insert, Duplicate Email Check, and Password Hashing

## Objective
Connect the existing StudentHub Register page to MySQL through MySQLi. Validate the submitted student details in JavaScript and PHP, reject duplicate email addresses or usernames, insert the new student account, and store only a `password_hash()` result.

## Existing page connectivity
- The existing `PRACTICAL-02/register.php` page keeps its Practical 3 green theme and Practical 5 field-level JavaScript validation.
- A username field was added so users can sign in with either username or email.
- Validated forms POST to this practical's `register.php`; the handler uses MySQLi prepared statements and transaction-safe insertion.
- Registration receives a session CSRF token. The successful insert and audit record are committed together.
- New accounts always receive the `student` role; the form cannot request administrator privileges.

## Database
Import `sql/secure-auth.sql` after `PRACTICAL-08/sql/studenthub.sql`. It creates `users`, `remember_tokens`, and `auth_audit_log`, then adds synthetic demo accounts. Password hashes are seeded, not plain-text passwords. The MySQLi connector reads the same `STUDENTHUB_DB_*` environment settings as Practical 8 and defaults to local XAMPP.

## Run
Start MySQL in XAMPP and run the PHP server from the project root:

    C:\xampp\php\php.exe -S 127.0.0.1:8000 -t . PRACTICAL-07/router.php

Open `http://127.0.0.1:8000/PRACTICAL-02/register.php`. Use sample details, a unique username and email, and a strong password. A successful account is stored in `studenthub.users`; its password is never stored in plain text.

## Required test cases
1. Valid, unique details create one student account and store a verifiable password hash.
2. Reusing an email is rejected without inserting a second account.
3. Reusing a username is rejected without inserting a second account.
4. Invalid email, mobile, course/year, gender, weak/mismatched password, missing terms, or a bad CSRF token is rejected.
5. A successful account insert and its audit event are recorded together.

## Viva notes
Explain frontend versus backend validation, prepared statements, duplicate checks, `password_hash()`/`password_verify()`, CSRF tokens, database transactions, and why the form cannot assign an admin role.

## Screenshots
Screenshots saved in `PRACTICAL-09/screenshots/`: `registration-success.jpg` (successful registration notice), `registration-invalid.jpg` (invalid email rejected), and `registration-duplicate.jpg` (duplicate email rejected).
