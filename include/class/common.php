<?php
/**
 * This is common class, it can extends by other class.
 * 
 * Fri Feb 15 08:54:31 CST 2008 08:54:31
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.5
 * ------------------------------------------------------------
 * add get values from Forms at xajax.			20081104
 * 
 * common.php
 */

class common
{

	/**
	 * have var caches
	 *
	 * @var boolean
	 */
	var $_notVar = false;


	/**
	 * get form vars of inputbox
	 *
	 * @return array
	 */
	function getFormInputVar()
	{
		$arrResult	= $_SESSION["pageParam"];
		if (is_array($arrResult)) {
			foreach ($arrResult as $key => $var)
			{
				$arrResult[$key]	=	$this->__StrReplace($var,false);
			}
		}

		return $arrResult;
	}


	/**
	 * set form vars of inputbox
	 * 
	 * @param  array $params
	 * @return array
	 */
	function setFormInuptVar($params = null)
	{
		unset($_SESSION["pageParam"]);
		$params	= is_array($params ) ? $params : $_POST;
		if (count($params) >0 ) {
			$_SESSION['pageParam'] = $params;
			$this -> _notVar		=	true;
			foreach ($params as $key => $value)
			{
				$arrTemp["$key"]	= 	$this -> __StrReplace($value);
			}
		}else {
			$arrTemp	=	array();
		}

		return $arrTemp;
	}

	/**
	 * add var to pageParam of session
	 *
	 * @param string $msg
	 */
	function addOperateMessage($msg)
	{
		$_SESSION["pageParam"]["msg"] .= urlencode($msg);
	}

	/**
	 * destroy var to pageParam of session
	 *
	 */
	function destroyFormInputVar()
	{
		$this -> _notVar	=	false;
		unset($_SESSION["pageParam"]);
	}

	/**
	 * input text replace sql string
	 *
	 * @param string $strVar1
	 * @return string
	 */
	function __StrReplace($strVar1,$toSql=true){
		if ( $strVar1 != '' )
		{
			if ($toSql) {
				$strVar1 = str_replace("\'","'",$strVar1) ;
				$strVar1 = str_replace("'","''",$strVar1) ;
			}
			else
			{
				if (@get_magic_quotes_gpc()){
					$strVar1 = str_replace("\'","'",$strVar1) ;
				}
				//$strVar1 = str_replace("'","&rsquo;",$strVar1) ;
				//$strVar1 = str_replace('"',"\"",$strVar1) ;
			}
		}
		return $strVar1;
	}

	/**
	 * replace context for var of lanaguage
	 *
	 * @param string $str
	 * @param string $strReplace
	 * @return string
	 */
	function replaceLangVar($str='',$strReplace='')
	{
		$strResult	=	'';
		if (!empty($str) && is_array($strReplace)){

			$arrTemp = split('%s%',$str);
			$i = 0;
			foreach ($arrTemp as $temp){
				$strResult .= $temp . $strReplace[$i];
				$i++;
			}

		}elseif (!empty($str)) {
			$strResult	= str_replace('%s%',$strReplace,$str);
		}

		return $strResult;
	}

	/**
	 * snap String
	 * 
	 * @author ping.hu <hhping.139.com> 20080117 
	 * @param  string   $str
	 * @param  int      $numberLine
	 * @param  int      $lenOfLine
	 * @return string
	 */
	function snapString($str, $numberLine=0, $lenOfLine = 25)
	{
		$strResult = '';
		$len = 0;
		$totalLen = 0;
		$intLine = $numberLine;

		if(!empty($str))
		{
			$totalLen = strlen($str);

			if ($totalLen > $lenOfline)
			{
				while(($len < $totalLen) && ( $numberLine==0 ? true : $intLine>0 ) )
				{
					$strResult .= '<br>'.substr($str, $len, $lenOfLine);
					$len += $lenOfLine;
					$intLine --;
				}
				$strResult = substr($strResult,4);
			}else{
				$strResult = $str;
			}
		}
		return $strResult;
	}


