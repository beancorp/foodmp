{if !$req.notfull}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.Subject}</title>
</head>

<body>
{/if}

{if $req.display eq 'conseller'}

<p> Dear {$req.seller_nickname}, </p>
<p>This is a Food Marketplace email alert. Please check-in to your <a href="{$req.msg_link}">Food Marketplace</a> admin area. You have an email waiting for you.</p>

{elseif $req.display eq 'conseller_detail'}

<p> Dear {$req.seller_nickname}, </p>
<p>
<table>
<tr>
	<td>Email:</td>
	<td>{$req.fromEmail}</td>
</tr>
<tr>
	<td>Name:</td>
	<td>{$req.fromName}</td>
</tr>
<tr>
	<td>Contact Phone:</td>
	<td>{$req.fromPhone}</td>
</tr>
<tr>
	<td>Subject:</td>
	<td>{$req.Subject}</td>
</tr>
<tr>
	<td valign="top">Message:</td>
	<td>{$req.message|nl2br}</td>
</tr>
</table>
</p>
{elseif $req.display eq 'adminsend'}
{$req.message|nl2br}
{elseif $req.display eq 'reply'}

<p> Dear {$req.buyer_nickname}, </p>
<p>This is a message from SOCExchange.com.au website: <a href="{$req.webside_link}">{$req.seller_nickname}</a></p>
<p>{$req.thebodyofmessage|nl2br}</p>
<p>If no email address has been provided in the message above, please return to the seller's Food Marketplace store homepage and hit 'contact seller' to respond.  Do not reply to this message - Thanks.</p>
{elseif $req.display eq 'contact'}
{$req.message|nl2br}<br/>
<br/>
{if $req.cusType eq 'buyer'}
You can <a href="{$req.webside_link}/soc.php?cp=sendmail&StoreID={$req.StoreID}&buyer={$req.buyer}&title=RE%3A{$req.subject|escape:url}">contact the seller [{$req.seller_nickname}]</a> by sending a message through Food Marketplace Australia.
{else}
You can <a href="{$req.webside_link}/soc.php?cp=sendmail&StoreID={$req.StoreID}&buyer={$req.buyer}&title=RE%3A{$req.subject|escape:url}">contact the buyer [{$req.seller_nickname}]</a> by sending a message through Food Marketplace Australia.
{/if}
{/if}
{if $req.display neq 'adminsend'}
<p>Sincerely,<br>
Food Marketplace Australia</p>
{/if}
{if !$req.notfull}
</body>
</html>
{/if}