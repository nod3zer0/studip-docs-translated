---
id: studip-form
title: Stud.IP forms
sidebar_label: Stud.IP forms
---

HTML forms are always used in Stud.IP. The Stud.IP stylesheet offers the possibility to give the form a certain standard appearance. This means that a form always feels somehow coherent and visually appropriate for the user.

It is clear to us that forms should not be forced into a rigid corset. What about drag & drop form elements? What about complex multi-selects that should contain images? These are special cases that we cannot foresee. That's why the Stud.IP stylesheet is limited to the basic elements of a form and tries to make them appealing. For everything that goes beyond this, the developer has to do it himself.

# Structure

```XML
<form class="default">
    <section>
        <legend>Basic data</legend>
        <label>
            Name of the object
            <input type="text">
        </label>
        <label>
            Type of the object
            <select>
                <option>Option 1</option>
                <option>Option 2</option>
                <option>Option 3</option>
            </select>
        </label>
        <label>
            <input type="checkbox">
            Make object visible
        </label>
    </section>
</form>
```

This is the basic structure of an HTML form in Stud.IP. The class of the form is particularly important. The class name should be "default". This class name is what gives the form its appearance in the first place.

One or more `fieldset` elements can be defined below this. Such sections should [according to HTML5](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/fieldset) be used to group several form elements that are related. In Stud.IP, a fieldset is given a blue frame and is indented accordingly.

Below the `fieldset` element there can be a `legend` element, which contains something like a heading for the `fieldset`. You can also leave it out, but it usually looks nicer with a `legend` element.

Now usually come the actual form elements, which are each enclosed by a `label` element. These labels are extremely important to keep the form **barrier-free**. Always remember people who use a screen reader, for example, because they are partially or completely blind! These people can also study and must be able to use Stud.IP almost completely. In practice, it is not easy to put yourself in the shoes of someone with impaired vision. But as a programmer, you don't have to. You just have to remember: every form element such as `<input>` or `<textarea>` must have a label. Even a placeholder attribute on an `input` element is no substitute for a label.

For the sake of simplicity, write the `@<label>@s` as shown above. Only radio buttons and checkboxes should be placed before the label text. Otherwise, the label text comes before the input element. For complex input options such as comboboxes or `<input>`s in a table, it can be useful to separate the label and input element from each other. This is not forbidden. With the `for` attribute on the label, you can still connect the label text and `<input>` so that the form remains accessible.

# Special features / goodies

In Stud.IP we have built in some special functions that are simply nice and make it easier for the user to use the form.

## File selector

Unfortunately, it is impossible to completely restyle a `<input type="file">` file selector so that it looks the same on all devices - unless you make it disappear. That's exactly what we did! However, you need another CSS class to create this. To do this, write a structure like this

```XML
<label class="file-upload">
    <input type="file">
    <?= _('Upload new avatar image') ?>
</label>
```

In most cases, the file selector should then look quite good and match Stud.IP. An additional icon is displayed and the selected file is shown in gray next to it so that the user can see whether he/she may have selected the wrong file by mistake.

## Collapsible fieldsets

Nothing is worse than clutter. Especially in very large forms it sometimes gets hectic. The fieldsets already provide a good order. But you may want to collapse entire fieldsets and only show them when you want to. This is possible with the additional class ``collapsable``, which you can either attach to the fieldset or to the entire form so that it applies to all fieldsets in it.

```XML
<form class="default collapsable">
    <section>
        <legend>Basic data</legend>
        <label>
            Name of the object
            <input type="text">
        </label>
    </section>
    <section>
        <legend>Extended data</legend>
        <label>
            Type of the object
            <select>
                <option>Option 1</option>
                <option>Option 2</option>
                <option>Option 3</option>
            </select>
        </label>
        <label>
            <input type="checkbox">
            Make object visible
        </label>
    </section>
</form>
```

## Maximum text length

The attribute `maxlength` can be appended to both `<textarea>` and `<input>`. The browser then automatically limits the number of characters to the specified value. Stud.IP also automatically displays the remaining characters at the bottom right of the form element. The display of the remaining characters can be suppressed by specifying the CSS class `no-hint`.

```XML
<input type="text" maxlength="160">
```

## Horizontal arrangement

Stud.IP displays the `<label>` elements and generally almost everything in the form below each other. This has the advantage that less space is used in the width and the form is easy to use even on smartphones. The disadvantage, however, is that you now have to scroll much more than before. Especially with radio buttons with little text, this can look absurd and is not desirable. So far, Stud.IP only offers the possibility to add a container element like a `<div class="hgroup">`. In such an `hgroup` all child elements are arranged horizontally and not vertically as usual.

