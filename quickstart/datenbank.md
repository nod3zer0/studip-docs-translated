---
id: database
title: Access to the Stud.IP database
sidebar_label: Database
---


The database in Stud.IP comprises about 100 tables, whereby a number of tables can still be added by plugins, for example. This is a small and much too late attempt to document these tables.

There are some things in the tables that would never be obvious to a programmer if they simply looked at the source code. The documentation is not intended to replace a look into PhpMyAdmin. That's why the documentation is not complete and doesn't worry too much about exact type specifications. Instead, it explains fields such as seminare.duration_time, which contain numbers from -1 to infinity, but which can all mean something different.

## Basic conventions

| type | description |
| ---- | ---- |
| In the tables of entities such as seminaries or institutes, md5 hashes are very often used as primary keys. This has the historical background that it is almost impossible to guess IDs in this way. Numerical IDs can be guessed very well. Of course, this is an inferior protection against hacker attacks on protected data. This is why Stud.IP now protects data differently. But once primary keys have been set, it is very difficult to convert them back into numerical IDs. This is why the md5 hashes have always been used, with the exception of some newer tables. |
| Stud.IP only uses integer values as Unix timestamps (if there is an exception, it confirms the rule), i.e. the number of seconds since 1.1.1970. Most tables have two fields 'mkdate' and 'chdate', which should always be filled in. `mkdate` is the time when the data record was created and `chdate` is the time when the data record was last changed.

## Tables

#### auth_user_md5
The auth_user_md5 table is one of the most important tables for the programmer because it represents the user. This table is used to manage the login and contains the user's email address. There is a second table that is linked 1-to-1 with auth_user_md5, namely user_info. It contains some more information such as hobbies and so on.

| table | description |
| ---- | ---- |
| **user_id** | A unique identification number, primary key of the table. |
| **username** | The login ID, should be used in URLs instead of the user_id to make it look nicer. |
| **password** | The password encrypted using an MD5 hash. This means that even a system administrator cannot read the password from the database. The password field is empty for users who are authenticated via LDAP or in some other way. |
| **perms** | Global user rights. In Stud.IP, a user has global rights so that it is clear, for example, that he can create courses as a 'lecturer'. There is also a local rights system which has little to do with this. In principle, every Stud.IP user should not have more local rights than global rights anywhere in the system. |
| First name** | First name of the user, capitalized as a field |
| Last name** | Last name of the user, also capitalized as a field. |
| **Email** | Email address of the user, also capitalized as a field. It should be treated as a UNIQUE field, even if it is not formally. |
| **auth_plugin** | Indicates whether and how a user is authenticated. If the field is empty (NULL) or filled with "standard", the user is authenticated in Stud.IP using the password stored in `password`. Other entries result in an AuthPlugin taking over the authentication. |
| **locked** | with 1 the user is locked and can therefore no longer log in, with 0 the user is not locked. |



#### Institute

Attention, this table is even capitalized in the name! It is therefore the only real table in Stud.IP that is capitalized.

This table contains all information on faculties/institutions/institutes/classes in Stud.IP.

| Attribute | Description |
| ---- | ---- |
| **Institut_id:** | Primary key of the table. An institution is identified via the Institut_id (note the capitalization!). |

#### seminar_cycle_dates

Metadates are stored in this table, which is nothing other than regular dates. For example, if a course has a Monday appointment from 10 a.m. to 12 p.m., this includes a number of 12 appointments that are in the 'appointments' table, but there is also a meta appointment that represents all 12 appointments at once. If you change the meta appointment, all entries in the 'appointments' table are also changed - at least this is what the responsible classes in Stud.IP do.

Since there are not only weekly appointments in the university area, but also bi-weekly appointments and appointments that take place on the first Thursday of every month, the matter with the meta appointments has become somewhat complicated.

| Attribute | Description |
| ---- | ---- |
| **weekday** | A number from 1 to 7, where 1 stands for Monday and 7 for Sunday. |
**week_offset** | Number of weeks that the event does not yet start at the beginning of the semester. So if 0, the appointment starts exactly when the semester has started, if 1, one week later and so on. |
| **cycle** | Either 0, 1 or 2, where 0 stands for weekly, 1 for bi-weekly and 2 for tri-weekly. |


## Database access

### PDO
Stud.IP uses the MySQL database by default. To access these databases, both the DBManager class was created and PDO was used. All database accesses in Stud.IP should now work directly via PDO. In the source code, this means that a database access looks something like this:

```php
$db = DBManager::get();
$result = $db->query("SELECT * FROM user_info WHERE LastName = '".$name."'")->fetchAll();
foreach ($result as $user) {
}
```

The DBManager takes care of the connection to the database and PDO controls the accesses as soon as the connection object $db has been initialized.

Further information on PDO can be found at [php.net](http://de2.php.net/manual/en/class.pdo.php).

More examples and extended functionality can be found here:
[StudipPDO](StudipPDO)

### Slave accesses

If you have read accesses whose correctness does not have to be 100% guaranteed (autocompleter), you can also address the slave to increase performance:

```php
$db = DBManager::get("studip-slave");
$result = $db->query("SELECT * FROM user_info WHERE LastName = '".$name."'")->fetchAll();
foreach ($result as $user) {
}
```

If the installation does not use replication, the requests to the slave are automatically directed to the (single) master.

# SQL injections
Important to prevent SQL injections: There are two methods to get ahead of nasty database hacks by injecting malicious SQL code. The above example is basically not yet protected.

1) Whenever you insert a potentially dangerous variable into an SQL statement, the variable should be inserted via $db->quote($name). The source code then looks like this:

```php
$db = DBManager::get();
$result = $db->query("SELECT * FROM user_info WHERE LastName = '".$db->quote($name)."'")->fetchAll();
foreach ($result as $user) {
}
```

2) You should always pay attention to the performance of a system like Stud.IP. It can be very useful to prepare frequently occurring commands via prepare so that the database does not have to implement the same command each time.

```php
$db = DBManager::get();
$preparation = $db->prepare("SELECT * FROM auth_user_md5 WHERE Lastname = :name", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$preparation->execute(array('name' => $name));
$result = $preparation->fetchAll();
foreach ($result as $user) {
}
```

In case of doubt, this is the clean variant because it makes life easier for the database and also automatically prevents SQL injections.
