<?php

class Artefact extends Model_DbTable_Table
{
	
	protected $_name = 'artefact';
	protected $_primary = 'artefact_id';
	
	public static $_hasImage = true;
	
	protected static $_hasDescendent = true;
	
	protected static $_fileRoot = '/tandl/elearning/exemplars/files/Artefact/';
	
	protected static $_xClasses = array(
		'Exemplar' => 'x_ExemplarArtefact'
	);
	
	protected $_dependentTables = array(
		'x_ExemplarArtefact'
	);
	
	protected static $_fields = array(
		'artefact_id'=>array(
			'label' => 'id', 
			'contexts' => array()),
		'artefactTitle'=>array(
			'label' => 'title', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'artefactType'=>array(
			'label' => 'type', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select',
			'refTableClass' => 'ArtefactType'), 
		'artefactShortDescription'=>array(
			'label' => 'short description', 
			'roles' => array('description'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'textarea',
			'fieldHint' => 'Keep this short - it appears in quite small contexts'),
		'artefactAuthor'=>array(
			'label' => 'author', 
			'roles' => array('description'), 
			'contexts' => array('form'), 
			'formType' => 'select',
			'refTableClass' => 'Person',
			'showZero'=>true,
			'fieldHint' => 'You may need to create this person in the People section first')
	);
	
	protected static $_nameFields = array('artefactTitle');
	protected static $_defaultOrder = 'artefact_id';
	protected static $_defaultOrderDirection = 'DESC';
	
	protected static $_groupField = 'artefactType';
	
	
	protected static $_descendentRules = array(
	'Artefact_Document' => array('fieldName' => 'artefactType', 'value' => '1'),
	'Artefact_Image' => array('fieldName' => 'artefactType', 'value' => '2'),
	'Artefact_Video' => array('fieldName' => 'artefactType', 'value' => '3'),
	'Artefact_Soundrec' => array('fieldName' => 'artefactType', 'value' => '4'),
	'Artefact_Url' => array('fieldName' => 'artefactType', 'value' => '5'),
	'Artefact_Screencast' => array('fieldName' => 'artefactType', 'value' => '6')
	);
	
	
	public function byExemplarId($exemplar_id)
	{
		return $this->fetchAll($this->select()->joinedRefs()->byExemplarId($exemplar_id));
	}
	
	
	
}

?>