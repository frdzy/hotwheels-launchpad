<?php
require_once('post_get.php');
require_once('game.php');

session_start();

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
      $_SESSION['team_id'] = $result['lp_team_id'][0];
      $_SESSION['team_password'] = $value;
      unset($result['lp_team_id']);
      error_log("session={$_SESSION['team_id']}");
      error_log(json_encode($result));
      echo json_encode(array_values($result));
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
