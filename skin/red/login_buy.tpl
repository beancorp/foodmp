{literal}
<style type="text/css">
/*	Login Form	*/

div#login_new							{ border: #e7e7e7 1px solid; border-width: 0 1px; width: 434px; padding:2px 0 10px 10px;}
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

input.submit							{ background: url(skin/red/images/btn_go.gif) no-repeat; width: 84px; height: 30px; margin: 0px 0 0 28px; cursor: pointer;}

* html input.submit						{ margin: 0px 0 0 33px;}

div#login_new form p					{ font-size: 11px; padding-left: 28px;}
a.red {color:#CC0138;text-decoration:none;}
a.red:hover{ text-decoration: underline;}
.input{ width:160px;}

#fb-login-button-seller {position: absolute; right:70px; top:165px; #top:180px; width: 165px;}

#login_box {
	background: url("skin/red/images/bg_login_browse.gif") no-repeat scroll 0 0 transparent;
    padding-top: 5px;
    height: 223px;
    padding-top: 10px;
    width: 429px;
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
	margin-left: 20px;
}

#content{float:left; margin-left:10px; width:926px !important;}

.login-left{float:left; padding-bottom:5px;}
.login-right{float:right; padding-top:28px;}

#registration_image {
	text-decoration: none;
}

#registration_text {
	font-size: 11pt !important;
    font-weight: bold;
    text-align: center;
	top: 55px;
    font-size: 11pt !important;
    font-weight: bold;
    position: absolute;
    text-align: center;
	color: #ce083a;
}

</style>
{/literal}
<div id="content" class="login-content">
	<div class="login-left">
		<img style="margin-top:25px; width:447px;" alt="top" src="skin/red/images/bg_login_newTop.gif">
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
								<input class="submit" type="submit" value="" name="submit">
							</fieldset>
							<p>
								<strong>* Food & Wine retailers</strong> enter your <i>Store ID</i> to login. <br />
								<strong>* Sellers & Buyers </strong> enter your <i>Email address</i> to login.
							</p>
						</div>
					</form>
					<div style="position:absolute; left: 250px; top: 95px; width: 165px;">
						<div id="fb-login-button" data-scope="email,user_checkins" class="fb-login-button">Login with Facebook</div>
					</div>
				</div>
			</div>
		</div>
		<img style="width:447px;" alt="bottom" src="skin/red/images/bg_login_newBottom.gif">
    </div>
    <div class="login-right">
		<a href="soc.php?cp=register&search_type=store" title="Register" id="registration_image">
			<img src="skin/red/images/bg_login_right.jpg" alt="Register" style="width:460px;" />
			<div id="registration_text">
				New to Food Marketplace? <br />
				Click to Register
			</div>
		</a>
    </div>
</div>