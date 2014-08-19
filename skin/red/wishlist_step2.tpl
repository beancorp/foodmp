<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script>
var StoreID = "{$smarty.session.ShopID}";
var soc_http_host="{$soc_http_host}";
</script>
{literal}
	<style type="text/css">
		.templatelist{
			list-style:none;
		}
		.templatelist li{
			list-style:none;
			float:left;
			text-align:center;
		}
	</style>
    <script language="javascript">
		function protectedfuc(obj){
			if(obj.checked){
				$('input[@name=password]').attr('disabled',false);
				$('input[@name=password]').css('backgroundColor','#FFFFFF');
			}else{
				$('input[@name=password]').attr('disabled',true);
				$('input[@name=password]').css('backgroundColor','#ECE9D8');
			}
		}
		function checkVaild(){
			var errors = "";
			if($('#paypal').val()==""){
				errors += "-	The Paypal Acc# is required.\n";
			}
			if($('#isprotected').attr('checked')){
				if($('#password_1').val()==""){
					errors += "-	The Password is required.\n";
				}else{
					if($('#password_1').val()!=$('#password_2').val()){
						errors += "-	The Password you have entered did not match.\n";
					}
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
		function deletefile(file,StoreID){
			if(confirm('Are you sure to delete this file?')){
				$('#filelist').html('');
				$('#delmusic').val('yes');
				$("#file_music, #music_name").val('');
			}
			return void(0);
		}
		
	$(function() {
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
		}
	}
	function uploadprocess(bl){
		if(!bl){
			$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/gray-submit.gif)');
		}else{
			$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/or-submit.gif)');
		}
	}
</script>
{/literal}
<form name="mainForm" action="" method="post" enctype="multipart/form-data" onsubmit="return checkVaild();">
<div align="center" style="color:#F00">{$req.msg}</div>
<div style="margin: 0pt 20px 20px 0pt; background: rgb(204, 204, 204) none repeat scroll 0% 0%; clear: both; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; height: 1px; line-height: 1px;"><img alt="" src="/skin/red/images/spacer-grey.gif"/></div>
<div style="margin-bottom:20px;">
<h3 style="font-size:16px; color:#666; font-weight:bold;margin-top:2px;float:left;">Wishlist Details</h3>
 <div style="float:left;margin-left: 13px;">
     <img height="22" align="absmiddle" width="22" src="/skin/red/images/adminhome/icon-view.gif"/>
     <a style="width: auto; margin: 3px;" target="_blank" href="/soc.php?cp=wishlistSample">View Some Examples&nbsp;</a>
 </div>
 <div style="clear:both"></div>
<table width="730" style="margin-left:20px;">
<tr>	
	<td width="17%">Wishlist Music:(Optional)</td><td>
    <div style="position:relative;">
    <!--<iframe marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="height:40px;*height:30px;" width="290" src="/uploadmusic.php?StoreID={$req.wishinfo.StoreID}" border=1></iframe>-->
    
    
    
    <!--<input type="file" name="music" class="inputB" style="*width:287px;"/>-->
    <input type="hidden" name="delmusic" value="no" id="delmusic"/>
    <div style="position:absolute; top:0; left:292px;" onmouseover="javascript:$('#musichelp').css('display','');" onmouseout="javascript:$('#musichelp').css('display','none');"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top"  style="cursor:pointer; margin:0; float:left;"/><span style="line-height:20px;">&nbsp;MP3 file (10MB limit)</span>
    <div id="musichelp" style="position:absolute; margin:0;width:200px; left:21px; top:0; display:none; border:1px solid #ccc; color:#777777; padding:2px 3px 2px 5px; background-color:#FFE870;">Please keep the music to a maximum of 10MB in size.
Supported file type is mp3 only.</div>
	</div>
    </div>
    <input type="file" name="music" id="musicupload" class="inputB" style="display:none;"/><div style="clear:both;"></div>
    <span id="filelist">{if $req.wishinfo.music}<a target="_blank" href="/{$req.wishinfo.music}" style="text-decoration:none"><span style=" float:left;margin-left:10px; line-height:24px; height:24px;">{$req.wishinfo.music_name}</span></a> <a href="javascript:deletefile('{$req.wishinfo.music}','{$req.wishinfo.StoreID}');"><img src="/skin/red/images/icon-deletes.gif"/></a>{/if}</span>
    <input type="hidden" name="music" id="file_music" value="{$req.wishinfo.music}" />
    <input type="hidden" name="music_name" id="music_name" value="{$req.wishinfo.music_name}" /><div style="clear:both;"></div></td><td>
    </td>
</tr>
<tr><td valign="top">Youtube Video:(Optional)</td>
	<td valign="bottom">
    <table width="100%" cellpadding="0" cellspacing="0">
    	<tr><td width="1%"><textarea name="youtubevideo" id="youtubevideo" class="inputB" style="height:50px">{$req.wishinfo.youtubevideo}</textarea></td>
        	<td ><a  href="/soc.php?cp=wishlistyoutubeins" target="_blank"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" style="margin:42px 0 0 5px;" /></a></td>
            <td width="99%" valign="bottom" align="left">&nbsp;<input onclick="javascript:$('#youtubevideo').val('');" type="button" value="Clear"/></td>
        </tr>
    </table>
   </td>
    <td valign="bottom"></td>
</tr>
<tr>
	<td colspan="2"><div style="margin: 10px 10px 10px 0pt; background: rgb(204, 204, 204) none repeat scroll 0% 0%; clear: both; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; height: 1px; line-height: 1px; width:500px;"><img alt="" src="/skin/red/images/spacer-grey.gif"/></div></td>
    <td></td>
</tr>
<tr>
	<td>Paypal Account:*</td>
    <td><input type="text" id="paypal" name="paypal" class="inputB" value="{$req.wishinfo.paypal}"/></td>
    <td></td>
</tr>

<tr>
	<td colspan="2"><div style="margin: 10px 10px 10px 0pt; background: rgb(204, 204, 204) none repeat scroll 0% 0%; clear: both; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; height: 1px; line-height: 1px; width:500px;"><img alt="" src="/skin/red/images/spacer-grey.gif"/></div></td>
    <td></td>
</tr>

<tr><td>Wishlist Message:</td>
	<td><textarea name="description" class="inputB" style="height:120px;">{$req.wishinfo.description}</textarea></td>
    <td></td>
</tr> 
<tr><td>Wishlist Visitor:</td>
	<td><a href="/soc.php?cp=wishlist_get_step_stat">{$req.wishNumber|number_format:0}</a></td>
    <td></td>
</tr> 
</table>
</div>
<div style="margin: 0pt 20px 20px 0pt; background: rgb(204, 204, 204) none repeat scroll 0% 0%; clear: both; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; height: 1px; line-height: 1px;"><img alt="" src="/skin/red/images/spacer-grey.gif"/></div>
<div style="margin-bottom:20px;">
<h3 style="font-size:16px; color:#666; font-weight:bold;">Wishlist Password </h3>
<table width="730" style="margin-left:20px;">
<tr style="display:none;">
	<td width="17%">Enable Wishlist Password:</td><td><input type="checkbox" checked="checked" value="1" name="isprotected" onclick="protectedfuc(this);" id="isprotected"/></td>
</tr>
<tr>
    <td  width="17%">Password:*</td><td><input id="password_1" type="password" name="password" class="inputB" value="{$req.wishinfo.password}"/></td><td></td>
</tr>
<tr>
    <td>RE-Enter Password:*</td><td align="left" style="width:290px;"><input id="password_2" type="password" name="password" class="inputB" value="{$req.wishinfo.password}"/></td><td></td>
</tr>
</table>
</div>
<input type="hidden" value="" name="cp"/>
<table style=" width:730px; margin:20px 0 0 0;" cellpadding="0" cellspacing="0">
  		 <tr>
         	<td height="2" colspan="2" bgcolor="#293694"></td>
          </tr>
          <tr>
          	<td align="right" style="padding-top:10px;">
		<input src="/skin/red/images/bu-continue.gif" class="submit form-save" type="image"  onclick="document.mainForm.cp.value='next';">
		<input src="/skin/red/images/bu-saveexit.gif" class="submit form-save" type="image"  onclick="document.mainForm.cp.value='save';">
        	</td>
           </tr>
</table>
</form>