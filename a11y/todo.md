---
title: Accessibility
sidebar_label: TODO Matching
---

In order to avoid barriers in Stud.IP, it is necessary to observe the following notes
on accessibility and to avoid certain constructs in the HTML code,
and in CSS rules.


## Colors and contrasts

There are guidelines for contrast ratios of text to background,
foreground color to background and graphics to background. This refers to
is the difference in brightness between two adjacent colors. This ranges
from the worst contrast of 1:1, where colors are identical, to the best possible contrast of
best possible contrast of 21:1 (black on white or vice versa).

### Contrast ratio requirements

In the WCAG (Web Content Accessibility Guidelines) there are three
points (success criteria) on the contrast ratio:

- **WCAG 1.4.3:** Minimum contrast 4.5:1 or 3:1 (large text)
- WCAG 1.4.6:** Increased contrast 7:1 or 4.5:1 (large text)
- WCAG 1.4.11:** Non-text contrast

These requirements apply to text and graphics that have an
**informational value**. Pure **decorative images and fonts**
without information relevant to the user are **excluded**.

### Text contrasts

As a general rule, a distinction is made between smaller and larger font sizes.
are distinguished. A minimum contrast ratio of 4.5:1 is required for all font sizes.
font sizes. In exceptional cases, a value of 3:1 is sufficient; however, a value of
however, a value of 7:1 is ideal.

For font sizes below 24px or 18pt (18.7px, or 14pt for bold font), a minimum contrast ratio of
font), a minimum contrast ratio of 4.5:1 is sufficient.

For font sizes from 24px or 18pt, a minimum contrast ratio
of 3:1 is sufficient.

#### Link highlights

Link highlights must be clearly marked, both with a sufficiently high contrast of
sufficiently high contrast of (at least) 3:1 as well as through other
highlighting, for example underlining.

If the link is focused (CSS pseudo-classes :hover and :focus), the contrast must be
must be increased, for example by using a different color. Here too
sufficient contrast to the background. If this is not sufficient
is not sufficient, the focused link must be highlighted.

### Contrasts of elements that are not text

This point concerns graphic elements such as icons, diagrams or symbols.
or symbols.

Important elements of the user interface (buttons, icons) as well as graphically
graphically displayed information (diagrams) must not convey information
exclusively through colors.

Active menu items or icons that indicate an active state
(e.g. text formatting tools and checkboxes) must be clearly distinguishable from inactive
clearly distinguishable from inactive elements. The minimum contrast ratio for all
states is 3:1.

Excluded from color changes are, for example, flags, heat maps, logos
and other graphic elements for which a color change would result in a change of meaning.
would result in a change of meaning.


### Exceptions

The following elements are excluded from the guidelines:

- purely decorative text that has no informational value
- logos or text in logos
- native UI components
- inactive elements such as buttons that are labeled "disabled"
- the browser's own focus indicator, provided it meets the contrast requirements
  fulfilled


## Keyboard operability

Keyboard operability means that all elements of a page can also be accessed using the keyboard
keyboard and can be used without using the mouse or other input elements.
or other input elements.

### Control via the keyboard

Very basic keyboard control can be achieved using the tab key
can be used. This can be used to navigate through all focusable elements
can be navigated. The key combination of the tab key with the shift key
to navigate backwards through the focusable elements.

For selectable elements such as checkboxes and radio buttons, the
arrow keys can be used to navigate through the entries.

By pressing the space bar, checkboxes and radio buttons can be
activated or deactivated. It can also be used to open select fields.

The Enter key is used to follow links and press buttons,
selecting an entry in a select field and submitting forms.

Screen readers offer additional key combinations and key assignments
to quickly navigate to text paragraphs, tables, regions of the page or links.
navigate to text paragraphs, tables, regions of the page or links. However, these vary depending on the
screen reader used.


### Making elements keyboard accessible

HTML elements that are not keyboard accessible by default can be made keyboard accessible via the
attribute "tabindex" can be made keyboard operable. Here is an example of a
LABEL element:

    <label for="some-element" tabindex="0">
    ....
    </label>

The "tabindex" attribute with the value 0 inserts the LABEL element into the list with
focusable elements.

The value for the "tabindex" attribute should be set to 0 if an element that is
element that is not focusable by default is to be made focusable.
In the opposite case, where a focusable element is not to be made focusable
is to be made focusable, the value of the "tabindex" attribute is -1.

