<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<link type="text/css" href="/static/css/fanfrenzy.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="{$smarty.const.STATIC_URL}js/skin/red/jquery-fileupload.js"></script>
{*{literal}
<style>
	#account_box {
		background-color: #f8f8f8;
		border-radius: 10px 10px 10px 10px;
		color: #000000;
		font-size: 10pt;
		margin-bottom: 10px;
		overflow: hidden;
		padding: 10px;
		text-align: center;
		border: 1px solid #FF0000;
	}
	
	#account_box strong {
		color: #FF0000;
		font-size: 14pt;
	}
	
	#account_box a {
		color: #000;
	}
	
	#bank_account {
		width: 250px;
		margin-left: auto;
		margin-right: auto;
		margin-top: 15px;
		text-align: left;
	}
	
	.field_row {
		margin-bottom: 10px;
		overflow: hidden;
	}
	
	#bank_account input {
		color: #000000;
	}
	
	.field_row label {
		color: #000000;
		float: left;
		font-weight: bold;
		text-align: right;
		width: 100px;
		margin-right: 10px;
	}
	
	#account_submit {
		margin-left: 115px;
		cursor: pointer;
	}
	#form_upload_profile input {
		max-width:200px;
	}
	#form_upload_profile span.error {
		width:200px;
		font-weight:bold;
		font-color:#900;
		display:block;
	}
	#form_upload_profile input.submit {
		cursor:pointer;
	}
	#profile_upload_box h4 {
		color: #777;
		font-weight:bold;
	}
	#profile_upload_box {
		position: absolute;
		margin-top: 13px;
	}
</style>
<script language="javascript">
windowOnload(function(){Nifty("div.buyeradminhomeinfo","big alias");Nifty("div.countinfo","small alias");});
</script>
{/literal}

{if $has_bankaccount}
<div id="account_box">
	Your Referral ID is: <strong>{$ref_name}</strong>
</div>
{/if}
{if $smarty.const.SHOW_REWARDS_BANNER }
<a href="/myrewards">
	<img src="/skin/red/referralrewards/rewards_banner.jpg" />
</a>
{/if}
<img src="/skin/red/images/buyeradmin_bg_top.jpg" style="margin:0px; padding:0px;">
<div id="buyeradminhome">

<div id="profile_upload_box">
	<h4>Profile Image</h4>
	<img id="profile_image" src="{if $image_uploaded}/profile_images/{$user_id}.jpg{else}/images/no_profile_pic.jpg{/if}" width="100px" height="100px" />
	<form id="form_upload_profile" action="/profile_upload.php" enctype="multipart/form-data" method="POST">
		<input type="file" name="profile_image" /> <br />
		<span class='error' id="profile_image_error"></span>
		<input type="submit" name="submit" value="Upload Profile Picture" class="submit" />
	</form>
	
</div>
<script>
{literal}

$(document).ready(function() {
	$('#form_upload_profile').fileUpload({
		uploadData: { 'user_id' : '{/literal}{$user_id}{literal}' },
		before : function() {
			$('#profile_image_error').html('');
			$('#profile_image').attr("src","/skin/red/images/loading_profile.gif");
		},
		beforeSubmit  : function(uploadData){ 
			if (typeof uploadData['error'] === 'undefined')
			{
				d = new Date();
				$('#profile_image').attr("src",uploadData['files'][0]+'?'+d.getTime());
				console.log('new image: '+uploadData['files'][0]);
			}
			else
			{
				console.log('fail: '+uploadData['error']);
				$('#profile_image_error').html(uploadData['error']);
				$('#profile_image').attr("src","/images/no_profile_pic.jpg");
			}
			return false; 
		}
	});
});

{/literal}
</script>

