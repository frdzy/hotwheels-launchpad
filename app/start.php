<?php

// Get controller and action
if (isset($_SERVER['PATH_INFO'])) {
  $request = trim($_SERVER['PATH_INFO'], '/');
} else {
  $request = "";
}
if (empty($request) && isset($_REQUEST['url'])) {
  $request = trim($_REQUEST['url'], '/');
} else {
  $request = "";
}
$params = explode('/', $request);

print_r($params);
if (count($params) == 0 || $params[0] == "") {
  $params[0] = "public";
}
if (count($params) == 1) {
  $params[1] = "index";
}

$controller_name = $params[0];
$action_name = $params[1];

echo "$controller_name, $action_name";
