<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
if(LogicSession::isLoggedIn()==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$id=@$_GET["id"];
$id=htmlspecialchars($id, ENT_COMPAT, 'UTF-8');
if(LogicUser::userExistsId($id)==false)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
}
else
{
$user=new ModelUser($id);
$photo=@$_GET["photo"];
$photo=htmlspecialchars($photo, ENT_COMPAT, 'UTF-8');

$comment=@$_POST["comment"];
$comment=htmlspecialchars($comment, ENT_COMPAT, 'UTF-8');
$userphoto_id=@$_POST["userphoto_id"];
$userphoto_id=htmlspecialchars($userphoto_id, ENT_COMPAT, 'UTF-8');
if($comment!="")
{
 $komentowaneZdjecie=new ModelUserPhoto($userphoto_id);
 $komentowaneZdjecie->comment(LogicSession::getUser()->id, $comment);
 echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=privgal.php?id='.$id.'&photo='.$userphoto_id.'">';
}

$del_kom=@$_GET["del_kom"];
$del_kom=htmlspecialchars($del_kom, ENT_COMPAT, 'UTF-8');
if($del_kom!="")
 {
  if(LogicComments::existsBarComment($del_kom)==false)
  {
  //echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
  }
  else
  {
  $usuwany=new ModelUserPhotoComment($del_kom);
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
	<link rel="stylesheet" type="text/css" href="css/privgal.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>	
	<script type="text/javascript" src="js/adgallery.js"></script>
	<script type="text/javascript" src="js/online.js"></script>	
	
	<script type="text/javascript">
		function imgsel(index) {
			// firstly detect userphoto id of requested pic
			var userphoto_id = $(".ad-thumb-list img")[index].id.substr(16);
			$("#comments > div").css('display', 'none');
			$("#pg-comments-userphoto-id-"+userphoto_id).css('display', 'block');
			$("#userphoto-id-picked").val(userphoto_id);
			$("#desc_field > div").css('display', 'none');
			$("#pg-description-userphoto-id-"+userphoto_id).css('display', 'block');
		}
	</script>
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
				<div id="welcomebar_rgt"></div>
				<div id="welcomebar_mid" style="background: url('gfx/news/belka_sample.png') repeat-x;"> 
		</div>
		<div id="welcomebar_lft">
		</div>
	</div>

	<div id="content_area_holder">
		<div id="content_area">
			<div id="content_area_header">
				<div id="content_area_header_imgl"></div>
				<div id="content_area_header_center">Prywatna galeria użytkownika <?php echo''.$user->login.'';?></div>
				<div id="content_area_header_imgr"></div>
			</div>
			<div id="content_area_content">
				
				
				<div class="ad-gallery">
				  <div class="ad-image-wrapper">
				  </div>
				  <div class="ad-controls">
				  </div>
				  <div class="ad-nav">
					<div class="ad-thumbs">
					  <ul class="ad-thumb-list">
						<?php
						$lista = ModelUser::prioritySortPhotos($user->getPhotos(), $photo);
						if(count($lista)==0)
						{
						echo'
								<li>
								  <a href="gfx/privigal_brak.jpg">
									<img src="gfx/privigal_brak.jpg" style="height: 50px;">
								  </a>
								</li>
							';
						}
						foreach($lista as &$value)
							  {
							  echo'
								<li>
								  <a href="uploads/native/'.$value->gfx.'.jpg">
									<img src="uploads/stath50/'.$value->gfx.'.jpg" style="height: 50px;" id="pg-userphoto-id-'.$value->id.'">
								  </a>
								</li>';
							  }
						?>
					  </ul>
					</div>
				  </div>
				</div>
						
				<script type="text/javascript">
					$('.ad-gallery').adGallery({callbacks:{afterImageVisible: function() { imgsel(this.current_index); }}});
				</script>	

				<div id="label_with_opis_napis">Opis</div>
				<div id="desc_field">
				<?php
				$licznik=1;
				foreach($lista as &$value)
				{
				if($licznik==1)
				 {
				echo'<div id="pg-description-userphoto-id-'.$value->id.'">';
				}
				 else
				 {
				 echo'
				<div style="display: none;" id="pg-description-userphoto-id-'.$value->id.'">';
				 }	
				echo'
				'.$value->description.'
				</div>';
				$licznik=$licznik+1;
				}
				?>
	
				</div>
				
			</div>
			<div id="content_area_footer"></div>
		</div>
			<div id="comments">
					<?php
					if(count($lista)!=0)
					{
					echo'';
					$licznik=1;
				    foreach($lista as &$value)
					{
					 if($licznik==1)
				     {
					 echo'<div id="pg-comments-userphoto-id-'.$value->id.'">';	
					 }
					 else
					 {
					 echo'<div style="display: none;" id="pg-comments-userphoto-id-'.$value->id.'">';	
					 }
 			
									$comment=$value->getComments();
									$licznik2=2;
									foreach($comment as &$value2)
									{			
									$user2= new ModelUser($value2->user);
									if(($licznik2%2)==0)
									{
									echo'<div class="comments_b1">';
									}
									else
									{
									echo'<div class="comments_b2">';
									}
									echo'	<span class="comments_kiedy">'.LogicHumanize::ago($value2->when_added).'</span><span class="comments_kto"><a href="czyjs_profil.php?id='.$user2->id.'">'.$user2->login.'</a></span>';
									if(LogicSession::isLoggedIn()==true)
									{
									if(LogicSession::getUserId()==$value2->user)
									{
									$link='?id='.$id.'&del_kom='.$value2->id.'';
									?>
									<div class="kaszak"><a href="#" onclick="zapytanie('<?php echo"$link"; ?>')"></a></div>
									<?php
									}
									}
									echo'<br>
									'.$value2->comment.'
									</div>';
									$licznik2++;
									}		
					echo'
					</div>
					 ';
					 $licznik=$licznik+1;
					 }
					 
					 
			    if(LogicSession::isLoggedIn()==true)
				{ ?>
				<form id="send_comment_submit_background" action="" method="post">
				<input type="text" id="comments_add" name="comment" value="Tu wpisz treść komentarza" onfocus="if (this.value=='Tu wpisz treść komentarza') this.value=''">
				<input type="hidden" id="userphoto-id-picked" value="<?php echo $lista[0]->id ?>" name="userphoto_id">
				<center><input type="submit" value="" id="send_comment_submit"></center>
				</form>
				<?php }
				else
				{
				echo'<span id="login_to_add_comment_background"><div class="center-message">Zaloguj się by móc komentować</div></span>';
				}
				?>
					 					
				</form>
				<?php 
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
}
?>