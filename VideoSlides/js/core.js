class AnchorPoint {
  constructor(x, y, type) {
    this.x = x;
    this.y = y;
    this.type = type;
  }
}

class Layer {
  constructor(value, name) {
    this.value = value;
    this.name = name;
    this.visible = true;
  }
}

class BG {
  constructor(src) {
    this.src = src;
  }
}

var undoActions = new Array();
var redoActions = new Array();

class Action {
  constructor(name, undone = false) {
    if (!undone) {
      redoActions = new Array();
    }
    this.name = name;
  }
}

var recording = false;

var editWidth = 700;
var editHeight = 400;

var presWidth = 1280;
var presHeight = 720;

var slideTimeout = null;

var presenting = false;

if (typeof presenter !== "undefined") {
  presenting = true;
}

var elements = new Array(
  new Array()
);
var layers = new Array(
  new Array()
);

var blobImgs = new Array('');

var layersVisibility = new Array();

var bg = new Array(new rgba(200, 200, 200, 255));
var slideDelays = [3];

var anchorPoints = new Array();

var slideNumber = 0;
var lastSlideNumber = slideNumber;
var slidesCount = 1;

var noLoopProcesses = 0;

var moveAction;
var saveMoveAction = false;

var resizeAction;
var saveResizeAction = false;

var selectedObj = null;
var selectedAnchorPoint = null;
var selectedObjOffX = 0;
var selectedObjOffY = 0;

var pressState = 0;
var elementCountBySlide = 0;

var ctrlPressed = false;
var unReDid = false;

var currentLayer = 1;
var globalCanvas;

var started = false;

var ended = false;

Math.clamp = function(a, b, c) {
  return Math.max(b, Math.min(c, a));
}

Array.prototype.remove = function() {
  var what, a = arguments,
    L = a.length,
    ax;
  while (L && this.length) {
    what = a[--L];
    while ((ax = this.indexOf(what)) !== -1) {
      this.splice(ax, 1);
    }
  }
  return this;
};

Array.prototype.removeOnce = function() {
  var what, a = arguments,
    L = a.length,
    ax;
  while (L && this.length) {
    what = a[--L];
    while ((ax = this.indexOf(what)) !== -1) {
      this.splice(ax, 1);
      return this;
    }
  }
};

Array.prototype.insert = function(index, item) {
  this.splice(index, 0, item);
};

Array.prototype.clone = function() {
  return this.slice(0);
};

function quick_Sort(origArray) {
  if (origArray.length <= 1) {
    return origArray;
  } else {

    var left = [];
    var right = [];
    var newArray = [];
    var pivot = origArray.pop();
    var length = origArray.length;

    for (var i = 0; i < length; i++) {
      if (origArray[i].layer <= pivot.layer) {
        left.push(origArray[i]);
      } else {
        right.push(origArray[i]);
      }
    }

    return newArray.concat(quick_Sort(left), pivot, quick_Sort(right));
  }
}

function prepareElements() {
  var elementsClone = _.clone(elements[slideNumber]);

  elements[slideNumber] = quick_Sort(elementsClone);

  elementCountBySlide = elements[slideNumber].length;

  var lastLayer = -1;

  layersVisibility = new Array();

  if (layers[slideNumber].length == 0) {
    for (var i = 0; i < elements[slideNumber].length; i++) {
      if (layersVisibility.length < elements[slideNumber][i].layer) {
        for (var j = lastLayer + 1; j <= elements[slideNumber][i].layer; j++) {
          layersVisibility.push(1);
          layers[slideNumber].push(new Layer(j, "Layer" + (j).toString()));
        }

        lastLayer = elements[slideNumber][i].layer;
      }
    }
  } else {
    var elementFound = null;

    for (var i = 0; i < elements[slideNumber].length; i++) {
      if (layers[slideNumber].filter(obj => {
          return obj.value == elements[slideNumber][i].layer;
        }).length == 0) {
        elementFound = elements[slideNumber][i];
        break;
      }
    }

    var layerMax = 0;

    if (elementFound != null) {
      var layersCountered = 0;
      for (var i = 0; i < layers[slideNumber].length; i++) {
        if (layers[slideNumber][i].value < elementFound.layer) {
          layersCountered++;
        } else {
          break;
        }
      }

      var layerToAdd = new Layer(elementFound.layer,
        "Layer" + (elementFound.layer).toString());

      layers[slideNumber].insert(layersCountered, layerToAdd);
    }


    for (var i = 0; i < layers[slideNumber].length; i++) {
      if (layers[slideNumber][i].value > layerMax) {
        layerMax = layers[slideNumber][i].value;
      }
    }


    for (var i = 0; i <= layerMax; i++) {
      var l = layers[slideNumber].find(obj => {
        return obj.value == i;
      });

      if (l != null) {
        if (l.visible == 1) {
          layersVisibility.push(1);
        } else {
          layersVisibility.push(0);
        }
      } else {
        layersVisibility.push(0);
      }
    }
  }

  for (var i = 0; i < layers[slideNumber].length - 1; i++) {
    for (var j = i + 1; j < layers[slideNumber].length; j++) {
      if (layers[slideNumber][i].value > layers[slideNumber][j].value) {
        var aux = layers[slideNumber][i];
        layers[slideNumber][i] = layers[slideNumber][j];
        layers[slideNumber][j] = aux;
      }
    }
  }

  if (!presenting) {
    if (typeof setup_layers === "function") {
      setup_layers();
    }
  }
}

