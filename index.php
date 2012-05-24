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
<title>Launch Pad</title>
<link type="text/css" href="http://thejit.org/static/v20/Jit/Examples/css/base.css" rel="stylesheet" />

<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="app/jit-yc.js"></script>
<script language="javascript" type="text/javascript" src="app/wordweb.js"></script>
<?php echo $login_js; ?>
</head>
<body onload='<?php echo $relogin_js; ?>'>

<div>
  <div>
  <img src="buzz_rocket_2.jpg" />
  <img src="buzz_rocket_3.jpg" />
  </div>
  <div>
  <script language="JavaScript">
  TargetDate = "05/24/2012 09:20 AM";
  /*BackColor = "palegreen";
  ForeColor = "navy";
  CountActive = true;
  CountStepper = -1;*/
  LeadingZero = true;
  DisplayFormat = "%%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
  FinishMessage = "Ready for blastoff!";
  </script>
  <script language="JavaScript" src="http://scripts.hashemian.com/js/countdown.js"></script>
  </div>
</div>

<div>
  <h1>
    Manual Override Control
  </h1>
</div>

<div id="login">
  <form method="post" name="form">
    Password:
    <input id="lp_password" name="lp_password" type="text" />
    <input id="lp_password_submit" type="submit" value="Submit" class="submit" action="" />
    <div style="height:30px;">
      <span class="error" style="display:none">Please enter a valid password</span>
      <span class="success" style="display:none">Password accepted</span>
      <div id="failtext" style="display:none">Tampering detected, initiating secondary firewall</div>
    </div>
  </form>
</div>

<div id="game" style="display:none">
<div id="container">

<div id="left-container">
  <form method="post" name="form">
  Next Explorable String:
  <input id="lp_next_phrase" name="lp_next_phrase" type="text" />
  <input id="lp_next_phrase_submit" type="submit" value="Submit" class="submit" action="" />
  <div style="height:30px;">
    <span class="next_phrase_error" style="display:none">Please enter a valid phrase</span>
    <span class="next_phrase_success" style="display:none">Password accepted</span>
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

