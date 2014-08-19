{literal}
<style type="text/css">
.seletcs{
	width:168px;
}
.states{
	padding-left:10px;
}
.tabletop	{height:30px;
			 text-align:center;
			 border-left:2px solid #FFFFFF;
			 background:#66ACCF;
			 font-size:12px;font-weight:bold;}
.tablelist { height:22px;
			 text-align:center;
			 background-color:#eeeeee; 
			 border-left:2px solid #FFFFFF;
			 border-bottom:1px solid #FFFFFF;
		   }
</style>
{/literal}
<table cellpadding="0" cellspacing="0" align="center" style="margin: auto;">
<colgroup>
	<col width="250px" />
    <col width="200px" />
    <col width="200px" />
</colgroup>
	<tr><td class="tabletop">User Type</td>
    	<td class="tabletop">Active</td>
    	<td class="tabletop">Expired</td>
    </tr>
    <tr>
    	<td class="tablelist">Sellers</td>
        <td class="tablelist">{$req.seller.0}</td>
        <td class="tablelist">{$req.seller.1}</td>
    </tr>
     <tr>
    	<td class="tablelist">Buyers</td>
        <td class="tablelist">{$req.buyer}</td>
        <td class="tablelist">N/A</td>
    </tr>
     <tr>
    	<td class="tablelist">Car Seller</td>
        <td class="tablelist">{$req.car.0}</td>
        <td class="tablelist">{$req.car.1}</td>
    </tr>
     <tr>
    	<td class="tablelist">Property Seller</td>
        <td class="tablelist">{$req.estate.0}</td>
        <td class="tablelist">{$req.estate.1}</td>
    </tr>
     <tr>
    	<td class="tablelist">Job Agent</td>
        <td class="tablelist">{$req.jobagent.0}</td>
        <td class="tablelist">{$req.jobagent.1}</td>
    </tr>
     <tr>
    	<td class="tablelist">Job Seekers(paid)</td>
        <td class="tablelist">{$req.jobseeker.0}</td>
        <td class="tablelist">{$req.jobseeker.1}</td>
    </tr>
     <tr>
    	<td class="tablelist">Job Seekers(free)</td>
        <td class="tablelist">{$req.jobs}</td>
        <td class="tablelist">N/A</td>
    </tr>
     <tr>
    	<td class="tablelist">Food & Wine Seller</td>
        <td class="tablelist">{$req.foodwine.0}</td>
        <td class="tablelist">{$req.foodwine.1}</td>
    </tr>
</table>