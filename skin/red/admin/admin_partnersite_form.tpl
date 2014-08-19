<link href="css/global.css" rel="stylesheet" type="text/css">
{if $req.isok eq 'yes'}
<script language="javascript">
	alert('{$req.msg}');
	location.href='/admin/?act=race&cp=partner_site';
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
		if(obj.site_name.value==""){
			errmsg +='- Site Name is required.\n';
		}
		if(obj.domain.value==""){
			errmsg +='- Domain is required.\n';
		}
		if(obj.point.value==""){
			errmsg +='- Point is required.\n';
		} else if (-1 == obj.point.value.search(/^[0-9]?[0-9]*\.?[0-9]*$/)) {
			errmsg +='point has to be a whole number without decimals.';
		}
		
		if(obj.max_time.value==""){
			errmsg +='- Max Time is required.\n';
		} else if (-1 == obj.max_time.value.search(/^[0-9]?[0-9]*\.?[0-9]*$/)) {
			errmsg +='Max Time has to be a whole number without decimals.';
		}
		
		if(errmsg==""){
			$.post('/admin/index.php',{act:'race',cp:'checkparnersiteform',domain:obj.domain.value,sid:obj.sid.value},function(data){																																																					
			var spary = data.split('|');
			if(data=="ok"){
				objform.submit();
			}else{
				if(spary[0]=="existed"){
					errmsg += "- Domain is existed.\n";
				}
				alert(errmsg);
			}
		});
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
    <input type="hidden" name="sid" value="{$req.info.sid}" id="sid"/>
	<table width="600px" cellpadding="0" cellspacing="4" class="tablelist">
    	<colgroup>
        <col width="20%"/>
        <col width="40%"/>
        <col width="30%"/>
        </colgroup>
        
        <tr>
        	<td><span id='txt_nickname'>{$lang.race.lb_sitename}</span>*</td>
            <td align="left"><input type="text" class="inputB" id="site_name" name="site_name" value="{$req.info.site_name}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>{$lang.race.lb_domain}</span>*</td>
            <td align="left"><input type="text" class="inputB" id="domain" name="domain" value="{$req.info.domain}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>{$lang.race.lb_point}</span>*</td>
            <td align="left"><input type="text" class="inputB" id="point" name="point" value="{$req.info.point}"/></td>
            <td>&nbsp;<span id="emailbox"></span></td>
        </tr>
        
        <tr>
        	<td><span id='txt_nickname'>{$lang.race.lb_maxtime} (s)</span>*</td>
            <td align="left"><input type="text" class="inputB" id="max_time" name="max_time" value="{$req.info.max_time}"/></td>
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
            <td><input type="submit" class="hbutton" value="Save"/> <input class="hbutton" type="button"  value="Cancel" onClick="location.href='/admin/?act=race&cp=partner_site'"/></td>
            <td></td>
        </tr>
        
    </table>
    </form>
	</div>
