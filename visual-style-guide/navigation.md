---
title: Navigation
sidebar_label: Navigation
---

The navigation in Stud.IP is organized in several levels. A distinction is made between:

* Main navigation: The header of the system. Complete functional areas are accessed from here. Each
  of these areas corresponds to one of the main points in the sitemap and each of these areas is presented with its own tab system.
* Scopes: Each main area (e.g. profile or community) has a scope that contains the functions of an area.
  of an area. A scope corresponds to the second level in the sitemap. A function may only be attached to a single
  only one position in a scope. This means that each function has a unique assignment to one of the main areas.
* Navigation in the sidebar: Various tasks within a function can be found in the navigation area of the
  sidebar. These lead to a new page call at this point (in contrast to actions in the sidebar).
* Links to other main areas are also possible from the sidebar navigation, but should be avoided.
  Ideally, the navigation of a function should also remain within its own tasks or within the respective scope.
  respective scope. An entry in the sidebar navigation corresponds to the third level in the navigation.

Further information on the structure of the sidebar and its various widgets can be found in the corresponding [section
of the style guide](#Sidebar)

## Header

The header introduces each page and provides access to all core components of Stud.IP:

Mini:kopfzeile.png

Depending on the permission level of the registered user and the system plugins set up, different system areas are made accessible from here.

The header provides the greatest scope for customization to the operator's corporate identity.

The following customizations are possible here:
* Insert your own logo in any position (suggestion: to the right of the Stud.IP logo)
* Insert your own links in the header (suggestion: to the left of the global search)

A few more notes on customizing the header:
* Do not remove the icons from the header, as the icons repeat their design within the system
  appear repeatedly within the system and thus create a link to this navigation
* Do not remove the labeling of the icons, as users receive important explanations via this labeling
  and the text is also available in other system languages.
* Do not change the order of the icons or divide the icons into several lines.
* Do not arrange headers in other places (e.g. as a sidebar). The Stud.IP system sometimes requires a very
  some places a very wide display. The header is best adapted to the system in this form.

## Rides (scopes)
Scopes summarize the function of a main area (e.g. all functions within a course or within the messaging system).

Mini:style_reiter.jpg

Stud.IP automatically adds an "overflow" in a scope (as well as in the main navigation), which lists all icons in a drop-down menu.
drop-down menu contains all icons that no longer fit into the horizontal display (depending on the screen width).
would. When designing new functions, care should be taken to choose the shortest possible names,
so that as many functions as possible fit next to each other. The width of the respective labels determines the width of the scope!

## Sidebar

### Preliminary remark

The concept of the info boxes (Stud.IP versions up to 3.0) has changed fundamentally to the sidebar concept (from Stud.IP
3.1), which incorporates many of the functions from the old info boxes, but does not directly replace them. As part of this
changeover, the 3rd navigation level was moved to a navigation widget in the sidebar as a line below the tabs.

Attach:Style/Sidebar-dafault.jpg

### Short description
The sidebar is located in a fixed position on the left-hand side of a Stud.IP page. The sidebar replaces the info box of older Stud.IP versions and contains at least one, usually several widgets. The sidebar contains the elements of the 3rd navigation level, actions, view options, page-internal search options and export functions within these widgets. If these standard widgets are not suitable, a page can have additional widgets.
The sidebar also has an orientation image in the header area, which contains the name of the page, shows the baisis icon of the respective area and can include an avatar.
Every page should have a sidebar.

### Structure & elements

#### Orientation image
The orientation image is 520px wide and 200px high. Corresponding orientation images are delivered for all basic functions (or based on their icons). In principle, locations can swap these images, but should ensure that the image content and brightness match the surrounding design. If in doubt, the Stud.IP GUI group is available to create additional images or provide tips on how to integrate your own images.

#### Types of widgets
| Type | Description |
| ---- | ---- |
| Navigation | Automatically contains the 3rd navigation level according to the Stud.IP navigation structure (formerly 3rd navigation level below the tab bar). Navigation points jump to other pages but ideally remain within a navigation context (= tab system). The currently selected page is marked with a blue arrow. Navigation points do not show any icons. |
| Actions | Contains actions that influence the content of the current page. Actions always open a dialog and therefore do not leave the current view that the user sees. |
| Views | These contain view options or filters that restrict the content displayed on the respective page. The selected view or filter is marked with a yellow arrow. |
| Search | A search widget is page-specific, i.e. it allows you to search within the content of the page. Ideally, a search here only filters within the content that I can see or reach on this page as a whole. If the content of a page itself provides a search result (e.g. for all search functions in Stud.IP), this search must be implemented outside the sidebar, e.g. in a content box in the content area of the page. A search widget could then theoretically limit the content found dynamically, ideally without reloading the page.
| Export | All functions that specifically offer a file (e.g. PDF, XLS export, CSV file) for download are included here. |

Pages generally start with navigation and actions, followed by other widgets (usually search, views or export). The other widgets can be placed according to the frequency of use of the respective page, the first two positions are fixed in order.


#### Other types of widgets

The following types occasionally appear:

| Type | Description |
| ---- | ---- |
| Settings | For settings that have a direct effect on the page and can be made quickly in the sidebar |
| watchlist | For caching any objects |


### What does not belong in the sidebar

* Help texts: Previously often used in the info box, explanatory or introductory texts about the function of a page no longer belong in the sidebar. The best place for this is the new help tab created in version 3.1, where tours can also be started and the link to the help wiki can be found.
* Forms: With the exception of an input field for the search widget, forms do not belong in the sidebar.

### Other things to note

* There is a fixed API for the sidebar that must be used to create it.
* The conversion of the admin area is expected to take place as part of the work on version 3.2, until then only the navigation has been moved to the corresponding widget.
* Except in the navigation widget, clear and matching icons in the color blue should be used in the sidebar and be clickable. Actions in particular benefit from being easy to find using icons + text.
