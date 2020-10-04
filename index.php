<?php
require "resurse.php";
verifica();
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta name="robots" content="index, follow">
  <meta charset="UTF-8">
  <meta name="description" content="Free games on TA Saturn Games: Shooter, .io, Girls and many more. Come and see for yourself!">
  <meta name="keywords" content="games,shooter,android,mobile,minecraft,online,multiplayer,metin2,galaxy,sports">
  <meta name="author" content="TA Saturn Games">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="canonical" href="www.<?php echo $_SERVER[HTTP_HOST]; ?>/home" />
<?php
include_dependente(); ?>
  <title>SaturnGames - Free online games</title>

  <style media="screen">
  .container-fluid{
    background-color: #262626 !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 304 304' width='304' height='304'%3E%3Cpath fill='%23827892' fill-opacity='0.31' d='M44.1 224a5 5 0 1 1 0 2H0v-2h44.1zm160 48a5 5 0 1 1 0 2H82v-2h122.1zm57.8-46a5 5 0 1 1 0-2H304v2h-42.1zm0 16a5 5 0 1 1 0-2H304v2h-42.1zm6.2-114a5 5 0 1 1 0 2h-86.2a5 5 0 1 1 0-2h86.2zm-256-48a5 5 0 1 1 0 2H0v-2h12.1zm185.8 34a5 5 0 1 1 0-2h86.2a5 5 0 1 1 0 2h-86.2zM258 12.1a5 5 0 1 1-2 0V0h2v12.1zm-64 208a5 5 0 1 1-2 0v-54.2a5 5 0 1 1 2 0v54.2zm48-198.2V80h62v2h-64V21.9a5 5 0 1 1 2 0zm16 16V64h46v2h-48V37.9a5 5 0 1 1 2 0zm-128 96V208h16v12.1a5 5 0 1 1-2 0V210h-16v-76.1a5 5 0 1 1 2 0zm-5.9-21.9a5 5 0 1 1 0 2H114v48H85.9a5 5 0 1 1 0-2H112v-48h12.1zm-6.2 130a5 5 0 1 1 0-2H176v-74.1a5 5 0 1 1 2 0V242h-60.1zm-16-64a5 5 0 1 1 0-2H114v48h10.1a5 5 0 1 1 0 2H112v-48h-10.1zM66 284.1a5 5 0 1 1-2 0V274H50v30h-2v-32h18v12.1zM236.1 176a5 5 0 1 1 0 2H226v94h48v32h-2v-30h-48v-98h12.1zm25.8-30a5 5 0 1 1 0-2H274v44.1a5 5 0 1 1-2 0V146h-10.1zm-64 96a5 5 0 1 1 0-2H208v-80h16v-14h-42.1a5 5 0 1 1 0-2H226v18h-16v80h-12.1zm86.2-210a5 5 0 1 1 0 2H272V0h2v32h10.1zM98 101.9V146H53.9a5 5 0 1 1 0-2H96v-42.1a5 5 0 1 1 2 0zM53.9 34a5 5 0 1 1 0-2H80V0h2v34H53.9zm60.1 3.9V66H82v64H69.9a5 5 0 1 1 0-2H80V64h32V37.9a5 5 0 1 1 2 0zM101.9 82a5 5 0 1 1 0-2H128V37.9a5 5 0 1 1 2 0V82h-28.1zm16-64a5 5 0 1 1 0-2H146v44.1a5 5 0 1 1-2 0V18h-26.1zm102.2 270a5 5 0 1 1 0 2H98v14h-2v-16h124.1zM242 149.9V160h16v34h-16v62h48v48h-2v-46h-48v-66h16v-30h-16v-12.1a5 5 0 1 1 2 0zM53.9 18a5 5 0 1 1 0-2H64V2H48V0h18v18H53.9zm112 32a5 5 0 1 1 0-2H192V0h50v2h-48v48h-28.1zm-48-48a5 5 0 0 1-9.8-2h2.07a3 3 0 1 0 5.66 0H178v34h-18V21.9a5 5 0 1 1 2 0V32h14V2h-58.1zm0 96a5 5 0 1 1 0-2H137l32-32h39V21.9a5 5 0 1 1 2 0V66h-40.17l-32 32H117.9zm28.1 90.1a5 5 0 1 1-2 0v-76.51L175.59 80H224V21.9a5 5 0 1 1 2 0V82h-49.59L146 112.41v75.69zm16 32a5 5 0 1 1-2 0v-99.51L184.59 96H300.1a5 5 0 0 1 3.9-3.9v2.07a3 3 0 0 0 0 5.66v2.07a5 5 0 0 1-3.9-3.9H185.41L162 121.41v98.69zm-144-64a5 5 0 1 1-2 0v-3.51l48-48V48h32V0h2v50H66v55.41l-48 48v2.69zM50 53.9v43.51l-48 48V208h26.1a5 5 0 1 1 0 2H0v-65.41l48-48V53.9a5 5 0 1 1 2 0zm-16 16V89.41l-34 34v-2.82l32-32V69.9a5 5 0 1 1 2 0zM12.1 32a5 5 0 1 1 0 2H9.41L0 43.41V40.6L8.59 32h3.51zm265.8 18a5 5 0 1 1 0-2h18.69l7.41-7.41v2.82L297.41 50H277.9zm-16 160a5 5 0 1 1 0-2H288v-71.41l16-16v2.82l-14 14V210h-28.1zm-208 32a5 5 0 1 1 0-2H64v-22.59L40.59 194H21.9a5 5 0 1 1 0-2H41.41L66 216.59V242H53.9zm150.2 14a5 5 0 1 1 0 2H96v-56.6L56.6 162H37.9a5 5 0 1 1 0-2h19.5L98 200.6V256h106.1zm-150.2 2a5 5 0 1 1 0-2H80v-46.59L48.59 178H21.9a5 5 0 1 1 0-2H49.41L82 208.59V258H53.9zM34 39.8v1.61L9.41 66H0v-2h8.59L32 40.59V0h2v39.8zM2 300.1a5 5 0 0 1 3.9 3.9H3.83A3 3 0 0 0 0 302.17V256h18v48h-2v-46H2v42.1zM34 241v63h-2v-62H0v-2h34v1zM17 18H0v-2h16V0h2v18h-1zm273-2h14v2h-16V0h2v16zm-32 273v15h-2v-14h-14v14h-2v-16h18v1zM0 92.1A5.02 5.02 0 0 1 6 97a5 5 0 0 1-6 4.9v-2.07a3 3 0 1 0 0-5.66V92.1zM80 272h2v32h-2v-32zm37.9 32h-2.07a3 3 0 0 0-5.66 0h-2.07a5 5 0 0 1 9.8 0zM5.9 0A5.02 5.02 0 0 1 0 5.9V3.83A3 3 0 0 0 3.83 0H5.9zm294.2 0h2.07A3 3 0 0 0 304 3.83V5.9a5 5 0 0 1-3.9-5.9zm3.9 300.1v2.07a3 3 0 0 0-1.83 1.83h-2.07a5 5 0 0 1 3.9-3.9zM97 100a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-48 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 96a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-144a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-96 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm96 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-32 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM49 36a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-32 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM33 68a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 240a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm80-176a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm112 176a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM17 180a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM17 84a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'%3E%3C/path%3E%3C/svg%3E");
  }

  .recente{
    background-color: #191919 !important;
  }
  </style>

