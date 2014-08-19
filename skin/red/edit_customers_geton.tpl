{literal}
<script language="javascript">
function checkForm(){

	var errors 	=	'';

	var flag 	=	1;
	clickSuburb();
	
	var emailID=document.formcc.cu_username;
	if(document.formcc.cu_name.value==''){
		errors += ' - Name is required.\n';
	}

	if(document.formcc.cu_nickname.value==''){
		errors += '- Nickname is required.\n';
	}

	if(document.formcc.cu_postcode.value==''){
		errors += '- Post code is required.\n';
	}
	if(document.formcc.cu_country.options[document.formcc.cu_country.selectedIndex].value != '{/literal}{$current_country}{literal}'){
		if (document.formcc.fstate.value == ''){
			errors += '- State is required.\n';
		}
		if (document.formcc.fsuburb.value == ''){
			errors += '- City / Suburb is required.\n';
		}
	}
	
	if(document.formcc.cu_pass.value!=''){
		if(document.formcc.re_pass.value==''){
			errors += '- Re-enter Password is required.\n';
		}
		if(document.formcc.cu_pass.value!=document.formcc.re_pass.value){
			errors += '- Pasword/Re-Password does not match.\n';
		}
	}
	if (document.formcc.phone1.value==''||document.formcc.phone2.value==''){
		errors += '- Phone is required.\n';
	}else{
		document.formcc.phone.value=document.formcc.phone1.value+'-'+document.formcc.phone2.value;
	}

	if(!document.formcc.agree.checked){
		errors += '-  You must agree to the terms.\n';
	}

	if(errors!=''){

		errors = '-  Sorry, The following fields are required.\n'+errors;

		alert(errors);

		return false;

	}

	return true;

}

function echeck(str) {
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
		ajaxLoadPage('soc.php','&act=signon&step=suburb&SID='+params,'GET',document.getElementById(obj));
		document.getElementById('cu_postcode').value = "";
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
		document.getElementById('cu_postcode').value = arrTemp[1].replace(/(^\s)|($\s)/g,'');
	}else{
		document.getElementById('cu_postcode').value = '';
	}
}

function clickSuburb()
{
	document.getElementById("suburb").value = document.getElementById("bu_suburb").value;
}
function checkCountry(country){
	if (country == '{/literal}{$current_country}{literal}'){
		document.getElementById("fstate").style.display = 'none';
		document.getElementById("fsuburb").style.display = 'none';
		document.getElementById("cstate").style.display = '';
		document.getElementById("bu_suburbobj").style.display = '';
	}else{
		document.getElementById("fstate").style.display = '';
		document.getElementById("fsuburb").style.display = '';
		document.getElementById("cstate").style.display = 'none';
		document.getElementById("bu_suburbobj").style.display = 'none';
        $('#fstate input').val('');
        $('#fsuburb input').val('');
	}
}


</script>
<style>
	.field {
		padding-left: 10px;
	}
</style>
{/literal}					  
					  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td align="center">&nbsp;</td>
                        </tr>
						<form action="updateCustomer.php" method="post" name="formcc" id="formcc" onsubmit="return checkForm()">
                        <input type="hidden" name="phone" value="{$req.phone}" />
                          <tr>
                            <td>
                              <div id="formcc">
                                <table width="85%" align="center" cellpadding="2" cellspacing="5" bgcolor="#FFFFEA" class="bdr">
                                  {if $req.msg ne ''}
                                  <tr align="center" bgcolor="#FFFFEA">

                                    <td height="20" colspan="3"  class="style1">
                                      {$req.msg}
                                      </td>
                                  </tr>
                                  {/if}
                                  <tr align="center" bgcolor="#F8F8F8">
                                    <td height="20" colspan="3"><h1><strong>Update Your Details </strong></h1></td>
                                  </tr>
                                  <tr>
                                    <td align="right" height="28">Name<font color=red>*</font>:</td>
                                    <td class="field"><input name="cu_name" type="text" class="inputBox" value="{$req.name}"/></td>
                                    <td>(Last name optional)</td>
                                  </tr>
                                  <tr>
                                    <td align="right" height="28">Nickname<font color=red>*</font>:</td>
                                    <td class="field">{if $req.nickname eq ''}<input name="cu_nickname" type="text" class="inputBox" value="{$req.nickname}"/>{else}{$req.nickname}<input type="hidden" name="cu_nickname" value="{$req.nickname}" />{/if}</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right" height="28">Country<font color="red">*</font>:</td>
                                    <td class="field" colspan="2"><select name="cu_country" style="width:200px" onchange="javascript:checkCountry(this.value);" class="inputBox">
                                        {$req.countrylist} 
                                    </select></td>
                                  </tr>
                                  <tr>
                                    <td align="right" height="28">State<font color=red>*</font>:</td>
                                    <td class="field"><span id="cstate" style="display:{$req.cstatedisplay}"><select name="cu_state" style="width:200px" onchange="javascript:selectSuburb('bu_suburbobj', document.formcc.cu_state.options[document.formcc.cu_state.options.selectedIndex].value);" class="inputBox">
                                        {$req.selectState} 
                                    </select></span><span id="fstate" style="display:{$req.fstatedisplay}"><input name="fstate" type="text" class="inputBox" value="{$req.fstate}" />
                                    </span></td>
                                    <td><input name="suburb" type="hidden" id="suburb" value=""/></td>
                                  </tr>
								  <input type="hidden" name="theStoreID" value="{$req.StoreID}">
								  <tr align="left"  valign="top">
									  <td align="right" height="28" valign="middle">City/ Suburb<font color=red>*</font>:</td>
									  <td class="field" colspan="2"><span class="style11" style="display:{$req.cstatedisplay}" id="bu_suburbobj">
										<select name="bu_suburb" id="bu_suburb" class="inputBox" style="width: 200px;" >
										  {$req.selectSuburb} 
										</select>
									   </span><span id="fsuburb" style="display:{$req.fstatedisplay}"><input type="text" name="fsuburb" value="{$req.fsuburb}" class="inputBox"/></span></td>
								  </tr>
								   <tr>
                                    <td align="right" height="28">Post Code<font color=red>*</font>:</td>
                                    <td class="field"><input name="cu_postcode" type="text" class="inputBox" id="cu_postcode" value="{$req.postcode}" ></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right" height="28">Email Address<font color=red>*</font>:</td>
                                    <td class="field">{$req.user}</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right" height="28">Password<font color=red>*</font>:</td>
                                    <td class="field"><input name="cu_pass" type="password" class="inputBox" value="" ></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right" height="28">Re-enter Password<font color=red>*</font>:</td>
                                    <td class="field"><input name="re_pass" type="password" class="inputBox" value="" ></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right" height="28">Phone<font color=red>*</font>:</td>
                                    <td class="field" colspan="2"><input name="phone2" type="text" style="width:90px;" class="inputBox"  value="{$req.phone2}" size="15" maxlength="19" /></td>
                                  </tr>
                                  <tr>
                                    <td align="right" height="28">&nbsp;</td>
                                    <td class="field" colspan="2"><b><input name="agree" type="checkbox" id="agree" value="1" > I agree to the site <a href="soc.php?cp=terms" target="_blank">terms of use</a>. </b></td>
                                  </tr>
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td align="center"><input type="submit" class="or_submit" value="" ></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                </table>
								</div>
                            </td>
                          </tr>
						</form>
                          <tr>
                            <td><br /><br /><br /><br /> </td>
                          </tr>
                      </table>