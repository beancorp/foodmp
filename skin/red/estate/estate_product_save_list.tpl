{literal}
<script language="javascript">
function checkallitem(){
	if($('#allcheck').attr('checked')){
		$("input[@name='ckpid[]']").attr('checked',true);
	}else{
		$("input[@name='ckpid[]']").attr('checked',false);
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
			default:
				break;		
		}
	}else{
		alert("Please select items.");				
	}
	
	return false;
}
</script>
{/literal}
<form id="listForm" name="listForm" action="" method="post" onsubmit="return false;">
<table cellpadding="0" cellspacing="0" width="98%" style="margin-bottom:10px;">
		<tr>
		<td align="right"><input type="submit" name="multAct" onclick="multcheckform(this);" value="Delete"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Publish"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Unpublish"/></td>
		<input type="hidden" value="" id="multcp" name="multcp"/>
		</tr>
		</table>
<table cellpadding="0" cellspacing="0" width="98%" id="myproducts" class="sortable">
  <colgroup>
  <col width="2%" />
  <col width="2%" />
  <col width="10%" />
  <col width="20%" />
  <col width="9%" />
  <col width="9%" />
  <col width="8%" />
  <col width="12%" />
  <col width="12%" />
  <col width="15%" />
  </colgroup>
  <thead>
    <tr>
	  <th class="unsortable" align="left"><input type="checkbox" id="allcheck" onclick="checkallitem()" value="1" /></th>
      <th class="unsortable" align="left">&nbsp;</th>
      <th class="unsortable" align="left">Item</th>
      <th align="left">{$lang.tt.itemname}</th>
      <th align="left">{$lang.tt.enabled}</th>
      <th align="left">{$lang.tt.featured}</th>
      <th align="left">{if $req.select.sortby == 'price_asc'}<a href="/estate/?act=product&step={$req.select.step}&sortby=price_desc">{$lang.tt.price}</a><img src="/skin/red/images/arrow-down.gif" border="0" /> {elseif $req.select.sortby == 'price_desc'} <a href="/estate/?act=product&step={$req.select.step}&sortby=price_asc">{$lang.tt.price}</a><img src="/skin/red/images/arrow-up.gif" border="0" /> {else} <a href="/estate/?act=product&step={$req.select.step}&sortby=price_asc">{$lang.tt.price}</a> {/if}</th>
      
	  <th align="left">{if $req.select.sortby == 'datec_asc'}<a href="/estate/?act=product&step={$req.select.step}&sortby=datec_desc">{$lang.tt.dateAdd}</a><img src="/skin/red/images/arrow-down.gif" border="0" />{elseif $req.select.sortby == 'datec_desc'}<a href="/estate/?act=product&step={$req.select.step}&sortby=datec_asc">{$lang.tt.dateAdd}</a><img src="/skin/red/images/arrow-up.gif" border="0" />{else}<a href="/estate/?act=product&step={$req.select.step}&sortby=datec_asc">{$lang.tt.dateAdd}</a> {/if}</th>
      
	  <th align="left">{if $req.select.sortby == 'dateexpired_asc'}<a href="/estate/?act=product&step={$req.select.step}&sortby=dateexpired_desc">{$lang.tt.dateExpired}</a><img src="/skin/red/images/arrow-up.gif" border="0" />{elseif $req.select.sortby == 'dateexpired_desc'}<a href="/estate/?act=product&step={$req.select.step}&sortby=dateexpired_asc">{$lang.tt.dateExpired}</a><img src="/skin/red/images/arrow-down.gif" border="0" />{else}<a href="/estate/?act=product&step={$req.select.step}&sortby=dateexpired_asc">{$lang.tt.dateExpired}</a> {/if}</th>
      <th class="unsortable">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
  
  {foreach from=$req.list item=p}
  <tr>
  	<td><input type="checkbox" value="{$p.pid}"  name="ckpid[]" /></td>
    <td>{if $p.pid==$req.select.pid}<img src="/skin/red/images/arrow.gif" border="0" />{else}<img src="/skin/red/images/spacer.gif" border="0" />{/if}</td>
    <td><img src="{$p.simage.text}" width="{$p.simage.width}" height="{$p.simage.height}" /></td>
    <td> {$p.item_name|truncate:30:"..."}</td>
    <td><input name="enabled" type="checkbox" disabled {if $p.enabled}checked{/if} /></td>
    <td><input name="checked" type="checkbox" disabled {if $p.featured}checked{/if} /></td>
    <td><strong>${$p.price}</strong></td>
    <td>{$p.datecfm}</td>
    <td>{$p.renewal_date|date_format:"$PBDateFormat"}</td>
    <td><ul id="icons-products">
      {if $p.status eq 0}
      <li><a href="javascript:document.location.replace('/estate/?act=product&step={$req.select.step}&sortby={$req.select.sortby}&pid={$p.pid}');void(0);" title="{$lang.but.edit}"><img src="/skin/red/images/icon-edits.gif" /></a></li>
      <li><a href="javascript:deletes('/estate/?act=product&step={$req.select.step}&op=del&sortby={$req.select.sortby}&pid={$p.pid}');void(0);"title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
      <li><a href="/soc.php?cp=dispro&pre=1&StoreID={$p.StoreID}&proid={$p.pid}" target="_blank"title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
      {/if}
      {if $p.status eq 2}
      <li><a href="javascript:deletes('/estate/?act=product&step={$req.select.step}&op=del&sortby={$req.select.sortby}&pid={$p.pid}');void(0);" title="{$lang.but.edit}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
      <li><a href="/soc.php?cp=dispro&pre=1&StoreID={$p.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
      {/if}
    </ul></td>
  </tr>
  {/foreach}
  </tbody>
</table>
<table cellpadding="0" cellspacing="0" width="98%" style="margin-top:10px;">
		<tr>
		<td align="right"><input type="submit" name="multAct" onclick="multcheckform(this);" value="Delete"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Publish"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Unpublish"/></td>
		</tr>
		</table>
</form>
<br>