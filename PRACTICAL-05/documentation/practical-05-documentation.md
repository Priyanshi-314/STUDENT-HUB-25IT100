# Practical 5: Registration Form with Frontend Validation and User-Friendly Error Handling

## Objective
Extend the existing StudentHub Register page with the required accessible fields, JavaScript regular-expression validation, password strength feedback, and clear field-level errors.

## Required fields and behavior
- Full name, username, email, mobile number, password, confirm password, course, year, gender, and account consent are required.
- HTML5 input types, labels, autocomplete hints, and required attributes support accessible entry.
- JavaScript validates the name, email, 10-digit Indian mobile number, and password pattern. Passwords need at least eight characters, uppercase and lowercase letters, a number, and a symbol.
- Errors appear beside the relevant fields and are announced to assistive technology. Validation runs on submit and updates while correcting errors.
- A strength indicator reports weak, fair, or strong. A successful frontend check submits to Practical 9 for matching server-side validation and secure database insertion.

## Files and connectivity
The existing page is PRACTICAL-02/register.php. It keeps the Practical 3 stylesheet and loads css/registration.css and js/validation.js from this practical. There is no duplicate Register page.

## Run and demonstrate
Open PRACTICAL-02/register.php in a modern browser. Try blank fields, malformed email, invalid mobile, mismatched passwords, weak and strong passwords, missing gender/terms, then valid sample values. Practical 5 validates in the browser. With Practical 9 configured, valid submissions are posted to the MySQLi registration handler; passwords are hashed before storage.

## Validation test cases

| Case | Sample action | Expected result |
|---|---|---|
| Empty submission | Submit without completing required fields | Field-specific errors appear and focus moves to the first invalid field |
| Invalid email/mobile | Enter malformed email or a mobile number not matching the 10-digit pattern | Relevant field error is shown |
| Weak password | Omit one or more strength requirements | Strength indicator reports weak/fair and submission is blocked |
| Password mismatch | Enter different password and confirmation values | Confirmation error is shown |
| Missing course/year/gender/terms | Leave any required choice unselected | Error appears beside the relevant control |
| Valid demonstration | Sample Student, student@example.edu, 9876543210, B.Tech IT, year 2, Female, StudentHub5!, and accepted notice | Practical 5 validation succeeds, then Practical 9 displays a server success response; only a password hash is stored |

Captured evidence: screenshots/registration-invalid.png and screenshots/registration-valid.png.
## Viva notes
Regular expressions validate expected patterns. Event listeners respond to input, blur, change, submit, and reset. aria-invalid, nearby errors, labels, and a live status message support accessible feedback. This client-side check is a learning demonstration, not production account security.

## Screenshots
Store valid and invalid form captures in PRACTICAL-05/screenshots/.
