<?php

class Person extends Model_DbTable_Table
{
	
	protected $_name = 'person';
	protected $_primary = 'person_id';
	
	protected static $_plural = 'people';
	protected static $_singular = 'person';
	
	protected $_dependentTables = array(
		'User'
	);
	
	protected static $_fields = array(
		'person_id'=>array(
			'label' => 'id', 
			'contexts' => array()),
		'personFirstname'=>array(
			'label' => 'first name', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'), 
		'personLastname'=>array(
			'label' => 'last name', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'personInitials'=>array(
			'label' => 'initials', 
			'roles' => array(), 
			'contexts' => array('form'), 
			'formType' => 'text'),
		'personEmail'=>array(
			'label' => 'email',
			'roles' => array('email'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'personTelephone'=>array(
			'label' => 'telephone',
			'roles' => array(), 
			'contexts' => array('form'), 
			'formType' => 'text'),
		'personRoom'=>array(
			'label' => 'room',
			'roles' => array('location'), 
			'contexts' => array('form'), 
			'formType' => 'select'),
		'personTeam'=>array(
			'label' => 'team',
			'roles' => array(), 
			'contexts' => array('list','form'), 
			'formType' => 'select')	
	);
	
	protected static $_nameFields = array('personFirstname', 'personLastname');
	protected static $_defaultOrder = 'personLastname';
	
	
}

?>