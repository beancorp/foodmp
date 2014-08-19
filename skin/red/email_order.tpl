<html><head><title>Seller Order</title></head><body>
{if $req.accept eq 'seller'}Dear {$req.seller_nickname},
<br><br>{$req.buyer_nickname} has initiated the purchase of the following item(s) from your website:
<br><br>Product Name: <a href="{$req.productLink}">{$req.item_name}</a>
<br>Product Code: {$req.product_code}<br>Cost: ${$req.cost}{if $req.isattachment eq '0'}
<br>Quantity: {$req.quantity}<br>Shipping cost: ${$req.shippingCost|number_format:2:'.':''}{/if}
<br>Payment method: {$req.payment_method}
<br/>Total: ${$req.totalCost}<br><br>
<span style="color:#F00">Buyer's Information<br>
Nickname: <a href="{$req.contactUrl}">{$req.buyer_nickname}</a><br>
Email: {$req.buyer_email}<br>
{if $req.buyer_phone ne '0' and $req.buyer_phone ne ''}Phone: {$req.buyer_phone}{/if}
</span>
<br/><br/><span style="color:#F00">You can send a message to the buyer by clicking on their nickname.</span><br>{if $req.reviewKey ne 'temp'}<br>Please click on the following link to enter a review for this buyer:<br>({$req.reviewUrl})<br>
{/if}<br>
Buyer's transaction is currently being processed, and will be complete upon receipt of his or her payment at your designated account.<br><br>{elseif $req.accept eq 'buydown'}Dear {$req.buyer_nickname},<br><br>You have successfully paid for the following downloadable item(s) from my website:
<br><br>Product Name: <a href="{$req.productLink}">{$req.item_name}</a><br>Product Code: {$req.product_code}<br>Cost: ${$req.cost}<br>Payment method: {$req.payment_method}<br>Total: ${$req.totalCost}<br><br>Please click on the following link to download the item(s):<br>(<a href="{$req.reviewUrl}">{$req.reviewUrl}</a>)<br><span style="color:#FF0000">The file can only be downloaded once and must be downloaded within 24 hours.</span><br><br>We appreciate your business and thank you!<br><br>Sincerely,<br>{$req.seller_name} HOT SHOP{else}Dear {$req.buyer_nickname},<br><br>You have initiated the purchase of the following item(s) from my website:<br><br>Product Name: <a href="{$req.productLink}">{$req.item_name}</a><br>Product Code: {$req.product_code}<br>Cost: ${$req.cost}{if $req.isattachment eq '0'}<br>Quantity: {$req.quantity}<br>
Shipping cost: ${if $req.shippingCost > 0}{$req.shippingCost}{else}{0|number_format:2:'.':''}{/if}{/if}<br>Payment method: {$req.payment_method}<br/>Total: ${$req.totalCost}<br>
{if $req.reviewKey ne 'temp'}<br>
Please click on the following link to enter a review for this purchase:<br>({$req.reviewUrl})<br>
{/if}<br>
Your transaction is currently being processed, and will be complete upon Seller's receipt of your payment at the designated account.<br><br>
You can <a href="{$req.contactUrl}">contact the seller</a> by sending a message through Food Marketplace.<br/><br />
<span style="color:#F00">Seller's Information<br />
Nick Name: {$req.seller_nickname}<br />
Email Address: {$req.seller_email}<br />
{if $req.seller_phone ne '0' and $req.seller_phone ne ''}Phone: {$req.seller_phone}{/if}
</span>
<br />
<br/>We appreciate your business and thank you!<br><br>Sincerely,<br>{$req.seller_name} HOT SHOP{/if}</body></html>
