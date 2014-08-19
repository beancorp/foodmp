{if $state_name ne '' or $statename ne ''}
    {if $req.imageAds.bottom.exists eq 'yes'}
    <div align="center" style="width:930px;margin:0 auto 10px auto;">
        <a href="/adclick.php?id={$req.imageAds.bottom.id}"><img src="/{$req.imageAds.bottom.img_url}" width='930' height='170' border="0" /></a>
    </div>
    {/if}
{/if}
