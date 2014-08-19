{literal}
<style type="text/css">
	#helpcontent b{background-color:#FFFF99;}
	h3.content b{font-size:14px;font-weight:bold;color:#3B307C;background-color:#FFFF99;}
	p.bigger a em b{font-size:14px;font-weight:bold;color:#3B307C;background-color:#FFFF99;}
</style>
<script language="javascript">
	function checkform(){
		
	}
</script>
{/literal}
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr><td>
<form method="GET" style=" background:url(/skin/red/images/h-state-cn2.jpg) no-repeat;" id="statesearch" action="" onsubmit="javascript:return checkform();">
	<input type="hidden" name="cp" value="newfaq"/>
	<table style="width:510px;height:80px;" cellpadding="0" cellspacing="0">
	<tr><td width="100%">
		<table width="100%" border="0" cellpadding="0" cellspacing="2" style="margin-top:5px;">
		<tr><td>&nbsp;</td>
			<td></td></tr>
		<tr><td ><div style="margin:0 0 6px 2px;"><strong style="color:#3E337F;">Search Help</strong></div>
		<div style="width:297px;margin:0 0 0 3px;+margin:0 0 0 2px;"><input type="text" name="helpkeywords" maxlength="100" class="inputB" style="width:200px;float:left;" value="{$req.helpkeywords}" /><input type="image" src="skin/red/images/bu-search.gif" style=" margin-left:5px;float:left"/><div style="clear:both"></div></div>
				</td><td><div style="font-size:10px;"><strong style="font-size:10px;">Notes:</strong><br/>1. Try to avoid using symbols like /<>() etc. <br/>2. The system will ignore any keyword less than 3 characters long.</div></td></tr>
</table>
	</td></tr>
	</td></tr>
	</table>
</form>
</td></tr>
  <tr>
	<td>
	{if $isinfo eq '1'}{include file='faq_info.tpl'}{else}{include file='faq_list.tpl'}{/if}</td>
  </tr>
</table>