	/**
	 * change array value , use unserialize array with language
	 *
	 * @param array $arrTemp
	 * @return array
	 */
	function changeArrayValue($arrTemp)
	{
		$arrResult = array();

		if (is_array($arrTemp)) {
			unset($arrTemp1);
			$k=0;
			$len = sizeof($arrTemp);
			foreach ($arrTemp as $temp){
				$arrTemp1[$temp][0]	=	true;
				$k++;
				if ($k < $len) {
					$arrTemp1[$temp][1]	=	true;
				}
			}
			$arrResult = $arrTemp1;
		}
		return $arrResult;
	}


	function imageDisplayWH($imageURL,$defType=1,$IW=397,$IH=282)
	{
		$arrResult	=	null;

		if (empty($imageURL) || !file_exists(realpath("./")."/".$imageURL) ){
			if ($defType == 1) {
				$strTemp	=	"images/soc_logo_final.jpg";
			}elseif ($defType == 2){
				$strTemp	=	"images/243x212.jpg";
			}elseif ($defType == 3){
				$strTemp	=	"images/497x195.jpg";
			}elseif ($defType == 4){
				$strTemp	=	"images/79x79.jpg";
			}else{
				$strTemp	=	"images/50x50.jpg";
			}
		}else{
			$strTemp	=	$imageURL;
		}
		//change images size
		if (!empty($strTemp)) {

			if ($IW > 0 && $IH >0) {
				$arrSize = getimagesize(realpath("./")."/".$strTemp);
				$actual_width = $arrSize[0];
				$actual_height = $arrSize[1];
				if ($arrSize[0]>0 and $arrSize[1]>0){
					if ($IW/$IH > $arrSize[0]/$arrSize[1]) {
						//if ($arrSize[1] != $IH) {
						$height	= $IH ;
						$width  = round($height * $arrSize[0] / $arrSize[1],0) ;
						//}
						//$intWidth = $width;
						/*
						if ($width != $IW && $intWidth) {
						$width	=	$IW;
						$height	=	$width / ( $intWidth / $height) ;
						}*/
					}else{
						//if ($arrSize[0] != $IW) {
						$width	=	$IW;
						$height	=	round($width / ($arrSize[0] / $arrSize[1]),0) ;
						//}
						//$intHeight = $height;
						/*
						if ($height != $IH && $intHeight) {
						$height	= $IH ;
						$width  = $height * $width / $intHeight ;
						}*/
					}
				}else{
					$width = $IW;
					$height = $IH;
				}
			}
			$arrResult	=	array(
			'name'	=>	$strTemp,
			'width'	=>	$width,
			'height'=>	$height,
			'actual_width' => $actual_width,
			'actual_height' => $actual_height
			);
			if (empty($width)){
				$arrResult['width_attribute'] = '';
			}else{
				$arrResult['width_attribute'] = 'width="'.$width.'"';
			}
			if (empty($height)){
				$arrResult['height_attribute'] = '';
			}else{
				$arrResult['height_attribute'] = 'height="'.$height.'"';
			}
		}
//		print_r($arrResult);echo "<br><br>";
		return $arrResult;
	}

	/**
	 * create FCKEditor string of html
	 *
	 * @param stirng $objName
	 * @param string $objValue
	 * @param string $toolBarSet   adminDefault,Default,SOCBasic,Basic, SOCAlerts
	 * @param array $size
	 * @return string
	 */
	function initEditor($objName='FCKEditor1', $objValue='', $toolBarSet='SOCBasic',$size=array('100%',200)) {
		// Automatically calculates the editor base path based on the _samples directory.
		// This is usefull only for these samples. A real application should use something like this:
		// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
		include_once('fckeditor/fckeditor.php');
		$sBasePath = '/include/fckeditor/' ;

		$oFCKeditor = new FCKeditor($objName);
		$oFCKeditor->BasePath = $sBasePath ;
		$oFCKeditor->ToolbarSet = $toolBarSet;
		$oFCKeditor->Value = $objValue;
		$oFCKeditor->Width = $size[0];
		$oFCKeditor->Height = $size[1];
    
    if($toolBarSet == "SOCAlerts") {
      $oFCKeditor->Config['BodyClass'] = "socalters";
    }

		$myEditor = stripcslashes($oFCKeditor->CreateHtml());
		unset($oFCKeditor);
		return $myEditor;
	}

