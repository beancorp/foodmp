<link rel="stylesheet" href="/skin/red/fanpromo/styles.css" type="text/css" />
<link type="text/css" href="/static/css/thankyou.css" rel="stylesheet" media="screen" />
<script src="/skin/red/fanpromo/promo.js" type="text/javascript"></script>
<style>
{literal}

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
<script>
{literal}
	$(document).ready(function() {
		$('.tab_button').click(function() {
			var identifier = $(this).attr('id');
			$('.tab_content').hide();
			$('#' + identifier + '_content').show();
			$('.tab_button').removeClass('tab_button_selected');
			$(this).addClass('tab_button_selected');
		});
		
		
		$('#promo_page_tab_about').addClass('tab_button_selected');
		$('#promo_page_tab_about_content').show();
		
		//$('#entry_form').validationEngine('attach', {scroll: false});
		
		
		$('#select_register').click(function() {
			$('#login_box').hide();
			$('#signup_box').show();
		});
		
		$('#select_login').click(function() {
			$('#signup_box').hide();
			$('#login_box').show();
		});

		$('#btn_to_frenzy').click(function() {
			window.location.href = "/fanfrenzy";
		});
		
	});
{/literal}
</script>
<div id="promo_page_container" class="thanks-shadow">
	<div id="promo_page_top">
		<div id="promo_header_container">
			<div id="promo_header_title"></div>
			<div id="promo_header_photos"></div>
		</div>
		
		<div id="promo_page_tabs">
			<div id="promo_page_tab_header">
				<div class="tab_button" id="promo_page_tab_about">About</div>
				<div class="tab_button" id="promo_page_tab_howtoenter">How to enter</div>
				<div class="tab_button" id="promo_page_tab_howitworks">Terms & Conditions</div>
			</div>
			<div id="promo_page_tab_content">
				<div class="tab_content" id="promo_page_tab_about_content">{$about_text}</div>
				<div class="tab_content" id="promo_page_tab_howtoenter_content">{$how_to_enter}</div>
				<div class="tab_content" id="promo_page_tab_howitworks_content">{$how_it_works}</div>
			</div>
		</div>
	</div>
	<div class="thankyou-content">
		{$thank_you}           
        <div class="btn-view" id="btn_to_frenzy">View All Entries</div>
	</div>
</div>