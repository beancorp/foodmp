{if $req.task ne 'submit'}
{literal}
<script language="javascript">
<!--//
function checkForm()
{
	var errors 	= '';
	var flag 	= 1;
	var emailID = document.formcc.cu_username;
	clickSuburb();
	if(document.formcc.cu_name.value==''){
		errors += '- Name is required.\n';
	}
	if(emailID.value==''){
		errors += '- Email address is required.\n';
	}else if (echeck(emailID.value)==false){
		errors += '- Email address is not valid.\n';
	}else{
		if(document.getElementById('re_cu_username').value !=emailID.value){
			errors += '- The Email address you have entered did not match.\n';
		}
	}
	if(document.formcc.cu_nickname.value==''){
		errors += '- Nickname is required.\n';
	}
	if(document.formcc.cu_postcode.value==''){
		errors += '- Post code is required.\n';
	}
	if(document.formcc.cu_country.options[document.formcc.cu_country.selectedIndex].value != '{/literal}{$current_country}{literal}'){
		if (document.formcc.f_state.value == ''){
			errors += '- State is required.\n';
		}
		if (document.formcc.f_suburb.value == ''){
			errors += '- City / Suburb is required.\n';
		}
	}else{
		if(document.formcc.cu_state.value==''){
			errors += '- State is required.\n';
		}
		if(document.formcc.bu_suburb.value==''){
			errors += '- City/ Suburb is required.\n';
		}
	}
	if(document.formcc.cu_pass.value==''){
		errors += '- Password is required.\n';
	}else if(document.formcc.cu_pass.value != document.formcc.cu_pass1.value){
		errors += '- The passwords you have entered did not match.\n';
	}
	if (document.formcc.phone1.value==''||document.formcc.phone2.value==''){
		errors += '- Phone is required.\n';
	}else{
		document.formcc.phone.value=document.formcc.phone1.value+'-'+document.formcc.phone2.value;
	}

	if(!document.formcc.agree.checked){
		errors += '- You must agree to the terms.\n';
	}
	if(errors!=''){
		errors = 'Sorry, The following fields are required.\n'+errors;
		alert(errors);
		return false;
	}
	return true;
}

function echeck(str)
{
	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if (str.indexOf(at)==-1){
		return false
	}
	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		return false
	}
	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		return false
	}
	if (str.indexOf(at,(lat+1))!=-1){
		return false
	}
	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		return false
	}
	if (str.indexOf(dot,(lat+2))==-1){
		return false
	}
	if (str.indexOf(" ")!=-1){
		return false
	}
	return true;
}

function selectSuburb(obj,params)
{
	try{
		$.get("soc.php",
			  { act: "signon",
			    step: "suburb",
				SID: params
			  },
  			  function(data){
				$('#'+obj).html(data);
  		});
	}
	catch(ex)
	{
		//alert(ex);
	}
}

function selectZip(obj){
	var strTemp	=obj.options[obj.selectedIndex].text;

	if(strTemp != 'Select Suburb'){
		var arrTemp = strTemp.split(',');
		//document.getElementById('cu_postcode').value = arrTemp[1].replace(/(^\s)|($\s)/g,'');
	}else{
		//document.getElementById('cu_postcode').value = '';
	}
}

function clickSuburb()
{
	if($('#suburb').val() != ''){
		document.getElementById("suburb").value = document.getElementById("cu_suburb").value;
	}
}
function checkCountry(country){
	if (country == '{/literal}{$current_country}{literal}'){
		document.getElementById("fstate").style.display = 'none';
		document.getElementById("fsuburb").style.display = 'none';
		document.getElementById("custate").style.display = '';
		document.getElementById("cusuburb").style.display = '';
	}else{
		document.getElementById("fstate").style.display = '';
		document.getElementById("fsuburb").style.display = '';
		document.getElementById("custate").style.display = 'none';
		document.getElementById("cusuburb").style.display = 'none';
	}
}
//-->
</script>
{/literal}

{if $req.msg ne ''}<div style="color:red; padding:0 0 18px 0;">{$req.msg}</div>{/if}

{$req.content1}

