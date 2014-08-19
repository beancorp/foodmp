{if !$req.nofull}
{literal}
<script>
	function changeselect(obj){
		var j=0;
		for(var i=0;i<obj.options.length;i++){
			if(obj.options[i].selected&&obj.options[i].value!="6"){
				j++;
			}
		}
		if(j>=1){
			obj.options[5].selected = false;
		}
	}
	function changetypestate(obj){
		if(obj.value=='1'){
			document.getElementById('isstatebanner').style.display = "";
		}else{
			document.getElementById('isstatebanner').style.display = "none";
		}
	}
	function showstateselect(obj){
		if(obj.value=='1'){
			document.getElementById("stid").style.display="";
			document.getElementById("state_id").value="-1";
		}else{
			document.getElementById("stid").style.display="none";
			document.getElementById("state_id").value="-1";
		}
	}
	function checkFormval(obj){
		if(1 == obj.stateId.value) {
			if(!obj.stateId2.value) {
				alert('State is required.');
				return false;
			}
		}
		var datef = "{/literal}{$PBDateFormat}{literal}";
		var intstart = 0;
		var intend = 0;
		if(obj.start_date.value!=""){
			var arystart = obj.start_date.value.split('/');
			if(datef=="%m/%d/%Y"){
				var dates = new Date(arystart[2],arystart[0],arystart[1]);
			}else{
				var dates = new Date(arystart[2],arystart[1],arystart[0]);
			}
			intstart = dates.valueOf();
		}else{
			var nowd = new Date();
			var dates = new Date(nowd.getYear(),nowd.getMonth()+1,nowd.getDate());
			intstart = dates.valueOf();
		}
		if(obj.end_date.value!=""){
			var aryend = obj.end_date.value.split('/');
			if(datef=="%m/%d/%Y"){
				var dates = new Date(aryend[2],aryend[0],aryend[1]);
			}else{
				var dates = new Date(aryend[2],aryend[1],aryend[0]);
			}
			intend = dates.valueOf();
		}
		if(intend!=0){
			if(intend-intstart<0){
				alert("End date should be later than Start date.");
				return false;
			}
		}
	}
