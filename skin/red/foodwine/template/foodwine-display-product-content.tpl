<h2 style="background:#{$templateInfo.bgcolor};">Item Detail&nbsp; <a style="{if $req.info.foodwine_type eq 'food'}{if $req.info.sold_status}margin-right:80px;{/if}{/if}" href="javascript: history.go(-1)">Back</a>{if $req.info.foodwine_type eq 'food'}{if $req.info.sold_status}&nbsp;<a id="show_basket_link" style="float:right" class="show-basket" href="/foodwine/?act=basket">Show basket</a>{/if}{/if}</h2>

{foreach from=$req.items.product item=l}  
	<div id="item-details" class="item-details">

		<div class="item-specs">
			<ul style="margin:0px; padding:0px;">
				<li style="padding-bottom:20px;">
                <div style="float:right; margin-right:5px; width:270px; display:inline;">
			{if $l.images.uploadCount > 0}	
			<div align="center"><a href="javascript:popSliding('{$l.StoreID}','{$l.pid}','{$l.images.mainImage.0.sname.text}');"><img border="0" src="{$l.images.mainImage.0.sname.text}" width="267" height="201" /></a></div>
            {else}
				<div align="center"><img src="{$smarty.const.SOC_HTTP_HOST}template_images/products/{$req.info.subAttrib}.jpg" /></div>
			{/if}
			<div style="clear:both"></div>
            {if $l.images.uploadCount > 0}
			  <div id="thumbwrapper" style="position:relative; height:auto;{if $l.imagesCount < 10}border:hidden;border-color:#FFF{/if}">
			  {if $l.images.imagesCount > 4}
			  	<div style="position:absolute; top:32px; z-index:999; left:-3px;"><a href="Javascript:;" onmouseover="Move_right(); return false;" onmouseout="Move_stop();"><img src="/skin/red/images/left.gif"  /></a></div>
				<div style="position:absolute; top:32px; left:226px; z-index:999;"><a href="Javascript:;" onmouseover="Move_left(); return false;" onmouseout="Move_stop();"><img src="/skin/red/images/right.gif" /></a></div>
				{/if}
				<div id="scroll_wrap">
				 <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						  <tr>
							<td id="scroll_item1">
						<table border="0" cellspacing="3" cellpadding="0" align="center" style='width:100%;+width:auto;'>
							<tr>
							{foreach from=$l.images.subImage item=il}
							{if $il.sname.text neq '/images/79x79.jpg'}
							<td width="76" align="center"><a href="javascript:popSliding('{$l.StoreID}','{$l.pid}','{$il.sname.text}')"><img border="0" src="{$il.sname.text}" width="76" /></a></td>
							{/if}
							{/foreach}
							</tr>
					</table></td>
					<td id="scroll_item2"></td>
          </tr>
		  
        </table>
		</div>
			  </div>
			{/if}

			</div>
			<div ><h1 class="itemTitle" style="width: auto; display: inline;margin:0; font-weight:bold; clear:left; font-size:16px;font-weight:701;color:#392F7E">{$l.item_name}</h1></div>
            
            <div style="float:left;padding-top: 10px; width:220px; padding-bottom:20px;">
            <fb:like href="{$smarty.const.SOC_HTTP_HOST}{if $l.is_auction=='yes'}soc.php?cp=disauction&StoreID={$l.StoreID}&proid={$l.pid}{else}{$req.info.url_bu_name}/{$l.url_item_name}{/if}" send="false" width="220" show_faces="true" font="arial"></fb:like>
            </div>
			
            <div class="clear"></div>
            <p style="padding:0; margin:0;">&nbsp;</p>
            {if $l.p_code}
            <p><span style="width:97px; display:block">Product Code:</span>{$l.p_code}&nbsp;</p>
            {/if}
            {if $req.info.foodwine_type eq 'food' && 0}
            <p><span style="width:97px; display:block">In Stock Quantity:</span>{$l.stock_quantity}</p>
            {/if}     
            {if $l.price neq '0.00'}   
            <p style="font-weight:bold">    
            <span style=" font-weight:bold; font-size:14px;width:50px; display:block">Price:</span>
            	{if $l.priceorder eq 1}
                	{$l.unit} ${$l.price}
                {else}
               		${$l.price} {$l.unit}
                {/if}
            </p>
            {/if}
            <p style="padding:0; margin:0;">&nbsp;</p>
            {if $req.info.foodwine_type eq 'food'}
                 {if $req.info.sold_status}
            <p style="padding:0; margin:0;"><a href="javascript:void(0);" title="Add to basket" pid="{$l.pid}" name="add_to_basket_btn" reurl="{$smarty.const.SOC_HTTP_HOST}{if $l.is_auction=='yes'}soc.php?cp=disauction&StoreID={$l.StoreID}&proid={$l.pid}{else}{$req.info.url_bu_name}/{$l.url_item_name}{/if}"><img src="/skin/red/images/foodwine/add-to-basket.jpg"></a></p>
                 {/if}
            {else}
            <p style="padding:0; margin:0;"><a href="/soc.php?cp=bookonline&StoreID={$req.info.StoreID}" title="Book Online"><img src="/skin/red/images/foodwine/menu-bookonline.jpg" /></a></p>
            {/if}
			<input type="hidden" value="1" maxlength="5" id="nums_{$l.pid}" />
            <div class="clear"></div>            
			</li>
			</ul>
		</div>
   	</div>
    {if $l.youtubevideo neq ""&& $sellerhome neq "1"}
    <div style="width:490px; height:auto;">
    <object width="490" height="300">
        <param name="movie" value="{$l.youtubevideo}"></param>
        <param name="allowFullScreen" value="true"></param>
        <param name="allowscriptaccess" value="always"></param>
        <param name="wmode" value="transparent" />
        <embed src="{$l.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="490" height="300" wmode="transparent"></embed>
    </object>
    </div>
    <br/>
    {/if}
	<div class="TextFS14 TextJustify" style=" padding:0; float:left; width:498px;">{$l.description}</div>
{/foreach}

