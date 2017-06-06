<?php

class Service_Row extends Model_DbTable_Row
{
	
	public function getExemplars()
	{
		$exemplar = new Exemplar();
		return $exemplar->byServiceId($this->getID());
	}
	
	
}

?>