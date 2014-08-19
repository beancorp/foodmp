<html>
<head>
<title>{$goods} List</title>
{literal}
<style>
	.SplHeadingBar1{
		font-family:Arial, Helvetica, sans-serif;
		font-weight:bold;
		font-size:21px;
		color:#FFFFFF;
		margin-left:8px;
		margin-top:4px;
		margin-bottom:4px;
	}
	.td {font-family:Arial, Helvetica, sans-serif;}
</style>
{/literal}
</head>
<body style="text-align:left;">
Dear {$customer},<br/>
	<table width="585">
		<tbody>
		<tr>
			<td align="center" colspan="2"></td> 
		</tr>
		<tr>
        	<td colspan="2">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
          			<tbody>
					<tr>
						<td height="30" align="left" class="fix">
							<p class="businessName">Items alert from <a href="{$home_link}/{$bu_urlstring}">{$business}</a>.</p>
						</td>
					</tr>
					</tbody>
				</table>
			</td>
      	</tr>
		{if $procunsts gt 0}
		<tr>
			<td align="left" valign="top">
			    <table width="100%">
				{if $type eq 'store'}
				
					{foreach from=$products item=l}
					{if $k++%2 eq 1}
					<tr style="background-color:{if $y++%2 eq 1}#ddeef6{else}#ffffff{/if}">
						<td width="50" align="left" valign="top">
						<img height="50" width="50" src="{$home_link}{if $l.smallPicture neq ""}{$l.smallPicture}{else}skin/red/images/default-thumbnail.gif{/if}"/></td>
						<td align="left" width="240" valign="top">
						<small>
						<b>{$l.item_name|truncate:40:"..."}</b><br/>
						${$l.price|number_format} {$l.unit}<br/>
						{truncate content="`$l.description`" length="40"}
						</small>
						</td>
					{else}		
						<td width="50" align="left" valign="top">
						<img height="50" width="50" src="{$home_link}{if $l.smallPicture neq ""}{$l.smallPicture}{else}skin/red/images/default-thumbnail.gif{/if}"/>
						</td>
						<td align="left" width="240" valign="top">
						<small>
						<b>{$l.item_name|truncate:40:"..."}</b><br/>
						${$l.price|number_format} {$l.unit}<br/>
						{truncate content="`$l.description`" length="40"}
						</small>
						</td>
					</tr>
					{/if}
					{/foreach}
					{if $procunsts%2 eq 1}
						<td width="50" align="left" valign="top">&nbsp;</td>
						<td width="240">&nbsp;</td>
					</tr>
					{/if}
				{elseif $type eq 'estate'}
					
					{foreach from=$products item=l}
					{if $k++%2 eq 1}
					<tr style="background-color:{if $y++%2 eq 1}#ddeef6{else}#ffffff{/if}">
						<td width="50" align="left" valign="top">
						<img height="50" width="50" src="{$home_link}{if $l.smallPicture neq ""}{$l.smallPicture}{else}skin/red/images/default-thumbnail.gif{/if}"/></td>
						<td align="left" width="240" valign="top">
						<small>									
						<b>{$l.item_name|truncate:40:"..."}</b><br/>
						${$l.price|number_format}<br/>
						{truncate content="`$l.content`" length="40"}
						</small>
						</td>
					{else}		
						<td width="50" align="left" valign="top">
						<img height="50" width="50" src="{$home_link}{if $l.smallPicture neq ""}{$l.smallPicture}{else}skin/red/images/default-thumbnail.gif{/if}"/>
						</td>
						<td width="240" align="left" valign="top">
						<small>
						<b>{$l.item_name|truncate:40:"..."}</b><br/>
						${$l.price|number_format}<br/>
						{truncate content="`$l.content`" length="40"}
						</small>
						</td>
					</tr>
					{/if}
					{/foreach}
					{if $procunsts%2 eq 1}
						<td width="50" align="left" valign="top">&nbsp;</td>
						<td width="240">&nbsp;</td>
					</tr>
					{/if}
					
					
					
				{elseif $type eq 'auto'}
				
					{foreach from=$products item=l}
					{if $k++%2 eq 1}
					<tr style="background-color:{if $y++%2 eq 1}#ddeef6{else}#ffffff{/if}">
						<td width="50" align="left" valign="top">
						<img height="50" width="50" src="{$home_link}{if $l.smallPicture neq ""}{$l.smallPicture}{else}skin/red/images/default-thumbnail.gif{/if}"/></td>
						<td align="left" width="240" valign="top">
						<small>
						<b>{$l.item_name|truncate:40:"..."}</b><br/>
						${$l.price|number_format}<br/>
						{$l.year} {$lang.tt.year}
	  					{if $l.transmission neq ''}, 		
						{$lang.val.transmission[$l.transmission]}{/if}
					    {if $l.pattern neq ''},
						{$lang.val.pattern[$l.pattern]}{/if}
					    {if $l.color neq ''}, 
						{$lang.tt.color}: {$l.color}{/if}
						{if $l.kms neq ''}, {$l.kms}{$lang.tt.mls}{/if}
						</small>
						</td>
					{else}		
						<td width="50" align="left" valign="top">
						<img height="50" width="50" src="{$home_link}{if $l.smallPicture neq ""}{$l.smallPicture}{else}skin/red/images/default-thumbnail.gif{/if}"/>
						</td>
						<td align="left" width="240" valign="top">
						<small>
						<b>{$l.item_name|truncate:40:"..."}</b><br/>
						${$l.price|number_format}<br/>
						{$l.year} {$lang.tt.year} 
	  					{if $l.transmission neq ''}, 		
						{$lang.val.transmission[$l.transmission]}{/if}
					    {if $l.pattern neq ''},
						{$lang.val.pattern[$l.pattern]}{/if}
					    {if $l.color neq ''}, 
						{$lang.tt.color}: {$l.color}{/if}
						{if $l.kms neq ''}, {$l.kms}{$lang.tt.mls}{/if}
						</small>
						</td>
					</tr>
					{/if}
					{/foreach}
					{if $procunsts%2 eq 1}
						<td width="50" align="left" valign="top">&nbsp;</td>
						<td width="240">&nbsp;</td>
					</tr>
					{/if}
					
				{elseif $type eq 'job'}
					
					{foreach from=$products item=l}
					{if $k++%2 eq 1}
					<tr style="background-color:{if $y++%2 eq 1}#ddeef6{else}#ffffff{/if}">
						<td width="290" align="left" valign="top">
						<small>
						<b>{$l.item_name|truncate:40:"..."}</b><br/>
						{if $l.salaryMin eq $l.salaryMax}{if $l.salaryMin neq -2}${/if}{valueOfArray arrValue=$lang.val.min_salary value=$l.salaryMin}{elseif $l.salaryMin eq -2 }${valueOfArray arrValue=$lang.val.max_salary value=$l.salaryMax}{elseif $l.salaryMax eq -2 }${valueOfArray arrValue=$lang.val.min_salary value=$l.salaryMin}{else}${valueOfArray arrValue=$lang.val.min_salary value=$l.salaryMin} - ${valueOfArray arrValue=$lang.val.max_salary value=$l.salaryMax}{/if}
						<br/>
						{truncate content="`$l.content1`" length="40"}</small>
						</td>
					{else}		
						<td width="290" align="left" valign="top">
						<small>
						<b>{$l.item_name|truncate:40:"..."}</b><br/>
						{if $l.salaryMin eq $l.salaryMax}{if $l.salaryMin neq -2}${/if}{valueOfArray arrValue=$lang.val.min_salary value=$l.salaryMin}{elseif $l.salaryMin eq -2 }${valueOfArray arrValue=$lang.val.max_salary value=$l.salaryMax}{elseif $l.salaryMax eq -2 }${valueOfArray arrValue=$lang.val.min_salary value=$l.salaryMin}{else}${valueOfArray arrValue=$lang.val.min_salary value=$l.salaryMin} - ${valueOfArray arrValue=$lang.val.max_salary value=$l.salaryMax}{/if}
						<br/>
						{truncate content="`$l.content1`" length="40"}
						</small>
						</td>
					</tr>
					{/if}
					{/foreach}
					{if $procunsts%2 eq 1}
						<td width="290" align="left" valign="top">&nbsp;</td>
					</tr>
					{/if}
					
				{/if}
				</table>
			</td>
		</tr>
		{/if}
		</tbody>
	</table><br/>
Sincerely,<br/>
{if $email_regards}{$email_regards}{else}Food Marketplace Australia{/if}
</body>
</html>