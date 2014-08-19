<script src="/skin/red/js/jquery-1.8.3.min.js"></script>
<script src="/skin/red/js/jquery.validationEngine.js"></script>
<script src="/skin/red/js/jquery.validationEngine-en.js"></script>

<link type="text/css" href="/skin/red/css/jquery.tooltip.css" rel="stylesheet" />
<script type="text/javascript" src="/skin/red/js/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery.tooltip.js"></script>
<style>
{literal}
	#step1_form {
		width: 540px;
		float: left;
		margin-bottom: 100px;
	}
	.form_row {
		overflow: hidden;
		margin-top: 10px;
	}
	.form_label {
		float: left;
		text-align: right;
		margin-right: 10px;
		width: 150px;
		padding-top: 5px;
	}
	.form_field {
		float: left;
	}
	#cuisine {
		display: none;
	}
	
	#payment_box {
		width: 430px;
		border-radius: 10px;
		background-color: #f1f1f1;
		text-align: center;
		padding: 20px;
		margin-left: 50px;
		overflow: hidden;
	}
	
	#eway_logo {
		margin-left: 45px;
	}
	
	#logo_image {
		float: left;
		margin-right: 10px;
	}
	
	#eway_text {
		padding-top: 10px;
	}
	
	.question {
		background-image: url("/skin/red/images/icon-question.gif");
		background-repeat: no-repeat;
		cursor: pointer;
		float: left;
		height: 20px;
		width: 21px;
		margin-left: 10px;
	}
	
	#select_suburb {
		display: none;
	}
	
	.field_display {
		color: #3C3380;
		padding: 5px;
		width: 277px;
	}
{/literal}
</style>
<script>
{literal}

var mailstatu = "success";
var webstatu = "success";
var bunamestatu = 'success';

function normal_form() {
	$('#own_website_question').hide();
	$('#registration_form').show();
	$('#normal_store').show();
	$('#payment_text_normal').show();
	$('#payment_text_other').hide();
	$('#website_url_box').hide();
	$('#own_website').val(0);
	$('#steps_2').hide();
	$('#steps').show();
}

function website_form() {
	$('#own_website_question').hide();
	$('#registration_form').show();
	$('#normal_store').hide();
	$('#payment_text_normal').hide();
	$('#payment_text_other').show();
	$('#website_url_box').show();
	$('#own_website').val(1);
	$('#steps').hide();
	$('#steps_2').show();
}

function selectSectionType(market, value) {
	if (market == 5) {
		if (value == 1) {
			$("#cuisine").css('display','block');
		} else { 
			$("#cuisine").css('display','none');
		}
		
		if (value == 1 || value == 7 || value == 9) {
			$('#registration_form').hide();
			$('#own_website_question').show();
		} else {
			normal_form();
		}
	}
}

function changeUrl(url){
	newurl = url.replace(/[^\d\w]/g,'');
	newurl = newurl.replace(/_/g,'');
	if (newurl.length > 60){
		alert("The URL String must be less than 60 characters.\n");
	} else {
		document.getElementById("url").innerHTML = newurl + {/literal}'.{$smarty.const.SHORT_HOSTNAME}'{literal};
	}
}

function checkBuNameUnique(Obj){
	ObjClewID = "#"+ Obj.id + "_clew";
	$(ObjClewID).removeClass().addClass('messagebox').text('Checking...').fadeIn("slow");
	
	$.post("soc.php?act=signon&step=checkBunameUnique",{ bu_name : $("#"+Obj.id).val() } ,function(data,textstatu)
	{
	  if(textstatu == 'success'){
		if(data=='existed')
			{
				$(ObjClewID).fadeTo(200,0.1,function() {
					$(this).html('This Website Name is invalid or exists.').addClass('messageboxerror').fadeTo(900,1);
				});
				$("#bu_name").val('');
			} else if(data=='empty') {
				$(ObjClewID).fadeTo(200,0.1,function() {
					$(this).html('Please enter Website Name to register.').addClass('messageboxerror').fadeTo(900,1);
				});
			} else {
				$(ObjClewID).fadeTo(200,0.1,function(){
					$(this).html('Website Name available to register.').addClass('messageboxok').fadeTo(900,1);
				});	
			}
		  }
		bunamestatu = textstatu;
	});
	
}

