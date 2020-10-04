<?php
  if(!isset($_GET['id'])){
    echo '<script>window.location.replace("home");</script>';
  }

  $w = 1280;
  $h = 720;

  $res = 720;
  if(isset($_GET['res'])){
    $res = intval($_GET['res']);

    switch($res){
      case 480:
        $res=480;
        $w = 858;
        $h = 480;
        break;

      case 720:
        $res=720;
        $w = 1280;
        $h = 720;
        break;

      case 1080:
        $res=1080;
        $w = 1980;
        $h = 1080;
        break;
      default:
        $res=720;
        $w = 1280;
        $h = 720;
        break;
    }
  }

  $fps = 60;

  if(isset($_GET['fps'])){
    $fps=intval($_GET['fps']);
  }

  $url = $_SERVER['REQUEST_URI']; //returns the current URL
  $parts = explode('/',$url);
  $dir = '';
  for ($i = 1; $i < count($parts) - 1; $i++) {
   $dir .= $parts[$i];
   if($i<count($parts) - 2){
     $dir .= "/";
   }
  }

  if(trim($dir) == '/'){
    $dir="";
  }else{
    $dir=$dir.'/';
  }

  $protocol = "http";

  if( isset($_SERVER['HTTPS'] ) ) {
    $protocol = "https";
  }

  $host = $protocol."://".$_SERVER['HTTP_HOST']."/".$dir;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Presenter - Present your project free on VideoSlides</title>

    <link rel="icon" href="imgs/icon.png">

    <script type="text/javascript">
      var presenter = true;
    </script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script>
    <script defer src="p5/p5.min.js"></script>
    <script defer src="p5/addons/p5.dom.min.js"></script>
    <script defer src="p5/addons/p5.sound.min.js"></script>

    <script src="ccapture/build/CCapture.all.min.js"></script>
    <script src="ccapture/src/webm-writer-0.2.0.js"></script>
    <script src="ccapture/src/download.js"></script>

    <script defer src="js/essentials.js"></script>
    <script defer src="js/core.js"></script>
    <script defer src="js/animation.js"></script>
    <script defer src="js/json_tools.js"></script>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style_presenter.css">


    <script type="text/javascript">


      var capturer = null;

      var file_name = '<?php echo $_GET['id']; ?>';

      var res = <?php echo $res; ?>;

      var fps = <?php echo $fps; ?>;

      function awake(){
        var lw = <?php echo $w; ?>;
        var lh = <?php echo $h; ?>;

        presWidth = lw;
        presHeight = lh;
      }

      function start(){
        <?php

          if(isset($_GET['rec'])){
            echo 'recording = true;';
          }else{
            echo 'recording = false;';
          }

        ?>

        if(recording){
          capturer = new CCapture( {
            format: 'webm',
            framerate: fps,
            verbose: true,
          } );
        }

        var data = {};
        load(file_name, data, function(){
          elements = data.elements;
          layers = data.layers;
          bg = data.bg;
          slideDelays = data.slideDelays;

          for (var i = 0; i < elements.length; i++) {
            for (var j = 0; j < elements[i].length; j++) {

              elements[i][j].x = (elements[i][j].x*presWidth)/editWidth;
              elements[i][j].sizeX = (elements[i][j].sizeX*presWidth)/editWidth;

              elements[i][j].y = (elements[i][j].y*presHeight)/editHeight;
              elements[i][j].sizeY = (elements[i][j].sizeY*presHeight)/editHeight;

              if(elements[i][j].type == Obj_type.TEXT){
                elements[i][j].font_size = (elements[i][j].font_size*presHeight)/editHeight;
              }

              switch (elements[i][j].type) {
                case Obj_type.TEXT:
                  var randAnim = Math.floor((Math.random() * 100) + 1);

                  if(randAnim>50){
                    setAnimation(elements[i][j],upAppear, 10);
                  }else{
                    setAnimation(elements[i][j],downAppear, 10);
                  }
                  break;
                case Obj_type.SHAPE:
                  setAnimation(elements[i][j],fadeIn, 10);
                  break;

                case Obj_type.IMAGE:
                  setAnimation(elements[i][j],popAppear, 30);
                  break;
                default:
              }
            }
          }

          prepareElements();
          refreshSlide();

          if(recording){
            $('.engine-loading').css("display", "flex");
          }

          var xhttp = new XMLHttpRequest();
          xhttp.open("POST", "ajax/delete_temp_file.php", true);
          xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhttp.send("name=" + encodeURIComponent(file_name));


        }, function(){
          location.replace("home");
        });

        if(recording){
          capturer.start();
        }
      }
    </script>

    <style media="screen">
      .engine-loading .download-div{
        display: none;
      }
    </style>

  </head>
  <body>
    <div class="engine-loading">
      <div class="act-load">

        <div class="load-div">
          <div class="loading">
            <img src="imgs/gear.png" id='gear-img'><h1 id="record-tx">Recording...</h1>
          </div>
          <br>
          <div class="download-div">
            <div class="progress" style="display:none;">
              <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <button id="donwload-but" style="width: 100%;" class="btn btn-success " host="<?php echo $host; ?>">Start Downloading</button>
            <a id="donwload-a" style="width: 100%; display:none;" class="btn btn-success" href="<?php echo $host; ?>" download>Download file</a>

          </div>
        </div>

      </div>
    </div>

    <div class="col-xs-12" id="canvas-container">
    </div>
  </body>
</html>
