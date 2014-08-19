{include_php file='include/jssppopup.php'}
{literal}
<script language="javascript">
	function changecategory(obj){
		$('#sub_category').attr("value",'');
		obj.form.submit();
	}
</script>
{/literal}
<a name="top"></a>
{if $req.info.sellerType neq 'estate' and $req.info.sellerType neq 'auto' and $req.info.sellerType neq 'job'}

<div style="background:url(/skin/red/images/search_title.gif) repeat-x left top;margin:-14px 0 10px 0;width:100%;height:37px;border-bottom:1px solid #ccc;">
<form name="category_search" action="/soc.php" method="get">
{if $req.buytypeState eq "'yes'"}
<div style="float:right;padding-top:6px;padding-right:5px;">
<strong>Time left:</strong>&nbsp;&nbsp;<select name="timelefts" class="inputB" style="width:120px;padding:2px;" onchange="this.form.submit();">
<option value="0" {if $req.timelefts eq "0" || $req.timelefts eq "" }selected{/if}>All</option>
<option value="1800" {if $req.timelefts eq "1800"}selected{/if}>30 Mins</option>
<option value="3600" {if $req.timelefts eq "3600"}selected{/if}>1 Hour</option>
<option value="7200" {if $req.timelefts eq "7200"}selected{/if}>2 Hours</option>
<option value="86400" {if $req.timelefts eq "86400"}selected{/if}>24 Hours</option>
</select>
</div>
{/if}
<div style="float:right;padding-top:6px; padding-right:5px;">
	<strong>Type:</strong>&nbsp;&nbsp;<select name="buytypeState" class="inputB" style="width:90px;" onchange="this.form.submit();">
	<option value="'yes','no'" {if $req.buytypeState eq "'yes','no'" || $req.buytypeState eq ""}selected{/if}>Any</option>
	<option value="'yes'" {if $req.buytypeState eq "'yes'"}selected{/if}>Auctions</option>
	<option value="'no'" {if $req.buytypeState eq "'no'"}selected{/if}>Buy Now</option>
	</select>
</div>
<input type="hidden" name="cp" value="disprolist"/>
<input type="hidden" name="StoreID" value="{$regetStoreID}"/>
<input type="hidden" name="STRORE_category" value="{$req.select_category}"/>
<input type="hidden" name="sub_category" value="{$req.select_subcategory}"/>
</form>
</div>



<div style="width:100%; text-align:left;"><form action="" method="get">
<input type="hidden" name="cp" value="disprolist"/>
<input type="hidden" name="StoreID" value="{$regetStoreID}"/>
<select name="STRORE_category" class="select" style="width:220px;margin-right:5px;margin-bottom:3px;" onchange="changecategory(this);">
			<option value="">All Categories</option>
			{foreach from=$req.categories item=category}
			<option value="{$category.id}" {if $req.select_category eq $category.id} selected="selected"{/if}>{$category.name}</option>
			{/foreach}
</select>
<select id="sub_category" name="sub_category" class="select" style="width:220px;margin-right:10px;margin-bottom:3px;" onchange="this.form.submit();">
			<option value="">All Sub-Categories</option>
			{foreach from=$req.subcat item=category}
			<option value="{$category.id}" {if $req.select_subcategory eq $category.id} selected="selected"{/if}>{$category.name}</option>
			{/foreach}
</select>
{if $req.template.bannerImg neq ''}
<select id="custom_subcat" name="custom_subcat" class="select" style="width:220px;margin-right:10px;margin-bottom:3px;" {if empty($req.custom_subcat)}disabled="disabled"{/if} onchange="this.form.submit();">
			<option value="">Customisable Subcategory</option>
			{foreach from=$req.custom_subcat item=category}
            {if $category.name neq ''}
			<option value="{$category.name}" {if $req.select_customsubcat eq $category.name} selected="selected"{/if}>{$category.name}</option>
            {/if}
			{/foreach}
