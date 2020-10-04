<?php
  require "../resurse.php";
  verifica();

if(isset($_POST["game"]) && isset($_POST["player"]) && isset($_POST["comm"])){
  $insert = "insert into games_comms(
      player,
      id_game,
      comment
    )values(
      '".$conn->real_escape_string($_POST["player"])."',
      '".$conn->real_escape_string($_POST["game"])."',
      '".$conn->real_escape_string($_POST["comm"])."'
    );";
    if($conn->query($insert)){
      echo 'ok';
    }
}
?>
