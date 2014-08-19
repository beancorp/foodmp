<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$pageTitle}</title>
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
{if $is_home eq 1 && $source eq ''}{literal}
<script language="Javascript" src="http://gd.geobytes.com/Gd?after=-1"></script>
<script language="javascript">
/*
if(typeof(sGeobytesLocationCode)!="undefined" && sGeobytesLocationCode.indexOf('AU')==0)
{
	document.write("<META HTTP-EQUIV='Refresh' CONTENT='0; URL=http://socexchange.com.au'>");
}
*/
</script>
{/literal}{/if}
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="{$keywords}" />
<meta name="Description" content="{$description}" />
{if $base eq true}
<base href="{$Protocol}://{$HostName}/" />
{/if}
<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<!--[if lt IE 7]><script defer type="text/javascript" src="/skin/red/js/pngfix.js"></script><![endif]-->
<!--[if IE 7]><link type="text/css" href="/skin/red/css/ie.css" rel="stylesheet" media="screen" /><![endif]-->
<!--[if lt IE 7]><link type="text/css" href="/skin/red/css/ie6.css" rel="stylesheet" media="screen" /><![endif]-->
<script type="text/javascript" src="/skin/red/js/niftycube.js"></script>
<script type="text/javascript" src="/skin/red/js/swfobject.js"></script>
<script type="text/javascript" src="/skin/red/js/ie-select-width-fix.js" ></script>
<script src="/skin/red/js/jquery-1.js" type="text/javascript"></script>
<script src="/skin/red/js/jquery.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
if (document.all){
	window.attachEvent('onload',winOnload);
}else{
	window.addEventListener('load',winOnload,false);
}
$(window).resize(function(){
	if($(window).height()-$('body').height()==0){return 0;}
	if($(window).height()-$('body').height()>0){
		$('#footer').css('height',($('#footer').height()+$(window).height()-$('body').height()));
	}else{
		$('#footer').css('height','auto');
	}
});

function winOnload(){
Nifty("div#seller-info1","big tl bl");
Nifty("div#seller-info2","big tl bl ");
Nifty("div#seller-info3","big tl bl br");
Nifty("div#paging-wide","medium bl br");
Nifty("div#paging","medium bl br");
Nifty("ul#loggedin","medium tr br");
if($(window).height()-$('body').height()>0){
	$('#footer').css('height',$('#footer').height()+$(window).height()-$('body').height());
}
//for state search page
Nifty("form#searchside","big br");
Nifty("h2#location","medium");
//for signup page
Nifty("div#grey-inside","big bl");
//for college search
Nifty("form.unisearch","big br tr");
$('#slickbox').hide();
{/literal}
{$onLoad}
{$stateOnLoad}
{literal}

	$("#foodwine_div").show();
}

function selectCollege(obj,params)
{
	try{
		ajaxLoadPage('/soc.php','&act=signon&step=college&SID='+params,'GET',document.getElementById(obj),false,false,true);
	}
	catch(ex)
	{
	
	}
}
function selectCollegebyName(obj,params)
{
	var objdiv = obj;
	$.get('/soc.php?statename='+params,{act:'signon',step:'getCollege'},function (data){$('#'+objdiv).html(data);new YAHOO.Hack.FixIESelectWidth('collegeid');});
}
function rightseletCollege(obj,params){
	$.get('/soc.php?statename='+params,{act:'signon',step:'getCollege2',obj:'collegeid2'},function (data){$('#collegeid2').html(data);new YAHOO.Hack.FixIESelectWidth('collegeid2');});
}
</script>
{/literal}
<script language="javascript" src="/js/control.js"></script>
<script language='javascript' src='/wasp/wasp.js'></script>
{$xajax_Javascript}
</head>
<body>

{if $notRoot}
	{include file='../index_top_banner.tpl'}
{else}
	{include file='index_top_banner.tpl'}
{/if}
    
    <!-- end banner -->
{literal}
<script>
var int =0;
function changetypetab(type){
	switch(type){
		case 'buySeller':
			$('#home_top_nav').attr('src','/skin/red/images/main/buy&seller-top.jpg');
			//int=window.setInterval("moveposition(0)",30);
			var psleft = 0;
			
		break;
		case 'realestate':
			$('#home_top_nav').attr('src','/skin/red/images/main/estate-top.jpg');
			var psleft = -731;
			//int=window.setInterval("moveposition(731)",30);
		break;
		case 'automotive':
			$('#home_top_nav').attr('src','/skin/red/images/main/automotive-top.jpg');
			var psleft = -1462;
		break;
		case 'careers':
			$('#home_top_nav').attr('src','/skin/red/images/main/careers-top.jpg');
			var psleft = -2193;
		break;
		case 'foodwine':
			$('#home_top_nav').attr('src','/skin/red/images/main/food&wine-top.jpg');
			var psleft = -2924;
		break;
	}
	$("#scroll_div").stop();
	$("#scroll_div").animate({left:"-"+Math.abs(psleft)+"px"},500);
}
function moveposition(max){
	var pos = parseInt($('#scroll_div').css('left').replace('px',''));
	if(Math.abs(pos)<Math.abs(max)){
		$('#scroll_div').css('left',(pos-43)+'px');
		return true;
	}else if(Math.abs(pos)>Math.abs(max)){
		$('#scroll_div').css('left',(parseInt(pos)+43)+'px');
		return true;
	}else if(Math.abs(pos)==Math.abs(max)){
		int = window.clearInterval(int);
		return false;
	}
	
}