## Simple selection of multiple checkbox elements [data-shiftcheck]

The `data-shiftcheck` attribute on the form element can be used to specify that it should be possible to select a set of checkboxes by activating/deactivating the first checkbox, holding down the Shift key and clicking on the last checkbox to be selected. All checkboxes between the first and the last are then set to the status of the last checkbox.

# Other options

The following options are not associated with the CSS class `form`:

## Confirmation of an action [data-confirm]

You can use the `data-confirm` attribute on a link, button or form to obtain confirmation of the action. The value of the attribute should be the question that the user is asked.

Please note that this should not replace the server-side validation of the input. Also note that all variables put into `data-confirm` must be processed with `htmlReady()`.

## Datepicker [data-date-picker, data-time-picker, data-datetime-picker]

You can give an `<input type="text">` the option of a date picker to make it easier to enter dates by giving the element the `data-date-picker` attribute. The same applies to time pickers (`data-time-picker`) and datetime pickers (`data-datetime-picker`).

Bear in mind that HTML5 actually provides an `<input type="date">` for this. This element would normally be the better alternative. But unfortunately there are problems with the fact that it is not supported in some browsers. Therefore, you cannot rely on the fact that the input always works well. Under no circumstances should you try a combination of the jQuery datepicker above with `<input type="date">`. For browsers that understand `<input type="date">`, the datepickers will both appear and completely confuse the user.

The value of the attribute may be empty. However, you can also specify a JSON object as the value for each of the `date-...-picker` attributes, specifying the relationship between this element and another object. You can specify whether the value of the current element must be less than, less than or equal to, greater than or equal to that of another element. This is then taken into account accordingly when selecting within the picker and the corresponding time period before/after cannot be selected. Invalid values in the linked element are also adjusted.

The corresponding elements are indicated by a CSS selector. The following is an example of the start of the course period, which must lie between the start and end of the semester:

```XML
<label>
    <?= _('Semester start') ?>
    <input type="text" name="semester-start" id="semester-start"
           data-datepicker='{"<": "#semester-end"}'>
</label>

<label>
    <?= _('semester-end') ?>
    <input type="text" name="semester-end" id="semester-end"
           data-datepicker='{">": "#semester-start"}'>
</label>

<label>
    <?= _('start of lecture') ?>
    <input type="text" name="lecture-start" id="lecture-start"
           data-datepicker='{">="semester-start","<": "#semester-end"}'>
</label>
```

## Proxy elements [data-proxyfor]

The attribute `data-proxyfor` on a checkbox can be used to specify a CSS selector that determines for which other checkboxes this element should serve as a "proxy". In this way, several checkboxes can be activated or deactivated via a single one.

## Activate/deactivate elements [data-activates, data-deactivates]

The `data-activates` or `data-deactivates` attribute on a checkbox/radiobox can be used to specify a CSS selector that determines which other elements are activated or deactivated by the status of this element. `data-activates` can also be attached to a select element and can thus activate an element as soon as a value is selected that is not equal to the empty string.

On the elements that are to be activated or deactivated in this way, the status can be controlled even more finely via the attribute `data-activates-condition` or `data-deactivates-condition`, which expect a CSS selector and only set the status if this selector has at least one hit.

## Comparison of two values [data-must-equal]

The attribute `data-must-equal` on an element can be used to ensure that the values in two elements are identical (for example when entering a password). The attribute is placed on the second confirmation field with a CSS selector as the value that determines the element that must be identical:

```XML
<label>
    <?= _('password') ?>
    <input type="password" name="password" id="password">
</label>

<label>
   <?= _('Confirm password') ?>
   <input type="password" name="password-confirm" data-must-equal="#password">
</label>
```

## Formsaver (data-confirm)

The "data-secure" attribute can be used to protect forms or form elements by displaying a warning when leaving the page if there are unsaved changes.

Add the data attribute "data-secure" to any "form" or "input" element and when the page is reloaded or the surrounding dialog is closed, a confirmation dialog will appear.

There are two configuration options for this attribute:

* always: Saves the element regardless of its state. If a form should always be saved, use this option. If you want to exclude an element from the security check, set the attribute value to "false" (but you should use the short form `data-secure="false"`).

* exists: Dynamically added nodes cannot be recognized and are therefore never taken into account if their content has changed. Specify a CSS selector that precisely identifies all required elements.

These options can be passed as a json encoded array like this:

```XML
<form data-secure='{always: false, exists: "#foo > .bar"}'>
```

