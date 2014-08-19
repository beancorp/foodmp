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
		{if $req.items.product[0].category < 4 }
		<li><span>{$lang.tt.price}: </span><em>{if $req.items.product[0].price|number_format eq '0.00'} - {else} ${$req.items.product[0].price|number_format} {/if}<samp style=" font-size:11px;">{if $req.items.product[0].priceMethod > 0}<br />{valueOfArray arrValue=$lang.val.priceMethod value=$req.items.product[0].priceMethod}{/if}{if $req.items.product[0].negotiable}{if $req.items.product[0].priceMethod > 0} |{/if} {$lang.tt.negotiable}{/if}</samp>&nbsp;</em>
		<div class="clearBoth"></div></li>
		{/if}
		
		<li><span>{$lang.tt.property}: </span><em>{valueOfArray arrValue=$lang.val.property value=$req.items.product[0].property}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.bedroom}: </span><em>{$req.items.product[0].bedroom|default:'-':true:6}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.bathroom}: </span><em>{$req.items.product[0].bathroom|default:'-':true:6}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.carspaces}: </span><em>{$req.items.product[0].carspaces|default:'-':true:6}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.category}: </span><em>{valueOfArray arrValue=$lang.val.category value=$req.items.product[0].category}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.inspect}: </span><em>{$req.items.product[0].inspect}&nbsp;</em>
		<div class="clearBoth"></div></li>
		
		{if $req.items.product[0].category eq '4'}
		<li><span>{$lang.tt.auction}: </span><em>{$req.items.product[0].auction}</em>
		<div class="clearBoth"></div></li>
		{/if}
		
		{if ($req.items.product[0].council neq '' and $req.items.product[0].council neq '0') or ($req.items.product[0].water neq '0' and $req.items.product[0].water neq '') or ($req.items.product[0].strata neq '0' and $req.items.product[0].strata neq '')}
		<li>
		<span>{$lang.tt.Outgoings}: </span>
		{if $req.items.product[0].council neq '' and $req.items.product[0].council neq '0'}
		<em>{$lang.tt.council} {$req.items.product[0].council|default:'-'}</em>
		<div class="clearBoth"></div>
		{/if}
		{if $req.items.product[0].water neq '' and $req.items.product[0].water neq '0'}
		{if $req.items.product[0].council neq '' and $req.items.product[0].council neq '0'}<span>&nbsp;</span>{/if}<em>{$lang.tt.water} {$req.items.product[0].water|default:'-'}</em>
		<div class="clearBoth"></div>
		{/if}
		{if $req.items.product[0].strata neq '' and $req.items.product[0].strata neq '0'}
		{if ($req.items.product[0].council neq '' and $req.items.product[0].council neq '0') or ($req.items.product[0].water neq '0' and $req.items.product[0].water neq '')}<span>&nbsp;</span>{/if}<em>{$lang.tt.strata} {$req.items.product[0].strata|default:'-'}</em>
		<div class="clearBoth"></div>
		{/if}
		</li>
		{/if}
		
		<li>
		<span>{$lang.tt.location}: <br />
		<a href="/soc.php?cp=map&StoreID={$req.info.StoreID}&key={$req.items.product[0].location},{$req.items.product[0].suburbName},{$req.items.product[0].stateName}'"><img src="/skin/red/images/icon_location.gif" border="0"/></a><br />&nbsp;&nbsp;Map</span><em><h1 style="font-size:12px;font-weight:bold;color:#777777; margin:0px; display:inline;">{$req.items.product[0].suburbName}, {$req.items.product[0].stateName}<br /> 
		{ $req.items.product[0].location|nl2br|wordwrap:16:'<br />':true}</h1>&nbsp;</em>
		<div class="clearBoth"></div>
		</li>
		{if $sellerhome neq "1"}
		<li>
		<strong style="white-space:nowrap;">{if $sellerhome eq "1"}HTML to embed your website{else}HTML to embed this item{/if}:</strong></li>
		<li>
		<input class="inputB" onclick="this.select();" id="widgetHTML" style="width:90%;" value="{$req.widgetHTML|escape:html}"/></li>
		{/if}
		<div id="line"><img id="space" src="/images/spacer.gif" width="1px" height="1px" style="clear:both"/></div>
		
        <h3>{$lang.seller.attribute.1.subattrib[$req.info.subAttrib]|upper} DETAILS</h3>
		
		<li><em style="width:220px;"><a href="/soc.php?cp=shopdes&StoreID={$req.info.StoreID}">{$req.info.bu_name|wordwrap:30:'-<br />':true}</a>&nbsp;</em><div class="clearBoth"></div></li>
		
        <li style="height:28px;"><fb:like href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}" send="false" width="210" show_faces="false" font="arial"></fb:like></li>
        
        <div class="clear"></div>
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
		
		{if $req.info.images.mainImage.2.bname.text neq '/images/72x100.jpg'}
			<li>
			<span>&nbsp;</span>
			<em><img src="{$req.info.images.mainImage.2.bname.text}" width="{$req.info.images.mainImage.2.bname.width}" height="{$req.info.images.mainImage.2.bname.height}"/></em>
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
		
		{if $req.info.attribute eq '1' and $req.info.subAttrib eq '2'}
			{if $req.items.product[0].coname neq '' || $req.items.product[0].coaddress neq '' || $req.items.product[0].cophone neq ''}
				<li><br />
					<div class="clearBoth"></div>
					<strong>{$lang.labelCoagent|upper} DETAILS</strong>
					<div class="clearBoth"></div>
				</li>
				{if $req.items.product[0].coname neq ''}
				<li>
					<span>{$lang.labelName}: </span>
					<em>{$req.items.product[0].coname}</em>
					<div class="clearBoth"></div>
				</li>
				{/if}
				{if $req.items.product[0].coaddress neq ''}
				<li>
					<span>{$lang.labelAddress}: </span>
					<em>{$req.items.product[0].coaddress|nl2br}</em>
					<div class="clearBoth"></div>
				</li>
				{/if}
				{if $req.items.product[0].cophone neq ''}
				<li>
					<span>{$lang.labelPhone}: </span>
					<em>{$req.items.product[0].cophone}</em>
					<div class="clearBoth"></div>
				</li>
				{/if}
       		{/if}
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
		<li style="text-align:center">
		<a href="{if $req.is_customer}javascript:popcontactwin(1,{$req.items.product[0].pid});{else}javascript:tipRedirect();{/if}" class="i-contact">
		{if $req.info.subAttrib eq 1}
		<img src="/skin/red/images/buttons/or-contact-seller.gif" width="118" height="29" align="absmiddle" />
		{else}
		<img src="/skin/red/images/buttons/or-contact-agent.gif" width="118" height="29" align="absmiddle" />
		{/if}		</a>		</li>
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
	<a href="https://www.paypal.com/my/cgi-bin/webscr?cmd=xpt/Marketing/securitycenter/buy/Protection-outside" target="_blank"><img style="float:left; margin:0px 0px 0px 2px;" border="0" src="/skin/default/images/paypal-buyer-protection.gif"/></a>{if $is_samplestie eq 1}{if $req.items.product.0.pid neq '19'}<img style="float:left; margin:14px 0 0 5px;" border="0" src="/skin/red/images/buttons/samplesite.gif"/>{else}{if $sellerhome eq "1"}<img style="float:left; margin:14px 0 0 5px;" border="0" src="/skin/red/images/buttons/samplesite.gif"/>{/if}{/if}{/if}
	</div>
