{if !$req.notfull}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.subject}{$req.Subject}</title>
</head>

<body>
{/if}
{if $req.notemail}
<table width="100%" border="0" cellpadding="2" id="offerViewEmail"><tr><td>
{/if}


{if $req.display eq 'accept'}

<p>Dear {$req.buyer_nickname},<br>
  <br>
  The seller has accepted your offer of {math equation ="x"  x=$req.offerTotal format="$%.2f"}. You can pay for the item with the following coupon code by using one of the authorised payment options.<br>
  <br>
  The coupon code is: <font style="color:Red">{$req.couponCode}</font><br/>
  <br>
  Please click on the following link to continue the purchase <br/><br/>
  ( {if $req.notemail}Product Link{else}<a href="http://{$req.base_url}/{$req.seller_url}/{$req.item_url}">http://{$req.base_url}/{$req.seller_url}/{$req.item_url}</a>{/if} )
  <br/>
  <br/> or <br/><br/>
Please click on the following link to contact the seller in order to proceed with the purchase process. </p>

<p>( {if $req.notemail}Review Link{else}<a href="{$req.reviewLink}" target="_blank">{$req.reviewLink}</a>{/if} )</p>
<p>Sincerely,<br>
{$smarty.session.UserName}</p>

{elseif $req.display eq 'offer'}

<p> Dear {$req.seller_nickname}  </p>
<p>This is a Food Marketplace email alert. Please check-in to your <a href="{$req.webside_link}" target="_blank">Food Marketplace</a> admin area. You have an offer waiting for you.</p>
<p>Sincerely,<br>
Food Marketplace Australia</p>

{else}

<p>Dear {$req.buyer_nickname},<br>
  <br>
  Your offer of {math equation ="x"  x=$req.offerTotal format="$%.2f"} has not been accepted by (<a href="http://socexchange.com.au/{$req.seller_url}" target="_blank">{$req.seller_name}</a>). Please feel free to submit another offer to the seller.</p>
<p>Sincerely,<br>
{$smarty.session.UserName}</p>

{/if}

{if $req.notemail}
</td>
</tr>
<tr><td>
<a href="javascript:xajax_offerViewEmail('','back');void(0);"><img src="/skin/red/images/buttons/or-back.gif" border="0"/></a>
{if $req.display eq 'accept'}
<a href="javascript:{literal}if(confirm('Are you sure to resend the email?')){{/literal}xajax_resendEmail('{$req.id}'){literal}}{/literal};void(0);">Resend Email</a>
{if $req.coupon_used}
<a href="javascript:{literal}if(confirm('Are you sure to active the Coupon Code? \r\nNote: The email will be resent again to the customer after the coupon code actived.')){{/literal}xajax_activeCoupon('{$req.id}'){literal}}{/literal};void(0);">Active Coupon Code</a>{/if}
{/if}
</td></tr>
<tr><td style="color:RED" align="center" id="cusmsg"></td></tr>
</table>
{/if}

{if !$req.notfull}
</body>
</html>
{/if}