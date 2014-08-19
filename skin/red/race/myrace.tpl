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
	#race_container {
		background-color: #000;
		border-left: 1px solid #FFF;
		color: #FFF;
		padding: 10px;
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
		padding: 10px;
		color: #FFF;
		margin-right: 1px;
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
		background-color: #232323;
		font-weight: bold;
		cursor: pointer;
	}
	
	.tab_link {
		float: left;
		padding: 10px;
		color: #FFF;
		margin-right: 1px;
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
		background-color: #232323;
		font-weight: bold;
		display: block;
		text-decoration: none;
		cursor: pointer;
	}
	
	.tab_selected {
		background-color: #3a3637;
	}
	
	#team {
		overflow: hidden;
		padding: 25px 10px 10px;
	}
	
	#create_team_box {
		background-color: #000000;
		border-radius: 10px 10px 10px 10px;
		padding: 0 10px 10px;
		width: 300px;
		float: left;
	}
	
	#join_team_box {
		background-color: #000000;
		border-radius: 10px 10px 10px 10px;
		padding: 0 10px 10px;
		width: 300px;
		float: right;
	}
	
	#create_team_box h1 {
		color: #FFFFFF;
		margin: 0;
		padding: 0;
		position: relative;
		text-align: center;
	}
	
	#join_team_box h1 {
		color: #FFFFFF;
		margin: 0;
		padding: 0;
		position: relative;
		text-align: center;
	}
	
	#invite {
		margin-top: 10px;
	}
	
	#add_invite {
		background-color: #04749f;
		padding: 10px;
		float: left;
		color: #FFF;
		border-radius: 10px;
		cursor: pointer;
		float: right;
		margin-bottom: 10px;
	}

	.team_row {
		background-color: #232323;
		border-radius: 10px 10px 10px 10px;
		margin-bottom: 10px;
		overflow: hidden;
		padding: 10px 0 10px 10px;
	}
	
	.field {
		padding: 5px;
		clear: both;
	}
	
	.team_row label {
		float: left;
		width: 50px;
		margin-right: 10px;
		color: #FFF;
	}
	
	.team_row input {
		float: left;
	}
	.remove {
		background-color: #FF0000;
		padding: 5px;
		color: #FFF;
		border-radius: 10px;
		cursor: pointer;
		font-size: 11pt;
	}
	
	#invite_button {		
		background-color: #04749F;
		border: 0 none;
		border-radius: 10px 10px 10px 10px;
		color: #FFFFFF;
		cursor: pointer;
		float: right;
		padding: 10px;
	}
	
	#preview_button {		
		background-color: #04749F;
		border: 0 none;
		border-radius: 10px 10px 10px 10px;
		color: #FFFFFF;
		cursor: pointer;
		padding: 10px;
		margin-right: 10px;
	}
	
	#members_list {
		background-color: #3b3939;
		border-radius: 10px;
		padding: 10px;
		overflow: hidden;
		margin-top: 10px;
	}
	
	#members_list h1 {
		color: #FFF;
		text-transform: capitalize;
		font-weight: bold;
	}
	
	.member_row {
		color: #FFF;
	}
	
	.member_number {
		color: #FFF;
		font-weight: bold;
		width: 25px;
		float: left;
	}
	
	.member_name {
		color: #FFF;
		float: left;
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
	
	.points {
		text-align: right;
		padding-right: 10px !important;
	}
	
	#team_name_container {
		float: left;
	}
	
	#invite_code_container {
		float: left;
	}
	
	#team_name {
		color: #000000;
		font-size: 12pt;
		padding: 10px;
		width: 200px;
	}
	
	#invite_code {
		color: #000000;
		font-size: 12pt;
		padding: 10px;
		width: 200px;
	}
	
	#create_button {
		float: left;
		margin-left: 5px;
	}
	
	#create_team {
		background-color: #04749F;
		border: 0 none;
		border-radius: 10px 10px 10px 10px;
		color: #FFFFFF;
		cursor: pointer;
		float: right;
		padding: 12px;
		width: 70px;
	}
	
	#join_button {
		float: left;
		margin-left: 5px;
	}
	
	#join_team {
		background-color: #04749F;
		border: 0 none;
		border-radius: 10px 10px 10px 10px;
		color: #FFFFFF;
		cursor: pointer;
		float: right;
		padding: 12px;
		width: 70px;
	}
	
	#message {
	
	}
	
	#or_text {
		background-color: #000000;
		border-radius: 60px 60px 60px 60px;
		color: #FFFFFF;
		float: left;
		font-size: 18pt;
		height: 70px;
		margin-left: 55px;
		overflow: hidden;
		padding-top: 30px;
		text-align: center;
		width: 100px;
	}
	
	#invite_box {
		background-color: #000000;
		border-radius: 10px 10px 10px 10px;
		padding: 10px;
		width: 310px;
		float: left;
	}
	
	#invite_box h1 {
		color: #FFFFFF;
		margin: 0;
		padding: 0;
		position: relative;
		text-align: center;
	}
	
	#invite_form {
		overflow: hidden;
		clear: both;
	}
	
	
	#invite_list_box {
		background-color: #000000;
		border-radius: 10px 10px 10px 10px;
		padding: 10px;
		width: 470px;
		float: right;
	}
	
	#invite_list_box h1 {
		color: #FFFFFF;
		margin: 0 0 10px;
		padding: 0;
		position: relative;
		text-align: center;
	}
	
	
	#invite_list {
		width: 100%;
		border-collapse:collapse;
		margin-top: 10px;
	}
	
	#invite_list td, #invite_list th {
		padding: 10px;
	}
	
	#invite_list thead tr {
		background-color: #232323;
	}
	
	#invite_list th {
		font-size: 10pt;
		color: #FFF;
		border-bottom: 2px solid #383637;
		padding: 5px;
	}
	
	#invite_list tbody td {
		font-size: 10pt;
		color: #FFF;
		padding: 5px;
	}
	
	#invite_list tbody td a {
		color: #FFF;
	}
	
	#invite_list tr {
		background-color: #000;
	}
	
	#leave_form {
		float: left;
		color: #FFF;
	}
	
	#leave {
		cursor: pointer;
	}
	
	#team_information_box {
		background-color: #000000;
		border-radius: 10px 10px 10px 10px;
		float: left;
		padding: 10px;
		margin-bottom: 5px;
		width: 300px;
	}
	
	#team_leave_box {
		background-color: #000000;
		border-radius: 10px 10px 10px 10px;
		float: right;
		padding: 10px;
		margin-bottom: 5px;
	}
	
	#leave_button {
		background-color: #04749F;
		border: 0 none;
		border-radius: 10px 10px 10px 10px;
		color: #FFFFFF;
		cursor: pointer;
		float: right;
		padding: 12px;
	}
	
	#bonus_message {
		clear: both;
		color: #FFFFFF;
		margin-bottom: 10px;
		margin-left: 5px;
	}
	
	#email_preview {
		background-color: #FFFFFF;
		left: 0;
		margin-left: auto;
		margin-right: auto;
		position: absolute;
		right: 0;
		top: 100px;
		width: 500px;
		overflow: hidden;
		border-radius: 10px;
		display: none;
	}
	
	#email_preview_close {
		color: #000000;
		cursor: pointer;
		float: right;
		font-size: 13pt;
		margin-bottom: 10px;
		margin-right: 10px;
		margin-top: 10px;
	}
	
	#content_tabbonus p {
		color: #FFF;
		font-size: 11pt;
	}
	
	#content_tabbonus i {
		color: #FFF;
		font-size: 11pt;
		font-style: italic;
	}
	
	#content_tabbonus p span {
		color: #FFF;
	}
	
	#content_tabbonus p a {
		color: #FFF;
		font-size: 11pt;
	}
	
	#personal_greeting_box {
		clear: both;
		margin-bottom: 10px;
		margin-top: 10px;
		background-color: #232323;
		border-radius: 10px 10px 10px 10px;
		padding: 10px 0 10px 35px;
	}
	
	#personal_greeting_box strong {
		color: #FFF;
		font-size: 11pt;
	}
{/literal}
</style>
<img src="/skin/red/race/banner4.gif" />
<div id="race_container">
	<div id="tab_container">
		<div id="tabs">
			<a href="soc.php?cp=sellerhome" class="tab_link">My Admin</a>
			<div class="tab" id="tabteam">My Team</div>
			<div class="tab" id="tabpoints">My Points</div>
			{if $captain}
				<div class="tab" id="tabinvite">Team Invite</div>
			{/if}
			<div class="tab" id="tabbank">Bank Details</div>
			{if $has_bankaccount}
			<div class="tab" id="tabrefer">Refer your Retailers</div>
			{/if}
			<div class="tab" id="tabcommission">Commission</div>
			<div class="tab" id="tabbonus">Bonus Points</div>
			<a href="soc.php?cp=foodwine" class="tab_link" target="_blank">Benefits</a>
			<a href="ultimaterace.php" class="tab_link">Leaderboards</a>
		</div>
		<div class="tab_content" id="content_tabteam">
			{if $no_team} 
				<div id="team">
					<div id="message"></div>
					<div id="create_team_box">
						<h1>Create Team</h1>
						<form id="create_form" method="POST">
							<div id="team_name_container">
								<input type="text" name="team_name" id="team_name" value="" placeholder="Team Name"  />
							</div>
							<div id="create_button">
								<input type="submit" id="create_team" value="Create" />
								<input type="hidden" name="action" value="create" />
							</div>
						</form>
					</div>
					<div id="or_text">OR</div>
					<div id="join_team_box">
						<h1>Join Team</h1>
						<form id="join_form" method="POST">
							<div id="invite_code_container">
								<input type="text" name="invite_code" id="invite_code" placeholder="Invite Code" maxlength="5" value="{$invite_code}" />
							</div>
							<div id="join_button">
								<input type="submit" id="join_team" value="Join" />
								<input type="hidden" name="action" value="join" />
							</div>
						</form>				
					</div>			
				</div>
			{else}
				{if $captain}
					<div id="team_information_box">
						<form method="POST">
							<div id="team_name_container">
								<input type="text" name="team_name" id="team_name" value="{$team_name}" placeholder="Team Name"  />
							</div>
							<div id="create_button">
								<input type="submit" id="create_team" value="Save" />
								<input type="hidden" name="action" value="change_name" />
							</div>
						</form>
					</div>
				{else}
					<h1>{$team_name}</h1>
				{/if}
				
				<div id="team_leave_box">
					<form id="leave_form" action="myrace.php" method="POST">
						<input type="hidden" name="action" value="leave" />
						<input type="submit" id="leave_button" value="Leave Team" />
					</form>
				</div>
				
				<div class="records_box">
					
					<table class="records">
						<thead>
							<tr>
								<th>Name</th>
								<th>Captain</th>
								<th align="right" style="padding-right: 10px;">Points</th>
							</tr>
						</thead>
						<tbody>
							{assign var=counter value=1}
							{foreach from=$team_members item=member}
								<tr {if ($counter mod 2) neq 0}class="odd"{/if}>
									<td>{$member->name}</td>
									<td>{if $member->captain eq 1}
											Yes
										{else}
											{if $captain}
												<a href="myrace.php?action=promote&user={$member->user_id}">[Make Captain]</a>
											{else}
												No
											{/if}
										{/if}
									</td>
									<td class="points">{$member->points}</td>
								</tr>
								{assign var=counter value=$counter+1}
							{/foreach}
						</tbody>
					</table>
				</div>
			{/if}
		</div>
		<div class="tab_content" id="content_tabpoints">
			<h1>My Points</h1>
			{if $bonus_message}
				<div id="bonus_message">
				{$bonus_message}
				</div>
			{/if}
			<div class="records_box">
				{php}
					myPoints();				
				{/php}
			</div>
		</div>
		{if $captain}
			<div id="email_preview">
				<div id="email_preview_close">Close</div>
				<iframe id="email_preview_content" width="500" height="750" scrolling="no" frameBorder="0"></iframe>
			</div>
			<script>
				{literal}
					$(document).ready(function() {
						$('#preview_button').click(function() {
							$.ajax({
								url: "myrace.php",
								type: "POST",
								data: {action: "preview", greeting: $('#personal_greeting').val()} 					
							}).done(function(data) {
								var iframe =  $('#email_preview_content');
								var idoc = iframe[0].contentDocument;
								idoc.open();
								idoc.write(data);
								idoc.close();
								$('#email_preview').show();
								$(window).scrollTop(0);
							});
						});
						$('#email_preview_close').click(function() {
							$('#email_preview').hide();
						});
					});
				{/literal}
			</script>
			<div class="tab_content" id="content_tabinvite">
				<div id="team">
					<div id="invite_box">
						<h1>Send Invites</h1>
						<form id="invite_form" method="POST">
							<div id="invite">
								
							</div>
							<div id="add_invite">Add</div>
							<div id="personal_greeting_box">
								<strong>Personal Greeting</strong> <br />
								<textarea id="personal_greeting" name="personal_greeting" cols="40" rows="2"></textarea>
							</div>
							<div style="clear: both; float: left;">
								<input type="submit" value="Send" id="invite_button" />
								<input type="button" value="Preview" id="preview_button" />
								<input type="hidden" name="action" value="invite" />
								<input type="hidden" name="tab" value="tabinvite" />
							</div>
						</form>
					</div>
					<div id="invite_list_box">
						<h1>Invite List</h1>
						{php}
							inviteList();
						{/php}
					</div>
					<script>
						{literal}
							function add_row() {
								var html = '<div class="team_row">' +
											'<div class="field"><label>Name:</label><input type="text" name="invite_name[]" />&nbsp;&nbsp;<span class="remove">Remove</span></div>' +
											'<div class="field"><label>Email:</label><input type="text" name="invite_address[]" /></div>' +
											'</div>';
								$('#invite').append(html);
							}
							$(document).ready(function() {		
								$('#add_invite').click(function() {
									add_row();
								});
								$(document).on('click', '.remove', function() {
									$(this).parent().parent().remove();
								});
								add_row();
							});
						{/literal}
					</script>
				</div>
			</div>
		{/if}
		
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
					width: 300px;
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
					width: 100px;
					margin-right: 10px;
				}
				
				#account_submit {
					margin-left: 115px;
					cursor: pointer;
				}
			</style>
			{/literal}
			<div id="account_box">
			Your Referral ID is: <strong>{$ref_name}</strong> <br />
			To activate your Referral ID your bank account details must be entered below: <br />
				<form id="bank_details" action="myrace.php?tab=tabbank" method="POST">
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
				Once your bank account details have been correctly entered you can start referring food retailers, <br /> earn a commission and compete in <a href="{$smarty.const.SOC_HTTPS_HOST}ultimaterace.php">The Ultimate Race</a>.
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
					border-radius: 10px 10px 10px 10px;
					left: 0;
					margin-left: auto;
					margin-right: auto;
					padding: 10px;
					position: absolute;
					right: 0;
					top: 100px;
					width: 450px;
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
		<div class="records_box">
			{php}
				commission();
			{/php}
		</div>
	</div>
	<div class="tab_content" id="content_tabbonus">
		{$bonus_points_page}
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
				{/literal}
				{if $tab}
					show_tab('{$tab}');
				{else}
					{literal}
					show_tab($('.tab').first().attr('id'));
					{/literal}
				{/if}
				{literal}
			});
		{/literal}
	</script>
</div>