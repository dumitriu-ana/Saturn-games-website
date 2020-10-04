<?php
  require "resurse.php";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php
      include_dependente_mobile();
    ?>
    <meta name="robots" content="index, follow">
    <meta charset="UTF-8">
    <meta name="description" content="Free games on TA Saturn Games: Shooter, .io, Girls and many more. Come and see for yourself!">
    <meta name="keywords" content="games,shooter,android,mobile,minecraft,online,multiplayer,metin2,galaxy,sports">
    <meta name="author" content="TA Saturn Games">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({
              google_ad_client: "ca-pub-8587623557598476",
              enable_page_level_ads: true
         });
    </script>

    <?php

      $ip = get_client_ip();

      $rowGame;
      $name = '';
      $description = '';
      $link = '';
      $playedC = 0;

      $saved = 0;

      if(isset($_GET['game'])){
        $permalink = $_GET['game'];

        $selectGame = "select * from games where permalink='".$permalink."';";

        $resultGame = $conn->query($selectGame);

        if($resultGame->num_rows>0){
          $row = $resultGame->fetch_assoc();
          $rowGame=$row;

          $name = $row['name'];
          $description = $row['description'];
          $playedC = $row['tot_players'];
          if(file_exists("../jocuri/".$name."/link")){
            $link = trim(file_get_contents("../jocuri/".$name."/link"));
          }else{
            echo
            '<script>
              window.location.replace("'.$dir.'home");
            </script>';
          }

          $savedQ = "select * from saved_games where user_ip='".$ip."' and id_game='".$rowGame['id']."';";

          if($conn->query($savedQ)->num_rows>0){
            $saved = 1;
          }

        }else{
          echo
          '<script>
            window.location.replace("'.$dir.'home");
          </script>';
        }
      }else{
        echo
        '<script>
          window.location.replace("'.$dir.'home");
        </script>';
      }
    ?>


    <title><?php echo $name." - Play on  TASaturnGames"; ?></title>

    <script type="text/javascript">
      var opened = false;
      var started = false;

      var saved = <?php echo $saved; ?>;

      var save_timeout = 0;

      function resetSTimeout(){
        save_timeout=0;
      }

      $(document).ready(function(){

        $('#open-game').click(function(){

          if(!started){
            var xhttp = new XMLHttpRequest();
            xhttp.open('POST', '<?php echo $dir; ?>../ajax_requests/update_game_plays.php', true);
            xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhttp.send('id=<?php echo $rowGame["id"]; ?>&plays=<?php echo $playedC;?>');
          }

          started = true;
          var elem = $("#game")[0];

          $("#game").show(0);

          if (elem.requestFullscreen) {
            elem.requestFullscreen();
          } else if (elem.mozRequestFullScreen) { /* Firefox */
            elem.mozRequestFullScreen();
          } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
            elem.webkitRequestFullscreen();
          } else if (elem.msRequestFullscreen) { /* IE/Edge */
            elem.msRequestFullscreen();
          }
          if(isLogged){
            setInterval(pointInterval, 60000);
          }
        });

        if (document.addEventListener)
        {
            document.addEventListener('webkitfullscreenchange', exitHandler, false);
            document.addEventListener('mozfullscreenchange', exitHandler, false);
            document.addEventListener('fullscreenchange', exitHandler, false);
            document.addEventListener('MSFullscreenChange', exitHandler, false);
        }

      });

      function exitHandler()
      {
          if (document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement !== null)
          {
            if(opened){
              $("#game").hide(0);
              opened = false;
            }else{
              opened = true;
            }
          }
      }
    </script>

    <script type="text/javascript">
      var isLogged = <?php if(isset($_SESSION["username"])){echo "true"; }else{ echo "false"; } ?>;
      var started = false;
      function pointInterval(){
        started = true;
        if(isLogged){
          var xhttp = new XMLHttpRequest();
          xhttp.open('POST', '<?php echo $dir; ?>../ajax_requests/add_point.php', true);
          xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
          xhttp.send('player=<?php if(isset($_SESSION['username'])){ echo $_SESSION['username'];}else{echo 'player';} ?>');

          $('.notif-stanga').show(500);
          setTimeout(function(){
            $('.notif-stanga').hide(500);
           }, 3000);
        }
      }
    </script>

  </head>
  <body id="real-body">
    <?php
      arata_bara_mobile();
    ?>

    <div class="row descriere">
      <div class="col-sm-12">
        <button class="center-block btn btn-primary" id="open-game" type="button" name="button" style="height: 50vh !important; width: 80% !important; font-size: 30px!important;">Click here to play</button>
      </div>

      <div class="col-sm-6 col-sm-offset-3" style="margin-top:5vh;">
        <center><b><h1 style="color: #03b71b;text-shadow: 0 0 10px red; font-weight:900 !important;"><?php echo $name; ?></h1></b></center>

        <p style="font-size: 25px !important;"><?php echo $description; ?></p>
        <br>

        <?php

          if($enabledCookies==0){
            echo '<div class="row">
              <center>
                <h2>Enable cookies to be able to save games in collection.</h2>
              </center>
            </div>';
          }

        ?>

        <div class="row"
        <?php
        if($enabledCookies==0){
          echo 'style="display:none;"';
        }
        ?>
        >
          <center><button id="save-but" onclick="
          if(save_timeout==0){
            save_timeout=1;
            var xhttp = new XMLHttpRequest();

            if(saved==1){
              $('#save-but').html('<span class=\'glyphicon glyphicon-bookmark\'></span>Save game</button>');

              saved = 0;

              xhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                      if(this.responseText.trim() == 'OK'){
                        resetSTimeout();
                      }else{
                        alert_b(this.responseText);
                      }
                 }
              };

              xhttp.open('POST', '<?php echo $dir; ?>../ajax_requests/delete_saved_game.php', true);
              xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xhttp.send('ip=<?php echo $ip; ?>&id=<?php echo $rowGame['id']; ?>');

            }else{
              $('#save-but').html('<span class=\'glyphicon glyphicon-remove\'></span>Delete from saved</button>');

              saved = 1;

              xhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                      if(this.responseText.trim() == 'OK'){
                        resetSTimeout();
                      }else{
                        alert_b(this.responseText);
                      }
                 }
              };

              xhttp.open('POST', '<?php echo $dir; ?>../ajax_requests/save_game.php', true);
              xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xhttp.send('ip=<?php echo $ip; ?>&id=<?php echo $rowGame['id']; ?>');
            }
          }
          " type="button" name="button" class="btn" style="font-size:25px !important;"><?php if($saved==1){
            echo '<span class="glyphicon glyphicon-remove"></span>Delete from saved</button>';
          }else{
            echo '<span class="glyphicon glyphicon-bookmark"></span>Save game';
          } ?></button></center>
        </div>


        <p style="font-size: 25px !important;" align="center"><?php echo $playedC; ?> players</p>
      </div>
    </div>

    <div id="game" style="display: none;">
      <iframe id="mobile-embed-container" class="mobile-game fullscreen" title="fullscreen-embed" width="100%" height="100%" frameborder="0" data-ratio-tolerant="false" allowfullscreen="true" data-supports-resizing="" scrolling="no" src="<?php echo $link; ?>">
      </iframe>

      <div class="notif-stanga">
        <img src="<?php echo $dir; ?>../imagini/star.png"><h2>New point gained.</h2>
      </div>
    </div>

    <br><br>

    <hr style="border-top: 2px solid #0ca037;">

    <!--<div class="container" style="
    background-color: rgba(86, 86, 86, .7) !important;
    width: 100% !important;
    margin: 0;
    margin-top:30px;
    padding: 15px;
    ">
      <center>
        <div>
          <SCRIPT data-cfasync="false" SRC="//bdv.bidvertiser.com/BidVertiser.dbm?pid=833989&bid=1981471" TYPE="text/javascript"></SCRIPT>
        </div>
      </center>
    </div>
    <br><br>-->

    <div class="container-fluid">
      <center><h1 style="color: #03b71b;">Other games:</h1></center>
      <br>
      <?php
        $selectGames = 'select * from games where isMobile=1 and status=2 order by rand() limit 4;';

        $resultGamesSelect = $conn->query($selectGames);

        $countG = $resultGamesSelect->num_rows;

        echo "<center>";
        for ($i=0; $i < 4; $i++) {
          if($countG>0){
            $row = $resultGamesSelect->fetch_assoc();
            $nameG = $row['name'];

            if(strlen($nameG) >= 11){
              $nameG = trim(substr($nameG, 0, 10))."...";
            }

            $img = "";

            if(file_exists("../jocuri/".$row['name']."/picture.jpg")){
              $img = $dir."../jocuri/".$row['name']."/picture.jpg";
            }else{
              $img = trim(file_get_contents("../jocuri/".$row['name']."/picture.txt"));
            }

            echo '<div class="joc" onclick="window.location.href=\''.$dir.'game/'.$row["permalink"].'\';">
              <div class="img-cont">
                <img src="'.$img.'" class="img-responsive" width="100%">
              </div>
              <div class="date-joc">
                <div class="joc-titlu">
                  '.$nameG.'
                </div>
                <div class="joc-categorie">
                  '.$row["category"].'
                </div>
              </div>
            </div>';
          }else{
            echo '<div class="joc">
              <div class="col-xs-12">
                <center><h1 style="color:#21c621; font-size:5vw;">...</h1></center>
              </div>
            </div>';
          }

          $countG--;

        }
        echo "</center>";


        ?>

    </div>

    <?php
      arata_drepturi_mobile();
    ?>
  </body>
</html>
