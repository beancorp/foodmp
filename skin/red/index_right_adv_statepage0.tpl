<div id="sidebar2" style="border:none; background:url(../images/advert-bottom.gif) no-repeat 10000px bottom;">
	<div id="random_banner_right">
	{foreach from=$statePageBanners item=banner key=k}
		{if ($k < 2) or ($k > 1 and $k%2==0)}
		<div style="width:160px; height:250px;" class="random_banner_right" {if $k >=17}id="right_banner_last"{/if}>{if $banner.banner_link}<a href="/adclick.php?id={$banner.banner_id}" title="{$banner.description}" target="_blank" >{/if}
		
		{if $banner.file_type eq 'image'}
			<img src="/upload/new/{$banner.banner_img}" width="160" height="250" border="0" alt="{$banner.description}" title="{$banner.description}" />
		{else}
		<div id="__Flash__{$k}__" replace_img="{$banner.replace_image}" title="{$banner.description}" >
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="160" height="250">
			<param name="movie" value="/upload/new/{$banner.banner_img}" />
			<param name="quality" value="high" />
			<embed src="/upload/new/{$banner.banner_img}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="160" height="250"></embed>
			</object>
		</div>
		{/if}
		
		{if $banner.banner_link}</a>{/if}</div>
		{/if}
	{/foreach}
	</div>
</div>

{literal}

	<script type="text/javascript">
		 function flashChecker()
{
    var hasFlashPlayer = false;

    if (window.navigator.userAgent.indexOf('MSIE') > -1) {
        if (new ActiveXObject('ShockwaveFlash.ShockwaveFlash')) {
            hasFlashPlayer = true;
        }
    } else {
        if (window.navigator.plugins['Shockwave Flash'] != null) {
            hasFlashPlayer = true;
        }
    }

    return hasFlashPlayer;
}

	$(function(){
		if(!flashChecker()) {
			$("div[id^=__Flash__]").each(function(o) {
				replaceImage = $(this).attr('replace_img');
				title = $(this).attr('title');
				if(replaceImage) {
					$(this).html('<img src="/upload/new/' + replaceImage + '" alt="' + title + '" title="' + title + '" width="160" height="250" border="0" />');
				}
		})
		}
	});
	</script>

{/literal}