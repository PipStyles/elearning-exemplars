<?php

class DetailController extends CommonController
{

    public function init()
    {
			parent::init();
    	$this->view->addHelperPath('application/views/helpers', 'View_Helper');
		}

    public function indexAction()
    {
			echo "form/index";
    }

		public function showAction()
		{
		$cat = $this->cat = $this->getRequest()->cat;
		$detailOb = new $cat();
		$this->view->row = $row = $detailOb->fetchRow($detailOb->select()->joinedRefs()->byPrimary($this->getRequest()->id));
		
		}
	
		
}

?>