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
		<li><span>{$lang.tt.sector}: </span><em><h2 style="font-size:12px;font-weight:bold;color:#777777; margin:0px; display:inline;">{$req.items.product[0].sectorName|wordwrap:18:'<br />':true}</h2>&nbsp;</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.subSector}: </span><em><h2 style="font-size:12px;font-weight:bold;color:#777777; margin:0px; display:inline;">{$req.items.product[0].subSectorName|wordwrap:18:'<br />':true}</h2>&nbsp;</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.refNo}: </span><em>{$req.items.product[0].refNo}&nbsp;</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.salary}: </span><em>
		{if $req.items.product[0].salaryMin eq $req.items.product[0].salaryMax }{if $req.items.product[0].salaryMin neq -2}${/if}{valueOfArray arrValue=$lang.val.min_salary value=$req.items.product[0].salaryMin}{elseif $req.items.product[0].salaryMin eq -2 }${valueOfArray arrValue=$lang.val.max_salary value=$req.items.product[0].salaryMax}{elseif $req.items.product[0].salaryMax eq -2 }${valueOfArray arrValue=$lang.val.min_salary value=$req.items.product[0].salaryMin}{else}${valueOfArray arrValue=$lang.val.min_salary value=$req.items.product[0].salaryMin} - ${valueOfArray arrValue=$lang.val.max_salary value=$req.items.product[0].salaryMax}{/if}
		{if $req.items.product[0].negotiable}<br /><samp style=" font-size:11px;">{$lang.tt.negotiable}</samp>{/if}
		&nbsp;</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.type}: </span><em>{valueOfArray arrValue=$lang.val.type value=$req.items.product[0].type}</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.location}:<br/>
				  {if $headerInfo.attribute eq 3 and $headerInfo.subAttrib eq 3}
				  {else}
				  	{if $req.items.product[0].suburbName neq ""}
				  	<a href="/soc.php?cp=map&StoreID={$req.info.StoreID}&key={$req.items.product[0].location},{$req.items.product[0].suburbName},{$req.items.product[0].stateName}'"><img src="/skin/red/images/icon_location.gif" border="0"/></a>
					<br />&nbsp;&nbsp;Map  {/if}
					{/if}</span>
					<em>{if $req.items.product[0].suburbName neq ""}{$req.items.product[0].suburbName}, {/if}{$req.items.product[0].stateName}<br/>{ $req.items.product[0].location|nl2br|wordwrap:16:'<br />':true}&nbsp;</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.datePosted}: </span><em>{if $req.items.product[0].datePosted neq ""}{$req.items.product[0].datePosted|date_format:"`$PBDateFormat`":"-"}{else}-{/if}&nbsp;</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.closingDate}: </span><em>{if $req.items.product[0].closingDate neq ""}{$req.items.product[0].closingDate|date_format:"`$PBDateFormat`":"-"}{else}-{/if}&nbsp;</em>
		<div class="clearBoth"></div></li>
		
		<li><span>{$lang.tt.advertiser}: </span><em>{$req.items.product[0].advertiser|wordwrap:18:'<br/>':true}</em>
		<div class="clearBoth"></div></li>
		{if $sellerhome neq "1"}
		<li>
		<strong style="white-space:nowrap;">{if $sellerhome eq "1"}HTML to embed your website{else}HTML to embed this item{/if}:</strong></li>
		<li>
		<input class="inputB" onclick="this.select();" id="widgetHTML" style="width:90%;" value="{$req.widgetHTML|escape:html}"/></li>
		{/if}
		
		<div id="line"><img id="space" src="/images/spacer.gif" width="1px" height="1px" style="clear:both"/></div>
		
		
        <h3>{$headerInfo.subAttribName|default:'Advertiser'|upper} DETAILS</h3>
		
		<!--li>
		<span>{$lang.tt.cpmpanyAgent}: </span>
		<em><a href="/soc.php?cp=shopdes&StoreID={$req.info.StoreID}">{$req.info.bu_name}</a></em>
		<div class="clearBoth"></div>
		</li-->
		<li>
		<em>{if $headerInfo.subAttrib neq 3}<a href="/soc.php?cp=shopdes&StoreID={$req.info.StoreID}">{/if}{$req.info.bu_name|wordwrap:30:'-<br />':true}{if $headerInfo.subAttrib neq 3}</a>{/if}</em>
		<div class="clearBoth"></div>
		</li>
		{if $req.info.bu_suburb || $req.info.bu_state || ($req.info.address_hide == 0 && $req.info.bu_address)}
        <li>
		<span>{$lang.tt.location}: </span>
		<em>{$req.info.bu_suburb}, {$req.info.bu_state}<br />{if $req.info.address_hide == 0 }{ $req.info.bu_address|nl2br|wordwrap:16:'<br />':true}{/if}</em>
		<div class="clearBoth"></div>
		</li>
		{/if}
        {if $req.info.phone_hide == 0 }
        <li><span>Phone:</span>
		<em>{$req.info.bu_phone }</em>
		<div class="clearBoth"></div>	
        </li>
        {/if}	
		<li>
		<span>{$lang.tt.contactPerson}: </span>
		<em>{$req.info.bu_nickname}</em>
		<div class="clearBoth"></div>
		</li>
		
		{if $req.info.subAttrib neq 3 && $req.items.product[0].category eq 2 && $req.info.images.mainImage.3.bname.text neq '/images/72x100.jpg'}
			<li>
			<span>&nbsp;</span>
			<em><img src="{$req.info.images.mainImage.3.bname.text}" width="{$req.info.images.mainImage.3.bname.width}" height="{$req.info.images.mainImage.3.bname.height}"/></em>
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
		<li style="text-align:center">
		<a href="{if $req.is_customer}javascript:popcontactwin(3,{$req.items.product[0].pid});{else}javascript:tipRedirect();{/if}" class="i-contact">
		{if $req.items.product[0].category eq '1'}
		<img src="/skin/red/images/buttons/or-apply-now.gif" width="118" height="29" align="absmiddle" />
		{else}
		<img src="/skin/red/images/buttons/or-contact-me.gif" width="118" height="29" align="absmiddle" />
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
	<a href="https://www.paypal.com/my/cgi-bin/webscr?cmd=xpt/Marketing/securitycenter/buy/Protection-outside" target="_blank"><img style="float:left; margin:0px 0px 0px 2px;" border="0" src="/skin/default/images/{if ($is_samplestie eq 1) && $req.info.StoreID eq '879521'}paypal-buyer-protection-sample.gif{else}paypal-buyer-protection.gif{/if}"/></a>
	</div>