<center>
	<div class="buyeradminhomeinfo">
		<ul class="buyeradminhomeinfo-top">
		<li>
		<span>{$lang.labelEmail}</span><samp title="{$smarty.session.email}">{$smarty.session.email|truncate:25}</samp>
		<div class="clearBoth"></div>
		</li>
		<li>
		<span>{$lang.labelPassword}</span><samp>********</samp>
		<div class="clearBoth"></div>
		</li>
		
		<li><a href="{$soc_https_host}soc.php?cp=edit_customers_geton" target="_self" class="redButt">Update my details/Change password</a><div class="clearBoth"></div></li>
		
		<li style="width:300px;">
		<img src="/skin/red/images/adminhome/icon-myemail.gif" align="absmiddle" style="float:left; width:auto;"><a href="/soc.php?cp=customers_geton_alerts" style="float:left; margin:3px; padding-top: 5px;">I am a Fan of <strong style="color: #FF0000;">{$req.emailAlert}</strong> websites.</a> 
		<div style="clear:both"></div>
		</li>
		{if $show_referral}
			{if $has_bankaccount}
				<li><a href="/soc.php?cp=bank_account">Bank Account</a></li>
				<li><a href="/soc.php?cp=ref_email">Refer Friends</a></li>
			{/if}
		{/if}
		
		<li><a href="/soc.php?cp=purchase">Past Receipts</a></li>
		<li><a href="/soc.php?cp=protection">Buyer Protection</a></li>
		<li>
		{if not $smarty.session.fb.can}
		<div class="fb-login-button" id="fb-login-button" data-scope="email,user_checkins">Connect to your facebook account</div>
		{else}
			<a href="/soc.php?cp=fbunbundle" target="_self">Untie the bundled facebook account</a>
		{/if}
		</li>
		&nbsp;
		</ul>
	</div>
</center>
</div>
<img src="/skin/red/images/buyeradmin_bg_bottom.jpg">

<style>
{literal}
	#fan_promo_footer {
		background-image: url('/skin/red/fanpromo/fan_profile_footer.png');
		width: 758px;
		height: 31px;
		overflow: hidden;
	}
	
	#fan_promo_content {
		width: 758px;
		overflow: hidden;	
	}

	#fan_promo_background {
		background-image: url('/skin/red/fanpromo/fan_profile_view.png');
		width: 758px;
		height: 315px;
		margin-top: 25px;
		overflow: hidden;	
	}
	
	#fan_profile_view_repeat {
		background-image: url('/skin/red/fanpromo/fan_profile_view_repeat.png');
		background-repeat: repeat-y;
		overflow: hidden;	
	}
	
	#enter_competition {
		background-image: url('/skin/red/fanpromo/enter_competition.png');
		width: 204px;
		height: 52px;
		display: block;	
		position: absolute;
		margin-top: 270px;
		margin-left: 70px;
	}
	
	#view_all_entries {
		background-image: url('/skin/red/fanpromo/view_all_entries.png');
		width: 196px;
		height: 53px;
		display: block;
		position: absolute;
		margin-top: 270px;
		margin-left: 290px;
	}
	
	#fan_promo_text {
		margin-left: 40px;
		margin-top: 180px;
		font-size: 14px;
		position: absolute;
	}
	
	#fan_profile_view_repeat_content {
		margin: 10px;
	
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
		clear: both;
		display: block;
		float: left;
		margin-bottom: 10px;
		overflow: hidden;
	}
	
	.fan_text {
		float: left;
	}
	
	#my_entries {
		width: 630px;
		overflow: hidden;
		margin-right: auto;
		margin-left: auto;
	}
	
	#fan_profile_view_repeat_content h1 {
		color: #3b3281;
		font-size: 16px;
		margin-left: 60px;
		margin-top: 50px;
	}
	

{/literal}

</style>*}


{literal}
<script>
    function store_url(url_string) {
        $.ajax({
            type: 'POST',
            url: '/ajax_requests.php?action=2',
            dataType: 'json',
            data: { value: url_string }
        }).done(function( data ) {
            if (data.valid) {
                url_valid = true;
            } else {
                url_valid = false;
                nickNameMessage();
            }
        });
    }
    
    $(document).ready(function() {
        $('#form_upload_profile').fileUpload({
            uploadData: { 'user_id' : '{/literal}{$user_id}{literal}' },
            before : function() {
                $('#profile_image_error').html('');
                $('#profile_image').attr("src","/skin/red/images/loading_profile.gif");
            },
            beforeSubmit  : function(uploadData){ 
                if (typeof uploadData['error'] === 'undefined')
                {
                    d = new Date();
                    $('#profile_image').attr("src",uploadData['files'][0]+'?'+d.getTime());
                    console.log('new image: '+uploadData['files'][0]);
                }
                else
                {
                    console.log('fail: '+uploadData['error']);
                    $('#profile_image_error').html(uploadData['error']);
                    $('#profile_image').attr("src","/images/no_profile_pic.jpg");
                }
                return false; 
            }
        });
    });
