<?php require_once('Connections/conRCMS.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


if ((isset($_POST["form_check"])) && ($_POST["form_check"] == "form_check")) {
	
	$email = $_POST['Email'];
	
	mysql_select_db($database_conRCMS, $conRCMS);
	$query_rsStatus = "SELECT * FROM tcr_register WHERE a_email = '$email'";
	$rsStatus = mysql_query($query_rsStatus, $conRCMS) or die(mysql_error());
	$row_rsStatus = mysql_fetch_assoc($rsStatus);
	$totalRows_rsStatus = mysql_num_rows($rsStatus);
	
	if($totalRows_rsStatus == 1) {
		
		mysql_select_db($database_conRCMS, $conRCMS);
		$query_rsAccepted = "SELECT * FROM tcr_register WHERE a_email = '$email'";
		$rsAccepted = mysql_query($query_rsAccepted, $conRCMS) or die(mysql_error());
		$row_rsAccepted = mysql_fetch_assoc($rsAccepted);
		$totalRows_rsAccepted = mysql_num_rows($rsAccepted);
		
		if($row_rsAccepted['status'] == 1){
			$message = "<span style=\"color:green;font-weight:bold;\">Your application has been accepted! Contact us for more details.</span>";
		} elseif($row_rsAccepted['status'] == 0) {
			$message = "<span style=\"color:orange;font-weight:bold;\">Your application is pending. Check again soon.<span>";
		} elseif($row_rsAccepted['status'] == 2) {
			$message = "<span style=\"color:red;font-weight:bold;\">Sorry, your application is not accepted. Contact us for more details.<span>";
		}
				
	} else {
		$message = "Your Email does not have in our system.";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Status Checking</title>
<link rel="stylesheet" href="css/text.css" />
<link rel="stylesheet" href="css/formalize.css" />
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/jquery.formalize.min.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<style>
th {
	padding-left: 10px;
	background-color:#ECF5FD;
	border-bottom: #C1DCF7 solid 1px;
}
.box {
	width:338px;
}
</style>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h3>Tadika Cahaya Raudhah</h3>
<p>Check your appliation status. Make sure you already registered at <a href="register.php" target="_blank">here</a> before checking.</p>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="status-checking">
<table width="350" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="50" align="left" valign="middle">E-mail</td>
    <td width="8" align="left" valign="middle">:</td>
    <td width="152" align="left" valign="middle"><span id="sprytextfield1">
    <input name="Email" type="text" placeholder="Registered Email" />
    <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    <td width="140" align="left" valign="middle"><input name="check_status" type="submit" value="Check Status" /></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle">&nbsp;</td>
  </tr>
</table>
<input name="form_check" type="hidden" value="form_check" />
</form>
<strong><?php echo @$message; ?></strong>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email");
</script>
</body>
</html>
