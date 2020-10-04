String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

function save_elements(){

  var returnVal = '{ELEMENTS}[[\n';

  var tempElem = null;
  for (var slide = 0; slide < elements.length; slide++) {

    returnVal+='{SLIDE}\n';
    for (var i = 0; i < elements[slide].length; i++) {

      tempElem = elements[slide][i];

      var centerX = 0.5;
      var centerY = 1;

      switch (tempElem.alignX) {
        case LEFT:
          centerX = 0;
          break;
        case CENTER:
          centerX = 0.5;
          break;
        case RIGHT:
          centerX = 1;
          break;
        default:
          break;
      }

      switch (tempElem.alignY) {
        case BOTTOM:
          centerY = 1;
          break;
        case CENTER:
          centerY = 0.5;
          break;
        case TOP:
          centerY = 0;
          break;
        default:
          break;
      }

      var elem = '({\n';

      elem+='"type":'+ parseInt(tempElem.type) + '\n';
      elem+='"text":"'+ tempElem.text + '"\n';
      elem+='"x":'+ parseInt(tempElem.x) + '\n';
      elem+='"y":'+ parseInt(tempElem.y) + '\n';
      elem+='"size_x":'+ parseInt(tempElem.sizeX) + '\n';
      elem+='"size_y":'+ parseInt(tempElem.sizeY) + '\n';
      elem+='"color":('
      + parseInt(tempElem.color.r) + ','
      + parseInt(tempElem.color.g) + ','
      + parseInt(tempElem.color.b) + ','
      + parseInt(tempElem.color.a) +')\n';
      elem+='"layer":'+ parseInt(tempElem.layer) + '\n';
      elem+='"font_path":"'+ tempElem.fontPath + '"\n';
      elem+='"font_size":'+ parseInt(tempElem.font_size) + '\n';
      elem+='"img_src":"'+ tempElem.src + '"\n';
      elem+='"rw":'+ parseInt(tempElem.rw) + '\n';
      elem+='"rx":'+ parseInt(tempElem.rx) + '\n';
      elem+='"ry":'+ parseInt(tempElem.ry) + '\n';
      elem+='"rz":'+ parseInt(tempElem.rz) + '\n';
      elem+='"alignX":"'+ centerX + '"\n';
      elem+='"alignY":"'+ centerY + '"\n';
      elem+='"stroke":'+ ((tempElem.stroke)? '1' : '0') + '\n';
      elem+='"fill":'+ ((tempElem.fill)? '1' : '0') + '\n';
      elem+='"visible":'+ ((tempElem.visible)? '1' : '0');

      elem+='\n})';

      if(i<elements[slide].length-1){
        elem+=',';
      }

      elem+='\n';

      returnVal+=elem;
    }
    returnVal+='{SLIDE}\n';
  }

  returnVal+=']]{ELEMENTS}';

  return returnVal;
}

function save_backgrounds(){
  var returnVal = "[[\n";

  for (var slide = 0; slide < elements.length; slide++) {
    var elem = '({\n';

    if (bg[slide] == null) {
      background(200,200,200);
      elem+='"color":(200,200,200,255)';

    } else {
      if (!(bg[slide] instanceof rgba)) {
        tint(255, 255, 255, 255);
        elem+='"src":"'+bg[slide].src+'"';
      } else {
        background(bg[slide].r, bg[slide].g, bg[slide].b);
        elem+='"color":"('
        + parseInt(bg[slide].r) + ','
        + parseInt(bg[slide].g) + ','
        + parseInt(bg[slide].b) + ','
        + parseInt(bg[slide].a) +')"';
      }
    }

    elem+='\n})\n';
    returnVal+=elem;
  }

  returnVal+=']]';

  return '{BACKGROUNDS}'+returnVal+'{BACKGROUNDS}';
}

function save_layers(){
  return '{LAYERS}' + JSON.stringify(layers, null, 4) + '{LAYERS}';
}

function save_delays(){
  return '{DELAYS}'+JSON.stringify(slideDelays, null, 4)+'{DELAYS}';
}

function save_all(cbk, downbk){
  var saved_vals = save_elements()+'\n'+save_layers()+'\n'+save_backgrounds()+'\n'+save_delays();

  if(typeof downbk === "function"){
    downbk(saved_vals);
  }

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          cbk(this.responseText);
     }
  };
  xhttp.open("POST", "ajax/save_project_server.php?temp=true", false);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("data="+encodeURIComponent(saved_vals.replaceAll('\n', '%0A')));
}

