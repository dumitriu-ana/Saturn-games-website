<?php
  require "resurse.php";
  verifica();
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
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

     <!-- Latest compiled and minified CSS -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

     <!-- jQuery library -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

     <!-- Latest compiled JavaScript -->
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

     <link rel="stylesheet" href="<?php echo $dir; ?>resurse/TemplateData/style.css">
     <script src="<?php echo $dir; ?>resurse/TemplateData/UnityProgress.js"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

     <?php

      $ip = get_client_ip();


      $name = "";
      $author = "";
      $descr = "";
      $controls = "";
      $likes = 0;
      $dislikes = 0;
      $fullscreen = 0;
      $game_type = 0;

      $tot_players=0;

      $link = "";


      $google_play = "";
      $app_store = "";
      $steam = "";

      $bar_percent = 50;
      $like_status = 0;

      $saved = 0;

      $resultComentariu;
      $rowGame;
      if(isset($_GET['game'])){
        $select = "select * from games where permalink='".$_GET['game']."';";

        $result = $conn->query($select);

        if($result->num_rows>0){
          $row = $result->fetch_assoc();
          $rowGame=$row;
          $name = $row['name'];
          $author = $row['author'];
          $descr = $row['description'];
          $controls = $row['controls'];
          $controls = str_replace(array('#'), '<br>', $controls);
          $likes = $row['likes'];
          $dislikes = $row['dislikes'];
          $fullscreen = $row['fullscreen'];
          $game_type = $row['game_type'];

          $tot_players = $row['tot_players'];

          $getLiked = "select * from games_likes where ip_player='".$ip."' and id_game='".$rowGame['id']."';";

          $likedResult = $conn->query($getLiked);

          if($likedResult->num_rows > 0){
            $like_status = $likedResult->fetch_assoc()['status'];
          }

          if($likes+$dislikes >= 1){
            $bar_percent = (int)((100*$likes)/($likes+$dislikes));
          }else{
            $bar_percent = 50;
          }

          if(file_exists("jocuri/".$name."/google_play.txt")){
            $google_play = file_get_contents("jocuri/".$name."/google_play.txt");
          }

          if(file_exists("jocuri/".$name."/app_store.txt")){
            $app_store = file_get_contents("jocuri/".$name."/app_store.txt");
          }

          if(file_exists("jocuri/".$name."/app_store.txt")){
            $steam = file_get_contents("jocuri/".$name."/steam.txt");
          }

          $selectCom = "select * from games_comms where id_game=".$rowGame['id']." order by date desc;";
          $resultComentariu = $conn->query($selectCom);

          if($game_type==1){
            if(file_exists("jocuri/".$name."/link")){
              $link = file_get_contents("jocuri/".$name."/link");
            }else{
              echo
              '<script>
                window.location.replace("'.$dir.'home");
              </script>';
            }
          }

          if($rowGame['status'] == 2){
            $emptyId = 0;
            $duplicat = false;

            for ($i=1; $i < 4; $i++) {
              if(isset($_SESSION['recent'.$i]) && $_SESSION['recent'.$i] == $_GET['game']){
                $emptyId = $i;
                $duplicat=true;
                break;
              }
              if(!isset($_SESSION['recent'.$i])){
                $emptyId = $i;
                break;
              }
            }

            if($duplicat){
              for ($i=$emptyId; $i > 1; $i--) {
                $_SESSION['recent'.($i)] = (isset($_SESSION['recent'.($i-1)])?$_SESSION['recent'.($i-1)]:null);
              }
              $_SESSION['recent1'] = $_GET['game'];
            }else{
              for ($i=4; $i > 1; $i--) {
                $_SESSION['recent'.($i)] = (isset($_SESSION['recent'.($i-1)])?$_SESSION['recent'.($i-1)]:null);
              }

              $_SESSION['recent1'] = $_GET['game'];
            }
          }

          $savedQ = "select * from saved_games where user_ip='".$ip."' and id_game='".$rowGame['id']."';";

          if($conn->query($savedQ)->num_rows>0){
            $saved = 1;
          }

        }else{
          echo
          '<script>
            window.location.replace("'.$dir.'404.php");
          </script>';
        }

      }else{
        echo
        '<script>
          window.location.replace("'.$dir.'404.php");
        </script>';
      }
     ?>

     <?php
      if($game_type==0){
        echo '<script src="'.$dir.'jocuri/'.$name.'/Build/UnityLoader.js"></script>
        <script>
          var gameInstance;
           function startGame(){
              gameInstance = UnityLoader.instantiate("gameContainer", "'.$dir.'jocuri/'.$name.'/Build/'.$name.'.json", {
                  onProgress: UnityProgress,
                  Module: {
                      onRuntimeInitialized: function() {
                          UnityProgress(gameInstance, "complete");
                      },
                  },
                  popup: function(message, callbacks) {
                      if (!callbacks || !callbacks.length) {
                          return;
                      } else if (callbacks.length > 1) {
                          return UnityLoader.Error.popup(this, message, callbacks);
                      } else if (callbacks[0].callback) {
                          callbacks[0].callback();
                      }
                  },
              });
            }
        </script>

        <script type=\'text/javascript\'>
           document.addEventListener(\'click\', function(e) {
               if (e.target.id == "#canvas") {
                   FocusCanvas("1");
               } else {
                   FocusCanvas("0");
                   e.target.focus();
               }
           });

           $(document).ready(function(){
             $(".webgl-parinte")[0].scrollIntoView();
           });

         </script>';
      }
     ?>
      <script type="text/javascript">
        var like_status = <?php echo $like_status; ?>;
        var likes = <?php echo $likes; ?>;
        var dislikes = <?php echo $dislikes; ?>;
        var gameType = <?php echo $game_type; ?>

        var noComm = <?php if($resultComentariu->num_rows==0){echo "true";}else{echo "false";} ?>;

        var contains = false;

        var saved = <?php echo $saved; ?>;

        var save_timeout = 0;

        function resetSTimeout(){
          save_timeout=0;
        }

        $(document).ready(function(){

          if(like_status!=0){
            $('.like-btn').hide();
          }

          <?php
            if($enabledCookies==1){
              echo 'setTimeout(function(){
                $(".aprecieri").show(1000);
              }, 1500);';
            }
          ?>

                    $('#forma-comentariu').on('keyup keypress', function(e) {
            return e.which !== 13;
          });

          if(gameType != 0){
            var xhttp = new XMLHttpRequest();
            xhttp.open('POST', '<?php echo $dir; ?>ajax_requests/update_game_plays.php', true);
            xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhttp.send('id=<?php echo $rowGame["id"]; ?>&plays=<?php echo $tot_players; ?>');

            if(isLogged){
              setInterval(pointInterval, 60000);
            }

            $(document).ready(function(){
              $(".iframe-parinte")[0].scrollIntoView();
            });

            $(document).ready(function(){

              $('#iframe-fullscreen').click(function(){
                var elem = $("#iframe-act")[0];
                if (elem.requestFullscreen) {
                  elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) { /* Firefox */
                  elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
                  elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { /* IE/Edge */
                  elem.msRequestFullscreen();
                }
              });

            });
          }

        });

      </script>
     <?php
       include_dependente();
      ?>

      <title><?php echo $name." - Play on TASaturnGames"; ?></title>

      <style media="screen">
      body{
        background-color: #262626 !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 304 304' width='304' height='304'%3E%3Cpath fill='%23827892' fill-opacity='0.31' d='M44.1 224a5 5 0 1 1 0 2H0v-2h44.1zm160 48a5 5 0 1 1 0 2H82v-2h122.1zm57.8-46a5 5 0 1 1 0-2H304v2h-42.1zm0 16a5 5 0 1 1 0-2H304v2h-42.1zm6.2-114a5 5 0 1 1 0 2h-86.2a5 5 0 1 1 0-2h86.2zm-256-48a5 5 0 1 1 0 2H0v-2h12.1zm185.8 34a5 5 0 1 1 0-2h86.2a5 5 0 1 1 0 2h-86.2zM258 12.1a5 5 0 1 1-2 0V0h2v12.1zm-64 208a5 5 0 1 1-2 0v-54.2a5 5 0 1 1 2 0v54.2zm48-198.2V80h62v2h-64V21.9a5 5 0 1 1 2 0zm16 16V64h46v2h-48V37.9a5 5 0 1 1 2 0zm-128 96V208h16v12.1a5 5 0 1 1-2 0V210h-16v-76.1a5 5 0 1 1 2 0zm-5.9-21.9a5 5 0 1 1 0 2H114v48H85.9a5 5 0 1 1 0-2H112v-48h12.1zm-6.2 130a5 5 0 1 1 0-2H176v-74.1a5 5 0 1 1 2 0V242h-60.1zm-16-64a5 5 0 1 1 0-2H114v48h10.1a5 5 0 1 1 0 2H112v-48h-10.1zM66 284.1a5 5 0 1 1-2 0V274H50v30h-2v-32h18v12.1zM236.1 176a5 5 0 1 1 0 2H226v94h48v32h-2v-30h-48v-98h12.1zm25.8-30a5 5 0 1 1 0-2H274v44.1a5 5 0 1 1-2 0V146h-10.1zm-64 96a5 5 0 1 1 0-2H208v-80h16v-14h-42.1a5 5 0 1 1 0-2H226v18h-16v80h-12.1zm86.2-210a5 5 0 1 1 0 2H272V0h2v32h10.1zM98 101.9V146H53.9a5 5 0 1 1 0-2H96v-42.1a5 5 0 1 1 2 0zM53.9 34a5 5 0 1 1 0-2H80V0h2v34H53.9zm60.1 3.9V66H82v64H69.9a5 5 0 1 1 0-2H80V64h32V37.9a5 5 0 1 1 2 0zM101.9 82a5 5 0 1 1 0-2H128V37.9a5 5 0 1 1 2 0V82h-28.1zm16-64a5 5 0 1 1 0-2H146v44.1a5 5 0 1 1-2 0V18h-26.1zm102.2 270a5 5 0 1 1 0 2H98v14h-2v-16h124.1zM242 149.9V160h16v34h-16v62h48v48h-2v-46h-48v-66h16v-30h-16v-12.1a5 5 0 1 1 2 0zM53.9 18a5 5 0 1 1 0-2H64V2H48V0h18v18H53.9zm112 32a5 5 0 1 1 0-2H192V0h50v2h-48v48h-28.1zm-48-48a5 5 0 0 1-9.8-2h2.07a3 3 0 1 0 5.66 0H178v34h-18V21.9a5 5 0 1 1 2 0V32h14V2h-58.1zm0 96a5 5 0 1 1 0-2H137l32-32h39V21.9a5 5 0 1 1 2 0V66h-40.17l-32 32H117.9zm28.1 90.1a5 5 0 1 1-2 0v-76.51L175.59 80H224V21.9a5 5 0 1 1 2 0V82h-49.59L146 112.41v75.69zm16 32a5 5 0 1 1-2 0v-99.51L184.59 96H300.1a5 5 0 0 1 3.9-3.9v2.07a3 3 0 0 0 0 5.66v2.07a5 5 0 0 1-3.9-3.9H185.41L162 121.41v98.69zm-144-64a5 5 0 1 1-2 0v-3.51l48-48V48h32V0h2v50H66v55.41l-48 48v2.69zM50 53.9v43.51l-48 48V208h26.1a5 5 0 1 1 0 2H0v-65.41l48-48V53.9a5 5 0 1 1 2 0zm-16 16V89.41l-34 34v-2.82l32-32V69.9a5 5 0 1 1 2 0zM12.1 32a5 5 0 1 1 0 2H9.41L0 43.41V40.6L8.59 32h3.51zm265.8 18a5 5 0 1 1 0-2h18.69l7.41-7.41v2.82L297.41 50H277.9zm-16 160a5 5 0 1 1 0-2H288v-71.41l16-16v2.82l-14 14V210h-28.1zm-208 32a5 5 0 1 1 0-2H64v-22.59L40.59 194H21.9a5 5 0 1 1 0-2H41.41L66 216.59V242H53.9zm150.2 14a5 5 0 1 1 0 2H96v-56.6L56.6 162H37.9a5 5 0 1 1 0-2h19.5L98 200.6V256h106.1zm-150.2 2a5 5 0 1 1 0-2H80v-46.59L48.59 178H21.9a5 5 0 1 1 0-2H49.41L82 208.59V258H53.9zM34 39.8v1.61L9.41 66H0v-2h8.59L32 40.59V0h2v39.8zM2 300.1a5 5 0 0 1 3.9 3.9H3.83A3 3 0 0 0 0 302.17V256h18v48h-2v-46H2v42.1zM34 241v63h-2v-62H0v-2h34v1zM17 18H0v-2h16V0h2v18h-1zm273-2h14v2h-16V0h2v16zm-32 273v15h-2v-14h-14v14h-2v-16h18v1zM0 92.1A5.02 5.02 0 0 1 6 97a5 5 0 0 1-6 4.9v-2.07a3 3 0 1 0 0-5.66V92.1zM80 272h2v32h-2v-32zm37.9 32h-2.07a3 3 0 0 0-5.66 0h-2.07a5 5 0 0 1 9.8 0zM5.9 0A5.02 5.02 0 0 1 0 5.9V3.83A3 3 0 0 0 3.83 0H5.9zm294.2 0h2.07A3 3 0 0 0 304 3.83V5.9a5 5 0 0 1-3.9-5.9zm3.9 300.1v2.07a3 3 0 0 0-1.83 1.83h-2.07a5 5 0 0 1 3.9-3.9zM97 100a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-48 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 96a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-144a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-96 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm96 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-32 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM49 36a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-32 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM33 68a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 240a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm80-176a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm112 176a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM17 180a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM17 84a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'%3E%3C/path%3E%3C/svg%3E");
      }

      .drepturi-autor{
        background-color: #0c0c0c;
      }

      .iframe-parinte{
        width: 1000px !important;
        height: 565px !important;
      }

      iframe{
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
      }

      .aprecieri h3:hover{
        text-shadow: 0 0 10px white !important;
      }

      </style>

      <script>
        $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip();
        });
      </script>
  </head>
  <body>

    <?php
    afiseaza_bara();
    ?>

    <?php
    afiseaza_jocuri_sugerate();
    ?>


    <hr style="border-color: #0ca037; margin:0; border-width:2px;">

    <hr style="border-top: 2px solid #0ca037;">

    <br><br>

    <center><h3 style="color:#ffffff;text-shadow: 0 0 15px #0fc121;">During the gameplay, ads may appear.
      You can skip them or wait until video finishes.<br>
      <b>Thank you!</b>
    </h3></center>

    <br><br>

    <!-- PENTRU UNITY => JOS -->
    <script type="text/javascript">
      var isLogged = <?php if(isset($_SESSION["username"])){echo "true"; }else{ echo "false"; } ?>;

      <?php
        if($game_type == 0){
          echo 'var gameReady = false;
          function GameControlReady () {
            gameReady = true;
          }

          function FocusCanvas(focus) {
            if (gameReady) {
                gameInstance.SendMessage("GameControl", "FocusCanvas", focus);
            }
          }';
        }
      ?>

      function pointInterval(){
        if(isLogged){
          var xhttp = new XMLHttpRequest();
          xhttp.open('POST', '<?php echo $dir; ?>ajax_requests/add_point.php', true);
          xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
          xhttp.send('player=<?php if(isset($_SESSION['username'])){ echo $_SESSION['username'];}else{echo 'player';} ?>');

          $('.notif-stanga').show(500);
          setTimeout(function(){
            $('.notif-stanga').hide(500);
           }, 3000);
        }
      }

      function addTrophy(){
        if(isLogged){
          var xhttp = new XMLHttpRequest();
          xhttp.open('POST', '<?php echo $dir; ?>ajax_requests/add_trophy.php', true);
          xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
          xhttp.send('player=<?php if(isset($_SESSION['username'])){ echo $_SESSION['username'];}else{echo 'player';} ?>');

          $('.notif-dreapta').show(500);
          setTimeout(function(){
            $('.notif-dreapta').hide(500);
           }, 3000);
        }
      }
    </script>

    <?php
      if($game_type==0){
        $casete = "";

        if($fullscreen==1){
          $casete = $casete.'<div class="fullscreen" onclick="gameInstance.SetFullscreen(1)"></div>';
        }

        if(trim($google_play)!=''){
          $casete = $casete.'<div class="google-play" onclick="window.open(\''.trim($google_play).'\', \'_blank\');"></div>';
        }

        if(trim($app_store)!=''){
          $casete = $casete.'<div class="ios" onclick="window.open(\''.trim($app_store).'\', \'_blank\');"></div>';
        }

        if(trim($steam)!=''){
          $casete = $casete.'<div class="steam" onclick="window.open(\''.trim($steam).'\', \'_blank\');"></div>';
        }

        echo '<div class="container-fluid" style="height: 600px;">
          <div class="webgl-parinte center-block" style="width:960px; height: 100%;">
            <div class="webgl-content" style="width: 100%; display:none;">
                <div id="gameContainer" style="width: 100%; height: 100%;"></div>
                <div class="footer">
                    <div class="webgl-logo" onclick="
                      window.open(\'/home\', \'_blank\');
                    "></div>'.$casete.'</div>

                <div id="overlay" class="overlay"></div>
                <div class="counter">
                    <p id="loadingInfo">loading</p>
                    <h1 id="progressC">0%</h1>
                    <div id="ProgressLineB" class="ProgressLineB"> </div>
                    <div id="ProgressLine" class="ProgressLine"> </div>
                </div>
              </div>
              <div class="start-btn-pan" style="width:100%; height: 100%;">
                <button type="button" id="start-game-but" name="button" onclick="
                  $(\'.webgl-content\').css(\'display\',\'block\');
                  startGame();
                  $(\'#start-game-but\').toggle(300);
                  $(\'.start-btn-pan\').toggle(300);

                  if(isLogged){
                    setInterval(pointInterval, 60000);
                  }

                  var xhttp = new XMLHttpRequest();
                  xhttp.open(\'POST\', \''.$dir.'ajax_requests/update_game_plays.php\', true);
                  xhttp.setRequestHeader(\'Content-type\', \'application/x-www-form-urlencoded\');
                  xhttp.send(\'id='.$rowGame["id"].'&plays='.$tot_players.'\');

                "></button>
                <img src="'.$dir.'resurse/TemplateData/logo.png" height="50px" onclick="
                  window.open(\'/home\', \'_blank\');
                ">
              </div>
            </div>
          </div>';
      }
    ?>
      <!-- PENTRU UNITY => SUS -->



      <!-- PENTRU IFRAME => JOS -->
      <?php
        if($game_type==1){
          $casete = "";

          if($fullscreen==1){
            $casete = $casete.'<div class="fullscreen" id="iframe-fullscreen"></div>';
          }

          if(trim($google_play)!=''){
            $casete = $casete.'<div class="google-play" onclick="window.open(\''.trim($google_play).'\', \'_blank\');"></div>';
          }

          if(trim($app_store)!=''){
            $casete = $casete.'<div class="ios" onclick="window.open(\''.trim($app_store).'\', \'_blank\');"></div>';
          }

          if(trim($steam)!=''){
            $casete = $casete.'<div class="steam" onclick="window.open(\''.trim($steam).'\', \'_blank\');"></div>';
          }
          echo '<div class="container-fluid" style="height: 664px;width:1000px !important; padding:0 !important; overflow: hidden;overflow-x: hidden;overflow-y: hidden;border: none;">
            <div class="col-xs-12 iframe-parinte">
              <iframe id="iframe-act" src="'.$link.'"  marginwidth="0" marginheight="0" hspace="0" vspace="0"  frameborder="0" scrolling="no"  webkitallowfullscreen="true" mozallowfullscreen="true" msallowfullscreen="true" allowfullscreen="true"></iframe>
            </div>
            <div class="col-xs-12 iframe-footer" width="100%">
              <img src="'.$dir.'resurse/TemplateData/logo.png" height="100%" onclick="
                window.open(\'/home\', \'_blank\');
              ">
              '.$casete.'
            </div>
          </div>';
        }
      ?>

      <!-- PENTRU IFRAME => SUS -->

      <div class="notif-stanga">
        <img src="<?php echo $dir; ?>imagini/star.png"><h2>New point gained.</h2>
      </div>

      <div class="notif-dreapta">
        <img src="<?php echo $dir; ?>imagini/trophy.png"><h2>New trophy gained.</h2>
      </div>

      <div class="container-fluid joc">
        <div class="col-lg-8 col-lg-offset-2">
          <div class="date-joc">
            <div class="row" style="margin-bottom:20px;">
              <div class="col-lg-7 nume">
                <h1><?php echo $name; ?><h1>
                <h2><?php echo $author; ?></h2><br>
                <p style="font-size:1vw;"><?php echo $tot_players; ?> plays</p><br>
              </div>

              <?php
                if($enabledCookies==0){
                  echo '<div class="col-lg-4 aprecieri">
                    <h3 style="text-align: center; color:#19b217;"><b>Please enable Cookies to be able to like/dislike/save game.</b></h3>
                  </div>
                  ';
                }
              ?>

              <div class="col-lg-4 aprecieri" style="display: none;">
                  <div class="row">
                    <button data-toggle="tooltip" title="WARNING! Saved games will be saved locally. Inactivity in the website for more than 30 days will delete saved games." id="save-but" onclick="
                    if(save_timeout==0){
                      save_timeout=1;
                      var xhttp = new XMLHttpRequest();

                      if(saved==1){
                        $('#save-but').removeClass('btn-danger');
                        $('#save-but').addClass('btn-warning');

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

                        xhttp.open('POST', '<?php echo $dir; ?>ajax_requests/delete_saved_game.php', true);
                        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhttp.send('ip=<?php echo $ip; ?>&id=<?php echo $rowGame['id']; ?>');

                      }else{
                        $('#save-but').addClass('btn-danger');
                        $('#save-but').removeClass('btn-warning');

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

                        xhttp.open('POST', '<?php echo $dir; ?>ajax_requests/save_game.php', true);
                        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhttp.send('ip=<?php echo $ip; ?>&id=<?php echo $rowGame['id']; ?>');
                      }
                    }
                    " type="button" name="button" class="btn <?php if($saved==1){
                      echo 'btn-danger';
                    }else{
                      echo 'btn-warning';
                    } ?>" style="width:100% !important;"><?php if($saved==1){
                      echo '<span class="glyphicon glyphicon-remove"></span>Delete from saved</button>';
                    }else{
                      echo '<span class="glyphicon glyphicon-bookmark"></span>Save game';
                    } ?></button>
                  </div>
                  <div class="center-block">
                      <h3 style="text-align: center; color:#19b217;"><b>Like status:</b></h3>
                  </div>
                  <div class="row like-btn">
                    <div class="col-sm-6" style="padding:0;">
                      <button class="btn btn-success navbar-btn navbar-left" onclick="

                        if(like_status != 1){
                          var xhttp = new XMLHttpRequest();

                          xhttp.onreadystatechange = function() {
                                  if (this.readyState == 4 && this.status == 200) {
                                      if(this.responseText.trim() == 'ok'){
                                        if(like_status==2){
                                          dislikes--;
                                        }
                                        like_status=1;
                                        likes++;

                                        var var_percent = 50;
                                        if(likes+dislikes >= 1){
                                          bar_percent = parseInt((100*likes)/(likes+dislikes));
                                        }else{
                                          bar_percent = 50;
                                        }

                                        $('#like-bar').attr('style', 'width: '+bar_percent+'% !important;');
                                        $('#dislike-bar').attr('style', 'width: '+(100-bar_percent)+'% !important;');

                                        $('#likesTX').text(likes);
                                        $('#dislikesTX').text(dislikes);

                                        $('#p-dislike-tx').text(100-bar_percent+'%');
                                        $('#p-like-tx').text(bar_percent+'%');
                                      }
                                 }
                              };

                          xhttp.open('POST', '<?php echo $dir; ?>ajax_requests/update_game_likes.php', true);
                          xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                          xhttp.send('ip=<?php echo $ip; ?>&status=1&game=<?php echo $rowGame['id']; ?>');

                          $('.like-btn').hide(250);

                        }
                      "><b id="likesTX"><?php echo $likes; ?></b> <span class="glyphicon glyphicon-thumbs-up"></span></button>
                    </div>
                    <div class="col-sm-6">
                      <button class="btn btn-danger navbar-btn navbar-right" onclick="
                        if(like_status!=2){
                          var xhttp = new XMLHttpRequest();

                          xhttp.onreadystatechange = function() {
                              if (this.readyState == 4 && this.status == 200) {
                                  if(this.responseText.trim() == 'ok'){
                                    if(like_status==1){
                                      likes--;
                                    }
                                    like_status = 2;
                                    dislikes++;
                                    var var_percent = 50;
                                    if(likes+dislikes >= 1){
                                      bar_percent = parseInt((100*likes)/(likes+dislikes));
                                    }else{
                                      bar_percent = 50;
                                    }

                                    $('#like-bar').attr('style', 'width: '+bar_percent+'% !important;');
                                    $('#dislike-bar').attr('style', 'width: '+(100-bar_percent)+'% !important;');


                                    $('#dislikesTX').text(dislikes);
                                    $('#likesTX').text(likes);

                                    $('#p-dislike-tx').text(100-bar_percent+'%');
                                    $('#p-like-tx').text(bar_percent+'%');
                                  }
                             }
                          };

                          xhttp.open('POST', '<?php echo $dir; ?>ajax_requests/update_game_likes.php', true);
                          xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                          xhttp.send('ip=<?php echo $ip; ?>&status=2&game=<?php echo $rowGame['id']; ?>');

                          $('.like-btn').hide(250);

                        }
                      "><b id="dislikesTX"><?php echo $dislikes; ?></b> <span class="glyphicon glyphicon-thumbs-down"></span></button>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="progress col-sm-12" style="padding:0;">
                        <div class="progress-bar progress-bar-success" id="like-bar" style="width:<?php echo $bar_percent; ?>%">
                          <p id="p-like-tx"><?php echo $bar_percent; ?>%</p>
                        </div>
                        <div class="progress-bar progress-bar-danger" id="dislike-bar" style="width:<?php echo (100-$bar_percent); ?>%">
                          <p id="p-dislike-tx"><?php echo 100-$bar_percent; ?>%</p>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
          <?php
            if(trim($controls)!=""){
              echo '<div class="col-lg-12 control">
                <img src="'.$dir.'imagini/controale.png">
                <br>
                <p>'.$controls.'</p>
              </div>
';
            }
          ?>

          <div class="descriere col-lg-12 control" style="margin-top: 3vh;">
            <img src="<?php echo $dir; ?>imagini/descriere.png">
            <p><?php echo $descr; ?></p>
          </div>


              <div class="comentarii col-lg-12 control" style="margin-top: 3vh;">
                <img src="<?php echo $dir; ?>imagini/comment.png">
                <p>Comentarii</p>
                  <div class="row">
                    <div class="col-lg-2">
                      <img class="navbar-right imgcom" src="<?php echo $dir; ?>imagini/profil.png" alt="">
                    </div>
                    <form class="col-lg-8" id="forma-comentariu">
                      <div class="form-group col-lg-10">
                        <?php
                          if(isset($_SESSION['username'])){
                            echo '<input type="text" class="form-control control comentariu" maxlength="25" id="comm">';
                          }else{
                            echo '<input type="text" class="form-control control comentariu" value="You have to be logged in to write a comment." style="font-size:1vw;" disabled>';
                          }
                        ?>

                      </div>
                      <div class="col-lg-2 postare-comm">
                        <button type="button" class="btn btn-default" onclick="
                          if(isLogged){
                            var comInput = $('#comm');
                            if(comInput.val().trim() != ''){
                              if(!comInput.val().includes('#') && !comInput.val().includes('-') && !comInput.val().includes('*') && !comInput.val().includes('/')){
                                var xhttp = new XMLHttpRequest();
                                xhttp.open('POST', '<?php echo $dir; ?>ajax_requests/add_comm.php', true);
                                xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                xhttp.send('player='+'<?php
                                if(isset($_SESSION['username'])){
                                  echo $_SESSION['username'];
                                }else{
                                  echo 'player';
                                } ?>'+'&comm='+$('#comm').val() + '&game=' + '<?php echo $rowGame['id']; ?>');

                                var comm = $('#comm');

                                var d = new Date();
                                d.setDate(d.getDate() + 20);
                                var date = '<?php $d = getdate()["year"]."/";
                                  if(getdate()["mon"]<10){
                                    $d = $d.'0'.getdate()["mon"];
                                  }else{
                                    $d = $d.getdate()["mon"];
                                  }
                                  $d = $d.'/';
                                  if(getdate()["mday"]<10){
                                    $d = $d.'0'.getdate()["mday"];
                                  }else{
                                    $d = $d.getdate()["mday"];
                                  }
                                  echo $d;
                                ?>';

                                if(noComm==true){
                                  $('.comentariu-box').empty();
                                  noComm=false;
                                }

                                $('.comentariu-box').prepend('<div class=\'row com\'><div class=\'col-lg-2 col-lg-offset-1\'><p><a target=\'_blank\' href=\'<?php echo $dir; ?>profile/'+'<?php if(isset($_SESSION['username'])){ echo $_SESSION['username'];}else{echo 'player';} ?>'+'\'><?php if(isset($_SESSION['username'])){ echo $_SESSION['username'];}else{echo 'player';} ?></a></p></div><div class=\'col-lg-7\'><p>'+comm.val()+'</p></div><div class=\'col-lg-2\'>'+date+'</div></div>');
                                comm.val('');
                              }else{
                                alert_b('You can use only alphabetic, numeric and punctuation characters.');
                                $('#comm').val('');
                              }
                            }
                          }
                        " style="vertical-align:top;">Post</button>
                      </div>
                    </form>
                  </div>
                  <br>

                  <div class="col-lg-12 comentariu-box">
                    <?php
                      if($resultComentariu->num_rows==0){
                        echo "<div class='row com'>
                        <div class='col-lg-2 col-lg-offset-1'>
                        </div>
                        <div class='col-lg-7'>
                        <p>No comments to this game.</p></div>
                        <div class='col-lg-2'>
                        </div>
                        </div>";
                      }else{
                        while($row = $resultComentariu->fetch_assoc()){
                          $username = $row['player'];
                          $dt = new DateTime($row['date']);
                          $dt = $dt->format('Y/m/d');

                          echo "<div class='row com'>
                          <div class='col-lg-2 col-lg-offset-1'><p><a target='_blank' href='".$dir."profile/".$username."'>".$username.
                          "</a></p></div>
                          <div class='col-lg-7'>
                          <p>".$row['comment']."</p></div>
                          <div class='col-lg-2'>".$dt.
                          "</div>
                          </div>";
                        }
                      }
                     ?>
                  </div>
                </div>


              </div>
            </div>
          </div>

          <br>


        <?php
          afiseaza_drepturi();
         ?>

  </body>
</html>
