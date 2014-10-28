
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
		padding: 10px 25px;
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
		margin-right: 35px;
	}
	
	.search_box input[type="text"], .search_box select {
		width: 180px;
		padding: 6px;
	}
	
	.search_box label {
		color: #3c3283;
		font-size: 16px;
		display: block;
		clear: both;
		margin-bottom: 5px;
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
    
    .btn-white a {
    	color: #333;
    	font-weight: normal;
    	border-radius: 4px;
    	border: 1px solid #ccc;
    	background-color: #fff;
    	background-image: -o-linear-gradient(bottom, #FFFFFF 20%, #EEEEEE 80%);
		background-image: -moz-linear-gradient(bottom, #FFFFFF 20%, #EEEEEE 80%);
		background-image: -webkit-linear-gradient(bottom, #FFFFFF 20%, #EEEEEE 80%);
		background-image: -ms-linear-gradient(bottom, #FFFFFF 20%, #EEEEEE 80%);
		background-image: linear-gradient(to bottom, #FFFFFF 20%, #EEEEEE 80%);
    }
    
    .fan-frenzy-graphic {
    	text-align: center;
    }
    
    .fan-frenzy-graphic img {
    	display: block;
    	margin-bottom: 10px;
    }
	
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
	.autocomplete-suggestion { cursor: pointer; padding: 2px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .content {margin-top: 5px; margin-bottom: 7px}
	.text-content-top { color: rgb(72,61,130); font-size: 20px; line-height: 24px; font-weight: bold; padding-top: 180px; }
	#search_name { border: #DCDCDC 1px solid}
	#search_name::-moz-focus-inner { border: 0; padding: 0;}
	.btn-go {font-size: 16px; padding: 0 !important; border: none}
{/literal}
</style>

<div class="block-list">
    <div class="header-fan-frenzy">
        <div class="fan-frenzy-logo"></div>
        <div class="fan-frenzy-graphic">
        	<img id="dt-message-img"  src="{$logo_retailer}" alt="{$retailer.bu_name}" title="{$retailer.bu_name}" width="243" height="162"/></a>
        	<a href="{$smarty.const.SOC_HTTP_HOST}{$retailer.bu_urlstring}">Back To Store</a>
        </div>
        <p class="text-content-top">{$retailer.bu_name}</p>
        <div class="button-top">
            <div class="btn-enter"><a href="/entry">Enter Competition</a></div>
            <div class="btn-enter btn-white"><a href="/fanfrenzy">View All Entries</a></div>
        </div>
        <div class="clear"></div>
		
        <div class="clear"></div>
        
    </div>
     <div id="promo_page_top_page">
		
	</div>
	<div class="clear"></div>
	
	<div id="promo-page-content">
		{php}view_photos($_REQUEST["retailer_id"]);{/php}
	</div>
	<input type="hidden" id="retailer_id" class="retailer_id" value="{$retailer_id}" />
</div>



<script>
		{literal}
				$(document).ready(function() {
					
				});			

		{/literal}
</script>