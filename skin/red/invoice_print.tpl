{literal}
<style type="text/css">
body{text-algin:center;margin:0 auto;}
 .hittable{
	 text-align:center;
 }
 .hittable th{
	 background-color:#9E99C1;
	 color: #FFF;
	 font-weight:bold;
	 height:23px;
	 font-size:12px;
 }
 .hittable th a{
	 color:#FFF;
	 font-weight:bold;
 }
 .hittable td{
	 border-right:1px solid #9E99C1;
	 border-bottom:1px solid #9E99C1;
	 height:23px;
	 font-size:12px;
 }
 .hittable td.firsttd{
	  border-left:1px solid #9E99C1;
 }
 .hittable th.endth{
	 background-color:#9E99C1;
	 border-right:1px solid #9E99C1;
 }
</style>
{/literal}
<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
{literal}
<style type="text/css">
#salehistory{width:950px;margin:auto;}
body{text-align:center;background:none;}
</style>
{/literal}
<br />
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
    	<td width="5%"></td>
    	<th width="40%" align="left" style="background:#9E99C1;color:white;font-weight:bold;padding-left:5px;">
        Order #{$req.order.ref_id}
        </td>
    	<th width="10%" align="left" style="background:#9E99C1;color:white;font-weight:bold;padding-left:5px;">        
    	<th width="40%" align="center" style="background:#9E99C1;color:white;font-weight:bold;padding-left:5px;">www.socexchange.com.au        
    	<td width="5%"></td>
    </tr>
	<tr>
    	<td width="5%"></td>
    	<td colspan="3" align="left">Order Date: {$req.order.orderDate}</td>
    	<td width="5%"></td>
    </tr>
	<tr>
	  <td colspan="5">&nbsp;</td>
  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td valign="top"><table  cellpadding="0"  cellspacing="0" width="100%" class="hittable">
	    <tr>
	      <th colspan="2" background="#9E99C1">Buyer Details</th>
        </tr>
	    <tr>
	      <td align="left" class="firsttd">&nbsp;Buyer:</td>
	      <td>{$req.buyer.bu_nickname}</td>
        </tr>
	    <tr>
	      <td align="left" class="firsttd">&nbsp;Buyer's Email:</td>
	      <td>{$req.buyer.bu_email}&nbsp;</td>
        </tr>
	    <tr>
	      <td align="left" class="firsttd">&nbsp;Phone:</td>
	      <td>{if $req.buyer.bu_phone ne '0' and $req.buyer.bu_phone ne ''}{$req.buyer.bu_phone}{else}-{/if}</td>
        </tr>
         <tr>
            <td align="left" class="firsttd">&nbsp;Shipping Method:</td>
            <td>{if $req.order.shipping_method ne ''}{$req.order.shipping_method}{else}-{/if}&nbsp;</td>
        </tr>
         <tr>
            <td align="left" class="firsttd">&nbsp;Payment Method:</td>
            <td>{if $req.order.description ne ''}{$req.order.description}{else}-{/if}&nbsp;</td>
        </tr>
     </table></td>
	  <td>&nbsp;</td>
	  <td valign="top"><table  cellpadding="0"  cellspacing="0" width="100%" class="hittable">
	    <tr>
	      <th colspan="2" background="#9E99C1">Seller Details</th>
        </tr>
	    <tr>
	      <td align="left" class="firsttd">&nbsp;Seller:</td>
	      <td>{$req.seller.bu_nickname}</td>
        </tr>
	    <tr>
	      <td align="left" class="firsttd">&nbsp;Seller's Email:</td>
	      <td>{$req.seller.bu_email}</td>
        </tr>
	    <tr>
	      <td align="left" class="firsttd">&nbsp;Phone:</td>
	      <td>{$req.seller.bu_phone}</td>
        </tr>
	    <tr>
	      <td align="left" class="firsttd">&nbsp;Seller's Website:</td>
	      <td>{$req.seller.bu_name}&nbsp;</td>
        </tr>
      </table></td>
	  <td>&nbsp;</td>
  </tr>
	<tr>
        <td colspan="5">&nbsp;</td>
    </tr>
	<tr>
    	<td width="5%"></td>
        <td colspan="2">
          <table  cellpadding="0"  cellspacing="0" width="100%" class="hittable">
            <tr>
              <th colspan="2" background="#9E99C1">Order Detail</th>
            </tr>
            {if $req.order.attribute eq '0'}
            <tr>
                <td align="left" class="firsttd">&nbsp;{$req.product.item_name}&nbsp;x&nbsp;{$req.order.month}</td>
                <td>&nbsp;${$req.order.productPrice}</td>
            </tr>
            {elseif $req.order.attribute eq '5'}
               	{foreach from=$req.product_list item=p}
                <tr>
                    <td align="left" class="firsttd">&nbsp;{$p.item_name}&nbsp;x&nbsp;{$p.quantity}</td>
                    <td>&nbsp;${$p.amount}</td>
                </tr>
                {/foreach}
            {/if}
            <tr>
                <td align="left" class="firsttd">&nbsp;Shipping</td>
                <td>&nbsp;${if $req.order.shipping_cost > 0}{$req.order.shipping_cost|number_format:2}{else}0.00{/if}</td>
            </tr>
            <tr>
              <td align="left" class="firsttd">&nbsp;Total</td>
              <td>&nbsp;${$req.order.amount|number_format:2}</td>
            </tr>
            </table>
          </td>
        <td>&nbsp;</td>
        <td width="5%"></td>
    </tr>
    <tr>
    	<td colspan="5" align="center">&nbsp;</td>
	</tr>
    <tr>
    	<td colspan="5" align="center">&nbsp;<a href="javascript:window.close()">Close Window</a></td>
	</tr>
</table>


<script type="text/javascript">window.print();</script>
