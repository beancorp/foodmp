	<link type="text/css" href="{$smarty.const.STATIC_URL}css/skin/red/swfupload_product.css" rel="stylesheet" media="screen" />
	
	<script type="text/javascript" src="{$smarty.const.STATIC_URL}js/skin/red/swfobject.js"></script>
	<script type="text/javascript" src="{$smarty.const.STATIC_URL}js/skin/red/swfupload.js"></script>
	<script language="javascript" src="{$smarty.const.STATIC_URL}js/skin/red/jquery-asyncUpload-0.1.js"></script>
	<script src="{$smarty.const.STATIC_URL}js/skin/red/uploadImages.js" language="javascript"></script>	
	<script>
		{literal}
		function uploadresponse(response) {
			var aryResult = response.split('|');
			setTimeout(function() {
				if (aryResult.length > 3) {
					var objRes = aryResult[0];
					var imgobj = $("#"+objRes+"_dis");
					$(imgobj).attr('src', '{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}' + aryResult[1]);
					$(imgobj).css('width',aryResult[2]);
					$(imgobj).css('height',aryResult[3]);
					$("#"+objRes+"_svalue").val("/"+aryResult[4]);
					$("#"+objRes+"_bvalue").val("/"+$.trim(aryResult[5]));
				} else if(aryResult.length==3) {
					$("#"+aryResult[0]).html(aryResult[2]);
					$("#swf_"+aryResult[0]).val(aryResult[1]);		
				}
			}, 2000);
		}
		function uploadprocess(bl) {}
		$(function() {
			try {
				$("#swf_upload_1").makeAsyncUploader({{/literal}
					upload_url: '{$smarty.const.SOC_HTTP_HOST}uploadproduct_img.php?objImage=mainImage&tpltype=0&attrib=0&index=0',
					flash_url: '{$smarty.const.SOC_HTTP_HOST}skin/red/js/swfupload.swf',{literal}
					button_image_url: '/skin/red/images/buttons/upload_image.png',
					disableDuringUpload: 'INPUT[type="submit"]',
					file_types:'*.jpg;*.gif;*.png',
					file_size_limit:'10MB',
					file_types_description:'All images',
					button_text:"",
					button_window_mode:"transparent",
					height:"25",
					debug:false
				});
			} catch(err) {
				alert(err.message);
			}

		});
		{/literal}
	</script>	
	
	
	<style>
		{literal}
		
		#upload_file td {
			padding: 5px;
		}
		
		#upload_file {
			margin-bottom: 25px;
		}
		
		{/literal}
	</style>
	
	<form action="?act=imagelibrary" method="post">
		<table id="upload_file">
			<tr>
				<td>Name</td>
				<td><input type="text" name="product_name" /></td>
			</tr>
			<tr>
				<td>Image</td>
				<td>
					<span class="lbl"> <a id="swf_upload_1" style="float:left;" href="javascript:uploadImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" /></a>&nbsp;&nbsp;| <a href="javascript:deleteImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a> </span>
					<br /><br /><br />
					<img src="/images/243x212.jpg" name="mainImage_dis" border="1" id="mainImage_dis" width="243" height="212" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="submit" value="Save" />
					<input name="mainImage_svalue" id="mainImage_svalue" type="hidden" value=""/>
					<input name="mainImage_bvalue" id="mainImage_bvalue" type="hidden" value=""/>
				</td>
			</tr>
		</table>
	</form>
	<table>
		{foreach from=$images item=image}
			<tr>
				<td><img src="{$image.product_image_small}" width="60px" /></td>
				<td>{$image.product_name}</td>
				<td><a href="?act=imagelibrary&delete={$image.product_id}">Delete</a></td>
			</tr>
		{/foreach}
	</table>
	
	