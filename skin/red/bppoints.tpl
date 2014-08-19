{literal}
<style type="text/css">
	ul.mainlist {list-style:none; margin:0; width:750px; background:#9E99C1; float:left; }
	ul.mainlist li{padding:0;margin:0; float:left; width:100%; }
	
	ul.listhead { list-style:none; margin:0; padding:0; width:100%; float:left; height:23px;}
	ul.listhead li{ padding:0; background:#9E99C1; color:#FFFFFF; font-weight:bold; text-align:center;height:23px;line-height:23px; border-left:1px solid #FFFFFF;}
	ul.listhead li{ margin:0 0 1px 0px ; float:left;width:246px;overflow:hidden;}
	ul.listhead li.nickname{width:255px;overflow:hidden;}
	ul.listhead li.item-listed{width:260px;overflow:hidden;}
	ul.listhead li.r-points{width:246px;}
	
	ul.listhead li.ref-points{width:245px;overflow:hidden;}
	ul.listhead li.ref-item-listed{width:184px;}
	
	ul.list { height:31px; list-style:none; margin:0; padding:0; width:100%; float:left;}
	ul.list li{float:left;	width:246px;   height:30px;   line-height:30px;   margin:0 0 0px 0px;  border-left:1px solid #9E99C1;   border-bottom:1px solid #9E99C1;  text-align:center;}
	ul.list li{ padding:0; background:#ffffff;}
	
	ul.list li.nickname{width:255px;overflow:hidden;}
	ul.list li.item-listed{width:240px;overflow:hidden;}
	ul.list li.r-points{width:246px;}
	
	ul.list li.ref-points{width:245px;overflow:hidden;}
	ul.list li.ref-item-listed{width:184px;}
	
	ul.mainlist li.pagelist{ border-left:1px solid #FFFFFF;
			border-bottom:1px solid #ffffff;
			border-right:1px solid #ffffff;
			background:#FFFFFF;
			width:750px;
			height:22px;
			line-height:22px;
			text-align:center;
    }
	ul.list li .inputX{_margin:3px 0 0 0;}
	.templatelist{
		list-style:none;
		margin-left:15px;
		float:left;
		_margin-left:10px;
		width:400px;
		overflow:hidden;
	}
	.templatelist li{
		list-style:none;
		float:left;
		width:200px;
		font-weight:bold;
		margin-left:20px;
		margin-right:20px;
		margin-bottom:20px;
		text-align:center;
		overflow:hidden;
	}
	.tabtmp{
		list-style:none;
		margin:0;
		float:left;
	}
	.tabtmp li{
		list-style:none;
		width:200px;
		height:40px;
		line-height:40px;
		text-align:center;
		float:left;
		cursor:pointer;
		font-weight:bold;
	}
	.tabtmp li.active_tab{
		background-color:#9E99C1;
	}
</style>
{/literal}
<div>
 	<ul class="tabtmp" style="text-align:right; width:100%; height:30px;">
    <li class="pagelist" style="text-align:right; width:100%; font-size:14px;">
		Your Total Points: {$req.pointinfo.total_points}&nbsp;
	</li>
    </ul>
    <div style="clear: both;"></div>
 </div>
<div style="background-color: rgb(238, 238, 238); width:400px; height: 40px; margin: 0pt 0px; border-bottom: 1px solid rgb(238, 238, 238);">
 	<ul class="tabtmp">
    	<li onclick="javascript:location.href='/soc.php?cp=listpoints'" style="font-weight:bold;text-decoration:none; line-height:40px;">SOC Listing Points</li>
        <li class="active_tab" style="font-weight:bold;color:#FFF;text-decoration:none; line-height:40px;">Bonus Points</li>
    </ul>
    <div style="clear: both;"></div>
 </div>
<ul class="mainlist">
	<li>
		<ul class="listhead">
			<li class="nickname" style="border-color:#9E99C1;">Partner Site</li>
			<li class="ref-points">Answer</li>
            <li class="r-points">Points</li>
		</ul>
	</li>
	{if $req.pointinfo.bp_list}
		{foreach from=$req.pointinfo.bp_list item=l}
        <form>
		<li><ul class="list">
				<li class="nickname">{$l.site_name}</li>
				<li class="ref-points"><img style="padding:7px 0;" src="/skin/red/images/{if $l.is_correct}reserve_icon.gif{else}reserve_no_icon.gif{/if}" /></li>
                <li class="r-points">{$l.point}</li>
			</ul>
		</li>
        </form>
		{/foreach}
    {else}
    <form>
		<li><ul class="list">
				<li class="nickname" style="width:748px">No Records</li>
			</ul>
		</li>
        </form>
	{/if}
	<li class="pagelist" style="text-align:right; font-size:12px; font-weight:bold; padding-top:10px;">
		Sub Total: {$req.pointinfo.bp_points}&nbsp;&nbsp;
	</li>
	</ul>
	<div style="clear:left;"></div>	