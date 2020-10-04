<?php

session_start();

require "Libs/requirements.php";
use database\db as DB;
use auth\user_log_reg as auth;
use secure_tools\base_tools as tool;

$enabledCookies = 0;

if(count($_COOKIE) > 0) {
  $enabledCookies = 1;
}

$createdNow = 0;

if(isset($_COOKIE['uniq_user']) && strtolower($_COOKIE['uniq_user'])=="test"){
  unset($_COOKIE['uniq_user']);
  setcookie('uniq_user', null, -1, '/');
}

$uniq_id = "";

if(!isset($_COOKIE['uniq_user'])){
  $uniq_id = (string)uniqid(mt_rand(), true);
  $uniq_id=$uniq_id.get_now().((string)round(microtime(true) * 1000));

  setcookie("uniq_user", $uniq_id, time() + (10 * 365 * 24 * 60 * 60), '/');
  $createdNow = 1;
}else{
  $uniq_id = $_COOKIE['uniq_user'];
}

$started = 0;

$servername = "127.0.0.1";
$username = "satushtm_admin";
$password = "Steve987";
$dbname = "satushtm_saturn-games";
$dbport = "3306";

$conn = new mysqli($servername, $username, $password, $dbname, $dbport);

$msql = new DB(
  $servername,
  $username,
  $password,
  $dbname,
  $dbport
);

$auth_obj = new auth(
  $msql,
  "players"
);

$login=false;
$falseLogin=false;

if(isset($_GET['logout'])){
  $auth_obj->logout("username");
  echo
  '<script>
    window.location.replace("/home");
  </script>';
}else if(isset($_GET['changepwd'])){
  schimba_parola();
}
else{
  if(isset($_POST['Username'])){
    if(!isset($_POST['Keyword'])){
      intra_in_cont();
    }
  }
}



if($enabledCookies==1){
  if($createdNow==1){
    $insertUq = "insert into uniq_users (
        id
      )values(
        '".$uniq_id."'
      );";

      $conn->query($insertUq);
  }else{

    $selectUq = "select * from uniq_users where id='".$uniq_id."';";

    $numUqSel = $conn->query($selectUq)->num_rows;

    if($numUqSel==0){
      $insertUq = "insert into uniq_users (
          id
        )values(
          '".$uniq_id."'
        );";

        $conn->query($insertUq);
    }else{
      $updateUq = "update uniq_users set last_activity=now() where id='".$uniq_id."';";

      $conn->query($updateUq);
    }
  }
}

$resultOldUsers = $msql
                  ->select("uniq_users")
                  ->olderThan("last_activity", 30)
                  ->get();

while($row=$resultOldUsers->fetch_assoc()){
  $id = $row['id'];

  $deleteQ = "DELETE * from saved_games WHERE user_ip='".$id."';";

  $msql->query($deleteQ);

  $deleteInactiveUser = "delete * from uniq_users where id='".$id."';";

  $msql->query($deleteInactiveUser);

}

$dir = substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT']));
$dir = str_replace('\\', '/', $dir);
if(trim($dir) != '/'){
  $dir = $dir.'/';
}

function string_to_date($string){
  $date_obj = new \DateTime($string);

  return DateTime::createFromFormat('Y-m-d H:i:s', $date_obj->format('Y-m-d H:i:s'));
}

function get_now(){
  $date_utc = new \DateTime("now", new \DateTimeZone("UTC"));
  $utcDate = $date_utc->format(\DateTime::RFC850);

  return $utcDate;
}

