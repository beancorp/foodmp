<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
{if $sellerhome eq "1" && $req.info.youtubevideo neq ""}
    <div style="width:243px; height:160px; margin-bottom:10px;">
	<object width="243" height="160">
    	<param name="movie" value="{$req.info.youtubevideo}"></param>
        <param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
        <embed src="{$req.info.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="243" height="160"></embed>
     </object>
	</div>
{/if}
<div id="seller-info1">
        <h3>{$lang.seller.attribute.1.subattrib[$req.info.subAttrib]|upper} DETAILS</h3>
    <ul class="seller-details">

		<li>
			<em style="width:220px;"><a href="/soc.php?cp=shopdes&StoreID={$req.info.StoreID}">{$req.info.bu_name|wordwrap:30:'-<br />':true}</a></em>
			<div class="clearBoth"></div>
		</li>
        
        <li style="height:28px;"><fb:like href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}" send="false" width="210" show_faces="false" font="arial"></fb:like></li>
        <div class="clear"></div>
		
        <li>
		<span>{$lang.tt.location}: </span>
		<em>{$req.info.bu_suburb}, {$req.info.bu_state}<br />{if $req.info.address_hide == 0 }{ $req.info.bu_address|nl2br}{/if}</em>
		<div class="clearBoth"></div>
		</li>
		
        <li>{if $req.info.phone_hide == 0 }<span>{$lang.labelPhone}:</span>
		<em>{$req.info.bu_phone }</em>
		<div class="clearBoth"></div>{else}<span>&nbsp;</span><em> </em><div class="clearBoth"></div>{/if}
		</li>
		
		<li>
		<span>{$lang.tt.contactPerson}: </span>
		<em>{$req.info.bu_nickname}</em>
		<div class="clearBoth"></div>
		</li>
		
			{if $req.info.images.mainImage.2.bname.text neq '/images/72x100.jpg'}
			<li>
			<span>&nbsp;</span>
			<em><img src="{$req.images.mainImage.2.bname.text}" width="{$req.images.mainImage.2.bname.width}" height="{$req.images.mainImage.2.bname.height}"/></em>
			<div class="clearBoth"></div>
			</li>
			{/if}
		
		{if $req.info.mobile neq ''}
		<li>
		<span>{$lang.labelMobile}: </span>
		<em>{$req.info.mobile}</em>
		<div class="clearBoth"></div>
		</li>
        {/if}
	  {if $req.info.facebook neq ""}
	  <li><em><a href="{$req.info.facebook}" target="_blank"><img style="float:left;" src="/skin/red/images/facebook.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:none; cursor:pointer;">Facebook</span></a></a></em>
		<div class="clearBoth"></div>
	  </li>
	  {/if}
	  {if $req.info.twitter neq ""}
	  	<li><em><a href="{$req.info.twitter}" target="_blank"><img style="float:left;" src="/skin/red/images/twitter.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:none; cursor:pointer;">Twitter</span></a></a></em>
		<div class="clearBoth"></div>
		</li>
	  {/if}
	  {if $req.info.myspace neq ""}
	  <li><em><a href="{$req.info.myspace}" target="_blank"><img style="float:left;" src="/skin/red/images/myspace.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:none; cursor:pointer;">MySpace</span></a></a></em>
		<div class="clearBoth"></div>
	  </li>
	  {/if}
	  {if $req.info.linkedin neq ""}
	   <li>	<em><a href="{$req.info.linkedin}" target="_blank"><img style="float:left;" src="/skin/red/images/linkedin.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:none; cursor:pointer;">Linked In</span></a></em>
		<div class="clearBoth"></div>
	  </li>
	  {/if}	
	  <li>
		<strong style="white-space:nowrap;">{if $sellerhome eq "1"}HTML to embed your website{else}HTML to embed this item{/if}:</strong></li>
		<li>
		<input class="inputB" onclick="this.select();" id="widgetHTML" style="width:90%;" value="{$req.widgetHTML|escape:html}"/></li>
    </ul><br />
</div>
   <div id="seller-info4" style="clear:both;width:240px">
	<img style=" float:left;" border="0" src="/skin/default/images/quickssl_anim.gif" />
	<a href="https://www.paypal.com/my/cgi-bin/webscr?cmd=xpt/Marketing/securitycenter/buy/Protection-outside" target="_blank"><img style="float:left; margin:0px 0px 0px 2px;" border="0" src="/skin/default/images/paypal-buyer-protection.gif"/></a>{if $is_samplestie eq 1}<img style="float:left; margin:14px 0 0 5px;" border="0" src="/skin/red/images/buttons/samplesite.gif"/>{/if}
	</div>
