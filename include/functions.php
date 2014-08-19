<?php

ob_start();

define("LIMIT", "20");

function StrReplace($strVar1){
	if ( $strVar1 != '' )
	{
		if (get_magic_quotes_gpc()){
			return $strVar1;
		}else{
			return $StrReplace = str_replace("'","''",$strVar1) ;
		}
	}
}


function Headers($FromEmail)
{
	$headers = "From: <".$FromEmail.">\n";
	$headers .= "Reply-To: <".$FromEmail.">\n";
	$headers .= "Errors-To: <".$FromEmail.">\n";
	$headers .= "Return-path: <".$FromEmail.">\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type:text/html; charset=iso-8859-1\n";
	$headers .= "Content-Transfer-Encoding: 7 bit\n";
	$headers .= "X-Priority: 3\n";
	$headers .= "X-MSMail-Priority: Normal\n";
	$headers .= "X-Mailer: iCEx Networks HTML-Mailer v1.0\n";
	$headers .= "X-MimeOLE: Produced By Microsoft MimeOLE V6.00.2800.1441\n";
	$headers .= "X-Sender: ".$FromEmail."\n";
	$headers .= "X-AntiAbuse: This is a solicited email for - ".$FromEmail." mailing list.\n";
	$headers .= "X-AntiAbuse: Servername - ".$_SERVER['SERVER_NAME']."\n";
	$headers .= "X-AntiAbuse: User - ".$FromEmail."\n";
	return $headers;
}


////////////////////////////////////////////////////////////////////////////





//function for reading contents of general template directory

function readTemplateGeneral(){
	$templateAddr	=	array();
	//	echo "Path ".TEMPLATEDIR."/template/GN";
	$d = dir("template/GN");
	while (false !== ($entry = $d->read())) {
		if($entry !="." && $entry !=".." && $entry !="Thumbs.db" && $entry !="WS_FTP.LOG" && $entry != '.svn'){
			$templateAddr[]	=	 $entry;
		}
	}
	sort($templateAddr);
	$d->close();
	return $templateAddr;
}


function getCategoryID($cat_name)
{
	global $dbcon ;

	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."product_category where name ='$cat_name'";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$Category	=	($grid[0]['id'])?$grid[0]['id']:504;

	return $Category;

}
function getMultiuploadCID($cat_name){
	global $dbcon ;
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."product_category where LOWER(name) in('".strtolower($cat_name)."','other') and fid=0";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$Category = 0;
	if(count($grid)>1){
		foreach ($grid as $pass){
			if($pass['name'] != "Other"){
				$Category = $pass['id'];
			}
		}
	}else{
		$Category  = $grid[0]['id'];
	}
	return $Category;
}

function getSubCategoryID($fid,$cat_name)
{
	global $dbcon ;

	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."product_category where fid=$fid and name ='$cat_name'";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$Category	=	($grid[0]['id'])?$grid[0]['id']:0;

	return $Category;

}

function getMultiuploadSubCID($fid,$cat_name){
	global $dbcon ;
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."product_category where fid=$fid and LOWER(name) ='".strtolower($cat_name)."'";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$Category	=	($grid[0]['id'])?$grid[0]['id']:0;
	if($Category==0){
		$QUERY = "SELECT * FROM ".$GLOBALS['table']."product_category where fid=$fid and name like '%Other%'";
		$result = $dbcon->execute_query($QUERY);
		$grid = $dbcon->fetch_records();
		$Category = ($grid[0]['id'])?$grid[0]['id']:0;
		if($Category == 0){
			$QUERY = "SELECT * FROM ".$GLOBALS['table']."product_category where fid=$fid limit 1";
			$result = $dbcon->execute_query($QUERY);
			$grid = $dbcon->fetch_records();
			$Category = $grid[0]['id'];
		}
	}
	return $Category;
}
function getStoreByName($StoreID)
{
	global $dbcon ;

	$QUERY	=	"SELECT bu_name FROM ".$GLOBALS["table"]."bu_detail where StoreID  ='$StoreID'";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$StoreName	=	$grid[0]['bu_name'];

	return $StoreName;
}
/**
 * Enter description here...
 * add urlstring field, change get urlstring function
 * @param unknown_type $StoreID
 */
function getStoreURLNameById($StoreID){
	global $dbcon ;

	$QUERY	=	"SELECT bu_urlstring FROM ".$GLOBALS["table"]."bu_detail where StoreID  ='$StoreID'";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$StoreName	=	$grid[0]['bu_urlstring'];

	$QUERY  = "SELECT store_name FROM ".$GLOBALS['table']."login where StoreID ='".$StoreID."'";
	$result  = $dbcon->execute_query($QUERY);
	$lgrid = $dbcon->fetch_records();
	$lStoreName = $lgrid[0]['store_name'];
	
	if($StoreName!=$lStoreName){
		return $lStoreName;
	}else {
		return $StoreName;
	}
}


function getStoreByURL($StoreID)
{
	global $dbcon ;

	$QUERY	=	"SELECT store_name FROM ".$GLOBALS["table"]."login where StoreID  ='$StoreID'";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$StoreName	=	$grid[0]['store_name'];

	return $StoreName;

}

function getSubburb($state='',$subburb=''){
	global $dbcon ;
	if($state!=''){
		$QUERY			=	"SELECT * FROM ".$GLOBALS["table"]."state where stateName='$state'";
		$result			=	$dbcon->execute_query($QUERY) ;
		$grid			=	$dbcon->fetch_records() ;
		$stateID		=	$grid[0]['id'];
		$sqlQuery	="  state_id ='$stateID' ";
	}else{
		$sqlQuery	='';
	}
	$QUERY	=	"SELECT suburb FROM ".$GLOBALS["table"]."suburb WHERE $sqlQuery ORDER BY suburb";
	//echo $QUERY;
	$RESULT	=	mysql_query($QUERY);
	$option	=	'';
	while($rows=mysql_fetch_object($RESULT)){
		if($rows->suburb==$_REQUEST['selectSubburb'] || $rows->suburb==$_REQUEST['suburb']||$rows->suburb==$subburb){
			$option	.=	"<option  value='".$rows->suburb."' selected>".$rows->suburb ."</option>";
		}else{
			$option	.=	"<option  value='".$rows->suburb."'>".$rows->suburb ."</option>";
		}
	}
	return $option;
}

/**
 * get Suburb list
 *
 * @param int $state
 * @param int $subburb
 * @return stringHTML
 */
function getSubburb1($state='',$subburb=''){
	global $dbcon ;
	$sqlQuery =	"WHERE state_id ='$state'";

	$QUERY	=	"SELECT suburb,zip FROM ".$GLOBALS["table"]."suburb $sqlQuery ORDER BY suburb";
	$RESULT	=	mysql_query($QUERY);

	$option	=	'<option value="">Select Suburb</option>';

	while($rows=mysql_fetch_object($RESULT)){
		if((!empty($_REQUEST['selectSubburb']) && $rows->suburb==$_REQUEST['selectSubburb']) || (!empty($_REQUEST['selectSubburb']) && $rows->suburb==$_REQUEST['suburb'])||$rows->suburb == $subburb)
		{
			$option	.=	"<option  value='".$rows->suburb."' selected title='".$rows->suburb ." , " .$rows->zip. "'>".$rows->suburb ." , " .$rows->zip. "</option>";
		}
		else
		{
			$option	.=	"<option  value='".$rows->suburb."' title='".$rows->suburb ." , " .$rows->zip. "'>".$rows->suburb ." , " .$rows->zip. "</option>";
		}
	}
	return $option;
}

