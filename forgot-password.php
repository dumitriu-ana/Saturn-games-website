<?php
  require "resurse.php";
  verifica();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Change password</title>

    <?php
      include_dependente();
    ?>

    <script type="text/javascript">
    $(document).ready(function(){
      $("#change-pwd-but").click(
        function(){
          var ok = true;
          var message = "";

          var inputs = $(".contactus input");

          var pwd = $(".contactus input[name='Password']").val();
          var confirm_pwd = $(".contactus input[name='confirm-pwd']").val();

          var email = $(".contactus input[name='Email']").val();

          var filledIn = true;

          $(inputs.get().reverse()).each(function() {
            if($(this).attr("type") == "text" || $(this).attr("type") == "password"){
              if($(this).val() == ""){
                message = "All fields must be filled in.";
                filledIn = false;
                ok = false;
                return;
              }else if($(this).attr("name") != "Email" && !alphanumeric($(this).val())){
                message = "You are allowed to use only letters and numbers.";
                ok = false;
                return;
              }else if($(this).val().length < 7 && $(this).attr("name") != "Keyword" && $(this).attr("name") != "Email" && $(this).attr("name") != "confirm-pwd"){
                message = $(this).attr("name") + " is to short.";
                ok = false;
                return;
              }
            }
          });

          if(filledIn){
            if(!validateEmail(email)){
              message = "You must give us a valid email.";
              ok = false;
            }else if(pwd != confirm_pwd){
              message = "Password field and confirm password filed must match.";
              ok = false;
            }
          }

          if(ok){
            $(this).parent().parent()[0].submit();
          }else{
            alert_b(message);
          }
        }
      );
    });

    </script>

  </head>
  <body>

    <?php
      afiseaza_bara();
    ?>

    <?php
      if(isset($_SESSION['username'])){
        echo '<script>
          window.location.replace("index.php");
        </script>';
      }
    ?>

    <br>
    <div class="container-fluid">
      <div class="col-lg-8 col-lg-offset-2">
        <h2 style="color:white;"><center>Change your password</center></h2>
          <form class="contactus" action="index.php?changepwd=true" method="post" autocomplete="off">
            <div class="form-group">
              <label for="email">Your email:</label>
              <input type="text" class="form-control" name="Email" id="email" autocomplete="off" required>
            </div><br>
            <div class="form-group">
              <label for="keyword">Your security keyword:</label>
              <input type="text" class="form-control" name="Keyword" id="keyword" required>
            </div><br>
            <div class="form-group">
              <label  for="pwd">New password:</label>
              <input type="password" class="form-control" name="Password" id="pwd" required>
            </div><br>
            <div class="form-group">
              <label  for="confirm-pwd">Confirm password:</label>
              <input type="password" class="form-control" name="confirm-pwd" id="confirm-pwd" required>
            </div>
            <center>
              <button type="button" class="btn" id="change-pwd-but">Change</button>
            </center>
          </form>
      </div>
    </div>


    <?php
      afiseaza_drepturi();
    ?>

  </body>
</html>
