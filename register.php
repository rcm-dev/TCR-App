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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tcr_register (`no`, reg_date, start_date, c_dob_year, c_dob__month, c_dob_day, c_age, c_gender, c_citizen, c_allergies, c_medical_problem, c_agerank, c_sibling, c_lastSchool, c_gradeLastSchool, a_name, a_relationship, a_edu, a_ic_passpord, a_citizen, a_addNo, a_addStreet, a_addPoscode, a_addState, a_hometel, a_mtel, a_email, a_comp, a_com_address, a_typeOfBusiness, a_yearsThere, a_position, a_comp_tel, a_comp_fax, status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['no'], "int"),
                       GetSQLValueString($_POST['reg_date'], "date"),
                       GetSQLValueString($_POST['start_date'], "date"),
                       GetSQLValueString($_POST['c_dob_year'], "int"),
                       GetSQLValueString($_POST['c_dob__month'], "int"),
                       GetSQLValueString($_POST['c_dob_day'], "int"),
                       GetSQLValueString($_POST['c_age'], "int"),
                       GetSQLValueString($_POST['c_gender'], "int"),
                       GetSQLValueString($_POST['c_citizen'], "int"),
                       GetSQLValueString($_POST['c_allergies'], "text"),
                       GetSQLValueString($_POST['c_medical_problem'], "text"),
                       GetSQLValueString($_POST['c_agerank'], "int"),
                       GetSQLValueString($_POST['c_sibling'], "int"),
                       GetSQLValueString($_POST['c_lastSchool'], "text"),
                       GetSQLValueString($_POST['c_gradeLastSchool'], "int"),
                       GetSQLValueString($_POST['a_name'], "text"),
                       GetSQLValueString($_POST['a_relationship'], "text"),
                       GetSQLValueString($_POST['a_edu'], "text"),
                       GetSQLValueString($_POST['a_ic_passpord'], "text"),
                       GetSQLValueString($_POST['a_citizen'], "int"),
                       GetSQLValueString($_POST['a_addNo'], "text"),
                       GetSQLValueString($_POST['a_addStreet'], "text"),
                       GetSQLValueString($_POST['a_addPoscode'], "int"),
                       GetSQLValueString($_POST['a_addState'], "int"),
                       GetSQLValueString($_POST['a_hometel'], "int"),
                       GetSQLValueString($_POST['a_mtel'], "int"),
                       GetSQLValueString($_POST['a_email'], "text"),
                       GetSQLValueString($_POST['a_comp'], "text"),
                       GetSQLValueString($_POST['a_com_address'], "text"),
                       GetSQLValueString($_POST['a_typeOfBusiness'], "text"),
                       GetSQLValueString($_POST['a_yearsThere'], "int"),
                       GetSQLValueString($_POST['a_position'], "text"),
                       GetSQLValueString($_POST['a_comp_tel'], "int"),
                       GetSQLValueString($_POST['a_comp_fax'], "int"),
                       GetSQLValueString($_POST['status'], "int"));

  mysql_select_db($database_conRCMS, $conRCMS);
  $Result1 = mysql_query($insertSQL, $conRCMS) or die(mysql_error());
  
  // insert services
  #new id
  $newid = mysql_insert_id();
  
  #array services
  $services = $_POST['services'];
  if(count($services) > 0)
	{
	 $services_string = implode(",", $services);
	}
	
  $insertService = "INSERT INTO tcr_services_term (id, register_id_fk, services_store) VALUES ('', $newid, '$services_string')";
  $result2 = mysql_query($insertService);

  $insertGoTo = "ThankYou.php?newid=".$newid;
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conRCMS, $conRCMS);
$query_rsCitizen = "SELECT * FROM tcr_citizen";
$rsCitizen = mysql_query($query_rsCitizen, $conRCMS) or die(mysql_error());
$row_rsCitizen = mysql_fetch_assoc($rsCitizen);
$totalRows_rsCitizen = mysql_num_rows($rsCitizen);

mysql_select_db($database_conRCMS, $conRCMS);
$query_rsState = "SELECT * FROM tcr_state";
$rsState = mysql_query($query_rsState, $conRCMS) or die(mysql_error());
$row_rsState = mysql_fetch_assoc($rsState);
$totalRows_rsState = mysql_num_rows($rsState);

mysql_select_db($database_conRCMS, $conRCMS);
$query_rsCitizen2 = "SELECT * FROM tcr_citizen";
$rsCitizen2 = mysql_query($query_rsCitizen2, $conRCMS) or die(mysql_error());
$row_rsCitizen2 = mysql_fetch_assoc($rsCitizen2);
$totalRows_rsCitizen2 = mysql_num_rows($rsCitizen2);

mysql_select_db($database_conRCMS, $conRCMS);
$query_rsServices = "SELECT * FROM tcr_services";
$rsServices = mysql_query($query_rsServices, $conRCMS) or die(mysql_error());
$row_rsServices = mysql_fetch_assoc($rsServices);
$totalRows_rsServices = mysql_num_rows($rsServices);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kid Registration</title>
<link rel="stylesheet" href="css/text.css" />
<link rel="stylesheet" href="css/formalize.css" />
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/jquery.formalize.min.js"></script>
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
</head>

