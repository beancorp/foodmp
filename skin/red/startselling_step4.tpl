<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<script language="javascript">
var protype =0;
var pid="{$smarty.get.pid}";
var copy="{$iscopy}";
var start_date="{$req.select.start_datetime.date}";
var start_hour="{$req.select.start_datetime.hour}";
var start_min="{$req.select.start_datetime.min}";
var now_date="{$smarty.now|date_format:'%d/%m/%Y'}";
var now_hour="{$smarty.now|date_format:'%H'}";
var now_min="{$smarty.now|date_format:'%M'}";
var copy_date="{$req.select.start_datetime.date}";
var copy_hour="{$req.select.start_datetime.hour}";
var copy_min="{$req.select.start_datetime.min}";
var soc_http_host="{$soc_http_host}";
</script>
<script type="text/javascript" src="/skin/red/js/productupload.js"></script>
<link type="text/css" href="/skin/red/css/swfupload_product.css" rel="stylesheet" media="screen" />
{literal}
<script type="text/javascript">
function changeList(obj)
{
	$("input[@name='ckpid[]']").attr('checked',false);
	$('#allchecka').attr('checked',false);
	$('#allcheck').attr('checked',false);
}
$(document).ready(function(){

	var v = $("input[name='rdo_import_type']:checked").val();
	if('ebay_turbolister' == v || 'ebay_blackthorn' == v) {
		$("#upload_sample_content").hide();
	}
	else {
		$("#upload_sample_content").show();
	}

	if(pid != "") {
		$("#start_date").val(start_date);
		$("#start_hour").val(start_hour);
		$("#start_minute").val(start_min);
	}
	else {
		$("#start_date").val(now_date);
		$("#start_hour").val(now_hour);
		$("#start_minute").val(now_min);
	}
	if(copy==1) {
		if(copy_date != "") {
			$("#start_date").val(copy_date);
			$("#start_hour").val(copy_hour);
			$("#start_minute").val(copy_min);
		}
		else {
			$("#start_date").val(now_date);
			$("#start_hour").val(now_hour);
			$("#start_minute").val(now_min);
		}
	}
	
	$("#down_sample_csv").click(function(){
	
		var csv_type=$("input[name='rdo_import_type']:checked").val();
		if(csv_type=='buynow') {
			href=soc_http_host+'download.php?cp=csvsample&filetype=buynow';
		}
		else {
			href=soc_http_host+'download.php?cp=csvsample&filetype=auction';
		}	
		$(this).attr('href',href);
		return;
	});
	
	$("input[name='rdo_import_type']").change(function(){
		if($("#swf_csvmsg").val()!='' || $("#swf_imgmsg").val()!='') {
			if(confirm('Are you sure you want to switch the mode without saving the file?')) {
				$("#imgmsg,#csvmsg").html('');
				$("#swf_csvmsg,#swf_imgmsg").val("");
			}
			else {
				val=$("input[name='rdo_import_type'][checked]").val();
				if(val=='buynow') {
					$("input[name='rdo_import_type'][value='auction']").attr('checked','checked');
				}
				else {
					$("input[name='rdo_import_type'][value='buynow']").attr('checked','checked');
				}
			}
		}
		
		var v = $("input[name='rdo_import_type']:checked").val();
		if('ebay_turbolister' == v || 'ebay_blackthorn' == v) {
			$("#upload_sample_content").hide();
		}
		else {
			$("#upload_sample_content").show();
		}
		
		//upload_sample_content
		/*
		if($("#swf_csvmsg").val()!='' || $("#swf_imgmsg").val()!='') {
			$("#csvmsg").html('Are you sure you want to switch the mode without saving the file?');
			$("#imgmsg").html('');
		}
		else {
			$("#imgmsg,#csvmsg").html('');
		}
		
		$("#swf_csvmsg,#swf_imgmsg").val("");
		*/
	});

});

function switchSample(ac) 
{
	if('show' == ac) {
		$("#upload_sample_content").show();
	}
	else {
		$("#upload_sample_content").hide();
	}
}

_editor_url = "";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
  document.write('<scr' + 'ipt src="' +_editor_url+ 'js/editor.js"');
  document.write(' language="Javascript1.2"></scr' + 'ipt>');  
}
else
{ 
	document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); 
}

function ToggleRow(obj){
	if(obj.value=="yes"){
		document.getElementById("deliveryPriceRow").style.display = "";
	}else if(obj.value=="no"){
		document.getElementById("deliveryPriceRow").style.display = "none";
	}else {
		document.getElementById("deliveryPriceRow").style.display = "none";
	}
}

function SetImg(imgN){
	MM_swapImage('MainIMG2','',imgN,1);
	document.mainForm.mainImageH.value = imgN ;
}

function selectSubCG(obj,obj2,subid){
	try{
		cid = document.getElementById(obj2).value;
		$.get("/soc.php", { act: "signon", step: "subcategory",cid: cid,subid: subid },
 				function(data){
					document.getElementById(obj).innerHTML = data;
		});
	}
	catch(ex)
	{
	
	}
}

