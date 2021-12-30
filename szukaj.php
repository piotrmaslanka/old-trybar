<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$region=@$_GET["region"];
$region=htmlspecialchars($region, ENT_COMPAT, 'UTF-8');
$city=@$_GET["city"];
$city=htmlspecialchars($city, ENT_COMPAT, 'UTF-8');
$RegionValue=array('Dolnośląskie',
										 'Kujawsko-Pomorskie',
										 'Lubelskie',
										 'Lubuskie',
										 'Łódzkie',
										 'Małopolskie',
										 'Mazowieckie',
										 'Opolskie',
										 'Podkarpackie',
										 'Podlaskie',
										 'Pomorskie',
										 'Śląskie',
										 'Świętokrzyskie',
										 'Warmińsko-Mazurskie',
										 'Wielkopolskie',
										 'Zachodniopomorskie');
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
	<link rel="stylesheet" type="text/css" href="css/szukaj.css">
	
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
<?php include "moduly/screening.php"; ?>
	<div id="haslo_przewodnie"></div>	<!-- twoj bar, twoja wodka -->
	<div id="top">
<iframe
src="http://www.facebook.com/plugins/like.php?href=http://www.facebook.com/pages/TryBAR/142549105817938
&layout=standard&show_faces=false& width=450&action=like&colorscheme=light&height=80"
scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; position: absolute; right: 10px; top: 10px; overflow: clip;
height:80px;" allowTransparency="true"></iframe> 
</div>
<?php $page=2; include 'moduly/banner.php'; ?>    
	<div id="menubar">					<!-- glowne menu - to brazowe - szkoda ze bez wodki -->
		<?php
			include "moduly/nawigacja.php";
		?>
    </div> 
	
	<div id="welcomebar">			<!-- Witamy na TryBAR / Witaj Ojou-sama poziomy pasek na cala dlugosc strony -->
		<img src="gfx/mid_border.png" style="position: absolute; left: 0; top: 0;" alt="">
		<img src="gfx/mid_border.png" style="position: absolute; right: 0; top: 0;" alt="">
		<?php
		if(LogicSession::isLoggedIn()==true)
		{
			echo'<div id="welcomebar_rgt"></div>';
		}
		else
		{
		?>
			<div id="welcomebar_rgt" style="background: url('gfx/nie_masz_jeszcze_konta_sample.gif') repeat-x;">
		<?php
			linki_rejstracyjne();
			echo'	</div>';
		}
		if(LogicSession::isLoggedIn()==true)
		{
		?>

				<div id="welcomebar_mid" style="background: url('gfx/szukaj/tlo2_part1.png');"></div>
		<?php
		}
		else
		{
		?>

		<div id="welcomebar_mid" style="background: url('gfx/szukaj/tlo_part1.png')"> </div>
		<?php
		}
		?>
		<div id="welcomebar_lft"></div>
	</div>

	<div id="content_area_holder">
		<table id="content_area">
			<tr>
				<td class="images_are_blocks">
					<img src="gfx/logowanie/lewy_box_tytul.gif" alt=""><div id="content_area_content_leftmenu">
						<div style="height: 17px;"></div>
						Część czynności i miejsc w serwisie jest dostępna tylko i wyłącznie dla zalogowanych użytkowników dla bezpieczeństwa zarejestrowanych osób i materiałów przez nich zebranych<br><br>Jeśli nie posiadasz jeszcze konta w naszym serwisie nie zwlekaj i załóż je jak najszybciej! Posiadanie konta pozwoli Ci cieszyć się w pełni możliwościami strony.
					</div><img src="gfx/lewy_box_dol.gif" alt="">
				</td>	
				<td id="content_area_content" rowspan="2">
					<div id="content_area_content_c">	<!-- glowny kontener -->							
					
					<form action="" method="">
					
					<div id="menu_cut">
						<table>
							<tr>
								<td>Województwo</td>
								<td class="mc_spacer"></td>
								<td>
									<select name="region">
									<?php
									 for($i=0;$i<count($RegionValue);$i++)
      {
        if ($RegionValue[$i]!=$region)
         {
         echo '<option value='.$RegionValue[$i].'>'.$RegionValue[$i].'</option>';
         }
         else
         {
         echo '<option value='.$RegionValue[$i].' selected="true">'.$RegionValue[$i].'</option>';
         }
       }
									?>
									</select>
								</td>
							</tr>
								<td class="mc_spacer">Miasto</td>
								<td class="mc_spacer"></td>
								<td>
									<input class="input-text" type="text" name="city" value="<?php echo''.$city.''; ?>"></input>
								</td>
							</tr>
						</table>
						<script type="text/javascript">
							$("select").selectBox();
						</script>
					</div>
					<br>
					<input type="submit" id="szukaj_button" value="">
					
					<div id="box_z_barami">
						<?php
						if($region!="")
						{
						$bary=LogicBars::search($region,$city);
						if(count($bary)==0)
						{
						echo'<div class="red-message">Nie znaleziono barów dla danego kryterium</div>';
						}
						else
						{
						$licznik=1;
						foreach($bary as &$value)
						{
						echo'
						<div class="s_bar_box">
							<div class="s_bar_photo">
							<a href="bar.php?id='.$value->id.'">';
								if($value->doHasPhoto()==true) 
								{
								echo'<img src="uploads/167x77/'.$value->getFirstPhoto().'.jpg">';
								
								}
								else echo'<img src="gfx/miniatura_bar_brak.jpg">';
							echo'
							</a>
							</div>
							
							<div class="s_bar_name">
								<a href="bar.php?id='.$value->id.'">'.$value->name.'</a>
							</div>						
							
							<div class="s_bar_grading">
									Średnia ocen '.$value->avgMark().'<br>
									Stali bywalcy '.$value->getBywalcyAmount().'
							</div>
						</div>
						';
						if(($licznik%2)==0) echo'<br><br>';
						$licznik=$licznik+1;
						}
						}
						}
						?>
						
					</div>
					</form>	
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