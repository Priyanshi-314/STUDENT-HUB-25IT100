# Practical 6: Rendering External JSON Data using Fetch API, Search and Filter

## Objective
Use Fetch API to load JSON collections and provide dynamic rendering, search, filtering, sorting, pagination, loading feedback, and error handling on the existing StudentHub Events page.

## Data collections
- data/events.json contains 15 sample event records.
- data/students.json contains 15 synthetic profiles using reserved example.edu addresses.
- data/faqs.json contains 15 sample FAQs.

The records are demonstration data, not official events, confirmed schedules, or real student records.

## Files and connectivity
The existing page is PRACTICAL-02/events.html. It loads this practical's css/data-browser.css and js/data-browser.js and fetches JSON from this folder. No separate page or external library is needed.

## Run
Fetch requests may be blocked when opened directly with a file URL. Start a local server at the project root:

    py -m http.server 8000

Then open http://localhost:8000/PRACTICAL-02/events.html. Stop the server with Ctrl+C.

## Demonstrate
Switch among Events, Student profiles, and FAQs. Search, choose a category or program, change sort order, move across pages, try a search with no matches, and use Try again after a missing-file error.

## Viva notes
fetch() retrieves JSON, response.json() parses it, and DOM elements render the records. Array filter, sort, slice, and map support search/filter/sort/pagination. The page shows loading, empty, and error states.

## Screenshots
Captured evidence in PRACTICAL-06/screenshots/: events-page-1.png and events-page-2.png show rendering/pagination; events-search-filter.png shows search and category filtering; events-error-state.png shows Fetch error handling. Browser verification also switched successfully to the student and FAQ collections and checked sorting and the no-results state.

