<?php
/**
 * $id class.socreview.php jacky.zhou Fri Jun 20 11:40:44 CST 2008 11:40:44 $
 * 
 * functionality of the soc order review
 * 
 * @author jacky.zhou
 * @package buyblitz
 * @subpackage include
 * 
 */

class socReviewClass extends socClass {
	
	
	/**
	 * html redirect
	 * @param	string $url
	 * @param 	string $msg
	 * 
	 * return null
	 */

	function htmlRedirect($url = 'index.php', $msg = '') {
		if ($msg != '') {
			echo "<script>alert(' ". $msg ." ');</script>";
		}
		echo "<script>window.location.href=' ". $url ." '</script>";
		exit();
	}
	
	/**
	 * have the user reviewed..
	 * @param 	string $reviewKey
	 * @return boolean
	 */
	function buyerReviewed($reviewKey) {
		
		$isreviewed = 0;
		$_query = "SELECT buyer_reviewed FROM ".$this->table."order_reviewref WHERE reviewkey='".$reviewKey."'";

		$result = $this->dbcon->execute_query($_query);

		if ($result) {
			$bid = $this->dbcon->fetch_records(true);
			if ($bid[0]['buyer_reviewed'] == 1) {
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
		$_query = "SELECT seller_reviewed FROM ".$this->table."order_reviewref WHERE reviewkey='".$reviewKey."'";

		$result = $this->dbcon->execute_query($_query);

		if ($result) {
			$bid = $this->dbcon->fetch_records(true);
			if ($bid[0]['seller_reviewed'] == 1) {
				$isreviewed = 1;
			}
		}
		return $isreviewed;
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
	
	function newReview() {
		$StoreID = intval(isset($_REQUEST['StoreID']) ? $_REQUEST['StoreID'] : 0);
		$pid = intval(isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0);
		$userlevel = addslashes(isset($_REQUEST['level']) ? $_REQUEST['level'] : '');
		$reviewkey = trim(isset($_REQUEST['reviewkey']) ? $_REQUEST['reviewkey'] : '');
		
		if ($StoreID == 0 || ((($pid == 0) || ($reviewkey == '')) && $userlevel != 'tyew8b')) {
			$this->htmlRedirect();
		}
		

		if ($userlevel == '1bp3a') {
			
			if ( $this->sellerReviewed($reviewkey) == 1) {
				$this->htmlRedirect('index.php', 'You have submitted a review for this user.');
			} else {
				$userinfo = $this->getUser($StoreID);
				$arrResult['storename'] = $userinfo['bu_name'];
				$arrResult['storeurl'] = $userinfo['bu_urlstring'];
				$arrResult['type'] = 'user';
				$arrResult['StoreID'] = $StoreID;
				$arrResult['reviewkey'] = $reviewkey;
				$arrResult['review_type'] = 'product';
				$arrResult['content_type'] = 'review';

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
				$_query = "SELECT * FROM " . $this->table . "bu_detail WHERE StoreID = ".$sStoreID;
				if ($this->dbcon->execute_query($_query)) {
					$storeInfo = $this->dbcon->fetch_records(true);
					$arrResult['storename'] = $storeInfo[0]['bu_name'];
					$arrResult['storeurl'] = $storeInfo[0]['bu_urlstring'];
					$arrResult['type'] = 'store';
					$arrResult['StoreID'] = $sStoreID;
					$arrResult['reviewkey'] = $reviewkey;
					$arrResult['review_type'] = 'product';
					$arrResult['content_type'] = 'review';

					$_query = "SELECT id FROM ". $this->table ."login WHERE StoreID=".$StoreID;
					$this->dbcon->execute_query($_query);
					$loginInfo = $this->dbcon->fetch_records(true);
					if ($loginInfo) {
						$arrResult['user_id'] = $loginInfo[0]['id'];
					} else {
						$this->htmlRedirect();
					}
				}
			}
		} elseif ($userlevel == 'tyew8b') {
			if (empty($_SESSION['LOGIN'])){
				echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
				exit;
			}
			
			$userinfo = $this->getUser($StoreID);
			$arrResult['storename'] = $userinfo['bu_name'];
			$arrResult['storeurl'] = $userinfo['bu_urlstring'];
			$arrResult['type'] = 'store';
			$arrResult['StoreID'] = $StoreID;
			$arrResult['reviewkey'] = '';
			$arrResult['review_type'] = 'store';
			$arrResult['content_type'] = 'review';
			$arrResult['is_foodwine'] = true;

			if ($_SESSION['UserID']) {
				$arrResult['user_id'] = $_SESSION['UserID'];
			} else {
				$this->htmlRedirect();
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
		$storename = addslashes($storename);
		
		$_query = "INSERT INTO " .$this->table ."review (review_type, content_type, type, StoreID, bu_name, user_id, rating, content, post_date,status) VALUES('$review_type', '$content_type', '$type', '$StoreID', '$storename', '$user_id', '$rating', '$message', now(),'$status')";
		if (isset($rating) && $rating > 0 && !check_badwords($message)){
			if ($this->dbcon->execute_query($_query)) {
				if ($reviewkey) {
					if ($type == 'user') {
						$_query = "UPDATE ".$this->table."order_reviewref SET seller_reviewed=1 WHERE reviewkey='".$reviewkey."'";
						$this->dbcon->execute_query($_query);
					} elseif ($type == 'store') {
						$_query = "UPDATE ".$this->table."order_reviewref SET buyer_reviewed=1 WHERE reviewkey='".$reviewkey."'";
						$this->dbcon->execute_query($_query);					
					}	
				}				
				$msg = 'Review inserted successfully.';
			} else {
				$msg = 'Review inserted failed.';
			}
		} elseif(check_badwords($message)) {
			$msg = "Please keep our website wholesome and clean by refraining from using vulgar words!";
		}else{
			$msg = "Rating is required.";
		}
		
		if ($reviewkey) {
			$this->htmlRedirect('index.php', $msg);
		} else {			
			$userinfo = $this->getUser($StoreID);
			$this->htmlRedirect($userinfo['bu_urlstring'], $msg);
		}
		
	}
	
	function delReview($rid)
	{
		$rid = intval($rid);
		if (empty($rid)) {
			return false;
		}
		
		$sql = "DELETE FROM " .$this->table ."review WHERE review_id='$rid'";
		
		return $this->dbcon->execute_query($sql);
	}
}