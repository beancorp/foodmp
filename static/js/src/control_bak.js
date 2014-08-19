<!-- Javascript 
//
var setNS4 = (document.layers) ? true : false;
var setIE4 = (document.all) ? true : false;

function onmousechange(obj,classname)
{
	obj.className=classname;
}

function trout(obj){
	obj.className="trOut";
}
function trover(obj){	
	obj.className="trOver";
}
//container
function trclick(obj,objsub,objtr,container){
	objtr.className="trClick";
	e=eval(obj + "."+objsub);
	if(container!=null && container !=""){
		inputList(container,e.value);
	}else if(! e.checked && e.disabled==false){
		e.checked=true;
		btDisabled(obj,objsub,true);
	}else if(e.disabled==false){
		e.checked=false;
		btDisabled(obj,objsub,false);
	}
}

function inputList(container,values,type){
	if(type == null || type == ""){
		valuesArr = values.split("|");
		with (container){
			for(var i=0 ; i<options.length; i++ ){
				if(options[i].value == valuesArr[1] ){
					return false;
				}
			}
			lengths = options.length;
			options.add(new Option(valuesArr[0],valuesArr[1]));
			selectedIndex=lengths;
		}
	}
}

function outputList(container,type){
	if(type == null || type == ""){
		with (container){
			var contentArray=new Array();
			j=0;
			for(i=0;i<length;i++){
				if(i!=selectedIndex){
					contentArray[j]=options[i].text + "||" + options[i].value;
					j++;
				}
			}
			length=0;
			for (i=0; i<j ; i++){
				temp = contentArray[i].split("||");
				options.add(new Option(temp[0],temp[1]));
			}
		}
	}
}

//
function btDisabled(obj,objsub,value){
	try
	{
		btNotDisabled(obj);
		cpbString = eval(obj + ".cpb"+objsub);
		if(typeof(cpbString)=="object" && cpbString.value.length>0){
			cparr = cpbString.value.split(",");
			for(var i=0 ; i<cparr.length ; i++ ){
				try{
					e=eval(cparr[i]);
					value ? e.disabled = value : "";
				}catch(ex){
				}
			}
			setSelectDisabled(obj,objsub,value);
		}
	}catch(ex)
	{}
}
//
function btNotDisabled(obj){
	object1 = eval(obj);
	recordesSelectNum= 0;
	for (var i=0;i<object1.elements.length;i++){
		var e = object1.elements[i];
		if (e.checked  == true && e.type == "checkbox")
		   recordesSelectNum++;
    }
	try{
		CPBadd.disabled = false;
	}catch(ex){
	}
	try{
		if(recordesSelectNum != 1){
			CPBcopy.disabled = true;
		}else
			CPBcopy.disabled = false;
	}catch(ex){
	}
	try{
		if(recordesSelectNum != 1){
			CPBcha.disabled = true;
		}else{
			CPBcha.disabled = false;
		}
	}catch(ex){
	}
	try{
		if(recordesSelectNum==0){
			CPBdel.disabled = true;
		}else{
			CPBdel.disabled = false;
		}
	}catch(ex){
	}
	try{
		if(recordesSelectNum==0){
			CPBset.disabled = true;
		}else{
			CPBset.disabled = false;
		}
	}catch(ex){
	}
}

//
function setSelectDisabled(obj,objsub,value){
	if(value=="") value = false;
	obj = eval(obj);
	for (var i=0;i<obj.elements.length;i++){
		var e = obj.elements[i];
		//var e2= eval("obj" + ".cpb" + objsub );
		if (e.name != objsub && e.type == "checkbox"){
			e.checked = false;
			e.disabled = value;
		}
    }
}

function bnout(obj){
	obj.className="bnout";
}
function bnover(obj){	
	obj.className="bnover";
}
function bnclick(obj,objsub,objtr){
	obj.className="bnclick";
}

