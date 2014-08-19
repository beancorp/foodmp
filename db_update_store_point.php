<?php 
include_once ('include/config.php');
@session_start();
include_once ('include/smartyconfig.php');
include_once ('include/maininc.php');
include_once ('include/class.soc.php');
include_once "include/class.socstore.php" ;
include_once "include/class.point.php" ;

/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

$current_time = time();
$pointObj = new Point();
$socObj = new socClass();
$stostoreObj = new socstoreClass();

$psw = $_GET['psw'];
if ($psw != '1314') {
	echo 'Incorrect Passwod.';
	exit();
}

echo 'START <br />';

/***	Start	***/
$sql = "SELECT * FROM {$table}point_type WHERE id=18";
$point_type = $dbcon->getOne($sql);

$sql = "SELECT detail.* FROM {$table}bu_detail detail \n".
	   "LEFT JOIN {$table}login login ON detail.StoreID=login.StoreID \n".
	   "WHERE detail.is_popularize_store='0' \n".
	   "AND detail.CustomerType='seller' \n".
	   "AND detail.israceold='1' \n".
	   "AND NOT(detail.attribute='3' AND detail.subAttrib='3') \n".
	   "AND login.suspend='0' \n".
	   "ORDER BY detail.StoreID ASC";
$result = $dbcon->execute_query($sql);
$p_info = $dbcon->fetch_records(true);
foreach ($p_info as $p) {
	$active_num = getActiveProdNum($p['StoreID']);
		echo ':'.$p['StoreID'].','.$active_num .'<br />';
	if ($active_num) {			
		$point_data = array(
			'StoreID' 			=> 	$p['StoreID'],
			'ref_store_id'		=>	0,
			'type'  			=> 	$point_type['id'],
			'point'				=>	$point_type['point'],
			'timestamp'			=> time()
		);
		
		if (!$dbcon->checkRecordExist($table.'point_records', "WHERE StoreID='{$p['StoreID']}' AND type='{$point_type['id']}'")) {
			$dbcon->insert_record($table.'point_records', $point_data);
			echo $p['StoreID'].'<br />';	
		}
	}	
}	   

	   
/***	End 	***/

echo '<br /> END';

function getActiveProdNum($StoreID)
{
	global $dbcon, $table;
	
	$_REQUEST['StoreID'] = $StoreID;
	
	//seller info
	$_query = "SELECT * FROM ".$table."bu_detail where StoreID='$StoreID'";
	$arrTemp = $dbcon->getOne($_query);
	
	//product info
	switch ($arrTemp['attribute']){
		case 0:
			$_query	= "select pid from ".$table."product where StoreID='$StoreID' and deleted != 'YES' ";
			break;
		case 1:
			$_query	= "select pid from ".$table."product_realestate where StoreID='$StoreID' and deleted=0";
			break;
		case 2:
			$_query	= "select pid from ".$table."product_automotive where StoreID='$StoreID' and deleted=0";
			break;
		case 5:
			$_query	= "select pid from ".$table."product_foodwine where StoreID='$StoreID' and deleted=0";
			break;

		default:
			$attribute = $arrTemp['attribute'];
			$subAttrib = $arrTemp['subAttrib'];
			if($attribute == 3 && $subAttrib == 1){
				$job_where = " and category=1 ";
			}else{
				$job_where = " and category=2 ";
			}
			$_query	= "select pid from ".$table."product_job where StoreID='$StoreID' and deleted=0 $job_where";
			break;
	}
	$dbcon->execute_query($_query);
	$product_list = $dbcon->fetch_records(true);
	$product_count = 0;
	
	if (is_array($product_list)){
		if ($arrTemp['attribute'] == 3) {
			return true;
		}
		
		include_once "include/class.uploadImages.php";
		$socObj = new socClass();
		$imageObj = new uploadImages();
		foreach ($product_list as $product) {
			//echo $product['pid'].',';
			/*$_REQUEST['proid'] = $product['pid'];
	        $_REQUEST['pre'] = 1;
	        $req	=	$socObj -> displayStoreProduct();
	    	if ($req['items']['product'][0]['images']['uploadCount'] >= 1) {
	    		$product_count++;
	    	}*/
			$sql = "select * from {$table}image where StoreID='$StoreID' and pid='{$product['pid']}' and picture!='/images/700x525.jpg' and picture!='/images/79x79.jpg' and picture!='/images/243x212.jpg'";
			//echo $sql;
			$dbcon->execute_query($sql);
			$image_list = $dbcon->fetch_records(true);
			//print_r($image_list);
			foreach ($image_list as $image) {
				//echo $image['picture'];
				if (is_file(ROOT_PATH.$image['picture'])) {
					//echo $image['picture'];
					return true;
				}
			}
		}
	}
	
	return false;
}

?>
