<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" /> 
 
{literal}
<style type="text/css">
span#main_swf_upload_uploading .uploadcancel,span#mainImage2_swf_upload_uploading .uploadcancel,span#mainImage3_swf_upload_uploading .uploadcancel,span#mainImage4_swf_upload_uploading .uploadcancel{ border:0; margin-right:15px;}
span#asyncUploader_main_swf_upload,span#asyncUploader_mainImage2_swf_upload,span#asyncUploader_mainImage3_swf_upload{
	width:auto;
}
div#pro_tab_bar_main_swf_upload{ 
	background:#FFF;
}
div#pro_tab_bar_mainImage2_swf_upload,div#pro_tab_bar_mainImage3_swf_upload{
	background:#FFF; 
}
div#ProgressBar_main_swf_upload{
	width:70px;
}
div#ProgressBar_mainImage2_swf_upload,div#ProgressBar_mainImage3_swf_upload{
	width:50px;
}
</style>
<script language="javascript">
displayUploadSellerFormsBind('TemplateName');
$(function() {
		$("#main_swf_upload").makeAsyncUploader({
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage0&tpltype=6&attrib=0&index=0",
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
		$("#mainImage3_swf_upload").makeAsyncUploader({
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage2&tpltype=6&attrib=2&index=0",
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
		$("#mainImage2_swf_upload").makeAsyncUploader({
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage3&tpltype=6&attrib=3&index=0",
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
		$("#mainImage4_swf_upload").makeAsyncUploader({
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage1&tpltype=6&attrib=1&index=0",
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
		$("#mainImage5_swf_upload").makeAsyncUploader({
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage4&tpltype=6&attrib=4&index=0",
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
		$("#mainImage6_swf_upload").makeAsyncUploader({
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage5&tpltype=6&attrib=5&index=0",
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
function uploadresponse(response){
	var aryResult = response.split('|');
	if(aryResult.length>3){
		var objRes = aryResult[0];
		var imgobj = $("#"+objRes+"_dis");
		$(imgobj).attr('src',"/"+aryResult[1]);
		$(imgobj).css('width',aryResult[2]);
		$(imgobj).css('height',aryResult[3]);
		$("#"+objRes+"_svalue").val("/"+aryResult[4]);
		$("#"+objRes+"_bvalue").val("/"+$.trim(aryResult[5]));
	}
}
function uploadprocess(bl){
	
}
</script>
{/literal}
<div style="position:relative; min-height:600px; width:755px; float:left;">

<div>
	<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;"><img src="/images/spacer.gif" alt="" /></div>
	<h1 class="foodwine-h-step2-addfeature"><span>Add Your Logo & Feature Image</span></h1>
	<div style="float:left; width:300px;"><font style="font-weight:bold; font-size:14px;">Logo</font></strong>
	<!--<ul class="arrows" style="width:250px;">
		<li><strong>a <font style="font-weight:bold; font-size:14px;">Logo</font>,</strong></li>
		<li><strong>an item you are selling,</strong></li>
		<li><strong>or any picture of your choice</strong></li>
	</ul>-->
	
	
	
	<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:258px;">
	<tr>
	<td>
    <p><strong style="font-weight:bold; font-size:14px; padding-left:5px;">Feature Image</strong></p>
	<a id="main_swf_upload" href="javascript:uploadImage(6, 0, 0, 'mainImage0' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle" /></a>		<input name="mainImage0_svalue" id="mainImage0_svalue" type="hidden" value="{if $req.images.mainImage.0.sname.text neq '/images/253x105.jpg'}{$req.images.mainImage.0.sname.text}{/if}"/>
		<input name="mainImage0_bvalue" id="mainImage0_bvalue" type="hidden" value="{if $req.images.mainImage.0.bname.text neq '/images/497x206.jpg'}{$req.images.mainImage.0.bname.text}{/if}"/>
	<span style="margin-top:5px; float:left"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>		  </td>
	</tr>
	<tr>
      <td colspan="2" align="left" valign="top">For perfect fit, the image size is 497 x 206 pixels</td>
	  </tr>
	<tr>
		<td colspan="2" align="left" valign="top">
		<img src="{$req.images.mainImage.0.sname.text}" name="mainImage0_dis" border="1" id="mainImage0_dis" width="{$req.images.mainImage.0.sname.width}" height="{$req.images.mainImage.0.sname.height}" />		</td>
	</tr>
	<tr>
	<td colspan="2" align="left" valign="middle" height="50">
		<a href="javascript:deleteImage(6, 0, 0, 'mainImage0' );void(0);"><img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle" /></a>	</td>
	</tr>
	</table>
  <div class="seller-setting-fieldset">
    <div class="seller-setting-title">
      <p><strong style="font-weight:bold; font-size:14px;">Search Results logo</strong></p>
    </div>
    <div class="seller-setting-search">
      <table width="100%" cellpadding="0" cellspacing="0" style="">
        <tr>
          <td>
            <a id="mainImage6_swf_upload" href="javascript:uploadImage(6, 5, 0, 'mainImage5' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle" /></a>
            <input name="mainImage5_svalue" id="mainImage5_svalue" type="hidden" value="{$req.images.mainImage.5.sname.text}"/>
            <input name="mainImage5_bvalue" id="mainImage5_bvalue" type="hidden" value="{$req.images.mainImage.5.bname.text}"/>
            <span style="margin-top:5px; float:left"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>		  </td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="top">
            If you upload the "Search Results logo", the system will show this logo on the search results page instead of the store logo.<br />For perfect fit, please make sure the image size is 242 x 201 pixels.
          </td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="top">
            <img src="{$req.images.mainImage.5.sname.text}" name="mainImage5_dis" border="1" id="mainImage5_dis" width="{$req.images.mainImage.5.sname.width}" height="{$req.images.mainImage.5.sname.height}" />		</td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" height="50">
            <a href="javascript:deleteImage(6, 5, 0, 'mainImage5' );void(0);"><img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle" /></a>	</td>
        </tr>
      </table>
    </div>
  </div>
	</div>  
	
    <!-- FOOD TEMPLATE -->
	<div id="disfoodwine-a" style="float:right; margin-right:10px; display:none">
	<img src="/skin/red/foodwine/images/foodwine-a-blank.jpg" alt="Template A" />
	</div>
	<div id="disfoodwine-b" style="float:right; margin-right:10px; display:none">
	<img src="/skin/red/foodwine/images/foodwine-b-blank.jpg" alt="Template B" />
	</div>
	<div id="disfoodwine-c" style="float:right; margin-right:10px; display:none">
	<img src="/skin/red/foodwine/images/foodwine-c-blank.jpg" alt="Template C" />
	</div>
	<div id="disfoodwine-h" style="float:right; margin-right:10px; display:none">
	<img src="/skin/red/foodwine/images/foodwine-h-blank.jpg" alt="Template D" />
	</div>
	
    <!-- WINE TEMPLATE -->
	<div id="disfoodwine-d" style="float:right; margin-right:10px;display:none">
	<img src="/skin/red/foodwine/images/foodwine-d-blank.jpg" alt="Template A" />
	</div>
	<div id="disfoodwine-e" style="float:right; margin-right:10px;display:none">
	<img src="/skin/red/foodwine/images/foodwine-e-blank.jpg" alt="Template B" />
	</div>
	<div id="disfoodwine-f" style="float:right; margin-right:10px;display:none">
	<img src="/skin/red/foodwine/images/foodwine-f-blank.jpg" alt="Template C" />
	</div>
	<div id="disfoodwine-g" style="float:right; margin-right:10px;display:none">
	<img src="/skin/red/foodwine/images/foodwine-g-blank.jpg" alt="Template D" />
	</div>
</div>

    <div class="clear"></div>
    <div style="float: none; width:300px; position:absolute; top:150px; left:0">
    
        <table width="100%" cellpadding="0" cellspacing="0"> 
        <tr>
        <td><a id="mainImage3_swf_upload" href="javascript:uploadImage(6, 2, 0, 'mainImage2' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle" /></a>
            <input name="mainImage2_svalue" id="mainImage2_svalue" type="hidden" value="{$req.images.mainImage.2.sname.text}"/>
            <input name="mainImage2_bvalue" id="mainImage2_bvalue" type="hidden" value="{$req.images.mainImage.2.bname.text}"/>
        <span style="float:left; margin-top:5px;"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>	
              </td> 
        </tr>
        <tr>
          <td colspan="2" align="left" valign="top">For perfect fit, the image size is 242 x 201 pixels</td>
          </tr>
        <tr>
            <td colspan="2" align="left" valign="top">
            <img src="{$req.images.mainImage.2.sname.text}" name="mainImage2_dis" border="1" id="mainImage2_dis"  width="{$req.images.mainImage.2.sname.width}" height="{$req.images.mainImage.2.sname.height}" />		</td>
        </tr>
        <tr>
        <td colspan="2" align="left" valign="middle" height="50"><a href="javascript:deleteImage(6, 2, 0, 'mainImage2' );void(0);"><img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle" /></a></td>
        </tr>
        <tr>
          <td>
            <label>Display Logo</label>
          </td>
          <td>
            <select name="logoDisplay" style="border: 1px solid;">
              <option value ="0"{if $req.LogoDisplay eq '0'} selected="selected"{/if}>Not Display</option>
              <option value ="1"{if $req.LogoDisplay eq '1'} selected="selected"{/if}>Display</option>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <div>Display Logo in store front page.</div>
          </td>
        </tr>
        </table>
    </div>
    <div class="clear"></div>
</div>