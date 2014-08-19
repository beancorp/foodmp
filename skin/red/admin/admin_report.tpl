<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<style type="text/css">
.buttoncs{
	margin-left:221px;
	+margin-left:227px;
	width:60px;
}
.seletcs{
	width:202px;
	+width:208px;
}
.states{
	padding-left:10px;
	+padding-left:15px;
}
@media screen and (-webkit-min-device-pixel-ratio:0) {
	.buttoncs{
		margin-left:234px;
		width:60px;
	}
	.seletcs{
		width:215px;
	}
	.states{
		padding-left:25px;
	}
}
</style>
<script>
function reportcheckFrom(){
	if(document.getElementById('start_date').value==""){
		alert("Please select Start Date.");
		return false;
	}
	return true;
}
function getcollege(obj){
	$.get('/admin/?act=report',{cp:'college',stateid:obj.value},function(data){$('#collegetd').attr('innerHTML',data);});
}

function changemebship(obj){
	if(obj.value==""){
		document.getElementById('inrenew').disabled = false;
	}else{
		document.getElementById('inrenew').checked = false;
		document.getElementById('inrenew').disabled = true;
	}
}
</script>
{/literal}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	<div id="input-table" style="width:750px; padding:0px; text-align:left;">
		<form id="new_form" name="new_form"  action="" method="post" onsubmit="return reportcheckFrom();">
		<ul style="margin-left:150px;padding-bottom:5px;">
			<li>
			<table cellpadding="1" cellspacing="3">
				<tr ><td>Start Date:*</td>
					<td>
					<input type="text"  style="width:80px;+height:21px;+padding:2px 0 0 0;" id="start_date" name="start_date" maxlength="12" value="{$req.selected.start_date}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.new_form.start_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>&nbsp;<select name="s_hour">{foreach from=$hour item=h key=k}
						<option value="{$k}" {if $req.selected.s_hour eq $k}selected{/if}>{$h}</option>
					{/foreach}</select>:<input type="text" name="s_min" value="{$req.selected.s_min}" maxlength="2" style="width:40px;+height:21px;+padding:2px 0 0 0;"/>
					</td>
					<td>&nbsp;</td>
					<td>End Date:&nbsp;
					</td>
					<td><input type="text"  style="width:80px;+height:21px;+padding:2px 0 0 0;" id="end_date" name="end_date" maxlength="12" value="{$req.selected.end_date}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.new_form.end_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>&nbsp;<select name="e_hour">{foreach from=$hour item=h key=k}
							<option value="{$k}" {if $req.selected.e_hour eq $k}selected{/if}>{$h}</option>
						{/foreach}</select>:<input type="text" name="e_min" value="{$req.selected.e_min}" maxlength="2" style="width:40px;+height:21px;+padding:2px 0 0 0;"/>
					</td>
				</tr>
				<tr style="margin-bottom:5px;">
					<td>Market Place:&nbsp;</td>
					<td><select name="selletype" class='seletcs'>
						{foreach from=$sellertype item=stype key=skey}
							<option value="{$skey}" {if $req.selected.selletype eq $skey}selected{/if}>{$stype}</option>
						{/foreach}
						</select>
					</td>
					
					<td>&nbsp;</td>
					
					<td>State:&nbsp;
					</td>
					<td>
						<select name="state" class='seletcs' onchange="getcollege(this);">
							<option value="">Select State</option>
							{foreach from=$state item=stype}
							<option value="{$stype.id}" {if $req.selected.state eq $stype.id}selected{/if}>{$stype.description}({$stype.stateName})</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr style="margin-bottom:5px;">
					<td>Membership:&nbsp;
					</td>
					<td>
					<select name="mebship" class='seletcs' onchange="changemebship(this);">
						<option value="">All</option>
						<option value="1" {if $req.selected.mebship eq "1"}selected{/if}>1 year</option>
					</select>
				</td>
				<td>&nbsp;</td>
				
				<td>Gender:&nbsp;</td>
				<td><select name='gender' class='seletcs'>
					<option value="" {if $req.selected.gender eq ''}selected{/if}>All</option>
					<option value="0" {if $req.selected.gender eq '0'}selected{/if}>Male</option>
					<option value="1" {if $req.selected.gender eq '1'}selected{/if}>Female</option>
				</select></td></tr>
				<tr>
				<td>College:&nbsp;</td>
				<td id="collegetd"><select name='college' class='seletcs'>
					<option value="" {if $req.selected.gender eq ''}selected{/if}>All</option>
					{foreach from=$college item=cl key=k}
						<option value="{$k}" {if $req.selected.college eq $k}selected{/if}>{$cl}</option>
					{/foreach}
				</select></td>
				<td>&nbsp;</td>
				<td colspan="2" valign="middle"><input id="inrenew" type="checkbox" name="inrenew" style="padding:0;+width:17px;+height:17px; float:left;" value="1" {if $req.selected.inrenew eq '1'} checked="checked" {/if} {if $req.selected.mebship eq "1"} disabled="disabled"{/if}/> &nbsp;Include Renewal</td>
				</tr>
				<tr><td colspan="2" align="right">
				<input class="hbutton" type="submit" name="act_report" value="Search"/></td><td>&nbsp;</td>
                <td colspan="2" align="left">
				<input type="submit" name="act_report" value="Export" class="hbutton"/>	
				</td></tr>
				</table>
			</li>
		</ul>
		<input type="hidden" name="opt" value="search"/>
		</form>
	</div>	
	<div align="center" style="border-bottom-color:#999999;">
		<div style="width:750px;font-size:12px;font-weight:400;color:#000000; margin-bottom:5px;">The signup times stored in the database are Sydney Time. To get the correct time to do a search, go to <a href="http://www.timezoneconverter.com/cgi-bin/tzc.tzc" target="_blank">http://www.timezoneconverter.com/cgi-bin/tzc.tzc</a> and convert your required time are Sydney Time.</div>
	<form id="mainForm" name="mainForm" method="post" action="">
	<div id="tabledatalist" class="wrap" >
		{include file="admin_report_list.tpl"}
	</div>
	<input name="pageno" type="hidden" id="pageno" value="{$req.reportlist.pageno}"/>
	</form>
	</div>
	<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>