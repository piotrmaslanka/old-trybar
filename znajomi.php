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
if(LogicSession::can_edit_profile($id)==true)
{
$del=@$_GET["del"];
$del=htmlspecialchars($del, ENT_COMPAT, 'UTF-8');
$usun=false;
if($del!="")
 {
 $user=New ModelUser($id);
 $friends=$user->getFriends();
 foreach($friends as &$znajomy)
  {
  if($znajomy->id==$del) $usun=true;
  }
 if($usun==true) LogicSession::getUser()->delZnajomy($del);
 }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>TryBAR</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/znajomi.css">
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
						

						<div id="content_area_limiter">
							<?php
							$user=New ModelUser($id);
							$friends=$user->getFriends();
							if(count($friends)==0)
							{
							echo'<div class="center-message">Ten użytkownik nie ma znajomych</div>';
							}
							else
							{
							foreach($friends as &$znajomy)
							{
							echo'
							<div class="line"></div>
							<div class="msg">
								<div class="fota">
									<a href="czyjs_profil.php?id='.$znajomy->id.'">';
										if($znajomy->hasAvatar()==false)
										{
										echo'<img src="gfx/awatar.jpg" alt=""></img>';
										}
										else echo'<img src="uploads/140x140/'.$znajomy->avatar.'.jpg" alt=""></img>';
									echo'
									</a>
								</div>
								<div class="nazwa">
									<div class="username">
										<a href="czyjs_profil.php?id='.$znajomy->id.'">'.$znajomy->login.'</a>
									</div>
									<div class="townname">'.$znajomy->city.'</div>
								</div>';
								if(LogicSession::can_edit_profile($id)==true)
								{
								echo'
								<div class="guziczek">
									<div><a href="znajomi.php?id='.$id.'&del='.$znajomy->id.'"></a></div>
								</div>';
								}
							echo'
							</div>
							';
							}
							}
							?>
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