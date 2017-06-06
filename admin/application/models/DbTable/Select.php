<?php

class Model_DbTable_Select extends Zend_Db_Table_Select
{
	
	const JOIN_TABLE_PREFIX = 'TBL_';
	const DEPENDENT_JOIN_PREFIX = 'DEP_';
	const DISPLAY_REF_ALIAS_PREFIX = 'DISP_';
	
	protected $_models = array();
	
	public function __construct($table)
	{
		parent::__construct($table);
		$this->from($this->getTable());
	}
	
	
	protected function _getModel($modelName)
	{
		if(class_exists($modelName))
		{
			if(!isset($this->_models[$modelName]) || $this->_models[$modelName] instanceof $modelName)
			{
				$this->_models[$modelName] = new $modelName();
			}
			return $this->_models[$modelName];
		}
		else
		{
			return false;
		}
	}
	
	
	
	
	public function title()
	{
		//this returns a select object with any tables/fields required by getTitle() joined on
		$titleFields = $this->getTable()->getFieldsByRole('title');
		if(!@count($titleFields)) return $this;
		
		
		foreach($titleFields as $fieldName => $field)
		{
			if(isset($field['refTableClass']))
			{
				$this->setIntegrityCheck(false);
			  $joinModel = new $field['refTableClass'];
			  $refTableFields = is_array($field['refTableTitleFields']) ? $field['refTableTitleFields'] : array();
				
			  $this->joinLeft($joinModel->info('name'), "`{$joinModel->info('name')}`.`{$joinModel->getPrimary()}` = `{$this->info('name')}`.`{$fieldName}`", $refTableFields);
			}
			
		}
		
		return $this;
		
	}
	
	
	public function byPrimary($id)
	{
		return $this->where($this->getTable()->info('name').'.'.$this->getTable()->getPrimary().'= ?', $id);
	}
	
	
	
	/*
	returns rows not flagged as deleted (finds field by querying the fields array)
	*/
	public function notDeleted()
	{
		$deleteFlagFields = $this->getTable()->getFieldNamesByRole('deleteFlag');
		if(is_array($deleteFlagFields) && count($deleteFlagFields))
		{
			foreach($deleteFlagFields as $df)
			{
				$this->where("`{$this->getTable()->info('name')}`.`{$df}` != ?", 1);
			}
		}
		return $this;
	}
	
	
	public function joinedRefs()
	{
		$this->setIntegrityCheck(false);
		$this->columns(array('*', 'primary_id' => $this->getTable()->getPrimary()));
		
		foreach($this->getTable()->info('referenceMap') as $key => $ref)
		{
			$joinTable = new $ref['refTableClass'];
			$joinTable_primary = $joinTable->getPrimary();
			$joinTableAlias = self::JOIN_TABLE_PREFIX.$ref['columns'];
			
			$joinFieldsSQL = "";
			$joinFieldsSQLParts = array();
			
			$refColumn = $ref['columns'];
			
			if(is_array($joinTable->getNameFields()) && count($joinTable->getNameFields()) > 1)
			{
				foreach($joinTable->getNameFields() as $fieldName)
				{
					$joinFieldsSQLParts[] = "`".$joinTableAlias."`.`".$fieldName."`";
				}
			$joinFieldsSQL = "CONCAT(".implode(",' ',", $joinFieldsSQLParts).")";
			}
			elseif(is_array($joinTable->getNameFields()) && count($joinTable->getNameFields()) == 1)
			{
				$joinTableNameFields = $joinTable->getNameFields();
				$joinFieldsSQL = $joinTableNameFields[0];
			}		
			else
			{
				$joinFieldsSQL = $joinTable->getNameFields();
			}
			
			$this->joinLeft(array($joinTableAlias => $joinTable->info('name')), "`{$joinTableAlias}`.`{$joinTable_primary}` = `{$this->getTable()->info('name')}`.`{$refColumn}`",  $joinFieldsSQL." AS ".self::DISPLAY_REF_ALIAS_PREFIX.$ref['columns']);
			
			}
		return $this;
	}
	
	
	public function joinLeft_Dependent($className, $fieldNames = array())
	{
		$this->setIntegrityCheck(false);
		if(!class_exists($className))
		{
			return $this;
		}
		
		$joinOb = new $className();
		$joinField = $joinOb->getFieldNamesByRefClass(get_class($this->getTable()));
		if(is_array($joinField) && count($joinField))
		{
			$joinField = current($joinField);
			$this->joinLeft($joinOb->info('name'), "`{$joinOb->info('name')}`.`{$this->getTable()->getPrimary()}` = `{$joinOb->info('name')}`.`{$joinField}`", $fieldNames);
		}
		
		return $this;
	}
	
