<?php 
include "sql.php";
include "logic/logic.php";
LogicSession::start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>TryBAR</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main2.css">
	<link rel="stylesheet" type="text/css" href="css/news.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
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
		<div id="welcomebar_lft" style="background: none; background-color: black;">
		</div>		

	</div>

	<div id="content_area_holder">
		<div id="content_area">
			<div id="content_area_header">
				<div id="content_area_header_imgl"></div>
				<?php
				echo'<div id="content_area_header_center">Regulamin</div>';
				?>
				<div id="content_area_header_imgr"></div>
			</div>
				
				<div id="content_area_content">
				<?php
				include "moduly/regulamin.php";
				?>
				</div>
			<div id="content_area_footer"></div>
		</div>
			
			<div id="prefooter"></div>
			<div id="footer">
				<?php
						include "moduly/stopka.php";
						?>
			</div>
	</div>
</body>
</html>