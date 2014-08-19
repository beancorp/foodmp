<?php
/**
 * Thu Feb 21 08:29:05 GMT+08:00 2012 08:29:05
 * @author  : Kevin.Liu <kevin.liu@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * Store function and class
 * ------------------------------------------------------------
 * \include\class.adminRace.php
 */

class adminRace extends common {
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
	public function getRaceList($startDate=0,$enddate=0,$nickname="",$usertype="-1",$paymethod="",$state="",$college="",$status="",$field="",$order="asc",$ispayment=false,$curpage=1){
		$arrResult	=	null;
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$wheresql = "";
		if($startDate!=0){
			$wheresql .= " and record.timestamp>='{$startDate}' ";
		}
		if($enddate!=0){
			$wheresql .= " and record.timestamp <= '{$enddate}' ";
		}
		if($nickname!=""){
			$wheresql .= " and detail.bu_nickname like '%{$nickname}%' ";
		}
		if($usertype!="-1"&&$usertype!=""){
			$wheresql .= " and detail.attribute = '{$usertype}' ";
		}
		if($state!=""){
			$wheresql .= " and detail.bu_state in({$state})";
		}
		if($college!=""){
			$wheresql .= " and detail.bu_college = '{$college}' ";
		}
		switch ($field){
			case 'bu_nickname':
				$order = " order by detail.bu_nickname $order ";
				break;
			case 'bu_email':
				$order = " order by detail.bu_email $order ";
				break;
			case 'bu_phone':
				$order = " order by detail.bu_phone $order ";
				break;
			case 'launch_date':
				$order = " order by detail.launch_date $order ";
				break;
			case 'bu_state':
				$order = " order by state.stateName $order ";
				break;
			case 'bu_suburb':
				$order = " order by detail.bu_suburb $order ";
				break;
			case 'points':
				$order = " order by total_points $order ";
				break;
			case 'attribute':
				$order = " order by detail.attribute $order ";
				break;
			default:
				$order = " order by total_points desc ";
				break;
		}
		$query = " SELECT SUM(point) AS total_points, detail.*, state.stateName, state.description \n".
		   " FROM {$this->table}point_records record,{$this->table}bu_detail detail \n".
		   " LEFT JOIN {$this->table}login lg ON(detail.StoreID = lg.StoreID) \n".
		   " LEFT JOIN {$this->table}state state ON(detail.bu_state=state.id) \n".
		   " WHERE record.StoreID=detail.StoreID AND lg.suspend=0 {$wheresql} GROUP BY record.StoreID";
		$query1 = "SELECT COUNT(*) FROM (".$query.") AS tmp WHERE 1";
		$this->dbcon->execute_query($query1);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		$query .= " {$order}, bu_nickname DESC limit $start,$perPage";
		$this->dbcon->execute_query($query);
		$arrTemp	=	$this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$tmparry = array();
				foreach ($arrTemp as $key=>$pass){
					$tmparry[$key]['addtime'] 	= $pass['addtime'];
					$tmparry[$key]['details'] 	= $pass['details'];
					$tmparry[$key]['name'] 		= $pass['name'];
					$tmparry[$key]['total_points'] 	= $pass['total_points'];
					$tmparry[$key]['StoreID'] 	= $pass['StoreID'];
					$tmparry[$key]['launch_date'] = $pass['launch_date'];
					$tmparry[$key]['bu_phone'] 	= $pass['bu_area']."-".$pass['bu_phone'];
					$tmparry[$key]['bu_email'] 	= $pass['bu_email'];
					$tmparry[$key]['bu_nickname'] = $pass['bu_nickname'];
					$tmparry[$key]['bu_state'] = $pass['stateName'];
					$tmparry[$key]['bu_suburb'] = $pass['bu_suburb'];
					$tmparry[$key]['address'] 	= $pass['address'];
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
						case '5':
							$tmparry[$key]['attribute'] = "Food & Wine";
							break;
						default:
							$tmparry[$key]['attribute'] = "Buyer";
							break;
					}
		
					$tmparry[$key]['paymethod'] = $pass['paymethod']==1?"Cheque":($pass['paymethod']==2?"Paypal":"N/A");
				}
				
