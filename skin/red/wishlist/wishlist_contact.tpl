<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<script>var StoreID = "{$smarty.session.ShopID}";</script>
{literal}
<script language="javascript">
function checkemailform(){
	if($('#own_name').val()==""){
		alert('Own Name is required.');
		return false;
	}
	if($('#own_email').val()==""){
		alert('Own Email is required.');
		return false;
	}else{
		if(!ifEmail($('#own_email').val(),false)){
			alert('Own Email is invalid.');
			return false;
		}
	}
	if($('#is_choose_upload').val()!="0"){
		if($('#is_csv_upload').val()=="0"){
			alert("CSV file is invalid.");
			return false;
		}
	}
	return true;
	
}
function ifEmail(str,allowNull){
	if(str.length==0) return allowNull;
	i=str.indexOf("@");
	j=str.lastIndexOf(".");
	if (i == -1 || j == -1 || i > j) return false;
	return true;
}
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
		upload_url: "/uploadcsvemail.php?type=wishlist&StoreID="+StoreID,
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
	<div align="center" style="color:#FF0000">{$req.msg}</div>
	<form action="" method="post" onsubmit="return checkemailform();">
	<div align="center">
	<div style="width:488px; padding-bottom:10px;" align="center">
		<div align="left" style="width:360px">
	Send your wishlist to your famliy or friends. 
		</div>

	</div>
	<div style="margin:0;padding:0; width:488px;">
	<img src="/skin/red/images/step1_top.jpg" border="0"  style="float:left"/>
	<div style="clear:both;"></div>
	</div>
	<div style="padding:0;margin:0;width:488px;background: rgb(241, 241, 241); height: 70px;">
	<table cellspacing="4" cellpadding="0">
		<tr><td>Your Name:*</td>
			<td><input type="text" name="own_name" id="own_name" value="{$req.own_name}" class="inputB"/></td>
			<td></td>
		</tr>
		<tr><td style="width:75px;+width:76px;">Your Email:*</td>
			<td><input type="text" name="own_email" id="own_email" value="{$req.own_email}" class="inputB"/></td>
			<td ></td>
		</tr>
	</table></div>
<img src="/skin/red/images/step1_bottom.jpg" border="0"/>
<br/><br/>
	</div>
	<div align="center">
    		<div style="width:437px"><strong>Please Select:</strong> &nbsp;&nbsp;<input type="radio" name="up_tab" checked="checked" onclick="showtabfunc('normaltab');" />Standard Mode &nbsp;&nbsp;
            <input type="radio" name="up_tab" onclick="showtabfunc('uploadtab');"/>Bulk Upload Mode </div>
            <input type="hidden" id="is_choose_upload" name="is_choose_upload" value="0"/>
        	<input type="hidden" id="is_csv_upload" name="is_csv_upload" value="0"/>
    		<table cellpadding="0" cellpadding="4" width="474" id="uploadtab" style="border:1px solid #ccc; margin:10px 0; display:none;">
            	<tr><td>&nbsp;</td>
                	<td width="80" align="left">Upload CSV:</td>
                	<td align="left" width="390"><input type="file" name="csvupload" id="csvupload" style="display:none"/><span style="float:left; height:29px; line-height:29px;">&nbsp;&nbsp;<a href="/pdf/emailcsv.csv">CSV Sample</a></span></td></tr>
                <tr><td></td><td colspan="2" id="uploadmsg" align="left" style="color:#F00"></td></tr>
            </table>
			<table cellpadding="0" cellspacing="4" width="475" id="normaltab">
				<tr><td style="background:#9E99C1; height:23px; color:#FFFFFF;font-weight:bold;" align="center">#</td><td style=" background:#9E99C1;height:23px;color:#FFFFFF;font-weight:bold;" align="center">Famliy/Friends</td><td style=" background:#9E99C1;height:23px;color:#FFFFFF; font-weight:bold;" align="center">Email</td></tr>
				{section name=foo start=0 loop=10 step=1}
					<tr><td width="3%">{$smarty.section.foo.index+1}.</td><td><input class="inputB" style="width:150px" type="text" name="nickname[]" value="{$req.nickname[$smarty.section.foo.index]}"/></td><td><input class="inputB" type="text" name="emailaddress[]"  value="{$req.emailaddress[$smarty.section.foo.index]}"/></td></tr>
				{/section}
             </table>
 			<table cellpadding="0" cellspacing="4" width="462">
			<tr><td align="left" colspan="2"><strong>Message:</strong><br/><textarea class="inputB" name="message" style="width:462px;height:100px;">{if $req.message}{$req.message}{else}I have setup a wishlist of all the items that I like. You can gift me an item by visiting my wishlist on SOC Exchange.{/if}</textarea></td></tr>
			<tr><td  colspan="2 align="left"><strong>Email Signature:</strong><br/><textarea class="inputB" name="signature" style="width:462px;height:50px;">{if $req.signature}{$req.signature}{else}Regards,
{$req.own_name}{/if}</textarea></td></tr>
			<tr><td align="left"><a href="/soc.php?act=wishlistproc&cp=wishmsglist">View list of Emails already sent</a></td><td align="right">
			<input type="image"  src="/skin/red/images/buttons/bu-send.png"/></td></tr>
			<tr><td colspan="2 align="right" style="font-size:12px; color:#F00">Note: Your wishlist password will be made visible to all persons receiving this email.</td></tr>
			</table>
		   <input type="hidden" name="optval" value="send"/>		
	</div>
	</form>
</div>