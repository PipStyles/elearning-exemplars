<?php
require_once('Exemplar.php');

// Api methods requiring valid api key
class Api_ExemplarAuth extends Api_ExemplarPublic
{
	
	public function __construct()
	{
		parent::__construct();
		
		
	}
	
	
	/**
  * Return a string
  * 
	* @param string
  * @return string
  *
  */
	public function dothing($str)
	{
		return "This was the string submitted ".$str;
	}
	
	
	
	
	
	
}

?>