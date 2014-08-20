<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<title>{$pageTitle}</title>
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="fb:app_id" content="{$facebook.appID}"/>
<meta content="{if $req.info.facelike_title}{$req.info.facelike_title}{else}SOC exchange{/if}" property="og:title">
<meta content="{if $req.info.facelike_image}{$req.info.facelike_image}{else}{$smarty.const.SOC_HTTP_HOST}skin/red/images/logo-main.png{/if}" property="og:image">
<meta content="{$req.info.facelike_desc}" property="og:description">

<!--<meta name="Keywords" content="{$keywords}" />
<meta name="Description" content="{$description}" />-->
{if $base eq true}
<base href="{$Protocol}://{$HostName}/" />
{/if}
<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<!--[if lt IE 7]><script defer type="text/javascript" src="/skin/red/js/pngfix.js"></script><![endif]-->
<!--[if IE 7]><link type="text/css" href="/skin/red/css/ie.css" rel="stylesheet" media="screen" /><![endif]-->
<!--[if lt IE 7]><link type="text/css" href="/skin/red/css/ie6.css" rel="stylesheet" media="screen" /><![endif]-->
<script type="text/javascript" src="{$smarty.const.STATIC_URL}js/skin/red/niftycube.js"></script>
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

function winOnload(){
Nifty("div#seller-info1","big tl bl");
Nifty("div#seller-info2","big tl bl ");
Nifty("div#seller-info3","big tl bl br");
Nifty("div#paging-wide","medium bl br");
Nifty("div#paging","medium bl br");
Nifty("ul#loggedin","medium tr br");

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
	$.get('/soc.php?statename='+params,{act:'signon',step:'getCollege'},function (data){
		$('#'+objdiv).html(data);
		//new YAHOO.Hack.FixIESelectWidth('collegeid');
	});
}
function rightseletCollege(obj,params){
	$.get('/soc.php?statename='+params,{act:'signon',step:'getCollege2',obj:'collegeid2'},function (data){
		$('#collegeid2').html(data);
		//new YAHOO.Hack.FixIESelectWidth('collegeid2');
	});
}
function showmoreImage_fade(id,bl){
	var width = 232;
	var height = 232;
	var image = $('#'+id+' img');
	var re = /px/g;
	var imgwidth = parseInt(image.css('width').replace(re,""));
	var imgheight = parseInt(image.css('height').replace(re,""));
	if (imgwidth > imgheight){
	   if(imgwidth>width){
	    image.css('width',width+"px");
	    image.css('height',(width/imgwidth*imgheight)+"px");
	   }
	}
	else{
	   if(imgheight>height){
	    image.css('height',height);
	    image.css('width',(height/imgheight*imgwidth)+"px");
	   }
	}
	if(bl){
		$('#'+id).fadeIn();
		$('#'+id+"_2").fadeIn();
	}else{
		$('#'+id).fadeOut();
		$('#'+id+"_2").fadeOut();
	}
}
</script>
{/literal}
<script language="javascript" src="/js/control.js"></script>
<script language='javascript' src='/wasp/wasp.js'></script>
{$xajax_Javascript}
{literal}
<script type="text/javascript">
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script'));
</script>
{/literal}

