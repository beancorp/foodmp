<link href="/skin/red/race/ui-darkness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<script src="/skin/red/js/jquery-1.10.2.min.js"></script>
<script src="/skin/red/js/jquery-ui-1.10.3.custom.min.js"></script>

<script type="text/javascript" src="/skin/red/js/niftyplayer.js"></script>

<script src="/skin/red/js/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="/skin/red/js/jquery.validationEngine.js" type="text/javascript"></script>
<link rel="stylesheet" href="/skin/red/css/jquery.tooltip.css" type="text/css" />
<script src="/skin/red/js/jquery.tooltip.js" type="text/javascript"></script>

<style>
{literal}
	#header {
		margin-bottom: 0px;
	}
	#container {
		width: 930px;
	}
	#race_container {
		background-color: #000;
		border-left: 1px solid #FFF;
		color: #FFF;
		padding: 20px 10px 10px;
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
	
	.tab_content h1 {
		border-bottom: 2px solid #FF0000;
		color: #FFFFFF;
		font-size: 17pt;
		font-weight: bold;
		margin-bottom: 10px;
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
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
		background-color: #232323;
		font-weight: bold;
		cursor: pointer;
		height: 30px;
		text-align: center;
		font-size: 11pt;
	}
	.tab_link {
		float: left;
		padding: 15px;
		color: #FFF;
		margin-right: 1px;
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
		background-color: #232323;
		font-weight: bold;
		cursor: pointer;
		height: 30px;
		text-align: center;
		font-size: 11pt;
		display: block;
		text-decoration: none;
	}
	.leaderboard_text {
		font-size: 9pt;
	}
	.tab_selected {
		background-color: #3a3637;
	}
	#information {
		color: #FFFFFF;
		float: left;
		font-size: 11pt;
		width: 380px;
	}
	#prizes {
		float: left;
		margin-left: 50px;
	}
	#individual_event {
		float: left;
	}
	#team_event {
		float: left;
		margin-left: 50px;
	}
	.prize_row {
		clear: both;
		margin-bottom: 10px;
		overflow: hidden;
	}
	.prize_row .trophy {
		float: left;
	}
	.prize_row .prize_text {
		color: #FFFFFF;
		float: left;
		font-size: 11pt;
		font-weight: bold;
		margin-left: 10px;
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
	
	#teams table tbody tr {
		cursor: pointer;
	}
	
	#team_members {
		background-color: #383637;
		left: 0;
		margin-left: auto;
		margin-right: auto;
		padding: 0 10px 10px;
		position: absolute;
		right: 0;
		width: 450px;
		top: 600px;
		display: none;
		z-index: 2;
	}
	
	#team_members .scores td {
		font-size: 10pt;
	}
	
	#team_members_header {
		margin-bottom: 5px;
		overflow: hidden;
	}
	
	#team_name {
		color: #FFFFFF;
		float: left;
		font-size: 11pt;
		font-weight: bold;
		text-transform: uppercase;
		padding-top: 5px;
	}
	
	#close_button {
		float: right;
		cursor: pointer;
		background-image: url(/skin/red/race/close_button.jpg);
		background-repeat: no-repeat;
		width: 60px;
		height: 25px;
	}
	
	#modal_overlay {
		background-color: #000000;
		display: none;
		height: 255px;
		left: 0;
		margin-left: auto;
		margin-right: auto;
		opacity: 0.3;
		position: absolute;
		right: 0;
		top: 860px;
		width: 860px;
		z-index: 1;
	}
	
	.page_button {
		cursor: pointer;
	}
	
	.points {
		text-align: right;
	}
	
	#heading {		
		border-left: 1px solid #FFFFFF;
		color: #FFFFFF;
		font-size: 16pt;
		font-weight: bold;
		overflow: hidden;
		position: absolute;
		top: 480px;
		width: 930px;
	}
	
	#join_race {
		float: left;
		width: 160px;
		margin-left: 25px;
	}
	
	.sound {
		background: url("/skin/red/images/race/sound-img.jpg") no-repeat scroll 0 0 transparent;
		display: block;
		float: right;
		height: 32px;
		overflow: hidden;
		width: 32px;
		margin-left: 10px;
	}
	
	.close {
		background: url("/skin/red/images/race/sound-img-close.jpg") no-repeat scroll 0 0 transparent!important;
	}
	
	.tab_content * {
		color: #FFF;
		font-size: 11pt;
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
		left: 0;
		margin-left: auto;
		margin-right: auto;
		padding: 10px;
		position: absolute;
		right: 0;
		top: 100px;
		width: 450px;
		display: none;
	}
	
	#commission_container {
		margin-left: auto;
		margin-right: auto;
		width: 315px;
	}
	
	#commission_header {
		background-image: url(/skin/red/race/commission/header.png);
		background-repeat: no-repeat;
		width: 300px;
		height: 150px;
		margin-top: 5px;
	}
	#commission_rate {
		background-image: url(/skin/red/race/commission/commission_rate.png);
		background-repeat: no-repeat;
		width: 315px;
		height: 50px;
		margin-top: 15px;
		overflow: hidden;
	}
	
	#commission_percentage {
		color: #FFFFFF;
		font-size: 15pt;
		margin-left: 260px;
		margin-top: 10px;
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
	}
	
	#commission_text {
		font-family: Arial;
		font-size: 10pt;
		line-height: 16pt;
		margin-top: 15px;
	}
	
	#commission_terms {
		font-family: Arial;
		font-size: 8pt;
		line-height: 12pt;
		margin-top: 15px;
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
	
	.rank {
		padding-left: 25px!important;
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
	
	#agree_terms {
		padding-right: 10px;
		color: #FFF;
	}
	
	#agree_terms a {
		color: #FFF;
	}
{/literal}
</style>
<script>
{literal}
	var cisplay = false;
	var t1 = null;
	
	function replay(){
	  try{
		if(niftyplayer('niftyPlayer1').getState()=='finished'){
		   niftyplayer('niftyPlayer1').play();
			$("#sound").removeClass("close");
		   cisplay = true;
		}  
	  }catch(ex){}
	} 

	function myPlaySound(){
		if(cisplay){
			niftyplayer('niftyPlayer1').pause();
			cisplay = false;
			$("#sound").addClass("close");
			window.clearInterval(t1);
		} else {
			niftyplayer('niftyPlayer1').play();
			cisplay = true;
			$("#sound").removeClass("close");
			t1 = window.setInterval("replay()",500); 
		}
	}
	
	$(document).ready(function() {
		$('#commission_close').click(function() {
			$('#commission_window').hide();
		});
		
		$('.commission_link').click(function() {
			$('#commission_window').show();
			$(window).scrollTop(0);
		});
	});
{/literal}
</script>
<img src="/skin/red/race/banner.gif" />
<div id="heading">
	<div style="width:530px;margin-left:190px;float: left;padding-top: 5px;">
		<img src="/skin/red/race/tagline.png" width="480px" height="35px" />
		<a id="sound" class="sound close" href="javascript:void(0);" onclick="javascript:myPlaySound();">&nbsp;</a>
	</div>
	<div id="join_race"><a href="#signup_form"><img src="/skin/red/race/join_race.png" /></a></div>
	<object style="position:absolute;left:-9999px;" id="niftyPlayer1" width="1" align="" height="1" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
	  <param value="/skin/red/js/niftyplayer.swf?file=/upload/media/soc_race_sound.mp3&as=0" name="movie">
	  <param value="high" name="quality">
	  <param value="#FFFFFF" name="bgcolor">
	  <embed width="165" align="" height="37" pluginspage="http://www.macromedia.com/go/getflashplayer" swliveconnect="true" type="application/x-shockwave-flash" name="niftyPlayer1" bgcolor="#FFFFFF" quality="high" src="/skin/red/js/niftyplayer.swf?file=/upload/media/soc_race_sound.mp3&as=0">
	</object>
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
			<p>
				<a href="ultimaterace.php?terms">Read our Terms and Conditions</a>
			</p>
		</div>
	</div>
