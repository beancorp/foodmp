<link rel="stylesheet" href="/skin/red/fanpromo/styles.css" type="text/css" />
<script src="/skin/red/fanpromo/promo.js" type="text/javascript"></script>
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
	
	#promo_page_top_page {
		clear: both;
		font-size: 16px;
		margin-left: 20px;
		margin-top: 25px;
	}
	
{/literal}
</style>
<div id="promo_page_container">
	<div id="promo_page_top">
		<div id="promo_header_container">
			<div id="promo_header_title"></div>
			<div id="promo_header_photos2"></div>
		</div>
	</div>
	<div id="promo_page_content">
		{$terms_page}	
	</div>
</div>