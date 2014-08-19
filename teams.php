<?php
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
include_once ('include/config.php');
@session_start();
include_once('include/smartyconfig.php');
include_once('point_system.class.php');

if (!isset($_SESSION['StoreID'])) {
	header('location: soc.php?cp=login');
}

$team = Team::fetchMyTeam($_SESSION['StoreID']);

if (!empty($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'accept':
			
			break;
			
		case 'invite':
			if (!empty($_POST['team_name'])) {
				if (!isset($team)) {
					$team = new Team(0, $_POST['team_name']);
					$team->save();
					$team->addMember($_SESSION['StoreID']);
				} else {
					$team->team_name = $_POST['team_name'];
					$team->save();
				}
			}
			for ($i = 0; $i < count($_POST['invite_address']); $i++) {
				if (isset($_POST['invite_name'][$i]) && isset($_POST['invite_address'][$i])) {
					$name = $_POST['invite_name'][$i];
					$email = $_POST['invite_address'][$i];
					$team->invite($name, $email);
				}
			}
			break;	
	}
	exit;
}

if (isset($team)) {
	$smarty->assign('team_name', $team->team_name);
}

$smarty->assign('pageTitle','Race');
$smarty->assign('content', $smarty->fetch('race/teams.tpl'));
$smarty->assign('sidebar', 0);
$smarty->assign('hideLeftMenu', 0);
$smarty->assign('show_left_cms', 1);
$smarty->assign('is_content',1);
$smarty->display($template_tpl);

unset($smarty);
?>