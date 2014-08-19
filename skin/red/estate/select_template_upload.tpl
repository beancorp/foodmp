<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
{literal}
<style type="text/css">
span#main_swf_upload_uploading .uploadcancel,span#mainImage2_swf_upload_uploading .uploadcancel,span#mainImage3_swf_upload_uploading .uploadcancel{ border:0; margin-right:15px;}
span#asyncUploader_main_swf_upload,span#asyncUploader_mainImage2_swf_upload{
	width:auto;
}
div#pro_tab_bar_main_swf_upload{
	background:#FFF;
}
div#pro_tab_bar_mainImage2_swf_upload{
	background:#FFF;
}
div#ProgressBar_main_swf_upload{
	width:70px;
}
div#ProgressBar_mainImage2_swf_upload{
	width:50px;
}
</style>
<script language="javascript">
displayUploadSellerFormsBind('TemplateName');
$(function() {
		$("#main_swf_upload").makeAsyncUploader({
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage0&tpltype=2&attrib=0&index=0",
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
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage2&tpltype=2&attrib=2&index=0",
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
			upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage1&tpltype=2&attrib=1&index=0",
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


{if ($req.info.product_feetype eq 'year' && ($req.info.product_renewal_date >= $cur_time)) || !$req.info.is_single_paid}

<div id="disestate-a">
	<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;"><img src="/images/spacer.gif" alt="" /></div>
	<h1 class="h-step2-addfeature"><span>Add your feature image</span></h1>
	<div style="float:left; width:300px;">First feature image can be
	<ul class="arrows" style="width:250px;">
		<li><strong>a logo,</strong></li>
		<li><strong>an item you are selling,</strong></li>
		<li><strong>or any picture of your choice</strong></li>
	</ul>
	
	<p>&nbsp;</p>
	
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td><a id="main_swf_upload" href="javascript:uploadImage(2, 0, 0, 'mainImage0' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle" /></a>
		
	<span style="margin-top:5px; float:left;"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>
		<input name="mainImage0_svalue" id="mainImage0_svalue" type="hidden" value="{$req.images.mainImage.0.sname.text}"/>
		<input name="mainImage0_bvalue" id="mainImage0_bvalue" type="hidden" value="{$req.images.mainImage.0.bname.text}"/>
	    </td>
	</tr>
	<tr>
		<td colspan="2" align="left" valign="top">For perfect fit, the image size is 243 x 100 pixels</td>
	</tr>
	<tr>
		<td colspan="2" align="left" valign="top">
		<img src="{$req.images.mainImage.0.sname.text}" name="mainImage0_dis" border="1" id="mainImage0_dis" width="{$req.images.mainImage.0.sname.width}" height="{$req.images.mainImage.0.sname.height}" />
		</td>
	</tr>
	<tr>
	<td colspan="2" align="left" valign="middle" height="50">
		<a href="javascript:deleteImage(2, 0, 0, 'mainImage0' );void(0);"><img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle" /></a>   	
	</td>
	</tr>
	</table>
	</div>
	
	<div style="float:right; margin-right:10px;">
	<img id="uploadimages" src="/skin/red/estate/images/estate-a-blank.jpg" alt="" />
	</div>
</div>


<div id="disestate-b">
	<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;"><img src="/images/spacer.gif" alt="" /></div>
	<h1 class="h-step2-addfeature"><span>Add your feature image</span></h1>
	<div style="float:left; width:300px;">First feature image can be
	<ul class="arrows" style="width:250px;">
		<li><strong>a logo,</strong></li>
		<li><strong>an item you are selling,</strong></li>
		<li><strong>or any picture of your choice</strong></li>
	</ul>
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td><a id="mainImage3_swf_upload" href="javascript:uploadImage(2, 1, 0, 'mainImage1' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle" /></a>
		<input name="mainImage1_svalue" id="mainImage1_svalue" type="hidden" value="{$req.images.mainImage.1.sname.text}"/>
		<input name="mainImage1_bvalue" id="mainImage1_bvalue" type="hidden" value="{$req.images.mainImage.1.bname.text}"/>
		<span style="margin-top:5px; float:left;"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>	</td>
	</tr>
	<tr>
      <td colspan="2" align="left" valign="top">For perfect fit, the image size is 750 x 50 pixels</td>
	  </tr>
	<tr>
		<td colspan="2" align="left" valign="top">
		<img src="{$req.images.mainImage.1.sname.text}" name="mainImage1_dis" border="1" id="mainImage1_dis"  width="{$req.images.mainImage.1.sname.width}" height="{$req.images.mainImage.1.sname.height}" />		</td>
	</tr>
	<tr>
	<td colspan="2" align="left" valign="middle" height="50">
		<a href="javascript:deleteImage(2, 1, 0, 'mainImage1' );void(0);"><img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle" /></a>	</td>
	</tr>
	</table>
	</div>
	
	<div style="float:right; margin-right:10px;">
	<img id="uploadimages" src="/skin/red/estate/images/estate-b-blank.jpg" alt="" />
	</div>
</div>

{/if}

<div id="disestate-c">
	<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;"><img src="/images/spacer.gif" alt="" /></div>
	<h1 class="h-step2-addfeature"><span>Add your feature image</span></h1>
	<div style="float:left; width:300px;">&nbsp;</div>
	<div style="float:right; margin-right:10px;">
	<img id="uploadimages" src="/skin/red/estate/images/estate-c-blank.jpg" alt="" />
	</div>
</div>


<div>

	<div style="float:left; width:300px;">Second feature image can be your photo

	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td ><a id="mainImage2_swf_upload" href="javascript:uploadImage(2, 2, 0, 'mainImage2' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle" /></a>
		<input name="mainImage2_svalue" id="mainImage2_svalue" type="hidden" value="{$req.images.mainImage.2.sname.text}"/>
		<input name="mainImage2_bvalue" id="mainImage2_bvalue" type="hidden" value="{$req.images.mainImage.2.bname.text}"/>
	<span style="margin-top:5px; float:left;"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>
		 </td>
	</tr>
		<tr>
      <td colspan="2" align="left" valign="top">For perfect fit, the image size is 72 x 100 pixels</td>
	  </tr>
	<tr>
		<td colspan="2" align="left" valign="top">
		<img src="{$req.images.mainImage.2.sname.text}" name="mainImage2_dis" border="1" id="mainImage2_dis"  width="{$req.images.mainImage.2.sname.width}" height="{$req.images.mainImage.2.sname.height}" />		</td>
	</tr>
	<tr>
	<td colspan="2" align="left" valign="middle" height="50"><a href="javascript:deleteImage(2, 2, 0, 'mainImage2' );void(0);"><img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle" /></a></td>
	</tr>
	</table>
	</div>
		
</div>
