<?php
define('BASE', '/tandl/elearning');

define('ICON_PATH', '/tandl/elearning/images/app_icons/');

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath($_SERVER['DOCUMENT_ROOT'].BASE.'/exemplars/admin/application'));

defined('APPLICATION_ENV')
 || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath($_SERVER['DOCUMENT_ROOT'].'/tandl/_appLibrary'),
		realpath(APPLICATION_PATH.'/models'),
    get_include_path(),
)));

require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$config = new Zend_Config_Ini($_SERVER['DOCUMENT_ROOT'].BASE.'/exemplars/admin/application/configs/application.ini', 'development');

$db = new Zend_Db_Adapter_Pdo_Mysql(array(
    'host'     => $config->resources->db->params->host,
    'username' => $config->resources->db->params->username,
    'password' => $config->resources->db->params->password,
    'dbname'   => $config->resources->db->params->dbname
));

//auto discover the live db if on the production server.
$usedb = strstr($_SERVER['HTTP_HOST'], 'www.humanities.') ? $livedb : $db;

Zend_Registry::set('db', $usedb);
Zend_Db_Table::setDefaultAdapter(Zend_Registry::get('db'));

$modules = new Zend_Application_Module_Autoloader(array(
					'namespace' => '',
					'basePath'  => APPLICATION_PATH,
));

//needed because of the Service class! Duh!
$modules->removeResourceType('service');

$view = new Zend_View();
$view->addHelperPath(realpath(APPLICATION_PATH.'/views/helpers'), 'View_Helper_');

?>