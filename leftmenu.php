<?php
/**
 * Tue Dec 30 16:14:19 GMT 2008 16:14:19
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * left bar by all
 * ------------------------------------------------------------
 * leftmenu.php
 */
if(isset($_REQUEST['opt'])&&$_REQUEST['opt']=='getsubcat'){
	include_once ('include/config.php');
	include_once ('include/maininc.php');
	include_once ('include/functions.php');
	if ($_REQUEST['search_type'] == 'foodwine') {
		$subcat = getFoodWineCategorylist($_REQUEST['cid']);
	} else {
		$subcat = getCategorylist($_REQUEST['cid']);
	}
	$str = "<option value='' selected>All</option>";
	if(is_array($subcat)){
		foreach ($subcat as $pass){
			$str .= "<option value='{$pass['id']}'>{$pass['name']}</option>";
		}
	}
	echo $str;
	exit();
}
!empty($search_type) || $_SESSION['attribute']<1 ? 'store' : $search_type = ($_SESSION['attribute']==1 ? 'estate' : ($_SESSION['attribute']==2 ? 'auto' : ($_SESSION['attribute']==3 ? 'job' :(5 == $_SESSION['attribute'] ? 'foodwine' : 'store'))));


if(isset($_REQUEST['cp'])&&$_REQUEST['cp']=='home'&&isset($_REQUEST['onlinestore'])&&$_REQUEST['onlinestore']==1){
	$search_type='';
}

//print session to smarty
if (!empty($_SESSION)) {
	$userData = $_SESSION;
	$storeName = getStoreURLNameById($userData['StoreID']);
	if (!empty($storeName)) {
		$userData['website'] = clean_url_name($storeName);
	}
	$smarty -> assign('session', $userData);
}

//print state list to smarty
if (is_a($socObj, 'socClass')){
	$states = $socObj->getStatesList($search_type);
}elseif (is_a($socstoreObj, 'socstoreClass')){
	$states = $socstoreObj->getStatesList($search_type);
}
$smarty -> assign('states', $states);
$smarty -> assign('locations', $states);
$smarty -> assign('searchCities', $searchCities);

//onload function
if ($_REQUEST['statename'] && $_REQUEST['collegeid']) {
	$smarty -> assign('stateOnLoad', 	"selectCollegebyName('collegeobj', '". $statename ."&collegeid=".$_REQUEST['collegeid']."');");
}

//print requesturi
$smarty->assign('requesturi', $_SERVER['REQUEST_URI']);
if ($_REQUEST['StoreID']) {	
	$socObj = new socClass();
	$tmp_info = $socObj -> getStoreInfo($_REQUEST['StoreID']);
	switch ($tmp_info['attribute']) {
		case '1':
			$search_type = 'estate';
			break;
	
		case '2':
			$search_type = 'auto';
			break;
	
		case '3':
			$search_type = 'job';
			break;
	
		case '5':
			$search_type = 'foodwine';
			break;
		case '0':
		default:
			$search_type = '';
			break;
	}
}

//print menu bar and search bar.
empty($search_type) ? '' : $smarty -> loadLangFile($search_type. '/index');
$socObj = new socClass();
$cms_left = $socObj -> displayPageFromCMS(118);
$arrList	=	getSearchMenu($search_type);
$smarty -> assign('search_type', $search_type);
$smarty -> assign('cms_left', $cms_left);
$smarty->assign('search', $arrList['search']);
$smarty->assign('searchForm',$arrList['searchForm']);