</select>
{/if}
</form>
</div>
{/if}
{if $req.tpl_type <= 1}

	{foreach from=$req.product item=pl}
	<div class="clear"></div>
	<strong class="keywordresult" style="font-size:14px; color: #392f7e; ">{$pl.name}</strong>

	<ul id="search-list">
	{foreach from=$pl.product item=spl}
	 <li style=" padding-bottom:17px;"><div style="width:150px;float:left; margin:10px 0 0 0;" class="moreImg0_css"><a href="javascript:popSliding('{$spl.StoreID}','{$spl.pid}','{$spl.limage.text}')"><img border="0" src="{$spl.limage.text}" width="{$spl.limage.width}" height="{$spl.limage.height}" class="item" {if $spl.simage.text neq "/images/79x79.jpg"} onmouseover="showmoreImage_fade('pid_{$spl.pid}',true);" onmouseout="showmoreImage_fade('pid_{$spl.pid}',false);"{/if} /></a>
		        <div id="pid_{$spl.pid}" class="moreImg_css"><img src="{$spl.bimage.text}" style="width:{$spl.bimage.width}px;height:{$spl.bimage.height}px;"/></div><div id="pid_{$spl.pid}_2" class="moreImg_arror"></div>
     </div>
		<div id="context" style="padding-left:{$spl.simage.padding}px;"><a style=" padding:17px 0 5px;" href="/{$headerInfo.url_bu_name}/{$spl.url_item_name}">{$spl.item_name}</a>{$spl.description}<a href="/{$spl.url_bu_name}" class="arrowgrey">{$spl.bu_name}</a></div>
        <div style=" float:left; margin:0 0 0 20px;_width:100px;min-width:100px;">
        <div style="max-width:230px; height:30px;">
    		<strong class="{if $spl.is_auction eq 'yes'}auctionit{else}buyit{/if}">
				{if $spl.is_auction eq 'yes'}${$spl.cur_price|number_format:2}{else}
				${$spl.price}{/if}</strong>
        </div>
        {if $spl.on_sale == 'sold'}
        <div style="width:50px;height:38px;margin-left:10px;">
            <img src="skin/red/images/sold_icon.gif" />
        </div>
        {/if}
		{if $spl.is_auction eq 'no'}{if $spl.non}<div style=" width:50px; font-weight:bold; margin-left:10px;padding-top:12px;font-size:12px;color:#777777">{$lang.non[$spl.non]}</div>{/if}{/if}
        
		{if $spl.is_auction eq 'no'}
        {if !($smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3)}
          {if $smarty.session.attribute neq "" && $smarty.session.attribute neq 4}
          <div style="width:72px;margin-left:10px;">
          <a href="javascript:checkaddtoWishlist('{$spl.pid}','{$smarty.session.ShopID}','{$spl.item_name|replace:"'":"\'"}');"><img src="/skin/red/images/add-to-wishlist.gif" style="margin:10px 0 10px 0;"/></a>
          </div>
          {/if}
      	{/if}     	
      	{/if}
        </div>
          <span class="{if $spl.is_auction eq 'yes' && $spl.end_stamp<600}auctionhurg{else}buyitnow{/if}">
		     {if $spl.is_auction eq 'yes' && $spl.end_stamp<600}&lt;&nbsp;&nbsp;{$spl.end_stamp|timeup}{elseif $spl.is_auction eq 'yes'}{$spl.end_stamp|timeup}{/if}
		 </span>
		 <div class="clear"></div>	
		</li>
	{/foreach}
	</ul>
	{/foreach}
{include file='wishlist/wishlist_link.tpl' isline="yes"}
	
{elseif $req.tpl_type eq 2 }

	<ul id="search-list">
	{foreach from=$req.product item=l}
	 <li>
	 <div style="height:31px;">
	<table width="100%" height="31" border="0" cellpadding="0" cellspacing="0" class="clear">
  <tr>
    <td height="31" bgcolor="#eeeeee">&nbsp;&nbsp;{$l.description|upper}</td>
	<td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" class="clear"/></td>
	<td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="height:31px;"><img id="space" src="/skin/red/estate/images/list-type-{$l.property}.jpg" width="31" height="31" /></td>
    <td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" class="clear"/></td>
    <td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="background:url(/skin/red/estate/images/list-bedroom.jpg)"><samp id="listNum">{$l.bedroom|default:'-':true:6}</samp></td>
    <td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" style="clear:both"/></td>
    <td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="background:url(/skin/red/estate/images/list-bathroom.jpg)"><samp id="listNum">{$l.bathroom|default:'-':true:6}</samp></td>
    <td width="3" bgcolor="#FFFFFF"><img id="space" src="/images/spacer.gif" width="3px" height="1px" style="clear:both"/></td>
    <td width="31" align="center" valign="bottom" bgcolor="#eeeeee" style="background:url(/skin/red/estate/images/list-carspace.jpg)"><samp id="listNum">{$l.carspaces|default:'-':true:6}</samp></td>
  </tr>
