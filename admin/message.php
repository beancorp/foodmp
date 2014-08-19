<?php

@session_start();

require_once('../include/smartyconfig.php');
require_once('Pager/Pager.php');
require_once('xajax/xajax_core/xajax.inc.php');
require_once('maininc.php');
require_once('class.login.php');
require_once('class.adminmessage.php');
require_once('class.emailClass.php');
require_once('functions.php');

// check login
$objLogin = new login();
if (!$objLogin->checkLogin())
{
	header('Location: ../admin/');
	exit;
}
		
$objAdminMsg = new adminMsg();

// control
switch ($_REQUEST['cp'])
{
	// All Stores
	case 'all':
		
		// main content
		$req = $objAdminMsg->viewAllStores();
		$smarty->assign('req', $req);
		
		// page title
		$smarty->assign('pageTitle', $req['header']);
		
		// fetch template
		$content = $smarty->fetch('admin_msg_all.tpl');
		$smarty->assign('content', $content);
		
		break;
	
	// Selected Store
	case 'selected':
		
		$xajax = new xajax();
		$xajaxListSuburbs = $xajax->registerFunction('listSuburbs');
		$xajaxListSuburbs->setParameter(0, XAJAX_INPUT_VALUE, 'state');
		$xajaxListRecipients = $xajax->registerFunction('listRecipients');
		$xajaxListRecipients->setParameter(0, XAJAX_INPUT_VALUE, 'state');
		$xajaxListRecipients->setParameter(1, XAJAX_INPUT_VALUE, 'suburb');
		
		$xajax->processRequest();
		
		// main content
		$req = $objAdminMsg->viewSelectedStores();
		$smarty->assign('req', $req);
		// page title
		$smarty->assign('pageTitle', $req['header']);
		
		// fetch template
		$content = $smarty->fetch('admin_msg_selected.tpl');
		$smarty->assign('content', $content);
		
		break;
	
	// Individual Store
	case 'individual':
		
		// main content
		$req = $objAdminMsg->viewIndividualStores();
		$smarty->assign('req', $req);
		
		// page title
		$smarty->assign('pageTitle', $req['header']);
		
		// fetch template
		$content = $smarty->fetch('admin_msg_individual.tpl');
		$smarty->assign('content', $content);
		
		break;
	
	// Send Reminder
	case 'sendreminder':
		
		// main content
		$req['header'] = $objAdminMsg->lang['header'][$_REQUEST["cp"]];
		$req['title'] = $_REQUEST['msg'] == 'success' ? $objAdminMsg->lang['msg']['sentremindersuccessfully'] : ($_REQUEST['msg'] == 'fail' ? $objAdminMsg->lang['msg']['sentfailed'] : '');
		$req['input']['message'] = $objAdminMsg->initEditor('message', '<p><img src="'.SOC_HTTP_HOST.'/skin/red/images/emailalert/report_emailalert_banner.jpg" /></p><p>Dear Subscriber,</p>
<p>Just a quick message to let you know the latest copy of <font style="color:red;font-size:14px;font-family:Arial, Helvetica, sans-serif; font-weight:bold;">The Fresh Produce Report</font> is now available at: <a href="'.SOC_HTTP_HOST.'foodreport">'.SOC_HTTP_HOST.'foodreport</a></p>
<p>Learn about the current state of the fresh produce market. Be better informed before you spend your shopping dollar!</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Regards,</p>
<p>SOC exchange</p>', 'adminDefault', array('100%', '350'));
		
		$smarty->assign('req', $req);
		
		// page title
		$smarty->assign('pageTitle', $req['header']);
		
		$smarty->assign('soc_http_host', SOC_HTTP_HOST);
		
		// fetch template
		$content = $smarty->fetch('admin_send_reminder.tpl');
		$smarty->assign('content', $content);
		
		break;
	
	// Previous Message
	case 'pre':
		
		$xajax = new xajax();
		$xajaxListMessage = $xajax->registerFunction('listMessage');
		$xajaxViewMessage = $xajax->registerFunction('viewMessage');
		$xajax->processRequest();
		
		// main content
		$req = $objAdminMsg->viewPreviousMessage();
		$smarty->assign('req', $req);
	
		// page title
		$smarty->assign('pageTitle', $req['header']);
		
		// fetch template
		$content = $smarty->fetch('admin_msg_previous.tpl');
		$smarty->assign('content', $content);
		
		break;
	
	// preview
	case 'preview':
		echo 'test';
		break;
	// process
	case 'process':
		
		$objAdminMsg->process();
		
		break;
}
$req['Menu']["{$_REQUEST['cp']}"] = "style='color:#FF0000;font-weight:bold;'";
$smarty -> assign('req',	$req);

$smarty->display('index.tpl');

exit;

?>