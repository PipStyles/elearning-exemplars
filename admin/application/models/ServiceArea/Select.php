<?php

class ServiceArea_Select extends Model_DbTable_Select
{
	
	public function byExemplarId($exemplar_id)
	{
		$exemplar = $this->_getModel('Exemplar');
		return $this->joinLeft_Exemplar()->where("`{$exemplar->info('name')}`.`{$exemplar->getPrimary()}` = ?", $exemplar_id);
	}
	
	public function joinLeft_Service($fieldNames = array())
	{
		$service = $this->_getModel('Service');
		$refFields = $service->getFieldNamesByRefTableClass('ServiceArea');
		return $this->joinLeft($service->info('name'), "`{$service->info('name')}`.`{$refFields[0]}` = `{$this->getTable()->info('name')}`.`{$this->getTable()->getPrimary()}`", $fieldNames);
	}	
	
	public function joinLeft_Exemplar($fieldNames = array())
	{
		return $this->joinLeft_Service()->joinLeft_xClasses('Exemplar', $fieldNames);
	}
	
	
}

?>