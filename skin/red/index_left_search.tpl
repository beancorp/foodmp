<div id="sidebar">
<div id="search_panel">
<img width="162" height="14" style="height:14px;" src="{$smarty.const.IMAGES_URL}/left_search/search_top.gif">
</div>
{literal}
<style type="text/css">
	#statepage_state_name_id option {
		color:#3C3380;
	}
	
	.random_banner_left{
		margin-bottom: 24px;
	}
	#random_banner_left{
		 margin-top:15px;
	}
	
	.random_banner_right {
		 margin-bottom:31px;
	}
	#random_banner_right {
		margin-top:-1px;
	}
	
	/*	google	*/
	@media screen and (-webkit-min-device-pixel-ratio:0) {
		.random_banner_left{
			margin-bottom: 23px;
		}
		#random_banner_left{
		 margin-top:17px;
		}
		
		.random_banner_right {
		 	margin-bottom:30px;
		}
	}
	
	
	
	/*	ie8	*/
	.random_banner_left{
			margin-bottom: 23px\0;
	}
	#random_banner_left{
			margin-top:12px\0;
	}
	
	/*	ie7	*/
	.random_banner_left{
			*+margin-bottom: 31px;
	}
	.random_banner_right{
			*+margin-bottom: 36px;
	}
	#random_banner_right{
			*+margin-top: 15px;
	}
	
	/*	ie6	*/
	.random_banner_left{
			_margin-bottom: 30px;
	}
	#random_banner_left{
			_margin-top: 18px;
	}
	
	.search_form{width:162px;}
	
