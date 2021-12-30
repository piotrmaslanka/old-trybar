<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
$nowe_miasto=@$_POST["nowe_miasto"];
$nowe_miasto=htmlspecialchars($nowe_miasto, ENT_COMPAT, 'UTF-8');
$old_password=@$_POST["old_password"];
$old_password=htmlspecialchars($old_password, ENT_COMPAT, 'UTF-8');
$password=@$_POST["password"];
$password=htmlspecialchars($password, ENT_COMPAT, 'UTF-8');
$repassword=@$_POST["repassword"];
$repassword=htmlspecialchars($repassword, ENT_COMPAT, 'UTF-8');
$proba=@$_POST["proba"];
$imie=@$_POST["i"];
$imie=htmlspecialchars($imie, ENT_COMPAT, 'UTF-8');
$nazwisko=@$_POST["s"];
$nazwisko=htmlspecialchars($nazwisko, ENT_COMPAT, 'UTF-8');
if(LogicSession::isLoggedIn()==false || LogicSession::can_edit_profile($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$result=-1;
$problem_haslo=false;
$User=LogicSession::getUser();
if($nowe_miasto!="")
{
$User->changeCity($nowe_miasto);
}
if($proba!="") $result=LogicUpload::checkValidity('file',102400); //100 kb
$User->setName($imie);
$User->setSurname($nazwisko);
$User=LogicSession::getUser();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>TryBAR</title>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">	<!-- brzydko ale nie mam czasu :) -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/profil.css">
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
							<tr><td class="tp_label">Imię</td><td class="tp_spacer"></td><td><input type="txt" name="i" value=<?php echo $imie;?> ></td></tr>
							<tr></tr><td class="tp_label">Nazwisko</td><td class="tp_spacer"></td><td><input type="txt" name="s" value=<?php echo $nazwisko;?> ></td></tr>
							<tr>
								<td></td><td></td><td id="password_change_label"><span>Zmiana hasła</span>
							<?php
							if($old_password!="" && $password!="" && $repassword!="")
							{
							if($User->verifyPassword($old_password)==false)
							{
							echo'<font color="red" ><br>Aktualne hasło nie zgadza się</font></br></center>';
							$problem_haslo=true;
							}
							if($password==$User->login)
							{
							echo'<font color="red"><br>Hasło nie może być takie samo jak login</font>';
							$problem_haslo=true;
							}
							if($password!=$repassword)
							{
							echo'<font color="red" ><br>Nowe hasło nie zgadza się z powtórzonym</font></br>';
							$problem_haslo=true;
							}	
							}
							?>
							</td>								
													
							</tr>
							<tr>
							

								<td class="tp_label">Obecne hasło</td><td class="tp_spacer"></td><td><input type="password" name="old_password"></td>
							</tr>
							<tr>
								<td class="tp_label">Nowe hasło</td><td class="tp_spacer"></td><td><input type="password" name="password"></td>
							</tr>
							<tr>
								<td class="tp_label">Potwórz nowe hasło</td><td class="tp_spacer"></td><td><input type="password" name="repassword"></td>
							
							</tr>
							
							<tr><td colspan="3" class="miniseparator"></td></tr>
							
							<tr>
								<td colspan="3" style="text-align: center;">
							<?php
							if($problem_haslo==false && $old_password!="" && $password!="" && $repassword!="")
							{
						    $User->changePassword($password);
							echo'<font color="green">Zmieniono hasło</font><br>';
							}
							?>
								Zmiana miejsca zamieszkania</td>
							</tr>
							<tr>
								<td class="tp_label">Miasto obecne</td><td class="tp_spacer"></td><td><?php echo''.$User->city.''?></td>
							</tr>
							<tr>
								<td class="tp_label">Nowe miasto</td><td class="tp_spacer"></td><td><input type="text" name="nowe_miasto"></td>
							</tr>
							<tr><td colspan="3" class="miniseparator"></td></tr>
							<tr>
								<td></td><td></td><td id="password_change_label"><span>Zmiana awataru</span></td>							
							</tr>
						</table>

						<div id="avatar_select">
							<input type="file" name="file" id="avatar_select_field" onchange="document.getElementById('avatar_select_field_holder').value = this.value;" onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'">
							<div id="avatar_select_button" onclick="document.getElementById('avatar_select_field').click()"  onmouseover="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj_p.gif)'" onmouseout="$('#avatar_select_button')[0].style.backgroundImage = 'url(gfx/profil/button_przegladaj.gif)'"></div>
							<input type="text" name="proba" id="avatar_select_field_holder" onclick="document.getElementById('avatar_select_field').click()">
						<?php
						switch ($result)
						{
						  case 0:
						  $id_avatar=LogicUpload::storeAs('file',0);
						  $User->setAvatar($id_avatar);
						  echo'<font color="green">Zmieniono avatara</font><br>';
						  break;
						  
						  case 1:
						  echo'<font color="red">Maksymalna wielkosć avataru 100 kb</font><br>';
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