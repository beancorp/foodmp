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
 * Name:     isArray<br>
 * Example:  {$var|isArray}
 * Date:     September 15th, 2008
 * @author   Ping.Hu <monte at ohrt dot com>
 * @version  1.0
 * @param object $object
 * @return boolean
 */
function smarty_modifier_isarray($object)
{
    return is_array($object);
}

/* vim: set expandtab: */

?>