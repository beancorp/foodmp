<link type="text/css" href="/skin/red/css/gallery.css" rel="stylesheet" media="screen" />
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/skin/red/js/gallery.js"></script>
<script language="javascript">
	var xmas_s = {if $req.banner.SYSTEM.xmas[$req.galleryinfo.template]}{$req.galleryinfo.template}{else}0{/if};
	var birthday_s = {if $req.banner.SYSTEM.birthday[$req.galleryinfo.template]}{$req.galleryinfo.template}{else}0{/if};
	var college_s = {if $req.banner.SYSTEM.college[$req.galleryinfo.template]}{$req.galleryinfo.template}{else}0{/if};
	var general_s = {if $req.banner.SYSTEM.general[$req.galleryinfo.template]}{$req.galleryinfo.template}{else}0{/if};
	var wedding_s = {if $req.banner.SYSTEM.wedding[$req.galleryinfo.template]}{$req.galleryinfo.template}{else}0{/if};
	var StoreID = "{$smarty.session.ShopID}";
	var soc_http_host="{$soc_http_host}";
</script>
<script type="text/javascript" src="/js/lightbox_plus.js"></script>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
{literal}
 <script>
    $(function() {
        $("#photo").makeAsyncUploader({
            upload_url: soc_http_host+"uploadgallery.php?type=category",
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
		$("#musicupload").makeAsyncUploader({
			upload_url: soc_http_host+"uploadmusic2.php",
            flash_url: '/skin/red/js/swfupload.swf',
            button_image_url: '/skin/red/images/blankButton.png',
            disableDuringUpload: 'INPUT[type="submit"]',
            file_types:'*.mp3',
            file_size_limit:'10MB',
			file_types_description:'Music',
			button_window_mode:"transparent",
			button_text:"",
			height:"29",
            debug:false
		});
    }); 
	function uploadresponse(response){
		var aryResult = response.split('|');
		if(aryResult[2]&&$.trim(aryResult[2])=='music'){
			$("#filelist").html("<a target=\"_blank\" href=\"/"+aryResult[0]+"\" style=\"text-decoration:none\"><span style=\" float:left;margin-left:10px; line-height:24px; height:24px;\">"+aryResult[1]+"</span></a> <a href=\"javascript:deletefile('"+aryResult[0]+"','"+StoreID+"');\"><img src=\"/skin/red/images/icon-deletes.gif\"/></a>");
			$("#file_music").val(aryResult[0]);
			$("#music_name").val(aryResult[1]);
		}else if(aryResult[2]&&$.trim(aryResult[2])=='template'){
			$("#disimg").attr('src',"/"+aryResult[0]);
			$("#usertpl_img").val("/"+aryResult[0]);
		}else{
			$("#disimg2").attr('src',aryResult[1]);
			$("#gallery_images").val(aryResult[0]);
			$("#gallery_thumbs").val(aryResult[1]);
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


<div>
   <h3 style="font-size:16px; color:#666; font-weight:bold;width:100;">My Image Galleries</h3>
    
    <div id="gallerylistDiv">
    	<ul class="gallarylist">
        {if $req.gallerylist}
        	{foreach from=$req.gallerylist item=gl}
            <li><div class="listimg2"><span><a href="/soc.php?act=galleryinfo&cid={$gl.id}"><img src="{if $gl.gallery_category_thumbs}{$gl.gallery_category_thumbs}{else}/images/100x100.jpg{/if}"/></a></span></div>
           	  <div class="des_catname" title="{$gl.gallery_category}">{$gl.gallery_category|truncate:11:'...' }</div>
                <div class="des_opts" style="text-align:left;">
                <img height="20" align="absmiddle" src="/skin/red/images/adminhome/icon-manage.gif"/><a href="/soc.php?act=galleryinfo&cid={$gl.id}">Add/Edit Images</a><br/>
                <img height="20" align="absmiddle" src="/skin/red/images/adminhome/icon-email-forward.gif"/><a href="/soc.php?act=galleryinfo&cid={$gl.id}&cp=send">Send to Friends</a><br/>
                <img width="20" height="20"  align="absmiddle" src="/skin/red/images/adminhome/icon-prev.gif" /><a href="/{$req.urlstring}/gallery/{$gl.gallery_url}" target="_blank">Preview</a><br/>
                <img height="20" align="absmiddle" src="/skin/red/images/adminhome/icon-manage.gif"/><a href="/soc.php?act=gallery&cp=edit&id={$gl.id}">Gallery Settings</a><br/>
                 <img height="20" align="absmiddle" src="/skin/red/images/adminhome/icon-delete.gif" title="Delete"/><a href="/soc.php?act=gallery&cp=del&id={$gl.id}" onClick="javascript:return delconfirm('gallery');">Delete Gallery</a><br/>
                </div>
            </li>
            {/foreach}
        {/if}
        </ul>
        <div style="clear:both"></div>
        <div style="width:100%; text-align:center"></div>
    </div>
     <div style="width:100%; height:1px; background:#ccc; margin:10px 0;font-size:1px;"></div>
	<div><h3 style="font-size:16px; color:#666; font-weight:bold;width:100;">
    {if $req.galleryinfo.id}Edit Gallery{else}<a href="javascript:$('#galleryForm').show();void(0);" style="font-size:16px; color:#666; font-weight:bold;width:100;">Create New Gallery</a>{/if}</h3>
    <form action="" method="post" onSubmit="return checkGalleryForm();" id="galleryForm"  {if $req.galleryinfo.id}{else}style="display:none"{/if}>
    	<input type="hidden" name="id"  value="{$req.galleryinfo.id}"/>
    	<div style="color:red;text-align:center;">{$req.msg}</div>
    	<table cellpadding="4" cellspacing="4" >
        	<tr><td width="120">Gallery Name*:</td>
            	<td width="285"><input type="text" class="inputB" id="gallery_category" name="gallery_category" value="{$req.galleryinfo.gallery_category}" maxlength="60" onblur="checkGalleryName(StoreID,this,'{$req.galleryinfo.id|number_format:0}');"/></td>
                <td id="msg"></td>
            </tr>
            <tr style="display:none"><td valign="top">Gallery Image:</td>
            	<td valign="top" width="285">
                <input type="file" id="photo" name="Filedata" style="display:none" />
                <!--<iframe id="ifupload" marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="margin: 0px; z-index:100;border: 0px ; float:left;" height="70" width="280px" src="/uploadgallery.php?type=category"></iframe>--></td>
                <td rowspan=2 valign="top" align="left">
                <div style="border:1px solid #ccc; padding:3px;width:100px;"><img src="{if $req.galleryinfo.gallery_category_thumbs}{$req.galleryinfo.gallery_category_thumbs}{else}/images/100x100.jpg{/if}"  id="disimg2"/></div>
                <input type="hidden" id="gallery_thumbs" name="gallery_category_thumbs" value="{$req.galleryinfo.gallery_category_thumbs}"/>
                <input type="hidden" id="gallery_images" name="gallery_category_images" value="{$req.galleryinfo.gallery_category_images}"/>
                </td>
            </tr>
            
        	<tr><td valign="top">Gallery Description:</td>
            	<td><textarea name="gallery_category_desc"  class="inputB" style="height:60px;">{$req.galleryinfo.gallery_category_desc}</textarea></td>
            </tr>
            
            
            <tr>	
	<td width="17%" valign="top">Gallery Music:(Optional)</td>
    
    <td colspan="2">
    <div style="position:relative;">
<!--<iframe marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="height:40px;*height:30px;" width="290" src="/uploadmusic.php?StoreID={$req.galleryinfo.StoreID}" border=1></iframe>-->
    <!--<input type="file" name="music" class="inputB" style="*width:287px;"/>-->
    <input type="hidden" name="delmusic" value="no" id="delmusic"/>
    <div style="position:absolute; top:0; left:292px;" onmouseover="javascript:$('#musichelp').css('display','');" onmouseout="javascript:$('#musichelp').css('display','none');"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top"  style="cursor:pointer; margin:0; float:left;"/><span style="line-height:20px;">&nbsp;MP3 file (10MB limit)</span>
    <div id="musichelp" style="position:absolute; margin:0;width:200px; left:21px; top:0; display:none; border:1px solid #ccc; color:#777777; padding:2px 3px 2px 5px; background-color:#FFE870;">Please keep the music to a maximum of 10MB in size.
Supported file type is mp3 only.</div>
	</div>
    </div>
    <input type="file" name="music" id="musicupload" class="inputB" style="display:none;"/><div style="clear:both;"></div>
    <span id="filelist">{if $req.galleryinfo.music}<a target="_blank" href="/{$req.galleryinfo.music}" style="text-decoration:none"><span style=" float:left;margin-left:10px; line-height:24px; height:24px;">{$req.galleryinfo.music_name}</span></a> <a href="javascript:deletefile('{$req.galleryinfo.music}','{$req.galleryinfo.StoreID}');"><img src="/skin/red/images/icon-deletes.gif"/></a>{/if}</span>
    <input type="hidden" name="music" id="file_music" value="{$req.galleryinfo.music}" />
    <input type="hidden" name="music_name" id="music_name" value="{$req.galleryinfo.music_name}" /><div style="clear:both;"></div></td>
</tr>
            
            
            
            
            <tr><td>Gallery Password:*</td>
            	<td><input type="password" name="gallery_category_password" id="galpwd"  class="inputB" value="{$req.galleryinfo.gallery_category_password}"/></td>
                <td></td>
            </tr>
            <tr><td>RE-Enter Password:*</td>
            	<td><input type="password" name="gallery_category_password" id="galpwd2"  class="inputB" value="{$req.galleryinfo.gallery_category_password}"/></td>
                <td></td>
            </tr>
</table>        	
<div style="margin-bottom:20px">
<div style="margin: 0pt 20px 30px 0pt; background: rgb(204, 204, 204) none repeat scroll 0% 0%; clear: both; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; height: 1px; line-height: 1px;"><img alt="" src="/skin/red/images/spacer-grey.gif"/></div>
 <h3 style="font-size:16px; color:#666; font-weight:bold;">Choose Gallery Design</h3>
 <div style="background-color:#EEE; height:40px; margin:0 15px; border-bottom:1px solid #EEE;">
 	<ul class="tabtmp">
    	<li onclick="javascript:changetab('xmas_tab',this);" {if $req.banner.SYSTEM.xmas[$req.galleryinfo.template] or $req.galleryinfo.template eq ""}class="active_tab"{/if}>Christmas</li>
        <li onclick="javascript:changetab('birthday_tab',this);" {if $req.banner.SYSTEM.birthday[$req.galleryinfo.template]}class="active_tab"{/if}>Birthday</li>
        <li onclick="javascript:changetab('college_tab',this);" {if $req.banner.SYSTEM.college[$req.galleryinfo.template]}class="active_tab"{/if}>College</li>
        <li onclick="javascript:changetab('general_tab',this);" {if $req.banner.SYSTEM.general[$req.galleryinfo.template]}class="active_tab"{/if} style="width:120px;">All Occasions</li>
        <li onclick="javascript:changetab('wedding_tab',this);" {if $req.banner.SYSTEM.wedding[$req.galleryinfo.template]}class="active_tab"{/if} style="width:120px;">Weddings</li>
        <li onclick="javascript:changetab('custom_tab',this);" {if $req.banner.USER.id eq $req.galleryinfo.template && $req.galleryinfo.template neq ""}class="active_tab"{/if} style="width:140px;">Create Your Own</li>
    </ul>
    <div style="clear:both"></div>
 </div>
  {foreach from=$req.banner.SYSTEM item=t_banner key=k}
  	 <div id="{$k}_tab" style="display:{if $t_banner[$req.galleryinfo.template] or ($k eq 'xmas' and $req.galleryinfo.template eq "")}{else}none;{/if}">
     <ul class="templatelist">
         {foreach from=$t_banner item=banner}
            <li><a href="{$banner.bigimage}" rel=lightbox  ><img src="{$banner.thumbimg}" width="124" height="133"/></a><br/><input {if $req.galleryinfo.template eq $banner.id}checked="checked"{/if} id='{$k}_{$banner.id}' type="radio" name="banner" value="{$banner.id}"/></li>
         {/foreach}
     </ul>
     <div style="clear:both;font-size:0;height:0;padding:0;"></div>
     </div>
 {/foreach}
 <div style="margin:12px 0 0 15px;{if $req.banner.USER.id eq $req.galleryinfo.template && $req.galleryinfo.template neq ""}{else}display:none;{/if}" id="custom_tab">
 	<table cellpadding="0" cellspacing="0">
 		<tr><td>
 			<div style="position:relative; width:265px; float:left;">
            <input type="radio" name="banner" value="user_template" id="custom_1" {if $req.galleryinfo.template eq $req.banner.USER.id}checked{/if}/> Upload My Own Design
           
			<!--<iframe id="ifupload" marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="margin: 0px;padding:5px 0; z-index:100;border: 0px ; float:left;" height="105" width="265px" src="/wishupload.php"></iframe>-->
            <div style="margin:10px 0;" ><input type="file" id="custom_photo" name="Filedata" style="display:none" /><div style="clear:both"></div></div>
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
  <div style="width:720px;text-align:right;magrin-right:20px;">
  
  <input type="submit"  class="savebut" value="" />
  
  </div>
        <input type="hidden" value="savegallery" name="saveopt"/>
	</form>
    </div>
    
</div>
{if $req.galleryinfo.template eq ""}
	<script>
    	$('#xmas_1').attr('checked',true);
    </script>
{/if}