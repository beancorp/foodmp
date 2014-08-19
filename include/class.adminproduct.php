<?php
/**
 * Mon Oct 13 13:31:46 GMT+08:00 2008 13:31:46
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * product function and class of admin
 * ------------------------------------------------------------
 * \include\class.adminproduct.php
 */

class adminProduct extends common {
	var $dbcon 	= 	null;
	var $table	=	'';
	var $smarty = 	null;
	var $lang	=	null;

	/**
	 * @return void 
	 */
	function __construct()
	{
		$this -> dbcon  = &$GLOBALS['dbcon'];
		$this -> table	= &$GLOBALS['table'];
		$this -> smarty = &$GLOBALS['smarty'];
		$this -> lang	= &$GLOBALS['_LANG'];
	}

	/**
    * @return void 
    */
	function __destruct(){
		unset($this->dbcon,$this -> table,$this->smarty,$this -> lang);
	}


	public function getProductCategory($fatherID=0, $issort=true,$field='',$order='asc'){
		$arrResult	=	null;
		/*if ($issort) {
			$order	=	'order by sort';
		}else{
			$order	=	'order by fsort ';
		}*/
		$whorder = "";
		switch ($field){
			case 'id':
				$whorder .= " order by id $order "; 
				break;
			case 'name':
				$whorder .= " order by name $order "; 
				break;
			case 'sort':
				$whorder .= " order by sort $order "; 
				break;
			default:
				$whorder .= " order by sort asc ";
				break;
		}
		
		$query = "select * from ".$this->table."product_category where fid='$fatherID' and disabled=0 $whorder";

		$this->dbcon -> execute_query($query);
		$arrTemp = $this->dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult['list'] = $arrTemp;
		}
		$arrResult['fid'] = $fatherID;
		$arrResult['sort']['field']=$field;
		$arrResult['sort']['order']=$order;
		
