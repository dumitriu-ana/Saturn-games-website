<?php

function show_dep(){
  $dir = $GLOBALS['dir'];
  echo '
  <link rel="stylesheet" href="'.$dir.'css/style.css">

  <script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

  <script>
    $(document).ready(function(){
      $(\'[data-toggle="tooltip"]\').tooltip();
    });
  </script>
  ';
}

function fatal_handler() {
    $errfile = "unknown file";
    $errstr  = "shutdown";
    $errno   = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();

    if( $error !== NULL) {
        $errno   = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr  = $error["message"];

        customErrorHandler($errno, $errstr, $errfile, $errline);
    }
}

function customErrorHandler($errno, $errstr, $errfile, $errline) {
    show_dep();

    echo '
    <div class="error-container">
      <div class="error-nav">
        <b>PHP error:</b> <span class="err-no">['.$errno.']</span> File: '.$errfile.':
        <span class="err-no">'.$errline.'</span>
      </div>

      <div class="col-md-8 col-md-offset-2 col-xs-12 code-preview">
        <table class="table">
          <tbody>
    ';

    $line_idx = 1;
    $found = false;

    if($errfile!="unknown file"){
      $handle = fopen($errfile, "r");
      if ($handle) {
          $found = true;
          while (($line = fgets($handle)) !== false) {
              // process the line read.
              echo '
              <tr>
                <th scope="row">'.$line_idx.'</th>
                <td>';

                if($line_idx == $errline){
                  echo '<a href="#" data-toggle="tooltip" data-placement="top" title="'.$errstr.'">';
                }

                echo '<span class="';
                if($line_idx == $errline){
                  echo 'error';
                }else{
                  echo 'no-error';
                }

                echo '">'.htmlspecialchars($line);

                echo '
                  </span>';

                  if($line_idx == $errline){
                    echo '</a>';
                  }

                  echo '
                </td>
              </tr>
              ';

              $line_idx++;
          }

          fclose($handle);
      } else {
          // error opening the file.
          echo '
          <tr>
            <th scope="row">&#8734;</th>
            <td>Error opening the file: '.$errstr.'</td>
          </tr>
          ';
      }
    }else{
      // error opening the file.
      echo '
      <tr>
        <th scope="row"><span style="font-family: sans-serif !important;">&#8734;</span></th>
        <td><span class="no-error">Fatal error: '.$errstr.' at line '.$errline.'</span></td>
      </tr>
      ';
    }
    echo '
          </tbody>
        </table>
      </div>
    </div>';

    if($found){
      echo '
      <script>
      $(function() {
        $(\'.code-preview\').animate({
          scrollTop: $(".error").offset().top-300
        }, 500);
      });
      </script>
      ';
    }
}

?>
