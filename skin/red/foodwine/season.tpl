{literal}
<style type="text/css">
form#statesearch {padding-top:20px;}
.items {float:left;width:310px; padding-left:15px;}
.items p{line-height:20px;}
.items p.loc, .items p.price{margin:5px 0;}
.items p.desc{margin-bottom:0;}
.page_left{float:left;}
.page_right{float:right;}
.page_right .first, .page_right .last{ display:none;}
.page_right .previous{ background:url(/skin/red/images/pre-page.jpg) no-repeat; padding-left:11px;}
.page_right .next{ background:url(/skin/red/images/next-page.jpg) no-repeat right 0; padding-right:11px;}
.page_left a, .page_right a{ color:#463C8E}
.hide {display:none;}
.view-desc {padding-left:15px; width:60px; margin: 10px 10px 0 0; height: 12px; display: block; float:right;}
.view-all-desc {background:url(/skin/red/images/icons/view-all.png) no-repeat left 0;}
.view-less-desc {background:url(/skin/red/images/icons/view-less.png) no-repeat left 0;}
</style>
{/literal}
{literal}
<script type="text/javascript">
function viewDesc(status, n) {
	if(status == 'hide') {
		$("#less-desc-" + n).show();
		$("#all-desc-" + n).hide();
		$("#desc_full_" + n).animate({ height: status, opacity: status }, 'slow');
		$("#desc_short_" + n).animate({ height: 'show', opacity: 'show' }, 'slow');
	} else {
		$("#less-desc-" + n).hide();
		$("#all-desc-" + n).show();
		$("#desc_short_" + n).hide();
		$("#desc_full_" + n).animate({ height: status, opacity: status }, 'slow');
	}
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
<form action="/foodwine/index.php?cp=season" id="statesearch" class="st-connecticut" method="get" style="margin:3px 0 30px 0;">
	<input type="hidden" name="cp" value="season" />
	<div style="float:left; width: 180px; padding-top: 30px; padding-left: 8px; font-size:14px; font-weight: 900; color:#3c3380;">What's in season?</div>
	<div style="float:right; width:310px; margin-right:10px;">
	<fieldset>
		<h2 id="location" style="font-size:12px; width:285px;">Select the season below to find out what is available</h2>
	</fieldset>
	<fieldset>
		<ol>
			<li>
				<select name="season" id="season" class="state" style="width:297px;">
				{foreach from=$req.seasons item=season}
				<option value="{$season.id}" {$season.selected}>{$season.title}</option>
				{/foreach}
				</select>
			</li>
			<li>
				<span class="select-box" style="float:left;">
				<select name="typeid" class="region" id="typeid" style="width:230px;">
				{foreach from=$lang.ProductType item=type key=k}
				<option value="{$k}" title="{$type}" {if $k == $req.typeid}selected="selected"{/if}>{$type}</option>
				{/foreach}
				</select>
				</span><input src="/skin/red/images/bu-search.gif" type="image" style="float:right; margin-left:13px;" />
			</li>
		</ol>
	</fieldset>
	</div>
	</form>
	
	<table cellpadding="0" cellspacing="0" border="0" width="525">
		{foreach from=$req.product.list item=p key=k}
			<tr style="padding:10px 0;" class="{if $k eq 0}banner_tr_first{else}banner_tr{/if}"><td>
				<table cellpadding="0" cellspacing="0" border="0" style=" background:url('/skin/red/images/state-background.gif') repeat-y scroll 0 0;">
					<tr height="20">
						<td><img src="/skin/red/images/state-top.gif" border="0" /></td>
					</tr>
					
					<tr height="105">
						<td>
							
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="105">

								<tr height="30">
									<td style="padding-left:15px;" colspan="3">
										<img src="/skin/red/images/li-orange.gif" width="12" height="10" />&nbsp;<a style="font-size:13px;font-weight:bold;color:#352C7B;text-decoration:none;">{$p.title}</a>&nbsp;
									</td>
								  
								</tr>
								<tr height="5">
								  <td colspan="3" style="padding-left:15px;"><hr style="width:95%;margin:0;color:#ccc;background:#ccc;height:1px;" /></td>
								</tr>
                        
								<tr>
								  <td colspan="3">
									<div class="items" style=" width:295px; position:relative;">
										<p id="desc_short_{$k}" class="desc">
											{$p.desc|truncate:165:"..."}
										</p>
										<p id="desc_full_{$k}" class="desc hide">
											{$p.desc}
										</p>
                                        <p class="price" style="float:left;">
                                        {if $p.varities}
                                        <strong>Varities:</strong> 
                                        {$p.varities}
                                        {/if}
                                        {if strlen($p.desc) > 165}
                                        <div id="less-desc-{$k}" class="view-desc less-desc view-all-desc"><a onclick="viewDesc('show', '{$k}');" href="javascript:void(0);" style="font-size:12px; line-height:9px; text-decoration:none;">View all</a></div>
                                        <div id="all-desc-{$k}" style="display: none;" class="view-desc all-desc view-less-desc hide"><a onclick="viewDesc('hide', '{$k}');" href="javascript:void(0);" style="font-size:12px; line-height:9px; text-decoration:none">View less</a></div>
                                        {/if}
                                        </p>
									</div>
									<div style="width:183px; padding:10px 26px 0 0;" class="listFIXED" {if $p.product.images.text}style="_height:expression(this.firstChild.height>112?'112px':'auto'); padding-right:15px;"{/if}>
										{if $p.pic}<img width="183" src="{$p.pic}"/>{/if}
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
			</td></tr>
		{/foreach}
        <tr>
        <td>{$req.product.linkStr}</td>
        </tr>
        <tr height="80"><td>&nbsp;</td></tr>
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
