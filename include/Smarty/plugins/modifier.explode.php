<?php

/**
 * explode string
 *
 * @author ping.hu
 * @param string $string
 * @param string $space   {','}
 * @package boolean $serialize
 * @return string
 */
function smarty_modifier_explode($string, $space=',', $serialize=false){

	if(empty($string)) {
		//$smarty->trigger_error("math: missing equation parameter");
		return;
	}elseif ($serialize) {
		return unserialize($string);
	}else {
		$space	=	empty($space) ? ',' : $space;
		return explode($space, $string);
	}

}

?>