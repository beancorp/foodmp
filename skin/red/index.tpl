{if $hide_template}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
		<head>
			<link type="text/css" href="{$smarty.const.STATIC_URL}css/skin/red/all.css" rel="stylesheet" media="screen" />
			<link type="text/css" href="{$smarty.const.STATIC_URL}static/css/skin/red/validationEngine.jquery.css" rel="stylesheet" media="screen" />
			<!--[if lt IE 7]><script defer type="text/javascript" src="{$smarty.const.SOC_HTTP_HOST}skin/red/js/pngfix.js"></script><![endif]-->
			<!--[if IE 7]><link type="text/css" href="{$smarty.const.SOC_HTTP_HOST}skin/red/css/ie.css" rel="stylesheet" media="screen" /><![endif]-->
			<!--[if lt IE 7]><link type="text/css" href="{$smarty.const.SOC_HTTP_HOST}skin/red/css/ie6.css" rel="stylesheet" media="screen" /><![endif]-->
		</head>
		<body>
			<div id="frame_content">
				<script>
					var soc_https_host = {$smarty.const.SOC_HTTPS_HOST};
				</script>
				{$siteMenu}{$itemTitle}{$content}
			</div>
		</body>
	</html>
{else}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
	<head>
	<title>{$pageTitle}</title>
	{if $cp eq "blog"}
	<link rel="canonical" href="{$smarty.const.SOC_HTTP_HOST}{$headerInfo.bu_urlstring}" />
	{/if}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta property="fb:app_id" content="{$facebook.appID}"/>
	<meta content="{if $req.info.facelike_title}{$req.info.facelike_title}{else}Food Market Place{/if}" property="og:title">
	<meta content="{if $req.info.facelike_image}{$req.info.facelike_image}{else}{$smarty.const.IMAGES_URL}skin/red/logo-main.png{/if}" property="og:image">
	<meta content="{$req.info.facelike_desc}" property="og:description">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, minimum-scale=1.0, maximum-scale=5.0" />
	<meta name="google-site-verification" content="dHAkIMuHSon2_YaffhovdX253zJtYLlwz59aDgD0DeY" />
	<meta name="Keywords" content="{$keywords}" />
	<meta name="Description" content="{$description}" />
	<meta name="robots" content="noodp,noydir" />
	{if $base eq true}
	<base href="{if $Protocol}{$Protocol}{else}http{/if}://{$HostName}/" />
	{/if}
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png"/>
	<link rel="shortcut icon" type="image/png" href="{$smarty.const.IMAGES_URL}/favicon.png"/>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link type="text/css" href="{$smarty.const.STATIC_URL}css/skin/red/all-min-1.0.css" rel="stylesheet" media="screen" />
	<link type="text/css" href="{$smarty.const.STATIC_URL}css/skin/red/validationEngine.jquery.css" rel="stylesheet" media="screen" />
	<link href="{$smarty.const.STATIC_URL}css/skin/red/ui-lightness/jquery-ui.min.css" rel="stylesheet">
	<script type="text/javascript">
	var domain = '{$smarty.const.DOMAIN}';
	var facebook_appID = '{$facebook.appID}';
	var foodmp_ocp = '{$ocp}';
	var foodmp_act = '{$act}';
	var smarty_fb_can = '{$smarty.session.fb.can}';
	</script>
	<!--[if lt IE 7]><script defer type="text/javascript" src="{$smarty.const.SOC_HTTP_HOST}skin/red/js/pngfix.js"></script><![endif]-->
	<!--[if IE 7]><link type="text/css" href="{$smarty.const.SOC_HTTP_HOST}skin/red/css/ie.css" rel="stylesheet" media="screen" /><![endif]-->
	<!--[if lt IE 7]><link type="text/css" href="{$smarty.const.SOC_HTTP_HOST}skin/red/css/ie6.css" rel="stylesheet" media="screen" /><![endif]-->
	<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery-1.js" type="text/javascript"></script>
	<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="https://cdn.rawgit.com/aamirafridi/jQuery.Marquee/master/jquery.marquee.min.js"></script>
	<script type="text/javascript" src="{$smarty.const.STATIC_URL}js/skin/red/niftycube.js"></script>
    <script type="text/javascript" src="/static/js/foodmp.js" rel="stylesheet" ></script>

	{$xajax_Javascript}


	{if ! isset($hide_responsive)}
		<link type="text/css" href="{$smarty.const.STATIC_URL}css/skin/red/responsive.css" rel="stylesheet" media="all" />
	{/if}
	
	
	{literal}
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "e3316ed7-5941-4c4d-80f8-4a09809678c6", doNotHash: false, doNotCopy: false, hashAddressBar: false, shorten:false});</script>
	{/literal}

	{literal}
	<style type="text/css">
		#wrapper{
			width: auto !important;
		}

		.main-banner{
			float: left;
			width: 750px;
			background: url(../images/main-banner.png) 0 0 no-repeat #cee6f2;
			margin-left: 24px;
			border-radius: 10px;
			min-height: 360px;
		}

		.main-banner-cta{
			font-size: 19px;
			font-family: Arial;
			color: #3c3c3c;
			margin-top: 175px;
			margin-left: 30px;
			line-height: 30px;
		}

		.main-banner-cta span{
			color: #473b85;
			font-weight: bold;
			font-size: 21px;
			margin-left: 0;
		}

		.main-banner-button{
			background: url(../images/get_start_btn.png) 0 0 no-repeat;
			width: 179px;
			height: 54px;
			margin-left: 30px;
			margin-top: 30px;
			cursor: pointer;
		}
	</style>
	{/literal}


	</head>
	<body>
	
	<div id="fb-root"></div>
	{include file=$skindir|cat:'/index_top_banner.tpl'}
		
	<!-- end banner -->
		
	<div id="container">
	
		{if !$hideLeftMenu}
				{include file=$skindir|cat:"/index_left_search.tpl"}
		{/if}
	
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
			    {if !$hideLeftMenu}
                    <div id="wrapper" style="padding-left: 24px;">
                {else}
                    <div id="wrapper">
                {/if}
				<div id="content" style="{$contentStyle}">
				{$tmp_header}
				{$siteMenu}{$itemTitle}{$content}
				</div>
			{elseif $div == 'middle'}
			    {if !$hideLeftMenu}
                    <div id="wrapper" style="padding-left: 24px;">
                {else}
                    <div id="wrapper">
                {/if}
				<div id="only-content">
				{$tmp_header}
				{$siteMenu}{$itemTitle}{$content}
				</div>
			{elseif $sidebar ne '0'}
			    {if $home_page}
			        <div class="main-banner">
				        <div class="main-banner-cta">
					        Earn yourself an easy <span>$1,000,000</span>
					        <br>
					        and have lots of fun along the way.
				        </div>
				        <a href="/fanfrenzy"><div class="main-banner-button"></div></a>
			        </div>
                {/if}
			
			    {if !$hideLeftMenu}
                    <div id="wrapper" style="padding-left: 24px;">
                {else}
                    <div id="wrapper">
                {/if}
				<div id="content" style="{$contentStyle}">
				{$siteMenu}{$itemTitle}{$content}
				</div>
			{else}
			<div id="wrapper_2col">
				<div id="rightCol" style="{$contentStyle}">
				{$tmp_header}
				{$siteMenu}{$itemTitle}{$content}
				</div>
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
			{include file=$skindir|cat:'/index_right_adv.tpl'}
		{/if}
	{else}
		{include file=$skindir|cat:'/index_right_adv_statepage.tpl'}
	{/if}	
		<!-- end menu right -->
		
		{if $div == 'shop'}
		<div id="products">
			{$product_content}
		</div>
		{/if}
		<div style="clear: both;"></div>
	 </div>

	{include file=$skindir|cat:'index_bottom_banner.tpl'}  


	{foot type=$search_type}

	<script src="{$smarty.const.STATIC_URL}js/main.js"></script>

	</body>
	</html>
{/if}