{literal}
<script language="javascript">
function changeList(obj)
{
	$("input[@name='ckpid[]']").attr('checked',false);
	$('#allcheckpaid').attr('checked',false);
	$('#allcheck').attr('checked',false);
	
	if(obj == 'paidproducts') {
		$("#paidproducts").show();
		$("#unpaidproducts").hide();
	} else {
		$("#unpaidproducts").show();
		$("#paidproducts").hide();

	}
}

function checkallitem(){
	if($('#allcheckpaid').attr('checked')){
		$("#paidproducts input[@name='ckpid[]']").attr('checked',true);
	}else{
		$("#paidproducts input[@name='ckpid[]']").attr('checked',false);
	}
	
	if($('#allcheck').attr('checked')){
		$("#unpaidproducts input[@name='ckpid[]']").attr('checked',true);
	}else{
		$("#unpaidproducts input[@name='ckpid[]']").attr('checked',false);
	}
}

function multcheckform(obj){
	if($("input[@name='ckpid[]'][@checked]").length>0){
		switch(obj.value){
			case 'Delete':
				if(confirm('Are you sure you want to delete these items?')){
					$('#multcp').val('delete');
					$('#listForm').submit();
				}
				break;
			case 'Publish':
				if(confirm('Are you sure you want to publish these items?')){
					$('#multcp').val('publish');
					$('#listForm').submit();
				}
				break;
			case 'Unpublish':
				if(confirm('Are you sure you want to unpublish these items?')){
					$('#multcp').val('unpublish');
					$('#listForm').submit();
				}
				break;
			case 'Pay For Listing':
				$('#listForm').attr("action","/auto/?act=product&cp=pay&step=4");
				$('#listForm').submit();
				break;
			default:
				break;		
		}
	}else{
		alert("Please select items.");				
	}
	
	return false;
}
</script>
<style>
	.tabtmp{
		list-style:none;
		margin:0;
		float:left;
	}
	.tabtmp li{
		list-style:none;
		width:200px;
		height:40px;
		line-height:40px;
		text-align:center;
		float:left;
		cursor:pointer;
		font-weight:bold;
	}
	.tabtmp li.active_tab{
		background-color:#9E99C1;
		font-weight:bold;
		color:#FFF;
		text-decoration:none;
	}
	.input-button{cursor:pointer}	
</style>
{/literal}
<form id="listForm" name="listForm" action="" method="post" onsubmit="return false;">
<input type="hidden" value="" id="multcp" name="multcp"/>
<table cellpadding="0" cellspacing="0" width="98%" id="paidproducts" class="sortable">
  <colgroup>
   <col width="2%" />
  <col width="2%" />
  <col width="10%" />
  <col width="25%" />
  <col width="10%" />
  <col width="10%" />
  <col width="11%" />
  <col width="15%" />
  <col width="22%" />
  </colgroup>
  <thead>
  	<tr>
        <td colspan="7">
        <div style="background-color: rgb(238, 238, 238); width:400px; height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
<ul class="tabtmp">
    <li class="active_tab">Paid Products</li>
    <li onclick="changeList('unpaidproducts');" id="disAuctions" style="width:200px;">Unpaid Products</li>