</style>
<style type="text/css">
#wrap{width:650px;margin:0 auto;}
/*.txt{width:210px;height:25px;border:1px solid #ccc;line-height:25px;padding:0 5px;}*/
.productDis, .sellerDis, .keywordDis, .agentDis{border:1px solid #999;position:absolute;overflow:hidden; background:none repeat scroll 0 0 #FFFFFF; color:#0099FF}
.productDis p, .sellerDis p, .keywordDis p, .agentDis p{line-height:25px;cursor:default;padding:0 5px;}
.productDis ul, .sellerDis ul, .keywordDis ul, .agentDis ul{ padding:0; margin:0; list-style:none outside none;}
.productDis li, .sellerDis li, .keywordDis li, .agentDis li{line-height:20px;cursor:default;padding:0 5px;background-color:#fff; margin:0;}
.productDis .cur, .sellerDis .cur, .keywordDis .cur, .agentDis .cur{background:none repeat scroll 0 0 #0099FF; color:#FFFFFF;}

.cms-left{padding:10px 0;}
.cms-left ul{ padding:0; margin:0;}
.cms-left ul li{display:inline; list-style:none;}
.cms-left ul li p{ font-size:14px;}
.cms-left ul li .name{ color:#000000; padding:5px 0 13px; display:block; line-height:18px;}
fieldset#search{ padding: 5px 0; text-align: center;}
#sidebar_banner {margin-top: 10px;}
/*body{background: none !important;}*/
</style>
{/literal}
{if $isSafari}
	{literal}
		<style type="text/css">
			/* safari	*/
				@media screen and (-webkit-min-device-pixel-ratio:0) {
					.random_banner_left{
						margin-bottom: 26px;
					}
					#left_banner_last{
					 	margin-top:30px;
					}
					
				}		
		</style>
	{/literal}	
{/if}
<script>
	var dis_bg = "#f1f1f1";
	var dis_color = "#b8b6b6";
	
	var nor_bg = "#ECECEC";
	var nor_color = "#5D5D5D";
</script>

	<div style="font-size:16px; background-color:#F6F6F6; color:#463c8e; padding-left:5px; padding-top:10px; font-weight:bold;"><img src="{$smarty.const.IMAGES_URL}/left_search/zoom.gif" width="23" height="22"/> Search for<br/>&nbsp;Food & Wine</div>

	<form method="get" action="{$smarty.const.SOC_HTTP_HOST}foodwine/index.php?cp=search" name="searchside" id="searchside" class="search_form" style="background-color:#f6f6f6;">
	<input type="hidden" name="cp" value="search" />
	<input type="hidden" name="e4c387b8cf9f" value="1" />
		<div id="find_close_button">Close</div>
		<fieldset id="search_estate">
		<ol>
            <li class="clear">
                <label style="padding:3px 0;">Product or Business Name</label><input type="text" id="keyword_k" name="keyword" value="" class="text" onkeypress={literal}"if(event.keyCode==13){$('#searchside').submit();}"{/literal} autocomplete="off" placeholder="Any" />
            </li>	
            <li class="clear">
                <label style="padding:3px 0;">{$lang.labelZIP} Search</label><input type="text" id="bu_postcode" name="bu_postcode" value="{if $searchForm.bu_postcode}{$searchForm.bu_postcode}{else}{$smarty.session.search_form.bu_postcode}{/if}" class="text" onkeypress={literal}"if(event.keyCode==13){$('#searchside').submit();}"{/literal} autocomplete="off" placeholder="Any" />
            </li>	
			
            <li>
                <label>Category</label>
                <span class="select-box">
                <select name="bcategory" id="catst" onchange="changeCategory(this.value)">
                    <option value="">All Categories</option>
                    {foreach from=$lang.seller.attribute.5.subattrib item=l key=k}
                        <option value="{$k}" {if ($searchForm.bcategory eq $k) || ( $smarty.session.search_form.bcategory eq $k)}selected{/if}>{$l}</option>
                    {/foreach}
                </select>
                </span>		
            </li>
	
			
            <li id="li_cuisine" style="display:{if !($searchForm.bcategory eq 1 || $smarty.session.search_form.bcategory eq 1)}none{/if}">
                <label>Cuisine</label>
                <span class="select-box">
                <select name="cuisine" id="cuisine">
                    <option value="">All Cuisine</option>
                    {foreach from=$search.search_cuisines item=l}
                        <option value="{$l.cuisineID}" {if ($searchForm.cuisine eq $l.cuisineID) || ( $smarty.session.search_form.cuisine eq $k)}selected{/if}>{$l.cuisineName}</option>
                    {/foreach}
                </select>
                </span>		
            </li>
			
			<li>
				<label>State</label>
				<select name="search_state_name" id="state_name_id">
					<option value="-1">All</option>
					{foreach from=$search.search_states item=states}
					<option value="{$states.state}" {$states.selected}>{$states.description}</option>
					{/foreach}
				</select>
			</li>
		<li id="suburb_field">
			{literal}
			<style>
				.custom-combobox {
					float: left;
					position: relative;
					display: inline-block;
					margin-bottom: 11px;
				}
				.custom-combobox-toggle {
					position: absolute;
					top: 0;
					bottom: 0;
					margin-left: -1px;
					padding: 0;
					/* support: IE7 */
					*height: 1.7em;
					*top: 0.1em;
				}
				.custom-combobox-input {
					margin: 0;
					padding: 0.3em;
					width: 108px !important;
					border: none;
				}
				.ui-autocomplete  {
					max-height: 300px;
					overflow-y: scroll; 
					overflow-x: hidden;
				}				
				.suburb-down-arrow {
					float: left;
					background: url("../skin/red/images/suburb-down-arrow.png") center no-repeat;
					width: 29px;
					height: 26px;
					border-left: #CCC 1px solid;
				}
				fieldset#search{
					padding: 5px 0;
					text-align: center
				}
			</style>
			{/literal}
			{if $preselect_suburb}<script type="text/javascript">var preselect_suburb = "{$preselect_suburb}";</script>{/if}
			
			<label>Suburb:</label>
			<div class="select-box" style="border: rgb(169, 169, 169) 1px solid; border-radius: 5px; -mox-border-radius: 5px; -webkit-border-radius: 5px; height: 28px">
				<select name="suburb" id="suburb_id">
					<option value="">All</option>
				</select>
				<div class="suburb-down-arrow"></div>
			</div>
			
		</li>
			

		<li>
			<label>Distance</label>
			<select name="distance" id="proximityid">
				<option value="">Select Distance</option>
				{foreach from=$lang.val.search_radius item=l key=k}
				<option value="{$k}" {if ($searchForm.distance eq $k) || ( $smarty.session.search_form.distance eq $k)}selected{/if}>{$l}</option>
				{/foreach}
			</select>
		</li>
		
		</ol>
		</fieldset>
		
		<fieldset id="search">
		<input type="image" class="search" src="{$smarty.const.IMAGES_URL}/search-btn.png" style="width:auto; height: auto;" />
		</fieldset>
		
	</form>
	
	{literal}
		<script type="text/javascript">
			get_state_val="{$smarty.get.state_name}" ? "{$smarty.get.state_name}" :-1;
			get_make_val="{$smarty.get.make}" ? "{$smarty.get.make}" : '';
			$(document).ready(function(){
				//$("#state_name_id").val(get_state_val);
				$("#leftsector_2").val(get_make_val);
			});		
		</script>
	{/literal}
    	
 
 {if not $isstorepage}

 	{if $secure_connection}
	<table width="160" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose GeoTrust SSL for secure e-commerce and confidential communications.">
		<tr>
			<td align="center" valign="top">
				<script type="text/javascript" src="https://seal.geotrust.com/getgeotrustsslseal?host_name={$smarty.const.DOMAIN}&amp;size=M&amp;lang=en"></script><br />
				<a href="http://www.geotrust.com/ssl/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;"></a></td>
		</tr>
	</table>
	<br/>
	{/if}
 
	<div id="social_links">
		<a href="https://www.facebook.com/pages/Foodmarketplace/1417039495177479" title="Facebook" style="margin-right:2px;" target="_blank"><img src="{$smarty.const.IMAGES_URL}/facebook-like-area.png" title="Facebook" alt="Facebook"/></a>
	</div>
 
	

	 {*if $show_signon_banner_small}
	   <a href="{$smarty.const.SOC_HTTP_HOST}/mothernature" title="View more"><img src="{$smarty.const.SOC_HTTP_HOST}skin/red/foodwine/images/banner-small-coffs-harbour.jpg" alt="Where your profit stay in your pocket" /></a>
	   <a href="{$smarty.const.SOC_HTTP_HOST}/Harrisedgecliff" title="View more"><img src="{$smarty.const.SOC_HTTP_HOST}skin/red/foodwine/images/banner-small-edgecliff.jpg"  alt="Where your profit stay in your pocket" /></a>
	 {/if}
	 
	{if $show_join_banner}
	<div class="cms-left" style="padding:20px 0 0;">
	<a href="/registration.php"><img src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/retailers-join-here.jpg" /></a>
	</div>
	{/if}
	
	{if $show_consumer_join_banner}
	<div class="cms-left" style="padding:20px 0 0;">
	<a href="{$smarty.const.SOC_HTTPS_HOST}signup.php"><img src="{$smarty.const.IMAGES_URL}/consumers_join_here.jpg" /></a>
	</div>
	{/if}

	{if $show_alan_jones_button}
	<div class="cms-left" style="padding:20px 0 0;">
	<a href="/alanjones.php"><img src="{$smarty.const.IMAGES_URL}/alan_jones_button.jpg" /></a>
	</div>
	{/if}
	
	{if ((!isset($isitempage)) && (!isset($dontshowpromo)) || ($search_page))}
		<div class="cms-left" style="padding:20px 0 0;">
		<a href="/fanpromotion"><img src="{$smarty.const.IMAGES_URL}/fan_side_banner.png" /></a>
		</div>
	{/if*}
{/if}

{if $isstorepage}

	{*if $smarty.get.testing*}
		{if $promo_store_code}
			<style>
				{literal}
					#retailer_member_code {
						margin-right: 5px;
						position: relative;
					}
					
					#retailer_code_number {
						position: absolute;
						margin-top: 128px;
						margin-left: 25px;
						color: #555;
						text-align: center;
						width: 119px;
					}
					
					#retailer_member_code_link {
						display: block;
						height: 50px;
						margin-top: 160px;
						position: absolute;
						width: 170px;
					}
					
					#retailer_member_code_gallery {
						background-image: url(../images/gallery-btn.png);
						position: absolute;
						width: 154px;
						height: 39px;
						left: 8px;
						top: 210px;
						cursor: pointer;
						z-index: 2;
					}
					
					#retailer_member_code_competition {
						background-image: url(../images/competition-btn.png);
						position: absolute;
						width: 154px;
						height: 39px;
						left: 8px;
						top: 165px;
						cursor: pointer;
						z-index: 2;
					}
				{/literal}
			</style>
			<div id="retailer_member_code">
				<div style="position: absolute; top: 96px; left: 14px; width: 150px; height: 15px; overflow: hidden; text-align: center">
                	{$retailer_name}
                </div>
				<div id="retailer_code_number">{$promo_store_code}</div>
				<a href="{$smarty.const.SOC_HTTP_HOST}entry?code={$promo_store_code}" id="retailer_member_code_link"></a>
				<img src="/retailer_member_code.png" width="170px" position: />
				<div id="retailer_member_code_gallery" onclick="window.location.href='{$smarty.const.SOC_HTTP_HOST}fanpromo/list_photo_retailer.php?retailer_id={$req.info.StoreID}'"></div>
				<div id="retailer_member_code_competition" onclick="window.location.href='/entry'"></div>
			</div>
		{/if}
	{*/if*}

