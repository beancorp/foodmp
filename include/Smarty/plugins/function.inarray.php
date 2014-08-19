<?php

/**
 * check value of array
 *
 * @author ping.hu
 * @param array $params		{arrValue:{'0':''[,...]}, value:''[, return:'true', hasmsg:'false']}
 * @param smarty $smarty
 * @return string
 */
function smarty_function_inarray($params, &$smarty){

	if(is_array($params['arrValue'])){
    	$arrTemp	=	array_count_values($params['arrValue']);
    	if(array_key_exists($params['value'], $arrTemp)){
    		return "$params[return]";
    	}
    }else {
    	$params['hamsg'] ? $smarty->trigger_error("math: missing equation parameter") : '';
        return;
    }
}

?>