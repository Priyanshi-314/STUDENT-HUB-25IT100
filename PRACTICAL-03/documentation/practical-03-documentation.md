# Practical 3: Responsive UI Design using CSS Grid, Flexbox, and Bootstrap/Tailwind

## Objective
Design responsive layouts for Home, About, Registration, Dashboard, and Events using CSS Grid and Flexbox. This implementation uses native CSS without an optional framework.

## Pages and files
- `index.html` — Home
- `about.html` — About
- `register.html` — Registration
- `dashboard.html` — Dashboard
- `events.html` — Events
- `css/style.css` — shared responsive styles and the shared forest-green, emerald, and warm-gold theme

The pages are independent copies of the corresponding semantic HTML pages from Practical 2. Their shared stylesheet lives inside this practical and is also linked from all Practical 2 pages.

## Responsive approach
The stylesheet starts with a one-column, small-screen layout. At 640px, content cards and form rows move to two columns. At 960px, the home hero and service sections expand into their wider desktop layouts, and content cards use three columns. Flexbox wraps navigation and action buttons. Grid handles card groups, service areas, and form fields. No CSS framework or external dependency is used.

## Run and inspect
Open `index.html` in a browser, or serve the project root with a local static HTTP server and open `PRACTICAL-03/index.html`. Use browser responsive inspection at mobile, tablet, and desktop widths.

## Practical checklist
- [x] Home, About, Registration, Dashboard, and Events pages
- [x] CSS Grid and Flexbox layouts
- [x] Small-screen base styles with tablet and desktop breakpoints
- [x] Distinct theme from the supplied green StudentHub reference
- [ ] Capture mobile, tablet, and desktop screenshots in `screenshots/`
- [ ] Complete final visual review in a browser's responsive mode

## Notes
The registration form is a static demonstration. JavaScript validation and backend account creation belong to later practicals.


