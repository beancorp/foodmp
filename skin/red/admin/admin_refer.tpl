<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<style type="text/css">
.seletcs{
	width:168px;
}
.states{
	padding-left:10px;
}
.tabletop	{height:30px;
			 text-align:center;
			 border-left:2px solid #FFFFFF;
			 background:#66ACCF;
			 font-size:12px;font-weight:bold;}
.tablelist { height:22px;
			 text-align:center;
			 background-color:#eeeeee; 
			 border-left:2px solid #FFFFFF;
			 border-bottom:1px solid #FFFFFF;
		   }
</style>
<script type="text/javascript">
function getcollege(obj){
	var j=0;
	for(var i=0;i<obj.options.length;i++){
		if(obj.options[i].selected&&obj.options[i].value!=""){
			j++;
		}
	}
	var stateid = "";
	if(j>1){
		obj.options[0].selected = false;
		stateid = "";
	}else{
		stateid = obj.value;
	}
	$.get('/admin/?act=report',{cp:'college',stateid:stateid},function(data){$('#collegetd').attr('innerHTML',data);});
}
</script>
{/literal}
<div id="ajaxmessage" class="publc_clew" align="center">{$req.msg}</div>
<div id="input-table" style="width:750px; padding:0px; text-align:left;">
<form id="new_form" name="new_form"  action="{$tmpurl}" method="post" >
		<ul style="margin-left:150px;padding-bottom:5px;">
			<li>
			<table cellpadding="1" cellspacing="3">
			<colgroup>
				<col width="20%" />
				<col width="30%" />
				<col width="1%" />
				<col width="13%" />
				<col width="30%" />
				<col width="5%"	/>
			</colgroup>
				<tr ><td>Start Date:</td>
					<td>
					<input type="text"  style="width:80px;+height:21px;+padding:2px 0 0 0;" id="start_date" name="start_date" maxlength="12" value="{$req.selected.start_date}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.new_form.start_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>&nbsp;<select name="s_hour" style="width:46px;">{foreach from=$hour item=h key=k}
						<option value="{$k}" {if $req.selected.s_hour eq $k}selected{/if}>{$h}</option>
					{/foreach}</select>
					</td>
					<td>&nbsp;</td>
					<td>End Date:&nbsp;
					</td>
					<td><input type="text"  style="width:80px;+height:21px;+padding:2px 0 0 0;" id="end_date" name="end_date" maxlength="12" value="{$req.selected.end_date}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.new_form.end_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>&nbsp;<select name="e_hour" style="width:46px;>{foreach from=$hour item=h key=k}
							<option value="{$k}" {if $req.selected.e_hour eq $k}selected{/if}>{$h}</option>
						{/foreach}</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>Payment Method:&nbsp;</td>
					<td><select name="paymethod" style="width:168px;">
							<option value="" {if $req.selected.paymethod eq ""}selected{/if}>All</option>
							<option value="1" {if $req.selected.paymethod eq "1"}selected{/if}>Cheque</option>
							<option value="2" {if $req.selected.paymethod eq "2"}selected{/if}>Paypal</option>
						</select></td>
					<td>&nbsp;</td>
					<td>User Type:&nbsp;</td>
					<td><select name="usertype" style="width:168px;">
						<option selected="" value="-1" {if $req.selected.usertype eq '-1' or $req.selected.usertype eq ""}selected{/if}>All</option>
						<option value="0" {if $req.selected.usertype eq '0'}selected{/if}>Buy & Sell</option>
						<option value="1" {if $req.selected.usertype eq '1'}selected{/if}>Real Estate</option>
						<option value="2" {if $req.selected.usertype eq '2'}selected{/if}>Automotive</option>
						<option value="3" {if $req.selected.usertype eq '3'}selected{/if}>Job Market</option>
						</select></td>
				</tr>
				<tr style="margin-bottom:5px;">
                
                {if $req.disp_from eq 'yes'}
				
				<td valign='top'>State:&nbsp;</td>
					<td valign='top'>
						<select name="state[]" style="width:168px; height:100px;" multiple="multiple" onchange="getcollege(this);">
                            {html_options options=$state selected=$req.selected.state}
						</select>
					</td>
                    <td>&nbsp;</td>	
                    {/if}
                
					<td {if $req.disp_from eq 'yes'}valign='top'{/if}><div style="height:20px;+height:25px;+padding-top:2px;">Nickname:&nbsp;</div>
                    {if $req.disp_from eq 'yes'}
                    <div style="height:20px;+height:25px;+padding-top:2px;">Status:</div>
                    {/if}
					</td>
					<td {if $req.disp_from eq 'yes'}valign='top'{/if}>
					<input type="text" name="nickname" value="{$req.selected.nickname}" style="width:168px;"/>
                    {if $req.disp_from eq 'yes'}
                    <select name="status" style="width:168px; margin-top:3px;">
						<option value="" {if $req.selected.status eq ''}selected{/if}>All</option>
						<option value="Charity" {if $req.selected.status eq 'Charity'}selected{/if}>Charity</option>
                		<option value="School" {if $req.selected.status eq 'School'}selected{/if}>School</option>
                		<option value="Standard" {if $req.selected.status eq 'Standard'}selected{/if}>Standard</option>
					</select>
                    {/if}
				</td>

                {if $req.disp_from eq 'yes'}
				</tr>
				<tr>
					<td>College:&nbsp;</td>
				<td id="collegetd"><select name='college' style="width:168px;">
					<option value="" {if $req.selected.college eq ''}selected{/if}>All</option>
					{foreach from=$college item=cl key=k}
						<option value="{$k}" {if $req.selected.college eq $k}selected{/if}>{$cl}</option>
					{/foreach}
				</select></td>
				{/if}
				<td>&nbsp;</td>				
				<td colspan="2" align="left"><input type="submit" name="act_report" value="Search" class="hbutton"/>&nbsp;<input type="submit" name='act_report' value="Export" class="hbutton" /></td>
				<td></td>
				</tr>
	
				</table>
				</ul>
		<input type="hidden" name="opt" value="search"/>
		</form>
</div>

	<div align="center" style="border-bottom-color:#999999;margin-bottom:20px;" id="ref_list">
	{if $req.disp eq 'payment'}
		{include file='admin_refer_payment_list.tpl'}
	{elseif $req.disp eq 'payreport'}
		{include file='admin_payment_report.tpl'}
	{else}
		{include file='admin_refer_list.tpl'}
	{/if}
	
	</div>
	<input name="pageno" type="hidden" id="pageno" value="{$req.reportlist.pageno}"/>


<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>