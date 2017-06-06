<?php

class EditController extends CommonController
{

  public function init()
  {
		parent::init();
	  
		if(!isset($this->getRequest()->cat))
		{
			$this->_redirect('index/index');
		}
		
		$this->view->isEditing = false;
		
		$this->cat = $cat = $this->getRequest()->cat;
		$this->editOb = new $cat();
		
		if(isset($this->getRequest()->id) && intval($this->getRequest()->id) > 0)
		{
			$this->id = $this->getRequest()->id;
			
			//print_r($this->editOb->select());
			
			$this->select = $this->editOb->select()->title()->byPrimary($this->id);
			$this->zSelect =  $this->editOb->select()->byPrimary($this->id);
			
			$this->row = $this->editOb->fetchRow($this->select);
			$this->zRow = $this->editOb->fetchRow($this->zSelect);
			
			$this->view->isEditing = true;
		}
		else
		{
			$this->zRow = $this->row = $this->editOb->createRow();
		}
		
		$this->view->row = $this->row;
		
		$this->stdReturn = '/edit/show/cat/'.$cat.'/id/'. $this->zRow->getID();
		
		//make a form!
		$this->formBuilder = new $this->_helper->EditFormBuilder();
		$this->view->form = $this->form = $this->formBuilder->build($this->zRow);
		$this->form->setMethod('POST');
		
		//$this->form->setAction("http://www.humanities.manchester.ac.uk/tandl/elearning/exemplars/_postTest.php");
		$this->form->setAction(BASE.'/edit/save/cat/'.$this->cat.'/id/'.$this->id);
		
		//$this->view->form->setAttrib("action", BASE.'/edit/save/cat/'.$this->cat.'/id/'.$this->id);
		
		//got a descendent? make it too
		
		if($this->row->getDescendent() instanceof Model_DbTable_Row)
		{
			$this->view->descRow = $this->descRow = $this->row->getDescendent();
			$this->view->zDescRow = $this->zDescRow = $this->descRow->getWritableRow();
			$this->descForm = $this->formBuilder->build($this->descRow);
		}
			
	}

    
	public function indexAction()
    {
			$this->_forward('show');
    }
		
		
	public function showAction()
	{
		//don't show the descRow independently - redirect to parent where it will be shown in context
		if($this->editOb->isDescendent())
		{
			$ascClassName = $this->editOb->getAscendentClassName();
			$this->_redirect('/edit/show/cat/'.$ascClassName.'/id/'.$this->zRow->getID());
		}
		
		if($this->getRequest()->saved == 1)
		{
			$this->view->saved = true;
		}
		
	}
	
	
	
	public function saveAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		 
		if(@$this->getRequest()->id)
		{
			//we're saving an existing record
			$row = $this->zRow;
			//$row->exemplarLastModifiedUsername = $this->user->username;
			$retID = $this->id;
		}
		else
		{
			//we're making a new record
			$this->zRow = $this->editOb->createRow();
			//$this->zRow->exemplarCreatedByUsername = $this->user->username;
		}
		
		$formBuilder = new $this->_helper->EditFormBuilder();
		$form = $formBuilder->build($this->zRow);
		
		$data = $this->getRequest()->getPost();
		
		//validate against main form
		if($form->isValid($data))
		{
			$values = $form->getValues();
			
			if(get_magic_quotes_gpc())
			{
			  function stripslashes_gpc(&$value)
				{
				  $value = stripslashes($value);
				}
				array_walk_recursive($values, 'stripslashes_gpc');
			}
			
			$this->zRow->setFromArray($values);
			
			//log this save
			$log = new EditLog();
			$logRow = $log->createRow();
			$logRow->action = 'save';
			$logRow->className = $this->cat;	
			$logRow->user_id = $this->user->user_id;
			
			if($this->getRequest()->isPost())
			{
			  $this->zRow->save();
			  $logRow->save();
			}		
		}
		else
		{
			return $this->render('show');
		}
		
		//validate against subform
		if(isset($this->descRow) && isset($this->descForm))
		{
			$this->descForm->populate($data);
			$descValues = $this->descForm->getValues();
			$this->zDescRow->setFromArray($descValues);
			$this->zDescRow->save();
		}
		
		//$retClass = $this->zRow->getTable()->isDescendent() ? $this->zRow->getTable()->getAscendentClassName() : get_class($this->zRow->getTable());
		
