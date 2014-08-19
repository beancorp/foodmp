<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
<script src="/skin/red/js/jquery-1.4.2.min.js"></script>
<script src="/skin/red/js/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery.marquee.js"></script>

<script src="/js/lightbox_plus.js" type="text/javascript"></script>
<script src="/skin/red/js/uploadImages.js" type="text/javascript"></script>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<link type="text/css" href="/skin/red/css/swfupload.css" rel="stylesheet" media="screen"/>
<script type="text/javascript">
var cp = "{$req.cp}";
var page = "{$req.page}";
{literal}
	$(document).ready(
		function(){
			setTimeout(function(){$("#msg_show").hide();},5000);
			/*$("input[@name='type']").click(function(){
				location.href = "/foodwine/?act=emailalerts&type=" + $(this).val();
			});*/
	});
	function mouseEventLI(obj, flag)
	{
		var ck = $(obj).find(".ck-id:eq(0)").attr("checked");
		if(flag) {
			$(obj).addClass("select");
		} else if(!ck) {
			$(obj).removeClass("select");
		}
		
		return true;
	}
	
	function selectItem(id)
	{
		if($("input[name='type']:checked").val() == 'hotbuy') {
			$("li[id^='li_']").each(function(){
				if($(this).attr("id") != id) {
					$(this).find(".ck-id:eq(0)").attr("checked", "");
					$(this).removeClass("select");
				}
			});
		}
		
		var obj = $("#" + id);
		var ck = $(obj).find(".ck-id:eq(0)").attr("checked");
		var checked = ck ? "" : "checked";
		$(obj).addClass("select");
		$(obj).find(".ck-id:eq(0)").attr("checked", checked);
		
	}
	
	function changeType(type) 
	{
		var first = true;
		if(type == 'hotbuy') {
			$("li[id^='li_']").each(function(n){
				if($(this).find(".ck-id:eq(0)").attr("checked")) {
					if(!first) {
						$(this).find(".ck-id:eq(0)").attr("checked", "");
						$(this).removeClass("select");
					}
					first = false;
				}
			});
			$('#hot_buy_message').show();
		} else {
			$('#hot_buy_message').hide();
		}
	}
	
	function checkForm(obj)
	{
		if(multcheckform()) {
			var msgArr = new Array();
			/*if(obj.title.value == '') 
			{
				msgArr.push("Description is required.");
			}*/
			if(obj.start_date.value == '') 
			{
				msgArr.push("Valid From is required.");
			}
			if(obj.end_date.value == '') 
			{
				msgArr.push("Until is required.");
			}
			
			if(obj.start_date.value && obj.end_date.value) {
				var isCorrectDate = false;
				var startDateArr = obj.start_date.value.split("/");
				var endDateArr = obj.end_date.value.split("/");
			
				for(i=2; i >= 0; i--) {
					if((startDateArr[i] < endDateArr[i]) || (i==0 && startDateArr[i] == endDateArr[i])) {
						isCorrectDate = true;
						break;
					}
				}
			
				if(!isCorrectDate) {			
					msgArr.push("Valid From must before Until.");
				}	
			
				var myDate = new Date();
				var curDateArr = new Array(myDate.getDate(),parseInt(myDate.getMonth()+1),myDate.getFullYear());
				curDateArr[1] = parseInt(curDateArr[1]) < 10 ? ('0' + curDateArr[1]) : curDateArr[1];
			
				var end_date = parseInt(endDateArr[2].toString()+endDateArr[1].toString()+endDateArr[0].toString());
				var cur_date = parseInt(curDateArr[2].toString()+curDateArr[1].toString()+curDateArr[0].toString());
			
				if(end_date < cur_date) {
					msgArr.push("Until must after current date.");
				}
			}
			if(msgArr.length > 0) {
				alert(msgArr.join('\n'));
				return false;
			}
			return true;
		} else {
			return false;
		}
	}
	

	function multcheckform()
	{
		var length = $('#mainForm input[name="pid[]"]:checked').length;
		if(0 == length) {
			alert('Please select items.');
			return false;
		}
		
		return true;
	}
	
	$(function () {
		$("#upload_flyer_image").makeAsyncUploader({
			upload_url: "{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}uploadproduct_img.php?objImage=flyerimage&tpltype=7&attrib=5&index=0",
			flash_url: '/skin/red/js/swfupload.swf',
			button_image_url: '/skin/red/images/blankButton.png',
			disableDuringUpload: 'INPUT[type="submit"]',
			file_types: '*.jpg;*.gif;*.png',
			file_size_limit: '10MB',
			file_types_description: 'All images',
			button_window_mode: "transparent",
			button_text: "",
			height: "29",
			debug: false
		});
	});
	
	function uploadresponse(response) {
		var aryResult = response.split('|');
		if (aryResult.length > 3) {
			var objRes = aryResult[0];
			var imgobj = $("#" + objRes + "_dis");
			$(imgobj).attr('src', "/" + aryResult[1]);
			$(imgobj).css('width', aryResult[2]);
			$(imgobj).css('height', aryResult[3]);
			$("#" + objRes + "_svalue").val("/" + aryResult[4]);
			$("#" + objRes + "_bvalue").val("/" + $.trim(aryResult[5]));
		}
	}

	function uploadprocess(bl) {}
					
