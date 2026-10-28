# StudentHub

## Project overview
StudentHub is a semester-long student portal project for the Web Development Frameworks (ITUE203) practicals. It begins with requirements and navigation planning, then builds independent, progressively enhanced practicals.

## Purpose
This repository organizes the official practical work into separate folders. Practicals 1–6 are implemented locally; Practicals 7–14 remain pending. GitHub publication is pending repository write access.

## Technologies across the practicals
The official list uses HTML5, CSS3, CSS Grid, Flexbox, optional Bootstrap/Tailwind, JavaScript ES6+, JSON, Fetch API, PHP, CSV/JSON file storage, MySQL, MySQLi, PDO, sessions, and Git/GitHub. Only technologies required by each practical should be added to its folder.

## Project structure

```text
STUDENT HUB/
├── README.md
├── PRACTICAL-01/  # planning artifacts and documentation
├── PRACTICAL-02/  # 11 static semantic HTML5 pages
├── PRACTICAL-03/  # responsive CSS Grid/Flexbox pages and documentation
├── PRACTICAL-04/  # interaction CSS/JS used by the Practical 2 Home page
├── PRACTICAL-05/  # registration CSS/JavaScript and practical documentation
├── PRACTICAL-06/  # JSON data, browser CSS/JavaScript, and documentation
├── PRACTICAL-07/  # reserved; implementation pending
├── PRACTICAL-08/  # reserved; implementation pending
├── PRACTICAL-09/  # reserved; implementation pending
├── PRACTICAL-10/  # reserved; implementation pending
├── PRACTICAL-11/  # reserved; implementation pending
├── PRACTICAL-12/  # reserved; implementation pending
├── PRACTICAL-13/  # reserved; implementation pending
├── PRACTICAL-14/  # reserved; implementation pending
```

Practicals 7–14 remain reserved for later implementation. Completed practicals have separate documentation and screenshots folders; this root README is the single common README.

## Official practical list and status

| No. | Official practical title | Brief description | Status |
|---:|---|---|---|
| 1 | Project Initiation, Requirement Analysis, Sitemap, Wireframe, and GitHub Setup | Initiate the semester-long StudentHub portal: define scope, roles, modules, navigation, at least 10 pages; create sitemap, low-fidelity wireframes, folder structure, README, and GitHub repository. | Ready (planning artifacts complete; GitHub publication pending access) |
| 2 | Semantic HTML5 Pages with Accessibility-Ready Structure | Create at least 10 linked static StudentHub pages using semantic HTML5 and accessibility-friendly structure. | Implemented (11 pages linked to the shared Practical 3 stylesheet) |
| 3 | Responsive UI Design using CSS Grid, Flexbox, and Bootstrap/Tailwind | Design mobile-first responsive layouts using CSS Grid and Flexbox; Bootstrap or Tailwind is optional. | Implemented (five responsive pages; Grid/Flexbox checks passed; screenshots pending) |
| 4 | JavaScript DOM Manipulation, Event Handling, and UI Interactivity | Add an FAQ toggle, modal, content slider, dismissible notification, hamburger menu, and persistent light/dark theme. | Implemented on the existing Practical 2 Home page (JavaScript syntax passes; manual interaction screenshots pending) |
| 5 | Registration Form with Frontend Validation and User-Friendly Error Handling | Extend the existing Register page with required fields, regex validation, password strength, and accessible error handling. | Implemented and verified on the existing Practical 2 Register page (valid/invalid screenshots saved) |
| 6 | Rendering External JSON Data using Fetch API, Search and Filter | Fetch events.json, students.json, and faqs.json (15 records each); render with search, filter, sorting, pagination, and loading/error states. | Implemented and verified on the existing Practical 2 Events page (local HTTP access required; screenshots saved) |
| 7 | PHP Form Processing with Server-Side Validation and CSV/JSON File Storage | Process PHP POST forms with server-side validation and sanitization; save records as CSV or JSON. | Pending |
| 8 | MySQL Schema Design, ER Model, PDO Connectivity, and Prepared Statements | Design a normalized StudentHub MySQL schema and connect PHP using PDO and prepared statements. | Pending |
| 9 | Secure User Registration with Database Insert, Duplicate Email Check, and Password Hashing | Implement secure registration with MySQLi, duplicate checks, consistent validation, and password_hash(). | Pending |
| 10 | Secure Login/Logout with Sessions, Role-Based Access, Timeout, and Remember-Me Token | Implement secure PHP session login/logout, role-based access, timeout, and optional remember-me token. | Pending |
| 11 | Student Management CRUD with Search and Filter | Build student add/view/edit/delete, search, and filter with MySQLi and clear feedback. | Pending |
| 12 | Event Management CRUD with Poster Upload and File Validation | Build admin event CRUD and validate poster type and file size on upload. | Pending |
| 13 | PHP JSON API and AJAX/Fetch-Based CRUD without Page Reload | Create PHP JSON API endpoints and Fetch-based asynchronous list/add/update/delete without page reload. | Pending |
| 14 | Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log | Build an admin-only dashboard with dynamic statistics, role-based menus, and audit logging. | Pending |

