---
title: JavaScript
---

In Stud.IP, JavaScript is used more and more for extended and simplified operation or simply for particularly beautiful effects.

## Code conventions for JavaScript

As a programmer, you basically only have to ensure that the global namespace is kept reasonably clean (i.e. global variables must be avoided) and that the code conventions summarized in Lifters005 are adhered to. The code conventions are completely taken over from [Douglas Crockford's "Code Conventions for the JavaScript Programming Language"](http://javascript.crockford.com/code.html).

As far as the namespace is concerned, all special Stud.IP functions should be appended below the STUDIP object. So if I program some functions for the news, I start with:

```js
STUDIP.news = {
    openclose: function (id) {},
    open: function (id) {},
    close: function (id) {}
}
```

and then fill these methods with life. Plugin programmers should also adhere to this convention, although they must take special care to ensure that their method names are actually unique. If two different plug-ins implement a `STUDIP.go` method, at least one of the two plug-ins will cry. It makes sense to insert the unique class name of the plugin between them, either via `STUDIP.pluginclassname.go` or possibly just `pluginclassname.go`.

## Own Stud.IP library

For all JavaScript programmers it will be of interest that some functions are already implemented in Stud.IP, which could also be useful elsewhere. The advantage is obvious: the code remains small and can be better extended later with functionalities.

### Existing methods


|method |applicable to |behavior |to be considered |
---- | ---- | ---- | ---- |
| $.showAjaxNotification(%%position=left%%); | All elements | The element is preceded by an AJAX indicator. | The indicator can also be positioned behind the element using the parameter %%position="right"%%. The indicator is positioned absolutely, which can lead to problems with changes to elements in the vicinity of the actual element. |
| $.hideAjaxNotification(); | All elements | The AJAX indicator associated with the element is removed, if available. | - |

### Existing behavior patterns via CSS classes


|class |applicable to |behavior |to be considered |
| ---- | ---- | ---- | ---- |
| .add_toolbar | <textarea /> | The element is preceded by a menu bar with simplified formatting options. | Only the standard Stud.IP button set can be used in this way. |
| .load_via_ajax | <a /> | A specified URL (either *metadata.url* or the URL of the given link) is loaded via AJAX into an element (either *metadata.target* or the element following the given element). | The element that receives the AJAX indicator can be specified via CSS rule using *metadata.indicator*. Further parameters for calling the URL can be specified via *metadata*. |
| .load_via_ajax.internal_message | <a /> | Special case for internal messages. | The parameters are adapted to the conditions in *lib/sms_func.inc.php*. |
| .resizable | <textarea /> | The height of the element can be changed using a slider at the bottom. | - |

#### AJAX requests

AJAX requests should be made via jQuery using the methods `[.load](http://api.jquery.com/load/)` `[.get](http://api.jquery.com/get/)` or `[.ajax](http://api.jquery.com/jQuery.ajax/)`. Most AJAX calls have an AJAX indicator that tells the user that something is being loaded. If this indicator is unsolicited, you can embed the AJAX method as follows:

```JavaScript
STUDIP.ajax_indicator = false;
$('#dynamic_area').load(url);
STUDIP.ajax_indicator = true;
```

#### URLHelper in JavaScript

The object `STUDIP.URLHelper` offers similar functionality for JavaScript as the [URLHelper in PHP](URLHelper). However, it should not be forgotten that both URLHelpers are completely independent of each other and cannot communicate with each other. So why do you need a URLHelper in JavaScript? For example, to:

* Generate a link where a JavaScript file would otherwise not know the address of the server. So write `STUDIP.URLHelper.getURL("about.php")` to get a URI-encoded path to http://www.studip......de/about.php, or `STUDIP.URLHelper.getURL("about.php")` to get the same as non-URI-encoded.
* Adding variables to any URL without worrying about which variables are already in the URL and which are not. So `STUDIP.URLHelper.getURL(alte_url, {hello: "world"})` becomes a http:.../about.php?hello=world, regardless of whether alte_url had already specified a value for hello or not. You also don't have to worry about whether you append the parameter with "?" or an "&". Please also note that parameters in the old_url have less priority than parameters in the second argument.
* Add variables permanently (i.e. as long as the HTML page is in use) to generated URLs. This can be done with the method `STUDIP.URLHelper.addLinkParam("hello", "world")`. After this call, each `STUDIP.URLHelper.getURL("about.php")` will return, for example, http://..../about.php?hallo=welt. This method also overwrites parameters from the submitted URL, i.e. `STUDIP.URLHelper.getURL("about.php?hello=me")` would still return world as the content of the hello parameter. However, this is not the case with `STUDIP.URLHelper.getURL("about.php", {hello: "me"})`, where the second parameter once again has priority.
* Permanently remove variables from generated URLs if they were previously included. To do this, enter addLinkParam("hello", "") as above and the parameter hello is always considered to be empty and is also deleted from existing URLs if it was previously present.
* All hyperlinks of a section of the document must be provided with the current parameter. After one or more addLinkParam calls, you could call `STUDIP.URLHelper.updateAllLinks("#container");`, whereby all links within the CSS selector "#container" are replaced once by the STUDIP.URLHelper.getURL(...) method and thus receive current parameters. If no selector is specified, the links of the entire document are replaced.

[#caching](#caching)
#### Caching in JavaScript

Since version 3.2 Stud.IP offers an abstraction of caching in JavaScript via `STUDIP.Cache`. An instance is obtained using `STUDIP.Cache.getInstance()` with the optional parameter `prefix`. If possible, this prefix should always be used and chosen sensibly, as it can prevent conflicts when accessing cache data. If the prefix is set, it is guaranteed that the cache can only access data "below" this prefix without having to specify a corresponding mechanism for each individual cache operation:

```JavaScript
let cache0 = STUDIP.Cache.getInstance('foo.');
let cache1 = STUDIP.Cache.getInstance();

cache0.set('test', 42);

console.log(cache0.get('test'), cache1.get('foo.test'));

cache1.set('foo.test', 23);

console.log(cache0.get('test'), cache1.get('foo.test'));
```

The cache supports the following operations:

| function | description |
| ---- | ---- |
|`has(key)`|Inquires whether the cache has a value for the key |
|`get(key, setter, expires)`|Gets a value for the key. If no value is set and `setter` is defined, the value is generated and saved with the specified runtime.
|`set(key, value, expires)`|Saves a value for the specified key with the specified runtime (`expires = false` means that the value is deleted as soon as the browser window is closed).
|`remove(key)`|Deletes the stored value for the specified key.
|`prune()`|Deletes all stored data. |

**Note**: If data is only to be saved for one user or a certain session, a suitable prefix that contains the corresponding data (hashed) should be used. The cache in JavaScript knows nothing of the conditions on the PHP side.

## The jQuery framework in Stud.IP

Currently (Stud.IP 4.1) jQuery 3.2.1 and jQuery-UI 1.12.1 are used. All functions of jQuery-UI are loaded in Stud.IP.


### Other JS libraries used

An overview of the currently used JS libraries can be found in `package.json`.

#### jQuery plugins

##### TableSorter [(Link)](http://tablesorter.com)
* [jquery.tablesorter.js](https://develop.studip.de/trac/browser/trunk/public/assets/javascripts/jquery.tablesorter.js?rev=19220)
* [jquery.tablesorter.min.js](https://develop.studip.de/trac/browser/trunk/public/assets/javascripts/jquery.tablesorter.min.js?rev=19220)
* [jquery.tablesorter.pager.js](https://develop.studip.de/trac/browser/trunk/public/assets/javascripts/jquery.tablesorter.pager.js?rev=19220) TableSorter-Pagination-Plugin

This plugin provides similar options to the previous TableKit plugin (see above) and enables flexible client-side sorting of tables.

#### jQuery UI Multiselect [(Link)](http://www.quasipartikel.at/multiselect/)
* [ui.multiselect.js](https://develop.studip.de/trac/browser/trunk/public/assets/javascripts/ui.multiselect.js?rev=19220)

The jQuery UI Multiselect plugin converts "multiple select inputs" into sexier looking equivalents. The plugin has been patched in the following changesets:
* [https://develop.studip.de/trac/changeset/18594/trunk/public/assets/javascripts/ui.multiselect.js r18594](https://develop.studip.de/trac/changeset/18594/trunk/public/assets/javascripts/ui.multiselect.js r18594)
* [https://develop.studip.de/trac/changeset/18635/trunk/public/assets/javascripts/ui.multiselect.js r18635](https://develop.studip.de/trac/changeset/18635/trunk/public/assets/javascripts/ui.multiselect.js r18635)

#### JS-L10n [(Link)](https://github.com/eligrey/l10n.js/)
* [l10n.js](https://develop.studip.de/trac/browser/trunk/public/assets/javascripts/l10n.js?rev=19220)

This library is used to be able to use localized strings in JS. Further information is already documented in the [Wiki](quickstart/Internationalization).

## FAQ

#### How do I modularize my JavaScript code?
In Stud.IP, code may be written according to ECMAScript2015 and better, which is then compiled to ES5. So if I want to distribute my code to several files, I simply use the "import" statement, a language feature of JavaScript that is well described on MDN: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import

To do this, I create a second file and then enter an "import" statement with the relative path to this file in my first file.

#### How do I integrate npm libraries?
This can be shown using the example of "lodash": The lodash library is installed via npm: "npm i --save-dev lodash". Then I enter in my file:
```JavaScript
import lodash from "lodash"
```

#### What do I have to write as the module name for `import`?

The exact details can be found here: https://webpack.js.org/concepts/module-resolution/#resolving-rules-in-webpack

Here is a short summary:

If I refer to my own code, I write down a relative path:

```JavaScript
import '../src/file1';
import './file2';
```

If I want to import a library, I use the module name of the library:

```JavaScript
import lodash from 'lodash';
import 'module/lib/file';
```


#### I only want to load code/assets when required. Where do I have to enter them and how do I load them?

Dynamic reloading is currently being standardized in ECMAScript (this feature is currently in stage 3 at the beginning of 2019) The current status is documented in https://github.com/tc39/proposal-dynamic-import.

Nevertheless, you can already work with it thanks to webpack. To do this, I simply load a file using the function-like "import()" expression.

Here is an example:

```JavaScript
import('/modules/my-module.js')
  .then((module) => {
    // Do something with the module.
  }).catch(error => 'An error occurred while loading the module');
```

Detailed documentation can be found here:

* https://webpack.js.org/guides/code-splitting/#dynamic-imports
* https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import#Dynamic_Imports

#### I have a plugin that would also like to pack its components. How do I tell the Stud.IP core system or its webpack?

Plugins take care of their own business. If a plugin developer wants to use webpack, he does it for his plugin himself.

#### How can I execute the same function for Document Ready and the Update dialog?

As of version Stud.IP 4.4 there is the event `studip-ready`, which combines the events `ready` and `dialog-update` and is triggered in both cases. Prior to Stud.IP 4.4, the same function must be bound manually to the two events.

#### What is the word separator for JavaScript files?

JavaScript files should be stored in kebab case style (i.e. `one-two.js` and not `one_two.js`).
