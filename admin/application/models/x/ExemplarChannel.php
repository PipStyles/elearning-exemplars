<?php

class x_ExemplarChannel extends Model_DbTable_Table
{
	
	protected $_name = 'x_exemplar_channel';
	
	protected static $_fields = array(
		'exemplar_id'=>array(
			'label' => 'exemplar id',
			'roles' => array('x_id'),
			'refTableClass' => 'Exemplar'),
		'channel_id'=>array(
			'label' => 'channel id',
			'roles' => array('x_id'),
			'refTableClass' => 'Channel')
	);
		
}

?>