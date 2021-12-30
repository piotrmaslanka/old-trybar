<?php
	include "sql.php";
	include "logic/logic.php";
	LogicSession::start();

	if (!ModelBanner::exists($_GET['c'])) die();

    $ban = new ModelBanner($_GET['c']);
    if ($ban->page != $_GET['page']) die();
    
    $ban->was_clicked();
    header("Status: 200");
    header('Location: '.$ban->url);
?>