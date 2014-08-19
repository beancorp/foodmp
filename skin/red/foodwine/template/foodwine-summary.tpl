{if $sellerhome eq "1" && $req.info.youtubevideo neq ""}
    <div id="youtube_video" style="width:243px; height:160px; margin-bottom:10px;">
	<object width="243" height="160">
    	<param name="movie" value="{$req.info.youtubevideo}"></param>
        <param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
        <embed src="{$req.info.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="243" height="160"></embed>
     </object>
	</div>
{elseif $req.items.product.0.youtubevideo neq "" && 0}
    <div id="youtube_video" style="width:243px; height:160px; margin-bottom:10px;">
	<object width="243" height="160">
    	<param name="movie" value="{$req.items.product.0.youtubevideo}"></param>
        <param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
        <embed src="{$req.info.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="243" height="160"></embed>
     </object>
	</div>
{/if}
<style>
{literal}
ul.foodwine-seller-details li span {
    float: left;
}
.box_align_right {
	float: right;
	width: 145px;
}
{/literal}
</style>
<div id="seller-info1">
    <h2 style="font-size:12px; font-weight:bold; color:#777777; margin:4px 0;">{$req.info.bu_name|wordwrap:30:'-<br />':true}</h2>
    <ul class="foodwine-seller-details foodwine">
    
    	<li style="height:28px;"><fb:like href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}" send="false" width="210" show_faces="false" font="arial"></fb:like></li>
        <div class="clear"></div>
    {if $req.info.bu_suburb || $req.info.bu_state || ($req.info.address_hide == 0 && $req.info.bu_address)}
        <li>
		<span>Address:  {if $req.info.address_hide == 0 }<br/>


<br/>
  {/if}</span>
		<div class="box_align_right">
			<em>
				{$req.info.bu_suburb}, {$req.info.bu_state}<br />{if $req.info.address_hide == 0 }{ $req.info.bu_address}{/if}
			</em>
		</div>
		<div class="clearBoth"></div>
		</li>
        <li style="padding-left:56px;">
        <a href="{if $headerInfo.address_hide==1}javascript:alert('Address not listed');{else}{$smarty.const.SOC_HTTP_HOST}soc.php?cp=map&StoreID={$headerInfo.StoreID}&key={$headerInfo.bu_address},{$headerInfo.bu_suburb},{$headerInfo.bu_state}{/if}"><img border="0" src="{$smarty.const.IMAGES_URL}/skin/red/icon_location.gif"/></a>&nbsp;<a style=" position:relative; bottom:7px;" href="{if $headerInfo.address_hide==1}javascript:alert('Address not listed');{else}{$smarty.const.SOC_HTTP_HOST}soc.php?cp=map&StoreID={$headerInfo.StoreID}&key={$headerInfo.bu_address},{$headerInfo.bu_suburb},{$headerInfo.bu_state}{/if}">View Map</a>
        </li>
        {/if}
        {if $req.info.phone_hide == 0 }
        <li>{if $req.info.phone_hide == 0 }<span>Phone:</span>
		<div class="box_align_right">
			<em style="color: #000000; font-size: 13px;">{$req.info.bu_phone }</em>
		</div>
		<div class="clearBoth"></div>{else}<span>&nbsp;</span><em> </em><div class="clearBoth"></div>{/if}
		</li>
        {/if}
		{if $req.info.college_hide == 0 }
				{if $req.info.bu_college ne ''}
		<li>
				<span>{$lang.labelCollegeFront}:</span>
				<div class="box_align_right"><em>{ $req.info.bu_college}</em></div>
				<div class="clearBoth"></div>
		</li>
				{/if}
		{/if}
		
      <li><span>Rating:</span>
	  {if $req.aveRating eq 0}<div class="box_align_right" style="color:#F3B216; vertical-align: middle;">No Ratings</div>{else}<div class="box_align_right"><img src="{$smarty.const.IMAGES_URL}/skin/red/star_{$req.aveRating}.png" /></div>{/if}
	  <div class="clearBoth"></div>
	  </li>
       <li>
            <span>Reviews:</span>
            <em><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=disreview&StoreID={$req.info.StoreID}"><strong>{$req.info.reviews}</strong></a></em>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=oreview&StoreID={$req.info.StoreID}&level=tyew8b"><strong>Write a review</strong></a>
            <div class="clearBoth"></div>
       </li>
       {if $req.info.opening_hours neq ""}
       <li style="padding-top:10px; line-height:20px;">
       		<strong>Opening Hours:</strong><br />
            {$req.info.opening_hours}
       </li>
       {/if}
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
	  
	  {if (($req.info.subAttrib eq 1) || ($req.info.subAttrib eq 7) || ($req.info.subAttrib eq 9))}
		  {if (!empty($req.info.bu_website))}
			<li>
				<span>Link to:</span>
				<div class="box_align_right">
					<a href="{$req.info.bu_website}" target="_blank" style="color: #000000;">{$req.info.bu_name}</a>
				</div>
			</li>
		  {/if}
	   {/if}  
	  <!--
		<li>
		<strong style="white-space:nowrap;">{if $sellerhome eq "1"}HTML to embed this retailer{else}HTML to embed this item{/if}:</strong></li>
		<li>
		<input class="inputB" onclick="this.select();" id="widgetHTML" style="width:95%;" value="{$req.widgetHTML|escape:html}"/></li>-->
    </ul><br />
  </div>
  {if $req.info.sold_status eq '1'}
