#!/usr/bin/php
<?php
$new_test_site_usa_path=dirname(dirname(__FILE__));     //new live site path
include_once($new_test_site_usa_path.'/include/config.php');
include_once($new_test_site_usa_path.'/include/maininc.php');
$query = "DELETE {$table}ads_soc FROM {$table}ads_soc, {$table}bu_detail WHERE {$table}ads_soc.StoreID={$table}bu_detail.StoreID AND {$table}bu_detail.attribute='5'";
$dbcon->execute_query($query);
?>
