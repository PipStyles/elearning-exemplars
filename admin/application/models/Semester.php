<?php

class Semester extends Model_DbTable_Table
{
	
	protected $_name = 'semester';
	protected $_primary = 'semester_id';
	
	protected $_dependentTables = array(
		'course'
	);
	
	//protected static $_xClasses = array();
	
	protected static $_fields = array(
		'semester_id'=>array(
			'label'=>'id'),
		'semesterCode'=>array(
			'label' => 'semester', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'semesterName'=>array(
			'label' => 'semester', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('semesterName');
	protected static $_defaultOrder = 'semester_id';
	
	
}

?>