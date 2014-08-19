{if $req.display eq ''}
	{if !$req.nofull }
	<!--link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" /-->
	<div id="ajaxmessage" class="text" style="text-align:center;color:red;" ></div>
	<form id="mainForm" name="mainForm" method="post" action="" onSubmit="javascript: if(deleteList('offerId[]', '{$lang.pub_clew.pleaseselect}', '{$lang.pub_clew.delete}')) xajax_offerDelete(xajax.getFormValues('mainForm')); return false;">
	<div id="tabledatalist">
	{/if}
	<table width="100%" border="0" cellpadding="2" cellspacing="1">
	<tr>
	<td width="50" height="35" align="center" class="purpleTitle">#</td>
	<td width="120" class="purpleTitle">{$lang.obo.ttBuyer}</td>
	<td width="120" class="purpleTitle">Coupon Code</td>
	<td width="210" class="purpleTitle">{$lang.obo.productName}</td>
	<td width="140" class="purpleTitle">{$lang.obo.offer}</td>
	<td width="200" class="purpleTitle">{$lang.obo.date}</td>
	<td width="100" align="center" class="purpleTitle">{$lang.tit.operation}</td>
	</tr>
	{foreach name=offer from=$req.offer.list item=l}
	<tr height="55">
	  <td bgcolor="#FFFFFF"><input type="checkbox" id="offerId[]" name="offerId[]" value="{$l.id}" style="border:0px;"/>{math equation="(x-1)*y+z" x=$req.offer.pageno y=$req.offer.perpage z=$smarty.foreach.offer.iteration}</td>
	  <td bgcolor="#FFFFFF"><a href="mailto:{$l.bu_email}">{$l.buyerNickname|wordwrap:15:"<br>\n"}</a><br /></td>
	  <td bgcolor="#FFFFFF">{if $l.coupon_code && $l.accpet neq '2'}{if $l.coupon_used}Used{else}New{/if}{else}NAN{/if}</td>
	  <td bgcolor="#FFFFFF">{$l.productName|wordwrap:25:"<br>\n"}</td>
	  <td bgcolor="#FFFFFF"><strong>{$lang.obo.quantity}:</strong> {$l.quantity}<br />
		<strong>{$lang.obo.offer}:</strong> {math equation ="x"  x=$l.offer format="$%.2f"}<br />
		<strong>{$lang.obo.total}:</strong> {math equation = "x * y + z * y" x=$l.offer y=$l.quantity z=$l.postage format="$%.2f"}<br /></td>
	  <td bgcolor="#FFFFFF"><strong>{$lang.obo.datec}:</strong>&nbsp; &nbsp;{$l.datec}<br />
		<strong>{$lang.obo.datep}:</strong> {if $l.datep ne '01/01/1970'}{$l.datep}{/if}<br />
		<strong>{$lang.obo.dateReview}:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{if $l.dateReview ne '01/01/1970'}{$l.dateReview}{/if}</td>
	  <td align="center" bgcolor="#FFFFFF" class="lineHeight">
	  	{if $l.accpet > 0}<div style="padding-bottom:5px;"><samp style="color:#990000;">{$lang.obo.state[$l.accpet]}<br />
		<a href="javascript:xajax_offerViewEmail('{$l.id}');void(0);" >{$lang.obo.ttEmail}</a><br /></samp></div>{/if}
		{if $l.dateReview neq '01/01/1970' && $l.accpet > 0}<samp><a href="javascript:xajax_offerViewReview('{$l.id}');void(0);"> {$lang.obo.viewReview}</a></samp><br />
		{/if}
		{if $l.accpet eq 0}<samp><samp><a href="#"><img src="/skin/red/images/buttons/bu-accept.jpg" border="0" align="absmiddle" onclick="javascript:{literal}if(confirm('Are you sure to accept this offer?')){{/literal}xajax_offerAccept('{$l.id}',true);{literal}}{/literal}"/></a></samp><br />
		<a href="#"><img src="/skin/red/images/buttons/bu-notaccept.jpg" border="0" align="absmiddle" onclick="{literal}if(confirm('Are you sure to reject this offer?')){{/literal}xajax_offerAccept('{$l.id}');{literal}}{/literal}" style="padding-top:5px;"/></a></samp>{/if}</td>
	</tr>
	{foreachelse}
	<tr>
	  <td height="35" bgcolor="#FFFFFF">&nbsp;</td>
	  <td colspan="6" align="center" bgcolor="#FFFFFF" class="publc_clew">{$lang.pub_clew.nothing}</td>
	  </tr>
	{/foreach}
	<tr>
	  <td height="30" colspan="7" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="13%" height="35" valign="middle"><input name="all" type="checkbox" value="" style="border:0px;" onclick="javascript: selectAll('offerId[]',this);"/> {$lang.pub_clew.selectall} </td>
            <td width="6%" valign="middle"><input name="image" type="image" style="border:0px; padding:0px; margin:0px;" src="/skin/red/images/buttons/or-delete.gif"/></td>
            <td width="81%" align="center">{$req.offer.links.all} </td>
          </tr>
        </table></td>
	  </tr>
	<tr>
	  <td height="30" colspan="7" align="left" bgcolor="#FFFFFF"><a href="/soc.php?cp=lookupreview" style="font-weight:bold;">Lookup Buyer Reviews</a></td>
	  </tr>
	</table>

	{if !$req.nofull}
	</div>
	<input name="pageno" type="hidden" id="pageno" value="{$req.offer.pageno}"/>
	</form>
	{/if}

{elseif $req.display eq 'view'}
    <table width="100%" border="0" cellpadding="2">
      <tr>
        <td width="22%" height="25">&nbsp;</td>
        <td width="68%">&nbsp;</td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td height="25" align="right" valign="middle">{$lang.obo.ttContact} : </td>
        <td valign="middle">{$req.offer.contact}        </td>
        <td valign="middle">&nbsp;</td>
      </tr>
	  {if $req.offer.email neq ''}
      <tr>
        <td height="25" align="right" valign="middle">{$lang.obo.ttEmail} : </td>
        <td valign="middle">{$req.offer.email}        </td>
        <td valign="middle">&nbsp;</td>
      </tr>
	  {/if}
	  {if $req.offer.phone neq ''}
      <tr>
        <td height="25" align="right" valign="middle">{$lang.obo.ttPhone} : </td>
        <td valign="middle">{$req.offer.phone}        </td>
        <td valign="middle">&nbsp;</td>
      </tr>
	  {/if}
      <tr>
        <td height="60" align="right" valign="top">{$lang.obo.ttComment} : </td>
        <td valign="top">{$req.offer.comment|nl2br}</td>
        <td valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="25" align="right" valign="middle">{$lang.obo.dateReview} : </td>
        <td valign="middle">{$req.offer.dateReview|nl2br}</td>
        <td valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td height="25" align="right">&nbsp;</td>
        <td><a href="javascript:xajax_offerViewReview('','back');oid(0);"><img src="/skin/red/images/buttons/or-back.gif"/></a></td>
        <td>&nbsp;</td>
      </tr>
    </table>

{/if}