function getPaymentStore($StoreID,$fromDate='',$toDate='',$lastMonth=''){
	global $dbcon ;
	$date			=	date("Y-m-d");
	$lastmonth		=	mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
	$datelast		=	date("Y-m-d",$lastmonth);
	if($fromDate != '' AND $toDate != ''){
		$sqlWhere	=	" WHERE  OrderDate BETWEEN   '$fromDate' AND '$toDate'";
	}
	else if($fromDate != '' AND $toDate == ''){
		$sqlWhere	=	" WHERE OrderDate BETWEEN  '$fromDate' AND '$date'";
	}
	else{
		$sqlWhere	=	" WHERE OrderDate BETWEEN   '$datelast' AND '$date'";
	}
	if($fromDate == '' AND $toDate == '' AND $lastMonth=='' )
	{
		$sqlWhere = "" ;
	}
	$QUERY			=	"SELECT * FROM  ".$GLOBALS["table"]."order_detail $sqlWhere ";
	$result			=	$dbcon->execute_query($QUERY) ;
	$grid			=	$dbcon->fetch_records() ;
	$OrderAddr		=	array();
	for($i=0;$i<sizeof($grid);$i++){
		$OrderAddr[]	=	$grid[$i]['OrderID'];
	}
	$cost		=	array();
	$storeName	=	array();
	foreach($OrderAddr AS $key=>$values){
		$totalCost		=	0;
		if($StoreID != ''){
			$QUERY			=	"SELECT * FROM ".$GLOBALS["table"]."shopping_cart WHERE OrderID ='$values' AND StoreID ='$StoreID' ";
		}
		else{
			$QUERY			=	"SELECT * FROM ".$GLOBALS["table"]."shopping_cart WHERE OrderID ='$values'";
		}
		//echo $QUERY ;
		$result			=	$dbcon->execute_query($QUERY) ;
		$grid			=	$dbcon->fetch_records() ;
		$recordsp        =   $dbcon->count_records() ;
		$QUERY			=	"SELECT * FROM ".$GLOBALS["table"]."bu_detail WHERE StoreID ='".$grid[0]['StoreID']."'";
		$result1		=	$dbcon->execute_query($QUERY) ;
		$grid1			=	$dbcon->fetch_records();
		$storeNameAddr[$values]	=	$grid1[0]['bu_name'];
		$dateAddr[$values]		=	date("d/m/Y",strtotime($grid[0]['Date']));
		for($i=0;$i<sizeof($grid);$i++){
			$QUERY1			=	"SELECT * FROM ".$GLOBALS["table"]."product WHERE pid ='".$grid[$i]['pid']."'";
			$result1		=	$dbcon->execute_query($QUERY1) ;
			$grid1			=	$dbcon->fetch_records() ;
			$grid[$i]['Quantity']*$grid1[0]['price'];
			$totalCost		+=	$grid[$i]['Quantity']*$grid1[0]['price'];
		}
		$cost[$values]	=	$totalCost;
	}
	$Template	=	implode(",",$OrderAddr)."~".implode(",",$dateAddr)."~".implode(",",$storeNameAddr)."~".implode(",",$cost);
	return $Template;
}


function randnumber()
{
	srand((double)microtime()*10000000000);
	$number 	=	rand(0,10000000000);
	//$number1 	=	$number+1;
	$number1 	=	"1000".$number ;

	return $number1;
}

function getPostcodeSubburb($suburb, $statename=''){
	global $dbcon ;
	$QUERY	=	"Select id from ".$GLOBALS["table"]."state where stateName='$statename'";
	$result		=	$GLOBALS['dbcon']->execute_query($QUERY) ;
	$grid		=	$GLOBALS['dbcon']->fetch_records() ;
	$stateid	=	$grid[0]['id'];

	$QUERY	=	"Select zip from ".$GLOBALS["table"]."suburb where suburb='$suburb' and state_id='$stateid' limit 1";
	$result		=	$GLOBALS['dbcon']->execute_query($QUERY) ;
	$grid		=	$GLOBALS['dbcon']->fetch_records() ;
        $postcode	=	$grid[0]['zip'];

	return $postcode;
}


function getRadiusSqlString($postcode, $distance, $version='USA',$isstore=1) {
	define('EARTH_RADIUS_MILES',3963.34656);
	define('EARTH_RADIUS_KM',6378.388);
	
	if ($version=='USA') define('EARTH_RADIUS',EARTH_RADIUS_MILES);
	else define('EARTH_RADIUS',EARTH_RADIUS_KM);

	$sqlstring = "SELECT * FROM `".$GLOBALS["table"]."postcodelatlong` WHERE postcode='$postcode'";
	$result = $GLOBALS['dbcon']->execute_query($sqlstring) ;
	$row = $GLOBALS['dbcon']->fetch_records() ;
	$lng = $row[0]["longitude"];
	$lat = $row[0]["latitude"];
            
	$sqlstring = "SELECT DISTINCT postcode, \r\n"
	."(ACOS((SIN(RADIANS('".$lat."'))*SIN(RADIANS(Latitude))) + (COS(RADIANS('".$lat."'))*COS(RADIANS(Latitude))*COS(RADIANS('".$lng."')-RADIANS(Longitude)))) * ".(EARTH_RADIUS).") AS `distance` \r\n"
	."FROM `".$GLOBALS["table"]."postcodelatlong` \r\n"
	."HAVING `distance` <= $distance \r\n"
	."ORDER BY distance ASC";
	
	$GLOBALS['dbcon']->execute_query($sqlstring);
	$result = $GLOBALS['dbcon']->fetch_records();
	$counter=1;
	$condition = "";
	if(is_array($result)){
		foreach ($result as $row){
			if($counter==1){
				if($isstore==1){
				    $condition = $condition . "AND bu_postcode IN (".$row['postcode']. ",";
				}else{
					$condition = $condition . " IN (".$row['postcode']. ",";
				}
				$counter++;
			} else {
				$condition = $condition.$row['postcode'].",";
				$counter++;
			}
		}
		$condition = substr($condition, 0, -1);
		$condition = $condition . ")";
	}
	return $condition;
}


function getState1($StateLogin='WA')
{
	global $dbcon ;
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."state ";

	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	for($i=0;$i<sizeof($grid);$i++){
		if($grid[$i]['stateName']==$_REQUEST['bu_state'] || $grid[$i]['stateName']==$StateLogin){
			$selected	=	"selected";
		}else{
			$selected	=	"";
		}
		$State	.=  "<option value=\"".$grid[$i]['stateName']."\" $selected>". $grid[$i]['stateName']."</option>" ;
	}
	return $State;

}


function getState3($StateLogin='')
{
	global $dbcon ;
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."state order by description ASC";

	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$State	=	'<option value="" >Select State</option>';
	for($i=0;$i<sizeof($grid);$i++){
		if($grid[$i]['id']==$StateLogin){
			$selected	=	"selected";
		}else{
			$selected	=	"";
		}
		$State	.=  "<option value='".$grid[$i]['id']."' $selected>". $grid[$i]['description']." (".$grid[$i]['stateName'].")</option>" ;
	}
	//$State .= "</select>";
	return $State;

}

function getState4($StateLogin='')
{
	global $dbcon ;
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."state order by stateName";

	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	for($i=0;$i<sizeof($grid);$i++)
	{
		if($grid[$i]['stateName']==$StateLogin){
			$selected	=	"selected";
		}
		else{
			$selected	=	"";
		}
		$State	.=  "<option value='".$grid[$i]['id']."' $selected>". $grid[$i]['description']."</option>" ;
	}
	//$State .= "</option>";
	return $State;

}


function getStateByID($stateName='WA')
{
	global $dbcon ;
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."state where  stateName='$stateName'";

	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$StateID	=  $grid[0]['id'];

	return $StateID;

}
function getStateByName($stateID='')
{
	global $dbcon ;
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."state where  id='$stateID'";

	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$StateName	=  $grid[0]['stateName'];

	return $StateName;
}

function getStateIDByName($stateName='NSW')
{
	global $dbcon ;
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."state where  stateName='$stateName'";

	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$id	=  $grid[0]['id'];

	return $id;
}
function getStateDescByName($stateName='', $place=0)
{
	global $dbcon ;
	if ($stateName == -1 ) {
		$place == 0 ? $StateName = 'All State' : $StateName = "New South Wales" ;
	}else{
		$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."state where stateName='$stateName'";
		$result	=	$dbcon->execute_query($QUERY) ;
		$grid	=	$dbcon->fetch_records() ;
		$StateName	=  $grid[0]['description'];
	}
	return $StateName;
}

function getStoreID($userID){
	$query	=	"select * from ".$GLOBALS["table"]."login where id='$userID'";
	$result	=	mysql_query($query);
	$row	=	mysql_fetch_object($result);
	$_SESSION['StoreID']=$row->StoreID;

	$query	=	"select * from ".$GLOBALS["table"]."template_details  where StoreID='$_SESSION[StoreID]'";
	$result	=	mysql_query($query);
	$row	=	mysql_fetch_object($result);
	$_SESSION['TemplateName']=$row->TemplateName;
}

