	<div style="margin-bottom:10px;">
		Total Referrals: {if $req.ref.counter > 0}{$req.ref.counter}{else}0{/if} <br/>
		{php}
		//Total Earnings: ${$req.ref.total_amount|number_format:2}
		{/php}
	</div>
	<ul class="mainlist">
	<li>
		<ul class="listhead">
			<li>Date</li>
			<li class="detail" style="">Description</li>
			<!--<li style="border-left:1px solid #FFFFFF">Amount</li>
			<li style="border-left:1px solid #FFFFFF">Balance</li>-->
		</ul>
	</li>
	{if $req.ref.list}
		{foreach from=$req.ref.list item=rpl}
		<li><ul class="list">
				<li>{$rpl.datestamp|date_format:"$PBDateFormat"}</li>
				<li class="detail">
					{if $rpl.type eq 'reg'}
						1 x referral for referring &lt;{$rpl.nickname|escape:'html'}&gt; (&lt;{$rpl.email}&gt;)
					{elseif $rpl.type eq 'product' and $rpl.product_counter eq 1}
						1 x bonus referral. &lt;{$rpl.nickname|escape:'html'}&gt; added a product to their site
					{elseif $rpl.type eq 'product' and $rpl.product_counter eq 5}
						1 x bonus referral. &lt;{$rpl.nickname|escape:'html'}&gt; added 5 products to their site
					{/if}
				</li>
				<!--<li>{if $rpl.amount}${$rpl.amount|number_format:2}{else}&nbsp;{/if}</li>
				<li>${$rpl.balance|number_format:2}</li>-->
			</ul>
		</li>
		{/foreach}
	{else}
		<li><ul class="list">
				<li style="width:748px;">No Records</li>
			</ul>
		</li>
	{/if}
	<li class="pagelist">
		{$req.ref.links}
	</li>
	{php}
	//<li class="lifooter">
		//Current Earnings ${$req.ref.earn_amount|number_format:2}&nbsp;&nbsp;&nbsp;
	//</li>
	{/php}
	</ul>
	<div style="clear:left;"></div>	