<?php

class Artefact_SoundrecType extends Model_DbTable_Table
{
	
	protected $_name = 'artefact_soundrec_type';
	protected $_primary = 'soundrecType_id';
	
	protected static $_singular = 'sound recording type';
	
	protected $_listFromRelField = true;
	
	protected static $_xClasses = array();
	
	protected $_dependentTables = array(
		'Artefact_Image'
	);
	
	protected static $_fields = array(
		'soundrecType_id'=>array(
			'label' => 'id', 
			'contexts' => array()),
		'soundrecTypeName'=>array(
			'label' => 'title', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('soundrecTypeName');
	protected static $_defaultOrder = 'soundrecType_id';
	
	
}

?>