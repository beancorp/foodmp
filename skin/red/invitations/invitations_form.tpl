<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript"  src="/js/lightbox_plus.js"></script>
<script>var StoreID='{$smarty.session.ShopID}';</script>
{literal}
<style type="text/css">
.templatelist{
	list-style:none;
	margin-left:15px;
	_margin-left:8px;
	padding:0;
	float:left;
}
.templatelist li{
	list-style:none;
	float:left;
	margin-right:20px;
	margin-bottom:20px;
	text-align:center;
}
#msg{
	width:100%;
	color:#F00;
	text-align:center;
}

.tabsDiv{
	list-style:none;
	margin:0;
}
.tabsDiv li{
	list-style:none;
	width:100px;
	height:40px;
	line-height:40px;
	text-align:center;
	float:left;
	cursor:pointer;
	font-weight:bold;
}
.tabsDiv li.active_tab{
	background-color:#FFF;
}
.uploadtab,.uploadtab_active{
	height:25px; 
	font-weight:bold; 
	line-height:25px; 
	text-align:center; 
	background:#ccc; 
	width:144px; 
	float:left;
	border-bottom:1px solid #ccc;
	cursor:pointer;
}
.uploadtab_active{
	background:#FFF;
}
.class_uploadding{ padding-right:10px;}

</style>
<script>
function checkInvitations(){
	var errors = "";
	var nameobjs = $('input[@name="invitation_name[]"]');
	var emailobjs = $('input[@name="invitation_email[]"]');
	var eventobjs = $('input[@name="event_to[]"]');
	
	if($('#subject').val()==""){
		errors += "-  Invitation Subject is required.\n";
	}
	if($('#is_choose_upload').val()=="0"){
		for(var j=0,z=1; j<nameobjs.length; j++,z++){
			if($(nameobjs[j]).val()==""){
				errors += "-  Name("+(j==0?"s":z)+") is required.\n";
			}
		}
		for(var j=0,z=1; j<emailobjs.length; j++,z++){
			if($(emailobjs[j]).val()==""){
				errors += "-  Email "+(j==0?"":z)+" Address is required.\n";
			}else if(!ifEmail($(emailobjs[j]).val(),false)){
				errors += "-  Email "+(j==0?"":z)+" Address is invalid.\n";
			}
		}
	}else{
		if($('#is_csv_upload').val()=="0"){
			errors += "-  CSV file is invalid.\n";
		}
	}
	/*for(var j=0,z=1; j<eventobjs.length; j++,z++){
		if($(eventobjs[j]).val()==""){
			errors += "-  To "+(j==0?"":z)+" is required.\n";
		}
	}*/
	if($('#event').val()==""){
		errors +='-  Custom message line 1 is required.\n';
	}
	if($('#event_time').val()==""){
		errors +='-  When is required.\n';
	}
	if($('#event_addr').val()==""){
		errors +='-  Where is required.\n';
	}
	if($('#event_RSVP').val()==""){
		errors +='-  RSVP By is required.\n';
	}
	if($('#template_type').val()=='user' && $('#usertpl_img').val()==""){
		errors +='-  You should upload a banner for this template.\n';
	}
	if(errors!=""){
		errors = 'Sorry, the following fields are required.\n'+errors;
		alert(errors);
		return false;
	}else{
		return true;	
	}	
}
function ifEmail(str,allowNull){
	if(str.length==0) return allowNull;
	i=str.indexOf("@");
	j=str.lastIndexOf(".");
	if (i == -1 || j == -1 || i > j) return false;
	return true;
}
function changeTab(obj,key){
	var nextobj = $('.active_tab');
	var nextkey = nextobj.attr('id');
	nextobj.removeClass('active_tab');
	$(obj).addClass('active_tab');
	$('#key_'+nextkey).css('display','none');
	$('#key_'+key).css('display','');
	$('#key_'+key+" li:first-child input[name='email_template']").attr('checked','true');
	if(key==$('#xtype').val()){		
		$('#tpkid_'+$('#xtpid').val()).attr('checked','true');
	}
	$('#template_type').val(key);
	if(key=='wedding'){
		$('#weddingaddr').css('display','');
		$('#weddingaddrline').css('display','');
	}else{
		$('#weddingaddr').css('display','none');
		$('#weddingaddrline').css('display','none');
	}
}
var tabs = 0;
function addsubTab(){
	tabs += 1;
	var subhtml = '<table width="100%" cellspacing="4" cellpadding="0" id="subTabc'+tabs+'">';
	    subhtml += '<tr><td style="width:143px;*width:144px;" align="right">Name (<span class="tab1Num">'+tabs+'</span>):*</td>';
		subhtml += '<td align="left" align="left"><input type="text" class="inputB" name="invitation_name[]" maxlength="40" onblur="updateToText(this,'+tabs+');" /></td></tr>';
		subhtml += '<tr><td align="right">Email Address (<span class="tab2Num">'+tabs+'</span>):*</td>';
		subhtml += '<td align="left" align="left"><input type="text" class="inputB" name="invitation_email[]" maxlength="40" style="float:left;"/><a href="javascript:deleteTab('+tabs+');" style="float:left; margin-left:5px; margin-top:5px;"><img src="/skin/red/images/icon-deletes.gif"</a><div style="clear:both;"></div></td></tr></table>';
	var subhtml2 = '<table width="100%" cellspacing="4" cellpadding="0" id="subEvto'+tabs+'">';
		subhtml2 +='<tr><td align="right" style="width:148px;*width:144px;">To (<span class="tab3Num">'+tabs+'</span>):*</td><td align="left"><input type="text" id="event_to'+tabs+'" name="event_to[]" class="inputB"/></td></tr></table>';
	$('#subTab').append(subhtml);
	//$('#subTab2').append(subhtml2);
	resortNewIndex();
	
}
function deleteTab(id){
	$('#subTabc'+id).remove();
	//$('#subEvto'+id).remove();
	resortNewIndex();
}

