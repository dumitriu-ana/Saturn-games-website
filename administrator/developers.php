<?php
  require "resurse.php";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Developers</title>
<meta name="robots" content="noindex, nofollow">
    <?php
      include_dependente_admin();
    ?>
  </head>
  <body>
    <?php
      include_login();
    ?>
    <div class="wrapper">
      <?php
        afiseaza_bara_stanga_admin();
      ?>

      <div id="content">

        <?php
          afiseaza_bara_sus_admin();
        ?>
      <div class="inceput">
        <div class="container-fluid">
          <div class="row linie-sub">
            <div class="col-lg-8">
              <p style="color:black">No developers yet</p>
            </div>
          </div>
          <!--
          <div class="row linie-sub">
            <div class="col-lg-8">
              <a href="#">nume</a>
            </div>
            <div class="col-lg-4">
              <p style="color:black">email</p>
            </div>
          </div>
          <div class="row linie-sub">
            <div class="col-lg-8">
              <a href="#">nume</a>
            </div>
            <div class="col-lg-4">
              <p style="color:black">email</p>
            </div>
          </div>
          <div class="row linie-sub">
            <div class="col-lg-8">
              <a href="#">nume</a>
            </div>
            <div class="col-lg-4">
              <p style="color:black">email</p>
            </div>
          </div>-->

        </div>
      </div>
        <?php
          afiseaza_drepturi_admin();
        ?>
    </div>
  </div>

  </body>
</html>
