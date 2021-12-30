 <!-- Nota do ciecia: skrzynka z profilem usera          START -->    
        <!-- Nazwy kwalifikowane: profile -->
<?php
echo'
        <div id="profile">
            <div class="header">
                <div class="head"></div>
                <div class="tail">
                    <div class="content">
                        <span class="b">'.LogicSession::getUser()->login.'</span> - '.LogicSession::getUser()->name.' '.LogicSession::getUser()->surname.'
                    </div>
                </div>
            </div>
            <div class="content">
                
                <div class="left">
				';
                    if(LogicSession::getUser()->hasAvatar()==false)
					{
					echo'<img src="gfx/awatar.jpg" alt="avatar" id="userphoto">';
					}
					else
					{
					echo'<img src="uploads/140x140/'.LogicSession::getUser()->avatar.'.jpg" alt="avatar" id="userphoto">';
					}
				echo'
                </div>
                <div class="right">
                    <div class="element">
                        <span class="value"><a href="#">'.LogicSession::getUser()->rankingPosition().'</a></span>        <span class="label">Ranking:</span>            
                    </div>
                    <div class="element">
                        <span class="value"><a href="#">'.LogicSession::getUser()->points.'</a></span>        <span class="label">Punkty:</span>             
                    </div>
                    <div class="element">
                        <span class="value"><a href="dodane.php?id='.LogicSession::getUser()->id.'" >'.LogicSession::getUser()->countBars().'</a></span>        <span class="label">Bary:</span>               
                    </div>
                    <div class="element">
                        <span class="value"><a href="st_bywalec_gdzie.php?id='.LogicSession::getUser()->id.'" >'.LogicSession::getUser()->getBywalecCount().'</a></span>        <span class="label">St. bywalec:</span>        
                    </div>
                    <div class="element">
                        <span class="value"><a href="znajomi.php?id='.LogicSession::getUser()->id.'" >'.LogicSession::getUser()->Friends().'</a></a></span>        <span class="label">Znajomi:</span>            
                    </div>
                    <div class="element">
                        <span class="value"><a href="odbiorcza.php?id='.LogicSession::getUser()->id.'&strona=1" >('.LogicSession::getUser()->getUnreadedMessages().')'.LogicSession::getUser()->getMessages().'</a></a></span>     <span class="label">Poczta:</span>             
                    </div>
                    
 
                </div>

                <div class="n1">
                    <div class="label">'.LogicSession::getUser()->city.'</div>
                    <div class="value"><a href="profil.php?id='.LogicSession::getUser()->id.'">Edytuj mój profil</a></div>
                </div>
                <div class="n2">
                    <a href="galeria.php?id='.LogicSession::getUser()->id.'"><div class="content"></div></a>
                </div>
                <div class="n3">
                    <a href="napisz.php?id='.LogicSession::getUser()->id.'"><div class="content"></div></a>
                </div>
                <div class="n4">
                    <a href="dodajzdjecie.php?id='.LogicSession::getUser()->id.'"><div class="content"></div></a>
                </div>
                
            </div>
            <div class="footer"></div>
        </div>
	';
?>
        <!-- - - - - - - - - - - - - - - - - - - - - - - - - -  STOP  -->
        