function load(file_name, return_data, onload, onerror){

  var saved_string = '';

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(this.responseText.trim() != "error"){
          saved_string = this.responseText;

          var lastIndexElems = saved_string.lastIndexOf('{ELEMENTS}');
          var elementsString = saved_string.substr(0, lastIndexElems);
          elementsString = elementsString.replace('{ELEMENTS}', '');

          var elementsArray = elementsString.split('\n');

          var inSlide = false;
          var localCurrentSlide = -1;

          var recoveredElements = new Array();

          //for (var i = 1; i < elementsArray.length-1; i++)

          for (var i = 1; i < elementsArray.length-1; i++) {
              elementsArray[i] = elementsArray[i].trim();

              if(elementsArray[i]=='{SLIDE}'){
                if(inSlide==false){
                  inSlide=true;
                  localCurrentSlide++;
                  recoveredElements.push(new Array());
                  i++;
                }else{
                  inSlide=false;
                  continue;
                }
              }

              if(elementsArray[i]=='{SLIDE}'){
                inSlide=false;
                continue;
              }

              i++;

              var typeInt = parseInt(elementsArray[i].replace('"type":','').trim());

              var type = null;

              if(typeInt==1){
                type=Obj_type.TEXT;
              }else if(typeInt==2){
                type=Obj_type.SHAPE;
              }else if(typeInt==3){
                type=Obj_type.IMAGE;
              }
              i++;

              var text = elementsArray[i].replace('"text":"','').trim().substring(0, elementsArray[i].replace('"text":"','').trim().length - 1);

              i++;

              var x = parseInt(elementsArray[i].replace('"x":','').trim());

              i++;
              var y = parseInt(elementsArray[i].replace('"y":','').trim());

              i++;

              var sizeX = parseInt(elementsArray[i].replace('"size_x":','').trim());
              i++;
              var sizeY = parseInt(elementsArray[i].replace('"size_y":','').trim());

              i++;

              var colorArray = elementsArray[i].replace('"color":(','').trim().substring(0, elementsArray[i].replace('"color":(','').trim().length - 1).split(',');

              var color = new rgba(parseFloat(colorArray[0]),parseFloat(colorArray[1]),parseFloat(colorArray[2]),parseFloat(colorArray[3]));

              i++;

              var layer = parseInt(elementsArray[i].replace('"layer":','').trim());

              i++;

              var font_path = elementsArray[i].replace('"font_path":"','').trim().substring(0, elementsArray[i].replace('"font_path":"','').trim().length - 1);

              i++;

              var font_size = parseFloat(elementsArray[i].replace('"font_size":','').trim());

              i++;

              var img_src = elementsArray[i].replace('"img_src":"','').trim().substring(0, elementsArray[i].replace('"img_src":"','').trim().length - 1);

              i++;

              var rw = parseFloat(elementsArray[i].replace('"rw":','').trim());

              i++;

              var rx = parseFloat(elementsArray[i].replace('"rx":','').trim());

              i++;

              var ry = parseFloat(elementsArray[i].replace('"ry":','').trim());

              i++;

              var rz = parseFloat(elementsArray[i].replace('"rz":','').trim());

              i++;

              var alignX = parseFloat(elementsArray[i].replace('"alignX":"','').substring(0, elementsArray[i].replace('"alignX":"','').length - 1).trim());
              i++;

              var alignY = parseFloat(elementsArray[i].replace('"alignY":"','').substring(0, elementsArray[i].replace('"alignY":"','').length - 1).trim());
              i++;

              var stroke = parseInt(elementsArray[i].replace('"stroke":','').trim());

              i++;

              var fill = parseInt(elementsArray[i].replace('"fill":','').trim());

              i++;

              var visible = parseInt(elementsArray[i].replace('"visible":','').trim());

              i++;

              var elem = new p5_obj(
                x,
                y,
                sizeX,
                sizeY,
                type,
                layer,
                color,
                text
              );

              elem.setVisibility(visible);
              elem.setFill(fill);
              elem.setStroke(stroke);

              if(type==1){
                elem.setTextFont(font_path);
                elem.setFontSize(font_size);
                elem.setTextAlign(alignX, alignY);
              }else if(type==2){
                elem.setBorderRadius(rw,rx,ry,rz);
              }else if(type==3){
                elem.setImgSrc(img_src);
              }

              recoveredElements[localCurrentSlide].push(elem);

          }

          saved_string = saved_string.replace(elementsString, '').trim();
          saved_string = saved_string.replace('{ELEMENTS}{ELEMENTS}', '').trim();

          var lastIndexLayers = saved_string.lastIndexOf('{LAYERS}');
          var layersString = saved_string.substr(0, lastIndexLayers);
          layersString = layersString.replace('{LAYERS}', '');

          var layersArray = layersString.split('\n');

          localCurrentSlide = -1;

          var recoveredLayers = new Array();

          for (var i = 1; i < layersArray.length-1; i++) {
              layersArray[i] = layersArray[i].trim();

              if(layersArray[i]=='['){
                localCurrentSlide++;
                recoveredLayers.push(new Array());
                i++;
              }else if(layersArray[i]=='],' || layersArray[i]==']'){
                continue;
              }else if(layersArray[i]=='[],' || layersArray[i]=='[]')
              {
                recoveredLayers.push(new Array());
                continue;
              }

              i++;
              layersArray[i] = layersArray[i].trim();

              var value = parseInt(layersArray[i].replace('"value": ','').trim().substring(0, layersArray[i].replace('"value": ','').trim().length - 1));

              i++;
              layersArray[i] = layersArray[i].trim();

              var name = layersArray[i].replace('"name": "','').trim().substring(0, layersArray[i].replace('"name": "','').trim().length - 2);

              i++;
              layersArray[i] = layersArray[i].trim();

              var visToggle = layersArray[i].replace('"visible": ','').trim();

              var visible = 0;

              if(visToggle=='true' || visToggle=='1'){
                visible = 1;
              }

              i++;

              var layer = new Layer(
                value,
                name
              );

              layer.visible = visible;
              recoveredLayers[localCurrentSlide].push(layer);
          }

          localCurrentSlide = -1;

          saved_string = saved_string.replace(layersString, '').trim();
          saved_string = saved_string.replace('{LAYERS}{LAYERS}', '').trim();

          var lastIndexBackgrounds = saved_string.lastIndexOf('{BACKGROUNDS}');
          var backgroundsString = saved_string.substr(0, lastIndexBackgrounds);
          backgroundsString = backgroundsString.replace('{BACKGROUNDS}', '');

          var backgroundsArray = backgroundsString.split('\n');

          var recoveredBackgrounds = new Array();

          for (var i = 1; i < backgroundsArray.length-1; i++) {
            backgroundsArray[i] = backgroundsArray[i].trim();

            i++;

            var argument = backgroundsArray[i].split('"')[1].split('"')[0].trim();

            var lBg = null;

            if(argument=='src'){
              var src = backgroundsArray[i].replace('"src":"', '');
              src = src.substring(0, src.length - 1).trim();
              lBg = new BG(src);

              lBg.img = loadImage(lBg.src);
            }else{
              var colArray = backgroundsArray[i].replace('"color":"(', '');
              colArray = colArray.substring(0, colArray.length - 1).trim();
              colArray = colArray.split(',');
              lBg = new rgba(
                parseFloat(colArray[0]),
                parseFloat(colArray[1]),
                parseFloat(colArray[2]),
                parseFloat(colArray[3])
              );
            }

            recoveredBackgrounds.push(lBg);
            i++;
          }


          saved_string = saved_string.replace(backgroundsString, '').trim();
          saved_string = saved_string.replace('{BACKGROUNDS}{BACKGROUNDS}', '').trim();

          var lastIndexDelays = saved_string.lastIndexOf('{DELAYS}');
          var delaysString = saved_string.substr(0, lastIndexDelays);
          delaysString = delaysString.replace('{DELAYS}', '');

          var delaysArray = delaysString.split('\n');

          var recoveredDelays = new Array();

          for (var i = 1; i < delaysArray.length-1; i++) {
            delaysArray[i] = delaysArray[i].trim();

            var delay = 3;

            if(i==delaysArray.length-2){
              delay = parseInt(delaysArray[i]);
            }else{
              delay = parseInt(delaysArray[i].substring(0, delaysArray[i].length - 1));
            }
            recoveredDelays.push(delay);
          }

          return_data.elements = recoveredElements;
          return_data.layers = recoveredLayers;
          return_data.bg = recoveredBackgrounds;
          return_data.slideDelays = recoveredDelays;

          onload();
        }else{
          onerror();
        }
     }
  };
  xhttp.open("POST", "ajax/get_project_server.php?temp=true", false);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("file_name="+encodeURIComponent(file_name));

}
