<?php

  $w = 700;
  $h = 395;

  $res = 720;
  if(isset($_GET['res'])){
    $res = intval($_GET['res']);

    switch($res){
      case 480:
        $res=480;
        $w = 700;
        $h = 390;
        break;

      case 720:
        $res=720;
        $w = 700;
        $h = 395;
        break;

      case 1080:
        $res=1080;
        $w = 700;
        $h = 380;
        break;
      default:
        $res=720;
        $w = 700;
        $h = 395;
        break;
    }
  }

?>

<!DOCTYPE html>
<html lang="">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presentation editor - Create free presentations</title>

    <link rel="icon" href="imgs/icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="ccapture/src/download.js"></script>

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.css">

    <script type="text/javascript">
      var documentLoaded = false;

      $(document).ready(function(){
        documentLoaded = true;
      });

    </script>

    <script
      src="https://code.jquery.com/jquery-3.3.1.js"
      integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
      crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:200,400,500"/>
    <link href="dist/pickr.min.css" rel="stylesheet"/>
    <script src="p5/p5.min.js"></script>
    <script src="p5/addons/p5.dom.min.js"></script>
    <script src="p5/addons/p5.sound.min.js"></script>
    <script src="js/json_tools.js"></script>
    <script src="js/animation.js"></script>
    <script src="js/essentials.js"></script>
    <script src="js/core.js"></script>

    <link rel="stylesheet" href="perfect-scrollbar/css/perfect-scrollbar.css">
    <script type="text/javascript" src="perfect-scrollbar/dist/perfect-scrollbar.js"></script>

    <script type="text/javascript">
      var selectedColor = new rgba(66, 68, 90, 255);
      var bgColor = new rgba(0, 0, 0, 255);

      /// scroll
      var componentsm;
      var componentsd;

      $(document).ready(function(){
        $('#' + $('.dropdown-toggle').attr('dropdown-id')).hide(250);

        $('.btn-component').parent().css('display', 'flex');
        $('.btn-component').parent().css('flex-wrap', 'wrap');
        $('.btn-component').parent().css('align-items', 'center');

        $('.dropdown-toggle').click(function(){

          if($('#' + $(this).attr('dropdown-id')).css('display')=='none'){
            var elem = $(this).find('.glyphicon-triangle-right');
            elem.removeClass('glyphicon-triangle-right');
            elem.addClass('glyphicon-triangle-left');
          }else{
            var elem = $(this).find('.glyphicon-triangle-left');
            elem.removeClass('glyphicon-triangle-left');
            elem.addClass('glyphicon-triangle-right');
          }

          $('#' + $(this).attr('dropdown-id')).toggle(250);
        });

        $('#color-change-but').click(function(){
          setColorBg(bgColor);
          $('.background-change').fadeOut(250);
          $('.background-pan').fadeOut(250);
        });

        /// scrollbar

        componentsm = new PerfectScrollbar('.components-menu');
        componentsd = new PerfectScrollbar('.components-dropdown');
      });

      function start(){
        $('.engine-loading').fadeOut(100);
      }

    </script>

    <link rel="stylesheet" href="css/style.css">


    <script type="text/javascript">
      var res = <?php echo $res; ?>;

      var fps = 60;

      function awake(){
        var lw = <?php echo $w; ?>;
        var lh = <?php echo $h; ?>;

        editWidth = lw;
        editHeight = lh;
      }

      function refresh_bg_asset(){

        var loading_screen = $('.background-change .assets .loading-screen').detach();

        $('.background-change .assets').empty();
        $('.background-change .assets').append(loading_screen);

        var img_data;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if(this.responseText.trim()!=""){
                  img_data = this.responseText.split("/");
                  for (var i = 0; i < img_data.length; i++) {
                    var elem='<div class="asset">';
                        elem+='<div class="image">';
                        elem+='<img src="assets/imgs/'+img_data[i]+'">';
                        elem+='</div>';

                        elem+='<button type="button" class="btn btn-success" name="button"';
                        elem+='onclick="setBg(\'assets/imgs/'+img_data[i]+'\');'
                        elem+='$(\'.background-change\').fadeOut(250);';
                        elem+='$(\'.background-pan\').fadeOut(250);"';
                        elem+='>Use</button>';

                        elem+='<button type="button" class="btn btn-danger" name="button"';
                        elem+='onclick="$(\'.background-change .assets .loading-screen\').fadeIn(250);';
                        elem+='var xhttp = new XMLHttpRequest();';
                        elem+='xhttp.onreadystatechange = function() {';
                        elem+='if (this.readyState == 4 && this.status == 200) {';
                        elem+='$(\'.background-change .assets .loading-screen\').fadeOut(250);';
                        elem+='refresh_bg_asset();';
                        elem+='}';
                        elem+='};';
                        elem+='xhttp.open(\'POST\', \'ajax/delete_image_asset.php\', true);';
                        elem+='xhttp.setRequestHeader(\'Content-type\', \'application/x-www-form-urlencoded\');';
                        elem+='xhttp.send(\'name=\'+encodeURIComponent(\''+img_data[i]+'\'));"';

                        elem+='><span class=\'glyphicon glyphicon-trash\'></span></button>';

                        elem+='</div>';
                        $('.background-change .assets').append(elem);
                  }
                }

                $('.background-change .assets .loading-screen').fadeOut(250);
           }
        };
        xhttp.open("GET", "ajax/get_images_assets.php", true);
        xhttp.send();
      }

      function refresh_img_asset(){
        var loading_screen = $('.insert-image .assets .loading-screen').detach();

        $('.insert-image .assets').empty();
        $('.insert-image .assets').append(loading_screen);

        var img_data;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if(this.responseText.trim()!=""){
                  img_data = this.responseText.split("/");
                  for (var i = 0; i < img_data.length; i++) {

                    var img = new Image();
                    img.onload = function(){
                      var elem='<div class="asset">';
                          elem+='<div class="image">';
                          elem+='<img src="assets/imgs/'+this.img_data[this.i]+'">';
                          elem+='</div>';

                          elem+='<button type="button" class="btn btn-success" name="button"';
                          elem+='onclick="var img = new p5_obj('
                          elem+='canvas.width/2-'+(this.width/8)+',';
                          elem+='canvas.height/2-'+(this.height/8)+',';
                          elem+= this.width/4+',';
                          elem+= this.height/4+',';
                          elem+='Obj_type.IMAGE,';
                          elem+='currentLayer);';
                          elem+='img.setImgSrc(\'assets/imgs/'+this.img_data[this.i]+'\');';
                          elem+='elements[slideNumber].push(img);';
                          elem+='selectedObj=img;';
                          elem+='$(\'.insert-image\').fadeOut(250);';
                          elem+='$(\'.background-pan\').fadeOut(250);"';
                          elem+='>Add</button>';

                          elem+='<button type="button" class="btn btn-danger" name="button"';
                          elem+='onclick="$(\'.insert-image .assets .loading-screen\').fadeIn(250);';
                          elem+='var xhttp = new XMLHttpRequest();';
                          elem+='xhttp.onreadystatechange = function() {';
                          elem+='if (this.readyState == 4 && this.status == 200) {';
                          elem+='$(\'.insert-image .assets .loading-screen\').fadeOut(250);';
                          elem+='refresh_img_asset();';
                          elem+='}';
                          elem+='};';
                          elem+='xhttp.open(\'POST\', \'ajax/delete_image_asset.php\', true);';
                          elem+='xhttp.setRequestHeader(\'Content-type\', \'application/x-www-form-urlencoded\');';
                          elem+='xhttp.send(\'name=\'+encodeURIComponent(\''+this.img_data[this.i]+'\'));"';

                          elem+='><span class=\'glyphicon glyphicon-trash\'></span></button>';

                          elem+='</div>';
                          $('.insert-image .assets').append(elem);
                        };
                        img.src = 'assets/imgs/'+img_data[i];
                        img.img_data = img_data;
                        img.i = i;
                  }
                }

                $('.insert-image .assets .loading-screen').fadeOut(250);
           }
        };
        xhttp.open("GET", "ajax/get_images_assets.php", true);
        xhttp.send();
      }

      function refresh_font_asset(){
        var loading_screen = $('.insert-font .assets .loading-screen').detach();

        $('.insert-font .assets').empty();
        $('.insert-font .assets').append(loading_screen);

        var font_data;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if(this.responseText.trim()!=""){
                  font_data = this.responseText.split("/");
                  for (var i = 0; i < font_data.length; i++) {


                    var elem='<div class="asset">';
                        elem+='<div class="image">';
                        elem+='<img src="imgs/font-preview.png">';
                        elem+='</div>';
                        elem+='<style type="text/css" scoped>';
                        elem+='@font-face{';
                        elem+='font-family: \'customfont'+i+'\';';
                        elem+='src: url(\'assets/fonts/'+font_data[i]+'\'); ';
                        elem+='};';
                        elem+='</style>';
                        elem+='<p style="';
                        elem+='font-family: customfont'+i+';';
                        elem+='font-size: 25px;';
                        elem+='" class="font-preview">	&nbsp;	&nbsp;	&nbsp;Font preview</p>';
                        elem+='<button type="button" class="btn btn-success" name="button"';
                        elem+='onclick="selectedObj.setTextFont(\'assets/fonts/'+font_data[i]+'\');'
                        elem+='$(\'.insert-font\').fadeOut(250);';
                        elem+='$(\'.background-pan\').fadeOut(250);"';
                        elem+='>Use</button>';

                        elem+='<button type="button" class="btn btn-danger" name="button"';
                        elem+='onclick="$(\'.insert-font .assets .loading-screen\').fadeIn(250);';
                        elem+='var xhttp = new XMLHttpRequest();';
                        elem+='xhttp.onreadystatechange = function() {';
                        elem+='if (this.readyState == 4 && this.status == 200) {';
                        elem+='$(\'.insert-font .assets .loading-screen\').fadeOut(250);';
                        elem+='refresh_font_asset();';
                        elem+='}';
                        elem+='};';
                        elem+='xhttp.open(\'POST\', \'ajax/delete_font_asset.php\', true);';
                        elem+='xhttp.setRequestHeader(\'Content-type\', \'application/x-www-form-urlencoded\');';
                        elem+='xhttp.send(\'name=\'+encodeURIComponent(\''+font_data[i]+'\'));"';

                        elem+='><span class=\'glyphicon glyphicon-trash\'></span></button>';

                        elem+='</div>';
                        $('.insert-font .assets').append(elem);
                  }
                }

                $('.insert-font .assets .loading-screen').fadeOut(250);
           }
        };
        xhttp.open("GET", "ajax/get_fonts_assets.php", true);
        xhttp.send();
      }

      function startLoadingBG(){
        $('.background-change .loading-pan').show(250);
        $('#iframe-upload-bg').hide(250);
        $('.background-change #color-change-but').prop('disabled', true);
        $('.background-change .close-but').prop('disabled', true);
        $('.background-change .assets .loading-screen').fadeIn(250);
      }

      function startLoading(){
        $('.insert-image .loading-pan').show(250);
        $('#iframe-upload').hide(250);
        $('.insert-image .close-but').prop('disabled', true);
        $('.insert-image .assets .loading-screen').fadeIn(250);
      }

      function startLoadingFonts(){
        $('.insert-font .loading-pan').show(250);
        $('#iframe-upload-font').hide(250);
        $('.insert-font .close-but').prop('disabled', true);
        $('.insert-font .assets .loading-screen').fadeIn(250);
      }

      function error_bg_upload(message){
        $('#bg-upload-message').text(message);
      }

      function error_upload(message){
        $('#upload-message').text(message);
      }

      function error_upload_font(message){
        $('#upload-message-font').text(message);
      }

      function setup_layers(){
        clampSlideNumber();
        $('.layers-container').empty();

        for (var i = 0; i < layers[slideNumber].length; i++) {
          var name = layers[slideNumber][i].name;
          var val = layers[slideNumber][i].value;

          var visibleVal = 'false';

          if(!layers[slideNumber][i].visible){
            var visibleVal = 'true';
          }

          var class_visible = '';

          if(layers[slideNumber][i].visible){
            class_visible = 'glyphicon-eye-open';
          }else{
            class_visible = 'glyphicon-eye-close';
          }

          var class_selected = ' ';

          if(layers[slideNumber][i].value == currentLayer){
            class_selected+='selected';
          }

          var elem = '<div class="layer'+class_selected+'" onclick="if(!$(this).hasClass(\'selected\')){';
          elem+='currentLayer='+val+';';
          elem+='setup_layers();';
          elem+='prepareElements();';
          elem+='selectedObj=null;';
          elem+='refresh_properties();}';
          elem+='">';
          elem+='<span class="layer-name" style="display: inherit;">'+name+'</span>';
          elem+='<input type="text" name="" value="'+name+'" style="display:none;">';
          elem+='<button type="button" name="button" class="trash-but" onclick="';
          elem+='deleteLayer(\''+name+'\');';
          elem+='setup_layers();';
          elem+='prepareElements();';
          elem+='"><span class="glyphicon glyphicon-trash"></span></button>';
          elem+='<button type="button" name="button" onclick="';
          elem+='setLayerVisibility('+val+','+visibleVal+');';
          elem+='setup_layers();';
          elem+='prepareElements();';
          elem+='"><span class="glyphicon '+class_visible+'"></span></button>';
          elem+='</div>';

          $('.layers-container').append(elem);
        }

        $('.layers-container .layer .layer-name').click(function(){

          $(this).hide();
          var input = $(this).parent().find('input');
          input.show();
          input.focus();

          var rename_func = function(){

            $(this).hide();
            var text = $(this).parent().find('.layer-name');

            var val = $(this).val();

            var found = false;
            var layer = null;
            for (var i = 0; i < layers[slideNumber].length; i++) {
              if(layers[slideNumber][i].name == val){
                found = true;
              }
              if(layers[slideNumber][i].name == text.text()){
                layer = layers[slideNumber][i];
              }
            }

            if(!found && layer != null){
              layer.name = val;
              setup_layers();
            }

            text.show();
          };

          input.focusout(rename_func);

          input.bind("enterKey",rename_func);
          input.keyup(function(e){
              if(e.keyCode == 13)
              {
                  $(this).trigger("enterKey");
              }
          });


        });
        refresh_properties();
      }

      function setup_slides(recall = true){

        clampSlideNumber();
        if(draw){
          draw();
        }

        var add_button = $('.slides-container .add-slide').detach();

        $('.slides-container').empty();

        var jElem;

        for (var i = 0; i < elements.length; i++) {
          var current_class = ' ';
          if(i==slideNumber){
            current_class+='current-slide';
          }

          var src = "";

          if(blobImgs[i]!="" && blobImgs.length>i){
            src=blobImgs[i];
          }else{
            src='imgs/no-preview.png';
          }

          var elem = '<div class="slide'+current_class+'" onclick="';
          elem+='slideNumber='+i+';';
          elem+='refreshSlide();';
          elem+='setup_layers();';
          elem+='setup_slides();';
          elem+='$(\'.engine-loading\').fadeIn(400);';
          elem+='">';
          elem+='<p>Slide'+(i+1)+'</p>';
          elem+='<button type="button" name="button" onclick="';
          elem+='deleteSlide('+i+');';
          elem+='"><span class="glyphicon glyphicon-trash"></span></button>';
          elem+='<div class="image">';
          elem+='<img src="'+src+'">';
          elem+='</div>';
          elem+='</div>';

          if(i==slideNumber){
            jElem = $('.slides-container').append(elem);
          }else{
            $('.slides-container').append(elem);
          }
        }

        $('.slides-container').append(add_button);

        if(recall){
          if($('.p5Canvas')[0]){
            $('.p5Canvas')[0].toBlob(function(blob) {
              var newImg = jElem.find('img');
              var url = URL.createObjectURL(blob);

              newImg.onload = function() {
                // no longer need to read the blob so it's revoked
                URL.revokeObjectURL(url);
              };

              blobImgs[slideNumber]=url;
              setup_slides(false);
            });
          }
        }
        $('.engine-loading').fadeOut(250);

        $('#slide-delay').val(slideDelays[slideNumber]);
        refresh_properties();
      }

      function refresh_properties(){
        var layerName = $('.default-properties .layerName');
        var minusBut = $('.default-properties .glyphicon-minus').parent();
        var plusBut = $('.default-properties .glyphicon-plus').parent();
        var p = $('#visible-toggle').parent();

        $('.text-properties').hide();
        var textInput = $('.text-properties .text-text');
        textInput.val('');
        var fontSize = $('.text-properties #font-size');
        var minusFontSize = $('.text-properties .glyphicon-minus').parent();
        var plusFontSize = $('.text-properties .glyphicon-plus').parent();

        minusFontSize.prop("onclick", null).off("click");
        plusFontSize.prop("onclick", null).off("click");

        var fillInput = $('.fill');
        var strokeInput = $('.stroke');

        $('.rect-properties').hide();
        $('.basic-properties').hide();

        if(selectedObj && selectedObj!=null){

          /// Visibility

          var visible  = selectedObj.visible;

          $('#visible-toggle').prop('checked', visible);

          if(p.hasClass('disabled')){
            p.removeClass('disabled');
          }

          if(visible){
            if(p.hasClass('off')){
              p.removeClass('off');
            }
          }else{
            if(!p.hasClass('off')){
              p.addClass('off');
            }
          }


          /// Layers

          if(layers[slideNumber] && layers[slideNumber].length>currentLayer){
            layerName.text(layers[slideNumber][currentLayer].name);

            minusBut.prop("onclick", null).off("click");
            plusBut.prop("onclick", null).off("click");

            if(currentLayer > 0){
              minusBut.prop("disabled", false);
              minusBut.click(function(){
                selectedObj.layer--;
                currentLayer--;
                setup_layers();
                prepareElements();
              });
            }else{
              minusBut.prop("disabled", true);
            }

            if(currentLayer < layers[slideNumber].length-1){
              plusBut.prop("disabled", false);
              plusBut.click(function(){
                selectedObj.layer++;
                currentLayer++;
                setup_layers();
                prepareElements();
              });
            }else{
              plusBut.prop("disabled", true);
            }
          }

          if(selectedObj.fill){
            fillInput.prop('checked', true);
          }else{
            fillInput.prop('checked', false);
          }

          if(selectedObj.stroke){
            strokeInput.prop('checked', true);
          }else{
            strokeInput.prop('checked', false);
          }

          /// Components

          switch (selectedObj.type) {
            case Obj_type.TEXT:

              $('.text-properties').show();
              textInput.val(selectedObj.text);
              fontSize.val(parseInt(selectedObj.font_size));

              minusFontSize.click(function(){
                if(selectedObj.font_size-5 > 0){
                  selectedObj.font_size-=5;
                }
                refresh_properties();
              });

              plusFontSize.click(function(){
                selectedObj.font_size+=5;
                refresh_properties();
              });

              break;
            case Obj_type.SHAPE:
              if (selectedObj.text.toLowerCase() == "rect") {
                $('.rect-properties').show();
                break;
              }
            default:
              if(selectedObj.type!=Obj_type.IMAGE){
                $('.basic-properties').show();
              }
              break;
          }

        }else{
          /// Visibility

          if(!p.hasClass('disabled')){
            p.addClass('disabled');
          }
          if(p.hasClass('off')){
            p.removeClass('off');
          }

          /// Layers

          layerName.text("-");
          minusBut.prop("disabled", true);
          plusBut.prop("disabled", true);
        }
      }

      function startLoadingProject(){
        $('.engine-loading').fadeIn(250);
      }

      function error_upload_project(error_msg){
        $('#engine-load-tx').text(error_msg+' Refreshing...');
        $('#engine-load-img').hide(0);
        setTimeout(function(){
          window.location.href = 'home?res='+String(res);
        }, 1000);
      }

      function load_project(file_name){
        var loaded_elems = new Array();
        load(file_name, loaded_elems, function(){
          elements = loaded_elems.elements;
          layers = loaded_elems.layers;
          bg = loaded_elems.bg;
          slideDelays = loaded_elems.slideDelays;

          var xhttp = new XMLHttpRequest();
          xhttp.open("POST", "ajax/delete_processed_project_file.php", true);
          xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhttp.send("name=" + encodeURIComponent(file_name));

          slideNumber=0;
          prepareElements();
          refreshSlide();
          $('.engine-loading').fadeOut(250);
        },
      function(){
        error_upload_project('Error loading project.');
      });
      }

      $(document).ready(function(){
        $('#save-but').click(function(){
          $('.engine-loading').fadeIn(250);

          save_all(function(response){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  $('.engine-loading').fadeOut(250);
               }
            };
            xhttp.open('POST', 'ajax/delete_temp_file.php', true);
            xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhttp.send('name=' + encodeURIComponent(response));
          }, function(response){
            download(response, 'video_project.vsl');
          });
        });

        $('#60fps').css('background-color', '#06285e');
        $('#30fps').css('background-color', '#4286f4');

        $('#30fps').click(function(e){

          fps = '30';

          $(this).css('background-color', '#06285e');
          $('#60fps').css('background-color', '#4286f4');

          var event = e || window.event;
          event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);
        });

        $('#60fps').click(function(e){

          fps = '60';

          $(this).css('background-color', '#06285e');
          $('#30fps').css('background-color', '#4286f4');

          var event = e || window.event;
          event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);
        });

        refresh_bg_asset();
        refresh_img_asset();
        refresh_font_asset();
        setup_layers();
        setup_slides();
      });

    </script>


  </head>
  <body>

    <div class="engine-loading" style="display: block;">
      <div class="act-load">
        <div class="loading">
          <img src="imgs/gear.png" id="engine-load-img"><h1 id="engine-load-tx">Loading...</h1>
        </div>
      </div>
    </div>

    <div class="col-xs-6 col-xs-offset-3" id="canvas-container">
    </div>

    <div class="properties-menu">
      <div class="default-properties">
        <div class="property-header">
          Basic properties
        </div>

        <div class="property">

          <div class="checkbox">
            <label>
              <input onchange="
                if(selectedObj){
                  var checked = this.checked;

                  if(checked){
                    selectedObj.visible = true;
                  }else{
                    selectedObj.visible = false;
                  }
                }else{
                  var p = $('#visible-toggle').parent();
                  if(!p.hasClass('disabled')){
                    p.addClass('disabled');
                  }
                  if(p.hasClass('off')){
                    p.removeClass('off');
                  }
                }
              " type="checkbox" id="visible-toggle" checked data-style="ios" data-toggle="toggle" data-size="mini" data-on="Visible" data-off="Not visible">
              Visibility
            </label>
          </div>

        </div>

        <div class="property">
          <button type="button" name="button"><span class="glyphicon glyphicon-minus"></span></button>
          <p class="layerName">Layer1</p>
          <button type="button" name="button"><span class="glyphicon glyphicon-plus"></span></button>
        </div>

        <div class="property">
          <div class="form-group">
            <input onKeyUp="
              slideDelays[slideNumber] = parseInt(this.value);
            " type="number" id="slide-delay" class="form-control" value="3">
          </div>
          <p>(seconds) Timeout till next slide</p>
        </div>
      </div>



      <div class="component-properties text-properties">
        <div class="property-header">
          Text component
        </div>

        <div class="property">
          <div class="form-group" style="width:100%;">
            <label for="tx" style="display:inline-block; width:20%;">Text:</label>
            <textarea onKeyUp="
              if(selectedObj){
                selectedObj.text = this.value;
              }
            " type="text" class="form-control text-text" style="display:inline-block; width:70%;" id="tx">
          </textarea>
          </div>
        </div>

        <div class="property">
          <button type="button" name="button"><span class="glyphicon glyphicon-minus"></span></button>
          <p class="font-size">Font size:&nbsp;&nbsp;&nbsp;</p>
          <input onKeyUp="
            if(parseInt(this.value) < 0){
              this.value = '15';
            }
            if(selectedObj){
              selectedObj.font_size = parseInt(this.value);
            }
          " type="number" id="font-size" class="form-control" min="0" value="3" style="width:75px !important;">
          <button type="button" name="button"><span class="glyphicon glyphicon-plus"></span></button>
        </div>

        <div class="property">
          <button type="button" name="button" onclick="
            refresh_font_asset();
            $('.insert-font').fadeIn(250);
            $('.background-pan').fadeIn(250);
          ">Use custom font</button>
        </div>
      </div>

      <div class="component-properties text-properties">
        <div class="property-header">
          Additional styles
        </div>

        <div class="property">
          <div class="text-center">
            <button type="button" name="button" onclick="
              selectedObj.setTextAlign(0,0);
            "><span class="glyphicon glyphicon-align-left"></span></button>
            <button type="button" name="button" onclick="
            selectedObj.setTextAlign(.5,0);
            "><span class="glyphicon glyphicon-align-center"></span></button>
            <button type="button" name="button" onclick="
            selectedObj.setTextAlign(1,0);
            "><span class="glyphicon glyphicon-align-right"></span></button>
          </div>
        </div>

        <div class="property">
          <div class="form-check">
            <input type="checkbox" id="fillTx" class="fill" style="height:25px;" onchange="
              var filled = selectedObj.fill;
              selectedObj.setFill(!filled);
            " checked>
            <label class="form-check-label" for="fillTx">Fill the mesh?</label>
          </div>
        </div>

        <div class="property">
          <div class="form-check">
            <input type="checkbox" id="strokeTx" class="stroke" style="height:25px;" onchange="
              var stroked = selectedObj.stroke;
              selectedObj.setStroke(!stroked);
            " checked>
            <label class="form-check-label" for="strokeTx">Enable/disable stroke</label>
          </div>
        </div>
      </div>

      <div class="component-properties rect-properties">

        <div class="property-header">
          Rectangle properties
        </div>

        <div class="property">
          <img src="imgs/rounded-corner.png" style="width:25px; transform: rotate(-90deg);">
          <input onKeyUp="
            if(parseInt(this.value) < 0){
              this.value = 0;
            }

            var rw = parseInt(this.value);
            var rx = selectedObj.rx;
            var ry = selectedObj.ry;
            var rz = selectedObj.rz;

            selectedObj.setBorderRadius(rw,rx,ry,rz);
          " type="number" class="form-control" min="0" value="0" style="width:50px; margin-right:50px;">

          <input onKeyUp="
            if(parseInt(this.value) < 0){
              this.value = 0;
            }

            var rw = selectedObj.rw;
            var rx = parseInt(this.value);
            var ry = selectedObj.ry;
            var rz = selectedObj.rz;

            selectedObj.setBorderRadius(rw,rx,ry,rz);
          " type="number" class="form-control" min="0" value="0" style="width:50px;">
          <img src="imgs/rounded-corner.png" style="width:25px;">
        </div>

        <div class="property">
          <img src="imgs/rounded-corner.png" style="width:25px; transform: rotate(180deg);">
          <input onKeyUp="
            if(parseInt(this.value) < 0){
              this.value = 0;
            }

            var rw = selectedObj.rw;
            var rx = selectedObj.rx;
            var ry = selectedObj.ry;
            var rz = parseInt(this.value);

            selectedObj.setBorderRadius(rw,rx,ry,rz);
          " type="number" class="form-control" min="0" value="0" style="width:50px; margin-right:50px;">

          <input onKeyUp="
            if(parseInt(this.value) < 0){
              this.value = 0;
            }

            var rw = selectedObj.rw;
            var rx = selectedObj.rx;
            var ry = parseInt(this.value);
            var rz = selectedObj.rz;

            selectedObj.setBorderRadius(rw,rx,ry,rz);
          " type="number" class="form-control" min="0" value="0" style="width:50px;">
          <img src="imgs/rounded-corner.png" style="width:25px; transform: rotate(90deg);">
        </div>

        <div class="property">
          <div class="form-check" style="padding-right:10px; border-right: solid; border-color: black; border-width: 1px;">
            <input type="checkbox" id="fillRect" class="fill" style="height:25px;" onchange="
              var filled = selectedObj.fill;
              selectedObj.setFill(!filled);
            " checked>
            <label class="form-check-label" for="fillRect">Fill the mesh?</label>
          </div>

          <div class="form-check" style="padding-left:10px;">
            <input type="checkbox" id="strokeRect" class="stroke" style="height:25px;" onchange="
              var stroked = selectedObj.stroke;
              selectedObj.setStroke(!stroked);
            " checked>
            <label class="form-check-label" for="strokeRect">Enable stroke?</label>
          </div>

        </div>

      </div>

      <div class="component-properties basic-properties">

        <div class="property-header">
          Mesh properties
        </div>

        <div class="property">
          <div class="form-check">
            <input type="checkbox" id="fillBasic" class="fill" style="height:25px;" onchange="
              var filled = selectedObj.fill;
              selectedObj.setFill(!filled);
            " checked>
            <label class="form-check-label" for="fillBasic">Fill the mesh?</label>
          </div>
        </div>

        <div class="property">
          <hr style="border-color: black;">
        </div>

        <div class="property">
          <div class="form-check">
            <input type="checkbox" id="strokeBasic" class="stroke" style="height:25px;" onchange="
              var stroked = selectedObj.stroke;
              selectedObj.setStroke(!stroked);
            " checked>
            <label class="form-check-label" for="strokeBasic">Enable/disable stroke</label>
          </div>
        </div>

      </div>

    </div>

    <div class="background-pan">

    </div>

    <div class="new-proj">
      <div class="child-container">
        <div class="header">
          <h2>New project</h2>
        </div>
        <button type="button" class="close-but" name="button" onclick="
          $('.background-pan').fadeOut(250);
          $('.new-proj').fadeOut(250);
        "><span class="glyphicon glyphicon-remove"></span></button>
        <div class="form-container">
          <div class="form-group">
            <label for="res-sel">Select quality:</label>
            <select class="form-control" id="res-sel">
              <option>858 x 480 (480p)</option>
              <option selected>1280 x 720 (720p)</option>
              <option>1920 x 1080 (1080p)</option>
            </select>
          </div>

          <button type="button" class="btn center-block" onclick="
            var val = $('#res-sel').val().split('(')[1].split(')')[0].trim();
            val = val.substring(0, val.length - 1);
            var url = 'home?res='+val;
            window.location.href = url;
          ">Start creating!</button>
        </div>
      </div>
    </div>

    <div class="background-change">
      <button class="close-but" type="button" name="button"
        onclick="
        $('.background-change').fadeOut(250);
        $('.background-pan').fadeOut(250);
        "
      >
        <span class="glyphicon glyphicon-remove"></span>
      </button>
      <div class="row">
        <div class="col-xs-4">
          <h2>Image</h2>
          <br>
          <div class="assets">
            <div class="loading-screen">
              <p>Just a second, please...</p>
            </div>
          </div>
          <br>

          <center>
            <iframe id="iframe-upload-bg" src="iframes/upload-image-bg.php" width="100px" height="35px"
              onload="
                $('.background-change .loading-pan').hide(250);
                $('#iframe-upload-bg').show(250);

                $('.background-change #color-change-but').prop('disabled', false);
                $('.background-change .close-but').prop('disabled', false);
                $('.background-change .assets .loading-screen').fadeOut(250);

                refresh_bg_asset();
              "
            ></iframe>
            <div class="loading-pan">
              <p>Uploading...</p>
              <img src="imgs/load.png" alt="">
            </div>
          </center>
          <center>
            <p id="bg-upload-message"></p>
          </center>
        </div>

        <div class="col-xs-4">
          <h2>Color</h2>
          <br>
          <center>
            <div class="color-picker-bg"></div><br>
            <p>(Tap to open color picker)</p>
          </center>
          <br>
        </div>

        <div class="col-xs-4">
          <h2>Remove background</h2>
          <br>
          <button type="button" class="btn btn-danger center-block" name="button"
            onclick="
              removeBg();
              $('.background-change').fadeOut(250);
              $('.background-pan').fadeOut(250);
            "
          >Remove background</button>
          <br>
        </div>
      </div>

      <div class="row">
        <button type="button" class="btn btn-primary center-block" name="button" id="color-change-but">OK</button>
      </div>

    </div>

    <div class="insert-image">
      <button class="close-but" type="button" name="button"
        onclick="
        $('.insert-image').fadeOut(250);
        $('.background-pan').fadeOut(250);
        "
      >
        <span class="glyphicon glyphicon-remove"></span>
      </button>

      <div class="assets">
        <div class="loading-screen">
          <p>Just a second, please...</p>
        </div>
      </div>

      <center>
        <iframe id="iframe-upload" src="iframes/upload-image.php" width="100px" height="35px"
          onload="
            $('.insert-image .loading-pan').hide(250);
            $('#iframe-upload').show(250);

            $('.insert-image .close-but').prop('disabled', false);
            $('.insert-image .assets .loading-screen').fadeOut(250);

            refresh_img_asset();
          "
        ></iframe>
        <div class="loading-pan">
          <p>Uploading...</p>
          <img src="imgs/load.png" alt="">
        </div>
      </center>
      <center>
        <p id="upload-message"></p>
      </center>
    </div>

    <div class="insert-font">
      <button class="close-but" type="button" name="button"
        onclick="
        $('.insert-font').fadeOut(250);
        $('.background-pan').fadeOut(250);
        "
      >
        <span class="glyphicon glyphicon-remove"></span>
      </button>

      <div class="assets">
        <div class="loading-screen">
          <p>Just a second, please...</p>
        </div>
      </div>

      <center>
        <iframe id="iframe-upload-font" src="iframes/upload-font.php" width="100px" height="35px"
          onload="
            $('.insert-font .loading-pan').hide(250);
            $('#iframe-upload-font').show(250);

            $('.insert-font .close-but').prop('disabled', false);
            $('.insert-font .assets .loading-screen').fadeOut(250);

            refresh_font_asset();
          "
        ></iframe>
        <div class="loading-pan">
          <p>Uploading...</p>
          <img src="imgs/load.png" alt="">
        </div>
      </center>
      <center>
        <p id="upload-message-font"></p>
      </center>
    </div>

    <div class="components-menu">
      <button type="button" name="button" class="btn btn-dark btn-component"
        onclick='
          var c = new rgba(255,
          255,
          255,
          255
          );

          var shape = new p5_obj(
            canvas.width/2-150,
            canvas.height/2-15,
            300,
            50,
            Obj_type.TEXT,
            currentLayer,
            c,
            "New text"
          );
          elements[slideNumber].push(shape);


          selectedObj=shape;

          $("#shapes-dropdown").hide(250);

          var elem = $("#dropdown-shapes").find(".glyphicon-triangle-left");
          elem.removeClass("glyphicon-triangle-left");
          elem.addClass("glyphicon-triangle-right");'
        >
        <span class="glyphicon glyphicon-font"></span>
        <br><br>
        <p>Insert text</p>
      </button>

      <button type="button" name="button" id="dropdown-shapes" class="btn btn-dark btn-component dropdown-toggle" dropdown-id="shapes-dropdown">
        <span class="glyphicon glyphicon-asterisk"></span>
        <br><br>
        <p>Shapes</p>
        <span class="glyphicon glyphicon-triangle-right dropdown-arrow"></span>
      </button>

      <button type="button" name="button" class="btn btn-dark btn-component"
      onclick="
        refresh_img_asset();
        $('.insert-image').fadeIn(250);
        $('.background-pan').fadeIn(250);
      "
      >
        <span class="glyphicon glyphicon-picture"></span>
        <br><br>
        <p>Insert image</p>
      </button>

      <button type="button" name="button" class="btn btn-dark btn-component"
        onclick="
          refresh_bg_asset();
          $('.background-change').fadeIn(250);
          $('.background-pan').fadeIn(250);
        "
      >
        <span class="glyphicon glyphicon-file"></span>
        <br><br>
        <p>Change background</p>
      </button>

    </div>

    <div class="components-dropdown" id="shapes-dropdown">

      <button type="button" name="button" class="btn btn-dark btn-component"
        onclick='
          var c = new rgba(selectedColor.r,
          selectedColor.g,
          selectedColor.b,
          selectedColor.a
          );

          var shape = new p5_obj(
            canvas.width/2-50,
            canvas.height/2-50,
            100,
            100,
            Obj_type.SHAPE,
            currentLayer,
            c,
            "rect"
          );
          elements[slideNumber].push(shape);

          selectedObj=shape;

          $("#shapes-dropdown").hide(250);

          var elem = $("#dropdown-shapes").find(".glyphicon-triangle-left");
          elem.removeClass("glyphicon-triangle-left");
          elem.addClass("glyphicon-triangle-right");
        '>
          <span class="glyphicon glyphicon-stop"></span>
          <br><br>
          <p>Insert rectangle</p>
      </button>

      <button type="button" name="button" class="btn btn-dark btn-component"
        onclick='
          var c = new rgba(selectedColor.r,
          selectedColor.g,
          selectedColor.b,
          selectedColor.a
          );

          var shape = new p5_obj(
            canvas.width/2,
            canvas.height/2,
            50,
            50,
            Obj_type.SHAPE,
            currentLayer,
            c,
            "circle"
          );
          elements[slideNumber].push(shape);

          selectedObj=shape;

          $("#shapes-dropdown").hide(250);

          var elem = $("#dropdown-shapes").find(".glyphicon-triangle-left");
          elem.removeClass("glyphicon-triangle-left");
          elem.addClass("glyphicon-triangle-right");
        '>
          <i class="fa fa-circle"></i>
          <br><br>
          <p>Insert circle</p>
      </button>

      <button type="button" name="button" class="btn btn-dark btn-component"
        onclick='
        var c = new rgba(selectedColor.r,
        selectedColor.g,
        selectedColor.b,
        selectedColor.a
        );

          var shape = new p5_obj(
            canvas.width/2,
            canvas.height/2,
            100,
            50,
            Obj_type.SHAPE,
            currentLayer,
            c,
            "ellipse"
          );
          elements[slideNumber].push(shape);

          selectedObj=shape;

          $("#shapes-dropdown").hide(250);

          var elem = $("#dropdown-shapes").find(".glyphicon-triangle-left");
          elem.removeClass("glyphicon-triangle-left");
          elem.addClass("glyphicon-triangle-right");
        '>
          <i class="fa fa-circle" style="transform: scale(1.7, 1);"></i>
          <br><br>
          <p>Insert ellipse</p>
      </button>

    </div>

    <div class="layers-menu">
      <div class="layer-color">
        <center>
          <p>Change color</p>
          <div class="color-picker"></div>
          <p>(Tap to open color picker)</p>
        </center>
        <hr>
      </div>

      <div class="layers">
        <div class="layers-heading">
          <br>
          <h3>Slider1</h3>
          <br>
        </div>

        <hr>

        <div class="layers-container">
        </div>

        <div class="layer-options">
          <button type="button" name="button" onclick="
              var name = 'Layer';
              var maxLayer = -1;

              for (var i = 0; i < layers[slideNumber].length; i++) {
                if(layers[slideNumber][i].value>=maxLayer){
                  maxLayer = layers[slideNumber][i].value;
                }
              }
              maxLayer++;
              name+=maxLayer;

              var count=0;

              while(layers[slideNumber].find(obj => {
                return obj.name == name
              })){
                name+=' ('+count+')';
                count++;
              }
              var l = new Layer(maxLayer, name);
              layers[slideNumber].push(l);
              setup_layers();

              redoActions = new Array();
              undoAction = new Array();

            "><span class="glyphicon glyphicon-plus" ></span>Add layer</button>
        </div>

      </div>
    </div>

    <div class="options-menu">

      <div class="dropdown">
        <button class="dropdown-toggle" type="button" data-toggle="dropdown">File
        </button>

        <ul class="dropdown-menu">
          <li><a href="#" onclick="
              $('.background-pan').fadeIn(250);
              $('.new-proj').fadeIn(250);
            ">New</a></li>
          <li><a href="#" id="save-but">Save</a></li>
          <li>
            <iframe id="iframe-upload-project" src="iframes/upload-project.php" width="200px" height="25px"></iframe></li>
          <li><a href="#" onclick="
              $('.engine-loading').fadeIn(250);
              save_all(function(path){
                window.open('presenter.php?id='+encodeURIComponent(path) + '&res='+String(res));
                $('.engine-loading').fadeOut(250);
              });

            ">Play presentation</a></li>
          <li><a href="#" onclick="
              $('.engine-loading').fadeIn(250);
              save_all(function(path){
                window.open('presenter.php?id='+encodeURIComponent(path) + '&rec=true' + '&res='+String(res) + '&fps=' + fps);
                $('.engine-loading').fadeOut(250);
              });

            ">Record + Setup video</a></li>
          <hr>
          <li>
            <center>
              <div class="fps-sett">
                <button type="button" name="button" id="30fps">30 FPS</button>
                <button type="button" name="button" id="60fps">60 FPS</button>
              </div>
            </center>
          </li>
          <hr>
          <li><a href="#">Back to dashboard</a></li>
        </ul>
      </div>

    </div>

    <div class="slides-menu">
      <div class="slides-container">

        <div class="slide add-slide" onclick="
          $('.engine-loading').fadeIn(250);
          currentLayer=1;
          selectedObj=null;
          redoActions = new Array();
          undoActions = new Array();
          elements.push(new Array());
          layers.push(new Array());
          bg.push(new rgba(200,200,200,255));
          blobImgs.push('');
          slideDelays.push(3);
          slideNumber=elements.length-1;
          setup_slides();
          setup_layers();
          slidescont.update();
        ">
          <div class="image">
            <span class="glyphicon glyphicon-plus-sign"></span>
          </div>
          <p>Add a new slide</p>
        </div>

      </div>
    </div>


    <script src="dist/pickr.min.js"></script>
    <script type="text/javascript">
      const pickr = new Pickr({
          el: '.color-picker',

          onChange(hsva, instance) {
            selectedColor.r = hsva.toRGBA()[0];
            selectedColor.g = hsva.toRGBA()[1];
            selectedColor.b = hsva.toRGBA()[2];
            selectedColor.a = hsva.toRGBA()[3]*255;

            $('.layer-color .pcr-button').css('background', 'rgba('
             +selectedColor.r +','
             + selectedColor.g  +', '
             + selectedColor.b  +', '
             + (selectedColor.a/255)+')');

            if(selectedObj){
              selectedObj.color =
              new rgba(selectedColor.r,
                      selectedColor.g,
                      selectedColor.b,
                      selectedColor.a
                      );
            }
          },

          default: '#42445A',
          position: 'middle',

          swatches: [
              '#F44336',
              '#E91E63',
              '#9C27B0',
              '#673AB7',
              '#3F51B5',
              '#2196F3',
              '#03A9F4',
              '#00BCD4',
              '#009688',
              '#4CAF50',
              '#8BC34A',
              '#CDDC39',
              '#FFEB3B',
              '#FFC107'
          ],

          components: {

              preview: true,
              opacity: true,
              hue: true,

              interaction: {
                  hex: true,
                  rgba: true,
                  hsva: false,
                  input: true,
                  clear: false,
                  save: false
              }
          }
      });

      const pickr_bg = new Pickr({
          el: '.color-picker-bg',

          onChange(hsva, instance) {
              bgColor.r = hsva.toRGBA()[0];
              bgColor.g = hsva.toRGBA()[1];
              bgColor.b = hsva.toRGBA()[2];
              bgColor.a = hsva.toRGBA()[3]*255;

              $('.background-change .pcr-button').css('background', 'rgba('
               +bgColor.r +','
               + bgColor.g  +', '
               + bgColor.b  +', '
               + (bgColor.a/255)+')');
          },

          default: '#42445A',
          position: 'middle',

          swatches: [
              '#F44336',
              '#E91E63',
              '#9C27B0',
              '#673AB7',
              '#3F51B5',
              '#2196F3',
              '#03A9F4',
              '#00BCD4',
              '#009688',
              '#4CAF50',
              '#8BC34A',
              '#CDDC39',
              '#FFEB3B',
              '#FFC107'
          ],

          components: {

              preview: true,
              opacity: true,
              hue: true,

              interaction: {
                  hex: true,
                  rgba: true,
                  hsva: false,
                  input: true,
                  clear: false,
                  save: false
              }
          }
      });

    </script>
  </body>
</html>
