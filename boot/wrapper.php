<?php
//display all errors
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 'on');

//DATE
date_default_timezone_set('Europe/London');

//This constant is set for the headers
define('CONTENT_LAST_MODIFICATION_DATE', '16 Aug 2009 21:50:17 GMT');
/*
 * List of libraries being used and their version
 */
$libraries = array(
	'Zend'	 =>'1.7.8',
	'Guim'	 =>'1.0.1',
	'Miner' =>'0.5.5'
);

// public/index.php
//
// Step 1: APPLICATION_PATH is a constant pointing to our
// application/subdirectory. We use this to add our "library" directory
// to the include_path, so that PHP can find our Zend Framework classes.
define('SERVER_PATH', '/var/www');
define('SHARED_LIB_PATH', '/var' . DIRECTORY_SEPARATOR . 'share' . DIRECTORY_SEPARATOR . 'php');
define('PROJECT_PATH', realpath(dirname(__FILE__) . '/../')); //project root directory contains both /document_root and /application
define('DOCUMENT_ROOT_PATH', PROJECT_PATH . '/document_root'); //document root directry, where all http requests lend
define('APP_PATH', realpath(dirname(__FILE__) . '/../application'));
define('DOMAIN_NAME', 'miner');

/*
 * Dynamically define the libraries paths from $libraries array
 * and store them in the paths arrays
 * to retrieve the path later on use NAME_LIB_PATH
 */
$paths = array(
	get_include_path(), //append the rest to the actual include path
	PROJECT_PATH //application root directory
	);
foreach ($libraries as $name => $version) {
	$fw = $name . 'Framework';
	$path = SHARED_LIB_PATH . DIRECTORY_SEPARATOR . $fw . DIRECTORY_SEPARATOR . $fw . '-' . $version . DIRECTORY_SEPARATOR . 'library';
	// ex : ZEND_LIB_PATH
	define( mb_strtoupper($name) . '_LIB_PATH', $path);
	$paths[] = $path;
}
set_include_path(implode(PATH_SEPARATOR, $paths));

// Step 2: AUTOLOADER - Set up autoloading.
// This is a nifty trick that allows ZF to load classes automatically so
// that you don't have to litter your code with 'include' or 'require'
// statements.
require_once "Zend/Loader.php";
Zend_Loader::registerAutoload();

Zend_Controller_Front::getInstance()->throwExceptions(true);




// Step 3: REQUIRE APPLICATION BOOTSTRAP: Perform application-specific setup
// This allows you to setup the MVC environment to utilize. Later you 
// can re-use this file for testing your applications.
// The try-catch block below demonstrates how to handle bootstrap 
// exceptions. In this application, if defined a different 
// APPLICATION_ENVIRONMENT other than 'production', we will output the 
// exception and stack trace to the screen to aid in fixing the issue
try {
    require 'bootstrap.php';
}catch (Exception $exception){

        G_Echo::l1('<br /><br />' . $exception->getMessage() . '<br />'
           . '<div align="left">Stack Trace:' 
           . '<pre>' . $exception->getTraceAsString() . '</pre></div>');

    exit(1);
}

// Step 4: DISPATCH:  Dispatch the request using the front controller.
// The front controller is a singleton, and should be setup by now. We 
// will grab an instance and call dispatch() on it, which dispatches the
// current request.
//Zend_Controller_Front::getInstance()->dispatch();