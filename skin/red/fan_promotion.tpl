<script src="/skin/red/js/jquery.jplayer.min.js"></script>
<img src="/images/fan_banner.png" /> <br />
<style>
{literal}

#fan_promotion_content * {
	color: #000000 !important;
}

#fan_promotion_content h1 {
	display: inline-block !important;
    font-size: 14px !important;
    font-weight: bold;
    text-align: center;
    width: 100% !important;
    word-spacing: 3px;
	margin-bottom: 5px;
}

#fan_promotion_content h2 {
	display: inline-block;
    font-size: 12px !important;
    font-style: italic;
    font-weight: normal !important;
    margin-top: 0;
    text-align: center;
    word-spacing: 1px;
	margin-bottom: 0;
}

#fan_promotion_content p {
	font-size: 14px !important;
}

#star_icon {
	vertical-align: text-top;
}

#terms_n_conds a {
	font-size: 11px !important;
}

{/literal}
</style>

{if $smarty.const.SHOW_ALAN_JONES_BUTTON}
{literal}
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	var myPlayer = $("#jquery_jplayer_1"),
		myPlayerData,
		fixFlash_mp4,
		fixFlash_mp4_id,
		ignore_timeupdate,
		options = {
			ready: function (event) {
				fixFlash_mp4 = event.jPlayer.flash.used && /m4a|m4v/.test(event.jPlayer.options.supplied);
				$(this).jPlayer("setMedia", {
					mp3: "{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}fan_promotion.mp3"
				});
			},
			timeupdate: function(event) {
				if(!ignore_timeupdate) {
					myControl.progress.slider("value", event.jPlayer.status.currentPercentAbsolute);
				}
			},
			swfPath: "/skin/red/js/",
			supplied: "mp3",
			cssSelectorAncestor: "#jp_container_1",
			wmode: "window",
			keyEnabled: true
		},
		myControl = {
			progress: $(options.cssSelectorAncestor + " .jp-progress-slider")
		};
		
	myPlayer.jPlayer(options);

	myPlayerData = myPlayer.data("jPlayer");

	$('.jp-gui ul li').hover(
		function() { $(this).addClass('ui-state-hover'); },
		function() { $(this).removeClass('ui-state-hover'); }
	);

	myControl.progress.slider({
		animate: "fast",
		max: 100,
		range: "min",
		step: 0.1,
		value : 0,
		slide: function(event, ui) {
			var sp = myPlayerData.status.seekPercent;
			if(sp > 0) {
				if(fixFlash_mp4) {
					ignore_timeupdate = true;
					clearTimeout(fixFlash_mp4_id);
					fixFlash_mp4_id = setTimeout(function() {
						ignore_timeupdate = false;
					},1000);
				}
				myPlayer.jPlayer("playHead", ui.value * (100 / sp));
			} else {
				setTimeout(function() {
					myControl.progress.slider("value", 0);
				}, 0);
			}
		}
	});
});
//]]>
</script>
<style>
<!--
.jp-gui {
	overflow: hidden;
    padding: 20px;
    position: relative;
	margin-right: 10px;
}
.jp-gui ul {
	margin:0;
	padding:0;
}
.jp-gui ul li {
	position:relative;
	float:left;
	list-style:none;
	margin:2px;
	padding:4px 0;
	cursor:pointer;
}
.jp-gui ul li a {
	margin:0 4px;
}

li.jp-pause,
.jp-no-solution {
	display:none;
}
.jp-progress-slider {
	position:absolute;
	top:28px;
	left:100px;
	width:150px;
}
.jp-progress-slider .ui-slider-handle {
	cursor:pointer;
}
.jp-current-time,
.jp-duration {
	cursor: default;
    font-size: 0.8em;
    left: 50px;
    position: absolute;
    top: 50px;
}
.jp-current-time {
	left:100px;
}
.jp-duration {
	right:266px;
}
.jp-gui.jp-no-volume .jp-duration {
	right:70px;
}
.jp-clearboth {
	clear:both;
}
-->
#jp_container_1 {
	width: 300px;
	display: inline-block;
	margin-left: 25px;
}
</style>
{/literal}
{/if}
<div id="fan_promotion_content">
	{$fan_promotion_content}
	{if $smarty.const.SHOW_ALAN_JONES_BUTTON}
	<img src="/images/alan_jones_button.jpg" />
	<div id="jp_container_1">
		<div class="jp-gui ui-widget ui-widget-content ui-corner-all">
			<ul>
				<li class="jp-play ui-state-default ui-corner-all"><a href="javascript:;" class="jp-play ui-icon ui-icon-play" tabindex="1" title="play">play</a></li>
				<li class="jp-pause ui-state-default ui-corner-all"><a href="javascript:;" class="jp-pause ui-icon ui-icon-pause" tabindex="1" title="pause">pause</a></li>
				<li class="jp-stop ui-state-default ui-corner-all"><a href="javascript:;" class="jp-stop ui-icon ui-icon-stop" tabindex="1" title="stop">stop</a></li>
			</ul>
			<div class="jp-progress-slider"></div>
			<div class="jp-current-time"></div>
			<div class="jp-duration"></div>
			<div class="jp-clearboth"></div>
		</div>
		<div class="jp-no-solution">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>
	<div id="jquery_jplayer_1" class="jp-jplayer"></div>
	<p id="terms_n_conds"><a href="{$smarty.const.HTTPS_HOST}harrisedgecliff">Harris Farm Markets Edgecliff is not eligible to participate in this promotion</a> <br />
	<a href="{$smarty.const.HTTP_HOST}soc.php?cp=terms">See Terms of Use</a></p>
	{/if}
</div>
