<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

define('INDEX_PATH', str_replace('\\', '/', dirname(__FILE__)));
define('APPLICATION_PATH', str_replace('\\', '/', realpath(INDEX_PATH . '/../application')));
define('LIBRARY_PATH', str_replace('\\', '/', realpath(INDEX_PATH . '/../library')));
set_include_path(
		LIBRARY_PATH
		. PATH_SEPARATOR
		. get_include_path()
	);

/**
 * Salt for general hashing (security)
 */
define('GENERIC_SALT', 'asdDSasd4asdAd1GH4sdWsd1');

// Define application environment
define('APPLICATION_ENV', 'development');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();