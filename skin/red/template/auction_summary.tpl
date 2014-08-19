<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<div id="seller">
{if $sellerhome eq "1" && $req.info.youtubevideo neq ""}
    <div style="width:243px; height:160px; margin-bottom:10px;">
	<object width="243" height="160">
    	<param name="movie" value="{$req.info.youtubevideo}"></param>
        <param name="wmode" value="transparent" />
        <param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
        <embed src="{$req.info.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="243" height="160" wmode="transparent"></embed>
     </object>
	</div>
    {/if}
  <div id="seller-info1" style="width: 210px;">
        <h2 style="font-size:12px; font-weight:bold; color:#777777; margin:4px 0;">{$req.info.bu_name|wordwrap:30:'-<br />':true}</h2>
    <ul class="seller-details">
    	<li style="height:28px;"><fb:like href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}" send="false" width="210" show_faces="false" font="arial"></fb:like></li>
        <div class="clear"></div>
        <li>
		<span>Address:  {if $req.info.address_hide == 0 }<br/>
<a href="{if $headerInfo.address_hide==1}javascript:alert('Address not listed');{else}/soc.php?cp=map&StoreID={$headerInfo.StoreID}&key={$headerInfo.bu_address},{$headerInfo.bu_suburb},{$headerInfo.bu_state}{/if}">
<img border="0" src="/skin/red/images/icon_location.gif"/>
</a>
<br/>
  &nbsp;Map{/if}</span>
		<em>{$req.info.bu_suburb}, {$req.info.bu_state}<br />{if $req.info.address_hide == 0 }{ $req.info.bu_address}{/if}</em>
		<div class="clearBoth"></div>
		<div class="clearBoth"></div>
		</li>
		{if $req.info.phone_hide == 0 }
        <li><span>Phone:</span>
		<em>{$req.info.bu_phone }</em>
		<div class="clearBoth"></div>
		</li>
		{/if}
		{if $req.info.college_hide == 0 }
		{if $req.info.bu_college ne ''}
    	 <li>
			<span>{$lang.labelCollegeFront}:</span>
			<em>{ $req.info.bu_college}</em>
			<div class="clearBoth"></div>
		</li>
		{/if}
		{/if}
		
      <li><span>Seller's Rating:</span>
	  {if $req.aveRating eq 0}<span style="color:#F3B216; vertical-align:middle;">No Ratings</span>{else}<img src="/skin/red/images/star_{$req.aveRating}.png" />{/if}
	  <div class="clearBoth"></div>
	  </li>
        <li><span>Member Since:</span>
		<em><strong>{$req.info.launch_date}</strong></em>
		<div class="clearBoth"></div>
		</li>
        <li><span>Preferred Contact:</span>
		<em><strong>
		{if $req.info.contact eq 'Email'}
			<a href="{if $req.is_customer}javascript:popcontactwin();{else}javascript:tipRedirect();{/if}">{$req.info.contact}</a>
		{else}
			{$req.info.contact}
		{/if}</strong></em>
		<div class="clear"></div>
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
	   <li>	<em><a href="{$req.info.linkedin}" target="_blank"><img style="float:left;" src="/skin/red/images/linkedin.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:none; white-space:nowrap; cursor:pointer;">Linked In</span></a></em>
		<div class="clearBoth"></div>
	  </li>
	  {/if}
        <li><span>Reviews:</span>
		<em><a href="soc.php?cp=disreview&StoreID={$req.info.StoreID}"><strong>{$req.info.reviews}</strong></a></em>
		<div class="clear"></div>
		</li>
		<li>
		<strong style="white-space:nowrap;">{if $sellerhome eq "1"}HTML to embed your website{else}HTML to embed this item{/if}:</strong></li>
		<li>
		<input class="inputB" onclick="this.select();" id="widgetHTML" style="width:95%;" value="{$req.widgetHTML|escape:html}"/></li>
    </ul><br />
  </div>
  {if  $sellerhome eq "1"}
    <div id="seller-info2" style="position:relative;width: 210px;">
    
    <div style="clear:both;position:absolute;top:10px;right:10px;">
	<img width="67" src="/skin/default/images/quickssl_anim.gif" />
	</div>
        <p>Payments Accepted:<br />
      <span class="payment" style="display:block;width:135px;">
      {php}$i=0;{/php}
	  {foreach from=$req.info.payments item=lps key=key}
	  	{if $key eq 2}{php}continue;{/php}{/if}
	  	{php}if($i>0){echo ',';}{/php}
	  	{if $lang.Payments[$key].image ne '' }
	  		{php}$i++;{/php}
			<img src="/{$lang.Payments[$key].image}" align="absmiddle" />
		{else}
			{if $lang.Payments[$key].text neq ""}
				{php}$i++;{/php}
				{$lang.Payments[$key].text}
			{/if}
		{/if} 
	  {/foreach}
	  </span></p>
      {if $sellerhome eq "1"}
      <p>Shipping for this seller:<br /><span class="payment">
       	{foreach from=$req.info.bu_delivery item=lps key=key}
        {if $req.info.bu_delivery_text[$key] neq ""}{$req.info.bu_delivery_text[$key]}{if $lps[1]},{/if}{/if}
        {if $key eq 3}<br />{/if}
        {/foreach}
      </span></p>
      {else}
      <p>
      	Domestic Shipping for this item<br/>
        <span class="payment">
        {foreach from=$l.deliveryMethod|explode:"|" item=opcl key=oplk}
    		{if $req.info.bu_delivery_text[$opcl] neq ""}{$req.info.bu_delivery_text[$opcl]} (Fee:${if $l.postage}{foreach from=$l.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})<br/>{/if}
    	{/foreach}
    	</span>
      	{if $l.isoversea}
        <br/>Overseas Shipping for this item<br/>
    	<span class="payment">
    	{foreach from=$l.oversea_deliveryMethod|explode:"|" item=opcl key=oplk}
    		{if $req.info.bu_delivery_text[$opcl] neq ""}{$req.info.bu_delivery_text[$opcl]} (Fee:${if $l.oversea_postage}{foreach from=$l.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})<br/>{/if}
    	{/foreach}</span>
        {/if}
        </p>
      {/if}
  </div>
  {else}
  <div id="seller-info4" style="clear:both;width:240px">
	<img  style=" float:left;" src="/skin/default/images/quickssl_anim.gif" />
	<a href="https://www.paypal.com/my/cgi-bin/webscr?cmd=xpt/Marketing/securitycenter/buy/Protection-outside" target="_blank"><img style="float:left; margin:0px 0px 0px 2px;" border="0" src="/skin/default/images/paypal-buyer-protection.gif"/></a>{if $is_samplestie eq 1}<img style="float:left; margin:14px 0 0 5px;" border="0" src="/skin/red/images/buttons/samplesite.gif"/>{/if}
	</div>
  {/if}
	
</div>

<div id="products" style="width:500px;">{$product_content}</div>