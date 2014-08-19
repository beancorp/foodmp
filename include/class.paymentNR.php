<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PaymentNR extends common
{

    private $_lang;
    private $_msg = '';
    private $_dbcon 	= 	null;
    private $_table	=	'';
    private $_jumpPath = '';
    private $_regStoreID = 0;
    

    /**
     * construct
     */
    public function  __construct()
    {
        $this ->_lang	= &$GLOBALS['_LANG'];
        $this ->_dbcon  = &$GLOBALS['dbcon'];
        $this ->_table	= &$GLOBALS['table'];
        $this->_jumpPath = SOC_HTTPS_HOST;
    }

    /**
     * get payment infomation
     */
    public function getPaymentInfo()
    {
        
        $array = array(
            'paypal_url'    =>  SOC_HTTPS_HOST.'soc.php?act=nr'
        );
        return $array;
    }

    /**
     * get payment params
     */
    public function getPaymentParams()
    {
        unset($_SESSION['paymentUserInfo']);
        if($arrParams==""){
                $_SESSION['paymentUserInfo']	=	$this -> setFormInuptVar();
        }else{
                $_SESSION['paymentUserInfo']	=	$arrParams;
        }
        $array = array(
            'cardNumber'    =>  NR_PAYMENT_DEBUG_MODE ? '4111111111111111' : '',
            'amount'        =>  NR_PAYMENT_DEBUG_MODE ? '120.00' : number_format($_REQUEST['amount'],2),
            'expiryMonth'   => getExpMonth(),
            'expiryYear'    => getExpYear(),
            'email'         => $_SESSION['paymentUserInfo']['bu_user']
        );

        return $array;
    }

    /**
     * get payment params
     */
    public function getPaymentParamsFee($amount)
    {
    	$socObj = $GLOBALS['socObj'];
    	$store_info = $socObj->getStoreInfo($_SESSION['StoreID']);
        $array = array(
            'cardNumber'    =>  NR_PAYMENT_DEBUG_MODE ? '4111111111111111' : '',
            'amount'        =>  number_format($amount,2),
            'expiryMonth'   => getExpMonth(),
            'expiryYear'    => getExpYear(),
            'email'         => $store_info['bu_email']
        );

        return $array;
    }

    /**
     * get order type:  REG/KEEP/UP
     */
    public function getOrderType()
    {
        //register
        if(!isset($_SESSION['StoreID']) or '' == trim($_SESSION['StoreID']) or !isset($_SESSION['attribute'])) {
            return 'REG';
        }

        //up or keep
        if(isset($_SESSION['StoreID']) and '' != trim($_SESSION['StoreID'])) {
            if("4" == $_SESSION['attribute'] or ('3' == $_SESSION['attribute'] and '3' == $_SESSION['subAttrib'])) {
                return 'UP';
            }
        }

        //else keep
        return 'KEEP';
    }



    /**
     * payment to NR
     * @param <array> user data for payment
     */
    public function payNR($data = array())
    {
        //comment:AU - Market | Register/Renew/Upgrade | Name:xxx
        $marketName = $this->_getMarket();
//        file_put_contents('aaa_t.txt', $marketName);
        if (NR_PAYMENT_DEBUG_MODE) {
			return true;
		}
        $orderType = $this->getOrderType();
        if('REG' == $orderType) {
            $orderType = 'Register';
        }
        elseif('KEEP' == $orderType) {
            $orderType = 'Renew';
        }
        else {
            $orderType = 'Upgrade';
        }
        $comment = 'AU - ' . $marketName . ' | ' . $orderType . ' | Name:' . trim($data['txtname']);
        $url = 'https://4tknox.au.com/cgi-bin/themerchant.au.com/ecom/external2.pl?LOGIN=';
        $url .= NR_PAYMENT_USER_ID . '/' . NR_PAYMENT_PWD ;
        $url .= '&COMMAND=purchase&AMOUNT=120.00&CCNUM=' . trim($data['cardNumber']) ;
        $url .= '&CCEXP=' . str_pad(trim($data['expiryMonth']), 2, '0', STR_PAD_LEFT) . '/' . substr($data['expiryYear'], -2, 2);
        $url .= '&CCV=' . trim($data['cvc2']) . '&COMMENT=' . urlencode($comment);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $responseBody = curl_exec($ch);
        if(strstr($responseBody, 'result=1')) {
            return true;
        }
        preg_match('/response_text=([^\n\r]*)/', $responseBody, $info);
        if($info[1]) {
            $this->_msg = $info[1];
            preg_match('/response_code=([^\n\r]*)/', $responseBody, $code);
            if($code[0]) {
                $this->_msg = trim($this->_msg, '.') . '. (' . $code[0] . ')';
            }
        }
        else {
            $this->_msg = preg_replace(array('/\n/','/\r/'),' ',$responseBody);
        }
        return false;
    }

    /**
     * payment to NR
     * @param <array> user data for payment
     */
    public function payFeeNR($data = array())
    {
        //comment:AU - Market | Register/Renew/Upgrade | Name:xxx
        $marketName = $this->_getMarket();
//        file_put_contents('aaa_t.txt', $marketName);
        if (NR_PAYMENT_DEBUG_MODE) {
			return true;
		}
        $comment = 'AU - ' . $marketName . ' | ' . $data['orderType'] . ' | Name:' . trim($data['txtname']);
        $url = 'https://4tknox.au.com/cgi-bin/themerchant.au.com/ecom/external2.pl?LOGIN=';
        $url .= NR_PAYMENT_USER_ID . '/' . NR_PAYMENT_PWD ;
        $url .= '&COMMAND=purchase&AMOUNT='.$data['amount'].'&CCNUM=' . trim($data['cardNumber']) ;
        $url .= '&CCEXP=' . str_pad(trim($data['expiryMonth']), 2, '0', STR_PAD_LEFT) . '/' . substr($data['expiryYear'], -2, 2);
        $url .= '&CCV=' . trim($data['cvc2']) . '&COMMENT=' . urlencode($comment);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $responseBody = curl_exec($ch);
        if(strstr($responseBody, 'result=1')) {
            return true;
        }
        preg_match('/response_text=([^\n\r]*)/', $responseBody, $info);
        if($info[1]) {
            $this->_msg = $info[1];
            preg_match('/response_code=([^\n\r]*)/', $responseBody, $code);
            if($code[0]) {
                $this->_msg = trim($this->_msg, '.') . '. (' . $code[0] . ')';
            }
        }
        else {
            $this->_msg = preg_replace(array('/\n/','/\r/'),' ',$responseBody);
        }
        return false;
    }

    /**
     * @desc oparetion msg
     * @return <string>
     */
    public function msg()
    {
        return $this->_msg;
    }



    /**
     * @desc register, keep, up     enter
     */
    public function userProcess($userParam=array())
    {

        $arrUserInfo	=	&$_SESSION['paymentUserInfo'];
        $_titels		=	"t2.StoreID, t2.bu_name, t2.bu_username, t2.bu_email, t2.renewalDate, t2.bu_repid, t2.CustomerType, t2.bu_nickname, t2.bu_name, t2.bu_address, t2.bu_state, t2.bu_suburb, t2.bu_postcode, t2.bu_area, t2.bu_phone, t1.password";
        $_query		=	"select $_titels from ".$this->_table."login as t1 ".
        "left join ".$this->_table."bu_detail as t2 on t1.StoreID=t2.StoreID ".
        "where ((t1.attribute!=5 AND t1.`user`='".$arrUserInfo['bu_user']."') OR (t1.attribute=5 AND t1.username='".$arrUserInfo['bu_username']."')) and t1.attribute='". ($arrUserInfo['attribute'] ? $arrUserInfo['attribute'] : $_SESSION['attribute']) ."'";
        $this->_dbcon->execute_query($_query);
        $arrUser 	=	$this->_dbcon->fetch_records(true);
        if (is_array($arrUser)) {
            $arrUser	=	$arrUser[0];
        }
        
        $socobj = new socClass();
        
        $nowDateStamp = time();
        
        $orderType = $this->getOrderType();
        $sendEmail = !NR_PAYMENT_DEBUG_MODE;
        $sendEmail = true;
        //register
        if('REG' == $orderType) {
            $userInfo = array_merge($userParam, $arrUserInfo);
            if($this->_register($userInfo, $sendEmail)) {
                $socobj->setrefEarn($userInfo['referrer'],$userInfo['amount'],$userInfo['bu_nickname']);
                $this->_addOrderBilling($this->_regStoreID, 'reigstration');            //record order
                $this ->_jumpPath = SOC_HTTPS_HOST.'login_ipn.php?uname='.($userInfo['attribute'] == 5 ? $userInfo['bu_username'] : $userInfo['bu_user']).'&password='.$userInfo['bu_password'].'&user_type='.$userInfo['attribute']."&hascount=hascount&regsuc=".md5('regsuc');
                return true;
            }
            else {
                $this->_msg = 'error';
                return false;
            }
        }
        //keepup
        elseif('KEEP' == $orderType) {
            $userInfo = array_merge($arrUser,$arrUserInfo,$userParam);
            $userInfo['bu_name'] = $userInfo['bu_name'] ? $userInfo['bu_name'] : ($arrUserInfo['bu_name'] ? $arrUserInfo['bu_name'] : addslashes($arrUser['bu_name']));
            
            if($this->_keep($userInfo, $sendEmail)) {
                $socobj->setrefEarn($userInfo['referrer'],$userInfo['amount'],$userInfo['bu_nickname']);
                $this->_addOrderBilling($_SESSION['StoreID'], 'renew');         //record order
//                $this->_jumpPath = SOC_HTTPS_HOST . 'soc.php?cp=payreports';
                $this ->_jumpPath = SOC_HTTPS_HOST.'login_ipn.php?uname='.($userInfo['attribute'] == 5 ? $userInfo['bu_username'] : $userInfo['bu_user']).'&password='.$userInfo['password'].'&user_type='.$userInfo['attribute']."&hascount=hascount";
                return true;
            }
            else {
                $this->_msg = 'error';
                return false;
            }
            
        }
        // renew
        else {
            $userInfo['StoreID']	=	$_SESSION['StoreID'];
            $userInfo['bu_email']	=	$_SESSION['email'];
            $userInfo = array_merge($userParam, $arrUserInfo);
            $userInfo['bu_name'] = $userInfo['bu_name'] ? $userInfo['bu_name'] : ($arrUserInfo['bu_name'] ? $arrUserInfo['bu_name'] : addslashes($arrUser['bu_name']));
            if($this->_up($userInfo, $sendEmail)) {
                $socobj->setrefEarn($userInfo['referrer'],$userInfo['amount'],$userInfo['bu_nickname']);
                $this->_addOrderBilling($_SESSION['StoreID'], 'upgrade');         //record order
                $arrUserDB = $this->_getUserInfoByStoreID($_SESSION['StoreID']);                
                $this ->_jumpPath = SOC_HTTPS_HOST.'login_ipn.php?uname='.($userInfo['attribute'] == 5 ? $userInfo['bu_username'] : $userInfo['bu_user']).'&password='.$userInfo['password'].'&user_type='.$userInfo['attribute']."&hascount=hascount";
                return true;
            }
        }
    }


    /**
     * @desc payment, renew
     */
    public function feeProcess($data=array())
    {
    	include_once(SOC_INCLUDE_PATH.'/class.socstore.php');
		$query = "select count(*) as num from {$this->_table}order_reviewref where ref_id='{$data['ref_id']}' and p_status='paid'";
		$result = $this->_dbcon->execute_query($query);
		$res = $this->_dbcon->fetch_records(true);	
		if($res[0]['num']<1){	
			$arrSetting = array(
				'p_status'	=> 'paid',
				'paid_date' => time() 
			);
			$this->_dbcon->update_record($this->_table.'order_reviewref',$arrSetting,"where ref_id='{$data['ref_id']}'");
			
			$sql = "SELECT * FROM {$this->_table}order_reviewref WHERE ref_id='{$data['ref_id']}' AND p_status='paid'";
			$info = $this->_dbcon->getOne($sql);
			
			$pid_ary = $info['pids'] ? explode(',', $info['pids']) : array();
			$socstoreObj = new socstoreClass();
			$data['cardNumber']	=	$this ->_displayCardNumber($data['cardNumber']);
			$res = $socstoreObj->productActive($info['StoreID'], $info['product_feetype'], $pid_ary, $info['month'], true, $data);
			$flag	=	$res ? "Payment is successful." : "Payment is failed.";
			$this ->_jumpPath = SOC_HTTPS_HOST."soc.php?act=signon&step=4&msg=".$flag;
            return true;
		} else {
			$this->_msg = 'No order to pay.';
            return false;
		}
    }

    /**
     * @desc user register
     */
    private function _register($arrUserInfo=array(), $sendEmail=true)
    {
        $booleanResult	=	false;

        extract($arrUserInfo);
        $datenow	=	time();
        $expiringDate	=	$this ->_getActiveRenewDate($amount);
        if($expiringDate == 0){
                $expiringDate = time();
        }
		if(5 == $attribute) {
			include_once(SOC_INCLUDE_PATH . '/class.FoodWine.php');
			$fw_type = getFoodWineType($subattr5);
			$tpl_fw = $fw_type == 'food' ? 'foodwine-a' : 'foodwine-d';
        }
        $arrSetting = array(
                    'attribute'		=>	"$attribute",
                    'subAttrib'		=>	$attribute == 1 ? $subattr1 : ($attribute == 2 ? $subattr2 : ($attribute == 3 ? $subattr3 : ($attribute == 5 ? $subattr5 : 0))),
                    'mobile'		=>	"$mobile",
                    'licence'		=>	$attribute == 2 && $subattr1==2 ? $licence : '',

                    'bu_name'		=>	"$bu_name",
                    'bu_nickname'	=>	"$bu_nickname",
                    'bu_address'	=>	"$bu_address",
                    'bu_suburb'		=>	"$bu_suburb",
                    'bu_state'		=>	"$bu_state",
                    'bu_phone'		=>	"$bu_phone",
                    'bu_fax'		=>	"$bu_fax",
                    'bu_procode'	=>	"$bu_procode",
                    'bu_urlstring'	=>	clean_url_name($bu_urlstring),
                    'bu_website'	=>	"$bu_website",
                    'bu_email'		=>	"$bu_user",
                    'bu_username'	=>	$attribute == 5 ? "$bu_username" : '',
                    'bu_cuisine'	=>	$attribute == 5 ? "$bu_cuisine" : '',
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
        
        if ($this->_dbcon-> insert_record($this->_table."bu_detail", $arrSetting)) {
            $StoreID = $this->_dbcon->lastInsertId();
            $this->_regStoreID = $StoreID;
            unset($arrSetting);
            
			if ($referrer) {
				include_once(SOC_INCLUDE_PATH . '/class.socstore.php');
        		$stostoreObj = new socstoreClass();
                $stostoreObj->updateStoreRef('', $referrer);	
        	}
	            	
            $url_store_name = clean_url_name($bu_urlstring);
            $arrSetting = array(
                    'StoreID'		=>	"$StoreID",
                    'user'			=>	"$bu_user",
                    'username'		=>	$attribute == 5 ? "$bu_username" : '',
                    'password'		=>	"$bu_password",
                    'level'			=>	"1",
                    'store_name'	=>	$url_store_name,
                    'attribute'		=>  "$attribute"
            );
            if($this->_dbcon-> insert_record($this->_table."login", $arrSetting)) {
                $booleanResult	= true;
                //insert default template
                $arrSetting = array(
                'TemplateName'	=>	$attribute == 0 ? 'tmp-n-a' : ($attribute == 1 ? 'estate-c' : ($attribute == 2 ? 'auto-c' : ($attribute == 3 ? ($subattr3 == 3 ? 'job-c' : 'job-c') : ($attribute == 5 ? $tpl_fw : 'tmp-n-a')))),
                'TemplateBGColor'	=>	'33',
                'StoreID'		=>	"$StoreID",
                'tpl_type'		=>	($attribute+1)
                );
                
            	require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
	            $referrer = new Referrer();
				$referrer->addReferrerRecord('reg', $StoreID);
				
                if ($this->_dbcon-> insert_record($this->_table."template_details", $arrSetting)) {
                	// Modify by Haydn.H By 20120309 ========= Begin =========
					// facebook key save
					if($fb_id){
						$arrSetting = array(
							'StoreID'   =>  $StoreID,
							'fb_id'     =>  $fb_id,
							'attribute' =>  $attribute,
						);
	
						$sql = "SELECT count(*) as num FROM {$this->_table}facebook where fb_id='$fb_id' and attribute='$attribute'";
						
						$this->_dbcon->execute_query($sql);
						$totalNum	=	$this->_dbcon->fetch_records();
						$totalNum	= 	$totalNum[0]['num'];
						if(! $totalNum){
							$boolResult= $this->_dbcon-> insert_record($this->_table."facebook", $arrSetting);
						}else{
							$boolResult=$this->_dbcon->update_record($this->_table."facebook", $arrSetting, "where fb_id='$fb_id' and attribute='$attribute'");
						}
						if ($boolResult) {
							$msg = 'Bundled facebook successfully. ';
						}else {
							$msg = 'Bundled facebook failed. ';
						}
					}
					// Modify by Haydn.H By 20120309 ========= End =========
                }

                //send email
                if ($sendEmail) {
                    $arrParams		=	$arrUserInfo;
                    $arrParams['display']			=	'regedit';
                    $arrParams['To']				=	$bu_user;
                    $arrParams['Subject']			=	'Welcome To SOC Exchange' .($attribute == 1?' - Real Estate':($attribute == 2?' - Auto':($attribute == 3?' - Careers':($attribute == 5?' - Food & Wine':' - Buy & Sell'))));
                    $arrParams['seller_name']		=	Input::StripString($bu_name);
                    $arrParams['bu_name']			=	Input::StripString($bu_name);
                    $arrParams['bu_nickname']		=	Input::StripString($bu_nickname);
                    $arrParams['subAttrib']			=	$attribute == 1 ? $subattr1 : ($attribute == 2 ? $subattr2 : ($attribute == 3 ? $subattr3 : 0));
                    $arrParams['stateName']			=	getStateByName($bu_state);
                    $arrParams['expiringDate']		=	date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$expiringDate);
                    $objEmail	=	new emailClass();
                    $res = false;
					if ($arrParams['attribute'] == 0 || $arrParams['attribute'] == 5 || ($arrParams['attribute'] == 3 && $arrParams['subAttrib'] == 3) || $arrParams['display'] != 'regedit' || $arrParams['bu_procode'] != '') {
						$res = $objEmail -> send($arrParams,'email_userinfo.tpl');
					}
                    if($res){
                        $adminEmail = new emailClass();
                        $arrParams['To']	=	'patrick@infinitytechnologies.com.au';
                        $arrParams['BCC']	=	'james.w@infinitytechnologies.com.hk';
                        $arrParams['type']	=	'Seller Registration';
                        $arrParams['cardNumber']	=	$this ->_displayCardNumber($arrParams['cardNumber']);
                        $arrParams['bu_email']      =       $bu_user;
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
     * @desc user keep
     */
    private function _keep($arrUserInfo=array(), $sendEmail=true)
    {
        $booleanResult	=	false;

        extract($arrUserInfo);

        $datenow	=	time();
        $expiringDate	=	$this ->_getActiveRenewDate($amount, $renewalDate);
        $arrSetting = array(
                'PayDate'	=>	$datenow,
                'renewalDate'	=>	$expiringDate,
                 'bu_urlstring'	=>	clean_url_name($bu_urlstring),
                'bu_website'	=>	"$bu_website",
                'bu_name'       =>  "$bu_name"
        );
        $_SESSION['attribute'] == 3 && $_SESSION['subAttrib'] == 3 ? $arrSetting['subAttrib']=2 : '' ;

        $strCondition	=	 "where  StoreID=".$_SESSION['StoreID']."";
        if($this->_dbcon-> update_record($this->_table."bu_detail", $arrSetting, $strCondition)) {
            $this->_dbcon->update_record($this->_table."login",array('store_name'=>clean_url_name($bu_urlstring)),$strCondition);
            $booleanResult	=	true;
            $_SESSION['attribute'] == 3 && $_SESSION['subAttrib'] == 3 ? $_SESSION['subAttrib']=2 : '' ;

            //send email
            if ($sendEmail) {
                $arrParams		=	$arrUserInfo;
                $arrParams['display']			=	'keepup';
                $arrParams['To']				=	$bu_email;
                $arrParams['Subject']			=	'Welcome To SOC Exchange' .($_SESSION['attribute'] == 1?' - Real Estate':($_SESSION['attribute'] == 2?' - Auto':($_SESSION['attribute'] == 3?' - Careers':($_SESSION['attribute'] == 5?' - Food & Wine':' - Buy & Sell'))));
                $arrParams['seller_name']		=	Input::StripString($bu_name);
                $arrParams['bu_name']			=	Input::StripString($bu_name);
                $arrParams['bu_nickname']		=	Input::StripString($bu_nickname);
                $arrParams['stateName']			=	getStateByName($bu_state);
                $arrParams['expiringDate']		=	date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$expiringDate);
                $objEmail	=	new emailClass();
                if($objEmail -> send($arrParams,'email_userinfo.tpl')){
                    $adminEmail = new emailClass();
                    $arrParams['To']	=	'patrick@infinitytechnologies.com.au';
                    $arrParams['BCC']	=	'james.w@infinitytechnologies.com.hk';
                    $arrParams['type']	=	'Renew';
                    $arrParams['bu_email']      =       $bu_email;
                    $arrParams['cardNumber']	=	$this ->_displayCardNumber($arrParams['cardNumber']);
                    $adminEmail -> send($arrParams,'email_toadmin.tpl', NR_PAYMENT_DEBUG_MODE);
                    unset($adminEmail);
                }
                unset($objEmail);
            }
        }

        return $booleanResult;
    }

    /**
     * @desc user up
     */
    private function _up($arrUserInfo=array(), $sendEmail=true)
    {
        $booleanResult	=	false;

        extract($arrUserInfo);
        $datenow	=	time();
        $expiringDate	=	$this ->_getActiveRenewDate($amount);

        $arrSetting = array(
                'attribute'		=>	"$attribute",
                'subAttrib'		=>	$attribute == 1 ? $subattr1 : ($attribute == 2 ? $subattr2 : ($attribute == 3 ? $subattr3 : ($attribute == 5 ? $subattr5 : 0))),
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

        $strCondition = " where StoreID='" . $_SESSION['StoreID'] . "'";
        if ($this->_dbcon-> update_record($this->_table."bu_detail", $arrSetting, $strCondition)) {
            $url_store_name = clean_url_name($bu_urlstring);
            $arrSetting = array(
            'user'			=>	$bu_user,
            'password'		=>	$bu_password,
            'level'			=>	"1",
            'store_name'	=>	$url_store_name,
            'attribute'		=>	"$attribute"
            );
            $PWD = $bu_password;
            if(3 == $_SESSION['attribute']) {

                unset($arrSetting['password']);
            }
            if(5 == $_SESSION['attribute']) {
				include_once(SOC_INCLUDE_PATH . '/class.FoodWine.php');
				$fw_type = getFoodWineType($subattr5);
				$tpl_fw = $fw_type == 'food' ? 'foodwine-a' : 'foodwine-d';
            }
            if($this->_dbcon-> update_record($this->_table."login", $arrSetting, $strCondition)) {
                $booleanResult	= true;
                //insert default template
                $arrSetting = array(
                'TemplateName'	=>	$attribute == 0 ? 'tmp-n-e' : ($attribute == 1 ? 'estate-c' : ($attribute == 2 ? 'auto-c' : ($attribute == 3 ? ($subattr3 == 3 ? 'job-c' : 'job-c') : ($attribute == 5 ? $tpl_fw : 'tmp-n-e')))),
                'TemplateBGColor'	=>	'33',
                'StoreID'		=>	"$StoreID",
                'tpl_type'		=>	($attribute+1)
                );
                $this->_dbcon-> insert_record($this->_table."template_details", $arrSetting);

                //send email
                if ($sendEmail) {
                    $arrParams		=	$arrUserInfo;
                    $arrParams['bu_password'] = $PWD;
                    $arrParams['display']			=	'upgrade';
                    $arrParams['To']				=	$bu_user;
                    $arrParams['Subject']			=	'Welcome To SOC Exchange' .($attribute == 1?' - Real Estate':($attribute == 2?' - Auto':($attribute == 3?' - Careers':($attribute == 5?' - Food & Wine':' - Buy & Sell'))));
                    $arrParams['seller_name']		=	Input::StripString($bu_name);
                    $arrParams['bu_name']			=	Input::StripString($bu_name);
                    $arrParams['bu_nickname']		=	Input::StripString($bu_nickname);
                    $arrParams['stateName']			=	getStateByName($bu_state);
                    $arrParams['expiringDate']		=	date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$expiringDate);

                    $objEmail	=	new emailClass();
                    if($objEmail -> send($arrParams,'email_userinfo.tpl')){
                        $adminEmail = new emailClass();
                        $arrParams['To']	=	'patrick@infinitytechnologies.com.au';
                        $arrParams['BCC']	=	'james.w@infinitytechnologies.com.hk';
                        $arrParams['type']	=	'Upgrade';
                        $arrParams['bu_email']      =       $bu_user;
                        $arrParams['cardNumber']	=	$this ->_displayCardNumber($arrParams['cardNumber']);
                        $adminEmail -> send($arrParams,'email_toadmin.tpl', NR_PAYMENT_DEBUG_MODE);
                        unset($adminEmail);
                    }
                    unset($objEmail);
                }

            }
        }

        return $booleanResult;
    }



    /**
     *
     * @param <float> $money payment meney
     * @param <int> $intOldDate is old date
     * @return <int> date stamp 
     */
    private function _getActiveRenewDate($money = 0, $intOldDate = 0){
    	$money = 10;
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


    /**
     *
     * @param <string> $cardNumber
     * @return string
     */
    private function _displayCardNumber($cardNumber){
        $strResult	=	'';

        if (!empty($cardNumber)) {
            $len	=	strlen($cardNumber);
            $strResult	=	str_repeat('*',$len - 4) . substr($cardNumber, $len-4);
        }

        return $strResult;
    }


    /**
     * add data to order_reviewref
     */
    private function _addOrderBilling($storeID, $type='renew')
    {
        $nowDateStamp = time();
        $data = array(
            'buyer_id'  => $storeID,
            'StoreID'   => $storeID,
            'order_date'    =>  $nowDateStamp,
            'paid_date'     =>  $nowDateStamp,
            'type'      =>  $type,
            'p_status'  =>  'paid',
            'description'   =>  'NR',
            'amount'     =>  "120.00",
            'month'     =>  '12'
        );
        if($this->_dbcon->insert_record($this->_table.'order_reviewref', $data)) {
            return $this->_dbcon->lastInsertId();
        }
        return false;
    }


    /**
     * @desc get jump path
     * @return <string>
     */
    public function getJumpPath()
    {
        return $this->_jumpPath;
    }

    /**
     * @desc insert log record
     * @return null
     */
    private function _log($storeID=0, $email='', $type='KEEP', $log='')
    {
        $array = array(
            ''
        );
    }



    private function _getUserInfoByStoreID($stroreID=0)
    {
        $this->_dbcon->execute_query('select * from '.$this->_table . 'login where StoreID='.$stroreID);
        $arr = $this->_dbcon->fetch_records(true);
        return $arr[0];
    }

    private function _getMarket()
    {
        
        $marketName = '';
        if(isset($_SESSION['attribute'])) {
            $attribute = $_SESSION['attribute'];
            if('0' == $attribute) {
                return 'Buy & Seller';
            }
            elseif('1' == $attribute) {
                return  'Real Estate';
            }
            elseif('2' == $attribute) {
                return 'Auto';
            }
            elseif('3' == $attribute) {
                return  'Job';
            }
            elseif('5' == $attribute) {
                return  'Food & Wine';
            }
        }

        $userInfo = &$_SESSION['paymentUserInfo'];
        $attribute = $userInfo['attribute'];
        if('0' == $attribute) {
            return 'Buy & Seller';
        }
        elseif('1' == $attribute) {
            return  'Real Estate';
        }
        elseif('2' == $attribute) {
            return 'Auto';
        }
        elseif('3' == $attribute) {
            return  'Job';
        }
        elseif('5' == $attribute) {
            return  'Food & Wine';
        }


        
        
    }
}
?>