{/if}
<div id="sidebar_banner">
    <a href="/fanfrenzy" title="" style="margin-right:2px;" target="_blank">
        <img src="{$smarty.const.IMAGES_URL}/4_ff_sidebar_banner.png" title="Facebook" alt="Facebook"/>
    </a>
</div>


{*if $show_left_cms}
<div class="cms-left">
{$cms_left.aboutPage}
</div>
{/if*}

<div style="width:160px; display:none" id="random_banner_left">
{foreach from=$statePageBanners item=banner key=k}
	{if $k>=2 && $k%2==1}
		<div style="width:160px; height:250px;" class="random_banner_left" {if $k == 19}id="left_banner_last"{/if}>{if $banner.banner_link}<a href="{$smarty.const.SOC_HTTP_HOST}adclick.php?id={$banner.banner_id}" title="{$banner.description}" target="_blank">{/if}
		{if $banner.file_type eq 'image'}
			<img src="/upload/new/{$banner.banner_img}" width="160" height="250" border="0" alt="{$banner.description}" title="{$banner.description}" />
		{else}
		<div id="__Flash__{$k}__" replace_img="{$banner.replace_image}" title="{$banner.description}" >
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="160" height="250">
			<param name="movie" value="/upload/new/{$banner.banner_img}" />
			<param name="quality" value="high" />
			<embed src="/upload/new/{$banner.banner_img}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="160" height="250"></embed>
			</object>
			</div>
		{/if}
		
		{if $banner.banner_link}</a>{/if}</div>
	{/if}
{/foreach}
</div>
{if !$showRandomBanner}


