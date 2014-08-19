<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include('../indexPart.php');
$indexTemplate	=	'../index.tpl';

//Check Login
if($_SESSION['UserID'] == '' && $_SESSION['level'] != 1){
	header("Location:/soc.php?cp=login");
}

$req = array();
$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
switch ($_REQUEST["cp"]) {
 	case 'edit':
		$rid = $_REQUEST['rid'];
		$preview = $_REQUEST['preview'];
		
		if ($preview && $_SESSION['recipe_info']) {
			$req['info'] = $_SESSION['recipe_info'];
		} elseif ($rid) {
			$req['info'] = $foodWine->getRecipeInfo($rid);
		}
		
 		break;
 		
 	case 'delete':
		$rid = $_REQUEST['rid'];
		$res = $foodWine->delRecipe($rid, $StoreID);
		$msg = $res ? 'Recipe deleted successfully.' : 'Recipe deleted failed.';
		
        header('Location: /foodwine/?act=recipes&msg=' . $msg);
 		break;
 	
 	case 'preview':
        if('POST' === $_SERVER['REQUEST_METHOD']) {
	 		$data = $foodWine->checkRecipeData($_POST, $_POST['rid'] > 0 ? true : false);
	 		$data['data']['id'] = $_POST['rid'];
	 		$_SESSION['recipe_info'] = $data['data'];
	 		header("Location: /soc.php?cp=recipes&preview=1&StoreID=$StoreID");
	        exit();
        }
 	default:
		
		$rid = $_REQUEST['rid'];
		$title = $_REQUEST['title'];
		$content = $_REQUEST['content'];
		$StoreID = $_REQUEST['StoreID'];
		
        if('POST' === $_SERVER['REQUEST_METHOD']) {
            $data = $foodWine->checkRecipeData($_POST, $_POST['rid'] > 0 ? true : false);
            if(false == $data['status']) {
                header('Location: /foodwine/?act=recipes&msg=' . implode("\n\r", $data['msg']));
                exit;
            }
            
        	$msg = $_POST['rid'] ? 'Recipe edited successfully. ' : 'Recipe posted successfully. ';
            $rid = $foodWine->saveRecipe($data['data'], $_POST['rid']);
            $_SESSION['recipe_info'] = '';
            
            header('Location: /foodwine/?act=recipes&msg=' . $msg);
            exit;
        }
		break;
}

$req['recipe_list'] = $foodWine->getRecipeList($StoreID);
$store_info = $socObj->getStoreInfo($StoreID);
$req = array_merge($req, $_REQUEST, $store_info);
//print_r($req);
$smarty->assign('req',$req);
$smarty->assign('sidebar',0);
$pageTitle = 'Announcements';
$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Recipes');
$smarty -> assign('keywords','Recipes');
$content = $smarty->fetch('recipes.tpl');
$smarty -> assign('content', $content);
?>
