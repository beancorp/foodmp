{if $headerInfo.attribute eq '5'}
{if ! isset($hide_responsive)}
{literal}
<!--Some more responsive stuff for the store page-->
<style>
	@media only screen and (max-width: 767px), screen and (max-device-width: 720px) {
		#header_logo {
			display: none;
		}
		
		#header {
			position: absolute !important;
			z-index: 100;
		}
		
		#seller #store_logo {
			display: none !important;
		}
		
		#mobile_image {
			overflow: hidden !important;
			position: absolute;
			top: -100px;
			width: 120px !important;
			z-index: 200 !important;
			display: block !important;
		}
		
		#fan_button {
			margin-left: 75px !important;
			position: absolute;
			top: 70px;
			z-index: 1500 !important;
		}
		
		#container #rightCol {
			margin-top: 90px;
			position: relative;
		}
	}
	
	ul#icons {
		width: 570px !important;
		float: left !important;
		margin: 5px 0 0 15px !important;
	}
	
	#fan_button {
		width: 165px;
		float: left;
	}
	
	#fan_frame {
		background-image: url("/images/shootingstar_fan_box.png");
		cursor: pointer;
		height: 54px;
		padding-top: 2px;
		width: 90px;
		padding-left: 75px;
		margin-top: 5px;
		cursor: pointer;
		text-align: center;
	}
	
	#fan_counter {
		font-weight: bold;
		font-size: 12pt;
		color: #3d3185;
		display: block;
		width: 88px;
	}
	
	#are_you_a_fan {
		color: #3d3185;
		font-size: 8pt;
	}
	
	.ui-dialog {
		margin-top: 25px;
	}
	
	.ui-dialog * {
		color: #000;
	}
	
	.ui-dialog-title {
		color: #000 !important;
		font-weight: bold !important;
		font-style: italic !important;
	}
</style>
{/literal}
{/if}
<div id="fan_button">
		<div id="fan_frame">
			<span id="fan_counter"></span> 
			<span id="are_you_a_fan"></span>
		</div>
		{literal}
			<script>
				$(document).ready(function() {
					$('#fan_frame').click(function() {
						$.ajax({
							url: '/fan.php',
							type: 'post',
							data: {store_id: '{/literal}{if $headerInfo.info.StoreID}{$headerInfo.info.StoreID}{else}{$smarty.get.StoreID}{/if}{literal}'},
							dataType: 'json',
							success: function(response) {
								if (!response.error) {
									$('#fan_counter').html(response.fan_counter + ' Fans');
								} else {
									if (response.message == 'Must be logged in') {
										window.location.assign("{/literal}{$smarty.const.SOC_HTTPS_HOST}{literal}soc.php?cp=login");
									}
								}
								if (response.are_you) {
									$('#are_you_a_fan').html('You are a fan');
									$("#congratulations_dialog").dialog({ position: ['center', 'top'], minWidth: 500, minHeight: 50 });									
								} else {
									$('#are_you_a_fan').html('');
								}
							}						
						});
					});
					
					$.ajax({
						url: '/fan.php',
						type: 'post',
						data: {get_counter: '{/literal}{if $headerInfo.info.StoreID}{$headerInfo.info.StoreID}{else}{$smarty.get.StoreID}{/if}{literal}'},
						dataType: 'json',
						success: function(response) {
							$('#fan_counter').html(response.fan_counter + ' Fans');
							if (response.are_you) {
								$('#are_you_a_fan').html('You are a fan');
							} else {
								$('#are_you_a_fan').html('');
							}
						}						
					});					
				});			
			</script>
		{/literal}
