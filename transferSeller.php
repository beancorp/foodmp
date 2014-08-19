<?php
/**
 * Tue Feb 24 02:41:08 GMT 2009 02:41:08
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V2.0
 * ------------------------------------------------------------
 * seller transfer function
 * ------------------------------------------------------------
 * transferSeller.php
 * ------------------------------------------------------------
 * params ntn= table pre . I.E. ntn=aus_soc_  or ntn=usa_soc_  or ntn=
 * params cp = changeimages or ''
 */
 
require_once "include/smartyconfig.php";
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/class/common.php" ;
include_once "include/class.adminmain.php" ;
require_once "zip.class.php";

switch ($_REQUEST['cp']){
	//after insert products.
	case 'changeimages':
		echo changedPidOfImage();
		break;

	default:
		$ntable	=	empty($_REQUEST['ntn']) ? $table : $_REQUEST['ntn'] ;
		
		//$where ="and `store_name` in ('FurnitureSaleBeQuick','BensBasement','CindysCloset','GOLFCRAZY','SWEATHEAVEN','GrandTerraceForSale','HomeSweetHome','KeepingTime','SarahsStyle','TheGoldStop','TheGreenThumb','TheTrainingPit','TomsTV','SandysCastle','FAMILYFUN','SampleStudentSite','CoolTreasures','JohnJenkins')";
		$where ="and `store_name` in ('TJBooker','McGrawHlll','124515','AllTradersAutomobile','CityAuto','bhah','ExecutiveAppointments','OlivieraRecrultment','highachiever')";

		$query =	"SELECT * FROM `".$table."login` WHERE 1 $where order by id";
		$dbcon -> execute_query($query);
		$arrTemp = $dbcon -> fetch_records(true);

		$strOutput	=	"-- $where\n\n";
		$strStoreID		=	"";
		if (is_array($arrTemp)) {
			foreach ($arrTemp as $temp){
				$strStoreID	.=	",'$temp[StoreID]'";
			}
			$strStoreID	=	substr($strStoreID,1);
		}

		if (!empty($strStoreID)) {
			$strOutput .= getTableSQL("`".$table."login`", "WHERE StoreID in ($strStoreID)","'id'");
			$strOutput .= getTableSQL("`".$table."template_details`", "WHERE StoreID in ($strStoreID)", "'TemplateID',");
			$strOutput .= getTableSQL("`".$table."bu_detail`", "WHERE StoreID in ($strStoreID)", ",'Cat_ID','PayAmmount','bu_cri','trialflag','bu_sent','Status','UserType'");
			$strOutput .= getTableSQL("`".$table."blog`", "WHERE StoreID in ($strStoreID)","'blog_id',");
			$strOutput .= getTableSQL("`".$table."blog_comment`", "WHERE StoreID in ($strStoreID)", "'comment_id'");
			$strOutput .= getTableSQL("`".$table."deliverydetail`", "WHERE StoreID in ($strStoreID)","'DeliveryID'");
			$strOutput .= getTableSQL("`".$table."order_reviewref`", "WHERE StoreID in ($strStoreID)", "'ref_id'");
			//$strOutput .= getTableSQL("`".$table."order_detail`", "WHERE StoreID in ($strStoreID)", "'OrderID'");
			$strOutput .= getTableSQL("`".$table."hitcount_store`", "WHERE StoreID in ($strStoreID)");
			$strOutput .= getTableSQL("`".$table."ads`", "WHERE StoreID in ($strStoreID)");
			//$strOutput .= getTableSQL("`".$table."article`", "WHERE StoreID in ($strStoreID)","");
			$strOutput .= getTableSQL("`".$table."shopping_cart`", "WHERE StoreID in ($strStoreID)");
			$strOutput .= getTableSQL("`".$table."specials`", "WHERE StoreID in ($strStoreID)");
			$strOutput .= getTableSQL("`".$table."product`", "WHERE StoreID in ($strStoreID)", "'pid'");
			$strOutput .= getTableSQL("`".$table."product_realestate`", "WHERE StoreID in ($strStoreID)", "'pid'");
			$strOutput .= getTableSQL("`".$table."product_category`", "WHERE StoreID in ($strStoreID)", "'pid'");
			$strOutput .= getTableSQL("`".$table."product_job`", "WHERE StoreID in ($strStoreID)", "'pid'");
			$strOutput .= getTableSQL("`".$table."product_automotive`", "WHERE StoreID in ($strStoreID)", "'pid'");
			$strOutput .= getTableSQL("`".$table."image`", "WHERE StoreID in ($strStoreID)", "'id'");

			$strOutput .= getImagesPath("WHERE StoreID in ($strStoreID)", "usa_soc_multi_seller", "aus_soc_multi_seller");
		}
		echo $strOutput;

		break;
}

