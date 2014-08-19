{literal}
<style>
	.tabtmp{
		list-style:none;
		margin:0;
		float:left;
	}
	.tabtmp li{
		list-style:none;
        margin-left: 5px;
        margin-right: 5px;
        padding-left:5px;
        padding-right: 5px;
		height:40px;
		line-height:40px;
		text-align:center;
		float:left;
		cursor:pointer;
		font-weight:bold;
	}
	.tabtmp li.active_tab{
		background-color:#9E99C1;
	}
</style>
{/literal}
<div id="ajaxmessage" class="text" style="text-align:center;color:red;" ></div>
<form id="mainForm" name="mainForm" method="post" action="" onSubmit="javascript: if(deleteList('offerId[]', '{$lang.pub_clew.pleaseselect}', '{$lang.pub_clew.delete}')) xajax_offerDelete(xajax.getFormValues('mainForm')); return false;">
<div id="tabledatalist">

    
        <select id="sle_product" style="width:auto">
				<option value="all">All</option>
				{if $req.products|isarray }
					{foreach from=$req.products item=arr}
							{if $arr.pid eq $smarty.get.pid}
								<option value="{$arr.pid}" selected="selected">{$arr.item_name}</option>
							{else}
								<option value="{$arr.pid}">{$arr.item_name}</option>
							{/if}
					{/foreach}
				{/if}
    </select>
        <div style="clear: both;"></div>
    <div class="clear"></div>
	<table width="100%" border="0" cellpadding="2" cellspacing="1">
	<tr height="35">
	<td width="50" class="purpleTitle">#</td>
	<td width="120" class="purpleTitle">{$lang.certified.buyer}</td>
	<td width="210" class="purpleTitle">{$lang.certified.productName}</td>
	<td class="purpleTitle">{$lang.certified.submitDate}</td>
    <td class="purpleTitle">{$lang.certified.authoriseDate}</td>
	<td width="150" align="center" class="purpleTitle">{$lang.tit.operation}</td>
	</tr>
    {if $req.certifieds.list}
	{foreach name=offer from=$req.certifieds.list item=l}
	<tr height="30">
	  <td bgcolor="#FFFFFF"><input style="display:none;" type="checkbox" id="offerId[]" name="offerId[]" value="{$l.product_certified_id}" style="border:0px;"/>{$smarty.foreach.offer.iteration}</td>
	  <td bgcolor="#FFFFFF"><a href="soc.php?cp=certified&act=detail&id={$l.id}">{$l.full_name|wordwrap:15:"<br>\n"}</a><br /></td>
	  <td bgcolor="#FFFFFF"><a target="_blank" href="http://{$smarty.server.HTTP_HOST}/{$store_name}/{$l.url_item_name}">{$l.item_name|wordwrap:25:"<br>\n"}</a>{if $l.finish}&nbsp;(Ended){/if}</td>
	  <td bgcolor="#FFFFFF">{$l.created_time}</td>
      <td bgcolor="#FFFFFF"><span id="authoriseTime{$l.id}">{if $l.authorise_time neq '01/01/1970'}{$l.authorise_time}{/if}</span></td>
	  <td align="center" bgcolor="#FFFFFF" class="lineHeight">
          <span id="operation{$l.id}">
             {if $l.finish}
                {$lang.certified.notProceeded}
             {else}
                {if $l.is_authorised eq 1}
                    {$lang.certified.authorise}
                {elseif $l.is_authorised eq 2}
                    {$lang.certified.decline}
                {else}
                <input type="button" value="{$lang.certified.authoriseButton}" op="1" certifiedId="{$l.id}" style="cursor:pointer;padding:3px;margin:3px;width:68px;" class="operation"/>
                &nbsp;
                <input type="button" value="{$lang.certified.declineButton}" op="2" certifiedId="{$l.id}" style="cursor:pointer;padding:3px;margin:3px;width:60px;" class="operation"/>
                {/if}
            {/if}
            </span>
      </td>
	</tr>
	{/foreach}
	<tr>
	  <td height="30" colspan="7" bgcolor="#FFFFFF">
          <table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td align="center">{$req.certifieds.links.all}</td>
              </tr>
          </table>
      </td>
	</tr>
    {else}
		<tr>
	  <td height="30" colspan="7" bgcolor="#FFFFFF">
          <table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td align="center"><strong style="color:red; font-size:16px;">No Records</strong></td>
              </tr>
          </table>
      </td>
	</tr>
	
	{/if}
    </table>
</div>
</form>
{literal}
<script language="javascript" type="text/javascript">
<!--//
$(function(){
    $('.operation').click(function(){
        var op = $(this).attr('op');

        if(!confirm(op == '1'? '{/literal}{$lang.certified.authoriseConfirm}{literal}': '{/literal}{$lang.certified.declineConfirm}{literal}')){
            return;
        }
        
        var url = "soc.php"
        var cid = $(this).attr('certifiedId');
        $.ajax({
            type:'post',
            url:'soc.php',
            data:"cp=certified&act=audit&cid=" + cid + "&op=" + op,
            dataType:'json',
            success:function(data){
                if(data.success){
                    $('#authoriseTime' + cid).html(data.time);
                    if(op == '1'){
                        $('#operation' + cid).html('{/literal}{$lang.certified.authorise}{literal}');
                        alert('{/literal}{$lang.certified.authoriseAlert}{literal}');
                    }else{
                        $('#operation' + cid).html('{/literal}{$lang.certified.decline}{literal}');
                        alert('{/literal}{$lang.certified.declineAlert}{literal}');
                    }
                }
            }
        });
    });
	
	
	$("#sle_product").change(function(){
		id=$("#sle_product").val();
		if(id=='all')
			location.href="soc.php?cp=certified&act=list";
		else
			location.href="soc.php?cp=certified&act=list&pid="+id;
	
	
	});
});
//-->
</script>
{/literal}