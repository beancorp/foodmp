<link rel="stylesheet" href="/js/mdialog/mdialog.css" />
<script language="javascript">
	var protype =0;
	var soc_http_host="{$soc_http_host}";
	var soc_https_host="{$secure_url}";
	var max_id = "{if $categories_num}{$categories_num}{else}1{/if}";
	var max_num = "{if $categories_num}{$categories_num}{else}1{/if}";

</script>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript" src="/skin/red/js/productupload.js"></script>
<script type="text/javascript" src="/js/mdialog/mdialog.js"></script>
<link type="text/css" href="/skin/red/css/swfupload_product.css" rel="stylesheet" media="screen" />

{literal}

<script language="javascript" type="text/javascript">
function checkAddForm(cid)
{
	if($("#category_name").val() == "")
	{
		alert("Category Name is required");
		$("#category_name").focus();
		return false;
	}
	
	$.post('registration.php',{'step':'4','cp':'checkcategoryname','fid':2,'cid':cid,'category_name':$("#category_name").val()},function(data){
		if(data) {
			alert("The Category name already exists");
		} else {
			$("#addcategoryform").submit();
		}
	});
	
	return false;
}
function showAddForm()
{
	var html = '<form id="addcategoryform" action="registration.php?step=products&cp=addcategory" method="post"><div style=" padding:10px 0; color:#FFF;">Name: <input id="category_name" name="category_name" type="text" style="border:1px solid #CCCCCC" size="30" /></div><div style="text-align:center; padding:10px 0;"><input type="hidden" name="fid" value="2" /><input style="background-color:#FFEECA; cursor:pointer" type="button" onclick="return checkAddForm(0);" value="submit" /></div></form>';
	dlgClose();
	dlgOpen('Add Category', html, 300, 150);
}

function showEditForm(cid)
{

	$.post('registration.php',{'step':'4','cp':'getcategoryname','fid':2,'cid':cid},function(data){
		if(data) {
			var html = '<form id="addcategoryform" action="registration.php?step=products&cp=addcategory&cid=' + cid + '" method="post"><div style=" padding:10px 0; color:#FFF;">Name: <input id="category_name" name="category_name" type="text" style="border:1px solid #CCCCCC" size="30" value="' + data + '" /></div><div style="text-align:center; padding:10px 0;"><input type="hidden" name="fid" value="2" /><input style="background-color:#FFEECA; cursor:pointer" type="button" onclick="return checkAddForm(' + cid + ');" value="submit" /></div></form>';
			dlgClose();
			dlgOpen('Edit Category', html, 300, 150);
		} else {
			alert("Load data error.");
		}
	});
}
</script>
<script type="text/javascript">
$(document).ready(function() {
	max_id = parseInt(max_id);
	max_num = parseInt(max_num);
	setTimeout(function(){$("#msg_show").hide();},5000);
})
function mouseEventLI(obj, flag)
{
	$('.category-oper-changeorder').hide();
	$('.category-oper-del').hide();
	if(flag) {
		$(obj).find('a').each(function(n){
			$(this).show();
		});
		$(obj).addClass("select");
	} else {
		$(obj).removeClass("select");
	}
	
	return true;
}

function delCategory(num)
{
	var total = 0;
	$("#category_ul").find('li').each(function(n){
		total++;
	});
	
	if(total == 1) {
		alert("You should have a default category.");
		return;
	}
	
	var i = 1;
	id = $("#cat_li_" + num).find('input:eq(1)').val();
	if(num && (!id || window.confirm("Are you sure you want to delete the category although it has items?"))) {
		max_num = 0;
		$("#cat_li_" + num).remove();
		$("#category_ul").find('li').each(function(n){
			if($(this).attr('id') != ('cat_li_' + num)) {
				$(this).find('label:eq(0)').html('Category ' + i);
				i++;
				max_num++;
			} 			
		});
	} 
	
	return;
}

