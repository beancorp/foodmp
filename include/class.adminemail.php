<?php
/**
 * Fri Oct 10 08:20:29 GMT+08:00 2008 08:20:29
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * email function class
 * ------------------------------------------------------------
 * \include\class.adminemail.php
 */

class adminEmail extends common {
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
	 * Store Wise Email Subscription Report
	 *
	 * @param int     $pageno
	 * @return array
	 */
	public function StoreWiseEmailReportList($pageno=1){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	15;

		$_query = " select count(*) as t from ".$this->table."emailalert group by storeid" ;
		$this -> dbcon->execute_query($_query) ;
		$arrTemp = $this -> dbcon->fetch_records() ;
		$totalRecords = count( $arrTemp);
		
		($pageno * $perPage > $totalRecords) ? $pageno = ceil($totalRecords/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalRecords) {
			$_query = " select t1.storeid , t1.email ,count(t1.storeid) as records, t2.bu_name from ".
			$this->table."emailalert as t1 ".
			"left join ".$this->table."bu_detail as t2 on t1.storeid = t2.StoreId group by t1.storeid limit $start, $perPage " ;
			$this -> dbcon->execute_query($_query) ;
			
			$arrTemp = $this -> dbcon -> fetch_records(true) ;
			if (is_array($arrTemp)) {
				$arrResult['list']	=	$arrTemp;
				//pager
				$params = array(
				'perPage'    => "$perPage",
				'totalItems' => "$totalRecords",
				'currentPage'=> "$pageno",
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_StoreWiseEmailReportList(\'%d\',\'tabledatalist\');return false;',
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
			}
		}
		unset($arrTemp,$pager);

		return $arrResult;
	}
	
	/**
	 * Fresh Produce Report Subscriber
	 *
	 * @param int     $pageno
	 * @return array
	 */
	public function ReportSubscriberList($pageno=1){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	15;

		$_query = " select count(*) as t from ".$this->table."report_subscribe group by email" ;
		$this -> dbcon->execute_query($_query) ;
		$arrTemp = $this -> dbcon->fetch_records() ;
		$totalRecords = count( $arrTemp);
		
		($pageno * $perPage > $totalRecords) ? $pageno = ceil($totalRecords/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalRecords) {
			$_query = " select t1.* from ".$this->table."report_subscribe as t1 ".
			"group by t1.email limit $start, $perPage " ;
			$this -> dbcon->execute_query($_query) ;
			
			$arrTemp = $this -> dbcon -> fetch_records(true) ;
			if (is_array($arrTemp)) {
				$arrResult['list']	=	$arrTemp;
				//pager
				$params = array(
				'perPage'    => "$perPage",
				'totalItems' => "$totalRecords",
				'currentPage'=> "$pageno",
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_ReportSubscriberList(\'%d\',\'tabledatalist\');return false;',
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
			}
		}
		unset($arrTemp,$pager);

		return $arrResult;
	}
	
	
	public function exportSubscriberlist(){		
		$fileds = " email";
		$query = "select email,subscribe_date from {$this->table}report_subscribe where 1";
		
		$this->dbcon->execute_query($query);
		$arrTemp	=	$this->dbcon->fetch_records(true);
		
		if(DATAFORMAT_DB=="%m/%d/%Y"){
			$datef = "m/d/Y";
		}else{
			$datef = "d/m/Y";
		}
		
		$tmparry = array();
		$tmparry[0]  = array('Email Address','Subscribe Date');
		$i=1;
		if(is_array($arrTemp)){
			foreach ($arrTemp as $key=>$pass){
				$tmparry[$i][0] = $pass['email'];
				$tmparry[$i][1] = date($datef, $pass['subscribe_date']);
				$i++;
			}
		}
		
		return  $tmparry;
	}
	
	/**
	 * Show email of Store Wise Email Report List
	 *
	 * @return array
	 */
	public function StoreWiseEmailReportListShow($storeid){
		$arrResult	=	null;
		$_query	= "select t1.storeid, t1.email, t2.bu_name from ".$this->table."emailalert as t1 ".
		"left join ".$this->table."bu_detail as t2 on t1.storeid = t2.StoreId where t1.storeid ='$storeid' ";
		$this -> dbcon->execute_query($_query) ;
		$arrTemp = $this -> dbcon -> fetch_records(true) ;
		if (is_array($arrTemp)) {
			$arrResult['list']	=	$arrTemp;
		}
		unset($arrTemp);
		
		return $arrResult;
	}

	
	public function CustomerWiseEmailReportList($pageno=1){
		$arrResult	=	null;
		$perPage	=	15;
		
		$where	= "where t2.StoreID=t1.StoreID AND t2.level =2 ";
		$_query = " select count(t1.storeid) from  ".$this->table."bu_detail as t1,".$this->table."login as t2 $where" ;
		$this -> dbcon->execute_query($_query) ;
		$arrTemp = $this -> dbcon->fetch_records() ;
		$totalRecords = $arrTemp[0][0];
		
		($pageno * $perPage > $totalRecords) ? $pageno = ceil($totalRecords/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalRecords) {
			$_query = " select t1.bu_name ,t2.id from  ".$this->table."bu_detail as t1,".$this->table."login as t2 $where limit $start, $perPage " ;
			$this -> dbcon->execute_query($_query) ;
					
//		return $_query;
			$arrTemp = $this -> dbcon -> fetch_records(true) ;
			if (is_array($arrTemp)) {
				$arrResult['list']	=	$arrTemp;
				//pager
				$params = array(
				'perPage'    => "$perPage",
				'totalItems' => "$totalRecords",
				'currentPage'=> "$pageno",
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_CustomerWiseEmailReportList(\'%d\',\'tabledatalist\');return false;',
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
			}
		}
		unset($arrTemp,$pager);

		return $arrResult;
	}
	
	/**
	 * Customer email of Store Wise Email Report List
	 *
	 * @return array
	 */
	public function CustomerWiseEmailReportListShow($storeid){
		$arrResult	=	null;
		$_query	= "select t1.storeid, t1.email, t2.bu_name from ".$this->table."emailalert as t1 ".
		"left join ".$this->table."bu_detail as t2 on t1.storeid = t2.StoreId where t1.storeid ='$storeid' ";
		$this -> dbcon->execute_query($_query) ;
		$arrTemp = $this -> dbcon -> fetch_records(true) ;
		if (is_array($arrTemp)) {
			$arrResult['list']	=	$arrTemp;
		}
		unset($arrTemp);
		
		return $arrResult;
	}
}

/*********************
* xajax function
**********************/

/*******************************
* email function of xajax
*******************************/

/**
 * xajax Store Wise Email ReportList
 *
 * @param int $pageno
 * @param string $objHTML
 * @return objResponse
 */
function StoreWiseEmailReportList($pageno=1,$objHTML=''){
	$smarty			= &$GLOBALS['smarty'];
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminEmail 	= &$GLOBALS['objAdminEmail'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	
	$req['list'] =	$objAdminEmail -> StoreWiseEmailReportList($pageno);
	
	$req['nofull'] = true ;
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_email.tpl');
	$objResponse -> assign("$objHTML",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	
	return $objResponse;
}

/**
 * xajax Fresh Produce Report Subscribers List
 *
 * @param int $pageno
 * @param string $objHTML
 * @return objResponse
 */
function ReportSubscriberList($pageno=1,$objHTML=''){
	$smarty			= &$GLOBALS['smarty'];
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminEmail 	= &$GLOBALS['objAdminEmail'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	
	$req['list'] =	$objAdminEmail -> ReportSubscriberList($pageno);
	
	$req['nofull'] = true ;
	$smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_report_subscribe.tpl');
	$objResponse -> assign("$objHTML",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	
	return $objResponse;
}

/**
 *  show email of Store Wise Email ReportList by xajax
 * 
 * @param  int        $storeid
 * @return objResponse
 */
function StoreWiseEmailReportListShow($storeid){
	$smarty			= &$GLOBALS['smarty'];
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminEmail 	= &$GLOBALS['objAdminEmail'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	
	$req['list'] =	$objAdminEmail -> StoreWiseEmailReportListShow($storeid);
	$req['nofull'] = true ;
	$req['controlPage'] = 'showemail' ;
	
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_email.tpl');
	$objResponse -> assign('tabledatalist','innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	
	return $objResponse;
}

/**
 * show email of Customer Wise Email ReportList by xajax
 *
 * @param int $pageno
 * @param string $objHTML
 * @return objResponse
 */
function CustomerWiseEmailReportList($pageno=1,$objHTML=''){
	$smarty			= &$GLOBALS['smarty'];
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminEmail 	= &$GLOBALS['objAdminEmail'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	
	$req['list'] =	$objAdminEmail -> customerWiseEmailReportList($pageno);
	
	$req['nofull'] = true ;
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_email_cus.tpl');
	$objResponse -> assign("$objHTML",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	
	return $objResponse;
}

/**
 *  show email of Customer Wise Email ReportList by xajax
 * 
 * @param  int        $storeid
 * @return objResponse
 */
function CustomerWiseEmailReportListShow($storeid){
	$smarty			= &$GLOBALS['smarty'];
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminEmail 	= &$GLOBALS['objAdminEmail'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	
	$req['list'] =	$objAdminEmail -> CustomerWiseEmailReportListShow($storeid);
	$req['nofull'] = true ;
	$req['controlPage'] = 'showemail' ;
	
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_email_cus.tpl');
	$objResponse -> assign('tabledatalist','innerHTML',$content);
	//$objResponse -> assign("pageno",'value',$pageno);
	
	return $objResponse;
}
?>