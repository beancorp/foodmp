{literal}
<script type="text/javascript" src="/js/lightbox_plus.js"></script>
<script language="javascript">
	function checkamount(pid){
		var tmpprice = $('#tmp_price').val();
		$.post('/include/jquery_svr.php',
			   {svr:'checkthepayamount',pid:pid,amount:tmpprice},
			   function(data){
				 if(data=='0'){
					 if(tmpprice<=0){
					 	alert('Invalid Amount to gift.');
					 }else{
					 	alert('The maximum amount is '+FormatNumber($('#hid_price').val(),2)); 
					 }
					 $('#tmp_price').val(FormatNumber($('#price').val(),2));
				 }else{
					 if($('#payCal').attr('checked')&&$('input[name="payment_M"][@checked]').val()=='paypal'){
						if(parseFloat(tmpprice)!=0){
							tmpprice = FormatNumber(taxprice(tmpprice),2);
						}else{
							tmpprice = FormatNumber(tmpprice,2);
						}
					 }else if($('#googleCal').attr('checked')&&$('input[name="payment_M"][@checked]').val()=='googlecheckout'){
						 if(parseFloat(tmpprice)!=0){
							tmpprice = FormatNumber(taxpricegoogle(tmpprice),2);
						 }else{
							tmpprice = FormatNumber(tmpprice,2);
						 }
					 }else{
						tmpprice = FormatNumber(tmpprice,2);
					 }
					 $('#price').val(tmpprice);
					 $('#amount').val(tmpprice);
					 $('#total').html('$'+tmpprice);
				 }
			   });
	}
	function checksubmit(){
		var tmpprice = $('#tmp_price').val();
		if($('#payCal').attr('checked')&&$('input[name="payment_M"][@checked]').val()=='paypal'){
			if(parseFloat(tmpprice)!=0){
				tmpprice = FormatNumber(taxprice(tmpprice),2);
			}
		}else if($('#googleCal').attr('checked')&&$('input[name="payment_M"][@checked]').val()=='googlecheckout'){
			if(parseFloat(tmpprice)!=0){
				tmpprice = FormatNumber(taxpricegoogle(tmpprice),2);
			}
		}
		$('#price').val(tmpprice);
		$('#amount').val(tmpprice);
		$('#total').html('$'+FormatNumber(tmpprice,2));
		if(tmpprice==""){
			alert('Amount to gift is required.');
			return false;
		}
		if($('#yourname')==""){
			alert('Your Name is required.');
			return false;
		}
		if($('#youremail').val()==""){
			alert('Your Email is required.');
			return false;
		}
		if($('#price').val()<=0){
			alert("Invaid Amount to gift.");
			return false;
		}
		return true;
	}
	function FormatNumber(srcStr,nAfterDot){
		var srcStr,nAfterDot;
		var resultStr,nTen;
		srcStr = ""+srcStr+"";
		strLen = srcStr.length;
		dotPos = srcStr.indexOf(".",0);
		if (dotPos == -1){
			resultStr = srcStr+".";
			for (i=0;i<nAfterDot;i++){
				resultStr = resultStr+"0";
			}
			return resultStr;
		}else{
			if ((strLen - dotPos - 1) >= nAfterDot){
				nAfter = dotPos + nAfterDot + 1;
				nTen =1;
				for(j=0;j<nAfterDot;j++){
					nTen = nTen*10;
				}
				resultStr = Math.round(parseFloat(srcStr)*nTen)/nTen;
				resultStr = ""+resultStr+"";
				tmpPos = resultStr.indexOf(".",0);
				if (tmpPos == -1){
					resultStr = resultStr+".";
					for (i=0;i<nAfterDot;i++){
						resultStr = resultStr+"0";
					}
				}
				return resultStr;
			}else{
				resultStr = srcStr;
				for (i=0;i<(nAfterDot - strLen + dotPos + 1);i++){
					resultStr = resultStr+"0";
				}
				return resultStr;
			}
		}
	}
	function calculprice(obj){
		if(obj.checked&&$('input[name="payment_M"][@checked]').val()=='paypal'){
			var tmpprice = $('#tmp_price').val();
			if(parseFloat(tmpprice)!=0){
				tmpprice = FormatNumber(taxprice(tmpprice),2);
			}else{
				tmpprice = FormatNumber(tmpprice,2);
			}
			$('#price').val(tmpprice);
			$('#amount').val(tmpprice);
			$('#total').html('$'+tmpprice);
		}else{
			var tmpprice = $('#tmp_price').val();
			$('#price').val(tmpprice);
			$('#amount').val(tmpprice);
			$('#total').html('$'+FormatNumber(tmpprice,2));
		}
	}
	function calculgoogleprice(obj){
		if(obj.checked&&$('input[name="payment_M"][@checked]').val()=='googlecheckout'){
			var tmpprice = $('#tmp_price').val();
			if(parseFloat(tmpprice)!=0){
				tmpprice = FormatNumber(taxpricegoogle(tmpprice),2);
			}else{
				tmpprice = FormatNumber(tmpprice,2);
			}
			$('#price').val(tmpprice);
			$('#amount').val(tmpprice);
			$('#total').html('$'+tmpprice);
		}else{
			var tmpprice = $('#tmp_price').val();
			$('#price').val(tmpprice);
			$('#amount').val(tmpprice);
			$('#total').html('$'+FormatNumber(tmpprice,2));
		}
	}
	function hidpaypalcal(bl){
		if(bl){
			$('#paypalcaltab').css('display','');
			$('#googlecheckouttab').css('display','none');
		}else{
			$('#paypalcaltab').css('display','none');
			$('#googlecheckouttab').css('display','');
		}
	}
	
	function taxprice(price){
		var tmpprice = parseFloat(price);
		var total = 0;
		total = tmpprice + (tmpprice*0.024 + 0.30)*0.025 + (tmpprice*0.024)+0.30;
		return total;
	}
	function taxpricegoogle(price){
		var tmpprice = parseFloat(price);
		var total = 0;
		if(tmpprice<3000){
			total = tmpprice + tmpprice*0.029 + 0.30;
		}else if(tmpprice>=3000&&tmpprice<10000){
			total = tmpprice + tmpprice*0.025 + 0.30;
		}else if(tmpprice>=10000&&tmpprice<100000){
			total = tmpprice + tmpprice*0.022 + 0.30;
		}else if(tmpprice>=100000){
			total = tmpprice + tmpprice*0.019 + 0.30;
		}
		return total;
	}
	function changetab(tab,obj){
			$('.tabtmp').children().removeClass("active_tab");
			obj.className = "active_tab";
			$('#xmas_tab').css('display','none');
			$('#birthday_tab').css('display','none');
			$('#general_tab').css('display','none');
			$('#wedding_tab').css('display','none');
			$('#'+tab).css('display','');
			switch(tab){
				case 'xmas_tab':
					$('input[@name="ecardtpl"][@value="j"]').attr('checked',true);
					break;
				case 'birthday_tab':
					$('input[@name="ecardtpl"][@value="a"]').attr('checked',true);
					break;
				case 'general_tab':
					$('input[@name="ecardtpl"][@value="e"]').attr('checked',true);
					break;
				case 'wedding_tab':
					$('input[@name="ecardtpl"][@value="n"]').attr('checked',true);
					break;
					
			}
		}
