<?php 
/**
 * $id class.socbid.php jacky.zhou Fri May 23 08:49:22 CST 2008 08:49:22
 * 
 * functionality of the soc auction
 * 
 * @author jacky.zhou
 * @package buyblitz
 * @subpackage include
 * 
 */

include_once 'class.productcertified.php';

class socbidClass extends socClass {

	/**
	 * html redirect
	 * @param	string $url
	 * @param 	string $msg
	 * 
	 * return null
	 */
	var $bidcount = 1;

	function htmlRedirect($url = 'index.php', $msg = '') {
		if ($msg != '') {
			echo "<script>alert(' ". $msg ." ');</script>";
		}
		echo "<script>window.location.href=' ". $url ." '</script>";
		exit();
	}
	
	/**
	 * get StoreID by pid
	 * @param $pid
	 * @return int
	 */
	function getStoreID($pid) {
		$StoreID = 0;
		$_query = "SELECT StoreID FROM ". $this->table ."product WHERE pid =".$pid;
		$result = $this->dbcon->execute_query($_query);

		if ($result) {
			$store = $this->dbcon->fetch_records(true);
			$StoreID = $store[0]['StoreID'];
		}
		return $StoreID;
	}
	
	/**
	 * get reviewKey by pid
	 * @param @pid
	 * @return string
	 */
	function getReviewKey($pid, $winnerId, $bidprice) {
		$reviewKey = '';
		$_query = "SELECT reviewkey FROM ".$this->table."product_bid WHERE pid=".$pid
		." AND StoreID=".$winnerId." AND price=".$bidprice." AND is_auto='no'";
		$result = $this->dbcon->execute_query($_query);

		if ($result) {
			$bid = $this->dbcon->fetch_records(true);
			$reviewKey = $bid[0]['reviewkey'];
			return $reviewKey;
		}else{
			return false;
		}
		
		return $reviewKey;
	}
	
	/**
	 * have the user reviewed..
	 * @param 	string $reviewKey
	 * @return boolean
	 */
	function buyerReviewed($reviewKey) {
		
		$isreviewed = 0;
		$_query = "SELECT isbuyerreview FROM ".$this->table."product_bid WHERE reviewkey='".$reviewKey."'";

		$result = $this->dbcon->execute_query($_query);

		if ($result) {
			$bid = $this->dbcon->fetch_records(true);
			if ($bid[0]['isbuyerreview'] == 1) {
				$isreviewed = 1;
			}
		}
		return $isreviewed;		
	}

	/**
	 * have the user reviewed..
	 * @param 	string $reviewKey
	 * @return boolean
	 */
	function sellerReviewed($reviewKey) {
		
		$isreviewed = 0;
		$_query = "SELECT isreviewed FROM ".$this->table."product_bid WHERE reviewkey='".$reviewKey."'";

		$result = $this->dbcon->execute_query($_query);

		if ($result) {
			$bid = $this->dbcon->fetch_records(true);
			if ($bid[0]['isreviewed'] == 1) {
				$isreviewed = 1;
			}
		}
		return $isreviewed;
	}

	/**
	 * get winner_id by pid
	 * @param int $pid
	 * @return int
	 */
	function getWinnerID($pid) {

		$winnerID = 0;
		$_query = "SELECT winner_id FROM " . $this->table . "product_auction WHERE pid = ".$pid;
		$result = $this->dbcon->execute_query($_query);

		if ($result) {
			$winner = $this->dbcon->fetch_records(true);
			$winnerID = $winner[0]['winner_id'];
		}
		return $winnerID;
	}

	/**
	 * get final price by pid
	 * @param int $pid
	 * @return price 
	 */
	function getMaxBidPrice($pid) {

		$price = 0;
		$_query = "SELECT price FROM " . $this->table . "product_bid WHERE pid = $pid AND price IN " .
				"( SELECT max(price) FROM " . $this->table . "product_bid WHERE pid= " .$pid . " )";
		$result = $this->dbcon->execute_query($_query);

		if ($result) {
			$price = $this->dbcon->fetch_records(true);
			$price = $price[0]['price'];
			$price = number_format($price,2,'.',',');
		}
		return $price;
	}

	/**
	 * get user's detail by StoreID
	 * 
	 * @param int StoreID
	 * @return array
	 */
	function getUser($StoreID) {

		$userinfo = array();

		$_query = "SELECT * FROM ".$this->table."bu_detail WHERE  StoreID = '$StoreID'";

		$result		=	$this->dbcon->execute_query($_query) ;
		if ($result) {
			$user = $this->dbcon->fetch_records(true) ;
			$userinfo = $user[0];
		}

		return $userinfo;
	}

	function getEmailAccount($StoreID) {
		$_query = "SELECT user FROM ". $this->table . "login WHERE StoreID=".$StoreID;
		$this->dbcon->execute_query($_query);
		$user = $this->dbcon->fetch_records(true);
		$email = $user[0]['user'];
		return $email;
	}
	
	/**
	 * display the auction detail information
	 *
	 * @param null
	 * @return array
	 */
	function displayAuction() {
		
		global $time_zone_offset; 
		
		$arrResult	=	null;
		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$proID		=	$_REQUEST['proid'] ? $_REQUEST['proid'] : '0' ;

		$returnUrl = 'soc.php?cp=home';
		
		//is auction?
		$_query = "SELECT pid FROM ". $this->table ."product WHERE pid = " .$proID ." AND is_auction='yes'";
		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);
		if (empty($result)) {
			$this->htmlRedirect($returnUrl, 'The auction does not exist.');
		}
		
		//the auction has been expired?
		$currentTimeStamp = gmmktime() + $time_zone_offset;
		$_query = "SELECT end_stamp, status FROM ". $this->table ."product_auction WHERE pid = ".$proID;
		$this->dbcon->execute_query($_query);
		$auction = $this->dbcon->fetch_records(true);

		//echo "es:".$auction[0]['end_stamp'].";";
		if ( ($currentTimeStamp+$time_zone_offset >= $auction[0]['end_stamp']) && ($auction[0]['status'] != 2) ) {
			$_query = "UPDATE ".$this->table."product_auction SET status=2 WHERE pid = " . $proID;
			if ($this->dbcon->execute_query($_query)) {
				$winnerID = $this->getWinnerID($proID);
				$this->socSendMail($winnerID, $proID);
			}
		}

