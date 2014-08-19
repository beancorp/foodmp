<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<script src="/skin/red/js/jquery-1.4.2.min.js"></script>
<script src="/skin/red/js/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery.marquee.js"></script>
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
        	<h1 class="soc-admin">Welcome to your Admin Panel</h1>
            <div id="mid-content">
				<!--
            	<div class="sec-summary">
                	<div class="bottom">
						<ul class="clearfix">
                            <li>
                            	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="summary">
                                  <tr>
                                    <td class="left">Site visitors:</td>
                                    <td><a href="/soc.php?cp=business_get_step_stat">{$req.detail.clickNumber}</a></td>
                                  </tr>
                                </table>
                            </li>
                        </ul>
                    </div>
                </div>
				-->
                <div class="sec-details">
                	<div class="bottom">
                    	<div class="top">
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
                             <tr>
                                <td class="left">{$lang.labelLaunchdate}:</td>
                                <td>{$req.detail.launch_date}</td>
                              </tr>
                              <tr>
                                <td class="left">{$lang.labelSubExpires}:</td>
                                <td>{$req.detail.renewalDate}</td>
                              </tr>
                            </table>
                              {if $req.detail.expireWarning eq 'yes' && 0}
                              <div class="note">NOTE: Your subscription is about to expire.</div>
                              {/if}
                              <div><a href="{$soc_https_host}soc.php?cp=payreports">Renew subscription</a></div>
							<br/>
                      </div>
                    </div>
                </div>
				
				<div class="sec-website">
				
                	<div class="bottom">
                    	<div class="top">
							<h2>Your Listing</h2>
							<h3 style="color:#777;font-weight:bold;">Update your listing in 2 easy steps:</h3>
							<ul class="clearfix">
								<li><div class="float-l"><img src="/skin/red/images/home/website_step_1.jpg" alt="Step 1" /></div><a href="{$soc_https_host}registration.php?step=account">Account Information</a></li>
								<li><div class="float-l"><img src="/skin/red/images/home/website_step_2.jpg" alt="Step 2" /></div><a href="{$soc_https_host}registration.php?step=theme">Logo</a></li>
								{if $show_activate}
								<li><div class="float-l"><img src="/skin/red/images/home/website_step_3.jpg" alt="Step 3" /></div><a href="{$soc_https_host}registration.php?step=activate">Activate</a></li>
								{/if}
							</ul>
                        </div>
                    </div>
				</div>
          </div>
<div class="clear"></div>
<div class="clearBoth"></div>
