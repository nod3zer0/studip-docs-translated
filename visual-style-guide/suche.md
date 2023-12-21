---
title: Search
sidebar_label: Search
---

* Objects that can be searched for (and what to do with them)
    * People
        * Address book: add to address book; add to a group in the address book
        * Global settings: Change global properties
        * Events: add to an event as a lecturer/tutor
        * Events: add as participant to an event
        * Messaging: add to recipient list
        * General person search: call up personal homepage, send message
        * Facilities: add to the list of employees
        * Resource management: enter as local/global resource admin
    * Events
        * Event search for students: switch to individual events (= switch to overview page); call up personal homepage of individual events
        * Event search for admins: select event for editing, perform various "batch actions" (visibility, lock levels, etc.)
        * In the archive: various actions (call up details, download file collection, permanently delete, ...)
        * Event hierarchy: add event(s) to study areas
    * Institutions
        * Switch to the institution in Stud.IP
        * Go to the website of the institution
        * Write an e-mail to the contact person
    * Resources
        * Various actions (which you can also perform on resources: Call up occupancy plan, edit properties, ...)
    * Areas (in news, voting and evaluation management)
        * Create new voting/new test in an area
        * Select an area (to subsequently create/edit a news item there)
    * Forum posts
        * Various actions (which you can also perform on forum posts: reply, quote, edit, ...)
        * Public evaluation templates
        * Add to your own evaluation templates
    * Wiki pages
        * Call up wiki pages
    * Literature (= entries in literature lists)
        * Call up details
        * Add to watch list
        * Switch to the entry in the external catalog (OPAC)
* Formulate the search query
    * Variant 1: only a single-line text field
    * Variant 2: text field(s) plus additional form fields (e.g. event search, person search, resource search)

* Auto-complete
    * ...

* Triggering the search query
    * Variant 1: Click on the "magnifying glass" icon (e.g. search for lecturers on admin_seminare1.php, search for a desired room on admin_room_request.php)
    * Variant 2: Click on the "Start search" button (e.g. search for events on sem_portal.php, search for resources, search in the archive,
    * Variant 3: Click on the "Search" button (e.g. search for people on browse.php, search for literature on lit_search.php)

## Possible guidelines
* Search terms are always entered in a single-line text input field (input type="text").
* If the search form only consists of this text field, the search is triggered by clicking on a magnifying glass icon to the right of the text field.
* If the search form consists of several form fields (e.g. to limit the search results), the search is always triggered by a button with the text "Start search".

## Display of search results

### State of play
**Variant 1:** Drop-down list replaces input field (examples: Assigning a lecturer to a course on admin_seminare1.php; selecting a desired room when formulating a room request)
** **Variant 2:** Expandable (or already expanded) list elements (e.g. resource search, literature search)
**Variant 3:** Simple list (e.g. event search sem_portal.php, person search browse.php and_new_user_md5.php)
**Variant 4:** Expanded elements within a hierarchical list of otherwise collapsed elements (search for institutions institut_browse.php)
* **Variant 5:** Multi-line select box (free search for persons in the group management in institutions, group management in the address book)

### Possible guidelines
* The number of elements found should be displayed above the search results.
* Differentiation according to the intended use of the results
    * Selection of exactly one element in the results list** (e.g. assignment of a lecturer to a course, selection of a desired room in a room request)
    * Selecting several elements of the results list** (e.g. assigning courses to study areas in the study area administration, assigning persons found by search in the group administration in institutions)
    **Click on exactly one element of the results list to access it** (e.g. event search [sem_portal], person search [browse.php])

### Questions/ideas
* How are the guidelines for search results related to those for element lists? In other words: When should search results be output in the form of element lists, for which the rules defined there then automatically apply?
    * Idea: Search results are always displayed in the form of element lists. (Possibly with defined exceptions, e.g. lecturer search on admin_seminare1.php)
* What can/should the type of display depend on?
    * Intended use of the search results (see above)
    * Type and display of the search form from which the search results originate
    * Space available (e.g. within expandable and collapsible element lists, example: person search within the group administration in institutions)
    * Type of elements searched for (persons vs. events vs. resources vs. ...)
    * Predictability of the hit list (iframe or selectbox for potentially large hit lists)
    * %blue% A matrix with two criteria is conceivable, in which a separate set of display rules is defined for each combination of criteria values
* Scroll
    * Should it be possible to scroll (in principle or according to defined criteria) within the search results?
    * If so, how should the scrolling be displayed? (If applicable, general rules for scrolling function)
