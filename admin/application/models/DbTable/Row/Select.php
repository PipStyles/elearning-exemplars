<?php
class Model_DbTable_Row_Select extends Model_DbTable_Select
{
	
	public function xRowset($destClass)
	{
				
		return parent::joinLeft_xClasses($destClass);
	}
	
	
}
?>