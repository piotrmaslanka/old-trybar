<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$logout=@$_GET["logout"];
$login2=@$_POST["login2"];
$login2=htmlspecialchars($login2, ENT_COMPAT, 'UTF-8');
$password2=@$_POST["password2"]; 	
$password2=htmlspecialchars($password2, ENT_COMPAT, 'UTF-8');

if($logout=="true")
 {
 	LogicSession::logout();
	header("Location: index.php");
 }
 else
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
	header('Location: aktualnosci.php?id='.LogicSession::getUser()->id.'');
	}  
 }
?>