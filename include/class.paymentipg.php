<?php
/**
 * Mon Nov 24 14:23:09 GMT 2008 14:23:09
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * payment IPG class and function
 * ------------------------------------------------------------
 * \include\class.paymentipg.php
 */

class paymentIPG extends common {
	var $dbcon 	= 	null;
	var $table	=	'';
	var $smarty = 	null;
	var $lang	=	null;
	var $strMsg	=	'';				//clew messages
	var $jumpPath	=	'';
	
	//var $toemail = "steven.cheung@infinitytechnologies.com.au";
	//var $ccemail = "xiong.wu@infinitytesting.com.au";
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
	 * init interface by IPG of payment
	 *
	 * @return array
	 */
	public function  getPaymentParams ($arrParams=""){
		$arrResult	=	null;

		unset($_SESSION['paymentUserInfo']);
		if($arrParams==""){
			$_SESSION['paymentUserInfo']	=	$this -> setFormInuptVar();
		}else{
			$_SESSION['paymentUserInfo']	=	$arrParams;
		}

		$arrResult	=	array(
		'cardNumber'	=> THE_PORT=='3007' ? '4111111111111111' : '',
		'amount'		=> THE_PORT=='3007' ? '1.00' : number_format($_REQUEST['amount'],2),
		'expiryMonth'	=> getExpMonth(),
		'expiryYear'	=> getExpYear(),
		'email'			=> $_SESSION['paymentUserInfo']['bu_user']
		);

		return $arrResult;
	}

	/**
	 * Submit table of payment
	 *
	 * @param array $arrParams
	 * @package array $arrUserInfo
	 * @return boolean
	 */
	public function paymentSubmit($arrParams, $arrUserInfo){
		$booleanResult	=	false;

		$_var 		= 	$this -> setFormInuptVar($arrParams);
		extract($_var);
		if (empty($cardNumber)) {
			$this -> strMsg = $this -> replaceLangVar($this->lang['pub_clew']['required'],array($this->lang['payclew']['CCNumber']));
		}elseif ($amount > 0 ) {
			define("THE_INTERFACE", "CREDITCARD");
			define("THE_TRANSACTION_TYPE", "PURCHASE");
			//define("THE_WEBPAY_PHP_LIB", "webpay_php.so");

			//Attempt to load the WebpayPHP library - display message on error.
			//if (!extension_loaded(THE_WEBPAY_PHP_LIB)) {
			//			if (!dl(THE_WEBPAY_PHP_LIB)) {
			//				$this -> addMessage($this -> replaceLangVar($this->lang['pub_clew']['loadlib'],array('Unable')) . THE_WEBPAY_PHP_LIB);
			//			} else {
			//
			//Create Webpay Transaction  Starting webpay Transaction
			$webpayRef = newBundle();
			if($webpayRef == NULL){
				$this -> addMessage($this->lang['pub_clew']['installProblem']);
			}else{
				put($webpayRef, "DEBUG", "ON");
				put($webpayRef, "LOGFILE", dirname(dirname(__FILE__))."/_log_/webpay.log");
				put_ClientID ($webpayRef, THE_CLIENT_ID);
				put_CertificatePath ($webpayRef, THE_CERT_PATH);
				put_CertificatePassword ($webpayRef, THE_CERT_PASSWORD);
				setPort($webpayRef, THE_PORT);
				setServers($webpayRef, THE_SERVER);
				put($webpayRef, "INTERFACE", THE_INTERFACE);
				put($webpayRef, "TRANSACTIONTYPE", THE_TRANSACTION_TYPE);

				//Retrieve Form Data
				$expiryDate = substr('0'.$expiryMonth,-2) . substr($expiryYear,2);
				put($webpayRef, "TOTALAMOUNT", $amount);
				put($webpayRef, "CARDDATA", trim($cardNumber));
				put($webpayRef, "CARDEXPIRYDATE", $expiryDate);
				put($webpayRef, "COMMENT", $arrUserInfo['bu_name'] ."/". $arrUserInfo['bu_user']);
				empty($cvc2) ? '' : put($webpayRef, "CVC2", $cvc2);

				//Send the transaction to the Webpay Transaction Server
				$tranProcessed = executeTransaction( $webpayRef );
				if ($tranProcessed == "true") {
					//$this -> addMessage( "Successfully communicated with the WTS");
				} else {
					$this -> addMessage( $this -> replaceLangVar($this->lang['payclew']['WTS'],array('Unable')));
					//Try transaction recovery
					$transactionRef = get( $webpayRef, "TXNREFERENCE" );
					if ($transactionRef) {
						//We have a transaction reference so attempt a status transaction.
						$this -> addMessage( "Performing status check with Transaction Ref = [$transactionRef]");
						if($this -> __doStatusCheck($webpayRef)) {
							$this -> addMessage( "Status Check Successful - Details are displayed below.");
						} else {
							$this -> addMessage( "Status check failed: Unknown transaction status.\nPlease wait a short while and try status check again using Transaction Ref [$transactionRef].");
						}
					} else {
						// There is no transaction reference number so the transaction has failed completely.
						// It can be safely reprocessed.
						$this -> addMessage( "The transaction can be safely reprocessed as no Transaction Reference Number exists.");
					}
				}

				$booleanResult = $this -> __displayResults( $webpayRef );

				cleanup( $webpayRef );
			}
		}
		//		}
		//}

		return $booleanResult;
	}


