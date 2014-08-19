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
<div id="seller-left-head">{$headerInfo.sellerTypeName|upper} DETAILS</div>
<div id="seller-infonew">
    <ul class="seller-details">
		<li><span>{$lang.tt.price}: </span><em>{if $req.items.product[0].price >0}${$req.items.product[0].price|number_format}{else} - {/if}{if $req.items.product[0].negotiable}<br /><samp style=" font-size:11px;">{$lang.tt.negotiable}</samp>{/if}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.type}: </span><em>{$req.items.product[0].carTypeName}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.make}: </span><em><h2 style="font-size:12px;font-weight:bold;color:#777777; margin:0px;">{$req.items.product[0].makeName|wordwrap:18:'<br />':true}</h2></em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.model}: </span><em><h2 style="font-size:12px;font-weight:bold;color:#777777; margin:0px;">{$req.items.product[0].modelName|wordwrap:18:'<br />':true}</h2></em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.year}: </span><em>{$req.items.product[0].year}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.kms}: </span><em>{$req.items.product[0].kms} kms</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.body}: </span><em>{if $req.items.product[0].door}{valueOfArray arrValue=$lang.val.door value=$req.items.product[0].door}{/if} {valueOfArray arrValue=$lang.val.seat value=$req.items.product[0].seat} {valueOfArray arrValue=$lang.val.pattern value=$req.items.product[0].pattern}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.color}: </span><em>{$req.items.product[0].color}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.transmission}: </span><em>{valueOfArray arrValue=$lang.val.transmission value=$req.items.product[0].transmission}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.regNo}: </span><em>{$req.items.product[0].regNo}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.regExpired}: </span><em>{$req.items.product[0].regExpired|date_format:"`$PBDateFormat`":"-"}</em>
		<div class="clearBoth"></div></li>
        
    	{if $req.items.product[0].suburbName && $req.items.product[0].stateName}
        <li>
		<span>{$lang.tt.location}: <br />
		<a href="/soc.php?cp=map&StoreID={$req.info.StoreID}&key={if $req.items.product[0].location}{$req.items.product[0].location},{/if}{$req.items.product[0].suburbName},{$req.items.product[0].stateName}"><img src="/skin/red/images/icon_location.gif" border="0"/></a><br />&nbsp;&nbsp;Map</span><em><h1 style="font-size:12px;font-weight:bold;color:#777777; margin:0px; display:inline;">{$req.items.product[0].suburbName}, {$req.items.product[0].stateName}<br /> 
		{ $req.items.product[0].location|nl2br|wordwrap:16:'<br />':true}</h1>&nbsp;</em>
		<div class="clearBoth"></div>
		</li>
        {/if}
		{if $sellerhome neq "1"}
		<li>
		<strong style="white-space:nowrap;">{if $sellerhome eq "1"}HTML to embed your website{else}HTML to embed this item{/if}:</strong></li>
		<li>
		<input class="inputB" onclick="this.select();" id="widgetHTML" style="width:90%;" value="{$req.widgetHTML|escape:html}"/></li>
		{/if}
		
		<div id="line"><img id="space" src="/images/spacer.gif" width="1px" height="1px" style="clear:both"/></div>
		
        <h3>{$lang.seller.attribute.2.subattrib[$req.info.subAttrib]|upper} DETAILS</h3>
		
		<!--li><span>{$lang.seller.attribute.2.subattrib[$req.info.subAttrib]}: </span> <em><a href="/soc.php?cp=shopdes&StoreID={$req.info.StoreID}">{$req.info.bu_name}</a></em></li-->
		<li><em style="width:220px;"><a href="/soc.php?cp=shopdes&StoreID={$req.info.StoreID}">{$req.info.bu_name|wordwrap:30:'-<br />':true}</a></em><div class="clearBoth"></div></li>
		
    	<li style="height:28px;"><fb:like href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}" send="false" width="210" show_faces="false" font="arial"></fb:like></li>
        <div class="clear"></div>
		{if $req.info.subAttrib eq 2}
		<li><span>{$lang.labelLicence}: </span> <em>{$req.info.licence}&nbsp;</em></li>
		{/if}
		
        <li>
		<span>{$lang.tt.location}: </span>
		<em>{$req.info.bu_suburb}, {$req.info.bu_state}<br />{if $req.info.address_hide == 0 }{ $req.info.bu_address|nl2br|wordwrap:16:'<br />':true}{/if}</em>
		<div class="clearBoth"></div>
		</li>
		
        <li>{if $req.info.phone_hide == 0 }<span>Phone:</span>
		<em>{$req.info.bu_phone }</em>
		<div class="clearBoth"></div>{else}<span>&nbsp;</span><em> </em><div class="clearBoth"></div>{/if}		</li>
        
		<li>
		<span>{$lang.tt.contactPerson}: </span>
		<em>{$req.info.bu_nickname}</em>
		<div class="clearBoth"></div>
		</li>
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
		<br />
		
		<li style="text-align:center">
		<a href="{if $req.is_customer}javascript:popcontactwin(2,{$req.items.product[0].pid});{else}javascript:tipRedirect();{/if}" class="i-contact">
		{if $req.info.subAttrib eq 1}
		<img src="/skin/red/images/buttons/or-contact-seller.gif" width="118" height="29" align="absmiddle" />
		{else}
		<img src="/skin/red/images/buttons/or-contact-dealer.gif" width="118" height="29" align="absmiddle" />
		{/if}
		</a>
		</li>
		{if $sellerhome eq "1"}
		<li>
		<strong style="white-space:nowrap;">{if $sellerhome eq "1"}HTML to embed your website{else}HTML to embed this item{/if}:</strong></li>
		<li>
		<input class="inputB" onclick="this.select();" id="widgetHTML" style="width:90%;" value="{$req.widgetHTML|escape:html}"/></li>
		{/if}
    </ul>
</div>
   <div id="seller-info4" style="clear:both;width:240px">
	<img style=" float:left;" border="0" src="/skin/default/images/quickssl_anim.gif" />
	<a href="https://www.paypal.com/my/cgi-bin/webscr?cmd=xpt/Marketing/securitycenter/buy/Protection-outside" target="_blank"><img style="float:left; margin:0px 0px 0px 2px;" border="0" src="/skin/default/images/paypal-buyer-protection.gif"/></a>{if $is_samplestie eq 1}<img style="float:left; margin:14px 0 0 5px;" border="0" src="/skin/red/images/buttons/samplesite.gif"/>{/if}
	</div>
