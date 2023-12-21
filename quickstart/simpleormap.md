---
title: SimpleORMap
---

Since Stud.IP version 1.4, the SimpleORMap class (often abbreviated to SORM) offers simple object-relational mapping according to the Active Record pattern. With its help, the use of SQL code can be greatly reduced, so that in most cases it is no longer relevant for controllers what the name of the database table in which the data is stored is.


### General

Each class derived from SimpleORMap belongs to a database table. An instance of the class then corresponds to a data record of the corresponding table, and thus enables normal read and write operations (also called "CRUD operations") on the database. All columns of the table become virtual attributes of the class. The class fulfills the ArrayAccess interface, which allows the attributes to be accessed in the same way as an array. The attributes are not case-sensitive.

#### The SimpleORMap class

The SimpleORMap class is the base class that provides some functions for simply reading objects from database tables.

#### The SimpleORMapCollection class

The SimpleORMapCollection class manages a collection of SimpleORMap objects. It is used when fetching multiple objects from the database and can be treated like a normal array, as it implements the ArrayAccess class (via a few other derivations).

In addition, it has a few methods that an array does not have and facilitates the processing of SimpleORMap objects and their attributes. The most important of these are presented below.

##### pluck() - Find the value of an attribute of all objects with the method

For example, if you only want to find the user ID of all participants in an event, you can find this using the pluck() method of the SimpleORMapCollection class:

```php
$memberIds = Course::find($id)->members->pluck('user_id');
```

`$memberIds` contains an array with all user IDs.

##### filter() - Filter objects

The filter() method allows objects in a SimpleORMapCollection to be filtered according to user-defined criteria, whereby it in turn returns a SimpleORMapcollection object. To do this, it requires a callback function that decides for each object whether the filter criterion is fulfilled or not. If this function returns false, the criterion is not fulfilled. If it is fulfilled, however, it returns true.

Example: Find all user IDs of all lecturers of a course:

```php
$dozenten_ids = Course::find($seminar_id)->members->filter(function ($m) {
    return $m['status'] === 'lecturer';
})->pluck('user_id');
```

`$m` is an object of CourseMember, whereby `$m['status']` can be used to query whether the member is a lecturer in the course or not.

##### Further methods

There are a few more methods of SimpleORMapCollection, which are only briefly described here:

* **map**: Modifies all elements of the SimpleORMapCollection object using a function that accepts an element and returns anything else. The result of `map` is an array and not a SimpleORMapCollection object, because only elements of SimpleORMap may appear in a SimpleORMapCollection object.
**toGroupedArray**: returns an array of elements, where the keys of the array are equal to the IDs of the elements. This is useful for quickly finding the element with a specific ID within the set.
**first**: Returns only the first element of the SimpleORMapCollection object.
**last**: Returns only the last element of the SimpleORMapCollection object.
**val**: Returns the value of a specific attribute from the first element. For example, `Course::find($id)->members->val('status');` returns the status of the first participant in the SimpleORMapCollection.


### Creating a SimpleORMap class for a database table

A class for a database table extends the SimpleORMap class. It is important to set the name of the database table. This is done in the static method configure(), which can be passed an array of configuration parameters if required.
```php
class HelloWorld extends SimpleORMap
{
    protected static function configure ($config = [])
    {
        $config['db_table'] = 'hallo_welt';
        parent::configure($config);
    }
}
```

The table columns are automatically read from the database, just like the primary key. The metadata is cached in the Stud.IP cache, so this must also be emptied after table changes. This data can be obtained using the SimpleORMap::getTableMetadata() method. The primary key of a data set can always be read using the getId() method, and it is also mapped to a virtual property id. This means that if a table has a column id, it should also be the primary key.

#### Documentation in the source code

It is common for SimpleORMap classes to create documentation in the source code that describes the usable attributes. This makes it easier for other developers to use the class, as there is no need to look at the database schema to find out which attributes are available.

For documentation purposes (as an example with the above HalloWelt class), a block with the following schema is inserted above the class definition:
```php
/**
 * @property int id database column
 * @property string user_id database column
 * @property string greeting database column
**/
```

### Read objects from the database

#### using the primary key

##### Get a single object

The find() method is used to find an existing data record using the primary key. If a primary key is passed to the constructor, an existing data record with this primary key is loaded. If the data record was not found, null is returned.

