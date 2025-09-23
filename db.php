<?php
// Enter your Host, username, password, database below.
// I left password empty because i do not set password on localhost.
$con = mysqli_connect("v8d.h.filess.io","QfsLedger_fieldzulu","46557c4ef79234b6221acecd840ea63f1f9c8534","QfsLedger_fieldzulu","3306");
//$con = mysqli_connect("sql7.freemysqlhosting.net","sql7748837","L2CSaDcTLK","sql7748837","3306");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>
