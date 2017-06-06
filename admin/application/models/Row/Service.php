<?php

class Row_Service extends Model_DbTable_Row
{
	
	
	public function getExemplars()
	{
		$exemplarOb = new Exemplar();
		$select = $this->getTable()->getExemplarSelect();
		
		$select->where($this->getTable()->getName().'.'.$this->getTable()->getPrimary().'= ?', $this->getID());
		
		$pubSelect = $exemplarOb->getPublicSelect($select);
		
		$exemplars = $exemplarOb->fetchAll($pubSelect);
		
		return $exemplars;
		
	}
	
	
	
}

?>