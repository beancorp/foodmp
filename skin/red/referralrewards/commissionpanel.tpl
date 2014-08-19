<link href="/skin/red/race/ui-darkness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<script src="/skin/red/js/jquery.validationEngine.js"></script>
<script src="/skin/red/js/jquery.validationEngine-en.js"></script>


<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen"/>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
	
<style>
{literal}
	#header {
		margin-bottom: 0px;
	}

	#container {
		width: 930px;
	}
	
	#page_container {
		border-left: 1px solid #FFF;
		color: #FFF;
		padding: 10px 10px 10px;
	}
	
	#tab_container {
		width: 100%;
		display: none;
	}
	
	#tabs {
		width: 100%;
	}
	
	.tab_content {
		background-color: #3a3637;
		clear: both;
		padding: 15px;
		color: #FFF;
		display: none;
		overflow: hidden;
		border-bottom-left-radius: 10px;
		border-bottom-right-radius: 10px;
	}
	
	.tab_content h1 {
		color: #FFFFFF;
		font-size: 17pt;
		font-weight: bold;
		margin-bottom: 10px;
		margin-top: 0;
		padding: 5px;
		float: left;
	}
	
	.tab_content h2 {
		color: #FFFFFF;
		font-size: 14pt;
		font-weight: bold;
		margin-bottom: 10px;
		margin-top: 0;
	}
	
	.tab {
		float: left;
		padding: 15px;
		color: #FFF;
		margin-right: 1px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		background-color: #232323;
		font-weight: bold;
		cursor: pointer;
		height: 30px;
		text-align: center;
		font-size: 11pt;
	}
	
	.tab_link {
		background-color: #232323;
		display: block;
		text-decoration: none;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		color: #FFFFFF;
		cursor: pointer;
		float: left;
		font-size: 11pt;
		font-weight: bold;
		height: 30px;
		margin-right: 1px;
		padding: 15px;
		text-align: center;
	}
	
	.tab_selected {
		background-color: #3a3637;
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
	}
	
	.records tr td a {
		color: #FFF;
	}
	
	.records .odd {
		background-color: #666465!important;
	}
{/literal}
</style>
<img src="/skin/red/referralrewards/new_banner.jpg" />
<div id="page_container">
	<div id="tab_container">
		<div id="tabs">
			<a href="soc.php?cp=sellerhome" class="tab_link">My Admin</a>
			<div class="tab" id="tabbank">Bank Details</div>
			{if $has_bankaccount}
			<div class="tab" id="tabrefer">Refer your Retailers</div>
			{/if}
			<div class="tab" id="tabcommission">Commission</div>
		</div>
		
		<div class="tab_content" id="content_tabbank">
			{literal}
			<style>
				#account_box {
					color: #FFF;
					font-size: 10pt;
					margin-bottom: 10px;
					overflow: hidden;
					padding: 10px;
					text-align: center;
				}
				
				#account_box strong {
					color: #FF0000;
					font-size: 14pt;
				}
				
				#account_box a {
					color: #FFF;
				}
				
				#bank_account {
					width: 350px;
					margin-left: auto;
					margin-right: auto;
					margin-top: 15px;
					text-align: left;
				}
				
				.field_row {
					margin-bottom: 10px;
					overflow: hidden;
				}
				
				#bank_account input {
					color: #000000;
				}
				
				.field_row label {
					color: #FFF;
					float: left;
					font-weight: bold;
					text-align: right;
					width: 150px;
					margin-right: 10px;
				}
				
				#account_submit {
					margin-left: 165px;
					cursor: pointer;
				}
				
				#bank_security {
					color: #FFFFFF !important;
					margin-left: auto;
					margin-right: auto;
					text-align: left !important;
					width: 500px;
				}
				
				#bank_security strong {
					color: #FFF !important;
				}
				
				#bank_security ol {
					color: #FFF !important;
					text-align: left !important;
				}
				
				#bank_security ol li {
					color: #FFF !important;
					margin-bottom: 20px;
				}
			</style>
			{/literal}
			<div id="account_box">
			Your Referral ID is: <strong>{$ref_name}</strong> <br />
			To activate your Referral ID your bank account details must be entered below. <br />
			This will enable us to pay your Referral Fees directly into your bank account each month. <br /><br />
				<form id="bank_details" method="POST">
					<div id="bank_account">
						<div class="field_row">
							<label>Account Name:</label>
							<input type="text" name="account_name" class="validate[required]" value="{$account_name}" />
						</div>
						<div class="field_row">
							<label>BSB:</label>
							<input type="text" name="account_bsb" class="validate[required,custom[integer]]" value="{$account_bsb}" />
						</div>
						<div class="field_row">
							<label>Account Number:</label>
							<input type="text" name="account_number" class="validate[required,custom[integer]]" value="{$account_number}" />
						</div>
						<div class="field_row">
							<input type="submit" id="account_submit" name="account_submit" value="Save" />
						</div>
					</div>
				</form>
				<br />
				<div id="bank_security">
				<strong>Bank Account Security</strong> <br /><br />
				Giving out one's Bank Account Details is much safer in comparison to giving out one's Credit Card Details because of the following:
				<br />
				<ol>
					<li>An individual cannot withdraw funds from any bank account unless they have a pre-approved direct debit facility, regardless of whether they have the bank account name, branch number, routing number and account number.</li>
					<li>Where as with a credit card once the credit card numbers are in the public domain your account is vulnerable to any unauthorised withdrawal.</li>
				</ol>
				<br />
				Please feel free to confirm this information with your bank.
				</div>
			</div>
			<script>	
				{literal}
					$(document).ready(function() {
						$('#bank_details').validationEngine('attach', {scroll: false});
					});
				{/literal}
			</script>
		</div>
		
		{if $has_bankaccount}
			<div class="tab_content" id="content_tabrefer">
			{if $bonus_message}
				<div id="bonus_message">
				{$bonus_message}
				</div>
			{/if}
				<script>
				var StoreID = "{$smarty.session.ShopID}";
				var soc_http_host="{$soc_http_host}";
				</script>
				{literal}
				<script language="javascript">
				function checkemailform() {
					if($('#own_name').val()==""){
						alert('Own Name is required.');
						return false;
					}
					if($('#own_email').val()==""){
						alert('Own Email is required.');
						return false;
					}else{
						if(!ifEmail($('#own_email').val(),false)){
							alert('Own Email is invalid.');
							return false;
						}
					}
					if($('#is_choose_upload').val()!="0"){
						if($('#is_csv_upload').val()=="0"){
							alert("CSV file is invalid.");
							return false;
						}
					}
					$("#ref_submit").attr('disabled','disabled');
					$("#ref_form").attr('target','_self');
					$("#optval").val('send');
					document.ref_form.submit();
					return true;
				}
				function ifEmail(str,allowNull){
					if(str.length==0) return allowNull;
					i=str.indexOf("@");
					j=str.lastIndexOf(".");
					if (i == -1 || j == -1 || i > j) return false;
					return true;
				}
				function showtabfunc(obj){
					if (obj=='uploadtab') {
						$('#normaltab').hide();
						$('#is_choose_upload').val(1);
					} else {
						$('#uploadtab').hide();
						$('#is_choose_upload').val(0);
					}
					$('#'+obj).show();
				}
				$(function() {
					$("#csvupload").makeAsyncUploader({
						upload_url: soc_http_host+"uploadcsvemail.php?type=referrer&StoreID="+StoreID,
						flash_url: '/skin/red/js/swfupload.swf',
						button_image_url: '/skin/red/images/blankButton.png',
						disableDuringUpload: 'INPUT[type="submit"]',
						file_types:'*.csv',
						file_size_limit:'10MB',
						file_types_description:'CSV files',
						button_window_mode:"transparent",
						button_text:"",
						height:"29",
						debug:false
					});
				});
				function uploadresponse(response){
					var aryResult = response.split('|');
					if (parseInt(aryResult[0])>0) {
						$('#is_csv_upload').val(1);
					} else {
						$('#is_csv_upload').val(0)
					}
					$('#uploadmsg').html(aryResult[1]);
				}
				function uploadprocess(bl) {
					if (!bl) {
						$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/gray-submit.gif)');
					} else {
						$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/or-submit.gif)');
					}
				}
				function sendOnkeyDown(event)
				{
					if (event.keyCode==13) { 
						return checkemailform();
					} 
					return true;
				}
				</script>
				<style>
				#messagebox {
					background-color: #DE1111;
					border-radius: 10px 10px 10px 10px;
					color: #FFFFFF;
					font-weight: bold;
					margin-bottom: 10px;
					padding: 10px;
				}
				
				#referral_box {
					border-radius: 10px 10px 10px 10px;
					color: #FFFFFF;
					overflow: hidden;
					padding: 25px;
					width: 500px;
					margin-left: auto;
					margin-right: auto;
				}
				
				#referral_box table {
					width: 100%;
				}
				
				#referral_box a, #referral_box strong {
					color: #FFF;
				}
				
				#referral_box table td {
					padding: 5px;
					color: #FFF;
				}
				
				#uploadtab td , #uploadtab a {
					color: #000!important;
				}
				
				.button {
					background-color: #04749F;
					border: 0 none;
					border-radius: 10px 10px 10px 10px;
					color: #FFFFFF;
					cursor: pointer;
					float: right;
					padding: 12px;
				}
				#referral_id {
					background-color: #000000;
					border-radius: 10px 10px 10px 10px;
					color: #FFF;
					font-size: 10pt;
					margin-bottom: 10px;
					overflow: hidden;
					padding: 10px;
					text-align: center;
					clear: both;
				}
				
				#referral_id strong {
					color: #FF0000;
					font-size: 14pt;
				}
				
				</style>
				{/literal}
				<div>
					{if $smarty.get.msg}
					<div align="center" id="messagebox">
						{$smarty.get.msg}
					</div>
					{/if}
					<form action="" method="post" name="ref_form" id="ref_form">
						<div id="referral_box">
							<h1>Refer your Retailers</h1>
							<div id="referral_id">
							Your Referral ID is: <strong>{$ref_name}</strong>
							</div>
							<table cellspacing="4" cellpadding="0">
								<tr>
									<td align="left" valign="top" width="25%">
										Your Name:*
									</td>
									<td>
										<input type="text" name="own_name" id="own_name" value="{$req.own_name}" class="inputB" onkeydown="previewOnkeyDown(event);"/>
									</td>
									<td>
									</td>
								</tr>
								<tr>
									<td style="width:75px;+width:76px;" align="left" valign="top">
										Your Email:*
									</td>
									<td>
										<input type="text" name="own_email" id="own_email" value="{$req.own_email}" class="inputB" onkeydown="previewOnkeyDown(event);"/>
									</td>
									<td>
									</td>
								</tr>
								<tr>
									<td style="width:75px;+width:76px;" align="left" valign="top">
										Personal Note
									</td>
									<td>
										<textarea name="personal_note" class="inputB" style="height:50px;*height:52px;*padding-bottom:0;"></textarea>
									</td>
									<td>
									</td>
								</tr>
								<tr>
									<td style="width:75px;+width:76px;" align="left" valign="top">
										Signature:
									</td>
									<td>
										<textarea name="inscription" class="inputB" style="height:50px;*height:52px;*padding-bottom:0;">Regards,&#013;{$req.own_name}</textarea>
									</td>
									<td>
									</td>
								</tr>
								<tr>
									<td style="width:75px;+width:76px;" align="left">
										&nbsp;
									</td>
									<td align="right">
										<input type="button" class="button" value="Preview" onclick="return preview();" />
									</td>
									<td>
									</td>
								</tr>
							</table>
							
							<strong>Please Select:</strong> &nbsp;&nbsp;<input type="radio" name="up_tab" checked="checked" onclick="showtabfunc('normaltab');"/>&nbsp;Standard Mode &nbsp;&nbsp; <input type="radio" name="up_tab" onclick="showtabfunc('uploadtab');"/>&nbsp;Bulk Upload Mode
							
							<input type="hidden" id="is_choose_upload" name="is_choose_upload" value="0"/>
							<input type="hidden" id="is_csv_upload" name="is_csv_upload" value="0"/>
							<table cellpadding="0" cellpadding="4" width="474" id="uploadtab" style="border:1px solid #ccc; margin:10px 0; display:none; background-color: #FFF;">
								<tr>
									<td>
										&nbsp;
									</td>
									<td width="80" align="left">
										Upload CSV:
									</td>
									<td align="left" width="390">
										<input type="file" name="csvupload" id="csvupload" style="display:none"/><span style="float:left; height:29px; line-height:29px;">&nbsp;&nbsp;<a href="/pdf/emailcsv.csv">CSV Sample</a></span>
									</td>
								</tr>
								<tr>
									<td>
									</td>
									<td colspan="2" id="uploadmsg" align="left" style="color:#F00">
									</td>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="4" width="437px" id="normaltab">
								<tr>
									<td style="background:#000; height:23px; color:#FFFFFF;font-weight:bold;" align="center">
										#
									</td>
									<td style=" background:#000;height:23px;color:#FFFFFF;font-weight:bold;" align="center">
										Contact Name
									</td>
									<td style=" background:#000;height:23px;color:#FFFFFF; font-weight:bold;" align="center">
										Email
									</td>
								</tr>
								{section name=foo start=0 loop=10 step=1}
								<tr>
									<td width="3%">
										{$smarty.section.foo.index+1}.
									</td>
									<td>
										<input class="inputB" style="width:150px" type="text" name="nickname[]" value="{$req.nickname[$smarty.section.foo.index]}" onkeydown="return sendOnkeyDown(event);"/>
									</td>
									<td>
										<input class="inputB" type="text" name="emailaddress[]" value="{$req.emailaddress[$smarty.section.foo.index]}" onkeydown="return sendOnkeyDown(event);"/>
									</td>
								</tr>
								 {/section}
								</table>
								<table cellpadding="0" cellpadding="4" width="474">
								<tr>
									<td>
										&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left">
										<p>
											<a href="/soc.php?cp=refemaillist" title="View list of Emails already sent">View list of Emails already sent</a>
										</p>
										<p>
											<a href="/soc.php?cp=buyrefer" title="Members who have joined under your referral code">Members who have joined under your referral code</a>
										</p>
									</td>
									<td align="right">
										<input type="button" class="button" value="Refer Retailers" onclick="return checkemailform();" id="ref_submit" />
									</td>
								</tr>
							</table>
							<input type="hidden" name="optval" id="optval" value="send"/>
						</div>
					</form>
				</div>
				{literal}
				<script type="text/javascript">
					function previewOnkeyDown(event) {
						if (event.keyCode==13) { 
							preview();
						} 
						return true;
					}
					function preview() {		
						$("#optval").val('preview');
						document.ref_form.target = '_blank';
						document.ref_form.submit();
					}
				</script>
				{/literal}
			</div>
		{/if}
		
	<div class="tab_content" id="content_tabcommission">
		<style>
			{literal}
				#commission_window {
					background-color: #FFFFFF;
					border-radius: 10px 10px 0px 0px;
					left: 0;
					margin-left: auto;
					margin-right: auto;
					padding: 0px;
					position: absolute;
					right: 0;
					top: 92px;
					width: 450px;
				}
				
				#commission_container {
					margin-left: auto;
					margin-right: auto;
					width: 315px;
				}
				
				#commission_header {
					background-image: url(/skin/red/referralrewards/commission/header.png);
					background-repeat: no-repeat;
					width: 300px;
					height: 150px;
				}
				#commission_rate {
					background-image: url(/skin/red/referralrewards/commission/commission_rate.png);
					background-repeat: no-repeat;
					width: 315px;
					height: 50px;
					overflow: hidden;
				}
				
				#commission_percentage {
					color: #FFFFFF;
					font-size: 15pt;
					margin-left: 260px;
					margin-top: 10px;
				}
				
				#commission_details {
					margin-top: 0px;
					overflow: hidden;
				}
				
				#commission_details .row {
					clear: both;
					margin-top: 10px;
					overflow: hidden;
				}
				
				#commission_details .icon {
					float: left;
					width: 50px;
				}
				
				#commission_details .text {
					border-bottom: 1px solid #D1D1D1;
					float: right;
					font-family: Arial;
					font-size: 9pt;
					line-height: 15pt;
					margin-left: 10px;
					padding-bottom: 5px;
					padding-top: 2px;
					width: 240px;
				}
				
				#commission_text {
					font-family: Arial;
					font-size: 10pt;
					line-height: 16pt;
					margin-top: 0px;
				}
				
				#commission_terms {
					font-family: Arial;
					font-size: 8pt;
					line-height: 12pt;
					margin-top: 0px;
				}

				#open_commission_info {
					color: #FFF;
					cursor: pointer;
				}
			{/literal}
		</style>
		<div id="commission_window">
			<div id="commission_container">
				<div id="commission_header"></div>
				<div id="commission_rate">
					<div id="commission_percentage">{$commission_percentage}%</div>
				</div>
				<div id="commission_details">
					<div class="row">
						<div class="icon"><img src="/skin/red/referralrewards/commission/retailer_icon.png" /></div>
						<div class="text">The cash amount for every new food retailer you sign-up to FoodMarketplace is: <strong style="color: #d02232;">${$signup_retailer}</strong></div>
					</div>
					<div class="row">
						<div class="icon"><img src="/skin/red/referralrewards/commission/link_icon.png" /></div>
						<div class="text">The cash amount for every new 'Link' you sign-up to FoodMarketplace is: <strong style="color: #d02232;">${$signup_link}</strong></div>
					</div>
				</div>
				<div id="commission_text">
					{$commission_text}
				</div>
				<div id="commission_terms">
					*Restaurants and Pubs & Bars have the option of linking to their existing website at an annual subscription rate of $250.00 <br />
				</div>
			</div>
		</div>
		<div class="records_box">
			{php}
				commission();
			{/php}
		</div>
	</div>
	<script>
		{literal}
			function show_tab(tab_id) {
				$('.tab').removeClass('tab_selected');
				$('.tab_content').hide();
				$('#' + tab_id).addClass('tab_selected');
				$('#content_' + tab_id).show();
			}
			
			$(document).ready(function() {
				$('.tab').click(function() {
					$('.tab').removeClass('tab_selected');
					$(this).addClass('tab_selected');
					$('.tab_content').hide();
					$('#content_' + $(this).attr('id')).show();
				});
				$('#tab_container').show();
				show_tab($('.tab').first().attr('id'));
			});
		{/literal}
	</script>
</div>