{/literal}
</script>
<style>
{literal}
#hot_buy_message {
	border-bottom:1px solid #CCCCCC;
	margin: 0; 
	padding:13px 0;
	clear: both;
	overflow: hidden;
}
.hotbuy_option {
	width: 500px; 
	clear: both;
	margin-top: 15px;
	overflow: hidden;
}
.hotbuy_option_radio {
	padding-left: 50px;
    padding-top: 50px;
	float: left;
}
.hotbuy_option_img {
	padding-top: 15px;
	margin-left: 50px;
	float: left;
}
{/literal}
</style>
{include file="../seller_home_rightmenu.tpl"}
<form id="mainForm" name="mainForm" action="/foodwine/?act=emailalerts" method="post" onsubmit="return checkForm(this);">
{if $req.cp eq ''}
<h1 class="soc-emailalerts">Your Email Alerts</h1>
<div class="view-passorder"><a href="/foodwine/?act=emailalerts&cp=list" style="font-size:12px; line-height:9px; text-decoration:none">View Past Email Alerts</a></div>
{else}
<h1 class="soc-orderonline">Your Past Email Alerts</h1>
<div class="view-passorder"><a href="/foodwine/?act=emailalerts" style="font-size:12px; line-height:9px; text-decoration:none">Add New Email Alerts</a></div>
{/if}
<div class="view-passorder" style="margin-right:20px;"><a href="/foodwine/?act=emailalerts&cp=viewsubscribers" style="font-size:12px; line-height:9px; text-decoration:none">View Subscribers</a></div>
<div class="clear"></div>
<div style="border-bottom:1px solid #CCCCCC; margin:0;"></div> 
<p class="txt" id="msg_show" style="padding:0;{if $req.msg eq ''}display: none;{/if}"><font style="color:red;">{$req.msg}</font></p>