/**ID
* obj 		Form
* idName    ID, default "ID"
* noSubmit  in javascript, default "submit"
*/
function getid(obj,idName,noSubmit) {
	var setid = "";
	if(noSubmit)
	{
		setid ="";
	}else if(idName == "undefinde" || idName == null || idName == ""){
		setid = "&ID=";
	}else{
		setid = "&" + idName + "=";
	}
	//alert (setid);
	for (var i=0;i<obj.elements.length;i++){
		var e = obj.elements[i];
		if (e.checked && e.type == "checkbox")
			setid  += e.value + ","; 
	}
	if(setid.length>0){
		setid = setid.substr(0,setid.length-1);
	}
	//alert(setid);
	return setid ;
}

//get ID string
function getidv2(obj) {
	//var setid="&ID=" ;
	obj.ID.value = "";
	for (var i=0;i<obj.elements.length;i++){
		var e = obj.elements[i];
		if (e.checked && e.type == "checkbox")
			obj.setidarray.value += e.value + "," ; 
	}
	//return setid ;
}


//check items
function CheckMore(object1,tempString){
  var HasMulit=false;
  var j=0;
  for (var i=0;i<object1.elements.length;i++){
    var e = object1.elements[i];
    if (e.checked  == true && e.type == "checkbox")
	   j++;
  }
  if (j<1) {
    alert ('至少要选择一条记录才能执行“' +  tempString + '”!');
    return false;
    }
  else
    return true;
}

//check a item
function CheckSelect(obj,tempString){
  var HasMulit=false;
  var j=0;
  for (var i=0;i<obj.elements.length;i++){
    var e = obj.elements[i];
    if (e.checked  == true && e.type == "checkbox"){
	   j++;}
  }
  if (j>1) {
    alert ('once items“' + tempString + '”!');
    return false;
  }
  else if (j==0){
    alert ('not selected, not“' +  tempString + '”!');
    return false;
  }
  else 
    return true;
}



// button or all
function selectAll(obj){
	obj=document.all.item(obj);	
	if(obj != null && obj !="null"){
		for (var i=0;i<obj.elements.length;i++){
			var e = obj.elements[i];
			if(e.type == "checkbox" && e.disabled == false && e.name.lastIndexOf("checkbox") >=0){
				e1 = eval("obj.cpb" + e.name);
				if(typeof(e1)=="object" && e1.value == "" || typeof(e1)=="undefined"){
					e.checked =true; 
				}
			}
		}
		btNotDisabled(obj);
	}
}

//reverse button
function selectReverse(obj){
	obj=document.all.item(obj);
	if(obj != null && obj !="null"){
		for (var i=0;i<obj.elements.length;i++){
			var e = obj.elements[i];
			if(e.type == "checkbox" && e.disabled == false && e.name.lastIndexOf("checkbox") >=0)
			{
				//alert(e.type);
				e1 = eval("obj.cpb" + e.name);
				if(typeof(e1)=="object" && e1.value == "" || typeof(e1)=="undefined"){
					e.checked ?(e.checked =false):(e.checked =true);
				}
			}
		}
		btNotDisabled(obj);
	}
}

//cancel button
function selectCancel(obj){
	obj=document.all.item(obj);
	if(obj != null && obj !="null"){
		for (var i=0;i<obj.elements.length;i++){
			var e = obj.elements[i];
			if( e.type == "checkbox" && e.name.lastIndexOf("checkbox") >=0)
			{
				//if(eval("obj.cpb" + e.name).value == ""){
					e.checked =false;
				//}
			}
		}
		btNotDisabled(obj);
	}
}

//===========================================
/*============ other button ==============*/
function otherRes(urlstr,win,idName){
	if(win == null){
		window.location.target	=	"_self";
	}else{
		window.location.target	=	win;
	}
	window.location.href	=	urlstr;
}

/*============ new button ==============*/
function addrows(obj,cp){
	obj=eval(obj);
	window.location.target	=	"_self";
	window.location.href	=	"?cp=" + cp + obj.seturl.value;
}

