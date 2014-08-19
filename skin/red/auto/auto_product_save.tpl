<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<script language="javascript">
var protype =0;
var soc_http_host="{$soc_http_host}";
</script>
<script type="text/javascript" src="/skin/red/js/productupload.js"></script>
<link type="text/css" href="/skin/red/css/swfupload_product.css" rel="stylesheet" media="screen" />
{include_php file="jssppopup.php"}
{literal}
<style type="text/css">
	.op_bg{width:752px; height:41px; cursor:pointer; margin:5px 0; position:relative}
	.op_bg_year{background:url(/skin/red/images/bg-items.jpg) no-repeat left top; }
	.op_bg_add{background:url(/skin/red/images/bg-items.jpg) no-repeat left top; }
	.op_bg_add_select{background:url(/skin/red/images/bg-items-select.jpg) no-repeat left top; }
	.op_bg_edit{background:url(/skin/red/images/bg-items.jpg) no-repeat left top; }
	.op_bg_edit_select{background:url(/skin/red/images/bg-items-select.jpg) no-repeat left top;}
	
	.op_bg label{font-weight:bold; padding:13px 0 0 43px; position:absolute; cursor:pointer}
	.op_bg_option_lab{color:#3c317d;}
	.op_bg_option_lab_select{color:#FFFFFF;}
</style>
{/literal}
<script language="javascript">
{literal}
function checkSubmit(obj){
	var errors	=	'';
	
	//autoChangeEdit(obj,'content');
	
	if(obj.item_name.value==''){
		{/literal}errors += '-  {$lang.tt.itemname} is required.\n';{literal}
	}
	
	if(obj.sector_1.value=='-1'){
		{/literal}errors += '-  {$lang.tt.type} is required.\n';{literal}
	}

	if(obj.sector_2.value=='-1'){
		{/literal}errors += '-  {$lang.tt.make} is required.\n';{literal}
	}

	if(obj.sector_3.value=='-1'){
		{/literal}errors += '-  {$lang.tt.model} is required.\n';{literal}
	}

	if(obj.year.value=='0'){
		{/literal}errors += '-  {$lang.tt.year} is required.\n';{literal}
	}

	if(obj.door.value=='-1'){
		{/literal}errors += '-  {$lang.tt.door} is required.\n';{literal}
	}

	if(obj.seat.value=='0'){
		{/literal}errors += '-  {$lang.tt.seat} is required.\n';{literal}
	}

	if(obj.pattern.value=='0'){
		{/literal}errors += '-  {$lang.tt.pattern} is required.\n';{literal}
	}
	
	if(obj.sector_2.value=='-2' && obj.makeUser.value == 'Please enter the make here'){
		{/literal}errors += '-  {$lang.tt.make} is required.\n';{literal}
	}
	
	if(obj.sector_3.value=='-2' && obj.modelUser.value == 'Please enter the model here'){
		{/literal}errors += '-  {$lang.tt.model} is required.\n';{literal}
	}
	
	if(obj.kms.value==''){
		{/literal}errors += '-  {$lang.tt.kms} is required.\n';{literal}
	}
	
	if(!obj.negotiable.checked && obj.price.value==''){
		{/literal}errors += '-  {$lang.tt.price} is required.\n';{literal}
	} else if(obj.price.value.replace(/(^\+?[\d]{1,}.[\d]{1,2})|(^\+?[\d]{1,})/gi, '') != ''){
		{/literal}errors += '-  {$lang.tt.price} is invalid.\n';{literal}
	}
		
	
	if(obj.suburb_1.value=='-1'){
		{/literal}errors += '-  {$lang.tt.state} is required.\n';{literal}
	}
		
	if(obj.suburb_2.value=='-1' || obj.suburb_2.value==''){
		{/literal}errors += '-  {$lang.tt.suburb} is required.\n';{literal}
	}
	
	if(obj.postcode.value==''){
		{/literal}errors += '-  {$lang.tt.postcode} is required.\n';{literal}
	}
	
	if (errors != ''){
		errors = '-  Sorry, the following fields are required.\n' + errors;
		alert(errors);
		return false;
	}else{
		return true;
	}
}

function check_make2(val){
	if (val == '-2'){
		$('#custom_make2').css('display','');
	}else{
		$('#custom_make2').css('display','none');
	}
	if($('#sector_3').val()=='-2'){
		check_model2(val);
	}
}

function check_model2(val){
	if (val == '-2'){
		$('#custom_model2').css('display','');
	}else{
		$('#custom_model2').css('display','none');
	}
}

function makeAndModel(){
	
	$('#makeUser').focus(function(){defaultClew('#makeUser', true, 'Please enter the make here');});
	$('#makeUser').blur(function(){defaultClew('#makeUser', false, 'Please enter the make here');});

	$('#modelUser2').focus(function(){defaultClew('#modelUser2', true, 'Please enter the model here');});
	$('#modelUser2').blur(function(){defaultClew('#modelUser2', false, 'Please enter the model here');});
	
	$('#sector_1').change(function(){check_make2(0);});
	loadingOfCombox("#sector_1,#sector_2");
}

function content2AddItem(){
	$('#content2Html').append('<textarea name="featureList[]" cols="80" rows="8" wrap="virtual" id="featureList[]" style="width:290px; height:40px; padding-left:5px; margin-bottom:2px;"></textarea><br/>');
	void(0);
}
function checkImport(obj){
	var boolResult = false;
	var errors	= '';
	if(!obj.csv){
		if($('#swf_csvmsg').val()==""){
			errors += '-  Products Information is required.\n';
		}
		if($('#swf_imgmsg').val()==""){
			errors += '-  Products Images is required.\n';
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

windowOnload(function (){ 
	makeAndModel();
	{/literal}
	{if !$req.select.pid}
	xajax_getSuburbList(2, 'suburb_1', 2, 'Select a City/ Suburb');
	{/if}
	{literal}
});

{/literal}
</script>
{if $req.info.product_feetype eq 'product' || $req.info.product_feetype eq '' || ($req.info.product_renewal_date < $cur_time)}
<p align="center" class="txt"><font style="color:red;">{$req.msg}</font></p>
<p align="left" class="txt"><font style="color:#d21d3c; font-size:15px; font-weight:bold;">Please choose one of the following options: </font></p>
<div class="op_bg op_bg_year" onclick="javascript:location.href='/auto/?act=product&cp=pay&feetype=year&step=4'"><label class="op_bg_option_lab">1: List unlimited vehicles for just $120 per year</label></div>
<a name="optionsadd"></a>
<div  class="op_bg op_bg_add{if $req.select.options eq 'add'}_select{/if}" onclick="javascript:location.href='/auto/?act=product&step=4{if $req.select.options neq 'add'}&options=add#optionsadd{/if}'"><label class="op_bg_option_lab{if $req.select.options eq 'add'}_select{/if}">2: List a single vehicle until sold for just $10 (valid for 1 year)</label></div>
{if $req.select.options eq 'add'}
<div style="border:solid 1px #cccccc; padding:10px;">
{include file='auto_product_form.tpl'}
</div>
{/if}
<a name="optionsedit"></a>
<div class="op_bg op_bg_edit{if $req.select.options eq 'edit'}_select{/if}" onclick="javascript:location.href='/auto/?act=product&step=4{if $req.select.options neq 'edit'}&options=edit#optionsedit{/if}'"><label class="op_bg_option_lab{if $req.select.options eq 'edit'}_select{/if}">3: Edit existing listings</label></div>
{if $req.select.options eq 'edit'}
<div style="border:solid 1px #cccccc; padding:10px;">
<span style="text-align:left; vertical-align:middle; color:red; float:left; padding-right:20px;padding-top:15px; position: relative; bottom:10px;">Edited items will be displayed first in their category.</span><br />
<div class="clear"></div>
{if $req.info.product_feetype eq 'product' || $req.info.product_feetype eq '' || ($req.info.product_renewal_date < $cur_time)}
		
{include file='auto_product_save_list_normal.tpl'}

{else}

{include file='auto_product_save_list.tpl'}

{/if}
{if $req.select.pid}
{include file='auto_product_form.tpl'}
{/if}
</div>
{/if}

{if $req.select.options eq ''}
<div class="clear"></div>
<div style=" padding:10px 10px 10px 0; margin-top:10px; text-align:center;"><img src="/skin/red/images/auto-paid-screenshot.jpg" /></div>
{/if}

{if $req.info.hasitems}
<div style="float:right; padding:8px 0;">
<input name="image" type="image" class="submit" onclick="javascript:location.href='/auto/?act=product&op=next&step=4'" value="Continue to Next Step" src="/skin/red/images/bu-next.gif" />
        <!--<input name="image" type="image" class="submit"  onclick="javascript:location.href='/auto/?act=product&op=save&step=4'" value="Save And Exit" src="/skin/red/images/bu-exit.gif" />-->
</div>
{/if}

{else}
{include file='auto_product_year.tpl'}
{/if}

<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.php" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
