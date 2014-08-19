{if !$req.notfull}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.Subject}</title>
</head>

<body>
{/if}
{if $req.reftype eq 'request'}

	<p>Hi Admin,</p>
	<p>The user {$req.nickname} has requested a payment.</p>

	{if $req.checktype eq "1"}
		<p>
		<table cellpadding="0" cellspacing="0">
		<tr><td>Payment Method: </td><td>Cheque</td></tr>
		<tr><td>User Name: </td><td>{$req.name}</td></tr>
		<tr><td>User Address: </td><td>{$req.address|nl2br}</td></tr>
		</table>
		</p>
	{else}
		<p>
		<table cellpadding="0" cellspacing="0">
		<tr><td>Payment Method: </td><td>Paypal</td></tr>
		<tr><td>Paypal Account: </td><td>{$req.name}</td></tr>
		</table>
		</p>
	{/if}
	<p>Sincerely,<br>
	{if $req.email_regards}{$req.email_regards}{else}Food Marketplace{/if}<br/>
	<a href="{$req.webside_link}">{$req.webside_link}</a>
	</p>
{elseif $req.reftype eq 'send'}
	<p>Hi {$req.nickname},</p>
	<p>Food Marketplace has sent you ${$req.amount} USD by {$req.paidtype}. </p>
	
	<p>Sincerely,<br>
	{if $req.email_regards}{$req.email_regards}{else}Food Marketplace{/if}<br/>
	<a href="{$req.webside_link}">{$req.webside_link}</a>
	</p>
{elseif $req.reftype eq 'referrs'}
	<p>Hi {$req.nickname},</p>

	<p>I'd like to tell you about a fantastic new website I've joined. <br/>
	<a href='{$req.webside_link}/soc.php?act=signon&referr={$req.refID}'>SOCExchange.com.au</a></p>
	<p>It’s an online e-commerce community, where for just <strong>$10 per year flat rate</strong>, you can run unlimited auctions and sell as many items as you like. There are absolutely no commissions or extra charges.</p>

	<p>You create your own website within Food Marketplace community, with unique URL, email alerts, blog and loads of other IT features. You can sell everything from household goods and electronics, to cars and real estate, to listing all your job opportunities or even posting your resume!</p>
<p><strong>Step 1.</strong> Go to <a href="{$req.webside_link}/soc.php?act=signon&referr={$req.refID}">www.socexchange.com.au</a><br/>
	<strong>Step 2.</strong> Click on Join Here<br/>
	<strong>Step 3.</strong> Fill in the form and put my Referral ID <strong style="font-size:18px; color:#9966CC;">({$req.refID})</strong> in the 'Referrer' field</p>


	<p>The Food Marketplace is a great opportunity to succeed online, without the extra charges!</p>

<p>Regards,<br/>
{$req.fromName}</p>
	
{/if}

{if !$req.notfull}
</body>
</html>
{/if}