	/**
	 * User payment include regedit/ keep on / upgrade
	 *
	 * @param array $arrParams
	 * @param boolean $hasEmail  this is switch of debug
	 * @return boolean
	 */
	public function userPayment($arrParams, $hasEmail=true){
		$booleanResult	=	false;
		$arrUserInfo	=	&$_SESSION['paymentUserInfo'];

		$_titels		=	"t2.StoreID, t2.bu_name, t2.bu_email, t2.renewalDate, t2.bu_repid, t2.CustomerType, t2.bu_nickname, t2.bu_name, t2.bu_address, t2.bu_state, t2.bu_suburb, t2.bu_postcode, t2.bu_area, t2.bu_phone, t1.password";
		$_query		=	"select $_titels from ".$this->table."login as t1 ".
		"left join ".$this->table."bu_detail as t2 on t1.StoreID=t2.StoreID ".
		"where t1.`user`='".$arrUserInfo['bu_user']."' and t1.attribute='". ($arrUserInfo['attribute'] ? $arrUserInfo['attribute'] : $_SESSION['attribute']) ."'";
		$this->dbcon->execute_query($_query);
		$arrUser 	=	$this->dbcon->fetch_records(true);


		if (is_array($arrUser)) {
			$arrUser	=	$arrUser[0];
		}

		if (!$_SESSION['StoreID']) {

			if ($this -> paymentSubmit($arrParams, $arrUserInfo)) {
				$arrUserInfo['bu_email']	=	$arrUserInfo['bu_user'];
				$arrUserInfo	=	array_merge($arrParams, $arrUserInfo);
				$booleanResult = $this -> insertUserInfo($arrUserInfo,$hasEmail);
				$socobj = new socClass();
				$socobj->setrefEarn($arrUserInfo['referrer'],$arrUserInfo['amount'],$arrUserInfo['bu_nickname']);
				$this -> jumpPath = SOC_HTTPS_HOST.'login_ipn.php?uname='.$arrUserInfo['bu_user'].'&password='.$arrUserInfo['bu_password'].'&user_type='.$arrUserInfo['attribute']."&hascount=hascount";
			}

		}elseif ($arrUserInfo['keepon'] == 'yes') {

			if ($this -> paymentSubmit($arrParams, $arrUserInfo)) {
				$arrUserInfo = array_merge($arrUser, $arrParams, $arrUserInfo);
				$booleanResult = $this -> keepupUserInfo($arrUserInfo,$hasEmail);
				$this -> jumpPath = SOC_HTTPS_HOST.'soc.php?cp=payreports';
			}

		}elseif (4==$_SESSION['attribute'] or (3==$_SESSION['attribute'] and 3==$_SESSION['subAttrib'])) {

			if ($this -> paymentSubmit($arrParams, $arrUserInfo)) {
				$arrUserInfo['StoreID']	=	$_SESSION['StoreID'];
				$arrUserInfo['bu_email']	=	$_SESSION['email'];
				$arrUserInfo	=	array_merge($arrParams, $arrUserInfo);
				$booleanResult = $this -> upgradeUserInfo($arrUserInfo,$hasEmail);
				$this -> jumpPath = SOC_HTTPS_HOST.'login_ipn.php?uname='.$arrUserInfo['bu_user'].'&password='.$arrUserInfo['bu_password'].'&user_type='.$arrUserInfo['attribute']."&hascount=hascount";
			}

		}else{
			$this -> strMsg = "The account you used is not available, please contact us.";
		}

		unset($arrUser);


		if ($booleanResult) {
			unset($arrUserInfo, $_SESSION['paymentUserInfo']);
		}

		return $booleanResult;
	}

