<?php

  require "../resources.php";

  chdir('../temp');

  $temp = isset($_GET['temp']);
  $data = $_POST['data'];

  $data = str_replace('%0A', "\n", $data);

  $uniq_id = (string)uniqid(mt_rand(), true);
  $uniq_id=$uniq_id.get_now().((string)round(microtime(true) * 1000));
  $uniq_id = strtolower($uniq_id);
  $uniq_id = preg_replace("/[^a-z0-9_\s-]/", "", $uniq_id);
  $uniq_id = preg_replace("/[\s-]+/", " ", $uniq_id);
  $uniq_id = preg_replace("/[\s_]/", "-", $uniq_id);

  $file_name = $uniq_id.".vsl";

  $temp_file = fopen($file_name, "w");
  fwrite($temp_file, $data);
  fclose($temp_file);

  echo $file_name;

?>
