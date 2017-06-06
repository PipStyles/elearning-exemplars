<?php

class Channel extends Model_DbTable_Table
{
	
	protected $_name = 'channel';
	protected $_primary = 'channel_id';
	
  protected static $_nameFields = array('channelName');
	
	protected static $_fields = array(
		'channel_id'=>array(
			'label'=>'id'),
		'channelName'=>array(
			'label' => 'channel name', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text')
	);
  
	protected static $_xClasses = array(
		'Exemplar' => 'x_ExemplarChannel',
		'Theme' => 'x_ChannelTheme',
	);
	
	protected $_dependentTables = array(
		'x_ExemplarChannel',
		'x_ChannelTheme'
	);
	
	
	
}	

?>