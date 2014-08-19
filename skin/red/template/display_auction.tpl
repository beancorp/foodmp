<div id="winnerswf" style="position:absolute;z-index: 200008; left: -270px; top: -80px;display:none;">
	<object width="780" height="640">
         <param name="movie" value="{if $req.items.product.0.auct_celeb}{$req.items.product.0.auct_celeb}{else}/skin/red/images/auctions/win_US_1.swf{/if}"></param>
         <param name=wmode value=transparent></param>
         <param name="allowscriptaccess" value="always"></param>
         <embed src="{if $req.items.product.0.auct_celeb}{$req.items.product.0.auct_celeb}{else}/skin/red/images/auctions/win_US_1.swf{/if}" type="application/x-shockwave-flash" allowscriptaccess="always"  width="780" wmode="transparent" height="640"></embed>
    </object>
</div>
<link href="/skin/red/css/auction.css" type="text/css" rel="stylesheet" media="screen"/>
<script language="javascript" src="/skin/red/js/auctions.js"></script>
{include_php file='include/jssppopup.php'}
<h2  class="safarispace" style="background-color:#{$templateInfo.bgcolor};margin:0;width:492px;">Item Detail <a href="javascript: history.go(-1)">Back</a></h2>
{foreach from=$req.items.product item=l}
<script>var timeleft={$l.countTime};var j=0;</script>

<div style="min-height:47px;_height:47px;padding:10px 0 3px 0;">
<div class="auction_item_title">{$l.item_name} </div>
<div style="float:left; padding-bottom: 15px; width:230px;">
                <fb:like href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$l.url_item_name}" send="false" width="265" show_faces="false" font="arial"></fb:like>
                </div>
<div class="clear"></div>
<div class="auction_timer">
	<div style="float:left;" id="auction_timer">
	{if $l.countTime}
        Time Left:<span class="timmer" id="timmer">00h 00m 00s</span> <span class="enddate">(Ends {$l.end_stamp2|date_format:"%e %b %Y - %I:%M %p"} EST)</span>
        <script>var intev1 = setInterval("countdown('timmer','intev1','{$l.pid}');",1000);
            var inthigh = setInterval("hidelight();",400);
        </script>
	{else}
        {if $l.cur_price>=$l.reserve_price&&$l.bid_counter}
            <span class='timmer_no'>Item sold. This auction is now closed.</span>
        {else}
            <span class='timmer_no'>Item not sold. This auction is now closed.</span>
        {/if}
	{/if}
	</div>
<div style="float:right;" id="musicDIV">
	<span id="musiccontent"></span>&nbsp;
	<select class="inputB" id="bidmusic" style="width:110px;padding:1px;border:1px solid #453C8B;">
		<option value="">No bid sounds</option>
	{if $req.audio}
        {foreach from=$req.audio item=audio name=audioList}
		<option value="/skin/red/images/auctions/{$audio.bid_file}">{$audio.bid_name}</option>
        {/foreach}
	{/if}
	</select>
