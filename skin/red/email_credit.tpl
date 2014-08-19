<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$req.subject}</title>
</head>

<body>
Dear {$req.seller_nickname},<br><br>
{$req.buyer_nickname} has initiated the purchase of the following item(s) from your website:<br><br>
Product Name: <a href="{$req.productLink}">{$req.itemName}</a><br><br> Cost: ${$req.price}<br>
Quantity: {$req.quantity}<br>
Shipping cost: ${$req.shippingCost}<br>
Total: ${$req.totalCost}<br><br>
Buyer's email: {$req.buyer_email}<br><br>

Following is customer's detail:<br>
First Name: {$req.firstName}<br>
Last Name: {$req.lastName}<br>
Card Type: {$req.cardType}<br>
Card Number: {$req.cardNumber}<br>
Expiry Date: {$req.expMonth}/{$req.expYear}<br>
Address: {$req.address1} {$req.address2}<br>
Town/City: {$req.city}<br>
State: {$req.state}<br>
Post Code: {$req.postcode}<br>
Email: {$req.emailAddr}<br>
Phone: {$req.phone}<br><br>


Buyer's transaction is currently being processed, and will be complete upon receipt of his or her payment at your designated account.<br><br>
Sincerely,<br>Food Marketplace Australia
</body>
</html>
