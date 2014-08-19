<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.isok eq 'yes'}
<script language="javascript">
	alert('{$req.msg}');
	location.href='/admin/?act=race&cp=answer&sid={$req.info.site_id}&qid={$req.info.question_id}{if $req.back}&back={$req.back}{/if}';
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
		if(obj.site_id.value==""){
			errmsg +='- Site Name is required.\n';
		}
		if(obj.question_id.value==""){
			errmsg +='- Question is required.\n';
		}
		if(obj.pre_index.value==""){
			errmsg +='- Prefix is required.\n';
		}
		
		if(obj.answer.value==""){
			errmsg +='- Answer is required.\n';
		} 
		
		if(errmsg!=""){
			alert(errmsg);
			return false;
		}
		return true;
	}
	
	function changesite(obj){
		$.post('/admin/index.php',{act:'race',cp:'getsitelist',site:obj.value},function(data){$('#question_id').html(data);$('#question_id').val('');});
	}
</script>
{/literal}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.msg}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
    <form action="" method="post" onSubmit="return checkform(this);">
    <input type="hidden" name="sid" value="{$req.info.aid}" id="aid"/>
    <input type="hidden" name="back" value="{$req.back}" id="back"/>
	<table width="600px" cellpadding="0" cellspacing="4" class="tablelist">
    	<colgroup>
        <col width="20%"/>
        <col width="40%"/>
        <col width="30%"/>
        </colgroup>
        
        <tr>
        	<td><span id='txt_nickname'>{$lang.race.lb_sitename}</span>*</td>
            <td align="left">
            <select name="site_id" id="site_id" onChange="changesite(this);">
            <option value="">Select Site Name</option>
            {foreach from=$req.site_list item=l}
            	<option value="{$l.id}" {if $req.info.site_id eq $l.id}selected{/if}>{$l.site_name}</option>
            {/foreach}
            </select>
            </td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        <tr>
        	<td><span id='txt_nickname'>Question</span>*</td>
            <td align="left">
            <select name="question_id" id="question_id">
            <option value="">Select Question</option>
            {foreach from=$req.question_list item=l}
            	<option value="{$l.id}" {if $req.info.question_id eq $l.id}selected{/if}>{$l.question}</option>
            {/foreach}
            </select>
            </td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        <tr>
        	<td><span id='txt_nickname'>Prefix</span>*</td>
            <td align="left"><input type="text" class="inputB" id="pre_index" name="pre_index" value="{$req.info.pre_index}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Answer</span>*</td>
            <td align="left"><input type="text" class="inputB" id="answer" name="answer" value="{$req.info.answer}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Order</span></td>
            <td align="left"><input type="text" class="inputB" id="order" name="order" value="{$req.info.order}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Correct Answer</span>*</td>
            <td align="left">            
            <input type="radio" name="status" value="0" {if $req.info.status eq '0' || $req.info.status eq ''}checked="checked"{/if} />No
            <input type="radio" name="status" value="1" {if $req.info.status eq '1'}checked="checked"{/if}/>Yes
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
            <td align="left"><input type="submit" class="hbutton" value="Save"/> <input class="hbutton" type="button"  value="Cancel" onClick="location.href='/admin/?act=race&cp=answer{if $req.sid}&sid={$req.sid}{/if}{if $req.qid}&qid={$req.qid}{/if}{if $req.back}&back={$req.back}{/if}'"/></td>
            <td></td>
        </tr>
        
    </table>
    </form>
	</div>
