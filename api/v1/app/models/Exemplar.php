<?php

require_once('Exemplar.php');

class Api_Exemplar
{

  protected $_exemplar;
  
  public function __construct()
  {
	 $this->_exemplar = new Exemplar();
	  
	  
  }
  
  
	public function getExemplarObject()
	{
		return $this->_exemplar;
	}
	
	
	
	
  /**
  * Return sum of two variables
  *
  * @param int $a
  * @param int $b
  * @return array
  *
  */
  public function add($a, $b)
  {
	  return array('result'=> $a + $b);
  }
  
  
  
  /**
  * Return all exemplars
  * 
  * @return array
  *
  */
  public function allExemplars()
  {
     return $this->_exemplar->fetchAll()->toArray();
  }
  
  
  /**
  *
  * @param int $id
  * @return $row
  *
  */  
  public function getExemplarById($id)
  {
	  $select = $this->_exemplar->select()->from("exemplar")->where("exemplar_id = ?", $id);
	  return $this->_exemplar->fetchRow($select);
  }
  
  
  
  /**
  *
  * @param array $channels
  * @return object $rowset
  *
  */  
  public function getExemplarsByChannelNames($channels = array())
  {
	  $select = $this->_exemplar->getSelect_Published();
	  $select->join('x_exemplar_channel', 'x_exemplar_channel.exemplar_id = exemplar.exemplar_id', array());
	  $select->joinNatural("channel");
	  
	  $select->where("channelName IN(?)", $channels);
	  
	  return $this->_exemplar->fetchAll($select);
  }
  
	
  /**
  *
  * @param array $channels
  * @return object $rowset
  *
  */ 
  public function getExemplarsByChannelIds($channels = array())
  {
		return $this->_exemplar->fetchAll($this->getSelect_ExemplarsByChannelIds($channels));
  }
	
	
	public function getSelect_ExemplarsByChannelIds($channels = array(), $s = null)
	{
		if($s instanceof Zend_Db_Select)
		{
			$select = $s;
		}
		else
		{
			$select = $this->_exemplar->select()->from('exemplar')->setIntegrityCheck(false);
		}
		
		$select->join('x_exemplar_channel', 'x_exemplar_channel.exemplar_id = exemplar.exemplar_id', array())
	       ->joinNatural('channel')
	       ->where('channel_id IN(?)', $channels);
		
		return $select;
	}
	
  
	
}


?>