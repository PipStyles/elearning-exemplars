<?php

abstract class Model_DbTable_Table extends Zend_Db_Table_Abstract
{
	const FIELD_ALIAS_PREFIX = 'DISP_';
	
	//consider moving to somewhere better!
	const PUBLIC_PUBLISH_LEVEL = 3;
	
	//protected $PUBLIC_PUBLISH_LEVEL = 3;
	
	protected $_select;
	
	protected static $_fields = NULL;
	protected static $_xClasses = NULL;
	protected static $_derivationMap = NULL;
	
	protected $fields;
	
	protected $_rowClass = 'Model_DbTable_Row';
	
	protected static $_isDescendent = false;
	protected static $_descendentRules = null;
	
	protected static $_hasDescendent = false;
	
	protected static $_singular;
	protected static $_plural;
	
	protected static $_zeroTerm = 'unspecified';
	protected static $_showZeroInLists = true;
	
	protected static $_defaultOrder;
	protected static $_defaultOrderDirection = 'ASC';
	
	protected static $_groupField;
	
	public static $_hasFiles = false;
	public static $_hasImage = false;
	
	protected static $_canHide = false;	
	protected static $_canListAll = true;
	
	protected static $_visibilityField = 'showpublic';
	
	protected $_listFromRelField = false;
	
	
	public function init()
	{
		$this->className = get_class($this);
		$this->fields = $this->_buildFieldsArray(self::s_getFields(get_class($this)), $this->info());
		$this->setReferences($this->_buildReferenceMap());
		$this->setDependentTables($this->_getMergedxClassesAndDependentTables());
	}
	
	
	public function select()
	{
		$prefClassName = get_class($this).'_Select';
		if(class_exists($prefClassName, false))
		{
			return new $prefClassName($this);
		}
		$prefFileName = __DIR__.'/../'.get_class($this).'/Select.php';
		$ret =  file_exists($prefFileName) ? new $prefClassName($this) : new Model_DbTable_Select($this);
		return $ret;
	}

	
	public function getName()
	{
		return $this->_name;
	}
	
	
	public static function getStatic($class, $property)
	{
		@eval("\$isset = isset($class::\$$property);");
		
		if($isset)
		{
			@eval("\$out = $class::\$$property;");
		}
		else
		{
			$out = null;
		}
		
		return $out;
	}
	
	public function getPrimary()
	{
		$primary = $this->info('primary');	
		return count($primary) == 1 ? $primary[1] : $primary;
	}
	
	public static function s_getPrimary($class)
	{
		return self::getStatic($class, '_primary');
	}
	
	public function getTitle($row)
	{
		$nameFields = $this->getNameFields();
		$nameData = array();
		
		$name = "";
		
		if($this->isDescendent())
		{
			$ascRow = $row->getAscendent();
			return $ascRow->getTitle();
		}		
		elseif(is_array($nameFields))
		{
			foreach($nameFields as $field)
			{
				if(isset($row->$field)) $nameData[] = $row->$field;
			}
			$name = implode(" ", $nameData);
		}
		elseif(isset($row->$nameFields) && is_string($nameFields))
		{
			$name = $row->$nameFields;
		}
		elseif($this->getTable()->isDescendent())
		{
			$name = $row->getAscendent()->getTitle();
		}
		
		return $name;
	
	}
	
	
	public function getZeroTerm()
	{
		return self::s_getZeroTerm(get_class($this));
	}
	
	public static function s_getZeroTerm($class)
	{
		if(@self::getStatic($class, '_zeroTerm'))
		{
			$term = self::getStatic($class, '_zeroTerm');
		}
		else
		{
			$term = 'unspecified';
		}
		
		return $term;
	}
	
	
	public function getShowZeroInLists()
	{
		return self::s_getShowZeroInLists(get_class($this));
	}
	
	public static function s_getShowZeroInLists($class)
	{
		return self::getStatic($class, '_showZeroInLists');
	}
	
	
	
	
	
	public function getPlural()
	{
		return self::s_getPlural(get_class($this));
	}
	
	public static function s_getPlural($class)
	{
		if(@self::getStatic($class, '_plural'))
		{
			$term = self::getStatic($class, '_plural');
		}
		else
		{
			$term = self::s_getSingular($class).'s';
		}
		
		return $term;
	}
	
	
	public function getSingular()
	{
		return self::s_getSingular(get_class($this));
	}
	
