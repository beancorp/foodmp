{if !$req.notfull}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.Subject}</title>
</head>

<body>
{/if}

<p> Dear Admin,</p>
{if $req.bu_procode neq ""}
<p> <strong>{$req.seller_name}</strong> has successfully signed up for an account.</p>
{else}
<p> <strong>{$req.seller_name}</strong> has successfully paid for his account:</p>
{/if}
<p> Type: {$req.type}<br>
{if $req.bu_procode neq "" and $req.type eq "Seller Registration"}
	Promotion Code: {$req.bu_procode}
{elseif $req.attribute eq 5 and $req.type eq "Seller Registration"}
	Amount: $120 charged.  <br>
	Card Number: {$req.cardNumber}  <br>
	{if $req.expiryMonth neq ""}
		Expiry Date: {$req.expiryMonth} / {$req.expiryYear}
	{/if}
{else}
	Amount: Free  <br>
	Card Number: None  <br>
	{if $req.expiryMonth neq ""}
		Expiry Date: {$req.expiryMonth} / {$req.expiryYear}
	{/if}
{/if}
</p>
<p>
  <strong>Following is user's detail:</strong>
  <br>
  {if $req.attribute eq 5}
  Username: {$req.bu_username} <br>
  {/if}
  User's email: {$req.bu_email} <br>
  Nickname: {$req.bu_nickname} <br>
  Website name: {$req.bu_name} <br>
  Address: {$req.bu_address} <br>
  State: {$req.stateName} <br>
  City/ Suburb: {$req.bu_suburb} <br>
  Post Code: {$req.bu_postcode} <br>
  Phone Area Code: {$req.bu_area} <br>
  Phone: {$req.bu_phone} <br>
  {if !$req.hideExpDate && $req.atrribute neq '0'}
  Expiry Date: {$req.expiringDate}  <br>
  {/if}
</p>
  
  
<p>Sincerely,<br>
Food Marketplace Australia</p>

{if !$req.notfull}
</body>
</html>
{/if}