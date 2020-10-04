<?php

  require '../resurse.php';
  use database\db as DB;
  use auth\user_log_reg as auth;
  use secure_tools\base_tools as tool;

  $dir = substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT']));
  $dir = str_replace('\\', '/', $dir);
  if($dir[strlen($dir) - 1] != '/'){
    $dir = $dir.'/';
  }

  $started = 0;

  function alert_mobile($mess){
    if($GLOBALS['started']==1){
      echo '<script>alert_b("'.$mess.'");</script>';
    }
  }

  function arata_bara_mobile(){
    $dir = $GLOBALS['dir'];
    $msql = $GLOBALS['msql'];

    $isAdmin = 0;
    if(isset($_SESSION['username'])){
      $resultAdmin = $msql->select("players")
           ->where("username", $_SESSION['username'])
           ->get();
      $isAdmin = $resultAdmin->fetch_assoc()['isAdmin'];
    }

    echo '
    <div class="alert">
      <button type="button" class="closeBut" onclick="$(\'.alert\').hide(500);"></button>
      <h1 align="center">Alert</h1>
    </div>
    <div class="login">
      <center>
        <button type="button" class="closeBut" onclick="$(\'.login\').hide(500);"></button>
        <h1>Login</h1>
        <form action="'.$_SERVER["REQUEST_URI"].'" method="post">
          <div class="form-group row">
            <label for="username" class="col-sm-4">Username/Email:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" placeholder="Enter username or email" name="Username" maxlength="50" required>
            </div>
          </div>

          <div class="form-group row">
            <label for="pwd" class="col-sm-4">Password:</label>
            <div class="col-sm-8">
              <input type="password" class="form-control" placeholder="Enter password" name="Password" maxlength="50" required>
            </div>
          </div>

          <button type="submit" class="btn btn-default center-block">Submit</button>
        </form>

      </center>
    </div>

    <div class="register">
      <center>
        <button type="button" class="closeBut" onclick="$(\'.register\').hide(500);"></button>
        <h1>Register</h1>
        <form action="'.$_SERVER["REQUEST_URI"].'" method="post">
          <div class="form-group row">
            <label for="email" class="col-xs-2">Email/username:</label>
            <div class="col-xs-5">
              <input type="text" class="form-control" placeholder="Enter email" name="Email" maxlength="50" required>
            </div>
            <div class="col-xs-5">
              <input type="text" class="form-control" placeholder="Enter username" name="Username" maxlength="17" required>
            </div>
          </div>

          <div class="form-group row">
            <label for="pwd" class="col-xs-2">Password/confirm: </label>
            <div class="col-xs-5">
              <input type="password" class="form-control" placeholder="Enter password" name="Password" maxlength="17" required>
            </div>
            <div class="col-xs-5">
              <input type="password" class="form-control" placeholder="Confirm password" name="confirm-pwd" maxlength="17" required>
            </div>
          </div>

          <div class="form-group row">
            <label for="keyword" class="col-xs-4">Security keyword:</label>
            <div class="col-xs-8">
              <input type="text" class="form-control" placeholder="Enter keyword" name="Keyword" maxlength="25" required>
            </div>
          </div>

          <button type="submit" class="btn btn-default center-block" id="register-but-form">Submit</button>
        </form>

      </center>
    </div>';

    if(!isset($_COOKIE["privacy"])){
          echo '
        <div class="privacy">
          <div class="col-xs-8">
            <p>We and our partners collect data and use cookies for ad personalization and measurement,
            content personalization and traffic analysis.
            By continuing on our website you consent to it. Learn how reading our <a href="/privacy-policy.php">Privacy Policy and
            Cookie Policy</a>.'.$_SESSION['privacy'].'</p>
          </div>
          <div class="col-xs-4">
            <button class="btn center-block" onclick="
              var xhttp = new XMLHttpRequest();
              xhttp.open(\'POST\', \''.$dir.'../ajax_requests/set_privacy_session.php\', true);
              xhttp.send();

              $(\'.privacy\').fadeOut();
            ">
              Got it
            </button>
          </div>
        </div>
      ';

      }

    echo '<div class="navbar navbar-default navbar-fixed-top">
     <div class="navbar-header"><a class="navbar-brand" href="/mobile/home"><img src="'.$dir.'../resurse/TemplateData/logo.png" height="100%"></a>
          <a class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse" href="#navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
        </div>
        <div id="navbar-collapse" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="/mobile/home">Home</a></li>
            <li><a href="/mobile/?popular_games=1">Popular games</a></li>
            <li><a href="/mobile/?mygames=1">Saved games</a></li>
            <form class="navbar-form navbar-left" method="get" action="'.$dir.'">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search games" name="search" maxlength="17" required>
                <div class="input-group-btn">
                  <button class="btn btn-default" type="submit">
                    <i class="glyphicon glyphicon-search"></i>
                  </button>
                </div>
              </div>
            </form>
';

            if(isset($_SESSION['username'])){
              echo '<li><a href="/mobile/profile.php">'.$_SESSION['username'].'</a></li>';

              if($isAdmin){
                echo '<li><a href="/administrator" target="_blank">Administrator</a></li>';
              }

              echo '<li><a href="/mobile/home/?logout=1">Logout</a></li>';
            }else{
              echo '<li><a onclick="$(\'.login\').show(500);">Login</a></li>
              <li><a onclick="$(\'.register\').show(500);">Register</a></li>';
            }

          echo '</ul>
        </div>
    </div>';



    $GLOBALS["started"] = 1;
    main_mobile();
  }

  function arata_drepturi_mobile(){
    echo file_get_contents("resurse/bara-jos.html");
  }

  function include_dependente_mobile(){
    $dir = $GLOBALS['dir'];

    echo '
    <meta
     name=\'viewport\'
     content=\'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\'
    />

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="'.$dir.'js/design.js"></script>

    <link rel="stylesheet" href="'.$dir.'css/stil.css?'.rand(10,10000).'">

    <link rel="icon" href="'.$dir.'../imagini/upper_icon.png">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132891226-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag(\'js\', new Date());

      gtag(\'config\', \'UA-132891226-1\');
    </script>

';
  }

  function intra_in_cont_mobile(){
    $dir = $GLOBALS['dir'];

    $msql = $GLOBALS['msql'];
    $auth = $GLOBALS['auth_obj'];

    $username = $_POST['Username'];
    $pwd = $_POST['Password'];

    if(strpos($username, "'") || strpos($username, "\"") || strpos($username, "#") || strpos($username, "*") || strpos($pwd, "'") || strpos($pwd, "\"") || strpos($pwd, "#") || strpos($pwd, "*"))
    {
      alert("Username/Email or password incorrect");
      return;
    }

    if((!ctype_alnum($username) && !filter_var($username, FILTER_VALIDATE_EMAIL)) || !ctype_alnum($pwd)){
      alert("Username/Email or password incorrect");
      return;
    }

    $logged = $auth->login(
      $username,
      $mail,
      $pwd,
      "username",
      FALSE
    );

    if ($logged) {
      alert("Logged in!");
      echo '<script>
        $(document).ready(function(){
          $(".closeBut").prop("onclick", null).off("click");
          $(".closeBut").click(function(){
            window.location.replace("'.$_SERVER['REQUEST_URI'].'");
          });
        });
      </script>';
    }else{
      alert("Username/Email or password incorrect");
    }
  }

  function creaza_cont_mobile(){
    $dir = $GLOBALS['dir'];

    $conn = $GLOBALS['conn'];

    $msql = $GLOBALS['msql'];
    $auth = $GLOBALS['auth_obj'];

    $username = $_POST['Username'];
    $mail = $_POST['Email'];
    $pwd = $_POST['Password'];
    $confirm_pwd = $_POST['confirm-pwd'];
    $keyword = strtolower($_POST['Keyword']);

    if(strlen($username) < 7){
      alert("Username length must be bigger than 7 characters");
      return;
    }

    if(strlen($pwd) < 7){
      alert("Password length must be bigger than 7 characters");
      return;
    }

    if(
    strpos($username, "'") ||
    strpos($username, "\"") ||
    strpos($username, "#") ||
    strpos($username, "*") ||
    strpos($pwd, "'") ||
    strpos($pwd, "\"") ||
    strpos($pwd, "#") ||
    strpos($pwd, "*") ||
    strpos($mail, "'") ||
    strpos($mail, "\"") ||
    strpos($mail, "#") ||
    strpos($mail, "*")
    )
    {
      alert("Do not use special characters in mail or password");
      return;
    }

    if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
    {
      alert("Please provide a real email");
      return;
    }

    if($pwd != $confirm_pwd){
      alert("Password and confirm password do not match");
      return;
    }

    $result = $msql->select("players")
                   ->where("email", $mail)
                   ->get();

    if ($result->num_rows > 0) {
      alert_mobile("Email already in use.");
      echo '<script>
        $(document).ready(function(){
          $(".closeBut").prop("onclick", null).off("click");
          $(".closeBut").click(function(){
            window.location.replace("'.basename($_SERVER['PHP_SELF']).'");
          });
        });
      </script>';
      return;
    }

    $result = $msql->select("players")
                   ->where("username", $username)
                   ->get();

    if ($result->num_rows > 0) {
      alert_mobile("Username already in use.");
        echo '<script>
        $(document).ready(function(){
          $("#inchidere-alert").prop("onclick", null).off("click");
          $("#inchidere-alert").click(function(){
            window.location.replace("'.basename($_SERVER['PHP_SELF']).'");
          });
        });
      </script>';
      return;
      }

    $registered = $auth->register(
      array(
        "email"=>$mail,
        "username"=>$username,
        "password"=>$pwd,
        "description"=>"My description",
        "trophies"=>0,
        "points"=>0,
        "isAdmin"=>0,
        "date"=>date('Y/m/d'),
        "keyword"=>$keyword,
        "developer_name"=>$username
        )
    );

    if ($registered == TRUE) {
      $_SESSION["username"] = $username;
      echo '
      <script>
      var xhttp = new XMLHttpRequest();

      xhttp.open("POST", "'.$dir.'../ajax_requests/set_cookie.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("key=username&value='.$username.'");
      </script>
      ';

      alert_mobile("Account created. You can edit your profile in My profile section.");
      if(!file_exists("../Players")){
        mkdir("../Players");
      }
      if(!file_exists("../Players/".$username)){
        mkdir("../Players/".$username);
      }

      echo '<script>
        $(document).ready(function(){
          $(".closeBut").prop("onclick", null).off("click");
          $(".closeBut").click(function(){
            window.location.replace("'.basename($_SERVER['PHP_SELF']).'");
          });
        });
      </script>';
    } else {
      alert("Error on database");
    }
  }

  function main_mobile(){
    $auth = $GLOBALS['auth_obj'];

    if(isset($_GET['logout'])){
      $auth->logout("username");

      echo
      '<script>
        window.location.replace("/mobile/home");
      </script>';

    }else{
      if(isset($_POST['Username'])){
        if(isset($_POST['Keyword'])){
          creaza_cont_mobile();
        }else{
          intra_in_cont_mobile();
        }
      }
    }
  }

?>
