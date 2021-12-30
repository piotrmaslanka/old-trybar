<?php
	/* kopyrajt ołrajt!
	   jak skopiujesz ten kod do swoich Niecnych Celów, to cię dorwę i poszczuję Tusiem
	   a Tuś to całe 45 kg psa, wliczając zęby
	*/
	include "sql.php";
	include "logic/logic.php";
	LogicSession::start();

	if (!LogicSession::isLoggedIn()) die('[["0","2000-00-00      :45","Ze względów bezpieczeństwa wyłączyłem shoutbox. Zaloguj się aby móc skorzystać.","System"]]');
	
	$uid = LogicSession::getUserId();

	if (isset($_POST['content'])) LogicInternalShoutbox::shout($uid, $_POST['content']);

	$komenty = array_reverse(LogicInternalShoutbox::getEntries($uid, 10));
	
	echo json_encode($komenty);	
?>