<?php

class ArtefactType extends Model_DbTable_Table
{
	
	protected $_name = 'artefact_type';
	protected $_primary = 'artefactType_id';
	
	protected static $_singular = 'artefact type';
	protected static $_plural = 'artefact types';
	
	protected $_listFromRelField = true;
	
	protected static $_xClasses = array();
	
	protected $_dependentTables = array(
		
	);
	
	protected static $_fields = array(
		'artefactType_id'=>array(
			'label' => 'id', 
			'contexts' => array()),
		'artefactTypeName'=>array(
			'label' => 'title', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('artefactTypeName');
	protected static $_defaultOrder = 'artefactType_id';
	
	
}

?>