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

class adminMain extends common {
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
	 * get Paypal Information
	 *
	 * @return array
	 */
	public function getPaypalInfo()
	{
		$_query = "SELECT * FROM {$this->table}config WHERE type='paypal'";
		$this -> dbcon -> execute_query($_query);
		$arrTemp	=	$this -> dbcon ->fetch_records(true) ;
		if (is_array($arrTemp)) {
			$arrResult = array();
			foreach ($arrTemp as $val) {
				$arrResult[$val['name']] = $val['value'];
			}
		}

		return $arrResult;
	}
	
	        /**
	 * get eWay Information
	 *
	 * @return array
	 */
        
    public function getEwayInfo()
	{
		$_query = "SELECT * FROM {$this->table}config WHERE type='eway'";
		$this -> dbcon -> execute_query($_query);
		$arrTemp	=	$this -> dbcon ->fetch_records(true) ;
		if (is_array($arrTemp)) {
			$arrResult = array();
			foreach ($arrTemp as $val) {
				$arrResult[$val['name']] = $val['value'];
			}
		}

		return $arrResult;
	}

	/**
	 * get Facelike Race Information
	 *
	 * @return array
	 */
	public function getFacelikeInfo()
	{
		$sql = "SELECT * FROM {$this->table}facelike_race WHERE 1 ORDER BY id DESC, modify_date DESC LIMIT 1";
		$res = $this->dbcon->getOne($sql);
		$res['s_hour'] = date('H', $res['start_date']);
		$res['e_hour'] = date('H', $res['end_date']);
		
		return $res;
	}

	/**
	 * get CMS name of items
	 *
	 * @return array
	 */
	public function getCMSItemName(){
		$arrResult = null;

		$_query = "select id,title from ".$this->table."cms order by id";
		$this -> dbcon -> execute_query($_query);
		$arrTemp	=	$this -> dbcon ->fetch_records(true) ;
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp;
		}

