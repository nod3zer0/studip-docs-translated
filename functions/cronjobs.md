---
id: cronjobs
title: Cronjobs
sidebar_label: Cronjobs
---

To be able to use cronjobs, they must be installed accordingly. The installation instructions provide [detailed information](http://docs.studip.de/admin/Admins/Installationsanleitung#toc23).

There are at least two problems to consider when creating cronjobs:

* The cronjob script at CLI level may be executed under a different user ID than the scripts via the web interface. This can lead to side effects for certain operations such as files.
* Not every cache module is also available at CLI level. This means that classes can behave differently when they are executed via the web interface or via a cronjob. If content does not appear to change, check the status in the database and if there are differences to the web interface, a cache is probably involved. In this context, also check the setting `CACHING_ENABLE` in the file `cli/studip_cli_env.inc.php`. It may be possible to activate the cache at CLI level, but this depends on the tool used.

Since Stud.IP version 3.3, the behavior mentioned in the last point has improved, as caching via a special cache proxy works more consistently in CLI mode and in web mode. Values can be deleted from CLI mode. However, reading from the cache is still not necessarily guaranteed.

### Anatomy

#### Complete example

```php
<?php
class ExampleCronjob extends CronJob
{
    public static function getName()
    {
        return _('Example');
    }

    public static function getDescription()
    {
        return _('Example cronjob that does nothing');
    }

    public static function getParameters()
    {
        return [
            'verbose' => [
                'type' => 'boolean',
                'default' => false,
                'status' => 'optional',
                'description' => _('Should outputs be generated'),
            ],
        ];
    }

    public function setUp()
    {
    }

    public function execute($last_result, $parameters = [])
    {
        do_something();
    }

    public function tearDown()
    {
    }
}
```


#### Basics

#### `setup() / teardown()`


#### `execute()`


#### Parameters

Valid parameter types are the following:

| Parameter | Description |
| ---- | ---- |
| `boolean` | |
| `string` | (1-line text) |
| `text` | (multiline text) |
| `integer` | `select` (the valid values are defined in the separate `values` field|


The `default` field can be used to specify a default value, while the `status` field is used to specify whether the parameter is mandatory (`mandatory`) or optional (`optional`). The `description` field can be used to enter a description for the parameter that is displayed when the cronjob is configured.

### Registering/installing a cronjob

```php
<?php
require_once '../classes/example_cronjob.php'; // Contains ExampleCronjob

class CronjobMigration extends Migration
{
    public function up()
    {
        ExampleCronjob::register()->schedulePeriodic(59, 23)->activate();
    }

    public function down()
    {
        ExampleCronjob::unregister();
    }
}
```
