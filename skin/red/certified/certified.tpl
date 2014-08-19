

<script type="text/javascript">
		var usa_country_id="13";
		var country={if $smarty.session.attribute neq 4}usa_country_id{else}"{$user_info.bu_country}"{/if};
		var state="{$user_info.bu_state}";
		var sle_city_content="{$sle_city}";
		var city="{$user_info.bu_suburb}";
		
{literal}		
	$(document).ready(function(){
		if(country==usa_country_id) {
			$("#sle_country").attr('value',country);
			$("#sle_state").attr('value',state);
			$('#label_city').html(sle_city_content);
			$("select[@name=sle_city] option[@selected]").attr('value',city);
			$("#sle_show_state,#sle_show_city").show();
			$("#txt_show_state,#txt_show_city").hide();
		}
		else {
			$("#sle_country").attr('value',country);
			$("#txt_state").val(state);
			$("#txt_city").val(city);
			$("#sle_show_state,#sle_show_city").hide();
			$("#txt_show_state,#txt_show_city").show();
		}
		
		$("#sle_state").change(function(){
			$.get("soc.php", 
			  { act: "signon",
			    step: "suburb", 
				SID: this.value 
			  },
  			  function(data){
				$('#label_city').html(data);
  			  }
			);
		
		});
		
		
		
		$("#show_terms").toggle(
			function(){
				$("#div_terms").show('slow');
			},
			function(){
				$("#div_terms").hide('fast');
			}
		
		);
	
	});
	
/*	
	function countryChanged(val){
		if(val==usa_country_id) {
			$("#sle_show_state,#sle_show_city").show();
			$("#txt_show_state,#txt_show_city").hide();
		}
		else{
			$("#sle_show_state,#sle_show_city").hide();
			$("#txt_show_state,#txt_show_city").show();
		}
	}
*/	
	function form_submit(){
		msg='';
		if($.trim($("#txt_name").val())=='') msg+='Name is required.\n';
		if(!$('#txt_email').val().match(/\w+@\w+\.\S+/)) msg+='Email is invalid.\n';
		if($("#sle_country").val()==usa_country_id) {
			$("#hdn_state").val($("#sle_state").val());
			$("#hdn_city").val($("#bu_suburb").val());
			$("#txt_state_str").val($("select[@name=sle_state] option[@selected]").text());
			$("#txt_city_str").val($("select[@id=bu_suburb] option[@selected]").text());
		}
		else {
			$("#hdn_state").val($("#txt_state").val());
			$("#hdn_city").val($("#txt_city").val());
			$("#txt_state_str").val($("#txt_state").val());
			$("#txt_city_str").val($("#txt_city").val());
		}
	
		//if($.trim($("#hdn_state").val())=='') msg+='State is required.\n';
		//if($.trim($("#hdn_city").val())=='') msg+='City/ Suburb is required.\n';
		if($.trim($("#txt_phone").val())=='') msg+='Phone is required.\n';
		if($.trim($("#txt_iden_name1").val())=='') msg+='Reference1\'s Name is required.\n';
		if(!$("#txt_iden_email1").val().match(/\w+@\w+\.\S+/)) msg+='Reference1\'s Email is invalid.\n';
		if($.trim($("#txt_iden_phone1").val())=='') msg+='Reference1\'s Phone is required.\n';
		if($.trim($("#txt_iden_name2").val())=='') msg+='Reference2\'s Name is required.\n';
		if(!$("#txt_iden_email2").val().match(/\w+@\w+\.\S+/)) msg+='Reference2\'s Email is invalid.\n';
		if($.trim($("#txt_iden_phone2").val())=='') msg+='Reference2\'s Phone is required.\n';
		//if($.trim($("#txta_ps").val())=='') msg+='Comments are required.\n';
		if($("#chk_terms").attr('checked')==false) msg+='You must agree to the Terms and Conditions.\n';
		$("#txt_country_str").val($("select[@name=sle_country_select] option[@selected]").text());
		if(msg!='') {
			alert(msg);
			return false;
		}
		else 
			return true;
	}
	
</script>
<style type="text/css">
	#form_reg select {
		border:1px solid #CCCCCC;
		padding:5px;
		width:150px;
	}
	#form_reg textarea {
		border:1px solid #CCCCCC;
		padding:5px;
		width:275px;
		height:100px;
	}
</style>
{/literal}

<form action="soc.php?cp=certified&act=add" method="post" onsubmit="return form_submit()" id="form_reg">
<div style="font-size:13px; float:left; white-space:nowrap">Please complete the fields below, so as to enable the seller to certify you and give you permission to bid on this auction.</div><div style="clear:both;height:10px;"></div>

<div style="text-align:center"><strong style="font-size:15px;">Certified Bidders Application Form</strong></div>

			<table width="720" border="0" cellpadding="0" cellspacing="4" id="table14">

	
