<?php

class CommonController extends Zend_Controller_Action
{
	
	
	public function init()
	{
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			$this->auth = Zend_Auth::getInstance();
			$this->view->user = $this->user = $this->auth->getStorage()->read();
		}
		else
		{
		  
			$this->user = null;
			$this->_redirect('login/login');
		}
		
		$this->view->addHelperPath('application/views/helpers', 'View_Helper');
		
		//$this->_redirector = $this->_helper->getHelper('Redirector');
		//$this->_redirector->setPrependBase(Zend_Controller_Front::getInstance()->getBaseUrl());
		
	}
	
}
	
		
?>