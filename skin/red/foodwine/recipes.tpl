<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
<script src="/skin/red/js/uploadImages.js" language="javascript"></script>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload_product.css" rel="stylesheet" media="screen" />
<script type="text/javascript">
var protype =0;
var soc_http_host="{$soc_http_host}";
var soc_https_host="{$soc_http_host}";
{literal}
	$(document).ready(function() {
		setTimeout(function(){$("#msg_show").hide();},5000);
	})
	function mouseEventLI(obj, flag)
	{
		$('.recipes-oper-edit').hide();
		$('.recipes-oper-del').hide();
		if(flag) {
			$(obj).find('a').each(function(n){
				if(n != 0) {
					$(this).show();
				}
			});
			$(obj).addClass("select");
		} else {
			$(obj).removeClass("select");
		}
		
		return true;
	}
	
	function operRecipes(rid, cp)
	{
		if(rid && cp) {
			if(cp == 'delete' && !window.confirm("Are you sure you want to delete this recipes?")) {
				return;
			}
			location.href = '/foodwine/?act=recipes&cp=' + cp + '&rid=' + rid;
		} else {
		
			return ;
		}
	}
	
	function checkForm(obj)
	{
		if(obj.title.value == '') 
		{
			alert("Title is required.");
			obj.title.focus();
			return false;
		}
		if(obj.content.value == '') 
		{
			alert("Ingredients is required.");
			obj.content.focus();
			return false;
		}
		
		return true;
	}
	
	function doPreview(rid, StoreID)
	{
		document.mainForm.cp.value='preview';
		form = document.getElementById("mainForm");
		if(checkForm(form)) {
			form.submit();
		}
	}
    
$(function() {
		$("#swf_upload_1").makeAsyncUploader({
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage&tpltype=6&attrib=0&index=0",
            flash_url: '/skin/red/js/swfupload.swf',
            button_image_url: '/skin/red/images/blankButton.png',
            disableDuringUpload: 'INPUT[type="submit"]',
           	file_types:'*.jpg;*.gif;*.png',
			file_size_limit:'10MB',
			file_types_description:'All images',
			button_window_mode:"transparent",
			button_text:"",
			height:"29",
            debug:false
		});
});
{/literal}
</script>
<script type="text/javascript" src="/skin/red/js/productupload.js"></script>
<h1 class="soc-recipes">Your Recipes</h1>
<div class="clear"></div>
<div style="border-bottom:2px solid #CCCCCC; margin:0;"></div>
<div class="recipes">
<div class="repices-info">
	<h2>Current Recipes</h2>
    <ul>
    	{if $req.recipe_list}
    	{foreach from=$req.recipe_list item=recipe}
    	<li onmouseover="mouseEventLI(this, true);" onmouseout="mouseEventLI(this, false);"><a target="_blank" class="recipes-text" href="/soc.php?cp=recipes&StoreID={$req.StoreID}&rid={$recipe.id}" title="{$recipe.title}">{$recipe.title}</a><a href="javascript:void(0);" onclick="operRecipes('{$recipe.id}', 'edit');" class="recipes-oper-edit" title="Edit">Edit</a><a href="javascript:void(0);" onclick="operRecipes('{$recipe.id}', 'delete');" class="recipes-oper-del" title="Delete">Delete</a>
        </li>
        {/foreach}
        {else}
        <li>No Records</li>
        {/if}
    </ul>
</div>
	<div class="post-title">
	<h2>{if $req.info.id}Edit{else}Post New{/if} Recipe</h2>
    </div>    
<p class="txt" id="msg_show" style="{if $req.msg eq ''}display: none;{/if}"><font style="color:red;">{$req.msg}</font></p>
{include_php file='../include/jssppopup.php'}
<form action="" id="mainForm" name="mainForm" method="POST" onsubmit="return checkForm(this);" style="height:560px; padding:0; border:none;">
  <fieldset id="uploadproduct">
    <table cellspacing="4" cellpadding="0">
		<tbody>
        <tr height="40">
        	<td align="left">Title</td>
			<td><input type="text" class="inputB" id="title" name="title" style="width:320px;+width:320px;" value="{$req.info.title}" /></td>
			<td></td>
		</tr>		
		<tr>
        	<td align="left" style="width:30px;+width:30px;vertical-align:top;">Ingredients</td>
			<td>
				<textarea style="width:320px;+width:320px;height:150px;*height:152px;*padding-bottom:0;" class="inputB" name="content">{$req.info.content}</textarea>
			
			</td>
			<td></td>
		</tr>			
		<tr>
        	<td align="left" style="width:30px;+width:30px;vertical-align:top;">Method</td>
			<td>
				<textarea style="width:320px;+width:320px;height:150px;*height:152px;*padding-bottom:0;" class="inputB" name="method">{$req.info.method}</textarea>
			
			</td>
			<td></td>
		</tr>	
	</tbody>
    </table>
  </fieldset>
  <fieldset id="uploadimages" style="border-left:solid 1px #eee; padding-left:29px;display:block;">
  <div style="+width:320px; height:210px;">
    <table width="225">
      <tr valign="top" height="30">
        <td colspan="3"><span class="lbl"> <a id="swf_upload_1" style="float:left;" href="javascript:uploadImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" /></a>&nbsp;&nbsp;| <a href="javascript:deleteImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a> </span><span class="style11"><font face="Verdana" size="1"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;width:110px;">Click on the 'upload an image' button, in the pop-up window click 'browse' and go to the location on your computer where the image is saved, then 'upload'.</span></span></a></font></span></td>
      </tr>
      <tr>
        <td colspan="3"><table width="250" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td height="243" colspan="3" align="left"><img src="{if $req.info.picture}{ $req.info.picture}{else}/images/243x212.jpg{/if}" name="mainImage_dis" border="1" id="mainImage_dis" width="243" height="212" /></td>
            </tr>
            
          </table></td>
      </tr>
      <tr valign="top">
        <td valign="middle" colspan="3"><table width="250" height="35" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="258" height="35">
                <p>&nbsp;</p>
                <input name="mainImage_svalue" id="mainImage_svalue" type="hidden" value="{$req.info.thumb}"/>
                <input name="mainImage_bvalue" id="mainImage_bvalue" type="hidden" value="{$req.info.picture}"/>
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
  <input type="hidden" name="rid" value="{$req.info.id}"/>
  <input type="hidden" name="cp" id="cp" value="save" />
  <input type="hidden" name="StoreID" value="{$req.info.StoreID}" />
  <div class="clear"></div>
  <div style="position:relative">
  <a onclick="doPreview('{$req.info.id}', '{$req.info.StoreID}');" href="javascript:void(0);" class="recipe-preview">Preview</a>
  <input type="image" style="margin:30px 0 0 0; float:right; border:none;" src="/skin/red/images/foodwine/post-recipe.jpg" name="SubmitPic" onclick="javascript:document.mainForm.cp.value='save';" value="Post Recipe" border="0"/>
  </div>
</form>
</div>
<div class="clear"></div>