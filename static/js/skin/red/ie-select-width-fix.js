/* Copyright (c) 2006 Yahoo! Inc. All rights reserved. */var YAHOO=window.YAHOO||{};YAHOO.namespace=function(_1){if(!_1||!_1.length){return null;}var _2=_1.split(".");var _3=YAHOO;for(var i=(_2[0]=="YAHOO")?1:0;i<_2.length;++i){_3[_2[i]]=_3[_2[i]]||{};_3=_3[_2[i]];}return _3;};YAHOO.namespace("util");YAHOO.namespace("widget");YAHOO.namespace("example");

/* Copyright (c) 2006, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.net/yui/license.txt */ YAHOO.util.CustomEvent=function(_1,_2){this.type=_1;this.scope=_2||window;this.subscribers=[];if(YAHOO.util.Event){YAHOO.util.Event.regCE(this);}};YAHOO.util.CustomEvent.prototype={subscribe:function(fn,_4,_5){this.subscribers.push(new YAHOO.util.Subscriber(fn,_4,_5));},unsubscribe:function(fn,_6){var _7=false;for(var i=0,len=this.subscribers.length;i<len;++i){var s=this.subscribers[i];if(s&&s.contains(fn,_6)){this._delete(i);_7=true;}}return _7;},fire:function(){for(var i=0,len=this.subscribers.length;i<len;++i){var s=this.subscribers[i];if(s){var _10=(s.override)?s.obj:this.scope;s.fn.call(_10,this.type,arguments,s.obj);}}},unsubscribeAll:function(){for(var i=0,len=this.subscribers.length;i<len;++i){this._delete(i);}},_delete:function(_11){var s=this.subscribers[_11];if(s){delete s.fn;delete s.obj;}delete this.subscribers[_11];}};YAHOO.util.Subscriber=function(fn,obj,_13){this.fn=fn;this.obj=obj||null;this.override=(_13);};YAHOO.util.Subscriber.prototype.contains=function(fn,obj){return (this.fn==fn&&this.obj==obj);};if(!YAHOO.util.Event){YAHOO.util.Event=function(){var _14=false;var _15=[];var _16=[];var _17=[];var _18=[];var _19=[];var _20=[];var _21=[];var _22=0;var _23=[];var _24=0;var _25={};return {POLL_RETRYS:200,POLL_INTERVAL:50,EL:0,TYPE:1,FN:2,WFN:3,SCOPE:3,ADJ_SCOPE:4,isSafari:(/Safari|Konqueror|KHTML/gi).test(navigator.userAgent),isIE:(!this.isSafari&&!navigator.userAgent.match(/opera/gi)&&navigator.userAgent.match(/msie/gi)),addDelayedListener:function(el,_27,fn,_28,_29){_16[_16.length]=[el,_27,fn,_28,_29];if(_14){_22=this.POLL_RETRYS;this.startTimeout(0);}},startTimeout:function(_30){var _31=this;var _32=function(){_31._tryPreloadAttach();};this.timeout=setTimeout(_32,_30);},onAvailable:function(_33,_34,_35,_36){_23.push({id:_33,fn:_34,obj:_35,override:_36});_22=this.POLL_RETRYS;this.startTimeout(0);},addListener:function(el,_37,fn,_38,_39){if(!fn||!fn.call){return false;}if(this._isValidCollection(el)){var ok=true;for(var i=0,len=el.length;i<len;++i){ok=(this.on(el[i],_37,fn,_38,_39)&&ok);}return ok;}else{if(typeof el=="string"){var oEl=this.getEl(el);if(_14&&oEl){el=oEl;}else{this.addDelayedListener(el,_37,fn,_38,_39);return true;}}}if(!el){return false;}if("unload"==_37&&_38!==this){_17[_17.length]=[el,_37,fn,_38,_39];return true;}var _42=(_39)?_38:el;var _43=function(e){return fn.call(_42,YAHOO.util.Event.getEvent(e),_38);};var li=[el,_37,fn,_43,_42];var _46=_15.length;_15[_46]=li;this.mapListener(el,_37,fn,_46);if(this.useLegacyEvent(el,_37)){var _47=this.getLegacyIndex(el,_37);if(_47==-1){_47=_19.length;_21[el.id+_37]=_47;_19[_47]=[el,_37,el["on"+_37]];_20[_47]=[];el["on"+_37]=function(e){return YAHOO.util.Event.fireLegacyEvent(YAHOO.util.Event.getEvent(e),_47);};}_20[_47].push(_46);}else{if(el.addEventListener){el.addEventListener(_37,_43,false);}else{if(el.attachEvent){el.attachEvent("on"+_37,_43);}}}return true;},fireLegacyEvent:function(e,_48){var ok=true;var le=_20[_48];for(var i=0,len=le.length;i<len;++i){var _50=le[i];if(_50){var li=_15[_50];if(li&&li[this.WFN]){var _51=li[this.ADJ_SCOPE];var ret=li[this.WFN].call(_51,e);ok=(ok&&ret);}else{delete le[i];}}}return ok;},getLegacyIndex:function(el,_53){var key=this.generateId(el)+_53;if(typeof _21[key]=="undefined"){return -1;}else{return _21[key];}},useLegacyEvent:function(el,_55){return ((!el.addEventListener&&!el.attachEvent)||(_55=="click"&&this.isSafari));},removeListener:function(el,_56,fn,_57){if(!fn||!fn.call){return false;}if(typeof el=="string"){el=this.getEl(el);}else{if(this._isValidCollection(el)){var ok=true;for(var i=0,len=el.length;i<len;++i){ok=(this.removeListener(el[i],_56,fn)&&ok);}return ok;}}if("unload"==_56){for(i=0,len=_17.length;i<len;i++){var li=_17[i];if(li&&li[0]==el&&li[1]==_56&&li[2]==fn){delete _17[i];return true;}}return false;}var _58=null;if("undefined"==typeof _57){_57=this._getCacheIndex(el,_56,fn);}if(_57>=0){_58=_15[_57];}if(!el||!_58){return false;}if(el.removeEventListener){el.removeEventListener(_56,_58[this.WFN],false);}else{if(el.detachEvent){el.detachEvent("on"+_56,_58[this.WFN]);}}delete _15[_57][this.WFN];delete _15[_57][this.FN];delete _15[_57];return true;},getTarget:function(ev,_60){var t=ev.target||ev.srcElement;if(_60&&t&&"#text"==t.nodeName){return t.parentNode;}else{return t;}},getPageX:function(ev){var x=ev.pageX;if(!x&&0!==x){x=ev.clientX||0;if(this.isIE){x+=this._getScrollLeft();}}return x;},getPageY:function(ev){var y=ev.pageY;if(!y&&0!==y){y=ev.clientY||0;if(this.isIE){y+=this._getScrollTop();}}return y;},getXY:function(ev){return [this.getPageX(ev),this.getPageY(ev)];},getRelatedTarget:function(ev){var t=ev.relatedTarget;if(!t){if(ev.type=="mouseout"){t=ev.toElement;}else{if(ev.type=="mouseover"){t=ev.fromElement;}}}return t;},getTime:function(ev){if(!ev.time){var t=new Date().getTime();try{ev.time=t;}catch(e){return t;}}return ev.time;},stopEvent:function(ev){this.stopPropagation(ev);this.preventDefault(ev);},stopPropagation:function(ev){if(ev.stopPropagation){ev.stopPropagation();}else{ev.cancelBubble=true;}},preventDefault:function(ev){if(ev.preventDefault){ev.preventDefault();}else{ev.returnValue=false;}},getEvent:function(e){var ev=e||window.event;if(!ev){var c=this.getEvent.caller;while(c){ev=c.arguments[0];if(ev&&Event==ev.constructor){break;}c=c.caller;}}return ev;},getCharCode:function(ev){return ev.charCode||((ev.type=="keypress")?ev.keyCode:0);},_getCacheIndex:function(el,_65,fn){var key=el.id+_65;if(!_25[key]){return -1;}else{for(var i=0,len=_25[key].length;i<len;++i){var _66=_25[key][i];if(_66.fn==fn){return _66.index;}}}return -1;},generateId:function(el){var id=el.id;if(!id){id="yui-event-auto-id-"+(_24++);el.id=id;}return id;},mapListener:function(_68,_69,_70,_71){var key=this.generateId(_68)+_69;if(!_25[key]){_25[key]=[];}_25[key].push({fn:_70,index:_71});},_isValidCollection:function(o){return (o&&o.length&&typeof o!="string"&&!o.tagName&&!o.alert&&typeof o[0]!="undefined");},getEl:function(id){return document.getElementById(id);},clearCache:function(){},regCE:function(ce){_18.push(ce);},_load:function(e){_14=true;},_tryPreloadAttach:function(){if(this.locked){return false;}this.locked=true;var _74=!_14;if(!_74){_74=(_22>0);}var _75=[];for(var i=0,len=_16.length;i<len;++i){var d=_16[i];if(d){var el=this.getEl(d[this.EL]);if(el){this.on(el,d[this.TYPE],d[this.FN],d[this.SCOPE],d[this.ADJ_SCOPE]);delete _16[i];}else{_75.push(d);}}}_16=_75;notAvail=[];for(i=0,len=_23.length;i<len;++i){var _77=_23[i];if(_77){el=this.getEl(_77.id);if(el){var _78=(_77.override)?_77.obj:el;_77.fn.call(_78,_77.obj);delete _23[i];}else{notAvail.push(_77);}}}_22=(_75.length===0&&notAvail.length===0)?0:_22-1;if(_74){this.startTimeout(this.POLL_INTERVAL);}this.locked=false;},_unload:function(e,me,_80){for(var i=0,len=_17.length;i<len;++i){var l=_17[i];if(l){var _82=(l[this.ADJ_SCOPE])?l[this.SCOPE]:window;l[this.FN].call(_82,this.getEvent(e),l[this.SCOPE]);}}len=_15.length;if(len){for(i=0;i<len;++i){l=_15[i];if(l){this.removeListener(l[this.EL],l[this.TYPE],l[this.FN],i);}}this.clearCache();}for(i=0,len=_18.length;i<len;++i){_18[i].unsubscribeAll();delete _18[i];}for(i=0,len=_19.length;i<len;++i){delete _19[i];}},_getScrollLeft:function(){return this._getScroll()[1];},_getScrollTop:function(){return this._getScroll()[0];},_getScroll:function(){var dd=document.documentElement;db=document.body;if(dd&&dd.scrollTop){return [dd.scrollTop,dd.scrollLeft];}else{if(db){return [db.scrollTop,db.scrollLeft];}else{return [0,0];}}}};}();YAHOO.util.Event.on=YAHOO.util.Event.addListener;if(document&&document.body){YAHOO.util.Event._load();}else{YAHOO.util.Event.on(window,"load",YAHOO.util.Event._load,YAHOO.util.Event,true);}YAHOO.util.Event.on(window,"unload",YAHOO.util.Event._unload,YAHOO.util.Event,true);YAHOO.util.Event._tryPreloadAttach();}

