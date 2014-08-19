<?php
/**
 * Mon Dec 08 02:33:48 GMT 2008 02:33:48
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * upload images class and function
 * ------------------------------------------------------------
 * include\class.uploadImages.php
 */

class uploadImages extends common
{
	var $dbcon  = null;
	var $table  = '';
	var $smarty = null;
	var $lang   = null;

	/**
	 * @return void 
	 */
	public function __construct()
	{
		$this->dbcon  = &$GLOBALS['dbcon'];
		$this->table  = &$GLOBALS['table'];
		$this->smarty = &$GLOBALS['smarty'];
		$this->lang   = &$GLOBALS['_LANG'];
	}

	/**
	 * @return void 
	 */
	public function __destruct()
	{
		unset( $this->dbcon, $this->table, $this->smarty, $this -> lang );
	}

	public function uploadInterface(){
		$arrResult	=	null;

		$arrResult	=	array(
		'tpltype'	=>	$_REQUEST['tpltype'],
		'attrib'	=>	$_REQUEST['attrib'],
		'index'		=>	$_REQUEST['index'],
		'objImage'	=>	$_REQUEST['objImage']
		);

		return $arrResult;
	}

	public function upload(){
		$arrResult	=	null;

		$arrResult	=	array(
		'valueDis'	=>	'',
		'valueEdit'	=>	'',

		'tpltype'	=>	$_REQUEST['tpltype'],
		'attrib'	=>	$_REQUEST['attrib'],
		'index'		=>	$_REQUEST['index'],
		'objImage'	=>	$_REQUEST['objImage'],
		);
		$filetype = array('image/x-png','image/pjpeg','image/gif','image/jpeg','image/png');
		$msg = "";
		if (!empty($_FILES)) {
			if(in_array($_FILES["upfiles"]['type'],$filetype)){
			$arrPictureSmall	=	$this -> getDefaultImage('', true , $_REQUEST['tpltype'], $_REQUEST['attrib']);
			$arrPicture	=	$this -> getDefaultImage('', false , $_REQUEST['tpltype'], $_REQUEST['attrib']);

			$objUpload	= new uploadFile($arrPicture['width'], $arrPicture['height'] ,10240 ,10 , $_FILES["upfiles"], "SOCExchange.com.au");
			if($objUpload->uploadAndSmallPic($arrPictureSmall['width'], $arrPictureSmall['height'])){
				$arrResult['valueDis']	=	$objUpload -> newFileFullNameSmall;
				$arrResult['valueDisW']	=	$objUpload -> newFileSize[4];
				$arrResult['valueDisH']	=	$objUpload -> newFileSize[5];

				$arrResult['valueBig']	=	$objUpload -> newFileFullName;
				$arrResult['valueSmall']=	$objUpload -> newFileFullNameSmall;
			}else{
				$msg = "Invalid image. Please change and try again.";
			}

			unset($objUpload);
			}else{
				$msg = "Invalid image format.";
			}
			//echo print_r($_FILES,true);
		}else{
			$msg = "Image is required.";
		}
		$arrResult['msg'] = $msg;
		return $arrResult;
	}

	public function deleteImage($arrDelFromProduct = null){
		$strResult	=	'';

		$simage	=	$_REQUEST['simage'];
		$bimage	=	$_REQUEST['bimage'];
		$tpltype=	$_REQUEST['vtpltype'];
		$attrib	=	$_REQUEST['vattrib'];
		$index	=	$_REQUEST['vindex'];

		if ( $this -> __checkImagePath($simage) == 1 ) {
			//@ unlink(ROOT_PATH . $simage);
			//@ unlink(ROOT_PATH . $bimage);
			$newImageSmall	=	$this -> getDefaultImage('',true,$tpltype,$attrib);
			$newImage	=	$this -> getDefaultImage('',false,$tpltype,$attrib);
			$strResult	=	$newImageSmall['text'] . ",". $newImageSmall['width'] .",". $newImageSmall['height']. "|".$newImage['text'];
		}elseif( !$arrDelFromProduct && $this -> __checkImagePath($simage) == 2 ){
			$newImageSmall	=	$this -> getDefaultImage('',true,$tpltype,$attrib);
			$newImage	=	$this -> getDefaultImage('',false,$tpltype,$attrib);
			$strResult	=	$newImageSmall['text'] . ",". $newImageSmall['width'] .",". $newImageSmall['height']. "|".$newImage['text'];
		}elseif (is_array($arrDelFromProduct) && $this -> __checkImagePath($arrDelFromProduct['simage']) == 2){
			$basePath	=	substr(ROOT_PATH, 0 , strlen(ROOT_PATH)-1);

			//@ unlink($basePath . $arrDelFromProduct['simage']);
			//@ unlink($basePath . $arrDelFromProduct['bimage']);
		}

		return $strResult;
	}



