<?php require_once('Connections/conRCMS.php'); ?>
<?php

$status_no = mysql_real_escape_string($_POST['status_option']);
$user_no = mysql_real_escape_string($_POST['user_no']);

if(($status_no == '') && ($status_no == '')){
	header("location:dashboard.php");
} else {


	$sql = "UPDATE tcr_register SET status = '$status_no' WHERE no = '$user_no'";
	$sql_result = mysql_query($sql);
	
	//echo "Status No ".$status_no;
	//echo "<br/>";
	//echo "User No ".$user_no;
	
	if($status_no == 0) {
		$status = "Pending";
	}
	if($status_no == 1) {
		$status = "Approved";
	}
	if($status_no == 2) {
		$status = "Rejected";
	}
	
	header("location:user-edit.php?change=true&noid=".$user_no."&status=".$status);

}


?>