function showField(tag){
	if (tag == true){
		document.getElementById('auction').style.display = '';
        document.getElementById('certifiedline').style.display = '';
        document.getElementById('youtubeline').style.display = 'none';
		document.getElementById('oboline').style.display = 'none';
		document.getElementById('auction3').style.display = 'none';
		document.getElementById('quantityline').style.display = 'none';
		document.getElementById('extraline').style.display = '';
		document.getElementById('stockQuantity').value = '1';
		document.mainForm.stockQuantity.disabled=false;
		document.mainForm.stockQuantity.removeAttribute('readOnly');
		document.getElementById('cknow').checked = true;
		document.getElementById('auctiontime').style.display = '';
		document.getElementById('auctionstarttime').style.display = '';
		document.getElementById('noauction').style.display = 'none';
		document.getElementById('auction_celebration').style.display = '';
		document.getElementById('auction_notes').style.display = '';
		//changeList('myauctions');
	}else{
		document.getElementById('auction').style.display = 'none';
        document.getElementById('certifiedline').style.display = 'none';
        document.getElementById('youtubeline').style.display = '';
		document.getElementById('oboline').style.display = '';
		document.getElementById('auction3').style.display = '';
		document.getElementById('quantityline').style.display = '';
		document.getElementById('extraline').style.display = 'none';
		document.getElementById('auctiontime').style.display = 'none';
		document.getElementById('auctionstarttime').style.display = 'none';
		document.getElementById('noauction').style.display = '';
		document.getElementById('auction_celebration').style.display = 'none';
		document.getElementById('auction_notes').style.display = 'none';
        document.mainForm.is_certified.checked = false;
		//changeList('myproducts');
	}
}
function checkForm(obj){
	var boolResult = false;
	var errors	= '';
	try{
		if(obj.cp.value=='edit') {
			obj.categorySub.value = document.getElementById('categorySubList').value;
			
			if(obj.item_name.value==''){
				errors += '-  Item Name is required.\n';
			}
			if(obj.categorySub.value==''){
				errors += '-  Category is required.\n';
			}

			if(obj.description.value==''){
				errors += '-  Description is required.\n';
			}
			
			if ($('#mainImage_dis').attr('src') == '/images/243x212.jpg') {
				errors += '-  Image is required.\n';
			}
			
			/**vaild the price***/
			var r = /^[0-9]*[1-9][0-9]*$/;
			if(obj.price.value == '' && $('input[name=is_auction][checked]').val()=='no'){
				errors += '-  Price is required.\n';
			}else if (obj.initial_price.value == '' && $('input[name=is_auction][checked]').val()=='yes'){
				errors += '-  Starting price has to be a whole number without decimals.\n';
			}else if (obj.end_date.value == '' && $('input[name=is_auction][checked]').val()=='yes'){
				errors += '-  End time is required.\n';
			}else if (isNaN(obj.price.value) && $('input[name=is_auction][checked]').val()=='no'){
				errors += '-  Price is invalid.\n';
			}else if (!r.test(obj.initial_price.value) && $('input[name=is_auction][checked]').val()=='yes'){
				errors += '-  Starting price has to be a whole number without decimals.\n';
			}
			
			
			/***vaild the shipping method****/
			var ck_bl_err =false;
			var ck_is_err =true;
			$.each($('.ck_deliveryMethod'),function(i,n){
				if($(n).attr('checked')){
					//if (parseInt($(n).val()) == 1 || parseInt($(n).val()) == 2){
						if(!isNaN(parseFloat($($('.input_postage')[i]).val()))&&(parseFloat($($('.input_postage')[i]).val())>=0||$($('.input_postage')[i]).val()=="0")){}else{
							ck_bl_err = true;
						}
					//}
					ck_is_err = false;
				}
			});
			if(ck_is_err){errors += '-  Normal Shipping Method is required.\n';}
			if(ck_bl_err){errors += '-  Please set the cost for your shipping methods. \n';}
			
			if(obj.isoversea.checked){
				var cko_bl_err =false;
				var cko_is_err =true;
				$.each($('.ck_oversea_deliveryMethod'),function(i,n){
					if($(n).attr('checked')){
						if(!isNaN(parseFloat($($('.input_oversea_postage')[i]).val()))&&(parseFloat($($('.input_oversea_postage')[i]).val())>0||$($('.input_oversea_postage')[i]).val()=="0")){
							
						}else{
							cko_bl_err = true;
						}
						cko_is_err = false;
					}
				});
				if(cko_is_err){errors += '-  Overseas Shipping Method is required.\n';}
				if(cko_bl_err){errors += '-  The overseas shipping method is not completed.\n';}
			}
			
		}
		/*
		else{
			if(obj.OfferDelevery.value =='yes' && isNaN(obj.DeliveryPrice.value)){
				errors += '- Delivery Price must contain a number.\n';
			}
		}*/
		
		if(errors != '')
		{
			errors = '-  Sorry, the following fields are required.\n' + errors;
			alert(errors);
		}else{
			boolResult = true;
		}
		
	}catch(ex)
	{
		alert(ex);
	}
	
	return boolResult;
}
function checkImport(obj){
	var boolResult = false;
	var errors	= '';
	
	var uploadType = $("[name='rdo_import_type']:checked").val();
	
	if(!obj.csv){
		if($('#swf_csvmsg').val()==""){
			errors += '-  Products Information is required.\n';
		}
		if($('#swf_imgmsg').val()==""){
			if(uploadType != 'ebay_turbolister') {
				errors += '-  Products Images is required.\n';
			}
		}
		if(errors != '')
		{
			errors = '-  Sorry, the following fields are required.\n' + errors;
			alert(errors);
		}else{
			boolResult = true;
		}
		return boolResult;
	}
	try{
		if(obj.csv.value==''){
			errors += '-  Products file is required.\n';
		}
		
		if(errors != '')
		{
			errors = '-  Sorry, the following fields are required.\n' + errors;
			alert(errors);
		}else{
			boolResult = true;
		}
		
	}catch(ex)
	{
		alert(ex);
	}
	
	return boolResult;
}

function checkallitem(type){
	if (type == 'buy'){
		if($('#allcheck').attr('checked')){
			$("input[@name='ckpid[]'].b4js").attr('checked',true);
		}else{
			$("input[@name='ckpid[]'].b4js").attr('checked',false);
		}
	}else{
		if($('#allchecka').attr('checked')){
			$("input[@name='ckpid[]'].a4js").attr('checked',true);
		}else{
			$("input[@name='ckpid[]'].a4js").attr('checked',false);
		}
	}
}

function multcheckform(obj){
	if($("input[@name='ckpid[]'][@checked]").length>0){
		switch(obj.value){
			case 'Delete':
				if(confirm('Are you sure you want to delete these items?')){
					$('#multcp').val('delete');
					$('#multform').submit();
				}
				break;
			case 'Publish':
				if(confirm('Are you sure you want to publish these items?')){
					$('#multcp').val('publish');
					$('#multform').submit();
				}
				break;
			case 'Unpublish':
				if(confirm('Are you sure you want to unpublish these items?')){
					$('#multcp').val('unpublish');
					$('#multform').submit();
				}
				break;
			default:
				break;		
		}
	}else{
		alert("Please select items.");				
	}
	
	return false;
}
function isattach(i){
	if(i){
		$("#oboline").css('display','none');
		$("#onsaleline").css('display','none');
		$("#quantityline").css('display','none');
		$("#shipline").css('display','none');
		
		$("#overshipline").css('display','none');
		$("#isoversealine").css('display','none');
		
		$("#bulkuploadline").css('display','none');
		$("#uplines").css('display','');
		$("#bulkline").css('display','');
		$("#upload_sp").css('display','');
		$("#upstrus").css('display','');
		$("#uplistfile").css('display','');
		$(".noattachement").css('display','none');
		$("#deliveryMethod").attr("value","4"); 
		$("#postage").attr("value",""); 
		checkquanty(1);
		if($("input[@name='on_sale'][@checked]").val()!='no'){
			$("#cknow").attr('checked',true);
		}
	}else{
		$("#oboline").css('display','');
		$("#onsaleline").css('display','');
		$("#quantityline").css('display','');
		$("#shipline").css('display','');
		$("#bulkuploadline").css('display','');
		$("#isoversealine").css('display','');
		//$("#isoversea").attr('checked',false);
		$("#bulkline").css('display','none');
		$("#uplines").css('display','none');
		$("#upload_sp").css('display','none');
		$("#upstrus").css('display','none');
		$(".noattachement").css('display',''); 
		$("#uplistfile").css('display','none');
	}
}
function checkquanty(i){
	switch(i){
		case 1:
			document.mainForm.stockQuantity.disabled=false;
			document.mainForm.stockQuantity.value=1;
			document.mainForm.stockQuantity.removeAttribute('readOnly');
		break;
		case 2:
			document.mainForm.stockQuantity.disabled=true;
			document.mainForm.stockQuantity.value=0;
		break;
		case 3:
			document.mainForm.stockQuantity.disabled=true;
			document.mainForm.stockQuantity.value=0;
		break;
		case 4:
			document.mainForm.stockQuantity.disabled=false;
			document.mainForm.stockQuantity.value=1;
		break;
	}
}
function upfile(){
	window.open('/uploadImageSingle.php?cp=attachment','attachement',	'width=700,height=150,statusbars=yes,status=yes');
}
function deletefile(url){
	if(confirm('Are you sure you want to delete this file?')){
		$.get("/uploadImageSingle.php", { cp: "attachment", opt: "del",url: url },
 				function(data){
					if(data){
						$('#att_filename').val('');
						$('#att_fileurl').val('')
						$('#att_fopt').val(1);
						$('#uploadfiles').attr('innerHTML','');
						alert("Delete this file successfully.");
					}else{
						alert("Delete this file unsuccessfully.");
					}
				}); 
	}
}
function deletefile2(){
	if(confirm('Are you sure you want to delete this file?')){
		$('#att_filename').val('');
		$('#att_fileurl').val('');
		$('#att_fopt').val(1);
		$('#uploadfiles').attr('innerHTML','');
	}
}
function showoversea(obj){
	if(obj.checked){
		$("#overshipline").css('display','');
	}else{
		$("#overshipline").css('display','none');
	}
}

