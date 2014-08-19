/*
author: Vlad Roman (vlad@afian.com)
*/
var MyJSSscrollerHASDragging = false;

var MyJSScrollerHandler = {
	scrollers : [],
	intvals : [],
	
	initByClassName : function(className) {
		i = this.scrollers.length;
		document.getElementsByClassName(className).each(function(node) {
			var orient, dir, speed;
			MyJSScrollerHandler.scrollers[i] = new MyJSScroller(node, i, orient, dir, speed);
			i++;
		});
	},
	
	attachMouseWheel : function(el, index) {
		if (window.addEventListener)
		        /** DOMMouseScroll is for mozilla. */
		       el.addEventListener('DOMMouseScroll', function(event){MyJSScrollerHandler.wheel(event, index);}, false);
		/** IE/Opera. */
			el.onmousewheel = function(event){MyJSScrollerHandler.wheel(event, index);};
	},
		
	handleWheelMovement : function(delta, index) {
		MyJSScrollerHandler.scrollers[index].move(-(delta*10));
	},
	
	wheel : function(event, index){
	        var delta = 0;
	        if (!event) /* For IE. */
	                event = window.event;
	        if (event.wheelDelta) { /* IE/Opera. */
	                delta = event.wheelDelta/120;
	                if (window.opera)
	                        delta = -delta;
	        } else if (event.detail) { /** Mozilla case. */
	                delta = -event.detail/3;
	        }
	        if (delta)
	                MyJSScrollerHandler.handleWheelMovement(delta, index);
	        if (event.preventDefault)
	                event.preventDefault();
		event.returnValue = false;
	}
};


function MyJSScroller(node, index,  orient, dir, speed) {
	
	if (MyJSScrollerHandler.scrollers[index]) {
		return false;
	}
	

	if (node.getAttribute('myjsscroller') == 'true') {
		return false;
	}
	
	node.setAttribute('myjsscroller', 'true');
	
	this.pausePoints = [];
	this.pauseTimer = 0;
	this.node = node;
	this.index = index;
	this.orient = orient || node.getAttribute('orientation');
	this.dir = dir || node.getAttribute('direction');
	spd = speed || node.getAttribute('scrollspeed');
	if (!spd) {
		spd = 20;
	}
	
	this.speed = 1000/spd;
	wraper = document.createElement('DIV');
	//wraper.style.border = '1px solid red';
	wraper.style.height = '100%';
	node.style.overflow = 'hidden';
	wraper.style.overflow = 'hidden';
	
	wraper.style.position = 'relative';
	wraper.style.top = '0px';
	wraper.style.left = '0px';
	this.wraper = wraper;
	movingWraper = document.createElement('DIV');
	if (this.orient == 'horizontal') {
		movingWraper.style.whiteSpace = 'nowrap';
	}
	
	//movingWraper.style.border = '1px solid blue';
	movingWraper.style.position='absolute';
	this.movingWraper = movingWraper;
	wraper.appendChild(movingWraper);
	movingWraper.innerHTML = node.innerHTML;
	node.innerHTML = '';
	node.appendChild(wraper);
	this.w = movingWraper.offsetWidth;
	this.h = movingWraper.offsetHeight;
	
	if (this.orient != 'horizontal' && this.dir == 'down') {
		this.movingWraper.style.top = -this.h+'px';
	}
	
	
	if (MyJSSscrollerHASDragging) {
		if (this.orient == 'horizontal') {
	 		drg = Drag.init(movingWraper, null, -this.w, this.w, 0, 0);
		} else {
	 		drg = Drag.init(movingWraper, null, 0, 0, -this.h, this.h);
		}
	}
	
	
	
	
	for (var i = 0 ; i < movingWraper.childNodes.length ; i++) {
		ch = movingWraper.childNodes[i];
		if (ch.className == 'scrollpauser') {
			
			
			if (ch.getAttribute('scrollhide') == "1") {
				ch.style.visibility = 'hidden';
				ch.style.display ='inline';
				ch.style.margin = '0px';
				ch.style.border = '0px';
			}
			if (ch.getAttribute('scrolldelay') > 0) {
				delay = ch.getAttribute('scrolldelay');
			} else {
				delay = 2000;			
			}
			this.pausePoints.push([ch.offsetTop, ch.offsetLeft, delay]);
		}
	}
	
	
	
	
	movingWraper.onDragStart = function(x,y) {
		MyJSScrollerHandler.scrollers[index].dragged = true;
		MyJSScrollerHandler.scrollers[index].pause();
	}
	movingWraper.onDragEnd = function() {
		MyJSScrollerHandler.scrollers[index].dragged = false;
		MyJSScrollerHandler.scrollers[index].resume();
	}
	
	
	node.onmouseover = function() {
		MyJSScrollerHandler.scrollers[index].pause();
	}
	node.onmouseout = function() {
		if (!MyJSScrollerHandler.scrollers[index].dragged) {
			MyJSScrollerHandler.scrollers[index].resume();
		}
	}
	MyJSScrollerHandler.attachMouseWheel(node, index);
	
	this.start();
	
}

MyJSScroller.prototype.start = function() {
	
	if (!MyJSScrollerHandler.intvals[this.index]) {
		MyJSScrollerHandler.intvals[this.index] = window.setInterval('MyJSScrollerHandler.scrollers[\''+this.index+'\'].move()', this.speed);
	}
}

MyJSScroller.prototype.move = function(delta) {
	
	mw = this.movingWraper;
	slf = this;
	
	if (!delta) {
			delta = 1
		}
		
	if (this.orient == 'horizontal') {
		if (this.dir != "right") {
			if (mw.offsetLeft < -this.w) {
				mw.style.left = this.wraper.offsetWidth+'px';
			} else {
			 	mw.style.left = (mw.offsetLeft-delta)+'px';
			}
		} else {
			if (mw.offsetLeft > this.wraper.offsetWidth) {
				mw.style.left = -mw.offsetWidth+'px';
			} else {
			 	mw.style.left = (mw.offsetLeft+delta)+'px';
			}
		}
	} else {
		if (this.dir != "down") {
			if (mw.offsetTop > -this.h) {
			
				//pausers
				this.pausePoints.each(function(point){
					if (-point[0] == mw.offsetTop) {
						slf.pause();
						window.setTimeout('MyJSScrollerHandler.scrollers[\''+slf.index+'\'].start()', point[2]);
					}
				});
				
				mw.style.top = (mw.offsetTop-delta)+'px';
				
			} else {
			 	mw.style.top = this.wraper.offsetHeight+'px';
			}
		} else {
			if (-mw.offsetTop+this.wraper.offsetHeight > 0) {
				mw.style.top = (mw.offsetTop+delta)+'px';
			} else {
			 	mw.style.top = -mw.offsetHeight+'px';
			}
		}
	}
}

MyJSScroller.prototype.pause = function() {
	window.clearInterval(MyJSScrollerHandler.intvals[this.index]);
	MyJSScrollerHandler.intvals[this.index] = false;
}

MyJSScroller.prototype.resume = function() {
	this.start();
}

//window.onload = function() {MyJSScrollerHandler.initByClassName('scrollable');}