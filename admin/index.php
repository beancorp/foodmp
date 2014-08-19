<?php
/**
 * Wed Oct 08 14:06:09 GMT+08:00 2008 14:06:09
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * admin control
 * ------------------------------------------------------------
 * \admin\index.php
 */
ini_set('display_errors', 0);
ini_set('error_reporting', E_ALL);

@session_start();

switch ($_REQUEST['act'])
{

	case 'review':
		include('review.php');
		break;
		
	case 'main':
		include('main.php');
		break;
		
	case 'email':
		include('email.php');
		break;
	
	case 'pro':
		include('product.php');
		break;
	
	case 'payment':
		include('payment.php');
		break;
	
	case 'adv':
		include('advertising.php');
		break;
		
	case 'store':
		include('store.php');
		break;
	
	case 'msg':
		include('message.php');
		break;
	case 'promotion':
		include('promotion.php');
		break;
	case 'report':
		include('report.php');
		break;
	case 'test':
		include('test.php');
		break;
	case 'race':
		include('race.php');
		break;
	case 'referral':
		include('referral.php');
		break;
	case 'facelike':
		include('facelike.php');
		break;
	case 'referrals':
		include('referrals.php');
		break;
	case 'leaderboard':
		include('leaderboard.php');
		break;
	case 'commissions':
		include('commissions.php');
		break;
	case 'imagelibrary':
		include('imagelibrary.php');
		break;
	case 'fanfrenzy':
		include('fanfrenzy.php');
		break;
	case 'fanfrenzycontent':
		include('fanfrenzy_content.php');
		break;
	case 'fanfrenzyapproval':
		include('fanfrenzy_approval.php');
		break;
	case 'fanfrenzy_template_email':
		include('fanfrenzy_template_email.php');
		break;
	case 'fanfrenzy_grand_list':
		include('fanfrenzy_grand_list.php');
		break;
	case 'photos':
		include('photos.php');
		break;
	default:
		include('login.php');
		break;
}
exit;
?>