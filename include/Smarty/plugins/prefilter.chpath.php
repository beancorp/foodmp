<?php
function smarty_prefilter_chpath($tpl_source,&$smarty)
{
	$pattern = array(
	'/<!--[^>|\n]*?({.+?})[^<|{|\n]*?-->/', // replace smarty context
	'/<!--[^<|>|{|\n]*?-->/',               // replace html context
	);
	$replace = array(
	'\1',
	'',
	);

	return preg_replace($pattern, $replace, $tpl_source);
}
?>