function setLayerVisibility(layer, visible) {
  for (var i = 0; i < layers[slideNumber].length; i++) {
    if (layers[slideNumber][i].value == layer) {
      layers[slideNumber][i].visible = visible;

      selectedObj = null;

      return;
    }
  }
}

function getLayerVisibility(layer) {
  for (var i = 0; i < elements[slideNumber].length; i++) {
    if (elements[slideNumber][i].layer == layer) {
      return elements[slideNumber][i].visible;
    }
  }
}

function deleteLayer(layer) {

  var layerObj = null;

  if (typeof layer == "string") {
    for (var i = 0; i < layers[slideNumber].length; i++) {
      if (layers[slideNumber][i].name == layer) {
        layerObj = layers[slideNumber][i];
        break;
      }
    }
  } else if (typeof layer == "number") {
    for (var i = 0; i < layers[slideNumber].length; i++) {
      if (layers[slideNumber][i].value == layer) {
        layerObj = layers[slideNumber][i];
        break;
      }
    }
  } else {
    return false;
  }

  var removedElements = new Array();
  var removedElementsToCopy = new Array();

  for (var i = 0; i < elements[slideNumber].length; i++) {
    if (elements[slideNumber][i].layer == layerObj.value) {
      removedElements.push(elements[slideNumber][i]);
    }
  }

  for (var i = 0; i < removedElements.length; i++) {
    removedElementsToCopy.push(_.clone(removedElements[i]));
    elements[slideNumber].remove(removedElements[i]);
  }

  var action = new Action("DELETED LAYER");
  action.deletedElements = removedElementsToCopy;
  action.deletedLayer = _.clone(layerObj);
  undoActions.push(action);

  layers[slideNumber].remove(layerObj);
  prepareElements();
  selectedObj = null;
}

function deleteSlide(slide) {
  if (elements.length <= 1) {
    return;
  }

  if (slide == slideNumber) {
    lastSlideNumber = 0;
  }

  if (slideNumber > slide) {
    lastSlideNumber = slideNumber - 1;
  }

  if (slideNumber < slide) {
    lastSlideNumber = slideNumber;
  }

  var action = new Action("DELETED SLIDE");
  action.deletedSlide = _.clone(elements[slide]);
  action.deletedLayers = _.clone(layers[slide]);
  action.deletedBackground = _.clone(bg[slide]);
  action.deletedBlob = _.clone(blobImgs[slide]);
  action.deletedDelay = _.clone(slideDelays[slide]);
  action.position = slide;

  undoActions.push(action);

  elements.removeOnce(elements[slide]);
  layers.removeOnce(layers[slide]);
  bg.removeOnce(bg[slide]);
  blobImgs.removeOnce(blobImgs[slide]);
  slideDelays.removeOnce(slideDelays[slide]);

  if (typeof setup_slides === "function") {
    setup_slides();
  }

  if (typeof setup_layers === "function") {
    setup_layers();
  }
}

function handleElement(elem, invisible = false) {

  if (elem.stroke == 0) {
    noStroke();
  } else {
    strokeWeight(2);
    stroke(0);
  }

  if (elem.fill == 0) {
    noFill();
  } else {
    if (!invisible) {
      fill(elem.color.r, elem.color.g, elem.color.b, elem.color.a);
    } else {
      fill(75, 75, 75, 200);
    }
  }

  if (elem.type == Obj_type.TEXT) {
    textAlign(elem.alignX, elem.alignY);
    textSize(elem.font_size);
    textFont(elem.font);
    text(
      elem.text,
      elem.x,
      elem.y,
      elem.sizeX,
      elem.sizeY
    );
  } else if (elem.type == Obj_type.SHAPE) {
    if (elem.text.toLowerCase() == "circle") {

      if (pressState == 0) {
        elem.sizeY = elem.sizeX;
      }

      circle(
        elem.x,
        elem.y,
        elem.sizeX,
      );
    } else if (elem.text.toLowerCase() == "ellipse") {
      ellipse(
        elem.x,
        elem.y,
        elem.sizeX * 2,
        elem.sizeY * 2
      );
    } else if (elem.text.toLowerCase() == "rect") {
      rect(
        elem.x,
        elem.y,
        elem.sizeX,
        elem.sizeY,
        elem.rw,
        elem.rx,
        elem.ry,
        elem.rz
      );
    }
  } else if (elem.type == Obj_type.IMAGE) {
    if (elem.img) {
      if (!invisible) {
        tint(elem.color.r, elem.color.g, elem.color.b, elem.color.a);
      } else {
        tint(75, 75, 75, 150);
      }
      image(elem.img, elem.x, elem.y, elem.sizeX, elem.sizeY);
    } else {
      console.log("No image assigned");
    }
  }

  if (elem.animation && presenting) {
    elem.animation.loopAnimation();
  }

}

