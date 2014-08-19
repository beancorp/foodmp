<?php
/**
 * $Id class.estate.php Jessee Wang <at> Tus Dec 02 09:00:05 CST 200812:54:05 $
 *  
 * logic process during store set
 * @author Jessee wang
 * @package buyblitz SOC
 * @subpackage include
 */

class estateClass extends  common {
	/**
	 * @return void 
	 */
	function __construct()
	{
		$this -> dbcon  = &$GLOBALS['dbcon'];
		$this -> time_zone_offset = &$GLOBALS['time_zone_offset'];
		$this -> table	= &$GLOBALS['table'];
		$this -> smarty = &$GLOBALS['smarty'];
		$this -> lang	= &$GLOBALS['_LANG'];
		$this -> blog_length = &$GLOBALS['blog_length'];
	}

	/**
    * @return void 
    */
	function __destruct(){
		unset($this->dbcon,$this -> table,$this->smarty,$this -> lang);
	}

	/**
	 * @title	: searchEstateList
	 * Wed Dec 10 03:49:59 GMT 2008
	 * @input	: array searchForm, 
	 * @output	: array estate list 
	 * @description	: 
	 * @author	: Jessee <support@infinitytesting.com.au>
	 * @version	: V1.0
	 * 
	 */
	function searchEstateList($searchForm, $page){
		# get states list
		$states = getStateArray($searchForm['state_name']);

		$whereSQL = array();
		#  suburb search condition and suburb array
		$suburbArray = explode('.', $searchForm['suburb']);
		$cities = getSuburbArray($searchForm['state_name'] == -1 ? 'NSW' : $searchForm['state_name'],$searchForm['suburb']);
		if ($searchForm['suburb_id'] && $searchForm['distance'] == ""){
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
			$whereSQL[] = "category='".$searchForm['category']."'";
		}
		# property search condition
		if (!empty($searchForm['property'])){
			$whereSQL[] = "property='".$searchForm['property']."'";
		}

		# price condition and negotiable search
		if ($searchForm['category'] < 4){
			$price_string = '';
			if ($searchForm['min_price']>0 and $searchForm['max_price']>0){
				if ($searchForm['min_price']<=$searchForm['max_price']){
					$price_string = "(price >= '".$searchForm['min_price']."' and price <= '".$searchForm['max_price']."')";
				}
			}elseif ($searchForm['min_price']>0){
				$price_string = "price >= '".$searchForm['min_price']."'";
			}elseif ($searchForm['max_price']>0){
				$price_string = "price <= '".$searchForm['max_price']."'";
			}
			if ( $price_string != ''){
				if ($searchForm['negotiable']==1){
					$whereSQL[] = "(".$price_string." or negotiable = 1)";
				}else{
					$whereSQL[] = $price_string;
				}
			}
		}

		# bedroom condition
		if ($searchForm['bedroom']>0){
			if($searchForm['bedroom']==6){
				$whereSQL[] = "bedroom>='".$searchForm['bedroom']."'";
			}else{
				$whereSQL[] = "bedroom='".$searchForm['bedroom']."'";
			}
		}

		# bathroom condition
		if ($searchForm['bathroom']>0){
			if($searchForm['bathroom']==6){
				$whereSQL[] = "bathroom>='".$searchForm['bathroom']."'";
			}else{
				$whereSQL[] = "bathroom='".$searchForm['bathroom']."'";
			}
		}

		# car spaces condition
		if (is_numeric($searchForm['carspace'])){
			if($searchForm['carspace']==6){
				$whereSQL[] = "carspaces>='".$searchForm['carspace']."'";
			}else{
				$whereSQL[] = "carspaces='".$searchForm['carspace']."'";
			}
		}

		# keyword and agent name condition
		if (strlen($searchForm['keyword']) > 0){
			$searchForm['keyword'] = mysql_escape_string($searchForm['keyword']);
			$whereSQL[] = "( item_name like '%".$searchForm['keyword']."%' or content like '%".$searchForm['keyword']."%')";
		}
		if (strlen($searchForm['agent_name']) > 0){
			$searchForm['agent_name'] = mysql_escape_string($searchForm['agent_name']);
			$whereSQL[] = "(detail.bu_name like '%".$searchForm['agent_name']."%' or detail.bu_nickname like '%".$searchForm['agent_name']."%')";
		}
		# conditions
		$queryCondition = "FROM ". $this->table ."product_realestate AS product \n".
		"LEFT JOIN ". $this->table ."bu_detail AS detail ON detail.StoreID = product.StoreID \n".
		"LEFT JOIN ". $this->table ."login AS lg ON lg.StoreID = detail.StoreID \n".
		"LEFT JOIN ". $this->table ."state AS state ON state.id = product.state \n".
		"LEFT JOIN ". $this->table ."suburb AS suburb ON suburb.suburb_id = product.suburb \n".
		"LEFT JOIN ". $this->table ."image AS img ON (img.StoreID=product.StoreID and img.pid = product.pid and img.attrib=0 and img.sort=0) \n".
		"WHERE product.deleted = '' and product.enabled=1 \n".
		"AND detail.CustomerType = 'seller' AND detail.attribute='1' \n".
		"AND NOT(detail.bu_name IS NULL) \n".
		//"AND detail.renewalDate >= '".time()."' \n".
		"AND (product.pay_status=2 OR (product.pay_status=1 and product.renewal_date >= '".time()."')) \n".
		"AND lg.suspend=0 \n";
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
		$query = "SELECT product.*, state.description as stateName, suburb.suburb as suburbName, \n".
		" '' AS flag, '' AS website_name, \n".
		"detail.bu_name,detail.bu_urlstring, img.smallPicture,img.picture  \n".
		"$queryCondition \n".
		"ORDER BY product.datec DESC limit $start,".PAGESIZE;
		//echo "<pre>$query</pre>";
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
			$searchResult[$i]['simage']	=	$objImage->getDefaultImage($searchResult[$i]['smallPicture'],true,0,0,4);
			$searchResult[$i]['bimage']	=	$objImage->getDefaultImage($searchResult[$i]['picture'],false,0,0,9);
			$searchResult[$i]['limage']	=	$objImage->getDefaultImage($searchResult[$i]['picture'],false,0,0,15);
			$searchResult[$i]['website_name'] = clean_url_name($searchResult[$i]['bu_urlstring']);
			//echo "name: ".$searchResult[$i]['bu_name']."; url:".$searchResult[$i]['website_name']."\n<br>";
			//$searchResult[$i]['description'] = strip_tags($searchResult[$i]['content']);
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


	
	function getEstateRandomProduct($num=2) {                
		# conditions
		$queryCondition = "FROM ". $this->table ."product_realestate AS product \n".
		"LEFT JOIN ". $this->table ."bu_detail AS detail ON detail.StoreID = product.StoreID \n".
		"LEFT JOIN ". $this->table ."login AS lg ON lg.StoreID = detail.StoreID \n".
		"LEFT JOIN ". $this->table ."state AS state ON state.id = product.state \n".
		"LEFT JOIN ". $this->table ."suburb AS suburb ON suburb.suburb_id = product.suburb \n".
		"LEFT JOIN ". $this->table ."image AS img ON (img.StoreID=product.StoreID and img.pid = product.pid and img.attrib=0 and img.sort=0) \n".
		"WHERE product.deleted = '' and product.enabled=1 \n".
		"AND detail.CustomerType = 'seller' AND detail.attribute='1' \n".
		"AND NOT(detail.bu_name IS NULL) \n".
		"AND detail.renewalDate >= '".time()."' \n".
		"AND lg.suspend=0 \n".		
		"ORDER BY RAND() \n".
		"LIMIT $num \n";
		
		$query = "SELECT product.*, state.description as stateName, suburb.suburb as suburbName, \n".
		" '' AS flag, '' AS website_name, \n".
		"detail.bu_name,detail.bu_urlstring,detail.bu_address,detail.bu_phone,detail.address_hide,detail.phone_hide,img.smallPicture,img.picture  \n".
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
			$searchResult[$i]['limage']	=	$objImage->getDefaultImage($searchResult[$i]['picture'],false,0,0,15);
			$searchResult[$i]['website_name'] = clean_url_name($searchResult[$i]['bu_urlstring']);
			//echo "name: ".$searchResult[$i]['bu_name']."; url:".$searchResult[$i]['website_name']."\n<br>";
			//$searchResult[$i]['description'] = strip_tags($searchResult[$i]['content']);
		}
		
		return $searchResult;
	}


