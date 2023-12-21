---
title: Tips & Tricks
slug: /a11y/tips
sidebar_label: Tips & Tricks
---

In the following, code snippets are used to show solutions to problems that can occur when
programming solutions in Stud.IP with as few barriers as possible.

### Making elements keyboard accessible

HTML elements that are not keyboard-accessible by default can be made keyboard-accessible using the "tabindex" attribute. Here is an example of a LABEL element:

```html
<!-- Positive example: This is how it should be done. -->
<label for="some-element" tabindex="0">
....
</label>
```

The "tabindex" attribute with the value 0 inserts the LABEL element into the list of focusable elements.

The value for the "tabindex" attribute should be set to 0 if an element that is not focusable by default is to be made focusable. In the opposite case, where a focusable element is not to be made focusable, the value of the "tabindex" attribute is -1.

Values other than 0 and -1 should not be used for tabindex because they interfere with the "natural" order of the focusable elements and can lead to unexpected jumps if two elements in different page areas have been assigned a fixed number in the tabindex. Example:

```html
<!-- Negative example. This is not how it should be done. -->
<main>
    <div tabindex="2">E1</div>
    ...
    <a href=".../e2.php" tabindex="4">E2</a>
</main>
<footer>
    <a href=".../f1.php" tabindex="1">F1</a>
    <button tabindex="3">F2</button>
</footer>
```

The order of the focusable elements would be F1, E1, F2, E2 instead of the "natural" order E1, E2, F1, F2.

### Trigger "click" event also when pressing the Enter key