/*============ copy button ==============*/
function copyrows(obj,cp,idName){
	obj=eval(obj);
	if (CheckSelect(obj,'copy')==true){
		window.location.target	=	"_self";
		window.location.href	=	"?cp=" + cp + getid(obj,idName) + obj.seturl.value ;
	}
	
}

/*============ set button ==============*/
function setrows(obj,cp,idName){
	obj=eval(obj);
	if (CheckMore(obj,'setting')==true){
		if (confirm("Are you setting selected records?")){
			window.location.target	=	"_self";
			window.location.href	=	"?cp=" + cp + getid(obj,idName) + obj.seturl.value;
		}
	}
}

/*============ auditing button ==============*/
function audrows(obj,cp,idName){
	obj=eval(obj);
	if (CheckSelect(obj,'auditing')==true){
		window.location.target	=	"_self";
		window.location.href	=	"?cp=" + cp + getid(obj,idName) + obj.seturl.value;
	}
}

/*============ change button ==============*/
function charows(obj,cp,idName){
	obj=eval(obj);
	if (CheckSelect(obj,'change')==true){
		window.location.target	=	"_self";
		window.location.href	=	"?cp=" + cp + getid(obj,idName) + obj.seturl.value;
		//getidv2(obj);
		//obj.action = "?cp=" + cp;
		//obj.submit();
	}
}

/*============ information button ==============*/
function inforows_ajax(obj,objsub,url,param,title,container,idName){
	obj_o = obj;
	obj=eval(obj);
	if (CheckSelect(obj,'information')==true){
			ajaxWinPop(obj_o,objsub,'centerscr','','',true,title);
			ajaxLoadPage(url,param + '&' + getid(obj,idName) ,'get',container);
	}
}

/*============ delete button ==============*/
function deletes(obj,cp,idName){
	obj=eval(obj);
	if (CheckMore(obj,'delete')==true){
        if (confirm("Are you delete selected records? ")){
			window.location.target	=	"_self";
			window.location.href	=	"?cp=" + cp + getid(obj,idName) + obj.seturl.value;
		}
	}
}
/*========== real time button ==================*/
function clickRowsButton(param,returnparam)
{
	window.location.target	=	"_self";
	window.location.href	=	"?" + param + "&rparam=" + escape(returnparam);
}
/*============ all button ==============*/
function otherrows(obj,cp,title,rows,idName,isPrompt){
	obj=eval(obj);
	if(isPrompt && !confirm("Are you execute \"" + title + "\" operation?"))	{
	}else if( rows == 1 ){
		if (CheckSelect(obj,title)==true){
			window.location.target	=	"_self";
			window.location.href	=	"?" + obj.seturl.value +"&cp=" + cp + getid(obj,idName);
		}
	}else if(rows >1 ){
		if (CheckMore(obj,title)==true){
			window.location.target	=	"_self";
			window.location.href	=	"?" + obj.seturl.value +"&cp=" + cp + getid(obj,idName);
		}
	}else{
		window.location.target	=	"_self";
		window.location.href	=	"?" + obj.seturl.value +"&cp=" + cp + getid(obj,idName);
	}
	
}
//===========================================

/**
* obj    		
* sortNum 		
* sortTotal 	
* url  			
* param 		
* container 	
*/
function sortButton(obj,sortNum,sortTotal,url,param,container)
{
	param += "&SR=" + sortNum;
	for(i=0;i<sortTotal; i++)
	{
		try{
			e = eval(obj + i);
			
			if(sortNum != i)
			{
				e.title = "sort";
				e.innerHTML = "|";
			}
			else if(e.innerHTML == "↓")
			{
				e.title = "ASC";
				e.innerHTML = "↑";
				param += "&SRS=0";
			}
			else
			{
				e.title = "DESC";
				e.innerHTML = "↓";
				param += "&SRS=1";
			}
		}
		catch(ex){}
	}
	try
	{
		if(typeof(container) == "object")
		{
			ajaxLoadPage(url,param,'get',container);
		}
		else
		{
			otherRes(url + "?" + param ,null);
		}
	}catch(ex){ alert(ex);}
}