<tr>
		
		<td height="25" align="right">Auction Name </td>

	  <td colspan="2"><label>{$product_info.item_name}</label></td>
	  </tr>
		
	  <td height="25" align="right">{$lang.labelEmail} *</td>

	  <td colspan="2"><label><input type="text" class="inputB" name="txt_email" id="txt_email" readonly="" value="{$user_info.bu_email}"/></label></td>
	  </tr>
	<tr>
	  <td height="25" align="right">{$lang.labelName} *</td>
	  <td colspan="2"><label><input type="text" class="inputB" name="txt_name" id="txt_name" value="{$user_info.bu_name}"/></label></td>
      </tr>
	
	  
	<tr>
	  <td height="25" align="right">{$lang.labelCountry} *</td>
	  <td colspan="2">
	  <label><input type="text" class="inputB" value="{$country_name}" readonly=""/></label>
	  <label><select name="sle_country_select" class="select" id="sle_country" onchange="countryChanged(this.value)" style="display:none">
	    
                            {$req.countrylist}
                          
	    </select></label>
	  </td>
	  </tr>
	<tr>
	  <td height="25" align="right">{$lang.labelState} *</td>
	  <td colspan="2"><label><input type="text" class="inputB" value="{$state_name}" readonly=""/></label>
	  
	  <label id="sle_show_state"><select name="sle_state" class="select" id="sle_state" style="display:none">
	      
                              {$req.statelist}
                            
      </select>
      </label>
        <label id="txt_show_state">
        <input type="text" name="txt_state" class="inputB" id="txt_state" readonly="" style="display:none"/>
        </label></td>
	 </tr>

	<tr>
	  <td align="right" >{$lang.labelCity} *</td>

	  <td colspan="2">
	  <label><input type="text" class="inputB" value="{$user_info.bu_suburb}" readonly=""/></label>
	  <label id="sle_show_city"><span id="label_city" style="display:none">
	  
	  <select name="sle_city" class="select" id="select" style="display:none">
	      
                              {$req.suburblist}
                            
      </select>
	  
      </span></label>
        <label id="txt_show_city">
        <input type="text" class="inputB" name="txt_city" id="txt_city" style="display:none">
        </label></td>
	</tr>
	  
	  <tr>
		<td align="right">{$lang.labelZIP} *</td>
		<td colspan="2"><label><input type="text" class="inputB" name="txt_postcode" id="txt_postcode" value="{$user_info.bu_postcode}" readonly=""/></label></td>

	</tr>
	  
	<tr>
		<td align="right">{$lang.labelPhone} *</td>
		<td colspan="2"><label><input type="text" class="inputB" name="txt_phone" id="txt_phone" value="{$phone}"/></label></td>

	</tr>
	<tr>
			<td align="right">{$lang.langIdentify_1}</td>
			<td colspan="2"><label></label></td>
		</tr>
		<tr>
			<td align="right">Name *</td>
			<td colspan="2"><label><input type="text" class="inputB" name="txt_iden_name1" id="txt_iden_name1"/></label></td>
		</tr>
		  <tr>
			<td align="right">Email *</td>
			<td colspan="2"><label><input type="text" class="inputB" name="txt_iden_email1" id="txt_iden_email1"/></label></td>
		</tr>
		<tr>
			<td align="right">Phone *</td>
			<td colspan="2"><label><input type="text" class="inputB" name="txt_iden_phone1" id="txt_iden_phone1"/></label></td>
		</tr>
		<tr>
			<td align="right">{$lang.langIdentify_2}</td>
			<td colspan="2"><label></label></td>
		</tr>
	  <tr>
		<td align="right">Name *</td>
		<td colspan="2"><label><input type="text" class="inputB" name="txt_iden_name2" id="txt_iden_name2"/></label></td>

	</tr>
	<tr>
		<td align="right">Email *</td>
		<td colspan="2"><label><input type="text" class="inputB" name="txt_iden_email2" id="txt_iden_email2"/></label></td>
	</tr>
	
		<tr>
		<td align="right" height="30">Phone *</td>
		<td colspan="2"><label><input type="text" class="inputB" name="txt_iden_phone2" id="txt_iden_phone2"/></label></td>
	</tr>
	
	<tr>
		<td align="right" height="30">Comments </td>
		<td colspan="2"><label><textarea name="txta_ps" id="txta_ps"></textarea></label></td>
	</tr>
	
	<tr>
		<td align="right" height="30">Email a copy to myself</td>
		<td colspan="2"><label><input type="checkbox"  name="chk_email" value="send"/></label></td>
	</tr>
	<tr>
		<td align="right" height="15"></td>
		<td colspan="2"></td>
	</tr>	
			<input type="hidden" name="hdn_state" id="hdn_state" value=""/>
          <input 	type="hidden" name="hdn_city" id="hdn_city" value=""/>
		  
          <input 	type="hidden" name="hdn_produce_id" value="{$product_id}"/>
          <input type="hidden" name="hdn_store_id" value="{$store_id}"/>
		  
          <input type="hidden" name="txt_country_str" id="txt_country_str"/>
          <input type="hidden" name="txt_state_str" id="txt_state_str"/>
          <input type="hidden" name="txt_city_str" id="txt_city_str"/>
		
	
	
	</table>
	<div align="center" style="text-align:center;">
		<input type="checkbox" name="chk_terms" id="chk_terms"/>  You must agree to the <a href="#" id="show_terms">Terms and Conditions</a> to submit this form.
		<div id="div_terms" style="display:none; text-align:left;width:80%;margin-top:5px; margin-left:auto; margin-right:auto;">I acknowledge that in the event that I am the successful (winning) bidder in this auction, I accept the responsibility for payment of the full amount in accordance with the Terms and Conditions as stated by SOC Exchange or those specific payment instructions as stated by the vendor. </div>
		<br/>
		
		<input name="submit" type="submit" class="or_submit" value="" style="border:none;cursor:pointer; margin-top:5px;" />
		
	</div>
	
</form>