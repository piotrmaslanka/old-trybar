<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<?php
include "sql.php";
include "logic/logic.php";

$mode=@$_GET["mode"];
$del=@$_GET["del"];
$url=@$_POST["url"];
$name=@$_POST["name"];
$proba=@$_POST["proba"];
$wyslij=@$_POST["wyslij"];

if($mode=="")
{
echo'Wybierz kategorię<br>
<a href="?mode=0">Elektronika</a><br>
<a href="?mode=1">Gry komputerowe</a><br>';
}
else
{
if($del!="")
{
$usuwane=new ModelPrize($del);
$usuwane->delete();
}
if($wyslij==1)
{
if($proba!="")
{
$gfx=LogicUpload::storeAs('file',3);
LogicPrize::create($name, $mode, $gfx, $url);
}
else LogicPrize::create($name, $mode, 0, $url);
}
echo'<a href=?mode=>Wstecz</a><br><br>';
if($mode==0) echo'Lista nagród dla kategorii Elektronika<br>';

if($mode==1) echo'Lista nagród dla kategorii Gry komputerowe<br>';
echo'
<table border="1" cellpadding="1" cellspacing="1">
	<tr>
		<td>Nazwa nagrody</td>
		<td>Obrazek</td>
		<td>Link</td>
		<td>Tą nagrodę wybrało</td>
		<td>Usuń</td>
	</tr>
';
$lista=LogicPrize::pageByAll($mode);
 foreach($lista as &$value)
 {
 echo'
 <tr>
		<td>'.$value->name.'</td>
		<td>
		';
		
		if($value->gfx=="" || $value->gfx==0)
						{
                            echo'<img src="gfx/nagroda_brak.jpg">';
						}
						else
						{
						echo'<img src="uploads/50x50/'.$value->gfx.'.jpg">';
						}
		echo'
		</td>
		<td>'.$value->url.'</td>
		<td>'.$value->howMany().'</td>
		<td><a href="?mode='.$mode.'&del='.$value->id.'">Usuń</a></td>
 </tr>
 ';
 }

?>
 </table><br><br>
 Dodaj nagrodę do kategorii:
 <form action="" method="POST" enctype="multipart/form-data">
 Nazwa nagordy: <input type="text" class="txt" name="name"><br>
 Adres www (z całym http://www. na poczatku): <input type="text" class="txt" name="url"><br>
 Obrazek nagrody: <input type="file" name="file" id="avatar_select_field" onchange="document.getElementById('avatar_select_field_holder').value = this.value;" onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'">
							<div id="avatar_select_button" onclick="document.getElementById('avatar_select_field').click()"  onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'"></div>
 <input type="hidden" name="proba" id="avatar_select_field_holder" onclick="document.getElementById('avatar_select_field').click()">
 <input type="hidden" name="wyslij" value="1">
 <input type="submit" id="save_button" value="Dodaj">
 </form>
<?php
}
?>
</html>