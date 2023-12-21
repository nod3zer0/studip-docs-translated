---
title: Coding Style
---

### Scope

This document provides guidelines for formatting code and documentation for developers working on Stud.IP. The following areas are covered by the Stud.IP Coding Standard:


## PHP file formatting

For files containing only PHP code, the closing tag ("?>") is not allowed. It is not required by PHP, and omitting it prevents blank lines from being accidentally inserted into the response.

**IMPORTANT:** Inclusion of arbitrary binary data by __HALT_COMPILER() is forbidden in PHP files. Its use is only allowed for some installation scripts.

## Indentation

An indent should consist of 4 spaces. Tabs are not allowed.

## Maximum line length

The target line length is 80 characters. Developers should keep each line of code under 80 characters where possible and practicable. However, longer lines are allowed in some cases. The maximum length of a line is 120 characters.

## Line limit

The line limit follows the Unix text file convention. Lines must end with a single line feed character (LF). Line feed characters are represented by a 10 (decimal) or by 0x0A (hexadecimal).

Note: Do not use the carriage return (CR &#8594; 0x0D) or the combination of carriage return and line feed (CRLF &#8594; 0x0D 0x0A).


## Naming conventions

### Classes

Class names may only contain alphanumeric characters. Numbers are allowed in class names, but are not recommended in most cases.

If a class name consists of more than one word, the first letter of each new word must be capitalized.

To define pseudo namespaces, single underscores may be used in class names.

Example: `class Trails_Controller`

As soon as real namespaces are available, these pseudo namespaces must be replaced accordingly.

### File names

Only alphanumeric characters ("a-zA-Z0-9"), underscores ("_"), hyphens ("-") and dots (".") are permitted in file names. Spaces are completely forbidden.

Any file containing PHP code should end with the extension ".php".

File names must correspond to the class names as described above.

### Functions and methods

Method names may only contain alphanumeric characters. Underscores are not permitted. Digits are permitted in function names, but are not recommended in most cases.

Function and method names must always start with a lower case letter. If a method name consists of more than one word, the first letter of each word must be capitalized. This is usually called "camelCase" formatting.

Wordiness is generally encouraged. Function names should be as verbose as possible to explain their purpose and behavior.

Examples of method names:

```php
filterInput()

getElementById()

widgetFactory()
```

For object-oriented programming, access methods for instance or class variables should always begin with ``get` or ``set`. If design patterns should be implemented, the name of the method should follow the conventions of the pattern to better describe the behavior.

Global functions are allowed, but are discouraged in most cases. These functions should be encapsulated in a static class.

### Variables

Variable names may only contain alphanumeric characters and the underscore. Digits are permitted in variables, but are not recommended in most cases.

As with function names (see above), variable names must always begin with a lower case letter.

Speaking identifiers are generally recommended. Variables should always be as verbose as possible to describe the data that the developer intends to store in them. Very short variable names such as `$i` and `$n` are discouraged except for use in small loops. If a loop contains more than 20 lines of code, the index variables should have a more detailed name.

### Constants

Constant identifiers can contain alphanumeric characters and underscores.

All letters used in constant names must be capitalized. Words in a constant name must be separated by underscores.

Example: `EMBED_SUPPRESS_EMBED_EXCEPTION` is permitted, but `EMBED_SUPPRESSEMBEDEXCEPTION` is not.

Constants must be defined as class constants (keyword "const"). Defining constants with the `define` function in the global scope is allowed, but strongly discouraged.


## PHP code delimitation

PHP code must always be delimited with the complete form of the standard PHP tag:

```php
<?php

?>
```

Short tags are only allowed in templates. For files that only contain PHP code, the closing tag must never be specified.

## Strings
### String literals

For string literals, i.e. if a string does not contain any variables, the apostrophe "'" (single quote) should always be used to delimit the string:

```php
$aString = 'Example String';
```


## String literals with apostrophes

If a string literal itself contains apostrophes, it is permitted to delimit the string with double quotes. This is especially useful for SQL statements:

```php
$sql = "SELECT `id`, `name` from `people` "
     . "WHERE `name`='Fred' OR `name`='Susan'";
```

This syntax is preferable to protecting the apostrophe with "\'" for reasons of better readability.

### Variable substitution

Variable substitution is permitted using either of these forms:

```php
$greeting = "Hello $name, welcome back!";

$greeting = "Hello {$name}, welcome back!";
```



For consistency, this form is not permitted:

```php
$greeting = "Hello ${name}, welcome back!";
```


## String concatenation

Strings must be concatenated with the "." operator. A space must always be inserted before and after the "." operator to increase readability:

```php
$company = 'Zend' . ' ' . 'Technologies';
```

If strings are concatenated with the "." operator, the statement should be broken into several lines to increase readability. In these cases, each subsequent line should be filled with whitespace so that the "." operator is exactly below the "=" operator:

```php
$sql = "SELECT `id`, `name` FROM `people` "
     . "WHERE `name` = 'Susan' "
     . "ORDER BY `name` ASC ";
```


## Arrays
### Numerically indexed arrays

Negative indices are not permitted. Such an array may begin with a non-negative number, but this is not recommended.

If indexed arrays are defined in multiple lines using the "array" function, a space must follow each comma delimiter to improve readability:

```php
$sampleArray = array(1, 2, 3, 'Zend', 'Studio');
```

It is allowed to define multi-line indexed arrays with the "array" function. In this case, each subsequent line must be padded with spaces so that the beginning of each line is aligned:

```php
$sampleArray = array(1, 2, 3, 'Zend', 'Studio',
                     $a, $b, $c,
                     56.44, $d, 500);
```


### Associative arrays

If associative arrays are declared with the "array" function, wrapping the statement into multiple lines is permitted. In this case, each subsequent line must be filled with white space so that both the key and the value are below each other:

```php
$sampleArray = array(
    'firstKey' => 'firstValue',
    'secondKey' => 'secondValue'
);
```


## Classes
### Class declaration

Classes must be named according to the naming conventions.

The parenthesis should always be written on the line below the class name.

Each class must have a documentation block that conforms to the PHPDocumentor standard.

All code in the class must be indented with four spaces.

Only one class is allowed in each PHP file.

Placing additional code in class files is allowed, but discouraged.

The following is an example of a valid class declaration:

```php
/**
 * Documentation Block Here
 */
class SampleClass
{
    // all contents of class
    // must be indented four spaces
}
```


### Class variables

Class variables must be named according to the variable naming conventions.

Each variable declared in the class must be listed at the beginning of the class, before the declaration of any methods.

The "var" keyword is not allowed. Class variables define their visibility by using the private, protected, or public modifiers. Public class variables (visibility "public") are allowed, but are discouraged in favor of access methods (getter/setter).

## Functions and methods
### Declaration of functions and methods

Functions must be named according to the function naming convention.

Methods within classes must always define their visibility by using one of the private, protected, or public modifiers.

As with classes, the parenthesis should always be written in the line below the function name. Spaces between the function name and the opening parenthesis for the arguments are not allowed.

Global functions are not recommended.

The following is an example of a valid function declaration in a class:

```php
/**
 * Documentation Block Here
 */
class Foo
{
    /**
     * Documentation Block Here
     */
    public function bar()
    {
        // all contents of function
        // must be indented four spaces
    }
}
```

NOTE: Pass-by-reference is the only {+explicit+} parameter passing mechanism permitted in a method declaration.

```php
/**
 * Documentation Block Here
 */
class Foo
{
    /**
     * Documentation Block Here
     */
    public function bar(&$baz)
    {}
}
```

Call-time pass-by-reference is strictly forbidden.

The return value must not be enclosed in brackets. This can hinder readability and also lead to errors if a method is later changed to return by reference.

```php
/**
 * Documentation Block Here
 */
class Foo
{
    /**
     * WRONG
     */
    public function bar()
    {
        return($this->bar);
    }

    /**
     * RIGHT
     */
    public function bar()
    {
        return $this->bar;
    }
}
```


### Calling functions and methods

As with function declarations, there must be no space between the function name and the opening parenthesis for the arguments when calling the function.

Function arguments should be separated by a single separating space after the comma. The following is an example of a valid call to a function that takes three arguments:

```php
threeArguments(1, 2, 3);
```

Call-time pass-by-reference is strictly prohibited.

In passing arrays as arguments to a function, the function call may include the "array" hint and may be split into multiple lines to improve readability. In such cases, the normal guidelines for writing arrays still apply:

```php
threeArguments(array(1, 2, 3), 2, 3);

threeArguments(array(1, 2, 3, 'Zend', 'Studio',
                     $a, $b, $c,
                     56.44, $d, 500), 2, 3);
```


## Control structures
### if/else/elseif

Control statements based on the if and elseif constructs must have a single space before the opening parenthesis of the conditional and a single space after the closing parenthesis.

Within the conditional statements between the parentheses, operators must be separated by spaces for readability. Inner parentheses are encouraged to improve logical grouping for larger conditional expressions.

The opening brace is written on the same line as the conditional statement. The closing brace is always written on its own line. Any content within the braces must be indented using four spaces.

```php
if ($a != 2) {
    $a = 2;
}
```



For "if" statements that include "elseif" or "else", the formatting conventions are similar to the "if" construct. The following examples demonstrate proper formatting for "if" statements with "else" and/or "elseif" constructs:

```php
if ($a != 2) {
    $a = 2;
} else {
   $a = 7;
}

if ($a != 2) {
    $a = 2;
} elseif ($a == 3) {
   $a = 4;
} else {
   $a = 7;
}
```


PHP allows statements to be written without braces in some circumstances. This coding standard makes no differentiation - all "if", "elseif" or "else" statements must use braces.

Use of the "elseif" construct is permitted but strongly discouraged in favor of the "else if" combination.

### Switch

Control statements written with the "switch" statement must have a single space before the opening parenthesis of the conditional statement and after the closing parenthesis.

All content within the "switch" statement must be indented using four spaces. Content under each "case" statement must be indented using an additional four spaces.

```php
switch ($numPeople) {
    case 1:
        break;

    case 2:
        break;

    default:
        break;
}
```


The construct default should never be omitted from a switch statement.

NOTE: It is sometimes useful to write a case statement which falls through to the next case by not including a break or return within that case. To distinguish these cases from bugs, any case statement where break or return are omitted should contain a comment indicating that the break was intentionally omitted.

## Inline Documentation
### Documentation Format

All documentation blocks ("docblocks") must be compatible with the phpDocumentor format. Describing the phpDocumentor format is beyond the scope of this document. For more information, visit: http://phpdoc.org/

All class files must contain a "file-level" docblock at the top of each file and a "class-level" docblock immediately above each class. Examples of such docblocks can be found below.

### Files

Every file that contains PHP code must have a docblock at the top of the file that contains these phpDocumentor tags at a minimum:

```php
/**
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * LICENSE: Some license information
 *
 * @author first name last name <email>
 * @copyright 2008 Zend Technologies
 * @license http://framework.zend.com/license BSD License
 * @category Stud.IP
*/
```

Optional tags:
```php
/**
 * @package calendar
 * @link http://framework.zend.com/package/PackageName
 * @since File available since Release 1.5.0
*/
```

### Classes

Every class must have a docblock that contains {-these phpDocumentor tags-} at a minimum:

```php
/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 */
```

Optional tags:
```php
/**
 * @link http://framework.zend.com/package/PackageName
 * @since Class available since Release 1.5.0
 * @deprecated Class deprecated in Release 2.0.0
 */
```

### Functions

Every function, including object methods, must have a docblock that contains at a minimum:

A description of the function

All of the arguments

All of the possible return values


It is not necessary to use the "@access" tag because the access level is already known from the "public", "private", or "protected" modifier used to declare the function.

If a function/method may throw an exception, use @throws for all known exception classes:

```php
@throws exceptionclass [description]
```


## Templates

All of the above statements apply to templates. However, the following rules also apply:

For short tag assignments, exactly one space must be inserted after the opening tag and before the closing tag:

```php
<div class="<?= $css_class ?>"></div>
```

Semicolons are not used.

To increase readability, the alternative control structures can be used:

```php
<? if (true) : ?>
...
<? else : ?>
...
<? endif ?>

<? foreach ($array() as $key => $value) : ?>
...
<? endforeach ?>

etc.
```

Please note that the colons are each enclosed with a space. The final `endif`, `endforeach` etc. must not be ended with a semicolon (just like the usual {}).



### Goals

Coding standards are important in any software project, especially when many developers are working on it. Coding standards help to ensure that the code is of high quality, has fewer errors and is easy to maintain.


### Pagelevel doc block for copy&paste

This paragraph is non-normative.

```php
/**
 * filename - Short description for file
 *
 * Long description for file (if any)...
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author name <email>
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category Stud.IP
 */
```
