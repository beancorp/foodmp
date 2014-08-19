{if $search_type eq 'estate'}
	<div style=" margin-bottom:15px;"><img src="/skin/red/images/estate/Estate_right.jpg" border="0" usemap="#NewEstate" />
<map name="NewEstate" id="NewEstate">
<area shape="rect" coords="94,119,179,144" href="https://socexchange.com.au/soc.php?act=signon&attribute=1" />
</map>
	{elseif $search_type eq 'auto'}
	<div style=" margin-bottom:15px;"><img src="/skin/red/images/vehicle/Vehicle_right.jpg" border="0" usemap="#NewVehicle" />
<map name="NewVehicle" id="NewVehicle">
<area shape="rect" coords="97,120,182,145" href="https://socexchange.com.au/soc.php?act=signon&attribute=2" />
</map>
	{elseif $search_type eq 'job'}
	<div style=" margin-bottom:15px;"><img src="/skin/red/images/job/Job_right.jpg" border="0" usemap="#NewCareer" />
<map name="NewCareer" id="NewCareer">
<area shape="rect" coords="65,112,152,141" href="https://socexchange.com.au/soc.php?act=signon&attribute=3" />
</map>
	{/if}
</div>

{include file="whysoc_sidebar.tpl"}