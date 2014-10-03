<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<link type="text/css" href="/static/css/fanfrenzy.css" rel="stylesheet" media="screen" />
<script src="/skin/red/fanpromo/promo.js" type="text/javascript"></script>
{literal}
<style type="text/css">
	#rightCol{position:relative;}
	div.marquee{position:absolute;top:14px;right:10px;}
	div.marquee p{margin:0; color:#FFF;}
	
	#mid-content .sec-details .bottom .top {
		_height:202px;
		*height:202px;
	}
	.bottom-banner{padding-top:20px;}
	.bottom-banner .seller-banner{padding-right:10px;}
	.step {color: #453C8D; font-weight: bold;}
</style>
{/literal}
  <!--div class="marquee">
        <marquee behavior="scroll" direction="left" scrollamount="1" width="580"><p>{if $req.email.messageCount}You have {$req.email.messageCount} new email(s). {/if} {if $req.offer.newCount}&nbsp;&nbsp;&nbsp;&nbsp;You have {$req.offer.newCount} new offer(s). {/if} {if $req.email.NewmessageCount}&nbsp;&nbsp;&nbsp;&nbsp;You have {$req.email.NewmessageCount} {if $smarty.session.attribute eq 3}Employer{else}Buyer's{/if} Response(s). {/if}</p></marquee>
 </div-->
{if $req.regsuc && $req.regsuc eq 'e91104e4cac2b3b86243c321b326c7b7'}
	<script type='text/javascript'>
<!--//<![CDATA[
    var p = (location.protocol=='https:'?'https://tpnads.com/delivery/tjs.php':'http://tpnads.com/delivery/tjs.php');

    var r=Math.floor(Math.random()*999999);
    document.write ("<" + "script language='JavaScript' ");
    document.write ("type='text/javascript' src='"+p);
    document.write ("?trackerid=597&amp;append=0&amp;r="+r+"'><" + "\/script>");

//]]>-->
</script>
<noscript>
<div id='m3_tracker_597' style='position: absolute; left: 0px; top: 0px; visibility: hidden;'>
	<img src='https://tpnads.com/delivery/ti.php?trackerid=597&amp;cb={$req.randnum}' width='0' height='0' alt='' />
</div>
</noscript>
{/if}


{if $req.suspend}
<div style="text-align:center;">
	<div id="suspend_top" style="width:100%;height:80px;text-align:right;"><img onclick="javascript:$('#suspend_top').hide();$('#suspend_mid').hide();$('#suspend_footer').hide();" src="/images/fullscreen_maximize.gif" style="cursor:pointer"/></div>
	<strong style="color:red;font-size:16px;">Your account has been suspended.</strong>
	<div id="suspend_mid" style="width:100%;height:13px;"></div>
	<div>Please contact <a href="/soc.php?cp=contact">Food Marketplace Admin</a> department to resolve this issue.</div>
	<div id="suspend_footer" style="width:100%;height:100px;"></div>
</div>
{/if}
{if $req.msg ne ''}
<div class="publc_clew" style="height:30px;">{$req.msg}</div>
{/if}
		{include file="seller_home_rightmenu.tpl"}            
        	<h1 class="soc-admin">Welcome to your Admin Panel</h1>
			{if $account_status eq 0}
				<style>
					{literal}
						#not_activated {
							background-color: #e00702;
							border: 1px solid #000;
							padding: 10px;
							border-radius: 10px;
						}
						#not_activated li {
							color: #FFF;
							font-size: 12px;
							font-weight: bold;
						}
					{/literal}
				</style>
				<div id="not_activated">
					<ul>
						<li>Your website will 'GO LIVE' once Step 6 has been completed.</li>
						<li>Your Account Information in Step 1 (URL, website name, etc) will be secured once Step 6 has been completed.</li>
					</ul>
				</div>
			{/if}
			
            <div id="mid-content">
            	<!-- Start Summary -->
				<p>
				{if $account_status eq 1}
					<!--
					{if $subscriber}
						<a href="/soc.php?cp=subscriber"><img src="/skin/red/images/support_banner.jpg" width="690" height="50" /></a>
					{else}
						<a href="/soc.php?cp=subscribe"><img src="/skin/red/images/subscription_image.jpg" width="690" height="50" /></a>
					{/if}
					-->
          {if $smarty.const.SHOW_REWARDS_BANNER }
		  
					<a href="/myrewards">
						<img src="/skin/red/referralrewards/rewards_banner.jpg" />
					</a>
			
          {/if}
				{/if}
				</p>
            	<div class="sec-summary">
                	<div class="bottom">
                    	<h2>Current Activity Summary</h2>
                        {if $smarty.session.attribute ne 5}
                        {if $smarty.session.attribute eq 2 || $smarty.session.attribute eq 1 || ($smarty.session.attribute eq 3 && $smarty.session.subAttrib neq 3)}
                        <ul class="clearfix">
                        	<li>
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                <tr>
                                    <td class="left">Purchased Items:</td>
                                    <td><a href="/soc.php?cp=purchase" style="{if $req.count.purchase>0}color:red;{/if}">{$req.count.purchase}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">Watch item:</td>
                                    <td><a href="/soc.php?cp=watchitemlist">{$req.count.watch}</a></td>
                                  </tr>
                                </table>
                            </li>
                            <li>
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                  <tr>
                                    <td class="left">{ if $smarty.session.subAttrib < 7 && $smarty.session.subAttrib ne 1 }Stock Items{else}Menu Items{/if}:</td>
                                    <td><a href="/soc.php?act=signon&step=4&type=sale#list">{$req.product.count}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">Subscribers:</td>
                                    <td><a href="/emailAlerts.php">{$req.email.userAlertCount}</a></td>
                                  </tr>
                                </table>
                            </li>
                            <li class="last">
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                  <tr>
                                    <td class="left">Site visitors:</td>
                                    <td><a href="/soc.php?cp=business_get_step_stat">{$req.detail.clickNumber}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">New messages:</td>
                                    <td><a href="/soc.php?cp=inbox" style="{if $req.email.messageCount>0}color:red;{/if}">{$req.email.messageCount}</a></td>
                                  </tr>
                                </table>
                            </li>
                        </ul>
                        {elseif $smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3}
                        	  <ul class="clearfix">
                        	<li>
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                <tr>
                                    <td class="left">Purchased Items:</td>
                                    <td><a href="/soc.php?cp=purchase" style="{if $req.count.purchase>0}color:red;{/if}">{$req.count.purchase}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">Watch item:</td>
                                    <td><a href="/soc.php?cp=watchitemlist">{$req.count.watch}</a></td>
                                  </tr>
                                </table>
                            </li>
                            <li>
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                  <tr>
                                    <td class="left">Site visitors:</td>
                                    <td><a href="/soc.php?cp=business_get_step_stat">{$req.detail.clickNumber}</a></td>
                                  </tr>
                                </table>
                            </li>
                            <li class="last">
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                 
                                  <tr>
                                    <td class="left">New messages:</td>
                                    <td><a href="/soc.php?cp=inbox" style="{if $req.email.messageCount>0}color:red;{/if}">{$req.email.messageCount}</a></td>
                                  </tr>
                                </table>
                            </li>
                            </ul>
                        {else}
                        <ul class="clearfix">
                        	<li>
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                  <tr>
                                    <td class="left">Watched items:</td>
                                    <td><a href="/soc.php?cp=watchitemlist">{$req.count.watch}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">Purchased items:</td>
                                    <td><a href="/soc.php?cp=purchase">{$req.count.purchase}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">Sold items:</td>
                                    <td><a href="/soc.php?cp=saleshistory">{$req.count.sold}</a></td>
                                  </tr>
                                </table>
                            </li>
                            <li>
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                  <tr>
                                    <td class="left">Items for auction:</td>
                                    <td><a href="/soc.php?act=signon&step=4&type=auction#list">{$req.count.auction}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">{ if $smarty.session.subAttrib < 7 && $smarty.session.subAttrib ne 1 }Stock Items{else}Menu Items{/if}:</td>
                                    <td><a href="/soc.php?act=signon&step=4&type=sale#list">{$req.count.product}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">Site visitors:</td>
                                    <td><a href="/soc.php?cp=business_get_step_stat">{$req.detail.clickNumber}</a></td>
                                  </tr>                                </table>
                            </li>
                            <li class="last">
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                  <tr>
                                    <td class="left">New messages:</td>
                                    <td><a href="/soc.php?cp=inbox" style="{if $req.email.messageCount>0}color:red;{/if}">{$req.email.messageCount}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">New Offers:</td>
                                    <td><a href="/soc.php?act=offer&cp=offerlist" style="{if $req.offer.newCount>0}color:red;{/if}">{$req.offer.newCount}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">Certified Bidders:</td>
                                    <td><a href="/soc.php?cp=certified&act=list">{$req.certified.count}</a></td>
                                  </tr>
                                </table>
                            </li>
                        </ul>
                        {/if}
                        {else}
                        <ul class="clearfix">
                        	<li>
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                {if $req.foodwine_type eq 'food'}
                                  <tr>
                                    <td class="left">New Orders:</td>
                                    <td><a href="/foodwine/?act=order&StoreID={$smarty.session.StoreID}" {if $req.new_online_order_num>0}class="blink"{/if}>{$req.new_online_order_num}</a></td>
                                  </tr>
                                {else}
                                <tr>
                                    <td class="left">New Bookings:</td>
                                    <td><a href="/foodwine/?act=book&StoreID={$smarty.session.StoreID}" {if $req.new_online_book_num>0}class="blink"{/if}>{$req.new_online_book_num}</a></td>
                                  </tr>
                                {/if}
                                  <tr>
                                    <td class="left">Reviews:</td>
                                    <td><a href="/soc.php?cp=disreview&StoreID={$req.info.StoreID}">{$req.info.reviews}</a></td>
                                  </tr>
                                </table>
                            </li>
                            <li>
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                  <tr>
                                    <td class="left">{ if $smarty.session.subAttrib < 7 && $smarty.session.subAttrib ne 1 }Stock Items{else}Menu Items{/if}:</td>
                                    <td><a href="/soc.php?act=signon&step=4&type=sale#list">{$req.product.count}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">Fans:</td>
                                    <td><a href="/foodwine/?act=emailalerts&cp=viewsubscribers">{$fan_count}</a></td>
                                  </tr>
                                </table>
                            </li>
                            <li class="last">
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                  <tr>
                                    <td class="left">Site visitors:</td>
                                    <td><a href="/soc.php?cp=business_get_step_stat">{$req.detail.clickNumber}</a></td>
                                  </tr>
                                  <tr>
                                    <td class="left">New messages:</td>
                                    <td><a href="/soc.php?cp=inbox" style="{if $req.email.messageCount>0}color:red;{/if}">{$req.email.messageCount}</a></td>
                                  </tr>
                                </table>
                            </li>
                        </ul>
                        {/if}
                    </div>
                </div>
                <!-- End Summary -->
                
                <!-- Start Your Details -->
                <div class="sec-details">
                	<div class="bottom">
                    	<div class="top" {if $smarty.session.attribute eq 1 || $smarty.session.attribute eq 2 || ($smarty.session.attribute eq 3 &&$smarty.session.subAttrib neq 3)}style="height:223px;"{/if}>
                        	<h2>Your Details</h2>
                            <table width="100%" border="1" cellspacing="0" cellpadding="0" class="details">
                              <tr>
                                <td class="left">{$lang.labelEmail}:</td>
                                <td><samp title="{$smarty.session.email}">{$smarty.session.email|truncate:25}</samp></td>
                              </tr>
                              <tr>
                                <td class="left">Password:<br/><a href="/soc.php?cp=changepass">Change password</a></td>
                                <td>**********</td>
                              </tr>
							  {if $free_signup}
								<tr>
									<td colspan="2">{if $account_status}<a href="soc.php?cp=sellerhome&deactivation=2">Disable Account</a>{else}<a href="soc.php?cp=sellerhome&deactivation=1">Enable Account</a>{/if}</td>
								</tr>
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td><img src="/skin/red/images/adminhome/icon-myemail.gif" align="absmiddle" style="float:left; width:auto;"><a href="/soc.php?cp=customers_geton_alerts" style="float:left; width:100px; margin:3px;">{$lang.myemailalerts}&nbsp;</a></td>
									<td><div class="countinfo" style="width:30px; margin:3px;text-align: center;">{$alert.emailAlert}&nbsp;</div></td>
								</tr>
							  {/if}
                        {if !($smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3)}
                             <tr>
                                <td class="left">{$lang.labelLaunchdate}:</td>
                                <td>{$req.detail.launch_date}</td>
                              </tr>
                              {if $smarty.session.attribute eq '5'}
                              <tr>
                                <td class="left">{$lang.labelSubExpires}:</td>
                                <td>{if $req.detail.attribute eq '5'}{$req.detail.renewalDate}{else}{$req.detail.productRenewalDate}{/if}</td>
                              </tr>
                              {/if}
                        {/if}
                            </table>
                          {if $smarty.session.attribute neq '0'}
                          	{if $smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3}
                          		<div><a href="/job/?act=product&step=4">Upgrade your resume</a></div>
                          	{else}
                              {if $req.detail.expireWarning eq 'yes' && 0}
                              <div class="note">NOTE: Your subscription is about to expire.</div>
                              {/if}
                                <div><a href="{if $req.detail.attribute eq '5'}{$soc_https_host}soc.php?cp=payreports{else}{$soc_https_host}{$req.detail.dir_name}/?act=product&cp=pay&feetype=year&step=4{/if}">{if $smarty.session.attribute eq '2' || $smarty.session.attribute eq '1'|| ($smarty.session.attribute eq '3'&&$smarty.session.subAttrib neq 3)}Renew subscription{else}Click here to renew{/if}</a></div>
                            {/if}
                          {/if}
							<br/>
							
							
							<div><a href="/soc.php?cp=protection">Seller Protection</a>
							
								<!--<a style="margin-left: 29px;" href="/foodwine/?act=fanpromotion"><img src="/images/help_us_icon.png" style="vertical-align:text-top;" /></a>-->
							</div>
							
							
							{if not $smarty.session.fb.can}
								<div class="fb-login-button" id="fb-login-button" data-scope="email,user_checkins" style="margin-top:5px;">Connect to your facebook account</div>
							{else}
								<a href="/soc.php?cp=fbunbundle" target="_self" style="margin-top:5px; display:block;">Untie the bundled facebook account</a>
							{/if}
							
							{if $smarty.session.attribute eq '2' || $smarty.session.attribute eq '1' || ($smarty.session.attribute eq '3'&&$smarty.session.subAttrib neq 3)}
								<span style="float:left;margin-top:5px;line-height:29px;font-weight:bold;">Get HTML code to refer users:</span><input type="text" class="inputB" style="margin-left:10px;margin-top:5px;width:120px;" value="{$req.widgetHTML|escape:html}" onclick="this.select();"/><div class="clear"></div>
							{/if}
                      </div>
                    </div>
                </div>
                <!-- End Your Details -->
				
                <!-- Start Your Website -->
          <div class="sec-website">
                	<div class="bottom">
                    	<div class="top" {if $smarty.session.attribute eq 1 || $smarty.session.attribute eq 2 || ($smarty.session.attribute eq 3 &&$smarty.session.subAttrib neq 3)}style="height:223px;"{/if}>
							<div id="preview" class="preview" style="z-index:1000;"><a href="/registration.php?step=preview" style="color:#777;">Preview</a></div>
							<h2>Your Website</h2>
							<h3 style="color:#777;font-weight:bold;">Update your website in 4 easy steps:</h3>
							<ul class="clearfix">
								<li><div class="float-l step">Step 1</div><a href="/registration.php?step=account">Account Information</a></li>
								<li><div class="float-l step">Step 2</div><a href="/registration.php?step=details">Website Details</a></li>
								<li><div class="float-l step">Step 3</div><a href="/registration.php?step=theme">Template / Colour / Images</a></li>
								<li><div class="float-l step">Step 4</div><a href="/registration.php?step=products">Add Products</a></li>
								<li><div class="float-l step">Step 5</div><a href="/registration.php?step=preview">Preview</a></li>
								{if $account_status eq 0}
									<li><div class="float-l step" style="width: 53px;">Step 6</div><a href="/registration.php?step=activate">Pay & Go Live</a></li>
								{/if}
							</ul>
							<div style="padding: 10px; border: 1px solid #FF0000; border-radius: 10px;">
								<div class="float-l f_bold" style="margin-bottom:20px; font-size:1.2em; padding-top:4px;">Tip:</div>
								<p><a href="/soc.php?cp=bookads">Advertise your website for <span class="f_red">FREE</span> on<br/>
								<span class="f_bold f_red">Your {$lang.labelCouncil} Area Homepage</span></a></p>
							</div>
                        </div>
                    </div>
              </div>
                <!-- End Your Website -->
                <div class="clear"></div>
                <div class="notices" style="z-index:1000; min-height:85px;">
                <div style="float:left; width:340px">
                	{if $smarty.session.attribute eq 5}
                		<h2 style="width:577px; float:left">Admin Notices</h2>
                		<a class="view-passorder" style="z-index:1000; position:relative;" href="/soc.php?cp=foodwinetips"><img src="/skin/red/images/buttons/bu-tips.jpg" /></a>
                    {else}
                    	<p><h2>Admin Notices</h2></p>
                    {/if}
                    <p>
                        {$adminNotices.aboutPage}
                    </p>
                    <!--<p>Any notices from SOC Head Office will display here.</p>
                    <p><img src="/skin/red/images/home/icon-advertise.gif" alt="" align="left" />New Visitor Tracking System - you can now track exactly where your site visitors are coming from and what their interests are. Go to <a href="#">"site visitors"</a> to start tracking now.</p>
                    <p><img src="/skin/red/images/home/icon-advertise.gif" alt="" align="left" />A "refer a friend" system is to be implemented in September 2010.</p>-->
                </div>
                {if !($smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3)}
                <!--<div class="soc-race-scoreboard">
                <a href="/soc.php?cp=listpoints" class="scoreboard-{if $smarty.session.attribute eq '0'}buysell{else}foodwine{/if}"></a></div>-->
                {/if}
              </div>
                <div class="clear"></div>
          </div>