//------------------------------------------------------------
// function
//------------------------------------------------------------
function getSearchMenu($search_type=''){
	global $dbcon;
	$arrResult = null;
	    /*
	     * food & wine
	     * added by Kevin, 2011-06-24
	     */
        //search category
        $searchForm = array(
            'keyword'   	=>  trim($_REQUEST['keyword']),
            'bcategory' 	=>  $_REQUEST['bcategory'],
            'cuisine' 		=>  $_REQUEST['cuisine'],
            'state_name' 	=>  $_REQUEST['search_state_name'] ? $_REQUEST['search_state_name'] : ($_REQUEST['state_name'] ? $_REQUEST['state_name'] : ''),
            'suburb'    	=>  $_REQUEST['suburb'],
			'bu_postcode' => isset($_REQUEST['bu_postcode']) ? $_REQUEST['bu_postcode'] : $_GET['bu_postcode'],
            'distance'  	=>  $_REQUEST['distance']
         );
         
         $searchForm['state_name'] = $searchForm['state_name'] ? $searchForm['state_name'] : $_SESSION['search_form']['state_name'];
         $searchForm['suburb'] = $searchForm['suburb'] ? $searchForm['suburb'] : $_SESSION['search_form']['suburb'];
         $searchForm['cuisine'] = $searchForm['cuisine'] ? $searchForm['cuisine'] : $_SESSION['search_form']['cuisine'];
//        if('' == $_REQUEST['keyword']) {
//            $searchForm = array(
//                    'keyword'   =>  '',
//                    'bcategory' =>  $_REQUEST['bcategory'],
//                    'state_name' =>  $_REQUEST['search_state_name'],
//                    'suburb'    =>  $_REQUEST['suburb'],
//                    'distance'  =>  $_REQUEST['distance']
//            );
//        }
//        else {
//            $searchForm = array(
//                    'keyword'   =>  trim($_REQUEST['keyword']),
//                    'bcategory' =>  '',
//                    'state_name' =>  '-1',
//                    'suburb'    =>  '',
//                    'distance'  =>  ''
//            );
//        }

		$query	= "SELECT id, stateName, description FROM aus_soc_state ORDER BY description";
		$result	= $dbcon->execute_query($query);
		$state_list = $dbcon->fetch_records();
		$suburb_data = array();
		
		foreach($state_list as $state) {
			$query	= "SELECT suburb_id, suburb, zip FROM aus_soc_suburb WHERE state = '".$state['stateName']."' GROUP BY suburb ORDER BY suburb ASC";
			$result	= $dbcon->execute_query($query);
			$suburbs = $dbcon->fetch_records();
			$output = '<option value="">All</option>';
			foreach($suburbs as $suburb) {
				$output .= '<option value="'.addslashes($suburb['suburb']).'.'.$suburb['zip'].'.'.$suburb['suburb_id'].'">'.addslashes($suburb['suburb']).'</option>';
			}
			$suburb_data[$state['stateName']] = $output;
		}
        
		$search['category'] = getFoodWineCategorylist(0);
		$search['subcategory'] = $searchForm['bcategory'] ? getFoodWineCategorylist($searchForm['bcategory']) : array();
        $search['search_states'] = getStateArray($searchForm['state_name']);
        $search['search_cuisines'] = getCuisineList();
		$suburbArray = explode('.',$searchForm['suburb']);
		$searchForm['suburb'] = $suburbArray[0];
		$searchForm['postcode'] = $suburbArray[1];
		$searchForm['suburb_id'] = $suburbArray[2];
		$searchForm['bcategory'] = isset($_REQUEST['bcategory']) ? $_REQUEST['bcategory'] : '-2';
		$searchForm['bsubcategory'] = isset($_REQUEST['bsubcategory']) ? $_REQUEST['bsubcategory'] : $_GET['bsubcategory'];
		
		if(get_magic_quotes_gpc()){
			 $searchForm = stripslashes_deep($searchForm);
		}
		
		$searchForm['suburb_data'] = $suburb_data;
	# search for cities
	$suburb = explode('.', $searchForm['suburb']);
	$searchForm['suburb'] = $suburb[0];
	
	$search['cities'] = getSuburbArray((!empty($searchForm['state_name']) ? $searchForm['state_name'] : 'NSW'), $searchForm['suburb']);
	$arrResult =	array('search'=> $search, 'searchForm'=>$searchForm);

	return $arrResult;
}
?>