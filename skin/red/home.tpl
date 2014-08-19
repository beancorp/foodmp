<link rel="stylesheet" href="/skin/red/css/slides.css">
<script src="/skin/red/js/jquery-1.4.4.min.js"></script>
<script src="/skin/red/js/slides.min.jquery.js"></script>
{literal}
<script>
		$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: 'img/loading.gif',
				play: 4000,
				pause: 2500,
				hoverPause: true,
				animationStart: function(){
					$('.caption').animate({
						bottom:-35
					},100);
				},
				animationComplete: function(current){
					$('.caption').animate({
						bottom:0
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log(current);
					};
				}
			});
		});
</script>
<script language="Javascript1.2">
	function goUrl(bcategory)
	{
		var search_state_name = document.getElementById("state_name_id").value;
		var suburb = document.getElementById("suburb_id").value;
		
		location.href = "/foodwine/index.php?cp=search&bcategory=" + bcategory + "&e4c387b8cf9f=1&search_state_name=" + search_state_name + "&suburb=" + suburb;
	} 

	function viewCategory(status) {
    	$(".all-category").animate({ height: status, opacity: status }, 'slow');
		if(status == 'hide') {
    		$(".less-category").show();
		} else {
			$(".less-category").hide();
		}
    }
</script>
{/literal}
<div id="_cms_foodwine_home" style="margin-top:1px;">

			<div id="slides">
				<div class="slides_container">
					<div>
						<a href="/soc.php?cp=buysell&id=108" title="View more">
                        <img src="{$smarty.const.IMAGES_URL}skin/red/slides/buyandsell-logo1.jpg" height="260" alt="Where your profit stay in your pocket"/>
                        </a>					
                    </div>
					<div>
						<a href="/soc.php?cp=buysell&id=110" title="View more">
                        <img src="{$smarty.const.IMAGES_URL}skin/red/slides/buyandsell-logo2.jpg" height="260" alt="No seller fees means better bargain buys!"/>
                        </a>					
                   </div>
				   <!--<div>
						<a href="/soc.php?cp=race" title="View more">
                        <img src="{$smarty.const.IMAGES_URL}skin/red/slides/buyandsell-logo3.jpg" height="260" alt="You Decide Your Destiny!"/>
                        </a>					
                   </div>-->
				</div>
				<a href="#" class="prev" title="Previous"><img src="{$smarty.const.IMAGES_URL}/skin/red/slides/arrow-prev.png" width="26" height="260" alt="Previous"></a>
				<a href="#" class="next" title="Next"><img src="{$smarty.const.IMAGES_URL}/skin/red/slides/arrow-next.png" width="26" height="260" alt="Next"></a>			
                <div class="clear">&nbsp;</div>
            </div>
<div class="clear">&nbsp;</div>
<div class="category">
<h2 style="font-size:20px; margin:1.1em 0 0.5em;">Select a category</h2>
<div class="less-category">
<table cellspacing="0" cellpadding="0" border="0">
    <tbody>
    	{foreach from=$req.category item=icat key=key}
        {if $icat.num <= 8}
            {if $icat.num % 2 == 1}
            <tr style="height:36px;">
            {/if}
                <td width="30" class="line"><img width="29" height="25" src="{if $icat.image}{$icat.image}{else}.{$smarty.const.IMAGES_URL}skin/red/cat-antiques.gif{/if}" alt="{$icat.name}" title="{$icat.name}"></td>
                <td width="220" class="line"><a href="soc.php?cp=prolist&id={$icat.id}" title="{$icat.name}">{$icat.name}</a></td>
                {if $icat.num % 2 == 1}
                <td width="30">&nbsp;</td>
                {/if}
            {if $icat.num % 2 == 0}
            </tr>
            {/if}
        {/if}
        {/foreach}
    </tbody>
