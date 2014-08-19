{if $req.task eq 'book'}
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="color:#FF0000;">{$req.msg}</td>
			</tr>
		</table>		
		{else}  
				{if $req.storePaid ne ''}
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">


                    <tr>

                      <td>
					<div>
					  {$req.content.aboutPage}
						</div>
						<div style="font-weight: bolder;">Create your Ad</div><br>
						
						<!--Enter the details about your store into the lines below to create your ad:<BR><BR>-->
						
						<form name='bookad' action='' method="post" onsubmit="return checkAdForm()">
						<table width="100%"  border="0" cellspacing="4" cellpadding="2">
							<tr>
								<td width='20%'><b>Website Name:</b></td>
								<td class=fieldcellnormal> {$req.companyline}
									<input type='hidden' value="{$req.companyline}" class="inputB" name='lineone' id="lineone">
								</td>
							</tr>
							<tr>
								<td width='20%'><b>Website URL String:</b></td>
								<td class=fieldcellnormal> {$req.companyurl}
									<input type='hidden' value="{$req.companyurl}" class="inputB" name='url' >
								</td>
							</tr>
							{if $req.attribute ne '0'}
							<tr>
								<td width='20%'><b>Address:</b></td>
								<td class=fieldcellnormal>
									<input type='text' value="{$req.addressline}" class="inputB" name='linethree' id="linethree" readonly>
								</td>
							</tr>
							<tr>
								<td width='20%'><b>Phone:</b></td>
								<td class=fieldcellnormal>
									<input type='text' value="Phone: {$req.phoneline}" class="inputB" name='linefour' id="linefour"readonly>
								</td>
							</tr>
							{/if}
							<tr>
								<td width='20%'><b>State{if $smarty.session.attribute neq '5'} Page{/if}:</b></td>
								<td class=fieldcellnormal>
									<input type='text' value="{$req.statename}" class="inputB" name='statepage' readonly >
								</td>
							</tr>
                            {if $smarty.session.attribute eq '5'}
							<tr>
								<td width='20%'><b>{$lang.labelCouncil}:</b></td>
								<td class=fieldcellnormal>
									<input id="council" type='text' value="{$req.council}" class="inputB" name='council' readonly >
								</td>
							</tr>
							<tr>
								<td width='20%'><b>Featured Product:</b></td>
								<td class=fieldcellnormal>
									<select class="text" name="featured_product" id="featured_product" style="border:1px solid #CCCCCC; min-width:287px;">
                                    <option value=""></option>
                                    {foreach from=$req.products item=product}
                                            <option value="{$product.pid}">{$product.item_name}</option>
                                    {/foreach}
                                    </select>
								</td>
							</tr>
                            {/if}
							{if $smarty.session.attribute eq '0'}
							<tr>
								<td width='20%'><b>College Name:</b></td>
								<td class=fieldcellnormal>
									<input type="hidden" name="collegeid2" value="{$req.collegeid}">
									<input type='text' value="{$req.collegename}" class="inputB" name='collegename' readonly >
								</td>
							</tr>
							<tr>
								<td width='20%'><b>Display Page:</b></td>
								<td class=fieldcellnormal><span style="border: 2px solid #FF0000;position:absolute;overflow:hidden; margin-top:-8px;"><select name="displaypage" class="select" style="border:0px;margin:-1px;">
										<option value="State">State</option>
										<option value="College">College</option>
									</select></span></td>
							</tr>
							{else}
								<input type="hidden" name="displaypage" value="{if $smarty.session.attribute eq 1}Estate{elseif $smarty.session.attribute eq 2}Auto{elseif $smarty.session.attribute eq 3}Job{elseif $smarty.session.attribute eq 5}FoodWine{/if}" />
							{/if}
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<!--<tr>
								<td colspan="2"><font size="2" color="red"><STRONG>NOTE: The text in your ad cannot be changed once you submit it.</STRONG></font><BR></td>
							</tr>-->
							<tr>
								<td>&nbsp;</td>
								<td><input type="image" src="skin/red/images/buttons/or-submit.gif" value="Book Your Ad"><BR>
							  <BR></td>
							</tr>
						</table>
						</form>
						{literal}
						<script type="text/javascript">
						<!--//
						function checkAdForm()
						{
							if (document.getElementById('lineone').value == '')
							{
								alert('Please fill in Business Name.');
								document.getElementById('lineone').focus();
								return false;
							}
							if (document.getElementById('linetwo') && document.getElementById('linetwo').value == '')
							{
								alert('Please fill in Tagline.');
								document.getElementById('linetwo').focus();
								return false;
							}
							if (document.getElementById('linethree').value == '')
							{
								alert('Please fill in Address.');
								document.getElementById('linethree').focus();
								return false;
							}
							if (document.getElementById('linefour').value == '')
							{
								alert('Please fill in Phone.');
								document.getElementById('linefour').focus();
								return false;
							}
							
							{/literal}{if $smarty.session.attribute eq '5'}{literal}
							if (document.getElementById('council').value == '')
							{
								{/literal}alert('Please fill in {$lang.labelCouncil}.');{literal}
								document.getElementById('council').focus();
								return false;
							}		
							if (document.getElementById('featured_product').value == '')
							{
								alert('Please select a featured product.');
								document.getElementById('featured_product').focus();
								return false;
							}					
							{/literal}{/if}{literal}							
							
							return true;
						}
						//-->
						</script>
						{/literal}
						
					  </td>

                    </tr>

                  </table>
				  {else}		  
				  <table width="100%" cellpadding="0" cellspacing="0" border="0">
				  	<tr>
						<td style="color:#FF0000;">{$req.msg}</td>
					</tr>
				  </table>
				  
				  {/if}
		{/if}