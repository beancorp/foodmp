<?php
/**
 * Thu Dec 11 00:36:30 GMT 2008 00:36:30
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * job class and function
 * ------------------------------------------------------------
 * include\class.jobClass.php
 */


class jobClass extends common {
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
	 * The function dispose interface of product of job.
	 *
	 * @return array
	 */
	public function storeProductAddOrEditOfJob(){
		$arrResult 	= 	null;
		$StoreID	=	$_SESSION['ShopID'];
		$dustary = array('id' =>"-1",'fid' =>"",'type' => "",'name' =>"Select a Industry",'picture' =>"",
		'sort' =>"",'state' =>"","datec" =>'');
		$subscary =  array('id' =>"-1",'fid' =>"",'type' => "",'name' =>"Select a Sector",
		'picture' =>"",'sort' =>"",'state' =>"",'datec' =>"");
		$stateary =  array('id'=>'-1','name'=>'Select a State','stateName'=>'');
		$suburary =  array('id'=>'-1','name'=>'Select a City/ Suburb');
		if ($this-> _notVar){
			$arrResult['select']	=	$this -> getFormInputVar();

			$arrResult['select']['op']			=	'edit';
			$arrResult['select']['step']		=	$_REQUEST['step'];
			$arrResult['select']['sortby']		=	$_REQUEST['sortby'];

			$arrResult['sector']	=	getSectorListFromDB(0,1);
			$arrResult['subSector']=	getSectorListFromDB($arrResult['select']['sector'],1);

			$arrResult['state']			=	getStateArrayFromDB();
			$arrResult['suburb']		=	getSubburbArrayFromDB($arrResult['select']['state']);
		}else{
			//modify
			if ( $_REQUEST['pid'] || $_SESSION['subAttrib'] ) {
				if ($_SESSION['subAttrib'] == 3) {
					$_query	= "select * from ". $this->table ."product_job where StoreID='".$StoreID ."' and deleted=0 limit 0,1";
				}else{
					$_query	= "select * from ". $this->table ."product_job where pid=".$_REQUEST['pid'] ." and deleted=0 and StoreID='".$StoreID ."'";
				}
				$this -> dbcon -> execute_query($_query);
				$arrTemp	=	$this -> dbcon -> fetch_records(true);
				if (is_array($arrTemp)) {
					$arrResult['select']	=	$arrTemp[0];

					$arrResult['select']['op']			=	'edit';
					$arrResult['select']['step']		=	$_REQUEST['step'];
					$arrResult['select']['sortby']		=	$_REQUEST['sortby'];

					$arrResult['select']['datePosted']	=	$arrResult['select']['datePosted'] >0 ? $this -> changeDate($arrResult['select']['datePosted'],'ymd',str_replace('%','',str_replace('/','',DATAFORMAT_DB)),'-','/') : '';
					$arrResult['select']['closingDate']	=	$arrResult['select']['closingDate'] >0 ? $this -> changeDate($arrResult['select']['closingDate'],'ymd',str_replace('%','',str_replace('/','',DATAFORMAT_DB)),'-','/') : '';

					$arrResult['select']['content2']	=	explode('|=&&&&=|', $arrResult['select']['content2']);

					$arrResult['select']['contentStyle1']	=	empty($arrResult['select']['contentStyle1'])? '' : unserialize($arrResult['select']['contentStyle1']);
					$arrResult['select']['contentStyle3']	=	empty($arrResult['select']['contentStyle3'])? '' : unserialize($arrResult['select']['contentStyle3']);

					$arrResult['sector']	=	getSectorListFromDB(0,1);
					$arrResult['subSector']	=	getSectorListFromDB($arrResult['select']['sector'],1);

					$arrResult['state']			=	getStateArrayFromDB();
					$arrResult['suburb']		=	getSubburbArrayFromDB($arrResult['select']['state']);

				}
			}

			if( !$arrResult['select']['pid']){
				//new
				$arrResult['select']	=	array(
				'op'			=>	'edit',
				'step'			=>	$_REQUEST['step'],
				'sortby'		=>	$_REQUEST['sortby'],

				'pid'			=>			"",
				'StoreID'		=>			"",
				'item_name'		=>			"",
				'category'		=>			1,
				'sector'		=>			0,
				'subSector'		=>			0,
				'refNo'			=>			"",
				'salaryMin'		=>			"",
				'salaryMax'		=>			"",
				'type'			=>			"",
				'location'		=>			"",
				'state'			=>			0,
				'suburb'		=>			1,
				'postcode'		=>			"",
				'datePosted'	=>			"",
				'closingDate'	=>			"",
				'advertiser'	=>			"",
				'content1'		=>			"",
				'content2'		=>			"",
				'content3'		=>			"",
				'featured'		=>			0,
				'enabled'		=>			1,
				'datec'			=>			"",
				'datem'			=>			""
				);

				$arrResult['sector']	=	getSectorListFromDB(0,1);
				//$arrResult['subSector']=	getSectorListFromDB($arrResult['sector'][0]['id'],1);
				$arrResult['subSector'] = array();

				$arrResult['state']			=	getStateArrayFromDB();
				//$arrResult['suburb']		=	getSubburbArrayFromDB($arrResult['state'][0]['id']);
				$arrResult['suburb'] = array();
			}
		}
		if(!is_array($arrResult['sector'])){
			$arrResult['sector'] = array();
		}
		array_unshift($arrResult['sector'],$dustary);

		if(!is_array($arrResult['subSector'])){
			$arrResult['subSector'] = array();
		}
		array_unshift($arrResult['subSector'],$subscary);

		if(!is_array($arrResult['state'])){
			$arrResult['state'] = array();
		}
		array_unshift($arrResult['state'],$stateary);

		if(!is_array($arrResult['suburb'])){
			$arrResult['suburb'] = array();
		}
		array_unshift($arrResult['suburb'],$suburary);


		if ($_SESSION['subAttrib'] != 3) {
			$arrResult['list']	=	$this -> __getProductList($StoreID, $_REQUEST['sortby']);
		}

		return $arrResult;
	}