	/**
	 * date option
	 *
	 * @param string $interval
	 * @param int $number
	 * @param int $date
	 * @return int
	 */
	function dateAdd ($interval, $number, $date) {
		$date_time_array = getdate($date);
		@$hours = $date_time_array["hours"];
		@$minutes = $date_time_array["minutes"];
		@$seconds = $date_time_array["seconds"];
		$month = $date_time_array["mon"];
		$day = $date_time_array["mday"];
		$year = $date_time_array["year"];
		switch ($interval) {
			case "y":
			case "yyyy": $year +=$number; break;
			case "q": $month +=($number*3); break;
			case "m": $month +=$number; break;
			case "d": $day+=$number; break;
			case "w": $day+=($number*7); break;
			case "h": $hours+=$number; break;
			case "n": $minutes+=$number; break;
			case "s": $seconds+=$number; break;
		}
		$timestamp = mktime($hours ,$minutes, $seconds,$month ,$day, $year);

		return $timestamp;
	}

	/**
	 * get clinet browser
	 *
	 * @return array
	 */
	function   GetClinetBrowser(){
		$Agent =   $_SERVER["HTTP_USER_AGENT"];
		$browser[0]   =   60;
		$browser[1]   =   'unknown';
                
		if(strpos($Agent,   "Mozilla")) {   $browser[0]   =   1; $browser[1]   =   'unknown';}
		if(strpos($Agent,   "Mozilla/4")) {   $browser[0]   =   1; $browser[1]   =   '4.0';}
		if(strpos($Agent,   "Mozilla/5")) {   $browser[0]   =   1; $browser[1]   =   '5.0';}
		if(strpos($Agent,   "Firebird")) {   $browser[0]   =   1; $browser[1]   =   'Firebird';}
		if(strpos($Agent,   "Netscape")) {   $browser[0]   =   2; $browser[1]   =   'unknown';}
		if(strpos($Agent,   "Netscape6/")) {   $browser[0]   =   2; $browser[1]   =   '6.0';}
		if(strpos($Agent,   "Netscape/7.1")) {   $browser[0]   =   2; $browser[1]   =   '7.1';}
		if(strpos($Agent,   "Opera")) {   $browser[0]   =   3; $browser[1]   =   'unknown';}
		if(strpos($Agent,   "Firefox")) {   $browser[0]   =   4; $browser[1]   =   'unknown';}
		//Internet   Explorer   5
		if(strpos($Agent,   "MSIE")) {   $browser[0]   =   5; $browser[1]   =   'unknown';}
                if(strpos($Agent,   "MSIE 8.0")) {   $browser[0]   =   5; $browser[1]   =   '8.0';}
		if(strpos($Agent,   "MSIE 7.0")) {   $browser[0]   =   5; $browser[1]   =   '7.0';}
		if(strpos($Agent,   "MSIE 6.0")) {   $browser[0]   =   5; $browser[1]   =   '6.0';}
		if(strpos($Agent,   "MSIE 5.5")) {   $browser[0]   =   5; $browser[1]   =   '5.5';}
		if(strpos($Agent,   "MSIE 5.0")) {   $browser[0]   =   5; $browser[1]   =   '5.0';}
		if(strpos($Agent,   "MSIE 4.0")) {   $browser[0]   =   5; $browser[1]   =   '4.0';}

		if(stripos($Agent,   "safari") and !stripos($Agent,   "chrome")) {   $browser[0]   =   6; $browser[1]   =   'unknown';}

		Return   $browser;
	}

