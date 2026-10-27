# Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity

## Objective
Enhance the existing StudentHub Home page at `PRACTICAL-02/index.html` with accessible JavaScript interactions. Practical 4 does not introduce a separate HTML page. Its JavaScript and interaction-specific CSS stay in this practical folder and are linked only by the existing Practical 2 Home page.

## Interactions on the existing Home page
- Responsive hamburger menu with an updated `aria-expanded` state
- Three-card content slider with previous/next controls and a live status message
- Collapsible FAQ answers with accessible button state
- Native announcement dialog with focus handling and Escape-to-close
- Dismissible StudentHub notice banner
- Light/dark theme switcher that remembers the choice in `localStorage` using `studenthub-theme`

## Files
- `PRACTICAL-02/index.html` — existing Home page, with the interaction controls
- `css/interactions.css` — Practical 4 component styles, layered over Practical 3's shared theme
- `js/script.js` — DOM selection, event listeners, state updates, and theme persistence

## Run
Open `PRACTICAL-02/index.html` in a modern browser. Its Practical 3 stylesheet and this practical's JavaScript/CSS links load from their respective folders. No build tool or external dependency is required.

## Viva notes
`querySelector` and `querySelectorAll` select DOM elements. `addEventListener` connects controls to functions. The functions update text, classes, `hidden`, and ARIA attributes. `localStorage` stores the theme name so it can be restored on reload.

## Submission
Capture a Home page desktop and mobile screenshot in `PRACTICAL-04/screenshots/`. Manually demonstrate the menu, slider, FAQ, announcement, notice dismissal, and persistent theme choice.
