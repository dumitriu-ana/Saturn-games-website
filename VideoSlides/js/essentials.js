var Obj_type = {
  TEXT: 1,
  SHAPE: 2,
  IMAGE: 3,
};

const CENTER = "center";
const TOP = "top";
const BOTTOM = "bottom";
const LEFT = "left";
const RIGHT = "right";

class rgba {
  constructor(r,g,b,a=255) {
    this.r = r;
    this.g = g;
    this.b = b;
    this.a = a;
  }
}

class p5_obj {

  constructor(
    x,
    y,
    sizeX,
    sizeY,
    type,
    layer=1,
    color=new rgba(255, 255, 255),
    text=""
  ) {
    this.x=x;
    this.y=y;
    this.sizeX=sizeX;
    this.sizeY=sizeY;
    this.type = type;
    this.layer = layer;
    this.color = color;
    this.text = text;

    this.font_size = 32;

    this.rw=0;
    this.rx=0;
    this.ry=0;
    this.rz=0;

    this.stroke = 1;
    this.fill = 1;

    this.src = "";

    this.canvas = null;

    this.visible=true;

    if(this.type == Obj_type.SHAPE){
      if(this.text.toLowerCase() == "circle"){
        if(this.sizeX==0){
          this.sizeX = this.sizeY;
        }else if(this.sizeY==0){
          this.sizeY = this.sizeX;
        }
      }
    }

    if(this.type==Obj_type.TEXT){
      this.alignX = CENTER;
      this.alignY = TOP;

      this.fontPath = 'assets/fonts/times_new_roman.ttf';
      this.font = loadFont(this.fontPath);
    }

  }

  setTextFont(path){
    this.fontPath = path;
    this.font = loadFont(this.fontPath);
  }

  setTextAlign(x, y){
    this.alignX = CENTER;
    this.alignY = TOP;
    if(x==0){
      this.alignX = LEFT;
    }else if(x==1){
      this.alignX = RIGHT;
    }else{
      this.alignX = CENTER;
    }

    if(y==1){
      this.alignY = BOTTOM;
    }else if(y==0){
      this.alignY = TOP;
    }else{
      this.alignY = CENTER;
    }
  }

  setFontSize(size){
    this.font_size = size;
  }

  setBorderRadius(w=0,x=0,y=0,z=0){
    this.rw=w;
    this.rx=x;
    this.ry=y;
    this.rz=z;
  }

  setStroke(s){
    this.stroke = s;
  }

  setFill(f){
    this.fill = f;
  }

  setImgSrc(src){
    this.src = src;
    this.img = loadImage(
      src,
      img => {
        console.log("successful loaded img");
      },
      img => {
        console.log("failed loaded img");
      }
    );
  }

  setVisibility(visible){
    this.visible = visible;
  }

}
