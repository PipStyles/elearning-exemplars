<?php

class Theme_Select extends Model_DbTable_Select
{
	
	public function byExemplarId($exemplar_id)
	{
		$exemplar = $this->_getModel('Exemplar');
		return $this->distinct()->joinLeft_Exemplar()->where("`{$exemplar->info('name')}`.`{$exemplar->getPrimary()}` = ?", $exemplar_id);
	}
	
	public function joinLeft_Exemplar($fieldNames = array())
	{
		$this->joinLeft_Subtheme();
		$subtheme = $this->_getModel('Subtheme');
		$xClass = $subtheme->getxClassByDestClass('Exemplar');
		$xOb = new $xClass();
		//join to x table
		$this->joinLeft($xOb->info('name'),"`{$xOb->info('name')}`.`{$subtheme->getPrimary()}` = `{$subtheme->info('name')}`.`{$subtheme->getPrimary()}`", array());
		//join to exemplar
		$exemplar = $this->_getModel('Exemplar');
		$this->joinLeft($exemplar->info('name'), "`{$exemplar->info('name')}`.`{$exemplar->getPrimary()}` = `{$xOb->info('name')}`.`{$exemplar->getPrimary()}`", $fieldNames);
		return $this;
	}
	
	public function joinLeft_Subtheme($fieldNames = array())
	{
		$subtheme = $this->_getModel('SubTheme');
		$refFields = $subtheme->getFieldNamesByRefTableClass(get_class($this->getTable()));
		return $this->joinLeft($subtheme->info('name'), "`{$subtheme->info('name')}`.`{$refFields[0]}` = `{$this->getTable()->info('name')}`.`{$this->getTable()->getPrimary()}`", $fieldNames);
	}
	
	
	
}
?>