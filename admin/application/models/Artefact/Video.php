<?php

class Artefact_Video extends Artefact
{
	
	protected $_name = 'ext_artefact_video';
	protected $_primary = 'artefact_id';
	
	protected static $_singular = 'video';
	
	public static $_hasFiles = false;
	
	protected static $_isDescendent = true;
	
	protected $_dependentTables = array(
		'Artefact'
	);
	
	protected static $_fields = array(
		'artefact_id'=>array(
			'label' => 'id', 
			'contexts' => array()), 
		'videoType'=>array(
			'label' => 'type', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select', 
			'refTableClass' => 'Artefact_VideoType'),
		'videoUrl'=>array(
			'label' => 'url', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text',
			'fieldHint' => 'If this is in an accessible location online, put the full url.'), 
		'videoVLS_id'=>array(
			'label' => 'VLS id', 
			'roles' => array(), 
			'contexts' => array('form'), 
			'formType' => 'text',
			'fieldHint'=>'If this is a VLS video, enter the ID'),
		'videoEmbedCode'=>array(
			'label' => 'embedding code', 
			'roles' => array(), 
			'contexts' => array('form'), 
			'formType' => 'textarea',
			'fieldHint'=>'If this video can be embedded, copy the html to do so in here')
	);
	
	//protected static $_nameFields = array();
	//protected static $_defaultOrder = 'artefact_id';
	
	
}

?>