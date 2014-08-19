<?php

/**
 * this class are uploads files for Image or not Image
 * created date : 2008-01-28
 * @author Ping.Hu <support@infinitytesting.com.au>
 * @version 3.0.1
 */
class uploadFile{


	var $newFileName 	=	"" ;
	var $newFileNameSmall	=	"";
	var $newFileFullName=	"";
	var $newFileFullNameSmall=	"";

	var $strMessage		=	"";
	var $strUploadPath	=	"";
	var $newFileSize	=	null;


	var $_width 		= 	50 ;
	var $_height		=	50 ;
	var $_watermark		=	"" ;
	var $_gdinfo		=	"" ;
	var $_uploadType	=	0;
	var $_hasScale		=	true;
	/**
	 * file's max size
	 * unit KB
	 * @var float
	 */
	var $_maxSize		=	1024;

	var $_arrUpFile		=	null;



	/**
	 * create a object for upload files
	 *
	 * @param int $width
	 * @param int $height
	 * @param int $maxSize
	 * @param int $intUpType
	 * @param array $arrUpFile
	 * @param string $waterark
	 * @return void
	 */
	function __construct($width=0,$height=0, $maxSize=0, $intUpType=0, $arrUpFile=null, $watermark=""){
		if (is_array($arrUpFile)) {
			$this->_arrUpFile = &$arrUpFile;

			$this -> _uploadType = $intUpType;

			if ($this -> _checkGD()) {
				$this -> _width 	= 	$width > 0 ? $width : $this -> _width ;
				$this -> _height	=	$height > 0 ? $height : $this -> _height ;
				$this -> _maxSize	=	$maxSize >0 ? $maxSize : $this -> _maxSize;
				$this -> _watermark	=	empty($watermark) ? $this->_watermark : $watermark;
			}
		}
		else
		{
			$this -> strMessage		=	"Upload file Error.";
		}
	}

	/**
	 * check GD base
	 *
	 * @return boolean
	 */
	function _checkGD()
	{
		$boolResult = false;
		$this -> _gdinfo = gd_info() or die ("Cannot Initialize new GD image stream");
		if (is_array($this -> _gdinfo) && count($this -> _gdinfo)) {
			$boolResult = true;
		}
		return $boolResult;
	}

	/**
	 * check file's image
	 *
	 * @param string $type
	 * @return boolean
	 */
	function fileIsImage($type){
		$boolResult = false;
		if(($type=="image/pjpeg")||($type == "image/jpeg")||($type=="image/jpg")||($type=="image/gif")||($type=="image/x-png")||($type=="image/png")){
			$boolResult = true;
		}
		return $boolResult;
	}

	function fileCanUpload($fileURL){
		$boolResult = false;

		$exfile	=	strtolower($this -> getFileType($fileURL));
		if ($exfile != 'exe' && $exfile != 'sh' && $exfile != 'php' && $exfile != 'js' && $exfile != 'html' && $exfile != 'htm') {
			$boolResult	=	true;
		}

		return $boolResult;
	}

	/**
	 * check file's size
	 *
	 * @param int $num
	 * @param int $setsize
	 * @return boolean
	 */
	function checkOutSize($num){
		$boolResult = false;

		if ($num>0 && $num > $this->_maxSize * 1024){
			$boolResult = true;
		}

		return $boolResult;
	}

	/**
	 * check images's width or height
	 *
	 * @param string $filename
	 * @param int $filewidth
	 * @param int $fileheight
	 * @return boolean
	 */
	function checkImgOutWH($filename,$filewidth,$fileheight){
		$boolResult = false;
		$pic=GetImageSize($filename);
		if ($pic[0]>$filewidth || $pic[0]>$fileheight){
			$boolResult = true;
		}
		return $boolResult;
	}

	/**
	 * get file's spread name
	 *
	 * @param string $url
	 * @return string
	 */
	function getFileType($url)
	{
		$arr_type = array();
		$arr_type = explode(".", $url);
		$type = $arr_type[count($arr_type)-1];

		return $type;
	}

