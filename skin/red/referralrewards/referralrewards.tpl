<link href="/skin/red/race/ui-darkness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<script src="/skin/red/js/jquery-1.10.2.min.js"></script>
<script src="/skin/red/js/jquery-ui-1.10.3.custom.min.js"></script>

<script src="/skin/red/js/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="/skin/red/js/jquery.validationEngine.js" type="text/javascript"></script>
<link rel="stylesheet" href="/skin/red/css/jquery.tooltip.css" type="text/css" />
<script src="/skin/red/js/jquery.tooltip.js" type="text/javascript"></script>

<style>
{literal}
	
	@media only screen and (max-width: 767px), screen and (max-device-width: 720px) {
		#open_search_panel {
			display: none !important;
		}
	}


	div.jquery-gdakram-tooltip { 
		color: #000;
	}
	
	div.jquery-gdakram-tooltip div.content {
		background-color: #FFF !important;
		color: #000;
	}
	
	div.jquery-gdakram-tooltip div.content ul li { 
		color: #000;
	}
	
	div.jquery-gdakram-tooltip div.down_arrow {
		background : url('/skin/red/referralrewards/down_arrow.png') 60px 0px no-repeat;
	}
	
	#header {
		margin-bottom: 0px;
	}

	#container {
		width: 930px;
	}
	
	#page_container {
		color: #FFFFFF;
		position: absolute;
		top: 515px;
		width: 930px;
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
	}
	
	.tab_content p {
		color: #FFF;
	}
	
	.separator {
		border-bottom: 2px solid #FF0000;
		padding-bottom: 20px !important;
	}
	
	.tab_content h1 {
		color: #FFFFFF;
		font-size: 17pt;
		font-weight: bold;
		margin-bottom: 30px;
		margin-top: 0;
		padding: 5px;
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
		height: 15px;
		text-align: center;
		font-size: 11pt;
	}

	.tab_selected {
		background-color: #3a3637;
	}
	
	.tab_content h1 {
		font-size: 17pt;
	}
	
	.tab_content strong {
		font-size: 16pt;
	}
	
	.tab_content sup {
		font-size: 10pt;
	}
	
	.tab_content em {
		font-style: italic;
	}
	
	#commission_window {
		background-color: #FFFFFF;
		border-radius: 10px 10px 10px 10px;
		left: 40px;
		margin-left: auto;
		margin-right: auto;
		padding: 10px;
		position: absolute;
		right: 0;
		top: 100px;
		width: 450px;
		display: none;
		z-index: 99;
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
		margin-top: 5px;
	}
	#commission_rate {
		background-image: url(/skin/red/referralrewards/commission/commission_rate.png);
		background-repeat: no-repeat;
		width: 315px;
		height: 50px;
		margin-top: 15px;
		overflow: hidden;
	}
	
	#commission_percentage {
		color: #FFFFFF;
		font-size: 15pt;
		margin-left: 235px;
		margin-top: 11px;
	}
	
	#commission_details {
		margin-top: 15px;
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
		color: #000;
	}
	
	#commission_text {
		font-family: Arial;
		font-size: 10pt;
		line-height: 16pt;
		margin-top: 15px;
	}
	
	#commission_text p {
		color: #000 !important;
	}
	
	#commission_terms {
		font-family: Arial;
		font-size: 8pt;
		line-height: 12pt;
		margin-top: 15px;
		color: #000 !important;
	}
	
	#commission_terms a {
		color: #000 !important;
	}
	
	#commission_close {
		position: absolute;
		right: 25px;
		text-decoration: underline;
		cursor: pointer;
	}
	
	.commission_link {
		text-decoration: underline;
		cursor: pointer;
	}
	
	.learn_more {
		text-decoration: underline;
		cursor: pointer;
		font-size: 14px !important;
	}
	
	#signup_form {
		margin-left: 10px;
		overflow: hidden;
	}
	
	#registration_sucessful {
		margin-top: 10px;
		padding: 10px;
		overflow: hidden;
		color: #000!important;
	}
	
	#registration_sucessful h1 {
		color: #dd0e3a;
		font-weight: bold;
	}
	
	#signup_form fieldset {
		margin-top: 10px;
		border-radius: 10px 10px 10px 10px;
		padding: 10px;
		overflow: hidden;
	}
	
	#signup_form li {
		margin-top: 10px;
		overflow: hidden;
	}
	
	#signup_form label {
		float: left;
		color: #FFF;
		font-weight: normal;
		width: 80px;
	}
	
	#signup_form li select, #signup_form li input  {
		float: left;
	}
	
	#signup_form ul {
		list-style-type: none;
		width: 350px;
	}
	
	#register_button {
		background-color: #5F5F5F;
		border: medium none;
		border-radius: 10px;
		color: #FFFFFF !important;
		cursor: pointer;
		font-size: 10pt;
		font-weight: bold;
		height: 40px;
		margin-top: 10px;
		padding: 5px;
		text-transform: uppercase;
		width: 100px;
	}
	
	#email_processing {
		display: none;
	}
	
	#nickname_processing {
		display: none;
	}
	
	#sub_domain {
		display: none;
		height: 50px;
	}
	
	#url_processing {
		display: none;
	}
	
	.question {
		background-image: url("/skin/red/referralrewards/question_icon.jpg");
		background-repeat: no-repeat;
		cursor: pointer;
		float: right;
		height: 27px;
		width: 27px;
	}
	
	.tooltip_description {
		color: #FFF;
	}
	
	#register_box {
		color: #FFFFFF;
		display: block;
		float: right;
		margin-top: 10px;
		padding-right: 10px;
		text-align: right;
		margin-right: 30px;
	}
	
	#register_box a {
		color: #FFF !important;
	}
	
	.registration_left {
		float: left;
	}
	
	.registration_divider {
		float: left;
		margin-left: 50px;
	}
	
	.registration_right {
		float: left;
		margin-left: 50px;
		margin-top: 110px;
	}
	
	.content_text {
		color: #FFF !important;
	}
	
	#signup_form h1 {
		font-size: 16pt;
		margin-bottom: 10px;
		margin-top: 10px;
		text-align: center;
		color: #FFF;
	}
	
	#signup_form input[type="text"], #signup_form select {
		background-color: #5f5f5f;
		color: #FFF;
		border: none;
		border-top: 3px solid #4c4a4b;
		padding: 5px;
		width: 195px;
	}
	
	#signup_form input[type="checkbox"] {
		background-color: #5f5f5f;
		color: #FFF;
		border: none;
		border-top: 3px solid #4c4a4b;
		margin-right: 5px;
	}
	
	#signup_form select {
		width: 205px;
	}
	
	#signup_form select option {
		color: #FFF;
	}
	
	.referral_heading {
		font-weight: normal;
		font-size: 22px;
		color: #FFF !important;
		margin-bottom: 20px;
		margin-top: 10px;
	}
	
	.referral_heading a, .referral_heading strong {
		color: #FFF !important;
		font-size: 22px;
	}
	
	.retailer_information {
		color: #FFF !important;
		font-size: 14px;
	}
	
	.retailer_information strong {
		color: #FFF !important;
		font-size: 14px;
	}
	
	#content_tab_2 div, #content_tab_2 div * {
		color: #FFF !important;
		font-size: 15px;
	}
	
	#content_tab_2 div span {
		font-size: 15px !important;
	}
	
	#content_tab_3 div, #content_tab_3 div * {
		color: #FFF !important;
		font-size: 15px;
	}
	
	#content_tab_3 div span {
		font-size: 15px !important;
	}
	
	#content_tab_4 div, #content_tab_4 div * {
		color: #FFF !important;
		font-size: 15px;
	}
	
	#content_tab_4 div span {
		font-size: 15px !important;
	}
	
	#signup_box {
		background-color: #3A3637;
		border-bottom-left-radius: 10px;
		border-bottom-right-radius: 10px;
		clear: both;
		color: #FFFFFF;
		overflow: hidden;
	}
	
	#page_content {
		height: 1200px;
	}
	
	#terms_link {
		color: #FFF !important;
		display: block;
		float: right;
		margin-right: 50px;
		margin-bottom: 20px;
	}
	
	#corner_fan_promotion {
		display: block;
		position: absolute;
		top: 92px;
		background-image: url(/skin/red/referralrewards/corner_fan_promotion.png);
		width: 236px;
		height: 141px;
		overflow: hidden;
		cursor: pointer;
		z-index: 200;
	}
{/literal}
</style>
<script>
	{literal}
		$(document).ready(function() {
			$('#commission_close').click(function() {
				$('#commission_window').hide();
			});
			
			$('.commission_link').click(function() {
				$('#commission_window').show();
				$(window).scrollTop(0);
			});
			
			$('.learn_more').click(function() {
				show_tab('tab_2');
			});
		});
	{/literal}
