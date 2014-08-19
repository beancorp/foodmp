<script src="/skin/red/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/skin/red/js/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="/skin/red/js/jquery.validationEngine.js" type="text/javascript"></script>
<script src="/skin/red/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/skin/red/js/jquery.marquee.js"></script>
<script src="/js/lightbox_plus.js" type="text/javascript"></script>
<link rel="stylesheet" href="/skin/red/css/jquery.tooltip.css" type="text/css" />
<script src="/skin/red/js/jquery.tooltip.js" type="text/javascript"></script>
{literal}
	<style>
		.by_class {
			float: left;
		}
		#wizard {
			width: 760px;
			min-height: 480px;
			padding: 10px;
			margin-left: 10px;
			margin-top: 10px;
			background-color: #f2f2f2;
			border: 1px #e2e2e2 solid;
			border-radius: 10px 10px 10px 10px;
			float: left;
		}
		
		#wizard_steps {
			margin-left: auto;
			margin-right: auto;
			background-color: #FFF;
			border-radius: 10px 10px 10px 10px;
			padding: 10px;
		}
		
		.step_number {
			font-size: 11px;
			height: 25px;
			color: #777;
			text-decoration: none;
		}
		
		#wizard_steps .step_active {
			font-weight: bold;
		}
		
		#wizard_steps .preview_page {
			font-size: 12pt;
			font-weight: bold;
		}
		
		#wizard_body {
			margin-top: 20px;
		}
		
		#payment_type {
			width: 150px;
			background-color: #FFF;
			border-radius: 10px 10px 10px 10px;
			padding: 10px;
		}
		
		#payment_type label {
			float: right;
			clear: right;
			width: 100px;
			height: 50px;
			cursor: pointer;
		}
		
		#payment_type input {
			float: left;
			margin-left: 5px;
			margin-top: 10px;
		}
		
		#payment_type_column {
			float: left;
			overflow: hidden;
			margin-right: 10px;
		}
		
		#store_details {
			background-color: #FFF;
			border-radius: 10px 10px 10px 10px;
			width: 540px;
			overflow: hidden;
			padding: 10px;
		}
		
		#store_details table {
			width: 100%;
		}
		
		#store_details table th {
			width: 150px;
			font-weight: bold;
			text-align: right;
			padding-right: 10px;
		}
		
		#paypal_email {
			display: none;
		}
		
		#eway_id {
			display: none;
		}
		
		#template_selection {
			background-color: #FFF;
			overflow: hidden;
			border-radius: 10px 10px 10px 10px;
			padding: 10px;
		}
		
		#choosecolor {
			overflow: hidden;
		}
		
		#submit_button {
			width: 760px;
			text-align: center;
			margin-top: 20px;
			overflow: hidden;
		}
		
		#submit_button input {
			padding: 5px;
			font-weight: bold;
			color: #000;
			cursor: pointer;
		}
		
		#website_details {
			overflow: hidden;
			background: #FFF;
			width: 740px;
			padding: 10px;
			border-radius: 10px 10px 10px 10px;
			margin-bottom: 10px;
		}
		
		#website_details_content {
			margin-left: 130px;
		}
		
		#personal_details {
			overflow: hidden;
			background: #FFF;
			width: 740px;
			padding: 10px;
			border-radius: 10px 10px 10px 10px;
			margin-bottom: 10px;
		}
		
		.landline_phone {
			width: 100%;
			margin-top: 5px;
			overflow: hidden;
			clear: both;
		}
		
		.landline_phone label {
			width: 150px;
			text-align: right;
			margin-right: 10px;
			float: left;
		}
		
		.detail_row {
			width: 100%;
			margin-top: 5px;
			overflow: hidden;
			clear: both;
		}
		
		.detail_row label {
			width: 150px;
			text-align: right;
			margin-right: 10px;
			float: left;
		}
		
		.detail_row input[type='text'] { 
			width: 200px;
		}
		
		.detail_row select {
			width: 208px;
		}
		
		.detail_row span {
			width: 212px;
			padding-top: 3px;
		}
		
		#personal_details_content {
			margin-left: 130px;
			width: 515px;
		}
		
		#verification {
			margin-left: 10px;
			margin-top: 10px;
			border-radius: 10px 10px 10px 10px;
			padding: 10px;
			overflow: hidden;
			background-color: #fccd5e;
		}
		
		#sms_verified_failed {
			background-color: #dd0e3a;
			padding: 10px;
			overflow: hidden;
			border: 1px solid #000;
			margin-top: 10px;
			margin-bottom: 10px;
			width: 300px;
			color: #FFF!important;
			font-weight: bold;
			text-align: center;
			margin-left: auto;
			margin-right: auto;
		}
		
		#verified_success {
			background-color: #a8cceb;
			padding: 10px;
			overflow: hidden;
			border: 1px solid #000;
			margin-top: 10px;
			margin-bottom: 10px;
			width: 300px;
			color: #000!important;
			font-weight: bold;
			text-align: center;
			margin-left: auto;
			margin-right: auto;
		}	
		
		#sms_code {
			margin-left: auto;
			margin-right: auto;
			width: 300px;
		}
		
		#sms_code label {
			color: #000;
			font-weight: bold;
			width: 100px;
		}
		
		#sms_code input[type="text"]  {
			width: 150px;
			margin-left: 10px;
		}
		
		#verify_button {
			font-weight: bold;
			color: #000;
		}
		
		#change_password {
			margin-left: 10px;
			margin-top: 10px;
			border-radius: 10px 10px 10px 10px;
			padding: 10px;
			overflow: hidden;
			background-color: #acceeb;
		}
		
		#change_password_form ul {
			list-style-type: none;
			margin-left: auto;
			margin-right: auto;
			width: 320px;
		}
		
		#change_password_form li {
			margin-top: 10px;
			overflow: hidden;
		}
		
		#change_password_form label {
			float: left;
			color: #000;
			font-weight: bold;
			width: 150px;
		}
		
		#change_password_form li input[type="text"]  {
			float: left;
			width: 150px;
		}
		
		#change_password_button {
			font-weight: bold;
			color: #000;
		}
		
		#password_change_successful {
			background-color: #1cab0d;
			padding: 10px;
			overflow: hidden;
			border: 1px solid #000;
			margin-top: 10px;
			margin-bottom: 10px;
			width: 300px;
			color: #000!important;
			font-weight: bold;
			text-align: center;
			margin-left: auto;
			margin-right: auto;
		}
		
		#password_change_failed {
			background-color: #dd0e3a;
			padding: 10px;
			overflow: hidden;
			border: 1px solid #000;
			margin-top: 10px;
			margin-bottom: 10px;
			width: 300px;
			color: #FFF!important;
			font-weight: bold;
			text-align: center;
			margin-left: auto;
			margin-right: auto;
		}
		
		#purchase_upgrade {
			overflow: hidden;
		}
		
		#membership_upgrade {
			padding: 10px;
		}
		
		#purchase_button {
			background-color: #DE600B;
			background-image: -moz-linear-gradient(center bottom , #DE600B 30%, #FF8F05 65%);
			border: 0 none !important;
			border-radius: 10px 10px 10px 10px;
			color: #FFFFFF;
			cursor: pointer;
			display: block;
			float: left;
			font-size: 10pt !important;
			font-weight: bold;
			margin-left: 10px;
			padding: 6px;
			text-decoration: none;
			width: 100px;
		}
		
		#payment_failed {
			background-color: #dd0e3a;
			padding: 10px;
			overflow: hidden;
			border: 1px solid #000;
			margin-top: 10px;
			margin-bottom: 10px;
			width: 300px;
			color: #FFF!important;
			font-weight: bold;
			text-align: center;
			margin-left: auto;
			margin-right: auto;
		}
		
		#payment_successful {
			background-color: #a8cceb;
			padding: 10px;
			overflow: hidden;
			border: 1px solid #000;
			margin-top: 10px;
			margin-bottom: 10px;
			width: 300px;
			color: #000!important;
			font-weight: bold;
			text-align: center;
			margin-left: auto;
			margin-right: auto;
		}
		
		#verified_membership {
			margin-left: 10px;
			margin-top: 10px;
			border-radius: 10px 10px 10px 10px;
			padding: 10px;
			overflow: hidden;
			background-color: #463c8e;
			color: #FFF;
			font-weight: bold;
		}
		
		#cost {
			font-weight: bold;
			color: #000;
			font-size: 12pt;
		}
		
		#verification_info {
			background-color: #FF8F05;
			border-radius: 10px 10px 10px 10px;
			margin-left: auto;
			margin-right: auto;
			padding: 10px;
			width: 400px;
			margin-bottom: 10px;
			color: #000;
			font-size: 12pt;
			text-align: center;
		}
		
		#sms_information {
			background-color: #FFF;
			overflow: hidden;
			border-radius: 10px 10px 10px 10px;
			padding: 10px;
			width: 740px;
			color: #000;
			font-family: Arial;
			font-size: 10pt;
			margin-bottom: 10px;
			font-weight: bold;
		}
		
		#sms_information li {
			color: #000!important;
			font-family: Arial;
			font-size: 9pt;
		}
		
		.detail_row .preloader {
			display: block;
			float: right;
		}
		
		#store_url_preloader {
			display: none;
		}
		
		#temp_b_detail {
			overflow: hidden;
		}
		
		#temp_c_detail {
			overflow: hidden;
		}
		
		.question {
			width: 21px;
			height: 20px;
			background-image: url('/skin/red/images/icon-question.gif');
			background-repeat: no-repeat;
			float: left;
			cursor: pointer;
		}
		
		.tooltip_description {
			color: #FFF;
		}
		
		#url_label {
			color: #0000FF;
		}
		
		#store_details table td {
			padding: 5px;
		}
		
	</style>
{/literal}
<div id="wizard">
	<div id="wizard_steps">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tbody>
			<tr align="center">
				<td width="20%">
					<a class="step_number {if $step eq 1}step_active{/if}" href="settings.php?step=1">
						<img border="0" src="/skin/red/images/navimages/step1{if $step eq 1}_active{/if}.gif"> <br />
						Store Details
					</a>
				</td>
				<td width="20%">
					<a class="step_number {if $step eq 2}step_active{/if}" href="settings.php?step=2">
						<img border="0" src="/skin/red/images/navimages/step2{if $step eq 2}_active{/if}.gif"> <br />
						Template Selection
					</a>
				</td>
				<td width="20%">
					<a class="step_number {if $step eq 3}step_active{/if}" href="settings.php?step=3">
						<img src="/verified.png" alt="Become Verified" />
					</a>
				</td>
				<td width="20%">
					<a class="step_number {if $step eq 4}step_active{/if}" href="settings.php?step=4">
						<img border="0" src="/skin/red/images/navimages/step3{if $step eq 4}_active{/if}.gif"> <br />
						Add/ Edit Products
					</a>
				</td>
				<td width="20%">
					<a class="preview_page" href="/{$session.urlstring}" target="_blank">
						Preview Page
					</a>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<div id="wizard_body">
		{if $step eq 1}
		<style>
			{literal}
			#shipping_info {
				width: 225px!important;
			}
			#add_row {
				margin-left: 175px;
			}
			#store_detail_form {
				background-color: #FFFFFF;
				border-radius: 10px 10px 10px 10px;
				overflow: hidden;
				padding: 10px;
			}
			#shipping_info_box {
				background-color: #fccd5e;
				width: 225px;
				border-radius: 10px 10px 10px 10px;
				padding: 10px;
				margin-top: 10px;
				margin-bottom: 10px;
				overflow: hidden;
			}
			#shipping_info td {
				color: #000;
			}
			{/literal}
		</style>
		<script>
		{literal}
			$(document).ready(function() {
				$(".question").tooltip().css({opacity: 0.9});
			});
		{/literal}
		</script>
		<form id="store_details_form" action="settings.php?step=1" method="POST">
			<div id="website_details">
				<div id="website_details_content">
					<div class="detail_row">
						<label>Website Name</label><input type="text" name="website_name" id="website_name" class="validate[required]" value="{$bu_name}" />
					</div>
					<div class="detail_row">
						<label>Nickname</label>
						<span>{$bu_nickname}</span>
					</div>
					<div class="detail_row">
						<label>Sub-Domain</label><input type="text" name="urlstring" value="{$bu_urlstring}" id="store_url" class="validate[required, custom[onlyLetterNumber]]" /><img src="/images/preloader.png" class="preloader" id="store_url_preloader" />
						<div id="url_label" style="clear: both; margin-left: 166px; margin-top: 10px;"></div>
					</div>
				</div>
			</div>
			<script>
				$('#url_label').html('www.socexchange.com.au/{$bu_urlstring}');
			</script>
			<div id="personal_details">
				<div id="personal_details_content">
					<div class="detail_row">
						<label>Name</label><input type="text" name="contact_name" value="{$contact_name}" />
						<div id="name_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">This is your personal name.<br />It won't be visible to the public.</div>
						</div>
					</div>
					
					<div class="detail_row">
						<label>Address</label><input type="text" name="address" value="{$bu_address}" /> <input id="address_hide" type="checkbox" value="1" {if $address_hide}checked="checked"{/if} name="address_hide"> Hide Address
						<div id="address_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">This is your street address.<br />It can be hidden with the checkbox.</div>
						</div>
					</div>
					
					<div class="detail_row">
						<label>State</label>
						<select id="state_selection" disabled="disabled" name="state">
							<option value="">[Select a State]</li>
							{foreach from=$state_list key=k item=v}
							   <option value="{$v.id}" {if $v.id eq $bu_state}selected="selected"{/if}>{$v.description}</li>
							{/foreach}
						</select>
						<div id="state_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">This is your state.<br /><span style="font-size: 11pt; font-weight: bold; color: #FFF;">It cannot be modified.</span></div>
						</div>
					</div>
					
					<div class="detail_row">
						<label>Suburb</label>
						<select id="suburb_list" disabled="disabled" name="suburb" class="validate[required]">
						</select>
						<div id="suburb_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">This is your suburb.<br /><span style="font-size: 11pt; font-weight: bold; color: #FFF;">It cannot be modified.</span></div>
						</div>
					</div>
					
					<div class="detail_row">
						<label>Postcode</label>
						<input type="text" name="postcode" disabled="disabled" class="validate[required,custom[integer],minSize[4]]" value="{$bu_postcode}" maxlength="4" />
						<div id="postcode_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">This is your postcode.<br /><span style="font-size: 11pt; font-weight: bold; color: #FFF;">It cannot be modified.</span></div>
						</div>
					</div>
					
					<div class="landline_phone">
						<label>Phone</label>
						(<input type="text" name="phone_area" value="{$bu_area}" maxlength="4" size="4" />) - <input type="text" name="phone" value="{$bu_phone}" maxlength="8" size="8" style="width: 135px;" /> <input id="phone_hide" type="checkbox" value="1" {if $phone_hide}checked="checked"{/if} name="phone_hide"> Hide Phone
						<div id="phone_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">This is your phone.<br />It can be hidden with the checkbox.</div>
						</div>
					</div>
					
					<div class="detail_row">
						<label>Fax</label>
						<input type="text" name="fax" value="{$bu_fax}" maxlength="10" />
						<input id="fax_hide" type="checkbox" value="1" {if $fax_hide}checked="checked"{/if} name="fax_hide"> Hide Fax
						<div id="fax_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">This is your fax.<br />It can be hidden with the checkbox.</div>
						</div>
					</div>

					<div class="detail_row">
						<label>Mobile</label>
						{if $status eq '2'}
							<span>{$mobile}</span>
						{else}
							<input type="text" name="mobile" value="{$mobile}" />
						{/if}
						<input id="mobile_hide" type="checkbox" value="1" {if $mobile_hide}checked="checked"{/if} name="mobile_hide"> Hide Mobile
						<div id="mobile_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">
								{if $status eq '2'}
									Your mobile has been verified. It cannot be changed.
								{else}
									This is your mobile.
								{/if}
							</div>
						</div>
					</div>
					
					
					<div class="detail_row">
						<label>Email</label>
						<span>{$bu_email}</span>
						<div id="mobile_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">
								Your email address has been verified. It cannot be changed.
							</div>
						</div>
					</div>
					
					<div class="detail_row">
						<label>Preferred Contact</label>
						<select id="contact" class="select" name="contact">
							<option value="Telephone" {if $contact eq 'Telephone'}selected="selected"{/if}>Telephone</option>
							<option value="Email" {if $contact eq 'Email'}selected="selected"{/if}>Email</option>
							<option value="Telephone or Email" {if $contact eq 'Telephone or Email'}selected="selected"{/if}>Telephone or Email</option>
						</select>
						<div id="mobile_question" class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">
								Your preferred method of contact. It will be displayed on your store page.
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="store_detail_form">
				<div id="payment_type_column">
					<strong style="padding-left: 5px; color: #000;">Seller Payment Method</strong>
					<fieldset id="payment_type">
						<label for="type_paypal"><img src="/skin/red/images/payment/paypal.png" /></label><input id="type_paypal" type="checkbox" value="1" name="type_paypal" />
						
						{foreach from=$payment_options item=option}
							<label for="payment_{$option.id}"><img src="{$option.image}" alt="{$option.name}" /></label><input {if $option.selected}checked="checked"{/if} id="payment_{$option.id}" type="checkbox" value="1" name="payments[{$option.id}]" />
						{/foreach}
						<!--
						<label for="type_bank_transfer"><img src="/skin/red/images/bank_transfer.jpg" /></label><input id="type_bank_transfer" type="checkbox" value="1" name="type_bank_transfer" />
						<label for="type_cash_in_hand"><img src="/skin/red/images/cash_in_hand.jpg" /></label><input id="type_cash_in_hand" type="checkbox" value="1" name="type_cash_in_hand" />
						<label for="type_cheque"><img src="/skin/red/images/cheque.jpg" /></label><input id="type_cheque" type="checkbox" value="1" name="type_cheque" />
						-->
					</fieldset>
					<script>
						{if $bu_paypal}
							{literal}
								$(document).ready(function() {
									$('#type_paypal').attr('checked', 'checked');
									$('#paypal_email').show();
								});
							{/literal}
						{/if}
						{if $bu_eway}
							{literal}
								$(document).ready(function() {
									$('#type_eway').attr('checked', 'checked');
									$('#eway_id').show();
								});
							{/literal}
						{/if}
						var store_id = '{$store_id}';
						{literal}
						function changeState(value) {
							{/literal}
							{foreach from=$suburb_data key=k item=v}
								var {$k}_suburbs = '{$v}';
							{/foreach}
							{literal}
							switch (value) {
								case '6' :
									$('#suburb_list').append(ACT_suburbs);
									break;
								case '5' :
									$('#suburb_list').append(NSW_suburbs);
									break;
								case '2' :
									$('#suburb_list').append(NT_suburbs);
									break;
								case '3' :
									$('#suburb_list').append(QLD_suburbs);
									break;
								case '4' :
									$('#suburb_list').append(SA_suburbs);
									break;
								case '8' :
									$('#suburb_list').append(TAS_suburbs);
									break;
								case '7' :
									$('#suburb_list').append(VIC_suburbs);
									break;
								case '1' :
									$('#suburb_list').append(WA_suburbs);
									break;
							}
						}
						
						function invalidURL() {
							$('#store_url').validationEngine('showPrompt', 'URL already taken', 'red');
						}
						
						var url_valid = true;
						
						
						$('#store_url').keyup(function() {
							$('#url_label').html('www.socexchange.com.au/' + $('#store_url').val());
						});
						
						$('#store_url').change(function() {
							if ($("#store_details_form").validationEngine('validate')) {
								$('#store_url_preloader').show();
								$.ajax({
									type: 'POST',
									url: 'ajax_requests.php?action=2',
									dataType: 'json',
									data: { value: $('#store_url').val(), store_id: store_id }
								}).done(function( data ) {
									if (data.valid) {
										url_valid = true;
									} else {
										url_valid = false;
										invalidURL();
									}
									$('#store_url_preloader').hide();
								});
							}
						});
						
						$('#state_selection').change(function() {
							$('#suburb_list').empty();
							$('#state_processing').show();
							var value = $('#state_selection').val();
							$('#suburb_list').append('<option value="">[Please Select]</option>');
							changeState(value);
						});
						
						$(document).ready(function() {
							$('#save_data').click(function() {
								if (url_valid) {
									if ($("#store_details_form").validationEngine('validate')) {
										$('#store_details_form').submit();
									}
								} else {
									invalidURL();
								}								
							});
							$("#store_details_form").validationEngine('attach');
						});
						
						$('#type_paypal').click(function() {
							if ($('#type_paypal').is(':checked')) {
								$('#paypal_email').show();
							} else {
								$('#paypal_email').hide();
							}
						});
						
						$('#type_eway').click(function() {
							if ($('#type_eway').is(':checked')) {
								$('#eway_id').show();
							} else {
								$('#eway_id').hide();
							}
						});
						
						$(document).ready(function() {
							{/literal}
							{if $bu_state}
								var value = '{$bu_state}';
								changeState(value);
							{/if}
							{literal}
						});
						{/literal}
					</script>
					
				</div>
				<div id="store_details">
						<table>
							<tr id="about_store">
								<th valign="top">About Store: </th>
								<td>
									<textarea cols="80" rows="10" name="about_store">{$bu_desc}</textarea>
								</td>
							</tr>
							<tr>
								<th>Facebook</th>
								<td><input type="text" name="facebook" value="{$facebook}" /></td>
							</tr>
							<tr>
								<th>Twitter</th>
								<td><input type="text" name="twitter" value="{$twitter}" /></td>
							</tr>
							<tr>
								<th>MySpace</th>
								<td><input type="text" name="myspace" value="{$myspace}" /></td>
							</tr>
							<tr>
								<th>Linked In</th>
								<td><input type="text" name="linkedin" value="{$linkedin}" /></td>
							</tr>
							<tr id="paypal_email">
								<th>Paypal Email: </th>
								<td><input type="text" name="paypal_email" class="validate[required,custom[email]]" value="{$bu_paypal}" /></td>
							</tr>
							<tr id="eway_id">
								<th>Eway ID: </th>
								<td><input type="text" name="eway_id" class="validate[required]" value="{$bu_eway}" /></td>
							</tr>
						</table>
				</div>
			</div>
			<div style="width: 100px; margin-right: auto; margin-left: auto; margin-top: 10px;">
				<input type="hidden" name="submit_form" value="1" />
				<input id="save_data" type="button" style="padding: 10px; font-weight: bold; color: #000; font-size: 12pt; cursor: pointer;" value="Save" />
			</div>
		</form>
		{elseif $step eq 2}
			<script src="/skin/red/js/uploadImages.js" type="text/javascript"></script>
			<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
			<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
			<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
			<script>
				var tmpl = "{$smarty.session.TemplateName}";
				var protype =0;
				var soc_http_host="{$soc_http_host}";
				{literal}
					$(function() {
						$("#photo_tmp_b").makeAsyncUploader({
						
							upload_url: soc_http_host+"uploadgallery.php?type=seller_product&ut=3&res=tmp-n-a",
							flash_url: '/skin/red/js/swfupload.swf',
							button_image_url: '/skin/red/images/blankButton.png',
							disableDuringUpload: 'INPUT[type="submit"]',
							file_types:'*.jpg;*.gif;*.png',
							file_size_limit:'10MB',
							file_types_description:'All images',
							button_window_mode:"transparent",
							button_text:"",
							height:"29",
							debug:false
						});
						$("#photo_tmp_c").makeAsyncUploader({
							upload_url: soc_http_host+"uploadgallery.php?type=seller_product&ut=3&res=tmp-n-b",
							flash_url: '/skin/red/js/swfupload.swf',
							button_image_url: '/skin/red/images/blankButton.png',
							disableDuringUpload: 'INPUT[type="submit"]',
							file_types:'*.jpg;*.gif;*.png',
							file_size_limit:'10MB',
							file_types_description:'All images',
							button_window_mode:"transparent",
							button_text:"",
							height:"29",
							debug:false
						});
					});
					
					function tmp_change(val) {
							tips_tmp_change = 'Note: Please update your feature image if you changed the template for your website.';
							if (val=='tmp-n-e') {
								$("#temp_c_detail,#temp_b_detail,#div_tmp_change_tips").hide();
							}
							if (val=='tmp-n-a') {
								$("#div_tmp_change_tips").html(tips_tmp_change).show();
								$("#temp_c_detail").hide();
								$("#temp_b_detail").show();
							}
							if (val=='tmp-n-b') {
								$("#div_tmp_change_tips").html(tips_tmp_change).show();
								$("#temp_c_detail").show();
								$("#temp_b_detail").hide();
							}
					 }
					 
					function uploadresponse(response){
						var aryResult = response.split('|');
						if(aryResult[2]&&$.trim(aryResult[2])=='seller_product'){
							$("#MainIMG2,#MainIMG2_c").attr('src',aryResult[0]);
							$("#MainImageH").val(aryResult[0]);
						}
					}
					
					function uploadprocess(bl){
						if (!bl) {
							$('INPUT[type="submit"]').attr('disabled','disabled');
						} else {
							$('INPUT[type="submit"]').removeAttr('disabled');
						}
					}
					
					function ImgBlank(picType){
						if (confirm("Are you sure you want to delete?")){
							if (picType == 1) {
								if($('input[name="TemplateName"]').val()=='tmp-n-a') {
									MM_swapImage('MainIMG2','','images/big1_logo.gif',1);
								}
								else {
									MM_swapImage('MainIMG2','','images/imagetemp.gif',1);
								}
								$('#MainImageH').val('');
								$('#image_action').val('delmain');
							}
							$('#store_details_form').submit();
						}
					}
					
				{/literal}
			</script>
			<form id="store_details_form" action="settings.php?step=2" method="POST">
				<div id="template_selection">
					<h3 style="font-size:16px; color:#666; font-weight:bold; display:none;">Choose Template</h3>
					{include file="startselling_step3_template.tpl"}
					<div style=" clear:both; height:1px; margin:50px 20px 30px 0;"><img src="images/spacer.gif" alt="" /></div>
					<h3 style="font-size:16px; color:#666; font-weight:bold;">Choose Colour</h3>
					<ul id="choosecolor">
						<li><img src="/skin/red/images/color-purple.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="33" {if $req.TemplateBGColor eq '33' or $req.TemplateBGColor eq ''} checked {/if} /></li>
						<li><img src="/skin/red/images/color-orange.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="36" {if $req.TemplateBGColor eq '36'}checked{/if} /></li>
						<li><img src="/skin/red/images/color-blue.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="34" {if $req.TemplateBGColor eq '34'}checked{/if} /></li>
						<li><img src="/skin/red/images/color-red.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="35" {if $req.TemplateBGColor eq '35'}checked{/if} /></li>
						<li><img src="/skin/red/images/color-green.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="37" {if $req.TemplateBGColor eq '37'}checked{/if} /></li>
						<li><img src="/skin/red/images/color-black.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="38" {if $req.TemplateBGColor eq '38'}checked{/if} /></li>
					</ul>
					<div style="clear:both; height:1px; margin:15px 20px 30px 0;"><img src="images/spacer.gif" alt="" /></div>
					<h3 style="font-size:16px; color:#666; font-weight:bold;">Select an Icon (optional)</h3>
					<ul id="chooseicon">
					{foreach from=$categories item=category}
						<li><img src="/skin/red/images/icons_lg/{$category.id}.png" width="60" height="40" alt="" /><br /><input type="radio" name="WebsiteIconID" value="{$category.id}" {if $req.WebsiteIconID eq $category.id}checked{/if} /></li>   
					{/foreach}
					</ul>
				</div>
				<div id="temp_b_detail" style="{if $req.TemplateName neq 'tmp-n-a'}display:none;{/if}">
					<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;">
						<img src="images/spacer.gif" alt=""/>
					</div>
					<div id='tmp_contents'>
						<h3 style="font-size: 16px; color: rgb(102, 102, 102); font-weight: bold;">Add your feature image</h3>
						<div style=" clear:both;">
						</div>
						<div style="float:left; width:300px;">
							 A feature image can be
							<ul class="arrows" style="width:250px;">
								<li><strong>a logo,</strong></li>
								<li><strong>an item you are selling,</strong></li>
								<li><strong>or any picture of your choice</strong></li>
							</ul>
							<p>
								Your feature image will give your website the color and feel that you want to express to your buyers (in accordance with the "terms of use")
							</p>
							<p>
								<strong>For template B, the image size should be<br/> 497X195 pixels.</strong>
							</p>
							<p>
								<strong>For template C, the image size should be<br/> 243X212 pixels.</strong>
							</p>
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="2" style="width:300px;">
									Image &nbsp;&nbsp;&nbsp;<font style="color: rgb(119, 119, 119); font-size: 12px;">(Supported image type are jpg, gif, png)</font>
								</td>
							</tr>
							<input name="MainImageH" id="MainImageH" type="hidden" value="{$req.select.MainImageH}"/>
							<input type="hidden" name="MainImg" value="{$req.select.MainImg}"/>
							<tr>
								<td colspan="2" align="left" width="300px">
									<!-- <iframe marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="margin: 0px;padding:5px 0; z-index:100;border: 0px ; float:left;" height="75" width="300px" src="uploadfile.php?op=2&ut=3&res={$smarty.session.TemplateName}&idfn=MainIMG2&idhn=MainImageH"></iframe>-->
									<div style="padding:10px 0;">
										<input type="file" id="photo_tmp_b"/>
										<span><a class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top"/><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>
										<div style="clear:both;">
										</div>
										<div style="width:300px; margin-top:5px;">
											 For perfect fit, the image size is 497 x 195 pixels
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="left" valign="top">
									<img src="{$req.select.MainImgDis}" border="1" width="{if $req.select.MainImgDis eq 'images/big1_logo.gif'}250{else}{$req.MainImgW}{/if}" id="MainIMG2" name="MainIMG2" height="{if $req.select.MainImgDis eq 'images/big1_logo.gif'}98{else}{$req.MainImgH}{/if}"/>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="left" valign="middle" height="50">
									<a href="#" onclick="ImgBlank(1);"><img src="/skin/red/images/bu-delete-sm.jpg" border="0"/></a>
								</td>
							</tr>
							</table>
						</div>
						<div style="float:right; margin-right:10px;">
							<img id="img_tmp_images" src="/skin/red/images/templateB-big.jpg" alt=""/>
						</div>
					</div>
				</div>
				<div id="temp_c_detail" style="{if $req.TemplateName neq 'tmp-n-b'}display:none;{/if}">
					<input type="hidden" id="image_action" name="image_action" value="" />
					<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;">
						<img src="images/spacer.gif" alt=""/>
					</div>
					<div id='tmp_contents_c'>
						<h3 style="font-size: 16px; color: rgb(102, 102, 102); font-weight: bold;">Add your feature image</h3>
						<div style=" clear:both;">
						</div>
						<div style="float:left; width:300px;">
							 A feature image can be
							<ul class="arrows" style="width:250px;">
								<li><strong>a logo,</strong></li>
								<li><strong>an item you are selling,</strong></li>
								<li><strong>or any picture of your choice</strong></li>
							</ul>
							<p>
								Your feature image will give your website the color and feel that you want to express to your buyers (in accordance with the "terms of use")
							</p>
							<p>
								<strong>For template B, the image size should be<br/> 497X195 pixels.</strong>
							</p>
							<p>
								<strong>For template C, the image size should be<br/> 243X212 pixels.</strong>
							</p>
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="2" style="width:300px;">
									Image &nbsp;&nbsp;&nbsp;<font style="color: rgb(119, 119, 119); font-size: 12px;">(Supported image type are jpg, gif, png)</font>
								</td>
							</tr>
							<input name="MainImageH_c" id="MainImageH_c" type="hidden" value="{$req.select.MainImageH}"/>
							<input type="hidden" name="MainImg_c" id="MainImg_c" value="{$req.select.MainImg}"/>
							<tr>
								<td colspan="2" align="left" width="300px">
									<!-- <iframe marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="margin: 0px;padding:5px 0; z-index:100;border: 0px ; float:left;" height="75" width="300px" src="uploadfile.php?op=2&ut=3&res={$smarty.session.TemplateName}&idfn=MainIMG2&idhn=MainImageH"></iframe>-->
									<div style="padding:10px 0;">
										<input type="file" id="photo_tmp_c"/>
										<span><a class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top"/><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>
										<div style="clear:both;">
										</div>
										<div style="width:300px; margin-top:5px;">
											 For perfect fit, the image size is 243 x 212 pixels
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="left" valign="top">
									<img src="{$req.select.MainImgDis}" border="1" width="{if $req.select.MainImgDis eq 'images/big1_logo.gif'}185{else}{$req.MainImgW}{/if}" id="MainIMG2_c" name="MainIMG2_c" height="{if $req.select.MainImgDis eq 'images/big1_logo.gif'}130{else}{$req.MainImgH}{/if}"/>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="left" valign="middle" height="50">
									<a href="#" onclick="ImgBlank(1);"><img src="/skin/red/images/bu-delete-sm.jpg" border="0"/></a>
								</td>
							</tr>
							</table>
						</div>
						<div style="float:right; margin-right:10px;">
							<img id="img_tmp_images" src="/skin/red/images/templateC-big.jpg" alt=""/>
						</div>
					</div>
				</div>
				<div id="submit_button">
					<input type="hidden" name="submit_form" value="1" />
					<input type="submit" style="padding: 10px; font-weight: bold; color: #000; font-size: 12pt; cursor: pointer;" value="Save" />
				</div>
			</form>
		{elseif $step eq 3}
			{if $prompt_purchase}
				<div id="sms_information">
					Verification via SMS is an Important Option that is Highly Recommended. <br />
					Get Verified via SMS today for the one time cost of $2. <br />
					<br />
					Benefits of SMS Verification include:
					<ul>
						<li>Sellers earn immediate credibility by completing the SMS Verification process.</li>
						<li>Verified members have accountability, resulting in increased confidence for buyers and sellers within the Food Marketplace community.</li>
						<li>Increased Buyer Confidence.</li>
						<li>Increased Seller Confidence.</li>
						<li>Potential for Increased sales.</li>
						<li>All verified members have their verified status confirmed with the appearance of the Verified Shield (image) on their store pages and next to their nickname in all areas of the site.</li>
					</ul>
					<br />
					 Become a Lifetime SMS Verified Member for just $2.
				</div>
				<div id="purchase_upgrade">
					{if $payment_failed}
						<div id="payment_failed">
							{$payment_failed}
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
					<form id="mainForm" action="settings.php?step=3{if $testing}&test_gateway=1{/if}" method="post" name="mainForm" style="margin-top: 10px;">
						<table id="payment_form">
							<tr>
								<th>Mobile Number</th>
								<td><input type="text" name="mobile" value="{$mobile}" /></td>
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
								<th>Total Cost</th>
								<td><span id="cost">$2</span></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<img width="37" height="23" src="/skin/red/images/icon-visa.gif">&nbsp;<img width="37" height="23" src="/skin/red/images/icon-mastercard.gif">
								</td>			
							</tr>
						</table>
						<div id="form_buttons" style="width: 100px;">
							<input id="purchase_button" name="purchase_button" type="submit" name="submit" value="Send SMS" />
						</div>
					</form>
				</div>
			{else}
				{if $sms_verified}
					<div id="verified_membership">
						The account membership has been verified.
					</div>
				{else}
					<div id="verification">
						{if $sms_verified_failed}
							<div id="sms_verified_failed">
								{$sms_verified_failed}
							</div>
						{/if}
						{if $sms_verified}
							<div id="verified_success">
								{$sms_verified}
							</div>
						{/if}
						<form action="settings.php?step=3" method="POST">
							<div id="sms_code">
								<label for="sms_verification">SMS CODE *</label> <input name="sms_verification" id="sms_verification" type="text" size="5" maxlength="5" /> <input id="verify_button" type="submit" name="verify" value="Verify" />
							</div>
						</form>
					</div>
				{/if}
			{/if}
		{elseif $step eq 4}
			<style>
				{literal}
					#product_area {
						background-color: #FFF;
						padding: 10px;
						border-radius: 10px 10px 10px 10px;					
					}
				{/literal}
			</style>
			<script>
				{literal}
				function adjustHeight(id) {
					var newheight;
					if (document.getElementById){
						newheight = document.getElementById(id).contentWindow.document .body.scrollHeight;
					}
					document.getElementById(id).height = (newheight + 200) + "px";
				}
				{/literal}
			</script>
			<div id="product_area">
				<iframe id="product_frame" style="padding: 0;" scrolling="no" frameborder="0" src="soc.php?act=signon&step=4&iframe=1" width="100%" height="500px" onLoad="adjustHeight('product_frame');"></iframe>
			</div>
		{/if}
	</div>
</div>

{include file="seller_home_rightmenu.tpl"}