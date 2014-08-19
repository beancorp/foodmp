<script src="/js/lightbox_plus.js" type="text/javascript"></script>
<script src="/skin/red/js/uploadImages.js" type="text/javascript"></script>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen"/>
<script type="text/javascript">
  var tmpl = "{$smarty.session.TemplateName}";
  var protype =0;
  var soc_http_host="{$soc_http_host}";
  {literal}
  $(function () {
      $("#photo_tmp_b").makeAsyncUploader({
          upload_url: soc_http_host + "uploadgallery.php?type=seller_product&ut=3&res=tmp-n-a",
          flash_url: '/skin/red/js/swfupload.swf',
          button_image_url: '/skin/red/images/blankButton.png',
          disableDuringUpload: 'INPUT[type="submit"]',
          file_types: '*.jpg;*.gif;*.png',
          file_size_limit: '10MB',
          file_types_description: 'All images',
          button_window_mode: "transparent",
          button_text: "",
          height: "29",
          debug: false
      });
      $("#photo_tmp_c").makeAsyncUploader({
          upload_url: soc_http_host + "uploadgallery.php?type=seller_product&ut=3&res=tmp-n-b",
          flash_url: '/skin/red/js/swfupload.swf',
          button_image_url: '/skin/red/images/blankButton.png',
          disableDuringUpload: 'INPUT[type="submit"]',
          file_types: '*.jpg;*.gif;*.png',
          file_size_limit: '10MB',
          file_types_description: 'All images',
          button_window_mode: "transparent",
          button_text: "",
          height: "29",
          debug: false
      });
      $("#store_banner").makeAsyncUploader({
          upload_url: soc_http_host + "uploadgallery.php?type=store_banner&ut=0",
          flash_url: '/skin/red/js/swfupload.swf',
          button_image_url: '/skin/red/images/bu-step3-upload.gif',
          disableDuringUpload: 'INPUT[type="submit"]',
          file_types: '*.jpg;*.gif;*.png',
          file_size_limit: '10MB',
          file_types_description: 'All images',
          button_window_mode: "transparent",
          button_text: "",
          height: "34",
          upload_success_handler: function (file, response) {
              var aryResult = response.split('|');
              if (aryResult[2] && $.trim(aryResult[2]) == 'store_banner') {
                  if (aryResult[3] == 114) {
                      alert("Please upload a banner with correct size.");
                  } else {
                      $("#img_banner").attr('src', aryResult[0]);
					  $("#colour_box").show();
                      $("#txt_banner").val(aryResult[0]);
                  }
              }
          },
          debug: false
      });
  });

  function tmp_change(val) {
      tips_tmp_change = 'Note: Please update your feature image if you changed the template for your website.';
      if (val == 'tmp-n-e') {
          $("#temp_c_detail,#temp_b_detail,#div_tmp_change_tips").hide();
      }
      if (val == 'tmp-n-a') {
          $("#div_tmp_change_tips").html(tips_tmp_change).show();
          $("#temp_c_detail").hide();
          $("#temp_b_detail").show();
      }
      if (val == 'tmp-n-b') {
          $("#div_tmp_change_tips").html(tips_tmp_change).show();
          $("#temp_c_detail").show();
          $("#temp_b_detail").hide();
      }
  }

  function uploadresponse(response) {
      var aryResult = response.split('|');
      if (aryResult[2] && $.trim(aryResult[2]) == 'seller_product') {
          $("#MainIMG2,#MainIMG2_c").attr('src', aryResult[0]);
          $("#MainImageH").val(aryResult[0]);
      }
  }

  function uploadprocess(bl) {
      if (!bl) {
          $('INPUT[type="submit"]').css('background', 'url(/skin/red/images/buttons/bu_save_grey.gif)');
      } else {
          $('INPUT[type="submit"]').css('background', 'url(/skin/red/images/buttons/bu_save.gif)');
      }
  }

  function ImgBlank(picType) {
      if (confirm("Are you sure you want to delete?")) {
          if (picType == 1) {
              if ($('input[name="TemplateName"]').val() == 'tmp-n-a') {
                  MM_swapImage('MainIMG2', '', 'images/big1_logo.gif', 1);
              } else {
                  MM_swapImage('MainIMG2', '', 'images/imagetemp.gif', 1);
              }
              document.mainForm.MainImageH.value = '';
              document.mainForm.cp.value = 'delmain';
          } else {
              MM_swapImage('LogoImg2', '', 'images/templogo.gif', 1);
              document.mainForm.LogoImageH.value = '';
              document.mainForm.cp.value = 'dellogo';
          }
          document.mainForm.submit();
      }
  }

  function deleteBanner() {
	  {/literal}var image = '/template_images/banner/{$req.info.subAttrib}.jpg';{literal}
      $("#img_banner").attr('src', image);
      $("#txt_banner").val('');
	  $("#colour_box").hide();
  }
  $(function () {
      $(".li-title a.oper").each(function () {
          $(this).click(function () {
              index = $(this).attr('index');
              isclose = $("#step3_title_" + index).hasClass('close');
              tab_display = '';
              if (isclose) {
                  tab_display = 'show';
                  $("#step3_title_" + index).addClass('open');
                  $("#step3_title_" + index).removeClass('close');
              } else {
                  tab_display = "hide";
                  $("#step3_title_" + index).addClass('close');
                  $("#step3_title_" + index).removeClass('open');
              }
              $("#step3_content_" + index).animate({
                  height: tab_display,
                  opacity: tab_display
              }, 'slow');
          });
      });
  });
  {/literal}