```php
$id = 1;

$course = Course::find($id);
if ($course) {
    echo $course->name;
}
```

##### Get many objects

If you need a set of objects that you have determined using a list of primary keys, you can use the findMany() method. This takes an array of keys and optionally an ORDER BY part as the second parameter.

```php
$courses = Course::findMany($course_ids, "ORDER BY name");
```

##### Get many objects and process them directly

If, on the other hand, you do not want to create a set of objects but process them, the methods mentioned are still available in a findEach... and findAndMap... version. These methods require a "callable" as the first parameter, and they iterate through the data records found and call the callable with an object in each case. FindEach... returns the number of iterated objects, while findAndMap... returns an array with the return values of the callable.

```php
//creates an array with course titles
$courses = Course::findAndMapMany(function ($course) {
    return $course->getFullname('number-name-semester');
}, $course_ids, "ORDER BY name");
```


#### using SQL statements

Every SimpleORMap object has a whole series of findBy methods due to the inheritance of SimpleORMap. The most important of these is findBySQL(), as this method can be used to pass the part of an SQL query that should be to the right of the WHERE part of the SQL query. The second parameter of the findBySQL() method consists of an associative array containing parameters to be included in the query. The return is always an array (more precisely a SimpleORMapCollection) of SimpleORMap objects of the corresponding class.

```php
$courses = Course::findBySQL("name LIKE ? ORDER BY name", [$search]);
```

##### Finding individual objects using SQL statements

If you only want to retrieve one object using the query, you can use findOneBySQL() instead. In this case, only the first element of the query is created and returned as an object.
```php
$newest_course = Course::findOneBySQL("1 ORDER BY mkdate");
```

##### Finding objects based on their attributes

A SimpleORMap object automatically also has findBy methods for queries for all attributes (database columns) that have been defined, so that queries of the following type are possible:

```php
$courses = Course::findManyByStatus([1,4,5,7], "ORDER BY status,name");

$courses = Course::findByStatus(1, "ORDER BY status,name");

$course = Course::findOneByStatus(1, "ORDER BY mkdate");
```

Similarly, as of Stud.IP 4.2, entries can be counted or deleted using an attribute value:

```php
// Count all hidden courses in the system
$hidden_courses = Course::countByVisible(0);

// Delete all courses with the course number "TODO"
Course::deleteByEventNumber('TODO');
```

### Editing an object

After loading an object from the database, it can be changed using the attributes. To do this, simply set the attributes to other values:
```php
$course = Course::find($id); //load
$course->name = 'New course'; //change
$course->store(); //save
```

#### Save

To save the changed values of an object in the database, call its store() method. There is no automatic saving, so that changes that have not been transferred to the database using store() are lost.

store() returns a number indicating the number of changed data records (as relations may be saved, this can also be > 1). It can also return false, which means that the storage was interrupted, e.g. due to an error or a callback (`before_store`, `before_update`) that prevented the storage.


#### Recognize changes before saving

If you want to check whether the object has changed since it was last read, you can call the isDirty() method. Similarly, you can check a single attribute for changes with isFieldDirty($field).


#### Undoing changes

A change can be undone with revertValue(). The original value, if available, can be retrieved with getPristineValue().


#### Example for saving:

In the following, a course object (which reflects an event) is loaded, changed, checked for changes, the changes are reverted and saved.

```php
$course = Course::find($id);
$course->name; // "Old course";
$course->name = 'New course';
$course->isDirty(); // is true
$course->isFieldDirty('number'); // is false
$course->getPristineValue('name'); // returns "Old event"
$course->revertValue('name'); // undo the changes
$course->store(); //returns 0, as there are no more changes (changes have been reverted)
```


### Creating an object

To create a new data set, create a new object, assign required values via its attributes and call the store() method:

```php
$course = new Course();
$course->name = 'New course';
$course->store();
```

