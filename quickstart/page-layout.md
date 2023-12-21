An API is available in Stud.IP via the PageLayout class, which enables various adjustments to the basic HTML structure of the output. This includes simple things such as setting the page title, but also makes it possible to add or remove HTML elements in the `<head>` area of the page, for example to integrate your own style sheets or JavaScipt files.

## The `PageLayout` class

The basic HTML structure is adapted using the new class `PageLayout`. The class offers a range of static methods that cover the various options.

### Page title

| Function | Description |
| ---- | ---- |
| **setTitle($title)** | Sets the current page title, both for display in the browser window and in Stud.IP. |

Example:
```php
PageLayout::setTitle(_('Homepage'));
```

| function | description |
| ---- | ---- |
| **getTitle()** | Returns the current page title. |
| **hasTitle()** | Queries whether a page title has been set for the current page. |


### Help

| Function | Description |
| ---- | ---- |
| **setHelpKeyword($help_keyword)** | Sets the help topic for the displayed page. This is then transmitted to the help server when the help function is called. |


Example:
```php
PageLayout::setHelpKeyword('Basis.Startseite');
```

| function | description |
| ---- | ---- |
| **getHelpKeyword()** | Returns the set help topic. |


#### Tab navigation
| Function | Description |
| ---- | ---- |
| **setTabNavigation($path)** | Sets the path in the navigation tree where the tab navigation starts. The two levels below the specified navigation point are then displayed as tabs (1st and 2nd level). The default setting is the active element of the main navigation. An explicit setting is only necessary for navigation contexts with tab display that are integrated at a location other than the main navigation (e.g. the imprint). You can also switch off the display of the tab navigation completely by passing `NULL` as *$path*. |

Example:
```php
PageLayout::setTabNavigation('/links/siteinfo');
```

| function | description |
| ---- | ---- |
| **getTabNavigation()** | Returns the tab navigation. |

### Add content


| Function | Description |
| ---- | ---- |
| **addStyle($content)** | Adds a new CSS style element to the page header. |

Example:
```php
PageLayout::addStyle('#highlight { background-color: red; }');
```

| function | description |
| ---- | ---- |
|**addStylesheet($source, $attributes = [])** | Inserts a reference to a style sheet in the page header. *$source* can either be a complete URL or a file name that is resolved relative to the assets directory. Optionally, further attributes can be passed for the LINK element. |

Example:
```php
PageLayout::addStylesheet('print.css', ['media' => 'print']);
```

| function | description |
| ---- | ---- |
|**addScript($source)** | Includes another JavaScript file in the page header. *$source* can either be a complete URL or a file name that is resolved relative to the assets directory. |

Example:
```php
PageLayout::addScript($this->getPluginURL() . '/vote.js');
```

| function | description |
| ---- | ---- |
|**addHeadElement($name, $attributes = [], $content = NULL)** | Inserts any HTML element into the page header. *$name*, *$attributes* and *$content* correspond to the name, the attribute list and the content of the created element. If *$content* is `NULL`, the element is not closed (like META or LINK), otherwise a closing tag is automatically output after the content (e.g. for SCRIPT). |

Example:
```php
PageLayout::addHeadElement('link', [
    'rel' => 'alternate',
    'type' => 'application/rss+xml',
    'title' => 'RSS',
    'href' => $feed_url,
]);
```

| function | description |
| ---- | ---- |
|**addBodyElements($html)** | Inserts any HTML fragment directly at the beginning of the BODY in the page output. This is particularly useful in plugins that want to output content on any Stud.IP pages. |

### Remove content

| Function | Description |
| ---- | ---- |
|**removeStylesheet($source, $attributes = [])** | Removes a reference to a style sheet from the page header. As with **addStylesheet**, *$source* can be either a complete URL or a file name that is resolved relative to the assets directory. |

Example:
```php
PageLayout::removeStylesheet('style.css');
```

| function | description |
| ---- | ---- |
|**removeScript($source)** | Removes an integrated JavaScript file from the page header. As with **addScript**, *$source* can be either a complete URL or a file name that is resolved relative to the assets directory. |
|**removeHeadElement($name, $attributes = [])** | Removes all elements with the specified name and attributes from the page header. |

Example:
```php
PageLayout::removeHeadElement('link', ['rel' => 'stylesheet']); // remove all style sheets
```

### Display of messages

| function | description |
| ---- | ---- |
|**postMessage(MessageBox $message)** | Causes the system to display the specified [`MessageBox` object](MessageBox) at the next opportunity, i.e. the next time a layout is output. The message remains stored until it has been displayed, even across (possibly multiple) redirects.  |

For each type of `MessageBox` there is also a separate `post<type>` method on the `PageLayout` object, such as `PageLayout::postSuccess()` or `PageLayout::postError()`.

Example:
```php
PageLayout::postMessage(MessageBox::success('Entry deleted'));
// Equivalent:
PageLayout::postSuccess('Entry deleted');
```

| Function | Description |
| ---- | ---- |
|**clearMessages()** | Deletes all messages that have been stored for display and have not yet been output. |

### Confirm actions

| Function | Description |
| ---- | ---- |
|**postQuestion($question, $accept_url = "", $decline_url = "")** | Obtains a confirmation from the user for a specific action. If the execution of the action is confirmed, a POST request is sent to the specified `$accept_url`, otherwise the `$decline_url` is called via GET. Further details can be found in the first section under [Modal dialog](ModalDialog#server). The mechanism works in the same way as `postMessage()`, so that the confirmation is displayed at the next opportunity. |

Example:
```php
PageLayout::postQuestion(
    'Are you sure you want to perform this action?
    URLHelper::getURL('dispatch.php/foo/confimed')
);
```

### Display of the page header

| function | description |
| ---- | ---- |
|**disableHeader()** | Suppresses the display of the page header with the navigation area, e.g. for a print view (but this should be better solved with a print style sheet) or a pop-up window. |

### Set the Id attribute of the `<body>` element

| function | description |
| ---- | ---- |
|**setBodyElementId($id)** | Sets the Id of the `<body>` element in order to be able to address elements more specifically in CSS or Javascript, for example.
| **getBodyElementId()** | Returns the set Id of the `<body>` element. If no Id was set, `false` is returned. |

### Replace the quick search

| Function | Description |
| ---- | ---- |
|**addCustomQuicksearch($html)** | Replaces the quick search (top right) with any HTML.
| **hasCustomQuicksearch()** | Queries whether the quick search has been replaced.
| **getCustomQuicksearch()** | Returns the HTML code that is to replace the quick search. If no HTML was set by `addCustomQuicksearch()`, this method returns `null`. |

## Example

Finally, a small example from a plugin that (among other things) comes with its own CSS file:

```php
PageLayout::setTitle('Latest activities');
PageLayout::setHelpKeyword('Plugins.Activities');
PageLayout::addStylesheet($this->getPluginURL() . '/css/activities.css');
```