</script>
{/literal}
<link href="css/global.css" rel="stylesheet" type="text/css">
{/if}
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:800px; margin-bottom:15px;">
    <form action="/admin/?act=adv&cp=export" method="post" name="search_from" id="search_from"/>
    <table cellpadding="0" cellspacing="3">
    <colgroup>
    	<col width="100px"/>
        <col width="150px"/>
        <col width="20px" />
        <col width="100px"/>
        <col width="150px"/>
    </colgroup>
    	<tr><td>Start Date </td>
        	<td>
            	 <input type="text"  style="width:107px;" id="s_start_date" name="s_start_date" maxlength="12" value="{if $req.list.search.start_date gt 0}{$req.list.search.start_date|date_format:"$PBDateFormat"}{/if}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.search_from.s_start_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>
            </td>
            <td>&nbsp;</td>
            <td>End Date</td>
            <td>
            	 <input type="text"  style="width:107px;" id="s_end_date" name="s_end_date" maxlength="12" value="{if $req.list.search.end_date gt 0}{$req.list.search.end_date|date_format:"$PBDateFormat"}{/if}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.search_from.s_end_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>
            </td>
         </tr>
    
    	<tr><td>Banner Type </td>
        	<td><select name="sid" id="sid" style="width:145px" onChange="javascript: showstateselect(this);">
	<option value="" {if $req.list.search.sid eq '' }selected{/if}>Select Types</option>
	<option value="0" {if $req.list.search.sid eq '0' }selected{/if}>All States Banner</option>
	<option value="-1" {if $req.list.search.sid eq '-1' }selected{/if}>Default Banner</option>
    <option value="1" {if $req.list.search.sid eq '1' }selected{/if}>State Banner</option>
	</select>
            </td>
            <td>&nbsp;</td>
            <td colspan="2"><div id="stid" style="{if $req.list.search.sid neq '1'}display:none{/if}"><div style="width:103px; height:18px;+height:21px; +line-height:21px; float:left;">Banner State</div><select name="state_id" id="state_id" style="width:145px; float:left">
    	<option value="-1" {if $req.list.search.sid2 eq '-1' }'selected'{/if}>Select State</option>
	{foreach from=$req.list.stateList item=l}
	<option value="{$l.id}" {if $req.list.search.sid2 eq $l.id }selected{/if} title="{$l.description} ({$l.stateName})">{$l.description} ({$l.stateName})</option>
    {/foreach}
    		</select></div>
            </td>
        </tr>
        <tr>
        	<td>{$lang.banner.lb_seller}</td>
            <td>
			
			<select name="search_markets" id="search_markets" style="width:145px">
                <option value="">Select Type</option>
                <option value="State" {if $req.list.search.markets eq 'State' }selected{/if}>Buy & Sell</option>
                <option value="Estate" {if $req.list.search.markets eq 'Estate' }selected{/if}>Real Estate</option>
                <option value="Auto" {if $req.list.search.markets eq 'Auto' }selected{/if}>Automotive</option>
                <option value="Job" {if $req.list.search.markets eq 'Job' }selected{/if}>Job Market</option>
                <option value="FoodWine" {if $req.list.search.markets eq 'FoodWine' }selected{/if}>Food & Wine</option>
			</select>
			
			</td>
            <td>&nbsp;</td>
            <td colspan="2"><input type="button" value="Search" class="hbutton" onclick="javascript:xajax_getBannerAllAndDefaultListSearch2(xajax.getFormValues('search_from'));"/>&nbsp;<input type="submit" value="Export" class="hbutton"/>&nbsp;<input type="button" class="hbutton" name="submitButton" value=" {$lang.but.add} " onclick="javascript: xajax_addBannerAllAndDefault(xajax.getFormValues('mainForm'));"></td>
        </tr>
        <tr>
        	<td align="center" colspan="5">{$lang.banner.note}</td>
        </tr>
    </table>
    </form>
    
    
    
	</div>
	<form id="mainForm" name="mainForm" enctype="multipart/form-data" method="post" action="" onsubmit="return checkFormval(this);">
	<div id="tabledatalist" class="wrap">
	{/if}
		<ul id="table" style="width:950px;">
        <li class="tabletop" style="width:100px;">Market Place</li>
		<li class="tabletop" style="width:60px;">Banner</li>
		<li class="tabletop" style="width:100px;">{$lang.banner.lb_link}</li>
		<li class="tabletop" style="width:150px;">{$lang.banner.lb_view}</li>
		
		<li class="tabletop" style="width:85px;">Start Date</li>
		<li class="tabletop" style="width:85px;">End Date</li>
		<li class="tabletop" style="width:100px;">{$lang.banner.lb_desc}</li>
        <li class="tabletop" style="width:40px;">View</li>
        <li class="tabletop" style="width:40px;">Click</li>
		<li class="tabletop" style="width:100px;">{$lang.banner.lb_operate}</li>
		<div style="clear:both;"></div>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		<li style="width:100px;height:275px; text-align:justify">{$lang.seller[$l.markets]}</li>
        
		<li style="width:60px;height:275px; text-align:left">{if $l.state_id eq '0'}All{elseif $l.state_id eq '-1'}Default{else}State{/if}</li>
		
		<li style="width:100px;height:275px;"><a href='{$l.banner_link}' target=_blank>{$l.banner_link}</a></li>
		<li style="width:150px;height:275px;">{if $l.banner_link}<a href='{$l.banner_link}' target="_blank" title="{$l.description}">{/if}{$l.banner_img}{if $l.banner_link}</a>{/if}</li>
		<li class="" style="width:85px;height:275px;">{$l.start_format}</li>
		<li class="" style="width:85px;height:275px;">{$l.end_format}</li>
		<li style="width:100px;height:275px;">{$l.description}</li>
		<li style="width:40px; height:275px;">{$l.view_times}</li>
        <li style="width:40px; height:275px;">{$l.click}</li>
		<li style="width:100px;height:275px;"><input name="button1" type="button" class="hbutton" value="{$lang.but.edit}"  onclick="javascript:xajax_editBannerAllAndDefault('{$l.banner_id}',xajax.getFormValues('mainForm'));">&nbsp;<input name="button2" type="button" class="hbutton" value="{$lang.but.delete}" onclick="javascript:if(confirm('{$lang.pub_clew.delete}')) xajax_deleteBannerAllAndDefault({$l.banner_id});"></li>
		<div style="clear:both;"></div>
		{/foreach}
		<li style="width:950px; height:30px; background:#ffffff;">{$req.list.pager.linksAll}</li>
		{else}
		<li style="width:950px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
     <input name="searchparam" type="hidden" id="searchparam" value='{$req.list.search.searchparam}' />
	<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
	{if !$req.nofull }	</div>
	</form>
	</div>
	{/if}
	
