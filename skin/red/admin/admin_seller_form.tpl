<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.isok eq 'yes'}
<script language="javascript">
	alert('{$req.msg}');
	location.href='/admin/?act=store';
</script>
{/if}
{literal}
<style type="text/css">
	.inputB{ width:250px; height:27px; padding:4px;}
	.selectB{width:250px;}
</style>
<script language="javascript">
	function changesellertype(obj){
		document.getElementById('substr1').style.display = "none";
		document.getElementById('substr2').style.display = "none";
		document.getElementById('substr3').style.display = "none";
		document.getElementById('substr5').style.display = "none";
		document.getElementById('lencnetr').style.display= 'none';
		document.getElementById('tr_username').style.display = "none";
		switch(obj.value){
			case '0':
			break;
			case '1':
				document.getElementById('substr1').style.display = "";
			break;
			case '2':
				document.getElementById('substr2').style.display = "";
				if($('input[@name=subattr2][@checked]').val()=='2'){
				document.getElementById('lencnetr').style.display='';
				}
			break;
			case '3':
				document.getElementById('substr3').style.display = "";
			break;
			case '5':
				document.getElementById('substr5').style.display = "";
				document.getElementById('tr_username').style.display = "";
			break;
		}
	}
	function checkform(obj){
		var objform = obj;
		var errmsg = "";
		if(obj.attribute.value==5 && obj.subattr5.value==1 && obj.bu_cuisine.value==""){
			errmsg +='- Cuisine is required.\n';
		}
		if(obj.attribute.value==5 && obj.bu_username.value==""){
			errmsg +='- Store ID is required.\n';
		}
		if(obj.bu_email.value==""){
			errmsg += '- Email Address is required.\n';
		}
		if(document.getElementById('bu_password1').value==""||document.getElementById('bu_password2').value==""){
			errmsg += '- Password is required.\n';
		}else{
			if(document.getElementById('bu_password1').value!=document.getElementById('bu_password2').value){
				errmsg +="- The passwords you have entered did not match.\n";
			}
		}
		if(obj.bu_nickname.value==""){
			errmsg +='- Nickname is required.\n';
		}
		if(obj.bu_name.value==""){
			errmsg +='- Website Name is required.\n';
		}
		if(obj.bu_urlstring.value==""){
			errmsg +='- URL String is required.\n';
		}
		if(obj.bu_state.value==""){
			errmsg +='- State is required.\n';
		}
		if(obj.bu_suburb.value==""){
			errmsg +='- City / Suburb is required.\n';
		}
		
		if(errmsg==""){
			$.post('/admin/index.php',{act:'store',cp:'checkform',email:obj.bu_email.value,nickname:obj.bu_nickname.value,website:obj.bu_name.value,urlstring:obj.bu_urlstring.value,attribute:obj.attribute.value,StoreID:obj.StoreID.value,username:obj.bu_username.value},function(data){																																																					
			var spary = data.split('|');
			if(data=="ok|ok|ok|ok|ok"){
				objform.submit();
			}else{
				if(spary[0]=="existed"){
					errmsg += "- Email Address is existed.\n";
				}else if(spary[0]=="invalid"){
					errmsg += "- Email Address is invalid.\n";
				}
				if(spary[1]=="existed"){
					errmsg += "- NickName is existed.\n";
				}
				if(spary[2]=="existed"){
					errmsg += "- Website Name is existed.\n";
				}
				if(spary[3]=="existed"){
					errmsg += "- URL String is existed.\n";
				}
				if(obj.attribute.value==5){
					if(spary[4]=="existed"){
						errmsg += "- Store ID is existed.\n";
					} else if(spary[4]=="invalid") {
						errmsg += "- Store ID is invalid.\n";
					}
				}
				alert(errmsg);
			}
		});
		}else{
			alert(errmsg);
		}
		return false;
	}
	function changestate(obj){
		$.post('/admin/index.php',{act:'store',cp:'getstatelist',state:obj.value},function(data){$('#bu_suburb').html(data);$('#bu_suburb').val('');});
	}

	function selectSectionType(market, value)
	{
		if(market == 5) {
			if(value == 1) {
				$("#tr_cuisine").css('display','');
			} else { 
				$("#tr_cuisine").css('display','none');
			}
		}
	}
