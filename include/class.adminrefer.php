<?php
/**
 * Thu Oct 16 17:29:05 GMT+08:00 2008 17:29:05
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * Store function and class
 * ------------------------------------------------------------
 * \include\class.adminstore.php
 */

class adminRefer extends common {
	var $dbcon 	= 	null;
	var $table	=	'';
	var $smarty = 	null;
	var $lang	=	null;

	/**
	 * @return void 
	 */
	public function __construct()
	{
		$this -> dbcon  = &$GLOBALS['dbcon'];
		$this -> table	= &$GLOBALS['table'];
		$this -> smarty = &$GLOBALS['smarty'];
		$this -> lang	= &$GLOBALS['_LANG'];
	}

	/**
    * @return void 
    */
	public function __destruct(){
		unset($this->dbcon,$this -> table,$this->smarty,$this -> lang);
	}

	/**
	 * get refer list
	 *
	 * @param int $pageno
	 * @param string $strParam
	 * @param boolean $notOld
	 * @return array
	 */
	public function getReferList($startDate=0,$enddate=0,$nickname="",$usertype="-1",$paymethod="",$state="",$college="",$status="",$field="",$order="asc",$ispayment=false,$curpage=1){
		$arrResult	=	null;
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$wheresql = "";
		if($startDate!=0){
			if($ispayment){
				$wheresql .= " and rf.addtime>='{$startDate}' ";
			}else{
				$wheresql .= " and bu.launch_date>='{$startDate}' ";
			}
		}
		if($enddate!=0){
			if($ispayment){
				$wheresql .= " and rf.addtime <= '{$enddate}' ";
			}else{
				$wheresql .= " and bu.launch_date <= '{$enddate}' ";
			}
		}
		if($nickname!=""){
			$wheresql .= " and bu.bu_nickname like '%{$nickname}%' ";
		}
		if($usertype!="-1"&&$usertype!=""){
			$wheresql .= " and bu.attribute = '{$usertype}' ";
		}
		if($state!=""){
			$wheresql .= " and bu.bu_state in({$state})";
		}
		if($college!=""){
			$wheresql .= " and bu.bu_college = '{$college}' ";
		}
		if($paymethod!=""){
			if($_SESSION['refSearch']['payreport']){
				$wheresql .= " and rf.checktype = '{$paymethod}' ";
			}else{
				$wheresql .= " and rst.paymethod = '{$paymethod}' ";
			}
		}
		if($status!=""){
			$wheresql .= " and rfg.status = '{$status}' ";
		}
		switch ($field){
			case 'bu_nickname':
				$order = " order by bu.bu_nickname $order ";
				break;
			case 'bu_email':
				$order = " order by bu.bu_email $order ";
				break;
			case 'bu_phone':
				$order = " order by bu.bu_phone $order ";
				break;
			case 'contact':
				$order = " order by bu.contact $order ";
				break;
			case 'launch_date':
				$order = " order by bu.launch_date $order ";
				break;
			case 'renewalDate':
				$order = " order by bu.renewalDate $order ";
				break;
			case 'bu_state':
				$order = " order by st.stateName $order ";
				break;
			case 'referNum':
				$order = " order by rst.total_ref $order ";
				break;
			case 'ref_income':
				$order = " order by rst.cur_income  $order ";
				break;
			case 'ref_total':
				$order = " order by rst.total_income $order ";
				break;
			case 'status':
				$order = " order by rst.status,rst.paymethod $order ";
				break;
			case 'attribute':
				$order = " order by bu.attribute $order ";
				break;
			case 'paymethod':
				$order = " order by rst.paymethod $order ";
				break;
			case 'date':
				$order = " order by rf.addtime $order ";
				break;
			case 'details':
				$order = " order by rf.details $order ";
				break;
			case 'amount':
				$order = " order by rf.amount $order ";
				break;
			case 'name':
				$order = " order by rf.name $order ";
				break;
			case 'address':
				$order = " order by rf.address $order ";
				break;
			case 'rfgst':
				$order = " order by rfg.status $order ";
				break;
			default:
				$order = "";
				break;
		}
		if($ispayment){
			if($_SESSION['refSearch']['payreport']){
				$query ="select count(*) from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID left join {$this->table}refconfig rfg on rfg.ReferrerID=bu.ref_name  where rf.type=2 {$wheresql}";
			}else{
				$query ="select count(*) from (select count(distinct bu.StoreID) from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID left join {$this->table}refconfig rfg on rfg.ReferrerID=bu.ref_name  where rst.status=1 {$wheresql} group by bu.StoreID) as tab";
			}
		}else{
			$query ="select count(*) from (select count(distinct bu.StoreID) from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID left join {$this->table}refconfig rfg on rfg.ReferrerID=bu.ref_name  where 1=1 {$wheresql} group by bu.StoreID) as tab";
		}
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		$fileds = " rf.addtime,rf.details,rf.StoreID,rf.amount,rf.checktype,rf.name,rf.address,bu.attribute,bu.bu_nickname,bu.bu_email,bu.bu_area,bu.bu_phone,bu.contact,bu.launch_date,bu.renewalDate,rst.status,rst.paymethod,rst.total_ref,rst.cur_income,rst.total_income,st.stateName";
		
		if($ispayment){
			if($_SESSION['refSearch']['payreport']){
				$query = "select $fileds from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID left join {$this->table}refconfig rfg on rfg.ReferrerID=bu.ref_name  where rf.type=2 {$wheresql} {$order} limit $start,$perPage";
			}else{
				$query = "select $fileds from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID left join {$this->table}refconfig rfg on rfg.ReferrerID=bu.ref_name  where rst.status=1 {$wheresql} and rf.id in( select max(id) from {$this->table}referrer where type=1 group by StoreID) {$order} limit $start,$perPage";
			}
		}else{
			$query = "select $fileds,rfg.status as rfgst from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID left join {$this->table}refconfig rfg on rfg.ReferrerID=bu.ref_name  where 1=1 {$wheresql} group by bu.StoreID {$order} limit $start,$perPage";
		}
		$this->dbcon->execute_query($query);
		$arrTemp	=	$this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$tmparry = array();
				foreach ($arrTemp as $key=>$pass){
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
						case '0':
							$tmparry[$key]['attribute'] = "Buy & Sell";
							break;
						case '1':
							$tmparry[$key]['attribute'] = "Real Estate";
							break;
						case '2':
							$tmparry[$key]['attribute'] = "Automotive";
							break;
						case '3':
							$tmparry[$key]['attribute'] = "Job Market";
							break;
						default:
							$tmparry[$key]['attribute'] = "Buyer";
							break;
					}
		
					$tmparry[$key]['paymethod'] = $pass['paymethod']==1?"Cheque":($pass['paymethod']==2?"Paypal":"N/A");
					
					if(!$pass['status']){
						$tmparry[$key]['status']  = "N/A";
					}else{
						if($pass['status']=="1"){
							if($pass['paymethod'] == '1'){
								$tmparry[$key]['status']  = "Cheque";
							}elseif($pass['paymethod']=='2'){
								$tmparry[$key]['status']  = "Paypal";	
							}else{
								$tmparry[$key]['status'] = "N/A";
							}
						}elseif($pass['status']=="2"){
							$tmparry[$key]['status']  = "Sent";
						}else{
							$tmparry[$key]['status']  = "N/A";
						}
					}
					$tmparry[$key]['rfgst'] = $pass['rfgst'];
					$tmparry[$key]['referNum'] = $pass['total_ref'];
					$tmparry[$key]['ref_income'] = $pass['cur_income'];
					$tmparry[$key]['ref_total'] = $pass['total_income'];
				}
				
