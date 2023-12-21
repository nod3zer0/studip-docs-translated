---
title: Responsive Design
---

So that Stud.IP can also be used for devices with small screen sizes, a few "media queries" are used to provide meaningful "breakpoints" for layout and GUI. These breakpoints differ only in the minimum width of the viewport, i.e. the width of the virtual window into which the (mobile) browser renders the page. Depending on this width, elements can be scaled appropriately or shown or hidden at all.

The following "media query" intervals are used in Stud.IP's LESS/CSS:

```CSS
/* tiny: small smartphones (in portrait format) with a width of less than 576px. */

/* small: smartphones (in landscape format) with a width of 576px or more */
@media (min-width: 576px) { }

/* medium: e.g. tablets with a width of 768px or more */
@media (min-width: 768px) { }

/* large: Desktops with a width of 1200px or more */
@media (min-width: 1200px) { }
```

To make it easier to write these "media queries" in the LESS code, there are special mixins that help you to write responsive rules:

```CSS
.media-breakpoint-tiny-up({ });
.media-breakpoint-small-up({ });
.media-breakpoint-medium-up({ });
.media-breakpoint-large-up({ });

/* Example of use: */

.calhead label {
  cursor: pointer;
  &:hover {
    color: @base-color-40;
  }

  .media-breakpoint-small-down({
    .button();
  });
}

/* or also: */
.media-breakpoint-tiny-down({
     #barTopStudip img {
         height: 33px;
         margin-top: 5px;
    }
});
```

You can also use media queries that go in the other direction (i.e. smaller than a certain size):

```CSS
/* tiny: small smartphones (in portrait format) with a width of less than 576px. */
@media (max-width: 575px) { }

/* small: smartphones (in landscape format) with a width of less than 768px */
@media (max-width: 767px) { }

/* medium: e.g. tablets with a width smaller than 1200px */
@media (max-width: 1199px) { }

/* large: desktops with a width of 1200px or more */
/* not needed, as there is no upper limit */
```

Again, there are LESS mixins:

```CSS
.media-breakpoint-tiny-down({ });
.media-breakpoint-small-down({ });
.media-breakpoint-medium-down({ });
.media-breakpoint-large-down({ });


.hidden-tiny-up
.hidden-small-up
.hidden-medium-up
.hidden-large-up

.hidden-tiny-down
.hidden-small-down
.hidden-medium-down
.hidden-large-down
```


# Display variants

Stud.IP is a software that tries to offer the full range of functions on different end devices/device sizes and should also be as easy to use as possible for different target groups on these different devices.

**Supported device classes and their characteristics:**

**A. Smartphone**: These devices are characterized by the fact that
the maximum width is very narrow (up to 767 pixels at regular resolution),
scrolling is usually easy, so the pages can be quite long,
the devices are operated exclusively by touch, i.e. with a finger, and no complex content is created on these devices. The usual display orientation is upright.

**B. Tablet/small desktop devices:** These devices are characterized by the fact that
the maximum width is limited (up to 1024 pixels at regular resolution),
they are usually operated by touch (mouse operation should also be possible),
complex content is rarely created on these devices. The predominant display orientation is landscape, portrait in some applications.

**C. Desktop/large displays:** These devices are characterized by the fact that
the width has more than 1024 pixels (and is virtually unlimited),
they are predominantly operated by mouse. The usual display orientation is landscape.

See also the new display levels at https://gitlab.studip.de/studip/studip/-/wikis/Responsive-Navigation.

**Supported use cases and assigned user groups:**

In addition to different device classes, there are two important user groups with different UseCases. There are sometimes smooth transitions and overlaps in the use cases between the groups. Ultimately, both groups therefore represent different poles for which there are optimizations.

**1. creation and administration of complex content**
- The usual Stud.IP groups for this use case are **admins** and (to a limited extent) also lecturers
- The use case is characterized by the fact that content is created or complex content is edited over a longer period of time
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
- There is no space left for the joint display of content and control elements/navigation
- Key requirement: Hide all disruptive or distracting elements and leave as much space as possible for the content


**Display mode to support the two use cases**


**I. Full screen mode**

The regular full-screen mode is available on all pages in order to consistently support usage group 1 (admins/teachers) when creating and editing. The mode is optimized for all device classes (A, B, C).

Characteristics of the activated full-screen mode are

- The blue header remains displayed and allows access to the full navigation ("hamburger menu") in all device classes
- The browser itself is visible as normal (and therefore all other windows/elements of the operating system)
- ~~The footer can remain displayed (still to be clarified - for me we don't need to leave it out)~~ The footer is hidden, all navigation elements of the footer are part of the hamburger menu
- In order to create as much space as possible for operation, the sidebar is visible via a fade-in icon or invisible again, hidden by default
- Still to be discussed: Device classes B and C (tablet/desktop) could also show quick search and notification according to the use case. Currently, we have deliberately omitted them, but this may dilute the mode

It should be noted that for long pages on device classes B and C, resonive navigation is also used when scrolling down by displaying the hamburger menu in order to enable fast switching without scrolling up.

**Focus mode **II

The UseCase 2 optimized focus mode can only be activated in version 5.3 on pages with the new ContentBar (currently Courseware, Wiki and material in theOER Campus), as it can be assumed that this UseCase can only be used on pages where content is actively received. The focus mode is particularly optimized for class B (tablets), as it is assumed that learning and reading works best with this. However, the mode can also be used on desktops (C), although it may be less useful.

Characteristics of the activated focus mode are

- The blue header is hidden to both maximize space and prevent (accidental/active) navigation.
- The browser's own full-screen mode is also activated, as it can be assumed that no distractions from other tabs or control elements or accidental tapping (on touch devices, class A and B) can be prevented. All other windows/elements of the operating system are thus hidden (as far as possible).
- The sidebar is hidden and cannot be activated
- The footer is not visible
- The only remaining control elements outside the content are all elements that allow operation within the content of the selected context (e.g. table of contents)
