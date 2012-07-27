<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"\

#localhost
/*$hostname_conRCMS = "localhost";
$database_conRCMS = "tcr_app";
$username_conRCMS = "root";
$password_conRCMS = "";*/

#live richcoremedia
$hostname_conRCMS = "localhost";
$database_conRCMS = "richcore_tcrapp";
$username_conRCMS = "richcore_tcruser";
$password_conRCMS = "tcrpassword";


$conRCMS = mysql_pconnect($hostname_conRCMS, $username_conRCMS, $password_conRCMS) or trigger_error(mysql_error(),E_USER_ERROR); 
?>