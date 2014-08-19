
function activeTabs(obj,conentobj){
	$('.des_content').css('display','none');
	$('.bid_content').css('display','none');
	$('.ship_content').css('display','none');
	$('.extra_content').css('display','none');
	$('.img_content').css('display','none');
	$('.tabsActive').removeClass('tabsActive');
	$(obj).addClass('tabsActive');
	$('.'+conentobj).css('display','block');
	
	//alert($('.'+conentobj).height());
	$("#auction_div_content").height($('.'+conentobj).height());
	/*
     *if($('#paging')){
		if(conentobj=='img_content'){
			$('#paging').css('margin','410px 0');
		}else{
			$('#paging').css('margin','10px 0');
		}
	}
    */
}
function img_loaded() {
		$("#div_img_loading").hide();
		$("#div_img_show_content").show();
}
function showMoreImg(obj,srcs,imgwidth,imgheight){
	$('.small_high').removeClass('small_high');
	$(obj).addClass('small_high');
	var width = 680;var height = 398;
	var newwidth = width;
	var newheight = height;
	if (width/height > imgwidth/imgheight) {
		newwidth  = height * imgwidth / imgheight;
	}else{
		newheight = width / (imgwidth / imgheight);
	}
	$("#div_img_loading").show();
	$("#div_img_show_content").hide();
	$('#content_images').css('width',newwidth+"px");
	$('#content_images').css('height',newheight+"px");
	$('#content_images').attr('src',srcs);
}
function countdown(obj,intvs,pid){
	timeleft--;
	if (timeleft < 0){
		timeleft = 0;
	}
	var days = Math.floor(timeleft/(24*60*60));
	var hours = Math.floor((timeleft-days*24*60*60)/(60*60));
	var mins = Math.floor((timeleft-days*24*60*60 - hours*60*60)/60);
	var secs = Math.floor((timeleft-days*24*60*60 - hours*60*60 - mins*60));
	if(days<10){days = "0"+days;}
	if(hours<10){hours = "0"+hours;}
	if(mins<10){mins = "0"+mins;}
	if(secs<10){secs = "0"+secs;}
	if(days>0){
		$("#"+obj).html(days+"d "+hours+"h "+mins+"m ");
	}else if(hours>0){
		$("#"+obj).html(hours+"h "+mins+"m "+secs+"s ");
	}else{
		$("#"+obj).html(mins+"m "+secs+"s ");
		$("#"+obj).addClass('timmer_red');
	}
	var d = new Date();
	var localTime = d.getTime();
	var serverTime = 0;
	$.getJSON('/include/jquery_svr.php',{svr:"checkbidproduct",pid:pid},function(json){
		serverTime = d.getTime();
		if(json.timeleft=='-1'){
			if(json.bid_counter && parseInt(parseFloat(json.bidList[0].price)*100)>parseInt(parseFloat(json.reserve_price)*100)){
				$('#auction_timer').html("<span class='timmer_no'>Item sold. This auction is now closed.</span>");
				$('#soldwaters').show();
				$('#winnerswf').show();
				window.setTimeout('hidewinnder()',15000);
				$('#wintext').html('Winning bid:');
			}else{
				$('#auction_timer').html("<span class='timmer_no'>Item not sold. This auction is now closed.</span>");
				$('#wintext').html('Last bid:');
			}
			/**last bid update***/
			$('#updater1').html("&nbsp;");
			$('#updater2').remove();
			$('#updater3').remove();
			$('#updater4').remove();
			window.clearInterval(eval(intvs));
		}else{
			if(json.bid_counter>0){
				$('#cur_price').html("$"+FixVal(parseInt(json.cur_price)));
				$('#cur_price_html').html(FixVal(parseInt(json.cur_bid_price)));
				$('#cur_person').html(json.winner_id);
			}
			/**update reserve price**/
			$('#reserve_price').removeClass('reserve_yes');
			$('#reserve_price').removeClass('reserve_no');
			if(parseInt(parseFloat(json.cur_price)*100)>=parseInt(parseFloat(json.reserve_price)*100)){
				$('#reserve_price').html("The reserve has been met");
				$('#reserve_price').addClass('reserve_yes');
			}else{
				$('#reserve_price').html("A reserve has been set");
				$('#reserve_price').addClass('reserve_no');
			}
			if(parseInt($('#bid_counter').html())!=parseInt(json.bid_counter)){
				//play soud
				if($("#bidmusic").val()!=""){
					$('#musiccontent').html('<embed src="'+$("#bidmusic").val()+'" hidden="true" autostart="true">');
				}
				/**update bider list**/
				reflashList(json);
				$('#inputPrice').val(parseInt(json.cur_bid_price));
				$('#maxbid_price').val(parseInt(json.cur_bid_price));
				timeleft = json.timeleft;
			}
			$('#bid_counter').html(json.bid_counter+"");
			var httpDelay = parseInt((serverTime - localTime)/2);
			//if(Math.abs(timeleft - json.timeleft)>60){
			//	timeleft = json.timeleft;
			//}
			//alert(httpDelay);
			timeleft = (httpDelay > 500 || json.timeleft > 0)?json.timeleft-1:json.timeleft;
		}
	});
}

