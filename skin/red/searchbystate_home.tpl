{literal}
<script>new YAHOO.Hack.FixIESelectWidth('state_subburb');</script>
{/literal}

<form action="soc.php?cp=home" id="statesearch" class="st-connecticut" method="POST">
	<input type="hidden" name="cp" value="statepage" />
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
				{foreach from=$req.states item=state}
				<option value="{$state.state}"{$state.selected}>{$state.state}</option>
				{/foreach}
				</select>
			</li>
			<li>
				<span class="select-box">
				<select name="selectSubburb" class="region" id="state_subburb" style="width:140px;">
				{foreach from=$req.cities item=city}
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
		{foreach from=$req.ads item=ads}
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
            <table cellpadding="0" cellspacing="0" border="0" width="525" background="/skin/red/images/{if $ads.fake eq '1'}state_fake_background{else}state-background{/if}.gif" {if $ads.fake ne '1'}height="192"{/if}>
            	<tr>
                	<td><img src="/skin/red/images/{if $ads.fake eq '1'}state_fake_top{else}state-top{/if}.gif" border="0" /></td>
                </tr>
            	<tr style="height:152px">
                	<td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>
                          <td width="24" align="right"><img src="/skin/red/images/li-orange.gif" width="12" height="10" /></td>
                          <td><a style="font-size:13px;font-weight:bold;color:#352C7B;text-decoration:none;" href="/{$ads.url}">{$ads.website}</a>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="6" colspan="3"><img src="/skin/red/images/spacer.gif" /></td>
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
								  <ul style="list-style:none; list-style-image:url(/skin/red/images/arrow-statepage.gif);margin:0;padding:0 0 0 15px;;">
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
                	<td><img src="/skin/red/images/{if $ads.fake eq '1'}state_fake_bottom{else}state-bottom{/if}.gif" border="0" /></td>
                </tr>
            </table>
        {else}
		
		<table cellpadding="0" cellspacing="0" border="0" width="525" background="/skin/red/images/{if $ads.fake eq '1'}state_fake_background{else}state-background{/if}.gif" {if $ads.fake ne '1'}height="192"{/if}>
            	<tr>
                	<td><img src="/skin/red/images/{if $ads.fake eq '1'}state_fake_top{else}state-top{/if}.gif" border="0" /></td>
                </tr>
            	<tr>
                	<td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                
                        <tr>
                          <td width="24" align="right">&nbsp;</td>
                          <td align="left"><img src="/skin/red/images/state-fake_arrow.jpg" /> <a style="font-size:15px;font-weight:bold;color:#352C7B;padding:0;margin:0;text-decoration:none;" href="/soc.php?cp=statelink">{$ads.title}</a></td>
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
                	<td><img src="/skin/red/images/{if $ads.fake eq '1'}state_fake_bottom{else}state-bottom{/if}.gif" border="0" /></td>
                </tr>
            </table>
			
			<table cellpadding="0" cellspacing="0" border="0" width="525" background="/skin/red/images/{if $ads.fake eq '1'}state_fake_background{else}state-background{/if}.gif" {if $ads.fake ne '1'}height="192"{/if}>
            	<tr>
                	<td><img src="/skin/red/images/{if $ads.fake eq '1'}state_fake_top{else}state-top{/if}.gif" border="0" /></td>
                </tr>
            	<tr>
                	<td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                
                        <tbody><tr>
                          <td width="24" align="right">&nbsp;</td>
                          <td align="left"><img src="/skin/red/images/state-fake_arrow.jpg"> <a style="font-size: 15px; font-weight: bold; color: rgb(53, 44, 123); padding: 0pt; margin: 0pt; text-decoration: none;" href="/soc.php?cp=statelink">BE A FEATURED LOCAL LISTING - IT'S FREE.</a></td>
                          <td width="24">&nbsp;</td>
                        </tr>
                                                            </tbody>
                    </table>
					</td>
                </tr>
            	<tr>
                	<td><img src="/skin/red/images/{if $ads.fake eq '1'}state_fake_bottom{else}state-bottom{/if}.gif" border="0" /></td>
                </tr>
            </table>
		
        {/if}
		</li>
		{/foreach}
	</ul>
	
{literal}
<style type="text/css">
	#sys_ad {
		line-height:25px; 
		margin-bottom:6px;
	}

	/*	google	*/
	@media screen and (-webkit-min-device-pixel-ratio:0){
		#sys_ad {
			line-height:28px; 
			margin-bottom:1px;
		}
	}
	
	/*	ie7	*/
	#sys_ad {
		*+line-height:25px; 
		*+margin-bottom:1px;
		*+padding-bottom:3px;
	}
	
	
	#left_banner_last{
		margin-top:34px;
	}
	#right_banner_last{
		margin-top:42px;
	}
	/*	ie7	*/
	#left_banner_last{
		*+margin-top:24px;
	}
	#right_banner_last{
		*+margin-top:28px;
	}
	
	/*	ie6	*/
	
</style>
{/literal}