<script type="text/javascript">
var speed = 30;
var scroll_wrap=document.getElementById("scroll_wrap");
var scroll_item1=document.getElementById("scroll_item1");
var scroll_item2=document.getElementById("scroll_item2");
{if $l.images.imagesCount > 4}
scroll_item2.innerHTML = scroll_item1.innerHTML;
{/if}

{literal}
function Marquee_left(){
    if(scroll_item2.offsetWidth-scroll_wrap.scrollLeft<=0)
        scroll_wrap.scrollLeft-=scroll_item1.offsetWidth
    else{
        scroll_wrap.scrollLeft = scroll_wrap.scrollLeft + 5;
    }
}

function Marquee_right(){
    if(scroll_wrap.scrollLeft<=0)
        scroll_wrap.scrollLeft+=scroll_item1.offsetWidth
    else{
        scroll_wrap.scrollLeft = scroll_wrap.scrollLeft - 5;
    }
}
var Myaction;
//var Myaction = setInterval(Marquee_left,speed);
//var MyMar_right=setInterval(Marquee_right,speed);
function Move_left() {
    clearInterval(Myaction);
    Myaction = setInterval(Marquee_left,speed);
}
function Move_right() {
    clearInterval(Myaction);
    Myaction=setInterval(Marquee_right,speed);
}
function Move_stop() {
    clearInterval(Myaction);
}
{/literal}
</script>
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
				var reurl = $(this).attr('reurl');
				
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
							alert("The product does not exists.");
						} else if(data == 2) {
							alert("The stock is null.");
						}  else if(data == 3) {
							alert("You have fill in the max stock.");
						} else if(data == 4) {
							//alert("You need to be a member of \"SOC Exchange\" to use this service. Register now, it's FREE.");
							location.href="/soc.php?cp=login&from=buy&reurl=" + reurl;
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
<span class="price"></span>