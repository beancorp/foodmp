{include_php file='include/jssppopup.php'}
{literal}
<script type="text/javascript" src="/skin/red/js/niftyplayer.js"></script>
<link type="text/css" href="/skin/red/css/wishlist.css" rel="stylesheet" media="screen" />
<script language="javascript">
	var ismore = false;
	function showmore(){
		if($('#detail_inner').height()>105){
			if(ismore){
				$('#moredetails').css('height','105px');
				$('#showmorelink').html('More<img src="/skin/red/images/wishlist_left/next.gif"/>');
				ismore =false;
			}else{
				$('#moredetails').css('height','auto');
				$('#showmorelink').html('Less<img src="/skin/red/images/wishlist_left/next.gif"/>');
				ismore = true;
			}
			
		}
		return void(0);
	}
	</script>
{/literal}
{if $wishinfodetail.music neq ""}
    {literal}
    <script language="javascript">
        function replay(){
            try{
            if(niftyplayer('niftyPlayer1').getState()=='finished'){
                niftyplayer('niftyPlayer1').play();
            }
            }catch(ex){}
        }
        var isplay = true;
        function playSound(StoreID){
            if(isplay){
                niftyplayer('niftyPlayer1').stop();
                isplay = false;
				$.post('/include/jquery_svr.php',{svr:'setplaymusic',StoreID:StoreID,playnow:0});
				$('#playImg').attr('src','/skin/red/images/buttons/stopMusic.gif');
				$('#offmusic').html('On');
            }else{
                niftyplayer('niftyPlayer1').play();
                isplay = true;
				$.post('/include/jquery_svr.php',{svr:'setplaymusic',StoreID:StoreID,playnow:1});
				$('#playImg').attr('src','/skin/red/images/buttons/playMusic.gif');
				$('#offmusic').html('Off');
            }
			
        }
        var t1 = window.setInterval("replay()",500); 
    </script>
    {/literal}
{/if}

{if $wishinfo.type eq 'flash'}
	<div id="wishlist_banner" style=" position:relative; width:750px;height:245px; padding:0; margin:0;">
		<div style="position:absolute; left:0; top:-70px; z-index:1000">
			<object width="750" height="385">
			<param name="movie" value="{$wishinfo.flash_banner}"></param>
			<param name="quality" value="high" />
			<param name="wmode" value="transparent" />
			<embed src="{$wishinfo.flash_banner}" type="application/x-shockwave-flash" width="750" height="385" wmode="transparent"></embed></object>
		 </div>
	</div>	  
{else}
	<div style="display:block; overflow:hidden; height:{$banner_img.height}px;width:750px; background-color:#000000; text-align:center;">
		<img style="padding:0;margin:0 auto; width:{$banner_img.width}px; " src="{$wishinfo.banner}" height="{$banner_img.height}" width="{$banner_img.width}" />
	</div>
{/if}

