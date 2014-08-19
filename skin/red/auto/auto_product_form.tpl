<form name="mainForm" id="mainForm" method="post" action="" onsubmit="javascript:return checkSubmit(this);">
<input name="pid" id="pid" type="hidden" value="{$req.select.pid}" />
<input name="step" type="hidden" value="{$req.select.step}" />
<input name="op" type="hidden" value="{$req.select.op}"/>
{if $req.select.pid}
<input name="carType" type="hidden" value="{$req.select.carType}" />
<input name="make" type="hidden" value="{$req.select.make}"/>
<input name="model" type="hidden" value="{$req.select.model}"/>
<input name="year" type="hidden" value="{$req.select.year}"/>
{/if}
<fieldset id="uploadproduct">
<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="25%" height="30" align="right">{$lang.tt.itemname} *&nbsp;</td>
    <td width="75%">
        <input name="item_name" type="text" class="inputB" id="item_name" size="30" maxlength="100" value="{$req.select.item_name}">	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.category} &nbsp;</td>
    <td>
    {foreach from=$lang.val.category item=l key=k}
	<input type="radio" id="category" name="category" value="{$k}" {if $req.select.category eq $k}checked{/if}> {$l}
	{/foreach}	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.type} *&nbsp;</td>
    <td>
	<select name="carType" class="inputB" id="sector_1" onchange="{$req.element.jsSector}" style="width:287px;"  {if $req.select.pid}disabled="disabled"{/if}>
	{foreach from=$req.carTypeList item=l key=k}
	<option value="{$l.id}" {if $req.select.carType eq $l.id}selected{/if}>{$l.name}</option>
	{/foreach}
    </select>
	</td>
  </tr>
  <tr>
    <td height="30" align="right" >{$lang.tt.make} *&nbsp;</td>
    <td>
	<select name="make" class="inputB" id="sector_2" onchange="{$req.element.jsSector};check_make2(this.value);" style="width:287px;" {if $req.select.pid}disabled="disabled"{/if}>
	{foreach from=$req.sector item=l key=k}
	<option value="{$l.id}" {if $req.select.make eq $l.id}selected{/if}>{$l.name}</option>
	{/foreach}
    </select>
	<div style="display:{if $req.select.makeUser eq ''}none{/if}; padding-top:3px;" id="custom_make2">
		<input name="makeUser" type="text" class="inputB" id="makeUser" maxlength="100" value="{$req.select.makeUser|default:'Please enter the make here'}">
	</div>
	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.model} *&nbsp;</td>
    <td>
	<select name="model" id="sector_3" class="inputB" style="width:287px;" onchange="check_model2(this.value);" {if $req.select.pid}disabled="disabled"{/if}>
    {foreach from=$req.subSector item=l key=k}
	<option value="{$l.id}" {if $req.select.model eq $l.id }selected{/if}>{$l.name}</option>
	{/foreach}
	</select>
	<div style="display:{if $req.select.modelUser eq ''}none{/if}; padding-top:3px;" id="custom_model2">
		<input name="modelUser" type="text" class="inputB" id="modelUser2" maxlength="100" value="{$req.select.modelUser|default:'Please enter the model here'}">
	</div> 	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.year} *&nbsp;</td>
    <td>
	<select name="year" id="year" class="inputB" style="width:112px" {if $req.select.pid}disabled="disabled"{/if}>
	<option value="0">Select a Year</option>
	{foreach from=$lang.val.year item=l key=k}
	<option value="{$k}" {if $req.select.year eq $k}selected{/if}>{$l}</option>
	{/foreach}
    </select>	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.kms} *&nbsp;</td>
    <td><input name="kms" type="text" class="inputB" id="kms" maxlength="11" style="width:100px" value="{$req.select.kms}"></td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.door} *&nbsp;</td>
    <td>
	<select name="door" id="door" class="inputB" style="width:112px">
	<option value="-1">Select a Door</option>
	{foreach from=$lang.val.door item=l key=k}
	<option value="{$k}" {if $req.select.door eq $k}selected{/if}>{$l}</option>
	{/foreach}
    </select>	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.seat} *&nbsp;</td>
    <td>
	<select name="seat" id="seat" class="inputB" style="width:112px">
	<option value="0">Select a Seat</option>
	{foreach from=$lang.val.seat item=l key=k}
	<option value="{$k}" {if $req.select.seat eq $k}selected{/if}>{$l}</option>
	{/foreach}
    </select>	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.pattern} *&nbsp;</td>
    <td>
	<select name="pattern" id="pattern" class="inputB" style="width:112px">
	<option value="0">Select a Vehicle Type</option>
	{foreach from=$lang.val.pattern item=l key=k}
	<option value="{$k}" {if $req.select.pattern eq $k}selected{/if}>{$l}</option>
	{/foreach}
    </select>	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.transmission} &nbsp;</td>
    <td>
	{foreach from=$lang.val.transmission item=l key=k}
	<input type="radio" id="transmission" name="transmission" value="{$k}" {if $req.select.transmission eq $k}checked{/if}> {$l}
	{/foreach}	</td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.color} &nbsp;</td>
    <td><input name="color" type="text" class="inputB" id="color" maxlength="30" style="width:100px" value="{$req.select.color}"></td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.regNo} &nbsp;</td>
    <td><input name="regNo" type="text" class="inputB" id="regNo" maxlength="10" style="width:100px" value="{$req.select.regNo}"></td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.regExpired} &nbsp;</td>
    <td><input name="regExpired" type="text" class="inputB" id="regExpired" maxlength="25" style="width:100px" value="{$req.select.regExpired}" readonly="readonly">
	<a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.mainForm.regExpired);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" alt="" /></a>	</td>
  </tr>
  
  <tr>
    <td height="30" align="right">{$lang.tt.price} *&nbsp;</td>
    <td><input name="price" type="text" class="inputB" id="price" maxlength="12" style="width:100px;float:left;" value="{$req.select.price}">
	<span style="float:left;margin:4px 0 0 2px;"><font face="Verdana" size="1"><span><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;">Eg 12000.00<br />In the price field, enter decimal point only.</span></span></a></span></font></span>
	<span id="pricNegotiable" style="float: left; padding-top:5px; +padding-top:4px;   padding-left:4px;"><input name="negotiable" id="negotiable" type="checkbox" value="1" {if $req.select.negotiable eq '1'}checked{/if}/> {$lang.tt.negotiable}</span>
	</td>
  </tr>
  
  <tr>
    <td height="30" align="right" valign="top">{$lang.tt.street} &nbsp;</td>
    <td >
		<textarea style="width:280px; padding-left:5px; height:40px;" name="address" id="location1" >{$req.select.location}</textarea>
	</td>
  </tr>

  <tr>
    <td height="30" align="right">{$lang.tt.state} *&nbsp;</td>
    <td>
	<select name="state" class="inputB" id="suburb_1" onchange="{$req.element.jsSuburb};$('#postcode').val('');" style="width:287px;">
	{foreach from=$req.state item=l key=k}
	<option value="{$l.id}" {if $req.select.state eq $l.id}selected{/if}>{$l.name}</option>
	{/foreach}
    </select>	</td>
  </tr>
  
  <tr>
    <td height="30" align="right">{$lang.tt.suburb} *&nbsp;</td>
    <td>
	<select name="suburb" class="inputB" id="suburb_2" onchange="javascript:$('#postcode').val('');" style="width:287px;">
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
    <td valign="top"><textarea name="content" class="inputB"  style="height:220px;">{$req.select.content}</textarea></td>
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
  
