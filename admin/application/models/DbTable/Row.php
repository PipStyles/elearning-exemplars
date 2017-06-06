<?php

class Model_DbTable_Row extends Zend_Db_Table_Row_Abstract
{
	protected $_ascendent = NULL;
	protected $_descendent = NULL;
	
	
	public function init()
	{
		
	}
	
	
	public function delete()
	{
		//delete any descendent of this row
		$descRow = $this->getDescendent();
		
		//if($descRow) $descRow->delete();
		
		if($this->getTable()->getFieldNamesByRole('deleteFlag'))
		{
		//this model does a fake delete using a flag
			$deleteFlagField = $this->getTable()->getFieldNamesByRole('deleteFlag');
			$this->$deleteFlagField[0] = 1;
			$this->save();
		}
		else
		{
			//delete any xTable entries
			foreach($this->getTable()->getxClasses() as $destclass => $xClass)
			{
				$this->clearManyToMany($destclass);
			}
			
			if($this->getTable()->hasFiles())
			{
				$files = $this->getFiles();
				
				if(count($files))
				{
					$files->deleteAll();
				}
			}	
			
			parent::delete();
		}
		
	}
	
	
	
	public function save()
	{
		parent::save();
	}
	
	
	/*
	this saves mm table records, overwriting whatever is there already
	*/	
	public function saveManyToMany($destClassName, $ids)
	{
		if(!is_array($ids) || !count($ids))
		{
			return $this->clearManyToMany($destClassName);
		}
		
		$xClass = $this->getTable()->getxClassByDestClass($destClassName);
		$xTable = new $xClass();
		
		$clearSelect = $xTable->select();
		
		$x_srcRef = $xTable->getRefsByClassName(get_class($this->getTable()));
		$x_destRef = $xTable->getRefsByClassName($destClassName);
				
		$x_srcIdFieldName = $x_srcRef[get_class($this->getTable())]['columns'];
		$x_destIdFieldName = $x_destRef[$destClassName]['columns'];
		
		$destIds = $ids;
		if(!is_array($ids))
		{
			$destIds[] = $ids;
		}
		
		//clear all existing
		
		
		$this->clearManyToMany($destClassName);
		
		//print_r($destIds);
		
		foreach($destIds as $destId)
		{
			//check for existing - create if not 
			$xRow = $xTable->createRow();
			$xRow->$x_srcIdFieldName = $this->getID();
			$xRow->$x_destIdFieldName = $destId;
			
			//print_r($xRow);
			
			$xRow->save();
		}
		
		return true;
	}
	
	
	/*
	used pre MM save and just before deletion
	*/
	public function clearManyToMany($destClassName)
	{
		$xClass = $this->getTable()->getxClassByDestClass($destClassName);
		$xTable = new $xClass();
		
		$x_srcRef = $xTable->getRefsByClassName(get_class($this->getTable()));
		
		//print_r($x_srcRef);
		
		$x_srcIdFieldName = $x_srcRef[get_class($this->getTable())]['columns'];
		
		
		//clear all existing
		$where = $xTable->getAdapter()->quoteInto($x_srcIdFieldName." = ?", $this->getID());
		$xTable->delete($where);
		
		return true;
	}
	
	
	
	
	
	public function getID()
	{
		$prim = $this->getTable()->getPrimary();
		if(isset($this->$prim) && $this->$prim) return $this->$prim;
		return null;
	}
	
	public function getName()
	{
		return $this->getTitle();
	}
	
	public function getTitle()
	{
		return $this->getTable()->getTitle($this);
	}
	
	
	public function getWritableRow()
	{
		$wRow = $this;
		
		if($this->isReadOnly())
		{
			$wRow = $this->getTable()->fetchRow($this->getTable()->select()->where($this->getTable()->getPrimary()." = ?", $this->getID()));
		}
				
		return $wRow;
	}
	
	
	
	
	
	/*
	Returns an array of Zend Rowsets - one per mm relationship
	*/	
	public function getManyToManyRowsets()
	{
		$out = array();
		
		$xClasses = $this->getTable()->getxClasses();
		
		if(is_array($xClasses))
		{
			foreach($xClasses as $destClass => $xClass)
			{
			$out[$destClass] = $this->getManyToManyRowset($destClass, $xClass);
			}
		}
		
		return $out;
	}
	
	
	
