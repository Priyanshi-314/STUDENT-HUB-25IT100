# Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe, and GitHub Setup

## Problem scope
StudentHub is a semester-long student portal concept for finding campus information, events, student services, and account tools. This practical produces planning artifacts only; working data, authentication, and server behavior belong to later practicals.

## User roles
- **Visitor:** browse public information, events, FAQ, contact, and feedback pages; register or log in.
- **Student:** use a dashboard and profile after authentication; view portal information.
- **Administrator:** manage the portal through the planned admin area. Access control is deferred to the relevant backend practicals.

## Key modules
Public information (Home, About, Events, FAQ, Contact, Feedback); account entry (Register, Login); student area (Dashboard, Profile); administration (Admin). These are scope-level modules, not functional features implemented in Practical 1.

## Minimum page inventory (10)
Home, About, Register, Login, Dashboard, Events, Profile, Contact, Admin, FAQ, and Feedback. The list includes 11 pages, exceeding the minimum of 10.

## Navigation flow
Home provides links to public pages and account entry. Register and Login lead conceptually to Dashboard. Dashboard links to Profile and Events. Admin is a separate role-restricted destination. The full sitemap is in `../assets/studenthub-sitemap.svg`.

## Wireframe
The low-fidelity desktop and mobile home-page layout is in `../assets/low-fidelity-wireframes.svg`. It defines the page regions and hierarchy without committing to final UI styling.

## URL and browser notes
A URL identifies a resource. Common parts are scheme (for example `https`), host/domain, optional port, path, query string, and fragment. A browser requests or opens a resource, parses HTML into a DOM, applies discovered styles, runs scripts when present, then lays out and paints the page. The practical pages use relative links so they can be opened locally or served from a local web server.

## Project structure and GitHub setup
The root `README.md` is the common project guide. Each practical has its own folder and documentation. Practicals 1 and 2 have independent source folders; 3–14 are reserved until approved. GitHub repository: https://github.com/Priyanshi-314/STUDENT-HUB-25IT100. Practical work is published in separate commits in practical order. After each practical, review the changes and push a descriptive commit.

## Files created
- `../assets/studenthub-sitemap.svg`
- `../assets/low-fidelity-wireframes.svg`
- Root `README.md` and `.gitignore`

## Submission checklist
- [x] Problem scope, roles, modules, navigation, and page inventory
- [x] Sitemap artifact
- [x] Low-fidelity wireframe artifact
- [x] Folder structure and common README
- [x] GitHub repository identified and connected: https://github.com/Priyanshi-314/STUDENT-HUB-25IT100
- [ ] Add practical screenshots to this practical's `screenshots/` folder

