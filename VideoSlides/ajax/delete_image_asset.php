<?php
  $name = $_POST['name'];

  chdir("../assets/imgs");

  unlink($name);

?>
