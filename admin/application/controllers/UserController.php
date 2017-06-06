<?php

class UserController extends CommonController
{
	
	public function init()
	{
		parent::init();
		
		if(!isset($this->getRequest()->id))
		{
			$this->_redirect('/index/index');
		}
		
		$this->id = $this->getRequest()->id;
		
		
		if($this->id != $this->user->user_id)
		{
		$this->_redirect('/index/index');
		}
		
		
		
		$this->form = new Form_Login();
		$this->form->removeElement('username');
		$this->form->getElement('login')->setLabel('change password');
		$this->form->setAction(BASE.'/user/index/id/'.$this->id);
		
		$this->userModel = new User();
		
		$this->view->userRow = $this->userRow = $this->userModel->fetchRow($this->userModel->select()->where($this->userModel->getPrimary().'='.$this->id));
		
		//print_r($this->id);
		
	}
	
	
	public function indexAction()
	{
		if($this->getRequest()->isPost())
		{
			//is attempt to change password
			$this->_forward('changepassword');
		}
		
		$this->view->form = $this->form;
	}
	
	
	public function changepasswordAction()
	{
		
		if(!$this->form->isValid($_POST))
		{
			$this->view->messages = $this->form->getMessages();
		}
		else
		{
			$this->userRow->password = md5($this->getRequest()->password);
			$this->userRow->save();
			$this->view->success = 'Password changed';
		}
		
		$this->render('index');
	
	}
	
	
	
	
}
?>