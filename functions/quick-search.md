---
id: quick-search
title: QuickSearch class
sidebar_label: QuickSearch class
---


In `lib/classes/QuickSearch.class.php` a GUI class is provided with which you can quickly and easily add a single-line search field including an AJAX dropdown menu at any position. Advantages:

* Little and clear source code required.
* AJAX search and Javascript dropdown menu are free.
* The search field can also be used without Javascript and therefore meets the Stud.IP accessibility requirements.
* You can search for practically anything - not just in the database, but anywhere.
* The search field can be configured with additional functions.
* In the event of future changes to these search fields by the GUI commission, the programmer is on the safe side with this class, because only the class has to be changed.

In the HTML there is then the input field that is expected and another invisible input field for the ID. Usually you are looking for something like persons, which have a clearly readable name. But the programmer doesn't actually want the name at this point, but rather a user_id. The user_id is then stored hidden in the background. The programmer can integrate QuickSearch, like an input field in which the user would have magically entered only the user_id - but the user enters the clear name. QuickSearch offers this architecture automatically.

## Installation of a QuickSearch field

Usually, the search consists of two elements that work together: firstly, the search element, which is effectively a model that specifically searches for something, and secondly, the QuickSearch class that executes this search and takes care of the output.

```php
$search = new SQLSearch("SELECT username, surname " .
    "FROM auth_user_md5 " .
    "WHERE lastname LIKE :input " .
    "LIMIT 5", _("Lastname"), "username");
print QuickSearch::get("username", $search)
    ->setInputStyle("width: 240px")
    ->render();
```

The variable `$search` is therefore the object that performs the search. This object is not necessarily an object of the SQLSearch class, but an object of the SearchType class (of which SQLSearch is a subclass). SQLSearch is now a special class that can apply any SQL queries to the database. In this query, `:input` always denotes the search string that a user enters later.

The QuickSearch class then takes care of the output. The first value of the constructor is always the name of the search field in the HTML, for example `<input name="username">`. The second parameter is then the search object that we have previously defined. This is followed by a few methods to further configure the output, such as `setInputStyle("width: 240px")` and the `render()` method then triggers the output of the whole thing.

And this is what it will look like:

Attach:QuickSearch1.png Attach:QuickSearch2.png


## Shortcut

For very simple searches such as searching for a username or a user_id, you can also simply write the class "StandardSearch with parameters "username", "user_id", "Seminar_id", "Institut_id" or "Arbeitsgruppe_id" as the search object. So:

```php
print QuickSearch::get("seminar", new StandardSearch("Seminar_id"))
    ->setInputStyle("width: 240px")
    ->render();
```

## Further methods of the QuickSearch class

* *withButton()* : The search field also gets a magnifying glass; this magnifying glass is a simple submit button. The advantage is simply that you don't have to write three nested DIVs for the design. But please do not use this method in combination with other methods below. This is only for a very simple search field without special styling. Especially setInputStyle will destroy the styling rather than make it better. You can only change the length of the box using withButton(array('width' => "50")) with pixels as the length specification.
* *defaultValue($valueID, $valueName)* : if something should already be entered in the search field, you can enter the name and the corresponding ID here.
* *setInputClass($class)* : Name of a CSS class that is added to the field.
* *setInputStyle($style)* : special information for style="" which is given to the input field.
* *setDescriptionColor($color)* : Color of the description of the text field. The description of the text field only appears as long as the user has not yet written anything and may differ from the normal writing color of the text field.
* *noSelectbox()* : Forces a select box not to be displayed for the search results under any circumstances. Useful for search fields that appear on every page. But beware! This also eliminates the non-JS functionality of the search box.
* *fireJSFunctionOnSelect($function_name)* : The programmer can specify a Javascript function that can further process the selected object. Only specify the name. The Javascript function should expect (item_id, item_name) as parameters. This function should return true so that the result remains in the input after the JS function has been fired. Otherwise it will be deleted automatically.
* *setAttributes($attr_array)* : Additional attributes for the text field, such as a title attribute. To set such a title, `$attr_array = array('title' => 'only one search field')` would be passed. Of course, this also works with "style" or "class" as an attribute.
* *disableAutocomplete($disable = true)* : This can be used to deactivate the AJAX autocomplete for this search field. This is usually done to improve performance. You then no longer get a selection field, but have to press Enter regularly and then get a normal select box. Of course, this property also deactivates all entries from fireJSFunctionOnSelect and can only be combined with noSelectbox to a limited extent. If you want to deactivate all autocompleters for QuickSearches in the system to improve performance, the config setting global -> AJAX_AUTOCOMPLETE_DISABLED is more suitable, as it has the same effect. Only globally for the entire system and without having to touch the source code.

## Further search objects

You are not limited to SQLSearch. Every programmer can define their own search objects and implement a Lucene index search, for example, if they feel like it. The search classes must all be derived from the SearchType class and at least overwrite the `includePath()` and (sensibly) `getResults(...)` methods. If the search class is used in the core of Stud.IP, it should also be stored in the directory `lib/classes/seachtypes/`. But plugin builders can of course also store their search classes in the plugin.

A small example search class could look like this, for example:

```php
class SeminarTypeSearch extends SearchType {

    public function getTitle() {
        return _("Search seminar type");
    }

    public function getResults($input, $contextual_data = array()) {
        $types = $GLOBALS['SEM_TYPE'];
        foreach($types as $key => $type) {
            if (strpos($type['name'], $input) === false) {
                unset($types[$key]);
            } else {
                $types[$key] = array($key, $type['name']);
            }
        }
        return $types;
    }

    public function includePath() {
        return __file__;
    }
}
```

This class is intended for the case that there are a confusing number of semester types in Stud.IP. These types are defined in config.inc.php and are therefore not found in the database. The SQLSearch class is therefore impractical for this purpose.

The `getTitle` method only returns the text that is to appear later in the empty form field.
The method `getResults` does all the work so to speak, searches the array of all seminar types for the entered string and returns a result array of the form `array(array(ID_of_Seminar_Type, Name_of_Type), ...)`.
The method `includePath` is necessary for this class to be found (there is a small internal autoloader), but always has the same content, so it can be used for all extension classes of SearchType.

And that should be it. You can still specify an avatar for your search results, but this is not really suitable for seminar types. Nevertheless, you would do this by overwriting the methods `getAvatar` and `getAvatarImageTag`. See the documentation in the source code.
