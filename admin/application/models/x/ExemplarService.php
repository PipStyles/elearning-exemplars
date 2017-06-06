<?php

class x_ExemplarService extends Model_DbTable_Table
{
	
	protected $_name = 'x_exemplar_service';
	
	protected static $_fields = array(
		'exemplar_id'=>array(
			'label' => 'exemplar id',
			'roles' => array('x_id'),
			'refTableClass' => 'Exemplar'),
		'service_id'=>array(
			'label' => 'service id',
			'roles' => array('x_id'),
			'refTableClass' => 'Service')
	);
	
	
}

?>