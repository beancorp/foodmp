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
<script language="javascript">
function searchform(){
	document.searchForm.fromDate.value = document.mainSearch.fromDate.value;
	document.searchForm.toDate.value = document.mainSearch.toDate.value;
	xajax_getpurchaseRecords(1,xajax.getFormValues('mainSearch'));
	return false;
}

function exportFile(value)
{
	document.mainSearch.act_export.value = value;
	document.mainSearch.submit();
}
</script>
{/literal}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:820px;">
	  <form name="mainSearch" id="mainSearch" method="post" action="" onSubmit="return ;">
      <table cellspacing="3" cellpadding="0">
      	<tr height="30">
        	<td>{$lang.labelAttribute}</td>
        	<td>
            <select name="attribute" id="attribute" style="width:134px;">
            <option value="">All</option>
            {foreach from=$lang.seller.attribute item=l key=k}
            	{if $k eq '0' or $k eq '5'}<option value="{$k}">{$l.text}</option>{/if}
            {/foreach}
            </select>
    		</td>
            <td>&nbsp;</td>
        	<td>Payment Status</td>
        	<td>
            <select name="p_status" id="p_status" style="width:134px;">
            	<option value="">All</option>
            	<option value="completed">Completed</option>
            	<option value="faild">Faild</option>
            	<option value="order">Order</option>
            	<option value="paid">Paid</option>
            	<option value="pending">Pending</option>
                <option value="shipped">Shipped</option>
            </select>
    		</td>
            <td>&nbsp;</td>
        	<td>{$lang.main.lb_commission_type}</td>
        	<td>
            <select name="commission_type" id="commission_type" style="width:134px;">
            	<option value="">All</option>
            	<option value="1">{$lang.main.lb_commission_type_automatic}</option>
            	<option value="0">{$lang.main.lb_commission_type_manual}</option>
            </select>
    		</td>
            </tr>
            <tr height="30">
        	<td align="right">{$lang.payment.fromdate}</td>
        	<td><input name="fromDate" type="text" id="fromDate" size="15" value=""  readonly ><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.mainSearch.fromDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></td>
            <td>&nbsp;</td>
            <td align="right">{$lang.payment.todate}</td>
            <td colspan="3"><input name="toDate" type="text" id="toDate" size="15" value=""  readonly ><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.mainSearch.toDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></td></tr>
        <tr height="30"><td></td>
        	<td colspan="4">
            	<input type="button" class="hbutton"  value=" {$lang.but.search} " onClick="searchform()">
                <input type="hidden" class="hbutton" name="act_export" value="Export"/>
                <input type="button" class="hbutton" value=" Export " onclick="return exportFile('Export');"/>
                <input type="button" class="hbutton" value=" Export for Mass Pay " onclick="return exportFile('Masspay');"/>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr><td></td></tr>
      </table>
		
	 </form>
    <form name="searchForm" id="searchForm" method="post" action="">
    	<input type="hidden" name="fromDate"  value="{$req.fromDate}"/>
       	<input type="hidden" name="toDate" value="{$req.toDate}"/>
       	<input type="hidden" name="attribute" value="{$req.attribute}"/>
       	<input type="hidden" name="p_status" value="{$req.p_status}"/>
    </form>
	</div>
	<div id="tabledatalist" style="margin-top:20px;" >
		{include file='admin_purchase_list.tpl'}
	</div>
	</div>
	<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>