#!/usr/bin/php
<?php

/**
 *  live config
 */
$site_path=dirname(dirname(__FILE__));     //new live site path
include_once $site_path."/include/config.php" ;
include_once $site_path."/include/smartyconfig.php" ;
include_once $site_path."/include/maininc.php" ;
include_once $site_path."/include/functions.php" ;
include_once $site_path."/include/config.php" ;
//END

/**
 * new test site config

$new_test_site_usa_path='/infinity/sites/soc/soc_au';
include_once($new_test_site_usa_path.'/include/config.php');
include_once ($new_test_site_usa_path . '/include/smartyconfig.php');
include_once($new_test_site_usa_path.'/include/maininc.php');
include_once($new_test_site_usa_path.'/include/functions.php');
*/
//END

$now = mktime(0,0,0,date('m'),date('d'),date('Y'));
$last7day = $now-7*86400;
$wheresql .= " and rf.addtime>='{$last7day}' ";
$wheresql .= " and rf.addtime <= '{$now}' ";
$fields = " rf.addtime,rf.details,rf.StoreID,rf.amount,rf.checktype,rf.name,rf.address,bu.attribute,bu.bu_nickname,bu.bu_email,bu.bu_area,bu.bu_phone,bu.contact,bu.launch_date,bu.renewalDate,rst.status,rst.paymethod,rst.total_ref,rst.cur_income,rst.total_income,st.stateName";
$query = "select $fields from {$table}referrer rf left join {$table}bu_detail bu on rf.StoreID=bu.StoreID left join {$table}state st on st.id=bu.bu_state left join {$table}refer_status rst on rst.StoreID=rf.StoreID left join {$table}refconfig rfg on rfg.ReferrerID=bu.ref_name  where rst.status=1 {$wheresql} and rf.id in( select max(id) from {$table}referrer where type=1 group by StoreID)";

$dbcon->execute_query($query);
$result = $dbcon->fetch_records(true);

$tmparry = array();
if($result){
	foreach ($result as $key=>$pass){
		$tmparry[$key]['addtime'] 	= $pass['addtime'];
		$tmparry[$key]['details'] 	= $pass['details'];
		$tmparry[$key]['name'] 		= $pass['name'];
		$tmparry[$key]['amount'] 		= abs($pass['amount']);
		$tmparry[$key]['address'] 	= $pass['address'];
		$tmparry[$key]['StoreID'] 	= $pass['StoreID'];
		$tmparry[$key]['launch_date'] = $pass['launch_date'];
		$tmparry[$key]['contact'] 	= $pass['contact'];
		$tmparry[$key]['bu_phone'] 	= $pass['bu_area']."-".$pass['bu_phone'];
		$tmparry[$key]['bu_email'] 	= $pass['bu_email'];
		$tmparry[$key]['bu_nickname'] = $pass['bu_nickname'];
		$tmparry[$key]['renewalDate'] = $pass['renewalDate'];
		$tmparry[$key]['bu_state'] = $pass['stateName'];
		$tmparry[$key]['checktype'] = $pass['paymethod'];
		switch ($pass['attribute']){
			case '0':$tmparry[$key]['attribute'] = "Buy & Sell";break;
			case '1':$tmparry[$key]['attribute'] = "Real Estate";break;
			case '2':$tmparry[$key]['attribute'] = "Automotive";break;
			case '3':$tmparry[$key]['attribute'] = "Job Market";break;
			default:$tmparry[$key]['attribute'] = "Buyer";break;
		}
		$tmparry[$key]['paymethod'] = $pass['paymethod']==1?"Cheque":($pass['paymethod']==2?"Paypal":"N/A");
		switch ($pass['status']){
			case '1':
				switch ($pass['paymethod']){
					case '1':$tmparry[$key]['status']  = "Cheque";break;
					case '2':$tmparry[$key]['status']  = "Paypal";break;
					default:$tmparry[$key]['status'] = "N/A";break;
				}
				break;
			case '2':$tmparry[$key]['status']  = "Sent";break;
			default:$tmparry[$key]['status']  = "N/A";break;
		}
		$tmparry[$key]['rfgst'] = $pass['rfgst'];
		$tmparry[$key]['referNum'] = $pass['total_ref'];
		$tmparry[$key]['ref_income'] = $pass['cur_income'];
		$tmparry[$key]['ref_total'] = $pass['total_income'];
	}
}
	$arrResult['list']=$tmparry;
	$query = "select count(distinct StoreID) as num,sum(round(curtotal,2)) as curtotal from (select distinct bu.StoreID,rst.total_income as total,rst.cur_income as curtotal,rst.req_income as reqtotal from {$table}referrer rf left join {$table}bu_detail bu on rf.StoreID=bu.StoreID left join {$table}state st on st.id=bu.bu_state left join {$table}refer_status rst on rst.StoreID=rf.StoreID  where rst.status=1 {$wheresql} and rf.id in( select max(id) from {$table}referrer where type=1 group by StoreID))as newtab";
	$dbcon->execute_query($query);
	$result	=	$dbcon->fetch_records(true);
	$arrResult['total'] = $result[0];