{if $hid_left_banner neq '1' && 0}
	{if ($sidebar eq '0' and $sidebarContent eq '') or ($siderbar ne '0' and $sidebarContent ne '') or ($state_name ne '' or $statename ne '')}
	{if $search_type eq 'estate'}
	<div style=" margin-bottom:10px;"><img src="{$smarty.const.IMAGES_URL}/skin/red/startselling_estate.gif" width="160" height="175" border="0" usemap="#leftBannerEstate" />
<map name="leftBannerEstate" id="leftBannerEstate">
<area shape="rect" coords="58,137,143,162" href="{$smarty.const.SOC_HTTP_HOST}soc.php?act=signon&attribute=1" />
</map>
	{elseif $search_type eq 'auto'}
	<div style=" margin-bottom:10px;"><img src="{$smarty.const.IMAGES_URL}/skin/red/startselling_vehicle.gif" width="160" height="175" border="0" usemap="#leftBannerVehicle" />
<map name="leftBannerVehicle" id="leftBannerVehicle">
<area shape="rect" coords="54,140,139,165" href="{$smarty.const.SOC_HTTP_HOST}soc.php?act=signon&attribute=2" />
</map>
	{elseif $search_type eq 'job'}
	<div style=" margin-bottom:10px;"><img src="{$smarty.const.IMAGES_URL}/skin/red/startselling_career.gif" width="160" height="175" border="0" usemap="#leftBannerCareer" />
