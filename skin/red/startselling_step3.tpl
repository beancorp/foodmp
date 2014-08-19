<SCRIPT src="/js/lightbox_plus.js" type="text/javascript"></SCRIPT>
<script src="/skin/red/js/uploadImages.js" type="text/javascript"></script>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script type="text/javascript">
	var tmpl = "{$smarty.session.TemplateName}";
	var protype =0;
	var soc_http_host="{$soc_http_host}";
{literal}
	
	 $(function() {
        $("#photo_tmp_b").makeAsyncUploader({
		
            upload_url: soc_http_host+"uploadgallery.php?type=seller_product&ut=3&res=tmp-n-a",
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
		
		$("#photo_tmp_c").makeAsyncUploader({
		
            upload_url: soc_http_host+"uploadgallery.php?type=seller_product&ut=3&res=tmp-n-b",
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
		
		$("#store_banner").makeAsyncUploader({
		
            upload_url: soc_http_host+"uploadgallery.php?type=store_banner&ut=0",
            flash_url: '/skin/red/js/swfupload.swf',
            button_image_url: '/skin/red/images/bu-step3-upload.gif',
            disableDuringUpload: 'INPUT[type="submit"]',
            file_types:'*.jpg;*.gif;*.png',
            file_size_limit:'10MB',
			file_types_description:'All images',
			button_window_mode:"transparent",
			button_text:"",
			height:"34",
			upload_success_handler: function(file, response) {
				var aryResult = response.split('|');
				if(aryResult[2]&&$.trim(aryResult[2])=='store_banner'){
					if(aryResult[3] == 114) {
						alert("Please upload a banner with correct size.");
					} else {
						$("#img_banner").attr('src',aryResult[0]);
						$("#txt_banner").val(aryResult[0]);
					}
				}
			},
            debug:false
        });
		
 });
  
 function tmp_change(val) {
 		tips_tmp_change='Note: Please update your feature image if you changed the template for your website.';
		if(val=='tmp-n-e') {
			$("#temp_c_detail,#temp_b_detail,#div_tmp_change_tips").hide();
		}
		if(val=='tmp-n-a') {
			$("#div_tmp_change_tips").html(tips_tmp_change).show();
			$("#temp_c_detail").hide();
			$("#temp_b_detail").show();
		}
		if(val=='tmp-n-b') {
			$("#div_tmp_change_tips").html(tips_tmp_change).show();
			$("#temp_c_detail").show();
			$("#temp_b_detail").hide();
		}
 			//val=this.value;
			/*
			if(val=='tmp-n-e') {
				$("#tmp_contents").hide();
				$("#div_tmp_change_tips").html('');
			}
			else {
				$("#tmp_contents").show();
				$.get('/uploadgallery.php?type=seller_product_filename&tmp_name='+val,{},function(){});
				$("#div_tmp_change_tips").html('Note: Please update your feature image if you changed the template for your website.');
			}
			
			if(val=='tmp-n-a') {
				$("#l_tmp_images_pix").text('497X195');
				$("#img_tmp_images").attr('src','/skin/red/images/templateB-big.jpg');
			
			}
			else if(val=='tmp-n-b') {
				$("#l_tmp_images_pix").text('243X212');
				$("#img_tmp_images").attr('src','/skin/red/images/templateC-big.jpg');
			}
			else {
				$("#l_tmp_images_pix").text('nul');
			}*/
 
 }
 
 function uploadresponse(response){
		var aryResult = response.split('|');
		if(aryResult[2]&&$.trim(aryResult[2])=='seller_product'){
			$("#MainIMG2,#MainIMG2_c").attr('src',aryResult[0]);
			$("#MainImageH").val(aryResult[0]);
		}
}
function uploadprocess(bl){
	if(!bl){
		$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save_grey.gif)');
	}else{
		$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save.gif)');
	}
}
function ImgBlank(picType){
	if (confirm("Are you sure you want to delete?")){
		if(picType == 1){
			if($('input[name="TemplateName"]').val()=='tmp-n-a') {
				MM_swapImage('MainIMG2','','images/big1_logo.gif',1);
			}
			else {
				MM_swapImage('MainIMG2','','images/imagetemp.gif',1);
			}
			document.mainForm.MainImageH.value = '' ;
			document.mainForm.cp.value='delmain';
			
			// alert(document.AddImage.mainImageH.value);
		}else{
			MM_swapImage('LogoImg2','','images/templogo.gif',1);
			document.mainForm.LogoImageH.value = '' ;
			document.mainForm.cp.value='dellogo';
		}
		document.mainForm.submit();
	}
}

function deleteBanner()
{
	$("#img_banner").attr('src', '/skin/red/images/default-store-banner.jpg');
	$("#txt_banner").val('');
}

$(function(){	
	$(".li-title a.oper").each(function(){
		$(this).click(function(){
			index = $(this).attr('index');			
			isclose = $("#step3_title_" + index).hasClass('close');
			
			tab_display = '';
			if(isclose) {
				tab_display = 'show';
				$("#step3_title_" + index).addClass('open');
				$("#step3_title_" + index).removeClass('close');
			} else {
				tab_display = "hide";
				$("#step3_title_" + index).addClass('close');
				$("#step3_title_" + index).removeClass('open');
			}
			
			$("#step3_content_" + index).animate({ height: tab_display, opacity: tab_display }, 'slow');
		});
	});
});
{/literal}
</script>
<form name="mainForm" action="" method="POST">
<ul class="step3-ul">
{if $req.info.attribute neq '0' || 1}
<li class="step3-li">
    <div id="step3_title_1" class="li-title close top">
        <div class="title-text">
            <h3><strong>Premium Customised Template -</strong> <label class="italic">Upload your customised banner</label> </h3>
            <a onfocus="this.blur();" href="javascript:void(0);" class="oper" index="1">&nbsp;</a>
        </div>
    </div>
    <div class="li-content" id="step3_content_1">
    	<div class="col-left">
        	<div class="left-top">
                <h2>Customise the banner</h2>
                <p>
                   You can design your own banner that best reflects your brand.<br />
                   The banner needs to be <strong>950 pixels wide by 75 pixels tall.</strong> <br />
                   Please note the Food Marketplace logo overlaps the first <strong>183 pixels</strong> on <br />
                   the left so do not put any content that needs to visible in that area.
                </p>
            </div>
            <div class="left-middle">
                <div class="banner-upload">
                <input type="file" id="store_banner"  />
                 <span style="padding:10px 0;">&nbsp;&nbsp;| <a href="javascript:deleteBanner();void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a>&nbsp;&nbsp;<a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>
                <div style="clear:both;"></div>
                </div>
            
                <input name="txt_banner" id="txt_banner" class="inputB" type="text" value="{$req.bannerImg}" style="width:398px;" readonly="readonly"/>
            </div>
            <div class="left-bottom">          
                <img src="{if $req.bannerImg}{$req.bannerImg}{else}/skin/red/images/default-store-banner.jpg{/if}" name="img_banner" border="1" id="img_banner" width="410"/>
            </div>
        </div>
        <div class="col-right">
        	<label>Example banner</label>
            <img alt="Example banner" src="/skin/red/images/example-banner.jpg" />
        	<label>Example banner in place</label>
            <img alt="Example banner in place" src="/skin/red/images/example-banner-in-place.jpg" />
        </div>
        <div class="clear"></div>
    </div>
</li>
{/if}
<li class="step3-li">
<div id="step3_title_2" class="li-title open">
    <div class="title-text">
        <h3><strong>Customised Template -</strong> <label class="italic">Select your template, colour and icons.</label> </h3>
        <a onfocus="this.blur();" href="javascript:void(0);" class="oper" index="2">&nbsp;</a>
    </div>
</div>
<div class="li-content" id="step3_content_2" style="display:block;">
<div id="loadingImage"></div>
        
        <h3 style="font-size:16px; color:#666; font-weight:bold; display:none;">Choose Template</h3>
		{if $session.attribute eq '0'}
	    	{include file="startselling_step3_template.tpl"}
    	{elseif $session.attribute eq '1' }
			{include file="estate/select_template.tpl"}
		{elseif $session.attribute eq '2' }
			{include file="auto/select_template.tpl"}
		{elseif $session.attribute eq '3' }
			{include file="job/select_template.tpl"}
		{elseif $session.attribute eq '5' }
			{include file="foodwine/select_template.tpl"}
		{/if}
		<br/>
		<div style="margin:0 auto; color:red;" id="div_tmp_change_tips" align="center"></div>
    	<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;"><img src="images/spacer.gif" alt="" /></div>
        <h3 style="font-size:16px; color:#666; font-weight:bold;">Choose Colour</h3>
		<ul id="choosecolor">
        <li><img src="/skin/red/images/color-purple.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="33" {if $req.TemplateBGColor eq '33' or $req.TemplateBGColor eq ''} checked {/if} /></li>
        <li><img src="/skin/red/images/color-orange.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="36" {if $req.TemplateBGColor eq '36'}checked{/if} /></li>
        <li><img src="/skin/red/images/color-blue.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="34" {if $req.TemplateBGColor eq '34'}checked{/if} /></li>
        <li><img src="/skin/red/images/color-red.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="35" {if $req.TemplateBGColor eq '35'}checked{/if} /></li>
        <li><img src="/skin/red/images/color-green.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="37" {if $req.TemplateBGColor eq '37'}checked{/if} /></li>
        <li><img src="/skin/red/images/color-black.gif" alt="" /><br /><input type="radio" name="TemplateBGColor" value="38" {if $req.TemplateBGColor eq '38'}checked{/if} /></li>
        </ul>

		<br />
        
		{if $session.attribute eq '0'}
	<div id="temp_b_detail" style="{if $smarty.session.TemplateName neq 'tmp-n-a'}display:none;{/if}">
		<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;"><img src="images/spacer.gif" alt="" /></div>
<div id='tmp_contents'>		
        <h3 style="font-size: 16px; color: rgb(102, 102, 102); font-weight: bold;">Add your feature image</h3>
		<div style=" clear:both;"></div>
		<div style="float:left; width:300px;">
    	A feature image can be
        <ul class="arrows" style="width:250px;">
        <li><strong>a logo,</strong></li>
        <li><strong>an item you are selling,</strong></li>
		<li><strong>or any picture of your choice</strong></li>
        </ul>
        
        <p>Your feature image will give your website the color and feel that you want to express to your buyers (in accordance with the "terms of use")</p>
        <p><strong>For template B, the image size should be<br/> 497X195 pixels.</strong></p>
		<p><strong>For template C, the image size should be<br/> 243X212 pixels.</strong></p>
		<table cellpadding="0" cellspacing="0">
        <tr>
        <td colspan="2" style="width:300px;">Image &nbsp;&nbsp;&nbsp;<font style="color: rgb(119, 119, 119); font-size: 12px;">(Supported image type are jpg, gif, png)</font>
		</td>
		</tr>

        	<input name="MainImageH" id="MainImageH" type="hidden" value="{$req.select.MainImageH}" />
        	<input type="hidden" name="MainImg" value="{$req.select.MainImg}" />
		
			
		<tr>
			<td colspan="2" align="left" width="300px" >
			
           <!-- <iframe marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="margin: 0px;padding:5px 0; z-index:100;border: 0px ; float:left;" height="75" width="300px" src="uploadfile.php?op=2&ut=3&res={$smarty.session.TemplateName}&idfn=MainIMG2&idhn=MainImageH"></iframe>-->
           	<div style="padding:10px 0;">
            <input type="file" id="photo_tmp_b"  />
             <span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>
            <div style="clear:both;"></div>
            <div style="width:300px; margin-top:5px;">
            For perfect fit, the image size is 497 x 195 pixels</div>
            </div>
			</td>
		</tr>
		
        <tr>
        	<td colspan="2" align="left" valign="top"><img src="{$req.select.MainImgDis}" border="1" width="{if $req.select.MainImgDis eq 'images/big1_logo.gif'}250{else}{$req.MainImgW}{/if}" id="MainIMG2" name="MainIMG2" height="{if $req.select.MainImgDis eq 'images/big1_logo.gif'}98{else}{$req.MainImgH}{/if}"/>
        	</td>
        </tr>
        <tr>
        <td colspan="2" align="left" valign="middle" height="50">
        	<a href="#" onclick="ImgBlank(1);"><img src="/skin/red/images/bu-delete-sm.jpg" border="0"  /></a>     	
        </td>
        </tr>
        </table>

      

        </div>

        <div style="float:right; margin-right:10px;">

		<img id="img_tmp_images" src="/skin/red/images/templateB-big.jpg" alt="" />

		</div>
  </div></div>
  
  
  <div id="temp_c_detail" style="{if $smarty.session.TemplateName neq 'tmp-n-b'}display:none;{/if}">
		<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;"><img src="images/spacer.gif" alt="" /></div>
<div id='tmp_contents_c'>		
        <h3 style="font-size: 16px; color: rgb(102, 102, 102); font-weight: bold;">Add your feature image</h3>
		<div style=" clear:both;"></div>
		<div style="float:left; width:300px;">
    	A feature image can be
        <ul class="arrows" style="width:250px;">
        <li><strong>a logo,</strong></li>
        <li><strong>an item you are selling,</strong></li>
		<li><strong>or any picture of your choice</strong></li>
        </ul>
        
        <p>Your feature image will give your website the color and feel that you want to express to your buyers (in accordance with the "terms of use")</p>
        <p><strong>For template B, the image size should be<br/> 497X195 pixels.</strong></p>
		<p><strong>For template C, the image size should be<br/> 243X212 pixels.</strong></p>
		<table cellpadding="0" cellspacing="0">
        <tr>
        <td colspan="2" style="width:300px;">Image &nbsp;&nbsp;&nbsp;<font style="color: rgb(119, 119, 119); font-size: 12px;">(Supported image type are jpg, gif, png)</font>
		</td>
		</tr>

        	<input name="MainImageH_c" id="MainImageH_c" type="hidden" value="{$req.select.MainImageH}" />
        	<input type="hidden" name="MainImg_c" id="MainImg_c" value="{$req.select.MainImg}" />
		
			
		<tr>
			<td colspan="2" align="left" width="300px" >
			
           <!-- <iframe marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="margin: 0px;padding:5px 0; z-index:100;border: 0px ; float:left;" height="75" width="300px" src="uploadfile.php?op=2&ut=3&res={$smarty.session.TemplateName}&idfn=MainIMG2&idhn=MainImageH"></iframe>-->
           	<div style="padding:10px 0;">
            <input type="file" id="photo_tmp_c"  />
             <span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.</span></span></a></span>
            <div style="clear:both;"></div>
            <div style="width:300px; margin-top:5px;">
            For perfect fit, the image size is 243 x 212 pixels</div>
            </div>
			</td>
		</tr>
		
        <tr>
        	<td colspan="2" align="left" valign="top"><img src="{$req.select.MainImgDis}" border="1" width="{if $req.select.MainImgDis eq 'images/big1_logo.gif'}185{else}{$req.MainImgW}{/if}" id="MainIMG2_c" name="MainIMG2_c" height="{if $req.select.MainImgDis eq 'images/big1_logo.gif'}130{else}{$req.MainImgH}{/if}"/>
        	</td>
        </tr>
        <tr>
        <td colspan="2" align="left" valign="middle" height="50">
        	<a href="#" onclick="ImgBlank(1);"><img src="/skin/red/images/bu-delete-sm.jpg" border="0"  /></a>     	
        </td>
        </tr>
        </table>

      

        </div>

        <div style="float:right; margin-right:10px;">

		<img id="img_tmp_images" src="/skin/red/images/templateC-big.jpg" alt="" />

		</div>
  </div></div>
    
  
      {/if}
		
		<br	/>
		{if $session.attribute eq '0'}
			<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:50px 20px 30px 0;"><img src="images/spacer.gif" alt="" /></div>
		
			<h3 style="font-size:16px; color:#666; font-weight:bold;">Select an Icon (optional)</h3>
			<ul id="chooseicon">
			{foreach from=$categories item=category}
				<li><img src="/skin/red/images/icons_lg/{$category.id}.png" width="60" height="40" alt="" /><br /><input type="radio" name="WebsiteIconID" value="{$category.id}" {if $req.WebsiteIconID eq $category.id}checked{/if} /></li>   
			{/foreach}
			</ul>
			
		{elseif $session.attribute eq '1' }
			{include file="estate/select_template_upload.tpl"}
		{elseif $session.attribute eq '2' }
			{include file="auto/select_template_upload.tpl"}
		{elseif $session.attribute eq '3' }
			{include file="job/select_template_upload.tpl"}
		{elseif $session.attribute eq '5' }
			{include file="foodwine/select_template_upload.tpl"}
		{/if}
		
		

</div>
</li>
</ul>    
    <p style="clear:both;">&nbsp;</p>
		<fieldset style="text-align:right; margin-right:40px;">
			<input type="hidden" name="cp" value="">
			<input src="
			{if $stepButtonStatu.step3 and $smarty.session.attribute == 0}
				/skin/red/images/buyseller_step_button/edit_products.gif
			{else}
				/skin/red/images/bu-nextsave.gif
			{/if}
			" class="submit form-save" type="image" onclick="document.mainForm.cp.value='next';">
			<!--<input src="/skin/red/images/bu-saveexit.gif" class="submit form-save" type="image" onclick="document.mainForm.cp.value='save';">-->
		</fieldset>
		
	</form>
	