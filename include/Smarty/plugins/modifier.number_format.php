<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty number_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     string_format<br>
 * Purpose:  format number via sprintf
 *
 * @author ping.hu <20081230>
 * @param int $number
 * @param int $num_decimal_places
 * @param string $dec_seperator
 * @param string $thousands_seperator
 * @return string
 */
function smarty_modifier_number_format($number, $num_decimal_places=2, $dec_seperator='.', $thousands_seperator=',', $toK=false, $default=0)
{
	if ($number == 0 && is_string($default)) {
		
		$strResult = $default;
		
	}elseif ($number == 0 && is_numeric($default)){
		
		$strResult = number_format($default, $num_decimal_places, $dec_seperator, $thousands_seperator);
	
	}elseif ($number == 0){
		
		$strResult = number_format($number, $num_decimal_places, $dec_seperator, $thousands_seperator);
		
	}else{
	    $strResult = number_format($number, $num_decimal_places, $dec_seperator, $thousands_seperator);
	    
	    if ($toK && $number >= 1000) {
	    	$intStart	=	3;
	    	if ($num_decimal_places>0) {
	    		$intStart += $num_decimal_places + strlen($dec_seperator);
	    	}
	    	$intStart += strlen($thousands_seperator);
	    	
	    	$strResult	=	substr($strResult, 0, strlen($strResult)-$intStart) ."K";
	    }
	}
    
    return $strResult;
}

/* vim: set expandtab: */

?>
