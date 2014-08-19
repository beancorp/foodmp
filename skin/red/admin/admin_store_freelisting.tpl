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
<script type="text/javascript" src="/skin/red/js/freelistingupload.js"></script>
<link type="text/css" href="/skin/red/css/swfupload_product.css" rel="stylesheet" media="screen" />
<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<script type="text/javascript">
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
function checkFreeImport(obj){
	var boolResult = false;
	var errors	= '';
	
	var uploadType = $("[name='rdo_import_type']:checked").val();
	
	if(!obj.csv){
		if($('#swf_csvmsg').val()==""){
			errors += 'Free Listing CSV file is required.\n';
		}
		if(errors != '')
		{
			errors = '-  Sorry, ' + errors;
			alert(errors);
		}else{
			boolResult = true;
		}
		return boolResult;
	}
	try{
		if(obj.csv.value==''){
			errors += 'Free Listing CSV file is required.\n';
		}
		
		if(errors != '')
		{
			errors = '-  Sorry, ' + errors;
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
</script>
{/literal}
{if !$req.nofull}
<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>

<div align="center" style="border-bottom-color:#999999;">
<div id="input-table" style="width:720px;">
<ul>
<!--Import Freelisting -->
<form action="" method="post" enctype="multipart/form-data" name="bulk" id="bulk" onsubmit="return checkFreeImport(this)">				
<div id="admin_msg">
    <div class="wrap">
        <div class="row">
            <div class="title">CSV File :</div>
            <div id="recipients" class="data">
            <input name="csv" type="file" id="csv" class="inputB"  style="width:200px;" />
            <label>&nbsp;<a href="/download.php?cp=csvsample&filetype=freelisting" target="_blank" id="down_sample_csv">Sample Free Listing CSV</a></label>
            </div>
            <div class="clear"></div>
        </div>
        <div class="row">
        <div class="title">&nbsp;
            <input type="hidden" id="swf_csvmsg" name="swf_csv" value=""/>
            <input type="hidden" id="swf_imgmsg" name="swf_img" value=""/>
        </div>
        <div id="csvmsg" style="color:red;" class="data">&nbsp;</div>
        </div>
        <div class="row">
            <div class="title">&nbsp;</div>
            <div class="data">
                <p style="padding:10px 0;">Please keep all file uploads to a maximum of 70MB in size.</p>
                <input type="hidden" name="act" value="store" />
                <input type="hidden" name="cp" value="freelisting" />
                <input class="submit" type="image" src="/skin/red/images/import.gif" name="Submit" value="Import Products" /></div>
            <div class="clear"></div>
        </div>
        {if $error_info}
        <div class="row">
            <div class="title">&nbsp;</div>
            <div class="data">
                <p style="padding:10px 0; color:#FF0000">{$error_info.all_msg}</p>
                {if $error_info.fail}<p>{$error_info.error_more}</p>{/if}
            </div>
            <div class="clear"></div>
        </div>
        {/if}
    </div>
</div>
</form>
<!--Import Freelisting End -->



</ul>
<div style="clear:both;"></div>
</div>

</div>
{/if}