	/**
	 * date format change
	 *
	 * @param date $date
	 * @param string $input
	 * @param string $output
	 * @return date
	 */
	function changeDate($date,$input="mdy",$output="ymd", $splitInput="-",$splitOutput="-"){
		$strResult	=	'';
		if (strtolower($input) == 'mdy'){
			list($month,$day,$year) = explode("$splitInput",$date);
		}elseif (strtolower($input) == 'dmy'){
			list($day,$month,$year) = explode("$splitInput",$date);
		}else {
			list($year,$month,$day) = explode("$splitInput",$date);
		}

		if (strtolower($output) == 'mdy') {
			$strResult = $month.$splitOutput.$day.$splitOutput.$year;
		}elseif(strtolower($output) == 'dmy') {
			$strResult = $day.$splitOutput.$month.$splitOutput.$year;
		}else{
			$strResult = $year.$splitOutput.$month.$splitOutput.$day;
		}

		return $strResult;
	}

	/**
	 * clear HTML code
	 *
	 * @param string $str
	 * @param int $num
	 * @return string
	 */
	function clearHTMLChar($str, $num= 500)	{
		$strResult = "";

		if (strlen($str)>0) {
			$pattern = array(
			"/(<BR>)|(<br>)|(<br \/>)|(<BR \/>)|(<p>)|(<P>)/",
			//"/[0x0080-0x07FF]/",
			"/(<.*?)([^>]*?)(>)/is",
			"/(&nbsp;)|(&trade;)|(&rdquo;)|(&rsquo;)|(&bull;)|(&iexcl;)|(&cent;)|(&pound;)|(&curren;)|(&yen;)|(&brvbar;)|(&sect;)|(&uml;)|(&copy;)|(&ordf;)|(&laquo;)|(&not;)|(&shy;)|(&reg;)|(&macr;)|(&deg;)|(&plusmn;)|(&sup2;)|(&sup3;)|(&acute;)|(&micro;)|(&para;)|(&middot;)|(&cedil;)|(&sup1;)|(&ordm;)|(&raquo;)|(&frac14;)|(&frac12;)|(&frac34;)|(&iquest;)|(&Agrave;)|(&Aacute;)|(&Acirc;)|(&Atilde;)|(&Auml;)|(&Aring;)|(&AElig;)|(&Ccedil;)|(&Egrave;)|(&Eacute;)|(&Ecirc;)|(&Euml;)|(&Igrave;)|(&Iacute;)|(&Icirc;)|(&Iuml;)|(&ETH;)|(&Ntilde;)|(&Ograve;)|(&Oacute;)|(&Ocirc;)|(&Otilde;)|(&Ouml;)|(&times;)|(&Oslash;)|(&Ugrave;)|(&Uacute;)|(&Ucirc;)|(&Uuml;)|(&Yacute;)|(&THORN;)|(&szlig;)|(&agrave;)|(&aacute;)|(&acirc;)|(&atilde;)|(&auml;)|(&aring;)|(&aelig;)|(&ccedil;)|(&egrave;)|(&eacute;)|(&ecirc;)|(&euml;)|(&igrave;)|(&iacute;)|(&icirc;)|(&iuml;)|(&eth;)|(&ntilde;)|(&ograve;)|(&oacute;)|(&ocirc;)|(&otilde;)|(&ouml;)|(&divide;)|(&oslash;)|(&ugrave;)|(&uacute;)|(&ucirc;)|(&uuml;)|(&yacute;)|(&thorn;)|(&yuml;)/",
			"/\& /",
			);
			$replace = array(
			"\n",
			//"",
			"",
			" ",
			"",
			);
			$strTemp = preg_replace($pattern, $replace, $str);
			if ($num >0) {
				$strResult  = substr($strTemp,0,$num) . " ...";
			}else {
				$strResult  = $strTemp;
			}
		}
		return $strResult;
	}
	
	/**
	 * @title	: XmlSafeStr
	 * Mon Feb 02 11:23:09 GMT 2009
	 * @input	: string for xml ouput
	 * @output	: remove the special char from the string
	 * @description	: 
	 * @author	: Jessee <support@infinitytesting.com.au>
	 * @version	: V1.0
	 * 
	 */
	function XmlSafeStr($s){
	  return preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$s);
	}
	function XmlSafeStr2($s){
	  return preg_replace("/[^\\x20-\\x7f]/",'',$s);
	}
}
?>