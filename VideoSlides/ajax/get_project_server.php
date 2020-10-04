<?php

  $file_name = $_POST['file_name'];
  $temp = isset($_GET['temp']);

  if($temp){
    chdir('../temp');
  }else{
    chdir('../projects');
  }
  if (file_exists($file_name)) {
    echo file_get_contents($file_name);
  }else{
    echo "error";
  }
?>
