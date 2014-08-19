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
if(isset($_REQUEST['opt'])&&$_REQUEST['opt']=='getajax'){
	include_once ('include/config.php');
	include_once ('include/maininc.php');
	include_once ('include/functions.php');
	
	$limit = $_POST['limit'];
	$keyword = mysql_escape_string($_POST['keyword']);
	$search_type = $_POST['search_type'];
	
	if ($search_type == 'foodwine') {		
		if (empty($keyword)) {
			exit();
		} else {
			$detail_sql = 	"WHERE CustomerType = 'seller' \n".
							"AND attribute = '5' \n".
							"AND renewalDate >= '".time()."' \n".
							"AND MATCH (bu_name) AGAINST ('*".$keyword."*'IN boolean MODE ) \n";
		}
		
		# search products
		$query = "SELECT bu_name AS name FROM ".$table."bu_detail \n".
		"$detail_sql \n".
		"UNION \n".
		"SELECT item_name AS name \n".
		"FROM ".$table."product_foodwine \n".
		"WHERE StoreID \n".
		"IN (SELECT StoreID \n".
		"FROM ".$table."bu_detail \n".
		$detail_sql.
		") AND MATCH (item_name) AGAINST ('*".$keyword."*' IN boolean MODE)";
		//exit($query);
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);			
		$name_ary = getNameByAry($result, $keyword);
	} elseif ($search_type == 'estate') {
		$type = $_POST['type'];
		$agent_name = $_POST['agent_name'];
		
		if (($type == 'keyword' && empty($keyword)) || ($type == 'agent' && empty($agent_name))) {
			exit();
		}
		
		# keyword and agent name condition
		if ($type == 'keyword' && !empty($keyword)){
			$query = "SELECT item_name, content \n";
			$wheresql = " AND ( item_name like '%".$keyword."%' or content like '%".$keyword."%') \n";
		} elseif ($type == 'agent' && !empty($agent_name)){
			$query = "SELECT detail.bu_name, detail.bu_nickname, detail.coname \n";
			$keyword = $agent_name;
			$agent_name = mysql_escape_string($agent_name);
			$wheresql = " AND (detail.bu_name like '%".$agent_name."%' or detail.bu_nickname like '%".$agent_name."%' or detail.coname like '%".$agent_name."%') \n";
		}
		# conditions
		$queryCondition = "FROM ". $table ."product_realestate AS product \n".
		"LEFT JOIN ". $table ."bu_detail AS detail ON detail.StoreID = product.StoreID \n".
		"LEFT JOIN ". $table ."login AS lg ON lg.StoreID = detail.StoreID \n".
		"WHERE product.deleted = '' and product.enabled=1 \n".
		$wheresql.
		"AND detail.CustomerType = 'seller' AND detail.attribute='1' \n".
		//"AND detail.renewalDate >= '".time()."' \n".
		"AND (product.pay_status=2 OR (product.pay_status=1 and product.renewal_date >= '".time()."')) \n".	
		"AND lg.suspend=0 \n".			
		"ORDER BY RAND() \n".
		"LIMIT $limit \n";
		
		$query .= "$queryCondition \n";
		//exit($query);
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);		
		$name_ary = getNameByAry($result, $keyword);
		
	} elseif ($search_type == 'auto') {
		 if (empty($keyword)) {
			exit();
		} else {
			$wheresql = " AND (item_name like '%".$keyword."%' or content like '%".$keyword."%'".
			" or detail.bu_name like '%".$keyword."%' or color like '%".$keyword."%'".
			" or kms like '%".$keyword."%' or year like '%".$keyword."%')";
		}
		# conditions
		$queryCondition = "FROM ". $table ."product_automotive AS product \n".
		"LEFT JOIN ". $table ."bu_detail AS detail ON detail.StoreID = product.StoreID \n".
		"LEFT JOIN ". $table ."login AS lg ON lg.StoreID = detail.StoreID \n".
		"WHERE product.deleted = '0' and product.enabled=1 \n".
		$wheresql.
		"AND detail.CustomerType = 'seller' AND detail.attribute='2' \n".
		//"AND detail.renewalDate >= '".time()."' \n".
		"AND (product.pay_status=2 OR (product.pay_status=1 and product.renewal_date >= '".time()."')) \n".	
		"AND lg.suspend=0 \n".		
		"ORDER BY RAND() \n".
		"LIMIT 1 \n";

		# search products
		$query = "SELECT item_name, content, detail.bu_name, color, kms, year \n".
		"$queryCondition \n";
		
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);		
		$name_ary = getNameByAry($result, $keyword);
	} elseif ($search_type == 'job') {
		if (empty($keyword)) {
			exit();
		} else {
			# keywords search
			$wheresql = " AND (item_name like '%".$keyword."%' ".
			" or content1 like '%".$keyword."%' ".
			" or content2 like '%".$keyword."%' ".
			" or content3 like '%".$keyword."%' ".
			" or detail.bu_name like '%".$keyword."%')";
		}
		# current date
		$current_date = date("Y-m-d");               
		# conditions
		$queryCondition = "FROM ". $table ."product_job AS product \n".
		"LEFT JOIN ". $table ."bu_detail AS detail ON detail.StoreID = product.StoreID \n".
		"LEFT JOIN ". $table ."login AS lg ON lg.StoreID = detail.StoreID \n".
		" WHERE product.deleted = '0' and product.enabled=1 \n".
		"AND detail.CustomerType = 'seller' AND detail.attribute='3' \n".
		"AND ((datePosted <= '$current_date' or datePosted='0000-00-00') \n".
		"AND (closingDate >= '$current_date' or closingDate='0000-00-00')) \n ";
		//$queryCondition .= "AND IF(detail.subAttrib<>3,detail.renewalDate>='".time()."',1=1) \n";
		$queryCondition .= "AND (product.pay_status=2 OR (product.pay_status=1 and product.renewal_date >= '".time()."')) \n";
		if($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2)) {
			$queryCondition .= " AND IF(product.category=2, product.ispub in(0,1) , 1=1) ";
		}else{
			$queryCondition .= " AND IF(product.category=2, product.ispub in(1) , 1=1) ";
		}
		$queryCondition .= $wheresql." AND product.category in (1)\n".		
		 					"AND lg.suspend=0 \n".		
							"ORDER BY RAND() \n".
							"LIMIT $limit \n";
		
		$query = "SELECT product.item_name, detail.bu_name, content1, content2, content3 \n".
		"$queryCondition \n";
		
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);		
		$name_ary = getNameByAry($result, $keyword);
		
	} else {
		$type = $_POST['type'];
		$product_name = $_POST['product_name'];
		$seller_name = $_POST['seller_name'];
		
		if (($type == 'product' && empty($product_name)) || ($type == 'seller' && empty($seller_name))) {
			exit();
		}
		
		//Get Auto-Fill product string		
		$tfls = 0;
		$timenow = time();	
		$wheresql .= " and product.on_sale <> 'sold' AND IF(product.is_auction='yes',".($tfls>0?"au.end_stamp-$timenow<=$tfls and ":"")." au.end_stamp-$timenow>0,1=1) ";
		$wheresql .= " and product.is_auction in('yes','no') ";
		
		if ($type == 'product' && $product_name) {
			$keyword = $product_name;
			$wheresql .= "and product.item_name like '%$product_name%'";
			$query	=	"SELECT DISTINCT(product.item_name) AS name";
		} elseif ($type == 'seller' && $seller_name) {
			$keyword = $seller_name;
			$query	=	"SELECT DISTINCT(detail.bu_name) AS name";
			$wheresql .= "and detail.bu_name like '%$seller_name%'";
		}
		
		$query .= " FROM ".$table."product as product " .
		" left join ".$table."bu_detail as detail on detail.StoreID=product.StoreID ".
		" left join ".$table."login as lg on lg.StoreID = detail.StoreID ".
		" left join ".$table."product_auction  as au on au.pid=product.pid ".
		" WHERE 1=1 $wheresql and detail.CustomerType = 'seller' ".
		//" AND detail.renewalDate > ".time().
		" AND lg.suspend=0 ".
		" AND product.Deleted = '' and not (detail.bu_name is null) ".
	    " AND IF(product.is_auction='yes',au.starttime_stamp <=".time().",1=1) ".
		" Limit $limit";
		
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);
				
		$name_ary = getNameByAry($result, $keyword);
	}
	
	echo json_encode($name_ary);
	exit();
}

function getNameByAry($ary, $keyword, $num = 10)
{
	$keyword = strtolower($keyword);
	$tmp_ary = array();
	if ($ary) {
		$i = 1;
		foreach ($ary as $val) {
			if ($i > $num) {
				break;
			}
			foreach ($val as $name) {
				if (empty($name)) {
					continue;
				}
				if ($i > $num) {
					break;
				}
				$name = strip_tags($name);
				$pos = strpos(strtolower($name), $keyword);
				if ($pos !== false) {
					$sub_str = smart_substr(substr($name, $pos), 30, '');
					if (strlen($sub_str) > 1 && !in_array($sub_str, $tmp_ary)) {
						$tmp_ary[] = $sub_str;
						$i++;
					}					
				}				
			}
		}	
		
		$tmp_ary = array_unique($tmp_ary);
		sort($tmp_ary);
	}
	//echo $i;
	//exit($num);	
	return $tmp_ary;
}
?>