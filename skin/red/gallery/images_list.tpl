    	<ul class="gallarylist">
         {if $req.gallerylist}
         	{foreach from=$req.gallerylist item=gl}
        	<li style="width:106px;"><div class="listimg"><span><a href="{if $gl.gallery_images}{$gl.gallery_images}{else}#{/if}" rel=lightbox><img src="{if $gl.gallery_thumbs}{$gl.gallery_thumbs}{else}/images/100x100.jpg{/if}"/></a></span></div>
            	<div class="des_catname" title="{$gl.gallery_desc}">{$gl.gallery_desc|truncate:11:'...' }</div>
                <div class="des_opts">
                    <ul>
                        <li class="navigate"><img style="cursor:pointer;" onclick="updateOrder('left',{$gl.id},{$gl.gallery_order})" src="/skin/red/images/previous.jpg" alt="Move Left" title="Move Left" /></li>
                        <li><a href="/soc.php?act=galleryinfo&cid={$gl.gallery_category}&cp=edit&pid={$gl.id}"><img height="20" align="absmiddle" src="/skin/red/images/adminhome/icon-manage.gif" title="Edit"/></a></li>
                        <li><a href="/soc.php?act=galleryinfo&cid={$gl.gallery_category}&cp=del&pid={$gl.id}" onClick="javascript:return delconfirm('image');"><img height="20" align="absmiddle" src="/skin/red/images/adminhome/icon-delete.gif" title="Delete"/></a></li>
                        <li class="navigate"><img style="cursor:pointer;" onclick="updateOrder('right',{$gl.id},{$gl.gallery_order})" src="/skin/red/images/next.jpg" alt="Move Right" title="Move Right" /></li>
                    </ul>
                </div>
            </li>
            {/foreach}
        {/if}
        </ul>
