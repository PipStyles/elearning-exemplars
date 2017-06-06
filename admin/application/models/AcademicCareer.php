<?php

class AcademicCareer extends Model_DbTable_Table
{
	
	protected $_name = 'academic_career';
	protected $_primary = 'academicCareer_id';
	
	protected static $_xClasses = array();
	
	protected $_dependentTables = array(
		
	);
	
	protected static $_fields = array(
		'academicCareer_id'=>array(
			'label' => 'id', 
			'contexts' => array()), 
		'academicCareerName'=>array(
			'label' => 'title', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'academicCareerCode'=>array(
			'label' => 'code', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('academicCareerName');
	protected static $_defaultOrder = 'academicCareer_id';
	
	
}

?>