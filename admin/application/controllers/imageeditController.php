<?php


class ImageeditController extends CommonController
{

	public function init()
	{
		$this->_helper->layout->setLayout('imageedit');
		
		$cat = $this->cat = $this->getRequest()->cat;
		$this->table = new $cat();
		
		$this->id = $this->getRequest()->id;
		
		$this->rows = $this->table->find($this->id);
		$this->view->row = $this->row = $this->rows->current();
		
		
	}

	
	public function showAction()
	{
		
		
		
	}

	
	public function saveAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		//$saveSelect = $this->xTable->select();
		
		if(!isset($this->getRequest()->ids) || !count($this->getRequest()->ids))
		{
			//ids blank - delete all?
			
			//$this->row->clearManyToManyRecords($destCat);
		}
		
				
		$this->ids = $this->getRequest()->ids;
		
		$this->row->saveManyToMany($this->destCat, $this->ids);
		
		$this->_redirect("/mmedit/show/cat/{$this->cat}/id/{$this->id}/destcat/{$this->destCat}");
		
	}



}


?>