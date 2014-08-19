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

class adminFacelike extends common {
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
	 * get facelike list
	 *
	 * @param int $pageno
	 * @param string $strParam
	 * @param boolean $notOld
	 * @return array
	 */
	public function getFacelikeList($pageno=1,$strParam='',$notOld = true,$field="",$orders="asc"){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	18;

		$sqlWhere	=	"where detail.customerType='seller' ";

		if ($strParam) {
			$arrParam = unserialize($strParam);
			if(!get_magic_quotes_runtime()){
				$arrParam = striaddslashes_deep($arrParam);
			}
			if(isset($arrParam['state']) && $arrParam['state']!=''){
				$sqlWhere	.=	" And detail.bu_state ='$arrParam[state]'";
			}
			if(isset($arrParam['suburb']) && $arrParam['suburb']!=''){
				$sqlWhere	.=	" AND  detail.bu_suburb ='$arrParam[suburb]'";
			}
			if(isset($arrParam['attribute']) && $arrParam['attribute']!=''){
				$sqlWhere	.=	" And detail.attribute ='$arrParam[attribute]'";
			}
			if(isset($arrParam['refferID']) && $arrParam['refferID']!=''){
				$sqlWhere	.=	" And detail.ref_name like '%".trim($arrParam['refferID'])."%'";
			}
			if(isset($arrParam['bu_email']) && $arrParam['bu_email']!=''){
				$sqlWhere	.=	" And detail.bu_email like '%".trim($arrParam['bu_email'])."%'";
			}
			if(isset($arrParam['suspend']) && $arrParam['suspend']!=''){
				$sqlWhere	.=	" And lg.suspend='{$arrParam['suspend']}' ";
			}
			if(isset($arrParam['bu_name']) && $arrParam['bu_name']!=''){
				$sqlWhere	.=	" And detail.bu_name like '%{$arrParam['bu_name']}%' ";
			}
			
			
			
			$fromDate = 0;
			$toDate = 0;
			if(DATAFORMAT_DB=="%m/%d/%Y"){
				if(isset($arrParam['fromDate'])&&$arrParam['fromDate']!=""){
					list($month,$day,$year) = split('/',$arrParam['fromDate']);
					$fromDate = mktime(0,0,0,$month,$day,$year);
				}
				if(isset($arrParam['toDate'])&&$arrParam['toDate']!=""){
					list($month,$day,$year) = split('/',$arrParam['toDate']);
					$toDate = mktime(23,59,59,$month,$day,$year);
				}
			}else{
				if(isset($arrParam['fromDate'])&&$arrParam['fromDate']!=""){
					list($day,$month,$year) = split('/',$arrParam['fromDate']);
					$fromDate = mktime(0,0,0,$month,$day,$year);
				}
				if(isset($arrParam['toDate'])&&$arrParam['toDate']!=""){
					list($day,$month,$year) = split('/',$arrParam['toDate']);
					$toDate = mktime(23,59,59,$month,$day,$year);
				}
			}
			if($fromDate!=0){
				$sqlWhere	.=	" And facelike.timestamp >= '$fromDate'";
			}
			if($toDate!=0){
				$sqlWhere	.=	" And facelike.timestamp <= '$toDate'";
			}
			if($arrParam['num']!=''){
				$sqlWhere	.=	" And facelike.num = '$arrParam[num]'";
			}
		}
		
		switch ($field){
			case 'bu_name':
				$order = " order by detail.bu_name $orders ";
				break;
			case 'bu_nickname':
				$order = " order by detail.bu_nickname $orders ";
				break;
			case 'bu_email':
				$order = " order by detail.bu_email $orders ";
				break;
			case 'flike':
				$order = " order by flike $orders ";
				break;
			case 'funlike':
				$order = " order by funlike $orders ";
				break;
			case 'total':
				$order = " order by total $orders ";
				break;
			default:
				$order = "";
				break;
		}
		
		$query ="select facelike.StoreID from ".$this->table."facelike_records facelike,".$this->table."bu_detail detail $sqlWhere and facelike.StoreID=detail.StoreID GROUP BY facelike.StoreID order by detail.StoreID ";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->count_records();
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalNum) {
			
					$query = "SELECT SUM( IF( facelike.num =1, facelike.num , 0 ) ) AS flike, SUM( IF( facelike.num=-1, facelike.num, 0 ) ) AS funlike, SUM( facelike.num ) AS total, detail.ref_name,detail.StoreID,detail.bu_name,detail.bu_email,detail.renewalDate,detail.CustomerType,detail.bu_nickname \n".
					" FROM ".$this->table."facelike_records facelike,".$this->table."bu_detail detail \n".
					" $sqlWhere and facelike.StoreID=detail.StoreID \n".
					" GROUP BY facelike.StoreID  $order limit $start,$perPage \n";
					
			$arrResult['query']=$query;
			$this->dbcon->execute_query($query);
			$arrTemp	=	$this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$arrResult['list']	=	& $arrTemp;

				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getFacelikeList(\'%d\',xajax.getFormValues(\'mainForm\'),'.$notOld.',\''.$field.'\',\''.$orders.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = & Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			}
		}
		$arrResult['query'] = $query;
		$arrResult['sort']['page']=$pageno;
		$arrResult['sort']['field']=$field;
		$arrResult['sort']['notold']=$notOld;
		$arrResult['sort']['order']=$orders;
		unset($arrTemp,$params,$sqlWhere);

