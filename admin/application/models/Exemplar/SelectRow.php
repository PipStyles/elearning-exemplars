<?php
class Exemplar_SelectRow extends Exemplar_Select
{
	
	private $_row;
	
	public function __construct($row)
	{
		$this->_row = $row;
		parent::__construct($row->getTable());
	}
	
	
	public function schools()
	{
		$schoolOb = new School();
		
		return $schoolOb->getSchoolsByExemplarId($this->_row->getID());
		
		/*
		$courseOb = new Course();
		
		$select = $schoolOb->select()->joinedRefs();
		$select->distinct();
		
		//join from school to course
		$courseSchoolFieldArray = $courseOb->getRefsByClassName(get_class($schoolOb));
		$courseSchoolField = $courseSchoolFieldArray[get_class($schoolOb)]['columns'];
		
		$select->joinLeft($courseOb->getName(), 
						"`{$schoolOb->getName()}`.`{$schoolOb->getPrimary()}` = `{$courseOb->getName()}`.`{$courseSchoolField}`" ,
						array());
		
		$xTableClass = $courseOb->getxClassByDestClass(get_class($this->getTable()));
		$xOb = new $xTableClass();
		
		//join to xCourseExemplar
		$select->joinLeft($xOb->getName(),
						 "`{$courseOb->getName()}`.`{$courseOb->getPrimary()}` = `{$xOb->getName()}`.`{$courseOb->getPrimary()}`
						 AND `{$xOb->getName()}`.`{$this->getTable()->getPrimary()}` = '{$this->getID()}'",
						 array($this->getTable()->getPrimary()));
		
		$select->having($this->getTable()->getPrimary().' IS NOT NULL');
		
		return $select;
		*/
				
	}
	
	
	
}
?>