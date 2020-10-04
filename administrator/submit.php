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

    <script type="text/javascript">

    $(document).ready(function(){
      $('#unity-upload').hide(500);
      $('#iframe-upload').show(500);

      $("#gameSelect").change(function() {
        var selectVal = $("#gameSelect").find(":selected").text();
        if(selectVal.toLowerCase().includes('unity')){
          $('#unity-upload').show(500);
          $('#iframe-upload').hide(500);
        }else if(selectVal.toLowerCase().includes('iframe')){
          $('#unity-upload').hide(500);
          $('#iframe-upload').show(500);
        }
      });
    });

    </script>
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
              if(isset($_GET['successful'])){
                echo '<p style="color: #24d833;">Successful published the game. You must accept it in submissions</p>';
              }

              if(isset($_POST['gameName'])){
                $name = $_POST['gameName'];
                $name = str_replace("'", "", $name);
                $author = $_POST['gameAuthor'];
                $author = str_replace("'", "", $author);
                $description = $_POST['description'];
                $description = str_replace("\"", "", $description);
                $controls = $_POST['controls'];

                $category = $_POST['category'];

                $isMobile = 0;
                $fullscreen = 0;

                $gameType = $_POST['gameType'];

                $googlePlay = '';
                $ios = '';
                $steam = '';

                if($_POST['googlePlay']){
                  $googlePlay = $_POST['googlePlay'];
                }

                if($_POST['ios']){
                  $googlePlay = $_POST['ios'];
                }

                if($_POST['steam']){
                  $googlePlay = $_POST['steam'];
                }

                if(isset($_POST['isMobile'])){
                  if($_POST['isMobile']=="on"){
                    $isMobile = 1;
                  }
                }

                if(isset($_POST['fullscreen'])){
                  if($_POST['fullscreen']=="on"){
                    $fullscreen = 1;
                  }
                }

                $ok = true;

                if(!stringCorrect($name)){
                  echo '<p style="color: #e02626;">Please use only letters and numbers in name</p>';
                  $ok=false;
                }

                if(!stringCorrect($author)){
                  echo '<p style="color: #e02626;">Please use only letters and numbers in author</p>';
                  $ok=false;
                }

                if(!stringCorrect($description)){
                  echo '<p style="color: #e02626;">Please use only letters and numbers in description</p>';
                  $ok=false;
                }

                if(!stringCorrect($controls)){
                  echo '<p style="color: #e02626;">Please use only letters and numbers in controls</p>';
                  $ok=false;
                }

                $controls = str_replace("<br>", "#", $controls);
                $controls = str_replace("'", "", $controls);

                if($ok){
                  $permalink = strtolower($name);
                  $permalink = preg_replace("/[^a-z0-9_\s-]/", "", $permalink);
                  $permalink = preg_replace("/[\s-]+/", " ", $permalink);
                  $permalink = preg_replace("/[\s_]/", "-", $permalink);

                  $gameNameVerif = "select * from games where name='".$conn->real_escape_string($name)."'
                  or permalink='".$conn->real_escape_string($permalink)."';";

                  $gameNameResult = $conn->query($gameNameVerif);

                  if($gameNameResult->num_rows == 0){

                    if(file_exists('../jocuri/'.$name)){
                      deleteDir('../jocuri/'.$name);
                    }

                    if(isset($_FILES["game-icon"]["tmp_name"]) && trim($_FILES["game-icon"]["tmp_name"])!="") {
                      $check = getimagesize($_FILES["game-icon"]["tmp_name"]);
                      if($check !== false) {
                          mkdir('../jocuri/'.$name);
                          if ($_FILES["game-icon"]["size"] > 100000000) {
                            echo '<p style="color: #e02626;">Please upload an image with 10MB max</p>';
                          }else{
                            $ext = pathinfo($_FILES['game-icon']['name'], PATHINFO_EXTENSION);
                            if(strtolower($ext) == "jpg" || strtolower($ext) == "jpeg"){
                              if (move_uploaded_file($_FILES["game-icon"]["tmp_name"], "../jocuri/".$name."/picture.jpg")) {

                                if(trim($googlePlay)){
                                  $fileWrite = fopen("../jocuri/".$name."/google_play.txt", "w");
                                  fwrite($fileWrite, $googlePlay);
                                  fclose($fileWrite);
                                }

                                if(trim($ios)){
                                  $fileWrite = fopen("../jocuri/".$name."/app_store.txt", "w");
                                  fwrite($fileWrite, $ios);
                                  fclose($fileWrite);
                                }

                                if(trim($steam)){
                                  $fileWrite = fopen("../jocuri/".$name."/steam.txt", "w");
                                  fwrite($fileWrite, $steam);
                                  fclose($fileWrite);
                                }

                                if($gameType=="Unity 5.6+"){
                                  if(isset($_FILES["unity-upload"]["tmp_name"])) {
                                    $extArchive = pathinfo($_FILES['unity-upload']['name'], PATHINFO_EXTENSION);
                                    echo $extArchive;
                                    if($extArchive=="zip"){
                                      if ($_FILES["unity-upload"]["size"] > 3000000000) {
                                        echo '<p style="color: #e02626;">Please upload a file with maximum size of 300 MB.</p>';
                                      }else{
                                        if (move_uploaded_file($_FILES["unity-upload"]["tmp_name"], "../jocuri/".$name."/archive.zip")) {
                                          $file = "../jocuri/".$name."/archive.zip";

                                          $path = pathinfo(realpath($file), PATHINFO_DIRNAME);
                                          $zip = new ZipArchive;
                                          $res = $zip->open($file);
                                          if ($res === TRUE) {
                                            $zip->extractTo($path);
                                            $zip->close();

                                            unlink("../jocuri/".$name."/archive.zip");
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
                                                '".$conn->real_escape_string($name)."',
                                                '".$conn->real_escape_string($author)."',
                                                \"".$conn->real_escape_string($description)."\",
                                                '".$conn->real_escape_string($controls)."',
                                                '".$conn->real_escape_string($category)."',
                                                0,
                                                0,
                                                ".$fullscreen.",
                                                0,
                                                0,
                                                0,
                                                0,
                                                0,
                                                0,
                                                0,
                                                1,
                                                1,
                                                0,
                                                ".$isMobile.",
                                                '".$conn->real_escape_string($permalink)."'
                                              );";
                                              $conn->query($insertGame);
                                              echo
                                              '<script>
                                                window.location.replace("'.basename($_SERVER['PHP_SELF']).'?successful=1");
                                              </script>';

                                          } else {
                                            echo '<p style="color: #e02626;">We could not proccess the file. We are sorry!</p>';
                                          }
                                        }else{
                                          echo '<p style="color: #e02626;">An error occured while uploading</p>';
                                        }
                                    }
                                    }else{
                                      echo '<p style="color: #e02626;">Please upload a zip file with the BUILD folder in it.</p>';
                                    }
                                  }else{
                                    echo '<p style="color: #e02626;">Please upload a zip file with the BUILD folder in it.</p>';
                                  }

                                }else if($gameType=="IFrame"){
                                  $link = $_POST['iframeLink'];
                                  $linkFile = fopen("../jocuri/".$name."/link", "w");
                                  fwrite($linkFile, $link);
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
                                      '".$conn->real_escape_string($name)."',
                                      '".$conn->real_escape_string($author)."',
                                      \"".$conn->real_escape_string($description)."\",
                                      '".$conn->real_escape_string($controls)."',
                                      '".$conn->real_escape_string($category)."',
                                      0,
                                      0,
                                      ".$fullscreen.",
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
                                      '".$conn->real_escape_string($permalink)."'
                                    );";
                                    $conn->query($insertGame);
                                    echo
                                    '<script>
                                      window.location.replace("'.basename($_SERVER['PHP_SELF']).'?successful=1");
                                    </script>';
                                }
                              }else{
                                echo '<p style="color: #e02626;">An error occured while uploading image</p>';
                              }
                            }else{
                              echo '<p style="color: #e02626;">Please upload an jpg image</p>';
                            }
                          }
                      } else {
                        echo '<p style="color: #e02626;">The file you uploaded in game icon is not a picture. Please insert a picture</p>';
                      }
                  }else{
                    if(isset($_POST['game-icon-link'])){
                      mkdir('../jocuri/'.$name);
                      $iconImg = fopen("../jocuri/".$name."/picture.txt", "w");
                      fwrite($iconImg, $_POST['game-icon-link']);
                      fclose($iconImg);

                      $link = $_POST['iframeLink'];
                      $linkFile = fopen("../jocuri/".$name."/link", "w");
                      fwrite($linkFile, $link);
                      fclose($linkFile);

                      if($gameType=="Unity 5.6+"){
                        if(isset($_FILES["unity-upload"]["tmp_name"])) {
                          $extArchive = pathinfo($_FILES['unity-upload']['name'], PATHINFO_EXTENSION);
                          echo $extArchive;
                          if($extArchive=="zip"){
                            if ($_FILES["unity-upload"]["size"] > 3000000000) {
                              echo '<p style="color: #e02626;">Please upload a file with maximum size of 300 MB.</p>';
                            }else{
                              if (move_uploaded_file($_FILES["unity-upload"]["tmp_name"], "../jocuri/".$name."/archive.zip")) {
                                $file = "../jocuri/".$name."/archive.zip";

                                $path = pathinfo(realpath($file), PATHINFO_DIRNAME);
                                $zip = new ZipArchive;
                                $res = $zip->open($file);
                                if ($res === TRUE) {
                                  $zip->extractTo($path);
                                  $zip->close();

                                  unlink("../jocuri/".$name."/archive.zip");
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
                                      '".$conn->real_escape_string($name)."',
                                      '".$conn->real_escape_string($author)."',
                                      \"".$conn->real_escape_string($description)."\",
                                      '".$conn->real_escape_string($controls)."',
                                      '".$conn->real_escape_string($category)."',
                                      0,
                                      0,
                                      ".$fullscreen.",
                                      0,
                                      0,
                                      0,
                                      0,
                                      0,
                                      0,
                                      0,
                                      1,
                                      1,
                                      0,
                                      ".$isMobile.",
                                      '".$conn->real_escape_string($permalink)."'
                                    );";
                                    $conn->query($insertGame);
                                    echo
                                    '<script>
                                      window.location.replace("'.basename($_SERVER['PHP_SELF']).'?successful=1");
                                    </script>';

                                } else {
                                  echo '<p style="color: #e02626;">We could not proccess the file. We are sorry!</p>';
                                }
                              }else{
                                echo '<p style="color: #e02626;">An error occured while uploading</p>';
                              }
                          }
                          }else{
                            echo '<p style="color: #e02626;">Please upload a zip file with the BUILD folder in it.</p>';
                          }
                        }else{
                          echo '<p style="color: #e02626;">Please upload a zip file with the BUILD folder in it.</p>';
                        }

                      }else if($gameType=="IFrame"){
                        $link = $_POST['iframeLink'];
                        $linkFile = fopen("../jocuri/".$name."/link", "w");
                        fwrite($linkFile, $link);
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
                            '".$controls."',
                            '".$category."',
                            0,
                            0,
                            ".$fullscreen.",
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
                          echo
                          '<script>
                            window.location.replace("'.basename($_SERVER['PHP_SELF']).'?successful=1");
                          </script>';
                      }
                    }
                  }

                  }else{
                    echo '<p style="color: #e02626;">Please use another game for the name. There is another game with the same name on the website</p>';
                  }
                }
              }
            ?>
          <form action="submit.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label>Game name:</label>
              <input type="text" class="form-control" placeholder="Game name" name="gameName" required maxlength="100">
            </div>
            <div class="form-group">
              <label>Game author:</label>
              <input type="text" class="form-control" placeholder="Game author" name="gameAuthor" required>
            </div>
            <div class="form-group">
              <label>Game description:</label><br>
              <textarea rows="4" style="resize: none; width:100%; border-radius: 5px;" name="description" required></textarea>
            </div>

            <div class="form-group">
              <label>Game controls (use &lt;br&gt; for new line):</label><br>
              <textarea rows="4" style="resize: none; width:100%; border-radius: 5px;" name="controls" required></textarea>
            </div>

            <div class="form-group">
              <label>Game category:</label><br>
              <select class="form-control" name="category">
            <?php
                $categorii = explode(",", file_get_contents("../resurse/categorii.txt"));

                for ($i=0; $i < count($categorii); $i++) {

                  echo '<option data-tokens="'.trim($categorii[$i]).'" data-content="<span class=\'badge badge-success\'>'.trim($categorii[$i]).'</span>">'.trim($categorii[$i]).'</option>';

                }

            ?>
              </select>

            </div>

            <div class="form-group">
              <label>Other platforms:</label>
              <input type="text" class="form-control" placeholder="Google Play" name="googlePlay"><br>
              <input type="text" class="form-control" placeholder="App Store" name="ios"><br>
              <input type="text" class="form-control" placeholder="Steam" name="steam">
            </div>

            <div class="form-group">
              <label>Game type:</label><br>
              <select class="form-control" id="gameSelect" name="gameType">
                <option>IFrame</option>
                <option>Unity 5.6+</option>
              </select>
              <br><br>
            </div>

            <input type="checkbox" name="fullscreen" checked> <span class="tx">Can be played fullscreen?<span><br>
            <input type="checkbox" name="isMobile"> <span class="tx">Can be played in mobile?</span>
            <br><br>


            <div class="form-group" id="unity-upload">
              <label>Game files ('Build' folder zipped):</label>
              <input type="file" id="base-input" class="form-control form-input form-style-base" name="unity-upload">
            </div>

            <div class="form-group" id="iframe-upload">
              <label>Game IFrame link:</label>
              <input type="text" class="form-control" placeholder="IFrame Link name" name="iframeLink">
            </div>

            <div class="form-group">
              <label>Game image (4:3 resolution recommanded, JPG/JPEG supported):</label>
              <input type="file" id="base-input" class="form-control form-input form-style-base" name="game-icon" >
            </div>

<div class="form-group">
              <label>Or link to image:</label>
              <input type="text" class="form-control" placeholder="Game icon link" name="game-icon-link" maxlength="100">
            </div>

            <button type="submit" class="btn btn-default btn-primary">Submit</button>
          </form>
        </center>
        </div>

        <?php
          afiseaza_drepturi_admin();
        ?>
    </div>

  </body>
</html>