## Setup requirements
- A modern web browser.
- A code editor such as VS Code.
- For Practical 1 Git is used; GitHub hosting requires the student's own account and repository.
- Practical 2 and 3 pages can be opened directly; Practical 5 validation runs in the browser. Practical 6 uses Fetch API and needs a local HTTP server to load JSON.
- Practical 6 needs Python 3 for the documented local server command, or another local HTTP server; it has no package dependencies.
- Later PHP/MySQL practicals will require a PHP environment such as XAMPP/WAMP/LAMP and MySQL as specified in their documentation.

## Run practicals independently
1. Open a practical folder and follow its `documentation/practical-XX-documentation.md`.
2. For Practical 2, open `PRACTICAL-02/index.html` in a browser. Its pages use semantic HTML and link to the shared stylesheet in `PRACTICAL-03/css/style.css`.
3. For Practical 3, open `PRACTICAL-03/index.html`; its local CSS stylesheet adds the responsive layouts and forest-green/warm-gold theme.
4. For Practical 4, open `PRACTICAL-02/index.html`. Practical 4 interactions run on that existing Home page using CSS and JavaScript from `PRACTICAL-04/`.
5. For Practical 5, open PRACTICAL-02/register.html; validation files are in PRACTICAL-05/.
6. For Practical 6, run py -m http.server 8000 from the project root, then open http://localhost:8000/PRACTICAL-02/events.html; its JSON and browser files are in PRACTICAL-06/.
7. PHP/MySQL practicals need their local server/database environment; consult the applicable practical documentation.

## Screenshots and documentation
Store submission screenshots in the relevant `PRACTICAL-XX/screenshots/` directory. Keep explanations, checklists, and run instructions in that practical's `documentation/` directory. The Practical 1 sitemap and wireframes are in its `assets/` directory.

## Database setup
No database is required for Practicals 1–6. Database schema and setup instructions will be added alongside the applicable later practicals; no database has been invented for this phase.

## Local running notes
Practical 5 validates only in the browser and does not create accounts. Practical 6 uses Fetch API, so access its Events page through a local HTTP server rather than a file URL.
Practical 2 pages link to the shared stylesheet stored in Practical 3. Practical 3 uses a forest-green and warm-gold theme and implements responsive layouts. Forms remain static demonstrations and do not create accounts. Use a local web server when a browser's security rules require HTTP rather than `file://` URLs.

## Practical 15 note
The supplied PDF's summary also lists Practical 15, “Final Integration, Testing, Documentation, Submission, and Local Deployment.” The detailed list contains its title and hours only. This project follows the requested 14-folder scope; Practical 15 is not included in the status table or implemented here.
