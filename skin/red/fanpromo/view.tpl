<link rel="stylesheet" href="/skin/red/fanpromo/styles.css" type="text/css" />
<link rel="stylesheet" href="/static/css/detail.css" type="text/css" />
<script src="/skin/red/fanpromo/promo.js" type="text/javascript"></script>
<style>
{literal}
	#promo_page_view {
		background-color: #fff;
		margin-top: 25px;
		margin-left: 5px;
		float: left;
	}
	
	#promo_page_side {
		margin-top: 25px;
		margin-left: 5px;
		float: left;
	}
	
	#fan_button {
		width: 165px;
		float: left;
		margin-left: 10px;
	}
	
	#fan_frame {
		background-image: url("/skin/red/fanpromo/fan_background_1.png");
		position: relative;
		cursor: pointer;
		height: 109px;
		margin-top: 5px;
		text-align: center;
		width: 292px;
	}
	
	.become_fan {
		background-image: url("/skin/red/fanpromo/fan_background_2.png") !important;
	}
	
	#fan_counter {
	  color: #000;
	  display: block;
	  font-size: 12pt;
	  top: 75px;
	  left: 0;
	  position: absolute;
	  width: 88px;
	}
	
	#are_you_a_fan {
		color: #fff;
		display: block;
		font-size: 12pt;
		margin-left: 55px;
		position: absolute;
		top: 50px;
		width: 180px;
	}
	
	.fb-comments {
		margin-top: 25px;
	}
	
	#promo_photo_info {
		margin-top: 10px;
		margin-bottom: 10px;
		margin-left: 25px;
	}
	
	#promo_photo_info .profile_link {
		font-weight: bold;
		color: #000;
	}
	
	#profile_pic {
		margin-right: 10px;
	}
	
	#promo_photo_info_top {
		margin-bottom: 10px;
		overflow: hidden;
		clear: both;
	}
	
	#promo_photo_info_bottom {
		overflow: hidden;
		clear: both;
	}
	
	#photo_image {
		overflow: hidden;
		clear: both;
	}
	
	#photo_image_retailer {
		overflow: hidden;
		margin-top: 10px;
		clear: both;
	}
	
	#photo_comments {
		overflow: hidden;
		margin-top: 10px;
		clear: both;
	}
	
	#social_buttons {
		margin-left: 20px;
		margin-top: 120px;
		width: 250px;
	}
	
	#rotate_photo {
		cursor: pointer;
		display: block;
		float: right;
		margin-bottom: 25px;
	}
	
	#retailer_photo_info {
		margin-top: 10px;
		clear: both;
		overflow: hidden;
	}
	
	#promo_photo_image {
		display: block;
		margin-left: auto;
		margin-right: auto;
	}
	
	#rotate_photo {
		border: 1px solid #000;
		border-radius: 5px;
		cursor: pointer;
		display: block;
		float: right;
		margin-bottom: 25px;
		padding: 10px;
	}
	
{/literal}
</style>

<div class="detail-page-container">
	<div class="detail-page-view">
		<div class="dt-image">
			<img id="promo_photo_image" src="/fanpromo/{$photo.image}" alt="{$photo.description}">
		</div>
		{if $photo.store_id}
		<div class="dt-title-message">
			
			
			{if $photo.retailer_info.store_logo.text neq "/images/79x79.jpg"}
					<img id="dt-message-img" src="{$photo.retailer_info.store_logo.text}" alt="{$photo.bu_name}" title="{$photo.bu_name}"/></a>
			{else}
					<img id="dt-message-img" src="{$photo.retailer_info.default_store_image}" alt="{$photo.bu_name}" title="{$photo.bu_name}"/></a>
				{/if}
			
			
			
			{*<img id="dt-message-img" src="images/icon-message.png" alt="">*}
		
			
			<span class="text-harris">
				{if $photo.retailer_info.website_name eq '' && $photo.retailer_info.website_url neq ''} 
					<a href="/listing.php?id={$photo.store_id}" target="_blank">{$photo.bu_name}</a>
				{else} 
					<a href="{$smarty.const.SOC_HTTP_HOST}/{$photo.retailer_info.website_name}" class="arrowgrey">{$photo.bu_name}</a>
				{/if}
			</span>
			
			
			<span class="name-state">
			 	{if ($photo.bu_suburb)} {$photo.bu_suburb}, {/if} {$photo.stateAbbreviation}
			 	 
			</span>
		</div>
		{else}
		<div class="dt-title">   
			<img id="dt-warning-img" src="images/icon-error.png" alt="">
			<span>{$photo.retailer_name}</span>
			<span class="name-state">{$photo.stateAbbreviation}</span>
		</div>
		<p class="txt-this-retailer">{$add_retailer}{if $current_user_id == $photo.consumer_id}
		<a href="/fanpromo/enter1.php?photo_id={$photo.photo_id}"/>Add Retailer Member Code</a>
		{/if}</p>
		
		
		{/if}
		
		{*if $current_user_id == $photo.consumer_id}
		<a id="rotate_photo">Rotate Photo</a>
		{/if*}
		
		
		<div id="photo_comments">
			<div class="fb-comments" data-href="{$smarty.const.SOC_HTTP_HOST}fanpromo/view.php?image={$photo.photo_id}" data-width="615" data-numposts="5" data-colorscheme="light"></div>
		</div>
	</div>
	<div class="detail-page-side">
		<div class="dt-photo-info">
			<div class="dt-photo-info-top">
				<img id="dt-photo-info-img" src="{$profile_picture}" alt="" width="43px">
				<span>{$photo.consumer_info.bu_name}</span>
                {if $editable eq true}
				    <a href="{$smarty.const.SOC_HTTP_HOST}fanpromo/enter1.php?photo_id={$photo.photo_id}"><div class="edit-button"></div></a>
                {/if}
			</div>
			<div class="dt-photo-info-bottom">
				{$photo.photo_date}
			</div>
			<div class="dt-photo-info-content">
				{$photo.description}
			</div>
		</div>
		
		{if $vote_enabled == 1}
		<div id="fan_button">
			<div id="fan_frame">
				<div id="are_you_a_fan"></div>
				<div id="fan_counter"></div> 
			</div>
		</div>
		{elseif $photo.grand_final == 1}
		<div class="dt-block-1">
			<div class="dt-block-1-title">
				<span>Grand Finals Nominee</span>
				<img id="dt-block-1-img" src="{$smarty.const.IMAGES_URL}/icon-start.png" alt="" width="46px">
			</div>
			<div class="dt-block-1-content">
				{$grand_info_nominee}
			</div>
		</div>
		
		{/if}
		
		<div class="clear"></div>
		<div class="social-button">
			<strong>Share this...</strong> <br /><br />
			
			<span class='st_sharethis_large' displayText='ShareThis'></span>
			<span class='st_facebook_large' displayText='Facebook'></span>
			<span class='st_twitter_large' displayText='Tweet'></span>
			<span class='st_linkedin_large' displayText='LinkedIn'></span>
			<span class='st_pinterest_large' displayText='Pinterest'></span>
			<span class='st_email_large' displayText='Email'></span>
			
			<!-- 
			<img src="images/icon-face.png" alt="" width="37px">
			<img src="images/icon-twitter.png" alt="" width="37px">
			<img src="images/icon-google.png" alt="" width="37px">
			<img src="images/icon-instagram.png" alt="" width="37px">
			<img src="images/icon-mail.png" alt="" width="37px">
			 -->
		</div>
	</div>