<table>
<tr>
<td>
<a id="upload_flyer_image" href="javascript:uploadImage(6, 5, 0, 'flyerimage' );void(0);">
<img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" align="absmiddle"/>
</a>
<input name="flyerimage_svalue" id="flyerimage_svalue" type="hidden" value="{$req.template.emailalert_image}"/>
<input name="flyerimage_bvalue" id="flyerimage_bvalue" type="hidden" value="{$req.template.emailalert_image}"/>
</td>
</tr>
<tr>
<td>
{if $req.template.emailalert_image neq ''}
	<img src="{$req.template.emailalert_image}" name="flyerimage_dis" id="flyerimage_dis" title="" width="560" border="0"/></a>
{else}
	<img src="/template_images/hotbuy/{$req.info.subAttrib}.jpg" name="flyerimage_dis" id="flyerimage_dis" title="" width="560" border="0"/></a>
{/if}
</td>
</tr>
<tr>
<td>
<a href="javascript:deleteImage(6, 5, 0, 'flyerimage' );void(0);">
<img src="/skin/red/images/bu-delete-sm.jpg" alt="Delete" title="Delete" align="absmiddle"/>
</a>
</td>
</tr>
</table>
<div class="emailalerts">
    <div class="post-title">
    <h2>Create New Email Alerts</h2>
    </div> 
    <div>
        <table style="width:100%;">
            <!--<tr class="first" height="35">
                <td width="12%" align="right">Description</td>
                <td width="88%" colspan="3" style="padding-left:10px;"><input type="text" class="inputB" name="title" value="{$req.info.title}" /></td>
            </tr>-->
            <tr class="first" height="35">
                <td width="12%" align="right">Type</td>
                <td width="88%" colspan="3"><span class="emailalerts-type"><input type="radio" name="type" value="specials" {if $req.info.type eq '' || $req.info.type eq 'specials'}checked="checked"{/if} onclick="changeType(this.value)"/>&nbsp;Specials</span><span><input type="radio" name="type" value="hotbuy" {if $req.info.type eq 'hotbuy'}checked="checked"{/if} onclick="changeType(this.value)"/>&nbsp;Hot Buy</span></td>
            </tr>
            <tr height="35">
                <td align="right" width="12%">Valid From</td>
                <td width="20%" style="padding-left:10px;"><input name="start_date" id="start_date" type="text" class="inputB date"  size="11" readonly="readonly" maxlength="11" value="{$req.info.start_date|date_format:"%d/%m/%Y"}"/>
          			  <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.mainForm.start_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>
                </td>
                <td align="right" width="2%" style="padding:0 10px 0 5px;">Until</td>
                <td><input name="end_date" type="text" class="inputB date" id="end_date" size="11" readonly="readonly" maxlength="11" value="{$req.info.end_date|date_format:"%d/%m/%Y"}"/>
            <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.mainForm.end_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>
                </td>
            </tr>
        </table>
    </div>
	<div style="border-bottom:1px solid #CCCCCC; margin:0; padding:13px 0;"></div> 
	<div id="hot_buy_message" style="{if $req.info.type eq 'hotbuy'}display: 'block';{else}display: none;{/if}">
		<h3>Select a 'Hot Buy' message</h3>
		<div class="hotbuy_option">
			<div class="hotbuy_option_radio" style="padding-top:5px;">
				<input type="radio" name="hotbuy_message" value="0" checked="checked" />
			</div>
			<div class="hotbuy_option_img" style="padding-top:3px;">
				No message to be displayed on your Hot Buy Special.
			</div>
		</div>
		<div class="hotbuy_option">
			<div class="hotbuy_option_radio">
				<input type="radio" name="hotbuy_message" value="1" />
			</div>
			<div class="hotbuy_option_img">
				<img src="/images/hotbuy_1.jpg" />
			</div>
		</div>
		{if $req.sold_status eq 1}
		<div class="hotbuy_option">
			<div class="hotbuy_option_radio">
				<input type="radio" name="hotbuy_message" value="2" />
			</div>
			<div class="hotbuy_option_img">
				<img src="/images/hotbuy_2.jpg" />
			</div>
		</div>
		{/if}
	</div>
	
    <div style="position:relative;">
    	<h3>Select Items</h3>
        <a class="edit-stock-items" href="/foodwine/?act=product&step=4">Edit Stock Items</a>
            <div class="clear"></div>
        <p style="padding:0; margin:0; font-size:12px">Click on the check boxes to select items from your current stock list below to be included in your email alerts.</p>
    </div>
	
	<div class="clear"></div>
	<input type="image" border="0" value="Preview Email Alerts" onclick="javascript:document.mainForm.cp.value='preview';" src="/skin/red/images/foodwine/preview-emailalerts.jpg" style="margin:15px 10px 0 0; float:right; border:none">
	<input type="image" border="0" value="Save Email Alerts" onclick="javascript:document.mainForm.cp.value='save';" src="/skin/red/images/foodwine/save-emailalerts.jpg" style="margin:15px 3px 0 0; float:right; border:none">
	<div class="clear"></div>

	<div class="emailalerts_items_list" style=" min-height:205px;">
	<ul>
		{foreach from=$products.items item=p key=k}  
        <li class="{if $k%2 eq 0}left{else}right{/if} {if $k<2}top{else}ntop{/if} {if $req.info.pid_ary && in_array($p.pid, $req.info.pid_ary)}select{/if}" onmouseover="mouseEventLI(this, true);" onmouseout="mouseEventLI(this, false);" onclick="selectItem('li_{$p.pid}');" id="li_{$p.pid}">
        		<input class="ck-id" type="checkbox" name="pid[]" value="{$p.pid}" onclick="selectItem('li_{$p.pid}')" {if $req.info.pid_ary && in_array($p.pid, $req.info.pid_ary)}checked="checked"{/if} />
                {if $p.small_image && $p.small_image neq '/images/243x212.jpg'}
				<div class="img">
					<img src="{$p.small_image}" width="80" height="58" alt="{$p.name}" title="{$p.name}"/>
				</div>
                {/if}
				<div class="desc">
					<div class="name">{$p.item_name}</div>
                    {if $p.price neq '0.00'}
					<div class="price">
                    {if $p.priceorder eq 1}
                		{$p.unit} ${$p.price}
                    {else}
                        ${$p.price} {$p.unit}
                    {/if}
                    </div>
                    {/if}
				</div>
			</li>
    {/foreach}
    </ul>
    </div>
</div>
<div class="clear"></div>
<div>&nbsp;{$req.order_lists.linkStr}</div>
<input type="hidden" name="cp" value="save" />
<input type="hidden" name="eid" value="{$req.info.id}" />
<input type="hidden" name="StoreID" value="{$req.StoreID}" />
<input type="image" border="0" value="Preview Email Alerts" onclick="javascript:document.mainForm.cp.value='preview';" src="/skin/red/images/foodwine/preview-emailalerts.jpg" style="margin:15px 10px 0 0; float:right; border:none">
<input type="image" border="0" value="Save Email Alerts" onclick="javascript:document.mainForm.cp.value='save';" src="/skin/red/images/foodwine/save-emailalerts.jpg" style="margin:15px 3px 0 0; float:right; border:none">
</form>	
