var bxs,bxe,fxs,fxe,ys,ye,ta,ia,ie,st,ss,ft,fs,xp,yp,ci,t,tar,tarl;
ta = document.getElementById(thumbid);
//ia = document.getElementById(imgid);
t = ta.getElementsByTagName('li')
for(i=0;i<t.length;i++){
	//alert(t[i].innerHTML);
}
ie = document.all ? true : false;
st = 3;
ss = 3;
ft = 10;
fs = 5;
xp,yp = 0;
document.onmousemove = getPos;

function slideShow(){
  var taw = ta.parentNode.offsetWidth; //ta宽度
  var taa = taw / 3; //每个图片宽度
  bxs = leftPos(ta); // 图片div左侧起始坐标
  bxe = bxs + taa; // 左数第一个图片右侧坐标
  fxe = bxs + taw; // 图片div右侧结束右侧坐标
  fxs = fxe - taa; // 右数第一个图片左侧坐标
  ys = topPos(ta); // 顶部坐标
  ye = ys + ta.offsetHeight; // 底部坐标
  var len = t.length;
  tar = [];
 for(i=0; i < len; i++){
    var id = t[i].value;
    tar[i] = id;
    //t[i].onclick = new Function("getImg('" + id + "')");
    if(i == 0) {
      //getImg(id);
    }
  } /**/
  //alert('bxs='+bxs+';bxe='+bxe+';fxe='+fxe+';fxs='+fxs+';ys='+ys+';ye='+ye);
  tarl = tar.length;
}

function getPos(e){
  if(ie){
    xp = event.clientX + document.body.scrollLeft;
    yp = event.clientY + document.body.scrollTop;
  }else{
    xp = e.pageX;
    yp = e.pageY;
  }  
  if(xp < 0){xp = 0}
  if(yp < 0){yp = 0}
  if(xp > bxs && xp < bxe && yp > ys && yp < ye){
    scrlThumbs(-1);
  }else if(xp > fxs && xp < fxe && yp > ys && yp < ye){
    scrlThumbs(1);
  }else{
    cnclScrl();
  }
}

function scrlThumbs(d){
  clearInterval(ta.timer);
  var l;
  if(d == -1){
    l = 0;
  }else{
    l = t[tarl-1].offsetLeft - (ta.parentNode.offsetWidth - t[tarl-1].offsetWidth) + 10;
  }
  ta.timer = setInterval(function(){scrlMv(d,l)},st);
}

function scrlMv(d,l){
  //alert(d+':'+l);
  ta.style.left = ta.style.left || '0px';
  var left = ta.style.left.replace('px','');
  if(d == 1){
    if(l - Math.abs(left) <= ss){
      cnclScrl(ta.id);
      ta.style.left = '-' + l + 'px';
    }else{
      ta.style.left = left - ss + 'px';
    }
  }else{
    if(Math.abs(left) - l <= ss){
      cnclScrl(ta.id);
      ta.style.left = l + 'px';
    }else{
      ta.style.left = parseInt(left) + ss + 'px';
    }
  }
}

function cnclScrl(){clearTimeout(ta.timer)}

function leftPos(t){
  var left = 0;
  if(t.offsetParent){
    while(1){
      left += t.offsetLeft;
      if(!t.offsetParent){break}
      t = t.offsetParent;
    }
  }else if(t.x){
    left += t.x;
  }
  return left;
}

function topPos(t){
  var top = 0;
  if(t.offsetParent){
    while(1){
      top += t.offsetTop;
      if(!t.offsetParent){break}
      t = t.offsetParent;
    }
  }else if(t.y){
    top += t.y;
  }
  return top;
}