//=================ajax==================
/*
url 
request 
method 
container 
ispop default false
isvar default false
*/
function ajaxLoadPage(url,request,method,container,ispop,isvar){
	method=method.toUpperCase();
	var loading_msg='loading ...';
	var loader	= null;
	var popStatu = ""; //string of get pop page 
	
	//alert(url);
	
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		loader = new XMLHttpRequest();
		if (loader.overrideMimeType) {
			loader.overrideMimeType('text/xml');
		}
	} else if (window.ActiveXObject) { // IE
		try {
			loader = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				loader = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!loader) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}
	
	if (method=='GET'){
		urls=url.split("?");
		if (urls[1]=='' || typeof urls[1]=='undefined'){
			url=urls[0]+"?"+request;
		}else{
			url=urls[0]+"?"+urls[1]+"&"+request;
		}
		request=null;//for GET method,loader should send NULL
	}
	
	//alert (url);
	loader.open(method,url,!isvar);
	
	if (method=="POST")	{
		loader.setRequestHeader("Content-Length",request.length);
		loader.setRequestHeader("Cache-Control","no-cache");
		loader.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	}
	loader.onreadystatechange=function(){
		if (loader.readyState==1){
			//container.innerHTML=loading_msg;
		}
		if (loader.readyState==4){
			//alert(request + "\n" +loader.responseText);
			if(ispop)
			{
				popStatu = loader.responseText;
				//Is it pop
				if(popStatu == "hasData"){
					mssagePop();
				}
			}
			else if (!isvar)
			{
				//HTML
				container.innerHTML=loader.responseText;
				//alert(container.innerHTML);
			}
			else
			{
				//var
				container = loader.responseText;
			}
		}
	}
	loader.send(request);
	
	//
	if(isvar)
	{
		return	container;
	}
}

function formToRequestString(form_obj){
	var query_string='';
	var and='';
	//alert(form_obj.length);
	for (i=0;i<form_obj.length ;i++ ){
		e=form_obj[i];
		if (e.name != '' && e.type !="submit"){
			if (e.type=='select-one'){
				try{
					element_value=e.options[e.selectedIndex].value;
				}catch(ex){
				}
			}else if (e.type=='checkbox' || e.type=='radio'){
				if (e.checked==false){
					continue;	
				}
				//element_value=e.value;
				element_value=escape(e.value);
			}else{
				//element_value=e.value;
				element_value=escape(e.value);
			}
			query_string+=and+e.name+'='+element_value.replace(/\&/g,"%26");
			and="&";
		}	
	}
	return query_string;
}

function ajaxFormSubmit(form_obj,container){
	ajaxLoadPage(form_obj.getAttributeNode("action").value,formToRequestString(form_obj),form_obj.method,container)
}

/*
* AJAX 
* obj 		
* url 		
* param 	
* method 	
* obj2		
* value1 	
* value2 	
*/

function ajaxSubButton(obj,url,param,method,obj2,value1,value2)
{
	if(obj2.value == value1)
	{
		obj.style.display='';
		obj2.value = value2 == '' ? 'close' : value2;
		ajaxLoadPage(url,param,method,obj);
	}
	else
	{
		obj2.value = value1 == '' ? 'list' : value1;
		obj.style.display='none';
		obj.innerHTML='&nbsp;&nbsp;loading...';
	}
}
/* 
* AJAX
*/
function ajaxWindows(obj){
	//if(windowName == "") windowName = "ajaxPopDiv";
	printStr = "<object width='100%' height='100%'><div id=\"ajaxPopDiv\" style=\"z-index:1000;display:none;position: absolute;width:100%;height:100%;\" style=\"/*filter:progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=80,finishOpacity=80);background: url(../images/bg_all_orange.jpg);*/\">";
	printStr += "<table width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	printStr += "<tr><td align=\"center\"><table width=\"350\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"trOver\">";
	printStr += "<tr><td height=\"160\" align=\"center\" bgcolor=\"#E9F4FE\" id=\"ajaxPopContent\">Loading...</td></tr>";
	printStr += "<tr><td height=\"30\" align=\"center\"><input type=\"button\" name=\"Submit\" value=\" confirm \" onClick=\"ajaxWindowClose('"+ obj +"')\" class=\"bnout\" onMouseOver=\"bnover(this);\" onMouseOut=\"bnout(this);\">";
	printStr += "</td></tr></table></td></tr></table></div></object>";
	document.writeln(printStr);
}

