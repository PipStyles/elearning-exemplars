<?php

class x_ExemplarFund extends Model_DbTable_Table
{
	
	protected $_name = 'x_exemplar_fund';
	
	protected static $_fields = array(
		'exemplar_id'=>array(
			'label' => 'exemplar id',
			'roles' => array('x_id'),
			'refTableClass' => 'Exemplar'),
		'fund_id'=>array(
			'label' => 'fund id',
			'roles' => array('x_id'),
			'refTableClass' => 'Fund')
	);
		
}

?>