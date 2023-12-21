---
id: studip-mail
title: Stud.IP-Mail
sidebar_label: Stud.IP-Mail
---

This class [StudipMail](https://gitlab.studip.de/studip/studip/-/blob/main/lib/classes/StudipMail.class.php) offers the possibility to create and send an email. The message is not sent directly by the class, but by an instance of another class for email transport. The classes used for this are located in `vendor/email_message`, here there are various options for the transport, e.g. via the php function Mail or via smtp.


## quick example

```php
$mail = new StudipMail();
$mail->addRecipient('suchi@data-quest.de', 'Stefan Suchi')
     ->addRecipient('elmar.ludwig@uos.de', 'Elmar Ludwig', 'Cc')
     ->setSubject('Test of the mail class')
     ->addStudipAttachment($document_id)
     ->setBodyText("Hello,\nBlablubb")
     ->send();
```

## Mail transport
Which transport is used for the mail can be configured in `config/config_local.inc.php` in the mail settings with $MAIL_TRANSPORT. The following options are available here:

| type | description |
| ---- | ---- |
| `smtp` | direct smtp connection to `$MAIL_HOST_NAME` |
| `php` | php `mail()` function is used |
| `sendmail` | direct call to the local sendmail script |
| `qmail` | direct call of the local qmail script |
| `debug` | mails are not sent but written to a file in `$TMP_PATH` |
| `blackhole` | mails are not sent, but simply discarded |

Based on this setting, an instance of the corresponding transporter class is automatically created in `lib/phplib_local.inc.php` and stored with `StudipMail::setDefaultTransporter($mail_transporter)` as the default way to send.

However, it is possible to exchange this object, either via the above call to `StudipMail::setDefaultTransporter()` or when calling StudipMail::send(), as the `send()` method accepts an object derived from `email_message_class` as an optional parameter.

This also makes it possible to temporarily prevent the automatic sending of mails, e.g. as the class `UserManagement` does when a user changes:

```php
require_once 'vendor/email_message/blackhole_message.php';
$umanager = new UserManagement();
$suck_it_down = new blackhole_message_class();
$default_mailer = StudipMail::getDefaultTransporter();
StudipMail::setDefaultTransporter($suck_it_down);
$umanager->createNewUser($data));
StudipMail::setDefaultTransporter($default_mailer);
```

## Create mails
To create a mail, you create a new `StudipMail` object and fill it with content using the various `add` and `set` methods. All `add` and `set` methods always return the current object so that several calls can be placed one after the other ("fluent interface"). The main methods are

| function | description |
| ------ | ------ |
| `setSenderEmail($mail)` | sets the sender's mail address. The sender address is preset to `$MAIL_ENV_FROM` in the constructor. |
| `setSenderName($name)` | sets the name of the sender, defaults to `$MAIL_FROM` |
| `setReplyToEmail($mail)` | sets the mail address for the reply-to, defaults to `$MAIL_ABUSE`. |
| `setSubject($subject)` | sets the title of the mail |
| `addRecipient($mail, $name = *, $type = 'To')` | adds a recipient. The first parameter must contain the mail address, the next parameter optionally contains the name. The third parameter can be used to specify whether the recipient is a standard recipient ('To'), a copy recipient ('Cc') or a blind copy recipient ('Bcc'). |
| `addDataAttachment($data, $name, $type = 'automatic/name', $disposition = 'attachment')` | adds a data attachment to the mail. The $type parameter should be used to specify a correct mime-type |
| `addFileAttachment($file_name, $name = *, $type = 'automatic/name', $disposition = 'attachment')`| adds a file attachment to the mail. The `$type` parameter can be used to specify a correct mime-type, otherwise an attempt is made to decide on the basis of the file name. $file_name must contain the complete path to the file. |
|`addStudipAttachment($document_id)` | adds a file attachment to the mail, only the Stud.IP document ID has to be passed, the other parameters are then filled directly from the database.  |
| `setBodyText($body)` | sets the text content of the mail |
| `setBodyHtml($body)` | sets the HTML content of the mail. A multipart mail is then always generated, if the text content is missing, a help text is inserted.
| `send(email_message_class $transporter = null)` | sends the mail, with the optional parameter the transport object can be specified. The method returns true if the mail could be sent. |
