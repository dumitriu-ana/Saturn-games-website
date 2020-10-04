<?php
  require "resurse.php";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Submit</title>
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
          <?php

          if(isset($_GET['id'])){
            $deleteRep = "delete from reports where id=".$_GET['id'].";";

            if($conn->query($deleteRep)){
              echo '<script>
                window.location.replace("reports.php?success=1");
              </script>';
            }else{
              echo '<script>
                window.location.replace("reports.php?success=0");
              </script>';
            }

          }else if(isset($_GET['success'])){
            if($_GET['success']==1){
              echo '<p style="color:#12b722; font-weight:900;">Operation successful!</p>';
            }else{
              echo '<p style="color:#d61d1d; font-weight:900;">Operation failed!</p>';
            }
          }

          ?>

          <div class="container-fluid">
            <?php
              $selectMess = "select * from reports order by id desc;";
              $resultMess = $conn->query($selectMess);

              if($resultMess->num_rows > 0){
                while($row = $resultMess->fetch_assoc()) {
                  echo '<div class="row linie-mesaj">
                  <div class="col-lg-8">
                    <p><b>From: '.$row["fromEmail"].'</b></p>
                  </div>
                  <div class="col-lg-4">
                    <p><b>For: '.$row["toEmail"].'</b></p>
                  </div>
                  <div class="col-lg-12">
                    <p><b>'.$row["text"].'</b></p>
                  </div>
                  <div class="col-lg-4 offset-lg-8">
                    <p>Date: '.$row["date"].'</p>
                  </div>
                  <button class="btn btn-danger" type="button" name="button" onclick="
                    window.location.href=\'reports.php?id='.$row["id"].'\';
                  " style="width:100%;">Delete</button>
                  </div>';

                }
              }else{
                echo '<div class="row linie-mesaj">
                <div class="col-lg-12">
                  <p>No reports</p>
                </div>
                </div>';
              }

            ?>
          </div>

        </div>

        <?php
          afiseaza_drepturi_admin();
        ?>
    </div>
  </div>

  </body>
</html>
