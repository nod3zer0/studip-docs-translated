---
title: Codeception (PHP)
---

# Which tool do we use?

To provide Stud.IP with tests, we have been using the test suite Codeception since version 3.2, see http://codeception.com/.

Codeception includes PHPUnit for unit and API tests and also enables acceptance tests to be written in PHP. The acceptance tests can then be executed with an internal PHP browser or by simply switching in the configuration with a Selenium server (http://www.seleniumhq.org/).

To install Codeception, we use Composer. This is a dependency manager that helps you to install things like Codeception and keep it up to date, see https://getcomposer.org/.

PHPUnit is a test framework for PHP, it offers extensive possibilities for unit testing, the way it works can be found in the official documentation at https://phpunit.de/documentation.html.

## How to install composer

The composer page explains how to install it: https://getcomposer.org/doc/00-intro.md.

If you are under Linux, the easiest way is to use the following command:

```shell
curl -sS https://getcomposer.org/installer | php
```

A composer.phar is placed in the current directory, which can then be used like a command line tool. This is exactly what we will do in the next step.

## How to install codeception

After you have installed composer, you can easily install codeception with the following command:
```shell
php composer.phar install
```

or, if you have composer in your search path (e.g. by installing it via a package manager):
```shell
composer install
```

A directory called "composer" is then created in the Stud.IP main directory in which all the necessary files are stored.

# How do I execute the tests?

By default, only the unit tests are executed, as you still have to configure your test environment a little for acceptance and API tests before they can be carried out properly.

To execute the unit tests (see below), the following command is sufficient
```shell
make test
```

The Makefile defines a fallback to PHPUnit, which is used if no Codeception installation is found.

As a result, all unit tests are carried out with PHPUnit. The output then looks something like this (if the tests run successfully):

![image](../assets/1afde9c989ac66fc23192c4e7ca6aea8/image.png)

# Working with Codeception

Codeception has a command line tool with which you can perform the necessary operations. The exact documentation of how it works can be found on the Codeception website at http://codeception.com/. The following typical use cases are only intended as an introduction.

## Directory structure

Codeception works in the *tests* directory. The individual test suites are located there in the corresponding subdirectories, i.e. *acceptance*, *unit* etc. There is a separate configuration file for each test suite which ends in *.suite.yml*. The settings for the corresponding test suite are stored there.

## Executing tests, the educated way

As described above, `make test` is normally sufficient to execute the unit tests. What actually happens is the call of the command
```shell
./composer/bin/codecept run unit
```

Only a specific test suite is executed (see second parameter, "unit"). If you want to run all test suites, use the following command:
```shell
./composer/bin/codecept run
```

However, this will fail if the other test suites have not yet been configured. How to do this is described in the following sections.

It is also possible to include only a specific test case. For example, if you only want to run the StudipFileloaderTest, call codeception as follows:

```shell
./composer/bin/codecept run unit lib/classes/StudipFileloaderTest.php
```

This helps when writing new tests - you don't have to run all the currently irrelevant unit tests to see if the newly built test works.

## Acceptance tests

Acceptance tests are tests that work at the top level and simulate a Stud.IP user. They are used to ensure functionality from the user's point of view and range from simple, general tests such as "Can a user log in" to very specific requirements such as "Can a user with the authorization level write a forum entry within an area in an open course".

The official documentation for acceptance tests can be found here: http://codeception.com/docs/03-AcceptanceTests

Codeception offers PHP functions to write acceptance tests. Depending on the settings, these are then executed with an internal PHP browser or translated into Selenium tests and forwarded to a Selenium server.

### Testing with PHPBrowser

In order to be able to execute the existing tests, you need an executable version of Stud.IP, i.e. it must be accessible and usable via a web browser. Once this is done, enter the URL to this system in the configuration of the acceptance tests. This can be found in the following file:
`tests/acceptance.suite.yml`

Enter the path to the public directory of the Stud.IP test installation under "url":

```json
class_name: WebGuy
modules:
    enabled:
        - PhpBrowser
        - WebHelper
    config:
        PhpBrowser:
            url: 'http://localhost/pfad/zu/studip/public/'
```

**Important**: After each change to the configuration you should
you should execute the command `./composer/bin/codecept build` once, e.g. to have newly configured modules available.

Now you can use
```shell
./composer/bin/codecept run acceptance
```
to run the tests. Normally, the database should be updated to a defined status before each test run.
How this works is described in the section "Testing with database"


### Testing with Selenium

To run the tests with Selenium, you first need a Selenium server. This is a Java program which can be downloaded from http://www.seleniumhq.org/download/ in the section "Selenium Server" and then started with
```shell
java -jar selenium-server-standalone-VERSION..jar
```
can be started.

Once this is done, tell Codeception to use the Selenium server for the acceptance tests. To do this, again edit
```shell
tests/acceptance.suite.yml
```
and add another entry and change the activated modules so that the configuration now looks something like this:

```json
class_name: WebGuy
modules:
    enabled:
        - WebDriver
        - WebHelper
    config:
        PhpBrowser:
            url: 'http://localhost/pfad/zu/studip/public/'
        WebDriver:
            url: 'http://localhost/pfad/zu/studip/public/'
            browser: 'firefox'
```

The browser used can be defined in "browser". It is also possible to run the same tests in different browsers, this is explained in the "Environment" section at http://codeception.com/docs/07-AdvancedUsage#Environments.

**Important**: After each change to the configuration, the command `./composer/bin/codecept build` should be executed once, e.g. to have newly configured modules available.

## REST API tests

If you want to write tests for web services, you can find good documentation for this at
http://codeception.com/docs/10-WebServices

Roughly summarized, you have to generate a test suite, e.g. with the name api, where you then place your tests. You then have the option of sending HTTP requests (GET, POST, PUT, DELETE, etc.) and checking the result. In addition, it makes sense to always set the database to a defined status beforehand (see the section "Testing with database")

# Testing with database

For acceptance tests in particular, it makes sense to set the database to a defined state before testing. This is very easy thanks to Codeception.
Simply create a database dump in the *tests/_data* directory and configure the desired test suite.
If you want to set the database to a defined state for the acceptance test, edit the file
`tests/acceptance.suite.yml`
and add the configuration for the database.

It will look something like this:
```json
class_name: WebGuy
modules:
    enabled:
        - WebDriver
        - WebHelper
        - Db
    config:
        PhpBrowser:
            url: 'http://localhost/pfad/zu/studip/public/'
        WebDriver:
            url: 'http://localhost/pfad/zu/studip/public/'
            browser: 'firefox'
        Db:
            dsn: 'mysql:host=localhost;dbname=studip'
            user: 'root'
            password: 'password'
            dump: tests/_data/studip_acceptance_test.sql
```

It is now also possible to check the database contents directly in the tests. Detailed documentation can also be found here at Codeception, see http://codeception.com/docs/modules/Db.


# Testing SORM classes

It is not necessary to set up an external data source to test SORM classes. SORM objects can be created directly in the Codeception test class and used for tests. To do this, a few lines of code must be inserted into the _before() and _after() methods of the Codeception test class.

Code is inserted into the _before() method of the Codeception test class which performs the following actions:

* Creating the Stud.IP database connection
* Start a database transaction
* Set the database connection in the DBManager
* If necessary, creation of SORM objects which are required for the tests



The code within the `_before()` method of the test class can look like this, for example:

```php
//Create the Stud.IP database connection:
$this->db_handle = new \StudipPDO(
    'mysql:host='
        . $GLOBALS['DB_STUDIP_HOST']
        . ';dbname='
        . $GLOBALS['DB_STUDIP_DATABASE'] ,
    $GLOBALS['DB_STUDIP_USER'],
    $GLOBALS['DB_STUDIP_PASSWORD']
);

//Start a database transaction:
$this->db_handle->beginTransaction();

//Setting the database connection in DBManager:
\DBManager::getInstance()->setConnection('studip', $this->db_handle);

//If necessary: Create SORM objects:
$u = new User();
$u->username = 'sorm_test_user';
[...]
$u->store();
```


In the _after() method of the Codeception test class, only the database transaction is reset (a "rollback" is performed) in order to undo the changes to the database that were made within the tests. The _after() method therefore contains the following line of code:

```php
$this->db_handle->rollBack();
```
