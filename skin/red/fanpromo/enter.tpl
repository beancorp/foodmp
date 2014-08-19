<link rel="stylesheet" href="/skin/red/fanpromo/styles.css" type="text/css" />
<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.validationEngine.js" type="text/javascript"></script>
<script src="/skin/red/fanpromo/promo.js" type="text/javascript"></script>
<link rel="stylesheet" href="/skin/red/css/jquery.tooltip.css" type="text/css" />
<script src="/skin/red/js/jquery.tooltip.js" type="text/javascript"></script>

<script src="/skin/red/fanpromo/jquery.autocomplete.js" type="text/javascript"></script>


<script>
{literal}
		var t = null;
		window.fbAsyncInit = function () {
			FB.init({
				appId: facebook_appID,
				status: !0,
				cookie: !1,
				xfbml: !1,
				oauth: !0
			}), FB.Event.subscribe("auth.login", function (e) {
				"connected" == e.status && FB.api("/me", function (e) {
					t = e
				})
			}), FB.Event.subscribe("auth.statusChange", function (e) {
				"connected" == e.status && FB.api("/me", function (e) {
					t = e, fetchFacebookData(t), addFacebookButton();
					$('#fb-login-button').hide();
				})
			})
		}
		
{/literal}
</script>
<style>
	{literal}
		#footer_top {
			display: none !important;
		}
		
		#footer_category_list {
			display: none !important;
		}
		
		#signup_form {

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
			border-radius: 10px 10px 10px 10px;
			overflow: hidden;
			margin-top: 0px;
			padding: 0px;
		}
		
		#signup_form li {
			margin-top: 0px;
			overflow: hidden;
		}
		
		#signup_form label {
			color: #565656 !important;
			font-size: 14px;
			font-weight: bold;
			float: left;
			width: 90px;
		}
		
		#signup_form li select, #signup_form li input  {
			float: left;
		}
		
		#signup_form ul {
			list-style-type: none;
			width: 350px;
			margin-left: 0;
		}
		
		.register_button_left {
			cursor: pointer;
			font-size: 10pt;
			font-weight: bold;
			height: 40px;
			margin-top: 10px;
			padding: 5px;
			text-transform: uppercase;
			width: 100px;
			float: left;
		}
		
		.register_button {
			background-color: #f4a02c;	
			background-image: -moz-linear-gradient(center bottom , #f4a02c 30%, #ff8f05 65%);
			border: medium none;
			border-radius: 5px;
			color: #FFFFFF !important;
			cursor: pointer;
			font-size: 10pt;
			font-weight: bold;
			height: 40px;
			margin-top: 10px;
			padding: 5px;
			text-transform: uppercase;
			width: 100px;
			float: right;
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
			background-image: url("/skin/red/images/icon-question.gif");
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
			color: #000;
			display: block;
			float: left;
			margin-top: 8px;
			text-align: right;
			width: 300px;
		}
		
		#register_box a {
			color: #000 !important;
		}
		
		.registration_left {
			float: left;
			width: 300px;
		}
		
		.registration_divider {
			float: left;
			margin-left: 50px;
		}
		
		.registration_right {
			float: left;
			margin-left: 150px;
			margin-top: 50px;
		}
		
		.content_text {
			color: #000 !important;
		}
		
		#signup_form h1 {
			font-size: 16pt;
			margin-bottom: 10px;
			margin-top: 10px;
			text-align: center;
			color: #000;
		}
		
		#signup_form input[type="text"], #signup_form select {
			color: #000;
			padding: 5px;
			width: 195px;
		}
		
		#signup_form input[type="checkbox"] {
			color: #000;
			margin-right: 5px;
		}
		
		#signup_form select {
			width: 205px;
		}
		
		#signup_form select option {
			color: #000;
		}
		
		#promo_page_content {
			min-height: 600px;
		}
		
		
		#login_box ol.unstyled				{ list-style: none; margin: 0px; float: left;}
		#login_box ol.unstyled li			{ padding: 3px 0;}

		#login_box input.submit	{ width: 84px; font-weight: bold; color: #000; height: 30px; margin: 0px 0 0 28px; cursor: pointer;}
		#login_box input.submit	{ margin: 0px 0 0 33px;}

		#login_box form p	{ font-size: 11px; padding-left: 28px;}
		a.forgotten {color:#565656;text-decoration:none;}
		a.forgotten:hover{ text-decoration: underline;}
		
		#login_box .input {
			display: block;
			margin-top: 6px;
			padding: 10px;
			width: 280px;
		}
		
		#login_box {

		}

		#error_message {
			color: #FF0000;
			font-size: 10pt;
			font-weight: bold;
			margin-left: 50px;
			padding: 10px;
			position: absolute;
			text-align: center;
			width: 300px;
		}

		#login_box_content {
			width: 300px;
		}
		
		#login_box_content label {
			color: #565656 !important;
			font-size: 14px;
			font-weight: bold;
			clear: both;
			display: block;
		}
		
		fieldset.submit {
			width: auto;
		}
		
		.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
		.autocomplete-suggestion { cursor: pointer; padding: 2px 5px; white-space: nowrap; overflow: hidden; }
		.autocomplete-selected { background: #F0F0F0; }
		.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
		
	{/literal}
</style>
<script>
{literal}
	$(document).ready(function() {
		$('.tab_button').click(function() {
			var identifier = $(this).attr('id');
			$('.tab_content').hide();
			$('#' + identifier + '_content').show();
			$('.tab_button').removeClass('tab_button_selected');
			$(this).addClass('tab_button_selected');
		});
		
		
		$('#promo_page_tab_about').addClass('tab_button_selected');
		$('#promo_page_tab_about_content').show();
		
		$('#entry_form').validationEngine('attach', {scroll: false});
		
		
		$('#select_register').click(function() {
			$('#login_box').hide();
			$('#signup_box').show();
		});
		
		$('#select_login').click(function() {
			$('#signup_box').hide();
			$('#login_box').show();
		});
		
	});
{/literal}
</script>
<div id="promo_page_container">
	<div id="promo_page_top">
		<div id="promo_header_container">
			<div id="promo_header_title"></div>
			<div id="promo_header_photos"></div>
		</div>
		
		<div id="promo_page_tabs">
			<div id="promo_page_tab_header">
				<div class="tab_button" id="promo_page_tab_about">About</div>
				<div class="tab_button" id="promo_page_tab_howtoenter">How to enter</div>
				<div class="tab_button" id="promo_page_tab_howitworks">How it works</div>
			</div>
			<div id="promo_page_tab_content">
				<div class="tab_content" id="promo_page_tab_about_content">{$about_text}</div>
				<div class="tab_content" id="promo_page_tab_howtoenter_content">{$how_to_enter}</div>
				<div class="tab_content" id="promo_page_tab_howitworks_content">{$how_it_works}</div>
			</div>
		</div>
	</div>
	<div id="promo_page_content">
		{if not $logged_in}
			<div id="login_box">
				<h3>Enter competition</h3> <br />
				You must be logged in to enter. <br /><br />
				{if $error}
					<div id="error_message">
					{$error}
					</div>
				{/if}
				<div id="login_box_content">
					<form action="/login.php" method="POST">
						<div>
							<fieldset>
								<ol class="unstyled">
									<li style="padding:3px 0 0 0;">
										<label>Email address or Store ID</label>
										<input class="text input" type="text" name="username">
									</li>
									<li style="padding:3px 0 0 0;">
										<label>Password</label>
										<input class="text input" type="password" name="password">
									</li>
									<input type="hidden" name="reurl" value="https://foodmarketplace.com.au/fanpromo/enter.php" />
									<li style="padding:3px 0 0 0;">
										<a class="forgotten" onclick="javascript:window.open('/forgetpass.php','ForgetPassword','width=600,height=210,scrollbars=yes,status=yes')" title="Forgotten Password" href="#">Forgotten password?</a>
									</li>
								</ol>
							</fieldset>
							<fieldset class="submit">
								<input class="register_button_left" type="button" value="Register" id="select_register"> <input class="register_button" type="submit" value="Login" name="submit">
							</fieldset>
						</div>
					</form>
				</div>
			</div>		
		
		
			<div id="signup_box" style="display: none;">
				<div id="signup_form">
					<h3>Enter competition</h3> <br />
					Sign up to enter. <br /><br />
					<form id="registration" action="/signup.php" method="POST">
						<fieldset>
							<div class="registration_left">
								<input type="hidden" id="fb_id" name="fb_id" value="{$req.fb_id}" />
								<ul>
									<li>
										<label>Name *</label>
										<input name="name" id="name" type="text" class="validate[required]" value="{$name}"/>
										<!--
										<div id="name_question" class="question" style="opacity: 0.9;">
											<div class="tooltip_description" style="display:none">This is your personal name.<br />It won't be visible to the public.</div>
										</div>
										-->
									</li>
									<li>
										<label>Nickname *</label>
										<input name="nickname" id="nickname" type="text" class="validate[required]" value="{$nickname}"/> &nbsp; <img id="nickname_processing" src="/ajax-loader2.gif" />
										<!--
										<div id="nickname_question" class="question" style="opacity: 0.9;">
											<div class="tooltip_description" style="display:none">This is your online identity.</div>
										</div>-->
									</li>
									<input type="hidden" name="urlstring" id="store_url" />
									<li>
										<label>Email * </label>
										<input id="email" name="email" type="text" class="validate[required,custom[email]] text-input" value="{$email}" /> &nbsp; <img id="email_processing" src="/ajax-loader2.gif" />
										<!--
										<div id="email_question" class="question" style="opacity: 0.9;">
											<div class="tooltip_description" style="display:none">This is your email address. <br /> It will require verification.</div>
										</div>-->
									</li>
									<li id="state">
										<label>State *</label>
										<select id="state_selection" name="state" class="validate[required]">
											<option value="">[Select a State]</li>
											{foreach from=$state_list key=k item=v}
											   <option value="{$v.id}">{$v.description}</li>
											{/foreach}
										</select>
										<!--
										<div id="state_question" class="question" style="opacity: 0.9;">
											<div class="tooltip_description" style="display:none">The state you currently live in.</div>
										</div>-->
									</li>
									<li id="suburb">
										<label>Suburb *</label>
										<select id="suburb_list" name="suburb" class="validate[required]">
										</select>
										<!--
										<div id="email_question" class="question" style="opacity: 0.9;">
											<div class="tooltip_description" style="display:none">The suburb you currently live in.</div>
										</div>-->
									</li>
									<li id="postcode">
										<label>Postcode *</label>
										<input id="postcode_field" name="postcode" type="text" class="validate[required,custom[integer],minSize[4]]" value="{$postcode}" maxlength="4" />
										<!--
										<div id="email_question" class="question" style="opacity: 0.9;">
											<div class="tooltip_description" style="display:none">Your current postcode.</div>
										</div>-->
									</li>
								</ul>
								<div id="register_box">
									<input type="checkbox" id="terms_and_conditions" class="validate[required]" name="terms_and_conditions" /> I agree to the site <a target="_blank" href="/soc.php?cp=terms">Terms of use</a>
									<br />
									<input id="select_login" class="register_button_left" type="button" value="Login" /> &nbsp;
									<input id="register_button" class="register_button" type="button" value="Register" disabled="disabled" />
								</div>
								<input type="hidden" name="referrer" value="{$referrer}" />
								{if $team}
									<input type="hidden" name="team" value="{$team}" />
								{/if}
								<input type="hidden" name="submit_form" value="1" />
							</div>
							<div class="registration_divider">
							&nbsp;
							</div>
							<div class="registration_right">
								<div id="fb-login-button" class="fb-login-button" data-scope="email">Register with facebook</div>
							</div>							
						</fieldset>
					</form>
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
						url: '/ajax_requests.php?action=2',
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
				
				$(document).ready(function() {					
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
							url: "/ajax_requests.php?action=5",
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
							url: "/ajax_requests.php?action=3",
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
							url: "/ajax_requests.php?action=4",
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
		{else}
			<div id="promo_page_entry_form">
				<h3>Enter competition</h3>
				<form id="entry_form" method="post" enctype="multipart/form-data">
					<div class="entry_field">
						<div class="entry_field_text">
							<label>Upload Image</label> <br />
							<span>
							Simply take a photo either in store or at the front of your selected food retailer. Be as creative as <br />
							you like, but remember, the retailer's business name must be visible in your photo.</span>
						</div>
						<div class="entry_field_element">
							<input type="file" class="validate[required]" name="file" id="file">
						</div>
					</div>
					<!--
					<div class="entry_field">
						<div class="entry_field_text">
							<label>Retailer Member Code</label>
						</div>
						<div class="entry_field_element">
							<select class="validate[required]" name="retailer_code">
								<option value="">Please Select</option>
								{$store_select}
							</select>
						</div>
					</div>
					-->
					
					
					<div class="entry_field">
						<div class="entry_field_text">
							<label>Retailer Member Code</label>
						</div>
						<div class="entry_field_element">
							<input id="entry_code" type="text" name="code" value="{$retailer_code_name}" class="validate[required]" />
							<input id="entry_code_value" type="hidden" name="retailer_code" value="{$retailer_code_storeid}" />
						</div>
					</div>
					
					<div class="entry_field">
						<div class="entry_field_text">
							<label>Retailer Locaton</label>
						</div>
						<div class="entry_field_element">
							<input id="retailer_location" type="text" name="code" class="validate[required]" />
							<input id="retailer_location_value" type="hidden" name="retailer_location" />
						</div>
					</div>
					
					<div class="entry_field">
						<div class="entry_field_text">
							<label>Description</label>
						</div>
						<div class="entry_field_element">
							<textarea class="validate[required]" name="entry_description"></textarea>
						</div>
					</div>
					
					<input type="checkbox" class="validate[required]" name="agree_terms" /> &nbsp; I agree to the <a href="/fanfrenzy_tnc.html">terms and conditions</a>. <br /><br />
					
					
					<input id="entry_button" type="submit" name="submit" value="Enter Competition">
				</form>
			</div>
			<script>
				{literal}
				$(document).ready(function() {
					$('#entry_code').autocomplete({
						serviceUrl: '/fanpromo/codes.php',
						onSelect: function (suggestion) {
							$('#entry_code_value').val(suggestion.data);
							$('#retailer_location').val($(this).val());
							$('#retailer_location_value').val(suggestion.data);
						}
					});
					
					$('#retailer_location').autocomplete({
						serviceUrl: '/fanpromo/retailers.php',
						onSelect: function (suggestion) {
							$('#retailer_location_value').val(suggestion.data);
						}
					});
					
					$('#retailer_location').change(function() {
						if ($(this).val() == '') {
							$('#retailer_location_value').empty();
						}
					});
					
					$('#entry_code').change(function() {
						if ($(this).val() == '') {
							$('#entry_code_value').empty();
						}
					});
				});
				{/literal}
			</script>
		{/if}
	</div>
</div>