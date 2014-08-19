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
	xajax_getpaymentRecords(1,xajax.getFormValues('mainSearch'));
	return false;
}
</script>
{/literal}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:720px;">
	  <form name="mainSearch" id="mainSearch" method="post" action="" onSubmit="return ;">
      <table cellspacing="3" cellpadding="0">
      	<tr>
        	<!--<td>{$lang.adaptivepayment.websitename}</td>
        	<td><input name="bu_name" type="text" id="bu_name" size="25" value="" ></td>
            <td>&nbsp;</td>-->
        	<td>{$lang.adaptivepayment.fromdate}</td>
        	<td><input name="fromDate" type="text" id="fromDate" size="15" value=""  readonly ><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.mainSearch.fromDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></td>
            <td>&nbsp;</td>
            <td>{$lang.adaptivepayment.todate}</td>
            <td><input name="toDate" type="text" id="toDate" size="15" value=""  readonly ><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.mainSearch.toDate);return false;" HIDEFOCUS><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt=""></a></td>
            </tr>
        <tr><td></td>
        	<td>
            	<input type="button" class="hbutton"  value=" {$lang.but.search} " onClick="searchform()">
                <input type="submit" class="hbutton" name="act_export" value=" Export "/>
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
    </form>
	</div>
	<div id="tabledatalist" style="margin-top:20px;" >
		{include file='admin_purchase_list.tpl'}
	</div>
	</div>
	<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>