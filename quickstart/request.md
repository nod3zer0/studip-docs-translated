---
id: request
title: Request
sidebar_label: Request
---


## Using the Request class

### General

The `Request` class is intended to standardize and simplify access to request parameters. It enables type-safe queries of values (e.g. as a number or option value) and always delivers the same results regardless of the settings of `register_globals` and `magic_quotes_gpc`. There are also a number of auxiliary functions for querying request properties such as the current URL, the server name or the request method. In contrast to `$_REQUEST`, the class only evaluates GET and POST parameters, but not cookies.

There are generally two options for accessing the parameters: Direct access via static methods of the class (such as `Request::get($param)`) and alternatively array access via an instance of the class (see `Request::getInstance()`). The latter is particularly useful if you want to run through the list of all parameters or pass it to a function.

### Using the `Request` class

#### Querying request parameters

Parameters from the current request (i.e. GET and POST parameters) should be queried in Stud.IP using the methods of the `Request` class. This can validate the parameters - e.g. that the value passed is actually a number - and ensures that the results are independent of PHP settings such as `magic_quotes_gpc`. There are specific query functions for different types.

The following methods are used for type-safe access to request parameters that contain scalar values. If there is no parameter with the specified name (or the parameter in the call has the wrong type), the value `NULL` is returned or the passed default value, if this was specified:

| Function | Description |
| ---- | ---- |
| `get($param, $default = NULL)` | Returns the value of a parameter as a string. **Caution**: The value is returned as specified by the user.
| `quoted($param, $default = NULL)`| Returns the value of a parameter as a string |
| `username($param, $default = NULL)`| Returns the value of a parameter as a user ID. A user ID consists only of letters and numbers as well as the characters "_", "@", "." and "-". |
| `option($param, $default = NULL)`| Returns the value of a parameter as an option value. An option value consists only of letters, digits and underscores. |
| `int($param, $default = NULL)`| Returns the value of a parameter as an integer value. |
| `float($param, $default = NULL)` | Returns the value of a parameter as a floating point number. |

Some examples:

```php
if (!Request::submitted('reset')) {
    $title = Request::get('title'); // title can contain any characters
    $inst_id = Request::option('inst_id'); // IDs are always alphanumeric
    $sem_id = Request::option('sem_id');
    $page = Request::int('page', 1); // page number is an integer
}

$days = Request::int('days', 14); // default to 14 days
$category = Request::option('category'); // like "wiki", "forum" or "news"
$enable = Request::int('enable');
```

Similarly, there are also methods for type-safe access to request parameters that contain an array as a value (e.g. a list of user IDs). The individual values are handled in the same way as the corresponding methods for scalar values. If there is no parameter with the specified name, an empty array is returned here:

* `getArray($param)`
* `quotedArray($param)`
* `usernameArray($param)`
* `optionArray($param)`
* `intArray($param)`
* `floatArray($param)`

Example:

```php
$institutes = Request::optionArray('institutes');
$is_enabled = Request::intArray('is_enabled');
```

#### Listing request parameters

If you want to run through the complete list of request parameters, you can do this using an instance of the `Request` class. This is then to be used in exactly the same way as the `$_REQUEST` array, but it always behaves as if *magic_quotes_gpc* were switched off:

* `getInstance()`

  Returns the singleton instance of the request class. This object can be used to access the current request parameters directly with array notation or to iterate them using a `foreach` loop. The type-safe access methods listed above can also be called via the object.

Example:

```php
$request = Request::getInstance();

$user = $request['user']; // alternatively: $request->get('user')
$mode = $request['mode']; // alternatively: $request->option('mode')

foreach ($request as $key => $value) {
    [...]
}
```

#### Evaluating buttons in forms

There is a special function for evaluating whether a specific button in a form has been clicked (the name of the parameter actually passed is not identical in every browser). The name of the button must be specified:

* `submitted($param)`

  Tests whether a form button (INPUT or BUTTON) with the transferred name has been clicked.

Example:

```php
if (Request::submitted('add_user')) {
    $cmd = 'add_user';
}
```

#### Evaluation of request properties

There are a number of other auxiliary functions for querying general properties of the request.
These include the current URL and the name of the server or the request method (typically `GET` or `POST`).
The most important of these are briefly listed here:

* `url()`

  Returns the complete URL of the current page.

* `method()`

  Returns the request method used for the call (`GET`, `POST`, `HEAD` or similar).

* `isAjax()`

  Queries whether it is an Ajax request (i.e. `XmlHttpRequest` from jQuery or prototype).

Example:

```php
if (Request::isAjax()) {
    $this->set_layout(null);
}
```

Further details can be found in the corresponding [API documentation](http://hilfe.studip.de/api/class_request.html).