function reflashList(json){
	var bidlist = "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
		bidlist    += "<colgroup>";
		bidlist    += "	<col width=\"40%\"/>";
		bidlist    += "<col width=\"25%\"/>";
		bidlist    += "<col width=\"35%\"/>";
		bidlist    += "</colgroup>";
		bidlist    += "<thead>";
		bidlist    += "<tr>";
		bidlist    += "<th class=\"bid_header_first\">Bidder</th>";
		bidlist    += "<th class=\"bid_header\">Bid amount</th>";
		bidlist    += "<th class=\"bid_header\">Bid time</th>";
		bidlist    += "</tr>";
		bidlist    += "</thead>";
		bidlist    += "<tbody>";
		
		for(var i=0;i<json.bidList.length;i++){
			var linehigh = "";var firstline = "";
			if(i%2==0){linehigh="class=\"bid_high\"";}
			if(i==0){firstline="tfirst";}
			bidlist += "<tr "+linehigh+">";
			bidlist += "<td class=\"bid_line_first "+firstline+"\">"+json.bidList[i].bu_nickname+"</td>";
			bidlist += "<td class=\"bid_line "+firstline+"\">$"+FixVal(FormatNumber(json.bidList[i].price,0))+"</td>";
			bidlist += "<td class=\"bid_line "+firstline+"\">"+json.bidList[i].endtimes+"</td>";
			bidlist += "</tr>";
		}
		bidlist		+="<tr><td></td><td></td><td align=\"right\">(Last 20 bids)</td></tr>";
		bidlist    += "</tbody>";
		bidlist    += "</table>";
		
		$('#bid_content').html(bidlist);
		// reset height
		if($("#bidder_content").hasClass("tabsActive")) {
			$("#auction_div_content").height($('.bid_content').height());
		}
		
}
function hidelight(){
	if(timeleft<30){
		if(j%2){$("#timmer").addClass('timmer_red');}else{$("#timmer").removeClass('timmer_red');}
		j++;
	}
	if(timeleft<0){window.clearInterval(inthigh)}
}
/*******/
function checkCertified(pid, StoreID, productStoreId){
    if(!StoreID){
        //alert('Please login to your account to place a bid. You can Register for free to bid as a buyer.');
        //location.href='/soc.php?cp=login';
        $("#bid_message_2").html('<br/>Please login to your account to place a bid. <a href="/soc.php?cp=customers_geton&ctm=1">You can Register for free to bid on this auction.</a>').show();
        return;
    }
    if(StoreID == productStoreId){
        $('#bid_message').html("Sorry, you can't bid the items in your store.").show();
        return;
    }
    
    var url = '/soc.php?cp=certified&pid='+certifiedInfo.productId+'&StoreID='+certifiedInfo.storeId;
    //if(confirm('You are not a certified bidder at this auction. Please submit your details to get certified to bid at this auction.')){
        location.href=url;
    //}
}
/***bid the product***/
function bid(pid,StoreID){
	$('#bid_message').fadeOut(200);
	var curp = $('#inputPrice').val();
	var num = curp.replace(/,/g,'');
	var r = /^[0-9]*[1-9][0-9]*$/;
	if(!r.test(num)){
		$('#bid_message').html(data);
		$('#bid_message').fadeIn(200);return 0;
	}
	if(!StoreID){
            //alert('Please login to your account to place a bid. You can Register for free to bid as a buyer.');
            //location.href='/soc.php?cp=login';
            $("#bid_message_2").html('<br/>Please login to your account to place a bid. <a href="/soc.php?cp=customers_geton&ctm=1">You can Register for free to bid on this auction.</a>').show();
            return 0;
        }

	$.get('/include/jquery_svr.php',{svr:"bidproduct",pid:pid,StoreID:StoreID,price:curp},function(data){
		if(data==""){
			$('#cur_price').html("$"+parseInt(num));
			$('#cur_person').html($('#hide_bidname').val());
		}else if(data==0){
        }else if(data.substring(0, 1) == '{'){
            var o = eval('(' + data + ')');
            var url = '/soc.php?cp=certified&pid='+o.productId+'&StoreID='+o.storeId;
            if(o.state == 'notCertified'){
                if(confirm('You are not a certified bidder at this auction. Please submit your details to get certified to bid at this auction.')){
                    location.href=url;
                }
            }else if(o.state == 'pedding'){
                alert("Your request for certification is being reviewed. Please wait for confirmation from the seller.");
            }
        }else{
			$('#bid_message').html(data);
			$('#bid_message').fadeIn(200);
		}
	});
}
/**add the watch function**/
function addwatch(pid,StoreID){
	$('#bid_message').fadeOut(200);
	if(!StoreID){alert('Please login before watching this item.');location.href='/soc.php?cp=login';return 0;}
	$.get('/include/jquery_svr.php',{svr:"addwatchitem",pid:pid,StoreID:StoreID},function(data){
		$('#bid_message').html(data);
		$('#bid_message').fadeIn(200);
	});
}
/***auto bid function***/
function autobid(pid,StoreID){
	$('#desc').hide();
	$('#bid_message').fadeOut(200);
	if(!StoreID){
		//alert('Please login to your account to place a bid. You can Register for free to bid as a buyer.');
		//location.href='/soc.php?cp=login';
                $("#bid_message_2").html('<br/>Please login to your account to place a bid. <a href="/soc.php?cp=customers_geton&ctm=1">You can Register for free to bid on this auction.</a>').show();
		return 0;
	}
	
	var maxbid = $('#maxbid_price').val();
	var num = maxbid.replace(/,/g,'');
	var r = /^[0-9]*[1-9][0-9]*$/;
	if(!r.test(num)){
		$('#bid_message').html('Please enter whole dollar amounts only. Cents are not accepted.');
		$('#bid_message').fadeIn(200);
		return 0;
	}
	if(maxbid){
		$('#autobidfuns').hide();
		$.get('/include/jquery_svr.php',{svr:"autobid",pid:pid,StoreID:StoreID,price:maxbid},function(data){
            if(data.substring(0, 1) == '{'){
                var o = eval('(' + data + ')');
                var url = '/soc.php?cp=certified&pid='+o.productId+'&StoreID='+o.storeId;
                if(o.state == 'notCertified'){
                    if(confirm('You are not a certified bidder at this auction. Please submit your details to get certified to bid at this auction.')){
                        location.href=url;
                    }
                }else if(o.state == 'pedding'){
                    alert( "Your request for certification is being reviewed. Please wait for confirmation from the seller.");
                }
            }else{
                $('#bid_message').html(data);
                $('#bid_message').fadeIn(200);
                $('#maxbid_price').val($('#inputPrice').val());
            }
		});
	}
}

