
<div style="margin:0; text-align:center; width:100%;">
<table align="center" width="100%" bgcolor="#f5f5f5">
<tr>
<td>
<table width="100%" border="0">

<tbody>
<tr><td align="center">
  <table width="645" cellspacing="0" bgcolor="#f5f5f5" cellpadding="0" border="0">
    
    <tbody>
    
    <tr height="15">
      <td bgcolor="{$req.bgcolor}" height="15">&nbsp;</td>
    <td colspan="2" bgcolor="#FFFFFFF" height="15">
    <img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/foodwine/emailorder-top.jpg" width="640" height="16" /> 
    </td>
      <td bgcolor="{$req.bgcolor}" height="15">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="{$req.bgcolor}">&nbsp;</td>
      <td colspan="2" bgcolor="#FFFFFFF">
      <table width="639" bgcolor="#FFFFFF">
      <tbody>
      <tr>
      <td bgcolor="#FFFFFF" width="5">&nbsp;</td>
    <td bgcolor="#3C3380" align="center" valign="middle" height="25"><h2 style=" color:#fff; padding:0; margin:0; position:relative; text-align:left; font:arial,sans-serif;width:605px; font-size:14px; font-weight:normal">&nbsp;&nbsp;<strong style="color:#fff;font:arial,sans-serif;font-size:16px; font-weight:bold">Order No:</strong>  {$req.ordersn} from {$req.info.bu_name}</h2></td>
      <td bgcolor="#FFFFFF" width="5">&nbsp;</td>
    </tr>
    </tbody>
    </table>
    </td>
      <td bgcolor="{$req.bgcolor}">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="{$req.bgcolor}">&nbsp;</td>
      <td colspan="2" valign="top">
        <table width="639" cellspacing="0" cellpadding="0" border="0">
          <tbody>
          <tr>
        <td valign="top" height="349" bgcolor="#FFFFFF">
        	<table cellspacing="0" cellpadding="0" width="360">
              <tbody>
              <tr height="5"><td>&nbsp;</td></tr>
              <tr>
                <td valign="middle" height="20" colspan="2">
                  <table cellspacing="0" cellpadding="0" border="0" width="360">
                    <tbody>
                    <tr>
                      <td width="15"></td>
                      <td width="25" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Date:</font>&nbsp;&nbsp;<font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.order_date|date_format:"%d/%m/%Y"}</font></td>
                      <td width="85" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Order No:&nbsp;&nbsp;</font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.ordersn}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Buyer's Nick Name: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.buyer_nickname}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Buyer's email: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.buyer_email}</font></td>
                    </tr>
                    <tr><td height="20">&nbsp;</td></tr>
                    <tr height="25">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:14px arial,sans-serif;color:#585858; font-weight:bold;">Billing Information:</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">First Name: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.firstName}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Last Name: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.lastName}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Card Type: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.cardType}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Card Number: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.cardNumber}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Expiry Date: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.expMonth}/{$req.expYear}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Address: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.address1} {$req.address2}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Town/City: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.city}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">State: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.state}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Post Code: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.postcode}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Email: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.emailAddr}</font></td>
                    </tr>
                    <tr height="20">
                      <td width="15"></td>
                      <td width="100" colspan="2" align="left"><font style="font-style:normal;font:12px arial,sans-serif;color:#585858; font-weight:bold;">Phone: </font><font style="font-style:normal;font:12px arial,sans-serif;color:#585858;">{$req.phone}</font></td>
                    </tr>
                  </tbody>
                  </table>
                </td>
              </tr>
              </tbody>
              </table> 
        </td>
        <td valign="top" height="214" bgcolor="#FFFFFF">
        	<img src="{$smarty.const.SOC_HTTP_HOST}{$req.info.images.mainImage.2.bname.text}" width="{$req.info.images.mainImage.2.bname.width}" height="{$req.info.images.mainImage.2.bname.height}" />
        </td>
          </tr>
        </tbody>
        </table>     
        </td>
      <td bgcolor="{$req.bgcolor}">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="{$req.bgcolor}">&nbsp;</td>
      <td valign="top" bgcolor="#ffffff" width="260" align="center" colspan="2">
          <table cellspacing="0" cellpadding="0" width="98%" style="" class="sortable" id="myproducts">
  <colgroup>
  <col width="24%">
  <col width="24%">
  <col width="29%">
  <col width="20%">
  <col width="3%">
  </colgroup>
  <thead>
    <tr bgcolor="#ebebeb" height="30">
      <th align="left"><strong style="font-weight:bold; color:#585858;padding-left:10px;">Item</strong></th>
      <th align="left"><strong style="font-weight:bold; color:#585858">Quantity</strong></th>
      <th align="left"><strong style="font-weight:bold; color:#585858">Price</strong></th>
      <th align="right"><strong style="font-weight:bold; color:#585858">Sub Total</strong></th>
      <th align="right">&nbsp;</th>
    </tr>
  </thead>
  <tbody> 
  {if count($req.product_lists) > 0}
  {foreach from=$req.product_lists item=p key=k}
  <tr height="40">
    <td align="left" style="padding-left:10px; border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">{$p.item_name}</td>
    <td align="left" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">{$p.quantity}</td>
    <td align="left" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">{if $p.priceorder eq 1}{$p.unit} ${$p.price}{else}${$p.price} {$p.unit}{/if}</td>
    <td align="right" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">${$p.amount}</td>
    <td align="right" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">&nbsp;</td>
  </tr>
  {/foreach}
  {/if}
  <tr height="40">
    <td align="left" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">&nbsp;</td>
    <td align="left" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">&nbsp;</td>
    <td align="right" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;"><strong style="font-weight:bold; color:#585858">Delivery Charge</strong></td>
    <td align="right" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">${$req.shipping_cost}</td>
    <td align="right" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">&nbsp;</td>
  </tr>
  <tr height="40">
    <td align="left" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">&nbsp;</td>
    <td align="left" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">&nbsp;</td>
    <td align="right" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;"><strong style="font-weight:bold; color:#585858">Total</strong></td>
    <td align="right" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">${$req.total_money}</td>
    <td align="right" style="border-bottom:1px solid #DDDDDD;font-style:normal;font:12px arial,sans-serif;color:#585858;">&nbsp;</td>
  </tr>
  <tr height="40">
    <td colspan="5" style="border:none;">&nbsp;</td>
  </tr>
  </tbody>
</table>
      </td>
      <td bgcolor="{$req.bgcolor}">&nbsp;</td>
    </tr>
    <tr>
      <td>
        <img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/emailalert/emailalert-hotbuy-footer-left.jpg" width="24" height="134" />
      </td>
      <td colspan="2">
        <img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/emailalert/emailalert-hotbuy-footer-right.jpg" width="641" height="132" />      
      </td>
      <td bgcolor="{$req.bgcolor}">&nbsp;</td>
    </tr>
  </tbody></table>
</td></tr>

</tbody></table>
</td>
</tr>
</table>
</div>