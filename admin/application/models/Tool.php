<?php

class Tool extends Model_DbTable_Table
{
	
	protected $_name = 'tool';
	protected $_primary = 'tool_id';
	
	protected $_dependentTables = array(
		'x_ExemplarTool'
	);
	
	protected static $_xClasses = array(
		'Exemplar' => 'x_ExemplarTool'
	);
	
	protected static $_fields = array(
		'tool_id'=>array(
			'label'=>'id'),
		'toolName'=>array(
			'label' => 'tool', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('toolName');
	protected static $_defaultOrder = 'toolName';
	
	
	
}

?>