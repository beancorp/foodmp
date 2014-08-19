<h3 class="content" style="font-size:16px;margin-bottom:0px;">Latest Jokes</h3>
{if $req.jokelist.list}
<ul class="arrows">
	{foreach from=$req.jokelist.list item=hls}
    <li>
	    <p class="bigger"><a href="/soc.php?cp=jokesinfo&jokekeywords={$req.urlkeyword}&id={$hls.id}" class="bigger" ><em style="color:#3B307C">{$hls.title}</em></a></p>
    </li>
	{/foreach}
</ul>
{else}
0 item(s) found from your search.
{/if}
<div id="paging-wide" style="margin-top:10px;width:520px;">
&nbsp;{$req.jokelist.linkStr}
</div>