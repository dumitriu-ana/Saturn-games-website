<?php
require "resurse.php";
verifica();
 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <meta name="robots" content="index, follow">
     <meta charset="UTF-8">
     <meta name="description" content="Free games on TA Saturn Games: Shooter, .io, Girls and many more. Come and see for yourself!">
     <meta name="keywords" content="games,shooter,android,mobile,minecraft,online,multiplayer,metin2,galaxy,sports">
     <meta name="author" content="TA Saturn Games">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <title><?php
      if(isset($_GET["player"])){
        echo $_GET["player"]." - User";
      }else{
        echo "My profile";
      }
     ?></title>
     <?php
       include_dependente();
      ?>

      <style media="screen">
        .profil-form button{
          border: 2px solid #1fbc1a;
          color: #1fbc1a;
          background-color: black;
          padding: 8px 20px;
          border-radius: 8px;
          font-size: 20px;
          font-weight: bold;
          font-size: 1.5vw;
        }

        .profil-form button:hover{
          border: 2px solid #1fbc1a !important;
          color: #1fbc1a !important;
          background-color: black !important;
          text-shadow: 0 0 10px white;
          transition: text-shadow .3s;
        }

        .profil-form button:active{
          border: 2px solid #1fbc1a !important;
          color: #1fbc1a !important;
          background-color: black !important;
          text-shadow: 0 0 10px white;
        }

        label.myLabel input[type="file"] {
            display: none;
        }

        .myLabel {
            border: 2px solid #1fbc1a;
            border-radius: 4px;
            padding: 2px 5px;
            margin: 2px;
            background: #000;
            display: inline-block;
        }
        .myLabel:hover {
            background: #000;
        }
        .myLabel:active {
            background: #000;
        }
        .myLabel :invalid + span {
            color: #1fbc1a;
        }
        .myLabel :valid + span {
            color: #1fbc1a;
        }
      </style>

      <script type="text/javascript">
        $(document).ready(function(){

          $("#report-but").click(
            function(){
              $(".report-pan").fadeIn(250);
              $(".report").show(250);
            }
          );

          $("#upload-but")[0].onchange = function(e) {
            var filename = $('input[type=file]').val().replace(/C:\\fakepath\\/i, '');
            var ext = getExtension(filename);

            var imgExts = ["jpg", "png", "jpeg"];

            if(ext=="jpg" || ext == "jpeg" || ext == "png"){
              $("#upload-tx").text(filename);
            }else{
              $('input[type=file]').val("");
              alert_b("You should upload only jpg, jpeg or png files.");
            }
          };

          $("#save-but").click(
            function(){
              var ok = true;
              var tx = $("#comment").val();

              if(!alphanumeric_text(tx)){
                ok = false;
              }

              if(ok){
                $(".profil-form").submit();
              }else{
                alert_b("You can use only letters, numbers and punctuation in description.");
              }
            }
          );

        });
        function getExtension(filename){
          return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
        }
      </script>

   </head>
   <body>
     <?php
        afiseaza_bara();
      ?>

      <?php

        $isMine = false;

        $name = "";
        $level = 1;
        $points = 1;
        $trophies = 0;
        $description = "";

        if(isset($_GET['player'])){
          $name=$_GET['player'];
          $select = "select * from players where username='".$name."';";
          $result = $conn->query($select);

          if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            $name = $row['username'];
            $points = $row['points'];
            $trophies = $row['trophies'];
            $description = $row['description'];
          }else{
            echo
            '<script>
              window.location.replace("'.$dir.'home");
            </script>';
          }
          if(isset($_POST['report-tx'])){
            if(isset($_SESSION['username'])){
              if(trim($_POST['report-tx'])!=""){
                if(strpos($_POST['report-tx'], '-') || strpos($_POST['report-tx'], '#') || strpos($_POST['report-tx'], '/') || strpos($_POST['report-tx'], '*')){
                  alert("You can use only letters, numbers and punctuation in report message.");
                }else{
                  $fromMail = $conn->query("select * from players where username='".$_SESSION['username']."';")->fetch_assoc()['email'];
                  $toMail = $conn->query("select * from players where username='".$name."';")->fetch_assoc()['email'];

                  $insert = "insert into reports
                  (
                    fromEmail,
                    toEmail,
                    text,
                    date
                    )values(
                      '".$fromMail."',
                      '".$toMail."',
                      '".$_POST['report-tx']."',
                      '".date('Y/m/d')."'
                    );";

                    if($conn->query($insert)){
                      alert("User reported. Wait for administrator email.");
                      echo '<script>
                        $(document).ready(function(){
                          $("#inchidere-alert").click(function(){
                            window.location.replace("'.$dir.'profile/'.$name.'");
                          });
                        });
                      </script>';
                    }
                  }
                }else{
                  alert("Report message left empty. Ignoring...");
                }
            }else{
              alert("You have to be logged to report an user.");
            }
          }

        }else{
          if(!isset($_SESSION['username'])){
            echo
            '<script>
              window.location.replace("'.$dir.'home");
            </script>';
          }else{
            $isMine = true;

            if(isset($_POST['description'])){
              $update = "update players set description='".$_POST['description']."' where username='".$_SESSION['username']."';";
              if($conn->query($update)){
                if(!isset($_FILES["profile-icon"]["name"]) || $_FILES["profile-icon"]["name"] == ""){
                  echo '<script>
                    $(document).ready(function(){
                      $("#inchidere-alert").prop("onclick", null).off("click");
                      $("#inchidere-alert").click(function(){
                        window.location.replace("'.$dir.'profile.php");
                      });
                    });
                  </script>';

                  alert("Description saved");
                }
              }else{
                alert($conn->error);
              }
            }

            if(isset($_FILES["profile-icon"]["name"]) && $_FILES["profile-icon"]["name"] != ""){
              $img_size = $_FILES["profile-icon"]["size"];
              $ext = pathinfo($_FILES['profile-icon']['name'], PATHINFO_EXTENSION);

              if($img_size<=25000000){
                if(move_uploaded_file($_FILES["profile-icon"]["tmp_name"], 'Players/'.$_SESSION['username'].'/temp.'.$ext)){
                  $image_size = getimagesize("Players/".$_SESSION['username'].'/temp.'.$ext);
                  if($image_size !== false){
                    if($ext != "png"){
                      imagepng(imagecreatefromstring(file_get_contents('Players/'.$_SESSION['username'].'/temp.'.$ext)), 'Players/'.$_SESSION['username'].'/temp.png');
                      unlink('Players/'.$_SESSION['username'].'/temp.'.$ext);
                    }

                    if(explode("\"", $image_size[3])[1] == explode("\"", $image_size[3])[3]){
                      if(file_exists("Players/".$_SESSION['username']."/icon.png")){
                        unlink("Players/".$_SESSION['username']."/icon.png");
                      }

                      rename('Players/'.$_SESSION['username'].'/temp.png', 'Players/'.$_SESSION['username'].'/icon.png');

                      echo '<script>
                        $(document).ready(function(){
                          $("#inchidere-alert").prop("onclick", null).off("click");
                          $("#inchidere-alert").click(function(){
                            window.location.replace("'.$dir.'profile.php");
                          });
                        });
                      </script>';

                      alert("Data saved");
                    }else{
                      alert("Please insert a square image.");
                    }
                  }else{
                    alert("You have uploaded a wrong file. Try again.");
                  }
                }else{
                  alert("An error occured while trying to save image. Try again later.");
                }
              }else{
                alert("The file is too big. Please limit the image size to 25MB.");
              }
            }


            $select = "select * from players where username='".$_SESSION['username']."';";
            $result = $conn->query($select);

            if($result->num_rows == 1){

              $row = $result->fetch_assoc();
              $name = $row['username'];
              $level = 1;
              $points = $row['points'];
              $trophies = $row['trophies'];
              $description = $row['description'];

            }else{
              alert("DB error");
              echo '<script>
                $(document).ready(function(){
                  $("#inchidere-alert").prop("onclick", null).off("click");
                  $("#inchidere-alert").click(function(){
                    window.location.replace("'.$dir.'home");
                  });
                });
              </script>';
              die();
            }
          }
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

        $rankString = file_get_contents("resurse/rank.txt");
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

      <div class="report-pan">
        <div class="report">
          <button type="button" name="button" class="btn btn-default btn-inchidere"></button>
          <h2 align="center">Login</h2>
          <form method="post" action="<?php echo $dir; ?>profile/<?php echo $name; ?>">
            <div class="form-group row">
              <label class="col-sm-12">Your message for report here:</label><br>
              <div class="col-sm-12">
                <textarea class="form-control" rows="3" style="resize: none; max-height:20%;" maxlength="350" name="report-tx"></textarea><br>
                <button type="submit" class="btn btn-danger center-block">Report</button>
              </div>
            </div>
          </form>
        </div>

      </div>


      <br><br>
<div class="row profil">
  <div class="col-lg-8 col-lg-offset-2">
    <form class="profil-form" action="<?php echo $dir; ?>profile.php" method="post" enctype="multipart/form-data">
      <div class="row">
        <img  class="control img-responsive center-block" style="width:25% !important;" src="<?php
         if(($isMine && isset($_SESSION['username']) && file_exists("Players/".$_SESSION['username']."/icon.png"))
         || (!$isMine && file_exists("Players/".$name."/icon.png")))
         {
           echo $dir.'Players/'.$name."/icon.png?".rand(10,10000);
         }
         else{
           echo $dir.'imagini/imgdeprofil.png?'.rand(10,10000);
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
        <div class="col-xs-4 col-xs-offset-4 progress control" style="padding:0;">
          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $procent;?>"
          aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $procent; ?>%;">
            <center style="color:#fff; font-weight:900;"><?php echo $points.'/'.$pointsNextLVL."XP"; ?> </center>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <img class="control img-responsive center-block" src="imagini/scor.png" style="width:5vw" alt="">
        <p align="center"><?php echo "Points: ".$points; ?></p>
      </div>
      <div class="col-lg-6">
        <img class="img-responsive center-block"style="width:5vw" src="imagini/trofeu.png" alt="">
        <p align="center"><?php echo "Trophies: ".$trophies; ?></p>
      </div>

      <div class="row">
        <div class="col-lg-10 col-lg-offset-1 ">
          <label for="comment"><p>Description: </p></label>
          <?php
            echo '<textarea class="form-control" maxlength="350" rows="5" id="comment" style="resize: none;" name="description" '.($isMine==false?"disabled":"").'>'.$description.'</textarea>';
          ?>

        </div>
      </div>
      <br>
      <center>
        <?php

          if($isMine){
            echo '
              <button type="button" id="save-but" class="btn btn-primary" name="button">Save</button>
              <button type="button" id="preview-but" class="btn btn-success" name="button"
              onclick=\'window.location.replace("'.$dir.'profile/'.$_SESSION["username"].'");\'
              >Preview</button>
            ';
          }else{
            if($name != $_SESSION['username']){
              echo '
                <button type="button" id="report-but" class="btn btn-danger" name="button">Report</button>
              ';
            }
          }
        ?>
      </center>

    </form>

    </div>
  </div>
<br>
  <div class="row">
    <div class="col-lg-12 profiljoc">
      <?php
        afiseaza_jocuri_recente();
       ?>
    </div>
  </div>
    <br>

    <div class="row">
      <div class="col-lg-12 profiljoc">
        <?php
          afiseaza_jocuri_sugerate();
         ?>
      </div>
  </div>
<br><br>

<?php
afiseaza_drepturi();
 ?>


   </body>
 </html>
