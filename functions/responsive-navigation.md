---
id: responsive-navigation
title: Responsive Navigation
sidebar_label: Responsive Navigation
---


As of Stud.IP 5.3 the responsive navigation has been completely refactored and a full screen mode for the entire system has been realized.

# Use
In Stud.IP there are four levels of resolution-dependent display, these are defined in `resources/assets/stylesheets/scss/breakpoints.scss` (and analogously in `resources/assets/stylesheets/less/breakpoints.less`) and used in `resources/assets/stylesheets/scss/visibility.scss` to control visibility:
- 576px (small)
- 768px (medium)
- 1024px (large)
- 1280px (xlarge)
- 1600px (xxlarge)

Below a resolution of 768 pixels, the responsive view is automatically switched to and the responsive navigation is used accordingly.

There is also a button to switch to full screen mode in the desktop view (>= 768 pixels). The responsive navigation and the adapted page view with hidden sidebar and content bar above each page are also used here.

# Technical details
The responsive navigation was implemented with VueJS. All new components are located under `resources/vue/components/responsive`.

The component is integrated in `templates/header`, but is only displayed when required. "On demand" means here:
- The resolution is smaller than 768 pixels, so the HTML tag automatically gets the class `responsive-display`
- The full-screen mode is active, so the HTML tag is automatically assigned the class `fullscreen-mode`.

When the component is mounted, the help icon is moved to the top blue bar, and a content bar is created (or an existing one in the DOM is reattached) to accommodate the current page title and the icon for showing/hiding the sidebar.

The icon for activating the full screen mode is attached next to the help icon via MountingPortal and also moves to the blue top bar in full screen mode.

The navigation entries are created in the class `lib/classes/ResponsiveHelper.php` and stored as JSON. What is new here is that all your own courses from the current semester (as well as a currently open course for admins and roots) are also directly available as navigation entries including sub-navigation.

The footer is hidden, its navigation can be called up as sub-navigation "Imprint & Information" in the menu.

The site's ski links have also been reorganized. Each skiplink can now specify whether it is valid in full screen mode (default 'true'). All invalid skiplinks are hidden when mounting the responsive navigation and new ones are added that jump to the navigation, exit fullscreen mode or focus the icon to show/hide the sidebar.

# Compact navigation and full screen mode

Stud.IP is a software that tries to offer the full range of functions on different end devices/device sizes and should also be as easy to use as possible for different target groups on these different devices.

**Supported device classes and their characteristics:**

The device classes for which Stud.IP is optimized can be categorized as follows and according to the breakpoints in the responsive design.

**A. Smartphone (medium)**: These devices are characterized by the fact that
the maximum width is very narrow (up to 767 pixels at regular resolution),
scrolling is usually easy, i.e. the pages can be quite long, the devices are operated exclusively by touch, i.e. with a finger, and no complex content is created on these devices. The usual display orientation is upright.

**B. Tablet/small desktop devices (large)**: These devices are characterized by the fact that the maximum width is limited (up to. 1024 pixels at regular resolution),
they are usually operated by touch (mouse operation should also be possible), complex content is rarely created on these devices. The predominant display orientation is landscape, portrait in some applications.

**C. Desktop (xlarge)**: These devices are characterized by a width of more than 1024 pixels and are predominantly operated by mouse. The usual display orientation is landscape.

**Other variants** There are other variants, but so far these have only been minimally optimized for the respective device sizes and have been designed less for specific device classes. These variants have breakpoints for smaller (under 576 pixels/small) and larger (from 1280 pixels/xlarge and from 1600 pixels/xxlarge) displays.

See also the new display levels at https://gitlab.studip.de/studip/studip/-/wikis/Responsive-Navigation.

**Exemplary use cases and assigned user groups:**

In addition to different device classes, there are two important user groups with different use cases. There are sometimes fluid transitions and overlaps between the groups. Ultimately, both groups therefore represent different poles, for each of which there is now a separate presentation mode. Both modes build on each other.

**1. creation and administration of complex content**
- The usual Stud.IP groups for this UseCase are **Admins** and (to a limited extent) also lecturers
- The UseCase is characterized by the fact that extensive content is created or complex content is edited
- Typical elements used are large tables (many elements, many columns, many possible actions) and extensive content consisting of several media objects (continuous text, film, inactive elements) which are also structured in themselves (e.g. by a table of contents or headings)
- Full operation (especially navigation) of the system and use of communication functions is still expected and remains possible
- Functions/system areas can be changed, sidebar actions can be executed and communication functions can be called up
- Important requirements: As much space as possible for the elements to be edited while still allowing navigation

**2. consumption and interaction with content without changing it ("learning")**
- The usual Stud.IP test levels of this group are **students** and (to a limited extent) also teachers
- The use case is characterized by the fact that content is received over a longer period of time (texts are read, films are watched)
- Typical elements are extensive continuous texts, media objects (audio or video) and interactive elements (questions, quizzes, exams)
- Complete operation takes a back seat; the same context is usually presented over a longer period of time
- The central goal is: As little (visual) distraction as possible from elements of the system that are not needed for a longer period of time (and also no distraction from interaction elements that bind attention) while at the same time leaving as much space as possible for interaction
- There is no space left for the joint display of content and operating elements/navigation
- Key requirement: Hide all disruptive or distracting elements and leave as much space as possible for the content


**Display mode to support the two use cases**


**I. Compact navigation**

This mode is available on all pages to support usage group 1 (admins/teachers) throughout the creation and editing process. The mode is optimized for all device classes (A, B, C).

Characteristics of the compact navigation are:

- The blue header remains displayed and allows access to the full navigation ("hamburger menu") in all device classes
- The browser itself is visible as normal (as are all other windows/elements of the operating system)
- The footer is hidden, all navigation elements of the footer are part of the hamburger menu
- In order to create as much space as possible for operation, the sidebar is visible via a fade-in icon or invisible again, hidden by default

It should be noted that for long pages on device classes B and C, responsive navigation is also used by displaying the hamburger menu when scrolling down in normal mode in order to enable fast switching without scrolling up.

**II. full screen mode**

The UseCase 2 optimized focus mode can only be activated in version 5.3 on pages with the new ContentBar (currently Courseware, Wiki and material in the OER Campus), as it can be assumed that this UseCase can only be used on pages on which content is actively received. The full-screen mode is particularly optimized for class B (tablets), as it is assumed that learning and reading works best in this mode. However, the mode can also be used on desktops (C), although it may be less useful.

Characteristics of the activated full screen mode are

- The blue header is hidden to both maximize space and prevent (accidental/active) navigation.
- The browser's own full-screen mode is also activated, as it can be assumed that no distractions from other tabs or control elements or accidental tapping (on touch devices, class A and B) can be prevented. All other windows/elements of the operating system are thus hidden (as far as possible).
- The sidebar is hidden and cannot be activated
- The footer is not visible
- The only remaining control elements outside the content are all elements that allow operation within the content of the selected context (e.g. table of contents)

The activation of the full screen mode is either visible after activating the compact navigation as an icon on the right in the blue lines (and thus also represents a second level of the reduced display) or can also be activated from the action menu in the pages that use the mode.
