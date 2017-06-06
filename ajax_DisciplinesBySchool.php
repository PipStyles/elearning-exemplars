<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/tandl/elearning/exemplars/_publicBootstrap.php');

if(!isset($_GET['school']))
{
	exit;
}

$school = mysql_escape_string($_GET['school']);

$discOb = new Discipline();

$rowset = $discOb->fetchAllBySchools($school);

if(!count($rowset))
{
	exit;
}

$discJsonObs = array();
foreach($rowset as $row)
{
	$discJsonObs[] = Zend_Json::encode(array("discipline_id"=>$row->discipline_id, "disciplineName"=>$row->disciplineName));
}

$json = '['.implode(',', $discJsonObs).']';

//header('Content-Type: application/json');
echo $json;

?>