				$arrResult['list']	=	& $tmparry;

				$arrResult['totalIncome'] = $this->getTotalIncome($wheresql);
				$arrResult['ReqIncome'] = $this->getReqIncome($wheresql);
				$arrResult['CurIncome'] = $arrResult['totalIncome']-$arrResult['ReqIncome'];
				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getReferList(\'%d\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			}
		$arrResult['query'] = $query;
			
		if($ispayment){
			if($_SESSION['refSearch']['payreport']){
				$query = "select count(distinct StoreID) as num,abs(sum(round(amount,2))) as totals from (select $fileds from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID  where rf.type=2 {$wheresql})as newtab";
			}else{
				$query = "select count(distinct StoreID) as num,sum(round(curtotal,2)) as curtotal from (select distinct bu.StoreID,rst.total_income as total,rst.cur_income as curtotal,rst.req_income as reqtotal from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID  where rst.status=1 {$wheresql} and rf.id in( select max(id) from {$this->table}referrer where type=1 group by StoreID))as newtab";
			}
		
			$this->dbcon->execute_query($query);
			$result	=	$this->dbcon->fetch_records(true);
			$arrResult['total'] = $result[0];
		}else{
			$wheresql1 = $wheresql2 = "";
			if($startDate!=0){
				$wheresql1 .= " and addtime>='{$startDate}' ";
			}
			if($enddate!=0){
				$wheresql1 .= " and addtime <= '{$enddate}' ";
			}
			if($nickname!=""){
				$wheresql2 .= " and bu.bu_nickname like '%{$nickname}%' ";
			}
			if($usertype!="-1"&&$usertype!=""){
				$wheresql2 .= " and bu.attribute = '{$usertype}' ";
			}
			if($paymethod!=""){
				$wheresql1 .= " and checktype = '{$paymethod}' ";
			}
			if($state!=""){
				$wheresql2 .= " and bu.bu_state = '{$state}' ";
			}
			if($college!=""){
				$wheresql2 .= " and bu.bu_college = '{$college}' ";
			}
			if($status!=""){
				$wheresql2 .= " and rfg.status = '{$status}' ";
			}
			$query = "select sum(round(amount,2)) as total from {$this->table}referrer where type=0 and StoreID in(select bu.StoreID from {$this->table}refer_status rst left join {$this->table}bu_detail bu on rst.StoreID=bu.StoreID left join {$this->table}refconfig rfg on rfg.ReferrerID=bu.ref_name where 1=1 $wheresql2) $wheresql1";
			$this->dbcon->execute_query($query);
			$result	=	$this->dbcon->fetch_records(true);
			$tmptotal = $result[0]['total'];
			$query = "select abs(sum(round(amount,2))) as reqamount from {$this->table}referrer where type=2 and StoreID in(select bu.StoreID from {$this->table}refer_status rst left join {$this->table}bu_detail bu on rst.StoreID=bu.StoreID left join {$this->table}refconfig rfg on rfg.ReferrerID=bu.ref_name where 1=1 $wheresql2) $wheresql1";
			$this->dbcon->execute_query($query);
			$result	=	$this->dbcon->fetch_records(true);
			$tmpreq = $result[0]['reqamount'];
			$arrResult['total']['total'] = $tmptotal;
			$arrResult['total']['curtotal'] = $tmptotal-$tmpreq;
			$arrResult['total']['reqtotal'] = $tmpreq;
		}
		
		unset($arrTemp,$params);

		return $arrResult;
	}
	
