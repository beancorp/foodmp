{literal}
<style type="text/css">
/*	Login Form	*/

div#login_new							{ border: #e7e7e7 1px solid; border-width: 0 1px; width: 509px; padding: 2px 0 40px 25px;}
* html div#login_new					{ margin-top:-3px;}
*:first-child+html div#login_new		{ margin-top:-3px;}

p#or									{ clear: both; padding:20px 0 0px 125px;}	

.slideHeader img						{ float: left; cursor: pointer; height: 60px; cursor: pointer;}
.slideHeader p							{ padding-top: 30px; text-indent: 20px;}
.slideHeader p strong 					{ cursor: pointer;}

.slideBody								{ margin: 20px 0 0 110px;}

div#login_new form#browse				{ background: url(/skin/red/images/bg_login_browse.gif) no-repeat; width: 320px; height: 231px;}
div#login_new form#admin				{ background: url(/skin/red/images/bg_login1.gif) no-repeat; width: 320px; height: 288px;}
div#login_new ol.unstyled				{ list-style: none; margin: 12px 0 0 60px; float: left;}
* html div#login_new ol.unstyled		{ margin: 12px 0 0 32px;}

div#login_new ol.unstyled li			{ padding: 3px 0;}
div#login_new ol.unstyled li a.red		{ color: #cc0138; text-decoration: none;}
div#login_new ol.unstyled li a.red:hover{ text-decoration: underline;}

select#lg_select						{ width:120px; margin-top:5px; padding: 2px;}

input.submit							{ background: url(/skin/red/images/btn_go.gif) no-repeat; width: 84px; height: 30px; margin: 0px 0 0 60px; cursor: pointer;}

* html input.submit						{ margin: 0px 0 0 65px;}

div#login_new form p					{ font-size: 11px; padding-left: 65px;}


.register_row {
    margin-top: 40px;
	overflow: hidden;
	padding-bottom: 10px;
    padding-top: 10px;
	display: block;
	padding-left: 25px;
}

.register_row .register_icon {
	float: left;
	margin-right: 5px;
	width: 110px;
	font-size: 18pt;
}

.register_row .register_title {
	font-weight: bold;
	float: left;
	width: 115px;
	margin-right: 5px;
	font-size: 12pt;
	text-transform: uppercase;
	text-align: center;
}

.register_row .buyers_title {
	padding-top: 35px;
}

.register_button {
	float: left;
	display: block;
	border: 0 none!important;
	border-radius:10px 10px 10px 10px;
	cursor:pointer;
	color:#fff;
	font-weight:bold;
	font-size: 12pt!important;
	float: left;
	margin-left: 10px;
	text-decoration: none;
	padding: 5px;
	font-style: Arial!important;
	width: 170px;
	text-align: center;
}

.buyers_button {
	background-color: #FF8F05;
	background-image: linear-gradient(bottom, rgb(222,96,11) 30%, rgb(255,143,5) 65%);
	background-image: -o-linear-gradient(bottom, rgb(222,96,11) 30%, rgb(255,143,5) 65%);
	background-image: -moz-linear-gradient(bottom, rgb(222,96,11) 30%, rgb(255,143,5) 65%);
	background-image: -webkit-linear-gradient(bottom, rgb(222,96,11) 30%, rgb(255,143,5) 65%);
	background-image: -ms-linear-gradient(bottom, rgb(222,96,11) 30%, rgb(255,143,5) 65%);

	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0.3, rgb(222,96,11)),
		color-stop(0.65, rgb(255,143,5))
	);
	
	margin-top: 21px;
}

.seller_button {
	background-color: #7abcff; /* Old browsers */
	background: -moz-linear-gradient(top,  #7abcff 0%, #60abf8 44%, #4096ee 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#7abcff), color-stop(44%,#60abf8), color-stop(100%,#4096ee)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #7abcff 0%,#60abf8 44%,#4096ee 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #7abcff 0%,#60abf8 44%,#4096ee 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #7abcff 0%,#60abf8 44%,#4096ee 100%); /* IE10+ */
	margin-top: 17px;
}


</style>

{/literal}
<div id="content">				 
    <img src="/skin/red/images/bg_login_newTop.gif" alt="top" />
    <div id="login_new">
		<a href="{$soc_https_host}signup.php{if $team}?team={$team}{/if}" class="register_row">

			<div class="register_icon">
				<img src="/skin/red/images/icons/icon_lock.gif" alt="lock icon" />
			</div>
		<!--	
			<div class="register_title buyers_title">
				BUYERS
			</div>
		-->
			<div id="buyer_register" class="register_button buyers_button">
				Consumers<br />
				Join Here <br /> It's FREE.
			</div>
		</a>
		<a href="{$soc_https_host}registration.php{if $team}?team={$team}{/if}" class="register_row">
		
			<div class="register_icon">
				<img src="/skin/red/images/icons/icon_globe.gif" alt="lock icon" onclick="javascript:location.href='{$soc_https_host}soc.php?act=signon&attribute={$reg_attribute}';" />
			</div>
		<!--	
			<div class="register_title">
				Food & Wine <br /> Retailers <br /> + <br />Sellers
			</div>
		-->
			<div id="seller_register" class="register_button seller_button">
				All retailers<br />JOIN HERE
			</div>
		</a>
		
	</div>
	<img src="/skin/red/images/bg_login_newBottom.gif" alt="bottom" />
</div>