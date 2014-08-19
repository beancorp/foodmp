<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />

{include_php file="jssppopup.php"}

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
	if(obj.bedroom.value=='0'){
		{/literal}errors += '-  {$lang.tt.bedroom} is required.\n';{literal}
	}
	if(obj.bathroom.value=='0'){
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

<p align="center" class="txt"><font style="color:red;">{$req.msg}</font></p>
<form name="mainForm" id="mainForm" method="post" action="" onsubmit="javascript:return checkSubmit(this);">
<input name="pid" id="pid" type="hidden" value="{$req.select.pid}" />
<input name="step" type="hidden" value="{$req.select.step}" />
<input name="op" type="hidden" value="{$req.select.op}"/>
<fieldset id="uploadproduct">
<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="25%" height="30" align="right">{$lang.tt.itemname} *&nbsp;</td>
    <td width="75%">
        <input name="item_name" type="text" class="inputB" id="item_name" size="30" maxlength="100" value="{$req.select.item_name}">	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.category} *&nbsp;</td>
    <td>
    <select name="category" id="category" class="inputB" style="width:287px" onchange="javascript:categorySelect(this.value);">
	<option value="0">Select a Type of Sale</option>
	{foreach from=$lang.val.category item=l key=k}
	<option value="{$k}" {if $req.select.category eq $k}selected{/if}>{$l}</option>
	{/foreach}
    </select>	</td>
  </tr>
  
  <tr id="auctionItem" style="{if $req.select.category neq 4}display:none;{/if}">
    <td height="30" align="right">{$lang.tt.auction} &nbsp;</td>
    <td><input name="auction" type="text" class="inputB" id="auction"  value="{$req.select.auction}" size="30" maxlength="100"></td>
  </tr>
  
  <tr>
    <td height="30" align="right">{$lang.tt.property} *&nbsp;</td>
    <td>
    <select name="property" id="property" class="inputB" style="width:287px">
	<option value="0">Select a Type of Property</option>
	{foreach from=$lang.val.property item=l key=k}
	<option value="{$k}" {if $req.select.property eq $k}selected{/if}>{$l}</option>
	{/foreach}
    </select>	</td>
  </tr>
  

   <tr>
    <td height="30" align="right">{$lang.tt.bedroom} *&nbsp;</td>
    <td>
    <select name="bedroom" id="bedroom" class="inputB" style="width:150px">
	<option value="0">Select a Bedrooms</option>
	{foreach from=$lang.val.bedroom item=l key=k}
	<option value="{$k}" {if $req.select.bedroom eq $k}selected{/if}>{$l}</option>
	{/foreach}
    </select>	</td>
  </tr>
  
  <tr>
    <td height="30" align="right">{$lang.tt.bathroom} *&nbsp;</td>
    <td>
    <select name="bathroom" id="bathroom" class="inputB" style="width:150px">
	<option value="0">Select a Bathrooms</option>
	{foreach from=$lang.val.bathroom item=l key=k}
	<option value="{$k}" {if $req.select.bathroom eq $k}selected{/if}>{$l}</option>
	{/foreach}
    </select>	</td>
  </tr>

  <tr>
    <td height="30" align="right">{$lang.tt.carspaces} *&nbsp;</td>
    <td>
    <select name="carspaces" id="carspaces" class="inputB" style="width:150px">
	<option value="">Select a Car Spaces</option>
	{foreach from=$lang.val.carspaces item=l key=k}
	<option value="{$k}" {if $req.select.carspaces eq $k}selected{/if}>{$l}</option>
	{/foreach}
    </select>	</td>
  </tr>

   <tr>
    <td height="30" align="right">{$lang.tt.inspect} &nbsp;</td>
    <td><input name="inspect" type="text" class="inputB" id="inspect" maxlength="45" value="{$req.select.inspect}" /></td>
  </tr>
  
   <tr id="priceItem" style="{if $req.select.category >= 4 }display:none;{/if}">
    <td height="30" align="right">{$lang.tt.price} * &nbsp;</td>
    <td><input name="price" type="text" class="inputB" id="price" maxlength="12" style="width:100px;float:left;" value="{$req.select.price}">
	<span style="float:left;margin:4px 0 0 2px;"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Eg 12000.00<br />In the price field, enter decimal point only.</span></span></a></span></font></span>
	<span id="priceMethodItem" style="float:left; height:26px; padding-top:0px; padding-left:4px;{if $req.select.category <= 1}display:none;{/if}"><select id="priceMethod" name="priceMethod" style="width:75px; +width:72px; height:26px; margin:0px; +margin-top:3px;">
	<option value="0">Select Method</option>
	{foreach from=$lang.val.priceMethod item=l key=k}<option value="{$k}" {if $req.select.priceMethod eq $k}selected{/if}/>{$l}</option> {/foreach}</select></span>
	
	<span id="pricNegotiable" style="float:left; padding-top:5px; +padding-top:4px; padding-left:4px;{if $req.select.category >= 4 }display:none;{/if}"><input name="negotiable" id="negotiable" type="checkbox" value="1" {if $req.select.negotiable eq '1'}checked{/if}/> {$lang.tt.negotiable}</span>	</td>
  </tr>

   <tr>
    <td height="30" align="right">{$lang.tt.council} &nbsp;</td>
    <td><input name="council" type="text" class="inputB" id="council" maxlength="40" style="width:100px;float:left;" value="{$req.select.council}"> <label style=" float:left;margin:6px 0 0 1px; padding:0; width:70px; height:21px;"></label></td>
  </tr>

   <tr>
    <td height="30" align="right">{$lang.tt.water} &nbsp;</td>
    <td><input name="water" type="text" class="inputB" id="water" maxlength="40" style="width:100px;float:left;" value="{$req.select.water}"> <label style=" float:left;margin:6px 0 0 1px; padding:0; width:70px; height:21px;"></label> </td>
  </tr>
  
   <tr>
    <td height="30" align="right">{$lang.tt.strata} &nbsp;</td>
    <td><input name="strata" type="text" class="inputB" id="strata" maxlength="40" style="width:100px; float:left;" value="{$req.select.strata}"> <label style=" float:left;margin:6px 0 0 1px; padding:0; width:70px; height:21px;"></label> </td>
  </tr>

  <tr>
    <td height="30" align="right" valign="top">{$lang.tt.street} *&nbsp;</td>
    <td >
		<textarea style="width:280px; padding-left:5px; height:40px;" name="address" id="location1" >{$req.select.location}</textarea>
	</td>
  </tr>

  <tr>
    <td height="30" align="right">{$lang.tt.state} *&nbsp;</td>
    <td>
	<select name="state" class="inputB" id="sector_1" onchange="{$req.element.jsSector};$('#postcode').val('');" style="width:287px;">
	{foreach from=$req.state item=l key=k}
	<option value="{$l.id}" {if $req.select.state eq $l.id}selected{/if}>{$l.name}</option>
	{/foreach}
    </select>	</td>
  </tr>
  
  <tr>
    <td height="30" align="right">{$lang.tt.suburb} *&nbsp;</td>
    <td>
	<select name="suburb" class="inputB" id="sector_2" onchange="javascript:$('#postcode').val('');" style="width:287px;">
	{foreach from=$req.suburb item=l key=k}
	<option value="{$l.id}" {if $req.select.suburb eq $l.id}selected{/if}>{$l.name}</option>
	{/foreach}
    </select></td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.postcode} *&nbsp;</td>
    <td><input name="postcode" id="postcode" type="text" class="inputB" maxlength="10" style="width:100px" value="{$req.select.postcode}"></td>
  </tr>

  <tr>
    <td height="206" align="right" valign="top">{$lang.tt.content} &nbsp;</td>
    <td valign="top"><textarea name="content" class="inputB"  style="height:220px;">{$req.select.content}</textarea>
    </td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="25%" align="right" valign="top">{$lang.tt.featureList} &nbsp;</td>
    <td width="75%" valign="top"><samp id="content2Html" style="margin-bottom:6px;">
	{foreach from=$req.select.featureList item=l key=k}
	<textarea name="featureList[]" cols="80" rows="8" wrap="virtual" id="featureList[]" style="width:290px; height:40px;padding-left:5px; margin-bottom:2px;">{$l}</textarea><br />
	{foreachelse}
	<textarea name="featureList[]" cols="80" rows="8" wrap="virtual" id="featureList[]" style="width:290px; height:40px;padding-left:5px; margin-bottom:2px;"></textarea><br />
	{/foreach}</samp>
	<span style=" clear:both; margin-top:5px;">&nbsp;&nbsp;
	<a href="javascript:content2AddItem();">Add Feature</a><br /><br />
	</span>
	</td>
  </tr>
