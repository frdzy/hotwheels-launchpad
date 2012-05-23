<?php

if (array_key_exists('request', $_GET)) {
  $token = $_GET['request'];

  if ($token === 'password') {
    $value = '';
    if (array_key_exists('value', $_GET)) {
      $value = $_GET['value'];
    }
    echo "password = $value";
  }
  else {
    echo "nope";
  }
}

