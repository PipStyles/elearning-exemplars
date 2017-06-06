<?php

class x_ExemplarSubTheme extends Model_DbTable_Table
{
	
	protected $_name = 'x_exemplar_subtheme';
	
	protected static $_fields = array(
		'exemplar_id'=>array(
			'label' => 'exemplar id',
			'roles' => array('x_id'),
			'refTableClass' => 'Exemplar'),
		'subtheme_id'=>array(
			'label' => 'theme id',
			'roles' => array('x_id'),
			'refTableClass' => 'SubTheme')
	);
	
	
}

?>