</ul>
<div style="clear: both;"></div>
        </div>
        </td>
        
          <td colspan="10" style="border:none;" id="op_paid">
          
        <table cellpadding="0" cellspacing="0" width="100%" style="margin-top:10px;">
                <tr>
                <td align="right" style="border:none;"><input type="submit" name="multAct" onclick="multcheckform(this);" value="Delete"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Publish"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Unpublish"/></td>
                </tr>
                </table>
          </td>
    </tr>
    <tr height="60">
	  <th class="unsortable" align="left"><input type="checkbox" id="allcheckpaid" onclick="checkallitem()" value="1" /></th>
      <th class="unsortable" align="left">&nbsp;</th>
      <th class="unsortable" align="left">Item</th>
      <th align="left">{$lang.tt.itemname}</th>
      <th align="left">{$lang.tt.enabled}</th>
      <th align="left">{$lang.tt.featured}</th>
      <th align="left">{if $req.select.sortby == 'price_asc'}<a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=price_desc">{$lang.tt.price}</a><img src="/skin/red/images/arrow-down.gif" border="0" /> {elseif $req.select.sortby == 'price_desc'} <a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=price_asc">{$lang.tt.price}</a><img src="/skin/red/images/arrow-up.gif" border="0" /> {else} <a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=price_asc">{$lang.tt.price}</a> {/if}</th>
      
	  <th align="left">{if $req.select.sortby == 'datep_desc'}<a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=datep_desc">{$lang.tt.datePay}</a><img src="/skin/red/images/arrow-down.gif" border="0" />{elseif $req.select.sortby == 'datep_asc'}<a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=datep_asc">{$lang.tt.datePay}</a><img src="/skin/red/images/arrow-up.gif" border="0" />{else}<a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=datep_asc">{$lang.tt.datePay}</a> {/if}</th>
      <th class="unsortable">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
  
  {foreach from=$req.list item=p}
  {if $p.pay_status eq '2' || ($p.pay_status eq '1' && $p.renewal_date >= $cur_time)}
  <tr>
  	<td><input type="checkbox" value="{$p.pid}"  name="ckpid[]" /></td>
    <td>{if $p.pid==$req.select.pid}<img src="/skin/red/images/arrow.gif" border="0" />{else}<img src="/skin/red/images/spacer.gif" border="0" />{/if}</td>
    <td><img src="{$p.simage.text}" width="{$p.simage.width}" height="{$p.simage.height}" /></td>
    <td> {$p.item_name|truncate:30:"..."}</td>
    <td><input name="enabled" type="checkbox" disabled {if $p.enabled}checked{/if} /></td>
    <td><input name="checked" type="checkbox" disabled {if $p.featured}checked{/if} /></td>
    <td><strong>${$p.price}</strong></td>
    <td>{$p.datepay}</td>
    <td><ul id="icons-products">
      {if $p.status eq 0}
      <li><a href="javascript:document.location.replace('/auto/?act=product&step={$req.select.step}&sortby={$req.select.sortby}&pid={$p.pid}&options=edit#optionsedit');void(0);" title="{$lang.but.edit}"><img src="/skin/red/images/icon-edits.gif" /></a></li>
      <li><a href="javascript:deletes('/auto/?act=product&step={$req.select.step}&op=del&sortby={$req.select.sortby}&pid={$p.pid}&options=edit#optionsedit');void(0);" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
      <li><a href="/soc.php?cp=dispro&pre=1&StoreID={$p.StoreID}&proid={$p.pid}&options=edit#optionsedit" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
      {/if}
      {if $p.status eq 2}
      <li><a href="javascript:deletes('/auto/?act=product&step={$req.select.step}&op=del&sortby={$req.select.sortby}&pid={$p.pid}&options=edit#optionsedit');void(0);" title="{$lang.but.edit}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
      <li><a href="/soc.php?cp=dispro&pre=1&StoreID={$p.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
      {/if}
    </ul></td>
  </tr>
  {/if}
  {/foreach}
  <tr>
  <td colspan="10" style="border:none;">
  
<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:10px;">
		<tr>
		<td align="right" style="border:none;"><input type="submit" name="multAct" onclick="multcheckform(this);" value="Delete"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Publish"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Unpublish"/></td>
		</tr>
		</table>
  </td>
  </tr>
  </tbody>
</table>
<table cellpadding="0" cellspacing="0" width="98%" id="unpaidproducts" class="sortable" style="display:none">
  <colgroup>
   <col width="2%" />
  <col width="2%" />
  <col width="10%" />
  <col width="35%" />
  <col width="13%" />
  <col width="15%" />
  <col width="20%" />
  </colgroup>
  <thead>
  	<tr>
        <td colspan="5">
        <div style="background-color: rgb(238, 238, 238); width:400px; height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
<ul class="tabtmp">
    <li id="disAuctions" onclick="changeList('paidproducts');" style="width:200px;">Paid Products</li>
    <li class="active_tab">Unpaid Products</li>
