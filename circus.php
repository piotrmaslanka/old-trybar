<?php

	include "sql.php";
	include "logic/logic.php";
	LogicSession::start();
	if (LogicSession::isLoggedIn()) LogicInternalUser::ping();
?>