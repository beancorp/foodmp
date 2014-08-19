<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<h2 style="background:#{$templateInfo.bgcolor};">{valueOfArray arrValue=$lang.val.category value=$req.items.product[0].category} {$req.items.product[0].carTypeName}&nbsp; <a href="javascript: history.go(-1)">Back</a></h2>

{foreach from=$req.items.product item=l}  
	<div id="item-details" class="item-details">

		<div class="item-specs">
			<ul style="margin:0px; padding:0px;">
				<li style="padding-bottom:20px;"><div style="float:right; margin-right:5px; width:240px; display:inline;">
				
			<div align="center"><a href="javascript:popSliding('{$l.StoreID}','{$l.pid}','{$l.images.mainImage.0.sname.text}');"><img border="0" src="{$l.images.mainImage.0.sname.text}" width="231" /></a></div>
			<div style="clear:both"></div>
			  <div id="thumbwrapper" style="position:relative;{if $l.imagesCount < 10}border:hidden;border-color:#FFF{/if}">
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
			  

			</div>
			<div ><h1 class="itemTitle" style="width: auto; display: inline;margin:0; font-weight:bold; clear:left; font-size:16px;font-weight:701;color:#392F7E">{$l.item_name}</h1></div>
            
            <div style="float:left;padding-top: 10px; width:255px; padding-bottom:20px;">
            <fb:like href="{$smarty.const.SOC_HTTP_HOST}{if $l.is_auction=='yes'}soc.php?cp=disauction&StoreID={$l.StoreID}&proid={$l.pid}{else}{$req.info.url_bu_name}/{$l.url_item_name}{/if}" send="false" width="255" show_faces="true" font="arial"></fb:like>
            </div>
            
			<div class="TextFS14 TextJustify" style="padding-left: 0px; padding-right: 10px;">{$l.content}</div>
			
			{assign var=arrContent value=$l.featureList|explode:'|=&&&&=|'}
			{if $arrContent|isarray}
			<div style="display:block; width:250px; overflow:hidden;">
			<div class="xtop">
			<b class="xb1"></b>
			<b class="xb2"></b>
			<b class="xb3"></b>
			<b class="xb4"></b>
			</div>
			<div class="xboxcontent">
				<br />
				<strong>Feature List:</strong>
				<ol style="list-style:none; margin:0px; padding:5px 5px 0px 5px;">
				{foreach from=$arrContent item=sl}
				   {if $sl neq ''}
					<li style=" background:transparent url(/skin/red/images/li-orange.gif) no-repeat scroll 0 6px;color:#777777;padding-left:15px;padding-top:4px;">{$sl}</li>
				   {/if}
				{/foreach}
				</ol>
			</div>
			<div class="xbottom">
			<b class="xb4"></b>
			<b class="xb3"></b>
			<b class="xb2"></b>
			<b class="xb1"></b>
			</div>
			</div>
			{/if}
			{if $l.youtubevideo neq ""&& $sellerhome neq "1"}
			<div style="height:302px; width:498px; margin:10px 3px 0 0;float: right;">
           <object width="498" height="302">
                <param name="movie" value="{$l.youtubevideo}"></param>
                <param name="allowFullScreen" value="true"></param>
                <param name="allowscriptaccess" value="always"></param>
                <embed src="{$l.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="498" height="302"></embed>
     		</object>
            </div>
			{/if}
			</li>
			</ul>
		</div>
   	</div>
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

<span class="price"></span>