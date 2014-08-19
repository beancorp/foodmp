
{literal}
	<style type="text/css">
		#div_detail {
			margin:0,auto;
		}
		#div_detail h2{
			color:#3C3481;
			text-align:left;
			font-size:1.5em;
			margin:0 0 12px;
		}
		#div_detail table td{
			text-align:left;
		}
	
	</style>
{/literal}

<div style="clear:both;width:100%;height:30px;"></div>
	<div id="div_detail">
<div class="sec-details" style="float:none;margin:0 auto;">
                	<div class="bottom">
                    	<div class="top">
                        	<h2>Bidder's Details</h2>
                            <table width="100%" border="1" cellspacing="0" cellpadding="0" class="details">
							
							<tr>
                                <td class="left">Auction Name:</td>
                                <td><samp title="{$certified_info.full_name}">{$product_info.item_name}</samp></td>
                              </tr>
                              <tr>
							
							<tr>
                                <td class="left">{$lang.labelNickName}:</td>
                                <td><samp title="{$certified_info.full_name}">{$nickname}</samp></td>
                              </tr>
                              <tr>
							
							<tr>
                                <td class="left">{$lang.labelEmail}:</td>
                                <td>{$certified_info.contact_email}</td>
                              </tr>
							
                              <tr>
                                <td class="left">{$lang.labelName}:</td>
                                <td><samp title="{$certified_info.full_name}">{$certified_info.full_name}</samp></td>
                              </tr>
                              
                        
                             <tr>
                                <td class="left">{$lang.labelCountry}:</td>
                                <td>{$country_name.country_name}</td>
                              </tr>
                              <tr>
                                <td class="left">{$lang.labelState}:</td>
                                <td>{$state_name}</td>
                              </tr>
							  
							  <tr>
                                <td class="left">{$lang.labelCity}:</td>
                                <td>{$certified_info.city}</td>
                              </tr>
							  <tr>
                                <td class="left">{$lang.labelZIP}:</td>
                                <td>{$certified_info.post_code}</td>
                              </tr>
							  <tr>
                                <td class="left">{$lang.labelPhone}:</td>
                                <td>{$certified_info.contact_phone}</td>
                              </tr>
							  <tr>
                                <td class="left">{$lang.langIdentify_1}</td>
                                <td></td>
                              </tr>
							  	<tr>
                                <td class="left">Name:</td>
                                <td>{$certified_info.certified_name1}</td>
                              </tr>
							  <tr>
                                <td class="left">Email:</td>
                                <td>{$certified_info.certified_email1}</td>
                              </tr>
							  <tr>
                                <td class="left">Phone:</td>
                                <td>{$certified_info.certified_phone1}</td>
                              </tr>
							  
							  <tr>
                                <td class="left">{$lang.langIdentify_2}</td>
                                <td></td>
                              </tr>
							  	<tr>
                                <td class="left">Name:</td>
                                <td>{$certified_info.certified_name2}</td>
                              </tr>
							  <tr>
                                <td class="left">Email:</td>
                                <td>{$certified_info.certified_email2}</td>
                              </tr>
							  <tr>
                                <td class="left">Phone:</td>
                                <td>{$certified_info.certified_phone2}</td>
                              </tr>
							  
							  
							  
							  <tr>
                                <td class="left">Comments:</td>
                                <td>{$certified_info.product_comments}</td>
                              </tr>
							  
                            </table>
                         
                      </div>
                    </div>
                </div></div>