</script>
{/literal}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.msg}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
    <form action="" method="post" onSubmit="return checkform(this);">
    <input type="hidden" name="StoreID" value="{$req.info.StoreID}" id="StoreID"/>
	<table width="600px" cellpadding="0" cellspacing="4">
    	<colgroup>
        <col width="20%"/>
        <col width="40%"/>
        <col width="30%"/>
        </colgroup>
    	<tr>
        	<td>Market Place*</td>
            <td><select name="attribute" class="selectB"  onChange="changesellertype(this);">
            	<option value="0" {if $req.info.attribute eq '0'}selected{/if}>Buy & Sell</option>
               	<option value="1" {if $req.info.attribute eq '1'}selected{/if}>Real Esate</option>
                <option value="2" {if $req.info.attribute eq '2'}selected{/if}>Automotive</option>
                <option value="3" {if $req.info.attribute eq '3'}selected{/if}>Careers</option>
                <option value="5" {if $req.info.attribute eq '5'}selected{/if}>Food & Wine</option>
            </select></td>
            <td></td>
        </tr>
        <tr id="substr1" {if $req.info.attribute eq '1'}style="display:"{else}style="display:none"{/if}>
        	<td>Sub Attribute*</td>
            <td><input id="subattr1[]" {if $req.info.attribute eq '1' && $req.info.subAttrib eq '1'}checked{elseif $req.info.attribute neq '1'}checked{/if} type="radio" value="1" name="subattr1"/> Seller <input id="subattr1[]" type="radio"  value="2" {if $req.info.attribute eq '1' && $req.info.subAttrib eq '2'}checked{/if} name="subattr1"/> Agent 
            </td>
            <td></td>
        </tr>
        <tr id="substr2" {if $req.info.attribute eq '2'}style="display:"{else}style="display:none"{/if}>
        	<td>Sub Attribute*</td>
            <td><input id="subattr2[]" {if $req.info.attribute eq '2' && $req.info.subAttrib eq '1'}checked{elseif $req.info.attribute neq '2'}checked{/if} type="radio" value="1" name="subattr2" onClick="javascript:document.getElementById('lencnetr').style.display='none';"/> Seller <input id="subattr2[]" type="radio"  value="2" name="subattr2" onClick="javascript:document.getElementById('lencnetr').style.display='';" {if $req.info.attribute eq '2' && $req.info.subAttrib eq '2'}checked{/if}/> Dealer 
            </td>
            <td></td>
        </tr>
        <tr id="lencnetr" {if $req.info.attribute eq '2' && $req.info.subAttrib eq '2'}style="display:"{else}style="display:none"{/if}>
        	<td>License No.</td>
            <td><input id="licence" class="inputB" maxlength="25" value="{$req.info.licence}" name="licence"/></td>
            <td></td>
        </tr>
        <tr id="substr3" {if $req.info.attribute eq '3'}style="display:"{else}style="display:none"{/if}>
        	<td>Sub Attribute*</td>
            <td colspan="2">
            <input id="subattr3[]" type="radio" {if $req.info.attribute eq '3' && $req.info.subAttrib eq '1'}checked{elseif $req.info.attribute neq '3'}checked{/if}  value="1" name="subattr3"/><font style="color: rgb(0, 0, 255);"> Post Jobs</font> 
            <!--<input id="subattr3[]" type="radio" value="2" {if $req.info.attribute eq '3' && $req.info.subAttrib eq '2'}checked{/if} name="subattr3"/> <font style="color: rgb(0, 0, 255);">Post Paid Resumes</font>--> 
            <input id="subattr3[]" type="radio" value="3" name="subattr3" {if $req.info.attribute eq '3' && $req.info.subAttrib eq '3'}checked{/if}/>
Post Resume
            </td>
        </tr>
        <tr id="substr5" {if $req.info.attribute eq '5'}style="display:"{else}style="display:none"{/if}>
        	<td>Sub Attribute*</td>
            <td colspan="2">
            <select name="subattr5" class="select">
		  		{foreach from=$lang.seller.attribute.5.subattrib item=l key=k}
					<option value="{$k}" {if $req.info.attribute eq '5' && $req.info.subAttrib eq $k || $req.info.subattr5 eq $k }selected="selected"{/if} onclick="selectSectionType('5', '{$k}')">{$l}</option>				
				{/foreach}
			</select>
            </td>
        </tr>
	  
        <tr id="tr_cuisine" style="display:{if $req.info.attribute eq "5" && $req.info.subAttrib eq "1"}{else}none{/if}">
            <td>Cuisine*</td>
            <td colspan="2">
                <span class="style11"><font face="Verdana" size="1"><font face="Verdana" size="1">
                <select name="bu_cuisine" id="bu_cuisine" class="select" >
                <option value="">Select Cuisine</option>
                {foreach from=$req.list.cuisine item=l key=k}
                    <option value="{$l.cuisineID}" {if $req.info.bu_cuisine eq $l.cuisineID}selected{/if}>{$l.cuisineName}</option>
                {/foreach}
                </select>
                </font></font></span>		</td>
        </tr>
        <tr>
        	<td>Email Address*</td>
            <td><input type="text" class="inputB" name="bu_email" id="bu_email" value="{$req.info.bu_email}"/></td>
            <td><!--(unique)-->&nbsp;<span id="emailbox"></span></td>
        </tr>
        <tr id="tr_username" {if $req.info.attribute eq '5'}style="display:"{else}style="display:none"{/if}>
        	<td>Store ID*</td>
            <td><input type="text" class="inputB" name="bu_username" id="bu_username" value="{$req.info.bu_username}"/></td>
            <td>(unique)&nbsp;<span id="emailbox"></span></td>
        </tr>
        <tr>
        	<td>Password*</td>
            <td><input type="password" class="inputB" id="bu_password1" name="bu_password" value="{$req.info.bu_password}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
         <tr>
        	<td>Re-enter Password*</td>
            <td><input type="password" class="inputB" id="bu_password2" name="bu_password" value="{$req.info.bu_password}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Nickname</span>*</td>
            <td><input type="text" class="inputB" id="bu_nickname" name="bu_nickname" value="{$req.info.bu_nickname}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td>Gender*</td>
            <td><input type="radio" {if $req.info.gender eq '0' or $req.info.gender eq ""}checked{/if} value="0" name="gender"/>Male <input type="radio" value="1" name="gender" {if $req.info.gender eq '1'}checked{/if}/>Female </td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td>Website Name*</td>
            <td><input type="text" class="inputB" id="bu_name" name="bu_name" value="{$req.info.bu_name}"/></td>
            <td><span id="webnamebox"></span></td>
        </tr>
        
        <tr>
        	<td>URL String*</td>
            <td><input type="text" class="inputB" id="bu_urlstring" name="bu_urlstring" value="{$req.info.bu_urlstring}"/></td>
            <td><span id="webnamebox"></span></td>
        </tr>
        
         <tr>
        	<td>Number & Street Address</td>
            <td><input type="text" class="inputB" name="bu_address" value="{$req.info.bu_address}"/></td>
            <td><input id="address_hide" type="checkbox" value="1" name="address_hide" {if $req.info.address_hide eq '1'}checked{/if}/>