				$arrResult['list']	=	& $tmparry;
				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getRaceList(\'%d\');return false;',
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
	
	function checkPartnerSitefrom($domain, $sid=0){
		$ckwebsite = "";
		$strCondition = " WHERE domain='".$domain."'";
		if ($sid!="") {
			$strCondition .= " AND $sid<>'".$sid."'";
		}
		if (!$this-> dbcon-> checkRecordExist($this->table."point_site", $strCondition)) {
			$ckdomain = 'ok';
		} else {
			$ckdomain = "existed";
		}
		
		return $ckdomain;
	}
	
	public function getPartnerSiteInfo($sid){
		$arrResult = null;
		$sql = "select *, id AS sid from ".$this->table."point_site where id='$sid' ";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
		}
		$arrResult['site_name'] = str_replace("\"","&quot;",$arrResult['site_name']);
		
		return  $arrResult;
	}

	public function getQuestionInfo($qid){
		$arrResult = null;
		$sql = "select *, id AS qid from ".$this->table."point_question where id='$qid' ";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
		}
		$arrResult['question'] = str_replace("\"","&quot;",$arrResult['question']);
		
		return  $arrResult;
	}

	public function getAnswerInfo($aid){
		$arrResult = null;
		$sql = "select answer.*, answer.id AS aid, question.site_id from ".$this->table."point_answer answer,".$this->table."point_question question where answer.question_id=question.id and answer.id='$aid' ";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
		}
		$arrResult['answer'] = str_replace("\"","&quot;",$arrResult['answer']);
		
		return  $arrResult;
	}
	
	public function getPartnerSiteList($pageno=1,$strParam='',$notOld = true,$field="",$orders="asc"){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	18;

		$sqlWhere	=	"where 1 ";

		if ($strParam) {
			$arrParam = unserialize($strParam);
			if(!get_magic_quotes_runtime()){
				$arrParam = striaddslashes_deep($arrParam);
			}
			if(isset($arrParam['site_name']) && $arrParam['site_name']!=''){
				$sqlWhere	.=	" And site_name LIKE '%$arrParam[site_name]%'";
			}
			if(isset($arrParam['domain']) && $arrParam['domain']!=''){
				$sqlWhere	.=	" And domain LIKE '%$arrParam[domain]%'";
			}
			if(isset($arrParam['deleted']) && $arrParam['deleted']!=''){
				$sqlWhere	.=	" And deleted ='$arrParam[deleted]'";
			}
		}
		
		switch ($field){
			case 'id':
				$order = " order by id $orders ";
				break;
			case 'site_name':
				$order = " order by site_name $orders ";
				break;
			case 'domain':
				$order = " order by domain $orders ";
				break;
			case 'point':
				$order = " order by point $orders ";
				break;
			case 'max_time':
				$order = " order by max_time $orders ";
				break;
			case 'add_time':
				$order = " order by add_time $orders ";
				break;
			default:
				$order = " order by id $orders ";
				break;
		}
		
		$query ="select count(*) from ".$this->table."point_site $sqlWhere order by id ASC ";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalNum) {
			$query = "select *, FROM_UNIXTIME(add_time,'".DATAFORMAT_DB."') as DateAdd \n".
					" from ".$this->table."point_site \n".
					" $sqlWhere $order limit $start,$perPage";
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
				'onclick'	 => 'javascript:xajax_getPartnerSiteList(\'%d\',xajax.getFormValues(\'mainForm\'),'.$notOld.',\''.$field.'\',\''.$orders.'\');return false;',
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
	
	public function getQuestionList($pageno=1,$strParam='',$notOld = true,$field="",$orders="asc"){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	18;

		$sqlWhere	=	"where site.deleted != '1' ";

		if ($strParam) {
			$arrParam = unserialize($strParam);
			if(!get_magic_quotes_runtime()){
				$arrParam = striaddslashes_deep($arrParam);
			}
			if(isset($arrParam['site_id']) && $arrParam['site_id']!=''){
				$sqlWhere	.=	" And question.site_id='$arrParam[site_id]'";
			}
			if(isset($arrParam['question']) && $arrParam['question']!=''){
				$sqlWhere	.=	" And question.question LIKE '%$arrParam[question]%'";
			}
			if(isset($arrParam['deleted']) && $arrParam['deleted']!=''){
				$sqlWhere	.=	" And question.deleted ='$arrParam[deleted]'";
			}
		} elseif ($_REQUEST['sid']) {
			$sqlWhere	.=	" And question.site_id='$_REQUEST[sid]'";
		}
		
		switch ($field){
			case 'id':
				$order = " order by question.id $orders ";
				break;
			case 'question':
				$order = " order by question.question $orders ";
				break;
			case 'site_name':
				$order = " order by site.site_name $orders ";
				break;
			case 'domain':
				$order = " order by site.domain $orders ";
				break;
			default:
				$order = " order by question.id $orders ";
				break;
		}
		$query ="select count(*) from ".$this->table."point_question question,".$this->table."point_site site $sqlWhere and question.site_id=site.id order by question.id ASC ";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalNum) {
			$query = "select question.*, site.site_name, site.domain \n".
					" from ".$this->table."point_question question,".$this->table."point_site site \n".
					" $sqlWhere and question.site_id=site.id $order limit $start,$perPage";
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
				'onclick'	 => 'javascript:xajax_getQuestionList(\'%d\',xajax.getFormValues(\'mainForm\'),'.$notOld.',\''.$field.'\',\''.$orders.'\');return false;',
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
	
	public function getAnswerList($pageno=1,$strParam='',$notOld = true,$field="",$orders="asc"){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	18;

		$sqlWhere	=	"where (site.deleted != '1' && question.deleted != '1')";

		if ($strParam) {
			$arrParam = unserialize($strParam);
			if(!get_magic_quotes_runtime()){
				$arrParam = striaddslashes_deep($arrParam);
			}
			if(isset($arrParam['site_id']) && $arrParam['site_id']!=''){
				$sqlWhere	.=	" And question.site_id='$arrParam[site_id]'";
			}
			if(isset($arrParam['question_id']) && $arrParam['question_id']!=''){
				$sqlWhere	.=	" And answer.question_id='$arrParam[question_id]'";
			}
			if(isset($arrParam['answer']) && $arrParam['answer']!=''){
				$sqlWhere	.=	" And answer.answer LIKE '%$arrParam[answer]%'";
			}
			if(isset($arrParam['status']) && $arrParam['status']!=''){
				$sqlWhere	.=	" And answer.status ='$arrParam[status]'";
			}
			if(isset($arrParam['deleted']) && $arrParam['deleted']!=''){
				$sqlWhere	.=	" And answer.deleted ='$arrParam[deleted]'";
			}
		} elseif ($_REQUEST['qid']) {
			$sqlWhere	.=	" And answer.question_id='$_REQUEST[qid]'";
		}
		
		switch ($field){
			case 'id':
				$order = " order by answer.id $orders ";
				break;
			case 'answer':
				$order = " order by answer.answer $orders ";
				break;
			case 'site_name':
				$order = " order by site.site_name $orders ";
				break;
			case 'question':
				$order = " order by question.question $orders ";
				break;
			case 'domain':
				$order = " order by site.domain $orders ";
				break;
			case 'order':
				$order = " order by answer.order $orders ";
				break;
			case 'status':
				$order = " order by answer.status $orders ";
				break;
			default:
				$order = " order by answer.id $orders ";
				break;
		}
		$query ="select count(*) FROM ".$this->table."point_answer answer \n".
				"LEFT JOIN ".$this->table."point_question question ON (answer.question_id=question.id) \n".
				"LEFT JOIN ".$this->table."point_site site ON (question.site_id=site.id) \n".
				"$sqlWhere order by answer.id ASC ";
		
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalNum) {
			$query = "select answer.*, question.question, question.site_id, site.site_name, site.domain \n".
					"FROM ".$this->table."point_answer answer \n".
					"LEFT JOIN ".$this->table."point_question question ON (answer.question_id=question.id) \n".
					"LEFT JOIN ".$this->table."point_site site ON (question.site_id=site.id) \n".
					" $sqlWhere and question.site_id=site.id $order limit $start,$perPage";
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
				'onclick'	 => 'javascript:xajax_getAnswerList(\'%d\',xajax.getFormValues(\'mainForm\'),'.$notOld.',\''.$field.'\',\''.$orders.'\');return false;',
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
	
	public function getQuestionsBySid($sid){
		$arrResult = null;
		$sql = "SELECT id, question FROM ".$this->table."point_question WHERE site_id='$sid' AND deleted='0' order by question ASC";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp;
		}
		return  $arrResult;
	}
	
	public function getSelectPartnerSite($all=false){
		$query = "select * from ".$this->table."point_site WHERE 1";
		if (!$all) {
			$query .= " AND deleted='0'";
		}
		$this->dbcon->execute_query($query);
		$arrTemp	=	$this->dbcon->fetch_records(true);

		return $arrTemp;
	}
	
	public function getSitenameBySid($sid){
		$arrResult = null;
		$sql = "select suburb_id,suburb from ".$this->table."suburb where state_id='$sid' group by suburb order by suburb ";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp;
		}
		return  $arrResult;
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

}

function getRaceList($pageno){
	$objResponse 	= new xajaxResponse();
	$objAdminRace 	= new adminRace();
	
	$req = $_SESSION['refSearch'];
	
	$startDate = $_SESSION['refSearch']['startDate'] ? date(str_replace('%', '', DATAFORMAT_DB), $_SESSION['refSearch']['startDate']) : '';
	$enddate  = $_SESSION['refSearch']['enddate'] ? date(str_replace('%', '', DATAFORMAT_DB), $_SESSION['refSearch']['enddate']) : '';
			
	$req['sorturl'] = "&opt=send&nickname=".urlencode($_SESSION['refSearch']['nickname'])."&usertype={$_SESSION['refSearch']['usertype']}&start_date={$startDate}&end_date={$enddate}&s_hour={$_SESSION['refSearch']['s_hour']}&e_hour={$_SESSION['refSearch']['e_hour']}&state={$_SESSION['refSearch']['state']}&college={$_REQUEST['college']}";
	$req['reflist']	= $objAdminRace ->getRaceList($_SESSION['refSearch']['startDate'],$_SESSION['refSearch']['enddate'],$_SESSION['refSearch']['nickname'],$_SESSION['refSearch']['usertype'],$_SESSION['refSearch']['paymethod'],$_SESSION['refSearch']['state'],$_SESSION['refSearch']['college'],$_SESSION['refSearch']['status'],$_SESSION['refSearch']['field'],$_SESSION['refSearch']['order'],$_SESSION['refSearch']['ispayment'],$pageno);
	$objAdminRace -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$objAdminRace -> smarty -> assign('req',	$req);
	$content = $objAdminRace -> smarty -> fetch('admin_race_record.tpl');
		
	$objResponse -> assign("ref_list",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

function getPartnerSiteList($pageno,$objForm,$notOld = true,$field="",$orders='ASC'){
	$objResponse 	= new xajaxResponse();
	$objAdminRace 	= &$GLOBALS['objAdminRace'];
	$req['list']	= $objAdminRace -> getPartnerSiteList($pageno,$objForm['searchparam'],$notOld,$field,$orders);
	$req['nofull'] = true ;
	$objAdminRace -> smarty -> assign('req',	$req);
	$content = $objAdminRace -> smarty -> fetch('admin_partner_site.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

function getQuestionList($pageno,$objForm,$notOld = true,$field="",$orders='ASC'){
	$objResponse 	= new xajaxResponse();
	$objAdminRace 	= &$GLOBALS['objAdminRace'];
	$req['list']	= $objAdminRace -> getQuestionList($pageno,$objForm['searchparam'],$notOld,$field,$orders);
	$req['nofull'] = true ;
	$objAdminRace -> smarty -> assign('req',	$req);
	$content = $objAdminRace -> smarty -> fetch('admin_question.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

function getAnswerList($pageno,$objForm,$notOld = true,$field="",$orders='ASC'){
	$objResponse 	= new xajaxResponse();
	$objAdminRace 	= &$GLOBALS['objAdminRace'];
	$req['list']	= $objAdminRace -> getAnswerList($pageno,$objForm['searchparam'],$notOld,$field,$orders);
	$req['nofull'] = true ;
	$objAdminRace -> smarty -> assign('req',	$req);
	$content = $objAdminRace -> smarty -> fetch('admin_answer.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

function getPartnerSiteListSearch($objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminRace 	= &$GLOBALS['objAdminRace'];
	$strParam	= serialize($objForm);
	$req['list']	= $objAdminRace -> getPartnerSiteList(1,$strParam);
	$req['nofull'] = true ;
	$objAdminRace -> smarty -> assign('req',	$req);
	$content = $objAdminRace -> smarty -> fetch('admin_partner_site.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("searchparam",'value',$strParam);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}

function getQuestionListSearch($objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminRace 	= &$GLOBALS['objAdminRace'];
	$strParam	= serialize($objForm);
	$req['list']	= $objAdminRace -> getQuestionList(1,$strParam);
	$req['nofull'] = true ;
	$objAdminRace -> smarty -> assign('req',	$req);
	$content = $objAdminRace -> smarty -> fetch('admin_question.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("searchparam",'value',$strParam);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}

function getAnswerListSearch($objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminRace 	= &$GLOBALS['objAdminRace'];
	$strParam	= serialize($objForm);
	$req['list']	= $objAdminRace -> getAnswerList(1,$strParam);
	$req['nofull'] = true ;
	$objAdminRace -> smarty -> assign('req',	$req);
	$content = $objAdminRace -> smarty -> fetch('admin_answer.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("searchparam",'value',$strParam);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}
?>