However, as you will probably never need both options at the same time, you can either just pass a Boolean value to the "data-secure" attribute to set the "always" option or use another non-object value for the "exists" option:

```XML
<form data-secure="true">
```

 is equivalent to

```XML
<form data-secure='{always: true}'>
```

and

```XML
<form data-secure="#foo .bar">
```

 is equivalent to

```XML
<form data-secure='{exists: "#foo .bar"}'>
```

# The form class (from 5.2)

In Stud-IP there is now the form class or the class is called `\Studip\Forms\Form` including namespaces. It is best suited if you have one or more objects of the type `SimpleORMap` (SORM) and want to edit or save them. However, the Form class can also display and save forms that have nothing to do with SORM objects. In most cases you want to have a mixture, the Form class can also do this. The advantages are at a glance:

* As a programmer, you don't have to worry about form validation and accessibility in most cases.
* It almost always looks good and fits perfectly into the design of Stud.IP.
* The standard cases of inputs are easy to handle.
* And for special cases and in plugins, you can extend the class with your own input classes.

## Internal structure of the form

A form consists of several input elements and structural elements such as a fieldset (the blue boxes around the input fields) or an H-group (a `<div class="hgroup">` in which all inputs are arranged next to each other or horizontally). Such structural elements can also be nested as desired. For example, it is very popular to integrate an H-group into a fieldset.

If the form element then wants to have all inputs at some point, for example to save them, it retrieves them using the `getAllInputs` method, with which all parts are recursively searched for input elements.

Each input element is thus from a class derived from the abstract class `\Studip\Forms\Input`. Each input object knows its name in the form and may even have functions that are called when saving or for data mapping.

## Examples
```php
    $form = \Studip\Forms\Form::fromSORM(
        User::findCurrent(),
        [
            'without' => ['password', 'chdate', 'user_id'],
            'types' => ['lock_comment' => 'datetimepicker'],
            'legend' => _('user data from me')
        ]
    )->setURL($this->url_for('mycontroller/save'));
```

You could also write it differently:

```php
    $form = \Studip\Forms\Form::fromSORM(
        User::findCurrent(),
        [
            'fields' => [
                'username' => _('Login ID'),
                'firstname' => _('firstname'),
                'lastname' => _('lastname'),
                'email' => _('email address'),
                'lock_comment' => [
                    'label' => _('lock date'),
                    'type' => 'datetimepicker'
                ]
            ],
            'legend' => _('user data from me')
        ]
    )->setURL($this->url_for('mycontroller/save'));
```
In this example, the logged-in user is edited. The array contains the entry `fields`, in which the fields to be displayed in the form are named. In this case, the index is the same as the field name in the SORM object but also the input name in the form or request. And the value is either an array with attributes or, in shorthand, simply a string, which then corresponds to the visible label of the field.

The form class analyzes the database and tries to complete most of the information, for example the `type`. The idea is that the programmer has to make as few specifications as possible to get a nice form. But the special cases make all the difference.

There is also an object-oriented variant for making the specifications, which would then look like this:

```php
        ...
        'First name' => \Studip\Form\TextInput::create(
            'First name',
            _('First name'),
            User::findCurrent()->firstname
        )->setRequired()
        ...
```
This variant has the advantage that the IDE knows what the type (or `type`) of the form field is and you can continue working as with setRequired. The array notation is leaner and clearer, but you have to know what the parameters mean. Basically, the example `type` in the array notation becomes an object of the type `\Studip\Form\ExampleInput` - it does not matter whether the class `ExampleInput` exists in the core of Stud.IP or is provided by a plugin. In this way, a plugin can also provide and display its own form field types. Such a class `ExampleInput` would then be derived from the abstract class `\Studip\Form\Input`.

## The core form field types

The following classes are all located under `lib/classes/forms` or in the PHP namespace `\Studip\Form`.

**TextInput**: This is the most common input type, which simply corresponds to an `<input type='text'>` without much embellishment. Nevertheless, there are a few possible specifications that can be made for all input classes:

