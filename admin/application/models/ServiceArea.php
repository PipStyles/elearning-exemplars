<?php

class ServiceArea extends Model_DbTable_Table
{
	
	protected $_name = 'service_area';
	protected $_primary = 'serviceArea_id';
	
	protected static $_plural = 'service areas';
	protected static $_singular = 'service area';
	
	protected $_dependentTables = array(
		
	);
	
	protected static $_xClasses = array(
		
	);
	
	protected static $_fields = array(
		'serviceArea_id'=>array(
			'label'=>'id'),
		'serviceAreaName'=>array(
			'label' => 'service area name', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'serviceAreaShortDescription'=>array(
			'label' => 'short description', 
			'roles' => array('description'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'textarea'),
		'serviceAreaDescription'=>array(
			'label' => 'description', 
			'roles' => array('description'), 
			'contexts' => array('form'), 
			'formType' => 'textarea'),
		'serviceAreaOrder'=>array(
			'label' => 'display order', 
			'roles' => array('order'), 
			'contexts' => array('list','form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('serviceAreaName');
	protected static $_defaultOrder = 'serviceAreaOrder';
	
	public function byExemplarId($exemplar_id)
	{
		return $this->fetchAll($this->select()->byExemplarId($exemplar_id));
	}
	
	
	
}

?>