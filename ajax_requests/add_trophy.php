<?php
  require "../resurse.php";
  verifica();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    if(isset($_POST["player"])){
      $points = $conn->query("select * from players where username='".$_POST['player']."';")->fetch_assoc()['trophies'];
      $points++;
      $add_point = "update players set trophies=".$points." where username='".$_POST['player']."';";

      $conn->query($add_point);

    }else{
      echo
      '<script>
        window.location.replace("../index.php");
      </script>';
    }
    ?>
  </body>
</html>
