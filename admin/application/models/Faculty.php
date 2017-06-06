<?php

class Faculty extends Model_DbTable_Table
{
	
	protected $_name = 'faculty';
	protected $_primary = 'faculty_id';
	
	protected static $_plural = 'faculties';
	
	protected $_dependentTables = array(
		'school'
	);
	
	//protected static $_xClasses = array();
	
	protected static $_fields = array(
		'faculty_id'=>array(
			'label'=>'id'),
		'facultyName'=>array(
			'label' => 'faculty', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'facultyCode'=>array(
			'label' => 'code', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('facultyName');
	protected static $_defaultOrder = 'faculty_id';
	
	
	
	
	
}

?>