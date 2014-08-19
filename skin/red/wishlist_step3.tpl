<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<script language="javascript">
var protype =0;
var soc_http_host="{$soc_http_host}";
</script>
<script type="text/javascript" src="/skin/red/js/productupload.js"></script>
<link type="text/css" href="/skin/red/css/swfupload_product.css" rel="stylesheet" media="screen" />
{literal}
<script language="javascript" type="text/javascript">
_editor_url = "";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
  document.write('<scr' + 'ipt src="' +_editor_url+ 'js/editor.js"');
  document.write(' language="Javascript1.2"></scr' + 'ipt>');  
}
else
{ 
	document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); 
}

function checkForm(obj){
	var boolResult = false;
	var errors	= '';
    if(obj.cp.value=='edit'){
        if(obj.item_name.value==''){
            errors += '-  Item Name is required.\n';
        }
        if(obj.price.value != '' && !/^([0-9])|([1-9]\d+)|(\d+\.\d+)$/.test(obj.price.value)){
            errors += '-  Price is invalid.\n';
        }
    }

    if(errors != ''){
        errors = '-  Sorry, the following fields are required.\n' + errors;
        alert(errors);
    }else{
        boolResult = true;
    }

	
	return boolResult;
}
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
					$('#multform').submit();
				}
				break;
		}
	}else{
		alert("Please select itmes.");				
	}
	
	return false;
}

function upfile(){
	window.open('/uploadImageSingle.php?cp=attachment','attachement',	'width=700,height=150,statusbars=yes,status=yes');
}
</script>
{/literal}
{include_php file='include/jssppopup.php'}
<form action="" name="mainForm" method="POST" id="uploadsomething" style="padding-top:0px;border-top:none;" onsubmit="javascript:return checkForm(this);">
    <div style="margin: 0pt 20px 20px 0pt; background: rgb(204, 204, 204) none repeat scroll 0% 0%; clear: both; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; height: 1px; line-height: 1px;"><img alt="" src="/skin/red/images/spacer-grey.gif"/></div>
 <h3 style="font-size:16px; color:#666; font-weight:bold;margin-top:2px;float:left;">Wishlist items</h3>
 <div style="float:left;margin-left: 13px;">
     <img height="22" align="absmiddle" width="22" src="/skin/red/images/adminhome/icon-view.gif"/>
     <a style="width: auto; margin: 3px;" target="_blank" href="/soc.php?cp=wishlistSample">View Some Examples&nbsp;</a>
 </div>
 <div style="clear:both"></div>
	<fieldset id="uploadproduct">
    <table width="100%" border="0" cellpadding="0" cellspacing="4">
        <tr>
          <td align="right" width="25%">Item Name*</td>
          <td>
		  	<input name="item_name" type="text" class="text" id="item_name" size="30" value="{$req.select.item_name}"/>
            <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><span><span>These products will be available to registered customers to 'add to basket' and buy online. Enter the name of the product Eg Granny Smith Apples.</span></span></a></span></font></span></td>
        </tr>
        <tr id="noauction" style="display:{if $req.select.protype eq 1}none{else}{/if}">
          <td align="right">Price</td>
          <td valign="top">
          	<table>
          		<tr>
          			<td>$</td>
          			<td><input name="price" type="text" class="price" id="price" value="{$req.select.price}" size="11" maxlength="12"/></td>
          			<td><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Eg 12000.00<br />In the price field, enter decimal point only.</span></span></a></span></font></span></td>
          			<td></td>
          			<td></td>
          			<td></td>
          		</tr>
          	</table>
            
          </td>
        </tr>
        
        <tr valign="top">
          <td align="right">Description<br /></td>
          <td><textarea name="description" class="inputB"  style="height:220px; padding:3px; width:232px;">{$req.select.description}</textarea>
          </td>
        </tr>
      <tr valign="top">
		  <td height="23" align="right" style="font-weight:bold;">Display on your Wishlist homepage</td>
		  <td><input type="checkbox" name="isfeatured" value="1" {if $req.select.isfeatured}checked{/if}/> </td>
	  </tr>
      <tr valign="top">
      	  <td height="23" align="right">Youtube Video</td>
          <td><textarea class="text" name="youtubevideo" style="height:80px">{$req.select.youtubevideo}</textarea>&nbsp;<span class="style11"><a  href="/soc.php?cp=wishlistyoutubeins" target="_blank"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /></a></span></td>
      </tr>
	   <tr valign="top" id="uplines" style="display:none;">
	   	<td colspan="2"><strong style="font-size:14px">&nbsp;Downloadable File</strong>
			<table width="98%">
			 <tr>
		   	  <td height="23" style="width:93px;" align="right" valign="top">Choose File</td>
			  <td style="padding-left:10px;"><a href="javascript:upfile();void(0);"><img src="/skin/red/images/upload.gif"/></a></td>
			 </tr>
			</table>
		</td>
	  </tr>
    </table>
	</fieldset>
      
    <fieldset id="uploadimages" style="border-left:solid 1px #eee; padding-left:29px;display:block;">
		<script src="/skin/red/js/uploadImages.js" language="javascript"></script>
		<div style="+width:320px;">
