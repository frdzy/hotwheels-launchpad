<?php
require_once('post_get.php');
require_once('game.php');


if (get_param('request')) {
  $token = get_param('request');
  error_log("asdf");
  error_log("getrequest [$token]");

  if ($token === 'password') {
    $value = '1995';

    if(isset($_SESSION['team_password'])) {
    }
    else {
      error_log("value ($value)");
      $result = init_game($value);
      error_log("result ($result)");
      if ($result) {
        error_log("freak($result)");
        $_SESSION['team_id'] = $result['lp_team_id'];
        $_SESSION['team_password'] = $value;
        unset($result['lp_team_id']);
        error_log("session={$_SESSION['team_id']}");
        error_log("jsonresult".json_encode($result));
        echo json_encode(array_values($result));
      }
      else {
        error_log("no");
        echo "no";
      }
    }
  }
  else if ($token === "reveal") {
    echo json_encode(reveal('1995'));
  }
  else if ($token === "phrase") {

    $value = get_param('value');
    $result = enter_val($value);
    error_log("entryresult = $result");
    if ($result == 33) {
      echo json_encode(array('redirect' => "/launchpad/rocket_launch.php"));
    }
    else if ($result == 0) {
      echo "no";
    }
    else {
      echo json_encode(array('result' => "$result", 'value' => $value));
    }

  }
  else {
    error_log("no");
    echo "no";
  }
}
