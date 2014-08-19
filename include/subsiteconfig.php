<?php
	$siteconfig_sub = array(
	'socexchange.com.au'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'socexchange.com.au','site'=>'b','cmstable'=>'cms'),
	'www.socexchange.com.au'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'socexchange.com.au','site'=>'b','cmstable'=>'cms'),
	'socexchange.com.au'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'socexchange.com.au','site'=>'b','cmstable'=>'cms'),
	'www.socexchange.com.au'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'socexchange.com.au','site'=>'b','cmstable'=>'cms'),
								
	'socexchange.com'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'socexchange.com','site'=>'a','cmstable'=>'cms'),
	'www.socexchange.com'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'socexchange.com','site'=>'a','cmstable'=>'cms'),
	'socexchange.com'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'socexchange.com','site'=>'a','cmstable'=>'cms'),
	'www.socexchange.com'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'socexchange.com','site'=>'a','cmstable'=>'cms'),
	/**some test site**/
	'mus.infinitycn.com'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'mus.infinitycn.com','site'=>'s','cmstable'=>'cms'),
	'mus1.infinitycn.com'=>array('tpl'=>'index_site1.tpl','regards'=>'SOC Exchange mus1 test site',
								'session'=>'mus1.infinitycn.com','site'=>'u','cmstable'=>'cms'),
	'mus2.infinitycn.com'=>array('tpl'=>'index_site2.tpl','regards'=>'SOC Exchange mus2 test site',
								'session'=>'mus2.infinitycn.com','site'=>'v','cmstable'=>'cms'),
								
	'mas.infinitycn.com'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
								'session'=>'mas.infinitycn.com','site'=>'t','cmstable'=>'cms'),
	'mas1.infinitycn.com'=>array('tpl'=>'index_site1.tpl','regards'=>'SOC Exchange',
								'session'=>'mas.infinitycn.com','site'=>'t','cmstable'=>'cms'),
	'127.0.0.1:800'=>array('tpl'=>'index.tpl','regards'=>'SOC Exchange',
						   'session'=>'127.0.0.1:800','site'=>'z','cmstable'=>'cms'),
	'localhost:800'=>array('tpl'=>'index_site1.tpl','regards'=>'SOC Exchange',
						   'session'=>'localhost:800','site'=>'x','cmstable'=>'cms_loc')
	);
		
	$email_regards = isset($siteconfig_sub[$_SERVER['HTTP_HOST']]['regards'])?$siteconfig_sub[$_SERVER['HTTP_HOST']]['regards']:'SOC exchange Australia';
	$template_tpl = isset($siteconfig_sub[$_SERVER['HTTP_HOST']]['tpl'])?$siteconfig_sub[$_SERVER['HTTP_HOST']]['tpl']:"index.tpl";
	$session_domain = isset($siteconfig_sub[$_SERVER['HTTP_HOST']]['session'])?$siteconfig_sub[$_SERVER['HTTP_HOST']]['session']:"socexchange.com";
	$from_site_type = isset($siteconfig_sub[$_SERVER['HTTP_HOST']]['site'])?$siteconfig_sub[$_SERVER['HTTP_HOST']]['site']:"a";
	$cmstable = isset($siteconfig_sub[$_SERVER['HTTP_HOST']]['cmstable'])?$siteconfig_sub[$_SERVER['HTTP_HOST']]['cmstable']:"cms";
	$securt_url = "https://socexchange.com/";
	$normal_url = "http://{$_SERVER['HTTP_HOST']}/";
	switch ($_SERVER['HTTP_HOST']){
		case 'socexchange.com.au':
		case 'www.socexchange.com.au':
			$securt_url = "https://socexchange.com.au/";
			break;
		case 'socexchange.com':
		case 'www.socexchange.com.au':
			$securt_url = "https://socexchange.com/";
			break;
		default:
			$securt_url = "https://{$_SERVER['HTTP_HOST']}/";
			if(PAYPAL_DEBUG):$securt_url = "http://{$_SERVER['HTTP_HOST']}/";endif;
	}
?>