function content2AddItem(){
	$('#content2Html').append('<textarea name="extra[]" wrap="virtual" class="inputB" style="width:250px; height:40px; margin-bottom:2px;"></textarea><br/>');
	void(0);
}
function prev_tab(){
	switch($('#auct_celeb').val()){
		case '/skin/red/images/auctions/win_US_1.swf':
			window.open('/skin/red/images/auctions/prev_1.swf','_blank');
			break;
		case '/skin/red/images/auctions/win_colours.swf':
			window.open('/skin/red/images/auctions/prev_2.swf','_blank');
			break;
		case '/skin/red/images/auctions/win_AUS_1.swf':
			window.open('/skin/red/images/auctions/prev_3.swf','_blank');
			break;
	}
}

function enableCost(name,obj){
	if($(obj).attr('checked')){
		$('input[@name="'+name+'"]').attr('disabled',false);
	}else{
		$('input[@name="'+name+'"]').attr('disabled',true);
	}
}

$(function(){
	$("#disProducts").click(function(){
		$('#myproducts').css('display','none');
		$('#myauctions').css('display','none');
		$('#myproducts').css('display','block');
	});
	
	$("#disAuctions").click(function(){
		$('#myproducts').css('display','none');
		$('#myauctions').css('display','none');
		$('#myauctions').css('display','block');
	});
	$('#edit_shipping').hide();
	$('#customise_shipping').click(function() {
		$('#edit_shipping').toggle();
	});
});
</script>
<style>
	.tabtmp{
		list-style:none;
		margin:0;
		float:left;
	}
	.tabtmp li{
		list-style:none;
		width:200px;
		height:40px;
		line-height:40px;
		text-align:center;
		float:left;
		cursor:pointer;
		font-weight:bold;
	}
	.tabtmp li.active_tab{
		background-color:#9E99C1;
	}
</style>
{/literal}
{include_php file='include/jssppopup.php'}
	<style>
		{literal}
			#add_row {
				margin-left: 155px;
			}
			#shipping_info_box {
				background-color: #fccd5e;
				width: 195px;
				border-radius: 10px 10px 10px 10px;
				padding: 10px;
				margin-top: 10px;
				margin-bottom: 10px;
				overflow: hidden;
			}
			#shipping_info td {
				color: #000;
			}
			#customise_shipping {
				cursor: pointer;
				text-decoration: underline;
			}
		{/literal}
	</style>
<p align="center" class="txt"><font style="color:red;">{$req.msg}</font></p>
<form action="" name="mainForm" method="POST" id="uploadsomething" onsubmit="javascript:return checkForm(this);">
    
      
	<fieldset id="uploadproduct">

    <table width="100%" border="0" cellpadding="0" cellspacing="4">
		<tr {if $req.isopen neq 'yes'}style="display:none"{/if}>
          <td align="right">Downloadable Product</td>
          <td>
		  	<input name="isattachment" type="checkbox" id="isattachment" value="1" onclick="javascript:isattach(this.checked);" {if $req.select.isattachment}checked{/if}/>
            </td>
        </tr>
         <tr>
          <td align="right">Product Type</td>
          <td valign="top">
          {if $req.select.pid && $iscopy neq '1'}
          	<input type="radio" name="is_auction" value="no" disabled {if $req.select.is_auction neq 'yes'}checked{/if} onclick="showField(false)">&nbsp;Buy Now
          	<input type="radio" name="is_auction" value="yes" disabled {if $req.select.is_auction=='yes'}checked{/if}  onclick="showField(true)">&nbsp;Auction
          	{if $req.select.is_auction=='yes'}<input name="is_auction" type="hidden" value="yes"/>{/if}
          {else}
          	<input type="radio" name="is_auction" value="no" {if $req.select.is_auction neq 'yes'}checked{/if}  onclick="showField(false)">&nbsp;Buy Now
          	<input type="radio" name="is_auction" value="yes"  {if $req.select.is_auction=='yes'}checked{/if}  onclick="showField(true)">&nbsp;Auction
          {/if}
          </td>
        </tr>
		<tr id="certifiedline" style="display:{if $req.select.is_auction!='yes'}none{else}{/if}">
		  <td height="23" align="right">Certified Bidders Only</td>
		  <td>
              {if $iscopy eq '1'}
              <input type="checkbox" name="is_certified" value="1" {if $req.select.is_certified}checked{/if}/>
              {else}
              <input type="checkbox" name="is_certified" value="1" {if $req.select.is_certified}checked{/if}{if $req.select.pid} disabled{/if}/>
              {if $req.select.is_certified}
              <input name="is_certified" type="hidden" value="1"/>
              {/if}
              {/if}
			  <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">If you tick this box, you will need to approve all bidders before they can bid on your item.</span></span></a></span></font></span>
          </td>
        </tr>
        <tr>
          <td align="right">Item Name*</td>
          <td>
		  	<input name="item_name" type="text" class="text" id="item_name" size="30" value="{$req.select.item_name}"/>
            <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><span><span>These products will be available to registered customers to 'add to basket' and buy online. Enter the name of the product Eg Granny Smith Apples.</span></span></a></span></font></span></td>
        </tr>
       
        <tr id="noauction" style="display:{if $req.select.is_auction == 'yes'}none{else}{/if}">
          <td align="right">Price*</td>
          <td valign="top">
          	<table>
          		<tr>
          			<td>$</td>
          			<td><input name="price" type="text" class="price" id="price" value="{$req.select.price}" size="11" maxlength="12"/></td>
          			<td><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Eg 12000.00<br />In the price field, enter decimal point only.</span></span></a></span></font></span></td>
          			<td>Unit</td>
          			<td><input name="unit" type="text" class="price" maxlength="20" id="unit" size="5" value="{$req.select.unit}"/></td>
          			<td><span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">eg per kg, lbs, each, for 500g, etc.</span></span></a></span></font></span></td>
          		</tr>
          	</table>
            
          </td>
        </tr>
        <tr id="auction" style="display:{if $req.select.is_auction == 'yes'}{else}none{/if}">
          <td  align="right">Auction Starting Price*</td>
          <td valign="top">
          	<table>
          		<tr>
          			<td>$</td>
          			<td width="85"><input name="initial_price" type="text" class="price" id="initial_price" value="{if $req.select.initial_price}{$req.select.initial_price|number_format:0:'.':''}{/if}"  maxlength="9"/>&nbsp;<span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Starting price has to be a whole number without decimals.</span></span></a></span></font></span>
					</td>
          			<td>Auction Reserve</td>
          			<td><table>
          		<tr>
          		<td>$</td>
          		<td><input name="reserve_price" type="text" class="price" id="reserve_price" value="{if $req.select.reserve_price}{$req.select.reserve_price|number_format:0:'.':''}{/if}"  maxlength="9"/></td>
          		</tr>
          	</table></td>
          		</tr>
          	</table>
          </td>
        </tr>
		
		<tr id="auctionstarttime" style="display:{if $req.select.is_auction == 'yes'}{else}none{/if}">
          <td align="right">Start Time*</td>
          <td valign="top">
          	<table cellpadding="0" cellspacing="0">
          		<tr>
          			<td><input name="start_date" id="start_date" type="text" class="inputB date" style="width:66px;" size="11" readonly="readonly" maxlength="11"/>
          			  <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.mainForm.start_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a> &nbsp;
			<select name="start_hour" class="inputB time" id="start_hour">
			{foreach from=$arr_hour item=h }
				<option value="{$h}">{$h}</option>
			{/foreach}
			</select>&nbsp;:&nbsp;
			<select name="start_minute" class="inputB time" id="start_minute">
			{foreach from=$arr_min item=m }
				<option value="{$m}">{$m}</option>
			{/foreach}
			</select></td>
          		<td style="padding-top:5px;padding-left:5px;">
          		<span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" border="0" align="top" /><span><span style="color:#777;min-height:40px;_height:40px;">Eastern Standard Time</span></span></a></span></font></span></td>
          		</tr>
          	</table>
		  </td>
        </tr>
		
        <tr id="auctiontime" style="display:{if $req.select.is_auction == 'yes'}{else}none{/if}">
          <td align="right">End Time*</td>
          <td valign="top">
          	<table cellpadding="0" cellspacing="0">
          		<tr>
          			<td> <input name="end_date" type="text" class="inputB date" id="end_date" style="width:66px;" value="{$req.select.end_date}" size="11" readonly="readonly" maxlength="11"/>
            <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.mainForm.end_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a> &nbsp;
			<select name="end_hour" class="inputB time">
			{$req.select.hour_option}
			</select>&nbsp;:&nbsp;
			<select name="end_minute" class="inputB time">
			{$req.select.minute_option}
			</select></td>
          		<td style="padding-top:5px;padding-left:5px;">
          		<span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" border="0" align="top" /><span><span style="color:#777;min-height:40px;_height:40px;">Eastern Standard Time</span></span></a></span></font></span></td>
          		</tr>
          	</table>
           
			
			
			</td>
        </tr>
		
        <tr >
		  <td height="10" colspan="2" align="left"><table width="98%" >
              <tr>
                <td bgcolor="#293694"  height="2"></td>
              </tr>
          </table></td>
	  </tr>
		<tr id="oboline" style="display:{if $req.select.is_auction == 'yes'}none{else}{/if}">
		  <td height="23" align="right">OBO</td>
		  <td><input type="checkbox" name="non" value="1" {if $req.select.non}checked{/if}/> Activate</td>
	  </tr>
		<tr id="auction3" style="display:{if $req.select.is_auction == 'yes'}none{else}{/if}">
          <td align="right">On Sale </td>
		  <td style="line-height:22px;"><input id="cknow" name="on_sale" type="radio" value="yes" checked="checked" onclick="checkquanty(1)" />
		    <span style="color:red;">Now</span>
		      <input type="radio" name="on_sale" value="no" {if $req.select.on_sale == 'no'}checked="checked"{/if}  onclick="checkquanty(2)" />
	        <span style="color:red;">Soon</span>
	        <span id="onsaleline"><input type="radio" name="on_sale" value="sold" {if $req.select.on_sale == 'sold'}checked="checked"{/if}  onclick="checkquanty(3)" /> 
	        <span style="color:red;">Sold</span>
	        <input type="radio" name="on_sale" value="contact" {if $req.select.on_sale == 'contact'}checked="checked"{/if}  onclick="checkquanty(4)" /> 
	        <span style="color:red;">Contact</span></span>
