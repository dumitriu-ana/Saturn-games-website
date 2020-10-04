<?php

  require '../resurse.php';

  $ip = $_POST['ip'];
  $id = $_POST['id'];

  $query = "delete from saved_games where
   id_game='".$id."' and user_ip='".$ip."';";

  if($conn->query($query)){
    echo "OK";
  }else{
    echo $conn->error;
  }

?>
