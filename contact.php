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
        <h1>Contact</h1>
      </div>
      <p>Hi <strong><?php echo ucfirst($_SESSION['MM_Username']); ?></strong>, If you have any question please contact us.</p>
      
      <!-- Content -->
      <br class="cl" />
      
	<p>
    <strong>Rich Core Media</strong><br/>
    B201, Block B, Level 2, Phileo Damansara II,<br/>
	15, Jalan 16/11, 46350, Petaling Jaya, Selangor, Malaysia.<br/>
    Tel: <strong>013 246 5974</strong> Email: <a href="mailto:mahfudz@richcoremedia.com">mahfudz@richcoremedia.com</a>
    </p>
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
?>
