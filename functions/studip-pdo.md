---
id: studip-pdo
title: StudipPDO
sidebar_label: StudipPDO
---


Stud.IP uses its own classes derived from `PDO` and `PDOStatement` for database access. `DBManager::get()` always automatically returns an instance of `StudipPDO`. You can use this object in the same way as a standard PDO object, but it also contains a few extensions that make it easier to work with the database.

[StudipPDO](https://gitlab.studip.de/studip/studip/-/blob/main/lib/classes/StudipPDO.class.php)

## Automatic parameter recognition, additional parameter types

The parameters passed to a statement are mapped to a suitable PDO parameter type based on the PHP type. This means, for example, that if a passed parameter is `NULL` in PHP, it is also replaced in the query as an SQL `NULL`. There are also new parameter types available:

### `StudipPDO::PARAM_ARRAY`
The parameter type allows the transfer of an array, which is then expanded to a value list. To be used with the `IN ()` construct in SQL.

Example:
```php
$st = DBManager:get()->prepare("SELECT * FROM seminare WHERE status IN(?)");
$st->execute([[1,2,3,4]]);
```

is executed as
```sql
SELECT * FROM seminare WHERE status IN (1, 2, 3, 4)
```

### `StudipPDO::PARAM_COLUMN`
This parameter type allows the transfer of a string that is used in the SQL query without further processing. This allows a parameter to be used in the `ORDER BY` part, for example. Because this can be a security problem, every non-word character is filtered out of the parameter.

Example:
```php
$st = DBManager:get()->prepare("SELECT * FROM auth_user_md5 WHERE perms IN (:perms) ORDER BY :sorter");
$st->bindValue(':status', ['tutor','lecturer']);
$st->bindValue(':sorter', 'Last name', StudipPDO::PARAM_COLUMN);
```

is executed as
```sql
SELECT * FROM auth_user_md5 WHERE perms IN ('tutor','dozent') ORDER BY last name
```

## Prepared statements to go

As prepared statements are somewhat cumbersome in the application, an abbreviation for frequently used variants has been added. This also saves having to enter the PDO constants for the fetch mode. These fetch methods are also available directly in the StudipPDO object, with the option of specifying a query and the parameters directly.

Examples:
```php
<?php
$db = DBManager::get();

//for DELETE UPDATE etc
$db->execute("DELETE FROM xxx WHERE id=?", [$id]);

//get only the first result of the query as assoc array
$db->fetchOne("SELECT * FROM xxx WHERE id=?", [$id]);

//get only the value of the first column of the first result of the query
$db->fetchColumn("SELECT id FROM xxx WHERE id=?", [$id]);

//get all as array, first column as key, second as value
$db->fetchPairs("SELECT id,value FROM xxx WHERE id IN (?)", [$ids]);

//get everything as an array, only the values of the first column
$db->fetchFirst("SELECT value FROM xxx WHERE id IN (?)", [$ids]);

//get everything as assoc array,
$db->fetchAll("SELECT * FROM xxx WHERE id IN (?)", [$ids]);

//get all as assoc array, the first column becomes the key,
//the rest is grouped if there are several rows for a key,
// only the first one is returned
$db->fetchGrouped("SELECT id, xxx.* FROM xxx WHERE id IN (?)", [$ids]);

//get everything as assoc array, the first column becomes the key, the second is
//grouped and returned as an array
//(Note Arne:) replaced: fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP)
$db->fetchGroupedPairs("SELECT id, value FROM xxx WHERE id IN (?)", [$ids]);

//third parameter can be a callable for all methods that return arrays
//e.g. to implement an aggregate function for the groupings
$count = function ($a) {
    return count($a);
};
$db->fetchGroupedPairs("SELECT id, value FROM xxx WHERE id IN (?)", [$ids], $count);

//the result would be the same as here
$db->fetchPairs("SELECT id, COUNT(*) FROM xxx WHERE id IN (?) GROUP BY id", [$ids]);
```
