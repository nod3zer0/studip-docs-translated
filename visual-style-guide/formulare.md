---
title: Forms
sidebar_label: Forms
---


Forms should be standardized in Stud.IP according to LifTers014. A standard form is defined as follows:

```php
<form class="default" ...>
    ...
</form>
```


## General
Long explanatory texts at the beginning of the form should be avoided. Explanations can be realized via tooltips on the elements (see below) or, if necessary, texts in the help tab.

## Grouping of the form fields
Form fields (or input elements) should be grouped together if they are related in terms of content or function so that this relationship is clear. Each group should have a suitable heading.

```php
<form class="default" ...>
      <fieldset>
          <legend>Group heading 1</legend>
          ...
      </fieldset>
      <fieldset>
          <fieldset>Group heading 2</fieldset>
          ...
      </fieldset>
</form>
```

Forms with only one grouping are also permitted. In dialogs, however, a single grouping is removed!

#### Show/hide groups

Individual groups can be hidden or shown by giving either the `fieldset` (for a specific group) or the entire form (for all groups within) the class `collapsable`. By clicking on the 'legend' of the 'fieldset', the group is hidden or displayed again. If the group is to be hidden in the initial display, the `fieldset` must also be marked with the class `collapsed`.

```php
<form class="default" ...>
      <fieldset>
          <legend>Group heading 1</legend>
          ...
      </fieldset>
      <fieldset class="collapsable collapsed">
          <legend>Group heading 2</legend>
          ...
      </fieldset>
</form>
```

### Labels
In general, the HTML markup `<label>` should be used analogous to LifTers010. Example:

```html
<form class="default">
       <fieldset>
           <legend>Label</legend>
           ...
           <label>Input A
           <input name="input_a" type="text" placeholder="Text input A" required>
           </label>
           ...
       </fieldset>
 </form>
```

* The first word of the label should be written with a capital letter.
* The label should not end with a colon.

Wording:
* Meaningful labels should be chosen.
* Technical terms should be avoided.
* No complete sentences.

### Input fields that cannot be changed / deactivated

If a field in a form may not be changed in the current context, the attribute `disabled` must be attached to the input field. It is not permitted to simply output the text WITHOUT a form element!

Example from lib/classes/StudipSemTreeViewAdmin.class.php
```php
<form class="default" ...>
    <fieldset>
        <legend><?= _("Edit area") ?></legend>

        <label>
            <?= _("Name of the element") ?>
            <input type="text" name="edit_name"
                <?= $this->tree->tree_data[$this->edit_item_id]['studip_object_id'])? 'disabled' : * ?>
                   value="<?= htmlReady($this->tree->tree_data[$this->edit_item_id]['name']) ?>">">
        </label>
    ...
    </fieldset>
</form>
```

### Alignment of the form fields

Narrow form fields may be arranged in several columns. With narrower displays, these fields automatically wrap when used correctly.

Form fields arranged one below the other should be left-aligned. If several form fields form a logical sequence or belong directly together for other reasons, they should be arranged in a horizontal grouping (hgroup).

#### Regular elements arranged next to each other

To display elements next to each other on a suitably large screen, they are arranged in columns. There are a total of 6 columns and you can assign elements a width of 1 - 6 columns. The classes col-1 to col-5 are available for this - no specification means full width (equivalent to col-6).

These elements are then automatically displayed one below the other for narrower displays.

```php
<form class="default" ...>
    <label class="col-3">
        First name
        <input type="text" name="first-name">
    </label>

    <label class="col-3">
        Last name
        <input type="text" name="last-name">
    </label>
</form>
```


#### Horizontally grouped elements

To group elements horizontally in a line, a wrapper element with the class `.hgroup` is required. This element assumes the same size as the elements and initially distributes the space within itself equally, but the individual elements can also be influenced by the known size specifications.