function setAnimation(obj, anim, speed = anim.speed) {
  var animClone = new Animation(anim.looping, anim.speed);
  animClone.startAnimation = anim.startAnimation;
  animClone.loopAnimation = anim.loopAnimation;
  animClone.endAnimation = anim.endAnimation;
  animClone.eraseProcess = anim.eraseProcess;
  animClone.speed = speed;
  animClone.setupObj(obj);
}

function clampSlideNumber() {
  if (slideNumber > elements.length - 1) {
    slideNumber = elements.length - 1;
  }

  if (slideNumber < 0) {
    slideNumber = 0;
  }
}

function resumeSlide() {
  if (lastSlideNumber != -1) {
    slideNumber = lastSlideNumber;
    lastSlideNumber = -1;
  }
}

function refreshSlide() {
  resumeSlide();
  clampSlideNumber();

  selectedObj = null;
  noLoopProcesses = 0;

  for (var i = 0; i < elements[slideNumber].length; i++) {

    elements[slideNumber][i].canvas = canvas;
    if (elements[slideNumber][i].animation && presenting) {
      if (!elements[slideNumber][i].animation.looping) {
        noLoopProcesses++;

        elements[slideNumber][i].animation.eraseProcess = function() {
          noLoopProcesses--;
        }
      }
      elements[slideNumber][i].animation.startAnimation();
    }
  }

  if (typeof setup_slides === "function") {
    setup_slides();
  }

  if (typeof setup_layers === "function") {
    setup_layers();
  }
}

function removeBg() {
  bg[slideNumber] = new rgba(200, 200, 200, 255);

  if (typeof setup_slides === "function") {
    setup_slides();
  }
}

function setBg(path) {

  bg[slideNumber] = new BG(path);

  bg[slideNumber].img = loadImage(bg[slideNumber].src,
    img => {
      console.log("successful loaded img");
      if (typeof setup_slides === "function") {
        setTimeout(function() {
          setup_slides();
        }, .5);
      }
    },
    img => {
      console.log("failed loaded img");
    });
}

function setColorBg(color) {
  bg[slideNumber] = new rgba(color.r, color.g, color.b, color.a);

  if (typeof setup_slides === "function") {
    setup_slides();
  }
}

function setup() {
  // put setup code here
  frameRate(60);

  if (typeof awake === "function") {
    awake();
  }

  var w = editWidth;
  var h = editHeight;

  if (presenting) {
    w = presWidth;
    h = presHeight;
  }



  var canvas = createCanvas(w, h);

  var canvasElement = $('canvas').detach();
  globalCanvas = $('#canvas-container').append(canvasElement);

  refreshSlide();
  prepareElements();

}

function draw() {

  if (presenting) {
    if (!started) {
      started = true;
      start();
    }
  }

  if (elementCountBySlide != elements[slideNumber].length) {
    prepareElements();
    refreshSlide();

    if (typeof setup_layers === "function") {
      setup_layers();
    }
  }

  while (undoActions.length > 15) {
    undoActions.shift();
  }

  while (redoActions.length > 15) {
    redoActions.shift();
  }

  if (presenting) {
    if (noLoopProcesses == 0) {
      if (slideNumber < elements.length - 1) {
        if (typeof slideTimeout === "undefined" || slideTimeout == null) {
          slideTimeout = setTimeout(function() {
            slideTimeout = null;
            slideNumber++;
            prepareElements();
            refreshSlide();
            draw();
          }, slideDelays[slideNumber] * 1000);
        }
      } else {
        if (!ended) {
          ended = true;

          slideTimeout = setTimeout(function() {
            if (recording) {
              $('#record-tx').text('Saving video');
              capturer.stop();
              capturer.save();
              $('#gear-img').remove();
              $('#record-tx').text('Done saving');
            } else {
              $('#gear-img').remove();
              $('#record-tx').text('Done presenting');
              $('.engine-loading').fadeIn(250);
            }
          }, slideDelays[slideNumber] * 1000);
        }
      }
    }
  }

  drawBackground();
  drawElementsByLayer();
  input();

  if (presenting && recording) {
    capturer.capture(canvas);
  }
}