function changeCategoryOrder(num)
{
	var finded = false;
	var cur_id = $("#cat_li_" + num).find('input:eq(0)');
	var chg_id = cur_id;
	
	var cur_cid = $("#cat_li_" + num).find('input:eq(1)');
	var chg_cid = cur_cid;
	if(num) {
		$("#category_ul").find('li').each(function(n){
			if($(this).attr('id') == ('cat_li_' + num)) {
				finded = true;
			}
			if(!finded) { 			
				chg_id = $(this).find('input:eq(0)');
				chg_cid = $(this).find('input:eq(1)');
			}
		});
	} 
	
	var tmp = $(cur_id).val();
	$(cur_id).val($(chg_id).val());
	$(chg_id).val(tmp);
	
	tmp = $(cur_cid).val();
	$(cur_cid).val($(chg_cid).val());
	$(chg_cid).val(tmp);
	
	return;
}

function addCategory()
{
	$("#category_ul").append('<li id="cat_li_' + max_id + 
			'" onmouseout="mouseEventLI(this, false);" onmouseover="mouseEventLI(this, true);">' + 
        	'<label>Category ' + (max_num + 1) + '&nbsp;</label>' + 
            '<input style="float:left;" type="text" value="" size="30" class="text" name="category_name[]" />' + 
            '<input style="float:left;" type="hidden" value="" size="30" class="text" name="cid[]" />' + 
            '<a onfocus="this.blur();" class="category-oper-del" onclick="delCategory(\'' + max_id + '\');" href="javascript:void(0);" style="display: none;">Delete</a>' + 
            '<a onfocus="this.blur();" class="category-oper-changeorder" onclick="changeCategoryOrder(\'' + max_id + '\');" href="javascript:void(0);" style="display: none;">Change order</a>' + 
        '</li>');
	max_num++;	
	max_id++;
}

function checkCategoryForm(cp)
{	
	var result = true;
	var hasCategory = false;
	$("#category_ul").find('li').each(function(n){
		hasCategory = true;
		$(this).find('input:eq(0)').val($.trim($(this).find('input:eq(0)').val()));
		if(result && $.trim($(this).find('input:eq(0)').val()) == '')
		{
			alert("Category name is required.");
			$(this).find('input:eq(0)').focus();
			result = false;
			return;
		}
	});
	if(!hasCategory) {
		alert("No category to save.");
		return;
	}
	
	if(result && hasCategory) {
		document.getElementById('categoryform').submit();
	}
	return true;
}

function SetImg(imgN){
	MM_swapImage('MainIMG2','',imgN,1);
	document.mainForm.mainImageH.value = imgN ;
}

function changeType(obj)
{
	var type = obj.value;
	if('stock' == type || type == '0') {
		{/literal}{if $req.info.sold_status}{literal}
		$("#required_price").show();
		{/literal}{else}{literal}
		$("#required_price").hide();
		{/literal}{/if}{literal}
	}
	else {	
		$("#required_price").show();
	}
	return true;
}

function checkquanty(type)
{

}


