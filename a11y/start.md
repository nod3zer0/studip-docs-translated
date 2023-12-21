---
title: Accessibility
slug: /a11y/
sidebar_label: Introduction
---

Accessibility of the learning management system should be achieved for ALL people. For this reason, we no longer use the outdated term "handicapped accessible". Low or even barrier-free accessibility improves the usability of software for everyone and is therefore inclusive.
Not only people with permanent motor, visual or acoustic impairments are affected. There can also always be situational restrictions, a noisy environment, bright light or simultaneous activities when operating the software, such as making a phone call or an injury. This is why we can all benefit from improvements to websites and web-based learning management systems such as Stud.IP. You are not disabled, you become disabled.

A detailed introduction, which also deals with the legal requirements and the procedure in the project and community,
can be read [here](a11y/background.md).

In order to avoid barriers in Stud.IP, it is necessary to observe the following notes on accessibility and to avoid certain constructs in HTML code and CSS rules.

## Declaration on the accessibility of the website/app and feedback mechanism

There must be a separate page describing the extent to which the website or app is accessible and which areas are not (yet) accessible. A sample declaration of accessibility can be found on the following page in the documentation:
[Sample text for the declaration of accessibility](a11y/declaration_template.md)

In addition, it must be possible for users to point out accessibility deficiencies to site operators. Both pages must be accessible from every page. In Stud.IP, these are therefore placed in the footer. Example:

```php
    $navigation = new Navigation(dgettext("accessibleform", "Report barrier"));

    $navigation->setURL(PluginEngine::getURL($this, [], 'form/index/'));

    Navigation::addItem('/footer/form', $navigation);
```

And this is what the footer looks like in Stud.IP:

![footer](../assets/6feeded7490d702b510a71a924934269/footer.png)

## Colors and contrasts

There are guidelines for contrast ratios of text to background, foreground color to background and graphics to background. This refers to the difference in brightness between two adjacent colors. This ranges from the worst contrast of 1:1, where colors are identical, to the best possible contrast of 21:1 (black on white or vice versa).

### Contrast ratio requirements

In the WCAG (Web Content Accessibility Guidelines) there are three points (success criteria) for the contrast ratio:

- **WCAG 1.4.3:** Minimum contrast 4.5:1 or 3:1 (large text)
- WCAG 1.4.6:** Increased contrast 7:1 or 4.5:1 (large text)
- WCAG 1.4.11:** Non-text contrast

These requirements apply to text and graphics that have **informational value**. Pure **decorative images and fonts** without information relevant to the user are **excluded**.

### Text contrasts

As a general rule, a distinction is made between smaller and larger font sizes. A minimum contrast ratio of 4.5:1 should be aimed for for all font sizes. In exceptional cases, a value of 3:1 is sufficient; however, a value of 7:1 is ideal.

For font sizes below 24px or 18pt (18.7px, or 14pt for bold fonts), a minimum contrast ratio of 4.5:1 is sufficient.

For font sizes from 24px or 18pt, a minimum contrast ratio of 3:1 is sufficient.

#### Link highlights

Link highlights must be clearly marked, both with a sufficiently high contrast of (at least) 3:1 and by other highlights, such as underlining.

If the link is focused (CSS pseudo-classes :hover and :focus), the contrast must be increased, for example by using a different color. Sufficient contrast to the background must also be ensured here. If this is not sufficient, the focused link must be highlighted.

### Contrasts of elements that are not text

This point concerns graphic elements such as icons, diagrams or symbols.

Important elements of the user interface (buttons, icons) as well as graphically displayed information (diagrams) must not convey information exclusively through colors.

Active menu items or icons that indicate an active state (e.g. text formatting tools and checkboxes) must be clearly distinguishable from inactive elements. The minimum contrast ratio for all states is 3:1.

Excluded from color changes are, for example, flags, heat maps, logos and other graphic elements for which a color change would result in a change in meaning.

### Exceptions

The following elements are excluded from the guidelines:

- purely decorative text that has no informational value
- logos or text in logos
- native UI components
- inactive elements such as buttons that are labeled "disabled"
- the browser's own focus indicator, provided it meets the contrast requirements

## Keyboard operability

Keyboard operability means that all elements of a page are also accessible via the keyboard and can be used without using the mouse or other input elements.

### Control via the keyboard

Very basic keyboard control can be achieved using the tab key. This can be used to navigate through all focusable elements. By combining the Tab key with the Shift key, you can navigate backwards through the focusable elements.

For selectable elements such as checkboxes and radio buttons, the arrow keys can be used to navigate through the entries.

Checkboxes and radio buttons can be activated or deactivated by pressing the space bar. It can also be used to open select fields.

The Enter key is used to follow links, press buttons, select an entry in a select field and submit forms.

Screen readers offer additional key combinations and key assignments that can be used to quickly navigate to text paragraphs, tables, regions of the page or links. However, these vary depending on the screen reader used.

### Avoid resetting the focus

It must be ensured that the focus is not reset when opening or closing dialogs or other elements. A reset results in the first focusable element on the page being in focus again instead of the element with which the dialog was opened. As a result, the path through all focusable elements back to the content of the page you were on before can be very long and arduous. A jump behind the last focused element must also be avoided, as otherwise the first focused element of the browser's graphical user interface will be in focus next and the path back to the page content will be even longer.

### Skiplinks