function getTableSQL($tableName,$strWhere,$notFileds='',$keyFileds='StoreID'){
	$strResult = '';
	$dbcon	=	$GLOBALS['dbcon'];
	$table	=	$GLOBALS['table'];
	$ntable	=	$GLOBALS['ntable'];
	
	$nTName	=	str_replace($table, $ntable, $tableName);
	
	//save change id
	if (strripos("'`".$table."product`'`".$table."product_realestate`'`".$table."product_job`'`".$table."product_automotive`'", "'$tableName'") !== false){
		$query =	"update $tableName set transfer= '' ";
		$dbcon -> execute_query($query);
		$query =	"update $tableName set transfer= insert(insert(`StoreID`,(LENGTH(`StoreID`)+1),0,'_'),(LENGTH(`StoreID`) +2),0,`pid`) $strWhere";
		$dbcon -> execute_query($query);
		$strResult .= "UPDATE $nTName SET `transfer`= '' ;\n";
	}

	$query =	"SELECT * FROM $tableName $strWhere";

	$dbcon -> execute_query($query);
	$arrTemp = $dbcon -> fetch_records(true);

	if (is_array($arrTemp)) {
		$arrTemp2	= $arrTemp[0];
		$i=0;
		foreach ($arrTemp2 as $key => $value){
			if (empty($notFileds) || (strripos($notFileds, "'$key'")===false)) {
				$strTitle .= ",`$key`";
				if($i == 0 && !empty($keyFileds)){
					$strWhere = "WHERE `$keyFileds` in ";
				}elseif ($i == 0) {
					$strWhere = "WHERE `$key` in ";
				}
				$i++;
			}
		}
		$strTitle	=	substr($strTitle,1);

		$strTemp	=	'';
		$strWhereValue = '';
		foreach ($arrTemp as $temp){
			$strTemp	.=	",(";
			$i=0;
			foreach ($temp as $key => $value){
				if (empty($notFileds) || (strripos($notFileds, "'$key'")===false)) {
					$strTemp	.=	$i ? ",'".str_replace('\'',"''",$value)."'" : "'".str_replace('\'',"''",$value)."'" ;
					if(!empty($keyFileds)){
						if ($keyFileds == $key) {
							$strWhereValue .= ",'$value'";
						}
					}elseif ($i == 0) {
						$strWhereValue .= ",'$value'";
					}
					$i++;
				}
			}
			$strTemp	.=	")\n";
		}
		
		$strWhere .= " (".substr($strWhereValue,1).") ";
		$strResult .= "DELETE FROM $nTName $strWhere ; \n\n";
		$strResult .= "INSERT INTO $nTName ($strTitle) VALUES ";
		$strResult .=	substr($strTemp,1) . "; \n\n";

	}

	return $strResult;
}