</table>
<div class="view-category less-category view-all-category" style="margin-top:20px; height:12px"><a style="font-size:12px; line-height:9px; text-decoration:none;" href="javascript:void(0);" onclick="viewCategory('show');">View all categories</a></div>
<div class="clear"></div>
</div>
<div class="all-category" style="display:none;">
<table cellspacing="0" cellpadding="0" border="0">
    <tbody>
        {foreach from=$req.category item=icat key=key}
            {if $icat.num % 2 == 1}
            <tr style="height:36px;">
            {/if}
                <td width="30" class="line"><img width="29" height="25" src="{if $icat.image}{$icat.image}{else}.{$smarty.const.IMAGES_URL}skin/red/cat-antiques.gif{/if}" alt="{$icat.name}" title="{$icat.name}"></td>
                <td width="220" class="line"><a href="soc.php?cp=prolist&id={$icat.id}" title="{$icat.name}">{$icat.name}</a></td>
                {if $icat.num % 2 == 1}
                <td width="30">&nbsp;</td>
                {/if}
            {if $icat.num % 2 == 0}
            </tr>
            {/if}
        {/foreach}
        <tr style="height:12px;" class="hide_categories">
            <td width="30">&nbsp;</td>
            <td width="220"></td>
         	 <td width="30">&nbsp;</td>
            <td width="30"></td>
            <td width="220" style="vertical-align:bottom;">
<div class="view-category all-category view-less-category" style="display:none; height:12px; padding-left:5px; margin-top:20px;"><a style="font-size:12px; line-height:9px; text-decoration:none" href="javascript:void(0);" onclick="viewCategory('hide');">View less categories</a></div></td>
        </tr>
    </tbody>
</table>
</div>

</div>

<div class="clear"></div>

<h3 style="font-size:15px; background-color:#999999; color:#eeeeee; line-height:26px; padding-left:7px;">From our sellers</h3>
<div class="clear"></div>
<div class="random-items">
<ul>
	{if $req.random_items}
	{foreach from=$req.random_items item=product key=k}
	<li {if $k eq 0}class="first"{/if}><div class="image"><a href="/{$product.bu_urlstring}/{$product.url_item_name}"><img width="81" border="0" height="81" src="{$product.images.mainImage.0.sname.text}" alt="{$product.bu_name}" title="{$product.bu_name}"/></a></div><span>{$product.item_name|truncate:22:"..."}DOH!</span>
    </li>
    {/foreach}
    {/if}
</ul>
</div>
<div class="clear"></div>
<div class="view-all-items"><a style="font-size:12px; line-height:9px; text-decoration:none" href="/soc.php?cp=statepage&state_name={$req.default_state}">See all featured items</a></div>