		return $arrResult;
	}

	/**
	 * get facelike records
	 *
	 * @param int $pageno
	 * @param string $strParam
	 * @param boolean $notOld
	 * @return array
	 */
	public function getFacelikeRecords($pageno=1,$strParam='',$notOld = true,$field="",$orders="asc"){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	18;

		$sqlWhere	=	"where 1 ";

		if ($strParam) {
			$arrParam = unserialize($strParam);
			if(!get_magic_quotes_runtime()){
				$arrParam = striaddslashes_deep($arrParam);
			}
			if(isset($arrParam['StoreID']) && $arrParam['StoreID']!=''){
				$sqlWhere	.=	" And facelike.StoreID ='$arrParam[StoreID]'";
			}
			
			$fromDate = 0;
			$toDate = 0;
			if(DATAFORMAT_DB=="%m/%d/%Y"){
				if(isset($arrParam['fromDate'])&&$arrParam['fromDate']!=""){
					list($month,$day,$year) = split('/',$arrParam['fromDate']);
					$fromDate = mktime(0,0,0,$month,$day,$year);
				}
				if(isset($arrParam['toDate'])&&$arrParam['toDate']!=""){
					list($month,$day,$year) = split('/',$arrParam['toDate']);
					$toDate = mktime(23,59,59,$month,$day,$year);
				}
			}else{
				if(isset($arrParam['fromDate'])&&$arrParam['fromDate']!=""){
					list($day,$month,$year) = split('/',$arrParam['fromDate']);
					$fromDate = mktime(0,0,0,$month,$day,$year);
				}
				if(isset($arrParam['toDate'])&&$arrParam['toDate']!=""){
					list($day,$month,$year) = split('/',$arrParam['toDate']);
					$toDate = mktime(23,59,59,$month,$day,$year);
				}
			}
			if($fromDate!=0){
				$sqlWhere	.=	" And facelike.timestamp >= '$fromDate'";
			}
			if($toDate!=0){
				$sqlWhere	.=	" And facelike.timestamp <= '$toDate'";
			}
			if($arrParam['num']!=''){
				$sqlWhere	.=	" And facelike.num = '$arrParam[num]'";
			}
		} elseif ($_REQUEST['StoreID']) {
			$sqlWhere	.=	" And facelike.StoreID ='$_REQUEST[StoreID]'";
		}
		
		switch ($field){
			case 'bu_name':
				$order = " order by detail.bu_name $orders ";
				break;
			case 'bu_nickname':
				$order = " order by detail.bu_nickname $orders ";
				break;
			case 'url':
				$order = " order by facelike.url $orders ";
				break;
			case 'timestamp':
				$order = " order by facelike.timestamp $orders ";
				break;
			case 'num':
				$order = " order by facelike.num $orders ";
				break;
			default:
				$order = "";
				break;
		}
		
		$query ="select facelike.StoreID from ".$this->table."facelike_records facelike,".$this->table."bu_detail detail $sqlWhere and facelike.StoreID=detail.StoreID order by detail.StoreID ";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->count_records();
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalNum) {
			
					$query = "SELECT facelike.*, detail.ref_name,detail.StoreID,detail.bu_name,detail.bu_email,detail.renewalDate,detail.CustomerType,detail.bu_nickname \n".
					" FROM ".$this->table."facelike_records facelike \n".
					" LEFT JOIN ".$this->table."bu_detail detail ON facelike.uid=detail.StoreID \n".
					" $sqlWhere \n".
					" $order limit $start,$perPage \n";
					
			$arrResult['query']=$query;
			$this->dbcon->execute_query($query);
			$arrTemp	=	$this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$arrResult['list']	=	& $arrTemp;

				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getFacelikeRecords(\'%d\',xajax.getFormValues(\'mainForm\'),'.$notOld.',\''.$field.'\',\''.$orders.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = & Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			}
		}
		$arrResult['query'] = $query;
		$arrResult['sort']['page']=$pageno;
		$arrResult['sort']['field']=$field;
		$arrResult['sort']['notold']=$notOld;
		$arrResult['sort']['order']=$orders;
		$arrResult['PBDateFormat']=DATAFORMAT_DB;
		unset($arrTemp,$params,$sqlWhere);

		return $arrResult;
	}
	
	public function getFacelikeList2($pageno=1,$strParam='',$notOld = true,$field="",$orders="asc"){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	18;

		$sqlWhere	=	"where detail.subAttrib<>3 and detail.renewalDate<".time()." and detail.customerType='seller' ";

		if ($strParam) {
			$arrParam = unserialize($strParam);
			if(isset($arrParam['attribute']) && $arrParam['attribute']!=''){
				if ($arrParam['attribute'] == 5) {
					$sqlWhere	=	"where detail.renewalDate<".time()." and detail.customerType='seller' ";
				}
				$sqlWhere	.=	" And detail.attribute ='$arrParam[attribute]'";
			}
			if(isset($arrParam['state']) && $arrParam['state']!=''){
				$sqlWhere	.=	" And detail.bu_state ='$arrParam[state]'";
			}
			if(isset($arrParam['suburb']) && $arrParam['suburb']!=''){
				$sqlWhere	.=	" AND  detail.bu_suburb ='$arrParam[suburb]'";
			}
			if(isset($arrParam['refferID']) && $arrParam['refferID']!=''){
				$sqlWhere	.=	" And detail.ref_name like '%{$arrParam['refferID']}%'";
			}
		}
		
		switch ($field){
			case 'bu_name':
				$order = " order by detail.bu_name $orders ";
				break;
			case 'bu_nickname':
				$order = " order by detail.bu_nickname $orders ";
				break;
			case 'launch_date':
				$order = " order by detail.launch_date $orders ";
				break;
			case 'ref_name':
				$order = " order by detail.ref_name $orders ";
				break;
			default:
				$order = "";
				break;
		}
		
		$query ="select count(*) from ".$this->table."login lg,".$this->table."bu_detail detail $sqlWhere and lg.StoreID=detail.StoreID order by detail.StoreID ";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalNum) {
			$query = "select detail.ref_name,detail.StoreID,detail.bu_name,detail.renewalDate,detail.CustomerType,detail.bu_nickname, \n".
					" FROM_UNIXTIME(detail.launch_date,'".DATAFORMAT_DB."') as DateAdd,detail.launch_date \n".
					" from ".$this->table."login lg,".$this->table."bu_detail detail \n".
					" $sqlWhere and lg.StoreID=detail.StoreID $order limit $start,$perPage";
			$arrResult['query']=$query;
			$this->dbcon->execute_query($query);
			$arrTemp	=	$this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$arrResult['list']	=	& $arrTemp;

				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getFacelikeList2(\'%d\',xajax.getFormValues(\'mainForm\'),'.$notOld.',\''.$field.'\',\''.$orders.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = & Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			}
		}
		$arrResult['query'] = $query;
		$arrResult['sort']['page']=$pageno;
		$arrResult['sort']['field']=$field;
		$arrResult['sort']['notold']=$notOld;
		$arrResult['sort']['order']=$orders;
		unset($arrTemp,$params,$sqlWhere);

		return $arrResult;
	}

	public function getUserList($pageno=1,$strParam='',$notOld = true,$field="",$orders="asc"){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	18;

		$sqlWhere	=	"where 1=1 ";
		if ($strParam) {
			if(is_array($strParam)){
				$arrParam = $strParam;
			}else{
				$arrParam = unserialize($strParam);
			}
			if(!get_magic_quotes_runtime()){
				$arrParam = striaddslashes_deep($arrParam);
			}
			if(isset($arrParam['state']) && $arrParam['state']!=''){
				$sqlWhere	.=	" And detail.bu_state ='$arrParam[state]'";
			}
			if(isset($arrParam['suburb']) && $arrParam['suburb']!=''){
				$sqlWhere	.=	" AND  detail.bu_suburb ='$arrParam[suburb]'";
			}
			if(isset($arrParam['attribute']) && $arrParam['attribute']!=''){
				$sqlWhere	.=	" And detail.attribute ='$arrParam[attribute]'";
			}
		}
		switch ($field){
			case 'bu_email':
				$order = " order by detail.bu_email $orders ";
				break;
			case 'bu_nickname':
				$order = " order by detail.bu_nickname $orders ";
				break;
			case 'from_type':
				$order = " order by of.from_type $orders ";
				break;
			case 'form_date':
				$order = " order by of.form_date $orders ";
				break;
			default:
				$order = "";
				break;
		}
		$query ="select count(*) from {$this->table}order_from of ";
		$query .="LEFT JOIN {$this->table}bu_detail detail ON detail.StoreID=of.StoreID $sqlWhere";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		if ($totalNum) {
			$query ="select * from {$this->table}order_from of ";
			$query .="LEFT JOIN {$this->table}bu_detail detail ON detail.StoreID=of.StoreID $sqlWhere $order limit $start,$perPage";
			$arrResult['query']=$query;
			$this->dbcon->execute_query($query);
			$arrTemp	=	$this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$arrResult['list']	=	& $arrTemp;

				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getUserList(\'%d\',xajax.getFormValues(\'mainForm\'),'.$notOld.',\''.$field.'\',\''.$orders.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = & Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			}
		}
		$arrResult['query'] = $query;
		$arrResult['sort']['page']=$pageno;
		$arrResult['sort']['field']=$field;
		$arrResult['sort']['notold']=$notOld;
		$arrResult['sort']['order']=$orders;
		$arrResult['select']['state'] = $arrParam['state'];
		$arrResult['select']['suburb'] = $arrParam['suburb'];
		$arrResult['select']['attribute'] = $arrParam['attribute'];
		unset($arrTemp,$params,$sqlWhere);

		return $arrResult;
		
		
	}
	
	/**
	 * get state list of all
	 *
	 * @return array
	 */
	public function getStateList(){
		$arrResult = null;

		$sql = "select * from ".$this->table."state order by description,stateName";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp;
		}

		return  $arrResult;
	}
	public function getsuburbsbysid($sid){
		$arrResult = null;
		$sql = "select suburb_id,suburb from ".$this->table."suburb where state_id='$sid' group by suburb order by suburb ";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp;
		}
		return  $arrResult;
	}

	/**
	 * get Suburb list by state id
	 *
	 * @param int $stateID
	 * @return array
	 */
	public function getSuburbList($stateID=''){
		$arrResult = null;

		$sqlQuery	='';
		if($stateID!=''){
			$sqlQuery	=" AND state_id ='$stateID' ";
			$QUERY	=	"SELECT DISTINCT suburb FROM ".$this->table."suburb WHERE suburb <>'' $sqlQuery ORDER BY suburb";

			$this -> dbcon -> execute_query($QUERY);
			$arrResult = $this -> dbcon -> fetch_records(true);
		}
		//$arrResult['query']	=	$QUERY;

		return  $arrResult;
	}
	
	/**
	 *  Get State ID By Name
	 * 
	 */
	function getStateIdByName($name){
		if (empty($name)) {
			return 1;
		}
		
		if (is_int($name)) {
			return $name;
		}
		
		$name = strtolower($name);
		$sql = "select id from ".$this->table."state where lower(stateName)='$name' OR lower(description)='$name'";
		$res = $this->dbcon->getOne($sql);
		return $res ? $res['id'] : 1;
	}
	
	/**
	 *  Get Postcode By Suburb Name
	 * 
	 */
	function getPostcodeBySuburb($state_id='', $suburb_id='', $suburb_name=''){
		if (empty($state_id) || (empty($suburb_id) && empty($suburb_name))) {
			return '';
		}
		
		$suburb_name = clean_url_name(strtolower($suburb_name));
		$sql = "select zip from ".$this->table."suburb where state_id='$state_id' AND (lower(suburb)='$suburb_name' OR suburb_id='$suburb_id')";
		$res = $this->dbcon->getOne($sql);
		return $res ? $res['zip'] : '';
	}
	
}

