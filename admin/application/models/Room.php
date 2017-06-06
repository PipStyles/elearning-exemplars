<?php

class Room extends Model_DbTable_Table
{
	
	protected $_name = 'room';
	protected $_primary = 'room_id';
	
	protected $_dependentTables = array(
		'course'
	);
	
	//protected static $_xClasses = array();
	
	protected static $_fields = array(
		'room_id'=>array(
			'label'=>'id'),
		'roomCode'=>array(
			'label' => 'room', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'roomName'=>array(
			'label' => 'room', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('roomName');
	protected static $_defaultOrder = 'room_id';
	
	
	
	
	
}

?>