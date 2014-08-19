<div id="food_report">
	{literal}
	<style type="text/css">
	.sport p{line-height:20px; padding-top:20px;}
	</style>
	{/literal}
	{literal}
	<script type="text/javascript">
	function checkSubForm(obj) {
		var errors 	=	'';
		if(obj.email.value == '') {
			alert('Email Address is required.');
			obj.email.focus();
			return false;
		}
		if(!ifEmail(obj.email.value,false)) {
			alert('Email Address is invalid.');
			obj.email.focus();
			return false;
		}
		
		return true;
	}

	function ifEmail(str,allowNull){
		if(str.length==0) return allowNull;
		i=str.indexOf("@");
		j=str.lastIndexOf(".");
		if (i == -1 || j == -1 || i > j) return false;
		return true;
	}

	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
	</script>
	{/literal}
	<div id="cms">
		<h2 class="cms-header">The Fresh Produce Report<a href="javascript:history.go(-1)">Back</a></h2>
		<div class="content">
			{$req.aboutPage}
			
			<br />
			<strong>
				<span lang="EN-US" style="font-size:11.0pt;font-family:Calibri; mso-bidi-font-family:Calibri;color:#393939">
					Happy Shopping, 
					<br>
					Franco
				</span>
			</strong>
			<br />
			<br />
			<div class="cms-box">
				<div class="cms-box-l">
					<div style="text-align: left; ">
						<img width="190" height="157" border="0" src="/skin/red/images/foodwine/author.jpg" alt="">
					</div>
				</div>
				<div class="cms-box-r">
					<h2>About the author of this article...</h2>
					<span style="margin-top: 4px; width: 80px; float: left">
					<fb:like href="http://socexchange.com.au/soc.php?cp=report" send="false" width="140" show_faces="false" font="arial" data-layout="button_count"></fb:like>
					</span>
					<span style="margin-top: 4px; width: 80px; float: left; margin-left: 15px">
					<a class="twitter-share-button" href="https://twitter.com/share" data-lang="en" data-via="Food Marketplace" data-url="http://socexchange.com.au/soc.php?cp=report">Tweet</a>
					</span>
					<p>
						<span class="title">Name</span>
						<span>
						Franco Lagudi <br>
						</span>
					</p>
					<b>
					<br>
					</b>
					<p>
						<span class="title">About</span>
						<span>
						Franco is a 3rd generation fruit & vegetable wholesaler and retailer - supplying hundreds of fresh food & grocery retailers, including the major national and state chains. He is in the business of supplying and advising retailers on fruit and vegetable price movements, quality and quantity throughout Australia. With his family, he owns and operates two fresh food retail stores: Harris Farm Markets in Sydney's Edgecliff and Mother Nature's Top Fruit Market in Coffs Harbour. His long days usually start at 1:30am and, yes, he wishes he studied harder at school. <br>
						</span>
					</p>
					<p>
					</p>
					<p>
						<span>Left - Francesco Lagudi at the family fruit shop in Bondi Junction, Circa 1945.</span>
						<span>Right - Grandson Franco Lagudi, 2012</span>
					</p>
				</div>
			</div>
			
			<div class="clear">
			</div>
			<div class="cms-email">
				<div class="email-text">
					Never miss another Fresh Produce Report. Enter your email address to receive a reminder when a new Fresh Produce Report is posted.
				</div>
				<div class="email-form">
					<form action="/soc.php?cp=reportsubscribe" name="subscribeform">
						<input type="hidden" name="cp" value="reportsubscribe">
						<h2>Email Address</h2>
						<p class="input">
							<input type="text" name="email">
						</p>
						<p class="button">
							<input type="image" src="/skin/red/images/foodwine/report-subscribe.jpg">
						</p>
					</form>
				</div>
			</div>
			<div class="clear"> </div>
			<p class="msg">{$msg}</p>
			<br />
			<br />
			<p class="x_MsoNormal" style="margin-top: 0cm; margin-right: 0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size: 12pt; font-family: 'Times New Roman'; ">
				<span style="font-size: xx-small; ">
				<i>
					<font face="Arial">
						<span style="font-family: Arial; ">Disclaimer: Commentary and other materials posted on our site are not intended to amount to advice on which reliance should be placed. We therefore disclaim all liability and responsibility arising from any reliance placed on such materials by any visitor to our site, or by anyone who may be informed of any of its contents</span>
					</font>
				</i>
				</span>
			</p>
		</div>
		<div class="cms-footer"><img src="/skin/red/images/report_footer.jpg" alt="" /></div>
	</div>
</div>