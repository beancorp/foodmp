<?php
/**
 * Thu Oct 16 17:29:05 GMT+08:00 2008 17:29:05
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * Store function and class
 * ------------------------------------------------------------
 * \include\class.adminstore.php
 */

class adminPromotion extends common {
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
	 * get store list
	 *
	 * @param int $pageno
	 * @param string $strParam
	 * @param boolean $notOld
	 * @return array
	 */
	public function getPromotionList($pageno=1,$strParam='',$field="",$order='asc'){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	18;

		$query ="select count(*) from ".$this->table."promotion order by addtime DESC ";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		$whorder    =   "";
		switch ($field){
			case 'addtime':
				$whorder .= " order by addtime $order "; 
				break;
			case 'attribute':
				$whorder .= " order by attribute $order "; 
				break;
			case 'user':
				$whorder .= " order by user $order "; 
				break;
			case 'usedtime':
				$whorder .= " order by usedtime $order "; 
				break;
			case 'Isused':
				$whorder .= " order by Isused $order "; 
				break;
			case 'promotion':
				$whorder .= " order by promotion $order "; 
				break;
			default:
				break;
		}
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;

		if ($totalNum) {
			$query = "select * from ".$this->table."promotion $whorder limit $start,$perPage";
			$arrResult['query']=$query;
			$this->dbcon->execute_query($query);
			$arrTemp	=	$this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$arrResult['list']	=	& $arrTemp;

				//pager
				$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getPromotionList(\'%d\',\''.$field.'\',\''.$order.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
				$pager = Pager::factory($params);
				$arrResult['links'] 		= $pager->getLinks();
				$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			}
		}
		$arrResult['query'] = $query;
		unset($arrTemp,$params);
		$arrResult['sort']['page']=$pageno;
		$arrResult['sort']['field']=$field;
		$arrResult['sort']['order']=$order;
		
		return $arrResult;
	}

	/**
	 * @title	: 
	 * Tue Mar 17 01:52:40 GMT 2009 01:52:40
	 * @author	: Roy.luo <support@infinitytesting.com.au>
	 * @version	: V1.0
	 * 
	*/
	public function listpromot(){
		$query = "select * from {$this->table}promotion order by addtime DESC";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		return $result;
	}
	public function createpromot($arrsetting){
		return $this->dbcon->insert_record($this->table."promotion",$arrsetting);
	}
	public function editpromot($arrsetting,$wheresql){
		$query = "SELECT count(*) as num FROM {$this->table}promotion $wheresql";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if($result[0]['num']){
			return $this->dbcon->update_record($this->table."promotion",$arrsetting,$wheresql);	
		}else{
			return false;
		}
		
	}
	public function deletepromot($id){
		$query = "delete from ".$this->table."promotion where id={$id}";
		return $this->dbcon->execute_query($query);
	}
	public function checkonlypromot($promotion,$id=0){
		if($id!=0){
			$where = " and id!={$id} ";
		}
		$query = "select count(*) as num from {$this->table}promotion where promotion='$promotion' $where";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if($result[0]['num']>0){
			return false;
		}else{
			return true;
		}
	}
	public function getpromotbyid($id){
		$query = "select * from {$this->table}promotion where id='{$id}'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		return $result[0];
	}
	
	public function getSelectMarket($attribute)
	{
		//$html = '<option value="0" ' . ($attribute == '0' ? 'selected' : '') . '>Buy & Sell</option>'.
		$html = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Market Place:&nbsp;<select name="attribute" class="selectB">'.
				'<option value="1" ' . ($attribute == '1' ? 'selected' : '') . '>Real Estate</option>'.
				'<option value="2" ' . ($attribute == '2' ? 'selected' : '') . '>Automotive</option>'.
				'<option value="3" ' . ($attribute == '3' ? 'selected' : '') . '>Careers</option>'.
				'<option value="5" ' . ($attribute == '5' ? 'selected' : '') . '>Food & Wine</option>'.
				'</select>';
                
		return $html;
	}

}

/*********************
* xajax function
**********************/

/**
 * xajax get promotion list
 *
 * @param int $pageno
 * @return objResponse
 */
