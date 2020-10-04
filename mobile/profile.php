<?php

  require "resurse.php";

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>My profile</title>
    <?php

      include_dependente_mobile();

    ?>

    <meta name="robots" content="index, follow">
    <meta charset="UTF-8">
    <meta name="description" content="Free games on TA Saturn Games: Shooter, .io, Girls and many more. Come and see for yourself!">
    <meta name="keywords" content="games,shooter,android,mobile,minecraft,online,multiplayer,metin2,galaxy,sports">
    <meta name="author" content="TA Saturn Games">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  </head>
  <body>
    <?php
      arata_bara_mobile();

      $name = "";
      $level = 1;
      $points = 1;
      $trophies = 0;
      $description = "";

      if(isset($_SESSION['username'])){

        $name = $_SESSION['username'];
        $selectP = "select * from players where username='".$name."';";
        $res = $conn->query($selectP);

        if($res->num_rows){
          $row = $res->fetch_assoc();

          $points = $row['points'];
          $trophies = $row['trophies'];
          $description = $row['description'];

        }else{
          echo '<script>
            window.location.replace("'.$dir.'home");
          </script>';
        }

      }else{
        echo '<script>
          window.location.replace("'.$dir.'home");
        </script>';
      }

      $pointsNextLVL = $level*$level*50;

      while($points >= $pointsNextLVL){
        $changedLevel=true;
        $level++;
        $points-=$pointsNextLVL;
        $pointsNextLVL = $level*$level*50;
      }

      $procent = (100*$points)/($level*$level*50);

      $update = "update players set points='".$points."' where username='".$username."';";
      if(!$conn->query($update)){
        alert("DB error");
        echo '<script>
          $(document).ready(function(){
            $("#inchidere-alert").prop("onclick", null).off("click");
            $("#inchidere-alert").click(function(){
              window.location.replace("/home");
            });
          });
        </script>';
        die();
      }

      $rankString = file_get_contents("../resurse/rank.txt");
      $rankArray = explode(',', $rankString);

      for ($i=0; $i < count($rankArray); $i++) {
        $rankArray[$i] = trim($rankArray[$i]);
      }

      $rank = explode('-', $rankArray[0])[1];
      $c = 1;
      while($level >= (int)explode('-', $rankArray[$c])[0]){
        $rank = explode('-', $rankArray[$c])[1];
        $c++;
      }

    ?>

    <div class="profil">
      <div class="row">
        <img  class="control img-responsive center-block" style="width:25% !important;" src="<?php
         if(($isMine && isset($_SESSION['username']) && file_exists("../Players/".$_SESSION['username']."/icon.png"))
         || (!$isMine && file_exists("../Players/".$name."/icon.png")))
         {
           echo $dir.'../Players/'.$name."/icon.png?".rand(10,10000);
         }
         else{
           echo $dir.'../imagini/imgdeprofil.png?'.rand(10,10000);
         } ?>" alt="">
        <p align="center"><?php echo $name; ?></p>

        <center>
          <?php
            if($isMine){
              echo '<label class="myLabel">
                      <input id="upload-but" type="file" name="profile-icon"/>
                      <span>Upload profile icon</span>
                    </label>
                    <p id="upload-tx" style="color:#1fbc1a;"></p>';
            }
            echo '<p style="text-shadow: 0 0 10px red;">Rank: '.$rank.'</p>';
          ?>

        </center>
      </div>
      <br>

      <div class="row">
        <p align="center"><?php echo "Level: ".$level; ?></p>
        <div class="col-xs-8 col-xs-offset-2 progress control" style="padding:0;">
          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $procent;?>"
          aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $procent; ?>%;">
            <center style="color:#fff; font-weight:900;"><?php echo $points.'/'.$pointsNextLVL."XP"; ?> </center>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <img class="control img-responsive center-block" src="../imagini/scor.png" style="width:20%" alt=""><br>
        <p align="center"><?php echo "Points: ".$points; ?></p>
      </div>
      <div class="col-lg-6">
        <img class="control img-responsive center-block"style="width:20%" src="../imagini/trofeu.png" alt=""><br>
        <p align="center"><?php echo "Trophies: ".$trophies; ?></p>
      </div>

      <div class="row">
        <div class="col-lg-10 col-lg-offset-1 ">
          <label for="comment"><p>Description: </p></label>
          <?php
            echo '<textarea class="control form-control" maxlength="350" rows="5" id="comment" style="resize: none;" name="description" '.($isMine==false?"disabled":"").'>'.$description.'</textarea>';
          ?>
        </div>
      </div>
    </div>

    <?php

      arata_drepturi_mobile();

    ?>
  </body>
</html>
