<?php 
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
if(LogicNews::newsExistsId($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$news_comment=@$_POST["news_comment"];
$news = new ModelNews($id);
if($news_comment!="" && LogicSession::isLoggedIn()==true)
{
$news_comment = htmlspecialchars($news_comment, ENT_COMPAT, 'UTF-8');
$news->comment(LogicSession::getUser(), $news_comment);
}

$del_kom=@$_GET["del_kom"];
$del_kom=htmlspecialchars($del_kom, ENT_COMPAT, 'UTF-8');
if($del_kom!="")
 {
  if(LogicComments::existsNewsComment($del_kom)==false)
  {
  //echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
  }
  else
  {
  $usuwany=new ModelNewsComment($del_kom);
  if(LogicSession::getUserId()!=$usuwany->user)
   {
   // echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
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
	<link rel="stylesheet" type="text/css" href="css/news.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
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
		<div id="welcomebar_lft">
		</div>		

	</div>

	<div id="content_area_holder">
		<div id="content_area">
			<div id="content_area_header">
				<div id="content_area_header_imgl"></div>
				<?php
				echo'<div id="content_area_header_center">'.$news->title.'</div>';
				?>
				<div id="content_area_header_imgr"></div>
			</div>
				<?php
				echo'<div id="content_area_content">'.$news->content.'</div>';
				?>
			<div id="content_area_footer"></div>
		</div>
			<div id="comments">
			<?php
			$NewsComment=$news->getComments();
			$licznik=2;
			foreach($NewsComment as &$value)
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
				<input type="text" id="comments_add" name="news_comment" value="Tu wpisz treść komentarza" onfocus="if (this.value=='Tu wpisz treść komentarza') this.value=''">
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
</body>
</html>
<?php
}
?>
