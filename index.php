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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['Username'])) {
  $loginUsername=$_POST['Username'];
  $password=$_POST['Password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "dashboard.php";
  $MM_redirectLoginFailed = "login_failed.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conRCMS, $conRCMS);
  
  $LoginRS__query=sprintf("SELECT email, password FROM tcr_admin WHERE email=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString(md5($password), "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conRCMS) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">

<title>TCR Management - Login</title>
<meta name="description" content="">
<meta name="author" content="">
<!-- Make sure the latest version of IE is used -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- Place favicon.ico and apple-touch-icon.png in the root of your domain and delete these references -->
<link rel="shortcut icon" href="/favicon.ico">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<!-- CSS - Setup -->
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/grid.css" rel="stylesheet" type="text/css" />
<!-- CSS - Styles -->
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/forms.css" rel="stylesheet" type="text/css" />
<link href="css/lists.css" rel="stylesheet" type="text/css" />
<link href="css/calendar.css" rel="stylesheet" type="text/css" />
<link href="css/extensions.css" rel="stylesheet" type="text/css" />
<!-- Theme  -->
<link id="theme" href="css/themes/blue.css" rel="stylesheet" type="text/css" />

<!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
<script src="js/modernizr-1.5.min.js"></script>
</head>

<!--[if IE 7 ]>    <body class="ie7 login"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8 login"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<body class="login">
<!--<![endif]-->

  <div class="box-header">
    <h2>TCR Management</h2>
  </div>
  <div class="box">
  <div class="notification tip"> Login with your username and password. </div>
    <form action="<?php echo $loginFormAction; ?>" method="POST" class="form col" id="tcrlogin">
      <p>
        <label class="strong" for="Username">Name:</label>
        <input tabindex="1" id="Username" type="text" name="Username" title="Please enter your Username." />
      </p>
      <p>
        <label class="strong" for="Password">Password:</label>
        <input tabindex="2" id="Password" type="password" name="Password" title="Please enter your password." />
      </p>
      <p class="no-margin">
        <label for="RememberMe" style="display:none">
          <input id="RememberMe" type="checkbox">
          Remember Me?</label>
        <button type="submit" class="small fr">Login</button>
        <br class="cl" />
      </p>
    </form>
    <form method="post" class="form" action="" style="display:none">
      <fieldset class="grey collapsed no-margin">
        <legend><a href="#">Forgot Password?</a></legend>
        <p>
          <label for="Email">Enter your e-mail address:</label>
          <input class="fl" id="Email" type="text" name="Email" />
          <button type="button" class="small fr">Send</button>
        </p>
      </fieldset>
    </form>
  </div>

<!-- Javascript at the bottom for fast page loading --> 

<!-- Javascript at the bottom for fast page loading --> 
<script type="text/javascript" src="http://www.google.com/jsapi"></script> 
<!-- Grab Google CDN's jQuery + jQuery UI. fall back to local if necessary --> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
<script>!window.jQuery && document.write('<script src="js/jquery-1.4.2.min.js"><\/script>')</script> 
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script> 
<script>!window.jQuery && document.write('<script src="js/jquery-ui-1.8.1.min.js"><\/script>')</script> 
<script type="text/javascript" src="js/jquery.tipsy.js"></script> 
<script type="text/javascript" src="js/jquery.treeview.min.js"></script> 
<script type="text/javascript" src="js/jquery.cookie.js"></script> 
<script type="text/javascript" src="js/jquery.lightbox-0.5.min.js"></script> 
<script type="text/javascript" src="js/jquery.wysiwyg.js"></script> 
<script type="text/javascript" src="js/functions.js"></script> 
<!--[if lt IE 7 ]>
    <script src="js/dd_belatedpng.js"></script>
  <![endif]-->
</body>
</html>
