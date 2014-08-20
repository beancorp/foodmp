<link rel="stylesheet" href="{$smarty.const.STATIC_URL}css/skin/red/slides.css">
<script src="{$smarty.const.STATIC_URL}js/skin/red/slides.min.jquery.js"></script>
{literal}
  <script>
    $('document').ready(function(){
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
<style type="text/css">
.items {float:left;width:310px; padding-left:15px;}
.items p{line-height:20px;}
.items p.loc, .items p.price{margin:5px 0;}
.items p.desc{margin-bottom:0;}
#bookmark_link {
	float: left;
    font-size: 12px;
	color: #3C3380;
	padding-top: 8px;
}

#bookmark_link span {
	font-weight: bold;
	color: #3C3380;
}
</style>
{/literal}
{literal}
<script type="text/javascript">
$('document').ready(function(){new YAHOO.Hack.FixIESelectWidth('state_subburb');});
function checkStateForm() {
	if (document.getElementById('council_id_home').value == '')
	{
		alert('Please select a {/literal}{$lang.labelCouncil}{literal}.');
		document.getElementById('council_id_home').focus();
		return false;
	}
	
	return true;
}
</script>
{/literal}
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
	<span class="pad10px">
      {$req.aboutPage}
    </span>
	</td>
  </tr>
