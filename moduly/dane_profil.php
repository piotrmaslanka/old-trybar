<?php
echo'
						<img src="gfx/lewy_box_tytul_rog.gif" style="float: left;">
						<div id="nickname_box">
							<span id="nickname">'.LogicSession::getUser()->login.'</span> - '.LogicSession::getUser()->name.' '.LogicSession::getUser()->surname.'
						</div>
					</div>
					<div id="content_area_content_leftmenu">';
					if(LogicSession::getUser()->hasAvatar()==false)
					{
					echo'<img src="gfx/awatar.jpg" alt="avatar" id="userphoto">';
					}
					else
					{
					echo'<img src="uploads/140x140/'.LogicSession::getUser()->avatar.'.jpg" alt="avatar" id="userphoto">';
					}
					
					echo'
						
						
						
						<div id="account_description">
						<div><span class="value"><a href="#" >'.LogicSession::getUser()->rankingPosition().'</a></span><a href="#" style="font-weight: normal;">Ranking: </a></div>
						<div><span class="value"><a href="#" >'.LogicSession::getUser()->points.'</a></span><a href="#" style="font-weight: normal;">Punkty: </a></div>
						<div><span class="value"><a href="dodane.php?id='.LogicSession::getUser()->id.'" >'.LogicSession::getUser()->countBars().'</a></span><a href="dodane.php?id='.LogicSession::getUser()->id.'" style="font-weight: normal;">Bary: </a></div>
						<div><span class="value"><a href="st_bywalec_gdzie.php?id='.LogicSession::getUser()->id.'" >'.LogicSession::getUser()->getBywalecCount().'</a></span><a href="st_bywalec_gdzie.php?id='.LogicSession::getUser()->id.'" style="font-weight: normal;">St. bywalec: </a></div>
						<div><span class="value"><a href="znajomi.php?id='.LogicSession::getUser()->id.'" >'.LogicSession::getUser()->Friends().'</a></span><a href="znajomi.php?id='.LogicSession::getUser()->id.'" style="font-weight: normal;">Znajomi: </a></div>
						<div><span class="value"><a href="odbiorcza.php?id='.LogicSession::getUser()->id.'&strona=1" >('.LogicSession::getUser()->getUnreadedMessages().')'.LogicSession::getUser()->getMessages().'</a></span><a href="odbiorcza.php?id='.LogicSession::getUser()->id.'&strona=1" style="font-weight: normal;">Poczta: </a></div>
						</div>
						
						<div class="clear"></div>
						<table id="lewybox_panel">
							<tr>
								<td id="lewybox_panel_txt">
									'.LogicSession::getUser()->city.'<br>
									<span id="zmodyfikuj_konto"><a href="profil.php?id='.LogicSession::getUser()->id.'">Edytuj mój profil</a></span>
								</td>
								<td>
									<a href="galeria.php?id='.LogicSession::getUser()->id.'"><div id="prywatna_galeria"></div></a>
								</td>
							</tr>
							<tr>
								<td style="height: 10px;" colspan="2"></td>
							</tr>
							<tr>
								<td>
									<a href="napisz.php?id='.LogicSession::getUser()->id.'"><div id="napisz_wiadomosc"></div></a>
								</td>
								<td>
									<a href="dodajzdjecie.php?id='.LogicSession::getUser()->id.'"><div id="dodaj_zdjecie"></div></a>
								</td>
							</tr>
						</table>
				</div><img src="gfx/lewy_box_dol.gif" alt="">
	';				
?>