
<link rel="stylesheet" href="/skin/red/fanpromo/styles.css" type="text/css" />
<link type="text/css" href="/static/css/fanfrenzy.css" rel="stylesheet" media="screen" />
<script src="/skin/red/fanpromo/promo.js" type="text/javascript"></script>
<script src="/skin/red/fanpromo/countdown.min.js" type="text/javascript"></script>
<script src="/skin/red/fanpromo/jquery.autocomplete.js" type="text/javascript"></script>
<script src="/skin/red/fanpromo/jquery.data.js" type="text/javascript"></script>
<script src="/skin/red/fanpromo/jquery.watermark.min.js" type="text/javascript"></script>

<style>
{literal}
	
	#promo_header_container {
		height: 300px;
		margin-top: 15px;
	}

	#promo_header_container_text {
		font-size: 14px;
		height: 150px;
		line-height: 21px;
		padding: 10px;
		position: absolute;
		top: 165px;
		width: 500px;
		margin-left: 40px;
		color: #000;
	}
	
	#promo_header_container_text_wrapper, #promo_header_container_text_wrapper * {
		font-size: 14px !important;
		line-height: 21px !important;
		color: #000 !important;
	}
	
	#enter_button {
		background-color: #f29d28;
		border: 0 none;
		border-radius: 5px;
		color: #fff;
		cursor: pointer;
		display: inline-block;
		float: left;
		font-size: 14px;
		font-weight: bold;
		height: 35px;
		padding-top: 15px;
		text-align: center;
		text-decoration: none;
		width: 180px;
		margin-top: 15px;
	}
	
	#promo_page_content {
		margin-left: 10px;
		overflow: hidden;
	}
	
	.promo_thumb {
		float: left;
		overflow: hidden;
		width: 33.333333333333%;
		padding: 0 10px 20px 10px;
		box-sizing: border-box;
	}
	
	.place_image {
		width: 51px;
		height: 73px;
		position: absolute;
	}
	
	.place_1 {
		background-image: url("/skin/red/fanpromo/1st.png");
        background-repeat: no-repeat;
	}
	
	.place_2 {
		background-image: url("/skin/red/fanpromo/2nd.png");
        background-repeat: no-repeat;
	}
	
	.place_3 {
		background-image: url("/skin/red/fanpromo/3rd.png");
        background-repeat: no-repeat;
	}
	
	.fan_count {
		border: 1px solid #000;
		padding: 10px;
		text-align: center;
		font-size: 14px;
		color: #000;
		background-color: #FFF;
		float: right;
		margin-left: 10px;
		border-radius: 5px;
	}
	
	.fan_photo {
		display: block;
		clear: both;
		margin-bottom: 10px;
	}
	
	.fan_text {
		float: left;
	}
	
	#list_pagination {
		clear: both;
		margin-top: 25px;
		margin-bottom: 25px;
		margin-right: 100px;
		float: right;
	}
	
	#list_pagination div {
		float: left;
		font-size: 14px;
		font-color: #000;
		font-weight: bold;
		cursor: pointer;
		text-decoration: underline;
	}
	
	#retailer_slider_counter {
		border-top: 1px solid #f3f7f8;
		margin-top: 25px;
		padding-bottom: 30px;
		margin-left: 50px;
		margin-right: 50px;
	}
	
	#retailer_slider_bar {
		width: 850px;
		height: 50px;
		background-color: #FFF;
		border-radius: 5px;
		overflow: hidden;
		margin-top: 10px;
	}
	
	#retailer_slider_bar_inside {
		background: #5296ff; /* Old browsers */
		background: -moz-linear-gradient(top, #5296ff 0%, #4285ed 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#5296ff), color-stop(100%,#4285ed)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top, #5296ff 0%,#4285ed 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top, #5296ff 0%,#4285ed 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top, #5296ff 0%,#4285ed 100%); /* IE10+ */
		background: linear-gradient(to bottom, #5296ff 0%,#4285ed 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#5296ff', endColorstr='#4285ed',GradientType=0 ); /* IE6-9 */
		margin: 5px;
		width: {/literal}{$bar_pixels}{literal}px;
		height: 40px;
		overflow: hidden;
		border-radius: 5px;
	}
	
	#retailer_slider_gap {
		float: left;
		overflow: hidden;
		width: 490px;
		display: none;
	}
	
	#retailer_slider_top {
        border-top: 1px solid #b2d3df;
        padding-top: 15px;
		overflow: hidden;
	}
	
	#retailer_goal {
		text-align: right;
		float: right;
		overflow: hidden;
		
	}
	
	#countdown {
		text-align: right;
		float: right;
		overflow: hidden;
        margin-left: 25px;    
	}
	
	#promo_page_top_page {
		clear: both;
		font-size: 16px;
		margin-left: -25px;
		margin-top: 25px;
	}
	
	#promo_page_top_page_search {
		background-color: #f6f6f6;
		height: 65px;
		margin-top: 20px;
		width: 880px;
		padding: 10px 20px;
		margin-left: 10px;
	}
	
	#promo_page_top_page_search_form {
		display: block;
		margin-left: auto;
		margin-right: auto;
		width: 950px;
	}
	
	.search_box {
		float: left;
		margin-right: 20px;
	}
	
	.search_box input[type="text"], .search_box select {
		width: 250px;
		padding: 5px;
	}
	
	input.btn-go {
		height: 27px;
		line-height: 27px;
		vertical-align: top;
		font-weight: bold;
	}
	
	.search_box label {
		color: #3c3283;
		font-size: 16px;
		display: block;
		clear: both;
		font-weight: bold;	
	}
	
	#current_participants {
		font-size: 16px;
	}
	
	#grand_final_nominees {
		font-size: 16px;
	}
	
	#search_message {
		color: #000;
		font-size: 16px;
		margin-bottom: 100px;
		margin-top: 10px;
		text-align: center;
	}
	
	#promo_page_content_footer {
		margin-left: 25px;
	}
	
	.blue_writing, .blue_writing * {
		color: #4690fd;
		font-weight: bold;
		font-size: 14px;
	}
	
	#countdown_timer {
		display: inline-block;
		overflow: hidden;
		width: 200px;
	}
	
	.normal_writing {
		font-size: 14px;
	}
	
	#promo_page_top_page a {
		font-weight: bold;
	}
    
    #wrapper a {
        padding-left: 0px;
    }
	
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
	.autocomplete-suggestion { cursor: pointer; padding: 2px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .content {margin-top: 5px; margin-bottom: 7px}
	.text-content-top { color: #555; font-size: 16px; line-height: 24px; }
	#search_name { border: #DCDCDC 1px solid}
	#search_name::-moz-focus-inner { border: 0; padding: 0;}
	.btn-go {font-size: 16px; padding: 0 !important; border: none}
{/literal}
</style>

<div class="block-list">
    <div class="header-fan-frenzy">
        <div class="fan-frenzy-logo"></div>
        <div class="fan-frenzy-graphic">
            <img src="images/fan-frenzy-graphic1.png" alt="">
        </div>
        <p class="text-content-top">{$fanfrenzy_text}</p>
        <div class="button-top">
            <div class="btn-enter"><a href="/entry">Enter Competition</a></div>
        </div>
        <div class="clear"></div>
        
        {if $on_off_progress_bar == 1 && $grand_final_flag <> 1}
        <div id="retailer_slider_counter">
			<div id="retailer_slider_top">
				<div id="retailer_slider_gap">&nbsp;</div>
				<div id="countdown">			
					<span class="blue_writing"><span id="countdown_timer"></span></span> <br />
					<span class="normal_writing">until the {if $smarty.const.CURRENCYCODE eq 'AUD'}Grand Final{else}Fan Frenzy Final{/if}</span>
				</div>
                <div id="retailer_goal">
                    <span class="blue_writing">{$retailer_count} Retailers joined</span> <br />
                    <span class="normal_writing">of 3000 goal</span>
                </div>
			</div>
			
			<div id="retailer_slider_bar">
				<div id="retailer_slider_bar_inside">
					&nbsp;
				</div>
			</div>
		</div>
		{/if}
		
        <div class="clear"></div>
        
    </div>
     <div id="promo_page_top_page">
		<ul id="tab-nav">
			{if $grand_final_flag == 1}
			<li class="active" id="my-gallery-bt">{if $smarty.const.CURRENCYCODE eq 'AUD'}Grand Final{else}Fan Frenzy Final{/if} Nominees</li>
			{else}
            <li {if $grand_tab!=1} class="active" {/if} id="block-gallery-bt">Current Participants</li>
            <li>|</li>
            <li {if $grand_tab==1}class="active"{/if} id="my-gallery-bt">{if $smarty.const.CURRENCYCODE eq 'AUD'}Grand Final{else}Fan Frenzy Final{/if} Nominees</li>
            {/if}
            
            {if $grand_final_flag == 1 || $grand_tab==1}
            <input type="hidden" id="promo_page_type" value="2" />
            {else}
            <input type="hidden" id="promo_page_type" value="1" />
            {/if}
        </ul>
	</div>
	<div class="clear"></div>
	<div id="promo_page_top_page_search">
		<div id="promo_page_top_page_search_form">
			<div class="search_box">
				<label for="search_name">Search</label>
				<input type="text" placeholder="keywords, nicknames, stores, photo IDs..." name="search_name" id="search_name" /> 
				<input type="button" value="Go" id="search_go" class="btn-go" />
			</div>
			<div class="search_box">
				<label for="search_categories">Categories</label>
				<div class="entry_field_element style-select">
					<span class="select-1">All Categories</span>
        			<select id="search_categories" name="search_categories">
						<option value="">All Categories</option>
						{foreach from=$lang.seller.attribute.5.subattrib item=l key=k}
						<option value="{$k}" rel="{$l}" {if $photo.category_id == $k} selected{/if}>{$l}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="search_box">
				<label for="retailer_location">Location</label>
				<div class="entry_field_element style-select">
					<span class="select-1">All</span>
        			<select  id="retailer_location" name="retailer_location">
						<option value="">All</option>
						{foreach from=$states item=state}
						<option value="{$state.id}" rel="{$state.description}" {if $photo.state_id == $state.id} selected{/if}>{$state.description}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="search_box">
				<label for="search_sort">Sort by</label>
				<div class="entry_field_element style-select">
					<span class="select-1">Votes high to low</span>
        			<select  id="search_sort" name="search_sort">
						<option value="1">Votes high to low</option>
						<option value="2">Votes low to high</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div id="promo-page-content"> 
		{if $grand_tab == 1}
			{php}view_photos(true);{/php}
		{else}	
			{php}view_photos();{/php}
		{/if}
	</div>
</div>



<script>
		{literal}
				$(document).ready(function() {
                
                    $('#search_name').watermark('keywords, nicknames, stores, photo IDs...', {useNative: false});
                    
					var targetDate = new Date({/literal}{$grand_year}, {$grand_month}, {$grand_day}{literal});
					var showCountdown = function(){
						if (parseInt(countdown(targetDate).days)>0) {
							return countdown(targetDate).days+"d "+countdown(targetDate).hours+"h "+countdown(targetDate).minutes+"m "+countdown(targetDate).seconds+"s";
						} else if (parseInt(countdown(targetDate).hours)>0) {
							return countdown(targetDate).hours+"h "+countdown(targetDate).minutes+"m "+countdown(targetDate).seconds+"s";
						} else if (parseInt(countdown(targetDate).minutes)>0) {
							return countdown(targetDate).minutes+"m "+countdown(targetDate).seconds+"s";
						} else  {
							return countdown(targetDate).seconds+"s";
						}
						
					}
					$('#countdown_timer').html(showCountdown());  
					setInterval(function(){
						$('#countdown_timer').html(showCountdown());  
					}, 1000);
					
					
					//$('#retailer_location').autocomplete({
						//serviceUrl: '/fanpromo/locations.php',
						//onSelect: function (suggestion) {
							//$('#retailer_search_value').val(suggestion.data);
							//search_listing(1);							
						//}
					//});
					
					$('#current_participants').click(function() {
						$('#promo_page_type').val(1);
						$(this).css('text-decoration', 'none');
						$('#grand_final_nominees').css('text-decoration', 'underline');
						search_listing(1);
					});
				
					$('#grand_final_nominees').click(function() {
						$('#promo_page_type').val(2);
						$('#current_participants').css('text-decoration', 'underline');
						$(this).css('text-decoration', 'none');
						search_listing(1);
					});


					 $("#search_name").keypress(function(e){
	                       if (e.which == 13) 
	                            search_listing(1);
	                    });
						
						$('#search_go').click(function() {
							search_listing(1);					
						});
						
						$('#search_sort').change(function() {
							search_listing(1);						
						});
						
						$('#search_categories').change(function() {
							search_listing(1);
						});

						
						$('#block-gallery-bt').on("click", function(){
							$('#promo_page_type').val(1);
							search_listing(1);
					        $('#tab-nav > li').removeClass('active');
					        $(this).addClass('active');
					        //$('.tab-gallery').removeClass('active');
					        $('.block-gallery').addClass('active');
					        
					    });

						//greand final click
				    	$('#my-gallery-bt').on("click", function(){
				    		$('#promo_page_type').val(2);//grand
				    		search_listing(1);
					        $('#tab-nav > li').removeClass('active');
					        $(this).addClass('active');
					        //$('.tab-gallery').removeClass('active');
					        //$('.my-gallery').addClass('active');
					        $('.block-gallery').addClass('active');
					    });				
						
						$('#retailer_location').change(function() {
							search_listing(1);
						});			
				});			

		{/literal}
</script>