function gotowebsite(obj,divobj){
	var locurl ="";
	switch(obj){
		case 'estate_select':
		locurl = "/estate/index.php?cp=search&state_name=";
		break;
		case 'auto_select':
		locurl = "/auto/index.php?cp=search&state_name=";
		break;
		case 'job_select':
		locurl = "/job/index.php?cp=search&state_name=";
		break;
		case 'foodwine_select':
		locurl = "/foodwine/index.php?cp=home&state_name=";
		break;
	}
	//location.href = locurl+$('#'+obj).val();
	if($('#'+obj).val()!="" && $('#'+obj).val()!=null){
		$('#'+divobj).css('background','url(/skin/red/images/main/select_but1.jpg) left top no-repeat');
		location.href = locurl+$('#'+obj).val();
	}else{
		$('#'+divobj).css('background','url(/skin/red/images/main/select_but1.jpg) left bottom no-repeat');
		alert("Please select a state.");
	}
}

$(function(){
    //var defaultStateSelects = ['estate_select','auto_select', 'job_select', 'foodwine_select'];
    //for(var i = 0; i < defaultStateSelects.length; i++){
       //$($('#foodwine_select option')[0]).remove();
       $('#foodwine_select option').each(function(){
           if($(this).attr('value') == ''){
               var txt = $(this).html();
               $('#divfoodwine_select span').html(txt);
               $(this).attr('selected', 'selected');
               return;
           }
       });
    //}
});
</script>
<style type="text/css">
.scroll{clear:left;overflow:hidden;position:relative;width:100%;height:311px;}
.scrollContainer{width:3655px;position:relative;top:0;left:0;height:311px;}
.scrollContainer div{float:left;width:731px;height:311px;}
.scrollContainer div#tag_foodwine{background:url(/skin/red/images/main/food&wine.jpg);}
.scrollContainer div#tag_careers{background:url(/skin/red/images/main/careers.jpg);}
.scrollContainer div#tag_automotive{background:url(/skin/red/images/main/automotive.jpg);}
.scrollContainer div#tag_realesate{background:url(/skin/red/images/main/realestate.jpg);}
.scrollContainer div#tag_buySeller{background:url(/skin/red/images/main/buy&seller.jpg);}
div.divSlt{ height:auto;position:relative; }
div.divSlt span{ display:block; width:208px;font-size:19px;padding-left:11px;text-indent:6px; cursor:pointer;line-height:20px;height:33px;padding-top:10px;} 
div.divSlt ul{ display:block; background-color:#FFFFFF;margin:2px; width:212px; border:1px solid #CCC; top:36px;
border-top:0px; padding:0px; list-style:none; position:absolute;z-index:1000;} 
div.divSlt ul li{ text-indent:5px; margin-left:5px; height:20px;font-size:14px; line-height:20px; cursor:pointer; } 
</style>
<script src="/js/intselect.js"></script>
{/literal}
    

<div id="container" style="min-height:568px;_height:568px;">
	<div style="float:left;margin: 21px 8px 12px;"><img src="/skin/red/images/main/home_left.jpg" border="0" usemap="#Map_category"/>
      <map name="Map_category" id="Map_category">
        <area shape="rect" coords="21,235,170,272" href="/soc.php?cp=home&onlinestore=1" />
      </map>
</div>
	<div style="float:left;margin: 21px 0 12px 15px; position:relative;">
    <img src="/skin/red/images/main/home_right.jpg" border="0" usemap="#Map_register"/>
      <map name="Map_register" id="Map_register">
        <area shape="rect" coords="23,235,190,272" href="javascript:void(0);" onclick="gotowebsite('foodwine_select','foodwine_div');" />
      </map>
      <div style="background:url(/skin/red/images/main/select_but1.jpg) no-repeat; top:150px; left:2px;height:39px;width:218px;margin:15px 22px; position:absolute; overflow:visible; display:none" id="foodwine_div">
				<select id="foodwine_select" name="foodwine_select" style="border:0;height:45px; width:288px;background:url(/skin/red/images/main/select_but1.jpg);">
				<option value="">Select a state</option>
				{foreach from=$statesList item=states}
				<option value="{$states.stateName}">{$states.description}</option>
				{/foreach}
				</select>
				</div>
</div>
	<div style="clear:both"></div>
	
	
 </div>	

 
	{if $notRoot}
		{include file='../index_bottom_banner.tpl'}  
	{else}
		{include file='index_bottom_banner.tpl'}  
	{/if}
  
{$footer}



{if $is_home == 1}
{literal}
<script language="Javascript" type="text/javascript">
/*var version = 13
var cacheBust = '?version=' + version;
var base = new SWFObject('skin/red/assets/flashheader.swf'+ cacheBust, 'background', 545, 120, '6', 'FFFFFF', false);
base.addParam('salign', 't');
base.addParam('name', 'background');
base.addParam('menu', 'false');
base.addParam('scaleMode', 'noScale');
base.addParam('wmode', 'transparent');
base.addVariable('inBrowser', true);
base.write('flashheader');*/
</script>
{/literal}
{/if}
{literal}
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try{
	var pageTracker = _gat._getTracker("UA-5728313-1");
	pageTracker._trackPageview();
}catch(ex){}
</script>
{/literal}
</body>
</html>