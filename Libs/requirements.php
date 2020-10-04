<?php

  require 'database/db.php';
  require 'login_register/auth.php';
  require 'tools/secure_tools.php';
  require 'error_handler/error_handler.php';

  $dir = substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT']));
  $dir = str_replace('\\', '/', $dir);
  if($dir[strlen($dir) - 1] != '/'){
    $dir = $dir.'/';
  }
?>
