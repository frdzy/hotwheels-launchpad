<?php
require_once('post_get.php');
require_once('game.php');

if (get_param('request')) {
  $token = get_param('request');
  error_log("asdf");
  error_log("getrequest [$token]");

  if ($token === 'password') {
    $value = '';
    if (array_key_exists('value', $_GET)) {
      $value = $_GET['value'];
    }

    error_log("value ($value)");
    $result = init_game($value);
    error_log("result ($result)");
    if ($result) {
      error_log(json_encode($result));
      echo json_encode($result);
    }
    else {
      error_log("no");
      echo "no";
    }
  }
  else {
    error_log("no");
    echo "no";
  }
}
