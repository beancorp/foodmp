{literal}
<style>
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
		display: none;
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
</style>
{/literal}
<div id="change_password">
	{if $password_change_failed}
		<div id="password_change_failed">
			{$password_change_failed}
		</div>
	{/if}
	{if $password_change_successful}
		<div id="password_change_successful">
			{$password_change_successful}
		</div>
	{/if}
	<form action="signup.php?verification={$verification}" method="POST">
		<ul id="change_password_form">
			<ul>
				<li>
					<label for="new_password">Create Password *</label> <input name="new_password" id="new_password" type="password" size="20" />
				</li>
				<li>
					<label for="confirm_password">Confirm Password *</label> <input name="confirm_password" id="confirm_password" type="password" size="20" />
				</li>
				<li>
					<input id="change_password_button" type="submit" name="change_password" value="OK" />
				</li>
			</ul>
		</ul>
	</form>
</div>
<table width="135" border="0" style="display:inline-block; padding-top:10px; width:115px; float:left;" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose GeoTrust SSL for secure e-commerce and confidential communications.">
	<tr>
		<td width="135" align="center" valign="top">
			<script type="text/javascript" src="https://seal.geotrust.com/getgeotrustsslseal?host_name={$smarty.const.DOMAIN}&amp;size=S&amp;lang=en"></script><br />
			<a href="http://www.geotrust.com/ssl/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;"></a></td>
	</tr>
</table>