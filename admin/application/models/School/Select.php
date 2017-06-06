<?php
class School_Select extends Model_DbTable_Select
{
	
	
	public function byExemplarId($exemplar_id)
	{
		$exemplar = $this->_getModel('Exemplar');
		return $this->joinLeft_Exemplar()->where("`{$exemplar->info('name')}`.`{$exemplar->getPrimary()}` = ?", $exemplar_id);
	}
	
	
	public function joinLeft_Exemplar($fieldNames = array())
	{
		$exemplar = new Exemplar();
		$course = new Course();
		$x_exemplarCourse = new x_ExemplarCourse();
		
		//join to course
		$this->joinLeft_Course();
		//join to xTable
		$this->joinLeft($x_exemplarCourse->info('name'), "`{$x_exemplarCourse->info('name')}`.`{$course->getPrimary()}` = `{$course->info('name')}`.`{$course->getPrimary()}`", array());
		//join to exemplar
		$this->joinLeft($exemplar->info('name'), "`{$exemplar->info('name')}`.`{$exemplar->getPrimary()}` = `{$x_exemplarCourse->info('name')}`.`{$exemplar->getPrimary()}`", $fieldNames);
		
		return $this;
	}
	
	
	
	public function joinLeft_Course($fieldNames = array())
	{
		$course = new Course();
		$refFields = $course->getFieldNamesByRefTableClass('School');
		$this->joinLeft($course->info('name'), "`{$course->info('name')}`.`{$refFields[0]}` = `{$this->getTable()->info('name')}`.`{$this->getTable()->getPrimary()}`", $fieldNames);
		
		return $this;
	}
	
	
}
?>