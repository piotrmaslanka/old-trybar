<html>
<head>
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
include "sql.php";
include "logic/logic.php";
LogicSession::start();
$login=@$_GET["login"];
$login=htmlspecialchars($login, ENT_COMPAT, 'UTF-8');
$haszkod=@$_GET["haszkod"];
$haszkod=htmlspecialchars($haszkod, ENT_COMPAT, 'UTF-8');
if(LogicUser::getUserByLogin($login)==true)
{
$user=LogicUser::getUserByLogin($login);
if($user->regeneratePassword($haszkod)==true)
 {
 echo'Odzyskano hasło na email';
 }
 else echo'Błąd w odzyskaniu hasła';
}
else echo'Błąd w odzyskaniu hasła';

echo'<META HTTP-EQUIV="Refresh" CONTENT="2;URL=index.php">';
?>
</body>
</html>