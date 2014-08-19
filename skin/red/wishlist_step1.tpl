<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/js/lightbox_plus.js"></script>
<script>
var StoreID = "{$smarty.session.ShopID}";
var soc_http_host="{$soc_http_host}";
</script>
<script language="javascript">
	var xmas_s = {if $req.banner.SYSTEM.xmas[$req.wishinfo.banner]}{$req.wishinfo.banner}{else}0{/if};
	var birthday_s = {if $req.banner.SYSTEM.birthday[$req.wishinfo.banner]}{$req.wishinfo.banner}{else}0{/if};
	var college_s = {if $req.banner.SYSTEM.college[$req.wishinfo.banner]}{$req.wishinfo.banner}{else}0{/if};
	var general_s = {if $req.banner.SYSTEM.general[$req.wishinfo.banner]}{$req.wishinfo.banner}{else}0{/if};
	var wedding_s = {if $req.banner.SYSTEM.wedding[$req.wishinfo.banner]}{$req.wishinfo.banner}{else}0{/if};
</script>
{literal}
	<style type="text/css">
		.templatelist{
			list-style:none;
			margin-left:15px;
			float:left;
			_margin-left:10px;
			width:730px;
			overflow:hidden;
		}
		.templatelist li{
			list-style:none;
			float:left;
			width:124px;
			margin-right:20px;
			margin-bottom:20px;
			text-align:center;
			overflow:hidden;
		}
		.tabtmp{
			list-style:none;
			margin:0;
			float:left;
		}
		.tabtmp li{
			list-style:none;
			width:100px;
			height:40px;
			line-height:40px;
			text-align:center;
			float:left;
			cursor:pointer;
			font-weight:bold;
		}
		.tabtmp li.active_tab{
			background-color:#FFF;
		}
	</style>
    <script language="javascript">
		function checkformsubmit(){
			var errors = "";
			if($('input[@name="template"][@checked]').length==0){
				errors += '-  The template is required.\n';
			}
			if($('input[@name="banner"][@checked]').length==0){
				errors += '-  The banner is required.\n';
			}
			
			if($('input[@name="banner"][@checked]').val()=='user_template'){
				if($('input[@name="user_banner"][@checked]').length==0){
					errors += '-  The template backgroud is required.\n';
				}
			}
			if(errors!=""){
				errors = 'Sorry, the following fields are required.\n'+errors;
				alert(errors);
				return false;
			}else{
				return true;	
			}
		}
		
		function changetab(tab,obj){
			$('.tabtmp').children().removeClass("active_tab");
			obj.className = "active_tab";
			$('#xmas_tab').css('display','none');
			$('#birthday_tab').css('display','none');
			$('#college_tab').css('display','none');
			$('#general_tab').css('display','none');
			$('#custom_tab').css('display','none');
			$('#wedding_tab').css('display','none');
			$('#'+tab).css('display','');
			switch(tab){
				case 'xmas_tab':
					if(xmas_s==0){
						$('#xmas_1').attr('checked',true);
					}else{
						$('#xmas_'+xmas_s).attr('checked',true);
					}
					break;
				case 'birthday_tab':
					if(birthday_s==0){
						$('#birthday_13').attr('checked',true);
					}else{
						$('#birthday_'+birthday_s).attr('checked',true);
					}
					break;
				case 'college_tab':
					if(college_s==0){
						$('#college_16').attr('checked',true);
					}else{
						$('#college_'+college_s).attr('checked',true);
					}
					break;
				case 'general_tab':
					if(general_s==0){
						$('#general_27').attr('checked',true);
					}else{
						$('#general_'+general_s).attr('checked',true);
					}
					break;
				case 'wedding_tab':
					if(wedding_s==0){
						$('#wedding_85').attr('checked',true);
					}else{
						$('#wedding_'+wedding_s).attr('checked',true);
					}
					break;
				case 'custom_tab':
					$('#custom_1').attr('checked',true);
					if($('#custom_banner_s').val()){
						$('#'+$('#custom_banner_s').val()).attr('checked',true);
					}else{
						 $('#cus_bid_1').attr('checked',true);
					}
					break;
					
			}
		}
	$(function() {	
		$("#custom_photo").makeAsyncUploader({
				upload_url: soc_http_host+"uploadgallery.php?type=template",
				flash_url: '/skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/blankButton.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.jpg;*.gif;*.png',
				file_size_limit:'10MB',
				file_types_description:'All images',
				button_text:"",
				button_window_mode:"transparent",
				height:"29",
				debug:false
		 });
	});
	function uploadresponse(response){
		var aryResult = response.split('|');
		if(aryResult[2]&&$.trim(aryResult[2])=='template'){
			$("#disimg").attr('src',"/"+aryResult[0]);
			$("#usertpl_img").val("/"+aryResult[0]);
		}
	}
	function uploadprocess(bl){
		if(!bl){
			$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save_grey.gif)');
		}else{
			$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save.gif)');
		}
	}
	</script>
{/literal}
<form name="mainForm" act="" method="post" onsubmit="return checkformsubmit();">
<div style="margin-bottom:20px">
<div style="margin: 0pt 20px 20px 0pt; background: rgb(204, 204, 204) none repeat scroll 0% 0%; clear: both; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; height: 1px; line-height: 1px;"><img alt="" src="/skin/red/images/spacer-grey.gif"/></div>
 <h3 style="font-size:16px; color:#666; font-weight:bold;margin-top:2px;float:left;">Choose Template</h3>
 <div style="float:left;margin-left: 13px;">
     <img height="22" align="absmiddle" width="22" src="/skin/red/images/adminhome/icon-view.gif"/>
     <a style="width: auto; margin: 3px;" target="_blank" href="/soc.php?cp=wishlistSample">View Some Examples&nbsp;</a>
 </div>
 <div style="clear:both"></div>
 <table cellpadding="0" cellspacing="0">
 	<tr>
    	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">A</h3>
		<a href="/images/a.gif" rel=lightbox  >
			<img src="/images/a_s.gif" alt="" /></a>	</td>
	<td width="33%" align="center" valign="top">
		<h3 style="font-size:20px; color:#666; font-weight:bold;">B</h3>
		<a href="/images/b.gif" rel=lightbox  >
			<img src="/images/b_s.gif" alt="" /></a>	</td>
    <tr>
	<td align="center" valign="top">
		<p style="text-align:center;">To feature 1 item on your wish list homepage select template A.</p>
	</td>
	<td align="center" valign="top">        
		<p style="text-align:center;">To feature 4 items on your wish list homepage select template B.</p>
	</td>
	</tr>
    <tr>
	  <td align="center"><input type="radio" name="template" value="a" {if $req.wishinfo.template eq 'a'}checked{/if} /></td>
	  <td align="center"><input type="radio" name="template" value="b" {if $req.wishinfo.template eq 'b'}checked{/if} /></td>
  </tr>
    
 </table>
