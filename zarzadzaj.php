<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
if(LogicSession::isLoggedIn()==false || LogicBars::barExistsId($id)==false || LogicSession::can_edit_bar($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{

$wyslij=@$_POST["wyslij"];
$wyslij=htmlspecialchars($wyslij, ENT_COMPAT, 'UTF-8');
$del=@$_POST["del"];
$del=htmlspecialchars($del, ENT_COMPAT, 'UTF-8');
$main=@$_POST["main"];
$main=htmlspecialchars($main, ENT_COMPAT, 'UTF-8');
$proba=@$_POST["proba"];
$proba=htmlspecialchars($proba, ENT_COMPAT, 'UTF-8');
$bar=new ModelBar($id);
$opis=@$_POST["opis"];
$opis=htmlspecialchars($opis, ENT_COMPAT, 'UTF-8');
$street=@$_POST["street"];
$street=htmlspecialchars($street, ENT_COMPAT, 'UTF-8');
if($wyslij==1)
{
$opis=htmlspecialchars($opis, ENT_COMPAT, 'UTF-8');
$bar->setOpis($opis);

if($del!="") $bar->deletePhoto($del);

if($main!="") $bar->setAsMainPhoto($main);

if($proba!="")
{
$result=LogicUpload::checkValidity('file',1048576);  // 1mb
if($result==0) $id_photo=LogicUpload::storeAs('file',2);
$bar->addPhoto($id_photo);
}
$bar=new ModelBar($id);
if($street!="") $bar->setStreet($street);
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
	<link rel="stylesheet" type="text/css" href="css/zarzadzaj.css">
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
						<form action="" method="post" enctype="multipart/form-data">
						<table id="top_part">
							<tr><td class="tp_label">Ulica</td><td class="tp_spacer"></td><td><input type="text" name="street" value="<?php echo $bar->street;?>"></td></tr>
						</table>
						
						
						
						<input type="hidden" name="wyslij" value="1">
						<div id="napis_ze_opis">Opis</div>

						<table id="tabela_z_innymi_obrazkami">
								<!-- glowny obrazek -->
							
							<tr>
								<td class="margleft"></td>
								<td class="obrazek">	
								<?php	
								$photos=$bar->getOrganizedPhotos();
								$tym=$bar->getPhotos();
								if(count($tym)>0)	echo'<img src="uploads/206x154/'.$photos[0].'.jpg">';
								echo'
								</td>
								<td class="spacer"></td>
								<td class="rest">
									<textarea name="opis">'.$bar->description.'</textarea>
								';	
								if(count($tym)>0)	echo'<button class="usun" name="del" value="'.$photos[0].'"></button>';
								?>
								</td>
							</tr>
							<!-- reszta holoty -->
							<?php
							
							if(count($photos)>1)
							{
							for($i=1;$i<count($photos);$i++)
							{
							echo'
							<tr>
								<td class="margleft"></td>
								<td class="obrazek">								
									<img src="uploads/206x154/'.$photos[$i].'.jpg">								
								</td>
								<td class="spacer"></td>
								<td class="rest">
									<div class="asmain"><button class="jakoglowny" name="main" value="'.$photos[$i].'"></button></div>
									<div class="guzik"><button class="usun" name="del" value="'.$photos[$i].'"></button></div>
								</td>
							</tr>
							';
							}
							}
							?>
						</table>

						<div class="clear" style="height: 30px;"></div>

			
						<div id="avatar_select">
							<input type="file" name="file" id="avatar_select_field" onchange="document.getElementById('avatar_select_field_holder').value = this.value;" onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'">
							<div id="avatar_select_button" onclick="document.getElementById('avatar_select_field').click()"  onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'"></div>
							<input type="text" name="proba" id="avatar_select_field_holder" onclick="document.getElementById('avatar_select_field').click()">
						</div>
						<?php
						if($proba!="")
						{
						switch ($result)
						{
						  case 0:
						  echo'<div class="green-message"> '.$proba.' do baru</div><br>';
						  break;
						  
						  case 1:
						  echo'<div class="red-message">Maksymalna wielkosć zdjęcia 1 Mb</div><br>';
						  break;
						  
						  case 2:
						  echo'<div class="red-message">Niepoprawny plik graficzny lub nierozpoznany format</div><br>';
						  break;
						  
						  case 3:
						  echo'<div class="red-message">Błąd uploadowania</div><br>';
						  break;
						}
						}
						?>
						<input type="submit" id="save_button" value="">
		
						</form>

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