	/**
	 * read images from db
	 *
	 * @param int $StoreID
	 * @param int $pid
	 * @param int $attrib
	 * @param int $sort
	 * @param int $tpl_type
	 * @return array
	 */
	public function getDisplayImage($read='', $StoreID=0, $pid=0, $attrib=-1, $sort=-1, $tpl_type=0){
		$arrResult	=	null;
		$intImagesNum	=	0;
		$intImagesCount	=	0;

		if ($StoreID > 0 && ($pid>0 || $tpl_type > 1)) {
			$_where	=	"where 1 ";
			$_where	.=	" and StoreID='$StoreID'";

			if ($tpl_type > 1) {
				$_where	.=	" and tpl_type='$tpl_type'";
			}elseif ($pid){
				$_where	.=	" and pid='$pid'";
			}

			$attrib > -1 ? $_where .= " and attrib=$attrib": '';
			$sort > -1 ? $_where .= " and sort=$sort": '';

			$_query	=	"select * from ".$this->table."image $_where order by attrib, sort";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);

			if ($tpl_type < 1) {
				if (is_array($arrTemp)) {
					$arrResult['imagesCount']	=	count($arrTemp);
					$arrResult['uploadCount']	=	count($arrTemp);
					foreach ($arrTemp as $key => $temp){
						if ($temp['attrib']==0) {
							$arrResult['mainImage'][0]['sname']	=	$this -> getDefaultImage($temp['smallPicture'],true,0,$temp['attrib']);
							$arrResult['mainImage'][0]['bname']	=	$this -> getDefaultImage($temp['picture'],false,0,$temp['attrib']);

						}elseif ($temp['attrib']==1){
							$arrResult['subImage'][$temp['sort']]['sname'] = $this -> getDefaultImage($temp['smallPicture'],true,0,$temp['attrib']);
							$arrResult['subImage'][$temp['sort']]['bname'] = $this -> getDefaultImage($temp['picture'],false,0,$temp['attrib']);
							if ($arrResult['subImage'][$temp['sort']]['sname']['text'] == '/images/79x79.jpg') {
								$arrResult['uploadCount']--;
							}
						}else {
							$arrResult['planImage'][$temp['sort']]['sname']	= $this -> getDefaultImage($temp['smallPicture'],true,0,$temp['attrib']);
							$arrResult['planImage'][$temp['sort']]['bname']	= $this -> getDefaultImage($temp['picture'],false,0,$temp['attrib']);
						}
						
					}
				}
			} else {
				if (is_array($arrTemp)) {
					$arrResult['imagesCount']	=	count($arrTemp);
					$arrResult['uploadCount']	=	count($arrTemp);
					foreach ($arrTemp as $temp){
						$arrResult['mainImage'][$temp['attrib']]['sname']	=	$this -> getDefaultImage($temp['smallPicture'],true,$tpl_type,$temp['attrib']);
						$arrResult['mainImage'][$temp['attrib']]['bname']	=	$this -> getDefaultImage($temp['picture'],false,$tpl_type,$temp['attrib']);
					}
				}

				if ($tpl_type == 3 ) {
					$intImagesNum	=  2;
				}elseif ($tpl_type == 4){
					$intImagesNum	=  4;
				}elseif ($tpl_type == 6){
					$intImagesNum	=  4;
				}else{
					$intImagesNum	=  3;
				}
				for ($i=0; $i<$intImagesNum; $i++){
					empty($arrResult['mainImage'][$i]['sname']) ? $arrResult['mainImage'][$i]['sname'] = $this -> getDefaultImage('',true,$tpl_type,$i) : '';
					empty($arrResult['mainImage'][$i]['bname']) ? $arrResult['mainImage'][$i]['bname'] = $this -> getDefaultImage('',false,$tpl_type,$i) : '';
				}
			}
		}