/* 
* AJAX
*/
function ajaxContentWindows(obj,width,height,isShade,cleanContainer){
	if (width == "" || width == null) 
		width = 300;
	if (height == "" || height == null)
		height = 300;
	if(cleanContainer == "" || cleanContainer==null) cleanContainer=false;	
	printStr = "<object width='"+width+"' height='"+height+"'><div id=\"ajaxPopDiv2\" style=\"z-index:999;display:none;position: absolute;width:"+width+";height:" +height+ ";\">";
	printStr += "<table width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	printStr += "<tr><td align=\"center\"><table width=\""+ width +"\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"trOver\">";
	printStr += "<tr><td height=\""+ height +"\" bgcolor=\"#E9F4FE\"><div class=\"treelist\" id=\"ajaxPopContent2\">Loading...</div></td></tr>";
	printStr += "<tr><td height=\"25\" align=\"center\"><input type=\"button\" name=\"Submit\" value=\" close \" onClick=\"ajaxWindowClose('"+ obj +"',ajaxPopDiv2,"+cleanContainer+")\" class=\"bnout\" onMouseOver=\"bnover(this);\" onMouseOut=\"bnout(this);\">";
	printStr += "</td></tr></table></td></tr></table></div></object>";
	document.writeln(printStr);
}

/* 
* AJAX窗口内容
* obj 			
* width 			Int				
* height 			Int				
* isShade 			Booblean		
* cleanContainer 	Booblean		
* isTop   			Booblean 		
* titles			String				
*/
function ajaxContentWindows2(obj,width,height,isShade,cleanContainer,isTop,titles){
	if (width == "" || width == null) 
		width = 300;
	if (height == "" || height == null)
		height = 300;
	if(cleanContainer == "" || cleanContainer==null) cleanContainer=false;	
	titles = typeof(titles) == "undefined" ? "" : titles ;
	printStr = "<object width='"+width+"' height='"+height+"'><div id=\"ajaxPopDiv2\" style=\"z-index:999;display:none;position: absolute;width:"+width+";height:" +height+ ";\">";
	printStr += "<table width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	printStr += "<tr><td align=\"center\"><table width=\""+ width +"\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"trOver\">";
	printStr += "<tr><td width=\"100%\" id=\"popWinTitle\">&nbsp;<b>"+ titles +"</b>&nbsp;</td><td height=\"20\" align=\"right\" width =\"30\"><input type=\"button\" name=\"Submit\" value=\"close\" onClick=\"ajaxWindowClose('"+ obj +"',ajaxPopDiv2,"+cleanContainer+")\" class=\"bnout\" onMouseOver=\"bnover(this);\" onMouseOut=\"bnout(this);\">&nbsp;</td></tr>";
	if(isTop)
	{
		printStr += "<tr><td colspan=\"2\" height=\""+ height +"\" valign=\"top\" bgcolor=\"#E9F4FE\"><div id=\"ajaxPopContent2\">Loading...</div></td></tr>";
	}
	else
	{
		printStr += "<tr><td colspan=\"2\" height=\""+ height +"\" bgcolor=\"#E9F4FE\"><div id=\"ajaxPopContent2\">Loading...</div></td></tr>";
	}
	printStr += "</table></td></tr></table></div></object>";

	document.writeln(printStr);
}

/**
*	
* 　obj       FormObject    
*   subobj	  object 		
*   popStyle  String        
*   divleft   int          
*   divtop    int           
*   isShade   Boolean       default false
*   titles    String		window title
*/