</td>
	  </tr>
		<tr id="quantityline" style="display:{if $req.select.is_auction == 'yes'}none{else}{/if}">
          <td align="right">Stock Quantity </td>
		  <td style="line-height:22px; width:295px; color:red; vertical-align:middle;">
		  	<input name="stockQuantity" type="text" class="price" style="border:1px red solid;" id="stockQuantity" size="30" value="{if $req.select.stockQuantity neq ""}{$req.select.stockQuantity}{else}1{/if}" {if $req.select.on_sale ne '' and $req.select.on_sale != 'yes' and $req.select.on_sale != 'contact'}disabled{/if} />&nbsp;<font style="font-size:11px;color:red;">You must enter a quantity. Zero equals Sold.</font></td>
	  </tr>
		<tr valign="middle">
		<tr valign="top" style=" display:none">
          <td align="right">{$lang.labelPayments}</td>
          <td style="line-height:22px; width:295px;">
        	<span><input type="checkbox" value="3" class="checkbox" name="payments[]" checked="checked"></span>
            </td>
        </tr>
        <tr>
          <td align="right">Category*</td>
          <td>
		  <select name="category" class="text" id="category" onchange="selectSubCG('categoryDiv','category');">
		  <option value="">Select a Category</option>
		  {foreach from=$req.select.categoryList item=cg}
		  <option value="{$cg.id}" {if $cg.id eq $req.select.category}selected{/if}>{$cg.name}</option>
		  {/foreach}
		  </select>
		  <input name="categorySub" type="hidden" id="categorySub" value="{$req.select.categorySub}" />		  </td>
        </tr>
        <tr valign="top">
		  <td>&nbsp;</td>
          <td height="24"  id="categoryDiv">
            <select name="categorySubList" class="text" id="categorySubList">
            </select>		</td>
        </tr>
        
        {if $req.template.bannerImg neq ''}      
        <tr>
          <td align="right">Customisable Subcategory</td>
          <td>
		  	<input name="custom_subcat" type="text" class="text" id="custom_subcat" size="30" value="{$req.select.custom_subcat}"/>
            <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><span><span>These products will be available to registered customers to 'add to basket' and buy online. Enter the name of the product Eg Granny Smith Apples.</span></span></a></span></font></span></td>
        </tr>
       
        {/if}
        
        <tr valign="top">
          <td align="right" valign="top" style="vertical-align:top;">Description*<br /></td>
          <td><textarea name="description" class="inputB"  style="height:220px; width:250px;">{$req.select.description}</textarea>
          </td>
        </tr>

		<tr valign="top" id="shipline" >
          <td align="right" valign="top" style="vertical-align:top;">Shipping Method* (Normal)</td>
          <td>
		  <div id="shipping_options">
		  {$req.select.deliveryMethod}
		  </div>
		  <br />
		  <a id="customise_shipping">[Customise]</a>
		  </td>
        </tr>
		
		<tr id="edit_shipping">
			<td></td>
			<td>
				<div id="shipping_info_box">
				<table id="shipping_info">
				</table>
				<input id="add_row" type="button" name="add_row" value="Add" />
				<input id="save_shipping" type="button" value="Save" />
				<script>
					{literal}
						$(document).ready(function() {
							var id_key = 0;
							function add_row(id, name, value) {
								var row = '<tr id="row_' + id + '">' +
												'<td>' +
												'	<input type="checkbox" name="delivery_option[' + id + ']" value="' + id + '" class="shipping_option" checked="checked"/>' +
												'</td>' +
												'<td>' +
												'	<input type="text" class="delivery_text" name="bu_delivery_text[' + id + ']" style="width:100px;" value="' + name + '" />' +
												'</td>' +
												'<td>' +
												'	$<input type="text" class="delivery_value" name="bu_delivery[' + id + ']" style="width:50px;" value="' + value + '" />' +
												'</td>' +
											'</tr>';
								$('#shipping_info').append(row);
								$('#row_' + id + ' .shipping_option').click(function() {
									if (!$(this).is(':checked')) {
										$(this).parent().parent().remove();
									}
								});
							}
							$('#add_row').click(function() {
								id_key++;
								add_row(id_key,'',0);
							});
							$('#save_shipping').click(function() {
								var rows = new Object();
								$('#shipping_info > tbody > tr').each(function() {
									rows['delivery_text[' + $(this).attr('id') + ']'] = $(this).find('.delivery_text').val();
									rows['delivery_value[' + $(this).attr('id') + ']'] = $(this).find('.delivery_value').val();
								});
								
								$.ajax({
									type: 'POST',
									url: 'ajax_requests.php?action=6',
									data: rows,
									success: function(data) {
										//alert(data);
										$('#shipping_options').html(data);
									}
								});
							});
						{/literal}
						{foreach from=$delivery_items item=d key=key}
							id_key = {$key};
							add_row({$key},"{$d|escape:'html'}",{if $delivery_values[$key]}{$delivery_values[$key]}{else}0{/if});
						{/foreach}
						{literal}
						});
						{/literal}
				</script>
				</div>
			</td>
		</tr>
        
        <tr id="isoversealine" >
        <td align="right">Allow Shipping Overseas</td>
        <td><input type="checkbox" name="isoversea" value="1" {if $req.select.isoversea eq '1'}checked{/if} onclick="showoversea(this)" id="isoversea"/></td>
        </tr>
        
		<tr valign="top" id="overshipline" {if $req.select.isoversea neq '1'}style="display:none"{/if}>
          <td align="right" style="vertical-align:top;">Shipping Method* (Overseas)</td>
          <td>
		  {$req.select.oversea_deliveryMethod}
         </td>
        </tr>
        
        
        <tr id="extraline" style="display:{if $req.select.is_auction == 'yes'}{else}none{/if}">
        	<td align="right" valign="top" style="vertical-align:top;">Extra Infomation</td>
        	 <td valign="top"><samp id="content2Html" style="margin-bottom:6px;">
			{foreach from=$req.select.extra item=l key=k}
			<textarea name="extra[]" cols="80" rows="8" wrap="virtual" class="inputB" style="width:250px; height:40px;margin-bottom:2px;">{$l}</textarea><br />
			{foreachelse}
			<textarea name="extra[]" cols="80" rows="8" wrap="virtual" class="inputB" style="width:250px; height:40px;margin-bottom:2px;"></textarea><br />
			{/foreach}</samp>
			<span style=" clear:both; margin-top:5px;">&nbsp;&nbsp;
			<a href="javascript:content2AddItem();">Add Extra Information</a><br /><br />
			</span>
			</td>
        </tr>
        
        <tr valign="top">
          <td align="right">Product Code</td>
          <td><input name="p_code" type="text" class="text" id="p_code" size="25" value="{$req.select.p_code}"/>
              <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">This code is for your reference for accounting. Leave blank if not necessary.</span></span></a></span></font></span></td>
        </tr>
        
        <tr valign="top" id="auction_celebration" style="display:{if $req.select.is_auction neq 'yes'}none{/if}">
        
        	<td align="right">Auction Celebration</td>
        	<td>
        	<select id="auct_celeb" name="auct_celeb" class="inputB" style="width:200px;float:left;">
        		<option value="/skin/red/images/auctions/win_US_1.swf" {if $req.select.auct_celeb eq "/skin/red/images/auctions/win_US_1.swf"}selected{/if}>Sold Animation 1</option>
        		<option value="/skin/red/images/auctions/win_colours.swf" {if $req.select.auct_celeb eq "/skin/red/images/auctions/win_colours.swf"}selected{/if}>Sold Animation 2</option>
        		<option value="/skin/red/images/auctions/win_AUS_1.swf" {if $req.select.auct_celeb eq "/skin/red/images/auctions/win_AUS_1.swf"}selected{/if}>Sold Animation 3</option>
        	</select>
        	<img src="/skin/red/images/icon-finds.gif" onclick="prev_tab()"  style="cursor:pointer;padding-top:5px;*padding-top:0;float:left;" />
        	</td>
        </tr>
        
      <tr valign="top">
		  <td height="23" align="right" style="font-weight:bold;">Display on your homepage</td>
		  <td><input type="checkbox" name="isfeatured" value="1" {if $req.select.isfeatured}checked{/if}/> </td>
	  </tr>
      <tr valign="top" id="youtubeline" {if $req.select.is_auction=='yes'}style="display:none"{/if}>
      	  <td height="23" align="right">Youtube Video</td>
          <td><textarea class="text" name="youtubevideo" style="height:30px">{$req.select.youtubevideo}</textarea>&nbsp;<span class="style11"><a  href="/soc.php?cp=youtubeinstruction" target="_blank"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /></a></span></td>
      </tr>
      <tr valign="top">
      	<td height="23" align="right">Tags</td>
          <td valign="middle"><input class="inputB" name="str_tags" style="width:228px;"  value="{$req.select.str_tags}"/>
          	  <span class="style11"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Please use a comma as the separator.<br/>
