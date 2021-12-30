<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
if(LogicSession::isLoggedIn()==false || LogicSession::can_edit_profile($id)==false || LogicUser::userExistsID($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
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
	<link rel="stylesheet" type="text/css" href="css/aktualnosci.css">
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
    <?php include "moduly/screening.php"; ?>
	<div id="haslo_przewodnie"></div>	<!-- twoj bar, twoja wodka -->
	<div id="top">
<iframe
src="http://www.facebook.com/plugins/like.php?href=http://www.facebook.com/pages/TryBAR/142549105817938
&layout=standard&show_faces=false& width=450&action=like&colorscheme=light&height=80"
scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; position: absolute; right: 10px; top: 10px; overflow: clip;
height:80px;" allowTransparency="true"></iframe> 
</div>
<div style="width: 1026px; height: 188px; cursor: pointer; margin-left: 37px; margin-right: 37px; background-image: url('gfx/trenuj24.jpg');" onclick="window.location='http://www.trenuje24.pl'"></div>
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
						<?php
	/* 	Podreczny Spis Aktualnosci	( jesli mowa o awatarach, fotach czy grafikach to poslugujemy sie ich id $arg. Jesli to gfx jest null to znaczy ze ich nie ma )
	
			atype	=	0:		Użytkownik $arg1 został stałym bywalem w barze $arg2		
			atype	=	1:		Użytkownik $arg1 dodał komentarz do baru $arg2				
			atype	=	2:		Użytkownik $arg1 dodał komentarz do zdjęcia (ModelUserPhoto $arg2) użytkownika $arg3
			atype	=	3:		Użytkownik $arg1 dodał bar $arg2 o zdjeciu gfx id $arg3	
			atype	=	4:		Użytkownik $arg1 dodał zdjęcie gfx $arg2 do baru $arg3	
			atype	=	5:		Użytkownik $arg1 dodał sobie fotkę (ModelUserPhoto $arg2)	
			atype	=	6:		Użytkownik $arg1 został znajomym usera $arg2
	*/
						$aktualnosci=$User->getAktualnosci(50);
						foreach($aktualnosci as &$value)
						{
							switch ($value->atype)
							{
								case 0: //atype	=	0:		Użytkownik $arg1 został stałym bywalem w barze $arg2	
									$osoba=new ModelUser($value->arg1);
									$bar=new ModelBar($value->arg2);
									echo'
									<div class="aktualnosci_separator"></div>
									<table class="aktualnosci_row"><tr>
									<td>
									<a href="czyjs_profil.php?id='.$osoba->id.'">';
									if($osoba->hasAvatar()==true)
									{
									echo'<img src="uploads/50x50/'.$osoba->avatar.'.jpg">';
									}
									else echo'<img src="gfx/awatar_50x50.jpg">';		
									echo'
									</a>
									</td>
									<td> Użytkownik <a href="czyjs_profil.php?id='.$osoba->id.'">'.$osoba->login.'</a> został stałym bywalcem w barze <a href="bar.php?id='.$bar->id.'">'.$bar->name.'</a><br>
									<span class="dwalatatemu">'.LogicHumanize::ago($value->when_added).'</span>
									</td>
									<td>
									<a href="bar.php?id='.$bar->id.'">';
									if($bar->doHasPhoto()==true)
									{
									echo'<img src="uploads/50x50/'.$bar->getFirstPhoto().'.jpg">';
									}
									else echo'<img src="gfx/miniatura_bar_brak_50x50.jpg">';
									echo'
									</a>
									</td>
									</tr></table>
									';
									break;
								case 1: //atype	=	1:		Użytkownik $arg1 dodał komentarz do baru $arg2	
									$osoba=new ModelUser($value->arg1);
									$bar=new ModelBar($value->arg2);
									echo'
									<div class="aktualnosci_separator"></div>
									<table class="aktualnosci_row"><tr>
									<td>
									<a href="czyjs_profil.php?id='.$osoba->id.'">';
									if($osoba->hasAvatar()==true)
									{
									echo'<img src="uploads/50x50/'.$osoba->avatar.'.jpg">';
									}
									else echo'<img src="gfx/awatar_50x50.jpg">';		
									echo'
									</a>
									</td>
									<td> Użytkownik <a href="czyjs_profil.php?id='.$osoba->id.'">'.$osoba->login.'</a> dodał komentarz do baru <a href="bar.php?id='.$bar->id.'">'.$bar->name.'</a><br>
									<span class="dwalatatemu">'.LogicHumanize::ago($value->when_added).'</span>
									</td>
									<td>
									<a href="bar.php?id='.$bar->id.'">';
									if($bar->doHasPhoto()==true)
									{
									echo'<img src="uploads/50x50/'.$bar->getFirstPhoto().'.jpg">';
									}
									else echo'<img src="gfx/miniatura_bar_brak_50x50.jpg">';
									echo'
									</a>
									</td>
									</tr></table>';
									break;
								case 2: //		atype	=	2:		Użytkownik $arg1 dodał komentarz do zdjęcia (ModelUserPhoto $arg2) użytkownika $arg3
									$osoba1=new ModelUser($value->arg1);
									$photo=new ModelUserPhoto($value->arg2);
									$osoba2=new ModelUser($value->arg3);
									echo'
									<div class="aktualnosci_separator"></div>
									<table class="aktualnosci_row"><tr>
									<td>
									<a href="czyjs_profil.php?id='.$osoba1->id.'">';
									if($osoba1->hasAvatar()==true)
									{
									echo'<img src="uploads/50x50/'.$osoba1->avatar.'.jpg">';
									}
									else echo'<img src="gfx/awatar_50x50.jpg">';
									echo'
									</a>
									</td>
									<td> Użytkownik <a href="czyjs_profil.php?id='.$osoba1->id.'">'.$osoba1->login.'</a> dodał komentarz do zdjęcia użytkownika <a href="czyjs_profil.php?id='.$osoba2->id.'">'.$osoba2->login.'</a><br>
									<span class="dwalatatemu">'.LogicHumanize::ago($value->when_added).'</span>
									</td>
									<td>
									<a href="privgal.php?id='.$osoba2->id.'&photo='.$photo->id.'"><img src="uploads/50x50/'.$photo->gfx.'.jpg"></a>
									</a>
									</td>
									</tr></table>
									';
									break;
								case 3: //Użytkownik $arg1 dodał bar $arg2 o zdjeciu gfx id $arg3	
									$osoba1=new ModelUser($value->arg1);
									$bar=new ModelBar($value->arg2);
									echo'
									<div class="aktualnosci_separator"></div>
									<table class="aktualnosci_row"><tr>
									<td>
									<a href="czyjs_profil.php?id='.$osoba1->id.'">';
									if($osoba1->hasAvatar()==true)
									{
									echo'<img src="uploads/50x50/'.$osoba1->avatar.'.jpg">';
									}
									else echo'<img src="gfx/awatar_50x50.jpg">';
									echo'
									</a>
									</td>
									<td> Użytkownik <a href="czyjs_profil.php?id='.$osoba1->id.'">'.$osoba1->login.'</a> dodał bar <a href="bar.php?id='.$bar->id.'">'.$bar->name.'</a> <br>
									<span class="dwalatatemu">'.LogicHumanize::ago($value->when_added).'</span>
									</td>
									<td>
									<a href="bar.php?id='.$bar->id.'">';	
									if($value->arg3!=0)
									{
									echo'<img src="uploads/50x50/'.$value->arg3.'.jpg">';
									}
									else echo'<img src="gfx/miniatura_bar_brak_50x50.jpg">';
									echo'
									</a>
									</td>
									</tr></table>
									';
									break;
								case 4: //atype	=	4:		Użytkownik $arg1 dodał zdjęcie ______gfx $arg2_________ do baru $arg3	
									$osoba=new ModelUser($value->arg1);
									$identyfikator_gfx_zdjecia_ktore_do_baru_dodal_user = $value->arg2;
									$bar = new ModelBar($value->arg3);
									echo'
									<div class="aktualnosci_separator"></div>
									<table class="aktualnosci_row"><tr>
									<td>
									<a href="czyjs_profil.php?id='.$osoba->id.'">';
									if($osoba->hasAvatar()==true)
									{
									echo'<img src="uploads/50x50/'.$osoba->avatar.'.jpg">';
									}
									else echo'<img src="gfx/awatar_50x50.jpg">';		
									echo'
									</a>
									</td>
									<td> Użytkownik <a href="czyjs_profil.php?id='.$osoba->id.'">'.$osoba->login.'</a> dodał zdjęcie do baru <a href="bar.php?id='.$bar->id.'">'.$bar->name.'</a><br>
									<span class="dwalatatemu">'.LogicHumanize::ago($value->when_added).'</span>
									</td>
									<td>
									<a href="bar.php?id='.$bar->id.'">
									<img src="uploads/50x50/'.$identyfikator_gfx_zdjecia_ktore_do_baru_dodal_user.'.jpg">
									</a>
									</td>
									</tr></table>
									';
									break;
								case 5: //Użytkownik $arg1 dodał sobie fotkę (ModelUserPhoto $arg2)	
									$osoba=new ModelUser($value->arg1);
									$photo=new ModelUserPhoto($value->arg2);
									echo'
									<div class="aktualnosci_separator"></div>
									<table class="aktualnosci_row"><tr>
									<td>
									<a href="czyjs_profil.php?id='.$osoba->id.'">';
									if($osoba->hasAvatar()==true)
									{
									echo'<img src="uploads/50x50/'.$osoba->avatar.'.jpg">';
									}
									else echo'<img src="gfx/awatar_50x50.jpg">';		
									echo'
									</a>
									</td>
									<td> Użytkownik <a href="czyjs_profil.php?id='.$osoba->id.'">'.$osoba->login.'</a> dodał sobie zdjęcie<br>
									<span class="dwalatatemu">'.LogicHumanize::ago($value->when_added).'</span>
									</td>
									<td>
									<a href="privgal.php?id='.$osoba->id.'&photo='.$photo->id.'">
									<img src="uploads/50x50/'.$photo->gfx.'.jpg">
									</a>
									</td>
									</tr></table>
									';
									break;
								case 6: //Użytkownik $arg1 został znajomym usera $arg2
									$osoba1=new ModelUser($value->arg1);
									$osoba2=new ModelUser($value->arg2);
									echo'
									<div class="aktualnosci_separator"></div>
									<table class="aktualnosci_row"><tr>
									<td>
									<a href="czyjs_profil.php?id='.$osoba->id.'">';
									if($osoba1->hasAvatar()==true)
									{
									echo'<img src="uploads/50x50/'.$osoba1->avatar.'.jpg">';
									}
									else echo'<img src="gfx/awatar_50x50.jpg">';
									echo'
									</a>
									</td>
									<td> Użytkownik <a href="czyjs_profil.php?id='.$osoba->id.'">'.$osoba1->login.'</a> został znajomym użytkownika <a href="czyjs_profil.php?id='.$osoba2->id.'">'.$osoba2->login.'</a><br>
									<span class="dwalatatemu">'.LogicHumanize::ago($value->when_added).'</span>
									</td>
									<td>
									<a href="czyjs_profil.php?id='.$osoba2->id.'">';	
									if($osoba2->hasAvatar()==true)
									{
									echo'<img src="uploads/50x50/'.$osoba2->avatar.'.jpg">';
									}
									else echo'<img src="gfx/awatar_50x50.jpg">';
									echo'
									</a>
									</td>
									</tr></table>
									';
									break;
                                case 7:
									$osoba=new ModelUser($value->arg1);
									$nagroda=new ModelPrize($value->arg2);
									echo'
									<div class="aktualnosci_separator"></div>
									<table class="aktualnosci_row"><tr>
									<td>
									<a href="czyjs_profil.php?id='.$osoba->id.'">';
									if($osoba->hasAvatar()==true)
									{
									echo'<img src="uploads/50x50/'.$osoba->avatar.'.jpg">';
									}
									else echo'<img src="gfx/awatar_50x50.jpg">';
									echo'
									</a>
									</td>
									<td> Użytkownik <a href="czyjs_profil.php?id='.$osoba->id.'">'.$osoba->login.'</a> wybrał nagrodę <a href="'.$nagroda->url.'">'.$nagroda->name.'</a><br>
									<span class="dwalatatemu">'.LogicHumanize::ago($value->when_added).'</span>
									</td>
									<td>
									<a href="'.$nagroda->url.'">';	
									echo'<img src="uploads/50x50/'.$nagroda->gfx.'.jpg">';
                                    echo '
									</a>
									</td>
									</tr></table>
									';
									break;
                                    
							}
						}
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
?>