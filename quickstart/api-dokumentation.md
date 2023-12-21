---
title: API documentation
---

# Official API documentation

The official API documentation can be found at the URL:

https://docs.gitlab.studip.de/api

can be viewed. The tool [doxygen](https://www.doxygen.nl/index.html) is used for this, which (unlike phpdoc) generates the API documentation quickly and reliably from the source code. The sidebar there contains the following listings:


| value | description |
| ---- | ---- |
|Data Structures|existing classes |
|Class Hierarchy|Classes grouped hierarchically by Inheritance |
|Data Fields|class and instance variables |
|File List|all files |
|Directory Hierarchy|expanded directory structures |
|Examples|List of all examples contained in the comments |
|Globals|List of all global variables and functions |



### Creating the documentation

There is a [Makefile](https://gitlab.studip.de/studip/studip/-/blob/main/Makefile) with target "doc" in the Gitlab, so that the following call:

`1 ~ % make doc`

in the directory `doc/html` generates the corresponding API documentation freshly.

The prerequisite for this is the installation of `doxygen`. If you use Linux, you can usually simply install `doxygen` via the package management. Under Ubuntu, for example, this is sufficient:

`2 ~ % sudo apt-get install doxygen`

In principle, `doxygen` can also be installed for Unix, Mac and Windows from the sources. More detailed information can be found in the [English instructions](https://www.doxygen.nl/manual/install.html).

The configuration for the generation can be found in [tools/Doxyfile](https://gitlab.studip.de/studip/studip/-/blob/main/Doxyfile).
It is particularly easy to create with `doxywizard`.


## How do I write API documentation?

Anyone who has ever worked with `@phpdoc@` or `@javadoc@` will already be familiar with this.
Of course, there are also doxygen's own specifics, which are explained [below](#special features).
But first some best practices on how to document.


#### Top/file-level comments
Every PHP file outside of `/template` must be introduced with a copyright preamble and a description of the contents of the file:

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



### Class comments

Each class must have a docblock describing the use and(!) the usage.

```php
/**
 * This class provides a singleton instance that is used to manage PDO database
 * connections.
 *
 * Example of use:
 *
 * # getting a PDO connection
 * $key = 'studip';
 * $db = DBManager::get($key);
 * $db->query('SELECT * FROM user_info');
 *
 * # setting a PDO connection
 * $manager = DBManager::getInstance();
 * $manager->setConnection('example', 'mysql:host=localhost;dbname=example',
 * 'root', *);
 *
 **/
```


If the class has already been described in detail in the top-level comment, you may refer to it instead: "for a detailed description, see the comment at the beginning of this file".

### Method and function comments

Each function and method must have a docblock that describes what the function/method does and how to use it.
The comments should be descriptive ("Opens the file") and not imperative ("Open the file"). Usually the comment does not need to describe %%how%% the function works.
Such comments should be placed directly in the source code.

The following things should be included in the comment:

* a description of the function
* all arguments and their description
* all possible return values and their description
* if and when the function throws exceptions

Complete sentences must be used. Like classes, functions should be commented descriptively (in the third person).

If getter/setter methods, constructors or destructors do not do anything unexpected, the description of the function/method may be omitted.

````phpregexp
    /***
     * Returns the value of the selected query parameter as a string.
     *
     * @param string $param parameter name
     * @param string $default default value if parameter is not set
     *
     * @return string parameter value as string (if set), else NULL
     */
````

#### Code examples

Code examples can be easily inserted into a comment by semantically including the area with @code and @endcode. For example, the file [DBManager](https://develop.studip.de/trac/browser/trunk/lib/classes/DBManager.class.php#L15) contains the following comment:

```php
/**
 * This class provides a singleton instance that is used to manage PDO database
 * connections.
 *
 * Example of use:
 * @code
 * # get hold of the DBManager's singleton
 * $manager = DBManager::getInstance();
 *
 * [...]
 *
 * @endcode
 */
```