</div>
<div style="margin: 0pt 20px 30px 0pt; background: rgb(204, 204, 204) none repeat scroll 0% 0%; clear: both; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; height: 1px; line-height: 1px;"><img alt="" src="/skin/red/images/spacer-grey.gif"/></div>
 <h3 style="font-size:16px; color:#666; font-weight:bold;">Choose Color &nbsp;&nbsp;<a href="/skin/red/images/wishlist_color/purple.gif" rel=lightbox><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /></a></h3>
	<ul id="choosecolor" style="margin-left:15px;">
    <li><img alt="" src="/skin/red/images/color-purple.gif"/><br/>
    	<input type="radio" value="#453c8d" {if $req.wishinfo.color eq '#453c8d'}checked{/if} name="color"/></li>
    <li><img alt="" src="/skin/red/images/color-orange.gif"/><br/>
    	<input type="radio" value="#ffb914" {if $req.wishinfo.color eq '#ffb914'}checked{/if} name="color"/></li>
    <li><img alt="" src="/skin/red/images/color-blue.gif"/><br/>
    	<input type="radio" value="#80b0de" {if $req.wishinfo.color eq '#80b0de'}checked{/if} name="color"/></li>
    <li><img alt="" src="/skin/red/images/color-red.gif"/><br/>
    	<input type="radio" value="#d10435" {if $req.wishinfo.color eq '#d10435'}checked{/if} name="color"/></li>
    <li><img alt="" src="/skin/red/images/color-green.gif"/><br/>
    	<input type="radio" value="#006600" {if $req.wishinfo.color eq '#006600'}checked{/if} name="color"/></li>
    <li><img alt="" src="/skin/red/images/color-black.gif"/><br/>
    	<input type="radio" value="#777777" {if $req.wishinfo.color eq '#777777'}checked{/if} name="color"/></li>
    <li><img alt="" src="/skin/red/images/color-dark.gif"/><br/>
    	<input type="radio" value="#000000" {if $req.wishinfo.color eq '#000000' or $req.wishinfo.color eq ''}checked{/if} name="color"/></li>
   </ul>