		return $arrResult;
	}
	
	public function getlistProductCategory($fatherID=0){
		$arrResult	=	null;
		$query = "select * from ".$this->table."product_category where fid='$fatherID' and disabled=0 order by name asc";

		$this->dbcon -> execute_query($query);
		$arrTemp = $this->dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult['list'] = $arrTemp;
		}
		$arrResult['fid'] = $fatherID;
		
		return $arrResult;
	}

	public function categoryAdd($arrParams){
		$arrResult	=	null;
		$arrResult  = array('operate' => 'add');
		return $arrResult;
	}

	public function categoryUpdate($ID){
		$arrResult	=	null;
		if ($ID) {
			$_query	= "select * from " . $this->table. "product_category where id='$ID'";
			$this -> dbcon -> execute_query($_query);
			$arrTemp = $this -> dbcon ->fetch_records(true);
			if (is_array($arrTemp)){
				$arrResult = $arrTemp[0];
			}
			$arrResult['operate']  = 'update';
		}
		return $arrResult;
	}

	public function categoryUpdateOperate($objForm){
		$strResult	=	'';

		if (!is_array($objForm)) {
			$strResult = 'Faild.';
		}elseif ($objForm['operate'] == 'add') {

			$query = "select count(*) as num from ".$this->table."product_category where name='".$objForm['name']."'";
			$this->dbcon->execute_query($query);
			$arrTemp = $this->dbcon->fetch_records(true);

			if ($arrTemp[0]['num'] > 0){
				$strResult	= 'Category name exists.';
			}
			$arrSetting = array(
			'fid'	=>	$objForm['fid'],
			'name'	=>	$objForm['name'],
			'datec'	=>	time()
			);
			if (isset($objForm['order']) and is_numeric($objForm['order'])){
				$arrSetting['sort'] = $objForm['order'];
			}
			if ($this->dbcon->insert_record($this->table."product_category",$arrSetting)){
				$insert_id = $this->dbcon->lastInsertId();
				if (!isset($objForm['order']) || !is_numeric($objForm['order'])){
					$arrSetting = array('sort'=>$insert_id);
					$condition = "where id=$insert_id";
					$this->dbcon->update_record($this->table."product_category",$arrSetting,$condition);
				}
				$strResult	= 'Add catgory successfully.';
			}else{
				$strResult	= 'Failed to add category.';
			}

		}elseif($objForm['operate']=='update'){

			$query = "select count(*) as num from ".$this->table."product_category where name='".$objForm['name']."' and id!=".$objForm['id'];
			$this->dbcon->execute_query($query);
			$arrTemp = $this->dbcon->fetch_records(true);

			if ($arrTemp[0]['num'] > 0){
				$strResult	='Category name exists.';
			}
			$arrSetting = array(
			'name'	=>	$objForm['name'],
			);
			if (isset($objForm['order']) and is_numeric($objForm['order'])){
				$arrSetting['sort'] = $objForm['order'];
			}
			$arrSetting['image'] = $objForm['image'];
			$condition = "where id=".$objForm['id'];
			if ($this->dbcon->update_record($this->table."product_category",$arrSetting,$condition)){
				$strResult	='Update catgory successfully.';
			}else{
				$strResult	='Failed to update category.';
			}

		}else {
			$strResult = 'Operate Faild.';
		}

		return $strResult;
	}

	/**
	 * delete category of product
	 *
	 * @param int  $ID
	 * @return boolean
	 */
	function categoryDelete($ID){
		$strResult	=	'';

		if (!$ID or !is_numeric($ID)){
			return false;
		}
		$query = "delete from ".$this->table."product_category where id=$ID";
		if ($this->dbcon->execute_query($query)) {
			$strResult = $this->replaceLangVar($this->lang['pub_clew']['successful'],array('Category',$this->lang['operation']['delete']));
		}else {
			$strResult = $this->replaceLangVar($this->lang['pub_clew']['faild'],array('Category',$this->lang['operation']['delete']));
		}
		return $strResult;
	}



	/**
	 * get article list of category
	 *
	 * @param int $cgid
	 * @param int $page
	 * @return array
	 */
	function getCategoryArticleList($cgid=1,$page=1){
		$arrResult = null;
		$item_per_page = 15;
		$order = $_REQUEST['order'];
		$field = $_REQUEST['field'];
		
		$_query = "select count(*) from ". $this -> table ."article where cgid = $cgid ";
		$this -> dbcon -> execute_query($_query);
		$total_count = $this -> dbcon ->fetch_records();
		$total_count = $total_count[0][0];
		$total_page = ceil($total_count/$item_per_page);
		if (!isset($page) or $page == ''){
			$page = 1;
		}
		$start = ($page - 1)*$item_per_page;
		
		$whorder    =   "";
		switch ($field){
			case 'id':
				$whorder .= " order by id $order "; 
				break;
			case 'cgid':
				$whorder .= " order by cgid $order "; 
				break;
			case 'title':
				$whorder .= " order by title $order "; 
				break;
			case 'state':
				$whorder .= " order by state $order "; 
				break;
			default:
				break;
		}
		
		$titles = "id,cgid,title,context,state";
		$_query = "select $titles from ". $this -> table ."article where cgid= '$cgid' $whorder limit $start,$item_per_page";

		$this -> dbcon -> execute_query($_query);
		$arrTemp=	$this -> dbcon -> fetch_records(true);

		if (is_array($arrTemp)) {
			$arrResult['categoryArticleList']= $arrTemp;
			$arrResult['page_navi']	= '';
			$arrResult['page']	= $page;

			$params = array(
			'perPage'    => "$item_per_page",
			'totalItems' => "$total_count",
			'currentPage'=> "$page",
			'delta'      => 15,
			//'onclick'	 => 'javascript:xajax_customerGetList(\'%d\',\'tabledatalist\',xajax.$(\'searchparam\').value);return false;',
			//'append'     => false,
			'urlVar'     => 'p',
			//'path'		 => '#',
			//'fileName'   => '%d',
			);
			$pager = & Pager::factory($params);
			$arrResult['links'] 		= $pager->getLinks();
			$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			$arrResult['searchparam']	=  $strParam;
			//$arrResult['pageno']	=  $pageno;

			$arrResult['page_navi']	= $arrResult['links']['all'];
			$arrResult['page']	= $pageno;

			unset($pager,$params);
		}
		$arrResult['cgid']	= $cgid;
		$arrResult['page']	= $page;
		$arrResult['field']	= $field;
		$arrResult['order'] = $order;
		return $arrResult;
	}


	function getCategoryArticle($id=0){
		$arrResult = null;
		if (! $id) {
			$id = $_REQUEST['id'];
		}
		$arrResult['cgid'] = $_REQUEST['cgid'];

		$_query = "select * from ". $this->table."article where id='$id'";
		$this -> dbcon -> execute_query($_query);
		$arrTemp=	$this -> dbcon -> fetch_records(true);

		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
			$arrResult['content'] = $this->initEditor('content', "$arrResult[content]",'adminDefault',array('90%',350));
		}else {
			$arrResult['content'] = $this->initEditor('content', "",'adminDefault',array('90%',350));
		}
		$arrResult['page']	  = $_REQUEST['p'];
		return $arrResult;
	}


	function saveFeaturedCategories($arrParams) {
		$strResult	=	'';
		//set default state
		$_query = 'UPDATE '.$this->table.'product_category SET isfeatured=0, fsort=9';
		$this->dbcon->execute_query($_query);

		//save featured categoires
		$index = 0;
		if (is_array($arrParams)) {
			foreach ($arrParams['category'] as $k => $v) {
				$fsort = !empty($arrParams['fsort'][$k]) ? $arrParams['fsort'][$k] : 0;
				if ($index < 6) {
					$_query = 'UPDATE '.$this->table.'product_category SET isfeatured=1, fsort ='.$fsort.' WHERE id='.$k;
					$this->dbcon->execute_query($_query);
					$index ++;
				}
			}
			$strResult = $this->replaceLangVar($this->lang['pub_clew']['successful'],array('Category',$this->lang['operation']['update']));
		}else{
			$strResult = $this->replaceLangVar($this->lang['pub_clew']['faild'],array('Category',$this->lang['operation']['update']));
		}

		return $strResult;
	}
	
	public function getSeasonProductList($pageno=1,$strParam='',$notOld = true,$field="",$orders="asc"){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	18;

		$sqlWhere	=	"where 1 ";

		if ($strParam) {
			$arrParam = unserialize($strParam);
			if(!get_magic_quotes_runtime()){
				$arrParam = striaddslashes_deep($arrParam);
			}
			if(isset($arrParam['keyword']) && $arrParam['keyword']!=''){
				$sqlWhere	.=	" And product.title LIKE '%$arrParam[keyword]%'";
			}
			if(isset($arrParam['season']) && $arrParam['season']!=''){
				$sqlWhere	.=	" And product.season_ids LIKE '%{$arrParam['season']}%'";
			}
			if(isset($arrParam['typeid']) && $arrParam['typeid']!=''){
				$sqlWhere	.=	" And product.typeids LIKE '%{$arrParam['typeid']}%'";
			}
		}
                
		switch ($field){
			case 'id':
				$order = " order by product.pid $orders ";
				break;
			case 'title':
				$order = " order by product.title $orders ";
				break;
			case 'season':
				$order = " order by product.season $orders ";
				break;
			case 'typeid':
				$order = " order by product.typeids $orders ";
				break;
			default:
				$order = " order by product.pid $orders ";
				break;
		}
		
		$query ="select count(*) from ".$this->table."season_product AS product $sqlWhere order by pid ASC ";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		if ($totalNum) {
			$query = "select product.* \n".
					" from ".$this->table."season_product AS product \n".
					" $sqlWhere $order limit $start,$perPage";
			$arrResult['query']=$query;
			$this->dbcon->execute_query($query);
			$arrTemp	=	$this->dbcon->fetch_records(true);
                        
			if (is_array($arrTemp)) {
				$season_arr = getSeasonArray();
				foreach ($arrTemp as $key => $pro) {
					$season_ids = $pro['season_ids'];
					$ids_arr = explode(',', $season_ids);
					foreach ($ids_arr as $k => $sid) {
						foreach ($season_arr as $sea) {
							if ($sid == $sea['id']) {
								$pro['seasonname'] .= ($k == 0 ? '' : ', ') . $sea['title'];
							}
						}
					}
					$typeids = $pro['typeids'];
					$typeids_arr = explode(',', $typeids);
					foreach ($typeids_arr as $k => $tid) {
						for ($i = 1; $i <= 2; $i++) {
                                                    $title = $i == 1 ? 'Fruit' : 'Vegetables';
							if ($tid == $i) {
								$pro['typename'] .= ($k == 0 ? '' : ', ') . $title;
							}
						}
					}
					$arrTemp[$key] = $pro;
				}
				$arrResult['list']	=	& $arrTemp;

				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getSeasonProductList(\'%d\',xajax.getFormValues(\'mainForm\'),'.$notOld.',\''.$field.'\',\''.$orders.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = & Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			}
		}
		$arrResult['query'] = $query;
		$arrResult['sort']['page']=$pageno;
		$arrResult['sort']['field']=$field;
		$arrResult['sort']['notold']=$notOld;
		$arrResult['sort']['order']=$orders;
		unset($arrTemp,$params,$sqlWhere);

		return $arrResult;
	}
	
	public function getSeasonProductInfo($pid){
		$arrResult = null;
		$sql = "select * from ".$this->table."season_product where pid='$pid' ";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
		}

                $season_arr = getSeasonArray();
                $season_ids = $arrResult['season_ids'];
                $typeids = $arrResult['typeids'];
                $ids_arr = explode(',', $season_ids);
                $type_arr = explode(',', $typeids);
                $arrResult['in_season_arr'] = $ids_arr;
                $arrResult['in_type_arr'] = $type_arr;
                foreach ($ids_arr as $k => $sid) {
                        foreach ($season_arr as $sea) {
                                if ($sid == $sea['id']) {
                                        $arrResult['seasonname'] .= ($k == 0 ? '' : ', ') . $sea['title'];
                                }
                        }
                }
		
		return  $arrResult;
	}
}


