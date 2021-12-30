<?php $connection = @mysql_connect('localhost', 'trybar_trybar', 'stairwaytoheaven');
   $db = @mysql_select_db('trybar_trybar', $connection);
   mysql_set_charset('utf8');
?>