<style>
	{literal}
	html {
		background-color: #d8d9db;
	}
	.hotbuy {
		width: 635px;
		margin-left: auto;
		margin-right: auto;
		margin-top: 25px;
	}
	
	.hotbuy_header {
		position: absolute;
		width: 250px;
		height: 144px;
		background-image: url({/literal}{$smarty.const.SOC_HTTP_HOST}skin/red/images/emailalert/{if $req.info.send_type eq 'specials'}specials_header.png{else}hotbuy_header.png{/if}{literal});
		z-index: 10;
		left: 10px;
		top: 25px;
	}
	
	.hotbuy_background {
		position: absolute;
		height: 144px;
		width: 560px;
		left: 85px;
		background-color: #FFFFFF;
		background-image: url({/literal}{if $req.template.emailalert_image neq ''}{$smarty.const.SOC_HTTP_HOST}{$req.template.emailalert_image}{else}{$smarty.const.SOC_HTTP_HOST}template_images/hotbuy/{$req.info.subAttrib}.jpg{/if}{literal});
		background-repeat: no-repeat;
		z-index: 1;
		top: 25px;
	}
	
	.hotbuy_body {
		position: absolute;
		background-color: #FFF;
		padding-top: 20px;
		border-bottom-left-radius: 10px;
		border-bottom-right-radius: 10px;
		width: 635px;
		left: 10px;
		top: 160px;
		z-index: 1;
	}
	
	.title_pdf {
		padding: 10px;
	}
	
	.content_pdf {
		clear: both;
		width: 600px;
	}
	
	.left_side_pdf {
		background-color: #EBEBEB;
		width: 230px;
		padding: 5px;
		border-radius: 10px;
	}
	
	.right_side_pdf {
		width: 300px;
	}
	
	.side_content_pdf td {
		padding: 5px;
		font-size: 10px;
	}
	
	.side_content_pdf td * {
		font-size: 10px;
	}
	
	{/literal}
</style>
<page backtop="7mm" backbottom="7mm" backleft="10mm" backright="10mm" backcolor="#d8d9db" style="font-size: 10pt">
	<div class="hotbuy_background"></div>
	<div class="hotbuy_header"></div>
	<div class="hotbuy_body">
	
		<div class="title_pdf">
			<a href="{$smarty.const.SOC_HTTP_HOST}{$req.info.bu_urlstring}" title="{$req.info.bu_name}" target="_blank" style="padding:0; margin:0; text-align:left; color:#3e3183; font-size:24px; font-weight:bold; display:block; text-decoration:none;">{$req.info.bu_name}</a>
			<br />
			<hr />
			<span style="color:#3C3380; padding:0; margin:0; position:relative; text-align:left; font:arial,sans-serif;width:605px; font-size:14px; font-weight:normal">&nbsp;&nbsp;<strong style="color:#3C3380;font:arial,sans-serif;font-size:16px; font-weight:bold">Valid:</strong> {$req.info.start_date} - {$req.info.end_date} - while stocks last</span>
			<hr />
		</div>
		
		<table class="content_pdf">
			<tr>
				<td>
					<div class="left_side_pdf">
						<table class="side_content_pdf">
							<tr>
								<td>Address:</td>
								<td>
									<div style="font-style:normal;font:12px arial,sans-serif; font-weight:bold; color:#777777; width: 150px;">
										{if $req.info.address_hide == 0 }{$req.info.bu_address}<br />{/if}{$req.info.bu_suburb}, {$req.info.bu_state}
									</div>
									<br />
									<a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=map&StoreID={$req.info.StoreID}&key={$req.info.bu_address},{$req.info.bu_suburb},{$req.info.bu_state}"><img border="0" src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/icon_location.gif"/></a>
									<a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=map&StoreID={$req.info.StoreID}&key={$req.info.bu_address},{$req.info.bu_suburb},{$req.info.bu_state}">View Map</a>
								</td>
							</tr>
							{if $req.info.phone_hide == 0 }
							<tr>
								<td>Phone:</td>
								<td>
									<font style="font-style:normal;font:12px arial,sans-serif; font-weight:bold; color:#777777;">
									{$req.info.bu_phone}
									</font>
								</td>
							</tr>
							{/if}
							<tr>
								<td>Rating:</td>
								<td>
									{if $req.aveRating == 0} No Ratings {else} <img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/star_{$req.aveRating}.png"/>{/if}
								</td>
							</tr>
							<tr>
								<td>Opening Hours:</td>
								<td>
									<div style="font-style:normal;font:10px arial,sans-serif;color:#777777;line-height:20px; width: 150px;">{$req.info.opening_hours}</div>
								</td>
							</tr>
							{if $req.info.sold_status eq '1' && $req.info.foodwine_type eq 'food'}
							 <tr>
								<td colspan="2">
									<p>
										Payments Accepted:<br/>
										<span class="payment" style="text-align:left; font-weight:bold;">
										{foreach from=$req.info.payments item=lps key=key} {if $lang.Payments[$key].image ne '' } <img src="{$smarty.const.SOC_HTTP_HOST}{$lang.Payments[$key].image}" align="absmiddle"/>
										{if $lps[1]},{/if} {else} {if $lang.Payments[$key].text neq ""} {$lang.Payments[$key].text} {if $lps[1]},{/if} {/if} {/if} {/foreach} </span>
									</p>
									{if $req.info.sold_status eq '1'} 
										{if $sellerhome eq "1" || 1}
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
										{/if}
									{/if}
								</td>
							 </tr>	 
							{/if}
						</table>
					</div>
				</td>
				<td>
					<div class="right_side_pdf">
						<table width="300px" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
							<tbody>
							{if $req.products.items}
							{assign var=counter value=1}
							{foreach from=$req.products.items item=p key=k} 
							{if $req.products.total eq 1}
							<tr>
								<td valign="top" align="right">
									<table width="300px" cellspacing="0" cellpadding="0">
									<tbody>
									<tr>
										<td valign="top" align="right">
											<table width="300px" cellspacing="0" cellpadding="0">
											<tbody>
											<tr>
												<td valign="top" align="right">
													<a style=" text-decoration:none;font:12px arial,sans-serif; font-weight:bold;color:#4E4E4E" target="_blank" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}" title="{$p.item_name}">
													<img src="{if $p.big_image}{$smarty.const.SOC_HTTP_HOST}{$p.big_image}{else}{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg{/if}" width="356" alt="{$p.name}" title="{$p.name}"/></a>
												</td>
											</tr>
											<tr>
												<td valign="top" align="left">
													<table width="300px" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
													<tbody>
													<tr>
														<td height="55" align="left">
															<div style=" padding-left:15px;font:18px arial,sans-serif; color:#777777;font-weight:normal; ">{$p.item_name|truncate:60:"..."}</div>
														</td>
													</tr>
													<tr>
														<td height="25">
															<div style=" padding-left:15px;font:16px arial,sans-serif; color:#FF9900; font-weight:bold;">{if $p.price neq '0.00'}{if $p.priceorder eq 1} {$p.unit} ${$p.price} {else} ${$p.price} {$p.unit} {/if}{/if}</div>
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
							
							{if $counter eq 8}
							</tbody>
							</table>
					</div>
				</td>
			</tr>	
		</table>
	</div>