/* Copyright (c) 2006, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.net/yui/license.txt */YAHOO.util.Dom=function(){var ua=navigator.userAgent.toLowerCase();var id_counter=0;return{get:function(el){if(typeof el!='string'&&!(el instanceof Array)){return el;}if(typeof el=='string'){return document.getElementById(el);}else{var collection=[];for(var i=0,len=el.length;i<len;++i){collection[collection.length]=this.get(el[i]);}return collection;}return null;},getStyle:function(el,property){var f=function(el,self){var value=null;var dv=document.defaultView;if(property=='opacity'&&el.filters){value=1;try{value=el.filters.item('DXImageTransform.Microsoft.Alpha').opacity/100;}catch(e){try{value=el.filters.item('alpha').opacity/100;}catch(e){}}}else if(el.style[property]){value=el.style[property];}else if(el.currentStyle&&el.currentStyle[property]){value=el.currentStyle[property];}else if(dv&&dv.getComputedStyle){var converted='';for(var i=0,len=property.length;i<len;++i){if(property.charAt(i)==property.charAt(i).toUpperCase()){converted=converted+'-'+property.charAt(i).toLowerCase();}else{converted=converted+property.charAt(i);}}if(dv.getComputedStyle(el,'')&&dv.getComputedStyle(el,'').getPropertyValue(converted)){value=dv.getComputedStyle(el,'').getPropertyValue(converted);}}return value;};return this.batch(el,f,this);},setStyle:function(el,property,val){var f=function(el,self){switch(property){case'opacity':if(el.filters){el.style.filter='alpha(opacity='+val*100+')';if(!el.currentStyle.hasLayout){el.style.zoom=1;}}else{el.style.opacity=val;el.style['-moz-opacity']=val;el.style['-khtml-opacity']=val;}break;default:el.style[property]=val;}};this.batch(el,f,this);},getXY:function(el){var f=function(el,self){if(el.parentNode===null||self.getStyle(el,'display')=='none'){return false;}var parent=null;var pos=[];var box;if(el.getBoundingClientRect){box=el.getBoundingClientRect();Math.max(document.documentElement.scrollTop,document.body.scrollTop);var scrollTop=Math.max(document.documentElement.scrollTop,document.body.scrollTop);var scrollLeft=Math.max(document.documentElement.scrollLeft,document.body.scrollLeft);return[box.left+scrollLeft,box.top+scrollTop];}else if(document.getBoxObjectFor){box=document.getBoxObjectFor(el);pos=[box.x,box.y];}else{pos=[el.offsetLeft,el.offsetTop];parent=el.offsetParent;if(parent!=el){while(parent){pos[0]+=parent.offsetLeft;pos[1]+=parent.offsetTop;parent=parent.offsetParent;}}if(ua.indexOf('opera')!=-1||(ua.indexOf('safari')!=-1&&self.getStyle(el,'position')=='absolute')){pos[0]-=document.body.offsetLeft;pos[1]-=document.body.offsetTop;}}if(el.parentNode){parent=el.parentNode;}else{parent=null;}while(parent&&parent.tagName!='BODY'&&parent.tagName!='HTML'){pos[0]-=parent.scrollLeft;pos[1]-=parent.scrollTop;if(parent.parentNode){parent=parent.parentNode;}else{parent=null;}}return pos;};return this.batch(el,f,this);},getX:function(el){return this.getXY(el)[0];},getY:function(el){return this.getXY(el)[1];},setXY:function(el,pos,noRetry){var f=function(el,self){var style_pos=self.getStyle(el,'position');if(style_pos=='static'){self.setStyle(el,'position','relative');style_pos='relative';}var pageXY=YAHOO.util.Dom.getXY(el);if(pageXY===false){return false;}var delta=[parseInt(YAHOO.util.Dom.getStyle(el,'left'),10),parseInt(YAHOO.util.Dom.getStyle(el,'top'),10)];if(isNaN(delta[0])){delta[0]=(style_pos=='relative')?0:el.offsetLeft;}if(isNaN(delta[1])){delta[1]=(style_pos=='relative')?0:el.offsetTop;}if(pos[0]!==null){el.style.left=pos[0]-pageXY[0]+delta[0]+'px';}if(pos[1]!==null){el.style.top=pos[1]-pageXY[1]+delta[1]+'px';}var newXY=self.getXY(el);if(!noRetry&&(newXY[0]!=pos[0]||newXY[1]!=pos[1])){var retry=function(){YAHOO.util.Dom.setXY(el,pos,true);};setTimeout(retry,0);}};this.batch(el,f,this);},setX:function(el,x){this.setXY(el,[x,null]);},setY:function(el,y){this.setXY(el,[null,y]);},getRegion:function(el){var f=function(el,self){return new YAHOO.util.Region.getRegion(el);};return this.batch(el,f,this);},getClientWidth:function(){return(document.documentElement.offsetWidth||document.body.offsetWidth);},getClientHeight:function(){return(self.innerHeight||document.documentElement.clientHeight||document.body.clientHeight);},getElementsByClassName:function(className,tag,root){var re=new RegExp('(?:^|\\s+)'+className+'(?:\\s+|$)');var method=function(el){return re.test(el['className']);};return this.getElementsBy(method,tag,root);},hasClass:function(el,className){var f=function(el,self){var re=new RegExp('(?:^|\\s+)'+className+'(?:\\s+|$)');return re.test(el['className']);};return this.batch(el,f,this);},addClass:function(el,className){var f=function(el,self){if(self.hasClass(el,className)){return;}el['className']=[el['className'],className].join(' ');};this.batch(el,f,this);},removeClass:function(el,className){var f=function(el,self){if(!self.hasClass(el,className)){return;}var re=new RegExp('(?:^|\\s+)'+className+'(?:\\s+|$)','g');var c=el['className'];el['className']=c.replace(re,' ');};this.batch(el,f,this);},replaceClass:function(el,oldClassName,newClassName){var f=function(el,self){self.removeClass(el,oldClassName);self.addClass(el,newClassName);};this.batch(el,f,this);},generateId:function(el,prefix){prefix=prefix||'yui-gen';var f=function(el,self){el=el||{};if(!el.id){el.id=prefix+id_counter++;}return el.id;};return this.batch(el,f,this);},isAncestor:function(haystack,needle){haystack=this.get(haystack);if(!haystack||!needle){return false;}var f=function(needle,self){if(haystack.contains&&ua.indexOf('safari')<0){return haystack.contains(needle);}else if(haystack.compareDocumentPosition){return!!(haystack.compareDocumentPosition(needle)&16);}else{var parent=needle.parentNode;while(parent){if(parent==haystack){return true;}else if(parent.tagName=='HTML'){return false;}parent=parent.parentNode;}return false;}};return this.batch(needle,f,this);},inDocument:function(el){var f=function(el,self){return self.isAncestor(document.documentElement,el);};return this.batch(el,f,this);},getElementsBy:function(method,tag,root){tag=tag||'*';root=this.get(root)||document;var nodes=[];var elements=root.getElementsByTagName(tag);for(var i=0,len=elements.length;i<len;++i){if(method(elements[i])){nodes[nodes.length]=elements[i];}}return nodes;},batch:function(el,method,o){el=this.get(el);if(!el||!el.length){return method(el,o);}var collection=[];for(var i=0,len=el.length;i<len;++i){collection[collection.length]=method(el[i],o);}return collection;}};}();YAHOO.util.Region=function(t,r,b,l){this.top=t;this.right=r;this.bottom=b;this.left=l;};YAHOO.util.Region.prototype.contains=function(region){return(region.left>=this.left&&region.right<=this.right&&region.top>=this.top&&region.bottom<=this.bottom);};YAHOO.util.Region.prototype.getArea=function(){return((this.bottom-this.top)*(this.right-this.left));};YAHOO.util.Region.prototype.intersect=function(region){var t=Math.max(this.top,region.top);var r=Math.min(this.right,region.right);var b=Math.min(this.bottom,region.bottom);var l=Math.max(this.left,region.left);if(b>=t&&r>=l){return new YAHOO.util.Region(t,r,b,l);}else{return null;}};YAHOO.util.Region.prototype.union=function(region){var t=Math.min(this.top,region.top);var r=Math.max(this.right,region.right);var b=Math.max(this.bottom,region.bottom);var l=Math.min(this.left,region.left);return new YAHOO.util.Region(t,r,b,l);};YAHOO.util.Region.prototype.toString=function(){return("Region {"+"  t: "+this.top+", r: "+this.right+", b: "+this.bottom+", l: "+this.left+"}");};YAHOO.util.Region.getRegion=function(el){var p=YAHOO.util.Dom.getXY(el);var t=p[1];var r=p[0]+el.offsetWidth;var b=p[1]+el.offsetHeight;var l=p[0];return new YAHOO.util.Region(t,r,b,l);};YAHOO.util.Point=function(x,y){this.x=x;this.y=y;this.top=y;this.right=x;this.bottom=y;this.left=x;};YAHOO.util.Point.prototype=new YAHOO.util.Region();

