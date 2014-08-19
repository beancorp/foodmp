var bxs,bxe,fxs,fxe,ys,ye,ta,ia,ie,st,ss,ft,fs,xp,yp,ci,t,tar,tarl,optv;
ta = document.getElementById(thumbid);
ia = document.getElementById(imgid);
t = ta.getElementsByTagName('li')
ie = document.all ? true : false;
st = 3;
ft = 10;
xp,yp = 0;
optv = 100;
if(getOs()=="MSIE"){
fs = 5;
ss = 3;
}else{
fs = 20;
ss = 4;
}

//document.onmousemove = getPos;

function getOs()
{
    var OsObject = "";
   if(navigator.userAgent.indexOf("MSIE")>0) {
        return "MSIE";
   }
   if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){
        return "Firefox";
   }
   if(isSafari=navigator.userAgent.indexOf("Safari")>0) {
        return "Safari";
   } 
   if(isCamino=navigator.userAgent.indexOf("Camino")>0){
        return "Camino";
   }
   if(isMozilla=navigator.userAgent.indexOf("Gecko/")>0){
        return "Gecko";
   }
  
} 



function slideShow(selid){
  if(!selid){
	selid = 0;
  }
  var taw = ta.parentNode.offsetWidth;
  var taa = taw / 4;
  bxs = leftPos(ta);
  bxe = bxs + taa;
  fxe = bxs + taw;
  fxs = fxe - taa;
  ys = topPos(ta);
  ye = ys + ta.offsetHeight;
  var len = t.length;
  tar = [];
  getImg(selid);
  setnum(selid);
  for(i=0; i < len; i++){
    var id = t[i].value;
    tar[i] = id;
    t[i].onclick = new Function("getImg('" + id + "',this);setnum('"+id+"')");
  }
  tarl = tar.length;
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
  ta.style.left = ta.style.left || '0px';
  var left = ta.style.left.replace('px','');
  if(d == 1){
    if(l - Math.abs(left) <= (ss+fixwidth)){
      cnclScrl(ta.id);
      if(parseInt(left)==0){
		  ta.style.left = '0px';
	  }else{
		  ta.style.left = '-' +( l - fixwidth-1)+ 'px';
	  }
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

function getImg(id,obj){
  if(auto){clearTimeout(ia.timer)}
  if(ci != null){
    var ts = ia.getElementsByTagName('img');
    var tsl = ts.length;
    var x = 0;
    for(x; x < tsl; x++){
      if(ci.id != id){
        var o = ts[x];
        clearInterval(o.timer);
        o.timer = setInterval(function(){fdOut(o)},fs);
      }
    }
  }
  var i;
  if(!document.getElementById(id)){
    i = document.createElement('img');
    ia.appendChild(i);
    i.id = id;
    i.av = 0;
	i.style.width = tmpimgary[id]['width']+"px";
	i.style.height = tmpimgary[id]['height']+"px";
	i.style.left = parseInt((700-tmpimgary[id]['width'])/2)+"px";
	i.style.top = parseInt((525-tmpimgary[id]['height'])/2)+"px";
    i.style.opacity = 0;
    i.style.filter = 'alpha(opacity=0)';
//    i.src = imgdir + '/' + tmpimgary[id]['text'];// + imgext;
    i.src = tmpimgary[id]['text'];// + imgext;
  }else{
    i = document.getElementById(id);
    clearInterval(i.timer);
  }
  i.timer = setInterval(function(){fdIn(i)},fs);
}

function imgNav(d){
  var curr = 0;
  for(key in tar){
    if(tar[key] == ci.id){
      curr = key;
    }
  }
  if(tar[parseInt(curr) + d]){
    getImg(tar[parseInt(curr) + d]);
	setnum(tar[parseInt(curr) + d]);
  }else{
    if(d == 1){
      getImg(tar[0]);
	  setnum(tar[0]);
    }else{
      getImg(tar[tarl - 1]);
	  setnum(tar[tarl - 1]);
    }
  }
}

function autoSlide(){
  ia.timer = setInterval(function(){imgNav(1)}, autodelay * 1000);
}

function fdIn(i){
  if(i.complete){
    i.av = i.av + fs;
    i.style.opacity = i.av / optv;
    i.style.filter = 'alpha(opacity=' + i.av + ')';
  }
  if(i.av >= optv){
    if(auto){autoSlide()}
    clearInterval(i.timer);
    ci = i;
  }
}

function fdOut(i){
  i.av = i.av - fs;
  i.style.opacity = i.av / optv;
  i.style.filter = 'alpha(opacity=' + i.av + ')';
  if(i.av <= 0){
    clearInterval(i.timer);
    if(i.parentNode){i.parentNode.removeChild(i)}
  }
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