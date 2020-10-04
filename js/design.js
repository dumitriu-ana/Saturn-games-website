$(document).ready(function(){
  $(".jocuri .media .playB").hide(200);
  $(".jocuri-recente .media .playB").hide(200);

  $(".jocuri .media").mouseenter(
    function(){
      $(this).children(".playB").show(75);
    }
  );

  $(".jocuri .media").mouseleave(
    function(){
      $(this).children(".playB").hide(75);
    }
  );

  $(".jocuri-recente .media").mouseenter(
    function(){
      $(this).children(".playB").show(75);
    }
  );

  $(".jocuri-recente .media").mouseleave(
    function(){
      $(this).children(".playB").hide(75);
    }
  );

  $(".btn-inchidere").click(
    function(){
      var login_pan = $(this).parent();
      var cover_pan = login_pan.parent();
      login_pan.hide(250);
      cover_pan.fadeOut(250);
    }
  );

  $("#register-but").click(
    function(){
      $(".cont-pan").fadeIn(250);
      $(".register").show(250);
    }
  );

  $("#login-but").click(
    function(){
      $(".cont-pan").fadeIn(250);
      $(".login").show(250);
    }
  );

  $("#register-but-form").click(
    function(){
      var ok = true;
      var message = "";

      var inputs = $(".register input");

      var pwd = $(".register input[name='Password']").val();
      var confirm_pwd = $(".register input[name='confirm-pwd']").val();

      var email = $(".register input[name='Email']").val();

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
        $(this).parent()[0].submit();
      }else{
        alert_b(message);
      }
    }
  );

  $('.selectpicker').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    if($(this).children().eq(clickedIndex).html() != "Category"){
      window.location.replace("/?categ="+$(this).children().eq(clickedIndex).html().toString());
    }
  });

    if(window.innerWidth > 1000)
     {
    	// fade in .navbar
    	$(function () {
    		$(window).scroll(function () {
    			if ($(this).scrollTop() > 100) {
    				$('.navbar-inverse').css("background-color", "rgba(200,200,200,.3)");
            $('.nav a').css("background-color", "rgba(100,100,100,.3)");
    			} else {
    				$('.navbar-inverse').css("background-color", "#222222");
            $('.nav a').css("background-color", "#000000");

    			}
    		});
    	});
    }else{
      $('.navbar-inverse').css("background-color", "#222222");
      $('.nav a').css("background-color", "#000000");
    }

});

function alert_b(message){
  $(".alert-pan").fadeIn(250);
  $(".alert-pan .pan").fadeIn(250);
  $(".alert-pan .pan h2").text(message);
}

function alphanumeric_text(txt)
{
  var letterNumber = /^(?=.*[A-Z0-9])[\w.,!"'\/$ ]+$/i;
  if(txt.match(letterNumber))
  {
   return true;
  }
  else
  {
   return false;
  }
}

function alphanumeric(txt)
{
  var letterNumber = /^[0-9a-zA-Z]+$/;
  if(txt.match(letterNumber))
  {
   return true;
  }
  else
  {
   return false;
  }
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
