<?php

  require "resurse.php";

?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

  <head>

    <meta charset="utf-8">
    <title>SaturnGames - Free mobile games online</title>

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

      if(isset($_SESSION['username'])){
        echo '<h3 align="center" style="color: #31bf15;"><b>Welcome, '.$_SESSION["username"].'!</b></h3>';
        echo '<h3 align="center" style="color: #31bf15;"><b>You will receive a point each minute you play a game!</b></h3>';
      }else{
        echo '<h3 align="center" style="color: #31bf15;"><b>Create an account or login to get points playing games.</b></h3>';
      }
    ?>

    <br>

    <div class="container-fluid">
      <?php
        $page = 1;
        if(isset($_GET['page']) && $_GET['page']>0){
          $page = $_GET['page'];
        }

        $maxPage=1;
        $offset = ($page-1)*10;
        $extraSelect = "";

        if(isset($_GET['mygames'])){
          $extraSelect = $extraSelect.' inner join saved_games on games.id=saved_games.id_game and saved_games.user_ip="'.get_client_ip().'" ';
        }

        if(trim($extraSelect) == ''){
          $extraSelect = ' where';
        }else{
          $extraSelect = $extraSelect.' and';
        }

        if(isset($_GET['search'])){
          $extraSelect = $extraSelect.' games.name regexp "'.$_GET['search'].'" and ';
        }

        $extraSelect = $extraSelect." games.status=2 and games.isMobile=1 ";

        if(isset($_GET['popular_games'])){
          $extraSelect = $extraSelect.' order by games.tot_players desc ';
        }else{
          if(isset($_GET['mygames'])){
            $extraSelect = $extraSelect.' order by saved_games.id desc ';
          }else{
            $extraSelect = $extraSelect.' order by games.id desc ';
          }
        }

        $selectGames = 'select * from games '.$extraSelect;
        $resultGamesSelect = $conn->query($selectGames.' limit 10 offset '.$offset.';');

        $countG = $resultGamesSelect->num_rows;
        $countTot = $conn->query("select * from games ".$extraSelect.";")->num_rows;
        $maxPage=(int)($countTot/10);
        if($countTot%10 > 0){
          $maxPage++;
        }
        echo "<center>";

        if($countG==0){
          if(isset($_GET['mygames'])){
            echo "<h1 style='color:#07b515;'><b>You don't have saved games</b></h1>";
          }else{
            echo "<h1 style='color:#07b515;'><b>No games here.</b></h1>";
          }
        }

        for ($i=0; $i < 10; $i++) {
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


            echo '<div class="joc" onclick="window.location.href=\'/mobile/game/'.$row["permalink"].'\';">
              <div class="img-cont">
                <img src="'.$img.'" class="img-responsive">
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
          }
          $countG--;
        }
        echo "</center>";

        echo '<br>
        <div class="pagini">
        <center>';

        if(isset($_GET['search'])){
          $extraGet = '&search='.$_GET['search'];
        }

        if(isset($_GET['popular_games'])){
          $extraGet = '&popular_games='.$_GET['popular_games'];
        }

        if(isset($_GET['mygames'])){
          $extraGet = '&mygames='.$_GET['mygames'];
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

    </div>

    <br>
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
    </div>-->
    <br>


    <?php

      arata_drepturi_mobile();

    ?>

  </body>

</html>
