<?php

class Exemplar_Select extends Model_DbTable_Select
{
	/*
	query builder class for Exemplar. Uses same pattern of returning $this to allow chaining.
	*/
	
	const DEFAULT_LATEST_NUM = 10;
	
	
	public function published()
	{
		static $CALLED = false;
		if(!$CALLED)
		  {
			  $CALLED = true;
				$this->where("exemplarPublishStatus = ?", Exemplar::PUBLIC_PUBLISH_LEVEL)->notDeleted();
			}
		return $this;
	}
	
	
	public function latest($num = self::DEFAULT_LATEST_NUM)
	{
		return $this->limit($num)->order("{$this->getTable()->info('name')}.{$this->getTable()->getPrimary()} DESC");
	}
	
	
	
	public function byServiceId($service_id)
	{
		$service = $this->_getModel('Service');
		return $this->joinLeft_Service()->where("`{$service->info('name')}`.`{$service->getPrimary()}` = ?", $service_id);
	}
	
	public function byChannelNames($names)
	{
		$names = is_array($names) ? $ids : explode(',', $names);
		$channel = new Channel();
		$titleFields = $channel->getFieldNamesByRole('title');
		
		return $this->joinLeft_Channel(array())->where($channel->info('name').'.'.current($titleFields).' IN(?)', $names);
	}
	
	
	public function byChannelIds($ids)
	{
		$ids = is_array($ids) ? $ids : explode(',', $ids);
		$channel = new Channel();
		return $this->joinLeft_xClasses(get_class($channel))->where("{$channel->info('name')}.{$channel->getPrimary()} IN(?)", $ids);
	}
	
	
	
	
	public function refined($get, $matchAny = false)
	{
	  //get a select object with all GET parameters from search integrated into select object
		$this->distinct()->setIntegrityCheck(false);
		
		$numFilters = @((intval($get['school']) > 0) + (intval($get['discipline']) > 0) + (intval($get['theme']) > 0) + (intval($get['serviceArea']) > 0) + (strlen($get['text']) > 0));
				
		$whereMethod = $matchAny && $numFilters > 1 ? 'orWhere' : 'where';
		$whereJoiner = $matchAny && $numFilters > 1? 'OR' : 'AND';
		
		$textWheres = $wheres = array();
		
		//school search
		if(isset($get['school']) && intval($get['school']) > 0)
		{
			
			$school = new School();
			$this->joinLeft_School();
			//$this->$whereMethod("`{$school->info('name')}`.`{$school->getPrimary()}` = ?", $get['school']);
			$wheres[] = "`{$school->info('name')}`.`{$school->getPrimary()}` = ".$this->getTable()->getAdapter()->quote($get['school']);
			
			//search on discipline - only if school is already being searched!
			if(isset($get['discipline']) && intval($get['discipline']) > 0)
		    {
				$discipline = new Discipline();
				$this->joinLeft_Discipline();
				//$this->$whereMethod("`{$discipline->info('name')}`.`{$discipline->getPrimary()}` = ?", $get['discipline']);
		    
				$wheres[] = "`{$discipline->info('name')}`.`{$discipline->getPrimary()}` = ".$this->getTable()->getAdapter()->quote($get['discipline']);
				}
		}
		
		//theme search
		if(isset($get['theme']) && intval($get['theme']) > 0)
		{
			$theme = new Theme();
			$this->joinLeft_Theme();
			//$this->$whereMethod("`{$theme->info('name')}`.`{$theme->getPrimary()}` = ?", $get['theme']);
			
			$wheres[] = "`{$theme->info('name')}`.`{$theme->getPrimary()}` = ".$this->getTable()->getAdapter()->quote($get['theme']);
				
		}
		
		//serviceArea search
		if(isset($get['serviceArea']) && intval($get['serviceArea']) > 0)
		{
			$serviceArea = new ServiceArea();
			$this->joinLeft_ServiceArea();
			//$this->$whereMethod("`{$serviceArea->info('name')}`.`{$serviceArea->getPrimary()}` = ?", $get['serviceArea']);
			$wheres[] = "`{$serviceArea->info('name')}`.`{$serviceArea->getPrimary()}` = ".$this->getTable()->getAdapter()->quote($get['serviceArea']);
			
		}
		
		//text search
		if(isset($get['text']) && strlen($get['text']))
		{
		  $searchableTextFields = $this->getTable()->getFieldNamesByRole('textSearch');
		  foreach($searchableTextFields as $field)
		  {
				$textWheres[] = "`{$this->getTable()->info('name')}`.`{$field}` LIKE ".$this->getTable()->getAdapter()->quote('%'.trim($get['text'].'%'));
		  }
		}
		
		$whereStrAll = "";
		if($numFilters > 0)
		{
		 if(count($wheres) > 0)
		 {
			 $whereStr = "(".implode(' '.$whereJoiner.' ', $wheres).")";
		 }
		 if(count($textWheres) > 0)
		 {
			$textWhereStr = "(".implode(' OR ', $textWheres).")";
		 }
		 
		 if(count($wheres) > 0 && count($textWheres) > 0)
		 {
			 $whereStrAll = "$whereStr $whereJoiner $textWhereStr";
		 }
		 elseif(count($wheres) > 0)
		 {
			 $whereStrAll = $whereStr;
		 }
		 else
		 {
			 $whereStrAll = $textWhereStr;
		 }
		 $this->where($whereStrAll)->group($this->getTable()->getPrimary());	
		}
		
		return $this;
	}
	
	
	
	
	
