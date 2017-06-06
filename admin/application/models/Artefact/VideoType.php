<?php

class Artefact_VideoType extends Model_DbTable_Table
{
	
	protected $_name = 'artefact_video_type';
	protected $_primary = 'videoType_id';
	
	protected static $_singular = 'video type';
	protected static $_plural = 'video types';
	
	protected $_listFromRelField = true;
	
	protected static $_xClasses = array();
	
	protected $_dependentTables = array(
		'Artefact_Image'
	);
	
	protected static $_fields = array(
		'videoType_id'=>array(
			'label' => 'id', 
			'contexts' => array()),
		'videoTypeName'=>array(
			'label' => 'title', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('videoTypeName');
	protected static $_defaultOrder = 'videoType_id';
	
	
}

?>