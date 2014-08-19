{if !$req.notfull}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.Subject}</title>
</head>

<body>
{/if}

{if $req.display eq 'product'}

Dear {$req.seller_name},
<br />
<br />

{if $req.attribute eq '1' || $req.attribute eq '3'}
Congratulations on your listing with ‘Food Marketplace’. <br />
Your listing will be active on our platform for {$req.month} month(s). <br />
Your ‘SOC site’ can be accessed via your SOC Administration System 24/7. <br />
Below are your login details, please keep them secure.<br />
{else}
Congratulations on listing your vehicle with ‘Food Marketplace’. <br />
Your listing will be active on our platform until sold. <br />
Your ‘SOC site’ can be accessed via your SOC Administration System 24/7. <br />
Below are your login details, please keep them secure.<br />
{/if}
<br />

Email address: {$req.bu_email}<br />
Password: {$req.password}<br />

{elseif $req.display eq 'year'}
Dear {$req.seller_name},
<br />
<br />

Congratulations, your 1 year subscription to ‘Food Marketplace’ has been confirmed. <br />
You can now publish unlimited listings on your ‘Food Marketplace’ website. <br />
Your ‘SOC site’ can be accessed via your SOC Administration System 24/7. <br />
Below are your login details, please keep them secure. <br />
<br />
<br />

Email address: {$req.bu_email}<br />
Password: {$req.password}<br />

{elseif $req.display eq 'expired_store'}
Dear {$req.seller_name},
<br />
<br />

Your ‘Food Marketplace’ website will expire on {$req.expiring_date}.<br />
You can renew your ‘SOC site’ at any time by logging into your SOC Administration System. <br />
Renewing your subscription before the expiry date will secure your URL, website name, nickname and your listings. <br />

{elseif $req.display eq 'expired_store_today'}

Dear {$req.seller_name},
<br />
<br />

Your ‘Food Marketplace’ website expired today {$req.expiring_date}. <br />
You can renew your website by clicking the link below. <br />
<a href="{$smarty.const.SOC_HTTPS_HOST}soc.php?cp=login&reurl={$req.reurl}" target="_blank" title="{$req.bu_name}">
Login to Food Marketplace and renew your shop
</a><br />

{elseif $req.display eq 'expired_items'}

Dear {$req.seller_name},
<br />
<br />

Your ‘Food Marketplace’ listing(s) will expire. <br /><br />

<table width="559" border="0" cellpadding="0" cellspacing="0" class="" style="">
            <tr height="30" style="background-color:#F7F6F4;">
              <td width="169" height="30" align="left" valign="middle" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-right:none; ">
              Item Name
              </td>
              <td width="490" height="30" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-left:none;">
              Expired Date            
			  </td>
            </tr>
            {foreach from=$req.list item=p}
            <tr height="30" style="background-color:#F7F6F4;">
              <td width="169" height="30" align="left" valign="middle" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-right:none;border-top:none; ">
              <a href="{$smarty.const.SOC_HTTPS_HOST}soc.php?cp=login&reurl={$p.reurl}" target="_blank" title="{$p.item_name}">{$p.item_name}</a>
              </td>
              <td width="490" height="30" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-left:none;border-top:none;">
              {$p.expiring_date}
			  </td>
            </tr>
            {/foreach}
          </table>
<br />
<br />
You can renew your ‘SOC listing’ at any time by logging into your SOC Administration System. <br />
Renewing your listing before the expiry date will secure your URL, website name, nickname and your listing. <br />

{elseif $req.display eq 'expired_items_today'}
Dear {$req.seller_name},<br />
<br />
<br />

Your ‘Food Marketplace’ listing(s) expired today {$req.expiring_date}. <br /><br />

<table width="559" border="0" cellpadding="0" cellspacing="0" class="" style="">
            <tr height="30" style="background-color:#F7F6F4;">
              <td width="169" height="30" align="left" valign="middle" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-right:none; ">
              Item Name
              </td>
              <td width="490" height="30" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-left:none;">
              Expired Date            
			  </td>
            </tr>
            {foreach from=$req.list item=p}
            <tr height="30" style="background-color:#F7F6F4;">
              <td width="169" height="30" align="left" valign="middle" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-right:none;border-top:none; ">
              <a href="{$smarty.const.SOC_HTTPS_HOST}soc.php?cp=login&reurl={$p.reurl}" target="_blank" title="{$p.item_name}">{$p.item_name}</a>
              </td>
              <td width="490" height="30" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-left:none;border-top:none;">
              {$p.expiring_date}
			  </td>
            </tr>
            {/foreach}
          </table>
<br /><br />
You can renew your listing by clicking the link below. <br />
<br />
<br />

<a href="{$smarty.const.SOC_HTTPS_HOST}soc.php?cp=login&reurl={$req.reurl}" target="_blank" title="{$req.bu_name}">
Login to Food Marketplace and renew your shop
</a><br />
{/if}
<br />

Sincerely,<br />
{if $req.email_regards}{$req.email_regards}{else}Food Marketplace Australia{/if}<br />
{if !$req.notfull}
</body>
</html>
{/if}