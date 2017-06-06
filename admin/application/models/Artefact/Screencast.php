<?php

class Artefact_Screencast extends Artefact
{
	
	protected $_name = 'ext_artefact_screencast';
	protected $_primary = 'artefact_id';
	
	protected static $_singular = 'screencast';
	
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
			'fieldHint' => 'This is the url of the screencast which will be shown to the user as a clickable link. Please include "http://" or other protocol, as required.')
	);
	
}

?>