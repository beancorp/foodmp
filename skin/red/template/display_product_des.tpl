{include_php file='include/jssppopup.php'}
{foreach from=$req.items.product item=l}
	<p class="bigger">{$l.item_name}</p>
	
	{if $req.info.attribute eq 0}
	
	<p class="bigger"><a href="javascript:popUp('{$l.image_name.name}')"><img border="0" src="{$l.image_name.name}" width="300" /></a></p>
	<p class="bigger"><span class="TextFS14 TextJustify"><div id="shopdes">{$l.description}</div></span></p>
	
	{elseif $req.info.attribute eq 3}
	
	<p class="bigger"><span class="TextFS14 TextJustify"><div id="shopdes">
		<div style="{inarray arrValue=$l.contentStyle1|explode:'':true value='1' return='font-weight:bold;'} {inarray arrValue=$l.contentStyle1|explode:'':true value='2' return='font-style:italic;'}">
		{$l.content1|nl2br}
		</div>
		<div class="clearBoth"></div>
		
		{assign var=arrContent value=$l.content2|explode:'|=&&&&=|'}
		{if $arrContent|isarray}
			<br />
			<strong>Responsibilities include:</strong>
			<br />
			<ol class="content-item">
			{foreach from=$arrContent item=sl}
			   {if sl neq ''}
				<li>{$sl}</li>
			   {/if}
			{/foreach}
			</ol>
		{/if}

		<div style="{inarray arrValue=$l.contentStyle3|explode:'':true value='1' return='font-weight:bold;'} {inarray arrValue=$l.contentStyle3|explode:'':true value='2' return='font-style:italic;'}">
		{$l.content3|nl2br}
		</div>
			
	</div></span></p>
		
	{else}
	
		{if $l.images.mainImage.0.sname.text neq "/images/79x79.jpg"}
		<p class="bigger"><a href="javascript:popUp('{$l.images.mainImage.0.bname.text}');"><img border="0" src="{$l.images.mainImage.0.bname.text}" width="300" /></a>	</p>
		{/if}
	<p class="bigger"><span class="TextFS14 TextJustify"><div id="shopdes">{$l.content}</div></span></p>
	
	{/if}
{/foreach}