<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.isok eq 'yes'}
<script language="javascript">
	alert('{$req.msg}');
	location.href='/admin/?act=race&cp=question&sid={$req.info.site_id}{if $req.back}&back={$req.back}{/if}';
</script>
{/if}
{literal}
<style type="text/css">
	.inputB{ width:250px; }
	.selectB{width:250px;}
	.tablelist tr {height:25px;}
</style>
<script language="javascript">
	function checkform(obj){
		var objform = obj;
		var errmsg = "";
		if(obj.question.value==""){
			errmsg +='- Question is required.\n';
		}
		
		if(errmsg != ""){
			alert(errmsg);
			return false;
		}
		return true;
	}
</script>
{/literal}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.msg}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
    <form action="" method="post" onSubmit="return checkform(this);">
    <input type="hidden" name="qid" value="{$req.info.qid}" id="qid"/>
	<table width="600px" cellpadding="0" cellspacing="4" class="tablelist">
    	<colgroup>
        <col width="20%"/>
        <col width="40%"/>
        <col width="30%"/>
        </colgroup>
        
        <tr>
        	<td><span id='txt_nickname'>{$lang.race.lb_sitename}</span>*</td>
            <td align="left">
            <select name="site_id">
            {foreach from=$req.site_list item=l}
            	<option value="{$l.id}" {if $req.info.site_id eq $l.id}selected{/if}>{$l.site_name}</option>
            {/foreach}
            </select>
            </td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Question</span>*</td>
            <td align="left"><input type="text" class="inputB" id="domain" name="question" value="{$req.info.question}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Type</span>*</td>
            <td align="left">
            <input type="radio" name="type" value="radio" checked="checked" />Radio
            <input type="radio" name="type" value="checkbox" />Checkbox
            </td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        <tr>
        	<td>Suspend</td>
            <td align="left"><input type="checkbox" name="deleted" {if $req.info.deleted}checked{/if} value="1"/> Is suspend?
            </td>
            <td></td>
        </tr>
        <tr>
        	<td></td>
            <td align="left"><input type="submit" class="hbutton" value="Save"/> <input class="hbutton" type="button"  value="Cancel" onClick="location.href='/admin/?act=race&cp=question{if $req.sid}&sid={$req.sid}{/if}{if $req.back}&back={$req.back}{/if}'"/></td>
            <td></td>
        </tr>
        
    </table>
    </form>
	</div>
