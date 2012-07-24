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

$maxRows_rsServices = 20;
$pageNum_rsServices = 0;
if (isset($_GET['pageNum_rsServices'])) {
  $pageNum_rsServices = $_GET['pageNum_rsServices'];
}
$startRow_rsServices = $pageNum_rsServices * $maxRows_rsServices;

mysql_select_db($database_conRCMS, $conRCMS);
$query_rsServices = "SELECT * FROM tcr_services";
$query_limit_rsServices = sprintf("%s LIMIT %d, %d", $query_rsServices, $startRow_rsServices, $maxRows_rsServices);
$rsServices = mysql_query($query_limit_rsServices, $conRCMS) or die(mysql_error());
$row_rsServices = mysql_fetch_assoc($rsServices);

if (isset($_GET['totalRows_rsServices'])) {
  $totalRows_rsServices = $_GET['totalRows_rsServices'];
} else {
  $all_rsServices = mysql_query($query_rsServices);
  $totalRows_rsServices = mysql_num_rows($all_rsServices);
}
$totalPages_rsServices = ceil($totalRows_rsServices/$maxRows_rsServices)-1;

mysql_select_db($database_conRCMS, $conRCMS);
$query_rsAvailableServices = "SELECT * FROM tcr_services";
$rsAvailableServices = mysql_query($query_rsAvailableServices, $conRCMS) or die(mysql_error());
$row_rsAvailableServices = mysql_fetch_assoc($rsAvailableServices);
$totalRows_rsAvailableServices = mysql_num_rows($rsAvailableServices);

$queryString_rsServices = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsServices") == false && 
        stristr($param, "totalRows_rsServices") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsServices = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsServices = sprintf("&totalRows_rsServices=%d%s", $totalRows_rsServices, $queryString_rsServices);
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
<title>TCR Management | Services Module</title>
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
        <h1>Services Module</h1>
      </div>
      <p>Current available services <a href="module-services-new.php">Add New Services</a></p>
      <section class="grid_12" id="dashtabs">
        <?php if ($totalRows_rsServices > 0) { // Show if recordset not empty ?>
  <div class="box-header">
   Available Services
  </div>
        <div id="dashtabs-pages" class="box-content no-padding">
            
          </div>
        <div id="dashtabs-users" class="box-content no-padding">
          <table class="table no-border">
            <thead>
              <tr>
                <td>Name</td>
                <td>Start Time</td>
                <td>End Time</td>
                <td class="last">Options</td>
              </tr>
            </thead>
            <tbody>
              <?php do { ?>
                <tr>
                  <td><?php echo $row_rsServices['service_name']; ?></td>
                  <td align="center"><?php if(!isset($row_rsServices['start_time'])) {echo "-";} else {echo $row_rsServices['start_time'];} ?></td>
                  <td align="center"><?php if(!isset($row_rsServices['end_time'])) {echo "-";} else {echo $row_rsServices['end_time'];} ?></td>
                  <td class="last"><a href="user-edit.php?noid=<?php echo $row_rsServices['no']; ?>" class="tooltip" title="Edit Services"><img alt="edit user" src="./img/icons/16/page_edit.png"></a> <a href="#" class="tooltip" title="Delete User"><img alt="delete Services" src="./img/icons/16/page_delete.png"></a></td>
                </tr>
                <?php } while ($row_rsServices = mysql_fetch_assoc($rsServices)); ?>
            </tbody>
          </table>
          </div>
        <div id="dashtabs-comments" class="box-content no-padding">
            
          </div>
        <div class="box-footer"> <span class="txt-smaller txt-light">
Showing <?php echo ($startRow_rsServices + 1) ?> results <?php echo min($startRow_rsServices + $maxRows_rsServices, $totalRows_rsServices) ?> of <?php echo $totalRows_rsServices ?> </span>
          <table border="0" class="pagination">
            <tr>
              <td><?php if ($pageNum_rsServices > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_rsServices=%d%s", $currentPage, 0, $queryString_rsServices); ?>"><< First</a>
                  <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_rsServices > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_rsServices=%d%s", $currentPage, max(0, $pageNum_rsServices - 1), $queryString_rsServices); ?>">< Previous</a>
                  <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_rsServices < $totalPages_rsServices) { // Show if not last page ?>
                  <a href="<?php printf("%s?pageNum_rsServices=%d%s", $currentPage, min($totalPages_rsServices, $pageNum_rsServices + 1), $queryString_rsServices); ?>">Next ></a>
                  <?php } // Show if not last page ?></td>
              <td><?php if ($pageNum_rsServices < $totalPages_rsServices) { // Show if not last page ?>
                  <a href="<?php printf("%s?pageNum_rsServices=%d%s", $currentPage, $totalPages_rsServices, $queryString_rsServices); ?>">Last >></a>
                  <?php } // Show if not last page ?></td>
            </tr>
          </table>
        </div>
          <?php } // Show if recordset not empty ?>
          <?php if ($totalRows_rsServices == 0) { // Show if recordset empty ?>
  <div class="notification info" style="display: block; "> <span class="strong">Info:</span> No registration at the moment. <span class="close" title="Dismiss"></span></div>
  <?php } // Show if recordset empty ?>
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
mysql_free_result($rsServices);

mysql_free_result($rsAvailableServices);
?>
