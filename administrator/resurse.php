<?php

  require '../resurse.php';

  function include_login(){
    $conn = $GLOBALS['conn'];

    $ok = false;

    if(isset($_SESSION['username'])){
      $verifUsr = "select * from players where (username='".$_SESSION['username']."' or email='".$_SESSION['username']."') and isAdmin=1;";
      if($conn->query($verifUsr)->num_rows>0){
        $ok=true;
      }
    }

    if(!isset($_GET['logout'])){
      if(!$ok){

        if(isset($_POST['Username']) && isset($_POST['Password'])){
          $user = $_POST['Username'];
          $pass = $_POST['Password'];

          $verifSelect = "select * from players where (email='".$user."' or username='".$user."') and password='".$pass."' and isAdmin=1;";

          $resultSearch = $conn->query($verifSelect);

          if($resultSearch->num_rows>0 && strpos($user, '#') == false && strpos($user, '-') == false && strpos($user, '*') == false && strpos($user, '/') == false && strpos($pass, '#') == false && strpos($pass, '-') == false && strpos($pass, '*') == false && strpos($pass, '/') == false){
            $_SESSION['username'] = $user;
            echo '
            <script>
              $(document).ready(function(){
                setTimeout(function(){
                  $(\'.contPan\').fadeOut(500);
                  setTimeout(function(){
                    window.location.href=\'index.php\';
                  }, 500);
                }, 500);

              });
            </script>
            <div class="contPan">
                <div class="login" style="background-color: rgba(0,0,0,0) !important;">
                  <h3 style="background-color: #20c141 !important;">Login Successfull</h3>
                </div>
              </div>';
          }else{
            echo ' <div class="contPan">
                <div class="login">
                  <h3 style="background-color: #cc2c2c !important;">Incorrect login</h3>
                  <form action="'.$_SERVER["REQUEST_URI"].'" method="post">
                    <div class="form-group row">
                      <label for="username" class="col-sm-4">Username/Email:</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" style="width:100% !important;" placeholder="Enter username or email" name="Username" maxlength="50" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="pwd" class="col-sm-4">Password:</label>
                      <div class="col-sm-8">
                        <input type="password" class="form-control" placeholder="Enter password" name="Password" maxlength="50" required>
                      </div>
                    </div>
                    <center>
                      <button type="submit" class="btn btn-default" style="font-size: 1.3em !important;">Submit</button>
                      <button type="button" class="btn btn-default" style="background-color: #d64534 !important; font-size: 1.3em !important;" onclick="window.location.href=\'/home\';" >I am not an administrator</button>
                    </center>
                  </form>
                </div>
              </div>';

          }

        }else{
          echo ' <div class="contPan">
              <div class="login">
                <h3>Administrator Login</h3>
                <form action="'.$_SERVER["REQUEST_URI"].'" method="post">
                  <div class="form-group row">
                    <label for="username" class="col-sm-4">Username/Email:</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" style="width:100% !important;" placeholder="Enter username or email" name="Username" maxlength="50" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="pwd" class="col-sm-4">Password:</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" placeholder="Enter password" name="Password" maxlength="50" required>
                    </div>
                  </div>
                  <center>
                    <button type="submit" class="btn btn-default" style="font-size: 1.3em !important;">Submit</button>
                    <button type="button" class="btn btn-default" style="background-color: #d64534 !important; font-size: 1.3em !important;" onclick="window.location.href=\'/home\';" >I am not an administrator</button>
                  </center>
                </form>
              </div>
            </div>';
        }
      }
    }else{
      session_destroy();
      echo '<script>
        $(document).ready(function(){
          window.location.href=\'index.php\';
        });
      </script>';
    }
  }

  function include_dependente_admin(){
    echo file_get_contents("resurse/dependente.html");
  }

  function afiseaza_bara_stanga_admin(){
    echo '<nav id="sidebar">
            <ul class="list-unstyled components">';
            if(isset($_SESSION['username'])){
              echo '
              <li style="font-size: 30px;"><center>Welcome, '.$_SESSION['username'].'!</center></li>';
            }
                echo '<li>
                    <a href="index.php">Home</a>
                </li>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Games</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="submit.php">Submit a game</a>
                        </li>
                        <li>
                            <a href="earnings.php">View earnings</a>
                        </li>
                        <li>
                            <a href="submissions.php">Submissions</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="messages.php">Messages</a>
                </li>
                <li>
                    <a href="reports.php">Reports</a>
                </li>
                <li>
                    <a href="developers.php">Developers</a>
                </li>
                <li>
                    <a href="bot.php">Little robot</a>
                </li>';

                if(isset($_SESSION['username'])){
                  echo '
                  <li><a href="index.php?logout=1">Logout</a></li>';
                }

            echo '</ul>
        </nav>
    ';
  }

  function afiseaza_bara_sus_admin(){
    echo file_get_contents("resurse/bara-sus.html");
  }

  function afiseaza_drepturi_admin(){
    echo file_get_contents("resurse/drepturi.html");
  }

  function stringCorrect($string){
    if(strpos($string, '#') || strpos($string, '--') || strpos($string, '*') || strpos($string, '/')){
      return false;
    }else{
      return true;
    }
  }

  function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

?>
