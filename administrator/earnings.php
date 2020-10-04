<?php
  require "resurse.php";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Submit</title>
<meta name="robots" content="noindex, nofollow">
    <?php
      include_dependente_admin();
    ?>
  </head>
  <body>
    <?php
      include_login();
    ?>
    <div class="wrapper">
      <?php
        afiseaza_bara_stanga_admin();
      ?>

      <div id="content">

        <?php
          afiseaza_bara_sus_admin();
        ?>

        <div class="inceput">
          <p>Timeframe:</p>
          <select class="selectpicker form-control" name="gameType">
            <option data-content="<span class='badge badge-success'>Today</span>">Today</option>
            <option data-content="<span class='badge badge-success'>Yesterday</span>">Yesterday</option>
            <option data-content="<span class='badge badge-success'>This month</span>">This month</option>
            <option data-content="<span class='badge badge-success'>All time</span>">All time</option>
          </select>
          <br><br>

          <table class="table" style="background-color:white; color:black;">
            <thead>
              <tr>
                <th scope="col">Developer</th>
                <th scope="col">Plays</th>
                <th scope="col">Revenue</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>No developer yet</td>
                <td>No developer yet</td>
                <td>No developer yet</td>
              </tr>

              <tr>
                <th scope="row">Total</th>
                <td>0</td>
                <td>0</td>
              </tr>
            </tbody>
          </table>
          <br>
          <div class="container-fluid" style="background-color: white; border-radius:3px;">
            <p style="color:#000;">Note that the Plays stats are recent plays only, so they might be inaccurate for the longer-ranging periods.
            All stats are provided in EUR in Europe/Brussels timezone. The stats are not definite. The Developer Portal is in Beta, and we reserve the right to revise any data seen here.
            </p>
          </div>
        </div>

        <?php
          afiseaza_drepturi_admin();
        ?>
    </div>
  </div>

  </body>
</html>