	/**
	 * get file's path
	 *
	 * @param  int $intGetType
	 * @return string
	 */
	function getFilePath($intGetType=0){
		$strResult = "";
		switch ($this->_uploadType)
		{
			case 1:
				$strResult = "upload/logoImage/";
				break;

			case 2:
				$strResult = "upload/logo/";
				break;

			case 3:
				$strResult = "upload/mainImage/";
				break;

			case 4:
				$strResult = "upload/specials/";
				break;

			case 5:
				$strResult = "upload/ProductImage/";
				break;

			case 6:
				$strResult = "upload/VoucherImage/";
				break;

			case 7:
				$strResult = "upload/pdf/";
				break;

			case 8:
				$strResult = "upload/new/";
				break;

			case 9:
				$strResult = "/upload/blogImage/";
				break;

			case 10:
				$strResult = "upload/temp/";
				break;

			case 11:
				$strResult = "upload/attachment/";
				break;
			case 12:
				$strResult = "/upload/userImages/";
				break;
			case 13:
				$strResult = "skin/red/images/wishlist_banner/user/";
				break;
			case 14:
				$strResult = "skin/red/invitations/user/";
				break;
			case 15:
				$strResult = "upload/wishlist/gallery/";
				break;
			case 16:
				$strResult = "upload/wishlist/gallery_photo/";
				break;		
			default:
				$strResult = "upload/banner/";
				break;
		}

		if (!empty($strResult)) {
			$strDrectory	=	str_replace("\\/", "/", str_replace("//", "/", ROOT_PATH . $strResult));

			if (!file_exists( $strDrectory)) {
				$GLOBALS['boolisWin'] ? mkdir( $strDrectory ) :mkdir( $strDrectory ,0755);
			}

			if ($intGetType == 0) {
				$strResult = ($this->strUploadPath == "" ? str_replace("\\","/",realpath(".")) . "/" : $this->strUploadPath . "/")  . $strResult;
			}
		}

		return $strResult;
	}

	/**
	 * get file's name for new
	 *
	 * @param string $filename
	 * @param string $offal
	 * @return string
	 */
	function getNewFileName($filename, $offal=''){
		$tempNum	=	"0000" . rand(0,9999);
		$aNewName	= 	date("ymdh").rand(0,9999).substr($tempNum,strlen($tempNum)-4,4);
		if (empty($offal)) {
			$aNewName .=  "." . $this->getFileType($filename);
		}else {
			$aNewName	=	array( $aNewName. "." . $this->getFileType($filename) , $aNewName. "_$offal." . $this->getFileType($filename));
		}

		return $aNewName;
	}

	/**
	* create small images
	* @param String  $srcFile   source file' path
	* @param String  $dstFile   small picture path
	* @param String  $dstW      small picture width
	* @param String  $dstH      small picture height
	* @param boolean $hasScale
	* @param int     $clarity
	* @return boolean
	*/
	function MakeImage($srcFile,$dstFile,$dstW=0,$dstH=0, $hasScale=true, $clarity=100,$isroot=false) {
		$boolResult = false;
		if(!$isroot){
			$dstFile	=	$this -> getFilePath() . $dstFile;
		}
		$data = @getimagesize($srcFile);

		if (is_array($data)) {

			switch ($data[2]) {
				case 1:
					$source = @imagecreatefromgif($srcFile);
					break;
				case 2:
					$source = @imagecreatefromjpeg($srcFile);
					break;
				case 3:
					$source = @imagecreatefrompng($srcFile);
					break;
			}

			list($width, $height) = getimagesize($srcFile);

			$dstW	=	$dstW > 0 ? $dstW : $this->_width;
			$dstH	=	$dstH > 0 ? $dstH : $this->_height;
			if ($hasScale) {
				if($width > $height){
					$dstH2 = $dstW / $width * $height ;
					if($dstH2>$dstH){
						$dstW = $dstH / $height * $width ;
					}else{
						$dstH = $dstH2;
					}
				}else{
					$dstW2 = $dstH / $height * $width ;
					if($dstW2>$dstW){
						$dstH = $dstW / $width * $height ;
					}else{
						$dstW = $dstW2;
					}
				}
			}

			$thumb = @imagecreatetruecolor($dstW, $dstH);
			@imagecopyresampled($thumb,$source,0,0,0,0,$dstW,$dstH,$width,$height);
			switch ($data[2]) {
				case 1:
					@imagegif($thumb,$dstFile);
					break;
				case 2:
					imagejpeg($thumb,$dstFile,$clarity);
					break;
				case 3:
					@imagepng($thumb,$dstFile);
					break;
			}
			@imagedestroy($thumb);
		}

		if (file_exists($dstFile)) {
			$boolResult = true;
		}

		return $boolResult;
	}

