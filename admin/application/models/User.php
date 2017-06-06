<?php

class User extends Model_DbTable_Table
{
	protected $_rowClass = 'User_Row';
	
	protected $_primary = 'user_id';
	protected $_name = 'user';
	
	protected static $_singular = 'user';
	
	//protected static $_isDescendent = true;
	
	protected static $_fields = array(
		'user_id'=>array(
			'label' => 'person', 
			'contexts' => array('list', 'form'),
			'refTableClass' => 'Person',
			'formType' => 'select',
			'fieldHint' => 'Select the person with to link this user account. You will need to have made them first via the person section.'),
		'username'=>array(
			'label' => 'username', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text',
			'fieldHint' => 'This user\'s Central-IT account username.'),
		'userLevel'=>array(
			'label' => 'level', 
			'roles' => array('permission'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select',
			'refTableClass' => 'UserLevel')
	);
	
	protected static $_nameFields = array('username');
	protected static $_defaultOrder = 'username';
	
}
?>