<!--add get facebook login message by haydn.h at 20120306 -->
<script type='text/javascript'>
<!--//<![CDATA[
{literal}
window.fbAsyncInit = function() {
	try  
	{ 
		FB.Event.subscribe('edge.create', function(response) {		
			$.post('/facelikeajax.php',{'opt':'facelike','type':'like','like_type':{/literal}'{if $req.info.like_type}{$req.info.like_type}{else}other{/if}'{literal},'pid':{/literal}'{if $req.info.like_itemid}{$req.info.like_itemid}{/if}'{literal},'StoreID':{/literal}'{$req.info.StoreID}'{literal},'url':response},function(data){
					if(data) {
						//alert(data);								
					}                     
			});				
		}); 
	} catch(e) {   
	   //alert("FB"+":is undefined");   
	} 
	
	try  
	{ 
		FB.Event.subscribe('edge.remove', function(response) { 
			$.post('/facelikeajax.php',{'opt':'facelike','type':'unlike','like_type':{/literal}'{if $req.info.like_type}{$req.info.like_type}{else}other{/if}'{literal},'pid':{/literal}'{if $req.info.like_itemid}{$req.info.like_itemid}{/if}'{literal},'StoreID':{/literal}'{$req.info.StoreID}'{literal},'url':response},function(data){
					if(data) {
						//alert(data);								
					}                     
			});						
		});
	} catch(e) {   
	   //alert("FB"+":is undefined");   
	} 
}
{/literal}
{if ($ocp eq 'sellerhome' or $ocp eq 'buyerhome') and $smarty.session.fb.can}
{literal}
window.fbAsyncInit = function() {
	FB.init({
{/literal}
	appId: '{$facebook.appID}',
	status: false, 
	cookie: true,
	xfbml: true,
	oauth: true
{literal}
	});

	FB.Event.subscribe('auth.login', function(response) {
		window.location.href='/soc.php?cp=fbbundle';
	});
	
	FB.Event.subscribe('auth.statusChange', function(response) {
		if (response.status == 'connected') {
			window.location.href='/soc.php?cp=fbbundle';
		}
	});
};
{/literal}
{elseif $ocp eq 'customers_geton' or $act eq 'signon'}
{literal}
var fb_data = null;
var fb_get_number = 0;
window.fbAsyncInit = function() {
    FB.init({
{/literal}
        appId: '{$facebook.appID}',
{literal}
		status: true, 
        cookie: false,
        xfbml: false,
        oauth: true
	});

	FB.Event.subscribe('auth.login', function(response) {
		if (response.status == 'connected') {
			FB.api('/me', function(response) {
				addFacebookButton();
				fb_data = response;
				fb_get_number>1 ? inputFacebookMessage(fb_data) : fb_get_number ++;
			});
		}else{
			fb_get_number ++;
		}
	});
	FB.Event.subscribe('auth.statusChange', function(response) {
		if (response.status == 'connected') {
			FB.api('/me', function(response) {
				addFacebookButton();
				fb_data = response;
				fb_get_number>1 ? inputFacebookMessage(fb_data) : fb_get_number ++;
			});
		}else{
			fb_get_number ++;
		}
	});
};
function addFacebookButton(){
	$('#fb-login-button').html('<a class="fb_button fb_button_medium" href="javascript:void(0);" onclick="javascript:inputFacebookMessage(fb_data);"><span class="fb_button_text">Register with facebook</span></a>');
}

	{/literal}{if $ocp eq 'customers_geton'}{literal}
function inputFacebookMessage(data){
	try{
		$('#fb_id').val(data.id);
		$('#cu_username').val(data.email);
		$('#re_cu_username').val(data.email);
		$('#cu_name').val(data.username);
		if(data.gender == 'female') $('input[@type=radio][@name=gender]').attr('checked', 1);
	}catch(e){}
}
	{/literal}{elseif $act eq 'signon'}{literal}
function inputFacebookMessage(data){
	try{
		$('#fb_id').val(data.id);
		$('#bu_user').val(data.email);
		$('#re_bu_user').val(data.email);
		checkEmail();
		$('#bu_urlstring').val(data.username);
		checkWebsite();
		$('#bu_name').val(data.username);
		if($('input[@type=radio][@name=attribute][@checked]').val()==5){
			$('#bu_username').val(data.username);
			checkUsername();
		}
		if(data.gender == 'female') $('input[@type=radio][@name=gender]').attr('checked', 1);
	}catch(e){}
}

	{/literal}{/if}