	public function exportReferlist($startDate=0,$enddate=0,$nickname="",$usertype="-1",$paymethod="",$state="",$college="",$status="",$ispayment=false){
		if($startDate!=0){
			if($ispayment){
				$wheresql .= " and rf.addtime>='{$startDate}' ";
			}else{
				$wheresql .= " and bu.launch_date>='{$startDate}' ";
			}
		}
		if($enddate!=0){
			if($ispayment){
				$wheresql .= " and rf.addtime <= '{$enddate}' ";
			}else{
				$wheresql .= " and bu.launch_date <= '{$enddate}' ";
			}
		}
		if($nickname!=""){
			$wheresql .= " and bu.bu_nickname like '%{$nickname}%' ";
		}
		if($usertype!="-1"&&$usertype!=""){
			$wheresql .= " and bu.attribute = '{$usertype}' ";
		}
		if($state!=""){
			$wheresql .= " and bu.bu_state in({$state})";
		}
		if($college!=""){
			$wheresql .= " and bu.bu_college = '{$college}' ";
		}
		if($paymethod!=""){
			if($_SESSION['refSearch']['payreport']){
				$wheresql .= " and rf.checktype = '{$paymethod}' ";
			}else{
				$wheresql .= " and rst.paymethod = '{$paymethod}' ";
			}
		}else{
			if($status!=""){
				$wheresql .= " and rfg.status = '{$status}' ";
			}
		}
		
		$fileds = " rf.addtime,rf.details,rf.StoreID,rf.amount,rf.checktype,rf.name,rf.address,bu.attribute,bu.bu_nickname,bu.bu_email,bu.bu_area,bu.bu_phone,bu.contact,bu.launch_date,bu.renewalDate,rst.status,rst.paymethod,rst.total_ref,rst.cur_income,rst.total_income,st.stateName";
		
		if($ispayment){
			if($_SESSION['refSearch']['payreport']){
				$query = "select $fileds from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID  where rf.type=2 {$wheresql}";
			}else{
				$query = "select $fileds from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID  where rst.status=1 {$wheresql} and rf.id in( select max(id) from {$this->table}referrer where type=1 group by StoreID)";
			}
		}else{
			$query = "select $fileds,rfg.status as rfgst from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID left join {$this->table}refconfig rfg on rfg.ReferrerID=bu.ref_name  where 1=1 {$wheresql} group by bu.StoreID";
				
		}
		$this->dbcon->execute_query($query);
		$arrTemp	=	$this->dbcon->fetch_records(true);

		$tmparry = array();
		$attributes[0] = "Buy & Sell";
		$attributes[1] = "Real Estate";
		$attributes[2] = "Automotive";
		$attributes[3] = "Job Market";
		$paymethods[0] = "N/A";
		$paymethods[1] = "Cheque";
		$paymethods[2] = "Paypal";
		if(DATAFORMAT_DB=="%m/%d/%Y"){
			$datef = "m/d/Y";
		}else{
			$datef = "d/m/Y";
		}
		if($ispayment){
			if($_SESSION['refSearch']['payreport']){
				$tmparry = array();
				$tmparry[0]  = array('Date','Nickname','Email','User Type','State','Description','Amount','Account/Name','Address');
				$i=1;
				if(is_array($arrTemp)){
				foreach ($arrTemp as $key=>$pass){
					$tmparry[$i][0] = date($datef,$pass['addtime']);
					$tmparry[$i][1] = $pass['bu_nickname'];
					$tmparry[$i][2] = $pass['bu_email'];
					$tmparry[$i][3] = $attributes[$pass['attribute']];
					$tmparry[$i][4] = $pass['stateName'];
					$tmparry[$i][5] = substr($pass['details'],0,6);
					$tmparry[$i][6] = "$".number_format(floatval(abs($pass['amount'])),2);
					$tmparry[$i][7] = $pass['name'];
					$tmparry[$i][8] = $pass['address'];
					$i++;
				}
				}
			}else{
				$tmparry = array();
				$tmparry[0] = array('Nickname','Email','User Type','Payment method','Current Referral Requested');
				$i=1;
				if(is_array($arrTemp)){
				foreach ($arrTemp as $key=>$pass){
					$tmparry[$i][0] = $pass['bu_nickname'];
					$tmparry[$i][1] = $pass['bu_email'];
					$tmparry[$i][2] = $attributes[$pass['attribute']];
					$tmparry[$i][3] = $paymethods[$pass['paymethod']];
					if($pass['name']!=""){
						$tmparry[$i][3] .= " \r\n".$pass['name'];
					}
					if($pass['address']!=""){
						$tmparry[$i][3] .= " \r\n".$pass['address'];
					}
					$tmparry[$i][4] = "$".number_format(floatval($pass['cur_income']),2);
					$i++;
				}
				}
			}
		}else{
			$tmparry = array();
			$tmparry[0]  = array('Nickname','Email','Phone','Preferred Contact','Joined Date','Expiry Date','State','Number of Referrals','Current Referral Owing','Total Referral Earnt','Status');
			$i=1;
			if(is_array($arrTemp)){
			foreach ($arrTemp as $key=>$pass){
				$tmparry[$i][0] = $pass['bu_nickname'];
				$tmparry[$i][1] = $pass['bu_email'];
				$tmparry[$i][2] = $pass['bu_area']."-".$pass['bu_phone'];
				$tmparry[$i][3] = $pass['contact'];
				$tmparry[$i][4] = date($datef,$pass['launch_date']);
				$tmparry[$i][5] = date($datef,$pass['renewalDate']);
				$tmparry[$i][6] = $pass['stateName'];
				$tmparry[$i][7] = $pass['total_ref'];
				$tmparry[$i][8] = "$".number_format(floatval($pass['cur_income']),2);
				$tmparry[$i][9] = "$".number_format(floatval($pass['total_income']),2);
				$tmparry[$i][10] = $pass['rfgst'];
				$i++;
			}
			}
		}
		
		return  $tmparry;
	}
	
	
	public function getpaypallist($startDate=0,$enddate=0,$nickname="",$usertype="-1"){
		$wheresql = "";
		if($startDate!=0){
			$wheresql = " and rf.addtime >='{$startDate}' ";
		}
		if($enddate!=0){
			$wheresql .= " and rf.addtime <= '{$enddate}' ";
		}
		if($nickname!=""){
			$wheresql .= " and bu.bu_nickname like '%{$nickname}%' ";
		}
		if($usertype!="-1"&&$usertype!=""){
			$wheresql .= " and bu.attribute = '{$usertype}' ";
		}
		$query = "select f.name as first,rt.cur_income as second,'AUD' as third,bu.ref_name as forth,concat('SOC Fee payment (',bu.ref_name,')') as fifth,f.StoreID from {$this->table}referrer f left join {$this->table}refer_status rt on f.StoreID=rt.StoreID left join {$this->table}bu_detail bu on f.StoreID=bu.StoreID where f.id in(select max(rf.id) from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID  where rst.status=1 and rst.paymethod=2 and rf.type=1 {$wheresql} group by bu.StoreID order by rf.id ASC)";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		return $result;
	}
	
