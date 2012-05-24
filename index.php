<?php
require_once('app/init.php');
session_start();

if (!extension_loaded('xhp')) {
  echo 'XHP extension not found.\n';
}

if(isset($_SESSION['team_id']) && isset($_SESSION['team_password'])) {
  $login_js = '<script language="javascript" type="text/javascript" src="app/relogin.js"></script>';
  $relogin_js = 'relogin("' . $_SESSION['team_id'] . '", "' . $_SESSION['team_password'] . '");';
}
else {
  $login_js = '<script language="javascript" type="text/javascript" src="app/login.js"></script>';
  $relogin_js = '';
}

?>
<html>
<head>
<link type="text/css" href="http://thejit.org/static/v20/Jit/Examples/css/base.css" rel="stylesheet" />

<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="app/jit-yc.js"></script>
<script language="javascript" type="text/javascript" src="app/wordweb.js"></script>
<?php echo $login_js; ?>
</head>
<body onload='<?php echo $relogin_js; ?>'>

<div id="login">
  <form method="post" name="form">
    Password:
    <input id="lp_password" name="lp_password" type="text" />
    <input id="lp_password_submit" type="submit" value="Submit" class="submit" action="" />
    <div style="height:30px;">
      <span class="error" style="display:none">Please enter a valid password</span>
      <span class="success" style="display:none">Password accepted</span>
    </div>
  </form>
</div>

<div id="game" style="display:none">
<div id="container">

<div id="left-container">
  <form method="post" name="form">
  Disarm Code:
  <input id="lp_disarm" name="lp_disarm" type="text" />
  <input id="lp_disarm_submit" type="submit" value="Submit" class="submit" action="" />
  <div style="height:30px;">
    <span class="disarm_error" style="display:none">Please enter a valid password</span>
    <span class="disarm_success" style="display:none">Password accepted</span>
  </div>
</div>

<div id="center-container">
    <div id="infovis"></div>    
</div>

<div id="right-container">

<div id="inner-details"></div>

</div>

<div id="log"></div>
</div>
<div id="credits" style="vertical-align:text-bottom;">Special thanks to Xida</div>
</div>

</body>
</html>

