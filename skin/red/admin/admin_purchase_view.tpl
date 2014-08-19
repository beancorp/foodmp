<ul id="table" style="width:720px;">
<li id="lable" style="text-align:right;width:170px;">Market Place</li>
<li id="input" style="width:530px;text-align:left">{if $req.info.attribute eq '0'}Buy & Sell{else}Food & Wine{/if}</li>

<li id="lable" style="text-align:right;width:170px;">Buyer Nickname</li>
<li id="input" style="width:530px;text-align:left">{$req.info.buyer_nickname}</li>

<li id="lable" style="text-align:right;width:170px;">Buyer Email</li>
<li id="input" style="width:530px;text-align:left">{$req.info.buyer_email}</li>

<li id="lable" style="text-align:right;width:170px;">Seller Website Name</li>
<li id="input" style="width:530px;text-align:left">{$req.info.seller_webname}</li>

<li id="lable" style="text-align:right;width:170px;">Seller Email</li>
<li id="input" style="width:530px;text-align:left">{$req.info.seller_email}</li>

<li id="lable" style="text-align:right;width:120px; height:auto">Items
	{foreach from=$req.info.items item=item_name key=k}
		<br />
	{/foreach}
</li>
<li id="input" style="width:580px;text-align:left; height:auto">
{if $req.info.items}
	{foreach from=$req.info.items item=l}
		{$l.item_name}<br />
	{/foreach}
{else}
	No Items.
{/if}
</li>

<li id="lable" style="text-align:right;width:170px;">Total</li>
<li id="input" style="width:530px;text-align:left">{$req.info.amount}</li>

<li id="lable" style="text-align:right;width:170px;">{$lang.main.lb_commission_type}</li>
<li id="input" style="width:530px;text-align:left">{if $req.info.commission_type eq '0'}{$lang.main.lb_commission_type_manual}{else}{$lang.main.lb_commission_type_automatic}{/if}</li>

<li id="lable" style="text-align:right;width:170px;">Commission</li>
<li id="input" style="width:530px;text-align:left">{$req.info.commission}</li>

<li id="lable" style="text-align:right;width:170px;">Total(without commission)</li>
<li id="input" style="width:530px;text-align:left">{$req.info.seller_amount}</li>

<li id="lable" style="text-align:right;width:170px;">Payment Method</li>
<li id="input" style="width:530px;text-align:left">{$req.info.paymethod}</li>

{if $req.info.seller_paypal}
<li id="lable" style="text-align:right;width:170px;">Paypal Account</li>
<li id="input" style="width:530px;text-align:left">{$req.info.seller_paypal}</li>
{/if}

<li id="lable" style="text-align:right;width:170px;">Date</li>
<li id="input" style="width:530px;text-align:left">{$req.info.orderDate|date_format:"$PBDateFormat"}</li>

<li id="lable" style="text-align:right;width:170px;">Payment Status</li>
<li id="input" style="width:530px;text-align:left">{$req.info.p_status}</li>
<li id="lable" style="text-align:right;width:170px;"> </li>
<li id="input" style="width:530px;text-align:left">
  <input name="back" type="button" class="hbutton" id="back" value="{$lang.but.back}" onClick="javascript:xajax_getpurchaseRecords('{$req.info.pageno}',xajax.getFormValues('mainSearch'))">
</li>
</ul>