	public function storeProductAddOrEditOperateOfJob($setOP=''){
		$boolResult	=	false;
		$lang		=	&$this->lang;
		$StoreID	=	$_SESSION['ShopID'];
		$pid		=	$_REQUEST['pid'];

		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);

		if ($setOP == 'next' || $setOP == 'save') {
			$boolResult	=	true;

		}elseif ($setOP == 'edit' || $setOP=='paynow' || $setOP=='renewnow' || $setOP=='paylater') {

			$dateNow	=	time();
			$strCondition ="where StoreID='$StoreID' and pid='$pid' and deleted=0";

			$strContent2	=	implode('|=&&&&=|',array_unique($content2));

			$arrSetting	=	array(
			'pid'			=>		$pid,
			'item_name'		=>		$item_name,
			'url_item_name'	=>		clean_url_name($item_name),
			'category'		=>		$category,
			'sector'		=>		$sector,
			'subSector'		=>		$subSector,
			'refNo'			=>		$refNo,
			'salaryMin'		=>		$salaryMin,
			'salaryMax'		=>		$salaryMax,
			'negotiable'	=>		$negotiable?$negotiable:0,
			'type'			=>		$type,
			'location'		=>		$address,
			'state'			=>		$state,
			'suburb'		=>		$suburb,
			'postcode'		=>		$postcode,
			'datePosted'  	=> 		$datePosted?$this -> changeDate($datePosted,str_replace('%','',str_replace('/','',DATAFORMAT_DB)),'','/','-'):"0000-00-00",
			'closingDate' 	=> 		$closingDate?$this -> changeDate($closingDate,str_replace('%','',str_replace('/','',DATAFORMAT_DB)),'','/','-'):"0000-00-00",
			'advertiser'	=>		$advertiser,
			'content1'		=>		$content1,
			'contentStyle1'	=>		is_array($contentStyle1) ? serialize($contentStyle1) : '',
			'content2'		=>		$strContent2,
			'content3'		=>		$content3,
			'contentStyle3'	=>		is_array($contentStyle3) ? serialize($contentStyle3) : '',
			'featured'		=>		$featured ? 1 : 0,
			'enabled'		=>		$enabled ? 1 : 0,
			'datem'			=>		$dateNow,
			'ispub'			=>		isset($ISpub)?$ISpub:0,
			'youtubevideo'	=>		$youtubevideo,
			);
			
			$is_fee_year = false;			
			if (empty($_REQUEST['pid'])) {
				$socObj = new socClass();
				$store_info = $socObj->getStoreInfo($StoreID);
				if ($store_info['product_feetype'] == 'year' && $store_info['product_renewal_date'] >= time()) {
					$arrSetting['pay_status'] = 1;
					$arrSetting['pay_date'] = time();
					$arrSetting['renewal_date'] = $store_info['product_renewal_date'];
					$is_fee_year = true;
				}				
			}
			
			/***if same item name auto renew the item url****/
			/***2010 04 15 roy.luo***/
			$i=0;
			$item_url= clean_url_name($item_name);
			do {
				if($i>0){$item_url = clean_url_name($item_name).(date("His",time()+$i));}
				$ckname_bl = $this->checkProductName($item_url,$StoreID,$pid);
				$i++;
			}while ($ckname_bl);
			$arrSetting['url_item_name']=$item_url;
			/****end by roy.luo***/
			
			if($this -> dbcon-> checkRecordExist($this->table."product_job",$strCondition)){
				if ($this->checkProductName($item_name,$StoreID,$pid)){
					$msg = "Item exists! Please try with a new item name.";
					$boolResult = false;
				}else{
					if($this->dbcon->update_record($this->table."product_job", $arrSetting, $strCondition)){
						$boolResult	=	true;
						$msg = $this->replaceLangVar($lang['pub_clew']['successful'],array('Item',$lang['operation']['update']));
					}else {
						$msg = $this->replaceLangVar($lang['pub_clew']['faild'],array('Item',$lang['operation']['update']));
					}
				}
			}elseif ( $StoreID > 0 ){
				$arrSetting['StoreID']	=	$StoreID;
				$arrSetting['datec']	=	$dateNow;
				if ($this->checkProductName($item_name,$StoreID)){
					$msg = "Item exists! Please try with a new item name.";
					$boolResult = false;
				}else{
					unset($arrSetting['pid']);
					
					if($this->dbcon->insert_record($this->table."product_job",$arrSetting)){
						$boolResult	=	true;
						$newPID	=	$this->dbcon->lastInsertId();
						$msg = $this->replaceLangVar($lang['pub_clew']['successful'],array('Item',$lang['operation']['add']));
                                                /**
                                                 * added by YangBall, 2011-07-05
                                                 * referrer new rule
                                                 */
                                                 require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
                                                 $referrer = new Referrer();
                                                 $referrer->addReferrerRecord('product', $_SESSION['StoreID']);

                                                //END-YangBall
                                        
                                        /**
                                         * added by Kevin.Liu, 2012-02-16
                                         * point new rule
                                         */
                                        if ($is_fee_year) {
                                        	include_once(SOC_INCLUDE_PATH . '/class.point.php');
	                                         $objPoint = new Point();
	                                         $objPoint->addPointRecords($_SESSION['StoreID'], 'year', $newPID);
                                        }                                         
                                        //END
					}else {
						$msg = $this->replaceLangVar($lang['pub_clew']['faild'],array('Item',$lang['operation']['add']));
					}
				}
				//echo $this->dbcon->_query;
				//exit;
			}
			if ($boolResult) {
				$pid = !empty($newPID) ? $newPID : $_REQUEST['pid'] ;
			}

			//Go to pay
			if ($setOP=='paynow' || $setOP=='renewnow') {
				$pay_cp = $setOP == 'paynow' ? 'product_pay' : 'product_renew';
				header('Location:/job/?act=product&cp='.$pay_cp.'&step=4&pid='.$pid);
				exit();
			}
		}elseif ($setOP == 'del'){
			$arrSetting	=	array(
			'deleted'	=>	1
			);
			$strCondition	=	"where pid='$pid' and StoreID='$StoreID'";
			if($this->dbcon->update_record($this->table."product_job", $arrSetting, $strCondition)){
				$boolResult	=	true;
				$msg = $this->replaceLangVar($lang['pub_clew']['successful'],array('Item',$lang['operation']['delete']));
			}else {
				$msg = $this->replaceLangVar($lang['pub_clew']['faild'],array('Item',$lang['operation']['delete']));
			}
			
            /**
             * added by Kevin.Liu, 2012-02-16
             * reduce point new rule
             */
        	 include_once(SOC_INCLUDE_PATH . '/class.point.php');
             $objPoint = new Point();
             $objPoint->addPointRecords($StoreID, 'product', $pid, true);                                   
            //END
		}

