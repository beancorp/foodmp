<?php
/**
 * Tue Nov 04 09:54:28 GMT 2008 09:54:28
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * offer admin of seller and webmaster
 * ------------------------------------------------------------
 * \include\class.offer.php
 */

class offerClass extends common {
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
	 * read price of product record.
	 *
	 * @return array
	 */
	function offerSet(){
		global $socObj;
		
		$arrResult	=	null;
		if ($this-> _notVar){
			$arrResult	=	$this -> getFormInputVar();

		}elseif (isset($_REQUEST['StoreID']) && isset($_REQUEST['pid'])) {
			//$_title = "pid, item_name, stockQuantity, StoreID, price as offer, p_code, postage";
			$_title = "pid, item_name, stockQuantity,price, StoreID, p_code, postage,isoversea,oversea_postage,deliveryMethod,oversea_deliveryMethod";
			$_query = "select $_title from ".$this->table."product where pid = '$_REQUEST[pid]' and StoreID='$_REQUEST[StoreID]' and non=1";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			if (is_array($arrTemp)) {
				$arrResult = $arrTemp[0];
				$arrResult['quantity']	=	1;
				$arrResult['offer']		=	0;
			}
			$arrResult['info'] = $socObj -> _displayStoreInfo($temp,$_REQUEST[StoreID]);
		}

		return $arrResult;
	}

	/**
	 * save offer record.
	 *
	 * @return boolean
	 */
	function offerSave(){
		$boolResult	=	false;
		$msg		=	'';
		$lang		= 	& $this->lang;

		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);

		if (empty($StoreID)) {
			$msg = $this->replaceLangVar($lang['pub_clew']['faild'],array('Your offer',$lang['operation']['submit']));
		}else{
			$arrSetting = array(
			'pid'		=>	"$pid",
			'StoreID'		=>	"$StoreID",
			'quantity'		=>	"$quantity",
			'offer'			=>	"$offer",
			'postage'		=>	"$postage",
			'UserID'		=>	$_SESSION['ShopID'],
			'shipping_method'=>	$deliveryMethod,
			'isoversea'		=>	$isoversea,
			'datec'			=>	time()
			);
			
			$boolResult	= $this->dbcon-> insert_record($this->table."obo_offer", $arrSetting);
		}