	/**
	 * free regedit seller
	 *
	 * @return boolean
	 */
	public function userRegFree(){
		$booleanResult	=	false;

		$arrUserInfo	=	$this -> setFormInuptVar();
		$arrUserInfo['amount']		=	0;
		$arrUserInfo['bu_email']	=	$arrUserInfo['bu_user'];

                if(!$_SESSION['StoreID']) {
                    $booleanResult = $this -> insertUserInfo($arrUserInfo);
                }
                else {
                    $arrUserInfo['StoreID']	=	$_SESSION['StoreID'];
                    $arrUserInfo['bu_email']	=	$_SESSION['email'];
                    $booleanResult = $this -> upgradeUserInfo($arrUserInfo,true);
                    $this -> jumpPath = SOC_HTTPS_HOST.'login_ipn.php?uname='.$arrUserInfo['bu_user'].'&password='.$arrUserInfo['bu_password'].'&user_type='.$arrUserInfo['attribute']."&hascount=hascount";

                }
		
		if ($booleanResult) {
			$this -> jumpPath = SOC_HTTPS_HOST.'login_ipn.php?uname='.$arrUserInfo['bu_user'].'&password='.$arrUserInfo['bu_password'].'&user_type='.$arrUserInfo['attribute'] .'&msg=You have created an account successfully.';
			$this -> destroyFormInputVar();
		}

		return $booleanResult;
	}