/*********************
* xajax function
**********************/

/**
 * xajax get facelike list
 *
 * @param int $pageno
 * @param objForm $objForm
 * @param boolean $notOld
 * @return objResponse
 */
function getFacelikeList($pageno,$objForm,$notOld = true,$field="",$orders='ASC'){
	$objResponse 	= new xajaxResponse();
	$objAdminFacelike 	= &$GLOBALS['objAdminFacelike'];
	$req['list']	= $objAdminFacelike -> getFacelikeList($pageno,$objForm['searchparam'],$notOld,$field,$orders);
	$req['nofull'] = true ;
	$objAdminFacelike -> smarty -> assign('req',	$req);
	$content = $objAdminFacelike -> smarty -> fetch('admin_facelike.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

/**
 * xajax get facelike records
 *
 * @param int $pageno
 * @param objForm $objForm
 * @param boolean $notOld
 * @return objResponse
 */
function getFacelikeRecords($pageno,$objForm,$notOld = true,$field="",$orders='ASC'){
	$objResponse 	= new xajaxResponse();
	$objAdminFacelike 	= &$GLOBALS['objAdminFacelike'];
	$req['list']	= $objAdminFacelike -> getFacelikeRecords($pageno,$objForm['searchparam'],$notOld,$field,$orders);
	$req['nofull'] = true ;
	$objAdminFacelike -> smarty -> assign('req',	$req);
	$content = $objAdminFacelike -> smarty -> fetch('admin_facelike_records.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

/**
 * xajax get facelike records of search
 *
 * @param objForm $objForm
 * @return objResponse
 */
function getFacelikeRecordsSearch($objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminFacelike 	= &$GLOBALS['objAdminFacelike'];
	$strParam	= serialize($objForm);
	$req['list']	= $objAdminFacelike -> getFacelikeRecords(1,$strParam);
	$req['nofull'] = true ;
	$objAdminFacelike -> smarty -> assign('req',	$req);
	$objAdminFacelike -> smarty -> assign('PBDateFormat',	DATAFORMAT_DB);
	$content = $objAdminFacelike -> smarty -> fetch('admin_facelike_records.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("searchparam",'value',$strParam);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}

function getFacelikeList2($pageno,$objForm,$notOld = true,$field="",$orders='ASC'){
	$objResponse 	= new xajaxResponse();
	$objAdminFacelike 	= &$GLOBALS['objAdminFacelike'];
	$req['list']	= $objAdminFacelike -> getFacelikeList2($pageno,$objForm['searchparam'],$notOld,$field,$orders);
	$req['nofull'] = true ;
	$objAdminFacelike -> smarty -> assign('req',	$req);
	$content = $objAdminFacelike -> smarty -> fetch('admin_facelike_exp.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

/**
 * xajax get store list of search
 *
 * @param objForm $objForm
 * @return objResponse
 */
function getFacelikeListSearch($objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminFacelike 	= &$GLOBALS['objAdminFacelike'];
	$strParam	= serialize($objForm);
	$req['list']	= $objAdminFacelike -> getFacelikeList(1,$strParam);
	$req['nofull'] = true ;
	$objAdminFacelike -> smarty -> assign('req',	$req);
	$content = $objAdminFacelike -> smarty -> fetch('admin_facelike.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("searchparam",'value',$strParam);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}

function getFacelikeListSearch2($objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminFacelike 	= &$GLOBALS['objAdminFacelike'];
	$strParam	= serialize($objForm);
	$req['list']	= $objAdminFacelike -> getFacelikeList2(1,$strParam);
	$req['nofull'] = true ;
	$objAdminFacelike -> smarty -> assign('req',	$req);
	$objAdminFacelike->smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$content = $objAdminFacelike -> smarty -> fetch('admin_facelike_exp.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("searchparam",'value',$strParam);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}

/**
 * xajax get suburb list
 *
 * @param int $stateID
 * @param objHTML $objHTML
 * @return objresponse
 */
function getSuburbList($stateID,$objHTML){
	$objResponse 	= new xajaxResponse();
	$objAdminFacelike 	= &$GLOBALS['objAdminFacelike'];

	$req['suburb'] 	=	$objAdminFacelike -> getSuburbList($stateID);
	$req['display'] 	=	'suburb';
	$objAdminFacelike -> smarty -> assign('req',	$req);
	$content = $objAdminFacelike -> smarty -> fetch('admin_store.tpl');
	$objResponse -> assign("$objHTML",'innerHTML',$content);

	return $objResponse;
}
?>