function detectHover(hoveredClick = false) {

  // funcdetect

  if (presenting) {
    return null;
  }

  var locX;
  var locY;
  var locSizeX;
  var locSizeY;

  if (hoveredClick) {
    for (var i = 0; i < anchorPoints.length; i++) {
      locX = anchorPoints[i].x - 13;
      locY = anchorPoints[i].y - 13;
      locSizeX = 26;
      locSizeY = 26;

      fill(120, 120, 120);
      rect(
        locX,
        locY,
        locSizeX,
        locSizeY
      );

      if (
        locX <= mouseX &&
        locX + locSizeX >= mouseX &&
        locY <= mouseY &&
        locY + locSizeY >= mouseY
      ) {
        selectedAnchorPoint = anchorPoints[i];
        return true;
      }
    }
  }

  for (var i = elements[slideNumber].length - 1; i >= 0; i--) {

    locX = elements[slideNumber][i].x;
    locY = elements[slideNumber][i].y;
    var locSizeX = elements[slideNumber][i].sizeX;
    var locSizeY = elements[slideNumber][i].sizeY;

    if (elements[slideNumber][i].type == Obj_type.SHAPE) {
      if (elements[slideNumber][i].text.toLowerCase() == "circle" || elements[slideNumber][i].text.toLowerCase() == "ellipse") {
        locX = elements[slideNumber][i].x - elements[slideNumber][i].sizeX;
        locY = elements[slideNumber][i].y - elements[slideNumber][i].sizeY;
        locSizeX = elements[slideNumber][i].sizeX * 2;
        locSizeY = elements[slideNumber][i].sizeY * 2;
      }
    }

    if (
      locX <= mouseX &&
      locX + locSizeX >= mouseX &&
      locY <= mouseY &&
      locY + locSizeY >= mouseY
    ) {
      if (hoveredClick) {
        selectedObjOffX = mouseX - elements[slideNumber][i].x;
        selectedObjOffY = mouseY - elements[slideNumber][i].y;
      }
      if (layersVisibility[elements[slideNumber][i].layer]) {
        return elements[slideNumber][i];
      }
    }
  }

  return null;
}

