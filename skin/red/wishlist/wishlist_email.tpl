<html><head><title>Wishlist Order</title></head><body>{if $req.accept eq 'seller'}Dear {$req.seller_name},<br /><br />{$req.buyer_nickname} has amount to gift you:<br /><br />Product Name: <a href="{$req.productLink}">{$req.item_name}</a><br />Amount: ${$req.amount|number_format:2}<br />Message: <br />{$req.message|nl2br}<br /><br />Total: ${$req.total_amount|number_format:2}{if $req.payInculde neq ""}<br />{$req.payInculde}{/if}<br /><br /><span style="color:#F00">Buyer's nickname: <a href="mailto:{$req.buyer_email}">{$req.buyer_nickname}</a></span><br /><br /><br />Sincerely,<br />{$req.seller_name} HOT SHOP{else}Dear {$req.buyer_nickname},<br /><br />You have gifted an item to <a href="{$req.wishlistLink}">{$req.seller_name}</a>:<br /><br />Product Name: <a href="{$req.productLink}">{$req.item_name}</a><br />Amount: ${$req.amount}<br />Message: <br />{$req.message|nl2br}<br /><br />Total: ${$req.total_amount|number_format:2}{if $req.payInculde neq ""}<br />{$req.payInculde}{/if}<br /><br />Thank you for using SOC Exchange Australia's wishlist system.<br /><br />Sincerely,<br />Admin<br /> The SOChange Australia{/if}</body></html>