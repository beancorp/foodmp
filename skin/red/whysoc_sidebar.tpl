<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
{include file='flashbanner.tpl'}<br/>
{if $search_type eq 'estate'}
	<div id="promo">
        
    	<h2 class="promo">Sample Websites</h2>
		
        <h3 class="promo">Mc Graw Hill</h3>
        <a href="/McGrawHill"><img src="/skin/red/images/whysoc/sample_estate_01.jpg" alt=""></a>

		<br>
        <br>
		
		<h3 class="promo">TJ Booker</h3>
        <a href="/TJBooker"><img src="/skin/red/images/whysoc/sample_estate_02.jpg" alt=""></a>

		<br>
        <br>
		
	</div>

{elseif $search_type eq 'auto'}
	<div id="promo">
        
    	<h2 class="promo">Sample Websites</h2>
		
        <h3 class="promo">City Auto</h3>
        <a href="/CityAuto"><img src="/skin/red/images/whysoc/sample_auto_01.jpg" alt=""></a>

		<br>
        <br>
		
		<h3 class="promo">All Traders Automobile</h3>
        <a href="/AllTradersAutomobile"><img src="/skin/red/images/whysoc/sample_auto_02.jpg" alt=""></a>

		<br>
        <br>
		
	</div>

{elseif $search_type eq 'job'}
	{if $newrightJob eq '1'}
		<div id="promo">
			
			<h2 class="promo">Sample Websites</h2>
			
			<h3 class="promo">High Achiever</h3>
			<a href="/highachiever"><img src="/skin/red/images/whysoc/sample_job_03.jpg" alt=""></a>
	
			<br>
			<br>
			
			<h3 class="promo">Oliviera Recrultment</h3>
			<a href="/OlivieraRecrultment"><img src="/skin/red/images/whysoc/sample_job_01.jpg" alt=""></a>
	
			<br>
			<br>
		</div>

	{else}
		<div id="promo">
			
			<h2 class="promo">Sample Websites</h2>
			
			<h3 class="promo">Oliviera Recrultment</h3>
			<a href="/OlivieraRecrultment"><img src="/skin/red/images/whysoc/sample_job_01.jpg" alt=""></a>
	
			<br>
			<br>
			
			<h3 class="promo">Executive Appointments</h3>
			<a href="/ExecutiveAppointments"><img src="/skin/red/images/whysoc/sample_job_02.jpg" alt=""></a>
	
			<br>
			<br>
			
		</div>
	{/if}
{else}
	<div id="promo">
        
    	<h2 class="promo">Sample Websites</h2>
        <h3 class="promo">Sandy's Castle</h3>
        <a href="/SandysCastle"><img src="/skin/red/images/sampleshop.jpg" alt=""></a>

		<br>
        <br>
        
    	<strong>More sample sites</strong><br>

        <ul class="sampleshops">
            <li><a href="/FurnitureSaleBeQuick">"Furniture Sale - Be Quick!" </a></li>
            <li><a href="/BensBasement">Ben's Basement</a> </li>
            <li><a href="/CindysCloset">Cindy's Closet</a> </li>
            <li><a href="/GOLFCRAZY">GOLF CRAZY</a> </li>
            <li><a href="/SWEATHEAVEN">SWEAT HEAVEN</a> </li>

            <!--li><a href="/GrandTerraceForSale">Grand Terrace For Sale.</a> </li-->
            <li><a href="/HomeSweetHome">Home Sweet Home.</a> </li>
            <li><a href="/KeepingTime">Keeping Time.</a> </li>
            <li><a href="/SarahsStyle">Sarah's Style</a> </li>

            <li><a href="/TheGoldStop">The Gold Stop.</a> </li>
            <li><a href="/TheGreenThumb">The Green Thumb</a> </li>
            <li><a href="/TheTrainingPit">The Training Pit </a></li>
            <li><a href="/TomsTV">Tom's TV</a> </li>
			<li><a href="/SandysCastle">Sandy's Castle</a> </li>
			
            <li><a href="/FAMILYFUN">FAMILY FUN</a> </li>
			<li><a href="/SampleStudentSite">Sample Student Site</a> </li>
			<li><a href="/PennysLane">Penny’s lane</a> </li>
            <!--li><a href="/CoolTreasures">Cool Treasures</a> </li>
			<li><a href="/JohnJenkins">John Jenkins</a> </li-->
        </ul>

	</div>
{/if}