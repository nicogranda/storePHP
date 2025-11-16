
<?php
  $db_host = 'localhost';
  $db_user = 'ot2ryobi838h';
  $db_password = 'Ga872680481$';
  $db_db = 'ikusa';
 
  $mysqli = @new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db
  );


  if ($mysqli->connect_error) {
    echo 'Errno: '.$mysqli->connect_errno;
    echo '<br>';
    echo 'Error: '.$mysqli->connect_error;
    exit();
  }

?>