<body>
<h3>Tadika Cahaya Raudhah Kid Registration</h3>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="600" align="left">
    <tr valign="baseline">
      <th colspan="2" align="left" nowrap="nowrap">Available Services</th>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap"><table border="0" cellspacing="0" cellpadding="0">
        <?php do { ?>
          <tr>
            <td><input name="services[]" type="checkbox" value="<?php echo $row_rsServices['service_id']; ?>" /> <?php echo $row_rsServices['service_name']; ?> <?php echo $row_rsServices['start_time']; ?> <?php echo $row_rsServices['end_time']; ?></td>
          </tr>
          <?php } while ($row_rsServices = mysql_fetch_assoc($rsServices)); ?>
      </table></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <th colspan="2" align="left" nowrap="nowrap">Kid Information</th>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Start Date</td>
      <td>
      <input type="date" id="date" name="start_date">
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Date of Birth DD/MM/YYYY</td>
      <td>
      <input type="number" id="number" name="c_dob_day" min="1" max="31" step="1" placeholder="Day"> <input type="number" id="number" name="c_dob__month" min="1" max="12" step="1" placeholder="Month"> <input type="number" id="number" name="c_dob_year" min="2000" max="2010" step="1" placeholder="Year">
       </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Age</td>
      <td>
      <input type="number" id="number" name="c_age" min="1" max="5" step="1" placeholder="Age">
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Gender</td>
      <td><select name="c_gender">
        <option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>>Male</option>
        <option value="2" <?php if (!(strcmp(2, ""))) {echo "SELECTED";} ?>>Female</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Citizen</td>
      <td><select name="c_citizen">
        <?php 
do {  
?>
        <option value="<?php echo $row_rsCitizen['citizen_id']?>" ><?php echo $row_rsCitizen['citizen_name']?></option>
        <?php
} while ($row_rsCitizen = mysql_fetch_assoc($rsCitizen));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Allergies</td>
      <td><input type="text" name="c_allergies" value="" size="50" placeholder="Allergies" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Medical Problem</td>
      <td><textarea name="c_medical_problem" cols="50" rows="5" class="box" placeholder="Medical Problem"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Age Rank</td>
      <td>
      <input type="number" id="number" name="c_agerank" min="1" max="10" step="1" placeholder="Rank">
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Sibling</td>
      <td>
      <input type="number" id="number" name="c_sibling" min="1" max="10" step="1" placeholder="Sibling">
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Last School</td>
      <td><input type="text" name="c_lastSchool" value="" size="32" placeholder="Last School" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade</td>
      <td>
      <input type="number" id="number" name="c_gradeLastSchool" min="1" max="40" step="1" placeholder="Grade">
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <th colspan="2" align="left" nowrap="nowrap">Parent Information</th>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Name</td>
      <td><input type="text" name="a_name" value="" size="32" placeholder="Parent Name" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Relationship</td>
      <td><select name="a_relationship">
        <option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>>Parent</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Education</td>
      <td><input type="text" name="a_edu" value="" size="32" placeholder="Education" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">IC/Passport</td>
      <td><input type="text" name="a_ic_passpord" value="" size="32" placeholder="IC/Passport" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Citizen</td>
      <td><select name="a_citizen">
        <?php 
do {  
?>
        <option value="<?php echo $row_rsCitizen2['citizen_id']?>" ><?php echo $row_rsCitizen2['citizen_name']?></option>
        <?php
} while ($row_rsCitizen2 = mysql_fetch_assoc($rsCitizen2));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">No</td>
      <td><input type="text" name="a_addNo" value="" size="32" placeholder="123" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Street</td>
      <td><input type="text" name="a_addStreet" value="" size="32" placeholder="Street" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Poscode</td>
      <td><input type="text" name="a_addPoscode" value="" size="32" placeholder="42700" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">State</td>
      <td><select name="a_addState">
        <?php 
do {  
?>
        <option value="<?php echo $row_rsState['state_id']?>" ><?php echo $row_rsState['state_name']?></option>
        <?php
} while ($row_rsState = mysql_fetch_assoc($rsState));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Home Tel</td>
      <td><input type="text" name="a_hometel" value="" size="32" placeholder="0388888888" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Mobile</td>
      <td><input type="text" name="a_mtel" value="" size="32" placeholder="0123456789" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email</td>
      <td>
      <input type="email" id="email" name="a_email" placeholder="name@example.com"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <th colspan="2" align="left" nowrap="nowrap">Company / Organization</th>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Company</td>
      <td><input type="text" name="a_comp" value="" size="32" placeholder="Company Name" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Address</td>
      <td>
      <textarea name="a_com_address" cols="50" rows="5" class="box" placeholder="Address"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Type of Business</td>
      <td><input type="text" name="a_typeOfBusiness" value="" size="32" placeholder="Type of Business" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Years There</td>
      <td>
      <input type="number" id="number" name="a_yearsThere" min="1" max="10" step="1" placeholder="Years">
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Position</td>
      <td><input type="text" name="a_position" value="" size="32" placeholder="Position" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tel</td>
      <td><input type="text" name="a_comp_tel" value="" size="32" placeholder="0388888888" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fax</td>
      <td><input type="text" name="a_comp_fax" value="" size="32" placeholder="0388888889" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Submit Form" /></td>
    </tr>
  </table>
  <input type="hidden" name="no" value="" />
  <input type="hidden" name="reg_date" value="" />
  <input type="hidden" name="status" value="0" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsCitizen);

mysql_free_result($rsState);

mysql_free_result($rsCitizen2);

mysql_free_result($rsServices);
?>
