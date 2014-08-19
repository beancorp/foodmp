<div> &nbsp;<strong>Control MENU</strong>  
<input class="hbutton" type="button" value="{$lang.login.lb_logout}" onclick="location.href='?cp=logout'"/></div>
<ul>
<li>Configuration</li>
	<ul>
	<li {$req.Menu.paypal}><a href="?act=main&cp=paypal" {$req.Menu.paypal}>Paypal</a></li>
	<li {$req.Menu.facelikerace}><a href="?act=main&cp=facelikerace" {$req.Menu.facelikerace}>Facebook Sprints</a></li>
    <li {$req.Menu.eway}><a href="?act=main&cp=eway" {$req.Menu.eway}>eWay</a></li>
	</ul>
</ul>
<ul>
<li>CMS</li>
	<ul>
	<li {$req.Menu.cms}><a href="?act=main&cp=cms" {$req.Menu.cms}>CMS Pages</a></li>
	<li {$req.Menu.help}><a href="?act=main&cp=help" {$req.Menu.help}>Help Pages</a></li>
	<li {$req.Menu.jokes}><a href="?act=main&cp=jokes" {$req.Menu.jokes}>Jokes Pages</a></li>
	<li {$req.Menu.freshreport}><a href="?act=main&cp=freshreport" {$req.Menu.freshreport}>Fresh Produce Report</a></li>
	<li {$req.Menu.freshreportrecords}><a href="?act=main&cp=freshreportrecords" {$req.Menu.freshreportrecords}>Fresh Produce Report Records</a></li>
	</ul>
</ul>
<ul>
<li>Fan Frenzy</li>
	<ul>
	<li><a href="?act=fanfrenzycontent">Fan Frenzy</a></li>
	<li><a href="?act=photos">Administrate Photos</a></li>
	<li><a href="?act=fanfrenzy_template_email">Administrate Template Email</a></li>
	<li><a href="?act=fanfrenzy_grand_list">Grand List</a></li>
	</ul>
</ul>
<ul>
	<li>User Management</li>
	<ul>
	<li {$req.Menu.customer}><a href="?act=main&cp=customer" {$req.Menu.customer}>Customer Management</a></li>
	<li {$req.Menu.store}><a href="?act=store" {$req.Menu.store}>Manage Store</a></li>
        <li {$req.Menu.viewexp}><a href="?act=store&cp=viewexp" {$req.Menu.viewexp}>View Expired Users</a></li>
        <li {$req.Menu.stats}><a href="?act=store&cp=stats" {$req.Menu.stats}>Member Stats</a></li>
        <!--<li {$req.Menu.user_from}><a href="?act=store&cp=user_from" {$req.Menu.user_from}>User Report</a></li>-->
        <li {$req.Menu.freelisting}><a href="?act=store&cp=freelisting" {$req.Menu.freelisting}>Free Listing</a></li>
        <li {$req.Menu.duplicateListing}><a href="?act=store&cp=duplicateListing" {$req.Menu.duplicateListing}>Duplicate Listing</a></li>
	</ul>
</ul>
<ul>
	<li>Export</li>
	<ul>
	<li><a href="?act=store&cp=export&status=1&customer_type=seller&attribute=5">Paid Retailers</a></li>
	<li><a href="?act=store&cp=export&status=0&customer_type=seller&attribute=5">Trial Retailers</a></li>
	<li><a href="?act=store&cp=export&status=1&customer_type=listing&attribute=5">Paid Listings</a></li>
	<li><a href="?act=store&cp=export&status=0&customer_type=listing&attribute=5">Unpaid Listings</a></li>
	<li><a href="?act=store&cp=export&status=1&customer_type=buyer">Consumers</a></li>
	</ul>
</ul>
<ul>
	<li>Business Management</li>
	<ul>
	<li {$req.Menu.promotion}><a href="?act=promotion" {$req.Menu.promotion}>Promotion Code</a></li>
	<li {$req.Menu.report}><a href="?act=report" {$req.Menu.report}>Report for Competition</a></li>
	<li {$req.Menu.facelike}><a href="?act=facelike" {$req.Menu.facelike}>Report for Facebook Sprints</a></li>
	</ul>
</ul>
<ul>
	<li>Email Management</li>
	<ul>
	<li {$req.Menu.storemailreport}><a href="?act=email&cp=storemailreport" {$req.Menu.storemailreport}>Store Wise Email Subscription Report</a></li>
	<li {$req.Menu.reportsubscribe}><a href="?act=email&cp=reportsubscribe" {$req.Menu.reportsubscribe}>Fresh Produce Report Subscriber</a></li>
	<!--li {$req.Menu.cusmailreport}><a href="?act=email&amp;cp=cusmailreport">Customer Wise Email Subscription</a> </li>
	<li {$req.Menu.export}><a href="?act=email&amp;cp=export">Email Subscribers Excel Version </a></li-->
	</ul>
