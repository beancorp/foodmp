<link type="text/css" href="/skin/red/css/gallery.css" rel="stylesheet" media="screen" />
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/skin/red/js/gallery.js"></script>
<script type="text/javascript" src="/js/lightbox_plus.js"></script>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript">
var soc_http_host="{$soc_http_host}";
</script>
{literal}
 <script>
    $(function() {
        $("#photo").makeAsyncUploader({
            upload_url: soc_http_host+"uploadgallery.php",
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
        $("#bulkPhoto").makeAsyncUploader({
            upload_url: soc_http_host+"upload_bulkupload.php?type=imgmsg",
            flash_url: '/skin/red/js/swfupload.swf',
            button_image_url: '/skin/red/images/blankButton.png',
            disableDuringUpload: 'INPUT[type="submit"]',
            file_types:'*.zip',
            file_size_limit:'70MB',
            file_types_description:'Only ZIP',
            button_text:"",
            button_window_mode:"transparent",
            height:"29",
            debug:false 
        });
    });
    function switchTab(tabId){
        if($('#isUploaded').val() == '1'){
            if(!confirm('Are you sure you want to switch the mode without saving the file?')){
                if(tabId == 'bluktab'){
                    $('#normaltabSwitcher').attr('checked', true);
                    $('#bluktabSwitcher').removeAttr('checked');
                }else{
                    $('#bluktabSwitcher').attr('checked', true);
                    $('#normaltabSwitcher').removeAttr('checked');
                }
                return;
            }
        }

        $('#isUploaded').val('0');
        $('#bulkmsg').html('').hide();
        if(tabId == 'bluktab'){
            $('#bluktab').show();
            $('#normaltab').hide();
            $("#gallery_images").val('');
            $("#gallery_thumbs").val('');
            $('#disimg2').attr('src', '/images/100x100.jpg').parent().hide();
        }else{
            $('#bluktab').hide();
            $('#normaltab').show();
            $("#bulk_images").val('');
            $('#disimg2').parent().show();
        }
        $('#isUploaded').val(0);
    }

	function uploadresponse(response){
        $('#isUploaded').val('1');
		var aryResult = response.split('|');
        if(aryResult[0] == 'imgmsg'){
            $("#bulkmsg").html('Images file upload successfully.').show();
            $("#bulkimg").val(aryResult[1]);
        }else{
            $("#disimg2").attr('src',aryResult[1]);
            $("#gallery_images").val("/"+aryResult[0]);
            $("#gallery_thumbs").val("/"+aryResult[1]);
        }
	}
	function uploadprocess(bl){
		if(!bl){
            $('#blukInfo').css('margin-left', '5px');
			$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save_grey.gif)');
		}else{
            $('#blukInfo').css('margin-left', '-10px');
			$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save.gif)');
		}
	}
	function updateOrder(meth,id,order){
		$("#loading").show();
		if (meth == 'left'){
			order = eval(order) - 1;
		}else{
			order = eval(order) + 1;
		}
		$.getJSON('/include/jquery_svr.php',{svr:"galleryMove",category:{/literal}{$req.galleryinfo.id}{literal},id:id,order:order}, function(json){
			if (json.result == '1'){
				$("#gallerylistDiv").html(json.list)
			}else{
				//alert(json.msg);
			}
			$("#loading").hide();
		});
	}
  </script>  
{/literal}
<div>
	<div><h3 style="font-size:16px; color:#666; font-weight:bold;width:100;">{$req.galleryinfo.gallery_category}</h3>
    	<table>
        	<tr>
            	<td valign="top"><div style="border:1px solid #ccc; padding:3px; float:left;"><img src="{if $req.galleryinfo.gallery_category_thumbs}{$req.galleryinfo.gallery_category_thumbs}{else}/images/100x100.jpg{/if}"/></div></td>
            	<td valign="top" style="padding-left:10px;"><div style="width:600px;">{$req.galleryinfo.gallery_category_desc|nl2br}<br/><br/><a href="/soc.php?act=galleryinfo&cid={$req.galleryinfo.id}&cp=send"><img height="20" align="absmiddle" src="/skin/red/images/adminhome/icon-email-forward.gif" title="Send Gallery" style="float:left; padding-right:5px;"/> <span style="float:left;padding-top:3px;width:200px; cursor:pointer;">Send this gallery to your friends</span></a><br/><div style="clear:both;"></div>
                <a href="/{$req.urlstring}/gallery/{$req.galleryinfo.gallery_url}" target="_blank"><img src="/skin/red/images/adminhome/icon-prev.gif"  style="float:left; padding-right:5px;" /><span style="float:left;padding-top:3px; cursor:pointer;">View</span></a><div style="clear:both;"></div></div>
                </td>
            </tr>
        </table>
    </div>
   <div style="width:100%; height:1px; background:#ccc; margin:10px 0;"></div>
