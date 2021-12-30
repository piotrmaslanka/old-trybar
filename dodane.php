<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
if(LogicSession::isLoggedIn()==false || LogicUser::userExistsId($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
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
	<link rel="stylesheet" type="text/css" href="css/dodane.css">
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
	
	<div id="welcomebar">			<!-- Witamy na TryBAR / Witaj Ojou-sama poziomy pasek na cala dlugosc strony -->
		<img src="gfx/mid_border.png" style="position: absolute; left: 0; top: 0;" alt="">
		<img src="gfx/mid_border.png" style="position: absolute; right: 0; top: 0;" alt="">
		
				<div id="welcomebar_rgt"></div>
		<div id="welcomebar_mid" style="background: url('gfx/dodane/dodane.jpg');"></div>
		
		<div id="welcomebar_lft"></div>
	</div>

	<div id="content_area_holder">
		<table id="content_area">
			<tr>
				<td class="images_are_blocks">
					<div id="acc_box_header">
					<?php
						include "moduly/dane_profil.php"
					?>

				</td>					<td id="content_area_content" rowspan="2">
					<div id="content_area_content_c">	<!-- glowny kontener -->							
					<form action="" method="">
					
					<div id="box_z_barami">
					<?php
					$user=new ModelUser($id);
					$bary=$user->getAddedBars();
					$licznik=1;
					foreach($bary as &$value)
					{
					echo'
					<div class="s_bar_compact">
							<div class="s_bar_box">
								<div class="s_bar_photo">
								<a href="bar.php?id='.$value->id.'">
								';
								if($value->doHasPhoto()==true) 
								{
								echo'<img src="uploads/167x77/'.$value->getFirstPhoto().'.jpg">';
								
								}
								else echo'<img src="gfx/miniatura_bar_brak.jpg">';
								echo'
								</a>	
								</div>
								
								<div class="s_bar_name">
									'.$value->name.'
								</div>						
								
								<div class="s_bar_grading">
									Średnia ocen '.$value->avgMark().'<br>
									Stali bywalcy '.$value->getBywalcyAmount().'
								</div>
							</div>';
							if(LogicSession::getUser()->id==$id) echo'<div class="s_bar_manage"><a href="zarzadzaj.php?id='.$value->id.'"></a></div>';	
						echo'</div>';
					if(($licznik%2)==0) echo'<br><br>';
					$licznik=$licznik+1;
					}
					?>
							
					</form>
					</div>
				
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