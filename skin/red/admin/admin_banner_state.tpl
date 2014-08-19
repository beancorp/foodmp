<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.display eq ''}
	{if !$req.nofull}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
	<div id="input-table" style="width:800px;">
	<ul>
	<li id="input2" style="width:400px;height:35px;"><input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.add} " onclick="javascript: xajax_addBannerState(xajax.getFormValues('mainForm'));"> &nbsp; 
	<select name="sid" id="sid" style="width:145px" onChange="javascript: xajax_getBannerStateSearch(this.options[this.selectedIndex].value,xajax.$('po').value);">
	<option value="" {if $req.list.search.sid eq '' }'selected'{/if}>Select State</option>
	{foreach from=$req.list.stateList item=l}
	<option value="{$l.id}" {if $req.list.search.sid eq $l.id }selected{/if} title="{$l.description} ({$l.stateName})">{$l.description} ({$l.stateName})</option>
	{/foreach}
	</select> &nbsp; 
	<select name="po" id="po" style="width:138px" onChange="javascript: xajax_getBannerStateSearch(xajax.$('sid').value,this.options[this.selectedIndex].value);">
		<option value="" {if $req.list.search.po eq '' }selected{/if}>Select Position</option>
		<option value="0" {if $req.list.search.po eq '0' }selected{/if}>Down left 1</option>
		<option value="1" {if $req.list.search.po eq '1' }selected{/if}>Down left 2</option>
		<option value="2" {if $req.list.search.po eq '2' }selected{/if}>Down left 3</option>
		<option value="3" {if $req.list.search.po eq '3' }selected{/if}>Right 1</option>
		<option value="4" {if $req.list.search.po eq '4' }selected{/if}>Right 2</option>
		<option value="5" {if $req.list.search.po eq '5' }selected{/if}>Right 3</option>
	</select>
	<li id="input2" style="width:300px;height:35px;">{$lang.banner.note}</li>
	</li>
	</ul>
	</div>
	<form id="mainForm" name="mainForm" enctype="multipart/form-data" method="post" action="?act=adv&amp;cp=state" onsubmit="//xajax_saveBannerState(); return false;">
	<div id="tabledatalist" class="wrap">
	{/if}
		<ul id="table" style="width:800px;">
		<li class="tabletop" style="width:100px;">{$lang.banner.lb_type}</li>
		<li class="tabletop" style="width:120px;">{$lang.banner.lb_position}</li>
		<li class="tabletop" style="width:130px;">{$lang.banner.lb_link}</li>
		<li class="tabletop" style="width:190px;">{$lang.banner.lb_view}</li>
		<li class="tabletop" style="width:120px;">{$lang.banner.lb_desc}</li>
		<li class="tabletop" style="width:110px;">{$lang.banner.lb_operate}</li>
		<div style="clear:both;"></div>
		{if $req.list.list}
		{foreach from=$req.list.list item=l}
		
		<li style="width:100px;height:270px; text-align:justify">View: {$l.view_times}<br>Click: {$l.click}<br />Store Type:<br />{$lang.seller[$l.displaypage]}</li>
		<li style="width:120px;height:270px;">{$req.list.position[$l.position]}<br><img src="/skin/red/admin/images/{$l.position}.jpg"></li>
		<li style="width:130px;height:270px;"><a href='{$l.banner_link}' target=_blank>{$l.banner_link}</a></li>
		<li style="width:190px;height:270px;"><a href='{$l.banner_link}' target=_blank>{$l.banner_img} </a></li>
		<li style="width:120px;height:270px;">{$l.description}</li>
		<li style="width:110px;height:270px;"><input name="button1" type="button" class="hbutton" value="{$lang.but.edit}"  onclick="javascript:xajax_editBannerState('{$l.banner_id}',xajax.getFormValues('mainForm'));">&nbsp;<input name="button2" type="button" class="hbutton" value="{$lang.but.delete}" onclick="javascript:if(confirm('{$lang.pub_clew.delete}')) xajax_deleteBannerState({$l.banner_id});"></li>
		<label>
		</label>
		{/foreach}
		<li style="width:720px; height:30px; background:#ffffff;">{$req.list.pager.linksAll}</li>
		{else}
		<li style="width:720px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
		{/if}
		</ul>
	{if !$req.nofull }	</div>
	<input name="searchparam" type="hidden" id="searchparam" value='{$req.list.search.searchparam}' />
	<input name="pageno" type="hidden" id="pageno" value="{$req.list.search.pageno}"/>
	</form>
	</div>
	{/if}
	
