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
	if(obj.category.value=='0'){
		{/literal}errors += '-  {$lang.tt.category} is required.\n';{literal}
	}
	if(obj.property.value=='0'){
		{/literal}errors += '-  {$lang.tt.property} is required.\n';{literal}
	}
	if(obj.bedroom.value=='0' && obj.property.value!='8'){
		{/literal}errors += '-  {$lang.tt.bedroom} is required.\n';{literal}
	}
	if(obj.bathroom.value=='0' && obj.property.value!='8'){
		{/literal}errors += '-  {$lang.tt.bathroom} is required.\n';{literal}
	}
	if(obj.carspaces.value==''){
		{/literal}errors += '-  {$lang.tt.carspaces} is required.\n';{literal}
	}
	
	if(!obj.negotiable.checked && obj.category.value<4 && obj.price.value==''){
		{/literal}errors += '-  {$lang.tt.price} is required.\n';{literal}
	} else if(obj.price.value.replace(/(^\+?[\d]{1,}.[\d]{1,2})|(^\+?[\d]{1,})/gi, '') != ''){
		{/literal}errors += '-  {$lang.tt.price} is invalid.\n';{literal}
	}
	if( obj.category.value == '2' || obj.category.value == '3'){
		if(obj.priceMethod.value=='0'){
			errors += '-  Price Method is required.\n';
		}
	}
	
	if(obj.address.value==''){
		{/literal}errors += '-  {$lang.tt.street} is required.\n';{literal}
	}
	
	if(obj.sector_1.value=='-1'){
		{/literal}errors += '-  {$lang.tt.state} is required.\n';{literal}
	}
	if(obj.sector_2.value=='-1'){
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

function changeProperty(value) {
	if(value == '8') {
		$(".require_star").html('');
	} else {
		$(".require_star").html('*');
	}
}

function content2AddItem(){
	$('#content2Html').append('<textarea name="featureList[]" cols="80" rows="8" wrap="virtual" id="featureList[]" style="width:290px; height:40px; padding-left:5px; margin-bottom:2px;"></textarea><br/>');
	void(0);
}

function categorySelect(values){
	if ( values >= 4) {
		$('#priceItem').css('display','none');
		$('#pricNegotiable').css('display','none');
		values ==4 ? $('#auctionItem').css('display','') : $('#auctionItem').css('display','none') ;
	}else{
		values ==4 ? $('#auctionItem').css('display','') : $('#auctionItem').css('display','none') ;
		$('#priceItem').css('display','');
		$('#pricNegotiable').css('display','');
		
		if( values == 2 || values == 3){
			$('#priceMethodItem').css('display','');
		}else{
			$('#priceMethodItem').css('display','none');
		}
	}
}
function checkImport(obj){
	var boolResult = false;
	var errors	= '';
	if(!obj.csv){
		if($('#swf_csvmsg').val()==""){
			errors += '-  Products CSV is required.\n';
		}
		if($('#swf_imgmsg').val()==""){
			errors += '-  ZIP file of Products Images is required.\n';
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
//add loading
windowOnload(function (){ loadingOfCombox("#sector_1");});

{/literal}
</script>
{if $req.info.product_feetype eq 'product' || $req.info.product_feetype eq '' || ($req.info.product_renewal_date < $cur_time)}
<!--{if $req.info.product_feetype eq 'product' || $req.info.product_feetype eq '' || ($req.info.product_renewal_date < $cur_time)}
<p align="center" class="txt" style="padding-top:0; margin-top:0; margin-bottom:25px;">
	<a href="/estate/?act=product&cp=pay&feetype=year&step=4" title="Pay Fee" style="color:red;">
    Click here to pay $120 for one year with unlimited listing for your account.
    </a>
</p>
{else}
<p align="center" class="txt" style="padding-top:0; margin-top:0; margin-bottom:25px;">
	<a href="/estate/?act=product&cp=renew&feetype=year&step=4" title="Renew Fee" style="color:red;">
    Your account will be expired on {$req.info.product_renewal_date|date_format:"$PBDateFormat"}. If you want to list your properties <br />
    after this day, please click here to renew for your account.
    </a>
</p>
{/if}-->
<p align="center" class="txt"><font style="color:red;">{$req.msg}</font></p>
<p align="left" class="txt"><font style="color:#d21d3c; font-size:15px; font-weight:bold;">Please choose one of the following options: </font></p>
<div class="op_bg op_bg_year" onclick="javascript:location.href='/estate/?act=product&cp=pay&feetype=year&step=4'"><label class="op_bg_option_lab">1: List unlimited Real Estate for just $120 per year</label></div>
<a name="optionsadd"></a>
<div  class="op_bg op_bg_add{if $req.select.options eq 'add'}_select{/if}" onclick="javascript:location.href='/estate/?act=product&step=4{if $req.select.options neq 'add'}&options=add#optionsadd{/if}'"><label class="op_bg_option_lab{if $req.select.options eq 'add'}_select{/if}">2: List a single property for just $10 per month</label></div>
{if $req.select.options eq 'add'}
<div style="border:solid 1px #cccccc; padding:10px; min-height:1337px;">
{include file='estate_product_form.tpl'}
</div>
{/if}
<a name="optionsedit"></a>
<div class="op_bg op_bg_edit{if $req.select.options eq 'edit'}_select{/if}" onclick="javascript:location.href='/estate/?act=product&step=4{if $req.select.options neq 'edit'}&options=edit#optionsedit{/if}'"><label class="op_bg_option_lab{if $req.select.options eq 'edit'}_select{/if}">3: Edit existing listings</label></div>
{if $req.select.options eq 'edit'}
<div style="border:solid 1px #cccccc; padding:10px; {if $req.select.pid}min-height:1317px;{/if}">
<span style="text-align:left; vertical-align:middle; color:red; float:left; padding-right:20px;padding-top:15px; position: relative; bottom:10px;">Edited items will be displayed first in their category.</span><br />
<div class="clear"></div>
{if $req.info.product_feetype eq 'product' || $req.info.product_feetype eq '' || ($req.info.product_renewal_date < $cur_time)}
		
{include file='estate_product_save_list_normal.tpl'}

{else}

{include file='estate_product_save_list.tpl'}

{/if}
{if $req.select.pid}
{include file='estate_product_form.tpl'}
{/if}
</div>
{/if}

{if $req.select.options eq ''}
<div class="clear"></div>
<div style=" padding:10px 10px 10px 0; margin-top:10px; text-align:center;"><img src="/skin/red/images/estate-paid-screenshot.jpg" /></div>
{/if}

{if $req.info.hasitems}
<div style="float:right; padding:8px 0;">
<input name="image" type="image" class="submit" onclick="javascript:location.href='/estate/?act=product&op=next&step=4'" value="Continue to Next Step" src="/skin/red/images/bu-next.gif" />
        <!--<input name="image" type="image" class="submit"  onclick="javascript:location.href='/estate/?act=product&op=save&step=4'" value="Save And Exit" src="/skin/red/images/bu-exit.gif" />-->
</div>
{/if}

{else}
{include file='estate_product_year.tpl'}
{/if}