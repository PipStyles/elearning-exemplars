<?php

/**
 * Application bootstrap
 * 
 * @uses    Zend_Application_Bootstrap_Bootstrap
 * @package QuickStart
 */
 

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Bootstrap autoloader for application resources
     * 
     * @return Zend_Application_Module_Autoloader
     */
		 
	public function run()
  {
	  
		
		
		
		Zend_Controller_Action_HelperBroker::addPath('application/controllers/helpers', 'Helper');
		$this->bootstrap('db');
		$dbs = $this->getPluginResource('db');
		Zend_Registry::set('db', $dbs->getDbAdapter('db1'));
    parent::run();
  } 
	
	
	protected function _initAutoload()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
				'namespace' => '',
				'basePath'  => dirname(__FILE__)
		));
	
		return $autoloader;
	}

		
	/*
	protected function _initRequest(array $options = array())
  {
		// Ensure front controller instance is present, and fetch it
		$this->bootstrap('FrontController');
		$front = $this->getResource('FrontController');
		
		// Initialize the request object
		$request = new Zend_Controller_Request_Http();
		$request->setBaseUrl('/tandl/elearning/exemplars/admin/');

		// Add it to the front controller
		$front->setRequest($request);

		// Bootstrap will store this value in the 'request' key of its container
		return $request;
  }
  */
	
	
	protected function _initView()
    {
      //Initialize view
      $view = new Zend_View($this->getOptions());
      $view->doctype('XHTML1_TRANSITIONAL');
        
			$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
			$view->headTitle('eLearning exemplar database - admin');
		
		  $view->headScript()->appendFile(BASE.'/js/launchers.js');
		  $view->headLink()->appendStylesheet(BASE.'/css/import.css');
		
		  //Add it to the ViewRenderer
      /*$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
				
      $viewRenderer->setView($view);*/
		  // Return it, so that it can be stored by the bootstrap
	    return $view;
    }
		

    
} 



?>