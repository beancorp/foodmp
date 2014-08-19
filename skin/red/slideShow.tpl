<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$itemTitle}</title>

<link rel="stylesheet" type="text/css" href="/skin/red/images/slideshow/css.css" />
{literal}
<!--[if IE 6]>
<style type="text/css">
.thumbs{
z-index:102;
position:absolute;
clear:left; 
left:0px;
bottom:-1px;
width:720px;
}
</style>
<![endif]-->
{/literal}
<script>
	var tmpimgary = new Array();
	{literal}
	function setnum(num){
		document.getElementById('numnow').innerHTML = num;
	}{/literal}
</script>
<script>
{foreach from=$req.big item=l key=k}
	{if $l.text ne '/images/700x525.jpg'}
	tmpimgary[{$k+1}] = new Array();
	tmpimgary[{$k+1}]['text'] = '{$l.text}';
	tmpimgary[{$k+1}]['width'] = '{$l.width}';
	tmpimgary[{$k+1}]['height'] = '{$l.height}';
	{/if}
{/foreach}
</script>
</head>

<body onload="slideShow({$req.select})">
<div class="imagehead" >
	  <div style=" float:left;height:73px;width:100px;">
	  	
	  </div>
	  <div style=" float:left;width:620px;height:73px;">
	  	<div style="float:left;width:344px;height:73px;color:#FFFFFF;text-align:left;font-size:12px; font-family:Verdana;position:relative;">
		<div style="position:absolute;left:6px;bottom:6px; width:334px; white-space:break-all; padding-right:5px;">{if $adrdis eq 1}{$req.address|wordwrap:24:true}, {$req.suburb}, {$req.state}{/if}</div>
		</div>
	  	<div style="float:left;width:276px;height:20px;text-align:left;font-size:11px; font-family:Verdana; color:#FFFFFF; margin-top:53px;">
	  	&nbsp;<a href="#" onclick="imgNav(-1);" style="text-decoration:none;color:#FFFFFF">&lt;&nbsp;Previous</a>&nbsp;&nbsp;| Image <span id="numnow">1</span> of {$req.count}&nbsp; |&nbsp;&nbsp;<a href="#" onclick="imgNav(1);" style="text-decoration:none;color:#FFFFFF">Next&nbsp;&gt;</a>
	  	</div>
	  </div>
</div>

<div class="spacediv"></div>
<div class="content">
	<div class="tmpleft"></div>
	<div class="contentcenter" id="image"></div>
	<div class="tmpleft"></div>
	
	<div class="thumbs">
		<div class="thumbshead">
			
			<img src="/skin/red/images/slideshow/space.gif" style="float:left;padding:9px 0 0 0;margin:0;border:0;" height="1px" width="319px" />
			<IMG SRC="/skin/red/images/slideshow/button3.jpg" style="cursor:pointer;float:left;padding:0;margin:0;border:0;"  WIDTH="82px" HEIGHT="10px"   onclick="javascript:document.getElementById('imgthumbs').style.display=document.getElementById('imgthumbs').style.display==''?'none':''; "/>
			<img src="/skin/red/images/slideshow/space.gif" style="float:left;padding:9px 0 0 0;margin:0;border:0;" height="1px" width="319px" />
		</div>
		<div id='imgthumbs'>
			
			<div class='butleft'><img src="/skin/red/images/slideshow/button1.gif" width=26 height=39 onmousemove="scrlThumbs(-1);" onmouseout="cnclScrl();"/></div>
			<div class='thumbscontent'>
			
				<ul id="thumbs">
				
				{foreach from=$req.small item=l key=k}
					{if $l.text ne '/images/79x79.jpg' and $l.text ne '/images/243x212.jpg'}
	        		<li value="{$k+1}"><img src="{$l.text}" width="{$l.width}" height="79" /></li>
					{/if}
				{/foreach}
      			</ul>
			</div>
			<div class='butright'><img src="/skin/red/images/slideshow/button2.gif" width=26 height=39 onmousemove="scrlThumbs(1);" onmouseout="cnclScrl();"/></div>
		</div>
	</div>
</div>
 <script type="text/javascript">
var imgid = 'image';
var imgdir = '';
var thumbid = 'thumbs';
var auto = false;
var autodelay = 5;
//var imgwidth = '700px';
//var imgheight ='525px';
var fixwidth = 10; //fix right move out bug
</script>
<script type="text/javascript" src="/skin/red/js/slideshow.js"></script>
</body>
</html>