<form action="" method="post" name="formcc" id="signupform" onsubmit="return checkForm()" style="height:330px; position: relative;">
<input type="hidden" id="fb_id" name="fb_id" value="" />
<h1 class="h-signupnow"><span>Sign up now</span></h1>
<div style="left: 266px; position: absolute; top: 18px; width: auto;">
<div id="fb-login-button" class="fb-login-button" data-scope="email,user_checkins">Register with facebook</div>
</div>
<fieldset>
	<ol>
		<li><label>{$lang.labelName}*</label><input name="cu_name" id="cu_name" type="text" class="text" value="{$req.cu_name}"/></li>
		<li><label>{$lang.labelNickName}*</label><input name="cu_nickname" id="cu_nickname" type="text" class="text" value="{$req.cu_nickname}"/></li>



		<li class="half"><label>{$lang.labelCountry}*</label><select id="country" onchange="checkCountry(this.value)" name="cu_country" class="text" >{$req.countrylist}</select></li>
		<li id="custate" class="half"><label>{$lang.labelState}*</label><select name="cu_state" onchange="selectSuburb('bu_suburbobj', this.value);" class='text'>
        {$req.statelist}</select></li>
		<li id="cusuburb" class="half"><label>{$lang.labelCity}*</label>
		  <div id="bu_suburbobj">
		    <select id="cu_suburb" name="bu_suburb" >
		      {$req.suburblist}
	        </select>
	      </div>
		</li>


		<li id="fstate" style="display:none" class="half"><label>{$lang.labelState}*</label> <input name="f_state" type=text style="width:110px;+height:13px;" class='text'></li>
		<li id="fsuburb" style="display:none" class="half"><label>{$lang.labelCity}*</label> <input name="f_suburb" type="text" style="width:110px;+height:13px;" class='text'></li>
<li class="half"><label>{$lang.labelZIP}*</label><input id="cu_postcode" name="cu_postcode" type="text" style="width:110px;" class="text" value="{$req.cuPostCode}" /></li>


		<li class="clear"><label>{$lang.labelEmail}* (<span style="color:#F11F44;">Cannot be changed</span>)</label><input name="cu_username" type="text" class="text" id="cu_username" value="{$req.userNameC}" /></li>
        <li><label>Re-enter {$lang.labelEmail}*</label><input id="re_cu_username" type="text"  value="{$req.userNameC}" class="text"/></li>
		<li class="clear"><label>{$lang.labelPassword}*</label><input name="cu_pass" type="password"  value="{$req.PasswordC}" class="text"/></li>
        <li><label>{$lang.labelRePassword}*</label><input name="cu_pass1" type="password"  value="{$req.PasswordC}" class="text"/></li>
        <li class="phone"><label>Phone*</label><input name="phone1" type="text"  value="{$req.phone1}" size="4" maxlength="12" /> - <input name="phone2" type="text"  value="{$req.phone2}" size="12" maxlength="19" /></li>
	</ol>
    <input type="hidden" name="phone" value="" />
</fieldset>

<fieldset class="submit">
	<ol>
		<li><input id="agree" name="agree" type="checkbox" class="checkbox" value="1" style="margin-top:4px;+margin:0;" /><label>I agree to the site <a href="soc.php?cp=terms" target="_blank">Terms of use</a></label></li>
		<li class="right"><input type="image" class="register" src="skin/red/images/bu-register.gif" /></li>
		<li style="width:500px;color:#F11F44;">Nickname will be visible to others on the website and is set in stone!</li>
	</ol>
</fieldset>

<input name="suburb" type="hidden" id="suburb" value="" />
<input name="ctm" type="hidden" id="ctm" value="{$req.ctm}"/>

</form>

<a href="soc.php?cp=protection" class="arrow">Your Security</a>

{else}
{literal}

<!-- Google Code for Buyer Conversion Page -->
<script language="JavaScript" type="text/javascript">
<!--
var google_conversion_id = 1055688874;
var google_conversion_language = "en_US";
var google_conversion_format = "1";
var google_conversion_color = "ffffff";
var google_conversion_label = "MybBCP6JVxCqkbL3Aw";
if (50.0) {
 var google_conversion_value = 50.0;
}
//-->
</script>
<script language="JavaScript"
src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<img height="1" width="1" border="0"
src="https://www.googleadservices.com/pagead/conversion/1055688874/?value=50.0&label=MybBCP6JVxCqkbL3Aw&script=0"/>
</noscript>

{/literal}

<div>
	{*$req.msg*}
	<p><a href="soc.php?cp=category" class="style1" ><strong> Start browsing Food Marketplace... </strong></a></p>
        <p>Congratulations & Welcome to Food Marketplace. </p>
        <p>Shop online or subscribe to Email Alerts from your local Food & Wine retailers.</p>
        <p>If your local Food & Wine retailers have not yet opened a store on Food Marketplace let them know that you are a member. Food Marketplace is so easy & inexpensive, your local Food & Wine retailers now have every opportunity to connect with you online.</p>
        <p>You also have unlimited use of our Auctions marketplace, it's FREE to list.</p>

</div>

{/if}