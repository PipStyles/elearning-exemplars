<?php
require_once('Exemplar.php');

class Api_ExemplarPublic
{

  protected $_exemplar;
	
	//defines usable keys to query for funded exemplars, etc etc
	protected $_customParamKeys = array('hasFunding','hasChannel');
	
	
  public function __construct()
  {
	  $this->_exemplar = new Exemplar();
  }
  
	
	
	
	
	
	
  /**
  * Return all exemplars
  * 
  * @return array
  *
  */
  public function exemplars($params = null)
  {
     return $this->_getExemplars($params);
  }
	
  
	protected function _getExemplars($params = null)
	{
		$select = $this->_getSelect_AllJoined();
		
		echo $select;
		
		if(is_array($params))
		 {
			 // use query params
			
			 if(isset($params['hasFunding']))
			 {
				 
				 
				 
				 $result = $this->_exemplar->fetchAll($select->where("exemplar_id",89))->toArray();
			 }
			 	 
			 
			 $result = $this->_exemplar->fetchAll($select)->toArray();
		 }
		 else
		 {
			 $result =  $this->_exemplar->fetchAll()->toArray();
		 }
		 
		 return $result;
	}












	/**
  * Return exemplar by id
  * 
	* @param int id
  * @return array
  *
  */
	public function exemplar($id)
	{
		return $this->_getExemplar($id);
	}
	
	protected function _getExemplar($id)
	{
		$id = intval($id);
		
		$select = $this->_exemplar->select()->where("exemplar_id = ?", $id)
		                                    ->where("exemplarPublishStatus = ?", Exemplar::PUBLIC_PUBLISH_LEVEL);
		
		return $this->_exemplar->fetchRow($select)->toArray();
	}
	
	
	
	
	
	
	
	
	
	
	protected function _getExemplarsJoined($select = null)
	{
	  $s = $select instanceof Zend_Db_Select ? $select : $this->_exemplar->select();
		$select = $this->_exemplar->getxClassJoinedSelect($this->_exemplar->getJoinedSelect($s));
		
		return $this->_exemplar->fetchAll($select);
	}
	
	protected function _getSelect_AllJoined()
	{
		return $this->_exemplar->getxClassJoinedSelect($this->_exemplar->getJoinedSelect());
	}
	
	
	
	
	
	
	public function getExemplarsJson($params = null)
	{
		$select = $this->_exemplar->select();
		
		if(is_array($params))
		{
			
			foreach($params as $p => $v)
			{
				
			}
			
		}
		
		
		$rowset = $this->_getExemplarsJoined($select);
		
		
		$xClasses = $this->_exemplar->getxClasses();
		$fields = $this->_exemplar->getFields();
		
		
		$a = array();
		foreach($rowset as $r)
		{
		  
		  $a[$r->getID()] = array();
		   
		  foreach($r as $f => $v)
		  {
			if(!in_array($f, $fields))
			{
				$a[$r->getID()][$f] = $v;
			}
			else
			{
				
				///************************
			  $a[$r->getID()][$f] = ''	;
			}
			  
		  }
			
			
			
		}
		
		return Zend_Json::encode($a);
	}
	
	
	
	
	
}


?>