1. to mention would be the above setRequired (in the object-oriented version) or `'required' => true` as an array parameter to say that the specification of this field must not be empty. A checkbox that is `required` must be checked.
2. the parameter `'permission' => $GLOBALS['perm']->have_perm('admin)`, with which you define that this form field may only be displayed and evaluated if a `true` is entered here, i.e. you have permission to do so.
3. the parameter `if` or the method `setIfCondition`, with which you define that this form field should only be displayed if a condition is fulfilled. This condition is always checked by Javascript while the form is being filled out. For example, you could display a checkbox, and only if this checkbox is ticked will other form elements appear. This parameter `if` is not there to perform a validation or a security check, but solely for the purpose of clarity of the form. This condition can also contain a Javascript evaluation such as `'if' => 'age > 18'`. The form then knows the values of the other form fields in the JavaScript, as they have just been filled in, and displays the fields accordingly.
4. the `value` parameter or the call in the constructor of the `\Studip\Form\Input` class is used to set the value of the form field. Normally this will be the value of the SORM object, but a completely different value could also be set. If the form has nothing to do with a SORM object, this would of course be necessary, but this is usually only set for individual form fields.
5. with the `store` parameter or the `setStoringFunction` method, you can use a PHP function that takes care of saving the form values once the form has been submitted. If the form saves a SORM element, this function is always set so that the value of the SORM class is saved. You therefore do not need to define anything else here. However, you could also define a function here that writes a value to the UserConfig, for example.
6. with the parameter `mapper` or the method `setMapper` you can define a function that converts the value from the form before it is saved. For example, a written date '7.3.2012' could be converted into a Unix timestamp (number of seconds since 1970), which is then stored in the database (but this is a bad example, because the datetimepicker type already does the conversion in Javascript and only the Unix timestamp is transferred and not the readable equivalent of the date). The specified mapper function is first passed the value from the form as a parameter and the SORM object as a second parameter, if one exists. You often see the `mapper` parameter in combination with the `'type' => 'no'`, the `NoInput`. This is an input field that is not displayed to the user at all, but the mapper function still writes the value of the mapper function to the database when saving, such as the name of the person processing the data.

Now, let's get down to the list of input classes that exist in the core (in alphabetical order):

**CalculatorInput**: Strictly speaking, this is not an input field, but only an output field. A calculation of values is specified here. When editing the announcement, this field uses Javascript to tell the user how many days lie between the start of the announcement and its end, so that you can be sure that there are at least 14 days in between, for example. The parameter `'value' => "Math.floor((expire - date) / 86400)"` then contains a Javascript formula that is permanently evaluated by the form while it is being filled out. Simple entries in Javascript are possible here, but no control entries such as while loops.

**CheckboxInput**: An `<input type='checkbox'>` is displayed here. The `value` is 1 or 0, as it is also entered in the database.

**DatetimepickerInput**: This field is a date and time specification and corresponds to a Unix timestamp. In Stud.IP we use Unix timestamps almost everywhere (the number of seconds from 1.1.1970 Greenwich Mean Time) to save a date in the database. If you want to save a different date format such as ISO date in the database, you should set the parameters as follows: `'value' => strtotime($obj['zeitfuermich'])` and `'mapper' => function ($val) { return date('c', $val); }`.

**HiddenInput**: There is a value in this field in the form, but nothing is visible and nothing can be entered. Presumably you only want to use the value so that you can evaluate it in other form elements in the `if` clause or with the CalculatorInput.

**I18n_formattedInput**: This input class is used to display a WYSIWYG editor - but in several languages, provided that additional `$CONTENT_LANGUAGES` have been entered in the `config_local.inc.php` file. This class takes care of the `$CONTENT_LANGUAGES` and the question of whether they have been entered and how many. If no further `$CONTENT_LANGUAGES` have been entered, no language selector is displayed, as is usual in Stud.IP. And if the WYSIWYG editor is switched off in Stud.IP, only a normal text field with toolbar is displayed. This class does everything automatically. The corresponding field of the SORM class **must** also be declared as an i18n field in the configure method in this form `$config['i18n_fields']['feldname'] = true;`.

**I18n_textareaInput**: This class represents a `<textarea>` which, as just now, may also have a language selector so that you can enter the value in several languages. If you edit a SORM class, this class is automatically selected as `type` if the field in the database has the type `TEXT` and the SORM class in the configure method has something like: `$config['i18n_fields']['feldname'] = true;`

**I18n_textInput**: This class represents an `<input type='text'>` which, as just now, may also have a language selector so that you can enter the value in several languages. If you edit a SORM class, this class is automatically selected as `type` if the field in the database has the type `VARCHAR` and the SORM class in the configure method has something like: `$config['i18n_fields']['feldname'] = true;`

**InputRow**: This is not actually an input class, but an extension of the class `\Studip\Form\Part`. However, you can use this class to group several input fields horizontally. The specification would look something like this:

    'row' => new \Studip\Forms\InputRow(
        [
            'name' => 'field1',
            'label' => _('field 1')
        ],
        [
            'name' => 'field2',
            'label' => _('field 2')
        ],
        [
            'name' => 'field3',
            'label' => _('field 3')
        ]
    )