		/*
		$retLink = "<h2><a href=\"/edit/show/cat/".$this->cat.'/id/'. $this->zRow->getID()."/saved/1\" >save worked - click this to carry on...</a></h2>";
		if($this->getRequest()->isPost())
		{
			echo "<h2><a href=\"".BASE."/edit/show/cat/".$this->cat.'/id/'. $this->zRow->getID()."/saved/1\" >save worked - click this to carry on...</a></h2>";
			
		}
		else
		{
		  echo "<h2><a href=\"".BASE."/edit/show/cat/".$this->cat.'/id/'. $this->zRow->getID()."/saved/1\" >save DIDN'T work - click here then try again.</a></h2>";
		}
		
		
		echo "<h2>Testing: ignore</h2>";
		echo "<h3>\$_SERVER array:</h3>";
		print_r($_SERVER);
		
		echo"<h3>\$_POST array</h3>";
		print_r($_POST);
		
		echo "<h3>Request Object:</h3>";
		print_r($this->getRequest());
		
		echo"<br />";
		print_r(file_get_contents('php://input'));
		*/
		
		$this->_redirect('/edit/show/cat/'.$this->cat.'/id/'. $this->zRow->getID().'/saved/1');
	}
		
		
		
	
	public function deleteAction()
	{
		//confirm the delete
		
		if(isset($this->getRequest()->ids))
		{
			$this->zSelect->reset(Zend_Db_Select::WHERE);
			
			$idString = "'".implode("','", $this->getRequest()->ids)."'";
			
			$this->select->where($this->editOb->getPrimary(). "IN ({$idString})");
		}
		
		$this->view->rowset = $this->rowset = $this->editOb->fetchAll($this->select);
		
	}
		
		
	public function dodeleteAction()
	{
		//this is confirmed so do the delete.
		$this->_helper->viewRenderer->setNoRender();
		
		foreach($this->getRequest()->ids as $id)
		{
			$select = $this->editOb->select()->where($this->editOb->getPrimary().'= ?', $id);
			$row = $this->editOb->fetchRow($select);
			
			if($row) 
			{
				$log = new EditLog();
				$logRow = $log->createRow();
				$logRow->action = 'delete';
				$logRow->className = $this->cat;	
				$logRow->user_id = $this->user->user_id;
				$logRow->save();
				
				$row->delete();
			}
		}
		$this->_helper->_redirector->gotoUrl("/list/show/cat/{$this->cat}/id/{$this->id}/");
	}
		
		
	public function imageAction()
	{
		//showing the image edit page
		$this->_helper->layout()->setLayout('image');
		
		$form = new Form_ManageImage();
		$form->setAction(BASE.'/edit/imageupload/cat/'.$this->cat.'/id/'.$this->id);
		
		$this->view->imageForm = $form;
		
	}
	
	
	public function imageuploadAction()
	{
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->setLayout('image');
		
		$return = '/edit/image/cat/'.$this->cat.'/id/'.$this->id;
		
		if(!$this->getRequest()->isPost())
		{
			$this->_redirector->gotoUrl();
		}
		
		//get the image from temp, check size and type, get image object. Use it to save image.
		$tempFile = $_FILES['imagefile']['tmp_name'];
		$tempName = basename($_FILES['imagefile']['name']);
		$fileType = $_FILES['imagefile']['type'];
		
		if(!strstr($fileType,'image') || $_FILES['imagefile']['error'])
		{
			$this->view->errorMessage = 'There was a problem with the file attempted to upload. Is the right type? Is it too large?';
			$this->_forward('image');
			return false;
		}
		
		$image = $this->row->getImage();
		$image->save($tempFile, $tempName);
		
		$this->_redirect($return);
	}
	
	
	
	
	public function addfileAction()
	{
		$this->_helper->viewRenderer->setNoRender();
					
		if(!$this->getRequest()->isPost())
		{
			$this->_redirect($this->stdReturn);
		}
		
		$files = $this->row->getFiles();
		$files->uploadFile($_FILES['upload']);
		
		$this->_redirect($this->stdReturn);
	}
	
	
	
	public function confirmdeletefileAction()
	{
		
		if(!isset($this->getRequest()->num))
		{
			$this->_redirect($this->stdReturn);
		}
		
		$this->view->num = $this->getRequest()->num;
		$this->files = $this->row->getFiles();
		$this->view->file = $this->files->getFileNum($this->getRequest()->num);	
		
	}
	
	
	
	public function dodeletefileAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		if(!isset($this->getRequest()->num)) $this->_redirect($this->stdReturn);
		
		$files = $this->row->getFiles();
		$file = $files->getFileNum($this->getRequest()->num);
		$file->delete();
		
		$this->_redirect($this->stdReturn);
	}
		
		
		
}

?>