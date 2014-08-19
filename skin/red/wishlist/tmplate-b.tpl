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
	function pages(StoreID,pages){
		jQuery.post("/include/jquery_svr.php",
						{ svr:'wishlistpages',StoreID: StoreID,p:pages },
						function(data){
							$('#wishprolist_div').html(data);
									
					});
		return void(0);
	}
</script>
{/literal}
{if $wishinfodetail.music neq ""}
    {literal}
    <script language="javascript">
        function replay(){
            if(niftyplayer('niftyPlayer1').getState()=='finished'){
                niftyplayer('niftyPlayer1').play();
            }
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
				$.post('/include/jquery_svr.php',{svr:'setplaymusic',StoreID:StoreID,playnow:1});
				$('#playImg').attr('src','/skin/red/images/buttons/playMusic.gif');
				$('#offmusic').html('Off');
                isplay = true;
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

<div id="wishlist_content" style="background:URL({$wishinfo.template}) no-repeat; height:563px;">
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
    	<div id="wishlist_header" style="background:{$wishinfodetail.color};">
        <table cellpadding="0" cellspacing="0">
          	<tr>
            	<td style="	color:#FFF;	font-weight:bold;font-size:14px;">Wish List - {$headerInfo.bu_name}</td>
                <td>
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
            </tr></table></div>
    	<div id="wishprolist_div" style="width:100%; background:#FFF">
			<ul id="wishlist_prolist">
            	{if $prolist}
				{foreach from=$prolist item=pl key=k}
                	{if $k==0}
                    	<li class="firstli">
                    {else}
                    	<li>
                    {/if}
                    	<div id="listimg"><a href="javascript:popwishSliding('{$pl.StoreID}','{$pl.pid}','{$pl.simage.text}')"><img src="{$pl.simage.text}" width="{$pl.simage.width}" height="{$pl.simage.height}" border="0"/></a></div>
                    	<div id="listcontent">
                            <div style="width:100%;" class="protitle"><a href="/{$pl.bu_urlstring}/wishlist/{$pl.url_item_name}">{$pl.item_name|truncate:40}</a></div>
                            <div style="width:100%">{$pl.description|strip_tags|truncate:100}</div>
                            <div style="width:100%; padding-top:5px;"><a href="/{$pl.bu_urlstring}/wishlist"  class="arrowgrey">{$pl.bu_name}</a></div>
                        </div>
                    	<div id="listprice">{if $pl.price}{if $pl.protype neq 1}${$pl.price|number_format:2}{if !$pl.fotgive}<br/><img src="/skin/red/images/sold_wish_icon.png"/>{/if}{/if}{/if}</div>
                	</li>
                {/foreach}
                {/if} 
            </ul>
            <div style="clear:both;"></div>
            <div style="width:100%; text-align:right; padding-top:10px;">
            <table width="100%" cellspacing="0"><tr><td align="left" style="padding-left:10px;">(Total items: {$procount})</td>
            	<td><span style="padding-right:20px;">{$prolist.pagination}{$prolist.0.linkStr}</span></td></tr></table>
            </div>
        </div>
    	<img src="/skin/red/images/wish_bottom.png"/>
    </div>
    <div style="clear:both;"></div>
    {include file='wishlist/wishlist_bottom.tpl'}
</div>