function undo() {
  if (presenting) {
    return;
  }

  var lastAction = undoActions[undoActions.length - 1];

  if (lastAction == null) {
    return;
  }

  if (lastAction.name == "DELETED ELEMENT") {
    var actionElement = new Action("ADDED ELEMENT", true);
    elements[slideNumber].push(lastAction.deletedElement);

    var undoneElement = elements[slideNumber][elements[slideNumber].indexOf(lastAction.deletedElement)];
    actionElement.addedElement = undoneElement;
    redoActions.push(actionElement);

    prepareElements();

    selectedObj = undoneElement;
  } else if (lastAction.name == "ADDED ELEMENT") {
    var actionElement = new Action("DELETED ELEMENT", true);

    if (selectedObj == lastAction.addedElement) {
      selectedObj = null;
    }

    var removedElement = _.clone(lastAction.addedElement);

    elements[slideNumber].remove(lastAction.addedElement);

    actionElement.deletedElement = removedElement;

    redoActions.push(actionElement);
  } else if (lastAction.name == "MOVE ELEMENT") {
    moveAction = new Action("MOVE ELEMENT", true);
    moveAction.element = lastAction.element;
    moveAction.x = lastAction.element.x;
    moveAction.y = lastAction.element.y;

    lastAction.element.x = lastAction.x;
    lastAction.element.y = lastAction.y;

    redoActions.push(moveAction);
  } else if (lastAction.name == "DELETED LAYER") {

    var action = new Action("ADDED LAYER", true);

    for (var i = 0; i < lastAction.deletedElements.length; i++) {
      elements[slideNumber].push(lastAction.deletedElements[i]);
    }

    layers[slideNumber].push(lastAction.deletedLayer);

    action.addedLayer = lastAction.deletedLayer;

    redoActions.push(action);

    prepareElements();
  } else if (lastAction.name == "ADDED LAYER") {
    var layer = lastAction.addedLayer.value;
    var layerObj = null;

    for (var i = 0; i < layers[slideNumber].length; i++) {
      if (layers[slideNumber][i].value == layer) {
        layerObj = layers[slideNumber][i];
        break;
      }
    }

    var removedElements = new Array();
    var removedElementsToCopy = new Array();

    for (var i = 0; i < elements[slideNumber].length; i++) {
      if (elements[slideNumber][i].layer == layerObj.value) {
        removedElements.push(elements[slideNumber][i]);
      }
    }

    for (var i = 0; i < removedElements.length; i++) {
      removedElementsToCopy.push(_.clone(removedElements[i]));
      elements[slideNumber].remove(removedElements[i]);
    }

    var action = new Action("DELETED LAYER", true);
    action.deletedElements = removedElementsToCopy;
    action.deletedLayer = _.clone(layerObj);
    redoActions.push(action);

    layers[slideNumber].remove(layerObj);
    prepareElements();
    selectedObj = null;
  } else if (lastAction.name == "RESIZE ELEMENT") {
    resizeAction = new Action("RESIZE ELEMENT", true);
    resizeAction.element = lastAction.element;
    resizeAction.x = lastAction.element.x;
    resizeAction.y = lastAction.element.y;
    resizeAction.sizeX = lastAction.element.sizeX;
    resizeAction.sizeY = lastAction.element.sizeY;

    lastAction.element.x = lastAction.x;
    lastAction.element.y = lastAction.y;
    lastAction.element.sizeX = lastAction.sizeX;
    lastAction.element.sizeY = lastAction.sizeY;

    redoActions.push(resizeAction);
  } else if (lastAction.name == "DELETED SLIDE") {
    var delSlide = lastAction.deletedSlide;
    var delLayers = lastAction.deletedLayers;
    var delBG = lastAction.deletedBackground;
    var delBlob = lastAction.deletedBlob;
    var delDelay = lastAction.deletedDelay;
    var pos = lastAction.position;
    elements.insert(pos, delSlide);
    layers.insert(pos, delLayers);
    bg.insert(pos, delBG);
    blobImgs.insert(pos, delBlob);
    slideDelays.insert(pos, delDelay);

    refreshSlide();

    var action = new Action("ADDED SLIDE", true);
    action.addedElements = elements[pos];
    action.addedLayers = layers[pos];
    action.addedBg = bg[pos];
    action.addedBlob = blobImgs[pos];
    action.addedDelay = slideDelays[pos];
    redoActions.push(action);
  } else if (lastAction.name == "ADDED SLIDE") {
    if (elements.length > 1) {
      var addedSlide = lastAction.addedElements;

      var slide = elements.indexOf(addedSlide);
      if (elements.length <= 1) {
        return;
      }

      if (slide == slideNumber) {
        lastSlideNumber = 0;
      }

      if (slideNumber > slide) {
        lastSlideNumber = slideNumber - 1;
      }

      if (slideNumber < slide) {
        lastSlideNumber = slideNumber;
      }

      var action = new Action("DELETED SLIDE", true);
      action.deletedSlide = _.clone(elements[slide]);
      action.deletedLayers = _.clone(layers[slide]);
      action.deletedBackground = _.clone(bg[slide]);
      action.deletedBlob = _.clone(blobImgs[slide]);
      action.deletedDelay = _.clone(slideDelays[slide]);
      action.position = slide;

      undoActions.push(action);

      elements.removeOnce(elements[slide]);
      layers.removeOnce(layers[slide]);
      bg.removeOnce(bg[slide]);
      blobImgs.removeOnce(blobImgs[slide]);
      slideDelays.removeOnce(slideDelays[slide]);

      if (typeof setup_slides === "function") {
        setup_slides();
      }

      if (typeof setup_layers === "function") {
        setup_layers();
      }
    }
  }

  undoActions.remove(lastAction);
}

