<?php

class View_Helper_SelectBuilder extends Zend_View_Helper_Abstract
{


	public function selectBuilder($model)
	{
		$rowset = $model->fetchAll();
		return $rowset;
	
	}

}

?>