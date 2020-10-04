<?php

  chdir("../assets/imgs");

  $imgs = glob("*.{jpg,png,gif,jpeg}", GLOB_BRACE);

  $imgs_data = implode("/", $imgs);

  echo $imgs_data;

?>
