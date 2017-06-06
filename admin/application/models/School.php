<?php

class School extends Model_DbTable_Table
{
	
	protected $_name = 'school';
	protected $_primary = 'school_id';
	
	protected $_dependentTables = array(
		'discipline',
		'course'
	);
	
	protected static $_xClasses = array(
		
	);
	
	protected static $_fields = array(
		'school_id'=>array(
			'label'=>'id'),
		'schoolName'=>array(
			'label' => 'school', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'schoolCode'=>array(
			'label' => 'code', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'schoolFaculty'=>array(
			'label' => 'faculty', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select',
			'refTableClass' => 'Faculty'
			)
	);
	
	protected static $_nameFields = array('schoolName');
	protected static $_defaultOrder = 'schoolFaculty';
	
	
	public function select()
	{
		return new School_Select($this);
	}
	
	public function byExemplarId($exemplar_id)
	{
		return $this->fetchAll($this->select()->byExemplarId($exemplar_id));
	}
	
	
	
}

?>