Hide Address</td>
        </tr>
       
       	 <tr>
        	<td>State*</td>
            <td><select id="bu_state"  name="bu_state" class="selectB" onChange="changestate(this);">
            	<option value="">Select State</option>
                {if $req.list.state}
                {foreach from=$req.list.state item=lst}
                <option value="{$lst.id}" {if $lst.id eq $req.info.bu_state}selected{/if}>{$lst.description} ({$lst.stateName})</option>
                {/foreach}
                {/if}
            	</select>
            </td>
            <td></td>
        </tr>
        
        <tr>
        	<td>City / Suburb*</td>
            <td><select id="bu_suburb"  name="bu_suburb" class="selectB">
            	<option value="">Select City</option>
                {if $req.list.suburb}
                 {foreach from=$req.list.suburb item=lst}
                <option value="{$lst.suburb}" {if $lst.suburb eq $req.info.bu_suburb}selected{/if}>{$lst.suburb}</option>
                {/foreach}
                {/if}
            	</select>
            </td>
            <td></td>
        </tr>
        
        <tr>
        	<td>ZIP Code</td>
            <td><input type="text" class="inputB" name="bu_postcode" value="{$req.info.bu_postcode}"/>
            </td>
            <td></td>
        </tr>
        
        <tr>
        	<td>Phone Area Code</td>
            <td><input type="text" class="inputB" name="bu_area" maxlength="4" value="{$req.info.bu_area}"/>
            </td>
            <td></td>
        </tr>
        
        <tr>
        	<td>Phone</td>
            <td><input type="text" class="inputB" name="bu_phone" maxlength="20" value="{$req.info.bu_phone}"/>
            </td>
            <td><input id="phone_hide" type="checkbox" value="1" name="phone_hide" {if $req.info.phone_hide eq '1'}checked{/if}/>
Hide Phone </td>
        </tr>
        
        <tr>
        	<td>Cell Phone</td>
            <td><input type="text" class="inputB" name="mobile" maxlength="20" value="{$req.info.mobile}"/>
            </td>
            <td></td>
        </tr>
         <tr>
        	<td>Preferred Contact</td>
            <td><select id="contact" class="selectB" name="contact">
<option value="Telephone" {if $req.info.contact eq "Telephone"}selected{/if}>Telephone</option>
<option value="Email" {if $req.info.contact eq "Email"}selected{/if}>Email</option>
<option value="Telephone or Email" {if $req.info.contact eq "Telephone or Email"}selected{/if}>Telephone or Email</option>
</select>
            </td>
            <td></td>
        </tr>
         <tr>
        	<td>Fax</td>
            <td><input type="text"  class="inputB" name="bu_fax" maxlength="20" value="{$req.info.bu_fax}"/>
            </td>
            <td></td>
        </tr>
   		
        <tr>
        	<td>Suspend</td>
            <td><input type="checkbox" name="suspend" {if $req.info.suspend}checked{/if} value="1"/> Is suspend?
            </td>
            <td></td>
        </tr>
        
        <tr>
        	<td></td>
            <td><input type="submit" class="hbutton" value="Save"/> <input class="hbutton" type="button"  value="Cancel" onClick="location.href='/admin/?act=store'"/></td>
            <td></td>
        </tr>
        
    </table>
    </form>
	</div>
