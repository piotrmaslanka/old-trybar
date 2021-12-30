<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
if(LogicSession::isLoggedIn()==true)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=aktualnosci.php">';
}
else
{
$login3=@$_POST["login3"];
$login3=htmlspecialchars($login3, ENT_COMPAT, 'UTF-8');
$email3=@$_POST["email3"];
$email3=htmlspecialchars($email3, ENT_COMPAT, 'UTF-8');
$wyslano=@$_POST["wyslano"];
$wyslano=htmlspecialchars($wyslano, ENT_COMPAT, 'UTF-8');

$error="";
if($wyslano==1)
{
 if($login3=="" || $email3=="")
 {
 $error="Wypełnij oba pola";
 }
 else
 {
 if(LogicUser::userExistsLogin($login3)==false)
  { 
  $error="Nie istnieje taki login w bazie";
  }
  else
  {
  $user=LogicUser::getUserByLogin($login3);
  if($user->email!=$email3)
  {
  $error="Login i E-mail nie zgadzają się";
  }
  else $user->regeneratePassword();
  }
 }
}
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
	<link rel="stylesheet" type="text/css" href="css/przypomnij.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
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
		</div>
	</div>
	
	<div id="welcomebar">			<!-- Witamy na TryBAR / Witaj Ojou-sama poziomy pasek na cala dlugosc strony -->
		<img src="gfx/mid_border.png" style="position: absolute; left: 0; top: 0;" alt="">
		<img src="gfx/mid_border.png" style="position: absolute; right: 0; top: 0;" alt="">

		<div id="welcomebar_rgt">
			<?php
			linki_rejstracyjne();
			?>
		</div>
		<div id="welcomebar_mid">
		</div>
		<div id="welcomebar_lft">
		</div>
	</div>

	<div id="content_area_holder">
		<table id="content_area">
			<tr>
				<td class="images_are_blocks">
					<img src="gfx/logowanie/lewy_box_tytul.gif" alt=""><div id="content_area_content_leftmenu">
						<div style="height: 10px;"></div>
						Wygląda na to, drogi Użytkowniku, że zapomniałeś hasła. Poprawne wypełnienie pól powinno Ci pomóc. Dostaniesz e-maila zwrotnego na podany adres z świeżo wygenerowanym kodem, dzięki któremu po zalogowaniu się ustawisz swoje nowe hasło w ustawieniach profilu.
					</div><img src="gfx/lewy_box_dol.gif" alt="">
				</td>	
				<td id="content_area_content" rowspan="2">
					<div id="content_area_content_c">	<!-- glowny kontener -->							
					
					<form action="" method="POST">
					
					<div id="menu_cut">
						<table id="loginfield">
							<!-- if (!login_successful()) { -->
							<tr>
								<td colspan="2">
									<?php
									if($wyslano==1)
									{
									if($error=="") 
									{
									echo'<div class="green-message">Wysłano nowe hasło</div>';
									}
									else 
									{
									echo'<div class="red-message">'.$error.'</div>';
									}
									}
									?>
								</td>
							</tr>
							<!-- } -->
							<tr>
								<td>
									Login: 
								</td>
								<td>
									<input type="text" class="txt" name="login3" value="">
								</td>
							</tr>
							<tr>
								<td>
									E-mail
								</td>
								<td>
									<input type="text" class="txt" name="email3" value="">
								</td>
							</tr>
							<tr>
								<td>
								</td>
								<td>
									Po zatwierdzeniu danych zostanie
								</td>
							</tr>
							<tr>
								<td>
								</td>
								<td>
									wysłany e-mail zwrotny z nowym hasłem
								</td>
							</tr>
						</table>
						<input type="hidden" name="wyslano" value="1">
						<input type="submit" id="loguj_button" value="">
					</div>
					
					</form>
					</div>
				
				</td>
				<td class="images_are_blocks">
					<img src="gfx/glowna/prawy_box_tytul.gif" alt=""><div id="content_area_content_rightmenu">
						<?php
					   $news=LogicNews::getLatestX(4);
					   foreach($news as &$value)
					   {
					   echo'<div class="newsfuck">
							'.$value->title.'<br>
							<a href="news.php?id='.$value->id.'"><span class="czytajwiecej">Czytaj więcej</span></a>
						</div>';
					   }
					   ?>
					</div><img src="gfx/prawy_box_dol.gif" alt="">
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