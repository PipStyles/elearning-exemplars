<?php

class Service_Select extends Model_DbTable_Select
{
	
	public function byExemplarId($exemplar_id)
	{
		$exemplar = $this->_getModel('Exemplar');
		return $this->joinLeft_Exemplar()->where("`{$exemplar->info('name')}`.`{$exemplar->getPrimary()}` = ?", $exemplar_id);
	}
	
	public function joinLeft_Exemplar()
	{
		return $this->joinLeft_xClasses('Exemplar');
	}
	
	
	
}
?>