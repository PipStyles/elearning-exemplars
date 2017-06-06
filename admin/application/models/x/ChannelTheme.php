<?php

class x_ChannelTheme extends Model_DbTable_Table
{
	
	protected $_name = 'x_channel_theme';
	
	protected static $_fields = array(
		'theme_id'=>array(
			'label' => 'theme id',
			'roles' => array('x_id'),
			'refTableClass' => 'Theme'),
		'channel_id'=>array(
			'label' => 'channel id',
			'roles' => array('x_id'),
			'refTableClass' => 'Channel')
	);
		
}

?>