---
title: UTF-8
---

Since version 4.0 Stud.IP uses UTF-8 as standard encoding.
For core and plugin developers, there are a few things to consider that change during development. Operators should pay attention to a few things when switching, these are listed at the end.

## Database
The following points should be known and observed:
* The connection from PHP to the database via PDO is now realized with charset=utf-8.
* The database encoding is changed by means of a migration. The encoding is utf8mb4, the collation for text columns is utf8mb4_unicode_ci.
* Index columns with MD5 hashes as keys use latin1_bin as encoding for performance reasons. For your own tables, you must ensure that latin1_bin is also used for MD5 index columns, otherwise JOIN commands will fail due to different collations!
* Columns that were previously *_bin are converted to utf8mb4_bin.
* During the migration, the htmlentities in all text columns are also replaced by their UTF-8 representation.

 **In addition to the previous options, the following must also be set in the InnoDB configuration:**
* `innodb_large_prefix=1` (Up to MariaDB 10.2)
* `innodb_file_format=Barracuda`

These two settings are checked by the migration.

## Code - core and plugins

The following things must be observed during development in the core:
* All PHP and JS files must now be UTF-8 by default.
* The string functions must be used in their mb_* variant
* `studip_utf8(en|de)decode` no longer exists and is no longer required

### Plugins

To prepare plugins for Stud.IP 4.0, the same rules as for the development in the core must first be observed.

The procedure for converting a plugin is as follows:

#### Convert all source files to UTF-8
The easiest way to do this is to create a conversion script with the following command:

```shell
find . -name '*.php' -exec printf "iconv -f windows-1252 -t utf-8 {} > {}.new \n mv {}.new {}\n" >> convert_files.sh \;
```

This converts all PHP files. If necessary, you can repeat this for other file types by adapting this command.

#### Database

Existing database contents are normally converted directly via the migration from the core, i.e. no further migration is necessary. When creating new tables, it should be noted that the correct new encoding (utf8mb4) is then specified or better omitted altogether.

