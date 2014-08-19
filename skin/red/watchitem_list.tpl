{literal}
<style type="text/css">
table.watchlist{}
table.watchlist th{background:#9E99C1;color:#FFF; border-right:1px solid #FFF;padding-left:10px;font-weight:bold;font-size:12px;height:23px;}
table.watchlist td img{width:81px;}
table.watchlist td{border-bottom:1px solid #ccc;}
table.watchlist td.first{width:90px;padding:5px;}
table.watchlist td.center{padding:5px;text-align:left;}
table.watchlist td.last{padding:5px;text-align:left;width:100px;}
table.watchlist td.lastline{border:0;height:23px;}
table.watchlist div.item a{color:#352C7B;font-weight:bold;padding: 0 0 5px 0; text-decoration:none;}
table.watchlist div.desc{padding: 0 0 5px 0; }
table.watchlist div.time{color:red; padding: 0 0 5px 0;}
table.watchlist div.time span{color:#352C7B; }
table.watchlist div.time span.status{color:red; font-weight:bold;}
table.watchlist div.seller a{background:transparent url(/skin/red/images/li-orange.gif) no-repeat scroll 0 6px;
color:#777777;padding-left:15px; padding-top:4px;text-decoration:none;font-weight:bold;}
</style>
{/literal}
<table cellpadding="0" cellspacing="0" width="100%" class="watchlist">
<tr>
	<th valign="middle">Image</th>
	<th valign="middle">Information</th>
	<th valign="middle">Action</th>
</tr>
{if $req.watchlist}
{foreach from=$req.watchlist item=wl}
<tr>
<td class="first"><a href="/{$wl.bu_urlstring}/{$wl.url_item_name}"><img src="{if $wl.smallPicture}{$wl.smallPicture}{else}/images/79x79.jpg{/if}"/></a></td>
<td class="center" valign="top">
<div class="item"><a href="/{$wl.bu_urlstring}/{$wl.url_item_name}">{$wl.item_name}</a></div>
<div class="desc">{$wl.description|truncate:200}</div>
<div class="time">
{if $wl.end_stamp<0}<span class="status">Close</span>{else}
	<span>Current Bid:</span> ${$wl.cur_price}  <span>&nbsp;&nbsp;{$wl.bu_nickname}</span> &nbsp;&nbsp;&nbsp;{if $wl.end_stamp>0}<span>Time left:</span> {$wl.end_stamp|timeup}{/if}
	{/if}
</div>
<div class="seller"><a href="/{$wl.bu_urlstring}">{$wl.bu_name}</a></div>
</td>
<td class="last"><a href="/soc.php?cp=watchitemlist&act=del&wid={$wl.id}" onclick="return confirm('Are you sure to delete this item?');">Delete</a></td>
</tr>
{/foreach}
<tr>
<td colspan="3" align="center" class="lastline">{$req.links.all}</td>
</tr>
{/if}
</table>