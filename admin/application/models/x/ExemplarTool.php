<?php

class x_ExemplarTool extends Model_DbTable_Table
{
	
	protected $_name = 'x_exemplar_tool';
	
	protected static $_fields = array(
		'exemplar_id'=>array(
			'label' => 'exemplar id',
			'roles' => array('x_id'),
			'refTableClass' => 'Exemplar'),
		'tool_id'=>array(
			'label' => 'tool id',
			'roles' => array('x_id'),
			'refTableClass' => 'Tool')
	);
	
	
}

?>