</div>
<p>
{literal}
<style type="text/css">
	#_cms_foodwine_home a { text-decoration:none; color:#3a3a3a;}
	#_cms_foodwine_home .category table tr{ height:40px;}
	#_cms_foodwine_home .category table tr td.line{ border:none; border-bottom-style:solid; border-bottom-width:1px; border-bottom-color:#e5e5e5;}
	#_cms_foodwine_home .category table  a{ margin-left:10px; font-size:14px; color:#3a3a3a;}
	#sidebar2{ padding:10px 13px 17px 10px;}
	.view-category{ float:right; padding-left:15px;}
	.view-less-category{ background:url(/images/skin/red/icons/view-less.png) no-repeat scroll 0 0 transparent;}
	.view-all-category{ background:url(/images/skin/red/icons/view-all.png) no-repeat scroll 0 0 transparent;}
	#content .view-all-items{ float:right; padding-left:10px; background:url(/images/skin/red/foodwine/basket_save.png) no-repeat scroll 0 0 transparent; margin-top:20px;}
	.random-items ul{margin:10px 0;}
	.random-items ul li{list-style:none; float:left; padding:0 0 0 8px; text-align:center; vertical-align:middle;}
	.random-items ul li.first{ padding-left:0;}
	.random-items ul li .image{ border: #cccccc solid 1px; width:81px; height:81px;}
	.random-items ul li .image img{ text-align:center; vertical-align:middle}
	.random-items ul li span{ width:81px; color:#000099; text-align:center; display:block;}
</style>
{/literal}
</p>
{if $search_results}
	<div class="clear"></div>
	<div style="margin-top: 20px;">
		<form action="soc.php?cp=home&onlinestore=1" id="statesearch" class="st-connecticut" method="get">
			<input type="hidden" name="cp" value="home" />
			<div style="float:left; width: 180px; padding-top: 30px; padding-left: 8px; font-size:14px; font-weight: 900; color:#3c3380;">{$state_fullname} Homepage</div>
			<div style="float:right; width:300px; margin-right:10px;">
				<fieldset>
					<h2 id="location">Enter your location and find your local sellers</h2>
				</fieldset>
				<fieldset>
					<ol>
						<li>
							{literal}
							<script type="text/javascript">
							<!--//
							function switchState(state)
							{
								location.href = 'soc.php?cp=home&state_name=' + state;
							}
							//-->
							</script>
							{/literal}
							<select name="state_name" class="state" onchange="switchState(this.value)" style="width:140px;">
							{foreach from=$search_results.states item=state}
							<option value="{$state.state}"{$state.selected}>{$state.state}</option>
							{/foreach}
							</select>
						</li>
						<li>
							<span class="select-box">
							<select name="selectSubburb" class="region" id="state_subburb" style="width:140px;">
							{foreach from=$search_results.cities item=city}
							<option value="{$city.bu_suburb}.{$city.zip}"{$city.selected} title="{$city.bu_suburb}">{$city.bu_suburb}</option>
							{/foreach}
							</select>
							</span>
						</li>
					</ol>
				</fieldset>
				<fieldset class="searchlocation">
					<input src="skin/red/images/bu-search.gif" type="image" />
				</fieldset>
			</div>
		</form>
		<strong class="keywordresult">First 15 Featured Local Listings - Refreshed Daily at 1pm (ET)</strong>
		<ul id="state-advertisersnew" class="statepage" style="list-style:none;margin:0 0 0 3px">
			{foreach from=$search_results.ads item=ads}
			<li style="height:193px; overflow:hidden;">
			{if $ads.new ne 'new'}
				<div style="float:left;width:300px;">
				<a href="/{$ads.url}" class="title">{$ads.title}</a>
				<p>{$ads.fake}<br>
				{if $ads.address_hide eq '0'}{$ads.addr}<br>{/if}
				{if $ads.phone_hide eq '0'}{$ads.tel}<br>{/if}
				</p>
				</div>
				<div class="listFIXED" {if $ads.userlogos}style="_height:expression(this.firstChild.height>112?'112px':'auto');"{/if}>
				{if $ads.userlogos}<img width="81px" src="{$ads.userlogos}"/>{/if}</div>
				<div style="clear:both;"></div>
			{elseif $ads.fake ne '1'}
				<table cellpadding="0" cellspacing="0" border="0" width="525" background="{$smarty.const.IMAGES_URL}skin/red/{if $ads.fake eq '1'}state_fake_background{else}state-background{/if}.gif" {if $ads.fake ne '1'}height="192"{/if}>
					<tr>
						<td><img src="{$smarty.const.IMAGES_URL}skin/red/{if $ads.fake eq '1'}state_fake_top{else}state-top{/if}.gif" border="0" /></td>
					</tr>
					<tr style="height:152px">
						<td>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0">

							<tr>
							  <td width="24" align="right"><img src="{$smarty.const.IMAGES_URL}/skin/red/li-orange.gif" width="12" height="10" /></td>
							  <td><a style="font-size:13px;font-weight:bold;color:#352C7B;text-decoration:none;" href="/{$ads.url}">{$ads.website}</a>&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>
							<tr>
							  <td height="6" colspan="3"><img src="{$smarty.const.IMAGES_URL}skin/red/spacer.gif" /></td>
							</tr>
							<tr>
							  <td align="center" colspan="3"><hr style="width:95%;margin:0;color:#ccc;background:#ccc;height:1px;" /></td>
							</tr>
							<tr>
							  <td align="center" colspan="3">&nbsp;</td>
							</tr>
							<tr>
							  <td>&nbsp;</td>
							  <td valign="top">
								  <table cellpadding="0" cellspacing="0" width="100%" border="0">
								  <tr>
									<td valign="top" height="21"><strong>Categories:</strong> {$ads.category|truncate:100}</td>
								  </tr>
								  <tr>
									<td height="19" valign="middle"><strong>Some Items:</strong> {if $ads.items eq 'None'}{$ads.items}{/if}</td>
								  </tr>
								  <tr>
									  <td>{if $ads.items eq 'None'}&nbsp;{else}
									  <ul style="list-style:none; list-style-image:url({$smarty.const.IMAGES_URL}skin/red/arrow-statepage.gif);margin:0;padding:0 0 0 15px;;">
										{$ads.items}
									  </ul>{/if}</td>
								  </tr>
								  </table>
							  </td>
							  <td width="100" rowspan="3" align="center"><div style="width:77px; height:96px; overflow:hidden;">{if $ads.small_image ne ''}<img width="77" src="{$ads.large_image}" /></div>{else}&nbsp;{/if}</td>
							</tr>
		   
						</table></td>
					</tr>
					<tr>
						<td><img src="{$smarty.const.IMAGES_URL}/skin/red/{if $ads.fake eq '1'}state_fake_bottom{else}state-bottom{/if}.gif" border="0" /></td>
					</tr>
				</table>
			{else}
				<table cellpadding="0" cellspacing="0" border="0" width="525" background="{$smarty.const.IMAGES_URL}skin/red/{if $ads.fake eq '1'}state_fake_background{else}state-background{/if}.gif" {if $ads.fake ne '1'}height="192"{/if}>
					<tr>
						<td><img src="{$smarty.const.IMAGES_URL}skin/red/{if $ads.fake eq '1'}state_fake_top{else}state-top{/if}.gif" border="0" /></td>
					</tr>
					<tr>
						<td>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0">
					
							<tr>
							  <td width="24" align="right">&nbsp;</td>
							  <td align="left"><img src="{$smarty.const.IMAGES_URL}skin/red/state-fake_arrow.jpg" /> <a style="font-size:15px;font-weight:bold;color:#352C7B;padding:0;margin:0;text-decoration:none;" href="/soc.php?cp=statelink">{$ads.title}</a></td>
							  <td width="24">&nbsp;</td>
							</tr>
							{if $ads.desc ne ''}
							<tr>
							  <td>&nbsp;</td>
							  <td ><p id="sys_ad">{$ads.desc}</p></td>
							  <td width="24">&nbsp;</td>
							</tr>
							{/if}
						</table>
						</td>
					</tr>
					<tr>
						<td><img src="{$smarty.const.IMAGES_URL}skin/red/{if $ads.fake eq '1'}state_fake_bottom{else}state-bottom{/if}.gif" border="0" /></td>
					</tr>
				</table>
				
				<table cellpadding="0" cellspacing="0" border="0" width="525" background="{$smarty.const.IMAGES_URL}skin/red/{if $ads.fake eq '1'}state_fake_background{else}state-background{/if}.gif" {if $ads.fake ne '1'}height="192"{/if}>
					<tr>
						<td><img src="{$smarty.const.IMAGES_URL}skin/red/{if $ads.fake eq '1'}state_fake_top{else}state-top{/if}.gif" border="0" /></td>
					</tr>
					<tr>
						<td>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0">
					
							<tbody><tr>
							  <td width="24" align="right">&nbsp;</td>
							  <td align="left"><img src="{$smarty.const.IMAGES_URL}skin/red/state-fake_arrow.jpg"> <a style="font-size: 15px; font-weight: bold; color: rgb(53, 44, 123); padding: 0pt; margin: 0pt; text-decoration: none;" href="/soc.php?cp=statelink">BE A FEATURED LOCAL LISTING - IT'S FREE.</a></td>
							  <td width="24">&nbsp;</td>
							</tr>
																</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td><img src="{$smarty.const.IMAGES_URL}skin/red/{if $ads.fake eq '1'}state_fake_bottom{else}state-bottom{/if}.gif" border="0" /></td>
					</tr>
				</table>
			{/if}
			</li>
			{/foreach}
		</ul>
	</div>
{/if}