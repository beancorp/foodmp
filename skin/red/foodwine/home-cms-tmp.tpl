<script language="Javascript1.2">
	function goUrl(bcategory)
	{
		var search_state_name = document.getElementById("state_name_id").value;
		var suburb = document.getElementById("suburb_id").value;
		
		location.href = "/foodwine/index.php?cp=search&bcategory=" + bcategory + "&e4c387b8cf9f=1&search_state_name=" + search_state_name + "&suburb=" + suburb;
	}
</script>
<div id="_cms_foodwine_home" style="margin-top:1px;">
<div><img width="542" height="263" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/food-wine-logo.jpg" alt="" />
<h1 style=" font-size:24px; font-weight:bold; color:#463c8e; margin:10px 0 0;">Food Marketplace</h1>
<label style="line-height:24px;margin:0;font-size:24px; color:#463c8e;">...where retailers and consumers connect</label>
<div style="top:7px; right:2px; position:relative;" class="view_more"><a title="View more" href="/soc.php?cp=foodwine">View more</a></div>
</div>
<div class="clear">&nbsp;</div>
<div class="category">
<h2 style="font-size:20px; margin:0.6em 0;">Select a category</h2>
<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr style="height:36px;">
            <td width="30" class="line"><img width="29" height="25" title="Bakery &amp; Grocery" alt="Bakery &amp; Grocery" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/bakery.jpg" /></td>
            <td width="220" class="line"><a title="Bakery &amp; Grocery" onClick="goUrl(3)" href="javascript:void(0);">Bakery &amp; Grocery</a></td>
            <td width="30">&nbsp;</td>
            <td width="30" class="line"><img width="29" height="31" title="Restaurants" alt="Restaurants" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/restaurants.jpg" /></td>
            <td width="220" class="line"><a title="Restaurants" onClick="goUrl(1)" href="javascript:void(0);">Restaurants</a></td>
        </tr>
        <tr style="height:36px;">
            <td width="30" class="line"><img width="29" height="28" title="Fruits &amp; Vegetables" alt="Fruits &amp; Vegetables" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/fruits.jpg" /></td>
            <td width="220" class="line"><a title="Fruits &amp; Vegetables" onClick="goUrl(6)" href="javascript:void(0);">Fruits &amp; Vegetables</a></td>
            <td width="30">&nbsp;</td>
            <td width="30" class="line"><img width="30" height="28" title="Pubs &amp; Bars" alt="Pubs &amp; Bars" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/pubs_bars.jpg" /></td>
            <td width="220" class="line"><a title="Pubs &amp; Bars" onClick="goUrl(7)" href="javascript:void(0);">Pubs &amp; Bars</a></td>
        </tr>
        <tr style="height:36px;">
            <td width="30" class="line"><img width="28" height="25" title="Meat &amp; Deli" alt="Meat &amp; Deli" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/meat_deli.jpg" /></td>
            <td width="220" class="line"><a title="Meat &amp; Deli" onClick="goUrl(5)" href="javascript:void(0);">Meat &amp; Deli</a></td>
            <td width="30">&nbsp;</td>
            <td width="30" class="line"><img width="30" height="30" title="Fast Food" alt="Fast Food" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/fast_food.jpg" /></td>
            <td width="220" class="line"><a title="Fast Food" onClick="goUrl(8)" href="javascript:void(0);">Fast Food</a></td>
        </tr>
        <tr style="height:36px;">
            <td width="30" class="line"><img width="29" height="23" title="Seafood" alt="Seafood" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/seafood.jpg" /></td>
            <td width="220" class="line"><a title="Seafood" onClick="goUrl(4)" href="javascript:void(0);">Seafood</a></td>
            <td width="30">&nbsp;</td>
            <td width="30" class="line"><img width="30" height="27" title="Cafes" alt="Cafes" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/cafes.jpg" /></td>
            <td width="220" class="line"><a title="Cafes" onClick="goUrl(9)" href="javascript:void(0);">Cafes</a></td>
        </tr>
        <tr style="height:36px;">
            <td width="30" class="line"><img width="30" height="29" title="Liquor" alt="Liquor" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/liquor.jpg" /></td>
            <td width="220" class="line"><a title="Liquor" onClick="goUrl(2)" href="javascript:void(0);">Liquor Stores</a></td>
            <td width="30">&nbsp;</td>
            <td width="30" class="line"><img width="29" height="27" title="Juice Bars" alt="Juice Bars" src="{$smarty.const.IMAGES_URL}/skin/red/foodwine/category_icon/juice.jpg" /></td>
            <td width="220" class="line"><a title="Juice Bars" onClick="goUrl(10)" href="javascript:void(0);">Juice Bars</a></td>
        </tr>
    </tbody>
</table>
</div>
</div>
<p><style type="text/css">
	#_cms_foodwine_home a { text-decoration:none; color:#3a3a3a;}
	#_cms_foodwine_home ul{ list-style:none; margin:0; padding:0; margin-top:15px;}
	#_cms_foodwine_home ul li {float:left; font-size:12px; color:#000000; font-weight:normal; margin:0 0 13px 0; background:url(/skin/red/images/foodwine/home_yd.jpg) no-repeat left 4px; padding-left:10px; width:260px;}
	#_cms_foodwine_home ul.right li{width:230px; float:left; text-align:left; margin: 0 0 10px;}

	#_cms_foodwine_home .view_more {float:right; background:url(/skin/red/images/foodwine/home_arrow.jpg) no-repeat left center; padding-left:10px;}
	#_cms_foodwine_home .category table tr{ height:40px;}
	#_cms_foodwine_home .category table tr td.line{ border:none; border-bottom-style:solid; border-bottom-width:1px; border-bottom-color:#e5e5e5;}
	#_cms_foodwine_home .category table  a{ margin-left:10px; font-size:14px; color:#3a3a3a;}
	#sidebar2{ padding:10px 13px 17px 10px;}
</style></p>