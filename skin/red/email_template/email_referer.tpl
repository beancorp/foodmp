<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>{$req.Subject}</title>
</head>
<body>
 {if $req.preview} {literal}
<style type="text/css">
 	body {
		margin:0;
		padding:0;
	}
</style>
 {/literal} {/if}
<table width="{if $req.is_in_website}700{else}700{/if}" border="0" align="center" cellpadding="0" cellspacing="0">
 {if $req.preview}
<tr>
	<td width="700" scope="col" style="background-color:#777777;">
		<div style="height:15px; float:right; padding:5px;">
			<a style="color: rgb(255, 255, 255); font-size: 13px;font-family:Arial, Helvetica, sans-serif;" href="javascript:window.close();">Close</a>
		</div>
	</td>
</tr>
{/if}
<tr>
	<td width="700" height="93" scope="col">
		<img src="{$req.webside_link}/skin/red/images/email_referer/header1.jpg"/>
	</td>
</tr>
<tr>
	<td>
		<table width="{if $req.is_in_website}700{else}700{/if}" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="559" scope="col">
				<div align="left">
					<br/>
					<div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:14px;">
						Hi {$req.nickname}, <br /><br />
					</div>
					<div style="margin-top:0; font-family:Arial, Helvetica, sans-serif;font-weight:normal; font-size:14px;">
						{if $req.personal_note}
							{$req.personal_note}
							<br /><br />
						{/if}
						<em style="font-size:18px; color:red;">Take your business online for just <strong>$1 a day!</strong></em>
						<br /><br />
						Advertise your specials instantly and connect with your customers personally. It’s so easy, your business can have its own website in around 15 minutes.
						<br /><br />
						You can customise your website and even offer online shopping. There are no extra charges and the set-up is quick and easy. Now is the perfect time for you
						to join the FoodMarketplace community and talk to your customers the smart way, online.
						<br /><br />
						All you need to do is follow the two steps below:<br /><br />
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td width="559">
				<div align="left">
					
					<table width="559" border="0" cellpadding="0" cellspacing="0" class="" style="border-collapse:collapse;">
						<tr height="70" style="background-color:#F7F6F4;">
							<td width="69" height="62" align="center" valign="middle" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-right:none; text-align: left; ">
								<div align="center">
									<img src="{if !$req.preview}{$req.webside_link}{/if}/skin/red/images/email_referer/icon_001.jpg"/>
								</div>
							</td>
							<td width="490" height="62" scope="col" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-left:none; text-align: left;">
								<div align="left" style="font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:14px;">
									 Click on this Registration link <br /><a href="{$smarty.const.SOC_HTTPS_HOST}registration.php?referr={$req.refID}" style="font-size:14px; font-weight:bold; text-decoration:none; color:#7DB7F0;" title="FoodMarketplace">www.{$smarty.const.DOMAIN}/registration.php</a>
								</div>
							</td>
						</tr>
						<tr height="70" style="background-color:#F7F6F4;">
							<td width="69" align="center" valign="middle" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-top:none; border-right:none; border-bottom: none; text-align: left;">
								<div align="center">
									<img src="{if !$req.preview}{$req.webside_link}{/if}/skin/red/images/email_referer/icon_002.jpg"/>
								</div>
							</td>
							<td width="490" style="border-width:1px; border-color:#D5D5D5; border-style:solid; border-left:none; border-top:none; border-bottom: none; text-align: left;">
								<div align="left" style="font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:14px;">
									Complete the Registration Form.
								</div>
							</td>
						</tr>
						<tr style="background-color:#F7F6F4;">
							<td colspan="2" valign="top">
								<img src="{if !$req.preview}{$req.webside_link}{/if}/skin/red/images/email_referer/table_layout_b.jpg" style="vertical-align: top;" />
							</td>
						</tr>
					</table>
					
				</div>
			</td>
		</tr>
		<tr>
			<td width="559">
				<div align="left" style="font-family:Arial, Helvetica, sans-serif;font-size:14px; ">
					<div style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal;">
					<br /><br />
						My <strong style="font-weight:bold;">Referral ID</strong> is <strong style="font-size:20px; color:red; font-weight:bold;">{$req.refID}</strong>.
					<br /><br />
						This will be automatically inserted in the Referral ID field when you click on the link in Step 1 above.
					<br /><br />
						I really want to help spread the word about FoodMarketplace. Independent food retailers advertising and selling their products online is not only very
						smart, it will also make their customers very happy!
					<br /><br />
					{$req.inscription|nl2br}
					<br /><br />
					<br /><br />
					<strong>About {$smarty.const.SITENAME}</strong>
					<br />
						{$smarty.const.SITENAME} is Australia’s online food and wine marketplace. It has been especially designed to suit independent food and wine retailers of all
						sizes. From the small family business to the multi-store independent, FoodMarketplace is the platform where you can instantly open your website and connect
						with your customers via their computer and mobile phone, 24 hours a day / 7 days a week.
					<br /><br />
				</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</body>
</html>