function getSuburbArrayByCouncil($council='') {
	global $dbcon ;
	$query = "SELECT * FROM `". $GLOBALS['table'] ."suburb` where council = '$council'";
	$dbcon->execute_query($query);
	$res =	$dbcon->fetch_records();
	
	return $res;
}

function getCouncilBySuburb($suburb='') {
	global $dbcon ;
	if (empty($suburb)) {
		return ;
	}
	$query = "SELECT * FROM `". $GLOBALS['table'] ."suburb` where suburb = '$suburb'";
	$dbcon->execute_query($query);
	$res =	$dbcon->fetch_records();
	
	return $res[0]['council'];
}

function getCouncilArray($statename,$council=''){
	global $dbcon ;
	if (!is_numeric($statename)){
		$query = "SELECT * FROM `". $GLOBALS['table'] ."state` where stateName = '$statename'";
		$dbcon->execute_query($query);
		$grid =	$dbcon->fetch_records();
		$stateID = $grid[0]['id'];
	}else{
		$stateID = $statename;
	}

	$sqlQuery = "state_id = '$stateID'" ;
	$query = "SELECT council_id,council FROM ". $GLOBALS['table'] ."council WHERE $sqlQuery ORDER BY council ";
	$dbcon->execute_query($query);
	$option = '';
	$rows = $dbcon->fetch_records();
	$cities = array();
	$current_council = "";
	$councils = array();
	if (is_array($rows)) {
		foreach ($rows as $row){
			if ($current_council==''){
				$current_council = $row['council'];
			}elseif($current_council==$row['council']){
				continue;
			}else{
				$current_council = $row['council'];
			}
			$councils[] = array(
			'bu_council' => $row['council'],
			'council_id' => $row['council_id'],
			'selected'  => $council == $row['council'] ? ' selected' : ''
			);
		}
	}
	return $councils;
}

function getSuburbArray($statename,$suburb=''){
	global $dbcon ;
	if (!is_numeric($statename)){
		$query = "SELECT * FROM `". $GLOBALS['table'] ."state` where stateName = '$statename'";
		$dbcon->execute_query($query);
		$grid =	$dbcon->fetch_records();
		$stateID = $grid[0]['id'];
	}else{
		$stateID = $statename;
	}

	$sqlQuery = "state_id = '$stateID'" ;
	$query = "SELECT suburb_id,suburb,zip FROM ". $GLOBALS['table'] ."suburb WHERE $sqlQuery ORDER BY suburb ";
	$dbcon->execute_query($query);
	$option = '';
	$rows = $dbcon->fetch_records();
	$cities = array();
	$current_suburb = "";
	if (is_array($rows)) {
		foreach ($rows as $row){
			if ($current_suburb==''){
				$current_suburb = $row['suburb'];
			}elseif($current_suburb==$row['suburb']){
				continue;
			}else{
				$current_suburb = $row['suburb'];
			}
			$cities[] = array(
			'bu_suburb' => $row['suburb'],
			'suburb_id' => $row['suburb_id'],
			'zip'		=> $row['zip'],
			'selected'  => $suburb == $row['suburb'] ? ' selected' : ''
			);
		}
	}
	return $cities;
}

function getSearchURI(){
	$URI='';
	if($_REQUEST['selectSubburb']!=''){
		$URI = $URI=='' ? "selectSubburb=" .$_REQUEST['selectSubburb'] : $URI."&selectSubburb=".$_REQUEST['selectSubburb'];
	}
	if($_REQUEST['selectDistance']!=''){
		$URI = $URI=='' ? "selectDistance=" .$_REQUEST['selectDistance'] : $URI."&selectDistance=".$_REQUEST['selectDistance'];
	}
	if($_REQUEST['state']!=''){
		$URI = $URI=='' ? "state=" .$_REQUEST['state'] : $URI."&state=".$_REQUEST['state'];
	}
	if($_REQUEST['business_name']!=''){
		$URI = $URI=='' ? '&business_name='.$_REQUEST['business_name'] : $URI."&business_name=".$_REQUEST['business_name'];
	}
	return $URI;
}


function getExpMonth($month=''){
	$option	=	'';
	for($i=1;$i<=12;$i++){
		if($i==$month){
			$option	.=	"<option value=$i selected>$i</option>";
		}
		else{
			$option	.=	"<option value=$i >$i</option>";
		}
	}
	return $option;
}

function getExpYear($year=''){
	$date1	=	date("Y");
	$date2	=	date("Y")+12;

	$option	=	'';
	for($i=$date1;$i<=$date2;$i++){
		if($i==$year){
			$option	.=	"<option value=$i selected>$i</option>";
		}
		else{
			$option	.=	"<option value=$i >$i</option>";
		}
	}
	return $option;
}


function storeidTonameN($storeid){

	$sql4 = "select bu_name  from ".$GLOBALS["table"]."bu_detail where StoreID = " .$storeid ;
	$res = mysql_query($sql4) ;
	$row = mysql_fetch_object($res) ;
	return $row->bu_name ;
}


function check_badwords($check_fields1){
	$badwords = array("fuck", "shit", "piss", "cunt", "bitch", "asshole", "ass",
	"tits", "tit", "suck", "cock", "cocks", "dick", "dicks");
	$badwordsExist="off";

	//	$check_fields	=	explode(" ",$check_fields1);
	$check_fields	=	preg_split("/[\s&0-9]/ ",$check_fields1);

	foreach($badwords as $key=>$values){
		foreach($check_fields as $checks=>$check){

			if (trim($check)==trim($values)) {
				$badwordsExist="on";
				break;
			}
		}

		if($badwordsExist=="on"){
			break;
		}
	}

	if ($badwordsExist == "on") {
		return true;
	}
	else{
		return false;
	}
}

// cut summary from store description and return
function subStoreDetail($detail,$StoreID,$length=500, $posLeft=70, $url='storedetail.php?'){
	if (strlen($detail) < $length){
		return $detail;
	}else{
		$str =  interceptChar($detail,$length,true);
		$str .= '<br><samp align="center" style="left: '.$posLeft.'%; position:relative;"><a href="'.$url.'&StoreID='.$StoreID.'" class="back2srchLink">more&gt;&gt;</a></samp>';
	}

	return $str;
}

function changeDate($date){
	list($month,$day,$year) = split("-",$date);
	return $year.'-'.$month.'-'.$day;
}

function randStr($len){
	$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'; // characters to build the string from
	$string='';
	for(;$len>=1;$len--){
		$position=rand()%strlen($chars);
		$string.=substr($chars,$position,1);
	}
	return $string;
}


/**
 * setting counter function
 * 
 * @author ping.hu <2007-12-27>
 * @param int $StoreID  store ID
 */
function setCounter2($StoreID,$pid=0,$type='PRODUCT')
{
	if($_COOKIE['counter_'.$type] == "") {
		setcookie('counter_'.$type,'set',time()+3) ;

		//month stat.
		$sql = "select * From " . $GLOBALS['table']. "hitcount_store ".
		" where StoreID = '$StoreID' and `year`='".date("Y")."' and month='".date("m")."' and pid='$pid' and type='$type'";
		$GLOBALS['dbcon']->execute_query($sql) ;
		$monthCount = $GLOBALS['dbcon']->fetch_records() ;
		if (is_array($monthCount) && $monthCount[0]["click"]>0)
		{
			$sql = "update ".$GLOBALS['table']."hitcount_store set click=(click+1) ".
			" where StoreID = '$StoreID' and `year`='".date("Y")."' and pid='$pid' and type='$type' and month= date_format(now(),'%m')";
			$GLOBALS['dbcon']->execute_query($sql) ;
		}
		elseif ($StoreID>0)
		{
			$sql = "insert into ".$GLOBALS['table']."hitcount_store (`year`,`month`,`StoreID`,`click`,`pid`,`type`)".
			" values ('".date("Y")."',date_format(now(),'%m'),'$StoreID','1','$pid','$type')";
			$GLOBALS['dbcon']->execute_query($sql) ;
		}
	}
}

