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
    if(isset($_POST["id"]) && isset($_POST["plays"])){
      $update = "update games set tot_players=".($_POST['plays']+1)." where id=".$_POST['id'].";";
      $conn->query($update);
      echo $conn->error;
    }else{
      echo
      '<script>
        window.location.replace("../index.php");
      </script>';
    }
    ?>
  </body>
</html>
