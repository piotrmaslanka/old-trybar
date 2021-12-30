<!DOCTYPE html>
<?php
include "sql.php";
include "logic/logic.php";


LogicSession::start();


$cat=@$_GET["cat"];
$cat=htmlspecialchars($cat, ENT_COMPAT, 'UTF-8');
if($cat=="" || $cat>=3) $cat=0;
$strona=@$_GET["strona"];
$strona=htmlspecialchars($strona, ENT_COMPAT, 'UTF-8');
$wybor=@$_POST["wybor"];
$wybor=htmlspecialchars($wybor, ENT_COMPAT, 'UTF-8');

if($wybor!="")
 {
 $nag=new ModelPrize($wybor);
 $nag->pick(LogicSession::getUser()->id);
 }
$blad=false;
$il=LogicPrize::pagesInCategory($cat);
$strony=ceil($il/MAX_PRIZES_PER_PAGE);
if($strona=="" || $strona>$strony || $strona<1) $strona=1;

if($blad==false)
{
?>
<html>
<head>
   <?php include "moduly2/head.php" ?> 

    <link rel="stylesheet" type="text/css" href="css/o/nagrody.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
    <style type="text/css">
        #profile { position: absolute; top: 66px; left: 14px; }
        #middlebox { position: absolute; top: 0px; left: 265px; }
        #shoutbox { position: absolute; top: 66px; left: 782px; }
       <?php //#etiquette { position: absolute; top: 0px; left: 0px; }?>
    </style>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/shoutbox2.js"></script>
	<script type="text/javascript" src="js/online.js"></script>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>TryBAR</title>
</head>
<body>
	<?php include "moduly2/logo.php" ?>
    <?php $page=6; include 'moduly/banner.php'; ?>     
    <?php include "moduly2/menu.php" ?> 
    <!-- wewnatrz tego diva zawiera sie reszta strony -->
    <!-- mutable_content_holder opisany jest w style.css -->
    <div id="mutable_content_holder">   
        <div class="left"></div>    <!-- wąs lewy -->
        <div class="right"></div>   <!-- wąs prawy -->
           
        <?php //<div id="etiquette" style="background-image: url('gfx/etiqs/news.gif');"></div>?>

        <div id="middlebox">
            <div class="content" id="root_content">
            
                <div id="category_selector">
                    <div class="element"><a href="?cat=0">Elektronika</a></div>
                    <div class="element"><a href="?cat=1">Gry komputerowe</a></div>
                </div>
                
                <div id="goblin_banner">
                    <a href="http://goblin.org.pl/" target="_blank" class="af"></a>
                </div>
				<?php
				if($cat==0)
				{
				?>
				<div class="content" style="background-image: url('gfx/o/nagrody/elektronika.jpg')">
				<?php
				}
				else
				{
				?>
				<div class="content" style="background-image: url('gfx/o/nagrody/gry.jpg')">
				<?php
				}
				?>
                
                    <form action="<?php echo'?cat='.$cat.'&strona='.$strona.'' ?>" method="post">
					
					<?php
					if(LogicSession::isLoggedIn()==true) 
					{
					$user=LogicSession::getUser();
					if($user->prize_id=="" ||$user->prize_id==0) 
					{
					//echo'<font color="red" style="text-align: center;">Nie wybrałeś żadnej nagrody</font>';
					}
					}
					?>
                    <div class="horiz_sep_line"></div>
					<?php
					$nagrody=LogicPrize::pageBy($cat, $strona);
					foreach($nagrody as &$value)
					{
					echo'
					<div class="element">
                        <div class="left">
						';
						if(LogicSession::isLoggedIn()==true)
						{
						if($user->prize_id==$value->id)
						{
                           echo' <input type="radio" value="'.$value->id.'" name="wybor" CHECKED>';
						}
						else echo' <input type="radio" value="'.$value->id.'" name="wybor">';
						}
						echo'
                        </div>
                        <div class="middle">
                            <a href="'.$value->url.'">'.$value->name.'</a>
                        </div>
                        <div class="right">
						';
						if($value->gfx=="" || $value->gfx==0)
						{
                            echo'<img src="gfx/nagroda_brak.jpg">';
						}
						else
						{
						echo'<img src="uploads/50x50/'.$value->gfx.'.jpg">';
						}
						echo'
                        </div>
                    </div>
                
                    <div class="horiz_sep_line"></div>
					';
					}
					if(LogicSession::isLoggedIn()==true) 
					 {
					 echo'<input type="submit" id="wybierz_button" value="">';
					 }
					 else echo'<br><div class="center-message">Zaloguj się aby móc wybrać nagrodę</div>';
					?>
                    </form>
                    
                    <div id="pagination">
    
                        <?php
							$nastepna=$strona+1;
							$poprzednia=$strona-1;
							$kropki=false;
							if($strona>1)
							{
							echo'<div class="left"><a href="?cat='.$cat.'&strona='.$poprzednia.'" class="af"></a></div>';
							echo'<div class="element"><a href="?cat='.$cat.'&strona=1">1</a></div>';
							if($kropki==false)
							{
							echo'<div class="ellipsis">...</div>';
							$kropki=true;
							}
							}
							echo'<div class="element"><a href="?cat='.$cat.'&strona='.$strona.'">'.$strona.'</a></div>';	
							if($strony>$strona)
							{
							if($kropki==false)
							{
							echo'<div class="ellipsis">...</div>';
							$kropki=true;
							}
							echo'<div class="element"><a href="?cat='.$cat.'&strona='.$strony.'">'.$strony.'</a></div>';
							echo'<div class="right"><a href="?cat='.$cat.'&strona='.$nastepna.'" class="af"></a></div>';
							}
							?>  

                    </div>
                </div>
            </div>
			
			<?php include "moduly2/footer.php" ?> 
			
        </div>
		
		<?php if(LogicSession::isLoggedIn()==true) include "moduly2/shoutbox.php" ?> 

        <?php if(LogicSession::isLoggedIn()==true) include "moduly2/profil.php" ?> 
        
    </div>
</body>
</html>
<?php
}
?>