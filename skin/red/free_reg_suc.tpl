{if $smarty.session.attribute == 3 and $smarty.session.subAttrib == 3}
<h2 class="adminTitle">Start Selling&nbsp;<a href="javascript:history.go(-1)">Back</a></h2><table width="100%" cellspacing="0" cellpadding="0" border="0">
	
  <tbody><tr align="center">
	
    <td width="15%"><a class="anch" href="{$https_host}soc.php?act=signon"><img border="0" src="/skin/red/images/navimages/detail_active.gif"></a></td>
  
     <td width="20%"><a class="anch" href="{$https_host}soc.php?act=signon&amp;step=4"><img border="0" src="/skin/red/images/navimages/design_info.gif"></a></td>
	 
   
    <td width="20%" valign="top"><div style="vertical-align: top; padding-top: 10px;"><a style="font-family: Arial; font-size: 14px; font-weight: bolder; text-decoration: none;" href="/testball26url">Launch</a></div></td>

  </tr>
        <tr valign="top" align="center">
	<td height="40" style="font-size: 11px; height: 50px; color: rgb(119, 119, 119);">Website Details</td>
	
			<td style="font-size: 11px; height: 50px; color: rgb(119, 119, 119);">Specify Product</td>
	</tr>
</tbody></table>

{else}

<h2 class="adminTitle">Start Selling&nbsp;<a href="javascript:history.go(-1)">Back</a></h2><table width="100%" cellspacing="0" cellpadding="0" border="0">
	
  <tbody><tr align="center">
	
    <td width="15%"><a class="anch" href="{$https_host}soc.php?act=signon"><img border="0" src="/skin/red/images/navimages/detail_active.gif"></a></td>
 
    <td width="20%"><a class="anch" href="{$https_host}soc.php?act=signon&amp;step=2"><img border="0" src="/skin/red/images/navimages/design_info.gif"></a></td>

    <td width="25%"><a class="anch" href="/soc.php?act=signon&amp;step=3"><img border="0" src="/skin/red/images/navimages/design_theme.gif"></a></td>

    <td width="20%"><a class="anch" href="/soc.php?act=signon&amp;step=4"><img border="0" src="/skin/red/images/navimages/product.gif"></a></td>
	
   
    <td width="20%" valign="top"><div style="vertical-align: top; padding-top: 10px;"><a style="font-family: Arial; font-size: 14px; font-weight: bolder; text-decoration: none;" href="/{$req.info.website_name}">View my website</a></div></td>

  </tr>
        <tr valign="top" align="center">
	<td height="40" style="font-size: 11px; height: 50px; color: rgb(119, 119, 119);">Website Details</td>
	
	    <td style="font-size: 11px; height: 50px; color: rgb(119, 119, 119);">Details / Featured Image</td>
    <td style="font-size: 11px; height: 50px; color: rgb(119, 119, 119);">Template / Color / Icon</td>
	
    <td style="font-size: 11px; height: 50px; color: rgb(119, 119, 119);">Specify Product</td>
	</tr>
</tbody></table>
{/if}
<div style="color:#3b2f81; font-size:18px; font-weight:bold;">Welcome and thank you for joining 'Food Marketplace'</div>
<div style="font-size:13px; font-weight:normal; color:#777777; margin-top:17px; font-family:Arial, Helvetica, sans-serif; width:528px;">

<div>We are truly proud to be supporting Pat Farmer on his extraordinary 'Pole to Pole Run' and to also be supporting the Red Cross.</div>

<div style="margin-top:15px;">If you have not previously donated, you now have the opportunity by donating the waived $10 'Food Marketplace' membership fee to the Red Cross. </div>

<div style="margin-top:15px; font-style:italic; font-weight:bold;">This donation is strictly voluntary and there is absolutely no obligation!</div>

<div style="margin-top:15px;">Please click one of the links below and thank you again for joining 'Food Marketplace' marketplace.</div></div>


	<div style="background:url(/skin/red/images/free_payment/reg_free_suc_bg.gif) top left no-repeat; width:545px; height:212px; margin-top:30px;">
		
			<div style="padding-left:15px;">
			
				<div style="padding-top:30px;">
					<span style="color:#3b2f81; font-size:14px; font-weight:bold; text-decoration:none;">I would like to go to my SOC Admin </span>
					<a href="/soc.php?cp=sellerhome" title="SOC Admin" style="margin-left:55px;"><img src="/skin/red/images/free_payment/btn_soc_admin.png" alt="SOC Admin" title="SOC Admin" style=" vertical-align:middle;"/></a>
				</div>
				
				<div style="margin-top:30px;">
					<span style="color:#f70023; font-size:14px; font-weight:bold; text-decoration:none;">I would like to donate to the Red Cross</span>
					<a href="https://www.redcross.org.au/Donations/Pole2PoleRunForWater.asp" title="DONATE" style="margin-left:25px;" target="_blank"><img src="/skin/red/images/free_payment/btn_donate.png" alt="DONATE" title="DONATE" style=" vertical-align:middle;"/></a>
				</div>
				
				<div style="color:#777777; font-size:12px; margin-top:15px;">
					You will now be directed to the 'Pole to Pole' donation page on the Red Cross website.<br/>
You will return to this page when you close the Red Cross window.
				</div>
				
			</div>
	</div>