function FormatNumber(srcStr,nAfterDot){
	var srcStr,nAfterDot;
	var resultStr,nTen;
	srcStr = ""+srcStr+"";
	strLen = srcStr.length;
	dotPos = srcStr.indexOf(".",0);
	if (dotPos == -1){
		if(nAfterDot>0){
			resultStr = srcStr+".";
		}
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
			dotPos = (resultStr+"").indexOf(".",0);
			if(dotPos != -1){
				for (i=0;i<(nAfterDot - strLen + dotPos + 1);i++){
					resultStr = resultStr+"0";
				}
			}else{
				for (i=0;i<nAfterDot;i++){
					if(i==0){
						resultStr = resultStr+".0";
					}else{
						resultStr = resultStr+"0";
					}
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
function hidewinnder(){$('#winnerswf').fadeOut(600);}

function FixVal(svalue)
{
	var price = svalue.toString().replace(/,| /g,''); 
	var p1="",p2="";
	if(price.indexOf(".") > 0 ){
		var pp = price.split(".");
		p1 = pp[0];
		p2 = pp[1];
	}else p1 = price ;

	var fixVal ="" ;
	if(p1.length>3)
	{
		k = 0 ;
		if(p1.length%3!=0){
			for(var i=0;i<p1.length%3;i++){p1 = "0"+p1;}
		}
		for(var i=0; i<p1.length; i+=3){
			k ++ ;
			var t=p1.substr(i,3);
			fixVal+=","+t;	
		}
	}
	else  fixVal = p1;

	fixVal = (fixVal.substr(0,1)==",")?fixVal.substr(1):fixVal;
	var ti = fixVal.indexOf(",");
	if(ti!=-1)
	{
		fixVal = fixVal.substr(0,ti)*1+fixVal.substr(ti);
	}
	if (p2.length>0) fixVal = fixVal +"."+ p2;
		
	return fixVal;
}

function showDesc(){
	if ($('#desc').css('display') == 'none'){
		$('#desc').show(500);
	}else{
		$('#desc').hide(500);
	}
}

function FixValPrice(amountID){
	var obj = document.getElementById(amountID);
	if(obj){
		var price = obj.value.replace(/,| /g,'');
		
		if(!price.match(/^[\d,\s]*?\d*?$/))
			price=obj.t_value;
		else 
			obj.t_value=price;
		
		if(price.match(/^(?:\d+(?:\d+)?)?$/))
			obj.o_value=price
		
		var p1="",p2="";
		if(price.indexOf(".") > 0 ){
			var pp = price.split(".");
			p1 = pp[0];
			p2 = pp[1];
		}else 
			p1 = price ;

		var fixVal ="" ;
		if(p1.length>3){
			k = 0 ;
			if(p1.length%3!=0){
				for(var i=0;i<p1.length%3;i++){
					p1 = "0"+p1;
				}
			}
			for(var i=0; i<p1.length; i+=3){
				k ++ ;
				var t=p1.substr(i,3);
				fixVal+=","+t;
			}
		}else
			fixVal = p1;
	
		fixVal = (fixVal.substr(0,1)==",")?fixVal.substr(1):fixVal;
		var ti = fixVal.indexOf(",");
		if(ti!=-1){
			fixVal = fixVal.substr(0,ti)*1+fixVal.substr(ti);
		}
		if (p2.length>0) fixVal = fixVal +"."+ p2;
		obj.value = fixVal;
	}
}