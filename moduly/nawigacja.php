<?php
 $logout=@$_POST["logout"];
 if($logout!="")
 {
  if($logout=="true")
  {
	LogicSession::logout();
	header("Location: index.php");
  }
 }



function linki_rejstracyjne()
{
echo'
			<a href="przypomnij.php">
			<div class="left">Zapomniałeś hasła?</div>
			</a>
			<a href="rejestracja.php">
			<div class="right">Nie masz jeszcze konta?</div>
			</a>
			<a href="rejestracja.php">
				<div id="welcomebar_rgt_register">Zarejestruj się - za darmo!</div>
			</a>';
}
if(LogicSession::isLoggedIn()==false)
  {  
  $login2=@$_POST["login2"];
  $login2=htmlspecialchars($login2, ENT_COMPAT, 'UTF-8');
  $password2=@$_POST["password2"]; 	
  $password2=htmlspecialchars($password2, ENT_COMPAT, 'UTF-8');			
	  
 ?>
		<div id="menubar_l"></div><div id="menubar_r"></div>	<!-- dekoratory - koncowki menu -->
			
		<div id="menubar_m">	<!-- cale menu za wylaczeniem koncowek -->	
			<div id="">

			   <form name="logowanie" action="" method="post" id="menubar_login">
				<input type="text" value="Login" id="menubar_login_login" name="login2">
				<input type="password" value="xxxxxx" id="menubar_login_password" name="password2">
				<input type="submit" value="" id="menubar_login_submit">
				</form>		
										<!-- albo czysty zwykly tekst ktory zostanie nice sformatowany 
				Witaj Ojou-sama!
						-->
												

			</div>
<div id="menubar_links">
				<a href="index.php"><img src="gfx/button_glowna.png" alt="Strona główna" onmouseover="$(this).attr('src','gfx/button_glowna_p.png')" onmouseout="$(this).attr('src','gfx/button_glowna.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="szukaj.php"><img src="gfx/button_szukaj.png" alt="Szukaj baru" onmouseover="$(this).attr('src','gfx/button_szukaj_p.png')" onmouseout="$(this).attr('src','gfx/button_szukaj.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="nagrody.php"><img src="gfx/button_nagrody.png" alt="Nagrody onmouseover="$(this).attr('src','gfx/button_nagrody_p.png')" onmouseout="$(this).attr('src','gfx/button_nagrody.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="dnijaroslawia.php"><img src="gfx/button_juwenalia.png" alt="Dni Jarosławia" onmouseover="$(this).attr('src','gfx/button_juwenalia_p.png')" onmouseout="$(this).attr('src','gfx/button_juwenalia.png')"></a>
                
	<?php	
	if($login2!="" && $password2!="")
	{ 
	if(LogicSession::login($login2,$password2)==false)
	{
	echo'
	<font color="red" size="1" style="position: absolute;left: 670px; top: 7px;">
	Niepoprawny login i/lub hasło <br> lub użytkownik nieaktywny
	</font>'; 
	}
	else
	{
	echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=aktualnosci.php?id='.LogicSession::getUser()->id.'">';
	}
	}  
  ?>
			</div>	

		</div>	
	
	</div>

<?php
 }
 else
 {
 $logout=@$_POST["logout"];
 if($logout!="")
 {
  if($logout=="true")
  {
	LogicSession::logout();
  	echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
  }
 }
?>
<div id="menubar_l"></div><div id="menubar_r"></div>
<div id="menubar_m">	<!-- cale menu za wylaczeniem koncowek -->
			<div id="menubar_login">
			
				<form action="" method="post" id="menubar_login" style="position: absolute;right:5px;top: 0px;">
				<input type="hidden" name="logout" value="true">

				<input type="submit" value="" id="menubar_logout_submit">			
				

			    </form>
			<div id="menubar_login_shift">	

				</div>

			</div>
			<div id="menubar_links">
				<a href="index.php"><img src="gfx/button_glowna.png" alt="Strona główna" onmouseover="$(this).attr('src','gfx/button_glowna_p.png')" onmouseout="$(this).attr('src','gfx/button_glowna.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="<?php echo'aktualnosci.php?id='.LogicSession::getUser()->id.''; ?>"><img src="gfx/button_konto.png" alt="Moje konto"  onmouseover="$(this).attr('src','gfx/button_konto_p.png')" onmouseout="$(this).attr('src','gfx/button_konto.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="dodajbar.php"><img src="gfx/button_dodaj.png" alt="Dodaj bar" onmouseover="$(this).attr('src','gfx/button_dodaj_p.png')" onmouseout="$(this).attr('src','gfx/button_dodaj.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="szukaj.php"><img src="gfx/button_szukaj.png" alt="Szukaj baru" onmouseover="$(this).attr('src','gfx/button_szukaj_p.png')" onmouseout="$(this).attr('src','gfx/button_szukaj.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="ranking_user.php?strona=1"><img src="gfx/button_ruserzy.png" alt="Ranking userów" onmouseover="$(this).attr('src','gfx/button_ruserzy_p.png')" onmouseout="$(this).attr('src','gfx/button_ruserzy.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="ranking_bar.php?strona=1"><img src="gfx/button_rbary.png" alt="Ranking barów" onmouseover="$(this).attr('src','gfx/button_rbary_p.png')" onmouseout="$(this).attr('src','gfx/button_rbary.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="nagrody.php"><img src="gfx/button_nagrody.png" alt="Nagrody" onmouseover="$(this).attr('src','gfx/button_nagrody_p.png')" onmouseout="$(this).attr('src','gfx/button_nagrody.png')"></a>
				<img src="gfx/menu_separator.gif" alt="">
				<a href="dnijaroslawia.php"><img src="gfx/button_juwenalia.png" alt="Juwenalia" onmouseover="$(this).attr('src','gfx/button_juwenalia_p.png')" onmouseout="$(this).attr('src','gfx/button_juwenalia.png')"></a>
<?php } ?>