As you can see, you still have to specify the parameter `name` for the input fields, which is otherwise the index. Otherwise, you can pass any number of input fields or input objects to the constructor of `InputRow`, which are then displayed next to each other.

**MultiselectInput**: This is an input that is usually used to create a relation of a SORM object. The only special thing here is that the request to PHP or the $value of the mapper method and the store method contains an array instead of a string, as is usually the case. If you want to process a SORM relation with this, you should also set the parameters `value` and `mapper` (or `store`) so that this also works. In SORM itself, you only need to set a SimpleORMapCollection as the new value of the SORM object. The SimpleORMap class then already knows which objects need to be added and which need to be deleted. An example from editing the announcement is as follows:

```php
    'newsroles' => [
        'permission' => $GLOBALS['perm']->have_perm('admin'),
        'label' => _('visibility'),
        'value' => $news->news_roles->pluck('roleid'),
        'type' => 'multiselect',
        'options' => array_map(function ($r) {
            return $r->getRolename();
        }, RolePersistence::getAllRoles()),
        'store' => function ($value, $input) {
            $news = $input->getContextObject();
            NewsRoles::update($news->id, $value);
        }
    ]
```

The `options` parameter is used to control which options the user has.

**NewsRangesInput**: This input class is very specific to the announcements and assigns the announcement to areas in Stud.IP such as the start page, facilities, events and personal homepages. Presumably no one will ever be able to use this class for anything other than editing announcements. But this also shows that the form builder can also handle very special form elements. And within the NewsRangesInput there is a Vue component `EditableList` that can be reused.

**NoInput**: There is nothing to see here! That's right, only an `<input type='hidden'>` is actually placed in the form. You can use this to enable evaluations in Javascript for `if` conditions or for the CalculatorInput. And who knows what else you can do with it?

**NumberInput**: This simply specifies an integer. It is also often useful to specify other HTML attributes such as min or max, whereby the RangeInput could also be used. But if not, you would specify these attributes as additional parameters such as `'max' => 20'`.

**QuicksearchInput**: With this input field you have a quicksearch like the PHP class Quicksearch. The purpose is that although an ID should (usually) be entered in the database, users do not want to write an ID. Instead, they enter the name of an event or a person or something else and the quicksearch then sets the ID as a value in the form. For the whole thing to work, you also have to specify the parameter `'searchtype' => new SQLSearch(...)` or similar. So without the searchtype there is no search and therefore no quicksearch.

**RangeInput**: This can be used to enter integers between `max` and possibly `min` (default is between 1 and 10). The input is made using a slider and the current value is then displayed next to it.

**SelectInput**: With this class, a `<select>` is used in the HTML. As in MultiselectInput, the options in it are controlled via the parameter `'options' => ['value1' => _('The first value'), 'value2' => _('The second value')]`. If there is an `ENUM` field in the database, a SelectInput with the values of the ENUM is generated without further specifications of `type` or `options`.

**TextInput**: See above.

**TextareaInput**: A simple class for creating a `<textarea>`. This works in the same way as TextInput.

## Building your own input class

When building a form yourself, you quickly realize that the existing input classes cover a lot, but quite often it is necessary to have a special input field here and there. In this case, new input classes can also be programmed - either for a plugin or for the core of Stud.IP. The following points should be noted:

- Essentially, your own input class must be derived from the abstract class `\Studip\Form\Input` and at least overwrite the `render` method. The `render` method is used to output the HTML structure that should ultimately appear in the form.
- The element is then embedded in a Vue instance. This means that the HTML of the input element can and should use Vue tags such as v-model. **The value of the input field should always be both in the HTML (via `<input type='hidden'>` or similar) and passed to the Vue instance via v-model or other mechanisms.** Both are always necessary so that the input field of your own input class is fully usable - for example by being able to work with the `if` parameter of other input fields, with the CalvulatorInput and with form validation.
- You can of course also use Vue components in the HTML of the input field. PHP classes such as Quicksearch in PHP or jQuery, on the other hand, should be avoided like the devil avoids holy water, because in case of doubt Vue will be re-rendered at the wrong time and then break the beautiful PHP or jQuery. If you use Vue components, you must of course make sure that they are loaded correctly.
- If the request contains something other than a string (or something similar to a string like an integer), you should override the `getRequestValue` method, which then usually executes something like `return \Request::getArray($this->name);`. This means that the class knows that it expects an array (or something similar) from the request. The `mapper` method can then be used to change the output of the `getRequestValue` method. This keeps the input class flexible in its intended use.

There is nothing more to say. You probably just have to try things out a bit and copy the core from the examples.