<div class="clear"></div>
<div class="clearBoth"></div>

{*<div class="block-admin">
    <h3>Welcome to your Admin</h3>
    <div class="content-user-block">
        <div class="img-user">
            <img src="{if $image_uploaded}/profile_images/{$user_id}.jpg{else}/images/no_profile_pic.jpg{/if}" alt="" width="146px" height="147px">
        </div>
        <div class="info-user">
            <p><span class="text-1">{$lang.labelName}</span><span class="text-2">{$smarty.session.UserName}</span></p>
            <p><span class="text-1">{$lang.labelNickName}</span><span class="text-2">{$smarty.session.NickName}</span></p>
            <p><span class="text-1">{$lang.labelEmail}</span><span class="text-2">{$smarty.session.email|truncate:25}</span></p>
            <p><span class="text-1">{$lang.labelCountry}</span><span class="text-2">{$smarty.session.CountryName}</span></p>
            <p><span class="text-1">{$lang.labelState}</span><span class="text-2">{$smarty.session.State}</span></p>
            <p><span class="text-1">{$lang.labelCity}</span><span class="text-2">{$smarty.session.Suburb}</span></p>
            <a href="{$soc_https_host}soc.php?cp=edit_customers_geton" target="_self" class="blueButt">Update my details / change my password</a>
        </div>
        <div class="block-message">
            <div class="block-message-line1">
                <img src="images/icon-mess-1.png" alt="" width="22px" height="22px">
                <a href="./soc.php?cp=customers_geton_alerts">I’m a fan of <span class="st-text-1">{$req.emailAlert}</span> stores</p>
            </div>
            <div class="block-message-line1">
                <img src="images/icon-mess-2.png" alt="" width="13px" height="17px">
                <a href="./soc.php?cp=purchase">Past Receipts</a>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>*}



