
<script language="javascript">
{literal}
/*$(function() {
	$("#ctl_pn").click(function(){
		if ($("#ctl_pn").attr("src") == ("/skin/red/images/home/previous.gif")){
			$("#ctl_pn").attr("src", '/skin/red/images/home/next.gif');
			$("#right_menu").switchClass('dock-imgmenu', 'dock-textmenu', 1000);
			//$(".slimMenu").switchClass('slimMenu', 'fatMenu', 1000);
			//$(".hidelink").switchClass('hidelink', 'link', 1000);
		}else{
			$("#ctl_pn").attr("src", '/skin/red/images/home/previous.gif');
			$("#right_menu").switchClass('dock-textmenu', 'dock-imgmenu', 1000);
			//$(".link").switchClass('link', 'hidelink', 500);
			//$(".fatMenu").switchClass('fatMenu', 'slimMenu', 1000);
		}
		return false;	
	});
});*/

$(function() {
	$("#right_menu").mouseenter(moveOver);
});
$(function(){
	//$("#right_menu").mouseout(moveOut);
	$(".dock-bg").mouseleave(moveOut);
});

function moveOver(){
	$("#right_menu").unbind("mouseenter");
	$(".dock-bg").unbind("mouseleave");
	//if ($("#ctl_pn").attr("src") == '/skin/red/images/home/previous.gif'){
		$("#preview").hide();
		$(".view-passorder").css('z-index',1);
		$(".edit-stock-items").css('z-index',1);
		$("#right_menu").switchClass('dock-imgmenu', 'dock-textmenu', 500, function(){
			$("#ctl_pn").attr("src", '/skin/red/images/home/next.gif');
			$("#right_menu").mouseenter(moveOver);
			$(".dock-bg").mouseleave(moveOut);
		});
	//}
};
	
function moveOut(){
	$("#right_menu").unbind("mouseenter");
	$(".dock-bg").unbind("mouseleave");
	//if ($("#ctl_pn").attr("src") == '/skin/red/images/home/next.gif'){
		$("#right_menu").switchClass('dock-textmenu', 'dock-imgmenu', 500, function(){
			$("#ctl_pn").attr("src", '/skin/red/images/home/previous.gif');
			$("#preview").show();
			$(".view-passorder").css('z-index',1000);
			$(".edit-stock-items").css('z-index',1000);
			$("#right_menu").mouseenter(moveOver);
			$(".dock-bg").mouseleave(moveOut);
		});
	//}
};

