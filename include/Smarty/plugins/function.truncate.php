<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate function plugin
 *
 * Type:     function<br>
 * Name:     truncate<br>
 * @author   Ping.hu  <20081219>
 * @param array $params {content:'',length:0, break_words:false, etc:'...' , cleanHTML:true, newline:true, url:'' target:'_blank'}
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_truncate($params, &$smarty){
	$strResult	=	'';
	$etc		=	'...';
	$length 	=	50;
	$newline	=	true;
	$cleanHTML	=	true;
	$url		=	'';
	$break_words=	false;

	foreach ($params as $_key=>$_value) {
		$$_key	=	$_value;
	}

	$intTempLen	=	strlen($content);

	if ($intTempLen) {
		if ($cleanHTML) {
			$pattern = array("/(<.*?)([^>]*?)(>)/is");
			$replace = array("");
			$strTemp = preg_replace($pattern, $replace, str_replace("\n","",str_replace("\r","",$content)));
		}else {
			$strTemp =	$content;
		}
		$intTempLenNew	=	strlen($strTemp);

		if ($length >0 && $intTempLenNew >= $length) {
			empty($url) ? '' : $etc = "<a href='$url' target='$target' ".(empty($params['class']) ? "" : "class='$params[class]'") . (empty($params['style'])?"":" style='$params[style]'").(empty($params['id'])?'':" id='$params[id]'").">$etc</a>" ;
			$strResult  = substr($strTemp, 0, $length ) . ($newline ? " $etc<br>" : $etc);
		}else {
			$strResult  = $strTemp;
		}
	}else {
		$strResult  = $strTemp;
	}


	return $strResult;
}
/* vim: set expandtab: */

?>
