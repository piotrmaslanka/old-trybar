<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
if(LogicSession::isLoggedIn()==true)
{
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=aktualnosci.php">';
}
else
{
										 $name=@$_POST["name"];
										 $name=htmlspecialchars($name, ENT_COMPAT, 'UTF-8');
                                         $surname=@$_POST["surname"];
										 $surname=htmlspecialchars($surname, ENT_COMPAT, 'UTF-8');
                                         $login=@$_POST["login"];
										 $login=htmlspecialchars($login, ENT_COMPAT, 'UTF-8');
                                         $password=@$_POST["password"];
										 $password=htmlspecialchars($password, ENT_COMPAT, 'UTF-8');
                                         $repassword=@$_POST["repassword"];
										 $repassword=htmlspecialchars($repassword, ENT_COMPAT, 'UTF-8');
                                         $city=@$_POST["city"];
										 $city=htmlspecialchars($city, ENT_COMPAT, 'UTF-8');
                                         $region=@$_POST["region"];
										 $region=htmlspecialchars($region, ENT_COMPAT, 'UTF-8');
                                         $email=@$_POST["email"];
										 $email=htmlspecialchars($email, ENT_COMPAT, 'UTF-8');
                                         $gg=@$_POST["gg"];
										 $gg=htmlspecialchars($gg, ENT_COMPAT, 'UTF-8');
                                         $tel=@$_POST["tel"];
										 $tel=htmlspecialchars($tel, ENT_COMPAT, 'UTF-8');
                                         $problem=false;
										 $regulamin=@$_POST["regulamin"];
										 $RegionValue=array('Wybierz',
										 'Dolnośląskie',
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
function int($int){
       
        // First check if it's a numeric value as either a string or number
        if(is_numeric($int) === TRUE){
           
            // It's a number, but it has to be an integer
            if((int)$int == $int){

                return TRUE;
               
            // It's a number, but not an integer, so we fail
            }else{
           
                return FALSE;
            }
       
        // Not a number
        }else{
       
            return FALSE;
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
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/rejestracja.css">
	
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
	<div id="haslo_przewodnie"></div>	<!-- twoj bar, twoja wodka -->
	<div id="top">
<iframe
src="http://www.facebook.com/plugins/like.php?href=http://www.facebook.com/pages/TryBAR/142549105817938
&layout=standard&show_faces=false& width=450&action=like&colorscheme=light&height=80"
scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; position: absolute; right: 10px; top: 10px; overflow: clip;
height:80px;" allowTransparency="true"></iframe> 
</div>
<?php $page=5; include 'moduly/banner.php'; ?>    				<!-- duzy obrazek z duza iloscia wodki -->
	<div id="menubar">					<!-- glowne menu - to brazowe - szkoda ze bez wodki -->
			<?php
			include "moduly/nawigacja.php";
			if(LogicSession::isLoggedIn()==false)
			{
			echo'
				<div id="welcomebar">			<!-- Witamy na TryBAR / Witaj Ojou-sama poziomy pasek na cala dlugosc strony -->

		<img src="gfx/mid_border.png" style="position: absolute; left: 0; top: 0;" alt="">
		<img src="gfx/mid_border.png" style="position: absolute; right: 0; top: 0;" alt="">
		<div id="welcomebar_rgt">
		';
		
			linki_rejstracyjne();
		echo'
		</div>';
		}
		?>
		<div id="welcomebar_mid">
		</div>
		<div id="welcomebar_lft">
		</div>
	</div>
	<div id="content_area_holder">
		<table id="content_area">
			<tr>
				<td class="images_are_blocks">
					<img src="gfx/logowanie/lewy_box_tytul.gif" alt=""><div id="content_area_content_leftmenu">
						Rejestracja daje ci możliwość dodawania, komentowania i oceniania barów. Możesz też zapraszać znajomych, wysyłać wiadomości, dodawać zdjęcia z najlepszych imprez. Po rejestracji
						masz możliwość stworzenia swojej listy życzeń. Wygraj nagrody na które masz ochotę.
						<br><br>
						Pola oznaczona gwiazdką są obowiązkowe. Uwaga: nie wprowadzenie imienia i nazwiska spowoduje, że Twoi znajomi będą mieć problem z odnalezieniem Twojego profilu w katalogu użytkowników.
						Mimo to, pola te nie są obowiązkowe.
						<div style="height: 17px;"></div>
					</div><img src="gfx/lewy_box_dol.gif" alt="">
				</td>

				<td id="content_area_content" rowspan="2">
					<div id="content_area_content_c">	<!-- glowny kontener -->							
					<?php

                                        echo'
					<form name="form" action="" method="post">
	
					<div id="menu_cut">
						<table id="loginfield">
							<tr>
								<td>Imię:</td>
								<td class="ipbox"><input type="text" class="txt" name="name" value="'.$name.'" maxlength="50"></td>
								<td></td>								
							</tr>
							<tr>
								<td>Nazwisko:</td>
								<td class="ipbox"><input type="text" class="txt" name="surname" value="'.$surname.'" maxlength="50"></td>
								<td></td>								
							</tr>';
							if (LogicUser::userExistsLogin($login)==true)
							{
							echo'
							<tr>
								<td></td>
								<td class="reg_error" colspan="2">
									Login zajęty
								</td>
							</tr>';
							$problem=true;
							}

							echo'
							<tr>
								<td>Login*:</td>
								<td class="ipbox"><input type="text" class="txt" name="login" value="'.$login.'"></td>
								<td></td>
								
							</tr>';
							if($password!="" and $repassword!="")
							{
							if($password!=$repassword)
							{
							echo'
							<tr>
								<td></td>
								<td class="reg_error" colspan="2">
									Hasło nie jest takie same w obu polach
								</td>
							</tr>';
							$problem=true;
							}
							else
							{
							 if(strlen($password)<=5)
							 {
							 echo'
							 <tr>
								<td></td>
								<td class="reg_error" colspan="2">
									Hasło musi mieć przynajmniej 6 znaków
								</td>
							</tr>';
							 $problem=true;
							 }
							 else
							 {
							  if($login==$password)
							  {
							  echo'
								<tr>
								<td></td>
								<td class="reg_error" colspan="2">
								Hasło nie może być takie samo jak login
								</td>
								</tr>';
								$problem=true;
							  }
							 }
							}
							}
							echo'
							<tr>
								<td>Hasło*:</td>
								<td class="ipbox"><input type="password" class="txt" name="password" maxlength="50"></td>
								<td></td>
							</tr>
							<tr>
								<td>Powtórz hasło*:</td>
								<td class="ipbox"><input type="password" class="txt" name="repassword" maxlength="50"></td>
								<td></td>
							</tr>
							<tr>
								<td>Miejscowość*:</td>
								<td class="ipbox"><input type="text" class="txt" name="city" value="'.$city.'" maxlength="50"></td>
								<td></td>
							</tr>';
							
							if($region=="Wybierz")
							{
							echo'<td></td>
								<td class="reg_error" colspan="2">
									Wybierz województwo
								</td>';
							$problem=true;
							}
							echo'<tr>
								<td>Województwo*</td>
								<td class="ipbox">
									<select id="wojew" name="region">
										 ';
     
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
	   
      
									echo'
									</select>
									<script type="text/javascript">
										$("select").selectBox();
									</script>
								</td>
								<td></td>
							</tr>';
							
							if(LogicUser::userExistsEmail($email)==true)
							{
							echo'
							<tr>
								<td></td>
								<td class="reg_error" colspan="2">
									E-mail zajęty
								</td>
							</tr>';
							$problem=true;
							}
							
							if($email!="")
							{
							if(!filter_var($email, FILTER_VALIDATE_EMAIL))
							{
							echo'
							<tr>
								<td></td>
								<td class="reg_error" colspan="2">
									Niepoprawny format E-maila
								</td>
							</tr>';
							$problem=true;
							}
							}
							
							echo'
							<tr>
								<td>E-mail*:</td>
								<td class="ipbox"><input type="text" class="txt" name="email" value="'.$email.'" maxlength="50"></td>
								<td></td>
							</tr>';
							
							if($gg!="")
							{
							if(int($gg)==false)
							{
							echo'
							<tr>
								<td></td>
								<td class="reg_error" colspan="2">
									Dozwolone są tylko cyfry
								</td>
							</tr>';
							}
							}
							echo'
							<tr>
								<td>Gadu-Gadu:</td>
								<td class="ipbox"><input type="text" class="txt" name="gg" value="'.$gg.'" maxlength="25"></td>
								<td></td>
							</tr>';
							if($tel!="")
							{
							if(int($tel)==false)
							{
							echo'
							<tr>
								<td></td>
								<td class="reg_error" colspan="2">
									Dozwolone są tylko cyfry
								</td>
							</tr>';
							}
							}
							echo'
							<tr>
								<td>Telefon:</td>
								<td class="ipbox"><input type="text" class="txt" name="tel" value="'.$tel.'" maxlength="25" ></td>
								<td></td>
							</tr>
							<tr>
							<td><input type="checkbox" name="regulamin" value="yes"></td>
							<td>*Akceptuję regulamin znajdujący się <b><a href="regulamin.php">TUTAJ</a></b></td>
							<td></td>
							</tr>
						</table>';
										
						                if($login!="" && $password!="" && $repassword!=""&& $city!=""&& $region!="" && $email!="" && $problem==false&& $regulamin=="yes")
                                        {
                                           LogicUser::registerUser($name, $surname, $login, $password, $city, $region, $email, $gg, $tel);
                                                   echo'<br><br><font color="green">Formularz wypełniony pomyślnie.<br>Na podany adres e-mail zostanie wysłany link aktywacyjny.</font>';
                                        }
										else
										{
										echo'
										<font class="reg_error">
										Wypełnij wszystkie obowiązkowe pola
										</font>
										';
										}
										?>
						<input type="submit" id="loguj_button" value="">
					</div>
					
					</form>
            
                                        
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
<?php
}
?>
</html>