		$query	=	"select * from " . $this->table. "template_details where StoreID='$StoreID'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records();
		if (is_array($arrTemp)) {
			$arrResult['template']	=	$arrTemp[0];
			$arrResult['template']['TemplateName']	=	'product-display';
			//get store infomation
			$arrResult['info']		= 	$this -> _displayStoreInfo($arrResult['template'], $StoreID);
			$arrResult['info']['payments']		=	$this -> changeArrayValue($arrResult['info']['payments']);
			$arrResult['info']['bu_delivery']	=	$this -> changeArrayValue($arrResult['info']['bu_delivery']);
			//get store style
			$arrResult['style']		= 	$this -> _displayStoreStype($arrResult['template'], $StoreID);
			//get store others item
			$arrResult['items']["product"]	=	& $this -> __displayStoreItemsProduct($arrResult['template'],$StoreID, $proID);
			$endstamp = $arrResult['items']["product"][0]['end_stamp'];
			$timenow = time();
			$arrResult['items']["product"][0]['endtime'] = gmdate('m-d-Y H:i', $endstamp -$time_zone_offset);
			$arrResult['items']["product"][0]['countdown'] = gmdate('M d, Y G:i', $endstamp-$time_zone_offset );
			$arrResult['items']["product"][0]['countTime'] = $endstamp;
			$arrResult['items']['product'][0]['payments'] = $arrResult['items']['product'][0]['payments']?unserialize($arrResult['items']['product'][0]['payments']):"";
			$arrResult['items']['product'][0]['payments'] = $this -> changeArrayValue($arrResult['items']['product'][0]['payments']);
			$showCountdown =  $endstamp;
			//echo "Cstamp:$currentTimeStamp;Estamp:$endstamp;Edate:".gmdate("Y-m-d H:i:s",$endstamp).";offset:$time_zone_offset;Time left:".$showCountdown;
			if ($showCountdown < 3600 and $showCountdown >0) {
				$arrResult['items']["product"][0]['showCountdown'] = 1;
			}
			$arrResult['items']["product"][0]['cur_bid_price'] = $arrResult['items']["product"][0]['cur_price']+$this->bidcount;
			$arrResult['items']["product"][0]['extra']=explode('|=&&&&=|',$arrResult['items']["product"][0]['extra']);
			$query  = "SELECT pb.*,bu.bu_nickname FROM {$this->table}product_bid pb";
			$query .= " LEFT JOIN {$this->table}bu_detail bu ON bu.StoreID=pb.StoreID ";
			$query .= " WHERE pb.pid='{$arrResult['items']["product"][0]['pid']}' and pb.is_auto='no' ORDER BY pb.bid DESC";
			$this->dbcon->execute_query($query);
			$bidresult = $this->dbcon->fetch_records(true);
			$arrResult['items']["product"][0]['bid_counter'] = 0;
			$arrResult['items']["product"][0]['winner_id'] = $this->getBiderNickname($arrResult['items']["product"][0]['winner_id']);
			if($bidresult){
                                /*
                                 *  @Author :   YangBall
                                 *  @Date   :   2010-08-16
                                 *  @Bug    :   Bug #6059
                                 *  @Des    :   Limit The Bid List 
                                 */
                                $query  = "SELECT pb.*,bu.bu_nickname FROM {$this->table}product_bid pb";
                                $query .= " LEFT JOIN {$this->table}bu_detail bu ON bu.StoreID=pb.StoreID ";
                                $query .= " WHERE pb.pid='{$arrResult['items']["product"][0]['pid']}' and pb.is_auto='no' ORDER BY pb.bid DESC LIMIT 20";
                                $this->dbcon->execute_query($query);
                                $tmp_result=$this->dbcon->fetch_records(true);
				$arrResult['items']['product'][0]['bidlist'] = $tmp_result;
                                /*
                                 *  END YangBall
                                 */
				$arrResult['items']["product"][0]['bid_counter'] = count($bidresult);
			}
		}
		return $arrResult;
	}

	/**
	 * bid an auction 
	 * 
	 * @param null
	 * @return boolean
	 */
	function bid() {

		if (!empty($_POST)) {

			$retval = 1;
			
			foreach ($_POST as $k=>$v) {
				${$k} = $v;
			}

			$returnUrl = 'soc.php?cp=disauction&StoreID='.$StoreID.'&proid='.$pid;

			//the current winner of auction
			$lastBidStoreID = $this->getWinnerID($pid);
			//if bid success, replace the current winner 
			$bidStoreID = !empty($_SESSION['ShopID']) ? $_SESSION['ShopID'] : '';

			if (empty($bidStoreID)) {
				$this->htmlRedirect($returnUrl, 'Bid Failed.');
			}						
			//the auction has been expired?
			$currentTimeStamp = time() - $time_zone_offset;
			$_query = "SELECT end_stamp, status FROM ". $this->table ."product_auction WHERE pid = ".$pid;
			$this->dbcon->execute_query($_query);
			$auction = $this->dbcon->fetch_records(true);
					
			if ( ($currentTimeStamp >= $auction[0]['end_stamp']) && ($auction[0]['status'] != 2) ) {
				$_query = "UPDATE ".$this->table."product_auction SET status=2 WHERE pid = " . $pid;
				if ($this->dbcon->execute_query($_query)) {
					//send mail.
					$winnerID = $this->getWinnerID($pid);
					$this->socSendMail($winnerID, $proID);
					$this->htmlRedirect($returnUrl, 'the auction has been expired.');
				}
			}

			// begin an transaction
			$this->dbcon->beginTrans();

			if (!empty($manualBid)) {

				//get current bid
				$_query = "SELECT price FROM " . $this->table . "product WHERE pid =" . $pid;
				$this-> dbcon -> execute_query($_query);
				$arrTemp=	$this->dbcon->fetch_records(true);
				$currentBid = $realprice = !empty( $arrTemp[0]['price'] ) ? $arrTemp[0]['price'] : 0;

				//get initial price
				$_query = "SELECT status, initial_price, winner_id FROM " . $this->table . "product_auction WHERE pid = " . $pid;
				$this-> dbcon -> execute_query($_query);
				$arrTemp=	$this->dbcon->fetch_records(true);
				$status = !empty( $arrTemp[0]['status'] ) ? $arrTemp[0]['status'] : 0;
				$initial_price = !empty( $arrTemp[0]['initial_price'] ) ? $arrTemp[0]['initial_price'] : 0;
				$winner_id = !empty( $arrTemp[0]['winner_id'] ) ? $arrTemp[0]['winner_id'] : 0;

				//get maxbid
				$_query = "SELECT price, is_auto FROM " . $this->table . "product_bid WHERE pid = $pid AND price IN " .
				"( SELECT max(price) FROM " . $this->table . "product_bid WHERE pid= " .$pid . " )";
				$this-> dbcon -> execute_query($_query);
				$arrTemp=	$this->dbcon->fetch_records(true);
				$maxBidPrice = !empty($arrTemp[0]['price']) ? $arrTemp[0]['price'] : 0;
				$maxBidIsAuto = !empty($arrTemp[0]['is_auto']) ? $arrTemp[0]['is_auto'] : 'no';
				
				//change auction status to can't edit
				if ($status == 0) {
					$_query = "UPDATE ". $this->table ."product_auction SET status = 1 WHERE pid = " . $pid;
					if (!$this->dbcon->execute_query($_query)) {
						$retval = 0;
					}
				}

				//if currentBid eq to '0', the auction hasn't been bided now
				if ($currentBid == 0) {
					$maxBidPrice = $currentBid = $initial_price;
				}

				if ( ( $manualBid < $currentBid ) || ( $manualBid < $initial_price) ) {
					$this->htmlRedirect($returnUrl, 'Bid Failed.');
				} elseif ( ( $manualBid == $currentBid ) && ( $realprice != 0 ) ) {
					$this->htmlRedirect($returnUrl, 'Bid Failed.');
				} elseif ( $manualBid >= $currentBid ) {
					
					$reviewKey = $actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);

					$arrBid = array(
					'pid' => $pid,
					'StoreID' => $bidStoreID,
					'bid_time' => time(),
					'price' => $manualBid,
					'reviewkey' => $reviewKey
					);

					if (!$this->dbcon->insert_record($this->table."product_bid", $arrBid)) {
						$retval = 0;
					}
					if ( ($manualBid < $maxBidPrice) ) {

						$tmpManualBid = $manualBid + 1;
						
						if ($manualBid < $maxBidPrice) {
							if ( $maxBidPrice <= $tmpManualBid ) {
								$currentBid = $maxBidPrice;
							} else {
								$currentBid = $manualBid + 1;
							}
						} else {
							$currentBid = $manualBid;
						}
						//has autobid
						
						$_query  = "UPDATE " . $this->table . "product SET price = '". $currentBid ."' WHERE pid = " . $pid;
						if (!$this->dbcon->execute_query($_query)) {
							$retval = 0;
						}

					} else {
						if ($manualBid == $maxBidPrice) {
							$bidStoreID = $winner_id;
						}
						//has no autobid , update price
						$_query = "UPDATE " . $this->table . "product SET price = ". $manualBid ." WHERE pid = " . $pid;

						if (!$this->dbcon->execute_query($_query)) {
							$retval = 0;
						}

						$_query = "UPDATE " . $this->table . "product_auction SET winner_id = ". $bidStoreID . " WHERE pid=". $pid;
						if (!$this->dbcon->execute_query($_query)) {
							$retval = 0;
						}
					}
				}
			} else {

				//get current bid
				$_query = "SELECT price FROM " . $this->table . "product WHERE pid =" . $pid;
				$this-> dbcon -> execute_query($_query);
				$arrTemp=	$this->dbcon->fetch_records(true);
				$currentBid = $realprice = !empty( $arrTemp[0]['price'] ) ? $arrTemp[0]['price'] : 0;

				//get initial price
				$_query = "SELECT status, initial_price, winner_id FROM " . $this->table . "product_auction WHERE pid = " . $pid;
				$this-> dbcon -> execute_query($_query);
				$arrTemp=	$this->dbcon->fetch_records(true);
				$status = !empty( $arrTemp[0]['status'] ) ? $arrTemp[0]['status'] : 0;
				$initial_price = !empty( $arrTemp[0]['initial_price'] ) ? $arrTemp[0]['initial_price'] : 0;
				$winner_id = !empty( $arrTemp[0]['winner_id'] ) ? $arrTemp[0]['winner_id'] : 0;

				//get maxbid
				$_query = "SELECT price, is_auto FROM " . $this->table . "product_bid WHERE pid = $pid AND price IN " .
				"( SELECT max(price) FROM " . $this->table . "product_bid WHERE pid= " .$pid . " )";
				$this-> dbcon -> execute_query($_query);
				$arrTemp=	$this->dbcon->fetch_records(true);
				$maxBidPrice = !empty($arrTemp[0]['price']) ? $arrTemp[0]['price'] : 0;
				$maxBidIsAuto = !empty($arrTemp[0]['is_auto']) ? $arrTemp[0]['is_auto'] : 'no';

				//change auction status to can't edit
				if ($status == 0) {
					$_query = "UPDATE ". $this->table ."product_auction SET status = 1 WHERE pid = " . $pid;
					if (!$this->dbcon->execute_query($_query)) {
						$retval = 0;
					}
				}

				if ($currentBid == 0) {
					$maxBidPrice = $currentBid = $initial_price;
				}
					
				if ( ( $maxBid < $currentBid ) || ( $maxBid < $initial_price) ) {
					$this->htmlRedirect($returnUrl, 'Bid Failed.');
				} elseif ( ( $maxBid == $currentBid ) && ( $realprice != 0 ) ) {
					$this->htmlRedirect($returnUrl, 'Bid Failed.');
				} else {

					$tmpMaxBid = $maxBidPrice + 1;

					if ($maxBid > $maxBidPrice) {
						if ( $maxBid < $tmpMaxBid ) {
							if ( ($winner_id == $bidStoreID) || ($winner_id == 0) ) {
								$currentBid = $currentBid;
							} else {
								$currentBid = $maxBid;
							}
						} else {
							if ( ($winner_id == $bidStoreID) || ($winner_id == 0) ) {
								$currentBid = $currentBid;
							} else {
								$currentBid = $maxBidPrice + 1;
							}
						}
					} else {
						$currentBid = $maxBidPrice;
					}

					$_query  = "UPDATE " . $this->table . "product SET price = '". $currentBid ."' WHERE pid = " . $pid;

					if (!$this->dbcon->execute_query($_query)) {
						$retval = 0;
					}

					$_query = "UPDATE " . $this->table . "product_auction SET winner_id = ". $bidStoreID . " WHERE pid=". $pid;
					if (!$this->dbcon->execute_query($_query)) {
						$retval = 0;
					}
					
					$reviewKey = $actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);

					$arrBid = array(
					'pid' => $pid,
					'StoreID' => $bidStoreID,
					'bid_time' => time(),
					'price' => $maxBid,
					'reviewkey' => $reviewKey,
					'is_auto' => 'yes'
					);

					if (!$this->dbcon->insert_record($this->table."product_bid", $arrBid)) {
						$retval = 0;
					}
				}
			}

			if ($retval == 0) {
				$this->dbcon->rollbackTrans();
				$this->dbcon->endTrans();
				$this->htmlRedirect($returnUrl, 'Bid Failed.');
			} else {
				$this->dbcon->commitTrans();
				$this->dbcon->endTrans();
				$this->htmlRedirect($returnUrl, 'Bid Successfully.');
			}
		} else {
			$this->htmlRedirect($returnUrl, 'Bid Failed.');
		}
	}
	
	function newReview() {
		
		$winnerId = intval(isset($_REQUEST['winnerId']) ? $_REQUEST['winnerId'] : 0);
		$pid = intval(isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0);
		$userlevel = addslashes(isset($_REQUEST['level']) ? $_REQUEST['level'] : '');
		$reviewkey = trim(isset($_REQUEST['reviewkey']) ? $_REQUEST['reviewkey'] : '');
		
		if (($winnerId == 0) || ($pid == 0) || ($reviewkey == '')) {
			$this->htmlRedirect();
		}
		
		if (empty($userlevel)) {
			$this->htmlRedirect('index.php', 'Please login to give a review.');
		}
				
		$tableName = $this->table."product_bid";
		$strCondition = "WHERE reviewkey = '".$reviewkey."' AND pid= ".
				$pid . " AND is_auto='no' AND StoreID= ".$winnerId."";
		if ($this->dbcon->checkRecordExist($tableName, $strCondition)) {

			if ($userlevel == '1bp3a') {
				
				if ( $this->sellerReviewed($reviewkey) == 1 ) {
					$this->htmlRedirect('index.php', 'You have submitted a review for this user.');
				} else {
					$userinfo = $this->getUser($winnerId);
					$arrResult['storename'] = $userinfo['bu_nickname'];
					$arrResult['storeurl'] = $userinfo['bu_urlstring'];
					$arrResult['type'] = 'user';
					$arrResult['StoreID'] = $winnerId;
					$arrResult['reviewkey'] = $reviewkey;
					
					$_query = "SELECT t1.id FROM ". $this->table ."login t1, ". $this->table ."product t2 WHERE t1.StoreID=t2.StoreID AND t2.pid=$pid";
					$this->dbcon->execute_query($_query);
					$loginInfo = $this->dbcon->fetch_records(true);
					if ($loginInfo) {
						$arrResult['user_id'] = $loginInfo[0]['id'];
					} else {
						$this->htmlRedirect();
					}
				}				
				
			} elseif ($userlevel == '2xd3t') {
				if ( $this->buyerReviewed($reviewkey) == 1 ) {
					$this->htmlRedirect('index.php', 'You have submitted a review for this store.');
				} else {

					$sStoreID = $this->getStoreID($pid);
					$_query = "SELECT bu_name,bu_urlstring FROM " . $this->table . "bu_detail WHERE StoreID = ".$sStoreID;
					if ($this->dbcon->execute_query($_query)) {
						$storeInfo = $this->dbcon->fetch_records(true);
						$arrResult['storename'] = $storeInfo[0]['bu_name'];
						$arrResult['storeurl'] = $storeInfo[0]['bu_urlstring'];
						$arrResult['type'] = 'store';
						$arrResult['StoreID'] = $sStoreID;
						$arrResult['reviewkey'] = $reviewkey;

						$_query = "SELECT id FROM ". $this->table ."login WHERE StoreID=".$winnerId;
						$this->dbcon->execute_query($_query);
						$loginInfo = $this->dbcon->fetch_records(true);
						if ($loginInfo) {
							$arrResult['user_id'] = $loginInfo[0]['id'];
						} else {
							$this->htmlRedirect();
						}
					}
				}				
			}
		} else {
			$this->htmlRedirect();
		}
		return $arrResult;
	}
	
	function saveReview($formvars) {
		
		foreach ($formvars as $k => $v) {
			${$k} = $v;
		}

		$message = addslashes($message);
		
		if ($type == 'store' && $this->buyerReviewed($reviewkey)==1){
			$msg = 'Review already exists.';
		}else if($type == 'user' && $this->sellerReviewed($reviewkey)==1){
			$msg = 'Review already exists.';
		}else{
			$_query = "INSERT INTO " .$this->table ."review (type, StoreID, bu_name, user_id, rating, content, post_date,status) VALUES('$type', '$StoreID', '$storename', '$user_id', '$rating', '$message', now(),'$status')";
			if (isset($rating) && $rating > 0 && !check_badwords($message)){
				if ($this->dbcon->execute_query($_query)) {
					if ($type == 'user') {
						$_query = "UPDATE ".$this->table."product_bid SET isreviewed=1 WHERE reviewkey='".$reviewkey."'";
						$this->dbcon->execute_query($_query);
					} elseif ($type == 'store') {
						$_query = "UPDATE ".$this->table."product_bid SET isbuyerreview=1 WHERE reviewkey='".$reviewkey."'";
						$this->dbcon->execute_query($_query);					
					}
					$msg = 'Review inserted successfully.';
				} else {
					$msg = 'Failed to insert the review.';
				}
			}elseif(check_badwords($message)){
				$msg = "Please keep our website wholesome and clean by refraining from using vulgar words!";
			}else{
				$msg = "Rating is required.";
			}
		}
		$this->htmlRedirect('soc.php?cp=home', $msg);
	}

	function addComment() {
		$_var = $this -> setFormInuptVar();
		extract($_var);
		$message = addslashes($message);
		
		$arrSetting = array(
			'content_type' => 'comment',
			'type' => $type,
			'StoreID' => $StoreID,
			'upid' => $rid,
			'bu_name' => $storename,
			'user_id' => $user_id,
			'content' => $message,
			'post_date' => date("Y-m-d")
		);
		if ($this->dbcon->insert_record($this->table.'review',$arrSetting)){
			$msg = 'Comment inserted successfully.';
		} else {
			$msg = 'Comment inserted failed.';
		}
		if ($type=='store'){
			$this->htmlRedirect('soc.php?cp=disreview&StoreID='.$StoreID, $msg);
		}else{
			$this->htmlRedirect('soc.php?cp=lookupreview', $msg);
		}
	}
	
	function displayReview($StoreID,$pid=0) {

		$pageSize = PAGESIZE;
		$pageId = intval(!empty($_REQUEST['pageId']) ? $_REQUEST['pageId'] : 1);
		
		$arrResult = array();
		$arrResult['user_level'] = $_SESSION['level'];
		$arrResult['ShopID'] = $_SESSION['ShopID'];
		$tableName = $this->table."login";
		// changed for buyer's review display by Jessee 20100330
		$strCondition = "WHERE StoreID = ". $StoreID ;//." AND level=1";

		if ($this->dbcon->checkRecordExist($tableName, $strCondition)) {

			$_query = "SELECT * FROM " . $this->table . "bu_detail WHERE StoreID = ".$StoreID;
			if ($this->dbcon->execute_query($_query)) {
				$storeInfo = $this->dbcon->fetch_records(true);
				$arrResult['storeInfo'] = $storeInfo[0];
				$arrResult['ownerType'] = ($pid==0)?'store':'user';
			}
			
			$_query = "SELECT count(*) AS Counter FROM ". $this->table . "review WHERE StoreID = " . $StoreID ." AND type='store' and content_type='review'";
			$this->dbcon->execute_query($_query);
			$result = $this->dbcon->fetch_records(true);
			$totleCount = $result[0]['Counter'];

			$pager = new Page($totleCount, $pageSize, true, 'pageId');

			$_query = "SELECT t1.*, t1.content as rcontent, t2.bu_nickname as username, t2.StoreID as profile_id, "
					."DATE_FORMAT(t1.post_date,'".DATAFORMAT_DB."') as fdate FROM " . $this->table . "review t1"
					." left join ". $this->table ."login t3 on t1.user_id = t3.id"
					." left join ". $this->table ."bu_detail t2 on t2.StoreID = t3.StoreID"
					." WHERE t1.StoreID = ". $StoreID . " AND t1.type='".$arrResult['ownerType']
					."' and t1.content_type='review' ORDER BY post_date DESC ".$pager->get_limit();
			$this->dbcon->execute_query($_query);
			$reviews = $this->dbcon->fetch_records(true);
			if (!empty($reviews)) {
				foreach ($reviews as $review) {
					$review['content'] = nl2br(stripcslashes(html_entity_decode($review['content'])));
					$sql = "select count(*) from ".$this->table."review where upid = ".$review['review_id'];
					$this->dbcon->execute_query($sql);
					$comment_count = $this->dbcon->fetch_records();
					$comment_count = $comment_count[0][0];
					
					$filename = SITE_ROOT.DIRECTORY_SEPARATOR.'profile_images'.DIRECTORY_SEPARATOR.$review['profile_id'].'.jpg';
					if (file_exists($filename)) {
						$review['profile_image'] = '/profile_images/'.$review['profile_id'].'.jpg';
					}
					if ($comment_count > 0){
						$sql = "select review.*,DATE_FORMAT(review.post_date,'".DATAFORMAT_DB."') as fdate, bu_detail.bu_nickname from ".$this->table."review as review Left Join ".$this->table."login as login ON review.user_id=login.id Left join ".$this->table."bu_detail as bu_detail ON login.StoreID=bu_detail.StoreID where review.upid=".$review['review_id']." order by review.post_date desc";
						$this->dbcon->execute_query($sql);
						$info_comment = $this->dbcon->fetch_records();
						$comment = '<tr><td colspan=2 align=left >Comments:</td></tr>';
						$comment.= '<tr><td colspan=2 align=center>';
						$comment.= '<table width=90% cellspacing=0 cellpadding=0>';
						foreach($info_comment as $comm_val){
							$comment.= '<tr><td align=left>';
							if ($comm_val['user_id'] == $StoreID){
								$comment.= '<font color=red>';
								$comment.= $comm_val['fdate']." by Website Owner " ;
								$comment.= '</font>';
							}else{
								$comment.= $comm_val['fdate']." by ".(empty($comm_val['bu_nickname'])?$comm_val['bu_name']:$comm_val['bu_nickname']) ;
							}
							$comment.= '</td></tr><tr><td align=left>';
							$comment.= nl2br($comm_val['content']).'<br><br></td></tr>';
						}
						$comment.='</table></td></tr>';
						$review['comment'] = $comment;
					}else{
						$review['comment'] = '';
					}
					$arrResult['reviews'][] = $review;
				}
			}
			$arrResult['linkStr'] = $pager->get_link('soc.php?cp=disreview&StoreID='.$StoreID, $pageSize);
		} else {
			$this->htmlRedirect('soc.php?cp=home', 'Sorry, the website does not exist.');
		}

		return $arrResult;
	}
	
	
	/**
	 * lookup buyer's review
	 */
	function lookupreview() {
		
		if ( (!empty($_POST) ) || (isset($_REQUEST['pageId'])) && isset($_REQUEST['buyername'])) {

			$arrResult = array();

			$buyername = isset($_POST['buyername']) ? $_POST['buyername'] : $_REQUEST['buyername'];
			$buyername = $this -> __StrReplace($buyername);

			$pageId = intval(!empty($_REQUEST['pageId']) ? $_REQUEST['pageId'] : 1);
			$pageSize = PAGESIZE;
			
			$tableName = $this->table."bu_detail";
			$strCondition = "WHERE bu_nickname like '%$buyername%'";
			
			if (!empty($buyername) && ($this->dbcon->checkRecordExist($tableName, $strCondition))) {	

				$_query = "SELECT StoreID FROM ". $this->table ."bu_detail $strCondition";
				$this->dbcon->execute_query($_query);
				$budetails = $this->dbcon->fetch_records(true);
				
				$strConditionStoreID = '';
				foreach ($budetails as $val){
					$strConditionStoreID.= ",'". $this -> __StrReplace($val['StoreID']) ."'";
				}
				$strConditionStoreID = substr($strConditionStoreID,1);

				$strCondition = "WHERE t1.StoreID = '" . $_SESSION['StoreID'] . "' and t1.type='store' and t1.user_id = t3.id AND t2.StoreID = t3.StoreID and t1.upid=0 and t3.StoreID in ($strConditionStoreID)";

				$_query = "SELECT count(*) AS Counter FROM " . $this->table . "review t1, ". $this->table ."bu_detail t2, ". $this->table ."login t3 $strCondition";

				$this->dbcon->execute_query($_query);
				$result = $this->dbcon->fetch_records(true);
				$totleCount = $result[0]['Counter'];

				$pager = new Page($totleCount, $pageSize, true, 'pageId');
				
				$_query = "SELECT t1.* ,DATE_FORMAT(t1.post_date,'".str_replace('-','/',DATAFORMAT_DB)."') as fdate , t1.content as rcontent, t2.bu_nickname as username FROM " . $this->table . "review t1, ". $this->table ."bu_detail t2, ". $this->table ."login t3 $strCondition ORDER BY t1.post_date DESC, t1.review_id DESC ".$pager->get_limit();
				
				$this->dbcon->execute_query($_query);
				$reviews = $this->dbcon->fetch_records(true);
				if (!empty($reviews)) {
					foreach ($reviews as $review) {
						$review['content'] = stripcslashes(html_entity_decode($review['content']));
						$sql = "select count(*) from ".$this->table."review where upid = ".$review['review_id'];
						$this->dbcon->execute_query($sql);
						$comment_count = $this->dbcon->fetch_records();
						$comment_count = $comment_count[0][0];
						//echo $sql.'<br>count:'.$comment_count.'end.';
						
						if ($comment_count > 0){
							$sql = "select review.*,DATE_FORMAT(review.post_date,'".str_replace('-','/',DATAFORMAT_DB)."') as fdate, bu_detail.bu_nickname from ".$this->table."review as review Left Join ".$this->table."login as login ON review.user_id=login.id Left join ".$this->table."bu_detail as bu_detail ON login.StoreID=bu_detail.StoreID where review.upid=".$review['review_id']." order by review.post_date desc";
							
							
							$this->dbcon->execute_query($sql);
							$info_comment = $this->dbcon->fetch_records();
							$comment = '<tr><td colspan=2 align=left >Comments:</td></tr>';
							$comment.= '<tr><td colspan=2 align=center>';
							$comment.= '<table width=90% cellspacing=0 cellpadding=0>';
							foreach($info_comment as $comm_val){
								$comment.= '<tr><td align=left>';
								if ($comm_val['user_id'] == $StoreID){
									$comment.= '<font color=red>';
									$comment.= $comm_val['fdate']." by Website Owner " ;
									$comment.= '</font>';
								}else{
									$comment.= $comm_val['fdate']." by ".(empty($comm_val['bu_nickname'])?$comm_val['bu_name']:$comm_val['bu_nickname']) ;
								}
								$comment.= '</td></tr><tr><td align=left>';
								$comment.= $comm_val['content'].'<br><br></td></tr>';
							}
							$comment.='</table></td></tr>';
							$review['comment'] = $comment;
						}else{
							$review['comment'] = '';
						}
						$arrResult['reviews'][] = $review;
					}
				}

				$arrResult['linkStr'] = $pager->get_link('soc.php?cp=lookupreview&buyername='.$buyername, $pageSize);
				
			} else {
				$this->htmlRedirect('soc.php?cp=lookupreview', 'Sorry, the buyer does not exist.');
			}

			return $arrResult;
		}
		return false;
	}
	
	function socSendMail($winnerId, $pid) {
		
		//send mail

		//get max bid price
		$_query = "SELECT cur_price,reserve_price,winner_id FROM " . $this->table 
				. "product_auction WHERE pid =" . $pid;
		$this-> dbcon -> execute_query($_query);
		$arrTemp=	$this->dbcon->fetch_records(true);
		$currentBid = !empty( $arrTemp[0]['cur_price'] ) ? $arrTemp[0]['cur_price'] : 0;
		$reserve_price = !empty( $arrTemp[0]['reserve_price'] ) ? $arrTemp[0]['reserve_price'] : 0;
		$lastwinner = !empty( $arrTemp[0]['winner_id'] ) ? $arrTemp[0]['winner_id'] : 0;
		
		
		$_query = "SELECT item_name FROM ". $this->table . "product WHERE pid='$pid' ";
		$this-> dbcon -> execute_query($_query);
		$arrTemp=	$this->dbcon->fetch_records(true);
		$itemname = !empty( $arrTemp[0]['item_name'] ) ? $arrTemp[0]['item_name'] : "";
		//get the seller's store URL
		$sql = "select b.bu_urlstring,b.bu_phone,b.bu_name from ".$this->table."bu_detail as b,"
			.$this->table."product as p where b.StoreID=p.StoreID and p.pid=".$pid;
		$this->dbcon->execute_query($sql);
		$arrTemp = $this->dbcon->fetch_records();
		$sellerUrl = SOC_HTTP_HOST . $arrTemp[0]['bu_urlstring'];

		//send mail to winner
		//$winnerId = $this->getWinnerID($pid);
		$mailto = $this->getEmailAccount($winnerId);
		$userinfo = $this->getUser($winnerId);
		$price = $currentBid;
		//generate reviewkey
		$reviewKey = $this->getReviewKey($pid, $winnerId, $price);
		//echo "key:$reviewKey\n";exit;
		$reviewUrl = SOC_HTTP_HOST . 'soc.php?cp=newreview&winnerId='.$winnerId.'&pid='.$pid.'&level=2xd3t&reviewkey='.$reviewKey;
		$mailtoname = $userinfo['bu_nickname'];
		$mail_detail = array(
			'mailtoname'=> $mailtoname,
			'reviewlink'=> $reviewUrl,
			'sellerUrl' => $sellerUrl,
			'sellerName'=> $arrTemp[0]['bu_name'],
			'price'		=> $price,
			'item_name' => $itemname
			);
		if(intval($currentBid*100)>=intval($reserve_price*100)){
			$this->sendMail('winbid', $mailto, $mail_detail);
			//send mail to losers
			$_query = "SELECT distinct(bid.StoreID), login.user, detail.bu_nickname FROM " . $this->table . "product_bid as bid,".$this->table."login as login,"
			.$this->table."bu_detail as detail WHERE pid=".$pid." and bid.StoreID=login.StoreID and detail.StoreID=bid.StoreID and bid.StoreID!=$winnerId";
		}else{
			$_query = "SELECT distinct(bid.StoreID), login.user, detail.bu_nickname FROM " . $this->table . "product_bid as bid,".$this->table."login as login,"
			.$this->table."bu_detail as detail WHERE pid=".$pid." and bid.StoreID=login.StoreID and detail.StoreID=bid.StoreID";
		}
		
		$this->dbcon->execute_query( $_query );
		$bider = $this->dbcon->fetch_records(true);
		if ($bider) {

			foreach($bider as $val){
				$mail_detail = array(
				'mailtoname'=> $val['bu_nickname'],
				'item_name' => $itemname
				);
				if (emailcheck($val['user'])){
					$this->sendMail('losebid', $val['user'], $mail_detail);
				}
			}
		}

		//send mail for notice seller
		$StoreID = $this->getStoreID($pid);
		$mailtoseller = $this->getEmailAccount($StoreID);
		$userinfo = $this->getUser($StoreID);
		$reviewUrl = SOC_HTTP_HOST . 'soc.php?cp=newreview&winnerId='.$winnerId.'&pid='.$pid.'&level=1bp3a&reviewkey='.$reviewKey;
		$mail_detail = array(
			'buyer'		=> $mailtoname,
			'buyeremail'=> $mailto,
			'phone'		=> $userinfo['bu_phone'],
			'mailtoname'=> $userinfo['bu_nickname'],
			'reviewlink'=> $reviewUrl,
			'price'		=> $price,
			'item_name' => $itemname
			);
		if(intval($currentBid)>=intval($reserve_price)&&$lastwinner>0){
			$this->sendMail('confirmbid', $mailtoseller, $mail_detail);
		}else{
			$this->sendMail('sellernotsold', $mailtoseller, $mail_detail);
		}
	}

	function sendMail($mailType, $mailto = '', $detail) {
		
		$mailtoname = $detail['mailtoname'];
		$reviewlink = $detail['reviewlink'];
		$sellerLink = $detail['sellerUrl'];
		$sellerName = $detail['sellerName'];
		$buyer = $detail['buyer'];
		//$buyerPhone = $detail['phone'];
		$buyeremail = $detail['buyeremail'];
		$price = $detail['price'];
		$itemname = $detail['item_name'];
		
		$email_regards='SOC exchange Australia';

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

		/* additional headers */
		$headers .= 'From: noreply <noreply@thesocexchange.com>' . "\r\n";

		switch ($mailType) {
			
			case 'winbid':
				$subject = 'SOC Auction: '.$itemname.' won';
				$message = '<p><font color=red>Congratulations <b>'.$mailtoname.'</b></font>. ';
				$message .= 'Your bid of $'.$price.' is the <font color=red>successful</font> bid! ';
				$message .= 'Please contact '.$sellerName.' via <a href="'.$sellerLink.'">this link</a>,';
				$message .= ' to organize payment and delivery / pick-up.</p>';
				$message .= '<p>You can write a review on this seller via the following link:<br>';
				$message .= '<a href="'. $reviewlink .'">'.$reviewlink.'</a> once your payment has been processed.</p>';
				$message .= '<p>Your email address and contact phone number has been forwarded to this seller, ';
				$message .= 'to enable the quick completion of this transaction.</p>';
				$message .= '<br><br>Kind regards,<br>'.$email_regards;
				
				break;
				
			case 'losebid':
				$subject = 'SOC Auction: '.$itemname.' not won';
				$message = '<p>'.$mailtoname.'. Sorry, but your final bid on this occasion ';
				$message.= 'was not successful in the purchase of this item. ';
				$message.= 'Thank you for participating in this auction, and we wish you the best of luck next time!</p>';
				$message .= '<br><br>Kind regards,<br>'.$email_regards;
				
				break;
			
			case 'confirmbid':
				$subject = 'SOC Auction: '.$itemname.' sold';
				$message .= '<font color=red>Congratulations <b>'.$mailtoname.'</b></font>. ';
				$message .= 'Your item has <font color=red><b>sold</b></font> for $'.$price.'. ';
				$message .= 'Please contact the buyer ('.$buyer.' & '.$buyeremail.' ) ';
				$message .= 'to organize your receiving of funds, and delivery / pick-up. </p>';
				$message .= '<p>You can write a review on this buyer via the following link:<br>';
				$message .= '<a href="'. $reviewlink .'">'.$reviewlink.'</a> once the sale has been completed.</p>';
				$message .= '<p>Your email address and contact phone number has been forwarded to this purchaser,';
				$message .= ' to enable the quick completion of this transaction.</p>';
				$message .= '<br><br>Kind regards,<br>'.$email_regards;
				
				break;
			case 'sellernotsold':
				$subject = 'SOC Auction: '.$itemname.' not sold';
				$message .= '<b>'.$mailtoname.'</b>. Sorry, your auction item did not sell. ';
				$message .= 'You can log back in to your account to relist your item. ';
				$message .= '<br><br>Kind regards,<br>'.$email_regards;
				break;
			default:
				break;
		}

                    $message = getEmailTemplate($message);
                    @mail($mailto, $subject, Input::String($message), fixEOL($headers));
		
	}

	function getBidInfomation($pid){
		$query = "SELECT * FROM {$this->table}product_auction where pid='{$pid}'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		$bidInfo = array();
		if($result){
			$bidInfo = $result[0];
			$bidInfo['winner_id'] = $this->getBiderNickname($bidInfo['winner_id']);
			$query = "SELECT pd.*,bu.bu_nickname FROM {$this->table}product_bid pd LEFT JOIN {$this->table}bu_detail bu ON pd.StoreID = bu.StoreID where pd.pid='$pid' and pd.is_auto='no' ORDER BY pd.bid DESC";
			$this->dbcon->execute_query($query);
			$resBid = $this->dbcon->fetch_records(true);
			$bidInfo['bid_counter'] = 0;
			$bidInfo['timeleft'] = $bidInfo['end_stamp']-time()>=0?$bidInfo['end_stamp']-time():'-1';
			$bidInfo['cur_bid_price'] = $bidInfo['cur_price']+$this->bidcount;
			if($resBid){
				$arybidlist = array();
                                $query1 = "SELECT pd.*,bu.bu_nickname FROM {$this->table}product_bid pd LEFT JOIN {$this->table}bu_detail bu ON pd.StoreID = bu.StoreID where pd.pid='$pid' and pd.is_auto='no' ORDER BY pd.bid DESC LIMIT 20";
                                $this->dbcon->execute_query($query1);
                                $arr_rs = $this->dbcon->fetch_records(true);
				foreach ($arr_rs as $key=>$pass){
					$arybidlist[$key] = $pass;
					$arybidlist[$key]['endtimes'] = strftime('%d-%b-%Y &nbsp;&nbsp;&nbsp; %H:%M:%S',$pass['bid_time'])." EST";
				}
				$bidInfo['bid_counter'] = count($resBid);
				$bidInfo['bidList'] = $arybidlist;
			}
		}
		return $bidInfo;
	}
	
	
	function getBiderNickname($StoreID){
		$query = "SELECT bu_nickname FROM {$this->table}bu_detail where StoreID='$StoreID'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if($result){
			return htmlspecialchars($result[0]['bu_nickname']);
		}
		return "";
	}