<div style="margin-bottom:20px">
<div style="margin: 0pt 20px 30px 0pt; background: rgb(204, 204, 204) none repeat scroll 0% 0%; clear: both; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; height: 1px; line-height: 1px;"><img alt="" src="/skin/red/images/spacer-grey.gif"/></div>
 <h3 style="font-size:16px; color:#666; font-weight:bold;">Choose Wishlist Design</h3>
 <div style="background-color:#EEE; height:40px; margin:0 15px; border-bottom:1px solid #EEE;">
 	<ul class="tabtmp">
    	<li onclick="javascript:changetab('xmas_tab',this);" {if $req.banner.SYSTEM.xmas[$req.wishinfo.banner] or $req.wishinfo.banner eq ""}class="active_tab"{/if}>Christmas</li>
        <li onclick="javascript:changetab('birthday_tab',this);" {if $req.banner.SYSTEM.birthday[$req.wishinfo.banner]}class="active_tab"{/if}>Birthday</li>
        <li onclick="javascript:changetab('college_tab',this);" {if $req.banner.SYSTEM.college[$req.wishinfo.banner]}class="active_tab"{/if}>College</li>
        <li onclick="javascript:changetab('general_tab',this);" {if $req.banner.SYSTEM.general[$req.wishinfo.banner]}class="active_tab"{/if} style="width:120px;">All Occasions</li>
        <li onclick="javascript:changetab('wedding_tab',this);" {if $req.banner.SYSTEM.wedding[$req.wishinfo.banner]}class="active_tab"{/if} style="width:120px;">Weddings</li>
        <li onclick="javascript:changetab('custom_tab',this);" {if $req.banner.USER.id eq $req.wishinfo.banner && $req.wishinfo.banner neq ""}class="active_tab"{/if} style="width:140px;">Create Your Own</li>
    </ul>
    <div style="clear:both"></div>
 </div>
  {foreach from=$req.banner.SYSTEM item=t_banner key=k}
  	 <div id="{$k}_tab" style="display:{if $t_banner[$req.wishinfo.banner] or ($k eq 'xmas' and $req.wishinfo.banner eq "")}{else}none;{/if}">
     <ul class="templatelist">
         {foreach from=$t_banner item=banner}
            <li><a href="{$banner.bigimage}" rel=lightbox  ><img src="{$banner.thumbimg}" width="124" height="133"/></a><br/><input {if $req.wishinfo.banner eq $banner.id}checked="checked"{/if} id='{$k}_{$banner.id}' type="radio" name="banner" value="{$banner.id}"/></li>
         {/foreach}
     </ul>
     <div style="clear:both"></div>
     </div>
 {/foreach}
 <div style="margin:12px 0 0 15px;{if $req.banner.USER.id eq $req.wishinfo.banner && $req.wishinfo.banner neq ""}{else}display:none;{/if}" id="custom_tab">
 	<table cellpadding="0" cellspacing="0">
 		<tr><td>
 			<div style="position:relative; width:265px; float:left;">
            <input type="radio" name="banner" value="user_template" id="custom_1" {if $req.wishinfo.banner eq $req.banner.USER.id}checked{/if}/> Upload My Own Design
			
            <!--<iframe id="ifupload" marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="margin: 0px;padding:5px 0; z-index:100;border: 0px ; float:left;" height="105" width="265px" src="/wishupload.php"></iframe>-->
            <div style="margin:10px 0;">
            <input type="file" id="custom_photo" name="Filedata" style="display:none" /><div style="clear:both"></div></div>
            <div>For perfect fit, the image size is 750 x 245 pixels</div>
            
			<div style="position:absolute; clear:both;z-index:1001; top:0px; left:170px;">
			 <span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.<br/>Please keep the image to a maximum of 8MB in size. </span></span></a></span>
			</div>
			</div>
            <div style="width:450px; height:147px; float:left; margin-left:5px;">
            <img id="disimg" src="{if $req.banner.USER.banner&&$req.banner.USER.banner neq "/skin/red/images/wishlist_banner/user/template.jpg"}{$req.banner.USER.banner}{else}/skin/red/images/wishlist_banner/user/template_s.jpg{/if}" width="450" height="147"/>
            <input type="hidden" value="{if $req.banner.USER.banner}{$req.banner.USER.banner}{else}/skin/red/images/wishlist_banner/user/template.jpg{/if}" name="usertpl_img" id="usertpl_img"/>
            </div>
            <div style="clear:both"></div>
            
            <div>
            	<ul class="templatelist" style=" margin-left:0;">
                	{foreach from=$req.userTemp item=tpl_user}
                	<li style="border:1px solid #ccc;"><a href="{$tpl_user.template}" rel=lightbox><img src="{$tpl_user.template_thumb}" width="124"/></a><br/>
                    	<input type="radio" id="cus_bid_{$tpl_user.id}" name="user_banner" value="{$tpl_user.tpl_bg}" {if $req.banner.USER.template eq $tpl_user.tpl_bg}checked{/if}/>
                        {if $req.banner.USER.template eq $tpl_user.tpl_bg}<input type='hidden' id='custom_banner_s' value='cus_bid_{$tpl_user.id}'/>{/if}
                    </li>
                    {/foreach}
                </ul>
            </div>
 		</td></tr>
 	</table>
 </div>
</div>
<input type="hidden" value="" name="cp"/>
<div style="clear:both"></div>
<table style=" width:730px; margin:20px 0 0 0;" cellpadding="0" cellspacing="0">
  		 <tr>
         	<td height="2" colspan="2" bgcolor="#293694"></td>
          </tr>
          <tr>
          	<td align="right" style="padding-top:10px;">
		<input src="/skin/red/images/bu-continue.gif" class="submit form-save" type="image" onclick="document.mainForm.cp.value='next';">
		{if $req.bulid && $req.enable}<input src="/skin/red/images/bu-saveexit.gif" class="submit form-save" type="image" onclick="document.mainForm.cp.value='save';">{/if}
        	</td>
           </tr>
</table>
</form>
<div style="padding-bottom:30px"></div>
{if $req.wishinfo.banner eq ""}
	<script>
    	$('#xmas_1').attr('checked',true);
    </script>
{/if}