</ul>
<div style="clear: both;"></div>
        </div>
        </td>
          <td colspan="10" style="border:none;">
  
        <table cellpadding="0" cellspacing="0" width="100%" style="margin-top:10px;">
                <tr>
                <td align="right" style="border:none;"><input type="submit" name="multAct" onclick="multcheckform(this);" value="Delete"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Pay For Listing"/></td>
                </tr>
                </table>
          </td>
    </tr>
    <tr height="60">
	  <th class="unsortable" align="left"><input type="checkbox" id="allcheck" onclick="checkallitem()" value="1" /></th>
      <th class="unsortable" align="left">&nbsp;</th>
      <th class="unsortable" align="left">Item</th>
      <th align="left">{$lang.tt.itemname}</th>
      <th align="left">{if $req.select.sortby == 'price_asc'}<a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=price_desc">{$lang.tt.price}</a><img src="/skin/red/images/arrow-down.gif" border="0" /> {elseif $req.select.sortby == 'price_desc'} <a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=price_asc">{$lang.tt.price}</a><img src="/skin/red/images/arrow-up.gif" border="0" /> {else} <a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=price_asc">{$lang.tt.price}</a> {/if}</th>
      
	  <th align="left">{if $req.select.sortby == 'datec_asc'}<a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=datec_desc">{$lang.tt.dateAdd}</a><img src="/skin/red/images/arrow-down.gif" border="0" />{elseif $req.select.sortby == 'datec_desc'}<a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=datec_asc">{$lang.tt.dateAdd}</a><img src="/skin/red/images/arrow-up.gif" border="0" />{else}<a href="/auto/?act=product&step={$req.select.step}&options={$req.select.options}&sortby=datec_asc">{$lang.tt.dateAdd}</a> {/if}</th>
      <th class="unsortable">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
  
  {foreach from=$req.list item=p}
  {if $p.pay_status eq '0' || ($p.pay_status eq '1' && $p.renewal_date < $cur_time)}
  <tr>
  	<td><input type="checkbox" value="{$p.pid}"  name="ckpid[]" /></td>
    <td>{if $p.pid==$req.select.pid}<img src="/skin/red/images/arrow.gif" border="0" />{else}<img src="/skin/red/images/spacer.gif" border="0" />{/if}</td>
    <td><img src="{$p.simage.text}" width="{$p.simage.width}" height="{$p.simage.height}" /></td>
    <td> {$p.item_name|truncate:30:"..."}</td>
    <td><strong>${$p.price}</strong></td>
    <td>{$p.datecfm}</td>
    <td><ul id="icons-products">
      {if $p.status eq 0}
      <li><a href="javascript:document.location.replace('/auto/?act=product&cp=pay&step={$req.select.step}&sortby={$req.select.sortby}&pid={$p.pid}&options=edit#optionsedit');void(0);" title="{$lang.but.pay}"><img src="/skin/red/images/icon-pay.gif" /></a></li>
      <li><a href="javascript:document.location.replace('/auto/?act=product&step={$req.select.step}&sortby={$req.select.sortby}&pid={$p.pid}&options=edit#optionsedit');void(0);" title="{$lang.but.edit}"><img src="/skin/red/images/icon-edits.gif" /></a></li>
      <li><a href="javascript:deletes('/auto/?act=product&step={$req.select.step}&op=del&sortby={$req.select.sortby}&pid={$p.pid}&options=edit#optionsedit');void(0);" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
      <li><a href="/soc.php?cp=dispro&pre=1&StoreID={$p.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
      {/if}
      {if $p.status eq 2}
      <li><a href="javascript:deletes('/auto/?act=product&step={$req.select.step}&op=del&sortby={$req.select.sortby}&pid={$p.pid}&options=edit#optionsedit');void(0);" title="{$lang.but.edit}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
      <li><a href="/soc.php?cp=dispro&pre=1&StoreID={$p.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
      {/if}
    </ul></td>
  </tr>
  {/if}
  {/foreach}
  <tr>
  <td colspan="10" style="border:none;">
  
<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:10px;">
		<tr>
		<td align="right" style="border:none;"><input type="submit" name="multAct" onclick="multcheckform(this);" value="Delete"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Pay For Listing"/></td>
		</tr>
		</table>
  </td>
  </tr>
  </tbody>
</table>
</form>
<br>