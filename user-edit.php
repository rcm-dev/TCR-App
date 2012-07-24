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
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<title>TCR Management | Dashboard</title>
<?php include("meta.php"); ?>
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
        <h1>Edit User &raquo; </h1>
      </div>
      
      <section class="grid_12">
      	<form method="post" class="form" action="">
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
      </form>
      </section>
      <br class="cl" />
      <br />
      
      
      <!-- Start Layout Example -->
    </div>
    <br class="cl" />
  </div>
<?php include("footer.php"); ?>
</body>
</html>
<?php
mysql_free_result($rsRegister);

mysql_free_result($rsUserDetails);
?>
