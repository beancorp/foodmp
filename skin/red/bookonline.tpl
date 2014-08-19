{literal}
<script language="javascript">
$(document).ready(function() {
	setTimeout(function(){$("#msg_show").hide();},5000);
})
function closePopupLogin(){
	window.opener.location.href('customers_geton.php');
	window.close() ;
}

function checkForm(obj){ 
	var errors = '';
	var r = /^[0-9]*[1-9][0-9]*$/;
	obj.firstname.value=='' ? (errors += '-  First Name is required.\n') : '' ;
	obj.lastname.value=='' ? (errors += '-  Last Name is required.\n') : '' ;
	obj.email.value=='' ? (errors += '-  Email is required.\n') : '' ;
	if(obj.email.value && !ifEmail(obj.email.value,false)) {
		errors += '-  Email is invalid.\n';
	}
	obj.name.value=='' ? (errors += '-  Name is required.\n') : '' ;
	obj.phone.value=='' ? (errors += '-  Phone is required.\n') : '' ;
	obj.quantity.value=='' ? (errors += '-  No. People is required.\n') : (!r.test(obj.quantity.value) ? (errors += '-  No. People must be numberal.\n') : '');
	obj.reservation_date.value=='' ? (errors += '-  Date is required.\n') : '' ;

	if(obj.reservation_date.value) {
		var endDateArr = obj.reservation_date.value.split("/");
		var myDate = new Date();
		var curDateArr = new Array(myDate.getDate(),(myDate.getMonth()+1),myDate.getFullYear());
		curDateArr[1] = curDateArr[1] < 10 ? '0' + curDateArr[1] : curDateArr[1];
		var reservation_date = parseInt(endDateArr[2].toString()+endDateArr[1].toString()+endDateArr[0].toString());
		var cur_date = parseInt(curDateArr[2].toString()+curDateArr[1].toString()+curDateArr[0].toString());
		
		if(reservation_date < cur_date) {
			errors += '-  Date must after current date.\n';
		}
	
	}
	
	if (errors != ''){
		errors = '-  Sorry, the following fields are required.\n' + errors;
		alert(errors);
		return false;
	}else{
		return true;
	}
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

<p align="center" class="txt" id="msg_show" style="{if $req.msg eq ''}display: none;{/if}"><font style="color:red;">{$req.msg}</font></p>
<h2 style="color:#555555; font-weight:bold; padding:7px 0;">Online Booking Request</h2>
<p>This is a booking request only and must be confirmed by the establishment.</p>
  	<form method="post" action="/soc.php?cp=bookonline&StoreID={$req.StoreID}" name="mainForm" id="startselling" onsubmit="return checkForm(this);">
        <table width="100%" cellspacing="6" style="padding:0; margin:0" >
            <tr>
                <td width="10%" align="right" class="text">First Name *</td>
                <td><input name="firstname" maxlength="80" type="text" class="inputBox"/></td>
              	<td align="right" class="text" width="10%">Phone *</td>
              	<td align="left" ><input name="phone" type="text" class="inputBox" id="phone" value="" maxlength="30" /></td>
            </tr>
            
            <tr>
            <td align="right" class="text" >Last Name *</td>
            <td align="left" ><input name="lastname" type="text" class="inputBox" value="{if empty($_SESSION.NickName)}{$_SESSION.UserName}{else}{$_SESSION.NickName}{/if}" /></td>
            <td align="right" class="text">Email *</td>
            <td align="left" ><input name="email" type="text" class="inputBox" value='{$booker_email}'/></td>
            </tr>
            <tr>
               <td align="right" class="text">Date *</td>
               <td>
                <input style="width:91px;" name="reservation_date" id="reservation_date" type="text" class="inputB date"  size="11" readonly="readonly" maxlength="11" value=""/>
                <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.mainForm.reservation_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>&nbsp;&nbsp;&nbsp;
                No. People *&nbsp;
                <select name="quantity" class="inputB time" id="quantity" style="width:60px;">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10+</option>
                    <option value="15">15+</option>
                    <option value="20">20+</option>
                    <option value="50">50+</option>
                    <option value="100">100+</option>
                    <option value="200">200+</option>
                    <option value="500">500+</option>
                    <option value="1000">1000+</option>
                </select>
                </td>
                <td align="right" valign="top" rowspan="2" class="text">Details</td>
            	<td rowspan="2" valign="top"><textarea name="comments" rows="8" cols="60" class="inputBox" style="width:275px;"></textarea></td>
            </tr>
            <tr>
              <td align="right" class="text" >Time *</td>
              <td>
                <select name="start_hour" class="inputB time" id="start_hour" style="width:63px; text-align:center; padding:5px 5px 5px 0;">
                {foreach from=$arr_hour item=h }
                    <option value="{$h}">{$h}</option>
                {/foreach}
                </select>&nbsp;:&nbsp;
                <select name="am" class="inputB time" id="am" style="width:63px;">
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
             </td>
            </tr>
		</table>
        <input type="hidden" name="cp" value="bookonline" />
        <input type="hidden" name="StoreID" value="{$req.StoreID}" />
        <input type="image" border="0" style="margin:15px 3px 0 0; float:right; border:none" src="/skin/red/images/foodwine/send.jpg"  value="Send Online Booking Request">
</form>
<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.php" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>