Please note the maximum number of tags is 5.</span></span></a></span></font></span>
          </td>
      </tr>
      <tr>
      <td>&nbsp;</td>
      <td>
      <p id="auction_notes" style="{if $req.select.is_auction neq 'yes'}display:none;{/if}margin:0; padding-left:5px; font-family:Arial; font-size:14px; font-weight:bold; "><span style="font-size:16px; font-weight:bold;">Note:</span> Once a bid has been made, no settings can be changed in this auction.</p>
			 <br />
		    <input name="SubmitPic" type="image" src="/skin/red/images/bu-savetowebsite.gif" class="input-none-border" onclick="javascript:document.mainForm.cp.value='edit';" value="Save to My Website" border="0"/>
		    <p style="margin:0; padding-left:5px; font-family:Arial; font-size:16px; font-weight:bold; color:red;">Click after you update every item</p>
            <br />
      </td>
      </tr>
	  <tr id="upload_sp" style="display:none">
		  <td height="10" colspan="2" align="left"><table width="98%" >
              <tr>
                <td bgcolor="#293694"  height="2"></td>
              </tr>
          </table></td>
	  </tr>
	   <tr valign="top" id="uplines" style="display:none;">
	   	<td colspan="2"><strong style="font-size:14px">&nbsp;Downloadable File</strong>
			<table width="98%">
			 <tr>
		   	  <td height="23" style="width:93px;" align="right" valign="top">Choose File</td>
			  <td style="padding-left:10px;"><a href="javascript:upfile();void(0);"><img src="/skin/red/images/upload.gif"/></a></td>
			 </tr>
			</table>
		</td>
		  <input type="hidden" name="filename" id="att_filename" value="{$req.upfiles.filename}"/>
		  <input type="hidden" name="fileurl" id="att_fileurl" value="{$req.upfiles.fileurl}"/>
		  <input type="hidden" name="filenewname" id="att_filenewname" value="{$req.upfiles.filenewname}"/>
	   	  <input type="hidden" name="fopt" id="att_fopt" value="0"/>
	  </tr>
	  <tr id="uplistfile" style="display:none"><td></td><td style="padding-left:10px;" id="uploadfiles">{if $req.upfiles.filename neq ""}<a href="{$req.upfiles.fileurl}">{$req.upfiles.filename}</a>&nbsp;|&nbsp;<a href="javascript:deletefile2();void(0);"><img align="absmiddle" title="Delete" alt="Delete" src="/skin/red/images/icon-deletes.gif"/></a>{/if}</td></tr>
	  <tr id="upstrus" style="display:none"><td></td><td style="font-size:11px; color:#FF0000;">The file can only be downloaded once and must be downloaded within 24 hours.