</table>
 
<table width="100%"  border="0" cellspacing="1" cellpadding="2" {if $smarty.session.attribute eq 1 && $smarty.session.subAttrib eq 1}style="display:none;"{/if}>
	<tr>
		<td width="25%" align="right" headers="25"><strong>{$lang.labelCoagent}</strong></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="25%" align="right">{$lang.labelName}&nbsp;</td>
		<td colspan="2"><input name="coname" type="text" class="inputB" style="width:285px; margin-bottom:2px;" value="{$req.select.coname}" maxlength="245" /></td>
	</tr>
	<tr>
		<td width="25%" align="right" valign="top">{$lang.labelAddress}&nbsp;</td>
		<td colspan="2"><textarea name="coaddress" cols="30" wrap="virtual" style="width:290px; height:60px;padding-left:5px; margin-bottom:2px;">{$req.select.coaddress}</textarea></td>
	</tr>
	<tr>
		<td align="right">{$lang.labelPhone}&nbsp;</td>
		<td colspan="2"><input name="cophone" type="text" class="inputB" style="width:285px; margin-bottom:2px;" value="{$req.select.cophone}" size="30" maxlength="21" /></td>
	</tr>
	<tr>
	    <td width="25%" align="right" headers="25">&nbsp;</td>
	    <td colspan="2">&nbsp;</td>
	</tr>