Skip links make it easier to use a page, as they allow you to jump directly to the area of interest on the page and you are not forced to go through all the elements of a page in the usual order until you have found the right element.

In Stud.IP, the SkipLink class helps to provide this functionality. It can be used to jump to and focus on specific page elements. If a skip link is added, its position can also be defined.

#### Standard skip links and their positions

The following skiplinks are activated by default in Stud.IP:

| name | element ID | position\*\* |
|------|------------|--------------|
| profile menu | header_avatar_image_link | 1 |
| Main navigation | barTopMenu | 2 |
| Second navigation level\* | tabs | 10 |
| Third navigation layer\*\* | nav_layer_3 | 20 |
| actions\*\* | sidebar_actions | 21 |
| main content | layout_content | 100 |
| footer\*\* | layout_footer | 900 |
| search\*\* | globalsearch-input | 910 |
| tips & help\*\*\* | helpbar_icon | 920 |

\*= until Stud.IP 5.0: First tab navigation

\*\*=only as of Stud.IP 5.1

#### Add skiplink

Additional skip links can be added in plugins, for example, if they refer to an element that should be quickly accessible. To do this, the SkipLink class is called as follows:

```php
//Add the skiplink with the label "New skiplink", which points to
//the element with the ID "id_to_element". The skiplink should be set to
//be set to position 200 and not be overwritten by other code positions
//be overwritable (false):
SkipLinks::addIndex('New skiplink', 'id_to_element', 200, false);
```

New skiplinks should only be added sparingly and not placed between the core system skiplinks so that the "muscle memory" for the standard skiplinks can function when using the keyboard.

## Use of ARIA roles and landmarks

Not all ARIA roles can be used sensibly in Stud.IP. Others can be used in principle, but in this case it would be better to change the underlying HTML.

### Do not use "menu" and "menuitem"

The roles "menu" and "menuitem" should not be used in Stud.IP. The reason for this is that "menu" describes a menu that should be operable in the same way as the menu of a desktop application: arrow keys instead of Tab or Shift-Tab. For an HTML element with the role "menu", screen readers such as JAWS say that the element can be operated with arrow keys, which is not the case for any menu in the Stud.IP context.

More information on the problem of the "menu" and "menuitem" roles can be found here: https://adrianroselli.com/2017/10/dont-use-aria-menu-roles-for-site-nav.html


## Testing with screen readers

Screen readers can be used to test that a development can also be used by blind people or people with very limited vision. They read out the content of a page. Special key combinations can be used to jump directly to certain elements of a page in order to avoid lengthy navigation using the tab key.

## Which screen readers are available?

* For Windows: JAWS or NVDA
* For Mac OS X and iOS: VoiceOver
* For GNU/Linux: Orca
* For Android: TalkBack in combination with eSpeak

## Combinations of screen readers and browsers

Not all screen readers work well with all browsers. The following combinations have proven to "harmonize" in tests:

* JAWS with Microsoft Edge
* Orca with Chromium

## Accessibility in Stud.IP

### File marker for accessible files and its configuration

As of Stud.IP 5.3, it is possible to mark a file as "barrier-free" when uploading. For this purpose, the existing dialog has been rebuilt and extended by an accessibility area. There is a checkbox that can be used to confirm that the file just uploaded is barrier-free.

![dialog](../assets/a0377c72d0325b7d57151aca9ad52143/dialog.png)

If a file is accessible, an accessibility symbol appears after the file name in the file list.

![dateiliste](../assets/69756a9f57c09cc3f3450519af957b23/dateiliste.png)

The info text **below** the checkbox can be customized in the admin area if desired, for example to include links or e-mail addresses of contact persons with regard to accessibility.

![configuration](../assets/bbe72ca0392b9f0f40989e5fabba6e14/configuration.png)

Note: A file can be marked as barrier-free/non-barrier-free at any time.

### New HTML structure (as of Stud.IP 5.3)

In order to make the structure of pages in Stud.IP more comprehensible and compatible for screen readers, the entire HTML structure has been revised for version 5.3. Documentation on this (including an overview of all changed elements) can be found [in this article](https://gitlab.studip.de/studip/studip/-/wikis/Neue-HTML-Struktur-ab-Stud.IP-5.3).

### Drag & Drop

In general, the use of drag & drop is associated with challenges if the solution is to be barrier-free.
should be barrier-free. Not only does it require JavaScript code that reacts to arrow key events and positions elements
positioned accordingly. The areas of the page where the drag & drop control is to be used must also
should be used must also be assigned the aria role "application" so that screen readers know that
no special rules for certain HTML elements should be applied in these areas.

A more detailed description of the aria role "application" and its effects can be found at MDN
can be found at: https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/application_role

## Further links

The page [Tips and tricks](a11y/tips.md) shows solutions for specific problems,
that can arise in low-barrier programming.

### Colors and contrasts

- Contrast calculator for calculating and checking accessible color combinations: https://www.leserlich.info/werkzeuge/kontrastrechner/

### ARIA roles

- https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/ARIA_Techniques

### Skiplinks

- https://www.w3schools.com/accessibility/accessibility_skip_links.php

### FAQ of the Federal Accessibility Agency

- https://www.bundesfachstelle-barrierefreiheit.de/DE/Fachwissen/Informationstechnik/EU-Webseitenrichtlinie/FAQ/fragen-antworten-eu-richtlinie-websites-und-mobile-anwendungen.html
