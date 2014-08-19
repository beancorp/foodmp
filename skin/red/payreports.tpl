<table width="100%"  border="0">

        <tr>

          <td width="17%">&nbsp;</td>

          <td width="41%">&nbsp;</td>

          <td width="19%">&nbsp;</td>

          <td width="23%">&nbsp;</td>

        </tr>



        <tr>

          <td align="left">&nbsp;</td>

          <td>&nbsp;</td>

          <td>&nbsp;</td>

          <td>&nbsp;</td>

        </tr>

        <tr>

          <td align="left">Joining Date:</td>

          <td>{$req.startDate}</td>

          <td align="left">Renewed Date:</td>

          <td>{$req.nextYear}</td>

        </tr>

        <tr align="center">

          <td colspan="4">&nbsp;</td>

        </tr>

        <tr align="center">

          <td colspan="4" align="left"><p align="center" class="txt" style="width:560px;"><font style="color:red;">{$req.msg|nl2br}</font></p></td>

          </tr>
		  
		<tr><td colspan="4">
		<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <form  name="paypall" action="{$paypalInfo.paypal_url}" method="post" onsubmit="return checkForm_paypal(this);">
           <input type="hidden" name="item_name" value="Deposit money in your account">                                 
           <input type="hidden" name="currency_code" value="AUD">
		   {if $req.paymentMethod eq ''}
    	   <input type="hidden" name="cmd" value="_xclick">
           <input type="hidden" name="business" value="{$paypalInfo.paypal_email}">
		   <input type="hidden" name="item_number" value="{$req.StoreID}">
           <input type="hidden" name="StoreID" value="{$req.StoreID}"> 
           <input type="hidden" name="custom" value="StoreID={$req.StoreID}&type=payment&paid={php}echo time();{/php}"> 
           <input type="hidden" name="return" value="{$paypalInfo.paypal_siteurl}soc.php?act=signon&step=startSelling_ipn">
          <input type="hidden" name="cancel_return" value="{$paypalInfo.paypal_siteurl}">
          <input type="hidden" name="notify_url" value="{$paypalInfo.paypal_siteurl}soc.php?act=signon&step=startSelling_ipn">
		  {/if}
    <tr>
      <td align="center" valign="top" class="pad10px"><img border="0" alt="Paypal" title="Paypal" src="/images/payment_logo/paypal.gif"/>&nbsp;<img border="0" src="/images/payment_logo/visa.gif" alt="Visa" title="Visa"/>&nbsp;<img border="0" src="/images/payment_logo/MC.gif" alt="Master Card" title="Master Card"/>&nbsp;<img border="0" src="/images/payment_logo/Amex.gif" alt="Amex" title="Amex"/>&nbsp;<img border="0" src="/images/payment_logo/Discover.gif" alt="Discover" title="Discover"/></td>
      </tr>
      <input type="hidden" name="attribute" value="{$smarty.session.attribute}"/>
    <tr>
      <td align="right" valign="top" class="pad10px">
	  {if $smarty.session.attribute eq '0'}
      <input name="amount" type="hidden" value="120"  />
        <span > <br />
		</span>
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td width="15%" height="30">&nbsp;</td>
            <td width="85%">&nbsp;</td>
          </tr>
          <tr>
			<td height="35" align="right" width="15%" valign="top"><span class="txt" style="height:25px; line-height:25px;"><span class="lbl">{$lang.labelBusinessName}*</span></span></td>
			<td align="left" height="35" valign="top"><span class="style11"><font face="Verdana" size="1"><input name="bu_name" type="text" class="inputB" id="bu_name" value="{$req.bu_name|escape:"html"}" size="30" maxlength="60" onChange="checkBuNameUnique(this);" onblur="checkBuNameUnique(this);"/></font></span>
			<div id="bu_name_clew" style="display:none;margin-left:0; position:static;"></div></td>
		</tr>
          <tr>
			<td height="35" width="15%" align="right" valign="top"><span class="txt" style="height:25px; line-height:25px;"><span class="lbl">{$lang.labelurlstring}*</span></span></td>
			<td  align="left" height="35" valign="top"><span class="style11"><font face="Verdana" size="1"><input name="bu_urlstring" type="text" class="inputB" id="bu_urlstring" value="{$req.bu_urlstring}" size="30" onkeyup="changeUrl(this.value)" onChange="changeUrl(this.value)" {if $isUpdate == false}onblur="checkWebsite();"{/if}  onfocus="javascript:webstatu='error';" maxlength="60"/>
			<input type="hidden" name="bu_name_default" value="{$req.bu_urlstring}"></font></span>
			<div id="msgbox1" style="display:none;width:320px; margin-left:0; position:static;"></div></td>
		</tr>
		<tr>
			<td align="right" height="50">&nbsp;</td>
			<td class="tip">The URL String will automatically become your URL. <br />E.g. <span id="url" style="color: #0000FF">www.socexchange.com.au/{$req.bu_urlstring}</span></td>
		</tr>
        </table>
        <span >
		<input type="hidden" name="keepon" id="keepon" value="yes" />
		<input type="hidden" name="bu_user" id="bu_user" value="{$req.bu_user}" />
		<input type="hidden" name="bu_username" id="bu_username" value="{$req.bu_username}" />
        <!--<input name="submit" type="image" src="/skin/red/images/buttons/or-paynow.gif" class="greenButt" value="Pay Now" />-->
	
    	{if $smarty.session.attribute eq '5'}
        	<input type="image" src="/skin/red/images/free_payment/Renew.gif" alt="Renew" title="Renew" />
        {else}
			<a href="javascript:void(0);" onclick="buysellRenew();" title="Renew"><img src="/skin/red/images/free_payment/Renew.gif" title="Renew" alt="Renew"/></a>
        {/if}
        </span>       
        </td>
      </tr>
	  
  </form>
  <tr>
    <td class="pad10px"><div align="center">
      <p align="left">Open your website now. It is only $1 per month. A 1 year purchase for $10 in advance, will give you a 17% discount on the normal monthly rate. All State & Federal taxes are included.</p>
	 <div align="center">
      <p align="left">Open your website now. It is only $10 per year. All State & Federal taxes are included.</p>
	  
      <p align="left"></p></div>
	  </td>
  </tr>
  {else}
  <tr>
  <td height="48"></td>
  </tr>
  <tr><td>
  <input name="bu_name" type="hidden" class="inputB" value="{$req.bu_name}"/>
    <table width="100%">
    	<tr>
        <td height="35" width="15%" align="right" valign="top"><span class="txt" style="height:25px; line-height:25px;"><span class="lbl">{$lang.labelurlstring}*</span></span></td>
        <td  align="left" height="35" valign="top"><span class="style11"><font face="Verdana" size="1"><input name="bu_urlstring" type="text" class="inputB" id="bu_urlstring" value="{$req.bu_urlstring}" size="30" onkeyup="changeUrl(this.value)" onChange="changeUrl(this.value)" {if $isUpdate == false}onblur="checkWebsite();"{/if}  onfocus="javascript:webstatu='error';" maxlength="60"/>
        <input type="hidden" name="bu_name_default" value="{$req.bu_urlstring}"></font></span>
        <div id="msgbox1" style="display:none;width:320px; margin-left:0; position:static;"></div></td>
	</tr>
    <tr>
			<td align="right" height="50">&nbsp;</td>
			<td class="tip">The URL String will automatically become your URL. <br />E.g. <span id="url" style="color: #0000FF">www.socexchange.com.au/{$req.bu_urlstring}</span></td>
		</tr>
    </table>
    </td></tr>
  <tr>
    <td align="right" class="pad10px">
	  <input type="hidden" name="amount" value="120" />
        <span > <br />
		<input type="hidden" name="keepon" id="keepon" value="yes" />
		<input type="hidden" name="bu_user" id="bu_user" value="{$req.bu_user}" />
		<input type="hidden" name="bu_username" id="bu_username" value="{$req.bu_username}" />
       <!--<input name="submit" type="image" src="/skin/red/images/buttons/or-paynow.gif" class="greenButt" value="Pay Now" title="$10 / year" />-->
	   
    	{if $smarty.session.attribute eq '5'}
       		<input type="image" src="/skin/red/images/free_payment/Renew.gif" alt="Renew" title="Renew" />        
        {else}
	   <a href="javascript:void(0);" onclick="otherTypeRenew();" title="Renew"><img src="/skin/red/images/free_payment/Renew.gif" alt="Renew" title="Renew" /></a>
       {/if}
       </span>
       </td>
  </tr>
  
  	{if $smarty.session.attribute eq '1'}
	<tr><td height="35" class="pad10px">Yes! Sell as many Real Estate properties as you like for $120 per Year Flat Rate!
	</td>
	</tr>
	{elseif  $smarty.session.attribute eq '2'}
	<tr><td height="35" class="pad10px">Yes! Sell as many cars or bikes as you like for $120 per Year Flat Rate!
	</td>
	</tr>
	{elseif  $smarty.session.attribute eq '5'}
	<tr><td height="35" class="pad10px">Yes! Connect online with your local customers as you like for $120 per Year Flat Rate!
	</td>
	</tr>
	{else}
	<tr><td height="35" class="pad10px">Yes! Find your entire workforce, post as many 'positions vacant' or post as many resume's or CV's as you like for $120 per Year Flat Rate!
	</td>
	</tr>
	{/if}
     	
  {/if}
  
  </form>