</div><div class="clear"></div>
</div>
</div>
<div class="bid_message" id="bid_message"></div>
<div class="auction_content">
	<div class="auction_mainimg" style="position:relative;">
	<table width="100%" cellpadding="0" cellspacing="0" height="161">
	<tr><td width="161" align="center" valign="middle">
	<div id="soldwaters" style="{if $l.countTime<=0&&$l.cur_price>$l.reserve_price&&$l.bid_counter}{else}display:none;{/if}" class="soldwater"><img src="/skin/red/images/auctions/sold.png"/></div>
	<img src="{$l.images.mainImage.0.sname.text}" {images_size df_width=161 df_height=161 width=$l.images.mainImage.0.sname.width height=$l.images.mainImage.0.sname.height}/>
	</td></tr>
	</table>
	</div>
	<div class="auction_price">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" height="40">
		<table cellpadding="0" cellspacing="0">
			<tr>
			<td class="first" style="width:72px;" valign="bottom" id="wintext">{if $l.countTime}Current bid{else}{if $l.cur_price>=$l.reserve_price&&$l.bid_counter}Winning{else}Last{/if} bid{/if}: </td>
			<td width="35"><img src="/skin/red/images/flag_usa.gif"/></td>
			<td width="1%"  valign="bottom"><span class="cur_price" id="cur_price">${$l.cur_price|number_format:0:'.':','}</span></td>
	{if $l.countTime}
			<td valign="bottom" style="padding-bottom:2px;" id="updater1"><span class="cur_person" id="cur_person" onclick="activeTabs($('#bidder_content'),'bid_content');">{if $l.winner_id ne ''}{$l.winner_id}{else}Starting price{/if}&nbsp;</span></td><input type="hidden" id="hide_bidname" value="{$smarty.session.NickName|escape:html}"/>
			</tr>
		</table>
		</td>
	</tr>
	<tr id="updater2"><td height="40" class="first" valign="top" style="padding-top:10px;">Next bid:</td>
		<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td width="135"><span style="float:left;padding-top:10px;padding-right:3px;">$</span>{literal}<input type="text" id="inputPrice" class="inputB" maxlength="11" value="{/literal}{if $l.winner_id}{$l.cur_bid_price|number_format:0:'.':','}{else}{$l.cur_price|number_format:0:'.':','}{/if}{literal}" style="width:110px;margin:3px 0 0 0;" onkeyup="FixValPrice('inputPrice')" onkeypress="FixValPrice('inputPrice')" o_value="" t_value="" onblur="FixValPrice('inputPrice')" />{/literal}
					<br>(Enter $<span id="cur_price_html">{if $l.winner_id}{$l.cur_bid_price|number_format:0:'.':','}{else}{$l.cur_price|number_format:0:'.':','}{/if}</span> or more)
			</td>
			<td align="left" valign="top">
				<img src="/skin/red/images/bid_btn.jpg" style="margin-top:1px;cursor:pointer;" onclick="bid({$l.pid},{$smarty.session.ShopID|default:0},'{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$l.url_item_name}');"/>
			</td>
			</tr>
		</table>
             
		</td>
	</tr>
	<tr id="updater3"><td height="35" class="first" valign="middle">Bid count:</td>
	<td height="35" valign="middle">
		<table  cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><span style="color:#ff7d01;font-weight:bold;padding-left:5px;" id="bid_counter">{$l.bid_counter}</span></td>
				<td align="right">
				<div style="position:relative;">
				<span style="color:#453c8d;font-weight:bold;margin:0 10px;background:url(/skin/red/images/watch_icon.gif) no-repeat;padding:3px 0 3px 20px;line-height:18px;cursor:pointer;" onclick="addwatch({$l.pid},{$smarty.session.ShopID|default:0});">Watch item</span> | <span style="color:#453c8d;margin:0 10px;line-height:18px;font-weight:bold;background:url(/skin/red/images/auction_icon.gif) no-repeat;padding:3px 0 3px 20px;cursor:pointer;" onclick="$('#autobidfuns').show(500)">Auto bid</span>
				<div class="autobidfuns" id="autobidfuns">
					<div>
                    <table cellpadding="0" cellspacing="0" border="0" width="200" height="34">
                    <tr>
                    	<td width="65" valign="middle" align="left" style="font-weight:bold;">Max. bid:</td>
                        <td width="37" valign="middle" align="left">$ </td>
                        <td width="98" valign="middle" align="left"><input type="text" id="maxbid_price" maxlength="11" style="width:80px;margin:0;" value="{$l.cur_bid_price|number_format:0:'.':','}" class="inputB" onkeyup="FixValPrice('maxbid_price')" onkeypress="FixValPrice('maxbid_price')" o_value="" t_value="" onblur="FixValPrice('maxbid_price')" /></td>
                    </tr>
                    </table>
                    </div>
					<div style="float:left;text-align:left;">
					<div style="white-space:nowrap;"><a Onclick="javascript:showDesc()" style="text-decoration:underline; cursor:pointer;">What is an Autobid?</a> &nbsp;&nbsp;
                    <a onclick="autobid({$l.pid},{$smarty.session.ShopID|default:0},'{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$l.url_item_name}');" style="cursor:pointer;text-decoration:underline;" >Place bid</a>&nbsp;&nbsp; 
                    <a href="javascript:void(0)" onclick="$('#autobidfuns').hide(500)" style="text-decoration:none">[X]</a></div>
                    	<div id="desc" style="display:none;text-align:left;"><br />Set the maximum amount that you want to bid and the system will automatically bid up to this amount for you. Autobid will only bid the minimum amount necessary to win the auction.
                        </div>
                    </div><div class="clear"></div>
				</div>
				</div>
				</td>
			</tr>
		</table>
	</td>
	</tr>
	<tr id="updater4">
        <td height="28" class="first" style="width:auto;padding-top:12px;" valign="top" colspan="2">Reserve price:
            {if $l.reserve_price > 0}
                <span id="reserve_price" onclick="window.open('/soc.php?cp=reserve_price','_blank')" class="{if $l.cur_price>$l.reserve_price}reserve_yes">The reserve has been met{else}reserve_no">A reserve has been set{/if}</span>
            {else}
                <span style="color:#453c8d">No reserve has been set</span>
            {/if}
        </td>
    </tr>
	{else}
		<td>&nbsp;</td>
		</tr>
		</table>
		</td></tr>
	{/if}
	</table>
	</div>
    {if $l.is_certified}
    <div class="clear" style="height:10px;"></div>
    <div style="float:right;">
        <a href="/soc.php?cp=certified-bidder-auctions" target="_blank">
            <img src="/skin/red/images/buttons/certified.jpg" title="{$lang.certified.imageTitle}" alt="{$lang.certified.imageTitle}" style="float:right;"/>
        </a>
		
		
		
        {if $smarty.session.ShopID neq $req.info.StoreID}
            {if $l.countTime}
                {if $req.cancertified }
                <div style="float:right;margin-top:7px;margin-right:10px;">
                &nbsp;&nbsp;&nbsp;<a href="javascript:checkCertified({$l.pid}, {$smarty.session.ShopID|default:0}, {$req.info.StoreID},'{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$l.url_item_name}');">Get certified to bid at this auction</a>
                </div>
                {/if}
            {/if}
        {/if}
    </div>
	
    <div class="clear"></div>
    {/if}
	<div class="clear"></div>
	<div id="bid_message_2" class="bid_message"></div>
	<div class="clear"></div>
	<div class="note" style="color:black;">If a bid is made during the final 10 seconds of an auction, the auction time will be extended
		automatically by a further 20 seconds from the time such bid is made. The auction will close
		once all bidding activity has completely stopped for a period of 10 consecutive seconds. <a href="/soc.php?cp=auction" style="color:red;">Learn More</a>
	</div>
	<div class="tab">
	<a href="javascript:void(0);" onclick="activeTabs(this,'des_content');" onFocus="this.blur()" class="tabs tabsDesc tabsActive">
	</a>
	<a href="javascript:void(0);" onclick="activeTabs(this,'img_content');" onFocus="this.blur()" class="tabs tabsImg"></a>
	<a href="javascript:void(0);" id="bidder_content" onclick="activeTabs(this,'bid_content');" onFocus="this.blur()" class="tabs tabsBid"></a>
	<a href="javascript:void(0);" onclick="activeTabs(this,'ship_content');" onFocus="this.blur()" class="tabs tabsShip"></a>
	<a href="javascript:void(0);" onclick="activeTabs(this,'extra_content');" onFocus="this.blur()" class="tabs tabsExtra"></a>
	<div class="clear"></div>
	</div>
