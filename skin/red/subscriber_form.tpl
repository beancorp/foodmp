<img src="/skin/red/images/buyeradmin_bg_top.jpg" style="margin:0px; padding:0px;">
<div id="buyeradminhome">
	{if $eway_result}
		<div id="subscription_box">
			{$eway_result}
		</div>
	{/if}
	<div id="ewayBlock">
		<div style="text-align: center">
			<div>
				<a title="eWAY - Online payments made easy" target="_eway" href="http://www.eway.com.au/secure-site-seal">
				<img border="0" title="eWAY - Online payments made easy" alt="eWAY - Online payments made easy" src="https://www.eway.com.au/developer/payment-code/verified-seal.ashx?img=12&size=3">
				</a>
			</div>
		</div>
	</div>
	{if $eway_error}
		<div id="subscription_error">{$eway_error}</div>
	{/if}
		
	<form id="mainForm" action="soc.php?cp=subscription" method="post" name="mainForm" style="margin-top: 10px;">
		<table id="payment_form">
			<tr>
				<th>
					First name
				</th>
				<td>
					<input type="text" name="first_name" value="{$first_name}" />
				</td>
			</tr>
			<tr>
				<th>
					Last name
				</th>
				<td>
					<input type="text" name="last_name" value="{$last_name}" />
				</td>
			</tr>
			<tr>
				<th>
					State
				</th>
				<td>
					<select name="state" class="inputB" style="width:100px;">
						<option value="NSW"{if $state eq 'NSW'} selected="selected"{/if}>NSW</option>
						<option value="ACT"{if $state eq 'ACT'} selected="selected"{/if}>ACT</option>
						<option value="VIC"{if $state eq 'VIC'} selected="selected"{/if}>VIC</option>
						<option value="QLD"{if $state eq 'QLD'} selected="selected"{/if}>QLD</option>
						<option value="SA"{if $state eq 'SA'} selected="selected"{/if}>SA</option>
						<option value="WA"{if $state eq 'WA'} selected="selected"{/if}>WA</option>
						<option value="NT"{if $state eq 'NT'} selected="selected"{/if}>NT</option>
						<option value="TAS"{if $state eq 'TAS'} selected="selected"{/if}>TAS</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					Postcode
				</th>
				<td>
					<input type="text" name="postcode" value="{$postcode}" />
				</td>
			</tr>
			<tr>
				<th>CardHolder's Name</th>
				<td><input type="text" name="cardholder" autocomplete="off" value="{$cardholder}" /></td>
			</tr>
			<tr>
				<th>Card Number</th>
				<td><input type="text" name="cardnumber" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>CVC</th>
				<td><input type="text" name="cvc" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>Card Expiry</th>
				<td>
					<select class="inputB" style="width:50px;" name="expiry_month">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>
					/
					<select class="inputB" style="width:65px;" name="expiry_year">
						<option value=2013 >2013
						<option value="2014">2014</option>
						<option value="2015">2015</option>
						<option value="2016">2016</option>
						<option value="2017">2017</option>
						<option value="2018">2018</option>
						<option value="2019">2019</option>
						<option value="2020">2020</option>
						<option value="2021">2021</option>
						<option value="2022">2022</option>
						<option value="2023">2023</option>
						<option value="2024">2024</option>
						<option value="2025">2025</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<img width="37" height="23" src="/skin/red/images/icon-visa.gif">&nbsp;<img width="37" height="23" src="/skin/red/images/icon-mastercard.gif">
				</td>			
			</tr>
		</table>
		<div id="form_buttons">
			<a href="soc.php?cp=subscribe" class="back_button">Back</a>
			<input type="submit" name="submit" value="Process Payment" class="submit_button" />
		</div>
	</form>
</div>
<img src="/skin/red/images/buyeradmin_bg_bottom.jpg">