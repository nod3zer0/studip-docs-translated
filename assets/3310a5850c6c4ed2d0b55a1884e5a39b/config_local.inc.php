<?php
/*basic settings for Stud.IP
----------------------------------------------------------------
you find here the basic system settings. You shouldn't have to touch much of them...
please note the CONFIG.INC.PHP for the indivual settings of your installation!*/

namespace Studip {
    const ENV = 'development';
}

namespace {
    /*settings for database access
    ----------------------------------------------------------------
    please fill in your database connection settings.
    */

    // default Stud.IP database (DB_Seminar)
    $DB_STUDIP_HOST = 'localhost';
    $DB_STUDIP_USER = 'root';
    $DB_STUDIP_PASSWORD = '';
    $DB_STUDIP_DATABASE = 'studip';
	
	$TMP_PATH = $STUDIP_BASE_PATH . "/tmp";
	$CACHING_ENABLE = false;
	$MAIL_TRANSPORT = 'debug';
	define("LC_MESSAGES", 5);
	$CONTENT_LANGUAGES['en_GB'] = array('picture' => 'lang_en.gif', 'name' => 'English');

    /*URL
    ----------------------------------------------------------------
    customize if automatic detection fails, e.g. when installation is hidden
    behind a proxy
    */
    //$CANONICAL_RELATIVE_PATH_STUDIP = '/';
    //$ABSOLUTE_URI_STUDIP = 'http://localhost/test/public/';
    //$ASSETS_URL = 'https://www.studip.de/assets/';

}
