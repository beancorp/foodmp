<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//@session_start();
//include_once ('../include/smartyconfig.php');
//include_once ('maininc.php');
include_once ('functions.php');
//include_once ('class/pagerclass.php');
//include_once ('class.emailClass.php');
//include_once ('class.jobClass.php');
//include_once ('class.uploadImages.php');
//include_once ('class/ajax.php');

include('../indexPart.php');
$indexTemplate = '../index.tpl';
include('../include/class.cart.php');

$smarty->assign('isstorepage', 1);
switch ($_REQUEST["cp"]) {

    //stock items
    case 'list':
        $StoreID = $_REQUEST['StoreID'];
        if (empty($StoreID)) {
            echo '<script>alert("Please come from the correct url.");javascript:history.go(-1);</script>';
        }

        $sub_type = $_GET['type'];
        $is_special = $_GET['s'];
        $category = $_GET['category'];
        $req = $socObj->displayStoreWebside();
        $req['info']['attribute'] = 5;
        $req['info']['type'] = $sub_type;
        $req['info']['s'] = $is_special;
        $fw_type = $req['info']['foodwine_type'];

        $foodWine = new FoodWine();

        $emailAlertItem = $foodWine->getLastEmailAlert($req['info']['StoreID']);
        $validDateStr = "";
//        if ($emailAlertItem && $emailAlertItem['end_date'] >= time()) {
//            $startDate = date('d-m-Y', $emailAlertItem['start_date']);
//            $endDate = date('d-m-Y', $emailAlertItem['end_date']);
//            $validDateStr = "<div class='spcial-title'>Specials Valid: {$startDate} - {$endDate}</div>";
//        }

		/*
		if (str_word_count($req['info']['bu_name']) > 2) {
			$req['info']['bu_name'] = implode(' ', array_slice(explode(' ', $req['info']['bu_name']), 0, 2));
		} else {
			$req['info']['bu_name'] = (strlen($req['info']['bu_name']) > 30) ? substr($req['info']['bu_name'],0,30).'...' : $req['info']['bu_name'];
		}*/
        
        if ($sub_type == 'wine') {
            if ($is_special) {
                $products = $req['specials']['items'];
                $linkStr = $req['specials']['linkStr'];
                $item_title = $page_title = $req['info']['bu_name'] . " - Specials";
                $tpl_stock = '../foodwine/stock_items.tpl';
            } else {
                $products = $req['items'];
                $linkStr = $req['items_linkStr'];
                $item_title = $page_title = $req['info']['bu_name'] . ' - Menu';
                $tpl_stock = '../foodwine/stock_wine_items.tpl';
            }
        } else {
            if ($sub_type == 'stock') {
                $products = $req['items']['stock_items'];
                $linkStr = $req['items']['stock_items_linkStr'];
                $item_title = $page_title = $req['info']['bu_name'] . ' - Stock items';
            } else {
                $products = $req['items']['specials'];
                $linkStr = $req['items']['specials_linkStr'];
                $item_title = $page_title = $req['info']['bu_name'] . " - Specials";
            }
            $tpl_stock = '../foodwine/stock_items.tpl';
            $show_basket = true;
        }

        $categories = $foodWine->getCategoryList($req['info']['foodwine_type'], $req['info']['StoreID']);
        $smarty->assign('categories', $categories);

        $smarty->assign('req', $req);
        $smarty->assign('show_join_banner', 1);
        $smarty->assign('show_season_banner', 1);
        $smarty->assign('headerInfo', $req['info']);
        $tmp_header = $smarty->fetch('../template/tmp-header-include.tpl');
        $smarty->assign('tmp_header', $tmp_header);
        
        $smarty->assign('itemTitle', $socObj->getTextItemTitle($item_title, 4, $req['template']['bgcolor'] ? $req['template']['bgcolor'] : '3c3082', '', $show_basket).$validDateStr);
        $smarty->assign('sidebar', 0);
        $smarty->assign('pageTitle', $page_title);

        $smarty->assign('products', $products);
        $smarty->assign('linkStr', $linkStr);
        $smarty->assign('content', $smarty->fetch($tpl_stock));
        break;

    case 'delete':

        if (!isset($_SESSION['StoreID']) and 1 != $_SESSION['level']) {
            header('Location:/soc.php?cp=home');
            exit;
        }
        if (isset($_REQUEST['pid'])) {
            $pid = array();
            if (is_array($_REQUEST['pid'])) {
                $pid = $_REQUEST['pid'];
            } elseif (is_string($_REQUEST['pid'])) {
                $pid[] = $_REQUEST['pid'];
            }

            foreach ($pid as $k => $id) {
                $pid[$k] = intval($id);
            }

            $foodWine->deleteProductByPID($pid);
        }
        header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=product&step=4&msg=Deleted successfully.');
        exit;
        break;
    case 'addcategory':

        if (!isset($_SESSION['StoreID']) and 1 != $_SESSION['level']) {
            header('Location:/soc.php?cp=home');
            exit;
        }

        $fid = $_POST['fid'];
        $cid = $_REQUEST['cid'];
        $name = $_POST['category_name'];
        $res = $foodWine->checkCategoryName($name, $fid, $_SESSION['StoreID'], $cid);
        if ($res) {
            header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=product&step=4&msg=The Category name already exists.');
            exit;
        }
        if ($cid) {
            $foodWine->editCategory($cid, array('category_name' => $name));
            $sub_url = "&tab=clist&msg=Edit Category successfully.#list";
        } else {
            $foodWine->addCategory($name, $fid, $_SESSION['StoreID']);
            $sub_url = "&msg=Add Category successfully.";
        }

        header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=product&step=4' . $sub_url);
        exit;
        break;
    case 'getcategoryname':

        if (!isset($_SESSION['StoreID']) and 1 != $_SESSION['level']) {
            header('Location:/soc.php?cp=home');
            exit;
        }
        $fid = $_POST['fid'];
        $cid = $_POST['cid'];
        $res = $foodWine->getCategoryInfo($cid, $fid, $_SESSION['StoreID']);
        //print_r($res);
        exit($res['category_name']);
        break;
    case 'checkcategoryname':

        if (!isset($_SESSION['StoreID']) and 1 != $_SESSION['level']) {
            header('Location:/soc.php?cp=home');
            exit;
        }

        $fid = $_POST['fid'];
        $cid = $_POST['cid'];
        $name = $_POST['category_name'];
        $res = $foodWine->checkCategoryName($name, $fid, $_SESSION['StoreID'], $cid);
        exit($res);
        break;
    case 'deletecategory':

        if (!isset($_SESSION['StoreID']) and 1 != $_SESSION['level']) {
            header('Location:/soc.php?cp=home');
            exit;
        }
        if (isset($_REQUEST['cid'])) {
            $cid = array();
            if (is_array($_REQUEST['cid'])) {
                $cid = $_REQUEST['cid'];
            } elseif (is_string($_REQUEST['cid'])) {
                $cid[] = $_REQUEST['cid'];
            }

            foreach ($cid as $k => $id) {
                $cid[$k] = intval($id);
            }

            $foodWine->deleteCategoryByCID($cid);
        }
        header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=product&step=4&tab=clist&msg=Deleted category successfully.#list');
        exit;
        break;
    case 'savecategory':
        if (!isset($_SESSION['StoreID']) and 1 != $_SESSION['level']) {
            header('Location:/soc.php?cp=home');
            exit;
        }

        $cid_ary = $_POST['cid'];
        $category_name_ary = $_POST['category_name'];
        foreach ($category_name_ary as $name) {
            if (empty($name)) {
                header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=product&step=4&msg=Category name is required.');
                exit();
            }
        }
        $res = $foodWine->saveCategory($cid_ary, $category_name_ary, $_SESSION['StoreID']);
        $msg = $res ? 'Save category successfully.' : 'Save category failed.';
        header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=product&step=4&msg=' . $msg);
        exit();
        break;
    case 'savecategoryorder':

        if (!isset($_SESSION['StoreID']) and 1 != $_SESSION['level']) {
            header('Location:/soc.php?cp=home');
            exit;
        }

        $order = $_REQUEST['order'];
        if (is_array($order)) {
            foreach ($order as $k => $val) {
                echo $k = str_replace("'", '', stripcslashes($k));
                $foodWine->saveCategoryOrder($k, $val);
            }
        }
        header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=product&step=4&tab=clist&msg=Save category order successfully.#list');
        exit;
        break;
    default :
        if (!isset($_SESSION['StoreID']) and 1 != $_SESSION['level']) {
            header('Location:/soc.php?cp=home');
            exit();
        }

        $foodWineType = getFoodWineType();

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $data = $foodWine->checkProductData($_POST, $_POST['pid'] > 0 ? true : false);
            if (false == $data['status']) {
                header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=product&step=4&msg=' . implode("\n\r", $data['msg']));
                exit;
            }
            $foodWine->saveProduct($data['data'], $data['images'], $_POST['pid']);
            header('Location: ' . SOC_HTTPS_HOST . 'foodwine/?act=product&step=4&msg=Product saved successfully. ');
            exit;
        }

        $socstoreObj = new socstoreClass();
        $itemtitle = $socstoreObj->getTextItemTitle('Start Selling', 3);
        $menubar = $socstoreObj->getStartsellingMenu($stepOperate, true);
        $smarty->assign('itemTitle', $itemtitle . $menubar);
        $smarty->assign('pageTitle', 'The SOC Exchange');

        //get product data
        $productInfo = array();
        if (isset($_GET['pid']) and $_GET['pid'] > 0) {
            $productInfo = $foodWine->getProductInfo($_SESSION['StoreID'], $_GET['pid']);
        }
        $smarty->assign('productInfo', $productInfo);
