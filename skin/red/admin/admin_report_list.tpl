<ul id="table" style="width:965px;">
	{if $req.reportlist.list}
	<li class="tabletop" style="width:40px;">Login</li>
	<li class="tabletop" style="width:100px;"><a href="#" onclick="javascript:xajax_getReportList('{$req.reportlist.sort.page}','bu_nickname','{if $req.reportlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Nickname</a>{if $req.reportlist.sort.field eq 'bu_nickname'}{if $req.reportlist.sort.order eq 'ASC'}&darr;{elseif $req.reportlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:100px;"><a href="#" onclick="javascript:xajax_getReportList('{$req.reportlist.sort.page}','attribute','{if $req.reportlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Market Place</a>{if $req.reportlist.sort.field eq 'attribute'}{if $req.reportlist.sort.order eq 'ASC'}&darr;{elseif $req.reportlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:160px;"><a href="#" onclick="javascript:xajax_getReportList('{$req.reportlist.sort.page}','bu_email','{if $req.reportlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Email</a>{if $req.reportlist.sort.field eq 'bu_email'}{if $req.reportlist.sort.order eq 'ASC'}&darr;{elseif $req.reportlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:120px;"><a href="#" onclick="javascript:xajax_getReportList('{$req.reportlist.sort.page}','bu_name','{if $req.reportlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Website Name</a>{if $req.reportlist.sort.field eq 'bu_name'}{if $req.reportlist.sort.order eq 'ASC'}&darr;{elseif $req.reportlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:165px;"><a href="#" onclick="javascript:xajax_getReportList('{$req.reportlist.sort.page}','launch_date','{if $req.reportlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Signup Date (Sydney)</a>{if $req.reportlist.sort.field eq 'launch_date'}{if $req.reportlist.sort.order eq 'ASC'}&darr;{elseif $req.reportlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:70px"><a href="#" onclick="javascript:xajax_getReportList('{$req.reportlist.sort.page}','gender','{if $req.reportlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">Gender</a>{if $req.reportlist.sort.field eq 'gender'}{if $req.reportlist.sort.order eq 'ASC'}&uarr;{elseif $req.reportlist.sort.order eq 'DESC'}&darr;{/if}{/if}</li>
	<li class="tabletop" style="width:45px"><a href="#" onclick="javascript:xajax_getReportList('{$req.reportlist.sort.page}','stateName','{if $req.reportlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">State</a>{if $req.reportlist.sort.field eq 'stateName'}{if $req.reportlist.sort.order eq 'ASC'}&darr;{elseif $req.reportlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:120px"><a href="#" onclick="javascript:xajax_getReportList('{$req.reportlist.sort.page}','bizName','{if $req.reportlist.sort.order eq 'ASC'}DESC{else}ASC{/if}');">College</a>{if $req.reportlist.sort.field eq 'bizName'}{if $req.reportlist.sort.order eq 'ASC'}&darr;{elseif $req.reportlist.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	{else}
	<li class="tabletop" style="width:40px;">Login</li>
	<li class="tabletop" style="width:100px;"><a href="#">Nickname</a></li>
	<li class="tabletop" style="width:100px;"><a href="#">Market Place</a></li>
	<li class="tabletop" style="width:160px;"><a href="#">Email</a></li>
	<li class="tabletop" style="width:120px;"><a href="#">Website Name</a></li>
	<li class="tabletop" style="width:165px;"><a href="#">Signup Date (Sydney)</a></li>
	<li class="tabletop" style="width:70px"><a href="#">Gender</a></li>
	<li class="tabletop" style="width:45px"><a href="#">State</a></li>
	<li class="tabletop" style="width:120px"><a href="#">College</a></li>
	{/if}
	<div style="clear:both;"></div>
	{if $req.reportlist.list}
	{foreach from=$req.reportlist.list item=l}
	
	<li style="width:40px;" ><a href='storeLogin.php?StoreID={$l.StoreID}' target='_blank'><img src="../images/log-in.gif" border=0></a></li>
	<li style="width:100px;" title="{$l.bu_nickname}">{$l.bu_nickname}</li>
	<li style="width:100px;" >{$sellertype[$l.attribute]}</li>
	<li style="width:160px;" title="{$l.bu_email}">{$l.bu_email}</li>
	<li style="width:120px;" title="{$l.bu_name}">{$l.bu_name}</li>
	<li style="width:165px;">{$l.launch_date|date_format:"$PBDateFormat"}</li>
	<li style="width:70px">{if $l.gender eq '0'}Male{else}Female{/if}</li>
	<li style="width:45px">{$l.stateName}</li>
	<li style="width:120px" title="{$l.bizName}">{$l.bizName}</li>
	<label>
	</label>
	{/foreach}
	<li style="width:880px; height:30px; background:#ffffff;">{$req.reportlist.links.all}</li>
	{else}
	<li style="width:880px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
	{/if}
</ul>