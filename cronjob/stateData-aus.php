#!/usr/bin/php
<?php
//require_once('../include/smartyconfig.php');
//require_once '../include/maininc.php';
//require_once '../include/class.soc.php';
$site_path=dirname(dirname(__FILE__));     //new live site path
require_once($site_path.'/include/smartyconfig.php');
require_once($site_path.'/include/maininc.php');
require_once($site_path.'/include/class.soc.php');

global $table;
$dbcon->execute_query('delete from '.$table.'ads_new');
$sql =  "select a.StoreID,bu_name as website,bu_urlstring as url,stateName as state"
		." from ".$table."bu_detail as a,".$table."state as b,".$table."login as l "
		."where a.bu_state=b.id and a.StoreID=l.StoreID and a.attribute=0 and l.suspend!=1"
		." and renewalDate > ".time();
$dbcon->execute_query($sql);
$seller = $dbcon->fetch_records(true);
//echo $sql;
$socObj = new socClass();
$total = 1;
for($i=0; $i < count($seller);$i++){
	$time = time();
	// get category name list
	$sql =  "SELECT distinct d.name FROM ".$table."product p "
			."left join ".$table."product_category as c on p.category=c.id "
			."left join ".$table."product_category as d on c.fid=d.id "
			."left join ".$table."product_auction as a on a.pid=p.pid "
			."left join ".$table."image as i on p.StoreID=i.StoreID and "
			."p.pid=i.pid and i.attrib=0 and i.sort=0 "
			."WHERE p.deleted!='YES' and IF(p.is_auction='yes',a.end_stamp>$time,1) "
			."and i.smallPicture is not null "
			."and (p.on_sale='yes' or p.on_sale='contact') "
			."and p.stockQuantity>0 and p.StoreID=".$seller[$i]['StoreID'];
	$dbcon->execute_query($sql);
	$result = array();
	$result = $dbcon->fetch_records();
	//echo "$sql\n";
	//var_dump($result);
	$category = array();
	if ($result){
		foreach ($result as $row){
			$category[] = $row['name'];
		}
		$seller[$i]['category'] = implode(', ',$category);
	}else{
		//continue;
		$seller[$i]['category'] = 'None';
	}
	if ($seller[$i]['StoreID'] == '854837'){
		echo $sql."\n".$seller[$i]['category']."\n";
	}
	
	// get product list
	$sql = "select item_name,url_item_name from ".$table."product p "
		."left join ".$table."product_auction as a on a.pid=p.pid "
		."left join ".$table."image as i on p.StoreID=i.StoreID and "
		."p.pid=i.pid and i.attrib=0 and i.sort=0 "
		."where p.StoreID=".$seller[$i]['StoreID']." and deleted!='YES' "
		."and IF(p.is_auction='yes',a.end_stamp>$time,1) "
		."and i.smallPicture is not null and "
		."(p.on_sale='yes' or p.on_sale='contact') and p.stockQuantity>0 "
		."order by isfeatured desc, p.datem desc limit 4";
	$dbcon->execute_query($sql);
	$result = array();
	$result = $dbcon->fetch_records();
	$count = count($result);
	
	$items = array();
	if ($result){
		$count = 0;
		foreach ($result as $row){
			$items[] = '<li><a href="/'.$seller[$i]['url'].'/'.$row['url_item_name'].'">'.$row['item_name'].'</a></li>';
			$count++;
			if ($count>=4){
				break;
			}
		}
	}else{
		$items = array('None');
		//continue;
	}
	$seller[$i]['items'] = implode("\n",$items);
	if ($seller[$i]['StoreID'] == '854837'){
		echo $sql."\n".$seller[$i]['items']."\n";
	}
	
	$sql = "SELECT t7.smallPicture, t7.picture FROM ".$table."product AS t1 "
		."LEFT JOIN ".$table."product_auction au ON t1.pid = au.pid "
		."LEFT JOIN ".$table."image AS t7 ON t1.pid = t7.pid "
		."AND t1.pid = t7.pid AND t7.attrib =0 AND t7.sort =0 "
		."WHERE t1.StoreID =  '".$seller[$i]['StoreID']."' AND t1.deleted =  '' "
		."AND IF( t1.is_auction =  'yes', au.end_stamp >".$time.", 1 =1 ) "
		."AND t7.smallPicture IS NOT NULL and (t1.on_sale='yes' or t1.on_sale='contact') "
		."and t1.stockQuantity>0 " 
		."ORDER BY t1.isfeatured DESC , t1.datec DESC , t1.item_name LIMIT 1";
	$dbcon->execute_query($sql);
	$result = array();
	$result = $dbcon->fetch_records();
	$imgs = $result[0];
	if ($imgs){
		$seller[$i]['small_image'] = $imgs['smallPicture'];//['smallPicture'];
		$seller[$i]['large_image'] = $imgs['picture'];
		//var_dump($imgs);
	}
	insertAds($seller[$i]);
}

function insertAds($arr){
	global $table,$dbcon;//,$total;
	$fields = array_keys($arr) ;
	$values = array_values($arr) ;

	$values2 = "" ;
	for($i=0;$i<count($values);$i++){
		$values2 .= '"' . mysql_real_escape_string(trim($values[$i])). '",' ;
	}

	$fields = implode(",",$fields) ;
	$values2 = substr($values2,0,-1) ;
	$sql = "insert into ".$table."ads_new ($fields) values($values2)" ;
//	echo $total.':'.$sql."\n";
//	$total++;
//	exit;
	$result = $dbcon->execute_query($sql);
	if ( ! $result ){
		echo "Error : ".mysql_error();
		exit;
	}
}
?>