		if ($boolResult) {
			$msg = $this->replaceLangVar($lang['pub_clew']['successful'],array('Your offer',$lang['operation']['submit']));
			$msg = str_ireplace('successfully.','',$msg);
			$_query	=	"select bu_email,bu_nickname from ".$this->table."bu_detail where StoreID='$StoreID'" ;
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$arrTemp	=	$arrTemp[0];
				$arrParams	=	array(
				'display'			=>	'offer',
				'To'				=>	$arrTemp['bu_email'],
				'Subject'			=>	'Offer Alert From SOC Exchange',
				'seller_nickname'	=>	$arrTemp['bu_nickname'],
				'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST']
				);
				$objEmail	=	new emailClass();
				$objEmail -> send($arrParams,'email_offer.tpl');
				//$msg .= $objEmail -> msg;
				unset($objEmail);
			}

		}else {
			$msg = $this->replaceLangVar($lang['pub_clew']['faild'],array('Your offer',$lang['operation']['submit']));
		}

		$this -> addOperateMessage($msg);

		return $boolResult;
	}
	
	function checkCouponCode($str){
		$query = "SELECT * FROM {$this->table}obo_offer where coupon_code='$str'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if($result){
			return false;
		}else{
			return true;
		}
	}


	/**
	 * get list of offer
	 *
	 * @param int $pageno
	 * @return array
	 */
	public function offerList($pageno=1){
		$arrResult  = 	null;
		$perPage	=	10;
		$pageno		=	$pageno ? $pageno : 1;

		$sqlWhere = "where t1.StoreID='".$_SESSION['StoreID']."' AND t1.UserID = t2.StoreID AND t1.pid = t3.pid" ;
		$_query ="select count(*) from ".$this->table."obo_offer as t1, ".$this->table."bu_detail as t2,".$this->table."product as t3 $sqlWhere  order by t1.datec";

		$this -> dbcon -> execute_query($_query);
		$count	= $this -> dbcon -> fetch_records();
		$count	=	$count[0][0];

		($pageno * $perPage > $count) ? $pageno = ceil($count/$perPage) : '';
		$start	= ($pageno-1) * $perPage;

		if ($count) {
			$dateformat = str_replace('-','/',DATAFORMAT_DB);
			$_title = "t1.`id`,t1.`pid`,t1.`StoreID`,t1.`quantity`,t1.`offer`,t1.`postage`,DATE_FORMAT(FROM_UNIXTIME(t1.datec),'$dateformat') as datec,DATE_FORMAT(FROM_UNIXTIME(t1.`datep`),'$dateformat') as datep,DATE_FORMAT(FROM_UNIXTIME(t1.`dateReview`),'$dateformat') as dateReview,t1.`accpet`,t1.`contact`,t1.`is_new`,t1.`is_read`,t1.coupon_code,t1.coupon_used,t2.bu_nickname as buyerNickname,t2.bu_email, t3.p_code as productCode, t3.item_name as productName, t2.bu_email as buyerEmail ";

			$_query ="select $_title from ".$this->table."obo_offer as t1, ".$this->table."bu_detail as t2,".$this->table."product as t3 $sqlWhere order by t1.datec desc limit $start , $perPage";

			$this -> dbcon -> execute_query($_query);
			$arrTemp	= $this -> dbcon -> fetch_records(true);

			if (is_array($arrTemp)) {
				$arrResult['list'] = $arrTemp;

				$params = array(
				'perPage'    => "$perPage",
				'totalItems' => "$count",
				'currentPage'=> "$pageno",
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_offerList(\'%d\',\'tabledatalist\');return false;',
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
				$arrResult['perpage']	=  $perPage;
				unset($pager,$params);
			}
		}
		return $arrResult;
	}


	public function offerAccept($id, $isAccept=false){
		$strResult = $this->replaceLangVar($this->lang['pub_clew']['faild'],array('The offer',$isAccept?'accepted':'rejected'));

		if ($id) {
			do {
				$couponcode = randStr(10);
				$bl = $this->checkCouponCode($couponcode);
			}while (!$bl);
			$arrSetting = array(
			'accpet' => $isAccept ? '1': '2' ,
			'datep'	=> time(),
			'coupon_code' =>$couponcode
			);
			$strCondition ="where id='$id' and StoreID='$_SESSION[StoreID]'";
			if ($this -> dbcon -> update_record($this->table."obo_offer", $arrSetting, $strCondition)) {
				$strResult = $this->replaceLangVar($this->lang['pub_clew']['successful'],array('The offer',$isAccept?'accepted':'rejected'));
				if ($this-> offerSendMail($id, $isAccept)) {
					$strResult .= "\n\n".$this->replaceLangVar($this->lang['pub_clew']['sendsucce'],array('The email','the buyer'));
				}else{
					$strResult .= "\n\n".$this->replaceLangVar($this->lang['pub_clew']['sendfaild'],array('The email','the buyer'));
				}
			}
		}

		return $strResult;
	}


	function offerSendMail($id, $isAccept=false){
		$booleanResult = false;
		$dateformat = str_replace('-','/',DATAFORMAT_DB);

		$_where = "where t1.id = '$id' and t1.UserID=t2.StoreID and t1.pid=t3.pid ";
		$_title   = "t3.`url_item_name`, t1.`id`,t1.`pid`,t1.`StoreID`,t1.`UserID`,t1.`quantity`,t1.`postage`,t1.`offer`,DATE_FORMAT(FROM_UNIXTIME(t1.datec),'$dateformat') as datec,DATE_FORMAT(FROM_UNIXTIME(t1.`datep`),'$dateformat') as datep, t1.`accpet`,t1.coupon_code,t2.bu_nickname as buyerNickname, t2.bu_email as buyeremail, t3.p_code as productCode, t3.item_name as productName";

		$_query ="select $_title from ".$this->table."obo_offer as t1, ".$this->table."bu_detail as t2,".$this->table."product as t3 $_where limit 1";
		$booleanResult = $_query;
        
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		if (is_array($arrTemp)) {
			$arrTemp = $arrTemp[0];

			$strLinkUrl = 'http://'.$_SERVER['HTTP_HOST'].'/soc.php?act=offer&cp=review&id='.$arrTemp['id'].'&UserID='.$arrTemp['UserID'];
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=utf-8\r\n";
			$headers .= 'From: info@TheSOCExchange.com' . "\r\n";
			$subject 	= 'SOCExchange Offer';

			$arrParams 	= array(
            'base_url'          =>  $_SERVER['HTTP_HOST'],
			'display'			=>	$isAccept ? 'accept' : '',
			'subject'			=>	$subject,
			'buyer_nickname'	=>	$arrTemp['buyerNickname'],
			'offerTotal'		=>	$arrTemp['offer'] * $arrTemp['quantity'] + $arrTemp['postage'] * $arrTemp['quantity'],
			'reviewLink'		=>	$isAccept ? $strLinkUrl : '',
			'seller_name'		=>	getStoreByName($arrTemp['StoreID']),
			'seller_url'		=>	getStoreByURL($arrTemp['StoreID']),
            'item_url'          =>  $arrTemp['url_item_name'],
			'couponCode'		=>	$arrTemp['coupon_code']
			);
			$this-> smarty -> assign('req', $arrParams);
			$message =	$this -> smarty -> fetch('email_offer.tpl');
			$booleanResult = @mail($arrTemp['buyeremail'], $subject, getEmailTemplate($message), fixEOL($headers));

/*
                         *  @Author Yang Ball 2010-07-30
                         *  Bug #5402
                         *  Add Alert Email
                         */
                       /* $arrParams	=	array(
				'display'			=>	'conseller',
				'To'				=>	$arrTemp['buyeremail'],
				'Subject'			=>	'Message Alert From SOC Exchange',
				'seller_nickname'	=>	$arrTemp['buyerNickname'],
				'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST'],
				'email_regards'		=> ''
				);
                        $objEmail	=	new emailClass();
                        $objEmail -> send($arrParams,'email_contact_seller.tpl');   //Send Email Alert
*/
                        /*
                         *  END
                         */

			unset($arrTemp,$message);
		}
		return $booleanResult;
	}

	public function offerReview(){
		$arrResult = null;
		$arrResult['display'] 	= 'error';

		if (!empty($_REQUEST['id']) && !empty($_REQUEST['UserID'])) {
			$condition = "where id = '$_REQUEST[id]' and UserID='$_REQUEST[UserID]'";
			if ($this -> dbcon -> checkRecordExist($this->table."obo_offer",$condition)) {
				$condition = "where id = $_REQUEST[id] and UserID=$_REQUEST[UserID] and dateReview = 0";
				if ($this -> dbcon -> checkRecordExist($this->table."obo_offer",$condition)) {
					$arrResult['display'] 	= '';
					$arrResult['id'] 		= $_REQUEST['id'];
					$arrResult['UserID'] 	= $_REQUEST['UserID'];
					$arrResult['display'] 	= '';
				}else{
					$arrResult['msg']	= $this->lang['obooffer']['hasReview'];
				}
			}else{
				$arrResult['msg']	= $this->replaceLangVar($this->lang['pub_clew']['notexist'],array('The record'));
			}
		}else{
			$arrResult['msg']		= $this->replaceLangVar($this->lang['pub_clew']['notexist'],array('This Link'));
		}

		return $arrResult;
	}


	public function offerViewReview($id){
		$arrResult = null;

		if (!empty($id)) {
			$dateformat = str_replace('-','/',DATAFORMAT_DB);

			$_title = "id, contact, email, phone, comment, dateReview, DATE_FORMAT(FROM_UNIXTIME(`dateReview`),'$dateformat') as dateReview";
			$_query = "select $_title from ".$this->table."obo_offer where id=$id and StoreID='".$_SESSION['StoreID']."'";
			$this -> dbcon -> execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			if (is_array($arrTemp)) {
				$arrResult = $arrTemp[0];
			}
		}

		return $arrResult;
	}

	public function offerReviewSave($params){
		$strResult = $this->replaceLangVar($this->lang['pub_clew']['sendfaild'],array('Your message','the seller'));
		if (is_array($params)) {
			$_var 		= 	$this -> setFormInuptVar($params);
			extract($_var);
			if ($contact == 'email' && (empty($email) || trim($email) == '')) {
				$strResult = $this->replaceLangVar($this->lang['pub_clew']['required'],array($this->lang['obo']['ttEmail']));
			}elseif ($contact == 'phone' && (empty($phone) || trim($phone) == '')) {
				$strResult = $this->replaceLangVar($this->lang['pub_clew']['required'],array($this->lang['obo']['ttPhone']));
			}elseif (empty($comment) || trim($comment) == '') {
				$strResult = $this->replaceLangVar($this->lang['pub_clew']['required'],array($this->lang['obo']['ttComment']));
			}else{
				$strCondition = "where id='$id' and UserID='$UserID' and dateReview = 0";
                                $this->dbcon->execute_query('select * from '.$this->table.'obo_offer '.$strCondition);
                                $tmp=$this->dbcon->fetch_records();
                                
				if (count($tmp)==0) {
					$strResult	= $this->lang['obooffer']['hasReview'];
				}else{
					$arrSetting = array(
					'contact' 		=> $contact,
					'email'			=> $email,
					'phone'			=> $phone,
					'comment' 		=> $comment,
					'dateReview'	=> time()
					);
					if ($this -> dbcon -> update_record($this->table."obo_offer", $arrSetting, $strCondition)) {
						$strResult = $this->replaceLangVar($this->lang['pub_clew']['sendsucce'],array('Your message','the seller'));
                                                /*
                                                 *  @Author :   YangBall
                                                 *  @Date   :   2010-08-12
                                                 *  Bug #5402   Email Alert
                                                 */
                                                $this->dbcon->execute_query('select * from '.$this->table."obo_offer where id=\"$id\" and UserID=\"$UserID\"");
                                                $rs=$this->dbcon->fetch_records();
                                                $store_id=$rs[0]['StoreID'];
                                                $this->dbcon->execute_query('select * from '.$this->table.'bu_detail where StoreID="'.$store_id.'"');
                                                $rs=$this->dbcon->fetch_records();
                                                $arr=$rs[0];
                                                $headers  = "MIME-Version: 1.0\r\n";
                                                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                                                $headers .= 'From: '.'<noreply@TheSOCExchange.com>' . "\r\n";
                                                $message='Dear '.$arr['bu_nickname'].', <br/><br/>';
                                                $message.='You have received a private message for one offer. To read the message please log in to your account.<br/><br/>Sincerely,<br/>SOC Exchange';
                                                @mail($arr['bu_email'], 'Message Alert From SOC Exchange', getEmailTemplate($message),$headers);
                                                /*
                                                 *  END
                                                 *  YangBall
                                                 */
					}
				}
			}
			unset($_var);
		}

		return $strResult;
	}


	public function offerViewEmail($id){
		$arrResult	=	null;

		$dateformat = str_replace('-','/',DATAFORMAT_DB);

		$_where = "where t1.id = '$id' and t1.UserID=t2.StoreID and t1.pid=t3.pid ";
		$_title   = "t1.`id`,t1.`pid`,t1.`StoreID`,t1.`UserID`,t1.`quantity`,t1.`postage`,t1.`offer`,DATE_FORMAT(FROM_UNIXTIME(t1.datec),'$dateformat') as datec,DATE_FORMAT(FROM_UNIXTIME(t1.`datep`),'$dateformat') as datep, t1.`accpet`,t1.coupon_code,t1.coupon_used,t2.bu_nickname as buyerNickname, t2.bu_email as buyeremail, t3.p_code as productCode, t3.item_name as productName";

		$_query ="select $_title from ".$this->table."obo_offer as t1, ".$this->table."bu_detail as t2,".$this->table."product as t3 $_where limit 1";
		$booleanResult = $_query;

		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		if (is_array($arrTemp)) {
			$arrTemp = $arrTemp[0];
			$strLinkUrl = 'http://'.$_SERVER['HTTP_HOST'].'/soc.php?act=offer&cp=review&id='.$arrTemp['id'].'&UserID='.$arrTemp['UserID'];
			$arrResult 	= array(
			'display'			=>	$arrTemp['accpet']==1 ? 'accept' : 'notaccept',
			'subject'			=>	$subject,
			'buyer_nickname'	=>	$arrTemp['buyerNickname'],
			'offerTotal'		=>	$arrTemp['offer'] * $arrTemp['quantity'] + $arrTemp['postage'] * $arrTemp['quantity'],
			'reviewLink'		=>	$arrTemp['accpet']=1 ? $strLinkUrl : '',
			'seller_name'		=>	getStoreByName($arrTemp['StoreID']),
			'seller_url'		=>	getStoreByURL($arrTemp['StoreID']),
			'couponCode'		=>	$arrTemp['coupon_code'],
			'id'				=>	$arrTemp['id'],
			'coupon_used'		=>	$arrTemp['coupon_used']
			);
		}

		return $arrResult;
	}


	function offerDelete($objHTML){
		$strResult	=	$this->replaceLangVar($this->lang['pub_clew']['faild'],array('Buyer\'s offer',$this->lang['operation']['delete']));

		if (is_array($objHTML['offerId'])) {
			$ids = implode("','",$objHTML['offerId']);
			if (!empty($ids) ) {
				$_query	=	"delete from ". $this->table."obo_offer where id in ('$ids')";
				if ( $this -> dbcon -> execute_query($_query) ){
					$strResult	=	$this->replaceLangVar($this->lang['pub_clew']['successful'],array('Buyer\'s offer',$this->lang['operation']['delete']));
				}
			}
		}

		return $strResult;
	}
	
	function activeCoupon($id){
		$query = "UPDATE {$this->table}obo_offer SET coupon_used='0' WHERE id='$id'";
		return $this->dbcon->execute_query($query);
	}

}


/*********************
* xajax function
**********************/

/*******************************
* offer function of xajax
*******************************/

/**
 * xajax Store Wise Email ReportList
 *
 * @param int $pageno
 * @param string $objHTML
 * @return objResponse
 */
function offerList($pageno=1,$objHTML=''){
	$objOfferClass 	= &$GLOBALS['objOfferClass'];
	$messages		=	'';
	$objResponse = new xajaxResponse();


	$req['offer'] =	$objOfferClass -> offerList($pageno);

	$req['nofull'] = true ;
	$objOfferClass -> smarty -> assign('req',	$req);
	$content = $objOfferClass -> smarty -> fetch('obo_offer_list.tpl');
	$objResponse -> assign("$objHTML",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

function offerAccept($id, $isAccept=false){
	$objOfferClass 	= &$GLOBALS['objOfferClass'];
	$messages		=	'';
	$objResponse = new xajaxResponse();

	$messages	=	$objOfferClass -> offerAccept($id, $isAccept);

	$objResponse -> script("javascript:xajax_offerList(xajax.$('pageno').value,'tabledatalist');");
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	//$objResponse -> alert($messages);
	return $objResponse;
}

function offerViewReview($id,$display=''){
	$objOfferClass 	= &$GLOBALS['objOfferClass'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	if ($display == '') {
		$req["offer"] = $objOfferClass -> offerViewReview($id);
		$req['display'] = 'view' ;
		$objOfferClass -> smarty -> assign('req',	$req);
		$content = $objOfferClass -> smarty -> fetch('obo_offer_list.tpl');
		$objResponse -> assign("tabledatalist",'innerHTML',$content);
	}else {
		$objResponse -> script("javascript:xajax_offerList(xajax.$('pageno').value,'tabledatalist');");
	}

	return $objResponse;
}
/**
 * display offer review
 *
 * @param objHTML $objHTML
 * @return objResponse
 */
function offerReview($objHTML){
	$objOfferClass 	= &$GLOBALS['objOfferClass'];
	$messages		=	'';
	$objResponse = new xajaxResponse();

	$messages = $objOfferClass -> offerReviewSave($objHTML);

	//$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> alert($messages);
	return $objResponse;
}

function offerDelete($objHTML){
	$objOfferClass 	= &$GLOBALS['objOfferClass'];
	$messages		=	'';
	$objResponse = new xajaxResponse();

	$messages = $objOfferClass -> offerDelete($objHTML);

	$objResponse -> script("javascript:xajax_offerList(xajax.$('pageno').value,'tabledatalist');");
	//$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> alert($messages);
	return $objResponse;
}


function offerViewEmail($id, $display=''){
	$objOfferClass 	= &$GLOBALS['objOfferClass'];
	$messages		=	'';
	$objResponse = new xajaxResponse();

	if ($display == '') {
		$req = $objOfferClass -> offerViewEmail($id);
		$req['notfull'] = true ;
		$req['notemail'] = true ;
		$objOfferClass -> smarty -> assign('req',	$req);
		$content = $objOfferClass -> smarty -> fetch('email_offer.tpl');
		$objResponse -> assign("tabledatalist",'innerHTML',$content);
	}else {
		$objResponse -> script("javascript:xajax_offerList(xajax.$('pageno').value,'tabledatalist');");
	}

	return $objResponse;
}

function resendEmail($id){
	$objOfferClass = new offerClass();
	$objResponse = new xajaxResponse();
	if($objOfferClass->offerSendMail($id,true)){
		$msg = 'The email has been resent.';
	}else{
		$msg = 'It\'s unsuccessful to resend the email. Please try again.';
	}
	$objResponse->assign('cusmsg','innerHTML',$msg);
	return $objResponse;
}
function activeCoupon($id){
	$objOfferClass = new offerClass();
	$objResponse = new xajaxResponse();
	if($objOfferClass->activeCoupon($id)){
		$objOfferClass->offerSendMail($id,true);
		$msg = "The Coupon Code has been actived succcessfully.";
	}else{
		$msg = "Faild to acitve the Coupon Code.";
	}
	$req = $objOfferClass -> offerViewEmail($id);
	$req['notfull'] = true ;
	$req['notemail'] = true ;
	$objOfferClass -> smarty -> assign('req',	$req);
	$content = $objOfferClass -> smarty -> fetch('email_offer.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse->assign('cusmsg','innerHTML',$msg);
	
	return $objResponse;
}
?>