/*********************
* xajax function
**********************/

function getSeasonProductList($pageno,$objForm,$notOld = true,$field="",$orders='ASC'){
	$objResponse 	= new xajaxResponse();
	$objAdminPro 	= &$GLOBALS['objAdminPro'];
	$req['list']	= $objAdminPro -> getSeasonProductList($pageno,$objForm['searchparam'],$notOld,$field,$orders);
	$req['nofull'] = true ;
	$objAdminPro -> smarty -> assign('req',	$req);
	$content = $objAdminPro -> smarty -> fetch('admin_product_season.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

function getSeasonProductListSearch($objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminPro 	= &$GLOBALS['objAdminPro'];
	$strParam	= serialize($objForm);
	$req['list']	= $objAdminPro -> getSeasonProductList(1,$strParam);
	$req['nofull'] = true ;
	$objAdminPro -> smarty -> assign('req',	$req);
	$content = $objAdminPro -> smarty -> fetch('admin_product_season.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("searchparam",'value',$strParam);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}

function getProductCategory($fatherID,$field='',$order='asc'){
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPro 	= &$GLOBALS['objAdminPro'];
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$req['categoryList']=	$objAdminPro -> getProductCategory($fatherID,true,$field,$order);
	$req['nofull']	=	true;
	$smarty -> assign('req',	$req);
	$content	.=	$smarty -> fetch('admin_product_category.tpl');

	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("fid",'value',$fatherID);

	return $objResponse;
}

function categoryAdd($objForm){
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPro 	= &$GLOBALS['objAdminPro'];
	$messages		= '';
	$objResponse 	= new xajaxResponse();

	$req['category']= $objAdminPro -> categoryAdd($objForm);
	$req['display']	=	'update';
	$smarty -> assign('req',	$req);
	$content	.=	$smarty -> fetch('admin_product_category.tpl');

	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("ajaxmessage",'innerHTML','');

	return $objResponse;
}

function categoryUpdate($ID){
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPro 	= &$GLOBALS['objAdminPro'];
	$messages		= '';
	$objResponse 	= new xajaxResponse();

	$req['category']= $objAdminPro -> categoryUpdate($ID);
	$req['display']	=	'update';

	$smarty -> assign('req',	$req);
	$content	.=	$smarty -> fetch('admin_product_category.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("ajaxmessage",'innerHTML','');

	return $objResponse;
}

function categoryUpdateOperate($objForm){
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPro 	= &$GLOBALS['objAdminPro'];
	$messages		= '';
	$objResponse 	= new xajaxResponse();

	$messages	=	$objAdminPro -> categoryUpdateOperate($objForm);

	$objResponse -> script("xajax_getProductCategory(".$objForm['fid'].")");

	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);

	return $objResponse;
}

function categoryDelete($ID){
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPro 	= &$GLOBALS['objAdminPro'];
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$messages	=	$objAdminPro -> categoryDelete($ID);

	$objResponse -> script("xajax_getProductCategory(xajax.$('fid').value);");
	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);

	return $objResponse;
}


/**
 * display content of category item by xajax
 *
 * @param int $cgid
 * @param obj $objForm
 * @param string $formName
 * @return ajaxResponse
 */
function displayCategoryItem($cgid,$objForm,$formName){
	$objCommon = new common();
	$objResponse = new xajaxResponse();

	if (!empty($cgid)) {
		$dbcon = &$GLOBALS['dbcon'];
		$table_prefix = &$GLOBALS['table'];

		$_query = "SELECT * FROM " . $table_prefix . "ad as t1"
		." WHERE t1.cgid ='$cgid' limit 0,1";

		$dbcon -> execute_query($_query);
		$arrTemp=	$dbcon->fetch_records(true);
		$temp = $arrTemp[0];

		$objResponse -> assign('ad_left','value',$temp['ad_left']);
		$objResponse -> assign('ad_right','value',$temp['ad_right']);
		$objResponse -> assign('ad_bottom','value',$temp['ad_bottom']);
		$objResponse -> script("autoLoadEdit('ad_left','ad_right','ad_bottom');");
		$objResponse -> assign('cgid','value',"$cgid");

		$objResponse->script("document.$formName.state[".$temp['state']."].checked='true'");

		$objResponse -> clear('submitButton','disabled');

		$_query = "SELECT name FROM ".$table_prefix."product_category as t2 WHERE id = '$cgid'";
		$dbcon -> execute_query($_query);
		$arrTemp=	$dbcon->fetch_records(true);

		$objResponse -> assign('ajaxmessage','innerHTML',$arrTemp[0]['name']);
	}

	return $objResponse;
}

/**
 * save content of ad by xajax
 *
 * @param object $objForm
 * @return ajaxResponse
 */
function saveCategoryAds($objForm){

	$dbcon = &$GLOBALS['dbcon'];
	$table_prefix = &$GLOBALS['table'];
	$lang = &$GLOBALS['_LANG'];
	$messages	=	'';
	$objCommon = new common();


	$objResponse = new xajaxResponse();
	$objResponse -> assign('submitButton','disabled',"disabled");
	//$objResponse -> append('ajaxmessage','innerHTML',$lang['pub']['loadinfo']);

	if ($objForm['cgid'] ) {
		$_query = "SELECT name FROM ".$table_prefix."product_category as t2 WHERE id = '".$objForm['cgid']."'";
		$dbcon  -> execute_query($_query);
		$arrTemp =	$dbcon->fetch_records(true);
		$messageTitle = "(" . $arrTemp[0]['name'] . ")";

		$_query = "select id from ".$table_prefix."ad where cgid='$objForm[cgid]'";
		$dbcon -> execute_query($_query);
		$arrTemp=	$dbcon->fetch_records(true);
		if ($arrTemp[0]['id'] > 0) {
			$_query = "update ".$table_prefix."ad set ".
			"ad_left='". $objCommon->__StrReplace($objForm['ad_left'])  ."'," .
			"ad_right='". $objCommon->__StrReplace($objForm['ad_right'])  ."'," .
			"ad_bottom='". $objCommon->__StrReplace($objForm['ad_bottom']) ."'," .
			"state='". $objForm['state']  ."'" .
			" where cgid='".$objForm[cgid]."'";

			if($dbcon -> execute_query($_query)){
				$messages = $objCommon->replaceLangVar($lang['pub_clew']['successful'],array($messageTitle,$lang['operation']['update']));
			}else{
				$messages = $objCommon->replaceLangVar($lang['pub_clew']['faild'],array($messageTitle,$lang['operation']['update']));
			}
		}else{
			$selects = "(cgid,ad_left,ad_right,ad_bottom,state,datec)";
			$values  = "('".$objForm[cgid]."','".
			$objCommon->__StrReplace($objForm['ad_left'])  ."','" .
			$objCommon->__StrReplace($objForm['ad_right'])  ."','" .
			$objCommon->__StrReplace($objForm['ad_bottom'])  ."','" .
			$objForm['state'] . "','". time().
			"')";
			$_query = "insert into " . $table_prefix . "ad $selects values $values";

			if($dbcon -> execute_query($_query)){
				$messages = $objCommon->replaceLangVar($lang['pub_clew']['successful'],array($messageTitle,$lang['operation']['add']));
			}else{
				$messages = $objCommon->replaceLangVar($lang['pub_clew']['faild'],array($messageTitle,$lang['operation']['add']));
			}
		}

	}else{
		$messages = $objCommon->replaceLangVar($lang['pub_clew']['faild'],array($messageTitle,$lang['operation']['save']));
	}

	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> alert($messages);
	$objResponse -> clear('submitButton','disabled');
	return $objResponse;
}


function saveCategoryArticle($objForm){
	$dbcon = &$GLOBALS['dbcon'];
	$table_prefix = &$GLOBALS['table'];
	$lang = &$GLOBALS['_LANG'];
	$messages	=	'';
	$objCommon = new common();

	$objResponse = new xajaxResponse();
	$objResponse -> assign('submitButton','disabled',"disabled");

	if ($objForm['id']) {
		$_query = "update ".$table_prefix."article set ".
		"cgid='". $objForm['cgid']  ."'," .
		"title='". $objCommon->__StrReplace($objForm['title'])  ."'," .
		"context='". $objCommon->__StrReplace($objForm['context']) ."'," .
		"content='". $objCommon->__StrReplace($objForm['content']) ."'," .
		"state='". $objForm['state']  ."'" .
		" where id='".$objForm[id]."'";

		if($dbcon -> execute_query($_query)){
			$messages = $objCommon->replaceLangVar($lang['pub_clew']['successful'],array('Article',$lang['operation']['update']));
		}else{
			$messages = $objCommon->replaceLangVar($lang['pub_clew']['faild'],array('Article',$lang['operation']['update']));
		}
	}elseif ($objForm['content']){
		$selects = "(cgid,title,context,content,state,datec)";
		$values  = "('".$objForm[cgid]."','".
		$objCommon->__StrReplace($objForm['title'])  ."','" .
		$objCommon->__StrReplace($objForm['context'])  ."','" .
		$objCommon->__StrReplace($objForm['content'])  ."','" .
		$objForm['state'] . "','". time().
		"')";
		$_query = "insert into " . $table_prefix . "article $selects values $values";

		if($dbcon -> execute_query($_query)){
			$messages = $objCommon->replaceLangVar($lang['pub_clew']['successful'],array('Article',$lang['operation']['add']));
		}else{
			$messages = $objCommon->replaceLangVar($lang['pub_clew']['faild'],array('Article',$lang['operation']['add']));
		}
	}else {
		$messages = $objCommon->replaceLangVar($lang['pub_clew']['faild'],array('Article',$lang['operation']['add']));
	}

	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> alert($messages);
	$objResponse -> clear('submitButton','disabled');
	return $objResponse;
}

function deleteCategoryArticle($id,$url){
	$dbcon = &$GLOBALS['dbcon'];
	$table_prefix = &$GLOBALS['table'];
	$lang = &$GLOBALS['_LANG'];
	$messages	=	'';
	$isJump		=	false;
	$objCommon = new common();

	$objResponse = new xajaxResponse();
	if ($id) {
		$_query = "delete from ".$table_prefix."article where id='$id'";
		if($dbcon -> execute_query($_query)){
			$isJump = true;
			$messages = $objCommon->replaceLangVar($lang['pub_clew']['successful'],array('',$lang['operation']['delete']));
		}else{
			$messages = $objCommon->replaceLangVar($lang['pub_clew']['faild'],array('',$lang['operation']['delete']));
		}
	}

	$objResponse -> alert($messages);
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	unset($objCommon);
	if ($isJump) {
		$objResponse -> script("javascript:location.href='$url';");
	}
	return $objResponse;
}



function saveFeaturedCategories($objForm){
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPro 	= &$GLOBALS['objAdminPro'];
	$messages		= '';
	$objResponse = new xajaxResponse();
	$messages	=	$objAdminPro -> saveFeaturedCategories($objForm);
	$objResponse -> alert($messages);
	$objResponse -> assign("ajaxmessage",'innerHTML',$messages);
	return $objResponse;
}


function getfeathProCat($fatherID,$field='',$order='asc'){
	$smarty			= &$GLOBALS['smarty'];
	$objAdminPro 	=  new adminProduct();
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	
	$req['categoryList'] =	$objAdminPro -> getProductCategory($fatherID,false,$field,$order);
	$smarty -> assign('req',	$req);
	$content	.=	$smarty -> fetch('admin_featured_category.tpl');

	$objResponse -> assign("feat_list",'innerHTML',$content);
	$objResponse -> assign("fid",'value',$fatherID);

	return $objResponse;
}
?>