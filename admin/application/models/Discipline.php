<?php

class Discipline extends Model_DbTable_Table
{
	
	protected $_name = 'discipline';
	protected $_primary = 'discipline_id';
	
	protected $_dependentTables = array(
		'course'
	);
	
	//protected static $_xClasses = array();
	
	protected static $_fields = array(
		'discipline_id'=>array(
			'label'=>'id'),
		'disciplineCode'=>array(
			'label' => 'code', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'disciplineName'=>array(
			'label' => 'name', 
			'roles' => array('title'), 
			'contexts' => array('list', 'form'), 
			'formType' => 'text'),
		'disciplineSchool'=>array(
			'label' => 'school', 
			'roles' => array(), 
			'contexts' => array('list', 'form'), 
			'formType' => 'select',
			'refTableClass' => 'School'
		)
	);
	
	protected static $_nameFields = array('disciplineCode', 'disciplineName');
	protected static $_defaultOrder = 'disciplineCode';
	
	
	
	
	public function fetchAllBySchools($schools = null)
	{
		if(!$schools)
		{
			return $this->fetchAll($this->select()->where("1 = 0"));
		}
		
		if($schools && !is_array($schools))
		{
			$schoolsArr = array($schools);
			$schools = $schoolsArr;
		}
		
		$schoolOb = new School();
		
		$select = $this->select()->setIntegrityCheck(false);
		$select->from($this->info('name'));
		
		
		$schoolField = current($this->getFieldNamesByRefTableClass(get_class($schoolOb)));
		
		$select->joinLeft($schoolOb->info('name'), "`{$schoolOb->info('name')}`.`{$schoolOb->getPrimary()}` = `{$this->info('name')}`.`{$schoolField}`");
		
		foreach($schools as $school)
		{
			$select->orWhere($schoolField.' = ?', $school);
		}
		
		$select->order('disciplineName');
		
		//echo $select;
		
		$rowset = $this->fetchAll($select);
		
		return $rowset;
		
	}
	
	
	
	
}

?>