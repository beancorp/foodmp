<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty conversion modifier plugin
 *
 * Type:     modifier<br>
 * Name:     conversion<br>
 * Purpose:  conversion " ' \ on string
 *
 * @author ping.hu <20090106>
 * @param string $string
 * @return string
 */
function smarty_modifier_conversion($string, $type='preg')
{
	if (!empty($string)) {
		if($type == 'preg'){
			//$string	= str_replace('"', '&quot;', $string);
			$pattern = '/([\'\"\|\[\]\)\(])/i';
			$replace = "\\\\$1" ;
			return preg_replace($pattern, $replace, $string);
		}elseif ($type == 'url'){
			return urlencode($string);
		}elseif ($type == 'html'){
			return html_entity_decode($string);
		}
	}
}

/* vim: set expandtab: */

?>