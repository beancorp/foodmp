<link rel="stylesheet" href="/skin/red/fanpromo/styles.css" type="text/css" />
<style>
{literal}
	.error_message {
		clear: both;
		margin-bottom: 25px;
		font-size: 14px;
		color: #FF0000;
		font-weight: bold;
		text-align: center;
		padding: 10px;
	}
	
	#promo_page_content {
		margin-left: 10px;
		overflow: hidden;
		margin-bottom: 10px;
	}
	
	.promo_thumb_row {
		clear: both;
		overflow: hidden;
	}
	
	.promo_thumb {
		float: left;
		margin: 10px;
		overflow: hidden;
		width: 290px;
	}
	
	.place_image {
		width: 51px;
		height: 73px;
		position: absolute;
	}
	
	#photos_listing {
		width: 550px;
		overflow: hidden;
		margin-left: 100px;
	}
	
	#list_pagination {
		margin-bottom: 20px;
		font-weight: bold;
		font-size: 12px;
		color: #000;
		text-align: right;
	}
	
	#list_prev {
		cursor: pointer;
	}
	
	#list_next {
		cursor: pointer;
	}
	
	.fan_text {
		font-size: 12px;
	}
	
	.remove_link {
		display: inline-block;
		color: #000 !important;
		font-size: 12px;
		float: left;
	}
	
	.make_finalist {
		display: inline-block;
		float: right;
		color: #000 !important;
		font-size: 12px;
	}
	
{/literal}
</style>

<div id="photos_listing">

{$listing}

</div>