</div>
<div id="race_container">
	<div id="tab_container">
		<div id="tabs">
			<div class="tab" id="tab_1">$50,000 CASH</div>
			<div class="tab" id="tab_2">INDIVIDUAL EVENT<br /><span class="leaderboard_text">LEADERBOARD</span></div>
			<div class="tab" id="tab_3">TEAM EVENT<br /><span class="leaderboard_text">LEADERBOARD</span></div>
			<div class="tab" id="tab_4">ABOUT</div>
			{if $show_terms}
				<div class="tab" id="tab_5">TERMS & CONDITIONS</div>
			{/if}
			{if $show_myrace}
				<a href="myrace.php" class="tab_link">MY PANEL</a>
			{/if}
		</div>
		<div class="tab_content" id="content_tab_1">
			<h1>$50,000 CASH is on the line</h1>
			<div>
				<div id="information">
					{$race_information_page}
				</div>
				<div id="prizes">
					<div id="individual_event">
						<h2>Individual Event</h2>
						<div class="prize_row">
							<div class="trophy"><img src="/skin/red/race/gold.jpg" /></div><div class="prize_text">1st place - $20,000</div>
						</div>
						<div class="prize_row">
							<div class="trophy"><img src="/skin/red/race/silver.jpg" /></div><div class="prize_text">2nd place - $6,000</div>
						</div>
						<div class="prize_row">
							<div class="trophy"><img src="/skin/red/race/bronze.jpg" /></div><div class="prize_text">3rd place - $4,000</div>
						</div>
					</div>
					<div id="team_event">
						<h2>Team Event</h2>
						<div class="prize_row">
							<div class="trophy"><img src="/skin/red/race/gold.jpg" /></div><div class="prize_text">1st place - $20,000<br />Winner Takes All</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab_content" id="content_tab_2">
			<h1>INDIVIDUAL EVENT LEADERBOARD</h1>
			<div class="leaderboard" id="individual_scores">
			{php}
				showLeaderBoardPage();
			{/php}
			</div>
		</div>
		<div class="tab_content" id="content_tab_3">
			<h1>TEAM EVENT LEADERBOARD</h1>
			<div class="leaderboard" id="teams">
				{php}
					showTeamsPage();
				{/php}
			</div>
			<div id="modal_overlay"></div>
			<div id="team_members">
				
			</div>
			<script>
				{literal}
					$(document).ready(function() {
						$(document).on('click', '#teams .team_row', function() {
							$.ajax({
									url: 'ultimaterace.php',
									type: 'POST',
									data: {action: 'members', team_id: $(this).attr('tag')} 					
								}).done(function(data) {
									$('#team_members').html(data);
									$('#team_members').show();
								});
								$('#team_members').css('top', ($('#container').height() / 2) + 100);
								$('#modal_overlay').css('height', $('#teams table').height());
								$('#modal_overlay').show();
							});
						$(document).on('click', '#close_button', function() { 
							$('#team_members').empty();
							$('#team_members').hide();
							$('#modal_overlay').hide();
						});
					});
				{/literal}
			</script>
		</div>
		<div class="tab_content" id="content_tab_4">
			<h1>ABOUT</h1>
			{$about_page}
		</div>
		{if $show_terms}
			<div class="tab_content" id="content_tab_5">
				<h1>TERMS & CONDITIONS</h1>
				{$terms_page}
			</div>
		{/if}
	</div>
	<div id="signup_form">
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
					<li id="state">
						<label>State *</label>
						<select id="state_selection" name="state" class="validate[required]" style="width: 160px;">
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
						<select id="suburb_list" name="suburb" class="validate[required]" style="width: 160px;">
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
			</fieldset>
			<fieldset id="register">
				<span id="agree_terms"><input type="checkbox" id="terms_and_conditions" class="validate[required]" name="terms_and_conditions" /> I agree to the site <a target="_blank" href="soc.php?cp=terms">Terms of use</a></span>
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
					if ($('#tab_5').is(":visible")) {
						$('#tab_5').hide();
						$('#content_tab_5').hide();
					}
					$('.tab').removeClass('tab_selected');
					$(this).addClass('tab_selected');
					$('.tab_content').hide();
					$('#content_' + $(this).attr('id')).show();
				});
				$('#tab_container').show();
				{/literal}
				{if $show_terms}
					{literal}
					show_tab('tab_5');
					{/literal}
				{else}
					{literal}
					show_tab('tab_1');
					{/literal}
				{/if}
				{literal}
			});
		{/literal}
	</script>
</div>