<div class="block-fan-frenzy">
    <div class="content-fan-frenzy">
        <div class="fan-frenzy-logo"></div>
        <div class="fan-frenzy-graphic">
            <img src="images/fan-frenzy-graphic.png" alt="">
        </div>
        <p class="text-content-top" style="padding-left: 34px;">
        {$fanfrenzy_text}
        </p>
        <div class="button-top" style="margin-left: 34px;">
            <div class="btn-enter"><a href="/entry">Enter Competition</a></div>
            <div class="btn-view"><a href="/fanfrenzy">View All Entries</a></div>
        </div>
        <div class="clear"></div>
        {if $display}
            <div class="entries-for-my" style="margin-left: 0">
                <ul id="tab-nav">
                    <li class="active" id="block-gallery-bt">Entries for my store</li>
                    <li>|</li>
                    <li id="my-gallery-bt">My Entries</li>
                    <input type="hidden" id="promo_page_type" value="0" />
                </ul>
                <div class="clear"></div>
            </div>        
            <div class="tab-gallery block-gallery active">
                {php}view_photos_seller(false);{/php}
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="tab-gallery my-gallery">
                {php}view_photos_seller(true);{/php}     
                <div class="clear"></div>
            </div>
        {/if}
        <div class="clear"></div>
        <!-- <div class="button-bottom">
            <div class="btn-enter"><a href="/entry">Enter Competition</a></div>
            <div class="btn-view"><a href="/fanfrenzy">View All Entries</a></div>
        </div> -->
    </div>
</div>