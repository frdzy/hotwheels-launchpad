<?php
require_once('app/init.php');

if (!extension_loaded('xhp')) {
  echo 'XHP extension not found.\n';
}

?>

<html>
<head>
<link type="text/css" href="http://thejit.org/static/v20/Jit/Examples/css/base.css" rel="stylesheet" />

<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js">
</script>
<script language="javascript" type="text/javascript" src="app/jit-yc.js"></script>
<script language="javascript" type="text/javascript" src="app/wordweb.js"></script>
<script language="javascript" type="text/javascript" src="app/login.js"></script>
</script>
</head>
<body>

<form method="post" name="form">
  Password:
  <input id="lp_password" name="lp_password" type="password" />
  <div >
    <input id="lp_password_submit" type="submit" value="Submit" class="submit" action="" />
    <span class="error" style="display:none">Please enter a valid password</span>
    <span class="success" style="display:none">Password accepted</span>
  </div>
</form>

<div id="txtHint"><b>Person info will be listed here.</b></div>

<div id="container">

<div id="left-container">
</div>

<div id="center-container">
    <div id="infovis"></div>    
</div>

<div id="right-container">

<div id="inner-details"></div>

</div>

<div id="log"></div>
</div>
</body>
</html>