		if($tpl_type == 0 && $read ==	'auto') {

			empty($arrResult['mainImage'][0]['sname']) ? $arrResult['mainImage'][0]['sname']	= $this -> getDefaultImage('',true,0,0) : '';
			empty($arrResult['mainImage'][0]['bname']) ? $arrResult['mainImage'][0]['bname'] = $this -> getDefaultImage('',false,0,0) : '';
			for($i=0; $i<6; $i++){
				empty($arrResult['subImage'][$i]['sname'])	? $arrResult['subImage'][$i]['sname'] = $this -> getDefaultImage('',true,0,1) : '';
				empty($arrResult['subImage'][$i]['bname'])	? $arrResult['subImage'][$i]['bname'] = $this -> getDefaultImage('',false,0,1) : '';
			}

		}elseif($tpl_type == 0 && $read	==	'estate') {

			empty($arrResult['mainImage'][0]['sname']) ? $arrResult['mainImage'][0]['sname'] = $this -> getDefaultImage('',true,0,0) : '';
			empty($arrResult['mainImage'][0]['bname']) ? $arrResult['mainImage'][0]['bname'] = $this -> getDefaultImage('',false,0,0) : '';
			for($i=0; $i<6; $i++){
				empty($arrResult['subImage'][$i]['sname'])	? $arrResult['subImage'][$i]['sname'] = $this -> getDefaultImage('',true,0,1) : '';
				empty($arrResult['subImage'][$i]['bname'])	? $arrResult['subImage'][$i]['bname'] = $this -> getDefaultImage('',false,0,1) : '';
			}
			empty($arrResult['planImage'][0]['sname']) ? $arrResult['planImage'][0]['sname'] = $this -> getDefaultImage('',true,0,2) : '';
			empty($arrResult['planImage'][0]['bname']) ? $arrResult['planImage'][0]['bname'] = $this -> getDefaultImage('',false,0,2) : '';

		}elseif($tpl_type == 0 && $read	==	'foodwine') {
			$arrResult['uploadCount'] = 0;
			if (is_array($arrTemp)) {
				foreach ($arrTemp as $temp) {
					if ($temp['picture'] != '' && $temp['picture'] != '/images/700x525.jpg' && $temp['picture'] != '/images/79x79.jpg') {
						$arrResult['uploadCount']++;
					}
				}
			}
		}elseif($tpl_type == 0 && $read ==	'store') {

			empty($arrResult['mainImage'][0]['sname']) ? $arrResult['mainImage'][0]['sname']	= $this -> getDefaultImage('',true,0,0) : '';
			empty($arrResult['mainImage'][0]['bname']) ? $arrResult['mainImage'][0]['bname'] = $this -> getDefaultImage('',false,0,0) : '';
			for($i=0; $i<6; $i++){
				empty($arrResult['subImage'][$i]['sname'])	? $arrResult['subImage'][$i]['sname'] = $this -> getDefaultImage('',true,0,1) : '';
				empty($arrResult['subImage'][$i]['bname'])	? $arrResult['subImage'][$i]['bname'] = $this -> getDefaultImage('',false,0,1) : '';
			}		
		}elseif ($tpl_type == 2){
			for($i=0; $i<3; $i++){
				empty($arrResult['mainImage'][$i]['sname']) ? $arrResult['mainImage'][$i]['sname'] = $this -> getDefaultImage('',true,$tpl_type,$i) : '';
				empty($arrResult['mainImage'][$i]['bname']) ? $arrResult['mainImage'][$i]['bname'] = $this -> getDefaultImage('',false,$tpl_type,$i) : '';
			}
		}elseif ($tpl_type == 3){
			for($i=0; $i<2; $i++){
				empty($arrResult['mainImage'][$i]['sname']) ? $arrResult['mainImage'][$i]['sname'] = $this -> getDefaultImage('',true,$tpl_type,$i) : '';
				empty($arrResult['mainImage'][$i]['bname']) ? $arrResult['mainImage'][$i]['bname'] = $this -> getDefaultImage('',false,$tpl_type,$i) : '';
			}
		}elseif ($tpl_type == 4){
			for($i=0; $i<4; $i++){
				empty($arrResult['mainImage'][$i]['sname']) ? $arrResult['mainImage'][$i]['sname'] = $this -> getDefaultImage('',true,$tpl_type,$i) : '';
				empty($arrResult['mainImage'][$i]['bname']) ? $arrResult['mainImage'][$i]['bname'] = $this -> getDefaultImage('',false,$tpl_type,$i) : '';
			}
		}elseif ($tpl_type == 6){
			for($i=0; $i<5; $i++){
				empty($arrResult['mainImage'][$i]['sname']) ? $arrResult['mainImage'][$i]['sname'] = $this -> getDefaultImage('',true,$tpl_type,$i) : '';
				empty($arrResult['mainImage'][$i]['bname']) ? $arrResult['mainImage'][$i]['bname'] = $this -> getDefaultImage('',false,$tpl_type,$i) : '';
			}
		}
		
		unset($arrTemp);

