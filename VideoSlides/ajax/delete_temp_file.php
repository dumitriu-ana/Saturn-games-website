<?php
  $name = $_POST['name'];

  chdir("../temp");

  unlink($name);
?>
