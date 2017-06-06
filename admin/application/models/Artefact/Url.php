<?php

class Artefact_Url extends Artefact
{
	
	protected $_name = 'ext_artefact_webpage';
	protected $_primary = 'artefact_id';
	
	protected static $_singular = 'url';
	
	public static $_hasFiles = false;
	
	protected static $_isDescendent = true;
	
	protected $_dependentTables = array(
		'Artefact'
	);
	
	protected static $_fields = array(
		'artefact_id'=>array(
			'label' => 'id', 
			'contexts' => array()), 
		'url'=>array(
			'label' => 'url', 
			'roles' => array('url'), 
			'contexts' => array('form'), 
			'formType' => 'text',
			'fieldHint' => 'This is the url which will be shown to the user as a clickable link. Please include "http://" or other protocol, as required.')
	);
	
}

?>