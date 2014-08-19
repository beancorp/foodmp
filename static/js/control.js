//=================ajax==================
/*
url 
request 
method 
container 
ispop default false
isvar default false
loading default false
*/
function ajaxLoadPage(url,request,method,container,ispop,isvar, loading){
	//alert(container);
	method=method.toUpperCase();
	var loading_msg='loading ...';
	var loader	= null;
	var popStatu = ""; //string of get pop page 
	
	
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		loader = new XMLHttpRequest();
		if (loader.overrideMimeType) {
			loader.overrideMimeType('text/xml');
		}
	} else if (window.ActiveXObject) { // IE
		var versions = [ 'MSXML6.XMLHTTP', 'Microsoft.XMLHTTP', 'MSXML5.XMLHTTP', 'MSXML4.XMLHTTP', 'MSXML3.XMLHTTP', 'MSXML2.XMLHTTP', 'MSXML.XMLHTTP'];

		for (var i = 0; i < versions.length; i ++ )
		{
			try
			{
			  loader = new ActiveXObject(versions[i]);
			  break;
			}
			catch (ex)
			{
			  continue;
			}
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
	//alert(url);

	loader.open(method,url,!isvar);
	
	if (method=="POST")	{
		loader.setRequestHeader("Content-Length",request.length);
		loader.setRequestHeader("Cache-Control","no-cache");
		loader.setRequestHeader('Content-Type',  "text/xml");
		//loader.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	}
	loader.onreadystatechange=function(){
		if (loader.readyState==1 && loading && !isvar && !ispop){
			container.innerHTML=loading_msg;
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
				try{
				//HTML
				//handleResponse(loader.responseXML);
				container.innerHTML=loader.responseText;
				filtrate_css(loader.responseText);
				//var _d = document.createElement("style");		
				//_d.innerHTML=".aa{}";
				//var html_doc = document.getElementsByTagName( 'HEAD')[0];
				
				//container.appendChild(_d);
				//container.innerHTML="<style>.11111{}</style>";
				//alert(loader.responseText);
				//alert(html_doc);
				//alert(container.innerHTML);
				//insert_css(container,loader.responseText);
				//alert(loader.responseXML.documentElement);
				
				}catch(ex)
				{
					//alert(ex);
				}
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
* 
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
*  obj       FormObject    
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
			this.displayError(ex + ":error "+ objectText.value,true);
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
			this.displayError(ex + ":error of object.");
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



function filtrate_css(str) {
	try{
		if(document.all){
			if(str.length > 15){
				var begin 	= str.indexOf('<style>') + 7 ;
				var end 	= str.indexOf('</style>') - 9;
				
				if(end>0){
					var temp = str.substr(begin,end);
					if (temp.length>0){
						
						window.style= temp;
						document.createStyleSheet("javascript:style");
						
					}
				}
			}
		}
		
	}catch(ex){
		alert(ex);
	}
	
    return false;
}

var js;
function include_js(file) {
    var html_doc = document.getElementsByTagName( 'HEAD')[0];
    js = document.createElement( 'script ');
    js.setAttribute( 'type ',  'text/javascript ');
    js.setAttribute( 'src ', file);
    html_doc.appendChild(js);
    js.onreadystatechange = function () {
        if (js.readyState ==  'complete') {
            alert( 'JS onreadystate fired ');
        }
    }
    js.onload = function () {
        alert( 'JS onload fired ');
    }
    return false;
}


function selectAll(selNmae,obj){
	var subobj = document.getElementsByName(selNmae);
	for (var i = 0; i < subobj.length; i++){
		subobj[i].checked = obj.checked;
	}
}

function checkSelect(selNmae){
	var subobj = document.getElementsByName(selNmae);
	var selected =0;
	for (var i = 0; i < subobj.length; i++){
		if(subobj[i].checked){
			selected ++;
		}
	}
	return selected;
}

function deleteList(selNmae, clew1, clew2){
	var booleanResult	=	false;
	if(clew2 != ''){
		if(checkSelect(selNmae) == 0 ){
			alert(clew1);
		}else if(confirm(clew2)){
			booleanResult	=	true;
		}
	}else{
		if(confirm(clew1)){
			booleanResult	=	true;
		}
	}
	return	booleanResult;
}

//var test = inputListObj;
//test.init();

//=================End =====================


// images function
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->

<!-- menu
function check(){
	document.form3.business_name.value="";
}

function check1(){
	document.form1.uname.value="";
}

function check2(){
	document.form1.password.value="";
}

function check3(){
	document.form4.product_name.value="";
}

function setFocus(){
	document.getElementById('password').focus();
}


//check text of input 
function defaultClew(objName, hasFocus, clew){
	if (hasFocus){
		if ($(objName).val() == clew ){
			$(objName).val('');
		}
	}else{
		if ($(objName).val() == '' ){
			$(objName).val(clew);
		}
	}
}

//window onload
function windowOnload(fn){
	if (document.all){
		window.attachEvent('onload', fn);
	}else{
		window.addEventListener('load',fn,false);
	}

}


function jquery_check_search(url, cp, formID){
	if ( typeof(url) == 'string' &&  url != ''){
		$.post(url, { 'cp': cp, 'category': $('#se_category').val(),'keyword': $('#keyword_k').val(), 'formID': formID }, function(data,status){
			if ( status == 'success' ){
				try{
					eval(data);
				}catch(ex){alert(ex);}
			}
		}
		);
	}
	return false;
}


function clewMethod(data, Obj, params){
	if(typeof(params) == 'object'){
		var ObjClewID = "#"+ Obj + "_clew";
		$(ObjClewID).css('display','');
		for (i=0; i< params.length; i++){
			if(data == params[i][0]){
				$(ObjClewID).removeClass();
				$(ObjClewID).html(params[i][1]).addClass(params[i][2]);
				$(ObjClewID).fadeTo(200,0.1,function(){$(this).fadeTo(900,1);});
				data == 'existed' ? $('#' + Obj).val('') : '';
			}
		
		}
	}
}

/*
function jquery_return_js(url, cp, sector){
	var boolResult = false;

	if ( typeof(url) == 'string' &&  url != ''){
		$.post(url, { 'cp': cp, 'sector': $('#sector').val() }, function(data,status){
		  //$.post(url, eval(jquery_getFormValue(formID, ", 'cp':'" + cp + "'")), function(data,status){
			if ( status == 'success' ){
				try{
					alert(data);
				eval(data);
				}catch(ex){alert(ex);}
			}
		}
		);
		
	}
	return boolResult;
}

function jquery_getFormValue(formID, params){
	var strResult = '';
	var tempObj = $("#" + formID).get(0);
	
	for (var i=0; i< tempObj.elements.length; i++ ){
		if(typeof(tempObj.elements[i]) == 'object'){
			if(tempObj.elements[i].name != 'cp' && typeof(tempObj.elements[i].name) != 'undefined' && typeof(tempObj.elements[i].id) != 'undefined' && tempObj.elements[i].id != ''){
				//strResult += "'"+tempObj.elements[i].name+"': \""+$('#'+tempObj.elements[i].id).val()+"\", ";
				strResult += ", '"+tempObj.elements[i].name+"': $('#"+tempObj.elements[i].id +"').val()";
			}
		}
	}
	if(strResult != ""){
		strResult = strResult.substr(1,strResult.lenght);
	}
	
	if (typeof(params) != 'undefined'){
		strResult += params;
	}
	return "{" + strResult + "}";
}
*/

//display loading when combox changed.
function loadingAddOfCombox(objHTML){
	if(typeof(objHTML) == 'string' && objHTML != ''){
		var arrObjHTML	=	objHTML.split('_');
		var subObjHTML	=	'#' + arrObjHTML[0] + "_" + (parseInt(arrObjHTML[1])+1) ;
		if (typeof( $(subObjHTML) ) == 'object') {
			$(subObjHTML).empty(); 
			$("<option value='-100'>Loading...</option>").appendTo(subObjHTML);
		}
	}

}

//Want any text of loading for comboxs .
function loadingOfCombox(objHTMLs){
	if(objHTMLs != ''){
		var arrObjHTMLs	=	objHTMLs.split(',');
		if (arrObjHTMLs.length > 0){
			for (i =0; i< arrObjHTMLs.length ; i++){
				var tempStr = arrObjHTMLs[i].toString();
				$(tempStr).change(function(){loadingAddOfCombox(this.id);});
			}
		}
	}
}

//linkage function by jquery
function jquery_linkage(objHTML, url, cp, level, addOptions, hasLoading){
	var obj	=	$('#'+objHTML);
	if ( typeof(url) == 'string' &&  url != ''){
		hasLoading ? loadingAddOfCombox(objHTML) : '';
		$.post(url, { 'cp': cp, 'values': obj.val(), 'objHTML': objHTML, 'level': level , 'addOptions': addOptions}, function(data,status){
			if ( status == 'success' ){
				//alert(data);
				try{
				eval(data);
				}catch(ex){;}
			}
		}
		); 
	}
}

// FCKEditor content change function
function autoChangeEdit(objForm,arrItems)
{
	try{
		if (arguments.length > 1) {
			  for (var i = 1; i < arguments.length; i++){
				objForm[arguments[i]].value = __get_ed_text(arguments[i]);
			  }
		}
	}catch(ex){ alert(ex);}

}
function autoLoadEdit(arrItems){
	try{
		if (arguments.length > 0) {
			  for (var i = 0; i < arguments.length; i++){
				__set_ed_text(arguments[i],xajax.$(arguments[i]));
			  }
		}
	}catch(ex){ alert(ex);}
}

function __get_ed_text(editor_name)
{
    var oEditor = FCKeditorAPI.GetInstance(editor_name) ;
    if (oEditor.GetXHTML(true)) {
        return oEditor.GetXHTML(true);
    }
    else return '';
}

function __set_ed_text(editor_name,valueObject)
{
    var oEditor = FCKeditorAPI.GetInstance(editor_name);
	FCKeditorAPI.GetInstance(editor_name).SetHTML(valueObject.value);
}