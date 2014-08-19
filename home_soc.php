<?php
if(isset($_REQUEST["testing"])){
    $loginSuccessful = false;
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if ($username == 'admin' && $password == 'KahnKazzi88'){
            $loginSuccessful = true;
        }
    }
    if (!$loginSuccessful){
        header('WWW-Authenticate: Basic realm="Secret page"');
        header('HTTP/1.0 401 Unauthorized');
        print "Login failed!\n";
        exit;
    }
}

include_once "include/session.php" ;
include_once "include/functions.php" ;
include_once ('include/maininc.php');
$name = $_REQUEST['name'];
$name = clean_url_name($name);

$result = null;
$count = 0;

if (!empty($_SESSION['StoreID'])) {
	$query_own_store = "SELECT * FROM aus_soc_bu_detail detail WHERE detail.bu_urlstring = '".$name."' AND detail.StoreID = '".$_SESSION['StoreID']."'";
	$dbcon->execute_query($query_own_store);
	$count = $dbcon->count_records();
	if ($count > 0) {
		$result = $dbcon->fetch_records();
	}
}

if ($count == 0) {
	$query = "SELECT * FROM aus_soc_bu_detail detail WHERE detail.bu_urlstring = '".$name."' AND detail.status = 1 AND detail.CustomerType = 'seller'";
	$dbcon->execute_query($query);
	$count = $dbcon->count_records();
	if ($count > 0) {
		$result = $dbcon->fetch_records();
	}
}

if (isset($result)) {
	$sid = $result[0]['StoreID'];
	if ($result[0]['suspend']){
		echo "<script>alert('The Website is suspended.');location.href='/soc.php?cp=home';</script>";
		exit;
	}
	$is_soc = 1;
	$_REQUEST['StoreID'] = $sid;
	switch ($_REQUEST['act']) {
		case 'singon':
			include('soc/store_set.php');
			break;
		case 'admin':
			header('Location:business_get_step_home.php?ctm=1');
			exit;
			break;
		default:
			include('soc/url_index.php');
			break;
	}
} else {
    header('Location: /soc.php?cp=error404');
}
?>