{elseif $req.display eq 'edit'}

	<div id="input-table">
	<ul style="width:720px;">
	<li id="lable" style="width:20%;">{$lang.banner.lb_seller}</li>
	<li id="input" style="width:78%;">
	{if $req.list.displaypage eq ''}
	<select name="displaypage" id="seller" style="width:145px">
	<option value="State" {if $req.list.displaypage eq 'State' }selected{/if}>Online Store</option>
	<option value="Estate" {if $req.list.displaypage eq 'Estate' }selected{/if}>Real Estate</option>
	<option value="Auto" {if $req.list.displaypage eq 'Auto' }selected{/if}>Automotive</option>
	<option value="Job" {if $req.list.displaypage eq 'Job' }selected{/if}>Job Market</option>
	</select>
	{else}
		{if $req.list.displaypage eq 'State'}Online Store
		{elseif $req.list.displaypage eq 'Estate'}Real Estate
		{elseif $req.list.displaypage eq 'Auto'}Automotive
		{elseif $req.list.displaypage eq 'Job'}Job Market
		{/if}
	{/if}
	</li>
	<li id="lable" style="width:20%;">{$lang.banner.lb_state}</li>
	<li id="input" style="width:78%;">
	{if $req.list.banner_id eq ''}
	<select name="stateId" style="width:238px" id="stateId">
		{foreach from=$req.list.stateList item=l}
		<option value="{$l.id}" {if $req.list.sid eq $l.id } selected {/if}>{$l.description} ({$l.stateName})</option>
		{/foreach}
	</select>
	{else}
		{$req.list.description} ({$req.list.stateName})
	{/if}	</li>
	
	<li id="lable" style="width:20%;">{$lang.banner.lb_position}</li>
	<li id="input" style="width:78%;">
	{if $req.list.banner_id eq ''}
	<select name="bannerPosition" id="bannerPosition" style="width:138px" onChange="javascript:xajax.$('img_position').src='/skin/red/admin/images/'+this.options[this.selectedIndex].value+'.jpg'">
		<option value="left1" {if $req.list.po eq 'left1' }selected{/if}>Down left 1</option>
		<option value="left2" {if $req.list.po eq 'left2' }selected{/if}>Down left 2</option>
		<option value="left3" {if $req.list.po eq 'left3' }selected{/if}>Down left 3</option>
		<option value="right1" {if $req.list.po eq 'right1' }selected{/if}>Right 1</option>
		<option value="right2" {if $req.list.po eq 'right2' }selected{/if}>Right 2</option>
		<option value="right3" {if $req.list.po eq 'right3' }selected{/if}>Right 3</option>
	</select>
	{else}
		{$req.list.poname}
	{/if}	</li>
	
	<li id="lable" style="width:20%; height:150px;"></li>
	<li id="input" style="width:78%; height:150px; text-align:left;"><img src="/skin/red/admin/images/{$req.list.position}.jpg" name="img_position" hspace="5" vspace="0" align="left" id="img_position" /><br /><br /><br />(Left banner size must be 163px * 187px)<br>
	  (Right banner size must be 170px * 270px)	  </li>
	  
	<li id="lable" style="width:20%;">{$lang.banner.lb_file}</li>
	<li id="input" style="width:78%;">
	<input type="file" name="bannerFile" id="bannerFile" size="30" style="width:238px" /></li>
	
	<li id="lable" style="width:20%;">{$lang.banner.lb_link}</li>
	<li id="input" style="width:78%;"><input name="bannerLink" value="{$req.list.banner_link}" id="subject" style="width:238px" size="30" /></li>
	
	<li id="lable" style="width:20%; height:100px;">{$lang.banner.lb_desc}</li>
	<li id="input" style="width:78%; height:100px;"><textarea name="message" cols="60" rows="5" id="message">{$req.list.description}</textarea></li>
	
	<li id="input" style="width:700px; "><input name="submit" type="submit" class="hbutton" id="submit" value=" {$lang.but.submit} " /> <input type="button" class="hbutton" value=" {$lang.but.back} " onclick="javascript: xajax_getBannerStateList(xajax.$('pageno').value,xajax.getFormValues('mainForm'));" />
	  <input name="banner_id" type="hidden" id="banner_id" value="{$req.list.banner_id}"/>
	</li>
	</ul>
</div>
{/if}