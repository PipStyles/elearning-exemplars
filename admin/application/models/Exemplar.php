<?php

class Exemplar extends Model_DbTable_Table
{
	protected $_rowClass = 'Exemplar_Row';
	
	const PUBLIC_PUBLISH_LEVEL = 3;
	
	protected $_name = 'exemplar';
	protected $_primary = 'exemplar_id';
	protected $_select;
	
	protected static $_nameFields = array('exemplarTitle');
	protected static $_defaultOrder = 'exemplar_id';
	protected static $_defaultOrderDirection = 'DESC';
	protected static $_groupField = 'exemplarPublishStatus';
	
	public static $_hasImage = true;
	
	protected static $_xClasses = array(
		'Artefact' => 'x_ExemplarArtefact',
		'Course' => 'x_ExemplarCourse',
		'SubTheme' => 'x_ExemplarSubTheme',
		'Tool' => 'x_ExemplarTool',
		'Service' => 'x_ExemplarService',
		'Channel' => 'x_ExemplarChannel',
		'Fund' => 'x_ExemplarFund'
	);
	
	protected $_dependentTables = array(
		'x_ExemplarArtefact',
		'x_ExemplarCourse',
		'x_ExemplarSubTheme',
		'x_ExemplarTool',
		'x_ExemplarService',
		'x_ExemplarChannel',
		'x_ExemplarFund'
	);
	
	protected static $_derivationMap = array(
		'schools' => array('Course' => 'x_ExemplarCourse', 'School' => 'courseSchool')
	);
	
	protected static $_fields = array(
		'exemplar_id'=>array(
			'label' => 'id', 
			'contexts' => array()),
		'exemplarTitle'=>array(
			'label' => 'title', 
			'roles' => array('title', 'textSearch'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'exemplarShortDescription'=>array(
			'label' => 'short description', 
			'roles' => array('description', 'textSearch'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'textarea',
			'fieldHint' => 'This description will appear in listings. Keep it short.'),
		'exemplarDescription'=>array(
			'label' => 'description',
			'roles' => array('description', 'textSearch'), 
			'contexts' => array('form'), 
			'formType' => 'textarea',
			'fieldHint' => 'The full description which appears in the main example page.'),
		'exemplarBenefits'=>array(
			'label' => 'benefits',
			'roles' => array('description', 'textSearch'), 
			'contexts' => array('form'), 
			'formType' => 'textarea',
			'fieldHint' => 'This should contain a plain, unordered list and further text as required.'),
		'exemplarAcademicContact'=>array(
			'label' => 'Academic contact',
			'roles' => array(), 
			'contexts' => array('form'), 
			'formType' => 'select',
			'fieldHint' => 'Leave blank if none is appropriate',
			'refTableClass' => 'Person',
			'showZero' => true),
		'exemplarPublishStatus'=>array(
			'label' => 'publish status',
			'roles' => array('publishStatus'), 
			'contexts' => array('list','form'), 
			'formType' => 'select',
			'refTableClass'=>'PublishStatus', 
			'fieldHint' => "Indicate the status of this exemplar. Only 'publish' will show the exemplar to public visitors."),
		'exemplarDeleteFlag'=>array(
			'label' => 'deleted?',
			'roles' => array('deleteFlag'), 
			'contexts' => array(), 
			'formType' => 'radio'),
	    'exemplarLastModifiedUsername'=>array(
			'label' => 'Last edited username',
			'contexts' => array('list'),
			'refTableClass' => 'User',
			'refTableField' => 'username'),
		  'exemplarLastModifiedUsername'=>array(
			'label' => 'Created username',
			'contexts' => array(),
			'refTableClass' => 'User',
			'refTableField' => 'username'
			)
	);
	
	/*
	public function select()
	{
		return new Exemplar_Select();
	}*/
	
	
  	
	public function getLatest($num = 10)
	{
		return $this->fetchAll($this->select()->latest($num)->published());
	}
	
	public function getRefinedSelect($get, $matchAny = 0, $select = null)
	{
		return $this->select()->distinct()->refined($get, $matchAny)->published();
	}
	
	
	
	
	public function byServiceId($service_id)
	{
		return $this->fetchAll($this->select()->published()->byServiceId($service_id));
	}
	
	public function byChannelNames($names)
	{
		$names = is_array($names) ? $names : explode(',', $names);
		return $this->fetchAll($this->select()->published()->byChannelNames());
	}

	public function byChannelIds($ids)
	{
		return $this->fetchAll($this->select()->published()->byChannelIds($ids));
	}
	
	
}

?>