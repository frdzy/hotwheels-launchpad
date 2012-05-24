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
  $db = null;
  return $res;
}
