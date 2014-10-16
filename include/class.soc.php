<?php
/**
 * soc public class
 * Tue Feb 12 15:43:59 CST 2008 15:43:59
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * class.soc.php
 */

include_once 'class.productcertified.php';
include_once 'class.uploadImages.php';

class socClass extends common  {

	var $dbcon 	= 	null;
	var $table	=	'';
	var $smarty = 	null;
	var $lang	=	null;
	var $blog_length = 0;
	var $paypal_info = array();

	/**
	 * @return void
	 */
	function __construct()
	{
		$this -> dbcon  = &$GLOBALS['dbcon'];
		$this -> time_zone_offset = &$GLOBALS['time_zone_offset'];
		$this -> table	= &$GLOBALS['table'];
		$this -> smarty = &$GLOBALS['smarty'];
		$this -> lang	= &$GLOBALS['_LANG'];
		$this -> blog_length = &$GLOBALS['blog_length'];
		$this->paypal_info = getPaypalInfo();
	}

	/**
    * @return void
    */
	function __destruct(){
		unset($this->dbcon,$this -> table,$this->smarty,$this -> lang,$this->paypal_info);
	}

	/**
	 * get template background color
	 *
	 * @return string color value
	 */
	function getTemplateColor($cid){
		$query	=	"SELECT ColorValue,ColorName FROM ".$this->table."color  WHERE ColorID  ='$cid'";
		$this-> dbcon -> execute_query($query);
		$arrTemp=	$this->dbcon->fetch_records(true);
		if (is_array($arrTemp)) {
			$BGColor = $arrTemp[0]['ColorValue'];
		}
		return $BGColor;
	}

	function _getProductCategoryList($CFID=0,$CID=0)
	{
		$arrResult	=	array();
		if ($CID > 0) {
			$query = "select * from ".$this-> table."product_category where id='$CID' and disabled=0 order by name, sort ";
		}else {
			$query = "select * from ".$this-> table."product_category where fid='$CFID' and disabled=0 order by sort,name ";
		}
		$this-> dbcon -> execute_query($query);
		$arrTemp	=	$this->dbcon->fetch_records(true);
		if (is_array($arrTemp)){
			$arrResult	=	$arrTemp;
		}
		return $arrResult;
	}

	/**
	 * get list of product in store
	 *
	 * @param stirng $StoreID
	 * @param int $colNum
	 * @param int $catID
	 * @param int $limit
	 * @param int $offset
	 * @param boolean    $isEdit
	 * @param string     $sortby
	 * @param boolean    $isRSS
	 * @return array
	 */
	function _getProductList($StoreID,$colNum,$catID=0,$limit=0,$offset=0,$isEdit=false, $sortby='',$isRSS=false, $isCount=false,$plist=false)
	{
		$arrResult	=	array();

		if($StoreID && $catID){
			$query	=	"SELECT product.*,detail.bu_name,detail.bu_urlstring,detail.bu_suburb,auction.cur_price,auction.end_stamp,state.stateName, auction.status,auction.reserve_price,auction.winner_id, state.description as state,"
			." t2.smallPicture, t2.picture FROM ".$this->table."product as product"
			." left join ".$this->table."bu_detail as detail on product.StoreID=detail.StoreID"
			." LEFT JOIN ".$this->table."product_auction as auction ON product.pid=auction.pid"
			." LEFT JOIN ".$this->table."state as state ON detail.bu_state=state.id"
			." left join ".$this->table."image as t2 on product.pid=t2.pid "
			." where product.StoreID in ($StoreID) and product.category='$catID' and product.deleted='' AND IF(t2.StoreID>0, product.StoreID=t2.StoreID,1=1) ";
		}elseif ($StoreID) {
			$query	=	"SELECT product.*,detail.bu_name,detail.bu_urlstring,detail.bu_suburb,auction.cur_price,auction.end_stamp,state.stateName, auction.status,auction.starttime_stamp,auction.reserve_price,auction.winner_id, state.description as state,"
			." t2.smallPicture, t2.picture FROM ".$this->table."product as product"
			." left join ".$this->table."bu_detail as detail on product.StoreID=detail.StoreID"
			." LEFT JOIN ".$this->table."product_auction as auction ON product.pid=auction.pid"
			." LEFT JOIN ".$this->table."state as state ON detail.bu_state=state.id "
			." left join ".$this->table."image as t2 on product.pid=t2.pid "
			." where product.StoreID in ($StoreID) and product.deleted='' AND IF(t2.StoreID>0, product.StoreID=t2.StoreID,1=1) ";
		}elseif ($catID) {
			if ($isCount) {
				$query	=	"SELECT count(*) as count ";
			}else {
				$query	=	"SELECT category.name,product.*,detail.bu_name,detail.bu_urlstring,detail.bu_suburb,auction.cur_price,auction.end_stamp,state.stateName, auction.status,auction.reserve_price,auction.winner_id, "
				."state.description as state,t2.smallPicture, t2.picture ";
			}
			$query	.=	"FROM ".$this->table."product as product "
			."LEFT JOIN ".$this->table."product_category as category on product.category=category.id "
			."left join ".$this->table."bu_detail as detail on product.StoreID=detail.StoreID "
			."LEFT JOIN ".$this->table."login as lg on lg.StoreID=detail.StoreID "
			."LEFT JOIN ".$this->table."product_auction as auction ON product.pid=auction.pid "
			."LEFT JOIN ".$this->table."state as state ON detail.bu_state=state.id "
			."left join ".$this->table."image as t2 ON product.pid=t2.pid "
			."where product.category in ($catID)  and product.deleted='' "
			." and detail.CustomerType = 'seller' "
			//." and detail.renewalDate > ".time()
			." and lg.suspend=0 AND IF(t2.StoreID>0, product.StoreID=t2.StoreID,1=1) ";
		}
		$timelefts  = isset($_REQUEST['timelefts'])?$_REQUEST['timelefts']:"0";
		$buytypeState = isset($_REQUEST['buytypeState'])&&$_REQUEST['buytypeState']?$_REQUEST['buytypeState']:"'yes','no'";
		if(get_magic_quotes_gpc()){	$buytypeState = stripslashes($buytypeState);}

		$timenow = time();
		$query .= "AND product.is_auction in($buytypeState) \n ";
                if($_GET['step']!=4 and $_GET['signon']!='signon') {
                    $query.="AND IF(product.is_auction='yes',auction.starttime_stamp <=$timenow,1=1) ";
                }
		if(!$plist){
			if($buytypeState=="'yes'"){
				$query .= "AND IF(product.is_auction='yes',".($timelefts>0?"auction.end_stamp-$timenow<=$timelefts and":"")." auction.end_stamp-$timenow>0, 1=1)";
			}else{
				$query .= "AND IF(product.is_auction='yes',auction.end_stamp-$timenow>0,1=1)";
			}
		}
		$query.= " GROUP BY product.pid ";
		if($sortby=="cat_asc"&&@$_REQUEST['sort']){
			switch (@$_REQUEST['sort']){
				case '1':	$sortstr = " order by product.category asc, product.datec DESC ";	break;
				case '2':	$sortstr = " order by product.category asc, product.price ASC,product.datec DESC ";	break;
				case '3':	$sortstr = " order by product.category asc, product.price DESC,product.datec DESC ";	break;
				case '4':	$sortstr = " order by product.category asc, product.item_name ASC,product.datec DESC ";break;
				case '5':	$sortstr = " order by product.category asc, detail.bu_name ASC,product.datec DESC ";break;
				default:	$sortstr = " order by product.category asc, product.datec DESC ";	break;
			}
			$query .= $sortstr;
		}else{
			switch ($sortby) {
				case 'date_asc':
					$query .= "order by datec asc ";
					break;
				case 'date_desc':
					$query .= "order by datec desc ";
					break;
				case 'time_asc':
					$query .= "order by auction.end_stamp asc ";
					break;
				case 'time_desc':
					$query .= "order by auction.end_stamp desc ";
					break;
				case 'price_asc':
					$query .= "order by price asc ";
					break;

				case 'price_desc':
					$query .= "order by price desc ";
					break;

				case 'cat_asc':
					$query .= "order by product.category asc , product.datec desc ";
					break;

				default:
					$query .= "order by item_name asc, datem desc ";
			}
		}


		if ($limit > 0){
			$query .=	" limit $offset,$limit";
		}

		$this-> dbcon -> execute_query($query);
		$arrTemp =	$this->dbcon->fetch_records(true);
		if ($isCount) {
			$arrResult['count'] = count($arrTemp);
		}else{
			if (is_array($arrTemp)) {
				$i=0; $j=0;

				foreach ($arrTemp as $temp){

					$urlParam = $temp['StoreID']."&amp;proid=".$temp['pid'];
					$temp['urlParam']		=	$urlParam;
					if ($isRSS) {
						$temp['item_name'] = (($this->clearHTMLChar($temp['item_name'],0)));
						$temp['description'] = (($this->clearHTMLChar($temp['description'],0)));
						$temp['datec']  = date('D d M Y H:i:s T', $temp['datec']);
					}else{
						$temp['description'] = strip_tags(interceptChar($temp['description'],110,true,$temp['descMore']));
						$temp['datec']  = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)), $temp['datec']);
					}
					if ($temp['is_auction'] == 'yes'){
						$temp['endDate'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)), $temp['end_stamp']);
					}

					$arrResult['product'][$i]	=	$temp;
					$arrResult['product'][$i]['dateadd'] 	= $temp['datec'];
					$arrResult['product'][$i]['url_bu_name'] = clean_url_name($temp['bu_urlstring']);
					if (!$colNum || ($colNum && $i % $colNum == 0)) {
						$arrResult['product'][$i]['trbegin']	=	"<tr>";
						$j ++ ;
					}
					if ($j % 2 == 0) {
						$arrResult['product'][$i]['bgcHas']	=	true;
					}
					$arrResult['product'][$i]['image_name'] = $this -> imageDisplayWH($temp['picture'],4,79,79);
					$arrResult['product'][$i]['image_name']['padding'] = 79 - $arrResult['product'][$i]['image_name']['width'];
					$objUI = new uploadImages();
					$arrResult['product'][$i]['simage'] = $objUI -> getDefaultImage($temp['smallPicture'], true, 0, 0, 4);
					$arrResult['product'][$i]['bimage'] = $objUI -> getDefaultImage($temp['picture'],false,0,0,9);
					$arrResult['product'][$i]['limage'] = $objUI -> getDefaultImage($temp['picture'],false,0,0,15);
					unset($objUI);
					$arrResult['product'][$i]['price'] = number_format($arrResult['product'][$i]['price'],'2','.',',');
					$arrResult['product'][$i]['true_end_stamp'] = $arrResult['product'][$i]['end_stamp'];
					$arrResult['product'][$i]['end_stamp'] = $arrResult['product'][$i]['end_stamp']-time()>0?$arrResult['product'][$i]['end_stamp']-time():0;;
					$i++;

					if (!$colNum || $colNum && $i % $colNum == 0 && $i <(count($arrTemp))) {
						$arrResult['product'][$i-1]['trend']	=	"</tr>";
					}
				}

				if ($i>0) {
					$arrResult['productHas']=	true;
				}
			}

		}
		return $arrResult;
	}

	/**
	 * get images from gallery of database
	 *
	 * @param  int  $columnNum
	 * @return array
	 */
	function _getImagesGallery($columnNum=1)
	{
		$arrResult	=	array();

		$query	=	"SELECT  DISTINCT imgCategory  FROM ".$this->table."img_gallery ";
		$this-> dbcon -> execute_query($query);
		$arrTemp =	$this->dbcon->fetch_records(true);

		if (is_array($arrTemp)) {
			$arrResult['selectHas']	=	true;
			$i = 0;
			foreach ($arrTemp as $temp){

				$arrResult['imgCategory'][$i]['name']		=	ucfirst($temp['imgCategory']);

				$query	=	"SELECT * FROM ".$this-> table."img_gallery WHERE imgCategory='".$temp['imgCategory']."'  order by imgCategory asc ";
				$this-> dbcon -> execute_query($query);
				$arrTempSub = $this->dbcon->fetch_records(true);
				if (is_array($arrTempSub)) {
					$arrResult['imgCategory'][$i]['selectList']	= $arrTempSub;
					if ($columnNum>0) {
						for ($j=0; $j < count($arrTempSub)-1 ; $j = $j+$columnNum)
						{
							$arrResult['imgCategory'][$i]['selectList'][$j]['trbegin']				=	'<tr>';
							if ($j+$columnNum-1 <= count($arrTempSub)-1) {
								$arrResult['imgCategory'][$i]['selectList'][$j+$columnNum-1]['trend']	=	'</tr>';
							}else{
								$arrResult['imgCategory'][$i]['selectList'][count($arrTempSub)-1]['trend']	=	'</tr>';
							}
						}
					}
				}
				$i ++;
			}
		}
		//print_r($arrResult['imgCategory'][0]['selectList']);
		return $arrResult;
	}

	/**
	 * get item titles,it use top of subpage.
	 *
	 * @param string $itemName
	 * @param string $contextType
	 * @param boolean $hasSOC
	 * @return string
	 */
	function getItemTitle($context='',$contextType = 'img', $hasSOC=false)
	{
		$strResult = '';
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!empty($StoreID))
		$this->smarty-> assign('StoreID', $StoreID);
		if ($contextType == 'img') {
			$this->smarty-> assign('itemImage','<img src="'.$context.'" align="absmiddle">');
		}else{
			$this->smarty-> assign('itemImage',$context);
		}
		$this->smarty-> assign('hasSOC',$hasSOC);
		if ($_REQUEST['cp']=='blog' or $_REQUEST['cp']=='blogpage'){
			$this->smarty-> assign('hasRss','blog');
		}elseif($_REQUEST['cp']=='disprolist'){
			$this->smarty-> assign('hasRss','items');
		}

		$strResult = $this->smarty-> fetch('itemtitle.tpl');

		return $strResult;
	}


	// *================================================================
	// * This funciton display webside of store at below.
	// * begin====
	// *================================================================

	/**
	 * display webside of store
	 *
	 * @param boolean  $onlyInfo
	 * @return array
	 */
	function displayStoreWebside($onlyInfo=false, $isSellerHome=false, $StoreID='')
	{
		global $templateSet;
		$arrResult	=	null;
		$StoreID = $StoreID ? $StoreID : ($_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:''));
		$pro_category = $_REQUEST['category'] ? $_REQUEST['category'] : '';

		// count the visitors
		if (isset($_REQUEST['StoreID'])) {
			setCounter($StoreID,0);
		}
		$query	= "select * from " . $this->table. "template_details where StoreID='$StoreID'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$tpl_type	=	$arrTemp[0]['tpl_type'];
			$aveRating	=	$this->getAveRating($StoreID);
			$arrResult['aveRating'] = $aveRating;
			$arrResult['template']	=	$arrTemp[0];
			$arrResult['template']['bgcolor'] = $this->getTemplateColor($arrResult['template']['TemplateBGColor']);
			$arrResult['template']['emailalert_image'] = $arrResult['template']['emailalert_image'];

			if ($tpl_type <= 1) {
				$arrResult['template']['LogoImg'] = $this->imageDisplayWH($arrResult['template']['LogoImg'],1,243,76);
				if ($arrResult['template']['LogoImg']['name'] == "images/soc_logo_final.jpg"){
					$arrResult['template']['LogoImg']['name'] = '';
				}
				if ($arrResult['template']['TemplateName']=='tmp-n-b'){
					$arrResult['template']['MainImg'] = $this -> imageDisplayWH($arrResult['template']['MainImg'],2,243,210);
				}else{
					$arrResult['template']['MainImg'] = $this -> imageDisplayWH($arrResult['template']['MainImg'],3,497,195);
				}
				$timenow = time();
				$query = "select count(*) AS itemNumbers from ".$this->table."product p ".
						 " LEFT JOIN {$this->table}product_auction au ON au.pid=p.pid ".
						 " where p.StoreID='$StoreID' and p.deleted='' AND IF(p.is_auction='yes',au.end_stamp-$timenow>0 AND au.starttime_stamp <=$timenow ,1=1)";
				$this -> dbcon -> execute_query($query);
				$arrTemp = $this -> dbcon -> fetch_records();
				if (is_array($arrTemp) && $arrTemp[0][0]==1) {
					$arrResult['template']['TemplateName'] = 'tmp-n-e';
				}
				$arrResult['itemNumbers'] = $arrTemp[0]['itemNumbers'];
			}

			//get store infomation
			$arrResult['info']		= 	$this -> _displayStoreInfo($arrResult['template'], $StoreID);
			$arrResult['info']['payments']		=	$this -> changeArrayValue($arrResult['info']['payments']);
			$arrResult['info']['bu_delivery']	=	$this -> changeArrayValue($arrResult['info']['bu_delivery']);
			$arrResult['info']['website_name'] 	= 	clean_url_name($arrResult['info']['bu_urlstring']);

			$arrResult['template']['subAttrib']	=	$arrResult['info']['subAttrib'];

			//count product of new category.
			if($tpl_type > 1){
				$objUI	=	new uploadImages();
				$arrResult['images']	=	$objUI -> getDisplayImage('',$StoreID,0,-1,-1,$tpl_type);
				unset($objUI);

				if ($tpl_type == 2) {
					$query = "select count(*) AS itemNumbers from ".$this->table."product_realestate where StoreID='$StoreID' and deleted='0' and enabled=1 and (pay_status=2 or (pay_status=1 and renewal_date >= '".time()."'))";
				}elseif ($tpl_type == 3) {
					$query = "select count(*) AS itemNumbers from ".$this->table."product_automotive where StoreID='$StoreID' and deleted='0' and enabled=1 and (pay_status=2 or (pay_status=1 and renewal_date >= '".time()."'))";
				}elseif ($arrResult['info']['subAttrib'] == 1) {
					$current_date = date("Y-m-d");
					$query = "select count(*) AS itemNumbers from ".$this->table."product_job where StoreID='$StoreID' and category='1' and deleted='0' and enabled=1 and ((datePosted <= '$current_date' or datePosted='0000-00-00') and (closingDate >= '$current_date' or closingDate='0000-00-00')) and (pay_status=2 or (pay_status=1 and renewal_date >= '".time()."'))";
				}else{
					$current_date = date("Y-m-d");
					$query = "select count(*) AS itemNumbers from ".$this->table."product_job where StoreID='$StoreID' and category='2' and deleted='0' and enabled=1 and ((datePosted <= '$current_date' or datePosted='0000-00-00') and (closingDate >= '$current_date' or closingDate='0000-00-00'))";

					if ($arrResult['info']['attribute'] == 3 && $arrResult['info']['subAttrib'] != 3) {
						$query .= " AND (pay_status=2 or (pay_status=1 and renewal_date >= '".time()."'))";
					}
					if($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2)) {
						$query .= " AND IF(category=2, ispub in(0,1) , 1=1) ";
					}else{
						if($_SESSION['attribute']==3 && $_SESSION['subAttrib'] ==3 && $_SESSION['ShopID']==$_SESSION['StoreID']){
							$query .= " AND IF(category=2, ispub in(0,1) , 1=1) ";
						}else{
							$query .= " AND IF(category=2, ispub in(1), 1=1) ";
						}
					}
				}
				$this -> dbcon -> execute_query($query);
				$arrTemp = $this -> dbcon -> fetch_records();
				$arrResult['itemNumbers'] = $arrTemp[0]['itemNumbers'];
			}

			if ($arrResult['info']['attribute']==3 && $arrResult['info']['subAttrib'] == 3) {
				$arrResult['template']['TemplateName'] = 'job-c';
				$arrResult['itemNumbers'] = $arrResult['itemNumbers']>0?1:0;
			}

			//get store style
			$arrResult['style']		= 	$this -> _displayStoreStype($arrResult['template'], $StoreID);

			if (!$onlyInfo) {
				//get store others item
				$arrResult['items']		=	$this -> _displayStoreItems($arrResult['template'], $StoreID);
				if(count($arrResult['itmes'])>0){
					$urlParam = "$StoreID&cp=disprodes&proid=".$arrResult['items']['product'][0][0]['pid'];
					$arrResult['items']['product'][0][0]['description']	=	subStoreDetail($arrResult['items']['product'][0][0]['description'],$urlParam,170,80,'soc.php?');
				}

				//FoodWine solution Add By Kevin 2011-8-5
		        if ($arrResult['info']['attribute'] == 5) {
					include_once ('class.FoodWine.php');
					$isfeatured = $isSellerHome;
		        	$store_type = getFoodWineType($arrResult['info']['subAttrib']);
		        	$foodWine = new FoodWine();

					if ($arrResult['template']['TemplateName'] == '') {
						$arrResult['template']['TemplateName'] = $store_type == 'wine' ? 'foodwine-d' : 'foodwine-a';
					}

		        	if ($store_type == 'food') {
		        		$stock = $foodWine->getProductsList($arrResult['info']['StoreID'], $store_type, 'stock', $pro_category, $isfeatured, $arrResult['template']['TemplateName']);
		        		$specials = $foodWine->getProductsList($arrResult['info']['StoreID'], $store_type, 'special', $pro_category, $isfeatured, $arrResult['template']['TemplateName']);
		        		$arrResult['items']['stock_items'] = $stock['items'];
		        		$arrResult['items']['specials'] = $specials['items'];
		        		$arrResult['items']['stock_items_linkStr'] = $stock['linkStr'];
		        		$arrResult['items']['specials_linkStr'] = $specials['linkStr'];
		        		$arrResult['stock_item_num'] = $stock['items'] ? $stock['total'] : 0;
		        		$arrResult['special_num'] = $specials['items'] ? $specials['total'] : 0;
		        	} else {
		        		$arrResult['items'] = $foodWine->getProductByCategory($arrResult['info']['StoreID'], $store_type, 'stock', $isfeatured, $arrResult['template']['TemplateName']);
		        		$arrResult['specials'] = $foodWine->getProductsList($arrResult['info']['StoreID'], $store_type, '', $pro_category, $isfeatured, $arrResult['template']['TemplateName'], '', 1);
		        	}
					
					

		        	$arrResult['info']['foodwine_type'] = $store_type;
					$arrResult['info']['menu_type'] = $menu_type;
					$arrResult['announcement'] = $foodWine->getAnnouncementInfo($arrResult['info']['StoreID']);
		        }
			}
			
			$menu_type = in_array($arrResult['info']['subAttrib'], array(8, 9, 10));
			$arrResult['info']['menu_type'] = $menu_type;

			//set big images
			if ($templateSet[$arrResult['template']['TemplateName']]['isSingerPro']) {
				$arrResult['template']['MainImg']	=	$this -> imageDisplayWH($arrResult['items']['product'][0][0]['image_name']['name'],2,243,200);
			}
		} else {
			$arrResult['info'] = $this -> _displayStoreInfo($arrResult['template'], $StoreID);
		}

		$arrResult['info']['category'] = $pro_category;

		$arrResult['is_customer'] = ($_SESSION['level']==2 or $_SESSION['level']==1)?true:false;

		return $arrResult;
	}

	/**
	 * display singer product of store
	 *
	 * @return array
	 */
	function displayStoreProduct($preview=false){
		$arrResult = null;
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$proID = $_REQUEST['proid'] ? $_REQUEST['proid'] : '0' ;

		$query	=	"select * from " . $this->table. "template_details where StoreID='$StoreID'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult['template']	=	$arrTemp[0];
			$arrResult['template']['LogoImg'] = $this->imageDisplayWH($arrResult['template']['LogoImg'],1,243,76);
			if ($arrResult['template']['LogoImg']['name'] == "images/soc_logo_final.jpg"){
				$arrResult['template']['LogoImg']['name'] = '';
			}
			//$arrResult['template']['TemplateName']	=	'product-display';
			//get store infomation
			$arrResult['info'] = $this -> _displayStoreInfo($arrResult['template'], $StoreID);
			$arrResult['info']['payments']		=	$this -> changeArrayValue($arrResult['info']['payments']);
			$arrResult['info']['bu_delivery']	=	$this -> changeArrayValue($arrResult['info']['bu_delivery']);

			$arrResult['template']['subAttrib']	=	$arrResult['info']['subAttrib'];

			//get store style
			$arrResult['style'] = $this -> _displayStoreStype($arrResult['template'], $StoreID);
			//get store others item
			if ($arrResult['template']['tpl_type'] > 1) {
				$arrResult['items']["product"] = $this->__displayStoreItemsProductByOther($arrResult['template'],$StoreID, $proID,false,1,false,$preview);
			}else{
				$arrResult['items']["product"] = $this->__displayStoreItemsProduct($arrResult['template'],$StoreID, $proID);
			}
		}
		$arrResult['is_customer'] = ($_SESSION['level']==2 or $_SESSION['level']==1)?true:false;
		$arrResult['info']['foodwine_type'] = getFoodWineType($arrResult['info']['subAttrib']);

		return $arrResult;
	}


	/**
	 * display product more list of store
	 *
	 * @return array
	 */
	function displayStoreProductMore()
	{
		$arrResult 	= 	array();
		$StoreID	=	$_REQUEST['StoreID'];

		if ($StoreID) {
			//get website color info (added by jessee 080812)
			$query	=	"select * from " . $this->table. "template_details where StoreID='$StoreID'";
			$this -> dbcon -> execute_query($query);
			$arrTemp = $this -> dbcon -> fetch_records(true);
			if (is_array($arrTemp)) {
				$arrResult['template']	=	$arrTemp[0];
				$arrResult['bgcolor'] = $this->getTemplateColor($arrTemp[0]['TemplateBGColor']);
				$arrResult['tpl_type']	=	$arrTemp[0]['tpl_type'];

				//get store style
				$arrResult['style']		= 	$this -> _displayStoreStype($arrResult['template'], $StoreID);
			}

			//get store infomation
			$arrResult['info']		= 	$this -> _displayStoreInfo($arrResult['template'], $StoreID);

			$arrResult['template']['subAttrib']	=	$arrResult['info']['subAttrib'];


			$strcategory = "";
			if(isset($_REQUEST['STRORE_category'])&&$_REQUEST['STRORE_category']!=""){
				$strcategory = " and t2.fid = {$_REQUEST['STRORE_category']} ";
			}
			if(isset($_REQUEST['sub_category'])&&$_REQUEST['sub_category']!=""){
				$strcategory = " and t2.id = {$_REQUEST['sub_category']} ";
			}
			if(isset($_REQUEST['custom_subcat'])&&$_REQUEST['custom_subcat']!=""){
				$strcategory = " and t1.custom_subcat = '{$_REQUEST['custom_subcat']}' ";
			}

			if ($arrResult['tpl_type'] <= 1) {

				$query	=	"select DISTINCT t1.category,t2.fid,t3.`name` as parentName from `" . $this->table. "product` as t1 ".
				" left join `" . $this->table. "product_category` as t2 on t1.category = t2.`id` ".
				" left join `" . $this->table. "product_category` as t3 on t2.fid = t3.`id` ".
				" where t1.StoreID='$StoreID' $strcategory and t1.deleted='' and t2.fid>0 ".
				" group by t2.`fid` order by t3.name, t2.`sort` ";
				$this -> dbcon -> execute_query($query);
				$arrTemp = $this -> dbcon -> fetch_records(true);

				if (is_array($arrTemp)) {
					//get store others item
					$arrProduct	=	$this -> __displayStoreItemsProduct($arrResult['template'],$StoreID, 0, false, false,1);
					if(isset($arrProduct['linkStr'])){
						$tmplinkStr = $arrProduct['linkStr'];
					}
					if (is_array($arrProduct)) {
						$i=0;
						foreach ($arrTemp as $temp){

							$j = 0;
							foreach ($arrProduct as $temp1){
								if(isset($_REQUEST['sub_category'])&&$_REQUEST['sub_category']!=""){
									if ($temp["category"] == $temp1["category"]) {
										$urlParam = "StoreID=$StoreID&cp=disprodes&proid=".$temp1['pid'];
										// comment by jessee 080805 for listing problem
										//$temp1['description']	=	subStoreDetail($temp1['description'],$urlParam,110,80,'soc.php?');
										$temp1['description'] = strip_tags($temp1['description']);
										$desc_length = strlen($temp1['description']);
										$temp1['description'] = interceptChar($temp1['description'],110,true);
										$temp1['description'] = str_replace('&nbsp;', ' ', $temp1['description']);

										if ($desc_length > 110){
											//$temp1['description'].= ' &nbsp; <a href="soc.php?'.$urlParam.'" style="display:inline">more&gt;&gt;</a>';
											$temp1['description'].= ' &nbsp; <a href="/'.$arrResult['info']['bu_urlstring'].'/'.$temp1['url_item_name'].'" style="display:inline">more&gt;&gt;</a>';
										}
										if ($j % 2 == 1) {
											$temp1['bgcHas']	=	true;
										}
										$arrProList[$i]['product'][$j]	=	$temp1;

										$j++;

									}
								}else{
									if ($temp["fid"] == $temp1["fid"]) {
										$urlParam = "StoreID=$StoreID&cp=disprodes&proid=".$temp1['pid'];
										// comment by jessee 080805 for listing problem
										//$temp1['description']	=	subStoreDetail($temp1['description'],$urlParam,110,80,'soc.php?');
										$temp1['description'] = strip_tags($temp1['description']);
										$desc_length = strlen($temp1['description']);
										$temp1['description'] = interceptChar($temp1['description'],110,true);
										$temp1['description'] = str_replace('&nbsp;', ' ', $temp1['description']);
										if ($desc_length > 110){
											//$temp1['description'].= ' &nbsp; <a href="soc.php?'.$urlParam.'" style="display:inline">more&gt;&gt;</a>';
											$temp1['description'].= ' &nbsp; <a href="/'.$arrResult['info']['bu_urlstring'].'/'.$temp1['url_item_name'].'" style="display:inline">more&gt;&gt;</a>';
										}
										if ($j % 2 == 1) {
											$temp1['bgcHas']	=	true;
										}
										$arrProList[$i]['product'][$j]	=	$temp1;

										$j++;

									}
								}
							}
							if($j!=0){
								$arrProList[$i]['name']	=	$temp['parentName'];
							}
							$i ++;
						}
					}
				}
			} else {
				$arrProList	=	$this -> __displayStoreItemsProductByOther($arrResult['template'],$StoreID,0,false,25);
			}
			$arrResult["product"]	=	$arrProList;
			unset($arrProduct);

            $time = time();

			if(isset($_REQUEST['STRORE_category'])&&$_REQUEST['STRORE_category']!=""){
				$subcat_query = "SELECT DISTINCT cat.id,cat.fid,cat.name FROM `" . $this->table. "product` AS `product`
					LEFT JOIN `" . $this->table. "product_category` AS `cat` ON cat.id = product.category
					LEFT JOIN `" . $this->table. "bu_detail` AS `detail` ON detail.StoreID = product.StoreID
                    LEFT JOIN `" . $this->table. "product_auction` as auction on product.pid = auction.pid
					WHERE product.Deleted = ''
                    AND IF(product.is_auction='yes',auction.end_stamp>$time AND auction.starttime_stamp <=$time ,1)
					AND NOT(detail.bu_name IS NULL)
					AND detail.CustomerType = 'seller'
					AND detail.StoreID = $StoreID
					AND cat.fid = {$_REQUEST['STRORE_category']}
					ORDER BY cat.name ASC;";
				$this->dbcon->execute_query($subcat_query);
				$subcatResult = $this->dbcon->fetch_records(true);
			}

			$custom_subcat_query = "SELECT DISTINCT custom_subcat AS name FROM `" . $this->table. "product` AS `product`
				LEFT JOIN `" . $this->table. "product_category` AS `cat` ON cat.id = product.category
				LEFT JOIN `" . $this->table. "product_category` AS `cat2` ON cat.fid = cat2.id
				LEFT JOIN `" . $this->table. "bu_detail` AS `detail` ON detail.StoreID = product.StoreID
				LEFT JOIN ".$this->table."product_auction as auction on product.pid = auction.pid
				WHERE product.Deleted = ''
				AND IF(product.is_auction='yes',auction.end_stamp>$time AND auction.starttime_stamp <=$time ,1)
				AND product.custom_subcat!=''
				AND NOT(detail.bu_name IS NULL)
				AND detail.CustomerType = 'seller'
				AND detail.StoreID = $StoreID
				ORDER BY cat2.name ASC;";
			$this->dbcon->execute_query($custom_subcat_query);
			$customSubcatResult = $this->dbcon->fetch_records(true);

			$category_query = "SELECT DISTINCT cat2.id, cat2.name FROM `" . $this->table. "product` AS `product`
				LEFT JOIN `" . $this->table. "product_category` AS `cat` ON cat.id = product.category
				LEFT JOIN `" . $this->table. "product_category` AS `cat2` ON cat.fid = cat2.id
				LEFT JOIN `" . $this->table. "bu_detail` AS `detail` ON detail.StoreID = product.StoreID
				LEFT JOIN ".$this->table."product_auction as auction on product.pid = auction.pid
				WHERE product.Deleted = ''
				and IF(product.is_auction='yes',auction.end_stamp>$time AND auction.starttime_stamp <=$time ,1)
				AND NOT(detail.bu_name IS NULL)
				AND detail.CustomerType = 'seller'
				AND detail.StoreID = $StoreID
				ORDER BY cat2.name ASC;";
			$this->dbcon->execute_query($category_query);
			$categoriesResult = $this->dbcon->fetch_records(true);

			$categories = array();
			if (!empty($categoriesResult)) {
				foreach ($categoriesResult as $category) {
					if ($category['id'] && $category['name']) {
						$categories[] = array('id'   => $category['id'], 'name' => $category['name']);
					}
				}
			}
			$subcat = array();
			if (!empty($subcatResult)) {
				foreach ($subcatResult as $sub_category) {
					if ($sub_category['id'] && $sub_category['name']) {
						$subcat[] = array('id'   => $sub_category['id'], 'name' => $sub_category['name']);
					}
				}
			}
			$arrResult['categories'] = $categories;
			$arrResult['subcat'] = $subcat;
			$arrResult['custom_subcat'] = $customSubcatResult;
			if(isset($_REQUEST['STRORE_category'])&&$_REQUEST['STRORE_category']!=""){
				$arrResult['select_category'] = $_REQUEST['STRORE_category'];
			}
			if(isset($_REQUEST['sub_category'])&&$_REQUEST['sub_category']!=""){
				$arrResult['select_subcategory'] = $_REQUEST['sub_category'];
			}
			if(isset($_REQUEST['custom_subcat'])&&$_REQUEST['custom_subcat']!=""){
				$arrResult['select_customsubcat'] = $_REQUEST['custom_subcat'];
			}

		}

		$arrResult['linkStr'] = $tmplinkStr;

		return $arrResult;
	}

	/**
	 * display page from cms
	 *
	 * @param int $CID
	 * @return array
	 */
	function displayPageFromCMS($CID)
	{
		global $cmstable;
		$arrResult	=	array();
		$this->dbcon->execute_query("select * from ".$this->table."{$cmstable} where id = $CID") ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
			$arrResult['aboutPage']	=	stripcslashes(html_entity_decode($arrTemp[0]['body']));
		}
		return $arrResult;
	}

	/**
	 * display category list
	 *
	 * @param int $colNum
	 * @return array
	 */
	function displayCategoryListBySearch($colNum=0)
	{
		$arrResult	=	array();
		$itemsCount	= 0;

		$query = "select t1.id,t1.fid,t1.name".
		" from ".$this->table."product_category as t1 ".
		" where t1.fid='0' and t1.disabled=0 order by  t1.name,t1.sort";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);

		if (is_array($arrTemp)) {
			//get search where SQL **********************
			$stateid = $_REQUEST['state'];
			if(isset($_REQUEST['selectDistance']) && $_REQUEST['selectDistance'] !=''){
				$postcode		=	getPostcodeSubburb($_REQUEST["selectSubburb"],$stateid);
				$whereSQL		=	getRadiusSqlString($postcode, $_REQUEST['selectDistance']);
			}

			$query = "SELECT * FROM ".$this->table."state where stateName='$stateid'";
			$this -> dbcon -> execute_query($query);
			$arrTemp1 = $this -> dbcon -> fetch_records(true);
			$whereSQL1 = " AND bu_state = '".$arrTemp1[0]['id']."'";

			$whereSQL2  = "$whereSQL $whereSQL1 AND CustomerType ='seller' ";
			$query = "SELECT bu_detail.StoreID FROM ".$this->table."bu_detail as bu_detail , ".$this->table."template_details as template_details WHERE bu_detail.StoreID =template_details.StoreID $whereSQL2";
			//echo "$query<br><br>";
			$this -> dbcon -> execute_query($query);
			$arrTemp1 = $this -> dbcon -> fetch_records(true);
			$strStoreID = '';
			if (is_array($arrTemp1)) {
				foreach ($arrTemp1 as $temp){
					$strStoreID .= ",'$temp[StoreID]'";
				}
				$strStoreID = $strStoreID ? substr($strStoreID,1) : '';
			}

			//****************************
			$i =0;

			foreach ($arrTemp as $temp)
			{
				$arrTempCate	= $this-> _displayCategoryListSub($temp, $strStoreID);
				if (count($arrTempCate['sublist']) > 0) {
					if ($colNum >= 3) {
						if ($i % 3 == 2) {
							$arrResult['category'][2][]= $arrTempCate;
						}elseif ($i % 3 == 1){
							$arrResult['category'][1][]= $arrTempCate;
						}else{
							$arrResult['category'][0][]= $arrTempCate;
						}
					}elseif ($colNum == 2) {
						if ($i % 2 == 1){
							$arrResult['category'][1][]= $arrTempCate;
						}else{
							$arrResult['category'][0][]= $arrTempCate;
						}
					}else {
						$arrResult['category'][0][]	= $arrTempCate;
					}
					$i ++;
					$itemsCount += $arrTempCate['number'];
				}
			}
		}
		$arrResult['itemsCount']	=	$itemsCount ;
		$arrResult['urlparam']	=	getSearchURI();
		return $arrResult;
	}

	/**
	 * display category list
	 *
	 * @param int $colNum
	 * @param boolean $onlyParent
	 * @return array
	 */
	function displayCategoryList($colNum=0, $onlyParent=false)
	{
		$arrResult	=	array();

		if($onlyParent){
		$query = "select t1.id,t1.fid,t1.name,t1.image ".
				" from ".$this->table."product_category as t1 ".
				" where t1.fid='0' and t1.disabled=0 order by t1.name,t1.sort";
		}else{
			$query = "select t1.id,t1.fid,t1.name,t1.image ".
				" from ".$this->table."product_category as t1 ".
				" where t1.fid='0' and t1.disabled=0 order by t1.sort,t1.name";
		}
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if(!$onlyParent){
			$sublist = $this->getsubcategorylist('',$onlyParent);
		}
		if (is_array($arrTemp)) {
			$i =0;
			foreach ($arrTemp as $temp)
			{
				//$arrTempCate	= $this-> _displayCategoryListSub($temp, '', $onlyParent);
				$arrTempCate	= $temp;
				if(!$onlyParent){
					$arrTempCate['sublist']  = $sublist[$temp['id']];
					$arrResult['category'][] = $arrTempCate;
				}else{
					if ($colNum >= 3) {
						if ($i % 3 == 2) {
							$arrResult['category'][2][]= $arrTempCate;
						}elseif ($i % 3 == 1){
							$arrResult['category'][1][]= $arrTempCate;
						}else{
							$arrResult['category'][0][]= $arrTempCate;
						}
					}elseif ($colNum == 2) {
						if ($i % 2 == 1){
							$arrResult['category'][1][]= $arrTempCate;
						}else{
							$arrResult['category'][0][]= $arrTempCate;
						}
					}else {
						$arrResult['category'][0][]= $arrTempCate;
					}
					$i ++;
				}
			}
		}

		return $arrResult;
	}

	/**
	 * display sublist of category
	 *
	 * @param array $arrTemp
	 * @param boolean $notNull
	 * @param string $onlyParent
	 * @return array
	 */
	function _displayCategoryListSub($arrTemp, $strStoreID='', $onlyParent=false)
	{
		$arrResult = $arrTemp;

		if ($strStoreID) {
			$query ="Select t1.id,t1.fid,t1.name, count(t2.pid) as number ".
			" from ".$this->table."product_category as t1 ".
			" left join ".$this->table."product as t2 on t1.id = t2.category and t2.deleted=''" .
			" where t1.fid='$arrTemp[id]' and t2.StoreID in ($strStoreID) and t1.disabled=0 group by t1.id order by t1.name, t1.sort";
		}else{
			if($onlyParent){
					$query ="Select t1.id,t1.fid,t1.name, count(t2.pid) as number ".
				" from ".$this->table."product_category as t1 ".
				" left join ".$this->table."product as t2 on t1.id = t2.category and t2.deleted=''" .
				" where t1.fid='$arrTemp[id]' and t1.disabled=0 group by t1.id order by t1.name,t1.sort";
			}else{
				$query ="Select t1.id,t1.fid,t1.name, count(t2.pid) as number ".
				" from ".$this->table."product_category as t1 ".
				" left join ".$this->table."product as t2 on t1.id = t2.category and t2.deleted=''" .
				" where t1.fid='$arrTemp[id]' and t1.disabled=0 group by t1.id order by t1.sort,t1.name";
			}
		}
		$this -> dbcon -> execute_query($query);
		$arrSubTemp = $this -> dbcon -> fetch_records(true);

		if (is_array($arrSubTemp)) {
			$count = 0;
			foreach ($arrSubTemp as $tempSub){
				if ($strStoreID) {
					if ($tempSub['number']) {
						$count += $tempSub['number'];
						if (!$onlyParent) {
							$arrResult['sublist'][] = $tempSub;
						}
					}
				}else{
					$count += $tempSub['number'];
					if (!$onlyParent) {
						$arrResult['sublist'][] = $tempSub;
					}
				}
			}
		}
		$arrResult['number'] = $count;

		return $arrResult;
	}

	function getsubcategorylist($strStoreID='',$onlyParent){
		$query ="Select id,fid,name from ".$this->table."product_category where disabled=0 order by sort,name";
		$this -> dbcon -> execute_query($query);
		$arrSubTemp = $this -> dbcon -> fetch_records(true);
		$aryResult = NULL;
		foreach ($arrSubTemp as $pass){
			$aryResult[$pass['fid']][] = $pass;
		}
		return $aryResult;
	}
	/**
	 * display list of product by category
	 *
	 * @param boolean      $isRSS
	 * @return array
	 */
	function displayCategoryProduct($isRSS = false){
		$arrResult = array();
		$id	=	$_REQUEST['id'];
		$catIds	=	'';

		$query 	=	"select * from " .$this->table . "product_category where fid='$id' and disabled=0 order by name";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		$query = "select * from {$this->table}product_category where id='$id'";
		$this -> dbcon -> execute_query($query);
		$result = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$query = "select name,image,id from ".$this->table."product_category where id='$id' order by name";
			$this -> dbcon -> execute_query($query);
			$arrTempCate = $this -> dbcon -> fetch_records(true);
			$arrResult['categoryName'] = $arrTempCate[0]['name'];
			$arrResult['categoryImage'] = (empty($arrTempCate[0]['image']))?'no':'yes';
			$arrResult['categoryId'] = $arrTempCate[0]['id'];

			if (is_array($arrTemp)) {
				foreach ($arrTemp as $arrTemp2){
					$catIds	.=	",'" .$arrTemp2['id']."'";
				}
				$catIds	=	substr($catIds,1);
			}
		}elseif(!is_array($arrTemp)&&$result[0]['fid']==0){
			$query = "select name,image,id from ".$this->table."product_category where id='$id' order by name";
			$this -> dbcon -> execute_query($query);
			$arrTempCate = $this -> dbcon -> fetch_records(true);
			$arrResult['categoryName'] = $arrTempCate[0]['name'];
			$arrResult['categoryImage'] = (empty($arrTempCate[0]['image']))?'no':'yes';
			$arrResult['categoryId'] = $arrTempCate[0]['id'];

			if (is_array($arrTemp)) {
				foreach ($arrTemp as $arrTemp2){
					$catIds	.=	",'" .$arrTemp2['id']."'";
				}
				$catIds	=	substr($catIds,1);
			}
		}else {
			$query = "select t2.name,t2.image,t2.id from ".$this->table."product_category as t1 ".
			" left join ".$this->table."product_category as t2 on t1.fid=t2.id " .
			" where t1.id='$id' order by t2.name";
			$this -> dbcon -> execute_query($query);
			$arrTempCate = $this -> dbcon -> fetch_records(true);
			$arrResult['categoryName'] = $arrTempCate[0]['name'];
			$arrResult['categoryImage'] = (empty($arrTempCate[0]['image']))?'no':'yes';
			$arrResult['categoryId'] = $arrTempCate[0]['id'];

			$catIds	=	"'$id'";
		}
		if (!$isRSS) {
			$perPage	=	25;
			$arrTemp	=	$this->_getProductList(0,0,$catIds,0,0,false,'cat_asc',$isRSS,true);
			$count		=	$arrTemp['count'];
			$pageno		=	empty($_REQUEST['pageno']) ? 1 :$_REQUEST['pageno'];
			($pageno * $perPage > $count) ? $pageno = ceil($count/$perPage) : '';
			$start	= ($pageno-1) * $perPage;

                        //  @INFINITY_BUG [YangBall 2010-06-15 Page Error] <Des>
                        $start=$start>=0 ? $start : 0;
                        //  @INFINITY_END

			$arrTemp	=	$this->_getProductList(0,0,$catIds,$perPage,$start,false,'cat_asc',$isRSS);
		}else {
			$arrTemp	=	$this->_getProductList(0,0,$catIds,0,0,false,'cat_asc',$isRSS);
		}


		if (is_array($arrTemp['product'])) {
			$i	=	0;
			$intCurrent	=	0;
			$j=0;
			foreach ($arrTemp['product'] as $temp){
				if ($intCurrent != $temp['category']) {
					$intCurrent	=	$temp['category'];
					$arrResult['product'][$i]['id']		=	$temp['id'];
					$arrResult['product'][$i]['name']	=	htmlentities(stripcslashes($temp['name']));
					$arrResult['product'][$i]['hasMore']	=	false;
					$i++;
				}
				$arrResult['product'][$i-1]['product'][]		= 	$temp;
			}
			if (!$isRSS) {
				$pager		=	 new pagerClass();
				$arrResult['pager'] =	$pager -> getLink($pageno,$count,$perPage,'pageno');
				unset($pager);
			}
		}
		//$arrResult['pro_nums'] = $count;
		return $arrResult;
	}


	function displayCategoryProFilter($subid){
		$id	=	$_REQUEST['id'];
		$query = "select name,image,id from ".$this->table."product_category where id='$id' order by name";
		$this -> dbcon -> execute_query($query);
		$arrTempCate = $this -> dbcon -> fetch_records(true);
		$arrResult['categoryName'] = $arrTempCate[0]['name'];
		$arrResult['categoryImage'] = (empty($arrTempCate[0]['image']))?'no':'yes';
		$arrResult['categoryId'] = $arrTempCate[0]['id'];

		$catIds = $subid;
		$perPage	=	25;
		$arrTemp	=	$this->_getProductList(0,0,$catIds,0,0,false,'cat_asc',false,true);
		$count		=	$arrTemp['count'];
		$pageno		=	empty($_REQUEST['pageno']) ? 1 :$_REQUEST['pageno'];
		($pageno * $perPage > $count) ? $pageno = ceil($count/$perPage) : '';
		$start	= ($pageno-1) * $perPage;

		$arrTemp	=	$this->_getProductList(0,0,$catIds,$perPage,$start,false,'cat_asc',false);

		if (is_array($arrTemp['product'])) {
			$i	=	0;
			$intCurrent	=	0;
			$j=0;
			foreach ($arrTemp['product'] as $temp){
				if ($intCurrent != $temp['category']) {
					$intCurrent	=	$temp['category'];
					$arrResult['product'][$i]['id']		=	$temp['id'];
					$arrResult['product'][$i]['name']	=	htmlentities(stripcslashes($temp['name']));
					$arrResult['product'][$i]['hasMore']	=	false;
					$i++;
				}
				$arrResult['product'][$i-1]['product'][]		= 	$temp;
			}
			if (!$isRSS) {
				$pager		=	 new pagerClass();
				$arrResult['pager'] =	$pager -> getLink($pageno,$count,$perPage,'pageno');
				unset($pager);
			}
		}
		return $arrResult;
	}

	function getsubCategoryById($id){
		$query = "SELECT * FROM {$this->table}product_category where fid = '$id' and disabled=0 order by name";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		$catIds = "";
		if (is_array($arrTemp)) {
			$catIds = "";
			foreach ($arrTemp as $arrTemp2){
				$catIds	.=	($catIds==""?"":",") .$arrTemp2['id'];
			}
			$query = "SELECT pro.category FROM {$this->table}product as pro LEFT JOIN {$this->table}bu_detail as detail ON pro.StoreID=detail.StoreID LEFT JOIN {$this->table}login as lg ON lg.StoreID=detail.StoreID  WHERE pro.category in($catIds) and pro.deleted='' "
			." and detail.CustomerType = 'seller' and lg.suspend=0 group by pro.category";
			$this -> dbcon -> execute_query($query);
			$arrTemp = $this -> dbcon -> fetch_records(true);
			$catIds = "";
			if (is_array($arrTemp)) {
				foreach ($arrTemp as $arrTemp2){
					$catIds	.=	($catIds==""?"":",") .$arrTemp2['category'];
				}
			}
		}
		if($catIds){
		$query = "SELECT * FROM {$this->table}product_category where id in($catIds) and disabled=0 order by name";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		}else{
			$arrTemp = NULL;
		}
		return $arrTemp;
	}
	/**
	 * get display items of store.
	 *
	 * @param array $template
	 * @param stirng $StoreID
	 * @return array
	 */
	function _displayStoreItems(&$template, $StoreID){
		$arrResult	=	null;
		// comment by jessee 20081231, remove the old buyblitz  code
		/*$query	=	"select `Product`,`Vouchers`,`Specials`,`Menu`,`Cuisine` ".
		" from " . $this->table. "template where TemplateName='".$template['TemplateName']."'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records();
		if (is_array($arrTemp)) {
		foreach ($arrTemp[0] as $key => $val){
		if ($val) {
		if ($key == 'Product') {*/
		//var_dump($template);
		if ($template['tpl_type']<1) {
			$arrResult["product"]		=	$this -> __displayStoreItemsProduct($template, $StoreID, 0, false, true);
		}else {
			$arrResult["product"]		=	$this -> __displayStoreItemsProductByOther($template, $StoreID, 0, false, 0, true);
		}

		/*
		}elseif ($key == 'Vouchers') {
		//....
		}

		}
		}
		}*/
		//var_dump($arrResult);
		return $arrResult;
	}

	/**
	 * get display product of store.
	 *
	 * @param array $template
	 * @param string $StoreID
	 * @param int  $proID
	 * @param boolean $isEdit
	 * @return array
	 */
	function __displayStoreItemsProduct(&$template, $StoreID, $proID=0, $isEdit=false, $hasFeatured=false,$ispage=0)
	{
		global $templateSet;
		$arrResult	=	null;
		$limit 		=	$templateSet[$template['TemplateName']]['product'];
		$colnum		=	1;

		/**
		 * modified by jackyleft join auction table to get auction product information
		 * 080731 Jessee: add state info, left join bu_detail and state
		 * 081231 Jessee: left join images for new image display
		 */
		$strcategory = "";
		if(isset($_REQUEST['STRORE_category'])&&$_REQUEST['STRORE_category']!=""){
			$strcategory = " and t2.fid = {$_REQUEST['STRORE_category']} ";
		}
		if(isset($_REQUEST['sub_category'])&&$_REQUEST['sub_category']!=""){
			$strcategory .= " and t2.id = {$_REQUEST['sub_category']} ";
		}
		if(isset($_REQUEST['custom_subcat'])&&$_REQUEST['custom_subcat']!=""){
			$strcategory = " and t1.custom_subcat = '{$_REQUEST['custom_subcat']}' ";
		}

		$timelefts  = isset($_REQUEST['timelefts'])?$_REQUEST['timelefts']:"0";
		$buytypeState = isset($_REQUEST['buytypeState'])&&$_REQUEST['buytypeState']?$_REQUEST['buytypeState']:"'yes','no'";
		if(get_magic_quotes_gpc()){	$buytypeState = stripslashes($buytypeState);}

		$query ="Select t1.*,t2.name as catName, t3.name as catNameF, t3.id as fid, t4.initial_price, ".
		" t4.end_date, t4.end_time, t4.end_stamp,t4.end_stamp as end_stamp2, t4.cur_price, t4.bid, t4.winner_id, t4.status,t4.reserve_price,t4.cur_price, t6.description as state, t6.stateName,t5.bu_suburb, ".
		" t5.bu_name,t5.bu_urlstring, t7.smallPicture, t7.picture, t4.reserve_price ";

		$wheresql = " from ".$this->table."product as t1 ".
		" left join ".$this->table."product_category as t2 on t1.category=t2.id ".
		" left join ".$this->table."product_category as t3 on t2.fid=t3.id ".
		" left join ".$this->table."product_auction as t4 on t1.pid=t4.pid ".
		" left join ".$this->table."bu_detail as t5 on t1.StoreID=t5.StoreID ".
		" left join ".$this->table."state as t6 on t5.bu_state=t6.id ".
		" left join ".$this->table."image as t7 on t1.StoreID=t7.StoreID and t1.pid=t7.pid and t7.attrib=0 and t7.sort=0 ".
		" where t1.StoreID='$StoreID' and t1.deleted='' $strcategory ";

		if ($proID) {
			$wheresql .=" and t1.pid='$proID' ";
		}else{
			$timenow = time();
			$wheresql .=" AND t1.is_auction in($buytypeState) \n";
			if($buytypeState=="'yes'"){
                                $tmp_time=$timelefts+$timenow;
				$wheresql .=" AND ((t1.is_auction='yes' AND ".($timelefts>0?" t4.end_stamp<=$tmp_time AND ":"")." t4.end_stamp>$timenow AND t4.starttime_stamp <= $timenow ) OR t1.is_auction<>'yes') ";
			}else{
				$wheresql .=" AND ((t1.is_auction='yes' AND t4.end_stamp>$timenow AND t4.starttime_stamp <= $timenow) OR t1.is_auction<>'yes') ";
			}
		}

		if ($hasFeatured) {
			$wheresql .= " order by isfeatured desc, t1.datem desc,t1.item_name ";
		}else{
			if($ispage){
				$order = " t3.name ASC, ";
			}else{
				$order = "";
			}
			$wheresql .= " order by $order t1.datem desc,t1.item_name ";
		}

		if ($limit && $hasFeatured) {
			$wheresql .= " limit 0,$limit";
		}

		if($ispage){
			$cquery = "SELECT COUNT(*) \n".$wheresql;
			$this->dbcon->execute_query($cquery);
			$total = $this->dbcon->fetch_records(true);
			$total = $total[0]['COUNT(*)'];
			$pageSize = 25;
			$clsPage = new Page($total, $pageSize);
			$wheresql .= $clsPage->get_limit();
		}

		$query = $query.$wheresql;
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			foreach ($arrTemp as $key=>$val){
				$_tsql = "select make,makeUser,model,modelUser from ".$this->table."product_automotive where  pid='{$val['pid']}'";
				$this -> dbcon -> execute_query($_tsql);
				$tmpary = $this -> dbcon -> fetch_records(true);
				if($tmpary[0]['make']=='-2'){
					$arrTemp[$key]['makeName']	 = $tmpary[0]['makeUser'];
					if($tmpary[0]['model']=='-2'){
						$arrTemp[$key]['modelName'] = $tmpary[0]['modelUser'];
					}
				}else{
					if($tmpary[0]['model']=='-2'){
						$arrTemp[$key]['modelName'] = $tmpary[0]['modelUser'];
					}
				}
			}

			if ($colnum>1) {
				$i	=	0;
				foreach ($arrTemp as $temp)
				{
					$objUI	=	new uploadImages();
					$temp['images']	=	$objUI -> getDisplayImage('store', $temp['StoreID'], $temp['pid']);
					$temp['simage']	=	$objUI -> getDefaultImage($temp['smallPicture'], true, 0, 0, 4);
					$temp['limage']	=	$objUI -> getDefaultImage($temp['smallPicture'], true, 0, 0, 15);
					$temp['bimage']	=	$objUI -> getDefaultImage($temp['picture'],false,0,0,9);
					$temp['images_count'] = $temp['images']['imagesCount'];
					unset($objUI);

					//add a url bu_name by jessee 080804
					$temp['url_bu_name'] = clean_url_name($temp['bu_urlstring']);
					//set price format by jessee 080325
					$temp['price'] = number_format($temp['price'],2,'.',',');
					$temp['end_stamp'] = $temp['end_stamp']-time()>0?$temp['end_stamp']-time():0;
					//set payments
					if (!empty($temp['payments'])) {
						$temp['payments'] = unserialize($temp['payments']);
						unset($arrTemp2);
						$k=0;
						$len = sizeof($temp['payments']);
						foreach ($temp['payments'] as $temp2){
							$arrTemp2[$temp2][0]	=	true;
							$k++;
							if ($k < $len) {
								$arrTemp2[$temp2][1]	=	true;
							}
							//echo $arrTemp2[$temp2][1];
						}
						$temp['payments'] = $arrTemp2;
					}

					$temp['description'] = strip_tags($temp['description']);
					$temp['description'] = str_replace('&nbsp;', ' ', $temp['description']);

					/*if (!empty($this -> lang['Delivery'][strval($temp['deliveryMethod'])]['text'])){
						$temp['deliveryMethod'] = $this -> lang['Delivery'][strval($temp['deliveryMethod'])]['text'];
					}else{
						$temp['deliveryMethod'] = "";
					}*/

					if ($colnum >=3) {
						if ($i % 3 == 2) {
							$arrResult[2][]=$temp;
						}elseif ($i % 3 == 1){
							$arrResult[1][]=$temp;
						}else{
							$arrResult[0][]=$temp;
						}
					}else{
						$arrResult[]=$temp;
					}
					$i++;
				}
			}else{
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
		}

		if($ispage){
			$arrResult['linkStr']  = $clsPage->get_link('soc.php?cp=disprolist&StoreID='. $_REQUEST['StoreID'] .'&STRORE_category='. $_REQUEST['STRORE_category'].'&sub_category='.$_REQUEST['sub_category']."&timelefts=$timelefts&buytypeState=$buytypeState", $pageSize);
		}
		$arrResult[0]['youtubevideo'] = getobjurl($arrResult[0]['youtubevideo'])?getobjurl($arrResult[0]['youtubevideo']):"";
		require_once('class.producttag.php');
		$protags = new producttag();
		$tmpstr = $protags->get_pro_tags_ByPorID($arrResult[0]['pid'],0);
		if($tmpstr){
			$tmpary = explode(',',$tmpstr);
			$tmpary2 = array();
			foreach ($tmpary as $pass):
				if($pass!=""){
					$tmpary2[] = "<a href=\"/soc.php?cp=searchTag&pro_tags=$pass\">$pass</a>";
				}
			endforeach;
			$tmpstr = implode(', ',$tmpary2);
		}
		$arrResult[0]['pro_tags'] = $tmpstr;
		//var_dump($arrResult);
		return $arrResult;
	}


	/**
	 * Get list of product of auto
	 *
	 * @param array $template
	 * @param string $StoreID
	 * @param int  $proID
	 * @param boolean $isEdit
	 * @return array
	 */
	private function __displayStoreItemsProductByOther(&$template, $StoreID, $proID=0, $isEdit=false, $limit=0, $hasFeatured=false, $preview=false){
		global $templateSet;
		$arrResult	=	null;
		$limit 		=	$limit ? $limit : $templateSet[$template['TemplateName']]['product'];
		$colnum		=	1;
		$strPictureType	=	'';

		if($template['TemplateName']=='job-a'&&$template['subAttrib']==3){$limit=1;$hasFeatured=true;}

		$this -> checkPurviewOfVisit($StoreID, $template,$proID);

		$_where	=	"where t1.StoreID='$StoreID' and t1.deleted=0";

		if (empty($_REQUEST['pre'])) {
			$_where .= " and t1.enabled=1";
		}

		if ($proID) {
			$_title	=	"t1.*, DATE_FORMAT(FROM_UNIXTIME(t1.datec),'".DATAFORMAT_DB."') as datecfm";
		}else{
			$_title	=	"t1.*, DATE_FORMAT(FROM_UNIXTIME(t1.datec),'".DATAFORMAT_DB."') as datecfm";
		}

		if ($template['tpl_type'] == 1){
			$timenow = time();
			$_query	=	"select $_title, au.cur_price,au.end_stamp-$timenow as end_stamp,au.end_stamp as end_stamp2, t2.smallPicture, t2.picture, reserve_price from ".$this->table."product as t1 ";
			$_query .=   " LEFT JOIN {$this->table}product_auction as au ON au.pid =t1.pid ";
			$_where	=	"where t1.StoreID='$StoreID' and t1.deleted!='YES'";
			$_where .=  " AND IF(t1.is_auction='yes',au.end_stamp-$timenow>0 AND au.starttime_stamp <=$timenow ,1=1) ";
			$strPictureType	=	'store';
		}elseif ($template['tpl_type'] == 2) {
			if (!$preview) {
				$_where .= " AND (t1.pay_status=2 OR (t1.pay_status=1 AND t1.renewal_date >= '".time()."'))";
			}

			$_query	=	"select $_title, t2.smallPicture, t2.picture, st2.stateName, st2.description, st3.suburb as suburbName ".
			" from ".$this->table."product_realestate as t1 ".
			" left join ".$this->table."state as st2 on t1.state=st2.id ".
			" left join ".$this->table."suburb as st3 on t1.suburb=st3.suburb_id ";
			$strPictureType	=	'estate';
		}elseif ($template['tpl_type'] == 3){
			if (!$preview) {
				$_where .= " AND (t1.pay_status=2 OR (t1.pay_status=1 AND t1.renewal_date >= '".time()."'))";
			}

			$_query	=	"select $_title, t2.smallPicture, t2.picture, st2.name as carTypeName, st3.name as makeName, st4.name as modelName, st5.stateName, st5.description, st6.suburb as suburbName   from ".
			$this->table."product_automotive as t1 ".
			" left join ".$this->table."product_sort as st2 on t1.carType=st2.id ".
			" left join ".$this->table."product_sort as st3 on t1.make=st3.id ".
			" left join ".$this->table."product_sort as st4 on t1.model=st4.id ".
			" left join ".$this->table."state as st5 on t1.state=st5.id ".
			" left join ".$this->table."suburb as st6 on t1.suburb=st6.suburb_id ";

			$strPictureType	=	'auto';
		}elseif ($template['tpl_type'] == 6){
			$_query	=	"select $_title from ".
			$this->table."product_foodwine as t1 ";

			$strPictureType	=	'foodwine';
		}else{
			$_query	=	"select $_title, st2.name as sectorName, st3.name as subSectorName, st4.stateName, st4.description, st5.suburb as suburbName from ".$this->table."product_job as t1 ".
			" left join ".$this->table."product_sort as st2 on t1.sector=st2.id ".
			" left join ".$this->table."product_sort as st3 on t1.subSector=st3.id ".
			" left join ".$this->table."state as st4 on t1.state=st4.id ".
			" left join ".$this->table."suburb as st5 on t1.suburb=st5.suburb_id ";
			;

			if (empty($_REQUEST['pre'])) {
				$current_date = date("Y-m-d");
				$_where .= " and ((t1.datePosted <= '$current_date' or t1.datePosted='0000-00-00') and ".
				"(t1.closingDate >= '$current_date' or t1.closingDate='0000-00-00'))";
			}
			if (!$preview && $template['subAttrib'] != 3) {
				$_where .= " AND (t1.pay_status=2 OR (t1.pay_status=1 AND t1.renewal_date >= '".time()."'))";
			}
			$template['subAttrib'] == 1  ? $_where .= " and t1.category = 1" : $_where .= " and t1.category = 2" ;

			if($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2)) {
				$_where .= " AND IF(t1.category=2, t1.ispub in(0,1) , 1=1)";
			}else{
				if($_SESSION['attribute']==3 && $_SESSION['subAttrib'] ==3){
					if($_SESSION['ShopID']==$_SESSION['StoreID']){
						$_where .= " AND IF(t1.category=2, t1.ispub in(0,1) , 1=1)";
					}
				}else{
					$_where .= " AND IF(t1.category=2, t1.ispub in(1) , 1=1)";
				}
			}
		}

		if ($proID) {
			if ($template['tpl_type']==1 || $template['tpl_type']==2 || $template['tpl_type']==3){
				$_query	.=	"left join ".$this->table."image as t2 on t1.StoreID=t2.StoreID and t1.pid=t2.pid and t2.attrib=0 and t2.sort=0 ";
			}
			$_query	.=	" $_where and t1.pid='$proID' ";

		}else{
			if (!empty($strPictureType)) {
				$_query	.=	"left join ".$this->table."image as t2 on t1.StoreID=t2.StoreID and t1.pid=t2.pid and t2.attrib=0 and t2.sort=0 $_where";
			}else {
				$_query	.=	" $_where";
			}
			if ($hasFeatured) {
				if ($template['tpl_type']==1 || $template['tpl_type']==6){
					$_query .= " order by t1.isfeatured desc, t1.datec desc,t1.item_name ";
				}else{
					$_query .= " order by t1.featured desc, t1.datec desc,t1.item_name ";
				}
			}else{
				$_query .= " order by t1.datec desc,t1.item_name ";
			}
			if ($limit) {
				$_query .= " limit 0,$limit";
			}
		}

		$this -> dbcon -> execute_query($_query);
		$arrTemp	=	$this -> dbcon -> fetch_records(true);

		if (is_array($arrTemp)) {
			foreach ($arrTemp as $key=>$val){
				$_tsql = "select make,makeUser,model,modelUser from ".$this->table."product_automotive where  pid='{$val['pid']}'";
				$this -> dbcon -> execute_query($_tsql);
				$tmpary = $this -> dbcon -> fetch_records(true);
				if($tmpary[0]['make']=='-2'){
					$arrTemp[$key]['makeName']	 = $tmpary[0]['makeUser'];
					if($tmpary[0]['model']=='-2'){
						$arrTemp[$key]['modelName'] = $tmpary[0]['modelUser'];
					}
				}else{
					if($tmpary[0]['model']=='-2'){
						$arrTemp[$key]['modelName'] = $tmpary[0]['modelUser'];
					}
				}
				$arrTemp[$key]['description'] = strip_tags($arrTemp[$key]['description']);
				$arrTemp[$key]['description'] = $template['tpl_type'] == 6 ? $arrTemp[$key]['description'] : interceptChar($arrTemp[$key]['description'],60,true);
				$arrTemp[$key]['description'] = str_replace('&nbsp;', ' ', $arrTemp[$key]['description']);
			}
			if (empty($strPictureType)) {
				$arrResult	=	$arrTemp;
			}else {
				$objUI	=	new uploadImages();
				if ($proID){
					$arrResult	=	$arrTemp;
					$arrResult[0]['images']	=	$objUI -> getDisplayImage($strPictureType, $StoreID, $proID);

					/*if (!empty($this -> lang['Delivery'][strval($arrResult[0]['deliveryMethod'])]['text'])){
						$arrResult[0]['deliveryMethod'] = $this -> lang['Delivery'][strval($arrResult[0]['deliveryMethod'])]['text'];
					}else{
						$arrResult[0]['deliveryMethod'] = "";
					}*/

				}else {
					foreach ($arrTemp as $temp){
						$temp['simage']	=	$objUI -> getDefaultImage($temp['smallPicture'], true, 0, 0, 4);
						$temp['bimage']	=	$objUI -> getDefaultImage($temp['picture'],false,0,0,9);
						$temp['limage']	=	$objUI -> getDefaultImage($temp['picture'],false,0,0,15);
						if ($template['tpl_type']==1 and $template['TemplateName']=='tmp-n-e'){
							$temp['images'] = $objUI -> getDisplayImage($strPictureType, $StoreID, $temp['pid']);
						}

						//add a url bu_name by jessee 080804
						/*if (!empty($this -> lang['Delivery'][strval($temp['deliveryMethod'])]['text'])){
							$temp['deliveryMethod'] = $this -> lang['Delivery'][strval($temp['deliveryMethod'])]['text'];
						}else{
							$temp['deliveryMethod'] = "";
						}*/

						$arrResult[]	=	$temp;
					}
				}

				//read images in single template product at 20090119 by ping.hu
				if(count($arrTemp) == 1){
					$arrResult[0]['images']	=	$objUI -> getDisplayImage($strPictureType, $StoreID, $arrTemp[0]['pid']);
				}

				unset($objUI);
			}
			$arrResult[0]['youtubevideo'] = getobjurl($arrResult[0]['youtubevideo'])?getobjurl($arrResult[0]['youtubevideo']):"";
			require_once('class.producttag.php');
			$protags = new producttag();
			$tmpstr = $protags->get_pro_tags_ByPorID($arrResult[0]['pid'],0);
			if($tmpstr){
				$tmpary = explode(',',$tmpstr);
				$tmpary2 = array();
				foreach ($tmpary as $pass):
					if($pass!=""){
						$tmpary2[] = "<a href=\"/soc.php?cp=searchTag&pro_tags=$pass\">$pass</a>";
					}
				endforeach;
				$tmpstr = implode(', ',$tmpary2);
			}
			$arrResult[0]['pro_tags'] = $tmpstr;
		}

		return $arrResult;
	}


	/**
	 * get display style of store.
	 *
	 * @param array $template
	 * @return array
	 */
	function _displayStoreStype(&$template)
	{
		$arrResult	=	null;

		$query	=	"select ColorValue from " . $this->table. "color where ColorID='".$template['TemplateBGColor']."'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records();
		if (is_array($arrTemp)) {
			$arrResult['BGColor']		=	$arrTemp[0]['ColorValue'];
		}

		$query	=	"select ColorValue from " . $this->table. "color where ColorID='".$template['TemplateFont']."'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records();
		if (is_array($arrTemp)) {
			$arrResult['FontColor']		=	$arrTemp[0]['ColorValue'];
		}

                /*  @INFINITY_TEMP [Yang Ball 2010-06-15 16:35] <Des>
		$query	=	"select StyleName from " . $this->table. "style where ColorID='".$template['TemplateStyle']."'";

		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records();
		if (is_array($arrTemp)) {
			$arrResult['FontStyle']		=	$arrTemp[0]['StyleName'];
		}
                 */

		return $arrResult;
	}

	/**
	 * get display infomation of store.
	 *
	 * @param array $template
	 * @param string $StoreID
	 * @return array
	 */
	function _displayStoreInfo(&$template, $StoreID)
	{
		global $templateSet;
		$arrResult	=	null;

		$query	=	"select * from " . $this->table. "bu_detail where StoreID='$StoreID'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records();
		if (is_array($arrTemp)) {
			$arrTemp	=	$arrTemp[0];
			$dateformat = str_replace('-','/',str_replace('%','',DATAFORMAT_DB));
			$arrResult['StoreID']		=	$StoreID;
			$arrResult['bu_name']		=	stripslashes($arrTemp['bu_name']);
			$arrResult['url_bu_name']	=	clean_url_name($arrTemp['bu_urlstring']);
			$arrResult['bu_nickname']	=	$arrTemp['bu_nickname'];
			$arrResult['bu_address']	=	$arrTemp['address_hide'] ? "" : $arrTemp['bu_address'];
			if (intval($arrTemp['bu_college']) > 0) {
				$bu_college = $this->getCollegeNameBybizID($arrTemp['bu_college']);
				$arrResult['bu_college']	=	$arrTemp['college_hide'] ? "" : $bu_college;
			} else {
				$arrResult['bu_college']	=	$arrTemp['college_hide'] ? "" : $arrTemp['bu_college'];
			}

			$arrResult['bu_state']		=	$this-> _getStateName($arrTemp['bu_state'],1);
			$arrResult['bu_suburb']		=	$arrTemp['bu_suburb'];
			$arrResult['bu_postcode']	=	change_postcode($arrTemp['bu_postcode']);
			$arrResult['bu_phone']		=	$arrTemp['phone_hide'] || empty($arrTemp['bu_phone']) ? "" : $arrTemp['bu_area'] ." ". $arrTemp['bu_phone'];
			$arrResult['bu_fax']		=	empty($arrTemp['bu_fax']) ? "" : $arrTemp['bu_area'] ." ". $arrTemp['bu_fax'];
			$arrResult['bu_procode']		=	empty($arrTemp['bu_procode']) ? "" :$arrTemp['bu_procode'];
			$arrResult['bu_urlstring']		=	empty($arrTemp['bu_urlstring']) ? "" :$arrTemp['bu_urlstring'];
			$arrResult['bu_website_all']=	$arrTemp['bu_website'];
			$arrResult['bu_website']	=	$this -> snapString($arrTemp['bu_website'],2);
			$arrResult['bu_email']		=	$arrTemp['bu_email'];
			$arrResult['bu_paypal']		=	$arrTemp['bu_paypal'];
			$arrResult['address_hide']	=	!$arrTemp['address_hide'] || empty($arrTemp['bu_address']) ? 0 : $arrTemp['address_hide'];
			$arrResult['college_hide']	=	!$arrTemp['college_hide'] || empty($arrTemp['bu_college'])?0:$arrTemp['college_hide'];
			$arrResult['phone_hide']	=	$arrTemp['phone_hide'];
			$arrResult['description']	=	$arrTemp['bu_desc'];

			$arrResult['bu_delivery']	=	empty($arrTemp['bu_delivery'])?'': unserialize($arrTemp['bu_delivery']);
			$arrResult['bu_delivery_text'] = unserialize($arrTemp['bu_delivery_text']); //$this->getStorePayment($StoreID);
			$arrResult['deliveryMethod']=	$arrTemp['deliveryMethod'];
			$arrResult['postage']		=	$arrTemp['postage'];
			$arrResult['payments']		=	empty($arrTemp['payments']) ? '' : unserialize($arrTemp['payments']);
			$arrResult['contact']		=	$arrTemp['contact'];
			$arrResult['launch_date']	=	$arrTemp['launch_date'] >0?date($dateformat,$arrTemp['launch_date']) : '';

			$arrResult['attribute']		=	$arrTemp['attribute'];
			$arrResult['sellerType']	=	$arrTemp['attribute'] == 1 ? 'estate' : ($arrTemp['attribute'] == 2 ? 'auto': ($arrTemp['attribute'] == 3 ? 'job': ($arrTemp['attribute'] ? 'foodwine' : 'store')));
			$arrResult['sellerTypeName']	=	$arrTemp['attribute'] == 1 ? 'Property' : ($arrTemp['attribute'] == 2 ? 'Auto': ($arrTemp['attribute'] == 3 ? 'Job':''));
			$arrResult['subAttrib']		=	$arrTemp['subAttrib'];
			$arrResult['subAttribName']	=	($arrTemp['attribute'] == 1 || $arrTemp['attribute'] == 2 || $arrTemp['attribute'] == 5) ? $this->lang['seller']['attribute'][$arrTemp['attribute']]['subattrib'][$arrTemp['subAttrib']] : ($arrTemp['attribute']==3 ? 'Advertiser' : '');
			$arrResult['licence']		=	$arrTemp['licence'];
			$arrResult['mobile']		=	$arrTemp['mobile'];
			$arrResult['facebook']		=	$arrTemp['facebook'];
			$arrResult['myspace']		=	$arrTemp['myspace'];
			$arrResult['twitter']		=	$arrTemp['twitter'];
			$arrResult['linkedin']		=	$arrTemp['linkedin'];
			$arrResult['youtubevideo']	=	getobjurl($arrTemp['youtubevideo'])?getobjurl($arrTemp['youtubevideo']):"";
			$arrResult['opening_hours']	=	nl2br($arrTemp['opening_hours']);
			$arrResult['suburb_delivery']	=	$arrTemp['suburb_delivery'];
			$arrResult['sold_status']	=	$arrTemp['sold_status'];
			$arrResult['product_feetype']	=	$arrTemp['product_feetype'];
			$arrResult['product_renewal_date']	=	$arrTemp['product_renewal_date'];
			$arrResult['is_popularize_store']	=	$arrTemp['is_popularize_store'];

			$arrResult['ispayfee']				=	$arrTemp['ispayfee'];
			$arrResult['is_single_paid']		=	$arrTemp['is_single_paid'];
			$arrResult['israceold']		=	$arrTemp['israceold'];
			
			$arrResult['info'] = array();
			$arrResult['info']['subAttrib'] = $arrResult['subAttrib'];
			$arrResult['info']['bu_name'] = $arrResult['bu_name'];
			$arrResult['info']['StoreID'] = $arrResult['StoreID'];
			$arrResult['info']['attribute']	= $arrResult['attribute'];
			
//			$arrResult['music']			=	$arrTemp['music'];
//			$arrResult['music_name']	=	$arrTemp['music_name'];
			$maxChars					=	$templateSet[$template['TemplateName']]['maxChars'];
			if ($maxChars) {
				$arrResult['bu_desc'] = subStoreDetail($arrTemp['bu_desc'],$StoreID,$maxChars,50,'soc.php?cp=shopdes');
			}

			if ($arrResult['attribute'] >0) {
				$objUI	=	new uploadImages();
				$arrResult['images']	=	$objUI -> getDisplayImage('',$StoreID,0,-1,-1,($arrResult['attribute']+1));
				unset($objUI);
			}
			
			$arrResult['info']['images'] = $arrResult['images'];

			$arrResult['is_customer'] = ($_SESSION['level']==2 or $_SESSION['level']==1)?true:false;

		}
		$query	=	"select count(*) as num from " . $this->table. "review where StoreID='$StoreID' and upid=0 and type='store' and content_type='review'";
		$this -> dbcon -> execute_query($query);
		$review = $this -> dbcon -> fetch_records();
		$arrResult['reviews'] = $review[0]['num'];

		return $arrResult;
	}

	function getStorePayment($StoreID='') {
		$StoreID = $StoreID ? $StoreID : $_SESSION[ShopID];
		$query = "SELECT `key`, `payment` FROM ".$this-> table."payment WHERE StoreID ='$StoreID'";
		$this-> dbcon -> execute_query($query);
		$arrTemp = $this->dbcon->fetch_records();
		$arrResult = array();
		if($arrTemp && is_array($arrTemp) & !empty($arrTemp)) {
			foreach ($arrTemp as $val) {
				$arrResult[$val['key']] = $val['payment'];
			}
		} else {
			$info = $this->getStoreInfo($StoreID);
			$arrResult = unserialize($info['bu_delivery_text']);
		}

		return $arrResult;
	}

	/**
	 * get state full name and short name.
	 *
	 * @param int $stateID
	 * @param int $type
	 * @return array or string
	 */
	function _getStateName($stateID,$type=1)
	{
		$strResult = null;
		$query	= "select stateName,description from " . $this -> table ."state where id='$stateID'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records();
		if (is_array($arrTemp)) {
			if ($type == 1) {
				$strResult = $arrTemp[0]['stateName'];
			}elseif ($type == 2){
				$strResult = $arrTemp[0]['description'];
			}else{
				$strResult = $arrTemp[0];
			}
		}
		return $strResult;
	}

	/**
	 * get delivery of The SOC Exchange
	 *
	 * @author ping.hu <suppoit@infinitytesting.com.au> 20080221
	 * @return array
	 */
	function _getProductOfferDelivery($StoreID){
		$arrResult	=	array();
		$query	=	"select DeliveryID,StoreID,DeliveryPrice,OfferDelevery,termnCon as termnContent ".
		" from ". $this -> table ."deliverydetail where StoreID='$StoreID'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if(is_array($arrTemp)){
			if ($arrTemp[0]['OfferDelevery'] == "yes") {
				$arrResult = $arrTemp[0];
				$arrResult['offer']	= true;
			}else {
				$arrResult['termnContent']	= $arrTemp[0]['termnContent'];
				$arrResult['offer']	= false;
			}
		}

		return $arrResult;
	}

	//for Blog below


	/**
	 * get sub article of The SOC Exchange
	 *
	 * @param string $detail
	 * @param int $length
	 * @return array
	 */
	function _subDetail($detail, $length=500){
		if (strlen($detail) < $length){
			return $detail;
		}else{
			$str = substr($detail,0,$length);
			//echo "1--------<br>$str<br>---------------1";
			$tmp = array();
			//echo '&lt;count:'.preg_match_all("/</",$str,$tmp).' &gt;count:'.preg_match_all("/>/",$str,$tmp);
			if (preg_match_all("/</",$str,$tmp) > preg_match_all("/>/",$str,$tmp)){
				$str = substr($str,0,strrpos($str,'<'));
				//echo '<br>&lt;pos:'.strrpos($str,'<').'<br>cuted:'.$str;
			}
			if (strrpos($str,' ',-1)){
				$str = substr($str,0, strrpos($str, ' ',-1));
			}
			$str.= ' ... ';
		}
		return $str;
	}

	/**
	 * get blog navi of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @param int $pageid
	 * @param int $per_num
	 * @return array
	 */
	function getBlogNavi($pageid=	1, $per_num=5, $isOwner=false){
		$arrResult	=array();
		$StoreID		=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$table = $this->table . "blog" ;

		if ($isOwner){
			$query	=	"select count(0) as total from $table where StoreID = $StoreID";
		}else{
			$query	=	"select count(0) as total from $table where StoreID = $StoreID";
		}
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		$total = $arrTemp[0]['total'];

		$arrResult = $this -> getPageNavi($total, $pageid, $per_num, 'blog', $StoreID);
		return $arrResult;
	}

	/**
	 * get blog comment navi of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @param int $pageid
	 * @param int $per_num
	 * @return array
	 */
	function getBlogCommentNavi($blogid,$isOwner=false, $pageid=1, $per_num=5){
		$arrResult	=	array();
		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$table = $this->table . "blog_comment" ;

		if ($isOwner){
			$query	=	"select count(*) as total from $table where blog_id = $blogid";
		}else{
			$query	=	"select count(*) as total from $table where blog_id = $blogid and approved=1";
		}
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		$total = $arrTemp[0]['total'];
		//echo "sql:$query, total:$total, page:$pageid, num:$per_num\n";
		$arrResult = $this -> getPageNavi($total, $pageid, $per_num, "blogpage&bid=$blogid", $StoreID);
		return $arrResult;
	}

	/**
	 * get common pages navi list of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @param int $list_num
	 * @param int $sub_article_len
	 * @return string
	 */
	function getPageNavi($total, $pageid=1, $per_num=5, $cp, $sid){
		$Result	=	'';

		$pages = ceil($total/$per_num);

		if ($pageid > 1){
			$begin = ($pageid-1)*$per_num+1;
		}else{
			$begin = 1;
		}
		if($total == 0){
			$begin = 0;
		}
		$end = ($begin+$per_num-1);
		$end = ($end > $total)?$total:$end;

		$navi = $begin . ' - ' . $end . ' of ' . $total;
		$navi .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		if ($pageid > 1){
			//$navi .= "<a href=\"soc.php?cp=$cp&pageid=1\">First</a>&nbsp;";
			$navi .= "&nbsp;<a href=\"soc.php?cp=$cp&StoreID=$sid&pageid=".($pageid-1)."\">&lt;&lt;Previous</a>&nbsp;";
		}else{
			//$navi .= "First&nbsp;Prev&nbsp;";
			$navi .= "&nbsp;&lt;&lt;Previous&nbsp;";
		}

		for($i=1; $i<=$pages; $i++){
			if ($pageid == $i){
				$navi .= "&nbsp;$i&nbsp;";
			}else{
				$navi .= "&nbsp;<a href=\"soc.php?cp=$cp&StoreID=$sid&pageid=$i\">$i</a>&nbsp;";
			}
		}

		if ($pageid < $pages){
			$navi .= "&nbsp;<a href=\"soc.php?cp=$cp&StoreID=$sid&pageid=".($pageid+1)."\">Next&gt;&gt;</a>&nbsp;";
			//$navi .= "&nbsp;<a href=\"soc.php?cp=$cp&pageid=$pages\">Last</a>";
		}else{
			//$navi .= "&nbsp;Next&nbsp;Last";
			$navi .= "&nbsp;Next&gt;&gt;";
		}
		if ($total == 0){
			$Result = '';
		}else{
			$Result = $navi;
		}
		return $Result;
	}

	/**
	 * get blog article list of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @param int $list_num
	 * @param int $sub_article_len
	 * @return array
	 */
	function getBlogArticleList($pageid=1, $list_num=5){
		$arrResult	=	array();
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$query	=	"select * from ".$this->table . "blog where StoreID = $StoreID order by modify_date DESC limit ".($pageid-1)*$list_num . ", $list_num";
		$this -> dbcon -> execute_query($query);

		$arrTemp = $this -> dbcon -> fetch_records(true);
		if(is_array($arrTemp)){
			$isOwner = false;
			foreach ($arrTemp as $blogitem){
				if ($_SESSION['level'] == 1 ){
					if($StoreID==$_SESSION['ShopID']){
					$blogitem['edit'] = '<a href="soc.php?cp=blogedit&act=edit&bid=' . $blogitem['blog_id'] . '" class="back2srchLink">Edit</a>';
					$blogitem['del'] = '<a href="soc.php?cp=blogedit&act=del&bid=' . $blogitem['blog_id'] . '" onclick="if(confirm(\'Are you sure to delete this blog?\')){return true;}else{return false;}" class="back2srchLink">Delete</a>';
					}else{
						$blogitem['edit'] = "";
						$blogitem['del']  = "";
					}
					$blogitem['addComment'] = '<a href="soc.php?cp=blogpage&act=del&bid=' . $blogitem['blog_id'] . '#addComment" class="back2srchLink">Add Comment</a>';
				}elseif ($_SESSION['level'] == 2 ){
					$blogitem['edit'] = '';
					$blogitem['del'] = '';
					$blogitem['addComment'] = '<a href="soc.php?cp=blogpage&act=del&bid=' . $blogitem['blog_id'] . '#addComment" class="back2srchLink">Add Comment</a>';
				}else{
					$blogitem['edit'] = '';
					$blogitem['del'] = '';
					$blogitem['addComment'] = '';
				}
				$blogitem['StoreID'] = $StoreID;
				$blogitem['modify_date'] = date(DATAFORMAT_DB, $blogitem['modify_date']);
				$blogitem['modify_date'] = str_replace('%','',$blogitem['modify_date']);
				$blogitem['modify_date'] = str_replace('-','/',$blogitem['modify_date']);
				$content = stripcslashes($blogitem['content']);
				$content = str_replace("\n",'<br />',$content);
				$blogitem['content'] = html_entity_decode($this -> _subDetail($content, $this->blog_length));
				$blogitem['more'] .= '<a href="soc.php?cp=blogpage&StoreID='.$StoreID.'&bid='.$blogitem['blog_id'].'&pageid=1" class="back2srchLink">More Detail</a>';
				$blogitem['commentLink'] .= '<a href="soc.php?cp=blogpage&StoreID='.$StoreID.'&bid='.$blogitem['blog_id'].'&pageid=1" class="back2srchLink">'.$blogitem['comment'].' Comment(s)</a>';
				if($StoreID==$_SESSION['ShopID']){
					$blogitem['approval'] = $blogitem['total_comments']-$blogitem['comment'];
				}else{
					$blogitem['approval'] = 0;
				}
				$arrResult['blogitem'][] = $blogitem;

			}
		}
		if ($_SESSION['level'] == 1 and $StoreID==$_SESSION['ShopID']){
			$isOwner = true;
			$blogitem['new'] = '<a href="soc.php?cp=blogedit&act=new">Add New Posting</a>';
		}elseif ($_SESSION['level'] == 2 ){
			$blogitem['new'] = '';
		}else{
			$blogitem['new'] = '';
		}
		$arrResult = array_merge ($arrResult, $blogitem);
		$arrResult['navi'] = $this -> getBlogNavi($pageid,5,$isOwner);

		return $arrResult;
	}

	/**
	 * get blog name of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @param int $blog_id
	 * @return array
	 */
	function getBlogInfo(){
		$arrResult	=	array();
		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		//$UserName	=	$_SESSION['NickName'] ? $_SESSION['NickName'] : ($_SESSION['UserName'] ? $_SESSION['UserName']:'');
		$query	=	"select bu_name from " . $this->table . "bu_detail where StoreID = $StoreID";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		$UserName = $arrTemp[0]['bu_name'];

		$arrResult['name'] = $UserName . '\'s Blog';

		return $arrResult;
	}

	/**
	 * get blog article list of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @param int $blog_id
	 * @return array
	 */
	function dispBlogArticle($blog_id, $pageid){
		$arrResult	=	array();
		$query	=	"select * from " . $this->table . "blog where blog_id = $blog_id limit 1";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);

		$blog_images_count = 0;
		if(is_array($arrTemp)){
			foreach ($arrTemp[0] as $key=>$value){
				if ($value == 'images/50x50.gif'){
					$arrTemp[0][$key] = '';
				}
				if (substr($key,-5)=='thump' and $arrTemp[0][$key]!=''){
					$blog_images_count+=1;
				}
			}
			$storeID = $arrTemp[0]['StoreID'];
			$query = "select bu_nickname from ".$this->table."bu_detail where StoreID=$storeID";
			$this->dbcon->execute_query($query);
			$nickname = $this->dbcon->fetch_records(true);
			$nickname = $nickname[0]['bu_nickname'];
			$blogitem['nickname'] = $nickname;
			$isOwner = false;
			if ($_SESSION['level'] == 1 and $_SESSION['ShopID']==$storeID){
				$blogitem['new'] = '<a href="soc.php?cp=blogedit&act=new">Add New Posting</a>';
				$blogitem['edit'] = '<a href="soc.php?cp=blogedit&act=edit&bid=' . $blog_id . '" class="back2srchLink">Edit</a>';
				$blogitem['del'] = '<a href="soc.php?cp=blogedit&act=del&bid=' . $blog_id . '" onclick="if(confirm(\'Are you sure to delete  this blog?\')){return true;}else{return false;}" class="back2srchLink">Delete</a>';
				$isOwner = true;
			}elseif ($_SESSION['level'] == 2 ){
				$blogitem['new'] = '';
				$blogitem['edit'] = '';
				$blogitem['del'] = '';
			}else{
				$blogitem['new'] = '';
				$blogitem['edit'] = '';
				$blogitem['del'] = '';
			}
			$arrResult = $arrTemp[0];
			$blogitem['modify_date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)), $arrResult['modify_date']);
			$content = stripcslashes($arrResult['content']);
			if (strlen($content) > $this->blog_length){
				$arrResult['subContent'] = $this -> _subDetail($content, $this->blog_length);
				$arrResult['subContent'] = str_replace("\n", '<br />', $arrResult['subContent']);
				//$arrResult['more'] = 'true';
			}
			if ($arrResult['more']!='true' and $blog_images_count>=2 || ($blog_images_count==1 && empty($arrResult['image1']))){
				//			if ($arrResult['more']!='true' and $blog_images_count>=2){
				$arrResult['subContent']=$arrResult['content'];
				$arrResult['more']='true';
			}
			$content = str_replace("\n",'<br />',$content);
			$arrResult['content']	=	html_entity_decode($content);
			// set subContent without the cut off by Jessee 080506
			$arrResult['subContent'] = $arrResult['content'];
			$arrResult['comment']   =   $this -> getBlogComments($blog_id, $isOwner, $pageid);
			$arrResult['isOwner']	= $isOwner;
			$arrResult = array_merge ($arrResult, $blogitem);
			$arrResult['navi'] = $this -> getBlogCommentNavi($blog_id, $isOwner, $pageid);
			$arrResult['addComment'] = ($_SESSION['LOGIN'] == 'login') ? 'true' : 'false';
		}

		return $arrResult;
	}

	/**
	 * @title	: delete blog by blog id
	 * Mon Mar 23 01:36:56 GMT 2009 01:36:56
	 * @input	: $blogid
	 * @output	: bool
	 * @description	:
	 * @author	: Roy.luo <support@infinitytesting.com.au>
	 * @version	: V1.0
	 *
	*/
	function delbolgbyid($blogid){
		$query	=	"delete from " . $this->table . "blog_comment where blog_id = $blogid";
		if($this -> dbcon -> execute_query($query)){
			$query	=	"delete from " . $this->table . "blog where blog_id = $blogid";
			if($this -> dbcon -> execute_query($query)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	/**
	 * get blog article list of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @param int $blog_id
	 * @return array
	 */
	function _getBlogArticle($blog_id){
		$arrResult	=	array();

		$query	=	"select * from " . $this->table . "blog where blog_id = $blog_id limit 1";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);

		if(is_array($arrTemp)){

			$arrResult['subject']		=	$arrTemp[0]['subject'];
			$arrResult['content']		=	$arrTemp[0]['content'];
			$arrResult['image_name']		=	$arrTemp[0]['image1'];
			$arrResult['mainImageH']		=	$arrTemp[0]['image1'];
			$arrResult['image1_thump']	=	$arrTemp[0]['image1_thump'];
			$arrResult['moreImage1']		=	$arrTemp[0]['image2'];
			$arrResult['image2_thump']	=	$arrTemp[0]['image2_thump'];
			$arrResult['moreImage2']		=	$arrTemp[0]['image3'];
			$arrResult['image3_thump']	=	$arrTemp[0]['image3_thump'];
			$arrResult['moreImage3']		=	$arrTemp[0]['image4'];
			$arrResult['image4_thump']	=	$arrTemp[0]['image4_thump'];

		}
		return $arrResult;
	}

	/**
	 * get blog article list of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @param int $list_num
	 * @param int $sub_article_len
	 * @return array
	 */
	function getBlogComments($blogid=0, $isOwner=false, $pageid=1, $list_num=5){
		$arrResult	=	array();
		//$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');

		$table = $this->table . "blog_comment" ;
		if ($_SESSION['level'] == 1 and $isOwner){
			$query	=	"select * from $table where blog_id = $blogid order by post_date DESC limit " . ($pageid-1)*$list_num . ", $list_num";
		}else{
			$query	=	"select * from $table where blog_id = $blogid and approved = 1 order by post_date DESC limit " . ($pageid-1)*$list_num . ", $list_num";
		}

		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);

		if(is_array($arrTemp)){
			$curr_num = 0;
			foreach ($arrTemp as $blogitem){
				$content = stripcslashes($blogitem['content']);
				$content = str_replace("\n", '<br />', $content);
				$blogitem['content'] = html_entity_decode($content);
				$blogitem['post_date'] = date('m-d-Y H:i:s', $blogitem['post_date']).' (ET)';
				$blogitem['reply_date'] = date('m-d-Y H:i:s', $blogitem['reply_date']).' (ET)';

				$reply = stripcslashes($blogitem['reply']);
				$reply = str_replace("\n", '<br />', $reply);
				$blogitem['reply'] = html_entity_decode($reply);

				$StoreID = $blogitem['StoreID'] ? $blogitem['StoreID']:'';
				$query	=	"select bu_nickname, bu_name, bu_suburb from ".$this->table."bu_detail where StoreID = $StoreID limit 1";
				$this -> dbcon -> execute_query($query);
				$arrTemp1 = $this -> dbcon -> fetch_records(true);
				//print_r($arrTemp1);

				if(is_array($arrTemp1)){
					$UserName	=	$arrTemp1[0]['bu_nickname'] ? $arrTemp1[0]['bu_nickname'] : ($arrTemp1[0]['bu_name'] ? $arrTemp1[0]['bu_name']:'');
					$location 	=	$arrTemp1[0]['bu_suburb'] ? $arrTemp1[0]['bu_suburb']:'';
				}

				$blogitem['username'] = $UserName;
				$blogitem['location'] = $location;
				$arrResult[] = $blogitem;
				$curr_num++;
				if ($list_num>0 && $list_num <= $curr_num){
					break;
				}
			}
		}
		return $arrResult;
	}

	/**
	 * add blog article of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @return boolean
	 */
	function blogArticleAddOrEditOperate(){
		$boolResult	=	false;

		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		//$bid = $_REQUEST['bid'] ? $_REQUEST['bid'] : ($_SESSION['bid'] ? $_SESSION['bid']:'');

		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);

		$check_fields = $subject." ". $content;

		$strCondition ="where StoreID='$StoreID' and blog_id='$bid' limit 1";

		if ($act == 'del' || $_REQUEST['act'] == 'del'){

			if($this -> dbcon-> checkRecordExist($this->table."blog", $strCondition)){
				/*
				$arrSetting	= array(
				'deleted'	=>	'YES'
				);
				$boolResult = $this->dbcon-> update_record($this->table."blog", $arrSetting, $strCondition);
				*/

				$query = "delete from " . $this->table ."blog " . $strCondition;
				$boolResult = $this->dbcon-> execute_query($query);

			}
			if ($boolResult) {
				$msg	=	'Record deleted successfully. ';
			}else {
				$msg	=	'Record deleted failed. ';
			}

		}elseif (empty($StoreID)) {
			$msg	= "Store id not exist. Please relogin. ";
		}elseif (empty($subject)) {
			$msg	= "Subject is required. ";
		}elseif(empty($content)){
			$msg	= "content is required. ";
		}elseif(check_badwords($check_fields)){
			$msg	= "Please keep our website wholesome and clean by refraining from using vulgar words!";

		}elseif ($_REQUEST['act'] == 'new'){ //add new one

			$arrSetting = array(
			'StoreID'		=>	"$StoreID",
			'subject'		=>	"$subject",
			'content'		=>	"$content",
			'image1'		=>	"$mainImageH",
			'image1_thump'	=>	"$mainImageH",
			'image2'		=>	"$moreImage1",
			'image2_thump'	=>	"$moreImage1",
			'image3'		=>	"$moreImage2",
			'image3_thump'	=>	"$moreImage2",
			'image4'		=>	"$moreImage3",
			'image4_thump'	=>	"$moreImage3",
			'publish_date'	=>	time()+$this->time_zone_offset,
			'modify_date'	=>	time()+$this->time_zone_offset,
			'view'			=>	"0",
			'comment'		=>	"0"
			);

			$boolResult	= $this->dbcon-> insert_record($this->table."blog", $arrSetting);

			if ($boolResult) {
				$msg	=	'Record saved successfully. ';
			}else {
				$msg	=	'Record saved failed. ';
			}

		}elseif ($_REQUEST['act'] == 'edit'){

			$arrSetting = array(
			'subject'		=>	"$subject",
			'content'		=>	"$content",
			'image1'		=>	"$mainImageH",
			'image1_thump'	=>	"$mainImageH",
			'image2'		=>	"$moreImage1",
			'image2_thump'	=>	"$moreImage1",
			'image3'		=>	"$moreImage2",
			'image3_thump'	=>	"$moreImage2",
			'image4'		=>	"$moreImage3",
			'image4_thump'	=>	"$moreImage3",
			'modify_date'	=>	time()+$this->time_zone_offset
			);

			if($this -> dbcon-> checkRecordExist($this->table."blog", $strCondition)){
				$boolResult = $this->dbcon-> update_record($this->table."blog", $arrSetting, $strCondition);
			}else {
				$arrSetting['StoreID']	= "$StoreID";
				$arrSetting['publish_date']	= time();
				$boolResult = $this->dbcon-> insert_record($this->table."blog", $arrSetting);
			}

			if ($boolResult) {
				$msg	=	'Record saved successfully. ';
			}else {
				$msg	=	'Record saved failed. ';
			}
		}

		$this -> addOperateMessage($msg);

		return $boolResult;
	}

	/**
	 * add blog article of The SOC Exchange
	 *
	 * @author Rocky Loo <rocky.luo@infinitytesting.com.au> 20080428
	 * @return array
	 */
	function blogArticleAddOrEdit(){
		$arrResult	=	array();

		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$act		=	$_REQUEST['act'] ? $_REQUEST['act'] : ($_SESSION['act'] ? $_SESSION['act']:'');
		$bid 		= 	$_REQUEST['bid'] ? $_REQUEST['bid'] : ($_SESSION['bid'] ? $_SESSION['bid']:'');

		if ($this-> _notVar)
		{
			$arrResult['select']	=	$this -> getFormInputVar();
			//print_r($arrResult);
		}else{
			$arrResult['select']	=	$this -> _getBlogArticle($bid);
		}

		if (empty($arrResult['select']['image_name'])) {
			$arrResult['select']['image_name']	=	'images/50x50.gif';
		}
		if (empty($arrResult['select']['moreImage1'])) {
			$arrResult['select']['moreImage1']	=	'images/50x50.gif';
		}
		if (empty($arrResult['select']['moreImage2'])) {
			$arrResult['select']['moreImage2']	=	'images/50x50.gif';
		}
		if (empty($arrResult['select']['moreImage3'])) {
			$arrResult['select']['moreImage3']	=	'images/50x50.gif';
		}
		$arrResult['select']['msg']		=	$_REQUEST['msg'];
		$arrResult['select']['act']		=	$act;
		$arrResult['select']['bid']		=	$bid;

		//print_r($arrResult['select']);
		return $arrResult;
	}

	function blogCommentAddOrEditOperate($isUpdate=false){
		$boolResult	=	false;
		$email_regards = 'FoodMarketplace Australia';
		//$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$bid = $_REQUEST['bid'] ? $_REQUEST['bid'] : '';
		$cid		=	$_REQUEST['cid'] ? $_REQUEST['cid'] : '';
		$act 		= 	$_REQUEST['act'] ? $_REQUEST['act'] : '';

		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);
		$StoreID = $_SESSION['ShopID'];

		$content = $_var['content'];

		$check_fields = $content;

		if($act == 'appr'){ //approved comment
			$strCondition ="where comment_id='$cid' limit 1";
			$arrSetting = array(
			'approved'		=>	"1"
			);
			if($this->dbcon-> update_record($this->table."blog_comment", $arrSetting, $strCondition)){
				//setting session var
				$this->dbcon->execute_query("update ".$this->table."blog set comment=comment+1 where blog_id=$bid");
				$boolResult	=	true;
				$msg	=	"Comment approved.";
			}else{
				$msg	=	"Operation failed by inster while approve.";
			}
		}elseif($act == 'del') {
			$strCondition ="where comment_id='$cid' limit 1";
			if($this -> dbcon-> checkRecordExist($this->table."blog_comment", $strCondition)){
				$this->dbcon->execute_query("select approved from ".$this->table."blog_comment where comment_id=$cid");
				$isApproved = $this->dbcon->fetch_records();
				$isApproved = $isApproved[0]['approved'];
				if ($isApproved == 1){
					$this->dbcon->execute_query("update ".$this->table."blog set comment=comment-1,total_comments=total_comments-1 where blog_id=$bid");
				}else{
					$this->dbcon->execute_query("update ".$this->table."blog set total_comments=total_comments-1 where blog_id=$bid");		}
					$query = "delete from " . $this->table ."blog_comment " . $strCondition;
					$boolResult = $this->dbcon-> execute_query($query);
			}
			if ($boolResult) {
				$msg	=	'Record deleted successfully. ';
			}else {
				$msg	=	'Record deleted failed. ';
			}
		}elseif (empty($StoreID)) {
			$msg	= "Store id not exist. Please relogin. ";
		}elseif(empty($content)){
			$msg	= "Please input content. ";
		}elseif(check_badwords($check_fields)){
			$msg	= "Please keep our website wholesome and clean by refraining from using vulgar words!";
		}elseif(!$isUpdate){ //add
			$arrSetting = array(
			'StoreID'		=>	"$StoreID",
			'content'		=>	"$content",
			'post_date'		=>	time()+$this->time_zone_offset,
			'blog_id'		=>	"$bid",
			'approved'		=>	"0"
			);
			if($this->dbcon-> insert_record($this->table."blog_comment", $arrSetting)){
				//setting session var
				$this->dbcon->execute_query("update ".$this->table."blog set total_comments=total_comments+1 where blog_id=$bid");
				$boolResult	=	true;
				$msg	=	"Add comment success.";
				$query = "select bu_nickname,bu_email from {$this->table}bu_detail where StoreID={$_REQUEST['StoreID']}";
				$this->dbcon->execute_query($query);
				$result = $this->dbcon->fetch_records(true);
				$owner = $result[0]['bu_nickname'];
				$ownemail = $result[0]['bu_email'];
				$url = "http://{$_SERVER['HTTP_HOST']}/soc.php?cp=blogpage&StoreID={$_REQUEST['StoreID']}&bid={$bid}&pageid=1";
				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= 'From: noreply@'.EMAIL_DOMAIN . "\r\n";
				$subject = "Notification from your Blog";
				$message = "Dear {$owner},<br/><br/>";
				$message .= "This email is to notify you that an update has been made on your blog.<br/>";
				$message .= "Your blog can be viewed at: <a href='$url' target='_bank'>$url</a><br/><br/>";
				$message .= "Sincerely,<br/>$email_regards<br/>";

				@mail($ownemail, $subject, getEmailTemplate($message), FixEOL($headers));
				$query = "INSERT INTO `". $this->table ."message` (`subject`, `message`, `StoreID`, `date`,`emailaddress`,`fromtoname`)VALUES('$subject','".addslashes($message)."','{$_REQUEST['StoreID']}','".time()."','SYSTEM','SYSTEM') ";
				$this->dbcon->execute_query($query);
			}else{
				$msg	=	"Operation failed by inster while post comment.";
			}

		}elseif ($isUpdate){ //edit approved

		}

		$this -> addOperateMessage($msg);

		return $boolResult;
	}

	function ownerReplyOperate(){
		$boolResult = false;

		$StoreID = $_SESSION['ShopID'];
		$cid		=	$_REQUEST['cid'] ? $_REQUEST['cid'] : '';
		$act 		= 	$_REQUEST['act'] ? $_REQUEST['act'] : '';

		$_var 		= 	$this -> setFormInuptVar();
		extract($_var);

		$reply = $_var['reply'];


		$check_fields = $reply;

		if ($act == 'del'){
			$reply = null;
		}

		if (empty($StoreID)) {
			$msg	= "Store id not exist. Please relogin. ";
		}elseif(check_badwords($check_fields)){
			$msg	= "Please keep our website wholesome and clean by refraining from using vulgar words!";
		}else{

			$arrSetting = array(
			'reply'		=>	"$reply",
			'reply_date' => time()+$this->time_zone_offset
			);

			$strCondition ="where comment_id='$cid' limit 1";
			if($this -> dbcon-> checkRecordExist($this->table."blog_comment", $strCondition)){
				$boolResult = $this->dbcon-> update_record($this->table."blog_comment", $arrSetting, $strCondition);
			}

			if ($boolResult) {
				$msg	=	'Record saved successfully. ';
			}else {
				$msg	=	'Record saved failed. ';
			}
		}

		$this -> addOperateMessage($msg);

		return $boolResult;
	}

	/**
	 * blog reply
	 *
	 * @return array
	 */
	function blogReply(){
		$arrResult	=	array();

		$StoreID	=	$StoreID = $_SESSION['ShopID'];
		$act		=	$_REQUEST['act'] ? $_REQUEST['act'] : ($_SESSION['act'] ? $_SESSION['act']:'new');
		$bid 		= 	$_REQUEST['bid'] ? $_REQUEST['bid'] : ($_SESSION['bid'] ? $_SESSION['bid']:'');
		$cid 		= 	$_REQUEST['cid'] ? $_REQUEST['cid'] : ($_SESSION['cid'] ? $_SESSION['cid']:'');

		$arrResult['msg']		=	$_REQUEST['msg'];
		$arrResult['act']		=	$act;
		$arrResult['bid']		=	$bid;
		$arrResult['cid']		=	$cid;
		$arrResult['StoreID']		=	$StoreID;

		//print_r($arrResult['select']);
		return $arrResult;
	}

	/**
	 * generate RSS XML.
	 * @param int
	 * @return array
	 */
	function getBlogRssArray($list_num = 50){
		global $normal_url;
		$arrResult	=	array();
		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');

		//$blogInfo = $this -> getBlogInfo();
		$arrResult['title'] =  'The SOC Exchange Blog';
		$arrResult['pubDate'] =  time() + $this->time_zone_offset;
		$arrResult['lastBuildDate'] =  time() + $this->time_zone_offset;
		$arrResult['description'] =  'The SOC Exchange Blog';
		$arrResult['link'] = "{$normal_url}soc.php?cp=blogrss";

		$arrResult['image']['title'] =  'The SOC Exchange Blog';
		$arrResult['image']['url'] =  "{$normal_url}images/soc_logo_final.jpg";
		$arrResult['image']['link'] = "{$normal_url}";

		$strCondition = "";

		$query	=	"select `blog`.* from " . $this->table . "blog `blog`, " . $this->table . "bu_detail `detail` ";
		$query  .=  " where blog.StoreID=detail.StoreID ";
		$query  .=  " and blog.StoreID= $StoreID ";
		$query  .=  " order by blog.modify_date DESC limit $list_num";
		$this -> dbcon -> execute_query($query);

		$arrTemp = $this -> dbcon -> fetch_records(true);
		if(is_array($arrTemp)){
			foreach ($arrTemp as $blogitem){
				$rssItem = array();

				$rssItem['description'] = $blogitem['content'];
				$rssItem['title'] = $blogitem['subject'];
				$rssItem['link'] = "{$normal_url}soc.php?cp=blogpage&amp;StoreID={$blogitem['StoreID']}&amp;bid={$blogitem['blog_id']}&amp;pageid=1";
				$rssItem['guid'] = $rssItem['link'];
				$rssItem['pubDate'] =  date('D d M Y H:i:s T', $blogitem['modify_date']).date("P");

				$arrResult['item'][] = $rssItem;
			}
		}

		return $arrResult;
	}


	/**
	 * get product data array for Rss
	 * @param none
	 * @return array
	 */
	function getProductRssData($list_num = 50){
		global $normal_url;
		$arrResult	=	array();

		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');

		$arrResult['title'] =  'The SOC Exchange Items';
		$arrResult['pubDate'] =  time() + $this->time_zone_offset;
		$arrResult['lastBuildDate'] =  time() + $this->time_zone_offset;
		$arrResult['description'] =  'The SOC Exchange Items';
		$arrResult['link'] = "{$normal_url}soc.php?cp=category";

		$arrResult['image']['title'] =  'The SOC Exchange Items';
		$arrResult['image']['url'] =  "{$normal_url}images/product_logo.gif";
		$arrResult['image']['link'] = "{$normal_url}";


		$query = "select product.pid,product.item_name,product.StoreID,product.description,product.datem,";
		$query.= "product.datec,product.category from ".$this->table . "product as product,";
		$query.= $this->table."bu_detail as detail where product.StoreID=detail.StoreID";
		$query  .=  " and product.StoreID= $StoreID ";
		$query.= " and product.category!=0 and product.deleted='' order by datem desc limit $list_num";
		$this->dbcon->execute_query($query);

		$arrTemp = $this->dbcon->fetch_records(true);
		if(is_array($arrTemp)){
			foreach ($arrTemp as $item){
				$rssItem = array();
				//$rssItem['description'] = htmlentities(stripcslashes($this->XmlSafeStr($this->clearHTMLChar($item['description'],0))));
				$rssItem['title'] = $this->clearHTMLChar($item['item_name'],0);
				$rssItem['description'] = $this->clearHTMLChar($item['description'],0);
				$category = $this->getCategoryString($item['category']);
				$rssItem['link'] = "{$normal_url}soc.php?cp=dispro&amp;StoreID=".$item['StoreID']."&amp;proid=".$item['pid'];
				$rssItem['guid'] = $rssItem['link'];
				$rssItem['pubDate'] =  date('D d M Y H:i:s T', $item['datem']);
				$arrResult['item'][] = $rssItem;
			}
		}

		return $arrResult;
	}

	/**
	 * generate Category string of SOC products.
	 * @param category id
	 * @return string
	 */
	function getCategoryString($id){
		if ($id > 33){
			$query = "select a.name as fname,b.name from ".$this->table."product_category as a ";
			$query.= "left join ".$this->table."product_category as b on a.fid =b.id ";
			$query.= "where a.id=$id";
			$this->dbcon->execute_query($query);
			$arrTmp = $this->dbcon->fetch_records();
			return $arrTmp[0]['fname']."&gt;&gt;".$arrTmp[0]['name'];
		}else{
			$query = "select name from ".$this->table."product_category where id=$id";
			$this->dbcon->execute_query($query);
			$arrTmp = $this->dbcon->fetch_records();
			return $arrTmp[0]['name'];
		}
	}


	function getCategoryByName($id){
		$query = "select name from ".$this->table."product_category where id=$id order by name ASC";
		$this->dbcon->execute_query($query);
		$arrTmp = $this->dbcon->fetch_records();
		return $arrTmp[0]['name'];
	}

	/**
	 * send email to user's friend
			* @param
			* @return message string
			*/
	function emailToFriend($StoreID,$pid=0){
		global $normal_url;

		$msg = '';
		$tmpval = array();
		$check = true;
		if (!empty($_POST)){

			$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
		   	if(!eregi($pattern, $_POST['to'])) {
		   		$msg = "Recipient's email address is invalid.";
				$check = false;
		 	} elseif(!eregi($pattern, $_POST['from'])) {
		   		$msg = "Your email address is invalid.";
				$check = false;
		 	} elseif (strtolower($_POST['validation'])!=strtolower($_SESSION['authnum'])){
				$msg = "Validation Code is invalid.";
                if (get_magic_quotes_gpc()) {
                    $_POST = stripslashes_deep($_POST);
                }
                $check = false;
			}
			if (!$check) {
				array_walk($_POST,'html_decode');
                $tmpval = $_POST;
			}
		}

		if (!empty($_REQUEST['to']) && $check){
			// send email
			$store_name = getStoreURLNameById($StoreID);
			$url_name = clean_url_name($store_name);
			$toname = stripcslashes($_REQUEST['from_name']);
			$from = stripcslashes($_REQUEST['from']);
			$to = stripcslashes($_REQUEST['to']);
			$sender = stripcslashes($_REQUEST['sender']);
			$message = stripcslashes($_REQUEST['message']);
			$infomation = $this->getStoreInfo($StoreID,$pid);
			//if ($_REQUEST['format']=='html'){
//				$message = "Dear $toname,<br><br>".nl2br($message);
//				$message.= "<br><br> Following is the link to the website:";
//				$message .="<table width=100% cellspacing=4>";
//				if($infomation['Images']!="" || 1){
//					$message .= "<tr><td rowspan=4 width='100' align=center valign=top><img src='".$normal_url."/".($infomation['Images']?$infomation['Images']:'images/79x79.jpg')."' width=81 /></td></tr>";
//				}
//				if($infomation['url_item_name']){
//					$message.= "<tr><td align=left><a href='{$normal_url}/{$infomation['bu_urlstring']}/{$infomation['url_item_name']}'>".htmlspecialchars($infomation['item_name'])."</a></td></tr>";
//				}
//				$message.= "<tr><td>" . truncate($infomation['description'], 90) . "</td></tr>";
//				$message.= "<tr><td><a href='{$normal_url}/{$infomation['bu_urlstring']}'>".$infomation['bu_name']."</a></td>";
//				$message.= "</tr></table>";
//				$message .= "<br><br>Sincerely,<br>$sender";
//			}else{
//				$message = "Dear $toname,\n\n".$message;
//				$message.= "\n\n Following is the link to the website:\n";
//				$message.= "{$infomation['url_item_name']}\n";
//				$message.= truncate($infomation['description'], 90) . "\n";
//				$message.= $infomation['bu_name']."\n";
//				$message.= "\nSincerely,\n$sender";
//			}

            if ($_REQUEST['format']=='html'){
                $message = "Hi $toname,<br/><br/>".nl2br($message);
                $message.= "<br/><br/>Please cllick on this link: <a style='color:red' href='{$normal_url}{$infomation['bu_urlstring']}'>".$infomation['bu_name']."</a> to visit a great business.";
                $message .= "<br/><br/>Sincerely,<br>$sender";
            }else{
                $message = "Hi $toname,\n\n".$message;
                $message.= "\n\nPlease cllick on this link: {$normal_url}{$infomation['bu_urlstring']} to visit a great business.\n";
                $message.= "\nSincerely,\n$sender";
            }            
            
			$header = "From: $sender <$from>\n";
			$header.= "Content-type: text/".$_REQUEST['format']."\n";
			$subject = "Your friend $sender invite you to visit socexchange.com.au";
//			if($toname){$to = $toname." <$to>";	}
			$result = @mail($to,$subject,  getFanfrenzyEmailTemplate($message),fixEOL($header));
			if ($result){
				return array('msg'=>'Email sent successfully','StoreID'=>$StoreID,'pid'=>$_REQUEST['pid']);
			}else{
				return array('msg'=>'Failed to send email','StoreID'=>$StoreID,'pid'=>$_REQUEST['pid']);
			}
		}else{
			return array('msg'=>$msg,'StoreID'=>$_REQUEST['StoreID'],'pid'=>$_REQUEST['pid'], 'tmpval'=>$tmpval);
		}
	}
	/***get store infomation***/
	function getStoreInfo($StoreID,$pid=0){
		$query = "SELECT * FROM {$this->table}bu_detail WHERE StoreID='$StoreID'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		switch ($result[0]['attribute']):
		case '0':
			$products="product";
                        $productDescriptionFidleName='p.description';
			break;
		case '1':
			$products="product_realestate ";
                        $productDescriptionFidleName='p.content as description';
			break;
		case '2':
			$products="product_automotive";
                        $productDescriptionFidleName='p.content as description';
			break;
		case '3':
			$products="product_job";
                        $productDescriptionFidleName='p.content1 as description';
			break;
		case '5':
			$products="product_foodwine";
                        $productDescriptionFidleName='p.description';
			break;
		endswitch;

		if($pid){
			$query = "SELECT bu.bu_urlstring,bu.bu_name,p.url_item_name,p.item_name,".$productDescriptionFidleName.",t.smallPicture as Images ".
					" FROM {$this->table}$products p".
					" LEFT JOIN {$this->table}image t ON (p.pid=t.pid AND t.StoreID=p.StoreID) ".
					" LEFT JOIN {$this->table}bu_detail bu ON bu.StoreID = p.StoreID ".
					" WHERE IF(t.StoreID>0,t.attrib=0 and t.sort=0,1=1) and p.StoreID='$StoreID' and p.pid='$pid' ";
		}else{
			$query = "SELECT bu.*, bu.bu_desc as description, tpd.MainImg as Images, l.password  FROM {$this->table}bu_detail bu LEFT JOIN {$this->table}template_details tpd ON bu.StoreID = tpd.StoreID LEFT JOIN {$this->table}login l ON bu.StoreID = l.StoreID  WHERE bu.StoreID='$StoreID'";
		}
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);

		if ($result[0]['attribute'] == '5') {
			include_once(SOC_INCLUDE_PATH.'/class.FoodWine.php');
			$foodWine = new FoodWine();
			$result[0]['new_online_order_num'] = $foodWine->getOrderNum($StoreID, '0');
			$result[0]['new_online_book_num'] = $foodWine->getBookNum($StoreID, '0');
			$result[0]['subscribers_num'] = $foodWine->getSubscribersNum($StoreID);
		}

		$result[0]['website_name'] = clean_url_name($result[0]['bu_urlstring']);
		$result[0]['bu_name'] = str_replace("\"","&quot;",$result[0]['bu_name']);
		$result[0]['foodwine_type'] = getFoodWineType();
		return $result[0];
	}

	/**
	 * generate the array for payment method selection
	 * @param
	 * @return array of variable
	 */
	function selectPayment(){
		$arrResult = array();

		$arrResult['StoreID'] = $_REQUEST['StoreID'];
		$arrResult['pid'] = $_REQUEST['pid'];
		// check seller's paypal account
		$sql = "select p.*,t.attrib,t.sort,t.picture from ".$this->table."product p left join ".$this->table."image as t on p.pid=t.pid where  p.pid=".$arrResult['pid'] ." and p.StoreID='$arrResult[StoreID]' order by t.attrib, t.sort";
		$this->dbcon->execute_query($sql);
		$info = $this->dbcon->fetch_records();
		if (is_array($info)) {
			$arrResult = $info[0];
			$arrResult['ref_id'] = $_REQUEST['refid'];
			$arrResult['image_name']=$arrResult['picture'];
			if (!file_exists('./'.$arrResult['image_name'])){
				$arrResult['image_name'] = 'images/243x212.jpg';
			}
			$arrResult['item_name'] = $info[0]['item_name'];
			$arrResult['deliveryMethod'] = $info[0]['deliveryMethod'];
			$arrResult['stockQuantity'] = $info[0]['stockQuantity'];
			$postage = explode('|',$info[0]['postage']);
			$arrResult['total'] = $info[0]['price']+$postage[0];
			$sql = "select bu_paypal, google_merchantid, google_merchantkey, bt_account_name, bt_BSB, bt_account_num, bt_instruction from ".$this->table."bu_detail where StoreID='".$arrResult['StoreID']."'";
			$this->dbcon->execute_query($sql);
			$paymentInfo = $this->dbcon->fetch_records();
			$bu_paypal = $paymentInfo[0]['bu_paypal'];
			$google_merchantid = $paymentInfo[0]['google_merchantid'];
			$google_merchantkey = $paymentInfo[0]['google_merchantkey'];
			$bt_account_name = $paymentInfo[0]['bt_account_name'];
			$bt_bsb = $paymentInfo[0]['bt_BSB'];
			$bt_account_num = $paymentInfo[0]['bt_account_num'];
			$bt_instruction = $paymentInfo[0]['bt_instruction'];

			$payments = unserialize($info[0]['payments']);

			$arrResult['credit_card'] 		= 	'disabled';
			$arrResult['check_payment'] 	= 	'disabled';
			$arrResult['paypal_enable'] 	= 	'disabled';
			$arrResult['cash_payment'] 		= 	'disabled';
			$arrResult['bank_transfer'] 	= 	'disabled';
			$arrResult['other_payment'] 	= 	'disabled';
			$arrResult['googlecheckout'] 	= 	'disabled';
            $arrResult['cod'] 				= 	'disabled';
            $arrResult['cash_on_pickup'] 	= 	'disabled';
            $arrResult['eftpos'] 			= 	'disabled';

			if(is_array($payments)){
				foreach($payments as $val){
					if ($val=='1'){
						$arrResult['cash_payment']='';
					}elseif ($val=='2'){
						$arrResult['credit_card']='';
					}elseif($val == '4'){
						$arrResult['check_payment']='';
					}elseif($val== '3' && !empty($bu_paypal)){
						$arrResult['paypal_enable'] = '';
					}elseif($val == '5'){
						$arrResult['bank_transfer']='';
						$arrResult['isbtinfo'] = 1;
						$arrResult['btinfo']= array(
							'bt_account_name' 	=> 	$bt_account_name,
							'bt_BSB' 			=> 	$bt_bsb,
							'bt_account_num' 	=> 	$bt_account_num,
							'bt_instruction' 	=> 	$bt_instruction,
						);
					}elseif($val== '6'){
						$arrResult['other_payment'] = '';
					}elseif ($val=='7' && !empty($google_merchantid) && !empty($google_merchantkey)) {
						$arrResult['googlecheckout'] = '';
					}elseif($val == '9') {
                        $arrResult['cod'] = '';
                    }elseif($val == '10') {
                        $arrResult['cash_on_pickup'] = '';
                    }elseif($val == '11') {
                        $arrResult['eftpos'] = '';
                    }
				}
			}
			$arrResult['paypal'] = $bu_paypal;
			//echo "refid:".$arrResult['ref_id'];
			if ($arrResult['ref_id']){
				$postage = explode('|',$info[0]['postage']);
				$sql = "select price from ".$this->table."order_reviewref where ref_id=".$arrResult['ref_id'];
				$this->dbcon->execute_query($sql);
				$info = $this->dbcon->fetch_records();
				$arrResult['price'] = $info[0]['price'];
				$arrResult['total'] = $info[0]['price']+$postage[0];
				$arrResult['isbid'] = "1";
			}
		}else{
			$arrResult['display'] = 'error';
			$arrResult['msg']	  = $this->replaceLangVar($this->lang['pub_clew']['notexist'],array('Product'));
		}

		return $arrResult;

	}

	// *================================================================
	// * This funciton display webside of store.
	// * end ====
	// *================================================================
	function orderSendMail($pid,$buyerid,$StoreID,$reviewKey,$item_name,$price,$quantity,$shippingCost,$product_code="",$payment='mo') {
		$paymethod = array('mo'=>'Other','paypal'=>'PayPal','googlecheckout'=>'Google Checkout','check'=>'Check','cash'=>'Cash','bank_transfer'=>'Bank Transfer', 'cod'=>'COD', 'cash_on_pickup'=>'Cash on Pickup', 'credit_card'=>'Credit Card', 'eftpos'=>'Eftpos');
		$productLink = 'http://'.$_SERVER['HTTP_HOST'].'/soc.php?cp=dispro&StoreID='.$StoreID.'&proid='.$pid;

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= 'From: noreply@'.EMAIL_DOMAIN . "\r\n";

		//send mail to seller
		//get seller's info
		$_query = "SELECT t1.bu_nickname,t1.*, t1.bu_name, t2.user FROM ".$this->table."bu_detail t1, ".$this->table."login t2 WHERE t1.StoreID=".$StoreID." AND t1.StoreID=t2.StoreID";
		$this->dbcon->execute_query($_query);
		$seller = $this->dbcon->fetch_records(true);
		$seller_nickname = $seller[0]['bu_nickname'];
		$seller_name = $seller[0]['bu_name'];
		$seller_email	= $seller[0]['user'];
		$seller_phone = $seller[0]['bu_phone'];
		if($payment=="bank_transfer"){
			$seller_bt_name = $seller[0]['bt_account_name'];
			$seller_bsb = $seller[0]['bt_BSB'];
			$seller_act_num = $seller[0]['bt_account_num'];
			$seller_btinstruct = $seller[0]['bt_instruction'];
		}

		//get buyer's info
		$_query = "SELECT t1.bu_nickname,t1.bu_phone, t2.user FROM ".$this->table."bu_detail t1, ".$this->table."login t2 WHERE t1.StoreID=".$buyerid." AND t1.StoreID=t2.StoreID";
		$this->dbcon->execute_query($_query);
		$buyer = $this->dbcon->fetch_records(true);
		$buyer_nickname = $buyer[0]['bu_nickname'];
		$buyer_email	= $buyer[0]['user'];
		$buyer_phone	= $buyer[0]['bu_phone'];

		$reviewUrl = 'http://'.$_SERVER['HTTP_HOST'].'/soc.php?cp=oreview&StoreID='.$buyerid.'&pid='.$pid.'&level=1bp3a&reviewkey='.$reviewKey;

		$_query = "SELECT * from {$this->table}product where pid='{$pid}'";
		$this->dbcon->execute_query($_query);
		$proinfo = $this->dbcon->fetch_records(true);

		$subject 	= 'FoodMarketplace Purchase Order';
		$totalCost	= number_format(($price * $quantity + $shippingCost),2,'.',',');
		$arrParams 	= array(
		'accept'			=>	'seller',
		'subject'			=>	$subject,
		'buyer_nickname'	=>	$buyer_nickname,
		'buyer_email'		=>	$buyer_email,
		'buyer_phone'		=>	$buyer_phone,
		'seller_nickname'	=>	$seller_nickname,
		'seller_name'		=>	$seller_name,
		'seller_email'		=>  $seller_email,
		'seller_phone'		=>  $seller_phone,
		'productLink'		=>	$productLink,
		'item_name'			=>	$item_name,
		'cost'				=>	number_format($price,2,'.',','),
		'quantity'			=>	$quantity,
		'shippingCost'		=>	$shippingCost,
		'totalCost'			=>	$totalCost,
		'reviewUrl'			=>	$reviewUrl,
		'reviewKey'			=>  $reviewKey,
		'product_code'		=>	$product_code,
		'payment_method'	=>	$paymethod[$payment],
		'isattachment'		=>  $proinfo[0]['isattachment'],
		);
		$arrParams['contactUrl'] = 'http://'.$_SERVER['HTTP_HOST'].'/soc.php?cp=sendmail&StoreID='.$buyerid.'&buyer='.base64_encode($StoreID)."&title=".urlencode("RE:".$subject);

		$this-> smarty -> assign('req', $arrParams);
		$message =	$this -> smarty -> fetch('email_order.tpl');
		@mail($seller_email, $subject, getEmailTemplate($message), fixEOL($headers));
		$query = "INSERT INTO `". $this->table ."message` (`subject`, `message`, `StoreID`, `date`,`emailaddress`,`fromtoname`)VALUES('$subject','".addslashes($message)."','$StoreID','".time()."','SYSTEM','SYSTEM') ";
		$this->dbcon->execute_query($query);
		//send mail to buyer
		$reviewUrl 	= 'http://'.$_SERVER['HTTP_HOST'].'/soc.php?cp=oreview&StoreID='.$buyerid.'&pid='.$pid.'&level=2xd3t&reviewkey='.$reviewKey;
		$subject 	= 'The Item(s) You Purchased';

		$arrParams['accept'] 	= 'buyer';
		$arrParams['subject'] 	= $subject;
		$arrParams['reviewUrl'] = $reviewUrl;
		if($payment=="bank_transfer"){
			$arrParams['isbtinfo'] = 1;
			$arrParams['bt_name'] = $seller_bt_name;
			$arrParams['bsb'] = $seller_bsb;
			$arrParams['act_num'] = $seller_act_num;
			$arrParams['btinstruct'] = $seller_btinstruct;
		}

		$arrParams['contactUrl'] = 'http://'.$_SERVER['HTTP_HOST'].'/soc.php?cp=sendmail&StoreID='.$StoreID.'&buyer='.base64_encode($buyerid)."&title=".urlencode("RE:".$subject);

		$this-> smarty ->clear_all_assign();
		$this-> smarty -> assign('req', $arrParams);
		$message =	$this -> smarty -> fetch('email_order.tpl');
		@mail($buyer_email, $subject, getEmailTemplate($message), fixEOL($headers));
		$query = "SELECT * FROM {$this->table}login where StoreID='{$buyerid}'";
		$this->dbcon->execute_query($query);
		$buyerinfo = $this->dbcon->fetch_records(true);
		if($buyerinfo[0]['attribute']!='4'){
			$query = "INSERT INTO `". $this->table ."message` (`subject`, `message`, `StoreID`, `date`,`emailaddress`,`fromtoname`)VALUES('$subject','".addslashes($message)."','$buyerid','".time()."','SYSTEM','SYSTEM') ";
			$this->dbcon->execute_query($query);
		}
	}

	function downSendMail($pid,$buyerid,$StoreID,$downkey,$price,$payment='mo') {
		$paymethod = array('mo'=>'Other','paypal'=>'PayPal','googlecheckout'=>'Google Checkout','check'=>'Check','cash'=>'Cash','bank_transfer'=>'Bank Transfer');
		$productLink = 'http://'.$_SERVER['HTTP_HOST'].'/soc.php?cp=dispro&StoreID='.$StoreID.'&proid='.$pid;

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= 'From: info@'.EMAIL_DOMAIN . "\r\n";

		//send mail to seller
		//get seller's info
		$_query = "SELECT t1.bu_nickname, t1.bu_name, t2.user FROM ".$this->table."bu_detail t1, ".$this->table."login t2 WHERE t1.StoreID=".$StoreID." AND t1.StoreID=t2.StoreID";
		$this->dbcon->execute_query($_query);
		$seller = $this->dbcon->fetch_records(true);
		$seller_nickname = $seller[0]['bu_nickname'];
		$seller_name = $seller[0]['bu_name'];
		$seller_email	= $seller[0]['user'];
		//get buyer's info
		$_query = "SELECT t1.bu_nickname, t2.user FROM ".$this->table."bu_detail t1, ".$this->table."login t2 WHERE t1.StoreID=".$buyerid." AND t1.StoreID=t2.StoreID";
		$this->dbcon->execute_query($_query);
		$buyer = $this->dbcon->fetch_records(true);
		$buyer_nickname = $buyer[0]['bu_nickname'];
		$buyer_email	= $buyer[0]['user'];

		$reviewKey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
		$reviewUrl = 'http://'.$_SERVER['HTTP_HOST'].'/downloadatt.php?keys='.urlencode(base64_encode($downkey)).'&rev='.$reviewKey;

		$_query = "SELECT * from {$this->table}product where pid='{$pid}'";
		$this->dbcon->execute_query($_query);
		$proinfo = $this->dbcon->fetch_records(true);
		$item_name = $proinfo[0]['item_name'];
		$product_code = $proinfo[0]['p_code'];
		$subject 	= 'SOCExchange Purchase Finisned';
		$arrParams 	= array(
		'accept'			=>	'buydown',
		'subject'			=>	$subject,
		'seller_nickname'	=>	$seller_nickname,
		'buyer_nickname'	=>	$buyer_nickname,
		'seller_name'		=>	$seller_name,
		'productLink'		=>	$productLink,
		'item_name'			=>	$item_name,
		'cost'				=>	number_format($price,2,'.',','),
		'totalCost'			=>	number_format($price,2,'.',','),
		'buyer_email'		=>	$buyer_email,
		'reviewUrl'			=>	$reviewUrl,
		'product_code'		=>	$product_code,
		'payment_method'	=>	$paymethod[$payment],
		'isattachment'		=>  $proinfo[0]['isattachment'],
		);
		$this-> smarty -> assign('req', $arrParams);

		$message =	$this -> smarty -> fetch('email_order.tpl');
		@mail($seller_email, $subject, $message, $headers);
		$query = "SELECT * FROM {$this->table}login where StoreID='{$buyerid}'";
		$this->dbcon->execute_query($query);
		$buyerinfo = $this->dbcon->fetch_records(true);
		if($buyerinfo['attribute']!='4'){
			$query = "INSERT INTO `". $this->table ."message` (`subject`, `message`, `StoreID`, `date`,`emailaddress`,`fromtoname`)VALUES('$subject','".addslashes($message)."','$StoreID','".time()."','SYSTEM','SYSTEM') ";
			$this->dbcon->execute_query($query);
		}
	}

	function creditSendMail($orderId) {
		global $email_regards;
		//get order info
		$_query = "SELECT t1.*, t2.reviewkey, t2.StoreID, t3.item_name, t3.postage, t3.price FROM ". $this->table ."order_detail t1, ";
		$_query .= $this->table ."order_reviewref t2, ";
		$_query .= $this->table ."product t3 WHERE t1.OrderID=".$orderId." AND t1.OrderID=t2.OrderID AND t1.pid=t3.pid";
		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);
		$orderInfo 	= $result[0];

		$StoreID	= $orderInfo['StoreID'];
		$buyerId	= $orderInfo['buyer_id'];
		$pid		= $orderInfo['pid'];
		$reviewKey 	= $orderInfo['reviewkey'];
		$itemName	= $orderInfo['item_name'];
		$amount		= $orderInfo['amount'];
		$quantity	= $orderInfo['quantity'];
		$shippingCost = $orderInfo['postage'];
		$price = $orderInfo['price'];

		$productLink = 'http://'.$_SERVER['HTTP_HOST'].'/soc.php?cp=dispro&StoreID='.$StoreID.'&proid='.$pid;

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= 'From: info@'.EMAIL_DOMAIN . "\r\n";

		//send mail to seller
		//get seller's info
		$_query = "SELECT t1.bu_nickname, t2.user FROM ".$this->table."bu_detail t1, ".$this->table."login t2 WHERE t1.StoreID=".$StoreID." AND t1.StoreID=t2.StoreID";
		$this->dbcon->execute_query($_query);
		$seller = $this->dbcon->fetch_records(true);
		$seller_nickname = $seller[0]['bu_nickname'];
		$seller_email	= $seller[0]['user'];
		//get buyer's info
		$_query = "SELECT t1.bu_nickname, t2.user FROM ".$this->table."bu_detail t1, ".$this->table."login t2 WHERE t1.StoreID=".$buyerId." AND t1.StoreID=t2.StoreID";
		$this->dbcon->execute_query($_query);
		$buyer = $this->dbcon->fetch_records(true);
		$buyer_nickname = $buyer[0]['bu_nickname'];
		$buyer_email	= $buyer[0]['user'];

		$subject = 'SOCExchange Purchase Order';
		$cardType = array(
		'c1'=>'VISA',
		'c2'=>'MasterCard',
		'c3'=>'AMEX',
		'c4'=>'Discover'
		);
		$arrParams = array(
		'subject'			=>	$subject,
		'seller_nickname'	=>	$seller_nickname,
		'buyer_nickname'	=>	$buyer_nickname,
		'productLink'		=>	$productLink,
		'itemName'			=>	$itemName,
		'price'				=>	$price,
		'quantity'			=>	$quantity,
		'shippingCost'		=>	$shippingCost,
		'totalCost'			=>	$quantity * $price + $shippingCost,
		'buyer_email'		=>	$buyer_email,
		'firstName'			=>	"$orderInfo[firstName]",
		'lastName'			=>	"$orderInfo[lastName]",
		'cardType'			=>	$cardType['c'.$orderInfo['cardType']],
		'cardNumber'		=>	"$orderInfo[cardNumber]",
		'expMonth'			=>	"$orderInfo[expMonth]",
		'expYear'			=>	"$orderInfo[expYear]",
		'address1'			=>	"$orderInfo[address1]",
		'address2'			=>	"$orderInfo[address2]",
		'city'				=>	"$orderInfo[city]",
		'state'				=>	"$orderInfo[state]",
		'postcode'			=>	"$orderInfo[postcode]",
		'emailAddr'			=>	"$orderInfo[emailAddr]",
		'phone'				=>	"$orderInfo[phone]",
		'productLink'		=>	"$productLink",
		'email_regards'		=>	"$email_regards"
		);

		$this-> smarty -> assign('req', $arrParams);
		$seller_message =	$this -> smarty -> fetch('email_credit.tpl');

		@mail($seller_email, $subject, $seller_message, $headers);
	}

	/**
	 * save order and payment info then send confirm email
	 * @param
	 * @return message of operation
	 */
	function creditPayment(){
		$boolResult	=	false;

		$_var = $this -> setFormInuptVar();
		extract($_var);

		if (empty($firstName)) {
			$msg	= "First Name is required. ";
		}else{ //regeidt
			$arrSetting = array(
			'buyer_id'		=>	$_SESSION['ShopID'],
			'pid'			=>	"$pid",
			'quantity'		=>	"$quantity",
			'firstName'		=>	"$firstName",
			'lastName'		=>	"$lastName",
			'cardType'		=>	"$cardType",
			'cardNumber'	=>	"$cardNumber",
			'expMonth'		=>	"$month",
			'expYear'		=>	"$year",
			'address1'		=>	"$address1",
			'address2'		=>	"$address2",
			'city'			=>	"$city",
			'state'			=>	"$state",
			'postcode'		=>	"$postcode",
			'emailAddr'		=>	"$email",
			'phone'			=>	"$phone",
			'amount'		=>	"$amount",
			'OrderDate'		=>	date("Y-m-d H:i:s",time()),
			'delivery'		=>	"$postage"
			);

			if ($this->dbcon->insert_record($this->table."order_detail", $arrSetting)){
				$orderID = $this->dbcon->lastInsertId();
				$arrSetting = array('OrderID'=>$orderID);
				$this->dbcon->update_record($this->table."order_reviewref", $arrSetting, 'where ref_id='.$ref_id);
				$this->creditSendMail($orderID);
				$msg = "THANK YOU. Your order has been sent. Please check your email for order details. Thank you for shopping at SOC Exchange.";
			}else{
				$msg = "Operation failed.";
			}
		}
		return $msg;
	}
	// *================================================================
	// * This funciton display webside of store.
	// * end ====
	// *================================================================

	function getAveRating($StoreID) {

		$aveRating	= 0;
		//get review count
		$_query = "SELECT COUNT(review_id) AS ratingCount, SUM(rating) AS ratingSum FROM ".$this->table."review WHERE StoreID=".$StoreID." AND upid=0";
		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);
		$ratingCount = $result[0][ratingCount];
		$ratingSum = $result[0][ratingSum];

		if ($ratingCount > 0) {
			$aveRating = round(($ratingSum / $ratingCount), 1);
			$number = split('\.', $aveRating);
			if ($number[1] < 5) {
				$aveRating = $number[0];
			} elseif ($number[1] > 5) {
				$aveRating = $number[0] +1;
			}
		}
		return $aveRating;
	}
	// *================================================================
	// * This funciton display webside of store.
	// * end ====
	// *================================================================

	function paypalForm($ref_id) {

		$paypalInfo = $this->getPaypalInfo();

		$form = '<html><body onLoad="document.paypal.submit()">';
		$form.= '<form action="'.$paypalInfo['paypal_url'].'" method="post"  name="paypal" id="paypal">';
		$form.= '<input type="hidden" name="cmd" value="_xclick">';
		$form.= '<input type="hidden" name="business" value="'.$_REQUEST['business'].'">';
		$form.= '<input type="hidden" name="item_name" value="'.$_REQUEST['item_name'].'">';
		$form.= '<input type="hidden" name="shipping" value="'.($_REQUEST['quantity']*$_REQUEST['postage']).'">';
		$form.= '<input type="hidden" name="quantity" value="'.$_REQUEST['quantity'].'">';
		$form.= '<input type="hidden" name="amount" value="'.$_REQUEST['price'].'">';
		$form.= '<input type="hidden" name="currency_code" value="'.CURRENCYCODE.'">';
		$form.= '<input type="hidden" name="item_number" value="'.$_REQUEST['item_number'].'">';
		$form.= '<input type="hidden" name="StoreID" value="'.$_REQUEST['StoreID'].'"> ';
		$form.= '<input type="hidden" name="custom" value="'.$_REQUEST['StoreID'].','.$ref_id.','.$_SESSION['ShopID'].','.time().'"> ';
		$form.= '<input type="hidden" name="return" value="'.$paypalInfo['paypal_siteurl'].'/product_activate.php">';
		$form.= '<input type="hidden" name="cancel_return" value="'.$paypalInfo['paypal_siteurl'].'/product_activate.php">';
		$form.= '<input type="hidden" name="notify_url" value="'.$paypalInfo['paypal_siteurl'].'/product_activate.php">';
		$form.= '</form></body></html>';
		return $form;
	}

	function paypalFormProductFee($ref_id, $data) {

		$data['quantity'] = $data['quantity'] ? $data['quantity'] : 1;
		$paypalInfo = $this->getPaypalInfo();

		$form = '<html><body onLoad="document.paypal.submit()">';
		//$form = '<html><body>';
		$form.= '<p align="center">When payment is confirmed you will return to this page to continue with your listing.</p>';
		$form.= '<form action="'.$paypalInfo['paypal_url'].'" method="post"  name="paypal" id="paypal">';
		$form.= '<input type="hidden" name="cmd" value="_xclick">';
		$form.= '<input type="hidden" name="business" value="'.$paypalInfo['paypal_email'].'">';
		$form.= '<input type="hidden" name="item_name" value="'.$data['item_name'].'">';
		$form.= '<input type="hidden" name="shipping" value="0">';
		$form.= '<input type="hidden" name="quantity" value="'.$data['quantity'].'">';
		$form.= '<input type="hidden" name="amount" value="'.$data['price'].'">';
		$form.= '<input type="hidden" name="currency_code" value="'.CURRENCYCODE.'">';
		$form.= '<input type="hidden" name="item_number" value="'.$ref_id.'">';
		$form.= '<input type="hidden" name="StoreID" value="'.$data['StoreID'].'"> ';
		$form.= '<input type="hidden" name="attribute" value="'.$data['attribute'].'"> ';
		$form.= '<input type="hidden" name="product_feetype" value="'.$data['product_feetype'].'"> ';
		$form.= '<input type="hidden" name="custom" value="'.$data['StoreID'].','.$ref_id.','.$data['attribute'].','.time().'"> ';
		$form.= '<input type="hidden" name="return" value="'.$paypalInfo['paypal_siteurl'].'/product_fee_activate.php">';
		$form.= '<input type="hidden" name="cancel_return" value="'.$paypalInfo['paypal_siteurl'].'/product_fee_activate.php">';
		$form.= '<input type="hidden" name="notify_url" value="'.$paypalInfo['paypal_siteurl'].'/product_fee_activate.php">';
		$form.= '</form></body></html>';
		return $form;
	}

	// *================================================================
	// * This funciton display webside of store.
	// * end ====
	// *================================================================

	function paypalFormOrder($OrderID, $req) {		
		$paypalInfo = $this->getPaypalInfo();
		
		$quantity = (isset($_SESSION['couponInfo']['quantity']) ? $_SESSION['couponInfo']['quantity'] : 1);
		$postage = $req['postage'];
		
		//echo var_export($req);
		
		$paypal_details = array(
			'cmd' => '_xclick',
			'business' => $req['paypal'],//$paypalInfo['paypal_email'],
			'item_name' => $req['bu_name'].' Order Pay :'.$OrderID,
			'shipping' => ($quantity * $postage),
			'quantity' => $quantity,
			'amount' => $req['product_money'],
			'currency_code' => CURRENCYCODE,
			'item_number' => $OrderID,
			'StoreID' => $req['StoreID'],
			'custom' => $req['StoreID'].','.$OrderID.','.$_SESSION['ShopID'].','.time(),
			'return' => $paypalInfo['paypal_siteurl'].'order_activate.php',
			'cancel_return' => $paypalInfo['paypal_siteurl'].'order_activate.php',
			'notify_url' => $paypalInfo['paypal_siteurl'].'order_activate.php'
		);
		
		$post_string = http_build_query($paypal_details);
		
		//echo var_export($paypal_details);
		header('Location: '.$paypalInfo['paypal_url'].'?' . $post_string);
	}

	/**
	 * Gets state list for search form
	 * @param null
	 * @return array
	 */
	function getStatesList($search_type = 'store') {
		$_query = "SELECT id, `stateName`, `description` \n".
		"FROM `". $this->table ."state` \n".
		"ORDER BY `description` ASC";

		$this->dbcon->execute_query($_query);
		$statesList = $this->dbcon->fetch_records(true);

		if ($search_type != '' && $search_type != 'store' && is_array($statesList)) {
			array_unshift($statesList, array('id'=>'-1','stateName'=>'-1','description'=>'All'));
		}
		return $statesList;
	}

	/**
	 * Initial item title
	 */
	function getTextItemTitle($text, $style=1,$bgcolor='', $backURL='', $show_basket=true) {
		$backURL = $backURL ? $backURL : 'javascript:history.go(-1)';
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$store_info = $this->getStoreInfo($StoreID);
		$foodwine_type = getFoodWineType($store_info['subAttrib']);
		$show_basket = ($store_info['attribute'] == 5 && $store_info['is_popularize_store'] == 0 && $store_info['sold_status'] && $foodwine_type=='food') ? $show_basket : false;
		if ($show_basket) {
			$basket_url = '<a href="/foodwine/?act=basket" class="show-basket" style="float:right">Show basket</a>';
			$backURL = $text == 'Payment' ? '/foodwine/?act=basket' : $backURL;
		}
		switch ($style) {
			case 1:
				return '<h1 class="itemTitle">'.$text.'</h1>';
				break;
			case 2:
				return '<h2 class="adminTitle">'.$text.'</h2>';
				break;
			case 3:
				return '<h2 class="adminTitle">'.$text.'&nbsp;<a href="javascript:history.go(-1)">Back</a></h2>';
				break;
			case 4:
				return '<h2 class="adminTitle" style="background:#'.$bgcolor.'">'.$text.'&nbsp;<a '.($show_basket ? 'style="margin-right:80px;"' : '').' href="'.$backURL.'">Back</a>&nbsp;'.$basket_url.'</h2>';
				break;

			case 5:
				return '<div id="content_cms"><span id="titles">'.$text.'&nbsp;</span>&nbsp;<a href="javascript:history.go(-1)">&lt;&lt; Back</a></div><br />';
				break;

			case 6:
				return '<div id="content_cmslong"><span id="titles"><h1 style="font-size:14px;font-weight:400;color:#FFFFFF;margin:0px;">'.$text.'&nbsp;</h1> </span>&nbsp;<a href="javascript:history.go(-1)">&lt;&lt; Back</a></div><br />';
				break;

			case 7:
				return '<h2 class="adminTitle" style="background:#'.$bgcolor.'">'.$text.'<a href="'.$backURL.'">Back</a></h2>';
				break;
			case 8:
				return '<h2 class="adminTitle">'.$text.'&nbsp;<a href="'.$backURL.'">Back</a></h2>';
				break;
			default:
				break;
		}
	}

	/**
	 * Search by product name
	 * @param string $prodcut_name
	 * @return array product list
	 */
	function searchByProductName($product_name) {

		$arrTemp = array();
		$arrTemp['counter'] = 0;

		$pageSize = PAGESIZE;

		$_query = "SELECT count(*) AS Counter \n".
		"FROM `". $this->table ."product` AS `product` \n".
		"LEFT JOIN `". $this->table ."bu_detail` AS `detail` ON detail.StoreID = product.StoreID \n".
		" left join ".$this->table."login as lg on lg.StoreID = detail.StoreID ".
		"WHERE  (product.item_name LIKE '%".$product_name."%' OR product.description LIKE '%".$product_name."%')  \n".
		"AND detail.CustomerType = 'seller' \n".
		//"AND detail.renewalDate > ".time()." \n".
		"AND lg.suspend=0 \n".
		"AND product.Deleted = '' \n".
		"AND NOT(detail.bu_name IS NULL)";

		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);
		$totleCount = $result[0]['Counter'];

		$pager = new Page($totleCount, $pageSize, true, 'pageId');

		$_query	=	"SELECT '' AS website_name, '' AS img_link, product.description, product.StoreID, ".
		" product.item_name, product.url_item_name, product.price, product.unit,product.on_sale,".
		" product.image_name,state.stateName as state_name, t2.smallPicture, t2.picture".
		" ,detail.bu_state, detail.bu_suburb, detail.bu_name,detail.bu_urlstring, product.is_auction ".
		" FROM ".$this->table."product as product " .
		" left join ".$this->table."bu_detail as detail on detail.StoreID=product.StoreID ".
		" left join ".$this->table."login as lg on lg.StoreID = detail.StoreID ".
		" left join ".$this->table."state as state on detail.bu_state=state.id ".
		" left join ".$this->table."image as t2 on product.pid=t2.pid ".
		" WHERE (product.item_name LIKE '%".$product_name."%' OR product.description LIKE '%".$product_name."%')  and detail.CustomerType = 'seller' ".
		//" AND detail.renewalDate > ".time().
		" AND lg.suspend=0 ".
		" AND product.Deleted = '' and not (detail.bu_name is null) AND IF(t2.StoreID>0, product.StoreID=t2.StoreID and t2.attrib=0 and t2.sort=0,1=1) ".$pager->get_limit();

		$this->dbcon->execute_query($_query);
		$products = $this->dbcon->fetch_records(true);
		if (!empty($products)) {
			$objUI = new uploadImages();
			foreach ($products as $product) {
				$product['website_name'] = clean_url_name($product['bu_urlstring']);
				$product['description'] = strip_tags($product['description']);
				if (empty($product['image_name'])){
					$product['image_name'] = 'images/79x79.jpg';
				}
				$product['simage']	=	$objUI -> getDefaultImage($product['smallPicture'], true, 0, 0, 4);
				$product['bimage']	=	$objUI -> getDefaultImage($product['picture'],false,0,0,9);
				$product['limage']	=	$objUI -> getDefaultImage($product['picture'],false,0,0,15);

				$product['img_link'] = '<img src="'.$product['simage']['text'].'" alt="'.$product['bu_name'].'" title="'.$product['bu_name'].'" width="140" height="140" border="0" />';
				$arrTemp['products'][] = $product;
			}
			unset($objUI);
			$arrTemp['linkStr'] = $pager->get_link('soc.php?cp=statepage&product_name='.$product_name, $pageSize);
			$arrTemp['counter'] = $totleCount;
		}
		if(get_magic_quotes_gpc()){
			$arrTemp['product_name'] = stripslashes($product_name);
		}else{
			$arrTemp['product_name'] = $product_name;
		}

		return $arrTemp;
	}
	
	
	
	function searchAuction($parameters) {
		//ini_set('display_errors', 1);
		$arrTemp = array();
		$arrTemp['counter'] = 0;
		
		if (is_array($parameters)) {
		
			if (isset($parameters['state_name'])) {
				$query = "SELECT stateName FROM ". $this->table ."state ORDER BY stateName ASC";
				$this->dbcon->execute_query($query);
				$rows = $this->dbcon->fetch_records();
				$states = array();
				foreach ($rows as $row) {
					$states[] = array (
						'state'    => $row['stateName'],
						'selected' => $parameters['state_name'] == $row['stateName'] ? ' selected="selected"' : ''
					);
				}
			}
			
			$postcode = '';
			if (isset($parameters['suburb'])) {
				$suburb = explode('.', $parameters['suburb']);
				$suburb = $suburb[0];
				$cities = getSuburbArray($parameters['state_name'], $suburb);
				$distance = array( 3, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300);
				if (isset($parameters['postcode'])) {
					$postcode = $parameters['postcode'];
				} else {
					$arrPostcode = explode('.', $parameters['suburb']);
					$postcode =	$arrPostcode[1];
				}
			}
		
			$where_sql = '';
			$key_words = '';
			if (isset($parameters['product_name'])) {
				$where_sql .= " and (product.item_name LIKE '%{$parameters['product_name']}%' OR product.description LIKE '%{$parameters['product_name']}%' ";
				$key_words .= (empty($key_words)?'':' + ') . " '{$parameters['product_name']}' ";
				$where_sql .= " ) ";
			}
			if (isset($parameters['business_name'])) {
				$where_sql .= " and detail.bu_name like '%{$parameters['business_name']}%' ";
				$key_words .= (empty($key_words)?'':' + ') . " '" . $parameters['business_name'] . "' ";
			}
			if (isset($parameters['category'])) {
				$where_sql .= " and CA.fid = '{$parameters['category']}' ";
				$key_words .= (empty($key_words)?'':' + ') . " '" . $this->getCategoryByName($parameters['category']) . "' ";
			}
			if (isset($parameters['subcategory'])) {
				$where_sql .= " and product.category = '{$parameters['subcategory']}' ";
				$key_words .= (empty($key_words)?'':' + ') . " '" . $this->getCategoryByName($parameters['subcategory']) . "' ";
			}
			if (isset($parameters['price_min'])) {
				$where_sql .= " and product.price >= {$parameters['price_min']} ";
				$key_words .= (empty($key_words)?'':' + ') . " '&gt;={$parameters['price_min']}' ";
			}
			if (isset($parameters['price_max'])) {
				$where_sql .= " and product.price <= {$parameters['price_max']} ";
				$key_words .= (empty($key_words)?'':' + ') . " '&lt;={$parameters['price_max']}' ";
			}
			if (isset($parameters['state'])) {
				$where_sql .= " and state.stateName = '{$parameters['state']}' ";
				$key_words .= (empty($key_words)?'':' + ') . " '".$parameters['state']."' ";
			}

			if ($postcode != '') {
				$where_sql .= " and detail.bu_postcode ".getRadiusSqlString($postcode, (isset($parameters['distance']) ? $parameters['distance'] : 25), 'AUS');
			}
			
			if ($parameters['auctions_type']){
				$value = (($parameters['auctions_type']) ? 'yes' : 'no');
				$wheresql .= " and product.is_auction in({$value}) ";
			}
			
			$_query = "SELECT count(DISTINCT(product.pid)) AS Counter ".
						"FROM ".$this->table."product AS product ".
						"LEFT JOIN ".$this->table."bu_detail AS detail ON detail.StoreID = product.StoreID ".
						"LEFT JOIN ".$this->table."login as lg on lg.StoreID = detail.StoreID ".
						"LEFT JOIN ".$this->table."state AS state ON detail.bu_state = state.id ".
						"LEFT JOIN ".$this->table."product_auction  as au on au.pid=product.pid ".
						"LEFT JOIN ".$this->table."product_category AS CA ON product.category = CA.id ".
						"LEFT JOIN ".$this->table."image as t2 on product.pid=t2.pid ".
						"WHERE 1=1  $where_sql  ".
						"AND detail.CustomerType = 'seller' ";
						"AND lg.suspend = 0 ".
						"AND detail.status = 1 ".
						"AND product.Deleted = '' ".
						//"AND product.image_name != '' ".
						"AND NOT(detail.bu_name IS NULL) ".
						"AND IF(product.is_auction='yes',au.starttime_stamp <=".time().",1=1) ".
						"AND IF(t2.StoreID>0, product.StoreID=t2.StoreID,1=1)";
						
			//echo $_query;
			//exit;
			$this->dbcon->execute_query($_query);
			$result = $this->dbcon->fetch_records(true);
			$total = $result[0]['Counter'];
			$pager = new Page($total, PAGESIZE, true, 'pageId');
			
			switch ($parameters['sort']){
				case '1':	$sortstr = " order by lg.status DESC, product.datec DESC ";	break;
				case '2':	$sortstr = " order by lg.status DESC, product.price ASC,product.datec DESC";	break;
				case '3':	$sortstr = " order by lg.status DESC, product.price DESC,product.datec DESC";	break;
				case '4':	$sortstr = " order by lg.status DESC, product.item_name ASC,product.datec DESC";break;
				case '5':	$sortstr = " order by lg.status DESC, detail.bu_name ASC,product.datec DESC";break;
				default:	$sortstr = " order by lg.status DESC, product.datec DESC ";	break;
			}
			
			
			$_query	= "SELECT '' AS website_name, '' AS img_link, product.description, product.StoreID, ".
				" product.pid, product.item_name, product.url_item_name, product.price, product.unit, product.on_sale,".
				" product.image_name, state.stateName as state_name, t2.smallPicture, t2.picture,".
				" detail.bu_state, detail.bu_suburb, detail.bu_name, detail.bu_urlstring, product.is_auction, au.cur_price, au.end_stamp, is_certified".
				" FROM ".$this->table."product as product " .
				" LEFT JOIN ".$this->table."bu_detail as detail on detail.StoreID=product.StoreID ".
				" LEFT JOIN ".$this->table."login as lg on lg.StoreID = detail.StoreID ".
				" LEFT JOIN ".$this->table."state as state on detail.bu_state=state.id ".
				" LEFT JOIN ".$this->table."product_auction  as au on au.pid=product.pid ".
				" LEFT JOIN ".$this->table."product_category AS CA ON product.category = CA.id \n".
				" LEFT JOIN ".$this->table."image as t2 on product.pid=t2.pid ".
				" WHERE 1=1 $where_sql and detail.CustomerType = 'seller' ".
				" AND lg.suspend = 0 ".
				" AND detail.status = 1 ".
				" AND product.Deleted = '' and not (detail.bu_name is null) ".
				//" AND product.image_name != '' ".
				" AND IF(product.is_auction='yes', au.starttime_stamp <=" . time() . ",1=1) ".
				" AND IF(t2.StoreID > 0, product.StoreID = t2.StoreID,1=1) group by product.pid $sortstr ".$pager->get_limit();
				"$sortstr ".$pager->get_limit();
				$this->dbcon->execute_query($_query);
				$products = $this->dbcon->fetch_records(true);
				
			if (!empty($products)) {
				$objUI = new uploadImages();
				foreach ($products as $product) {
					$product['website_name'] = clean_url_name($product['bu_urlstring']);
					$product['description'] = strip_tags($product['description']);
					$product['end_stamp'] = $product['end_stamp']-time()>0?$product['end_stamp']-time():0;
					if (empty($product['image_name'])) {
						$product['image_name'] = 'images/79x79.jpg';

					}
					$product['simage']	=	$objUI -> getDefaultImage($product['smallPicture'], true, 0, 0, 4);
					$product['bimage']	=	$objUI -> getDefaultImage($product['picture'],false,0,0,9);
					$product['limage']	=	$objUI -> getDefaultImage($product['picture'],false,0,0,15);

					if ($product['simage']['text']=="/images/79x79.jpg") {
						$product['img_link'] = '<img src="'.$product['simage']['text'].'" alt="'.$product['bu_name'].'" title="'.$product['bu_name'].'" width="140" height="140" border="0" />';
					} else {
						$product['img_link'] = '<div class="moreImg0_css"><a href="/'.$product['website_name'].'/'.$product['url_item_name'].'"><img src="'.$product['limage']['text'].'" alt="'.$product['bu_name'].'" title="'.$product['bu_name'].'" width="'.$product['limage']['width'].'" height="'.$product['limage']['height'].'" class="item" border="0" onmouseover="showmoreImage_fade(\'pid_'.$product['pid'].'\',true);" onmouseout="showmoreImage_fade(\'pid_'.$product['pid'].'\',false);"/></a><div id="pid_'.$product['pid'].'" class="moreImg_css"><img src="'.$product['bimage']['text'].'" style="width:'.$product['bimage']['width'].'px;height:'.$product['bimage']['height'].'px;"/></div><div id="pid_'.$product['pid'].'_2" class="moreImg_arror"></div></div>';
					}
					$arrTemp['products'][] = $product;
				}
				unset($objUI);
				if (get_magic_quotes_gpc()) {
					$parameters['business_name'] = stripslashes($parameters['business_name']);
					$parameters['product_name'] = stripslashes($parameters['product_name']);
				}
				$arrTemp['linkStr'] = $pager->get_link('soc.php?cp=searchpage&product_name='.$parameters['product_name']."&business_name=".$parameters['business_name']."&bcategory=".$parameters['category']."&bsubcategory=".$parameters['subcategory']."&price_min=".$parameters['price_min']."&issold=".$parameters['issold']."&price_max=".$parameters['price_max']."&sstate_name=".$parameters['state']."&buytype={$parameters['buytype']}", $pageSize);
				$arrTemp['counter'] = $total;
			}
			
			if (get_magic_quotes_gpc()) {
				$key_words = stripslashes($key_words);
			}
			
			$arrTemp['product_name'] = " keyword ".$key_words;
		}
		return $arrTemp;
	}


	/**
	 * buy & seller new search
	 */


	function searchProductsBycon($aryseting) {

		$arrTemp = array();
		$arrTemp['counter'] = 0;

		$pageSize = PAGESIZE;
		$wheresql = "";
		$keywords = "";
		include('class.producttag.php');
		$protag = new producttag();
		if(isset($aryseting['pro_tags'])){
			$tagproduct = $protag->get_pro_tags_ByTagKeyword($aryseting['pro_tags'],0);
			$wheresql .= " and product.pid in($tagproduct) ";
			$keywords .= "'{$aryseting['pro_tags']}'";
		}
		if(isset($aryseting['product_name'])){
			$wheresql .= " and (product.item_name LIKE '%{$aryseting['product_name']}%' OR product.description LIKE '%{$aryseting['product_name']}%' ";
			$keywords .= ($keywords==""?"":" + ")." '{$aryseting['product_name']}' ";

			$tagproduct = $protag->get_pro_tags_ByTagKeyword($aryseting['product_name'],0);
			if($tagproduct){
				$wheresql .= " OR product.pid in ($tagproduct)) ";
			}else{
				$wheresql .= ") ";
			}
		}
		if (!empty($aryseting['bu_postcode'])) {
			$wheresql .= " AND detail.bu_postcode='$aryseting[bu_postcode]'";
		}
		
		if(isset($aryseting['business_name'])&&$aryseting['business_name']!=""){
			$wheresql .= " and detail.bu_name like '%{$aryseting['business_name']}%' ";
			$keywords .= ($keywords==""?"":" + ")." '".$aryseting['business_name']."' ";
		}
		if(isset($aryseting['category'])&&$aryseting['category']!=""){
			$wheresql .= " and CA.fid = '{$aryseting['category']}' ";
			$keywords .= ($keywords==""?"":" + ")." '".$this->getCategoryByName($aryseting['category'])."' ";
		}
		if(isset($aryseting['subcategory'])&&$aryseting['subcategory']!=""){
			$wheresql .= " and product.category = '{$aryseting['subcategory']}' ";
			$keywords .= ($keywords==""?"":" + ")." '".$this->getCategoryByName($aryseting['subcategory'])."' ";
		}
		if(isset($aryseting['price_min'])){
			$wheresql .= " and product.price >= {$aryseting['price_min']} ";
			$keywords .= ($keywords==""?"":" + ")." '&gt;={$aryseting['price_min']}' ";
		}
		if(isset($aryseting['price_max'])){
			$wheresql .= " and product.price <= {$aryseting['price_max']} ";
			$keywords .= ($keywords==""?"":" + ")." '&lt;={$aryseting['price_max']}' ";
		}
		if(isset($aryseting['state'])&&$aryseting['state']!=""){
			$wheresql .= " and state.stateName = '{$aryseting['state']}' ";
			$keywords .= ($keywords==""?"":" + ")." '".$aryseting['state']."' ";
		}

		$tfls = 0;
		if(isset($aryseting['timelefts'])){
			$tfls = $aryseting['timelefts'];
		}
		$timenow = time();

		if(isset($aryseting['issold'])&&$aryseting['issold']!=1){
			$wheresql .= " and product.on_sale <> 'sold' AND IF(product.is_auction='yes',".($tfls>0?"au.end_stamp-$timenow<=$tfls and ":"")." au.end_stamp-$timenow>0,1=1) ";
		}else{
			$wheresql .= " AND IF(product.is_auction='yes',(".($tfls>0?"au.end_stamp-$timenow<=$tfls and ":"")." au.end_stamp-$timenow>0) ".($tfls==0?"or (au.cur_price>au.reserve_price and au.winner_id>0 and  au.end_stamp<$timenow)":"").",1=1) ";

			if(!isset($aryseting['pro_tags'])){
				$keywords .= ($keywords==""?"":" + ")." 'Show sold items' ";
			}
		}
		if(isset($aryseting['buytype'])){
			$wheresql .= " and product.is_auction in({$aryseting['buytype']}) ";
		}

		$_query = "SELECT count(DISTINCT(product.pid)) AS Counter \n".
		"FROM `". $this->table ."product` AS `product` \n".
		"LEFT JOIN `". $this->table ."bu_detail` AS `detail` ON detail.StoreID = product.StoreID \n".
		"LEFT JOIN `".$this->table."login` as lg on lg.StoreID = detail.StoreID \n".
		" left join ".$this->table."product_auction  as au on au.pid=product.pid ".
		"LEFT JOIN `". $this->table ."product_category` AS `CA` ON product.category = CA.id \n".
		"LEFT JOIN `". $this->table ."state` AS `state` ON detail.bu_state = state.id \n".
		" left join ".$this->table."image as t2 on product.pid=t2.pid ".
		"WHERE 1=1  $wheresql  \n".
		"AND detail.CustomerType = 'seller' \n".
		//"AND detail.renewalDate > ".time()." \n".
		"AND lg.suspend=0 \n".
		"AND product.Deleted = '' \n".
		"AND NOT(detail.bu_name IS NULL) \n".
                "AND IF(product.is_auction='yes',au.starttime_stamp <=".time().",1=1) ".
                "AND IF(t2.StoreID>0, product.StoreID=t2.StoreID,1=1)";
		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);
		$totleCount = $result[0]['Counter'];

		$pager = new Page($totleCount, $pageSize, true, 'pageId');
		switch ($aryseting['sort']){
			case '1':	$sortstr = " order by lg.status DESC, product.datec DESC ";	break;
			case '2':	$sortstr = " order by lg.status DESC, product.price ASC,product.datec DESC";	break;
			case '3':	$sortstr = " order by lg.status DESC, product.price DESC,product.datec DESC";	break;
			case '4':	$sortstr = " order by lg.status DESC, product.item_name ASC,product.datec DESC";break;
			case '5':	$sortstr = " order by lg.status DESC, detail.bu_name ASC,product.datec DESC";break;
			default:	$sortstr = " order by lg.status DESC, product.datec DESC ";	break;
		}

		$_query	=	"SELECT '' AS website_name, '' AS img_link, product.description, product.StoreID, ".
		" product.pid,product.item_name, product.url_item_name, product.price, product.unit,product.on_sale,".
		" product.image_name,state.stateName as state_name, t2.smallPicture, t2.picture".
		" ,detail.bu_state, detail.bu_suburb, detail.bu_name,detail.bu_urlstring, product.is_auction, au.cur_price,au.end_stamp,is_certified ".
		" FROM ".$this->table."product as product " .
		" left join ".$this->table."bu_detail as detail on detail.StoreID=product.StoreID ".
		" left join ".$this->table."login as lg on lg.StoreID = detail.StoreID ".
		" left join ".$this->table."state as state on detail.bu_state=state.id ".
		" left join ".$this->table."product_auction  as au on au.pid=product.pid ".
		" LEFT JOIN `". $this->table ."product_category` AS `CA` ON product.category = CA.id \n".
		" left join ".$this->table."image as t2 on product.pid=t2.pid ".
		" WHERE 1=1 $wheresql and detail.CustomerType = 'seller' ".
		//" AND detail.renewalDate > ".time().
		" AND lg.suspend=0 ".
		" AND product.Deleted = '' and not (detail.bu_name is null) ".
                "AND IF(product.is_auction='yes',au.starttime_stamp <=".time().",1=1) ".
		" AND IF(t2.StoreID>0, product.StoreID=t2.StoreID,1=1) group by product.pid $sortstr ".$pager->get_limit();
		$this->dbcon->execute_query($_query);
		$products = $this->dbcon->fetch_records(true);
		if (!empty($products)) {
			$objUI = new uploadImages();
			foreach ($products as $product) {
				$product['website_name'] = clean_url_name($product['bu_urlstring']);
				$product['description'] = strip_tags($product['description']);
				$product['end_stamp'] = $product['end_stamp']-time()>0?$product['end_stamp']-time():0;
				if (empty($product['image_name'])){
					$product['image_name'] = 'images/79x79.jpg';

				}
				$product['simage']	=	$objUI -> getDefaultImage($product['smallPicture'], true, 0, 0, 4);
				$product['bimage']	=	$objUI -> getDefaultImage($product['picture'],false,0,0,9);
				$product['limage']	=	$objUI -> getDefaultImage($product['picture'],false,0,0,15);

				if($product['simage']['text']=="/images/79x79.jpg"){
				$product['img_link'] = '<img src="'.$product['simage']['text'].'" alt="'.$product['bu_name'].'" title="'.$product['bu_name'].'" width="140" height="140" border="0" />';
				}else{
					$product['img_link'] = '<div class="moreImg0_css"><a href="/'.$product['website_name'].'/'.$product['url_item_name'].'"><img src="'.$product['limage']['text'].'" alt="'.$product['bu_name'].'" title="'.$product['bu_name'].'" width="'.$product['limage']['width'].'" height="'.$product['limage']['height'].'" class="item" border="0" onmouseover="showmoreImage_fade(\'pid_'.$product['pid'].'\',true);" onmouseout="showmoreImage_fade(\'pid_'.$product['pid'].'\',false);"/></a><div id="pid_'.$product['pid'].'" class="moreImg_css"><img src="'.$product['bimage']['text'].'" style="width:'.$product['bimage']['width'].'px;height:'.$product['bimage']['height'].'px;"/></div><div id="pid_'.$product['pid'].'_2" class="moreImg_arror"></div></div>';
				}

				$arrTemp['products'][] = $product;
			}
			unset($objUI);
			if(get_magic_quotes_gpc()){
				$aryseting['business_name'] = stripslashes($aryseting['business_name']);
				$aryseting['product_name'] = stripslashes($aryseting['product_name']);
			}
			$arrTemp['linkStr'] = $pager->get_link('soc.php?cp=statepage&product_name='.$aryseting['product_name']."&business_name=".$aryseting['business_name']."&bcategory=".$aryseting['category']."&bsubcategory=".$aryseting['subcategory']."&price_min=".$aryseting['price_min']."&issold=".$aryseting['issold']."&price_max=".$aryseting['price_max']."&sstate_name=".$aryseting['state']."&timelefts={$aryseting['timelefts']}&buytype={$aryseting['buytype']}&sort={$aryseting['sort']}", $pageSize);
			$arrTemp['counter'] = $totleCount;
		}
		if(get_magic_quotes_gpc()){
			$keywords = stripslashes($keywords);
		}
		if(isset($aryseting['pro_tags'])){
			$arrTemp['product_name'] = " tag ".$keywords;
		}else{
			$arrTemp['product_name'] = " keyword ".$keywords;
		}


		return $arrTemp;
	}


	/**
	 * Search by product name
	 * @param string $prodcut_name
	 * @return array product list
	 */
	function searchByBusinessName($business_name) {

		$arrTemp = array();
		$arrTemp['counter'] = 0;

		$pageSize = PAGESIZE;

		$_query	=	"SELECT count(*) as Counter FROM ".$this->table."bu_detail as bu_detail ".
		"left JOIN ".$this->table."login as lg on lg.StoreID= bu_detail.StoreID ".
                //"LEFT JOIN".$this->table."product_auction as au ON (bu_date)".
		" WHERE bu_detail.bu_name LIKE '%".$business_name."%' AND bu_detail.CustomerType ='seller' AND bu_detail.attribute=0 and lg.suspend=0";

		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);
		$totleCount = $result[0]['Counter'];

		$pager = new Page($totleCount, $pageSize, true, 'pageId');

		$_query	= "SELECT '' AS img_link, '' AS website_name, bu_detail.StoreID, "
		."bu_detail.bu_name,bu_detail.bu_urlstring, bu_detail.bu_suburb, template_details.MainImg, state.stateName as state_name "
		."FROM ".$this->table."bu_detail as bu_detail "
		."left JOIN ".$this->table."login as lg on lg.StoreID= bu_detail.StoreID "
		."LEFT JOIN ".$this->table."template_details as template_details ON bu_detail.StoreID = template_details.StoreID "
		."LEFT JOIN ".$this->table."state as state ON bu_detail.bu_state = state.id "
		."WHERE bu_detail.bu_name LIKE '%".$business_name."%' AND bu_detail.CustomerType ='seller' "
		//."(bu_detail.renewalDate>".time().") "
		."and bu_detail.attribute=0 and lg.suspend=0 ORDER BY lg.status DESC ".$pager->get_limit();

		$this->dbcon->execute_query($_query);
		$business = $this->dbcon->fetch_records(true);
		if (!empty($business)) {
			foreach ($business as $businessItem) {
				$businessItem['website_name'] = clean_url_name($businessItem['bu_urlstring']);
				if (empty($businessItem['MainImg'])){
					$businessItem['MainImg'] = 'images/79x79.jpg';
				}elseif (!file_exists(ROOT_PATH . $businessItem['MainImg'])){
					$businessItem['MainImg'] = 'images/79x79.jpg';
				}

				$businessItem['img_link'] = '<img src="'.$businessItem['MainImg'].'" alt="'.$businessItem['bu_name'].'" title="'.$businessItem['bu_name'].'" width="140" height="140" border="0" />';
				$arrTemp['business'][] = $businessItem;
			}
			$arrTemp['linkStr'] = $pager->get_link('soc.php?cp=statepage&business_name='.$business_name, $pageSize);
			$arrTemp['counter'] = $totleCount;
		}
		if(get_magic_quotes_gpc()){
			$arrTemp['business_name'] = stripslashes($business_name);
		}else{
			$arrTemp['business_name'] = $business_name;
		}

		return $arrTemp;
	}

	function getBusinessHome($sid) {

		$arrResult = array();

		if(!empty($_REQUEST['msgid'])) { $msgid = $_REQUEST['msgid'] ; }
		if(!empty($_REQUEST['action'])) { $action = $_REQUEST['action'] ; }
		$dateformat = str_replace('-','/',DATAFORMAT_DB);

		$arrResult['msg']	=	$_REQUEST['msg'];

		//seller info
		$_query = "select DATE_FORMAT(FROM_UNIXTIME(launch_date),'$dateformat') as launch_date,launch_date as luDate, DATE_FORMAT(FROM_UNIXTIME(renewalDate),'$dateformat') as renewalDate, DATE_FORMAT(FROM_UNIXTIME(product_renewal_date),'$dateformat') as productRenewalDate, IF(attribute=5,renewalDate,product_renewal_date) as reDate, attribute, product_feetype, israceold from ".$this-> table."bu_detail where StoreID='$sid'";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		if (is_array($arrTemp)){
			$dateformat2 = str_replace("-","/",str_replace("%","",DATAFORMAT_DB));
			$arrResult['detail']['launch_date'] = $arrTemp[0]['launch_date']=='01/01/1970'? '-': date($dateformat2,$arrTemp[0]['luDate']) ;
			$arrResult['detail']['renewalDate'] = $arrTemp[0]['renewalDate']=='01/01/1970'? '-': ($arrTemp[0]['reDate']>time()?date($dateformat2,$arrTemp[0]['reDate']):date($dateformat2,$arrTemp[0]['reDate']).' <font style="color:red;">Account Expired</font>') ;
			$arrResult['detail']['attribute']   = $arrTemp[0]['attribute'];
			$arrResult['detail']['product_feetype']   = $arrTemp[0]['product_feetype'];
			$arrResult['detail']['israceold']   = $arrTemp[0]['israceold'];
			$arrResult['detail']['productRenewalDate']   = $arrTemp[0]['productRenewalDate']=='01/01/1970'? '-': ($arrTemp[0]['reDate']>time()?date($dateformat2,$arrTemp[0]['reDate']):date($dateformat2,$arrTemp[0]['reDate']).' <font style="color:red;">Account Expired</font>') ;
			$arrResult['detail']['expireWarning'] = ($arrTemp[0]['reDate']-time() < 7776000 && $arrTemp[0]['reDate']-time() > 0)?'yes':'no';
		}

		$_query = "SELECT sum(`Jan` + `Feb` + `Mar` + `Apr` + `May` + `Jun` + `Jul` + `Aug` + `Sep` + `Oct` + `Nov` + `Dec`) AS counter FROM {$this->table}hit_store where StoreID='$sid'";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		$_query = "SELECT sum(`Jan` + `Feb` + `Mar` + `Apr` + `May` + `Jun` + `Jul` + `Aug` + `Sep` + `Oct` + `Nov` + `Dec`) AS counter FROM {$this->table}hit_store_product where StoreID='$sid'";
		$this->dbcon->execute_query($_query);
		$arrTemp2 = $this->dbcon->fetch_records(true);
		$arrResult['detail']['clickNumber'] = $arrTemp[0]['counter']+$arrTemp2[0]['counter'];


		$_query = "SELECT sum(`Jan` + `Feb` + `Mar` + `Apr` + `May` + `Jun` + `Jul` + `Aug` + `Sep` + `Oct` + `Nov` + `Dec`) AS counter FROM {$this->table}hit_wishlist where StoreID='$sid'";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		$_query = "SELECT sum(`Jan` + `Feb` + `Mar` + `Apr` + `May` + `Jun` + `Jul` + `Aug` + `Sep` + `Oct` + `Nov` + `Dec`) AS counter FROM {$this->table}hit_wishlist_product where StoreID='$sid'";
		$this->dbcon->execute_query($_query);
		$arrTemp2 = $this->dbcon->fetch_records(true);
		$arrResult['detail']['wishNumber'] = $arrTemp[0]['counter']+$arrTemp2[0]['counter'];



		//product info
		switch ($arrResult['detail']['attribute']){
			case 0:
				$arrResult['detail']['dir_name'] = '';
				$_query	= "select count(*) as num from ".$this->table."product where StoreID='$sid' and deleted != 'YES' ";
				break;
			case 1:
				$arrResult['detail']['dir_name'] = 'estate';
				$_query	= "select count(*) as num from ".$this->table."product_realestate where StoreID='$sid' and deleted=0";
				break;
			case 2:
				$arrResult['detail']['dir_name'] = 'auto';
				$_query	= "select count(*) as num from ".$this->table."product_automotive where StoreID='$sid' and deleted=0";
				break;
			case 5:
				$arrResult['detail']['dir_name'] = 'foodwine';
				$_query	= "select count(*) as num from ".$this->table."product_foodwine where StoreID='$sid' and deleted=0";
				break;

			default:
				$arrResult['detail']['dir_name'] = 'job';
				$query = "SELECT attribute,subAttrib FROM {$this->table}bu_detail where StoreID='$sid'";
				$this->dbcon->execute_query($query);
				$result = $this->dbcon->fetch_records(true);
				$attribute =$result[0]['attribute'];
				$subAttrib =$result[0]['subAttrib'];
				if($attribute==3&&$subAttrib==1){
					$job_where = " and category=1 ";
				}else{
					$job_where = " and category=2 ";
				}
				$_query	= "select count(*) as num from ".$this->table."product_job where StoreID='$sid' and deleted=0 $job_where";
				break;
		}
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		if (is_array($arrTemp)){
			$arrResult['product']['count'] = $arrTemp[0]['num'];
		}

		// get watch, sold, purchased and auction item numbers
		$_query = "select count(*) as num from ".$this->table."watchitem where StoreID='$sid'";
		$this->dbcon->execute_query($_query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['count']['watch'] = $arrTemp[0]['num'];
		}

		switch ($arrResult['detail']['attribute']){
			case 5:
				$_query	= "select count(*) as num from ".$this->table."order_detail_foodwine where  StoreID='$sid' ";
				break;
			default:
				$_query = "select count(*) as num from ".$this->table."order_reviewref where StoreID='$sid' and pid!=0 and (type='purchasing' or type='bid')";
				break;
		}
		$this->dbcon->execute_query($_query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['count']['sold'] = $arrTemp[0]['num'];
		}

		//get order
		switch ($arrResult['detail']['attribute']){
			case 5:
				$_query	= "select count(*) as num from ".$this->table."order_foodwine where buyer_id='$sid' ";
				break;
			default:
				$_query = "select count(*) as num from ".$this->table."order_reviewref where buyer_id='$sid' and pid!=0 and (type='purchasing' or type='bid')";
				break;
		}
		$this->dbcon->execute_query($_query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['count']['purchase'] = $arrTemp[0]['num'];
		}

		$_query = "select count(*) as num from ".$this->table."product where deleted!='YES' and is_auction='yes' and StoreID='$sid'";
		$this->dbcon->execute_query($_query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['count']['auction'] = $arrTemp[0]['num'];
		}

		$_query = "select count(*) as num from ".$this->table."product "
				."where deleted!='YES' and is_auction='no' and StoreID='$sid'";
		$this->dbcon->execute_query($_query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['count']['product'] = $arrTemp[0]['num'];
		}

		//email
		$_query = "select count(*) as num from ".$this->table."message WHERE StoreID  REGEXP '(^|,)$sid(,|$)' and pid=0 and status=0 order by status" ;
		$this->dbcon->execute_query($_query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['email']['messageCount'] = $arrTemp[0]['num'];
		}

		$_query = "select count(*) as num from ".$this->table."message WHERE StoreID  REGEXP '(^|,)$sid(,|$)' and pid<>0 and status=0 order by status" ;
		$this->dbcon->execute_query($_query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['email']['NewmessageCount'] = $arrTemp[0]['num'];
		}

		$_query = "select count(*) as num from ".$this->table."message WHERE StoreID  REGEXP '(^|,)$sid(,|$)' and pid<>0" ;
		$this->dbcon->execute_query($_query) ;
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['email']['AllmessageCount'] = $arrTemp[0]['num'];
		}

		$_query = "SELECT count(*) as num FROM ".$this->table."emailalert as t1, ".$this->table."login as t2 WHERE t1.userid= t2.id and t2.StoreID = '".$sid."'";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['email']['myAlertCount'] = $arrTemp[0]['num'];
		}

		$_query = "SELECT count(*) as num FROM ".$this->table."emailalert as t1 WHERE t1.StoreID = '".$sid."'";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		if (is_array($arrTemp)){
			$arrResult['email']['userAlertCount'] = $arrTemp[0]['num'];
		}

		//blog
		$_query = "select count(*) as num from ".$this->table."blog as t2 where t2.StoreID=$sid ";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true) ;
		if (is_array($arrTemp)){
			$arrResult['blog']['commentCount'] = $arrTemp[0]['num'];
		}

		//offer
		$_query	= "select count(*) as num from ".$this->table."obo_offer as t1, ".
		$this->table."bu_detail as t2,".
		$this->table."product as t3 ".
		"where t1.UserID = t2.StoreID AND t1.pid = t3.pid AND t1.StoreID='$sid'";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records();
		if (is_array($arrTemp)){
			$arrResult['offer']['allCount'] = $arrTemp[0]['num'];
		}

		$_query	= "select count(*) as num from ".$this->table."obo_offer as t1, ".
		$this->table."bu_detail as t2,".
		$this->table."product as t3 ".
		"where t1.UserID = t2.StoreID AND t1.pid = t3.pid AND t1.StoreID='$sid' and t1.accpet=0";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records();
		if (is_array($arrTemp)){
			$arrResult['offer']['newCount'] = $arrTemp[0]['num'];
		}

		$_query	= "select count(*) as num from ".$this->table."obo_offer  as t1, ".
		$this->table."bu_detail as t2,".
		$this->table."product as t3 ".
		"where t1.UserID = t2.StoreID AND t1.pid = t3.pid AND t1.StoreID='$sid' and accpet>0 and dateReview>0";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records();
		if (is_array($arrTemp)){
			$arrResult['offer']['receivedCount'] = $arrTemp[0]['num'];
		}

        //Certified
        $certified = new ProductCertified();
        $arrResult['certified']['count'] = $certified->getCountCertifiedByState($sid, 0);

		$arrResult['messageCount'] = $messageCount;
		$arrResult['totalNumber'] = $totalNumber;
		$arrResult['total_order_count'] = $total_order_count;
		$arrResult['approval_count'] = $approval_count;
		$arrResult['msg1'] = $msg1;
		$_query = "select bu_urlstring from ".$this-> table."bu_detail where StoreID='$sid'";
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		$arrResult['website_name'] = clean_url_name($arrTemp[0]['bu_urlstring']);
		$arrResult['foodwine_type'] = getFoodWineType();

		$query = "SELECT suspend from {$this->table}login where StoreID='$sid'";
		$this->dbcon->execute_query($query);
		$arrTemp = $this->dbcon->fetch_records(true);
		$arrResult['suspend'] = $arrTemp[0]['suspend'];

		if ($arrResult['detail']['attribute'] == '5') {
			include_once(SOC_INCLUDE_PATH.'/class.FoodWine.php');
			$foodWine = new FoodWine();
			$arrResult['new_online_order_num'] = $foodWine->getOrderNum($sid, '0');
			$arrResult['new_online_book_num'] = $foodWine->getBookNum($sid, '0');
			$foodWineNum = $foodWine->getSubscribersNum($sid);
                        
                        include_once "include/class.guestEmailSubscriber.php";
                        $guestSub = new guestEmailSubscriber();
                        $guestSubNum = $guestSub->getGuestSubscriberNum($sid);
                        
                        $arrResult['subscribers_num'] = $foodWineNum + $guestSubNum;
                        
                        
                        
		}

		return $arrResult;
	}

	/**
	 * get Buyer admin pages details.
	 *
	 * @author ping.hu
	 * @param stirng $sid
	 * @return array
	 */
	function getBuyerHome($sid){
		$arrResult['emailAlert']	=	0;
		
		$consumer_query = "SELECT login.id As userid FROM aus_soc_bu_detail detail INNER JOIN aus_soc_login login ON login.StoreID = detail.StoreID WHERE detail.StoreID = '".$sid."'";
		$consumer_result = $this->dbcon->getOne($consumer_query);

		$_query = "select count(*) as num from aus_soc_emailalert WHERE userid = '".intval($consumer_result['userid'])."'";
		$this->dbcon->execute_query($_query);
		$arrTemps = $this->dbcon->fetch_records(true);
		$arrResult['emailAlert'] = $arrTemps[0]['num'];

		return $arrResult;
	}

	function statePage($statename = '', $displaypage='', $collegeid='')
	{
		global $socObj;
		$today = date("Y-m-d");
		$totalfakes = in_array($displaypage, array('State', 'College')) ? 15 : 20;
		$Store = "";
		$sellertype = 0;
		$product_tab = 'product';
		$council = $_REQUEST['council'] ? $_REQUEST['council'] : DEFAULT_COUNCIL;

		$statename = ($statename == -1 or $statename == "") ? DEFAULT_STATE : $statename ;
		# search for ads
		if ($displaypage == 'State') {
			$query = "SELECT 'new',a.* FROM ". $this->table ."ads_soc as old,".
					$this->table."ads_new as a \n".
					"WHERE old.StoreID=a.StoreID \n".
					($statename != -1 ?"AND old.state = '$statename' \n" : '' ).
					"AND old.StoreID != '0' \n".
					"AND displaypage = 'State' \n".
					"ORDER BY adid ASC \n".
					"LIMIT $totalfakes";
			$sellertype = 0;
		} elseif ($displaypage == 'College') {
			$query = "SELECT 'new',a.* FROM `". $this->table ."ads_soc` as old, "
					.$this->table."ads_new as a \n".
					"WHERE old.StoreID=a.StoreID \n".
					"AND old.state = '$statename' \n".
					"AND old.collegeid = '$collegeid' \n".
					"AND old.StoreID != '0' \n".
					"AND `displaypage` = 'College' \n".
					"ORDER BY `adid` ASC \n".
					"LIMIT $totalfakes";
			$sellertype = 0;
		} elseif ($displaypage == 'Auto') {
			$query = "SELECT * FROM `". $this->table ."ads_soc` \n".
			"WHERE 1=1 \n".
			"AND `state` = '$statename' \n".
			"AND `StoreID` != '0' \n".
			"AND `displaypage` = 'Auto' \n".
			"ORDER BY `adid` ASC \n".
			"LIMIT $totalfakes";
			$sellertype = 2;
			$product_tab = 'product_automotive';
		} elseif ($displaypage == 'Estate') {
			$query = "SELECT * FROM `". $this->table ."ads_soc` \n".
			"WHERE 1=1 \n".
			"AND `state` = '$statename' \n".
			"AND `StoreID` != '0' \n".
			"AND `displaypage` = 'Estate' \n".
			"ORDER BY `adid` ASC \n".
			"LIMIT $totalfakes";
			$sellertype = 1;
			$product_tab = 'product_realestate';
		} elseif ($displaypage == 'Job') {
			$query = "SELECT * FROM `". $this->table ."ads_soc` \n".
			"WHERE 1=1 \n".
			"AND `state` = '$statename' \n".
			"AND `StoreID` != '0' \n".
			"AND `displaypage` = 'Job' \n".
			"ORDER BY `adid` ASC \n".
			"LIMIT $totalfakes";
			$sellertype = 3;
			$product_tab = 'product_job';
		} elseif ($displaypage == 'FoodWine') {
			$totalfakes = 15;
			$query = "SELECT ad.*,product.item_name,product.description,product.url_item_name,product.price,product.unit,product.priceorder FROM `". $this->table ."ads_soc` AS ad, `". $this->table ."product_foodwine` AS product \n".
			"WHERE ad.pid=product.pid \n".
			"AND ad.`state` = '$statename' \n".
			"AND ad.`council` = '$council' \n".
			"AND ad.`StoreID` != '0' \n".
			"AND ad.`displaypage` = 'FoodWine' \n".
			"ORDER BY ad.`adid` ASC \n".
			"LIMIT $totalfakes";
			$sellertype = 5;
			$product_tab = 'product_foodwine';
		} else {
			return false;
		}
		$this->dbcon->execute_query($query);
		$rows = $this->dbcon->fetch_records();

		//$freeFake = $totalfakes - count($rows);
		$ads = array();
		$notdis = "";
		$jj = 0;
        $StoreId=array();       // Store ID Array,promise storeid not same
        $StoreIds = 0;
        $cate_arr = array();
		if (is_array($rows)){
			foreach ($rows as $row){
				if ($row['StoreID'] && $row['new']!='new'){
					$_query = "SELECT bu.bu_urlstring,bu.address_hide, bu.phone_hide, lg.suspend FROM "
							.$this->table."bu_detail bu LEFT JOIN {$this->table}login lg "
							."ON lg.StoreID=bu.StoreID WHERE bu.StoreID=".$row['StoreID'];
					$this->dbcon->execute_query($_query);
					$bu_detail = $this->dbcon->fetch_records(true);
					if($bu_detail[0]['suspend']==1){
						continue;
					}
					$address_hide = $bu_detail[0]['address_hide'];
					$phone_hide = $bu_detail[0]['phone_hide'];
					$bu_urlstring = $bu_detail[0]['bu_urlstring'];
					$jj++;
					$userlogos = $this->getUserlogos($row['StoreID']);
					if ($sellertype == 5) {
						$_REQUEST['StoreID'] = $row['StoreID'];
						$_REQUEST['proid'] = $row['pid'];
				        $_REQUEST['pre'] = 1;
				        $product_info	=	$socObj -> displayStoreProduct();
				        $StoreIds .= ',' . $row['StoreID'];
				        $cate_arr[] = $product_info['items']['product'][0]['category'];
					}
					$ads[] = array
					(
						'url'   => clean_url_name($bu_urlstring),
						'title' => $row['companyline'],
						'desc'  => $row['tagline'],
						'addr'  => $row['addressline'],
						'tel'   => $row['phoneline'],
						'address_hide' => $address_hide,
						'phone_hide' => $phone_hide,
						'userlogos'	=>	$userlogos,
	                    'StoreID'   =>  $row['StoreID'],
                        'product'  =>  array(
                        	'name' =>$row['item_name'],
                        	'url_item_name' =>$row['url_item_name'],
                        	'description' =>$row['description'],
                        	'price' =>$row['price'],
                        	'unit' =>$row['unit'],
                        	'priceorder' =>$row['priceorder'],
                        	'images' => ($sellertype == 5 ? $product_info['items']['product'][0]['images']['mainImage'][0]['sname'] : null)
                        )
					);
					//$notdis .= $notdis==""?"'{$row['StoreID']}'":",'{$row['StoreID']}'";
                    $StoreId[]=$row['StoreID'] ;
				}else{
                                    /**
                                     * added by YangBall, 2011-03-25
                                     */
                                     $arr = explode('</li>', $row['items']);
                                     $i = 0;
                                     $rs = array();
                                     if(!empty($arr)) {
                                        foreach($arr as $a) {
                                            preg_match_all('/<a[^>]*>([^<]*)<\/a>/i', $a, $matches);
                                            $a = str_replace($matches[1][0], truncate($matches[1][0],50), $a);

                                            $rs[] = $a . '</li>';
                                            $i++;
                                            if($i>2) break;
                                        }
                                     }
                                     $row['items'] = implode(' ', $rs);
                                    //END-YangBall

					$ads[] = $row;
                                        $StoreId[]=$row['StoreID'] ;
					$jj++;
				}
			}
			//print_r($ads);
			$freeFake = $totalfakes-$jj;
		} else {
			$freeFake = $totalfakes;
		}
//                var_dump($ads);
                $notdis=implode(',',$StoreId);
		if($freeFake > 0){
			$wherenot = "";
			if($notdis!=""){
				$wherenot = " AND detail.StoreID NOT IN ($notdis) ";
			}
			if($sellertype==0){
				$timenow = time();
				$pronotdel = " AND IF(pro.is_auction='yes',au.end_stamp>$timenow,1=1) and pro.pid>0 and pro.deleted<>'YES' group by detail.StoreID ";
			}else{
				$pronotdel = " and pro.pid>0 and pro.deleted=0 and pro.enabled=1 group by detail.StoreID ";
			}
			$query = "SELECT detail.*,state.stateName FROM {$this->table}bu_detail detail \n".
					 " LEFT JOIN {$this->table}login lg on lg.StoreID=detail.StoreID \n".
					 " LEFT JOIN {$this->table}state state ON detail.bu_state = state.id \n".
					 " LEFT JOIN {$this->table}$product_tab pro ON pro.StoreID = detail.StoreID \n".
					 " LEFT JOIN {$this->table}product_auction  au ON au.pid = pro.pid \n".
					 " WHERE  lg.suspend=0 \n".
					 //" and detail.renewalDate>".time().
					 " and state.stateName = '{$statename}' \n".
					 " and detail.attribute={$sellertype} $wherenot $pronotdel order by RAND() LIMIT $freeFake";
			if($sellertype == 3){
				$query = "SELECT detail.*,state.stateName FROM {$this->table}bu_detail detail \n".
				" LEFT JOIN {$this->table}login lg on lg.StoreID=detail.StoreID \n".
				" LEFT JOIN {$this->table}state state ON detail.bu_state = state.id \n".
				" lEFT JOIN {$this->table}product_job job ON detail.StoreID = job.StoreID  \n".
				" WHERE lg.suspend=0 and state.stateName = '{$statename}' \n".
				" and detail.attribute={$sellertype} AND detail.subAttrib<>3 \n";
				$query .= " and IF(detail.subAttrib=1, job.category=1 , 1=1) ";
				$query .= " and IF(detail.subAttrib=2, job.category=2 , 1=1) ";
				$current_date = date("Y-m-d");
				if($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2)) {
					$query .= " AND IF(job.category=2, job.ispub in(0,1) , 1=1) ";
				}else{
					$query .= " AND IF(job.category=2, job.ispub in(1) , 1=1) ";
				}
				$query .= 	" AND ((job.datePosted <= '$current_date' or job.datePosted='0000-00-00') \n".
							" AND (job.closingDate >= '$current_date' or job.closingDate='0000-00-00'))\n ";
				$query .=" $wherenot AND job.deleted = '0' AND job.enabled=1 AND job.StoreID>0 group by detail.StoreID  order by RAND()  LIMIT $freeFake";
			}
			if ($sellertype == 5) {
				$query = '';
			}
			if ($sellertype == 0){
				$query = "SELECT  'new', a . * FROM ".$this->table."ads_new AS a "
						."WHERE state='{$statename}' and category!='None' " . ($notdis ? " and StoreID not in ({$notdis}) " : ' ') . " order by rand() LIMIT $freeFake";
			}

			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			
	        $i = 0;
			$res = array();
			if(is_array($result)){
				foreach ($result as $pass){
					if ($sellertype == 5) {
				        $images = array(
				        	'text' => IMAGES_URL.'/skin/red/foodwine/category_icon/default/'.$this->lang['seller']['attribute']['5']['cateimg'][$pass['subAttrib']]
				        );
					}
					if ($pass['new']!='new'){
						$ad_item = array
						(
						'url'	=> clean_url_name($pass['bu_urlstring']),
						'title'	=> "{$pass['bu_name']}",
						'desc'	=> "Click here to visit our site!",
						'addr'	=> ($pass['bu_address']!=""?"{$pass['bu_address']},":"").($pass['bu_suburb']!=""?" {$pass['bu_suburb']},":"").($pass['stateName']!=""?" {$pass['stateName']}":"").($pass['bu_postcode']!=""?", {$pass['bu_postcode']}":""),
						'tel'	=> "{$pass['bu_phone']}",
						'address_hide'	=> "{$pass['address_hide']}",
						'phone_hide'	=> "{$pass['phone_hide']}",
						'phone_hide'	=> "{$pass['phone_hide']}",
						'userlogos'	=>	$this->getUserlogos($pass['StoreID'])	,
                        'StoreID'   =>  $pass['StoreID'],
                        'itemtype'   =>  'cate',
                        'subAttrib'   =>  "{$pass['subAttrib']}",
                        'product'  =>  array(
                        	'name' =>$this->lang['seller']['attribute']['5']['subattrib'][$pass['subAttrib']],
                        	'url_item_name' =>$pass['url_item_name'],
                        	'description' =>$pass['description'],
                        	'price' =>$pass['price'],
                        	'unit' =>$pass['unit'],
                        	'priceorder' =>$pass['priceorder'],
                        	'images' => ($sellertype == 5 ? $images : null)
                        )
						);
						if ($sellertype == 5) {
					        if ($product_info['items']['product'][0]['images']['mainImage'][0]['sname']['text'] != '/images/243x212.jpg' && !in_array($pass['subAttrib'], $cate_arr)) {
								$ads[] = $ad_item;
								$cate_arr[] = $pass['subAttrib'];
								$i++;
							}
							if ($i >= $show_count) {
								break;
							}
						} else {
							$ads[] = $ad_item;
						}
					}else{
                                                /**
                                                 * added by YangBall, 2011-03-25
                                                 */
                                                 $arr = explode('</li>', $pass['items']);
                                                 $i = 0;
                                                 $rs = array();
                                                 if(!empty($arr)) {
                                                    foreach($arr as $a) {
                                                        preg_match_all('/<a[^>]*>([^<]*)<\/a>/i', $a, $matches);
                                                        $a = str_replace($matches[1][0], truncate($matches[1][0],50), $a);
                                                        $rs[] = $a . '</li>';
                                                        $i++;
                                                        if($i>2) break;
                                                    }
                                                 }
                                                 $pass['items'] = implode(' ', $rs);
                                                //END-YangBall

						$ads[] = $pass;
					}
				}
				$freeFake = $freeFake - count($result);
			}
		}
		
		if ($sellertype == 5) {			
			$ads = $this->getRandPopularize($ads, $statename, $council, 15);
		} elseif ($freeFake > 0) {
			$flag = 0;
			for ($num = 0; $num < $freeFake; $num ++)
			{
				if ($flag == 0) {
					if ($sellertype == 0){
						$ads[] = array(
							'new'	=> 'new',
							'fake'	=> 1,
							'title'	=> 'PROMOTE YOURSELF HERE - IT\'S FREE.',
							'desc'	=> 'Everyday each state homepage will be refreshed. The first 15 people to claim a spot at 1pm daily, will become the Featured Local Listings for the day!'
						);
//                                                if('College' == $displaypage)
//                                                $ads[] = array(
//							'new'	=> 'new',
//							'fake'	=> 1,
//							'title'	=> 'BE A FEATURED LOCAL LISTING - IT\'S FREE.'
//						);

					}else{
						$ads[] = array(
							'url'   => 'soc.php?cp=statelink',
							'title' => 'PROMOTE YOURSELF HERE - IT\'S FREE.',
							'fake'  => 'Everyday each state homepage will be refreshed. The first 15 people to claim a spot at 1pm daily, will become the Featured Local Listings for the day!',
							'addr'  => '',
							'tel'   => '',
							'address_hide' => '0',
							'phone_hide' => '0',
                                                        'type'      =>  'system'
						);
                                                $flag = 1;
					}
					//if('College' == $displaypage) $flag = 1;
				} else {
					if ($sellertype == 0){
						$ads[] = array(
							'new'	=> 'new',
							'fake'	=> 1,
							'title'	=> 'BE A FEATURED LOCAL LISTING - IT\'S FREE.'
						);
					}else{
						$ads[] = array(
							'url'   => 'soc.php?cp=statelink',
							'title' => 'BE A FEATURED LOCAL LISTING - IT\'S FREE.',
							'desc'  => '',
							'addr'  => '',
							'tel'   => '',
							'address_hide' => '0',
							'phone_hide' => '0',
                                                        'type'      =>  'system'
						);
					}
					$flag = 0;
				}
			}
		}

		//$statename = $_REQUEST['state_name'] ? $_REQUEST['state_name'] : $_REQUEST['statename'];
		# search for states
		$query = "SELECT stateName, description \n".
		"FROM `". $this->table ."state` \n".
		"ORDER BY `stateName` ASC";

		$this->dbcon->execute_query($query);
		$rows = $this->dbcon->fetch_records();
		$states = array();
		foreach ($rows as $row)
		{
			$states[] = array
			(
			'state'    => $row['stateName'],
			'description' => $row['description'],
			'selected' => $statename == $row['stateName'] ? ' selected="selected"' : ''
			);
		}

		# search for colleges
		$collegeid = $_REQUEST['collegeid'];
		$stateID = getStateByID($statename);
		$query = "SELECT * \n".
		"FROM `". $this->table ."universities_colleges` WHERE bizState=$stateID  \n".
		"ORDER BY `bizName` ASC";

		$this->dbcon->execute_query($query);
		$rows = $this->dbcon->fetch_records();
		$colleges = array();
		if (!empty($rows)) {
			foreach ($rows as $row)
			{
				$colleges[] = array
				(
				'collegeid'    => $row['bizID'],
				'collegename'    => $row['bizName'],
                                'city'         =>   $row['City'],
				'selected' => $collegeid == $row['bizID'] ? ' selected="selected"' : ''
				);
			}

		}

		# search for cities
		$suburb = explode('.', ($displaypage=='Estate')?$_REQUEST['suburb']:$_REQUEST['selectSubburb']);
		$suburb = $suburb[0];
		$cities = getSuburbArray($statename, $suburb);

		# distance
		$distance = array( 3, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300);

		// changed for function call by Jessee 20081222
		$stateAd = getBannerDisplay($displaypage,$statename);



		$arr = array
		(
		'ads'      => $ads,
		'states'   => $states,
		'colleges' => $colleges,
		'cities'   => $cities,
		'distance' => $distance,
		'imageAds' => $stateAd
		);
		
		if ($sellertype == 5) {
			$arr['councils'] = getCouncilArray($statename, $_REQUEST['council']);
		}

		return $arr;
	}
	
	function getRandPopularize($result=array(), $statename='', $council='', $show_count=15) {
		$wheresql = '';
		if ($statename) {
			$wheresql .= "AND state.`stateName`='$statename' \n";
		}
		if ($council) {
			$subarr = getSuburbArrayByCouncil($council);
			$suburbstr = '';	
			if (is_array($subarr)) {
				foreach ($subarr as $key => $sub) {
					$suburbstr .= (($key == 0 ) ? '' : ','). "'" . $sub['suburb'] . "'";
				}
			}
			$wheresql .= "AND detail.`bu_suburb` IN ($suburbstr) \n";	
		}
		
		$query = "SELECT detail.* FROM {$this->table}bu_detail AS detail \n".
						 " LEFT JOIN {$this->table}state AS state ON state.id=detail.bu_state \n".
						 " WHERE detail.CustomerType='seller' \n".
						 " AND detail.is_popularize_store='1' \n".
						 " AND detail.attribute=5 \n"
						 .$wheresql;
		$this->dbcon->execute_query($query);
		$res = $this->dbcon->fetch_records(true);
		
		if (empty($res)) {
			return array();
		}
	
		$result = $this->getRandItems($res, $result, true, $show_count);
		$result = $this->getRandItems($res, $result, true, $show_count);
		$result = $this->getRandItems($res, $result, false, $show_count);
	
		return $result;
	}
	
	function getRandItems($src=array(), $result=array(),$uniqid_cat=true, $show_count=15) {
		$random_num = count($src);
		$limit = $show_count - count($result);
		if (is_array($result)) {
			$storearr = array();
			foreach ($result as $item) {
				$storearr[] = $item['StoreID'];
			}
		}
		if ($limit > 0 && $random_num) {
			$i = $limit;
			$subarr = array();
			shuffle($src);
			$random_key = array_rand($src, $random_num);
			foreach ($random_key as $key) {
				$pass = $src[$key];
				if ((!in_array($pass['subAttrib'], $subarr) || !$uniqid_cat) && !in_array($pass['StoreID'], $storearr)) {
					$images = array(
					        	'text' => IMAGES_URL.'/skin/red/foodwine/category_icon/default/'.$this->lang['seller']['attribute']['5']['cateimg'][$pass['subAttrib']]
					        );
					$result[] = array
					(
						'url'	=> clean_url_name($pass['bu_urlstring']),
						'title'	=> "{$pass['bu_name']}",
		                'StoreID'   =>  $pass['StoreID'],
		                'itemtype'   =>  'cate',
		                'subAttrib'   =>  "{$pass['subAttrib']}",
		                'product'  =>  array(
		                	'name' =>$this->lang['seller']['attribute']['5']['subattrib'][$pass['subAttrib']],
		                	'url_item_name' =>$pass['url_item_name'],
		                	'description' =>$pass['description'],
		                	'price' =>$pass['price'],
		                	'unit' =>$pass['unit'],
		                	'priceorder' =>$pass['priceorder'],
		                	'images' => $images
		                	)
						);	
						$subarr[] = $pass['subAttrib'];
						$storearr[] = $pass['StoreID'];
			            $i--;	
					}
		            if ($i < 1) {
		            	break;
		            }
				}
			}
			
			return $result;
	}
	
	function getRandNext($result, $show_count=15) {
		$limit = $show_count - count($result);
		
		if ($limit > 0) {
			$query = "SELECT * FROM {$this->table}bu_detail \n".
							 " WHERE CustomerType='seller' \n".
							 " AND is_popularize_store='1' \n".
							 " AND attribute=5 \n".
					 		 " GROUP BY subAttrib".
					 		 " ORDER BY RAND()".
			                 " LIMIT 0, {$limit}";
		}
		
		$this->dbcon->execute_query($query);
		$res = $this->dbcon->fetch_records(true);
		if (is_array($res)) {
			foreach ($res as $pass) {
				$images = array(
				        	'text' => IMAGES_URL.'/skin/red/foodwine/category_icon/default/'.$this->lang['seller']['attribute']['5']['cateimg'][$pass['subAttrib']]
				        );
				$result[] = array
				(
					'url'	=> clean_url_name($pass['bu_urlstring']),
					'title'	=> "{$pass['bu_name']}",
	                'StoreID'   =>  $pass['StoreID'],
	                'itemtype'   =>  'cate',
	                'subAttrib'   =>  "{$pass['subAttrib']}",
	                'product'  =>  array(
	                	'name' =>$this->lang['seller']['attribute']['5']['subattrib'][$pass['subAttrib']],
	                	'url_item_name' =>$pass['url_item_name'],
	                	'description' =>$pass['description'],
	                	'price' =>$pass['price'],
	                	'unit' =>$pass['unit'],
	                	'priceorder' =>$pass['priceorder'],
	                	'images' => $images
	                )
				);
			}	
		}
		
		return $result;		
	}

    function sendReportSellerMail(array $data){
		global $email_regards;
        $mailto  = 'dafiny.zhang@infinitytesting.com.au';//'little.he@infinitytesting.com.au';
        $from    = 'info@thesocexchange.com';
        $subject = 'SOC Report '.$data['storeType'];
        $message = '<html>
        <head>
        <title>'.$subject.'</title>
        </head>
        <body>
        <p>Dear Administrator,</p>
        <p>This is the Report '.$data['storeType'].' enquiry.</p>
        Seller Name: '.htmlspecialchars(get_magic_quotes_gpc()?stripslashes($data['sellerName']):$data['sellerName']).' <br/>
        Seller Email: '.htmlspecialchars(get_magic_quotes_gpc()?stripslashes($data['sellerEmail']):$data['sellerEmail']).'<br/>
        Seller Phone: '.htmlspecialchars(get_magic_quotes_gpc()?stripslashes($data['sellerPhone']):$data['sellerPhone']).'<br/>
        Seller Url: '.htmlspecialchars(get_magic_quotes_gpc()?stripslashes($data['sellerUrl']):$data['sellerUrl']).'</p><p>
        Complainant Name: '.htmlspecialchars(get_magic_quotes_gpc()?stripslashes($data['complainantName']):$data['complainantName']).'<br/>
        Complainant Email: '.htmlspecialchars(get_magic_quotes_gpc()?stripslashes($data['complainantEmail']):$data['complainantEmail']).'<br/>
        Complainant Comments: '.nl2br(htmlspecialchars(get_magic_quotes_gpc()?stripslashes($data['complainantComment']):$data['complainantComment'])).'</p>
        <p>Sincerely,<br/>
        '.$email_regards.'<br/>
        <a href="http://'.$_SERVER['HTTP_HOST'].'/">http://'.$_SERVER['HTTP_HOST'].'</a>
        </p>
        </body>
        </html>';

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "From: $from\r\n";

        if (@mail($mailto, $subject, getEmailTemplate($message), fixEOL($headers))){
            $msg = ($type == 'contact') ? "Thank you for contacting 'FoodMarketplace'. We have received your message and will attend to your enquiry." :
            "Thank you for contacting 'FoodMarketplace'. We have received your message and will attend to your enquiry.";
            $urlParam = empty($_REQUEST['ctm']) ? '' : 'ctm='. $_REQUEST['ctm'] .'&';
            //header("Location: contact_us_resp.php?". $urlParam ."msg=$msg");
        }else{
            $msg = "Email sent failed.";
        }

		return $msg;
    }

	function mailFunContact()
	{
		global $email_regards;
		$Email      = trim($_REQUEST['Email']) ;
		$Phone      = trim($_REQUEST['Phone']) ;
		$Comments   = trim($_REQUEST['Comments']) ;
		$utype 		= trim($_REQUEST['type']);
		$nickName	= trim($_REQUEST['nickName']);


		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{


			switch ($utype){
				case 'Advertising':
					$subject = 'SOC Advertising';
					$ttype = 'Advertising';
                                        $mailto = 'advertising@thesocexchange.com';
//                                        $mailto = 'dafinyinfinitytest@163.com';
					break;

				case 'Report Seller':
					$subject = 'SOC Report Seller';
					$ttype = 'Report Seller';
                                        $mailto = 'reportseller@thesocexchange.com';
//                                        $mailto = 'testeam2067@yahoo.com';
					break;

				case 'General Enquiry':
					$subject = 'SOC General Enquiry';
					$ttype = 'General Enquiry';
                                        $mailto = 'enquiry@thesocexchange.com';
//                                        $mailto = 'testeam2067@126.com';
					break;
                                case 'Suggestion/Feedback':
                                        $subject = 'SOC Suggestion/Feedback';
                                        $ttype = 'Suggestion/Feedback';
                                        $mailto = 'feedback@thesocexchange.com';
//                                        $mailto = 'testeam2067@163.com';
                                        break;
                                case 'Technical Support':
                                        $subject = 'SOC Technical Support';
                                        $ttype = 'Technical Support';
                                        $mailto = 'techsupport@thesocexchange.com';
//                                        $mailto = 'testeam2067@yeah.net';
                                        break;
                                default :
                                        $subject = 'SOC Admin';
                                        $ttype = 'Admin';
                                        $mailto = 'admin@thesocexchange.com';
//                                        $mailto = 'testeam2067@gmail.com';
                                        break;
			}

         if($this->paypal_info['paypal_mode'] == 0){
				$mailto = 'dafiny.infinitytest@gmail.com';
			}

			$from    = $Email;

			$message = '<html>
			<head>
			<title>'.$subject.'</title>
			</head>
			<body>
			<p>Dear Administrator,</p>
			<p>This is the Contact Us enquiry.</p>
			<p> Type: '.$ttype.' <br/>
			User Name: '.htmlspecialchars(get_magic_quotes_gpc()?stripslashes($nickName):$nickName).'<br/>
			Contact Email: '.htmlspecialchars(get_magic_quotes_gpc()?stripslashes($Email):$Email).'<br/>
			Phone: '.htmlspecialchars(get_magic_quotes_gpc()?stripslashes($Phone):$Phone).'</p>
			<p>Comments: '.nl2br(htmlspecialchars(get_magic_quotes_gpc()?stripslashes($Comments):$Comments)).'</p>
			<p>Sincerely,<br/>
			'.$email_regards.'<br/>
			<a href="http://'.$_SERVER['HTTP_HOST'].'/">http://'.$_SERVER['HTTP_HOST'].'</a>
			</p>
			</body>
			</html>';

			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$headers .= "To: $to\r\n";
			$headers .= "From: $from\r\n";

			if (@mail($mailto, $subject, getEmailTemplate($message), fixEOL($headers)))
			{
				$msg = ($type == 'contact') ? "Thank you for contacting 'FoodMarketplace'. We have received your message and will attend to your enquiry." :
				"Thank you for contacting 'FoodMarketplace'. We have received your message and will attend to your enquiry.";
//				$urlParam = empty($_REQUEST['ctm']) ? '' : 'ctm='. $_REQUEST['ctm'] .'&';
				//header("Location: contact_us_resp.php?". $urlParam ."msg=$msg");
			}
			else
			{
				$msg = "Email sent failed.";
			}
		}

		return $msg;
	}

	function mailFun($type)
	{
		$First_Name = trim($_REQUEST['First_Name']) ;
		$Last_Name  = trim($_REQUEST['Last_Name']) ;
		$Address    = trim($_REQUEST['Address']) ;
		$Suburb     = trim($_REQUEST['Suburb']) ;
		$State      = trim($_REQUEST['State']) ;
		$Postcode   = trim($_REQUEST['Postcode']) ;
		$Country    = trim($_REQUEST['Country']) ;
		$Email      = trim($_REQUEST['Email']) ;
		$Phone      = trim($_REQUEST['Phone']) ;
		$Comments   = trim($_REQUEST['Comments']) ;



		$utype 		= trim($_REQUEST['type']);
		$name 		= trim($_REQUEST['bu_Name']);
		$nickName	= trim($_REQUEST['nickName']);


		if($_SERVER['REQUEST_METHOD'] == 'POST' and !empty($First_Name) )
		{
			$subject = ($type == 'contact') ? 'SOC Protection - Comment From User' : 'SOC Protection - Urgent Action From User';
			$mailto  = ($type == 'contact') ? 'info@thesocexchange.com' : 'info@thesocexchange.com';
			$from    = $Email;
			if($this->paypal_info['paypal_mode'] == 0){
				$mailto = 'dafiny.infinitytest@gmail.com';
			}
			$message = '<html>
			<head>
			<title>Comment From User</title>
			</head>
			<body>
			<p>&nbsp;</p>
			<p> '.$_REQUEST['Comments'].'</p><br>
			<p> Thanks And Regards</p>
			<p> '.$_REQUEST['First_Name'].' '.$_REQUEST['Last_Name'] .'<br>
			'.$_REQUEST['Address'].', '.$_REQUEST['Suburb'].' <br>
			'.$_REQUEST['State'].' ('.$_REQUEST['Country'].')<br>
			'.$_REQUEST['Postcode'].' <br>
			Email: '.$Email.'<br>
			'.$_REQUEST['Phone'].'</p>
			</body>
			</html>';

			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$headers .= "To: $to\r\n";
			$headers .= "From: $from\r\n";
                        $message=stripslashes($message);
			if (@mail($mailto, $subject, getEmailTemplate($message), $headers))
			{
				$msg = ($type == 'contact') ? "Thank you for contacting 'FoodMarketplace'. We have received your message and will attend to your enquiry." :
				"Thank you for contacting 'FoodMarketplace'. We have received your message and will attend to your enquiry.";
				$urlParam = empty($_REQUEST['ctm']) ? '' : 'ctm='. $_REQUEST['ctm'] .'&';
				//header("Location: contact_us_resp.php?". $urlParam ."msg=$msg");
			}
			else
			{
				$msg = "Email sent failed.";
			}
		}

		return $msg;
	}

	function getBusinessStat($sid,$type='PRODUCT'){
		$arrTemp = array();
		if($type=="WISHLIST"){
			$pro_table = "wishlist";
		}else{
			$pro_table = "store";
		}

		$nowtimemap = time();
		$lasttimemap = mktime(0,0,0,date('m')-1,date('d'),date('Y'));
		$_query = "SELECT `".date('M',$lasttimemap)."`,`".date('M',$nowtimemap)."`,year from {$this->table}hit_{$pro_table} WHERE StoreID = '".$sid."' and `year` in('".date("Y",$nowtimemap)."','".date('M',$lasttimemap)."')";
		$this->dbcon->execute_query($_query) ;
		$countMonth = $this->dbcon->fetch_records(true);

		$arrTemp['countThisMonth'] = 0;
		$arrTemp['countLastMonth'] = 0;
		if($countMonth){
			foreach ($countMonth as $ps_count):
				if(date("Y",$nowtimemap)==date("Y",$lasttimemap)){
					$arrTemp['countThisMonth'] = $ps_count[date('M',$nowtimemap)];
					$arrTemp['countLastMonth'] = $ps_count[date('M',$lasttimemap)];
				}elseif($ps_count['year']==date("Y",$nowtimemap)){
					$arrTemp['countThisMonth'] = $ps_count[date('M',$nowtimemap)];
				}elseif ($ps_count['year']==date("Y",$lasttimemap)){
					$arrTemp['countLastMonth'] = $ps_count[date('M',$lasttimemap)];
				}
			endforeach;
		}
		$_query = "SELECT `".date('M',$lasttimemap)."`,`".date('M',$nowtimemap)."`,year from {$this->table}hit_{$pro_table}_product WHERE StoreID = '".$sid."' and `year` in('".date("Y",$nowtimemap)."','".date('M',$lasttimemap)."')";
		$this->dbcon->execute_query($_query) ;
		$countproMonth = $this->dbcon->fetch_records(true);
		if($countproMonth){
			foreach ($countproMonth as $ps_count):
				if(date("Y",$nowtimemap)==date("Y",$lasttimemap)){
					$arrTemp['countThisMonth'] += $ps_count[date('M',$nowtimemap)];
					$arrTemp['countLastMonth'] += $ps_count[date('M',$lasttimemap)];
				}elseif($ps_count['year']==date("Y",$nowtimemap)){
					$arrTemp['countThisMonth'] += $ps_count[date('M',$nowtimemap)];
				}elseif ($ps_count['year']==date("Y",$lasttimemap)){
					$arrTemp['countLastMonth'] += $ps_count[date('M',$lasttimemap)];
				}
			endforeach;
		}
		$year = empty($_REQUEST['selectYear']) ? date("Y",$nowtimemap) : $_REQUEST['selectYear'];

		$_query = "SELECT `year`,`Jan` as '1',`Feb` as '2',`Mar` as '3',`Apr` as '4',`May` as '5',`Jun` as '6',`Jul` as '7',`Aug` as '8',`Sep` as '9',`Oct` as '10',`Nov` as '11',`Dec` as '12',(`Jan` + `Feb` + `Mar` + `Apr` + `May` + `Jun` + `Jul` + `Aug` + `Sep` + `Oct` + `Nov` + `Dec`) AS total from {$this->table}hit_{$pro_table} WHERE StoreID = '".$sid."' and `year`='{$year}'";
		$this->dbcon->execute_query($_query) ;
		$countYearTemp = $this->dbcon->fetch_records();
		$CYS = $countYearTemp[0];
		$CYS['year'] =$year;
		$arrTemp['cys'] = $CYS;
		$arrTemp['currentYear'] = date("Y",$nowtimemap);
		$arrTemp['lastYear'] = date("Y",$nowtimemap)-1;

		$arrTemp['product'] = $this->getpreItemStat($sid,$year,1,$type);
		return $arrTemp;
	}
	function getpreItemStat($sid,$year,$curpage = 1,$type='PRODUCT'){
		$arrTemp = array();
		if ($type=="WISHLIST") {
			$pro_table = "wishlist";
			$product_table = "wishlist";
		} else {
			$pro_table = "store";
			$product_table = "product_foodwine";	
		}
		$titles = "SELECT p.`item_name` as name,hp.`year`,hp.`Jan` as '1',hp.`Feb` as '2',hp.`Mar` as '3',hp.`Apr` as '4',hp.`May` as '5',hp.`Jun` as '6',hp.`Jul` as '7',hp.`Aug` as '8',hp.`Sep` as '9',hp.`Oct` as '10',hp.`Nov` as '11',hp.`Dec` as '12',(hp.`Jan` + hp.`Feb` + hp.`Mar` + hp.`Apr` + hp.`May` + hp.`Jun` + hp.`Jul` + hp.`Aug` + hp.`Sep` + hp.`Oct` + hp.`Nov` + hp.`Dec`) AS total";
		$wheres = " FROM {$this->table}hit_{$pro_table}_product hp LEFT JOIN {$this->table}$product_table p ON p.pid=hp.pid WHERE hp.StoreID = '".$sid."' and hp.`year`='{$year}'";
		$_query = "SELECT count(*) as num $wheres";

		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);
		$totalNum	= 	$result[0]['num'];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;

		$limits = " limit $start,$perPage ";

		$query = $titles.$wheres.$limits;
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		$arrResult['list'] = $result;
		$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getpreItemStat(\''.$sid.'\',\''.$year.'\',\'%d\',\''.$type.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
		$pager = Pager::factory($params);
		$arrResult['links'] 		= $pager->getLinks();
		$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
		return $arrResult;
	}

	function changePass() {
		getStoreID($_SESSION['UserID']);

		$_query	=	"select * from ".$this->table."login where id	=	'$_SESSION[UserID]'";
		$this->dbcon->execute_query($_query);
		$userinfo = $this->dbcon->fetch_records(true);
		//echo $query;

		if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_REQUEST['passwords'] != '' AND $_REQUEST['new_password'] != '' AND $_SESSION['StoreID'] != '')
		{
			// checking existance of the userid and password
			if($userinfo[0]['password'] == $_REQUEST['passwords']){
				//				$arr = array("password"=>$_REQUEST['new_password']) ;

				//echo print_r($arr);
				$_query = "UPDATE ".$this->table."login SET password='".$_REQUEST['new_password']."' where id = $_SESSION[UserID]";
				if($this->dbcon->execute_query($_query))
				{
					$_SESSION['Password'] = $_REQUEST['new_password'];
					$msg =  "Password changed successfully. " ;
				}
				else
				{
					$msg =  "Operation failed." ;
				}
			}else{
				$msg =  "Current password is wrong." ;
			}
			//header("Location:change_pass.php?msg=$msg");
		}
		elseif($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$msg =  "Operation failed.";
		}

		return $msg;
	}

	function payReports() {

		$arrTemp = array();

		$date	=	time();

		$path	=	"http://".$_SERVER['HTTP_HOST']."";
		$url=$path."/activate.php?action=return";
		$url1=$path."/activate.php?action=cancel";
		$url2=$path."/activate.php?action=notify";
		$StoreID = $_SESSION['StoreID'];

		if($_SESSION['UserID']=='' AND $_SESSION['level']!=1){
			header("Location:index.php");
		}

		getStoreID($_SESSION['UserID']);
		$dateformat = str_replace("-","/",str_replace("%","",DATAFORMAT_DB));

		$_query		=	"SELECT * FROM ".$this->table."bu_detail WHERE StoreID='$_SESSION[ShopID]'";
		$this->dbcon->execute_query($_query);
		$rows = $this->dbcon->fetch_records(true);

		if($rows[0]['PayDate']!='' && $rows[0]['renewalDate']>0){
			$startDate	=	date($dateformat,$rows[0]['PayDate']);
			$nextyear  	= 	date($dateformat,$rows[0]['renewalDate']);
		}else{
			$startDate	=	date($dateformat);
			$nextyear  = 	date($dateformat);
			$msg	=	"You have not currently paid. Your site will not be live at SOCExchange until payment is received. To pay now click here.";
		}

		if(isset($_REQUEST['submit']) AND $_REQUEST['submit']!=''){
			$fromDate	=	(isset($_REQUEST['fromDate']) && $_REQUEST['fromDate'] !='') ? $_REQUEST['fromDate']:'';
			$toDate		=	(isset($_REQUEST['toDate']) && $_REQUEST['toDate'] !='') ? $_REQUEST['toDate']:'';
			$date		=	"From $fromDate To $toDate";
			$flag=1;
		}

		$lastMonth	=	(isset($_REQUEST['lastMonth']) && $_REQUEST['lastMonth'] !='') ? $_REQUEST['lastMonth']:'';

		$lastmonth		=	mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
		$datelast		=	date("Y-m-d",$lastmonth);

		$arrTemp['startDate'] = $startDate;
		$arrTemp['nextYear'] = $nextyear;
		$arrTemp['StoreID'] = $_SESSION['StoreID'];
		$arrTemp['bu_user'] = $_SESSION['email'];
		$arrTemp['bu_name'] = $rows[0]['bu_name'];
		$arrTemp['bu_username'] = $rows[0]['bu_username'];
		$arrTemp['bu_urlstring'] = $rows[0]['bu_urlstring'];
		$arrTemp['msg']		= $_REQUEST['msg'];

		return $arrTemp;
	}

	function Inbox() {

		$arrTemp = array();
		$datetimeFormat = str_replace("-","/",str_replace("%","",DATAFORMAT_DB));

		if (!empty($_POST)) {
			$delchk = $_REQUEST['delchk'] ;
			$del = implode(",",$delchk) ;

			for($i=0;$i<count($delchk);$i++)
			{
				$sqlr = "select StoreID from ".$this->table."message where messageID = $delchk[$i] " ;
				$this->dbcon->execute_query($sqlr) ;
				$grid = $this->dbcon->fetch_records() ;

				$sid = $_SESSION['StoreID'] ;

				$storeidArr =  explode(",",$grid[0]['StoreID']) ;
				$key = array_search($sid, $storeidArr );
				$storeidArr[$key] = "" ;
				$n = array() ;
				for($j=0 ; $j< count($storeidArr) ; $j++)
				{
					if($storeidArr[$j] != "" )
					$n[] =  $storeidArr[$j] ;
				}

				$sidstr = implode(",",$n) ;
				$sqlupdate = "update ".$this->table."message set StoreID = '$sidstr' where messageID  = " .$delchk[$i]  ;
				$this->dbcon->execute_query($sqlupdate) ;

			}

			for($i=0;$i<count($delchk);$i++)
			{
				$sqlr = "select StoreID from ".$this->table."message_out where messageID = $delchk[$i] " ;
				$this->dbcon->execute_query($sqlr) ;
				$grid = $this->dbcon->fetch_records() ;
				$sid = $_SESSION['StoreID'] ;

				$storeidArr =  explode(",",$grid[0]['StoreID']) ;
				$key = array_search($sid, $storeidArr );
				$storeidArr[$key] = "" ;
				$n = array() ;
				for($j=0 ; $j< count($storeidArr) ; $j++)
				{
					if($storeidArr[$j] != "" )
					$n[] =  $storeidArr[$j] ;
				}

				$sidstr = implode(",",$n) ;
				$sqlupdate = "update ".$this->table."message_out set StoreID = '$sidstr' where messageID  = " .$delchk[$i]  ;
				$this->dbcon->execute_query($sqlupdate) ;
			} // for
                        $headerUrl="/soc.php?cp=inbox".(isset($_GET['opt']) ? "&opt=".$_GET['opt'] : '') ;
			header('Location: '.$headerUrl);
		} else {
			if(!isset($_SESSION['StoreID'])&&$_SESSION['SotreID']==""){
				header('Location:soc.php?cp=home');
				exit();
			}
			$pageSize = 5 ;
			$sid = $_SESSION['StoreID'] ;
			if(isset($_REQUEST['opt'])&&$_REQUEST['opt']==1){
				$strcond = " and pid<>0 ";
			}else{
				$strcond = " and pid=0 ";
			}
			if(isset($_REQUEST['appnew'])&&$_REQUEST['appnew']==1){
				$strcond .= " and status=0 ";
			}
			$_query = "select count(*) AS Counter from ".$this->table."message where  StoreID  REGEXP '(^|,)$sid(,|$)' $strcond order by date desc";
			$this->dbcon->execute_query($_query);
			$totalCount = $this->dbcon->fetch_records(true);
			$totalCount = $totalCount[0]['Counter'];

			$pager1 = new Page($totalCount, $pageSize, true, 'pageId1');

			$_query = "select * from ".$this->table."message where StoreID REGEXP '(^|,)$sid(,|$)' $strcond order by date desc".$pager1->get_limit();
			$this->dbcon->execute_query($_query);
			$inbox = $this->dbcon->fetch_records(true);

			if (!empty($inbox)) {
				foreach ($inbox as $inboxItem) {
					$inboxItem['date'] = date($datetimeFormat, $inboxItem['date']);
					$inboxItem['attachmentStatus']	=	empty($inboxItem['attachmentName'])?"No":"Yes";
					$arrTemp['inbox'][] = $inboxItem;
				}
				$arrTemp['linkStr1'] = $pager1->get_link('soc.php?cp=inbox', $pageSize);
			}



			$_query = "select count(*) AS Counter from ".$this->table."message_out where  StoreID  REGEXP '(^|,)$sid(,|$)' $strcond order by date desc";
			$this->dbcon->execute_query($_query);
			$totalCount = $this->dbcon->fetch_records(true);
			$totalCount = $totalCount[0]['Counter'];

			$pager2 = new Page($totalCount, $pageSize, true, 'pageId2');

			$_query = "select * from ".$this->table."message_out where StoreID REGEXP '(^|,)$sid(,|$)' $strcond order by date desc".$pager2->get_limit();
			$this->dbcon->execute_query($_query);
			$outbox = $this->dbcon->fetch_records(true);
			//echo $datetimeFormat;
			if (!empty($outbox)) {
				foreach ($outbox as $outboxItem) {
					$outboxItem['date'] = date($datetimeFormat,$outboxItem['date']);
					$arrTemp['outbox'][] = $outboxItem;
				}
				$arrTemp['linkStr2'] = $pager2->get_link('soc.php?cp=inbox', $pageSize);
			}
		}
		return $arrTemp;
	}

	function customersGetonAlerts() {

		$arrTemp = array();
		if($_SESSION['StoreID']=='' AND $_SESSION['level']!=2){
			header("Location:index.php");
		}

		/////////////////////////////unsubscribe//////////////////////////////////////
		$id1 = $_REQUEST['id1'] ;
		if (!empty($id1)) {
			$id1 = intval($id1);
			
			$consumer_query = "SELECT login.id As userid FROM aus_soc_bu_detail detail INNER JOIN aus_soc_login login ON login.StoreID = detail.StoreID WHERE detail.StoreID = '".$_SESSION['StoreID']."'";
			$consumer_result = $this->dbcon->getOne($consumer_query);
			
			$sql = "delete from ".$this->table."emailalert where storeid = $id1  AND userid = '".$consumer_result['userid']."' " ;
			$this->dbcon->execute_query($sql);
			
			$unfan_sql = "DELETE FROM aus_soc_fans WHERE store_id = '".$id1."' AND consumer_id = '".$_SESSION['StoreID']."'";
			$this->dbcon->execute_query($unfan_sql);
			
			$store_info = $this->getStoreInfo($id1);
			if ($store_info['attribute'] == 5) {
				/**
                 * added by Kevin.Liu, 2012-02-16
                 * point new rule
                 */
            	 include_once(SOC_INCLUDE_PATH . '/class.point.php');
                 $objPoint = new Point();
                 $objPoint->addPointRecords($id1, 'subscriber', '', true);
                //END
			}
			$msg11 =  "You have successfully unsubscribed." ;
			$urlParam = empty($_REQUEST['ctm']) ? '' : 'ctm=' . $_REQUEST['ctm'] . "&" ;
			header("Location:soc.php?cp=customers_geton_alerts&".$urlParam."msg11=$msg11");
		}
		//////////////////////////end unsubscribe////////////////////////////////////

		$consumer_query = "SELECT login.id As userid FROM aus_soc_bu_detail detail INNER JOIN aus_soc_login login ON login.StoreID = detail.StoreID WHERE detail.StoreID = '".$_SESSION['StoreID']."'";
		$consumer_result = $this->dbcon->getOne($consumer_query);
		
		$QUERY			=	"SELECT * FROM ".$this->table."emailalert WHERE userid = '".$consumer_result['userid']."'";
		$this->dbcon->execute_query($QUERY) ;
		$recordsSQ = $this->dbcon->count_records() ;
		$gridSQ = $this->dbcon->fetch_records() ;

		if($recordsSQ){
			$msg		=	"<table>";
			$msg	.=	"<tr><td>You have subscribed for the following store.<br />&nbsp;</td></tr>";
			for($i=0;$i<sizeof($gridSQ);$i++) {

				$StoreID1= $gridSQ[$i]['storeid'];
				$QUERY			=	"SELECT * FROM ".$this->table."bu_detail WHERE StoreID = $StoreID1 ";
				$result	=	$this->dbcon->execute_query($QUERY) ;
				$grid 	= 	$this->dbcon->fetch_records() ;
				$msg	.=	"<tr><td align = left>"."- ".$grid[0]['bu_name']."</td><td align=left><a href = soc.php?cp=customers_geton_alerts&id1=".$grid[0]['StoreID'].">Unsubscribe</a></td></tr>";
			}
			$msg		.=	"</table>";
		}
		else {
			$msg	=	"You have not currently subscribed to any email alerts. ";
		}

		$arrTemp['msg'] = $msg;
		$arrTemp['msg11'] = $msg11;
		return $arrTemp;
	}

	function bookAds() {
		global $cmstable;
		$arrTemp = array();
		# get descriptions
		$query = "SELECT * FROM `". $this->table ."{$cmstable}` WHERE `id` = '54'";
		$this->dbcon->execute_query($query);
		$grid5 = $this->dbcon->fetch_records();

		# get store details
		$query = "SELECT * FROM `".$this->table."bu_detail` detail left join {$this->table}login lg on lg.StoreID=detail.StoreID  WHERE detail.`StoreID` = '". $_SESSION['StoreID'] ."'";
		$this->dbcon->execute_query($query);
		$rows = $this->dbcon->fetch_records();
		$row = $rows[0];



		if ($row['renewalDate'] == '') {
			$storePaid = false;
			$msg = "Your website is not active. To activate your website please proceed with payment.";
		}
		elseif ($row['renewalDate'] < time() && 0) {
			$storePaid = false;
			$msg = "Your website has expired or you're not a paid user. To activate your website please proceed with payment.";
		}elseif($row['suspend']==1){
			$storePaid = false;
			$msg = "Your account has been suspended.<br/>Please contact <a href='soc.php?cp=contact'>SOC Exchange Admin</a> department to resolve this issue.";
		}
		else {
			$storePaid   = true;
			$companyline = $row['bu_name'];
			$companyurl = $row['bu_urlstring'];
			$addressline = $row['bu_address'] .", ". $row['bu_suburb'] .", ". getStateByName($row['bu_state']) ." ". $row['bu_postcode'];
			$phoneline   = $row['bu_area'].' '.$row['bu_phone'];
			$stateid     = $row['bu_state'];
			$bizID		 = intval($row['bu_college']);
		}

		$arrTemp['content'] = $grid5[0]['body'];
		$arrTemp['storePaid'] = $storePaid;
		$arrTemp['companyline'] = $companyline;
		$arrTemp['companyurl'] = $companyurl;
		$arrTemp['addressline'] = $addressline;
		$arrTemp['phoneline'] = $phoneline;
		$arrTemp['statename'] = getStateByName($stateid);
		$arrTemp['attribute'] = $row['attribute'];
		if ($bizID > 0) {
			$arrTemp['collegeid'] = $bizID;
			$arrTemp['collegename'] = $this->getCollegeNameBybizID($bizID);
		}
		$arrTemp['msg']		= $msg;

		return $arrTemp;
	}

	function adStatus() {
		$arrTemp = array();

		$today = date("Y-m-d");
		$state = $_POST['statepage'];
		$council = $_POST['council'];
		$pid = $_POST['featured_product'];

		/* comment by Jessee 20081225 no use code here
		$query = "SELECT COUNT(*) \n".
		"FROM `". $this->table ."ads_soc` \n".
		"WHERE `date` = '$today' \n".
		"AND `StoreID` != '0' \n".
		"AND `state` = '$state'";

		$rows = $this->dbcon->fetch_records() ;
		$total = $rows[0][0];
		var_dump($rows);
		echo $query . " total:$total";

		$totalfakes = 15;

		if ($total < $totalfakes) {
		*/
		$date        = $today;
		$StoreID     = $_SESSION['StoreID'];
		$companyline = addslashes($_POST['lineone']);
		$companyurl = addslashes($_POST['url']);
		$tagline     = addslashes($_POST['linetwo']);
		$addressline = addslashes($_POST['linethree']);
		$phoneline   = addslashes($_POST['linefour']);
		$collegeid	 = $_POST['collegeid2'] ? $_POST['collegeid2'] : 0;
		$collegename = addslashes($_POST['collegename']);
		$displaypage = $_POST['displaypage'] ? $_POST['displaypage'] : 0;

		# check if ad already submitted
		$query = "SELECT displaypage \n".
		"FROM `". $this->table ."ads_soc` \n".
		"WHERE 1=1 \n".
		"AND `StoreID` = '$StoreID' \n".
		"AND `state` = '$state' \n".
		"AND `council` = '$council' \n".
		"AND `displaypage`= '$displaypage'";

		$this->dbcon->execute_query($query);
		$rows = $this->dbcon->fetch_records(true);

		$counts = $this->dbcon->count_records();
		//echo $query . " total:$counts;page:$displaypage;row:".$rows[0]['displaypage'];
		//var_dump($rows);

		if ($counts > 0) {
			$req['msg'] = '<BR><span class="STYLE1"><b><u>Failed</u></b><BR>
						<BR>You have submitted an ad today already.</span><BR><BR><BR><BR><BR><BR>';
		}else {
			$query = "INSERT INTO `". $this->table ."ads_soc` (`date`, `storeid`, `companyline`, companyurl, `tagline`, `addressline`, `phoneline`, `state`, `council`, `pid`, `collegeid`, `college`, `displaypage`) \n".
			"VALUES ('$date', '$StoreID', '$companyline', '$companyurl', '$tagline', '$addressline', '$phoneline', '$state', '$council', '$pid', '$collegeid', '$collegename', '$displaypage')";
			$this->dbcon->execute_query($query);
			$req['msg'] = '<BR><span class="STYLE2"><b><u>Successful</u></b><BR>
							<BR>Thank you! Your advertising has been submitted.</span><BR><BR><BR><BR><BR><BR>';
		}
		// comment by Jessee 20081225, uselesse code
		/*}else {
		$req['msg'] = '
		<BR><span class="STYLE1"><b><u>Failed</u></b><br />
		<BR>There are alredy 15 available text link advertisements in this state page.</span><BR><BR><BR><BR><BR><BR>';
		}*/
		return $req;
	}

	function customersGeton() {
		global $cmstable;
		$arrTemp = array();

		if (!empty($_REQUEST)) {
			foreach ($_REQUEST as $k=>$v) {
				$arrTemp[$k] = $v;
			}
		}

		$statelist = getState3();
		$arrTemp['statelist'] = $statelist;

		$suburblist = getSubburb1($arrTemp['statelist'][0]);
		$arrTemp['suburblist'] = $suburblist;

		$countryList = getCountryList();
		$arrTemp['countrylist'] = $countryList;

		$_SESSION['logo_new'] = true; //show new logo on left

		$this->dbcon->execute_query("select * from ".$this->table."{$cmstable} where id=57") ;
		$grid5 = $this->dbcon->fetch_records() ;

		$arrTemp['content1'] = stripcslashes(html_entity_decode($grid5[0]['body']));

		$userNameC	=	isset($_REQUEST['cu_username']) ? $_REQUEST['cu_username'] : '';
		$PasswordC	=	isset($_REQUEST['cu_pass']) ? $_REQUEST['cu_pass'] :'' ;

		$arrTemp['userNameC'] = $userNameC;
		$arrTemp['PasswordC'] = $PasswordC;

		return $arrTemp;
	}

	function customersGetonSubmit() {
		global $normal_url,$email_regards,$from_site_type;
		$arrTemp = array();

		$_query	= "select * from  ".$this->table."login where user='$_POST[cu_username]'";
		$result	=	$this->dbcon->execute_query($_query) ;
		$grid = $this->dbcon->fetch_records() ;

		$data="cu_name=$_POST[cu_name]&cu_nickname=$_POST[cu_nickname]&cu_username=$_POST[cu_username]"
			. "&cuPostCode=$_POST[cu_postcode]&cu_phone=$_POST[cu_phone]&cu_pass=$_REQUEST[cu_pass]"
			. "&ctm=$_POST[ctm]&phone1=$_POST[phone1]&phone2=$_POST[phone2]&phone3=$_POST[phone3]"
			. "&phone4=$_POST[phone4]&fb_id=$_POST[fb_id]";

		if(empty($grid)) {
			//			$check_fields = array($_POST['cu_name'], $_POST['cu_username'],$_POST['cu_nickname'], $bu_desc);

			if(!get_magic_quotes_gpc()){
				$nickname = addslashes($_POST['cu_nickname']);
			}else{
				$nickname = $_POST['cu_nickname'];
			}
			$sql = "select bu_email from {$GLOBALS['table']}bu_detail where bu_nickname='$nickname' limit 1";
			$GLOBALS['dbcon']->execute_query($sql);
			$result = $GLOBALS['dbcon']->fetch_records();
			if(is_array($result)){
				if($result[0]['bu_email']!=$_POST[cu_username]){
					$msg = "The Nickname exists or invalid.";
					header("Location:soc.php?cp=customers_geton&msg=$msg&$data");
					exit();
				}
			}

			$check_fields = $_POST['cu_name']." ".$_POST['cu_username']." ".$_POST['cu_nickname']." ".$bu_desc;
			if(check_badwords($check_fields)){
				$data="cu_name=$_POST[cu_name]&cu_nickname=$_POST[cu_nickname]&cu_username=$_POST[cu_username]&cu_phone=$_POST[cu_phone]&cu_pass=$_REQUEST[cu_pass]&ctm=$_POST[ctm]&fb_id=$_POST[fb_id]";
				$msg	= "Please keep our website wholesome and clean by refraining from using vulgar words!";
				header("Location:soc.php?cp=customers_geton&msg=$msg&$data");
				exit;
			}
			/**
                        * add country field by roy 20090107
                        */
			if ($_POST['cu_country']=='13'){
                            $QUERY	= "INSERT INTO ".$this->table."bu_detail (bu_name, bu_nickname, bu_country, "
                                            . "bu_state,bu_suburb,bu_email,bu_postcode,bu_phone,attribute,ref_name,bu_address) "
                                            . "values('".$_POST['cu_name']."','".$_POST['cu_nickname']."','"
                                            .$_POST['cu_country']."','".$_POST['cu_state']."','".$_POST['bu_suburb']."','"
                                            .$_POST['cu_username']."','".$_POST['cu_postcode']."','".$_POST['phone']."',4,'".getrefname()."', '')";
			}else{
                            $QUERY	= "INSERT INTO ".$this->table."bu_detail (bu_name, bu_nickname, bu_country, bu_state,bu_suburb,bu_email,bu_postcode,attribute,ref_name,bu_address) values('".$_POST['cu_name']."','".$_POST['cu_nickname']."','".$_POST['cu_country']."','".$_POST['f_state']."','".$_POST['f_suburb']."','".$_POST['cu_username']."','".$_POST['cu_postcode']."',4,'".getrefname()."','')";
			}

			$_SESSION['PostCode']   =   $_POST['cu_postcode'];
			$_SESSION['State']      =   getStateByName($_POST['cu_state']);
			//add by roy 20090107
			$_SESSION['Country']    =   $_POST['cu_country'];
			$result                 =   $this->dbcon->execute_query($QUERY) ;

			if($result){
				$StoreID                =   mysql_insert_id();

				$_SESSION['email']      =   $_POST[cu_username];
				$_SESSION['UserName']   =   Input::StripString($_POST[cu_name]);
				$_SESSION['NickName']   =   Input::StripString($_POST[cu_nickname]);
				$_SESSION['StoreID']    =   $StoreID ;

				// Add suburb by ping.hu at 20080115
				$_SESSION['Suburb']     =   $_POST['bu_suburb'];
				$_SESSION['level']      =   2 ;
				$_SESSION['LOGIN']      =   "login";
                                $_SESSION['attribute']  =   4;

				$QUERY	="INSERT INTO ".$this->table."login (user,password,StoreID,level,attribute) VALUES('$_POST[cu_username]','$_POST[cu_pass]',$StoreID,2,4)";
				$result	=	$this->dbcon->execute_query($QUERY) ;
				$_SESSION['UserID'] = mysql_insert_id();

                                $this->facebookSaveKeyToDb();

				if($result){

                                    $query_form_site = "INSERT INTO ".$this->table."order_from (`order_review_id`,`StoreID`,`from_site`,`from_type`,`form_date`)VALUES(0,'{$StoreID}','{$from_site_type}','Registration',".time().")";
                                    $this->dbcon->execute_query($query_form_site) ;

                                    $to		=	$_REQUEST[cu_username];
                                    $_SESSION['ShopID'] = $StoreID;
                                    $from	=	"info@thesocexchange.com";
                                    $subject = "Welcome to SOCExchange.com";
                                    $this-> smarty -> assign('normal_url',$normal_url);
                                    $_POST['email_regards'] = $email_regards;
                                    $_POST[cu_name] = Input::StripString($_POST["cu_name"]);
                                    $this-> smarty -> assign('req', $_POST);
                                    $message =	$this -> smarty -> fetch('email_customers_geton.tpl');

                                    $headers  = "MIME-Version: 1.0\r\n";
                                    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                                    //$headers .= "To: $to\r\n";
                                    $headers .= "From: $from\r\n";
                                    /**
                                        * added by YangBall, 2011-05-26
                                        * add email header and footer
                                        */

                                    @mail($to, $subject, getEmailTemplate($message), fixEOL($headers));
				}else{
                                    $_SESSION['email']		=	"";
                                    $_SESSION['UserName']	=	"";
                                    $_SESSION['NickName']	=	"";
                                    $_SESSION['StoreID']	=	"";

                                    // Add suburb by ping.hu at 20080115
                                    $_SESSION['Suburb']		=	"";
                                    $_SESSION['level']		=	"" ;
                                    $_SESSION['LOGIN']	=	"";


                                    $QUERY = "delete from ".$this->table."bu_detail where StoreID=$StoreID";
                                    $result = $this->dbcon->execute_query($QUERY);
                                    $msg = "You are unsuccessful in creating a buyer account, please try again.";
                                    header("Location:soc.php?cp=customers_geton&$data&msg=$msg");
                                    exit();
				}
			}else{
				$msg = "You are unsuccessful in creating a buyer account, please try again.";
				header("Location:soc.php?cp=customers_geton&$data&msg=$msg");
				exit();
			}
		} else {
			$msg	=	urlencode($this->lang['customersGeton']['emailExists']);
			header("Location:soc.php?cp=customers_geton&$data&msg=$msg");
			exit();
		}

		return $arrTemp;
	}

	function editCustomersGeton() {
        global $countryID;
		$arrTemp = array();



		if($_SESSION['UserID']=='' AND $_SESSION['level']!=2) {
			header("Location:soc.php?cp=home");
		}

		$_query = "Select * FROM ".$this->table."state WHERE stateName='".$_SESSION['State']."'";
		$this->dbcon->execute_query($_query);
		$stateInfo = $this->dbcon->fetch_records(true);
		$stateId = $stateInfo[0]['id'];

		$selectState = getState3($stateId);
		$cities = getSuburbArray($stateId ? $stateId : 6,$_SESSION['Suburb']);
		$selectSuburb = '<option value="">Select City</option>';
		foreach ($cities as $row){
			$selectSuburb.= '<option value="'.$row['bu_suburb'].'" ';
			$selectSuburb.= $row['selected'];
			$selectSuburb.= '>'.$row['bu_suburb'].'</option>';
		}

		$_query1			=	"SELECT * FROM ".$this->table."login WHERE id=".$_SESSION['UserID']."";
		$result	=	$this->dbcon->execute_query($_query1) ;
		$grid1 = $this->dbcon->fetch_records(true) ;
		$arrTemp['grid1'] = $grid1[0];

		$_query2			=	"SELECT * FROM ".$this->table."bu_detail WHERE StoreID = '".$grid1[0]['StoreID']."'";
		$result	=	$this->dbcon->execute_query($_query2) ;
		$grid = $this->dbcon->fetch_records(true) ;
		$arrTemp['grid'] = $grid[0];

		$arrTemp['selectState'] = $selectState;
		$arrTemp['selectSuburb'] = $selectSuburb;
		$arrTemp['name'] = $arrTemp['grid']['bu_name'];
		$arrTemp['nickname'] = $arrTemp['grid']['bu_nickname'];
		$arrTemp['postcode'] = $arrTemp['grid']['bu_postcode'];
		$arrTemp['countrylist'] = getCountryList($arrTemp['grid']['bu_country']);
		$phone = $arrTemp['grid']['bu_phone'];
		$arrTemp['phone'] = $phone;
        $phones = explode('-', $phone);
        $arrTemp['phone1'] = $phones[0];
        $arrTemp['phone2'] = $phones[1];

		if ($arrTemp['grid']['bu_country']==$countryID){
			$arrTemp['cstatedisplay'] = '';
			$arrTemp['fstatedisplay'] = 'none';
		}else{
			$arrTemp['cstatedisplay'] = 'none';
			$arrTemp['fstatedisplay'] = '';
			$arrTemp['fstate'] = $arrTemp['grid']['bu_state'];
			$arrTemp['fsuburb'] = $arrTemp['grid']['bu_suburb'];
		}

		$arrTemp['user'] = $arrTemp['grid1']['user'];
		$arrTemp['password'] = $arrTemp['grid1']['password'];
		$arrTemp['StoreID'] = $arrTemp['grid1']['StoreID'];
		return $arrTemp;
	}

	function searchByProduct($statename='')
	{
		//start form data
		# search for states
		$query = "SELECT `stateName` FROM `". $this->table ."state` ORDER BY `stateName` ASC";

		$this->dbcon->execute_query($query);
		$rows = $this->dbcon->fetch_records();
		$states = array();
		foreach ($rows as $row)
		{
			$states[] = array
			(
			'state'    => $row['stateName'],
			'selected' => $_REQUEST['state_name'] == $row['stateName'] ? ' selected="selected"' : ''
			);
		}

		# search for cities
		$suburb = explode('.', $_REQUEST['selectSubburb']);
		$suburb = $suburb[0];
		$cities = getSuburbArray($statename,$suburb);

		# distance
		$distance = array( 3, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300);

		//end form data

		# get post codes
		$arrPostcode 			=	explode('.',$_REQUEST['selectSubburb']);
		$postcode				=	$arrPostcode[1];
		/*if($_REQUEST['selectDistance']==3){
			$selectDistance1	=	$postcode-2;
			$selectDistance2	=	$postcode+2;
		}elseif($_REQUEST['selectDistance']==5){
			$selectDistance1	=	$postcode-5;
			$selectDistance2	=	$postcode+5;
		}elseif($_REQUEST['selectDistance'] <50 AND $_REQUEST['selectDistance'] >5){
			$selectDistance1	=	$postcode-(ceil($_REQUEST['selectDistance']/5))-4;
			$selectDistance2	=	$postcode+(ceil($_REQUEST['selectDistance']/5))+4;
		}else{
			$selectDistance1	=	$postcode-(ceil($_REQUEST['selectDistance']/5))-5;
			$selectDistance2	=	$postcode+(ceil($_REQUEST['selectDistance']/5))+5;
		}
		$whereSQL1 = " bu_postcode BETWEEN  '$selectDistance1' AND  '$selectDistance2'";*/

		$whereSQL1 = " AND bu_postcode ".getRadiusSqlString($postcode, $_REQUEST['selectDistance'],'AUS');
		
		if (!empty($_REQUEST['bu_postcode'])) {
			$whereSQL1 = " AND bu_postcode='$_REQUEST[bu_postcode]'";
		}		

		# get state id
		$stateName = $_REQUEST['state_name'];
		$query = "SELECT id, description FROM `". $this->table ."state` WHERE `stateName` = '$stateName'";
		$this->dbcon->execute_query($query);
		$stateInfo = $this->dbcon->fetch_records(true);
		$whereSQL2 = "AND `bu_state` = '".$stateInfo[0]['id']."'";

		//$whereSQL = "SELECT StoreID FROM `". $this->table ."bu_detail` WHERE 1=1 \n".
		$whereSQL = "SELECT StoreID FROM `". $this->table ."bu_detail` WHERE 1=1\n".
		"$whereSQL1 \n".
		"$whereSQL2 \n".
		"AND `CustomerType` = 'seller' \n";
		//"AND renewalDate > ".time()." ";

		$whereSQL = "SELECT StoreID FROM {$this->table}login where StoreID in ($whereSQL) and suspend=0 ";

		$timelefts  = isset($_REQUEST['timelefts'])?$_REQUEST['timelefts']:"0";
		$buytypeState = isset($_REQUEST['buytypeState'])&&$_REQUEST['buytypeState']?$_REQUEST['buytypeState']:"'yes','no'";
		if(get_magic_quotes_gpc()){	$buytypeState = stripslashes($buytypeState);}

		if (isset($_REQUEST['category']) && $_REQUEST['category'] != '') {
			# get sub category ids
			$query = "SELECT `id` \n".
			"FROM `". $GLOBALS["table"] ."product_category` \n".
			"WHERE `fid` = '". $_REQUEST['category'] ."'";

			$tmpArr = array($_REQUEST['category']);
			$result = mysql_query($query);
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$tmpArr[] = $row['id'];
			}

			# conditions
			$timenow = time();
			$whereSQL1 = "FROM ($whereSQL) AS `stores`, `". $GLOBALS["table"] ."product` AS `product` \n".
			"LEFT JOIN `". $GLOBALS["table"] ."bu_detail` AS `detail` ON detail.StoreID = product.StoreID \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_category` AS `cat` ON cat.id = product.category \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_auction` AS `au` ON au.pid = product.pid \n".
			"LEFT JOIN `". $GLOBALS["table"] ."state` AS `st` ON st.id = detail.bu_state \n".
			" left join ". $GLOBALS["table"] ."image as t2 on product.pid=t2.pid ".
			"WHERE product.category IN ('". implode("', '", $tmpArr) ."') \n".
			"AND product.Deleted = '' \n".
			"AND NOT(detail.bu_name IS NULL) \n".
			"AND detail.CustomerType = 'seller' \n".
			"AND detail.StoreID = stores.StoreID AND IF(t2.StoreID>0, product.StoreID=t2.StoreID and t2.attrib=0 and t2.sort=0,1=1) \n".
			"AND product.is_auction in($buytypeState) \n".
                        " AND IF(product.is_auction='yes',au.starttime_stamp <= $timenow ,1=1) \n";
			if($buytypeState=="'yes'"){
				$whereSQL1 .= "AND IF(product.is_auction='yes',".($timelefts>0?"au.end_stamp-$timenow<=$timelefts and":"")." au.end_stamp-$timenow>0,1=1)";
			}else{
				$whereSQL1 .= "AND IF(product.is_auction='yes',au.end_stamp-$timenow>0,1=1)";
			}

		} else {
			# conditions
			$timenow = time();
			$whereSQL1 = "FROM ($whereSQL) AS `stores`, `". $GLOBALS["table"] ."product` AS `product` \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_category` AS `cat` ON cat.id = product.category \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_category` AS `cat2` ON cat.fid = cat2.id \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_auction` AS `au` ON au.pid = product.pid \n".
			"LEFT JOIN `". $GLOBALS["table"] ."bu_detail` AS `detail` ON detail.StoreID = product.StoreID \n".
			"LEFT JOIN `". $GLOBALS["table"] ."state` AS `st` ON st.id = detail.bu_state \n".
			" left join ". $GLOBALS["table"] ."image as t2 on product.pid=t2.pid ".
			"WHERE product.Deleted = '' \n".
			"AND NOT(detail.bu_name IS NULL) \n".
			"AND detail.CustomerType = 'seller' \n".
			"AND detail.StoreID = stores.StoreID AND IF(t2.StoreID>0, product.StoreID=t2.StoreID and t2.attrib=0 and t2.sort=0,1=1) \n".
			"AND product.is_auction in($buytypeState) \n".
                        " AND IF(product.is_auction='yes',au.starttime_stamp <= $timenow,1=1 ) \n ";
			if($buytypeState=="'yes'"){
				$whereSQL1 .= "AND IF(product.is_auction='yes',".($timelefts>0?"au.end_stamp-$timenow<=$timelefts and":"")." au.end_stamp-$timenow>0,1=1)";
			}else{
				$whereSQL1 .= "AND IF(product.is_auction='yes',au.end_stamp-$timenow>0,1=1)";
			}

		}


		# get total number
		$query = "SELECT COUNT(*) \n".$whereSQL1;
		$this->dbcon->execute_query($query);
		$total = $this->dbcon->fetch_records(true);
		$total = $total[0]['COUNT(*)'];

		//		$pageSize = PAGESIZE;
		$pageSize = 25;

		# divide pages
		$clsPage = new Page($total, $pageSize);
		switch (@$_REQUEST['sort']){
			case '1':	$sortstr = " ,product.datec DESC ";	break;
			case '2':	$sortstr = " ,product.price ASC,product.datec DESC";	break;
			case '3':	$sortstr = " ,product.price DESC,product.datec DESC";	break;
			case '4':	$sortstr = " ,product.item_name ASC,product.datec DESC";break;
			case '5':	$sortstr = " ,detail.bu_name ASC,product.datec DESC";break;
			default:	$sortstr = " ,product.datec DESC ";	break;
		}

		if (isset($_REQUEST['category']) && $_REQUEST['category'] != '') {
			# search products
			$query = "SELECT product.*,au.end_stamp,au.cur_price, t2.smallPicture,au.end_stamp, t2.picture, detail.bu_name,detail.bu_urlstring, detail. bu_suburb,st.stateName, cat.name \n".
			"$whereSQL1 \n".
			"ORDER BY ".substr($sortstr, 2)."\n".
			$clsPage->get_limit();
		} else {
			# search products
			$query = "SELECT product.is_certified,product.pid, product.StoreID, product.item_name,product.url_item_name,product.price, product.on_sale, product.image_name, product.description,product.is_auction,au.end_stamp,au.cur_price, st.stateName,  t2.smallPicture, t2.picture, '' AS flag, '' AS website_name, detail.bu_name, detail.bu_urlstring, detail. bu_suburb, cat2.name \n".
			"$whereSQL1 \n".
			"ORDER BY cat2.name ASC $sortstr\n".
			$clsPage->get_limit();
		}

		//		echo "<pre>$query</pre>";
		$this->dbcon->execute_query($query);
		$searchResult = $this->dbcon->fetch_records(true);

		$_query_cat = "SELECT DISTINCT cat2.id, cat2.name \n".
		"FROM ($whereSQL) AS `stores`, `". $this->table ."product` AS `product` \n".
		"LEFT JOIN `". $this->table ."product_category` AS `cat` ON cat.id = product.category \n".
		"LEFT JOIN `". $this->table ."product_category` AS `cat2` ON cat.fid = cat2.id \n".
		"LEFT JOIN `". $this->table ."bu_detail` AS `detail` ON detail.StoreID = product.StoreID \n".
        "LEFT JOIN `". $this->table ."product_auction` AS `au` ON au.pid = product.pid \n".
        "LEFT JOIN `". $this->table ."image` as t2 on product.pid=t2.pid\n".
		"WHERE product.Deleted = '' \n".
		"AND NOT(detail.bu_name IS NULL) \n".
		"AND detail.CustomerType = 'seller' \n".
		"AND detail.StoreID = stores.StoreID \n".
        "AND IF(t2.StoreID>0, product.StoreID=t2.StoreID and t2.attrib=0 and t2.sort=0,1=1)\n".
        "AND product.is_auction in('yes','no') \n".
        "AND IF(product.is_auction='yes',au.end_stamp-$timenow>0 AND au.starttime_stamp <=$timenow ,1=1)\n".
		"ORDER BY cat2.name ASC";
		$this->dbcon->execute_query($_query_cat);
		$categoriesResult = $this->dbcon->fetch_records(true);

		$categories = array();
		if (!empty($categoriesResult)) {
			foreach ($categoriesResult as $category) {
				if ($category['id'] && $category['name']) {
					$categories[] = array
					(
					'id'   => $category['id'],
					'name' => $category['name']
					);
				}
			}
		}

		$tmpName = '';
		$objUI = new uploadImages();
		for ($i = 0; $i < $pageSize; $i ++) {
			if ($i > 0) {
				if ($searchResult[$i]['name'] == $tmpName) {
					$searchResult[$i]['flag'] = 0;
				} else {
					$searchResult[$i]['flag'] = 1;
					$tmpName = $searchResult[$i]['name'];
				}
			} else {
				$searchResult[$i]['flag'] = 1;
				$tmpName = $searchResult[$i]['name'];
			}
			$searchResult[$i]['simage']	=	$objUI -> getDefaultImage($searchResult[$i]['smallPicture'], true, 0, 0, 4);
			$searchResult[$i]['bimage']	=	$objUI -> getDefaultImage($searchResult[$i]['picture'],false,0,0,9);
			$searchResult[$i]['limage']	=	$objUI -> getDefaultImage($searchResult[$i]['picture'],false,0,0,15);
			$searchResult[$i]['website_name'] = clean_url_name($searchResult[$i]['bu_urlstring']);
			$searchResult[$i]['description'] = strip_tags($searchResult[$i]['description']);
			$searchResult[$i]['end_stamp'] = $searchResult[$i]['end_stamp']-time()>0?$searchResult[$i]['end_stamp']-time():0;
		}

		return array
		(
		'states'   => $states,
		'cities'   => $cities,
		'distance' => $distance,
		'counter'  => $total,
		'products' => $searchResult,
		'categories' => $categories,
		'linkStr'  => $clsPage->get_link('soc.php?cp=statepage&selectSubburb='. $_REQUEST['selectSubburb'] .'&state_name='. $_REQUEST['state_name'] .'&selectDistance='. $_REQUEST['selectDistance'].'&category='.$_REQUEST['category']."&sort={$_REQUEST['sort']}&buytypeState=$buytypeState&timelefts=$timelefts", $pageSize),
		'page'		=> 'state'
		);
	}

	function featuredCategories() {

		$_query = "SELECT * FROM ".$this->table.'product_category WHERE fid=0 AND isfeatured = 1 ORDER BY fsort';
		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);

		return $result;
	}

	function getTemplateInfo() {

		$arrTemp = array();
		$arrResult = array();
//$_REQUEST['StoreID']=855715;
		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$query	=	"select * from " . $this->table. "template_details where StoreID='$StoreID'";
		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records();
		$arrResult['bgcolor'] = $this->getTemplateColor($arrTemp[0]['TemplateBGColor']);
		$arrResult['websiteiconid'] = $arrTemp[0]['WebsiteIconID'];

		if ($arrTemp[0]['WebsiteIconID'] > 0) {
			$arrResult['websiteicon'] = $this->loadIcon($arrTemp[0]['WebsiteIconID']);
		}

		return $arrResult;
	}

	function loadIcon($catid) {
		$imglocation = 'skin/red/images/icons_lg/'.$catid.'.png';
		return $imglocation;
	}

	function getCollegeNameBybizID($bizID) {
        $_query = "SELECT bizName FROM ".$this->table."universities_colleges";
        if(!empty($bizID)){
            $_query .= " WHERE bizID=".$bizID;
        }

		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);
		if ($result) {
			return $result[0]['bizName'];
		} else {
			return false;
		}
	}

	function getCollegesByState($StateID, $collegeid='') {

		$_query = "SELECT * FROM ".$this->table."universities_colleges WHERE bizState='".$StateID."' ORDER BY bizName";
		$this->dbcon->execute_query($_query);
		$result = $this->dbcon->fetch_records(true);

		$collegelist = '';
		if (is_array($result)) {
			foreach ($result as $college) {
				$collegelist .= '<option value="'.$college['bizID'].'" title="'.$college['bizName'].'"';
				if ($collegeid == $college['bizID']) {
					$collegelist .= ' selected="selected"';
				}
				$collegelist .= ' >'.$college['bizName'].' ('.$college['City'].')'.'</option>';
			}
		}
		return $collegelist;
	}

	function searchProductsbyCollege() {

		//start form data
		# search for states
		$query = "SELECT `stateName` \n".
		"FROM `". $this->table ."state` \n".
		"ORDER BY `stateName` ASC";

		$this->dbcon->execute_query($query);
		$rows = $this->dbcon->fetch_records();
		$states = array();
		foreach ($rows as $row)
		{
			$states[] = array
			(
			'state'    => $row['stateName'],
			'selected' => $_REQUEST['statename'] == $row['stateName'] ? ' selected="selected"' : ''
			);
		}

		# search for colleges
		$collegeid = $_REQUEST['collegeid2'];
		$statename = $_REQUEST['statename'];

		$timelefts  = isset($_REQUEST['timelefts'])?$_REQUEST['timelefts']:"0";
		$buytypeState = isset($_REQUEST['buytypeState'])&&$_REQUEST['buytypeState']?$_REQUEST['buytypeState']:"'yes','no'";
		if(get_magic_quotes_gpc()){	$buytypeState = stripslashes($buytypeState);}

		$stateID = getStateByID($statename);
		$query = "SELECT * \n".
		"FROM `". $this->table ."universities_colleges` WHERE bizState=$stateID  \n".
		"ORDER BY `bizName` ASC";

		$this->dbcon->execute_query($query);
		$rows = $this->dbcon->fetch_records();
		$colleges = array();
		if (!empty($rows)) {
			foreach ($rows as $row)
			{
				$colleges[] = array
				(
				'collegeid'    => $row['bizID'],
				'collegename'    => $row['bizName'],
                                'city'          => $row['City'],
				'selected' => $collegeid == $row['bizID'] ? ' selected="selected"' : ''
				);
			}

		}

		# get state id
		$stateid = getStateByID($statename);
		$whereSQL2 = "AND `bu_state` = '".$stateid."'";
        if(!empty($collegeid)){
            $whereSQL2 .= "AND `bu_college`='".$collegeid."'";
        }
		//		$whereSQL2 = "AND `bu_college`='".$collegeid."'";

		# search customers
		$whereSQL = "SELECT bu_detail.StoreID \n".
		"FROM `". $this->table ."bu_detail` AS `bu_detail`, `". $this->table ."template_details` AS `template_details` \n".
		"WHERE bu_detail.StoreID = template_details.StoreID \n".
		"$whereSQL2 \n".
		"AND `CustomerType` = 'seller' \n";

		if (isset($_REQUEST['category']) && $_REQUEST['category'] != '') {

			# get sub category ids
			$query = "SELECT `id` \n".
			"FROM `". $GLOBALS["table"] ."product_category` \n".
			"WHERE `fid` = '". $_REQUEST['category'] ."'";

			$tmpArr = array($_REQUEST['category']);
			$result = mysql_query($query);
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$tmpArr[] = $row['id'];
			}

			# conditions
			$whereSQL1 = "FROM ($whereSQL) AS `stores`, `". $GLOBALS["table"] ."product` AS `product` \n".
			"LEFT JOIN `". $GLOBALS["table"] ."bu_detail` AS `detail` ON detail.StoreID = product.StoreID \n".
			"LEFT JOIN `". $GLOBALS["table"] ."state` AS `st` ON detail.bu_state = st.id \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_category` AS `cat` ON cat.id = product.category \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_auction` AS `au` ON au.pid = product.pid \n".
			"left join ".$this->table."image as t2 on product.pid=t2.pid \n".
			"LEFT JOIN {$this->table}login as lg ON lg.StoreID=detail.StoreID ".
			"WHERE product.category IN ('". implode("', '", $tmpArr) ."') \n".
			"AND product.Deleted = '' \n".
			"AND NOT(detail.bu_name IS NULL) \n".
			"AND detail.CustomerType = 'seller' \n".
			"AND lg.suspend=0 \n".
                        " AND IF(product.is_auction='yes',au.starttime_stamp <= ".time().",1=1) ".
			" AND detail.StoreID = stores.StoreID AND IF(t2.StoreID>0, product.StoreID=t2.StoreID and t2.attrib=0 and t2.sort=0,1=1)";
		} else {
			# conditions
			$whereSQL1 = "FROM ($whereSQL) AS `stores`, `". $GLOBALS["table"] ."product` AS `product` \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_category` AS `cat` ON cat.id = product.category \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_auction` AS `au` ON au.pid = product.pid \n".
			"LEFT JOIN `". $GLOBALS["table"] ."product_category` AS `cat2` ON cat.fid = cat2.id \n".
			"LEFT JOIN `". $GLOBALS["table"] ."bu_detail` AS `detail` ON detail.StoreID = product.StoreID \n".
			"LEFT JOIN `". $GLOBALS["table"] ."state` AS `st` ON detail.bu_state = st.id \n".
			"left join ".$this->table."image as t2 on product.pid=t2.pid \n".
			"LEFT JOIN {$this->table}login as lg ON lg.StoreID=detail.StoreID ".
			"WHERE product.Deleted = '' \n".
			"AND NOT(detail.bu_name IS NULL) \n".
			"AND detail.CustomerType = 'seller' \n".
			"AND lg.suspend=0 \n".
                        " AND IF(product.is_auction='yes',au.starttime_stamp <= ".time().",1=1) ".
			" AND detail.StoreID = stores.StoreID AND IF(t2.StoreID>0, product.StoreID=t2.StoreID and t2.attrib=0 and t2.sort=0,1=1)";
		}
		$timenow = time();
		$whereSQL1 .= " AND product.is_auction in($buytypeState) ";
		if($buytypeState=="'yes'"){
			$whereSQL1 .= "AND IF(product.is_auction='yes',".($timelefts>0?"au.end_stamp-$timenow<=$timelefts and":"")." au.end_stamp-$timenow>0,1=1)";
		}else{
			$whereSQL1 .= "AND IF(product.is_auction='yes',au.end_stamp-$timenow>0,1=1)";
		}

		# get total number
		$query = "SELECT COUNT(*) \n".
		$whereSQL1;
		$this->dbcon->execute_query($query);
		$total = $this->dbcon->fetch_records(true);
		$total = $total[0]['COUNT(*)'];

		//		$pageSize = PAGESIZE;
		$pageSize =25;

		# divide pages
		$clsPage = new Page($total, $pageSize);


		switch (@$_REQUEST['sort']){
			case '1':	$sortstr = " ,product.datec DESC ";	break;
			case '2':	$sortstr = " ,product.price ASC,product.datec DESC";	break;
			case '3':	$sortstr = " ,product.price DESC,product.datec DESC";	break;
			case '4':	$sortstr = " ,product.item_name ASC,product.datec DESC";break;
			case '5':	$sortstr = " ,detail.bu_name ASC,product.datec DESC";	break;
			default:	$sortstr = " ,product.datec DESC ";	break;
		}


		if (isset($_REQUEST['category']) && $_REQUEST['category'] != '') {
			# search products
			$query = "SELECT product.*,au.end_stamp,au.cur_price, detail.bu_name,detail.bu_urlstring, detail. bu_suburb, st.stateName, cat.name, t2.smallPicture, t2.picture \n".
			"$whereSQL1 \n".
			"ORDER BY ".substr($sortstr, 2)."\n".
			$clsPage->get_limit();

		} else {
			# search products
			$query = "SELECT product.*,au.end_stamp,au.cur_price, '' AS flag, '' AS website_name, detail.bu_name,st.stateName, detail.bu_urlstring, detail. bu_suburb, cat2.name, \n".
			"t2.smallPicture, t2.picture $whereSQL1 \n".
			"ORDER BY cat2.name ASC $sortstr\n".
			$clsPage->get_limit();
		}

//		echo "<pre>$query</pre>";
		$this->dbcon->execute_query($query);
		$searchResult = $this->dbcon->fetch_records(true);

		$_query_cat = "SELECT DISTINCT cat2.id, cat2.name \n".
		"FROM ($whereSQL) AS `stores`, `". $this->table ."product` AS `product` \n".
		"LEFT JOIN `". $this->table ."product_category` AS `cat` ON cat.id = product.category \n".
		"LEFT JOIN `". $this->table ."product_category` AS `cat2` ON cat.fid = cat2.id \n".
		"LEFT JOIN `". $this->table ."bu_detail` AS `detail` ON detail.StoreID = product.StoreID \n".
        "LEFT JOIN `". $this->table ."product_auction` AS `au` ON au.pid = product.pid \n".
        "LEFT JOIN `". $this->table ."image` as t2 on product.pid=t2.pid\n".
		"WHERE product.Deleted = '' \n".
		"AND NOT(detail.bu_name IS NULL) \n".
		"AND detail.CustomerType = 'seller' \n".
		"AND detail.StoreID = stores.StoreID \n".
        "AND IF(t2.StoreID>0, product.StoreID=t2.StoreID and t2.attrib=0 and t2.sort=0,1=1)\n".
        "AND product.is_auction in('yes','no') \n".
        " AND IF(product.is_auction='yes',au.starttime_stamp <= ".time().",1=1)\n".
        "AND IF(product.is_auction='yes',au.end_stamp-$timenow>0,1=1)\n".
		"ORDER BY cat2.name ASC";

		$this->dbcon->execute_query($_query_cat);
		$categoriesResult = $this->dbcon->fetch_records(true);

		$categories = array();
		if (!empty($categoriesResult)) {
			foreach ($categoriesResult as $category) {
				if ($category['id'] && $category['name']) {
					$categories[] = array
					(
					'id'   => $category['id'],
					'name' => $category['name']
					);
				}
			}
		}

		$tmpName = '';

		$objUI = new uploadImages();
		for ($i = 0; $i < $pageSize; $i ++) {
			if ($i > 0) {
				if ($searchResult[$i]['name'] == $tmpName) {
					$searchResult[$i]['flag'] = 0;
				} else {
					$searchResult[$i]['flag'] = 1;
					$tmpName = $searchResult[$i]['name'];
				}
			} else {
				$searchResult[$i]['flag'] = 1;
				$tmpName = $searchResult[$i]['name'];
			}
			$searchResult[$i]['simage']	=	$objUI -> getDefaultImage($searchResult[$i]['smallPicture'], true, 0, 0, 4);
			$searchResult[$i]['bimage']	=	$objUI -> getDefaultImage($searchResult[$i]['picture'],false,0,0,9);
			$searchResult[$i]['limage']	=	$objUI -> getDefaultImage($searchResult[$i]['picture'],false,0,0,15);
			$searchResult[$i]['website_name'] = clean_url_name($searchResult[$i]['bu_urlstring']);
			$searchResult[$i]['description'] = strip_tags($searchResult[$i]['description']);
			$searchResult[$i]['end_stamp'] = $searchResult[$i]['end_stamp']-time()>0?$searchResult[$i]['end_stamp']-time():0;;
		}
		unset($objUI);

		return array
		(
		'states'   => $states,
		'colleges'   => $colleges,
		'distance' => $distance,
		'counter'  => $total,
		'products' => $searchResult,
		'categories' => $categories,
		'linkStr'  => $clsPage->get_link('soc.php?cp=collegeproducts&statename='. $_REQUEST['statename'] .'&collegeid2='. $collegeid.'&category='.$_REQUEST['category']."&buytypeState=$buytypeState&timelefts=$timelefts&sort={$_REQUEST['sort']}", $pageSize),
		'page'		=> 'state'
		);
	}

	function getStockQuantityByPid($pid) {

		$_query = "SELECT stockQuantity FROM ".$this->table."product WHERE pid=".$pid;
		$this->dbcon->execute_query($_query);
		$product = $this->dbcon->fetch_records(true);

		return $product[0]['stockQuantity'];
	}

	function setStockQuantityByPid($pid, $quantity) {

		$stockQuantity 	= $this->getStockQuantityByPid($pid);
		$currQuantity	= $stockQuantity-$quantity;
		//$_query = "UPDATE ".$this->table."product SET stockQuantity=".$currQuantity." WHERE pid=".$pid;
		/*if ($this->dbcon->execute_query($_query)) {

		if ($this->getStockQuantityByPid($pid) == 0) {
		$_query = "UPDATE ".$this->table."product SET on_sale='sold' WHERE pid=".$pid;
		$this->dbcon->execute_query($_query);
		}
		if (TRANSACTION_DEBUG > 0){
		echo "System will sleep ".TRANSACTION_DEBUG." seconds during the transaction operation.";
		sleep(TRANSACTION_DEBUG);
		}
		return true;*/
		$product_status = ($currQuantity==0)?'sold':'';

		$sql = "select * from ".$this->table."product where pid='$pid'";
		$this->dbcon->execute_query($sql);
		$productInfo = $this->dbcon->fetch_records();
		$productInfo = $productInfo[0];
		if($productInfo['isattachment']=="1"){
			$_query = "UPDATE ".$this->table."product SET stockQuantity=1 WHERE pid=".$pid;
			$currQuantity = $stockQuantity;
		}else{
			$_query = "UPDATE ".$this->table."product SET stockQuantity=stockQuantity-".$quantity.(($product_status=='sold')?",on_sale='sold'":'')." WHERE pid=".$pid;
		}
		if ($this->dbcon->execute_query($_query)) {
			// comment by jessee 20081120, move the operation to last sql execution
			//			if ($this->getStockQuantityByPid($pid) == 0) {
			//				$_query = "UPDATE ".$this->table."product SET on_sale='sold' WHERE pid=".$pid;
			//				$this->dbcon->execute_query($_query);
			//			}
			// added by jessee 20081120, debug mode
			if (TRANSACTION_DEBUG > 0){
				echo "System will sleep ".TRANSACTION_DEBUG." seconds during the transaction operation.";
				echo $_query;
				sleep(50);
			}/**/
			if ($this->getStockQuantityByPid($pid) == $currQuantity) {
				return true;
			}else{
				return false;
			}
		} else {
			return false;
		}
	}

	function payProductByGoogle($ref_id) {
		require_once('./include/googlecheckout/library/googlecart.php');
		require_once('./include/googlecheckout/library/googleitem.php');
		require_once('./include/googlecheckout/library/googleshipping.php');
		require_once('./include/googlecheckout/library/googletax.php');

		//get google merchant_id & merchant_key by StoreID
		$_query = "SELECT google_merchantid, google_merchantkey FROM ".$this->table."bu_detail WHERE StoreID=".$_REQUEST['StoreID'];
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		$merchant_id = $arrTemp[0]['google_merchantid'];
		$merchant_key = $arrTemp[0]['google_merchantkey'];
		$server_type = GOOGLE_CHECKOUT_SERVER_TYPE;
		$currency = "USD";

		$additionalInfo = 'buyerId='.$_SESSION['ShopID'];
		$additionalInfo .= ',pid='.$_REQUEST['pid'];
		$additionalInfo .= ',deliveryMethod='.$_REQUEST['deliveryMethod'];
		$additionalInfo .= ',ref_id='.$ref_id;

		$cart = new GoogleCart($merchant_id, $merchant_key, $server_type,$currency);
                $cart->SetContinueShoppingUrl(PAYPAL_SITEURL);
		/*$cart->SetEditCartUrl(PAYPAL_SITEURL."soc.php?cp=buy&StoreID={$_REQUEST['StoreID']}&pid={$_REQUEST['pid']}");
		$query = "SELECT * FROM {$this->table}product where pid='{$_REQUEST['pid']}'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if($result[0]['isattachment']==1){
			$downurl = "&downable=".urlencode("/soc.php?cp=dispro&StoreID={$_REQUEST['StoreID']}&proid={$_REQUEST['pid']}");
		}
		$flag	=	"Payment is successful.";
		$cart->SetContinueShoppingUrl(PAYPAL_SITEURL."soc.php?cp=message&StoreID={$_REQUEST['StoreID']}&msg=".$flag.$downurl);*/
		$items = new GoogleItem($_REQUEST['item_name'], '', $_REQUEST['quantity'], $_REQUEST['price']);
		$items->SetMerchantPrivateItemData($additionalInfo);
		$cart->AddItem($items);

		$shippingMethod = $this -> lang['Delivery'][strval($_REQUEST['deliveryMethod'])];
		$shipping = new GoogleFlatRateShipping('Shipping Method', $_REQUEST['shipping']);
		$cart->AddShipping($shipping);

		list($status, $error) = $cart->CheckoutServer2Server();
		if ($status != '200'){
			echo "<script> alert('The google checkout account of this seller is invalid or expired. Please try other payment methods or contact the seller.');history.go(-1);</script>";
			exit;
		}
	}

	function payOrderByGoogle($OrderID, $cartid) {
		require_once(SOC_INCLUDE_PATH.'/googlecheckout/library/googlecart.php');
		require_once(SOC_INCLUDE_PATH.'/googlecheckout/library/googleitem.php');
		require_once(SOC_INCLUDE_PATH.'/googlecheckout/library/googleshipping.php');
		require_once(SOC_INCLUDE_PATH.'/googlecheckout/library/googletax.php');

		require_once(SOC_INCLUDE_PATH.'/class.cart.php');

		$cart = new Cart();
		$cart_info = $cart->getGoods();
		$cart->delCart();
		if (empty($cart_info['can_buy_list']) || !is_array($cart_info['can_buy_list'])) {
			return ;
		}

		//get google merchant_id & merchant_key by StoreID
		$_query = "SELECT google_merchantid, google_merchantkey FROM ".$this->table."bu_detail WHERE StoreID=".$_REQUEST['StoreID'];
		$this->dbcon->execute_query($_query);
		$arrTemp = $this->dbcon->fetch_records(true);
		$merchant_id = $arrTemp[0]['google_merchantid'];
		$merchant_key = $arrTemp[0]['google_merchantkey'];
		$server_type = GOOGLE_CHECKOUT_SERVER_TYPE;
		$currency = "USD";

		$additionalInfo = 'buyerId='.$_SESSION['ShopID'];
		$additionalInfo .= ',OrderID='.$_REQUEST['OrderID'];
		$additionalInfo .= ',deliveryMethod='.$_REQUEST['deliveryMethod'];
		$additionalInfo .= ',ref_id='.$_REQUEST['OrderID'];

		$cart = new GoogleCart($merchant_id, $merchant_key, $server_type,$currency);
        $cart->SetContinueShoppingUrl(PAYPAL_SITEURL);

        foreach ($cart_info['can_buy_list'] as $goods) {
        	$items = new GoogleItem($goods['item_name'], '', $goods['quantity'], $goods['price']);
			$items->SetMerchantPrivateItemData($additionalInfo);
			$cart->AddItem($items);
        }


		$shippingMethod = $this -> lang['Delivery'][strval($_REQUEST['deliveryMethod'])];
		$shipping = new GoogleFlatRateShipping('Shipping Method', $_REQUEST['shipping']);
		$cart->AddShipping($shipping);

		list($status, $error) = $cart->CheckoutServer2Server();
		if ($status != '200'){
			echo "<script> alert('The google checkout account of this seller is invalid or expired. Please try other payment methods or contact the seller.');history.go(-1);</script>";
			exit;
		}
	}

	/**
	 * get ad of category big
	 *
	 * @param int $cgid
	 * @return array
	 */
	function getProCategoryAd($cgid = 0){
		$arrResult = null;

		$cgid = $cgid ? $cgid : $_REQUEST['id'];

		if ($cgid >0) {
			$_query = "select fid from ".$this->table."product_category where id='$cgid'";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			if ($arrTemp[0]['fid'] > 0) {
				$cgid = $arrTemp[0]['fid'];
				$arrResult['categoryFID']		=	$cgid;
			}

			$_query = "select * from ".$this->table."ad where cgid=$cgid and state=0";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			if (is_array($arrTemp)) {
				$arrResult['categoryCMSRightTop'] = $this-> displayPageFromCMS(62);

				$arrResult['categoryCMSTop'] = $this->__StrReplace($arrTemp[0]['rss_content'],false);
				$arrResult['categoryCMSLeft'] = $this->__StrReplace($arrTemp[0]['ad_left'],false);
				$arrResult['categoryCMSRightDown'] = $this->__StrReplace($arrTemp[0]['ad_right'],false);
				$arrResult['categoryCMSDown'] = $this->__StrReplace($arrTemp[0]['ad_bottom'],false);

				$arrResult['categoryID']		=	$_REQUEST['id'];
				$arrResult['categoryCMS']		= 	true ;
			}
		}


		return $arrResult;
	}

	/**
	 * get article head by category
	 *
	 * @param int $cgid
	 * @param int $intMaxLine
	 * @return array
	 */
	function getProCategoryArticleHead($cgid=0,$intMaxLine=2){
		$arrResult = null;
		$cgid = $cgid ? $cgid : $_REQUEST['id'];

		if ($cgid >0) {
			$_query = "select fid,name from ".$this->table."product_category where id='$cgid'";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			if ($arrTemp[0]['fid'] > 0) {
				$cgid = $arrTemp[0]['fid'];
			}
			$arrResult['categoryName']		=	$arrTemp[0]['name'];
			$arrResult['categoryFID']		=	$cgid;



			$_query = "select id,cgid,title,context,datec from ".$this->table."article ".
			"where cgid='$cgid' and state=0 order by datec desc limit 0,$intMaxLine";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			if (is_array($arrTemp)) {
				$arrResult['list'] 				= 	$arrTemp;
				$arrResult['categoryID']		=	$_REQUEST['id'];
				$arrResult['categoryART']		= 	true ;
			}
		}

		return $arrResult;
	}

	/**
	 * get article content by category
	 *
	 * @param int $id
	 * @return array
	 */
	function getProCategoryArticle($id=0){
		$arrResult = null;
		$id = $id ? $id : $_REQUEST['id'];
		$cgid = $_REQUEST['cgid'];

		if ($id) {
			$_query = "select fid,name from ".$this->table."product_category where id='$cgid'";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			if ($arrTemp[0]['fid'] > 0) {
				$cgid = $arrTemp[0]['fid'];
			}
			$arrResult['categoryName']		=	$arrTemp[0]['name'];
			$arrResult['categoryFID']		=	$cgid;

			$_query = "select * from ".$this->table."article where id='$id' and state=0 ";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			if (is_array($arrTemp)) {
				$arrResult['list'] 				= 	$arrTemp[0];
				$arrResult['categoryID']		=	$_REQUEST['id'];
				$arrResult['categoryART']		= 	true ;
			}
		}
		return $arrResult;
	}

	function getPaypalInfo() {

		$paypalInfo = array();

		if ($this->paypal_info['paypal_mode'] == 0) {
			$paypalInfo['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr/';
			$paypalInfo['adaptivepayments_url'] = 'https://svcs.sandbox.paypal.com/AdaptivePayments/Pay';
		} else {
			$paypalInfo['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr/';
			$paypalInfo['adaptivepayments_url'] = 'https://svcs.paypal.com/AdaptivePayments/Pay';
		}
		$paypalInfo['paypal_email']	= $this->paypal_info['paypal_email'];
		$paypalInfo['paypal_siteurl'] = PAYPAL_SITEURL;

		return $paypalInfo;
	}

	function getIPGInfo(){
		$arrResult = array();

		$arrResult['paypal_url'] = SOC_HTTPS_HOST.'soc.php?act=ipg';
		return $arrResult;
                /*
		if ($this->paypal_info['paypal_mode'] == 0) {
			$paypalInfo['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr/';
		} else {
			$paypalInfo['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr/';
		}
		$paypalInfo['paypal_email']	= $this->paypal_info['paypal_email'];;
		$paypalInfo['paypal_siteurl'] = PAYPAL_SITEURL;
		return $paypalInfo;*/

	}

	/**
	 * get png filename from dir
	 *
	 * @return array
	 */
	function getImageOfStoreType(){
		$arrResult	= array();
		$path = ROOT_PATH . "skin/red/images/icons_lg";

		foreach( glob( $path . '/*') as $item ){
			if( !is_dir( $item ) ){
				$arrResult[]['id'] = basename( $item , '.png' );
			}
		}
		if (is_array($arrResult)) {
			asort ($arrResult);
		}

		return $arrResult;
	}

	/**
	 * @title	: getProductByID
	 * Fri Dec 26 03:53:54 GMT 2008
	 * @input	: string Product type, int Product ID, string field name in database table
	 * @output	: the field value
	 * @description	: query product table and return the request filed value
	 * @author	: Jessee <support@infinitytesting.com.au>
	 * @version	: V1.0
	 *
	 */
	function getProductValueByID($type,$id,$field){
		$table = 'product';
		switch ($type){
			case 'estate':
				$table = 'product_realestate';
				break;
			case 'auto':
				$table = 'product_automotive';
				break;
			case 'job':
				$table = 'product_job';
				break;
		}
		$sql = "select $field from ".$this->table."$table where pid=$id";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records();
		return ($result[0])?$result[0]:false;
	}

	/**
	 * @title	: getSlidingImages
	 * Wed Dec 24 08:24:52 GMT 2008
	 * @input	: int $StoreID, int Product ID, string selected Image url, string page type
	 * @output	: array images information of product
	 * @description	: get the images list of the specified product
	 * @author	: Jessee <support@infinitytesting.com.au>
	 * @version	: V1.0
	 *
	 */
	function getSlidingImages($StoreID,$pid,$url,$page='estate'){
		$objUI	=	new uploadImages();
		$images	=	$objUI -> getDisplayImage($page, $StoreID, $pid);
		$big = array();
		$small = array();
		$count = $images['imagesCount'];
		//echo "<pre>";
		if ($images['mainImage']){
			foreach ($images['mainImage'] as $row){
				if ($row['sname']['text']!='/images/79x79.jpg'){
					$big[] = $row['bname'];
					$small[] = $row['sname'];
				}
			}
		}
		if ($images['subImage']){
			foreach ($images['subImage'] as $row){
				if ($row['sname']['text']!='/images/79x79.jpg'){
					$big[] = $row['bname'];
					$small[] = $row['sname'];
				}
			}
		}
		if ($images['planImage']){
			foreach ($images['planImage'] as $row){
				if ($row['sname']['text']!='/images/79x79.jpg'){
					$big[] = $row['bname'];
					$small[] = $row['sname'];
				}
			}
		}
		$select = 1;
		for($i=0;$i<count($small);$i++){
			if ($small[$i]['height']>0){
				$small[$i]['width']  = round(79 * $small[$i]['width'] / $small[$i]['height'],0) ;
				$small[$i]['height']	= 79 ;
			}else{
				$small[$i]['width'] = $small[$i]['height'] = 79;
			}
			if ($small[$i]['text']==$url){
				$select = $i+1;
			}
		}

		$info = $this->getProductValueByID('estate',$pid,'location,suburb');
		$address = $info['location'];
		$suburb_id = $info['suburb'];
		$sql = "select * from ".$this->table."suburb where suburb_id=".$suburb_id;
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records();

		$list = array(
		'count'=>$count,
		'big'=>$big,
		'small'=>$small,
		'select'=>$select,
		'address'=>$address,
		'state'=>$result[0]['state'],
		'suburb'=>$result[0]['suburb']
		);
		//var_dump($small);
		//exit;
		return $list;
	}


	function getSlidingWishImages($StoreID,$pid,$url,$page='estate'){
		$objUI	=	new uploadImages();
		$images	=	$objUI -> getDisplayWishlistImage($page, $StoreID, $pid);
		$big = array();
		$small = array();
		$count = $images['imagesCount'];
		//echo "<pre>";
		if ($images['mainImage']){
			foreach ($images['mainImage'] as $row){
				if ($row['sname']['text']!='/images/79x79.jpg'){
					$big[] = $row['bname'];
					$small[] = $row['sname'];
				}
			}
		}
		if ($images['subImage']){
			foreach ($images['subImage'] as $row){
				if ($row['sname']['text']!='/images/79x79.jpg'){
					$big[] = $row['bname'];
					$small[] = $row['sname'];
				}
			}
		}
		if ($images['planImage']){
			foreach ($images['planImage'] as $row){
				if ($row['sname']['text']!='/images/79x79.jpg'){
					$big[] = $row['bname'];
					$small[] = $row['sname'];
				}
			}
		}
		$select = 1;
		for($i=0;$i<count($small);$i++){
			if ($small[$i]['height']>0){
				$small[$i]['width']  = round(79 * $small[$i]['width'] / $small[$i]['height'],0) ;
				$small[$i]['height']	= 79 ;
			}else{
				$small[$i]['width'] = $small[$i]['height'] = 79;
			}
			if ($small[$i]['text']==$url){
				$select = $i+1;
			}
		}

		$info = $this->getProductValueByID('estate',$pid,'location,suburb');
		$address = $info['location'];
		$suburb_id = $info['suburb'];
		$sql = "select * from ".$this->table."suburb where suburb_id=".$suburb_id;
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records();

		$list = array(
		'count'=>$count,
		'big'=>$big,
		'small'=>$small,
		'select'=>$select,
		'address'=>$address,
		'state'=>$result[0]['state'],
		'suburb'=>$result[0]['suburb']
		);
		//var_dump($small);
		//exit;
		return $list;
	}
	/**
	 * check purview of visit
	 *
	 * @param array $template
	 */
	function checkPurviewOfVisit($StoreID, $template,$proid){
		global $dbcon;
		if ( $template['tpl_type']== 4 && ($template['subAttrib']== 2 || $template['subAttrib']== 3) ){
			if ($StoreID != $GLOBALS['samplesiteid'][9]){
				if(!(($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2)) ||
				$_SESSION['attribute'] == 3 && $template['StoreID']== $_SESSION['ShopID'])) {
					if($proid==0){
						$query = "SELECT t1.ispub,t1.category FROM {$GLOBALS['table']}product_job AS t1
						LEFT JOIN {$GLOBALS['table']}product_sort AS st2 ON t1.sector = st2.id
						LEFT JOIN {$GLOBALS['table']}product_sort AS st3 ON t1.subSector = st3.id
						LEFT JOIN {$GLOBALS['table']}state AS st4 ON t1.state = st4.id
						LEFT JOIN {$GLOBALS['table']}suburb AS st5 ON t1.suburb = st5.suburb_id
						WHERE t1.StoreID = '{$StoreID}' AND t1.deleted =0 AND t1.enabled =1
						AND ((t1.datePosted <= '2009-03-17' OR t1.datePosted = '0000-00-00')
						AND (t1.closingDate >= '2009-03-17' OR t1.closingDate = '0000-00-00'))
						ORDER BY t1.featured DESC , t1.datec DESC , t1.item_name LIMIT 0 , 1";
					}else{
						$query = "SELECT ispub,category FROM {$GLOBALS['table']}product_job where pid = {$proid}";
					}
					$dbcon->execute_query($query);
					$result = $dbcon->fetch_records(true);
					if($result[0]['ispub']!=1&&$result[0]['category']==2){
						include_once (SOC_INCLUDE_PATH.'/class.login.php');
						$objLogin = new login();
						if((($proid==0&&$template['TemplateName']=='job-c') || $proid!=0) && !$objLogin -> checkLogin()){
						echo '<script language="javascript"> alert("Sorry. You don\'t have permission to access this page.");
					window.location.href="/index.php";
					</script>';
						exit;
						}
					}
				}
			}
		}
	}


	function mulitopeartion($act,$arrayid=array()){
		if(count($arrayid)>0){
			$expstr = implode(',',$arrayid);
			$sql = "";
			switch ($act){
				case 'delete':
					/*$sql = "select smallPicture,picture from {$this->table}image where pid in($expstr);";
					$this->dbcon->execute_query($sql);
					$result = $this->dbcon->fetch_records(true);
					$sql = "delete from {$this->table}image where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){
						if(is_array($result)){
							foreach ($result as $pass){
								if($pass['smallPicture']){
									unlink(ROOT_PATH.$pass['smallPicture']);
								}
								if($pass['picture']){
									unlink(ROOT_PATH.$pass['picture']);
								}
							}
						}
						$sql = "delete from {$this->table}product where pid in($expstr);";
						if($this->dbcon->execute_query($sql)){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}*/
					$sql = "update {$this->table}product  set `deleted` = 'YES' where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){
			            /**
				         * added by Kevin.Liu, 2012-02-16
				         * reduce point new rule
				         */
				    	 include_once(SOC_INCLUDE_PATH . '/class.point.php');
				         $objPoint = new Point();
				         foreach ($arrayid as $pid) {
				         	$objPoint->addPointRecords($_SESSION['StoreID'], 'product', $pid, true);
				         }

				        //END

						return true;
					}else{
						return false;
					}
					break;
				case 'publish':
					$sql = "update {$this->table}product  set `isfeatured` = '1' where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){
						return true;
					}else{
						return false;
					}
					break;
				case 'unpublish':
					$sql = "update {$this->table}product set `isfeatured` = '0' where pid in($expstr);";
					if($this->dbcon->execute_query($sql)){
						return true;
					}else{
						return false;
					}
					break;
				default:
					break;
			}

		}else{
			return false;
		}
	}


	/**
	 * refer fun start
	 */
	function getdetailinfo($sid){
		$sql = "select ref_name from {$this->table}bu_detail where StoreID='{$sid}'";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		return $result[0]['ref_name'];
	}
	function gettotalRefAmount($sid){
		$sql = "select sum(round(amount,2)) as total from {$this->table}referrer where StoreID={$sid} and type=0";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		$total_amount = $result[0]['total']?$result[0]['total']:"0.00";
		return $total_amount;
	}

	function gettotalCommis($sid){
		$sql = "select sum(round(amount,2)) as sentamount from {$this->table}referrer where StoreID={$sid} and type=2";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		$com_amount =  abs($result[0]['sentamount']?$result[0]['sentamount']:"0.00");
		return $com_amount;
	}

	function getRefconfig($sid=""){
		if($sid!=""){
			$refname = $this->getdetailinfo($sid);
			$sql = "select * from {$this->table}refconfig where ReferrerID in('{$refname}','0')";
		}else{
			$sql = "select * from {$this->table}refconfig where ReferrerID='0'";
		}
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		$configs = array();
		if(count($result)>1){
			foreach ($result as $pass){
				if($pass['ReferrID']!="0"){
					$configs = $pass;
				}
			}
		}else{
			$configs = $result[0];
		}
		return $configs;
	}
	function saveRefconfig($configs){
		$sql = "update {$this->table}refconfig set `min_commission`='{$configs['min_commission']}',`min_refer`='{$configs['min_refer']}',`percent`='{$configs['percent']}' where id=1";
		return $this->dbcon->execute_query($sql);
	}

	function getRefUserStatus($sid){
		$sql = "select * from {$this->table}refer_status where StoreID={$sid}";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		if(is_array($result)){
			return $result[0];
		}
		return false;
	}

	function getBuyerRefer($sid,$field="",$order="asc",$curpage=1,$isadmin=0){
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;

		$arrResult = array();
		$sql = "select count(*) as num,sum(round(amount,2)) as total from {$this->table}referrer where StoreID={$sid} and type=0";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		$arrResult['total_ref']=$result[0]['num'];
		$arrResult['total_amount'] = $result[0]['total']?$result[0]['total']:"0.00";

		$arrResult['com_amount'] =  $this->gettotalCommis($sid);
		$arrResult['earn_amount'] = ($earn = $arrResult['total_amount']-$arrResult['com_amount'])?$earn:"0.00";

		if($field!=""){
			switch ($field){
				case 'details':
					$orders = " order by $field $order ";
					break;
				case 'addtime':
					$orders = " order by $field $order ";
					break;
				case 'amount':
					$orders = " order by amount $order ";
					break;
				case 'balance':
					$orders = " order by balance $order ";
					break;
				default:
					$orders = " order by addtime DESC ";
					break;
			}

		}else{
			$orders = " order by addtime DESC ";
		}

		$query = "select count(*) from {$this->table}referrer where StoreID={$sid} $orders";
		$this->dbcon->execute_query($query);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0][0];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;

		$sql = "select * from {$this->table}referrer where StoreID={$sid} $orders limit $start,$perPage";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		$arrResult['refer_list']= $result;

		$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_Referpage(\'%d\',\''.$sid.'\',\''.$field.'\',\''.$order.'\','.$isadmin.');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
		$pager = Pager::factory($params);
		$arrResult['links'] 		= $pager->getLinks();
		$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];

		return $arrResult;
	}

	function sendrequest($sid){
		global $email_regards;
		$message = "";
		$total_amount = $this->gettotalRefAmount($sid);
		$com_amount =  $this->gettotalCommis($sid);
		$Refconfig = $this->getRefconfig($sid);
		$min_amount =  $Refconfig['min_commission'];
		if($_REQUEST['checktype']=="1"){
			if(trim($_REQUEST['name'])==""){
				$message = "The name is required.";
				return $message;
			}
			if(trim($_REQUEST['address'])==""){
				$message = "The address is required.";
				return $message;
			}
		}else{
			if(trim($_REQUEST['pname'])==""){
				$message = "The paypal account is required.";
				return $message;
			}
		}
		if(round(round($total_amount,2)-round($com_amount,2),2)<round($min_amount,2)){
			$message = "You don't have enough money for request.";
			return $message;
		}
		$sql = "select * from {$this->table}refer_status where StoreID={$sid}";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		$reqs = is_array($result)?count($result):0;
		if($reqs>0&&$result[0]['status']==1){
			$message = "You have sent a request, please wait for admin response.";
			return $message;
		}
		$checktype = $_REQUEST['checktype'];
		if($checktype=="1"){
			$name = $_REQUEST['name'];
			$address = $_REQUEST['address'];
			$ctype = "Cheque";
		}else{
			$name = $_REQUEST['pname'];
			$address = "";
			$ctype = "Paypal payment";
		}
		if(!get_magic_quotes_gpc()){
			$name = addslashes($name);
			$address = addslashes($address);
		}
		$balance = round(round($total_amount,2)-round($com_amount,2),2);
		$sql = "insert into {$this->table}referrer (`StoreID`,`addtime`,`details`,`amount`,`type`,`checktype`,`name`,`address`,`balance`)values('{$sid}','".time()."','{$ctype} requested','','1','{$checktype}','{$name}','{$address}','$balance')";
		$bl = $this->dbcon->execute_query($sql);
		if($bl){
			if($reqs>0){
				$sql = "update {$this->table}refer_status set status='1',details=CONCAT(details,'".date('Y-m-d H:i:s')." : send request\n'),lastupdate='".time()."',paymethod='{$checktype}' where StoreID='{$sid}'";
			}else{
				$sql = "insert into {$this->table}refer_status (`StoreID`,`status`,`addtime`,`lastupdate`,`details`,`paymethod`,`total_ref`,`total_income`,`cur_income`,`req_income`)values('{$sid}','1','".time()."','".time()."','".date('Y-m-d H:i:s')." : Send request\n','$checktype','0','0','0','0')";
			}

			$this->dbcon->execute_query($sql);
			$message = "Your payment request has been sent. You will receive payment into your Paypal account within 10 days.";

			/*send email*/
			$sql = "select bu_nickname,bu_email from {$this->table}bu_detail where StoreID='{$sid}' limit 1";
			$this->dbcon->execute_query($sql);
			$result = $this->dbcon->fetch_records(true);
			$arrParams = array('reftype'=>'request',
							   'Subject'=>'The SOC Exchange member requested a payment',
							   'fromName'=>$result[0]['bu_nickname'],
							   'From'=>$result[0]['bu_email'],
							   'nickname'=>$result[0]['bu_nickname'],
							   'checktype'=>$checktype,
							   'name'=>$name,
							   'address'=>$address,
							   'webside_link'=>	'http://'.$_SERVER['HTTP_HOST'],
							   'email_regards'=> $email_regards);
			$objEmail	=	new emailClass();
			$objEmail -> send($arrParams,'referrer_email.tpl',(THE_PORT=='3007' ? true:false));
			unset($objEmail);

			return $message;
		}else{
			$message = "Faild to send request.";
			return $message;
		}
	}
	function sendcheque($sid,$amount){
		$message = "";
		$total_amount = $this->gettotalRefAmount($sid);
		$com_amount =  $this->gettotalCommis($sid);
		$Refconfig = $this->getRefconfig($sid);
		$min_amount =  $Refconfig['min_commission'];

		if(round(round($amount,2)-round($min_amount,2),2)<0){
			$message = "Please input amount which is not less than minimum earn commission.";
			return $message;
		}
		if(round(round($total_amount,2)-round($com_amount,2)-round($amount,2),2)<0){
			$message = "This user doesn't have enough money.";
			return $message;
		}
		$sql = "select * from {$this->table}referrer where StoreID='{$sid}' and type=1 order by id DESC limit 1";
		$this->dbcon->execute_query($sql);
		$refrs = $this->dbcon->fetch_records(true);
		if($refrs[0]['checktype']=='1'){
			$type = "Cheque";
		}else{
			$type = "Paypal payment";
		}

		$sql = "select * from {$this->table}refer_status where StoreID={$sid}";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		$reqs = is_array($result)?count($result):0;
		if($reqs>0&&$result[0]['status']==2){
			$message = "The payment has been sent already and you can't send the payment again.";
			return $message;
		}else if($reqs==0){
			$message = "You can't send the payment because there is no payment request.";
			return $message;
		}
		$balance = round(round($total_amount,2)-round($com_amount,2),2)-$amount;
		$sql = "insert into {$this->table}referrer (`StoreID`,`addtime`,`details`,`amount`,`type`,`checktype`,`name`,`address`,`balance`)values('{$sid}','".time()."','{$type} sent','-{$amount}','2','{$refrs[0]['checktype']}','{$refrs[0]['name']}','{$refrs[0]['address']}','{$balance}')";
		$bl = $this->dbcon->execute_query($sql);
		if($bl){
			if($reqs>0){
				$sql = "update {$this->table}refer_status set status='2',details=CONCAT(details,'".date('Y-m-d H:i:s')." : send $type\n'),lastupdate='".time()."',`paymethod`='0',`cur_income`=cur_income-{$amount},`req_income`=req_income+$amount  where StoreID='{$sid}'";
			}else{
				$sql = "insert into {$this->table}refer_status (`StoreID`,`status`,`addtime`,`lastupdate`,`details`,`paymethod`,`total_ref`,`total_income`,`cur_income`,`req_income`)values('{$sid}','1','".time()."','".time()."','".date('Y-m-d H:i:s')." : Send {$type}\n','0','0','0','0','0')";
			}
			$this->dbcon->execute_query($sql);
			$message = "Sent ".strtolower($type)." succesfully.";

			$sql = "select bu_nickname,bu_email from {$this->table}bu_detail where StoreID='{$sid}' limit 1";
			$this->dbcon->execute_query($sql);
			$result = $this->dbcon->fetch_records(true);

			$subject = "The SOC Exchange has sent you the payment";
			$arrParams = array('reftype'=>'send',
							   'To'=>$result[0]['bu_email'],
							   'Subject'=>$subject,
							   'amount'=>$amount,
							   'nickname'=>$result[0]['bu_nickname'],
							   'paidtype'=>$type,
							   'webside_link'=>	'http://'.$_SERVER['HTTP_HOST']);
			$objEmail	=	new emailClass();
			$objEmail -> send($arrParams,'../referrer_email.tpl');
			unset($objEmail);

			$arrParams['notfull'] = true;
			$this->smarty->assign('req',$arrParams);
			$content = $this->smarty->fetch('../referrer_email.tpl');
			$query		=	"insert into ".$this->table."message(subject,message,StoreID,date,emailaddress,fromtoname) values('$subject','".addslashes($content)."','$sid','".time()."','{$result[0]['bu_nickname']}','SYSTEM')";
			$this->dbcon->execute_query($query);

			return $message;
		}else{
			$message = "Faild to send ".strtolower($type).".";
			return $message;
		}
	}

	function setrefEarn($nkname,$amount,$nickname){
		if(!get_magic_quotes_gpc()){
			$nkname = addslashes($nkname);
		}
		if($nkname==""){
			return false;
		}
		$sql = "select StoreID from {$this->table}bu_detail where ref_name='{$nkname}' limit 1";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records(true);
		if(!is_array($result)){
			return false;
		}
		$sid = $result[0]['StoreID'];
		$configs = $this->getRefconfig($sid);
		if($amount<$configs['min_refer']){
			return false;
		}
		
		$eranamount = floatval($amount*$configs['percent']/100);
		$eranamount = (ceil($eranamount/5) * 5);
		$balance = round(round($this->gettotalRefAmount($sid),2)-round($this->gettotalCommis($sid),2)+$eranamount,2);
		$strreq  = "Referred {$nickname} to join for $".$amount;
		$sql = "insert into {$this->table}referrer (`StoreID`,`addtime`,`details`,`amount`,`type`,`ref_amount`,`balance`)values('{$sid}','".time()."','{$strreq}','{$eranamount}','0','$amount','{$balance}')";
		if($this->dbcon->execute_query($sql)){
			$sql = "select * from {$this->table}refer_status where StoreID={$sid}";
			$this->dbcon->execute_query($sql);
			$result = $this->dbcon->fetch_records(true);
			$reqs = is_array($result)?count($result):0;
			if($reqs>0){
				$sql = "update {$this->table}refer_status set details=CONCAT(details,'".date('Y-m-d H:i:s')." : referred {$nickname} to join for ${$amount} \n'),lastupdate='".time()."',`total_ref`=total_ref+1,`total_income`=total_income+{$eranamount},`cur_income`=cur_income+{$eranamount} where StoreID='{$sid}'";
			}else{
				$sql = "insert into {$this->table}refer_status (`StoreID`,`status`,`addtime`,`lastupdate`,`details`,`paymethod`,`total_ref`,`total_income`,`cur_income`,`req_income`)values('{$sid}','0','".time()."','".time()."','".date('Y-m-d H:i:s')." : referred {$nickname} to join for {$month} months\n','0','1','{$eranamount}','{$eranamount}','0')";
			}
			if($this->dbcon->execute_query($sql)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function sendemailintoDB($arraySetting){
		global $dbcon;
		return $dbcon->insert_query($this->table."referemail_list",$arraySetting);
	}

	function getrefermailinfo($msgid){
		global $dbcon;
		$query = "SELECT * FROM {$this->table}referemail_list where id='$msgid'";
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);
		return $result[0];
	}

	function getEmailList($sid,$curpage = 1){
		global $dbcon;
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$arrResult = array();
		$sql = "SELECT count(*) as num from {$this->table}referemail_list where StoreID='{$sid}'";
		$dbcon->execute_query($sql);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0]['num'];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
                $start=$start<0 ? 0 : $start;
		$sql = "SELECT * from {$this->table}referemail_list where StoreID='{$sid}' limit $start,$perPage";
		$dbcon->execute_query($sql);
		$arrResult['refer_emaillist']=$dbcon->fetch_records('true');

		$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_Referemail(\'%d\',\''.$sid.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
		$pager = Pager::factory($params);
		$arrResult['links'] 		= $pager->getLinks();
		$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
		return $arrResult;
	}

	function getsalelog($StoreID,$curpage=1,$field="order_date",$order="DESC"){
		global $dbcon;
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$arrResult = array();
		$sql = "SELECT count(*) as num FROM {$this->table}order_reviewref WHERE StoreID ='$StoreID' and pid <> '0'";
		$dbcon->execute_query($sql);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0]['num'];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		$orderstr = "";
		switch ($field){
			case 'bu_nickname':
				$orderstr = " order by bu.$field $order ";
				break;
			case 'item_name':
				$orderstr = " order by p.$field $order ";
				break;
			case 'month':
			case 'amount':
			case 'description':
			case 'p_status':
			case 'order_date':
				$orderstr = " order by rf.$field $order ";
				break;
			default:
				$orderstr = "";
				break;
		}
		$sql = "SELECT rf.*,bu.bu_nickname,p.item_name from {$this->table}order_reviewref rf left join {$this->table}product p on p.pid=rf.pid left join {$this->table}bu_detail bu on rf.buyer_id=bu.StoreID where rf.StoreID='{$StoreID}' and rf.pid <> '0' $orderstr limit $start,$perPage";
		$dbcon->execute_query($sql);
		$arrResult['product']=$dbcon->fetch_records('true');
		$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'onclick'	 => 'javascript:xajax_getsalelogX(\''.$StoreID.'\',\'%d\',\''.$field.'\',\''.$order.'\');return false;',
				'append'     => false,
				'urlVar'     => 'pageno',
				'path'		 => '#',
				'fileName'   => '%d',
				);
		$pager = Pager::factory($params);
		//$arrResult['sql'] = $sql;
		$arrResult['links'] 		= $pager->getLinks();
		$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
		return $arrResult;
	}

	function getsalelog_print($StoreID){
		global $dbcon;
		$sql = "SELECT rf.*,bu.bu_nickname,p.item_name from {$this->table}order_reviewref rf left join {$this->table}product p on p.pid=rf.pid left join {$this->table}bu_detail bu on rf.buyer_id=bu.StoreID where rf.StoreID='{$StoreID}' and rf.pid <> '0' order by rf.order_date DESC";
		$dbcon->execute_query($sql);
		$arrResult['product']=$dbcon->fetch_records('true');
		return $arrResult;
	}

	function getOrderDetail($StoreID){
		global $dbcon;
		$sql = "SELECT * FROM {$this->table}order_reviewref where buyer_id='$StoreID' and StoreID='$StoreID' and pid=0 limit 1";
		$dbcon->execute_query($sql);
		$result = $dbcon->fetch_records(true);
		return $result;
	}

	/**check the website is invalid.**/
	function checkwebsiteinvaild($StoreID){
		$_query = "select * from ".$this->table."login as t1 left join ".$this->table."bu_detail as t2 on t1.StoreID=t2.StoreID where t1.StoreID='".$StoreID."' ";
		$this->dbcon->execute_query($_query);
		$result=$this->dbcon->fetch_records();
		if($result){
			if($result[0]['attribute'] == 5 && ($result[0]['renewalDate']>time() || 1)){
				return true;
			}elseif($result[0]['attribute'] != 5){
				return true;
			}else {
				return false;
			}
		}else{
			return false;
		}
	}

	function getUserlogos($StoreID){
		$query = "SELECT attribute,subAttrib FROM {$this->table}bu_detail where StoreID='$StoreID'";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		$attribute =$result[0]['attribute'];
		$subAttrib =$result[0]['subAttrib'];
		switch ($attribute):
			case '1':
			$query ="Select t7.smallPicture, t7.picture from ".$this->table."product_realestate as t1 ".
	" left join ".$this->table."image as t7 on t1.StoreID=t7.StoreID and t1.pid=t7.pid and t7.attrib=0 and t7.sort=0 ".
	" where t1.StoreID='$StoreID' and t1.deleted=0 and t1.enabled=1 order by t1.featured desc, t1.datec desc,t1.item_name limit 1";
			break;
			case '2':
			$query ="Select t7.smallPicture, t7.picture from ".$this->table."product_automotive as t1 ".
	" left join ".$this->table."image as t7 on t1.StoreID=t7.StoreID and t1.pid=t7.pid and t7.attrib=0 and t7.sort=0 ".
	" where t1.StoreID='$StoreID' and t1.deleted=0 and t1.enabled=1 order by t1.featured desc, t1.datec desc,t1.item_name limit 1";
			break;
			case '3':

			$current_date = date("Y-m-d");

			if($subAttrib!=1){
				$query ="Select jb.item_name,jb.salaryMin,jb.salaryMax from ".$this->table."product_job as jb ".
				" LEFT JOIN {$this->table}bu_detail bu ON bu.StoreID=jb.StoreID ".
				" where jb.StoreID='$StoreID' and jb.deleted=0 and jb.enabled=1 and category='2' ";
				if($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2)) {
				}else{
					$query.=" and jb.ispub in(1) ";
				}
				$query.=" and ((datePosted <= '$current_date' or datePosted='0000-00-00')".
				" and (closingDate >= '$current_date' or closingDate='0000-00-00')) ".
				" order by jb.featured desc, jb.datec desc,jb.item_name limit 1";
			}else{
				$query="Select jb.item_name,jb.salaryMin,jb.salaryMax from ".$this->table."product_job as jb ".
				" LEFT JOIN {$this->table}bu_detail bu ON bu.StoreID=jb.StoreID ".
				" where jb.StoreID='$StoreID' and jb.deleted=0 and jb.enabled=1 and category='1' ".
				" and ((datePosted <= '$current_date' or datePosted='0000-00-00')".
				" and (closingDate >= '$current_date' or closingDate='0000-00-00'))".
				" order by jb.featured desc, jb.datec desc,jb.item_name limit 1";
			}
			break;
			default:
				$timenow = time();
				$query ="Select t7.smallPicture, t7.picture from ".$this->table."product as t1 ".
				" LEFT JOIN ".$this->table."product_auction au ON t1.pid=au.pid ".
				" left join ".$this->table."image as t7 on t1.StoreID=t7.StoreID and t1.pid=t7.pid and t7.attrib=0 and t7.sort=0 ".
				" where t1.StoreID='$StoreID' and t1.deleted='' AND IF(t1.is_auction='yes',au.end_stamp>$timenow,1=1) order by t1.isfeatured desc, t1.datec desc,t1.item_name limit 1";
			break;
		endswitch;
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		if($attribute==3){
			return $result[0];
		}else{
			return $result[0]['smallPicture'];
		}
	}

	function getpurchaseHis($StoreID,$curpage =1){
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	18;
		$arrResult = array();
		$sql = "SELECT count(*) as num FROM {$this->table}order_reviewref where buyer_id='$StoreID' and (type='purchasing' or type='bid') order by order_date DESC";
		$this->dbcon->execute_query($sql);
		$totalNum	=	$this->dbcon->fetch_records();
		$totalNum	= 	$totalNum[0]['num'];
		($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
		$start	= ($pageno-1) * $perPage;

		$query = "SELECT invoice.*,detail.bu_name FROM {$this->table}order_reviewref as invoice,{$this->table}bu_detail as detail "
				."where detail.StoreID=invoice.StoreID and buyer_id='$StoreID' and (type='purchasing' or type='bid') "
				."order by order_date DESC limit $start,$perPage";
		$this->dbcon->execute_query($query);
		$arrResult['purchase']['list'] = $this->dbcon->fetch_records(true);

		$params = array(
				'perPage'    => $perPage,
				'totalItems' => $totalNum,
				'currentPage'=> $pageno,
				'delta'      => 15,
				'append'     => true,
				'onclick'	 => "",
				'urlVar'     => 'pageno',
				'path'		 => '',
				'fileName'   => '%d',
				);
		$pager = Pager::factory($params);
		$arrResult['purchase']['links'] 		= $pager->getLinks();
		$arrResult['purchase']['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['purchase']['links']['all'];

		return $arrResult;
	}
	// get all information of order from database for invoice print
	function invoicePrint($StoreID, $orderID){
		$detail = array();
		// get order information
		$sql = "select * from ".$this->table."order_reviewref where ref_id=$orderID";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records();
		if (!$result){
			return false;
		}else{
			$detail['order'] = $result[0];
			$detail['order']['orderDate'] = date(DATAFORMAT_PHP,$detail['order']['order_date']);
			$detail['order']['payDate'] = $detail['order']['paid_date']>0?date("Y-m-d H:i:s",$detail['order']['paid_date']):'';
			$detail['order']['price'] = str_replace(',','',$detail['order']['price']);
			$detail['order']['productPrice'] = number_format($detail['order']['price'] * $detail['order']['month'],2,'.',',');
		}

		if ($detail['order']['attribute'] == '0') {
			// get product information
			$sql = "select * from ".$this->table."product where pid='{$detail['order']['pid']}'";
			$this->dbcon->execute_query($sql);
			$result = $this->dbcon->fetch_records();
			if (!$result){
				return false;
			}else{
				$detail['product'] = $result[0];
			}
		} elseif ($detail['order']['attribute'] == '5') {
			$sql = "select a.*,b.item_name from ".$this->table."order_detail_foodwine as a, ".$this->table."product_foodwine as b where a.pid=b.pid and a.OrderID='{$detail['order']['OrderID_foodwine']}'";
			$this->dbcon->execute_query($sql);
			$result = $this->dbcon->fetch_records();
			if (!$result){
				return false;
			}else{
				$detail['product_list'] = $result;
			}
		}

		if ($detail['product']['is_auction'] == 'yes'){
			// get auction information
			$sql = "select * from ".$this->table."product_auction where pid='{$detail['order']['pid']}'";
			$this->dbcon->execute_query($sql);
			$result = $this->dbcon->fetch_records();
			if (!$result){
				return false;
			}else{
				$detail['auction'] = $result[0];
			}
		}

		// get buyer information
		$sql = "select * from ".$this->table."bu_detail where StoreID='{$detail['order']['buyer_id']}'";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records();
		if (!$result){
			return false;
		}else{
			$detail['buyer'] = $result[0];
		}

		// get seller information
		$sql = "select * from ".$this->table."bu_detail where StoreID='{$detail['order']['StoreID']}'";
		$this->dbcon->execute_query($sql);
		$result = $this->dbcon->fetch_records();
		if (!$result){
			return false;
		}else{
			$detail['seller'] = $result[0];
		}

		return $detail;
	}

	function resetTemplate($StoreID=0)
	{
		$StoreID = $StoreID ? $StoreID : ($_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:''));
		$store_info = $this->getStoreInfo($StoreID);

		if ($store_info['attribute'] == 1 || $store_info['attribute'] == 2 || $store_info['attribute'] == 3) {
			switch ($store_info['attribute']) {
				case 1:
					$tpl_name = 'estate-c';
					$product_table = 'product_realestate';
					break;
				case 2:
					$tpl_name = 'auto-c';
					$product_table = 'product_automotive';
					break;
				case 3:
					$tpl_name = 'job-c';
					$product_table = 'product_job';
					break;
			}

			$current_time = time();
			$res = $this->dbcon->getOne("SELECT * FROM {$this->table}$product_table WHERE StoreID='$StoreID' AND (renewal_date>='$current_time' OR pay_status='2') AND deleted=0");

			if (empty($res)) {
				$sql = "UPDATE ".$this->table."bu_detail SET is_single_paid='0' where StoreID='{$store_info['StoreID']}'";
				$this->dbcon->execute_query($sql);
				$store_info['is_single_paid'] = 0;
			}

			$tpl_name = $store_info['attribute'] == 1 ? 'estate-c' : ($store_info['attribute'] == 2 ? 'auto-c' : ($store_info['attribute'] == 3 ? ($store_info['subAttrib'] == 3 ? 'job-c' : 'job-c') : 'job-c'));
			if ((($store_info['product_feetype'] == 'year' && $store_info['product_renewal_date'] < time()) || $store_info['product_feetype'] == 'product') && $store_info['is_single_paid']) {
				$sql = "UPDATE ".$this->table."template_details SET TemplateName='$tpl_name' where StoreID='{$store_info['StoreID']}'";
				$this->dbcon->execute_query($sql);
			}
		}

		return true;
	}

        /**
         * save facebook user id to db
         * @author Haydn
         * @date 20120228
         */
        public function facebookSaveKeyToDb(){

            $StoreID = $_SESSION['StoreID'];
            $attribute = $_SESSION['attribute'];
            $facebook = new Facebook(array('appId' => FB_APP_ID, 'secret' => FB_APP_SECRET));
            $fb_id = $facebook->getUser();

            if($fb_id){
                $arrSetting = array(
                    'StoreID'   => $StoreID,
                    'fb_id'     => $fb_id,
                    'attribute' => $attribute
                );

                $sql = "SELECT count(*) as num FROM {$this->table}facebook where (StoreID='$StoreID' or fb_id='$fb_id') and attribute='$attribute'";
                $this->dbcon->execute_query($sql);
                $totalNum	=	$this->dbcon->fetch_records();
                $totalNum	= 	$totalNum[0]['num'];
                if(! $totalNum){
                    $boolResult= $this->dbcon-> insert_record($this->table."facebook", $arrSetting);
                }else{
                    $boolResult=$this->dbcon->update_record($this->table."facebook", $arrSetting, "where (StoreID='$StoreID' or fb_id='$fb_id') and attribute='$attribute'");
                }
                if ($boolResult) {
                    $msg = 'Bundled facebook successfully. ';
                }else {
                    $msg = 'Bundled facebook failed. ';
                }
            }

            $_SESSION['fb']['id'] = $fb_id;
            $_SESSION['fb']['can'] = false;
        }

        public function facebookDeleteKeyFromDb(){
            $StoreID = $_SESSION['StoreID'];
            $attribute = $_SESSION['attribute'];
            $sql = "delete from {$this->table}facebook WHERE StoreID='$StoreID' and attribute='$attribute'";

            if($this->dbcon->execute_query($sql)){
                $_SESSION['fb']['id'] = '';
                $_SESSION['fb']['can'] = true;
                $msg = 'untie the bundled facebook a account successfully. ';
            }else{
                $msg = 'untie the bundled facebook a account unsuccessfully. ';
            }
        }
}

function Referpage($page,$sid,$field,$order,$isadmin=0){
	$objResponse 	= new xajaxResponse();
	$socObj  = &$GLOBALS['socObj'];
	$socObj -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$req['StoreID'] = $sid;
	$req['page'] = $page;
	if($isadmin){
		$req['ref'] = $socObj->getBuyerRefer($sid,$field,$order,$page,$isadmin);
		$socObj -> smarty -> assign('req',	$req);
		$content = $socObj -> smarty -> fetch('admin_refer_userlist.tpl');
	}else{
		$req['ref'] = $socObj->getBuyerRefer($sid,$field,$order,$page,$isadmin);
		$socObj -> smarty -> assign('req',	$req);
		$content = $socObj -> smarty -> fetch('referrer_userlist.tpl');
	}
	$objResponse -> assign("refcontent",'innerHTML',$content);
	return $objResponse;
}
function Referemail($page,$sid){
	$objResponse 	= new xajaxResponse();
	$socObj  = &$GLOBALS['socObj'];
	$socObj -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$req['ref'] = $socObj -> getEmailList($sid,$page);
	$socObj -> smarty -> assign('req',	$req);
	$content = $socObj -> smarty -> fetch('referrer_emaillist.tpl');
	$objResponse -> assign("refcontent",'innerHTML',$content);
	return $objResponse;
}
function getsalelogX($StoreID,$curpage,$field="",$order="ASC"){
	$objResponse 	= new xajaxResponse();
	$socObj  = $GLOBALS['socObj'];
	$req = $socObj->getsalelog($StoreID,$curpage,$field,$order);
	$socObj ->smarty->assign('req',$req);
	$socObj ->smarty->assign('notfull','1');
	$socObj ->smarty->assign('StoreID',$StoreID);
	$socObj ->smarty->assign('page',$curpage);
	$socObj ->smarty->assign('order',$order);
	$socObj ->smarty->assign('field',$field);
	$content = $socObj -> smarty -> fetch('salelog.tpl');
	$objResponse -> assign("salehistory",'innerHTML',$content);
	return $objResponse;
}
function getpreItemStat($StoreID,$year,$page,$type='PRODUCT'){
	$objResponse 	= new xajaxResponse();
	$socObj  = $GLOBALS['socObj'];
	$req['product'] = $socObj->getpreItemStat($StoreID,$year,$page,$type);
	$socObj ->smarty->assign('req',$req);
	$socObj ->smarty->assign('notfull','1');
	$content = $socObj -> smarty -> fetch('business_get_step_stat.tpl');
	$objResponse -> assign("peritemtable",'innerHTML',$content);
	return $objResponse;
}

