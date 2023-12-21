---
title: Page structure
sidebar_label: Page layout
---

Each Stud.IP page is structured in the same way and contains the following elements:

## Header

Introductory line containing a system-wide search. If the main navigation is moved out of view by scrolling, it is included in compact form in the header. The header can be extended by the operator.

## Main navigation

It introduces each page and is the fixed navigation element that connects the system areas. The composition of the header depends on the global rights of the user. The header represents the 1st navigation level.
Scopes: Scopes connect certain functions of a main area, such as the messaging system or all functions within events. A scope has an area or an icon in the main navigation and refers to several functions. A scope always represents or contains the 2nd navigation level.

## Sidebar
This is located on the left-hand side of the screen and contains several widgets in a defined form, such as
Navigation (of the selected function in the selected scope), actions, views, export and possibly other widgets.

### Navigation widget
This widget always appears as the first widget and, if available, represents the 3rd navigation level.

### Content area
All content is displayed here. A content area is made up of tables and ContentBoxes
or input fields. There are fixed elements for the content area from which it must be designed.
The content area includes all content that is displayed or edited by the respective function.
All object manipulations and the content display take place in this area. It is crucial that only objects (which are marked as such, see later), methods that manipulate them and various other (meta) information about these objects should actually be placed in this area. Explanatory texts, references to other parts of the system and other navigation elements must not appear in this area.
Standardized graphic elements should be used for the design; functions that already exist in the system in a similar way must be based on them in terms of operation. In the content area in particular, the declared aim must be to work with familiar elements in order to offer the user a familiar environment, even with new functions.

Some basic tips for designing the content area:
* Avoid placing text freely in the content area of pages. There are a number of graphic design options, which are described below, with which you can mark any content within the content area and separate it from other objects.


## Footer
This contains further links and references, which can be expanded by the operator in the same way as the header.

//TODO: Screenshot of a typical page

The actual page consists of a sidebar and content area. Both areas are introduced by a
page title. In contrast to the design up to Stud.IP 3.5, the content area now does not have its own title
(previously partially designed as an h1 object).

## Page title

* The title must have the same name as the entry in the navigation in the sidebar
* For events, the name of the event is automatically displayed (the same applies to the institution area for the selected institution)

## Further specifications

* Each page must contain a sidebar.
* Each sidebar contains at least one decorative image.
* Access to help (question mark icon) is provided on the right-hand side as the end of the page title.
* Sidebar actions (found in the widget of the same name) are executed in dialogs.

Further information on the sidebar: see section [Sidebar](seitenaufbau#sidebar)
