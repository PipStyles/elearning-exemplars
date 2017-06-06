<?php

class Exemplar_Row extends Model_DbTable_Row
{
	
	
	public function select()
	{
		return new Exemplar_SelectRow($this);
	}
	
	
	
	
	public function getCourses()
	{
		$courseOb = new Course();
		return $courseOb->byExemplarId($this->getID());;
	}
	
	public function getSchools()
	{
		$schoolOb = new School();
		return $schoolOb->byExemplarId($this->getID());
	}
	
	
	public function getThemes()
	{
		$theme = new Theme();
		return $theme->byExemplarId($this->getID());
	}
	
	public function getServiceAreas()
	{
		$serviceArea = new ServiceArea();
		return $serviceArea->byExemplarId($this->getID());
	}
	
	public function getServices()
	{
		$service = new Service();
		return $service->byExemplarId($this->getID());
	}
	
	public function getArtefacts()
	{
		$artefact = new Artefact();
		return $artefact->byExemplarId($this->getID());
	}
	
	
	
}

?>