</div>
<img id="mobile_image" src="{if $headerInfo.info.images.mainImage.2.sname.text neq '/images/121x100.jpg'}{$headerInfo.info.images.mainImage.2.sname.text}{else}/template_images/default_logo.jpg{/if}" style="display: none;" />
{/if}
<div id="congratulations_dialog" title="Congratulations" style="display: none;">
<p>You have shown your support of {$headerInfo.info.bu_name} by becoming a Fan. <br /> You are now connected to {$headerInfo.info.bu_name}. </p>
</div>
{literal}
<style>
ul#icons li { padding-right:0;margin-right:0;}
</style>
{/literal}
<ul id="icons">
{if $headerInfo.attribute eq 3 and $headerInfo.subAttrib eq 3}
    <li><a href="{$smarty.const.SOC_HTTP_HOST}{$headerInfo.url_bu_name}" class="i-home">{$lang.heardeItems[0][0]}</a></li>
    <li><a href="javascript:void(0)" class="i-contact-grey">Contact {$headerInfo.subAttribName|default:'Seller'}</a></li>
    <li><a href="javascript:void(0)" class="i-about-grey">About {$headerInfo.subAttribName|default:'Seller'}</a></li>
    <li><a href="javascript:void(0)" class="i-location-grey">{$headerInfo.subAttribName|default:'Sellers'} Location</a></li>
    <li><a href="{if $headerInfo.is_customer}javascript:popupw();{elseif $headerInfo.attribute eq '5'}javascript:popupNewsletter();{else}javascript:tipRedirect();{/if}" class="i-alerts">Email Alerts</a></li>
    <li><a href="javascript:bookmark()" class="i-bookmark-grey">Bookmark {$headerInfo.subAttribName|default:'Product'}</a></li>
    <li><a href="javascript:void(0)" class="i-blog-grey">Blog Board</a></li>
    <li><a href="javascript:void(0)" class="i-rss-grey">RSS {$headerInfo.sellerTypeName|default:'Products'}</a></li>
    <li><a href="javascript:void(0)" class="i-rss-grey">RSS Blog</a></li>
{elseif $headerInfo.attribute eq '0'}
    <li style="width:62px;"><a href="{$smarty.const.SOC_HTTP_HOST}{$headerInfo.url_bu_name}" class="i-home2">Seller {$lang.heardeItems[0][0]}</a></li>
    <li><a href="{$smarty.const.SOC_HTTP_HOST}{$headerInfo.url_bu_name}/wishlist" class="i-wishlist">Wishlist Home</a></li>
    <li><a href="javascript:popcontactwin();" class="i-contact">Contact {$headerInfo.subAttribName|default:'Seller'}</a></li>
    <li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=shopdes&amp;StoreID={$headerInfo.StoreID}" class="i-about">About {$headerInfo.subAttribName|default:'Seller'}</a></li>
    <li><a href="{if $headerInfo.is_customer}javascript:popupw();{elseif $headerInfo.attribute eq '5'}javascript:popupNewsletter();{else}javascript:tipRedirect();{/if}" class="i-alerts">Email Alerts</a></li>
    <li><a href="javascript:bookmark()" class="i-bookmark">Bookmark {$headerInfo.subAttribName|default:'Product'}</a></li>
    <li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=blog&StoreID={$headerInfo.StoreID}" class="i-blog">Blog Board</a></li>
    <li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=productrss&StoreID={$headerInfo.StoreID}" class="i-rss">RSS {$headerInfo.sellerTypeName|default:'Products'}</a></li>
    <li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=blogrss&StoreID={$headerInfo.StoreID}" class="i-rss">RSS Blog</a></li>
{elseif $headerInfo.attribute eq '5'}	
{literal}
<style type="text/css">
	#rightCol ul#icons li{ width:70px;}
</style>
{/literal}
	<li><a href="{$smarty.const.SOC_HTTP_HOST}{$headerInfo.url_bu_name}" class="i-home2">Home</a></li>
	<li><a href="javascript:popcontactwin();" class="i-contact">Contact Us</a></li>
	<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=shopdes&amp;StoreID={$headerInfo.StoreID}" class="i-about">About Us</a></li>
	<li><a href="javascript:bookmark()" class="i-bookmark">Bookmark</a></li>
	<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=blog&StoreID={$headerInfo.StoreID}" class="i-blog">Our Blog</a></li>
	<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=recipes&StoreID={$headerInfo.StoreID}" class="i-recipes">Recipes</a></li>
{else}
	<li style="width:62px;"><a href="{$smarty.const.SOC_HTTP_HOST}{$headerInfo.url_bu_name}" class="i-home2">{$lang.heardeItems[0][0]}</a></li>
	<li><a href="{$smarty.const.SOC_HTTP_HOST}{$headerInfo.url_bu_name}/wishlist" class="i-wishlist">Wishlist Home</a></li>
    <li><a href="javascript:popcontactwin();" class="i-contact">Contact {$headerInfo.subAttribName|default:'Seller'}</a></li>
    <li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=shopdes&amp;StoreID={$headerInfo.StoreID}" class="i-about">About {$headerInfo.subAttribName|default:'Seller'}</a></li>
    <li><a href="{if $headerInfo.is_customer}javascript:popupw();{elseif $headerInfo.attribute eq '5'}javascript:popupNewsletter();{else}javascript:tipRedirect();{/if}" class="i-alerts">Email Alerts</a></li>
    <li><a href="javascript:bookmark()" class="i-bookmark">Bookmark {$headerInfo.subAttribName|default:'Product'}</a></li>
    <li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=blog&StoreID={$headerInfo.StoreID}" class="i-blog">Blog Board</a></li>
    <li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=productrss&StoreID={$headerInfo.StoreID}" class="i-rss">RSS {$headerInfo.sellerTypeName|default:'Products'}</a></li>
    <li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=blogrss&StoreID={$headerInfo.StoreID}" class="i-rss">RSS Blog</a></li>
{/if}
    <li><a href="{if $headerInfo.is_customer}/soc.php?cp=reportseller&amp;StoreID={$headerInfo.StoreID}{else}javascript:tipRedirect();{/if}" class="i-report">Report Us</a></li>
    <li style="position: absolute; right: -5px; width: auto!important; float: right; !important;"><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=friend&amp;StoreID={$headerInfo.StoreID}{if $req.pid}&pid={$req.pid}{else}{if $sellerhome neq 1 && $req.items.product.0.pid}&pid={$req.items.product.0.pid}{/if}{/if}" class="i-friend" style="width: 75px;">Email Friend</a></li>
  </ul>