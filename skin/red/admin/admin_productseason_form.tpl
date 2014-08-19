<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.isok eq 'yes'}
<script language="javascript">
	alert('{$req.msg}');
	location.href='/admin/?act=pro&cp=season';
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
		var check_season = false;
		var check_type = false;
		$("input[@name='season[]']").each(function(){
			if($(this).attr("checked")) {
				check_season = true;
				return;
			}
		});
		if(check_season == false) {		
			errmsg +='- Season is required.\n';
		}
		$("input[@name='type[]']").each(function(){
			if($(this).attr("checked")) {
				check_type = true;
				return;
			}
		});
		if(check_type == false) {		
			errmsg +='- Type is required.\n';
		}
		/*if(obj.desc.value==""){
			errmsg +='- Description is required.\n';
		}
		if(obj.varities.value==""){
			errmsg +='- Varities is required.\n';
		}*/
		if(errmsg==""){
			objform.submit();
		}else{
			alert(errmsg);
		}
		return false;
	}
</script>
{/literal}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.msg}</div>
	
	<div align="center" style="border-bottom-color:#999999;">
    <form action="" method="post" onSubmit="return checkform(this);">
    <input type="hidden" name="pid" value="{$req.info.pid}" id="pid"/>
	<table width="600px" cellpadding="0" cellspacing="4" class="tablelist">
    	<colgroup>
        <col width="20%"/>
        <col width="40%"/>
        <col width="30%"/>
        </colgroup>
        
        <tr>
        	<td><span id='txt_nickname'>Season</span>*</td>
            <td align="left">
            {foreach from=$req.seasons item=season}
                <input type="checkbox" name="season[]" value="{$season.id}" {if in_array($season.id, $req.info.in_season_arr)}checked="checked"{/if} />{$season.title}
            {/foreach}
            </td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Type</span>*</td>
            <td align="left">{$req.info.typename}
            <input type="checkbox" name="type[]" value="1"{if in_array(1, $req.info.in_type_arr)}checked="checked"{/if} />Fruit
            <input type="checkbox" name="type[]" value="2"{if in_array(2, $req.info.in_type_arr)}checked="checked"{/if} />Vegetables
            </td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Name</span>*</td>
            <td align="left">{$req.info.title}</td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Description</span></td>
            <td align="left"><textarea id="desc" name="desc" cols="37" rows="8">{$req.info.desc}</textarea></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>Varities</span></td>
            <td align="left"><input type="text" class="inputB" id="varities" name="varities" value="{$req.info.varities}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        <tr>
        	<td></td>
            <td><input type="submit" class="hbutton" value="Save"/> <input class="hbutton" type="button"  value="Cancel" onClick="location.href='/admin/?act=pro&cp=season'"/></td>
            <td></td>
        </tr>
        
    </table>
    </form>
	</div>