function ajaxWinPop(obj,subobj,popStyle,divleft,divtop,isShade,titles)
{
	if(typeof(titles)!= "undefined" && typeof(popWinTitle) == "object")
	{
		popWinTitle.innerHTML = "<b>" + titles + "</b>";
	}
	
	var pointo = ajaxGetXY(subobj);
	var divLimit = 10 ;
	with (ajaxPopDiv2.style)
	{
		displaySelectArea(obj,"none");
		display = '';
		if(divleft >0 && divtop >0){
			left = pointo.x + subobj.offsetWidth+1 + divleft ;
			top  = divtop;
		}else if(popStyle=="up"){
			left = pointo.x;
			top  = pointo.y - ajaxPopDiv2.offsetHeight;
		}else if(popStyle == "upright"){
			left = pointo.x + subobj.offsetWidth+1;
			top  = pointo.y + subobj.offsetHeight - ajaxPopDiv2.offsetHeight;
		}else if(popStyle == "downright"){
			left = pointo.x + subobj.offsetWidth+1;
			top  = pointo.y;
		}else if(popStyle == "downleft"){
			left = pointo.x + subobj.offsetWidth +1 - ajaxPopDiv2.offsetWidth;
			top  =  pointo.y+subobj.offsetHeight + 1;
		}else if(popStyle == "downcenter"){
			left = pointo.x + subobj.offsetWidth +1 - ajaxPopDiv2.offsetWidth/2;
			top  =  pointo.y+subobj.offsetHeight + 1;
		}else if(popStyle == "centerright"){
			tempLeft = pointo.x + subobj.offsetWidth;
			tempTop  = pointo.y - ajaxPopDiv2.offsetHeight/2;
			tempTopTotal = tempTop + ajaxPopDiv2.offsetHeight ;
			pageArr = getPageScroll();
			yOffsetH = pageArr[1] + document.body.offsetHeight;

			left = tempLeft>0 ? tempLeft : divLimit;
			top  = tempTop>0 ? (tempTopTotal >= yOffsetH ? yOffsetH - ajaxPopDiv2.offsetHeight - divLimit : (tempTop < pageArr[1] ? pageArr[1] + divLimit : tempTop)) : divLimit;
		}else if(popStyle == "centerscr"){//
			pageArr = getPageScroll();
			left = pageArr[0] + (document.body.offsetWidth - ajaxPopDiv2.offsetWidth) /2;
			top  = pageArr[1] + (document.body.offsetHeight - ajaxPopDiv2.offsetHeight)/2;
		}else{ //down
			left = pointo.x;
			top  = pointo.y+subobj.offsetHeight + 1;
		}
	}
	if (isShade){
		ajaxBackgroundDiv.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=50,finishOpacity=50);";
	}
}


//
function getPageScroll(){
  var xScroll = 0;
  var yScroll = 0;
  if (self.pageXOffset) {
    xScroll = self.pageXOffset;
  } else if (document.documentElement && document.documentElement.scrollLeft){   // Explorer 6 Strict
    xScroll = document.documentElement.scrollLeft;
  } else if (document.body) {// all other Explorers
    xScroll = document.body.scrollLeft;
  }
  
  if (self.pageYOffset) {
    yScroll = self.pageYOffset;
  } else if (document.documentElement && document.documentElement.scrollTop){   // Explorer 6 Strict
    yScroll = document.documentElement.scrollTop;
  } else if (document.body) {// all other Explorers
    yScroll = document.body.scrollTop;
  }
  
  arrayPageScroll = new Array(xScroll,yScroll) 
  return arrayPageScroll;
}

/*
*	AJAX
*/
function ajaxWindowPop(obj,divname,noShade){
	if (divname =="" || divname == null ) divname = ajaxPopDiv;
	displaySelectArea(obj,"none");
	divname.style.display='';
	if ( !noShade){
		ajaxBackgroundDiv.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=50,finishOpacity=50);";
	}
}

