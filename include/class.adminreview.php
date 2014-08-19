<?php
/**
 * Sun Oct 12 09:22:37 GMT+08:00 2008 09:22:37
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * admin review class and function
 * ------------------------------------------------------------
 * \include\class.adminreview.php
 */

class adminReview extends common {
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
	 * Get Reailer Expiry Days
	 *
	 * @return array
	 */
	function getReviewsExpriy()	{
		$arrResult	= null;

		$_query = "select body from ".$this->table."cms where id in('30','31') order by id";
		$this->dbcon->execute_query($_query) ;
		$intTemp = $this->dbcon->fetch_records() ;
		$arrResult = array("real"=>$intTemp[0][0],'free'=>$intTemp[1][0]);

		return $arrResult;
	}

	/**
	 * set delete expriy of  reviews
	 *
	 * @param array $arrForm
	 * @return string
	 */
	function setReviewsDelExpriy($arrForm){
		$strResult	=	'';
		if($arrForm['setreal']>0 && $arrForm['setfree']>0){
			$_query = "update ".$this->table."cms set body='".$arrForm['setreal']."' where id='30'";
			$this->dbcon->execute_query($_query) ;

			$_query = "update ".$this->table."cms set body='".$arrForm['setfree']."' where id='31'";
			$this->dbcon->execute_query($_query) ;

			$strResult = "The item has been updated.";
		}
		else
		{
			$strResult = "The item update faild.";
		}

		return $strResult;
	}


	function getReviewsList($pageno=1, $strParams=''){
		$arrResult	=	null;
		$perPage	=	5;
		$pageno		=	$pageno ? $pageno : 1;
		
		if ($strParams) {
			$arrParams = unserialize($strParams);
			if ($arrParams['searchstore']) {
				$wheres	= " and review.bu_name like '%". $this->__StrReplace($arrParams['searchstore'])."%'";
			}
		}

		$_query = "select count(*) ".
		" from ".$this->table."review as review " .
		" left join ".$this->table."login as login on login.id= review.user_id ".
		" left join ".$this->table."bu_detail as bu_detail on bu_detail.StoreID=login.StoreID ".
		" left join ".$this->table."state as state on bu_detail.bu_state=state.id ".
		" where upid=0 ". (empty($wheres) ? "" : $wheres) .
		" order by review.post_date asc";

		$this->dbcon->execute_query($_query);
		$count = $this->dbcon->fetch_records();
		$count = $count[0][0];
		
		($pageno * $perPage > $count) ? $pageno = ceil($count/$perPage) : '';
		$start	= ($pageno-1) * $perPage;

		if ($count) {
			$_query = "select review.*,DATE_FORMAT(review.post_date,'".DATAFORMAT_DB."') as fdate, bu_detail.bu_suburb, bu_detail.bu_nickname, state.description, review.bu_name as storename ".
			" from ".$this->table."review as review " .
			" left join ".$this->table."login as login on login.id= review.user_id ".
			" left join ".$this->table."bu_detail as bu_detail on bu_detail.StoreID=login.StoreID ".
			" left join ".$this->table."state as state on bu_detail.bu_state=state.id ".
			" where upid=0 ". (empty($wheres) ? "" : $wheres) .
			" order by review.post_date desc limit $start, $perPage";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			$arrResult['query']	=  $count;
			$i = 0;
			$arrResult['list']	=	$arrTemp;
			foreach($arrTemp as $val){
				$arrResult['list'][$i]['storename']	=	str_replace('\'','\\\'',$val['storename']);
				if ($i%2==0){
					$arrResult['list'][$i]['row_style']= "reviewRow";
				}else{
					$arrResult['list'][$i]['row_style']= "reviewRow1";
				}

				$rating = '';
				for($j=0;$j<floor($val['rating']);$j++){
					$rating.= '<img src="../images/star1.gif" border=0 align="absmiddle">';
				}
				if(floor($val['rating']) != $val['rating'])
				{
					$j ++;
					$rating.= '<img src="../images/star0.gif" border=0 align="absmiddle">';
				}
				$arrResult['list'][$i]['rating'] = $rating;

				// get count of comments
				$_query = "select count(*) from ".$this->table."review where upid = ".$val['review_id'];
				$this->dbcon->execute_query($_query);
				$comment_count = $this->dbcon->fetch_records();
				$comment_count = $comment_count[0][0];
				if ($comment_count > 0){
					$_query = "select review.*,DATE_FORMAT(review.post_date,'".DATAFORMAT_DB."') as fdate, bu_detail.bu_suburb, bu_detail.bu_nickname, bu_detail.bu_name, state.description, login.level ".
					" from ".$this->table."review as review ".
					" Left Join ".$this->table."login as login ON review.user_id=login.id ".
					" Left join ".$this->table."bu_detail as bu_detail ON login.StoreID=bu_detail.StoreID ".
					" LEFT JOIN ".$this->table."state as state ON bu_detail.bu_state=state.id ".
					" where review.upid=".$val['review_id']." order by post_date asc";
					$this->dbcon->execute_query($_query);
					$arrComment = $this->dbcon->fetch_records(true);

					$arrResult['list'][$i]['sublist'] = $arrComment ;
				}

				$i++;
			}
			$params = array(
			'perPage'    => "$perPage",
			'totalItems' => "$count",
			'currentPage'=> "$pageno",
			'delta'      => 15,
			'onclick'	 => 'javascript:xajax_getReviewsList(\'%d\',xajax.$(\'searchparam\').value);return false;',
			'append'     => false,
			'urlVar'     => 'pageno',
			'path'		 => '#',
			'fileName'   => '%d',
			);
			$pager = & Pager::factory($params);
			$arrResult['links'] 		= $pager->getLinks();
			$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			$arrResult['searchparam']	=  $strParam;
			$arrResult['pageno']		=  $pageno;
			unset($pager,$params);
		}

		return $arrResult;
	}

