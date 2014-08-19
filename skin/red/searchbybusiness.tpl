<div id="searchresults2">
	<h2>{$req.counter} seller(s) found from your search</h2>
    <strong class="keywordresult">Your search results for the keyword '{$req.business_name}'.</strong>

    <ul id="search-list">
    	{foreach from=$req.business item=business}
        <li>
        	<a href="/{$business.website_name}">{$business.img_link}</a>
        	<p>
        	<a href="/{$business.website_name}" class="arrowgrey">{$business.bu_name}</a></p>
            <span>{$business.bu_suburb}</span>
        </li>
        {/foreach}
	</ul>
    
	<div id="paging-wide">
		&nbsp;{$req.linkStr}
    </div>	
</div>