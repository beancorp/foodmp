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

	$("#foodwine_div").show();
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
<script src="/js/intselect.js"></script>
{/literal}
{literal}


{/literal}
<script type="text/javascript">
var cisplay = false;
var t1 = null;
{literal}
	function usernameOnFocus(obj)
	{
		if(obj.value == 'Login') {
			obj.value = '';
		}
	}
	function usernameOnBlur(obj)
	{
		if(obj.value == '') {
			obj.value = 'Login';
		}
	}
	function passwordOnFocus(obj)
	{
		if(obj.value == 'bp password') {
			obj.value = '';
		}
	}
	function passwordOnBlur(obj)
	{
		if(obj.value == '') {
			obj.value = 'bp password';
		}
	}
	
	function pagefunc(p){
		$.post("/soc.php?cp=race",{full:"1",p:p},function(data){$('#content_2').html(data);});
	}
	
	function replay(){
	  try{
		if(niftyplayer('niftyPlayer1').getState()=='finished'){
		   niftyplayer('niftyPlayer1').play();
			$("#sound").removeClass("close");
		   cisplay = true;
		}  
	  }catch(ex){}
	} 

	function myPlaySound(){
		if(cisplay){
			niftyplayer('niftyPlayer1').pause();
			cisplay = false;
			$("#sound").addClass("close");
			window.clearInterval(t1);
		}else{
			niftyplayer('niftyPlayer1').play();
			cisplay = true;
			$("#sound").removeClass("close");
			t1 = window.setInterval("replay()",500); 
		}
		
	}
{/literal}
</script>    

<div id="container" class="soc-race-box">
    <a class="map_banner" id="map_banner" name="map_banner" href="/soc.php?cp=race" title="Go to leaderboard">
        <img border="0" src="/skin/red/images/race/login-min-bg.gif" alt="Go to leaderboard">
    </a>
	<div class="slideBody" style="display:{$req.sllerdisp};">
			<div class="soc-race-title">
            	<h1>SOC Race Bonus Point Question</h1><a id="sound" class="sound close" href="javascript:void(0);" onclick="javascript:myPlaySound();"></a>
            	<div class="clear"></div>
            </div>
        <object style="position:absolute;left:-9999px;" id="niftyPlayer1" width="1" align="" height="1" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
      <param value="/skin/red/js/niftyplayer.swf?file=/upload/media/iStock_000008438954Wav44100.mp3&as=0" name="movie">
      <param value="high" name="quality">
      <param value="#FFFFFF" name="bgcolor">
      <embed width="165" align="" height="37" pluginspage="http://www.macromedia.com/go/getflashplayer" swliveconnect="true" type="application/x-shockwave-flash" name="niftyPlayer1" bgcolor="#FFFFFF" quality="high" src="/skin/red/js/niftyplayer.swf?file=/upload/media/iStock_000008438954Wav44100.mp3&as=0">
  </object>
			<div class="block-login">
				<div class="login-bg">
    			<form id="admin" action="{$soc_https_host}login.php" method="POST">
					<input type="hidden" value="{$req.from}" name="from"/>
					<input type="hidden" value="bp_login" name="use"/>
					<fieldset>
						<ul class="unstyled">
                        	<!--<li style="padding-top:18px;"><label>What are you looking for?</label></li>
                        	<li style="text-align: center; padding: 11px 0pt 11px;"><label style="color:#F26521; text-align:center">{$lang.labelSelectMarketPlace}</label></li>
							<li>
                                {foreach from=$lang.seller.attribute item=l key=k}
                                 <div style="float:left; width:72px;">
                                 <span style="text-align:center; display:block">{$l.text}</span>
                                 <span style="text-align:center; width:72px; display:block;"><input type="radio" name="user_type" value="{$k}" {if $reg_attribute eq $k}checked{/if} onclick="changeUsertype('{$k}');"/></span>
                                 </div>
                               {/foreach}
							</li>-->
                            <div class="clear"></div>
							<li class="first"><p>Please login to answer this question... Good luck!</p></li>
							<li class="input-li"><label id="lab_loginfield">Email address</label>
								<div class="input-bg"><input class="text input" type="text" name="uname" value="Login" onfocus="usernameOnFocus(this)" onblur="usernameOnBlur(this)" /></div>
								<label>Password</label>
								<div class="input-bg"><input class="text input" type="password" name="password" value="bp password" onfocus="passwordOnFocus(this)" onblur="passwordOnBlur(this)" /></div>
								
								<input class="submit" type="submit" name="submit" value="Login" />
								
								<a href="#" title="Forgotten Password" class="red" onclick="javascript:window.open('/forgetpass.php','ForgetPassword','width=600,height=210,scrollbars=yes,status=yes')">Forgotten password?</a>
							</li>
							<li class="last">
								<p>This question value if answered correctly = <font class="font-bold">{$req.site_info.point} points</font><br />
					You will have a maximum time of <font class="font-bold">{if $req.site_info.max_minute}{$req.site_info.max_minute} minute{if $req.site_info.max_minute > 1}s{/if}{/if}{if $req.site_info.max_second} {$req.site_info.max_second} second{if $req.site_info.max_second > 1}s{/if}{/if}</font> to answer this question.</p><br />
                    			<p>This question can only be attempted once.</p>
							</li>
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