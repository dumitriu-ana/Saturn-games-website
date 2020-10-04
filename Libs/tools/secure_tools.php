<?php namespace secure_tools;
class base_tools
{

  public static function upload(
    $file_name,
    $save_path,
    array $acceptedExtensions = array(),
    $memoryLimit = 0
  ){

    $allRight = 1;
    $exceptionMessage = "";

    if(isset($_FILES[$file_name]["tmp_name"])){
      $name = $_FILES[$file_name]["name"];

      $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

      if (count($acceptedExtensions)>0 && !in_array($ext, $acceptedExtensions)){
        $allRight = 0;
        $exceptionMessage = "Extension not correct. The file extension should be: ".implode(", ",$acceptedExtensions);
      }

      $size=$_FILES[$file_name]["size"];

      if($memoryLimit > 0 && $size > $memoryLimit*1000000){
        $allRight = 0;
        $exceptionMessage = "The file is too big. The file size limit is: ".$memoryLimit."MB.";
      }

      if (file_exists($save_path)) {
        $allRight = 0;
        $exceptionMessage = "The file already exists in the save path: ".$save_path;
      }

      if($allRight==1){
        if(move_uploaded_file($_FILES[$file_name]["tmp_name"], $save_path)){
          return 1;
        }else{
          $allRight = 0;
          $exceptionMessage = "Error saving file. Please, try again.";
        }
      }

      if($allRight==0){
        throw new \Exception($exceptionMessage);
        return 0;
      }
    }else{
      throw new \Exception('File not set.');
      return 0;
    }
  }

  public static function upload_img(
    $file_name,
    $save_path,
    array $acceptedExtensions = array('png','jpg', 'jpeg'),
    $memoryLimit = 0
  ){
    $allRight = 1;
    $exceptionMessage = "";

    if(isset($_FILES[$file_name]["tmp_name"])){
        $is_img = 0;

        try{
          if(base_tools::verif_img(
              $file_name
            )==1){
              $is_img = 1;
            }else{
              $is_img = 0;
            }
          }catch(Exception $e){
            $is_img = 0;
          }

          if($is_img==1){
            try{
              if(base_tools::upload(
                $file_name,
                $save_path,
                $acceptedExtensions,
                $memoryLimit
              )!=1){
                $allRight = 0;
                $exceptionMessage = "Unknown error";
              }
            }catch(Exception $e){
              $allRight = 0;
              $exceptionMessage = explode("in",$e)[0];
            }
          }else{
            $allRight = 0;
            $exceptionMessage = "The file is not an image.";
          }

          if($allRight==0){
            throw new \Exception($exceptionMessage);
            return 0;
          }

          return 1;

      }else{
        throw new \Exception('File not set.');
        return 0;
      }
  }

  public static function verif_img(
    $file_name
  ){
    if(isset($_FILES[$file_name]["tmp_name"])){
      $check = getimagesize($_FILES[$file_name]["tmp_name"]);
      if($check !== false) {
          return 1;
      } else {
          return 0;
      }
    }else{
      throw new \Exception('File not set.');
      return 0;
    }
  }

  public static function to_jpg(
    $filePath,
    $quality = 50
  ){

    $exts = array('jpg', 'png', 'jpeg');

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    if(in_array($ext, $exts))
    {
      $image = imagecreatefrompng($filePath);
      $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
      imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
      imagealphablending($bg, TRUE);
      imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
      imagedestroy($image);
      imagejpeg($bg, str_replace('.'.$ext, '.jpg',$filePath), $quality);
      imagedestroy($bg);
      unlink($filePath);
    }else{
      throw new \Exception('File is not an image.');
      return 0;
    }
  }

  public static function to_png(
    $filePath
  ){

    $exts = array('jpg', 'png', 'jpeg');

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    if(in_array($ext, $exts))
    {
      imagepng(imagecreatefromstring(file_get_contents($filePath)), str_replace('.'.$ext, '.png', $filePath));
      unlink($filePath);
    }else{
      throw new \Exception('File is not an image.');
      return 0;
    }
  }


  public static function format_exception(
    $exception
  ){
    return explode("Exception: ", explode("in",$exception)[0])[1];
  }

}


?>
