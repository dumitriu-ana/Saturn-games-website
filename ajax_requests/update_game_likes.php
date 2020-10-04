<?php
  require "../resurse.php";
  verifica();
?>
    <?php
    if(isset($_POST["ip"]) && isset($_POST["status"]) && isset($_POST["game"])){

      $ip = $_POST["ip"];
      $gameId = $_POST["game"];
      $status = (int)$_POST["status"];

      $selectLike = "select * from games_likes where ip_player='".$ip."' and id_game='".$gameId."';";

      $result = $conn->query($selectLike);

      if($result->num_rows == 0){
        $insertLike = "insert into games_likes(
          ip_player,
          id_game,
          status
          )values(
            '".$ip."',
            '".$gameId."',
            '".$status."'
          );";
        if($conn->query($insertLike)){

          $likes = 0;
          $dislikes = 0;

          $selectLikesGame = "select * from games where id='".$gameId."';";
          $likes = $conn->query($selectLikesGame)->fetch_assoc()['likes'];
          $dislikes = $conn->query($selectLikesGame)->fetch_assoc()['dislikes'];

          if($status==1){
            $likes++;
            $updateLikes = "update games set likes=".$likes." where id=".$gameId.";";
            if($conn->query($updateLikes)){
              echo 'ok';
            }
          }else if($status==2){
            $dislikes++;
            $updateLikes = "update games set dislikes=".$dislikes." where id=".$gameId.";";
            if($conn->query($updateLikes)){
              echo 'ok';
            }
          }
        }
      }else{

        $oldStatus = $result->fetch_assoc()['status'];
        $updateStatus = "update games_likes set status=".$status." where ip_player='".$ip."';";
        if($conn->query($updateStatus)){

          $selectLikesGame = "select * from games where id='".$gameId."';";
          $likes = $conn->query($selectLikesGame)->fetch_assoc()['likes'];
          $dislikes = $conn->query($selectLikesGame)->fetch_assoc()['dislikes'];

          if($status==1){
            $likes++;
            $dislikes--;

            $updateLikes = "update games set likes=".$likes.", dislikes=".$dislikes." where id=".$gameId.";";
            if($conn->query($updateLikes)){
              echo 'ok';
            }
          }else if($status==2){
            $dislikes++;
            $likes--;

            $updateLikes = "update games set likes=".$likes.", dislikes=".$dislikes." where id=".$gameId.";";
            if($conn->query($updateLikes)){
              echo 'ok';
            }
          }
        }
      }
    }else{
      echo
      '<script>
        window.location.replace("../index.php");
      </script>';
    }
    ?>
