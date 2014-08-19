<?php

/**
 * $Id class.socstore.php jacky.zhou <at> Wed Sep 03 09:54:05 CST 200809:54:05 $
 *
 * logic process during store set
 * @author jacky.zhou
 * @package buyblitz
 * @subpackage include
 */

class socstoreClass extends socClass {


	/**
	 * get step operate name, It use setting by store at count step of every one.
	 *
	 * @return array
	 */
	function getStartSellingOperate()
	{
		$strResul	=	'';
		$temp		=	$_REQUEST["step"];
        $intNextStep = NULL;
		if ((empty($temp) || $temp == "1") && !($_SESSION['attribute']==3 && $_SESSION['subAttrib']==3)) {
			$strResul	=	'';
			$intNextStep	=	2;
		}elseif (empty($temp) || $temp == "1" ) {
			$strResul	=	'';
			$intNextStep	=	4;
		}elseif ($temp == 2){
			$strResul	=	'design_info';
			$intNextStep	=	$temp+1;
		}elseif ($temp == 3){
			$strResul	=	'design_theme';
			$intNextStep	=	$temp+1;
		}elseif ($temp == 4){
			$strResul	=	'product';
			$intNextStep	=	$temp+1;
		}elseif ($temp > 4){
			$strResul	=	'store';
		}else{ 
			$strResul = $temp;
		}
        
		return array('name'=>$strResul,'nextStep'=>$intNextStep,'step'=>$temp);
	}

	function getStartsellingMenu ($stepOperate=array(), $isSub=false)
	{
		$lang	=	&$this -> lang;
		$steps	=	$_REQUEST['step'];
		$strResult = '';
		$notLogin	=	true;

		$arrReq = array(
		'finishActive' 	=> $steps == '5' ? '_active' : '' ,
		'storeActive' 	=> $steps == '1' || $steps == '' ? '_active' : '' ,
		'chooseActive' 	=> $steps == '2' ? '_active' : '' ,
		'colorActive' 	=> $steps == '3' ? '_active' : '' ,
		'imageActive' 	=> $steps == '4' ? '_active' : '' ,

		'detail'		=> '1',
		'designinfo'	=> '2',
		'designtheme'	=> '3',
		'product'		=> '4'
		);

		$shopID = $_SESSION['ShopID'];
		if ($shopID) {
			$notLogin	=	false;
			//print_r($arrReq);
		}elseif($steps > 1){
			$notLogin	=	false;
		}

		if ($_REQUEST['step']>1) {
			$strStepInfo = $this->replaceLangVar($lang['msgStep'][$stepOperate['name']],$_REQUEST["step"]);
		}elseif ($_REQUEST['step'] == '0'){
			$strStepInfo = $lang["msgStep"][$stepOperate['name']];
		}else {
			$strStepInfo = $this->replaceLangVar($lang["msgStep"][$stepOperate['name']],'1');
		}

		$this -> smarty -> assign('setMsg', $strStepInfo);
		$this -> smarty -> assign('notLogin', $notLogin);
		$this -> smarty -> assign('mainAttribute', empty($_SESSION['attribute'])? '0' :$_SESSION['attribute'] );
		$this -> smarty -> assign('req', $arrReq);
		if ($_SESSION['level']) {
			$this -> smarty -> assign('userlevel', 	$_SESSION['level']);
		}
		$strResult = $this->smarty->fetch($isSub ? '../startselling_menu.tpl' : 'startselling_menu.tpl');
		return $strResult;
	}

	function startSellingIPN() {
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}

		// post back to PayPal system to validate
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
                if ($this->paypal_info['paypal_mode'] == 0) {
                    $header .= "Host: www.sandbox.paypal.com\r\n";
                } else {
                    $header .= "Host: www.paypal.com\r\n";
                }
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

		if ($this->paypal_info['paypal_mode'] == 0) {
			$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
		} else {
			$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
		}

		if (!$fp) {
			// HTTP ERROR

		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {

					//$sellerInfo = explode('&',$_POST['custom']);
					/**
					 * modify by roy 20081211
					*/
					$this->paypalsubmit(true);
				}
				else if (strcmp ($res, "INVALID") == 0) {
					// log for manual investigation
				}
			}
			fclose ($fp);
		}
	}

	    function startSellingEway()
        {
            require('EwayPayment.php');
            $eway = new EwayPayment(  $_POST['ewayCustomerID'], $_POST['ewaygatewayURL'] );
			//$eway = new EwayPayment('87654321', 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp');
           
            $eway->setCustomerEmail( $_POST['ewayCustomerEmail'] );
           
            $eway->setCardHoldersName( $_POST['cardName'] );
            $eway->setCardNumber( $_POST['cardNumber'] );
            $eway->setCardExpiryMonth( $_POST['expiryMonth'] );
            $eway->setCardExpiryYear(  substr($_POST['expiryYear'],2) );
			
            $eway->setTotalAmount( (($_POST['payment_type'] == 1) ? 22000 : 40150) );
            $eway->setCVN(  $_POST['cvc2']  );
         
            if( $eway->doPayment() == EWAY_TRANSACTION_OK ) {
                exit(json_encode(array('status'=>'true','jumpPath'=>'soc.php?act=signon&step=ewaySubmit')));
            } else {
                $errMsg= "Error occurred (".$eway->getError()."): " . $eway->getErrorMessage();
                 exit(json_encode(array('status'=>'false', 'msg'=>preg_replace("/[\n\r]/","",$errMsg))));
            }
        }
        
        
        function ewaySubmit($ispaid=false)
        {
		
        if(!ereg('=',$_SESSION['custom'])){
			$tmpcustom = getbyidcustom($_SESSION['custom']);
		} else {
			$tmpcustom = $_SESSION['custom'];
		}

		$sellerInfo = explode('&',$tmpcustom);
		foreach($sellerInfo as $val){
			$value = explode('=',$val);
			if(!ereg('=',$_SESSION['custom'])){
				${$value[0]} = urldecode($value[1]);
			}else{
				${$value[0]} = $value[1];
			}
		}
		
		if (($own_website == 1) && ($subAttrib == 1 || $subAttrib == 7 || $subAttrib == 9)) {
			
			$renewal_date = strtotime('+5 years');
			
			$insert_store_query = "INSERT INTO aus_soc_bu_detail SET bu_name = '".$business_name."', 
									bu_cuisine = '".(empty($bu_cuisine) ? 0 : $bu_cuisine)."', bu_address = '".$bu_address."', bu_suburb = '".$bu_suburb."', 
									bu_state = '".$bu_state."', bu_phone = '".$bu_phone."', bu_fax = '".$bu_fax."', bu_website = '".$bu_website."',
									bu_email = '".$bu_user."', bu_postcode = '".$bu_postcode."', address_hide = '".$address_hide."', phone_hide = '".$phone_hide."', bu_area = '".$bu_area."',
									CustomerType = 'listing', contact = '".$contact."', mobile = '".$mobile."', attribute = '".$attribute."', subAttrib = '".$subAttrib."', renewalDate = '".$renewal_date."', launch_date = '".time()."'";  
									
			if ($this->dbcon->execute_query($insert_store_query)) {
				$storeID = $this->dbcon->lastInsertId();
				$insert_template = "INSERT INTO aus_soc_template_details SET TemplateName = 'foodwine-a', TemplateBGColor = '', TemplateStyle = '', TemplateFont = '31', StoreID = '".$storeID."', LogoImg = '', LogoDisplay = '', MainImg = '', bannerImg = '', tpl_type = '', WebsiteIconID = '', Alerts = ''";
				if ($this->dbcon->execute_query($insert_template)) {	
					$insert_login_query = "INSERT INTO aus_soc_login SET StoreID = '".$storeID."', user = '".$bu_user."', password = '".$bu_password."', level = 3, attribute = 5, suspend = 0, status = 1";				
					if ($this->dbcon->execute_query($insert_login_query)) {
						$subject = 'FoodMarketplace Website Listing';
						$message = 'You have successfully placed your link within the FoodMarketplace platform.<br /><br />Kind Regards,<br />FoodMarketplace.';
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: Website Listing <no-reply@'.EMAIL_DOMAIN.'>' . "\r\n";
						mail($bu_user, 'Website Listing', $message, $headers);
						$_SESSION['ListingID'] = $storeID;
						header('Location: soc.php?act=signon&step=upload_logo');
						exit();
					}
				}
			}
			
		} else {

			if ($StoreID > 0) {
				$hasCount = $this->activate($StoreID, 120, $tmpcustom,$paid);
				if ($type=="payment") {
									if($_SESSION['attribute']==3 and $_SESSION['subAttrib']==3) {$_SESSION['subAttrib']=2;}
					//header('Location:soc.php?cp=payreports'. "&hascount=".($hasCount?'hascount':''));
					header('Location: ' . SOC_HTTPS_HOST . 'soc.php?cp=sellerhome');
				} else {
					header('Location: login_ipn.php?uname='.($attribute==5 ? $bu_username : $bu_user).'&password='.$bu_password.'&user_type='.$attribute. "&hascount=".($hasCount?'hascount':''));
				}
			} else {
				$condition = "where ((attribute!=5 and user='".$bu_user."') or (attribute=5 and username='".$bu_username."')) and password='$bu_password' and attribute='$attribute'";
				if($this->dbcon->checkRecordExist($this->table.'login',$condition)){
					deltmpcustom($_POST['custom']);
					header('Location: login_ipn.php?uname='.($attribute==5 ? $bu_username : $bu_user).'&password='.$bu_password.'&user_type='.$attribute."&hascount=hascount".'&regsuc=1');
					exit();
				}
				
				$arrSetting = array(
				'bu_cuisine'	=>	empty($bu_cuisine) ? 0 : $bu_cuisine,
				'bu_username'	=>	$attribute==5 ? "$bu_username" : "",
				'bu_name'		=>	"$bu_name",
				'bu_nickname'	=>	"$bu_nickname",
				'gender'		=>	"$gender",
				'bu_address'	=>	"$bu_address",
				'bu_suburb'		=>	"$bu_suburb",
				'bu_state'		=>	"$bu_state",
				'bu_phone'		=>	"$bu_phone",
				'bu_fax'		=>	"$bu_fax",
				'bu_procode'	=>	"$bu_procode",
				'bu_website'	=>	"$bu_website",
				'bu_urlstring'	=>	clean_url_name($bu_urlstring),
				'bu_email'		=>	"$bu_user",
				'bu_postcode'	=>	"$bu_postcode",
				'address_hide'	=>	"$address_hide",
				'phone_hide'	=>	"$phone_hide",
				'bu_area'		=>	"$bu_area",
				'CustomerType'	=>	"seller",
				'contact'		=>	"$contact",
				'mobile'		=>  "$mobile",
				'attribute'		=>	"$attribute",
				'subAttrib'		=>	"$subAttrib",
				'licence'		=>  "$licence",
				'referrer'		=>  "$referrer",
				'launch_date'	=>	time(),
				'status'		=> 	1
				);

				$_query = "SELECT id FROM ".$this->table."login WHERE ((attribute!=5 and user='".$bu_user."') or (attribute=5 and username='".$bu_username."')) and attribute='$attribute'";
				$this->dbcon->execute_query($_query);
				$result = $this->dbcon->fetch_records(true);
				
				if ($result[0]['id'] > 0) {

					header('Location: login_ipn.php?uname='.($attribute==5 ? $bu_username : $bu_user).'&password='.$bu_password.'&user_type='.$attribute."&hascount=hascount".'&regsuc=1');

				} else {
					if ($this->dbcon-> insert_record($this->table."bu_detail", $arrSetting)) {
						$StoreID = $this->dbcon->lastInsertId();
						unset($arrSetting);
						
						if ($referrer) {
							$this->updateStoreRef('', $referrer);	
						}
						
						$url_store_name = clean_url_name($bu_urlstring);
						$arrSetting = array(
						'StoreID'		=>	"$StoreID",
						'user'			=>	"$bu_user",
						'username'		=>	$attribute == 5 ? "$bu_username" : "",
						'password'		=>	"$bu_password",
						'level'			=>	"1",
						'store_name'	=>	$url_store_name,
						'attribute'		=>  $attribute
						);

						if($this->dbcon-> insert_record($this->table."login", $arrSetting)) {
							//setting session var
							$UserID					=	$this->dbcon->lastInsertId();
							$_SESSION['UserID']		=	$UserID;
							$_SESSION['UserName']	=	$bu_name;
							$_SESSION['NickName']	=	$bu_nickname;
							$_SESSION['StoreID']	=	$StoreID;
							$_SESSION['ShopID']		=	$StoreID;
							$_SESSION['LOGIN']		=	"login";
							$_SESSION['level']		=	1;
							$_SESSION['TemplateName']	=	'tmp-n-a';
							$boolResult	= true;

							if ($attribute == 5) {
								$tpl_fw = 'foodwine-a';
								include_once(SOC_INCLUDE_PATH . '/class.FoodWine.php');
								$fw_type = getFoodWineType($subAttrib);
								$tpl_fw = $fw_type == 'food' ? 'foodwine-a' : 'foodwine-d';
							}

							//insert default template
							$arrSetting = array(
							'TemplateName'	=>	$attribute == 0 ? 'tmp-n-a' : ($attribute == 1 ? 'estate-c' : ($attribute == 2 ? 'auto-c' : ($attribute == 3 ? 'job-c' : ($attribute == 5 ? $tpl_fw : 'tmp-n-a')))),
							'StoreID'		=>	"$StoreID",
							'tpl_type'		=>	($attribute+1)
							);
							$this->dbcon-> insert_record($this->table."template_details", $arrSetting);

							if($ispaid){
								$socObj = new socClass();
								$socObj->setrefEarn($referrer,120,$bu_nickname);
							}
						}
						
						$arrrefSetting = array(
							'buyer_id'  => $StoreID,
							'StoreID'	=> $StoreID,
							'p_status'	=> 'paid',
							'paid_date' => time() );
						$this->dbcon->update_record($this->table.'order_reviewref',$arrrefSetting,"where ref_id='$ref_id'");
						$hasCount = $this->activate($StoreID, $_POST['mc_gross'],'',$paid,$ref_id);

						// Modify by Haydn.H By 20120306 ========= Begin =========
						// facebook key save
						if($fb_id){
							$arrSetting = array(
								'StoreID'   =>  $StoreID,
								'fb_id'     =>  $fb_id,
								'attribute' =>  $attribute
							);

							$sql = "SELECT count(*) as num FROM {$this->table}facebook where fb_id='$fb_id' and attribute='$attribute'";
							$this->dbcon->execute_query($sql);
							$totalNum	=	$this->dbcon->fetch_records();
							$totalNum	= 	$totalNum[0]['num'];
							if(! $totalNum){
								$boolResult= $this->dbcon-> insert_record($this->table."facebook", $arrSetting);
							}else{
								$boolResult=$this->dbcon->update_record($this->table."facebook", $arrSetting, "where fb_id='$fb_id'and attribute='$attribute'");
							}
							if ($boolResult) {
								$msg = 'Bundled facebook successfully. ';
							}else {
								$msg = 'Bundled facebook failed. ';
							}
						}
						// Modify by Haydn.H By 20120306 ========= End =========


						/**
						 * added by YangBall, 2011-07-05
						 * referrer new rule
						 */
						 if(isset($referrer) and '' != trim($referrer)) {
							 require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
							 $referrer = new Referrer();
		//                   $refStoreID = $referrer->getStoreIDByRefer($referrer);
		//                   if($refStoreID >0) {
								$referrer->addReferrerRecord('reg', $_SESSION['StoreID']);
		//                   }
						 }

						 //Paid Year IF have promotion code
						 if ($_POST['bu_procode'] != "") {
							if($this -> checkvalidpromot($_POST['bu_procode'], $attribute)){
								if ($attribute == 1 || $attribute == 2 || $attribute == 3) {
									$res = $this -> productActive($StoreID, 'year', array(), '', false);
								}								
								$bu_username = $attribute == 5 ? $_POST['bu_username'] : '';						
								$this -> updatepromotcode($_POST['bu_procode'] ,$_POST['bu_user'], $bu_username, $StoreID);
							}
						}

						//END-YangBall
						header('Location: login_ipn.php?uname='.($attribute == 5 ? $bu_username : $bu_user).'&password='.$bu_password.'&user_type='.$attribute. "&hascount=".($hasCount?'hascount':'').'&regsuc=1');
					}
				}
			}
		}
	}
     
        
        
	
	function paypalsubmit($ispaid=false){
		if(!ereg('=',$_POST['custom'])){
			$tmpcustom = getbyidcustom($_POST['custom']);
		}else{
			$tmpcustom = $_POST['custom'];
		}

		$sellerInfo = explode('&',$tmpcustom);
		foreach($sellerInfo as $val){
			$value = explode('=',$val);
			if(!ereg('=',$_POST['custom'])){
				${$value[0]} = urldecode($value[1]);
			}else{
				${$value[0]} = $value[1];
			}
		}

		if ($StoreID > 0) {
			$hasCount = $this->activate($StoreID, $_POST['mc_gross'], $tmpcustom,$paid);
			if ($type=="payment") {
                                if($_SESSION['attribute']==3 and $_SESSION['subAttrib']==3) {$_SESSION['subAttrib']=2;}
				//header('Location:soc.php?cp=payreports'. "&hascount=".($hasCount?'hascount':''));
                                header('Location: ' . SOC_HTTPS_HOST . 'soc.php?cp=sellerhome');
			} else {
				header('Location: login_ipn.php?uname='.($attribute==5 ? $bu_username : $bu_user).'&password='.$bu_password.'&user_type='.$attribute. "&hascount=".($hasCount?'hascount':''));
			}
		} else {
			$condition = "where ((attribute!=5 and user='".$bu_user."') or (attribute=5 and username='".$bu_username."')) and password='$bu_password' and attribute='$attribute'";
			if($this->dbcon->checkRecordExist($this->table.'login',$condition)){
				deltmpcustom($_POST['custom']);
				header('Location: login_ipn.php?uname='.($attribute==5 ? $bu_username : $bu_user).'&password='.$bu_password.'&user_type='.$attribute."&hascount=hascount".'&regsuc=1');
				exit();
			}
			$arrSetting = array(
			'bu_cuisine'	=>	empty($bu_cuisine) ? 0 : $bu_cuisine,
			'bu_username'	=>	$attribute==5 ? "$bu_username" : "",
			'bu_name'		=>	"$bu_name",
			'bu_nickname'	=>	"$bu_nickname",
			//'gender'		=>	"$gender",
			'bu_address'	=>	"$bu_address",
			'bu_suburb'		=>	"$bu_suburb",
			'bu_state'		=>	"$bu_state",
			'bu_phone'		=>	"$bu_phone",
			'bu_fax'		=>	"$bu_fax",
			'bu_procode'	=>	"$bu_procode",
			'bu_website'	=>	"$bu_website",
			'bu_urlstring'	=>	clean_url_name($bu_urlstring),
			'bu_email'		=>	"$bu_user",
			'bu_postcode'	=>	"$bu_postcode",
			'address_hide'	=>	"$address_hide",
			'phone_hide'	=>	"$phone_hide",
			'bu_area'		=>	"$bu_area",
			'CustomerType'	=>	"seller",
			'contact'		=>	"$contact",
			'mobile'		=>  "$mobile",
			'attribute'		=>	"$attribute",
			'subAttrib'		=>	"$subAttrib",
			'licence'		=>  "$licence",
			'referrer'		=>  "$referrer",
			'launch_date'	=>	time()
			);

			$_query = "SELECT id FROM ".$this->table."login WHERE ((attribute!=5 and user='".$bu_user."') or (attribute=5 and username='".$bu_username."')) and attribute='$attribute'";
			$this->dbcon->execute_query($_query);
			$result = $this->dbcon->fetch_records(true);

			if ($result[0]['id'] > 0) {

				header('Location: login_ipn.php?uname='.($attribute==5 ? $bu_username : $bu_user).'&password='.$bu_password.'&user_type='.$attribute."&hascount=hascount".'&regsuc=1');

			} else {
				$arrSetting['ref_name'] = getrefname();
				if ($this->dbcon-> insert_record($this->table."bu_detail", $arrSetting)) {
					$StoreID = $this->dbcon->lastInsertId();
					unset($arrSetting);
	            	
	            	if ($referrer) {
	                    $this->updateStoreRef('', $referrer);	
	            	}
	            	
					$url_store_name = clean_url_name($bu_urlstring);
					$arrSetting = array(
					'StoreID'		=>	"$StoreID",
					'user'			=>	"$bu_user",
					'username'		=>	$attribute == 5 ? "$bu_username" : "",
					'password'		=>	"$bu_password",
					'level'			=>	"1",
					'store_name'	=>	$url_store_name,
					'attribute'		=>  $attribute
					);

					if ($this->dbcon-> insert_record($this->table."login", $arrSetting)) {
						//setting session var
						$UserID					=	$this->dbcon->lastInsertId();
						$_SESSION['UserID']		=	$UserID;
						$_SESSION['UserName']	=	$bu_name;
						$_SESSION['NickName']	=	$bu_nickname;
						$_SESSION['StoreID']	=	$StoreID;
						$_SESSION['ShopID']		=	$StoreID;
						$_SESSION['LOGIN']		=	"login";
						$_SESSION['level']		=	1;
						$_SESSION['TemplateName']	=	'tmp-n-a';
						$boolResult	= true;

						if ($attribute == 5) {
							$tpl_fw = 'foodwine-a';
							include_once(SOC_INCLUDE_PATH . '/class.FoodWine.php');
							$fw_type = getFoodWineType($subAttrib);
							$tpl_fw = $fw_type == 'food' ? 'foodwine-a' : 'foodwine-d';
						}

						//insert default template
						$arrSetting = array(
						'TemplateName'	=>	$attribute == 0 ? 'tmp-n-a' : ($attribute == 1 ? 'estate-c' : ($attribute == 2 ? 'auto-c' : ($attribute == 3 ? 'job-c' : ($attribute == 5 ? $tpl_fw : 'tmp-n-a')))),
						'StoreID'		=>	"$StoreID",
						'tpl_type'		=>	($attribute+1)
						);
						$this->dbcon-> insert_record($this->table."template_details", $arrSetting);

						if($ispaid){
							$socObj = new socClass();
							$socObj->setrefEarn($referrer,$_POST['mc_gross'],$bu_nickname);
						}
					}
					$arrrefSetting = array(
						'buyer_id'  => $StoreID,
						'StoreID'	=> $StoreID,
						'p_status'	=> 'paid',
						'paid_date' => time() );
					$this->dbcon->update_record($this->table.'order_reviewref',$arrrefSetting,"where ref_id='$ref_id'");
					$hasCount = $this->activate($StoreID, $_POST['mc_gross'],'',$paid,$ref_id);

                    // Modify by Haydn.H By 20120306 ========= Begin =========
                    // facebook key save
                    if($fb_id){
                        $arrSetting = array(
                            'StoreID'   =>  $StoreID,
                            'fb_id'     =>  $fb_id,
                            'attribute' =>  $attribute
                        );

                        $sql = "SELECT count(*) as num FROM {$this->table}facebook where fb_id='$fb_id' and attribute='$attribute'";
                        $this->dbcon->execute_query($sql);
                        $totalNum	=	$this->dbcon->fetch_records();
                        $totalNum	= 	$totalNum[0]['num'];
                        if(! $totalNum){
                            $boolResult= $this->dbcon-> insert_record($this->table."facebook", $arrSetting);
                        }else{
                            $boolResult=$this->dbcon->update_record($this->table."facebook", $arrSetting, "where fb_id='$fb_id'and attribute='$attribute'");
                        }
                        if ($boolResult) {
                            $msg = 'Bundled facebook successfully. ';
                        }else {
                            $msg = 'Bundled facebook failed. ';
                        }
                    }
                    // Modify by Haydn.H By 20120306 ========= End =========


                    /**
                     * added by YangBall, 2011-07-05
                     * referrer new rule
                     */
                     if(isset($referrer) and '' != trim($referrer)) {
                         require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
                         $referrer = new Referrer();
	//                   $refStoreID = $referrer->getStoreIDByRefer($referrer);
	//                   if($refStoreID >0) {
							$referrer->addReferrerRecord('reg', $_SESSION['StoreID']);
	//                   }
                     }

                     //Paid Year IF have promotion code
                     if ($_POST['bu_procode'] != "") {
	            		if($this -> checkvalidpromot($_POST['bu_procode'], $attribute)){
	            			if ($attribute == 1 || $attribute == 2 || $attribute == 3) {
	            				$res = $this -> productActive($StoreID, 'year', array(), '', false);
	            			}								
							$bu_username = $attribute == 5 ? $_POST['bu_username'] : '';						
							$this -> updatepromotcode($_POST['bu_procode'] ,$_POST['bu_user'], $bu_username, $StoreID);
	            		}
	            	}

                    //END-YangBall
					header('Location: login_ipn.php?uname='.($attribute == 5 ? $bu_username : $bu_user).'&password='.$bu_password.'&user_type='.$attribute. "&hascount=".($hasCount?'hascount':'').'&regsuc=1');
				}
			}
		}
	}

