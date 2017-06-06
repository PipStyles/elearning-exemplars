<?php

class AjaxController extends CommonController
{
	
	
	public function init()
	{
		parent::init();
		$this->_helper->layout()->setLayout('ajax');
		
	}
	
	
	public function getlocationAction()
	{
		
		if(@!$this->getRequest()->cat || @!$this->getRequest()->id) return null;
				
		$cat = $this->getRequest()->cat;
		$id = intval($this->getRequest()->id);
		
		$catOb = new $cat();
		
		if(!$catOb->canMap()) return null;
		
		$rowset = $catOb->find($id);
		
		
		$this->view->row = $row = $rowset->current();
		
		$this->render('ajax');
		
	}
	
	
	public function getjsonlocationsAction()
	{
		if(@!$this->getRequest()->cat)
		{
			$this->render('blank/blank.phtml');
		}
		
		$cat = $this->getRequest()->cat;
		
		$this->mapOb = new $cat();
		
		$latField = $this->mapOb->getLatitudeField();
		$lngField = $this->mapOb->getLongitudeField();
		
		$select = $this->mapOb->select()->where($latField.' IS NOT NULL')
															->where($latField.' != '. 0.000000);
		
		if(!$this->user && $this->mapOb->canHide())
		{
			$visField = $this->mapOb->getVisField();
			$select->where($visField.' = ?', 'show');
		}
		
		$nameFields = $this->mapOb->getNameFields();
		
		$fieldsArray = array('primaryId' => $this->mapOb->getPrimary(), 'lat' => $latField, 'lng' => $lngField);
		
		if(is_array($nameFields))
		{
			$fieldsArray = array_merge($fieldsArray, array('title' => $nameFields[0]));
		}
		else
		{
			$fieldsArray['title'] = $nameFields;
		}
		
		$select->from($this->mapOb->info('name'), array_merge($fieldsArray));
		
		//$select->limit(5);
		
		$this->rowset = $this->view->rowset = $this->mapOb->fetchAll($select);
		
		
	}
	
	
	
	public function getjsoncountriesAction()
	{
		if(@!$this->getRequest()->cat)
		{
			$this->render('blank/blank.phtml');
		}
		
		$cat = $this->getRequest()->cat;
		
		$this->mapOb = new $cat();
		
		$latField = $this->mapOb->getLatitudeField();
		$lngField = $this->mapOb->getLongitudeField();
		
		$countryFields = $this->mapOb->getFieldNamesByRole('country');
		$countryField = $countryFields[0];
		
		$countryOb = new Country();
		
		$cnlatField = $countryOb->getLatitudeField();
		$cnlngField = $countryOb->getLongitudeField();
				
		$select = $this->mapOb->select()->where($latField.' IS NOT NULL')
																	->where($latField.' != '. '0.000000');
															
		$select->setIntegrityCheck(false);
		
		
		if(!$this->user && $this->mapOb->canHide())
		{
			$visField = $this->mapOb->getVisField();
			$select->where($visField.' = ?', 'show');
		}
		
		$nameFields = $this->mapOb->getNameFields();
		
		$fieldsArray = array('primaryId' => $this->mapOb->getPrimary(), 'lat' => $latField, 'lng' => $lngField);
		
		if(is_array($nameFields))
		{
			$fieldsArray = array_merge($fieldsArray, array('title' => $nameFields[0]));
		}
		else
		{
			$fieldsArray['title'] = $nameFields;
		}
		
		$select->from($this->mapOb->info('name'), array_merge($fieldsArray));
		$select->joinLeft(array($countryOb->info('name')), "`{$countryField}` = `{$countryOb->info('name')}`.`{$countryOb->getPrimary()}`", array('countryId' => $countryOb->getPrimary(), 'country' => 'common_name', 'cnLat' => $cnlatField , 'cnLng' => $cnlngField ));
		$select->where("`{$countryOb->info('name')}`.`{$countryOb->getPrimary()}` IS NOT NULL");
		$select->where($cnlatField." != '0'");
		
		//$select->limit(10);
		
		
		$this->rowset = $this->view->rowset = $this->mapOb->fetchAll($select);
		
	}
	
	
	
	
}

?>