function afiseaza_bara(){
  $msql = $GLOBALS['msql'];

  $dir = $GLOBALS['dir'];

  echo '<div class="cont-pan">
    <div class="login">
      <button type="button" name="button" class="btn btn-default btn-inchidere"></button>
      <h2 align="center">Login</h2>
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

        <div class="form-group row">
          <center><a href="forgot-password.php" style="text-decoration:underline;">I forgot my password</a></center>
        </div>

        <button type="submit" class="btn btn-default center-block">Login</button>
      </form>
    </div>

    <div class="register">
      <button type="button" name="button" class="btn btn-default btn-inchidere"></button>
      <h2 align="center">Register</h2>
      <form action="'.$_SERVER["REQUEST_URI"].'" method="post">
        <div class="form-group row">
          <label for="email" class="col-sm-4">Email:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Enter email" name="Email" maxlength="50" required>
          </div>
        </div>

        <div class="form-group row">
          <label for="username" class="col-sm-4">Username:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Enter username" name="Username" maxlength="17" required>
          </div>
        </div>

        <div class="form-group row">
          <label for="pwd" class="col-sm-4">Password:</label>
          <div class="col-sm-8">
            <input type="password" class="form-control" placeholder="Enter password" name="Password" maxlength="17" required>
          </div>
        </div>

        <div class="form-group row">
          <label for="pwd" class="col-sm-4">Confirm password:</label>
          <div class="col-sm-8">
            <input type="password" class="form-control" placeholder="Confirm password" name="confirm-pwd" maxlength="17" required>
          </div>
        </div>

        <div class="form-group row">
          <label for="keyword" class="col-sm-4">Security keyword:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Enter keyword" name="Keyword" maxlength="25" required>
          </div>
        </div>

        <button type="button" class="btn btn-default center-block" id="register-but-form">Register</button>
      </form>
    </div>
  </div>
  <div class="alert-pan">
    <div class="pan">
      <button type="button" name="button" class="btn btn-default btn-inchidere" id="inchidere-alert"></button>
      <h2 align="center">Register</h2>
    </div>
  </div>
';

    if(!isset($_COOKIE["privacy"])){
          echo '
        <div class="privacy">
          <div class="col-md-10">
            <p>We and our partners collect data and use cookies for ad personalization and measurement,
            content personalization and traffic analysis.
            By continuing on our website you consent to it. Learn how reading our <a href="/privacy-policy.php">Privacy Policy and
            Cookie Policy</a>.'.$_SESSION['privacy'].'</p>
          </div>
          <div class="col-md-2">
            <button class="btn center-block" onclick="
              var xhttp = new XMLHttpRequest();
              xhttp.open(\'POST\', \''.$dir.'ajax_requests/set_privacy_session.php\', true);
              xhttp.send();

              $(\'.privacy\').fadeOut();
            ">
              Got it
            </button>
          </div>
        </div>
      ';
      }

  $GLOBALS['started'] = 1;
  main();

  echo '<nav class="navbar navbar-inverse fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="'.$dir.'home/"><img src="'.$dir.'resurse/TemplateData/logo.png" height="100%"></a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="'.$dir.'home/?popular_games=1">Popular games</a></li>
        <li><a href="'.$dir.'contact.php">Contact</a></li>';
        if(isset($_SESSION['username'])){
          echo '<li><a href="'.$dir.'profile.php">'.$_SESSION["username"].'</a></li>';
        }

      echo '</ul>
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

      <ul class="nav navbar-nav">
        <li><a href="'.$dir.'home/?mygames=1">Saved games</a></li>
      </ul>

      <div class="col-md-2 navbar-form navbar-right" style="min-width:15% !important;">
        <select class="selectpicker form-control" data-size="3" data-live-search="true" name="categorie">
          ';
          echo '<option data-tokens="Category" data-content="<span class=\'badge badge-success\'>Category</span>">Category</option>';

          $categorii = explode(",", file_get_contents("resurse/categorii.txt"));
          for ($i=0; $i < count($categorii); $i++) {
            echo '<option data-tokens="'.trim($categorii[$i]).'" data-content="<span class=\'badge badge-success\'>'.trim($categorii[$i]).'</span>">'.trim($categorii[$i]).'</option>';
          }

          echo '
        </select>
      </div>';

      if(isset($_SESSION['username'])){
        echo '<button class="btn btn-warning navbar-btn navbar-right"
        onclick="window.location.href = \''.$dir.'home/?logout=true\';"
        >Log out</button>';

        $resultAdmin = $msql->select("players")
             ->where("username", $_SESSION['username'])
             ->get();

        $isAdmin = $resultAdmin->fetch_assoc()['isAdmin'];

        if($isAdmin==1){
          echo '<button style="margin-right:1%;" class="btn btn-success navbar-btn navbar-right"
          onclick="window.open(\''.$dir.'administrator\', \'_blank\');"
          >Admnistrator</button>';
        }
      }else{

        echo
        '
        <button class="btn btn-warning navbar-btn navbar-right" id="register-but">Register</button>
        <button class="btn btn-success navbar-btn navbar-right" style="margin-right:1%;" id="login-but">Login</button>';
      }
    echo '</div>
  </nav>';

  echo '<nav class="navbar navbar-replace" style="visibility: hidden;">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="'.$dir.'home"><img src="'.$dir.'resurse/TemplateData/logo.png" height="100%"></a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="'.$dir.'home/?popular_games=1">Popular games</a></li>
        <li><a href="'.$dir.'contact.php">Contact</a></li>';
        if(isset($_SESSION['username'])){
          echo '<li><a href="'.$dir.'profile.php">My profile</a></li>';
        }

      echo '</ul>
      <form class="navbar-form navbar-left" method="get" action="'.$dir.'home">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search games" name="search" maxlength="17" required>
          <div class="input-group-btn">
            <button class="btn btn-default" type="submit">
              <i class="glyphicon glyphicon-search"></i>
            </button>
          </div>
        </div>
      </form>

      <ul class="nav navbar-nav">
        <li><a href="'.$dir.'home/?mygames=1">Saved games</a></li>
      </ul>

      <div class="col-md-2 navbar-form navbar-right" style="min-width:15% !important;">
        <select class="selectpicker form-control" data-size="3" data-live-search="true" name="categorie">
          ';

          $categorii = explode(",", file_get_contents("resurse/categorii.txt"));
          for ($i=0; $i < count($categorii); $i++) {
            echo '<option data-tokens="'.trim($categorii[$i]).'" data-content="<span class=\'badge badge-success\'>'.trim($categorii[$i]).'</span>">'.trim($categorii[$i]).'</option>';
          }

          echo '
        </select>
      </div>';

      if(isset($_SESSION['username'])){
        echo '<button class="btn btn-warning navbar-btn navbar-right"
        onclick="window.location.href = \''.$dir.'home?logout=true\';"
        >Log out</button>';

        if($isAdmin==1){
          echo '<button style="margin-right:1%;" class="btn btn-success navbar-btn navbar-right"
          onclick="window.open(\''.$dir.'administrator\', \'_blank\');"
          >Admnistrator</button>';
        }


      }else{

        echo
        '
        <button class="btn btn-warning navbar-btn navbar-right" id="register-but">Register</button>
        <button class="btn btn-success navbar-btn navbar-right" style="margin-right:1%;" id="login-but">Login</button>';
      }
    echo '</div>
  </nav>';

  if(isset($_GET['categ'])){
    echo '<script>
      $(document).ready(function(){
        $(\'.selectpicker\').selectpicker(\'val\', \''.$_GET['categ'].'\');
      });
    </script>';
  }

}

function alert($mess){
  if($GLOBALS['started']==1){
    echo '<script>alert_b("'.$mess.'");</script>';
  }
}

function debug_alert($mess){
  echo '<script>alert("'.$mess.'");</script>';
}

function afiseaza_drepturi(){
  echo file_get_contents("resurse/drepturi.html");
}

function include_dependente(){
  $dir = $GLOBALS['dir'];

  echo '<!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <link rel="stylesheet" media="screen" href="'.$dir.'css/stil.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

  <!-- (Optional) Latest compiled and minified JavaScript translation files -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>
  <script type="text/javascript" src="'.$dir.'js/design.js"></script>

  <link rel="icon" href="'.$dir.'imagini/upper_icon.png">

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

function afiseaza_jocuri_recente(){
  $msql = $GLOBALS['msql'];
  $dir = $GLOBALS['dir'];

  echo '<div class="row recente">
    <div class="col-sm-2 jocuri-recente iconita">
      <img src="'.$dir.'imagini/recent icon.png" height="40%"/>
      <br>
      <p style="color: #b2b0b0; font-size: 13px !important;"><b style="color:white;">We saved your recent games.</b><br>
        Games that you played will appear here.
      </p>
    </div>';

    for ($i=1; $i < 4; $i++) {
      if(isset($_SESSION['recent'.$i])){
        $resultRecent = $msql->select("games")
                             ->where("permalink", $_SESSION['recent'.$i])
                             ->get();

        $row = $resultRecent->fetch_assoc();

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

        echo '<div class="col-sm-2 jocuri-recente" onclick="window.location.href=\''.$dir.'game/'.$row["permalink"].'\';">
          <div class="titlu">
            <p>'.$nameG.'</p>
          </div>
          <div class="nota">
            <p><span class="glyphicon glyphicon-thumbs-up"></span>  '.$nota.'%</p>
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
      }else{
        echo '
        <div class="col-sm-2 jocuri-recente">
          <div class="lipseste">
            <p>...</p>
          </div>
        </div>';
      }
    }
}

function afiseaza_jocuri_sugerate(){
  $dir = $GLOBALS['dir'];
  $msql = $GLOBALS['msql'];

  echo '<div class="row recente">';

  $selectRandom = 'select * from games where status=2 order by rand() limit 6';
  $resultRandom = $msql->query($selectRandom);

  $count = $resultRandom->num_rows;

  for ($i=0; $i < 4; $i++) {
    if($count > 0){
      $row = $resultRandom->fetch_assoc();

      $style = '';

      if($row['likes']+$row['dislikes']){
        $nota = round(((100*$row['likes'])/($row['likes']+$row['dislikes'])),1);
      }else{
        $nota = 50;
      }

      $nameG = $row['name'];

      if(strlen($nameG) >= 11){
        $nameG = trim(substr($nameG, 0, 10))."...";
      }

      if($i==0){
        $style='style="margin-left: 0;"';
      }


      $img = "";

      if(file_exists("jocuri/".$row['name']."/picture.jpg")){
        $img = $dir."jocuri/".$row['name']."/picture.jpg";
      }else{
        $img = trim(file_get_contents("jocuri/".$row['name']."/picture.txt"));
      }

      echo '  <div class="col-sm-2 jocuri-recente" '.$style.' onclick="window.location.href=\''.$dir.'game/'.$row["permalink"].'\';">
          <div class="titlu">
            <p>'.$nameG.'</p>
          </div>

          <div class="nota">
            <p><span class="glyphicon glyphicon-thumbs-up"></span>  '.$nota.'%</p>
          </div>

          <<div class="media">
            <div class="playB">
              <img src="'.$dir.'imagini/play-button.png">
            </div>
            <div class="gameImg">
              <img src="'.$img.'">
            </div>
          </div>
          </div>
    ';
    }else{
      echo '
      <div class="col-sm-2 jocuri-recente">
        <div class="lipseste">
        <p>...</p>
        </div>
      </div>';
    }

    $count--;
  }

  echo '</div>';
}

function iaSistemOperare() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];;

    $os_platform  = "Unknown OS Platform";

    $os_array     = array(
                          '/windows nt 10/i'      =>  'Windows 10',
                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                          '/windows nt 6.2/i'     =>  'Windows 8',
                          '/windows nt 6.1/i'     =>  'Windows 7',
                          '/windows nt 6.0/i'     =>  'Windows Vista',
                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                          '/windows nt 5.1/i'     =>  'Windows XP',
                          '/windows xp/i'         =>  'Windows XP',
                          '/windows nt 5.0/i'     =>  'Windows 2000',
                          '/windows me/i'         =>  'Windows ME',
                          '/win98/i'              =>  'Windows 98',
                          '/win95/i'              =>  'Windows 95',
                          '/win16/i'              =>  'Windows 3.11',
                          '/macintosh|mac os x/i' =>  'Mac OS X',
                          '/mac_powerpc/i'        =>  'Mac OS 9',
                          '/linux/i'              =>  'Linux',
                          '/ubuntu/i'             =>  'Ubuntu',
                          '/iphone/i'             =>  'iPhone',
                          '/ipod/i'               =>  'iPod',
                          '/ipad/i'               =>  'iPad',
                          '/android/i'            =>  'Android',
                          '/blackberry/i'         =>  'BlackBerry',
                          '/webos/i'              =>  'Mobile'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function verifica(){
  $dir = $GLOBALS['dir'];

  $os = iaSistemOperare();

  if($os == "Android" || $os == "iPod" || $os == "iPhone" || $os == "iPad" || $os == "BlackBerry" || $os == "Mobile"){
    echo
    '<script>
      window.location.replace("'.$dir.'mobile");
    </script>';    die();
  }
}

function get_client_ip() {
    return $_COOKIE['uniq_user'];
}

function creaza_cont(){
  $dir = $GLOBALS['dir'];
  $msql = $GLOBALS['msql'];
  $auth = $GLOBALS['auth_obj'];

  $username = $_POST['Username'];
  $mail = $_POST['Email'];
  $pwd = $_POST['Password'];
  $keyword = strtolower($_POST['Keyword']);

  $result = $msql->select("players")
                 ->where("email", $mail)
                 ->get();

  if ($result->num_rows > 0) {
    alert("Email already in use.");
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

  $result = $msql->select("players")
                 ->where("username", $username)
                 ->get();

  if ($result->num_rows > 0) {
    alert("Username already in use.");
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
    $auth->login(
      $username,
      $mail,
      $pwd,
      "username",
      false
    );

    $_SESSION["username"] = $username;


    alert("Account created. You can edit your profile in My profile section.");

    if(!file_exists("Players")){
      mkdir("Players");
    }

    if(!file_exists("Players/".$username)){
      mkdir("Players/".$username);
    }

    echo '<script>
      $(document).ready(function(){
        $("#inchidere-alert").prop("onclick", null).off("click");
        $("#inchidere-alert").click(function(){
          window.location.replace("'.basename($_SERVER['PHP_SELF']).'");
        });
      });
    </script>';
  } else {
    alert("Error on database");
  }
}

function intra_in_cont(){
  $conn = $GLOBALS['conn'];
  $dir = $GLOBALS['dir'];

  $msql = $GLOBALS['msql'];
  $auth = $GLOBALS['auth_obj'];

  $username = $_POST['Username'];
  $pwd = $_POST['Password'];

  if((!ctype_alnum($username) && !filter_var($username, FILTER_VALIDATE_EMAIL)) || !ctype_alnum($pwd)){
    $GLOBALS['failedLogin']=true;
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
    $GLOBALS['login']=true;
  }else{
    $GLOBALS['failedLogin']=true;
    return;
  }
}

function schimba_parola(){
  $dir = $GLOBALS['dir'];
$msql = $GLOBALS['msql'];

  if(isset($_POST['Email'])){
    $email = $_POST['Email'];
    $keyword = strtolower($_POST['Keyword']);
    $new_pwd = $_POST['Password'];

    $conn = $GLOBALS['conn'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !ctype_alnum($keyword)){
      alert("Email or keyword incorrect.");
      echo '<script>
        $(document).ready(function(){
          $("#inchidere-alert").prop("onclick", null).off("click");
          $("#inchidere-alert").click(function(){
            window.location.replace("'.basename($_SERVER['PHP_SELF']).'");
          });
        });
      </script>';
    }else{
      $update = "update players set password='".$conn->real_escape_string($new_pwd)."'
      where email='".$conn->real_escape_string($email)."';";

      $result = $msql->select("players")
                     ->where("email", $email)
                     ->where("keyword", $keyword)
                     ->get();

      if ($result->num_rows == 1) {
        if ($msql->query($update) == TRUE) {
          alert("Password changed.");
          echo '<form style="display:none;" action="'.basename($_SERVER['PHP_SELF']).'" method="post" id="forma-ascunsa-login">
            <div class="form-group row">
              <label for="username" class="col-sm-4">Username/Email:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" value="'.$email.'" placeholder="Enter username or email" name="Username" maxlength="50" required>
              </div>
            </div>

            <div class="form-group row">
              <label for="pwd" class="col-sm-4">Password:</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" value="'.$new_pwd.'" placeholder="Enter password" name="Password" maxlength="50" required>
              </div>
            </div>
            <div class="form-group row">
              <center><a href="forgot-password.php" style="text-decoration:underline;">I forgot my password</a></center>
            </div>

            <button type="submit" class="btn btn-default center-block">Submit</button>
          </form>';

          echo '<script>
            $(document).ready(function(){
              $("#inchidere-alert").prop("onclick", null).off("click");
              $("#inchidere-alert").click(function(){
                $("#forma-ascunsa-login").submit();
              });
            });
          </script>';
        }else{
          alert("Database error: ".$conn->error);
          echo '<script>
            $(document).ready(function(){
              $("#inchidere-alert").prop("onclick", null).off("click");
              $("#inchidere-alert").click(function(){
                window.location.replace("'.basename($_SERVER['PHP_SELF']).'");
              });
            });
          </script>';
        }
      }else{
        alert("Email or keyword incorrect.");
        echo '<script>
          $(document).ready(function(){
            $("#inchidere-alert").prop("onclick", null).off("click");
            $("#inchidere-alert").click(function(){
              window.location.replace("'.basename($_SERVER['PHP_SELF']).'");
            });
          });
        </script>';
      }
    }
  }
}

function main(){

  if($GLOBALS['failedLogin']){
    alert("Username/Email or password incorrect");
  }

  if($GLOBALS['login']){
    alert("Logged in!");
    echo '<script>
      $(document).ready(function(){
        $("#inchidere-alert").prop("onclick", null).off("click");
        $("#inchidere-alert").click(function(){
          window.location.replace("'.$_SERVER['REQUEST_URI'].'");
        });
      });
    </script>';
  }

  $conn = $GLOBALS['conn'];
  $auth = $GLOBALS['auth_obj'];
  $dir = $GLOBALS['dir'];

  if(!isset($_GET['logout'])&&!isset($_GET['changepwd'])){
    if(isset($_POST['Username'])){
      if(!isset($_POST['Keyword'])){
        intra_in_cont();
      }
    }
  }

  if ($conn->connect_error) {
    alert("Connection failed: " . $conn->connect_error);
    die();
  }

}

?>