</script>
<style type="text/css">
	.ecard{
		margin:0;
	}
	.ecard li{
		float:left;
		list-style:none;
		text-align:center;
	}
	.ecard_radio{
		border:1px solid #F0F;
	}
	.tabtmp{
		list-style:none;
		margin:0;
	}
	.tabtmp li{
		list-style:none;
		width:97px;
		height:30px;
		line-height:30px;
		text-align:center;
		float:left;
		cursor:pointer;
		font-weight:bold;
	}
	.tabtmp li.active_tab{
		background-color:#FFF;
	}
	
</style>
{/literal}
<div style="float:left;">
    {if $req.image_name ne ''}
    <img src="{$req.image_name}" width="235" alt="" class="bigimage" />
    {else}
    <img src="images/243x212.jpg" width="235" alt="" class="bigimage" />
    {/if}
    <br />
</div>
 <form action="{$soc_https_host}soc.php?act=wishlistproc&cp=payment" id="buyitem" method="post"  name="paypal" onsubmit="return checksubmit();" >
    <table cellpadding="0" cellspacing="0" width="100%">
    <colgroup>
    <col width="45%"/>
    <col width="35%" />
    <col width="20%" />
    </colgroup>
    <thead>
    <tr>
    <th>Item</th>
    <th>Amount to gift</th>
    <th class="bolder">Total</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td>{$req.item_name}&nbsp;<input style=" padding-left:5px;border-left:0px;" type="hidden" name="item_name" value="{$req.item_name}" /></td>
    <td><span style="line-height:23px; height:23px;*line-height:25px;*height:25px; float:left;">$</span><input type="text" id="tmp_price" name="tmp_price" onblur="checkamount({$req.pid})" maxlength="15"  value="{$req.fotgive|number_format:2:'.':''}" style="width:100px"/>&nbsp;</td>
    <td class="bolder" id="total">${$req.fotgive|number_format:2}&nbsp;</td>
    </tr>
    </tbody>
    </table>
    <h3>Payment Method</h3>
    <input type="radio" name="payment_M" value="paypal" onclick=" checkamount({$req.pid});hidpaypalcal(true);" checked="checked"/><img src="/skin/red/images/parments_ico.png" border="0" />(Credit Card accepted) {if $req.info.google_merchantid&&$req.info.google_merchantkey && false}<input type="radio" onclick=" checkamount({$req.pid});hidpaypalcal(false);" name="payment_M" value="googlecheckout"/>GoogleCheckOut{/if}<br/>
    <span id="paypalcaltab"><input type="checkbox" value="yes" id="payCal" name="payCal" onclick="calculprice(this);" /> Pay for this users's Paypal transaction fees</span>
    <div id="googlecheckouttab" style="display:none;margin-top:5px;"><input type="checkbox" value="yes" id="googleCal" name="googleCal" onclick="calculgoogleprice(this);" /> Pay for this users's GoogleCheckout transaction fees<br/><strong>Note:</strong> Please be aware when using Google Checkout that your eCard will be sent to the wishlist recipient, irrespective of the funds being successfully deposited into the designated Google Checkout account.</div>
    <h3>eCard Message</h3>
    <table cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td style="border:0;padding-bottom:0;width:150px;color:#777777">Your Name:*</td>
        	<td style="border:0;padding-bottom:0;"><input type="text" id="yourname" name="yourname" value="{$buyerinfo.bu_nickname}" class="inputB" style="width:250px;color:#777777"/></td>
        </tr>
        <tr>
        	<td style="border:0;padding-bottom:0;color:#777777">Your Email Address:*</td>
        	<td style="border:0;padding-bottom:0;"><input type="text" id="youremail" name="youremail" value="{$buyerinfo.bu_email}" class="inputB" style="width:250px;color:#777777"/></td>
        </tr>
        <tr>
        	<td style="border:0;padding-bottom:0;color:#777777">Your Message:</td>
        	<td style="border:0;padding-bottom:0;">&nbsp;</td>
        </tr>
        <tr>
        	<td colspan="2" style="border:0;padding-bottom:0;">
            <textarea id="message" name="message" style="width:408px; height:120px;" class="inputB">Dear {if $req.info.SELLER.bu_nickname}{$req.info.SELLER.bu_nickname}{else}{$req.info.SELLER.bu_name}{/if},</textarea></td>
        </tr>
        <tr>
        	<td style="border:0;padding-bottom:0;color:#777777">Email Signature:</td>
            <td style="border:0;padding-bottom:0;">&nbsp;</td>
        </tr>
        <tr>
        	<td colspan="2" style="border:0;padding-bottom:0;">
            <textarea id="signature" name="signature" style="width:408px; height:40px;" class="inputB">Regards,
{$buyerinfo.bu_nickname}</textarea></td>
        </tr>
        <tr><td colspan="2"><h3>Select your eCard template</h3>
        <div style="background-color:#EEE; height:30px; margin:0; border-bottom:1px solid #EEE;">
        <ul class="tabtmp">
    	<li onclick="javascript:changetab('general_tab',this);" class="active_tab" style="width:150px;">College / All Occasions</li>
        <li onclick="javascript:changetab('birthday_tab',this);">Birthday</li>
        <li onclick="javascript:changetab('xmas_tab',this);" >Christmas</li>
        <li onclick="javascript:changetab('wedding_tab',this);" >Weddings</li>
        </ul></div>
        <div id="birthday_tab" style="display:none;">
    	<ul class="ecard">
    		<li><a href="/skin/red/images/ecard/tmpl/a.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/a_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="a"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/b.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/b_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="b"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/c.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/c_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="c"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/d.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/d_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="d"/></li>
        </ul>
        <div style="clear:both"></div>
        </div>
        <div id="general_tab">
        <ul class="ecard">
    		<li><a href="/skin/red/images/ecard/tmpl/e.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/e_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" checked="checked" value="e"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/f.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/f_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="f"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/g.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/g_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="g"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/h.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/h_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="h"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/i.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/i_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="i"/></li>
        </ul>
        <div style="clear:both"></div>
        </div>
        <div id="xmas_tab" style="display:none;">
        <ul class="ecard">
    		<li><a href="/skin/red/images/ecard/tmpl/j.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/j_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="j"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/k.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/k_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="k"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/l.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/l_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="l"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/m.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/m_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="m"/></li>
    	</ul>
        <div style="clear:both"></div>
        </div>
         <div id="wedding_tab" style="display:none;">
        <ul class="ecard">
    		<li><a href="/skin/red/images/ecard/tmpl/n.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/n_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="n"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/o.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/o_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="o"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/p.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/p_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="p"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/q.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/q_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="q"/></li>
    		<li><a href="/skin/red/images/ecard/tmpl/r.jpg" rel=lightbox><img src="/skin/red/images/ecard/tmpl/r_s.jpg" width="110"/></a><br/>
    		<input type="radio" name="ecardtpl" style="border:0;" value="r"/></li>
    	</ul>
        <div style="clear:both"></div>
        </div>
        </td></tr>
    </table>
    <br /><br />
    {if $req.info.paypal}
    <input type="image" name="imageField" src="/skin/red/images/buttons/bu-giftNow.gif" />
    <br/><br/>
    <br/><br/>
    {/if}
     <input type="hidden" id="hid_price" name="hid_price" value="{$req.fotgive}" />
    <input type="hidden" name="old_price" value="{$req.price}"/>
    
    <input type="hidden" name="cmd" value="_xclick" />
    <input type="hidden" name="business" value="{$req.info.paypal}">
    <input type="hidden" id="price" name="price" value="{$req.fotgive}" />
    <input type="hidden" name="pid" value="{$req.pid}" />
    <input type="hidden" name="item_name" value="{$req.item_name}" />
    <input type="hidden" id="amount" name="amount" value="{$req.fotgive}" />
    <input type="hidden" name="quantity" value="1" />
    <input type="hidden" name="currency_code" value="AUD" />
    <input type="hidden" name="item_number" value="{$req.pid}" />
    <input type="hidden" name="StoreID" value="{$req.StoreID}" />
    <input type="hidden" name="return" value="{$hosturl}/wishlistproduct.php" />
    <input type="hidden" name="cancel_return" value="{$hosturl}/soc.php?act=wishlistproc&cp=buy&StoreID={$req.StroeID}&pid={$req.pid}" />
    <input type="hidden" name="notify_url" value="{$hosturl}/wishlistproduct.php" />
</form>