//        echo '<pre>';var_dump($productInfo);exit;
        //other data
        $products = $foodWine->getProductsList($_SESSION['StoreID'], $foodWineType);
        $smarty->assign('productList', $products);

        $store_info = $socObj->displayStoreWebside(true);
        $req = array_merge($_REQUEST, $store_info);

        //paypal required on Buy & Sell and Food & Wine
        if (($req['info']['attribute'] == 0 || ($req['info']['attribute'] == 5 && $req['info']['sold_status'] == 1)) && $req['info']['bu_paypal'] == '') {
            exit("<script>alert('Please fill in Paypal Account in step2 before you edit step3 and step4.');location.href='/soc.php?act=signon&step=2';</script>");
        }

        $smarty->assign('req', $req);
        $categories = $foodWine->getCategoryList($foodWineType, $_SESSION['StoreID']);
        $smarty->assign('categories', $categories);
        $smarty->assign('categories_num', ($categories ? count($categories) : 0));

        $smarty->assign('sidebar', 0);
        $smarty->assign('isstorepage', 0);
        $smarty->assign('foodwine_type', $foodWineType);
		$smarty->assign('secure_url',SOC_HTTPS_HOST);
        $smarty->assign('content', $smarty->fetch('../foodwine/product_edit.tpl'));
        //$smarty->assign('content', $smarty->fetch('../foodwine/' . $foodWineType . '_product_edit.tpl'));
        break;
}
?>
