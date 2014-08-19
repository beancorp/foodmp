// JavaScript Document
var speed = 30;
var scroll_wrap=document.getElementById("scroll_wrap");
var scroll_item1=document.getElementById("scroll_item1");

function Marquee_left(){
	if(scroll_item2.offsetWidth-scroll_wrap.scrollLeft<=0)
		scroll_wrap.scrollLeft-=scroll_item1.offsetWidth
	else{
		scroll_wrap.scrollLeft = scroll_wrap.scrollLeft + 5;
	}
}

function Marquee_right(){
	if(scroll_wrap.scrollLeft<=0)
		scroll_wrap.scrollLeft+=scroll_item1.offsetWidth
	else{
		scroll_wrap.scrollLeft = scroll_wrap.scrollLeft - 5;
	}
}
var Myaction;
function Move_left() {
	clearInterval(Myaction);
	Myaction = setInterval(Marquee_left,speed);
}
function Move_right() {
	clearInterval(Myaction);
	Myaction=setInterval(Marquee_right,speed);
}
function Move_stop() {
	clearInterval(Myaction);
}