function findCounterBycon($StoreID,$pid=0,$type='PRODUCT'){
	global $dbcon,$table;
	$tmpTable ="";
	switch ($type):
		case 'PRODUCT':
			if($pid):
				$tmpTable = "{$table}hit_store_product";
			else:
				$tmpTable = "{$table}hit_store";
			endif;
		break;
		case 'WISHLIST':
			if($pid):
				$tmpTable = "{$table}hit_wishlist_product";
			else:
				$tmpTable = "{$table}hit_wishlist";
			endif;
	endswitch;
	$query = "SELECT id FROM $tmpTable where StoreID='{$StoreID}' and year='".date('Y')."'".($pid==0?"":" AND pid ='$pid' ");
	$dbcon->execute_query($query);
	if($result = $dbcon->fetch_records(true)){
		return array('table'=>$tmpTable,'id'=>$result[0]['id']);
	}else{
		return array('table'=>$tmpTable,'id'=>0);
	}
}
function setCounter($StoreID,$pid=0,$type='PRODUCT'){
	global $dbcon,$table;
	if($_COOKIE['counter_'.$type] == "") {
		setcookie('counter_'.$type,'set',time()+10) ;
		$checkresult = findCounterBycon($StoreID,$pid,$type);
		if($checkresult['id']){
			$query = "Update {$checkresult['table']} set `".date('M')."`=(`".date('M')."`+1) where id='{$checkresult['id']}'";
		}else{
			$query = "insert into {$checkresult['table']}(`StoreID`,`year`,`".date('M')."`".($pid==0?"":",`pid`").")".
			"values($StoreID,".date('Y').",1".($pid==0?"":",$pid").")";
		}
		$dbcon->execute_query($query);
	}
}
/**
 * get reviews Number
 *
 * @author ping.hu <2007-12-27>
 * @param int $StoreID
 * @return int
 */
function getReviewsNum($StoreID)
{
	$sql = "select count(*) from ".$GLOBALS['table']."review where StoreID='$StoreID' and `type`='review'";
	$GLOBALS['dbcon']->execute_query($sql) ;
	$intTemp = $GLOBALS['dbcon']->fetch_records() ;
	//echo $sql;
	return $intTemp[0][0];
}
/**
 * Get Reailer Expiry Days
 *
 * @return array
 */
function getReviewsExpriy()
{
	global $cmstable;
	$sql = "select body from ".$GLOBALS['table']."{$cmstable} where id in('30','31') order by id";
	$GLOBALS['dbcon']->execute_query($sql) ;
	$intTemp = $GLOBALS['dbcon']->fetch_records() ;
	return array("real"=>$intTemp[0][0],'free'=>$intTemp[1][0]);
}
/**
 * delete expriy reviews
 */
function setReviewsDelExpriy()
{
	$expriyArr = getReviewsExpriy();
	$sql = "select t1.`review_id` from ".$GLOBALS['table']."review as t1 ".
	" left join ".$GLOBALS['table']."bu_detail as t2 on t1.`StoreID`= t2.`StoreID` ".
	" where (t1.`post_date` <= now() + INTERVAL -".$expriyArr['free']." day) ";
	$GLOBALS['dbcon']->execute_query($sql) ;
	$strIdArr = $GLOBALS['dbcon']->fetch_records() ;
	$strDelId = "";
	//echo $sql;
	if (is_array($strIdArr)) {
		foreach ($strIdArr as $temp)
		{
			$strDelId .= ",'$temp[0]'";
		}
		if (strlen($strDelId)>1) {
			$strDelId = substr($strDelId,1);
			$sql = "delete from " .$GLOBALS['table']."review " .
			" where review_id in ($strDelId) or upid in ($strDelId)";
			//echo $sql;
			$GLOBALS['dbcon']->execute_query($sql) ;
		}
	}
}


/**
 * intercept char include HTML
 * 
 * @author     ping.hu <support@infinitytesting.com.au>
 * @version    1.2.0
 * @param      string     $str
 * @param      int        $num
 * @param      boolean    $isMore
 * @param      boolean    $hasMore
 * @return     string
 */
function interceptChar($str, $num= 500, $isMore = true, &$hasMore=false)
{
	$strResult = "";

	if (strlen($str)>0) {

		$pattern = array(
		"/&nbsp;/is",
		"/&gt;/is",
		"/&lt;/is",
		"/(<[^]*?)([^>]*?)(>)/is"
		);
		$replace = array(
		"\t",
		"\t",
		"\t",
		""
		);
		$strTemp = preg_replace($pattern, $replace, $str);
		$intLen  = strlen($strTemp);

		if ($intLen > $num) {
			$strTemp = __cutstr($strTemp,$num);
			$strTemp = str_replace("\t","",$strTemp);

			$arrayStr = preg_split("/(&nbsp;)|(&gt;)|(&lt;)|(<[^]*?)([^>]*?)(>)/is", $str);
			if (is_array($arrayStr)) {
				preg_match_all("/(&nbsp;)|(&gt;)|(&lt;)|(<[^]*?[^>]*?>)/is", $str,$targetArr, PREG_PATTERN_ORDER);

				$i = 0;
				foreach ($arrayStr as $temp)
				{
					if ($temp == "")
					{
						if ($i>0) {
							$strResult .= $targetArr[0][($i-1)];
						}
						else
						{
							$strResult .=  ereg_replace("/[\s]/is","&nbsp;",$temp);
						}
					}elseif (substr("$strTemp",0,strlen($temp)) == $temp) {
						if ($i>0) {
							$strResult .= $targetArr[0][($i-1)] . ereg_replace("/[\s]/is","&nbsp;",$temp);
						}
						else
						{
							$strResult .= ereg_replace("/[\s]/is","&nbsp;",$temp);
						}
						$strTemp = substr($strTemp, (strlen($temp)));
					}
					else
					{
						$strResult .= $targetArr[0][($i-1)] . ereg_replace("/[\s]/is","&nbsp;",$strTemp);
						break;
					}
					$i++;
				}

				for ($j = $i ; $j < count($targetArr[0]) ; $j ++)
				{
					if (!empty($targetArr[0][($j)]) && substr_count($targetArr[0][$j],'</')) {
						$strResult .= $targetArr[0][($j)];
					}
					else
					{
						break;
					}
				}

				if ($isMore && $intLen > $num) {
					$strResult .= '...';
					$hasMore	=	true;
				}

			}
			else
			{
				$strResult = $strTemp;
				if ($isMore && $intLen > $num) {
					$strResult .= '...';
					$hasMore	=	true;
				}
			}
		}
		else
		{
			$strResult = $str;
		}
	}
	//echo print_r($arrayStr)."<br>".print_r($targetArr) ."<br><br>";
	return $strResult;
}
/**
 * intercept string of SBC case 
 *
 * @author     ping.hu <support@infinitytesting.com.au>
 * @version    1.0.0
 * @param stirng $string
 * @param int $length
 * @return string
 */
function __cutstr($string, $length) {
	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);
	for($i=0; $i<count($info[0]); $i++) {
		$wordscut .= $info[0][$i];
		$j = ord($info[0][$i]) > 127 ? $j + 2 : $j + 1;
		if ($j > $length - 3) {
			return $wordscut;
		}
	}
	return join('', $info[0]);
}


/*
* change int postcode to string for display
*/
function change_postcode($pc){
	$length = strlen($pc);

	if ($pc){
		for ($i=0;$i<(5-$length);$i++){
			$pc = '0'.$pc;
		}
		return $pc;
	}else{
		return '';
	}
}

/*
* check validation of email address
*/

function emailcheck($email){
	$ret=false;
	if(strstr($email, '@') && strstr($email, '.')){
		if(eregi("^([_a-zA-Z0-9]+([\._a-zA-Z0-9-]+)*)@([a-zA-Z0-9]{1,}(\.[a-zA-Z0-9-]{1,})*)$", $email)){
			$ret=true;
		}
	}
	return $ret;
}

/*
* remove special char from store name
*/

function clean_url_name($name){
	$pattern = array("/\s+/","/_/","/\W/");
	$replace = array("","",'');

	$name = preg_replace($pattern,$replace,$name);
	if (strlen($name)>60){
		$name = substr($name,0,60);
	}
	return $name;
}


