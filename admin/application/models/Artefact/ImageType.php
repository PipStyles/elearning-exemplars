<?php

class Artefact_ImageType extends Model_DbTable_Table
{
	
	protected $_name = 'artefact_image_type';
	protected $_primary = 'imageType_id';
	
	protected static $_singular = 'image type';
	
	protected $_listFromRelField = true;
	
	protected static $_xClasses = array();
	
	protected $_dependentTables = array(
		'Artefact_Image'
	);
	
	protected static $_fields = array(
		'imageType_id'=>array(
			'label' => 'id', 
			'contexts' => array()),
		'imageTypeName'=>array(
			'label' => 'title', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('imageTypeName');
	protected static $_defaultOrder = 'imageType_id';
	
	
}

?>