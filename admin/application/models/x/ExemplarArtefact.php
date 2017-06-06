<?php

class x_ExemplarArtefact extends Model_DbTable_Table
{
	
	protected $_name = 'x_exemplar_artefact';
	
	protected static $_fields = array(
		'exemplar_id'=>array(
			'label' => 'exemplar id',
			'roles' => array('x_id'),
			'refTableClass' => 'Exemplar'),
		'artefact_id'=>array(
			'label' => 'artefact id',
			'roles' => array('x_id'),
			'refTableClass' => 'Artefact')
	);
		
}

?>