	//join functions	
	public function joinLeft_Course($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
			$CALLED = true;
		  $this->joinLeft_xClasses('Course', $fieldNames);
		}
		return $this;
	}	
	
	public function joinLeft_Discipline($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
			$CALLED = true;
		  
			$this->joinLeft_Course();
			$course = new Course();
			$discipline = new Discipline();
			$disciplineFields = $course->getFieldNamesByRefTableClass('Discipline');
			$this->joinLeft($discipline->info('name'), "`{$discipline->info('name')}`.`{$discipline->getPrimary()}` = `{$course->info('name')}`.`{$disciplineFields[0]}`", $fieldNames);
		}
		return $this;
	}
	
	public function joinLeft_School($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
			$CALLED = true;
		  $this->joinLeft_Course();
		  $course = new Course();
		  $school = new School();
		  $schoolFields = $course->getFieldNamesByRefTableClass('School');
		  $this->joinLeft($school->getName(), "`{$school->getName()}`.`{$school->getPrimary()}` = `{$course->info('name')}`.`{$schoolFields[0]}`", $fieldNames);
		}
	  return $this;
	}
	
	public function joinLeft_Theme($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
			$CALLED = true;
			$this->joinLeft_xClassParent('Theme', 'SubTheme');
		}
		return $this;
	}
	
	public function joinLeft_SubTheme($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
		  $CALLED = true;
			$this->joinLeft_xClasses('SubTheme', $fieldNames);
		}
		return $this;
	}
	
	public function joinLeft_ServiceArea($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
		  $CALLED = true;
			$this->joinLeft_xClassParent('ServiceArea', 'Service', $fieldNames);
		}
		return $this;
	}
	
	public function joinLeft_Service($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
		  $CALLED = true;
			$this->joinLeft_xClasses('Service', $fieldNames);
		}
		return $this;
	}
	
	public function joinLeft_Tool($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
		  $CALLED = true;
			$this->joinLeft_xClasses('Tool', $fieldNames);
		}
		return $this;
	}
	
	public function joinLeft_Channel($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
		  $CALLED = true;
			$this->joinLeft_xClasses('Channel', $fieldNames);
		}
		return $this;
	}
	
	public function joinLeft_Fund($fieldNames = array())
	{
		static $CALLED = false;
		if(!$CALLED)
		{
		  $CALLED = true;
			$this->joinLeft_xClasses('Fund', $fieldNames);
		}
		return $this;
	}
	
	
}
?>