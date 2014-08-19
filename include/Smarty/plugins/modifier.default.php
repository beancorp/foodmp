<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty default modifier plugin
 *
 * Type:     modifier<br>
 * Name:     default<br>
 * Purpose:  designate default value for empty variables
 * @link http://smarty.php.net/manual/en/language.modifier.default.php
 *          default (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_default($string, $default = '', $includeZone=false, $maxVal=0, $addMax="+")
{
	$strResult	=	'';
	if (!isset($string) || $string === ''){
		$strResult	= $default;
	}elseif ($includeZone && ($string == '0' || $string == 0 )){
		$strResult	=  $default;
	}elseif ($maxVal > 0 && is_numeric($string) && $string >= $maxVal ){
		$strResult	=  $maxVal . $addMax;
	}else{
		$strResult	= $string;
	}
	
	return $strResult;
}

/* vim: set expandtab: */

?>
