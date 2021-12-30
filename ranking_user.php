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
$strona=@$_GET["strona"];
$strona=htmlspecialchars($strona, ENT_COMPAT, 'UTF-8');
$blad=false;
$il=LogicUser::rankingUserCount();
$strony=ceil($il/MAX_RANKING_PER_PAGE);

if($strona<=0) {echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';$blad=true;}
 else
 {
 if($strony==0)
 {
 if($strona!=1) {echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';$blad=true;}
 }
 else
 {
 if($strona>$strony) {echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';$blad=true;}
 else if($strona=="") {echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';$blad=true;}
 } 
 }
}
if($blad==false)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>TryBAR</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/ranking_user.css">
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
<?php $page=1; include 'moduly/banner.php'; ?>    
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
						
						<div id="content_area_limiter">
							<?php
							$userzy=LogicUser::ranking($strona);
							$pozycja=($strona*MAX_RANKING_PER_PAGE)-(MAX_RANKING_PER_PAGE-1);
							foreach($userzy as &$value)
							{			
							$user=new ModelUser($value);	
							echo'
							<div class="line"></div>
							<div class="msg">
								<div class="indeks">'.$pozycja.'</div>
								<div class="fota">
									<a href="czyjs_profil.php?id='.$user->id.'">';
										
									if($user->hasAvatar()==false)
									{
									echo'<img src="gfx/awatar_50x50.jpg">';
									}
									else echo'<img src="uploads/50x50/'.$user->avatar.'.jpg">';
									echo'
									</a>
								</div>
								<div class="ocena">'.$user->points.'</div>
								<div class="nazwa">
									<div class="username">
										<a href="czyjs_profil.php?id='.$user->id.'">'.$user->login.'</a>
									</div>
									<div class="townname">'.$user->city.'</div>
								</div>
							</div>';
							$pozycja=$pozycja+1;
							}
							?>
							
						<div id="bt_stuff">
							<div id="pagina">
							<?php
							$nastepna=$strona+1;
							$poprzednia=$strona-1;
							$kropki=false;
							if($strona>1)
							{
							echo'<a href="ranking_user.php?strona='.$poprzednia.'"><div class="left"></div></a>';
							echo'<a href="ranking_user.php?strona=1"><div class="bx">1</div></a>';
							if($kropki==false)
							{
							echo'<div class="mdot">...</div>';
							$kropki=true;
							}
							}
							echo'<a href="ranking_user.php?strona='.$strona.'"><div class="bx">'.$strona.'</div></a>';	
							if($strony>$strona)
							{
							if($kropki==false)
							{
							echo'<div class="mdot">...</div>';
							$kropki=true;
							}
							echo'<a href="ranking_user.php?strona='.$strony.'"><div class="bx">'.$strony.'</div></a>';
							echo'<a href="ranking_user.php?strona='.$nastepna.'"><div class="right"></div></a>';
							}
							?>
							</div>
						</div>
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