	public static function s_getSingular($class)
	{
		if(@self::getStatic($class, '_singular'))
		{
			$term = self::getStatic($class, '_singular');
		}
		else
		{
			$term = strtolower($class);
		}
		
		return $term;
	}
	
	
	
	
	public function getDefaultOrder()
	{
		return self::s_getDefaultOrder(get_class($this));
	}
	
	
	public static function s_getDefaultOrder($class)
	{
		$order = self::getStatic($class, '_defaultOrder');
		return $order ? $order : self::s_getPrimary($class);
	}
	
	public function getDefaultOrderDirection()
	{
		return self::s_getDefaultOrderDirection(get_class($this));
	}
	
	public static function s_getDefaultOrderDirection($class)
	{
		$direction = self::getStatic($class, '_defaultOrderDirection');
		return $direction ? $direction : '';
	}
	
	
	
	
	
	public function getGroupField()
	{
		return self::s_getGroupField(get_class($this));
	}
	
	public static function s_getGroupField($class)
	{
		return self::getStatic($class, '_groupField');;
	}
	
	
	
	
	
	
	public function getDescendentRules()
	{
		$rules = $this->getStatic(get_class($this), '_descendentRules');
		return is_array($rules) ? $rules : null;
	}
	
	public function hasDescendent()
	{
		return self::s_hasDescendent(get_class($this));
	}
	
	public static function s_hasDescendent($class)
	{
		return self::getStatic($class, '_hasDescendent');
	}
	
	
	public function isDescendent()
	{
		return self::s_isDescendent(get_class($this));
	}
	
	public static function s_isDescendent($class)
	{
		return self::getStatic($class, '_isDescendent');
	}
	
	
	public function getAscendentClassName()
	{
		return get_parent_class($this);
	}
	
	
	
	public function getRefsByClassName($class)
	{
		$out = array();
		foreach($this->info('referenceMap') as $key => $ref)
		{
			if($ref['refTableClass'] == $class)
			{
				$out[$key] = $ref;
			}
		}
		return $out;
	}
	
	public function getRefByFieldName($field)
	{
		$out = null;
		foreach($this->info('referenceMap') as $key => $ref)
		{
			if($ref['columns'] == $field)
			{
				$out[$key] = $ref;
			}
		}
		return $out;
	}
	
		
	
	public function getxClassByDestClass($destClass)
	{
		return self::s_getxClassByDestClass(get_class($this), $destClass);
	}
	
	public static function s_getxClassByDestClass($class, $destClass)
	{
		if(!count(self::s_getxClasses($class))) return null;
		
		foreach(self::s_getxClasses($class) as $dest => $xClass)
		{
			if($dest == $destClass) return $xClass;
		}
		
		return null;
	}
	
	
	
	
	
	private function _buildFieldsArray(array $fields, array $info)
	{
		foreach($info['metadata'] as $fieldName => $metaInfo)
		{
			$out[$fieldName] = array_merge($metaInfo, array_key_exists($fieldName, $fields) ? $fields[$fieldName] : array());
		}
		return $out;
	}
	
	
	
	private function _buildReferenceMap()
	{
		return self::_s_buildReferenceMap(get_class($this));
	}
	
	private static function _s_buildReferenceMap($class)
	{
		$out = array();
		
		foreach(self::s_getFields($class) as $fieldName => $info)
		{
			if(isset($info['refTableClass']))
			{
				$refName = isset($info['refTableRole']) ? $info['refTableRole'] : $info['refTableClass'];
				$out[$refName] = array('columns' => $fieldName, 'refTableClass' => $info['refTableClass']);
				
				if(isset($info['onDelete']))
				{
					//$out[$refName]['onDelete'] = $info['onDelete'];
				}
			}
		}
		return $out;
	}
	
	private function _getMergedxClassesAndDependentTables()
	{
		$xClasses = $this->getxClasses();
		$dependentTables = $this->getDependentTables();
		
		if(!is_array($dependentTables) && !is_array($xClasses))
		{
			return array();
		}
		
		$out = array();
		if(is_array($xClasses))
		{
			foreach($xClasses as $class)
			{
				$out[] = $class;
			}
		}
		
		return is_array($dependentTables) ? array_merge($dependentTables, $out) : array();
	}
	
	
	
	
	public function getField($fieldName)
	{
		if(!array_key_exists($fieldName, $this->fields)) throw new Exception($fieldName."is not a field in this class");
		return $this->fields[$fieldName];
	}
	
	public static function s_getField($class, $fieldName)
	{
		$fields = self::getStatic($class, '_fields');
		return array_key_exists($fieldName, $fields)? $fields[$fieldName] : false;
	}
	
	
	
