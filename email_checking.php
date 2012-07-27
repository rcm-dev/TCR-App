<?php require_once('Connections/conRCMS.php'); ?>
<?php

$email = mysql_real_escape_string(htmlentities($_GET['email']));

$available = "SELECT * FROM tcr_register WHERE a_email = '$email'";
$available_result = mysql_query($available);
$available_row = mysql_num_rows($available_result);

if($available_row == 1) {
	echo "Not Available.";
} else {
	echo "This email is available to be use.";
}


?>