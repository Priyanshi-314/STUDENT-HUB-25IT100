# Practical 12: Event Management CRUD with Poster Upload and File Validation

## Objective
Provide administrators with database-backed event creation, viewing, updating, and deletion, with optional poster uploads validated by file type and size. Category and open/closed filters implement the practical's intermediate extension.

## Project connectivity
- Open `PRACTICAL-02/admin.php` and choose **Manage Events**, or visit `PRACTICAL-12/events.php` while signed in as the administrator.
- The event manager uses the existing session role checks, MySQLi connector, and common green Practical 3 stylesheet.
- Event records use the shared `studenthub.events` table. The new migration adds poster and open/closed status fields without replacing the existing sample event records.

## Database setup
1. Import Practical 8's schema and Practical 9's authentication schema if they are not already installed.
2. Import `PRACTICAL-12/sql/event-management.sql` once. It adds `status` and `poster_path` to `events` and creates an event-change audit table.

## Run and demonstrate
Start the PHP server from the project root:

    C:\xampp\php\php.exe -S 127.0.0.1:8000 -t . PRACTICAL-07/router.php

Sign in as the local admin and open `http://127.0.0.1:8000/PRACTICAL-12/events.php`.

- Add, edit, and delete event records; each operation uses a MySQLi prepared statement and a success/failure message.
- Filter by event category and open/closed status.
- Optionally upload a JPEG, PNG, or WebP poster up to 2 MB. PHP checks the upload result, MIME type using Fileinfo, and file size. The server assigns a random filename; the application never trusts the original path. Existing poster files are removed when replaced or when their event is deleted.
- The page requires an administrator session and validates CSRF tokens. Event changes are recorded in `event_management_audit`.
- `uploads/.htaccess` disables directory listing and blocks common executable file extensions on Apache. The PHP application still enforces image-only validation.

## Practical 12 key questions
1. Add, update, and delete use prepared INSERT, UPDATE, and DELETE operations; event changes and audit records are committed together.
2. The form and list are connected to the existing MySQL `events` table via the shared MySQLi connection.
3. Posters are limited to JPEG/PNG/WebP, checked with Fileinfo, and capped at 2 MB; random names avoid trusting user filenames.
4. Validation, database errors, file errors, and successful operations produce visible status messages.

## Screenshots
Save the CRUD list, successful event save, and invalid poster upload screenshots in this practical's `screenshots/` folder for submission.

## Test cases for submission
- Add an event without a poster and confirm it is saved and listed.
- Add an event with a valid JPEG, PNG, then WebP poster no larger than 2 MB; confirm each accepted image is displayed.
- Try an unsupported file type and a file larger than 2 MB; confirm each is rejected and no event/poster is saved.
- Edit event details and replace the poster; confirm the new image is shown and the old upload is removed.
- Remove a poster without deleting its event, then delete an event and confirm its poster is removed.
- Filter by category and by open/closed status, and confirm the list changes accordingly.
- Confirm a success or failure message appears and the admin audit table records completed changes.
- Open the manager as a student or signed-out visitor; confirm administrator-only access is enforced.
