<?php
function lloyd_db_connect() {
  $host = HOST;
  $dbname = DB;
  $user = USER;
  $password = PASSWORD;

  $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
  $dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $dbh;
}