/**
 * create by roy 20081211
 * modify by ping.hu at 20091117
 * function to add a record tmpcustom
 */
function addtmpcustom($custom=''){
	global $dbcon,$table;
	
	$query = "insert into {$table}tmpcustom(`custom`,`bu_name`,`bu_urlstring`,`datec`)values('$custom','{$_POST['bu_name']}','".clean_url_name($_POST['bu_urlstring'])."','".time()."')";
	//$queryary['custom'] = $custom;
	//$dbcon ->insert_query($table."tmpcustom",$queryary);
	$dbcon ->execute_query($query);
	
	$_SESSION['payment_tmpcustom'] = $dbcon ->insert_id();
	
	return $dbcon ->insert_id();
}
/**
 * create by roy 20081211
 * modify by ping.hu at 20091117
 * function to delete tmpcustom recode
 */
function deltmpcustom($id){
	global $dbcon,$table;
	$query = "delete from {$table}tmpcustom where id=$id";
	$result = $dbcon ->execute_query($query);
	
	unset($_SESSION['payment_tmpcustom']);
	
	return $result;
}
function uptmpcustom($id){
	global $dbcon,$table;
	$query = "update {$table}tmpcustom set updateStatus=1 where id='$id' ";
	$result = $dbcon ->execute_query($query);
	return $result;
}
/**
 * create by roy 20081211
 * funtion to get by custom by id 
 */
function getbyidcustom($id){
	global $dbcon,$table;
	$query = "select * from {$table}tmpcustom where id=$id";
	$dbcon->execute_query($query);
	$result = $dbcon->fetch_records(1);
	$str = "";
	foreach ($result as $pass){
		$str = $pass['custom'];
	}
	return  $str;
}

/**
 * check Tmpcustom record exist.
 *
 * @param array $arrCheck    array(["bu_name"=>'', "bu_urlstring"=>,...])
 * @return boolean
 */
function checkTmpcustomExist($arrCheck){
	global $dbcon,$table;
	if (is_array($arrCheck)){
		$strCondition = " WHERE datec>" . (time()-DISABLED_TIME);
		
		foreach ($arrCheck as $key=>$value){
			$strCondition .= " AND `{$key}`= '$value'";
		}
		
		if($_SESSION['payment_tmpcustom']){
			uptmpcustom($_SESSION['payment_tmpcustom']);
			$strCondition .= " AND id <> '".$_SESSION['payment_tmpcustom']."'" ;
		}
		
		if ($dbcon->checkRecordExist($table."tmpcustom", $strCondition." AND updateStatus<>1 ")) {
			return true;
		}else{
			return false;
		}
		
	}else {
		return false;
	}
}

/**
 * get subburb array from database
 *
 * @param string $strIDOrName
 * @param boolean $isID
 * @return array
 */
function getSubburbArrayFromDB($strIDOrName, $isID=true){
	$arrReustlt	=	null;
	global $dbcon ;

	if($isID){
		$_where	=	"where state_id='$strIDOrName'";
	}else{
		$_query			=	"SELECT state_id FROM ".$GLOBALS["table"]."state where stateName='$strIDOrName'";
		$dbcon->execute_query($_query) ;
		$arrTemp =	$dbcon->fetch_records(true) ;
		if (is_array($arrTemp) && $arrTemp[0]['id'] > 0) {
			$_where	= "where state_id ='".$arrTemp[0]['id']."' ";
		}
	}
	if (!empty($_where)) {
		$_query	=	"SELECT suburb_id as id, suburb as name FROM ".$GLOBALS["table"]."suburb $_where group BY suburb";
		$dbcon->execute_query($_query);
		$arrTemp =	$dbcon->fetch_records(true);
		if (is_array($arrTemp)) {
			$arrReustlt		=	$arrTemp;
		}
	}

	unset($arrTemp);

	return $arrReustlt;
}


function getStateArrayFromDB(){
	$arrReustlt	=	null;
	global $dbcon ;

	$_query	= "SELECT id, description as name , stateName FROM ".$GLOBALS["table"]."state  ORDER BY description ";
	$dbcon->execute_query($_query);
	$arrTemp =	$dbcon->fetch_records(true);
	if (is_array($arrTemp)) {
		$arrReustlt		=	$arrTemp;
	}
	unset($arrTemp);

	return $arrReustlt;
}


/**
 * Get sector of all 
 *
 * @param int $id
 * @param int $type
 * @param array $arrAddOption { {name:'',id:'', place:''}[, {}...] }
 * @return array
 */
function getSectorListFromDB($id=0, $type=0, $arrAddOption=null){
	$arrResult	=	null;

	$_where	= "where type=$type and fid='$id'";
	$_query	= "select * from ".$GLOBALS['table']."product_sort $_where order by sort, fid, name";
	$GLOBALS['dbcon']-> execute_query($_query);
	$arrTemp	=	$GLOBALS['dbcon']-> fetch_records(true);
	if (is_array($arrTemp)) {
		$arrResult	=	$arrTemp;
	}

	if (is_array($arrAddOption)) {
		foreach ($arrAddOption as $temp){
			if ($temp["place"] == 0) {
				if (is_array($arrResult)) {
					array_unshift($arrResult, array('name'=>$temp['name'],'id'=>$temp['id']));
				}else{
					$arrResult[]	=	array('name'=>$temp['name'],'id'=>$temp['id']);
				}
			}elseif ($temp["place"] == 1) {
				$arrResult[]	=	array('name'=>$temp['name'],'id'=>$temp['id']);
			}
		}
	}

	unset($arrTemp);
	return $arrResult;
}

//------------------------------------------------------------
// xajax function
//------------------------------------------------------------

/**
 * combox linkage element by ajax
 *
 * @param array $arrData
 * @param string $objHTML
 * @param int $level
 * @return JS
 */
function ajaxComboxLinkageElement($arrData, $objHTML, $level=2){
	$strResult	=	'';

	if ($level >1) {
		$arrObjHTML	=	explode('_',$objHTML);
		$subObjHTML	=	$arrObjHTML[1]+1 ;
		for ($i= $subObjHTML ; $i <= $level; $i++){
			$strResult	.=	"xajax.$('".$arrObjHTML[0]."_$i').length = 0;";
		}
	}

	if (is_array($arrData)) {
		$i = 0;
		foreach ($arrData as $temp){
			$strResult	.=	"xajax.$('".$arrObjHTML[0]."_$subObjHTML')[$i] = new Option(\"$temp[name]\", \"$temp[id]\");";
			$i++;
		}
	}else{
		$strResult	.=	"xajax.$('".$arrObjHTML[0]."_$subObjHTML')[0] = new Option(\"Nothing\", \"-100\");";
	}

	return $strResult;
}

/**
 * combox linkage element by jquery
 *
 * @param array $arrData
 * @param string $objHTML
 * @param int $level
 * @return JS
 */
function jqueryComboxLinkageElement($arrData, $objHTML, $level=2){
	$strResult	=	'';

	if ($level >1) {
		$arrObjHTML	=	explode('_',$objHTML);
		$subObjHTML	=	$arrObjHTML[1]+1 ;
		for ($i= $subObjHTML ; $i <= $level; $i++){
			$strResult	.=	"$('#".$arrObjHTML[0]."_$i').empty();";
		}
	}

	if (is_array($arrData)) {
		$i = 0;
		foreach ($arrData as $temp){
			$strResult	.=	"$(\"<option value='".preg_replace('/([\'\"])/','\\\1',$temp['id'])."'>".preg_replace('/([\'\"])/','\\\1',$temp['name'])."</option>\").appendTo('#".$arrObjHTML[0]."_$subObjHTML');";
		}
		$strResult	.=	"$('#".$arrObjHTML[0]."_$subObjHTML')[0].selectedIndex=0;";
	}

	return $strResult;
}

/**
 * @title	: getStateArray
 * Thu Dec 11 09:02:18 GMT 2008
 * @input	: String state name 
 * @output	: Array state list with selected condition
 * @description	:
 * @author	: Jessee <support@infinitytesting.com.au>
 * @version	: V1.0
 * 
 */
