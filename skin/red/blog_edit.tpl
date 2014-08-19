<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<link type="text/css" rel="stylesheet" href="/skin/red/css/blog.css" />
{literal}
<style type="text/css">
span#blog_1_uploading .uploadcancel,span#blog_2_uploading .uploadcancel,span#blog_3_uploading .uploadcancel,span#blog_4_uploading .uploadcancel{ border:0;}
span#asyncUploader_blog_1,span#asyncUploader_blog_2,span#asyncUploader_blog_3,span#asyncUploader_blog_4{
	position:relative;
	width:auto;
}
div#pro_tab_bar_blog_1{
	position:absolute;
	width:185px;
	background:#FFF;
}
div#pro_tab_bar_blog_2,div#pro_tab_bar_blog_3,div#pro_tab_bar_blog_4{
	position:absolute;
	width:185px;
	background:#FFF;
}
div#ProgressBar_blog_1{
	width:70px;
}
div#ProgressBar_blog_2,div#ProgressBar_blog_3,div#ProgressBar_blog_4{
	width:50px;
}
</style>
<script language="JavaScript" type="text/JavaScript">
<!--//
{/literal}
var soc_http_host="{$soc_http_host}";
{literal}
function checkForm(obj){
	var boolResult	=	true;
	try{
		if(obj.subject.value == ''){
			alert("Subject is required.");
			return false;
		}
		if(obj.content.value == ''){
			alert("Content is required.");
			return false;
		}
	}catch(ex){
		alert(ex);
	}
	return boolResult;
}

