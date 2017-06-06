<?php

class EditLog extends Model_DbTable_Table
{
	protected $_rowClass = 'Model_DbTable_Row';
	
	protected $_primary = 'edit_id';
	protected $_name = 'db_edit_log';
	
	protected static $_fields = array(
		'edit_id'=>array(
			'label' => 'id', 
			'contexts' => array('list')),
		'action'=>array(
			'label' => 'action', 
			'roles' => array(), 
			'contexts' => array('list')), 
		'timestamp'=>array(
			'label' => 'timestamp', 
			'roles' => array('timestamp'), 
			'contexts' => array('list')),
		'user_id'=>array(
			'label' => 'user', 
			'roles' => array(), 
			'contexts' => array('list'),
			'refTableClass' => 'User'),
		'className'=>array(
			'label' => 'class', 
			'roles' => array(), 
			'contexts' => array('list'))
	);
	
	protected static $_nameFields = array('user_id', 'className', 'action');
	protected static $_defaultOrder = 'edit_id';
	
	
	
	
}
?>