function getImagesPath($strWhere , $spath, $dpath){
	$strResult = '';
	$strSql	=	'';
	$dbcon	=	$GLOBALS['dbcon'];
	$table	=	$GLOBALS['table'];
	$ntable	=	$GLOBALS['ntable'];
	
	$nTName	=	str_replace($table, $ntable, $tableName);
	
	$query =	"SELECT smallPicture,picture FROM `".$table."image` $strWhere";

	$dbcon -> execute_query($query);
	$arrTemp = $dbcon -> fetch_records(true);

	if (is_array($arrTemp)) {
		$file_list	=	array();
		$i = 0;
		foreach ($arrTemp as $temp){
			if (!empty($temp['smallPicture'])) {
				/*
				$strDirectory	=	str_replace($spath, $dpath, dirname(realpath(dirname(__FILE__).$temp['smallPicture'])));

				$strCreateDir .=	"mkdir " . substr($strDirectory,0,strrpos($strDirectory, '/')) . " \n";
				$strCreateDir .=	"mkdir $strDirectory \n";
				$strResult .= '/bin/cp '.realpath(dirname(__FILE__).$temp['smallPicture']). ' '. dirname(str_replace($spath, $dpath, realpath(dirname(__FILE__).$temp['smallPicture']))) . '/' . "\n";
				*/
				
				//array_push($file_list, realpath(dirname(__FILE__).$temp['smallPicture']) , realpath(dirname(__FILE__).$temp['picture']));
				array_push($file_list, substr($temp['smallPicture'],1), substr($temp['picture'],1));
				$i++;
			}
			/*
			if (!empty($temp['picture'])) {
				$strResult .= '/bin/cp '.realpath(dirname(__FILE__).$temp['picture']) . ' '. dirname(str_replace($spath, $dpath, realpath(dirname(__FILE__).$temp['picture']))). '/ ' . "\n";
			}
			*/

		}
		$zipObj = new zipClass("product_userImages");
		$zipObj->create($file_list);
		if($zipObj -> error){
			print_r($zipObj -> error);
		}else{
			echo "-- <br>产品图片打包到 ".$zipObj->zipSavePath .$zipObj -> zipName ."　成功 (".($i*2)."副图).<br> \n";
		}

	}

	return $strCreateDir . $strResult . "\n";
}


function  changedPidOfImage(){
	$strResult	=	"";
	$dbcon	=	$GLOBALS['dbcon'];
	$table	=	$GLOBALS['table'];

	$query =	"SELECT * FROM `".$table."product` where transfer != '' and transfer is not null";
	$dbcon -> execute_query($query);
	$arrTemp = $dbcon -> fetch_records(true);
	if (is_array($arrTemp)) {
		foreach ($arrTemp as $temp){
			$oldParam = split("_", $temp['transfer']);
			if (count($oldParam)==2) {
				$strResult .= "UPDATE `".$table."image` SET pid = $temp[pid] where StoreID='$oldParam[0]' and pid='$oldParam[1]'; \n";
			}
		}
	}

	$query =	"SELECT * FROM `".$table."product_realestate` where transfer != '' and transfer is not null";
	$dbcon -> execute_query($query);
	$arrTemp = $dbcon -> fetch_records(true);
	if (is_array($arrTemp)) {
		foreach ($arrTemp as $temp){
			$oldParam = split("_", $temp['transfer']);
			if (count($oldParam)==2) {
				$strResult .= "UPDATE `".$table."image` SET pid = $temp[pid] where StoreID='$oldParam[0]' and pid='$oldParam[1]'; \n";
			}
		}
	}

	$query =	"SELECT * FROM `".$table."product_automotive` where transfer != '' and transfer is not null";
	$dbcon -> execute_query($query);
	$arrTemp = $dbcon -> fetch_records(true);
	if (is_array($arrTemp)) {
		foreach ($arrTemp as $temp){
			$oldParam = split("_", $temp['transfer']);
			if (count($oldParam)==2) {
				$strResult .= "UPDATE `".$table."image` SET pid = $temp[pid] where StoreID='$oldParam[0]' and pid='$oldParam[1]'; \n";
			}
		}
	}

	$query =	"SELECT * FROM `".$table."product_job` where transfer != '' and transfer is not null";
	$dbcon -> execute_query($query);
	$arrTemp = $dbcon -> fetch_records(true);
	if (is_array($arrTemp)) {
		foreach ($arrTemp as $temp){
			$oldParam = split("_", $temp['transfer']);
			if (count($oldParam)==2) {
				$strResult .= "UPDATE `".$table."image` SET pid = $temp[pid] where StoreID='$oldParam[0]' and pid='$oldParam[1]'; \n";
			}
		}
	}

	return $strResult;
}


?>