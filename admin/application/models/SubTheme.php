<?php

class SubTheme extends Model_DbTable_Table
{
	
	protected $_name = 'subtheme';
	protected $_primary = 'subtheme_id';
	
	protected $_dependentTables = array(
		'x_ExemplarSubTheme'
	);
	
	protected static $_xClasses = array(
		'Exemplar' => 'x_ExemplarSubTheme'
	);
	
	public static $xGroupField = 'subthemeTheme';
	
	protected static $_fields = array(
		'subtheme_id'=>array(
			'label'=>'id'),
		'subthemeName'=>array(
			'label' => 'sub-theme', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'subthemeTheme'=>array(
			'label' => 'theme', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select',
			'refTableClass' => 'Theme')
	);
	
	protected static $_nameFields = array('subthemeName');
	protected static $_defaultOrder = 'subthemeTheme';
	
	protected static $_groupField = 'subthemeTheme';
	
	public function byExemplarId($exemplar_id)
	{
		return $this->fetchAll($this->select()->joinedRefs()->byExemplarId($exemplar_id));
	}
	
	
	
}

?>