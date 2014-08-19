<html><head><title>Wishlist Order</title></head><body>Dear {$req.nickname},<br/><br/>{$req.message|nl2br}<br/><br/>
To view my wishlist, please follow the link below:<br/><br/><a href="{$req.webside_link}">{$req.seller_name} wishlist</a>{if $req.password}<br/><br/>The password is: {$req.password}{/if}<br/><br/>{$req.signature|nl2br}
</body></html>