{elseif $ocp eq 'login'}
{literal}
    window.fbAsyncInit = function() {
        FB.init({
{/literal}
			appId: '{$facebook.appID}',
			status: false, 
			cookie: true,
			xfbml: false,
			oauth: true
{literal}
	});

	FB.Event.subscribe('auth.login', function(response) {
		fbLogin($('#fb_login_type').val());
	});
	FB.Event.subscribe('auth.statusChange', function(response) {
		fbLogin($('#fb_login_type').val());
	});
};


function fbLogin(utype){
	window.location.href = '/login.php?use=fb_login&utype=' + utype + '&reurl='+ '{/literal}{$req.reurl}{literal}';
}
{/literal}
{/if}
//]]>-->
</script>
<!--add get facebook login message by haydn.h at 20120306 -->

</head>
<body>
<div id="fb-root"></div>
{if $notRoot}
	{include file='../index_top_banner.tpl'}
{else}
	{include file='index_top_banner.tpl'}
{/if}
    
    <!-- end banner -->
	
<div id="container">
		{if $div == 'shop'}
        <div id="shop">
		<div id="seller">
		{$siteMenu}{$itemTitle}{$content}
        </div>
		{elseif $div == 'list'}
		<div id="list">
        <div id="searchresults">
        	{$tmp_header}
			{$siteMenu}{$itemTitle}{$content}
        </div>
		{elseif $div == 'list_adv'}
		<div id="wrapper">
        <div id="content">
        	{$tmp_header}
			{$siteMenu}{$itemTitle}{$content}
        </div>
		{elseif $div == 'middle'}
    	<div id="wrapper">
        <div id="only-content">
        	{$tmp_header}
			{$siteMenu}{$itemTitle}{$content}
        </div>
		
		{elseif $sidebar ne '0'}
    	<div id="wrapper">
        <div id="content">
			{$siteMenu}{$itemTitle}{$content}
        </div>
		{else}
    	<div id="wrapper_2col">
        <div id="rightCol">
        	{$tmp_header}
			{$siteMenu}{$itemTitle}{$content}
        </div>
		{/if}
		
        {if !$hideLeftMenu}
		{if $notRoot}
			{include file="../index_left_search1.tpl"}
		{else}
			{include file="index_left_search1.tpl"}
		{/if}
        {/if}
		

         <!-- end menu left -->

	</div>
	
	{if $req.ad.categoryCMS}<div id="sidebar_cms" >
		{if $req.ad.categoryCMSRightTop.aboutPage}
		<div class="categoryCMSRightTop"><div align="left">{$req.ad.categoryCMSRightTop.aboutPage}</div></div><br />
		{/if}
		{if $req.ad.categoryCMSRightDown}
		<div class="categoryCMSRightTop"><div align="left">{$req.ad.categoryCMSRightDown}</div></div>
		{/if}
		</div>
	{/if}
{if !$showRandomBanner}	
	{if $sidebar ne '0'}
    	{if $notRoot}
			{include file='../index_right_adv.tpl'}
		{else}
			{include file='index_right_adv.tpl'}
		{/if}
    {/if}
{else}
	
    	{if $notRoot}
			{include file='../index_right_adv_statepage.tpl'}
		{else}
			{include file='index_right_adv_statepage.tpl'}
		{/if}
{/if}	
	<!-- end menu right -->
	
	{if $div == 'shop'}
	<div id="products">
		{$product_content}
	</div>
	{/if}
	<div style="clear: both;"></div>
 </div>	
	{if $notRoot}
		{include file='../index_bottom_banner.tpl'}  
	{else}
		{include file='index_bottom_banner.tpl'}  
	{/if}


{foot type=$search_type}

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
try {
	var pageTracker = _gat._getTracker("UA-7837615-1");
	pageTracker._trackPageview();
}catch(err) {}
</script>

<script type="text/javascript">

var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-32904933-1']);
_gaq.push(['_trackPageview']);

(function() {
var ga = document.createElement('script'); 
ga.type = 'text/javascript'; 
ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www' ) + '.http://google-analytics.com/ga.js'; 
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(ga, s);
})();

</script>
{/literal}
</body>
</html>
