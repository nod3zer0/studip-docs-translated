---
id: cache
title: Stud.IP-Cache
sidebar_label: Stud.IP-Cache
---

## Stud.IP-Cache

Stud.IP enthält ein minimales Framework, um beliebige Daten zu cachen. In dem Verzeichnis [lib/classes](https://develop.studip.de/trac/browser/trunk/lib/classes) sind die folgenden Klassen und Interfaces enthalten:

* Klasse `StudipCacheFactory`
* Interface `StudipCache`
* Klasse `StudipDbCache`
* Klasse `StudipFileCache`
* Klasse `StudipNullCache`
* Klasse `StudipCacheProxy`

Seit Version 1.11 ist das Caching fester Bestandteil (StudipFileCache) und standardmässig aktiviert. Ab der Version 4.1 ist `StudipDbCache` die Voreinstellung und es gibt kleine Erweiterungen an der Cache-API (s.u.).

### StudipCacheFactory

Die Aufgabe der `StudipCacheFactory` ist das zur Verfügung stellen eines Stud.IP-weiten Caches. Um die Singleton-Instanz zu erhalten, muss lediglich `StudipCacheFactory::getCache()` aufgerufen werden.

### StudipCache

Das Interface StudipCache definiert die Operationen einer Cache-Instanz:

| Aktion | Beschreibung |
| ---- | ---- |
| `expire($key)` | entfernt ein Schlüssel-Wert-Paar aus dem Cache |
| `flush()` | leert den Cache, d.h. entfernt alle Werte aus dem Cache **[ab Stud.IP 4.1]** |
| `read($key)` | liest den Wert zu einem Schlüssel aus dem Cache oder gibt im Falle eines cache miss `FALSE` zurück |
| `write($key, $value, $expire = 43200)` | legt unter einem Schlüssel für eine gegebene Zeit (in Sekunden, Default: 12 Stunden) einen Wert ab. |


Der Cache kann dazu verwendet werden, über mehrere HTTP-Requests hinweg einen möglicherweise aufwendig zu berechnenden Wert zu speichern. Der Cache garantiert nicht, dass ein Schlüssel-Wert-Paar für die gesamte `expire`-Dauer vorgehalten wird. Er garantiert lediglich, dass das Schlüssel-Wert-Paar nach Ablauf nicht mehr zurückgeliefert wird. Bis einschließlich der Stud.IP Version 4.0 müssen die Werte den Typ `string` haben, ab Version 4.1 können auch Werte beliebigen Typs im Cache abgelegt werden (solange die Werte serialisierbar sind). Der Schlüssel ist immer ein String (maximal 255 Zeichen) und sollte nur ASCII-Zeichen enthalten.

Der Lebenszyklus eines Schlüssel-Wert-Eintrags in den Cache sieht so aus:

* Der Eintrag wird mit `#write` eingetragen.
* Nun kann der Eintrag mit `#read` und dem Schlüssel ausgelesen werden.
* Der Eintrag scheidet unter folgenden Bedingungen aus:
** Die Lebensdauer ist überschritten worden.
** Es wurde explizit `#expire` oder `#flush` aufgerufen.
** Der Cache entfernt den Wert, z.B. aus Platzmangel.

In der Regel sollte man nur Resultate referenziell transparenter Funktionen [cachen](http://de.wikipedia.org/wiki/Memoisierung), andernfalls muss man sich selbst um die Invalidierung des gespeicherten Wertes kümmern, wenn das alte Resultat ungültig wird. Dazu stehen drei Möglichkeiten zur Verfügung:

* Man verwendet die eingestellte Lebensdauer.
* Man entfernt die Einträge per `#expire`.
* Man wählt den Schlüssel geschickt.

Beispiel: Es soll eine Liste der Geburststagskinder angezeigt werden. Da die Berechnung aufwendig ist, soll das Ergebnis gespeichert werden. Ab Mitternacht ist die Liste neu zu berechnen. Unter Berücksichtigung der obigen drei Punkte kann man also folgendermaßen vorgehen:

* Beim Eintragen in den Cache rechnet man aus, wieviele Sekunden noch bis Mitternacht vergehen und stellt die Lebensdauer entsprechend ein.
* Man liest nicht nur die Liste aus dem Cache aus, sondern noch einen zweiten Wert, der angibt, für welchen Tag diese Liste ist. Falls man sich an einem anderen Tag befindet, berechnet man neu.
* Man nimmt den aktuellen Tag im Monat in den Schlüssel des Cache-Eintrags auf: "birthdays/22". Am 22. wird die Liste ausgelesen; nach Mitternacht (der Schlüssel ist ja dann "birthdays/23") findet man keinen Eintrag und berechnet neu.

Ganz offensichtlich ist (in diesem Fall) die letzte Möglichkeit die eleganteste.

#### Konvention
Der Schlüssel eines Cache-Eintrags wird durch Vorwärtsschrägstriche "/" in Namensräume aufgeteilt. Stud.IP-Kerndateien sollten "core/XYZ/argument1/argument2/usw" erzeugen. Stud.IP-Plugins sollten dementsprechend `<plugin>/birthday/22` verwenden. Auf diese Weise sollte es zu keinen Kollisionen kommen.

#### Achtung
Bis zur Version 4.0 musste der Wert eines Cache-Eintrags ein String sein. Arrays oder Objekte müssen daher (de)serialisiert werden.

#### Funktionsbeispiele
```php
//Beispiel beim schwarzenBrettPlugin

// Konstante in der Klasse festlegen
const ARTIKEL_CACHE_KEY = 'plugins/SchwarzesBrettPlugin/artikel/';

//Beispielfunktion
private function getArtikel($thema_id)
{
    // Cache-Objekt erzeugen
    $cache = StudipCacheFactory::getCache();
    // Daten aus dem Cache holen
    $ret = unserialize($cache->read(self::ARTIKEL_CACHE_KEY.$thema_id));

    // Wenn der Cache leer ist, Daten aus der Datenbank holen
    if (empty($ret)) {
        $ret = DBManager::get()->query("SELECT ...")->fetchAll(PDO::FETCH_COLUMN);
        // Daten in den Cache schreiben
        $cache->write(self::ARTIKEL_CACHE_KEY.$thema_id, serialize($ret));
    }
    return $ret;
}
```

### StudipDbCache

Da es sich bei StudipCache nur um ein Interface handelt, muss eine konkrete Implementation zur Verfügung gestellt werden. Die Standardimplementierung des Cache ist seit Stud.IP 4.1 die Klasse `StudipDbCache`, die alle Werte in der Tabelle `cache` der Stud.IP-Datenbank ablegt.

### StudipFileCache

Die Standardimplementierung des Cache war bis Stud.IP 4.0 die Klasse `StudipFileCache`, die alle Werte in Dateien im Stud.IP-Temp Verzeichnis ablegt.

### StudipNullCache

Wird der Cache in der Konfiguration ausgeschaltet (oder wird die CLI-Umgebung verwendet), steht kein Cache zur Verfügung. In diesem Fall wird der StudipNullCache verwendet, der dann zwar von der Factory zurückgeliefert wird und auch entsprechend und gültig antwortet, aber tatsächlich nicht speichert. Ein geschriebener Wert wird also nie zurückgelesen.

### StudipCacheProxy

TODO

### CachePlugins

Weitere cachende Implementationen des Interfaces können über entsprechende Plugins nachträglich installiert werden. Es gibt spezielle PHP-Erweiterungen, die PHP damit nachrüsten bzw. externe Lösung verfügbar machen. Derzeit existieren mindestens folgende Plugins:

* [APCCache](https://plugin-dev.studip.de/index.php/Plugins/00030) : Nutzung der PHP-Erweiterung [APC](http://www.php.net/apc)
* [EAcceleratorCache](https://plugin-dev.studip.de/index.php/Plugins/00031) : Nutzung der PHP-Erweiterung [eAccelerator](http://eaccelerator.net)
* [XCacheCache](https://plugin-dev.studip.de/index.php/Plugins/00032) : Nutzung der PHP-Erweiterung [XCache](http://xcache.lighttpd.net)
* [MemcachedCache](https://develop.studip.de/studip/plugins.php/pluginmarket/presenting/details/c511a822c4ab899e2a6d0b0ec3c05c67) : Nutzung eines [memcached Servers](http://memcached.org/) (libmemcached API)
* [MemcacheCache](https://develop.studip.de/studip/plugins.php/pluginmarket/presenting/details/5c3b6e43090d96816ed6bb69864cf9f3) : Nutzung eines [memcached Servers](http://memcached.org/) (libmemcache API)

Für die Konfiguration der Erweiterung muss die entsprechende Dokumentation befolgt werden, da der Cache sonst nicht funktionieren wird.
