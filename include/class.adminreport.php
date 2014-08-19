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

class adminReport extends common {
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
	 * get store list
	 *
	 * @param int $pageno
	 * @param string $strParam
	 * @param boolean $notOld
	 * @return array
	 */
	public function getReportList($startDate,$enddate,$selectType,$statename,$gender = "",$college="",$curpage=1,$membership="",$inrenew="",$field="",$order="asc"){
		$arrResult	=	null;
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		if($selectType!="-1"){
			$wheresql = " bu.attribute='{$selectType}' and ";
		}
		if($statename!=""){
			$wheresql .= " bu.bu_state = '{$statename}' and ";
		}
		if($membership!=""){
			$wheresql .= " bu.PayDate = bu.launch_date and ";
		}
	
		if($gender!=""){
			$wheresql .= " bu.gender = '$gender' and ";
		}
		
		if($college!=""){
			$wheresql .= " bu.bu_college = '$college' and ";
		}
		
		if($inrenew!=""){
			$renewsql = " ,'renew' ";
		}else{
			$renewsql = "";
		}
		
		$whorder    =   "";
		switch ($field){
			case 'gender':
				$whorder .= " order by bu.gender $order "; 
				break;
			case 'bu_college':
				$whorder .= " order by bu.bu_college $order "; 
				break;
			case 'bu_nickname':
				$whorder .= " order by bu.bu_nickname $order "; 
				break;
			case 'attribute':
				$whorder .= " order by bu.attribute $order "; 
				break;
			case 'bu_email':
				$whorder .= " order by bu.bu_email $order "; 
				break;
			case 'bu_name':
				$whorder .= " order by bu.bu_name $order "; 
				break;
			case 'launch_date':
				$whorder .= " order by bu.launch_date $order "; 
				break;
			case 'stateName':
				$whorder .= " order by st.stateName $order "; 
				break;
			case 'bizName':
				$whorder .= " order by un.bizName $order "; 
				break;
			default:
				break;
		}
		
		$tempary =  null; 
			
		$query ="select bu.*,un.*,st.* from ".$this->table."bu_detail bu left join {$this->table}universities_colleges un on un.bizID = bu.bu_college left join {$this->table}state st on bu.bu_state=st.id left join {$this->table}order_reviewref orf on orf.buyer_id=bu.StoreID where $wheresql bu.attribute<>4 and bu.subAttrib in('0','1') and orf.p_status='paid' and orf.type in('reigstration'$renewsql) and  orf.paid_date>=$startDate and orf.paid_date<=$enddate and bu.renewalDate>=".time()." group by bu.StoreID $whorder ";
		$this->dbcon->execute_query($query);
		$totals	=	$this->dbcon->fetch_records(true);
		$totalNum = 0;
		$tmptotals = array();
		if($totals){
			$j = 0;
			foreach ($totals as $ktol=>$vtol){
				if($membership!=""){
					if(!$this->countyear($vtol['launch_date'],$vtol['renewalDate'])){
						continue;
					}
				}
				$tmptotals[$j] = $vtol;
				$j++;
			}
			$totalNum = $j;
		}
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		$newarrTemp = array();
		if($totalNum){
			for($i=$start,$j=$start; $i<$totalNum;$i++){
				if(!isset($tmptotals[$i])||$j>=$pageno*$perPage){
					break;
				}
				$newarrTemp[$j] = $tmptotals[$i];
				$newarrTemp[$j]['id'] = $j+1;
				$j++;
			}
			
			if (is_array($newarrTemp)) {
				$arrResult['list']	=	& $newarrTemp;

				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getReportList(\'%d\',\''.$field.'\',\''.$order.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			}
		}
		$arrResult['query'] = $query;
		unset($arrTemp,$params);
		$arrResult['sort']['page']=$pageno;
		$arrResult['sort']['field']=$field;
		$arrResult['sort']['order']=$order;
		
		return $arrResult;
	}

	public function getExcelList($startDate,$enddate,$selectType,$statename,$gender = "",$college="",$membership="",$inrenew=""){
		$arrResult	=	null;
		$colleges = $this->getColleges();
		if(DATAFORMAT_DB=="%m/%d/%Y"){
				$DateStr = "m/d/Y H:i";
				$time_map = "(NY Time)";
			}else{
				$DateStr = "d/m/Y H:i";
				$time_map = "(Sydney Time)";					
		}
		$aryhead = array(0=>array("id"=>"Number",
		"launch_date"=>"Signup Date $time_map",
		"attribute"=>"Seller Type",
		"bu_email"=>"Email",
		"bu_nickname"=>"Nickname",
		"gender"=>"Gender",
		"bu_college"=>"College",
		"bu_name"=>"Website Name",
		"bu_address"=>"Address",
		"bu_state"=>"State",
		"bu_suburb"=>"City",
		"bu_postcode"=>"Post Code",
		"bu_area"=>"Phone Area Code",
		"bu_phone"=>"Phone",
		"mobile"=>"Mobile",
		"renewalDate"=>"Expiry Date $time_map"));
		$state = $this->getStateList();
		$selltype = $this->getSellerType();
		if($selectType!="-1"){
			$wheresql = " bu.attribute='{$selectType}' and  ";
		}
		if($statename!=""){
			$wheresql .= " bu.bu_state = '{$statename}' and ";
		}
		if($membership!=""){
			$wheresql .= " bu.PayDate = bu.launch_date and ";
		}
		if($gender!=""){
			$wheresql .= " bu.gender = '$gender' and ";
		}
		
		if($college!=""){
			$wheresql .= " bu.bu_college = '$college' and ";
		}
		
		if($inrenew!=""){
			$renewsql = " ,'renew' ";
		}else{
			$renewsql = "";
		}
		
		$query ="select count(*) from ".$this->table."bu_detail bu left join {$this->table}order_reviewref orf on orf.buyer_id=bu.StoreID where $wheresql bu.attribute<>4 and bu.subAttrib in('0','1') and orf.p_status='paid' and orf.type in('reigstration'$renewsql) and  orf.paid_date>=$startDate and orf.paid_date<=$enddate and bu.renewalDate>=".time()." group by bu.StoreID ";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];

		if ($totalNum) {
			$query ="select bu.* from ".$this->table."bu_detail bu left join {$this->table}order_reviewref orf on orf.buyer_id=bu.StoreID where $wheresql bu.attribute<>4 and bu.subAttrib in('0','1') and orf.p_status='paid' and orf.type in('reigstration'$renewsql) and  orf.paid_date>=$startDate and orf.paid_date<=$enddate and bu.renewalDate>=".time()." group by bu.StoreID order by bu.launch_date ASC";
			
			$this->dbcon->execute_query($query);
			$arrTemp	=	$this->dbcon->fetch_records(true);
			$newarrTemp = array();
			$i = 1;
			foreach ($arrTemp as $key=>$values){
				if($membership!=""){
					if(!$this->countyear($values['launch_date'],$values['renewalDate'])){
						continue;
					}
				}
				foreach ($values as $vks=>$pass){
					if(in_array($vks,array_keys($aryhead[0]))){
						switch ($vks){
							case 'attribute':
								$newarrTemp[$i][$vks] = $selltype[$pass];
								break;
							case 'bu_state':
								if($state[$pass]['stateName']==""){
									$newarrTemp[$i][$vks] = "";
								}else{
									$newarrTemp[$i][$vks] = "{$state[$pass]['description']}({$state[$pass]['stateName']})";
								}
								break;
							case 'launch_date':
								$newarrTemp[$i][$vks] = intval($pass)>0?date($DateStr,$pass):"";
								break;
							case 'renewalDate':
								$newarrTemp[$i][$vks] = intval($pass)>0?date($DateStr,$pass):"";
								break;
							case 'gender':
								$newarrTemp[$i][$vks] = $pass==0?"Male":"Female";
								break;
							case 'bu_college':
								$newarrTemp[$i][$vks] = isset($colleges[$pass])?$colleges[$pass]:"";
								break;
							default:
								$newarrTemp[$i][$vks] = $pass;
								break;
						}
					}
				}
				$newarrTemp[$i]['id'] = $i;
				$i++;
			}
			$result = array_merge($aryhead,$newarrTemp);
		}else{
			$result = $aryhead;
		}
		unset($arrTemp,$newarrTemp,$aryhead);
		return $result;
	}
	/**
	 * @title	: 
	 * Tue Mar 17 01:52:40 GMT 2009 01:52:40
	 * @author	: Roy.luo <support@infinitytesting.com.au>
	 * @version	: V1.0
	 * 
	*/
	public function listreport(){
		$query = "select * from {$this->table}promotion order by addtime DESC";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		return $result;
	}

