# Practical 11: Student Management CRUD with Search and Filter

## Objective
Create an administrator-only student management page to add, view, edit, delete, search, and filter StudentHub student records. Every database operation uses MySQLi prepared statements and displays clear feedback.

## Project connectivity
- Open the existing `PRACTICAL-02/admin.php` and use **Manage Students**, or open `PRACTICAL-11/students.php` while signed in as the administrator.
- The page uses the existing `PRACTICAL-10/session.php` role/session setup, `PRACTICAL-09/db.php` MySQLi connector, and the common green `PRACTICAL-03/css/style.css` theme.
- Course search/filter uses the existing `students.program` field; the Practical 8 schema calls the field `program` rather than `course`.

## Database setup
1. Start MySQL in XAMPP.
2. Import `PRACTICAL-08/sql/studenthub.sql` and then `PRACTICAL-09/sql/secure-auth.sql` if they have not already been imported.
3. Import `PRACTICAL-11/sql/student-management.sql` once. It adds a status field for optional deactivate/restore actions and inserts twenty synthetic test records. Together with Practical 8's starter records, the table meets the minimum twenty-record dataset requirement.

## Run and demonstrate
Start the PHP server from the project root:

    C:\xampp\php\php.exe -S 127.0.0.1:8000 -t . PRACTICAL-07/router.php

Sign in using the local admin demo account documented in Practical 10, then visit `http://127.0.0.1:8000/PRACTICAL-11/students.php`.

- Add and edit records with the form.
- Search by student name, email, or course/program. Use the program and status filters and pagination to narrow results.
- Export the currently filtered records as CSV.
- Deactivate and restore records (soft-delete extension), or use **Delete permanently** to run a real DELETE statement. Permanent deletion is refused by MySQL when the student still has event registrations, preserving the foreign-key relationship.
- Every form is protected by a session CSRF token. Values are escaped in the HTML and database inputs are parameterized.

## Practical 11 key questions
1. SELECT, INSERT, UPDATE, and DELETE are handled by MySQLi prepared statements. Deactivation/restoration uses UPDATE; permanent deletion uses DELETE.
2. Search checks name, email, and program/course. The program dropdown provides an additional course filter.
3. A browser confirmation is required before a permanent-delete request is submitted; the server still enforces authorization and CSRF validation.

## Screenshots
Save demonstration screenshots in this practical's `screenshots/` folder. Capture the list/search view and add/edit/delete feedback for submission.

## Test cases for submission
- Add a valid new student; confirm the row appears in the list.
- Submit an email already present; confirm the database rejects it with a clear message.
- Search separately for a name, email, and a course/program; then apply the program and active/inactive filters.
- Edit a record and confirm the saved value appears.
- Deactivate a record and restore it from the inactive filter.
- Permanently delete a record without registrations after the confirmation prompt; try one with registrations and confirm the foreign key prevents deletion.
- Export the current filtered set as CSV and check its header and rows.
- Open the page as a student or signed-out visitor; confirm administrator-only access is enforced.
