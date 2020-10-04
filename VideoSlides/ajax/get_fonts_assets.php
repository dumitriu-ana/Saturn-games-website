<?php

  chdir("../assets/fonts");

  $fonts = glob("*.{ttf,otf,font}", GLOB_BRACE);

  $fonts_data = implode("/", $fonts);

  echo $fonts_data;

?>
