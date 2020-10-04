<?php
  require "resurse.php";
  verifica();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>About</title>
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
<div class="container-fluid">
<div class="col-lg-8 col-lg-offset-2 despre">
    <h2><center>About us</center></h2>
    <p><center><span style="color:#0caf11">TA Saturn Games</span> is a web gaming platform that features hundreds of thousands games from all around the World Wide Web.
    It also features professional developers' games belonging to various categories, like: <a href="#">3D</a>, <a href="#">cars</a>,
  <a href="#">io</a> and so on.</center></p>
    <br>
    <h2><center>Our team</center></h2>
    <p><center><span style="color:#0caf11">TA Saturn Games</span> is developed by two <span style="color:#af0c0c;">Rom</span><span style="color:#afaa0a;">ani</span><span style="color:#1660dd;">an</span> students:<br><br>
      <span style="color:#09a514;">Ana Maria Dumitriu</span> - She is in charge of the design and good looking of the website. Also, every submission requested by a developer
      is analyzed by her.
      <br><br>
      <span style="color:#09a514;">Radoi Teodor Cristian</span> - He is in charge of everything tech. Any bug report is handled by him.
    </center></p>
    <center><img src="imagini/steag.png" style="width:70%" alt=""></center>
    <center><p>If you would like to get in touch with us do not hesitate to <a href="contact.php">contact us</a>.</p></center>
</div>
</div>

    <?php
      afiseaza_drepturi();
    ?>

  </body>
</html>
