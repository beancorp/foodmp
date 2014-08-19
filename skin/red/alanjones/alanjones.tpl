<script src="/skin/red/js/jquery.jplayer.min.js"></script>
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
					mp3: "{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}skin/red/alanjones/audio1.mp3"
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
	
	
	var myPlayer2 = $("#jquery_jplayer_2"),
		myPlayerData2,
		fixFlash_mp42,
		fixFlash_mp4_id2,
		ignore_timeupdate2,
		options2 = {
			ready: function (event) {
				fixFlash_mp42 = event.jPlayer.flash.used && /m4a|m4v/.test(event.jPlayer.options.supplied);
				$(this).jPlayer("setMedia", {
					mp3: "{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}skin/red/alanjones/audio2.mp3"
				});
			},
			timeupdate: function(event) {
				if(!ignore_timeupdate2) {
					myControl2.progress.slider("value", event.jPlayer.status.currentPercentAbsolute);
				}
			},
			swfPath: "/skin/red/js/",
			supplied: "mp3",
			cssSelectorAncestor: "#jp_container_2",
			wmode: "window",
			keyEnabled: true
		},
		myControl2 = {
			progress: $(options2.cssSelectorAncestor + " .jp-progress-slider")
		};
		
	myPlayer2.jPlayer(options2);

	myPlayerData2 = myPlayer2.data("jPlayer");

	myControl2.progress.slider({
		animate: "fast",
		max: 100,
		range: "min",
		step: 0.1,
		value : 0,
		slide: function(event, ui) {
			var sp = myPlayerData2.status.seekPercent;
			if(sp > 0) {
				if(fixFlash_mp4) {
					ignore_timeupdate2 = true;
					clearTimeout(fixFlash_mp4_id2);
					fixFlash_mp4_id2 = setTimeout(function() {
						ignore_timeupdate2 = false;
					},1000);
				}
				myPlayer2.jPlayer("playHead", ui.value * (100 / sp));
			} else {
				setTimeout(function() {
					myControl2.progress.slider("value", 0);
				}, 0);
			}
		}
	});
	
	var myPlayer3 = $("#jquery_jplayer_3"),
		myPlayerData3,
		fixFlash_mp43,
		fixFlash_mp4_id3,
		ignore_timeupdate3,
		options3 = {
			ready: function (event) {
				fixFlash_mp43 = event.jPlayer.flash.used && /m4a|m4v/.test(event.jPlayer.options.supplied);
				$(this).jPlayer("setMedia", {
					mp3: "{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}skin/red/alanjones/audio3.mp3"
				});
			},
			timeupdate: function(event) {
				if(!ignore_timeupdate3) {
					myControl3.progress.slider("value", event.jPlayer.status.currentPercentAbsolute);
				}
			},
			swfPath: "/skin/red/js/",
			supplied: "mp3",
			cssSelectorAncestor: "#jp_container_3",
			wmode: "window",
			keyEnabled: true
		},
		myControl3 = {
			progress: $(options3.cssSelectorAncestor + " .jp-progress-slider")
		};
		
	myPlayer3.jPlayer(options3);

	myPlayerData3 = myPlayer3.data("jPlayer");

	myControl3.progress.slider({
		animate: "fast",
		max: 100,
		range: "min",
		step: 0.1,
		value : 0,
		slide: function(event, ui) {
			var sp = myPlayerData3.status.seekPercent;
			if(sp > 0) {
				if(fixFlash_mp4) {
					ignore_timeupdate3 = true;
					clearTimeout(fixFlash_mp4_id3);
					fixFlash_mp4_id3 = setTimeout(function() {
						ignore_timeupdate3 = false;
					},1000);
				}
				myPlayer3.jPlayer("playHead", ui.value * (100 / sp));
			} else {
				setTimeout(function() {
					myControl3.progress.slider("value", 0);
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
</style>
<style>
	#alanjones_container {
		margin-left: auto;
		margin-right: auto;
		width: 531px;
	}
	#alanjones_image {
		width: 531px;
		height: 291px;
	}
	#alanjones_box {
		background-color: #F6F6F6;
		border-bottom: 1px solid #D5D5D5;
		border-bottom-left-radius: 10px;
		border-bottom-right-radius: 10px;
		border-left: 1px solid #D5D5D5;
		border-right: 1px solid #D5D5D5;
		margin-bottom: 10px;
		margin-left: 1px;
		margin-right: 1px;
		overflow: hidden;
		padding-bottom: 10px;
	}
	#image_caption {
		color: #848484;
		font-size: 8pt;
		padding-right: 10px;
		padding-top: 5px;
		text-align: right;
	}
	#words_of_wisdom {
		clear: left;
		float: left;
		margin-top: 10px;
		margin-left: 10px;
		width: 180px;
	}
	#sound_player {
		float: right;
		margin-top: 15px;
		text-align: left;
		width: 300px;
	}
</style>
{/literal}
<div id="alanjones_container">
	<div id="alanjones_image"><img src="/skin/red/alanjones/alanjones.jpg" /></div>
	<div id="alanjones_box">
		<div id="image_caption">Photo taken by Eva Rinaldi, Celebrity and Live Music Photographer <br /> Creative Commons Attribution Share-Alike 2.0 Generic</div>
		<div id="words_of_wisdom">
			<img src="/skin/red/alanjones/words.jpg" />
		</div>
		<div id="sound_player">
			<div id="jquery_jplayer_1" class="jp-jplayer"></div>
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
			<br /><br />
			<div id="jquery_jplayer_2" class="jp-jplayer"></div>
			<div id="jp_container_2">
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
			<br /><br />
			<div id="jquery_jplayer_3" class="jp-jplayer"></div>
			<div id="jp_container_3">
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
		</div>
	</div>
</div>