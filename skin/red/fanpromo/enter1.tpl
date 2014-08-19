<link rel="stylesheet" href="/skin/red/fanpromo/styles.css" type="text/css" />
<link type="text/css" href="/static/css/thankyou.css" rel="stylesheet" media="screen" />
<link rel="stylesheet" href="{$smarty.const.STATIC_URL}css/skin/red/jquery.tooltip.css" type="text/css" />
<link rel="stylesheet" href="{$smarty.const.STATIC_URL}css/skin/red/jquery.Jcrop.min.css" type="text/css" />

<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.min.js"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.Jcrop.min.js"></script>

<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.validationEngine.js" type="text/javascript"></script>

<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.tooltip.js" type="text/javascript"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.autocomplete.js" type="text/javascript"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/promo.js" type="text/javascript"></script>

<script src="{$smarty.const.STATIC_URL}js/skin/red/crop_image.js" type="text/javascript"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/ie-upload/jquery.ui.widget.js"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/ie-upload/jquery.fileupload.js"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/ie-upload/jquery.fileupload-process.js"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/ie-upload/jquery.fileupload-validate.js"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/ie-upload/jquery.iframe-transport.js"></script>

<!--[if IE 9 ]>	<body class="ie9"> <![endif]-->

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
	
	function fb_login(){
		FB.login(function(response) {
			if (response.authResponse) {
				console.log('Welcome!  Fetching your information.... ');
				//console.log(response); // dump complete info
				access_token = response.authResponse.accessToken; //get access token
				user_id = response.authResponse.userID; //get FB UID
				FB.api('/me', function(response) {
					user_email = response.email; //get user email
					// you can store this data into your database
				});
			} else {
				//user hit cancel button
				console.log('User cancelled login or did not fully authorize.');
			}
		}, {
			scope: 'publish_stream,email'
		});
	}
	
	(function() {
		var e = document.createElement('script');
		e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		e.async = true;
		document.getElementById('fb-root').appendChild(e);
	}());

	// Load the SDK asynchronously
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	// Here we run a very simple test of the Graph API after login is
	// successful.  See statusChangeCallback() for when this call is made.
	function testAPI() {
		console.log('Welcome!  Fetching your information.... ');
		FB.api('/me', function(response) {
			console.log('Successful login for: ' + response.name);
			document.getElementById('status').innerHTML =
			'Thanks for logging in, ' + response.name + '!';
		});
	}
{/literal}
</script>
<style>
	{literal}
		#footer_top {
			/*display: none !important;*/
		}
		
		#footer_category_list {
			/*display: none !important;*/
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
		
		
		#processing_btn_upload{
			display: none;
		}

		#preview-container{
			/*width: 860px;*/
			/*overflow-x: auto;*/
		}

		.jcrop-vline{
			display: none;
		}

		.jcrop-hline{
			display: none;
		}

		.loading{
			float: left;
			margin-left: 10px;
			display: none;
			width: 32px;
			height: 32px;
			background: url(/skin/red/fanpromo/ajax-loader.gif) 0 0 no-repeat;
		}

		#preview_upload_fanfrenzy .info{
			display: none;
		}

		.facebook-login{			
			background: url(/skin/red/fanpromo/login_btn.png) 0 0 no-repeat;
			width: 281px;
			height: 48px;
			cursor: pointer;
			position: absolute;
			top: 98px;
			left: 480px;
		}

		.login-vr{
			position: absolute;
			top: 8px;
			left: 395px;
			height: 250px;
			border-left: #dcdcdc 1px solid;
		}
		
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

		$('.style-select select').change(function(){
			$(this).parent().find('span').html($(this).find('option:selected').text()); 
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
									<li style="padding:3px 0 0 0; position: relative">
										<label>Email address or Store ID</label>
										<input class="text input" type="text" name="username">
										<div class="login-vr"></div>
										<div href="#" onclick="fb_login();" class="facebook-login"></div>
									</li>
									<li style="padding:3px 0 0 0;">
										<label>Password</label>
										<input class="text input" type="password" name="password">
									</li>
									<input type="hidden" name="reurl" value="{$redirect_url}" />
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
				
				{if $error_message}
				<div class="error_form">Error:</br>
					{$error_message}
					</br>
					</br>
				</div>
				{/if}
				
				<form id="entry_form" method="post" enctype="multipart/form-data" >
				
					<input type="hidden" id="photo_id" name="photo_id" value="{$photo_id}" />
					<input type="hidden" id="upload_flag" value="0" />
					<input type="hidden" id="submit_flag" value="0" />
					
					
					 <!-- hidden crop params for photo-->
					<input type="hidden" id="x1" name="x1" />
					<input type="hidden" id="y1" name="y1" />
					<input type="hidden" id="x2" name="x2" />
					<input type="hidden" id="y2" name="y2" />
				
				  
					<div class="entry_field photo_upload_fanfrenzy">
						<div class="entry_field_text">
							<label>Upload Image</label> 
							
							{if $photo_id > 0}
							
							{else}
							<!-- <div id="upload_question" class="question">
								<div class="tooltip_description">Your photo must less than 1MB.</div>
							</div> -->
							
							
							<br />
							<span>
							Simply take a photo either in store or at the front of your selected food retailer. Be as creative as <br />
							you like, but remember, the retailer's business name must be visible in your photo.</span>
							
							{/if}
						</div>
						<div class="clear"></div>
						<div class="entry_field_element entry-field-text-box-1">
							{if !$photo_id }
							<div class="entry_field_element text_box_1">
								<input id="text_box_1" type="text" name="text_box_1" disabled="disabled" />
							</div>
							<div class="btn-browse">
								<input type="file"  name="file" id="file" onchange="fillData()" id="file-upload" data-url="/fanpromo/ie_upload.php?ie_upload_name={$ie_upload_name}" >
								<input type="hidden" name="ie_upload_info" id="ie_upload_info" value="/fanpromo/ie_upload/{$ie_upload_name}.json"/>



								<input type="hidden" name="ie_upload_filename" id="ie_upload_filename" value="{$ie_upload_info}"/>




							</div>
							<div class="btn-upload-1" onclick="fileSelectHandler()" id="button-upload"></div>
							<div class="loading"></div>
							{/if}
							<div class="error"></div>
						</div>
						
						{if $photo_id > 0}
						<div class="entry_field_element" id="preview_upload_fanfrenzy">
							<img id="preview" src="{$smarty.const.SOC_HTTP_HOST}/fanpromo/{$photo.thumb}" />
						</div>
						{else}						
					 	<div class="entry_field_element preview_upload_fanfrenzy" id="preview_upload_fanfrenzy">
							<div id="preview-container">
								<img id="preview" />
								</br>
								</br>
								</br>
							</div>
							<div class="info" id="">
								<div class="info-row"><div class="left-title">File size</div> <input type="text" id="filesize" name="filesize" disabled="disabled" /></div>
								<div class="info-row"><div class="left-title">Type</div> <input type="text" id="filetype" name="filetype" disabled="disabled" /></div>
								<div class="info-row"><div class="left-title">Image dimension</div> <input type="text" id="filedim" name="filedim" disabled="disabled" /></div>
								<div class="info-row"><div class="left-title">W</div> <input type="text" id="w" name="w" disabled="disabled" /></div>
								<div class="info-row"><div class="left-title">H</div> <input type="text" id="h" name="h" disabled="disabled" /></div>
							</div>
						</div>
						{/if}
					</div>
					
					
					
					<div class="entry_field">
						<div class="entry_field_text">
							<label>Retailer Name</label>
						</div>
						<div class="entry_field_element">
							{if $photo_id > 0 && $photo.store_id <> 0}
							{$photo.retailer_name} 
                            <input type="hidden" id="retailer_name" name="retailer_name" value="{$photo.retailer_name}" />
							{else}
							<input id="retailer_name" type="text" name="retailer_name" value="{$photo.retailer_name}" class="validate[required]"/>
							{/if}
						</div>
					</div>
					
					<div class="entry_field">
						<div class="entry_field_text">
							<label>Category</label>
						</div>
						{*
						<div class="entry_field_element">
						*}
						
						<div class="entry_field_element style-select">							
							<span class="select-1" id="span_select_cat">
								{if $photo.category_id > 0}
									{foreach from=$lang.seller.attribute.5.subattrib item=l key=k}
										{if $photo.category_id == $k} {$l}{/if}
									{/foreach}
								{else}
									-Select one-
								{/if}
							</span>
						
							<select name="category_id" id="category_id" class="validate[required]" {if $photo.store_id > 0} disabled {/if}>
									<option value="">-Select One-</option>	
									 {foreach from=$lang.seller.attribute.5.subattrib item=l key=k}
									<option value="{$k}" rel="{$l}" {if $photo.category_id == $k} selected{/if}>{$l}</option>
									{/foreach}
							</select>
						</div>
					</div>
		 			
					
					<div class="entry_field">
						<div class="entry_field_text entry-field-fl">
							<label>Retailer Member Code</label><img id="code_processing" src="{$smarty.const.IMAGES_URL}/orange_processing.gif" width="20px" height="20px" />
							<div id="upload_question" class="question">
								<div class="tooltip_description">{$tooltip_code}</div>
							</div>
						</div>
						<div class="entry_field_element">
							{if $photo_id > 0 && $photo.store_id <> 0}
								{$photo.code}
                                <input type="hidden" id="entry_code" name="code" value="{$photo.code}" /> 
							{else}
							<input id="entry_code" type="text" name="code" value="{$photo.code}" />
							<input id="retailer_id" type="hidden" name="retailer_id" value="{$photo.store_id}" />
							{/if}
						</div>
					</div>
					
					{*
					<div class="entry_field">
						<div class="entry_field_text">
							<label>Retailer Location</label>
						</div>
						<div class="entry_field_element">
							<input id="retailer_location" type="text" name="retailer_location"/>
						</div>
					</div>
					*}
					
					<div class="entry_field" style="margin-bottom: 0;">
						<div class="entry_field_text">
							<label>Retailer Location</label>
						</div>
						<div class="entry_field_element">
						
						
						
						<div class="entry_field_element style-select ">
							<span class="select-1" id="span_select_state">{if ($photo.state_id >0)} {$photo.state} {else} -Select state- {/if}</span>
							<select name="state_id" id="state_id" class="validate[required]"   {if $photo_id > 0 && $photo.store_id <> 0}disabled {/if} >
									<option value="">-Select State-</option>
									{foreach from=$states item=state}
									<option value="{$state.id}" rel="{$state.description}" {if $photo.state_id == $state.id} selected{/if}>{$state.description}</option>
									{/foreach}
							</select>
						</div>
					</div>
					
					{*literal}
					<div class="entry_field" id="suburb_field">
						
						<style>
							.custom-combobox {
								position: relative;
								display: inline-block;
							}
							.custom-combobox-toggle {
								position: absolute;
								top: 0;
								bottom: 0;
								margin-left: -1px;
								padding: 0;
								/* support: IE7 */
								*height: 1.7em;
								*top: 0.1em;
							}
							.custom-combobox-input {
								margin: 0;
								padding: 0.3em;
								width: 110px!important;
							}
							.ui-autocomplete  {
								max-height: 300px;
								overflow-y: scroll; 
								overflow-x: hidden;
							}
						</style>
						
						{if $preselect_suburb}<script type="text/javascript">var preselect_suburb = "{$preselect_suburb}";</script>{/if}
							<div class="entry_field_text">
								<label>Suburb</label>
							</div>
								<span class="select-box">
									<select name="suburb" id="suburb_id">
										<option value="">-Select One-</option>
								</select>
							</span>
					</div>
					{/literal*}
					
					
					
					
					<div class="entry_field">
						<div class="entry_field_text">
							<label>Description</label>
						</div>
						<div class="entry_field_element">
							<textarea name="entry_description" id="entry_description" class="validate[required]">{$photo.description}</textarea>
						</div>
					</div>
					
					<input type="checkbox" class="validate[required]" name="agree_terms" id="tc_checkbox" {if ($photo_id>0)} checked disabled {/if} /> &nbsp; I agree to the <a href="/fanfrenzy_tnc.html">terms and conditions</a>. <br /><br />
					<input type="checkbox" class="validate[required]" name="copyright_confirm" id="photo_checkbox" {if ($photo_id>0)} checked disabled{/if} /> &nbsp; This photo is taken by me. <br /><br />
					
					<input class="entry-btn" id="entry_button" type="button" value="Enter Competition" onclick="validateBeforeSubmit()">					
					
					{*<input id="entry_button" type="button" name="submit" value="Enter Competition">*}
					
					<input type="hidden" id="suburb_id"/>
			</div>
			<script>
				{literal}
				window.ie9Uploaded = false;
				window.ie9 = navigator.appVersion.indexOf("MSIE 9.")!=-1;
				window.imageInfo = {};
				$(document).ready(function() {
						
					$('.question').tooltip().css({opacity: 0.9});
					
					if(window.ie9){
						$('#file').fileupload({
							dataType: 'json',
							maxFileSize: 1048576, // 5 MB
							progressall: function (e, data) {
							},
							processalways: function (e, data) {
								window.imageInfo = {}
								window.ie9Uploaded = false;
								var itv = setInterval(function(){
									if (!window.imageInfo.error) {
										loadImageInfo( $("#ie_upload_info").val() );
									} else {
										window.ie9Uploaded = true;
										clearInterval(itv);
									}
								}, 500)
							}
						}).on('fileuploaddone', function (e, data) {
							// loadImageInfo( $("#ie_upload_info").val() );
						});
					}
					
					$('#entry_code').autocomplete({
						serviceUrl: '/fanpromo/codes.php',
						onSelect: function (suggestion) {
                            if(suggestion.value){
                                $('#retailer_name').val(suggestion.value);
                                $('#retailer_name').validationEngine('hide');
                            }
							
							if(suggestion.code){
								$('#entry_code').val(suggestion.code);
								check_code_valid();
							}
							if(suggestion.data)
								$('#retailer_id').val(suggestion.data);
							if(suggestion.state_id){
								$('#state_id').val(suggestion.state_id);
								if (suggestion.state_id > 0)
									$('#span_select_state').html($('#state_id option:selected').attr("rel"));
								else
									$('#span_select_state').html("");
								$('#state_id').validationEngine('hide');
							}
							if (suggestion.category_id){
								$('#category_id').val(suggestion.category_id);
								if (suggestion.category_id > 0)
									$('#span_select_cat').html($('#category_id option:selected').attr("rel"));
								else
									$('#span_select_cat').html("");
								$('#category_id').validationEngine('hide');
							} 

						}
					});

					$('#entry_code').blur(function() {   
						if ($(this).val() == '') {
							$('#entry_code_value').empty();
							$('#entry_code').validationEngine('hide');
						}else{
							$.ajax({
					            async: false,
					            type: "POST",
					            url: "/fanpromo/codes.php",
					            dataType: "json",
					            data: { 'code': $(this).val(), 'action': "autoFill"}
					        }).done(function( d ) {
                                if (d) {
					                if (d.retailer_name) {
					            	    $('#retailer_name').val(d.retailer_name);					            	    
									    $('#retailer_id').val(d.retailer_id);
									    $('#state_id').val(d.state_id);
									    $('#category_id').val(d.category_id);
									    $('#retailer_name').validationEngine('hide');
									    $('#state_id').validationEngine('hide');
									    $('#category_id').validationEngine('hide');
									    if(d.category_id){
										    $('#span_select_cat').html($('#category_id option:selected').attr("rel"));
									    }
									    if(d.state_id){
										    $('#span_select_state').html($('#state_id option:selected').attr("rel"));
									    }
					                }
                                }
					        });
						}
					});
					

					$('#retailer_name').autocomplete({
						serviceUrl: '/fanpromo/retailers.php',
						onSelect: function (suggestion) {
							$('#retailer_name').val($(this).val());
							$('#entry_code').val(suggestion.code);
							$('#retailer_id').val(suggestion.data);
							$('#state_id').val(suggestion.state_id);
							$('#category_id').val(suggestion.category_id);
							if (suggestion.category_id > 0){
								$('#span_select_cat').html($('#category_id option:selected').attr("rel"));
							}else{
								$('#span_select_cat').html("");
							}
							if(suggestion.state_id > 0){
								$('#span_select_state').html($('#state_id option:selected').attr("rel"));
							}else{
								$('#span_select_state').html("");
							}
							$('#state_id').validationEngine('hide');
							$('#category_id').validationEngine('hide');
						}
					});

					$('#retailer_name').change(function() {
						if ($(this).val() == '') {
							$('#retailer_name_value').empty();
						}
					});					
					
					
				});
				{/literal}
			</script>
		{/if}
	</div>
</div>
</div>