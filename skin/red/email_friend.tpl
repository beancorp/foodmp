{literal}
<script language="JavaScript" type="text/JavaScript">
function checkForm(obj){
	var boolResult	=	true;
	try{
		if(obj.from_name.value==""){
			alert("Your friend's name is required.");
			obj.from_name.focus();
			return false;
		}
		if(obj.to.value == ''){
			alert("Recipient's email address is required.");
			obj.to.focus();
			return false;
		} else if(!ifEmail(obj.to.value,false)){
			alert("Recipient's email address is invalid.");
			obj.to.focus();
			return false;
		}
		if(obj.sender.value == ''){
			alert("Your name is required.");
			obj.sender.focus();
			return false;
		}
		if(obj.from.value == ''){
			alert("Your email address is required.");
			obj.from.focus();
			return false;
		} else if(!ifEmail(obj.from.value,false)){
			alert("Your email address is invalid.");
			obj.from.focus();
			return false;
		}
		if(obj.message.value == ''){
			alert("Your message is required.");
			obj.message.focus();
			return false;
		}
		if(obj.validation.value == ''){
			alert("Validation Code is required.");
			obj.validation.focus();
			return false;
		}
	}catch(ex){
		alert(ex);
	}
	return boolResult;
}

function ifEmail(str,allowNull){
	if(str.length==0) return allowNull;
	i=str.indexOf("@");
	j=str.lastIndexOf(".");
	if (i == -1 || j == -1 || i > j) return false;
	return true;
}
</script>
{/literal}

<p align="center" class="txt"><font color="#FF0000" style="font-size:14px;">{$req.msg}</font></p>

  <form method="post" action="soc.php?cp=friend&StoreID={$req.StoreID}" name="mainForm" id="startselling" onsubmit="return checkForm(this);">
	<input name="act" type="hidden" value="send" />
 <input name="format" type="hidden" value="html" />
 <input name="pid" type="hidden" value="{$req.pid}" />
         <fieldset>
        <ol>
        <Li><label>Your friend's name*</label>
        <input name="from_name" maxlength="80" type="text" class="inputB" id="from_name" size="35" value="{$req.tmpval.from_name}"/>
        </Li>
       <li>
        <label>Recipient's email address*</label>
        <input name="to" maxlength="250" type="text" class="inputB" id="to" size="35" value="{$req.tmpval.to}" />
        </li>
        <li>
        <label>Your name*</label>
        <input name="sender" type="text" class="inputB" id="sender" size="35" maxlength="80" value="{$req.tmpval.sender}" />
        </li>
        <li>
        <label>Your email address*</label>
        <input name="from" maxlength="250" type="text" class="inputB" id="from" size="35" value="{$req.tmpval.from}" />
        </li>
        <li>
        <label>Your message*</label>
        <textarea name="message" cols="30" rows="8" class="inputB" id="message">{$req.tmpval.message}</textarea>
        </li>
        <li>
        <label>Validation Code*</label> 
        <input name="validation" type="text" class="inputB" id="validation" value="" size="4" maxlength="4"  style="width:40px; vertical-align:top;"/>
        <img src="authimg.php" style="width:70px; height:27px; "></span>
        </li>
		<li>
        <label> </label>
        <input name="submit" type="image" src="skin/red/images/buttons/or-submit.gif" style="border:none" id="submit" value="Send" />
		</li>
        </ol>
        </fieldset>
</form>
