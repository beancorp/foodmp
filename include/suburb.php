<?php
include_once "../include/config.php" ;
$database = "buyblitz_aus_soc";
include_once "../include/maininc.php" ;
include_once "../include/functions.php" ;

mysql_select_db('buyblitz_soc_aus');
$sql = "select distinct(bu_suburb), bu_state, bu_postcode, stateName from buyblitz_bu_detail, aus_soc_state where buyblitz_bu_detail.bu_state=aus_soc_state.id and LENGTH(buyblitz_bu_detail.bu_postcode)=4 and buyblitz_bu_detail.bu_suburb!='' ";
$dbcon->execute_query($sql);
$data = $dbcon->fetch_records();

$count = 0;
foreach($data as $row){
	$sql = "insert into aus_soc_suburb (state_id,state,suburb,zip) values(".$row['bu_state'].",'".mysql_escape_string($row['stateName'])."','".$row['bu_suburb']."','".$row['bu_postcode']."')";
	if ($dbcon->execute_query($sql)){
		$count+=1;
	}else{
		echo "$sql\n";
		$dbcon->showerror();
		exit;
	}
}
echo "count:$count";
?>