	/**
	 * The function dispose interface of product.
	 *
	 * @return array
	 */
	public function storeProductAddOrEditOfEstate(){
		$arrResult 	= 	null;
		$StoreID	=	$_SESSION['ShopID'];

		if ($this-> _notVar){
			$arrResult['select']	=	$this -> getFormInputVar();

			$arrResult['select']['op']			=	'edit';
			$arrResult['select']['step']		=	$_REQUEST['step'];
			$arrResult['select']['sortby']		=	$_REQUEST['sortby'];

			$arrResult['state']			=	getStateArrayFromDB();
			$arrResult['suburb']		=	getSubburbArrayFromDB($arrResult['select']['state']);
		}else{
			//modify
			if ($_REQUEST['pid']) {
				$_query	=	"select * from ". $this->table ."product_realestate where pid=".$_REQUEST['pid'] ." and StoreID='".$StoreID ."'";
				$this -> dbcon -> execute_query($_query);
				$arrTemp	=	$this -> dbcon -> fetch_records(true);
				if (is_array($arrTemp)) {
					$arrResult['select']	=	$arrTemp[0];

					$arrResult['select']['op']			=	'edit';
					$arrResult['select']['step']		=	$_REQUEST['step'];
					$arrResult['select']['sortby']		=	$_REQUEST['sortby'];

					//$arrResult['select']['content']		=	$this -> initEditor('content',$arrResult['select']['content'],'SOCBasic',array('100%',220));
					
					$arrResult['select']['featureList']	=	explode('|=&&&&=|', $arrResult['select']['featureList']);

					$arrResult['state']			=	getStateArrayFromDB();
					$arrResult['suburb']		=	getSubburbArrayFromDB($arrResult['select']['state']);
				}
			}else {
				//new
				$arrResult['select']	=	array(
				'op'			=>	'edit',
				'step'			=>	$_REQUEST['step'],
				'sortby'		=>	$_REQUEST['sortby'],

				'item_name'		=>	"",
				'category'		=>	"",
				'property'		=>	"",
				'bedroom'		=>	0,
				'bathroom'		=>	0,
				'carspaces'		=>	'-1',
				'inspect'		=>	'',
				'auction'		=>	"",
				'price'			=>	"",
				'priceMethod'	=>	0,
				'council'		=>	"",
				'water'			=>	"",
				'strata'		=>	"",
				'state'			=>	0,
				'suburb'		=>	0,
				'postcode'		=>	"",
				'location'		=>	"",
				'content'		=>	"",
				'featured'		=>	0,
				'enabled'		=>	1,
				'solded'		=>	0,
				'coname'		=>	"",
				'coaddress'		=>	"",
				'cophone'		=>	""

				);

				$arrResult['state']			=	getStateArrayFromDB();
				//$arrResult['suburb']		=	getSubburbArrayFromDB($arrResult['state'][0]['id']);
				$arrResult['suburb']		=   array();
			}
			
			$stateary =  array('id'=>'-1','name'=>'Select a State','stateName'=>'');
			$suburary =  array('id'=>'-1','name'=>'Select a City/ Suburb');
			if(!is_array($arrResult['state'])){
				$arrResult['state'] = array();
			}
			array_unshift($arrResult['state'],$stateary);
			
			if(!is_array($arrResult['suburb'])){
				$arrResult['suburb'] = array();
			}
			array_unshift($arrResult['suburb'],$suburary);
			
		}

		$objUI	=	new uploadImages();
		$arrResult['images']	=	$objUI -> getDisplayImage('estate',$StoreID,$_REQUEST['pid']);
		unset($objUI);


		$arrResult['list']	=	$this -> __getProductList($StoreID, $_REQUEST['sortby']);

		return $arrResult;
	}

