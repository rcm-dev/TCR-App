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

if($_GET['noid'] == ''){
	header("location: dashboard.php");
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsRegister = 10;
$pageNum_rsRegister = 0;
if (isset($_GET['pageNum_rsRegister'])) {
  $pageNum_rsRegister = $_GET['pageNum_rsRegister'];
}
$startRow_rsRegister = $pageNum_rsRegister * $maxRows_rsRegister;

mysql_select_db($database_conRCMS, $conRCMS);
$query_rsRegister = "SELECT * FROM tcr_register WHERE status = 0";
$query_limit_rsRegister = sprintf("%s LIMIT %d, %d", $query_rsRegister, $startRow_rsRegister, $maxRows_rsRegister);
$rsRegister = mysql_query($query_limit_rsRegister, $conRCMS) or die(mysql_error());
$row_rsRegister = mysql_fetch_assoc($rsRegister);

if (isset($_GET['totalRows_rsRegister'])) {
  $totalRows_rsRegister = $_GET['totalRows_rsRegister'];
} else {
  $all_rsRegister = mysql_query($query_rsRegister);
  $totalRows_rsRegister = mysql_num_rows($all_rsRegister);
}
$totalPages_rsRegister = ceil($totalRows_rsRegister/$maxRows_rsRegister)-1;

$colname_rsUserDetails = "-1";
if (isset($_GET['noid'])) {
  $colname_rsUserDetails = $_GET['noid'];
}
mysql_select_db($database_conRCMS, $conRCMS);
$query_rsUserDetails = sprintf("Select   tcr_citizen.citizen_name,   tcr_register.*,   tcr_state.state_name,   tcr_citizen1.citizen_name As citizen_name1,   tcr_register.no As no1 From   tcr_register Inner Join   tcr_citizen On tcr_register.c_citizen = tcr_citizen.citizen_id Inner Join   tcr_state On tcr_register.a_addState = tcr_state.state_id Inner Join   tcr_citizen tcr_citizen1 On tcr_register.a_citizen = tcr_citizen1.citizen_id Where   tcr_register.no = %s", GetSQLValueString($colname_rsUserDetails, "int"));
$rsUserDetails = mysql_query($query_rsUserDetails, $conRCMS) or die(mysql_error());
$row_rsUserDetails = mysql_fetch_assoc($rsUserDetails);
$totalRows_rsUserDetails = mysql_num_rows($rsUserDetails);

$colname_rsServices = "-1";
if (isset($_GET['noid'])) {
  $colname_rsServices = $_GET['noid'];
}
mysql_select_db($database_conRCMS, $conRCMS);
$query_rsServices = sprintf("SELECT * FROM tcr_services_term WHERE register_id_fk = %s", GetSQLValueString($colname_rsServices, "int"));
$rsServices = mysql_query($query_rsServices, $conRCMS) or die(mysql_error());
$row_rsServices = mysql_fetch_assoc($rsServices);
$totalRows_rsServices = mysql_num_rows($rsServices);

$queryString_rsRegister = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsRegister") == false && 
        stristr($param, "totalRows_rsRegister") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsRegister = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsRegister = sprintf("&totalRows_rsRegister=%d%s", $totalRows_rsRegister, $queryString_rsRegister);
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}