The hgroup is only permitted for combined input fields, such as telephone numbers, dates etc. and radio buttons with very short labels (e.g. gender: m/f/kA, switch: yes/no/kA, etc.). Do not use fields that are too large and/or label texts that are too long for horizontal grouping!

```php
<form class="default" ...>

     <!-- ... -->

        <div>
            <?= _('gender') ?>
        </div>

        <section class="hgroup">
            <label>
                <input type="radio" <? if (!$gender) echo 'checked' ?> name="gender" value="0">
                <?= _("unknown") ?>
            </label>

            <label>
                <input type="radio" <? if ($gender == 1) echo "checked" ?> name="gender" value="1">
                <?= _("male") ?>
            </label>

            <label>
                <input type="radio" name="gender" <? if ($gender == 2) echo "checked" ?> value="2">
                <?= _("female") ?>
            </label>
        </section>
     <!-- ... -->
</form>
```

There is a second variant that can be used if the title is actually the label of a subsequent form element. Example from the user administration:

```php
<label for="inactive">
    <?= _('inactive') ?>
</label>

<section class="hgroup">
    <select name="inactive" class="size-s" id="inactive">
    <? foreach(array('<=' => '>=', '=' => '=', '>' => '<', 'never' =>_('never')) as $i => $one): ?>
        <option value="<?= htmlready($i) ?>" <?= ($request['inactive'][0] === $i) ? 'selected' : * ?>>
            <?= htmlReady($one) ?>
        </option>
    <? endforeach; ?>
    </select>

    <label>
        <input name="inactive_days" type="number" id="inactive" value="0">
        <?= _('days') ?>
    </label>
</section>
```

#### Combined variant with col and hgroup specifications

It is also possible and permissible to divide horizontally grouped elements into columns:

```php
<label class="col-3">
    phone number
    <section class="hgroup">
        + <input type="text" size="3">
        <input type="text" maxlength="5" class="no-hint" size="5"> /
        <input type="text" maxlength="10" size="10">
    </section>
</label>

<label class="col-3">
    Fax
    <section class="hgroup">
        + <input type="text" size="3">
        <input type="text" maxlength="5" class="no-hint" size="5"> /
        <input type="text" maxlength="10" size="10">
    </section>
</label>
```

### Alignment of the labels
The labels should be aligned to the left and above the input fields. This makes it easier to read the labels and clarifies the connection between the field labels and the input fields.

Attach::formlabel2015.png

If vertical space is limited, the labels should be left-aligned and placed to the left of the form fields. This maintains legibility and saves vertical space. In this case, the labels should be chosen so that they differ as little as possible in length so that the gaps between the labels and the input fields are not too large.

The labels should be arranged uniformly within a context.

### Placeholder
The placeholder attribute is used to fill input fields with short notes. This content disappears as soon as a user clicks in the input field.
* Placeholders should not be used as an alternative to the label.
* Placeholders should be used sparingly.

Example of a correctly used placeholder attribute:
TODO: Screenshot


Example of an **incorrect** placeholder attribute:
Attach::wronglabel.png