	/**
	 * product's function for save.
	 *
	 * @param string $setOP
	 * @return array
	 */
	public function storeProductAddOrEditOperateOfEstate($setOP){
		$boolResult	=	false;
		$lang		=	&$this->lang;
		$StoreID	=	$_SESSION['ShopID'];
		$pid		=	$_REQUEST['pid'];
		
		$is_fee_year = false;			
		$socObj = new socClass();
		$store_info = $socObj->getStoreInfo($StoreID);
		if ($store_info['product_feetype'] == 'year' && $store_info['product_renewal_date'] >= time()) {
			$is_fee_year = true;
		}	

		if ($setOP == 'next' || $setOP == 'save') {
			$boolResult	=	true;

		}elseif ($setOP == 'edit' || $setOP=='paynow' || $setOP=='renewnow' || $setOP=='paylater') {
			$_var 		= 	$this -> setFormInuptVar();
			extract($_var);

			$dateNow	=	time();
			$strCondition ="where StoreID='$StoreID' and pid='$pid' and deleted=0";

			$strFeatureList	=	implode('|=&&&&=|',array_unique($featureList));
			
			$arrSetting	=	array(
			'StoreID'		=>	$StoreID,
			'item_name'		=>	$item_name,
			'url_item_name'	=>	clean_url_name($item_name),
			'category'		=>	$category,
			'property'		=>	$property,
			'bedroom'		=>	$bedroom,
			'bathroom'		=>	$bathroom,
			'carspaces'		=>	$carspaces,
			'inspect'		=>	$inspect,
			'auction'		=>	$category == 4 ? $auction : 0 ,
			'price'			=>	$price,
			'priceMethod'	=>	($category == 2 || $category == 3) ? $priceMethod : '0',
			'negotiable'	=>	$negotiable?$negotiable:0,
			'council'		=>	$council,
			'water'			=>	$water,
			'strata'		=>	floatval($strata),
			'state'			=>	$state,
			'suburb'		=>	$suburb,
			'postcode'		=>	$postcode,
			'location'		=>	$address,
			'content'		=>	$content,
			'featureList'	=>	$strFeatureList,
			'featured'		=>	$featured ? 1 : 0,
			'enabled'		=>	$enabled ? 1 : 0,
			'solded'		=>	$solded ? 1 : 0,
			'datem'			=>	$dateNow,
			'coname'		=>	$coname,
			'coaddress'		=>	$coaddress,
			'cophone'		=>	$cophone,
			'youtubevideo'	=>	$youtubevideo,
			);
				
			if ($is_fee_year) {
				$arrSetting['pay_status'] = 1;
				$arrSetting['pay_date'] = time();
				$arrSetting['renewal_date'] = $store_info['product_renewal_date'];		
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
			/*****end by roy.luo*****/
			
			if($this -> dbcon-> checkRecordExist($this->table."product_realestate",$strCondition)){
				
				/*if ($this->checkProductName($item_name,$StoreID,$pid)){
					$msg = "item exists! Please try with a new item name.";
					$boolResult = false;
				}else{*/
					if($this->dbcon->update_record($this->table."product_realestate", $arrSetting, $strCondition)){
						$boolResult	=	true;
						$msg = $this->replaceLangVar($lang['pub_clew']['successful'],array('Item',$lang['operation']['update']));
					}else {
						$msg = $this->replaceLangVar($lang['pub_clew']['faild'],array('Item',$lang['operation']['update']));
					}
				//}
			}elseif ( $StoreID > 0 ){
				$arrSetting['StoreID']	=	$StoreID;
				$arrSetting['datec']	=	$dateNow;

				/*if ($this->checkProductName($item_name,$StoreID)){
					$msg = "Item exists! Please try with a new item name.";
					$boolResult = false;
				}else{*/
					if($this->dbcon->insert_record($this->table."product_realestate",$arrSetting)){
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
					}else {
						$msg = $this->replaceLangVar($lang['pub_clew']['faild'],array('Item',$lang['operation']['add']));
					}
				//}
			}
			//modify picture
			if ($boolResult) {
				$pid	=	!empty($newPID) ? $newPID : $_REQUEST['pid'] ;
				$objUI	=	new uploadImages();
				$arrSetting	=	array(
				'0' => array('simage'=> $_REQUEST['mainImage_svalue'], 'bimage'=> $_REQUEST['mainImage_bvalue'])
				);
				$objUI -> setDisplayImage('estate',$arrSetting,$StoreID,$pid,0,0);

				$arrSetting	=	array(
				'0' => array('simage'=> $_REQUEST['subImage0_svalue'], 'bimage'=> $_REQUEST['subImage0_bvalue']),
				'1' => array('simage'=> $_REQUEST['subImage1_svalue'], 'bimage'=> $_REQUEST['subImage1_bvalue']),
				'2' => array('simage'=> $_REQUEST['subImage2_svalue'], 'bimage'=> $_REQUEST['subImage2_bvalue']),
				'3' => array('simage'=> $_REQUEST['subImage3_svalue'], 'bimage'=> $_REQUEST['subImage3_bvalue']),
				'4' => array('simage'=> $_REQUEST['subImage4_svalue'], 'bimage'=> $_REQUEST['subImage4_bvalue']),
				'5' => array('simage'=> $_REQUEST['subImage5_svalue'], 'bimage'=> $_REQUEST['subImage5_bvalue']),
				);
				$objUI -> setDisplayImage('estate', $arrSetting, $StoreID, $pid, 1, 0);

				$arrSetting	=	array(
				'0' => array('simage'=> $_REQUEST['planImage0_svalue'], 'bimage'=> $_REQUEST['planImage0_bvalue'])
				);
				$objUI -> setDisplayImage('estate',$arrSetting,$StoreID,$pid,2,0);
				unset($objUI);
			}
							
			/**
             * added by Kevin.Liu, 2012-02-16
             * point new rule
             */
        	include_once(SOC_INCLUDE_PATH . '/class.point.php');
            $objPoint = new Point();
            if ($is_fee_year) {
                 $objPoint->addPointRecords($StoreID, 'year', $pid);
            } else {
		        $objPoint->addPointRecords($StoreID, 'product', $pid);
            }
            //END
			
			//Go to pay
			if ($setOP=='paynow' || $setOP=='renewnow') {
				$pay_cp = $setOP == 'paynow' ? 'product_pay' : 'product_renew';
				header('Location:/estate/?act=product&cp='.$pay_cp.'&step=4&pid='.$pid);
				exit();
			}
			

		}elseif ($setOP == 'del'){
			$arrSetting	=	array(
			'deleted'	=>	1
			);
			$strCondition	=	"where pid='$pid' and StoreID='$StoreID'";
			if($this->dbcon->update_record($this->table."product_realestate", $arrSetting, $strCondition)){
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
            if ($is_fee_year) {
            	$objPoint->addPointRecords($StoreID, 'year', $pid, true);    
            } else {
            	$objPoint->addPointRecords($StoreID, 'product', $pid, true); 
            }                                 
            //END
		}elseif ($setOP=='upload'){
			//echo "Starting Import";
			// upload the products with csv and images (zip format)
			$csvfile = $_REQUEST['swf_csv'];
			$imgfile = $_REQUEST['swf_img'];
			$_FILES['csv']['size'] = filesize($csvfile);
			$_FILES['image']['size'] = filesize($imgfile);
			$_FILES['csv']['tmp_name'] = $csvfile;
			$_FILES['csv']['name'] = "upload.csv";
			$_FILES['image']['tmp_name'] = $imgfile;
			$_FILES['image']['name'] = "upload.zip";
			
			if($csvfile==""||$imgfile==""){
				$msg = $GLOBALS['multi_msg'][0];
			}elseif (strtolower(substr($_FILES['csv']['name'],-3,3))!='csv'
			or strtolower(substr($_FILES['image']['name'],-3,3))!='zip'){
				$msg = $GLOBALS['multi_msg'][1];
			}else{
				if($_FILES['csv']['size']+$_FILES['image']['size']<=80000000){
					$products = $this->getEstateProductCSV($_FILES['csv']);
	
					if (!$products){
						$msg = $GLOBALS['multi_msg'][2];
					}else{
						list($success,$fail) = $this->importEstateProducts($products,$_FILES['image']);
						$boolResult = true;
						$msg = "$success records imported successfully, $fail records imported failed.";
						if($fail==='all'){
							$msg = "The titles in the csv don&#039;t match the standardized titles completely. Please check the csv.";
						}elseif ($fail>0){
							$this->setFormInuptVar(array('error_more'=>"&nbsp;<a href='#' onclick='javascript:window.open(\"/multi_msg.php\",\"multerr\",\"width=600,height=400,scrollbars=yes,status=yes\");location.href=\"/estate/?act=product&step=4\"'>Click here show more!</a>"));
						}
					}
				}else{
					$msg = $GLOBALS['multi_msg'][11];
				}
				//echo $msg;
				//exit;
			}
			@unlink($csvfile);
			@unlink($imgfile);
			//exit;
		}
		$_SESSION["pageParam"]["msg"] ="";
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

		$_title	=	"t1.*, DATE_FORMAT(FROM_UNIXTIME(t1.datec),'".DATAFORMAT_DB."') as datecfm, DATE_FORMAT(FROM_UNIXTIME(t1.renewal_date),'".DATAFORMAT_DB."') as dateexpired, t2.smallPicture, t2.picture";
		$_where	=	"where t1.StoreID='$StoreID' and t1.deleted=0";

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

		$_query	=	"select $_title from ".$this->table."product_realestate as t1 ".
		"left join ".$this->table."image as t2 on t1.StoreID=t2.StoreID and t1.pid=t2.pid and t2.attrib=0 and t2.sort=0 $_where $_orderby";

		$this -> dbcon -> execute_query($_query);
		$arrTemp	=	$this -> dbcon -> fetch_records(true);

		if (is_array($arrTemp)) {
			$objUI	=	new uploadImages();
			foreach ($arrTemp as $temp){
				$temp['simage']	=	$objUI -> getDefaultImage($temp['smallPicture'],true,0,0,5);
				$temp['bimage']	=	$objUI -> getDefaultImage($temp['picture'],false,0,0,5);
				$arrResult[]	=	$temp;
			}

			unset($objUI);

		}

		return $arrResult;
	}
	/**
	 * @title	: getVehicleRssData
	 * Tue Dec 23 03:05:53 GMT 2008
	 * @input	: int StoreID, int List Number
	 * @output	: array Rss Data
	 * @description	: Generate the Rss Data of Vehicle seller
	 * @author	: Jessee <support@infinitytesting.com.au>
	 * @version	: V1.0
	 * 
	 */
	function getEstateRssData($id,$list_num = 50){
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


		$query = "select product.pid,product.item_name,product.StoreID,product.content,product.datem,";
		$query.= "product.datec from ".$this->table . "product_realestate as product,";
		$query.= $this->table."bu_detail as detail where product.StoreID=detail.StoreID";
		$query  .=  " and product.StoreID= $StoreID ";
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
		$sql = "select count(*) as num from ".$this->table."product_realestate where url_item_name='$name' and StoreID='$StoreID' and deleted=0";
		if ($pid!=0){
			$sql.= " and pid!=$pid";
		}
		$this->dbcon->execute_query($sql);
		$num = $this->dbcon->fetch_records();
		//echo "num:".$num[0]['num'].";$sql\n<br>";
		return ($num[0]['num']>0)?true:false;
	}
	
	function mulitopeartionestate($act,$arrayid=array()){
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
						$sql = "delete from {$this->table}product_realestate where pid in($expstr);";
						if($this->dbcon->execute_query($sql)){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}*/
					$sql = "update {$this->table}product_realestate  set `deleted` = '1' where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){					
			            /**
				         * added by Kevin.Liu, 2012-02-16
				         * reduce point new rule
				         */
			            $StoreID	=	$_SESSION['ShopID'];						
						$is_fee_year = false;
						$socObj = new socClass();
						$store_info = $socObj->getStoreInfo($StoreID);
						if ($store_info['product_feetype'] == 'year' && $store_info['product_renewal_date'] >= time()) {
							$is_fee_year = true;
						}
				    	 include_once(SOC_INCLUDE_PATH . '/class.point.php');
				         $objPoint = new Point();
				         foreach ($arrayid as $pid) {
				         	if ($is_fee_year) {
				         		$objPoint->addPointRecords($_SESSION['StoreID'], 'year', $pid, true); 
				         	} else {
				         		$objPoint->addPointRecords($_SESSION['StoreID'], 'product', $pid, true); 
				         	}	
				         }
				                                           
				        //END
			            
						return true;
					}else{
						return false;
					}
					break;
				case 'publish':
					$sql = "update {$this->table}product_realestate  set `enabled` = '1' where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){
						return true;
					}else{
						return false;
					}
					break;
				case 'unpublish':
					$sql = "update {$this->table}product_realestate set `enabled` = '0' where pid in($expstr);";
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

	function getEstateProductCSV($csv){
		$file = fopen($csv['tmp_name'],'r');
		$products = array();
		while(($row = fgetcsv($file,0,',','"'))!==false){
			$products[] = $row;
		}
		fclose($file);
		return $products;
	}
	
	function importEstateProducts($products,$images){
		$socObj = new socClass();
		$store_info = $socObj->getStoreInfo($_SESSION['ShopID']);
		$renew_date = $store_info['product_renewal_date'] >= time() ? $store_info['product_renewal_date'] : 0;
		$pay_date = $renew_date ? time() : 0;
		$pay_status = $pay_date ? 1 : 0;
		$imgup = new uploadImages();
		$field = $products[0];
		$images = $imgup->getzipProductImages($images);
		$product_csv = $GLOBALS['estate_csv'];
		$success = 0;
		$fail = 0;
		$fail_detail = '';
		$ignore = array('image_name','moreImage1','moreImage2','moreImage3','moreImage4','moreImage5','moreImage6','moreImage7','#');
		unset($_SESSION['multi_upload']);
		if(count(array_unique($field))!=count(array_keys($product_csv))){
			return array(0,'all');
		}
		//check images and save products information to database
		$sql = "insert into ".$this->table."product_realestate (";
		for ($i=0;$i<count($field);$i++){
			$field[$i] = trim($field[$i]);
			$field[$i] = strtolower($field[$i]);
			if($field[$i]==""){
				return 	array(0, 'all');	
			}else{
				if(!isset($product_csv[$field[$i]])){
					return 	array(0, 'all');	
				}
			}
			if(in_array($product_csv[$field[$i]],$ignore)){
				continue;
			}if($product_csv[$field[$i]]=='item_name'){
				$sql.= $product_csv[$field[$i]].',url_item_name,';
			}else{
				$sql.= $product_csv[$field[$i]].',';
			}
		}
		$sql.= "datec,datem,StoreID,pay_status,pay_date,renewal_date) values(";
		$imageinfo = array();
		$arycategory = array();
		$aryProperty = array();
		$aryPreMethod = array();
		foreach ($GLOBALS['_LANG']['val']['category'] as $key=>$v_paren){
			$arycategory[$key] = strtolower($v_paren);
		}
		foreach ($GLOBALS['_LANG']['val']['property'] as $key=>$v_pro){
			$aryProperty[$key] = strtolower($v_pro);
		}
		foreach ($GLOBALS['_LANG']['val']['priceMethod'] as $key=>$v_pMet){
			$aryPreMethod[$key] = strtolower($v_pMet);
		}
		//$mustprice = array(1,2,3);
		$mustprice = array();
		for($i=1,$k=0;$i<=count($products);$i++){
			if (count($products[$i])<count($field)){
				continue;
			}
			$values = '';
			$invalid = 0;
			$categoryid = "";
			$makeid = "";
			$modelid = "";
			for($j=0;$j<count($products[$i]);$j++){
				$products[$i][$j] = trim($products[$i][$j]);
				if($product_csv[$field[$j]]=='#'){
					$_SESSION['multi_upload'][$i]['ID'] = $products[$i][$j];
					continue;
				}elseif($product_csv[$field[$j]]=='item_name'){					
					/*if ($this->checkProductName($products[$i][$j],$_SESSION['ShopID'])){
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][24];
						$invalid = 1;
					}else{
						$invalid = empty($products[$i][$j])?1:$invalid;
						if(empty($products[$i][$j])){
							$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][15];
						}
					}*/
                                        $invalid = empty($products[$i][$j])?1:$invalid;
                                        if(empty($products[$i][$j])){
                                                $_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][15];
                                        }
					
					/***if same item name auto renew the item url****/
					/***2010 04 15 roy.luo***/
					$n=0;
					$item_url= clean_url_name($products[$i][$j]);
					do {
						if($n>0){$item_url = clean_url_name($item_url).date("His",time()).$i;}
						$ckname_bl = $this->checkProductName($item_url,$_SESSION['ShopID']);
						$n++;
					}while ($ckname_bl);
					/****end by roy.luo***/

					$values .= "'".addslashes($products[$i][$j])."','".clean_url_name($item_url)."',";
					
					
				}elseif ($product_csv[$field[$j]]=='category'){
					if($patkey = array_search(strtolower($products[$i][$j]),$arycategory)){
						$values .= "'$patkey',";
					}else{
						$invalid = 1;
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][18];
					}
				}elseif ($product_csv[$field[$j]]=='property'){
					if($prokey = array_search(strtolower($products[$i][$j]),$aryProperty)){
						$values .= "'$prokey',";
					}else{
						$invalid = 1;
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][19];
					}
				}elseif ($product_csv[$field[$j]]=='state'){
					if($stateid = $this->getStateIdForBulkupload(addslashes($products[$i][$j]))){
						$values .= "'$stateid',";
					}else{
						$invalid = 1;
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][20];
					}
				}elseif ($product_csv[$field[$j]]=='suburb'){
					if($suburbid = $this->getSuburbIdForBulkupload($stateid,addslashes($products[$i][$j]))){
						$values .= "'$suburbid',";
					}else{
						$invalid = 1;
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][21];
					}
				}elseif($product_csv[$field[$j]]=='location'){
					if(trim($products[$i][$j]) == ""){
						$invalid = 1;
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][22];
					}else{
						$values .= "'".addslashes($products[$i][$j])."',";
					}
				}elseif ($product_csv[$field[$j]]=='postcode'){
					if(trim($products[$i][$j]) == ""){
						$invalid = 1;
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][23];
					}else{
						$values .= "'".addslashes($products[$i][$j])."',";
					}
				}elseif($product_csv[$field[$j]]=='content'){
					$values.= "'".addslashes(trim($products[$i][$j]))."',";
				}elseif ($product_csv[$field[$j]]=='negotiable'||$product_csv[$field[$j]]=='featured'||$product_csv[$field[$j]]=='enabled'||$product_csv[$field[$j]]=='solded'){
					if($product_csv[$field[$j]]=='enabled'){
						if(strtolower($products[$i][$j])=='no'){
							$values .= "'0',";
						}else{
							$values .= "'1',";
						}
					}else{
						if(strtolower($products[$i][$j])=='yes'){
							$values .= "'1',";
						}else{
							$values .= "'0',";
						}
					}
				}elseif($product_csv[$field[$j]]=='featureList'){
					# db split |=&&&&=|   ||
					if($products[$i][$j]!=""){
						$values .= "'".addslashes(str_replace('||','|=&&&&=|',$products[$i][$j]))."',";
					}else{
						$values .= "'',";
					}
				}else{
					if (in_array($product_csv[$field[$j]],$ignore)){

						/**change picture to image table by roy.luo**/
						if($i>$last_i){
							$z=0;
						}
						$last_i = $i;
						if(isset($images[$products[$i][$j]])&& $images[$products[$i][$j]]['picture']!=""){
							$imageinfo[$i][$k]['picture']=$images[$products[$i][$j]]['picture'];
							$imageinfo[$i][$k]['smallPicture']=$images[$products[$i][$j]]['smallpicture'];
							$imageinfo[$i][$k]['tpl_type'] = 0;
							$imageinfo[$i][$k]['datec'] = time();
							$imageinfo[$i][$k]['datem'] = time();
							if($product_csv[$field[$j]]=='image_name'){
								$imageinfo[$i][$k]['attrib'] = 0;
								$imageinfo[$i][$k]['sort'] = 0;
							}elseif ($product_csv[$field[$j]]=='moreImage7'){
								$imageinfo[$i][$k]['attrib'] = 2;
								$imageinfo[$i][$k]['sort'] = 0;	
							}else{
								$imageinfo[$i][$k]['attrib'] = 1;
								$imageinfo[$i][$k]['sort'] = $z;
								$z++;
							}
							$k++;
						}
					}else{
						if(in_array($product_csv[$field[$j]],array('bedroom','bathroom'))){
							if(intval($products[$i][$j])>0&intval($products[$i][$j])<=6){
								$values.= "'".addslashes(trim(intval($products[$i][$j])))."',";
							}elseif(intval($products[$i][$j])>6){
								$values.= "'6',";
							}else{
								$values.= "'1',";
							}
						}elseif ($product_csv[$field[$j]]=='carspaces'){
							if(intval($products[$i][$j])>0&intval($products[$i][$j])<=6){
								$values.= "'".addslashes(trim(intval($products[$i][$j])))."',";
							}elseif(intval($products[$i][$j])>6){
								$values.= "'6',";
							}else{
								$values.= "'0',";
							}
						}elseif ($product_csv[$field[$j]]=='price'){
							if(trim($products[$i][$j])==""&&in_array($patkey,$mustprice)){
								$invalid = 1;
								$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][6];
							}else{
								//if(in_array($patkey,$mustprice)){
								if($products[$i][$j]!=""){
									$values.= "'".addslashes(floatval(trim($products[$i][$j])))."',";
								}else{
									$values.= "'0',";
								}
							}
						}elseif ($product_csv[$field[$j]]=='priceMethod'){
							if($perkey = array_search(strtolower($products[$i][$j]),$aryPreMethod)){
								$values .= "'$perkey',";
							}else{
								if(in_array($patkey,array(2,3))){
									$values .= "'2',";	
								}else{
									$values .= "'0',";
								}
								
							}
						}else{
							$values.= "'".addslashes(trim($products[$i][$j]))."',";
						}
					}
				}
			}
		
			if ($invalid==1){
				$fail+= 1;
				continue;
			}
			$values.= time().",".time().",".$_SESSION['ShopID'].",".$pay_status.",".$pay_date.",".$renew_date.")";
			if ($this->dbcon->execute_query($sql.$values)){
				$success+= 1;
				$pid = $this->dbcon->insert_id();
				if(!empty($imageinfo[$i])){
					$imageinfo[$i]['pid']=$pid;
					$imageinfo[$i]['StoreID']=$_SESSION['ShopID'];
				}
                                /**
                                 * added by YangBall, 2011-07-05
                                 * referrer new rule
                                 */
                                 require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
                                 $referrer = new Referrer();
                                 $referrer->addReferrerRecord('product', $_SESSION['StoreID']);

                                //END-YangBall
			}else{
				$fail+= 1;
			}
			$invalid = 0;
		}

		/** merge image info **/
		$tmp1 = array();$tmp2 = array();
		foreach ($imageinfo as $k=>$iteminfo){
			foreach ($iteminfo as $key=>$values){
				if(is_array($values)){
					$tmp1[$k][$key] = $values;
				}else{
					$tmp2[$k][$key] = $values;
				}
			}
		}
		$imageinfo = array();
		foreach ($tmp1 as $key=>$item){
			foreach ($item as $values){
				if(!empty($tmp2[$key])){
					$imageinfo[]=array_merge($values,$tmp2[$key]);
				}else{
					$imageinfo[] = $values;
				}
			}

		}
		$imgup->saveImageInfo($imageinfo);
		if ($pay_status == 1) {
			foreach ($imageinfo as $image) {		
	            /**
	             * added by Kevin.Liu, 2012-02-16
	             * point new rule
	             */
	        	include_once(SOC_INCLUDE_PATH . '/class.point.php');
	            $objPoint = new Point();
	            $objPoint->addPointRecords($_SESSION['StoreID'], 'year', $image['pid']);
	            //END
			}
		}
		//echo "succ:$success; fail:$fail;";
		//exit;
		return array($success,$fail);
	}


	function getStateIdForBulkupload($statename){		
		$_query	= "SELECT id FROM ".$this->table."state where description='$statename'";
		$this->dbcon->execute_query($_query);
		$arrTemp =	$this->dbcon->fetch_records(true);
		if($arrTemp){
			return $arrTemp[0]['id'];
		}else{
			return 0;
		}
	}
	
	function getSuburbIdForBulkupload($stateid,$suburb){
		$_query	= "SELECT suburb_id as id FROM ".$this->table."suburb where state_id='$stateid' and suburb='$suburb' ";
		$this->dbcon->execute_query($_query);
		$arrTemp =	$this->dbcon->fetch_records(true);
		if($arrTemp){
			return $arrTemp[0]['id'];
		}else {
			return 0;
		}
	}
}


//------------------------------------------------------------
// xajax function
//------------------------------------------------------------

function getSectorList($id, $objHTML, $level=2,$default_option='Any',$hasAddOptions=true){
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