	public function getHour(){
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

	public function getSellerType(){
		$sellertype = array();
		$sellertype[-1]= "All";
		$sellertype[0] = "Seller";
		$sellertype[1] = "Real Estate Seller ";
		$sellertype[2] = "Car Seller";
		$sellertype[3] = "Job Poster";
		return $sellertype;
	}

	public function getStateList(){
		$arrResult = null;

		$sql = "select * from ".$this->table."state order by description,stateName";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			foreach ($arrTemp as $pass){
				$arrResult[$pass['id']] = $pass;
			}
		}

		return  $arrResult;
	}

	public function countyear($startdate,$enddate){
		if($startdate){
			$tmpdate = mktime(0,0,0,date('m',$startdate),date('d',$startdate),date('Y',$startdate)+1);
			if($tmpdate>=$enddate-86400&&$tmpdate<=$enddate+86400){
				return true;
			}
		}
		return false;		
	}
	
	public function getColleges($state="all"){
		if($state=='all'){
			$query = "select bizID,bizName from {$this->table}universities_colleges order by bizID";
		}else{
			$query = "select bizID,bizName from {$this->table}universities_colleges where bizState='$state' order by bizID";
		}
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		$colleges = array();
		if(is_array($result)){
			foreach ($result as $pass){
				$colleges[$pass['bizID']] = $pass['bizName'];
			}
		}
		return $colleges;
	}
}
function getReportList($pageno,$field="",$order='asc'){
	$objResponse 	= new xajaxResponse();
	$objAdminReport 	= &$GLOBALS['objAdminReport'];
	$req['reportlist']	= $objAdminReport -> getReportList($_SESSION['reportSearch']['startDate'],$_SESSION['reportSearch']['enddate'],$_SESSION['reportSearch']['selletype'],$_SESSION['reportSearch']['state'],$_SESSION['reportSearch']['gender'],$_SESSION['reportSearch']['college'],$pageno,$_SESSION['reportSearch']['mebship'],$_SESSION['reportSearch']['inrenew'],$field,$order);
	$objAdminReport -> smarty -> assign('PBDateFormat',DATAFORMAT_DB." %H:%M");
	$objAdminReport -> smarty -> assign('sellertype',$objAdminReport -> getSellerType());
	$objAdminReport -> smarty -> assign('req',	$req);
	$content = $objAdminReport -> smarty -> fetch('admin_report_list.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

?>