function selectSuburb(obj,params) {
	$('#select_suburb').show();
	$('#bu_suburbobj').html('<img src="/skin/red/images/preloader.gif" width="20px" />');
	try{
		ajaxLoadPage('soc.php','&act=signon&step=suburb&SID='+params,'GET',document.getElementById(obj),false,false,true);
	}
	catch(ex)
	{}
}
 
function selectZip(obj){
	var strTemp	=obj.options[obj.selectedIndex].text;
	if(strTemp != 'Select City'){
		var arrTemp = strTemp.split(',');
		document.getElementById('bu_postcode').value = arrTemp[1].replace(/(^\s)|($\s)/g,'');
	}else{
		document.getElementById('bu_postcode').value = '';
	}
}

function ifEmail(str,allowNull){
	if(str.length==0) return allowNull;
	i=str.indexOf("@");
	j=str.lastIndexOf(".");
	if (i == -1 || j == -1 || i > j) return false;
	return true;
}

function checkEmail() {
	$("#msgbox").removeClass().addClass('messagebox').text('Checking...').fadeIn("slow");
	$.post("soc.php?act=signon&step=checkEmailExist",{ bu_user:$("#bu_user").val(),attribute:$("input[name='attribute']:checked").val() } ,function(data,textstatu) {
		mailstatu = textstatu;
		if(data=='invalid' || data=='existed') //if username not avaiable
		{
			$("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('This Email Address is invalid or exists.').addClass('messageboxerror').fadeTo(100,1);
				$('#re_bu_user').val('');
			});
			$("#bu_user").val('');
		} else if(data=='empty') {
			$("#msgbox").fadeTo(200,1,function()  //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('Please enter a email to register.').addClass('messageboxerror').fadeTo(100,1);
				$('#re_bu_user').val('');
			});
		} else {
			$("#msgbox").fadeTo(200,1,function()  //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('Email available to register.').addClass('messageboxok').fadeTo(100,1);
			});	
		}
	});
}

function checkEmailIfExists(){
	if ($("#bu_user").val()!=''){
		checkEmail();
	}
}

function checkrefid(){
	$('#referrer_tips').show();
	$("#msgbox5").removeClass().text('Checking...').fadeIn("slow");
	$.post("/include/jquery_svr.php?svr=check_RefID",{ refID:$("#referrer").val() } ,function(data,textstatu){if(data=='0'){
		$("#msgbox5").fadeTo(200,0.1,function(){
				$(this).html('Invalid Referrer ID.').removeClass().addClass('errormsg').fadeTo(900,1);
		});
		$("#referrer").val('');
		$("#referrer").removeAttr('readonly');
	}else{
		$("#msgbox5").fadeTo(200,0.1,function(){
				$(this).html(data).removeClass().addClass('sucmsg').fadeTo(900,1);
		});
	}});
}

function checkWebsite() {
	$("#msgbox1").removeClass().addClass('messagebox').text('Checking...').fadeIn("slow");
	//check the username exists or not from ajax
	$.post("soc.php?act=signon&step=checkWebsiteExist",{ bu_name:$("#bu_urlstring").val() } ,function(data,textstatu)
	{
		webstatu = textstatu;
		if(data=='existed') //if username not avaiable
		{
			$("#msgbox1").fadeTo(200,0.1,function() //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('This URL string has already been used. Please create another.').addClass('messageboxerror').fadeTo(900,1);
			});
			$("#bu_urlstring").val('');
		} else if(data=='empty') {
			$("#msgbox1").fadeTo(200,0.1,function()  //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('Please enter a URL String.').addClass('messageboxerror').fadeTo(900,1);
			});
		} else {
			$("#msgbox1").fadeTo(200,0.1,function()  //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('URL String available to register.').addClass('messageboxok').fadeTo(900,1);
			});	
		}

	});
}


