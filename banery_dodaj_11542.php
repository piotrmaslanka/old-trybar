<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<?php
include "sql.php";
include "logic/logic.php";
$StronaValue=array(
                   'Strona główna',
                   'Ranking user',
                   'Wyszukiwarka',
                   'Strona baru',
                   'Ranking baru',
                   'Rejestracja',
                   'Nagrody');
$wyslij=@$_POST["wyslij"];
$del=@$_GET["del"];
if($del!="")
{
$baner=new ModelBanner($del);
$baner->delete();
}
if($wyslij==1 && $proba!="")
{
$alttext=@$_POST["alttext"];
$url=@$_POST["url"];
$strona=@$_POST["strona"];

$result=LogicUpload::checkValidity('file',1048576);  // 1mb
if($result==0) $id_photo=LogicUpload::storeAs('file',4);

ModelBanner::create($alttext, $url, $id_photo, $strona);
}
echo'<table border="1">';
echo'<tr><td>Grafika(podgląd/miniaturka)</td><td>Alt text</td><td>Url</td><td>Kliknieto</td><td>Wyświetlono</td><td>Strona</td><td>USUŃ</td></tr>';
$banery=ModelBanner::get_all_banners();
for ($i=0;$i<count($banery);$i++)
    {
    echo'<tr>';
    echo '<td><img src="uploads/native/'.$banery[$i]->id_gfx.'.jpg" style="width: 500px;"/></td>';
    echo '<td>'.$banery[$i]->alttext.'</td>';
    echo '<td>'.$banery[$i]->url.'</td>';
    echo '<td>'.$banery[$i]->clicks.'</td>';
    echo '<td>'.$banery[$i]->displays.'</td>';
    echo '<td>'.$StronaValue[$banery[$i]->page].'</td>';
   echo '<td><a href="?del='.$banery[$i]->id.'">Usuń</a></td>';
    echo'</tr>';
    }
echo'</table><br>';
?>
 Dodaj baner:
 <form action="" method="POST" enctype="multipart/form-data">
 Alt text: <input type="text" class="txt" name="alttext"><br>
 Odnośnik www (z całym http://www. na poczatku): <input type="text" class="txt" name="url"><br>
 Obrazek baneru: <input type="file" name="file" id="avatar_select_field" onchange="document.getElementById('avatar_select_field_holder').value = this.value;" onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'">
							<div id="avatar_select_button" onclick="document.getElementById('avatar_select_field').click()"  onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'"></div>
 Strona pokazywania:  <select id="strona" name="strona">
									    <?php
										for($i=0;$i<count($StronaValue);$i++)
                                         echo '<option value='.$i.'>'.$StronaValue[$i].'</option>';
                                        ?>
						</select> <br>                        
 <input type="hidden" name="proba" id="avatar_select_field_holder" onclick="document.getElementById('avatar_select_field').click()">
 <input type="hidden" name="wyslij" value="1">
 <input type="submit" id="save_button" value="Dodaj">
 </form>
<?php
?>
</html>