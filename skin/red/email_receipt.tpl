<html>
<body style="width:700px;font-size: 12pt;font-family: Arial, Helvetica, Sans-serif;">
<img src='http://foodmarketplace.com/images/skin/red/email_referer/header1.jpg'>
<div id='statuscontainer' style="width:100%;background-color:{if $req.status}#393{else}#933{/if};color:#FFF;height:36px;padding-top:10px;">
	<span id='status' style="font-size:16pt;margin: 10px;">Transaction receipt: {if $req.status eq true}approved{else}declined{/if}</span>
	<span id='date' style="font-size: 12pt;float:right;width:100px;margin: 5px;text-align: right;">{if $req.txnID}#{$req.txnID}{/if}</span>
</div>
{if $req.status}<p>Your payment has been processed successfully. Your website is now active.</p>
{else}<p>Sorry, your payment was declined. Check below for details</p>{/if}
<img id='eway-logo' style="float:right;margin-right: 30px;" src='http://foodmarketplace.com/images/skin/red/eway-logo.gif'>
<div id='stats'>
	<table style="background-color: #DDD;font-size: 11pt;width:550px;">
		<tr><th style="text-align: left;">Payment Amount</th><td>${$req.amount}</td></tr>
		{if $req.status}<tr style="background-color: #CCC;"><th style="text-align: left;">Payment Status</th><td>Success</td></tr>
		{else}<tr style="background-color: #CCC;"><th style="text-align: left;">Reason declined</th><td>{$req.error}</td></tr>{/if}
		<tr><th style="text-align: left;">Date/Time</th><td>{$req.date}</td></tr>
		<tr style="background-color: #CCC;"><th style="text-align: left;">Transaction Number</th><td>{if $req.txnID}{$req.txnID}{else}N/A{/if}</td></tr>
		<tr><th style="text-align: left;">Customer Email address</th><td>{$req.bu_email}</td></tr>
	</table>
</div>
<img src='http://foodmarketplace.com/images/skin/red/email_referer/table_layout_b.jpg'>
<p>Kind Regards,<br />{$smarty.const.SITENAME}</p>
</body>