/*** Wed Mar 24 11:32:16 CST 2010 roy.luo**/
	function bidProduct($pid,$StoreID=0,$price=0){
		$retval = 1;

		$check_query = "SELECT StoreID,is_certified,pid FROM {$this->table}product where pid='$pid' ";
		$this->dbcon->execute_query($check_query);
		if($check_result = $this->dbcon->fetch_records(true)){
			if($StoreID==$check_result[0]['StoreID']){
				return "Sorry, you can't bid the items in your store.";
			}
		}

        /**
         * Check Certified
         * @author ronald
         */
        if($check_result[0]['is_certified']){
            $certifiedObj = new ProductCertified();
            $isAuthorised = $certifiedObj->getApplyState($pid);
            if(is_null($isAuthorised) || $isAuthorised == 2){
                return "{"."state:'notCertified', storeId:'{$check_result[0]['StoreID']}', productId:'{$check_result[0]['pid']}'"."}";
            }elseif($isAuthorised == 0){
                return "{"."state:'pedding', storeId:'{$check_result[0]['StoreID']}', productId:'{$check_result[0]['pid']}'"."}";
            }
        }

		$this->dbcon->beginTrans();
		$query = "SELECT initial_price,end_stamp,cur_price,winner_id FROM {$this->table}"
				."product_auction where pid='$pid' for update";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		// add line lock for bid
		$this->dbcon->execute_query("select StoreID,price from ".$this->table."product_bid where pid='$pid' for update");
		$max_price = 0;
		if($result){
			if($result[0]['end_stamp'] - time()<=0){
				return "Your bid cannot be accepted. This auction has already closed. ";
			}
			$max_price = $result[0]['cur_price']?$result[0]['cur_price']:0;
			$pbidquery = "SELECT count(*) as num FROM {$this->table}product_bid where pid='$pid' and is_auto='no'";
			$this->dbcon->execute_query($pbidquery);
			$pbid = $this->dbcon->fetch_records(true);
			if($pbid[0]['num']>0){
				if(intval(($max_price+$this->bidcount))>intval($price)){
					return "You have been outbid by another user.";
				}elseif($StoreID==$result[0]['winner_id']){return "You are currently the highest bidder.";	}
			}else{
				if(intval($max_price)>intval($price)){
					return "You have been outbid by another user.";
				}elseif($StoreID==$result[0]['winner_id']){return "You are the current winner so you don't need to bid again.";	}
			}
		}
		$addval = $price-$max_price;
		if(intval($addval/($this->bidcount))!=intval($addval)/($this->bidcount)){
			return "The value should be whole doller value only. Please change and try again.";
		}
		$reviewKey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
		if($StoreID=='854836'&&($this->paypal_info['paypal_mode'] == 0)){sleep(10);}
		
		$query = "select count(*) as num from {$this->table}product_bid "
				."where StoreID!='$StoreID' and pid='$pid' and is_auto='yes' and price='$price'";
		$this->dbcon->execute_query($query);
		$count = $this->dbcon->fetch_records();
		$count = $count?$count[0]['num']:0;
		if ($count == 0){
			$query = "insert into {$this->table}product_bid(`pid`,`StoreID`,`bid_time`,`price`,`reviewkey`) values('$pid','$StoreID','".time()."','$price','$reviewKey')";
			$bid = 0;
			if($this->dbcon->execute_query($query)){ 
				$bid = $this->dbcon->insert_id();
			}else{	$retval = 0; }
			if($result[0]['end_stamp']-time()>10){
				$query = "update {$this->table}product_auction SET `winner_id`='$StoreID',`cur_price`=cur_price+$addval,`bid`='$bid',status=1 WHERE pid='$pid'";	
			}else{
				//$nowtimemap = time()+20;
				$query = "update {$this->table}product_auction SET `end_stamp`=end_stamp+20,`winner_id`='$StoreID',`cur_price`=cur_price+$addval,`bid`='$bid',status=1 WHERE pid='$pid'";	
			}
			
			if(!$this->dbcon->execute_query($query)){$retval = 0;}
		}
		
		$this->dbcon->execute_query("update {$this->table}product SET `price`=price+$addval  WHERE pid='$pid'");
		
		if($StoreID=='854836'&&($this->paypal_info['paypal_mode'] == 0)){sleep(20);}
		
		if ($count == 0){
			$query = "SELECT cur_price FROM {$this->table}product_auction where pid='$pid'";
			$this->dbcon->execute_query($query);
			$result= $this->dbcon->fetch_records(true);
			if($result){
				if(intval($price)!=intval($result[0]['cur_price'])){
					$retval = 0;
				}
			}else{$retval=0;}
		}
		if ($retval == 0) {
			$this->dbcon->rollbackTrans();
			$this->dbcon->endTrans();			
			return "Your bid could not be submitted. Please try again.";
		} else {
			if ($this->autobidFunc($pid,'bid',$price,$StoreID)){
				$query = "SELECT cur_price,winner_id FROM {$this->table}product_auction WHERE pid='$pid'";
				$this->dbcon->execute_query($query);
				$result = $this->dbcon->fetch_records(true);
				$isupdate =false;
				if($result[0]['winner_id']!=$StoreID&&intval($result[0]['cur_price'])==intval($price)){
					$msg = "You have been outbid by another user.";
					$isupdate = true;
				}else{
					$msg = "Your bid has been submitted successfully.";
				}
				$this->dbcon->commitTrans();
				$this->dbcon->endTrans();
				if($isupdate){
					$query = "UPDATE {$this->table}product_bid SET StoreID='{$result[0]['winner_id']}' WHERE bid='$bid'";
					$this->dbcon->execute_query($query);
				}
				return $msg;
			}else{
				$this->dbcon->rollbackTrans();
				$this->dbcon->endTrans();
				return "";			
			}
		}
		/**auction bid**/		
		return "";
	}
	