function ajaxWindowClose(obj,divname,cleanContainer){
	try{
		displaySelectArea(obj,"");
		if (typeof(divname)!= "object" || divname == "" || divname == null )
		{
			divname = ajaxPopDiv;
			ajaxPopContent.innerHTML="Loading...";
		}
		else if(typeof(cleanContainer) == "object")
		{
			ajaxPopContent2.innerHTML="Loading...";
		}
		divname.style.display='none';	
		ajaxBackgroundDiv.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=100,finishOpacity=100);";
	}catch(ex){
	}
	//alert(ajaxBackgroundDiv.name);
}
//display | hidden the select of HTML
function displaySelectArea(obj,display){
	try{
		obj=document.all.item(obj);	
		if(obj != null && obj !="null"){
			for (var i=0;i<obj.elements.length;i++){
				var e = obj.elements[i];
				if(e.type == "select-one" || e.type == "text/x-scriptlet"){
					e.style.display = display;
				}
			}
		}
	}catch(ex){
	}
}

function ajaxGetXY(aTag)
{
	var oTmp = aTag;
	var pt = new getPoint(0,0);
	do{
		pt.x += oTmp.offsetLeft;
		pt.y += oTmp.offsetTop;
		oTmp = oTmp.offsetParent;
	}while(oTmp.tagName!="BODY");
	return pt;
}

function getPoint(iX, iY)
{
	this.x = iX;
	this.y = iY;
}

//================End ajax=================


//====================================
/**
 * @file            control.js
 * @description     call ajaxContentWindows2()
 * @author          hhping <hh_ping@163.com>
 * @date            2007-10-06 hhping
 * @version         1.0.0
**/

