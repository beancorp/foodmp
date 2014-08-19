<link href="css/global.css" rel="stylesheet" type="text/css">
{literal}
<script>
	function procheckFrom(param){
		if(param==1){
			if(document.getElementById('txt_promot').value==""){
				alert('{/literal}{$lang.promotion.alt_promot}{literal}');
				return false;
			}
			xajax_createPromot(xajax.getFormValues('new_form'));
		}else{
			if(document.getElementById('edit_txt_promot').value==""){
				alert('{/literal}{$lang.promotion.alt_promot}{literal}');
				return false;
			}
			xajax_editPromot(xajax.getFormValues('edit_form'));
		}
		return false;
	}
	function deleteRecord(id){
		xajax_deletePromotion(id);
	}
	function CancelEdit(){
		document.getElementById('edit_form').style.display = "none";
		document.getElementById('new_form').style.display  = "";
		document.getElementById('edit_txt_promot').value  = "";
		document.getElementById('edit_promot_id').value  = "";
	}
</script>
{/literal}
	<div id="ajaxmessage" class="publc_clew" align="center">{$req.input.title}</div>
	<div id="input-table" style="width:850px; padding:0px; text-align:left;">
		<form id="new_form" name="new_form"  action="" method="post" onsubmit="return procheckFrom(1);">
		<ul style="margin-left:320px;padding-bottom:5px;">
			<li style="padding-bottom:5px;">&nbsp;&nbsp;&nbsp;&nbsp;Market Place:&nbsp;
            <select name="attribute" class="selectB">
            	<!--<option value="0" {if $req.info.attribute eq '0'}selected{/if}>Buy & Sell</option>-->
               	<option value="1" {if $req.info.attribute eq '1'}selected{/if}>Real Estate</option>
                <option value="2" {if $req.info.attribute eq '2'}selected{/if}>Automotive</option>
                <option value="3" {if $req.info.attribute eq '3'}selected{/if}>Careers</option>
                <option value="5" {if $req.info.attribute eq '5'}selected{/if}>Food & Wine</option>
            </select>
            </li>
            <li>Promotion Code:&nbsp;<input type="text" id="txt_promot" name="promotion" maxlength="12" value=""/>&nbsp;<input type="submit" name="submit" value="Create" class="hbutton"/></li>
		</ul>
		<input type="hidden" name="opt" value="add"/>
		</form>
		
		<form id="edit_form" name="edit_form" style="display:none;"  action="" method="post" onsubmit="return procheckFrom(2);">
		<ul style="margin-left:320px;padding-bottom:5px;">
			<li id="mymarket" style="padding-bottom:5px;"></li>
			<li>Promotion Code:&nbsp;<input type="text" id="edit_txt_promot" name="promotion" maxlength="12" value=""/>&nbsp;<input type="submit" name="submit" value="&nbsp;Save&nbsp;"/>&nbsp;<input type="button" value="Cancel" onclick="CancelEdit();"/></li>
		</ul>
		<input type="hidden" id="edit_promot_id" name="id" value=""/>
		<input type="hidden" name="opt" value="edit"/>
		</form>
	</div>	
	<div align="center" style="border-bottom-color:#999999;">
	<form id="mainForm" name="mainForm" method="post" action="">
	<div id="tabledatalist" class="wrap" >
		{include file="admin_promotion_list.tpl"}
	</div>
	</form>
	</div>