	public function getFieldLabel($fieldName)
	{
		$field = self::s_getField(get_class($this) , $fieldName);
		return isset($field['label']) ? $field['label'] : $fieldName;
	}
	
	
	
	
	public function getFields($fieldNames = null)
	{
		return is_array($fieldNames) ? $this->getFieldsByNames($fieldNames) : $this->fields;
	}
	
	
	public static function s_getFields($class, array $fieldNames = array())
	{
		return count($fieldNames) ? self::s_getFieldsByNames($class, $fieldNames) : self::getStatic($class, '_fields');
	}
	
	
	/*
	returns array of field meta arrays with a given role
	*/
	public function getFieldsByRole($role)
	{
		return $this->getFields($this->getFieldNamesByRole($role));
	}
	
	/*
	returns only the static fields array with a given role
	*/
	public static function s_getFieldsByRole($class, $role)
	{
		return self::s_getFields($class, self::s_getFieldNamesByRole($class, $role));
	}
	
	
	/*
	returns array of field meta arrays with a given context
	*/
	public function getFieldsByContext($context)
	{
		return $this->getFields($this->getFieldNamesByContext($context));
	}
	
	/*
	returns only the static fields array with a given context
	*/
	public static function s_getFieldsByContext($class, $context)
	{
		return self::s_getFields($class, self::s_getFieldNamesByContext($class, $context));
	}
	
	
	
	
	//returns array of fieldnames which have the given RefTableclass
	public function getFieldNamesByRefTableClass($refTableClass)
	{
		return self::s_getFieldNamesByRefTableClass(get_class($this), $refTableClass);
	}
	
	public static function s_getFieldNamesByRefTableClass($class, $refTableClass)
	{
		return self::s_getFieldNamesByPropertyValue($class, 'refTableClass', $refTableClass);
	}
	
	
	
	
	
	/*
	returns array of fieldnames only
	*/
	public function getFieldNamesByRole($role)
	{
		return self::s_getFieldNamesByRole(get_class($this), $role);
	}
	
	public static function s_getFieldNamesByRole($class, $role)
	{
		return self::s_getFieldNamesByPropertyValue($class, 'roles', $role);
	}
	
	
	public function getFieldNamesByContext($context)
	{
		return self::s_getFieldNamesByContext(get_class($this), $context);
	}
	
	public static function s_getFieldNamesByContext($class, $context)
	{
		return self::s_getFieldNamesByPropertyValue($class, 'contexts', $context);
	}
	
	
	
	/*
	returns an array of fields given an array of names
	*/	
	public function getFieldsByNames(array $namesArray)
	{
		$out = array();
		
		if(!count($namesArray)) return array();
		
		foreach($namesArray as $fieldName)
		{
			if(array_key_exists($fieldName, $this->fields))
			{
			$out[$fieldName] = $this->getField($fieldName);
			}
		}
		return $out;
	}
	
	public static function s_getFieldsByNames($class, array $namesArray)
	{
		$out = array();
		foreach(self::s_getFields($class) as $fieldName => $info)
		{
			if(array_key_exists($fieldName, $namesArray))
			{
			$out[$fieldName] = self::s_getField($class, $fieldName);
			}
		}
		return $out;
	}
	
	
	
	/*
	Used internally to get fields which have a particular property and value
	*/
	protected function getFieldNamesByPropertyValue($property, $value)
	{
		return self::s_getFieldNamesByPropertyValue(get_class($this), $property, $value);
	}
	
	/*
	static implementation ^
	*/
	protected static function s_getFieldNamesByPropertyValue($class, $property, $value)
	{
		$out = array();
		
		foreach(self::s_getFields($class) as $fieldName => $info)
		{
			if(isset($info[$property]))
			{
				if(is_array($info[$property]) && in_array($value, $info[$property]))
				{
					$out[] = $fieldName;
				}
				elseif($info[$property] == $value)
				{
					$out[] = $fieldName;
				}
			}
		}		
		return $out;
	}
	
	
	
	
	public function getNameFields()
	{
		return self::s_getNameFields(get_class($this));
	}
	
	public static function s_getNameFields($class)
	{
		$nameFields = self::getStatic($class, '_nameFields');
		
		if(!$nameFields && !count(self::s_getFieldNamesByRole($class, 'title'))) return false;
		
		return $nameFields ? $nameFields : self::s_getFieldNamesByRole($class, 'title');
	}
	
	
	
	public function getxClasses()
	{
		return self::s_getxClasses(get_class($this));		
	}
	
	public static function s_getxClasses($class)
	{
		return self::getStatic($class, '_xClasses') ? self::getStatic($class, '_xClasses') : array();
	}
		
	
	
	
	public function getFieldAliasPrefix()
	{
		return self::FIELD_ALIAS_PREFIX;
	}
	
	
	