function redo() {

  if (presenting) {
    return;
  }

  var lastAction = redoActions[redoActions.length - 1];

  if (lastAction == null) {
    return;
  }

  if (lastAction.name == "ADDED ELEMENT") {
    var actionElement = new Action("DELETED ELEMENT", true);

    if (selectedObj == lastAction.addedElement) {
      selectedObj = null;
    }

    var removedElement = _.clone(lastAction.addedElement);

    elements[slideNumber].remove(lastAction.addedElement);

    actionElement.deletedElement = removedElement;

    undoActions.push(actionElement);
  } else if (lastAction.name == "DELETED ELEMENT") {
    var actionElement = new Action("ADDED ELEMENT", true);
    elements[slideNumber].push(lastAction.deletedElement);

    var undoneElement = elements[slideNumber][elements[slideNumber].indexOf(lastAction.deletedElement)];
    actionElement.addedElement = undoneElement;
    redoActions.push(actionElement);

    prepareElements();

    selectedObj = undoneElement;
  } else if (lastAction.name == "MOVE ELEMENT") {
    moveAction = new Action("MOVE ELEMENT", true);
    moveAction.element = lastAction.element;
    moveAction.x = lastAction.element.x;
    moveAction.y = lastAction.element.y;

    lastAction.element.x = lastAction.x;
    lastAction.element.y = lastAction.y;

    undoActions.push(moveAction);

  } else if (lastAction.name == "DELETED LAYER") {

    var action = new Action("ADDED LAYER", true);

    for (var i = 0; i < lastAction.deletedElements.length; i++) {
      elements[slideNumber].push(lastAction.deletedElements[i]);
    }

    layers[slideNumber].push(lastAction.deletedLayer);

    action.addedLayer = lastAction.deletedLayer.value;

    undo.push(action);

    prepareElements();
  } else if (lastAction.name == "ADDED LAYER") {
    var layer = lastAction.addedLayer.value;
    var layerObj = null;

    for (var i = 0; i < layers[slideNumber].length; i++) {
      if (layers[slideNumber][i].value == layer) {
        layerObj = layers[slideNumber][i];
        break;
      }
    }

    var removedElements = new Array();
    var removedElementsToCopy = new Array();

    for (var i = 0; i < elements[slideNumber].length; i++) {
      if (elements[slideNumber][i].layer == layerObj.value) {
        removedElements.push(elements[slideNumber][i]);
      }
    }

    for (var i = 0; i < removedElements.length; i++) {
      removedElementsToCopy.push(_.clone(removedElements[i]));
      elements[slideNumber].remove(removedElements[i]);
    }

    var action = new Action("DELETED LAYER", true);
    action.deletedElements = removedElementsToCopy;
    action.deletedLayer = _.clone(layerObj);
    undoActions.push(action);

    layers[slideNumber].remove(layerObj);
    prepareElements();
    selectedObj = null;

  } else if (lastAction.name == "RESIZE ELEMENT") {
    resizeAction = new Action("RESIZE ELEMENT", true);
    resizeAction.element = lastAction.element;
    resizeAction.x = lastAction.element.x;
    resizeAction.y = lastAction.element.y;
    resizeAction.sizeX = lastAction.element.sizeX;
    resizeAction.sizeY = lastAction.element.sizeY;

    lastAction.element.x = lastAction.x;
    lastAction.element.y = lastAction.y;
    lastAction.element.sizeX = lastAction.sizeX;
    lastAction.element.sizeY = lastAction.sizeY;

    undoActions.push(resizeAction);
  } else if (lastAction.name == "DELETED SLIDE") {
    var delSlide = lastAction.deletedSlide;
    var delLayers = lastAction.deletedLayers;
    var delBG = lastAction.deletedBackground;
    var delBlob = lastAction.deletedBlob;
    var delDelay = lastAction.deletedDelay;
    var pos = lastAction.position;
    elements.insert(pos, delSlide);
    layers.insert(pos, delLayers);
    bg.insert(pos, delBG);
    blobImgs.insert(pos, delBlob);
    slideDelays.insert(pos, delDelay);

    refreshSlide();

    var action = new Action("ADDED SLIDE", true);
    action.addedElements = elements[pos];
    action.addedLayers = layers[pos];
    action.addedBg = bg[pos];
    action.addedBlob = blobImgs[pos];
    action.addedDelay = slideDelays[pos];
    redoActions.push(action);
  } else if (lastAction.name == "ADDED SLIDE") {
    if (elements.length > 1) {
      var addedSlide = lastAction.addedElements;

      var slide = elements.indexOf(addedSlide);
      if (elements.length <= 1) {
        return;
      }

      if (slide == slideNumber) {
        lastSlideNumber = 0;
      }

      if (slideNumber > slide) {
        lastSlideNumber = slideNumber - 1;
      }

      if (slideNumber < slide) {
        lastSlideNumber = slideNumber;
      }

      var action = new Action("DELETED SLIDE", true);
      action.deletedSlide = _.clone(elements[slide]);
      action.deletedLayers = _.clone(layers[slide]);
      action.deletedBackground = _.clone(bg[slide]);
      action.deletedBlob = _.clone(blobImgs[slide]);
      action.deletedDelay = _.clone(slideDelays[slide]);
      action.position = slide;

      undoActions.push(action);

      elements.removeOnce(elements[slide]);
      layers.removeOnce(layers[slide]);
      bg.removeOnce(bg[slide]);
      blobImgs.removeOnce(blobImgs[slide]);
      slideDelays.removeOnce(slideDelays[slide]);

      if (typeof setup_slides === "function") {
        setup_slides();
      }

      if (typeof setup_layers === "function") {
        setup_layers();
      }
    }
  }

  redoActions.remove(lastAction);
}

