# Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage

## Objective
Demonstrate PHP POST processing for StudentHub registration and Contact data with server-side checks and JSON file storage. Practical 9 later upgrades the existing Register page to secure database registration. The processor validates and sanitizes each submitted field, safely appends valid submissions to a JSON file, and returns clear success or error messages.

## Existing page connectivity
- The existing Contact page posts to this handler. The earlier registration flow used this handler; the connected registration page now uses Practical 9 to create hashed-password accounts.
- PRACTICAL-02/contact.html posts contact messages to the same handler.
- The handler links back to the StudentHub pages and uses the shared Practical 3 stylesheet.
- Password and confirmation are checked on the server but deliberately never written to storage.

## Storage and safety
Validated registration and contact records are appended to storage/submissions.json under an exclusive file lock. Invalid forms are not written. Values are trimmed and stripped of HTML tags before storage and escaped before output. The storage directory has an Apache access rule; router.php also blocks direct JSON requests when using PHP's built-in server.

## Run
From the StudentHub project root, run the PHP 8 server with the Practical 7 router:

    C:\xampp\php\php.exe -S 127.0.0.1:8000 -t . PRACTICAL-07/router.php

Open http://127.0.0.1:8000/PRACTICAL-02/contact.html. Keep this terminal open while submitting. Valid contact submissions appear in the handler response and JSON file. No MySQL connection is needed for Practical 7.

## Test cases
1. Submit registration with missing fields, invalid email/mobile, weak password, mismatched passwords, missing gender, or unaccepted terms: server returns field errors and saves nothing.
2. Submit a valid registration: server saves sanitized student fields and confirms that no password was stored.
3. Submit a contact form with a malformed email or short message: server returns an error and saves nothing.
4. Submit a valid contact message: server confirms the saved record.
5. Request the storage JSON path directly: the server returns not found/denied.

## Screenshots and viva
Captured screenshots: registration-success.png, registration-server-errors.png, contact-success.png, and contact-server-errors.png are stored in PRACTICAL-07/screenshots/. Explain POST handling, server validation, sanitization, JSON encoding, file locking, and escaped output.

