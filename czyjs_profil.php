<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');

if(LogicSession::isLoggedIn()==false || LogicUser::userExistsID($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$znajomy=@$_POST["znajomy"];
$znajomy=htmlspecialchars($znajomy, ENT_COMPAT, 'UTF-8');
$ja=LogicSession::getUser();
$user=new ModelUser($id);
if($znajomy!="")
{
if($znajomy=="dodaj") $ja->addZnajomy($user->id);
if($znajomy=="usun")  $ja->delZnajomy($user->id);
$ja=LogicSession::getUser();
$user=new ModelUser($id);
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
	<link rel="stylesheet" type="text/css" href="css/czyjs_profil.css">
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
						<?php
						echo'
						<div id="panel_left">
							<span class="nickname">'.$user->login.' - </span><span class="namesurname">'.$user->name.' '.$user->surname.'</span>
							<div class="city">'.$user->city.'</div>
							
							<div id="qaprywatna_galeria"><a href="privgal.php?id='.$user->id.'"></a></div>
							
							<form action="napisz.php?id='.$ja->id.'" method="post">
							<input type="submit" id="qanapisz_wiadomosc" value="" style="border: none;">
							<input type="hidden" name="target_person_id" value="'.$user->id.'">
							</form>';
							if(LogicSession::getUser()->id!=$id)
							{
							if($ja->isZnajomy($user->id)==false)
							{
							echo'
							<form action="" method="post">
							<input type="submit" id="qadodaj_znajomego" value="" style="border: none;">
							<input type="hidden" name="znajomy" value="dodaj">
							</form>
							';
							}
							else
							{
							echo'
							<form action="" method="post">
							<input type="submit" id="qausun_znajomego" value="" style="border: none;">
							<input type="hidden" name="znajomy" value="usun">
							</form>
							';
							}
							}
						echo'
						</div>
						
						<div id="panel_right">';
							echo'<a href="privgal.php?id='.$user->id.'">';
							if($user->hasAvatar()==false)
							{
							echo'<img src="gfx/awatar.jpg" alt=""></img>';
							}
							else echo'<img src="uploads/140x140/'.$user->avatar.'.jpg" alt=""></img>';
							echo'
							</a>
							<table id="statystyki">
								<tr>
									<td class="desc"><a href="#">Ranking: </a></td>
									<td class="val"><a href="#">'.$user->rankingPosition().'</a></td>
								</tr>
								<tr>
									<td class="desc"><a href="#">Punkty: </a></td>
									<td class="val"><a href="#">'.$user->points.'</a></td>
								</tr>
								<tr>
									<td class="desc"><a href="dodane.php?id='.$user->id.'">Bary: </a></td>
									<td class="val"><a href="dodane.php?id='.$user->id.'">'.$user->countBars().'</a></td>
								</tr>
								<tr>
									<td class="desc"><a href="st_bywalec_gdzie.php?id='.$user->id.'">St. bywalec: </a></td>
									<td class="val"><a href="st_bywalec_gdzie.php?id='.$user->id.'">'.$user->getBywalecCount().'</a></td>
								</tr>
								<tr>
									<td class="desc"><a href="znajomi.php?id='.$user->id.'">Znajomi: </a></td>
									<td class="val"><a href="znajomi.php?id='.$user->id.'">'.$user->Friends().'</a></td>
								</tr>
							</table>
						</div>

					</div>
							';
					?>
					<div class="clear"></div>
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