function input() {

  // funcinput
  if (presenting) {
    return;
  }

  var locX;
  var locY;
  var locSizeX;
  var locSizeY;

  var hoveredItem = detectHover();

  if (hoveredItem) {
    if (selectedObj == null || selectedObj != hoveredItem) {
      locX = hoveredItem.x;
      locY = hoveredItem.y;
      locSizeX = hoveredItem.sizeX;
      locSizeY = hoveredItem.sizeY;

      if (hoveredItem.type == Obj_type.SHAPE) {
        if (hoveredItem.text.toLowerCase() == "circle" || hoveredItem.text.toLowerCase() == "ellipse") {
          locX = hoveredItem.x - hoveredItem.sizeX;
          locY = hoveredItem.y - hoveredItem.sizeY;
          locSizeX = hoveredItem.sizeX * 2;
          locSizeY = hoveredItem.sizeY * 2;

        }
      }

      noFill();
      stroke(255, 255, 255, 190);
      strokeWeight(3);
      rect(
        locX,
        locY,
        locSizeX,
        locSizeY
      );
    }
  }

  if (selectedObj != null) {
    locX = selectedObj.x;
    locY = selectedObj.y;
    locSizeX = selectedObj.sizeX;
    locSizeY = selectedObj.sizeY;

    if (selectedObj.type == Obj_type.SHAPE) {
      if (selectedObj.text.toLowerCase() == "circle" || selectedObj.text.toLowerCase() == "ellipse") {
        locX = selectedObj.x - selectedObj.sizeX;
        locY = selectedObj.y - selectedObj.sizeY;
        locSizeX = selectedObj.sizeX * 2;
        locSizeY = selectedObj.sizeY * 2;

      }
    }

    noFill();
    stroke(255, 255, 255, 255);
    strokeWeight(2);
    rect(
      locX,
      locY,
      locSizeX,
      locSizeY
    );

    fill(0, 0, 0);
    circle(
      locX,
      locY,
      6
    );

    circle(
      locX + locSizeX,
      locY,
      6
    );

    circle(
      locX,
      locY + locSizeY,
      6
    );

    circle(
      locX + locSizeX,
      locY + locSizeY,
      6
    );

    anchorPoints = new Array();

    anchorPoints.push(new AnchorPoint(locX, locY, "leftup"));
    anchorPoints.push(new AnchorPoint(locX + locSizeX, locY, "rightup"));
    anchorPoints.push(new AnchorPoint(locX, locY + locSizeY, "leftdown"));
    anchorPoints.push(new AnchorPoint(locX + locSizeX, locY + locSizeY, "rightdown"));
  }

}

function mousePressed() {
  if (presenting) {
    return;
  }

  if (
    mouseX < 0 || mouseX > canvas.width ||
    mouseY < 0 || mouseY > canvas.height
  ) {
    return;
  }

  if (!documentLoaded) {
    return;
  }

  if ($(".background-pan").css("display") != "none" || $(".layer-color .pcr-app").css("visibility") != "hidden" || $(".background-change .pcr-app").css("visibility") != "hidden" || $("#shapes-dropdown").css("display") != "none") {
    return;
  }

  var selected = detectHover(true);

  if (selected != null) {
    if (selected.constructor.name == "p5_obj") {
      selectedObj = selected;

      currentLayer = selectedObj.layer;

      if (setup_layers) {
        setup_layers();
      }
    } else {
      if (selected == false) {
        selectedAnchorPoint = null;
      }
    }
  } else {
    selectedObj = null;
    selectedAnchorPoint = null;
  }

  if (selectedObj) {
    moveAction = new Action("MOVE ELEMENT");
    moveAction.element = selectedObj;
    moveAction.x = selectedObj.x;
    moveAction.y = selectedObj.y;

    resizeAction = new Action("RESIZE ELEMENT");
    resizeAction.element = selectedObj;
    resizeAction.x = selectedObj.x;
    resizeAction.y = selectedObj.y;
    resizeAction.sizeX = selectedObj.sizeX;
    resizeAction.sizeY = selectedObj.sizeY;
  }

  pressState = 1;

  if (refresh_properties) {
    refresh_properties();
  }
}

function mouseClicked() {
  if (presenting) {
    return;
  }

  if (
    mouseX < 0 || mouseX > canvas.width ||
    mouseY < 0 || mouseY > canvas.height
  ) {
    return;
  }

  if (!documentLoaded) {
    return;
  }

  if ($(".background-pan").css("display") != "none" || $(".layer-color .pcr-app").css("visibility") != "hidden" || $(".background-change .pcr-app").css("visibility") != "hidden" || $("#shapes-dropdown").css("display") != "none") {
    return;
  }

  if (saveMoveAction) {
    undoActions.push(moveAction);
  }

  if (saveResizeAction) {
    undoActions.push(resizeAction);
  }

  saveMoveAction = false;

  selectedAnchorPoint = null;
  pressState = 0;
}