		return $arrResult;
	}

	/**
	 * get Fresh Produce Report CMS updated of items
	 *
	 * @return array
	 */
	public function getFreshProduceReportList(){
		$arrResult = null;

		$_query = "select * from ".$this->table."fresh_produce_report ORDER BY id DESC";
		$this -> dbcon -> execute_query($_query);
		$arrTemp	=	$this -> dbcon ->fetch_records(true) ;
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp;
		}

		return $arrResult;
	}

	/**
	 * get CMS Page face of edit
	 *
	 * @return array
	 */
	public function getCMSItemEdit($id=0){
		$arrResult	= null;
		$arrBrowser = $this -> GetClinetBrowser();
		if (!empty($id)) {
			$content = $this->getCMSInfo($id);
			$arrResult = $content;
			$content = $content['body'];
		}

		if ($arrBrowser[0] == 5) {
			$arrResult['content']	=	$this -> initEditor('content',$content,'adminDefault',array('100%','450'));
		}else{
			//Firefox,google,safari
			$arrResult['content']	=	$this -> initEditor('content',$content,'adminDefault',array('80%','450'));
		}

		return $arrResult;
	}

	/**
	 * get CMS Pages infomation
	 *
	 * @param int $id
	 * @return array
	 */
	public function getCMSInfo($id=0){
		$arrResult	= null;

		$_query	=	"select * from ".$this->table."cms where id = '$id'";
		$this -> dbcon -> execute_query($_query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
			$arrResult['body'] = html_entity_decode($arrResult['body']);
		}

		return $arrResult;
	}

	/**
	 * get Fresh Produce Report CMS Pages infomation
	 *
	 * @param int $id
	 * @return array
	 */
	public function getFreshProduceReportCMSInfo($id=0){
		$arrResult	= null;

		$_query	=	"select * from ".$this->table."fresh_produce_report where id = '$id'";
		$this -> dbcon -> execute_query($_query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
			$arrResult['body'] = html_entity_decode($arrResult['body']);
		}

		return $arrResult;
	}

	/**
	 * Delete Fresh Produce Report CMS Pages infomation
	 *
	 * @param int $id
	 * @return array
	 */
	public function delFreshProduceReportCMSInfo($id=0){
		$arrResult	= null;

		$_query	=	"delete from ".$this->table."fresh_produce_report where id = '$id'";
		$this -> dbcon -> execute_query($_query);

		return $this -> dbcon -> execute_query($_query);;
	}

	/**
	 * save data fo cms
	 *
	 * @param array $data
	 */
	public function setCMSInfo($data){
		$boolean = false;
		if (is_array($data)) {
			$boolean = $this -> dbcon -> update_query($this->table."cms" , $data,"where id='$data[id]'");
		}
		return $boolean;
	}

	/**
	 * save data of fresh produce report cms to other table
	 *
	 * @param array $data
	 */
	public function setFreshProduceReport($data){
		$boolean = false;
		if (is_array($data)) {
			$boolean = $this->dbcon->insert_query($this->table.'fresh_produce_report ', $data);
		}
		return $boolean;
	}

	/**
	 * get list of customer
	 *
	 * @param int $pageno
	 * @param string $strParam
	 * @return array
	 */
	public function customerGetList($pageno=1,$strParam='',$field="",$orders="asc"){
		$arrResult = null;
		$perPage	=	15;
		$pageno		=	$pageno >0 ? $pageno : 1;
                
		$sqlWhere = "where ".$this->table."bu_detail.StoreID = ".$this->table."login.StoreID AND ".$this->table."login.level = '2' AND CustomerType ='buyer'" ;
		if ($strParam) {
			$arrParam = unserialize($strParam);
			if (trim($arrParam['bu_name'])!='') {
				$sqlWhere .= " and bu_name like '%".trim($arrParam['bu_name'])."%'";
			}
			if (trim($arrParam['bu_email'])!='') {
				$sqlWhere .= " and ".$this->table."login.user like '%".trim($arrParam['bu_email'])."%'";
			}
			if (trim($arrParam['bu_postcode'])!='') {
				$sqlWhere .= " and ".$this->table."bu_detail.bu_postcode like '%".trim($arrParam['bu_postcode'])."%'";
			}

                        if (trim($arrParam['txt_bu_nickname'])!='') {
				$sqlWhere .= " and ".$this->table."bu_detail.bu_nickname like '%".trim($arrParam['txt_bu_nickname'])."%' ";
			}
		}
				
		switch ($field){
			case 'bu_name':
				$order = " order by {$this->table}bu_detail.bu_name $orders ";
				break;
			case 'bu_nickname':
				$order = " order by {$this->table}bu_detail.bu_nickname $orders ";
				break;
			case 'bu_email':
				$order = " order by {$this->table}bu_detail.bu_email $orders ";
				break;
			case 'bu_postcode':
				$order = " order by {$this->table}bu_detail.bu_postcode $orders ";
				break;
			default:
				$order = "";
				break;
		}

		$_query ="select count(*) from ".$this->table."bu_detail , ".$this->table."login $sqlWhere  order by bu_name";

		$this -> dbcon -> execute_query($_query);
		$count	= $this -> dbcon -> fetch_records();
		$count	=	$count[0][0];
		
		($pageno * $perPage > $count) ? $pageno = ceil($count/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($count) {
			$_query ="select ".$this->table."bu_detail.StoreID,bu_name, bu_nickname ,bu_address,bu_suburb,bu_state,bu_phone,bu_fax,bu_procode,bu_urlstring,bu_postcode,PayDate,renewalDate,lastLogin,".$this->table."login.user as bu_email from ".$this->table."bu_detail ,  ".$this->table."login $sqlWhere  $order limit $start , $perPage";
			$this -> dbcon -> execute_query($_query);
			$arrTemp	= $this -> dbcon -> fetch_records(true);

			if (is_array($arrTemp)) {
				$arrResult['list'] = $arrTemp;

				$params = array(
				'perPage'    => "$perPage",
				'totalItems' => "$count",
				'currentPage'=> "$pageno",
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_customerGetList(\'%d\',\'tabledatalist\',xajax.getFormValues(\'mainForm\'),\''.$field.'\',\''.$orders.'\');return false;',
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
		}
		$arrResult['sort']['page']=$pageno;
		$arrResult['sort']['field']=$field;
		$arrResult['sort']['notold']=$notOld;
		$arrResult['sort']['order']=$orders;
		return $arrResult;
	}


	/**
	 * delete records from customer
	 *
	 * @param int $StoreID
	 * @return boolean
	 */
	public function customerDelete($StoreID){
		$booleanResult	=	false;

		if ($StoreID) {
			$ids = '';
			foreach ($StoreID as $temp){
				$ids .= $temp ? ",'$temp'" : '';
			}
			if (strlen($ids)>1) {
				$ids = substr($ids,1);
				$sql="delete from `".$this->table."bu_detail` where StoreID in  (".$ids.")";
				if ($this->dbcon->execute_query($sql) == true) {
					$sql="delete from `".$this->table."login` where  StoreID in (".$ids.")";
					$this->dbcon->execute_query($sql);
					$booleanResult = true;
				}
			}
		}

		return $booleanResult;
	}

	/**
	 * view record of customer
	 *
	 * @param int $StoreID
	 * @return array
	 */
	public function customerView($StoreID=0){
		$arrResult	=	null;
		if ($StoreID) {
			$_query = "select description from ".$this->table."state order by id ";
			$this -> dbcon -> execute_query($_query);
			$arrState = $this -> dbcon -> fetch_records();

			$_query = "select ".$this->table."bu_detail.StoreID,bu_name, bu_nickname,bu_address,bu_suburb,bu_state,bu_phone,bu_fax,bu_procode,bu_urlstring,bu_postcode,PayDate,renewalDate,lastLogin,bu_desc ,".$this->table."login.user as bu_email from ".$this->table."bu_detail ,  ".$this->table."login Where ".$this->table."bu_detail.StoreID = ".$this->table."login.StoreID AND ".$this->table."bu_detail.StoreID='$StoreID' AND ".$this->table."login.level = '2' AND  CustomerType ='buyer'";
			$this -> dbcon ->execute_query($_query);
			$arrTemp = $this -> dbcon -> fetch_records(true);
			if (is_array($arrTemp)) {
				$arrResult = $arrTemp[0];
				$arrResult['bu_state'] = $arrState[$arrResult['bu_state']]['description'];
			}
		}
		return $arrResult;
	}


	public function savePassword($arrForm){
		$strResult	=	'';

		if (! is_array($arrForm)) {
			$strResult	=	'';
		}elseif (empty($arrForm['oldpass'])){
			$strResult	=	'Please enter the old password.';
		}elseif (empty($arrForm['newpass'])){
			$strResult	=	'Please enter the new password.';
		}elseif (empty($arrForm['confirmpass'])){
			$strResult	=	'Please enter the confirm password.';
		}elseif (! ($arrForm['newpass'] == $arrForm['confirmpass'])){
			$strResult	=	'New Password And Confirmed New Password does not match.';
		}else {
			$uid = $_SESSION['uid'] ;
			//Old password is not correct
			$_query = "select count(*) from ".$this->table."login where password ='". $arrForm['oldpass'] ."' and id = " . $uid ;
			$this -> dbcon -> execute_query($_query) ;
			$arrTemp = $this -> dbcon -> fetch_records($rs) ;

			if($arrTemp[0][0]>0) {
				$_query = "update ".$this->table."login set password = '". $arrForm['newpass'] ."' where id = $uid " ;
				$res = mysql_query($_query) or die(mysql_error()) ;
				$_SESSION['p'] = $newpassword ;
				$strResult = "Password Changed Successfully" ;
			}else{
				$strResult = "Old password is not correct." ;
			}
		}

		return $strResult;
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
	
	public function savePaypalInfo($arrForm){
		$strResult	=	'';

		if (! is_array($arrForm)) {
			$strResult	=	'';
		}elseif (empty($arrForm['paypal_email'])){
			$strResult	=	'Please enter the Paypal Email.';
		}elseif (empty($arrForm['operator'])){
			$strResult	=	'Please enter the Username.';
		}else {
			$pre_paypal_info = $this->getPaypalInfo();
			$uid = $_SESSION['uid'] ;
			$arrSetting = array();
			$config['type'] = 'paypal';
			$config['name'] = 'paypal_mode';
			$config['value'] = trim($arrForm['paypal_mode']);			
			$config_list[] = $config;
			
			$config['type'] = 'paypal';
			$config['name'] = 'paypal_email';
			$config['value'] = trim($arrForm['paypal_email']);			
			$config_list[] = $config;
			
			$config['type'] = 'paypal';
			$config['name'] = 'paypal_app_id';
			$config['value'] = trim($arrForm['paypal_app_id']);			
			$config_list[] = $config;
			
			$config['type'] = 'paypal';
			$config['name'] = 'paypal_api_username';
			$config['value'] = trim($arrForm['paypal_api_username']);			
			$config_list[] = $config;
			
			$config['type'] = 'paypal';
			$config['name'] = 'paypal_api_password';
			$config['value'] = trim($arrForm['paypal_api_password']);			
			$config_list[] = $config;
			
			$config['type'] = 'paypal';
			$config['name'] = 'paypal_api_signature';
			$config['value'] = trim($arrForm['paypal_api_signature']);			
			$config_list[] = $config;
			
			foreach ($config_list as $arrSetting) {
				$condition = "WHERE type='$arrSetting[type]' AND name='$arrSetting[name]'";
				if ($this->dbcon->checkRecordExist($this->table.'config', $condition)) {
					$this->dbcon->update_record($this->table.'config', $arrSetting, $condition);
				} else {
					$this->dbcon->insert_query($this->table.'config', $arrSetting);
				}
			}			

			$pre_mode = $pre_paypal_info['paypal_mode'] == 1 ? 'Live' : 'Sandbox';
			$cur_mode = $arrForm['paypal_mode'] == 1 ? 'Live' : 'Sandbox';
			//Paypal Oper Log
			$log_dir = ROOT_PATH.'/log/';
			!is_dir($log_dir) && mkdir($log_dir);
			$fp = fopen(ROOT_PATH.'/log/paypal_oper_log.txt', 'a+');
			$operator = $arrForm['operator'];
			$comments = $arrForm['comments'];
			$content = "[".date('D M d H:i:s Y', time())."] [".$pre_mode."=>".$cur_mode."] $operator: $comments \r\n";
			fwrite($fp, $content);
			fclose($fp);
			
			$strResult = "Paypal Information Changed Successfully";
		}

		return $strResult;
	}
	
	 public function saveEwayInfo($arrForm){
		$strResult	=	'';

                if (! is_array($arrForm)) {
			$strResult	=	'';
		}elseif (empty($arrForm['eway_email'])){
			$strResult	=	'Please enter the Eway Email.';
		}elseif (empty($arrForm['eway_id'])){
			$strResult	=	'Please enter Eway ID.';
		}else {
			$pre_eway_info = $this->getEwayInfo();
			$uid = $_SESSION['uid'] ;
			$arrSetting = array();
			$config['type'] = 'eway';
			$config['name'] = 'eway_customer_id';
			$config['value'] = trim($arrForm['eway_id']);			
			$config_list[] = $config;
			
			$config['type'] = 'eway';
			$config['name'] = 'eway_customer_email';
			$config['value'] = trim($arrForm['eway_email']);			
			$config_list[] = $config;
			
			
			
			foreach ($config_list as $arrSetting) {
				$condition = "WHERE type='$arrSetting[type]' AND name='$arrSetting[name]'";
				if ($this->dbcon->checkRecordExist($this->table.'config', $condition)) {
					$this->dbcon->update_record($this->table.'config', $arrSetting, $condition);
				} else {
					$this->dbcon->insert_query($this->table.'config', $arrSetting);
				}
			}			

			
			
			$strResult = "Eway Information Changed Successfully";
		}

		return $strResult;
                
        }
	
	public function saveFacelikeRaceInfo($arrForm){
		$strResult	=	'';

		if (! is_array($arrForm)) {
			$strResult	=	'';
		}elseif (empty($arrForm['round'])){
			$strResult	=	'Please enter the Round.';
		}elseif (empty($arrForm['start_date'])){
			$strResult	=	'Please enter the Start Date.';
		}elseif (empty($arrForm['end_date'])){
			$strResult	=	'Please enter the End Date.';
		}else {					
			/**date**/
			$startDate = 0;
			$enddate = 0;
			if(DATAFORMAT_DB=="%m/%d/%Y"){
				if($arrForm['start_date']!=""){
					list($month,$day,$year) = split('/',$arrForm['start_date']);
					$startDate = mktime($arrForm['s_hour'],($arrForm['s_min']!="")?intval($arrForm['s_min']):0,0,$month,$day,$year);
				}
				if($arrForm['end_date']!=""){
					list($e_month,$e_day,$e_year) = split('/',$arrForm['end_date']);
					$enddate = mktime($arrForm['e_hour'],($arrForm['e_min']!="")?intval($arrForm['e_min']):0,59,$e_month,$e_day,$e_year);
				}else{
					$enddate = time();
				}
			}else{
				if($arrForm['start_date']!=""){
					list($day,$month,$year) = split('/',$arrForm['start_date']);
					$startDate = mktime($arrForm['s_hour'],($arrForm['s_min']!="")?intval($arrForm['s_min']):0,0,$month,$day,$year);
				}
				if($arrForm['end_date']!=""){
					list($e_day,$e_month,$e_year) = split('/',$arrForm['end_date']);
					$enddate = mktime($arrForm['e_hour'],($arrForm['e_min']!="")?intval($arrForm['e_min']):0,59,$e_month,$e_day,$e_year);
				}else{
					$enddate = time();
				}		
			}
			
			$arrSetting = array(
				'round'			=> $arrForm['round'],
				'start_date'	=> $startDate,
				'end_date'		=> $enddate,
				'image'			=> $arrForm['image'],
				'description'	=> $arrForm['description'],
				'modify_date'	=> time()
			);
			
			$res = $this->dbcon->insert_query($this->table.'facelike_race', $arrSetting);	
			
			if ($res) {
				$strResult = "Facelike Race Information Changed Successfully";
			} else {
				$strResult = "Facelike Race Information Changed Failed";
			}
			
		}

		return $strResult;
	}

}


/*********************
* xajax function
**********************/

/**
 * load cms pages content
 *
 * @param int $id
 * @return objResponse
 */
function displayCMSItem($id){
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminMain 	= &$GLOBALS['objAdminMain'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	$objResponse -> assign('submitButton','disabled',"disabled");

	if ($id) {
		$arrTemp = $objAdminMain -> getCMSInfo($id);
		$objResponse -> assign('id','value',$arrTemp['id']);
		$objResponse -> assign('title','value',$arrTemp['title']);
		$objResponse -> assign('content','value',$arrTemp['body']);
		if ($arrTemp['id'] == '119') {
			$objResponse -> assign('reminder','innerHTML','<input type="checkbox" name="sendreminder" value="1" />Send a reminder');
		} else {
			$objResponse -> assign('reminder','innerHTML','');
		}
		$objResponse -> script("autoLoadEdit('content');");
		$messages	=	$arrTemp['title'];
	}

	$objResponse -> clear('submitButton','disabled');
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	return $objResponse;
}

/**
 * load Fresh Produce Report cms pages content
 *
 * @param int $id
 * @return objResponse
 */
function displayFreshProduceReportCMSItem($id){
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminMain 	= &$GLOBALS['objAdminMain'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	$objResponse -> assign('submitButton','disabled',"disabled");

	if ($id) {
		$arrTemp = $objAdminMain -> getFreshProduceReportCMSInfo($id);
		$arrTemp['title'] = 'Fresh Produce Report('.date(str_replace('%', '', DATAFORMAT_DB).' H:i:s', $arrTemp['updated']).')';
		$objResponse -> assign('id','value',$arrTemp['id']);
		$objResponse -> assign('title','value',$arrTemp['title']);
		$objResponse -> assign('content','value',$arrTemp['body']);
		$objResponse -> script("autoLoadEdit('content');");
		$messages	=	$arrTemp['title'];
	}

	$objResponse -> clear('submitButton','disabled');
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	return $objResponse;
}

/**
 * Delete Fresh Produce Report cms pages content
 *
 * @param int $id
 * @return objResponse
 */
function delFreshProduceReportCMSItem($id){
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminMain 	= &$GLOBALS['objAdminMain'];
	$messages		=	'';
	$objResponse = new xajaxResponse();

	if ($id) {
		$objAdminMain -> delFreshProduceReportCMSInfo($id);
		$messages	=	'Fresh Produce Report Record has been delete successfully.';
	}
	$objResponse -> alert($messages);
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> redirect('/admin/?act=main&cp=freshreportrecords');
	return $objResponse;
}

/**
 * save cms pages content
 *
 * @param htmlForm $objForm
 * @return objResponse
 */
function saveCMSItem($objForm){
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminMain 	= &$GLOBALS['objAdminMain'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	$objResponse -> assign('submitButton','disabled',"disabled");

	if ($objForm['id']) {
		$data = array('id'=>$objForm['id'] ,
		'body' => addslashes($objForm['content']));

		if($objAdminMain -> setCMSInfo($data)){
			$messages = $objAdminMain->replaceLangVar($lang['pub_clew']['successful'],array($objForm['title'],$lang['operation']['update']));
		}else {
			$messages = $objAdminMain->replaceLangVar($lang['pub_clew']['faild'],array($objForm['title'],$lang['operation']['update']));
		}
	}else{
		$messages = $objAdminMain->replaceLangVar($lang['pub_clew']['faild'],array($objForm['title'],$lang['operation']['update']));
	}
	
	if ($objForm['id'] == 119) {
		unset($data['id']);
		$data['updated'] = time();
		$objAdminMain->setFreshProduceReport($data);
	}

	$objResponse -> clear('submitButton','disabled');
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> alert($messages);
	if($objForm['sendreminder']) {
		$objResponse -> redirect('/admin/?act=msg&cp=sendreminder');
	}
	return $objResponse;
}


/*******************************
* customer function of xajax
*******************************/

/**
 * get list of customer
 *
 * @param int $pageno
 * @param string $objHTML
 * @param string $param
 * @return objResponse
 */
function customerGetList($pageno=1,$objHTML='', $param='',$field="",$order="ASC"){
	$smarty			= &$GLOBALS['smarty'];
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminMain 	= &$GLOBALS['objAdminMain'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	$req['list'] = $objAdminMain -> customerGetList($pageno,$param['searchparam'],$field,$order);
	$req['nofull'] = true ;
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_customer.tpl');
	$objResponse -> assign("$objHTML",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);

	return $objResponse;
}

/**
 * search records of customer
 *
 * @param objForm $objForm
 * @param string $objHTML
 * @return objResponse
 */
function customerSearch($objForm , $objHTML=''){
	$smarty			= &$GLOBALS['smarty'];
	$objAdminMain 	= &$GLOBALS['objAdminMain'];
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	//if ($objForm) {
		$param = serialize($objForm);
		$req['list'] = $objAdminMain -> customerGetList(1,$param);
		$req['nofull'] = true ;
		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('admin_customer.tpl');
		$objResponse -> assign("$objHTML",'innerHTML',$content);
		$objResponse -> assign('searchparam', 'value' ,$param);
		$objResponse -> assign("pageno",'value',1);
	//}

	return $objResponse;
}

/**
 * view records of customer
 *
 * @param int $StoreID
 * @return objResponse
 */
function customerView($StoreID){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$smarty			= &$GLOBALS['smarty'];
	$objAdminMain 	= &$GLOBALS['objAdminMain'];

	$req['list'] =	$objAdminMain -> customerView($StoreID);
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_customer_view.tpl');

	$objResponse -> assign("tabledatalist",'innerHTML',$content);

	//	$objResponse -> alert($req['list']);
	return $objResponse;
}

/**
 * delete records from customer
 *
 * @param objForm $objForm
 * @return objResponse
 */
function customerDelete($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$smarty			= &$GLOBALS['smarty'];
	$objAdminMain 	= &$GLOBALS['objAdminMain'];

	if ($objAdminMain -> customerDelete($objForm['id'])) {
		$messages = $objAdminMain -> replaceLangVar($objAdminMain -> lang['pub_clew']['successful'],array('Item',$objAdminMain->lang['operation']['delete']));
	}else{
		$messages = $objAdminMain -> replaceLangVar($objAdminMain -> lang['pub_clew']['faild'],array('Item',$objAdminMain->lang['operation']['delete']));
	}

	$param = $objForm['searchparam'];
	$req['list'] = $objAdminMain -> customerGetList($objForm['pageno'],$param);
	$req['nofull'] = true ;
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_customer.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> alert($messages);

	return $objResponse;
}

/*******************************
* password function of xajax
*******************************/

function savePassword($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminMain 	= &$GLOBALS['objAdminMain'];

	$messages	=	$objAdminMain -> savePassword($objForm);

	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);

	return $objResponse;
}
function saveCommission($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminMain 	= &$GLOBALS['objAdminMain'];

	$messages	=	$objAdminMain -> saveCommission($objForm);

	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);

	return $objResponse;
}
function savePaypalInfo($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminMain 	= &$GLOBALS['objAdminMain'];

	$messages	=	$objAdminMain -> savePaypalInfo($objForm);

	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);
	$objResponse -> assign("operator",'value','');
	$objResponse -> assign("comments",'value','');

	return $objResponse;
}
function saveFacelikeRaceInfo($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminMain 	= &$GLOBALS['objAdminMain'];

	$messages	=	$objAdminMain -> saveFacelikeRaceInfo($objForm);

	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);

	return $objResponse;
}

function saveEwayInfo($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminMain 	= &$GLOBALS['objAdminMain'];

	$messages	=	$objAdminMain -> saveEwayInfo($objForm);

	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);
	$objResponse -> assign("operator",'value','');
	$objResponse -> assign("comments",'value','');

	return $objResponse;
}

?>