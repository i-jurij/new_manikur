<?php

// DB Params
define('DBINITNAME', 'Db_init_sqlite');
// define('DBINITNAME', 'Db_init_mysql'); //mysql, mariadb
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'new_manikur');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(dirname(__FILE__))));
define('APPROOT', dirname(dirname(__FILE__)));
define('PUBLICROOT', dirname(dirname(dirname(__FILE__))).DS.'public');
define('TEMPLATEROOT', PUBLICROOT.DS.'templates');
define('IMGDIR', PUBLICROOT.DS.'imgs');
// site name
define('SITENAME', 'localhost/new_manikur');
// define('SITENAME', 'new_welder');
// define('URLROOT', 'http'.((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']=='on') ? 's': '').'://'.SITENAME.'.net');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
define('URLROOT', $protocol.SITENAME);
define('CURRENT_PAGE_LOCATION', $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
// data for date time for appointment end work shedule (grafiki_form_for_include.php and appoint_appointment.php)
define('PERIOD', 60);
define('WORKTIME', ['09:00', '19:00']);
define('LUNCH', ['12:00', 40]);
$wt0 = (int) mb_substr(WORKTIME[0], 0, 2) * 60;
$wt1 = (int) mb_substr(WORKTIME[1], 0, 2) * 60;
for ($i = $wt0; $i < $wt1; $i = $i + PERIOD) {
    if ($i != (int) mb_substr(LUNCH[0], 0, 2) * 60) {
        $time[] = $i / 60 .':00';
    }
}
define('TIME', $time);
