<?php

function post_param ($param) {
  if (isset($_POST[$param])) {
    return $_POST[$param];
  }
  return null;
}

function get_param ($param) {
  if (isset($_GET[$param])) {
    return $_GET[$param];
  }
  return null;
}

function strip_string ($str) {
  return preg_replace("/[^a-zA-Z0-9 ]+/", "", $str);
}
