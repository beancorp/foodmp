{literal}
<script>new YAHOO.Hack.FixIESelectWidth('state_subburb');</script>
{/literal}
<form action="soc.php?cp=statepage" id="statesearch" class="st-connecticut" method="get">
	<input type="hidden" name="cp" value="statepage" />
	<div style="float:left; width: 180px; padding-top: 30px; padding-left: 8px; font-size:14px; font-weight: 900; color:#3c3380;">{$state_fullname} Homepage</div>
	<div style="float:right; width:300px; margin-right:10px;">
	<fieldset>
		<h2 id="location">Enter your location and find your local sellers</h2>
	</fieldset>
	<fieldset>
		<ol>
			<li>
				{literal}
				<script type="text/javascript">
				<!--//
				function switchState(state)
				{
					location.href = 'soc.php?cp=statepage&state_name=' + state;
				}
				//-->
				</script>
				{/literal}
				<select name="state_name" class="state" onchange="switchState(this.value)">
				{foreach from=$req.states item=state}
				<option value="{$state.state}"{$state.selected}>{$state.state}</option>
				{/foreach}
				</select>
			</li>
			<li>
				<span class="select-box">
				<select name="selectSubburb" class="region" id="state_subburb">
				<option value="">All</option>
				{foreach from=$req.cities item=city}
				<option value="{$city.bu_suburb}.{$city.zip}"{$city.selected} title="{$city.bu_suburb}">{$city.bu_suburb}</option>
				{/foreach}
				</select>
				</span>
			</li>
			<li>
				<select name="selectDistance" class="radius">
				<option value="">All</option>
				{foreach from=$req.distance item=distance}
				<option value="{$distance}"{if $selectDistance eq $distance } selected="selected" {/if}>within {$distance} km</option>
				{/foreach}
				</select>
			</li>
		</ol>
	</fieldset>
	<fieldset class="searchlocation">
		<input src="skin/red/images/bu-search.gif" type="image" />
	</fieldset>
	</div>
	</form>