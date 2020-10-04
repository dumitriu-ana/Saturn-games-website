<?php
  require "resurse.php";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Submission</title>
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
          <?php
          $os = iaSistemOperare();
          $mobile = false;
          if($os == "Android" || $os == "iPod" || $os == "iPhone" || $os == "iPad" || $os == "BlackBerry" || $os == "Mobile"){
            $mobile = true;
          }

          if(!$mobile){
            echo '<button type="button" class="btn btn-default btn-danger" onclick="
            window.location.href=\'submissions.php?delete_all=1\';
            ">Delete all</button>';
          }
          ?>

          <?php
            if(isset($_GET["success"])){
              if($_GET["success"]==1){
                echo '<p style="color:#12b722; font-weight:900;">Operation successful!</p>';
              }else{
                echo '<p style="color:#d61d1d; font-weight:900;">Operation failed!</p>';
              }
            }

            if(isset($_GET["game_accept"])){
              $update = "update games set status=2 where permalink='".$_GET["game_accept"]."';";

              if($conn->query($update)){
                echo '<script>
                  window.location.href=\'submissions.php?success=1\';
                </script>';
              }else{
                echo '<script>
                  window.location.href=\'submissions.php?success=0\';
                </script>';
              }
            }else if(isset($_GET["game_delete"])){
              $selectGameDel = "select * from games where permalink='".$_GET["game_delete"]."';";
              $row = $conn->query($selectGameDel)->fetch_assoc();

              if(trim("../jocuri/".$row['name']) != "../jocuri/" && trim($row['name']) != ""){
                deleteDir("../jocuri/".$row['name']);

                $deleteGame = "delete from games where permalink='".$_GET["game_delete"]."';";

                if($conn->query($deleteGame)){
                  echo '<script>
                    window.location.href=\'submissions.php?success=1\';
                  </script>';
                }else{
                  echo '<script>
                    window.location.href=\'submissions.php?success=0\';
                  </script>';
                }
              }else{
                echo '<script>
                  window.location.href=\'submissions.php?success=0\';
                </script>';
              }
            }else if(isset($_GET["delete_all"])){
              if(!$mobile){
                $selectAll = "select * from games where status=1;";

                $resAll = $conn->query($selectAll);

                while($row = $resAll->fetch_assoc()) {
                  if(trim("../jocuri/".$row['name']) != "../jocuri/" && trim($row['name']) != ""){
                    deleteDir("../jocuri/".$row['name']);
                  }
                }

                $delAll = "delete from games where status = 1;";
                if($conn->query($delAll)){
                  echo '<script>
                    window.location.href=\'submissions.php?success=1\';
                  </script>';
                }else{
                  echo '<script>
                    window.location.href=\'submissions.php?success=0\';
                  </script>';
                }
              }
            }
          ?>
          <?php
          $extraSelect = "";

          if($os == "Android" || $os == "iPod" || $os == "iPhone" || $os == "iPad" || $os == "BlackBerry" || $os == "Mobile"){
            $extraSelect = "and isMobile=1";
          }

            $selectSubs = "select * from games where status=1 ".$extraSelect." ;";

            $resSubs = $conn->query($selectSubs);

            if($resSubs->num_rows==0){
              echo '<div class="row linie-sub"><p style="color: #222">No submissions<p></div>';

            }

            while($row = $resSubs->fetch_assoc()) {
              echo '<div class="row linie-sub">';

              echo '<div class="col-lg-8">
                <a href="game-view.php?game='.$row["permalink"].'">'.$row["name"].'</a>';

              $link = '../game/'.$row["permalink"];

              if(trim($extraSelect)!=""){
                $link = '../mobile/game/'.$row["permalink"];
              }

              echo '</div>
              <div class="col-xs-1">
                <button class="btn btn-success"type="button" name="button" onclick="
                  window.location.href=\'submissions.php?game_accept='.$row["permalink"].'\';
                ">Accept</button>
              </div>
              <div class="col-xs-1">
                <button class="btn btn-warning"type="button" name="button" onclick="
                  window.location.href=\'game-view.php?game='.$row["permalink"].'\';
                ">Edit</button>
              </div>
              <div class="col-xs-1">
                <button class="btn btn-danger"type="button" name="button" onclick="
                  window.location.href=\'submissions.php?game_delete='.$row["permalink"].'\';
                ">Delete</button>
              </div>
              <div class="col-xs-1">
                <button class="btn"type="button" name="button" onclick="
                  window.open(\''.$link.'\', \'_blank\');
                ">Preview</button>
              </div>';

              echo '</div>';

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