</script>
<div id="page_content">
	<a href="/fanpromotion" id="corner_fan_promotion"></a>
	<img src="/skin/red/referralrewards/new_banner.jpg" />
</div>
<div id="commission_window">
	<div id="commission_container">
		<div id="commission_close">Close</div>
		<div id="commission_header"></div>
		<div id="commission_rate">
			<div id="commission_percentage">{$commission_percentage}%</div>
		</div>
		<div id="commission_details">
			<div class="row">
				<div class="icon"><img src="/skin/red/race/commission/retailer_icon.png" /></div>
				<div class="text">The cash amount for every new food retailer you sign-up to FoodMarketplace is: <strong style="color: #d02232;">${$signup_retailer}</strong></div>
			</div>
			<div class="row">
				<div class="icon"><img src="/skin/red/race/commission/link_icon.png" /></div>
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
<div id="page_container">
	<div id="tab_container">
		<div id="tabs">
			<div class="tab" id="tab_1">COMMISSION</div>
			<div class="tab" id="tab_2">ABOUT</div>
			<div class="tab" id="tab_3">TUTORIAL</div>
			{if $show_terms}
				<div class="tab" id="tab_4">TERMS & CONDITIONS</div>
			{/if}
		</div>
		<div class="tab_content" id="content_tab_1">
			<div class="content_text separator">
				<div class="referral_heading">
					Sign-up a food retailer to FoodMarketplace and <br />
					collect a <a class="commission_link"><strong>${$signup_retailer}</strong> Commission</a> every time <a class="learn_more">(Learn More)</a>.
				</div>
				<div class="retailer_information">
					<strong>Food Retailers:</strong> Fruit & Veg. shops, Bakeries, Butchers, Deli's, Fish shops, Liquor stores, Cafes, Juice Bars, Fast Food, Grocery & <br /> Convenience stores, Restaurants, Pubs & Bars.
				</div>
			</div>
		</div>
		<div class="tab_content" id="content_tab_2">
			<h1 class="separator">ABOUT</h1>
			<div>
				{$referral_information_page}
			</div>
		</div>
		<div class="tab_content" id="content_tab_3">
			<h1 class="separator">TUTORIAL</h1>
			<div>
				{$referral_tutorial_page}
			</div>
		</div>
		{if $show_terms}
			<div class="tab_content" id="content_tab_4">
				<h1>TERMS & CONDITIONS</h1>
				<div>
				{$terms_page}
				</div>
			</div>
		{/if}
		<div id="signup_box">
			<div id="signup_form">
				<h1>Join Now and make $$$</h1>
				<form id="registration" action="signup.php" method="POST">
					<fieldset>
						<div class="registration_left">
							<input type="hidden" id="fb_id" name="fb_id" value="{$req.fb_id}" />
							<ul>
								<li>
									<label>Name *</label>
									<input name="name" id="name" type="text" class="validate[required]" value="{$name}"/>
									<div id="name_question" class="question" style="opacity: 0.9;">
										<div class="tooltip_description" style="display:none">This is your personal name.<br />It won't be visible to the public.</div>
									</div>
								</li>
								<li>
									<label>Nickname *</label>
									<input name="nickname" id="nickname" type="text" class="validate[required]" value="{$nickname}"/> &nbsp; <img id="nickname_processing" src="ajax-loader.gif" />
									<div id="nickname_question" class="question" style="opacity: 0.9;">
										<div class="tooltip_description" style="display:none">This is your online identity. <br /> <span style="font-size: 11pt; font-weight: bold; color: #000;">It cannot be changed.</span></div>
									</div>
								</li>
								<input type="hidden" name="urlstring" id="store_url" />
								<li>
									<label>Email * </label>
									<input id="email" name="email" type="text" class="validate[required,custom[email]] text-input" value="{$email}" /> &nbsp; <img id="email_processing" src="ajax-loader.gif" />
									<div id="email_question" class="question" style="opacity: 0.9;">
										<div class="tooltip_description" style="display:none">This is your email address. <br /> It will require verification.</div>
									</div>
								</li>
								<li id="state">
									<label>State *</label>
									<select id="state_selection" name="state" class="validate[required]">
										<option value="">[Select a State]</li>
										{foreach from=$state_list key=k item=v}
										   <option value="{$v.id}">{$v.description}</li>
										{/foreach}
									</select>
									<div id="state_question" class="question" style="opacity: 0.9;">
										<div class="tooltip_description" style="display:none">The state you currently live in.</div>
									</div>
								</li>
								<li id="suburb">
									<label>Suburb *</label>
									<select id="suburb_list" name="suburb" class="validate[required]">
									</select>
									<div id="email_question" class="question" style="opacity: 0.9;">
										<div class="tooltip_description" style="display:none">The suburb you currently live in.</div>
									</div>
								</li>
								<li id="postcode">
									<label>Postcode *</label>
									<input id="postcode_field" name="postcode" type="text" class="validate[required,custom[integer],minSize[4]]" value="{$postcode}" maxlength="4" />
									<div id="email_question" class="question" style="opacity: 0.9;">
										<div class="tooltip_description" style="display:none">Your current postcode.</div>
									</div>
								</li>
							</ul>
							<div id="register_box">
								<input type="checkbox" id="terms_and_conditions" class="validate[required]" name="terms_and_conditions" /> I agree to the site <a target="_blank" href="soc.php?cp=terms">Terms of use</a>
								<br />
								<input id="register_button" type="button" value="Register" disabled="disabled" />
							</div>
							<input type="hidden" name="referrer" value="{$referrer}" />
							{if $team}
								<input type="hidden" name="team" value="{$team}" />
							{/if}
							<input type="hidden" name="submit_form" value="1" />
						</div>
						<div class="registration_divider">
							<img src="/skin/red/referralrewards/divider.jpg" />
						</div>
						<div class="registration_right">
							<div id="fb-login-button" class="fb-login-button" data-scope="email">Register with facebook</div>
						</div>							
					</fieldset>
				</form>
			</div>
			<a id="terms_link" href="/referaretailer?terms">Terms & Conditions</a>
		</div>
	</div>
	<script>
		{foreach from=$suburb_data key=k item=v}
			var {$k}_suburbs = '{$v}';
		{/foreach}
		
		{literal}
		var url_valid = true;
		var nickname_valid = false;
		var email_valid = false;
		var accept_terms = false;
		
		function nickNameMessage() {
			$('#nickname').validationEngine('showPrompt', 'Nickname already exists', 'red');
		}

		function emailMessage() {
			$('#email').validationEngine('showPrompt', 'Email already exists', 'red');
		}
		
		function store_url(url_string) {
			$.ajax({
				type: 'POST',
				url: 'ajax_requests.php?action=2',
				dataType: 'json',
				data: { value: url_string }
			}).done(function( data ) {
				if (data.valid) {
					url_valid = true;
				} else {
					url_valid = false;
					nickNameMessage();
				}
			});
		}
		
		function show_tab(tab_id) {
			$('.tab').removeClass('tab_selected');
			$('.tab_content').hide();
			$('#' + tab_id).addClass('tab_selected');
			$('#content_' + tab_id).show();
			updateHeight();
		}
		
		function updateHeight() {
			var container_height = $('#page_container').height() + 400;
			$('#page_content').css('height', container_height + 'px');
		}

		$(document).ready(function() {		
			$('.tab').click(function() {
				if ($('#tab_4').is(":visible")) {
					$('#tab_4').hide();
					$('#content_tab_4').hide();
				}
				show_tab($(this).attr('id'));
			});
				
			$('#tab_container').show();
			{/literal}
			{if $show_terms}
				{literal}
				show_tab('tab_4');
				{/literal}
			{else}
				{literal}
				show_tab('tab_1');
				{/literal}
			{/if}
			{literal}
			
			$('#suburb').hide();
			$('#postcode').hide();
			$('#register_button').attr('disabled','disabled');
			$('.question').tooltip().css({opacity: 0.9});
			$('#registration').validationEngine('attach', {scroll: false});
			$('#state_selection').change(function() {
				$('#suburb_list').empty();
				var value = $('#state_selection').val();
				$('#suburb_list').append('<option value="">[Please Select]</option>');
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
				$('#suburb').show();
			});
			
			$('#suburb').change(function() {
				$.ajax({
					type: "POST",
					url: "ajax_requests.php?action=5",
					dataType: "json",
					data: { suburb: $('#suburb_list').val(), state: $.trim($('#state_selection option:selected').text())}
				}).done(function( data ) {
					$('#postcode_field').val(data);
				});
			});

			$('#email').change(function() {
				$('#email_processing').show();
				$.ajax({
					type: "POST",
					url: "ajax_requests.php?action=3",
					dataType: "json",
					data: { email: $('#email').val()}
				}).done(function( data ) {
					if (!data.valid) {
						email_valid = false;
						emailMessage();
					} else {
						email_valid = true;
					}
					$('#email_processing').hide();
				});
			});
			
			$('#store_url').change(function() {
				store_url($('#store_url').val());
			});

			$('#nickname').change(function() {
				$('#nickname_processing').show();
				$.ajax({
					type: "POST",
					url: "ajax_requests.php?action=4",
					dataType: "json",
					data: { value: $('#nickname').val()}
				}).done(function( data ) {
					if (data.valid) {
						nickname_valid = true;
						$('#sub_domain').show();
						var nickname = $('#nickname').val();
						nickname = nickname.toLowerCase();
						nickname = nickname.replace(/[^a-z0-9]/g,"");
						$('#store_url').val(nickname);
						store_url(nickname);
					} else {
						nickname_valid = false;
						nickNameMessage();
					}
					$('#nickname_processing').hide();
				});
			});

			$('#suburb_list').change(function() {
				$('#postcode').show();
			});
			
			$('#terms_and_conditions').change(function() {
				accept_terms = ($(this).is(":checked"));
				if (accept_terms) {
					$('#register_button').removeAttr('disabled');
				} else {
					$('#register_button').attr('disabled','disabled');
				}
			});
			
			$('#register_button').click(function() {
				if ($("#registration").validationEngine('validate')) {
					if (!nickname_valid) {
						nickNameMessage();
					}
					if (!email_valid) {
						emailMessage();
					}
					if (!url_valid) {
						nickNameMessage();
					}
					if (email_valid && nickname_valid && accept_terms) {
						$('#registration').submit();
					}
				}
			});
		});
		{/literal}
	</script>
</div>