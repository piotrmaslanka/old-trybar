<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
$proba=@$_POST["proba"];
$proba=htmlspecialchars($proba, ENT_COMPAT, 'UTF-8');
$description=@$_POST["description"];
$description=htmlspecialchars($description, ENT_COMPAT, 'UTF-8');
if(LogicSession::isLoggedIn()==false || LogicSession::can_edit_profile($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$result=-1;

if($proba!="") $result=LogicUpload::checkValidity('file',1048576);  // 1mb
if($result==0)
{
						  $id_photo=LogicUpload::storeAs('file',1);
						  LogicSession::getUser()->addPhoto($id_photo,$description);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>TryBAR</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/dodajzdjecie.css">
	<link rel="stylesheet" type="text/css" href="css/shoutbox.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/shoutbox.js"></script>
	<script type="text/javascript" src="js/online.js"></script>
	
	<script type="text/javascript">

　var _gaq = _gaq || [];
　_gaq.push(['_setAccount', 'UA-15488341-7']);
　_gaq.push(['_trackPageview']);

　(function() {
　　var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
　　ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
　　var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
　})();

</script>
<?php
			include "moduly/head.php";
?>
</head>
<body>
	<div id="haslo_przewodnie"></div>	<!-- twoj bar, twoja wodka -->
	<div id="top">
<iframe
src="http://www.facebook.com/plugins/like.php?href=http://www.facebook.com/pages/TryBAR/142549105817938
&layout=standard&show_faces=false& width=450&action=like&colorscheme=light&height=80"
scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; position: absolute; right: 10px; top: 10px; overflow: clip;
height:80px;" allowTransparency="true"></iframe> 
</div>
				<!-- duzy obrazek z duza iloscia wodki -->
	<div id="menubar">					<!-- glowne menu - to brazowe - szkoda ze bez wodki -->
		<?php
			include "moduly/nawigacja.php";
			?>
	</div>
	
	<div id="content_area_holder">
		<img src="gfx/mid_border.png" style="position: absolute; left: 0; top: 0;" alt="">
		<img src="gfx/mid_border.png" style="position: absolute; right: 0; top: 0;" alt="">
		<table id="content_area">
			<tr>
				<td id="content_area_padtop_leftmenu" style="vertical-align: middle;">
					<img src="gfx/aktualnosci/moje_konto.gif" alt="Moje konto">
				</td>
				<td id="content_area_content" rowspan="3">
				
					<div id="content_area_content_c">	<!-- glowny kontener -->									
						<div id="ie_safeguard"><!-- straznik przeciwko idiotyzmom Internet Explorera --></div>
						<div class="center-message">Możesz wgrać maksymalnie 3 zdjęcia</div>
						<form action="" method="post" enctype="multipart/form-data">
						<?php
						$user=LogicSession::getUser();
						if($user->getPhotosCount()<3) //ilosc zdjec dla usera dla prywatnej galerii
						{
						?>
						<div id="descript_box">Dodaj opis:<br><textarea name="description" ><?php echo''.$description.''; ?></textarea></div>
						<?php
						if($proba!="")
						{
						echo'<img id="button_dodaj" src="uploads/206x154/'.$id_photo.'.jpg" style="float: left;">';
						}
						else echo'<img id="button_dodaj" src="gfx/galeria/button_dodaj.gif" style="float: left;">';
						?>
						

						<div class="clear" style="height: 30px;"></div>
			
						<div id="avatar_select">
							<input type="file" name="file" id="avatar_select_field" onchange="document.getElementById('avatar_select_field_holder').value = this.value;" onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'">
							<div id="avatar_select_button" onclick="document.getElementById('avatar_select_field').click()"  onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'"></div>
							<input type="text" name="proba" id="avatar_select_field_holder" onclick="document.getElementById('avatar_select_field').click()">
							<?php
						switch ($result)
						{
						  case 0:
						  echo'<font color="green">Dodano zdjęcie</font><br>';
						  break;
						  
						  case 1:
						  echo'<font color="red">Maksymalna wielkosć zdjęcia 1 Mb</font><br>';
						  break;
						  
						  case 2:
						  echo'<font color="red">Niepoprawny plik graficzny lub nierozpoznany format</font><br>';
						  break;
						  
						  case 3:
						  echo'<font color="red">Błąd uploadowania</font><br>';
						  break;
						}
						?>
						</div>

						<input type="submit" id="save_button" value="">
		
						</form>
						<?php
						}
						?>
					</div>
				
				</td>
				<td id="content_area_padtop_rightmenu"></td>
			</tr>
			
			<tr>
				<td class="images_are_blocks">
					<div id="acc_box_header">
					<?php
						include "moduly/dane_profil.php"
						?>	
				</td>	
				<td class="images_are_blocks">
					<?php
					include "moduly/shoutbox.php"
					?>
				</td>
			</tr>
					
			<tr>
				<td id="content_area_padbottom_leftmenu"></td>
				<td id="content_area_padbottom_rightmenu"></td>
			</tr>
			<tr>
				<td colspan="3">
					<div id="footer">
						<?php
						include "moduly/stopka.php";
						?>
					</div>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>
<?php
}
?>