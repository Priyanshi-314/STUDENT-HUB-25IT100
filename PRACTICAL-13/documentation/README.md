# Practical 13 — PHP JSON API + AJAX CRUD

Open `http://localhost/PRACTICAL-13/index.php` after logging in as an admin.

Files: `index.php` (UI), `app.js` (Fetch API), `api.php` (admin-only JSON endpoint). Uses `studenthub.students` and `auth_audit_log` from existing migrations. Import Practical 11 SQL first to add the `status` field. No new SQL required.

Test add, search, edit, delete and verify page does not reload. Deletion is blocked by MySQL when registrations reference a student.
