<div id="sidebar2" style="border:none; background:url(../images/advert-bottom.gif) no-repeat 10000px bottom;{if $foodwinehome}padding:10px 15px 17px 0;{/if}">
{if !$foodwinehome && ($state_name ne '' or $statename ne '')}  
<script language="javascript" src="/js/rotateAd.js" type="text/javascript"></script>
	<div id="sidebar_ad">
	<script type="text/javascript">
	var fadeimages=new Array()
	{if $req.imageAds.right1a.exists eq 'yes'}
		fadeimages[0]=["/{$req.imageAds.right1a.img_url}", "/adclick.php?id={$req.imageAds.right1a.id}", ""];
	{/if}
	{if $req.imageAds.right1b.exists eq 'yes'}
		fadeimages[1]=["/{$req.imageAds.right1b.img_url}", "/adclick.php?id={$req.imageAds.right1b.id}", ""];
	{/if}
	{if $req.imageAds.right1c.exists eq 'yes'}
		fadeimages[2]=["/{$req.imageAds.right1c.img_url}", "/adclick.php?id={$req.imageAds.right1c.id}", ""];
	{/if}
	{if $req.imageAds.right1a.exists eq 'yes' or $req.imageAds.right1b.exists eq 'yes' or $req.imageAds.right1c.exists eq 'yes'}
		var fadeobj = new fadeshow(fadeimages, 170, 270, 0, 7000, 1);
	{/if}
	</script>
    <!--<div id="sidebar_ad">-->
    {if $req.imageAds.right1.exists eq 'yes'}
	    <br><br>
		<a href="/adclick.php?id={$req.imageAds.right1.id}"><img src="/{$req.imageAds.right1.img_url}" width=170 height=270 border="0" /></a>
	{/if}
	{if $req.imageAds.right2.exists eq 'yes'}
	<br><br>
		<a href="/adclick.php?id={$req.imageAds.right2.id}"><img src="/{$req.imageAds.right2.img_url}" width=170 height=270 border="0" /></a>
	{/if}
	
	{if $req.imageAds.right3.exists eq 'yes'}
	<br><br>
		<a href="/adclick.php?id={$req.imageAds.right3.id}"><img src="/{$req.imageAds.right3.img_url}" width=170 height=270 border="0" /></a>
	{/if}
	 <!-- start left ads -->
	{if $req.imageAds.left1.exists eq 'yes'}
		<br><br>
		<a href="/adclick.php?id={$req.imageAds.left1.id}"><img src="/{$req.imageAds.left1.img_url}" width=170 height=270 border="0" /></a>
	{/if}	
	
	{if $req.imageAds.left2.exists eq 'yes'}
		<br><br>
		<a href="/adclick.php?id={$req.imageAds.left2.id}"><img src="/{$req.imageAds.left2.img_url}" width=170 height=270 border="0" /></a>
	{/if}
	<!-- end left ads -->
	<br><br>
	</div>
{else}    
	{if $sidebarContent ne ''}  
		{$sidebarContent}
		
	{else} 
		<!--
		<div style="margin:10px 0 0 0;">
        <map style="padding-top:10px;" id="map_foodwine_reg" name="map_foodwine_reg">
            <img src="/skin/red/images/banner/foodwine_singup.jpg" alt="" border="0" usemap="#map_foodwine_reg" />
            <area shape="rect" coords="16,230,136,262" href="/soc.php?act=signon&amp;attribute=5">
        </map>
        </div> 
		-->
		<div style="margin:10px 0 0 0;">
		
		{if $wishlistabout_right_image eq 'yes'}
		<a href="soc.php?cp=wishlistSample"><img src="/skin/red/images/wishlistabout_right_banner.png" height="206" width="192" alt="View Some Wish Lists" title="View Some Wish Lists"/></a>
		{elseif $home_page eq true or $foodwine_home eq true}
		<a href="{$soc_https_host}registration.php"><img alt="" src="{$soc_https_host}/skin/red/images/onedollaraday.jpg" /></a>
		{else}
        <!--<a href="/soc.php?cp=cms"><img src="/skin/red/images/banner/testimonial_banner.jpg" alt="Testimonial" title="Testimonial" border="0" /></a>-->
        {/if}
		
		</div>
		<br><p></p>
		<!--<a href="http://www.socexchange.com.au/TheSOCStore" target="_blank"><img src="/skin/red/images/congratulations.jpg" alt="" border="0"  /></a>-->
<map name="Map" id="Map">
<area shape="rect" coords="15,345,182,384" href="{$securt_url}soc.php?act=signon" />
<area shape="rect" coords="6,411,190,612" href="/soc.php?cp=mediaplay" target="_blank" />
</map>
<!--<map name="Map1" id="Map1">
<area shape="rect" coords="14,147,125,178" href="http://socexchange.com.au/thesocstore" />
</map>-->
	{/if}
{/if}
</div>