<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once ('../include/smartyconfig.php');
include_once ('maininc.php');
include_once ('functions.php');
include_once ('class/pagerclass.php');
include_once ('class.emailClass.php');
include_once ('class.uploadImages.php');
include_once ("../indexPart.php");
include_once ('class.FoodWine.php');
include_once ('class/ajax.php');

$indexTemplate = "../index.tpl";
$foodwineObj = new FoodWine();

switch ($_REQUEST['cp']) {
    case 'search':
		$smarty->assign('search_page', 1);
        $lang = $GLOBALS['_LANG'];
        $searchForm = array(
            'keyword' => trim($_REQUEST['keyword']),
            'bcategory' => intval($_REQUEST['bcategory']),
            'cuisine' => intval($_REQUEST['cuisine']),
            'bcategory_name' => intval($_REQUEST['bcategory']) ? $lang['seller']['attribute'][5]['subattrib'][intval($_REQUEST['bcategory'])] : '',
            'state_name' => $_REQUEST['search_state_name'] ? $_REQUEST['search_state_name'] : $_REQUEST['state_name'],
            'suburb' => $_REQUEST['suburb'],
            'bu_postcode' => $_REQUEST['bu_postcode'],
            'distance' => $_REQUEST['distance'],
            'sort' => $_REQUEST['sort']
        );

        $search['search_states'] = getStateArray($searchForm['state_name']);
        $suburbArray = explode('.', $searchForm['suburb']);
        $searchForm['suburb'] = $suburbArray[0];
        $searchForm['postcode'] = $suburbArray[1];
        $searchForm['suburb_id'] = $suburbArray[2];
        $searchForm['bcategory'] = isset($_REQUEST['bcategory']) ? $_REQUEST['bcategory'] : '-2';
        $searchForm['bsubcategory'] = isset($_REQUEST['bsubcategory']) ? $_REQUEST['bsubcategory'] : $_GET['bsubcategory'];

        // set page title and keywords
        $smarty->assign('pageTitle', $_LANG['seo']['searchVehicle']['title']);
        $smarty->assign('keywords', $_LANG['seo']['searchVehicle']['keywords']);

        $smarty->assign('is_content', 1);
        $smarty->assign('searchForm', $searchForm);

        if ($_REQUEST['e4c387b8cf9f'] == 1) {
            $bcategory = $_REQUEST['bcategory'];
            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : $_GET['page'];
            if (!$page) {
                $page = 1;
            }

            $req = $foodwineObj->searchStoreList(array_merge($searchForm), $page);

            if ($bcategory) {
                $req['category_name'] = $lang['seller']['attribute'][5]['subattrib'][$bcategory];
                $tpl_search = 'search_category.tpl';
            } else {
                $tpl_search = 'search.tpl';
            }
            $_SESSION['search_form'] = $searchForm;

            $smarty->assign('lang', $lang);
            $smarty->assign('itemTitle', $socObj->getTextItemTitle('Search Results', 4, '3c3082'));
            $smarty->assign('state_name', $searchForm['state_name']);
            $smarty->assign('foodwine_fullname', getStateDescByName($searchForm['state_name'], 1));
            $smarty->assign('req', $req);
            $smarty->assign('content', $smarty->fetch($tpl_search));
            $smarty->assign('sidebar', 0);
        } else {
            $tpl_search = $_REQUEST['tpl_search'];
            $state_name = $searchForm['state_name'];
            $smarty->assign('state_name', $state_name);
            $smarty->assign('distance', 200);

            $fullName = getStateDescByName($searchForm['state_name'], 1);
            $smarty->assign('state_fullname', empty($fullName) ? DEFAULT_COUNCIL : $fullName);
            $req = $socObj->statePage($searchForm['state_name'], 'FoodWine');

            /**
             * Added by Kevin, 2011-08-17
             */
            include_once(SOC_INCLUDE_PATH . '/class.banner.php');
            $banner = new Banner();
            $banners = $banner->StatePageRandomBanner(!$state_name ? DEFAULT_STATE : $state_name, 'FoodWine');
            $smarty->assign('showRandomBanner', false);
            //$smarty->assign('statePageBanners', $banners);
            $bannerIDArray = array();
            foreach ($banners as $bn) {
                $bannerIDArray[] = $bn['banner_id'];
            }
            $banner->addStatePageRandomBannerViews($bannerIDArray);

            $browse = $socObj->GetClinetBrowser();
            $isSafari = $browse[0] == 6 ? true : false;
            $smarty->assign('isSafari', $isSafari);
            $state_name = $_REQUEST['state_name'];

            //banner
            $smarty->assign('foodwinehome', 1);
            $smarty->assign('show_left_cms', 1);
            $smarty->assign('hide_race_banner', 1);
            $smarty->assign('state_name', $state_name);
            $smarty->assign('sidebarContent', $smarty->fetch('right_banner.tpl'));
            $req = array_merge($req, $socObj->displayPageFromCMS(102));
            $smarty->assign('pageTitle', 'Local Food & Wine Retailers: Find Specials on Fruit, Vegetables & Wine Near You: FoodMarketplace');
            $smarty->assign('keywords', $keywordsList);
            $smarty->assign('description', 'FoodMarketplace Food & Wine where you can find local food and Wine retailers near you.');
            $is_set_desc = true;

            $smarty->assign('req', $req);
            $smarty->assign('content', $smarty->fetch($tpl_search ? $tpl_search . '.tpl' : 'home.tpl'));
            //$smarty->assign('sidebar_bg', 0);
            $smarty->assign('sidebar', 1);
        }

        break;

    case "samplesite":
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - Sample Websites');
        $smarty->assign('sampsitelist', getSamplesitebyNames($samplesiteid));
        $content = $smarty->fetch('../sample_site.tpl');
        $smarty->assign('content', $content);
        $smarty->assign('sidebar', 0);
        $smarty->assign('is_content', 1);
        break;

    case 'season':
        $season_arr = array(
            '1' => array(9, 10, 11),
            '2' => array(12, 1, 2),
            '3' => array(3, 4, 5),
            '4' => array(6, 7, 8)
        );

        $searchForm = array(
            'all' => $_REQUEST['all'],
            'page' => $_REQUEST['page'] ? intval($_REQUEST['page']) : 1,
            'season' => intval($_REQUEST['season']),
            'typeid' => intval($_REQUEST['typeid'])
        );

        $searchForm['typeid'] = $searchForm['typeid'] ? $searchForm['typeid'] : 2;
        if (empty($searchForm['season'])) {
            $month = intval(date('m', time()));
            foreach ($season_arr as $key => $val) {
                if (in_array($month, $val)) {
                    $searchForm['season'] = $key;
                    break;
                }
            }
        }

        // set page title and keywords
        $smarty->assign('pageTitle', $_LANG['seo']['searchVehicle']['title']);
        $smarty->assign('keywords', $_LANG['seo']['searchVehicle']['keywords']);

        $smarty->assign('is_content', 1);
        $smarty->assign('searchForm', $searchForm);

        $state_name = $searchForm['state_name'];
        $smarty->assign('state_name', $state_name);
        $smarty->assign('distance', 200);

        $req['product'] = $foodwineObj->getSeasonProduct($searchForm);
        $req['seasons'] = getSeasonArray(intval($searchForm['season']));
        $req['typeid'] = intval($searchForm['typeid']);

        /**
         * Added by Kevin, 2011-08-17
         */
        include_once(SOC_INCLUDE_PATH . '/class.banner.php');
        $banner = new Banner();
        $banners = $banner->StatePageRandomBanner(!$state_name ? DEFAULT_STATE : $state_name, 'FoodWine');
        $smarty->assign('showRandomBanner', false);
        $bannerIDArray = array();
        foreach ($banners as $bn) {
            $bannerIDArray[] = $bn['banner_id'];
        }
        $banner->addStatePageRandomBannerViews($bannerIDArray);

        $browse = $socObj->GetClinetBrowser();
        $isSafari = $browse[0] == 6 ? true : false;
        $smarty->assign('isSafari', $isSafari);
        $state_name = $_REQUEST['state_name'];

        //banner
        $smarty->assign('foodwinehome', 1);
        $smarty->assign('show_left_cms', 1);
        $smarty->assign('hide_race_banner', 1);
        $smarty->assign('state_name', $state_name);
        $smarty->assign('council_name', $_REQUEST['council']);
        $smarty->assign('sidebarContent', $smarty->fetch('right_banner.tpl'));
		
        //$req = array_merge($req, $socObj->displayPageFromCMS(102));
        $smarty->assign('pageTitle', 'Fruit & Vegetables:What\'s in Season:SOC Exchange');
        $smarty->assign('description', 'Find out what fruit and vegetables are available, and fresh, by the season');
        $smarty->assign('keywords', 'Fruit, Vegetables, SOC Exchange');
        $is_set_desc = TRUE;
        $custom_seo_keywords = TRUE;

        $smarty->assign('req', $req);
        $smarty->assign('content', $smarty->fetch('season.tpl'));
        $smarty->assign('sidebar', 1);
        break;

    case 'home':
    default:
	
        $lang = $GLOBALS['_LANG'];
        if (!$_REQUEST['state_name'] && !$_REQUEST['council']) {
            if (!$_SESSION['state_name'] && !$_SESSION['council']) {
                $_REQUEST['state_name'] = DEFAULT_STATE;
				$_REQUEST['council'] = DEFAULT_COUNCIL;
				
            } else {
                $_REQUEST['state_name'] = $_SESSION['state_name'];
                $_REQUEST['council'] = $_SESSION['council'];
            }
        }
        $_SESSION['state_name'] = $_REQUEST['state_name'];
        $_SESSION['council'] = $_REQUEST['council'];
        
        $searchForm = array(
            'keyword' => trim($_REQUEST['keyword']),
            'bcategory' => intval($_REQUEST['bcategory']),
            'cuisine' => intval($_REQUEST['cuisine']),
            'bcategory_name' => intval($_REQUEST['bcategory']) ? $lang['seller']['attribute'][5]['subattrib'][intval($_REQUEST['bcategory'])] : '',
            'state_name' => $_REQUEST['search_state_name'] ? $_REQUEST['search_state_name'] : $_REQUEST['state_name'],
            'council' => $_REQUEST['council'],
            'suburb' => $_REQUEST['suburb'],
            'distance' => $_REQUEST['distance'],
            'sort' => $_REQUEST['sort']
        );

        $search['search_states'] = getStateArray($searchForm['state_name']);
        $suburbArray = explode('.', $searchForm['suburb']);
        $searchForm['suburb'] = $suburbArray[0];
        $searchForm['postcode'] = $suburbArray[1];
        $searchForm['suburb_id'] = $suburbArray[2];
        $searchForm['bcategory'] = isset($_REQUEST['bcategory']) ? $_REQUEST['bcategory'] : '-2';
        $searchForm['bsubcategory'] = isset($_REQUEST['bsubcategory']) ? $_REQUEST['bsubcategory'] : $_GET['bsubcategory'];

        // set page title and keywords
        $smarty->assign('pageTitle', $_LANG['seo']['searchVehicle']['title']);
        $smarty->assign('keywords', $_LANG['seo']['searchVehicle']['keywords']);

        $smarty->assign('is_content', 1);
        $smarty->assign('searchForm', $searchForm);
		$smarty->assign('show_consumer_join_banner', true);
        if ($_REQUEST['e4c387b8cf9f'] == 1) {
            $bcategory = $_REQUEST['bcategory'];
            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : $_GET['page'];
            if (!$page) {
                $page = 1;
            }

            $req = $foodwineObj->searchStoreList(array_merge($searchForm), $page);

            if ($bcategory) {
                $req['category_name'] = $lang['seller']['attribute'][5]['subattrib'][$bcategory];
                $tpl_search = 'search_category.tpl';
            } else {
                $tpl_search = 'search.tpl';
            }

            $smarty->assign('lang', $lang);
            $smarty->assign('itemTitle', $socObj->getTextItemTitle('Search Results', 4, '3c3082'));
            $smarty->assign('state_name', $searchForm['state_name']);
            $smarty->assign('council_name', $_REQUEST['council']);
            $smarty->assign('foodwine_fullname', getStateDescByName($searchForm['state_name'], 1));
            $smarty->assign('req', $req);
            $smarty->assign('content', $smarty->fetch($tpl_search));
            $smarty->assign('sidebar', 0);
        } else {
            $tpl_search = $_REQUEST['tpl_search'];
            $state_name = $searchForm['state_name'];
            $smarty->assign('state_name', $state_name);
            $smarty->assign('distance', 200);

            $fullName = getStateDescByName($searchForm['state_name'], 1);
            $smarty->assign('state_fullname', empty($fullName) ? DEFAULT_COUNCIL : $fullName);
            $req = $socObj->statePage($searchForm['state_name'], 'FoodWine');

            /**
             * Added by Kevin, 2011-08-17
             */
            include_once(SOC_INCLUDE_PATH . '/class.banner.php');
            $banner = new Banner();
            $banners = $banner->StatePageRandomBanner(!$state_name ? DEFAULT_STATE : $state_name, 'FoodWine');
            $smarty->assign('showRandomBanner', false);
            //$smarty->assign('statePageBanners', $banners);
            $bannerIDArray = array();
            foreach ($banners as $bn) {
                $bannerIDArray[] = $bn['banner_id'];
            }
            $banner->addStatePageRandomBannerViews($bannerIDArray);

            $browse = $socObj->GetClinetBrowser();
            $isSafari = $browse[0] == 6 ? true : false;
            $smarty->assign('isSafari', $isSafari);
            $state_name = $_REQUEST['state_name'];

            //banner
            $smarty->assign('foodwinehome', 1);
            $smarty->assign('show_join_banner', 1);
			$smarty->assign('show_alan_jones_button', SHOW_ALAN_JONES_BUTTON);
            if (SHOW_FAN_PROMO_SIDEBAR===false) $smarty->assign('dontshowpromo', 1);
            $smarty->assign('show_left_cms', 1);
            $smarty->assign('hide_race_banner', 1);
            $smarty->assign('state_name', $state_name);
            $smarty->assign('council_name', $_REQUEST['council']);
            $smarty->assign('sidebarContent', $smarty->fetch('right_banner.tpl'));
            $req = array_merge($req, $socObj->displayPageFromCMS(102));
            $smarty->assign('pageTitle', 'Local Food & Wine Retailers: Find Specials on Fruit, Vegetables & Wine Near You: SOC Exchange Australia');
            $smarty->assign('keywords', $keywordsList);
            $smarty->assign('description', 'Food Marketplace Food & Wine where you can find local food and Wine retailers near you.');
            $is_set_desc = true;

            $smarty->assign('req', $req);
            
            $smarty->assign('content', $smarty->fetch($tpl_search ? $tpl_search . '.tpl' : 'home.tpl'));
            //$smarty->assign('sidebar_bg', 0);
            $smarty->assign('sidebar', 1);
        }
        break;
}