<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td height="30" width="25%" align="right">{$lang.tt.featured} &nbsp;</td>
    <td width="75%"><input name="featured" type="checkbox" id="featured" value="1" {if $req.select.featured eq '1'}checked{/if}></td>
  </tr>
  <tr>
    <td height="30" align="right">{$lang.tt.enabled} &nbsp;</td>
    <td><input name="enabled" type="checkbox" id="enabled" value="1" {if $req.select.enabled eq '1'}checked{/if}></td>
  </tr>
   <tr valign="top">
    <td height="30" align="right">Youtube Video </td>
    <td><textarea class="text" name="youtubevideo" style="width:266px; height:60px;padding-left:5px; margin-bottom:2px;">{$req.select.youtubevideo}</textarea>&nbsp;<span class="style11"><a  target="_blank" href="/soc.php?cp=youtubeinstruction"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /></a></span></td>
  </tr>
  <tr height="60"><td>&nbsp;</td><td>
  			{if (empty($req.select.pid) && ($req.info.product_feetype eq 'product' || $req.info.product_feetype eq '' || ($req.info.product_renewal_date < $cur_time))) || (!empty($req.select.pid) && ($req.select.pay_status eq '0' || ($req.select.pay_status eq '1' && $req.select.renewal_date < $cur_time)))}
            
		    <input name="SubmitPic" type="image" src="/skin/red/images/bu-paylater.gif" class="input-none-border" onclick="javascript:document.mainForm.op.value='paylater';" value="Save & Pay Later" border="0" style="border:none"/>
		    <input name="SubmitPic" type="image" src="/skin/red/images/bu-paynow.gif" class="input-none-border" onclick="javascript:document.mainForm.op.value='paynow';" value="Pay Now" border="0" style="border:none"/>
            {else}
		    <input name="SubmitPic" type="image" src="/skin/red/images/bu-savetowebsite.gif" class="input-none-border" onclick="javascript:document.mainForm.op.value='edit';" value="Save to My Website" border="0" style="border:none"/>
            {/if}
  </td></tr>