function resortNewIndex(){
	var objtb = $('.tab1Num');
	var objtt = $('.tab2Num');
	//var objtc = $('.tab3Num');
	for(var i=0,j=2; i<objtb.length; i++,j++){
		$(objtb[i]).html(j);
		$(objtt[i]).html(j);
		//$(objtc[i]).html(j);
	}
}
function updateToText(obj,id){
	if($('#event_to'+id).val()==""){
		$('#event_to'+id).val($(obj).val());
	}
}
function changeAction(id){
	if(id=='preview'){
		$('#invition_form').attr('action','/soc.php?act=invitations&cp=preview');
		$('#invition_form').attr('target','_blank');
		if(checkInvitations()){
			$('#invition_form').submit();
		}
	}else{
		$('#invition_form').attr('action','');
		$('#invition_form').attr('target','');
	}
	
	
}

$(function() {
	$("#csvupload").makeAsyncUploader({
		upload_url: "{/literal}{$soc_http_host}{literal}uploadcsvemail.php?type=invitation&StoreID="+StoreID,
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
	$("#custom_photo").makeAsyncUploader({
		upload_url: "{/literal}{$soc_http_host}{literal}uploadgallery.php?type=invitation",
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
	if(aryResult[2]&&$.trim(aryResult[2])=='invitation'){
		$("#disimg").attr('src',"/"+aryResult[0]);
		$("#usertpl_img").val(aryResult[0]);
	}else{
		if(parseInt(aryResult[0])>0){
			$('#is_csv_upload').val(1);
		}else{
			$('#is_csv_upload').val(0)
		}
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
function changeuploadtab(tab,obj){
	if(tab=='normal'){
		$('#is_choose_upload').val(0);
		$('#nor_tab5').hide();
		$('#nor_tab6').hide();
		$('#nor_tab1').show();
		$('#nor_tab2').show();
		$('#nor_tab3').show();
		$('#nor_tab4').show();
	}else{
		$('#is_choose_upload').val(1);
		$('#nor_tab5').show();
		$('#nor_tab6').show();
		$('#nor_tab1').hide();
		$('#nor_tab2').hide();
		$('#nor_tab3').hide();
		$('#nor_tab4').hide();
	}
}
</script>
{/literal}
<div style="text-align:left">
<div id="msg">{$req.msg}</div>
<form id="invition_form" action="/soc.php?act=invitations" method="post" onsubmit="return checkInvitations()">
	<table width="100%" cellpadding="0" cellspacing="4">
    	<tr><td colspan="2" align="left"><h3 style="font-size:16px; color:#666; font-weight:bold;width:100$">All responses to your invitation will be sent to <a href="mailto:{$req.email}" style="font-size:16px;">{$req.email}</a>. <span style="padding-left:20px;"><a href="/soc.php?act=invithis&cp=list" style="color:#74ACE1;"><strong style="color:#74ACE1;">View Invites History</strong></a></span></h3>Person(s) who will be receiving your invitaion.</td></tr>
        <tr><td width="20%" align="right">Invitation Subject:*</td>
        	<td><input type="text" class="inputB" name="subject" id="subject" maxlength="200" value="{$req.invationInfo.0.subject}"/></td>
        </tr>
        <tr>
        	<td align="right">Please Select:&nbsp;</td>
            <td><input type="radio" checked="checked" name="up_tab" onclick="changeuploadtab('normal',this);"/>Standard Mode							
            <input type="radio" name="up_tab" onclick="changeuploadtab('csv',this)"/>Bulk Upload Mode</td>
        </tr>
        
    	<tr id="nor_tab1"><td width="20%" align="right">Name(s):*</td>
        	<td align="left" align="left">
            <input type="text" class="inputB" id="invitation_name1" name="invitation_name[]" maxlength="40" onblur="updateToText(this,0);"/> </td></tr>
    	<tr id="nor_tab2"><td align="right">Email Address:*</td>
        	<td align="left" align="left">
        	<input type="text" class="inputB" name="invitation_email[]" maxlength="40" /> </td></tr>
        <tr id="nor_tab3"><td></td><td><a href="javascript:addsubTab();" style="margin-left:180px;">Add more receivers</a></td></tr>
        <tr id="nor_tab4"><td colspan="2" id="subTab"></td></tr>
      
        <tr id="nor_tab5" style="display:none"><td align="right" >Upload CSV:</td><td valign="middle"><input type="file" name="csvupload" id="csvupload" style="display:none"/><span style="float:left; height:29px; line-height:29px;">&nbsp;&nbsp;<a href="/pdf/emailcsv.csv">CSV Sample</a></span></td></tr>
        <tr id="nor_tab6" style="display:none"><td align="right" ></td><td id="uploadmsg" style="color:#F00;"></td></tr>
        <input type="hidden" id="is_choose_upload" name="is_choose_upload" value="0"/>
        <input type="hidden" id="is_csv_upload" name="is_csv_upload" value="0"/>
        
       <tr><td colspan="2"><div style="margin:15px 0 ; width:100%; height:1px; border-bottom:1px solid #ccc"></div>       </td></tr>  
        <tr><td colspan="2" align="left"><h3 style="font-size:16px; color:#666; font-weight:bold;">Invitation Template</h3><span>Click on the invitation template image to see an enlarged view showing the field placements.
</span><br/><br/>
</td></tr>
        <tr><td colspan="2">
        <input type="hidden" id="xtype" value="{$req.invationTpl.type}"/>
        <input type="hidden" id="xtpid" value="{$req.invationTpl.id}"/>
         <div style="background-color:#EEE; height:40px; margin:0 15px; border-bottom:1px solid #EEE;">
            <ul class="tabsDiv">
            {foreach from=$req.template item=l key=k}
            	{if $k neq 'user'}
                <li id="{$k}" {if $req.invationTpl.type}{if $k eq $req.invationTpl.type}class='active_tab'{/if}{else}{if $k eq 'xmas'}class='active_tab'{/if}{/if} onclick="changeTab(this,'{$k}');">
                	{if $k eq 'general'}All Occasions
                    {elseif $k eq 'xmas'}Xmas
                    {elseif $k eq 'birthday'}Birthday
                    {elseif $k eq 'wedding'}Wedding
                    {elseif $k eq 'party'}Party{/if}</li>
                {/if}
            {/foreach}
            	<li id="user" onclick="changeTab(this,'user');">Create Your Own</li>
            </ul>
         </div>
        {foreach from=$req.template item=l key=k}
            <div id="key_{$k}" style="width:100%; {if $req.invationTpl.type}{if $k neq $req.invationTpl.type}display:none;{/if}{else}{if $k neq 'xmas'}display:none;{/if}{/if}">
            {if $k eq 'user'}
            	<div style="padding-top:10px;">
                <div style="position:relative; padding:0 15px; width:265px; float:left;">
                <!--<iframe id="ifupload" marginheight="0" frameBorder=0 scrolling="no" hspace="0" vspace="0" style="margin: 0px;padding:5px 0; z-index:100;border: 0px ; float:left;" height="105" width="265px" src="/invationupload.php"></iframe>-->
                <div style="margin:10px 0;">
            	<input type="file" id="custom_photo" name="Filedata" style="display:none" /> <span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">To give your page impact we recommend you load a photo to your website. Simply click 'Browse' and go to the location on your computer where the photo is saved.<br/>Please keep the image to a maximum of 8MB in size. </span></span></a></span><div style="clear:both"></div></div>
            	<div>For perfect fit, the image size is 749 x 216 pixels </div>
                </div>
               
                
                <div style="width:425px; height:147px; float:left; margin-left:5px;">
                <img id="disimg" src="{if $req.userbanner}{$req.userbanner}{else}/skin/red/invitations/user/template.jpg{/if}" width="425" height="147"/>
                <input type="hidden" value="{if $req.userbanner}{$req.userbanner}{else}{/if}" name="usertpl_img" id="usertpl_img"/>
                </div>
                <div style="clear:both"></div>
                </div>
                
                
            {/if}
            <ul class="templatelist">
            {foreach from=$l item=tl}
            	<li><a href="/skin/red/invitations/tmpl_images/{$tl.images}" rel=lightbox><img src="/skin/red/invitations/tmpl_images/{$tl.thumbs}" width="124"/></a><br/>
                	<input type="radio" id="tpkid_{$tl.id}" name="email_template" value="{$tl.id}" {if $req.invationInfo.0.email_template}{if $tl.id eq $req.invationInfo.0.email_template}checked="checked"{/if}{else}{if $k eq 'xmas' && $tl.id eq 3}checked="checked"{/if}{/if}/>
                </li>
            {/foreach}
            </ul>
            </div>
         {/foreach}
        	</td>
        </tr>
        <tr><td colspan="2" height="1">
        	<div style="margin:15px 0 ; width:100%; line-height:1px; height:1px; border-bottom:1px solid #ccc"></div>
        </td></tr>
        <tr><td colspan="2" align="left"><h3 style="font-size:16px; color:#666; font-weight:bold;">Invitation Details</h3><span><strong>Note:</strong> Not all the fields are used in every template.</span><br/><br/></td></tr>
        <tr style="display:none;"><td align="right" >To:*</td>
        	<td align="left"><input type="text" id="event_to0" name="event_to[]" class="inputB" value=""/></td></tr>
        <tr><td colspan="2" id="subTab2">
        	
        </td></tr>    
            
        <tr><td align="right">Custom message line 1:*</td>
        	<td align="left"><input type="text" id="event" name="event_1" class="inputB" maxlength="130" style="float:left" value="{$req.invationInfo.0.event_1}"/><span class="style11" style="margin-left:5px; float:left;"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">This is indicated by the (1) in the enlarged template view.</span></span></a></span></font></span></td></tr>
		<tr><td align="right">Custom message line 2:&nbsp;</td>
        	<td align="left"><input type="text" id="event_2" name="event_2" class="inputB" maxlength="130" style="float:left" value="{$req.invationInfo.0.event_2}"/><span class="style11" style="margin-left:5px; float:left;"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">This is indicated by the (2) in the enlarged template view.</span></span></a></span></font></span></td></tr>
       	<tr><td align="right">When:*</td>
        	<td align="left"><input type="text" value="{$req.invationInfo.0.event_time}" class="inputB" id="event_time" name="event_time" /></td></tr>
        <tr><td align="right">Where <span id="weddingaddrline" {if $req.invationTpl.type eq 'wedding'}{else}style="display:none;"{/if}>line 1</span>:*</td>
        	<td align="left"><input type="text" value="{$req.invationInfo.0.event_addr}" class="inputB" id="event_addr" name="event_addr"/></td></tr>

        <tr id="weddingaddr" {if $req.invationTpl.type eq 'wedding'}{else}style="display:none;"{/if}>
          <td align="right">Where line 2:&nbsp;</td>
        	<td align="left"><input type="text" class="inputB" value="{$req.invationInfo.0.event_addr2}" id="event_addr2" name="event_addr2"/></td></tr>

        <tr><td align="right">RSVP By:*</td>
        	<td align="left"><input type="text" class="inputB" value="{$req.invationInfo.0.event_RSVP}" id="event_RSVP" name="event_RSVP"/></td></tr>
      
        <tr><td></td><td><input type="checkbox" name="ispass" value="1" {if $req.invationInfo.0.ispass} checked="checked"{/if}/> <strong>Add link to my wishlist in the invitation.</strong></td></tr>
        <tr><td colspan="2"><div style="width:100%;height:2px; background:#293694; margin:15px 0;"></div></td></tr>
        <tr><td></td>
        	<td align="right"><img style="padding:3px; cursor:pointer;" src="/skin/red/images/buttons/bu-preview.gif" onclick="changeAction('preview');"/><input type="image" src="/skin/red/images/buttons/bu-send.png" onclick="changeAction('save');"/> </td>
            </tr>
    </table>
    <input type="hidden" value="send" name="opt"/>
    <input type="hidden" value="xmas" name="template_type" id="template_type"/>
 </form>
</div>