if(isset($_POST['submit'])){
	echo $_POST['status_option'];
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<title>TCR Management | Dashboard</title>
<?php include("meta.php"); ?>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
</head>

<body class="sidebar-left chart">
<?php include("header.php"); ?>

  <div id="page">
    
    <aside>
      <?php include("aside.php"); ?>
    </aside>
    
    
    <div id="page-content" class="container_12"> 
      <a href="#" id="close_sidebar" class="tooltip east" title="Close Sidebar"> Close Sidebar </a>
      <a href="#" id="open_sidebar" class="tooltip east" title="Open Sidebar">Open Sidebar</a>
      
      <div id="page-header">
      <?php if(@$_GET['change'] == true){ ?>
      	<div class="notification success">Status for user no <strong>#<?php echo @$_GET['noid']; ?></strong> already change to <strong><?php echo $_GET['status']; ?></strong>.</div>
      <?php } ?>
	  
      	<?php if ($totalRows_rsUserDetails == 0) { // Show if recordset empty ?>
  <h1>No User.</h1>
  <?php } // Show if recordset empty ?>
  
  		<?php if ($totalRows_rsUserDetails > 0) { // Show if recordset not empty ?>
        <h1>Edit User &raquo; <?php echo $row_rsUserDetails['a_email']; ?> | Status: <?php  
					if($row_rsUserDetails['status'] == 0){
						echo "<strong style=\"color:orange\">Pending</strong>";
					} elseif($row_rsUserDetails['status'] == 1) {
						echo "<strong style=\"color:Green\">Approved</strong>";
					} elseif($row_rsUserDetails['status'] == 2) {
						echo "<strong style=\"color:red\">Rejected</strong>";
					}
					
					?></h1>
         <?php } // Show if recordset not empty ?>
      </div>
      
      <section class="grid_12">
        <?php if ($totalRows_rsUserDetails == 0) { // Show if recordset empty ?>
  <p>No User for this no. <a href="module-user.php">View All users</a></p>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_rsUserDetails > 0) { // Show if recordset not empty ?>
  <form method="post" class="form" action="update_status.php" name="change_status">
    <fieldset id="fKid">
      <legend><a href="#" class="collapse">Services</a></legend>
      <table>
        <tbody>
          <tr>
            <td>
              <?php
						//$rowArray = mysql_fetch_object($rsServices);
						$services_object = explode(",", $row_rsServices['services_store']);
						
						//$row_rsServices;
					?>
              <ul>
                <?php 
					
					
						$services_object; 
						
						foreach($services_object as $key){
							
							$sql_services_name = "SELECT * FROM tcr_services WHERE service_id = '$key'";
							$sql_services_name_result = mysql_query($sql_services_name);
							$sql_services_name_object = mysql_fetch_object($sql_services_name_result);
							echo "<li><strong>".$sql_services_name_object->service_name."</strong></li>";
						}
						
					?>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
    </fieldset>
    <fieldset id="fKid">
      <legend><a href="#" class="collapse">Kid Information</a></legend>
      <table class="table white no-border">
        <tbody>
          <tr>
            <td width="200">No</td>
            <td width="5">:</td>
            <td>#<?php echo $row_rsUserDetails['no']; ?></td>
            </tr>
          <tr>
            <td>Registration Date</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['reg_date']; ?></td>
            </tr>
          <tr>
            <td>Start Date</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['start_date']; ?></td>
            </tr>
          <tr>
            <td>Date of Birth</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['c_dob_day']; ?>/<?php echo $row_rsUserDetails['c_dob__month']; ?>/<?php echo $row_rsUserDetails['c_dob_year']; ?></td>
            </tr>
          <tr>
            <td>Age</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['c_age']; ?></td>
            </tr>
          <tr>
            <td>Gender</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['c_gender']; ?></td>
            </tr>
          <tr>
            <td>Citizen</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['citizen_name']; ?></td>
            </tr>
          <tr>
            <td> Allergies </td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['c_allergies']; ?></td>
            </tr>
          <tr>
            <td>Medical Problem</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['c_medical_problem']; ?></td>
            </tr>
          <tr>
            <td>Age Rank</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['c_agerank']; ?></td>
            </tr>
          <tr>
            <td>Sibling</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['c_sibling']; ?></td>
            </tr>
          <tr>
            <td>Last School</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['c_lastSchool']; ?></td>
            </tr>
          <tr>
            <td>Grade Last School</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['c_gradeLastSchool']; ?></td>
            </tr>
          </tbody>
        </table>
    </fieldset>
    
    <fieldset>
      <legend><a href="#" class="collapse">Parent Information</a></legend>
      <table class="table white no-border">
        <tbody>
          <tr>
            <td width="200">Parent Name</td>
            <td width="5">:</td>
            <td><?php echo $row_rsUserDetails['a_name']; ?></td>
            </tr>
          <tr>
            <td>Relationship</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_relationship']; ?></td>
            </tr>
          <tr>
            <td>Education</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_edu']; ?></td>
            </tr>
          <tr>
            <td>IC/Passport</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_ic_passpord']; ?></td>
            </tr>
          <tr>
            <td>Citizen</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['citizen_name1']; ?></td>
            </tr>
          <tr>
            <td>Address</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_addNo']; ?><br>
              <?php echo $row_rsUserDetails['a_addStreet']; ?><br>
              <?php echo $row_rsUserDetails['a_addPoscode']; ?><br>
              <?php echo $row_rsUserDetails['a_addState']; ?><br></td>
            </tr>
          <tr>
            <td>Home Tel</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_hometel']; ?></td>
            </tr>
          <tr>
            <td>Mobile Tel</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_mtel']; ?></td>
            </tr>
          <tr>
            <td>E-mail</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_email']; ?></td>
            </tr>
          </tbody>
        </table>
    </fieldset>
    
    <fieldset>
      <legend><a href="#" class="collapse">Company / Organization</a></legend>
      <table class="table white no-border">
        <tbody>
          <tr>
            <td width="200">Company / Organization</td>
            <td width="5">:</td>
            <td><?php echo $row_rsUserDetails['a_comp']; ?></td>
            </tr>
          <tr>
            <td>Company / Organization Address</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_com_address']; ?></td>
            </tr>
          <tr>
            <td>Type of Business</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_typeOfBusiness']; ?></td>
            </tr>
          <tr>
            <td>Year(s) Experience</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_yearsThere']; ?></td>
            </tr>
          <tr>
            <td>Position</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_position']; ?></td>
            </tr>
          <tr>
            <td>Company Tel</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_comp_tel']; ?></td>
            </tr>
          <tr>
            <td>Company Fax</td>
            <td>:</td>
            <td><?php echo $row_rsUserDetails['a_comp_fax']; ?></td>
            </tr>
          </tbody>
        </table>
    </fieldset>
    
    <fieldset>
      <legend>Status</legend>
      <table class="table white no-border">
        <tbody>
          <tr>
            <td width="200">Status</td>
            <td width="5">:</td>
            <td><?php  
					if($row_rsUserDetails['status'] == 0){
						echo "<strong style=\"color:orange\">Pending</strong>";
					} elseif($row_rsUserDetails['status'] == 1) {
						echo "<strong style=\"color:Green\">Approved</strong>";
					} elseif($row_rsUserDetails['status'] == 2) {
						echo "<strong style=\"color:red\">Rejected</strong>";
					}
					
					?></td>
            </tr>
          </tbody>
        </table>
    </fieldset>
    
    <fieldset>
      <legend>Action</legend>
      <table class="table white no-border">
        <tbody>
          <tr>
            <td width="200">Change to</td>
            <td width="5">&nbsp;</td>
            <td>
              <span id="spryselect1"><select name="status_option">
                <option value="">Choose Action</option>
                <option value="0">Pending</option>
                <option value="2">Reject</option>
                <option value="1">Approve</option>
              </select><span class="selectRequiredMsg">Please select an item.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="user_no" type="hidden" id="user_no" value="<?php echo $row_rsUserDetails['no']; ?>"></td>
            <td><input type="submit" class="blue small" name="btn_submit" value="Submit" /></td>
          </tr>
        </tbody>
      </table>
    </fieldset>
    
  </form>
  <?php } // Show if recordset not empty ?>
      </section>
      <br class="cl" />
      <br />
      
      
      <!-- Start Layout Example -->
    </div>
    <br class="cl" />
  </div>
<?php include("footer.php"); ?>
<script type="text/javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
</script>
</body>
</html>
<?php
mysql_free_result($rsRegister);

mysql_free_result($rsUserDetails);

mysql_free_result($rsServices);
?>
