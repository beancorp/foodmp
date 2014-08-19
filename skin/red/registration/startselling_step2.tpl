{if $registered}
	{literal}
		<script type="text/javascript">
		var fb_param = {};
		fb_param.pixel_id = '6011881536639';
		fb_param.value = '0.00';
		fb_param.currency = 'AUD';
		(function(){
		  var fpw = document.createElement('script');
		  fpw.async = true;
		  fpw.src = '//connect.facebook.net/en_US/fp.js';
		  var ref = document.getElementsByTagName('script')[0];
		  ref.parentNode.insertBefore(fpw, ref);
		})();
		</script>
		<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6011881536639&amp;value=0&amp;currency=AUD" /></noscript>
		<!-- Google Code for Business Registration Conversion Page --> 
		<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = 976104963;
		var google_conversion_language = "en";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "lYraCJW0gQgQg9y40QM"; var google_conversion_value = 0; var google_remarketing_only = false;
		/* ]]> */
		</script>
		<script type="text/javascript"  
		src="//www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt=""  
		src="//www.googleadservices.com/pagead/conversion/976104963/?value=0&amp;label=lYraCJW0gQgQg9y40QM&amp;guid=ON&amp;script=0"/>
		</div>
		</noscript>
		<img src="https://track.performtracking.com/aff_l?offer_id=495" width="1" height="1" />
	{/literal}
{/if}
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen" />
<script language="JavaScript" type="text/JavaScript">
var tmpl = "{$smarty.session.TemplateName}";
var check_paypal = {if $req.payments.3}true{else}false{/if}; 
var check_googlecheckout = {if $req.payments.7}true{else}false{/if}; 
{literal}
function checkForm(obj){
	var errors;
	errors ="";
	try{
{/literal}
		{if $req.attribute eq '0'}
		{literal}
		/*if($('input[@name="bu_delivery[]"][@checked]').length==0){
			errors += '-  Shipping is required.\n';
		}*/
		var has_delivery = false;
		$('input[name="bu_delivery_text[]"]').each(function(){
			if($.trim($(this).val()) != '') {
				has_delivery = true;
			}
		});
		
		if(!has_delivery) {
			errors += '-  Please add shipping methods for your store.\n';
		}
		
		//if($('input[@name="payments[]"][@checked]').length==0){
		//	errors += '-  Payment Accepted String is required.\n';
		//}
		if($('#bu_paypal').val() == ""){
			errors += '-  Paypal Acc is required.\n';
		}
		if($('#btpayment').val() == "1"){
			if($('#bt_account_name').val() == ""){
				errors += '-  Account Name is required.\n';
			}
			if($('#bt_BSB').val() == ""){
				errors += '-  BSB is required.\n';
			}
			if($('#bt_account_num').val() == ""){
				errors += '-  Account Number is required.\n';
			}else{
				if(isNaN($('#bt_account_num').val())){
				errors += '-  Account Number is invalid.\n';
				}
			}
		}
		if($('#bu_college').val()!=""){
			if($('#colleges_ACN').val()==""){
				errors += "-  Abbreviated College Name is required.\n";
			}
		}
		{/literal}
		{/if}
		{if $req.attribute eq '5' && $req.foodwine_type eq 'food'}
		{literal}
		//if($('input[@name="payments[]"][@checked]').length==0){
		//	errors += '-  Payment Accepted String is required.\n';
		//}
		if($('#btpayment').val() == "1"){
			if($('#bt_account_name').val() == ""){
				errors += '-  Account Name is required.\n';
			}
			if($('#bt_BSB').val() == ""){
				errors += '-  BSB is required.\n';
			}
			if($('#bt_account_num').val() == ""){
				errors += '-  Account Number is required.\n';
			}else{
				if(isNaN($('#bt_account_num').val())){
				errors += '-  Account Number is invalid.\n';
				}
			}
		}
		if($("input[name='payments[]']:checked").val() == '3' && parseInt($("input[name='sold_status']:checked").val())){
			//if($('#bu_paypal').val() == ""){
				//errors += '-  Paypal Acc is required.\n';
			//}
		}
		
			/***vaild the shipping method****/
			var ck_bl_err =false;
			var ck_is_err =true;
			$.each($('.ck_deliveryMethod'),function(i,n){
				if($(n).attr('checked')){
					if (parseInt($(n).val()) == 1 || parseInt($(n).val()) == 2){
						if(!isNaN(parseFloat($($('.input_postage')[i]).val()))&&(parseFloat($($('.input_postage')[i]).val())>=0||$($('.input_postage')[i]).val()=="0")){}else{
							ck_bl_err = true;
						}
					}
					ck_is_err = false;
				}
			});
			
			//if(ck_is_err && parseInt($("input[name='sold_status']:checked").val())){errors += '-  Normal Shipping Method is required.\n';}
			if(ck_bl_err && parseInt($("input[name='sold_status']:checked").val())){errors += '-  Please set the cost for your shipping methods. \n';}
			
			/*if(obj.isoversea.checked){
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
			}*/
		{/literal}
		{/if}
{literal}
		if($('#facebook').val()!=""&&$('#facebook').val()!="http://"&&$('#facebook').val()!="https://"){
			if(!IsUrl($('#facebook').val())){
				errors += "-  Facebook url is invalid.\n";
			}
		}
		if($('#twitter').val()!=""&&$('#twitter').val()!="http://"&&$('#twitter').val()!="https://"){
			if(!IsUrl($('#twitter').val())){
				errors += "-  Twitter url is invalid.\n";
			}
		}
		/*if($('#myspace').val()!=""&&$('#myspace').val()!="http://"&&$('#myspace').val()!="https://"){
			if(!IsUrl($('#myspace').val())){
				errors += "-  MySpace url is invalid.\n";
			}
		}*/
		if($('#linkedin').val()!=""&&$('#linkedin').val()!="http://"&&$('#linkedin').val()!="https://"){
			if(!IsUrl($('#linkedin').val())){
				errors += "-  Linked In url is invalid.\n";
			}
		}
	}catch(ex){
		alert(ex);
	}
	if(errors!=""){
		errors = 'Sorry, the following fields are required.\n'+errors;
		alert(errors);
		return false;
	}
	return true;
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

function loadIcon(params) {
	
	try{
		ajaxLoadPage('soc.php','cp=loadicon&websiteicon='+params,'GET',document.getElementById('iconpreview'));
	}
	catch(ex)
	{
		alert(ex);
	}
}
function showpayment(obj){
	if(obj.checked){
		$('#bankTranTr').css('display','');
		$('#btpayment').val(1);
	}else{
		$('#bankTranTr').css('display','none');
		$('#btpayment').val(0);
	}
}
function showPaymentRadio(obj){
	if(parseInt($(obj).val()) == 5) {
		if($(obj).attr("checked")){
			$('#bankTranTr').css('display','');
			$('#btpayment').val(1);
		}else{
			$('#bankTranTr').css('display','none');
			$('#btpayment').val(0);
		}
	}
	
	if(parseInt($(obj).val()) == 3) {
		check_paypal = $(obj).attr("checked");
		if(check_paypal){
			$('#paypal_acc').css('display','');
			$('#txt_PaymentMethod').css('display','');
		}else{
			$('#paypal_acc').css('display','none');
			$('#txt_PaymentMethod').css('display','none');
		}
	}
	
	if(parseInt($(obj).val()) == 7) {
		check_googlecheckout = $(obj).attr("checked");
		if(check_googlecheckout){
			$('.google_checkout_acc').css('display','');
		}else{
			$('.google_checkout_acc').css('display','none');
		}
	}	
	
	if(check_paypal || check_googlecheckout) {
		$('#txt_PaymentMethod').css('display','');		
	} else {
		$('#txt_PaymentMethod').css('display','none');		
	}
}
function   IsUrl(str){ 
  var regExp = /(http[s]?|ftp):\/\/[^\/\.]+?\..+\w$/i;
  if (str.match(regExp)){
  	return true; 
  }else{
   	return false;      
  }
}

 $(function() {
        $("#photo").makeAsyncUploader({
            upload_url: "/uploadgallery.php?type=product&ut=3&res="+tmpl,
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
 });
 function uploadresponse(response){
		var aryResult = response.split('|');
		if(aryResult[2]&&$.trim(aryResult[2])=='product'){
			$("#MainIMG2").attr('src',aryResult[0]);
			$("#MainImageH").val(aryResult[0]);
		}
}
function uploadprocess(bl){
	if(!bl){
		$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save_grey.gif)');
	}else{
		$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save.gif)');
	}
}

function show_ACN_tag(obj){
	if($(obj).val()==""){
		$('#ACN_tag').css('display','none');
	}else{
		$('#ACN_tag').css('display',"");
	}
}

function enableCost(name,obj){
	if ($(obj).is(':checked')) {
		$('input[name="'+name+'"]').attr('disabled',false);
	} else {
		$('input[name="'+name+'"]').attr('disabled',true);
	}
}

function showoversea(obj){
	if(obj.checked){
		$("#overshipline").css('display','');
	}else{
		$("#overshipline").css('display','none');
	}
}

function changeOnlineShopping(flag) {
	if (flag) {
		$("#required_fields").css('display','');
		$("#shipline").css('display','');
		$("#deliver_suburbs").css('display','');
		$("#account_holders").css('display','');
		if($("input[name='payments[]']:checked").val() == '3') {		
			$("#txt_PaymentMethod").css('display','');
			$("#paypal_acc").css('display','');
		}
	} else {
		$("#required_fields").css('display','none');
		$("#shipline").css('display','none');
		$("#txt_PaymentMethod").css('display','none');
		$("#paypal_acc").css('display','none');
		$("#deliver_suburbs").css('display','none');
		$("#account_holders").css('display','none');
	}
}
</script>
<style type="text/css">
.class_uploadding{ padding-right:10px;}
</style>
{/literal}
<h1 style="width:400px; color:#3F3480; font-size:14pt; font-weight:bold;">{if $req.attribute eq 3 && $req.subAttrib eq 1}Employer's{elseif $req.attribute eq 3 && $req.subAttrib eq 2}Job Seeker's{elseif $req.attribute eq 5}Retailer{elseif $req.subAttrib neq '2'}Seller's{elseif $req.attribute eq 1}Agent's{else}Dealer's{/if} Information</h1>

 		<div id="top_spacer_div" style=" clear:both; background:#ccc; height:1px; line-height:1px; margin:25px 20px 30px 0;"><img src="images/spacer.gif" alt="" /></div>


        <form id="registration_form_step2" name="mainForm" action="" method="POST"  onsubmit="return checkForm(this);">
	
    	<input type="hidden" name="cp" value="">
  
    	<div id="step_2_retailer_info_box" style="float:left; width:{if $req.attribute eq '0'}98%{else}98%{/if};">{if $req.attribute eq 5}Write some information about your business here.{else}Write some general information about yourself and your website here.{/if}<br />
    	<br />
        <textarea name="bu_desc" class="inputB" style="width:100%;height:100px;">{$req.bu_desc|strip_tags:false}</textarea><br />

		</div>
    
	{if ($req.attribute eq '0') or (($req.attribute eq '5') and ($req.foodwine_type eq 'food')) or $req.attribute eq '1' or $req.attribute eq '2'}
    	<div id="step2_shipping_details" style="float:left; width:50%; margin-top:25px;">
    	{if $req.attribute eq '0'}
        <h3 style="font-size:16px; color:#666; font-weight:bold; margin-top:0;">Shipping Details</h3>
        {/if}
        <table cellpadding="0" cellspacing="0" id="shipping" width="100%">
		<colgroup>
        <col width="20%" />
        <col width="80%" />
        </colgroup>
       {if $req.attribute eq '0'}

       <tr>
        <td>Shipping*</td>
        <td>
        		{foreach from=$req.delivery item=d key=key}
        		<input type="checkbox" name="bu_delivery[]" value="{$key}" class="checkbox" {if $req.bu_delivery[$key]}checked{/if}/> {php}echo ++$key;{/php}: <input type="text" class="text" name="bu_delivery_text[]" style="width:180px;" value="{$d|escape:'html'}" />
                <br /><br />
                {/foreach}
		</td>
    	</tr>
		<tr><td colspan="2">
        <h3 style="font-size:16px; color:#666; font-weight:bold; margin-top:0;">Payment Details</h3></td>
		</tr>
        <tr>
        <td>Paypal Acc# *</td>
        <td>
        	<input name="bu_paypal" type="text" class="text" id="bu_paypal" value="{$req.bu_paypal}" /><br>
       	    <strong>Put your Paypal email ID here</strong> </td>
		</tr>
        <tr style="display:none;">
        <td>Payment<br />Accepted*</td>
        <td>
        	<input type="checkbox" value="3" class="checkbox" name="payments[]" checked="checked" />
        </tr>
        {elseif $req.attribute eq '5' and ($req.foodwine_type eq 'food')}
        <tr style="display:none">
        <td>Payment Methods Accepted*</td>
        <td>
        	<input type="checkbox" value="3" class="checkbox" name="payments[]" checked="checked" />
        </tr>
        <tr id="bankTranTr" style="{if $req.payments[5] && 0}display:{else}display:none;{/if}">
			<td colspan="2" style="padding:0px;">
			<input type="hidden" name="btpayment" id="btpayment" value="{if $req.payments[5]}1{else}0{/if}" />
			<table width="100%" cellpadding="0" cellspacing="0">
				<colgroup>
				<col width="24%" />
				<col width="76%" />
				</colgroup>
				<tr>
				<td style="padding:10px 0;">Account Name* </td><td style="padding:10px 0;"><input name="bt_account_name" id="bt_account_name" type="text" maxlength="50" class="inputB" style="width:230px;" value="{$req.bt_account_name|escape:'html'}" /></td>
				</tr>
				<tr>
				<td style="padding:10px 0;">BSB* </td><td style="padding:10px 0;"><input type="text" class="inputB" name="bt_BSB" id="bt_BSB"  style="width:230px;" maxlength="50" value="{$req.bt_BSB}" /></td>
				</tr>
				<tr>
				<td style="padding:10px 0;">Account Number* </td><td style="padding:10px 0;"><input type="text" class="inputB" name="bt_account_num" id="bt_account_num" style="width:230px;" maxlength="50" value="{$req.bt_account_num}" /></td>
				</tr>
				<tr>
				<td style="padding:10px 0;">Bank Transfer Instructions </td><td style="padding:10px 0;"><textarea class="inputB" style="width:230px;" name="bt_instruction">{$req.bt_instruction|escape:'html'}</textarea></td>
				</tr>
			</table>
			</td>	
		</tr>       
        <tr id="allow_online_shopping">
            <td style="width: 100px;">Allow Online Shopping</td>
            <td>
                <input onclick="changeOnlineShopping(1)" type="radio" name="sold_status" value="1" {if $req.sold_status eq '1'}checked{/if}/> Yes
                <input onclick="changeOnlineShopping(0)" type="radio" name="sold_status" value="0" {if $req.sold_status neq '1'}checked{/if}/> No
            </td>
        </tr>
        <tr id="shipline" style="{if $req.sold_status neq '1'}display:none;{/if}">
          <td>Shipping Method* (Normal)</td>
          <td>
		  {$req.select_shipping.deliveryMethod}
		  </td>
        </tr>
        <tr id="deliver_suburbs" valign="top" style="{if $req.sold_status eq '1'}display:{else}display:none;{/if}">
            <td>List the suburbs you deliver to:</td>
            <td>
            <textarea class="inputB" name="suburb_delivery" style="height:70px; width:230px;">{$req.suburb_delivery}</textarea>
            </td>
        </tr>
		
        <tr id="required_fields" style="{if $req.sold_status neq '1' && $req.attribute eq '5'}display:none{/if}">
        <td>&nbsp;</td>
        <td style="font-size:10px;">*required fields</td>
        </tr>

		<tr id="account_holders">
			<td colspan="2">
				<style>
					{literal}
						#account_holder_box {
							clear: both;
							margin-top: 10px;
							overflow: hidden;
						}
						
						#account_holder_add {
							margin-bottom: 10px;
						}
						
						#account_holder_add input {
							width: 230px;
							padding: 5px;
						}
						
						#account_holder_add span {
							font-size: 10px;
						}
						
						#account_holder_list table td {
							padding-bottom: 5px;
						}
						
						#account_holder_add .step_list {
							font-size: 11px;
						}
						
						#account_holder_add .step_list strong {
							font-size: 11px;
						}
						
						.delete_account {
							margin-left: 5px;
							font-weight: bold;
							text-decoration: underline;
							cursor: pointer;
						}
					{/literal}
				</style>
				
				<div id="account_holder_box">
					<div id="account_holder_add">
						<strong style="color: #cd2040;">Online Shopping Offline Payment</strong> <br />
						<span>Online shopping for pre-approved customers with payment made offline <br />
						(Cash, Cheque, Credit Card).</span> <br />
						<span class="step_list">
						<br /><strong>Step 1</strong> - Your customer joins FoodMarketplace for FREE
						<br /><strong>Step 2</strong> - Enter your customer's email address in the box below.</span><br /><br />
						<input type="text" id="consumer_email" placeholder="Email Address" />
					</div>
					<div id="account_holder_list">
						{php}show_account_list();{/php}
					</div>
				</div>
				<script>
					{literal}
						$(function() {
							$("#consumer_email").autocomplete({
								source: "registration.php?query_consumers=1",
								minLength: 2,
								select: function(event, ui) {
									var user_id = ui.item.id;
									$.ajax({
										url: "registration.php?query_consumers=2",
										type: "POST",
										data: { user_id : user_id },
										dataType: "html"
									}).done(function( msg ) {
										$( "#account_holder_list" ).html( msg );
									});
								}
							});
						});
					{/literal}			
				</script>
			</td>
		</tr>	
		
        {else}
        <tr><td colspan="2" style=" padding:0;">
        <h3 style="font-size:16px; color:#666; font-weight:bold; margin-top:0;">Payment Details</h3></td>
		</tr>
        <tr>
        <td>Paypal Acc#</td>
        <td>
        	<input name="bu_paypal" type="text" class="text" id="bu_paypal" value="{$req.bu_paypal}" /><br>
       	    <strong>Put your Paypal email ID here</strong> </td>
		</tr>
        {/if}	
        <!--<tr><td>Google Checkout<br>Merchant ID</td>
        <td><input name="google_merchantid" type="text" class="text" id="google_merchantid" value="{$req.google_merchantid}"  /><br />
		  <strong>Put your Merchant ID here to activate google checkout</strong> </td>
		</tr>
        <tr>
        <td>Google Checkout<br>Merchant Key</td>
        <td><input name="google_merchantkey" type="text" class="text" id="google_merchantkey" value="{$req.google_merchantkey}"  /><br />
		  <strong>Put your Merchant Key here to activate google checkout</strong> </td>
		</tr>-->
        </table>
        </div>
		{/if}
	<div id="step2_other_details" style="float:left; width:50%;margin-top:25px;">
    {if $req.attribute neq '5'}
	 <h3 style="font-size:16px; color:#666; font-weight:bold; margin-top:0;">Other Details</h3>
    {/if}
	<table id="otherdetails" width="100%" cellspacing="0">
		{if $req.attribute eq '0'}
			<tr><td colspan="2">College / University Name<br />
			<select name="bu_college" style="width:349px; border:solid 1px #ccc; " id="bu_college" onchange="show_ACN_tag(this);">
			<option value="">College / University</option>
			{$req.colleges}
			</select>
            </td></tr>
            
			<tr id="ACN_tag" {if $req.bu_college eq ""}style="display:none;"{/if}>
				<td colspan="2">Abbreviated College Name* &nbsp;&nbsp;(eg. UNSW, USYD)<br/>
			<input type="text" class="inputB" style="width:337px;" name="bu_colleges_ACN" id="colleges_ACN" value="{$req.bu_colleges_ACN}" maxlength="240"/></td>
			</tr>
			<tr><td colspan="2"><input name="college_hide" type="checkbox" id="college_hide" value="1" {if $req.college_hide eq '1' } checked {/if}/> Hide College / University</td></tr>
		{/if}
        
			<tr><td>Facebook:</td><td><input name="facebook" type="text" id="facebook" class="inputB" value="{if $req.facebook neq ""}{$req.facebook}{else}http://{/if}" /></td></tr>
			<tr><td>Twitter:</td><td><input name="twitter" type="text" id="twitter" class="inputB" value="{if $req.twitter neq ""}{$req.twitter}{else}http://{/if}" /></td></tr>
			<!--<tr><td>MySpace:</td><td><input name="myspace" type="text" id="myspace" class="inputB" value="{if $req.myspace neq ""}{$req.myspace}{else}http://{/if}" /></td></tr>-->
			<tr><td>Linked In:</td><td><input name="linkedin" type="text" id="linkedin" class="inputB" value="{if $req.linkedin neq ""}{$req.linkedin}{else}http://{/if}" /></td></tr>
        	<tr><td>Youtube Video:<br/></td>
            <td>
            	<textarea class="inputB" name="youtubevideo" style="height:45px;">{$req.youtubevideo}</textarea>
            </td>
            </tr>            
        {if $req.attribute eq '5'}
            <tr>
                <td>Opening Hours:</td>
                <td>
                <textarea class="inputB" name="opening_hours" style="height:45px;">{$req.opening_hours}</textarea>
                {if 0}
                {$req.edit_opening_hours}
                {/if}
                </td>
            </tr>
            {if $req.foodwine_type eq 'food'}
            <tr id="txt_PaymentMethod" style="{if $req.payments.3 && $req.sold_status}display:{else}display:none;{/if}">
                <td colspan="2" style="font-weight: bold; color: #1e3968;">Online Shopping Payment Method:</td>
            </tr>
            <tr id="paypal_acc" style="{if $req.payments.3 && $req.sold_status}display:{else}display:none;{/if}">
            <td>Paypal Acc# *</td>
            <td>
                <input name="bu_paypal" type="text" class="inputB" id="bu_paypal" value="{$req.bu_paypal}" /><br>
                <strong>Put your Paypal email ID here</strong> </td>
            </tr>
            {/if}
   		{/if}
		</table>
		<div style="clear:both; padding-top:40px;">&nbsp;</div>
		<fieldset style="text-align:right; margin-right:10px;">
			<input src="
			{if $stepButtonStatu.step2 and $smarty.session.attribute == 0}
				/skin/red/images/buyseller_step_button/edit_store_template.gif
			{else}
				/skin/red/images/bu-nextsave.gif
			{/if}
			" class="submit form-save" type="image" onclick="document.mainForm.cp.value='next';">
			<!--<input src="/skin/red/images/bu-saveexit.gif" class="submit form-save" type="image" onclick="document.mainForm.cp.value='save';">-->
		</fieldset>
        </div>
		</form>