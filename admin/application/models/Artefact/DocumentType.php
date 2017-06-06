<?php

class Artefact_DocumentType extends Model_DbTable_Table
{
	
	protected $_name = 'artefact_document_type';
	protected $_primary = 'documentType_id';
	
	protected static $_singular = 'document type';
	
	protected $_listFromRelField = true;
	
	protected static $_xClasses = array();
	
	protected $_dependentTables = array(
		'Artefact_Document'
	);
	
	protected static $_fields = array(
		'documentType_id'=>array(
			'label' => 'id', 
			'contexts' => array()),
		'documentTypeName'=>array(
			'label' => 'title', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('documentTypeName');
	protected static $_defaultOrder = 'documentType_id';
	
	
}

?>