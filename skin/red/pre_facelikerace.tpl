{literal}
<link type="text/css" href="/skin/red/css/race.css" rel="stylesheet" media="screen" />
{/literal}
<div class="soc-scoreboard">
	<div class="soc-score-bg-img">
<div class="soc-scoreboard-bg facebook">
<div class="soc-content">
	<div class="soc-top"></div>
	<div class="soc-bottom"></div>

<div id="refcontent">

	<div class="refcontent-title">
		<div class="ref-left">
			<h2>SOC Sprints</h2>
			<div class="text">
			<p>{$req.race_info.description}</p>
            </div>			
			<p>
			<button class="join-btn button" type="button" onclick="javascript:location.href='/soc.php?act=signon'"><span><span>Join the sprint!</span></span></button>
			<button class="button" type="button" onclick="javascript:location.href='/soc.php?cp=facelikerace'"><span><span>View Leader Board</span></span></button>
			<!--<input type="button" onclick="javascript:location.href='/soc.php?act=signon'" value="Join the sprint!" class="button">
			&nbsp;&nbsp;    
			<input type="button" onclick="javascript:location.href='/soc.php?cp=pre_facelikerace'" value="View Previous Winners" class="button">-->
			</p>
		</div>
    <div class="award"><img width="243" src="{$req.race_info.image}" /></div>
    <div class="clear"></div>
	</div>
	
    {$req.cms_pre_winners.aboutPage}
	
</div>



</div>
</div>
</div>
</div>

