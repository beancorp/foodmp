{if !isset($req.full)}

{literal}

<script type="text/javascript" src="/skin/red/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/skin/red/js/niftyplayer.js"></script>
<link type="text/css" href="/skin/red/css/race.css" rel="stylesheet" media="screen" />

<script language="javascript" type="text/javascript">
var cisplay = false;
var t1 = null;

$(function(){	
	$(".ul-title a").each(function(){
		$(this).click(function(){
			style_class = $(this).attr('class');
			index = $(this).attr('index');
			tab_display = '';
			
			if(style_class == 'tab_opt_extend') {
				tab_display = "hide";
				$(this).attr('class', 'tab_opt_contract');
			} else {
				tab_display = 'show';
				$(this).attr('class', 'tab_opt_extend');
			}
			$("#content_" + index).animate({ height: tab_display, opacity: tab_display }, 'slow');
		});
	});
});
	function pagefunc(p){
		$.post("/soc.php?cp=race",{full:"1",p:p},function(data){$('#content_2').html(data);});
	}
	
	function replay(){
	  try{
		if(niftyplayer('niftyPlayer1').getState()=='finished'){
		   niftyplayer('niftyPlayer1').play();
			$("#sound").removeClass("close");
		   cisplay = true;
		}  
	  }catch(ex){}
	} 

	function myPlaySound(){
		if(cisplay){
			niftyplayer('niftyPlayer1').pause();
			cisplay = false;
			$("#sound").addClass("close");
			window.clearInterval(t1);
		}else{
			niftyplayer('niftyPlayer1').play();
			cisplay = true;
			$("#sound").removeClass("close");
			t1 = window.setInterval("replay()",500); 
		}
		
	}
	
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script>
{/literal}

<div class="soc-scoreboard">
	<div class="soc-score-bg-img">
<div class="soc-scoreboard-bg you-decide">
	<div class="block-decide">
		<div class="block-title">
			<a id="sound" class="sound close" href="javascript:void(0);" onclick="javascript:myPlaySound();"></a>        
		</div>
        <object style="position:absolute;left:-9999px;" id="niftyPlayer1" width="1" align="" height="1" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
      <param value="/skin/red/js/niftyplayer.swf?file=/upload/media/soc_race_sound.mp3&as=0" name="movie">
      <param value="high" name="quality">
      <param value="#FFFFFF" name="bgcolor">
      <embed width="165" align="" height="37" pluginspage="http://www.macromedia.com/go/getflashplayer" swliveconnect="true" type="application/x-shockwave-flash" name="niftyPlayer1" bgcolor="#FFFFFF" quality="high" src="/skin/red/js/niftyplayer.swf?file=/upload/media/soc_race_sound.mp3&as=0">
  </object>
		<div class="block-content">
			<button onclick="javascript:location.href='/soc.php?act=signon'"><span><span></span></span></button>
		</div>
	</div>
<div class="soc-content">
	<div class="soc-top"></div>
	<div class="soc-bottom"></div>
<div id="refcontent">
	<ul class="block-cash">
	<li style="*min-height:54px;">
    {$req.cms_promotion.aboutPage}
    </li>
	<a name="list"></a>
    <li style="*min-height:54px;">
    {$req.cms_content_title.aboutPage}
	<div id="content_2"  class="ul-content ul-content-2" style="display:none">
{/if}
<div class="mainlist-bg">
	<div class="mainlist-top"></div>
	<div class="clear"></div>
	<ul class="mainlist">
	<li>
		<ul class="listhead">
			<li class="li-rank">Rank</li>
			<li class="li-name">Nickname</li>
			<li class="li-suburb">Suburb</li>
			<li class="li-state">State</li>
			<li class="li-total">Total Points Score</li>
		</ul>
	</li>
	{if $req.list}
		{foreach from=$req.list item=l key=k}
		<li class="li-bg{if $l.rank > 3 && $l.rank <= 7 }{if $k % 2 == 0}5{else}4{/if}{else}{$l.rank}{/if}"><ul class="list">
				<li class="li-rank">{$l.rank}{if $l.rank eq 1}st{elseif $l.rank eq 2}nd{elseif $l.rank eq 3}rd{else}th{/if}</li>
				<li class="li-name" title="{$l.bu_nickname}"><a target="_blank" href="/{$l.bu_urlstring}" title="{$l.bu_nickname}">{$l.bu_nickname|truncate:42:"..."}</a></li>
				<li class="li-suburb">{$l.bu_suburb}</li>
				<li class="li-state">{$l.stateName}</li>
				<li class="li-total">{$l.total_points}</li>
			</ul>
		</li>
		{/foreach}
	{else}
		<li><ul class="list">
				<li  class="li-none">No Records</li>
			</ul>
		</li>
	{/if}
    {if $req.last_p || $req.next_p}
    <li class="li-bg19 race-pagelist"><ul class="list">
				<li class="li-rank">{if $req.last_p}<a href="#list" onClick="javascript:pagefunc({$req.last_p});"><img class="prev" src="/skin/red/images/race/prev-img.jpg"/></a>&nbsp;{/if}</li>
				<li class="li-left">&nbsp;</li>
				<li class="li-desc">Listed above are the rankings of the TOP 60 place getters in Real Time.</li>
				<li class="li-total">{if $req.next_p && $req.next_p <= 3}<a href="#list" onClick="javascript:pagefunc({$req.next_p});"><img src="/skin/red/images/race/next-im.jpg" onClick="javascript:pagefunc({$req.next_p});"/></a>&nbsp;{/if}</li>
			</ul>
		</li>
    {/if}
	</ul>
	  <div class="clear"></div>
	</div>
	<div class="clear"></div>
    
{if !isset($req.full)}
    </div>
    </li>
    <li>
    {$req.cms_rules.aboutPage}
    </li>
    <li>
    {$req.cms_earn_points.aboutPage}
    </li>
    <li class="li-button-opt">
    <span class="float_l"><fb:like href="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=race" send="false" layout="button_count" width="20" show_faces="true" font="arial"></fb:like></span>
     <span class="float_l"><a href="https://twitter.com/share" class="twitter-share-button" data-url="{$smarty.const.SOC_HTTP_HOST}soc.php?cp=race" data-via="Food Marketplace" data-lang="en">Tweet</a></span>
    </li>
	</ul>
	<div style="clear:left;"></div>	
</div>

</div>
</div>
</div>
</div>
{/if}