function getStateArray($state=''){
	# query the state list
	$query = "SELECT `id`, `stateName`,`description` FROM `". $GLOBALS['table'] ."state` ORDER BY `stateName` ASC";

	$GLOBALS['dbcon']->execute_query($query);
	$rows = $GLOBALS['dbcon']->fetch_records();
	$statesList = array();
	foreach ($rows as $row){
		$statesList[] = array(
		'state'    => $row['stateName'],
		'id'    => $row['id'],
		'selected' => $state == $row['stateName'] ? ' selected="selected"' : '',
                'description'   =>  $row['description']
		);
	}
	return $statesList;
}

/**
 * @title	: getSeasonArray
 * Thu Aug 21 09:02:18 GMT 2012
 * @input	: int season id 
 * @output	: Array season list with selected condition
 * 
 */
function getSeasonArray($season=0){
	# query the seasonList list
	$query = "SELECT * FROM `". $GLOBALS['table'] ."season` ORDER BY `order` ASC, `title` ASC";
	$GLOBALS['dbcon']->execute_query($query);
	$seasonList = $GLOBALS['dbcon']->fetch_records();
	foreach ($seasonList as $key => $row){
		$seasonList[$key]['selected'] = $season == $row['id'] ? ' selected="selected"' : '';
	}
	
	return $seasonList;
}

/**
 * @title	: getStateInfoByName
 * Thu Dec 11 09:34:16 GMT 2008
 * @input	: String State Name
 * @output	: Array State information or Boolean false
 * @description	: use state name query state information and return the result
 * @author	: Jessee <support@infinitytesting.com.au>
 * @version	: V1.0
 * 
 */
function getStateInfoByName($state){
	if ($state){
		$query = "SELECT id, description FROM ". $GLOBALS['table'] ."state WHERE stateName='".$state."'";
		$GLOBALS['dbcon']->execute_query($query);
		$stateInfo = $GLOBALS['dbcon']->fetch_records();
		$stateInfo = $stateInfo[0];
		return $stateInfo;
	}else{
		return false;
	}
}

function html_decode(&$value,$key){
	if(!is_array($value)){
		if($key!='State'&&$key!='Subburb'){
			$value = htmlspecialchars($value);
		}
	}
}

/**
 * @title	: getBannerDisplay
 * Mon Dec 22 11:28:20 GMT 2008
 * @input	: String display page, String State name
 * @output	: Banner display code
 * @description	: 
 * @author	: Jessee <support@infinitytesting.com.au>
 * @version	: V1.0
 * 
 */
function getBannerDisplay($displaypage = 'State',$state_name){
	# right hand ads image
	$img_url_base = 'upload/new/';
	$stateAd = array
	(
	'left1'   => array('exists'=>'no'),
	'left2'   => array('exists'=>'no'),
	'left3'   => array('exists'=>'no'),
	'right1a' => array('exists'=>'no'),
	'right1b' => array('exists'=>'no'),
	'right1c' => array('exists'=>'no'),
	'right2'  => array('exists'=>'no'),
	'right3'  => array('exists'=>'no')
	);

	// Banner type condition string by Jessee 20081222
	if ($displaypage == 'State' or $displaypage == 'College'){
		$bannerCondition = " displaypage='State'";
	}else{
		$bannerCondition = " displaypage='".$displaypage."'";
	}

	// init Ad. array with default banner information
	$sql = "select * from `". $GLOBALS['table'] ."banner_soc` where state_id = 0 and $bannerCondition";
	$GLOBALS['dbcon']->execute_query($sql);
	$grid = $GLOBALS['dbcon']->fetch_records();

	$right1_count = 0;
	$right1_key = 'a';
	if (!empty($grid)) {
		foreach($grid as $val){
			$filetype = explode('.',$val['banner_img']);
			$filetype = array_pop($filetype);
			if ($val['position'] == 'right1'){
				$stateAd[$val['position'].$right1_key]['img'] = $val['banner_img'];
				$stateAd[$val['position'].$right1_key]['img_url'] = $img_url_base.$val['banner_img'];
				$stateAd[$val['position'].$right1_key]['link'] = $val['banner_link'];
				$stateAd[$val['position'].$right1_key]['exists'] = 'yes';
				$stateAd[$val['position'].$right1_key]['id'] = $val['banner_id'];
				$stateAd[$val['position'].$right1_key]['type'] = $filetype;
				$right1_count += 1;
				if ($right1_key == 'a') $right1_key = 'b';
				else if ($right1_key == 'b') $right1_key = 'c';
				else $right1_key = 'a';
			}else{
				$stateAd[$val['position']]['img'] = $val['banner_img'];
				$stateAd[$val['position']]['img_url'] = $img_url_base.$val['banner_img'];
				$stateAd[$val['position']]['link'] = $val['banner_link'];
				$stateAd[$val['position']]['exists'] = 'yes';
				$stateAd[$val['position']]['id'] = $val['banner_id'];
				$stateAd[$val['position']]['type'] = $filetype;
			}
		}
	}

	// init Ad. array with state banner information
	$state_id = getStateIDByName($state_name);
	$sql = "select * from `". $GLOBALS['table'] ."banner_soc` where state_id = $state_id and $bannerCondition";
	$GLOBALS['dbcon']->execute_query($sql);
	$grid = $GLOBALS['dbcon']->fetch_records();

	if (!empty($grid)) {
		foreach($grid as $val){
			$filetype = explode('.',$val['banner_img']);
			$filetype = array_pop($filetype);
			if ($val['position'] == 'right1'){
				$stateAd[$val['position'].$right1_key]['img'] = $val['banner_img'];
				$stateAd[$val['position'].$right1_key]['img_url'] = $img_url_base.$val['banner_img'];
				$stateAd[$val['position'].$right1_key]['link'] = $val['banner_link'];
				$stateAd[$val['position'].$right1_key]['exists'] = 'yes';
				$stateAd[$val['position'].$right1_key]['id'] = $val['banner_id'];
				$stateAd[$val['position'].$right1_key]['type'] = $filetype;
				$right1_count += 1;
				if ($right1_key == 'a') $right1_key = 'b';
				else if ($right1_key == 'b') $right1_key = 'c';
				else $right1_key = 'a';
			}else{
				$stateAd[$val['position']]['img'] = $val['banner_img'];
				$stateAd[$val['position']]['img_url'] = $img_url_base.$val['banner_img'];
				$stateAd[$val['position']]['link'] = $val['banner_link'];
				$stateAd[$val['position']]['exists'] = 'yes';
				$stateAd[$val['position']]['id'] = $val['banner_id'];
				$stateAd[$val['position']]['type'] = $filetype;
			}
		}
	}

	// init Ad. array with state banner information
	$sql = "select * from `". $GLOBALS['table'] ."banner_soc` where state_id = 100 and $bannerCondition";
	$GLOBALS['dbcon']->execute_query($sql);
	$grid = $GLOBALS['dbcon']->fetch_records();

	if (!empty($grid)) {
		foreach($grid as $val) {
			$filetype = explode('.',$val['banner_img']);
			$filetype = array_pop($filetype);
			if ($val['position'] == 'right1'){
				$stateAd[$val['position'].$right1_key]['img'] = $val['banner_img'];
				$stateAd[$val['position'].$right1_key]['img_url'] = $img_url_base.$val['banner_img'];
				$stateAd[$val['position'].$right1_key]['link'] = $val['banner_link'];
				$stateAd[$val['position'].$right1_key]['exists'] = 'yes';
				$stateAd[$val['position'].$right1_key]['id'] = $val['banner_id'];
				$stateAd[$val['position'].$right1_key]['type'] = $filetype;
				$right1_count += 1;
				if ($right1_key == 'a') $right1_key = 'b';
				else if ($right1_key == 'b') $right1_key = 'c';
				else $right1_key = 'a';
			}else{
				$stateAd[$val['position']]['img'] = $val['banner_img'];
				$stateAd[$val['position']]['img_url'] = $img_url_base.$val['banner_img'];
				$stateAd[$val['position']]['link'] = $val['banner_link'];
				$stateAd[$val['position']]['exists'] = 'yes';
				$stateAd[$val['position']]['id'] = $val['banner_id'];
				$stateAd[$val['position']]['type'] = $filetype;
			}
		}
	}

	// record the view times
	foreach($stateAd as $key=>$val){
		if ($val['exists'] == 'yes'){
			$sql = "update `". $GLOBALS['table'] ."banner_soc` set view_times=view_times+1 where banner_id=".$val['id'];
			$GLOBALS['dbcon']->execute_query($sql);
		}
	}

	return $stateAd;
}

