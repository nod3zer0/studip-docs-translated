---
id: url-helper
title: URLHelper
sidebar_label: URLHelper
---


## Using the URLHelper class


To simplify the conversion of existing PHP code to tabbed browsing and to generally reduce the use of session variables to a sensible level, the class `URLHelper` was introduced with Lifter001. New code must use this class to create links to other pages (or the same page) in Stud.IP. External links and areas of Stud.IP, which already use their own functions for link generation, are excluded from the use of the `URLHelper`. These are in detail:


* Links to external pages (e.g. help)
* Links to static content (images, videos, etc.)
* Links to actions in a plugin (`PluginEngine::getLink()`)
* Links to documents in the download area (`GetDownloadLink()`)
* Links to trails controllers (`Trails_Controller::url_for()`)


All other links - especially links from plugins to pages in the Stud.IP core system - must be changed so that they use the class `URLHelper` to generate the URL.


### General


The main purpose of this class is to be able to add additional URL parameters to all links on a page as required without having to adapt all links again and again. Especially with links created by auxiliary functions or classes, it would sometimes not be (sensibly) possible to adapt them in this way.


The basic idea is relatively simple: there is a global list of "automatic" link parameters - i.e. registered with the `URLHelper` class - and a helper function *getLink()*, which adds these registered parameters to a passed URL. The caller of `getLink()` therefore does not have to worry about which additional parameters are to be inserted. In Stud.IP, this mechanism is used, for example, to pass on the currently selected event or the view options set on a page with each click without having to save them in the session on the server side (which would inevitably lead to problems with tabbed browsing).


A simple example could look like this:


```php
// $view contains the selected view
URLHelper::addLinkParam('view', $view)


[...]


switch ($view) {
    case 'show': // normal view of the page
        [...]
    case 'search': // show search results
        [...]
    case 'edit': // edit page
        [...]
}


[...]


// Generate output (can also be in the template)
echo '<a href="'.URLHelper::getLink(*, array('page' => 25)).'">...</a>';
```


The current content of the variable `$view` is then automatically added to the link created in this way and you get something like:


```php
<a href="?page=25&amp;view=edit">
```


Of course, each link can also contain its own parameters that are specific to this link. These would then be specified directly in the call to `getLink()` and not registered globally as parameters. Parameters specified locally in the call have priority over those registered globally, i.e. parameters registered for individual links can be given different values or hidden completely if required (set parameters to `NULL` when calling).


### Methods of the `URLHelper` class


The most important operations of the class `URLHelper` are collected and documented here. These are *class methods*, i.e. the call is made via `URLHelper::`*Name*.


**addLinkParam($name, $value)**


  Registers a link parameter with the specified name and value. If there is already a parameter with the same name, the old value is replaced by the new one. Any existing binding (see `bindLinkParam()`) is removed.


* **bindLinkParam($name, &$var)**


  Binds a link parameter to the specified PHP variable. If there is already a parameter with the same name, the old value is replaced by the binding. In contrast to `addLinkParam()`, the concrete value of this parameter is not set directly in this operation, but is only determined *when* `getLink()` or `getURL()` is called by reading the specified variable. So if you change the value of this variable after calling `bindLinkParam()`, the current value is always used.


  In addition, this call initializes the specified variable with the value of the URL parameter in the REQUEST environment of the page. This function is particularly useful for storing status previously saved in session variables in URL parameters.


**removeLinkParam($name)**


  Removes a previously registered link parameter. If no parameter of this name was registered, nothing happens.


* **getLinkParams()**


  Returns a list (an array with name/value pairs) of all currently registered parameters. This could be used, for example, as *hidden* fields in a FORM to avoid length restrictions on URL parameters.


* **getLink($url = *, $params = NULL)**


  Adds all currently registered parameters to the URL passed. In the case of parameters bound to variables, the current value of the respective variable at the time of the call is used. If the second (optional) parameter is passed, further parameters can be set whose values are also added to the URL.


  In the case of parameters with the same name, the following applies: Entries in the `$params` array have priority over parameters in the `$url`. Parameters from the transferred `$url` have priority over registered parameters. If you want to hide a registered parameter completely from the URL, you must give it a value of `NULL` in the `$params` array.


  The result of this function is an *entity-encoded URL*, i.e. it can be used directly in attributes in the HTML (*action* of a FORM, *href* of an A element). If you need the unencoded URL, `getURL()` should be used.


* **getURL($url = *, $params = NULL)**


  This function works exactly like `getLink()`, but does not return an entity-encoded value, but the unencoded URL. This can then be used for calls via JavaScript, for example.


### Problems with URL parameters


The use of the `URLHelper` can also cause new problems that do not exist or do not exist to the same extent when using session variables. Not all types of session data are suitable for transfer via the URL, so that it must be considered on a case-by-case basis whether a changeover makes sense. The following points should be taken into account:


* Length restriction of URLs


  There is a browser-dependent maximum length limit for URLs in the order of a few thousand characters (typically 2048 characters for Internet Explorer, 8192 characters for Firefox). Longer URLs are truncated and therefore lead to a loss of information. Therefore, if you want to transfer a very complex state via the URL - e.g. any number of expanded nodes in a tree view - it may be better to save this on the server side and only store a reference to a saved configuration in the URL.

* Manipulation of URL parameters by the user


  You should always be aware of the fact that URL parameters - in contrast to session data - can be changed by the user at will, so you should never rely on them not being manipulated by the user. If you were to move a user's authorizations previously stored in the session to the URL, you would have to check them every time the page is called up.


* Name collisions with parameters of different pages


  When generating URLs, the `URLHelper` does not distinguish which page a link refers to. It is also not possible to register parameters only for certain destinations (as dispatchers are increasingly being used in Stud.IP, this would not help much either). You should therefore take care to avoid name collisions for parameters of different pages managed via the URLHelper, either by using unique prefixes (*wiki_search*) or by comparing them with the list of names already in use.


### Examples


Some small examples of the use of the `URLHelper` from practical use in Stud.IP should be collected here. Unfortunately there is not much here yet...


```php
$link = URLHelper::getLink('wiki.php', array('keyword' => $keyword));
echo '<a href="'.$link.'">'.htmlReady($keyword).'</a>';
```


### URLHelper for Javascript


Independently of this, there is also a [URLHelper for Javascript](HowToJavascript), which has a similar API and also does similar things. However, these two URLHelpers are not coordinated and are completely independent of each other.
