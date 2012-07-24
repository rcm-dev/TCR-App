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

$maxRows_rsAllRegister = 20;
$pageNum_rsAllRegister = 0;
if (isset($_GET['pageNum_rsAllRegister'])) {
  $pageNum_rsAllRegister = $_GET['pageNum_rsAllRegister'];
}
$startRow_rsAllRegister = $pageNum_rsAllRegister * $maxRows_rsAllRegister;

mysql_select_db($database_conRCMS, $conRCMS);
$query_rsAllRegister = "SELECT * FROM tcr_register ORDER BY reg_date DESC";
$query_limit_rsAllRegister = sprintf("%s LIMIT %d, %d", $query_rsAllRegister, $startRow_rsAllRegister, $maxRows_rsAllRegister);
$rsAllRegister = mysql_query($query_limit_rsAllRegister, $conRCMS) or die(mysql_error());
$row_rsAllRegister = mysql_fetch_assoc($rsAllRegister);

if (isset($_GET['totalRows_rsAllRegister'])) {
  $totalRows_rsAllRegister = $_GET['totalRows_rsAllRegister'];
} else {
  $all_rsAllRegister = mysql_query($query_rsAllRegister);
  $totalRows_rsAllRegister = mysql_num_rows($all_rsAllRegister);
}
$totalPages_rsAllRegister = ceil($totalRows_rsAllRegister/$maxRows_rsAllRegister)-1;

mysql_select_db($database_conRCMS, $conRCMS);
$query_rsPending = "SELECT * FROM tcr_register WHERE status = 0";
$rsPending = mysql_query($query_rsPending, $conRCMS) or die(mysql_error());
$row_rsPending = mysql_fetch_assoc($rsPending);
$totalRows_rsPending = mysql_num_rows($rsPending);

$queryString_rsAllRegister = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsAllRegister") == false && 
        stristr($param, "totalRows_rsAllRegister") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsAllRegister = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsAllRegister = sprintf("&totalRows_rsAllRegister=%d%s", $totalRows_rsAllRegister, $queryString_rsAllRegister);
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
<title>TCR Management | User Module</title>
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
        <h1>User Module</h1>
      </div>
      <p>List of User Register</p>
      <section class="grid_12" id="dashtabs">
        <?php if ($totalRows_rsAllRegister > 0) { // Show if recordset not empty ?>
  <div class="box-header">
   All user
  </div>
        <div id="dashtabs-pages" class="box-content no-padding">
            
          </div>
        <div id="dashtabs-users" class="box-content no-padding">
          <div class="notification info no-margin"> <span class="strong">Information</span> There are <strong style="color:red"><?php echo $totalRows_rsPending ?></strong> users pending approval. </div>
          <table class="table no-border">
            <thead>
              <tr>
                <td>Name</td>
                <td>Email</td>
                <td>Registered</td>
                <td>Status</td>
                <td class="last">Options</td>
              </tr>
            </thead>
            <tbody>
              <?php do { ?>
                <tr>
                  <td><?php echo $row_rsAllRegister['a_name']; ?></td>
                  <td><?php echo $row_rsAllRegister['a_email']; ?></td>
                  <td><?php echo date('l, d/m/Y',strtotime($row_rsAllRegister['reg_date'])); ?></td>
                  <td>
				  <?php 
				  
				  if($row_rsAllRegister['status'] == 0){
					echo "<strong style=\"color:orange\">Pending</strong>";  
				  } elseif($row_rsAllRegister['status'] == 1) {
					  echo "<strong style=\"color:green\">Approved</strong>"; 
				  } elseif($row_rsAllRegister['status'] == 2) {
					  echo "<strong style=\"color:red\">Rejected</strong>"; 
				  }
				  
				  ?></td>
                  <td class="last"><a href="user-edit.php?noid=<?php echo $row_rsAllRegister['no']; ?>" class="tooltip" title="Edit User"><img alt="edit user" src="./img/icons/16/user_edit.png"></a> <a href="#" class="tooltip" title="Delete User"><img alt="delete user" src="./img/icons/16/user_delete.png"></a></td>
                </tr>
                <?php } while ($row_rsAllRegister = mysql_fetch_assoc($rsAllRegister)); ?>
            </tbody>
          </table>
          </div>
        <div id="dashtabs-comments" class="box-content no-padding">
            
          </div>
        <div class="box-footer"> <span class="txt-smaller txt-light">
Showing <?php echo ($startRow_rsAllRegister + 1) ?> results <?php echo min($startRow_rsAllRegister + $maxRows_rsAllRegister, $totalRows_rsAllRegister) ?> of <?php echo $totalRows_rsAllRegister ?> </span>
          <table border="0" class="pagination">
            <tr>
              <td><?php if ($pageNum_rsAllRegister > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_rsAllRegister=%d%s", $currentPage, 0, $queryString_rsAllRegister); ?>"><< First</a>
                  <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_rsAllRegister > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_rsAllRegister=%d%s", $currentPage, max(0, $pageNum_rsAllRegister - 1), $queryString_rsAllRegister); ?>">< Previous</a>
                  <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_rsAllRegister < $totalPages_rsAllRegister) { // Show if not last page ?>
                  <a href="<?php printf("%s?pageNum_rsAllRegister=%d%s", $currentPage, min($totalPages_rsAllRegister, $pageNum_rsAllRegister + 1), $queryString_rsAllRegister); ?>">Next ></a>
                  <?php } // Show if not last page ?></td>
              <td><?php if ($pageNum_rsAllRegister < $totalPages_rsAllRegister) { // Show if not last page ?>
                  <a href="<?php printf("%s?pageNum_rsAllRegister=%d%s", $currentPage, $totalPages_rsAllRegister, $queryString_rsAllRegister); ?>">Last >></a>
                  <?php } // Show if not last page ?></td>
            </tr>
          </table>
        </div>
          <?php } // Show if recordset not empty ?>
          <?php if ($totalRows_rsAllRegister == 0) { // Show if recordset empty ?>
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
mysql_free_result($rsAllRegister);

mysql_free_result($rsPending);
?>
