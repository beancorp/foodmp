<script src="{$smarty.const.STATIC_URL}/js/skin/red/jquery.validationEngine.js"></script>
<script src="{$smarty.const.STATIC_URL}/js/skin/red/jquery.validationEngine-en.js"></script>

<link type="text/css" href="{$smarty.const.STATIC_URL}/css/skin/red/jquery.tooltip.css" rel="stylesheet" />
<script type="text/javascript" src="{$smarty.const.STATIC_URL}/js/skin/red/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="{$smarty.const.STATIC_URL}/js/skin/red/jquery.tooltip.js"></script>
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
	#cuisine {{/literal}
	{if $req.subAttrib eq 1 || $req.subAttrib eq 8}
	{else}
		display: none;
	{/if}{literal}
	}
	
	#payment_box {
		width: 430px;
		border-radius: 10px;
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
		display: inline-block;
		height: 20px;
		vertical-align: bottom;
		width: 21px;
	}
	
	.field_display {
		color: #3C3380;
		padding: 5px;
		width: 277px;
	}
	
	.messageboxerror {
		color: #FF0000;
	}
	
	.messageboxok {
		color: #61B329;
	}
{/literal}
</style>
<script>
{literal}

var mailstatu = "success";
var webstatu = "success";
var bunamestatu = "success";
var nicknamestatu = "success";

function normal_form() {
	$('#own_website_question').hide();
	$('#registration_form').show();
	$('#normal_store').show();
	$('#own_website').val(0);
	$('#steps_2').hide();
	$('#steps').show();
}

function website_form() {
	$('#own_website_question').hide();
	$('#registration_form').show();
	$('#normal_store').hide();
	$('#website_url_box').show();
	$('#own_website').val(1);
	$('#steps').hide();
	$('#steps_2').show();
}

function selectSectionType(market, value) {
	if (market == 5) {
		if (value == 1 || value == 8) {
			$("#cuisine").css('display','block');
		} else { 
			$("#cuisine").css('display','none');
		}
		
		if (value == 1 || value == 7) {
			$('#website_url_box').show();
		} else {
			$('#website_url_box').hide();
		}
		normal_form();
	}
}

function changeUrl(url){
	newurl = url.replace(/[^\d\w]/g,'');
	newurl = newurl.replace(/_/g,'');
	if (newurl.length > 60){
		alert("The URL String must be less than 60 characters.\n");
	} else {
		document.getElementById("url").innerHTML = newurl + '.foodmp.com.au';
	}
}

function updateFields(value) {
	value = value.replace(/[^\d\w]/g,'');
	value = value.replace(/_/g,'');

	$('#bu_urlstring').val(value);
	
	changeUrl(value);
	checkWebsite();
	
	$('#bu_username').val(value);
	checkUsername();
	
	$('#bu_nickname').val(value);
	checkNickname();
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
				updateFields($("#"+Obj.id).val());
				
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

function checkNickname() {
	$("#msg_nickname").removeClass().addClass('messagebox').text('Checking...').fadeIn("slow");
	//check the username exists or not from ajax
	$.post("soc.php?act=signon&step=checkNicknameExist",{ bu_nickname:$("#bu_nickname").val() } ,function(data,textstatu)
	{
		nicknamestatu = textstatu;
		if(data=='existed') //if username not avaiable
		{
			$("#msg_nickname").fadeTo(200,0.1,function() //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('This Nickname is already taken.').addClass('messageboxerror').fadeTo(900,1);
			});
			$("#bu_nickname").val('');
		} else if(data=='empty') {
			$("#msg_nickname").fadeTo(200,0.1,function()  //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('Please enter a Nickname.').addClass('messageboxerror').fadeTo(900,1);
			});
		} else {
			$("#msg_nickname").fadeTo(200,0.1,function()  //start fading the messagebox
			{
				//add message and change the class of the box and start fading
				$(this).html('Nickname available.').addClass('messageboxok').fadeTo(900,1);
			});	
		}
	});
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
	if (!$('#registerForm').validationEngine('validate')) {
		return false;
	}
	if (document.registerForm.cp.value == 'next' || document.registerForm.cp.value == 'save') {	
		document.registerForm.action = '';
	}
	document.registerForm.submit();
}