function checkUsername()
{
	//remove all the class add the messagebox classes and start fading
	$("#msgbox6").removeClass().addClass('messagebox').text('Checking...').fadeIn("slow");
	//check the username exists or not from ajax
	$.post("soc.php?act=signon&step=checkUsernameExist",{ bu_username:$("#bu_username").val() } ,function(data,textstatu)
	{
		webstatu = textstatu;
		if(data=='existed') //if username not avaiable
		{
			$("#msgbox6").fadeTo(200,0.1,function() //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('This Store ID has already been used. Please create another.').addClass('messageboxerror').fadeTo(900,1);
			});
			$("#bu_username").val('');
		} else if(data=='empty') {
			$("#msgbox6").fadeTo(200,0.1,function()  //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('Please enter a Store ID.').addClass('messageboxerror').fadeTo(900,1);
			});
		} else if(data=='invalid') {
			$("#msgbox6").fadeTo(200,0.1,function()  //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('This Store ID is invalid.').addClass('messageboxerror').fadeTo(900,1);
			});				
			$("#bu_username").val('');
		} else {
			$("#msgbox6").fadeTo(200,0.1,function()  //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('Store ID available to register.').addClass('messageboxok').fadeTo(900,1);
			});	
		}

	});
}

function checkForm(obj){
	if (!$('#mainForm').validationEngine('validate')) {
		return false;
	}
	if (document.mainForm.cp.value == 'next' || document.mainForm.cp.value == 'save') {	
		document.mainForm.action = '';
	}
	document.mainForm.submit();
}

$(document).ready(function() {
	$('#mainForm').validationEngine('attach');
	$('.question').tooltip().css({opacity: 0.9});
	$('#own_website_yes').click(function() {
		website_form();
	});
	$('#own_website_no').click(function() {
		normal_form();
	});
	{/literal}
	{if $session.level eq 3}
	{literal}
		website_form();
	{/literal}
	{else}
	{literal}
		normal_form();
	{/literal}
	{/if}
	{literal}
	
});

