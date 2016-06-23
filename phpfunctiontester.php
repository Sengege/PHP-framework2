<html>
<?php
require 'scripts/prepend.php';
include 'scripts/addNotification.php';
include 'scripts/readNotification.php';



  if (isset($_GET['hello'])) {
   //if you want to test a function include it above then enter a new line below and comment out the rest
   addNotification("New Message",26,92,null);
   //readNotification(120);
  }
 
?>

Hello there!
<a href='?hello=true'>Run PHP Function</a>
</html>