</td></tr>
    </table>
	</fieldset>
      
    <fieldset id="uploadimages" style=" padding-left:29px;display:block;">
		<script src="/skin/red/js/uploadImages.js" language="javascript"></script>
		<div style="+width:320px;">
<table width="225">
        <tr valign="top">
          <td colspan="3"><span class="lbl">
		  <a id="swf_upload_1" style="float:left;" href="javascript:uploadImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" /></a>&nbsp;&nbsp;| <a href="javascript:deleteImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a>
          </span><span class="style11"><font face="Verdana" size="1"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;width:110px;">Click on the 'upload an image' button, in the pop-up window click 'browse' and go to the location on your computer where the image is saved, then 'upload'.</span></span></a></font></span></td>
        </tr>
		
		<tr><td colspan="3">
		<table width="250" cellpadding="0" cellspacing="0" border="0">
			<tr>
          		<td height="225" colspan="3" align="center"><img src="{$req.images.mainImage.0.sname.text}" name="mainImage_dis" border="1" id="mainImage_dis" width="250" height="250" /></td>
        	</tr>
			<tr><td colspan="3" height="10"><img src="images/spacer.gif" width="1" height="1" /></td>
			<tr onmousemove="//displayUploadInterface('uploadImagesSub1',true)">
				<td align="center"><img src="{$req.images.subImage.0.sname.text}" width="79" height="79" name="subImage0_dis" border="1" id="subImage0_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.1.sname.text}" width="79" height="79" name="subImage1_dis" border="1" id="subImage1_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.2.sname.text}" width="79" height="79" name="subImage2_dis" border="1" id="subImage2_dis" /></td>
			</tr>
			<tr id="uploadImagesSub1" onmouseout="//displayUploadInterface('uploadImagesSub1',false)">
				<td align="center"><a id="swf_upload_2" href="javascript:uploadImage(0, 1, 0, 'subImage0' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 0, 'subImage0' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_3" href="javascript:uploadImage(0, 1, 1, 'subImage1' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 1, 'subImage1' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_4" href="javascript:uploadImage(0, 1, 2, 'subImage2' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 2, 'subImage2' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
			</tr>
			<tr><td colspan="3" height="10"><img src="images/spacer.gif" width="1" height="1" /></td>
			</tr>
			<tr onmousemove="//displayUploadInterface('uploadImagesSub2',true);">
				<td align="center"><img src="{$req.images.subImage.3.sname.text}" width="79" height="79" name="subImage3_dis" border="1" id="subImage3_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.4.sname.text}" width="79" height="79" name="subImage4_dis" border="1" id="subImage4_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.5.sname.text}" width="79" height="79" name="subImage5_dis" border="1" id="subImage5_dis" /></td>
			</tr>
			<tr id="uploadImagesSub2" onmouseout="//displayUploadInterface('uploadImagesSub2',false);">
				<td align="center"><a id="swf_upload_5" href="javascript:uploadImage(0, 1, 3, 'subImage3' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 3, 'subImage3' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_6" href="javascript:uploadImage(0, 1, 4, 'subImage4' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 4, 'subImage4' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_7" href="javascript:uploadImage(0, 1, 5, 'subImage5' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 5, 'subImage5' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
			</tr>
		</table></td>
		</tr>
        <tr valign="top">
          <td valign="middle" colspan="3">
          <table width="250" height="35" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="258" height="35">
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
					
			<input name="mainImage_svalue" id="mainImage_svalue" type="hidden" value="{$req.images.mainImage.0.sname.text}"/>
			<input name="mainImage_bvalue" id="mainImage_bvalue" type="hidden" value="{$req.images.mainImage.0.bname.text}"/>
			
			<input name="subImage0_svalue" id="subImage0_svalue" type="hidden" value="{$req.images.subImage.0.sname.text}"/>
			<input name="subImage0_bvalue" id="subImage0_bvalue" type="hidden" value="{$req.images.subImage.0.bname.text}"/>
			
			<input name="subImage1_svalue" id="subImage1_svalue" type="hidden" value="{$req.images.subImage.1.sname.text}"/>
			<input name="subImage1_bvalue" id="subImage1_bvalue" type="hidden" value="{$req.images.subImage.1.bname.text}"/>
			
			<input name="subImage2_svalue" id="subImage2_svalue" type="hidden" value="{$req.images.subImage.2.sname.text}"/>
			<input name="subImage2_bvalue" id="subImage2_bvalue" type="hidden" value="{$req.images.subImage.2.bname.text}"/>
			
			<input name="subImage3_svalue" id="subImage3_svalue" type="hidden" value="{$req.images.subImage.3.sname.text}"/>
			<input name="subImage3_bvalue" id="subImage3_bvalue" type="hidden" value="{$req.images.subImage.3.bname.text}"/>
			
			<input name="subImage4_svalue" id="subImage4_svalue" type="hidden" value="{$req.images.subImage.4.sname.text}"/>
			<input name="subImage4_bvalue" id="subImage4_bvalue" type="hidden" value="{$req.images.subImage.4.bname.text}"/>
			
			<input name="subImage5_svalue" id="subImage5_svalue" type="hidden" value="{$req.images.subImage.5.sname.text}"/>
			<input name="subImage5_bvalue" id="subImage5_bvalue" type="hidden" value="{$req.images.subImage.5.bname.text}"/>
			<input type="hidden" name="cp" id="cp" value="edit" />
			
				</td>
              </tr>
          </table></td>
        </tr>
        <tr valign="top" id="gallaryImage">
          <td colspan="3">&nbsp;</td>
        </tr>
		</table>
		</div>
  </fieldset>
