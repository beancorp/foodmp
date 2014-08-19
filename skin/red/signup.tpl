<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.validationEngine.js" type="text/javascript"></script>
<link rel="stylesheet" href="{$smarty.const.STATIC_URL}css/skin/red/jquery.tooltip.css" type="text/css" />
<script src="{$smarty.const.STATIC_URL}js/skin/red/jquery.tooltip.js" type="text/javascript"></script>
{literal}
<style>
	#signup_form {
		margin-left: 10px;
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
		font-weight: bold;
		width: 100px;
	}
	
	#signup_form li select, #signup_form li input  {
		float: left;
		width: 150px;
	}
	
	#signup_form ul {
		list-style-type: none;
		margin-left: 200px;
		width: 350px;
	}
	
	#step1 {
		background-color: #fccd5e;
	}
	
	#step1 label {
		color: #000!important;
	}
	
	#step2 {
		background-color: #acceeb;
	}
	
	#step2 label {
		color: #000!important;
	}
	
	#step3 {
		background-color: #463c8e;
	}
	
	#register {
		float: right;
	}
	
	#register_button {
		font-weight: bold;
		color: #000;
		font-size: 12pt;
		cursor: pointer;
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
		width: 21px;
		height: 20px;
		background-image: url('/skin/red/images/icon-question.gif');
		background-repeat: no-repeat;
		float: right;
		cursor: pointer;
    }
	
	.tooltip_description {
		color: #FFF;
	}
	
</style>
{/literal}
<div id="signup_form">
{if $registration_sucessful}
	{literal}
		<script type="text/javascript">
		var fb_param = {};
		fb_param.pixel_id = '6011881529439';
		fb_param.value = '0.00';
		fb_param.currency = 'AUD';
		(function(){
		  var fpw = document.createElement('script');
		  fpw.async = true;
		  fpw.src = '//connect.facebook.net/en_US/fp.js';
		  var ref = document.getElementsByTagName('script')[0];
		  ref.parentNode.insertBefore(fpw, ref);
		})();
		</script>
		<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6011881529439&amp;value=0&amp;currency=AUD" /></noscript>
	{/literal}
	<div id="registration_sucessful">
		<h1>Welcome to FoodMarketplace</h1>
		Hi {$name} <br /><br />
		{$registration_sucessful}
	</div>
	<img src="https://track.performtracking.com/aff_l?offer_id=493" width="1" height="1" />
{else}
	<form id="registration" action="signup.php" method="POST">
		<fieldset id="step1">
			<div id="fb-login-button" class="fb-login-button" data-scope="email">Register with facebook</div>
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
					<input name="nickname" id="nickname" type="text" class="validate[required]" value="{$nickname}"/> <img id="nickname_processing" src="orange_processing.gif" width="20px" height="20px" />
					<div id="nickname_question" class="question" style="opacity: 0.9;">
						<div class="tooltip_description" style="display:none">This is your online identity. <br /> <span style="font-size: 11pt; font-weight: bold; color: #FFF;">It cannot be changed.</span></div>
					</div>
				</li>
				<input type="hidden" name="urlstring" id="store_url" />
				<li>
					<label>Email * </label>
					<input id="email" name="email" type="text" class="validate[required,custom[email]] text-input" value="{$email}" /> <img id="email_processing" src="orange_processing.gif" width="20px" height="20px" />
					<div id="email_question" class="question" style="opacity: 0.9;">
						<div class="tooltip_description" style="display:none">This is your email address. <br /> It will require verification.</div>
					</div>
				</li>
			</ul>
		</fieldset>
		<fieldset id="step2">
			<ul>
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
					<label>{$lang.labelZIP} *</label>
					<input id="postcode_field" name="postcode" type="text" class="validate[required,custom[integer],minSize[4]]" value="{$postcode}" maxlength="{if $lang.labelZIP eq 'ZIP'}10{else}4{/if}" />
					<div id="email_question" class="question" style="opacity: 0.9;">
						<div class="tooltip_description" style="display:none">Your current {$lang.labelZIP}.</div>
					</div>
				</li>
			</ul>
		</fieldset>
		<table width="135" border="0" style="display:inline-block; padding-top:10px; width:115px; float:left;" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose GeoTrust SSL for secure e-commerce and confidential communications.">
			<tr>
				<td width="135" align="center" valign="top">
					<script type="text/javascript" src="https://seal.geotrust.com/getgeotrustsslseal?host_name={$smarty.const.DOMAIN}&amp;size=S&amp;lang=en"></script><br />
					<a href="http://www.geotrust.com/ssl/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;"></a></td>
			</tr>
		</table>
		<fieldset id="register">
			<span style="padding-right:10px"><input type="checkbox" id="terms_and_conditions" class="validate[required]" name="terms_and_conditions" /> I agree to the site <a target="_blank" href="soc.php?cp=terms">Terms of use</a></span>
			<input id="register_button" type="button" value="Register" disabled="disabled" />
			<input type="hidden" name="referrer" value="{$referrer}" />
			{if $team}
				<input type="hidden" name="team" value="{$team}" />
			{/if}
			<input type="hidden" name="submit_form" value="1" />
		</fieldset>
	</form>
	</div>
		<script>
		
		var suburb_data = {literal}{{/literal}
		{foreach from=$suburb_data key=k item=v}
		'{$k}': '{$v}',
		{/foreach}{literal}}{/literal};
		
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
				$('#suburb_list').append(suburb_data[value]);
				$('#suburb').show();
			});
			
			{/literal}{if $lang.labelZIP ne 'ZIP'}{literal}
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
			{/literal}{/if}{literal}

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
{/if}