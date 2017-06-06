<?php

class Row_Exemplar extends Model_DbTable_Row
{
	
	
	
	
	public function getCourses()
	{
		$courseOb = new Course();
		
		$rowset = array();
		$rowset = $courseOb->fetchAll($this->getCoursesSelect());
		return $rowset;	
		
		
	}
	
	public function getCoursesSelect()
	{
		$courseOb = new Course();
		
		$select = $courseOb->getJoinedSelect();
		$select->distinct();
		
		$xTableClass = $courseOb->getxClassByDestClass(get_class($this->getTable()));
		$xOb = new $xTableClass();
		
		//join to xCourseExemplar
		
		$select->joinLeft($xOb->getName(),
						 "`{$courseOb->info('name')}`.`{$courseOb->getPrimary()}` = `{$xOb->info('name')}`.`{$courseOb->getPrimary()}` AND `{$xOb->info('name')}`.`{$this->getTable()->getPrimary()}` = '{$this->getID()}'",
						 array($this->getTable()->getPrimary()));
		
		$select->having($this->getTable()->getPrimary().' IS NOT NULL');
				
		return $select;
	}
	
	
	
	
	public function getSchools()
	{
		$schoolOb = new School();
		
		$rowset = array();
		$rowset = $schoolOb->fetchAll($this->getSchoolsSelect());
		return $rowset;
	}
	
	
	public function getSchoolsSelect()
	{
		
		$schoolOb = new School();
		$courseOb = new Course();
		
		$select = $schoolOb->getJoinedSelect();
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
	}
	
	
	
	
	public function getThemes()
	{
		$themeOb = new Theme();
		$rowset = array();
		$rowset = $themeOb->fetchAll($this->getThemesSelect());
		return $rowset;	
	}
	
	
	
	public function getThemesSelect()
	{
		$themeOb = new Theme();
		$subthemeOb = new SubTheme();
		
		$select = $themeOb->getJoinedSelect();
		$select->distinct();
		
		//join from theme to subtheme
		$subthemeThemeFieldArray = $subthemeOb->getRefsByClassName(get_class($themeOb));
		$subthemeThemeField = $subthemeThemeFieldArray[get_class($themeOb)]['columns'];
		
		$select->joinLeft($subthemeOb->getName(), 
						"`{$themeOb->getName()}`.`{$themeOb->getPrimary()}` = `{$subthemeOb->getName()}`.`{$subthemeThemeField}`" ,
						array());
		
		$xTableClass = $subthemeOb->getxClassByDestClass(get_class($this->getTable()));
		$xOb = new $xTableClass();
		
		//join to xCourseExemplar
		$select->joinLeft($xOb->getName(),
						 "`{$subthemeOb->getName()}`.`{$subthemeOb->getPrimary()}` = `{$xOb->getName()}`.`{$subthemeOb->getPrimary()}`
						 AND `{$xOb->getName()}`.`{$this->getTable()->getPrimary()}` = '{$this->getID()}'",
						 array($this->getTable()->getPrimary()));
		
		$select->having("`{$this->getTable()->getPrimary()}` IS NOT NULL");
		
		return $select;
	}
	
	
	
	
	public function getServiceAreas()
	{
		$serviceAreaOb = new ServiceArea();
		$rowset = array();
		$rowset = $serviceAreaOb->fetchAll($this->getServiceAreasSelect());
		return $rowset;	
	}
	
	
	
	public function getServiceAreasSelect()
	{
		$serviceAreaOb = new ServiceArea();
		$serviceOb = new Service();
		
		$select = $serviceAreaOb->getJoinedSelect();
		$select->distinct();
		
		//join from theme to subtheme
		$serviceServiceAreaFieldArray = $serviceOb->getRefsByClassName(get_class($serviceAreaOb));
		$serviceServiceAreaField = $serviceServiceAreaFieldArray[get_class($serviceAreaOb)]['columns'];
				
		$select->joinLeft($serviceOb->getName(), 
						"`{$serviceAreaOb->getName()}`.`{$serviceAreaOb->getPrimary()}` = `{$serviceOb->getName()}`.`{$serviceServiceAreaField}`" ,
						array());
		
		$xTableClass = $serviceOb->getxClassByDestClass(get_class($this->getTable()));
		$xOb = new $xTableClass();
		
		//join to xCourseExemplar
		$select->joinLeft($xOb->getName(),
						 "`{$serviceOb->getName()}`.`{$serviceOb->getPrimary()}` = `{$xOb->getName()}`.`{$serviceOb->getPrimary()}`
						 AND `{$xOb->getName()}`.`{$this->getTable()->getPrimary()}` = '{$this->getID()}'",
						 array($this->getTable()->getPrimary()));
		
		$select->having("`{$this->getTable()->getPrimary()}` IS NOT NULL");
				
		return $select;
	}
	
	
	
	
	
	
	public function getServices()
	{
		$serviceOb = new Service();
				
		$select = $this->getTable()->getServiceSelect();
		
		$select->where("`{$this->getTable()->getName()}`.`{$this->getTable()->getPrimary()}` = ?", $this->getID());
		
		//echo $select;
		
		$rowset = $serviceOb->fetchAll($select);
		
	//	print_r($rowset);
		
		return $rowset;
	}
	
	
	
	
	
	public function getArtefacts()
	{
		$artefactOb = new Artefact();
		$rowset = array();
		$rowset = $artefactOb->fetchAll($this->getArtefactsSelect());
		return $rowset;	
	}
	
	
	public function getArtefactsSelect()
	{
		$artefactOb = new Artefact();
		
		$select = $artefactOb->getJoinedSelect();
		
		$xClass = $artefactOb->getxClassByDestClass(get_class($this->getTable()));
		
		$xOb = new $xClass();
		
		$select->joinLeft($xOb->getName(),
						 "`{$artefactOb->getName()}`.`{$artefactOb->getPrimary()}` = `{$xOb->getName()}`.`{$artefactOb->getPrimary()}`
						 AND `{$xOb->getName()}`.`{$this->getTable()->getPrimary()}` = '{$this->getID()}'",
						 array($this->getTable()->getPrimary()));
		
		$select->having("`{$this->getTable()->getPrimary()}` IS NOT NULL");
		
		return $select;
		
	}
	
	
	
	
	
	
	
	
}

?>