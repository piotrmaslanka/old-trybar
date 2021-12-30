<?php
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$login=@$_GET["login"];
$login=htmlspecialchars($login, ENT_COMPAT, 'UTF-8');
$haszkod=@$_GET["haszkod"];
$haszkod=htmlspecialchars($haszkod, ENT_COMPAT, 'UTF-8');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
<?php
if(LogicUser::activate($login,$haszkod)==true)
{
echo'Aktywowano pomyślnie konto '.$login.'';
}
else echo'Błąd aktywacji konta '.$login.'';
echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
?>
</body>
</html>