<?php 
include "sql.php";
include "logic/logic.php";
LogicSession::start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>GigaBajt</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/kontakt.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/selectbox.js"></script>
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
					<img src="gfx/kontakt/kontakt.gif" alt="Dodawanie">
				</td>
				<td id="content_area_content" rowspan="3">
					<div id="content_area_content_c">	<!-- glowny kontener -->									
						<div id="ie_safeguard"><!-- straznik przeciwko idiotyzmom Internet Explorera --></div>
						
						<form action="#" method="post" enctype="multipart/form-data">
							
							E-mail kontaktowy<br>
							<input type="text" id="email" name="email"><br>
							Temat<br>
							<input type="text" id="title" name="title">
							<br><br>
							Treść<br>
							<textarea id="msgcontent" name="tresc"></textarea>
							
							<br>
							<input id="b_wyslij" type="submit" value="">
							<input type="hidden" name="wyslij" value="1">
							<?php
							$wyslij=@$_POST["wyslij"];
							$wyslij=htmlspecialchars($wyslij, ENT_COMPAT, 'UTF-8');
							if($wyslij==1)
							{
							$title=@$_POST["title"];
							$title=htmlspecialchars($title, ENT_COMPAT, 'UTF-8');
							$email=@$_POST["email"];
							$email=htmlspecialchars($email, ENT_COMPAT, 'UTF-8');
							$tresc=@$_POST["tresc"];
							$tresc=htmlspecialchars($tresc, ENT_COMPAT, 'UTF-8');
							if(($tresc!="" && $title!="") && $email!="")
							{
							LogicContact::contact($email, $title, $tresc);
							echo'<div class="green-message">Twoja wiadomość została wysyłana</div>';
							}
							else echo'<div class="red-message">Wypełnij obydwa pola aby wysłać wiadomość</div>';
							}
							?>
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
						Masz jakieś sugestie, uwagi, propozycje? A może chcesz po prostu z nami korespondować? Cokolwiek chcesz nam napisać, nie krępuj się i daj nam znać o sobie :)
					</div>
					</div><img src="gfx/lewy_box_dol.gif" alt="">
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