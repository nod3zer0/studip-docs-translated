---
title: Periodic AJAX Updates
---

Imagine a plugin that wants to retrieve information about which buddies are currently online without reloading the Stud.IP page (e.g. the OnlineBadge plugin from Osnabrück). A messenger would like to check every few seconds whether the user has received any new messages. And the site itself wants to check whether there is a new post in the forum. For Stud.IP, this was previously only possible in a cumbersome way. With common social networks, however, this is common practice.

As of Stud.IP 2.2, there is a central mechanism to prevent three AJAX requests per second being started in the above case. This would drive even powerful servers to the brink of despair. If the plugins use the UpdateInformation class to get the data, this relieves the server and the programmer, as they no longer have to worry about some things.

# Usecase 1: Plugins

A plugin that is supposed to display users who are online, for example, uses the UpdateInformation class, which is located in the lib/classes/UpdateInformation.class.php file. The plugin should be a system plugin so that it (i.e. the constructor) is called before the trails controller app/controllers/jsupdater.php comes into play and retrieves the data again.

In the PHP source code of the system plugin's constructor, the call is then something like this:

```php
UpdateInformation::setInformation('myplugin', $data)
```

The PHP variable $data (a string, a number or an array) is then passed to the implementation of the updater on the JavaScript page.

The plugin must register on the JavaScript page in order to receive these notifications:

```javascript
STUDIP.JSUpdater.register("myplugin", receiveCallback, sendCallbackOrData)
```

Then the following happens:

* The Javascript function STUDIP.JSUpdater.call is called every few seconds (sometimes more frequently, sometimes faster, depending on server speed, data situation and user activity) and calls the page dispatch.php/jsupdater/get itself via Ajax request.
* Behind dispatch.php/jsupdater is the trails controller in app/controllers/jsupdater.php and its method get_action().
* Before the controller does anything, the constructors of the system plugins are activated. The plugin myplugin collects data and passes it on to UpdateInformation::setInformation. The data is now registered and ready to be passed on.
* It is now the turn of JsupdaterController to call UpdateInformation::getInformation().
* UpdateInformation::getInformation() returns the data it has received from the plugins as an array.
* JsupdaterController takes this huge array as a JSON object.
* This JSON object arrives at the Javascript function STUDIP.JSUpdater.processUpdate, which forwards the individual notifications to the corresponding registered handlers.

# Usecase 2: Core functionality

Even if it is sometimes forgotten: functionality in Stud.IP is not only provided by plugins. Core functionality that wants to use periodic AJAX updates does not use the UpdateInformation class, but simply extends the JsUpdaterController#coreInformation() method by a few lines. An array $data is initialized there and returned at the end, which is associative. The index entries are the name of the Javascript function (without "STUDIP." in front) and the values are then arbitrary.

On the PHP side, there are these other methods for accessing passed data in Javascript:

* `UpdateInformation::hasData($index)` - checks whether data is available under the specified index
* `UpdateInformation::getData($index)` - returns the data under the specified index (or `null` if no data is available).

On the JavaScript side, the API of the JS updater looks like this:

* `STUDIP.JSUpdater.start()` - Starts the JS updater
* `STUDIP.JSUpdater.stop()` - Stops the JS updater
* `STUDIP.JSUpdater.register(index, receiveCallback, sendCallbackOrData)` - Registers a new object with the updater under the specified index. Returned data is processed by the specified `receiveCallback` and data specified in `sendCallbackOrData` is also sent each time the updater is called. `sendCallbackOrData` can be either a regular JavaScript array, an object or a function that returns the data dynamically.
* `STUDIP.JSUpdater.unregister(index)` - Removes a previously registered object.

The JS-Updater has been optimized so that, on the one hand, only a single call of the updater takes place at any time and, on the other hand, it reacts better to load situations on the server. The number of queries is also reduced if the window with Stud.IP is in the background and therefore inactive.
