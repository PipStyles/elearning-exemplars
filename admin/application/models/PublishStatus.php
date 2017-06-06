<?php

class PublishStatus extends Model_DbTable_Table
{
	
	protected $_name = 'publish_status';
	protected $_primary = 'publishStatus_id';
	
	protected static $_singular = 'status';
	protected static $_plural = 'statii';
	
	protected static $_showZeroInLists = false;
	
	protected $_dependentTables = array(
		'Exemplar'
	);
	
	protected static $_fields = array(
		'publishStatus_id'=>array(
			'label'=>'id'),
		'publishStatusName'=>array(
			'label' => 'status name', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
	
	protected static $_nameFields = array('publishStatusName');
	protected static $_defaultOrder = 'publishStatus_id';
	
	
}

?>