<?php
//sets up exemplar page specific assets: Exemplar object, search from, refine query...
$db = Zend_Registry::get('db');

$ex = new Exemplar();

$searchForm = new Form_Exemplar_Search();

$searchFlag = 'search=1&';

$refineFlag = new Zend_Form_Element_Hidden('search');
$refineFlag->setValue(1);
$refineFlag->setDecorators(array('ViewHelper','Form','FormElements'));

$searchForm->addElement($refineFlag);
$searchForm->setAction('index.php')->setMethod('get');

if(isset($_GET['search']))
{
	$searchForm->populate($_GET);
}

?>