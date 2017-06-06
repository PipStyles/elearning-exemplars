<?php

class Artefact_Soundrec extends Artefact
{
	
	protected $_name = 'ext_artefact_soundrec';
	protected $_primary = 'artefact_id';
	
	protected static $_singular = 'sound recording';
	
	public static $_hasFiles = false;
	
	protected static $_isDescendent = true;
	
	protected $_dependentTables = array(
		'Artefact'
	);
	
	protected static $_fields = array(
		'artefact_id'=>array(
			'label' => 'id', 
			'contexts' => array()), 
		'soundrecType'=>array(
			'label' => 'type', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select', 
			'refTableClass' => 'Artefact_SoundrecType'),
		'soundrecUrl'=>array(
			'label' => 'url', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text',
			'fieldHint' => 'This should be an accessible location online. Put the full url.')
	);
		
	
}

?>