{elseif $req.display eq 'edit'}

	 <input name="searchparam" type="hidden" id="searchparam" value='{$req.list.search.searchparam}' />
	<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
	<div id="input-table">
	<ul style="width:720px;">
	<li id="lable" style="width:20%;">{$lang.banner.lb_seller}</li>
	<li id="input" style="width:78%;">
	<select name="displaypage" id="seller" style="width:138px">
	<option value="State" {if $req.list.markets eq 'State' }selected{/if}>Buy & Sell</option>
	<option value="Estate" {if $req.list.markets eq 'Estate' }selected{/if}>Real Estate</option>
	<option value="Auto" {if $req.list.markets eq 'Auto' }selected{/if}>Automotive</option>
	<option value="Job" {if $req.list.markets eq 'Job' }selected{/if}>Job Market</option>
	<option value="FoodWine" {if $req.list.markets eq 'FoodWine' }selected{/if}>Food & Wine</option>
	</select>
	</li>
	<li id="lable" style="width:20%;">{$lang.banner.lb_type}</li>
	<li id="input" style="width:78%;">
	<select name="stateId" style="width:238px" id="stateId" onchange="changetypestate(this);">
		<option value="0" {if $req.list.state_id eq '0'}selected{/if}>All States Banner</option>
		<option value="-1"  {if $req.list.state_id eq '-1' }selected{/if}>Default Banner</option>
        <option value="1"  {if $req.list.state_id neq '0' and $req.list.state_id neq '-1'}selected{/if}>State Banner</option>
	</select>
	</li>
	<div id="isstatebanner" {if $req.list.state_id neq '0' and $req.list.state_id neq '-1'}style="display:block;"{else}style="display:none;"{/if}>
    <li id="lable" style="width:20%;">{$lang.banner.lb_state}</li>
	<li id="input" style="width:78%; height:150px;">
	<select name="stateId2[]" style="width:238px" id="stateId2" multiple="multiple" size="9">
		{foreach from=$req.list.stateList item=l}
		<option value="{$l.id}" {inarray arrValue=$req.list.state_id|explode value=$l.id return="selected"} title="{$l.description} ({$l.stateName})">{$l.description} ({$l.stateName})</option>
		{/foreach}
	</select>
	</li>
    </div>
	
	<li id="lable" style="width:20%; height:50px;"></li>
	<li id="input" style="width:78%; height:50px; text-align:left;">
	  (banner size must be 160px * 250px)	<br/>
    </li>
	 
    <li id="lable" style="width:20%;">Start Date</li>
    <li id="input" style="width:78%;">
    <input type="text"  style="width:80px;" id="start_date" name="start_date" maxlength="12" value="{$req.list.start_date|date_format:"$PBDateFormat"}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.mainForm.start_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>
    </li>
     <li id="lable" style="width:20%;">End Date</li>
    <li id="input" style="width:78%;">
     <input type="text"  style="width:80px;" id="end_date" name="end_date" maxlength="12" value="{if $req.list.end_date}{$req.list.end_date|date_format:"$PBDateFormat"}{/if}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.mainForm.end_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>
    </li>
     
	<li id="lable" style="width:20%;">{$lang.banner.lb_file}</li>
	<li id="input" style="width:78%;">
	<input type="file" name="bannerFile" id="bannerFile" size="30" style="width:238px" /></li>
	
	<li id="lable" style="width:20%;">Image File(if flash)</li>
	<li id="input" style="width:78%;">
	<input type="file" name="bannerFile_img" id="bannerFile_img" size="30" style="width:238px" /></li>
	
	<li id="lable" style="width:20%;">{$lang.banner.lb_link}</li>
	<li id="input" style="width:78%;"><input name="bannerLink" value="{$req.list.banner_link}" id="subject" style="width:440px" size="30" /></li>
	
	<li id="lable" style="width:20%; height:100px;">{$lang.banner.lb_desc}</li>
	<li id="input" style="width:78%; height:100px;"><textarea name="message" cols="60" rows="5" id="message">{$req.list.description}</textarea></li>
	<li id="lable" style="width:20%;"></li>
	<li id="input" style="width:78%;"><input name="submit" type="submit" class="hbutton" id="submit" value=" {$lang.but.submit} " /> <input type="button" class="hbutton" value=" {$lang.but.back} " onclick="javascript: location.href='/admin/?act=adv&cp=allbanner';" />
	  <input name="banner_id" type="hidden" id="banner_id" value="{$req.list.banner_id}"/>
	</li>
	</ul>
</div>
{/if}
{if !$req.nofull }
<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>{/if}

{literal}
<script type="text/javascript">
		 function flashChecker()
{
    var hasFlashPlayer = false;

    if (window.navigator.userAgent.indexOf('MSIE') > -1) {
        if (new ActiveXObject('ShockwaveFlash.ShockwaveFlash')) {
            hasFlashPlayer = true;
        }
    } else {
        if (window.navigator.plugins['Shockwave Flash'] != null) {
            hasFlashPlayer = true;
        }
    }
	
    return hasFlashPlayer;
}


	replaceFlash();
	
	function replaceFlash()
	{
		if(!flashChecker()) {
			$("div[id^=__Flash__]").each(function(o) {
				replaceImage = $(this).attr('replace_img');
				title = $(this).attr('title');
				if(replaceImage) {
					$(this).html('<img src="/upload/new/' + replaceImage + '" alt="' + title + '" title="' + title + '" width="160" height="250" border="0" />');
				}
				
		})
		}
	}
</script>
{/literal}