	public function getManyToManyRowset($destClass, $xClass = null, $where = null)
	{
		$destOb = new $destClass();
		
		$select = $destOb->select()->joinLeft_xClasses(get_class($this->getTable()))
		          ->where($this->getTable()->info('name').'.'.$this->getTable()->getPrimary().' = ?', $this->getID());
		
		
		return $destOb->fetchAll($select);
		
		/*
		$srcModel = $this->getTable();
		
		if(!$xClass)
		{
			$xClass = $srcModel->getxClassByDestClass($destClass);
		}
		
		if(!$xClass) return false;
		
		
		$destModel = new $destClass();
		$xModel = new $xClass();
		
		$select = $destModel->select()->title()->notDeleted();
		$select->setIntegrityCheck(false);
		
		$select->joinUsing($xModel->info('name'), $destModel->getPrimary());
		$select->join($srcModel->info('name'), "`{$xModel->info('name')}`.`{$srcModel->getPrimary()}` = `{$srcModel->info('name')}`.`{$srcModel->getPrimary()}` AND `{$srcModel->info('name')}`.`{$srcModel->getPrimary()}` = '{$this->getID()}'");
		
		//$select->where("`{$srcModel->info('name')}`.`{$srcModel->getPrimary()}` = ?", $this->getID());
		
		$out = $destModel->fetchAll($select);
		
		//$out = $this->findManyToManyRowset(new $destClass(), new $xClass());
		
		return $out;*/
	}
	
	
	
	
	/*
	gets rowsets of all other data which refers to this record
	*/
	public function getBackRefRowsets()
	{
		$backRefClasses = $this->getTable()->getDependentTables();
		
		if(!is_array($backRefClasses) || !count($backRefClasses)) return array();
		
		$out = array();		
		foreach($backRefClasses as $brClassName)
		{
			//we don't need to process mm classes - they are a seperate concern
			if(in_array($brClassName, $this->getTable()->getxClasses())) continue;
			
			$brTable = new $brClassName();
			$refsToThisTable = $brTable->getRefsByClassName(get_class($this->getTable()));
			
			if(!count($refsToThisTable)) continue;
			
			foreach($refsToThisTable as $key => $ref)
			{
				$brFieldName = $ref['columns'];
				$brFieldInfo = $brTable->getField($brFieldName);
				
				$brSelect = $brTable->select()->where($brFieldName." = ?", $this->getID());
				
				//echo $brSelect->__toString();
				$brRowset = $brTable->fetchAll($brSelect);
				if(count($brRowset))
				{
				$brArray = array(
										'class' => $brClassName,
										'label' => $brFieldInfo['label'],
										'rowset' => $brRowset
										);
				$out[] = $brArray;
				}
			}
		}
		
		return $out;
	}
	
	
	
	
	public function setDescendent($descendent)
	{
		$this->_descendent = $descendent;
	}
	
	
	public function getDescendent()
	{
		//gets this objects descendent row object(s) using the _descendentRules array
		if($this->getTable()->isDescendent()) return null;
		return $this->_descendent instanceof Model_DbTable_Row ? $this->_descendent : $this->loadDescendent();
	}
	
	
	public function getDescendentTable()
	{
		if($this->getTable()->isDescendent()) return null;
		
		if(!$this->getID())return null;
		
		$rules = $this->getTable()->getDescendentRules();
		if(!is_array($rules) || !count($rules)) return null;
		
		foreach($rules as $className => $rule)
		{
			if($this->$rule['fieldName'] == $rule['value'])
			{
			$descOb = new $className();
			}
		}
		
		return $descOb;
	}
	
	
	protected function loadDescendent()
	{
		if($this->getTable()->isDescendent()) return null;
		
		if(!$this->getID())return null;
		
		$rules = $this->getTable()->getDescendentRules();
		
		if(!is_array($rules) || !count($rules)) return null;
		
		$descOb = $this->getDescendentTable();
		$descRow = $descOb->fetchRow($descOb->select()->joinedRefs()->byPrimary($this->getID()));
		
		if(!$descRow)
		{
			$descRow = $descOb->createRow(array($descOb->getPrimary() => $this->getID()));
			$descRow->save();
		}
		
		return $descRow;
	}
	
	
	
	
	
	
	
	
	
	public function setAscendent($ascendent)
	{
		$this->_ascendent = $ascendent;
	}
	
	
	public function getAscendent()
	{
		if(!$this->getTable()->isDescendent()) return null;
		//gets this objects descendent row object(s) using the _descendentRules array
		if(!isset($this->_ascendent))
		{
		$this->setAscendent($this->loadAscendent());
		}
		
		return $this->_ascendent;	
	}
	
	
	protected function loadAscendent()
	{
		//loads this object ascendent (parent) row object
		$parentClass = $this->getTable()->getAscendentClassName();
		$ascOb = new $parentClass();
		return $ascOb->fetchRow($ascOb->select()->joinedRefs()->byPrimary($this->getID()));
	}
	
	
	public function getDisplayString($fieldName)
	{
		$keyString = $this->getTable()->getFieldAliasPrefix().$fieldName;
		return isset($this->$keyString) ? $this->$keyString : $this->$fieldName;
	}
	
	
	
	public function getImageFolder()
	{
		
	}
	
	
	public function getImage()
	{
		return new RowImage($this);
	}
	
	
	public function getThumb()
	{
		$image = new RowImage($this);
		return $image->getThumb();
	}
	
	
	
	
	
	
	/*
	public function isPrivate()
	{
		$visField = $this->getTable()->getVisField();
		
		if($this->getTable()->canHide() && $this->$visField == 'hide')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	public function isPublic()
	{
		$visField = $this->getTable()->getVisField();
		
		if($this->getTable()->canHide() && $this->$visField == 'show')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	*/
	
	
	/*
	public function getVisIcon()
	{
		return $this->isPrivate() ? '/images/icons/group_key.png' : '/images/icons/group_go.png';
	}
	*/
	
	
	
	
	
	public function hasFiles()
	{
		return $this->getTable()->hasFiles();
	}
	
	public function getFiles()
	{
		if($this->getTable()->hasDescendent() && !$this->getTable()->hasFiles())
		{
			$descRow = $this->getDescendent();
			if($descRow->getTable()->hasFiles())
			{
				return new Files($descRow->getTable()->getFileRoot().$this->getID());
			}
		}
		elseif($this->getTable()->hasFiles())
		{
			return new Files($this->getTable()->getFileRoot().$this->getID());
		}
		return null;
		
	}
	
	public function setFiles($files)
	{
		$this->_files = $files;
	}
	
	protected function loadFiles()
	{
		return new Files($this);
	}
	
	
	
}


?>