		return $arrResult;
	}

	
	public function getDisplayWishlistImage($read='', $StoreID=0, $pid=0, $attrib=-1, $sort=-1, $tpl_type=0){
		$arrResult	=	null;
		$intImagesNum	=	0;
		$intImagesCount	=	0;

		if ($StoreID > 0 && ($pid>0 || $tpl_type > 1)) {
			$_where	=	"where 1 ";
			$_where	.=	" and StoreID='$StoreID'";

			if ($tpl_type > 1) {
				$_where	.=	"and tpl_type='$tpl_type'";
			}elseif ($pid){
				$_where	.=	"and pid='$pid'";
			}

			$attrib > -1 ? $_where .= " and attrib=$attrib": '';
			$sort > -1 ? $_where .= " and sort=$sort": '';

			$_query	=	"select * from ".$this->table."wishlist_image $_where order by attrib, sort";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);

			if ($tpl_type < 1) {
				if (is_array($arrTemp)) {
					$arrResult['imagesCount']	=	count($arrTemp);
					foreach ($arrTemp as $temp){
						if ($temp['attrib']==0) {
							$arrResult['mainImage'][0]['sname']	=	$this -> getDefaultImage($temp['smallPicture'],true,0,$temp['attrib']);
							$arrResult['mainImage'][0]['bname']	=	$this -> getDefaultImage($temp['picture'],false,0,$temp['attrib']);

						}elseif ($temp['attrib']==1){
							$arrResult['subImage'][$temp['sort']]['sname'] = $this -> getDefaultImage($temp['smallPicture'],true,0,$temp['attrib']);
							$arrResult['subImage'][$temp['sort']]['bname'] = $this -> getDefaultImage($temp['picture'],false,0,$temp['attrib']);
						}else {
							$arrResult['planImage'][$temp['sort']]['sname']	= $this -> getDefaultImage($temp['smallPicture'],true,0,$temp['attrib']);
							$arrResult['planImage'][$temp['sort']]['bname']	= $this -> getDefaultImage($temp['picture'],false,0,$temp['attrib']);
						}
					}
				}
			} else {
				if (is_array($arrTemp)) {
					$arrResult['imagesCount']	=	count($arrTemp);
					foreach ($arrTemp as $temp){
						$arrResult['mainImage'][$temp['attrib']]['sname']	=	$this -> getDefaultImage($temp['smallPicture'],true,$tpl_type,$temp['attrib']);
						$arrResult['mainImage'][$temp['attrib']]['bname']	=	$this -> getDefaultImage($temp['picture'],false,$tpl_type,$temp['attrib']);
					}
				}

				if ($tpl_type == 3 ) {
					$intImagesNum	=  2;
				}elseif ($tpl_type == 4){
					$intImagesNum	=  4;
				}else{
					$intImagesNum	=  3;
				}
				for ($i=0; $i<$intImagesNum; $i++){
					empty($arrResult['mainImage'][$i]['sname']) ? $arrResult['mainImage'][$i]['sname'] = $this -> getDefaultImage('',true,$tpl_type,$i) : '';
					empty($arrResult['mainImage'][$i]['bname']) ? $arrResult['mainImage'][$i]['bname'] = $this -> getDefaultImage('',false,$tpl_type,$i) : '';
				}
			}
		}

		if($tpl_type == 0 && $read ==	'auto') {

			empty($arrResult['mainImage'][0]['sname']) ? $arrResult['mainImage'][0]['sname']	= $this -> getDefaultImage('',true,0,0) : '';
			empty($arrResult['mainImage'][0]['bname']) ? $arrResult['mainImage'][0]['bname'] = $this -> getDefaultImage('',false,0,0) : '';
			for($i=0; $i<6; $i++){
				empty($arrResult['subImage'][$i]['sname'])	? $arrResult['subImage'][$i]['sname'] = $this -> getDefaultImage('',true,0,1) : '';
				empty($arrResult['subImage'][$i]['bname'])	? $arrResult['subImage'][$i]['bname'] = $this -> getDefaultImage('',false,0,1) : '';
			}

		}elseif($tpl_type == 0 && $read	==	'estate') {

			empty($arrResult['mainImage'][0]['sname']) ? $arrResult['mainImage'][0]['sname'] = $this -> getDefaultImage('',true,0,0) : '';
			empty($arrResult['mainImage'][0]['bname']) ? $arrResult['mainImage'][0]['bname'] = $this -> getDefaultImage('',false,0,0) : '';
			for($i=0; $i<6; $i++){
				empty($arrResult['subImage'][$i]['sname'])	? $arrResult['subImage'][$i]['sname'] = $this -> getDefaultImage('',true,0,1) : '';
				empty($arrResult['subImage'][$i]['bname'])	? $arrResult['subImage'][$i]['bname'] = $this -> getDefaultImage('',false,0,1) : '';
			}
			empty($arrResult['planImage'][0]['sname']) ? $arrResult['planImage'][0]['sname'] = $this -> getDefaultImage('',true,0,2) : '';
			empty($arrResult['planImage'][0]['bname']) ? $arrResult['planImage'][0]['bname'] = $this -> getDefaultImage('',false,0,2) : '';

		}elseif($tpl_type == 0 && $read ==	'store') {

			empty($arrResult['mainImage'][0]['sname']) ? $arrResult['mainImage'][0]['sname']	= $this -> getDefaultImage('',true,0,0) : '';
			empty($arrResult['mainImage'][0]['bname']) ? $arrResult['mainImage'][0]['bname'] = $this -> getDefaultImage('',false,0,0) : '';
			for($i=0; $i<6; $i++){
				empty($arrResult['subImage'][$i]['sname'])	? $arrResult['subImage'][$i]['sname'] = $this -> getDefaultImage('',true,0,1) : '';
				empty($arrResult['subImage'][$i]['bname'])	? $arrResult['subImage'][$i]['bname'] = $this -> getDefaultImage('',false,0,1) : '';
			}		}elseif ($tpl_type == 2){
			for($i=0; $i<3; $i++){
				empty($arrResult['mainImage'][$i]['sname']) ? $arrResult['mainImage'][$i]['sname'] = $this -> getDefaultImage('',true,$tpl_type,$i) : '';
				empty($arrResult['mainImage'][$i]['bname']) ? $arrResult['mainImage'][$i]['bname'] = $this -> getDefaultImage('',false,$tpl_type,$i) : '';
			}
		}elseif ($tpl_type == 3){
			for($i=0; $i<2; $i++){
				empty($arrResult['mainImage'][$i]['sname']) ? $arrResult['mainImage'][$i]['sname'] = $this -> getDefaultImage('',true,$tpl_type,$i) : '';
				empty($arrResult['mainImage'][$i]['bname']) ? $arrResult['mainImage'][$i]['bname'] = $this -> getDefaultImage('',false,$tpl_type,$i) : '';
			}
		}elseif ($tpl_type == 4){
			for($i=0; $i<4; $i++){
				empty($arrResult['mainImage'][$i]['sname']) ? $arrResult['mainImage'][$i]['sname'] = $this -> getDefaultImage('',true,$tpl_type,$i) : '';
				empty($arrResult['mainImage'][$i]['bname']) ? $arrResult['mainImage'][$i]['bname'] = $this -> getDefaultImage('',false,$tpl_type,$i) : '';
			}
		}

		unset($arrTemp);

		return $arrResult;
	}

	/**
	 * set images to save
	 *
	 * @param string $read
	 * @param array $arrImages
	 * @param int $StoreID
	 * @param int $pid
	 * @param int $attrib
	 * @param int $tpl_type
	 * @return boolean
	 */
	public function setDisplayImage($read='', $arrImages, $StoreID=0, $pid=0, $attrib=0, $tpl_type=0){
		$booleanResult	=	null;

		foreach ($arrImages as $key => $temp ){
			$arrSetting	= $temp;

			if ($tpl_type >= 1) {
				$attrib					=	$key;
				$arrSetting['sort']		=	0;
			}else {
				$arrSetting['sort']	=	$key;
			}
			
			if($this -> saveImageToDB($arrSetting,$StoreID,$pid,$attrib,$tpl_type)){
				$booleanResult	=	true;
			}

		}

		return $booleanResult;
	}

	/**
	 * save image to database
	 *
	 * @param array $arrImage
	 * @param int $StoreID
	 * @param int $pid
	 * @param int $attrib
	 * @param int $tpl_type
	 * @return array
	 */
	public function saveImageToDB ($arrImage, $StoreID=0, $pid=0, $attrib=0, $tpl_type=0) {
		$booleanResult	=	false;
		$dateNow		=	time();

		if (is_array($arrImage)) {
			extract($arrImage);

			$strCondition = "where StoreID='$StoreID' and pid='$pid' and attrib='$attrib' and sort='$sort' and tpl_type='$tpl_type'";
			$_title	= "`id`,`smallPicture`,`picture`";
			$_query	= "select $_title from ".$this->table."image $strCondition";
			$this->dbcon->execute_query($_query);
			$arrTemp = $this->dbcon->fetch_records(true);

			if (is_array($arrTemp)) {
				$arrTemp	=	$arrTemp[0];
				if ($arrTemp['smallPicture'] != $simage ) {
					if ($this ->__checkImagePath($simage)==1) {
						$arrSetting	=	array(
						'smallPicture' => $this -> __copyTempToLive($simage),
						'picture'	   => $this -> __copyTempToLive($bimage),
						'datem'		   => $dateNow
						);
						if($this->dbcon->update_record($this->table."image", $arrSetting, $strCondition)){
							$booleanResult	=	true;
							$this -> deleteImage(array('simage'=>$arrTemp['smallPicture'], 'bimage'=>$arrTemp['picture']));
						}
					}elseif ($this ->__checkImagePath($simage) == 0){
						$_query	=	"delete from " . $this -> table ."image where id='".$arrTemp['id']."'";
						if($this->dbcon->execute_query($_query)){
							$booleanResult	=	true;
							$this -> deleteImage(array('simage'=>$arrTemp['smallPicture'], 'bimage'=>$arrTemp['picture']));
						}
					}

				}else{
					$booleanResult	=	true;
				}

			}else{

				if ($this ->__checkImagePath($simage)==1) {
					$arrSetting	=	array(
					'smallPicture'	=>  $this -> __copyTempToLive($simage),
					'picture'		=>	$this -> __copyTempToLive($bimage),
					'StoreID'		=>	$StoreID,
					'pid'			=>	$pid,
					'attrib'		=>	$attrib,
					'tpl_type'		=>	$tpl_type,
					'sort'			=>	$sort,
					'datec'			=>	$dateNow,
					'datem'			=>	$dateNow
					);

					if($this->dbcon->insert_record($this->table."image", $arrSetting)){
						$booleanResult	=	true;
					}
				}else{
					if($this->__imageExist($simage)&&$this->__imageExist($bimage)){
						if($simage=='/images/243x212.jpg'||$simage=='/images/79x79.jpg'){
						}else{
							$arr_type = explode(".", $simage);
							$type = $arr_type[count($arr_type)-1];
							$tempNum	=	"0000" . rand(0,9999);
							$aNewName	= 	date("ymdh").rand(0,9999).substr($tempNum,strlen($tempNum)-4,4);
							$new_simage = $aNewName."_s.".$type;
							$new_bimage = $aNewName.".".$type;
							
							$arrSetting	=	array(
								'smallPicture'	=>  $this -> __copyTempToLive($simage,$new_simage),
								'picture'		=>	$this -> __copyTempToLive($bimage,$new_bimage),
								'StoreID'		=>	$StoreID,
								'pid'			=>	$pid,
								'attrib'		=>	$attrib,
								'tpl_type'		=>	$tpl_type,
								'sort'			=>	$sort,
								'datec'			=>	$dateNow,
								'datem'			=>	$dateNow
							);
							if($this->dbcon->insert_record($this->table."image", $arrSetting)){
								$booleanResult	=	true;
							}
						}
					}
				}
			}

		}

		return $booleanResult;
	}


	/**
	 * get default images
	 *
	 * @param string $imagePath
	 * @param boolean $isSmall
	 * @param int $tpl_type
	 * @param int $attrib
	 * @param int $sizeType
	 * @return array   {text:'',width:'',height:''}
	 */
	public function getDefaultImage($imagePath, $isSmall=true, $tpl_type=0, $attrib=0, $sizeType=0){

		$arrResult	=	null;

		if ($sizeType > 0 ) {

			$arrResult	=	$this -> __setImagesSize($imagePath, $sizeType);

		}elseif ($tpl_type < 1) {

			if ( $attrib == 1 || $attrib == 2 ) {
				if($isSmall){
					$arrResult	=	$this -> __setImagesSize($imagePath,4);
				}else {
					$arrResult	=	$this -> __setImagesSize($imagePath,9);
				}

			}else{
				if($isSmall){
					$arrResult	=	$this -> __setImagesSize($imagePath,2);
				}else {
					$arrResult	=	$this -> __setImagesSize($imagePath,9);
				}
			}

		}else{
			switch ("$tpl_type"){
				case	'1':
					if ($attrib==0) {
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,107);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,7);
						}
					}elseif ($attrib==1){
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,108);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,8);
						}
					}

					break;

				case	'2':
					if ($attrib==0) {
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,107);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,7);
						}
					}elseif ($attrib==1){
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,110);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,10);
						}
					}else {
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,106);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,6);
						}
					}
					break;
					
				case	'3':
					if ($attrib==0) {
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,107);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,7);
						}
					}elseif ($attrib==1){
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,108);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,8);
						}
					}

					break;

				case	'4':
					if ($attrib==0) {
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,107);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,7);
						}
					}elseif ($attrib==1){
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,108);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,8);
						}
					}elseif ($attrib==2){
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,111);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,11);
						}
					}else{
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,106);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,6);
						}
					}
					break;
					
				case	'6':
					if ($attrib == 2){
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,13); 
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,113);
						}
					}else{
						if($isSmall){
							$arrResult	=	$this -> __setImagesSize($imagePath,14);
						}else {
							$arrResult	=	$this -> __setImagesSize($imagePath,114);
						}
					}

					break;
				case	'7':
				
					$arrResult	=	$this -> __setImagesSize($imagePath,115);
					
					break;
				

				default:
					break;
			}
		}

		return $arrResult;
	}

	/**
	 * set images size
	 *
	 * @param array $arrImage    {text:'',width:'',height:''}
	 * @param int $sizeType
	 * @return array
	 */
	private function __setImagesSize($imagePath, $sizeType = 0){
		$arrResult	=	null;
		$hasImages	=	false;

		if (!empty($imagePath) && $this->__imageExist( $imagePath )) {
			$hasImages	=	true;
			$basePath	=	substr(ROOT_PATH, 0 , strlen(ROOT_PATH)-1);
			$arrImageInfo	= getimagesize($basePath. $imagePath);
		}
                elseif(preg_match('/^http/i', $imagePath)) {
                    $hasImages = true;
                    $basePath	=	$imagePath;
                    $arrImageInfo	= @getimagesize($imagePath);
//                    echo  $imagePath.'<br/>';
                }

		if ($sizeType	==	0){
			$arrResult	=	$arrImage;
		}elseif ($sizeType == 1) {
			!$hasImages ? $imagePath	=	'/images/172x127.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>172,'height'=>127);
		}elseif ($sizeType == 2){
			!$hasImages ? $imagePath	=	'/images/243x212.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>243,'height'=>212);
		}elseif ($sizeType == 3){
			!$hasImages ? $imagePath	=	'/images/282x195.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>282,'height'=>195);
		}elseif ($sizeType == 4){
			!$hasImages ? $imagePath	=	'/images/79x79.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>79,'height'=>79);
		}elseif ($sizeType == 5){
			!$hasImages ? $imagePath	=	'/images/79x79.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>65,'height'=>31);
		}elseif ($sizeType == 6){
			!$hasImages ? $imagePath	=	'/images/72x100.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>72,'height'=>100);
		}elseif ($sizeType == 7){
			!$hasImages ? $imagePath	=	'/images/243x100.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>243,'height'=>100);
		}elseif ($sizeType == 8){
			!$hasImages ? $imagePath	=	'/images/755x100.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>750,'height'=>100);
		}elseif ($sizeType == 9){
			!$hasImages ? $imagePath	=	'/images/700x525.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>700,'height'=>525);
		}elseif ($sizeType == 10){
			!$hasImages ? $imagePath	=	'/images/750x50.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>750,'height'=>50);
		}elseif ($sizeType == 11){
			!$hasImages ? $imagePath	=	'/images/500x115.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>500,'height'=>115);
		}elseif ($sizeType == 13){
			!$hasImages ? $imagePath	=	'/images/121x100.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>121,'height'=>100);
		}elseif ($sizeType == 14){
			!$hasImages ? $imagePath	=	'/images/253x105.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>253,'height'=>105);
		}elseif ($sizeType == 15){
			!$hasImages ? $imagePath	=	'/images/79x79.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>140,'height'=>140);
		}elseif ($sizeType == 16){
			!$hasImages ? $imagePath	=	'/images/80x58.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>140,'height'=>100);
		}elseif ($sizeType == 106){
			!$hasImages ? $imagePath	=	'/images/72x100.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>72,'height'=>100);
		}elseif ($sizeType == 107){
			!$hasImages ? $imagePath	=	'/images/243x100.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>243,'height'=>100);
		}elseif ($sizeType == 108){
			!$hasImages ? $imagePath	=	'/images/250x34.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>250,'height'=>34);
		}elseif ($sizeType == 110){
			!$hasImages ? $imagePath	=	'/images/250x17.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>250,'height'=>17);
		}elseif ($sizeType == 111){
			!$hasImages ? $imagePath	=	'/images/250x57.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>250,'height'=>57);
		}elseif ($sizeType == 112){
			!$hasImages ? $imagePath	=	'/images/497x195.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>497,'height'=>195);
		}elseif ($sizeType == 113){
			!$hasImages ? $imagePath	=	'/images/242x201.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>242,'height'=>201);
		}elseif ($sizeType == 114){
			!$hasImages ? $imagePath	=	'/images/497x206.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>497,'height'=>206);
		}elseif ($sizeType == 115){
			!$hasImages ? $imagePath	=	'/images/497x206.jpg' : '';
			$arrResult	=	array('text'=>$imagePath, 'width'=>560,'height'=>130);
		}else{
			!$hasImages ? $imagePath	=	'/images/50x50.jpg' : '';
			$arrResult	=	array('text'=> $imagePath, 'width'=>50,'height'=>50);
		}

		if ($sizeType>0) {
			$IW	=	$arrResult['width'];
			$IH	=	$arrResult['height'];
			if ($arrImageInfo[0] > 0 && $arrImageInfo[1] >0) {
				$actual_width = $arrImageInfo[0];
				$actual_height = $arrImageInfo[1];

				$height	=	$IH;
				$width	=	$IW;

				if ($IW/$IH > $actual_width/$actual_height) {
					$height	= $IH ;
					$width  = round($height * $actual_width / $actual_height , 0) ;
				}else{
					$width	= $IW;
					$height	= round($width / ($actual_width / $actual_height) , 0) ;
				}


				$arrResult['width']		=	$width;
				$arrResult['height']	=	$height;
			}
		}

		return $arrResult;
	}

	private function __copyTempToLive($strImagePathTemp,$desfilename=""){
		$strResult	=	'';

		$strResult = "/upload/userImages/" . date("Ym");
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
			if($this ->__checkImagePath($strImagePathTemp)==1){
				@unlink($basePath.$strImagePathTemp);
			}
		}

		return $strResult;
	}

	public function __checkImagePath($imagePath){
		$intResult	=	0;

		if (!empty($imagePath)) {
			if(strpos($imagePath, '/temp/') !== false ){
				$intResult	=	1;
			}elseif (strpos($imagePath, '/userImages/') !== false ){
				$intResult	=	2;
			}elseif (strpos($imagePath, '/wishlist/') !== false ){
				$intResult	=	2;
			}
		}

		return $intResult;
	}

	private function __imageExist($imagePath){
		$booleanResult	=	false;

		$basePath	=	substr(ROOT_PATH, 0 , strlen(ROOT_PATH)-1);
		if (!empty($imagePath) && file_exists($basePath .$imagePath)) {
			$booleanResult	=	true;
		}

		return $booleanResult;
	}

        function getFolderImages($dir,$copy_dir) {
            $arr_file=array();
            if($handle = opendir($dir)) {
                while($file=readdir($handle)) {
                    if(!in_array($file,array('.','..'))) {
                        if(is_dir($dir.'/'.$file)) {
                            $arr_tmp=$this->getFolderImages($dir.'/'.$file,$copy_dir);
                            foreach($arr_tmp as $key=>$value) {
                                /*$new_name=$value;
                                if(in_array($key,$arr_file)) {
                                    $new_name=substr($value,0,-4).'_1'.substr($value,-4,4);
                                    $arr_file[$new_name]=$new_name;
                                }
                                else {
                                    $arr_file[$new_name]=$new_name;
                                }*/
                                
                                //$new_name=substr($value,0,-4).'_'.date('His').'_'.rand(1000, 9999).substr($value,-4,4);
                                $arr_file[]=$value;
                                copy($dir.'/'.$value, $copy_dir.'/'.$value);
                            }
                        }
                        else {
                            /*
                            $new_name=$file;
                            if(in_array($file,$arr_file)) {
                                    $new_name=substr($file,0,-4).'_1'.substr($file,-4,4);
                                    $arr_file[$new_name]=$new_name;
                            }
                            else {
                                    $arr_file[$file]=$new_name;
                            }*/
                            $new_name=substr($file,0,-4).'    '.date('His').'_'.rand(1000, 9999).substr($file,-4,4);
                            $arr_file[]=$new_name;
                            copy($dir.'/'.$file, $copy_dir.'/'.$new_name);
                        }
                    }
                }
                foreach($arr_file as $key=>$value) {
                    $ex = strtolower(substr($value,-4,4));
                    if(!in_array($ex, array('.jpg','.png','.gif'))) {
                        unset($arr_file[$key]);
                    }
                }
                return $arr_file;
            }
            else
                return array();
        }