</script> 
<style>
#form_upload_profile input {
    max-width:200px;
}
#form_upload_profile span.error {
    width:200px;
    font-weight:bold;
    font-color:#900;
    display:block;
}
#form_upload_profile input.submit {
    cursor:pointer;
}
#profile_upload_box h4 {
    color: #777;
    font-weight:bold;
}
#profile_upload_box {
    position: absolute;
    margin-top: 13px;
}
</style>
{/literal} 

<div class="block-admin">
	<h3>Welcome to your Admin</h3>
	<div class="content-user-block">
		<div class="img-user">
			<img id="profile_image" src="{if $image_uploaded}/profile_images/{$user_id}.jpg{else}/images/no_profile_pic.jpg{/if}" alt="" width="146px" height="147px">
            <form id="form_upload_profile" action="/profile_upload.php" enctype="multipart/form-data" method="POST">
                <input type="file" name="profile_image"> <br>
                <span class="error" id="profile_image_error"></span>
                <input type="submit" name="submit" value="Upload Profile Picture" class="submit">
            </form>
		</div>
		<div class="info-user">
			<p><span class="text-1">{$lang.labelName}</span><span class="text-2">{$smarty.session.UserName}</span></p>
			<p><span class="text-1">{$lang.labelNickName}</span><span class="text-2">{$smarty.session.NickName}</span></p>
			<p><span class="text-1">{$lang.labelEmail}</span><span class="text-2">{$smarty.session.email|truncate:25}</span></p>
			<p><span class="text-1">{$lang.labelCountry}</span><span class="text-2">{$smarty.session.CountryName}</span></p>
			<p><span class="text-1">{$lang.labelState}</span><span class="text-2">{$smarty.session.State}</span></p>
			<p><span class="text-1">{$lang.labelCity}</span><span class="text-2">{$smarty.session.Suburb}</span></p>
			<a href="{$soc_https_host}soc.php?cp=edit_customers_geton" target="_self" class="blueButt">Update my details / change my password</a>
		</div>
		<div class="block-message">
			<div class="block-message-line1">
				<img src="images/icon-mess-1.png" alt="" width="22px" height="22px">
				<a href="./soc.php?cp=customers_geton_alerts">I’m a fan of <span class="st-text-1">{$req.emailAlert}</span> stores</p>
			</div>
			<div class="block-message-line1">
				<img src="images/icon-mess-2.png" alt="" width="13px" height="17px">
				<a href="./soc.php?cp=purchase">Past Receipts</a>
			</div>
		</div>
	</div>
</div>
<div class="clear"></div>
<div class="block-fan-frenzy">
    <div class="content-fan-frenzy">
        <div class="fan-frenzy-logo"></div>
        <div class="fan-frenzy-graphic">
            <img src="images/fan-frenzy-graphic.png" alt="">
        </div>
        <p class="text-content-top">
            {$fanfrenzy_text}
        </p>
        <div class="button-top">
            <div class="btn-enter"><a href="/entry">Enter Competition</a></div>
            <div class="btn-view"><a href="/fanfrenzy">View All Entries</a></div>
        </div>
        <div class="clear"></div>
        {if $display} 
            <div class="entries-for-my">
                <ul>
                    <li class="active">My Entries</li>
                </ul>
            </div>
	        <div class="clear"></div>
	        <div class="block-gallery">
	            {php}view_photos_customer();{/php} 
	            <div class="clear"></div>
	        </div>
	        <div class="clear"></div>
        {/if}
    </div>
</div>