/**
 * @title getcountrylist
 * ADD BY Royluo
 */
function getCountryList($current=''){
	global $dbcon;
	$sql = "select country_id,country_name from ".$GLOBALS['table']."country";
	$dbcon->execute_query($sql);
	$result = $dbcon->fetch_records();
	
	$country_list = '';
	foreach ($result as $row){
		$country_list.= '<option value="'.$row['country_id'].'" ';
		if (($current!='' and $row['country_id']==$current) or ($current=='' and $row['country_id']==13)){
			$country_list.= 'selected';
		}
		$country_list.= ' title="'.$row['country_name'].'">'.$row['country_name'].'</option>'."\n";
	}
	return $country_list;
}
/**
 * @title	:  get type by storeID 
 * Wed Jan 14 06:13:21 GMT 2009 06:13:21
 * @input	: storeID
 * @output	: type
 * @description	: get type by storeID
 * @author	: Roy.luo <support@infinitytesting.com.au>
 * @version	: V1.0
*/
function getonlieType($storeID){
	global $dbcon;
	$sql = "select attribute from {$GLOBALS['table']}bu_detail where storeID=$storeID";
	$GLOBALS['dbcon']->execute_query($sql);
	$result = $dbcon->fetch_records(true);
	switch ($result[0]['attribute']){
		case '0':
			return 'onlinestore';
			break;
		case '1':
			return 'estate';
			break;
		case '2':
			return 'auto';
			break;
		case '3':
			return 'job';
			break;
		case '4':
			return 'buyer';
			break;
		default:
			return 'buyer';
			break;
	}
}

/**
 * @title	: getSortNameByID
 * Mon Feb 02 08:54:04 GMT 2009
 * @input	: int Sort ID
 * @output	: string Sort name
 * @description	: return the make name for specialized Make ID
 * @author	: Jessee <support@infinitytesting.com.au>
 * @version	: V1.0
 */
function getSortNameByID($id){
	$sql = "select name from ".$GLOBALS['table']."product_sort where id='$id'";
	
	$GLOBALS['dbcon']->execute_query($sql);
	$row = $GLOBALS['dbcon']->fetch_records();
	
	return $row[0]['name'];
}

/**
 * @title	: 
 * Wed Feb 04 08:06:46 GMT 2009 08:06:46
 * @input	:  statename & suburb
 * @output	: string array suburb ID
 * @description	:return suburb ID string
 * @author	: Roy.luo <support@infinitytesting.com.au>
 * @version	: V1.0
 * 
*/
function getSuburbIdArystr($statename,$suburb=''){
	global $dbcon ;
	if (!is_numeric($statename)){
		$query = "SELECT * FROM `". $GLOBALS['table'] ."state` where stateName = '$statename'";
		$dbcon->execute_query($query);
		$grid =	$dbcon->fetch_records();
		$stateID = $grid[0]['id'];
	}else{
		$stateID = $statename;
	}
	
	$tmpquery = "SELECT suburb FROM ".$GLOBALS['table']."suburb WHERE suburb_id='$suburb'";
	$sqlQuery = "state_id = '$stateID' and suburb=($tmpquery) " ;
	$query = "SELECT suburb_id,suburb,zip FROM ". $GLOBALS['table'] ."suburb WHERE $sqlQuery ORDER BY suburb ";
	$dbcon->execute_query($query);
	$rows = $dbcon->fetch_records();
	$cities = '';
	if (is_array($rows)) {
		foreach ($rows as $row){
			$cities .= $cities==''?"'{$row['suburb_id']}'":",'{$row['suburb_id']}'";
		}
	}
	return $cities;
}

function getSamplesitebyNames($sitenames = array()){
	global $dbcon ;
	$query = "SELECT StoreID,bu_urlstring FROM `". $GLOBALS['table'] ."bu_detail` where StoreID in(".implode(',',$sitenames).")";
	$dbcon->execute_query($query);
	$grid =	$dbcon->fetch_records();
	$urlstr = array();
	if (is_array($grid)) {
		foreach ($grid as $pass){
			for($i=1;$i<=count($sitenames);$i++){
				if($sitenames[$i]==$pass['StoreID']){
					$urlstr[$i]=clean_url_name($pass['bu_urlstring']);
				}
			}
		}	
	}
	return $urlstr;
}

function checkFreeSeller($id){
	global $dbcon;
	$sql = "select attribute,subAttrib from ".$GLOBALS['table'] ."bu_detail where StoreID=$id";
	$dbcon->execute_query($sql);
	$row = $dbcon->fetch_records();
	$row = $row[0];
	if ($row['attribute'] == 3 and $row['subAttrib'] == 3){
		return true;
	}else{
		return false;
	}
}

function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}

function striaddslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('striaddslashes_deep', $value) :
                addslashes($value);

    return $value;
}

function step1checkedvaild($arraySeting){
		/****/
		//$arraySeting = array('attribute','StroeID','bu_user','bu_name','bu_urlstring');
		//$arraySeting = array('attribute','StroeID','email','websitename','urlstring');
		
		$whs="";
		if(isset($arraySeting['StoreID'])&&$arraySeting['StoreID']!=""){
			$whs = " StoreID != '{$arraySeting['StoreID']}' and  ";
		}

		$query	="select count(*) from  ".$GLOBALS['table']."login where $whs user='{$arraySeting['email']}' and attribute = '{$arraySeting['attribute']}'";
		$result	=	$GLOBALS['dbcon']->execute_query($query) ;
		$arrCheck = $GLOBALS['dbcon']->fetch_records() ;
		
		if($arraySeting['attribute']==0){
			$query	="select count(*) as num from  ".$GLOBALS['table']."bu_detail where $whs bu_name='{$arraySeting['websitename']}' and attribute={$arraySeting['attribute']} AND renewalDate > ".time();
			$result	=	$GLOBALS['dbcon']->execute_query($query) ;
			$arrCheckBuname = $GLOBALS['dbcon']->fetch_records(true) ;
		}else{
			$arrCheckBuname[0]['num'] = 0;
		}
	
		$url_store_name = clean_url_name($arraySeting['urlstring']);
		$QUERY = "select count(*) as num from ".$GLOBALS['table']."login as t1 left join "
				.$GLOBALS['table']."bu_detail as t2 on t1.StoreID=t2.StoreID where $whs t1.store_name='"
				.$url_store_name."' AND ((t2.renewalDate > ".time().") or (t2.`attribute`=3 && t2.`subAttrib`=3))";
		$GLOBALS['dbcon']->execute_query($QUERY);
		$store_name_check = $GLOBALS['dbcon']->fetch_records();
		$store_name_check = $store_name_check[0]['num'];
		$msg = "";
		if ($arraySeting['attribute'] == 0 && $arrCheckBuname[0]['num'] > 0 || checkTmpcustomExist(array('bu_name'=> $bu_name))) {
			$msg	= "Web Name exists. Please try again.";
		}elseif ($store_name_check>0 || checkTmpcustomExist(array('bu_urlstring'=> $url_store_name))){
			$msg	= "This URL string has already been used.\n Please create another.";
		}elseif($arrCheck[0][0] > 0 && $arraySeting['attribute'] != 5){
			$msg	=	"The Email Address you have entered already exists. Please try again.";
		}elseif(strlen($url_store_name)>60){
			$msg = "The URL String must be less than 60 characters. Please try again.";
		}else{
			$msg = "";
			
			if(!get_magic_quotes_gpc()){
				$nickname = addslashes($arraySeting['nickname']);
			}else{
				$nickname = $arraySeting['nickname'];
			}
			$sql = "select bu_email from {$GLOBALS['table']}bu_detail where bu_nickname='$nickname' limit 1";
			$GLOBALS['dbcon']->execute_query($sql);
			$result = $GLOBALS['dbcon']->fetch_records();
			if(is_array($result)){
				if($result[0]['bu_email']!=$arraySeting['email']){
					$nickname_l = $arraySeting['attribute'] == 1 && 0 ? $GLOBALS['_LANG']['labelContactPerson'] : $GLOBALS['_LANG']['labelNickName'];
					$msg = "The $nickname_l exists or invalid.";
				}
			}	
		}
		return $msg;
}

