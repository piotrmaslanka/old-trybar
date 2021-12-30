<?php
	include "sql.php";
	include "logic/logic.php";

	LogicInternalBar::regenerateRanking();
	LogicInternalUser::regenerateRanking();
	echo 'OK';
?>