</table>
	</div>
	
	
	
	<div style=" float:left; width:150px; padding-top:3px; padding-bottom:10px; padding-right:10px;" class="moreImg0_css">
	  <a href="javascript:popSliding('{$l.StoreID}','{$l.pid}','{$l.simage.text}')"><img border="0" src="{$l.simage.text}" width="{$l.limage.width}" height="{$l.limage.height}" class="item" {if $l.simage.text neq "/images/79x79.jpg"} onmouseover="showmoreImage_fade('pid_{$l.pid}',true);" onmouseout="showmoreImage_fade('pid_{$l.pid}',false);"{/if} /></a>
		        <div id="pid_{$l.pid}" class="moreImg_css"><img src="{$l.bimage.text}" style="width:{$l.bimage.width}px;height:{$l.bimage.height}px;"/></div><div id="pid_{$l.pid}_2" class="moreImg_arror"></div>
	</div>
	
      <div id="context" style="width:520px"><a href="{if $l.is_auction=='yes'}/soc.php?cp=disauction&amp;StoreID={$l.StoreID}&amp;proid={$l.pid}{else}/{$req.info.url_bu_name}/{$l.url_item_name}{/if}" style="font-weight:bold">{$l.item_name|truncate:60:"..."}</a>
	  {$l.suburbName}, {$l.stateName}{if $l.location neq ''}, { $l.location}{/if} <br />
	  {truncate content="`$l.content`" length="200"}
	  <a href="/{$req.info.url_bu_name}" class="arrowgrey">{$req.info.bu_name}</a>
	  </div>
	  {if $l.category < 4 }<em style=" float:left; padding-top:5px; width:110px;font-weight:bold; font-size:14px;">{if $l.price >0}${$l.price|number_format:2}{/if}{if $l.priceMethod > 0}<br/>{valueOfArray arrValue=$lang.val.priceMethod value=$l.priceMethod}{/if}</em>{/if}
	  <div class="clear"></div>	
	 </li>
		
	{/foreach}
	</ul>

{elseif $req.tpl_type eq 3}

	
	<ul id="search-list">
	{foreach from=$req.product item=l}
	 <li style="height:79px;">
	 <div style=" float:left; width:150px; padding-top:0px; padding-bottom:10px; padding-right:10px;" class="moreImg0_css">
	 <a href="javascript:popSliding('{$l.StoreID}','{$l.pid}','{$l.simage.text}')"><img border="0" src="{$l.simage.text}" width="{$l.limage.width}" height="{$l.limage.height}" class="item" {if $l.simage.text neq "/images/79x79.jpg"} onmouseover="showmoreImage_fade('pid_{$l.pid}',true);" onmouseout="showmoreImage_fade('pid_{$l.pid}',false);"{/if} /></a>
		        <div id="pid_{$l.pid}" class="moreImg_css"><img src="{$l.bimage.text}" style="width:{$l.bimage.width}px;height:{$l.bimage.height}px;"/></div><div id="pid_{$l.pid}_2" class="moreImg_arror"></div>
	 </div>
		<div id="context" style=" float:left; width:490px">
		{if $l.is_auction == 'yes'}<a href="soc.php?cp=disauction&StoreID={$l.StoreID}&proid={$l.pid}">{else}<a href="/{$headerInfo.url_bu_name}/{$l.url_item_name}">{/if}{$l.item_name}</a>
		
		{$l.year} {$lang.tt.year}, {$l.makeName}, {$l.modelName}
		{if $l.transmission neq ''}, {$lang.val.transmission[$l.transmission]}{/if}
		{if $l.pattern neq ''}, {$lang.val.pattern[$l.pattern]}{/if}
		{if $l.color neq ''}, {$lang.tt.color}: {$l.color}{/if}
		{if $l.kms neq ''}, {$l.kms}{$lang.tt.mls}{/if}
		<a href="/{$req.info.url_bu_name}" class="arrowgrey">{$req.info.bu_name}</a>
		</div>
		<strong style=" float:left;font-weight:bold; font-size:14px; padding-top:5px; width:110px;">{if $l.price > 0}${$l.price|number_format:2}{/if}</strong>
		
	<div class="clear"></div>	
	 </li>
	{/foreach}
	</ul>
	
{else}
	
		<ul id="search-list">
	{foreach from=$req.product item=l} 
		<li>
	
		  <div id="context" style="width:540px;">
		  <a href="/{$req.info.url_bu_name}/{$l.url_item_name}">{$l.item_name|truncate:60:"..."}</a>
		  {truncate content="`$l.content1`" length="400"}
		  <a href="/{$req.info.url_bu_name}" class="arrowgrey">{$req.info.bu_name}</a>
		  </div>
		  <strong>{if $l.salaryMin eq $l.salaryMax}{if $l.salaryMin neq -2}${/if}{valueOfArray arrValue=$lang.val.min_salary value=$l.salaryMin}{elseif $l.salaryMin eq -2 }${valueOfArray arrValue=$lang.val.max_salary value=$l.salaryMax}{elseif $l.salaryMax eq -2 }${valueOfArray arrValue=$lang.val.min_salary value=$l.salaryMin}{else}${valueOfArray arrValue=$lang.val.min_salary value=$l.salaryMin} - ${valueOfArray arrValue=$lang.val.max_salary value=$l.salaryMax}{/if}</strong>
	
	<div class="clear"></div>
		</li>
	{/foreach}
	</ul>

{/if}


<div id="paging-wide" style="background:#{$req.bgcolor}">
&nbsp;{$pl.pagination}{$req.linkStr}&nbsp;&nbsp;&nbsp;&nbsp;<a href="#top" style=" margin-right:4px;font-weight:bold; color:#fff; text-decoration:none;">Back to top</a>
</div>
