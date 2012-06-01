<?php
require_once('app/init.php');

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

if (false){
  header('Location /launchpad/rocket_launch.php');
}
?>
<html>
<head>
<title>Launch Pad</title>
<link type="text/css" href="http://toystory.caltech.edu/launchpad/style.css" rel="stylesheet" />

<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="app/jit-yc.js"></script>
<script language="javascript" type="text/javascript" src="app/wordweb.js"></script>
<script language="javascript" type="text/javascript" src="app/timer.js"></script>
<?php echo $login_js; ?>
</head>
<body onload='<?php echo $relogin_js; ?>'>

<div style="margin-left: 0px; float:left; padding: 100px 0px 0px 50px;">
  <div>
  <img src="buzz_rocket_2.jpg" />
  </div>
  <div>
  <img src="buzz_rocket_3.jpg" />
  </div>
  <div>
  <script language="JavaScript">
  TargetDate = "05/24/2012 11:50 AM";
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
<center>
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
    <span class="next_phrase_error" style="display:none">Please enter a new valid phrase</span>
    <span class="next_phrase_success" style="display:none"></span>
  </div>
</div>

<div id="center-container">
    <div id="infovis"></div>    
</div>

<div id="inner-details"></div>

<div id="log"></div>
</div>

</div>
</div>
</center>
</div>

</body>
</html>

