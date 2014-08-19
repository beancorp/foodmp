	<div id="seller">
	{if $req.template.TemplateName eq 'tmp-n-b' && $req.template.MainImg.actual_width >0}
	<div id="store_logo" align="left"><img src="{$smarty.const.SOC_HTTP_HOST}{$req.template.MainImg.name}" {$req.template.MainImg.width_attribute} {$req.template.MainImg.height_attribute} style="border:solid 0px #ddd; margin:0px 0px 10px 0px; " /></div>{/if}
		{if $sellerhome eq "1" && $req.info.youtubevideo neq ""}
		<div id="youtube_video" style="width:243px; height:160px; margin-bottom:10px;">
		<object width="243" height="160">
			<param name="movie" value="{$req.info.youtubevideo}"></param>
			<param name="wmode" value="transparent" />
			<param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
			<embed src="{$req.info.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="243" height="160" wmode="transparent"></embed>
		 </object>
		</div>
		{/if}
	  <div id="seller-info1">
			<h2 style="font-size:12px; font-weight:bold; color:#777777; margin:4px 0;">{$req.info.bu_name|wordwrap:30:'-<br />':true}</h2>
		<ul class="seller-details">    
			<li style="height:28px;"><fb:like href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}" send="false" width="210" show_faces="false" font="arial"></fb:like></li>
			<div class="clear"></div>
			<li>
			<span>Address:  {if $req.info.address_hide == 0 }<br/>
	<a href="{if $headerInfo.address_hide==1}javascript:alert('Address not listed');{else}{$smarty.const.SOC_HTTP_HOST}soc.php?cp=map&StoreID={$headerInfo.StoreID}&key={$headerInfo.bu_address},{$headerInfo.bu_suburb},{$headerInfo.bu_state}{/if}">
	<img border="0" src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/icon_location.gif"/>
	</a>
	<br/>
	  &nbsp;Map{/if}</span>
			<em>{$req.info.bu_suburb}, {$req.info.bu_state}<br />{if $req.info.address_hide == 0 }{ $req.info.bu_address}{/if}</em>
			<div class="clearBoth"></div>
			</li>
			<li>{if $req.info.phone_hide == 0 }<span>Phone:</span>
			<em>{$req.info.bu_phone }</em>
			<div class="clearBoth"></div>{else}<span>&nbsp;</span><em> </em><div class="clearBoth"></div>{/if}
			</li>
			
			{if $free_signup}
				{if $bu_fax}
					{if $fax_hide == 0 }
						<li>
							<span>Fax:</span>
							<em>{$bu_fax}</em>
							<div class="clearBoth"></div>
						</li>
					{/if}
				{/if}
				{if $mobile}
					{if $mobile_hide == 0 }
						<li>
							<span>Mobile:</span>
							<em>{$mobile}</em>
							<div class="clearBoth"></div>
						</li>
					{/if}
				{/if}
			{/if}
			
			<li>
				{if $req.info.college_hide == 0 }
					{if $req.info.bu_college ne ''}
					<span>{$lang.labelCollegeFront}:</span>
					<em>{ $req.info.bu_college}</em>
					<div class="clearBoth"></div>
					{/if}
				{else}
					<span>&nbsp;</span><em> </em><div class="clearBoth"></div>
				{/if}
			</li>
			
		  <li><span>Seller's Rating:</span>
		  {if $req.aveRating eq 0}<div style="color:#F3B216; vertical-align:middle; float: left;">No Ratings</div>{else}<div style="float: left;"><img src="/skin/red/images/star_{$req.aveRating}.png" /></div>{/if}{$verified_image}
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
			<div class="clearBoth"></div>
		  </li>
		  {if $req.info.facebook neq ""}
		  <li><em><a href="{$req.info.facebook}" target="_blank"><img style="float:left;" src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/facebook.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:none; cursor:pointer;">Facebook</span></a></a></em>
			<div class="clearBoth"></div>
		  </li>
		  {/if}
		  {if $req.info.twitter neq ""}
			<li><em><a href="{$req.info.twitter}" target="_blank"><img style="float:left;" src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/twitter.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:none; cursor:pointer;">Twitter</span></a></a></em>
			<div class="clearBoth"></div>
			</li>
		  {/if}
		  {if $req.info.myspace neq ""}
		  <li><em><a href="{$req.info.myspace}" target="_blank"><img style="float:left;" src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/myspace.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:none; cursor:pointer;">MySpace</span></a></a></em>
			<div class="clearBoth"></div>
		  </li>
		  {/if}
		  {if $req.info.linkedin neq ""}
		   <li>	<em><a href="{$req.info.linkedin}" target="_blank"><img style="float:left;" src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/linkedin.gif"/><span style="float:left; line-height:18px; padding-left:3px; height:18px; text-decoration:none; cursor:pointer;">Linked In</span></a></em>
			<div class="clearBoth"></div>
		  </li>
		  {/if}
			<li><span>Reviews:</span>
			<em><a href="soc.php?cp=disreview&StoreID={$req.info.StoreID}"><strong>{$req.info.reviews}</strong></a></em>
			<div class="clearBoth"></div>
			</li>
			<li>
			<strong style="white-space:nowrap;">{if $sellerhome eq "1"}HTML to embed your website{else}HTML to embed this item{/if}:</strong></li>
			<li>
			<input class="inputB" onclick="this.select();" id="widgetHTML" style="width:95%;" value="{$req.widgetHTML|escape:html}"/></li>
		</ul><br />
	  </div>
		<div id="seller-info2">
		{if $free_signup}
			<strong>Payments Accepted:</strong> <br />
			{foreach from=$payment_options item=option}
				<img src="{$option.image}" alt="{$option.name}" width="50px" />
			{/foreach}
		{else}
		  <p>Payments Accepted:<br />
		  <span class="payment">
		  {foreach from=$req.info.payments item=lps key=key}
			{if $key eq 2}{php}continue;{/php}{/if}
			{if $lang.Payments[$key].image ne '' }
				<img src="{$smarty.const.SOC_HTTP_HOST}{$lang.Payments[$key].image}" align="absmiddle" />
				{if $lps[1]},{/if}
			{else}
				{if $lang.Payments[$key].text neq ""}
					{$lang.Payments[$key].text}
					{if $lps[1]},{/if}
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
				{if $req.info.bu_delivery_text[$opcl] neq ""}{$req.info.bu_delivery_text[$opcl]} (Fee:${if $l.postage}{foreach from=$l.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})<br/>
				{/if}
			{/foreach}
			</span>
			{if $l.isoversea}
			<br/>Overseas Shipping for this item<br/>
			<span class="payment">
			{foreach from=$l.oversea_deliveryMethod|explode:"|" item=opcl key=oplk}
				{if $req.info.bu_delivery_text[$opcl] neq ""}{$req.info.bu_delivery_text[$opcl]} (Fee:${if $l.oversea_postage}{foreach from=$l.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})<br/>
				{/if}
			{/foreach}</span>
			{/if}</p>
		  {/if}
		{/if}
	  </div>
		<div id="seller-info4" style="clear:both;width:240px">
		<img  style=" float:left;" src="{$smarty.const.SOC_HTTP_HOST}skin/default/images/quickssl_anim.gif" />
		<a href="https://www.paypal.com/my/cgi-bin/webscr?cmd=xpt/Marketing/securitycenter/buy/Protection-outside" target="_blank"><img style="float:left; margin:0px 0px 0px 2px;" border="0" src="{$smarty.const.SOC_HTTP_HOST}skin/default/images/paypal-buyer-protection.gif"/></a>{if $is_samplestie eq 1}<img style="float:left; margin:14px 0 0 5px;" border="0" src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/buttons/samplesite.gif"/>{/if}
		</div>
		
	</div>
	{if $account_enabled}
		<div id="products">{$product_content}</div>
	{else}
		<div id="products" style="font-size: 12pt; font-weight: bold; padding-top: 10px;">The store has been deactivated by the user.</div>
	{/if}