<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>
		<generator>https://socexchange.com.au</generator>
		<title><![CDATA[{$req.title}]]></title>
		<link>{$req.link}</link>
		<language>en</language>
		<webMaster>mail@thesocexchange.com</webMaster>
		<copyright>copy;2008 Food Marketplace Australia</copyright>
		<pubDate>{$req.pubDate}</pubDate>
		<lastBuildDate>{$req.lastDate}</lastBuildDate>
		<description><![CDATA[{$req.description|nl2br}]]></description>
		<image>
			<title>{$req.image.title}</title>
			<url>{$req.image.url}</url>
			<link>{$req.image.link}</link>
		</image>
		{if $req.item}
		{foreach from=$req.item item=l}
		<item>
			<title><![CDATA[{$l.title}]]></title>
			<link>{$l.link}</link>
			<guid isPermaLink="true">{$l.guid}</guid>
			<pubDate>{$l.pubDate}</pubDate>
			<description><![CDATA[{$l.description|nl2br}]]></description>
		</item>
		{/foreach}
		{/if}
	</channel>
</rss>
