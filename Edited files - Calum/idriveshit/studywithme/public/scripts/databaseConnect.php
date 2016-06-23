<?php
// User details in these variables:
$username = "Max";
$password = "L1nuxP0wa";

try {
  $conn = new PDO('mysql: host=mydbinstance.c1wj4jomkfkw.eu-west-1.rds.amazonaws.com:3306; dbname=studywithme', $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
  echo 'ERROR: '.$e->getMessage();
}
?>