Since the fix of [BIESt 106](https://gitlab.studip.de/studip/studip/-/issues/106 "Wiki: Table of contents not accessible via keyboard navigation"), there is a piece of general JavaScript code that becomes active for all elements that belong to the "enter-accessible" class. If such an element is focused and the enter key is pressed, the click event is triggered, which is normally only triggered by mouse clicks. The LABEL element from the example above should be extended as follows:

```html
<label for="some-element" class="enter-accessible" tabindex="0">
....
</label>
```

### Avoid "button" role, use BUTTON elements instead

The ARIA role "button" can be used to mark elements as "buttons" that are not normally buttons. For example, links that actually perform an action instead of referring to another page can be marked with the "button" role so that they are read out by screen readers as a button instead of a link:

```html
<a href="#" aria-role="button">
    Add
</a>
```

The problem in this example is that the A element for anchors is already inappropriate at this point, as it is not an anchor or link. The better solution is to use a BUTTON element directly in the HTML:

```html
<button type="button">Add</button>
```

### Hide checkboxes and radio buttons

If you want to hide checkboxes or radio buttons, you should not do this with display:none or visibility:hidden, because then the associated content can no longer be focused with the keyboard.

A cross-browser solution is the following:

```css
position: fixed;
opacity: 0;
pointer-events: none;
```

It is also not advisable to use your own graphics instead of the regular checkboxes.


### input elements: minlength attribute is not respected by screen readers

If input elements are equipped with a minlength attribute, this is not taken into account by screen readers. Therefore, other means must be used to indicate that a minimum number of characters must be entered.

In the event that too few characters have been entered, an accessible message should also be displayed to indicate this problem. This can be done with an aria-live region and JavaScript, which displays the length of the entered character when leaving the field.
of the field checks the length of the text entered and then issues a message via the aria-live region.

The check for compliance with the maxlength attribute can be carried out in the same way.

### Programming low-barrier forms

If possible, you should use the form construction kit, which is documented here: https://gitlab.studip.de/studip/studip/-/wikis/StudipForm#die-form-klasse-ab-52

Building a form with this construction kit may be a little unfamiliar, but the result will always be barrier-free because the standard elements of this construction kit are barrier-free and easy to use.

### Programming accordion elements with low accessibility

Accordion elements, such as the selection of the terms of use in the file area, should be programmed in such a way
that they can also be used by keyboard and their content can be read out by screen readers.
Usually, such elements have radio buttons that are replaced by a graphic to match the Stud.IP design.
design. If the radio buttons are to be hidden for this reason, this must not be done using the CSS specification
"display: none" because the element is then not accessible via the keyboard. Instead, "opacity: 0"
should be used instead. This means that such a radio button is still accessible via the keyboard, but still invisible.

The example from the Stud.IP file area looks like this for an entry in the terms of use after the
rendered as follows in the source code:

```html
<input type="radio" name="content_terms_of_use_id" value="SELFMADE_NONPUB" id="content_terms_of_use-SELFMADE_NONPUB"
       checked="" aria-description="(Description of the terms of use)">
<label for="content_terms_of_use-SELFMADE_NONPUB">
    <div class="icon">
        <img src="(Icon of the terms of use)" alt="" class="icon-role-clickable icon-shape-own-license" width="32" height="32">
    </div>
    <div class="text">(Name of the terms of use)</div>
    <img class="arrow icon-role-clickable icon-shape-arr_1down" src="(pop-up icon)" alt="" width="24" height="24">
    <img class="check icon-role-clickable icon-shape-check-circle" src="(selected-icon)" alt="" width="32" height="32">
</label>
<div class="terms_of_use_description">
    <div class="description">
        <div class="formatted-content">(Description of the terms of use)</div>
    </div>
</div>
```

If a radio button is selected, no additional JavaScript is required to make the solution less accessible.
Because the radio button contains the entire description of the terms of use, there is no need to use aria-live
regions to have the text read aloud.


### More accessible drag & drop for sorting elements

If sorting via drag & drop should not only be possible in the GUI, but also via the keyboard using a screen reader,
it takes relatively little effort to ensure that the sorting is more accessible.
For a table with sortable rows, part of the solution using vue.js looks like this:
````vue
<span aria-live="assertive" class="sr-only">{{ assistiveLive }}</span>


<draggable v-model="elements" handle=".drag-handle" :animation="300" @end="dropElement" tag="tbody" role="listbox">
    <tr v-for="(element, index) in elements" :key="index">
        <td>
            <a v-if="elements.length > 1" class="drag-link" role="option" tabindex="0" :title="$gettextInterpolate($gettext('Sort element for element %{node}. Press the up or down arrow keys to move this element in the list.'), {node: element.name})" @keydown="keyHandler($event, index)" :ref="'draghandle-' + index">
                <span class="drag-handle"></span>
            </a>
        </td>
        ...
    </tr>
</draggable>
````
The crucial things here are

- `role="listbox"` on the container and `role="option"` on the individual elements. Without this role assignment, the arrow keys only work within forms, because otherwise the screen reader intercepts the arrow key action.
- `@keydown="keyHandler($event, index)"` The method reacts when a key is pressed (within the method it is further checked whether it is "arrow up" (`keyCode 38`) or "arrow down" (`keyCode 40`)), triggers the re-sorting and sets a corresponding hint text via `assistiveLive`. The new position of the element in the list should also be read out, à la "Element is at position X of Y".

````vue
switch (e.keyCode) {
    case 38: // up
        e.preventDefault();
        this.decreasePosition(index);
        this.$nextTick(() => {
            this.$refs['draghandle-' + (index - 1)][0].focus();
                this.assistiveLive = this.$gettextInterpolate(
                    this.$gettext('Current position in the list: %{pos} of %{listLength}.'),
                    { pos: index, listLength: this.children.length }
                );
            });
            break;
        ...
````

The enclosing area in which sorting should be possible must be provided with the ARIA role "application" at the appropriate position,
be provided with the ARIA role "application" so that screen readers know that this area
has its own keyboard control, which should not be changed by the screen reader. Otherwise
Otherwise, the event handlers for the arrow keys may not be executed because the
the screen reader is already doing something else with the arrow key events, such as jumping to the
next element with text in order to read it out.

Source: https://medium.com/salesforce-ux/4-major-patterns-for-accessible-drag-and-drop-1d43f64ebf09