function ImgBlank(picType){
	if (confirm("Are you sure you want to delete?")){
		if(picType == 1){
			MM_swapImage('MainIMG2','','images/imagetemp.gif',1);
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

function SetImg(imgN){
	MM_swapImage('MainIMG2','',imgN,1);
	document.mainForm.MainImageH.value = imgN ;
}

function imageLoadSizeChange(obj,width,height)
{
	try{
		//var testObj = document.getElementById('test');
		//testObj.innerHTML += obj.width +" |"+ obj.height + "<br>";
		
		if (obj.width >= obj.height){
			if(obj.width != width){
				obj.height = width / (obj.width/obj.height);
				obj.width  = width;
			}
			//testObj.innerHTML  += "1) " + obj.width +" |"+ obj.height + "<br>";
			
			if(obj.height >= height){
				obj.width  = obj.height * obj.width/obj.height;
				obj.height = height;
			}
			//testObj.innerHTML  += "1) " + obj.width +" |"+ obj.height + "<br>";
			
		}else{
			if(obj.height >= height){
				obj.width  = height * obj.width/obj.height;
				obj.height = height;
			}
			//testObj.innerHTML  += "2) " + obj.width +" |"+ obj.height + "<br>";
			
			if(obj.width >= width){
				obj.height = obj.width / obj.width/obj.height;
				obj.width  = width;
			}
			//testObj.innerHTML  += "2) " + obj.width +" |"+ obj.height + "<br>";
			
		}
	}catch(ex){
		alert(ex);
	}

}

function ImgBlank(img){
	if (confirm("Are you sure you want to delete?")){
		if(img == 0){
			MM_swapImage('MainIMG2','','images/50x50.gif',1);
			document.mainForm.mainImageH.value = '' ;
		}else{
			MM_swapImage('moreIMG'+img,'','images/50x50.gif',1);
			moreImg = eval('document.mainForm.moreImage'+img);
			moreImg.value = '' ;
		}
	}
}

$(function() {
		$("#blog_1").makeAsyncUploader({
			upload_url: soc_http_host+"uploadblog.php?type=0",
            flash_url: '/skin/red/js/swfupload.swf',
            button_image_url: '/skin/red/images/swfupload.png',
            disableDuringUpload: 'INPUT[type="submit"]',
           	file_types:'*.jpg;*.gif;*.png',
			file_size_limit:'10MB',
			file_types_description:'All images',
			button_window_mode:"transparent",
			button_text:"",
			height:"29",
			width:"73",
            debug:false
		});
		$("#blog_2").makeAsyncUploader({
			upload_url: soc_http_host+"uploadblog.php?type=1",
            flash_url: '/skin/red/js/swfupload.swf',
            button_image_url: '/skin/red/images/swfupload.png',
            disableDuringUpload: 'INPUT[type="submit"]',
           	file_types:'*.jpg;*.gif;*.png',
			file_size_limit:'10MB',
			file_types_description:'All images',
			button_window_mode:"transparent",
			button_text:"",
			height:"29",
			width:"73",
            debug:false
		});
		$("#blog_3").makeAsyncUploader({
			upload_url: soc_http_host+"uploadblog.php?type=2",
            flash_url: '/skin/red/js/swfupload.swf',
            button_image_url: '/skin/red/images/swfupload.png',
            disableDuringUpload: 'INPUT[type="submit"]',
            file_types:'*.jpg;*.gif;*.png',
			file_size_limit:'10MB',
			file_types_description:'All images',
			button_window_mode:"transparent",
			button_text:"",
			height:"29",
			width:"73",
            debug:false
		});
		$("#blog_4").makeAsyncUploader({
			upload_url: soc_http_host+"uploadblog.php?type=3",
            flash_url: '/skin/red/js/swfupload.swf',
            button_image_url: '/skin/red/images/swfupload.png',
            disableDuringUpload: 'INPUT[type="submit"]',
            file_types:'*.jpg;*.gif;*.png',
			file_size_limit:'10MB',
			file_types_description:'All images',
			button_window_mode:"transparent",
			button_text:"",
			height:"29",
			width:"73",
            debug:false
		});
		
}); 
function uploadresponse(response){
	var aryResult = response.split('|');
	if(aryResult.length>0){
		var type = $.trim(aryResult[2]);
		if(type=="0"){
			$("#MainIMG2").attr("src",aryResult[0]);
			$("#mainImage").val(aryResult[0]);
			$("#mainImageH").val(aryResult[0]);
			$("#fileUpload").val("YES");
		}else{
			$("#moreIMG"+type).attr("src",aryResult[0]);
			$("#moreImage"+type).val(aryResult[0]);
			$("#mImg"+type).val("YES");
		}
	}
}
function uploadprocess(bl){
	
}
//-->
</script>
{/literal}
<p align="center" class="txt"><font color="#FF0000">{$req.select.msg}</font></p>
<form method="post" action="soc.php?cp=blogedit" name="mainForm" id="mainForm" onsubmit="return checkForm(this);">
<table width="720" border="0" align="center" cellpadding="0" cellspacing="4" id="table14">
	<tr>
		<td width="100">&nbsp;</td>
		<td height="30" colspan="2" class="tip">
			{$lang.msgCreateBlog} <img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" />
		</td>
	</tr>
	<tr>
		<td height="30" align="right">
			<span class="txt"><span class="lbl">Subject*</span></span>
		</td>
		<td colspan="2">
			<span class="style11"><font face="Verdana" size="1">
				<input name="subject" maxlength="250" type="text" class="inputB" id="subject" value="{$req.select.subject}" size="30" />
			</font></span>
		</td>
	</tr>
	<tr valign="top">
		<td align="right">
			<span class="txt"><span class="lbl">Content*</span></span>
		</td>
		<td>
			<textarea name="content" rows="5" class="inputB textarea">{$req.select.content}</textarea>
		</td>
	</tr>
	<tr>
		<td height="30" align="right">
			<span class="txt"><span class="lbl">Image</span></span>
		</td>
		<td colspan="2">
			<span class="lbl">
				<!--<a href="#" onclick="javascript:window.open('uploadfile.php?ut=9&amp;idfn=MainIMG2&amp;idhn=mainImageH&amp;idun=fileUpload&amp;edit=1','addimage','width=700,height=250,statusbars=yes,status=yes')"><img src="/skin/red/images/buttons/or-uploadimage.gif" border="0" /></a>-->
			</span>
			<span class="style11">
				<font face="Verdana" size="1">
					<a class="help" href="#">
						<img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" />
						<span><span>Click on the 'Upload Images' button, in the pop-up window click 'browse' and go to the location on your computer where the image is saved, then 'upload'.</span></span>
					</a>
				</font>
			</span>
		</td>
	</tr>
	<tr align="left" valign="top">
		<td>&nbsp;</td>
		<td colspan="2">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="center" valign="bottom">
						<input name="act" type="hidden" value="{$req.select.act}" />
						<input name="submit" type="hidden" value="add postings" />
						<input name="bid" type="hidden" value="{$req.select.bid}" />
						<input name="mainImageH" id="mainImageH" type="hidden" value="{$req.select.mainImageH}" />
						<input name="moreImage1" id="moreImage1" type="hidden" value="{$req.select.moreImage1}" />
						<input name="moreImage2" id="moreImage2" type="hidden" value="{$req.select.moreImage2}" />
						<input name="moreImage3" id="moreImage3" type="hidden" value="{$req.select.moreImage3}" />
						<input name="mImg1" type="hidden" id="mImg1" value="NO" />
						<input name="mImg2" type="hidden" id="mImg2" value="NO" />
						<input name="mImg3" type="hidden" id="mImg3" value="NO" />
						<input name="mainImageP" id="mainImageP" type="hidden" value="" />
						<input name="fileUpload" type="hidden" id="fileUpload" value="NO" />
						<input name="pid" type="hidden" id="pid" value="{$req.select.pid}"/>
						<img src="{$req.select.image_name}" name="MainIMG2" border="1" width="150" id="MainIMG2" /><br /></td>
				  <td width="140" align="center" valign="bottom"><img src="{$req.select.moreImage1}" name="moreIMG1" width="100" border="1" id="moreIMG1" /><br /></td>
					<td width="140" align="center" valign="bottom">
						<img src="{$req.select.moreImage2}" name="moreIMG2" width="100" border="1" id="moreIMG2" /><br /></td>
					<td width="140" align="center" valign="bottom">
						<img src="{$req.select.moreImage3}" name="moreIMG3" width="100" border="1" id="moreIMG3" /></td>
				</tr>
				<tr>
				  <td height="45" align="center" valign="middle" style="padding-left:20px;"><input type="file" id="blog_1" style="display:none" />
                  <a href="javascript:ImgBlank(0)" style="float:left; margin-top:5px;"><img src="/skin/red/images/icon-deletes.gif" border="0" /></a>
                  </td>
				  <td align="center" valign="middle" style="padding-left:15px;">
                  <input type="file" id="blog_2" style="display:none" /> 
                  <a href="javascript:ImgBlank(1)" style="float:left; margin-top:5px;"><img src="/skin/red/images/icon-deletes.gif" border="0" /></a></td>
				  <td align="center" valign="middle" style="padding-left:15px;">
                  <input type="file" id="blog_3" style="display:none" /> 
                  <a href="javascript:ImgBlank(2)" style="float:left; margin-top:5px;"><img src="/skin/red/images/icon-deletes.gif" border="0" /></a></td>
				  <td align="center" valign="middle" style="padding-left:15px;">
                  <input type="file" id="blog_4" style="display:none" /> 
                  <a href="javascript:ImgBlank(3)" style="float:left; margin-top:5px;"><img src="/skin/red/images/icon-deletes.gif" border="0" /></a></td>
			  </tr>
			</table>
		</td>
	</tr>
	<tr align="left" valign="top">
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr align="left" valign="top">
		<td>&nbsp;</td>
		<td height="70" align="center" valign="middle" colspan="2">
			<table width="100%" height="75" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td height="32" class="redrequired">* required fields</td>
				</tr>
				<tr>
					<td height="27" align="center" class="redrequired">
						<span class="redrequired">
							<input type="image" src="/skin/red/images/buttons/or-addupdatepost.gif" style="border:none" />
						</span>
					</td>
				</tr>
				<tr>
					<td class="redrequired">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr align="left" valign="top">
		<td></td>
		<td colspan="2" bgcolor="#FFFFFF"></td>
	</tr>
</table>
</form>
