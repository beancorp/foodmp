<?php
/**
 * Fri Oct 10 16:08:34 GMT+08:00 2008 16:08:34
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * payment function class
 * ------------------------------------------------------------
 * \include\class.adminpayment.php
 */

class adminPayment extends common {
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
	 * Here generate all orders' information.
	 * the value of OrderType:
	 * 0 - normal order
	 * 1 - gift Certificate order
	 * 2 - ad booking order
	 * 3 - referral order
	 * ... if any other type, update here
	 *
	 * @param int $pageno
	 * @param str $params  	fromDate, toDate, lastMonth, listType
	 * @return array
	 */
	public function paymentDetailsDateWiseReports($pageno=1,$params=''){
		$arrResult	= null;
		$perPage	=	15;
		$pageno		=	$pageno ? $pageno : 1;

		$arrParam	=	unserialize($params);
		if ($arrParam) {
			$fromDate	=	$arrParam['fromDate'];
			$toDate		=	$arrParam['toDate'];
			$lastMonth	=	$arrParam['lastMonth'];
			$listType	=	$arrParam['listType'];
		}

		$date			=	date("Y-m-d");
		if (!$listType && $fromDate != '' && $toDate != '') {
			$fromDate 	= $this->changeDate($fromDate);
			$toDate 	= $this->changeDate($toDate);
			$sqlWhere	=	" WHERE OrderType=0 and OrderDate >= '$fromDate' AND OrderDate <= '$toDate'";
			$arrResult['message']	=  "Payments Reports From ".$this->changeDate($fromDate,'ymd','mdy')." To ".$this->changeDate($toDate,'ymd','mdy');
		}elseif (!$listType && $fromDate != '' && $toDate == ''){
			$fromDate 	= $this->changeDate($fromDate);
			$arrResult['message']	=  "Payments Reports From ".$this->changeDate($fromDate,'ymd','mdy')." To Now";
			$sqlWhere	=	" WHERE OrderType=0 and OrderDate >= '$fromDate' AND OrderDate <= '$date'";
		}else {
			$lastmonth		=	$this->dateAdd('m',-1,time());
			$datelast		=	date("Y-m-d",$lastmonth);
			$arrResult['message']	=  "Payments Reports For the Last Month";
			$sqlWhere	=	" WHERE OrderType=0 and OrderDate >= '$datelast' AND OrderDate <= '$date'";
		}
		$QUERY			=	"SELECT count(*) FROM  ".$this->table."order_detail $sqlWhere";
		$result			=	$this->dbcon->execute_query($QUERY) ;
		$grid			=	$this->dbcon->fetch_records() ;
		$count			=	$grid[0][0];
		
		($pageno * $perPage > $count) ? $pageno = ceil($count/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		$QUERY			=	"SELECT OrderID,orderDate FROM  ".$this->table."order_detail $sqlWhere LIMIT $start,$perPage";
		$result			=	$this->dbcon->execute_query($QUERY) ;
		$grid			=	$this->dbcon->fetch_records() ;
		$OrderAddr		=	array();
		// usset grid if empty
		if ($count) {
			if (!$grid) $grid = array();
			for($i=0;$i<sizeof($grid);$i++){
				$OrderAddr[]	=	$grid[$i]['OrderID'];
				$arrResult['list'][$i]['orderDate'] = $grid[$i]['orderDate'];
			}
			$j=0;
			foreach($OrderAddr AS $key=>$values){
				$arrResult['list'][$j]['OrderID'] = $values;

				$QUERY			=	"SELECT StoreID,pid,Quantity,Date FROM ".$this->table."shopping_cart WHERE OrderID ='$values'";
				$result			=	$this->dbcon->execute_query($QUERY) ;
				$grid			=	$this->dbcon->fetch_records() ;
				$QUERY			=	"SELECT bu_name FROM ".$this->table."bu_detail WHERE StoreID ='".$grid[0]['StoreID']."' ";
				$result1		=	$this->dbcon->execute_query($QUERY) ;
				$grid1			=	$this->dbcon->fetch_records();
				$arrResult['list'][$j]['StoreID'] 	= $grid1[0]['StoreID'];
				$arrResult['list'][$j]['bu_name'] 	= $grid1[0]['bu_name'];
				$totalCost		=	0;
				for($i=0;$i<sizeof($grid);$i++){
					$QUERY1			=	"SELECT price FROM ".$this->table."product WHERE pid ='".$grid[$i]['pid']."'";
					$result1		=	$this->dbcon->execute_query($QUERY1) ;
					$grid1			=	$this->dbcon->fetch_records() ;
					$totalCost		+=	$grid[$i]['Quantity']*$grid1[0]['price'];
				}
				$arrResult['list'][$j]['amount'] = "$".$totalCost;
				$j++;
			}
			$params = array(
			'perPage'    => "$perPage",
			'totalItems' => "$count",
			'currentPage'=> "$pageno",
			'delta'      => 15,
			'onclick'	 => 'javascript:xajax_paymentDetailsDateWiseReports(\'%d\',xajax.$(\'searchparam\').value);return false;',
			'append'     => false,
			'urlVar'     => 'pageno',
			'path'		 => '#',
			'fileName'   => '%d',
			);
			$pager = & Pager::factory($params);
			$arrResult['links'] 		= $pager->getLinks();
			$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			$arrResult['searchparam']	=  $strParam;
			$arrResult['pageno']	=  $pageno;
			unset($pager,$params);
		}

		return $arrResult;
	}

	/**
	 * Here generate all orders' information.
	 * the value of OrderType:
	 * 0 - normal order
	 * 1 - gift Certificate order
	 * 2 - ad booking order
	 * 3 - referral order
	 * ... if any other type, update here
	 *
	 * @param int $pageno
	 * @param str $params  	fromDate, toDate, StoreID
	 * @return array
	 */
	public function paymentDetailsDateWiseReportsStore($pageno=1,$params=''){
		$arrResult	= null;
		$perPage	=	15;
		$pageno		=	$pageno ? $pageno : 1;

		$arrParam	=	unserialize($params);
		if ($arrParam) {
			$fromDate	=	$arrParam['fromDate'];
			$toDate		=	$arrParam['toDate'];
			$StoreID	=	$arrParam['store'];
		}

		$date			=	date("Y-m-d");
		if  ($fromDate != '' && $toDate != '') {
			$fromDate 	= $this->changeDate($fromDate);
			$toDate 	= $this->changeDate($toDate);
			$arrResult['message']	=  "'$StoreID' Payments Reports From ".$this->changeDate($fromDate,'ymd','mdy')." To ".$this->changeDate($toDate,'ymd','mdy');
			$sqlWhere	=	" WHERE t1.storeId='$StoreID' and t2.OrderType=0 and t2.OrderDate >= '$fromDate' AND t2.OrderDate <= '$toDate'";
		}elseif($fromDate != ''){
			$fromDate 	= $this->changeDate($fromDate);
			$arrResult['message']	=  "'$StoreID' Payments Reports From ".$this->changeDate($fromDate,'ymd','mdy')." To Now";
			$sqlWhere	=	" WHERE t1.storeId='$StoreID' and t2.OrderType=0 and t2.OrderDate >= '$fromDate' AND t2.OrderDate <= '$date'";
		}else {
			$arrResult['message']	=  "'$StoreID' Payments Reports of All";
			$sqlWhere	=	" WHERE t1.storeId='$StoreID' and t2.OrderType=0";
		}
		$QUERY			=	"SELECT count(*) FROM ".$this->table."shopping_cart as t1 left join ".$this->table."order_detail as t2 on t1.OrderID = t2.OrderID $sqlWhere group by t1.OrderID";
		$result			=	$this->dbcon->execute_query($QUERY) ;
		$grid			=	$this->dbcon->fetch_records() ;
		$count			=	count($grid);
		
		($pageno * $perPage > $count) ? $pageno = ceil($count/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		$QUERY			=	"SELECT t1.OrderID,t1.StoreID,t2.OrderDate FROM ".$this->table."shopping_cart as t1 left join ".$this->table."order_detail as t2 on t1.OrderID = t2.OrderID $sqlWhere group by t1.OrderID LIMIT $start,$perPage";
		$result			=	$this->dbcon->execute_query($QUERY) ;
		$arrTemp		=	$this->dbcon->fetch_records() ;
//		$arrResult['query'] = $QUERY;
		// usset grid if empty
		if ($count && is_array($arrTemp)) {
			$j = 0;
			foreach($arrTemp AS $temp){
				$arrResult['list'][$j]['OrderID'] = $temp['OrderID'];
				$arrResult['list'][$j]['StoreID'] 	= $temp['StoreID'];
				$arrResult['list'][$j]['OrderDate'] = $this->changeDate($temp['OrderDate'],'ymd','mdy');
				$QUERY			=	"SELECT StoreID,pid,Quantity,Date FROM ".$this->table."shopping_cart WHERE OrderID ='".$temp['OrderID']."'";
				$result			=	$this->dbcon->execute_query($QUERY) ;
				$grid			=	$this->dbcon->fetch_records() ;
				$QUERY			=	"SELECT bu_name FROM ".$this->table."bu_detail WHERE StoreID ='".$temp['StoreID']."' ";
				$result1		=	$this->dbcon->execute_query($QUERY) ;
				$grid1			=	$this->dbcon->fetch_records();

				$arrResult['list'][$j]['bu_name'] 	= $grid1[0]['bu_name'];
				$totalCost		=	0;
				for($i=0;$i<sizeof($grid);$i++){
					$QUERY1			=	"SELECT price FROM ".$this->table."product WHERE pid ='".$grid[$i]['pid']."'";
					$result1		=	$this->dbcon->execute_query($QUERY1) ;
					$grid1			=	$this->dbcon->fetch_records() ;
					$totalCost		+=	$grid[$i]['Quantity']*$grid1[0]['price'];
				}
				$arrResult['list'][$j]['amount'] = "$".$totalCost;
				$j++;
			}
			$params = array(
			'perPage'    => $perPage,
			'totalItems' => $count,
			'currentPage'=> $pageno,
			'delta'      => 15,
			'onclick'	 => 'javascript:xajax_paymentDetailsDateWiseReportsStore(\'%d\',xajax.$(\'searchparam\').value);return false;',
			'append'     => false,
			'urlVar'     => 'pageno',
			'path'		 => '#',
			'fileName'   => '%d',
			);
			$pager = & Pager::factory($params);
			$arrResult['links'] 		= $pager->getLinks();
			$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			$arrResult['searchparam']	=  $strParam;
			$arrResult['pageno']	=  $pageno;
			unset($pager,$params);
		}

		return $arrResult;
	}

	/**
	 * get store name of order
	 *
	 * @return array
	 */
	public function getOrderStoreName(){
		$arrResult = null;
		// It's likely to change on below.
		$query = "select StoreID , bu_name from ".$this->table."bu_detail  where CustomerType ='seller' and is_popularize_store=0" ;
		$this->dbcon->execute_query($query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)) {
			$arrResult	=	$arrTemp;
		}
		
		return $arrResult;
	}
	
	function purchaseRecords($aryset="",$field="orderDate",$order='DESC',$curpage=1){
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$fromDate = 0;
		$toDate = 0;
		if(DATAFORMAT_DB=="%m/%d/%Y"){
			if(isset($aryset['fromDate'])&&$aryset['fromDate']!=""){
				list($month,$day,$year) = split('/',$aryset['fromDate']);
				$fromDate = mktime(0,0,0,$month,$day,$year);
			}
			if(isset($aryset['toDate'])&&$aryset['toDate']!=""){
				list($month,$day,$year) = split('/',$aryset['toDate']);
				$toDate = mktime(23,59,59,$month,$day,$year);
			}
		}else{
			if(isset($aryset['fromDate'])&&$aryset['fromDate']!=""){
				list($day,$month,$year) = split('/',$aryset['fromDate']);
				$fromDate = mktime(0,0,0,$month,$day,$year);
			}
			if(isset($aryset['toDate'])&&$aryset['toDate']!=""){
				list($day,$month,$year) = split('/',$aryset['toDate']);
				$toDate = mktime(23,59,59,$month,$day,$year);
			}
		}
		$whsql = "";
		if ($aryset['attribute'] != '') {
			$whsql .= " and rf.attribute='{$aryset['attribute']}'";
		}
		if($fromDate!=0){
			$whsql .= " and rf.order_date >= '$fromDate' ";
		}
		if($toDate!=0){
			$whsql .= " and rf.order_date <= '$toDate' ";
		}
		if($aryset['p_status'] != ''){
			$whsql .= " and lower(rf.p_status) = '{$aryset['p_status']}' ";
		}
		if($aryset['commission_type'] != '' || $aryset['commission_type'] == '0'){
			$whsql .= " and rf.commission_type = '{$aryset['commission_type']}' ";
		}
		$sorder = "";
		switch ($field){
			case 'attribute':
			case 'buyer_nickname':
			case 'buyer_email':
			case 'seller_webname':
			case 'seller_email':
			case 'items':
			case 'amount':
			case 'commission':
			case 'commission_type':
			case 'paymethod':
			case 'p_status':
			case 'orderDate':
				$sorder = " order by $field $order ";
				break;
			default:
				$sorder = " order by orderDate DESC ";
				break;
		}
		
		$query = "SELECT count(*) from {$this->table}order_reviewref rf where (rf.type ='purchasing' or rf.type ='bid') $whsql ";
		$this->dbcon->execute_query($query) ;
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		$start  = $start < 0 ? 0 : $start;
		
		$fields = "buy.bu_nickname as buyer_nickname, buy.bu_email as buyer_email, sl.bu_name as seller_webname,".
				  "sl.bu_email as seller_email,pro.item_name as items,rf.ref_id,rf.p_status,rf.attribute,rf.amount as amount,rf.commission_type,rf.commission,rf.description as paymethod,".
				  "rf.order_date as orderDate";
		$query = "SELECT $fields from {$this->table}order_reviewref rf ".
				 " left join {$this->table}bu_detail buy on rf.buyer_id=buy.StoreID ".
				 " left join {$this->table}bu_detail sl on rf.StoreID=sl.StoreID ".
				 " left join {$this->table}product pro on rf.pid=pro.pid ".
				 " where (rf.type ='purchasing' or rf.type ='bid') $whsql $sorder limit $start,$perPage";
		$this->dbcon->execute_query($query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		
		$arrResult = array();
		$arrResult['sort'] = array('page'=>$pageno,'field'=>$field,'order'=>$order);

		
		if(is_array($arrTemp)){
			$arrResult['purlist'] = $arrTemp;
			$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getpurchaseRecords(\'%d\',xajax.getFormValues(\'mainSearch\'),\''.$field.'\',\''.$order.'\');return false;',
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
	
	function exportpurchaseRecords($aryset=""){
		$fromDate = 0;
		$toDate = 0;
		if(DATAFORMAT_DB=="%m/%d/%Y"){
			$datef = "m/d/Y";
			if(isset($aryset['fromDate'])&&$aryset['fromDate']!=""){
				list($month,$day,$year) = split('/',$aryset['fromDate']);
				$fromDate = mktime(0,0,0,$month,$day,$year);
			}
			if(isset($aryset['toDate'])&&$aryset['toDate']!=""){
				list($month,$day,$year) = split('/',$aryset['toDate']);
				$toDate = mktime(23,59,59,$month,$day,$year);
			}
		}else{
			$datef = "d/m/Y";
			if(isset($aryset['fromDate'])&&$aryset['fromDate']!=""){
				list($day,$month,$year) = split('/',$aryset['fromDate']);
				$fromDate = mktime(0,0,0,$month,$day,$year);
			}
			if(isset($aryset['toDate'])&&$aryset['toDate']!=""){
				list($day,$month,$year) = split('/',$aryset['toDate']);
				$toDate = mktime(23,59,59,$month,$day,$year);
			}
		}
		$whsql = "";
		if ($aryset['attribute'] || $aryset['attribute'] == '0') {
			$whsql .= " and rf.attribute='{$aryset['attribute']}'";
		}
		if($fromDate!=0){
			$whsql .= " and rf.order_date >= '$fromDate' ";
		}
		if($toDate!=0){
			$whsql .= " and rf.order_date <= '$toDate' ";
		}
		if($aryset['p_status'] != ''){
			$whsql .= " and lower(rf.p_status) = '{$aryset['p_status']}' ";
		}
		if($aryset['commission_type'] != '' || $aryset['commission_type'] == '0'){
			$whsql .= " and rf.commission_type = '{$aryset['commission_type']}' ";
		}
		
		$fields = "buy.bu_nickname as buyer_nickname, buy.bu_email as buyer_email, sl.bu_name as seller_webname,".
				  "sl.bu_email as seller_email,pro.item_name as items,rf.amount as amount,rf.description as paymethod,".
				  "IF(rf.commission_type=1, 'Automatic', 'Manual') as approach_type,rf.commission,(rf.amount - rf.commission) as seller_amount,sl.bu_paypal as paypal_account,rf.order_date as orderDate,rf.attribute,rf.OrderID_foodwine";
		$query = "SELECT $fields from {$this->table}order_reviewref rf ".
				 " left join {$this->table}bu_detail buy on rf.buyer_id=buy.StoreID ".
				 " left join {$this->table}bu_detail sl on rf.StoreID=sl.StoreID ".
				 " left join {$this->table}product pro on rf.pid=pro.pid ".
				 " where (rf.type ='purchasing' or rf.type ='bid') $whsql".
				 " order by rf.order_date desc";
		$this->dbcon->execute_query($query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		$aryResult[0] = array('Buyer Nickname','Buyer Email','Seller Website Name','Seller Email','Items','Total','Payment Method','Approach Type','Commission','Total(without commission)','Paypal Account','Date');
		if(is_array($arrTemp)){
			$i=1;
			foreach ($arrTemp as $pass){
				if ($pass['attribute'] == '5') {
					$pass['items'] = '';
					 $query = "SELECT product.item_name from {$this->table}product_foodwine product ".
					 " left join {$this->table}order_detail_foodwine detail on product.pid=detail.pid ".
					 " where detail.OrderID='{$pass['OrderID_foodwine']}'";
				
					$this->dbcon->execute_query($query);
					$items = $this->dbcon->fetch_records(true);
					/*foreach ($items as $key => $item) {
						$pass['items'] .= ($key == 0 ? '' : ';').$item['item_name'];
					}*/
					$pass['items'] .= $items[0]['item_name'].'...';
				}
				$aryResult[$i][0] = $pass['buyer_nickname'];
				$aryResult[$i][1] = $pass['buyer_email'];
				$aryResult[$i][2] = $pass['seller_webname'];
				$aryResult[$i][3] = $pass['seller_email'];
				$aryResult[$i][4] = $pass['items'];
				$aryResult[$i][5] = "$".$pass['amount'];
				$aryResult[$i][6] = $pass['paymethod'];
				$aryResult[$i][7] = $pass['approach_type'];
				$aryResult[$i][8] = "$".$pass['commission'];
				$aryResult[$i][9] = "$".$pass['seller_amount'];
				$aryResult[$i][10] = $pass['paypal_account'];
				$aryResult[$i][11] = date($datef,$pass['orderDate']);
				$i++;
			}
		}
		return $aryResult;
	}
	
	function exportpurchaseRecordsPaymentInfo($aryset=""){
		$fromDate = 0;
		$toDate = 0;
		if(DATAFORMAT_DB=="%m/%d/%Y"){
			$datef = "m/d/Y";
			if(isset($aryset['fromDate'])&&$aryset['fromDate']!=""){
				list($month,$day,$year) = split('/',$aryset['fromDate']);
				$fromDate = mktime(0,0,0,$month,$day,$year);
			}
			if(isset($aryset['toDate'])&&$aryset['toDate']!=""){
				list($month,$day,$year) = split('/',$aryset['toDate']);
				$toDate = mktime(23,59,59,$month,$day,$year);
			}
		}else{
			$datef = "d/m/Y";
			if(isset($aryset['fromDate'])&&$aryset['fromDate']!=""){
				list($day,$month,$year) = split('/',$aryset['fromDate']);
				$fromDate = mktime(0,0,0,$month,$day,$year);
			}
			if(isset($aryset['toDate'])&&$aryset['toDate']!=""){
				list($day,$month,$year) = split('/',$aryset['toDate']);
				$toDate = mktime(23,59,59,$month,$day,$year);
			}
		}
		$whsql = "";
		if ($aryset['attribute'] || $aryset['attribute'] == '0') {
			$whsql .= " and rf.attribute='{$aryset['attribute']}'";
		}
		if($fromDate!=0){
			$whsql .= " and rf.order_date >= '$fromDate' ";
		}
		if($toDate!=0){
			$whsql .= " and rf.order_date <= '$toDate' ";
		}
		if($aryset['p_status'] != ''){
			$whsql .= " and lower(rf.p_status) = '{$aryset['p_status']}' ";
		}
		if($aryset['commission_type'] != '' || $aryset['commission_type'] == '0'){
			$whsql .= " and rf.commission_type = '{$aryset['commission_type']}' ";
		}
		
		$fields = "buy.bu_nickname as buyer_nickname, buy.bu_email as buyer_email, sl.bu_name as seller_webname,".
				  "sl.bu_email as seller_email,pro.item_name as items,rf.amount as amount,rf.description as paymethod,".
				  "IF(rf.commission_type=1, 'Automatic', 'Manual') as approach_type,rf.commission,(rf.amount - rf.commission) as seller_amount,sl.bu_paypal as paypal_account,rf.order_date as orderDate,rf.attribute,rf.OrderID_foodwine";
		$query = "SELECT $fields from {$this->table}order_reviewref rf ".
				 " left join {$this->table}bu_detail buy on rf.buyer_id=buy.StoreID ".
				 " left join {$this->table}bu_detail sl on rf.StoreID=sl.StoreID ".
				 " left join {$this->table}product pro on rf.pid=pro.pid ".
				 " where (rf.type ='purchasing' or rf.type ='bid') $whsql".
				 " order by rf.order_date desc";
		$this->dbcon->execute_query($query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		//$aryResult[0] = array('Buyer Nickname','Buyer Email','Seller Website Name','Seller Email','Items','Total','Payment Method','Approach Type','Commission','Total(without commission)','Paypal Account','Date');
		if(is_array($arrTemp)){
			$i=0;
			foreach ($arrTemp as $pass){
				if ($pass['attribute'] == '5') {
					$pass['items'] = '';
					 $query = "SELECT product.item_name from {$this->table}product_foodwine product ".
					 " left join {$this->table}order_detail_foodwine detail on product.pid=detail.pid ".
					 " where detail.OrderID='{$pass['OrderID_foodwine']}'";
				
					$this->dbcon->execute_query($query);
					$items = $this->dbcon->fetch_records(true);
					/*foreach ($items as $key => $item) {
						$pass['items'] .= ($key == 0 ? '' : ';').$item['item_name'];
					}*/
					$pass['items'] .= $items[0]['item_name'].'...';
				}
				$aryResult[$i][0] = $pass['paypal_account'];
				$aryResult[$i][1] = $pass['seller_amount'];
				$aryResult[$i][2] = CURRENCYCODE;
				$aryResult[$i][3] = $pass['items'];
				$aryResult[$i][4] = 'Order Date: '.date($datef,$pass['orderDate']);
				$i++;
			}
		}
		return $aryResult;
	}

	public function getCommission()
	{
		$sql = "SELECT * FROM {$this->table}commission WHERE 1 ORDER BY id DESC, modify_date DESC LIMIT 1";
		return $this->dbcon->getOne($sql);
	}
	
	public function saveCommission($arrForm){
		$strResult	=	'';

		if (! is_array($arrForm)) {
			$strResult	=	'';
		}elseif (empty($arrForm['commission_rate'])){
			$strResult	=	'Please enter the commission rate.';
		}elseif (empty($arrForm['commission_max'])){
			$strResult	=	'Please enter the max commission.';
		}elseif (!is_numeric($arrForm['commission_rate']) || $arrForm['commission_rate'] > 1){
			$strResult	=	'The commission rate must to be number and less than 1.';
		}elseif (!is_numeric($arrForm['commission_max'])){
			$strResult	=	'The commission rate must to be number.';
		}else {
			$uid = $_SESSION['uid'] ;
			$_query = "insert into ".$this->table."commission(`commission_type`,`commission_rate`, `commission_max`, `modify_date`, `uid`) values('". $arrForm['commission_type'] ."', '". $arrForm['commission_rate'] ."', '". $arrForm['commission_max'] ."', '". time() ."', '". $uid ."')" ;
			
			$res = $this -> dbcon -> execute_query($_query) ;			
			$strResult = $res ? "Commission Information Changed Successfully" : "Commission Information Changed Failed";
		}

		return $strResult;
	}
	
	/**
	 * @param int $intId
	 * @param int $intPage
	 * @return array
	 */
	public function viewPurchase($intId, $intPage)
	{
		$fields = "buy.bu_nickname as buyer_nickname, buy.bu_email as buyer_email, sl.bu_name as seller_webname,".
				  "sl.bu_paypal as seller_paypal,sl.bu_email as seller_email,pro.item_name as items,rf.ref_id,rf.p_status,rf.OrderID_foodwine,rf.attribute,rf.amount as amount,rf.commission_type,rf.commission,(rf.amount-rf.commission) as seller_amount,rf.description as paymethod,".
				  "rf.order_date as orderDate";
		$query = "SELECT $fields from {$this->table}order_reviewref rf ".
				 " left join {$this->table}bu_detail buy on rf.buyer_id=buy.StoreID ".
				 " left join {$this->table}bu_detail sl on rf.StoreID=sl.StoreID ".
				 " left join {$this->table}product pro on rf.pid=pro.pid ".
				 " where (rf.type ='purchasing' or rf.type ='bid') and rf.ref_id='$intId'";
		$arrResult = $this->dbcon->getOne($query);
		
		if ($arrResult['attribute'] == '0') {
			$item_name = $arrResult['items'];
			$arrResult['items'] = array();
			$arrResult['items'][0] = array('item_name' => $item_name);
		} elseif ($arrResult['attribute'] == '5') {
			$query = "SELECT product.item_name from {$this->table}product_foodwine product ".
				 " left join {$this->table}order_detail_foodwine detail on product.pid=detail.pid ".
				 " where detail.OrderID='{$arrResult['OrderID_foodwine']}'";
			
			$this->dbcon->execute_query($query);
			$items = $this->dbcon->fetch_records(true);
			$arrResult['items'] = $items;
		}
		$arrResult['pageno'] = $intPage;
		
		return $arrResult;
	}

}

/*********************
* xajax function
**********************/

/*******************************
* email function of xajax
*******************************/


function paymentDetailsDateWiseReports($pageno,$params){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPayment	=	&$GLOBALS['objAdminPayment'];

	//$arrParams = unserialize($params);
	$req['list'] = $objAdminPayment -> paymentDetailsDateWiseReports($pageno,$params);
	$req['nofull'] = true ;

	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_payment.tpl');

	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	//$objResponse -> alert($req['list']['links']['all']);
	return $objResponse;
}

function paymentDetailsDateWiseReportsSearch($objForm){
	$objResponse 	= new xajaxResponse();
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPayment	=	&$GLOBALS['objAdminPayment'];

	$params = serialize($objForm);
	$req['list'] = $objAdminPayment -> paymentDetailsDateWiseReports(1,$params);
	$req['nofull'] = true ;

	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_payment.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign('searchparam', 'value' , $params);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}


function paymentDetailsDateWiseReportsStore($pageno,$params){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPayment	=	&$GLOBALS['objAdminPayment'];

	//$arrParams = unserialize($params);
	$req['list'] = $objAdminPayment -> paymentDetailsDateWiseReportsStore($pageno,$params);
	$req['nofull'] = true ;

	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_payment.tpl');

	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	//$objResponse -> alert($req['list']['links']['all']);
	return $objResponse;
}

function paymentDetailsDateWiseReportsSearchStore($objForm){
	$objResponse 	= new xajaxResponse();
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPayment	=	&$GLOBALS['objAdminPayment'];

	$params = serialize($objForm);

	$req['list'] = $objAdminPayment -> paymentDetailsDateWiseReportsStore(1,$params);
	$req['nofull'] = true ;
	$req['display']	= 'storerep';
	
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_payment.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign('searchparam', 'value' , $params);
	$objResponse -> assign("pageno",'value',1);
//	$objResponse -> alert($req['list']['query']);	
	
	return $objResponse;
}

function getpurchaseRecords($curpage,$arySet="",$field="orderDate",$order="DESC"){
	$objResponse 	= new xajaxResponse();
	$objAdminPayment	=	new adminPayment();
	$req['list'] = $objAdminPayment ->purchaseRecords($arySet,$field,$order,$curpage);
	$objAdminPayment->smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$objAdminPayment->smarty -> assign('req',$req);
	$content = $objAdminPayment->smarty -> fetch('admin_purchase_list.tpl');
	$objResponse->assign('tabledatalist','innerHTML',$content);
	return $objResponse;
}

function saveCommission($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminPayment	=	new adminPayment();

	$messages	=	$objAdminPayment -> saveCommission($objForm);

	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);

	return $objResponse;
}

function viewPurchase($intId, $intPage)
{
	$smarty      = &$GLOBALS['smarty'];
	$objAdminPayment = &$GLOBALS['objAdminPayment'];

	$req['info'] = $objAdminPayment->viewPurchase($intId, $intPage);
	$smarty->assign('req', $req);
	$smarty -> assign('PBDateFormat',DATAFORMAT_DB);

	$objResponse = new xajaxResponse();
	$objResponse->assign('tabledatalist', 'innerHTML', $smarty->fetch('admin_purchase_view.tpl'));

	return $objResponse;
}
?>