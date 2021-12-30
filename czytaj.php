<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
if(LogicSession::isLoggedIn()==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
 $wiadomosc=New ModelMessage($id);
 if($wiadomosc->receiver!=LogicSession::getUser()->id || LogicMessage::existsId($id)==false)
 {
 echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=odbiorcza.php?id='.LogicSession::getUser()->id.'&strona=1">';
 }
 else
  {
  $wiadomosc->markReaded();
  $del=@$_POST["del"];
  $del=htmlspecialchars($del, ENT_COMPAT, 'UTF-8');
  if($del==1)
  {
  $wiadomosc->delete();
  echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=odbiorcza.php?id='.LogicSession::getUser()->id.'&strona=1">';
  }
  
  $znajomy=@$_GET["znajomy"];
  $znajomy=htmlspecialchars($znajomy, ENT_COMPAT, 'UTF-8');
  
  if($znajomy=="akceptuj")
  {
  $wiadomosc->acceptInvitation();
  echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=odbiorcza.php?id='.LogicSession::getUser()->id.'&strona=1">';
  }
  
  if($znajomy=="odrzuc")
  {
  $wiadomosc->rejectInvitation();
  echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=odbiorcza.php?id='.LogicSession::getUser()->id.'&strona=1">';
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
	<link rel="stylesheet" type="text/css" href="css/czytaj.css">
	<link rel="stylesheet" type="text/css" href="css/shoutbox.css">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/selectbox.js"></script>
	<script type="text/javascript" src="js/shoutbox.js"></script>
	<script type="text/javascript" src="js/online.js"></script>
	
			<script type="text/javascript">		
		function zapytanie() 
		{
		var pytanie =confirm('Czy na pewno usunąć?');
		if (pytanie) $('#usun2').submit();
		}
	</script>
	
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
		<img src="gfx/mid_border.png" style="position: absolute; left: 0; top: 0;"
alt="">
		<img src="gfx/mid_border.png" style="position: absolute; right: 0; top:
0;" alt="">
		<table id="content_area">
			<tr>
				<td id="content_area_padtop_leftmenu" style="vertical-align: middle;">
					<img src="gfx/aktualnosci/moje_konto.gif" alt="Moje konto">
				</td>
				<td id="content_area_content" rowspan="3">

					<div id="content_area_content_c">	<!-- glowny kontener -->
						<div id="ie_safeguard"><!-- straznik przeciwko idiotyzmom Internet Explorera --></div>
							<?php
							$odkogo=New ModelUser($wiadomosc->sender);
							echo'
							<div class="line"></div>
							<span class="titl"><span class="bold">Od: </span><a href="czyjs_profil.php?id='.$odkogo->id.'">'.$odkogo->login.'</a></span>
							<div class="line"></div>
							<span class="titl"><span class="bold">Temat: </span>'.$wiadomosc->title.'</span>
							<div class="line"></div>
							<span class="titl bold">Treść:</span>

							<div class="cont">';
							if($wiadomosc->getUserAsking()==false)
							{
							echo'	'.$wiadomosc->content.'';
							}
							else
							{
							echo'
							<p style="text-align: center;">
							Jeśli chcesz zaakceptować zaproszenie do znajomych kliknij poniższy link<br>
							<a href="czytaj.php?id='.$wiadomosc->id.'&znajomy=akceptuj" id="link_akceptuj">Akceptuj</a><br>
							<br>
							Jeśli chcesz odrzucić zaproszenie do znajomych kliknij poniższy link<br>
							<a href="czytaj.php?id='.$wiadomosc->id.'&znajomy=odrzuc" id="link_odrzuc">Odrzuć</a><br>
							</p>
							';
							}
							echo'
							</div>
							<div class="line"></div>

							<div id="b_spacer"></div>
							<table style="margin-left: 0px; margin-right: auto;">
							<tr>
							
							<td>
							<form action="napisz.php?id='.LogicSession::getUser()->id.'" method="post">
							<input type="submit" id="b_odpisz" value="" style="border: none;">
							<input type="hidden" name="target_person_id" value="'.$wiadomosc->sender.'">
							<input type="hidden" name="title" value="re: '.$wiadomosc->title.'">
							</form>
							</td>
							
							<td>
							<form id="usun2" action="" method="post">
							<input onclick="zapytanie()" type="submit" id="b_usun" value="" style="border: none; onclick="zapytanie"">
							<input type="hidden" name="del" value="1">
							</form>
							</td>
							
							</tr>
							</table>
							';
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
}
?>