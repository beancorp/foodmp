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
<link type="text/css" href="/skin/red/css/race.css" rel="stylesheet" media="screen" />
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

<script src="/js/intselect.js"></script>
{/literal}
{literal}


{/literal}
<script type="text/javascript">
{literal}
var SysSecond;
var InterValObj;
$(document).ready(function () {
	SysSecond = parseInt({/literal}{$req.site_info.left_time}{literal});  
	InterValObj = window.setInterval(SetRemainTime, 1000);  
});
 
function SetRemainTime() {
	if (SysSecond > 0) {
		SysSecond = SysSecond - 1;
		var second = Math.floor(SysSecond % 60);             //second    
		var minite = Math.floor((SysSecond / 60) % 60);      //minute 
		var hour = Math.floor((SysSecond / 3600) % 24);      //hour 
		var day = Math.floor((SysSecond / 3600) / 24);       //year
		if(second < 10) {
			second = '0' + second;
		} 
		if(minite < 10) {
			minite = '0' + minite;
		} 
		if(hour < 10) {
			hour = '0' + hour;
		} 
		$("#timer").html(hour + ":" + minite + ":" + second);
	} else {
		window.clearInterval(InterValObj); 
		
		$("#timer").html("00:00:00");
		{/literal}{if !$req.preview}{literal}
		$.post('/bp_question.php',{'cp':'finish','is_correct':'0','question_id':{/literal}'{$req.question_info.id}'{literal},'site_id':{/literal}'{$req.site_info.id}'{literal},'StoreID':{/literal}'{$session.StoreID}'{literal}},function(data){
				if(data) {
					//alert(data);								
				}                     
		});	
		{/literal}{/if}{literal}
		
		alert("Time limit has expired.");
		
		{/literal}{if !$req.preview}{literal}
		location.href = '/soc.php?cp=listpoints';
		{/literal}{/if}{literal}
	}
} 

function checkForm(obj)
{
	if($("input[@name='answer[]'][@checked]").val()){
		return true;
	} else {
		alert("Please select the answer.");
		return false;
	}
}
{/literal}
</script>    

<div id="container"  class="soc-race-box">
    <a class="map_banner" id="map_banner" name="map_banner" href="/soc.php?cp=race" title="Go to leaderboard">
        <img border="0" src="/skin/red/images/race/login-min-bg.gif" alt="Go to leaderboard">
    </a>
    
	<div class="slideBody" style="display:{$req.sllerdisp};">
    			<div class="soc-race-title"><h1>SOC Race Bonus Point Question</h1><label id="timer" class="timer"></label>
                <div class="clear"></div>
                </div>
				<div class="block-login">
				<div class="login-bg">
    			<form id="admin" action="" method="POST" onsubmit="return checkForm(this);">
					<input type="hidden" value="answer" name="cp"/>
					<input type="hidden" value="{$req.question_info.id}" name="question_id"/>
					<input type="hidden" value="{$search_type}" name="search_type" id="search_type"/>
					<fieldset>
						<div class="question-title">
							<h2>Question</h2>
							<p>{$req.question_info.question}</p>
						</div>
						<ul class="question-ul">
                        	<h2>Answer</h2>
                        	
							
								<!--<select class="text" name="user_type" id="lg_select">
									<option id="buy-and-sell" value="0" {if $search_type eq ''}selected{/if}>Buy & Sell</option>
									<option id="option2" value="1">Real Estate</option>
									<option id="option3" value="2">Auto</option>
									<option id="option4" value="3" {if $search_type eq 'job'}selected{/if}>Job Market</option>
									<option id="option4" value="5">Food & Wine</option>
								</select>-->
                                {foreach from=$req.question_info.answer_list item=l}
                                 <li>
                                 <input type="{$req.question_info.type}" name="answer[]" value="{$l.id}" /><span><span>{$l.pre_index}</span> {$l.answer} </span>
                                 </li>
                               {/foreach}
							<div class="clear"></div>
						</ul>
						<div class="clear"></div>
					</fieldset>
					<fieldset>
						<button class="submit-answer" type="submit" {if $req.preview}disabled="disabled"{/if}><span><span>Answer Now</span></span></button>
					</fieldset>
				</form>
				</div>
				</div>
			</div>
</div>
	<div style="clear:both"></div>
	

 
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