</ul>
<ul>
	<li>Message Center</li>
	<ul>
	<li {$req.Menu.all}><a href="?act=msg&amp;cp=all" {$req.Menu.all}>All Stores</a></li>
	<li {$req.Menu.selected}><a href="?act=msg&amp;cp=selected" {$req.Menu.selected}>Selected Store</a></li>
	<li {$req.Menu.individual}><a href="?act=msg&amp;cp=individual" {$req.Menu.individual}>Individual Store</a></li>
	<li {$req.Menu.pre}><a href="?act=msg&amp;cp=pre" {$req.Menu.pre}>Previous Message</a></li>
	<li {$req.Menu.sendreminder}><a href="?act=msg&cp=sendreminder" {$req.Menu.sendreminder}>Send Fresh Produce Report Reminder</a></li>
	</ul>
</ul>
<ul>
	<li>Payment Management</li>
	<ul>
	<li {$req.Menu.catadset}><a href="?act=payment&amp;cp=commission" {$req.Menu.commission}>Commission Setting</a></li>
	<li {$req.Menu.daterep}><a href="?act=payment&amp;cp=daterep" {$req.Menu.daterep}>Date wise Reports</a></li>
	<li {$req.Menu.storerep}><a href="?act=payment&amp;cp=storerep" {$req.Menu.storerep}>Store wise Reports</a></li>
    <li {$req.Menu.purchase}><a href="/admin/?act=payment&cp=purchase" {$req.Menu.purchase}>Purchase Records</a></li>
	<!--li><a href="?act=payment&amp;cp=catrep">Store/category wise Reports</a></li>
	<li><a href="?act=payment&amp;cp=adrep">Ad. wise Reports</a></li>
	<li><a href="?act=payment&amp;cp=giftrep">Gift wise Reports</a></li>
	<li><a href="?act=payment&amp;cp=refrep">Referrals Reports</a></li-->
	<li><a href="?act=commissions">View Commissions</a></li>
	</ul>
</ul>
<ul>
	<li>State Banner System</li>
	<ul>
	<li {$req.Menu.allbanner}><a href="?act=adv&amp;cp=allbanner" {$req.Menu.allbanner}>All States/ States/ Default Banner</a></li>
	<!--<li {$req.Menu.state}><a href="?act=adv&amp;cp=state" {$req.Menu.state}>State Banner</a></li>-->
	</ul>
</ul>
<ul>
	<li>Reviews Management</li>
	<ul>
	<li {$req.Menu.expset}><a href="?act=review&amp;cp=expset" {$req.Menu.expset}>Expiry Setting</a></li>
	<li {$req.Menu.details}><a href="?act=review&amp;cp=details" {$req.Menu.details}>Reviews Details</a></li>
	</ul>
</ul>
<ul>
<li>Product</li>
	<ul>
	<li><a href="?act=imagelibrary">Image Library</a></li>
	<li {$req.Menu.cat}><a href="?act=pro&amp;cp=cat" {$req.Menu.cat}>Category</a></li>
	<li {$req.Menu.catadset}><a href="?act=pro&amp;cp=catadset" {$req.Menu.catadset}>Category ad set</a></li>
	<li {$req.Menu.catartset}><a href="?act=pro&amp;cp=catartset" {$req.Menu.catartset}>Category article set</a></li>
	<li {$req.Menu.catfeat}><a href="?act=pro&amp;cp=catfeat" {$req.Menu.catfeat}>Featured Categories </a></li>
	<li {$req.Menu.season}><a href="?act=pro&amp;cp=season" {$req.Menu.season}>Season Product </a></li>
	</ul>
</ul>

<ul>
	<li>Race</li>
	<ul>
	<li {$req.Menu.partner_site}><a href="?act=race&cp=partner_site" {$req.Menu.partner_site}>Partner Site</a></li>
	<li {$req.Menu.question}><a href="?act=race&cp=question" {$req.Menu.question}>Question</a></li>
	<li {$req.Menu.answer}><a href="?act=race&cp=answer" {$req.Menu.answer}>Answer</a></li>
	<li {$req.Menu.race_list}><a href="?act=race&cp=race_list" {$req.Menu.race_list}>Race Report</a></li>
	<li><a href="?act=leaderboard">Leaderboard</a></li>
	</ul>
</ul>

{if $req.current_country eq '223'}
<ul>
	<li>Referrals</li>
	<ul>
	<li {$req.Menu.ref_list}{$req.Menu.ref_user}><a href="?act=referral&cp=ref_list" {$req.Menu.ref_list}{$req.Menu.ref_user}>Referrer Report</a></li>
	<li {$req.Menu.ref_payment}><a href="?act=referral&cp=ref_payment" {$req.Menu.ref_payment}>Payment Requested Report</a></li>
	<li {$req.Menu.ref_payReport}><a href="?act=referral&cp=ref_payReport" {$req.Menu.ref_payReport}>Payment Report</a></li>
	<li {$req.Menu.ref_report}><a href="?act=referral&cp=ref_report" {$req.Menu.ref_report}>Referrer ID Report</a></li>
	<li {$req.Menu.downlist}><a href="?act=referral&cp=downlist" {$req.Menu.downlist}>Export Report</a></li>
	<li {$req.Menu.ref_config}><a href="?act=referral&cp=ref_config" {$req.Menu.ref_config}>Referral Configuration</a></li>
	</ul>
</ul>
{/if}
<ul>
<li {$req.Menu.pass}><a href="?act=main&amp;cp=pass" {$req.Menu.pass}>Change Password</a></li>
</ul>
