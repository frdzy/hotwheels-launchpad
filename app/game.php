<?php
require_once('pdo_connect.php');
require_once('post_get.php');


function check_pwd ($pwd) {
}

function init_game($pwd) {
  $db = lloyd_db_connect();
  $pwd = strip_string($pwd);
  $init_exec = "CALL first_login('$pwd')";
  $res = $db->query($init_exec)->fetch();
  error_log("derpderp $res");

  if ($res !== null) {
    $graph_init_exec = "SELECT * FROM launchpad_init;";
    $graph_init_query = $db->query($graph_init_exec);

    foreach ($graph_init_query as $row) {
      error_log("row=$row");
    }
  }

  $db = null;
  return $res;
}