		$this -> addOperateMessage($msg);
		return $boolResult;
	}

	/**
	 * Get list of product of auto
	 *
	 * @param int $StoreID
	 * @param string $sortby
	 * @param int $limit
	 * @return array
	 */
	public function __getProductList($StoreID, $sortby='', $limit=0){
		$arrResult	=	null;
		$query = "SELECT attribute,subAttrib FROM {$this->table}bu_detail where StoreID='$StoreID'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		$attribute =$result[0]['attribute'];
		$subAttrib =$result[0]['subAttrib'];
		
		$_title	=	"t1.*, DATE_FORMAT(FROM_UNIXTIME(t1.datec),'".DATAFORMAT_DB."') as datecfm, DATE_FORMAT(FROM_UNIXTIME(t1.renewal_date),'".DATAFORMAT_DB."') as dateexpired ";
		$_where	=	"where t1.StoreID='$StoreID' and t1.deleted=0";
		if($attribute==3&&$subAttrib==1){
			$_where .= " and category=1 ";
		}else{
			$_where .= " and category=2 ";
		}
		
		if (!empty($sortby)) {
			$sortby_str = str_replace('_', ' ', $sortby);		
			if ($sortby == 'dateexpired_asc' || $sortby == 'dateexpired_desc') {
				$tmp_ary = explode('_', $sortby);
				$sortby_str = 'renewal_date '.$tmp_ary[1];
			}
			$_orderby	=	'order by t1.'. $sortby_str . ', datem desc';
		}else {
			$_orderby	=	'order by t1.item_name asc, t1.datem desc';
		}

		$_query	=	"select $_title from ".$this->table."product_job as t1 "." $_where $_orderby";

		$this -> dbcon -> execute_query($_query);
		$arrTemp	=	$this -> dbcon -> fetch_records(true);

		if (is_array($arrTemp)) {
			$arrResult	=	$arrTemp;
		}

		return $arrResult;
	}

	/**
	 * @title	: searchJobList
	 * Tue Dec 16 09:33:01 GMT 2008
	 * @input	: array searchForm, int page
	 * @output	: array Job search list
	 * @description	: execute the search query and return the search result
	 * @author	: Jessee <support@infinitytesting.com.au>
	 * @version	: V1.0
	 * 
	 */
	function searchJobList($searchForm, $page){
		# get states list
		$states = getStateArray($searchForm['state_name']);

		$whereSQL = array();
		#  suburb search condition and suburb array
		$suburbArray = explode('.', $searchForm['suburb']);
		$cities = getSuburbArray($searchForm['state_name'] == -1 ? 'NSW' : $searchForm['state_name'],$searchForm['suburb']);
		if ($searchForm['suburb_id'] && $searchForm['distance']==""){
			//$whereSQL[] = "product.suburb ='".$searchForm['suburb_id']."'";
			$whereSQL[] = "product.suburb in(".getSuburbIdArystr($searchForm['state_name'],$searchForm['suburb_id']).") ";
		}

		# distance
		$distance = array( 3, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300);

		# generate the radius search sql string
		$postcode		=	$searchForm['postcode'];
		if ($searchForm['state_name'] != '-1' && $searchForm['distance']!=""){
			$whereSQL[] = "product.postcode ".getRadiusSqlString($searchForm['postcode'], $searchForm['distance'],'AUS', 0);
		}

		# get state id and add state condition
		$stateInfo = getStateInfoByName($searchForm['state_name']);
		if($searchForm['state_name'] != '-1'){
			$whereSQL[] = "product.state = '".$stateInfo['id']."'";
		}
		
		# postcode search condition
		if (!empty($searchForm['bu_postcode'])){
			$whereSQL[] = "product.postcode='".$searchForm['bu_postcode']."'";
		}

		# category search condition
		if ($searchForm['category']>0){
			$whereSQL[] = "category in (".$searchForm['category'].")";
		}

		# type condition
		if (!empty($searchForm['type'])){
			$whereSQL[] = "type='".$searchForm['type']."'";
		}

		# Industry condition
		if ($searchForm['industry']>0){
			$whereSQL[] = "sector='".$searchForm['industry']."'";
		}

		# sector condition
		if ($searchForm['sector']>0){
			$whereSQL[] = "subSector='".$searchForm['sector']."'";
		}

		# salary condition
		if ($searchForm['min_salary']>0 && $searchForm['max_salary']>0){
			if ($searchForm['min_salary'] == $searchForm['max_salary']){
				$price_string = "(salaryMin = '".$searchForm['min_salary']."' and salaryMax = '".$searchForm['max_salary']."')";
			}else{
				$price_string = "IF(salaryMin>=0,salaryMin BETWEEN '{$searchForm['min_salary']}' AND '{$searchForm['max_salary']}' AND IF(salaryMax>=0,salaryMax BETWEEN '{$searchForm['min_salary']}' AND '{$searchForm['max_salary']}',1=1),IF(salaryMax>=0,salaryMax BETWEEN '{$searchForm['min_salary']}' AND '".$searchForm['max_salary']."',1<>1)) ";
			}
		}elseif ($searchForm['min_salary']>0 && $searchForm['max_salary'] == ''){
			$price_string = "IF(salaryMin>=0,salaryMin >= '{$searchForm['min_salary']}' AND IF(salaryMax>=0,salaryMax>='{$searchForm['min_salary']}','1=1'), IF(salaryMax>=0,salaryMax>='{$searchForm['min_salary']}',1<>1)) ";
		}elseif ($searchForm['max_salary']>0){
			$price_string = "IF(salaryMin>=0,salaryMin <= '{$searchForm['max_salary']}' AND IF(salaryMax>=0,salaryMax<='{$searchForm['max_salary']}','1=1'), IF(salaryMax>=0,salaryMax<='{$searchForm['max_salary']}',1<>1)) ";
		}

		# check price_string and add negotiable condition in search
		if ( $price_string != ''){
			if ($searchForm['negotiable']==1){
				$whereSQL[] = "(".$price_string." or negotiable = 1)";
			}else{
				$whereSQL[] = $price_string;
			}
		}elseif ($searchForm['negotiable']==1){
			$whereSQL[] = " negotiable = 1";
		}

		# keywords search
		if (strlen($searchForm['keyword'])>0){
			$whereSQL[] = "(item_name like '%".$searchForm['keyword']."%' ".
			" or content1 like '%".$searchForm['keyword']."%' ".
			" or content2 like '%".$searchForm['keyword']."%' ".
			" or content3 like '%".$searchForm['keyword']."%' ".
			" or detail.bu_name like '%".$searchForm['keyword']."%')";
		}

		# current date
		$current_date = date("Y-m-d");
		# conditions
		$queryCondition = "FROM ". $this->table ."product_job AS product \n".
		"LEFT JOIN ". $this->table ."bu_detail AS detail ON detail.StoreID = product.StoreID \n".
		"LEFT JOIN ". $this->table ."login AS lg ON lg.StoreID = detail.StoreID \n".
		"LEFT JOIN ". $this->table ."suburb AS suburb ON suburb.suburb_id = product.suburb \n".
		"LEFT JOIN ". $this->table ."image AS img ON (img.StoreID=product.StoreID and img.pid = product.pid \n".
		" and img.attrib=0 and img.sort=0) WHERE product.deleted = '0' and product.enabled=1 \n".
		"AND NOT(detail.bu_name IS NULL) \n".
		"AND detail.CustomerType = 'seller' AND detail.attribute='3' \n".
		"AND ((datePosted <= '$current_date' or datePosted='0000-00-00') \n".
		"AND IF(detail.subAttrib=3, 1, (product.pay_status=2 OR (product.pay_status=1 and product.renewal_date >= '".time()."'))) \n".
		"AND (closingDate >= '$current_date' or closingDate='0000-00-00'))\n ";
		//$queryCondition .= "AND IF(detail.subAttrib<>3,detail.renewalDate>='".time()."',1=1)  \n";
		$queryCondition .= "AND lg.suspend=0  \n";

		if($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2) && $_SESSION['ispayfee']) {
			$queryCondition .= " AND IF(product.category=2, product.ispub in(0,1) , 1=1) ";
		}else{
			$queryCondition .= " AND IF(product.category=2, product.ispub in(1) , 1=1) ";
		}

		if ($whereSQL){
			$queryCondition.= "AND ".join(' and ',$whereSQL);
		}

		# get total number
		$query = "SELECT COUNT(*) \n".$queryCondition;

		$this->dbcon->execute_query($query);
		$total = $this->dbcon->fetch_records(true);
		$total = $total[0]['COUNT(*)'];

		# divide pages
		$clsPage = new pagerClass();
		$pageLink = $clsPage->getLink($page, $total, PAGESIZE, 'page');
		unset($clsPage);

		# search products
		$start = ($page-1)*PAGESIZE;
		$query = "SELECT product.pid, product.StoreID, product.item_name,product.url_item_name,product.salaryMin, \n".
		"product.salaryMax, product.content1, '' AS flag, '' AS website_name, \n".
		"detail.bu_name,detail.bu_urlstring, suburb.suburb as bu_suburb, img.smallPicture \n".
		"$queryCondition \n".
		"ORDER BY product.datec DESC limit $start,".PAGESIZE;
		//echo "<!--$query-->";
		$this->dbcon->execute_query($query);
		$searchResult = $this->dbcon->fetch_records(true);

		$tmpName = '';
		$objImage	=	new uploadImages();
		for ($i = 0; $i < PAGESIZE && $i < $total-$start; $i ++) {
			if ($i > 0) {
				if ($searchResult[$i]['name'] == $tmpName) {
					$searchResult[$i]['flag'] = 0;
				} else {
					$searchResult[$i]['flag'] = 1;
					$tmpName = $searchResult[$i]['name'];
				}
			} else {
				$searchResult[$i]['flag'] = 1;
				$tmpName = $searchResult[$i]['name'];
			}
			if ($searchResult[$i]['salaryMin']>0 and $searchResult[$i]['salaryMax']>0){
				$searchResult[$i]['salary'] = $searchResult[$i]['salaryMin'].' - '.$searchResult[$i]['salaryMax'];
			}elseif ($searchResult[$i]['salaryMin']>0){
				$searchResult[$i]['salary'] = $searchResult[$i]['salaryMin'];
			}else{
				$searchResult[$i]['salary'] = $searchResult[$i]['salaryMax'];
			}
			$searchResult[$i]['simage']	=	$objImage->getDefaultImage($searchResult[$i]['smallPicture'],true,'',0,4);
			$searchResult[$i]['website_name'] = clean_url_name($searchResult[$i]['bu_urlstring']);
			$searchResult[$i]['description'] = strip_tags($searchResult[$i]['content']);
		}

		//var_dump($searchResult);
		return array
		(
		'states'   => $states,
		'cities'   => $cities,
		'distance' => $distance,
		'counter'  => $total,
		'products' => $searchResult,
		'linkStr'  => $pageLink['linksAllFront'],
		'page'	   => 'state'
		);
	}
	
	function getJobRandomProduct($num=2) { 
		# current date
		$current_date = date("Y-m-d");               
		# conditions
		$queryCondition = "FROM ". $this->table ."product_job AS product \n".
		"LEFT JOIN ". $this->table ."bu_detail AS detail ON detail.StoreID = product.StoreID \n".
		"LEFT JOIN ". $this->table ."login AS lg ON lg.StoreID = detail.StoreID \n".
		"LEFT JOIN ". $this->table ."state AS state ON state.id = product.state \n".
		"LEFT JOIN ". $this->table ."suburb AS suburb ON suburb.suburb_id = product.suburb \n".
		"LEFT JOIN ". $this->table ."image AS img ON (img.StoreID=product.StoreID and img.pid = product.pid \n".
		" and img.attrib=0 and img.sort=0) WHERE product.deleted = '0' and product.enabled=1 \n".
		"AND NOT(detail.bu_name IS NULL) \n".
		"AND detail.CustomerType = 'seller' AND detail.attribute='3' \n".
		"AND ((datePosted <= '$current_date' or datePosted='0000-00-00') \n".
		"AND (closingDate >= '$current_date' or closingDate='0000-00-00'))\n ";
		$queryCondition .= "AND IF(detail.subAttrib<>3,detail.renewalDate>='".time()."',1=1) \n";
		if($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2)) {
			$queryCondition .= " AND IF(product.category=2, product.ispub in(0,1) , 1=1) ";
		}else{
			$queryCondition .= " AND IF(product.category=2, product.ispub in(1) , 1=1) ";
		}
		$queryCondition .= "AND product.category in (1)\n".		
		 					"AND lg.suspend=0 \n".		
							"ORDER BY RAND() \n".
							"LIMIT $num \n";
		
		$query = "SELECT product.pid, product.StoreID, product.item_name,product.url_item_name,product.salaryMin, \n".
		"product.salaryMax, product.content1, '' AS flag, '' AS website_name, \n".
		"detail.bu_name,detail.bu_urlstring,detail.bu_address,detail.bu_phone,detail.address_hide,detail.phone_hide, state.description as stateName, suburb.suburb as suburbName, img.smallPicture \n".
		"$queryCondition \n";
		
		$this-> dbcon -> execute_query($query);
		$searchResult = $this->dbcon->fetch_records(true);
		
		$tmpName = '';
		$objImage	=	new uploadImages();
		for ($i = 0; $i < $num; $i++) {
			if ($i > 0) {
				if ($searchResult[$i]['name'] == $tmpName) {
					$searchResult[$i]['flag'] = 0;
				} else {
					$searchResult[$i]['flag'] = 1;
					$tmpName = $searchResult[$i]['name'];
				}
			} else {
				$searchResult[$i]['flag'] = 1;
				$tmpName = $searchResult[$i]['name'];
			}
			$searchResult[$i]['simage']	=	$objImage->getDefaultImage($searchResult[$i]['smallPicture'],true,0,0,4);
			$searchResult[$i]['bimage']	=	$objImage->getDefaultImage($searchResult[$i]['picture'],false,0,0,9);
			$searchResult[$i]['website_name'] = clean_url_name($searchResult[$i]['bu_urlstring']);
			//echo "name: ".$searchResult[$i]['bu_name']."; url:".$searchResult[$i]['website_name']."\n<br>";
			//$searchResult[$i]['description'] = strip_tags($searchResult[$i]['content']);
		}
		
		return $searchResult;
	}
	
	/**
	 * @title	: getJobRssData
	 * Tue Dec 23 03:05:53 GMT 2008
	 * @input	: int StoreID, int List Number
	 * @output	: array Rss Data
	 * @description	: Generate the Rss Data of Job Market seller
	 * @author	: Jessee <support@infinitytesting.com.au>
	 * @version	: V1.0
	 * 
	 */
	function getJobRssData($id,$list_num = 50){
		$arrResult	=	array();
		global $normal_url;
		
		$StoreID	=	$id;

		$arrResult['title'] =  'The SOC Exchange Items';
		$arrResult['pubDate'] =  time() + $this->time_zone_offset;
		$arrResult['lastBuildDate'] =  time() + $this->time_zone_offset;
		$arrResult['description'] =  'The SOC Exchange Items';
		$arrResult['link'] = "{$normal_url}soc.php?cp=category";

		$arrResult['image']['title'] =  'The SOC Exchange Items';
		$arrResult['image']['url'] =  "{$normal_url}images/product_logo.gif";
		$arrResult['image']['link'] = "{$normal_url}";


		$query = "select product.pid,product.item_name,product.StoreID,product.content1,product.datem,";
		$query.= "product.datec from ".$this->table . "product_job as product,";
		$query.= $this->table."bu_detail as detail where product.StoreID=detail.StoreID";
		$query  .=  " and product.StoreID= $StoreID ";
		if($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2)) {
			$query .= " AND IF(product.category=2, product.ispub in(0,1) , 1=1)";
		}else{
			$query .= " AND IF(product.category=2, product.ispub in(1) , 1=1) ";
		}
		$query.= " and product.deleted='' and product.enabled=1 order by datem desc limit $list_num";

		$this->dbcon->execute_query($query);

		$arrTemp = $this->dbcon->fetch_records(true);
		if(is_array($arrTemp)){
			foreach ($arrTemp as $item){
				$rssItem = array();
				$rssItem['description'] = (($this->clearHTMLChar($item['content'],0)));
				$rssItem['title'] = (($this->clearHTMLChar($item['item_name'],0)));
				//$category = $this->getCategoryString($item['category']);
				$rssItem['link'] = "{$normal_url}soc.php?cp=dispro&amp;StoreID=".$item['StoreID']."&amp;proid=".$item['pid'];
				$rssItem['guid'] = $rssItem['link'];
				$rssItem['pubDate'] =  date('D d M Y H:i:s T', $item['datem']);
				$arrResult['item'][] = $rssItem;
			}
		}

		return $arrResult;
	}

	function checkProductName($name,$StoreID,$pid=0){
		if(strtolower(clean_url_name($name))=='wishlist'||strtolower(clean_url_name($name))=='gallery'){
			return true;
		}
		$name = clean_url_name($name);
		$sql = "select count(*) as num from ".$this->table."product_job where url_item_name='$name' and StoreID='$StoreID' and deleted=0";
		if ($pid!=0){
			$sql.= " and pid!=$pid";
		}
		$this->dbcon->execute_query($sql);
		$num = $this->dbcon->fetch_records();
		//echo "num:".$num[0]['num'].";$sql\n<br>";
		return ($num[0]['num']>0)?true:false;
	}
	
	function mulitopeartionjob($act,$arrayid=array()){
		if(count($arrayid)>0){
			$expstr = implode(',',$arrayid);
			$sql = "";
			switch ($act){
				case 'delete':
					/*$sql = "select smallPicture,picture from {$this->table}image where pid in($expstr);";
					$this->dbcon->execute_query($sql);
					$result = $this->dbcon->fetch_records(true);
					$sql = "delete from {$this->table}image where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){
						if(is_array($result)){
							foreach ($result as $pass){
								if($pass['smallPicture']){
									unlink(ROOT_PATH.$pass['smallPicture']);
								}
								if($pass['picture']){
									unlink(ROOT_PATH.$pass['picture']);
								}
							}
						}
						$sql = "delete from {$this->table}product_automotive where pid in($expstr);";
						if($this->dbcon->execute_query($sql)){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}*/
					$sql = "update {$this->table}product_job set `deleted` = '1' where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){					
			            /**
				         * added by Kevin.Liu, 2012-02-16
				         * reduce point new rule
				         */
				    	 include_once(SOC_INCLUDE_PATH . '/class.point.php');
				         $objPoint = new Point();
				         foreach ($arrayid as $pid) {
				         	$objPoint->addPointRecords($_SESSION['StoreID'], 'product', $pid, true); 
				         }
				                                           
				        //END
			            
						return true;
					}else{
						return false;
					}
					break;
				case 'publish':
					$sql = "update {$this->table}product_job set `enabled` = '1' where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){
						return true;
					}else{
						return false;
					}
					break;
				case 'unpublish':
					$sql = "update {$this->table}product_job set `enabled` = '0' where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){
						return true;
					}else{
						return false;
					}
					break;
				default:
					break;
			}
			
		}else{
			return false;
		}
	}
}