var inputListObj =
{

	objectForm  : "",         		//FORM object
	objectText  : Object(),    		//text input box object
	objectValue : Object(),	 		//select value object
	objectList  : "",    			//div name of down list
	objectClew  : null,				//prompt object
	strListTitle: "",				//prompt title
	strListUrl  : "",				//search url of down list
	intListNum  : 10,				//page number of down list
	intListDisSce : 400,            //delay time of down list ,second
	objectTimeOut : null,			//counter objectr
	intPageNo   : 1 ,               //is display page number
	displaySelect : true,           //hidden other select of HTML
	intDisplayWidth: 230,			//width of down list
	
	
	/**
	  * init
	  * @objForm   object   Form obj
	  * @objText   object   text input box object
	  * @objValue  object   hidden value box object
	  * @strUrl    string   url
	  * @seturlParam  string   action 
	  * @objClew   object   clew object
	  * @strTitle  string   pop window title
	  * @disSelect boolean   display or hide other select of HTML
	  * @strContainer string get container
	  * @public
	  */
	init : function (objForm,objText,objValue,strUrl,urlParam,objClew,strTitle,disSelect,strContainer)
	{
		try
		{
			if(typeof(objForm) != "" && typeof(objText) == "object")
			{
				objectForm	= objForm;
				objectText  = objText;
				objectValue = objValue;
				objectClew  = objClew
				strListTitle= strTitle;
				strListUrl  = strUrl;
				seturlParam = urlParam;
				displaySelect = disSelect;
				
				if(objectText.style.width != "" )
				{
					this.intDisplayWidth = 	objectText.style.width;
				}
				
				//AJAX
				if (!typeof(strContainer) == "undefined")
				{
					ajaxContentWindows3(objectForm,this.intDisplayWidth,25,false,strContainer);
				}
				else if(typeof(ajaxPopDiv2)=="undefined")
				{
					ajaxContentWindows3(objectForm,this.intDisplayWidth,25,false,'ajaxPopDiv2');
				}
				
				//objectText.onblur = "onblur";
				
			}
			else
			{
				this.displayError("error of init.");
			}
		}
		catch(ex){
			this.displayError(ex);
		}
	},
	
	/**
	  * @pageno 
	  * @public
	  */
	input : function (pageno)
	{
		try{
			//alert (pageno);
			if( typeof(pageno) == "number")
			{
				intPageNo = pageno;
			}
			if(typeof(objectValue) == "object")
			{
				objectValue.value = "";
			}
			this.checkValue();
			if ( objectText.value.replace(/[\s]+/g,"") != "")
			{
				if(this.objectTimeOut != null)
					clearTimeout(this.objectTimeOut);
				
				this.objectTimeOut = window.setTimeout (this.getDataUrl,this.intListDisSce);
			}
			else
			{
				ajaxWindowClose(objectForm,ajaxPopDiv2,ajaxPopContent2);
			}
		}
		catch(ex)
		{
			this.displayError(ex + "：error "+ objectText.value,true);
		};
	},
	
	/**
	  * event
	  *
	  * @private
	  */
	getDataUrl : function()
	{
		try{
			clearTimeout(this.objectTimeOut);
			if ( objectText.value.replace(/[\s]+/g,"")!="")
			{
				ajaxLoadPage(strListUrl,"act="+seturlParam+"&sect=" + encodeURIComponent(objectText.value) + "&page=" + this.intPageNo ,"GET",ajaxPopContent2);
				ajaxWinPop(objectForm,objectText,'downleft',0,0,false,strListTitle,this.displaySelect);
			}
			//alert(this.displaySelect);
		}
		catch(ex)
		{
			this.displayError(ex + "：error of object.");
		}
	},
	
	/**
	 * select item
	 * @public
	 */
	selectItem : function(name,value)
	{
		try{
			objectText.value = name;
			if(typeof(objectValue) == "object")
			{
				objectValue.value = value;
				this.checkValue();
			}
			this.listClose();
		}
		catch(ex){}
	},
	
	onblur : function()
	{
		if(typeof(objectValue) == "object")
		{
			objectValue.value = "";
			var getvalues=ajaxLoadPage(strListUrl,"act="+seturlParam+"check&sect=" + encodeURIComponent(objectText.value) ,"GET", getvalues ,false,true);
			if (typeof(getvalues) != "undefined" &&  getvalues>0 )
			{
				if(typeof(objectValue) == "object")
				{
					objectValue.value = getvalues ;
				}
			}
			this.checkValue();
		}
	},
	
	
	/**
	 * close down list
	 * @public
	 */
	listClose : function()
	{
		ajaxWindowClose(objectForm,ajaxPopDiv2,ajaxPopContent2);
	},
	
	/**
	 * check 
	 * @public
	 */
	checkValue : function()
	{
		if(typeof(objectValue) == "object")
		{
			if (objectValue.value != "")
			{
				this.setClew(true);
			}
			else
			{
				this.setClew(false);
			}
		}
	},
	
	/**
	 * clew
	 * @private
	 */
	setClew  : function (bolVar)
	{
		if (bolVar)
		{
			objectClew.innerHTML = "<font color=green><img src='images/yes.gif' align='absmiddle'/></font>";
		}
		else
		{
			objectClew.innerHTML = "<font color=green><img src='images/no.gif' align='absmiddle'/></font>";
		}
	},
	
	//clew error information
	displayError : function(error,isNotDis)
	{
		if(!isNotDis)
		{
			alert(error);
		}
	}
	
}

//var test = inputListObj;
//test.init();

//=================End =====================

//
function checkMsgPop(){
    //30 sec.
	checkNewMsg();
	window.setInterval("checkNewMsg()",50000);
}

//key event================================
function keyDown(e) { 
	if (setNS4) { 
		var nKey=e.which; 
		var ieKey=0;
		var Key = nKey
	}
	if (setIE4) { 
		var ieKey=event.keyCode; 
		var nKey=0;
		var Key = ieKey
	}
	//alert("nKey:"+nKey+" ieKey:" + ieKey) 
}
  
// key
function userKeyDown()
{
	document.onkeydown = keyDown;
	if (setNS4) document.captureEvents(Event.KEYDOWN);
}

//=============================

//hide right mouse menu

function hideMenu(){
  return false;
}

//document.oncontextmenu = hideMenu ;
window.onerror = function(){return false;}
-->