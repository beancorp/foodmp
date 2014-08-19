<div id="subscription_title">Support Plan</div>
<div id="subscription_message">
	{if $rebill}
		You can cancel the subscription rebilling using the button below.
	{else}
		Your subscription is cancelled. It will expire once the 'days remaining' counter has reached zero. 
	{/if}
</div>
<div id="subscription_box">	
	<div class="info_box">
		<strong>Starts:</strong> {$subscription_begin}
	</div>
	<div class="info_box">
		<strong>Ends:</strong> {$subscription_end}
	</div>
	<div class="info_box">
		{$days_remaining} days remaining
	</div>
	<div id="subscription_buttons">
		{if $rebill}
			<button id="cancel_button">Cancel Subscription</button>
		{else}
			<a id="renew_button" href="soc.php?cp=subscription">Renew Subscription</a>
		{/if}
	</div>
</div>

{if $success}
	<div id="subscription_success">{$success}</div>
{/if}

{if $error}
	<div id="subscription_error">{$error}</div>
{/if}


<div id="website_information_title">
	Website Information
</div>

<div id="website_information">
	<form action="soc.php?cp=subscriber" method="POST" enctype="multipart/form-data">
		<table id="subscriber_form">
			<tr>
				<th valign="top">Logo image</th>
				<td valign="top"><input type="file" name="logo" /> {if $logo_image} <a href="upload/website_info/{$logo_image}" target="_blank">Uploaded Logo</a> {/if} </td>
			</tr>
			<tr>
				<th valign="top">Headline image</th>
				<td valign="top"><input type="file" name="headline" /> {if $headline_image} <a href="upload/website_info/{$headline_image}" target="_blank">Uploaded Headline Image</a> {/if} </td>
			</tr>
			<tr>
				<th valign="top">Border Colour</th>
				<td valign="top">
					<ul id="choosecolor">
						<li><img src="/skin/red/images/color-purple.gif" alt="" /><br /><input type="radio" name="colour" value="purple" {if $colour eq 'purple'} checked="checked" {/if} /></li>
						<li><img src="/skin/red/images/color-orange.gif" alt="" /><br /><input type="radio" name="colour" value="orange" {if $colour eq 'orange'} checked="checked" {/if} /></li>
						<li><img src="/skin/red/images/color-blue.gif" alt="" /><br /><input type="radio" name="colour" value="blue" {if $colour eq 'blue'} checked="checked" {/if} /></li>
						<li><img src="/skin/red/images/color-red.gif" alt="" /><br /><input type="radio" name="colour" value="red" {if $colour eq 'red'} checked="checked" {/if} /></li>
						<li><img src="/skin/red/images/color-green.gif" alt="" /><br /><input type="radio" name="colour" value="green" {if $colour eq 'green'} checked="checked" {/if} /></li>
						<li><img src="/skin/red/images/color-black.gif" alt="" /><br /><input type="radio" name="colour" value="black" {if $colour eq 'black'} checked="checked" {/if} /></li>
					</ul>
				
				</td>
			</tr>
			<tr>
				<th valign="top">About Retailer</th>
				<td valign="top">
					<textarea name="about_retailer" cols="50" rows="10">{$about_retailer}</textarea>
				</td>
			</tr>
			<tr>
				<th valign="top">Specials <span class="question_mark">?</span></th>
				<td valign="top">
					<div class="group_box">
						<div id="specials"></div>		
						<span id="add_specials" class="add">Add Special</span>
					</div>				
				</td>
			</tr>
			{if $req.info.sold_status eq 1}
			<tr>
				<th valign="top">Stock Items <span class="question_mark">?</span></th>
				<td valign="top">
					<div class="group_box">
						<div id="stock"></div>		
						<span id="add_stock" class="add">Add Stock Item</span>
					</div>
				</td>
			</tr>
			{/if}
			<tr>
				<td colspan="2" style="padding-left: 300px;"><input type="submit" name="submit" class="submit_button" value="Submit" /></td>
			</tr>	
		</table>
	</form>
</div>
<div id="example_form" title="Example Form">
	<div class="group_box">
		<div id="specials">
			<div class="group_row" id="example1">
				<div class="remove"><span class="remove_btn">-</span></div>
				<div class="group_row_field">
					<label>Category:</label><input type="text" name="category[1]" value="Vegetables" readonly />
				</div>
				<div class="group_row_field">
					<label>Item:</label><input type="text" name="item[1]" value="Carrots" readonly />
				</div>
				<div class="group_row_field">
					<label>Price:</label><input type="text" name="price[1]" value="$1.99kg" readonly />
				</div>
			</div>
			<div class="group_row" id="example2">
				<div class="remove"><span class="remove_btn">-</span></div>
				<div class="group_row_field">
					<label>Category:</label><input type="text" name="category[2]" value="Fruit" readonly />
				</div>
				<div class="group_row_field">
					<label>Item:</label><input type="text" name="item[2]" value="Mangoes" readonly />
				</div>
				<div class="group_row_field">
					<label>Price:</label><input type="text" name="price[2]" value="2 for $5" readonly />
				</div>
			</div>
			<div class="group_row" id="example3">
				<div class="remove"><span class="remove_btn">-</span></div>
				<div class="group_row_field">
					<label>Category:</label><input type="text" name="category[3]" value="Meat" readonly />
				</div>
				<div class="group_row_field">
					<label>Item:</label><input type="text" name="item[3]" value="Premium Mince" readonly />
				</div>
				<div class="group_row_field">
					<label>Price:</label><input type="text" name="price[3]" value="2kg for $12" readonly />
				</div>
			</div>
			<div class="group_row" id="example4">
				<div class="remove"><span class="remove_btn">-</span></div>
				<div class="group_row_field">
					<label>Category:</label><input type="text" name="category[4]" value="Bread" readonly />
				</div>
				<div class="group_row_field">
					<label>Item:</label><input type="text" name="item[4]" value="Cottage Loaf" readonly />
				</div>
				<div class="group_row_field">
					<label>Price:</label><input type="text" name="price[4]" value="$4.98 each" readonly />
				</div>
			</div>
		</div>
	</div>
</div>

<div id="confirmation_dialog" title="Confirmation Required">
  Are you sure about this?
</div>

<script type="text/javascript" src="/skin/red/js/website_info.js"></script>
<script>
{if $special_items}
	{foreach name=outer item=special from=$special_items}
		add_special('{$special.category}', '{$special.item}', '{$special.price}');
	{/foreach}
{else}
	add_special('','','');
	add_special('','','');
{/if}

{if $stock_items}
	{foreach name=outer item=stock from=$stock_items}
		add_stock('{$stock.category}', '{$stock.item}', '{$stock.price}');
	{/foreach}
{else}
	add_stock('','','');
	add_stock('','','');
{/if}
</script>