</table>
 
  
<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="25%" height="30" align="right">{$lang.tt.featured} &nbsp;</td>
    <td width="75%"><input name="featured" type="checkbox" id="featured" value="1" {if $req.select.featured eq '1'}checked{/if}></td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.enabled} &nbsp;</td>
    <td><input name="enabled" type="checkbox" id="enabled" value="1" {if $req.select.enabled eq '1'}checked{/if}></td>
  </tr>
  <tr>
    <td height="30" align="right">Solded &nbsp;</td>
    <td><input name="solded" type="checkbox" id="solded" value="1" {if $req.select.solded eq '1'}checked{/if}></td>
  </tr>
  
  <tr valign="top">
    <td height="30" align="right">Youtube Video </td>
    <td><textarea class="text" name="youtubevideo" style="width:266px; height:60px;padding-left:5px; margin-bottom:2px;">{$req.select.youtubevideo}</textarea>&nbsp;<span class="style11"><a href="/soc.php?cp=youtubeinstruction" target="_blank"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /></a></span></td>
  </tr>
  <tr><td>&nbsp;</td><td></td></tr>
</table>
</fieldset>

<fieldset id="uploadimages" style="border-left:solid 1px #eee; width:250px; padding-left:30px;display:block;">
{include file='estate_product_save_picture.tpl'}
</fieldset>

</form>

<fieldset id="bulkupload" style="width:100%;">
     <table style="width:100%;" cellspacing="0" cellpadding="0" align="left">
		  <tr>
			 <td colspan="2">
			 <table width="100%" border="0" cellspacing="10" cellpadding="0">
			 <form action="" method="post" enctype="multipart/form-data" name="bulk" id="bulk" onsubmit="return checkImport(this)">
			 	<input name="op" type="hidden" value="upload"/>
				<tr><td height="2" colspan="2" bgcolor="#293694"></td>
			 </tr>
			  <tr>
			    <td width="22%">&nbsp;</td>
			    <td width="78%" ><h3 style="font-size:16px; color:#666; font-weight:bold;">Bulk Upload Properties</h3></td>
			  </tr>
			  <tr>
			    <td align="right">Products CSV file </td>
			    <td><table cellpadding="0" cellspacing="0">
                	<tr>
                    	<td><input name="csv" type="file" id="csv" class="inputB"  style="width:200px;" /></td>
                        <td>
			      		&nbsp; <a href="/pdf/sample_estate.csv" target="_blank">Sample Product CSV</a><br/>
                  		&nbsp; <a href="/pdf/images_estate.zip" target="_blank">Sample ZIP file of Product Images</a>
                  		</td></tr></table></td>
			  </tr>
			  <tr>
			    <td align="right">ZIP file of Product Images </td>
			    <td><table cellpadding="0" cellspacing="0">
                	<tr>
                    	<td><input name="image" type="file" id="image" class="inputB"  style="width:200px;" /></td>
                        <td>
			      &nbsp; <a href="/soc.php?cp=propertiesinstru" target="_blank">Step by step help on using bulk upload</a> </td></tr></table></td>
			  </tr>
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
		 <tr>
		 <td align="right" style="padding-right:10px;"><input name="image" type="image" class="submit" onclick="document.mainForm.op.value='next'; if (continueNextStep()) document.mainForm.submit(); void(0);" value="Continue to Next Step" src="/skin/red/images/bu-continue.gif" />
        <input name="image" type="image" class="submit"  onclick="document.mainForm.op.value='save'; if (continueNextStep()) document.mainForm.submit(); void(0);" value="Save And Exit" src="/skin/red/images/bu-exit.gif" />
    </td>
		 </tr>   
      </table>
      </fieldset>

<h3 style="font-size:16px; color:#666; font-weight:bold;">Your Products
      	<span style="text-align:right; vertical-align:middle; color:red; float:right; padding-right:20px;padding-top:10px; position: relative; bottom:10px;">Edited items will be displayed first in their category.</span>
</h3>
		
{include file='estate_product_save_list.tpl'}


