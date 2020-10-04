<?php
  require "resurse.php";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Administrator</title>

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
            <h2>Administrator</h2>
            <p>Here you can view messages, submissions and earnings of the developers.</p>
            <div class="line">
              <br><br><br>
            </div>

            <h1>Adding games to TA Saturn Games</h1>
            <br>
            <h2>1. Upload in minutes</h2>
            <p>You only need your game files and a screenshot.</p>

            <br>

            <h2>2. Reach an audience of thousands</h2>
            <p>Monthly, <a href="../index.php"><b style="color:#10ba07;">TA Saturn Games</b></a> has an audience of over thousands unique visitors.</p>

            <br>

            <h2>3. Earn revenue based on the game's success</h2>
            <p>We share all revenue with developers. See the <a href="#faq">FAQ</a> for details.</p>
          </div>

          <br><br>

          <div id="faq">
            <h2>FAQ</h2>
            <br><br>
            <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Why do I need an administrator page?</a>
                  </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                  <div class="panel-body">On this page you can check messages sent by user, view submissions, check reports of users and a lot more.
                  With this page, you can manage the website very quickly without messing up with the database by yourself.</div>
              </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Can I post games from here?</a>
                  </h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse in">
                  <div class="panel-body">Yes, you can post games from administrator page, but keep in mind that they will not pe rewarded
                  as developers' games. All of the earnings will go to Hacknet INC. property.</div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">How can I contact the team, if I need?</a>
                </h4>
              </div>
              <div id="collapse3" class="panel-collapse collapse in">
                <div class="panel-body">You can get in contact with our team by email: <a href="mailto:saturn.games.inc@gmai.com">saturn.games.inc@gmail.com</a>
                </div>
              </div>
            </div>
          </div>

        <?php
          afiseaza_drepturi_admin();
        ?>
    </div>


  </body>
</html>
