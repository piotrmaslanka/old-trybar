<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
if(LogicSession::isLoggedIn()==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$name=@$_POST["name"];
$name=htmlspecialchars($name, ENT_COMPAT, 'UTF-8');
$street=@$_POST["street"];
$street=htmlspecialchars($street, ENT_COMPAT, 'UTF-8');
$city=@$_POST["city"];
$city=htmlspecialchars($city, ENT_COMPAT, 'UTF-8');
$region=@$_POST["region"];
$region=htmlspecialchars($region, ENT_COMPAT, 'UTF-8');
$opis=@$_POST["opis"];
$opis=htmlspecialchars($opis, ENT_COMPAT, 'UTF-8');
$proba=@$_POST["proba"];
$proba=htmlspecialchars($proba, ENT_COMPAT, 'UTF-8');
$wyslij=@$_POST["wyslij"];
$wyslij=htmlspecialchars($wyslij, ENT_COMPAT, 'UTF-8');
$RegionValue=array('Wybierz',
										 'Dolnośląskie',
										 'Kujawsko-Pomorskie',
										 'Lubelskie',
										 'Lubuskie',
										 'Łódzkie',
										 'Małopolskie',
										 'Mazowieckie',
										 'Opolskie',
										 'Podkarpackie',
										 'Podlaskie',
										 'Pomorskie',
										 'Śląskie',
										 'Świętokrzyskie',
										 'Warmińsko-Mazurskie',
										 'Wielkopolskie',
										 'Zachodniopomorskie');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>TryBAR</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/dodajbar.css">
	<link rel="stylesheet" type="text/css" href="css/shoutbox.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/selectbox.js"></script>
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
					<img src="gfx/dodajbar/dodawanie.gif" alt="Dodawanie">
				</td>
				<td id="content_area_content" rowspan="3">
				
					<div id="content_area_content_c">	<!-- glowny kontener -->									
						<div id="ie_safeguard"><!-- straznik przeciwko idiotyzmom Internet Explorera --></div>
						
						<form action="" method="post" enctype="multipart/form-data">
						<table id="top_part">
							<tr>
								<td class="tp_label">Nazwa baru*</td><td class="tp_spacer"></td><td><input type="text" name="name" value="<?php echo''.$name.''; ?>" maxlength="50" ></td>
							</tr>
							<tr>
								<td class="tp_label">Ulica*</td><td class="tp_spacer"></td><td><input type="text" name="street" value="<?php echo''.$street.'';  ?>" maxlength="50"></td>
							</tr>
							<tr>
								<td class="tp_label">Miasto*</td><td class="tp_spacer"></td><td><input type="text" name="city" value="<?php echo''.$city.''; ?>" maxlength="50"></td>
							</tr>
							<tr>
								<td class="tp_label">Województwo*</td><td class="tp_spacer"></td>
								<td>
									<select id="wojew" name="region">
									<?php
										for($i=0;$i<count($RegionValue);$i++)
      {
        if ($RegionValue[$i]!=$region)
         {
         echo '<option value='.$RegionValue[$i].'>'.$RegionValue[$i].'</option>';
         }
         else
         {
         echo '<option value='.$RegionValue[$i].' selected="true">'.$RegionValue[$i].'</option>';
         }
       }
	   ?>
									</select>
									<script type="text/javascript">
										$("select").selectBox();
									</script>																
								</td>
							</tr>
							<tr>
								<td class="tp_label">Opis</td><td class="tp_spacer"></td><td><textarea name="opis"><?php echo''.$opis.'';  ?></textarea></td>
							</tr>
							
							<tr><td colspan="3" class="miniseparator"></td></tr>
							
							<tr>
								<td></td><td></td><td id="password_change_label"><span>Dodawanie zdjęcia</span></td>							
							</tr>
						</table>

						<div id="avatar_select">
							<input type="file" name="file" id="avatar_select_field" onchange="document.getElementById('avatar_select_field_holder').value = this.value;" onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'">
							<div id="avatar_select_button" onclick="document.getElementById('avatar_select_field').click()"  onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'"></div>
							<input type="text" name="proba"  id="avatar_select_field_holder" onclick="document.getElementById('avatar_select_field').click()">
							<input type="hidden" name="wyslij" value="1">
						</div>
				
						<?php
						if($wyslij!="")
						{
						if($name=="" || $street=="" || $city=="" || $region=="" || $region=="Wybierz") 
						{
						echo'<div class="red-message">Wypełnij wszystkie wymagane pola</font></div>';
						}
						else
						{
						
						 $znal=0;
						 if(LogicBars::getBarByName($name)!=null)
						 {
						  $znaleziony_bar=LogicBars::getBarByName($name);
						  if($znaleziony_bar->city==$city && $znaleziony_bar->region==$region && $znaleziony_bar->street==$street)
						   {
						   $znal=1;
						   echo'<div class="red-message">Bar o podanej nazwie istnieje już w bazie</div>';
						   }
						 }
						 
						 if($znal==0)
						 {
						if( $proba!="")
						{
						$result=LogicUpload::checkValidity('file',1048576);  // 1mb
						switch ($result)
						{
						  case 0:
						  $id_photo=LogicUpload::storeAs('file',2);
						  LogicBars::create($name, $street, $city, $region, $opis, $id_photo, LogicSession::getUser()->id);
						  break;
						  
						  case 1:
						  echo'<div class="red-message">Maksymalna wielkosć zdjęcia 1 Mb</div>';
						  break;
						  
						  case 2:
						  echo'<div class="red-message">Niepoprawny plik graficzny lub nierozpoznany format</div><br>';
						  break;
						  
						  case 3:
						  echo'<div class="red-message">Błąd uploadowania</div><br>';
						  break;
						}
						}
						else 
						{
						$nbid = LogicBars::create($name, $street, $city, $region, $opis, 0, LogicSession::getUser()->id);
                        header('Location: bar.php?id='.$nbid);
						}
						echo'<div class="green-message">Pomyślnie dodano bar</div><br>';
						 }	
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
					<img src="gfx/logowanie/lewy_box_tytul.gif" alt="Informacja">
					<div id="content_area_content_leftmenu">
					<div id="leftmenu_info">
						Dodanie baru umieszcza Twój ulubiony bar w naszej bazie. Po pomyślnym przejściu przez ten proces więcej zdjęć można dodać z panelu właściciela baru. Inni użytkownicy również mogą dodawać zdjęcia do Twojego baru. Te nieodpowiednie możesz osobiście usuwać, koniecznie podając przyczynę. <br><br>Bar może być również komentowany i oceniany.<br><br>Dzięki średniej ocenie będzie rywalizował z innymi barami w rankingu barów.
					</div>
					</div><img src="gfx/lewy_box_dol.gif" alt="">
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