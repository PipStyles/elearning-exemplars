<?php

class UserLevel extends Person
{
	
	protected $_primary = 'userLevel_id';
	protected $_name = 'user_level';
	
	protected static $_singular = 'level';
	
	protected static $_showZeroInLists = false;
	
	protected $_dependentTables = array(
		'User');
	
	protected static $_fields = array(
		'userLevel_id'=>array(
			'label' => 'id', 
			'contexts' => array(),
			'formType' => 'select',
			'fieldHint' => 'Select the person with to link this userLevel account. You will need to have made them first via the person section.'),
		'userLevelName'=>array(
			'label' => 'level name', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('userLevelName');
	protected static $_defaultOrder = 'userLevel_id';
	
}
?>