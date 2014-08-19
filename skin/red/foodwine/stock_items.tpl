<style>
{literal}
@media only screen and (max-width: 767px), screen and (max-device-width: 720px) {
	#fan_button {
		top: 70px !important;
	}
}
{/literal}
</style>
<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
<div style="background:url(/skin/red/images/foodwine/search_panel_bg.jpg) repeat-x left top;width:100%;height:38px;border-bottom:1px solid #ccc;">
	<form name="foodwine_time_search" id="foodwine_time_search" action="/foodwine/index.php" method="get">
	<a name="top" style=" visibility:hidden;"></a>
		<div style="float:left;padding-top:3px;padding-left:5px;">
		<strong>Show</strong>&nbsp;&nbsp;
			<select name="category" class="select" onchange="this.form.submit();">
            	<option value="">All Categories</option>
		  		{foreach from=$categories item=c}                	
					<option value="{$c.id}" {if $c.id eq $req.info.category }selected="selected"{/if}>{$c.category_name}</option>				
				{/foreach}
			</select>
		</div>
        {if $req.info.sold_status eq 1 && $req.info.foodwine_type eq 'food'}
		<div style="float:right;padding-top:0; padding-right:5px;">
			<a href="/foodwine/?act=basket&StoreID={$req.info.StoreID}" title="Show basket">
				<img src="/skin/red/images/foodwine/show_basket.jpg" height="27" width="132" title="Show basket" alt="Show basket"/>
			</a>
		</div>
        {/if}
		<input type="hidden" name="act" value="product" />
		<input type="hidden" name="cp" value="list" />
		<input type="hidden" name="type" value="{$req.info.type}" />
		<input type="hidden" name="StoreID" value="{$req.info.StoreID}" />
		<input type="hidden" name="s" value="{$req.info.s}" />
	</form>
</div>

<div class="stock_items_list">
	<ul>
		{foreach from=$products item=p key=k}
			<li class="{if $k%2 eq 0}left{else}right{/if} {if $k<2}top{else}ntop{/if}" style="z-index:{php}echo 1000-$i;$i++;{/php};">
            	{if $p.small_image && $p.small_image neq '/images/243x212.jpg'}
				<div class="img">
					<a title="{$p.item_name}" href="/{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{if $p.small_image}{$p.small_image}{else}/images/80x58.jpg{/if}" width="{$p.limage.width}" height="{$p.limage.height}" alt="{$p.name}" title="{$p.name}" onmouseover="showmoreImage_fade('pid_{$p.pid}',true);" onmouseout="showmoreImage_fade('pid_{$p.pid}',false);"/></a>
		        <div id="pid_{$p.pid}" class="moreImg_css"><img src="{$p.big_image}" style="width:{$p.bimage.width}px;height:{$p.bimage.height}px;"/></div><div id="pid_{$p.pid}_2" class="moreImg_arror"></div>
				</div>
                {else}
				<div class="img"><a title="{$p.item_name}" href="{$smarty.const.SOC_HTTP_HOST}{$req.info.url_bu_name}/{$p.url_item_name}"><img src="{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg" width="100" alt="{$p.name}" title="{$p.name}"/></a>
				</div>
				{/if}
				<div class="desc">
					<div class="name"><a title="{$p.item_name}" href="/{$req.info.url_bu_name}/{$p.url_item_name}">{$p.item_name}</a></div>
                    {if $p.price neq '0.00'}
					<div class="price">
                    {if $p.priceorder eq 1}
                		{$p.unit} ${$p.price}
                    {else}
                        ${$p.price} {$p.unit}
                    {/if}
                    </div>
                    {/if}
				</div>
                {if $req.info.sold_status eq 1 && $req.info.foodwine_type eq 'food'}
                	{if $p.sale_state eq 'soon'}
                    	<div class="onsale-soon">
                    	<img border="0" src="/skin/red/images/bu-onsalesoon.gif">
                        </div>
                    {else}
                        <div class="add">
                            <img src="/skin/red/images/foodwine/add_to_basket.jpg" width="8" height="8" alt="Add to basket" title="Add to basket"/> <a href="javascript:void(0);" title="Add to basket" name="add_to_basket" panel_id="add_panel_{$k}">Add to basket</a>
                            <div class="add_panel" id="add_panel_{$k}">
                                <div class="add_to_basket">
                                    <img src="/skin/red/images/foodwine/add_to_basket.jpg" width="8" height="8" alt="Add to basket" title="Add to basket"/> <a href="javascript:void(0);" title="Add to basket">Add to basket</a>
                                </div>
                                <div class="clear"></div>
                                <div class="add_opera"><span style=" vertical-align:middle; float:left; padding-top:3px;">Qty</span><input type="text" value="1" maxlength="5" class="input_add" id="nums_{$p.pid}" /><a href="javascript:void(0);" title="Add to basket" style="vertical-align:bottom;" pid="{$p.pid}" name="add_to_basket_btn"><img src="/skin/red/images/foodwine/add_to_basket/add_btn.jpg" width="44" height="20" alt="Add to basket" title="Add to basket"/></a></div>
                            </div>
                        </div>
                    {/if}
                {/if}
			</li>
		{/foreach}
	</ul>
