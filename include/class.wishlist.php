<?php
	class wishlist extends common {
		var $imagelc;
		var $mp3lc;
		var $dbcon  = null;
		var $table  = '';
		var $smarty = null;
		var $lang   = null;
		
		public function __construct(){
			/**
			 * The wishlist image file location:
			 * The wishlist mp3 file location: 
			**/
			$this->imagelc = "/upload/wishlist/image";
			$this->mp3lc = "/upload/wishlist/mp3";
			$this->dbcon  = &$GLOBALS['dbcon'];
			$this->table  = &$GLOBALS['table'];
			$this->smarty = &$GLOBALS['smarty'];
			$this->lang   = &$GLOBALS['_LANG'];
		}
		public function __destruct(){
			unset( $this->dbcon, $this->table, $this->smarty, $this -> lang , $this->imagelc, $this->mp3lc);
		}
		/**
		 * check the per online store wishlist
		 * param @StoreID
		 */
		public function getwishlist($StoreID){
			
		}
		
		/**
		 * init the seller wishlist
		 *
		 */
		public function initwishlist($StoreID,$template,$banner,$color){
			$query = "SELECT bu_name,bu_paypal,google_merchantkey,google_merchantid FROM  {$this->table}bu_detail WHERE StoreID='$StoreID'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$paypalacc = "";
			if($result){
				$paypalacc = $result[0]['bu_paypal'];
				$googlekey = $result[0]['google_merchantkey'];
				$googlemid = $result[0]['google_merchantid'];
			}

			if($this->checkInitwishlist($StoreID)){
				$query = "update {$this->table}wishlist_detail SET `template` = '{$template}',`banner`='$banner',`color`='$color' WHERE StoreID='$StoreID' ";
			}else{
				$query = "insert into {$this->table}wishlist_detail(`StoreID`,`template`,`banner`,`paypal`,`color`,`google_merchantid`,`google_merchantkey`,`description`,`isprotected`,`password`)values('$StoreID','$template','$banner','$paypalacc','$color','$googlemid','$googlekey','Here is my wishlist. Please feel free to gift me anything from my \'Wish list Website.\'\nYou can even part-gift an item if you choose? You can also pre-pay the PayPal and Google Checkout charges for me, if you like?','1','')";
				$aryPro = array('StoreID'=>$StoreID,
								'item_name'=>'Gift Any Amount Any Time',
								'url_item_name'=>'GiftAnyAmountAnyTime',
								'price'=>'1',
								'description'=>addslashes('Please feel free to gift any amount.'),
								'deleted'=>'',
								'datec'=>time(),
								'datem'=>time(),
								'isfeatured'=>'0',
								'protype'=>'1');
				$this->dbcon->insert_record($this->table."wishlist", $aryPro);
				$pid = $this->dbcon->insert_id(); 
				$aryProImg = array('pid'=>$pid,'StoreID'=>$StoreID,'tpl_type'=>0,
								   'smallPicture'=>'/skin/red/images/gifted_logo.gif',
								   'picture'=>'/skin/red/images/gifted_logo_big.gif',
								   'attrib'=>'0',
								   'sort'=>'0',
								   'datec'=>time(),
								   'datem'=>time());
				$this->dbcon->insert_record($this->table."wishlist_image", $aryProImg);
			}
			if($this->dbcon->execute_query($query)){
				return true;	
			}else{
				return false;
			}
		}
		
		public function saveUserTemplate($StoreID,$template,$banner){
			$query = "SELECT * FROM {$this->table}wishlist_template where StoreID='$StoreID'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			if($result){
				$query = "UPDATE {$this->table}wishlist_template SET template='$template',banner='$banner'  WHERE StoreID='$StoreID'";
				$this->dbcon->execute_query($query);
				$tplid = $result['0']['id'];
			}else{
				$query = "INSERT INTO {$this->table}wishlist_template(`template`,`banner`,`flash_banner`,`type`,`thumbimg`,`bigimage`,`StoreID`)values('$template','$banner','','image','','','$StoreID')";
				$this->dbcon->execute_query($query);
				$tplid = 	$this->dbcon->insert_id();			
			}
			
			return $tplid;
		}
		
		public function addoreditwishlist(){
			
			$boolResult	=	false;
			$StoreID	=	$_SESSION['ShopID'];
			$pid	=	$_REQUEST['pid'];
			$_var 		= 	$this -> setFormInuptVar();
			extract($_var);
			if($cp == 'edit'){
				$arrSetting	=	array(
				"item_name"		=>	"$item_name",
				"url_item_name"	=> 	clean_url_name($item_name),
				"price"			=>	empty($price)? "0": "$price",
				"description"	=>	"$description",
				'isfeatured'	=>	$isfeatured?$isfeatured:"0",
				'youtubevideo'  =>  $youtubevideo,
				'deleted'  		=>  "",
				);
				if($pid){
					$strCondition ="where StoreID='$StoreID' and pid='$pid'";
					if($this -> dbcon-> checkRecordExist($this->table."wishlist",$strCondition)){
						if ($this->checkProductName_wishlist($item_name,$StoreID,$pid)){
							$msg = "Product name exists.";
							$boolResult = false;
						}else{
							$arrSetting['datem']	= time();
							$boolResult = $this->dbcon->update_record($this->table."wishlist", $arrSetting, $strCondition);
						}
					}
				}else{
					if ($this->checkProductName_wishlist($item_name,$StoreID)){
						$msg = "Product exists! Please try with a new product name.";
						$boolResult = false;
					}else{
						$arrSetting['StoreID']	= "$StoreID";
						$arrSetting['datec']	= time();
						$arrSetting['datem']	= $arrSetting['datec'];
						$boolResult = $this->dbcon->insert_record($this->table."wishlist", $arrSetting);
						$pid = $this->dbcon->insert_id();
					}
				}
				if ($boolResult) {
					$Pid	=	$pid ;
					$arrSetting	=	array(
					'0' => array('simage'=> $_REQUEST['mainImage_svalue'], 'bimage'=> $_REQUEST['mainImage_bvalue'])
					);
					$this -> saveImageInfo($arrSetting,$StoreID,$Pid,0,0);
	
					$arrSetting	=	array(
					'0' => array('simage'=> $_REQUEST['subImage0_svalue'], 'bimage'=> $_REQUEST['subImage0_bvalue']),
					'1' => array('simage'=> $_REQUEST['subImage1_svalue'], 'bimage'=> $_REQUEST['subImage1_bvalue']),
					'2' => array('simage'=> $_REQUEST['subImage2_svalue'], 'bimage'=> $_REQUEST['subImage2_bvalue']),
					'3' => array('simage'=> $_REQUEST['subImage3_svalue'], 'bimage'=> $_REQUEST['subImage3_bvalue']),
					'4' => array('simage'=> $_REQUEST['subImage4_svalue'], 'bimage'=> $_REQUEST['subImage4_bvalue']),
					'5' => array('simage'=> $_REQUEST['subImage5_svalue'], 'bimage'=> $_REQUEST['subImage5_bvalue']),
					);
					$this -> saveImageInfo($arrSetting, $StoreID, $Pid, 1, 0);
					unset($objUI);
				}
				if ($boolResult) {
					$msg = 'Product saved successfully. ';
				}else {
					$msg = $msg?$msg:'Record saved failed. ';
				}
			}elseif ($cp == 'del' || $_REQUEST['cp'] == 'del'){
				$strCondition ="where StoreID='$StoreID' and pid='$pid'";
				if($this -> dbcon-> checkRecordExist($this->table."wishlist",$strCondition)){
					$boolResult = $this->dbcon-> insert_record($this->table."wishlist", $arrSetting);
					$pid = $this->dbcon->insert_id();
					$arrSetting	= array(
					'deleted'	=>	'YES'
					);
					$boolResult = $this->dbcon-> update_record($this->table."wishlist", $arrSetting, $strCondition);
				}
				if ($boolResult) {
					$msg	=	'Record deleted successfully. ';
				}else {
					$msg	=	'Record deleted failed. ';
				}
			}

			$this -> addOperateMessage($msg);
			return $boolResult;
			
		}
		
		public function copytowishlist($StoreID=0,$pid=0,$newname=""){
			if($pid!=0){
				$query = "SELECT * FROM {$this->table}product WHERE pid='$pid' limit 1";
				$this->dbcon->execute_query($query);
				$result = $this->dbcon->fetch_records(true);
				
				if($result){
					$pStoreID = $result[0]['StoreID'];
					$cpAry = array();
					if($newname!=""){
						$cpAry['item_name'] = $newname;
					}else{
						$cpAry['item_name'] = $result[0]['item_name'];
					}
					$cpAry['url_item_name'] = clean_url_name($cpAry['item_name']);
					$cpAry['item_name'] = addslashes($cpAry['item_name']);
					$cpAry['rel_pid'] 	  = $result[0]['pid'];
					$cpAry['description'] = addslashes($result[0]['description']);
					$cpAry['price']		  = $result[0]['price'];
					$cpAry['isfeatured']  = $result[0]['isfeatured'];
					$cpAry['StoreID']	  = $StoreID;
					$cpAry['datec']		  = time();
					$cpAry['datem']		  = time();
					$cpAry['deleted']	  = "";
					$cpAry['youtubevideo']	  =  $result[0]['youtubevideo'];

					$this->dbcon->insert_record($this->table."wishlist", $cpAry);
					
					$newpid = $this->dbcon->insert_id();
					
					$query = "SELECT * FROM {$this->table}image WHERE pid='$pid' and StoreID='$pStoreID' and attrib in(0,1)";
					$this->dbcon->execute_query($query);
					$result = $this->dbcon->fetch_records(true);
					if($result){
						foreach ($result as $pass){
							$cpAryImg = array();
							$cpAryImg['pid'] 	   = $newpid;
							$cpAryImg['StoreID'] = $StoreID;
							$cpAryImg['tpl_type']= $pass['tpl_type'];
							$cpAryImg['attrib']= $pass['attrib'];
							$cpAryImg['sort']= $pass['sort'];
							$cpAryImg['datec']= time();
							$cpAryImg['datem']= time();
							$cpAryImg['smallPicture'] = $this->copyImgtowish($pass['smallPicture'],"",true);
							$cpAryImg['picture'] = $this->copyImgtowish($pass['picture'],"",true);
							$this->dbcon->insert_record($this->table."wishlist_image", $cpAryImg);
							unset($cpAryImg);
						}
					}
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		
		public function copyImgtowish($strImagePathTemp,$desfilename="",$isoldfile=false){
			$strResult	=	'';

			$strResult = "/upload/wishlist/" . date("Ym");
			if (!file_exists( ROOT_PATH . $strResult)) {
				$GLOBALS['boolisWin'] ? mkdir( ROOT_PATH . $strResult) :mkdir( ROOT_PATH . $strResult,0755);
			}
			$strResult .= '/' . date("d") .'/';
			if (!file_exists( ROOT_PATH . $strResult)) {
				$GLOBALS['boolisWin'] ? mkdir( ROOT_PATH . $strResult) :mkdir( ROOT_PATH . $strResult,0755);
			}
			if($desfilename==""){
				$strResult .= basename($strImagePathTemp);
			}else{
				$strResult .= $desfilename;
			}
			$basePath	=	substr(ROOT_PATH, 0 , strlen(ROOT_PATH)-1);
	
			if(copy(realpath($basePath.$strImagePathTemp), ROOT_PATH.$strResult)){
				if(!$isoldfile){
					@unlink($basePath.$strImagePathTemp);
				}
			}
			return $strResult;
		}
		public function saveImageInfo($arrImages, $StoreID=0, $pid=0, $attrib=0, $tpl_type=0){
				$booleanResult	=	null;
		
				foreach ($arrImages as $key => $temp ){
					$arrSetting	= $temp;
		
					if ($tpl_type >= 1) {
						$attrib					=	$key;
						$arrSetting['sort']		=	0;
					}else {
						$arrSetting['sort']	=	$key;
					}
					if($this -> saveImageToDB_wishlist($arrSetting,$StoreID,$pid,$attrib,$tpl_type)){
						$booleanResult	=	true;
					}
		
				}

			return $booleanResult;
		}
		public function saveImageToDB_wishlist($arrImage, $StoreID=0, $pid=0, $attrib=0, $tpl_type=0){
			$booleanResult	=	false;
			$dateNow		=	time();
	
			if (is_array($arrImage)) {
				extract($arrImage);
	
				$strCondition = "where StoreID='$StoreID' and pid='$pid' and attrib='$attrib' and sort='$sort' and tpl_type='$tpl_type'";
				$_title	= "`id`,`smallPicture`,`picture`";
				$_query	= "select $_title from ".$this->table."wishlist_image $strCondition";
				$this->dbcon->execute_query($_query);
				$arrTemp = $this->dbcon->fetch_records(true);
	
				if (is_array($arrTemp)) {
					$arrTemp	=	$arrTemp[0];
					if ($arrTemp['smallPicture'] != $simage ) {
						if (uploadImages::__checkImagePath($simage)==1) {
							$arrSetting	=	array(
							'smallPicture' => $this -> copyImgtowish($simage),
							'picture'	   => $this -> copyImgtowish($bimage),
							'datem'		   => $dateNow
							);
							if($this->dbcon->update_record($this->table."wishlist_image", $arrSetting, $strCondition)){
								$booleanResult	=	true;
								$objUI	=	new uploadImages();
								$objUI->deleteImage(array('simage'=>$arrTemp['smallPicture'], 'bimage'=>$arrTemp['picture']));
							}
						}elseif (uploadImages::__checkImagePath($simage) == 0){
							$_query	=	"delete from " . $this -> table ."wishlist_image where id='".$arrTemp['id']."'";
							if($this->dbcon->execute_query($_query)){
								$booleanResult	=	true;
								$objUI	=	new uploadImages();
								$objUI->deleteImage(array('simage'=>$arrTemp['smallPicture'], 'bimage'=>$arrTemp['picture']));
							}
						}
	
					}else{
						$booleanResult	=	true;
					}
	
				}else{
					if (uploadImages::__checkImagePath($simage)==1) {
						$arrSetting	=	array(
						'smallPicture'	=>  $this -> copyImgtowish($simage),
						'picture'		=>	$this -> copyImgtowish($bimage),
						'StoreID'		=>	$StoreID,
						'pid'			=>	$pid,
						'attrib'		=>	$attrib,
						'tpl_type'		=>	$tpl_type,
						'sort'			=>	$sort,
						'datec'			=>	$dateNow,
						'datem'			=>	$dateNow
						);
	
						if($this->dbcon->insert_record($this->table."wishlist_image", $arrSetting)){
							$booleanResult	=	true;
						}
					}
				}
	
			}
	
			return $booleanResult;
		}
		
		
		public function getWishlistPro($pid=0,$StoreID){
			$arrResult 	= 	null;
			if ($this-> _notVar)
			{
				$arrResult['select']	=	$this -> getFormInputVar();
			}else{
				$query		=	"SELECT * FROM ".$this-> table."wishlist WHERE pid='$pid' and StoreID ='$StoreID' and deleted='' order by datec asc";
				$this-> dbcon -> execute_query($query);
				$arrTemp=	$this->dbcon->fetch_records(true);
	
				if (is_array($arrTemp)){
					$arrResult['select']	=	$arrTemp[0];
					$arrResult['select']['mainImageH'] = $arrResult['select']['image_name'];
				}
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

			$arrResult['select']['step']	=	$_REQUEST['step'];
			$arrResult['select']['msg']		=	$_REQUEST['msg'];
	
			$arrResult['product']			=	$this->getWishlistProlist($StoreID,$_REQUEST['sortby']);
	
			$arrResult['select']['sortby'] = $_REQUEST['sortby'];
	
			$productDescription = $arrResult['select']['description'];
	
			$objUI	=	new uploadImages();
			$arrResult['images']	=	$objUI -> getDisplayWishlistImage('auto',$StoreID,$_REQUEST['pid']);
			unset($objUI);
	
			return $arrResult;
		}
		
		
		public function getwishlistMenu($steps,$StoreID){
			$strResult = '';
			$arrReq = array(
			'storeActive' 	=> $steps == '1' || $steps == '' ? '_active' : '' ,
			'chooseActive' 	=> $steps == '2' ? '_active' : '' ,
			'colorActive' 	=> $steps == '3' ? '_active' : '' ,
			'bulid'			=> $this->checkInitwishlist($StoreID),
			'enable'			=> $this->checkEnablewishlist($StoreID),
			);
			$this -> smarty -> assign('req', $arrReq);
			$strResult = $this->smarty->fetch('wishlist_menu.tpl');
	
			return $strResult;
		}
		
		public function getBannerList($StoreID){
			$query = "SELECT * FROM {$this->table}wishlist_template WHERE StoreID in (0,$StoreID) order by sort";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records();
			$aryBanner = array();
			if($result){
				foreach ($result as $pass){
					if($pass['StoreID']==0){
						$aryBanner['SYSTEM'][$pass['b_type']][$pass['id']] = $pass; 
					}else{
						$aryBanner['USER'] = $pass;
					}
				}
			}
			return $aryBanner;
			
		}
		
		public function getUserTempList(){
			$query = "SELECT * FROM {$this->table}wishlist_usertemplate ORDER BY tpl_sort ASC";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records();
			return $result;
		}
		
		public function checkInitwishlist($StoreID){
			$isbulid = false;
			$query = "SELECT count(*) FROM {$this->table}wishlist_detail WHERE StoreID='$StoreID'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records();
			if($result[0][0]>0){
				$isbulid = true;
			}
			return $isbulid;
		}
		
		public function checkEnablewishlist($StoreID){
			$isbulid = false;
			$query = "SELECT count(*) FROM {$this->table}wishlist_detail WHERE StoreID='$StoreID' and `enable`='1'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records();
			if($result[0][0]>0){
				$isbulid = true;
			}
			return $isbulid;
		}
		
		public function getwishlistInfo($StoreID){
			$query = "SELECT * FROM {$this->table}wishlist_detail WHERE StoreID='$StoreID'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records();
			if($result){
				$aryResult = array();
				foreach ($result[0] as $key=>$pass){
					$aryResult[$key]=stripslashes($pass);
				}
				return $aryResult;
			}
			return array();
		}
		
		public function getWishListNumber($StoreID){
			$_query = "SELECT sum(`Jan` + `Feb` + `Mar` + `Apr` + `May` + `Jun` + `Jul` + `Aug` + `Sep` + `Oct` + `Nov` + `Dec`) AS counter FROM {$this->table}hit_wishlist where StoreID='$StoreID'";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			$_query = "SELECT sum(`Jan` + `Feb` + `Mar` + `Apr` + `May` + `Jun` + `Jul` + `Aug` + `Sep` + `Oct` + `Nov` + `Dec`) AS counter FROM {$this->table}hit_wishlist_product where StoreID='$StoreID'";
			$this->dbcon->execute_query($_query);
			$arrTemp2 = $this->dbcon->fetch_records(true);
			return $arrTemp[0]['counter']+$arrTemp2[0]['counter'];
		}

		public function saveWishlistInfo($StoreID){
			$aryInfo = array();
			$message = "";
			if(!magic_quotes_runtime()){
				$_POST['paypal'] = addslashes($_POST['paypal']);
				$_POST['description'] = addslashes($_POST['description']);
				$_POST['youtubevideo'] = addslashes($_POST['youtubevideo']);
				$_POST['password'] = addslashes($_POST['password']);
				$_POST['music_name'] = addslashes($_POST['music_name']);
				$_POST['music'] = addslashes($_POST['music']);			
			}
			
			$aryInfo['music_name'] = $_POST['music_name'];
			$aryInfo['music'] = $_POST['music'];		
			$aryInfo['paypal'] = $_POST['paypal'];
			$aryInfo['google_merchantid'] = $_POST['google_merchantid'];
			$aryInfo['google_merchantkey'] = $_POST['google_merchantkey'];
			$aryInfo['password'] = $_POST['password'];
			$aryInfo['isprotected'] = 1;
			$aryInfo['enable'] = 1;
			$aryInfo['description'] = $_POST['description'];
			$aryInfo['youtubevideo'] = $_POST['youtubevideo'];
			
			$str = "";
			foreach ($aryInfo as $key=>$val){
				$str .= ($str==""?"":",")."`$key`='$val'";
			}
			$query = "update {$this->table}wishlist_detail SET $str WHERE StoreID='$StoreID'";
			if($this->dbcon->execute_query($query)){
				return $message;
			}else{
				if($message){
					return $message;
				}else{
					return "Updated your wishlist details unsuccessfully.";
				}
			}
		}
	
		public function getWishlistProlist($StoreID,$sortby="",$ispage=false){
			
			$query0	=	"SELECT p.*,detail.bu_name,detail.bu_urlstring,"
			." t2.smallPicture, t2.picture ";
			$query = " FROM ".$this->table."wishlist as p"
			." left join ".$this->table."bu_detail as detail on p.StoreID=detail.StoreID"
			." left join ".$this->table."wishlist_image as t2 on p.StoreID=t2.StoreID and p.pid=t2.pid and t2.attrib=0 and t2.sort=0 "
			." where p.StoreID in ($StoreID) and p.deleted='' ";
			switch ($sortby) {
				case 'date_asc':
					$query .= "order by p.datec asc ";
					break;	
				case 'date_desc':
					$query .= "order by p.datec desc ";
					break;
				case 'price_asc':
					$query .= "order by p.price asc ";
					break;
				case 'price_desc':
					$query .= "order by p.price desc ";
					break;
				case 'cat_asc':
					$query .= "order by p.category asc , p.datec desc ";
					break;
				case 'price_desc':
					$query .= "order by p.price desc ";
					break;
				case 'cat_asc':
					$query .= "order by p.category asc , p.datec desc ";
					break;
				case 'featured':
					$query .= " order by p.isfeatured desc , p.datem desc ";
					break;
				default:
					$query .= "order by p.datem desc,p.item_name ";
			}
			$limitstr = "";
			if($ispage){
				$cquery = "SELECT COUNT(*) \n".$query;
				$this->dbcon->execute_query($cquery);
				$total = $this->dbcon->fetch_records(true);
				$total = $total[0]['COUNT(*)'];
				$pageSize = 4;
				$clsPage = new Page($total, $pageSize);
				$limitstr .= $clsPage->get_limit();
			}
			$this-> dbcon -> execute_query($query0.$query.$limitstr);
			$arrTemp =	$this->dbcon->fetch_records(true);
			if (is_array($arrTemp)) {
				$i=0;
				foreach ($arrTemp as $temp){
					$urlParam = $temp['StoreID']."&amp;proid=".$temp['pid'];
					$temp['urlParam']		=	$urlParam;
					$temp['description'] = strip_tags(interceptChar($temp['description'],110,true,$temp['descMore']));
					$temp['datec']  = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)), $temp['datec']);	
					$arrResult['product'][$i]	=	$temp;
					$arrResult['product'][$i]['dateadd'] 	= $temp['datec'];
					$arrResult['product'][$i]['url_bu_name'] = clean_url_name($temp['bu_urlstring']);
					$arrResult['product'][$i]['image_name'] = $this -> imageDisplayWH($temp['picture'],4,79,79);
					$arrResult['product'][$i]['image_name']['padding'] = 79 - $arrResult['product'][$i]['image_name']['width'];
					if($req['protype']){
						$arrResult['product'][$i]['fotgive'] = $temp['price'];
					}else{
						$arrResult['product'][$i]['fotgive'] = $temp['price']-$temp['gifted'];
					}
					$objUI = new uploadImages();
					$arrResult['product'][$i]['simage'] = $objUI -> getDefaultImage($temp['smallPicture'], true, 0, 0, 4);
					$arrResult['product'][$i]['bimage'] = $objUI -> getDefaultImage($temp['picture'],false,0,0,9);
					unset($objUI);
					$arrResult['product'][$i]['price'] = $arrResult['product'][$i]['price'];
					if($ispage){
						$arrResult['product'][$i]['linkStr']  = $clsPage->get_link('javascript:pages('.$StoreID.',', $pageSize,true);
					}
					$i++;
				}
			}
			return $arrResult['product'];
			
		}
		
		private function checkProductName_wishlist($name,$StoreID,$pid=0){
			$name = clean_url_name($name);
			$sql = "select count(*) as num from ".$this->table."wishlist where url_item_name='$name' and StoreID='$StoreID' and deleted!='YES'";
			if ($pid!=0){
				$sql.= " and pid!=$pid";
			}
			$this->dbcon->execute_query($sql);
			$num = $this->dbcon->fetch_records();
			return ($num[0]['num']>0)?true:false;
		}
		
		public function multidel($arrayid = array()){
			if(count($arrayid)>0){
				$expstr = implode(',',$arrayid);
				$sql = "update {$this->table}wishlist  set `deleted` = 'YES' where pid in($expstr);";
				if($this->dbcon->execute_query($sql)){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	
		public function getWishlistTemplate($banner){
			$query = "SELECT * FROM {$this->table}wishlist_template WHERE id='$banner'";
			$this->dbcon->execute_query($query);
			if($result = $this->dbcon->fetch_records(true)){
				return $result[0];
			}
			return false;
		}
		public function getwishProByName($name,$StoreID){
			$query = "SELECT * FROM {$this->table}wishlist where StoreID='$StoreID' and url_item_name='".trim($name)."' and deleted<>'YES' ";
			$this->dbcon->execute_query($query);
			if($result = $this->dbcon->fetch_records(true)){
				return $result[0];
			}else{
				return false;
			}
		}
		
		public function wishlistItemsProduct($StoreID, $proID=0, $hasFeatured=false,$list="")
		{
			global $templateSet;
			$arrResult	=	null;
			$query ="Select t1.*, ".
			" t5.bu_name,t5.bu_urlstring, t7.smallPicture, t7.picture ";
			$wheresql = " from ".$this->table."wishlist as t1 ".
			" left join ".$this->table."bu_detail as t5 on t1.StoreID=t5.StoreID ".
			" left join ".$this->table."wishlist_image as t7 on (t1.StoreID=t7.StoreID and t1.pid=t7.pid and t7.attrib=0 and t7.sort=0) ".
			" where t1.StoreID='$StoreID' and t1.deleted='' ";
	
			if ($proID) {
				$wheresql .=" and t1.pid='$proID' ";
			}
	
			if ($hasFeatured) {
				$wheresql .= " order by isfeatured desc, t1.datem desc,t1.item_name ";
			}
	
			if ($limit && $hasFeatured) {
				$wheresql .= " limit 0,$limit";
			}
			
			$query = $query.$wheresql;
			$this -> dbcon -> execute_query($query);
			$arrTemp = $this -> dbcon -> fetch_records(true);
			if (is_array($arrTemp)) {
					foreach ($arrTemp as $temp)
					{
						//set default images
						if ($proID>0){
							$temp['image_name'] = $this -> imageDisplayWH($temp['picture'],2,241,241);
						}elseif ($template['TemplateName'] == 'tmp-n-e'){
							$temp['image_name'] = $this -> imageDisplayWH($temp['picture'],3,243,212);
						}else{
							$temp['image_name'] = $this -> imageDisplayWH($temp['smallPicture'],4,79,79);
						}
	
						$objUI	=	new uploadImages();
						$temp['images']	=	$objUI -> getDisplayWishlistImage('store', $temp['StoreID'], $temp['pid']);
						$temp['simage']	=	$objUI -> getDefaultImage($temp['smallPicture'], true, 0, 0, 4);
						$temp['bimage']	=	$objUI -> getDefaultImage($temp['picture'],false,0,0,9);
						$temp['images_count'] = $temp['images']['imagesCount'];
						unset($objUI);
						$temp['image_name']['padding'] = 80 - $temp['image_name']['width'];
	
						//add a url bu_name by jessee 080804
						$temp['url_bu_name'] = clean_url_name($temp['bu_urlstring']);
						//set price format by jessee 080325
						$temp['price'] = $temp['price'];
						$temp['youtubevideo']	=	getobjurl($temp['youtubevideo'])?getobjurl($temp['youtubevideo']):"";

						if($req['protype']){
							$temp['fotgive'] = $temp['price'];
						}else{
							$temp['fotgive'] = $temp['price']-$temp['gifted'];
						}
						$arrResult[]	=	$temp;
				}
			}
			return $arrResult;
		}

		public function checkisaddlink($StoreID,$pid){
			$query = "SELECT COUNT(*) FROM {$this->table}wishlist WHERE StoreID='{$StoreID}' and rel_pid='$pid'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records();
			if($result[0][0]>0){
				return false;
			}else{
				return true;
			}
		}
		
		public function chekcisneworder($orderID){
			$query = "SELECT count(*) from {$this->table}wishlist_order where OrderID='$orderID' and p_status='order' and order_date >".(time()-90);
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records();
			if($result[0][0]>0){
				return false;
			}
			return true;
		}
		
		public function getProURL($StoreID,$pid=0){
			$query = "SELECT * FROM {$this->table}bu_detail bu left join {$this->table}wishlist w ON w.StoreID=bu.StoreID where bu.StoreID='$StoreID' ";
			if($pid){ $query.=" and w.pid='$pid' ";}
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			if($pid){
			$url = "/{$result[0]['bu_urlstring']}/wishlist/{$result[0]['url_item_name']}";
			}else{
				$url = "/{$result[0]['bu_urlstring']}/wishlist";
			}
			return $url;
		}
	
		public function getSellerInfo($StoreID,$pid){
			$query = "SELECT * FROM {$this->table}bu_detail bu left join {$this->table}wishlist w ON w.StoreID=bu.StoreID where bu.StoreID='$StoreID' and w.pid='$pid'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			return $result[0];
		}
		
		public function googlepayment($ref_id,$StoreID,$amount,$itemname){
			require_once('./include/googlecheckout/library/googlecart.php');
			require_once('./include/googlecheckout/library/googleitem.php');
			require_once('./include/googlecheckout/library/googleshipping.php');
			require_once('./include/googlecheckout/library/googletax.php');

			$_query = "SELECT google_merchantid, google_merchantkey FROM ".$this->table."wishlist_detail WHERE StoreID=".$StoreID;
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);
			$merchant_id = $arrTemp[0]['google_merchantid'];
			$merchant_key = $arrTemp[0]['google_merchantkey'];
			$server_type = GOOGLE_CHECKOUT_SERVER_TYPE;
			$currency = "AUD";

			if(isset($_SESSION['ShopID'])){
				$additionalInfo = 'buyerId='.$_SESSION['ShopID'];
			}else{
				$additionalInfo = 'buyerId=0';
			}
			$additionalInfo .= ',pid='.$pid;
			$additionalInfo .= ',ref_id='.$ref_id;

			$cart = new GoogleCart($merchant_id, $merchant_key, $server_type,$currency);
			$cart->SetContinueShoppingUrl(PAYPAL_SITEURL);
		
			$items = new GoogleItem($itemname, '', 1, $amount);
			$items->SetMerchantPrivateItemData($additionalInfo);
			$cart->AddItem($items);

			list($status, $error) = $cart->CheckoutServer2Server();
			if ($status != '200'){
				echo "<script> alert('The google checkout account of this seller is invalid or expired. Please try other payment methods or contact the seller.');history.go(-1);</script>";
				exit;
			}
		}
	
		public function saveEmailFriends($aryParams){
			return $this->dbcon->insert_query($this->table."wishlist_email",$aryParams);
		}
		public function getwishlistemails($msgid){
			$query = "SELECT * FROM {$this->table}wishlist_email where id='$msgid'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			return $result[0];
		}
		
		function getwishmsgList($sid,$curpage = 1){
			global $dbcon;
			$pageno		=	$curpage >0 ? $curpage : 1;
			$perPage	=	18;
			$arrResult = array();
			$sql = "SELECT count(*) as num from {$this->table}wishlist_email where StoreID='{$sid}'";
			$dbcon->execute_query($sql);
			$totalNum	=	$this->dbcon->fetch_records();
			$totalNum	= 	$totalNum[0]['num'];
			($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
			$start	= ($pageno-1) * $perPage;
			$sql = "SELECT * from {$this->table}wishlist_email where StoreID='{$sid}' limit $start,$perPage";
			$dbcon->execute_query($sql);
			$arrResult['wishtlist_emaillist']=$dbcon->fetch_records('true');
			
			$params = array(
					'perPage'    => $perPage,
					'totalItems' => $totalNum,
					'currentPage'=> $pageno,
					'delta'      => 15,
					'onclick'	 => 'javascript:xajax_getwishmsg_list(\'%d\',\''.$sid.'\');return false;',
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
	
		function uploadMusic(){
			if(isset($_FILES['music'])&&$_FILES['music']['size']>0){
				if($_FILES['music']['size']<='10485760'){
					$ext = explode('.',$_FILES['music']['name']);
					$ext = $ext[count($ext)-1];
					if($ext=='mp3'){
						$newfile = "upload/wishlist/mp3/".time().randStr(8).".$ext";
						$dstfile = ROOT_PATH.$newfile;
						if(move_uploaded_file($_FILES['music']['tmp_name'],$dstfile)){
							$aryInfo['music_name'] = addslashes($_FILES['music']['name']);
							$aryInfo['music'] = $newfile;
						}else{
							$aryInfo['music_name'] = "";
							$aryInfo['music'] = "";
							$message = "Can not upload the file.";
						}
					}else{
						$aryInfo['music_name'] = "";
						$aryInfo['music'] = "";
						$message = "File type is invalid.";
					}
				}else{
					$aryInfo['music_name'] = "";
					$aryInfo['music'] = "";
					$message = "The file is overloaded by 10MBs.";
				}
			}else{
				$aryInfo['music_name'] = "";
				$aryInfo['music'] = "";
				$message = "You should select one file.";
			}
			$aryInfo['msg'] = $message;
			return $aryInfo;
		}
	}
?>