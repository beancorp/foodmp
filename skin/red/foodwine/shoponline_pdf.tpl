<link type="text/css" href="{$smarty.const.SOC_HTTP_HOST}skin/red/css/foodwine.css" rel="stylesheet"/>
{literal}
<style type="text/css">
p{ padding:3px 0; margin:0;font:12px arial,sans-serif; color:#777777; }
</style>
{/literal}
<div style="margin: 10px;">
<div style="background-color:#f5f5f5; border:none; position:relative; width: 710px; font: arial,sans-serif;">
	<img width="710" src="{$smarty.const.SOC_HTTP_HOST}flyer_images/pdf/shop/{$req.info.subAttrib}.jpg" />
	{if $req.images.mainImage.2.bname.text neq '/images/243x100.jpg' && $req.images.mainImage.2.bname.text neq '/images/242x201.jpg'}
		<div style="position:absolute; {$position_logo}">
			<img width="{$logo_width}" src="{$smarty.const.SOC_HTTP_HOST}{$req.images.mainImage.2.bname.text}"/>
		</div>
	{else}
		<div style="position:absolute; {$position_logo}">
			<img width="{$logo_width}" src="{$smarty.const.SOC_HTTP_HOST}template_images/default_logo.jpg"/>
		</div>
	{/if}
	<div style="position:absolute; padding: 10px; border: 2px solid #878787; font-size: 24px; font-family: arial; font-weight: bold; color:#000; {$position_url}">
		{$req.info.bu_urlstring|lower}.{$smarty.const.SHORT_HOSTNAME} <img src="{$smarty.const.SOC_HTTP_HOST}flyer_images/cursor.gif" height="40px" style="position: absolute; bottom: -20px; right: -15px;" />
	</div>
</div>
</div>
<div style="margin: 10px;">
<div style="background-color:#f5f5f5; border:none; position:relative; width: 710px; font: arial,sans-serif;">
	<img width="710" src="{$smarty.const.SOC_HTTP_HOST}/flyer_images/pdf/shop/{$req.info.subAttrib}.jpg" />
	{if $req.images.mainImage.2.bname.text neq '/images/243x100.jpg' && $req.images.mainImage.2.bname.text neq '/images/242x201.jpg'}
		<div style="position:absolute; {$position_logo}">
			<img width="{$logo_width}" src="{$smarty.const.SOC_HTTP_HOST}{$req.images.mainImage.2.bname.text}"/>
		</div>
	{else}
		<div style="position:absolute; {$position_logo}">
			<img width="{$logo_width}" src="{$smarty.const.SOC_HTTP_HOST}template_images/default_logo.jpg"/>
		</div>
	{/if}
	<div style="position:absolute; padding: 10px; border: 2px solid #878787; font-size: 24px; font-family: arial; font-weight: bold; color:#000; {$position_url}">
		{$req.info.bu_urlstring|lower}.{$smarty.const.SHORT_HOSTNAME} <img src="{$smarty.const.SOC_HTTP_HOST}flyer_images/cursor.gif" height="40px" style="position: absolute; bottom: -20px; right: -15px;" />
	</div>
</div>
</div>