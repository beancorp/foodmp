{literal}
<script type="text/javascript">
	function showmorecat(id,obj){
		if($("li[class='"+id+"']").css('display')=='none'){
			$("li[class='"+id+"']").css('display','');
			obj.innerHTML = "Less<img src='/skin/red/images/arrow-up.gif'/>";
		}else{
			$("li[class='"+id+"']").css('display','none');
			obj.innerHTML = "More<img src='/skin/red/images/arrow-down.gif'/>";
		}
	} 
</script>
{/literal}

{php}$i = 0;$j=0;{/php}
{foreach from=$req.category item=catlist key=k}
{php}
	if($i%3==0){
{/php}
	<div style="float:left; width:752px;padding:0;margin:0;">
{php}
   	}
{/php}
<div class="all-categories">
<h3><a href="soc.php?cp=prolist&amp;id={$catlist.id}">{$catlist.name}</a>{if $catlist.image ne ''}<img src="{$catlist.image}" height="50" width="50" class="cat-icon" />{else}<img src="./skin/red/images/cat-antiques.gif" class="cat-icon" />{/if}</h3>
<ul class="categorylist">
          {php}$j=0;{/php}
          {foreach from=$catlist.sublist item=scl}
          	{php} if($j<5){ {/php}
            <li><a href="#"><a href="soc.php?cp=prolist&amp;id={$scl.id}">{$scl.name}</a>&nbsp;{*({$scl.number})*}</li>
          	{php} }else{ {/php}
            <li class="sublist_{$catlist.id}" style="display:none;"><a href="#"><a href="soc.php?cp=prolist&amp;id={$scl.id}">{$scl.name}</a>&nbsp;{*({$scl.number})*}</li>
            {php} }
            	  $j++; 
            {/php}
          {/foreach}
          {php}
          	if($j>5){
            	echo "<li><a href='javascript:void(0);' style='font-weight:bold;' onclick=\"showmorecat('sublist_";{/php}{$catlist.id}{php}echo "',this)\">More<img src='/skin/red/images/arrow-down.gif'/></a></li>";
            }
          {/php}
</ul>
</div>
{php}
	if(($i+1)%3==0){
{/php}
	</div>
{php}
	} 
    $i++;
{/php}
{/foreach}
{php}
if($i%3!=0){
{/php}
</div>
{php}
}
{/php}
