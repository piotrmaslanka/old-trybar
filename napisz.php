<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
$title=@$_POST["title"];
$title=htmlspecialchars($title, ENT_COMPAT, 'UTF-8');
$message=@$_POST["message"];
$message=htmlspecialchars($message, ENT_COMPAT, 'UTF-8');
$dokogo=@$_POST["target_person_id"];
$dokogo=htmlspecialchars($dokogo, ENT_COMPAT, 'UTF-8');
$wyslano=@$_POST["wyslano"];
$wyslano=htmlspecialchars($wyslano, ENT_COMPAT, 'UTF-8');
if(LogicSession::isLoggedIn()==false || LogicSession::can_edit_profile($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$znajomi=LogicSession::getUser()->getFriends();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>TryBAR</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/napisz.css">
	<link rel="stylesheet" type="text/css" href="css/shoutbox.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/fancybox.js"></script>
	<script type="text/javascript">
		function targetify(name, id) {
			$('#target_person').html(name);
			$('#target_person_id').val(id);
			$.fancybox.close();
		}
	</script>
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
	<div style="display: none;">
		<div id="friends_and_relatives">
		<?php
			foreach($znajomi as &$value)
			{
			$user2= new ModelUser($value->id);
			
		?>
			<div class="froline" onclick="targetify('<?php echo''.$user2->login.'';?>', '<?php echo''.$user2->id.'';?>')">
		<?php
			if($user2->hasAvatar()==false)
			{
			echo'<img src="gfx/awatar_50x50.jpg">'.$user2->login.'</div>';
			}
			else
			{
			echo'<img src="uploads/50x50/'.$user2->avatar.'.jpg">'.$user2->login.'</div>';
			}
			echo'<div class="sepline"></div>';		
			}
		?>
		</div>
	</div>


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
						
							Wybierz osobę docelową<br>
							<a href="#friends_and_relatives" id="target_person_href"><div id="target_person"><?php if($dokogo!="") {$user3= new ModelUser($dokogo); echo''.$user3->login.''; }?></div></a><br>
							<script type="text/javascript">
								$("#target_person_href").fancybox({
									'scrolling'		: 'no',
									'titleShow'		: false,
								});
							</script>
							Temat<br>
							<input type="text" id="title" maxlength="50" name="title" value="<?php echo''.$title.''; ?>">
							<br><br>
							Treść<br>
							<textarea id="msgcontent" name="message"><?php echo''.$message.''; ?></textarea>
							<input type="hidden" name="target_person_id" id="target_person_id" value="<?php echo''.$dokogo.''; ?>">
							<input type="hidden" name="wyslano" value="1">
							<?php
							if($wyslano==1)
							{
							if($title=="" || $message=="" || $dokogo=="")
							{
							echo'<div class="red-message">Wypełnij wszystkie pola</div>';
							}	
							if($title!="" && $message!="" && $dokogo!="")
							{
							if(LogicUser::userExistsId($dokogo)==true)
							{
							LogicSession::getUser()->sendMessage($dokogo,$title, $message);
							echo'<div class="green-message">Wysłano wiadomość</div>';
							}
							else echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
							}
							}
							?>
							<input id="b_wyslij" type="submit" value="">
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