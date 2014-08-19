<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>
		<generator>https://socexchange.com.au</generator>
		<title>The Food Marketplace Australia Items</title>
		<link>http://www.socexchange.com.au/soc.php?cp=category</link>
		<language>en</language>
		<webMaster>mail@thesocexchange.com</webMaster>
		<copyright>&amp;copy;2008 Food Marketplace Australia</copyright>
		<pubDate>{$req.pubDate}</pubDate>
		<lastBuildDate>{$req.lastBuildDate}</lastBuildDate>
		<description>The Food Marketplace Australia Items</description>
		<image>
			<title>The Food Marketplace Australia Items</title>
			<url>http://www.socexchange.com.au/images/product_logo.gif</url>
			<link>http://www.socexchange.com.au/</link>
		</image>

	{foreach from=$req.product item=groupList}
		{foreach from=$groupList.product item=l}
		<item>
			<title>{$l.item_name}</title>
			<link>http://www.socexchange.com.au/soc.php?cp=dispro&amp;StoreID={$l.StoreID}&amp;proid={$l.pid}</link>
			<guid isPermaLink="true">{$l.guid}</guid>
			<pubDate>{$l.dateadd}</pubDate>
			<description><![CDATA[{$l.description|nl2br}]]></description>
		</item>
		{/foreach}
	{/foreach}
	</channel>
</rss>