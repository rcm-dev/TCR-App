<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<header>
  <div class="header-top tr">
    <p>Hello <strong><?php echo ucfirst($_SESSION['MM_Username']); ?></strong> | <a href="<?php echo $logoutAction ?>">Logout</a></p>
  </div>
  <div class="header-middle"> 
    <!-- Start Nav -->
    <ul id="nav" class="fr ">
      <!-- Nav - Start Help -->
      <li class="help"><a class="help" href="#">Help</a>
        <ul>
          <li><a href="#">Contact</a></li>
        </ul>
      </li>
      <!-- Nav - End Help --> 
      <!-- Nav - Start Settings -->
      <li class="settings"><a class="settings" href="#">Modules</a>
        <ul>
          <li><a href="module-user.php">Users</a></li>
          <li><a href="module-services.php">Services</a></li>
        </ul>
      </li>
      <!-- Nav - End Settings --> 
      <!-- Nav - Start Users -->
      <li class="dashboard"><a class="dashboard" href="dashboard.php">Dashboard</a></li>
      <!-- Nav - End Dashboard -->
    </ul>
    <!-- End Nav --> 
    <!-- Start Logo --> 
    <img id="logo" src="img/logo.png" alt="Admin Theme" /> 
    <!-- End Logo --> 
    <br class="cl" />
  </div>
  <div class="header-bottom"> 
    <!-- Start Breadcrumbs -->
    <ul id="breadcrumbs">
    You are here : 
      <li>
      <a href="#">
      Dashboard
      </a>
      </li>
    </ul>
    <!-- End Breadcrumbs --> 
  </div>
</header>