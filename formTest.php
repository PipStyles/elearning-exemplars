<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<form id="form-edit-form" enctype="application/x-www-form-urlencoded" accept-charset="UTF-8" method="post" action="processTest.php">
<div class="edit-field "><label for="personFirstname" class="optional">first name</label>

<input type="text" name="personFirstname" id="personFirstname" value="Ralph" class="text" /></div>

<div class="edit-field "><label for="personLastname" class="optional">last name</label>

<input type="text" name="personLastname" id="personLastname" value="Becker" class="text" /></div>
<div class="edit-field "><label for="personInitials" class="optional">initials</label>

<input type="text" name="personInitials" id="personInitials" value="" class="text" /></div>
<div class="edit-field "><label for="personEmail" class="optional">email</label>

<input type="text" name="personEmail" id="personEmail" value="" class="text" /></div>
<div class="edit-field "><label for="personTelephone" class="optional">telephone</label>

<input type="text" name="personTelephone" id="personTelephone" value="" class="text" /></div>
<div class="edit-field "><label for="personRoom" class="optional">room</label>

<input type="text" name="personRoom" id="personRoom" value="0" class="text" /></div>
<div class="edit-field "><label for="personTeam" class="optional">team</label>

<input type="text" name="personTeam" id="personTeam" value="0" class="text" /></div>
<div>
<input type="submit" name="save" id="form-save-button" value="save" class="button save-button edit-save-button" /></div></form>

<a onclick="document.getElementById('form-edit-form').submit();" >submit</a>

</body>
</html>
