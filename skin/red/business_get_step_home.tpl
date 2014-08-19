<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
			<table width="100%">
					{if $req.msg1 ne ''}
                    <tr>
                      <td height="28" align="left" valign="top" class="headsadmin">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="center" style="padding-left:10px; "><font color="#FF0000"><b>
                        {$req.msg1}
                      </b></font></td>
                    </tr>
                    {/if}
                    <tr>
					<BR>
                      <!--<td height="28" align="left" valign="top" class="headsadmin">&nbsp;</td>-->

					  <td align="center" valign="top" >
					  <table cellpadding="10" width="100%" height="50" bgcolor="#ffff99">
					  <tr>
					  	<td valign="middle" align="center">
                        <strong>VIEW YOUR WEBSITE</strong><BR>
						<a href="/{$req.website_name}">
						<strong>click here</strong></a>
						</td>
						<td align="right">
							<a href="javascript:;" onClick="waspPopup('/videos/ba.flv', 384, 288)"></a>
						</td>

                      </tr></table>
                      </td>

                    </tr>

					

                      <td><table width="100%" height="100%" border="0">

                        <tr>

						

                          <td style="padding-left:10px;"><p>Welcome <b>{$session.NickName}</b> to 
                          {if $req.CatID eq 11}
                          your administration system. To edit your website
                          {else}
                          the website admin. To edit your website
                          {/if}
                          <a href="soc.php?act=signon"><strong>click here </strong></a>. </p></td>
                        </tr>

                        <tr>

                          <td>&nbsp;</td>
                        </tr>



                        <tr>

                          <td><table width="100%" border="0" cellspacing="0" cellpadding="8">

                              <tr>

                                <td bgcolor="#DFEAFF" scope="col" style="padding-left:10px; ">
                               {if $session.CatID eq 11}
								<a href="emailAlerts.php"><strong>Click here</strong></a> to send an <strong>email alert</strong> to your subscribers containing all the items for sale on your website.
{else}
								<a href="emailAlerts.php"><strong>Click here</strong></a> to send an <strong>email alert</strong> to your subscribers, containing all the specials from your store<a href="emailAlerts.php"></a>. Please check your specials before sending your email alert.
								{/if}								</td>
                              </tr>

                          </table></td>
                        </tr>
						
{if !($smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3)}
						
                        <tr>
                          <td height="44" bgcolor="#FAF3DE">
                            <table width="100%" border="0" cellpadding="8" cellspacing="0">
                              <tr>
                                <td style="padding-left:10px; "><span class="star"><a href="soc.php?cp=blog&StoreID={$session.StoreID}&pageid=1" target="_self"><strong>Click here</strong></a></span> to enter the Blog Management page. {if $req.approval_count gt 0}You have <b>{$req.approval_count} new comments</b> for approval.{/if}</td>
                              </tr>
                            </table></td>
                        </tr>
{/if}
						
                        {if $req.total_order_count gt 0}
                        <tr>
                          <td style="padding-left:10px; "><br /><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="45%" valign=top> You currently have <b>{$req.totalNumber}</b> members subscribed.
								   <br>
								   <br>You have had <a href="soc.php?cp=business_get_step_stat" target="_self"><b>{$req.clickNumber} visitors</b></a> to your store.</br></br></td>
                              <td width="55%" valign=top align=left><div ID="order" style="border: 1px solid #00F;padding: 5px;" width=100%><font color=red>You have received a Gift Certificate purchase and/or online store order.  <br><br>Please <a href="orders.php" style="color:blue;text-decoration:underline;"><strong>click here</strong></a> to process.</font></div></td>
                            </tr>
                          </table></td>
                        </tr>
						{else}
						<tr>

                          <td style="padding-left:10px; "><br />

                            You currently have <b>{$req.totalNumber}</b> members subscribed.

                           <br>

                           <br>You have had <a href="soc.php?cp=business_get_step_stat" target="_self"><b>{$req.clickNumber} visitors</b></a> to your website.</br></br></td>
                        </tr>
                        {/if}
                        <tr>

                          <td>&nbsp;</td>
                        </tr>


                        <tr>
                          <td>
                              <table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="FFFFCC">
                                <tr>
                                  <td style="padding-left:10px; " align="left" bgcolor="#FFFFCC" scope="col">The customer you are contacting will not be able to see your email address when this message arrives. You may continue to communicate through Food Marketplace, or if you would like them to contact you directly you should include your email address in the message box.
<br /><p><b>Your Email Messages ({$req.messageCount gt})<br /></b> </p>

                                      <div style=" float:left;width:100px;padding-top:2px;"> <a href="soc.php?cp=inbox">Open Mail</a> </div>
									  <div style=" float:left;width:270px;height:19px;"><input name="outerEmail" type="checkbox" id="outerEmail" value="{$session.outerEmail}" {if $session.outerEmail eq 1}checked{/if} onclick="{$req.element.jsSaveOuterEmail}" style="float:left;"><div style="float:left;padding-left:2px;padding-top:2px;">Forward the emails to my external mail box.</div>
	</div>
									  <div id="outerEmailClew" style="float:left; width:350px;color:red;height:19px;padding-top:2px;"></div>
								</td>
                                </tr>
                              </table>
							</td>
                        </tr>
						
						{if $session.attribute eq '0'}
						<tr>
			   			<td>&nbsp;</td>
                        </tr>
						
						<tr>
							<td>
                              <table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="#DFEAFF">
                                <tr bgcolor="#DFEAFF">
                                  <td style="padding-left:10px; " align="left" bgcolor="#DFEAFF" scope="col"><p><span style="color:#FF0000">You have {$req.offerCount } offer(s).</span></b><br />
                                    <span style="color:#FF0000">You have {$req.offerReceivedCount} buyer’s response(s).</span></p>
                                  <div> <a href="soc.php?act=offer&cp=offerlist">Open</a> </div></td>
                                </tr>
                              </table>
							  </td>
                        </tr>
						{/if}
                      </table>                      
                      <p>&nbsp;</p>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
    				  </td>
                    </tr>
                    <tr>
                      <td >
                      {literal}
						<script>
						function del(id) {
							if(confirm('Do you want to delete the message')) { 
								window.location.href = 'soc.php?cp=business_get_step_home&msgid=' + id + '&action=del' 
							}
						}
						</script>
						{/literal}
					  </td>
                    </tr>
                  </table>