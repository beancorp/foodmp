<?php
@session_start();

include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

include_once('../languages/'.LANGCODE.'/soc.php');
include_once('../languages/'.LANGCODE.'/foodwine/index.php');

if (isset($_GET['query'])) {
	$suburbs_sql = "SELECT * FROM aus_soc_suburb WHERE suburb LIKE '%".$_GET['query']."%' LIMIT 50";

	$dbcon->execute_query($suburbs_sql);
	$suburb_result = $dbcon->fetch_records(true);
	
	$result = array();
	$result['suggestions'] = array();
	
	if (is_array($suburb_result)) {
		foreach($suburb_result as $suburb) {
			$result['suggestions'][] = array('value' => ucwords($suburb['suburb']) . ', ' . $suburb['zip'], 'data' => $suburb['suburb_id']);
		}
	}
	
	echo json_encode($result);
}

?>

<?php


		
		
		

?>