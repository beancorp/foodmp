<?php

/**
 * get value of array
 *
 * @author ping.hu
 * @param array $params		{arrValue:{'0':''[,...]}, value:''}
 * @param smarty $smarty
 * @return string
 */
function smarty_function_valueOfArray($params, &$smarty){

    if(is_array($params['arrValue'])){
    	return $params['arrValue'][$params['value']];
    }else {
    	$smarty->trigger_error("math: missing equation parameter");
        return;
    }
}

?>