# Practical 14 — Admin dashboard, RBAC, audit

Import `PRACTICAL-14/sql/admin-audit.sql` into `studenthub` using phpMyAdmin **once** after Practical 9. Also ensure Practical 11 and 12 migrations have been imported.

Open `http://localhost/PRACTICAL-14/dashboard.php` after signing in as admin. Live counts query the same students, events, registrations and users tables. The dashboard shows existing auth and event audit logs, and writes new admin dashboard visits to `admin_activity_log` when you press the record button.