	public function getxClassJoinedSelect($select = null, $xClassName = null)
	{
	//this joins either one or all of this class's xClasses onto the given select object, or the internal select object by default
		
		$sel = $select instanceof Zend_Db_Select ? $select : $this->select();
		
		$temp_xClasses = array();
		if(is_array($xClassName))
		{
			$temp_xClasses = array();
			foreach($xClasses as $xClass)
			{
				$temp_xClasses[$xClassName] = $this->getxClassByDestClass($xClassName);
			}
		}
		elseif(is_string($xClassName))
		{
			$temp_xClasses[$xClassName] = $this->getxClassByDestClass($xClassName);
		}
		else
		{
			$temp_xClasses = $this->getxClasses();
		}
		
		$xClasses = $temp_xClasses;
		
		if(!is_array($xClasses) || !count($xClasses)) return $sel;
		
		foreach($xClasses as $destClass => $xClass)
		{
			$xModel = new $xClass();
			$destModel = new $destClass();
			
			//join the xTable
			$sel->joinLeftUsing($xModel->info('name'), $this->getPrimary(), array());
			
			//join the dest table
			
			//$sel->joinLeft($destModel->info('name'), "`{$destModel->info('name')}`.`{$destModel->getPrimary()}` = `{$xModel->info('name')}`.`{$destModel->getPrimary()}`", array($destModel->getPrimary()));
			
			$sel->joinLeft($destModel->info('name'), "`{$destModel->info('name')}`.`{$destModel->getPrimary()}` = `{$xModel->info('name')}`.`{$destModel->getPrimary()}`", array('*'));
			
			
		}
		//echo $sel->__toString();
		
		return $sel;
	}
	
	
	public function byPrimary($id)
	{
		return $this->fetchRow($this->select()->byPrimary($id));
	}
	
	
	
	
	
	
	

	
	public function getDefaultThumb()
	{
		return self::s_getDefaultThumb(get_class($this));
	}
	
	public static function s_getDefaultThumb($class)
	{
	return is_file('../images/cat_icons_large/'.strtolower($class).'.png') ? '/images/cat_icons_large/'.strtolower($class).'.png' : '/images/cat_icons_large/default.png';
	}
	
	public function getIconLarge()
	{
		return self::s_getIconLarge(get_class($this));
	}
	
	public static function s_getIconLarge($class)
	{
	return self::getDefaultThumb($class);
	}
	
	public function getIconSmall()
	{
		return self::s_getIconSmall(get_class($this));
	}
	
	public static function s_getIconSmall($class)
	{ 
		
	return is_file('../images/cat_icons_small/'.strtolower($class).'.png') ? '/images/cat_icons_small/'.strtolower($class).'.png' : '/images/cat_icons_small/default.png';
	}
	
	
	
	
	
	
	
	public function hasImage()
	{
		return self::s_hasImage(get_class($this));
	}
	
	public static function s_hasImage($class)
	{
		return self::getStatic($class, '_hasImage');
	}
	
	
	public function hasFiles()
	{
		return self::s_hasFiles(get_class($this));
	}
	
	public static function s_hasFiles($class)
	{
		return self::getStatic($class, '_hasFiles');
	}
	
	public function getFilesVolumeField()
	{
		return self::s_getFilesVolumeField(get_class($this));
	}
	
	public static function s_getFilesVolumeField($class)
	{
		return self::getStatic($class, '_filesVolumeField');
	}
	
	
	
	
	
	
	public function getFileRoot()
	{
		return self::s_getFileRoot(get_class($this));
	}
	
	public static function s_getFileRoot($class)
	{
		return self::getStatic($class, '_fileRoot');
	}
	
	
	
	
	
	public function canHide()
	{
		///is this a class that can hide?
		return self::s_canHide(get_class($this));
	}
	
	
	public static function s_canHide($class)
	{
		///is this a class that can hide?
		return self::getStatic($class, '_canHide');
	}
	
	
	public function getVisField()
	{
		///is this a class that can hide?
		return self::s_getVisField(get_class($this));
	}
	
	
	public static function s_getVisField($class)
	{
		///is this a class that can hide?
		return self::getStatic($class, '_visibilityField');
	}
	
	
	
	public function canListAll()
	{
		return self::s_canListAll(get_class($this));
	}
	
	public static function s_canListAll($class)
	{
		return self::getStatic($class, '_canListAll');
		
	}
	
	
	
	public function getDerivationMap()
	{
		return self::$_derivationMap;
	}
	
	
}


?>