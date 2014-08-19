<script type="text/javascript" src="/skin/red/js/jquery.livequery.js"></script>
<script type="text/javascript" src="/skin/red/js/niftyplayer.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery.galleria.js"></script>
<link href="/skin/red/css/galleria.css" rel="stylesheet" type="text/css" media="screen"> 
<script type="text/javascript">
	var site="{$smarty.get.site}";
	var galleryname="{$smarty.get.gallery}";
	var images_nums={$pagelist.links.totals};

</script>
{literal}
<style type="text/css">
	.demo{position:relative;}
	.gallery_demo{width:712px;margin:0 auto; list-style:none;}
	.gallery_demo li{width:68px;height:50px;border:3px double #111;margin: 2px 2px;background:#000;}
	.gallery_demo li div{left:240px}
	
	#main_image{margin:0 auto 10px auto; padding-bottom:60px;_padding-bottom:32px;width:500px; height:325px;_height:300px;background:black; text-align:center; }
	#main_image img{padding:10px;}	
	.nav{padding-top:15px;clear:both;font:80% 'helvetica neue',sans-serif;letter-spacing:3px;text-transform:uppercase;}
	.nav a{color:#348;text-decoration:none;outline:none;}
	.nav a:hover{color:#67a;}
	.caption{ padding:0 10px;color:#ffffff; background:black; font-weight:bold;}
	#pages_div span a {
	color:#FFFFFF;
	}

</style>
<script>
	var autoplaytime=6000;
	var page_waitting=2000;
	var intervalID=0;
	var timeoutID=0;
	var autoplay_state=false;
jQuery(function($) {
		
		$('.gallery_demo_unstyled').addClass('gallery_demo'); // adds new class name to maintain degradability
		
		$('ul.gallery_demo').galleria({
			history   : false, // activates the history object for bookmarking, back-button etc.
			clickNext : false, // helper for making the image clickable
			insert    : '#main_image', // the containing selector for our main image
			onImage   : function(image,caption,thumb) { // let's add some image effects for demonstration purposes
				
				// fade in the image & caption
				var browserName=navigator.userAgent.toLowerCase();
				if(/chrome/i.test(browserName) && /webkit/i.test(browserName) && /mozilla/i.test(browserName)){
					is_chrome=true;
				}
				else {
					is_chrome=false;
				}
				if(! ($.browser.mozilla && navigator.appVersion.indexOf("Win")!=-1) && is_chrome==false) { // FF/Win fades large images terribly slow
					image.css('display','none').fadeIn(1000);
				}
				caption.css('display','none').fadeIn(1000);
				
				// fetch the thumbnail container
				var _li = thumb.parents('li');
				
				// fade out inactive thumbnail
				_li.siblings().children('img.selected').fadeTo(500,0.3);
				
				// fade in active thumbnail
				thumb.fadeTo('fast',1).addClass('selected');
				
				// add a title for the clickable image
				//image.attr('title','Next image >>');
			},
			onThumb : function(thumb) { // thumbnail effects goes here
				
				// fetch the thumbnail container
				var _li = thumb.parents('li');
				
				// if thumbnail is active, fade all the way.
				var _fadeTo = _li.is('.active') ? '1' : '0.3';
				
				// fade in the thumbnail when finnished loading
				thumb.css({display:'none',opacity:_fadeTo}).fadeIn(1500);
				
				// hover effects
				thumb.hover(
					function() { thumb.fadeTo('fast',1); },
					function() { _li.not('.active').children('img').fadeTo('fast',0.3); } // don't fade out if the parent is active
				)
			}
		});
		
		
		if($(".gallery_demo_unstyled li").length==1) {
			$("#autoplay_button").hide();
		}
		
		$(".gallery_demo_unstyled li,#preview_a,#next_a").livequery('click',function(){
			if(autoplay_state==true) {
				clearInterval(intervalID);
				autoplay();
			}
		});	
		
		
	});
	
	
	
			//Auto Play
	function autoplay(){
			intervalID=setInterval(function(){
					$.galleria.next();
					now_index=$(".gallery_demo_unstyled li").index($(".gallery_demo_unstyled li.active"));
					li_length=$(".gallery_demo_unstyled li").length;
					if(images_nums>18) {
						if(li_length==now_index+1)  {
							obj_next=$("#pages_div span a[title='next page']");
							if(obj_next.attr('href')) {
								stopplay(false,false);
								timeoutID=setTimeout("getGallery(obj_next.attr('href'),true)",autoplaytime);
							}
							else {
								stopplay(false,false);
								timeoutID=setTimeout("getGallery('#/1',true)",autoplaytime);
							}
						
						}
					}
				},
				autoplaytime
			);
			autoplay_state=true;
			$("#img_autoplay_play").attr('src','/skin/red/images/gallery/gallery_autoplay_play_null.jpg');
			$("#img_autoplay_play").css('cursor','default');
			$("#img_autoplay_stop").attr('src','/skin/red/images/gallery/gallery_autoplay_stop.jpg');
			$("#img_autoplay_stop").css('cursor','pointer');
	}
	
	function stopplay(auto,button_status){
		clearInterval(intervalID);
		if(auto) clearTimeout(timeoutID);
		autoplay_state=false;
		if(button_status) {
			$("#img_autoplay_stop").attr('src','/skin/red/images/gallery/gallery_autoplay_stop_null.jpg');
			$("#img_autoplay_stop").css('cursor','default');
			$("#img_autoplay_play").attr('src','/skin/red/images/gallery/gallery_autoplay_play.jpg');
			$("#img_autoplay_play").css('cursor','pointer');
		}
	}
	
	
	function getGallery(href,auto,pre_status) {
		//href=$(a).attr('href');
		arr=href.split('/');
		pageno=arr[arr.length-1];
		$.getJSON(
		'/include/jquery_svr.php?svr=getgallery&site='+site+'&gallery='+galleryname+'&p='+pageno+'&time='+new Date().getTime(),
		function(data) {
			str='';
			$.each(data.gallerylist,function(i,val){
				str+='<li';
				if(pre_status) {
					str+(data.gallerylist.length==i) ? ' class="active" ':'';
				}
				else {
					str+=(i==0) ? ' class="active" ':'';
				}
				
				str+='><img src="'+val['gallery_images']+'" ';
				str+=' alt="'+(val['gallery_desc']?val['gallery_desc']:'No description.')+'" ';
				str+=' title="'+(val['gallery_desc']?val['gallery_desc']:'No description.')+'" ';
				str+='/>';
				str+='</li>';
			});
			$(".gallery_demo_unstyled").get(0).innerHTML=str;
			
			//*********** 	Waitting
			if(auto) {
				//alert('do');
				stopplay(false,false);
				if($(".gallery_demo_unstyled li").length>1) {
					timeoutID=setTimeout('autoplay()',page_waitting+autoplaytime);
				}
				else {
					timeoutID=setTimeout("getGallery('#/1',true)",autoplaytime+page_waitting);
				}
				
			}
			
			$("#pages_div span").html('Total images:&nbsp;'+data.pagelist['links']['totals']+'&nbsp;&nbsp;&nbsp;&nbsp;'+data.pagelist['links']['links']['all']);
			
			$('ul.gallery_demo').galleria({
				history   : false, // activates the history object for bookmarking, back-button etc.
				clickNext : false, // helper for making the image clickable
				insert    : '#main_image', // the containing selector for our main image
				onImage   : function(image,caption,thumb) { // let's add some image effects for demonstration purposes
					
					// fade in the image & caption
					var browserName=navigator.userAgent.toLowerCase();
					if(/chrome/i.test(browserName) && /webkit/i.test(browserName) && /mozilla/i.test(browserName)){
						is_chrome=true;
					}
					else {
						is_chrome=false;
					}
					if(! ($.browser.mozilla && navigator.appVersion.indexOf("Win")!=-1) && is_chrome==false) { // FF/Win fades large images terribly slow
						image.css('display','none').fadeIn(1000);
					}
					caption.css('display','none').fadeIn(1000);
					
					// fetch the thumbnail container
					var _li = thumb.parents('li');
				
					// fade out inactive thumbnail
					_li.siblings().children('img.selected').fadeTo(500,0.3);
					
					// fade in active thumbnail
					thumb.fadeTo('fast',1).addClass('selected');
					
					// add a title for the clickable image
					//image.attr('title','Next image >>');
				},
				onThumb : function(thumb) { // thumbnail effects goes here
					// fetch the thumbnail container
					if(pre_status) {
						$(".gallery_demo_unstyled li:last").addClass('active');
					}
					else {
						$(".gallery_demo_unstyled li:first").addClass('active');
					}
					var _li = thumb.parents('li');

					// if thumbnail is active, fade all the way.
					var _fadeTo = _li.is('.active') ? '1' : '0.3';
					// fade in the thumbnail when finnished loading
					thumb.css({display:'none',opacity:_fadeTo}).fadeIn(1500);
					
					// hover effects
					thumb.hover(
						function() { thumb.fadeTo('fast',1); },
						function() { _li.not('.active').children('img').fadeTo('fast',0.3); } // don't fade out if the parent is active
					)
				}
			});
			
			
			if($(".gallery_demo_unstyled li").length==1) {
				$("#autoplay_button").css('display','none');
				stopplay(false,false);
			}
			else {
				if(autoplay_state==true) {
					clearInterval(intervalID);
					autoplay();
				}
				$("#autoplay_button").css('display','');
			}
			if(pre_status) {
				$(".gallery_demo_unstyled li:last").addClass('active');
			}
			else {
				$(".gallery_demo_unstyled li:first").addClass('active');
			}
		});
		
		
	}
	
	
	function next_img() {
		//$.galleria.next()
		obj_next=$("#pages_div span a[title='next page']");
		now_index=$(".gallery_demo_unstyled li").index($(".gallery_demo_unstyled li.active"));
		li_lenght=$(".gallery_demo_unstyled li").length;
		
		if(images_nums>18) {				//More Pages
			if(now_index<li_lenght-1) {
				$.galleria.next();
				return false;
			}
			else {
				if(obj_next.attr('href')) {	//Next Page
					getGallery(obj_next.attr('href'),false);
				}
				else {
					getGallery('#/1',false);
				}
			}
		
		
		
		}
		else {			//One Page
			$.galleria.next();
			return false;
		}
	
	}
	
	function pre_img() {
		obj_pre=$("#pages_div span a[title='previous page']");
		now_index=$(".gallery_demo_unstyled li").index($(".gallery_demo_unstyled li.active"));
		li_lenght=$(".gallery_demo_unstyled li").length;
		all_page_nums=1;
		all_page_nums=parseInt(images_nums/18);
		all_page_nums+=(images_nums%18>0)?1:0;
		//alert(all_page_nums);
		if(images_nums>18) {				//More Pages
			if(now_index > 0) {
				$.galleria.prev(); 
				return false;
			}
			else {
				if(obj_pre.attr('href')) {
					getGallery(obj_pre.attr('href'),false,true);
					//$(".gallery_demo_unstyled li:last").addClass('active');
					//alert($(".gallery_demo_unstyled li").index($(".gallery_demo_unstyled li.active")));
				}
				else {
					getGallery('#/'+all_page_nums,false,true);
					//$(".gallery_demo_unstyled li:last").addClass('active');
					//alert($(".gallery_demo_unstyled li").index($(".gallery_demo_unstyled li.active")));
				}
			}
		
		}
		else {				// One Page
			$.galleria.prev(); 
			return false;
		}
		//alert($(".gallery_demo_unstyled li").index($(".gallery_demo_unstyled li.active")));
	}
	
	
	//autoplay();
</script>
{/literal}
{if $gallerydetail.music neq ""}
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
				$.post('/include/jquery_svr.php',{svr:'setGalleryplaymusic',StoreID:StoreID,playnow:0});
				$('#playImg').attr('src','/skin/red/images/buttons/stopMusic.gif');
				$('#offmusic').html('On');
            }else{
                niftyplayer('niftyPlayer1').play();
                isplay = true;
				$.post('/include/jquery_svr.php',{svr:'setGalleryplaymusic',StoreID:StoreID,playnow:1});
				$('#playImg').attr('src','/skin/red/images/buttons/playMusic.gif');
				$('#offmusic').html('Off');
            }
			
        }
		window.onload = function(){var t1 = window.setInterval("replay()",500)};
    </script>
    {/literal}
{/if}
<link type="text/css" href="/skin/red/css/wishlist.css" rel="stylesheet" media="screen" />

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

<div id="wishlist_content" style="background:URL({$wishinfo.template}) no-repeat;height:563px;">
	<div style="margin:0 10px; height:500px; position:relative; z-index:10002; text-align:center;">
	
	
	<div style="width:500px; background:#000; color:#fff; margin:auto; padding:5px 0 0 0; font-weight:bold; font-size:14px;"><span style="margin:0 auto;font-weight:bold; font-size:14px;color:#fff;">{$gallerydetail.gallery_category|truncate:45}</span>&nbsp;&nbsp;&nbsp;
	
	
	{if $pagelist.links.totals >1}
	<span style="position:absolute;" id="autoplay_button"><img id="img_autoplay_play" height="17px" width="17px" src="/skin/red/images/gallery/gallery_autoplay_play.jpg" alt="Play Slideshow" title="Play Slideshow" style="cursor:pointer;" onclick="autoplay()"/>  <img id="img_autoplay_stop" height="17px" width="17px" src="/skin/red/images/gallery/gallery_autoplay_stop_null.jpg" alt="Pause Slideshow" title="Pause Slideshow" style="cursor:default;" onclick="stopplay(true,true)"/></span>
	{/if}
	
	</div>
	
	
	
    <div style="text-align:right;padding-right:10px; z-index:10000; position:absolute;right:110px;top:0;"> {if $gallerydetail.music neq ""}
     <img src="/skin/red/images/buttons/{if $smarty.session.galleryplay[$gallerydetail.StoreID] eq '0'}stopMusic.gif{else}playMusic.gif{/if}" id="playImg" onclick="playSound('{$gallerydetail.StoreID}');" height="12" style="margin-left:10px; margin-top:3px; cursor:pointer; " /> <span id="offmusic" style="color:#FFF;">{if $smarty.session.galleryplay[$gallerydetail.StoreID] eq '0'}On{else}Off{/if}</span>
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="niftyPlayer1" align="">
        <param name=movie value="/skin/red/js/niftyplayer.swf?file=/{$gallerydetail.music}&as={if $smarty.session.galleryplay[$gallerydetail.StoreID] eq '0'}0{else}1{/if}">
        <param name=quality value=high>
        <param name=wmode value=transparent>
        <param name=bgcolor value=#FFFFFF>
        <embed src="/skin/red/js/niftyplayer.swf?file=/{$gallerydetail.music}&as={if $smarty.session.galleryplay[$gallerydetail.StoreID] eq '0'}0{else}1{/if}" quality=high bgcolor=#FFFFFF width="1" height="1" name="niftyPlayer1" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent">
        </embed>
	</object>
  {/if}
 </div> 
 <div style="clear:both"></div> 
 	<div class="demo">
 	 {if $gallery_count gt 1} 
 	<div style="position:absolute;left:115px;_left:0;top:0px;background:url(/skin/red/images/blank.gif) repeat;z-index:20003;width:250px;height:230px;padding-top:150px;text-align:left;" onmouseover="javascript:$('#preview_a').show();" onmouseout="javascript:$('#preview_a').hide();"> 
 		<a id="preview_a" style="display:none;" href="javascript:void(0);" onclick="pre_img()"><img src="/skin/red/images/prev.gif"/></a>
		</div> 
 	 <div style="position:absolute;right:115px;height:230px;width:250px;background:url(/skin/red/images/blank.gif) repeat;z-index:20003;padding-top:150px;text-align:right;" onmouseover="javascript:$('#next_a').show();" onmouseout="javascript:$('#next_a').hide();"> 
 	 <a id="next_a" style="display:none;" href="javascript:void(0);" onclick="next_img()"><img src="/skin/red/images/next_g.gif"/></a></div> 
 	 {/if}
       <div id="main_image"></div>
        {if $gallerlist}
		
			<ul class="gallery_demo_unstyled" style="list-style-type:none;">
				{foreach from=$gallerlist item=gl key=k}
				<li {if $k eq 0}class="active"{/if}><img src="{$gl.gallery_images}" alt="{if $gl.gallery_desc}{$gl.gallery_desc|truncate:180}{else}No description.{/if}" title="{if $gl.gallery_desc}{$gl.gallery_desc|truncate:180}{else}No description.{/if}" style="display:none;"/></li>
				{/foreach}
			</ul>
		
        {/if}
        <div style="clear: both;"></div>
        <div id="pages_div" style="text-align:center;width:100%;margin-top:5px;"><span style="background:#000;padding:0px 10px; color:#FFFFFF;">Total images:&nbsp;{$pagelist.links.totals}&nbsp;&nbsp;&nbsp;&nbsp;{$pagelist.links.links.all}</span></div>
    </div>
    </div>
</div>
{include file='wishlist/wishlist_bottom.tpl'}