<!---end show the gallery info--->

	<div><h3 style="font-size:16px; color:#666; font-weight:bold;width:100;">Upload images to this gallery</h3>
    <form action="" method="post" onSubmit="javascript:return checkImageForm();">
    	<input type="hidden" name="id" value="{$req.galleryDetails.id}"/>
    	<input type="hidden" name="gallery_category" value="{$req.galleryinfo.id}"/>
    	<table cellpadding="4" cellspacing="4">
            {if !$req.isEdit}
            <tr>
                <td align="right">Please Select:&nbsp;</td>
                <td>
                    <input type="radio" id="normaltabSwitcher" name="uploadtype" value="normal" checked="checked" onclick="switchTab('normaltab');"/>Standard Mode
                    <input type="radio"id="bluktabSwitcher"  name="uploadtype" value="bluk" onclick="switchTab('bluktab')"/>Bulk Upload Mode
                    <input type="hidden" id="isUploaded" value="0"/>
                </td>
            </tr>
            {/if}
            <tr>
            	<td valign="top">Image*:</td>
            	<td valign="top" height="29">
                    <div id="normaltab">
                        <input type="file" id="photo" name="Filedata" style="display:none" />
                    </div>
                    <div id="bluktab" style="display:none">
                        <input type="file" id="bulkPhoto" name="Imagedata" style="display:none" />
                        <span id="blukInfo" style="margin-left:-10px;">
                        <a href="#" class="help">
                            <img height="20" border="0" align="top" width="21" style="margin: 4px 5px;" src="/skin/red/images/icon-question.gif">
                            <span>
                                <span style="color: rgb(119, 119, 119); z-index: 100;">
                                Upload one ZIP file containing all your images.(Maximum file size accepted is 70MBs.)
                                </span>
                            </span>
                        </a>
                        </span>
                    </div>
                <div id="bulkmsg" style="clear:both;display:none;color:red;"></div>
                <input type="hidden" id="bulkimg" name="bulkimg"/>
                <div style="clear:both"></div>
                For perfect fit, the image size is 480 x 320 pixels.
                </td>
                <td rowspan=3 valign="top">
                    <div style="border:1px solid #ccc; padding:3px;"><img src="{if $req.galleryDetails.gallery_thumbs}{$req.galleryDetails.gallery_thumbs}{else}/images/100x100.jpg{/if}"  id="disimg2"/></div>
                    <input type="hidden" id="gallery_thumbs" name="gallery_thumbs" value="{$req.galleryDetails.gallery_thumbs}"/>
                    <input type="hidden" id="gallery_images" name="gallery_images" value="{$req.galleryDetails.gallery_images}"/>
                    <input type="hidden" id="bluk_images" name="bluk_images" value=""/>
                </td>
            </tr>
            
        	<tr><td valign="top">Description:<br/>
            <span style="font-size:9px;">(180 characters)</span>
            </td>
            	<td><textarea name="gallery_desc"  class="inputB" style="height:60px;" onkeyup="textcontrol(this)" onkeypress="textcontrol(this)" onblur="textcontrol(this)" onfocus="textcontrol(this)"  id="gallery_desc">{$req.galleryDetails.gallery_desc}</textarea></td>
            </tr>
        	<tr style="display:none;">
                <td valign="top">Order:</td>
            	<td><input name="gallery_order"  class="inputB" type="text" style="width:20px;" id="gallery_order" value="{$req.lastOrder}" /></td>
            </tr>
            <tr><td></td>
            	<td align="right"><span style="color:#F00;margin-right:10px;">Click save to add image to your gallery.</span><input type="submit"  class="savebut" value="" /></td>
                <td></td>
            </tr>
        </table>
        <input type="hidden" value="savegallery" name="saveopt"/>
	</form>
    </div>
    <div style="width:100%; height:1px; background:#ccc; margin:10px 0 0 0;"></div>
    <div id="loading" style="float:left;z-index:100;left:300px;position:relative;display:none;"><img border="0" src="/skin/red/images/loading.gif" /></div><div style="height:32px;"></div>
    <div class="clear"></div>
    <div id="gallerylistDiv">
    	<ul class="gallarylist">
         {if $req.gallerylist}
         	{foreach from=$req.gallerylist item=gl}
        	<li style="width:106px;"><div class="listimg" align="center"><span><a href="{if $gl.gallery_images}{$gl.gallery_images}{else}#{/if}" rel=lightbox><img src="{if $gl.gallery_thumbs}{$gl.gallery_thumbs}{else}/images/100x100.jpg{/if}"/></a></span></div>
            	<div class="des_catname" title="{$gl.gallery_desc}">{$gl.gallery_desc|truncate:11:'...' }</div>
                <div class="des_opts">
                    <ul>
                        <li class="navigate"><img style="cursor:pointer;" onclick="updateOrder('left',{$gl.id},{$gl.gallery_order})" src="/skin/red/images/previous.jpg" alt="Move Left" title="Move Left"/></li>
                        <li><a href="/soc.php?act=galleryinfo&cid={$req.galleryinfo.id}&cp=edit&pid={$gl.id}"><img height="20" align="absmiddle" src="/skin/red/images/adminhome/icon-manage.gif" title="Edit"/></a></li>
                        <li><a href="/soc.php?act=galleryinfo&cid={$req.galleryinfo.id}&cp=del&pid={$gl.id}" onClick="javascript:return delconfirm('image');"><img height="20" align="absmiddle" src="/skin/red/images/adminhome/icon-delete.gif" title="Delete"/></a></li>
                        <li class="navigate"><a href="javascript:void(0)"><img style="cursor:pointer;" onclick="updateOrder('right',{$gl.id},{$gl.gallery_order})" src="/skin/red/images/next.jpg" alt="Move Right" title="Move Right" /></a></li>
                    </ul>
                </div>
            </li>
            {/foreach}
        {/if}
        </ul>
    </div>
</div>