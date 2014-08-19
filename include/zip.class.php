<?php
/**
 * Sat Feb 14 15:31:16 GMT 2009 15:31:16
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * zip serve class and function
 * ------------------------------------------------------------
 * WEB-INF\zip.class.php
 */
 
define('PBRootPath', (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : ""));
define( 'ARCHIVE_ZIP_TEMPORARY_DIR', PBRootPath. "/upload/" );
define( 'ARCHIVE_ZIP_CREATE_DIR', PBRootPath. "/upload/ZIP/" );

include_once('class/common.php');
require_once("Archive/Zip.php");

class zipClass extends common {
	var $zipObj			=	null;
	var $zipName		=	'';
	var $zipSavePath	=	'';
	var $zipSaveFiles	=	array();
	var $msg			=	'';
	function __construct($zipName = '', $onlyUse = false, $savePath=''){
		$this -> createZipFileName($zipName, $onlyUse);
		$this-> __checkPath($savePath);
	}

	function __destruct(){
		unset($this -> zipObj, $this->error, $this->zipSaveFiles);

	}

	function create($fileList = ''){
		$this -> addFileList($fileList);
		$this -> zipObj	= new Archive_Zip($this -> zipSavePath . $this->zipName);
		$this -> zipObj -> create($this -> zipSaveFiles);
		if($this -> zipObj -> errorCode() <= 0 ){
			$this -> msg 	= $this -> zipObj -> errorInfo();
		}
	}

	function close(){
		unset($this -> zipObj);
	}

	function createZipFileName($str = '', $onlyUse = false){
		if(empty($str)){
			$this -> zipName	=	date("Ymdhis"). ".zip";
		}elseif($onlyUse){
			$this -> zipName	=	$str. ".zip";
		}else{
			$this -> zipName	=	$str . "_" . date("Ymdhis"). ".zip";
		}
	}

	function addFileList($arrFileList = null){
		if(is_array($arrFileList)){
			$this -> zipSaveFiles += $arrFileList;
		}elseif (!empty($arrFileList)){
			array_push($this -> zipSaveFiles, $arrFileList);
		}
	}

	function __checkPath($str = ''){
		if ($str) {
			$this->zipSavePath	=	PBRootPath . $str;
		}else {
			$this->zipSavePath	=	ARCHIVE_ZIP_CREATE_DIR;
		}
		
		if (empty($this->zipSavePath)) {
			$this -> msg 	=	'path is empty.';
		}elseif(! file_exists($this->zipSavePath)){
			if ($GLOBAL[boolisWin]){
				mkdir( $this->zipSavePath);
			}else{
				umask(2);
				mkdir( $this->zipSavePath);
			}
		}
	}

}
?>