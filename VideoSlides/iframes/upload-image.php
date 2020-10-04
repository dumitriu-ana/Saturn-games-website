<?php
  require "../resources.php";
  use secure_tools\base_tools as tools;


  if(isset($_FILES['uploaded-file'])){

    $uniq_id = (string)uniqid(mt_rand(), true);
    $uniq_id=$uniq_id.get_now().((string)round(microtime(true) * 1000));
    $uniq_id = strtolower($uniq_id);
    $uniq_id = preg_replace("/[^a-z0-9_\s-]/", "", $uniq_id);
    $uniq_id = preg_replace("/[\s-]+/", " ", $uniq_id);
    $uniq_id = preg_replace("/[\s_]/", "-", $uniq_id);

    $ext = pathinfo($_FILES['uploaded-file']['name'], PATHINFO_EXTENSION);

    $path = "../assets/imgs/".$uniq_id.".".strtolower($ext);

    $uploaded = 1;

    try{
      tools::upload(
        'uploaded-file',
        $path,
        array('jpg', 'png', 'jpeg'),
        10
      );
    }catch(Exception $e){
      echo "<script>
        if(parent.error_upload){
          parent.error_upload('".$e->getMessage()."');
        }
      </script>";
      $uploaded = 0;

    }

    if($uploaded==1){
      list($width, $height) = getimagesize($path);

      if($width>=1000 || $height>=700){
        $newX;
        $newY;
        if($width>=$height){
          $newX = 700;
          $newY = (700*$height)/$width;
        }else{
          $newY = 700;
          $newX = (700*$width)/$height;
        }

        $layer = resize_image($path, $newX, $newY);

        switch($ext){
            case "png":
              imagepng($layer, $path);
            break;
            case "jpeg":
            case "jpg":
                imagejpeg($layer, $path);
            break;
            case "gif":
              imagegif($layer, $path);
            break;
            default:
            break;
        }
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Upload image for background</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <script
      src="https://code.jquery.com/jquery-3.3.1.js"
      integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
      crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:200,400,500"/>

    <style media="screen">
    html,body{
      overflow:hidden;
      background-color: rgba(255, 0, 0, 0);
    }

    .btn-secondary{
      background-color: #333333;
      color: white;

      border: solid;
      border-width: 1px;
      border-color: #0f0f0f;

      transition: all .3s;

      position: fixed;
      margin: auto;

      top: 0;
      left: 0;
      right: 0;
      bottom: 0;

      display: flex;
      align-items: center;
      justify-content: center;

      font-size: 12vw;

    }

    .btn-secondary:hover{
      color: white;
      background-color: #1c1c1c;
      transition: all .3s;
    }

    .btn-secondary .buttonText{
      display: none;
    }

    .btn-secondary:after{
      content: 'Upload an asset' !important;
    }
    </style>

  </head>
  <body>
    <center>
      <form method="post" enctype="multipart/form-data" id="upload-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="file">
          <input type="file" name="uploaded-file" class="filestyle" data-classButton="btn btn-success" data-input="false" data-classIcon="icon-plus" data-buttonText="Your label here."
            id="upload-asset" accept="image/*"
            onchange="
              if(parent.startLoading){
                parent.startLoading();
              }
              $('#upload-form').submit();
            "
          >
        </div>
      </form>
    </center>
  </body>
</html>
