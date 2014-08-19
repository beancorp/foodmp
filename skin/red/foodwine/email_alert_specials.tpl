
<div style="margin:0; text-align:center; width:100%;">
<table align="center" width="100%" bgcolor="d8d9db">
<tr>
<td>
<table width="100%" border="0">

<tbody>

<tr><td align="center">
  <table width="645" cellspacing="0" cellpadding="0" border="0">
    
    <tbody>
    
    <tr>
      <td bgcolor="#d8d9db">&nbsp;</td>
      <td colspan="2" valign="top">
        <table width="639" cellspacing="0" cellpadding="0" border="0">
          <tbody>
          <tr>
        <td height="149" bgcolor="#FFFFFF">
        <img src="{$soc_http_host}/skin/red/images/emailalert/emailalert-specials-left-top.jpg" width="254" height="149" /> 
        </td>
        <td valign="top" rowspan="2" height="214" bgcolor="#FFFFFF">
        	<img src="{$soc_http_host}/skin/red/images/emailalert/emailalert-{$req.info.foodwine_type}-specials-right.jpg" width="387" height="214" />
        </td>
          </tr>
          <tr>
          	<td width="254" height="65" bgcolor="#FFFFFF" valign="top" style="padding-left:18px;"><span style="padding:0; margin:0; text-align:left; color:#3e3183; font-size:24px; font-weight:bold; display:block; text-decoration:none">{$req.info.bu_name}</span>  </td>
          </tr>
        </tbody>
        </table>     
        </td>
      <td bgcolor="#d8d9db">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#d8d9db">&nbsp;</td>
      <td colspan="2">
      <table width="100%" bgcolor="#FFFFFF">
      <tbody>
      <tr>
      <td bgcolor="#FFFFFF" width="20">&nbsp;</td>
    <td bgcolor="#3C3380" align="center" height="25"><h2 style=" color:#fff; padding:0; margin:0; position:relative; text-align:left; font:arial,sans-serif;width:605px; font-size:14px; font-weight:normal">&nbsp;&nbsp;<strong style="color:#fff;font:arial,sans-serif;font-size:16px; font-weight:bold">Valid:</strong>  {$req.info.start_date} - {$req.info.end_date} - while stocks last</h2></td>
      <td bgcolor="#FFFFFF" width="20">&nbsp;</td>
    </tr>
    </tbody>
    </table>
    </td>
      <td bgcolor="#d8d9db">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#d8d9db">&nbsp;</td>
      <td valign="top" bgcolor="#ffffff" width="260" align="center">
          <table width="242" cellspacing="0" cellpadding="0" bgcolor="#EBEBEB">
              <tbody>
              <tr>
                <td height="5" bgcolor="#FFFFFF">&nbsp;</td>
              </tr>
              <tr height="24">
              <td colspan="2" height="24" bgcolor="#ebebeb">
              <img src="{$soc_http_host}/skin/red/images/emailalert/email-alert-body-left-top.jpg" width="243" height="24" />
              </td>
              </tr>
              <tr>
                <td valign="middle" height="39" colspan="2">
                  <table cellspacing="0" cellpadding="0" border="0" width="184">
                    <tbody><tr>
                      <td width="15"></td>
                      <td width="45"><font style="font-style:normal;font:12px arial,sans-serif;color:#777777;">Address:</font></td>
                      <td width="15"></td>
                      <td align="left">
                        <font style="font-style:normal;font:12px arial,sans-serif; font-weight:bold;  color:#777777;">
                          {$req.info.bu_suburb}, {$req.info.bu_state}<br />{if $req.info.address_hide == 0 }{ $req.info.bu_address}{/if}
                        </font>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
              <tr>
                <td valign="middle" height="19" colspan="2">
                  <table cellspacing="0" cellpadding="0" border="0" width="184">
                    <tbody><tr>
                      <td width="15"></td>
                      <td width="45">&nbsp;</td>
                      <td width="15"></td>
                      <td align="left">
                        <a href="{if $headerInfo.address_hide==1}javascript:alert('Address not listed');{else}/soc.php?cp=map&StoreID={$headerInfo.StoreID}&key={$headerInfo.bu_address},{$headerInfo.bu_suburb},{$headerInfo.bu_state}{/if}"><img border="0" src="{$soc_http_host}/skin/red/images/icon_location.gif"/></a>&nbsp;<a style=" position:relative; bottom:7px;color:#777777; font:12px arial,sans-serif; " href="{if $headerInfo.address_hide==1}javascript:alert('Address not listed');{else}{$soc_http_host}/soc.php?cp=map&StoreID={$headerInfo.StoreID}&key={$headerInfo.bu_address},{$headerInfo.bu_suburb},{$headerInfo.bu_state}{/if}">View Map</a>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
              {if $req.info.phone_hide == 0 }
              <tr>
                <td valign="middle" height="25" colspan="2">
                  <table cellspacing="0" cellpadding="0" border="0" width="184">
                    <tbody><tr>
                      <td width="15"></td>
                      <td width="45"><font style="font-style:normal;font:12px arial,sans-serif;color:#777777;">Phone:</font></td>
                      <td width="15"></td>
                      <td align="left">
                        <font style="font-style:normal;font:12px arial,sans-serif; font-weight:bold;  color:#777777;">
                          {$req.info.bu_phone}
                        </font>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
              {/if}
              <tr>
                <td valign="middle" height="25" colspan="2">
                  <table cellspacing="0" cellpadding="0" border="0" width="184">
                    <tbody><tr>
                      <td width="15"></td>
                      <td width="45"><font style="font-style:normal;font:12px arial,sans-serif;color:#777777;">Rating: </font></td>
                      <td width="15"></td>
                      <td align="left">
                          {if $req.aveRating eq 0}
                        <font style="font-style:normal;font:12px arial,sans-serif; font-weight:bold;  color:#F3B216; vertical-align:middle">No Ratings{else}<img src="{$soc_http_host}/skin/red/images/star_{$req.aveRating}.png" />{/if}
                        </font>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
              <!--<tr>
                <td valign="middle" height="25" colspan="2">
                  <table cellspacing="0" cellpadding="0" border="0" width="184">
                    <tbody><tr>
                      <td width="15"></td>
                      <td width="45"><font style="font-style:normal;font:12px arial,sans-serif;color:#777777;">Reviews:</font></td>
                      <td width="15"></td>
                      <td align="left">
                          {$req.info.reviews}&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="soc.php?cp=disreview&StoreID={$req.info.StoreID}" style="color:#777777;"><strong style="color:#777777; font:12px arial,sans-serif;">Write a review</strong></a>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>-->
              {if $req.info.opening_hours neq ""}
              <tr>
                <td valign="middle" height="25" colspan="2">
                  <table cellspacing="0" cellpadding="0" border="0" width="184">
                    <tbody><tr>
                      <td width="15"></td>
                      <td colspan="3"><font style="font-style:normal;font:12px arial,sans-serif;color:#777777;">Opening Hours:</font></td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
              <tr>
                <td valign="middle" height="25" colspan="2">
                  <table cellspacing="0" cellpadding="0" border="0" width="184">
                    <tbody><tr>
                      <td width="15"></td>
                      <td colspan="3">
                      <font style="font-style:normal;font:12px arial,sans-serif;color:#777777;">
                          {$req.info.opening_hours}
                      </font>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
              {/if}
              {if $req.info.facebook neq ""}
              <tr>
                <td valign="middle" height="25" colspan="2">
                  <table cellspacing="0" cellpadding="0" border="0" width="184">
                    <tbody><tr>
                      <td width="15"></td>
                      <td align="left">
                          <a style="color:#777777; text-decoration:underline" href="{$req.info.facebook}" target="_blank"><img style="float:left;" src="{$soc_http_host}/skin/red/images/facebook.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:underline; cursor:pointer;color:#777777; font:12px arial,sans-serif;">Facebook</span></a>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
              {/if}
              <tr>
                <td valign="top" height="20" style=" font:12px arial,sans-serif; color:#777777; width:30%;"></td>
                <td valign="top" height="20" style=" font-style:normal;font:12px arial,sans-serif; font-weight:bold;  color:#777777;"></td>
              </tr>
              
              <tr><td colspan="2"><img src="{$soc_http_host}/skin/red/images/emailalert/email-alert-body-left-middle.jpg" width="243" height="38" /> </td></tr>
              <tr><td height="30" colspan="2" style="margin:10px; padding:10px;color:#777777; font:12px arial,sans-serif;"><p>Payments Accepted:<br />
      <span class="payment">
	  {foreach from=$req.info.payments item=lps key=key}
	  	{if $key eq 2}{php}continue;{/php}{/if}
	  	{if $lang.Payments[$key].image ne '' }
			<img src="{$soc_http_host}/{$lang.Payments[$key].image}" align="absmiddle" />
			{if $lps[1]},{/if}
		{else}
			{if $lang.Payments[$key].text neq ""}
				{$lang.Payments[$key].text}
				{if $lps[1]},{/if}
			{/if}
		{/if} 
	  {/foreach}
	  </span></p>
      {if $sellerhome eq "1"}
      <p>Shipping for this seller:<br /><span class="payment">
       	{foreach from=$req.info.bu_delivery item=lps key=key}{if $lang.Delivery[$key].text neq ""}{$lang.Delivery[$key].text}{if $lps[1]},{/if}{/if}{/foreach}
      </span></p>
      {else}
      <p>
      	Domestic Shipping for this item<br/>
        <span class="payment">
        {foreach from=$l.deliveryMethod|explode:"|" item=opcl key=oplk}
    		{$lang.Delivery[$opcl].text} {if $opcl eq '1' or $opcl eq '2' or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$l.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}<br/>
    	{/foreach}
    	</span>
      	{if $l.isoversea}
        <br/>Overseas Shipping for this item<br/>
    	<span class="payment">
    	{foreach from=$l.oversea_deliveryMethod|explode:"|" item=opcl key=oplk}
    		{$lang.Delivery[$opcl].text} {if $opcl eq '1' or $opcl eq '2'  or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$l.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}<br/>
    	{/foreach}</span>
        {/if}</p>
      {/if}</td></tr>
      			<tr>

                	<td colspan="2" height="26" bgcolor="#FFFFFF">
                    <img src="{$soc_http_host}/skin/red/images/emailalert/email-alert-body-left-bottom.jpg" width="243" height="26" />
                    </td>
                </tr>
              </tbody>
          </table>
      </td>
      <td valign="top" bgcolor="#ffffff" width="340">
        <table width="237" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
          <tbody>
          <tr>
          	<td height="17">&nbsp;</td>
          </tr>
          {if $req.products.items}
          {foreach from=$req.products.items item=p key=k}
          	{if $req.products.total eq 1}
            <tr>
            <td valign="top" style="padding:0">              
              <table width="340" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                  <td valign="top">
                  
                    <table width="340" cellspacing="0" cellpadding="0">
                      <tbody>
                      <tr>
                  <td valign="top" width="width:100%;" style="padding-right:10px;">
                    
                        <img src="{if $p.small_image}{$soc_http_host}/{$p.small_image}{else}{$soc_http_host}/images/80x58.jpg{/if}" width="width:100%;"  alt="{$p.name}" title="{$p.name}"/>                  
                        </td>
                        </tr>
                       	<tr>
                        <td valign="top">   
                        <table cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                            <tbody>
                            <tr><td height="55" width="100%"><font style="font:18px arial,sans-serif; color:#777777;font-weight:normal; ">{$p.item_name|truncate:60:"..."} - ONLY THIS SUNDAY!</font></td></tr>
                            <tr>
                              <td height="25"><font style="font:16px arial,sans-serif; color:#FF9900; font-weight:bold;">{if $p.price neq '0.00'}${$p.price} {$p.unit}{/if}</font></td>
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
                    
                        <img src="{if $p.small_image}{$soc_http_host}/{$p.small_image}{else}{$soc_http_host}/images/80x58.jpg{/if}" width="80" height="58" alt="{$p.name}" title="{$p.name}"/>                  
                        </td>
                        <td valign="top">   
                        <table cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                            <tbody>
                            <tr><td height="25" width="100%"><font face="arial,sans-serif" title="" style="font:12px arial,sans-serif; font-weight:bold;color:#4E4E4E">{$p.item_name|truncate:60:"..."}</font> </td></tr>
                            <tr>
                              <td height="15"><font face="arial,sans-serif" title="" style="padding-top:10px;font:12px arial,sans-serif; color:#FF9900">
                              {if $p.price neq '0.00'}
                    {if $p.priceorder eq 1}
                		{$p.unit} ${$p.price}
                    {else}
                        ${$p.price} {$p.unit}
                    {/if}
                    {/if}
                    </font> </td>
                            </tr>
                          </tbody>
                          </table>                      
                        </td>
                      </tr>
                    </tbody>
                    </table>                  
                    <table width="340" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tbody>
                <tr><td height="10" style="border-bottom:1px solid #CCCCCC">&nbsp;</td></tr>
                <tr>
                  <td height="13"></td>
                </tr>
              </tbody></table>
                    </td>
                </tr>
              </tbody>
              </table>            
              </td>
          </tr>
          	{/if}
          {/foreach}
          {/if}
        </tbody>
        </table>      </td>
      <td bgcolor="#d8d9db">&nbsp;</td>
    </tr>
    <tr>
      <td>
        <img src="{$soc_http_host}/skin/red/images/emailalert/emailalert-footer-left.jpg" width="17" height="132" />
      </td>
      <td colspan="2">
        <img src="{$soc_http_host}/skin/red/images/emailalert/emailalert-footer-right.jpg" width="641" height="132" />      
      </td>
      <td bgcolor="#d8d9db">&nbsp;</td>
    </tr>
  </tbody></table>
</td></tr>

</tbody></table>
</td>
</tr>
</table>
</div>