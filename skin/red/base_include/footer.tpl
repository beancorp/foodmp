
{if !$hidefootercat}
<div id="div_footer">

	<div id="footer_top"></div>
	
<div style="clear: both;"></div>

<div class="footer_category">
<div style="" id="footer_category_list">
	<div class="footer_category_foodwine">	
        <ul class="col1">
			
				<li>
					<ul>
						{foreach from=$lang.seller.attribute.5.subattrib item=cate key=k}
								<li class="col_{$k}"><a href="{$smarty.const.SOC_HTTP_HOST}foodwine/index.php?cp=search&bcategory={$k}&e4c387b8cf9f=1&search_state_name=-1" title="{$cate}">{$cate}</a></li>
						{/foreach}
					</ul>
				</li>
		</ul>
	</div>	
</div>

</div>
</div>
{/if}


<div style="clear: both;"></div>
{literal}
<div id="footer_link_container" style="background-color:#e3e3e3; margin-left:auto;margin-right:auto; margin-bottom: -20px; padding-bottom:20px;">
{/literal}
	<div>
		<ul id="footerlinks" style="width:950px;">
			<li class="first"><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=contact">Contact Us</a></li>
			<li>|</li>
			<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=terms">Terms of use</a></li>
			<li>|</li>
			<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=privacy">Privacy policy</a></li>
			<li>|</li>
			<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=fees">Selling Fees</a></li>
			<li>|</li>
			<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=ads">Advertisers</a></li>
			<li>|</li>
			<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=thanks">Acknowledgements</a></li>
			<li>|</li>
			<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=faq">FAQ</a></li>
			<li>|</li>
			<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=protection">Buyer Protection</a></li>
			<li>|</li>
			<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=payments">Payment Methods</a></li>
			<li>|</li>
			<li><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=sitemap">Site Map</a></li>
			<li style="margin-top:0; margin-bottom: 0; width:950px; display:block">
				<div id="div_cr">
					<div id="copyright" style=" margin-top:0; "><a href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=copyright" style="color:#999999;">&copy; 2007-2013 {$smarty.const.SITENAME} Patent / IP Statement&nbsp;&nbsp;<strong style="color:#999999; font-weight:bold;">Flat Rate Freedom™</strong></a></div>
				</div>
			</li>
			<li style="margin-top: 5px; width:950px; font-size: 9px; color: #000; display:block;">
				Please note that the picture you submit becomes the property of Food Marketplace, and that by your submission, you warrant that (i) the picture is your original work and will not violate the rights of any third party, and (ii) you have obtained all releases and permissions necessary of our use. Your submission also permits Food Marketplace use of the picture on our website, and in any of our promotional materials.
			</li>
			<li style="margin-top: 5px; width:950px; text-align: center; font-size: 11px;">FoodMarketplace: Proudly helping Local Food &amp Wine Retailers Online by helping retailers and consumers to connect</li>
			<div style="clear: both;"></div>
		</ul>
	</div>
</div>

{literal}
<script type="text/javascript"> 
function showMeta() 
{ 
  var metas = document.getElementsByTagName("meta"); 
  metas[0].name ="" 
  metas[0].content=""
} 
</script>
{/literal}
{if $req.has_calender}
<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.php" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
{/if}