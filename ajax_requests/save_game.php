<?php

  require '../resurse.php';

  $ip = $_POST['ip'];
  $id = $_POST['id'];

  $query = "insert into saved_games(
    user_ip,
    id_game
    )values(
    '".$ip."',
    '".$id."'
  );";

  if($conn->query($query)){
    echo "OK";
  }else{
    echo $conn->error;
  }

?>
