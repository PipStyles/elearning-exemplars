<?php

class DevController extends Zend_Controller_Action
{

public function init()
	{	
	$this->db = Zend_Registry::get('db');;
	}


public function indexAction()
	{
	
	}
	
	
public function listfieldsAction()
	{
	$master = array();
	
	//$sql = 'SELECT * FROM db_field ORDER BY `table`, id, fieldname';
	
	$this->tables = $this->db->listTables();
	
	foreach($this->tables as $table)
		{
		
		$sql = "SELECT * FROM db_field WHERE `table` = '{$table}' ORDER BY id, fieldname";
		$fields = $this->db->fetchAll($sql);
		
		$master[$table] = array();
		$meta = array();
		
		foreach($fields as $field)
			{
			foreach($field as $property_name=>$property)
			  {
				$meta[$field['fieldname']][$property_name] = $property;
				}
			}
			
		$master[$table] = $meta;
		}
	
	$this->view->tables = $fixed_master = $this->fixMaster($master);
	
	}
	
	
	
	
	private function fixMaster($tablesArray)
		{
			
		//make a reference map and a custom fields info array
		
		$refMaps = array();
		$master = array();
		
		foreach($tablesArray as $tableName => $tableFields)
			{
			if(!count($tableFields)) continue;
			
			$master[$tableName] = array();
			$refMaps[$tableName] = array();
			
			foreach($tableFields as $fieldName => $properties)
				{
				$master[$tableName][$fieldName] = array();
				$refMaps[$tableName] = array();
				
				$master[$tableName][$fieldName]['label'] = $properties['label'];
				
				//do the ref map bit
				if(strlen($properties['refTableClass']))
					{
					//yes - this field has a ref class - just stick the tablename in for now
					$refMaps[$tableName]['REF_'.$fieldName] = $properties['refTableClass'];
					}
				
				
				
				$contexts = array();
				if($properties['show_in_list'] == 'true')
					{
					$contexts[] = 'list';
					}
				if($properties['show_in_form'] == 'true')
					{
					$contexts[] = 'form';
					}
				if($properties['show_in_refine'] == 'true')
					{
					$contexts[] = 'refine';
					}
				if($properties['show_in_public_record'] == 'true')
					{
					$contexts[] = 'detail';
					}
				
				
				$master[$tableName][$fieldName]['contexts'] = $contexts;
				
				$master[$tableName][$fieldName]['private'] = $properties['is_private'] == 'true' ? 1 : 0;
				
				$master[$tableName][$fieldName]['formType'] = $properties['formType'];
				
				
				$roles = array();
				if($properties['ext_link'] == 1)
					{
					$roles[] = 'URL';
					}
				if(strstr($fieldName,'email'))
					{
					$roles[] = 'email';
					}
				if(strstr($fieldName,'address'))
					{
					$roles[] = 'address';
					}
				if(strstr($fieldName,'description'))
					{
					$roles[] = 'description';
					}
				if(strstr($fieldName,'telephone'))
					{
					$roles[] = 'telephone';
					}
				if(strstr($fieldName,'postcode'))
					{
					$roles[] = 'address';
					$roles[] = 'postcode';
					}
				if(strstr($fieldName,'latitude'))
					{
					$roles[] = 'latitude';
					}
				if(strstr($fieldName,'longitude'))
					{
					$roles[] = 'longitude';
					}
				if(strstr($fieldName,'city'))
					{
					$roles[] = 'address';
					}
				if(strstr($fieldName,'country'))
					{
					$roles[] = 'address';
					}
				if(strstr($fieldName,'name') || strstr($fieldName,'title'))
					{
					$roles[] = 'title';
					}
				
				if(count($roles))
					{
					$master[$tableName][$fieldName]['roles'] = $roles;
					}
					
				if(strlen($properties['refTableClass']))
					{
					$master[$tableName][$fieldName]['refTableClass'] = $properties['refTableClass'];
					}
					
					
				}
			}
		return $master;	
		}






   public function fixAllToBle()
   {
	  
	  /*
	  $exemplarModel = new Exemplar();
	  $exChannel = new x_ExemplarChannel();
	  $exemplars = $exemplarModel->fetchAll();
	  
	  foreach($exemplars as $ex)
	  {
		  $exch_row = $exChannel->createRow(array('exemplar_id'=>$ex->exemplar_id,'channel_id'=>1));
		  $exch_row->save();
	  }
	  */
	     
   }
   
   
   
   
}


?>