<div id="seller-info2">
        <p>Payments Accepted:<br />
      <span class="payment">
	  {foreach from=$req.info.payments item=lps key=key}
            {if $lang.Payments[$key].image ne '' }
                <img src="{$smarty.const.IMAGES_URL}/{$lang.Payments[$key].image}" align="absmiddle" />
                {if $lps[1]},{/if}
            {else}
                {if $lang.Payments[$key].text neq ""}
                    {$lang.Payments[$key].text}
                    {if $lps[1]},{/if}
                {/if}
            {/if} 
	  {/foreach}
	  </span>
      </p>
      {if $req.info.sold_status eq '1'}
      {if $sellerhome eq "1"}
      <p>Shipping for this seller:<br /><span class="payment">
       	{foreach from=$req.info.deliveryMethod item=lps key=key}{if $lang.Delivery[$key].text neq ""}{$lang.Delivery[$key].text}{if $lps[1]},{/if}{/if}{/foreach}
        {foreach from=$req.info.deliveryMethod|explode:"|" item=opcl key=oplk}
    		{$lang.Delivery[$opcl].text} {if $opcl eq '1' or $opcl eq '2' or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$req.info.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}<br/>
    	{/foreach}
      </span></p>
      {else}
      <p>
      	Domestic Shipping for this item<br/>
        <span class="payment">
        {foreach from=$req.info.deliveryMethod item=lps key=key}{if $lang.Delivery[$key].text neq ""}{$lang.Delivery[$key].text}{if $lps[1]},{/if}{/if}{/foreach}
        {foreach from=$req.info.deliveryMethod|explode:"|" item=opcl key=oplk}
    		{$lang.Delivery[$opcl].text} {if $opcl eq '1' or $opcl eq '2' or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$req.info.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}<br/>
    	{/foreach}
    	</span>
      	{if $l.isoversea}
        <br/>Overseas Shipping for this item<br/>
    	<span class="payment">
    	{foreach from=$l.oversea_deliveryMethod|explode:"|" item=opcl key=oplk}
    		{$lang.Delivery[$opcl].text} {if $opcl eq '1' or $opcl eq '2'  or $opcl eq '5' or $opcl eq '6'}(Fee:${foreach from=$l.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}){/if}<br/>
    	{/foreach}</span>
        {/if}</p>
      {/if}
      {/if}
  </div>
  {/if}
<div id="seller-info4" style="clear:both;width:240px">
	<table width="135" border="0" style="display:inline-block; width:115px; float:left;" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose GeoTrust SSL for secure e-commerce and confidential communications.">
<tr>
<td width="135" align="center" valign="top"><script type="text/javascript" src="https://seal.geotrust.com/getgeotrustsslseal?host_name={$smarty.const.DOMAIN}&amp;size=S&amp;lang=en"></script><br />
<a href="http://www.geotrust.com/ssl/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;"></a></td>
</tr>
</table>
    {if $req.info.sold_status eq '1'}
	<a href="https://www.paypal.com/my/cgi-bin/webscr?cmd=xpt/Marketing/securitycenter/buy/Protection-outside" target="_blank"><img style="float:left; margin:0px 0px 0px 2px;" border="0" src="{$smarty.const.IMAGES_URL}/{if ($is_samplestie eq 1) && $req.info.StoreID eq '879521'}paypal-buyer-protection-sample.gif{else}paypal-buyer-protection.gif{/if}"/></a>
    {/if}
</div>