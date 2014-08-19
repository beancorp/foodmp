{if ! isset($preview)}
<!DOCTYPE html>
<html>
<head>
{/if}
<style>
	{if ! isset($preview)}
	{literal}
	html {
		background-color: #d8d9db;
	}
	{/literal}
	{/if}
	{literal}
	#hotbuy {
		width: 642px;
		margin-left: auto;
		margin-right: auto;	
	}
	#hotbuy_background {
		height: 130px;
		overflow: hidden;
		width: 642px;
		position: relative;
	}
	#hotbuy_body {
		background-color: #FFF;
		padding-top: 20px;
	}
	{/literal}
</style>
{if ! isset($preview)}
</head>
<body>
{/if}
	<div id="hotbuy">
		<div id="hotbuy_background">
			<img src="{$smarty.const.SOC_HTTP_HOST}template_images/email_alerts/{$req.info.send_type}/{$req.info.subAttrib}.jpg" />
		</div>
		<div id="hotbuy_body">
			<table cellspacing="0" cellpadding="0" border="0">
			<tbody>
			<tr>
				<td>
					
				</td>
				<td colspan="2">
					<a href="{$smarty.const.SOC_HTTP_HOST}{$req.info.bu_urlstring}" title="{$req.info.bu_name}" target="_blank" style="padding:0; margin:0; text-align:left; color:#3e3183; font-size:24px; font-weight:bold; display:block; text-decoration:none;">{$req.info.bu_name}</a>
					<table width="100%" bgcolor="#FFFFFF">
					<tbody>
					<tr>
						<td bgcolor="#FFFFFF" width="3">
							&nbsp;
						</td>
						<td align="left" style="border-top: 1px solid #d8d9db; border-bottom: 1px solid #d8d9db;" height="25">
							<span style=" display:block;color:#3C3380; padding:0; margin:0; position:relative; text-align:left; font:arial,sans-serif;width:605px; font-size:14px; font-weight:normal">&nbsp;&nbsp;<strong style="color:#3C3380;font:arial,sans-serif;font-size:16px; font-weight:bold">Valid:</strong> {$req.info.start_date} - {$req.info.end_date} - while stocks last</span>
						</td>
						<td bgcolor="#FFFFFF" width="3">
							&nbsp;
						</td>
					</tr>
					</tbody>
					</table>
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td valign="top" bgcolor="#ffffff" width="260" align="center">
					<table width="242" cellspacing="0" cellpadding="0" bgcolor="#EBEBEB">
					<tbody>
					<tr height="1">
						<td height="1" bgcolor="#FFFFFF" colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr height="24">
						<td colspan="2" height="24" bgcolor="#ebebeb">
							<img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/emailalert/email-alert-body-left-top.jpg" width="245" height="24"/>
						</td>
					</tr>
					<tr>
						<td valign="middle" height="39" colspan="2" align="left">
							<table cellspacing="0" cellpadding="0" border="0" width="184">
							<tbody>
							<tr>
								<td width="15">
									&nbsp;
								</td>
								<td width="45">
									<font style="font-style:normal;font:12px arial,sans-serif;color:#777777;">Address:</font>
								</td>
								<td width="15">
									&nbsp;
								</td>
								<td align="left">
									<font style="font-style:normal;font:12px arial,sans-serif; font-weight:bold; color:#777777;">
									{if $req.info.address_hide == 0 }{ $req.info.bu_address}<br/>{/if}{$req.info.bu_suburb}, {$req.info.bu_state} </font>
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="middle" height="19" colspan="2">
							<table cellspacing="0" cellpadding="0" border="0" width="184">
							<tbody>
							<tr>
								<td width="45">
									&nbsp;
								</td>
								<td width="15">
									&nbsp;
								</td>
								<td align="left">
									<a href="{if $req.info.address_hide==1}javascript:alert('Address not listed');{else}/soc.php?cp=map&StoreID={$req.info.StoreID}&key={$req.info.bu_address},{$req.info.bu_suburb},{$req.info.bu_state}{/if}"><img border="0" src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/icon_location.gif"/></a>&nbsp;<a style=" position:relative; bottom:7px;color:#777777; font:12px arial,sans-serif; " href="{if $req.info.address_hide==1}javascript:alert('Address not listed');{else}{$smarty.const.SOC_HTTP_HOST}soc.php?cp=map&StoreID={$req.info.StoreID}&key={$req.info.bu_address},{$req.info.bu_suburb},{$req.info.bu_state}{/if}">View Map</a>
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					 {if $req.info.phone_hide == 0 }
					<tr>
						<td valign="middle" height="25" colspan="2" align="left">
							<table cellspacing="0" cellpadding="0" border="0" width="184">
							<tbody>
							<tr>
								<td width="15">
									&nbsp;
								</td>
								<td width="45">
									<font style="font-style:normal;font:12px arial,sans-serif;color:#777777;">Phone:</font>
								</td>
								<td width="15">
									&nbsp;
								</td>
								<td align="left">
									<font style="font-style:normal;font:12px arial,sans-serif; font-weight:bold; color:#777777;">
									{$req.info.bu_phone} </font>
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					 {/if}
					<tr>
						<td valign="middle" height="25" colspan="2" align="left">
							<table cellspacing="0" cellpadding="0" border="0" width="184">
							<tbody>
							<tr>
								<td width="15">
									&nbsp;
								</td>
								<td width="45">
									<font style="font-style:normal;font:12px arial,sans-serif;color:#777777;">Rating: </font>
								</td>
								<td width="15">
									&nbsp;
								</td>
								<td align="left">
									 {if $req.aveRating eq 0} <font style="font-style:normal;font:12px arial,sans-serif; font-weight:bold; color:#F3B216; vertical-align:middle">No Ratings{else}<img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/star_{$req.aveRating}.png"/>{/if} </font>
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					 {if $req.info.opening_hours neq ""}
					<tr>
						<td valign="middle" height="25" colspan="2" align="left">
							<table cellspacing="0" cellpadding="0" border="0" width="184">
							<tbody>
							<tr>
								<td width="15">
									&nbsp;
								</td>
								<td colspan="3" align="left">
									<font style="font-style:normal;font:12px arial,sans-serif;color:#777777; line-height:20px;">Opening Hours:</font>
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="middle" height="25" colspan="2" align="left">
							<table cellspacing="0" cellpadding="0" border="0" width="184">
							<tbody>
							<tr>
								<td width="15">
									&nbsp;
								</td>
								<td colspan="3" align="left">
									<font style="font-style:normal;font:12px arial,sans-serif;color:#777777; line-height:20px;">
									{$req.info.opening_hours} </font>
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					 {/if} {if $req.info.facebook neq ""}
					<tr>
						<td valign="middle" height="25" colspan="2" align="left">
							<table cellspacing="0" cellpadding="0" border="0" width="184">
							<tbody>
							<tr>
								<td width="15">
									&nbsp;
								</td>
								<td align="left">
									<a style="color:#777777; text-decoration:underline" href="{$req.info.facebook}" target="_blank"><img style="float:left;" src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/facebook.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:underline; cursor:pointer;color:#777777; font:12px arial,sans-serif;">Facebook</span></a>
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					 {/if}
					<tr>
						<td valign="top" height="20" style=" font:12px arial,sans-serif; color:#777777; width:30%;">
						</td>
						<td valign="top" height="20" style=" font-style:normal;font:12px arial,sans-serif; font-weight:bold; color:#777777;">
						</td>
					</tr>
					 {if $req.info.sold_status eq '1' && $req.info.foodwine_type eq 'food'}
					<tr>
						<td colspan="4">
							<img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/emailalert/email-alert-body-left-middle.jpg" width="245" height="38"/>
						</td>
					</tr>
					<tr>
						<td valign="middle" height="25" colspan="2" align="left">
							<table cellspacing="0" cellpadding="0" border="0" width="184">
							<tbody>
							<tr>
								<td width="15">
									&nbsp;
								</td>
								<td height="30" align="left" colspan="3" style="margin:0px; padding:0 10px 10px 0;color:#777777; font:12px arial,sans-serif;">
									<p>
										Payments Accepted:<br/>
										<span class="payment" style="text-align:left; font-weight:bold;">
										{foreach from=$req.info.payments item=lps key=key} {if $lang.Payments[$key].image ne '' } <img src="{$smarty.const.SOC_HTTP_HOST}{$lang.Payments[$key].image}" align="absmiddle"/>
										{if $lps[1]},{/if} {else} {if $lang.Payments[$key].text neq ""} {$lang.Payments[$key].text} {if $lps[1]},{/if} {/if} {/if} {/foreach} </span>
									</p>
									 {if $req.info.sold_status eq '1'} {if $sellerhome eq "1" || 1}
									<p style="text-align:left;">
										Shipping for this seller:<br/><span class="payment" style=" font-weight:bold;">
										{foreach from=$req.info.deliveryMethod item=lps key=key}{if $lang.Delivery[$key].text neq ""}{$lang.Delivery[$key].text}{if $lps[1]},{/if}{/if}{/foreach} {foreach from=$req.info.deliveryMethod|explode:"|" item=opcl key=oplk} {$lang.Delivery[$opcl].text} {if $opcl eq '1' or $opcl eq '2' or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$req.info.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}<br/>
										{/foreach} </span>
									</p>
									 {else}
									<p>
										 Domestic Shipping for this item<br/>
										<span class="payment">
										{foreach from=$req.info.deliveryMethod item=lps key=key}{if $lang.Delivery[$key].text neq ""}{$lang.Delivery[$key].text}{if $lps[1]},{/if}{/if}{/foreach} {foreach from=$req.info.deliveryMethod|explode:"|" item=opcl key=oplk} {$lang.Delivery[$opcl].text} {if $opcl eq '1' or $opcl eq '2' or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$req.info.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}<br/>
										{/foreach} </span>
										{if $l.isoversea} <br/>Overseas Shipping for this item<br/>
										<span class="payment">
										{foreach from=$l.oversea_deliveryMethod|explode:"|" item=opcl key=oplk} {$lang.Delivery[$opcl].text} {if $opcl eq '1' or $opcl eq '2' or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$l.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}<br/>
										{/foreach}</span>
										{/if}
									</p>
									 {/if} {/if}
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="26" bgcolor="#FFFFFF">
							<img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/emailalert/email-alert-body-left-bottom.jpg" width="245" height="26"/>
						</td>
					</tr>
					 {/if}
					</tbody>
					</table>
				</td>
				<td valign="top" bgcolor="#ffffff" width="340">
					<table width="237" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
					<tbody>
					<tr>
						<td height="1">
							&nbsp;
						</td>
					</tr>
					 {if $req.products.items} {foreach from=$req.products.items item=p key=k} {if $req.products.total eq 1}
					<tr>
						<td valign="top" align="right">
							<table width="340" cellspacing="0" cellpadding="0">
							<tbody>
							<tr>
								<td valign="top" align="right">
									<table width="340" cellspacing="0" cellpadding="0">
									<tbody>
									<tr>
										<td valign="top" width="width:100%;" align="right">
											<a style=" text-decoration:none;font:12px arial,sans-serif; font-weight:bold;color:#4E4E4E" target="_blank" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}" title="{$p.item_name}">
											<img src="{if $p.big_image}{$smarty.const.SOC_HTTP_HOST}{$p.big_image}{else}{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg{/if}" width="356" alt="{$p.name}" title="{$p.name}"/></a>
										</td>
									</tr>
									<tr>
										<td valign="top" align="left">
											<table cellspacing="0" cellpadding="0" bgcolor="#ffffff">
											<tbody>
											<tr>
												<td height="55" width="100%" align="left">
													<font style=" padding-left:15px;font:18px arial,sans-serif; color:#777777;font-weight:normal; ">{$p.item_name|truncate:60:"..."}</font>
												</td>
											</tr>
											<tr>
												<td height="25">
													<font style=" padding-left:15px;font:16px arial,sans-serif; color:#FF9900; font-weight:bold;">{if $p.price neq '0.00'}{if $p.priceorder eq 1} {$p.unit} ${$p.price} {else} ${$p.price} {$p.unit} {/if}{/if}</font>
												</td>
											</tr>
											</tbody>
											</table>
											<br/>
											{if $req.hotbuy_message} {if $req.hotbuy_message eq 1} <img src="{$smarty.const.SOC_HTTP_HOST}images/hotbuy_1.jpg"/>
											{else if $req.hotbuy_message eq 2} <img src="{$smarty.const.SOC_HTTP_HOST}images/hotbuy_2.jpg"/>
											{/if} {/if}
										</td>
									</tr>
									</tbody>
									</table>
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					 {else}
					<tr>
						<td valign="top" style="padding:0">
							<table width="340" cellspacing="0" cellpadding="0">
							<tbody>
							<tr>
								<td valign="top">
									<table width="340" cellspacing="0" cellpadding="0">
									<tbody>
									<tr>
										<td valign="top" width="80" style="padding-right:10px;">
											<a style=" text-decoration:none;font:12px arial,sans-serif; font-weight:bold;color:#4E4E4E" target="_blank" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}" title="{$p.item_name}">
											<img src="{if $p.small_image}{$smarty.const.SOC_HTTP_HOST}{$p.small_image}{else}{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg{/if}" width="80" alt="{$p.name}" title="{$p.name}"/></a>
										</td>
										<td valign="top" align="left">
											<table cellspacing="0" cellpadding="0" bgcolor="#ffffff">
											<tbody>
											<tr>
												<td height="25" width="100%" align="left">
													<a style=" text-decoration:none;font:12px arial,sans-serif; font-weight:bold;color:#4E4E4E" target="_blank" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}" title="{$p.item_name}">{$p.item_name|truncate:60:"..."}</a>
												</td>
											</tr>
											<tr>
												<td height="15" align="left">
													<font face="arial,sans-serif" title="" style="padding-top:10px;font:12px arial,sans-serif; color:#FF9900">
													{if $p.price neq '0.00'} {if $p.priceorder eq 1} {$p.unit} ${$p.price} {else} ${$p.price} {$p.unit} {/if} {/if} </font>
												</td>
											</tr>
											</tbody>
											</table>
										</td>
									</tr>
									</tbody>
									</table>
									<table width="340" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
									<tbody>
									<tr>
										<td height="10" style="border-bottom:1px solid #CCCCCC">
											&nbsp;
										</td>
									</tr>
									<tr>
										<td height="13">
										</td>
									</tr>
									</tbody>
									</table>
								</td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					 {/if} {/foreach} {/if}
					</tbody>
					</table>
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
			</tbody>
			</table>
		</div>
		<div id="bottom">
			<img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/emailalert/hotbuy_bottom.png" width="642px" />
		</div>
	</div>
{if ! isset($preview)}
</body>
</html>
{/if}