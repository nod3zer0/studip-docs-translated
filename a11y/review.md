---
title: Criteria for accessibility reviews
slug: /a11y/review
sidebar_label: Reviews
---


General information on accessibility can be found on the [Accessibility](start) page.


Accessibility tests are divided into several components:


1. testing for sufficient contrast and labeling of GUI elements
2. testing the keyboard operability of page elements
3. testing the usability of page elements with screen readers


The questions of the individual test steps are listed below.


## Test for sufficient contrast and identification of GUI elements


- Do the foreground and background colors contrast sufficiently with each other?
- Are links highlighted appropriately?
- Is the displayed information recognizable even without colors?


## Check for keyboard operability of page elements


Can a new or changed page element be operated using the keyboard?


- Can a link, button or other interactive element be accessed via TAB?
- Can the usual keys be used to control interactive elements?
    - Link, button: Enter key to call up/resolve
    - Checkbox, radio button: Space bar to select/deselect
    - Select box: Arrow keys to select an entry


## Checking the usability of page elements with screen readers


Is a new or changed page element correctly labeled for screen readers?


- Are form elements and action elements read out correctly?
    - Buttons as "switches"?
        - A button changes a part of the page or triggers an action on the current page. Examples: Expanding areas, deleting or sorting elements in a list.
    - Links as a "link"?
        - A link calls up a new page in the main area or in the dialog or the link is an anchor to a position on the current page.
    - Select field as a "selection field"?
    - ...
- Are icons that are only decorative elements invisible to screen readers?
- Are images or icons that provide important information provided with an alternative text?


Testing is mainly carried out with the combination of JAWS and Microsoft Edge. When testing with other screen readers and browsers, their market shares should be taken into account so that a tested solution works for as many people as possible: https://webaim.org/projects/screenreadersurvey9/