<div id="wishlist_content" style="background:URL({$wishinfo.template}) no-repeat;_height:563px;">
	<div id="wishlist_left">
    	{if $wishinfodetail.youtubevideo neq ""}
        <div style="width:243px; height:160px; margin-bottom:10px;">
        <object width="243" height="160">
            <param name="movie" value="{$wishinfodetail.youtubevideo}"></param>
            <param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
            <embed src="{$wishinfodetail.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="243" height="160"></embed>
         </object>
        </div>
    	{/if}
        <div id="wishInfoleft" style=" margin:0;width:100%;">
            <img src="/skin/red/images/wishlist_left/{$wishinfo.subCat}_top.gif" style="float:left;" border="0"/>
            <div id="moredetails" style="background:url(/skin/red/images/wishlist_left/{$wishinfo.subCat}_mid.png) repeat-y;"><div id="detail_inner" style="color:{$wishinfo.subColor}">{$wishinfodetail.description|strip_tags|nl2br}</div>
            </div>
            <div class="morelinks" style="background:url(/skin/red/images/wishlist_left/{$wishinfo.subCat}_mid.png) repeat-y;">
            	<a href="javascript:showmore();" id="showmorelink" style="color:{$wishinfo.subColor};">More<img src="/skin/red/images/wishlist_left/next.gif"/></a>
             </div>
            <img src="/skin/red/images/wishlist_left/{$wishinfo.subCat}_bottom.gif" style=" float:left;"/>
            <div style="clear:both;"></div>
        </div>
        
    	<div style="text-align:right; margin-top:10px;">
        	<img src="/skin/default/images/quickssl_anim.gif"/>
        </div>
    </div>
    <div id="wishlist_center" >
    	<div id="wishlist_header" style="background:{$wishinfodetail.color}; position:relative;">
        <table cellpadding="0" cellspacing="0">
          	<tr>
            	<td style="	color:#FFF;	font-weight:bold;font-size:14px;">Wish List - {$headerInfo.bu_name}</td>
                <td >
                {if $wishinfodetail.music neq ""}
                <img src="/skin/red/images/buttons/{if $smarty.session.Wishlistplay[$wishinfodetail.StoreID] eq '0'}stopMusic.gif{else}playMusic.gif{/if}" id="playImg" onclick="playSound('{$wishinfodetail.StoreID}');" height="12" style="margin-left:10px; margin-top:3px; cursor:pointer; " /> <span id="offmusic" style="color:#FFF;">{if $smarty.session.Wishlistplay[$wishinfodetail.StoreID] eq '0'}On{else}Off{/if}</span>
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="niftyPlayer1" align="">
                     <param name=movie value="/skin/red/js/niftyplayer.swf?file=/{$wishinfodetail.music}&as={if $smarty.session.Wishlistplay[$wishinfodetail.StoreID] eq '0'}0{else}1{/if}">
                     <param name=quality value=high>
                      <param name=wmode value=transparent>
                     <param name=bgcolor value=#FFFFFF>
                     <embed src="/skin/red/js/niftyplayer.swf?file=/{$wishinfodetail.music}&as={if $smarty.session.Wishlistplay[$wishinfodetail.StoreID] eq '0'}0{else}1{/if}" quality=high bgcolor=#FFFFFF width="1" height="1" name="niftyPlayer1" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent">
                    </embed>
				</object>
                {/if}
              	</td>
            </tr></table>
            	{if $detalpage eq "1"}<a style="color:#FFFFFF;font-size:11px; top:5px;padding-left:15px;position:absolute;right:10px;background:transparent url(/skin/red/images/arrow-purple.gif) no-repeat scroll 0 1px;text-decoration:none;" href="javascript:history.go(-1);">Back</a>{/if}
            </div>
    	<div style="width:467px; background:#FFF; padding-top:14px; padding-left:10px;">        
        	{if $prolist}
			{foreach from=$prolist item=l key=k}
            	{if $k ==0}	
                    <div style="float:right; margin-right:5px; width:240px; display:inline;">
                    <div align="center">{if $l.images.mainImage.0.sname.text ne ''}<a href="javascript:popwishSliding('{$l.StoreID}','{$l.pid}','{$l.images.mainImage.0.sname.text}');"><img border="0" src="{$l.images.mainImage.0.sname.text}" width="231" /></a>{/if}</div>
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
                                        <td width="76" align="center"><a href="javascript:popwishSliding('{$l.StoreID}','{$l.pid}','{$il.sname.text}');"><img border="0" src="{$il.sname.text}" width="76" /></a></td>
                                        {/if}
                                        {/foreach}
                                        </tr>
                                </table></td>
                                <td id="scroll_item2"></td>
                              </tr>
                            </table>
                            <script type="text/javascript" src="/skin/red/js/smallslideshow.js"></script>
                            <script  language="javascript">var scroll_item2=document.getElementById("scroll_item2");</script>
                            {if $l.images.imagesCount > 3}
                            <script  language="javascript">scroll_item2.innerHTML = scroll_item1.innerHTML;</script>
                            {/if}
               				
                        </div>
                      </div>
                </div>
               	<div>
                	<h1 style="margin: 0pt; width: auto; display: inline; clear: left; font-size: 13px; font-weight: bold; color: rgb(119, 119, 119);">{$l.item_name}</h1><br/>
                    {if $l.price}
                    <div style="padding:15px 0; font-size:13px; font-weight:bold">{if $l.protype neq 1}Price: &nbsp;&nbsp;${$l.price|number_format:2}{/if}
                    	{if $l.protype neq 1}{if $l.gifted neq '0' && $l.fotgive}<div style="margin-left:50px">(${$l.gifted|number_format:2} already gifted)</div>{/if}{/if}
                    </div>{if $l.fotgive}
                    <a href="{$soc_https_host}soc.php?act=wishlistproc&cp=buy&StoreID={$l.StoreID}&pid={$l.pid}"><img src="/skin/red/images/buttons/bu-giftMe.gif"/></a>{else}<img src="/skin/red/images/sold_wish_icon.png"/>{/if}
                    {/if}
                    <div style="width:222px; height:15px; margin:0;"></div>
                    {$l.description|strip_tags|nl2br}<br/>
                    {if $l.youtubevideo neq ""&& $sellerhome neq "1"}
                    <div style="float:left;">
                    <object width="457" height="280">
                        <param name="movie" value="{$l.youtubevideo}"></param>
                        <param name="allowFullScreen" value="true"></param>
                        <param name="allowscriptaccess" value="always"></param>
                        <param name="wmode" value="transparent" />
                        <embed src="{$l.youtubevideo}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="457" height="280" wmode="transparent"></embed>
     				</object>
                    </div>
                    {/if}
                    
                </div>

                
                {/if}
                {/foreach}
                {/if}
            <div style="clear:both;"></div>
            <div style="width:100%; text-align:right; padding-top:10px;">
            {if $detalpage neq "1"}<span style="padding-right:20px;">
            <strong><a href="/socwishlist.php?cp=dispro&StoreID={$req.info.StoreID}">All Items</a>&nbsp;&nbsp;({$procount})</strong></span>{/if}</div>
        </div>
    	<img src="/skin/red/images/wish_bottom.png"/>
    </div>
    <div style="clear:both;"></div>
   {include file='wishlist/wishlist_bottom.tpl'}
</div>