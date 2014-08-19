<?php
/**
 * Fri Feb 15 08:54:31 CST 2008 08:54:31
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.1
 * ------------------------------------------------------------
 * smarty extends class and function
 * ------------------------------------------------------------
 * E:\hhp\web\soc_au\include\class\template.php
 */
 

require_once('Smarty/Smarty.class.php');
class Template extends Smarty {
	
	function Template(){
		Smarty::Smarty();
		$this->load_filter('pre','chpath');
	}
	
	/**
	 * Load language file.
	 *
	 * @param string $file
	 */
	function loadLangFile($file){
		$_LANG 			=	& $GLOBALS['_LANG'];
		$languagePath	=	LANGPATH . '/' .$file . '.php';
		if (file_exists($languagePath)) {
			require_once($languagePath);
		}
		$GLOBALS['smarty']	-> assign('lang', $_LANG);
	}
}
?>