windowOnload(function(){
	Nifty("div.buyeradminhomeinfo","big alias");
	Nifty("div.countinfo","small alias");
	$('div.marquee marquee').marquee('pointer').mouseover(function () {
		$(this).trigger('stop');
	}).mouseout(function () {
		$(this).trigger('start');
	});
});
{/literal}
</script>
<div class="dock-menu">
        	<div id="right_menu" class="dock-imgmenu">
            	<div class="dock-t"></div>
                <div class="dock-bg">
                	<div class="dock-arrow"><img id="ctl_pn" src="/skin/red/images/home/previous.gif" alt="" /></div>
                	<ul>
						{if $subscriber}
							<li class="vip"><a href="/soc.php?cp=subscriber">Subscriber</a></li>
						{/if}
                    	{if $smarty.session.attribute eq 5}
							{if $account_status eq 1}
								<li class="home"><a href="/{$req.website_name}">Admin home</a></li>
								<li class="edit"><a href="/registration.php?step=account">Edit my website</a></li>
							{/if}
                            <li class="email"><a href="/soc.php?cp=inbox">Email</a></li>
                            <li class="blog"><a href="/soc.php?cp=blog&StoreID={$smarty.session.StoreID}&pageid=1">My blog</a></li>
                            {if $req.foodwine_type eq 'food'}
                            <li class="order"><a href="/foodwine/?act=order&StoreID={$smarty.session.StoreID}">Online Orders</a>{if $req.new_online_order_num > 0}<span class="new-order" >{$req.new_online_order_num}</span>{/if}</li>
                            {else}
                            <li class="order"><a href="/foodwine/?act=book&StoreID={$smarty.session.StoreID}">Online Booking</a><span class="new-order">{if $req.new_online_book_num > 0}{$req.new_online_book_num}{/if}</span></li>
                            {/if}
                            <!--<li class="referrals"><a href="/soc.php?cp=ref_email">Referral Template</a></li>-->
                            <li class="flyers"><a href="/foodwine/?act=flyers">Flyers</a></li>
							{if $req.info.sold_status eq 1}
                            <li class="flyers"><a href="/foodwine/?act=clickandcollect"><span>Flyers <br />Click & Collect</span></a></li>
							<li class="flyers"><a href="/foodwine/?act=shoponline"><span>Flyers <br />Shop Online</span></a></li>
							{/if}
                            <li class="emailalerts"><a href="/foodwine/?act=emailalerts" style="color:red">SPECIALS</a></li>
                            <li class="announcements"><a href="/foodwine/?act=announcement">Announcements</a></li>
                            <li class="reviews"><a href="/soc.php?cp=disreview&StoreID={$smarty.session.StoreID}">Reviews</a></li>
                            <li class="recipes"><a href="/foodwine/?act=recipes">Recipes</a></li>
                         {elseif $smarty.session.attribute eq 2 || $smarty.session.attribute eq 1 || ($smarty.session.attribute eq 3 && $smarty.session.subAttrib neq 3)}
                         	<li class="home"><a href="/{$req.website_name}">Admin home</a></li>
                            <li class="edit"><a href="/soc.php?act=signon">Edit my website</a></li>
                            <li class="email"><a href="/soc.php?cp=inbox">Email</a></li>
                            <li class="blog"><a href="/soc.php?cp=blog&StoreID={$smarty.session.StoreID}&pageid=1">My blog</a></li>
                            <li class="wishlist"><a href="/soc.php?act=wishlist&step=1">My wishlist</a></li>
                            <li class="send"><a href="/soc.php?act=invitations">Send Invitations</a></li>
                            <li class="gallery"><a href="/soc.php?act=gallery">Photo gallery</a></li>
                            <li class="history"><a href="/soc.php?cp=purchase">Transaction history</a></li>
                            <!--<li class="referrals"><a href="/soc.php?cp=ref_email">Referral Template</a></li>-->
                            <li class="emailalerts"><a href="/soc.php?cp=inbox&opt=1" style="color:red">{if $smarty.session.attribute eq '3'&& $smarty.session.subAttrib eq '1'}Applicants{else}Responses{/if}</a></li>
                         {elseif $smarty.session.attribute eq 3 && $smarty.session.subAttrib eq 3}
                         	<li class="home"><a href="/{$req.website_name}">Admin home</a></li>
                            <li class="edit"><a href="/soc.php?act=signon">Edit my website</a></li>
                            <li class="email"><a href="/soc.php?cp=inbox">Email</a></li>
                            <li class="emailalerts" style="color:red"><a href="/soc.php?cp=inbox&opt=1" style="color:red">Responses</a></li>
                         {else}
                            <li class="home"><a href="/{$req.website_name}">Seller home</a></li>
							{if $free_signup}
								<li class="edit"><a href="/settings.php?step=1">Edit my website</a></li>
							{else}
								<li class="edit"><a href="/soc.php?act=signon">Edit my website</a></li>
							{/if}
                            
							
                            <li class="email"><a href="/soc.php?cp=inbox">Email</a></li>
                            <li class="blog"><a href="/soc.php?cp=blog&StoreID={$smarty.session.StoreID}&pageid=1">My blog</a></li>
                            <li class="wishlist"><a href="/soc.php?act=wishlist&step=1">My wishlist</a></li>
                            <li class="send"><a href="/soc.php?act=invitations">Send Invitations</a></li>
                            <li class="gallery"><a href="/soc.php?act=gallery">Photo gallery</a></li>
                            <li class="history"><a href="/soc.php?cp=purchase">Transaction history</a></li>
                            <!--<li class="referrals"><a href="/soc.php?cp=ref_email">Referral Template</a></li>-->
                        {/if}
                    </ul>

                </div>
                <div class="dock-b"></div>
            </div>
		</div>