<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/tandl/elearning/exemplars/_publicBootstrap.php');

$discOb = new Discipline();
$discSelect = $discOb->select();

$rowset = $discOb->fetchAll($discSelect);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Subject in Hum</title>
</head>

<body>
<?php 

$out = array();
foreach($rowset as $row)
{
$out[] = $row->disciplineCode;
}

echo implode(',', $out);
?>

</body>
</html>
