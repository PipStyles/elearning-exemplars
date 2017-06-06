<?php

class Service extends Model_DbTable_Table
{
	protected $_rowClass = 'Service_Row';
	
	protected $_name = 'service';
	protected $_primary = 'service_id';
	
	protected $_dependentTables = array(
		'ServiceArea',
		'x_ExemplarService'
	);
	
	protected static $_xClasses = array(
		'Exemplar' => 'x_ExemplarService'
	);
	
	protected static $_fields = array(
		'service_id'=>array(
			'label'=>'id'),
		'serviceName'=>array(
			'label' => 'service', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'serviceArea'=>array(
			'label' => 'service area', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select',
			'refTableClass' => 'ServiceArea'),
		'serviceShortDescription'=>array(
			'label' => 'short description', 
			'roles' => array('description'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'textarea',
			'fieldHint' => 'This is the description which will appear in listings.'),
		'serviceBenefits'=>array(
			'label' => 'benefits', 
			'roles' => array('description'), 
			'contexts' => array('form'), 
			'formType' => 'textarea',
			'fieldHint' => 'This should contain an unordered list and any additional text as required'),
		'serviceInvolvement'=>array(
			'label' => 'what\'s involved', 
			'roles' => array('description'), 
			'contexts' => array('form'), 
			'formType' => 'textarea'), 
		'serviceTimeCommitment'=>array(
			'label' => 'time commitment', 
			'roles' => array('description'), 
			'contexts' => array('form'), 
			'formType' => 'textarea'), 
		'serviceWhatsNext'=>array(
			'label' => 'what\'s next', 
			'roles' => array('description'), 
			'contexts' => array('form'), 
			'formType' => 'textarea'), 
		'serviceOrder'=>array(
			'label' => 'display order', 
			'roles' => array('order'), 
			'contexts' => array('list','form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('serviceName');
	protected static $_defaultOrder = array('serviceArea ASC','serviceOrder ASC');
	
	protected static $_groupField = 'serviceArea';
	
	
	
	public function getExemplarSelect()
	{
		$exemplarOb = new Exemplar();
		$select = $exemplarOb->select()->distinct()->joinedRefs()->joinLeft_Service();
		return $select;
		
	}	
	
	public function byExemplarId($exemplar_id)
	{
		$select = $this->select()->byExemplarId($exemplar_id);
		return $this->fetchAll($select);
	}
		
	
	
}

?>