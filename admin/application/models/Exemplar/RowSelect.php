<?php
class Exemplar_RowSelect extends Exemplar_Select
{
	
	private $_row;
	
	public function __construct($row)
	{
		$this->_row = $row;
		parent::__construct($row->getTable());
	}
	
	
}
?>