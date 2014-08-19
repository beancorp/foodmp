<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<style type="text/css">
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
{/literal}
<div align="center">This report will return all the users that are referred by the Referrer ID entered</div>
<div id="ajaxmessage" class="publc_clew" align="center">{$req.msg}</div>

<div id="input-table" style="padding:0 0 10px 320px; text-align:left; width:960px;">
<form id="new_form" name="new_form"  action="{$tmpurl}" method="post" style="width:350px" >
			<table cellpadding="1" cellspacing="3" width="350px" align="center">
				<tr><td>Referrer ID:</td>
					<td>
					<input type="text"  name="referID" maxlength="12" value="{$req.selected.referID}"/>
					</td>		
				<td align="left"><input class="hbutton" type="submit" name="act_report" value="Search"/>&nbsp;<input type="submit" name='act_report' value="Export" class="hbutton" /></td>
				</tr>
	
			</table>
		<input type="hidden" name="opt" value="search"/>
		</form>
</div>

	<div align="center" style="border-bottom-color:#999999;margin-bottom:20px;" id="ref_list">
		{include file='admin_refer_report_list.tpl'}
	</div>
	<input name="pageno" type="hidden" id="pageno" value="{$req.reportlist.pageno}"/>