---
id: log
title: Log
sidebar_label: Log
---

It is often necessary to perform debugging on the production/staging system during programming, but possibly also afterwards. Output is then usually generated for this purpose. This is confusing and visible to everyone on the production system if you are not careful. There are also areas where it is very difficult to work with debugging outputs, e.g. login process, SSO variants, web services etc. For such cases, a log class is available with which you can flexibly create one or more logs and control the output of the log as desired.

The [Log](https://gitlab.studip.de/studip/studip/-/blob/main/lib/classes/Log.php) class is available from version 2.4. A standard log is automatically initialized, which is written to the studip.log file in the temporary Stud.IP directory. In `development` mode, the log level is set to DEBUG, otherwise to ERROR.

# General

Various named loggers can be controlled via the class `Log`. The standard logger has no special name. In the simplest case, the logger can simply be the name of a file in which the messages are written. Alternatively, a callback can be passed which receives the messages. The class defines the following logging levels:

* `FATAL`
* `ALERT`
* `CRITICAL`
* `ERROR`
* `WARNING`
* `NOTICE`
* `INFO`
* `DEBUG`

Methods with the same name are available to send a message at the corresponding level.

Static call:

```php
Log::warning('This could be problematic');
```

Call via instance method:

```php
Log::get()->warning('This could be problematic');
```

Upper and lower case are not important, a unique abbreviation is also sufficient, e.g. warn() instead of warning().

## Create other loggers

### File-based

```php
//create a new named logger
Log::set('mylogger', '/tmp/mylog.log');

//Call
Log::info_meinlogger('This is for my information only');
or
Log::get('mylogger')->info('This is for my information only');
```

### With callback

```php
//logging by mail
$logtomail = function($l)
{
    return mail('noack@data-quest.de', 'Log ' . ' ['.$l['level_name'].'] ', $l['formatted']);
};
//change named logger
Log::get('mylogger')->setHandler($logtomail);
```

### combined logger with different levels

```php
$defaultlog = Log::get();
$defaultlog->setLogLevel(Log::DEBUG);
$maillog = Log::get('mylogger');
$maillog->setLogLevel(Log::ALERT);
$combi_handler = function($m) use ($defaultlog, $maillog)
{
    $maillog->log($m['message'], $m['level']);
    $defaultlog->log($m['message'], $m['level']);
};
//set as new default logger
Log::set(*, $combi_handler );

//this only goes into the file
Log::debug('doesn't really matter what happens here');
//in file and by mail
Log::fatal('now it's really cracking');
```
