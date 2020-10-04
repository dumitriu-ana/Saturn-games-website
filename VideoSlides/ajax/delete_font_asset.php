<?php
  $name = $_POST['name'];

  chdir("../assets/fonts");

  unlink($name);

?>
