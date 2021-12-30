<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
if(LogicSession::isLoggedIn()==false || LogicSession::can_edit_profile($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$del=@$_GET["del"];
$del=htmlspecialchars($del, ENT_COMPAT, 'UTF-8');
if($del!="")
 {
 $znal=false;
 $photos=LogicSession::getUser()->getPhotos();
 foreach($photos as &$value)
  {
  if($value->id==$del) 
   {
   $znal=true;
   $usuwanezdjecie=new ModelUserPhoto($value->id);
   }
  }
  
 if($znal==true) $usuwanezdjecie->delete();
 
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
	<link rel="stylesheet" type="text/css" href="css/galeria.css">
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
						
						<table id="galeria_tab">
						<?php
						$photos=LogicSession::getUser()->getPhotos();
						foreach($photos as &$value)
						{
						echo'<tr>
								<td class="image">
								<a href="privgal.php?id='.LogicSession::getUser()->id.'&photo='.$value->id.'"><img src="uploads/206x154/'.$value->gfx.'.jpg"></a>
								</td>
								<td class="spacer"></td>
								<td class="descbox">
									<div class="desc_div">
										<div class="desc_title">Opis</div>';
										if(strlen($value->description)>30)
										{
										$tym=substr($value->description,0,30);
										echo'<div class="desc_content">'.$tym.'...</div>';
										}
										else
										{
										echo'<div class="desc_content">'.$value->description.'</div>';
										}
										echo'
									</div>
				
									<div class="desc_div">';
									
									if($value->getComments()==false)
									{
									echo'	<div class="desc_title">Brak komentarzy</div>';
						
									}
									else
									{
									echo'<div class="desc_title">Ostatni komentarz</div>';
									$komentarze=$value->getComments();
									$User= new ModelUser($komentarze[0]->user);
									if(strlen($komentarze[0]->comment)>30)
									{
									$tym=substr($komentarze[0]->comment,0,30);
										echo'
										<div class="desc_content">'.$tym.'...</div>';
									}
									else
									{
										echo'
										<div class="desc_content">'.$komentarze[0]->comment.' </div>';
									}
									echo'<div class="desc_commenter">'.LogicHumanize::ago($komentarze[0]->when_added).' <span>'.$User->login.'</span></div>							
									</div>';
								    }
									echo'
								<div class="desc_delete_button"><a onclick="if (confirm(\'Usunąć?\')) window.location=\'galeria.php?id='.$id.'&del='.$value->id.'\'"></a></div>	
								</td>
							</tr>';
										
						}
						?>
						</table>

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