/**
	 * @title	: checkvalidpromot
	 * Wed Mar 18 06:23:50 GMT 2009 06:23:50
	 * @input	: $procode
	 * @output	: bool
	 * @description	:
	 * @author	: Roy.luo <support@infinitytesting.com.au>
	 * @version	: V1.0
	 *
	*/
	function checkvalidpromot($procode, $attribute=0){
		global $dbcon;
		$query = "select count(*) as num from {$GLOBALS['table']}promotion where promotion='{$procode}' and attribute='{$attribute}' and Isused = 0";
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);
		if($result[0]['num']==1){
			return true;
		}else{
			return false;
		}
	}
	function updatepromotcode($procode,$user,$username='',$StoreID){
		global $dbcon;
		$query = "update {$GLOBALS['table']}promotion set Isused=1,user='{$user}',username='{$username}',usedtime='".time()."',StoreID='{$StoreID}' where promotion='{$procode}'";
		return $dbcon->execute_query($query);
	}

	function activate($StoreID, $month, $custom='',$paid=0,$ref_id=0) {
		global $email_regards,$normal_url,$from_site_type;
		$boolResult = false;
		$QUERY		=	"select renewalDate,StoreID,bu_repid,CustomerType,attribute,subAttrib,paid  from ".$this->table."bu_detail where StoreID=".$StoreID."";
		$result		=	$this->dbcon->execute_query($QUERY) ;
		$grid 		=	$this->dbcon->fetch_records() ;
		$QUERY1		=	"select * from ".$this->table."login where  StoreID=".$StoreID."";
		$result		=	$this->dbcon->execute_query($QUERY1) ;
		$grid1 		=	$this->dbcon->fetch_records() ;

		$etype 		=	"regedit"; //regedit

		if($paid==$grid[0]['paid']){
			if(is_numeric($_POST['custom'])){
				deltmpcustom($_POST['custom']);
			}
			return ;
		}

		$sellerInfo = explode('&',$custom);
		foreach($sellerInfo as $val){
			$value = explode('=',$val);
			if(!ereg('=',$_POST['custom'])){
				${$value[0]} = urldecode($value[1]);
			}else{
				${$value[0]} = $value[1];
			}
		}
		if ($grid[0]['CustomerType'] == 'buyer') {
			$etype = "upgrade"; //upgrade

			$arrSetting = array(
			'bu_name'		=>	"$bu_name",
			'bu_nickname'	=>	"$bu_nickname",
			//'gender'	=>	"$gender",
			'bu_address'	=>	"$bu_address",
			'bu_suburb'		=>	"$bu_suburb",
			'bu_state'		=>	"$bu_state",
			'bu_phone'		=>	"$bu_phone",
			'bu_fax'		=>	"$bu_fax",
			'bu_procode'	=>	"$bu_procode",
			'bu_website'	=>	"$bu_website",
			'bu_urlstring'	=>	clean_url_name($bu_urlstring),
			'bu_email'		=>	"$bu_user",
			'bu_postcode'	=>	"$bu_postcode",
			'address_hide'	=>	"$address_hide",
			'phone_hide'	=>	"$phone_hide",
			'bu_area'		=>	"$bu_area",
			'CustomerType'	=>	"seller",
			'contact'		=>	"$contact",
			'mobile'		=>  "$mobile",
			'attribute'		=>	"$attribute",
			'subAttrib'		=>	"$subAttrib",
			'licence'		=>  "$licence",
			'referrer'		=>  "$referrer",
			'launch_date'	=>	time(),
			'paid'			=>  $paid
			);

			$strCondition ="where StoreID='$StoreID'";

			if ($this->dbcon-> update_record($this->table."bu_detail", $arrSetting, $strCondition)) {

				$url_store_name = clean_url_name($bu_urlstring);
				$arrSetting = array(
				'user'			=>	"$bu_user",
				'password'		=>	"$bu_password",
				'level'			=>	"1",
				'store_name'	=>	$url_store_name,
				'attribute'		=>	$attribute
				);

				if($this->dbcon-> update_record($this->table."login", $arrSetting, $strCondition)) {
					//setting session var
					$_SESSION['UserName']	=	$bu_name;
					$_SESSION['NickName']	=	$bu_nickname;
					$_SESSION['StoreID']	=	$StoreID;
					$_SESSION['ShopID']		=	$StoreID;
					$_SESSION['LOGIN']		=	"login";
					$_SESSION['level']		=	1;
					$_SESSION['TemplateName']	=	'tmp-n-a';

					//insert default template
					$arrSetting = array(
					'TemplateName'	=>	$_SESSION['TemplateName'],
					'StoreID'		=>	"$StoreID"
					);
					$this->dbcon-> insert_record($this->table."template_details", $arrSetting);
				}
			}
		}

		if($grid[0]['renewalDate']){
			$dateupto1	=	$grid[0]['renewalDate'];
		}
		$date11		=	time();

		// add promotion between Jan 14th and Jan 17th
		$renew_year = 1;

		// get the payment gross as month value for soc user
		if($dateupto1!=''){
			$etype = "keepup";
			//$dateupto = $this->__paypalActiveTime($month, $dateupto1);
			$dateupto = $this->__paypalActiveTime(10, $dateupto1);
		}else{
			//$dateupto = $this->__paypalActiveTime($month);
			$dateupto = $this->__paypalActiveTime(10);
		}

		// add job free user upgrade by ping.hu at 20090209
		if ($grid[0]['attribute'] == 3 && $grid[0]['subAttrib'] == 3 ) {
			$typeValue	= ', subAttrib = 3' ;
                        $isUp = true;
			$_SESSION['subAttrib']	=	3;
		}else{
			$typeValue = '';
                        $isUp = false;
		}

		//add get bu_name adn bu_urlstring by ping.hu at 20091118
		$query		=	"select * from ".$this->table."tmpcustom where id=$tmp_id";
		$this->dbcon->execute_query($query) ;
		$arrTemp	=	$this->dbcon->fetch_records(true);
		if (is_array($arrTemp)){
                        empty($arrTemp[0]['bu_name']) ? '' : $typeValue .= ", bu_name='{$arrTemp[0]['bu_name']}'";
			if(!empty($arrTemp[0]['bu_urlstring'])){
				$temp_bu_urlstring = clean_url_name($arrTemp[0]['bu_urlstring']);
				$typeValue .= ", bu_urlstring='".$temp_bu_urlstring."'";

				$QUERY		=	"UPDATE ".$this->table."login set store_name='{$temp_bu_urlstring}' where StoreID=".$StoreID."";
				$result		=	$this->dbcon->execute_query($QUERY);
			}

			$value = explode('&', $arrTemp[0]['custom']);
			foreach($value as $val){
				$value = explode('=',$val);
				$arrTemp['customparams'][$value[0]] = urldecode($value[1]);
			}
			$ref_id = $arrTemp['customparams']['ref_id'];
		}
		/*====================*/

		$QUERY		=	"UPDATE ".$this->table."bu_detail set renewalDate='$dateupto',paid=$paid, PayDate ='$date11' $typeValue where StoreID=".$StoreID."";
		$result		=	$this->dbcon->execute_query($QUERY);
		$boolResult	= !$result ? false : true ;

		if ($boolResult){
			if(is_numeric($_POST['custom'])){
				uptmpcustom($_POST['custom']);
			}else{
				uptmpcustom($tmp_id);
			}
		}
		//change by roy.lou 20090119
		switch($etype){
			case 'regedit':
				$customtype="Seller Registration";
				break;
			case 'keepup':
				$customtype="Renew";
				break;
			case 'upgrade':
				$customtype="Upgrade";
				break;
		}
		$arrrefSetting = array(
						'buyer_id'  => $StoreID,
						'StoreID'	=> $StoreID,
						'p_status'	=> 'paid',
						'paid_date' => time()
				);
		$this->dbcon->update_record($this->table.'order_reviewref',$arrrefSetting,"where ref_id='$ref_id'");
		$QUERY		=	"select b.*,l.password from ".$this->table."bu_detail b left join ".$this->table."login l on b.StoreID=l.StoreID where b.StoreID='".$StoreID."'";
		$result		=	$this->dbcon->execute_query($QUERY) ;
		$erlt 		=	$this->dbcon->fetch_records() ;

		$query_form_site = "INSERT INTO ".$this->table."order_from (`order_review_id`,`StoreID`,`from_site`,`from_type`,`form_date`)VALUES('$ref_id','{$StoreID}','{$from_site_type}','{$customtype}',".time().")";
		$this->dbcon->execute_query($query_form_site) ;
		$arrParams['attribute'] 	= $erlt[0]['attribute'];
		$arrParams['subAttrib'] 	= $erlt[0]['subAttrib'];
		$arrParams['bu_user'] 		= $erlt[0]['bu_email'];
		$arrParams['bu_password'] 	= $erlt[0]['password'];
		$arrParams['seller_name'] 	= stripslashes(str_replace("''", "'", $erlt[0]['bu_name']));

		$arrParams['bu_username'] 	= $erlt[0]['bu_username'];
		$arrParams['bu_name'] 		= $erlt[0]['bu_name'];
		$arrParams['bu_email'] 		= $erlt[0]['bu_email'];
		$arrParams['bu_nickname'] 	= $erlt[0]['bu_nickname'];
		$arrParams['bu_address'] 	= $erlt[0]['bu_address'];
		$arrParams['bu_suburb'] 	= $erlt[0]['bu_suburb'];
		$arrParams['bu_area'] 		= $erlt[0]['bu_area'];
		$arrParams['bu_phone'] 		= $erlt[0]['bu_phone'];
		$arrParams['bu_postcode'] 	= $erlt[0]['bu_postcode'];
		$arrParams['bu_procode'] 	= $erlt[0]['bu_procode'];
		$arrParams['amount']		= $month;
		$arrParams['display']		= $etype;
		$arrParams['To']			= $erlt[0]['bu_email'];

		$arrParams['Subject']	=	'Welcome To SOC Exchange' .($arrParams['attribute'] == 1?' - Real Estate':($arrParams['attribute'] == 2?' - Auto':($arrParams['attribute'] == 3?' - Careers':($arrParams['attribute'] == 5?' - Food & Wine':' - Buy & Sell'))));

		$arrParams['stateName']		=	getStateByName($erlt[0]['bu_state']);

		$arrParams['expiringDate']	=	$isUp ? 'No expire date' : date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$dateupto);
		$arrParams['email_regards'] =	'SOC exchange Australia';
		$arrParams['normal_url'] 	=	$normal_url;
		$objEmail	=	new emailClass();
		
		$res = false;
		if ($arrParams['attribute'] == 0 || $arrParams['attribute'] == 5 || ($arrParams['attribute'] == 3 && $arrParams['subAttrib'] == 3) || $arrParams['display'] != 'regedit' || $arrParams['bu_procode'] != '') {
			$res = $objEmail -> send($arrParams,'email_userinfo.tpl');
		}

		if($res && !($arrParams['attribute'] == 3 && $arrParams['subAttrib'] == 3)){
			$adminEmail = new emailClass(); 							//to admin
			$arrParams['To']	=	'patrick@infinitytechnologies.com.au';						//to admin
			$arrParams['BCC']	=	'james.w@infinitytechnologies.com.hk';	 						//to admin	
			$arrParams['type']	=	$customtype;
			$arrParams['cardNumber']	=	"PayPal";

			$adminEmail -> send($arrParams,'email_toadmin.tpl', false);
			unset($adminEmail);
		}
		unset($objEmail);
		return $boolResult;
	}

	private function __paypalActiveTime($money = 0, $intOldDate = 0){
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

	function sellerValidation($isUpdate) {
		$arrResult 	= 	null;
		if ($this-> _notVar)
		{
			$arrResult	=	$this -> getFormInputVar();

			$arrResult['subAttrib'] =	$arrResult['attribute'] == 1 ? $arrResult['subattr1'] : ($arrResult['attribute'] == 2 ? $arrResult['subattr2'] : ($arrResult['attribute'] == 3 ? $arrResult['subattr3'] : ($arrResult['attribute'] == 5 ? $arrResult['subattr5'] : 0)));

		}elseif ($isUpdate) {
			$query = "select *, t1.status As account_status from ".$this->table."bu_detail as t1,".$this->table."login as t2 ".
			" where t1.StoreID=t2.StoreID and t1.StoreID='$_SESSION[StoreID]'";

			$this-> dbcon -> execute_query($query);
			$arrTemp=	$this->dbcon->fetch_records();
			$arrResult	=	&$arrTemp[0];
			
			$arrResult['account_status']=	$arrResult['account_status'];
			$arrResult['CustomerType']	=	$arrResult['CustomerType'];
			$arrResult['bu_user']		=	$arrResult['user'];
			$arrResult['bu_password']	=	$arrResult['password'];
			$arrResult['bu_password1']	=	$arrResult['password'];
			$arrResult['suburb']		=	$arrResult['bu_suburb'];
			$arrResult['url_site_name']	=	clean_url_name($arrResult['bu_urlstring']);
			$arrResult['url_site_name']	=	(empty($arrResult['url_site_name']))?'':$arrResult['url_site_name'];
			$arrResult['bu_urlstring']	=	$arrResult['bu_urlstring'];
			$arrResult['bu_delivery']	=	$arrResult['bu_delivery'] ? unserialize($arrResult['bu_delivery']) : "";
			$arrResult['payments']		=	$arrResult['payments'] ? unserialize($arrResult['payments']) : "";
			$arrResult['college']		=	intval($arrResult['bu_college']);
			$arrResult['bu_postcode']	=	($arrResult['bu_postcode'] != "") ? $arrResult['bu_postcode'] : '';
			$arrResult['bu_phone']		=	($arrResult['bu_phone'] != "") ? $arrResult['bu_phone'] : '';

			if ($_SESSION['level'] == 2 && (strlen($arrResult['attribute']) == 0 || $arrResult['attribute'] == '4')) {
				$arrResult['attribute']		=	$_REQUEST['attribute'];
			}

		}else{
			$arrResult['url_site_name']	=	'';
			$arrResult['attribute']		=	$_REQUEST['attribute'];
		}

		$arrResult['bu_delivery'] = $this -> changeArrayValue($arrResult['bu_delivery']);
		$arrResult['payments'] = $this -> changeArrayValue($arrResult['payments']);

		$state		= 	empty($arrResult['bu_state']) ? '' : $arrResult['bu_state'];
		$arrResult['State'] 	= getState3($state);
		$arrResult['soc'] = 1;
		//$arrResult['Subburb'] 	= getSubburb1($state,$arrResult['suburb']);
		$cities = getSuburbArray($state, $arrResult['suburb']);
		//echo "state:".$state.";suburb:".$arrResult['suburb'];
		$arrResult['Subburb'] = '<option value="">Select Suburb</option>';
		foreach ($cities as $row){
			$arrResult['Subburb'].= '<option value="'.$row['bu_suburb'].'" ';
			$arrResult['Subburb'].= $row['selected'];
			$arrResult['Subburb'].= '>'.$row['bu_suburb'].'</option>';
		}

    	$arrResult['cuisine_list'] = getCuisineList();

		$arrResult['msg']		= $_SESSION["pageParam"]["msg"];

		return $arrResult;
	}

	function sellerSave($isUpdate=false)
	{
		$boolResult	=	false;
		
		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);
		
		$own_website = (isset($own_website) && ($own_website == 1));

		$query	="select count(*) from  ".$this->table."login as t1 where t1.StoreID != '$_SESSION[ShopID]' and t1.user='".$bu_user."' and t1.attribute = '{$_SESSION['attribute']}'";
		$result	=	$this->dbcon->execute_query($query) ;
		$arrCheck = $this->dbcon->fetch_records() ;

		if($arraySeting['attribute']==0){
			$query	="select count(*) as num from  ".$this->table."bu_detail as t1
			 where t1.StoreID != '$_SESSION[ShopID]' and t1.bu_name='".$bu_name."'  and t1.attribute=0";
			$result	=	$this->dbcon->execute_query($query) ;
			$arrCheckBuname = $this->dbcon->fetch_records(true);
		}else{
			$arrCheckBuname[0]['num'] = 0;
		}

		$url_store_name = clean_url_name($bu_urlstring);
		//$QUERY = "select count(*) as num from ".$this->table."login where store_name='".$url_store_name."' and StoreID!='$_SESSION[ShopID]'";
		$QUERY = "select count(*) as num from ".$this->table."login as t1 left join "
				.$this->table."bu_detail as t2 on t1.StoreID=t2.StoreID "
				."where t1.store_name='".$url_store_name."' and t1.StoreID!='$_SESSION[ShopID]'";
		$this->dbcon->execute_query($QUERY);
		$store_name_check = $this->dbcon->fetch_records();
		$store_name_check = $store_name_check[0]['num'];

		if ((!$own_website) && ($attribute == 0 && $arrCheckBuname[0]['num'] > 0 || checkTmpcustomExist(array('bu_name'=> $bu_name)))) {
			$msg	= "Web Name exists. Please try again.";
		//}elseif ((!$own_website) && ($store_name_check>0 || checkTmpcustomExist(array('bu_urlstring'=> $url_store_name)))) {
			//$msg	= "This URL string has already been used.\n Please create another.";
		}elseif ((!$own_website) && ($arrCheck[0][0] > 0 && $_SESSION['attribute'] != 5)) {
			$msg	=	"The Email Address you have entered already exists. Please try again.";
		}elseif ((!$own_website) && (strlen($url_store_name)>60)) {
			$msg = "The URL String must be less than 60 characters. Please try again.";
		} else {
			//$setSubAttrib	=	$attribute == 1 ? $subattr1 : ($attribute == 2 ? $subattr2 : ($attribute == 3 ? $subattr3 : ($attribute == 5 ? $subattr5 : 0)));
			$arrSetting = array(
			'attribute'		=>	"$attribute",
			//'subAttrib'		=>	$setSubAttrib,
			'mobile'		=>	"$mobile",
			'licence'		=>	$attribute == 2 && $subattr2==2 ? $licence : '',

			'bu_cuisine'	=>	empty($bu_cuisine) ? 0 : $bu_cuisine,
			'bu_name'		=>	"$bu_name",
			'bu_nickname'	=>	"$bu_nickname",
			//'gender'		=>	"$gender",
			'bu_address'	=>	"$bu_address",
			'bu_suburb'		=>	"$bu_suburb",
			'bu_state'		=>	"$bu_state",
			'bu_postcode'	=>	"$bu_postcode",
			'bu_phone'		=>	"$bu_phone",
			'bu_fax'		=>	"$bu_fax",
			'bu_procode'	=>	"$bu_procode",
			'bu_website'	=>	"$bu_website",
			'referrer'		=>  "$referrer",
			'bu_urlstring'	=>	clean_url_name($bu_urlstring),
			'bu_email'		=>	"$bu_user",
			'address_hide'	=>	$address_hide ==1 ? $address_hide : "0",
			'phone_hide'	=>	$phone_hide == 1 ? $phone_hide : "0",
			'bu_area'		=>	"$bu_area",
			'contact'		=>	"$contact"
			);
			
			if ($own_website) {
				$arrSetting['bu_name'] = $business_name;
			}
			
			if (isset($subattr5)) {
				$arrSetting['subAttrib'] = $subattr5;
			}
			
			//$arrSetting['ref_name'] = getrefname();

			$strCondition = "where StoreID='$_SESSION[ShopID]'";
			if ($this->dbcon-> update_record($this->table."bu_detail", $arrSetting, $strCondition)){
				$StoreID = $this->dbcon->lastInsertId();
				unset($arrSetting);
				$arrSetting = array(
				'user'			=>	"$bu_user",
				'level'			=>	"1",
				'store_name'	=>	$url_store_name
				);
				if ($bu_password) $arrSetting['password'] = crypt($bu_password,getSalt());

				if($this->dbcon-> update_record($this->table."login", $arrSetting, $strCondition)){

					if ($attribute == 5) {
						include_once(SOC_INCLUDE_PATH . '/class.FoodWine.php');
						$fw_type_old = getFoodWineType($_SESSION['subAttrib']);
						if (isset($subattr5)) {
							$fw_type = getFoodWineType($subattr5);
							if ($fw_type_old != $fw_type) {
								//update default template
								$tpl_fw = $fw_type == 'food' ? 'foodwine-a' : 'foodwine-d';
								$arrSetting = array(
									'TemplateName'	=>	$tpl_fw
								);
								$this->dbcon-> update_record($this->table."template_details", $arrSetting, " WHERE StoreID='$_SESSION[ShopID]'");
								$this->resetFoodwineProduct($fw_type, $_SESSION['ShopID']);
							}
						}
					}


					$_SESSION['UserName']=str_replace("''", "'", Input::StripString($bu_name));
					$_SESSION['NickName']=str_replace("''", "'", Input::StripString($bu_nickname));

					$_SESSION['attribute']	=	$attribute;
					if (isset($subattr5)) {
						$_SESSION['subAttrib'] = $subattr5;
					}
					$boolResult	= true;
				}else{
					$msg	= "Operation failed by inster at step 2.";
				}
			}else{
				$msg	= "Operation failed by inster at step 1.";
			}
		}
		
		//echo $msg;
		$this -> addOperateMessage($msg);

		return $boolResult;
	}

	function resetFoodwineProduct($fw_type, $StoreID)
	{
		$StoreID = $StoreID ? $StoreID : $_SESSION['ShopID'];
		if ($fw_type == 'food') {
			$sql = "update `{$this->table}product_foodwine` set `type`=IF(`is_special`=1, 'special', 'stock') WHERE `StoreID`='$StoreID'";
		} else {
			$sql = "update `{$this->table}product_foodwine` set `is_special`=IF(`type`='special', 1, 0), `type`='wine' WHERE `StoreID`='$StoreID'";
		}

		$this->dbcon->execute_query($sql);
	}

	function getRandomProduct($num=6) {
		
		$limit = 100;
		$arrTemp = array();
		$wheresql = "";

		$tfls = 0;
		$timenow = time();
		$wheresql .= " and product.on_sale <> 'sold' AND IF(product.is_auction='yes',".($tfls>0?"au.end_stamp-$timenow<=$tfls and ":"")." au.end_stamp-$timenow>0,1=1) ";
		$wheresql .= " and product.is_auction in('yes','no') ";

		$query	=	"SELECT '' AS website_name, '' AS img_link, product.description, product.StoreID, ".
		" product.pid,product.item_name, product.url_item_name, product.price, product.unit,product.on_sale,".
		" product.image_name,state.stateName as state_name, t2.smallPicture, t2.picture".
		" ,detail.bu_state, detail.bu_suburb, detail.bu_name,detail.bu_urlstring, product.is_auction, product.category, au.cur_price,au.end_stamp,is_certified ".
		" FROM ".$this->table."product as product " .
		" left join ".$this->table."bu_detail as detail on detail.StoreID=product.StoreID ".
		" left join ".$this->table."login as lg on lg.StoreID = detail.StoreID ".
		" left join ".$this->table."state as state on detail.bu_state=state.id ".
		" left join ".$this->table."product_auction  as au on au.pid=product.pid ".
		" LEFT JOIN `". $this->table ."product_category` AS `CA` ON product.category = CA.id \n".
		" left join ".$this->table."image as t2 on (product.pid=t2.pid and product.StoreID=t2.StoreID)".
		" WHERE 1=1 $wheresql and detail.CustomerType = 'seller' ".
		//" AND detail.renewalDate > ".time().
		" AND lg.suspend=0 ".
		" AND product.Deleted = '' and not (detail.bu_name is null) ".
                "AND IF(product.is_auction='yes',au.starttime_stamp <=".time().",1=1) ".
		" AND IF(t2.StoreID>0, product.StoreID=t2.StoreID,1=1) ".
		//" GROUP BY product.category".
		" ORDER BY RAND() ".
		" Limit 0, $limit";

		//echo $query;
		$this-> dbcon -> execute_query($query);
		$arrTemp = $this->dbcon->fetch_records(true);
		if(!empty($arrTemp)) {
			foreach ($arrTemp as $temp)
			{
				//set default images
				if ($proID>0){
					$temp['image_name'] = $this -> imageDisplayWH($temp['picture'],2,241,241);
				}elseif ($template['TemplateName'] == 'tmp-n-e'){
					$temp['image_name'] = $this -> imageDisplayWH($temp['picture'],3,243,212);
				}else{
					$temp['image_name'] = $this -> imageDisplayWH($temp['smallPicture'],4,79,79);
					if ($limit > 1) {
						$temp['description'] = strip_tags($temp['description']);
						$temp['description'] = str_replace('&nbsp;', ' ', $temp['description']);
					}
				}

				$objUI	=	new uploadImages();
				$temp['images']	=	$objUI -> getDisplayImage('store', $temp['StoreID'], $temp['pid']);
				$temp['simage']	=	$objUI -> getDefaultImage($temp['smallPicture'], true, 0, 0, 4);
				$temp['bimage']	=	$objUI -> getDefaultImage($temp['picture'],false,0,0,9);
				$temp['limage']	=	$objUI -> getDefaultImage($temp['picture'],false,0,0,15);
				$temp['images_count'] = $temp['images']['imagesCount'];
				unset($objUI);
				$temp['image_name']['padding'] = 80 - $temp['image_name']['width'];

				//add a url bu_name by jessee 080804
				$temp['url_bu_name'] = clean_url_name($temp['bu_urlstring']);
				//set price format by jessee 080325
				$temp['price'] = number_format($temp['price'],2,'.',',');
				$temp['end_stamp'] = $temp['end_stamp']-time()>0?$temp['end_stamp']-time():0;
				/*if (!empty($this -> lang['Delivery'][strval($temp['deliveryMethod'])]['text'])){
					$temp['deliveryMethod'] = $this -> lang['Delivery'][strval($temp['deliveryMethod'])]['text'];
				}else{
					$temp['deliveryMethod'] = "";
				}*/

				$arrResult[]	=	$temp;
			}
		}

		$i = 0;
		$res = array();
		$category = array();
		foreach ($arrResult as $item) {
			if ($item['images']['mainImage'][0]['sname']['text'] != '/images/243x212.jpg' && !in_array($item['category'], $category)) {
				$res[$i] = $item;
				$category[] = $item['category'];
				$i++;
			}
			if ($i >= $num) {
				break;
			}
		}

		return $res;
	}

	function getDesignInfo() {

		$query = "select * from ".$this->table."bu_detail as t1,".$this->table."login as t2 ".
		" where t1.StoreID=t2.StoreID and t1.StoreID='$_SESSION[ShopID]'";

		$this-> dbcon -> execute_query($query);
		$arrTemp=	$this->dbcon->fetch_records(true);
		$arrResult	=	&$arrTemp[0];

		$arrResult['delivery'] 		= 	$this->getStorePayment();
		$arrResult['bu_delivery']	=	$arrResult['bu_delivery'] ? unserialize($arrResult['bu_delivery']) : "";
		$arrResult['payments']		=	$arrResult['payments'] ? unserialize($arrResult['payments']) : "";
		$arrResult['colleges']		=	$this->getCollegesByState($arrResult['bu_state'], $arrResult['bu_college']);
		$arrResult['desc']			=	$arrResult['bu_desc'] ;
		$arrResult['opening_hours'] = 	$arrResult['opening_hours'];
		//$arrResult['edit_opening_hours'] = $this->initEditor('opening_hours', $arrResult['opening_hours'], 'SOCBasic', array('100%', '100')) ;

		$arrResult['payments'] = $this -> changeArrayValue($arrResult['payments']);
		$arrResult['bu_delivery'] = $this -> changeArrayValue($arrResult['bu_delivery']);

		if ($arrResult['attribute'] == 5) {
			include_once ('class.FoodWine.php');
			$foodWine = new FoodWine();
			$arrResult['select_shipping'] = $foodWine->getStoreDeliveryMethod($arrResult['StoreID']);
			$arrResult['foodwine_type'] = getFoodWineType($arrResult['subAttrib']);
		}
		/*if ($_SESSION['attribute'] > 0) {
			$arrResult['bu_desc'] = $this->initEditor('bu_desc', htmlspecialchars_decode($arrResult['desc']),'SOCBasic',array('100%',450));
		}else {
			$arrResult['bu_desc'] = $this->initEditor('bu_desc', htmlspecialchars_decode($arrResult['desc']),'SOCBasic',array('100%',350));
		}*/


		$arrFeaturedImage = $this->getFeaturedImage();
		$arrResult = array_merge($arrResult, $arrFeaturedImage);
		return $arrResult;
	}

	function saveDesignInfo() {

		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);

		if ($cp == 'next' || $cp == 'save') {
			$arrPaymentSetting = array();
			for ($i = 0; $i < 5; $i++) {
				$arrPaymentSetting['key'] = $i;
				$arrPaymentSetting['payment'] = $bu_delivery_text[$i];
				$sql = "SELECT id FROM {$this->table}payment WHERE StoreID='$_SESSION[ShopID]' AND `key`='$i' LIMIT 1";
				$res = $this->dbcon->getOne($sql);
				if ($res) {
					$this->dbcon-> update_record($this->table."payment ", $arrPaymentSetting, "WHERE id='$res[id]'");
				} else {
					$arrPaymentSetting['StoreID'] = $_SESSION[ShopID];
					$this->dbcon->insert_record($this->table."payment ", $arrPaymentSetting);
				}

			}

			$arrSetting = array(
			'bu_paypal'				=> 		"$bu_paypal",
			'bu_delivery'			=>		is_array($bu_delivery) ? serialize($bu_delivery) : "",
			//'bu_delivery_text'		=>		is_array($bu_delivery_text) ? serialize($bu_delivery_text) : "",
			"postage"				=>		trim(str_ireplace('||','|',implode('|',$postage)),'|'),
			"deliveryMethod"		=>		trim(str_ireplace('||','|',implode('|',($deliveryMethod ? $deliveryMethod : $bu_delivery))),'|'),
			"isoversea"				=>		$isoversea ? $isoversea : 0,
			"payments"				=>		is_array($payments) ? serialize($payments) : "",
			'bu_college'			=>		"$bu_college",
			'bu_colleges_ACN'		=> 		"$bu_colleges_ACN",
			'college_hide'			=>		$college_hide?$college_hide:0,
			'bu_desc'				=>		"$bu_desc",
			'sold_status'			=>		$sold_status ? $sold_status : 0,
			'opening_hours'			=>		"$opening_hours",
			'suburb_delivery'		=>		"$suburb_delivery",
			'bt_account_name' 		=> 		"$bt_account_name",
			'bt_BSB'				=>		"$bt_BSB",
			'bt_account_num'		=>		"$bt_account_num",
			'bt_instruction'		=>		"$bt_instruction",
			'google_merchantid'		=>		"$google_merchantid",
			'google_merchantkey'	=>		"$google_merchantkey"
			);
			if($facebook=="http://"||$facebook=="https://"){
				$facebook = "";
			}
			if($twitter=="http://"||$twitter=="https://"){
				$twitter = "";
			}
			if($myspace=="http://"||$myspace=="https://"){
				$myspace = "";
			}
			if($linkedin=="http://"||$linkedin=="https://"){
				$linkedin = "";
			}
			if($isoversea){
				$arrSetting['oversea_postage']= trim(str_ireplace('||','|',implode('|',$oversea_postage)),'|');
				$arrSetting['oversea_deliveryMethod']=trim(str_ireplace('||','|',implode('|',($oversea_deliveryMethod ? $oversea_deliveryMethod : $bu_delivery))),'|');
			}else{
				$arrSetting['oversea_postage']= "";
				$arrSetting['oversea_deliveryMethod']='';
			}
			$arrSetting['facebook'] = $facebook;
			$arrSetting['twitter'] = $twitter;
			$arrSetting['myspace'] = $myspace;
			$arrSetting['linkedin'] = $linkedin;
			$arrSetting['youtubevideo'] = $youtubevideo;
			$strCondition = "where StoreID='$_SESSION[ShopID]'";
			if (!$this->dbcon-> update_record($this->table."bu_detail", $arrSetting, $strCondition)) {
				return false;
			}
		}

		/* comment by Jessee 081105, change the main Image to not mandatory
		*/
                /*
                 *  Delete By Yang Ball 2010-07-19
                 */
                /*
		if (!$this->editFeaturedImage()) {
			//return false;
		}*/

                /**
                 * added by YangBall, 2011-01-20
                 * change continue button
                 */
                $this->setSellerStepContinueButtonStatus($_SESSION['StoreID'],2);
                //END-YangBall
		return true;
	}

	function getFeaturedImage() {

		$arrResult 	= 	null;

		if ($this-> _notVar)
		{
			$arrResult['select']	=	$this -> getFormInputVar();
		}else{
			$query		=	"SELECT * FROM ".$this-> table."template_details WHERE StoreID ='$_SESSION[ShopID]'";
			$this-> dbcon -> execute_query($query);
			$arrTemp=	$this->dbcon->fetch_records();

			if (is_array($arrTemp)){
				$arrResult['select']['MainImg']	=	$arrTemp[0]['MainImg'];
				$arrResult['select']['MainImageH']	=	$arrResult['select']['MainImg'];
			}
		}

		$arrResult['MainImgW']	=	'185';
		$arrResult['MainImgH']	=	'130';
		if($arrTemp[0]['TemplateName']=='tmp-n-a'){
			$arrResult['MainImgW']	=	'250';
			$arrResult['MainImgH']	=	'98';
		}
		$arrResult['select']['MainImgDis'] = empty($arrResult['select']['MainImageH'])? (empty($arrResult['select']['MainImg'])? 'images/big1_logo.gif' : $arrResult['select']['MainImg']) : $arrResult['select']['MainImageH'];

		$arrResult['select']['step']	=	$_REQUEST['step'];
		$arrResult['cp']	=	'next';

		return $arrResult;
	}

	function editFeaturedImage() {

		$boolResult	=	true;
		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);

		$query = "select MainImg from ".$this-> table."template_details WHERE StoreID ='$_SESSION[ShopID]'";

		$this-> dbcon ->execute_query($query);
		$picUrl = $this-> dbcon -> fetch_records(true);
		$MainImgUrl = $picUrl[0]['MainImg'];

		$realpath	=	realpath(".");

		if ($cp == 'next' || $cp == 'save') {

			if ($MainImgUrl != $MainImageH && !empty($MainImgUrl) && substr_count($MainImgUrl,"mainImage/") && file_exists($realpath ."/".$MainImgUrl)) {
				unlink($realpath ."/".$MainImgUrl);
			}

			// update images
			$query	=	"update ".$this-> table."template_details set MainImg='$MainImageH' WHERE StoreID ='$_SESSION[ShopID]'";
			$boolResult = $this-> dbcon-> execute_query($query);

		} elseif ($cp == 'delmain') {

			if (!empty($MainImgUrl) && substr_count($MainImgUrl,"mainImage/") && file_exists($realpath ."/".$MainImgUrl)) {
				unlink($realpath ."/".$MainImgUrl);
			}
			$query	=	"update ".$this-> table."template_details set MainImg='' WHERE StoreID ='$_SESSION[ShopID]'";
			$boolResult = $this-> dbcon-> execute_query($query);
		}

		return $boolResult;
	}
	
	function getLogos($StoreID) {
		$_query = "SELECT * FROM ".$this->table."template_details WHERE StoreID=".$StoreID;
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		if (is_array($arrTemp)) {
			$objUI = new uploadImages();
			$arrTemp[0]['images'] = $objUI->getDisplayImage('',$StoreID,0,-1,-1,6);
			unset($objUI);
		}
		return $arrTemp[0];
	}

	function getDesignTheme() {

		$StoreID = $_SESSION['ShopID'];
		$_query = "SELECT * FROM ".$this->table."template_details WHERE StoreID=".$StoreID;

		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		if (is_array($arrTemp)) {
			$objUI	=	new uploadImages();
			$arrTemp[0]['images'] =	$objUI->getDisplayImage('',$StoreID,0,-1,-1,$arrTemp[0]['tpl_type']);
			unset($objUI);
		}

		return $arrTemp[0];
	}
	
	function updateLogo($StoreID) {
		$objUI = new uploadImages();
		$arrSetting = array(2 => array('simage'=> $_REQUEST['mainImage2_svalue'], 'bimage'=> $_REQUEST['mainImage2_bvalue']));
		$objUI->setDisplayImage('', $arrSetting, $StoreID, 0, 0, 6);
		
		$query = "SELECT * FROM ".$this-> table."image WHERE StoreID = ".$StoreID;
		if ($this->dbcon->execute_query($query)) {
			$result = $this->dbcon->fetch_records(true);
			if (count($result) > 0) {
				$image = $result[0]['picture'];
				$update_query = "UPDATE ".$this->table."template_details SET LogoDisplay = '1', MainImg='".$image."' WHERE StoreID = '".$StoreID."'";
				$this->dbcon->execute_query($update_query);
			}
		}
		return true;
	}

	function saveDesignTheme() {
		$booleanReault	=	false;
		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);

		$arrSetting = array(
		'TemplateName' => "$TemplateName",
		'TemplateBGColor' => "$TemplateBGColor",
		'WebsiteIconID' => ($WebsiteIconID ? $WebsiteIconID : 0),
		'tpl_type'		=>	($_SESSION['attribute'] + 1),
		'bannerImg'		=>	"$txt_banner",
		'LogoDisplay'		=>	($logoDisplay ? $logoDisplay : 0),
		);

		$strCondition = "where StoreID='$_SESSION[ShopID]'";
		if ($this->dbcon->update_record($this->table."template_details", $arrSetting, $strCondition)) {
			$_SESSION['TemplateName'] = "$TemplateName";
			$booleanReault	=	true;
		}

		//modify picture
		if ($booleanReault) {
			$objUI	=	new uploadImages();
			$arrSetting	=	array(
				'0' => array('simage'=> $_REQUEST['mainImage0_svalue'], 'bimage'=> $_REQUEST['mainImage0_bvalue']),
				'1' => array('simage'=> $_REQUEST['mainImage1_svalue'], 'bimage'=> $_REQUEST['mainImage1_bvalue'])
			);
			if (($_SESSION['attribute'] + 1) == 2) {
				$arrSetting['2']	=	array('simage'=> $_REQUEST['mainImage2_svalue'], 'bimage'=> $_REQUEST['mainImage2_bvalue']);
			}elseif (($_SESSION['attribute'] + 1) == 4){
				$arrSetting['2']	=	array('simage'=> $_REQUEST['mainImage2_svalue'], 'bimage'=> $_REQUEST['mainImage2_bvalue']);
				$arrSetting['3']	=	array('simage'=> $_REQUEST['mainImage3_svalue'], 'bimage'=> $_REQUEST['mainImage3_bvalue']);
			}elseif (($_SESSION['attribute'] + 1) == 6){
				$arrSetting['2']	=	array('simage'=> $_REQUEST['mainImage2_svalue'], 'bimage'=> $_REQUEST['mainImage2_bvalue']);
				$arrSetting['3']	=	array('simage'=> $_REQUEST['mainImage3_svalue'], 'bimage'=> $_REQUEST['mainImage3_bvalue']);
				$arrSetting['4']	=	array('simage'=> $_REQUEST['mainImage4_svalue'], 'bimage'=> $_REQUEST['mainImage4_bvalue']);
			}
      if(!empty($_REQUEST['mainImage5_svalue'])) {
				$arrSetting['5']	=	array('simage'=> $_REQUEST['mainImage5_svalue'], 'bimage'=> $_REQUEST['mainImage5_bvalue']);
      }
			$objUI -> setDisplayImage('',$arrSetting,$_SESSION['StoreID'],0,0,($_SESSION['attribute'] + 1));
			unset($objUI);
		}
                $this->editFeaturedImage();

                /**
                 * added by YangBall, 2011-01-20
                 * change continue button
                 */
                 $this->setSellerStepContinueButtonStatus($_SESSION['StoreID'], 3);
                //END-YangBall
		return $booleanReault;
	}

	/*
	function getPaypalInfo() {

	$paypalInfo = array();

	if ($this->paypal_info['paypal_mode'] == 0) {
	$paypalInfo['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr/';
	} else {
	$paypalInfo['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr/';
	}
	$paypalInfo['paypal_email']	= $this->paypal_info['paypal_email'];
	$paypalInfo['paypal_siteurl'] = PAYPAL_SITEURL;

	return $paypalInfo;
	}
	*/


	/**
	 * get products information from CSV file
	 * @param array $_FILES
	 * @return array
	 */
	function getProductCSV($csv){
		/* get csv content version 1
		$file = file($csv['tmp_name']);
		$field = explode(',',$file[0]);
		// check field for necesary
		//var_dump($file);
		$price = 0;
		$itemName = 0;
		for($i=0;$i<count($field);$i++){
		$field[$i] = trim($field[$i]);
		$price = ($field[$i]=='price')?1:$price;
		$itemName = ($field[$i]=='itemName')?1:$itemName;
		}
		if ($price == 0 or $itemName == 0){
		return false;
		}

		$products = array();
		$products[0] = $field;
		for($i=1;$i<count($file);$i++){
		$arrTemp = explode(',',$file[$i]);
		$products[] = $arrTemp;
		}
		*/
		$file = fopen($csv['tmp_name'],'r');
		$products = array();
		while(($row = fgetcsv($file,0,',','"'))!==false){
			$products[] = $row;
		}
		fclose($file);
		return $products;
	}

	/**
	 * Import products information
	 * @param array $products $images
	 * @return status
	 */
	function importProducts($products,$images){
		$imgup = new uploadImages();
		$field = $products[0];
		$images = $imgup->getzipProductImages($images);
		$product_csv = $GLOBALS['product_csv'];
		$success = 0;
		$fail = 0;
		$fail_detail = '';
		$arrShippings = array(); // for postcost
		$arrOverseaShippings = array(); //for oversea postcost
		$field_list = array(
			'#','productcode','quantity','itemname','price','unit','ono','onsale','category','subcategory',
			'shippingcost','shippingmethod','mainimage','image1','image2','image3',
			'image4','image5','image6','description','homeitem',
			'allowshippingoverseas','shippingcostoverseas','shippingmethodoverseas','youtubevideo');
		$pay_method = array(1=>'cash',3=>'paypal',4=>'cheque',5=>'banktransfer',6=>'other');
		$ship_method = array(1=>'AUS Post',2=>'Courier',3=>'Pickup',4=>'Other',5=>'C.O.D.');
		unset($_SESSION['multi_upload']);
		if(count(array_unique($field))!=count($field_list)){
			return array(0,'all');
		}
		//check images and save products information to database
		$sql = "insert into ".$this->table."product (";
		#, productCode, itemName, price, unit, OBO, onSale, category, deliveryCost, deliveryMethod, mainImage, image1, image2, imge3, description
		$payment = 0;
		$is_category = 0;
		$is_subCategory = 0;
		$is_onsale = 0;
		$is_quantity = 0;

		for ($i=0;$i<count($field);$i++){
			//echo "field:[".$field[$i]."]<br>";
			$field[$i] = trim($field[$i]);
			$field[$i] = strtolower($field[$i]);
			if($field[$i]!=""){
				if(!in_array(strtolower($field[$i]),$field_list)){
					return 	array(0, 'all');
				}
			}else{
				return array(0,'all');
			}
			if (in_array(strtolower($field[$i]),$pay_method)){
				if ($payment==0){
					$payment = 1;
				}
			}elseif( $field[$i]=='subcategory' or $field[$i]=='category'){
				if ($is_category==0){
					$is_category = 1;
				}
				if ($is_subCategory==0){
					$is_subCategory = 1;
				}
			}elseif($field[$i]=='#'){
				continue;
			}elseif($field[$i]=='onsale'){
				$is_onsale = 1;
			}elseif($field[$i]=='quantity'){
				$is_quantity = 1;
			}elseif($field[$i]=='itemname'){
				$sql.= $product_csv[$field[$i]].',url_item_name,';
			}else{
				if(isset($product_csv[$field[$i]])&&$product_csv[$field[$i]]!=""){
					$sql.= $product_csv[$field[$i]].',';
				}else{
					return 	array(0, 'all');
				}
			}
		}
		if ($is_category==1){
			$sql.= "category,";
		}else{
			return array(0, 'all');
		}
		if ($payment==1){
			$sql.= 'payments,';
		}
		if ($is_quantity==1){
			$sql.= 'stockQuantity,';
		}
		if ($is_onsale==1){
			$sql.= 'on_sale,';
		}
		$sql.= "datec,datem,StoreID) values(";
		$payment_method = array();

		$imageinfo = array();

		for($i=1,$k=0;$i<=count($products);$i++){
			if (count($products[$i])<count($field)){
				continue;
			}
			$values = '';
			$invalid = 0;
			for($j=0;$j<count($products[$i]);$j++){
				$products[$i][$j] = trim($products[$i][$j]);
				$field[$j] = trim($field[$j]);
				if (in_array(strtolower($field[$j]),$pay_method)){
					$products[$i][$j] = strtolower($products[$i][$j]);
					foreach ($pay_method as $key_p=>$val_p){
						if(strtolower($field[$j])==strtolower($val_p)){
							if($products[$i][$j]=='yes'){
								$payment_method[] = $key_p;
							}
						}
					}
				}elseif($field[$j]=='itemname'){

					$invalid = empty($products[$i][$j])?1:$invalid;
					if(empty($products[$i][$j])){
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][4];
					}

					/***if same item name auto renew the item url****/
					/***2010 04 15 roy.luo***/
					$n=0;
					$item_url= clean_url_name($products[$i][$j]);
					do {
						if($n>0){$item_url = clean_url_name($products[$i][$j]).(date("His",time()+$i));}
						$ckname_bl = $this->checkProductName($item_url,$_SESSION['ShopID']);
						$n++;
					}while ($ckname_bl);
					/****end by roy.luo***/

					$values .= "'".addslashes($products[$i][$j])."','".clean_url_name($item_url)."',";


				}elseif($field[$j]=='description'){
					if (empty($products[$i][$j])){
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][5];
						$invalid = 1;
					}else{
						$values.= "'".mysql_escape_string(trim(formatTextarea(strip_tags($products[$i][$j]))))."',";
					}
				}elseif($field[$j]=='category'){
					$category = $products[$i][$j];
				}elseif($field[$j]=='subcategory'){
					$subCategory = $products[$i][$j];
				}elseif($field[$j]=='#'){
					$_SESSION['multi_upload'][$i]['ID'] = $products[$i][$j];
					continue;
				}elseif($field[$j]=='onsale'){
					$products[$i][$j] = trim(strtolower($products[$i][$j]));
					if (!in_array($products[$i][$j],array('now','sold','contact'))){
						$products[$i][$j] = 'no';
					}

					if ($products[$i][$j]=='now'){
						$products[$i][$j] = 'yes';
					}
					//$values.= "'".$products[$i][$j]."',";
					$on_sale = $products[$i][$j];
				}elseif($field[$j]=='homeitem'){
					$products[$i][$j] = strtolower($products[$i][$j]);
					$products[$i][$j] =($products[$i][$j]=='yes')?1:0;
					$values.= $products[$i][$j].",";
				}elseif($field[$j]=='obo'||$field[$j]=='ono'){
					$products[$i][$j] = strtolower($products[$i][$j]);
					$products[$i][$j] =($products[$i][$j]=='yes')?1:0;
					$values.= $products[$i][$j].",";
				}elseif($field[$j]=='shippingmethod'){
					$tempAry =  array();
					$tempShipping = explode('|',$products[$i][$j]);
					$tempship = 4;

                    foreach ($tempShipping as $tmps){
                        foreach ($ship_method as $keys_s=>$val_s){
							if(strtolower($val_s)==strtolower(trim($tmps))){
								$tempAry[] = $keys_s;
                                break;
							}
						}
					}

					if(!empty($tempAry)){
                        $arrShippings = $tempAry;
						$tempship = implode('|',$tempAry);
					}
					$products[$i][$j] = "'".$tempship."'";

					$values.= $products[$i][$j].",";

				}elseif ($field[$j]=='allowshippingoverseas'){
					if (strtolower($products[$i][$j])=='yes'){
						$values.= '1'.",";
					}else{
						$values.= '0'.",";
					}
				}elseif ($field[$j]=='shippingmethodoverseas'){

					$tempAry =  array();
					$tempShipping = explode('|',$products[$i][$j]);
					$tempship = 4;
                    foreach ($tempShipping as $tmps){
                        foreach ($ship_method as $keys_s=>$val_s){
							if(strtolower($val_s)==strtolower(trim($tmps))){
								$tempAry[] = $keys_s;
                                break;
							}
						}
					}
					if(!empty($tempAry)){
                        $arrOverseaShippings = $tempAry;
						$tempship = implode('|',$tempAry);
					}

					$products[$i][$j] = "'".$tempship."'";

					$values.= $products[$i][$j].",";


				}elseif($field[$j]=='quantity'){
					if (!is_numeric($products[$i][$j])||strpos($products[$i][$j],'.')===true||$products[$i][$j]<0){
						$quantity = 0;
					}else{
						//$values.= $products[$i][$j].",";
						$quantity = $products[$i][$j];
					}
				}else{
					if ($invalid!=1 and ($field[$j]=='mainimage' or $field[$j]=='image1'
					or $field[$j]=='image2' or $field[$j]=='image3' or $field[$j]=='image4'
					or $field[$j]=='image5' or $field[$j]=='image6')){

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
							if($field[$j]=='mainimage'){
								$imageinfo[$i][$k]['attrib'] = 0;
								$imageinfo[$i][$k]['sort'] = 0;
							}else{
								$imageinfo[$i][$k]['attrib'] = 1;
								$imageinfo[$i][$k]['sort'] = $z;
								$z++;
							}
							$k++;
						}

					}elseif($field[$j]=='price'){
						$invalid = (strlen($products[$i][$j])==0 or $products[$i][$j]<0)?1:$invalid;
						if(strlen($products[$i][$j])==0 or $products[$i][$j]<0){
							$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][6];
						}
						//echo "price:".$products[$i][$j]."-$invalid;";
					}elseif ($field[$j]=='shippingcost' ){
						if($products[$i][$j]==""){
							$products[$i][$j] = 0;
						}else{
                            /*
                             * fix bug#5403
                             * @author ronald
                             */
                            $shippingCosts = explode('|', $products[$i][$j]);
                            for($smi = 0; $smi < count($arrShippings); $smi++){
                                if(!isset($shippingCosts[$smi])){
                                    $shippingCosts[$smi] = 0;
                                }

                                if($arrShippings[$smi] == 3 || $arrShippings[$smi] == 4){
                                    if(isset($shippingCosts[$smi])){
                                        $shippingCosts[$smi] = 0;
                                    }
                                }
                            }
                            $products[$i][$j] = implode('|', $shippingCosts);
                        }

						$invalid = $products[$i][$j]<0?1:$invalid;
						if($products[$i][$j]<0){
							$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][7];
						}
						//echo "postage:".$products[$i][$j]."-$invalid;<br>";
					}elseif ($field[$j]=='shippingcostoverseas'){
						if($products[$i][$j]==""){
							$products[$i][$j] = 0;
						}else{
                             /*
                             * fix bug#5403
                             * @author ronald
                             */
                            $shippingCosts = explode('|', $products[$i][$j]);
                            for($smi = 0; $smi < count($arrOverseaShippings); $smi++){
                                if(!isset($shippingCosts[$smi])){
                                    $shippingCosts[$smi] = 0;
                                }

                                if($arrOverseaShippings[$smi] == 3 || $arrOverseaShippings[$smi] == 4){
                                    if(isset($shippingCosts[$smi])){
                                        $shippingCosts[$smi] = 0;
                                    }
                                }
                            }

                            $products[$i][$j] = implode('|', $shippingCosts);
                        }



						$invalid = $products[$i][$j]<0?1:$invalid;
						if($products[$i][$j]<0){
							$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][16];
						}
					}

					$values.= "'".mysql_escape_string(trim($products[$i][$j]))."',";
				}
			}
			if ($invalid==1){
				$fail+= 1;
				continue;
			}
			//echo "cate:$category;scate:$subCategory;";
			if (!empty($category) && !empty($subCategory)){
				//echo "category:$category;sub:$subCategory;";
				//$category = getCategoryID($category);
				$category = getMultiuploadCID($category);
				//echo "catid:$category;";
				if ($category==0){
					$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][8];
					$fail+= 1;
					continue;
				}
				if (is_int($category) || (is_numeric($category) && strpos($category, '.') === false && $category>0)){
					$subCategory = getMultiuploadSubCID($category, $subCategory);
					//echo "subid:$subCategory;";
					if (is_int($subCategory) || (is_numeric($subCategory) && strpos($subCategory, '.') === false && $subCategory>0)){
						$values.= $subCategory.",";
					}else{
						$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][9];
						$fail+= 1;
						continue;
					}
				}else{
					$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][9];
					$fail+=1;
					continue;
				}
				//exit;
			}else{
				//exit;
				$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][10];
				$fail+=1;
				continue;
			}
			if ($payment==1){
				$values.= "'".serialize($payment_method)."',";
				$payment_method = array();
			}
			if ($is_onsale==1){
				if($on_sale == ''){
					$on_sale = 'no';
				}
			}
			if ($is_quantity==1){
				if (!is_numeric($quantity) or $quantity<=0 or $quantity == ""){
					$quantity = 0;
				}
				if($quantity == 0 && ($on_sale!='sold' && $on_sale!='no')){
					$quantity = 1;
				}elseif($on_sale=='sold' || $on_sale=='no'){
					$quantity = 0;
				}
				$values.=$quantity.",";
			}
			if ($is_onsale==1){
				$values.= "'".$on_sale."',";
			}
			//echo "invalid:$invalid<br>";
			//exit;
			$values.= time().",".time().",".$_SESSION['ShopID'].")";

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
				$_SESSION['multi_upload'][$i]['msg'][$j] = $GLOBALS['multi_msg'][17];
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
		//echo "succ:$success; fail:$fail;";
		//exit;
		return array($success,$fail);
	}

	/**
	 * store product Add or Edit
	 *
	 * @return array
	 */
	function storeProductAddOrEdit()
	{
		global $dbcon;
		$arrResult 	= 	null;
		$StoreID	=	$_SESSION['ShopID'];

		if ($this-> _notVar)
		{
			$arrResult['select']	=	$this -> getFormInputVar();
			$hour = $arrResult['select']['end_hour'];
			$minute = $arrResult['select']['end_minute'];
			$arrResult['select']['hour_option'] = '';
			for ($i=0;$i<24;$i++){
				if ($i==$hour){
					$arrResult['select']['hour_option'].= '<option value="'.$i.'" selected>'.($i<10?'0':'').$i.'</option>';
				}else{
					$arrResult['select']['hour_option'].= '<option value="'.$i.'">'.($i<10?'0':'').$i.'</option>';
				}
			}
			$arrResult['select']['minute_option'] = '';
			for ($i=0;$i<60;$i++){
				if ($i==$hour){
					$arrResult['select']['minute_option'].= '<option value="'.$i.'" selected>'.($i<10?'0':'').$i.'</option>';
				}else{
					$arrResult['select']['minute_option'].= '<option value="'.$i.'">'.($i<10?'0':'').$i.'</option>';
				}
			}
			//print_r($arrResult);
		}else{
			$arrResult['select']	=	$this->_getProductOfferDelivery($StoreID);

			$pid		=	$_REQUEST['pid'];
			$query		=	"SELECT * FROM ".$this-> table."product WHERE pid='$pid'"
							." and StoreID ='$StoreID' and deleted='' order by datec asc";
			$this-> dbcon -> execute_query($query);
			$arrTemp=	$this->dbcon->fetch_records(true);

			if (is_array($arrTemp)){
				$arrResult['select']	+=	$arrTemp[0];
				$arrResult['select']['mainImageH'] = $arrResult['select']['image_name'];
				$arrResult['select']['payments']	=	$arrResult['select']['payments'] ? unserialize($arrResult['select']['payments']) : "";
			}
			
			if ($arrTemp[0]['is_auction']=='yes'){
				$query		=	"SELECT * FROM ".$this-> table."product_auction WHERE pid='$pid'";
				$this-> dbcon -> execute_query($query);
				$arrTemp=	$this->dbcon->fetch_records(true);
				$arrResult['select']['initial_price'] = $arrTemp[0]['initial_price'];
				$arrResult['select']['reserve_price'] = $arrTemp[0]['reserve_price'];
				list($year,$month,$day) = split('-',$arrTemp[0]['end_date']);
				$arrResult['select']['end_date'] = $day.'/'.$month.'/'.$year;
                                $arrResult['select']['start_datetime']=array('date'=>date('d/m/Y',$arrTemp[0]['starttime_stamp']),'hour'=>date('H',$arrTemp[0]['starttime_stamp']),'min'=>date('i',$arrTemp[0]['starttime_stamp']));
				list($hour,$minute) = split(':',$arrTemp[0]['end_time']);
				$arrResult['select']['hour_option'] = '';
				for ($i=0;$i<24;$i++){
					if ($i==($hour+0)){
						$arrResult['select']['hour_option'].= '<option value="'.$i.'" selected>'.($i<10?'0':'').$i.'</option>';
					}else{
						$arrResult['select']['hour_option'].= '<option value="'.$i.'">'.($i<10?'0':'').$i.'</option>';
					}
				}
				$arrResult['select']['extra']	=	explode('|=&&&&=|', $arrResult['select']['extra']);
				$arrResult['select']['minute_option'] = '';
				for ($i=0;$i<60;$i++){
					if ($i==($minute+0)){
						$arrResult['select']['minute_option'].= '<option value="'.$i.'" selected>'.($i<10?'0':'').$i.'</option>';
					}else{
						$arrResult['select']['minute_option'].= '<option value="'.$i.'">'.($i<10?'0':'').$i.'</option>';
					}
				}
			}else{
				$arrResult['select']['hour_option'] = '';
				for ($i=0;$i<24;$i++){
					$arrResult['select']['hour_option'].= '<option value="'.$i.'">'.($i<10?'0':'').$i.'</option>';
				}
				$arrResult['select']['minute_option'] = '';
				for ($i=0;$i<60;$i++){
					$arrResult['select']['minute_option'].= '<option value="'.$i.'">'.($i<10?'0':'').$i.'</option>';
				}
			}
			if (empty($arrResult['select']['payments'])) {
				$temp		=	array();
				$arrInfo	=	$this -> _displayStoreInfo($temp,$StoreID);
				$arrResult['select']['payments']	=	$arrInfo['payments'];
			}
			$protags = new producttag();
			$arrResult['select']['str_tags'] = $protags->get_pro_tags_ByPorID($pid,0);
			$arrResult['select']['deliveryMethod'] = explode('|',$arrResult['select']['deliveryMethod']);
			$arrResult['select']['postage'] = explode('|',$arrResult['select']['postage']);
			$arrResult['select']['oversea_deliveryMethod'] = explode('|',$arrResult['select']['oversea_deliveryMethod']);
			$arrResult['select']['oversea_postage'] = explode('|',$arrResult['select']['oversea_postage']);
		}
		if (empty($arrResult['select']['image_name'])) {
			$arrResult['select']['image_name']	=	'skin/red/images/default-mainimage.gif';
		}
		if (empty($arrResult['select']['moreImage1'])) {
			$arrResult['select']['moreImage1']	=	'images/79x79.jpg';
		}
		if (empty($arrResult['select']['moreImage2'])) {
			$arrResult['select']['moreImage2']	=	'images/79x79.jpg';
		}
		if (empty($arrResult['select']['moreImage3'])) {
			$arrResult['select']['moreImage3']	=	'images/79x79.jpg';
		}
		if (empty($arrResult['select']['moreImage4'])) {
			$arrResult['select']['moreImage4']	=	'images/79x79.jpg';
		}
		if (empty($arrResult['select']['moreImage5'])) {
			$arrResult['select']['moreImage5']	=	'images/79x79.jpg';
		}
		if (empty($arrResult['select']['moreImage6'])) {
			$arrResult['select']['moreImage6']	=	'images/79x79.jpg';
		}

		$arrResult['select']['payments'] = $this -> changeArrayValue($arrResult['select']['payments']);

		$arrResult['select']['step']	=	$_REQUEST['step'];
		$arrResult['select']['msg']		=	$_REQUEST['msg'];
		//$arrResult			=	$arrResult + $this-> _getImagesGallery(8);
		// generate the products list
		$sortby = $_REQUEST['sortby'];
		if ($sortby && ($sortby=='price_asc' || $sortby=='price_desc' || $sortby=='date_asc'
			|| $sortby=='date_desc' || $sortby=='time_asc' || $sortby=='time_asc')){
			$product_sortby = ($sortby == 'time_asc' || $sortby=='time_desc')?'date_asc':$sortby;
			$auction_sortby = ($sortby == 'date_asc' || $sortby=='date_desc')?'time_desc':$sortby;
		}else{
			$product_sortby = '';
			$auction_sortby = 'time_desc';
		}
		$arrResult = $arrResult + $this->_getProductList($StoreID,2,0,0,0,true, $product_sortby,false,false,true);
		$auction = $this->_getProductList($StoreID,2,0,0,0,true, $auction_sortby,false,false,true);
		$arrResult['auctionHas'] = $auction['productHas'];
		$arrResult['auction'] = $auction['product'];

		$arrResult['select']['categoryList']	=	$this-> _getProductCategoryList();
		$arrTemp	=	$this-> _getProductCategoryList(0,$arrResult['select']['category']);
		$arrResult['select']['categorySub']		=	$arrResult['select']['category'];
		$arrResult['select']['category']		=	$arrTemp[0]['fid'];

		$deliveryMethod = "";
		$oversea_deliveryMethod = "";
                $codHelp_one='<span style="position:absolute; width:23px;height:21px;">
                                    <a href="http://auspost.com.au/business/cash-on-delivery.html" target="_blank" onmouseover="javascript:$(\'#cod_help\').show();" onmouseout="javascript:$(\'#cod_help\').hide();">
                                        <img width="21" height="20" border="0" align="top" src="{$smarty.const.IMAGES_URL}skin/red/icon-question.gif" alt="Australia Post COD Information" title="Australia Post COD Information">
                                    </a>
                                    <div style="border:1px solid #CCCCCC;padding:10px;color: rgb(119, 119, 119); background: #FFE870;position:absolute;top:-17px;left:21px;display:none;z-index:100;float:right;white-space: nowrap;" id="cod_help" onmouseover="javascript:$(\'#cod_help\').show();" onmouseout="javascript:$(\'#cod_help\').hide();">
                                        &nbsp;&nbsp;&nbsp;<a href="http://auspost.com.au/business/cash-on-delivery.html" target="_blank" style="font:11px Verdana;" title="Australia Post COD Information">Australia Post COD Information</a>&nbsp;&nbsp;&nbsp;
                                    </div>

                        </span>';
                $codHelp_two='<span style="position:absolute; width:23px;height:21px;">
                                    <a href="http://auspost.com.au/business/cash-on-delivery.html" target="_blank" onmouseover="javascript:$(\'#cod_help2\').show();" onmouseout="javascript:$(\'#cod_help2\').hide();">
                                        <img width="21" height="20" border="0" align="top" src="{$smarty.const.IMAGES_URL}skin/red/icon-question.gif" alt="Australia Post COD Information" title="Australia Post COD Information">
                                    </a>
                                    <div style="border:1px solid #CCCCCC;padding:10px;color: rgb(119, 119, 119); background: #FFE870;position:absolute;top:-17px;left:21px;display:none;z-index:100;float:right;white-space: nowrap;" id="cod_help2" onmouseover="javascript:$(\'#cod_help2\').show();" onmouseout="javascript:$(\'#cod_help2\').hide();">
                                        &nbsp;&nbsp;&nbsp;<a href="http://auspost.com.au/business/cash-on-delivery.html" target="_blank" style="font:11px Verdana;" title="Australia Post COD Informationn">Australia Post COD Information</a>&nbsp;&nbsp;&nbsp;
                                    </div>

                        </span>';

        $arrInfo	=	$this -> _displayStoreInfo($temp,$StoreID);
        $delivery = $arrInfo['bu_delivery_text'];
		//echo var_export($arrInfo);
		
	    $arrResult['select']['deliveryMethod'] = (!empty($arrResult['select']['deliveryMethod'][0]) || $arrResult['select']['deliveryMethod'][0] == '0') ? $arrResult['select']['deliveryMethod'] : explode('|', $arrInfo['deliveryMethod']);
	    $arrResult['select']['oversea_deliveryMethod'] = (!empty($arrResult['select']['oversea_deliveryMethod'][0]) || $arrResult['select']['oversea_deliveryMethod'][0] == '0') ? $arrResult['select']['oversea_deliveryMethod'] : explode('|', $arrInfo['oversea_deliveryMethod']);
		if (is_array($delivery)){
			$deliveryMethod .= "<table style=\"background:#F1F1F1\" cellpadding=4>";
			$oversea_deliveryMethod .= "<table style=\"background:#F1F1F1\" cellpadding=4>";
			
			$postage_query = "SELECT deliveryMethod, postage FROM aus_soc_bu_detail WHERE StoreID ='".$_SESSION['StoreID']."'";
			//echo $postage_query;
			$postage_query_result = $dbcon->execute_query($postage_query);
			$postage_result = $dbcon->fetch_records();
			
			$delivery_method = explode('|',$postage_result[0]['deliveryMethod']);
			$postage = explode('|',$postage_result[0]['postage']);
			
			//echo var_export($postage_result['postage']);

			foreach ($delivery as $key => $value){
				
				if (empty($value)) {
					continue;
				}
				if (!empty($arrResult['select']['deliveryMethod'][0]) || $arrResult['select']['deliveryMethod'][0] == '0') {
					$checked = in_array($key,$arrResult['select']['deliveryMethod'])?" checked='checked'":"";
				}
				
				$pos_key = array_search($key, $delivery_method);
				if ( $pos_key !== false ) { 
					$pos_value = isset($postage[$pos_key])? $postage[$pos_key] : '';
				} else {
					$pos_value = '';
				}
				
				$deliveryMethod .= "<tr><td>".
					"<input type='checkbox' class='ck_deliveryMethod' name='deliveryMethod[$key]' ".
					"$checked value='{$key}' title='{$value}' ".
					"onclick='enableCost(\"postage[$key]\",this);' />&nbsp;".($key=='5' ? '<strong>' : '')."{$value} ".(($key=='5' && false) ? '</strong>' : '').(($key=='5' && false) ? $codHelp_one : '')."</td>".
					"<td valign='middle'>&nbsp;&nbsp;";
				
				$deliveryMethod.= "$</td><td><input type='text' class='inputB input_postage' name='postage[$key]' ".($checked==""?"disabled":"")." value='$pos_value' style='width:40px;margin-right:5px;'>";
				
				$deliveryMethod.= "</td></tr>";

				$checked_over = in_array($key,$arrResult['select']['oversea_deliveryMethod'])?" checked='checked'":"";
				$pos_over_key = array_search($key,$arrResult['select']['oversea_deliveryMethod']);
				$pos_over_value = $checked_over!=""&&isset($arrResult['select']['oversea_postage'][$pos_over_key])?$arrResult['select']['oversea_postage'][$pos_over_key]:"";

				$oversea_deliveryMethod .= "<tr><td>".
				   "<input type='checkbox' class='ck_oversea_deliveryMethod' ".
					"name='oversea_deliveryMethod[$key]' $checked_over value='{$key}' ".
					"title='{$value}' onclick='enableCost(\"oversea_postage[$key]\",this);' ".
					"/>&nbsp;".(($key=='5' && false) ? '<strong>' : '')."{$value} ".($key=='5' ? '</strong>' : '').(($key=='5' && false) ? $codHelp_two : '')."</td><td valign='middle'>&nbsp;&nbsp;";
				if (1){
					$oversea_deliveryMethod.= "$</td><td><input type='text' class='inputB "
						."input_oversea_postage' name='oversea_postage[$key]' "
				   		.($checked_over==""?"disabled":"")." value='$pos_over_value' "
				   		."style='width:40px;margin-right:5px;'></td></tr>";
				}else{
					$oversea_deliveryMethod.= "</td><td style='height:22px;'><input type='hidden' class='inputB "
						."input_oversea_postage' name='oversea_postage[$key]' "
				   		.($checked_over==""?"disabled":"")." value='0' "
				   		."style='width:40px;margin-right:5px;'></td></tr>";
				}

			}
			$oversea_deliveryMethod .= "</table>";
			$deliveryMethod.= "</table>";
		}
		$arrResult['select']['deliveryMethod'] = $deliveryMethod;
		$arrResult['select']['oversea_deliveryMethod'] = $oversea_deliveryMethod;
		$arrResult['select']['sortby'] = $_REQUEST['sortby'];

		$productDescription = $arrResult['select']['description'];
		//$arrResult['select']['description'] = $this->initEditor('description', $productDescription);
		$objUI	=	new uploadImages();
		$arrResult['images']	=	$objUI -> getDisplayImage('auto',$StoreID,$_REQUEST['pid']);
		$query = "SELECT  * FROM {$this->table}product_attachment where pid = '$pid'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if(is_array($result)){
			$arrResult['upfiles'] = $result[0];
			$arrResult['upfiles']['filenewname']  = substr($result[0]['fileurl'],strrpos($result[0]['fileurl'],"/")+1);
		}
		unset($objUI);
		return $arrResult;
	}

	/**
	 * store product Add or Edit operate
	 *
	 * @return boolean
	 */
	function storeProductAddOrEditOperate(){
                global $time_zone_offset;
		$boolResult	=	false;
		$StoreID	=	$_SESSION['ShopID'];
		$pid	=	$_REQUEST['pid'];
		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);
		if ($cp == 'next' || $cp == 'save') {
			$strCondition ="where StoreID='$StoreID'";
			$arrSetting	= array(
			'OfferDelevery'	=>	"$OfferDelevery",
			'termnCon'		=>	"$termnContent"
			);
			$arrSetting['DeliveryPrice'] = $OfferDelevery == 'yes' ? "$DeliveryPrice" : "0";
			if($this -> dbcon-> checkRecordExist($this->table."deliverydetail",$strCondition)){
				$boolResult = $this->dbcon-> update_record($this->table."deliverydetail", $arrSetting, $strCondition);
			}else{
				$arrSetting['StoreID']	=	"$StoreID";
				$boolResult = $this->dbcon-> insert_record($this->table."deliverydetail", $arrSetting);
			}
		}elseif($cp == 'edit'){
			if (empty($stockQuantity)) {
				if ($on_sale == 'yes' or $on_sale == 'contact') {
					$stockQuantity = 1;
				}
			}
			if ($on_sale == 'sold' || $on_sale == 'no') {
				$stockQuantity = 0;
			}
			if($isattachment){
				if($fileurl==""){
					$stockQuantity = 0;
					$on_sale = "no";
				}else{
					if($on_sale=="yes"){
						$stockQuantity = 1;
						$on_sale = "yes";
					}else{
						$stockQuantity = 0;
						$on_sale = "no";
					}
				}
				$non = 0;
			}
			$strextra	=	implode('|=&&&&=|',$extra);
			$strCondition ="where StoreID='$StoreID' and pid='$pid'";
			$is_auction=='yes'?'yes':'no';
			if($is_auction=='yes'){$on_sale = "yes";}
            if(!isset($is_certified)){
                $is_certified = 0;
            }
			$arrSetting	=	array(
			"item_name"		=>	"$item_name",
			"url_item_name"	=> 	clean_url_name($item_name),
			"price"			=>	$is_auction=='yes'?$initial_price:"$price",
			"postage"		=>	trim(str_ireplace('||','|',implode('|',$postage)),'|'),
			"deliveryMethod"=>	trim(str_ireplace('||','|',implode('|',$deliveryMethod)),'|'),
			"unit"			=>	"$unit",
			"category"		=>	$categorySub ? "$categorySub" : $category ,
			"custom_subcat"	=>	$custom_subcat,
			"description"	=>	trim(formatTextarea(strip_tags("$description"))),
			"p_code"		=>	"$p_code",
			"payments"		=>	is_array($payments) ? serialize($payments) : "",
			'non'			=>	$non ? $non : '0',
			'is_auction'	=>	$is_auction=='yes'?'yes':'no',
			'on_sale'		=>	$on_sale,
			'stockQuantity'	=>	$stockQuantity,
			'isfeatured'	=>	$isfeatured?$isfeatured:"0",
			'isoversea'		=>  $isoversea?$isoversea:"0",
			'youtubevideo'  =>  $youtubevideo,
			'extra'			=>  $strextra,
            'is_certified'  =>  $is_certified
			);
			if($is_auction=='yes'){
				$arrSetting['auct_celeb'] = $auct_celeb;
			}
			if($isoversea){
				$arrSetting['oversea_postage']= trim(str_ireplace('||','|',implode('|',$oversea_postage)),'|');
				$arrSetting['oversea_deliveryMethod']=trim(str_ireplace('||','|',implode('|',$oversea_deliveryMethod)),'|');
			}else{
				$arrSetting['oversea_postage']= "";
				$arrSetting['oversea_deliveryMethod']='';
			}
			$arrSetting['isattachment'] = $isattachment?"1":"0";

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

			// check image upload and add data to product array
			/* comment by Jessee 20081105 for uploading check
			"image_name"	=>	"$mainImageH",
			"moreImage1"	=>	"$moreImage1",
			"moreImage2"	=>	"$moreImage2",
			"moreImage3"	=>	"$moreImage3",
			"moreImage4"	=>	"$moreImage4",
			"moreImage5"	=>	"$moreImage5",
			"moreImage6"	=>	"$moreImage6",

			if ($fileUpload=='YES'){
			$arrSetting['image_name'] = $mainImageH;
			}
			for($i==1;$i<7;$i++){
			if ($_REQUEST['mImg'.$i]=='YES'){
			$arrSetting['moreImage'.$i] = $_REQUEST['moreImage'.$i];
			}
			}*/

			if($this -> dbcon-> checkRecordExist($this->table."product",$strCondition)){
				$tfbl=true;
				if ($is_auction=='yes'){
					list($day,$month,$year) = split('/',$end_date);
					$now_time=time();
					$end_stamp = mktime($end_hour,$end_minute,0,$month,$day,$year);
                                        $arr_start_date=explode('/',$start_date);
                                        $starttime_stamp=mktime($start_hour,$start_minute,0,$arr_start_date[1],$arr_start_date[0],$arr_start_date[2]);
					if ($end_stamp-$now_time<=0 or $end_stamp<=$starttime_stamp) {
						$msg = "The end time is not valid.";
						$tfbl = false;
					}
				}
				if($tfbl){
					$arrSetting['datem']	= time();
					$sql = "select product.is_auction,auction.status from ".$this->table."product as product,";
					$sql.= $this->table."product_auction as auction where product.pid=auction.pid and auction.pid=$pid";
					$this->dbcon->execute_query($sql);
					$auction = $this->dbcon->fetch_records();
					$auction = $auction[0];
					if ($auction['is_auction']=='yes' and $auction['status']!=0){
						$msg = "Can not update an item which bidding is in progress.";
						$boolResult = false;
					}else{
						$boolResult = $this->dbcon->update_record($this->table."product", $arrSetting, $strCondition);
						$pro_tags = new producttag();
						$pro_tags->save_pro_tags($str_tags,$pid,0);
						if($isattachment){
							if(!file_exists(ROOT_PATH.'upload/proupload/')){
								@mkdir(ROOT_PATH.'upload/proupload/');
							}
							$query = "SELECT * from {$this->table}product_attachment where pid='$pid'";
							$this->dbcon->execute_query($query);
							$result = $this->dbcon->fetch_records(true);
							if(is_array($result)&&count($result)>0){
								if($filenewname==""||$fileurl==""){
									if($fopt){
										@unlink(ROOT_PATH.$result[0]['fileurl']);
										$att_array = array('filename'=>"",'fileurl'=>"",'lastupdate'=>time());
									}
								}else{
									if($result[0]['fileurl']!=$fileurl){
										if(@copy(ROOT_PATH.$fileurl,ROOT_PATH.'upload/proupload/'.$filenewname)){
											@unlink(ROOT_PATH.$fileurl);
											$fileurl = '/upload/proupload/'.$filenewname;
										}else{
											$fileurl = $result[0]['fileurl'];
											$filename = $result[0]['filename'];
 										}
									}else{
										$fileurl = $result[0]['fileurl'];
									}
									$att_array = array('filename'=>"$filename",
											   'fileurl'=>"$fileurl",
											   'lastupdate'=>time());
								}
								$boolResult = $this->dbcon->update_record($this->table."product_attachment",$att_array,"where pid='$pid'");
							}else{
								if(@copy(ROOT_PATH.$fileurl,ROOT_PATH.'upload/proupload/'.$filenewname)){
									@unlink(ROOT_PATH.$fileurl);
									$fileurl = '/upload/proupload/'.$filenewname;
									$att_array = array();
									$att_array['filename']	 =$filename;
									$att_array['fileurl']	 =$fileurl;
									$att_array['lastupdate'] = time();
									$att_array['createtime'] = time();
									$att_array['pid']		 = $pid;
									$boolResult = $this->dbcon->insert_record($this->table."product_attachment",$att_array);
								}
							}
						}
						if ($auction['is_auction']=='yes'){
							list($day,$month,$year) = split('/',$end_date);
							$end_stamp = mktime($end_hour,$end_minute,0,$month,$day,$year);
                                                        $arr_start_date=explode('/',$start_date);
                                                        $starttime_stamp=mktime($start_hour,$start_minute,0,$arr_start_date[1],$arr_start_date[0],$arr_start_date[2]);
							$arrAuction = array(
							"initial_price"	=>	intval(substr($initial_price,0,9)),
							"end_date"		=>	"$year-$month-$day",
							"end_time"		=>	"$end_hour:$end_minute",
							"end_stamp"		=>	"$end_stamp",
							"reserve_price"	=>	intval(substr($reserve_price?$reserve_price:0,0,9)),
							"cur_price"		=>	intval(substr($initial_price,0,9)),
                                                        "starttime_stamp"       =>      "$starttime_stamp"
							);
							$boolResult = $this->dbcon->update_record($this->table."product_auction", $arrAuction,"where pid=$pid");
						}
					}
				}
			}else {
				$tfbl=true;
				if ($is_auction=='yes'){
					list($day,$month,$year) = split('/',$end_date);
                                        $now_time=time();
					$end_stamp = mktime($end_hour,$end_minute,0,$month,$day,$year);
                                        $arr_start_date=explode('/',$start_date);
                                        $starttime_stamp=mktime($start_hour,$start_minute,0,$arr_start_date[1],$arr_start_date[0],$arr_start_date[2]);
					if ($end_stamp-$now_time<=0 or $end_stamp<=$starttime_stamp) {
						$msg = "The end time is not valid.";
						$tfbl = false;
					}
				}
				if($tfbl){
					$arrSetting['StoreID']	= "$StoreID";
					$arrSetting['datec']	= time();
					$arrSetting['datem']	= $arrSetting['datec'];
					// fix the error quantity when copy from a sold auction item, by jessee 20100507
					if($is_auction=='yes'){
						$arrSetting['stockQuantity'] = 1;
					}
					$boolResult = $this->dbcon->insert_record($this->table."product", $arrSetting);
					$pid = $this->dbcon->insert_id();
					$pro_tags = new producttag();
					$pro_tags->save_pro_tags($str_tags,$pid,0);
					if($isattachment){
							if(!file_exists(ROOT_PATH.'upload/proupload/')){
								@mkdir(ROOT_PATH.'upload/proupload/');
							}
							$query = "SELECT * from {$this->table}product_attachment where pid='$pid'";
							$this->dbcon->execute_query($query);
							$result = $this->dbcon->fetch_records(true);
							if(is_array($result)&&count($result)>0){
								if($filenewname==""||$fileurl==""){
									if($fopt){
										@unlink(ROOT_PATH.$result[0]['fileurl']);
										$att_array = array('filename'=>"",'fileurl'=>"",'lastupdate'=>time());
									}
								}else{
									if($result[0]['fileurl']!=$fileurl){
										if(@copy(ROOT_PATH.$fileurl,ROOT_PATH.'upload/proupload/'.$filenewname)){
											@unlink(ROOT_PATH.$fileurl);
											$fileurl = '/upload/proupload/'.$filenewname;
										}else{
											$fileurl = $result[0]['fileurl'];
											$filename = $result[0]['filename'];
 										}
									}else{
										$fileurl = $result[0]['fileurl'];
									}
									$att_array = array('filename'=>"$filename",
											   'fileurl'=>"$fileurl",
											   'lastupdate'=>time());
								}
								$boolResult = $this->dbcon->update_record($this->table."product_attachment",$att_array,"where pid='$pid'");
							}else{
								if(@copy(ROOT_PATH.$fileurl,ROOT_PATH.'upload/proupload/'.$filenewname)){
									@unlink(ROOT_PATH.$fileurl);
									$fileurl = '/upload/proupload/'.$filenewname;
									$att_array = array();
									$att_array['filename']	 =$filename;
									$att_array['fileurl']	 =$fileurl;
									$att_array['lastupdate'] = time();
									$att_array['createtime'] = time();
									$att_array['pid']		 = $pid;
									$boolResult = $this->dbcon->insert_record($this->table."product_attachment",$att_array);
								}
							}
					}
					if ($is_auction=='yes' and $pid > 0){
						list($day,$month,$year) = split('/',$end_date);
						$end_stamp = mktime($end_hour,$end_minute,0,$month,$day,$year);
                                                $arr_start_date=explode('/',$start_date);
                                                $starttime_stamp=mktime($start_hour,$start_minute,0,$arr_start_date[1],$arr_start_date[0],$arr_start_date[2]);
						$arrAuction = array(
							"pid"			=>	"$pid",
							"initial_price"	=>	intval(substr($initial_price,0,9)),
							"end_date"		=>	"$year-$month-$day",
							"end_time"		=>	"$end_hour:$end_minute",
							"end_stamp"		=>	"$end_stamp",
							"reserve_price"	=>	intval(substr($reserve_price?$reserve_price:0,0,9)),
							"status"		=>	"0",
							"cur_price"		=>	intval(substr($initial_price,0,9)),
                                                        "starttime_stamp"       =>      "$starttime_stamp"
						);
						$boolResult = $this->dbcon->insert_record($this->table."product_auction", $arrAuction);
					}

                                        /**
                                         * added by YangBall, 2011-07-05
                                         * referrer new rule
                                         */
                                         require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
                                         $referrer = new Referrer();
                                         $referrer->addReferrerRecord('product', $_SESSION['StoreID']);

                                        //END-YangBall
				}

			}
			//modify picture, added by jessee 20081230
			if ($boolResult) {
				$Pid	=	$pid ;
				$objUI	=	new uploadImages();
				$arrSetting	=	array(
				'0' => array('simage'=> $_REQUEST['mainImage_svalue'], 'bimage'=> $_REQUEST['mainImage_bvalue'])
				);
				$objUI -> setDisplayImage('auto',$arrSetting,$StoreID,$Pid,0,0);

				$arrSetting	=	array(
				'0' => array('simage'=> $_REQUEST['subImage0_svalue'], 'bimage'=> $_REQUEST['subImage0_bvalue']),
				'1' => array('simage'=> $_REQUEST['subImage1_svalue'], 'bimage'=> $_REQUEST['subImage1_bvalue']),
				'2' => array('simage'=> $_REQUEST['subImage2_svalue'], 'bimage'=> $_REQUEST['subImage2_bvalue']),
				'3' => array('simage'=> $_REQUEST['subImage3_svalue'], 'bimage'=> $_REQUEST['subImage3_bvalue']),
				'4' => array('simage'=> $_REQUEST['subImage4_svalue'], 'bimage'=> $_REQUEST['subImage4_bvalue']),
				'5' => array('simage'=> $_REQUEST['subImage5_svalue'], 'bimage'=> $_REQUEST['subImage5_bvalue']),
				);
				$objUI -> setDisplayImage('auto', $arrSetting, $StoreID, $Pid, 1, 0);
				unset($objUI);
			}
             /**
             * added by Kevin.Liu, 2012-02-16
             * point new rule
             */

        	 include_once(SOC_INCLUDE_PATH . '/class.point.php');
             $objPoint = new Point();
             $objPoint->addPointRecords($_SESSION['StoreID'], 'year', $pid);

            //END
			if ($boolResult) {
				$msg = 'Product saved successfully. ';
			}else {
				$msg = $msg?$msg:'Record saved failed. ';
			}
		}elseif ($cp=='upload'){
			//echo "Starting Import";
			// upload the products with csv and images (zip format)
			$csvfile = $_REQUEST['swf_csv'];
			$imgfile = $_REQUEST['swf_img'];

			$_FILES['csv']['size'] = filesize($csvfile);
			$_FILES['image']['size'] = filesize($imgfile);

                        $allNotImage = array('ebay_turbolister');
//			if(($csvfile==""||$imgfile=="") or (in_array($_POST['rdo_import_type'], $allNotImage) and $csvfile != "")){
                        if($csvfile == '' or (!in_array($_POST['rdo_import_type'], $allNotImage) and $imgfile == '')) {
				$msg = $GLOBALS['multi_msg'][0];
			}elseif($_FILES['csv']['size']+$_FILES['image']['size']>83400320){
				$msg = $GLOBALS['multi_msg'][11];
			}else{

				$_FILES['csv']['tmp_name'] = $csvfile;
				$_FILES['csv']['name'] = "upload.csv";
				$_FILES['image']['tmp_name'] = $imgfile;
				$_FILES['image']['name'] = "upload.zip";

				if ($_FILES['csv']['size']==0){
					$msg = $GLOBALS['multi_msg'][0];
				}elseif (strtolower(substr($_FILES['csv']['name'],-3,3))!='csv'){
                                    $msg = $GLOBALS['multi_msg'][1];
				}
                                elseif($_FILES['image']['name']!='' and strtolower(substr($_FILES['image']['name'],-3,3))!='zip') {
					$msg = $GLOBALS['multi_msg'][1];
                                }else{
					$products = $this->getProductCSV($_FILES['csv']);
					if (!$products){
						$msg = $GLOBALS['multi_msg'][2];
					}else{
                                            /*
                                             *  Import BuyNow Product  OR Auction
                                             *  by Yang Ball 2010-07-20
                                             *  Bug #5723
                                             */
                                                if($_POST['rdo_import_type']=='buynow') {
                                                    list($success,$fail) = $this->importProducts($products,$_FILES['image']);
                                                }
                                                elseif('auction' == $_POST['rdo_import_type']) {
                                                    list($success,$fail) = $this->importAuctionProduct($products,$_FILES['image']);
                                                }
                                                elseif('ebay_turbolister' == $_POST['rdo_import_type']) {
                                                    include_once(SOC_INCLUDE_PATH . '/class.ebayUpload.php');
                                                    $ebayUpload = new EbayUpload();
                                                    list($success,$fail) = $ebayUpload->turbolister($products,$_FILES['image']);
                                                }
                                                elseif('ebay_blackthorn' == $_POST['rdo_import_type']) {
                                                    include_once(SOC_INCLUDE_PATH . '/class.ebayUpload.php');
                                                    $ebayUpload = new EbayUpload();
                                                    list($success,$fail) = $ebayUpload->blackthorn($products,$_FILES['image']);
                                                }

                                                // END
						$boolResult = true;
						$msg = "$success records imported successfully, $fail records imported failed.";

						if($fail==='all'){
							$msg = "The titles in the csv don&#039;t match the standardized titles completely. Please check the csv.";
						}elseif ($fail>0){
							$this->setFormInuptVar(array('error_more'=>"&nbsp;<a href='#' onclick='javascript:window.open(\"/multi_msg.php\",\"multerr\",\"width=600,height=400,scrollbars=yes,status=yes\");location.href=\"/soc.php?act=signon&step=4\";'>Click here show more!</a>"));
						}
					}
				}
				@unlink($csvfile);
				@unlink($imgfile);
			}
			//exit;
		}elseif ($cp == 'del' || $_REQUEST['cp'] == 'del'){
			$strCondition ="where StoreID='$StoreID' and pid='$pid'";
			if($this -> dbcon-> checkRecordExist($this->table."product",$strCondition)){
				$p_id = $pid;
				$boolResult = $this->dbcon-> insert_record($this->table."product", $arrSetting);
				$pid = $this->dbcon->insert_id();
				if ($is_auction=='yes'){
					$boolResult = false;
				}else{
					$arrSetting	= array(
					'deleted'	=>	'YES'
					);
					$boolResult = $this->dbcon-> update_record($this->table."product", $arrSetting, $strCondition);
				}
			}
			if ($boolResult) {
	            /**
	             * added by Kevin.Liu, 2012-02-16
	             * reduce point new rule
	             */
	        	 include_once(SOC_INCLUDE_PATH . '/class.point.php');
	             $objPoint = new Point();
	             $objPoint->addPointRecords($StoreID, 'product', $p_id, true);
	            //END

				$msg	=	'Record deleted successfully. ';
			}else {
				$msg	=	'Record deleted failed. ';
			}
		}
		$_SESSION["pageParam"]["msg"] ="";
		$this -> addOperateMessage($msg);
		return $boolResult;
	}

	/* get StoreID with the url website name */
	function getStoreIDbyName($name,$isexprite=true){
		$name = mysql_escape_string($name);
		//$_query = "select StoreID from ".$this->table."login where store_name='".$name."'";
		$_query = "select t1.StoreID,t2.* from ".$this->table."login as t1 left join ".$this->table."bu_detail as t2 on t1.StoreID=t2.StoreID
		where t1.store_name='".$name."'";

		$this->dbcon->execute_query($_query);
		$count = $this->dbcon->count_records();
		if ($count == 0){
			$_query = "select StoreID from {$this->table}bu_detail where store_name='$name'";
			$this->dbcon->execute_query($_query);
			$result = $this->dbcon->count_records();
			if($result&&$_SESSION['ShopID']){
				foreach ($result as $pass){
					if($pass['StoreID']==$_SESSION['ShopID']){
						return $_SESSION['ShopID'];
					}
				}
			}
			return false;
		}else{
			$row = $this->dbcon->fetch_records();
			if($isexprite){
				if($row[0]['renewalDate']>time()){
					return $row[0]['StoreID'];
				}elseif($row[0]['attribute']==3&&$row[0]['subAttrib']){
					return $row[0]['StoreID'];
				}elseif($row[0]['StoreID']==$_SESSION['ShopID']){
					return $row[0]['StoreID'];
				}else{
					return false;
				}
			}else{
				return $row[0]['StoreID'];
			}
		}
	}

	/**check this site is suspend**/
	function IsSuspendbyStoreID($StoreID){
		$query = "SELECT count(*) FROM {$this->table}login where StoreID='{$StoreID}' and suspend=1 ";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(false);
		if($result[0][0]){
			return false;
		}
		return true;
	}
	function isexpritebyStoreID($StoreID){
		$query = "SELECT * FROM {$this->table}bu_detail where StoreID='{$StoreID}'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if($result[0]['renewalDate']>time()){
			return true;
		}elseif($result[0]['StoreID']==$_SESSION['ShopID']){
			return true;
		}elseif($result[0]['attribute']==3&&$result[0]['subAttrib']==3){
			return true;
		}else{
			return false;
		}
	}
	/*get item ID by item name*/
	function getItemIDbyName($StoreID, $name){
		$intResult	=	false;

		$_query	=	"select attribute, subAttrib from ".$this->table."bu_detail where StoreID='$StoreID'";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);

		if (is_array($arrTemp)) {
			$name = mysql_escape_string($name);
			$_where	=	"where StoreID=$StoreID and url_item_name='".$name."'";
			if ($arrTemp[0]['attribute']==1) {
				$_query = "select pid from ".$this->table."product_realestate $_where and deleted = 0 and (pay_status=2 or (pay_status=1 and renewal_date >= '".time()."')) ";
			}elseif ($arrTemp[0]['attribute']==2) {
				$_query = "select pid from ".$this->table."product_automotive $_where and deleted = 0 and (pay_status=2 or (pay_status=1 and renewal_date >= '".time()."')) ";
			}elseif ($arrTemp[0]['attribute']==3) {
				$current_date = date("Y-m-d");
				$_query = "select pid from ".$this->table."product_job $_where and deleted = 0 and (closingDate>='$current_date' or closingDate='0000-00-00') ";
				if ($arrTemp[0]['subAttrib']!=3) {
					$_query .= " and (pay_status=2 or (pay_status=1 and renewal_date >= '".time()."')) ";
				}
			}elseif ($arrTemp[0]['attribute']==5) {
				$current_date = date("Y-m-d");
				$_query = "select pid from ".$this->table."product_foodwine $_where and deleted = 0 ";
			}else{
				$_query = "select pid from ".$this->table."product $_where and deleted!='YES'";
			}

			$this->dbcon->execute_query($_query);
			$count = $this->dbcon->count_records();
			if ($count > 0){
				$row = $this->dbcon->fetch_records();
				$intResult = $row[0]['pid'];
			}
		}

		return $intResult;
	}

	/* check product name */
	function checkProductName($name,$StoreID,$pid=0){
		if(strtolower(clean_url_name($name))=='wishlist'||strtolower(clean_url_name($name))=='gallery'){
			return true;
		}
		$name = clean_url_name($name);
		$sql = "select count(*) as num from ".$this->table."product where url_item_name='$name' and StoreID='$StoreID' and deleted!='YES'";
		if ($pid!=0){
			$sql.= " and pid!=$pid";
		}
		$this->dbcon->execute_query($sql);
		$num = $this->dbcon->fetch_records();
		//echo "num:".$num[0]['num'].";$sql\n<br>";
		return ($num[0]['num']>0)?true:false;
	}

	function getCodeProduct($buyerid,$StoreID,$pid,$coupon){
		if(!get_magic_quotes_gpc()){
			$buyerid 	= addslashes($buyerid);
			$StoreID 	= addslashes($StoreID);
			$pid 		= addslashes($pid);
			$coupon 	= addslashes($coupon);
		}
		$query = "SELECT * FROM {$this->table}obo_offer where StoreID='$StoreID' and UserID='$buyerid' and pid='$pid' and coupon_code='$coupon' and accpet='1' and coupon_used=0";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if($result){
			return $result[0];
		}else{
			return false;
		}
	}

	function updateCouponCode($userid,$StoreID,$pid,$coupon){
		$query = "update {$this->table}obo_offer set coupon_used=1 where StoreID='$StoreID' and UserID='$userid' and pid='$pid' and coupon_code='$coupon'";
		return $this->dbcon->execute_query($query);
	}


        /**
         * @author  YangBall, 2011-01-20
         * @param   int $storeID seller store id
         * @param   bool    $getResultArray  get the result for database
         * @desc    get seller is click the step save
         * @return array('step1'=>bool,'step2'=>bool.....'step4'),  save:true, no save:false
         */
        public function getSellerStepContinueButtonStatus($storeID=0,$getResultArray=false)
        {
            $strSql = 'SELECT `bs_step_click` FROM ' . $this->table . 'bu_detail WHERE `StoreID` = ' . intval($storeID);
            $this->dbcon->execute_query($strSql);
            $result = $this->dbcon->fetch_records(true);
            if(null == $result[0]) {
                return false;
            }
            $arr = explode(',',$result[0]['bs_step_click']);

            //if $getResultArray, return result array
            if($getResultArray) {
                return $arr;
            }

            return array(
                'step1' =>  in_array(1, $arr) ? true : false,
                'step2' =>  in_array(2, $arr) ? true : false,
                'step3' =>  in_array(3, $arr) ? true : false,
                'step4' =>  in_array(4, $arr) ? true : false
            );
        }

        /**
         * @author  YangBall, 2011-01-20
         * @param
         *
         */
        public function setSellerStepContinueButtonStatus($storeID=0, $stepNumber=0)
        {
            $stepNumber = intval($stepNumber);
            //check step
            if(!in_array($stepNumber, array(1,2,3,4))) {
                return false;
            }
            $arr = $this->getSellerStepContinueButtonStatus($storeID, true);
            //no exists record
            if(false == $arr) {
                return false;
            }
            //is click , return
            if(in_array($stepNumber, $arr)) {
                return true;
            }
            $arr[] = $stepNumber;
            $str = implode(',', $arr);

            //update
            $strSql = 'UPDATE ' . $this->table . 'bu_detail SET `bs_step_click` = "' . $str . '" WHERE StoreID = ' . intval($storeID);
            return $this->dbcon->execute_query($strSql);
        }

        public function getProductFee($attribute=0, $feetype='product', $quantity=1, $month=1)
        {
        	$fee = 0;
        	if ($attribute == '1' || $attribute == '3') {
        		switch ($feetype) {
        			case 'month':
        				$fee = 10 * $quantity * $month;
        				break;
        			case 'year':
        				$fee = 120;
        				break;
        			default:
        				break;
        		}
        	} elseif ($attribute == '2') {
				switch ($feetype)
				{
					case 'product':
						$fee = 10 * $quantity;
						break;
					default:
						$fee = 120;
				}
	        } elseif ($attribute == '5') {
	        	$fee = 120;
	        }

			return $fee;
        }

        public function productActive($StoreID=0, $feetype='product', $pid_ary=array(), $month=1, $hasEmail=true, $payment_info=array())
        {
        	global $email_regards,$normal_url;
        	
        	if (empty($StoreID) || empty($feetype) || ($feetype == 'product' && empty($pid_ary))) {
        		return false;
        	}

        	$sql = "SELECT attribute,ispayfee,product_renewal_date FROM {$this->table}bu_detail WHERE StoreID='$StoreID'";
        	$store_info = $this->dbcon->getOne($sql);
        	if (empty($store_info['attribute'])) {
        		return false;
        	} else {
        		$product_talbe = '';
	        	switch ($store_info['attribute']) {
	        		case '0':
	        			$product_talbe = 'product';
	        			break;
	        		case '1':
	        			$product_talbe = 'product_realestate';
	        			break;
	        		case '2':
	        			$product_talbe = 'product_automotive';
	        			break;
	        		case '3':
	        			$product_talbe = 'product_job';
	        			break;
	        		case '5':
	        		default:
	        			$product_talbe = 'product_foodwine';
	        			break;
	        	}
	        	if ($feetype == 'year') {
	        		$pid_ary = array();
	        		$query = "select pid from {$this->table}{$product_talbe} where StoreID='$StoreID' and pay_status != 2 and deleted='0'";
					$result = $this->dbcon->execute_query($query);
					$res = $this->dbcon->fetch_records(true);
					foreach ($res as $p) {
						$pid_ary[] = $p['pid'];
					}
	        	}

	        	$product_list = array();
	        	//Active Store
	        	foreach ($pid_ary as $pid) {
		        	$sql = "SELECT * FROM {$this->table}{$product_talbe} WHERE StoreID='$StoreID' AND pid='$pid'";
		        	$res = $this->dbcon->getOne($sql);
		        	$intOldDate = $res['renewal_date'] ? $res['renewal_date'] : 0;

	        		$arrProductSetting['pay_status'] = '2';
	        		$arrProductSetting['pay_date'] = time();
	        		$arrProductSetting['renewal_date'] = '0';
	        		if ($feetype == 'month') {
	        			$arrProductSetting['pay_status'] = '1';
	        			$arrProductSetting['renewal_date'] = $this->__paymentActiveTime($month, $intOldDate);
	        		} elseif ($feetype == 'year') {
	        			$arrProductSetting['pay_status'] = '1';
	        			$arrProductSetting['renewal_date'] = $this->__paymentActiveTime(12, $intOldDate);
	        		}

	        		$resultTmp=$this->dbcon->update_record($this->table.$product_talbe,$arrProductSetting,'where pay_status != 2 and StoreID="'.$StoreID.'" and pid="'.$pid.'"');

		        	$res['expiring_date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$arrProductSetting['renewal_date']);
		        	$product_list[] = $res;
		        	unset($res);

		        	/**
                     * added by Kevin.Liu, 2012-02-16
                     * point new rule
                     */
                    if ($feetype != 'year' || 1) {
                    	include_once(SOC_INCLUDE_PATH . '/class.point.php');
                         $objPoint = new Point();
                         $objPoint->addPointRecords($StoreID, $feetype, $pid);
                    }
                    //END
	        	}

	        	//Active Store
        		if ($feetype == 'year') {
	        		$arrStoreSetting['product_renewal_date'] = $this->__paymentActiveTime(12, $store_info['product_renewal_date']);					$arrStoreSetting['product_feetype'] = $feetype;
	        		$arrStoreSetting['product_renewal_time'] = time();
	        		$resultTmp=$this->dbcon->update_record($this->table.'bu_detail',$arrStoreSetting,'where StoreID="'.$StoreID.'"');
        		}

        		$is_single_paid = $feetype == 'year' ? 0 : 1;
        		$this->dbcon->update_record($this->table.'bu_detail',array('ispayfee'=>1, 'is_single_paid'=>$is_single_paid),'where StoreID="'.$StoreID.'"');
        		$_SESSION['ispayfee'] = 1;

		        //auto, estate, career expired or Single paid reset Template A
		        $this->resetTemplate($StoreID);

        		//Send Email
        		if ($hasEmail) {
        			
        			//If the first time to pay fee, then send the welcome email to user
        			if (empty($store_info['ispayfee'])) {
        				$QUERY		=	"select b.*,l.password from ".$this->table."bu_detail b left join ".$this->table."login l on b.StoreID=l.StoreID where b.StoreID='".$StoreID."'";
						$result		=	$this->dbcon->execute_query($QUERY) ;
						$erlt 		=	$this->dbcon->fetch_records() ;
				
						$arrParams['attribute'] 	= $erlt[0]['attribute'];
						$arrParams['subAttrib'] 	= $erlt[0]['subAttrib'];
						$arrParams['bu_user'] 		= $erlt[0]['bu_email'];
						$arrParams['bu_password'] 	= $erlt[0]['password'];
						$arrParams['seller_name'] 	= stripslashes(str_replace("''", "'", $erlt[0]['bu_name']));
				
						$arrParams['bu_username'] 	= $erlt[0]['bu_username'];
						$arrParams['bu_name'] 		= $erlt[0]['bu_name'];
						$arrParams['bu_email'] 		= $erlt[0]['bu_email'];
						$arrParams['bu_nickname'] 	= $erlt[0]['bu_nickname'];
						$arrParams['bu_address'] 	= $erlt[0]['bu_address'];
						$arrParams['bu_suburb'] 	= $erlt[0]['bu_suburb'];
						$arrParams['bu_area'] 		= $erlt[0]['bu_area'];
						$arrParams['bu_phone'] 		= $erlt[0]['bu_phone'];
						$arrParams['bu_postcode'] 	= $erlt[0]['bu_postcode'];
						$arrParams['bu_procode'] 	= $erlt[0]['bu_procode'];
						$arrParams['product_feetype'] 		= $erlt[0]['product_feetype'];
						$arrParams['product_renewal_date'] 	= $erlt[0]['product_renewal_date'];
						$arrParams['amount']		= 12;
						$arrParams['display']		= 'regedit';
						$arrParams['To']			= $erlt[0]['bu_email'];
				
						$arrParams['Subject']	=	'Welcome To SOC Exchange' .($arrParams['attribute'] == 1?' - Real Estate':($arrParams['attribute'] == 2?' - Auto':($arrParams['attribute'] == 3?' - Careers':($arrParams['attribute'] == 5?' - Food & Wine':' - Buy & Sell'))));
				
						$arrParams['stateName']		=	getStateByName($erlt[0]['bu_state']);
		
						$arrParams['hideExpDate'] = true;
						if ($arrParams['product_feetype'] == 'year') {
							$arrParams['hideExpDate'] = false;
							//$arrParams['cardNumber'] = $payment_info['cardNumber'];
							$dateupto = $arrParams['product_renewal_date'];
						}
						$arrParams['expiringDate']	=	$isUp ? 'No expire date' : date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$dateupto);
						$arrParams['email_regards'] =	'SOC exchange Australia';
						$arrParams['normal_url'] 	=	$normal_url;
						$objEmail	=	new emailClass();
				
						if($objEmail -> send($arrParams,'../email_userinfo.tpl')){
							$adminEmail = new emailClass();
							//			$toemail = "yoyo.mai@infinitytesting.com.au";
							//			$ccemail = "xiong.wu@infinitytesting.com.au";
							$arrParams['To']	=	'';								//to admin
							//			$arrParams['To']	=	$toemail;						//to admin
							//			$arrParams['Cc']	=	$ccemail; 						//to admin
							$arrParams['type']	=	'Seller Registration';
							$arrParams['cardNumber']	=	"PayPal";
				
							$adminEmail -> send($arrParams,'../email_toadmin.tpl', (THE_PORT=='3007' ? true:false));
							unset($adminEmail);
						}
						unset($objEmail);
        			}
        			
        			//Send the fee email to user
        			$socObj = new socClass();
					$arrParams = $socObj->getStoreInfo($StoreID);
	        		if ($feetype == 'year') {
	        			$arrParams['display']			=	'year';
						$arrParams['To']				=	$arrParams['bu_email'];
						$arrParams['Subject']			=	'Notification of One-year payment';
						$arrParams['seller_name']		=	$arrParams['bu_name'];
						$arrParams['reurl'] 			= 	urlencode(SOC_HTTPS_HOST.'soc.php?cp=sellerhome');
						$arrParams['month'] 			= 	$month;
						$arrParams['expiring_date']		=	date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)),$arrParams['product_renewal_date']);

						$objEmail	=	new emailClass();
						if($objEmail -> send($arrParams,'../email_product_fee.tpl')){
							/*$adminEmail = new emailClass();
							$arrParams['To']	=	'';//to admin
							//						$arrParams['To']	=	$this->toemail;			//to admin
							//						$arrParams['Cc']	=	$this->ccemail;			//to admin
							$arrParams['type']	=	'Upgrade';
							$arrParams['cardNumber']	=	$this -> displayCardNumber($arrParams['cardNumber']);
	                                                $arrParams['email_regards'] = $email_regards;
							$adminEmail -> send($arrParams,'email_toadmin.tpl', (THE_PORT=='3007' ? true:false));
							unset($adminEmail);*/
						}
						unset($objEmail);
	        		} elseif (!empty($product_list)) {
	        			$subject = 'Welcome to SOC exchange Single Item Listing - ';
	        			if ($arrParams['attribute'] == 1) {
	        				$subject .= 'Real Estate';	
	        			} elseif ($arrParams['attribute'] == 2) {
	        				$subject .= 'Auto';       				
	        			} elseif ($arrParams['attribute'] == 3) {
	        				$subject .= 'Careers';	 
	        			}
						$arrParams['display']			=	'product';
						$arrParams['To']				=	$arrParams['bu_email'];
						$arrParams['Subject']			=	$subject;
						$arrParams['seller_name']		=	$arrParams['bu_name'];
						$arrParams['attribute']			=	$arrParams['attribute'];
						$arrParams['list']				=	$product_list;
						$arrParams['month'] 			= 	$month;

						$objEmail	=	new emailClass();
						if($objEmail -> send($arrParams,'../email_product_fee.tpl')){
							/*$adminEmail = new emailClass();
							$arrParams['To']	=	'';//to admin
							//						$arrParams['To']	=	$this->toemail;			//to admin
							//						$arrParams['Cc']	=	$this->ccemail;			//to admin
							$arrParams['type']	=	'Upgrade';
							$arrParams['cardNumber']	=	$this -> displayCardNumber($arrParams['cardNumber']);
	                                                $arrParams['email_regards'] = $email_regards;
							$adminEmail -> send($arrParams,'email_toadmin.tpl', (THE_PORT=='3007' ? true:false));
							unset($adminEmail);*/
						}
						unset($objEmail);
					}
        		}
        	}

        	return true;
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
			if ($money > 0 and $money < 12){
				$intResult	=	mktime(0, 0, 0, date("m",$intOldDate)+$money, date("d",$intOldDate),   date("Y",$intOldDate));
			}elseif ($money == 12) {
				$intResult	=	mktime(0, 0, 0, date("m",$intOldDate), date("d",$intOldDate),   date("Y",$intOldDate)+1);
			}
		}else{
			if ($money > 0 and $money < 12){
				$intResult	=	mktime(0, 0, 0, date("m")+$money, date("d"),   date("Y"));
			}elseif ($money == 12) {
				$intResult	=	mktime(0, 0, 0, date("m"), date("d"),   date("Y")+1);
			}
		}

		return $intResult;
	}
	
    
    function updateStoreRef($StoreID=0, $ref_name='') {
    	if (empty($StoreID) && empty($ref_name)) {
    		return false;
    	}
    	
    	if (empty($StoreID) || empty($ref_name)) {
	    	$sql = "SELECT StoreID,ref_name FROM {$this->table}bu_detail WHERE is_popularize_store='0' AND CustomerType='seller' AND ";
			$sql .= empty($StoreID) ? "ref_name='$ref_name'" : "StoreID='$StoreID'";
			$store_info = $this->dbcon->getOne($sql);
			$StoreID 	= 	$store_info['StoreID'];
			$ref_name 	= 	$store_info['ref_name'];	
    	}
		
    	$sql = "SELECT COUNT(*) AS ref \n".
    		   "FROM {$this->table}bu_detail \n".
    		   "WHERE referrer='$ref_name' AND is_popularize_store='0' AND CustomerType='seller'";
    	$res = $this->dbcon->getOne($sql);
    	$ref = $res['ref'] ? $res['ref'] : 0;
    	unset($res);
    	
    	$sql .= " AND attribute='0'";
    	$res = $this->dbcon->getOne($sql);
    	$ref0 = $res['ref'] ? $res['ref'] : 0;
    	unset($res);
    	
    	$data = array(
    		'ref_name' => $ref_name,
    		'ref' => $ref,
    		'ref0' => $ref0,
    	);
    	
    	if ($StoreID && $ref > 0) {
    		$sql_where = "WHERE StoreID='$StoreID'";
	    	if ($this->dbcon->checkRecordExist($this->table.'store_referrer', $sql_where)) {
	    		$this->dbcon->update_record($this->table.'store_referrer', $data, $sql_where);
	    	} else {
	    		$data['StoreID'] = $StoreID;
	    		$this->dbcon->insert_record($this->table.'store_referrer', $data);
	    	}
    	} else {
    		return false;
    	}
    	
    	
    	return true;
    }
}