</page>
<page backtop="7mm" backbottom="7mm" backleft="10mm" backright="10mm" backcolor="#d8d9db" style="font-size: 10pt">
	<div class="hotbuy_background"></div>
	<div class="hotbuy_header"></div>
	<div class="hotbuy_body">
	
		<div class="title_pdf">
			<a href="{$smarty.const.SOC_HTTP_HOST}{$req.info.bu_urlstring}" title="{$req.info.bu_name}" target="_blank" style="padding:0; margin:0; text-align:left; color:#3e3183; font-size:24px; font-weight:bold; display:block; text-decoration:none;">{$req.info.bu_name}</a>
			<br />
			<hr />
			<span style="color:#3C3380; padding:0; margin:0; position:relative; text-align:left; font:arial,sans-serif;width:605px; font-size:14px; font-weight:normal">&nbsp;&nbsp;<strong style="color:#3C3380;font:arial,sans-serif;font-size:16px; font-weight:bold">Valid:</strong> {$req.info.start_date} - {$req.info.end_date} - while stocks last</span>
			<hr />
		</div>
		
		<table class="content_pdf">
			<tr>
				<td>
					<div class="left_side_pdf" style="background-color: #FFF;">
					
					</div>
				</td>
				<td>
					<div class="right_side_pdf">
						<table width="300px" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
							<tbody>
							
								{assign var=counter value=1}
							{/if}
							
							<tr>
								<td width="300px" valign="top" style="width: 300px; padding:0">
									<table width="300px" cellspacing="0" cellpadding="0">
									<tbody>
									<tr>
										<td valign="top">
											<table width="300px" cellspacing="0" cellpadding="0">
											<tbody>
											<tr>
												<td valign="top" width="80" style="padding-right:10px;">
													<a style=" text-decoration:none;font:12px arial,sans-serif; font-weight:bold;color:#4E4E4E" target="_blank" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}" title="{$p.item_name}">
													<img src="{if $p.small_image}{$smarty.const.SOC_HTTP_HOST}{$p.small_image}{else}{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg{/if}" width="80" alt="{$p.name}" title="{$p.name}"/></a>
												</td>
												<td width="220px" valign="top" align="left">
													<table cellspacing="0" cellpadding="0" bgcolor="#ffffff">
													<tbody>
													<tr>
														<td height="25" align="left">
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
										</td>
									</tr>
									</tbody>
									</table>
									<hr />
								</td>
							</tr>
							
							{assign var=counter value=$counter+1}
							
							{/if} 
							{/foreach} 
							{/if}
							</tbody>
							</table>
					</div>
				</td>
			</tr>	
		</table>
	</div>
</page>