<?php

class LoginController extends Zend_Controller_Action
{
	
	
	public function init()
	{
		
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/');
		}
		
		//$this->_helper->layout->setLayout('login');
		
		//$this->loginForm = new Form_Login();
		//$this->loginForm->setAction(BASE.'/login/login');
		
	}
	
	
	public function indexAction()
	{
		$this->_forward('login');
		//$this->view->loginForm = $this->loginForm;
	}
	
	
	
	
	public function loginAction()
	{
		//validate form, check credentials, create identity, redirect if success
		$this->_helper->viewRenderer->setNoRender();
		
		$request = $this->getRequest();
		
		$this->db = Zend_Registry::get('db');
		
		$bootOut = "location:http://www.humanities.manchester.ac.uk/tandl/elearning/exemplars/";
		
		//is $_SERVER['HTTP_CAS_USER'] set? No? Error page!
		if(!isset($_SERVER['REMOTE_USER']))
		{
		  header($bootOut);
		  exit;
		}
		
		//check CAS id against local users table
		$userOb = new User();
		$userRow = $userOb->fetchRow($userOb->select()->where('username = ?', $_SERVER['REMOTE_USER']));
		
		$auth = Zend_Auth::getInstance();
		
		if(!$userRow)
		{
			//invalid user
			$auth->clearIdentity();
			header($bootOut);
			exit;
		}
		else
		{
			//valid user
			$auth->getStorage()->write($userRow);
			$this->user = $auth->getStorage()->read();
			
			//$authSession = new Zend_Session_Namespace('Zend_Auth');
		}
		
		$this->_redirect('/');
		
	}
	
	
	/*
	public function getAuthAdapter($username)
	{
		$auth = new AuthAdapter(Zend_Registry::get('db'));
		$auth->setIdentity($username, null);
		return $auth;
	}	
	*/
	
	
}
	
		
?>