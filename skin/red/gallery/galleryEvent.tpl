<link type="text/css" href="/skin/red/css/gallery.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/skin/red/js/gallery.js"></script>
<script type="text/javascript" src="/js/lightbox_plus.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<script>
var StoreID = "{$smarty.session.ShopID}";
var soc_http_host="{$soc_http_host}";
</script>
{literal}
<script>
function showtabfunc(obj){
	if(obj=='uploadtab'){
		$('#normaltab').hide();
		$('#is_choose_upload').val(1);
	}else{
		$('#uploadtab').hide();
		$('#is_choose_upload').val(0);
	}
	$('#'+obj).show();
}

$(function() {
	$("#csvupload").makeAsyncUploader({
		upload_url: soc_http_host+"uploadcsvemail.php?type=gallery&StoreID="+StoreID,
		flash_url: '/skin/red/js/swfupload.swf',
		button_image_url: '/skin/red/images/blankButton.png',
		disableDuringUpload: 'INPUT[type="submit"]',
		file_types:'*.csv',
		file_size_limit:'10MB',
		file_types_description:'CSV files',
		button_window_mode:"transparent",
		button_text:"",
		height:"29",
		debug:false
	});
});
function uploadresponse(response){
	var aryResult = response.split('|');
	if(parseInt(aryResult[0])>0){
		$('#is_csv_upload').val(1);
		loadCSVUser(StoreID);
	}else{
		$('#is_csv_upload').val(0)
	}
	$('#uploadmsg').html(aryResult[1]);
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
<div>
	<div><h3 style="font-size:16px; color:#666; font-weight:bold;width:550px; float:left;">{$req.galleryinfo.gallery_category}</h3>
    	<a style="color:#74ACE1; margin-top:16px; float:left; font-weight:bold;" href="/soc.php?act=galleryinfo&cid={$req.galleryinfo.id}&cp=emaillist">Send gallery to my friends history</a>
        <div style="clear:both"></div>
    	<table>
        	<tr>
            	<td valign="top"><div style="border:1px solid #ccc; padding:3px; float:left;"><img src="{if $req.galleryinfo.gallery_category_thumbs}{$req.galleryinfo.gallery_category_thumbs}{else}/images/100x100.jpg{/if}"/></div></td>
            	<td valign="top" style="padding-left:10px;"><div style="width:600px;">{$req.galleryinfo.gallery_category_desc}</div>
                </td>
            </tr>
        </table>
    </div>
   <div style="width:100%; height:1px; background:#ccc; margin:10px 0;"></div>
<!---end show the gallery info--->

<div>
<div style="width:100%; color:red; text-align:center">{$req.msg}</div>
<h3 style="font-size:16px; color:#666; font-weight:bold;width:100;">Send gallery to my friends</h3></div>
<form action="" method="post" onSubmit="return checkeventSend();">
<table cellspacing="4" cellpadding="4" width="100%">
	<tr>
    	<td style="width:120px;" align="center">Email Subject*:</td>
       	<td><input type="text" class="inputB" id="subject" name="subject"/></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
    	<td align="right">Please Select:&nbsp;</td>
            <td><input type="radio" checked="checked" name="up_tab" onclick="showtabfunc('normaltab');"/>Standard Mode							
            <input type="radio" name="up_tab" onclick="showtabfunc('uploadtab')"/>Bulk Upload Mode</td>
        <td></td>
    <tr id="normaltab">
    	<td style="width:120px;" align="center">Add a Recipient Email:</td>
       	<td><input type="text" class="inputB" id="recivers" style="float:left;"/> <input type="button" onClick="addgalleryuser();" value="Add Recipient" class="inputB" style="width:110px; height:28px;float:left; margin:0 3px;"/><span style="height:28px; float:left; color:red;">Click here after each email <br/>address is entered.</span></td>
        <td></td>
        <td></td>
    </tr>
    
    <tr id="uploadtab" style="display:none">
    	<td align="center">Upload CSV:</td>
        <td colspan="3"><input type="file" name="csvupload" id="csvupload" style="display:none"/><span style="float:left; height:29px; line-height:29px;">&nbsp;&nbsp;<a href="/pdf/emailcsv2.csv">CSV Sample</a></span><div id="uploadmsg" style="color:#F00; float:left; height:29px; line-height:29px; padding-left:10px;"></div></td>
    </tr>
      <input type="hidden" id="is_choose_upload" value="0"/>
      <input type="hidden" id="is_csv_upload" name="is_csv_upload" value="0"/>
     <tr>
    	<td style="width:120px;" align="center">Add emails from previous invitations:</td>
       	<td> <select class="inputB" id="invationlist"  style="width:287px;padding:5px;">
        {if $req.eventlist}
        	{foreach from=$req.eventlist item=el}
            <option value="{$el.add_time}">{$el.subject}</option>
            {/foreach}
        {/if}
        </select> <input type="button" class="inputB" onClick="loaduserlist({$req.galleryinfo.StoreID});" value="Add Recipients" style="width:110px; height:28px;cursor:pointer;"/></td>
        <td></td>
        <td></td>
    </tr>
    
    <tr>
    	<td style="width:120px;" align="center" valign="top">
        </td>
       	<td valign="bottom" colspan="3">
      <select multiple class="inputB" id="userlist" name="userlist[]" size="12" style="float:left;width:287px;height:192px;*height:auto;">
      </select>&nbsp;<input type="button" value="Remove Recipients" style="width:120px; cursor:pointer;height:28px;margin-top:164px;*margin-top:157px;" class="inputB" onClick="removeuser()"/>
        </td>
    </tr>
    <tr>
    	<td style="width:120px;" align="center">Email Content*</td>
        <td colspan="3" >
        	<textarea style="width:548px; height:200px;" id="emailcontent" name="content" class="inputB">Text here...

Please click here to view my gallery: 
{$gallerylink}

The gallery password: {$req.galleryinfo.gallery_category_password}           
            </textarea>
        </td>
    </tr>
    <tr><td align="center">Email Signature:</td><td>
    <textarea style="width:548px; height:50px;" name="signature" class="inputB">
Regards,
{$smarty.session.NickName} 
    </textarea>
    </td></tr>
    <tr>
    	<td style="width:120px;"></td>
	    <td colspan="3" align="right" style="padding-right:55px;"><input type="image" src="/skin/red/images/buttons/bu-send.png"/></td>
    </tr>
</table>
<input type="hidden" value="sendinvit" name="sendinvit"/>
</form>
</div>