function checkForm(obj)
{
	var msgArr = new Array();
	if('' == obj.item_name.value) {
		msgArr.push('Item Name is required.');
	}
	var r = /^[0-9]*[1-9][0-9]*$/;	
	var required_price = $("#required_price").css("display");
	if('' == obj.price.value) {
		if(required_price != 'none') {
			msgArr.push('Price is required.');
		}		
	}
	else if(-1 == obj.price.value.search(/^[0-9]?[0-9]*\.?[0-9]*$/)) {		
		if(required_price != 'none') {	
			msgArr.push('price has to be a whole number without decimals.');
		}	
	}
	if('' == obj.category.value) {
		msgArr.push('Category is required.');
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


function changePriceOrder()
{
	var price_val = $("#price").val();
	var unit_val = $("#unit").val();
	var tr_price_html = $("#tr_price").html();
	var tr_unit_html = $("#tr_unit").html();
	$("#tr_price").html(tr_unit_html);
	$("#tr_unit").html(tr_price_html);
	$("#priceorder").val(1-$("#priceorder").val());
	$("#price").val(price_val);
	$("#unit").val(unit_val);
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
		background-color:#CCC;
		vertical-align:middle;
	}
	.tabtmp li.active_tab{
		background-color:#9E99C1;
	}
	.text{ border:1px solid #CCCCCC; width:30px;}
	.field{ padding-right:10px;}
	
	.change-order{ background:url(/skin/red/images/change_order1.png) no-repeat; padding:5px 0 5px 20px; line-height:50px;}
	.product-edit-h2{ font-size:16px; color:#3c347f}
	.category-list ul li{list-style:none; text-decoration:none}
	.category-list{height:auto;}
	.category-list ul{ margin:0 36px; padding:0;}
	.category-list ul li{ display:block; padding:10px 0; width:485px; list-style:none;line-height:30px; padding:3px 0; float:left}
	.category-list ul li.select{background-color:#f1f1f1; border:none;}
	.category-list ul li input.text{ border:solid 1px #ccc; width:227px; margin-bottom:5px; padding:5px; }
	.category-list ul li label{width:70px; padding:7px 0 0 7px;float:left;}
	.category-list ul li .category-oper{ padding-right:10px ;}
	.category-list ul li .category-oper-del{ background:url(/skin/red/images/foodwine/del_basket_item.png) 0px 7px no-repeat; text-decoration:none; padding-left:13px; display:none; margin:0 10px;float:left; padding-top:7px;}
	.category-list ul li .category-oper-changeorder{ text-decoration:none;padding:3px 5px 3px 17px;background:url(/skin/red/images/change_order2.png) 0px 7px no-repeat; display:none;float:left; padding-top:7px;}
	.category-oper-addmore{ position:absolute; right:140px; float:right;font-weight:bold; text-decoration:none; padding-left:13px; line-height:13px;background:url(/skin/red/images/add.png) no-repeat;}
</style>
{/literal}
{include_php file='include/jssppopup.php'}
<p id="msg_show" align="center" class="txt" {if $req.msg eq ''}style="display:none;"{/if}><font style="color:red;">{$req.msg}</font></p>
<div style="border-bottom:1px solid #CCCCCC; margin:0 0 10px;"></div>
<h2 class="product-edit-h2">Define Categories</h2>
<p id="category_define_paragraph">Type your categories within the fields below. They will be displayed in order of preference.<br />E.g. Category 1 will always be displayed first</p>
<form id="categoryform" name="categoryform" method="post" action="registration.php?step=products&cp=savecategory">
<div class="category-list">
    <ul id="category_ul" style="overflow: hidden; clear: both;">
    	{if $categories}
    	{foreach from=$categories item=cg key=k}
    	<li onmouseover="mouseEventLI(this, true);" onmouseout="mouseEventLI(this, false);" id="cat_li_{$k}">
        	<label>Category {php} echo ++$k;{/php}</label>
            <input style="float:left;" type="text" value="{$cg.category_name}" size="30" class="text" name="category_name[]" />
            <input style="float:left;" type="hidden" value="{$cg.id}" size="30" class="text" name="cid[]" />
            <a onfocus="this.blur();" href="javascript:void(0);" onclick="delCategory('{$k}');" class="category-oper-del" title="Delete">Delete</a>
            <a onfocus="this.blur();" href="javascript:void(0);" onclick="changeCategoryOrder('{$k}');" class="category-oper-changeorder" title="Change Order">Change order</a>
        </li>
        {/foreach}
        {else} 
        <li id="cat_li_0" onmouseout="mouseEventLI(this, false);" onmouseover="mouseEventLI(this, true);">
        	<label>Category 1</label>
            <input type="text" name="category_name[]" class="text" size="30" value="" style="float:left;" />
            <input type="hidden" name="cid[]" class="text" size="30" value="" style="float:left;" />
            <a title="Delete" class="category-oper-del" onclick="delCategory('0');" href="javascript:void(0);" onfocus="this.blur();" style="display: none;">Delete</a>
            <a title="Change Order" class="category-oper-changeorder" onclick="changeCategoryOrder('0');" href="javascript:void(0);" onfocus="this.blur();" style="display: none;">Change order</a>
        </li>
        {/if}
    </ul>
	<div style="overflow: hidden; clear: both; margin-left: 110px; margin-top: 10px;">
		<a onclick="return checkCategoryForm();" href="javascript:void(0);"><img src="/skin/red/images/foodwine/save-categories.png"></a>
	</div>
</div>
<div class="clear"></div>
<div style="padding-bottom:40px; position:relative">
	<a href="javascript:void(0);" onclick="addCategory();" class="category-oper-addmore" style="position:absolute; right:240px; float:right; top:6px;">Add More Categories</a>
    </div>
</form>
<div class="clear"></div>
<div style="border-bottom:1px solid #CCCCCC; margin:0 0 10px;"></div>
<h2 class="product-edit-h2">{if $foodwine_type eq 'food'}Specify Items{else}Specify Menu Items{/if}</h2>
<form style="height:600px; border-top:none; padding:0;" action="registration.php?step=products" name="mainForm" method="POST" id="uploadsomething" onsubmit="return checkForm(document.getElementById('uploadsomething'));">
  <fieldset id="uploadproduct">
  <table width="100%" border="0" cellpadding="0" cellspacing="4"> 
  	{if $foodwine_type eq 'food'}
    <tr>
      <td align="right" width="115" class="field">Item Type</td>
      <td valign="top"><input type="radio" name="type" {if $productInfo.type eq 'stock'}checked="checked"{/if} value="stock" onclick="changeType(this)" />
        &nbsp;<strong style="font-weight:bold;">{if $req.info.menu_type}Menu{else}Stock Product{/if}</strong>
        <input type="radio" name="type" value="special" {if $productInfo.type eq 'special'}checked="checked"{else}{if !isset($smarty.get.pid)}checked="checked"{/if}{/if} onclick="changeType(this)"/>
        &nbsp;<strong style="color:#FF0000">Special</strong></td>
    </tr>
    {else}
    <tr>
      <td align="right" class="field" style="color: #FF0000 !important;">Special Item</td>
      <td valign="top"><input onclick="changeType(this)" type="radio" name="is_special" value="1" {if $productInfo.is_special eq '1' || $productInfo.is_special eq ''} checked="checked"{/if} />Yes <input onclick="changeType(this)" type="radio" name="is_special" value="0" {if $productInfo.is_special eq '0'} checked="checked"{/if} /> No<span class="style11" style="padding-left:2px;"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Display on the special items.</span></span></a></span></font></span></td>
    </tr>
    {/if}
    <tr>
      <td align="right" class="field">Item Name*</td>
      <td><input name="item_name" type="text" class="text" id="item_name" size="30" value="{$productInfo.item_name|escape}"/>
        <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><span><span>These products will be available to registered customers to 'add to basket' and buy online. Enter the name of the product Eg Granny Smith Apples.</span></span></a></span></font></span></td>
    </tr>
    <tr id="noauction" style="display:{if $req.select.is_auction == 'yes'}none{else}{/if}">
      <td align="right" valign="top" style="vertical-align:top; padding-top:8px;" class="field">Price<span id="required_price" style="display: {if $productInfo.is_special eq '1' || !isset($smarty.get.pid)}inline{else}{if $productInfo.is_special eq '0'}none{/if}{/if};">*</span></td>
      <td valign="top" style="vertical-align:top; width:275px;">
      <table style="float:left">
      	{if $productInfo.priceorder eq 1}
          <tr id="tr_price">
          	<td>Unit</td>
            <td style="vertical-align:top;"><input name="unit" type="text" class="price" maxlength="20" id="unit" size="5" value="{$productInfo.unit|escape}"/></td>
            <td style="vertical-align:top;"><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">eg per kg, lbs, each, for 500g, etc.</span></span></a></span></font></span></td>
          </tr>
          <tr id="tr_unit">
            <td align="right">$</td>
            <td style="vertical-align:top;"><input name="price" type="text" class="price" id="price" value="{$productInfo.price}" size="11" maxlength="12"/></td>
            <td style="vertical-align:top;"><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Eg 12000.00<br />
              In the price field, enter decimal point only.</span></span></a></span></font></span></td>
          </tr>
        {else}
          <tr id="tr_price">
            <td align="right">$</td>
            <td style="vertical-align:top;"><input name="price" type="text" class="price" id="price" value="{$productInfo.price}" size="11" maxlength="12"/></td>
            <td style="vertical-align:top;"><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Eg 12000.00<br />
              In the price field, enter decimal point only.</span></span></a></span></font></span></td>
          </tr>
          <tr id="tr_unit">
          	<td>Unit</td>
            <td style="vertical-align:top;"><input name="unit" type="text" class="price" maxlength="20" id="unit" size="5" value="{$productInfo.unit|escape}"/></td>
            <td style="vertical-align:top;"><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">eg per kg, lbs, each, for 500g, etc.</span></span></a></span></font></span></td>
          </tr>
         {/if}
        </table>
        <table style="float:left; padding-left: 5px;">
        	<tr>
                <td rowspan="2"><a class="change-order" href="javascript:void(0);" onfocus="this.blur();" onclick="changePriceOrder();">Switch Price Display</a></td>
                <td rowspan="2"><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777; top:-1em;">Switch Price Display eg: 3 for $2.00</span></span></a></span></font></span></td>
            </tr>
        </table>
        </td>
    </tr>
	{if $foodwine_type eq 'food'}
    <tr id="" style="">
      <td align="right" class="field">On Sale </td>
      <td style="line-height:22px;"><input id="cknow" name="on_sale" type="radio" value="sell"  checked="checked"/>
        <span style="color:red;">Now</span>
        <input type="radio" name="on_sale" value="soon"  {if $productInfo.sale_state eq 'soon'}checked="checked" {/if} />
        <span style="color:red;">Soon</span> </td>
    </tr>
    <tr id="quantityline" style=" display:none;">
      <td align="right" class="field">Stock Quantity </td>
      <td style="line-height:22px; width:295px; color:red; vertical-align:middle;"><input name="stockQuantity" type="text" class="price" style="border:1px red solid;" id="stockQuantity" size="30" value="1" />
        &nbsp;<font style="font-size:11px;color:red;">You must enter a quantity. Zero equals Sold.</font></td>
    </tr>
    {/if}
    <tr valign="top">
      <td align="right" style="vertical-align:top;" class="field">Category*</td>
      <td><select name="category" class="text" id="category">
          <option value="">Select a Category</option>
          
			  {foreach from=$categories item=cg}
			  	
          <option value="{$cg.id}" {if $productInfo.category  eq $cg.id}selected="selected"{/if}>{$cg.category_name}</option>
          
			  {/foreach}
		  
        </select>
    </tr>
    <tr valign="top">
      <td align="right" valign="top" style="vertical-align:top;" class="field">Item Description<br /></td>
      <td><textarea name="description" class="inputB"  style="height:220px; width:250px;">{$productInfo.description|escape}</textarea>
      </td>
    </tr>
    <tr valign="top">
      <td align="right" class="field">Item Code</td>
      <td><input name="p_code" type="text" class="text" id="p_code" size="25" value="{$productInfo.p_code|escape}"/>
        <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">This code is for your reference for accounting. Leave blank if not necessary.</span></span></a></span></font></span></td>
    </tr>
    <tr valign="top">
      <td height="23" align="right" style="font-weight:bold;" class="field">Display on your homepage</td>
      <td><input type="checkbox" name="isfeatured" value="1" {if $productInfo.isfeatured}checked{/if}/>
      </td>
    </tr>
    <tr valign="top" id="youtubeline" {if $req.select.is_auction=='yes'}style="display:none"{/if}>
      	  <td height="23" align="right" class="field">Youtube Video</td>
          <td><textarea class="text" name="youtubevideo" style="height:30px">{$productInfo.youtubevideo}</textarea>&nbsp;<span class="style11"><a  href="/soc.php?cp=youtubeinstruction" target="_blank"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /></a></span></td>
      </tr>
    <tr valign="top">
      <td height="23" align="right" class="field">Tags</td>
      <td valign="middle"><input class="inputB" name="str_tags" style="width:228px;"  value="{$productInfo.tags}"/>
        <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Please use a comma as the separator.<br/>
        Please note the maximum number of tags is 5.</span></span></a></span></font></span> </td>
    </tr>
    <tr id="save_to_website">
    <td>&nbsp;</td>
    <td>
		<br />
		<input id="save_to_website_button" name="SubmitPic" type="image" src="/skin/red/images/bu-savetowebsite.gif" class="input-none-border" onclick="javascript:document.mainForm.cp.value='edit';" value="Save to My Website" border="0"/>
		<p style="margin:0; padding-left:5px; font-family:Arial; font-size:16px; font-weight:bold; color:red; width:265px">Click after you update every item</p>
		<br />
    </td>
    </tr>
  </table>
  </fieldset>
  <fieldset id="uploadimages" style=" padding-left:5px;display:block;">
  <script src="/skin/red/js/uploadImages.js" language="javascript"></script>
  <div style="+width:320px;">
    <table width="225">
      <tr valign="top">
        <td colspan="3"><span class="lbl"> <a id="swf_upload_1" style="float:left;" href="javascript:uploadImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" /></a>&nbsp;&nbsp;| <a href="javascript:deleteImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a> </span><span class="style11"><font face="Verdana" size="1"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;width:110px;">Click on the 'upload an image' button, in the pop-up window click 'browse' and go to the location on your computer where the image is saved, then 'upload'.</span></span></a></font></span></td>
      </tr>
	  {if $image_library}
	  <script>
		var image_library = new Array();
		{foreach from=$image_library item=image}
			image_library[{$image.product_id}] = ["{$image.product_image_small}","{$image.product_image}"];
		{/foreach}
		{literal}
			$(document).ready(function() {
				$('#image_library').change(function() {
					if ($(this).val() > 0) {
						$('#mainImage_dis').attr('src', image_library[$(this).val()][1]);
						$('#mainImage_svalue').val(image_library[$(this).val()][0]);
						$('#mainImage_bvalue').val(image_library[$(this).val()][1]);
					}
				});
			});
		{/literal}	  
	  </script>
	  <tr>
		<td colspan="3">
			<select id="image_library">
			<option value="0">Choose Existing Image</option>
			{foreach from=$image_library item=image}
				<option value="{$image.product_id}">{$image.product_name}</option>
			{/foreach}
			</select>
		</td>
	  </tr>
	  {/if}
      <tr>
        <td colspan="3"><table width="250" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td height="225" colspan="3" align="center"><img src="{if $productInfo.images.big.picture}{$productInfo.images.big.picture}{else}/template_images/products/{$req.info.subAttrib}.jpg{/if}" name="mainImage_dis" border="1" id="mainImage_dis" width="243" height="212" /></td>
            </tr>
            <tr>
              <td colspan="3" height="10"><img src="images/spacer.gif" width="1" height="1" /></td>
            <tr onmousemove="//displayUploadInterface('uploadImagesSub1',true)">
              <td align="center"><img src="{if $productInfo.images.small.0.smallPicture}{$productInfo.images.small.0.smallPicture}{else}/images/79x79.jpg{/if}" width="79" height="79" name="subImage0_dis" border="1" id="subImage0_dis" /></td>
              <td align="center"><img src="{if $productInfo.images.small.1.smallPicture}{$productInfo.images.small.1.smallPicture}{else}/images/79x79.jpg{/if}" width="79" height="79" name="subImage1_dis" border="1" id="subImage1_dis" /></td>
              <td align="center"><img src="{if $productInfo.images.small.2.smallPicture}{$productInfo.images.small.2.smallPicture}{else}/images/79x79.jpg{/if}" width="79" height="79" name="subImage2_dis" border="1" id="subImage2_dis" /></td>
            </tr>
            <tr id="uploadImagesSub1" onmouseout="//displayUploadInterface('uploadImagesSub1',false)">
              <td align="center"><a id="swf_upload_2" href="javascript:uploadImage(0, 1, 0, 'subImage0' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 0, 'subImage0' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
              <td align="center"><a id="swf_upload_3" href="javascript:uploadImage(0, 1, 1, 'subImage1' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 1, 'subImage1' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
              <td align="center"><a id="swf_upload_4" href="javascript:uploadImage(0, 1, 2, 'subImage2' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 2, 'subImage2' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
            </tr>
            <tr>
              <td colspan="3" height="10"><img src="images/spacer.gif" width="1" height="1" /></td>
            </tr>
            <tr onmousemove="//displayUploadInterface('uploadImagesSub2',true);">
              <td align="center"><img src="{if $productInfo.images.small.3.smallPicture}{$productInfo.images.small.3.smallPicture}{else}/images/79x79.jpg{/if}" width="79" height="79" name="subImage3_dis" border="1" id="subImage3_dis" /></td>
              <td align="center"><img src="{if $productInfo.images.small.4.smallPicture}{$productInfo.images.small.4.smallPicture}{else}/images/79x79.jpg{/if}" width="79" height="79" name="subImage4_dis" border="1" id="subImage4_dis" /></td>
              <td align="center"><img src="{if $productInfo.images.small.5.smallPicture}{$productInfo.images.small.5.smallPicture}{else}/images/79x79.jpg{/if}" width="79" height="79" name="subImage5_dis" border="1" id="subImage5_dis" /></td>
            </tr>
            <tr id="uploadImagesSub2" onmouseout="//displayUploadInterface('uploadImagesSub2',false);">
              <td align="center"><a id="swf_upload_5" href="javascript:uploadImage(0, 1, 3, 'subImage3' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 3, 'subImage3' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
              <td align="center"><a id="swf_upload_6" href="javascript:uploadImage(0, 1, 4, 'subImage4' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 4, 'subImage4' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
              <td align="center"><a id="swf_upload_7" href="javascript:uploadImage(0, 1, 5, 'subImage5' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 5, 'subImage5' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
            </tr>
          </table></td>
      </tr>
      <tr valign="top">
        <td valign="middle" colspan="3"><table width="250" height="35" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="258" height="35"><p>&nbsp;</p>
                <input name="mainImage_svalue" id="mainImage_svalue" type="hidden" value="{$productInfo.images.big.smallPicture}"/>
                <input name="mainImage_bvalue" id="mainImage_bvalue" type="hidden" value="{$productInfo.images.big.picture}"/>
                <input name="subImage0_svalue" id="subImage0_svalue" type="hidden" value="{$productInfo.images.small.0.smallPicture}"/>
                <input name="subImage0_bvalue" id="subImage0_bvalue" type="hidden" value="{$productInfo.images.small.0.picture}"/>
                <input name="subImage1_svalue" id="subImage1_svalue" type="hidden" value="{$productInfo.images.small.1.smallPicture}"/>
                <input name="subImage1_bvalue" id="subImage1_bvalue" type="hidden" value="{$productInfo.images.small.1.picture}"/>
                <input name="subImage2_svalue" id="subImage2_svalue" type="hidden" value="{$productInfo.images.small.2.smallPicture}"/>
                <input name="subImage2_bvalue" id="subImage2_bvalue" type="hidden" value="{$productInfo.images.small.2.picture}"/>
                <input name="subImage3_svalue" id="subImage3_svalue" type="hidden" value="{$productInfo.images.small.3.smallPicture}"/>
                <input name="subImage3_bvalue" id="subImage3_bvalue" type="hidden" value="{$productInfo.images.small.3.picture}"/>
                <input name="subImage4_svalue" id="subImage4_svalue" type="hidden" value="{$productInfo.images.small.4.smallPicture}"/>
                <input name="subImage4_bvalue" id="subImage4_bvalue" type="hidden" value="{$productInfo.images.small.4.picture}"/>
                <input name="subImage5_svalue" id="subImage5_svalue" type="hidden" value="{$productInfo.images.small.5.smallPicture}"/>
                <input name="subImage5_bvalue" id="subImage5_bvalue" type="hidden" value="{$productInfo.images.small.5.picture}"/>
                <input type="hidden" name="cp" id="cp" value="edit" />
                </td>
            </tr>
          </table></td>
      </tr>
      <tr valign="top" id="gallaryImage">
        <td colspan="3">&nbsp;</td>
      </tr>
    </table>
  </div>
  </fieldset>
  <input type="hidden" name="pid" value="{if $smarty.get.op eq 'copy'}0{else}{$productInfo.pid}{/if}"/>
  <input type="hidden" name="iscopy" value="{if $smarty.get.op eq 'copy'}1{else}0{/if}"/>
  {if $foodwine_type eq 'wine'}
  <input type="hidden" name="stockQuantity" value="1"/>
  <input type="hidden" name="type" value="wine"/>
  {/if}
  <input type="hidden" name="priceorder" id="priceorder" value="{if $productInfo.priceorder}{$productInfo.priceorder}{else}0{/if}" />
</form>
<p>&nbsp;</p>
<form action="registration.php?step=products{if $req.tab==''}&cp=delete{/if}" method="post" onsubmit="" id="multform" />
<h3 style="font-size:16px; color:#666; font-weight:bold;">{if $foodwine_type eq 'food'}Your Items{else}Your Menu Items{/if}</h3>
<a name="list"></a>
<table cellpadding="0" cellspacing="0" width="98%" id="myproducts" class="sortable" style="">
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
  <col width="15%" />
  </colgroup>
  <thead>
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
      <th class="unsortable">&nbsp;</th>
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
    <td {if $p.is_special eq 1 || $p.type eq 'special'} style="background-color: #fefebf;" {/if}><img src="{if !empty($p.small_image)}{$p.small_image}{else}/template_images/products/{$req.info.subAttrib}.jpg{/if}" width="61" /></td>
    <td {if $p.is_special eq 1 || $p.type eq 'special'} style="background-color: #fefebf;" {/if}> {$p.item_name|truncate:30:"..."} {if $p.is_special eq 1 || $p.type eq 'special'}<span style="color: #ffa500; font-weight: bold; font-style: italic;">(Special)</span>{/if}</td>
    <td {if $p.is_special eq 1 || $p.type eq 'special'} style="background-color: #fefebf;" {/if}> {$p.p_code}</td>
    <td {if $p.is_special eq 1 || $p.type eq 'special'} style="background-color: #fefebf;" {/if}>
		{if $p.sale_state eq 'soon'}
			Soon
		{elseif $p.stock_quantity > 0}
			Active
		{else}
			Sold
		{/if}
	</td>
    <td{if $p.is_special eq 1 || $p.type eq 'special'} style="background-color: #fefebf;" {/if} input type="checkbox" {if $p.isfeatured}checked{/if} disabled /></td>
    <td{if $p.is_special eq 1 || $p.type eq 'special'} style="background-color: #fefebf;" {/if}><strong>{if $p.price neq '0.00'}${$p.price}{/if}</strong></td>
    <td{if $p.is_special eq 1 || $p.type eq 'special'} style="background-color: #fefebf;" {/if}>{$p.datec|date_format:"%d/%m/%Y"}</td>
    <td><ul id="icons-products" style="margin:0 0 0 15px;">
        
        <li><a href="#" onclick="javascript:document.location.href ='{$soc_https_host}registration.php?step=products&pid={$p.pid}';" title="{$lang.but.edit}"><img src="/skin/red/images/icon-edits.gif" /></a></li>
       
        <li><a href="javascript:deletes('registration.php?step=products&cp=delete&pid[]={$p.pid}');void(0);" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
        
       
        <li><a href="/{$req.info.url_bu_name}/{$p.url_item_name}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
        <li><a href="{$soc_https_host}registration.php?step=products&pid={$p.pid}&op=copy" title="Copy"><img src="/skin/red/images/icon-copy.gif"/></a></li>
      </ul></td>
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
    <td align="right"><input type="button" name="multAct" onclick="{if $req.tab==''}multcheckform(this);{else}checkCategoryForm('deletecategory');{/if}" value="Delete" style="background-color:#CCCCCC;"/>
    </td>
  </tr>
</table>
</form>
<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="include/cal/ipopeng.php" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"> </iframe>
<p>&nbsp;</p>
<p>&nbsp;</p>
