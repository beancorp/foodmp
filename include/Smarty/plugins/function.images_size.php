<?php
/**create by roy.luo 2010-03-22**/
function smarty_function_images_size($params, &$smarty)
{
	$dfwidth =  $params['df_width'];
	$dfheight = $params['df_height'];
	
	$imgwidth = $params['width'];
	$imgheight = $params['height'];
	
	$newwidth = $dfwidth;
	$newheight = $dfheight;
	
	if ($dfwidth/$dfheight > $imgwidth/$imgheight) {
		$newwidth  = round($dfheight * $imgwidth / $imgheight , 0) ;
	}else{
		$newheight	= round($dfwidth / ($imgwidth / $imgheight) , 0) ;
	}

	return " width=\"$newwidth\" height=\"$newheight\" ";
}
?>