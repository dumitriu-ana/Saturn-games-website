<?php
  $name = $_POST['name'];

  chdir("../projects");

  unlink($name);
?>
