<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<ul class="hp-cats">
{foreach from=$req.category[0] item=catlist}
<li><a href="soc.php?cp=prolist&amp;id={$catlist.id}">{$catlist.name}</a></li>
          {foreach from=$catlist.sublist item=scl}
            <li><a href="soc.php?cp=prolist&amp;id={$scl.id}">{$scl.name}</a>&nbsp;{*({$scl.number})*}</li>
          {/foreach}
{/foreach}
</ul>

<ul class="hp-cats">
{foreach from=$req.category[1] item=catlist}
<li><a href="soc.php?cp=prolist&amp;id={$catlist.id}">{$catlist.name}</a></li>
          {foreach from=$catlist.sublist item=scl}
            <li><a href="soc.php?cp=prolist&amp;id={$scl.id}">{$scl.name}</a>&nbsp;{*({$scl.number})*}</li>
          {/foreach}
{/foreach}
</ul>

{if $req.category.2|isarray }
<ul class="hp-cats">
{foreach from=$req.category[2] item=catlist}
<li><a href="soc.php?cp=prolist&amp;id={$catlist.id}">{$catlist.name}</a></li>
          {foreach from=$catlist.sublist item=scl}
            <li><a href="soc.php?cp=prolist&amp;id={$scl.id}">{$scl.name}</a>&nbsp;{*({$scl.number})*}</li>
          {/foreach}
{/foreach}
</ul>
{/if}

{include file='three_seller_into.tpl'}
