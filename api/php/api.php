<?php

/*
Provides the global object Api_Exemplar() from which rowsets of various Exemplars can be returned.
*/

// Define path to application directory
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '../v1/app'));
// Define application environment
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('ADMIN_APP', $_SERVER['DOCUMENT_ROOT'].'/tandl/elearning/exemplars/admin/application/');

set_include_path(implode(PATH_SEPARATOR, array(
    $_SERVER['DOCUMENT_ROOT'].'/tandl/_appLibrary/ZendFramework_1.11.0',
	ADMIN_APP.'models')));

require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$resLoader = new Zend_Loader_Autoloader_Resource(array(
    'basePath'  => $_SERVER['DOCUMENT_ROOT'].'/tandl/elearning/exemplars',
    'namespace' => '',
	'resourceTypes' => array(
        'api' => array(
          'path'      => 'api/v1/app/models',
          'namespace' => 'Api'
        ),
		'model' => array(
		  'path'      => 'admin/application/models',
          'namespace' => ''
		)
  )
));

$resLoader->addResourceType('dbtable', 'admin/application/models/DbTable', 'Model_DbTable');

//get config from admin app for db config
$admin_config = new Zend_Config_Ini(ADMIN_APP.'configs/application.ini',APPLICATION_ENV);
$admin_config->resources->db->params;

//set default db adapter for db models
$dbAdapter = Zend_Db::factory('Pdo_Mysql', $admin_config->resources->db->params);
Zend_Db_Table::setDefaultAdapter($dbAdapter);

$api_exemplar = new Api_Exemplar();


?>