$(document).ready(function() {
	$('#registerForm').validationEngine('attach');
	$('#registerForm .question').tooltip().css({opacity: 0.9});
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
		$('#business_type_selection').hide();
	{/literal}
	{else}
	{literal}
		normal_form();
	{/literal}
	{/if}
	{literal}
	{/literal}
	{if not $example_site}
	{literal}
	$('#bu_name').bind('blur', function(){bunamestatu='error';checkBuNameUnique(this);})
	{/literal}
	{/if}
	{literal}
});
{/literal}
{if $example_site}
{literal}
	$(document).ready(function() {
		$('#bu_user').change(function() {
			$('#re_bu_user').val($(this).val());
			$('#bu_username').val($(this).val());
		});	
	});
{/literal}
{/if}
</script>
<style>
{literal}
	.field_group {
		background-color: #efefef;
		border-radius: 10px;
		padding: 10px;
		margin-bottom: 10px;
	}
	
	#website_url_box {{/literal}
	{if ($req.subAttrib eq 1) || ($req.subAttrib eq 7) || ($req.subAttrib eq 9)}
	{else}
		display: none;
	{/if}{literal}
	}
	#own_website_question {
		display: none;
	}
{/literal}
</style>
<form method="post" name="registerForm" id="registerForm" onsubmit="return checkForm(this);">
	<input type="hidden" name="attribute" value="5" />
	<div id="step1_form">
		{if (!($isUpdate && $req.CustomerType eq 'listing' && $req.account_status eq 1))}
		
			<div id="business_type_selection" class="field_group" style="border: 3px solid #2b4470;">
				<div class="form_row">
					<div class="form_label" style="color: #2b4470; font-weight: bold;">
						Business Type *
					</div>
					<style>
						{literal}
						.category_selection label {
							display:block;
							padding:3px 0;
							float:left;
							width:140px;
						}
						.category_selection label input {
							margin-right: 5px;
						}
						{/literal}
					</style>
					<div class="form_field">
						<div style="margin-left: 5px; width: 280px;" class="category_selection">
							{foreach from=$lang.seller.attribute.5.subattrib item=l key=k}
								<label for="category{$k}"><input type="radio" id="category{$k}" class="validate[required]" name="subattr5" value="{$k}" {if $req.attribute eq '5' && $req.subAttrib eq $k || $req.subattr5 eq $k }checked{else}{if $example_site}{if $k eq 5}checked="checked"{/if}{/if}{/if} style="border:none" onclick="selectSectionType('5', '{$k}')"/>{$l}</label>
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
			.referral_code {
				border: 2px solid #D3202E;
				color: #D3202E;
				font-size: 12pt;
				font-weight: bold;
				text-align: center;
				width: 70px;
			}
			{/literal}
		</style>
		
		{if $example_site}
			<input type="hidden" name="example_site" value="1" />
		{/if}
		
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
						{$lang.labelEmail} 
						
						<div class="question" style="opacity: 0.9;">
							<div class="tooltip_description" style="display:none">
								Email Address cannot be changed.
							</div>
						</div>
						
						*
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
			
			{if (! ($isUpdate && $req.CustomerType eq 'listing'))} 
			<div id="normal_store">				
				<div class="field_group">
					<div class="form_row">
						<div class="form_label">
							{$lang.labelBusinessName} 
							
							<div class="question" style="opacity: 0.9;">
								<div class="tooltip_description" style="display:none">
									This is the name you create for yourself in FoodMarketplace.
								</div>
							</div>
							
							*
						</div>
						<div class="form_field">
							<input name="bu_name" type="text" {if $example_site}readonly="readonly"{/if} class="inputB validate[required]" id="bu_name" value="{if $example_site}The Great Australian Food Emporium{else}{$req.bu_name}{/if}" size="30" maxlength="96" />
							<div id="bu_name_clew">&nbsp;</div>
						</div>
					</div>
					
					<div class="form_row">
						<div class="form_label">
							{$lang.labelurlstring}
							
							<div class="question" style="opacity: 0.9;">
								<div class="tooltip_description" style="display:none">
									This is the URL you will create for yourself on Food Marketplace so that <br />people can visit your website directly from the internet.
								</div>
							</div>
							
							*
						</div>
						<div class="form_field">
							{if $userlevel == 2}
								<input name="bu_urlstring" type="text" class="inputB validate[required]" id="bu_urlstring" size="30" value="{$req.bu_urlstring}" onkeyup="changeUrl(this.value)" onblur="checkWebsite();" onfocus="javascript:webstatu='error';" maxlength="60"/>
							{else}
								<input name="bu_urlstring" type="text" class="inputB" id="bu_urlstring" {if $example_site}readonly="readonly"{/if} value="{if $example_site}ozfoodemporium{else}{$req.bu_urlstring}{/if}" size="30" {if not $example_site}onkeyup="changeUrl(this.value)" onblur="checkWebsite();"  onfocus="javascript:webstatu='error';"{/if} maxlength="60"/>
								<input type="hidden" name="bu_name_default" value="{$req.bu_urlstring}">
							{/if}
							<br />
							The URL String will automatically become your URL. <br />E.g. <span id="url" style="color: #0000FF">{if $example_site}ozfoodemporium{else}{$req.bu_urlstring}{/if}.{$smarty.const.SHORT_HOSTNAME}</span>
							<div id="msgbox1">&nbsp;</div>
						</div>
					</div>
				
					<div class="form_row">
						<div class="form_label">
							Store ID 
							
							<div class="question" style="opacity: 0.9;">
								<div class="tooltip_description" style="display:none">
									<ul style="margin: 0 0 0 1em;">
										<li>Store ID is unique for each store</li>
										<li>It cannot be changed once created</li>
										<li>Multiple ID's can be used with the same Email Address</li>
									</ul>
								</div>
							</div>
							
							*
						</div>
						<div class="form_field">
							<input name="bu_username" id="bu_username" type="text" class="inputB validate[required]" value="{$req.bu_username}" size="30" onblur="checkUsername();" onfocus="javascript:mailstatu='error';" />
							<div id="msgbox6">&nbsp;</div>
						</div>
					</div>
					<div class="form_row">
						<div class="form_label">
							{$lang.labelNickName} 
							
							<div class="question" style="opacity: 0.9;">
								<div class="tooltip_description" style="display:none">
									This will be visible to others on the website and can't be changed! 
								</div>
							</div>
						
							*
						</div>
						<div class="form_field">
							{if $isUpdate == false}
								<input name="bu_nickname" type="text" class="inputB validate[required]" id="bu_nickname" {if $example_site}readonly="readonly"{/if} value="{if $example_site}ozfoodemporium{else}{$req.bu_nickname}{/if}" size="30" {if not $example_site}onblur="checkNickname();" onfocus="javascript:nicknamestatu='error';"{/if} />
								<div id="msg_nickname">&nbsp;</div>
							{else}
								<div class="field_display">{$req.bu_nickname}</div>
								<input name="bu_nickname" type="hidden" class="inputB" id="bu_nickname" value="{$req.bu_nickname}" size="30" />
							{/if}
						</div>
					</div>
				</div>
			</div>
			{/if}
			<div class="field_group">
				<div class="form_row">
					<div class="form_label">
						{if $isUpdate == true}New {/if}{$lang.labelPassword} *
					</div>
					<div class="form_field">
						<input name="bu_password" type="{if $example_site}text{else}password{/if}" class="inputB {if $isUpdate == false}validate[required]{/if}" id="bu_password" value="{if $example_site}testing{/if}" size="30" />
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelRePassword} *
					</div>
					<div class="form_field">
						<input name="bu_password1" type="{if $example_site}text{else}password{/if}" class="inputB {if $isUpdate == false}validate[required]{/if}" id="bu_password1" value="{if $example_site}testing{/if}" size="30" />
					</div>
				</div>
			</div>
			
			<div class="field_group" id="website_url_box">
				<div class="form_row">
					<div class="form_label">
						Existing Website URL
					</div>
					<div class="form_field">
						<input name="bu_website" id="website_url" type="text" class="inputB validate[custom[url]]" value="{$req.bu_website}" size="30" />
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
			
			<div class="field_group">
				<div class="form_row">
					<div class="form_label">
						{$lang.labelAddress} *
					</div>
					<div class="form_field">
						<input type="text" class="inputB validate[required]" id="bu_address" name="bu_address" {if $example_site}readonly="readonly"{/if} value="{if $example_site}1 Martin Place{else}{$req.bu_address}{/if}"/>
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelState} *
					</div>
					<div class="form_field">
						<select name="bu_state" style="width: 200px;" class="select validate[required]" id="bu_state" value="{$req.bu_state}" {if not $example_site}onchange="javascript:selectSuburb('bu_suburbobj', document.registerForm.bu_state.options[document.registerForm.bu_state.options.selectedIndex].value);"{/if}>
							{if $example_site}<option value="5" selected="selected">New South Wales (NSW)</option>{else}{$req.State}{/if}
						</select>
						<input name="suburb" type="hidden" id="suburb" value="{$req.suburb}"/>
					</div>
				</div>
				<div class="form_row" id="select_suburb">
					<div class="form_label">
						{$lang.labelCity} *
					</div>
					<div class="form_field" id="bu_suburbobj">
						<select name="bu_suburb" style="width: 200px;" id="bu_suburb" class="select validate[required]" >
							{if $example_site}<option value="Sydney" selected="selected">Sydney</option>{else}{$req.Subburb}{/if}
						</select>
					</div>
				</div>
				<div class="form_row">
					<div class="form_label">
						{$lang.labelZIP} *
					</div>
					<div class="form_field">
						<input name="bu_postcode" type="text" class="inputB validate[required]" id="bu_postcode" {if $example_site}readonly="readonly"{/if} value="{if $example_site}2000{else}{$req.bu_postcode}{/if}" size="4" maxlength="{if $lang.labelZIP eq 'ZIP'}10{else}4{/if}" />
					</div>
				</div>
				<div class="form_row">
					<div style="float: left;">
						<div class="form_label">
							{$lang.labelPAC} *
						</div>
						<div class="form_field">
							<input name="bu_area" type="text" style="width: 50px" {if $example_site}readonly="readonly"{/if} class="inputB validate[required]" id="bu_area" value="{if $example_site}1122{else}{$req.bu_area}{/if}" size="5" maxlength="4" />
						</div>
					</div>
					<div style="float: left;">
						<div style="width: 60px;" class="form_label">
							{$lang.labelPhone} *
						</div>
						<div class="form_field">
							<input name="bu_phone" style="width: 143px;" type="text" {if $example_site}readonly="readonly"{/if} class="inputB validate[required]" id="bu_phone" value="{if $example_site}3344{else}{$req.bu_phone}{/if}" size="30" />
						</div>
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
						<input name="bu_fax" type="text" class="inputB" id="bu_fax" {if $example_site}readonly="readonly"{/if} value="{if $example_site}5566-7890{else}{$req.bu_fax}{/if}" size="30" />
					</div>
				</div>
			</div>
			
			<div class="form_row">
				<div class="form_label">
				
				</div>
				<div id="registration_agree_tnc" class="form_field">
					<input name="agree" type="checkbox" class="validate[required]" id="agree" value="1" {if $req.agree == 1 or $isUpdate } checked {/if} />
					I agree to the site <a href="soc.php?cp=terms" target="_blank">terms of use</a>. </b></td>
				</div>
			</div>
			
			<div class="form_row">
				<div id="payment_box">
					<input id="submit_button" src="/skin/red/images/bu-nextsave.gif" class="submit form-save" type="image">
					<input type="hidden" name="cp" value="next" />
					{if $team}
						<input type="hidden" name="team" value="{$team}" />
					{/if}
				</div>
			</div>
		</div>
	</div>
	<div id="registration_image_right" style="float: right; text-align: center;">
		<!--<a href="/moneyback" target="_blank"><img border="0" alt="" src="/images/money_back_badge.png" align="middle" /></a> <br />-->
	
		<img border="0" alt="" src="/skin/red/images/onedollaraday.jpg">
	</div>
</form>