</head>

<body>
  <?php
    afiseaza_bara();
  ?>


  <?php
    afiseaza_jocuri_recente();
  ?>

  <?php

    if(isset($_SESSION['username'])){
      echo '<hr style="border-color: #10b53c; margin: 0;">
      <h4 style="color:#07c607;">You will get a level point each minute you play a game.</h4>';
    }else{
      echo '<hr style="border-color: #10b53c; margin: 0;">
      <h4 style="color:#07c607;">Create an account or login to get points playing games.</h4>';
    }

    if(isset($_GET['categ'])){
      echo '<hr style="border-color: #10b53c; margin: 0;">
      <h2 style="color:#07c607;">Here are some '.$_GET['categ'].' games. Enjoy!</h2>';
    }

   ?>

  <div class="container-fluid" style="border-top: 2px solid #0ca037;">

    <?php
      $extraSelect = '';


      if(isset($_GET['mygames'])){
        $extraSelect = $extraSelect.' inner join saved_games on games.id=saved_games.id_game and saved_games.user_ip="'.get_client_ip().'" ';
      }

      if(trim($extraSelect) == ''){
        $extraSelect = ' where';
      }else{
        $extraSelect = $extraSelect.' and';
      }

      if(isset($_GET['categ'])){
        $extraSelect = $extraSelect.' games.category="'.$_GET['categ'].'" and ';
      }

      if(isset($_GET['search'])){
        $extraSelect = $extraSelect.' games.name regexp "'.$conn->real_escape_string($_GET['search']).'" and ';
      }

      $extraSelect = $extraSelect." games.status=2";

      if(isset($_GET['popular_games'])){
        $extraSelect = $extraSelect.' order by games.tot_players desc ';
      }else{
        if(isset($_GET['mygames'])){
          $extraSelect = $extraSelect.' order by saved_games.id desc ';
        }else{
          $extraSelect = $extraSelect.' order by games.id desc ';
        }
      }

      $page = 1;
      if(isset($_GET['page']) && $_GET['page']>0){
        $page = $_GET['page'];
      }

      $maxPage=1;

      $offset = ($page-1)*30;
      $resultGamesSelect = $conn->query('select * from games '.$extraSelect.' limit 30 offset '.$offset.';');
      $countG = $resultGamesSelect->num_rows;

      $countTot = $conn->query('select * from games '.$extraSelect.';')->num_rows;
      $maxPage=(int)($countTot/30);
      if($countTot%30 > 0){
        $maxPage++;
      }

      if($countG==0){
        if(isset($_GET['mygames'])){
          echo "<h1 style='color:#07b515;'><b>You don't have saved games</b></h1>";
        }else{
          echo "<h1 style='color:#07b515;'><b>No games here.</b></h1>";
        }
      }

      for ($i=1; $i <= 6; $i++) {
        echo '<div class="row jocuri-rand">';
        for ($j=1; $j <= 5; $j++) {
          if($countG>0){
            $row = $resultGamesSelect->fetch_assoc();
            if($row['likes']+$row['dislikes']){
              $nota = round(((100*$row['likes'])/($row['likes']+$row['dislikes'])),1);
            }else{
              $nota = 50;
            }

            $nameG = $row['name'];

            if(strlen($nameG) >= 11){
              $nameG = trim(substr($nameG, 0, 10))."...";
            }

            $img = "";

            if(file_exists("jocuri/".$row['name']."/picture.jpg")){
              $img = $dir."jocuri/".$row['name']."/picture.jpg";
            }else{
              $img = trim(file_get_contents("jocuri/".$row['name']."/picture.txt"));
            }

            echo '<div class="col-lg-2 jocuri" onclick="window.location.href=\''.$dir.'game/'.$row["permalink"].'\';">
                    <div class="titlu">
                      <p>'.$nameG.'</p>
                    </div>

                    <div class="nota">
                      <p><span class="glyphicon glyphicon-thumbs-up"></span> '.$nota.'%</p>
                    </div>

                    <div class="media">
                      <div class="playB">
                        <img src="'.$dir.'imagini/play-button.png">
                      </div>
                      <div class="gameImg">
                        <img src="'.$img.'">
                      </div>
                    </div>
                  </div>';
          }
          $countG--;
        }
        echo '</div>';
      }
      echo '<br>
      <div class="pagini">
      <center>';

      $extraGet = '';

      if(isset($_GET['mygames'])){
        $extraGet = '&mygames='.$_GET['mygames'];
      }

      if(isset($_GET['categ'])){
        $extraGet = '&categ='.$_GET['categ'];
      }

      if(isset($_GET['search'])){
        $extraGet = '&search='.$_GET['search'];
      }

      if(isset($_GET['popular_games'])){
        $extraGet = '&popular_games='.$_GET['popular_games'];
      }

      if($maxPage > 1){
        if($page < 3){
          if($page==1){
            echo '<a href="'.$dir.'page/1'.$extraGet.'" style="color: white !important;">1</a>';
          }else{
            echo '<a href="'.$dir.'page/1'.$extraGet.'">1</a>';
          }

          if($page==2){
            echo '<a href="'.$dir.'page/2'.$extraGet.'" style="color: white !important;">2</a>';
          }else{
            echo '<a href="'.$dir.'page/2'.$extraGet.'">2</a>';
          }

            if($maxPage>=3){
              if($page==2 || ($page==1 && $maxPage==3)){
                echo '<a href="'.$dir.'page/3'.$extraGet.'">3</a>';
              }
              if($maxPage>3){
                echo '...';
                echo '<a href="'.$dir.'page/'.$maxPage.''.$extraGet.'">'.$maxPage.'</a>';
              }
            }
        }else{
          echo '<a href="'.$dir.'page/1'.$extraGet.'">1</a>';

          if($page > 3){
            echo '...';
          }

          if($page< $maxPage-1){
            echo '<a href="'.$dir.'page/'.($page-1).''.$extraGet.'">'.($page-1).'</a>';
            echo '<a href="'.$dir.'page/'.($page).''.$extraGet.'" style="color: white !important;">'.($page).'</a>';
            echo '<a href="'.$dir.'page/'.($page+1).''.$extraGet.'">'.($page+1).'</a>';
            if($page < $maxPage-2){
              echo '...';
            }
            echo '<a href="'.$dir.'page/'.$maxPage.''.$extraGet.'">'.$maxPage.'</a>';
          }else{

            if($page==$maxPage-1){
              echo '<a href="'.$dir.'page/'.($maxPage-2).''.$extraGet.'">'.($maxPage-2).'</a>';
              echo '<a href="'.$dir.'page/'.($maxPage-1).''.$extraGet.'" style="color: white !important;">'.($maxPage-1).'</a>';
            }else{
              echo '<a href="'.$dir.'page/'.($maxPage-1).''.$extraGet.'">'.($maxPage-1).'</a>';
            }

            if($page==$maxPage){
              echo '<a href="'.$dir.'page/'.$maxPage.''.$extraGet.'" style="color: white !important;">'.$maxPage.'</a>';
            }else{
              echo '<a href="'.$dir.'page/'.$maxPage.''.$extraGet.'">'.$maxPage.'</a>';
            }
          }
        }
      }else{
        echo '<a href="'.$dir.'page/1" style="color: white !important;">1</a>';
      }

      echo '</center>
      </div>';

    ?>

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
    </div>-->

    <br><br>
  </div>



<?php

afiseaza_drepturi();
 ?>

</body>

</html>