function getrefname(){
	$strname = "";
	for (;;){
		$str_ref = randStr(6);
		if(is_numeric($str_ref)){
			continue;
		}
		if(!preg_match('/[0-9]+/',$str_ref)){
			continue;
		}
		$sql = "select count(*) as num from {$GLOBALS[table]}bu_detail where ref_name='{$str_ref}'";
		$GLOBALS[dbcon]->execute_query($sql);
		$result = $GLOBALS[dbcon]->fetch_records(true);
		if($result[0]['num']==0){
			$strname = $str_ref;
			break;
		}
	}
	return $strname;
}

function validateReferrer($referrer) {
	global $dbcon;
    $ref = $dbcon->getOne("SELECT * FROM aus_soc_bu_detail WHERE ref_name = '".$referrer."'");
	if (isset($ref)) {
		$StoreID = $ref['StoreID'];
		if ($ref['CustomerType'] == 'buyer') {
			$bank_result = $dbcon->getOne("SELECT * FROM bank_details WHERE user_id = '".$StoreID."'");
			if (isset($bank_result)) {
				return true;
			}
		} else {
			return true;
		}
	}
	return false;
}

function getCategorylist($fid = 0){
	global $dbcon ;
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."product_category where fid ='$fid' order by name ASC";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$num = 1;
	$len = count($grid);
	for ($i = 0; $i < $len; $i++ ) {
		$grid[$i]['num'] = $num;
		$num++;
	}
	
	return $grid;
}

function getEwayInfo(){
    global $dbcon ;
	$query	=	"SELECT * FROM ".$GLOBALS["table"]."config WHERE type ='eway'";
	$dbcon->execute_query($query);
	$arrTemp = $dbcon->fetch_records(true) ;
	if (is_array($arrTemp)) {
		$arrResult = array();
		foreach ($arrTemp as $val) {
			$arrResult[$val['name']] = $val['value'];
		}
	}

	return $arrResult;
}


function getPaypalInfo(){
	global $dbcon ;
	$query	=	"SELECT * FROM ".$GLOBALS["table"]."config WHERE type ='paypal'";
	$dbcon->execute_query($query);
	$arrTemp = $dbcon->fetch_records(true) ;
	if (is_array($arrTemp)) {
		$arrResult = array();
		foreach ($arrTemp as $val) {
			$arrResult[$val['name']] = $val['value'];
		}
	}

	return $arrResult;
}
function getFoodWineCategorylist($fid = 0){
	global $dbcon ;
	$QUERY	=	"SELECT id, fid, category_name AS name FROM ".$GLOBALS["table"]."product_category_foodwine where fid ='$fid' order by category_name ASC";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records(true) ;
	return $grid;
}
function isuserbuy($pid=0,$bid=0){
	global $dbcon,$table;
	if($pid==0||$pid==""){
		return false;
	}
	if($bid==0||$pid==""){
		return false;
	}
	$query = "SELECT count(*) as num FROM {$table}product_download where pid='{$pid}' and bid='{$bid}' and isdownload=0 and enable=1 and lastdowndate >=".time();
	$dbcon->execute_query($query);
	$result = $dbcon->fetch_records(true);
	if($result[0]['num']>0){
		return true;
	}else{
		return false;
	}
	
}
/**get url in youtube objstring **/
function getobjurl($str){
	preg_match("/src=[\"|\'](\S+)[\"|\']/i",$str,$params);
	if(count($params)>1){
    if(isset($_SERVER['HTTPS'])) {
      return str_ireplace("http:", "https:", $params[1]);
    } else {
      return $params[1];
    }
	}else{
		return false;
	}
}

function getCustomInfo($StoreID)
{
	global $dbcon ;

	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."bu_detail where StoreID  ='$StoreID'";
	$result	=	$dbcon->execute_query($QUERY) ;
	$grid	=	$dbcon->fetch_records() ;
	$StoreInfo	=	$grid[0];

	return $StoreInfo;
}

function sendInboxMessage($toId, $subject, $content, $fromname = 'SYSTEM', $email = 'SYSTEM'){
	global $dbcon;
    $QUERY	=	"insert into ".$GLOBALS['table']."message(subject,message,StoreID,date,emailaddress,fromtoname,pid) values('$subject','$content','$toId','".time()."','$email','SYSTEM','0')";
	$result	=	$dbcon->execute_query($QUERY) ;
}

function formatTextarea($string)
{
	return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($string)));
}


function truncate($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}


/**
 * get email template of add header and footer
 *
 * @param <string> $templateContent template content
 * @param <array> $param
 */
function getEmailTemplate($templateContent='', $param=array())
{
    $templateName = dirname(__FILE__) . '/../skin/red/email_template/email_global.tpl';

    global $smarty;
    $req = array('emailContents'=>$templateContent);
    if(!empty($param)) {
        $req = array_merge($req, $param);
    }
    $smarty->assign('req', $req);

    return $smarty->fetch($templateName);

}

/**
 * send email
 */
function fixEOL($str)
{
    $str = str_replace("\r\n", "\n", $str);
    $str = str_replace("\r", "\n", $str);
    $str = str_replace("\n", "\n", $str);

    return $str;
}

function getSitemap()
{
    global $smarty;
    global $dbcon;
    global $table;
    global $lang;
    $socObj=new socClass();
    
    //buysell
    $query="select t1.id,t1.fid,t1.name,t1.image ".
			" from ".$table."product_category as t1 ".
			" where t1.fid='0' and t1.disabled=0 order by t1.name,t1.sort";
    $dbcon->execute_query($query);
    $result['buysell'] = $dbcon->fetch_records(true);
    
    //estate
    $_query = "SELECT id, `stateName` as mname,`description` as name \n".
	"FROM `". $table ."state` \n".
	"ORDER BY `description` ASC";
    $dbcon->execute_query($_query);
    $result['estate'] = $dbcon->fetch_records(true);
    
    //auto
    $add_option = array(array('name'=>'All','id'=>'','place'=>0),array('name'=>'Other','id'=>'-2','place'=>1));
    $rs = getSectorListFromDB(504,0,$add_option);
    unset($rs[0]);
    sort($rs);
    $other=$rs[0];
    unset($rs[0]);    
    sort($rs);
    $rs[]=$other;
    $result['auto'] = $rs;
        
    //job
    $result['job'] = getSectorListFromDB(0,1);
    
    //foodwine
    $result['foodwine'] = $GLOBALS['_LANG']['seller']['attribute'][5]['subattrib'];
    
    return $result;
}

function getFoodWineType($subAttribute=null)
{
    if(null === $subAttribute) {
        $subAttribute = $_SESSION['subAttrib'];
    }
	//if(in_array($subAttribute, array(1,7,8,9,10))) {
	if(in_array($subAttribute, array(1,7,8,9,10))) {
	//if(in_array($subAttribute, array(1,7,8))) {
    //if(in_array($subAttribute, array(1, 7))) {
        return 'wine';
    }
    return 'food';
}

function getCuisineList()
{
    global $dbcon ;

	$QUERY	= "SELECT * FROM ".$GLOBALS["table"]."cuisine_options WHERE 1 ORDER BY cuisineName ASC";
	$result	= $dbcon->execute_query($QUERY) ;
	$result	= $dbcon->fetch_records() ;

	return $result;
}

function smart_substr($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
{
	if ($length == 0)
		return '';

	if (strlen($string) > $length) {
		$length -= min($length, strlen($etc));
		if (!$break_words && !$middle) {
			$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
		}
		if(!$middle) {
			return substr($string, 0, $length) . $etc;
		} else {
			return substr($string, 0, $length / 2) . $etc . substr($string, -$length/2);
		}
	} else {
		return $string;
	}
}

function getip()
{
    //IP
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_replace("/^([\d\.]+).*/", "\\1", $onlineip);
}

function getSalt()
{
	$salt = "\$2y\$12\$";
	for ($i = 0; $i < 22; $i++) {
		$salt .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
	}
	return $salt;
}
?>
