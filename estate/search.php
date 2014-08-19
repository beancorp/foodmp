<?php
/**
 * estate search control file
 * Tue Dec 09 03:49:01 GMT 2008 03:49:01
 * @author  : Jessee <support@infinitytesting.com.au>
 * @version : V0.1
 * ------------------------------------------------------------
 * 
 */

include_once ('../include/smartyconfig.php');
include_once ('maininc.php');
include_once ('functions.php');
include_once ('class/pagerclass.php');
include_once ('class.emailClass.php');
include_once ('class.uploadImages.php');
include_once ("../indexPart.php");
include_once ('class.estate.php');
include_once ('class/ajax.php');

$indexTemplate = "../index.tpl";
$estateObj	=	new estateClass();

switch ($_REQUEST['cp']){
	case 'home':
		$smarty -> loadLangFile('threeSeller');

		$smarty -> assign('pageTitle','The New Way To Buy &amp; Sell Real Estate Online: SOC Exchange Australia');
		$smarty -> assign('keywords',$keywordsList);
		$smarty -> assign('description', 'List your property for sale on SOC Exchange Australia for only $10 a month, or search our extensive range of property throughout Australia.');
		$is_set_desc = true;
		
		$req['random_items'] = $estateObj->getEstateRandomProduct();
		//print_r($req['random_items']);
		$smarty -> assign('req',	$req);
		$content =	$smarty -> fetch('home.tpl');

		$smarty -> assign('req',	$req);
		//$content	.=	$smarty -> fetch('category.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_home',1);
		$smarty->assign('hide_race_banner',1);
		$smarty -> assign('sidebar_bg', '0');
		$smarty->assign('is_content',1);
                        /**
                         * added by YangBall, 2011-04-29
                         */
                         $browse = $socObj->GetClinetBrowser();
                         $isSafari = $browse[0] == 6 ? true : false;
                         $smarty->assign('isSafari', $isSafari);
                        //END-YangBall
        $smarty->assign('sidebarContent', $smarty->fetch('right_banner.tpl'));

		break;

	case 'search':
		// save the search value to an array
		if($_REQUEST['keyword']==""){
		$searchForm = array(
			'state_name' => isset($_REQUEST['state_name']) ? $_REQUEST['state_name'] : $_GET['state_name'],
			'suburb' => isset($_REQUEST['suburb']) ? $_REQUEST['suburb'] : $_GET['suburb'],
			'bu_postcode' => isset($_REQUEST['bu_postcode']) ? $_REQUEST['bu_postcode'] : $_GET['bu_postcode'],
			'category' => isset($_REQUEST['category']) ? $_REQUEST['category'] : $_GET['category'],
			'property' => isset($_REQUEST['property']) ? $_REQUEST['property'] : $_GET['property'],
			'bedroom' => isset($_REQUEST['bedroom']) ? $_REQUEST['bedroom'] : $_GET['bedroom'],
			'bathroom' => isset($_REQUEST['bathroom']) ? $_REQUEST['bathroom'] : $_GET['bathroom'],
			'carspace' => isset($_REQUEST['carspace']) ? $_REQUEST['carspace'] : $_GET['carspace'],
			'agent_name' => isset($_REQUEST['agent_name']) ? $_REQUEST['agent_name'] : $_GET['agent_name'],
			'keyword' => isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : $_GET['keyword'],
			'min_price' => isset($_REQUEST['min_price']) ? $_REQUEST['min_price'] : $_GET['min_price'],
			'max_price' => isset($_REQUEST['max_price']) ? $_REQUEST['max_price'] : $_GET['max_price'],
			'negotiable' => isset($_REQUEST['negotiable']) ? $_REQUEST['negotiable'] : $_GET['negotiable'],
			'distance' => isset($_REQUEST['distance']) ? $_REQUEST['distance'] : $_GET['distance'],
			//'' => isset($_REQUEST['']) ? $_REQUEST[''] : $_GET[''],
		);
		}else{
			$searchForm = array(
			'state_name' => '-1',
			'suburb' => '',
			'category' => '',
			'property' => '',
			'bedroom' => '',
			'bathroom' => '',
			'carspace' => '',
			'agent_name' => '',
			'keyword' => isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : $_GET['keyword'],
			'min_price' => '-1',
			'max_price' => '',
			'negotiable' => '',
			'distance' => '',
			//'' => isset($_REQUEST['']) ? $_REQUEST[''] : $_GET[''],
			);
		}
                
		if ($searchForm['state_name']=="-1"){
			$searchForm['state_name'] = 'NSW';
                        if(isset($_GET['e4c387b8cf9f']) and $_GET['e4c387b8cf9f']==1) {
                            $searchForm['state_name']="-1";
                        }
		}
		$state_name = $searchForm['state_name'];
		if (is_numeric($searchForm['min_price']) and is_numeric($searchForm['max_price'])){
			if ($searchForm['min_price'] != $searchForm['max_price']){
				if ($searchForm['min_price']>$searchForm['max_price']){
					$tmp_price = $searchForm['min_price'];
					$searchForm['min_price'] = $searchForm['max_price'];
					$searchForm['max_price'] = $tmp_price;
				}
			}
		}else{
			if (!is_numeric($searchForm['min_price'])){
				$searchForm['min_price'] = '';
			}
			if (!is_numeric($searchForm['max_price'])){
				$searchForm['max_price'] = '';
			}
		}
		$suburbArray = explode('.',$searchForm['suburb']);
		$searchForm['suburb'] = $suburbArray[0];
		$searchForm['postcode'] = $suburbArray[1];
		$searchForm['suburb_id'] = $suburbArray[2];
		//var_dump($searchForm);
		
		$smarty -> assign('pageTitle',$_LANG['seo']['searchEstate']['title']);
		$smarty -> assign('keywords',$_LANG['seo']['searchEstate']['keywords']);
		
		$smarty->assign('is_content',1);
		$smarty->assign('searchForm',$searchForm);
		if ($_REQUEST['e4c387b8cf9f']==1){
			$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : $_GET['page'];
			if (!$page){
				$page = 1;
			}
                        $smarty -> assign('state_fullname', getStateDescByName($_REQUEST['search_state_name'],1));
                        $_state_name = $_REQUEST['keyword']=="" ? $_REQUEST['search_state_name'] : '-1';
			$req = $estateObj->searchEstateList(array_merge($searchForm,array('state_name'=>$_state_name)),$page);
			$smarty->assign('state_name', $searchForm['state_name']);

			$smarty->assign('req', $req);
			$content = $smarty->fetch('search.tpl');
			$smarty->assign('content', $content);
			$smarty->assign('sidebar', 0);
		}else{
			$smarty->assign('state_name', $state_name);
			$smarty -> assign('distance', 200);

                        $fullName = getStateDescByName($searchForm['state_name'],1);
			$smarty -> assign('state_fullname', empty($fullName) ? 'New South Wales' : $fullName);
			$req = $socObj->statePage($searchForm['state_name'], 'Estate');

                        /**
                         * added by YangBall, 2011-03-11
                         */
                        include_once(SOC_INCLUDE_PATH . '/class.banner.php');
                        $banner = new Banner();
                        $banners = $banner->StatePageRandomBanner(!$state_name ? 'NSW' : $state_name,'Estate');
                        $smarty->assign('showRandomBanner',true);
                        $smarty->assign('statePageBanners', $banners);
                        $bannerIDArray = array();
                        foreach($banners as $bn) {
                            $bannerIDArray[] = $bn['banner_id'];
                        }
                        $banner->addStatePageRandomBannerViews($bannerIDArray);
                        //END-YangBall

                        /**
                         * added by YangBall, 2011-04-29
                         */
                         $browse = $socObj->GetClinetBrowser();
                         $isSafari = $browse[0] == 6 ? true : false;
                         $smarty->assign('isSafari', $isSafari);
                        //END-YangBall
			$smarty->assign('req', $req);
			$smarty->assign('content', $smarty->fetch('statepage.tpl'));
			$smarty->assign('sidebar', 1);
		}

		break;
	case 'productrss':
		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!$StoreID){
			header('Location:soc.php?cp=home');
			exit;
		}
		header("Content-Type: text/xml; charset=utf-8");
		$arrProductRss = $estateObj->getEstateRssData($StoreID, 100);
		$smarty -> assign('req', 	$arrProductRss);
		$smarty -> display('../rss_blogandproduct.tpl');
		exit;
		break;
	case "samplesite":
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Sample Websites');
		$smarty -> assign('sampsitelist',getSamplesitebyNames($samplesiteid));
		$content	=	$smarty -> fetch('../sample_site.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty->assign('is_content',1);
		break;
	default:
		header('Location: '.RDURL);
		exit();
		break;
}

$partBottom 	=	true;
$search_type	=	'estate';
include('../indexPart.php');
exit;
?>