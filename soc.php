<?php
/**
 * soc public control
 * Wed Feb 5 08:56:29 CST 2008 08:56:29
 * @author  : Ping.Hu <ping.hu@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * soc.php
 */

// if(isset($_REQUEST["testing"])){
// 	$loginSuccessful = false;
// 	if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
// 	    $username = $_SERVER['PHP_AUTH_USER'];
// 	    $password = $_SERVER['PHP_AUTH_PW'];
// 	    if ($username == 'admin' && $password == 'KahnKazzi88'){
// 	        $loginqSuccessful = true;
// 	    }
// 	}
// 	if (!$loginSuccessful){
// 	    header('WWW-Authenticate: Basic realm="Secret page"');
// 	    header('HTTP/1.0 401 Unauthorized');
// 	    print "Login failed!\n";
// 	    exit;
// 	}
// }


include_once ('include/config.php');
@session_start();

// define a soc flag
$is_soc = 1;

$setACT	=	!empty($setACTAtInc) ? "$setACTAtInc" : (isset($_REQUEST['act'])?$_REQUEST['act']:null);
switch ($setACT)
{

	case 'signon':
		include('soc/store_set.php');
		break;
	
	case 'regfree':
		include('soc/reg_free_seller.php');
		break;
		
	case 'admin':
		//header('Location:soc.php?cp=business_get_step_home&ctm=1');
		header('Location:soc.php?cp=sellerhome&ctm=1');
		exit;
		break;
		
	case 'offer':
		include('soc/offer.php');
		break;
	
	case 'ipg':
		include('soc/payment_ipg.php');
		break;
        /**
         * added by YangBall, 2011-02-16
         * add NR payment method
         */

         case 'nr':
                include('soc/payment_nr.php');
                break;
        //END-YangBall
	case 'wishlist':
		include('soc/wishlist.php');
		break;
	case 'wishlistproc':
		include('soc/wishlistproc.php');
		break;
	case 'invitations':
		include('soc/invitations.php');
		break;
	case 'invithis':
		include('soc/invit_list.php');
		break;
        /*
         *  @INFINITY_TEMP 
         */
        case 'tinvithis' :
                include('soc/invites.php');
                break;

	case 'gallery':
		include('soc/gallery.php');
		break;
	case 'galleryinfo':
		include('soc/galleryinfo.php');
		break;
	case 'galleryInt':
		include('soc/galleryInt.php');
		break;			
	default:
		include('soc/index.php');
		break;
}
exit;
?>
