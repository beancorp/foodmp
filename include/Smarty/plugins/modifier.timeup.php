<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty count_words modifier plugin
 *
 * Type:     modifier<br>
 * Name:     count_words<br>
 * Purpose:  count the number of words in a text
 * @link http://smarty.php.net/manual/en/language.modifier.count.words.php
 *          count_words (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return integer
 */
function smarty_modifier_timeup($string)
{
	$timeleft = intval($string);
	$days = intval($timeleft/(24*60*60));
	$hours = intval(($timeleft-$days*24*60*60)/(60*60));
	$mins = intval(($timeleft-$days*24*60*60- $hours*60*60)/60);
	$secs = intval($timeleft-$days*24*60*60- $hours*60*60-$mins*60);
	
	if($hours<10){$hours = "0".$hours;}
	if($mins<10){$mins = "0".$mins;}
	if($secs<10){$secs = "0".$secs;}
	if($days>0){
		$str = ($days."d ".$hours."h ".$mins."m");
	}else if($hours>0){
		$str = ($hours."hr ".$mins."m");
	}else{
		$str = ($mins.":".$secs);
	}
    return $str;
}

/* vim: set expandtab: */

?>