## Type of form fields
The type of input fields should be chosen in such a way that you can recognize which inputs are possible. A text field is used for the free input of characters without restrictions (except for the number of characters). [Checkboxes](Checkboxes), [Radio Buttons](Visual-Style-Guide#RadioButtons) or [Drop-Down Lists](DropDown) are used to limit the number of options or for entries where users easily make mistakes.


## Size of the form fields
Input fields should be large enough to accept typical entries without "writing over the right edge". The size of the form fields should be chosen so that it is clear which entries are possible there. Example: The input field for the course number should be shorter than the one for the course title.

The Stud.IP stylesheet suggests three sizes by default (CSS classes "size-s", "size-m" and "size-l"):

* size-s: 10em (intended for short entries such as numbers)
* size-m: 48em
* size-l: 100%

```php
<form class="default" ...>
...
    <label>
        Short input
        <input type="text" class="size-s">
    </label>

    <label>
        Medium input
        <input type="text" class="size-m">
    </label>

    <label>
        Longer input
        <input type="text" class="size-l">
    </label>
...
</form>
```

Attach::formsizes2015.png

The default setting is "size-m". Exception: For the input types "number" and "date", the default setting is "size-s".
```php
<form class="default narrow" ...>
    ...
</form>
```

### Narrow forms

Sometimes it is necessary to make a form particularly space-saving by default (see e.g. Admin > Location > Event hierarchy).
The "narrow" class can be added to the form for this purpose. This ensures that the individual form elements are somewhat closer together in order to avoid premature wrapping.

Attach::narrow_form.png

## Marking of mandatory fields

```php
<form class="default" ...>
       <fieldset>
           <legend>Label</legend>
           ...
           <label>
               <span class="required">Input A</span>
               <?= tooltipIcon(_('Please enter only one number here')) ?>
               <input type="number">
           </label>
           ...
       </fieldset>
 </form>
```


Mandatory fields must be marked with a superscript red asterisk to the right of the field label. This can be implemented in a label using `<span class="required">` in the source code.

### Note texts for the form fields [#Note texts](#Note texts)

As the labeling of a form field should be as short as possible, it is possible that further information or explanatory notes on the corresponding field are required. A required information or description text for a form field is implemented using a tooltip. The tooltip is positioned via the existing logic `<?= tooltipIcon(_('...'))?>` to the right of the label and, if necessary, behind the label of a mandatory field.

Attach:formtooltip2015.png


## Format specifications and input validation
If entries may only be made in a certain format, this should be indicated, either by
* appropriate selection or design of the form fields,
* an "intelligent" interpretation of the entries (e.g. recognition of 15 or 1500 as the time 15:00) or
* Notes in the input field [see Note texts](#Note texts).
* Use of corresponding input types (see [Input validation](Howto/Input validation))

Input validation should, if possible, take place directly after leaving the respective input field. For every mandatory field that is not filled in or for every input field that is otherwise filled in incorrectly, the correction note should be displayed directly next to the respective input field so that the user's attention is drawn directly to the entries that still need to be made or corrected.

Further information: [Input validation](Howto/Input validation)

## Buttons
The button for submitting/saving/accepting the entered data ("primary action") should be left-aligned with the form fields and located directly below the form in the `<footer>` element. This makes it clear which data is accepted by clicking on this button.

A button for canceling or resetting ("secondary action") should be avoided. If it is required, it should be visually different from the button for the primary action.

```php
<form class="default" ...>
...
    <footer>
        <?= \Studip\Button::createAccept(_("Save")) ?>
        <?= \Studip\Button::createCancel(_("Cancel")) ?>
    </footer>
</form>
```



Attach:formfooter2015.png




* TODO: Formulate more precise specifications for the design of buttons for secondary actions

#### Exception: Buttons for wizards
* Where should the buttons for "back" and "next" be placed on multi-page forms?
  ** centered, what is the distance between the two buttons?

For longer forms (which extend over one screen page): "Double" the buttons, i.e. display them at the top and bottom of the page
page, e.g. "back" and "next" buttons

* http://patternry.com/p=multiple-page-wizard/
* Further research on buttons at Wizards
  ** Attach:labelsonform.pdf
  ** Source: http://de.slideshare.net/cjforms/labels-and-buttons-on-forms/

### Further information

#### General

* Cheat Sheet For Designing Web Forms http://uxdesign.smashingmagazine.com/2011/10/07/free-download-cheat-sheet-for-designing-web-forms/ Attach:formsheet.pdf
* http://uxdesign.smashingmagazine.com/2011/11/08/extensive-guide-web-form-usability/
* http://www.formsthatwork.com/Articles
* http://www.slideshare.net/cjforms/labels-and-buttons-on-forms
* [Paper](http://www.intechopen.com/download/pdf/10814) "Simple but Crucial User Interfaces in the World Wide Web: Introducing 20 Guidelines for Usable Web Form Design"

#### Placeholder
* http://mentalized.net/journal/2010/08/05/dont_use_placeholder_text_as_labels/
* http://dev.w3.org/html5/spec/single-page.html#the-placeholder-attribute
* http://laurakalbag.com/labels-in-input-fields-arent-such-a-good-idea/


## Checkboxes

### Usage
Checkboxes are used to activate or deactivate options.

### Appearance
* Checkboxes should be arranged one below the other if possible. This makes them easier to read.
* The label should be placed to the right of the box.
* Boxes and labels should be left-aligned below each other.

### Labeling
Negative labels should be avoided:
* marked checkboxes activate settings and do not deactivate them

```html
  <form ... >
        <fieldset>
            <legend>Label</legend>

            <fieldset>
                <legend>Checkbox group</legend>
                <input class="studip_checkbox" id="cb1" type="checkbox" name="cb" value="1">
                <label for="cb1">Answer option 1</label>
                <input class="studip_checkbox" id="cb2" type="checkbox" name="cb" value="2">
                <label for="cb2">Answer option 2</label>
                <input class="studip_checkbox" id="cb3" type="checkbox" name="cb" value="3">
                <label for="cb3">Answer option 3</label>
            </fieldset>
            ...
        </fieldset>
  </form>
```
### Order of the checkboxes
If there are several checkboxes on a page, they should be listed in a logical order, e.g. the options that are used most frequently first.

## Radio Buttons [#RadioButton](#RadioButton)

### Use
Radio buttons allow users to select exactly one option from mutually exclusive alternatives, e.g. send email as text or in HTML.

If there are more than four/six options, a [Drop-Down List](#Visual-Style-Guide#DropDown) is the better choice.

### Behavior
If possible, a sensible default option should be preselected.

```html
<form class="default" ... >
        <fieldset>
            <fieldset>Label</fieldset>

            <fieldset>
                <legend>Checkbox group</legend>
                <input class="studip_checkbox" id="cb1" type="checkbox" name="cb" value="1">
                <label for="cb1">Answer option 1</label>
                <input class="studip_checkbox" id="cb2" type="checkbox" name="cb" value="2">
                <label for="cb2">Answer option 2</label>
                <input class="studip_checkbox" id="cb3" type="checkbox" name="cb" value="3">
                <label for="cb3">Answer option 3</label>
            </fieldset>
            ...
        </fieldset>
  </form>
```

#### Appearance
Radio buttons should be arranged one below the other if possible. This makes them easier to skim.
The name should be to the right.

## Drop-down lists [#DropDown](#DropDown)

Drop-down lists allow users to select exactly one option from two or more mutually exclusive options. They are used instead of [Radio Buttons](Visual-Style-Guide#RadioButtons) for long lists of options.

### Sorting the options
The options should be arranged in a logical or natural order, e.g. Monday, Tuesday first for weekdays. If there is no logically sensible order, the options should be arranged alphabetically (or alphanumerically).

http://uxmovement.com/forms/stop-misusing-select-menus

### Behavior
Drop-down lists should preferably have a default value.

## List boxes
### Use
List boxes can be used as an alternative to a series of [Radio Buttons](Visual-Style-Guide#RadioButton), which allow you to select exactly one option from a series of mutually exclusive options. Or serve as an alternative to [Checkboxes](Checkboxes), which allow you to select any number of choices from a list of options. They take up less space on the screen than a list of radio buttons or checkboxes.

List boxes should only be used very sparingly.

## Date entries
* Create/edit event date
* Create appointment in the appointment calendar
* Define time range for export in the appointment calendar
* Create/edit regular time
* Define date to be displayed in the booking plan
* Enter/edit resource allocation
* Validity period of
  * News
  * Votings
  * Evaluations
* Define registration period for events
* Enter your own "event" in the timetable
* Generic data fields of type "Date"?