As no value was set for the primary key in this example, a new key is implicitly generated before the store(). With a single-value key, it is assumed that a 32-character key typical for Stud.IP is used. If the key in the database is set to AUTO_INCREMENT, the key automatically assigned by the database is loaded after the store() instead. You can also modify this behavior (see #callbacks)

### Deleting an object

To delete an object, call the delete() method after loading it from the database. delete() returns the number of deleted records. It can also happen that the number is greater than 1 if cascading dependent objects have also been deleted. It can also return false, which means that the deletion was interrupted, which may have happened due to an error or a callback (`before_delete`).

The object itself is not automatically deleted after calling delete(), but it is emptied. You can check whether you have a deleted object in front of you using its isDeleted() method.

```php
$course = Course::find($id); //Load object
$course->delete(); //Delete object
$course->getId(); // returns null
$course->isNew(); // results in false;
$course->isDeleted(); //returns true
```


### Relations

Since version 2.4 of Stud.IP, SimpleORMap can also map relations between the database tables or their contained objects. The basic principle is that an object of a class derived from SimpleORMap has another attribute to access another database table. For example, several user objects are assigned to a course object, which are regarded as participants in the event. There is a relation table `seminar_user` between the tables `seminare` and `auth_user_md5/user_info`. There is therefore an n:m link that has been mapped in the course class in order to obtain the participants in a simple way via an attribute of a course object:

```php
$course = new Course($seminar_id);
$courseMembers = $course->members;
```

**IMPORTANT:** This example shows a pitfall when using relations in SimpleORMap: In the example, $courseMembers does not represent the users, but objects of the SimpleORMap class CourseMember. This means that the variable `$coursemembers` is filled correctly, because it can also be used to easily access the fields of the table `seminar_user`, but most people are not interested in the relation table, but in the linked objects, such as the associated user objects in this case. The obvious way to access these objects would be to go through all objects of `$courseMembers` and then get the associated user objects. Thanks to SimpleORMapCollection, there is a way that requires less code to be written.


For example, to get all User objects of all lecturers of an event, those who have the status lecturer are filtered from all event participants and only their user IDs are saved. The static method findMany() of the User class can then be used to read out all User objects of the lecturers of the selected event.

```php
$dozenten_ids = Course::find($seminar_id)->members->filter(function ($m) {
     return $m['status'] === 'lecturer';
})->pluck('user_id');
$dozenten = User::findMany($dozenten_ids, "ORDER BY last name, first name");
```


The advantage of this procedure is that not as many database queries are made as if all user objects were loaded individually. This is because findMany() only executes a single SQL query, the result set of which then automatically becomes objects of type User.

### Definition of relations


| Variable | Description |
| ---- | ---- |
|assoc_foreign_key |Defines the column of the second table with which the key (default primary key) of the basic object is compared |

#### Example for the definition of relations

It is very easy to map a tree structure with the SimpleORMap, as the following example shows. The example assumes that the sample_table table used has an id column, which represents the primary key. It is also assumed that a parent_id column exists, which references the respective parent element of a table entry.

```php
class SampleSorm extends SimpleORMap
{
    protected static function configure($config = [])
    {
        $config['db_table'] = 'sample_table';

        // Define child link
        $config['has_many']['children'] = [
            'class_name' => SampleSorm::class,
            'assoc_func' => 'findByParent_id'
        ];

        // Define parent link
        $config['belongs_to']['parent'] = [
            'class_name' => SampleSorm::class,
            'foreign_key' => 'parent_id'
        ];
        parent::configure($config);
    }
}
```

For each object of the SampleSorm class, its child elements and its parent element can now be accessed directly as SimpleORMap objects.

### Joins

Joins are also possible with SimpleORMap. These can greatly reduce the amount of writing required if the selection criteria for objects are to be defined using other tables.

To use a join, simply call the static method findBySql() of the SimpleORMap class that maps the desired object type. The result is then available in the desired object type.

In contrast to the calls of findBySql() presented so far, the SQL code must be specified from the JOIN statement onwards when using joins. This means that only the "SELECT FROM tablename" part of the SQL statement is generated by the SimpleORMap class. The rest of the statement must be written manually.

#### Example
In the following example, all event dates of a participant are retrieved:

```php
<?php
$courseDates = CourseDate::findBySQL(
      "LEFT JOIN seminar_user "
    . "ON (seminar_user.Seminar_id = dates.range_id ) "
    . "WHERE (seminar_user.user_id = :user_id )",
    ['user_id' => $user_id]
);
```


### Further documents

* [Presentation slides SORM 1](/pdf/entwicklerworkshop2013-activerecord.pdf)
* [Presentation slides SORM 2](/pdf/entwicklerworkshop2014-attack_of_the_sorm.pdf)
* [Presentation slides SORM 3](/pdf/entwicklerworkshop2015-sorm_sucks.pdf)
