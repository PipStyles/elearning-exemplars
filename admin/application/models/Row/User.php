<?php 

class Row_User extends Model_DbTable_Row
{


	public function save()
	{
	//$this->password = $this->setPassword($this->password);
	
	parent::save();
	}
	
	
	public function setPassword($password)
	{
		$original = $this->getTable()->fetchRow($this->getID());
		
		if(strlen($password) == 0)
		{
			return $original->password;
		}
		
		return strlen($password) > 30 ? $password : md5($password);
		
	}
	
	

}


?>