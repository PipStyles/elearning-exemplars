<?php

class AuthAdapter extends Zend_Auth_Adapter_DbTable
	{
	
	function __construct(Zend_Db_Adapter_Abstract $zendDb, $tableName = 'user', $identityColumn = 'username')
		{
		parent::__construct($zendDb, $tableName, $identityColumn);
		
		}
	
	
	}

?>