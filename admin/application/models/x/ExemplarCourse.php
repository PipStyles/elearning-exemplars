<?php

class x_ExemplarCourse extends Model_DbTable_Table
{
	
	protected $_name = 'x_exemplar_course';
	
	protected static $_fields = array(
		'exemplar_id'=>array(
			'label' => 'exemplar id',
			'roles' => array('x_id'),
			'refTableClass' => 'Exemplar'),
		'course_id'=>array(
			'label' => 'course id',
			'roles' => array('x_id'),
			'refTableClass' => 'Course')
	);
	
	
}

?>