</div>
<div id="auction_div_content" style="position:relative;">
	<div class="des_content" style="word-wrap: break-word;">
	{$l.description}
	</div>
	<div class="img_content" style="margin-left:0;padding-left:0;float:left;_width:752px;">
		<div class="img_left_list" style="width:51px;margin-right:14px;float:left;margin-left:0;padding-left:0;">
		{if $l.images.mainImage.0.sname.text neq '/images/243x212.jpg'}
		<div class="small_normal small_high" onclick="showMoreImg(this,'{$l.images.mainImage.0.bname.text}','{$l.images.mainImage.0.bname.width}','{$l.images.mainImage.0.bname.height}')">
			<img src="{$l.images.mainImage.0.sname.text}" width="47"/>
		</div>
		{php}$ishigh = true;{/php}
		{/if}
		{foreach from=$l.images.subImage item=imgs name=imgItem}
			{if $imgs.sname.text neq '/images/79x79.jpg'}
			<div class="small_normal {php}if(!$ishigh){echo "small_high";}{/php}" onclick="showMoreImg(this,'{$imgs.bname.text}','{$imgs.bname.width}','{$imgs.bname.height}')"><img width="47" border="0" src="{$imgs.sname.text}"/></div>
			{/if}
		{/foreach}
			
		</div>
		<div class="img_right_content" style="margin-left:65px;border:1px solid #CBCBCB;_height:398px;">
		<table id="clbgclaass" cellpadding="0" cellspacing="0" height="400" width="100%">
		<tr><td align="center" valign="middle">
		<div style="display:none;background:#FFFFFF url(/skin/red/images/slideshow/loading.gif) no-repeat scroll center center;width:100%;height:400px;" id="div_img_loading">
		
		</div>
		<div id="div_img_show_content">
		{if $l.images.mainImage.0.sname.text neq '/images/243x212.jpg'}
			<img id="content_images" {images_size df_width=680 df_height=398 width=$l.images.mainImage.0.bname.width height=$l.images.mainImage.0.bname.height} src="{$l.images.mainImage.0.bname.text}" onload="img_loaded()"/>
		{else}
			{foreach from=$l.images.subImage item=imgs name=imgItem}
				{if $imgs.sname.text neq '/images/79x79.jpg'}
				<img id="content_images" src="{$imgs.bname.text}" onload="img_loaded()"/>
				{php}break;{/php}
				{/if}
			{/foreach}
		{/if}
		</div>
		</td></tr>
		</table>
		</div>
		<div class="clear"></div>
	</div>
	<div class="bid_content" id="bid_content">
	<table width="100%" cellpadding="0" cellspacing="0">
		<colgroup>
			<col width="40%"/>
			<col width="25%"/>
			<col width="35%"/>
		</colgroup>
		<thead>
		<tr>
			<th class="bid_header_first">Bidder</th>
			<th class="bid_header">Bid amount</th>
			<th class="bid_header">Bid time</th>
		</tr>
		</thead>
		<tbody>
		{if $l.bidlist}
		{foreach from=$l.bidlist item=blist key=s}
			<tr {if $s%2 eq 0}class="bid_high"{/if}>
			<td class="bid_line_first {if $s eq 0}tfirst{/if}">{$blist.bu_nickname}</td>
			<td class="bid_line {if $s eq 0}tfirst{/if}">${$blist.price|number_format:0:'.':','}</td>
			<td class="bid_line {if $s eq 0}tfirst{/if}">{$blist.bid_time|date_format:"%d-%b-%Y &nbsp;&nbsp;&nbsp; %H:%M:%S "} EST</td>
			</tr>
		{/foreach}
		<tr>
			<td></td>
			<td></td>
			<td align="right">(Last 20 bids)</td>
		</tr>
		{/if}
		</tbody>
	</table>
	</div>
	<div class="ship_content">
	<table cellspacing="0" width="100%">
		<tr><th class="bid_header_first" style="color:#FF7D01;" align="left">Shipping:</th></tr>
		<tr><td style="line-height:30px;padding-bottom:15px;">
		<ol>
			
		<li>
		Domestic Shipping for this item<br/>
        <span class="payment">
        {foreach from=$l.deliveryMethod|explode:"|" item=opcl key=oplk}
    		{if $req.info.bu_delivery_text[$opcl] neq ''}{if $opcl eq '5'}<strong>{/if}{$req.info.bu_delivery_text[$opcl]}{if $opcl eq '5'}</strong>{/if} 
            (Fee:${if $l.postage}{foreach from=$l.postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})
            <br/>
            {/if}
    	{/foreach}
    	</span></li>
    	
      	{if $l.isoversea}
        <li>Overseas Shipping for this item<br/>
    	<span class="payment">
    	{foreach from=$l.oversea_deliveryMethod|explode:"|" item=opcl key=oplk}
        {if $req.info.bu_delivery_text[$opcl] neq ''}
    		{if $opcl eq '5'}<strong>{/if}{$req.info.bu_delivery_text[$opcl]}{if $opcl eq '5'}</strong>{/if} 
            (Fee:${if $l.oversea_postage}{foreach from=$l.oversea_postage|explode:"|" item=costl key=costk}{if $costk eq $oplk}{$costl|number_format:2}{/if}{/foreach}{else}0.00{/if})<br/>
        {/if}
    	{/foreach}</span></li>
        {/if}
    	</ol>
		</td></tr>
		
		<tr><th class="bid_header_first" style="color:#FF7D01;" align="left">Payment:</th></tr>
		<tr><td style="line-height:30px;">
		<ol>
		{foreach from=$l.payments item=lps key=key}
		  	{if $key eq 2}{php}continue;{/php}{/if}
		  	{if $lang.Payments[$key].image ne '' }
				<li><img src="/{$lang.Payments[$key].image}" align="absmiddle" /></li>
			{else}
				{if $lang.Payments[$key].text neq ""}
					<li>{$lang.Payments[$key].text}</li>		
				{/if}
			{/if} 
	  	{/foreach}
	  	</ol>
		</td></tr>
	</table>
	</div>
	<div class="extra_content">
		<ol>
		{if $l.extra}
			{foreach from=$l.extra item=ext}
			{if $ext neq ""}
			<li>{$ext|nl2br}</li>
			{/if}
			{/foreach}
		{/if}
		</ol>
	</div>
</div>
<div class="clear"></div>
{/foreach}

{if $sellerhome eq 1}
<div class="clear"></div>
 <div id="paging" style="margin:25px 0;background:#{$req.template.bgcolor};width:487px;"><strong><a href="soc.php?cp=disprolist&StoreID={$req.info.StoreID}">All Items</a>&nbsp;&nbsp;({$req.itemNumbers})</strong> </div>
{/if}
<div class="clear"></div>


{literal}
<style>
#nascarcontent {
	
}
#contentNascar p,#contentNascar li {color:#353533; font-size:13px;text-align:left;}
#contentNascar strong {color:black; font-size:14px}
</style>
<script language="javascript" type="text/javascript">
//des_height=$(".des_content").height()+20;
//content_height=des_height>448?des_height:448;
//$("#auction_div_content").height(content_height);
var certifiedInfo = {
    pass:{/literal}{if $req.certifiedAuthorise}1{else}0{/if}{literal},
    storeId:'{/literal}{$req.info.StoreID}{literal}',
    productId:'{/literal}{$req.items.product.0.pid}{literal}'
};
</script>
{/literal}