</form>
      
         <fieldset id="bulkupload">
     <table style="width:100%;">
		  <tr id="bulkuploadline">
			 <td colspan="2">
			 <table width="100%" border="0" cellspacing="10" cellpadding="0">
			 <form action="" method="post" enctype="multipart/form-data" name="bulk" id="bulk" onsubmit="return checkImport(this)">
			 	<input name="cp" type="hidden" value="upload"/>
				<tr><td height="2" colspan="2" bgcolor="#293694"></td>
			 </tr>
			  <tr>
			    <td width="100%" colspan="2" style="padding-left:160px;" ><h3 style="font-size:16px; color:#666; font-weight:bold;">Bulk Product Import</h3><div style="margin-left:-50px;"><label for="rdo_import_type_buynow" onclick="switchSample('show')"><input type="radio" id="rdo_import_type_buynow" name="rdo_import_type" value="buynow" checked="checked" />Buy Now</label><label for="rdo_import_type_ebay_turbolister" onclick="switchSample('hide')"><input type="radio" name="rdo_import_type" value="ebay_turbolister" id="rdo_import_type_ebay_turbolister" />Import Turbo Lister export file</label></div></td>
			  </tr>
			  <tr>
			    <td align="right" width="22%">Products Information </td>
			    <td width="78%">
                  <table cellpadding="0" cellspacing="0">
                	<tr>
                    	<td><input name="csv" type="file" id="csv" class="inputB"  style="width:200px;" /></td>
                        <td>
			      		<label id="upload_sample_content">&nbsp;<a href="#" target="_blank" id="down_sample_csv">Sample Product CSV</a><br/>
                  		&nbsp;&nbsp;<a href="/pdf/images_buy&sell.zip" target="_blank">Sample ZIP file of Product Images</a></label>
                  		</td></tr></table>
                  </td>
				  
			  </tr>
			  <tr>
			    <td align="right">Products Images </td>
			    <td><input name="image" type="file" id="image" class="inputB" style="width:200px;" />
			      &nbsp; <a href="/soc.php?cp=bulkinstruction" target="_blank">Setup Process</a> </td>
			  </tr>
              <tr><td></td>
              	  <td><div id="csvmsg" style="color:red;"></div><input type="hidden" id="swf_csvmsg" name="swf_csv" value=""/>
                  <div id="imgmsg" style="color:red"></div><input type="hidden" id="swf_imgmsg" name="swf_img" value=""/></td></tr>
			  <tr>
			  	<td colspan="2" style="color:#FF0000; margin:0; padding-left:100px">Please keep all file uploads to a maximum of 70MB in size.</td>
			  </tr>
			  <tr>
			    <td align="right">&nbsp;</td>
			    <td><input class="submit" type="image" src="/skin/red/images/import.gif" name="Submit" value="Import Products" /></td>
			  </tr>
			  <tr><td height="2" colspan="2" bgcolor="#293694"></td>
			 </tr>
			  </form>
			</table></td>
		 </tr>
		 <tr id="bulkline" style="display:none;"><td colspan="2"><table style="width:100%; padding:0 8px;"><tr><td height="2" colspan="2" bgcolor="#293694"></td></tr></table></td></tr>
		 <tr>
		 <td colspan="2" align="right" height="30">
		 {if $smarty.session.attribute != 0}
		 <input class="submit" type="image" src="/skin/red/images/bu-next.gif" value="Continue to Next Step" onclick="document.mainForm.cp.value='next'; if (continueNextStep()) document.mainForm.submit(); void(0);" />
        <!--<input class="submit" type="image" src="/skin/red/images/bu-exit.gif" value="Save And Exit"  onclick="document.mainForm.cp.value='save'; if (continueNextStep()) document.mainForm.submit(); void(0);" />-->
		{/if}
		</td>
		 </tr>   
      </table>
      </fieldset>
        <form action="" method="post" onsubmit="return false;" id="multform" />
		<h3 style="font-size:16px; color:#666; font-weight:bold;">Your Products
      	<span style="text-align:right; vertical-align:middle; color:red; float:right; padding-right:20px;padding-top:10px; position: relative; bottom:10px;">Edited items will be displayed first in their category.</span>
      	</h3>
		<table cellpadding="0" cellspacing="0" width="98%" style="margin-bottom:10px;">
		<colgroup>
		<col width="80%" />
		<col width="20%" />
		</colgroup>
		<tr>
		<td></td>
		<td align="right"><input type="submit" name="multAct" onclick="multcheckform(this);" value="Delete"/><!--<input type="submit" name="multAct" onclick="multcheckform(this);" value="Publish"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Unpublish"/>--></td>
		<input type="hidden" value="" id="multcp" name="multcp"/>
		</tr>
		</table>
        <a name="list"></a>
        <table cellpadding="0" cellspacing="0" width="735" id="myproducts" class="sortable" style="display:{if $req.displayType eq 'sale'}block{else}none{/if}">
        <colgroup>
		<col width="2%"/>
        <col width="2%" />
        <col width="10%" />
        <col width="22%" />
        <col width="10%" />
        <col width="8%" />
        <col width="11%" />
        <col width="10%" />
        <col width="10%" />
        <col width="33%" />
        </colgroup>
        <thead>
        <tr>
        	<td colspan="10">
            <div style="background-color: rgb(238, 238, 238); width:400px; height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
 	<ul class="tabtmp">
    	<li class="active_tab" style="font-weight:bold;color:#FFF;text-decoration:none;">My Products</a></li>
        <li id="disAuctions" style="width:200px;" onclick="javascript:changeList('myproducts');">My Auctions</li>
    </ul>
    <div style="clear: both;"></div>
            </div>
			</td>
        </tr>
        <tr>
			<th class="unsortable"><input type="checkbox" id="allcheck" onclick="checkallitem('buy')" value="1" /></th>
        	<th class="unsortable">&nbsp;</th>
        	<th class="unsortable">Item</th>
        	<th>Name</th>
        	<th>Code</th>
        	<th>Onsale Status</th>
        	<th>Featured</th>
        	<th>
        		{if $req.select.sortby == 'price_asc'}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=price_desc">Price</a><img src="/skin/red/images/arrow-down.gif" border="0" />
        		{elseif $req.select.sortby == 'price_desc'}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=price_asc">Price</a><img src="/skin/red/images/arrow-up.gif" border="0" />
        		{else}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=price_asc">Price</a>
        		{/if}
        	</th>
        	<th>
        		{if $req.select.sortby == 'date_asc'}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=date_desc">Date Added</a><img src="/skin/red/images/arrow-down.gif" border="0" />
        		{elseif $req.select.sortby == 'date_desc'}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=date_asc">Date Added</a><img src="/skin/red/images/arrow-up.gif" border="0" />
        		{else}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=date_asc">Date Added</a>
        		{/if}        	
        	</th>
        	<th class="unsortable">&nbsp;</th>
          
        </tr>
        </thead>
       	
       	<tbody>
        {if $req.productHas}
        {foreach from=$req.product item=p}
        {if $p.is_auction eq 'no'}
        <tr>
		<td> {if $p.status eq 0 || $p.status eq 2}<input type="checkbox" value="{$p.pid}"  name="ckpid[]" class="b4js" />{/if}</td>
        <td>{if $p.pid==$req.select.pid}<img src="/skin/red/images/arrow.gif" border="0" />{else}<img src="/skin/red/images/spacer.gif" border="0" />{/if}</td>
        <td><img src="{$p.simage.text}" width="61" height="35" /></td>
        <td> {$p.item_name|truncate:30:"..."}</td>
        <td> {$p.p_code}</td>
        <td> {if $p.is_auction == 'yes'}
        		{if $p.end_stamp>0}
        			Active
        		{else}
        			{if $p.cur_price>=$p.reserve_price}
        				{if $p.winner_id neq "0"}
        				Sold
        				{else}
        				Not Sold
        				{/if}
        				{else}Not Sold{/if}
        		{/if}
        	{else}
        		{if $p.on_sale == 'sold'}Sold{else}Active{/if}
        	{/if}
        </td>
		<td><input type="checkbox" {if $p.isfeatured}checked{/if} disabled /></td>
		<td><strong>{if $p.is_auction == 'yes'}${$p.cur_price}{else}${$p.price}{/if}</strong></td>
		<td>{$p.dateadd}</td>
        <td>
        	<ul id="icons-products" style="margin:0 0 0 15px;">

            {if $p.status eq 0}
            <li><a href="#" onclick="javascript:document.location.href ='soc.php?act=signon{if $hide_template}&iframe=1{/if}&step={$req.select.step}&pid={$p.pid}';" title="{$lang.but.edit}"><img src="/skin/red/images/icon-edits.gif" /></a></li>
            <li><a href="#" onclick="javascript:deletes('soc.php?act=signon{if $hide_template}&iframe=1{/if}&step={$req.select.step}&cp=del&pid={$p.pid}');" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
            <li><a href="soc.php?cp=dispro{if $hide_template}&iframe=1{/if}&StoreID={$req.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
            {elseif $p.status eq 2}
            <li><a href="#" onclick="javascript:deletes('soc.php?act=signon{if $hide_template}&iframe=1{/if}&step={$req.select.step}&cp=del&pid={$p.pid}');" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
            <li><a href="soc.php?cp=dispro{if $hide_template}&iframe=1{/if}&StoreID={$req.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
            {else}
             <li><a href="soc.php?cp=dispro{if $hide_template}&iframe=1{/if}&StoreID={$req.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
            {/if}
            <li><a href="/soc.php?act=signon{if $hide_template}&iframe=1{/if}&step={$req.select.step}&pid={$p.pid}&ap=copy" title="Copy"><img src="/skin/red/images/icon-copy.gif"/></a></li>
            </ul>
            </td>
			
        </tr>
        {/if}
        {/foreach}
        {/if}
        </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" width="98%" id="myauctions" class="sortable" style="display:{if $req.displayType eq 'auction'}block{else}none{/if};">
        <colgroup>
		<col width="2%"/>
        <col width="2%" />
        <col width="10%" />
        <col width="22%" />
        <col width="10%" />
        <col width="10%" />
        <col width="8%" />
        <col width="13%" />
        <col width="8%" />
        <col width="35%" />
        </colgroup>
        <thead>
        <tr>
        	<td colspan="10">
            <div style="background-color: rgb(238, 238, 238); width:400px; height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
 	<ul class="tabtmp">
    	<li id="disProducts" onclick="javascript:changeList('myauctions');">My Products</li>
        <li class="active_tab" style="width:200px;font-weight:bold;text-decoration:none;color:#FFF;">My Auctions</a></li>
    </ul>
    <div style="clear: both;"></div>
            </div>
			</td>
        </tr>
        <tr>
			<th class="unsortable"><input type="checkbox" id="allchecka" onclick="checkallitem('auction')" value="1" /></th>
        	<th class="unsortable">&nbsp;</th>
        	<th class="unsortable">Item</th>
        	<th>Name</th>
        	<th>Code</th>
        	<th>Onsale Status</th>
        	<th>Featured</th>
        	<th>
        		{if $req.select.sortby == 'price_asc'}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=price_desc">Price</a><img src="/skin/red/images/arrow-down.gif" border="0" />
        		{elseif $req.select.sortby == 'price_desc'}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=price_asc">Price</a><img src="/skin/red/images/arrow-up.gif" border="0" />
        		{else}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=price_asc">Price</a>
        		{/if}
        	</th>
        	<th>
        		{if $req.select.sortby == 'date_asc'}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=time_desc">End Date</a><img src="/skin/red/images/arrow-down.gif" border="0" />
        		{elseif $req.select.sortby == 'date_desc'}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=time_asc">End Date</a><img src="/skin/red/images/arrow-up.gif" border="0" />
        		{else}
        			<a href="soc.php?act=signon&step=4{if $hide_template}&iframe=1{/if}&sortby=time_asc">End Date</a>
        		{/if}        	
        	</th>
        	<th class="unsortable">&nbsp;</th>
          
        </tr>
        </thead>
       	
       	<tbody>
        {if $req.auctionHas}
        {foreach from=$req.auction item=p}
        {if $p.is_auction eq 'yes'}
        <tr>
		<td> {if $p.status eq 0 || $p.status eq 2}{if $p.true_end_stamp > $req.auctionDeleteTime and $p.cur_price>=$p.reserve_price and $p.winner_id neq "0"}{else}<input type="checkbox" value="{$p.pid}"  name="ckpid[]" class="a4js" />{/if}{/if}</td>
        <td>{if $p.pid==$req.select.pid}<img src="/skin/red/images/arrow.gif" border="0" />{else}<img src="/skin/red/images/spacer.gif" border="0" />{/if}</td>
        <td><img src="{$p.simage.text}" width="61" height="35" /></td>
        <td> {$p.item_name|truncate:30:"..."}</td>
        <td> {$p.p_code}</td>
        <td> {if $p.is_auction == 'yes'}
        		{if $p.end_stamp>0 and $smarty.now > $p.starttime_stamp}
        			Active
        		{elseif $smarty.now <= $p.starttime_stamp}
					Not Started
				{else}
        			{if $p.cur_price>=$p.reserve_price}
        				{if $p.winner_id neq "0"}
        				Sold
        				{else}
        				Not Sold
        				{/if}
        				{else}Not Sold{/if}
        		{/if}
        	{else}
        		{if $p.on_sale == 'sold'}Sold{else}Active{/if}
        	{/if}
        </td>
		<td><input type="checkbox" {if $p.isfeatured}checked{/if} disabled /></td>
		<td><strong>{if $p.is_auction == 'yes'}${$p.cur_price}{else}${$p.price}{/if}</strong></td>
		<td>{$p.endDate}</td>
        <td>
        	<ul id="icons-products" style="margin:0 0 0 15px;">

            {if $p.status eq 0}
            <li><a href="#" onclick="javascript:document.location.href ='soc.php?act=signon{if $hide_template}&iframe=1{/if}&step={$req.select.step}&pid={$p.pid}';" title="{$lang.but.edit}"><img src="/skin/red/images/icon-edits.gif" /></a></li>
            <li><a href="#" onclick="javascript:deletes('soc.php?act=signon{if $hide_template}&iframe=1{/if}&step={$req.select.step}&cp=del&pid={$p.pid}');" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
            <li><a href="soc.php?cp=dispro{if $hide_template}&iframe=1{/if}&StoreID={$req.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
            {elseif $p.status eq 2}
            	{if $p.true_end_stamp > $req.auctionDeleteTime and $p.cur_price>=$p.reserve_price and $p.winner_id neq "0"}{else}
            <li><a href="#" onclick="javascript:deletes('soc.php?act=signon{if $hide_template}&iframe=1{/if}&step={$req.select.step}&cp=del&pid={$p.pid}');" title="{$lang.but.delete}"><img src="/skin/red/images/icon-deletes.gif" /></a></li>
            	{/if}
            <li><a href="soc.php?cp=dispro{if $hide_template}&iframe=1{/if}&StoreID={$req.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
            {else}
             <li><a href="soc.php?cp=dispro{if $hide_template}&iframe=1{/if}&StoreID={$req.StoreID}&proid={$p.pid}" target="_blank" title="{$lang.but.preview}"><img src="/skin/red/images/icon-finds.gif" /></a></li>
            {/if}
            <li><a href="/soc.php?act=signon{if $hide_template}&iframe=1{/if}&step={$req.select.step}&pid={$p.pid}&ap=copy" title="Copy"><img src="/skin/red/images/icon-copy.gif"/></a></li>
			
			{if $p.is_certified eq 1}
				{if $p.auction_new}
					<li><a href="soc.php?cp=certified{if $hide_template}&iframe=1{/if}&act=list&pid={$p.pid}"><img src="/skin/red/images/icon-people.gif" title="View certified bidder applications." alt="View certified bidder applications."/></a></li>
				{/if}
			{/if}
            </ul>
            </td>
			
        </tr>
        {/if}
        {/foreach}
        </tbody>
        </table>
        {/if}
		<table cellpadding="0" cellspacing="0" width="98%" style="margin-top:10px;">
		<colgroup>
		<col width="80%" />
		<col width="20%" />
		</colgroup>
		<tr>
		<td></td>
		<td align="right"><input type="submit" name="multAct" onclick="multcheckform(this);" value="Delete"/><!--<input type="submit" name="multAct" onclick="multcheckform(this);" value="Publish"/>&nbsp;<input type="submit" name="multAct" onclick="multcheckform(this);" value="Unpublish"/>--></td>
		</tr>
		</table>
		</form>
       <iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="include/cal/ipopeng.php" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
       <p>&nbsp;</p><p>&nbsp;</p>