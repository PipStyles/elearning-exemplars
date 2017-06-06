<?php

class Term extends Model_DbTable_Table
{
	
	protected $_name = 'term';
	protected $_primary = 'term_id';
	
	protected $_dependentTables = array(
		'course'
	);
	
	//protected static $_xClasses = array();
	
	protected static $_fields = array(
		'term_id'=>array(
			'label'=>'id'),
		'termCode'=>array(
			'label' => 'term', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'termName'=>array(
			'label' => 'term', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('termName');
	protected static $_defaultOrder = 'term_id';
	
	
	
	
	
}

?>