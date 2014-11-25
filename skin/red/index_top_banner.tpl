<div id="header" {if $isstorepage}style="height:150px;"{/if}>
	<div id="inside_header">
		<div id="header_logo">
			<a href="{$smarty.const.SOC_HTTP_HOST}"><img src="{$smarty.const.IMAGES_URL}/logo-main-60.png" height="60px" border="0" /></a>
		</div>
		<div id="header_navigation_phone" style="display: none;">
			<span id="open_search_panel"><img src="{$smarty.const.IMAGES_URL}/find_button.png" /></span>
			<a href="{$smarty.const.SOC_HTTP_HOST}" title="Home">Home</a> &nbsp;&nbsp;
			{if $session.UserID ne ''}
				<a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=sellerhome">My Panel</a>
				&nbsp;&nbsp;
				<a href="{$smarty.const.SOC_HTTP_HOST}logout.php">Logout</a>
			{else}
				<a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=register">Register</a>
				&nbsp;&nbsp;
				<a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=login">Login</a>
			{/if}
			<script>
				{literal}
					$(document).ready(function() {
						$('#open_search_panel').click(function() {
							$('#searchside').css('visibility', 'visible');
						});
						$('#find_close_button').click(function() {
							$('#searchside').css('visibility', 'hidden');
						});
					});
				{/literal}
			</script>
		</div>
 		<div id="header_message" {if $race_page}style="margin-top: 10px;"{/if}{if $header_message ne ''} style="margin-top: 10px;" {/if}>
			{if not $race_page}
				{if $header_message ne ''}
					{$header_message}
				{else}
					<span>
						{if $is_category ne ''}
							{$req.categoryName}
						{else}
							{if not $isstorepage}
								Buy Smart. Buy Local. <sup>&#174;</sup>
							{/if}
						{/if}
					</span>
				{/if}
			{else}
				<div id="race_header_message"><img src="{$smarty.const.SOC_HTTP_HOST}skin/red/race/prize_money.jpg" /></div>
			{/if}
		</div>
		<div id="head-main-menu">
			{if not $race_page}
				<div id="navigation_links">
					<div class="navigation_link">
						<a href="{$smarty.const.SOC_HTTP_HOST}" title="Home">Home</a>
					</div>
					<div class="navigation_link" style="margin-left: 8px;">
						<a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=about" {if $cp eq 'about'}class="about_active"{else}class="about"{/if} title="About Us">About Us</a>
					</div>
					<div class="navigation_link" style="float: left; margin-left: 10px;">
						<a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=newfaq" {if $cp eq 'newfaq' or $cp eq 'faqinfo'}class="selling_active"{else}class="selling"{/if} title="Help">Help</a>
					</div>
                    <div class="social-icon" style="float: left; margin-left: 30px; margin-top: -2px;">
                        <a target="_blank" href="https://www.facebook.com/FoodMarketplaceOfficial" style="text-decoration:none;"><img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/icon-face.png" alt=""></a>
                        <a target="_blank" href="https://twitter.com/foodmarketplace" style="text-decoration:none;"><img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/icon-twitter.png" style="margin-left: 10px; cursor: pointer;" alt=""> </a>
                        <a target="_blank" href="http://instagram.com/foodmarketplaceofficial" style="text-decoration:none;"><img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/icon-instagram.png" style="margin-left: 10px; cursor: pointer;" alt=""></a> 
                    </div>
				</div>
				
				<div style="clear: both;">
					<div id="header_buttons" style="float: right;">
						{if $session.UserID ne ''}
							<a href="{$smarty.const.SOC_HTTPS_HOST}soc.php?cp=sellerhome" style="float:left;" class="header_button" id="myadmin">My Panel</a>
							<a href="{$smarty.const.SOC_HTTPS_HOST}logout.php" style="float:right;" class="header_button" id="logout">Logout</a>
						{else}
							<a href="{$smarty.const.SOC_HTTPS_HOST}soc.php?cp=register" style="float:left;" class="header_button" id="register">Register</a>
							<a href="{$smarty.const.SOC_HTTPS_HOST}soc.php?cp=login" style="float:right;" class="header_button" id="login">Login</a>
						{/if}
					</div>
					<div id="sub_links">
						<a class="sub_link buyers_register_link" href="{$smarty.const.SOC_HTTPS_HOST}signup.php">Consumers<br>Join Here<br>It's FREE</a>
						<a class="sub_link seller_register_button" href="{$smarty.const.SOC_HTTPS_HOST}registration.php">All retailers<br>JOIN HERE</a>
					</div>
				</div>
				
			{else}
				<div style="clear: both; margin-top: 15px;">
					<a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=sellerhome"><img src="/skin/red/images/race_logo.png" /></a>
				</div>
			{/if}
		</div>
		{if $isstorepage}
			{if $req.template.bannerImg}
				<div id="top_banner_store_image" style="position: absolute; top: 92px;"><img src="{$smarty.const.SOC_HTTP_HOST}{$req.template.bannerImg}" /></div>
			{else}
				<div id="top_banner_store_image" style="position: absolute; top: 92px; height: 75px; background-color: #{$req.template.bgcolor};">
					<div style="height: 70px; width: 950px; background-image: url({$smarty.const.SOC_HTTP_HOST}template_images/banner/{$req.info.subAttrib}.jpg);">
						<div style="font-family: Arial, Helvetica, sans-serif; margin-left: auto; margin-right: auto; padding-top: 20px; color: {if $req.info.subAttrib eq 7}#ffebdc{else}#{$req.template.bgcolor}{/if}; font-size: 14pt; text-align: center;">
							{$req.info.bu_name|wordwrap:40:'<br/>':true}
						</div>
					</div>
				</div>
			{/if}
		{/if}
	</div>
</div>