<map name="leftBannerCareer" id="leftBannerCareer">
<area shape="rect" coords="59,132,144,157" href="{$smarty.const.SOC_HTTP_HOST}soc.php?act=signon&attribute=3" />
</map>
	{else}
	<div style=" margin-bottom:10px;"><!--img src="{$smarty.const.IMAGES_URL}/skin/red/startSelling_smallBanner01.jpg" width="160" height="175" border="0" usemap="#leftBanner" />
<map name="leftBanner" id="leftBanner">
  <area shape="rect" coords="59,132,144,157" href="https://socexchange.com.au/soc.php?act=signon" />
</map-->
	{/if}
</div>
{/if}
	{/if}
	 {if $is_media eq '1'}
	<a href="http://www.e-commerceguide.com/essentials/ebay/article.php/3782991" title="e-commerceguide.com" target="_blank"><img src="{$smarty.const.IMAGES_URL}/skin/red/logos/e-commerceguide.jpg" alt="" border="0" style="padding-top:45px;" /></a> 
	<p>November 5, 2008.</p>
	{/if}
	{if $search.ad.categoryCMS}
		<ul id="categoryCMSLeft">
			<li>{$search.ad.categoryCMSLeft}</li>
		</ul>
	{/if}
	
	{if $smarty.get.cp neq 'media' and $smarty.get.cp neq 'media6' and $smarty.get.cp neq 'media5' and $smarty.get.cp neq 'media4' and $smarty.get.cp neq 'media3' and $smarty.get.cp neq 'media2' and $smarty.get.cp neq 'media1' and( $smarty.get.name eq '' or ($smarty.get.name neq '' and ($search_type eq '' or $search_type eq 'store'))) and ($gallery_banner_display_status neq 'show' or ($gallery_banner_display_status eq 'show' and ($search_type eq '' or $search_type eq 'store'))) and !$noShowGalleryBanner and $search_type neq 'foodwine' and 0}
		<div style=" margin-bottom:15px;">
			<img src="{$smarty.const.IMAGES_URL}/skin/red/gallery_invites_banner.jpg" border="0" usemap="#Mapgi" />
			<map name="Mapgi" id="Mapgi">
			  <area shape="rect" coords="0,0,160,174" href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=invites" />
			</map>
		</div>
	{/if}
    
 {if $cp neq 'faqinfo' && $cp neq 'newfaq' && $cp neq 'sellerhome' && $nottv neq '1' && $cp neq 'dispro' && $cp neq 'shopdes' &&  $cp neq 'map' && $cp neq 'friend' && $modcp neq 'wishlist' && !$noShowTvBanner && $search_type neq 'foodwine' and 0}
	 
		<div style=" margin-bottom:15px;">
		<img src="{$smarty.const.IMAGES_URL}/skin/red/tv-ad_banner.jpg" border="0" usemap="#Maptv" />
		<map name="Maptv" id="Maptv">
		  <area shape="rect" coords="0,0,160,174" href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=youtube" />
		</map>
		</div>

{/if}



{/if}

</div>
