<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$Topbary=LogicBars::topX(10);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>TryBAR</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Portal prezentuje spis barów, pubów, restauracji i klubów z całej Polski. Zdjęcia, oceny i komentarze wystawiane przez użytkowników pozwalają na łatwe zdecydowanie, który lokal warto odwiedzić, a który nie.">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/scroll.js"></script>	
	<script type="text/javascript" src="js/unlimitedbarworks.js"></script>
	<script type="text/javascript" src="js/online.js"></script>
	
	<script type="text/javascript">
		$(window).load(function() {
			$("div#photobox").smoothDivScroll({autoScroll:"always",
											   autoScrollInterval:30,
											   autoScrollStep:2, 
											   autoScrollDirection: "endlessloopright"});
											   
			$("div#photobox_left").bind("mouseover", function() { $("div#photobox").smoothDivScroll("option", {autoScrollDirection:"endlessloopright"}); });
			$("div#photobox_right").bind("mouseover", function() { $("div#photobox").smoothDivScroll("option", {autoScrollDirection:"endlessloopleft"}); });
			$("div#photobox_mid").bind("mouseover", function() { $("div#photobox").smoothDivScroll("stopAutoScroll"); });
			$("div#photobox_mid").bind("mouseout", function() { $("div#photobox").smoothDivScroll("startAutoScroll"); });
			});
		function shows(j) {
			var i=0;
			for(i=0;i<6;i++) $("#supersection"+i).hide();
			$("#supersection"+j).show();			
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
    <?php include "moduly/screening.php"; ?>
	<div id="haslo_przewodnie"></div>	<!-- twoj bar, twoja wodka -->
	<div id="top">
<iframe
src="http://www.facebook.com/plugins/like.php?href=http://www.facebook.com/pages/TryBAR/142549105817938
&layout=standard&show_faces=false& width=450&action=like&colorscheme=light&height=80"
scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; position: absolute; right: 10px; top: 10px; overflow: clip;
height:80px;" allowTransparency="true"></iframe> 
	</div>
<?php $page=0; include 'moduly/banner.php'; ?>    
	<div id="menubar">					<!-- glowne menu - to brazowe - szkoda ze bez wodki -->
		<?php
			include "moduly/nawigacja.php";
			?>

	
	<div id="welcomebar">			<!-- Witamy na TryBAR / Witaj Ojou-sama poziomy pasek na cala dlugosc strony -->
		<img src="gfx/mid_border.png" style="position: absolute; left: 0; top: 0;" alt="">
		<img src="gfx/mid_border.png" style="position: absolute; right: 0; top: 0;" alt="">

		<?php
		if(LogicSession::isLoggedIn()==true)
		{
			echo'	<div id="welcomebar_rgt"></div>';
		}
		else
		{
		?>
				<div id="welcomebar_rgt" style="background: url('gfx/nie_masz_jeszcze_konta_sample.gif') repeat-x;">
					
		<?php
		linki_rejstracyjne();
		echo '</div>';
		} 
		if(LogicSession::isLoggedIn()==true)
		{
		?>
				<div id="welcomebar_mid" style="background: url('gfx/news/belka_sample.png') repeat-x;">
		<?php
		}
		else 
		{ 
		?>
			<div id="welcomebar_mid" style="background: url('gfx/news/belka.png')"> 
		<?php	
		} 
		?>
			
		</div>
		<div id="welcomebar_lft">
		</div>
	</div>
	
	<div id="photobox">
		<div id="photobox_right"></div>		
		<div id="photobox_left"></div>
		<div id="photobox_mid">
			<div id="scroll" class="scrollWrapper">
				<div id="scrollable" class="scrollableArea">
					<?php
					$zdjecia=LogicNews::getPicturesMainpageEx();
					foreach($zdjecia as &$value)
					{
					echo'<div><a href="bar.php?id='.$value[1].'"><img src="uploads/stath132/'.$value[0].'.jpg"></a></div>';
					}				
					/*<!-- radze drugi raz powtorzyc sekcje z obrazkami. dlugosc scrollable musi byc przynajmniej 2x 
						dlugosc photobox_mid zeby bylo co scrollowac i z czego podstawiac nieskonczona petle -->*/
					foreach($zdjecia as &$value)
					{
					echo'<div><a href="bar.php?id='.$value[1].'"><img src="uploads/stath132/'.$value[0].'.jpg"></a></div>';
					}	
					?>
				</div>
			</div>
		</div>				

		<div class="clear"></div>
		<img src="gfx/glowna/photobox_border.png" style="position: absolute; left: 0; top: 0;"></img>
		<img src="gfx/glowna/photobox_border.png" style="position: absolute; right: 0; top: 0;"></img>
	</div>
	
	<div class="positioning_handle_envelope">	<!-- UWAGA ZACZYNA SIE KOSZMAR PROJEKTANTA -->
		<!-- DISCLAIMER: Krytykuj dopiero jak ZROBISZ to lepiej. Nie lepiej w kontekscie tego ze napiszesz ta
			strona od poczatku w ten sposob ze zadna z jej czesci nie bedzie mogla byc uzyta na innej stronie. -->
		<div class="positioning_handle">
					<!-- zmieniajac tutaj left w miare bezbolesnie przesuwasz w lewo/prawo -->
			<img id="sw1" onmouseover="startscroll(1)" onmouseout="stopscroll(1)" src="gfx/glowna/buton_baza.png" style="top: 96px; left: 19px;" alt="" onclick="shows(1)">
			<img id="sw2" onmouseover="startscroll(2)" onmouseout="stopscroll(2)" src="gfx/glowna/button_miejsce.png" style="top: 147px; left: 19px;" alt="" onclick="shows(2)">
			<img id="sw3" onmouseover="startscroll(3)" onmouseout="stopscroll(3)" src="gfx/glowna/button_wyrazenie.png" style="top: 196px; left: 19px;" alt="" onclick="shows(3)">

			<img id="sw4" onmouseover="startscroll(4)" onmouseout="stopscroll(4)" src="gfx/glowna/button_faq.png" style="top: 264px; left: 19px;" alt="" onclick="shows(4)">
	<!-- to jest tak zrobione, bo Słowo Boga (W3C) mówi że position: relative jest niezdefiniowane w tabeli -->
		</div>
	</div>										<!-- KONIEC KOSZMARU PROJEKTANTA -->

	<div id="content_area_holder">
		<table id="content_area">
			<tr>
				<td id="content_area_padtop_leftmenu"></td>
				<td id="content_area_padtop_content"></td>
				<td id="content_area_padtop_rightmenu"></td>
			</tr>
			
			<tr>
				<td class="images_are_blocks">
					<img src="gfx/glowna/lewy_box_tytul.gif" alt=""><div id="content_area_content_leftmenu">
						<img src="gfx/glowna/lewy_box_kreska.gif" style="position: static; padding-top: 160px;" alt="">
					</div><img src="gfx/lewy_box_dol.gif" alt="">
				</td>	
				<td id="content_area_content" rowspan="2">
					<div id="content_area_content_c">	<!-- glowny kontener -->							
					
							<div id="supersection0">
									<!--------------------------------------------- START SEKCJI ------------------------------------------------------------------ -->
											<div id="top_bary">
												<div id="top_bary_label">Top bary</div>
												<?php
							for($i=0;$i<count($Topbary);$i++)
							{
							$bar=new ModelBar($Topbary[$i]);
							echo'<div onmouseover="show_bar('.($i+1).')"><a href="bar.php?id='.$bar->id.'">'.$bar->name.'-'.($i+1).'</a></div>';
							}
							?>
											</div>

											<?php
						for($i=0;$i<count($Topbary);$i++)
						{
						$bar=new ModelBar($Topbary[$i]);
						if($i==0)
						{
						echo'<div id="bar'.($i+1).'" class="opisbaru">';
						}
						else echo'<div id="bar'.($i+1).'" class="invisible opisbaru">';
						echo'	
							<div id="barname">'.$bar->name.'</div>
						';	
							
						if($bar->doHasPhoto()==true)
						{
						echo'<img src="uploads/180x132/'.$bar->getFirstPhoto().'.jpg"><br>';
						}
						else echo'<img src="gfx/miniatura_bar_brak_180x132.jpg"><br>';
						echo'						
							<span class="bold">'.$bar->city.'</span><br><br>
							'.$bar->description.'
						</div>
						';
						}
						?>
											</div>
									<!----------------------------------------------------   KONIEC SEKCJI --------------------------------------------------- -->
							</div>	

							<div id="supersection1" style="display: none;" class="supersection">
									<!--------------------------------------------- START SEKCJI ------------------------------------------------------------------ -->
								<p class="ssfota"><img src="gfx/intro/baza.jpg" alt="Baza barów"></p>
								Celem portalu jest stworzenie bazy barów z całej Polski. Bazy pełnej wyczerpujących informacji, a nie zdawkowych danych, które można znaleźć w lokalnych serwisach. Dzięki zdjęciom, szczegółowym ocenom każdego aspektu lokali, komentarzom, opisom a także podstawowymi informacjami każdy będzie mógł dobrze zaplanować swoje weekendowe wieczory!						
									<!----------------------------------------------------   KONIEC SEKCJI --------------------------------------------------- -->
							</div>
							
							<div id="supersection2" style="display: none;" class="supersection">
									<!--------------------------------------------- START SEKCJI ------------------------------------------------------------------ -->
								<img src="gfx/intro/mszz.jpg" style="float: right; margin-left: 20px;" alt="Miejsce do spotkań ze znajomymi">
								By oddać smak nocnego trybu życia portal Trybar oferuje również funkcje umożliwiające kontakt z innymi użytkownikami. Dzięki shoutboxowi każdy może porozmawiać ze swoimi znajomymi, a prywatne wiadomości pozwalają zostawić wiadomość znajomym lub zaczepić innego stałego bywalca ulubionego lokalu :)
									<!----------------------------------------------------   KONIEC SEKCJI --------------------------------------------------- -->
							</div>

							<div id="supersection3" style="display: none;" class="supersection">
									<!--------------------------------------------- START SEKCJI ------------------------------------------------------------------ -->
								<p class="ssfota"><img src="gfx/intro/opinia.jpg" alt="Miejsce do spotkań ze znajomymi"></p>
								Spędziłeś wieczór w świetnym lokalu? A może personel zachowywał się w stosunku do Ciebie w sposób niedopuszczalny? Masz swój ulubiony bar, a nie ma go w naszej bazie? Czy może wbrew złym opiniom jakiegoś baru poszedłeś do niego i się mile rozczarowałeś? Bez względu na to co chcesz napisać na temat lokali do których uczęszczasz, Trybar jest do tego najlepszym miejscem. Napisz co myślisz lub opisz swoje weekendowe przygody i zobacz, co o tym myślą inni :)
									<!----------------------------------------------------   KONIEC SEKCJI --------------------------------------------------- -->
							</div>

							<div id="supersection4" style="display: none;" class="supersection">
									<!--------------------------------------------- START SEKCJI ------------------------------------------------------------------ -->
								<?php
								include "moduly/faq.php";
								?>
									<!----------------------------------------------------   KONIEC SEKCJI --------------------------------------------------- -->
							</div>
					</div>
				</td>
				<td class="images_are_blocks">
					<img src="gfx/glowna/prawy_box_tytul.gif" alt=""><div id="content_area_content_rightmenu">
						<?php
					   $news=LogicNews::getLatestX(4);
					   foreach($news as &$value)
					   {
					   echo'<div class="newsfuck">
							'.str_replace(' ', '&nbsp;', $value->title).'<br>
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