YAHOO.namespace( 'YAHOO.Hack' ).FixIESelectWidth = new function()
{
	var oSelf = this; 
	var YUE = YAHOO.util.Event;
	var YUD = YAHOO.util.Dom;
	var oTimer = {};
	var oAnim = {};
	var nTimerId =  0 ;
	var dLastFocalItem;
	var ie7 = !!(document.uniqueID  &&   typeof(XMLHttpRequest)!='undefined' )
	function init(el)
	{
		
		
		el = el || this;
		
		

		if( el.tagName.toLowerCase() != 'select')
		{
			throw Error('element [' + el.id + '] is not <select>');
			return;
		};	
		
		if(!YUD.hasClass( el.parentNode, 'select-box'))
		{
			throw Error('className select-box is not included for element [' + el.id + ']');
			return;
		};	
		
		var oRs = el.runtimeStyle;
		var oPRs = el.parentNode.runtimeStyle;
		
		
		oPRs.fonSize = 0;
		
		
		var sDisplay = el.parentNode.currentStyle.display.toLowerCase() ;
		if(  sDisplay=='' ||  sDisplay=='inline' ||  sDisplay=='inline-block' )
		{
			oPRs.display = 'inline-block';
			oPRs.width = el.offsetWidth + 'px';
			oPRs.height =el.offsetHeight + 'px';
			oPRs.position = 'relative';
			oRs.position = 'absolute';
			oRs.top = 0;
			oRs.left = 0;
		};
		
		
		
		el._timerId = ( nTimerId+=1 );

		el.selectedIndex = Math.max( 0 , el.selectedIndex );
		
		oTimer[ '_' + el._timerId ] = setTimeout('void(0)',0);
		oAnim [ 'A' + el._timerId ] = setTimeout('void(0)',0);
		
		YUE.on( el, 'mouseover' , onMouseOver);
		YUE.on( document, 'mousedown' ,onMouseDown , el, true);
		YUE.on(  el, 'change' ,collapseSelect , el, true);
	}


	function collapseSelect(e)
	{
		status++;
		this.runtimeStyle.width = '';			
	}

	function onMouseOver(e )
	{
	
		var el = this;	
		if(dLastFocalItem && dLastFocalItem !=el)
		{
			 onMouseDown.call( dLastFocalItem , e );
		};

		var sTimerId ='_' +  el._timerId ;
		var sAniId = 'A' + el._timerId ;
		clearTimeout( oTimer[ sTimerId ] );

		

		var onTween = function()
		{
			clearTimeout( oAnim [  sAniId  ] );
			if( Math.abs( nEndWidth - nStartWidth ) > 3 )
			{
				nStartWidth += (nEndWidth - nStartWidth ) /3;
				el.runtimeStyle.width = nStartWidth + 'px';
				oAnim [  sAniId  ] = setTimeout( onTween ,0 );
			}
			else
			{
				el.runtimeStyle.width = 'auto';
				el.selectedIndex = Math.max( 0 , el.selectedIndex );
			}
		}

		var nStartWidth =  el.offsetWidth ;
		el.runtimeStyle.width = 'auto';
		var nEndWidth  = el.offsetWidth;
		

		clearTimeout( oAnim [  sAniId  ] );
		onTween();

		el.focus();		
		dLastFocalItem = el;
	}

	function onMouseDown(e , el )
	{
		el = ( e.srcElement || e.target );
		
		
		
		if( el == this && e.type!='mouseover' )
		{
			status++;
			YUE.stopEvent(e);
			return false;
		};
		
		
		el = this;
		
		clearTimeout( oAnim [ 'A' + el._timerId ] );
	
		
		var sTimerId ='_' +  el._timerId ;
		var doItLater = function()
		{
			el.runtimeStyle.width = '';			
		};
		if( e.type=='mouseover')
		{ doItLater();}
		else{
			oTimer[ sTimerId ] = setTimeout(doItLater,100);
		}
	}

	

	function constructor(sId)
	{
		sId = [ sId , ''].join('');
		//Only fix for IE55 ~ IE7
		
		if(document.uniqueID && window.createPopup )
		{			
			YUE.onAvailable(sId ,init );
			return true;

		}else{return false};
	};

	return  constructor;
}
