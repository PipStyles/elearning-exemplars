<?php

class Theme extends Model_DbTable_Table
{
	
	protected $_name = 'theme';
	protected $_primary = 'theme_id';
	
	protected $_dependentTables = array(
		'SubTheme'
	);
	
	//protected static $_xClasses = array();
	
	protected static $_fields = array(
		'theme_id'=>array(
			'label'=>'id'),
		'themeName'=>array(
			'label' => 'theme name', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'themeOrder'=>array(
			'label' => 'display order', 
			'roles' => array('order'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('themeName');
	protected static $_defaultOrder = 'themeOrder';
	
	public function byExemplarId($exemplar_id)
	{
		return $this->fetchAll($this->select()->byExemplarId($exemplar_id));
	}
	
}

?>