	/**
	 * insert user infomation to database, new account
	 *
	 * @param array $arrParams
	 * @param boolean $hasEmail
	 * @return boolean
	 */
	public function insertUserInfo($arrUserInfo, $hasEmail=true){
		$booleanResult	=	false;

		extract($arrUserInfo);
		$datenow	=	time();
		$expiringDate	=	$this -> __paymentActiveTime($amount);
		if($expiringDate == 0){
			$expiringDate = time();
		}

		$arrSetting = array(
		'attribute'		=>	"$attribute",
		'subAttrib'		=>	$attribute == 1 ? $subattr1 : ($attribute == 2 ? $subattr2 : ($attribute == 3 ? $subattr3 : ($attribute == 5 ? $subattr5 : 0))),
		'mobile'		=>	"$mobile",
		'licence'		=>	$attribute == 2 && $subattr1==2 ? $licence : '',

		'bu_username'	=>	"$bu_username",
		'bu_name'		=>	addslashes("$bu_name"),
		'bu_nickname'	=>	addslashes("$bu_nickname"),
		'bu_address'	=>	addslashes("$bu_address"),
		'bu_suburb'		=>	"$bu_suburb",
		'bu_state'		=>	"$bu_state",
		'bu_phone'		=>	"$bu_phone",
		'bu_fax'		=>	"$bu_fax",
		'bu_procode'	=>	"$bu_procode",
		'bu_urlstring'	=>	clean_url_name($bu_urlstring),
		'bu_website'	=>	addslashes("$bu_website"),
		'bu_email'		=>	"$bu_user",
		'bu_postcode'	=>	"$bu_postcode",
		'address_hide'	=>	"$address_hide",
		'phone_hide'	=>	"$phone_hide",
		'bu_area'		=>	"$bu_area",
		'CustomerType'	=>	"seller",
		'contact'		=>	"$contact",
		'launch_date'	=>	$datenow,
		'PayDate'		=>	$datenow,
		'renewalDate'	=>  $expiringDate,
		'referrer'		=>  $referrer,
		'ref_name'		=>  getrefname(),
                'gender'        =>  intval($gender)
		);
		//$this -> addMessage(serialize($arrSetting));
                if($_SESSION['StoreID']) {
                    $resultTmp=$this->dbcon->update_record($this->table."bu_detail",$arrSetting,'where StoreID="'.$_SESSION['StoreID'].'"');
                }
                else {
                    $resultTmp=$this->dbcon-> insert_record($this->table."bu_detail", $arrSetting);
                }

                $isUp = ($arrSetting['attribute'] == 3 and 3 == $arrSetting['subAttrib']) ? true :  false;

		if ($resultTmp) {
			$StoreID = $this->dbcon->lastInsertId();
			unset($arrSetting);

			$url_store_name = clean_url_name($bu_urlstring);
			$arrSetting = array(
			'StoreID'		=>	"$StoreID",
			'username'		=>	$attribute==5 ? "$bu_username" : '',
			'user'			=>	"$bu_user",
			'password'		=>	"$bu_password",
			'level'			=>	"1",
			'store_name'	=>	$url_store_name,
			'attribute'		=>  "$attribute"
			);

            if($_SESSION['StoreID']) {
                $resultTmp=$this->dbcon->update_record($this->table."login",$arrSetting,'where StoreID="'.$_SESSION['StoreID'].'"');
            }
            else {
                $resultTmp=$this->dbcon-> insert_record($this->table."login", $arrSetting);
                
                if ($referrer) {
					include_once(SOC_INCLUDE_PATH . '/class.socstore.php');
	        		$stostoreObj = new socstoreClass();
	                $stostoreObj->updateStoreRef('', $referrer);	
	        	}
	        	require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
	            $referrer = new Referrer();
				$referrer->addReferrerRecord('reg', $StoreID);
            }

                        if($resultTmp) {
				$booleanResult	= true;
				if ($attribute == 5) {
					$tpl_fw = 'foodwine-a';
					include_once(SOC_INCLUDE_PATH . '/class.FoodWine.php');
					$sub_attr = $attribute == 1 ? $subattr1 : ($attribute == 2 ? $subattr2 : ($attribute == 3 ? $subattr3 : ($attribute == 5 ? $subattr5 : 0)));
					$fw_type = getFoodWineType($sub_attr);
					$tpl_fw = $fw_type == 'food' ? 'foodwine-a' : 'foodwine-d';
				}
				//insert default template
				$arrSetting = array(
				'TemplateName'	=>	$attribute == 0 ? 'tmp-n-a' : ($attribute == 1 ? 'estate-c' : ($attribute == 2 ? 'auto-c' : ($attribute == 3 ? ($subattr3 == 3 ? 'job-c' : 'job-c') : ($attribute == 5 ? $tpl_fw : 'tmp-n-a')))),
				'TemplateBGColor'	=>	'33',
				'StoreID'		=>	"$StoreID",
				'tpl_type'		=>	($attribute+1)
				);

                                if($_SESSION['StoreID']) {
                                    $resultTmp=$this->dbcon->update_record($this->table."template_details",$arrSetting,'where StoreID="'.$_SESSION['StoreID'].'"');
                                }
                                else {
                                    $resultTmp=$this->dbcon-> insert_record($this->table."template_details", $arrSetting);
                                }

				//send email
				if ($hasEmail) {
					$arrParams		=	$arrUserInfo;
					$arrParams['display']			=	'regedit';
					$arrParams['To']				=	$bu_user;
					$arrParams['Subject']			=	'Welcome To SOC Exchange' .($attribute == 1?' - Real Estate':($attribute == 2?' - Auto':($attribute == 3?' - Careers':($attribute == 5?' - Food & Wine':' - Buy & Sell'))));
					$arrParams['seller_name']		=	stripslashes(str_replace("''", "'", $bu_name));
					$arrParams['subAttrib']			=	$attribute == 1 ? $subattr1 : ($attribute == 2 ? $subattr2 : ($attribute == 3 ? $subattr3 : 0));
					$arrParams['stateName']			=	getStateByName($bu_state);
					$arrParams['expiringDate']		=	$isUp ? 'No expire date' : date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$expiringDate);
					$objEmail	=	new emailClass();
					$res = false;
					if ($arrParams['attribute'] == 0 || $arrParams['attribute'] == 5 || ($arrParams['attribute'] == 3 && $arrParams['subAttrib'] == 3) || $arrParams['display'] != 'regedit' || $arrParams['bu_procode'] != '') {
						$res = $objEmail -> send($arrParams,'email_userinfo.tpl');
					}
					if($res){
						$adminEmail = new emailClass();			
						$arrParams['To']	=	'patrick@infinitytechnologies.com.au';						//to admin
						$arrParams['BCC']	=	'james.w@infinitytechnologies.com.hk';
						$arrParams['type']	=	'Seller Registration';
						$arrParams['cardNumber']	=	$this -> displayCardNumber($arrParams['cardNumber']);

						$adminEmail -> send($arrParams,'email_toadmin.tpl', false);
						unset($adminEmail);
					}
					unset($objEmail);
				}
			}
		}