function mouseDragged() {

  //funcdrag

  if (presenting) {
    return;
  }

  if (
    mouseX < 0 || mouseX > canvas.width ||
    mouseY < 0 || mouseY > canvas.height
  ) {
    return;
  }

  if (!documentLoaded) {
    return;
  }

  if ($(".background-pan").css("display") != "none" || $(".layer-color .pcr-app").css("visibility") != "hidden" || $(".background-change .pcr-app").css("visibility") != "hidden" || $("#shapes-dropdown").css("display") != "none") {
    return;
  }

  if (pressState != 1) {
    return;
  }

  if (selectedAnchorPoint && selectedObj) {
    saveResizeAction = true;

    if (selectedObj.type == Obj_type.SHAPE &&
      (selectedObj.text.toLowerCase() == "circle" || selectedObj.text.toLowerCase() == "ellipse")) {
      switch (selectedAnchorPoint.type) {
        case "leftdown":
          selectedObj.sizeX = selectedObj.x - mouseX;
          selectedObj.sizeY = mouseY - selectedObj.y;
          break;

        case "leftup":
          selectedObj.sizeX = selectedObj.x - mouseX;
          selectedObj.sizeY = selectedObj.y - mouseY;
          break;

        case "rightdown":
          selectedObj.sizeX = mouseX - selectedObj.x;
          selectedObj.sizeY = mouseY - selectedObj.y;
          break;
        case "rightup":
          selectedObj.sizeX = mouseX - selectedObj.x;
          selectedObj.sizeY = selectedObj.y - mouseY;
          break;
        default:
          break;
      }
    } else {
      switch (selectedAnchorPoint.type) {
        case "leftdown":
          var objX = selectedObj.x;
          selectedObj.sizeY = mouseY - selectedObj.y;

          var diff = objX - mouseX;

          if (diff >= 0 || selectedObj.sizeX + diff > 40) {
            selectedObj.x = mouseX;
            selectedObj.sizeX += objX - selectedObj.x;
          } else {
            selectedObj.x += (selectedObj.sizeX - 40);
            selectedObj.sizeX = 40;
          }
          break;

        case "leftup":
          var objX = selectedObj.x;
          var diff = objX - mouseX;
          if (diff >= 0 || selectedObj.sizeX + diff > 40) {
            selectedObj.x = mouseX;
            selectedObj.sizeX += objX - selectedObj.x;
          } else {
            selectedObj.x += (selectedObj.sizeX - 40);
            selectedObj.sizeX = 40;
          }

          var objY = selectedObj.y;
          var diff = objY - mouseY;
          if (diff >= 0 || selectedObj.sizeY + diff > 40) {
            selectedObj.y = mouseY;
            selectedObj.sizeY += objY - selectedObj.y;
          } else {
            selectedObj.y += (selectedObj.sizeY - 40);
            selectedObj.sizeY = 40;
          }
          break;

        case "rightdown":
          selectedObj.sizeX = mouseX - selectedObj.x;
          selectedObj.sizeY = mouseY - selectedObj.y;
          break;
        case "rightup":
          var objY = selectedObj.y;
          selectedObj.sizeX = mouseX - selectedObj.x;
          var diff = objY - mouseY;

          if (diff >= 0 || selectedObj.sizeY + diff > 40) {
            selectedObj.y = mouseY;
            selectedObj.sizeY += objY - selectedObj.y;
          } else {
            selectedObj.y += (selectedObj.sizeY - 40);
            selectedObj.sizeY = 40;
          }
          break;
        default:
          break;
      }
    }

    selectedObj.sizeX = Math.clamp(selectedObj.sizeX, 40, canvas.width);
    selectedObj.sizeY = Math.clamp(selectedObj.sizeY, 40, canvas.height);

    return;
  }

  var detect = detectHover();

  if (selectedObj && !selectedAnchorPoint) {
    saveMoveAction = true;
    selectedObj.x = mouseX - selectedObjOffX;
    selectedObj.y = mouseY - selectedObjOffY;
  }
}

function keyPressed() {

  if (presenting) {
    return;
  }

  if ($(':focus').length && $(':focus')[0] != $('canvas')[0])
    return;

  if (keyCode === DELETE) {
    if (selectedObj) {
      var cloneObj = _.clone(selectedObj);
      elements[slideNumber].remove(selectedObj);
      selectedObj = null;

      var undoAction = new Action("DELETED ELEMENT");

      undoAction.deletedElement = cloneObj;

      undoActions.push(undoAction);
    }
  } else if (keyCode === CONTROL) {
    ctrlPressed = true;
  } else if (keyCode === 90) {
    if (ctrlPressed) {
      undo();
    }
  } else if (keyCode === 89) {
    if (ctrlPressed) {
      redo();
    }
  }
}

function keyReleased() {
  if (presenting) {
    return;
  }

  if (keyCode === CONTROL) {
    ctrlPressed = false;
  }

}

function drawBackground() {

  if (typeof background === 'undefined')
    return;

  background(200, 200, 200);

  if (bg[slideNumber] == null) {
    background(200, 200, 200);
  } else {
    if (!(bg[slideNumber] instanceof rgba)) {
      tint(255, 255, 255, 255);
      background(bg[slideNumber].img);
    } else {
      background(bg[slideNumber].r, bg[slideNumber].g, bg[slideNumber].b);
    }
  }
}

function drawElementsByLayer() {
  for (var i = 0; i < elements[slideNumber].length; i++) {
    if (layersVisibility[elements[slideNumber][i].layer] && elements[slideNumber][i].visible == 1) {
      handleElement(elements[slideNumber][i]);
    } else if (layersVisibility[elements[slideNumber][i].layer] && (elements[slideNumber][i].visible == 0 && !presenting)) {
      handleElement(elements[slideNumber][i], true);
    }
  }
}
