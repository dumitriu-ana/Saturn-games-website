<?php
  require "resurse.php";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Update Game</title>
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
            $mobile = false;
            $os = iaSistemOperare();
            if($os == "Android" || $os == "iPod" || $os == "iPhone" || $os == "iPad" || $os == "BlackBerry" || $os == "Mobile"){
              $mobile = true;
            }

            $name = "";
            $author = "";
            $description = "";
            $controls = "";
            $category = "";
            $imgType = 0;
            $permalink = "";

              if(isset($_GET["game"]) && !isset($_POST["gameName"])){
                $selectGame = "select * from games where permalink='".$_GET["game"]."';";
                $resGame = $conn->query($selectGame);

                if($resGame->num_rows > 0){
                  $rowGame = $resGame->fetch_assoc();

                  $id = $rowGame['id'];
                  $name = $rowGame['name'];
                  $author = $rowGame['author'];
                  $description = $rowGame['description'];
                  $controls = str_replace("#", "<br>", $rowGame['controls']);
                  $category = $rowGame['category'];
                  $permalink = $rowGame['permalink'];

                  if(file_exists("../jocuri/".$name."/picture.txt")){
                    $imgType = 1;
                  }

                  echo '<script>
                    $(document).ready(function(){
                      $(\'select\').val(\''.$category.'\');
                    });
                  </script>';

                }else{
                  echo '<script>
                    window.location.replace("../404.php");
                  </script>';
                }
              }else if(isset($_GET["game"]) && isset($_POST["gameName"])){
                $controls = str_replace("<br>","#",$_POST["controls"]);
                $update = "update games set name='".$_POST["gameName"]."',
                author='".$_POST["author"]."',
                description='".$_POST["description"]."',
                category='".$_POST["categorie"]."',
                controls='".$controls."'
                where permalink='".$_GET["game"]."';";

                $name = $_POST['gameName'];
                $author = $_POST['author'];
                $description = $_POST['description'];
                $controls = $_POST['controls'];
                $category = $_POST['categorie'];
                $conn->query($update);

                if(file_exists("../jocuri/".$name."/picture.txt")){
                  $imgType = 1;
                }

                if($imgType==0){
                  if(isset($_FILES["game-icon"]["tmp_name"])){
                    $check = getimagesize($_FILES["game-icon"]["tmp_name"]);
                    if($check !== false) {
                      if ($_FILES["game-icon"]["size"] < 100000000) {
                        $ext = pathinfo($_FILES['game-icon']['name'], PATHINFO_EXTENSION);
                        if(strtolower($ext) == "jpg" || strtolower($ext) == "jpeg"){
                          unlink("../jocuri/".$name."/picture.jpg");
                          move_uploaded_file($_FILES["game-icon"]["tmp_name"], "../jocuri/".$name."/picture.jpg");
                        }
                      }else{
                        echo '<p style="color:#d61d1d; font-weight:900;">Image file size exceeds 10MB. Please try with another file</p>';
                      }
                    }
                  }
                }else if($imgType==1){
                  $iconImg = fopen("../jocuri/".$name."/picture.txt", "w");
                  fwrite($iconImg, $_POST['game-img-link']);
                  fclose($iconImg);
                }

                if($_POST["submit"] == "Accept"){
                  $permalink = $conn->query("select * from games where name='".$name."';")->fetch_assoc()["permalink"];
                  echo '<script>
                    window.location.replace("submissions.php?game_accept='.$permalink.'");
                  </script>';
                }else{
                  echo '<script>
                    window.location.replace("submissions.php?success=1");
                  </script>';
                }
              }else{
                echo '<script>
                  window.location.replace("../404.php");
                </script>';
              }

              $linkGame = '../game/'.$permalink;

              if($mobile){
                $linkGame = '../mobile/game/'.$permalink;
              }
            ?>

          <form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group" style="display: none;">
              <label>Game name:</label>
              <input type="text" class="form-control" placeholder="Game name" name="gameName" value="<?php echo $name; ?>" required>
            </div>
            <div class="form-group">
              <h1><?php echo $name; ?></h1>
            </div>
            <div class="form-group">
              <label>Author:</label>
              <input type="text" class="form-control" placeholder="Author" name="author" value="<?php echo $author; ?>" required>
            </div>
            <div class="form-group">
              <label>Game description:</label><br>
              <textarea rows="4" style="resize: none; width:100%; border-radius: 5px;" name="description" required><?php echo $description; ?></textarea>
            </div>

            <div class="form-group">
              <label>Game controls:</label><br>
              <textarea rows="4" style="resize: none; width:100%; border-radius: 5px;" name="controls"><?php echo $controls; ?></textarea>
            </div>

            <div class="form-group">
              <label>Game category:</label><br>
              <select class="form-control" data-size="5" data-live-search="true" name="categorie">
                <?php
                  $categorii = explode(",", file_get_contents("../resurse/categorii.txt"));
                  for ($i=0; $i < count($categorii); $i++) {
                    echo '<option data-tokens="'.trim($categorii[$i]).'" data-content="<span class=\'badge badge-success\'>'.trim($categorii[$i]).'</span>">'.trim($categorii[$i]).'</option>';
                  }
                ?>
              </select>
            </div>
            <?php

              if($imgType==0){
                echo '<div class="form-group">
                  <label>Game image (4:3 resolution recommanded, JPG/JPEG supported):</label>
                  <input type="file" id="base-input" class="form-control form-input form-style-base" name="game-icon">
                  <br><p>Current img: </p>
                  <center><img src="../jocuri/'.$name.'/picture.jpg" style="width:20%;"><center>
                </div>';
              }else{
                echo '<div class="form-group">
                  <label>Game image link:</label>
                  <input type="text" class="form-control" placeholder="Game image link" name="game-img-link" value="'.file_get_contents('../jocuri/'.$name.'/picture.txt').'" required>
                  <br><p>Current img: </p>
                  <center><img src="'.file_get_contents('../jocuri/'.$name.'/picture.txt').'" style="width:20%;"><center>
                </div>';
              }

            ?>

            <button type="submit" class="btn btn-default btn-primary" name="submit" value="Update">Update game</button>
            <button type="submit" class="btn btn-default btn-success" name="submit" value="Accept">Accept game</button>
            <button type="button" class="btn btn-default btn-danger" onclick="
            window.location.href='submissions.php?game_delete=<?php echo $permalink; ?>';
            ">Delete game</button>
            <br><br>
            <button type="button" class="btn btn-default btn-warning" onclick="
              window.location.href='submissions.php';
            ">Back to submissions</button>
            <button type="button" class="btn btn-default" onclick="
            window.open('../game/<?php echo $linkGame; ?>', '_blank');
            ">Preview game</button>
          </form>
        </center>
        </div>

        <?php
          afiseaza_drepturi_admin();
        ?>
    </div>

  </body>
</html>