/*
 * Function: autobidFunc
 * description: processing the step of autobid
 * Input: $pid
 * Output: boolean status of operation
 * authur: Jessee
 * date: 2010/03/31
 */
	function autobidFunc($pid,$type='bid',$price,$StoreID=0){
		$query = "SELECT * FROM {$this->table}product_auction where pid='$pid'";
		$this->dbcon->execute_query($query);
		$auctionInfo = $this->dbcon->fetch_records();
		if ($auctionInfo){
			$auctionInfo = $auctionInfo[0];
		}else{
			return false;
		}
		// check if auto bid exists, if not, return true
		$query = "SELECT StoreID,price as price FROM {$this->table}product_bid "
				."where pid='$pid' and is_auto='yes' order by price desc,bid_time asc,bid asc limit 1";
		$this->dbcon->execute_query($query);
		$bidInfo = $this->dbcon->fetch_records();
		if (!$bidInfo) return true;

		if ($type == 'bid'){
			// processing the manual bid
			$autobid = $bidInfo[0];
			// get current top price
//			$query = "select bid,StoreID,price from {$this->table}product_bid "
//					."where pid='$pid' and is_auto='no' order by price desc limit 1";
//			$this->dbcon->execute_query($query);
//			$curPrice = $this->dbcon->fetch_records();
//			if ($curPrice)$curPrice = $curPrice[0];

			// some one bid it now
//			if (intval($price*100) == intval($curPrice['price']*100) && $curPrice['StoreID'] == $autobid['StoreID']){
//				return true;
//			}else{
//				$bid = $curPrice['bid'];
//			}
			$arrayBid = array(
				'pid'		=> $pid,
				//'StoreID'	=> $autobid['StoreID'],
				'bid_time'	=> time(),
				//'price'		=> $curPrice['price']+1,
				'reviewkey'	=> substr(md5(uniqid(mt_rand(), 1)), 0, 8),
				'isreviewed'=> 0,
				'is_auto'	=> 'no'
			);
			if (intval($autobid['price']*100) > intval($price*100)){
				$arrayBid['price'] = $price + $this->bidcount;
				$arrayBid['StoreID'] = $autobid['StoreID'];
			}elseif(intval($autobid['price']*100)==intval($price*100)){
				$arrayBid['price'] = $price;
				$arrayBid['StoreID'] = $autobid['StoreID'];
//				// delete the current bid record;
//				$updateAuction = array(
//					'winner_id'	=> $autobid['StoreID'],
//					'cur_price' => $curPrice['price']
//				);
//				$condition = "where pid='$pid'";
//				$this->dbcon->update_record($this->table."product_auction",$updateAuction,$condition);
//				
//				/***update product table price**/
//				$this->dbcon->execute_query("update {$this->table}product SET `price`={$updateAuction['cur_price']}  WHERE pid='$pid'");
//				
//				$query = "select winner_id,cur_price from {$this->table}product_auction where pid='$pid'";
//				$this->dbcon->execute_query($query);
//				$check = $this->dbcon->fetch_records();
//				$check = $check[0];
//				if ($check['winner_id'] == $autobid['StoreID'] 
//					&& intval($check['cur_price']*100)==intval($curPrice['price']*100)){
//					return true;
//				}else{
//					return false;
//				}
			}else{
				return true;
			}
			if ($this->dbcon->insert_record($this->table."product_bid",$arrayBid)){
				$bid = $this->dbcon->insert_id();
				$updateAuction = array(
					'bid'		=> $bid,
					'winner_id'	=> $arrayBid['StoreID'],
					'cur_price' => $arrayBid['price']
				);
				if($auctionInfo['end_stamp']-time()<=10){
					$updateAuction['end_stamp'] = $auctionInfo['end_stamp']+20;
				}
				
				$condition = "where pid='$pid'";
				$this->dbcon->update_record($this->table."product_auction",$updateAuction,$condition);
				/***update product table price**/
				$this->dbcon->execute_query("update {$this->table}product SET `price`={$updateAuction['cur_price']}  WHERE pid='$pid'");
				
				$query = "select winner_id,cur_price from {$this->table}product_auction where pid='$pid'";
				$this->dbcon->execute_query($query);
				$check = $this->dbcon->fetch_records();
				$check = $check[0];
				if ($check['winner_id'] == $arrayBid['StoreID'] 
					&& (int)($check['cur_price']*100)==(int)($arrayBid['price']*100)){
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}
			//}else{
			//	return false;
			//}
		}else{
			// processing the auto bid
			$autobid = $bidInfo[0]['price'];
			// get current top price
			$query = "select StoreID,price from {$this->table}product_bid "
					."where pid='$pid' and is_auto='no' order by price desc limit 1";
			$this->dbcon->execute_query($query);
			$curBidPrice = $this->dbcon->fetch_records();

					
			$query = "SELECT sb.bid,sb.StoreID,sb.price from (SELECT bid,StoreID, price FROM {$this->table}product_bid"
					." where pid='$pid' and is_auto='yes' order by price desc,bid_time asc,bid asc) as sb group by sb.StoreID order by sb.price DESC limit 2";
			//$query = "SELECT StoreID, price FROM {$this->table}product_bid"
			//		." where pid='$pid' and is_auto='yes' order by price desc,bid_time asc limit 2";
			$this->dbcon->execute_query($query);
			$arrayAutoBid = $this->dbcon->fetch_records();
			//var_dump($query.$arrayAutoBid);
			// return error when no auto bid record was found
			if (!$arrayAutoBid)return false;
			//echo "auto records:(".$this->dbcon->count_records().").";
			if ($this->dbcon->count_records()==2){
				// processing the condition of 2 autobid records exist
				//echo "2 auto bid.\n";
				// return error when no bid record was found
				if (!$curBidPrice)return false;
				$curBidPrice = $curBidPrice[0];
				// check the max price of auto bid
				// record for error check
				$firstOld = $arrayAutoBid[0];
				$secondOld = $arrayAutoBid[1];
				$query = "select bid,StoreID,price from {$this->table}product_bid "
						."where is_auto='yes' and pid='$pid' and StoreID!='$StoreID' "
						."order by price desc, bid_time asc, bid asc limit 1";
				$this->dbcon->execute_query($query);
				$second = $this->dbcon->fetch_records();
				$second = $second[0];
				$first = array(
					'StoreID'	=> $StoreID,
					'price'		=> $price
				);
				// correct the first and second with the actual data
				if ($first['price'] <= $second['price']){
					$tmp = $first;
					$first = $second;
					$second = $tmp;
				}
				// record the log if the old order is different with current bid.
				if ($first['StoreID']!=$firstOld['StoreID'] or $second['StoreID']!=$secondOld['StoreID']){
					//$log = "[ERROR]pid:$pid;old first:({$firstOld['bid']}){$firstOld['StoreID']}={$firstOld['price']};";
					//$log.= "({$secondOld['bid']})old second:{$secondOld['StoreID']}={$secondOld['price']};";
					//$log.= "first:({$first['bid']}){$first['StoreID']}={$first['price']};";
					//$log.= "second:({$second['bid']}){$second['StoreID']}={$second['price']};\n";
					//file_put_contents('/home/jessee/log/bid.log', $log, FILE_APPEND);
					
				}
				if ($curBidPrice['StoreID']==$first['StoreID']){
					// the bidder of current top bid is the first auto bidder.
					if ($first['StoreID']==$second['StoreID']){
						return true;
					}
					$arrayAutoBid = array(
						'pid'		=> $pid,
						'StoreID'	=> $first['StoreID'],
						'bid_time'	=> time(),
						//'price'		=> $curBidPrice['price']+1,
						'reviewkey'	=> substr(md5(uniqid(mt_rand(), 1)), 0, 8),
						'isreviewed'=> 0,
						'is_auto'	=> 'no'
					);
					if (intval($second['price']*100) > intval($curBidPrice['price']*100)){
						if (intval($first['price']*100) == intval($second['price']*100)) {
							$arrayAutoBid['price'] = $second['price'];
						}else{
							$arrayAutoBid['price'] = $second['price']+$this->bidcount;
						}
					}elseif(intval($second['price']*100) == intval($curBidPrice['price']*100)){
						return true;
					}else{
						return true;
					}
					//$log = "pid:$pid;current:{$curBidPrice['price']};";
					//$log.= "fistr:{$first['StoreID']}={$first['price']};";
					//$log.= "sencond:{$second['StoreID']}={$second['price']};\n";
					//file_put_contents('/home/jessee/log/bid.log', $log, FILE_APPEND);
					if ($this->dbcon->insert_record($this->table."product_bid",$arrayAutoBid)){
						//$log = "Insert success, Data: StoreID={$arrayAutoBid['StoreID']};price:{$arrayAutoBid['price']}\n";
						//file_put_contents('/home/jessee/log/bid.log', $log, FILE_APPEND);
						$updateAuction = array(
							'winner_id'	=> $arrayAutoBid['StoreID'],
							'cur_price' => $arrayAutoBid['price']
						);
						if($auctionInfo['end_stamp']-time()<=10){
							$updateAuction['end_stamp'] = $auctionInfo['end_stamp']+20;
						}
						$condition = "where pid='$pid'";
						$this->dbcon->update_record($this->table."product_auction",$updateAuction,$condition);
						/***update product table price**/
						$this->dbcon->execute_query("update {$this->table}product SET `price`={$updateAuction['cur_price']}  WHERE pid='$pid'");
						
						$query = "select winner_id,cur_price from {$this->table}product_auction where pid='$pid'";
						$this->dbcon->execute_query($query);
						$check = $this->dbcon->fetch_records();
						$check = $check[0];
						if ($check['winner_id'] == $arrayAutoBid['StoreID'] 
							&& (int)($check['cur_price']*100)==(int)($arrayAutoBid['price']*100)){
							return true;
						}else{
							return false;
						}
					}else{
						//$log = "Insert fail, Data: StoreID={$arrayAutoBid['StoreID']};price:{$arrayAutoBid['price']}\n";
						//file_put_contents('/home/jessee/log/bid.log', $log, FILE_APPEND);
						return true;
					}
				}else{
					// the bidder of current top bid is not the first auto bidder.
					$arrayAutoBid = array(
						'pid'		=> $pid,
						'bid_time'	=> time(),
						'StoreID'	=> $first['StoreID'],
						//'price'		=> (int)$second['price']+$this->bidcount,
						'reviewkey'	=> substr(md5(uniqid(mt_rand(), 1)), 0, 8),
						'isreviewed'=> 0,
						'is_auto'	=> 'no'
					);
					if (intval($curBidPrice['price']*100) >= intval($second['price']*100)){
						$arrayAutoBid['price'] = $curBidPrice['price'] + $this->bidcount;
					}else{
						if (intval($second['price']*100) < intval($first['price']*100)){
							$arrayAutoBid['price'] = $second['price'] + $this->bidcount;
						}else{
							$arrayAutoBid['price'] = $second['price'];
						}
					}

					//$log = "pid:$pid;current:{$curBidPrice['price']};";
					//$log.= "fistr:{$first['StoreID']}={$first['price']};";
					//$log.= "sencond:{$second['StoreID']}={$second['price']};\n";
					//file_put_contents('/home/jessee/log/bid.log', $log, FILE_APPEND);
					if ($this->dbcon->insert_record($this->table."product_bid",$arrayAutoBid)){
						//$log = "Insert success, Data: StoreID={$arrayAutoBid['StoreID']};price:{$arrayAutoBid['price']}\n";
						//file_put_contents('/home/jessee/log/bid.log', $log, FILE_APPEND);
						$updateAuction = array(
							'winner_id'	=> $arrayAutoBid['StoreID'],
							'cur_price' => $arrayAutoBid['price']
						);
						if($auctionInfo['end_stamp']-time()<=10){
							$updateAuction['end_stamp'] = $auctionInfo['end_stamp']+20;
						}
						$condition = "where pid='$pid'";
						$this->dbcon->update_record($this->table."product_auction",$updateAuction,$condition);
						/***update product table price**/
						$this->dbcon->execute_query("update {$this->table}product SET `price`={$updateAuction['cur_price']}  WHERE pid='$pid'");
//						$query = "update {$this->table}product_auction set winner_id={$updateAuction['winner_id']},"
//								."cur_price=cur_price+".($updateAuction['cur_price']-$auctionInfo['cur_price']).",status='1'  ".$condition;
//						$this->dbcon->execute_query($query);
								
						$query = "select winner_id,cur_price from {$this->table}product_auction where pid='$pid'";
						$this->dbcon->execute_query($query);
						$check = $this->dbcon->fetch_records();
						$check = $check[0];
						if ($check['winner_id'] == $arrayAutoBid['StoreID'] 
							&& (int)($check['cur_price']*100)==(int)($arrayAutoBid['price']*100)){
							return true;
						}else{
							return false;
						}
					}else{
						//$log = "Insert fail, Data: StoreID={$arrayAutoBid['StoreID']};price:{$arrayAutoBid['price']}\n";
						//file_put_contents('/home/jessee/log/bid.log', $log, FILE_APPEND);
						return false;
					}
				}
			}else{
				// generate the bid record with initial price
				//echo "first bid.\n";
				// get initial price
				$first = $arrayAutoBid[0];
				$query = "select initial_price from {$this->table}product_auction where pid=$pid";
				$this->dbcon->execute_query($query);
				$initialPrice = $this->dbcon->fetch_records();
				if (!$initialPrice)return false;
				$initialPrice = $initialPrice[0];
				//var_dump($initialPrice);
				$arrayAutoBid = array(
					'pid'		=> $pid,
					'StoreID'	=> $first['StoreID'],
					'bid_time'	=> time(),
					'price'		=> $initialPrice['initial_price'],
					'reviewkey'	=> substr(md5(uniqid(mt_rand(), 1)), 0, 8),
					'isreviewed'=> 0,
					'is_auto'	=> 'no'
				);
				//var_dump($arrayAutoBid);
				$query = "select StoreID,price from {$this->table}product_bid "
						."where pid='$pid' and is_auto='no' order by price desc limit 1";
				$this->dbcon->execute_query($query);
				$curPrice = $this->dbcon->fetch_records();
				//var_dump($curPrice);
				if ($curPrice){
					$curPrice = $curPrice[0];
					if ($curPrice['StoreID'] == $first['StoreID']){
						return true;
					}
					if (intval($curPrice['price']*100) < intval($first['price']*100)){
						$arrayAutoBid['price'] = $curPrice['price'] + $this->bidcount;
					}elseif (intval($curPrice['price']*100) == intval($arrayAutoBid['price']*100)){
						$arrayAutoBid['price'] = $curPrice['price'];
					}else{
						return false;
					}
				}
//				if ($_SESSION['ShopID'] == '854837'){
//					//var_dump($initialPrice);
//					var_dump($arrayAutoBid);
//					return false;
//				}
				if ($this->dbcon->insert_record($this->table."product_bid",$arrayAutoBid)){
					$updateAuction = array(
						'winner_id'	=> $arrayAutoBid['StoreID'],
						'cur_price' => $arrayAutoBid['price'],
						'status'	=> 1
					);
					if($auctionInfo['end_stamp']-time()<=10){
						$updateAuction['end_stamp'] = $auctionInfo['end_stamp']+20;
					}
					$condition = "where pid='$pid'";
					$this->dbcon->update_record($this->table."product_auction",$updateAuction,$condition);
					/***update product table price**/
					$this->dbcon->execute_query("update {$this->table}product SET `price`={$updateAuction['cur_price']}  WHERE pid='$pid'");
					
					$query = "select winner_id,cur_price from {$this->table}product_auction where pid='$pid'";
					$this->dbcon->execute_query($query);
					$check = $this->dbcon->fetch_records();
					$check = $check[0];
					if ($check['winner_id'] == $arrayAutoBid['StoreID'] 
						&& (int)($check['cur_price']*100)==(int)($arrayAutoBid['price']*100)){
						return true;
					}else{
						return false;
					}
				}else return false;
			}
		}
	}
/*** Wed Mar 24 16:52:08 CST 2010 roy.luo**/
	function autobidProduct($pid=0,$StoreID=0,$price=0){
		if(!$pid || !$StoreID || !$price){return false;}
		
		$check_query = "SELECT StoreID,is_certified,pid FROM {$this->table}product where pid='$pid' ";
		$this->dbcon->execute_query($check_query);
		if($check_result = $this->dbcon->fetch_records(true)){
			if($StoreID==$check_result[0]['StoreID']){
				return "Sorry, you can't bid the items in your store.";
			}
		}

         /**
         * Check Certified
         * @author ronald
         */
        if($check_result[0]['is_certified']){
            $certifiedObj = new ProductCertified();
            $isAuthorised = $certifiedObj->getApplyState($pid);
            if(is_null($isAuthorised) || $isAuthorised == 2){
                return "{"."state:'notCertified', storeId:'{$check_result[0]['StoreID']}', productId:'{$check_result[0]['pid']}'"."}";
            }elseif($isAuthorised == 0){
                return "{"."state:'pedding', storeId:'{$check_result[0]['StoreID']}', productId:'{$check_result[0]['pid']}'"."}";
            }
        }
		
		$this->dbcon->beginTrans();
		$query = "SELECT * FROM {$this->table}product_auction WHERE pid='$pid' for update";
		$this->dbcon->execute_query($query);
		$proBidInfo = $this->dbcon->fetch_records(true);
		if($proBidInfo){
			if($proBidInfo[0]['end_stamp'] - time()<=0){
				return "Your bid cannot be accepted. This auction has already closed. ";
			}
			$query = "SELECT * FROM {$this->table}product_bid WHERE pid='$pid' for update";
			$this->dbcon->execute_query($query);
//			$query = "SELECT * FROM {$this->table}product_bid WHERE pid='$pid' and"
//					." is_auto='no' order by price desc limit 1";
//			$this->dbcon->execute_query($query);
//			$topPrice = $this->dbcon->fetch_records();
//			if (!$topPrice){
//				$topPrice = $proBidInfo[0]['price'];
//			}else{
//				$topPrice = $topPrice[0]['price'];
//			}
//			$addval = $price - $topPrice;
			$addval = $price - $proBidInfo[0]['cur_price'];
			if(intval($addval)<intval(($this->bidcount))){
				return "Your Bid amount of $price is too low. Please increase your bid and try again.";
			}elseif (intval($addval/($this->bidcount))!=intval($addval)/($this->bidcount)){
				return "The value should be whole doller value only. Please change and try again.";
			}
			$query = "SELECT price,StoreID FROM {$this->table}product_bid WHERE pid='$pid' and is_auto='yes' and StoreID='$StoreID' order by price DESC limit 1 for update";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$query = "";
			if($result){
				if(intval($result[0]['price'])>=intval($price)){
					return "Sorry, the maximum bid is lower than the one you set before. Please change and try again.";
				}else{
					$query = "insert into {$this->table}product_bid(`pid`,`StoreID`,`bid_time`,`price`,`is_auto`) values('$pid','$StoreID','".time()."','$price','yes')";
				}
			}else{
				$query = "insert into {$this->table}product_bid(`pid`,`StoreID`,`bid_time`,`price`,`is_auto`) values('$pid','$StoreID','".time()."','$price','yes')";
			}
			if($query){
				/**set auto bid **/
				$retval = 0;
				if ($this->dbcon->execute_query($query)){
					$retval = $this->autobidFunc($pid,'auto', $price, $StoreID);
					if($StoreID=='854836'&&($this->paypal_info['paypal_mode'] == 0)){sleep(30);}
					if ($retval === false) {
						$this->dbcon->rollbackTrans();
						$this->dbcon->endTrans();
						return false;
					} else {
						$this->dbcon->commitTrans();
						$this->dbcon->endTrans();
						return "Your bid has been submitted successfully.";
					}
				}else{
					$this->dbcon->rollbackTrans();
					$this->dbcon->endTrans();
					return false;
				}
			}else{
				$this->dbcon->rollbackTrans();
				$this->dbcon->endTrans();
				return false;
			}
		}else{
			$this->dbcon->rollbackTrans();
			$this->dbcon->endTrans();
			return false;
		}
		return true;
	}
	
	/***2010 03 24 roy.luo**/
	function watchItem($pid,$StoreID){
		$query = "SELECT * FROM {$this->table}watchitem where pid='$pid' and StoreID='$StoreID'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if($result&&$result[0]['id']){
			return "The item exists in your watch list already.";
		}else{
			$query = "insert into {$this->table}watchitem (`pid`,`StoreID`) values('$pid','$StoreID')";
			if($this->dbcon->execute_query($query)){
				return "The item has been added to your watch list successfully.";
			}else{
				return "It's unsuccessful to add this item to your watch list.";
			}
		}
	}


	function watchItemList($StoreID=0,$curpage=1){
		
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$arrResult = array();
		
		$sFrom = "FROM {$this->table}watchitem wi ".
				 " LEFT JOIN {$this->table}product p ON wi.pid = p.pid ".
				 " LEFT JOIN {$this->table}product_auction au ON wi.pid = au.pid ".
				 " LEFT JOIN {$this->table}image s ON wi.pid = s.pid ".
				 " LEFT JOIN {$this->table}bu_detail bu ON bu.StoreID = p.StoreID ".
				 " LEFT JOIN {$this->table}bu_detail wbu ON wbu.StoreID = au.winner_id ".
				 " WHERE wi.StoreID='$StoreID' AND IF(s.StoreID>0,s.attrib=0 and sort=0 , 1=1) ";
		
		$query = "SELECT count(*) as num $sFrom";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0]['num'];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		$now = time();
		$query = "SELECT wi.id,p.*,bu.bu_name, bu.bu_urlstring,wbu.bu_nickname,s.smallPicture,au.cur_price,au.reserve_price, au.end_stamp-$now as end_stamp $sFrom limit $start,$perPage";
		$this->dbcon->execute_query($query);
		$result =$this->dbcon->fetch_records(true);
		
		$arrResult['watchlist']=$result;
		$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'append'     => true,
				'urlVar'     => 'p',
				'path'		 => '/',
				'fileName'   => '%d',
				);
		$pager = Pager::factory($params);
		$arrResult['links'] 		= $pager->getLinks();
		$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
		
		return $arrResult;
	}
	function delwatchItme($wid){
		$query = "DELETE FROM {$this->table}watchitem WHERE id='{$wid}'";
		return $this->dbcon->execute_query($query);
	}
	
	function getAudioList(){
		$query = "SELECT * FROM {$this->table}bid_audio where bid_status= 1 ORDER BY bid_order asc";
		$this->dbcon->execute_query($query);
		$list = $this->dbcon->fetch_records(true);
		if ($list){
			return $list;
		}else{
			return false;
		}
	}
}
?>