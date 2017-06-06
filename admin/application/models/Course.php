<?php

class Course extends Model_DbTable_Table
{
	
	protected $_name = 'course';
	protected $_primary = 'course_id';
	
	protected $_dependentTables = array(
		'x_ExemplarCourse'
	);
	
	protected static $_xClasses = array(
		'Exemplar' => 'x_ExemplarCourse'
	);
	
	protected static $_fields = array(
		'course_id'=>array(
			'label'=>'id'),
		'courseTitle'=>array(
			'label' => 'title', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form')),
		'courseDiscipline'=>array(
			'label' => 'discipline', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select', 
			'refTableClass' => 'Discipline',
			'refTableTitleFields' => array('disciplineCode')),
		'courseCatnum'=>array(
			'label' => 'catalogue number', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form')),
		'courseSchool'=>array(
			'label' => 'school', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select', 
			'refTableClass' => 'School'),
		'courseTerm'=>array(
			'label' => 'term', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select', 
			'refTableClass' => 'Term'),
		'courseSession'=>array(
			'label' => 'session', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select', 
			'refTableClass' => 'Semester'),
		'courseNumStudents'=>array(
			'label' => 'number of students', 
			'roles' => array(), 
			'contexts' => array('list', 'form')),
		'courseDescription'=>array(
			'label' => 'description', 
			'roles' => array('description'), 
			'contexts' => array('form'), 
			'formType' => 'textarea')
		
	);
	
	
	protected static $_nameFields = array('disciplineCode', 'courseCatnum' , 'courseTitle');
	protected static $_defaultOrder = 'courseTitle';
	
	
	public function getTitle($row)
	{
		if(isset($row->DISP_courseDiscipline))
		{
			return substr($row->DISP_courseDiscipline,0,4).$row->courseCatnum.' '.$row->courseTitle;
		}
		
		return parent::getTitle($row);
	}
	
	public function byExemplarId($exemplar_id)
	{
		return $this->fetchAll($this->select()->joinedRefs()->byExemplarId($exemplar_id));
	}
	
	
	
	
}

?>