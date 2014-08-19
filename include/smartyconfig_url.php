<?php 
/** string */
define("ProjectDir","");

/** String	*/
define("PB_CHARSET","UTF-8");
//define("PB_CHARSET","iso-8859-1");

$prefix = '';
$script_name = 'soc.php';
/** load setting */
$_PBS["skin"] = "red";
$_PBS["lang"] = LANGCODE;

//ob_start("ob_gzhandler");

/** @var string webroot path */
define('ROOT_PATH', str_replace('include/smartyconfig_url.php', '', str_replace('\\', '/', __FILE__)));

//include path
@set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
define("SMARTY_DIR", str_replace("//","/",ROOT_PATH . "/include/Smarty/"));

/* cache dir setting */
$cachePath =str_replace(ProjectDir,"",str_replace(ProjectDir."/","",$prefix));
if (!file_exists( ROOT_PATH . 'caches' . $cachePath)) {
	mkdir( ROOT_PATH . 'caches' . $cachePath,0755);
}
if (!file_exists( ROOT_PATH . 'caches/compiled' . $cachePath)) {
	mkdir( ROOT_PATH . 'caches/compiled' . $cachePath,0755);
}

/* language dir setting */
require_once(ROOT_PATH . 'languages/' . $_PBS['lang'] . '/common.php');
define(LANGPATH, ROOT_PATH . "languages/".$_PBS["lang"]);
$languagePath = LANGPATH .$cachePath.'/'.$script_name;
if (file_exists($languagePath)) {
	require_once($languagePath);
}


/* Link path */
define("TMP_DIR", '/'.ProjectDir.'/skin/'.$_PBS['skin'].($cachePath != "" && $cachePath != "/" ? $cachePath.'/' : ''));

/* Smarty Setting */
require_once('class/template.php');
$smarty = new Template();

$skindir = SITE_ROOT.DIRECTORY_SEPARATOR.'skin'.DIRECTORY_SEPARATOR.$_PBS['skin'].DIRECTORY_SEPARATOR;
$smarty -> assign('skindir',$skindir);

//$smarty -> cache_lifetime	= 3600;
$smarty -> cache_lifetime	= 10;
$smarty -> template_dir		= ROOT_PATH . 'skin/' . $_PBS['skin'] .'/'.$cachePath ;
$smarty -> cache_dir		= ROOT_PATH . 'caches' . $cachePath;
$smarty -> compile_dir		= ROOT_PATH . 'caches/compiled' . $cachePath;

$smarty -> compile_check	= true;
$smarty -> caching			= false;

$smarty	-> assign('lang', $_LANG);
$smarty -> assign('current_country', $countryID);
//$smarty -> debugging		= true;

$smarty -> assign('PBDateFormat',DATAFORMAT_DB);

include_once('class/common.php');
// Modify by Haydn.H By 20120306 ========= Begin =========
// add facebook api configrable
include_once ('fbconfig.php');
// Modify by Haydn.H By 20120306 ========= End =========

header('Content-Type:text/html;charset=' . PB_CHARSET);
?>