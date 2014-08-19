<a name="top"></a>
{if $galleryList}
{literal}
<style type="text/css">
ul.gl_list{list-style:none;margin:0;}
ul.gl_list li{border-bottom:1px solid #ccc;min-height:100px;_height:100px;margin:0;padding:0;float:left;width:100%;padding-top:10px;}
ul.gl_list li div.gl_left{float:left;width:100px;height:100px;margin:0 10px 10px 5px;text-align:center;}
ul.gl_list li div.gl_right_title a{color:#352C7B;display:block;font-weight:bold;padding:10px 0 5px;text-decoration:none;}
ul.gl_list li div.gl_right{float:left;width:625px;}
.clear{font-size:0;height:0;clear:both;margin:0;padding:0;}
</style>
{/literal}
	<ul class="gl_list">
	{foreach from=$galleryList item=gl}
		<li>
			<div class="gl_left">
				<a href="/{$site}/gallery/{$gl.gallery_url}">{if $gl.gallery_category_thumbs}<img src="{$gl.gallery_category_thumbs}"/>{else}<img src="/images/100x100.jpg"/>{/if}</a>
			</div>
			<div class="gl_right">
				<div class="gl_right_title"><a href="/{$site}/gallery/{$gl.gallery_url}">{$gl.gallery_category}</a></div>
				<div class="gl_right_desc">{$gl.gallery_category_desc|truncate:300}</div>
			</div>
			<div class="clear"></div>
		</li>
	{/foreach}
	</ul>
{else}
<div style="width:100%; text-align:center;padding-top:60px;font-size:20px;">
	No gallery
</div>
{/if}
<div id="paging-wide" style="background:#{$req.bgcolor}">
&nbsp;{$pl.pagination}{$req.linkStr}&nbsp;&nbsp;&nbsp;&nbsp;<a href="#top" style=" margin-right:4px;font-weight:bold; color:#fff; text-decoration:none;">Back to top</a>
</div>