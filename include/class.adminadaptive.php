<?php
/**
 * Wed Oct 08 21:18:33 GMT+08:00 2008 21:18:33
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * main class of admin
 * ------------------------------------------------------------
 * include\class.adminmain.php
 */

class adminAdaptive extends common {
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
			$_query = "insert into ".$this->table."commission(`commission_rate`, `commission_max`, `modify_date`, `uid`) values('". $arrForm['commission_rate'] ."', '". $arrForm['commission_max'] ."', '". time() ."', '". $uid ."')" ;
			
			$res = $this -> dbcon -> execute_query($_query) ;			
			$strResult = $res ? "Commission Information Changed Successfully" : "Commission Information Changed Failed";
		}

		return $strResult;
	}
	
	function paymentRecords($aryset="",$field="orderDate",$order='ASC',$curpage=1){
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
		if($fromDate!=0){
			$whsql .= " and rf.order_date >= '$fromDate' ";
		}
		if($toDate!=0){
			$whsql .= " and rf.order_date <= '$toDate' ";
		}
		$sorder = "";
		switch ($field){
			case 'buyer_nickname':
			case 'buyer_email':
			case 'seller_webname':
			case 'seller_email':
			case 'items':
			case 'amount':
			case 'paymethod':
			case 'orderDate':
				$sorder = " order by $field $order ";
				break;
			default:
				$sorder = " order by orderDate DESC ";
				break;
		}
		
		$query = "SELECT count(*) from {$this->table}order_reviewref rf where rf.type ='purchasing' $whsql ";
		$this->dbcon->execute_query($query) ;
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		$fields = "buy.bu_nickname as buyer_nickname, buy.bu_email as buyer_email, sl.bu_name as seller_webname,".
				  "sl.bu_email as seller_email,pro.item_name as items,rf.amount as amount,rf.description as paymethod,".
				  "rf.order_date as orderDate";
		$query = "SELECT $fields from {$this->table}order_reviewref rf ".
				 " left join {$this->table}bu_detail buy on rf.buyer_id=buy.StoreID ".
				 " left join {$this->table}bu_detail sl on rf.StoreID=sl.StoreID ".
				 " left join {$this->table}product pro on rf.pid=pro.pid ".
				 " where rf.type ='purchasing' $whsql $sorder limit $start,$perPage";
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
				'onclick'	 => 'javascript:xajax_getpaymentRecords(\'%d\',xajax.getFormValues(\'searchForm\'),\''.$field.'\',\''.$order.'\');return false;',
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

}

/*********************
* xajax function
**********************/

/*******************************
* Commission function of xajax
*******************************/

function saveCommission($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminAdaptive 	= &$GLOBALS['objAdminAdaptive'];

	$messages	=	$objAdminAdaptive -> saveCommission($objForm);

	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);

	return $objResponse;
}

function getpaymentRecords($curpage,$arySet="",$field="orderDate",$order="ASC"){
	$objResponse 	= new xajaxResponse();
	$objAdminAdaptive	=	new adminAdaptive();
	$req['list'] = $objAdminAdaptive->paymentRecords($arySet,$field,$order,$curpage);
	$objAdminAdaptive->smarty->assign('PBDateFormat',DATAFORMAT_DB);
	$objAdminAdaptive->smarty->assign('req',$req);
	$content = $objAdminAdaptive->smarty->fetch('admin_adaptivepayment_list.tpl');
	$objResponse->assign('tabledatalist','innerHTML',$content);
	return $objResponse;
}
?>