</table>
	</td></tr>

	</table>
	

{literal}
<script language="javascript">
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

function changeUrl(url){
	newurl = url.replace(/[^\d\w]/g,'');
	newurl = newurl.replace(/_/g,'');
	if (newurl.length > 60){
		alert("The URL String must be less than 60 characters.\n");
	}else{
		//$('#bu_urlstring').val(newurl);
		document.getElementById("url").innerHTML = 'www.socexchange.com.au/'+newurl;
	}
}

function checkWebsite(){

	//remove all the class add the messagebox classes and start fading
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


function checkForm_paypal(obj){
	try{
		RegExp.multiline=true;
		var errors 	=	'';
		var flag 	=	1;
		var emailID=obj.bu_user;
{/literal}
{if $smarty.session.attribute eq '0'}
{literal}		
		if(obj.bu_name.value==''){
			errors += '-  Website Name is required.\n';
		}else{
			var webname = obj.bu_name.value;
			webname = webname.replace(/[^\d\w]/g,'');
			if (webname.length > 60){
				errors += "-  The Website Name must be less than 60 characters.";
			}
		}
{/literal}
{/if}
{literal}
		if(obj.bu_urlstring.value==''){
			errors += '-  URL String is required.\n';
		}else{
			newurl = obj.bu_urlstring.value;
			newurl = newurl.replace(/[^\d\w]/g,'');
			if (newurl.length > 60){
				errors += "-  The URL String must be less than 60 characters.";
			}
		}

		
	}catch(ex)
	{
		alert(ex);
	}

	if(errors!=''){
		errors = 'Sorry, the following fields are required.\n'+errors;
		alert(errors);
		return false;
	}
	else
	{
		return true;
	}
}
</script>
<style type="text/css">
.messagebox{
	font-familly:Arial;
	position:absolute;
	width:100px;
	margin-left:30px;
	padding:3px;
}
.messageboxok{
	position:absolute;
	width:auto;
	margin-left:30px;
	padding:3px;
	color:#008000;
}
.messageboxerror{
	position:absolute;
	width:auto;
	margin-left:30px;
	padding:3px;
	color:#CC0000;
}

</style>

<script type="text/javascript">
	function buysellRenew()
	{
		if(false == checkForm_paypal(document.paypall)) {
			return false;
		}
		$.post('/jquery.functions.php',{'cp':'free_renew','type':'buysell','bu_name': $("#bu_name").val(),'bu_urlstring':$("#bu_urlstring").val()},function(s) {
			if('_F_' == s) {
				alert('Renew Fail.');
			}
			else {
				msg = '_U_' == s ? 'Your account was upgraded successfully.' : 'Your account was renewed successfully.';
				alert(msg);
				location.href = "/soc.php?cp=sellerhome";
			}		
		});
	
	}
	
	function otherTypeRenew()
	{
		if(false == checkForm_paypal(document.paypall)) {
			return false;
		}
		$.post('/jquery.functions.php',{'cp':'free_renew','type':'other','bu_urlstring':$("#bu_urlstring").val()},function(s) {

			if('_F_' == s) {
				alert('Renew Fail.');
			}
			else {
				msg = '_U_' == s ? 'Your account was upgraded successfully.' : 'Your account was renewed successfully.';
				alert(msg);
				location.href = "/soc.php?cp=sellerhome";
			}		
		});
	}


</script>
{/literal}