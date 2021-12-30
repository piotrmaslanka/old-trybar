<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();

$bywalec=@$_POST["bywalec"];
$bywalec=htmlspecialchars($bywalec, ENT_COMPAT, 'UTF-8');
$comment=@$_POST["comment"];

if($comment!="" && LogicSession::isLoggedIn()==true)
{
$comment = htmlspecialchars($comment, ENT_COMPAT, 'UTF-8');
ModelJuveComment::create(LogicSession::getUser()->id, $comment);
}

if($bywalec!="" && LogicSession::isLoggedIn()==true && $bywalec=="tak")
{
Juve::setBywalec(LogicSession::getUser()->id);
}

if($bywalec!="" && LogicSession::isLoggedIn()==true && $bywalec=="nie")
{
Juve::unsetBywalec(LogicSession::getUser()->id);
}

if(LogicSession::isLoggedIn()==true)
{
foreach ($_POST as $klucz => $wartosc)
{
if($wartosc=="null" or ($wartosc>=1 && $wartosc<=10))
 {
 Juve::mark(LogicSession::getUser()->id,$wartosc);
 }
}
}


$del_kom=@$_GET["del_kom"];
$del_kom=htmlspecialchars($del_kom, ENT_COMPAT, 'UTF-8');
if($del_kom!="")
 {
  if(ModelJuveComment::exists($del_kom)==true)
  {
  $usuwany=new ModelJuveComment($del_kom);
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
	<link rel="stylesheet" type="text/css" href="css/juve.css">
	<link rel="stylesheet" type="text/css" href="css/bar_tooltip.css">
	
    <style type="text/css">
        .ad-preloads * { display: none; } !important;
    </style>
    
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
				<div id="content_area_header_center">Dni Jarosławia</div>
				<div id="content_area_header_imgr"></div>
			</div>
			<div id="content_area_content">
				
				
				<div id="content_area_gallery_top">
					<div id="content_area_gallery_top_name">Rynek</div>
					<div id="content_area_gallery_top_staly">
					<?php
					if(LogicSession::isLoggedIn()==true)
					{
					if(Juve::isBywalec(LogicSession::getUser()->id)==true)
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
							  $zdjecia=Juve::getImages();
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
						<div class="content_area_header">Uczestnicy imprezy</div>
						<div id="content_area_bywalcy_box">
							<?php
								
							 $bywalcy=Juve::getBywalcy();
							 foreach($bywalcy as &$value)
							 {
							 $user_bywalec=new ModelUser($value);
							  if($user_bywalec->hasAvatar()==true)
							  {
							  echo'<a href="czyjs_profil.php?id='.$user_bywalec->id.'"><img src="uploads/84x84/'.$user_bywalec->avatar.'.jpg" alt="'.$user_bywalec->login.'"></a>';
							  }
							  else echo '<a href="czyjs_profil.php?id='.$user_bywalec->id.'"><img src="gfx/awatar_84x84.jpg" ></a>';
							 } 
							
							?>
							
 						</div>
					</div>
					<div id="content_area_oceny">
						<div class="content_area_header">Oceny</div>
						<div id="content_area_oceny_box">
							<div id="avg_box">Średnia ocena <span><?php echo''.round(Juve::avgMark(),2).''; ?></span></div>
							<div id="cnt_box">Liczba oddanych głosów: <?php echo''.Juve::howManyVoted().''; ?></div>
							<form action="" method="POST" id="rankform">
							<table id="ol_table">
							<?php
							if(LogicSession::isLoggedIn()==true)
							{
							$mojaOcena=Juve::getPersonsMark(LogicSession::getUser()->id);
							?>
								<tr>
									<td class="ol_t1 ol_30"></td>
									<td class="ol_t1 ol_label" onmouseover="tip.show('Oceń jak się bawiłeś na imprezie')" onmouseout="tip.hide()">Zabawa</td>
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
									<td class="ol_t1 ol_mygrade"><?php echo''.$mojaOcena.''; ?></td>
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
                        Dni Jarosławia odbyły się 23 i 24 czerwca 2012r. na Jarosławskim rynku. 
Na wydarzeniu były między innymi takie atrakcje jak wesołe miasteczko, 
pchli targ, występy solistów i grup tanecznych, zabawy i konkursy, teatr 
kulinarny czy wybory miss. Gwiazdami wieczorów był Lady Pank i Siklawa.<br> 
Trybar był patronem medialnym imprezy.<br><br>Zdjęcia w wykonaniu Centrum kultury i promocji w Jarosławiu<br><a href="http://www.ckip.jaroslaw.pl/">http://www.ckip.jaroslaw.pl/</a>

						<br><br>
<!--                        Fot. Monika Samojedny -->
                        </div>
					</div>
					<div style="clear: both; width: 998px; height: 0px;"></div>
				</div>
				
				
				
				
			</div>
			<div id="content_area_footer"></div>
		</div>
			<div id="comments">
			<?php
			$comment=ModelJuveComment::get_all();
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
				$link='?del_kom='.$value->id.'';
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