		return $booleanResult;
	}


	/**
	 * Keep Up of user
	 *
	 * @param array $arrUserInfo
	 * @param boolean $hasEmail
	 * @return boolean
	 */
	public function keepupUserInfo($arrUserInfo, $hasEmail=true){
            
		$booleanResult	=	false;
		extract($arrUserInfo);

		$datenow	=	time();
		$expiringDate	=	$this -> __paymentActiveTime($amount, $renewalDate);
		$arrSetting = array(
		'PayDate'		=>	$datenow,
		'renewalDate'	=>	$expiringDate,
                 'bu_urlstring'	=>	clean_url_name($bu_urlstring),
		'bu_website'	=> $bu_website,
                'bu_name'   => $bu_name
		);
                // is up
                $isUp = ($_SESSION['attribute']==3 and $_SESSION['subAttrib'] == 3) ? true : false;
		$_SESSION['attribute'] == 3 && $_SESSION['subAttrib'] == 3 ? $arrSetting['subAttrib']=2 : '' ;

		$strCondition	=	 "where  StoreID=".$StoreID."";
		if($this->dbcon-> update_record($this->table."bu_detail", $arrSetting, $strCondition)) {
                    $this->dbcon->update_record($this->table."login",array('store_name'=>clean_url_name($bu_urlstring)), $strCondition);
			$booleanResult	=	true;
			$_SESSION['attribute'] == 3 && $_SESSION['subAttrib'] == 3 ? $_SESSION['subAttrib']=2 : '' ;

			//send email
			if ($hasEmail) {
				$arrParams		=	$arrUserInfo;
				$arrParams['display']			=	'keepup';
				$arrParams['To']				=	$bu_email;
				$arrParams['Subject']			=	'Welcome To SOC Exchange' .($_SESSION['attribute'] == 1?' - Real Estate':($_SESSION['attribute'] == 2?' - Auto':($_SESSION['attribute'] == 3?' - Careers':($_SESSION['attribute'] == 5?' - Food & Wine':' - Buy & Sell'))));
				$arrParams['seller_name']		=	stripslashes($bu_name);
				$arrParams['stateName']			=	getStateByName($bu_state);
				$arrParams['expiringDate']		=	$isUp ? 'No expire date' : date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$expiringDate);
                                $arrParams['email_regards'] =	'SOC Exchange Australia';
				$objEmail	=	new emailClass();
				if($objEmail -> send($arrParams,'email_userinfo.tpl')){
					$adminEmail = new emailClass();		
					$arrParams['To']	=	'patrick@infinitytechnologies.com.au';						//to admin
					$arrParams['BCC']	=	'james.w@infinitytechnologies.com.hk';
					$arrParams['type']	=	'Renew';
					$arrParams['cardNumber']	=	$this -> displayCardNumber($arrParams['cardNumber']);
					$adminEmail -> send($arrParams,'email_toadmin.tpl', false);
					unset($adminEmail);
				}
				unset($objEmail);
			}
		}

		return $booleanResult;
	}


	/**
	 * Upgrade of user
	 *
	 * @param array $arrUserInfo
	 * @param boolean $hasEmail
	 * @return boolean
	 */
	private function upgradeUserInfo($arrUserInfo, $hasEmail=true){
		$booleanResult	=	false;
                global $email_regards;
		extract($arrUserInfo);
		$datenow	=	time();
		$expiringDate	=	$this -> __paymentActiveTime($amount);

		$arrSetting = array(
		'attribute'		=>	"$attribute",
		'subAttrib'		=>	$attribute == 1 ? $subattr1 : ($attribute == 2 ? $subattr2 : ($attribute == 3 ? $subattr3 : 0)),
		//'mobile'		=>	$attribute == 1 && $subattr1==1 ? $mobile : '',
		'mobile'		=>	$mobile,
		'licence'		=>	$attribute == 2 && $subattr1==2 ? $licence : '',

		'bu_name'		=>	"$bu_name",
		'bu_nickname'	=>	"$bu_nickname",
		'bu_address'	=>	"$bu_address",
		'bu_suburb'		=>	"$bu_suburb",
		'bu_state'		=>	"$bu_state",
		'bu_urlstring'  =>  clean_url_name($bu_urlstring),
		'bu_phone'		=>	"$bu_phone",
		'bu_fax'		=>	"$bu_fax",
		'bu_procode'	=>	"$bu_procode",
		'bu_website'	=>	"$bu_website",
		'bu_email'		=>	"$bu_user",
		'bu_postcode'	=>	"$bu_postcode",
		'address_hide'	=>	"$address_hide",
		'phone_hide'	=>	"$phone_hide",
		'bu_area'		=>	"$bu_area",
		'CustomerType'	=>	"seller",
		'contact'		=>	"$contact",
		'launch_date'	=>	$datenow,
		'PayDate'		=>	$datenow,
		'renewalDate'	=>  $expiringDate,
                'gender'    =>  intval($gender)
		);

                $isUp = ( $arrSetting['attribute'] == 3 and $arrSetting['subAttrib'] ==3) ? true : false;
		$strCondition = " where StoreID='$StoreID'";

		if ($this->dbcon-> update_record($this->table."bu_detail", $arrSetting, $strCondition)) {

			$url_store_name = clean_url_name($bu_urlstring);
			$arrSetting = array(
			'user'			=>	$bu_user,
			'password'		=>	$bu_password,
			'level'			=>	"1",
			'store_name'	=>	$url_store_name,
			'attribute'		=>	"$attribute"
			);

			if($this->dbcon-> update_record($this->table."login", $arrSetting, $strCondition)) {
				$booleanResult	= true;
				//insert default template
				$arrSetting = array(
				'TemplateName'	=>	$attribute == 0 ? 'tmp-n-e' : ($attribute == 1 ? 'estate-c' : ($attribute == 2 ? 'auto-c' : ($subattr3 == 3 ? 'job-c' : 'job-c'))),
				'TemplateBGColor'	=>	'33',
				'StoreID'		=>	"$StoreID",
				'tpl_type'		=>	($attribute+1)
				);
				$this->dbcon-> insert_record($this->table."template_details", $arrSetting);

				//send email
				if ($hasEmail) {
					$arrParams		=	$arrUserInfo;
					$arrParams['display']			=	'upgrade';
					$arrParams['To']				=	$bu_user;
					$arrParams['Subject']			=	'Welcome To SOC Exchange' .($attribute == 1?' - Real Estate':($attribute == 2?' - Auto':($attribute == 3?' - Careers':($attribute == 5?' - Food & Wine':' - Buy & Sell'))));
					$arrParams['seller_name']		=	$bu_name;
					$arrParams['stateName']			=	getStateByName($bu_state);
					$arrParams['expiringDate']		=	$isUp ? 'No expire date' : date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$expiringDate);

					$objEmail	=	new emailClass();
					if($objEmail -> send($arrParams,'email_userinfo.tpl')){
						$adminEmail = new emailClass();		
						$arrParams['To']	=	'patrick@infinitytechnologies.com.au';						//to admin
						$arrParams['BCC']	=	'james.w@infinitytechnologies.com.hk';
						$arrParams['type']	=	'Upgrade';
						$arrParams['cardNumber']	=	$this -> displayCardNumber($arrParams['cardNumber']);
                                                $arrParams['email_regards'] = 'SOC Exchange Australia';
						$adminEmail -> send($arrParams,'email_toadmin.tpl', false);
						unset($adminEmail);
					}
					unset($objEmail);
				}

			}
		}

		return $booleanResult;
	}

	function displayCardNumber($cardNumber){
		$strResult	=	'';

		if (!empty($cardNumber)) {
			$len	=	strlen($cardNumber);
			$strResult	=	str_repeat('*',$len - 4) . substr($cardNumber, $len-4);
		}

		return $strResult;
	}

	/**
	 * payment active time
	 *
	 * @param int $money     default 0 
	 * @param int $intOldDate  default 0
	 * @return intTime
	 */
	private function __paymentActiveTime($money = 0, $intOldDate = 0){
		$intResult	=	0;

		if ($intOldDate > time()) {
			if ($money > 0 and $money < 10){
				$intResult	=	mktime(0, 0, 0, date("m",$intOldDate)+$money, date("d",$intOldDate),   date("Y",$intOldDate));
			}elseif ($money == 10) {
				$intResult	=	mktime(0, 0, 0, date("m",$intOldDate), date("d",$intOldDate),   date("Y",$intOldDate)+1);
			}
		}else{
			if ($money > 0 and $money < 10){
				$intResult	=	mktime(0, 0, 0, date("m")+$money, date("d"),   date("Y"));
			}elseif ($money == 10) {
				$intResult	=	mktime(0, 0, 0, date("m"), date("d"),   date("Y")+1);
			}
		}

		return $intResult;
	}

	private function __displayResults($webpayRef){
		$booleanResult	=	false;

		// Check the error field
		$error = get( $webpayRef, "ERROR" );
		if($error){
			if ($error == 'INVALID CHARACTERS IN CARDDATA') {
				$this -> addMessage("Your credit card number is invalid. Please try again.");
			}else{
				$this -> addMessage( $this->__outTableRow("Error message", $error));
			}
		}else{
			$txnRef = get( $webpayRef, "TXNREFERENCE");
			$result = get( $webpayRef, "RESULT");
			$authCode = get( $webpayRef, "AUTHCODE");
			$responseText = get( $webpayRef, "RESPONSETEXT");
			$responseCode = get( $webpayRef, "RESPONSECODE");

			if (($this -> __approvedTransaction($responseCode))) {
				$this -> strMsg .= "Your payment has been processed successfully.";
				$booleanResult	= true;
			} else {
				$this -> strMsg .= "Your payment was not completed. Please try again or contact us. (error code: ".$responseCode.")";
			}
		}

		return $booleanResult;
	}

	private function __approvedTransaction($responseCode) {
		// Check the returned response Code against the list of known Approved Response Codes
		if ($responseCode){
			$ARRAY_SIZE = 3;
			$listOfApprovedResponseCodes[0] = "00"; //Transaction Approved
			$listOfApprovedResponseCodes[1] = "08"; //Approved Signature
			$listOfApprovedResponseCodes[2] = "77"; //Approved Signature
			for($i = 0; $i < $ARRAY_SIZE; $i++) {
				if( $responseCode == $listOfApprovedResponseCodes[$i]){
					//Found it. Return true to indicate approved transaction.
					return true;
				}
			}
		}
		return false;
	}

	private function __doStatusCheck ($webpayRef) {
		$txnRef = get( $webpayRef, "TXNREFERENCE");

		if ($txnRef) {
			//We have a transaction reference so attempt a status transaction.
			put( $webpayRef, "TRANSACTIONTYPE", "STATUS" );
			if (executeTransaction( $webpayRef ) == "true"){
				return true;
			}else{
				return false;
			}
		} else {
			// No txnref number so we can not do a status check.
			return false;
		}
	}

	// Simple helper function for displaying table rows
	private function __outTableRow($name, $value){
		return  "$name: $value\n";
	}

	function addMessage($msg){
		$this -> strMsg .= $msg ."<br>\n";
	}

}

