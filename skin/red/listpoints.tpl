{literal}
<link type="text/css" href="/skin/red/css/race.css" rel="stylesheet" media="screen" />
<script type="text/ecmascript" language="javascript">
$(function(){
	$(".tabtmp li").each(function(i){
		$(this).mouseover(function(){
			j = 1 - i;
			$("#mainlist_" + i).show();
			$("#mainlist_" + j).hide();
			$("#tabtmp_" + i).addClass("active_tab");
			$("#tabtmp_" + j).removeClass("active_tab");
		});
		$(this).mouseout(function(){
			
		});
	});
});
</script>
{/literal}
<div class="soc-scoreboard">
	<div class="soc-score-bg-img">
<!--<a href="/soc.php?cp=sellerhome" class="soc-back-admin" id="map_backadmin"><img src="/skin/red/images/race/back-admin.jpg" /></a>-->

<a class="map_banner" id="map_banner" name="map_banner" href="/soc.php?cp=race" title="Go to leaderboard">
    <img border="0" src="/skin/red/images/race/scoreboard-banner.jpg" alt="Go to leaderboard">
</a>
<div class="soc-scoreboard-bg">
<div class="soc-content">
	<div class="soc-top"></div>
	<div class="soc-bottom"></div>
	<div class="race-seller-info">
	<h2><span>Welcome</span> <a class="nickname" href="javascript:void(0);">{$smarty.session.NickName|truncate:18:"..."}</a></h2>
	<div class="pagelist"><span class="fields"> Total Points Score (TPS)</span><span class="scores">{$req.pointinfo.total_points}&nbsp;</span><span class="fields"> Rank</span><span class="scores">{$req.pointinfo.rank}</span></div>
    <div class="clear"></div>
 </div>
	<div class="race-title">
	<h2>my soc race scoreboard</h2>
    <div class="clear"></div>
    <div class="text">
       <p>Every point counts. It's not a matter of who finishes the race fastest, it's a matter of who finishes the race with the</p>
       <p>highest Total Points Score. The way you accumulate points from the combination of Listings, Referrals and Bonus Points</p>
       <p>is the key. In the end, your combination may unlock the cash.</p>
       <p>Good Luck!</p>
    </div>	
    <div class="clear"></div>
 </div>

 	<ul class="tabtmp">
    	<li class="soc-points active_tab" id="tabtmp_0">SOC Listing / Referral Points</li>
        <li class="points" id="tabtmp_1">Bonus Points</li>
    </ul>

 
 <div class="mainlist-bg">
<ul class="mainlist" id="mainlist_0">

	<li>
		<ul class="listhead">
			<li class="nickname">Nickname</li>
			<li class="item-listed">Items Listed</li>
			<li class="points total-align-right">Points</li>
		</ul>
	</li>
        {if $req.pointinfo.spec && $req.pointinfo.spec.point > 0}
		<li><ul class="list">
				<li class="nickname" title="{$req.pointinfo.bu_nickname}"><a target="_blank" href="/{$req.pointinfo.bu_urlstring}" title="{$req.pointinfo.bu_nickname}">{$req.pointinfo.bu_nickname|truncate:62:"..."}</a></li>
				<li class="item-listed" title="{$rpl.item_name}">1</li>
				<li class="points total-align-right" title="{$rpl.bu_name}">{$req.pointinfo.spec.point}</li>
			</ul>
		</li>
        {/if}
        {if ($req.pointinfo.product_feetype eq 'year' && $req.pointinfo.total_items > 0 && $req.pointinfo.total_list_points > 0) || !($req.pointinfo.spec && $req.pointinfo.spec.point > 0) || $req.pointinfo.attribute eq 0 || $req.pointinfo.attribute eq 5}
		<li><ul class="list">
				<li class="nickname" title="{$req.pointinfo.bu_nickname}"><a target="_blank" href="/{$req.pointinfo.bu_urlstring}" title="{$req.pointinfo.bu_nickname}">{$req.pointinfo.bu_nickname|truncate:62:"..."}</a></li>
				<li class="item-listed" title="{$rpl.item_name}">{$req.pointinfo.total_items}</li>
				<li class="points total-align-right" title="{$rpl.bu_name}">{$req.pointinfo.total_list_points}</li>
			</ul>
		</li>
        {/if}
	<li>
		<ul class="list-top">
			<li class="nickname">Referral Nickname</li>
			<li class="ref-points">Referral Points</li>
			<li class="ref-Joined">Market Joined</li>
			<li class="ref-item-listed">Items Listed</li>
            <li class="points total-align-right">Points</li>
		</ul>
	</li>
    
	{if $req.pointinfo.ref_list}
		{foreach from=$req.pointinfo.ref_list item=l}
        {if $l.total_rp_points>0}
		<li>
		<ul class="list">
				<li class="nickname"><a target="_blank" href="/{$l.bu_urlstring}" title="{$l.pointinfo.bu_nickname}">{$l.bu_nickname}&nbsp;</a></li>
				<li class="ref-points">{$l.ref_points}</li>
				<li class="ref-Joined">{$l.market}</li>
				<li class="ref-item-listed">{$l.total_items}</li>
                <li class="points total-align-right">{$l.total_rp_points}</li>
			</ul>
		</li>
        {/if}
		{/foreach}
    {else}
		<li><ul class="list">
				<li class="li-none">No Records</li>
			</ul>
		</li>
	{/if}
	<li class="pagelist">
		Sub total <span class="sub">{$req.pointinfo.lp_points}</span>
	</li>
	</ul>
    <ul class="mainlist" id="mainlist_1" style="display:none">
	<li>
		<ul class="listhead">
			<li class="partner">Partner Site</li>
			<li class="answer">Answer</li>
            <li class="r-points total-align-right">Points</li>
		</ul>
	</li>
	{if $req.pointinfo.bp_list}
		{foreach from=$req.pointinfo.bp_list item=l}
		<li><ul class="list">
				<li class="partner" title="{$l.site_name}">{if $l.type eq '18'}Existing Member Bonus{else}{$l.site_name|truncate:62:"..."}{/if}&nbsp;</li>
				<li class="answer">{if $l.type neq '18'}<img src="/skin/red/images/race/{if $l.is_correct}reserve_icon.png{else}reserve_no_icon.png{/if}" />{/if}&nbsp;</li>
                <li class="r-points total-align-right">{$l.point}</li>
			</ul>
		</li>
		{/foreach}
    {else}
		<li><ul class="list">
				<li class="li-none">No Records</li>
			</ul>
		</li>
	{/if}
	<li class="pagelist">
		Sub total <span class="sub1 total-align-right">{$req.pointinfo.bp_points}</span>
	</li>
	<div class="clear"></div>	
	</ul>
	
	</div>
	<div style="clear:left;"></div>	
	<div class="clear"></div>	
	</div>
	</div>
	<div class="clear"></div>
	</div>
</div>