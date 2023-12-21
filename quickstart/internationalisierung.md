---
title: Localization (L10n)
---

In software development, localization refers to the adaptation of content (books, film art, homepages), processes, products and in particular computer programs (software) to the local linguistic and cultural conditions prevailing in a specific geographical or ethnic sales or usage area (country, region or ethnic group).

The English word for localization is localization (American/British English) or localization (British English) and is often abbreviated to L10N in software development. The 10 is the number of omitted letters. (In contrast, I18N stands for internationalization).

> Wikipedia, L10N http://de.wikipedia.org/wiki/L10N

Localization is important in various places:
The translations of the texts in the code are automatically extracted at irregular intervals
and translated collaboratively on the [Transifex](https://www.transifex.com/projects/p/studip/) platform.


# Internationalization in the PHP code

Stud.IP uses the gettext package, which is also used in many other software projects, for internationalization.

There is a separation between the preparation of the localized output of texts (internationalization, this is the task of each programmer) and the actual translation of the texts with the help of special tools such as "kbabel" (localization, this is the task of the maintainer of a language file).

The source language used in the source code is German for Stud.IP.

All strings in the system must not be in the HTML parts of the source files, but must be written from PHP sections.
The strings to be translated are included in the program code in the special function `gettext()`. Only the short form should be used, realized in PHP as `_()`.

```php
echo _("My events")
```

The strings to be translated should contain plain text, no HTML structure of the page and no program code such as variable names.

Incorrect:
```php
echo _("<tr><td>My events</td></tr>");
```
Correct:

```php
echo "<tr><td>" . _("My events") . "</td></tr>";
```

Or correct:

```php
printf("<tr><td>%s</td></tr>", _(" My events "));
```

Incorrect:

```php
print _("error§No authorization!§");
```

Correct:

```php
printf("error§%s§", _("No authorization!"));
```

Incorrect:
```php
echo _("You have $count new messages.");
```
Also wrong:
```php
echo _("You have ") . $count . _(" new messages.");
```
Correct:
```php
printf(_("You have %s new messages."), $count);
```

The strings enclosed in a `gettext()` should contain complete sentences or blocks of information, i.e. not be pieced together from individual substrings (see above).

If the two previous rules are mutually exclusive, e.g. because part of a sentence is being formatted, the latter rule takes precedence (the translator needs basic html knowledge anyway).

Incorrect:
```php
echo _("You can translate this file ") . "<b>" . _("not") . "</b>" . _(" delete");
```
Correct:
```php
echo _("You can <b>not</b> delete this file");
```

Complicated html expressions, such as a clickable icon in the text, should be extracted from the string via `%s`

Correct:
```php
printf(_("Under %s you get to your appointments."), "<a href><img src = \"pictures/icon-lit.gif\"></a>");
```

## Text buttons

Labeled form buttons that need to be translated are generally not integrated directly into the code,
but always generated via the [Button-Api](Buttons), which then takes care of the localization.


# Internationalization in the JS code


In order to benefit from the existing gettext translations in JavaScript code, we use a special web service in Stud.IP that converts selected translations into JavaScript code and makes them available for the l10n.js library](http://purl.eligrey.com/l10n.js) written by [Eli Grey.

## Web service

The web service can be found in every Stud.IP installation from version 2.0 under the URL: `dispatch.php/localizations/{locale}`

The German translations can therefore be found on the official development server of the Stud.IP Core Group at:

`http://develop.studip.de/studip/dispatch.php/localizations/de_DE`

and the English translations at:

`http://develop.studip.de/studip/dispatch.php/localizations/en_GB`

can be accessed.

If you specify an unavailable country code, the web service returns the status code 406 (Not acceptable) and a JSON list with the locales that are actually available.

This web service is automatically included by the following files (and therefore on almost every page), whereby the activated locale is used in each case:

* `lib/include/html_head.inc.php`
* `templates/layouts/base.php`
* `templates/layouts/base_without_infobox.php`

These pages also automatically include the above-mentioned JavaScript library [l10n.js](http://purl.eligrey.com/l10n.js).

### JavaScript API

The official JavaScript client API contains the method [`Object#toLocaleString`](https://developer.mozilla.org/en/Core_JavaScript_1.5_Reference/Global_Objects/Object/toLocaleString), which is defined as follows:

> Returns a string representing the object. This method is meant to be overridden by derived objects for locale-specific purposes.

For strings, this method calls `String#toString`. This is where the library comes in and redefines the existing method.

So if you now want to translate a string into JavaScript, you simply call `toLocaleString`.


Example:

```javascript
var aString = "search".toLocaleString();

// results in de_DE if locale is activated:
// aString === "search"

// whereas en_GB is active:
// aString === "search"
```

Only the strings contained in the list of the web service are translated. Strings not included remain as they are.


## Include new strings

New strings can simply be marked in the JavaScript using the `String.toLocaleString()` method mentioned above.
The CLI script `extract-js-localizations.php` should then be triggered, which extracts these strings and writes them to the file [`app/views/localizations/show.php`](https://develop.studip.de/trac/browser/trunk/app/views/localizations/show.php). This means that the translation mechanism is also available for these strings.


# I18N

Since Stud.IP 3.5 it is possible to save database contents in an internationalized format. The corresponding functionality can be easily used with SimpleORMap classes without having to write a lot of code. The following example shows how certain fields of a SimpleORMap class can be internationalized.

### Example of internationalization

In the following, the SimpleORMap class ResourceProperty is extended by two internationalized fields.
ResourceProperty stores resource properties and has the fields "description" for a description of the property and "display_name" to define the name of the property to be displayed.

#### Customization of the SimpleORMap class

In the static configure method, the following entries are added to the associative array $config:

```php
$config['i18n_fields']['display_name'] = true;
$config['i18n_fields']['description'] = true;
```

This means that the SimpleORMap class has already been adapted to internationalized data fields.

#### Use in the view

International data fields are displayed by calling static methods of the I18N class, which generate the appropriate HTML input fields.
A text area is required for the "description" field, whereas a simple input field is required for "display_name".
The following code is inserted in the view to display the fields:

```php
<label>
<?= _('description')?>
<?= I18N::textarea('description', $property->description) ?>
</label>
<label>
<?= _('Displayed name') ?>
<?= I18N::input('display_name', $property->display_name) ?>
</label>
```

This displays the internationalized data fields in the appropriate input fields.
The methods `I18N::textarea()` and `I18N::input()` take the name of the field in the HTML form as the first parameter, which is added to the input fields as the name attribute, and the value of the field as the second parameter.

#### Processing input

If an HTML form has been sent in which internationalized data fields are present, the contents of the data fields
are read using the method `Request::i18n()` instead of `Request::get()`. The contents can then be assigned directly to the database fields of the SimpleORMap class.
In the case of the ResourceProperty class used as an example, this looks like this:

```php
//Read the data fields from the request:
$description = Request::i18n('description');
$display_name = Request::i18n('display_name');

//Assign to the ResourceProperty object:
$property->description = $description;
$property->display_name = $display_name;

//Save:
if ($property->isDirty()) {
$property->store();
}
```

