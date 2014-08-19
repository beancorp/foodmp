<?php

/**
 * include php's file
 *
 * @author ping.hu <20081230>
 * @param array $params
 * @param Smarty $smarty
 * @return HTML
 */
function smarty_function_inc_php($params, &$smarty)
{
	extract($params);
	
	$setACTAtInc	=	$act;
	$setCPAtInc		=	$cp;
	
	include ($file);
}

?>