	/**
	 * upload files
	 *
	 * @return boolean
	 */
	function upload(){
		$boolResult		=	false;

		if ($this->_arrUpFile["name"]) {
			$this -> newFileName	= $this -> getNewFileName($this->_arrUpFile["name"]);
			$this -> newFileFullName = $this -> getFilePath(1) . $this -> newFileName;

			if (!$this->fileIsImage($this->_arrUpFile["type"])) {
				$this -> strMessage		=	"File type is not compatible.";

			}elseif ($this->checkOutSize($this->_arrUpFile["size"])) {
				$this -> strMessage		=	"File is oversize. ";
			}elseif($this -> MakeImage($this->_arrUpFile["tmp_name"],$this -> newFileName)){
				$this -> strMessage		=	"File has been updated successfully.";
				$boolResult = true;
			}else {
				$this -> strMessage		=	"File operation failed. ";
			}
		}else {
			$this -> strMessage		=	"File operation failed. ";
		}

		return $boolResult;
	}

	/**
	 * upload image
	 *
	 * @return boolean
	 */
	function uploadAndSmallPic($smallWidth=0, $smallHeight=0){
		$boolResult		=	false;

		if ($this->_arrUpFile["name"]) {
			$arrTemp	=	$this -> getNewFileName($this->_arrUpFile["name"],'s');
			$this -> newFileName		= 	$arrTemp[0];
			$this -> newFileNameSmall	=	$arrTemp[1];

			$strFilePath	=	$this -> getFilePath(1);
			$this -> newFileFullName = $strFilePath . $this -> newFileName;
			$this -> newFileFullNameSmall = $strFilePath . $this -> newFileNameSmall;

			if (!$this->fileIsImage($this->_arrUpFile["type"])) {
				$this -> strMessage		=	"File type is not compatible.";

			}elseif ($this->checkOutSize($this->_arrUpFile["size"])) {

				$this -> strMessage		=	"File is oversize. ";

			}elseif(! $this -> MakeImage($this->_arrUpFile["tmp_name"], $this -> newFileNameSmall, $smallWidth ,$smallHeight )){

				$this -> strMessage		=	"You're unsuccessful in creating the thumbnail. Please try again.";

			}elseif($this -> MakeImage($this->_arrUpFile["tmp_name"],$this -> newFileName)){

				$arrTemp	=	getimagesize($this -> newFileFullName);
				$arrTemp1	=	getimagesize($this -> newFileFullNameSmall);
				$this -> newFileSize = array(0,$arrTemp[0],$arrTemp[1],0,$arrTemp1[0],$arrTemp1[1]);
				unset($arrTemp, $arrTemp1);

				$this -> strMessage		=	"File has been updated successfully.";
				$boolResult = true;

			}else {
				$this -> strMessage		=	"File operation failed. Please try again. ";
			}
		}else {
			$this -> strMessage		=	"File operation failed. Please try again. ";
		}

		return $boolResult;
	}



	function uploadOther(){
		$boolResult		=	false;
		if ($this->_arrUpFile["name"]) {
			$this -> newFileName	= $this -> getNewFileName($this->_arrUpFile["name"]);
			$this -> newFileFullName = $this -> getFilePath(11) . $this -> newFileName;

			if (!$this->fileCanUpload($this->_arrUpFile["name"])) {
				$this -> strMessage		=	"File type is not compatible.";
			}elseif ($this->checkOutSize($this->_arrUpFile["size"])) {
				$this -> strMessage		=	"File is oversize. ";
			}elseif(move_uploaded_file($this->_arrUpFile["tmp_name"], $this -> newFileFullName)){
				$this -> strMessage		=	"File has been updated successfully.";
				$boolResult = true;
			}else {
				$this -> strMessage		=	"File operation failed.2";
			}
		}else {
			$this -> strMessage		=	"File operation failed. ";
		}

		return $boolResult;
	}



	/**
    * @return void 
    */
	function __destruct(){
		unset($this->_width,$this->_height);
	}


}

?>