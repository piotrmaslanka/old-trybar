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
 echo'
 <!-- Nota do ciecia: zasadnicze menu portalu            START -->    
        <!-- referuje do tego: mainmenu.css -->
        <div id="mainmenu_menu">
            <div class="left"></div>
            <div class="right"></div>   <!-- left i right to zaokraglenia menu -->
            <div class="center">        <!-- content to zasadnicza tresc menu -->
			';
			if(LogicSession::isLoggedIn()==false)
			{
				echo'
                <div class="element" id="mm_glowna"><a href="index.php" class="af"></a></div>
                <div class="ellipsis"></div>
                <div class="element" id="mm_szukaj"><a href="szukaj.php" class="af"></a></div>
				';
			}
			else
			{
			echo
			'
				<div class="element" id="mm_glowna"><a href="index.php" class="af"></a></div>
                <div class="ellipsis"></div>
                <div class="element" id="mm_konto"><a href="aktualnosci.php?id='.LogicSession::getUser()->id.'" class="af"></a></div>
                <div class="ellipsis"></div>
                <div class="element" id="mm_dodaj"><a href="dodajbar.php" class="af"></a></div>
                <div class="ellipsis"></div>
                <div class="element" id="mm_szukaj"><a href="szukaj.php" class="af"></a></div>
                <div class="ellipsis"></div>
                <div class="element" id="mm_ruzytkownikow"><a href="ranking_user.php?strona=1" class="af"></a></div>
                <div class="ellipsis"></div>
                <div class="element" id="mm_rbarow"><a href="ranking_bar.php?strona=1" class="af"></a></div>
                <div class="ellipsis"></div>
                <div class="element" id="mm_juve"><a href="dnijaroslawia.php" class="af"></a></div>
			';
			
			}
			echo'
                <div class="right">';
					if(LogicSession::isLoggedIn()==false)
					{
					echo'
					<form action="redirect.php" method="POST">
                        <input type="text" class="textbox" value="Login" name="login2">
                        <input type="password" class="textbox" value="xxxxxx" name="password2">

                        <input type="submit" class="submitbox" value="">
                    </form>';
					}	
					else
					{
					echo
					'
                    <div class="logout"><a href="redirect.php?logout=true" class="af"></a></div>
					';
					}
				echo'
                </div></div></div>
         ';
         ?>