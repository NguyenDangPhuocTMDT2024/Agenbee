<?php
define('servername', 'localhost');
define('username', 'root');
define('password', '');
define('database', 'AGENBEE');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
define('_HOST_URL', $protocol . $_SERVER['HTTP_HOST'] . '/agenbee');
define('_HOST_URL_LAYOUT', _HOST_URL . '/app/views/layouts');
define('_HOST_URL_PUBLIC', _HOST_URL . '/public');

define('_ROOT_PATH',dirname(__DIR__));
define('_PUBLIC_PATH', _ROOT_PATH.'/public');

define('_HOST_MAIL', 'agenbee0502@gmail.com');
define('_APP_PASS','usby gflt wnmn hkph');
define('_PHONE','0765058016');

// Login TTL (seconds)
define('_LOGIN_SESSION_LIFETIME', 60 * 60 * 24);      // 24 hours
define('_LOGIN_TOKEN_LIFETIME', 60 * 60 * 24 * 3);   // 3 days

define('_AGENBEE_BRIEF','https://cv6hqcr4yk.zite.so');
?>