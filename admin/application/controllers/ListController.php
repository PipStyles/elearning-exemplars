<?php

class ListController extends CommonController
{

	public function init()
	{
		parent::init();
		
		if(!isset($this->getRequest()->cat)) $this->_redirect('/index/show/');
		
		$cat = $this->cat = $this->getRequest()->cat;
		
		$this->listOb = new $cat();
		
		
		if(@$this->getRequest()->refine == true)
		{
			
			
		}
		
		
	}
	
	
	public function indexAction()
	{
		$this->_helper->redirector('show', 'list', null, array('cat' => $this->getRequest()->cat));
	}
	
		
		
	public function showAction()
	{
	  $cat = $this->cat;
		$select = $this->listOb->select()->joinedRefs()->notDeleted()->order($this->listOb->getDefaultOrder());
		
		$this->view->model = $this->listOb;
		
		if($this->listOb->canListAll())
		{
			$this->view->rowset = $this->rowset = $this->listOb->fetchAll($select);
		}
		
		if(count($this->rowset) == 0)
		{
			$this->view->render('list/noRecords.phtml');
		}
		
	}
	
	public function noRecordsAction()
	{
	
	}
	
	
	
	
	
	public function processAction()
	{
			//this is dealing with input from the "with selected" list controls
			//print_r($_POST);
			$this->_helper->viewRenderer->setNoRender();
			
			if(!isset($this->getRequest()->ids) || !is_array($this->getRequest()->ids))
			{
				$this->_forward('show');
				return false;
			}
			
			$actionName = $this->getRequest()->doProcess;
			
			$cat = $this->cat;
			$list = new $cat();
			
			$select = $list->select()->where($list->getPrimary()." IN ('".implode("','", $this->getRequest()->ids)."')");
			
			$this->rowset = $list->fetchAll($select);
			
			$forwardParams = array();
			if($this->isRefine)
			{
				$forwardParams['refine'] = 1;
			}
			
			
			switch($actionName)
			{
				case "setPublishStatus":
				//set all publishStatus for all ids to the value of whatever is sent in by ***publishStatus***
				$pField = $list->getFieldNamesByRole('publishStatus');
				foreach($this->rowset as $row)
				{
					$row->$pField[0] = $this->getRequest()->$pField[0];
					
					$row->save();
				}
				
				$this->_forward('show');
				break;
				
				case "delete":
					$this->_forward('delete');
				break;
				
				default:
					//$this->_forward('index');
				break;
			}
			
		}
	
		
		public function showMultiple($rowset)
		{
			$visField = $rowset->getTable()->getVisField();
			foreach($rowset as $row)
			{
				$row->$visField = 'show';
				$row->save();
			}
		}
		
		public function hideMultiple($rowset)
		{
			$visField = $rowset->getTable()->getVisField();
			foreach($rowset as $row)
			{
				$row->$visField = 'hide';
				$row->save();
			}
		}
		
		
		
		/*
		returns query string to persist refine across actions
		*/
		public function getRefineQueryString($listOb, $request)
		{
			$out = "";
			foreach($listOb->getFieldsByContext('refine') as $fieldName => $info)
			{
				if(isset($request->$fieldName))
				{
					$out .= $fieldName.'='.$request->$fieldName.'&';
				}
			}
			return rtrim($out, '&');
		}
		
		
	
		public function toggleAction()
		{
			$this->_helper->viewRenderer->setNoRender();
			
			$this->_redirect("/list/show/cat/{$this->cat}/".$this->refineString);
			
			$new_request = clone $this->getRequest();
			$new_request->setActionName('dotoggle')
               ->setControllerName('edit');
    	$this->_helper->actionStack($new_request);
			
		}
		
		
		public function deleteAction()
		{
			$cat = $this->cat;
			$list = new $cat();
			
			$this->select = $list->select()->where($list->getPrimary()." IN ('".implode("','", $this->getRequest()->ids)."')");
			
			$this->view->rowset = $this->rowset = $list->fetchAll($this->select);
			
		}
		
		
		public function dodeleteAction()
		{
			
			
		}
		
		
}

?>