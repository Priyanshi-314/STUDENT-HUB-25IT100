# Practical 10: Secure Login/Logout with Sessions, Role-Based Access, Timeout, and Remember-Me Token

## Objective
Connect the existing StudentHub Login page to the Practical 9 `users` table. Verify credentials with `password_verify()`, regenerate the session ID on authentication, protect the existing student and admin dashboard pages by role, end idle sessions after 20 minutes, and support a secure optional remember-me cookie.

## Existing page connectivity
- The existing `PRACTICAL-02/login.php` page posts to `PRACTICAL-10/login.php` and accepts either username or email.
- The existing `PRACTICAL-02/dashboard.php` requires a student session. The existing `PRACTICAL-02/admin.php` requires an admin session. Unauthorized users are redirected to login; users with the wrong role are redirected to their own dashboard.
- Both dashboards include a CSRF-protected POST sign-out control. Logout removes the server session and the remember token.
- Session cookies are HTTP-only and SameSite=Lax; Secure is enabled when the site uses HTTPS. CSRF tokens protect login and logout.
- The optional persistent cookie contains a random selector and validator. MySQL stores only the SHA-256 validator hash, and a valid token rotates when it is used. No JWT-like token is used.
- Successful password sign-in updates `last_login`. Login, logout, timeout, and remember sign-in events are written to the auth audit table.

## Setup and run
1. Import `PRACTICAL-08/sql/studenthub.sql` once to create the `studenthub` database.
2. Import `PRACTICAL-09/sql/secure-auth.sql` to create authentication tables and sample users. This file may be re-imported safely.
3. Start MySQL and run from the project root:

       C:\xampp\php\php.exe -S 127.0.0.1:8000 -t . PRACTICAL-07/router.php

4. Open `http://127.0.0.1:8000/PRACTICAL-02/login.php`.

## Synthetic demo accounts
- Student: username `aarav.student` or email `aarav.patel@example.edu`; password `StudentHub@123`.
- Admin: username `hub.admin` or email `admin@studenthub.local`; password `AdminHub@123`.

These are local practical demo credentials. Replace or remove them before using a public deployment.

## Required post-lab test cases
1. Valid student login redirects to the student Dashboard and changes the session ID.
2. Valid admin login redirects to the Admin page.
3. A wrong password shows a generic failure and reveals no account details.
4. Direct unauthenticated access to either protected dashboard redirects to Login with an access notice.
5. A student attempting Admin and an admin attempting student Dashboard are sent to their role's own page.
6. POST logout destroys the session; the protected Dashboard then redirects to Login.
7. After 20 minutes without activity the session expires and Login shows a timeout message.
8. Remember-me persists across a browser session, stores only a token hash in the database, rotates the token after use, and is revoked by logout.

## Viva notes
Explain sessions and session fixation, session ID regeneration, role authorization on the server, idle timeout, password verification, cookie flags, high-entropy selector/validator tokens, token hashing/rotation, CSRF protection, and the last-login field.

## Screenshots
The `screenshots/` folder contains `login-success.jpg` (student dashboard), `login-failed.jpg` (generic credential error), `logout.jpg` (signed-out notice), `timeout.jpg` (20-minute inactivity notice), and `unauthorized-access.jpg` (sign-in required notice after an unauthenticated protected-page request).
