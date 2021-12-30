<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
$bywalec=@$_POST["bywalec"];
$bywalec=htmlspecialchars($bywalec, ENT_COMPAT, 'UTF-8');
if(LogicBars::barExistsId($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$bar=new ModelBar($id);
$comment=@$_POST["comment"];
if($comment!="" && LogicSession::isLoggedIn()==true)
{
$comment = htmlspecialchars($comment, ENT_COMPAT, 'UTF-8');
$bar->comment(LogicSession::getUser()->id, $comment);
}
if($bywalec!="" && LogicSession::isLoggedIn()==true && $bywalec=="tak")
{
$bar->setBywalec(LogicSession::getUser()->id);
}

if($bywalec!="" && LogicSession::isLoggedIn()==true && $bywalec=="nie")
{
$bar->deleteBywalec(LogicSession::getUser()->id);
}

if(LogicSession::isLoggedIn()==true)
{
foreach ($_POST as $klucz => $wartosc)
{
if($wartosc=="null" or ($wartosc>=1 && $wartosc<=10))
 {
 $oceny=$bar->getModelMark(LogicSession::getUser()->id);
 $oceny->setMark($klucz, $wartosc);
 }
}
}


$del_kom=@$_GET["del_kom"];
$del_kom=htmlspecialchars($del_kom, ENT_COMPAT, 'UTF-8');
if($del_kom!="")
 {
  if(LogicComments::existsBarComment($del_kom)==false)
  {
 // echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
  }
  else
  {
  $usuwany=new ModelBarComment($del_kom);
  if(LogicSession::getUserId()!=$usuwany->user)
   {
    //echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
   }
   else $usuwany->delete();
  }
 }
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>TryBAR</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main2.css">
	<link rel="stylesheet" type="text/css" href="css/bar.css">
	<link rel="stylesheet" type="text/css" href="css/bar_tooltip.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>	
	<script type="text/javascript" src="js/adgallery.js"></script>	
	<script type="text/javascript" src="js/bardbox.js"></script>	
	<script type="text/javascript" src="js/tooltip.js"></script>	
	<script type="text/javascript" src="js/skinnytip.js"></script>	
	<script type="text/javascript" src="js/selectbox.js"></script>	
	<script type="text/javascript" src="js/online.js"></script>
	
	<script type="text/javascript">		
		function zapytanie(link) 
		{
		var pytanie =confirm('Czy na pewno usunąć?');
		if (pytanie) window.location=link;
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
	<div id="tiplayer" style="position:absolute; visibility:hidden; z-index:10000;">
		
	</div>
    <?php include "moduly/screening.php"; ?>
	<div id="haslo_przewodnie"></div>	<!-- twoj bar, twoja wodka -->
	<div id="top">
<iframe
src="http://www.facebook.com/plugins/like.php?href=http://www.facebook.com/pages/TryBAR/142549105817938
&layout=standard&show_faces=false& width=450&action=like&colorscheme=light&height=80"
scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; position: absolute; right: 10px; top: 10px; overflow: clip;
height:80px;" allowTransparency="true"></iframe> 
</div>
<?php $page=3; include 'moduly/banner.php'; ?>    
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




	<div id="content_area_holder">
		<div id="content_area">
			<div id="content_area_header">
				<div id="content_area_header_imgl"></div>
				<div id="content_area_header_center"><?php echo''.$bar->name.''?></div>
				<div id="content_area_header_imgr"></div>
			</div>
			<div id="content_area_content">
				
				
				<div id="content_area_gallery_top">
					<div id="content_area_gallery_top_name"><?php echo''.$bar->region.' > '.$bar->city.' > '.$bar->street.''?></div>
					<div id="content_area_gallery_top_staly">
					<?php
					if(LogicSession::isLoggedIn()==true)
					{
					if($bar->isBywalec(LogicSession::getUser()->id)==true)
					{
					echo'
					<form action="" method="POST" id="">
					<input type="submit" value="" id="niebywalec_submit">
					<input type="hidden" name="bywalec" value="nie">
					</form>
					';
					}
					else
					{
					echo'
					<form action="" method="POST" id="">
					<input type="submit" value="" id="bywalec_submit">
					<input type="hidden" name="bywalec" value="tak">
					</form>
					';
					}
					}
					
					?>
					</div>
					<div style="clear: both; width: 998px; height: 5px;"></div>
					<div id="content_area_gallery_box">
					
					
					
						<div class="ad-gallery">
						  <div class="ad-image-wrapper">
						  </div>
						  <div class="ad-controls">
						  </div>
						  <div class="ad-nav">
							<div class="ad-thumbs">
							  <ul class="ad-thumb-list">
							  <?php
							  $zdjecia=$bar->getPhotos();
							  foreach($zdjecia as &$value)
							  {
							  echo'
								<li>
								  <a href="uploads/native/'.$value.'.jpg">
									<img src="uploads/stath50/'.$value.'.jpg" style="height: 50px;">
								  </a>
								</li>';
							  }
							  ?>
							  </ul>
							</div>
						  </div>
						</div>
							
					
					<script type="text/javascript">
						$('.ad-gallery').adGallery();
					</script>
					</div>
				</div>
				
				<div id="content_area_triple">
					<div id="content_area_bywalcy">
						<div class="content_area_header">Stali bywalcy</div>
						<div id="content_area_bywalcy_box">
							<?php
								
							 $bywalcy=$bar->getBywalcy(9);
							 foreach($bywalcy as &$value)
							 {
							 $user_bywalec=new ModelUser($value);
							  if($user_bywalec->hasAvatar()==true)
							  {
							  echo'<a href="czyjs_profil.php?id='.$user_bywalec->id.'"><img src="uploads/84x84/'.$user_bywalec->avatar.'.jpg" alt="'.$user_bywalec->login.'"></a>';
							  }
							  else echo '<a href="czyjs_profil.php?id='.$user_bywalec->id.'"><img src="gfx/awatar_84x84.jpg" ></a>';
							 } 
							if($bar->getBywalcyAmount()>9)
							{
							echo'<div id="wiecej_bywalcow"><a href="st_bywalcy_bar.php?id='.$id.'">Zobacz więcej stałych bywalców...</a></div>';
							}
							?>
							
 						</div>
					</div>
					<div id="content_area_oceny">
						<div class="content_area_header">Oceny</div>
						<div id="content_area_oceny_box">
							<div id="avg_box">Średnia ocena <span><?php echo''.$bar->avgMark().''; ?></span></div>
							<div id="cnt_box">Liczba oddanych głosów: <?php echo''.$bar->getMarkCout().''; ?></div>
							<form action="" method="POST" id="rankform">
							<table id="ol_table">
							<?php
							if(LogicSession::isLoggedIn()==true)
							{
							$mojeOceny=$bar->getModelMark(LogicSession::getUser()->id);
							$srednieOceny=$bar->getAverageMark();
							?>
								<tr>
									<td class="ol_t1 ol_30"></td>
									<td class="ol_t1 ol_label" onmouseover="tip.show('Oceń czy wnętrze lokalu jest zrobione ze smakiem. Czy ogranicza się tylko do czterech białych ścian czy zawiera jakieś miłe dla oka atrakcje w postaci obrazów, bibelotów lub innych dodatków. 1 oznacza obskurny wystrój, a 10 lokal do którego można przychodzić chociażby po to by zawiesić na czymś ładnym oko.')" onmouseout="tip.hide()">Wystrój wnętrza</td>
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel0" onchange="$('#rankform')[0].submit();" name="0">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(0).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[0].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t2 ol_30"></td>
									<td class="ol_t2 ol_label" onmouseover="tip.show('Oceń czy elewacja budynku jest zadbana i czy zawiera jakieś ciekawe elementy zachęcające do wejścia do lokalu. 1 oznacza, że budynek lokalu w ogóle nie jest zadbany, a 10, że oprócz tego, że jest zadbany to zawiera również zachęcający wystrój.')" onmouseout="tip.hide()">Wystrój na zewnątrz</td>
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel1" onchange="$('#rankform')[0].submit();" name="1">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(1).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[1].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t1 ol_30"></td>
									<td class="ol_t1 ol_label" onmouseover="tip.show('Oceń czy lokal rzuca się w oczy. 1 oznacza, że nawet stojąc pod drzwiami lokalu ciężko się zorientować, że w środku jest bar, a 10, że już z daleka można rozpoznać, że dany lokal jest tym, którego się szukało.')" onmouseout="tip.hide()">Identyfikacja</td>
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel2" onchange="$('#rankform')[0].submit();" name="2">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(2).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[2].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t2 ol_30"></td>
									<td class="ol_t2 ol_label" onmouseover="tip.show('Oceń czy lokal znajduje się w okolicy zarazem miłej dla oka jak i pozwalającej na komfortowe spędzenie wieczoru. Czy w okolicy są inne bary gdzie można znaleźć innych znajomych, są sklepy lub kioski gdzie można skoczyć po papierosy lub inne rzeczy, czy jest przystanek albo postój taksówek umożliwiający komfortowy powrót do domu? 1 oznacza, że lokal jest na odludziu lub w nieprzyjemnej okolicy, a 10, że okolica żyje swoim własnym imprezowym życiem i pozwala na przyjemne i komfortowe spędzenie wieczoru.')" onmouseout="tip.hide()">Okolica</td>
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel3" onchange="$('#rankform')[0].submit();" name="3">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(3).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[3].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t1 ol_30"></td>
									<td class="ol_t1 ol_label" onmouseover="tip.show('Oceń czy muzyka lecąca w tle nie jest za głośna, uniemożliwiająca komunikację, puszczane piosenki pasują do klimatu/charakteru lokalu. Nie oceniaj puszczanych piosenek gdyż jest to kwestia indywidualnego gustu. 1 oznacza muzykę, która w ogóle nie pasuje do lokalu lub docelowej grupy klientów albo jest tak głośna, że nie można usłyszeć własnych myśli, a 10 muzykę, która buduje w lokalu niepowtarzalny klimat.')" onmouseout="tip.hide()">Muzyka</td>	
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel4" onchange="$('#rankform')[0].submit();" name="4">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(4).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[4].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t2 ol_30"></td>
									<td class="ol_t2 ol_label" onmouseover="tip.show('Oceń czy ceny w lokalu są wysokie,niskie czy może zachowują standard w porównaniu do innych barów. 1 oznacza, że ceny są bardzo wysokie, wręcz nie do przyjęcia, a 10 że ceny są dużo niższe od konkurencji.')" onmouseout="tip.hide()">Ceny</td>	
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel5" onchange="$('#rankform')[0].submit();" name="5">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(5).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[5].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t1 ol_30"></td>
									<td class="ol_t1 ol_label" onmouseover="tip.show('Oceń czy lokal znajduje się w miejscu, do którego łatwo trafić np. dzięki komunikacji miejskiej lub to czy osoby nie znające okolicy nie będą miały problemu ze znalezieniem lokalu. 1 oznacza, że do lokalu bardzo ciężko trafić, a 10, że łatwo.')" onmouseout="tip.hide()">Lokalizacja</td>	
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel6" onchange="$('#rankform')[0].submit();" name="6">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(6).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[6].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t2 ol_30"></td>
									<td class="ol_t2 ol_label" onmouseover="tip.show('Oceń czy w okolicy lokalu łatwo zaparkować. 1 oznacza, że do lokalu lepiej wybrać się na piechotę nawet gdy jest dostępny kierowca lub trzeba zaparkować daleko od lokalu by mieć gdzie zostawić auto, a 10, że lokal posiada swój własny parking lub takowy jest bardzo blisko i przejście z samochodu do lokalu nie jest problemem nawet w deszczowe dni.')" onmouseout="tip.hide()">Parking</td>	
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel7" onchange="$('#rankform')[0].submit();" name="7">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(7).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[7].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t1 ol_30"></td>
									<td class="ol_t1 ol_label" onmouseover="tip.show('Oceń czy lokal zatrudnia ochronę lub czy jest taka konieczność. Czy w okolicy lokalu kręcą się nieciekawe typy, a w samym lokalu lub okolicy dochodzi do zamieszek i bójek? 1 oznacza, że do lokalu lepiej nie przychodzić samemu, a 10, że bezpieczeństwo lokalu i jego okolicy jest na wysokim poziomie.')" onmouseout="tip.hide()">Bezpieczeństwo</td>	
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel8" onchange="$('#rankform')[0].submit();" name="8">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(8).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[8].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t2 ol_30"></td>
									<td class="ol_t2 ol_label" onmouseover="tip.show('Oceń czy obsługa jest miła i profesjonalna. Czy zdarzają się jakieś wpadki nie do wybaczenia w postaci pijanego barmana, złośliwej kelnerki lub chamskiego ochroniarza? 1 oznacza totalną klapę, do lokalu lepiej nie przychodzić jeśli ktoś nie chce zepsuć sobie humoru, a 10, że personel jest kwintensecją profesjonalizmu w tej branży.')" onmouseout="tip.hide()">Personel</td>	
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel9" onchange="$('#rankform')[0].submit();" name="9">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(9).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[9].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t1 ol_30"></td>
									<td class="ol_t1 ol_label" onmouseover="tip.show('Oceń czy alkohol w lokalu jest dobry i czy jest duży wybór. 1 oznacza, że piwo beczkowe jest mieszane z wodą  lub jest jej więcej niż alkoholu albo praktycznie nie ma wyboru alkoholi nie tylko w postaci piwa, a 10, że alkohol jest najwyższej jakości, a w palecie tych dostępnych każdy wybierze coś dla siebie.')" onmouseout="tip.hide()">Alkohol</td>	
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel10" onchange="$('#rankform')[0].submit();" name="10">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(10).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[10].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t2 ol_30"></td>
									<td class="ol_t2 ol_label" onmouseover="tip.show('Oceń czy lokal oferuje jakieś przekąski. Jeśli tak to czy są one dobre i czy jest duży wybór. 1 oznacza, że lokal nie oferuje żadnych przekąsek lub oferuje, ale są one nie do przełknięcia, a 10, że w palecie dostępnych przekąsek każdy znajdzie coś dla siebie, jedzenie jest bardzo dobre i do tego można się najeść.')" onmouseout="tip.hide()">Jedzenie</td>	
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel11" onchange="$('#rankform')[0].submit();" name="11">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(11).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[11].''; ?></td>
								</tr>
								<tr>
									<td class="ol_t1 ol_30"></td>
									<td class="ol_t1 ol_label" onmouseover="tip.show('Oceń czy personel lokalu dba o jego czystość. Czy regularnie są wycierane stoliki, szklanki i sztućce regularnie myte, a wnętrze jest zadbane? 1 oznacza, że czystość lokalu ma wiele do życzenia i totalnie odbiera chęci do spożywania alkoholu czy przekąsek, a 10, że w kwestii czystości lokalowi nie można nic zarzucić.')" onmouseout="tip.hide()">Czystość</td>	
									<td class="ol_t2 ol_arrow"> <!-- idy maja byc od ssel0 do ssel12. name zrob sobie jak chcesz. optionow zrob do 10 -->
										<select id="ssel12" onchange="$('#rankform')[0].submit();" name="12">
											<option value="x">Wybierz</option>
											<option value="null">Brak oceny</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>															
									</td>
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojeOceny->getMark(12).''; ?></td>
									<td class="ol_t1 ol_ograde"><?php echo''.$srednieOceny[12].''; ?></td>
								</tr>
							<?php
							}
							else
							{
							echo'<br></br><div class="center-message">Zaloguj się by móc oceniać</div>';
							}
							?>
							</table>
							</form>
						</div>
					</div>
					<div id="content_area_opis">
						<div class="content_area_header">Opis</div>
						<div id="content_area_opis_box">
						<?php
						echo''.$bar->description.'';
						?>
						</div>
					</div>
					<div style="clear: both; width: 998px; height: 0px;"></div>
				</div>
				
				
				
				
			</div>
			<div id="content_area_footer"></div>
		</div>
			<div id="comments">
			<?php
				$comment=$bar->getComments();
			$licznik=2;
			foreach($comment as &$value)
			{			
			$User= new ModelUser($value->user);
			if(($licznik%2)==0)
			{
			echo'<div class="comments_b1">';
			}
			else
			{
			echo'<div class="comments_b2">';
			}
				echo'	<span class="comments_kiedy">'.LogicHumanize::ago($value->when_added).'</span><span class="comments_kto"><a href="czyjs_profil.php?id='.$User->id.'">'.$User->login.'</a></span>';
				if(LogicSession::isLoggedIn()==true)
				{
				if(LogicSession::getUserId()==$value->user)
				{
				$link='?id='.$id.'&del_kom='.$value->id.'';
				?>
				<div class="kaszak"><a href="#" onclick="zapytanie('<?php echo"$link"; ?>')"></a></div>
				<?php
				}
				}
				echo'<br>
					'.$value->content.'
				</div>';
			$licznik++;
			}
	
				if(LogicSession::isLoggedIn()==true)
				{ ?>
				<form id="send_comment_news_submit_background" action="" method="post">
				<input type="text" id="comments_add" name="comment" value="Tu wpisz treść komentarza" onfocus="if (this.value=='Tu wpisz treść komentarza') this.value=''">
				<center><input type="submit" value="" id="send_comment_news_submit"></center>
				</form>
				<?php }
				else
				{
				echo'<span id="login_to_add_comment_background"><div class="center-message">Zaloguj się by móc komentować</div></span>';
				}
				?>
			</div>
			
			<div id="prefooter"></div>
			<div id="footer">
				<?php
						include "moduly/stopka.php";
						?>
			</div>
	</div>
	
	<script type="text/javascript">$("#ssel0").selectBox();$("#ssel1").selectBox();$("#ssel2").selectBox();$("#ssel3").selectBox();$("#ssel4").selectBox();
								   $("#ssel5").selectBox();$("#ssel6").selectBox();$("#ssel7").selectBox();$("#ssel8").selectBox();$("#ssel9").selectBox();
								   $("#ssel10").selectBox();$("#ssel11").selectBox();$("#ssel12").selectBox();								   
	</script>
</body>
</html>
<?php
}
?>