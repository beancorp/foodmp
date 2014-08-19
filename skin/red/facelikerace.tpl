{if !isset($req.full)}
{literal}
<link type="text/css" href="/skin/red/css/race.css" rel="stylesheet" media="screen" />
<script>
 
var SysSecond;
var InterValObj;
$(document).ready(function () {
	SysSecond = parseInt({/literal}{$req.race_info.lefttime}{literal});  
	InterValObj = window.setInterval(SetRemainTime, 1000);  
	
	$(".tab_opt_extend").each(function(){
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
 
function SetRemainTime() {
	if (SysSecond > 0) {
		SysSecond = SysSecond - 1;
		var second = Math.floor(SysSecond % 60);             //second    
		var minite = Math.floor((SysSecond / 60) % 60);      //minute 
		//var hour = Math.floor(SysSecond / 3600);      //hour
		var hour = Math.floor((SysSecond / 3600) % 24);      //hour 
		var day = Math.floor((SysSecond / 3600) / 24);      //day
		if(second < 10) {
			second = '0' + second;
		} 
		if(minite < 10) {
			minite = '0' + minite;
		} 
		if(hour < 10) {
			hour = '0' + hour;
		} 
		
		str = hour + " : " + minite + " : " + second;
		if(day) {
			str = day + " days " + str;
		}
		
		$("#timer").html(str);
	} else {
		window.clearInterval(InterValObj);
		$("#timer").html("00 : 00 : 00");
		//alert("End of time! ");
	}
} 
	function pagefunc(p){
		$.post("/soc.php?cp=facelikerace",{full:"1",p:p},function(data){$('#mainlist').html(data);});
	}
		
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script>
{/literal}

<div class="soc-scoreboard">
	<div class="soc-score-bg-img">
<div class="soc-scoreboard-bg facebook" style=" min-height:220px;">
<div class="soc-content">
	<div class="soc-top"></div>
	<div class="soc-bottom"></div>

<div id="refcontent">
	<div class="refcontent-title">
		<div class="ref-left">
			<h2>SOC Sprints ... Coming soon!</h2>
			<div class="text">
               <p>Check back here to see if SOC Sprints have begun. <br />
               	  More prizes and fun to be had! </p>
            </div>			
			<p>
			<!--<input type="button" onclick="javascript:location.href='/soc.php?act=signon'" value="Join the sprint!" class="button">
			&nbsp;&nbsp;    
			<input type="button" onclick="javascript:location.href='/soc.php?cp=pre_facelikerace'" value="View Previous Winners" class="button">-->
			</p>
		</div>
        <div class="award" style="min-height:190px; width:360px;">&nbsp;</div>
    <div class="clear"></div>
	</div>
    <div class="clear"></div>
	</div>
</div>
</div>
</div>
</div>
{/if}
