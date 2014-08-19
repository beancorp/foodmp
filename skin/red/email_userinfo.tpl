{if !$req.notfull}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.Subject}</title>
</head>

<body>
{/if}

{if $req.display eq 'regedit'}

Dear {$req.seller_name},
<br />
<br />

{if $req.attribute eq 5}
Congratulations and Welcome. <br />
Your 1 year subscription to the ‘Food Marketplace’ Food & Wine market has been confirmed.<br />
You can now publish your specials and connect with your customers like never before! <br />
Online shopping is a feature that you can activate at any time. <br />
Your ‘SOC site’ can be accessed via your SOC Administration System 24/7.<br />
{else}
Congratulations and Welcome to ʻFood Marketplaceʼ. <br />
You are now a member of our {if $req.attribute eq 1}Real Estate{elseif $req.attribute eq 2}Auto{elseif $req.attribute eq 3}Careers{else}Auctions + Buy Now{/if} marketplace.<br />
You can access your SOC website via your SOC Administration System 24/7. <br />
{/if}
Below are your login details, please keep them secure.  
<br />
<br />

{if $req.attribute eq 5}Username: {$req.bu_username}{else}Email address: {$req.bu_user}{/if} <br />
Password: {$req.bu_password}

{elseif $req.display eq 'keepup'}
Dear {$req.seller_name},
<br />

Your account has been activated and remains activated till {$req.expiringDate} <br />

{elseif $req.display eq 'upgrade'}
Dear {$req.seller_name},
<br />

<strong>Your account has been upgraded and you can make changes to your website at anytime by logging in at www.socexchange.com.au </strong><br />
{if $req.attribute eq 5}Username: {$req.bu_username}{else}Email address: {$req.bu_user}{/if}<br />
Password: {$req.bu_password}<br />
{/if}
<br />
<br />

Sincerely, <br />
Food Marketplace Australia<br />
{if $req.attribute ne 1 && $req.attribute ne 2 && $req.attribute ne 3 && $req.display eq 'regedit' && 0}*As per the terms of use.{/if}
{if !$req.notfull}
</body>
</html>
{/if}