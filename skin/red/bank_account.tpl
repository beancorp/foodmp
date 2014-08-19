<style>
{literal}
	#account_box {
		background-color: #f8f8f8;
		padding: 10px;
		border-radius: 10px;
		overflow: hidden;
	}
	
	#bank_account {
		width: 300px;
		margin-left: auto;
		margin-right: auto;
	}
	
	.field_row {
		margin-bottom: 10px;
		overflow: hidden;
	}
	
	.field_row label {
		margin-right: 10px;
		width: 100px;
		float: left;
	}
{/literal}
</style>
<div id="account_box">
<form action="soc.php?cp=bank_account" method="POST">
	<div id="bank_account">
		<div class="field_row">
			<label>Account Name</label>
			<input type="text" name="account_name" value="{$account_name}" />
		</div>
		<div class="field_row">
			<label>BSB</label>
			<input type="text" name="account_bsb" value="{$account_bsb}" />
		</div>
		<div class="field_row">
			<label>Account Number</label>
			<input type="text" name="account_number" value="{$account_number}" />
		</div>
		<div class="field_row" style="text-align: center;">
			<input type="submit" id="account_submit" name="account_submit" value="Save" />
		</div>
	</div>
	<a href="soc.php?cp=bank_account&remove=1">Remove Bank Account</a>
</form>
</div>