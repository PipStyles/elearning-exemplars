<?php

class LogoutController extends Zend_Controller_Action
{
	
	
	public function init()
	{
		$this->_helper->viewRenderer->setNoRender();
	}
	
	
	public function indexAction()
	{
		$this->auth = Zend_Auth::getInstance();
		$this->auth->clearIdentity();		
		
		$this->_redirect('/login/');
	}
	
	
}
	
		
?>