	public function getexportlist($field="",$order="asc",$curpage=1){
		$arrResult	=	null;
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		switch ($field){
			case 'export_num':
				$orders = " order by export_num $order ";
				break;
			case 'start_date':
				$orders = " order by start_date $order ";
				break;
			case 'end_date':
				$orders = " order by end_date $order ";
				break;
			case 'create_time':
				$orders = " order by create_time $order ";
				break;
			default:
				$orders = "";
				break;
		}
		$query = "select count(*) from {$this->table}ref_download";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		$query = "select * from {$this->table}ref_download $orders limit $start,$perPage";
		$this->dbcon->execute_query($query);
		$arrTemp	=	$this->dbcon->fetch_records();
		if (is_array($arrTemp)) {
				$arrResult['list']	=	& $arrTemp;

				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getdownlist(\'%d\',\''.$field.'\',\''.$order.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
		}
		return $arrResult;
		
	}
	
	public function getreferrerbyRefID($refid,$curpage=1,$field="",$order="asc"){
		$arrResult	=	null;
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$orders = $order;
		if(trim($refid)==""){
			return $arrResult;
		}
		$wheresql = " and bu.referrer = '".trim($refid)."' ";
		switch ($field){
			case 'bu_nickname':
				$order = " order by bu.bu_nickname $order ";
				break;
			case 'bu_email':
				$order = " order by bu.bu_email $order ";
				break;
			case 'bu_phone':
				$order = " order by bu.bu_phone $order ";
				break;
			case 'contact':
				$order = " order by bu.contact $order ";
				break;
			case 'launch_date':
				$order = " order by bu.launch_date $order ";
				break;
			case 'renewalDate':
				$order = " order by bu.renewalDate $order ";
				break;
			case 'bu_state':
				$order = " order by st.stateName $order ";
				break;
			case 'referNum':
				$order = " order by rst.total_ref $order ";
				break;
			case 'ref_income':
				$order = " order by rst.cur_income  $order ";
				break;
			case 'ref_total':
				$order = " order by rst.total_income $order ";
				break;
			default:
				$order = "";
				break;
		}
		$query ="select count(*) from (select count(distinct bu.StoreID) from {$this->table}bu_detail bu left join  {$this->table}referrer rf  on rf.StoreID=bu.StoreID left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID  where 1=1 {$wheresql} group by bu.StoreID) as tab";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		$fileds = " rf.addtime,rf.details,rf.StoreID,rf.amount,rf.checktype,rf.name,rf.address,bu.attribute,bu.bu_nickname,bu.bu_email,bu.bu_area,bu.bu_phone,bu.contact,bu.launch_date,bu.renewalDate,rst.status,rst.paymethod,rst.total_ref,rst.cur_income,rst.total_income,st.stateName";
		
		$query = "select $fileds from {$this->table}bu_detail bu left join {$this->table}referrer rf on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID  where 1=1 {$wheresql} group by bu.StoreID {$order} limit $start,$perPage";
		$this->dbcon->execute_query($query);
		$arrTemp	=	$this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$tmparry = array();
				foreach ($arrTemp as $key=>$pass){
					$tmparry[$key]['addtime'] 	= $pass['addtime'];
					$tmparry[$key]['details'] 	= $pass['details'];
					$tmparry[$key]['name'] 		= $pass['name'];
					$tmparry[$key]['amount'] 	= abs($pass['amount']);
					$tmparry[$key]['address'] 	= $pass['address'];
					$tmparry[$key]['StoreID'] 	= $pass['StoreID'];
					$tmparry[$key]['launch_date'] = $pass['launch_date'];
					$tmparry[$key]['contact'] 	= $pass['contact'];
					$tmparry[$key]['bu_phone'] 	= $pass['bu_area']."-".$pass['bu_phone'];
					$tmparry[$key]['bu_email'] 	= $pass['bu_email'];
					$tmparry[$key]['bu_nickname'] = $pass['bu_nickname'];
					$tmparry[$key]['renewalDate'] = $pass['renewalDate'];
					$tmparry[$key]['bu_state'] = $pass['stateName'];
					$tmparry[$key]['referNum'] = $pass['total_ref'];
					$tmparry[$key]['ref_income'] = $pass['cur_income'];
					$tmparry[$key]['ref_total'] = $pass['total_income'];
				}
				
				$arrResult['list']	=	& $tmparry;
				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getReferById(\'%d\',\''.$refid.'\',\''.$field.'\',\''.$orders.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			}
		$arrResult['query'] = $query;
		
		unset($arrTemp,$params);

		return $arrResult;
	}
	
	public function exportRefID($refid){
		$aryhead = array();
		$aryhead[0] = array('Nickname','Email','Phone','Preferred Contact','Joined Date','Expiry Date','State','Number of Referrals','Current Referral Owing','Total Referral Earnt');
		if(DATAFORMAT_DB=="%m/%d/%Y"){
			$datef = "m/d/Y";
		}else{
			$datef = "d/m/Y";
		}
		if(trim($refid)==""){
			return $aryhead;
		}
		$wheresql = " and bu.referrer = '".trim($refid)."' ";
		$fileds = " rf.addtime,rf.details,rf.StoreID,rf.amount,rf.checktype,rf.name,rf.address,bu.attribute,bu.bu_nickname,bu.bu_email,bu.bu_area,bu.bu_phone,bu.contact,bu.launch_date,bu.renewalDate,rst.status,rst.paymethod,rst.total_ref,rst.cur_income,rst.total_income,st.stateName";
		
		$query = "select $fileds from {$this->table}bu_detail bu left join {$this->table}referrer rf on rf.StoreID=bu.StoreID left join {$this->table}state st on st.id=bu.bu_state left join {$this->table}refer_status rst on rst.StoreID=rf.StoreID  where 1=1 {$wheresql} group by bu.StoreID";
		$this->dbcon->execute_query($query);
		$arrTemp=$this->dbcon->fetch_records(true);
		
		if(is_array($arrTemp)){
			$i = 1;
			foreach ($arrTemp as $pass){
				$aryhead[$i][0] = $pass['bu_nickname'];
				$aryhead[$i][1] = $pass['bu_email'];
				$aryhead[$i][2] = $pass['bu_area']."-".$pass['bu_phone'];
				$aryhead[$i][3] = $pass['contact'];
				$aryhead[$i][4] = date($datef,$pass['launch_date']);
				$aryhead[$i][5] = date($datef,$pass['renewalDate']);
				$aryhead[$i][6] = $pass['stateName'];
				$aryhead[$i][7] = number_format($pass['total_ref'],0);
				$aryhead[$i][8] = "$".number_format($pass['cur_income'],2);
				$aryhead[$i][9] = "$".number_format($pass['total_income'],2);
				$i++;
			}
		}
		return $aryhead;
	}
	
	public function get_Hour(){
		$hour = array();
		for($i=0;$i<24;$i++){
			if($i<10){
				$hour[$i] = "0".$i;
			}else{
				$hour[$i] = $i;
			}
		}
		return $hour;
	}

	public function getTotalIncome($whSql = ""){
		$sql = "select sum(round(amount,2)) as amount  from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID where type=0 $whSql";
		$this->dbcon->execute_query($sql);
		$result	=$this->dbcon->fetch_records();
		return floatval($result[0]['amount']);
	}
	public function getReqIncome($whSql = ""){
		$sql = "select sum(round(amount,2)) as amount from {$this->table}referrer rf left join {$this->table}bu_detail bu on rf.StoreID=bu.StoreID where type=2 $whSql";
		$this->dbcon->execute_query($sql);
		$result	=$this->dbcon->fetch_records();
		return floatval($result[0]['amount']);
		
	}
	
	public function getconfiglist($curpage=1,$field="",$order='ASC'){
		$arrResult	=	null;
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$orders = "";
		switch ($field){
			case 'min_commission':
				$orders = " order by min_commission $order ";
				break;
			case 'percent':
				$orders = " order by percent $order ";
				break;
			case 'min_refer':
				$orders = " order by min_refer $order ";
				break;
			case 'ReferrerID':
				$orders = " order by ReferrerID $order ";
				break;
			case 'status':
				$orders = " order by status $order ";
				break;
			default:
				$orders = "";
				break;
		}
		$query = "select count(*) from {$this->table}refconfig where ReferrerID<>'0'";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		$sql = "select * from {$this->table}refconfig where ReferrerID<>'0' $orders limit $start,$perPage";
		$this->dbcon->execute_query($sql);
		$arrTemp	=	$this->dbcon->fetch_records();
		if (is_array($arrTemp)) {
				$arrResult['list']	=	& $arrTemp;

				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getconfiglist(\'%d\',\''.$field.'\',\''.$order.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
		}
		$arrResult['pageno'] = $pageno;
		return $arrResult;
	}
	
	public function exportconfig(){
		$arrResult = array();
		$arrResult[0] = array('Referrer ID','Commission Rate','Earnings Before Commission','Withdrawal Amount','Status');
		$sql = "select * from {$this->table}refconfig where ReferrerID<>'0'";
		$this->dbcon->execute_query($sql);
		$arrTemp	=	$this->dbcon->fetch_records();
		if (is_array($arrTemp)) {
			$i=1;
			foreach ($arrTemp as $pass){
				$arrResult[$i][0] = $pass['ReferrerID'];
				$arrResult[$i][1] = $pass['percent']."%";
				$arrResult[$i][2] = "$".number_format($pass['min_commission'],2);
				$arrResult[$i][3] = "$".number_format($pass['min_refer'],2);
				$arrResult[$i][4] = $pass['status'];
				$i++;
			}
		}
		return $arrResult;
	}
	
	public function exportRefreport($StoreID){
		if(DATAFORMAT_DB=="%m/%d/%Y"){
			$datef = "m/d/Y";
		}else{
			$datef = "d/m/Y";
		}
		$sql = "select * from {$this->table}referrer where StoreID='{$StoreID}'";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		$arrResult = array();
		$arrResult[0] = array('Date','Description','Amount','Balnace');
		if(is_array($result)){
			$i = 1;
			foreach ($result as $pass){
				$arrResult[$i][0] = date($datef,$pass['addtime']);
				$arrResult[$i][1] = $pass['details'];	
				$arrResult[$i][2] = "$".number_format($pass['amount'],2);
				$arrResult[$i][3] = "$".number_format($pass['balance'],2);
				$i++;
			}
		}
		return $arrResult;
	}
	
	public function saveRefConf($arySetting){
		if(isset($arySetting['id'])&&$arySetting['id']!=""){
			$sql = "update {$this->table}refconfig set `min_commission`='{$arySetting['min_commission']}',`min_refer`='{$arySetting['min_refer']}',`percent`='{$arySetting['percent']}',`status`='{$arySetting['status']}' where id={$arySetting['id']}";
		}else{
			$sql = "insert {$this->table}refconfig (`min_commission`,`min_refer`,`percent`,`ReferrerID`,`status`)values('{$arySetting['min_commission']}','{$arySetting['min_refer']}','{$arySetting['percent']}','{$arySetting['ReferrerID']}','{$arySetting['status']}');";
		}
		return $this->dbcon->execute_query($sql);
	}
	public function delRefConf($id){
		$sql = "delete from {$this->table}refconfig where id='$id'";
		return $this->dbcon->execute_query($sql);
	}
	
	public function getrefinfo($id){
		$query = "select * from {$this->table}refconfig where id = '{$id}'";
		$this->dbcon->execute_query($query);
		$result	=$this->dbcon->fetch_records();
		return $result[0];
	}
	public function exitsrefID($refID){
		if(isset($refID['id'])&&$refID['id']!=""){
			return true;
		}
		$query = "SELECT count(*) FROM {$this->table}refconfig where ReferrerID='{$refID['ReferrerID']}'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records();
		if($result[0][0]>0){
			return false;
		}else{
			return true;
		}
	}
	public function isinvaildrefID($refID){
		$query = "SELECT count(*) FROM {$this->table}bu_detail where ref_name='$refID' and attribute <>4";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records();
		if($result[0][0]>0){
			return true;
		}else{
			return false;
		}
	}
}
function getReferList($pageno){
	$objResponse 	= new xajaxResponse();
	$objAdminRefer 	= new adminRefer();
	$req['reflist']	= $objAdminRefer ->getReferList($_SESSION['refSearch']['startDate'],$_SESSION['refSearch']['enddate'],$_SESSION['refSearch']['nickname'],$_SESSION['refSearch']['usertype'],$_SESSION['refSearch']['paymethod'],$_SESSION['refSearch']['state'],$_SESSION['refSearch']['college'],$_SESSION['refSearch']['status'],$_SESSION['refSearch']['field'],$_SESSION['refSearch']['order'],$_SESSION['refSearch']['ispayment'],$pageno);
	$objAdminRefer -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$objAdminRefer -> smarty -> assign('req',	$req);
	
	if($_SESSION['refSearch']['ispayment']){
		if($_SESSION['refSearch']['payreport']){
			$content = $objAdminRefer -> smarty -> fetch('admin_payment_report.tpl');
		}else{
			$content = $objAdminRefer -> smarty -> fetch('admin_refer_payment_list.tpl');
		}
	}else{
		$content = $objAdminRefer -> smarty -> fetch('admin_refer_list.tpl');
	}
	$objResponse -> assign("ref_list",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

function getReferById($pageno,$referID,$field,$order){
	$objResponse 	= new xajaxResponse();
	$objAdminRefer 	= new adminRefer();
	$req['reflist']	= $objAdminRefer ->getreferrerbyRefID($referID,$pageno,$field,$order);
	$objAdminRefer -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$url = "&referID=$referID&pageid=$pageno";
	$req['order'] = $order;
	$req['field'] = $field;
	$req['sorturl'] = $url;
	$objAdminRefer -> smarty -> assign('req',	$req);
	$content = $objAdminRefer -> smarty -> fetch('admin_refer_report_list.tpl');
	$objResponse -> assign("ref_list",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}
function getexportnum(){
	$objResponse 	= new xajaxResponse();
	$objAdminRefer 	= new adminRefer();
	$rst = $objAdminRefer->getpaypallist($_SESSION['refSearch']['startDate'],$_SESSION['refSearch']['enddate'],$_SESSION['refSearch']['nickname'],$_SESSION['refSearch']['usertype']);
	if(is_array($rst)){
		$num = count($rst);
	}else{
		$num = 0;
	}
	if($num>1){
		$str = "$num records exported.";
	}else{
		$str = "$num record exported.";
	}
	$objResponse -> assign("ajaxmessage",'innerHTML',$str);
	return $objResponse;
}

function getdownlist($pageno,$field,$order='asc'){
	$objResponse 	= new xajaxResponse();
	$objAdminRefer 	= new adminRefer();
	$req['order'] = $order;
	$req['field'] = $field;
	$req['reflist']	= $objAdminRefer->getexportlist($field,$order,$pageno);
	$objAdminRefer -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$objAdminRefer -> smarty -> assign('req',	$req);
	$content = $objAdminRefer -> smarty -> fetch('admin_ref_download_list.tpl');
	$objResponse -> assign("downlist",'innerHTML',$content);
	return $objResponse;
}
function getconfiglist($pageno,$field="",$order='asc'){
	$objResponse 	= new xajaxResponse();
	$objAdminRefer 	= new adminRefer();
	$req['order'] = $order;
	$req['field'] = $field;
	$req['field']=$field;
	$req['page']=$pageno;
	$req['configlist']	= $objAdminRefer->getconfiglist($pageno,$field,$order);
	$objAdminRefer -> smarty -> assign('req',	$req);
	$content = $objAdminRefer -> smarty -> fetch('admin_refer_conf_list.tpl');
	$objResponse -> assign("conflist",'innerHTML',$content);
	return $objResponse;
}
function saveReferConfig($param){
	$objResponse = new xajaxResponse();
	$objReferrer = new adminRefer();
	$aryseting = array('min_commission'=>addslashes($param['min_commission']),'min_refer'=>addslashes($param['min_refer']),'percent'=>addslashes($param['percent']),'ReferrerID'=>addslashes($param['ReferrerID']),'status'=>$param['status']);
	if($param['id']){
		$aryseting['id']=$param['id'];
	}
	if($objReferrer->isinvaildrefID($aryseting['ReferrerID'])){
		if($objReferrer->exitsrefID($aryseting)){
			if($objReferrer->saveRefConf($aryseting)){
				if(isset($aryseting['id'])){
					$objResponse -> alert("Update Referrer Config successfully.");
					$objResponse -> script("javascript:cancleedit();");
				}else{
					$objResponse -> alert("Create Referrer Config successfully.");
					$objResponse -> assign('ReferrerID','value','');
					$objResponse -> assign('newmincommistion','value','');
					$objResponse -> assign('newpercent','value','');
					$objResponse -> assign('newminrefer','value','');
				}
			}else{
				$objResponse -> alert("Faild to Create Referrer Config.");
			}	
		}else{
			$objResponse -> alert("Duplicate Referrer ID.");
		}
	}else{
		$objResponse -> alert("Referrer ID is invalid.");
	}
	$objResponse -> script("javascript:xajax_getconfiglist(xajax.$('pageno').value);");
	return $objResponse;
}

function getReferConfig($id){
	$objResponse 	= new xajaxResponse();
	$objAdminRefer 	= new adminRefer();
	$info = $objAdminRefer ->getrefinfo($id);
	$objResponse->assign('EditID','value',$info['id']);
	$objResponse->assign('EditReferrerID','value',$info['ReferrerID']);
	$objResponse->assign('refidtxt','innerHTML',$info['ReferrerID']);
	$objResponse->assign('Editpercent','value',$info['percent']);
	$objResponse->assign('Editminrefer','value',$info['min_refer']);
	$objResponse->assign('Editmincommistion','value',$info['min_commission']);
	$objResponse->assign('newform','style.display','none');
	$objResponse->assign('editform','style.display','');
	$objResponse->assign('edstatus','value',$info['status']);
	return $objResponse;
}

function delReferConfig($id){
	$objResponse 	= new xajaxResponse();
	$objAdminRefer 	= new adminRefer();
	if($objAdminRefer->delRefConf($id)){
		$objResponse -> alert('Delete Referrer Config successfully.');
		$objResponse -> script("javascript:xajax_getconfiglist(xajax.$('pageno').value);");	
	}else{
		$objResponse -> alert('Faild to delete Referrer Config.');
	}
	return $objResponse;
}
?>