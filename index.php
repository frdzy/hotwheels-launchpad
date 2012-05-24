<?php
require_once('app/init.php');

if (!extension_loaded('xhp')) {
  echo 'XHP extension not found.\n';
}

?>

<html>
<head>
<link type="text/css" href="http://thejit.org/static/v20/Jit/Examples/css/base.css" rel="stylesheet" />

<script language="javascript" type="text/javascript" src="app/jit-yc.js"></script>
<script language="javascript" type="text/javascript" src="app/wordweb.js"></script>
</head>
<body>

-<form>
-<select name="users" onchange="init()">
-<option value="">Select a person:</option>
-<option value="1">Peter Griffin</option>
-<option value="2">Lois Griffin</option>
-<option value="3">Glenn Quagmire</option>
-<option value="4">Joseph Swanson</option>
-</select>
-</form>

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