	public function reviewsDelete($reviewID){
		$strResult	=	'';

		$_query = "delete from ".$this -> table."review where (review_id='$reviewID' or upid='$reviewID')";
		if ($this -> dbcon -> execute_query($_query)) {
			$strResult = $this->replaceLangVar($this->lang['pub_clew']['successful'],array('Review',$this->lang['operation']['delete']));
		}else {
			$strResult = $this->replaceLangVar($this->lang['pub_clew']['faild'],array('Review',$this->lang['operation']['delete']));
		}

		return $strResult;
	}

	public function reviewsUpdateOption($objForm){
		$strResult	=	'';

		$_query = "update ".$this -> table."review set content='".$objForm["message"]."' where (review_id='$objForm[review_id]')";
		if ($this -> dbcon -> execute_query($_query)) {
			$strResult = $this->replaceLangVar($this->lang['pub_clew']['successful'],array('Review',$this->lang['operation']['update']));
		}else {
			$strResult = $this->replaceLangVar($this->lang['pub_clew']['faild'],array('Review',$this->lang['operation']['update']));
		}

		return $strResult;
	}

	public function reviewsUpdate($reviewID){
		$arrResult	=	null;

		$_query = "select review_id,content from ".$this -> table."review where review_id='$reviewID'";
		$this -> dbcon -> execute_query($_query);
		$arrTemp = $this -> dbcon -> fetch_records(true);

		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
		}

		return $arrResult;
	}
}

/*********************
* xajax function
**********************/

function saveReviewsExpriy($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminReview	=	&$GLOBALS['objAdminReview'];

	$messages = $objAdminReview -> setReviewsDelExpriy($objForm);

	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);
	$objResponse -> alert($messages);

	return $objResponse;
}

function getReviewsList($pageno, $strParams){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$smarty			= &$GLOBALS['smarty'];
	$objAdminReview	=	&$GLOBALS['objAdminReview'];

	//$arrParams = unserialize($params);
	$req['list'] = $objAdminReview -> getReviewsList($pageno,$strParams);
	$req['nofull'] = true ;
	$req['display'] = 'details' ;
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_review.tpl');

	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

function reviewsSearch($objForm){
	$objResponse 	= new xajaxResponse();
	$smarty			= &$GLOBALS['smarty'];
	$objAdminReview	=	&$GLOBALS['objAdminReview'];

	$strParams = serialize($objForm);
	$req['list'] = $objAdminReview -> getReviewsList(1,$strParams);
	$req['nofull'] = true ;
	$req['display'] = 'details' ;
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_review.tpl');

	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign('searchparam', 'value' , $strParams);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}

function reviewsDelete($reviewID){
	$objResponse 	= new xajaxResponse();
	$objAdminReview	=	&$GLOBALS['objAdminReview'];

	$message = $objAdminReview -> reviewsDelete($reviewID);
	$objResponse -> script("xajax_getReviewsList(xajax.$('pageno').value, xajax.$('searchparam').value)");

	$objResponse -> alert($message);
	return $objResponse;
}

function reviewsUpdate($reviewID){
	$objResponse 	= new xajaxResponse();
	$smarty			= &$GLOBALS['smarty'];
	$objAdminReview	=	&$GLOBALS['objAdminReview'];

	$req['list']	=	$objAdminReview -> reviewsUpdate($reviewID);
	$req['nofull'] 	= true ;
	$req['display']	= 'update' ;
	$smarty -> assign('req',	$req);
	$content = $smarty -> fetch('admin_review.tpl');

	$objResponse -> assign("tabledatalist",'innerHTML',$content);

	return $objResponse;
}

function reviewsUpdateOption($objForm){
	$message = '';
	$objResponse 	= new xajaxResponse();
	$objAdminReview	=	&$GLOBALS['objAdminReview'];

	$message = $objAdminReview -> reviewsUpdateOption($objForm);

	$objResponse -> script("xajax_getReviewsList($objForm[pageno], '$objForm[searchparam]')");

	$objResponse -> alert($message);
	return $objResponse;
}
?>