<?php
  require "resurse.php";
  verifica();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Contact us</title>
    <meta name="robots" content="index, follow">
    <meta charset="UTF-8">
    <meta name="description" content="Free games on TA Saturn Games: Shooter, .io, Girls and many more. Come and see for yourself!">
    <meta name="keywords" content="games,shooter,android,mobile,minecraft,online,multiplayer,metin2,galaxy,sports">
    <meta name="author" content="TA Saturn Games">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
      include_dependente();
    ?>

  </head>
  <body>

    <?php
      afiseaza_bara();
    ?>
<br>

  <?php
    $logged = false;
    $user = '';
    if(isset($_SESSION['username'])){
      $logged=true;
      $user = $_SESSION['username'];
    }

    if(isset($_POST['message'])){
      $email = '';
      $message = $_POST['message'];

      if(trim($user) == ''){
        $user = $_POST['username'];
        $email = $_POST['email'];
      }else{
        $selectUser = 'select * from players where username="'.$user.'";';
        $email = $conn->query($selectUser)->fetch_assoc()['email'];
      }

      $insertMessage = 'insert into messages(
          name,
          mail,
          text
        )values(
          "'.$user.'",
          "'.$email.'",
          "'.$message.'"
        );';

        $conn->query($insertMessage);

        alert('Message sent');

        echo '<script>
          $(document).ready(function(){
            $("#inchidere-alert").prop("onclick", null).off("click");
            $("#inchidere-alert").click(function(){
              window.location.replace("contact.php");
            });
          });
        </script>';
    }

  ?>

<div class="container-fluid">
  <div class="col-lg-8 col-lg-offset-2">
    <h2 style="color:white;"><center>Contact us</center></h2>
      <form class="contactus" action="contact.php" method="post">
        <?php
          if(!$logged){
            echo '<div class="form-group">
              <label for="usr">Name:</label>
              <input type="text" class="form-control" id="usr" name="username" required>
            </div><br>
            <div class="form-group">
              <label  for="email">Email address:</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div><br>';
          }
        ?>

        <div class="form-group text-area" style="min-height:25vh !important;">
          <label for="comment">Comment:</label>
          <textarea class="form-control" id="comment" style="resize:none; max-height:70%;" name="message" required></textarea>
        </div>
        <center>
          <input type="submit" name="submit" value="Send" class="btn">
        </center>
      </form>

      <div class="contactus">
        <label>Our email: <a href="mailto:saturn.games.inc@gmail.com">saturn.games.inc@gmail.com</a></label><br>
        <label>Other: <a href="https://www.facebook.com/Saturn-Games-388878681877286/" target="_blank">Facebook</a></label>
      </div>
  </div>
</div>


    <?php
      afiseaza_drepturi();
    ?>

  </body>
</html>