<table width="225">
        <tr valign="top">
          <td colspan="3"><span class="lbl">
		  <a id="swf_upload_1" style="float:left;" href="javascript:uploadImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" /></a>&nbsp;&nbsp;| <a href="javascript:deleteImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a>
          </span><span class="style11"><font face="Verdana" size="1"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;width:110px;">Click on the 'upload an image' button, in the pop-up window click 'browse' and go to the location on your computer where the image is saved, then 'upload'.</span></span></a></font></span></td>
        </tr>
		
		<tr><td colspan="3">
		<table width="250" cellpadding="0" cellspacing="0" border="0">
			<tr>
          		<td height="225" colspan="3" align="center"><img src="{$req.images.mainImage.0.sname.text}" name="mainImage_dis" border="1" id="mainImage_dis" width="250" height="250" /></td>
        	</tr>
			<tr><td colspan="3" height="10"><img src="images/spacer.gif" width="1" height="1" /></td>
			<tr onmousemove="//displayUploadInterface('uploadImagesSub1',true)">
				<td align="center"><img src="{$req.images.subImage.0.sname.text}" width="79" height="79" name="subImage0_dis" border="1" id="subImage0_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.1.sname.text}" width="79" height="79" name="subImage1_dis" border="1" id="subImage1_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.2.sname.text}" width="79" height="79" name="subImage2_dis" border="1" id="subImage2_dis" /></td>
			</tr>
			<tr id="uploadImagesSub1" onmouseout="//displayUploadInterface('uploadImagesSub1',false)">
				<td align="center"><a id="swf_upload_2" href="javascript:uploadImage(0, 1, 0, 'subImage0' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 0, 'subImage0' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_3" href="javascript:uploadImage(0, 1, 1, 'subImage1' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 1, 'subImage1' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_4" href="javascript:uploadImage(0, 1, 2, 'subImage2' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 2, 'subImage2' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
			</tr>
			<tr><td colspan="3" height="10"><img src="images/spacer.gif" width="1" height="1" /></td>
			</tr>
			<tr onmousemove="//displayUploadInterface('uploadImagesSub2',true);">
				<td align="center"><img src="{$req.images.subImage.3.sname.text}" width="79" height="79" name="subImage3_dis" border="1" id="subImage3_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.4.sname.text}" width="79" height="79" name="subImage4_dis" border="1" id="subImage4_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.5.sname.text}" width="79" height="79" name="subImage5_dis" border="1" id="subImage5_dis" /></td>
			</tr>
			<tr id="uploadImagesSub2" onmouseout="//displayUploadInterface('uploadImagesSub2',false);">
				<td align="center"><a id="swf_upload_5" href="javascript:uploadImage(0, 1, 3, 'subImage3' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 3, 'subImage3' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_6" href="javascript:uploadImage(0, 1, 4, 'subImage4' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 4, 'subImage4' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_7" href="javascript:uploadImage(0, 1, 5, 'subImage5' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 5, 'subImage5' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
			</tr>
		</table></td>
		</tr>
        <tr valign="top">
          <td valign="middle" colspan="3">
          <table width="250" height="35" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="258" height="35">
                    <p>&nbsp;</p>
					
			<input name="mainImage_svalue" id="mainImage_svalue" type="hidden" value="{$req.images.mainImage.0.sname.text}"/>
			<input name="mainImage_bvalue" id="mainImage_bvalue" type="hidden" value="{$req.images.mainImage.0.bname.text}"/>
			
			<input name="subImage0_svalue" id="subImage0_svalue" type="hidden" value="{$req.images.subImage.0.sname.text}"/>
			<input name="subImage0_bvalue" id="subImage0_bvalue" type="hidden" value="{$req.images.subImage.0.bname.text}"/>
			
			<input name="subImage1_svalue" id="subImage1_svalue" type="hidden" value="{$req.images.subImage.1.sname.text}"/>
			<input name="subImage1_bvalue" id="subImage1_bvalue" type="hidden" value="{$req.images.subImage.1.bname.text}"/>
			
			<input name="subImage2_svalue" id="subImage2_svalue" type="hidden" value="{$req.images.subImage.2.sname.text}"/>
			<input name="subImage2_bvalue" id="subImage2_bvalue" type="hidden" value="{$req.images.subImage.2.bname.text}"/>
			
			<input name="subImage3_svalue" id="subImage3_svalue" type="hidden" value="{$req.images.subImage.3.sname.text}"/>
			<input name="subImage3_bvalue" id="subImage3_bvalue" type="hidden" value="{$req.images.subImage.3.bname.text}"/>
			
			<input name="subImage4_svalue" id="subImage4_svalue" type="hidden" value="{$req.images.subImage.4.sname.text}"/>
			<input name="subImage4_bvalue" id="subImage4_bvalue" type="hidden" value="{$req.images.subImage.4.bname.text}"/>
			
			<input name="subImage5_svalue" id="subImage5_svalue" type="hidden" value="{$req.images.subImage.5.sname.text}"/>
			<input name="subImage5_bvalue" id="subImage5_bvalue" type="hidden" value="{$req.images.subImage.5.bname.text}"/>
			<input type="hidden" name="cp" id="cp" value="edit" />
		    <input name="SubmitPic" type="image" src="/skin/red/images/buttons/bu-savetowishlist-sm.gif" class="input-none-border" onclick="javascript:document.mainForm.cp.value='edit';" value="Save to My Website" border="0"/>
		    <p style="margin:0; padding-left:5px; font-family:Arial; font-size:16px; font-weight:bold; color:red;">Click after you update every item</p>
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
   <div style="clear:both"></div>
  <table style="width:720px; padding:0 8px;" cellpadding="0" cellspacing="10">
  		 <tr>
         	<td height="2" colspan="2" bgcolor="#293694"></td>
          </tr>
          <tr>
		 <td colspan="2" align="right"><input class="submit" type="image" src="/skin/red/images/bu-continue.gif" value="Continue to Next Step" onclick="document.mainForm.cp.value='next'; if (continueNextStep()) document.mainForm.submit(); void(0);" />
        <input class="submit" type="image" src="/skin/red/images/bu-exit.gif" value="Save And Exit"  onclick="document.mainForm.cp.value='exit'; if (continueNextStep()) document.mainForm.submit(); void(0);" /></td>
		 </tr>
  </table>
		</form> 
       
        <form action="" method="post" onsubmit="return false;" id="multform" /> 
        <input type="hidden" value="" id="multcp" name="multcp"/>
        <table cellpadding="0" cellspacing="0" width="98%" id="myproducts" class="sortable">
        
        <colgroup>
		<col width="2%"/>
        <col width="2%" />
        <col width="10%" />
        <col width="26%" />
        <col width="20%" />
        <col width="10%" />
        <col width="15%" />
        <col width="25%" />
        </colgroup>
        
        <thead>
        <tr>
			<th class="unsortable"><input type="checkbox" id="allcheck" onclick="checkallitem()" value="1" /></th>
        	<th class="unsortable">&nbsp;</th>
        	<th class="unsortable">Item</th>
        	<th>Description</th>
        	<th>Homepage Item</th>
        	<th>
        		{if $req.select.sortby == 'price_asc'}
        			<a href="soc.php?act=wishlist&step=3&sortby=price_desc">Price</a><img src="/skin/red/images/arrow-down.gif" border="0" />
        		{elseif $req.select.sortby == 'price_desc'}
        			<a href="soc.php?act=wishlist&step=3&sortby=price_asc">Price</a><img src="/skin/red/images/arrow-up.gif" border="0" />
        		{else}
        			<a href="soc.php?act=wishlist&step=3&sortby=price_asc">Price</a>
        		{/if}
        	</th>
        	<th>
        		{if $req.select.sortby == 'date_asc'}
        			<a href="soc.php?act=wishlist&step=3&sortby=date_desc">Date Added</a><img src="/skin/red/images/arrow-down.gif" border="0" />
        		{elseif $req.select.sortby == 'date_desc'}
        			<a href="soc.php?act=wishlist&step=3&sortby=date_asc">Date Added</a><img src="/skin/red/images/arrow-up.gif" border="0" />
        		{else}
        			<a href="soc.php?act=wishlist&step=3&sortby=date_asc">Date Added</a>
        		{/if}        	
        	</th>
        	<th class="unsortable">&nbsp;</th>
          
        </tr>
        </thead>
       	
       	<tbody>
        {if $req.product}
        {foreach from=$req.product item=p}
        <tr>
		<td><input type="checkbox" value="{$p.pid}" {if $p.protype eq 1}disabled{/if}  name="ckpid[]" /></td>
        <td>{if $p.pid==$req.select.pid}<img src="/skin/red/images/arrow.gif" border="0" />{else}<img src="/skin/red/images/spacer.gif" border="0" />{/if}</td>
        <td><img src="{$p.simage.text}" width="61" height="35" /></td>
        <td> {$p.description|truncate:30:"..."}</td>
		<td><input type="checkbox" {if $p.isfeatured}checked{/if} disabled /></td>
		<td>{if $p.protype neq 1}<strong>${$p.price|number_format:2}</strong>{/if}</td>
		<td>{$p.dateadd}</td>
        <td>
        	<ul id="icons-products">
            <li>{if $p.protype neq 1}<a href="#" onclick="javascript:document.location.replace('soc.php?act=wishlist&step={$req.select.step}&pid={$p.pid}')" title="{$lang.but.edit}"><img src="/skin/red/images/icon-edits.gif" /></a>{else}<div style="width:22px;">&nbsp;</div>{/if}</li>
            <li>{if $p.protype neq 1}<a href="#" onclick="javascript:deletes('soc.php?act=wishlist&step={$req.select.step}&cp=del&pid={$p.pid}');" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a>{else}<div style="width:22px;">&nbsp;</div>{/if}</li>
            <li><a href="/{$p.bu_urlstring}/wishlist/{$p.url_item_name}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
            </ul>        </td>
			
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
		<td align="right"><input type="submit" name="multAct" onclick="multcheckform(this);" value="Delete"/></td>
		</tr>
		</table>
		</form>
       <iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
       <p>&nbsp;</p><p>&nbsp;</p>