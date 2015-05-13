{literal}
<style type="text/css">
/*	Login Form	*/

div#login_new							{ border: #e7e7e7 1px solid; border-width: 0 1px; width: 495px; padding: 2px 0 40px 40px;}
* html div#login_new					{ margin-top:-3px;}
*:first-child+html div#login_new		{ margin-top:-3px;}

p#or									{ clear: both; padding:20px 0 0px 125px;}

.slideHeader img						{ float: left; cursor: pointer; height: 60px;}
.slideHeader p							{ padding-top: 30px; text-indent: 20px;  cursor: pointer;}

.slideBody								{ margin: 20px 0 0 27px;}

div#login_new form#browse				{ background: url(skin/red/images/bg_login_browse.gif) no-repeat; width: 429px; height: 223px; padding-top:10px;}
div#login_new form#admin				{ background: url(skin/red/images/bg_login1.gif) no-repeat; width: 430px; height: 385px; padding-top:5px;}
div#login_new ol.unstyled				{ list-style: none; margin:12px 0 0 31px; float: left;}
* html div#login_new ol.unstyled		{ margin: 12px 0 0 32px;}

div#login_new ol.unstyled li			{ padding: 3px 0;}
div#login_new ol.unstyled li a.red		{ color: #cc0138; text-decoration: none;}
div#login_new ol.unstyled li a.red:hover{ text-decoration: underline;}

select#lg_select						{ width:120px; margin-top:5px; padding: 2px;}

input.submit							{ width: 84px; font-weight: bold; color: #000; height: 30px; margin: 0px 0 0 28px; cursor: pointer;}

* html input.submit						{ margin: 0px 0 0 33px;}

div#login_new form p					{ font-size: 11px; padding-left: 28px;}
a.red {color:#CC0138;text-decoration:none;}
a.red:hover{ text-decoration: underline;}
.input{ width:160px;}

#fb-login-button-seller {position: absolute; right:70px; top:165px; #top:180px; width: 165px;}

#login_box {
	background: url("skin/red/images/bg_login1.gif") no-repeat scroll 0 0 transparent;
    height: 385px;
    padding-top: 5px;
    width: 430px;
	padding: 10px;
	margin-left: auto;
	margin-right: auto;
	overflow: hidden;
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
	margin: 50px 20px 20px;
}

#registration_button {
	background-color: #FF8F05;
    border: 0 none !important;
    border-radius: 10px 10px 10px 10px;
    color: #FFFFFF;
    cursor: pointer;
    display: block;
    float: left;
    font-size: 11pt !important;
    font-weight: bold;
    margin-left: 65px;
    margin-top: 30px;
    padding: 15px;
    text-align: center;
    text-decoration: none;
	background-image: -moz-linear-gradient(center bottom , #DE600B 30%, #FF8F05 65%);
}


</style>
{/literal}
<div id="content">
	<img src="skin/red/images/bg_login_newTop.gif" alt="top" />
    <div id="login_new">
		<div id="login_box">
			{if $error}
				<div id="error_message">
				{$error}
				</div>
			{/if}
			<div id="login_box_content">
				<form action="login.php" method="POST">
					<div>
						<fieldset>
							<ol class="unstyled">
								<li style="padding:3px 0 0 0;">
								<label>Store ID or Email address</label>
								<br>
								<input class="text input" type="text" name="username">
								</li>
								<li style="padding:3px 0 0 0;">
								<label>Password</label>
								<br>
								<input class="text input" type="password" name="password">
								</li>
								{if $reurl}
									<input type="hidden" name="reurl" value="{$reurl}" />
								{/if}
								{if $search_type}
									<input type="hidden" name="search_type" value="{$search_type}" />
								{/if}
								<li style="padding:3px 0 0 0;">
								<a class="red" onclick="javascript:window.open('/forgetpass.php','ForgetPassword','width=600,height=210,scrollbars=yes,status=yes')" title="Forgotten Password" href="#">Forgotten password?</a>
								</li>
							</ol>
						</fieldset>
						<fieldset class="submit">
							<input class="submit" type="submit" value="Login" name="submit">
						</fieldset>
						<p>
							<a href="soc.php?cp=register" id="registration_button" >New to Food Marketplace? <br /> Click here to Register</a>
						</p>
					</div>
				</form>
				<div style="position:absolute; right:70px; top:124px; width: 165px;">
					<div id="fb-login-button" data-scope="email" class="fb-login-button">Login with Facebook</div>
				</div>
			</div>
		</div>
	</div>
	<img src="skin/red/images/bg_login_newBottom.gif" alt="bottom" />
</div>