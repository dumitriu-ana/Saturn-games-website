class Animation {
  constructor(looping, speed=5) {
    this.looping = looping;
    this.speed = speed;
    this.running = false;
    this.ended = false;

    this.startAnimation = function(){

    };

    this.loopAnimation = function(){

    };

    this.endAnimation = function(){
      this.running = false;
      this.eraseProcess();
    };

    this.eraseProcess = function(){

    };
  }

  setupObj(obj){
    this.obj = obj;
    obj.animation = this;
  }
}

var fadeIn = new Animation(false);
fadeIn.speed = 1;

fadeIn.startAnimation = function(){
  this.objA = this.obj.color.a;
  this.obj.color.a=0;
  this.running = true;
};

fadeIn.loopAnimation = function(){
  if(this.ended){
    return;
  }
  if(this.obj.color.a<this.objA){
    if((this.obj.color.a+this.speed)<=this.objA){
      this.obj.color.a+=this.speed;
    }else{
      this.obj.color.a=this.objA;
    }
  }else{
    if(!this.ended){
      this.ended=true;
      this.endAnimation();
    }
  }
}


var upAppear = new Animation(false);
upAppear.speed = 1;

upAppear.startAnimation = function(){
  this.objX = this.obj.x;
  this.objY = this.obj.y;

  var w = editWidth;
  var h = editHeight;

  if(presenting){
    w = presWidth;
    h = presHeight;
  }

  this.obj.x = w/2-this.obj.sizeX/2;
  this.obj.y = -this.obj.sizeY*2;

  this.running = true;
};

upAppear.loopAnimation = function(){
  if(this.ended){
    return;
  }
  var inDestinationX = false;
  var inDestinationY = false;

  if(Math.abs(this.objX-this.obj.x)>=this.speed){

    if(this.objX-this.obj.x<0){
      this.obj.x -= this.speed;
    }else{
      this.obj.x += this.speed;
    }
  }else{

    this.obj.x = this.objX;
    inDestinationX = true;
  }


  if(Math.abs(this.objY-this.obj.y)>=this.speed){

    this.obj.y += this.speed;
  }else{
    this.obj.y = this.objY;
    inDestinationY = true;
  }

  if(inDestinationX && inDestinationY){
    if(!this.ended){
      this.ended=true;
      this.endAnimation();
    }
  }
}


var downAppear = new Animation(false);
downAppear.speed = .001;

downAppear.startAnimation = function(){
  this.objX = this.obj.x;
  this.objY = this.obj.y;

  var w = editWidth;
  var h = editHeight;

  if(presenting){
    w = presWidth;
    h = presHeight;
  }

  this.obj.x = w/2-this.obj.sizeX/2;
  this.obj.y = h+this.obj.sizeY/2;

  this.running = true;
};

downAppear.loopAnimation = function(){
  if(this.ended){
    return;
  }
  var inDestinationX = false;
  var inDestinationY = false;

  if(Math.abs(this.objX-this.obj.x)>=this.speed){
    if(this.objX-this.obj.x<0){
      this.obj.x -= this.speed;
    }else{
      this.obj.x += this.speed;
    }
  }else{
    this.obj.x = this.objX;
    inDestinationX = true;
  }

  if(Math.abs(this.objY-this.obj.y)>=this.speed){
    this.obj.y -= this.speed;
  }else{
    this.obj.y = this.objY;
    inDestinationY = true;
  }

  if(inDestinationX && inDestinationY){
    if(!this.ended){

      this.ended=true;
      this.endAnimation();
    }
  }
}



var popAppear = new Animation(false);
popAppear.speed = 25;

popAppear.startAnimation = function(){

  this.growingX = true;
  this.growingY = true;

  this.objX = this.obj.x;
  this.objY = this.obj.y;

  this.sizeX = this.obj.sizeX;
  this.sizeY = this.obj.sizeY;

  this.obj.x += this.obj.sizeX/2;
  this.obj.y += this.obj.sizeY/2;


  this.obj.sizeX=0;
  this.obj.sizeY=0;

  this.speedX = this.speed;
  this.speedY = (this.sizeY*this.speedX)/this.sizeX;

  this.offsetX = .3*this.sizeX;
  this.offsetY = .3*this.sizeY;

  this.running = true;
};

popAppear.loopAnimation = function(){

  if(this.ended){
    return;
  }

  var inDestinationX = false;
  var inDestinationY = false;

  if(this.growingX){
    if(this.obj.sizeX + this.speedX < this.sizeX+this.offsetX){
      this.obj.sizeX+=this.speedX;
      this.obj.x-=this.speedX/2;
    }else{
      this.obj.sizeX=this.sizeX+this.offsetX;
      this.obj.x=this.objX-this.offsetX/2;
      this.growingX = false;
    }
  }else{
    if(this.obj.sizeX - this.speedX > this.sizeX){
      this.obj.sizeX-=this.speedX;
      this.obj.x+=this.speedX/2;
    }else{
      this.obj.sizeX = this.sizeX;
      this.obj.x=this.objX;
      inDestinationX = true;
    }
  }

  if(this.growingY){
    if(this.obj.sizeY + this.speedY < this.sizeY+this.offsetY){
      this.obj.sizeY+=this.speedY;
      this.obj.y-=this.speedY/2;
    }else{
      this.obj.sizeY=this.sizeY+this.offsetY;
      this.obj.y=this.objY-this.offsetY/2;
      this.growingY = false;
    }
  }else{
    if(this.obj.sizeY - this.speedY > this.sizeY){
      this.obj.sizeY-=this.speedY;
      this.obj.y+=this.speedY/2;
    }else{
      this.obj.sizeY = this.sizeY;
      this.obj.y=this.objY;
      inDestinationY = true;
    }
  }

  if(inDestinationX && inDestinationY){
    if(!this.ended){

      this.ended=true;
      this.endAnimation();
    }
  }
}
