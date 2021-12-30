<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
$strona=@$_GET["strona"];
$strona=htmlspecialchars($strona, ENT_COMPAT, 'UTF-8');
if(LogicSession::isLoggedIn()==false || LogicSession::can_edit_profile($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$blad=false;
$il=LogicSession::getUser()->getMessages();
$strony=ceil($il/MESSAGES_PER_PAGE);

 if($strona<=0) {echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';$blad=true;}
 else
 {
 if($strony==0)
 {
 if($strona!=1) {echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';$blad=true;}
 }
 else
 {
 if($strona>$strony) {echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';$blad=true;}
 else if($strona=="") {echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';$blad=true;}
 } 
 }

if($blad==false)
{
$del=@$_GET["del"];
$del=htmlspecialchars($del, ENT_COMPAT, 'UTF-8');
if($del!="")
{
$msg=new ModelMessage($del);
if($msg->receiver==LogicSession::getUser()->id)
{
$msg->delete();
}
else echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}

foreach ($_POST as $klucz => $wartosc)
{
if($wartosc=="on")
 {
 $msg=new ModelMessage($klucz);
 if($msg->receiver==LogicSession::getUser()->id)
	{
	$msg->delete();
	}	
	else echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
 }
}

$il=LogicSession::getUser()->getMessages();
$strony=ceil($il/MESSAGES_PER_PAGE);
$wiadomosci=LogicSession::getUser()->inbox($strona);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
 <html>
<head>
	<title>TryBAR</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Trybar to portal społecznościowy zrzeszający ludzi lubiących prowadzić nocny tryb życia. Portal ma na celu zbudować szczegółową, ogólnopolską bazę barów i lokali.">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/odbiorcza.css">
	<link rel="stylesheet" type="text/css" href="css/shoutbox.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/selectbox.js"></script>	
	<script type="text/javascript" src="js/online.js"></script>
	
		<script type="text/javascript">		
		function zapytanie(link) 
		{
		var pytanie =confirm('Czy na pewno usunąć?');
		if (pytanie) window.location=link;
		}
	</script>
	<script type="text/javascript" src="js/shoutbox.js"></script>
	
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
						<?php
						echo'<form action="odbiorcza.php?id='.$id.'&strona='.$strona.'" method="POST" enctype="multipart/form-data" id="odbiorczaform">';
						?>
						<div id="content_area_limiter">
						<?php
						for($i=1;$i<=count($wiadomosci);$i++)
						{
						$user=new ModelUser($wiadomosci[$i-1]->sender);
						$link="odbiorcza.php?id=".$id."&strona=".$strona."&del=".$wiadomosci[$i-1]->id;
						echo'<div class="line"></div>
							<div class="msg">';
						?>	
							<a href="" onclick="zapytanie('<?php echo"$link"; ?>')"><div class="kaszak"></div></a>
						<?php
							echo'
								<input type="checkbox" class="cbox" name="'.$wiadomosci[$i-1]->id.'" id="msg'.$i.'">
								<a href="czytaj.php?id='.$wiadomosci[$i-1]->id.'">';
								if($wiadomosci[$i-1]->readed==false)
								{
								echo '<span style="font-size: 14px;font-weight: bold;">'.$wiadomosci[$i-1]->title.' od '.$user->login.'</span>';
								}
								else echo $wiadomosci[$i-1]->title.' od '.$user->login;
								echo'
								</a>
								<br>
								<span>'.LogicHumanize::ago($wiadomosci[$i-1]->when_sent).'</span>	 
							</div>';
						}
						echo'<div class="line"></div>';
						?>
						</div>
						
						<div id="bt_stuff">
		 <span id="zaznaczodznacz">
<a onclick="$('input[type=checkbox]').attr('checked', true)" href="#">Zaznacz wszystkie</a> | <a href="#" onclick="$('input[type=checkbox]').attr('checked', false)">Odznacz wszystkie</a>
</span>

							
							<select id="ssel" onchange="if (confirm('Czy na pewno usunąć zaznaczone wiadomośći?')) $('#odbiorczaform').submit();">
								<option value="#">Wybierz...</option>
								<option value="" >Usuń</option>
							</select>
							<script type="text/javascript">$("#ssel").selectBox();</script>
							
							<div id="pagina">
							<?php
							$nastepna=$strona+1;
							$poprzednia=$strona-1;
							$kropki=false;
							if($strona>1)
							{
							echo'<a href="odbiorcza.php?id='.LogicSession::getUser()->id.'&strona='.$poprzednia.'"><div class="left"></div></a>';
							echo'<a href="odbiorcza.php?id='.LogicSession::getUser()->id.'&strona=1"><div class="bx">1</div></a>';
							if($kropki==false)
							{
							echo'<div class="mdot">...</div>';
							$kropki=true;
							}
							}
							echo'<a href="odbiorcza.php?id='.LogicSession::getUser()->id.'&strona='.$strona.'"><div class="bx">'.$strona.'</div></a>';	
							if($strony>$strona)
							{
							if($kropki==false)
							{
							echo'<div class="mdot">...</div>';
							$kropki=true;
							}
							echo'<a href="odbiorcza.php?id='.LogicSession::getUser()->id.'&strona='.$strony.'"><div class="bx">'.$strony.'</div></a>';
							echo'<a href="odbiorcza.php?id='.LogicSession::getUser()->id.'&strona='.$nastepna.'"><div class="right"></div></a>';
							}
							?>
								
							</div>
						</div>
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
}
?>