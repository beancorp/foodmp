<link href="/skin/red/race/ui-darkness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
<script src="/skin/red/js/jquery-1.10.2.min.js"></script>
<script src="/skin/red/js/jquery-ui-1.10.3.custom.min.js"></script>

<style>
{literal}
	.ui-tabs .ui-tabs-nav li {font-size: 12px; }
	
	.ui-button .ui-button-text {
	   font-size: 12px !important;
	}
	
	.leaderboard {
		background-color: #838383;
		padding: 10px;
		border-radius: 10px;
	}
	
	.scores {
		width: 100%;
		border-collapse:collapse;
	}
	
	.scores td, .scores th {
		padding: 10px;
	}
	
	.scores thead tr {
		background-color: #505050;
	}
	
	.scores th {
		font-size: 10pt;
		color: #FFF;
		border-right: 1px solid #FFF;
		border-bottom: 2px solid #383637;
		padding: 5px;
	}
	
	.scores tbody td {
		font-size: 10pt;
		color: #FFF;
		padding: 5px;
		border-right: 1px solid #FFF;
		font-weight: bold;
	}
	
	.scores tr {
		background-color: #747474;
	}
	
	.scores tfoot tr {
		background-color: #505050;
	}
	
	.scores tfoot td {
		font-size: 10pt;
		color: #FFF;
		padding: 5px;
		font-weight: bold;
	}
	
	.scores tr td a {
		color: #FFF;
	}
	
	.scores .first {
		background-color: #e8bf0b!important;
	}
	
	.scores .second {
		background-color: #b5aaa8!important;
	}
	
	.scores .third {
		background-color: #d78e28!important;
	}
	
	.scores .odd {
		background-color: #666465!important;
	}
	
	.records_box {
		background-color: #838383;
		padding: 10px;
		border-radius: 10px;
		clear: both;
	}
	
	.records {
		width: 100%;
		border-collapse:collapse;
	}
	
	.records td, .records th {
		padding: 10px;
	}
	
	.records thead tr {
		background-color: #505050;
	}
	
	.records th {
		font-size: 10pt;
		color: #FFF;
		border-right: 1px solid #FFF;
		border-bottom: 2px solid #383637;
		padding: 5px;
	}
	
	.records tbody td {
		font-size: 10pt;
		color: #FFF;
		padding: 5px;
		border-right: 1px solid #FFF;
	}
	
	.records tr {
		background-color: #747474;
	}
	
	.records tfoot tr {
		background-color: #505050;
	}
	
	.records tfoot td {
		font-size: 10pt;
		color: #FFF;
		padding: 5px;
		font-weight: bold;
	}
	
	.records tr td a {
		color: #FFF;
	}
	
	.records .odd {
		background-color: #666465!important;
	}
	
	#buttons {
		width: 100%;
		height: 50px;
	}
	
	h1 {
		font-size: 16pt;
	}
{/literal}
</style>
{if $show}
		<div id="buttons">			
			<button id="back" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">
				<span class="ui-button-text">Back</span>
			</button>
		</div>
		<div id="heading">
			<h1>{$name}</h1>
		</div>
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Commission</a></li>
				<li><a href="#tabs-2">Bank Details</a></li>
			</ul>			
			<div id="tabs-1">
				<div class="records_box">
					<table class="records">
						<thead>
							<tr>
								<th>Details</th>
								<th>Amount</th>
								<th>Status</th>
							</tr>
						</thead>
						{if $referrals}
							<tbody>
							{assign var=balance value=0}
							{foreach from=$referrals item=referral}
								<tr>
									<td>{$referral.details}</td>
									<td>{$referral.amount}</td>
									<td>
										<select class="status" tag="{$referral.id}">
											<option {if $referral.status eq 0}selected="selected"{/if} value="0">UNPAID</option>
											<option {if $referral.status eq 1}selected="selected"{/if} value="1">PAID</option>
										</select>
									</td>
								</tr>
								{assign var=balance value=$balance+$referral.amount}
							{/foreach}
							</tbody>
							<tfoot>
								<tr>
									<td></td>
									<td colspan="2">${$balance}</td>
								 </tr>
							</tfoot>
						{else}
							<tfoot>
							<tr>	
								<td colspan="2">No Referrals</td>
							</tr>
							</tfoot>
						{/if}
					</table>
					<script>
						{literal}
							$(document).ready(function() {
								$('.status').change(function() {
									var id = $(this).attr('tag');
									$.ajax({
										url: "leaderboard.php",
										type: "POST",
										data: {action: "comission", id: id, value: $(this).val()} 					
									}).done(function(data) {
									});
								});
							});
						{/literal}
					</script>
				</div>
			</div>
			<div id="tabs-2">
				<style>
					{literal}
						#bank_details th {
							color: #FFFFFF;
							font-size: 12px;
							text-align: left;
							width: 150px;
						}
						
						#bank_details td {
							color: #FFFFFF;
							font-size: 12px;
							padding: 5px;
						}
						
						#no_bank_details {
							color: #FFFFFF;
						}
					{/literal}
				</style>
				{if $bank_details}
					<table id="bank_details">
						<tr>
							<th>Account Name</th>
							<td>{$bank_details.account_name}</td>
						</tr>
						<tr>
							<th>BSB</th>
							<td>{$bank_details.account_bsb}</td>
						</tr>
						<tr>
							<th>Number</th>
							<td>{$bank_details.account_number}</td>
						</tr>
					</table>
				{else}
					<span id="no_bank_details">No Details Provided</span>
				{/if}
			</div>
		</div>
		<script>
		{literal}
			$(document).ready(function() {
				$('#tabs').tabs();
				$('#back').button();
				
				$('#back').click(function() {
					parent.history.back();
					return false;
				});
			});
		{/literal}
		</script>		
{else}
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Commissions</a></li>
			</ul>
			<div id="tabs-1">
				<div class="leaderboard" id="comissions">
					{php}
						showCommissions();
					{/php}
				</div>
			</div>
		</div>
		<script>
		{literal}
			$(document).ready(function() {
				$('#tabs').tabs();
			});
		{/literal}
		</script>
{/if}