</div>
<div class="clear"></div>


<!--<div class="stock_items_bottom">
	<a href="#top" class="back_to_top" title="Back to top">Back to top</a>
</div>-->
<div id="paging" style="width:742px;background:#{$req.template.bgcolor};">{$linkStr}&nbsp;&nbsp;<a href="#top" class="back_to_top" title="Back to top">Back to top</a></div>

{literal}
	<script type="text/javascript">
		$(function(){
			$("a[name='add_to_basket']").hover(
				function(){
					pid = $(this).attr("panel_id");
					$("#" + pid + " input").val(1);		//reset input equal 1
					$("#" + pid).show();
				},
				function(){
					pid = $(this).attr("panel_id");
					$("#" + pid).hide();
				}
			);
			
			$("div[id^='add_panel_']").hover(
				function(){
					$(this).show();
				},
				function(){
					$(this).hide();
				}
			);
			
			//add to basket
			$("a[name='add_to_basket_btn']").click(function(){
				var pid = $(this).attr('pid');
				
				var qty = $("#nums_" + pid).val();
				if(qty<=0 || qty.search(/^\d+$/)) {
					alert('Qty is error.');
					return false;
				}
				
				$.ajax({
					url:'/foodwine/index.php?act=basket&cp=add&rand='+Math.random(),
					data:{"pid":pid, "qty":qty},
					dataType:'text',
					type:'POST',
					success:function(data) {
						if(data == 0) {
							alert("Item added to your basket successfully.");
						} else if(data == 1) {
							alert("The product does not exist.");
						} else if(data == 2) {
							alert("The stock is null.");
						}  else if(data == 3) {
							alert("You have fill in the max stock.");
						} else if(data == 4) {
							alert("You need to be a member of \"Food Marketplace\" to use this service. Register now, it's FREE.");
							location.href="/signup.php";
						}else if(data == 5) {
							if(window.confirm("You can not add product from different stores, do you want to empty your basket?")) {
								emptyBasket();
							}
						}else if(data == 6) {
							alert("The item will be on sale soon.");
						} else {
							alert("Failed to add to the basket.");
						}
					}					
				});
			});
		});
		
		function emptyBasket()
		{
			$.ajax({
					url:'/foodwine/index.php?act=basket&cp=empty&rand='+Math.random(),
					data:{"SN":'a2e4822a98337283e39f7b60acf85ec9'},
					dataType:'text',
					type:'POST',
					success:function(data) {
						if(data) {
							alert("Empty basket successfully, you can add this retailer's product to your basket now.");
						} else {
							alert("Empty basket failed.");
						} 
					}					
				});
		}
	</script>
{/literal}