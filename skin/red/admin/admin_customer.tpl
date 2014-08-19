<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<script type="text/javascript">
	function deletecustomers(){
		var sl = 0;
		try{
				var obj = document.getElementsByName('id[]');
				for(var i=0; i<obj.length;i++){
					if(obj[i].checked){
						sl++;
					}
				}
		}catch(ex){}
		if(sl==0){
			alert('Please choose the item you want to delete.');	
			return false
		}
		if(confirm('{/literal}{$lang.pub_clew.delete}{literal}')){
		 	xajax_customerDelete(xajax.getFormValues('mainForm'));
		}
		return false;
	}
</script>
{/literal}
{if !$req.nofull}
<div id="ajaxmessage" class="publc_clew">{$req.input.title}</div>

<div align="center" style="border-bottom-color:#999999;">
<div id="input-table" style="width:720px;">
  <form name="mainSearch" id="mainSearch" method="post" action="" onSubmit="javascript:xajax_customerSearch(xajax.getFormValues('mainSearch'),'tabledatalist');return false;">
<ul>
<li id="lable">{$lang.main.lb_cus_name}</li>
<li id="input2"><input name="bu_name" id="bu_name" type="text" size="30" maxlength="50" style="width:180px;"></li>
<li id="lable">{$lang.main.lb_cus_email}</li>
<li id="input2"><input name="bu_email" id="bu_email" type="text" size="30" maxlength="50" style="width:180px;"></li>
<br>
<li id="lable">{$lang.main.lb_cus_zipcode}</li>
<li id="input2"><input name="bu_postcode" id="bu_postcode" type="text" size="30" maxlength="20" style="width:180px;"></li>

<li id="lable">Nickname</li>
<li id="input2"><input name="txt_bu_nickname" id="txt_bu_nickname" type="text" size="30" maxlength="20" style="width:180px;"></li>

<li id="lable"></li>
<li id="input2" style="width:250px;">
    <input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.search} ">
</li>
</ul>
</form>
</div><div style="clear:both;"></div>
<form id="mainForm" name="mainForm" onSubmit="return deletecustomers();">
<div id="tabledatalist">
{/if}
	<ul id="table" style="width:750px;">
	<li class="tabletop" style="width:60px;">{$lang.main.lb_cus_select}</li>
	<li class="tabletop" style="width:150px;"><a href="#" onclick="javascript:xajax_customerGetList('{$req.list.sort.page}','tabledatalist',xajax.getFormValues('mainSearch'),'bu_name','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.main.lb_cus_name}</a>{if $req.list.sort.field eq 'bu_name'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:100px;"><a href="#" onclick="javascript:xajax_customerGetList('{$req.list.sort.page}','tabledatalist',xajax.getFormValues('mainSearch'),'bu_nickname','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.main.lb_cus_nickname}</a>{if $req.list.sort.field eq 'bu_nickname'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:250px;"><a href="#" onclick="javascript:xajax_customerGetList('{$req.list.sort.page}','tabledatalist',xajax.getFormValues('mainSearch'),'bu_email','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.main.lb_cus_email}</a>{if $req.list.sort.field eq 'bu_email'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:80px;"><a href="#" onclick="javascript:xajax_customerGetList('{$req.list.sort.page}','tabledatalist',xajax.getFormValues('mainSearch'),'bu_postcode','{if $req.list.sort.order eq 'ASC'}DESC{else}ASC{/if}');">{$lang.main.lb_cus_zipcode}</a>{if $req.list.sort.field eq 'bu_postcode'}{if $req.list.sort.order eq 'ASC'}&darr;{elseif $req.list.sort.order eq 'DESC'}&uarr;{/if}{/if}</li>
	<li class="tabletop" style="width:80px;">{$lang.but.view}</li>
	{if $req.list.list}
	{foreach from=$req.list.list item=l}
	<li style="width:60px;"><input type="checkbox" id="id[]" name="id[]" value="{$l.StoreID}" class="input-none-border"/></li>
	<li style="width:150px;">{$l.bu_name}</li>
	<li style="width:100px;">{$l.bu_nickname}</li>
	<li style="width:250px;">{$l.bu_email}</li>
	<li style="width:80px;">{$l.bu_postcode}</li>
	<li style="width:80px;"><input name="button" type="button" class="hbutton" value="{$lang.but.view}" onclick="javascript:xajax_customerView({$l.StoreID});"></li>
	{/foreach}
	<li style="width:60px; text-align:left;"><input name="delete" type="submit" class="hbutton" value="{$lang.but.delete}"></li>
	<li style="width:600px; height:30px; background:#ffffff;">{$req.list.links.all}</li>
	{else}
	<li style="width:720px; height:30px; background:#ffffff;">{$lang.pub_clew.nothing}</li>
	{/if}
	</ul>
{if !$req.nofull }
</div>
<input name="searchparam" type="hidden" id="searchparam" value="{$req.list.searchparam}" />
<input name="pageno" type="hidden" id="pageno" value="{$req.list.pageno}"/>
</form>
</div>
{/if}