</script>
<style>
{literal}
	#colour_box {
		margin-top: 10px;
	}
	.colour_element {
		width: 25px; 
		height: 25px; 
		display: inline-block;
	}

{/literal}
</style>
<form name="mainForm" action="" method="POST">
	<ul class="step3-ul">
		 {if $req.info.attribute neq '0' || 1}
		<li class="step3-li">
		<div id="step3_title_1" class="li-title close top">
			<div class="title-text">
				<h3>
				<strong>
				Premium Customised Template - </strong>
				<label class="italic">
				Upload your customised banner </label>
				</h3>
				<a onfocus="this.blur();" href="javascript:void(0);" class="oper" index="1">
				&nbsp; </a>
			</div>
		</div>
		<div class="li-content" id="step3_content_1">
			<div class="col-left">
				<div class="left-top">
					<h2>
					Customise the banner </h2>
					<p>
						 You can design your own banner that best reflects your brand. <br/>
						The banner needs to be <strong>
						950 pixels wide by 75 pixels tall. </strong>
					</p>
				</div>
				<div class="left-middle">
					<div class="banner-upload">
						<input type="file" id="store_banner"/>
						<span style="padding:10px 0;">
						&nbsp;&nbsp;| <a href="javascript:deleteBanner();void(0);" style="text-decoration: none;"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" border="0" align="absmiddle"/></a>
						&nbsp;&nbsp; <a class="help" href="#">
						<img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top"/>
						<span>
						<span style="color:#777;">
						To give your page impact we recommend you load a photo to your website. Simply click 'Select File' and go to the location on your computer where the photo is saved. </span>
						</span>
						</a>
						</span>
						<div style="clear:both;">
						</div>
					</div>
					<input name="txt_banner" id="txt_banner" class="inputB" type="hidden" value="{$req.bannerImg}" style="width:398px;" readonly="readonly"/>
				</div>
				<div class="left-bottom">
					<img src="{if $req.bannerImg}{$req.bannerImg}{else}/template_images/banner/{$req.info.subAttrib}.jpg{/if}" name="img_banner" border="1" id="img_banner" width="410"/>
				</div>
	
				<div id="colour_box" {if not $req.bannerImg}style="display:none;"{/if}>
					<h3 style="font-size:16px; color:#666; font-weight:bold;">Choose Colour</h3>
					<ul id="choosecolor">
						{foreach from=$colour_list item=colour}
						
						<li>
							<span class="colour_element" style="background-color: #{$colour.ColorValue}">&nbsp;</span>							
							<br/>
							<input type="radio" name="TemplateBGColor" value="{$colour.ColorID}" {if $req.TemplateBGColor eq $colour.ColorID} checked {/if}/>
						</li>
						
						{/foreach}
					</ul>
				</div>
				
				{if not $req.bannerImg}
					<input type="hidden" name="TemplateBGColor" value="{$default_bgcolour}" />
				{/if}
			</div>
			<!--
			<div class="col-right">
				<label>
				Example banner </label>
				<img alt="Example banner" src="/skin/red/images/example-banner.jpg"/>
				<label>
				Example banner in place </label>
				<img alt="Example banner in place" src="/skin/red/images/example-banner-in-place.jpg"/>
			</div>
			-->
			<div class="clear">
			</div>
		</div>
		</li>
		 {/if}
		<li class="step3-li">
		<div id="step3_title_2" class="li-title open">
			<div class="title-text">
				<h3>
				<strong>
				Customised Template - </strong>
				<label class="italic">
				Select your template, colour and icons. </label>
				</h3>
				<a onfocus="this.blur();" href="javascript:void(0);" class="oper" index="2">
				&nbsp; </a>
			</div>
		</div>
		
		<div class="li-content" id="step3_content_2" style="display:block;">
			<div id="loadingImage">
			</div>
			<h3 style="font-size:16px; color:#666; font-weight:bold; display:none;">
			Choose Template </h3>
			{if $req.info.foodwine_type eq 'food'}
			<table width="720" cellpadding="0" cellspacing="0" id="choosetemplate">
			<tr>
			
			
				<td width="33%" align="center" valign="top">
					<h3 style="font-size:20px; color:#666; font-weight:bold;">
					A </h3>
					<a href="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-a.jpg" rel="lightbox">
					<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-a-s.jpg" border="0"/>
					</a>
				</td>
				<td width="33%" align="center" valign="top">
					<h3 style="font-size:20px; color:#666; font-weight:bold;">
					B </h3>
					<a href="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-b.jpg" rel="lightbox">
					<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-b-s.jpg" alt="" border="0"/>
					</a>
				</td>
				<td width="33%" align="center" valign="top">
					<h3 style="font-size:20px; color:#666; font-weight:bold;">
					C </h3>
					<a href="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-c.jpg" rel="lightbox">
					<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-c-s.jpg" border="0"/>
					</a>
				</td>
				<td width="33%" align="center" valign="top">
					<h3 style="font-size:20px; color:#666; font-weight:bold;">
					D </h3>
					<a href="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-h.jpg" rel="lightbox">
					<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-h-s.jpg" border="0"/>
					</a>
				</td>
			</tr>
			<tr>
				<td align="center">
					<p style="text-align:center;">
						<strong>
						Template A </strong>
					</p>
				</td>
				<td align="center">
					<p style="text-align:center;">
						<strong>
						Template B </strong>
					</p>
				</td>
				<td align="center">
					<p style="text-align:center;">
						<strong>
						Template C </strong>
					</p>
				</td>
				<td align="center">
					<p style="text-align:center;">
						<strong>
						Template D </strong>
					</p>
				</td>
			</tr>
			<tr>
				<!--
				<td height="25" align="center">
					<input type="radio" {if $req.info.sold_status eq 0}disabled="disabled"{/if} name="TemplateName" id="TemplateName" value="foodwine-a" {if ($req.info.sold_status eq 1) and ($req.TemplateName eq '' or $req.TemplateName eq 'foodwine-a')}checked{/if}/>
				</td>
				-->
				<td height="25" align="center">
					<input type="radio" name="TemplateName" id="TemplateName" value="foodwine-a" {if ($req.TemplateName eq '' or $req.TemplateName eq 'foodwine-a')}checked{/if}/>
				</td>
				<td align="center">
					<input type="radio" name="TemplateName" id="TemplateName" value="foodwine-b" {if $req.TemplateName eq 'foodwine-b'}checked{/if}/>
				</td>
				<td align="center">
					<input type="radio" name="TemplateName" id="TemplateName" value="foodwine-c" {if $req.TemplateName eq 'foodwine-c'}checked{/if}/>
				</td>
				<td align="center">
					<input type="radio" name="TemplateName" id="TemplateName" value="foodwine-h" {if $req.TemplateName eq 'foodwine-h'}checked{/if}/>
				</td>
			</tr>
			</table>
			{else}
			<table width="720" cellpadding="0" cellspacing="0" id="choosetemplate">
				<tr>
					<td width="33%" align="center" valign="top">
						<h3 style="font-size:20px; color:#666; font-weight:bold;">A</h3>
						<a href="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-d.jpg" rel=lightbox  ><img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-d-s.jpg" border="0" /></a>
					</td>
					<td width="33%" align="center" valign="top">
						<h3 style="font-size:20px; color:#666; font-weight:bold;">B</h3>
						<a href="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-e.jpg" rel=lightbox  ><img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-e-s.jpg" border="0" /></a>
					</td>
					<td width="33%" align="center" valign="top">
						<h3 style="font-size:20px; color:#666; font-weight:bold;">C</h3>
						<a href="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-f.jpg" rel=lightbox  ><img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-f-s.jpg" alt="" border="0" /></a>
					</td>
					<td width="33%" align="center" valign="top">
						<h3 style="font-size:20px; color:#666; font-weight:bold;">D</h3>
						<a href="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-g.jpg" rel=lightbox  ><img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-g-s.jpg" border="0" /></a>
					</td>
				</tr>
				<tr>
				<td align="center">
					<p style="text-align:center;"><strong>Template A</strong></p>
				</td>
				<td align="center">
					<p style="text-align:center;"><strong>Template B</strong></p>
				</td>
				<td align="center">
					<p style="text-align:center;"><strong>Template C</strong></p>
				</td>
				<td align="center">
					<p style="text-align:center;"><strong>Template D</strong></p>
				</td>
				</tr>
				<tr>
					  <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-d" {if $req.TemplateName eq 'foodwine-d'}checked{/if} /></td>
					  <td height="25" align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-e" {if $req.TemplateName eq '' or $req.TemplateName eq 'foodwine-e'}checked{/if} /></td>
					  <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-f" {if $req.TemplateName eq 'foodwine-f'}checked{/if} /></td>
					  <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-g" {if $req.TemplateName eq 'foodwine-g'}checked{/if} /></td>
			  </tr>
			</table>
			{/if}
			<br/>
				{literal}
				<style type="text/css">
				  span#main_swf_upload_uploading .uploadcancel,span#mainImage2_swf_upload_uploading .uploadcancel,span#mainImage3_swf_upload_uploading .uploadcancel,span#mainImage4_swf_upload_uploading .uploadcancel{
					border:0;
					margin-right:15px;
				  }
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
					$(function () {
						$("#main_swf_upload").makeAsyncUploader({
							upload_url: soc_http_host + "uploadproduct_img.php?objImage=mainImage0&tpltype=6&attrib=0&index=0",
							flash_url: '/skin/red/js/swfupload.swf',
							button_image_url: '/skin/red/images/blankButton.png',
							disableDuringUpload: 'INPUT[type="submit"]',
							file_types: '*.jpg;*.gif;*.png',
							file_size_limit: '10MB',
							file_types_description: 'All images',
							button_window_mode: "transparent",
							button_text: "",
							height: "29",
							debug: false
						});
						$("#mainImage3_swf_upload").makeAsyncUploader({
							upload_url: soc_http_host + "uploadproduct_img.php?objImage=mainImage2&tpltype=6&attrib=2&index=0",
							flash_url: '/skin/red/js/swfupload.swf',
							button_image_url: '/skin/red/images/blankButton.png',
							disableDuringUpload: 'INPUT[type="submit"]',
							file_types: '*.jpg;*.gif;*.png',
							file_size_limit: '10MB',
							file_types_description: 'All images',
							button_window_mode: "transparent",
							button_text: "",
							height: "29",
							debug: false
						});
						$("#mainImage2_swf_upload").makeAsyncUploader({
							upload_url: soc_http_host + "uploadproduct_img.php?objImage=mainImage3&tpltype=6&attrib=3&index=0",
							flash_url: '/skin/red/js/swfupload.swf',
							button_image_url: '/skin/red/images/blankButton.png',
							disableDuringUpload: 'INPUT[type="submit"]',
							file_types: '*.jpg;*.gif;*.png',
							file_size_limit: '10MB',
							file_types_description: 'All images',
							button_window_mode: "transparent",
							button_text: "",
							height: "29",
							debug: false
						});
						$("#mainImage4_swf_upload").makeAsyncUploader({
							upload_url: soc_http_host + "uploadproduct_img.php?objImage=mainImage1&tpltype=6&attrib=1&index=0",
							flash_url: '/skin/red/js/swfupload.swf',
							button_image_url: '/skin/red/images/blankButton.png',
							disableDuringUpload: 'INPUT[type="submit"]',
							file_types: '*.jpg;*.gif;*.png',
							file_size_limit: '10MB',
							file_types_description: 'All images',
							button_window_mode: "transparent",
							button_text: "",
							height: "29",
							debug: false
						});
						$("#mainImage5_swf_upload").makeAsyncUploader({
							upload_url: soc_http_host + "uploadproduct_img.php?objImage=mainImage4&tpltype=6&attrib=4&index=0",
							flash_url: '/skin/red/js/swfupload.swf',
							button_image_url: '/skin/red/images/blankButton.png',
							disableDuringUpload: 'INPUT[type="submit"]',
							file_types: '*.jpg;*.gif;*.png',
							file_size_limit: '10MB',
							file_types_description: 'All images',
							button_window_mode: "transparent",
							button_text: "",
							height: "29",
							debug: false
						});
						$("#mainImage6_swf_upload").makeAsyncUploader({
							upload_url: soc_http_host + "uploadproduct_img.php?objImage=mainImage5&tpltype=6&attrib=5&index=0",
							flash_url: '/skin/red/js/swfupload.swf',
							button_image_url: '/skin/red/images/blankButton.png',
							disableDuringUpload: 'INPUT[type="submit"]',
							file_types: '*.jpg;*.gif;*.png',
							file_size_limit: '10MB',
							file_types_description: 'All images',
							button_window_mode: "transparent",
							button_text: "",
							height: "29",
							debug: false
						});
						$("#upload_flyer_image").makeAsyncUploader({
							upload_url: soc_http_host + "uploadproduct_img.php?objImage=flyerimage&tpltype=7&attrib=5&index=0",
							flash_url: '/skin/red/js/swfupload.swf',
							button_image_url: '/skin/red/images/blankButton.png',
							disableDuringUpload: 'INPUT[type="submit"]',
							file_types: '*.jpg;*.gif;*.png',
							file_size_limit: '10MB',
							file_types_description: 'All images',
							button_window_mode: "transparent",
							button_text: "",
							height: "29",
							debug: false
						});
					});

					function uploadresponse(response) {
						var aryResult = response.split('|');
						if (aryResult.length > 3) {
							var objRes = aryResult[0];
							var imgobj = $("#" + objRes + "_dis");
							$(imgobj).attr('src', "/" + aryResult[1]);
							$(imgobj).css('width', aryResult[2]);
							$(imgobj).css('height', aryResult[3]);
							$("#" + objRes + "_svalue").val("/" + aryResult[4]);
							$("#" + objRes + "_bvalue").val("/" + $.trim(aryResult[5]));
						}
					}

					function uploadprocess(bl) {}
				</script>
				{/literal}
			<div id="image_selection" style="position:relative; width:755px; float:left;">
				<div>
					<div style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:20px 20px 30px 0;">
						<img src="/images/spacer.gif" alt=""/>
					</div>
					<h1 class="foodwine-h-step2-addfeature">
					<span>
					Add Your Logo & Feature Image </span>
					</h1>
					<div style="float:left; width:300px;">
						<font style="font-weight:bold; font-size:14px;">
						Logo </font>
						</strong>
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<a id="mainImage3_swf_upload" href="javascript:uploadImage(6, 2, 0, 'mainImage2' );void(0);">
									<img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle"/>
									</a>
									<input name="mainImage2_svalue" id="mainImage2_svalue" type="hidden" value="{$req.images.mainImage.2.sname.text}"/>
									<input name="mainImage2_bvalue" id="mainImage2_bvalue" type="hidden" value="{$req.images.mainImage.2.bname.text}"/>
									<span style="float:left; margin-top:5px;">
									<a class="help" href="#">
									<img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top"/>
									<span>
									<span style="color:#777;">
									To give your page impact we recommend you load a photo to your website. Simply click 'Select File' and go to the location on your computer where the photo is saved. </span>
									</span>
									</a>
									</span>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="left" valign="top">
									 For perfect fit, the image size is 242 x 201 pixels
								</td>
							</tr>
							<tr>
								<td colspan="2" align="left" valign="top">
									{if $req.images.mainImage.2.sname.text neq '/images/121x100.jpg'}
										<img src="{$req.images.mainImage.2.sname.text}" name="mainImage2_dis" border="1" id="mainImage2_dis" width="{$req.images.mainImage.2.sname.width}" height="{$req.images.mainImage.2.sname.height}"/>
									{else}
									  {if $req.info.subAttrib eq 8}
										<img src="/template_images/default_fastfood.jpg" name="mainImage2_dis" border="1" id="mainImage2_dis" width="{$req.images.mainImage.2.bname.width}" />
									  {elseif $req.info.subAttrib eq 1}
										<img src="/template_images/default_restaurants.png" name="mainImage2_dis" border="1" id="mainImage2_dis" width="{$req.images.mainImage.2.bname.width}" />
									  {else}
										{if $req.info.foodwine_type eq 'food'}
											<img src="/template_images/default_logo.jpg" name="mainImage2_dis" border="1" id="mainImage2_dis" width="{$req.images.mainImage.2.sname.width}" height="{$req.images.mainImage.2.sname.height}"/>
										{else}
											<img src="/template_images/default_wine_logo.jpg" name="mainImage2_dis" border="1" id="mainImage2_dis" width="{$req.images.mainImage.2.sname.width}" height="{$req.images.mainImage.2.sname.height}"/>
										{/if}
									  {/if}
									{/if}
								</td>
							</tr>
							<tr>
								<td colspan="2" align="left" valign="middle" height="50">
									<a href="javascript:deleteImage(6, 2, 0, 'mainImage2' );void(0);">
									<img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle"/>
									</a>
								</td>
							</tr>
						</table>
						<input type="hidden" name="logoDisplay" value="1" />
						<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<p>
									<strong style="font-weight:bold; font-size:14px; padding-left:5px;">Feature Image </strong>
								</p>
								<a id="main_swf_upload" href="javascript:uploadImage(6, 0, 0, 'mainImage0' );void(0);">
								<img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle"/>
								</a>
								<input name="mainImage0_svalue" id="mainImage0_svalue" type="hidden" value="{if $req.images.mainImage.0.sname.text neq '/images/253x105.jpg'}{$req.images.mainImage.0.sname.text}{/if}"/>
								<input name="mainImage0_bvalue" id="mainImage0_bvalue" type="hidden" value="{if $req.images.mainImage.0.bname.text neq '/images/497x206.jpg'}{$req.images.mainImage.0.bname.text}{/if}"/>
								<span style="margin-top:5px; float:left">
								<a class="help" href="#">
								<img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top"/>
								<span>
								<span style="color:#777;">
								To give your page impact we recommend you load a photo to your website. Simply click 'Select File' and go to the location on your computer where the photo is saved. </span>
								</span>
								</a>
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="left" valign="top">
								 For perfect fit, the image size is 497 x 206 pixels
							</td>
						</tr>
						<tr>
							<td colspan="2" align="left" valign="top">
								{if $req.images.mainImage.0.sname.text neq '/images/253x105.jpg'}
									<img src="{$req.images.mainImage.0.sname.text}" name="mainImage0_dis" border="1" id="mainImage0_dis" width="{$req.images.mainImage.0.sname.width}" height="{$req.images.mainImage.0.sname.height}"/>
								{else}
									<img src="/template_images/featured/{$req.info.subAttrib}.jpg" name="mainImage0_dis" border="1" id="mainImage0_dis" width="{$req.images.mainImage.0.sname.width}" height="{$req.images.mainImage.0.sname.height}"/>
								{/if}
							</td>
						</tr>
						<tr>
							<td colspan="2" align="left" valign="middle" height="50">
								<a href="javascript:deleteImage(6, 0, 0, 'mainImage0' );void(0);">
								<img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle"/>
								</a>
							</td>
						</tr>
						</table>
						<div class="seller-setting-fieldset">
							<div class="seller-setting-title">
								<p>
									<strong style="font-weight:bold; font-size:14px;">Search Results logo</strong>
								</p>
							</div>
							<div class="seller-setting-search">
								<table width="100%" cellpadding="0" cellspacing="0" style="">
								<tr>
									<td>
										<a id="mainImage6_swf_upload" href="javascript:uploadImage(6, 5, 0, 'mainImage5' );void(0);">
										<img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle"/>
										</a>
										<input name="mainImage5_svalue" id="mainImage5_svalue" type="hidden" value="{$req.images.mainImage.5.sname.text}"/>
										<input name="mainImage5_bvalue" id="mainImage5_bvalue" type="hidden" value="{$req.images.mainImage.5.bname.text}"/>
										<span style="margin-top:5px; float:left">
										<a class="help" href="#">
										<img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top"/>
										<span>
										<span style="color:#777;">
										To give your page impact we recommend you load a photo to your website. Simply click 'Select File' and go to the location on your computer where the photo is saved. </span>
										</span>
										</a>
										</span>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="top">
										 If you upload the "Search Results logo", the system will show this logo on the search results page instead of the store logo. <br/>
										For perfect fit, please make sure the image size is 242 x 201 pixels.
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="top">
										{if $req.images.mainImage.5.sname.text neq ''}
											<img src="{$req.images.mainImage.5.sname.text}" name="mainImage5_dis" border="1" id="mainImage5_dis" width="{$req.images.mainImage.5.sname.width}" height="{$req.images.mainImage.5.sname.height}"/>
										{else}
											<img src="{$default_search_image}" name="mainImage5_dis" id="mainImage5_dis" title="{$product.bu_name}" width="120" height="91" border="0"/></a>
										{/if}
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="middle" height="50">
										<a href="javascript:deleteImage(6, 5, 0, 'mainImage5' );void(0);">
										<img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle"/>
										</a>
									</td>
								</tr>
								</table>
							</div>
						</div>
						<div class="seller-setting-fieldset">
							<div class="seller-setting-title">
								<p>
									<strong style="font-weight:bold; font-size:14px;">Email Alert Image</strong>
								</p>
							</div>
							<div class="seller-setting-search">
								<table width="100%" cellpadding="0" cellspacing="0" style="">
								<tr>
									<td>
										<a id="upload_flyer_image" href="javascript:uploadImage(6, 5, 0, 'flyerimage' );void(0);">
										<img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle"/>
										</a>
										<input name="flyerimage_svalue" id="flyerimage_svalue" type="hidden" value="{$req.template.emailalert_image}"/>
										<input name="flyerimage_bvalue" id="flyerimage_bvalue" type="hidden" value="{$req.template.emailalert_image}"/>
										<span style="margin-top:5px; float:left">
										<a class="help" href="#">
										<img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top"/>
										<span>
										<span style="color:#777;">
											To give your page impact we recommend you load a photo to your website. Simply click 'Select File' and go to the location on your computer where the photo is saved. </span>
										</span>
										</a>
										</span>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="top">
										{if $req.template.emailalert_image neq ''}
											<img src="{$req.template.emailalert_image}" name="flyerimage_dis" id="flyerimage_dis" title="" width="560" border="0"/></a>
										{else}
											<img src="/template_images/hotbuy/{$req.info.subAttrib}.jpg" name="flyerimage_dis" id="flyerimage_dis" title="" width="560" border="0"/></a>
										{/if}
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="middle" height="50">
										<a href="javascript:deleteImage(6, 5, 0, 'flyerimage' );void(0);">
										<img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle"/>
										</a>
									</td>
								</tr>
								</table>
							</div>
						</div>
					</div>
					{if $req.info.foodwine_type eq 'food'}
					<!-- FOOD TEMPLATE -->
					<div id="disfoodwine-a" style="float:right; margin-right:10px; display:none">
						<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-a.jpg" alt="Template A" width="370px" />
					</div>
					<div id="disfoodwine-b" style="float:right; margin-right:10px; display:none">
						<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-b.jpg" alt="Template B" width="370px" />
					</div>
					<div id="disfoodwine-c" style="float:right; margin-right:10px; display:none">
						<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-c.jpg" alt="Template C" width="370px" />
					</div>
					<div id="disfoodwine-h" style="float:right; margin-right:10px; display:none">
						<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-h.jpg" alt="Template D" width="370px" />
					</div>
					{else}
					<!-- WINE TEMPLATE -->
					<div id="disfoodwine-d" style="float:right; margin-right:10px;display:none">
						<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-d.jpg" alt="Template A" width="370px" />
					</div>
					<div id="disfoodwine-e" style="float:right; margin-right:10px;display:none">
						<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-e.jpg" alt="Template B" width="370px" />
					</div>
					<div id="disfoodwine-f" style="float:right; margin-right:10px;display:none">
						<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-f.jpg" alt="Template C" width="370px" />
					</div>
					<div id="disfoodwine-g" style="float:right; margin-right:10px;display:none">
						<img src="/skin/red/theme_selection/{$req.info.subAttrib}/foodwine-g.jpg" alt="Template D" width="370px" />
					</div>
					{/if}
				</div>
				<fieldset style="text-align:right; margin-right:10px; padding-top: 20px;">
					<input type="hidden" name="cp" value="">
					<input src="{if $stepButtonStatu.step3 and $smarty.session.attribute == 0}/skin/red/images/buyseller_step_button/edit_products.gif{else}/skin/red/images/bu-nextsave.gif{/if}" class="submit form-save" type="image" onclick="document.mainForm.cp.value='next';">
				</fieldset>
				<div class="clear">
				</div>
			</div>
		</div>
		</li>
	</ul>
	<p style="clear:both;">
		 &nbsp;
	</p>
</form>