/*
        function bl($dir,$copy_dir) {
            $arr_file=array();
            if($handle=opendir($dir)) {
                while($file=readdir($handle)) {
                    var_dump($file);
                    if(in_array($file,array('.','..'))) continue;
                    if(is_dir($dir.'/'.$file)) {
                        $tmp=$this->bl($dir.'/'.$file,$copy_dir);
                        foreach($tmp as $key=>$val) {
                            $new_name=$key;
                            if(in_array($key,$arr_file)) {
                                $new_name='1_'.$key;
                                $arr_file[$new_name]=$new_name;
                            }
                            else {
                                $arr_file[$key]=$key;
                            }
                            //copy($dir.'/'.$key, $copy_dir.'/'.$new_name);
                            echo '---'.$key.'-----'.$new_name.'<br/>';
                        }
                    }
                    else {
                        $new_name=$file;
                        if(in_array($file,$arr_file)) {
                            $new_name='1_'.$file;
                            $arr_file[$new_name]=$new_name;
                        }
                        else {
                            $arr_file[$new_name]=$new_name;
                        }
                        //copy($dir.'/'.$file, $copy_dir.'/'.$new_name);
                        echo '---'.$file.'-----'.$new_name.'<br/>';
                    }
                }
                return $arr_file;
            }
            return array();
        }
*/


        function getzipProductMoreImages($zip){
		$uploadclass = new uploadFile();
		$uploadclass->_uploadType = 12;
		$za = new ZipArchive();
                $tmpary=array();
                $za->open($zip['tmp_name']);
		$folder = randStr(8);
		while(file_exists(ROOT_PATH.'/upload/temp/'.$folder)){
			$folder = randStr(8);
		}
		mkdir(ROOT_PATH.'/upload/temp/'.$folder);
                $copy_dir=ROOT_PATH.'/upload/temp/'.$folder.'_copy___';
                mkdir($copy_dir);
		$za->extractTo(ROOT_PATH.'/upload/temp/'.$folder.'/');
                $tmpary=$this->getFolderImages(ROOT_PATH.'/upload/temp/'.$folder.'',$copy_dir);
                foreach($tmpary as $key=>$val) {
                    $tmpary[$val]=$val;
                    unset($tmpary[$key]);
                }
		$za->close();
		$imgary = array();
		$i=0;
		foreach ($tmpary as $key=>$value){
			$destfile = $uploadclass->getNewFileName($value,'s');
			$dstfile = $this->__copyTempToLive('/upload/temp/'.$folder.'_copy___/'.$value,$destfile[0]);

			$smdstfile = str_replace($destfile[0],$destfile[1],$dstfile);
			$tmpsize = $this->__setImagesSize($dstfile,2);
			$uploadclass->MakeImage(ROOT_PATH.$dstfile,ROOT_PATH.$smdstfile,$tmpsize['width'],$tmpsize['height'],true,100,true);
			$imgary[$value]['tmpname']=$value;
			$imgary[$value]['picture']=$dstfile;
			$imgary[$value]['smallpicture']=$smdstfile;
			$i++;
		}
		@$this->full_rmdir(ROOT_PATH.'/upload/temp/'.$folder);
                @$this->full_rmdir($copy_dir);
		return $imgary;
	}



	function getzipProductImages($zip){
		$uploadclass = new uploadFile();
		$uploadclass->_uploadType = 12;
		$za = new ZipArchive();
		$za->open($zip['tmp_name']);
		$images = array();
		$tmpary = array();
		for ($i=0; $i<$za->numFiles;$i++) {
			$entry = $za->statIndex($i);
			$ex = strtolower(substr($entry['name'],-4,4));
			if ($entry['size']==0||strpos($entry['name'],'/')!==false||($ex!='.jpg' && $ex!='.gif' && $ex != '.png')){
                           //
			}else{
				$images[$entry['name']] = $entry['size'];
				$tmpary[$entry['name']] = $entry;
			}
		}
		$folder = randStr(8);
		while(file_exists(ROOT_PATH.'/upload/temp/'.$folder)){
			$folder = randStr(8);
		}
		mkdir(ROOT_PATH.'/upload/temp/'.$folder);
		$za->extractTo(ROOT_PATH.'/upload/temp/'.$folder.'/');
                
		$za->close();
		$imgary = array();
		$i=0;
		foreach ($tmpary as $key=>$value){
			$destfile = $uploadclass->getNewFileName($key,'s');
			$dstfile = $this->__copyTempToLive('/upload/temp/'.$folder.'/'.$key,$destfile[0]);
			
			$smdstfile = str_replace($destfile[0],$destfile[1],$dstfile);
			$tmpsize = $this->__setImagesSize($dstfile,2);
			$uploadclass->MakeImage(ROOT_PATH.$dstfile,ROOT_PATH.$smdstfile,$tmpsize['width'],$tmpsize['height'],true,100,true);
			$imgary[$key]['tmpname']=$key;
			$imgary[$key]['picture']=$dstfile;
			$imgary[$key]['smallpicture']=$smdstfile;
			$i++;
		}
		$this->full_rmdir(ROOT_PATH.'/upload/temp/'.$folder);
		return $imgary;
	}

	function saveImageInfo($imageinfo){
		$uploadclass = new uploadFile();
		$value = "";
		foreach ($imageinfo as $image){
			if(!isset($image['pid'])||$image['pid']==''){
				$basePath	=	substr(ROOT_PATH, 0 , strlen(ROOT_PATH)-1);
				//@ unlink($basePath . $image['picture']);
				//@ unlink($basePath . $image['smallPicture']);
				continue;
			}else{
				$value .= $value==''?"":",";
				if($image['attrib']==0&&$image['sort']==0){
					$srcfile = ROOT_PATH.$image['picture'];
					$dstfile = ROOT_PATH.$image['smallPicture'];
					$tmpsize = $this->__setImagesSize($image['picture'],2);
					unlink($dstfile);
					$uploadclass->MakeImage($srcfile,$dstfile,$tmpsize['width'],$tmpsize['height'],true,100,true);
				}
				$value .= "('{$image['StoreID']}','{$image['tpl_type']}','{$image['pid']}','{$image['smallPicture']}','{$image['picture']}','{$image['attrib']}','{$image['sort']}','{$image['datec']}','{$image['datem']}')";
			}
		}
		if($value!=""){
			$sql = "insert into {$this->table}image (`StoreID`,`tpl_type`,`pid`,`smallPicture`,`picture`,`attrib`,`sort`,`datec`,`datem`) values".$value;
			if($this->dbcon->execute_query($sql)){
				return true;
			}else {
				return false;
			}
		}else{
			return true;
		}
	}
	
	function full_rmdir($dirname){
        if ($dirHandle = opendir($dirname)){
            $old_cwd = getcwd();
            chdir($dirname);

            while ($file = readdir($dirHandle)){
                if ($file == '.' || $file == '..') continue;

                if (is_dir($file)){
                    if (!$this->full_rmdir($file)) return false;
                }else{
                    if (!unlink($file)) return false;
                }
            }

            closedir($dirHandle);
            chdir($old_cwd);
            if (!rmdir($dirname)) return false;

            return true;
        }else{
            return false;
        }
    }

}

?>