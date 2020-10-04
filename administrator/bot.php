<?php
  require "resurse.php";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Little robot</title>
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
        <center>

          <?php
            if(isset($_POST["page-data"])){
              $resGames = $conn->query("select * from games;");
              $names = array();
              $permalinks = array();

              while($row = $resGames->fetch_assoc()) {
                array_push($names,strtolower((string)$row['name']));
                array_push($permalinks,(string)$row['permalink']);
              }

              function verifName($name, $permalink){
                $names = $GLOBALS['names'];
                $permalinks = $GLOBALS['permalinks'];
                for ($i=0; $i < count($names); $i++) {
                  if(strtolower(trim($name)) == trim($names[$i]) || trim($permalink) == trim($permalinks[$i])){
                    return false;
                  }
                }
                return true;
              }

              $htmlContent = $_POST["page-data"];

              for ($i=1; $i <= 20; $i++) {

                $permalink = trim(explode("\">",explode("class=\"title\" title=\"", $htmlContent)[$i])[0]);
                $name = $permalink;

                if(trim($name) != ""){
                  $permalink = strtolower($permalink);
                  $permalink = preg_replace("/[^a-z0-9_\s-]/", "", $permalink);
                  $permalink = preg_replace("/[\s-]+/", " ", $permalink);
                  $permalink = preg_replace("/[\s_]/", "-", $permalink);

                  $link = "https://gamedistribution.com/games/".$permalink;

                  $gameContentHTML = file_get_contents($link);

                  $httpsStrPos=strpos($gameContentHTML, 'HTTPS ready');

                  if($httpsStrPos){
                    if(explode("</span>", explode("<span data-v-498e134b>", explode('HTTPS ready', $gameContentHTML)[1])[1])[0]){
                      $html5 = trim(explode('" data-v-498e134b>', (explode('<input type="text" value="', $gameContentHTML)[1]))[0]);

                      $isMobile = 0;
                      $mobileStrPos=strpos($gameContentHTML, 'Mobile ready');
                      if($mobileStrPos){
                        $mobVar = explode("</span>", explode("<span data-v-498e134b>", explode('Mobile ready', $gameContentHTML)[1])[1])[0];
                        if(strtolower(trim($mobVar)) == "yes"){
                          $isMobile = 1;
                        }
                      }

                      $description = explode("</span>", explode("<span data-v-498e134b>", explode('Description', $gameContentHTML)[1])[1])[0];
                      $description = str_replace("'","",$description);
                      $description = str_replace("\"","",$description);

                      $author = explode('"', explode('title="', explode("</span>", explode("<span data-v-498e134b>", explode('Company', $gameContentHTML)[1])[1])[0])[1])[0];

                      $imgLink = explode('"', explode('class="image is-4by3" data-v-498e134b><img src="', $gameContentHTML)[1])[0];

                      /// DB
                      if(verifName($name, $permalink)){
                        if(file_exists('../jocuri/'.$name)){
                          deleteDir('../jocuri/'.$name);
                        }
                        mkdir('../jocuri/'.$name);

                        $iconImg = fopen("../jocuri/".$name."/picture.txt", "w");
                        fwrite($iconImg, $imgLink);
                        fclose($iconImg);

                        $linkFile = fopen("../jocuri/".$name."/link", "w");
                        fwrite($linkFile, $html5);
                        fclose($linkFile);

                        $insertGame = "insert into games(
                          name,
                          author,
                          description,
                          controls,
                          category,
                          likes,
                          dislikes,
                          fullscreen,
                          tot_money,
                          month_money,
                          tot_players,
                          month_players,
                          yesterday_players,
                          today_players,
                          reset_day_date,
                          status,
                          forCompany,
                          game_type,
                          isMobile,
                          permalink
                          )
                          values(
                            '".$name."',
                            '".$author."',
                            \"".$description."\",
                            '',
                            'Arcade',
                            0,
                            0,
                            1,
                            0,
                            0,
                            0,
                            0,
                            0,
                            0,
                            0,
                            1,
                            1,
                            1,
                            ".$isMobile.",
                            '".$permalink."'
                          );";

                          $conn->query($insertGame);
                      }
                    }
                  }
                }
              }


              echo
              '<script>
                window.location.replace("'.basename($_SERVER['PHP_SELF']).'?success=1");
              </script>';

            }else{

              if(isset($_GET['success'])){
                if($_GET['success']==1){
                  echo '<p style="color: #24d833;">Successful published the games. You must accept them in submissions</p>';
                }else{
                  echo '<p style="color: #c91e1e;">Something got wrong.</p>';
                }
              }

              echo '<form action="bot.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Page data:</label><br>
                  <textarea rows="15" style="resize: none; width:100%; border-radius: 5px;" name="page-data" required></textarea>
                </div>
                <button type="submit" class="btn btn-default btn-primary">Submit</button>
              </form>';
            }
          ?>

        </center>
      </div>
        <?php
          afiseaza_drepturi_admin();
        ?>
    </div>
  </div>

  </body>
</html>
