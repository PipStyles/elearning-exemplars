<?php


class MmeditController extends CommonController
{

	public function init()
	{
		parent::init();
		
		$this->_helper->layout->setLayout('mmedit');
		
		$cat = $this->cat = $this->getRequest()->cat;
		$this->table = new $cat();
		
		$this->id = $this->getRequest()->id;
		
		if(!isset($this->getRequest()->destcat))
		{
			$this->render('mmedit/error.phtml');
			exit;
		}
		
		
		$destCat = $this->destCat = $this->getRequest()->destcat;
		$this->destTable = new $destCat();
		
		$this->rows = $this->table->find($this->id);
		$this->view->row = $this->row = $this->rows->current();
		$this->view->selected = $this->existingRowset = $this->row->getManyToManyRowset($this->destCat);
		$xTableName = $this->table->getxClassByDestClass($destCat);
		$this->xTable = new $xTableName();
		
		$unSelected = $this->destTable->select()->joinedRefs();
		$unSelected->setIntegrityCheck(false);
		
		$ids = array();
		foreach($this->existingRowset as $row)
		{
			$ids[] = $row->getID();
		}
		
		$unSelected->where($this->destTable->getPrimary() . " NOT IN ('".implode("','", $ids)."')");
		
		if($groupField = $this->destTable->getGroupField())
		{
			$unSelected->order($this->destTable->getName().'.'.$groupField);
			$this->view->groupField = $groupField;
		}
		
		$unSelected->order($this->destTable->getDefaultOrder());
		
		$this->view->unselected = $this->unselectedRowset = $this->destTable->fetchAll($unSelected);
		
	}
	
	
	public function showAction()
	{
		
	}

	
	public function saveAction()
	{
		$redir = "/mmedit/show/cat/{$this->cat}/id/{$this->id}/destcat/{$this->destCat}";
		
		if(!$this->getRequest()->isPost())
		{
			$this->_redirect($redir);
		}
		
		$this->_helper->viewRenderer->setNoRender();
						
		$this->ids = $this->getRequest()->ids;
		
		$this->row->saveManyToMany($this->destCat, $this->ids);
		
		$this->_redirect($redir);
	}



}


?>