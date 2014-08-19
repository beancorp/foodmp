<?php
include_once ('include/config.php');
@session_start();
include_once ('include/smartyconfig.php');
include_once ('include/maininc.php');
include_once ('include/class.soc.php');
include_once ('include/class.point.php');
$smarty -> loadLangFile('threeSeller');
$smarty -> loadLangFile('soc');

$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - SOC Exchange');
$smarty -> assign('keywords',$keywordsList);
$smarty -> assign('description', 'The SOC Exchange involves selling online all your products in an online trading post. No matter how much you sell, we use flat rate selling.');
$socObj = new socClass();
$states = $socObj->getStatesList();
$smarty -> assign('statesList',	$states);

if (!empty($_SESSION)) {
$_SESSION['website'] = $_SESSION['urlstring'];
$smarty -> assign('session', $_SESSION);
}
if ($_SESSION['level'] == 2 || $_SESSION['attribute'] == 4 || ($_SESSION['attribute'] == 3 && $_SESSION['subAttrib'] == 3)) {
	$msg = "The Ultimate SOC Race is open to those who list items on 'SOC exchange'. It's FREE to list, so join the race for the cash today!";
	header("Location:/showmessage.php?msg=".urlencode($msg));
    exit;
}

$domain = $_SESSION['bp_domain'];
$objPoint = new Point();

if (empty($domain)) {
	$msg = "Please come from the partner's website.";
	header("Location:/showmessage.php?msg=".urlencode($msg));
    exit;
}
$req['site_info'] = $objPoint->getSiteInfo($domain);
if (!$req['site_info']['status']) {
	$msg = "There is not question for this partner's website.";
	header("Location:/showmessage.php?msg=".urlencode($msg));
    exit;
}

if(empty($_SESSION['bp_login'])){	
	header("Location: /bp_login.php");
	exit();
}

$site_info = $objPoint->getSiteInfo($domain);
$check_info = $objPoint->checkHasAnswer($domain);
$finish = (((time() - $check_info['start_time']) > ($site_info['max_time'])) || $check_info['finish']) || $check_info['is_correct'];

if ($_REQUEST['cp'] == 'finish') {
	extract($_REQUEST);
	$objPoint->addBRPoint($domain, $question_id, $is_correct, $answer);
	exit();
}
if ($_REQUEST['cp'] == 'answer') {
	if ($finish) {		
		$msg = "Time limit has expired.";
		header("Location:/showmessage.php?msg=".urlencode($msg));
	    exit;	
	}
	extract($_REQUEST);
	$is_correct = $objPoint->checkAnswer($question_id, $answer);
	$objPoint->addBRPoint($domain, $question_id, $is_correct, $answer);
	
	$req['site_info'] = $site_info;
	$req['correct_answer']['list'] = $objPoint->getCorrectAnswerInfo($question_id);
	$req['answer_info']['list'] = $objPoint->getAnswerInfoByChoose($question_id, $answer);
	$req['answer_info']['is_correct'] = $is_correct;
	$smarty->assign('req',$req);
	$smarty->assign('is_home',1);
	$smarty -> assign('sidebar_bg', '0');
	
	/**
	 * added by Kevin, 2012-02-22
	 * mark of top menu type
	 */
	$smarty->assign('hideTopTypeMenu', true);
	
	$smarty->assign('footer',  footer());
	$smarty -> assign('main_page',1);
	$smarty->display('bp_answer.tpl');
	exit();
}

if ($check_info) {
	if ($finish) {
		$msg = "You have previously logged-in to answer this question. Bonus Point Partner Questions can only be attempted once.";
		header("Location:/showmessage.php?msg=".urlencode($msg));
	    exit;	
	} else {
		$site_info['left_time'] = $site_info['max_time'] - (time() - $check_info['start_time']);
		$site_info['left_time'] = $site_info['left_time'] > 0 ? $site_info['left_time'] : 0;
	}	
}

$req['site_info'] = $site_info;
if ($finish) {
	$req['question_info'] = $objPoint->getRandQuestion('', $domain);
	if ($req['question_info'] === 1) {
		$msg = "There is not question for this partner's website.";
		header("Location:/showmessage.php?msg=".urlencode($msg));
	    exit;
	} elseif ($req['question_info'] === 2) {
		$msg = "You have answer all the questions.";
		header("Location:/showmessage.php?msg=".urlencode($msg));
	    exit;	
	}
	$objPoint->addStoreQuestion('', $domain, $req['question_info']['id']);	
} else {	
	$req['question_info'] = $objPoint->getRandQuestion('', $domain, $check_info['question_id']);
}

$pageTitle = 'SOC Race Bonus Point Question';
$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - SOC Race Bonus Point Question');
$smarty -> assign('keywords','SOC Race Bonus Point Question');
$smarty->assign('req', $req);
$smarty->assign('is_home',1);
$smarty -> assign('sidebar_bg', '0');

/**
 * added by Kevin, 2012-02-22
 * mark of top menu type
 */
$smarty->assign('hideTopTypeMenu', true);

$smarty->assign('footer',  footer());
$smarty -> assign('main_page',1);
$smarty->display('bp_question.tpl');

exit;

?>