	public function joinLeft_Dependents($classNames)
	{
		if(is_string($classNames))
		{
			$classNames = implode(',', $classNames);
		}
		
		foreach($classNames as $cn)
		{
			$this->joinLeft_Dependent($cn);
		}
		return $this;
	}
	
	
	
	
		
	/*
	uses the xClasses defined in the model to join to the destination classes
	*/	
	public function joinLeft_xClasses($classNames, $fieldNames = array())
	{
		
		if(is_string($classNames))
		{
			$classNames = array($classNames);
		}
		
		foreach($classNames as $key => $destClass)
		{
			//optional use of alias names in array?
		  $xClasses = $this->getTable()->getxClasses();
			if( isset($xClasses[$destClass]) && class_exists($xClasses[$destClass]) && class_exists($destClass) )
		  {
				$this->setIntegrityCheck(false);
				$xModel = new $xClasses[$destClass];
				$destModel = new $destClass();
				$destJoinAlias = is_string($key) ? array($key => $destModel->info('name')) : $destModel->info('name');
				
				//join the xTable
				$this->joinLeft($xModel->info('name'), "`{$this->getTable()->info('name')}`.`{$this->getTable()->getPrimary()}` = `{$xModel->info('name')}`.`{$this->getTable()->getPrimary()}`", array());
				//join the dest table
				$this->joinLeft($destJoinAlias, "`{$destJoinAlias}`.`{$destModel->getPrimary()}` = `{$xModel->info('name')}`.`{$destModel->getPrimary()}`", $fieldNames);
				
				}
		}
		return $this;
	}
	
	
	public function joinLeft_xClassParent($parentClass, $childClass, $parentFieldNames = array())
	{
		static $ALIASES = array();
		
		if(is_array($parentClass))
		{
			$joinClass = current($parentClass);
			$joinAlias = key($parentClass);
		}
		else
		{
			$joinClass = $parentClass;
		}
		
		$parentOb = new $joinClass();
		$joinTable = $parentOb->info('name');
		
		if(!is_array($parentClass))
		{
			$joinAlias = $joinTable;
		}
		
		//only allows one use of alias to be joined
		if(!in_array($joinAlias, $ALIASES))
		{
			$this->setIntegrityCheck(false);
		  $this->joinLeft_xClasses($childClass);
		  
			$childOb = new $childClass();
			$childFields = $childOb->getFieldNamesByRefTableClass(get_class($parentOb));
		  
			$this->joinLeft(is_array($parentClass) ? $parentClass : $joinTable, "`{$joinAlias}`.`{$parentOb->getPrimary()}` = `{$childOb->info('name')}`.`{$childFields[0]}`", $parentFieldNames);
		  
			$ALIASES[] = $joinAlias;
		}
		
		return $this;
	}
	
	
	/*
	tries to left join a given class name (or classname of given object) to the current select, using alias TBL_$localFieldName to avoid collisions
	*/
	public function joinLeft_refClass($refClass, $refFieldName, $fieldNames = array())
	{
		if(is_object($refClass))
		{
			$refClass = get_class($refClass);
		}
		
		if(!class_exists($refClass))
		{
			return $this;
		}
		
		$refOb = new $refClass();
		$refName = $alias ? array($alias => $refClass) : $refClass;
		$joinAlias = self::JOIN_TABLE_PREFIX.$refFieldName;
		$this->setIntegrityCheck(false);
		$this->joinLeft(array($joinAlias => $refOb->info('name')), "`{$joinAlias}`.`{$refOb->getPrimary()}` = `{$this->getTable()->info('name')}`.`{$refFieldName}`", $fieldNames);
		return $this;
	}
		
	
}
?>