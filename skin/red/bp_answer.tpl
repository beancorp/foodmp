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
<link type="text/css" href="/skin/red/css/race.css" rel="stylesheet" media="screen" />
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
	
	{/literal}{if $req.answer_info.is_correct}{literal}
			myPlaySound();
	{/literal}{/if}{literal}
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
<script type="text/javascript" src="/skin/red/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/skin/red/js/niftyplayer.js"></script>
<script type="text/javascript">
var cisplay = false;
var t1 = null;
$(document).ready(function () {
	{/literal}{if $req.answer_info.is_correct}{literal}
			//myPlaySound();
	{/literal}{/if}{literal}
});

function replay(){
  try{
	if(niftyplayer('niftyPlayer1').getState()=='finished'){
	   niftyplayer('niftyPlayer1').play();
	   cisplay = true;
	}  
  }catch(ex){}
} 

function myPlaySound(){
	if(cisplay){
		niftyplayer('niftyPlayer1').pause();
		cisplay = false;
		window.clearInterval(t1);
	}else{
		niftyplayer('niftyPlayer1').play();
		cisplay = true;
		t1 = window.setInterval("replay()",500); 
	}
	
}
</script>

{/literal}
{literal}


{/literal}  

<div id="container" class="soc-race-box">
    <a class="map_banner" id="map_banner" name="map_banner" href="/soc.php?cp=race" title="Go to leaderboard">
        <img border="0" src="/skin/red/images/race/login-min-bg.gif" alt="Go to leaderboard">
    </a>
	<object style="position:absolute;left:-9999px;" id="niftyPlayer1" width="1" align="" height="1" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
      <param value="/skin/red/js/niftyplayer.swf?file=/upload/media/bp_correct.mp3&as=0" name="movie">
      <param value="high" name="quality">
      <param value="#FFFFFF" name="bgcolor">
      <embed width="165" align="" height="37" pluginspage="http://www.macromedia.com/go/getflashplayer" swliveconnect="true" type="application/x-shockwave-flash" name="niftyPlayer1" bgcolor="#FFFFFF" quality="high" src="/skin/red/js/niftyplayer.swf?file=/upload/media/bp_correct.mp3&as=0">
  </object>
	<div class="slideBody" style="display:{$req.sllerdisp};">
				<div class="soc-race-title"><h1>SOC Race Bonus Point Question</h1>
                    <div class="clear"></div>
                </div>
				<div class="block-login">
				<div class="login-bg">
    			<form id="admin" action="" method="POST">
					<input type="hidden" value="answer" name="cp"/>
					<input type="hidden" value="{$req.question_info.id}" name="question_id"/>
					<input type="hidden" value="{$search_type}" name="search_type" id="search_type"/>
					<fieldset>
						<ul class="answer-ul">
                        	<!--<li><label>The Soc Race Bonus Points</label></li>-->
                        	<li>
                            <label>
							{if $req.answer_info.is_correct}
                            Congratulations your answer is correct!<br />{$req.site_info.point} Points have been added to your SOC Race TPS.
                            {else}
							Unfortunately your answer was incorrect.<br />
							You did not receive any points this round.
                            {/if}
							</label>
                            </li>
                        	<li><label>                            
							{if $req.answer_info.is_correct}
                                Answer : 
                                {foreach from=$req.answer_info.list item=l key=k}
                                {if $k > 0},{/if}{$l.answer}     
                                {/foreach}
                            {else}
                            	Correct Answer : 
                                {foreach from=$req.correct_answer.list item=l key=k}
                                {if $k > 0},{/if}{$l.answer}     
                                {/foreach}
                            {/if}
                            </label></li>
							
                        	<li class="last"><a class="view-btn" href="/soc.php?cp=listpoints"><span>View your scoreboard!</span></a></li>
							
						</ul>
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