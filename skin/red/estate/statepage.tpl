    {include file="searchpanel.tpl"}

	<strong class="keywordresult">First 15 Featured Local Listings - Refreshed Daily at 1pm (ET)</strong>

	<table cellpadding="0" cellspacing="0" border="0" width="525">
		{foreach from=$req.ads item=ads key=k}
			<tr height="145" class="{if $k eq 0}banner_tr_first{else}banner_tr{/if}"><td>
				{if $ads.type ne 'system'}
				<table cellpadding="0" cellspacing="0" border="0" style=" background:url('/skin/red/images/state-background.gif') repeat-y scroll 0 0;">
					<tr height="20">
						<td><img src="/skin/red/images/state-top.gif" border="0" /></td>
					</tr>
					
					<tr height="105">
						<td>
							
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="105">

								<tr height="30">
									<td style="padding-left:15px;" colspan="3">
										<img src="/skin/red/images/li-orange.gif" width="12" height="10" />&nbsp;<a style="font-size:13px;font-weight:bold;color:#352C7B;text-decoration:none;" href="/{$ads.url}">{$ads.title}</a>&nbsp;
									</td>
								  
								</tr>
								<tr height="5">
								  <td colspan="3" style="padding-left:15px;"><hr style="width:95%;margin:0;color:#ccc;background:#ccc;height:1px;" /></td>
								</tr>
 
              
                        
								<tr>
								  <td colspan="3">
									<div style="float:left;width:300px; padding-left:15px;">
										<p>
											{if $ads.address_hide eq '0'}{$ads.addr}<br>{/if}
											{if $ads.phone_hide eq '0'}{$ads.tel}{/if}
											&nbsp;
										</p>
									</div>
									<div class="listFIXED" {if $ads.userlogos}style="_height:expression(this.firstChild.height>112?'112px':'auto'); padding-right:15px;"{/if}>
										{if $ads.userlogos}<img width="81px" src="{$ads.userlogos}"/>{/if}
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
				{else}
				
					<table cellpadding="0" cellspacing="0" border="0"  style="background:url('/skin/red/images/state_fake_background.gif') repeat-y scroll 0 0 transparent;">
					<tr height="20">
						<td><img src="/skin/red/images/state_fake_top.gif" border="0" /></td>
					</tr>
					
					<tr height="105">
						<td>
							
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="105">

								<tr height="30">
								  <td colspan="3" style="padding-left:15px;"><img src="/skin/red/images/state-fake_arrow.jpg" />&nbsp;<a style="font-size: 15px; font-weight: bold; color: rgb(53, 44, 123); padding: 0pt; margin: 0pt; text-decoration: none;" href="/{$ads.url}">{$ads.title}</a>&nbsp;</td>
								</tr>
								
 
              
                        
								<tr>
								  <td colspan="3" style="padding-left:15px;">
								  		<p style="line-height:25px;">
											{$ads.fake}&nbsp;
										</p>
								  </td>
								</tr>
       
                    		</table>
							
						</td>
					</tr>
					
					<tr height="20">
						<td><img src="/skin/red/images/state_fake_bottom.gif" border="0" /></td>
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
	#random_banner_left .random_banner_left{ margin-bottom:27px;}
	#random_banner_left #left_banner_last{margin-top:35px;}
	#random_banner_right .random_banner_right{margin-bottom:45px;}
	
	/*	google	*/
	@media screen and (-webkit-min-device-pixel-ratio:0) {
		table tr.banner_tr_first{margin-bottom:10px;}
		#random_banner_left #left_banner_last{margin-top:38px;}
		#random_banner_right #right_banner_last{margin-top:22px;}
	}
	
	/*	ie8	*/
	table tr.banner_tr_first{height:155px\0;}
	table tr.banner_tr{ height:155px\0;}
	#random_banner_left .random_banner_left{ margin-bottom:27px\0;}
	#random_banner_left #left_banner_last{margin-top:43px\0;}
	#random_banner_right #right_banner_last{margin-top:57px\0;}
	
	/*	ie7	*/
	table tr.banner_tr_first{*+height:155px;}
	table tr.banner_tr{ *+height:155px;}
	#random_banner_left .random_banner_left{ *+margin-bottom:34px;}
	#random_banner_left #left_banner_last{*+margin-top:36px;}
	#random_banner_right .random_banner_right{*+margin-bottom:47px;}
	#random_banner_right #right_banner_last{*+margin-top:53px;}
	
	/*	ie6	*/
	#random_banner_left .random_banner_left{ _margin-bottom:26px;}
	#random_banner_left #left_banner_last{_margin-top:48px;}
	#random_banner_right .random_banner_right{_margin-bottom:44px;}
	#random_banner_right #right_banner_last{_margin-top:51px;}
</style>
{/literal}	

{if $isSafari}
<style type="text/css">
	{literal}	
	@media screen and (-webkit-min-device-pixel-ratio:0) {
		table tr.banner_tr_first{margin-bottom:10px;}
		#random_banner_left .random_banner_left{ margin-bottom:31px;}
		#random_banner_left #left_banner_last{margin-top:43px;}
	}
	{/literal}	
</style>
{/if}