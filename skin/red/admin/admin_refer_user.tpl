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
	{include file="admin_refer_userlist.tpl"}
</div>
<div align="center" style="padding-top:10px;">
<table>
<tr>
<td>
<form action="" method="post" style="font-size:12px;" onSubmit="checkSendform(this);return false;">
Payment: <span style="color:#FF0000; margin-right:100px;">{if $req.statu.status eq '2'}Sent{else}{if $req.statu.status eq '1'}{if $req.statu.paymethod eq '1'}Cheque{elseif $req.statu.paymethod eq '2'}Paypal{else}N/A{/if}{else}N/A{/if}{/if}</span>	Send Amount:&nbsp;<input type="text" name="amount" id="amount" class="inputB"/>&nbsp;<input type="submit" value="Send" class="hbutton"/>
	<input type="hidden" name="request_send" value="send" />
</form>
</td><td><form action="" method="post"><input type="submit" name='act_report' value="Export" class="hbutton" /></form></td></tr></table>
</div>