---
id: format
title: Formatting engine for Stud.IP
sidebar_label: Formatting engine
---

Existing functions:

| function | file | description |
| ---- | ---- | ---- |
|htmlReady|visual.inc.php|No formatting, just simple HTML replacement|
|quotes_decode|visual.inc.php|Extracts quotes from text|
|quotes_encode|visual.inc.php|Delivers quote code for passed text|
|format_help|visual.inc.php|Helper for formatReady, code handling|
|formatReady|visual.inc.php|Formats string with all formatting options. Special-modes: trim=yes/no, extern=yes/no, wiki=yes/no, comments=icon/none/full|
|wikiReady|visual.inc.php|abbreviation for formatReady, sets wiki=TRUE|
|wiki_format|visual.inc.php|Replaces comment formatting|
|format_wiki_comment|visual.inc.php|formats a single comment according to parameters|
|latex |visual.inc.php|Render LaTeX formula and embed image|
|decodeHTML|visual.inc.php|Replaces HTML entities with corresponding characters (iso latin-1)|
|format|visual.inc.php|Applies regular expressions for formatting|
|preg_call_format_text|visual.inc.php|Helper for big/small formatting|
|preg_call_format_list|visual.inc.php|Helper for list formatting|
|preg_call_format_table|visual.inc.php|Helper for table formatting|
|preg_call_rss_include|visual.inc.php|include RSS feed|
|xss_remove|visual.inc.php|Remove dangerous HTML tags|
|kill_format|visual.inc.php|Remove formatting for ASCII|
|FixLinks|visual.inc.php|Replace links with HTML links|
|preg_call_link|visual.inc.php|Helper for link formatting|
|idna_link|visual.inc.php|Generates punycode for umlaut URLS|
|smile|visual.inc.php|Replaces smiley expressions|
|symbol|visual.inc.php|Replaces smiley short forms|


Processing order:

* Normal: latex -> format -> FixLinks -> rss_include -> smile -> symbol
* Wiki: latex -> format -> FixLinks -> rss_include -> smile -> symbol -> wiki_format