</table>
</fieldset>

<fieldset id="uploadimages" style=" width:250px; padding-left:30px;display:block;">
{include file='auto_product_save_picture.tpl'}
</fieldset>

</form>

{if $req.select.options eq 'add'}
<fieldset id="bulkupload">
     <table style="width:100%;">
		  <tr>
			 <td colspan="2">
			 <table width="100%" border="0" cellspacing="10" cellpadding="0">
			 <form action="" method="post" enctype="multipart/form-data" name="bulk" id="bulk" onsubmit="return checkImport(this)">
			 	<input name="op" type="hidden" value="upload"/>
				<tr><td height="2" colspan="2" bgcolor="#293694"></td>
			 </tr>
			  <tr>
			    <td width="22%">&nbsp;</td>
			    <td width="78%" >Bulk Product Import</td>
			  </tr>
			  <tr>
			    <td align="right">Products Information </td>
			    <td><input name="csv" type="file" id="csv" class="inputB"  style="width:200px;" />
			      &nbsp; <a href="/pdf/sample_auto.zip" title="Please download and extract the zip file." target="_blank">Sample</a></td>
			  </tr>
			  <tr>
			    <td align="right">Products Images </td>
			    <td><input name="image" type="file" id="image" class="inputB"  style="width:200px;" />
			      &nbsp; <a href="/pdf/Vehicle_Bulk Upload Process_AUS.pdf" target="_blank">Setup Process</a> </td>
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
			  <tr><td height="2" colspan="2"></td>
			 </tr>
			  </form>
			</table></td>
		 </tr>
		 <!--<tr>
		 <td colspan="2" align="right"><input class="submit" type="image" src="/skin/red/images/bu-continue.gif" value="Continue to Next Step" onclick="document.mainForm.op.value='next'; if (continueNextStep()) document.mainForm.submit(); void(0);" />
        <input class="submit" type="image" src="/skin/red/images/bu-exit.gif" value="Save And Exit"  onclick="document.mainForm.op.value='save'; if (continueNextStep()) document.mainForm.submit(); void(0);" /></td>
		 </tr>-->   
      </table>
      </fieldset>
{/if}

<h3 style="height:1px; padding:0; margin:0;">&nbsp;</h3>