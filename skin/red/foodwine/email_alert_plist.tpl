<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
{literal}
<script type="text/javascript">
$(document).ready(function(){

	

});



function SetImg(imgN){
	MM_swapImage('MainIMG2','',imgN,1);
	document.mainForm.mainImageH.value = imgN ;
}

function changeType(obj)
{
	var type = obj.value;
	if('stock' == type) {
		
	
	}
	else {
	
	
	}
	return true;
}

function checkquanty(type)
{
	/*obj = $("#stockQuantity");
	if(1 == type) {
		obj.val('1');
		obj.attr('disable', false);
		obj.attr('readonly', false);
	}
	else {
		obj.val('0');
		obj.attr('disable', true);
		obj.attr('readonly', true);
	}
	return true;*/
}


function checkForm(obj)
{


	var msgArr = new Array();
	if('' == obj.item_name.value) {
		msgArr.push('Item Name is required.');
	}
	
	var r = /^[0-9]*[1-9][0-9]*$/;
	if('' == obj.price.value) {
		msgArr.push('Price is required.');
	}
	else if(-1 == obj.price.value.search(/^[0-9]?[0-9]*\.?[0-9]*$/)) {
		msgArr.push('price has to be a whole number without decimals.');
	}
	/*if(!(obj.stockQuantity.value >= 0)) {
		msgArr.push('');
	}*/
	if('' == obj.category.value) {
		msgArr.push('Category is required.');
	}
	if('' == obj.description.value) {
		msgArr.push('Description is required.');
	}
	
	if(msgArr.length > 0) {
		alert(msgArr.join('\n'));
		return false;
	}
	return true;
}


function multcheckform(form)
{
	var length = $('#multform input[name="pid[]"]:checked').length;
	if(0 == length) {
		alert('Please select items.');
		return false;
	}
	document.getElementById('multform').submit();
	return true;
}

function checkallitem(status)
{
	$.each($('#multform input[name="pid[]"]'), function(i,n){
		n.checked = status;
	});
	
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
	}
</style>
{/literal}
<div>
<form action="/foodwine/?act=emailalerts&cp=send" method="post" onsubmit="" id="multform" />
<h3 style="font-size:16px; color:#666; font-weight:bold;">Your Products</h3>
<a name="list">
<table cellpadding="0" cellspacing="0" width="100%" id="myproducts" class="sortable" style="">
  <colgroup>
  <col width="2%"/>
  <col width="2%" />
  <col width="10%" />
  <col width="22%" />
  <col width="10%" />
  <col width="10%" />
  <col width="8%" />
  <col width="10%" />
  <col width="10%" />
  </colgroup>
  <thead>
    <tr>
      <td colspan="9"><div style="background-color: rgb(238, 238, 238); width:200px; height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
          <ul class="tabtmp">
            <li class="active_tab"><a href="#" style="font-weight:bold;color:#FFF;text-decoration:none;">My Products</a></li>
            
          </ul>
          <div style="clear: both;"></div>
        </div></td>
    </tr>
    <tr>
      <th class="unsortable"><input type="checkbox" id="allcheck" onclick="checkallitem(this.checked)" value="1" /></th>
      <th class="unsortable">&nbsp;</th>
      <th class="unsortable">Item</th>
      <th>Name</th>
      <th>Code</th>
      <th>Onsale Status</th>
      <th>Featured</th>
      <th>Price</th>
      <th>Date Added</th>
    </tr>
  </thead>
  <tbody>
  
  {if $productList}
  {foreach from=$productList.items item=p}
  
  <tr>
    <td> {if $p.status eq 0 || $p.status eq 2}
      <input type="checkbox" value="{$p.pid}"  name="pid[]" class="b4js" />
      {/if}</td>
    <td>{if $p.pid==$smarty.get.pid}<img src="/skin/red/images/arrow.gif" border="0" />{else}<img src="/skin/red/images/spacer.gif" border="0" />{/if}</td>
    <td><img src="{$p.small_image}" width="61" height="35" /></td>
    <td> {$p.item_name|truncate:30:"..."}</td>
    <td> {$p.p_code}</td>
    <td>
		{if $p.sale_state eq 'soon'}
			Soon
		{elseif $p.stock_quantity > 0}
			Active
		{else}
			Sold
		{/if}
	</td>
    <td><input type="checkbox" {if $p.isfeatured}checked{/if} disabled /></td>
    <td><strong>${$p.price}</strong></td>
    <td>{$p.datec|date_format:"%m/%d/%Y"}</td>
  </tr>
  {/foreach}
  {/if}
  </tbody>
  
</table>
<table cellpadding="0" cellspacing="0" width="98%" style="margin-top:10px;">
  <colgroup>
  <col width="80%" />
  <col width="20%" />
  </colgroup>
  <tr>
    <td></td>
    <td align="right">
    	<input type="hidden" name="type" value="hotbuy" />
    	<input style="cursor:pointer; background-color:#9E99C1; color:#fff" type="button" name="multAct" onclick="multcheckform();" value="Send"/>
      <!--<input type="submit" name="multAct" onclick="multcheckform(this);" value="Publish"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Unpublish"/>--></td>
  </tr>
</table>
</form>
</div>