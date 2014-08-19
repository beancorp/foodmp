<link rel="stylesheet" href="/js/mdialog/mdialog.css" />
<script language="javascript">
	var protype =0;
	var soc_http_host="{$soc_http_host}";
	var max_id = "{$categories_num}";
	var max_num = "{$categories_num}";

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
	
	$.post('/foodwine/',{'act':'product','cp':'checkcategoryname','fid':2,'cid':cid,'category_name':$("#category_name").val()},function(data){
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
	var html = '<form id="addcategoryform" action="/foodwine/?act=product&cp=addcategory" method="post"><div style=" padding:10px 0; color:#FFF;">Name: <input id="category_name" name="category_name" type="text" style="border:1px solid #CCCCCC" size="30" /></div><div style="text-align:center; padding:10px 0;"><input type="hidden" name="fid" value="2" /><input style="background-color:#FFEECA; cursor:pointer" type="button" onclick="return checkAddForm(0);" value="submit" /></div></form>';
	dlgClose();
	dlgOpen('Add Category', html, 300, 150);
}

function showEditForm(cid)
{

	$.post('/foodwine/',{'act':'product','cp':'getcategoryname','fid':2,'cid':cid},function(data){
		if(data) {
			var html = '<form id="addcategoryform" action="/foodwine/?act=product&cp=addcategory&cid=' + cid + '" method="post"><div style=" padding:10px 0; color:#FFF;">Name: <input id="category_name" name="category_name" type="text" style="border:1px solid #CCCCCC" size="30" value="' + data + '" /></div><div style="text-align:center; padding:10px 0;"><input type="hidden" name="fid" value="2" /><input style="background-color:#FFEECA; cursor:pointer" type="button" onclick="return checkAddForm(' + cid + ');" value="submit" /></div></form>';
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

function delCategory(num, id)
{
	var i = 1;
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
            '<input type="text" value="" size="30" class="text" name="category_name[]" />' + 
            '<input type="hidden" value="" size="30" class="text" name="cid[]" />' + 
            '<a class="category-oper-del" onclick="delCategory(\'' + max_id + '\',\'\');" href="javascript:void(0);" style="display: none;">Delete</a>' + 
            '<a class="category-oper-changeorder" onclick="changeCategoryOrder(\'' + max_id + '\');" href="javascript:void(0);" style="display: none;">Change order</a>' + 
        '</li>');
	max_num++;	
	max_id++;
}

function checkCategoryForm(cp)
{	
	var result = true;
	$("#category_ul").find('li').each(function(n){
		if(result && $(this).find('input:eq(0)').val().trim() == '')
		{
			alert("Category name is required.");
			$(this).find('input:eq(0)').focus();
			result = false;
			return;
		}
	});
	
	if(result) {
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
	if('stock' == type) {
		
	
	}
	else {
	
	
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
	
	.change-order{ background:url(/skin/red/images/change_order1.png) no-repeat; padding:5px 0 5px 20px}
	.product-edit-h2{ font-size:16px; color:#3c347f}
	.category-list ul li{list-style:none; text-decoration:none}
	.category-list{height:auto;}
	.category-list ul{ margin:0 33px; padding:0;}
	.category-list ul li{ display:block; padding:10px 0; width:485px; list-style:none; line-height:30px; padding: 3px 0;}
	.category-list ul li.select{background-color:#f1f1f1; border:none;}
	.category-list ul li input.text{ border:solid 1px #ccc; width:227px; margin-bottom:5px; padding:5px; }
	.category-list ul li .category-oper{ padding-right:10px ;}
	.category-list ul li .category-oper-del{ background:url(/skin/red/images/foodwine/del_basket_item.png) no-repeat; text-decoration:none; padding-left:13px; display:none; margin:0 10px;}
	.category-list ul li .category-oper-changeorder{ text-decoration:none;padding:3px 5px 3px 17px;background:url(/skin/red/images/change_order2.png) no-repeat; display:none;}
	.category-oper-addmore{ position:absolute; right:140px; float:right;font-weight:bold; text-decoration:none; padding-left:13px; line-height:13px;background:url(/skin/red/images/add.png) no-repeat;}
</style>
{/literal}
{include_php file='../include/jssppopup.php'}
<p id="msg_show" align="center" class="txt" {if $req.msg eq ''}style="display:none;"{/if}><font style="color:red;">{$req.msg}</font></p>
<div style="border-bottom:1px solid #CCCCCC; margin:0 0 10px;"></div>
<h2 class="product-edit-h2">Define Categories</h2>
<p>Type your categories within the fields below. They will be displayed in order of preference.<br />E.g. Category 1 will always be displayed first</p>
<form id="categoryform" name="categoryform" method="post" action="/foodwine/index.php?act=product&step=4&cp=savecategory">
<div class="category-list">
    <ul id="category_ul">
    	{if $categories}
    	{foreach from=$categories item=cg key=k}
    	<li onmouseover="mouseEventLI(this, true);" onmouseout="mouseEventLI(this, false);" id="cat_li_{$k}">
        	<label>Category {php} echo ++$k;{/php}</label>
            <input type="text" value="{$cg.category_name}" size="30" class="text" name="category_name[]" />
            <input type="hidden" value="{$cg.id}" size="30" class="text" name="cid[]" />
            <a href="javascript:void(0);" onclick="delCategory('{$k}', '{$cg.id}');" class="category-oper-del">Delete</a>
            <a href="javascript:void(0);" onclick="changeCategoryOrder('{$k}');" class="category-oper-changeorder">Change order</a>
        </li>
        {/foreach}
        {/if}
    </ul>
</div>
<div style="padding-bottom:40px; position:relative">
	<a href="javascript:void(0);" onclick="addCategory();" class="category-oper-addmore" style="position:absolute; right:262px; float:right; top:6px;">Add More Categories</a>
    <a style="float:right; position:absolute; right:100px;" onclick="return checkCategoryForm();" href="javascript:void(0);"><img src="/skin/red/images/foodwine/save-categories.png"></a>
    </div>
</form>
<div class="clear"></div>
<div style="border-bottom:1px solid #CCCCCC; margin:0 0 10px;"></div>
<h2 class="product-edit-h2">{if foodwine_type eq 'food'}Specify Items{else}Specify Menu Items{/if}</h2>
<form style="height:600px; border-top:none; padding:0;" action="" name="mainForm" method="POST" id="uploadsomething" onsubmit="return checkForm(document.getElementById('uploadsomething'));">
  <fieldset id="uploadproduct">
  <table width="100%" border="0" cellpadding="0" cellspacing="4"> 
  	{if foodwine_type eq 'food'}
    <tr>
      <td align="right">Product Type</td>
      <td valign="top"><input type="radio" name="type" value="stock" checked="checked" onclick="changeType(this)">
        &nbsp;Stock Product
        <input type="radio" name="type" value="special" {if $productInfo.type eq 'special'} checked="checked"{/if} onclick="changeType(this)">
        &nbsp;Special </td>
    </tr>
    {else}
    <tr>
      <td align="right" class="field">Special</td>
      <td valign="top"><input type="checkbox" name="is_special" value="1" {if $productInfo.is_special} checked="checked"{/if} /><span class="style11" style="padding-left:2px;"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Display on the special items.</span></span></a></span></font></span></td>
    </tr>
    {/if}
    <tr>
      <td align="right" class="field">Item Name*</td>
      <td><input name="item_name" type="text" class="text" id="item_name" size="30" value="{$productInfo.item_name|escape}"/>
        <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><span><span>These products will be available to registered customers to 'add to basket' and buy online. Enter the name of the product Eg Granny Smith Apples.</span></span></a></span></font></span></td>
    </tr>
    <tr id="noauction" style="display:{if $req.select.is_auction == 'yes'}none{else}{/if}">
      <td align="right" valign="top" style="vertical-align:top;">Price*</td>
      <td valign="top" style="vertical-align:top; width:270px;">
      <table style="float:left">
      	{if $productInfo.priceorder eq 1}
          <tr id="tr_price">
          	<td>Unit</td>
            <td style="vertical-align:top;"><input name="unit" type="text" class="price" maxlength="20" id="unit" size="5" value="{$productInfo.unit|escape}"/></td>
            <td style="vertical-align:top;"><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">eg per kg, lbs, each, for 500g, etc.</span></span></a></span></font></span></td>
          </tr>
          <tr id="tr_unit">
            <td>$</td>
            <td style="vertical-align:top;"><input name="price" type="text" class="price" id="price" value="{$productInfo.price}" size="11" maxlength="12"/></td>
            <td style="vertical-align:top;"><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Eg 12000.00<br />
              In the price field, enter decimal point only.</span></span></a></span></font></span></td>
          </tr>
        {else}
          <tr id="tr_price">
            <td>$</td>
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
        <table style="float:left; padding:17px 0 17px 5px;">
        	<tr>
                <td rowspan="2"><a class="change-order" href="javascript:void(0);" onclick="changePriceOrder();">Switch Price Display</a></td>
                <td rowspan="2"><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777; top:-1em;">Switch Price Display</span></span></a></span></font></span></td>
            </tr>
        </table>
        </td>
    </tr>

    <tr valign="middle">
    <tr>
      <td align="right" style="vertical-align:top;" class="field">Category*</td>
      <td><select name="category" class="text" id="category">
          <option value="">Select a Category</option>
          
			  {foreach from=$categories item=cg}
			  	
          <option value="{$cg.id}" {if $productInfo.category  eq $cg.id}selected="selected"{/if}>{$cg.category_name}</option>
          
			  {/foreach}
		  
        </select>
        <!--<br /><a href="#" onclick="showAddForm();" style="color:#0033FF;" title="Add Category">Add Category</a>-->
    </tr>
    <tr valign="top">
      <td align="right" valign="top" style="vertical-align:top;" class="field">Description*<br /></td>
      <td><textarea name="description" class="inputB"  style="height:220px; width:250px;">{$productInfo.description|escape}</textarea>
      </td>
    </tr>
    <tr valign="top">
      <td height="23" align="right" style="font-weight:bold;" class="field">Display on your homepage</td>
      <td><input type="checkbox" name="isfeatured" value="1" {if $productInfo.isfeatured}checked{/if}/>
      </td>
    </tr>
    <tr valign="top">
      <td align="right">Product Code</td>
      <td><input name="p_code" type="text" class="text" id="p_code" size="25" value="{$productInfo.p_code|escape}"/>
        <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">This code is for your reference for accounting. Leave blank if not necessary.</span></span></a></span></font></span></td>
    </tr>
    <tr valign="top">
      <td height="23" align="right">Tags</td>
      <td valign="middle"><input class="inputB" name="str_tags" style="width:228px;"  value="{$productInfo.tags}"/>
        <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Please use a comma as the separator.<br/>
        Please note the maximum number of tags is 5.</span></span></a></span></font></span> </td>
    </tr>
    <!--<tr valign="top">
      <td height="23" colspan="2" align="right"><input name="SubmitPic" type="image" src="/skin/red/images/bu-savetowebsite-sm.gif" class="input-none-border" onclick="javascript:document.mainForm.cp.value='edit';" value="Save to My Website" border="0"/>
                <p style="margin:0; padding-left:5px; font-family:Arial; font-size:16px; font-weight:bold; color:red; width:255px">Click after you update every item</p></td>
    </tr>-->
  </table>
  </fieldset>
  <fieldset id="uploadimages" style="border-left:solid 1px #eee; padding-left:5px;display:block;">
  <script src="/skin/red/js/uploadImages.js" language="javascript"></script>
  <div style="+width:320px;">
    <table width="225">
      <tr valign="top">
        <td colspan="3"><span class="lbl"> <a id="swf_upload_1" style="float:left;" href="javascript:uploadImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" /></a>&nbsp;&nbsp;| <a href="javascript:deleteImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a> </span><span class="style11"><font face="Verdana" size="1"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;width:110px;">Click on the 'upload an image' button, in the pop-up window click 'browse' and go to the location on your computer where the image is saved, then 'upload'.</span></span></a></font></span></td>
      </tr>
      <tr>
        <td colspan="3"><table width="250" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td height="225" colspan="3" align="center"><img src="{if $productInfo.images.big.picture}{$productInfo.images.big.picture}{else}/images/243x212.jpg{/if}" name="mainImage_dis" border="1" id="mainImage_dis" width="250" height="250" /></td>
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
                <!--<p id="auction_notes" style="{if $req.select.is_auction neq 'yes'}display:none;{/if}margin:0; padding-left:5px; font-family:Arial; font-size:14px; font-weight:bold; "><span style="font-size:16px; font-weight:bold;">Note:</span> Once a bid has been made, no settings can be changed in this auction.</p>-->
                <input name="SubmitPic" type="image" src="/skin/red/images/bu-savetowebsite-sm.gif" class="input-none-border" onclick="javascript:document.mainForm.cp.value='edit';" value="Save to My Website" border="0"/>
                <p style="margin:0; padding-left:5px; font-family:Arial; font-size:16px; font-weight:bold; color:red; width:255px">Click after you update every item</p></td>
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
  <input type="hidden" name="stockQuantity" value="1"/>
  <input type="hidden" name="type" value="wine"/>
  <input type="hidden" name="priceorder" id="priceorder" value="{if $productInfo.priceorder}{$productInfo.priceorder}{else}0{/if}" />
</form>
<!--<fieldset id="bulkupload">
     <table style="width:100%;">
		  <tr id="bulkuploadline">
			 <td colspan="2">
			 <table width="100%" border="0" cellspacing="10" cellpadding="0">
			 <form action="" method="post" enctype="multipart/form-data" name="bulk" id="bulk" onsubmit="return checkImport(this)">
			 	<input name="cp" type="hidden" value="upload"/>
				<tr><td height="2" colspan="2" bgcolor="#293694"></td>
			 </tr>
			  <tr>
			    <td width="100%" colspan="2" style="padding-left:160px;" ><h3 style="font-size:16px; color:#666; font-weight:bold;">Bulk Product Import</h3><div style="margin-left:-50px;"><label for="rdo_import_type_buynow" onclick="switchSample('show')"><input type="radio" id="rdo_import_type_buynow" name="rdo_import_type" value="buynow" checked="checked" />Buy Now</label><label for="rdo_import_type_ebay_turbolister" onclick="switchSample('hide')"><input type="radio" name="rdo_import_type" value="ebay_turbolister" id="rdo_import_type_ebay_turbolister" />Import Turbo Lister export file</label></div></td>
			  </tr>
			  <tr>
			    <td align="right" width="22%">Products Information </td>
			    <td width="78%">
                  <table cellpadding="0" cellspacing="0">
                	<tr>
                    	<td><input name="csv" type="file" id="csv" class="inputB"  style="width:200px;" /></td>
                        <td>
			      		<label id="upload_sample_content">&nbsp;<a href="#" target="_blank" id="down_sample_csv">Sample Product CSV</a><br/>
                  		&nbsp;&nbsp;<a href="/pdf/images_buy&sell.zip" target="_blank">Sample ZIP file of Product Images</a></label>
                  		</td></tr></table>
                  </td>
				  
			  </tr>
			  <tr>
			    <td align="right">Products Images </td>
			    <td><input name="image" type="file" id="image" class="inputB" style="width:200px;" />
			      &nbsp; <a href="/soc.php?cp=bulkinstruction" target="_blank">Setup Process</a> </td>
			  </tr>
              <tr><td></td>
              	  <td><div id="csvmsg" style="color:red;"></div><input type="hidden" id="swf_csvmsg" name="swf_csv" value=""/>
                  <div id="imgmsg" style="color:red"></div><input type="hidden" id="swf_imgmsg" name="swf_img" value=""/></td></tr>
			  <tr>
			  	<td colspan="2" style="color:#FF0000; margin:0; padding-left:100px">Please keep all file uploads to a maximum of 70MB in size.</td>
			  </tr>
			  <tr>
			    <td align="right">&nbsp;</td>
			    <td><input class="submit" type="image" src="/skin/red/images/import.gif" name="Submit" value="Import Products" /></td>
			  </tr>
			  <tr><td height="2" colspan="2" bgcolor="#293694"></td>
			 </tr>
			  </form>
			</table></td>
		 </tr>
		 <tr id="bulkline" style="display:none;"><td colspan="2"><table style="width:100%; padding:0 8px;"><tr><td height="2" colspan="2" bgcolor="#293694"></td></tr></table></td></tr>
		 <tr>
		 <td colspan="2" align="right" height="30">
		 {if $smarty.session.attribute != 0}
		 <input class="submit" type="image" src="/skin/red/images/bu-continue.gif" value="Continue to Next Step" onclick="document.mainForm.cp.value='next'; if (continueNextStep()) document.mainForm.submit(); void(0);" />
        <input class="submit" type="image" src="/skin/red/images/bu-exit.gif" value="Save And Exit"  onclick="document.mainForm.cp.value='save'; if (continueNextStep()) document.mainForm.submit(); void(0);" />
		{/if}
		</td>
		 </tr>   
      </table>
      </fieldset>-->
      <p>&nbsp;</p>
<form action="/foodwine/index.php?act=product{if $req.tab==''}&cp=delete{/if}" method="post" onsubmit="" id="multform" />
{if $req.tab==''}
<h3 style="font-size:16px; color:#666; font-weight:bold;">{if foodwine_type eq 'food'}Your Items{else}Your Menu Items{/if}</h3>
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
      <td colspan="10"><div style="background-color: rgb(238, 238, 238); height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
          <ul class="tabtmp">
            <li {if $req.tab == ''}class="active_tab"{/if}><a href="/foodwine/?act=product&step=4#list" style=" padding:13px 60px;font-weight:bold;color:#FFF;text-decoration:none;">My Products</a></li>
            <li {if $req.tab == 'clist'}class="active_tab"{/if}><a href="/foodwine/?act=product&step=4&tab=clist#list" style="padding:13px 60px;font-weight:bold;color:#FFF;text-decoration:none;">Category List</a></li>            
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
    <td><strong>{$p.price neq '0.00'}${$p.price}{/if}</strong></td>
    <td>{$p.datec|date_format:"%d/%m/%Y"}</td>
    <td><ul id="icons-products" style="margin:0 0 0 15px;">
        
        <li><a href="#" onclick="javascript:document.location.href ='{$soc_https_host}foodwine/?act=product&step=4&pid={$p.pid}';" title="{$lang.but.edit}"><img src="/skin/red/images/icon-edits.gif" /></a></li>
       
        <li><a href="/foodwine/index.php?act=product&cp=delete&pid[]={$p.pid}" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
        
       
        <!--<li><a href="#" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>-->
        <li><a href="{$soc_https_host}foodwine/?act=product&step=4&pid={$p.pid}&op=copy" title="Copy"><img src="/skin/red/images/icon-copy.gif"/></a></li>
      </ul></td>
  </tr>
  {/foreach}
  {/if}
  </tbody>
  
</table>
{else}
<h3 style="font-size:16px; color:#666; font-weight:bold;">Your Categories</h3>
<a name="list"></a>
<table cellpadding="0" cellspacing="0" width="98%" id="myproducts" class="sortable" style="">
  <colgroup>
  <col width="10%"/>
  <col width="20%" />
  <col width="55%" />
  <col width="15%" />
  </colgroup>
  <thead>
    <tr>
      <td colspan="10"><div style="background-color: rgb(238, 238, 238); height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
          <ul class="tabtmp">
            <li {if $req.tab == ''}class="active_tab"{/if}><a href="/foodwine/?act=product&step=4#list" style=" padding:13px 60px;font-weight:bold;color:#FFF;text-decoration:none;">My Products</a></li>
            <li {if $req.tab == 'clist'}class="active_tab"{/if}><a href="/foodwine/?act=product&step=4&tab=clist#list" style="padding:13px 60px;font-weight:bold;color:#FFF;text-decoration:none;">Category List</a></li>            
          </ul>
          <div style="clear: both;"></div>
        </div></td>
    </tr>
    <tr>
      <th class="unsortable"><input type="checkbox" id="allcheck" onclick="checkallitem(this.checked)" value="1" /></th>
      <th>Order<a title="Save Order" href="#" onclick="return checkCategoryForm('savecategoryorder');" style="padding-top:3px;"><img src="/skin/red/images/filesave.png"></a></th>
      <th>Name</th>
      <th class="unsortable">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
  
  {if $categories}
  {foreach from=$categories item=p}
  
  <tr>
    <td> {if $p.status eq 0 || $p.status eq 2}
      <input type="checkbox" value="{$p.id}"  name="cid[]" class="b4js" />
      {/if}</td>
    <td><input type="text" name="order['{$p.id}']" value="{$p.order}" class="text" /></td>
    <td> {$p.category_name|truncate:30:"..."}</td>
    <td><ul id="icons-products" style="padding-right:5px;margin:0 0 0 15px; float:right">
        
        <li><a href="#" onclick="showEditForm('{$p.id}');" title="{$lang.but.edit}"><img src="/skin/red/images/icon-edits.gif" /></a></li>       
        <li><a href="/foodwine/index.php?act=product&cp=deletecategory&tab=clist&cid[]={$p.id}#list" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
      </ul></td>
  </tr>
  {/foreach}
  {/if}
  </tbody>
  <input type="hidden" id="category_cp" name="cp" value="" />
  
</table>
{/if}
<table cellpadding="0" cellspacing="0" width="98%" style="margin-top:10px;">
  <colgroup>
  <col width="80%" />
  <col width="20%" />
  </colgroup>
  <tr>
    <td></td>
    <td align="right"><input type="button" name="multAct" onclick="{if $req.tab==''}multcheckform(this);{else}checkCategoryForm('deletecategory');{/if}" value="Delete"/>
      <!--<input type="submit" name="multAct" onclick="multcheckform(this);" value="Publish"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Unpublish"/>--></td>
  </tr>
</table>
</form>
<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="include/cal/ipopeng.php" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"> </iframe>
<p>&nbsp;</p>
<p>&nbsp;</p>