</div>

{*
<div id="promo_page_container">
	<div id="promo_page_view">
		<div id="photo_image">
			<img id="promo_photo_image" src="/fanpromo/{$photo.image}" />
		</div>
		
		<div id="photo_image_retailer">
			<a id="rotate_photo">Rotate Photo</a>
			<div id="retailer_photo_info">
			{$photo.retailer}
			</div>
		</div>
		
		<div id="photo_comments">
			<div class="fb-comments" data-href="http://foodmarketplace.com.au/fanpromo/view.php?image={$photo.photo_id}" data-width="615" data-numposts="5" data-colorscheme="light"></div>
		</div>
	</div>
	<div id="promo_page_side">
		<div id="promo_photo_info">
		
			<div id="promo_photo_info_top">
				<img id="profile_pic" src="{$profile_picture}" align="left" width="40px">
				<a class="profile_link" href="#">{$photo.consumer}</a> 
			</div>
			<div id="promo_photo_info_bottom">
				{$photo.photo_date} <br /><br />
				{$photo.description}
			</div>
		</div>
		
		<div id="fan_button">
			<div id="fan_frame">
				<div id="are_you_a_fan"></div>
				<div id="fan_counter"></div> 
			</div>
		</div>
		<br /><br />
		<div id="social_buttons">
			<strong>Share this...</strong> <br /><br />
			<span class='st_sharethis_large' displayText='ShareThis'></span>
			<span class='st_facebook_large' displayText='Facebook'></span>
			<span class='st_twitter_large' displayText='Tweet'></span>
			<span class='st_linkedin_large' displayText='LinkedIn'></span>
			<span class='st_pinterest_large' displayText='Pinterest'></span>
			<span class='st_email_large' displayText='Email'></span>
		</div>
	</div>
</div>
*}


{literal}
				<script>
					$(document).ready(function() {
						$('#rotate_photo').click(function() {
							
							$.ajax({
								url: '/photo_orientation.php',
								type: 'post',
								data: {photo_id: '{/literal}{$photo.photo_id}{literal}'},
								dataType: 'json',
								success: function(response) {
									$('#promo_photo_image').hide();
									if (response.success) {
										$('#promo_photo_image').attr('src', response.image);
										$('#promo_photo_image').show();
									}
								}
							});
							
						});
					
						$('#fan_frame').click(function() {
							$.ajax({
								url: '/photo_fan.php',
								type: 'post',
								data: {photo_id: '{/literal}{$photo.photo_id}{literal}'},
								dataType: 'json',
								success: function(response) {
									if (!response.error) {
										$('#fan_counter').html(response.fan_counter + ' Fans');
									} else {
										if (response.message == 'Must be logged in') {
											window.location.assign("{/literal}{$smarty.const.SOC_HTTPS_HOST}soc.php?cp=login&reurl={$smarty.const.SOC_HTTPS_HOST}photo_{$photo.photo_id}.html{literal}");
										}
									}
									if (response.are_you) {
										$('#fan_frame').addClass('become_fan');
									} else {
										$('#fan_frame').removeClass('become_fan');
									}
								}						
							});
						});
						
						$.ajax({
							url: '/photo_fan.php',
							type: 'post',
							data: {get_counter: '{/literal}{$photo.photo_id}{literal}'},
							dataType: 'json',
							success: function(response) {
								$('#fan_counter').html(response.fan_counter + ' Fans');
								if (response.are_you) {
									$('#fan_frame').addClass('become_fan');
								} else {
									$('#fan_frame').removeClass('become_fan');
								}
							}						
						});					
					});			
				</script>
{/literal}