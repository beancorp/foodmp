{if $req.info.sold_status eq 1}
	<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
	{literal}
	<style type="text/css">
	p{ padding:3px 0; margin:0;font:12px arial,sans-serif; color:#777777; }
	</style>
	{/literal}
	<div style="margin:0">
	<h1 class="soc-flyers">Click & Collect</h1>
	<div class="oper-emailalerts">
		<input type="button" onclick="window.open('{$smarty.const.SOC_HTTP_HOST}clickandcollect_pdf.php{if $req.info.subAttrib}?subAttrib={$req.info.subAttrib}{/if}','_blank','width=790,height=560,toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=1');void(0);" style="cursor:pointer;padding:3px;margin:3px;" value="Create PDF / Print Flyer">
	</div>
	<div class="clear"></div>
	<div style="position:relative; width:748px; margin-bottom: 10px;">
		<img width="748" height="527" src="/flyer_images/collect/{$req.info.subAttrib}.jpg" />
		{if $req.images.mainImage.2.bname.text neq '/images/243x100.jpg' && $req.images.mainImage.2.bname.text neq '/images/242x201.jpg'}
			<div style="position:absolute; {$position_logo}">
				<img width="160" height="105" src="{$req.images.mainImage.2.bname.text}"/>
			</div>
		{else}
			<div style="position:absolute; {$position_logo}">
				<img width="{$logo_width}" src="/template_images/default_logo.jpg"/>
			</div>
		{/if}
		<div style="position:absolute; padding: 10px; border: 2px solid #878787; font-size: 24px; font-family: arial; font-weight: bold; color:#000; {$position_url}">
			{$req.info.bu_urlstring|lower}.{$smarty.const.SHORT_HOSTNAME} <img src="/flyer_images/cursor.gif" height="40px" style="position: absolute; bottom: -20px; right: -15px;" />
		</div>
	</div>
	</div>
{/if}