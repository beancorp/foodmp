{literal}
<style type="text/css">
	#user_ref_list{
		text-align:center;
		witdh:750px;
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
	.point-title {font-size:16px;}
</style>
<script language="javascript">
function checkSendform(obj){
	var money = document.getElementById('amount').value;
	if(money==""){
		alert('Send Amount is required.');
	}else{
		if(confirm('Are you sure you want to send $'+money+'?')){
			obj.submit();
		}
	}
}
</script>

{/literal}
<div>
	<div style="color:#FF0000; text-align:center">{$req.msg}</div>
</div>
<div id="refcontent" align="center">
	<table cellpadding="0" cellspacing="0" width="800">
		<colgroup>
			<col width="250px"/>
			<col width="150px"/>
			<col width="150px"/>
			<col width="150px"/>
			<col width="100px"/>
		</colgroup>
		<tr><td colspan="4" align="left"><div style="margin-bottom:10px;" id="body-control-title">
		Total Point Score: {$req.pointinfo.total_points} &nbsp; &nbsp; Rank: {$req.pointinfo.rank}
		</div></td>
		<td valign="top" align="right"><a href="/admin/?act=race&cp=race_list" style="text-decoration:none;">&lt;&lt;back</a></td>
		</tr>
        <tr><td colspan="4" align="left">
        <div style="margin-bottom:10px;" class="point-title">
		SOC Listing / Referral Points
		</div></td>
		</tr>
		<tr>
			<td class="tabletop">Nickname</td>
			<td class="tabletop" colspan="3">Items Listed</td>
			<td class="tabletop">Points</td>
		</tr>
        {if $req.pointinfo.spec && $req.pointinfo.spec.point > 0}
		<tr>
			<td class="tablelist">{$req.pointinfo.bu_nickname|truncate:62:"..."}&nbsp;</td>
			<td class="tablelist" colspan="3">1</td>
			<td class="tablelist">{$req.pointinfo.spec.point}&nbsp;</td>
		</tr>
        {/if}
        {if ($req.pointinfo.product_feetype eq 'year' && $req.pointinfo.total_items > 0 && $req.pointinfo.total_list_points > 0) || !($req.pointinfo.spec && $req.pointinfo.spec.point > 0) || $req.pointinfo.attribute eq 0 || $req.pointinfo.attribute eq 5}
		<tr>
			<td class="tablelist">{$req.pointinfo.bu_nickname|truncate:62:"..."}&nbsp;</td>
			<td class="tablelist" colspan="3">{$req.pointinfo.total_items}</td>
			<td class="tablelist">{$req.pointinfo.total_list_points}&nbsp;</td>
		</tr>
        {/if}
		<tr>
			<td class="tabletop">Referral Nickname</td>
			<td class="tabletop">Referral Points</td>
			<td class="tabletop">Market Joined</td>
			<td class="tabletop">Items Listed</td>
			<td class="tabletop">Points</td>
		</tr>
		{if $req.pointinfo.ref_list}
		{foreach from=$req.pointinfo.ref_list item=l}
        {if $l.total_rp_points>0}
		<tr>
			<td class="tablelist">{$l.bu_nickname}&nbsp;</td>
			<td class="tablelist">{$l.ref_points}</td>
			<td class="tablelist">{$l.market}</td>
			<td class="tablelist">{$l.total_items}</td>
			<td class="tablelist">{$l.total_rp_points}</td>
		</tr>
        {/if}
		{/foreach}
		{/if}
	<tr height="30">
	<td align="right" colspan="5"> Sub total: {$req.pointinfo.lp_points}&nbsp;&nbsp;&nbsp;</td>
	</tr>
	</table>
    
	<table cellpadding="0" cellspacing="0" width="800">
		<colgroup>
			<col width="250px"/>
			<col width="150px"/>
			<col width="150px"/>
			<col width="150px"/>
			<col width="100px"/>
		</colgroup>
        <tr><td colspan="4" align="left">
        <div style="margin-bottom:10px;" class="point-title">
		Bonus Points
		</div></td>
		</tr>
		<tr>
			<td class="tabletop" colspan="2">Partner Site</td>
			<td class="tabletop" colspan="2">Answer</td>
			<td class="tabletop">Points</td>
		</tr>
		{if $req.pointinfo.bp_list}
		{foreach from=$req.pointinfo.bp_list item=l}
		<tr>
			<td class="tablelist" colspan="2">{if $l.type eq '18'}Existing Member Bonus{else}{$l.site_name|truncate:62:"..."}{/if}&nbsp;</td>
			<td class="tablelist" colspan="2">{if $l.type neq '18'}<img src="/skin/red/images/race/{if $l.is_correct}reserve_icon_admin.png{else}reserve_no_icon.png{/if}" />{/if}&nbsp;</td>
			<td class="tablelist">{$l.point}&nbsp;</td>
		</tr>
		{/foreach}
		{/if}
	<tr height="30">
	<td align="right" colspan="5"> Sub total: {$req.pointinfo.bp_points}&nbsp;&nbsp;&nbsp;</td>
	</tr>
	</table>
</div>
<div align="right" style="padding-top:10px;"></div>