Values other than 0 and -1 should not be used for tabindex because they
interfere with the "natural" order of the focusable elements and can lead to unexpected
unexpected jumps if two elements in different page areas have a fixed number in the tabindex.
page areas have been given a fixed number in the tabindex. Example:

    <main>
        <div tabindex="2">E1</div>
        ...
        <a href=".../e2.php" tabindex="4">E2</a>
    </main>
    <footer>
        <a href=".../f1.php" tabindex="1">F1</a>
        <button tabindex="3">F2</button>
    </footer>

The order of the focusable elements here would be F1, E1, F2, E2 instead of
the "natural" order E1, E2, F1, F2.


### Trigger "click" event also when pressing the enter key

Since the fix of [BIESt 106](https://gitlab.studip.de/studip/studip/-/issues/106)
there is a piece of general JavaScript code that becomes active for all elements
that belong to the "enter-accessible" class. If such an element
is focused and the enter key is pressed, the click event is triggered,
which is normally only triggered by mouse clicks.
The LABEL element from the example above should be extended as follows

    <label for="some-element" class="enter-accessible" tabindex="0">
    ....
    </label>


### Skiplinks

Skiplinks make it easier to use a page, as they allow you to jump directly to the
interesting area of the page and you are not forced to scroll through all the
elements of a page in the usual order until you find the right element.
until you have found the right element.

In Stud.IP, the SkipLink class helps to provide this functionality.
functionality. It can be used to jump to and focus on specific page elements.
focus. If a skip link is added, its position can also be
can also be defined.

#### Skiplinks available by default and their positions

The following skiplinks are activated by default in Stud.IP:

| Name | Element ID | Position** |
| ------------------------- | ------------------------ | ---------- |
| profile menu | header_avatar_image_link | 1 |
| Main navigation | barTopMenu | 2 |
| Second navigation level* | tabs | 10 |
| Third navigation level** | nav_layer_3 | 20 |
| actions** | sidebar_actions | 21 |
| main content | layout_content | 100 |
| footer** | layout_footer | 900 |
| search** | globalsearch-input | 910 |
| tips & help** | helpbar_icon | 920 |

*= until Stud.IP 5.0: First tab navigation

**=only from Stud.IP 5.1

#### Add skiplink

Additional skiplinks can be added in plugins, for example, if these
refer to an element that should be quickly accessible. To do this, the
SkipLink class is called as follows:

    //Add the skiplink labeled "New skiplink" that points to
    //the element with the ID "id_to_element". The skiplink should be set to
    //be set to position 200 and not be overwritten by other code positions
    //be overwritable (false):
    SkipLinks::addIndex('New skiplink', 'id_to_element', 200, false);

New skiplinks should only be added sparingly and should not be placed between the
ski links of the core system so that the "muscle memory" for the
for the standard skiplinks can function when using the keyboard.
can work.


## Use of ARIA roles and landmarks

Not all ARIA roles can be used sensibly in Stud.IP.

### Do not use "menu" and "menuitem"

The roles "menu" and "menuitem" should not be used in Stud.IP.
The background to this is that "menu" describes a menu that should be just as operable
the same way as the menu of a desktop application: arrow keys instead of tab or
Shift-Tab. For an HTML element with the role "menu", screen readers
like JAWS say that the element can be operated with arrow keys, which is not the case for any menu in the Stud.IP context.
is not the case for any menu.

More information on the problem of the "menu" and "menuitem" roles
can be found here: https://adrianroselli.com/2017/10/dont-use-aria-menu-roles-for-site-nav.html


## Testing with screen readers

In order to test that a development can also be used by blind people or people with very
blind people or people with very limited vision, screen readers can be used.
They read out the content of a page. Using special
specific elements of a page can be accessed directly using special key
to avoid tedious navigation using the tab key.

## Which screen readers are available?

* For Windows: JAWS or NVDA
* For Mac OS X and iOS: VoiceOver
* For GNU/Linux: Orca
* For Android: TalkBack in combination with eSpeak

## Combinations of screen readers and browsers

Not all screen readers work well with all browsers. The following
combinations have proven to "harmonize" in tests:

* JAWS with Microsoft Edge
* Orca with Chromium


## Tips & tricks

### Hide checkboxes and radio buttons

If you want to hide checkboxes or radio buttons, you should not do this
with display:none or visibility:hidden, because then the associated content can no longer be
content can no longer be focused with the keyboard.

The following is a cross-browser solution:

    position: fixed;
    opacity: 0;
    pointer-events: none;

It is also not advisable to use your own graphics instead of the regular checkboxes
instead of the regular checkboxes.



## Further links

### Colors and contrasts

- Contrast calculator for calculating and checking accessible color combinations
  color combinations: https://www.leserlich.info/werkzeuge/kontrastrechner/

### ARIA roles

- https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/ARIA_Techniques
