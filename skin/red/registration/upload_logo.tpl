{if $registered}
	{literal}
		<!-- Google Code for Business Registration Conversion Page --> 
		<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = 976104963;
		var google_conversion_language = "en";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "lYraCJW0gQgQg9y40QM"; var google_conversion_value = 0; var google_remarketing_only = false;
		/* ]]> */
		</script>
		<script type="text/javascript"  
		src="//www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt=""  
		src="//www.googleadservices.com/pagead/conversion/976104963/?value=0&amp;label=lYraCJW0gQgQg9y40QM&amp;guid=ON&amp;script=0"/>
		</div>
		</noscript>
	{/literal}
{/if}
<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<SCRIPT src="/js/lightbox_plus.js" type="text/javascript"></SCRIPT>
<script src="/skin/red/js/uploadImages.js" type="text/javascript"></script>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script>
var soc_http_host="{$soc_http_host}";
</script>
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
#submit_button {
    background-color: #FF8F05;
    background-image: -moz-linear-gradient(center bottom , #DE600B 30%, #FF8F05 65%);
    border: 0 none !important;
    border-radius: 10px 10px 10px 10px;
    color: #FFFFFF;
    cursor: pointer;
    display: inline-block;
    float: left;
    font-size: 12pt !important;
    font-weight: bold;
    margin-left: 275px;
    padding: 5px;
    text-align: center;
    text-decoration: none;
    width: 120px;
}

#upload_form {
    background-color: #EFEFEF;
    border-radius: 10px 10px 10px 10px;
    margin-bottom: 10px;
    padding: 10px;
	overflow: hidden;
}

</style>
<script language="javascript">
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
<form action="" method="POST">
	<div id="upload_form">
		<h1 class="foodwine-h-step2-addfeature"><span>Add Your Logo</span></h1>
		<table width="100%" cellpadding="0" cellspacing="0"> 
			<tr>
			<td>
				<a id="mainImage3_swf_upload" href="javascript:uploadImage(6, 2, 0, 'mainImage2' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle" /></a>
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
					<img src="{$req.images.mainImage.2.sname.text}" name="mainImage2_dis" border="1" id="mainImage2_dis"  width="{$req.images.mainImage.2.sname.width}" height="{$req.images.mainImage.2.sname.height}" />
				</td>
			</tr>
		</table>
		<br />
		<input id="submit_button" type="submit" name="submit_form" value="Save">
	</div>
</form>