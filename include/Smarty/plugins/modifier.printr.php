<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty array modifier plugin
 *
 * Type:     modifier<br>
 * Name:     printr<br>
 * Example:  {$var|printr}
 * Date:     September 15th, 2008
 * @author   Ping.Hu <monte at ohrt dot com>
 * @version  1.0
 * @param object $object
 * @return void
 */
function smarty_modifier_printr($object)
{
	
	if (is_array($object)) {
		
//		echo "<pre>";
//		print_r($object);
//		echo "</pre>";
		
	}
    return serialize($object);
}

?>