</table>
<a name="list"></a>	
<form action="/foodwine/index.php?cp=home#list" id="statesearch" class="st-connecticut" method="get" style="margin:30px 0;" onsubmit="return checkStateForm(this);">
	<input type="hidden" name="cp" value="home" />
	<div style="float:left; width: 180px; padding-top: 30px; padding-left: 8px; font-size:14px; font-weight: 900; color:#3c3380;">{$council_name} Homepage</div>
	<div style="float:right; width:315px; margin-right:10px;">
		<fieldset>
			<h2 id="location" style="font-size:12px;">Select your {$lang.labelCouncil} area to find local retailers</h2>
		</fieldset>
		<fieldset>
			<ol>
				<li>
					<select name="state_name" id="state_name_council_home" class="state" onchange="switchState(this.value, 'council_id_home')" style="width:90px;">
					{foreach from=$req.states item=state}
					<option value="{$state.state}"{$state.selected}>{$state.state}</option>
					{/foreach}
					</select>
				</li>
				<li>
					<span class="select-box">
					<select name="council" class="region" id="council_id_home" style="width:195px;">
					<option value="" title="{$lang.labelCouncil}">{$lang.labelCouncil}</option>
					{foreach from=$req.councils item=council}
					<option value="{$council.bu_council}" {$council.selected} title="{$council.bu_council}">{$council.bu_council}</option>
					{/foreach}
					</select>
					</span>
				</li>
			</ol>
		</fieldset>
		<fieldset class="searchlocation">
			<a href="" id="bookmark_link" rel="sidebar">Bookmark your <span>{$lang.labelCouncil} area hompage</span></a>
			<input src="/skin/red/images/bu-search.gif" type="image" />
		</fieldset>
	</div>
	</form>
	
	<br />
	<script>
	{literal}
		$(document).ready(function() {
			$('#bookmark_link').hide();
			
			var isFirefox = typeof InstallTrigger !== 'undefined';
			var isIE = /*@cc_on!@*/false || !!document.documentMode;
			
			if (isFirefox || isIE)  {
				var state = $('#state_name_council_home').val();
				var council = $('#council_id_home').val();
				var title = "FoodMarketplace";
				var address = "{/literal}{$smarty.const.SOC_HTTPS_HOST}{literal}foodwine/index.php?cp=home&state_name=" + state + "&council=" + council + "&x=24&y=18#list";
				$('#bookmark_link').attr('href', address);
				
				if (isIE) {
					$('#bookmark_link').click(function() {
						if (window.external) {
							window.external.AddFavorite(address, title);
							return false;
						}
					});
				}
				
				$('#bookmark_link').show();
			}
		});
	{/literal}
	</script>
	
	<table cellpadding="0" cellspacing="0" border="0" width="525" id="food_wine_homelist">
		{foreach from=$req.ads item=ads key=k}
			<tr style="padding:10px 0;" class="{if $k eq 0}banner_tr_first{else}banner_tr{/if}"><td>
				{if $ads.type ne 'system'}
				<table cellpadding="0" cellspacing="0" border="0" style=" background:url('/skin/red/images/state-background.gif') repeat-y scroll 0 0;">
					<tr height="20">
						<td><img src="{$smarty.const.IMAGES_URL}/skin/red/state-top.gif" border="0" /></td>
					</tr>
					
					<tr height="105">
						<td>
							
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="105">

								<tr height="30">
									<td style="padding-left:15px;" colspan="3">
										<img src="/skin/red/images/li-orange.gif" width="12" height="10" />&nbsp;<a class="home_page_listing_title" style="font-size:13px;font-weight:bold;color:#352C7B;text-decoration:none;" href="{if $ads.itemtype neq 'cate'}/{$ads.url}/{$ads.product.url_item_name}{else}/foodwine/index.php?cp=search&bcategory={$ads.subAttrib}&search_state_name=-1&e4c387b8cf9f=1{/if}">{$ads.product.name}</a>&nbsp;
									</td>
								  
								</tr>
								<tr height="5">
								  <td colspan="3" style="padding-left:15px;"><hr style="width:95%;margin:0;color:#ccc;background:#ccc;height:1px;" /></td>
								</tr>
 
              
                        
								<tr>
								  <td colspan="3">
									<div class="items">
										<p class="desc">
											{if $ads.itemtype neq 'cate'}{$ads.product.description|truncate:145:'...'}{/if}
										</p>
                                        <p class="loc"><strong>Location:</strong> <a title="{$ads.title}" style="text-decoration:underline; color:#777777" href="/{$ads.url}">{$ads.title}</a></p>
                                        {if $ads.itemtype neq 'cate'}
                                        <p class="price"><strong>Price:</strong> 
                                        {if $ads.product.priceorder eq 1}
                                            {$ads.product.unit} ${$ads.product.price}
                                        {else}
                                            ${$ads.product.price} {$ads.product.unit}
                                        {/if}
                                        </p>
                                        {/if}
									</div>
									<div style="width:140px; padding:10px 26px 0 0;" class="listFIXED" {if $ads.product.images.text}style="_height:expression(this.firstChild.height>112?'112px':'auto'); padding-right:15px;"{/if}>
										{if $ads.product.images.text}<img width="140" src="{$ads.product.images.text}"/>{/if}
									</div>
									<div style="clear:both;"></div>
								  </td>
								</tr>
       
                    		</table>
							
						</td>
					</tr>
					
					<tr height="20">
						<td><img src="/skin/red/images/state-bottom.gif" border="0" /></td>
					</tr>	
				</table>				
				{/if}
			</td></tr>
		{/foreach}
	</table>
	
		
{literal}
<style type="text/css">

	/*	firefox	*/
	table tr.banner_tr_first{margin:0;padding:0; margin-bottom:10px; display:block;}
	table tr.banner_tr{margin:0;padding:0; margin-bottom:10px; display:block;}
	#random_banner_left .random_banner_left{ margin-bottom:30px;}
	#random_banner_left #left_banner_last{margin-top:41px;}
	#random_banner_right .random_banner_right{margin-bottom:45px;}
	
	/*	google	*/
	@media screen and (-webkit-min-device-pixel-ratio:0) {
		table tr.banner_tr_first{margin-bottom:10px;}
		#random_banner_left #left_banner_last{margin-top:44px;}
		#random_banner_right #right_banner_last{margin-top:22px;}
	}
	
	/*	ie8	*/
	table tr.banner_tr_first{height:155px\0;}
	table tr.banner_tr{ height:155px\0;}
	#random_banner_left .random_banner_left{ margin-bottom:32px\0;}
	#random_banner_left #left_banner_last{margin-top:42px\0;}
	#random_banner_right .random_banner_right{margin-bottom:47px\0;}
	#random_banner_right #right_banner_last{margin-top:20px\0;}
	
	/*	ie7	*/
	table tr.banner_tr_first{*+height:155px;}
	table tr.banner_tr{ *+height:155px;}
	#random_banner_left .random_banner_left{ *+margin-bottom:40px;}
	#random_banner_left #left_banner_last{*+margin-top:42px;}
	#random_banner_right .random_banner_right{*+margin-bottom:49px;}
	#random_banner_right #right_banner_last{*+margin-top:59px;}
	
	/*	ie6	*/
	#random_banner_left .random_banner_left{ _margin-bottom:37px;}
	#random_banner_left #left_banner_last{_margin-top:43px;}
	#random_banner_right .random_banner_right{_margin-bottom:47px;}
	#random_banner_right #right_banner_last{_margin-top:52px;}
</style>
{/literal}	

{if $isSafari}
<style type="text/css">
	{literal}	
	@media screen and (-webkit-min-device-pixel-ratio:0) {
		table tr.banner_tr_first{margin-bottom:10px;}
		#random_banner_left .random_banner_left{ margin-bottom:35px;}
		#random_banner_left #left_banner_last{margin-top:45px;}
	}
	{/literal}	
</style>
{/if}