{/literal}
</script>
<style>
{literal}
	.field_group {
		background-color: #efefef;
		border-radius: 10px;
		padding: 10px;
		margin-bottom: 10px;
	}
	#submit_button {
		background-color: #FF8F05;
		background-image: -moz-linear-gradient(center bottom , #DE600B 30%, #FF8F05 65%);
		border: 0 none !important;
		border-radius: 10px 10px 10px 10px;
		color: #FFFFFF;
		cursor: pointer;
		display: inline-block;
		float: left;
		font-size: 12pt !important;
		font-weight: bold;
		margin-left: 10px;
		padding: 5px;
		text-align: center;
		text-decoration: none;
		width: 120px;
		margin-left: 150px;
	}
	#payment_text_normal {
		margin-bottom: 10px;
	}
	#payment_text_other {
		display: none;
		margin-bottom: 10px;
	}
	#website_url_box {
		display: none;
	}
	#own_website_question {
		display: none;
	}
{/literal}
</style>
<form method="post" name="mainForm" id="mainForm" action="{if $payment_method eq 'eway'}{$ewayInfo.eway_url}{/if}"  onsubmit="return checkForm(this);">
	<input type="hidden" name="attribute" value="5" />
	<div id="step1_form">
		{if $isUpdate == false}
			<div class="field_group">
				<div class="form_row">
					<div class="form_label">
					</div>
					<style>
						{literal}
						.category_selection span {
							display:block;
							padding:3px 0;
							float:left;
							width:140px;
						}
						.category_selection span input {
							margin-right: 5px;
						}
						{/literal}
					</style>
					<div class="form_field">
						<div style="width: 280px;" class="category_selection">
							{foreach from=$lang.seller.attribute.5.subattrib item=l key=k}
								<span><input type="radio" class="validate[required]" name="subattr5" value="{$k}" {if $req.attribute eq '5' && $req.subAttrib eq $k || $req.subattr5 eq $k }checked{/if} style="border:none" onclick="selectSectionType('5', '{$k}')"/>{$l}</span>
							{/foreach}
						</div>
					</div>
				</div>
			</div>
		{/if}
		<style>
			{literal}
			#own_website_question {
				background-color: #EFEFEF;
				border-radius: 10px 10px 10px 10px;
				margin-bottom: 10px;
				padding: 10px;
				overflow: hidden;
			}
			#own_website_question_title {
				font-weight: bold;
				font-size: 12pt;
				margin-bottom: 10px;
			}
			#own_website_question_buttons {
				width: 210px;
				margin-left: auto;
				margin-right: auto;
			}
			#own_website_yes {
				background-color: #FCCD5E;
				border-radius: 10px;
				padding: 10px;
				width: 70px;
				float: left;
				text-align: center;
				font-weight: bold;
				cursor: pointer;
			}
			#own_website_no {
				background-color: #ACCEEB;
				border-radius: 10px;
				padding: 10px;
				width: 70px;
				float: right;
				text-align: center;
				font-weight: bold;
				cursor: pointer;
			}
			{/literal}
		</style>
		
		<div id="own_website_question">
			<div id="own_website_question_title">
				Do you own a website?
			</div>
			<div id="own_website_question_buttons">
				<div id="own_website_yes" class="own_website">YES</div><div id="own_website_no" class="own_website">NO</div>
			</div>
			<input type="hidden" name="own_website" id="own_website" value="0" />
		</div>
		
		<div id="registration_form">
			<div class="field_group" id="cuisine">
				<div class="form_row">
					<div class="form_label">
						{$lang.labelCuisine} *
					</div>
					<div class="form_field">
						<select name="bu_cuisine" id="bu_cuisine" class="select" >
							<option value="">Select Cuisine</option>
							{foreach from=$req.cuisine_list item=l key=k}
								<option value="{$l.cuisineID}" {if $req.bu_cuisine eq $l.cuisineID}selected{/if}>{$l.cuisineName}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
			
			<div class="field_group">
				<div class="form_row">
					<div class="form_label">
						{$lang.labelEmail} *
					</div>
					<div class="form_field">
						{if $isUpdate == false}
							<input name="bu_user" id="bu_user" type="text" class="inputB validate[required,custom[email]]" value="{$req.bu_user}" size="30" onblur="checkEmail();" onfocus="javascript:mailstatu='error';" />
							<div id="msgbox">&nbsp;</div>
						{else}
							<div class="field_display">{$req.bu_user}</div>
							<input type="hidden" name="bu_user" value="{$req.bu_user}" id="bu_user" />
						{/if}
					</div>
					<div class="question" style="opacity: 0.9;">
						<div class="tooltip_description" style="display:none">
							Email Address cannot be changed.
						</div>
					</div>
				</div>
				{if $isUpdate == false}
				<div class="form_row">
					<div class="form_label">
						Re-enter {$lang.labelEmail} *
					</div>
					<div class="form_field">
						<input name="re_bu_user" id="re_bu_user" type="text" class="inputB validate[required,custom[email]]" value="{$req.re_bu_user}" size="30" />
					</div>
				</div>
				{/if}
			</div>
			
			<div class="field_group" id="website_url_box">
				<div class="form_row">
					<div class="form_label">
						Business Name *
					</div>
					<div class="form_field">
						<input name="business_name" type="text" class="inputB validate[required]" value="{$req.bu_name}" size="30" />
					</div>
					<div class="question" style="opacity: 0.9;">
						<div class="tooltip_description" style="display:none">
							Your Business Name
						</div>
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						Website URL *
					</div>
					<div class="form_field">
						<input name="bu_website" id="website_url" type="text" class="inputB validate[required,custom[url]]" value="{$req.bu_website}" size="30" />
					</div>
					<div class="question" style="opacity: 0.9;">
						<div class="tooltip_description" style="display:none">
							Your website URL goes here
						</div>
					</div>
				</div>
				<script>
					{literal}
					$('#website_url').change(function() {
						var website = "http://" + $(this).val().replace("http://", "");
						$(this).val(website);
					});
					{/literal}
				</script>
			</div>
			<div id="normal_store">				
				<div class="field_group">
					<div class="form_row">
						<div class="form_label">
							Store ID *
						</div>
						<div class="form_field">
							<input name="bu_username" id="bu_username" type="text" class="inputB validate[required]" value="{$req.bu_username}" size="30" onblur="checkUsername();" onfocus="javascript:mailstatu='error';" />
							<div id="msgbox6">&nbsp;</div>
						</div>
						<div class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">
								<ul>
									<li>Store ID is unique for each store</li>
									<li>It cannot be changed once created</li>
									<li>Multiple ID's can be used with the same Email Address</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="form_row">
						<div class="form_label">
							{$lang.labelNickName} *
						</div>
						<div class="form_field">
							{if $isUpdate == false}
								<input name="bu_nickname" type="text" class="inputB validate[required]" id="bu_nickname" value="{$req.bu_nickname}" size="30" />
							{else}
								<div class="field_display">{$req.bu_nickname}</div>
								<input name="bu_nickname" type="hidden" class="inputB" id="bu_nickname" value="{$req.bu_nickname}" size="30" />
							{/if}
						</div>
						<div class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">
								This will be visible to others on the website and can't be changed! 
							</div>
						</div>
					</div>
					<div class="form_row">
						<div class="form_label">
							{$lang.labelBusinessName} *
						</div>
						<div class="form_field">
							<input name="bu_name" type="text" class="inputB validate[required]" id="bu_name" value="{$req.bu_name}" size="30" maxlength="60" />
						</div>
						<div class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">
								This is the name you create for yourself in FoodMarketplace.
							</div>
						</div>
					</div>
					<div class="form_row">
						<div class="form_label">
							{$lang.labelurlstring} *
						</div>
						<div class="form_field">
							{if $userlevel == 2}
								<input name="bu_urlstring" type="text" class="inputB validate[required]" id="bu_urlstring" size="30" value="{$req.bu_urlstring}" onkeyup="changeUrl(this.value)" onblur="checkWebsite();" onfocus="javascript:webstatu='error';" maxlength="60"/>
							{else}
								<input name="bu_urlstring" type="text" class="inputB" id="bu_urlstring" value="{$req.bu_urlstring}" size="30" onkeyup="changeUrl(this.value)" onblur="checkWebsite();"  onfocus="javascript:webstatu='error';" maxlength="60"/>
								<input type="hidden" name="bu_name_default" value="{$req.bu_urlstring}">
							{/if}
							<br />
							The URL String will automatically become your URL. <br />E.g. <span id="url" style="color: #0000FF">{$req.bu_urlstring}.{$smarty.const.SHORT_HOSTNAME}</span>
							<div id="msgbox1">&nbsp;</div>
						</div>
						<div class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">
								This is the URL you will create for yourself on Food Marketplace so that <br />people can visit your website directly from the internet.
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="field_group">
				<div class="form_row">
					<div class="form_label">
						{$lang.labelPassword} *
					</div>
					<div class="form_field">
						<input name="bu_password" type="password" class="inputB validate[required]" id="bu_password" value="{$req.bu_password}" size="30" />
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelRePassword} *
					</div>
					<div class="form_field">
						<input name="bu_password1" type="password" class="inputB validate[required]" id="bu_password1" value="{$req.bu_password1}" size="30" />
					</div>
				</div>
			</div>
			
			<div class="field_group">
				<div class="form_row">
					<div class="form_label">
						{$lang.labelAddress} *
					</div>
					<div class="form_field">
						<input type="text" class="inputB validate[required]" id="bu_address" name="bu_address" value="{$req.bu_address}"/>
						<input name="address_hide" type="checkbox" id="address_hide" value="1" {if $req.address_hide eq '1' } checked {/if}/> <span style="font-size:11px">Hide</span>
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelState} *
					</div>
					<div class="form_field">
						<select name="bu_state" style="width: 200px;" class="select validate[required]" id="bu_state" value="{$req.bu_state}" onchange="javascript:selectSuburb('bu_suburbobj', document.mainForm.bu_state.options[document.mainForm.bu_state.options.selectedIndex].value);" onfocus="document.getElementById('bu_postcode').value = '';">
							{$req.State}
						</select>
						<input name="suburb" type="hidden" id="suburb" value="{$req.suburb}"/>
					</div>
				</div>
				<div class="form_row" id="select_suburb">
					<div class="form_label">
						{$lang.labelCity} *
					</div>
					<div class="form_field" id="bu_suburbobj">
						<select name="bu_suburb" id="bu_suburb" class="select validate[required]" >
							{$req.Subburb}
						</select>
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelZIP} *
					</div>
					<div class="form_field">
						<input name="bu_postcode" type="text" class="inputB validate[required]" id="bu_postcode" value="{$req.bu_postcode}" size="4" maxlength="4" />
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelPAC} *
					</div>
					<div class="form_field">
						<input name="bu_area" type="text" style="width: 50px" class="inputB validate[required]" id="bu_area" value="{$req.bu_area}" size="5" maxlength="4" />
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelPhone} *
					</div>
					<div class="form_field">
						<input name="bu_phone" type="text" class="inputB validate[required]" id="bu_phone" value="{$req.bu_phone}" size="30" />
						<input name="phone_hide" type="checkbox" id="phone_hide" value="1" {if $req.phone_hide eq '1' } checked {/if}/>
						<span style="font-size:11px">Hide</span> 
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelMobile}
					</div>
					<div class="form_field">
						<input name="mobile" class="inputB" id="mobile" value="{$req.mobile}" maxlength="25">
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelContact} *
					</div>
					<div class="form_field">
						<select style="width: 200px;" name="contact" id="contact" class="select">
							{foreach from=$lang.Contact item=cl}
								<option value="{$cl}" {if $cl eq $req.contact}selected{/if}>{$cl}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelFax}
					</div>
					<div class="form_field">
						<input name="bu_fax" type="text" class="inputB" id="bu_fax" value="{$req.bu_fax}" size="30" />
					</div>
				</div>
				{if $isUpdate eq false}
				<div class="form_row">
					<div class="form_label">
						{$lang.labelreferrer}
					</div>
					<div class="form_field">
						{if $isUpdate == false} 
							<input name="referrer" type="text" class="inputB" id="referrer" value="{$req.referrer}" size="30" onblur="checkrefid();" {if $req.referrer neq ''} readonly="readonly"{/if}/>
						{/if}
					</div>
				</div>
				{elseif $req.ref_name neq ''}
				<div class="form_row">
					<div class="form_label">
						{$lang.labelreferrer}
					</div>
					<div class="form_field">
						<div class="field_display">{$req.ref_name}</div><input name="referrer" type="hidden" class="inputB" id="referrer" value="{$req.referrer}" size="30" />
					</div>
				</div>
				{/if}
			</div>
			
			<div class="form_row">
				<div class="form_label">
				
				</div>
				<div class="form_field">
					<input name="agree" type="checkbox" class="validate[required]" id="agree" value="1" {if $req.agree==1 } checked {/if} />
					I agree to the site <a href="soc.php?cp=terms" target="_blank">terms of use</a>. </b></td>
				</div>
			</div>
			
			<div class="form_row">
				<div id="payment_box">
					{if $isUpdate == false}
						<div id="payment_text_normal">
							<font style="font-weight:normal;font-size:18px;">Open your website now for </font>
							<font style="font-size:26px;color:#F11F44">
							just
							<b style="font-size:26px;color:#F11F44;font-weight:bold;">$365</b>
							per year
							</font>
							<br />
							$1 x 365 days = $365 flat rate yearly subscription! <br />
							<font style="font-size:10px;">{$lang.SalesTax} to be added.</font>
						</div>
						<div id="payment_text_other">
							<font style="font-weight:normal;font-size:18px;">Drive traffic to your website now for  </font>
							<font style="font-size:26px;color:#F11F44">
							just
							<b style="font-size:26px;color:#F11F44;font-weight:bold;">$200</b>
							</font>
							<br />
							$0.55cents x 365 days = $200 flat rate yearly subscription. <br />
							<font style="font-size:10px;">{$lang.SalesTax} to be added.</font>
						</div>
						<input type="submit" value="Next" id="submit_button" />
						<input type="hidden" name="cp" value="payment" />
					{else}
						<input type="submit" value="Next" id="submit_button" />
						<input type="hidden" name="cp" value="next" />
					{/if}
				</div>
			</div>
			
			<div class="form_row">
				<div id="eway_logo">
					<div id="logo_image">
						<img src="/skin/red/images/eway-logo.gif">
					</div>
					<div id="eway_text">
						<small style="font-size:10px;">You will be taken to eWay for payment. Please make sure you return back to <br /> FoodMarketplace from the payment confirmation page on eWay.</small>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="float: right;">
		<img border="0" alt="" src="/skin/red/images/onedollaraday.jpg">
	</div>
</form>