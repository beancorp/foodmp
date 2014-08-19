<table width="100%"  border="0" cellspacing="0" cellpadding="0">

                    <tr> 

                      <td height="28" colspan="2" valign="top" class="headsadmin">

					  

					  <table id="table14" cellspacing="0" cellpadding="0" border="0" width="100%">

						<tr height="28" valign="top">

							<td valign="center" align="left" class="headsadmin">Your Progress: Finish</td>

							<td align="right">

								<a href="javascript:;" onClick="waspPopup('/videos/finish.flv', 384, 288)">

									<img src="images/vidtutorial.png" border="0">

								</a>

							</td>

						</tr>

					  </table>

					  </td>

                    </tr>

                    <tr> 

                      <td colspan="2">

                      {include_php file="nav.php"}</td>

                    </tr>

                    <tr>

                      <td colspan="2" class="pad10px"> <strong>CONGRATULATIONS, your store has been completed. </strong> Simply click on &lsquo;continue to payment page&rsquo; to complete the order.</td>

                    </tr>

                    <tr> 

                      <td colspan="2" class="pad10px">Purchase your store now. It is only $1 per day. Your credit card will be debited $365. This will provide you with 365 days of online membership.</td>

                    </tr>

                    <tr> 

                      <td colspan="2"  align="center" class="star"><b> {$req.errors} </b></td>

                    </tr>

 <form  name="paypall" action="https://www.paypal.com/cgi-bin/webscr" method="post">

          <input type="hidden" name="cmd" value="_xclick">

          <input type="hidden" name="business" value="info@thesocexchange.com">

           <input type="hidden" name="item_name" value="Deposit money in your account">                                                   

           <input type="hidden" name="amount" value="365"> 

           <input type="hidden" name="currency_code" value="AUD"> 

		   <input type="hidden" name="item_number" value="{$req.StoreID}">


           <input type="hidden" name="StoreID" value="{$req.StoreID}"> 

           <input type="hidden" name="return" value="http://www.buyblitz.com/activate.php">

          <input type="hidden" name="cancel_return" value="http://www.buyblitz.com/finalstep.php">

          <input type="hidden" name="notify_url" value="http://www.buyblitz.com/activate.php">

					

                    <tr>

                      <td align="center" valign="top" class="pad10px"><img src="images/amex.gif" border="0"/><img src="images/mastercard.gif" border="0"/><img src="images/visa.gif" border="0"/><img src="images/paypal_intl.gif" border="0"/></td>

                      <td align="center" valign="top" >&nbsp;</td>

                    </tr>

                    <tr>

                      <td align="center" valign="top" class="pad10px">

                        <table width="200" border="0">

                          <tr>

                            <td height="35" align="center" bgcolor="#FFFF99"><span class="hbutton"><input type="submit" value="Countinue To Payment Page" class="greenButt2"></span></td>

                          </tr>

                        </table></td>

                      <td align="center" valign="top" ><table cellpadding="10" width="150" height="100" bgcolor="ffff99"><tr><td valign="middle" align="center">

                        <strong>STORE PREVIEW</strong><br />

                        To preview the store you have just created <a href="productDispay.php?StoreID={$req.StoreID}" target="_blank"><strong>click here</strong></a></td>

                      </tr></table></td>

                    </tr>

</form>					

                    <tr> 

                      <td colspan="2" class="pad10px"><div align="center">

                        <p align="left"><strong>Please note: You will now be directed to Paypal to process your payment.</strong> Do not close the screen during payment. You must complete the payment section and then you will be redirected back to Food Marketplace Australia / Buyblitz.com.</p>

                        <p align="left"></p>

						</div></td>

                    </tr>

                    <tr> 

                      <td colspan="2" class="pad10px"><HR></td>

                    </tr>


                  </table>