$str7dy  = '<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
				<title>Payment Requests - Thesocxchange.com</title>
				<style type="text/css">
				body{font-family:arial,sans-serif;font-size:12px;color:#777777;}
				table.list{border-top:1px solid #cccccc;border-right:1px solid #cccccc;border-left:1px solid #cccccc;width:800px;}
				table.list th{background-color:#66accf;color:#FFFFFF;height:30px;}
				table.list td{border-bottom:1px solid #cccccc;}
				</style>
			</head>
			<body>
			Hi admin,<br/>
			<table>';
$str7dy .= "<tr>
			<td height=30><strong>Payment Requests on Each Site (only last week)</strong></td>
		</tr>";
$str7dy .= "<tr><td>";

$str7dy .= "	<table class='list' cellspacing=0 cellpadding=0>
				<tr>
					<th>NO.</th>
					<th>Nickname</th>
					<th>Email</th>
					<th>User Type</th>
					<th>Payment method</th>
					<th>Current Referral Requested</th>
				</tr>";
if($arrResult['list']):
	$i = 1;
	foreach ($arrResult['list'] as $pass):
		$str7dy .= "<tr>";
		$str7dy .= "	<td align='center'>$i</td>";
		$str7dy .= "	<td align='center'>{$pass['bu_nickname']}&nbsp;</td>";
		$str7dy .= "	<td align='center'>{$pass['bu_email']}&nbsp;</td>";
		$str7dy .= "	<td align='center'>{$pass['attribute']}&nbsp;</td>";
		$str7dy .= "	<td align='center'>{$pass['paymethod']}&nbsp;</td>";
		$str7dy .= "	<td align='center'>$".number_format($pass['ref_income'],2)."</td>";
		$str7dy .= "</tr>";
		$i++;
	endforeach;
else:
	$str7dy .= "<tr><td colspan=6 align='center'>No Records.</td></tr>";
endif;
$str7dy .= "<tr><td height=30 colspan=6 align='center'>Total Amount Requested:  $".number_format($arrResult['total']['curtotal'],2)."</td></tr>";
$str7dy .= "		</table>";
$str7dy .= "	</td></tr>";
$str7dy .="<tr>
			<td></td>
		</tr>
	  </table>
	  <br/><br/>Sincerely,<br/>
The SOC Exchange
	  </body>
	  </html>
	  ";
	$subject = "Payment Requests - Thesocexchange.com.au";

	/* To send HTML mail, you can set the Content-type header. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	/* additional headers */
	//$headers .= "To: ".$to." \r\n";
	$headers .= "From: noreply@TheSOCExchange.com\r\n";

	/* and now mail it */
$message = getEmailTemplate($str7dy);

echo $message;
$headers = fixEOL($headers);

$to = "dafiny.zhang@infinitytesting.com.au";
mail($to, $subject, $message, $headers);
$to = "xiong.wu@infinitytesting.com.au";
mail($to, $subject, $message, $headers);
//$to = "roy.luo@infinitytesting.com.au";
$to = "info@thesocexchange.com";
mail($to, $subject, $message, $headers);
?>
