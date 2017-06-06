<?php

class Fund extends Model_DbTable_Table
{
	
	protected $_name = 'fund';
	protected $_primary = 'fund_id';
	
  protected static $_nameFields = array('fundName');
	
	protected static $_fields = array(
		'fund_id'=>array(
			'label'=>'id'),
		'fundName'=>array(
			'label' => 'fund name', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'fundCode'=>array(
			'label' => 'fund short code', 
			'roles' => array(),
			'contexts' => array('list', 'form'), 
			'formType' => 'text',
			'fieldHint' => 'A short acronym, useful to shorten readable references to it, e.g TLEF, TESS')
	);
  
	protected static $_xClasses = array(
		'Exemplar' => 'x_ExemplarFund'
	);
	
	protected $_dependentTables = array(
		'x_ExemplarFund',
	);
	
	
	
}	

?>