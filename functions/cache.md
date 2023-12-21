---
id: cache
title: Stud.IP-Cache
sidebar_label: Stud.IP-Cache
---

## Stud.IP cache

Stud.IP contains a minimal framework for caching any data. The following classes and interfaces are contained in the directory [lib/classes](https://develop.studip.de/trac/browser/trunk/lib/classes):

* Class `StudipCacheFactory`
* Interface `StudipCache`
* Class `StudipDbCache`
Class `StudipFileCache` * Class `StudipNull`
Class `StudipNullCache` * Class `StudipCacheFactory
* Class `StudipCacheProxy`

Since version 1.11, caching has been a fixed component (StudipFileCache) and is activated by default. As of version 4.1, `StudipDbCache` is the default setting and there are small extensions to the cache API (see below).

### StudipCacheFactory

The task of the `StudipCacheFactory` is to provide a Stud.IP-wide cache. To get the singleton instance, only `StudipCacheFactory::getCache()` has to be called.

### StudipCache

The StudipCache interface defines the operations of a cache instance:

| Action | Description |
| ---- | ---- |
| `expire($key)` | removes a key-value pair from the cache |
| `flush()` | empties the cache, i.e. removes all values from the cache **[from Stud.IP 4.1]** |
| `read($key)` | reads the value for a key from the cache or returns `FALSE` in the case of a cache miss |
| `write($key, $value, $expire = 43200)` | stores a value under a key for a given time (in seconds, default: 12 hours). |


The cache can be used to store a value over several HTTP requests, which may be complex to calculate. The cache does not guarantee that a key-value pair will be stored for the entire `expire` duration. It only guarantees that the key-value pair will not be returned after expiration. Up to and including Stud.IP version 4.0 the values must have the type `string`, from version 4.1 values of any type can also be stored in the cache (as long as the values can be serialized). The key is always a string (maximum 255 characters) and should only contain ASCII characters.

The life cycle of a key-value entry in the cache looks like this:

* The entry is entered with `#write`.
* Now the entry can be read with `#read` and the key.
* The entry is rejected under the following conditions:
** The lifetime has been exceeded.
** Explicit `#expire` or `#flush` was called.
** The cache removes the value, e.g. due to lack of space.

As a rule, you should only [cache](http://de.wikipedia.org/wiki/Memoisierung) the results of referentially transparent functions, otherwise you will have to take care of invalidating the stored value yourself if the old result becomes invalid. There are three ways to do this:

* Use the set lifetime.
* Remove the entries using `#expire`.
* Select the key skillfully.

Example: A list of birthdays is to be displayed. As the calculation is time-consuming, the result should be saved. The list is to be recalculated from midnight. Taking the above three points into account, you can proceed as follows:

* When entering into the cache, calculate how many seconds are left until midnight and set the lifetime accordingly.
* You not only read the list from the cache, but also a second value that specifies the day for which this list is valid. If you are on a different day, you recalculate.
* Include the current day of the month in the key of the cache entry: "birthdays/22". The list is read out on the 22nd; after midnight (the key is then "birthdays/23") no entry is found and a new calculation is performed.

Obviously (in this case) the last option is the most elegant.

#### Convention
The key of a cache entry is divided into namespaces by forward slashes "/". Stud.IP core files should generate "core/XYZ/argument1/argument2/usw". Stud.IP plugins should use `<plugin>/birthday/22` accordingly. This way there should be no collisions.

#### Attention
Up to version 4.0, the value of a cache entry had to be a string. Arrays or objects must therefore be (de)serialized.

#### Function examples
```php
//Example with the schwarzBrettPlugin

// Set constant in the class
const ARTIKEL_CACHE_KEY = 'plugins/BlackBoardPlugin/article/';

//Example function
private function getArticle($thema_id)
{
    // create cache object
    $cache = StudipCacheFactory::getCache();
    // Get data from the cache
    $ret = unserialize($cache->read(self::ARTICLE_CACHE_KEY.$thema_id));

    // If the cache is empty, retrieve data from the database
    if (empty($ret)) {
        $ret = DBManager::get()->query("SELECT ...")->fetchAll(PDO::FETCH_COLUMN);
        // Write data to the cache
        $cache->write(self::ARTICLE_CACHE_KEY.$thema_id, serialize($ret));
    }
    return $ret;
}
```

### StudipDbCache

As StudipCache is only an interface, a concrete implementation must be made available. The standard implementation of the cache since Stud.IP 4.1 is the class `StudipDbCache`, which stores all values in the `cache` table of the Stud.IP database.

### StudipFileCache

Until Stud.IP 4.0 the standard implementation of the cache was the class `StudipFileCache`, which stores all values in files in the Stud.IP-Temp directory.

### StudipNullCache

If the cache is switched off in the configuration (or if the CLI environment is used), no cache is available. In this case, the StudipNullCache is used, which is then returned by the factory and also responds accordingly and validly, but does not actually save. A written value is therefore never read back.

### StudipCacheProxy

TODO

### CachePlugins

Further caching implementations of the interface can be installed subsequently using corresponding plugins. There are special PHP extensions that retrofit PHP with this or make external solutions available. At least the following plugins currently exist:

* [APCCache](https://plugin-dev.studip.de/index.php/Plugins/00030) : Use of the PHP extension [APC](http://www.php.net/apc)
* [EAcceleratorCache](https://plugin-dev.studip.de/index.php/Plugins/00031) : Use of the PHP extension [eAccelerator](http://eaccelerator.net)
* [XCacheCache](https://plugin-dev.studip.de/index.php/Plugins/00032) : Use of the PHP extension [XCache](http://xcache.lighttpd.net)
* [MemcachedCache](https://develop.studip.de/studip/plugins.php/pluginmarket/presenting/details/c511a822c4ab899e2a6d0b0ec3c05c67) : Use of a [memcached server](http://memcached.org/) (libmemcached API)
* [MemcacheCache](https://develop.studip.de/studip/plugins.php/pluginmarket/presenting/details/5c3b6e43090d96816ed6bb69864cf9f3) : Use of a [memcached server](http://memcached.org/) (libmemcache API)

For the configuration of the extension, the corresponding documentation must be followed, otherwise the cache will not work.
