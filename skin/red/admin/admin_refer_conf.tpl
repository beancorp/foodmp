<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<style type="text/css">
	#user_ref_list{
		text-align:center;
		witdh:750px;
	}
	.tabletop	{height:30px;
			 text-align:center;
			 border-left:1px solid #FFFFFF;
			 background:#66ACCF;
			 font-size:12px;font-weight:bold;}
	.tablelist { height:25px;
			 text-align:center;
			 background-color:#eeeeee; 
			 border-left:1px solid #FFFFFF;
			 border-bottom:1px solid #FFFFFF;
		   }
	.inputB{
		width:130px;
	}
</style>
<script language="javascript">
	function checkform(obj){
		var per   = document.getElementById('percent').value;
		var m_ref = document.getElementById('min_refer').value;
		var m_com = document.getElementById('min_commission').value;
		if(per==""){
			alert("Commission Rate is required.");
			return false;
		}
		if(m_ref==""){
			alert("Withdrawal amount is required.");
			return false;
		}
		if(m_com==""){
			alert("Earnings before commission is required.");
			return false;
		}
		obj.submit();
	}
	
	function checkformRef(obj){
		if(obj['ReferrerID'].value==""){
			alert("Referrer ID is required.");
			return false;
		}
		if(obj['percent'].value==""){
			alert("Commission Rate is required.");
			return false;
		}
		if(obj['min_commission'].value==""){
			alert("Earnings before commission is required.");
			return false;
		}
		if(obj['min_refer'].value==""){
			alert("Withdrawal amount is required.");
			return false;
		}
		if(obj.name == "Ref_form_edit"){
			xajax_saveReferConfig(xajax.getFormValues("Ref_form_edit"));
		}else{
			xajax_saveReferConfig(xajax.getFormValues("Ref_form"));
		}
	}
	function cancleedit(){
		document.getElementById('editform').style.display = "none";
		document.getElementById('newform').style.display = "";
		document.getElementById('EditID').value = "";
		document.getElementById('EditReferrerID').value = "";
		document.getElementById('Editpercent').value = "";
		document.getElementById('Editminrefer').value = "";
		document.getElementById('Editmincommistion').value = "";
		document.getElementById('ndstatus').value = "Charity";
	}
	function refedit(id){
		xajax_getReferConfig(id);
	}
	function refdel(id){
		if(confirm('Are you sure you want to delete this referrer config?')){
			xajax_delReferConfig(id);
		}
	}
</script>
{/literal}
<div>
	<div style="color:#FF0000; text-align:center">{$req.msg}</div>
</div>
<div align="center">
<form action="" method="post" onsubmit="checkform(this);return false;">
	<table cellpadding="0" cellspacing="3">
		<tr>
		  <td>Commission Rate:</td>
		  <td><input type="text" class="inputB" id="percent" name="percent" value="{$req.config.percent}"/></td></tr>
		<tr><td>Earnings before commission:</td>
		<td><input type="text" id="min_commission" class="inputB" name="min_commission" value="{$req.config.min_commission|number_format:2}"/></td></tr>
		<tr><td>Withdrawal amount:</td>
		<td><input type="text" class="inputB" id="min_refer" name="min_refer" value="{$req.config.min_refer|number_format:2}"/></td></tr>
		<tr><td colspan="2" align="right"><input type="submit" value="Save" class="hbutton"/></td></tr>
		<input type="hidden" value="save" name="opt"/>
	</table>
</form>
</div>
<div style="margin:20px 0 0px 0;font-size:18px;font-weight:bold;height:30px;" align="center">Special Referral Rates</div>
<div align="center" style="padding:0px 0 5px 0;">
<div id="newform">
<form action="" method="post" id="Ref_form" name="Ref_form" onsubmit="checkformRef(this);return false;">
	<table cellpadding="0" cellspacing="3">
		<colgroup>
			<col width="130px"/>
			<col width="130px"/>
			<col width="130px"/>
			<col width="130px"/>
			<col width="100px"/>
		</colgroup>
		<tr><td>Referrer ID: </td><td><input type="text" class="inputB" id="ReferrerID" name="ReferrerID" value=""/></td><td>Commission Rate: </td><td><input type="text" class="inputB" id="newpercent" name="percent" value=""/></td><td></td></tr>
		<tr><td>Earnings before commission:</td><td><input type="text" id="newmincommistion" class="inputB" name="min_commission" value=""/></td><td>Withdrawal amount:</td><td><input type="text" id="newminrefer" class="inputB" name="min_refer" value=""/></td><td></td></tr>
        <tr><td>Status:</td><td>
        	<select id="ndstatus" name="status" style="width:130px;">
            	<option value="Charity">Charity</option>
                <option value="Blogger">Blogger</option>
                <option value="School">School</option>
                <option value="Standard">Standard</option>
            </select>
        </td><td><input type="submit" value="Create" class="hbutton"/></td></form><td align="right"><form action="" method="post"><input type="submit" name='act_report' value="Export" class="hbutton" /></form></td><td></td></tr>
	</table>

</div>

<div id="editform" style="display:none;">
<form action="" method="post" id="Ref_form_edit" name="Ref_form_edit" onsubmit="checkformRef(this);return false;">
	<input type="hidden" id="EditID" value="" name="id"/>
	<table cellpadding="0" cellspacing="3">
		<colgroup>
			<col width="130px"/>
			<col width="130px"/>
			<col width="130px"/>
			<col width="130px"/>
			<col width="100px"/>
		</colgroup>
		<tr><td>Referrer ID: </td><td><span id="refidtxt">&nbsp;</span><input type="hidden" class="inputB" id="EditReferrerID" name="ReferrerID" value=""/></td><td>Commission Rate: </td><td><input type="text" class="inputB" id="Editpercent" name="percent" value=""/></td><td></td></tr>
		<tr><td>Earnings before commission:</td><td><input type="text" id="Editmincommistion" class="inputB" name="min_commission" value=""/></td><td>Withdrawal amount:</td><td><input type="text" id="Editminrefer" class="inputB" name="min_refer" value=""/></td><td></td></tr>
        <tr><td>Status:</td><td>
        <select id="edstatus" name="status" style="width:130px;">
            <option value="Charity">Charity</option>
            <option value="Blogger">Blogger</option>
            <option value="School">School</option>
            <option value="Standard">Standard</option>
        </select>
        </td><td><input type="submit" value="Save" class="hbutton"/>&nbsp;<input type="button" value="Cancel" onclick="cancleedit();" class="hbutton"/></form><td align="right"><form action="" method="post"><input type="submit" name='act_report' value="Export" class="hbutton" /></form></td><td></td></tr>
	</table>
</div>
</div>
<div align="center" id="conflist" style="display:;padding: 0 0 20px 0;">{include file="admin_refer_conf_list.tpl"}</div>