//------------------------------------------------------------
// xajax function
//------------------------------------------------------------

function getSectorList($id, $objHTML, $level=2, $default_option='Any', $hasAddOptions=true){
	$objResponse = new xajaxResponse();
	if (!empty($id)) {
		if($id!="-1"){
			$addOption	=	array(
			array('name'=>$default_option,'id'=>'-1','place'=>0),
			array('name'=>'Other','id'=>'-2','place'=>1)
			);
		}else{
			$addOption	=	array(
			array('name'=>$default_option,'id'=>'-1','place'=>0)
			);
		}
		if ($hasAddOptions) {
			$arrDate	=	getSectorListFromDB($id, 1, $addOption);
		}else {
			$arrDate	=	getSectorListFromDB($id, 1);
		}
		$strScript	=	ajaxComboxLinkageElement($arrDate, $objHTML, $level);
		if (!empty($strScript)) {
			$objResponse -> script($strScript);
		}

	}
	unset($arrDate);
	return $objResponse;
}

function getState($id, $objHTML, $level=2,$default_option='Any',$hasAddOptions=true){
	$objResponse = new xajaxResponse();
	if (!empty($id)) {

		$addOption	=array('name'=>$default_option,'id'=>'-1');

		if($id!='-1'){
			$arrDate	=	getSubburbArrayFromDB($id);
		}else{
			$arrDate = array();
		}
		if($hasAddOptions){
			array_unshift($arrDate,$addOption);
		}
		$strScript	=	ajaxComboxLinkageElement($arrDate, $objHTML, $level);
		if (!empty($strScript)) {
			$objResponse -> script($strScript);
		}

	}

	unset($arrDate);
	return $objResponse;
}

?>