function getPromotionList($pageno,$field,$order='asc'){
	$objResponse 	= new xajaxResponse();
	$objAdminPromot 	= &$GLOBALS['objAdminPromotion'];
	$req['promotlist']	= $objAdminPromot -> getPromotionList($pageno,'',$field,$order);
	$objAdminPromot -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$objAdminPromot -> smarty -> assign('req',	$req);
	$content = $objAdminPromot -> smarty -> fetch('admin_promotion_list.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

/**
 * delete record of store
 *
 * @param int $proid
 * @return objResponse
 */
function deletePromotion($proid){
	$objResponse 	= new xajaxResponse();
	$objAdminPromot 	= &$GLOBALS['objAdminPromotion'];
	$messages	=	$objAdminPromot -> deletepromot($proid);
	$objResponse -> script("javascript:xajax_getPromotionList(xajax.$('pageno').value,xajax.$('field').value,xajax.$('order').value);");
	//$objResponse -> alert($messages);

	return $objResponse;
}

function getPromotionById($proid){
	$objResponse = new xajaxResponse();
	$objAdminPromot = &$GLOBALS['objAdminPromotion'];
	$promot = $objAdminPromot -> getpromotbyid($proid);
	$objResponse -> assign('mymarket','innerHTML', $objAdminPromot->getSelectMarket($promot['attribute']));
	$objResponse -> assign('edit_txt_promot','value',$promot['promotion']);
	$objResponse -> assign('edit_promot_id','value',$promot['id']);
	$objResponse -> assign('new_form','style.display','none');
	$objResponse -> assign('edit_form','style.display','');
	return $objResponse;
}

function editPromot($param){
	$objResponse = new xajaxResponse();
	$objAdminPromot = &$GLOBALS['objAdminPromotion'];
	if($param['opt']=='edit'){
		$aryseting = array('promotion'=>addslashes($param['promotion']),'attribute'=>$param['attribute'],'addtime'=>time());
		if($objAdminPromot->checkonlypromot($param['promotion'],$param['id'])){
			$wheresql = " where id={$param['id']}";
			if($objAdminPromot->editpromot($aryseting,$wheresql)){
				$objResponse -> alert("{$GLOBALS['_LANG']['promotion']['alt_edit_suf']}");
				$objResponse -> assign('edit_txt_promot','value','');
				$objResponse -> assign('edit_promot_id','value','');
				$objResponse -> assign('new_form','style.display','');
				$objResponse -> assign('edit_form','style.display','none');
			}else{
				$objResponse -> alert("{$GLOBALS['_LANG']['promotion']['alt_edit_err']}");
				$objResponse -> assign('edit_txt_promot','value','');
				$objResponse -> assign('edit_promot_id','value','');
				$objResponse -> assign('new_form','style.display','');
				$objResponse -> assign('edit_form','style.display','none');
			}
		}else{
			$objResponse -> alert("{$GLOBALS['_LANG']['promotion']['alt_only_promot']}");
		}
	}else{
	$objResponse -> alert("{$param['opt']}");
	}
	$objResponse -> script("javascript:xajax_getPromotionList(xajax.$('pageno').value,xajax.$('field').value,xajax.$('order').value);");
	return $objResponse;
}

function createPromot($param){
	$objResponse = new xajaxResponse();
	$objAdminPromot = &$GLOBALS['objAdminPromotion'];
	if($param['opt']=='add'){
		$aryseting = array('promotion'=>addslashes($param['promotion']),'attribute'=>$param['attribute'],'addtime'=>time(),'user'=>'','usedtime'=>'0','Isused'=>'0');
		if($objAdminPromot->checkonlypromot($param['promotion'])){
			if($objAdminPromot->createpromot($aryseting)){
				$objResponse -> alert("{$GLOBALS['_LANG']['promotion']['alt_add_suf']}");
				$objResponse -> assign('txt_promot','value','');
			}else{
				$objResponse -> alert("{$GLOBALS['_LANG']['promotion']['alt_add_err']}");
			}
		}else{
			$objResponse -> alert("{$GLOBALS['_LANG']['promotion']['alt_only_promot']}");
		}
	}
	$objResponse -> script("javascript:xajax_getPromotionList(xajax.$('pageno').value,xajax.$('field').value,xajax.$('order').value);");
	return $objResponse;
}
?>