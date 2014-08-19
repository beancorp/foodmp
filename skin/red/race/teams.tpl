
<script src="/skin/red/js/jquery-1.8.3.min.js"></script>
<style>
	{literal}
	#team {
		background-color: #3b3939;
		border-radius: 10px;
		padding: 10px;
		overflow: hidden;
	}
	
	#create_team_box {
		border-radius: 10px 10px 10px 10px;
		overflow: hidden;
		padding: 10px;
		float: left;
	}
	
	#join_team_box {
		overflow: hidden;
		padding: 10px;
		width: 300px;
		float: right;
	}
	
	#create_team_box h1 {
		color: #FFF;
		margin-top: 0px;
	}
	
	#join_team_box h1 {
		color: #FFF;
		margin-top: 0px;
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
	}

	.team_row {
		background-color: #000000;
		border-radius: 10px 10px 10px 10px;
		margin-bottom: 10px;
		overflow: hidden;
		padding: 10px;
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
	
	#team_name_container {
	
	}
	
	#team_name_container label {
		float: left;
		margin-left: 5px;
		width: 60px;
		color: #FFF;
	}
	
	#invite_code_container {
	
	}
	
	#invite_code_container label {
		float: left;
		margin-left: 5px;
		width: 60px;
		color: #FFF;
	}
	
	#create_button {
		clear: both;
		float: left;
	}
	
	#invite_button {
		color: #000;
		cursor: pointer;
		float: left;
		font-weight: bold;
		padding: 5px;
		font-size: 14px;
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
	
	{/literal}
</style>
	
	<div id="team">
		<div id="create_team_box">
			<form id="invite_form" method="POST">
				<h1>Invite Members</h1>
				<div id="team_name_container">
					<label>Name:</label><input type="text" name="team_name" id="team_name" value="{$team_name}" />
				</div>
				<div id="invite">
					
				</div>
				<div id="add_invite">Add</div>
				<div id="create_button">
					<input type="button" value="Invite" id="invite_button" />
				</div>
				<input type="hidden" name="action" value="invite" />
			</form>
		</div>
		<div id="join_team_box">
			<form id="join_team_form" method="POST">
				<h1>Join Team</h1>
				<input type="text" name="invite_code" id="invite_code" /> <input type="button" value="Join" id="join_team" />
			</form>
		</div>
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
				
				$('#invite_button').click(function() {
					var form_data = $('#invite_form').serialize();
					$.ajax({
						url: 'teams.php',
						type: 'POST',
						data: form_data					
					}).done(function(msg) {
						alert(msg);
					});
				});
				
				$('#join_team').click(function() {
					$.ajax({
						url: 'teams.php',
						type: 'POST',
						data: {action: 'accept', invite_code: $('#invite_code').val()} 					
					}).done(function(msg) {
						alert(msg);
					});
				});
			});
		{/literal}
	</script>
	
{if isset($members)}
	<div id="members_list">
	<h1>{$team_data.team_name}</h1>
	{assign var=val value=1}
	{foreach from=$members item=member}
		<div class="member_row">
			<div class="member_number">{$val}</div>
			<div class="member_name">{$member.bu_name} {if $member.captain eq 1} (Leader) {/if}</div>
			{assign var=val value=$val+1}
		</div>
	{/foreach}
	</div>
{/if}



