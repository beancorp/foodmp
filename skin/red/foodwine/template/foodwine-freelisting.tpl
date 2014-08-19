<link type="text/css" href="{$smarty.const.SOC_HTTP_HOST}skin/red/css/foodwine.css" rel="stylesheet"/>
<div id="free_retailer_listing" style="padding-bottom:60px; position:relative;">
	<h2 style="color:#3C3082; font-weight:bold; font-size:22px">{$req.info.bu_name}</h2>
	<span style="line-height:18px;">
        { $req.info.bu_address}<br />{$req.info.bu_suburb} {$req.info.bu_state} {$req.info.bu_postcode|substr:1:4}    
    </span>
    <p><span>Phone:</span>
    <em>{$req.info.bu_phone }</em></p>
	<a id="retailers_join_here" href="{$smarty.const.SOC_HTTP_HOST}registration.php" style="position:absolute; right:5px; top:5px;"><img src="{$smarty.const.SOC_HTTP_HOST}skin/red/images/foodwine/retailers-join-here.jpg" alt="retailers join here" /></a>
</div>
<div class="clear"></div>
<div id="paging" style="text-align:left;width:735px;background:#{$req.template.bgcolor}; padding-right:10px;">Is this your business? <a href="{$smarty.const.SOC_HTTP_HOST}registration.php">Sign up now</a></div>
