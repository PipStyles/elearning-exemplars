<?php

class Artefact_Document extends Artefact
{
	
	protected $_name = 'ext_artefact_document';
	protected $_primary = 'artefact_id';
	
	protected static $_singular = 'document';
	
	public static $_hasFiles = true;
	
	//protected static $_fileRoot = '/tandl/elearning/exemplars/files/Artefact';
	
	protected static $_isDescendent = true;
	
	protected $_dependentTables = array(
		'Artefact'
	);
	
	protected static $_fields = array(
		'artefact_id'=>array(
			'label' => 'id', 
			'contexts' => array()), 
		'documentType'=>array(
			'label' => 'type', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select', 
			'refTableClass' => 'Artefact_DocumentType')
	);
	
	//protected static $_nameFields = array();
	//protected static $_defaultOrder = 'artefact_id';
	
	
}

?>