<?php
// Define path to application directory

/*
echo "<h2>Testing: ignore</h2>";
echo "<h3>\$_SERVER array:</h3>";
print_r($_SERVER);

echo"<h3>\$_POST array</h3>";
print_r($_POST);
echo"<h3>\$_GET array</h3>";
print_r($_GET);

echo"<br />";
print_r(file_get_contents('php://input'));
//*/

ini_set('register_globals', 'off');
ini_set('magic_quotes_gpc', 'off');
ini_set('magic_quotes_runtime', 'off');

define('BASE', '/tandl/elearning/exemplars/admin');
define('ICON_PATH', '/tandl/elearning/images/app_icons');
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath($_SERVER['DOCUMENT_ROOT'].'/tandl/elearning/exemplars/admin/application'));

$appEnv = 'production';
//$appEnv = 'development';

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $appEnv));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath($_SERVER['DOCUMENT_ROOT'].'/tandl/_appLibrary/ZendFramework_1.11.0'), 
    get_include_path(),
)));

require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

/* Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);

/*
echo "<h2>Testing: ignore</h2>";
echo "<h3>\$_SERVER array:</h3>";
print_r($_SERVER);

echo"<h3>\$_POST array</h3>";
print_r($_POST);
echo"<h3>\$_GET array</h3>";
print_r($_GET);

echo"<br />";
print_r(file_get_contents('php://input'));
//*/


$application->bootstrap();
$application->run();


?>