//------------------------------------------------------------
// xajax function
//------------------------------------------------------------

function paymentSubmit($objHTML){
	$objResponse = new xajaxResponse();
	$_SESSION['pageParam_tmp_session'] = $_SESSION['pageParam'];
	$objPayIPG 	= &$GLOBALS['objPayIPG'];
	$objResponse -> assign('mainSubmit','src','/skin/red/images/buttons/gray-submit.gif');

	$messages		=	'';
	$booleanResult =	$objPayIPG -> userPayment($objHTML);
	$messages	=	$objPayIPG -> strMsg;
	if ($booleanResult) {
		$objResponse -> assign("ajaxmessage",'innerHTML','');
		$objResponse -> alert($messages);
		$_SESSION['pageParam'] = $_SESSION['pageParam_tmp_session'];
                $_SESSION['pageParam']['pay_fail']=true;
		unset($_SESSION['pageParam_tmp_session']);
		$objResponse -> script('window.location.href = "'. $objPayIPG -> jumpPath .'";');
	}else{
		$objResponse -> assign("ajaxmessage",'innerHTML',$messages);
		$objResponse -> assign('mainSubmit','disabled','');
		$objResponse -> assign('mainSubmit','src','/skin/red/images/buttons/or-submit.gif');
		$objResponse -> assign('ipgback','src','/skin/red/images/buttons/or-back.gif');
		$objResponse -> assign('ipgback','disabled','');
		$_SESSION['pageParam'] = $_SESSION['pageParam_tmp_session'];
                $_SESSION['pageParam']['pay_fail']=false;
		unset($_SESSION['pageParam_tmp_session']);
	}

	unset($objPayIPG);

	return $objResponse;
}

?>
