<?php

class Artefact_Image extends Artefact
{
	
	protected $_name = 'ext_artefact_image';
	protected $_primary = 'artefact_id';
	
	protected static $_singular = 'image';
	
	protected static $_isDescendent = true;
	
	public static $_hasFiles = false;
	
	//protected static $_fileRoot = '/tandl/elearning/exemplars/files/Artefact';
	
	protected $_dependentTables = array(
		'Artefact'
	);
	
	protected static $_fields = array(
		'artefact_id'=>array(
			'label' => 'id', 
			'contexts' => array()), 
		'imageType'=>array(
			'label' => 'type', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select', 
			'refTableClass' => 'Artefact_ImageType'), 
		'imageUrl'=>array(
			'label' => 'location', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	
	//protected static $_nameFields = array();
	//protected static $_defaultOrder = 'artefact_id';
	
	
}

?>