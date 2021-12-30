<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "sql.php";
include "logic/logic.php";
$wyslij=@$_POST["wyslij"];
$del=@$_GET["del"];
if($del!="")
{
Juve::deleteImage($del);
}
if($wyslij==1 && $proba!="")
{

$result=LogicUpload::checkValidity('file',100048576);  // 1mb
if($result==0) $id_photo=LogicUpload::storeAs('file',5);
Juve::addImage($id_photo);
}
echo'<table border="1">';
echo'<tr><td>Zdjęcia(podgląd/miniaturki)</td><td>USUŃ</td></tr>';
$zdjecia=Juve::getImages();
for ($i=0;$i<count($zdjecia);$i++)
    {
    echo'<tr>';
    echo '<td><img src="uploads/native/'.$zdjecia[$i].'.jpg" style="width: 150px;"/></td>';
    echo '<td><a href="?del='.$zdjecia[$i].'">Usuń</a></td>';
    echo'</tr>';
    }
echo'</table><br>';
?>
 Dodaj zdjęcie:
 <form action="" method="POST" enctype="multipart/form-data">
 Zdjęcie(plik): <input type="file" name="file" id="avatar_select_field" onchange="document.getElementById('avatar_select_field_holder').value = this.value;" onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'">
							<div id="avatar_select_button" onclick="document.getElementById('avatar_select_field').click()"  onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'"></div>                  
 <input type="hidden" name="proba" id="avatar_select_field_holder" onclick="document.getElementById('avatar_select_field').click()">
 <input type="hidden" name="wyslij" value="1">
 <input type="submit" id="save_button" value="Dodaj">
 </form>
<?php
?>
</html>