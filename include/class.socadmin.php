<?php
/**
 * soc admin public class
 * Tue Feb 12 15:43:59 CST 2008 15:43:59
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.5
 * ------------------------------------------------------------
 * class.socadmin.php
 */

//include_once ("include/class.page.php");

class socadmin extends common  {
	
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
	
	function featuredCategories(){
		$arrResult	=	array();
		
		$query = "select * from ".$this->table."product_category where fid=0 and disabled=0 order by fsort";
		$this->dbcon -> execute_query($query);
		$arrTemp = $this->dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult['categoryList'] = $arrTemp;
		}
		return $arrResult;
	}
	
	function saveFeaturedCategories() {
		
		//set default state
		$_query = 'UPDATE '.$this->table.'product_category SET isfeatured=0, fsort=9';
		$this->dbcon->execute_query($_query);		
		
		//save featured categoires
		$index = 0;
		
		foreach ($_POST['category'] as $k => $v) {
			$fsort = !empty($_POST['fsort'][$k]) ? $_POST['fsort'][$k] : 0;
			if ($index < 6) {
				$_query = 'UPDATE '.$this->table.'product_category SET isfeatured=1, fsort ='.$fsort.' WHERE id='.$k;	
				$this->dbcon->execute_query($_query);
				$index ++;
			}
		}
	}
	
	function storeCategoryOfProduct(){
		$arrResult	=	array();
		// get parent categorys
		$query = "select * from ".$this->table."product_category where fid=0 and disabled=0 order by sort";	
		$this->dbcon -> execute_query($query);
		$arrTemp = $this->dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult['cateParent'] = $arrTemp;
		}
		
		$fid = empty($_REQUEST['fid']) ? '0' : $_REQUEST['fid'];
		//get sub categorys
		
		$query = "select * from ".$this->table."product_category where fid='$fid' and disabled=0 order by sort";
		$this->dbcon -> execute_query($query);
		$arrTemp = $this->dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult['categoryList'] = $arrTemp;
		}
		$arrResult['select']['fid'] = "$fid";
		return $arrResult;
	}

	function storeAddCategoryOfProduct(){
		$arrResult = array();

		if ($this->_notVar){
			$arrResult = $this->getFormInputVar();
		}
		$arrResult['fid'] = $_REQUEST['fid'];
		$arrResult['cp'] = 'insert';

		return $arrResult;
	}

	function storeEditCategoryOfProduct(){
		$arrResult = array();

		$id = $_REQUEST['id'];
		$arrResult['id'] = $id;
		$arrResult['cp'] = 'update';

		$query = "select * from ".$this->table."product_category where id=$id";
		$this->dbcon->execute_query($query);
		$arrTemp = $this->dbcon->fetch_records(true);
		if (is_array($arrTemp)){
			$arrResult['category_name'] = $arrTemp[0]['name'];
			$arrResult['category_order'] = $arrTemp[0]['sort'];
			$arrResult['category_image'] = $arrTemp[0]['image'];
		}

		return $arrResult;
	}

	function storeUpdateCategoryOfProduct(){
		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);

		if (!isset($_REQUEST['category_name']) or $_REQUEST['category_name']== ''){
			return array(false,'Category name is required.');
		}
		
		if ($_REQUEST['cp'] == 'insert'){
			$query = "select count(*) as num from ".$this->table."product_category where name='".$_REQUEST['category_name']."'";
			$this->dbcon->execute_query($query);
			$arrTemp = $this->dbcon->fetch_records(true);

			if ($arrTemp[0]['num'] > 0){
				return array(false,'Category name exists.');
			}
			$arrSetting = array(
				'fid'	=>	"$fid",
				'name'	=>	"$category_name",
				'datec'	=>	time()
			);
			if (isset($_REQUEST['category_order']) and is_numeric($_REQUEST['category_order'])){
				$arrSetting['sort'] = $_REQUEST['category_order'];
			}
			if ($this->dbcon->insert_record($this->table."product_category",$arrSetting)){
				$insert_id = $this->dbcon->lastInsertId();
				if (!isset($_REQUEST['category_order']) or !is_numeric($_REQUEST['category_order'])){
					$arrSetting = array('sort'=>$insert_id);
					$condition = "where id=$insert_id";
					$this->dbcon->update_record($this->table."product_category",$arrSetting,$condition);
				}
				$this->destroyFormInputVar();
				return array(true, 'Add catgory successfully.');
			}else{
				return array(false,'Failed to add category.');
			}
		}elseif($_REQUEST['cp'] == 'update'){
			if (!isset($_REQUEST['id']) or $_REQUEST['id']== ''){
				return array(false,'Category ID is required.');
			}
			$query = "select count(*) as num from ".$this->table."product_category where name='".$_REQUEST['category_name']."' and id!=".$_REQUEST['id'];
			$this->dbcon->execute_query($query);
			$arrTemp = $this->dbcon->fetch_records(true);

			if ($arrTemp[0]['num'] > 0){
				return array(false,'Category name exists.');
			}
			$arrSetting = array(
				'name'	=>	"$category_name",
			);
			if (isset($_REQUEST['category_order']) and is_numeric($_REQUEST['category_order'])){
				$arrSetting['sort'] = $_REQUEST['category_order'];
			}
			$arrSetting['image'] = $_REQUEST['category_image'];
			$condition = "where id=".$_REQUEST['id'];
			if ($this->dbcon->update_record($this->table."product_category",$arrSetting,$condition)){
				$this->destroyFormInputVar();
				return array(true, 'Update catgory successfully.');
			}else{
				return array(false,'Failed to update category.');
			}
		}

		return false;
	}
	
	/**
	 * delete category of product
	 *
	 * @return array
	 */
	function storeDeleteCategoryOfProduct(){
		$id = $_REQUEST['id'];

		if (!$id or !is_numeric($id)){
			return false;
		}

		$query = "delete from ".$this->table."product_category where id=$id";

		return $this->dbcon->execute_query($query);
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
		
		
		$_query = "select count(*) from ". $this -> table ."article where cgid = $cgid ";
		$this -> dbcon -> execute_query($_query);
		$total_count = $this -> dbcon ->fetch_records();
		$total_count = $total_count[0][0];
		$total_page = ceil($total_count/$item_per_page);
		if (!isset($page) or $page == ''){
			$page = 1;
		}
		$start = ($page - 1)*$item_per_page;
		$pn = new page($total_count,$item_per_page,false);
		
		$pn->set_str("","<<previous","next>>",""," ");
		$page_navi = $pn->get_link("socadmin.php?cp=catartset&cgid=$cgid&",$item_per_page);
		
		$titles = "id,cgid,title,context,state";
		$_query = "select $titles from ". $this -> table ."article where cgid= '$cgid' ".$pn->get_limit();;
		
		$this -> dbcon -> execute_query($_query);
		$arrTemp=	$this -> dbcon -> fetch_records(true);
		
		if (is_array($arrTemp)) {
			$arrResult['categoryArticleList']= $arrTemp;
			$arrResult['page_navi']	= "Total: $total_count Records | ".$page_navi . " | Pages: $page / $total_page";
			$arrResult['page']	= $page;
		}
		$arrResult['cgid']	= $cgid;
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
			$arrResult['page']	  = $_REQUEST['p'];
		}
		
		return $arrResult;
	}

}
?>