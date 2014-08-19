<?php
session_start();
include_once "config.php" ;
include_once ('smartyconfig.php');
include_once "maininc.php" ;
include_once ('class/common.php');
include_once "functions.php" ;
require_once('class.soc.php');
require_once('class.socbid.php');
require_once('class.gallery.php');

global $table;

switch ($_REQUEST['svr']){
	case 'check_RefID':
		$query = "SELECT * FROM {$table}bu_detail where ref_name='{$_REQUEST['refID']}'";
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);
		if(is_array($result)&&count($result)>0){
			echo "Nickname: ".htmlspecialchars($result[0]['bu_nickname']);
		}else{
			echo 0;
		}
		exit();
		break;
	case 'check_WishLogin':
		$StoreID = $_POST['StoreID'];
		$pwd = $_POST['PWD'];
		$query = "SELECT password FROM {$table}wishlist_detail WHERE StoreID='$StoreID'";
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);
		if($result){
			if($result[0]['password']==$pwd){
				echo 1;
				$_SESSION['StoreWishlist'][$StoreID] = true;
				
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
		break;
	case 'checkwishItem':
		$StoreID = $_POST['StoreID'];
		$pid = $_POST['pid'];
		$query = "SELECT url_item_name FROM {$table}product where pid='$pid'";
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);
		if($result){
			if(isset($_POST['name'])&&trim($_POST['name'])!=""){
				$query = "SELECT count(*) FROM {$table}wishlist WHERE StoreID='$StoreID' and url_item_name='".clean_url_name($_POST['name'])."' and deleted !='yes'";
			}else{
				$query = "SELECT count(*) FROM {$table}wishlist WHERE StoreID='$StoreID' and url_item_name='{$result[0]['url_item_name']}' and deleted !='yes'";
			}
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records();
			if($result&&$result[0][0]>0){
				echo 1;	
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}		
		break;
	case 'copytowish':
		include_once('./class.wishlist.php');
		$StoreID = $_POST['StoreID'];
		$pid = $_POST['pid'];
		$newname = $_POST['name'];
		$wishlist = new wishlist();
		if($wishlist->copytowishlist($StoreID,$pid,$newname)){
			echo 1;
		}else{
			echo 0;
		}
		break;
	case 'checkthepayamount':
		$pid = $_POST['pid'];
		$amount = $_POST['amount'];
		if(is_numeric($amount)&&$amount>0){
			$query = "SELECT * FROM {$table}wishlist where pid=$pid";
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records(true);
			if($result[0]['protype']){
				echo 1;
			}else{
				if((intval($result[0]['price']*100)-intval($result[0]['gifted']*100)-intval($amount*100))>=0){
					echo 1;
				}else {
					echo "0";
				}
			}
		}else{
			echo 0;
		}
		break;
	case 'deletefile':
		$StoreID = $_POST['StoreID'];
		$file = $_POST['file'];
		$query = "UPDATE {$table}wishlist_detail SET music='' WHERE StoreID='$StoreID'";
		if($dbcon->execute_query($query)){
			unlink(ROOT_PATH.$file);
			echo 1;
		}else{
			echo 0;
		}
		break;
	case 'setplaymusic':
		$StoreID = $_POST['StoreID'];
		$playnow = $_POST['playnow'];
		$_SESSION['Wishlistplay'][$StoreID] = $playnow;
		echo $_SESSION['Wishlistplay'][$StoreID];
		break;
	case 'wishlistpages':
		include_once('./class.wishlist.php');
		include_once('./class.page.php');
		include_once('./class.uploadImages.php');
		chdir('..');
		$wishlist = new wishlist();
		$StoreID = $_REQUEST['StoreID'];
		$_GET['p']=$_REQUEST['p'];
		$_SESSION['wishpage'][$StoreID] = $_REQUEST['p'];
		$req['product'] = $wishlist->getWishlistProlist($StoreID,'featured',true);
		$wishpro = count($wishlist->getWishlistProlist($StoreID));
		$smarty -> assign('procount',$wishpro);
		$smarty -> assign('req',	$req);
		$smarty -> template_dir		= ROOT_PATH ."skin/red/";
		$content = $smarty -> fetch('wishlist/wishlist_tmplist.tpl');
		echo $content;
		break;
	case 'loadinvationUser':
		include_once('./class.gallery.php');
		$gallery = new gallery();
		$result = $gallery->getuserlist($_REQUEST['StoreID'],$_REQUEST['invations']);
		echo json_encode($result);
		break;
	case 'loadCSVUser':
		include_once ("class.processcsv.php");
		$procsv = new processcsv();
		$result = $procsv->showCSVEmail($_REQUEST['StoreID'],'gallery');
		echo json_encode($result);
		break;
	case 'check_GalleryLogin':
		$StoreID = $_POST['StoreID'];
		$pwd = $_POST['PWD'];
		$gl_url = $_POST['URL'];
		$query = "SELECT gallery_category_password,gallery_url FROM {$table}gallery_category WHERE StoreID='$StoreID' and gallery_url='$gl_url'";
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);
		if($result){
			if($result[0]['gallery_category_password']==$pwd){
				echo 1;
				$_SESSION['StoreGallery'][$gl_url] 	= 'true';
				$_SESSION['StoreGallery'][$StoreID] = 'true';
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
		break;
	case 'setGalleryplaymusic':
		$StoreID = $_POST['StoreID'];
		$playnow = $_POST['playnow'];
		$_SESSION['galleryplay'][$StoreID] = $playnow;
		echo $_SESSION['galleryplay'][$StoreID];
		break;
	case 'checkgalleryname':
		require_once('class.gallery.php');
		$gallery = new gallery();
		$StoreID = $_POST['StoreID'];
		$name = $_REQUEST['name'];
		$id = $_REQUEST['id'];
		if(!$gallery->checkgalleryURL($name,$StoreID,$id)){
			echo "The Gallery Name is invalid or exists.";
		}
		break;
		
	case 'checkbidproduct':
		$pid = isset($_REQUEST['pid'])?$_REQUEST['pid']:0;
		$socbid = new socbidClass();
		$binfo = $socbid->getBidInfomation($pid);
		echo json_encode($binfo);
		break;
	case 'bidproduct':
		$socbid = new socbidClass();
		$StoreID = isset($_REQUEST['StoreID'])?$_REQUEST['StoreID']:0;
		$pid = isset($_REQUEST['pid'])?$_REQUEST['pid']:0;
		$price = isset($_REQUEST['price'])?$_REQUEST['price']:0;
		$price = str_replace(',','',$price);
		echo $socbid->bidProduct($pid,$StoreID,$price);
		break;
	/**/
	case 'bidtest':
		$socbid = new socbidClass();
		$StoreID = 854887 + rand(1,1000);
		$pid = 5029;
		$zone = $_REQUEST['zone'];
		$price = 10+rand($zone,$zone*500);
		$type = 'bid';
		if (1){
			//$response = $socbid->bidProduct($pid,$StoreID,$price);
			$response = $socbid->autobidProduct($pid,$StoreID,$price);
		}elseif (rand(1,1000)%5 == 0){
			$price = 10 + rand($zone*250,$zone*500);
			//$price = 10 + $zone*500;
			$type = 'auto';
			$response = $socbid->autobidProduct($pid,$StoreID,$price);
			if(!$response){$response = "Your bid has been submitted unsuccessfully.";}
		}else{
			$response = $socbid->bidProduct($pid,$StoreID,$price);
		}
		switch ($response){
			case "Your Bid amount of $price is too low. Please increase your bid and try again.":
			case "Sorry, the maximum bid is lower than the one you set before. Please change and try again.":
			case "You have been outbid by another user.":
				$return = "low";
				break;
			case 'You are the current winner so you don\'t need to bid again.':
				$return = "no need";
				break;
			case 'Your bid could not be submitted. Please try again.':
				$return = 'rollback';
				break;
			case 'Your bid has been submitted successfully.':
				$return = 'success';
				break;
			default:
				//$return = 'other response';
				break;
		}
		$time = date("Y-m-d H:i:s",time());
		$testLog = array(
			'StoreID'	=> $StoreID,
			'tester'	=> 'LoadTest'.($StoreID-854887),
			'pid'		=> $pid,
			'price'		=> $price,
			'result'	=> $return,
			'btype'		=> $type,
			'btime'		=> $time
		);
		$dbcon->insert_query('bid_test_log',$testLog);
		echo $response;
		break;
	/**/
	case 'addwatchitem':
		$StoreID = isset($_REQUEST['StoreID'])?$_REQUEST['StoreID']:0;
		$pid = isset($_REQUEST['pid'])?$_REQUEST['pid']:0;
		$socbid = new socbidClass();
		echo $socbid->watchItem($pid,$StoreID);
		break;
	case 'autobid':
		$StoreID = isset($_REQUEST['StoreID'])?$_REQUEST['StoreID']:0;
		$pid = isset($_REQUEST['pid'])?$_REQUEST['pid']:0;
		$price = isset($_REQUEST['price'])?$_REQUEST['price']:0;
		$price = str_replace(',','',$price);
		$socbid = new socbidClass();
		if($result = $socbid->autobidProduct($pid,$StoreID,$price)){
			echo $result;
		}else{echo "Your bid has been submitted unsuccessfully.";}
		break;
	case 'changebidorder':
		$ref_id = isset($_REQUEST['refid'])?$_REQUEST['refid']:0;
		$status = isset($_REQUEST['stat'])?$_REQUEST['stat']:'';
		//var_dump($status);
		// check ref_id validation
		if (!is_numeric($ref_id)||strpos($ref_id,'.')!== false){
			exit;
		}
		if ($status != 'Pending' and $status != 'Paid' and $status != 'Shipped' and $status != 'Completed'){
			exit;
		}
		$sql = "update ".$table."order_reviewref set p_status='$status' where ref_id=$ref_id";
		//echo $sql;
		if ($dbcon->execute_query($sql)){
			$return = array('status'=>'true');
			switch ($status){
				case 'Paid':
					$return['html']= "<option value='Paid'>Paid</option>\n<option value='Shipped'>Shipped</option>\n<option value='Completed'>Completed</option>";
					break;
				case 'Shipped':
					$return['html']= "<option value='Shipped'>Shipped</option>\n<option value='Completed'>Completed</option>\n";
					break;
				case 'Completed':
					$return['html']= "<option value='Completed'>Completed</option>";
					break;
			}
			echo json_encode($return);
		}else{
			echo json_encode(array('status'=>'false'));
		}
		
		break;
	case 'saveOuterEmail':
		$intVal = $_REQUEST['outerEmail']?$_REQUEST['outerEmail']:0;
		$arrSetting	=	array(
			'outerEmail' =>	$intVal
		);
		if (!$_SESSION['ShopID']){
			echo json_encode(array('status'=>'login'));
			exit;
		}
		$strCondition	=	" where StoreID='".$_SESSION['ShopID']."'";

		if($dbcon->update_record($table."bu_detail", $arrSetting, $strCondition)){
			$boolResult	=	true;
			$_SESSION['outerEmail']	=	$intVal;
			$status = $intVal?'on':'off';
			echo json_encode(array('status'=>$status));
		}else{
			echo json_encode(array('status'=>'fail'));
		}
		
		break; 
	case 'galleryMove':
		$order = $_REQUEST['order'];
		$id = $_REQUEST['id'];
		$category = $_REQUEST['category'];
		
		if (!is_numeric($order)||!is_numeric($id)||!is_numeric($category)){
			echo json_encode(array('result'=>0,'msg'=>'Parameter is invalid.order='.$order.';id='.$id.';category='.$category));
			exit;
		}
		$order = ceil($order);
		$socObj = new socClass();
		$gallery = new gallery();
		$order = $gallery->generateOrder($category,$order,$id);
		if ($gallery->updateGallery(array('id'=>$id,'gallery_order'=>$order))){
			$list = '';
			$req['gallerylist'] = $gallery->gallerylist($_SESSION['StoreID'],$category);
			$smarty -> template_dir		= ROOT_PATH ."skin/red/";
			$smarty->assign('req',$req);
			$list = $smarty->fetch('gallery/images_list.tpl');
			echo json_encode(array('result'=>1,'msg'=>'Image order is updated successfully.','list'=>$list));
		}else{
			echo json_encode(array('result'=>0,'msg'=>'Failed to update the order.'));
		}
		exit;
		break;
        case 'getgallery' :
                $site=$_GET['site'];
                $gallery_name=$_GET['gallery'];
                $pageid=$_GET['p'];
                include_once ('class.socstore.php');
                $socstoreObj = new socstoreClass();
                $gallery = new gallery();
                
                
                $StoreID = $socstoreObj->getStoreIDbyName($site);
                $galleryInfo = $gallery->getGalleryByName($gallery_name,$StoreID);
                $galleryInfo = $galleryInfo[0];
                $cid = $galleryInfo['id'];
                $gallerlist = $gallery->gallerylist($StoreID,$cid,$pageid);
                $pagelist = $gallery->gallerylistPages($StoreID,$cid,$pageid);
                echo json_encode(array('gallerylist'=>$gallerlist,'pagelist'=>$pagelist));
                break;
	default:
		break;
}
?>