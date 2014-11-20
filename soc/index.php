<?php
/**
 * soc contrl file
 * Tue Feb 5 15:52:12 CST 2008 15:52:12
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * index.php
 */
include_once ('include/config.php');
include_once ('include/session.php');
include_once ('include/smartyconfig.php');
include_once ('maininc.php');
include_once ('class.emailClass.php');
include_once ('class.soc.php');
include_once ('class.phpmailer.php');
include_once ('class.socbid.php');
include_once ('class.socreview.php');
include_once ('functions.php');
include_once ('class.page.php');
include_once ('class/pagerclass.php');
include_once ('class.uploadImages.php');
include_once ('class.adminhelp.php');
include_once ('class.adminjokes.php');
include_once ('class.wishlist.php');
include_once ('class/ajax.php');
include_once ('class.socstore.php');
include_once ('class.order.php');
include_once ('class.cart.php');


$socObj = new socClass();

$socbidObj = new socbidClass();
$socreviewObj = new socReviewClass();
$socstoreObj = new socstoreClass();

$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');

//display logo or not
unset($_SESSION['logo_old']);
unset($_SESSION['logo_new']);
$is_logo = '';
$menu_bgcolor = '';
$menu_bottom = '';
$keywordsList = 'flat rate selling, how to sell online, online trading post, sell goods online, sell items online, sell products online, sell stuff online, sell things online, selling online, simple selling online';

$setCP	=	!empty($setCPAtInc) ? "$setCPAtInc" : $_REQUEST['cp'];

if (($setCP == 'category')
|| ($setCP == 'features')
|| ($setCP == 'signon')
|| ($setCP == 'cgeton' && empty($_REQUEST['step']))
|| ($setCP == 'faq')
|| ($setCP == 'payments')
|| ($setCP == 'prolist')
|| ($setCP == 'sample') ) {

	$_SESSION['logo_new'] = true;

}elseif (  ($setCP == 'home')
|| ($setCP == 'contact')
|| ($setCP == 'protection')
|| ($setCP == 'blog')
|| ($setCP == 'blogpage')
|| ($setCP == 'dispro')
|| ($setCP == 'disprolist')
|| ($setCP == 'about') ){


	$_SESSION['logo_old'] = true;

}


$smarty -> assign('securt_url',$securt_url);
$smarty -> assign('normal_url',$normal_url);
$smarty->assign('soc_https_host',SOC_HTTPS_HOST);



function limit_text($text, $limit) {
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}

function view_photos_customer() {
    //Start update
    if(!isset($_SESSION['StoreID'])){
        echo "Unauthorized";
        exit;   
    }
    global $dbcon;
            
    $res = $dbcon->getOne("SELECT COUNT(*) As count FROM photo_promo WHERE consumer_id = '".$_SESSION['StoreID']."' AND approved <> 2");
    
    $perPage = 18;       
    $pageno    = empty($_REQUEST['p']) ? 1 :$_REQUEST['p'];
    
    $count = $res['count'];
   
    $start    = ($pageno-1) * $perPage;        
    $end = $start + $perPage;
    
    $total_page = ceil($count/$perPage);
    
    $sql = "SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, retailer.bu_urlstring As retailer_url, COUNT(fans.fan_id) As fan_count FROM photo_promo photo
            LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
            LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
            LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
            WHERE photo.consumer_id = '".$_SESSION['StoreID']."'
            AND photo.approved <> 2
            GROUP BY photo.photo_id ORDER BY fan_count DESC, photo.timestamp ASC
            LIMIT $start, $perPage";
    
    $dbcon->execute_query($sql);
    $res = $dbcon->fetch_records(true);
    
    if ($res) {
        foreach ($res as $key => $val) {
            $val['rank'] = $perPage * ($pageno - 1) + $key + 1;
            $res[$key] = $val;
        }    
    }
    
    $last_p = ($pageno - 1) > 0 ? ($pageno - 1) : 0;
    $next_p = ($pageno * $perPage < $count) ? ($pageno + 1) : 0;
    $info = array(
        'last_p' => $last_p,
        'next_p' => $next_p,
        'list' => $res
    );

    if (is_array($info['list'])) {
        $i = 1;
        foreach ($info['list'] as $photo) {
        
            if (($i % 2) == 1){
                $my_entries .= '<div class="item-row">';
            }
            
            if(LANGCODE == 'en-us'){
                $date_uploaded = date('F d, Y', strtotime($photo['timestamp']));
            }else{
                $date_uploaded = date('d.m.Y', strtotime($photo['timestamp']));     
            }
            $my_entries .= '<div class="block-1">';
            
            if ($photo['rank'] >= 1 && $photo['rank'] <= 3) {
                $my_entries .= '<div class="place_image place_'.$photo['rank'].'"></div>';
            }
            $my_entries .= '<a class="fan_photo" href="/photo_'.$photo['photo_id'].'.html"><img src="/fanpromo/'.$photo['thumb'].'" alt=""></a>';

            if(!empty($photo['retailer'])){   
                $retailer_name = limit_text($photo['retailer'], 4);    
            }else{  
                $retailer_name = limit_text($photo['retailer_name'], 4);
            }
            
            $my_entries .= '<div class="block-left">
                <div class="name-user">'.$photo['consumer'].'</div>';
            if(!empty($photo['retailer_url'])){
                $my_entries .= '<div class="content"><a href="'.SOC_HTTP_HOST.$photo['retailer_url'].'" target="_blank">'.$retailer_name.'</a></div>';
            }else{
                $my_entries .= '<div class="content">'.$retailer_name.'</div>';   
            }
                $my_entries .= $date_uploaded.'            
                </div>
                <div class="block-right"><div class="fans">'.$photo['fan_count'].' Fans</div></div>
            </div>';
            if (($i % 2) == 0 || $i == count($info["list"])){
                $my_entries .= '<div class="clear"></div>';
                $my_entries .= '</div>'; //end div item-row
            }
            $i++;
        }
        echo $my_entries;
        
        if ($total_page > 1){    
            echo '<div class = "number-page">';
            if (!empty($info['last_p'])) {                        
                echo '<span class="txt-next" id="list_prev" >< Previous</span>';
            }
            for($i = 1; $i<=$total_page; $i++){
                echo '<span rel="'.$i.'" class="search_list ';
                if ($pageno == $i) echo ' active ';
                echo '">'.$i.'</span>&nbsp&nbsp';
            }
            
            
            if (!empty($info['next_p'])) {                        
                echo '<span class="txt-next" id="list_next" ><a href="javascript:void(0)">Next ></a></span>';
            }
             echo '</div>';  
        }
       
    
        echo '<script>
        
                function view_photos_customer(page_number) {
                    $.ajax({
                        url: "/soc.php?cp=buyerhome",
                        type: "POST",
                        data: {action: "list", p: page_number}                     
                    }).done(function(data) {
                        if(data == "Unauthorized")
                            window.location.reload();
                        else
                            $(".block-gallery").html(data);
                    });
                }
                
                $(document).ready(function() {                                  
                    $("#list_prev").click(function() {
                        view_photos_customer('.$info['last_p'].');
                    });
                    $("#list_next").click(function() {
                        view_photos_customer('.$info['next_p'].');
                    });
                    
                    $(".search_list").click(function() {                    
                        view_photos_customer($(this).attr("rel"));
                    });
                });
            </script>';
    } 
}


function view_photos_seller($owner=true) {
    //Start update
    if(!isset($_SESSION['StoreID'])){
        echo "Unauthorized";
        exit;   
    }
    global $dbcon;
    if($owner){        
        $res = $dbcon->getOne("SELECT COUNT(*) As count FROM photo_promo WHERE consumer_id = '".$_SESSION['StoreID']."' AND photo_promo.approved <> 2");
    }else{
        $res = $dbcon->getOne("SELECT COUNT(*) As count FROM photo_promo WHERE store_id = '".$_SESSION['StoreID']."' AND photo_promo.approved=1");   
    }
    $perPage = 18;       
    $pageno    = empty($_REQUEST['p']) ? 1 :$_REQUEST['p'];
    
    $count = $res['count'];
    
    $start    = ($pageno-1) * $perPage;        
    $end = $start + $perPage;
    
    $total_page = ceil($count/$perPage);
    
    if($owner){        
         $sql = "SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, retailer.bu_urlstring As retailer_url, COUNT(fans.fan_id) As fan_count FROM photo_promo photo
            LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id 
            LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
            LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
            WHERE photo.consumer_id = '".$_SESSION['StoreID']."'
            AND photo.approved <> 2
            GROUP BY photo.photo_id ORDER BY fan_count DESC, photo.timestamp ASC
            LIMIT $start, $perPage";
    }else{      
        $sql = "SELECT photo.*, consumer.bu_name As consumer, retailer.bu_name As retailer, retailer.bu_urlstring As retailer_url, COUNT(fans.fan_id) As fan_count FROM photo_promo photo
            LEFT JOIN photo_promo_fan fans ON fans.photo_id = photo.photo_id
            LEFT JOIN aus_soc_bu_detail consumer ON consumer.StoreID = photo.consumer_id
            LEFT JOIN aus_soc_bu_detail retailer ON retailer.StoreID = photo.store_id
            WHERE photo.store_id = '".$_SESSION['StoreID']."' AND photo.approved=1
            GROUP BY photo.photo_id ORDER BY fan_count DESC, photo.timestamp ASC
            LIMIT $start, $perPage";    
    } 
   
    $dbcon->execute_query($sql);
    $res = $dbcon->fetch_records(true);     
    if ($res) {
        foreach ($res as $key => $val) {
            $val['rank'] = $perPage * ($pageno - 1) + $key + 1;
            $res[$key] = $val;
        }    
    }
    
    $last_p = ($pageno - 1) > 0 ? ($pageno - 1) : 0;
    $next_p = ($pageno * $perPage < $count) ? ($pageno + 1) : 0;
    $info = array(
        'last_p' => $last_p,
        'next_p' => $next_p,
        'list' => $res
    );
    
    if (is_array($info['list'])) {
        $i = 1;
        foreach ($info['list'] as $photo) {
            if (($i % 2) == 1){
                $my_entries .= '<div class="item-row">';
            }
            if(LANGCODE == 'en-us'){
                $date_uploaded = date('F d, Y', strtotime($photo['timestamp']));
            }else{
                $date_uploaded = date('d.m.Y', strtotime($photo['timestamp']));     
            }
            $my_entries .= '<div class="block-1">';
            
            if ($photo['rank'] >= 1 && $photo['rank'] <= 3) {
                $my_entries .= '<div class="place_image place_'.$photo['rank'].'"></div>';
            }
            $my_entries .= '<a class="fan_photo" href="/photo_'.$photo['photo_id'].'.html"><img src="/fanpromo/'.$photo['thumb'].'" alt=""></a>';
            
            if(!empty($photo['retailer'])){   
                $retailer_name = limit_text($photo['retailer'], 4);    
            }else{  
                $retailer_name = limit_text($photo['retailer_name'], 4);
            }
            
            $my_entries .= '<div class="block-left">
                <div class="name-user">'.$photo['consumer'].'</div>';
            if(!empty($photo['retailer_url'])){
                $my_entries .= '<div class="content"><a href="'.SOC_HTTP_HOST.$photo['retailer_url'].'" target="_blank">'.$retailer_name.'</a></div>';
            }else{
                $my_entries .= '<div class="content">'.$retailer_name.'</div>';   
            }
                $my_entries .= $date_uploaded.'           
                </div>
                <div class="block-right"><div class="fans">'.$photo['fan_count'].' Fans</div></div>
            
            </div>';
            if (($i % 2) == 0 || $i == count($info["list"])){
                $my_entries .= '<div class="clear"></div>';
                $my_entries .= '</div>'; //end div item-row
            }
            $i++;
        }
        echo $my_entries;
        
        if ($total_page > 1){    
            echo '<div class = "number-page">';
            if (!empty($info['last_p'])) {                        
                echo '<span class="txt-next" id="list_prev" >< Previous</span>';
            }
            for($i = 1; $i<=$total_page; $i++){
                echo '<span rel="'.$i.'" class="search_list ';
                if ($pageno == $i) echo ' active ';
                echo '">'.$i.'</span>&nbsp&nbsp';
            }
            
            
            if (!empty($info['next_p'])) {                        
                echo '<span class="txt-next" id="list_next" ><a href="javascript:void(0)">Next ></a></span>';
            }
            echo '</div>'; 
        }
        
        
        echo '<script>
        
                function view_photos_seller(page_number) {
                    var page_type = $("#promo_page_type").val();
                    $.ajax({
                        url: "/soc.php?cp=sellerhome",
                        type: "POST",
                        data: {action: "list", p: page_number, page_type: page_type}                     
                    }).done(function(data) {
                        if(data == "Unauthorized"){
                            window.location.reload();
                        }else{
                            if(page_type==1)
                                $(".my-gallery").html(data); 
                            else
                                $(".block-gallery").html(data);        
                        }
                    });
                }
                
                $(document).ready(function() {
                    $("#list_prev").click(function() {
                        view_photos_seller('.$info['last_p'].');
                    });
                    $("#list_next").click(function() {
                        view_photos_seller('.$info['next_p'].');
                    });
                    
                    $(".search_list").click(function() {                    
                        view_photos_seller($(this).attr("rel"));
                    });
                });
            </script>';
    } 
}


switch($setCP){
	case 'home':
		header('location: ' . SOC_HTTPS_HOST);
		break;

	case 'about':
		$search_type	=	empty($_REQUEST['search_type']) ? 'store' : $_REQUEST['search_type'];
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - About Food Marketplace');
		$smarty -> assign('keywords','About Food Marketplace');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('About Food Marketplace'));
		$req 	= $socObj -> displayPageFromCMS(35);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		break;

	case 'report':
		$search_type	=	empty($_REQUEST['search_type']) ? 'foodwine' : $_REQUEST['search_type'];
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','The Fresh Produce Report by Franco Lagudi:Food Marketplace');
		$smarty -> assign('description', 'Know the very latest information on the state of the fresh produce market. What are the best buys and why, plus we will discuss the industry dynamics that have an impact on the price you pay in store!');
		$is_set_desc = true;
		$custom_seo_keywords = TRUE;
		$smarty -> assign('keywords','The Fresh Produce Report, Franco Lagudi,Food Marketplace');
		$req 	= $socObj -> displayPageFromCMS(119);
		$req['msg'] = $_REQUEST['msg'];
		$req['aboutPage'] = str_replace('{$msg}', $_REQUEST['msg'], $req['aboutPage']);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('foodwine/report.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('show_left_cms', 1);
		$smarty->assign('sidebar',0);
        $smarty->assign('hide_race_banner',1);
		break;

	case 'reportsubscribe':
		$email	 	= 	$_REQUEST['email'];
		$uid 		=	$_SESSION['UserID'];
		
		if (!emailcheck($email)){
			$msg = "Email Address is not a valid email address.";			
			//header("Location: soc.php?cp=report&msg=".$msg);
		}elseif(!empty($email)) { 
		    $sql2 = "select email from ".$table."report_subscribe where email = '" .$email. "'" ;
			$res = mysql_query($sql2) ;
			$row = mysql_num_rows($res) ;
		
			if( $row > 0 ){   
				$msg = "You are already subscribed to receive a reminder when a new Fresh Produce Report is posted.";
			}else { 
				$date = time();
				$sql = "insert into ".$table."report_subscribe (email,userid,subscribe_date) values('$email','$uid',$date) " ;
				if($dbcon->execute_query($sql)) { 
					$msg = "You are now subscribed to receive a reminder when a new Fresh Produce Report is posted.";
				}else{
					$msg = "You are failed to subscribed." ; 
				}
			}
		}else{
			$msg	=	"You have been successfully subscribed to receive a reminder when a new Fresh Produce Report.";
		}
		
		header("Location: soc.php?cp=report&msg=".$msg."#bottom");
		exit();
		break;

	case 'buysell':
		$search_type = 'store';
		$hide_middle = true;
		$cms_id = $_GET['id'] ? $_GET['id'] : 108;
		if ($cms_id == 108) {
			$itemtitle = 'Food Marketplace - Where your profit stays in your pocket';
		} elseif ($cms_id == 110) {
			$itemtitle = 'Low seller fees means better bargain buys!';
			$hide_middle = true;
		} else {
			$itemtitle = 'Making your Christmas shopping easy!';
		}
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('hide_middle', $hide_middle);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Buy & Sell');
		$smarty -> assign('keywords','Buy & Sell');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle($itemtitle));
		$req 	= $socObj -> displayPageFromCMS($cms_id);
		//$smarty -> assign('req', $req);
		//$content =	$smarty -> fetch('cms.tpl');
		$content = $req['aboutPage'];
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		break;

	case 'estate':
		$search_type	=	'estate';
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Real Estate');
		$smarty -> assign('keywords','Real Estate');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Sell or Lease Real Estate'));
		$req 	= $socObj -> displayPageFromCMS(105);
		//$smarty -> assign('req', $req);
		//$content =	$smarty -> fetch('viewmore.tpl');
		$content = $req['aboutPage'];
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		break;

	case 'auto':
		$search_type	=	'auto';
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Auto');
		$smarty -> assign('keywords','Auto');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Sell a Car or Motorcycle'));
		$req 	= $socObj -> displayPageFromCMS(106);
		//$smarty -> assign('req', $req);
		//$content =	$smarty -> fetch('viewmore.tpl');
		$content = $req['aboutPage'];
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		break;

	case 'job':
		$search_type	=	'job';
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Careers');
		$smarty -> assign('keywords','Careers');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Careers - find the right staff today!'));
		$req 	= $socObj -> displayPageFromCMS(107);
		//$smarty -> assign('req', $req);
		//$content =	$smarty -> fetch('viewmore.tpl');
		$content = $req['aboutPage'];
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		break;

	case 'foodwine':
		$search_type	=	'foodwine';
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Food & Wine');
		$smarty -> assign('keywords','Food & Wine');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Food & Wine'));
		$req 	= $socObj -> displayPageFromCMS(103);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('cms.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
        $smarty->assign('foodwine_home', true);
		break;

	case 'foodwinetips':
		$search_type	=	'foodwine';
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Food & Wine Tips');
		$smarty -> assign('keywords','Food & Wine Tips');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Food & Wine Tips'));
		$req 	= $socObj -> displayPageFromCMS(104);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('cms.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		break;

	case 'copyright':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Copyright/Patent');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Copyright/Patent'));
		$req 	= $socObj -> displayPageFromCMS(58);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
        $smarty->assign('hide_race_banner',1);
		break;
	case 'wishlistAbout':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - About SOC Wish List');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('About SOC Wish List'));
                $smarty->assign('wishlistabout_right_image','yes');
		$req 	= $socObj -> displayPageFromCMS(82);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;
	case 'wishlistTips':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Wishlist Tips');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Wishlist Tips'));
		$req 	= $socObj -> displayPageFromCMS(83);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;
	case 'wishlistSample':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Wishlist Sample');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Wishlists'));
		$req 	= $socObj -> displayPageFromCMS(84);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;
        case 'invites':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Invitations');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Invitations'));
		$req 	= $socObj -> displayPageFromCMS(96);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
		$smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

        case 'membershiprate':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Membership Rate');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Membership Rate'));
		$req 	= $socObj -> displayPageFromCMS(97);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'wishlistFAQ':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Wishlist Sample');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Wishlist FAQ'));
		$req 	= $socObj -> displayPageFromCMS(85);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;
	case 'whySOCwishlist':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Wishlist Sample');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Why SOC Wishlist'));
		$req 	= $socObj -> displayPageFromCMS(86);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;
	case 'wishlistyoutubeins':
			$req = $socObj -> displayPageFromCMS(87);
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Youtube Video Instruction'));
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Youtube Video Instruction');
			$smarty -> assign('keywords','Youtube Video Instruction');
			$smarty -> assign('req', $req);
			$content = $smarty -> fetch('about.tpl');
			$smarty -> assign('content', $content);
			$smarty->assign('hid_left_banner',0);
        $smarty->assign('hide_race_banner',1);
			$smarty->assign('is_content',1);
			$smarty->assign('sidebar',0);
			$smarty -> assign('sidebar_bg', '0');
		break;
	case 'socraceinfo':
			$req = $socObj -> displayPageFromCMS(89);
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Socraceinfo'));
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Socraceinfo');
			$smarty -> assign('keywords','Socraceinfo');
			$smarty -> assign('req', $req);
			$content = $smarty -> fetch('about.tpl');
			$smarty -> assign('content', $content);
			$smarty->assign('hid_left_banner',0);
        $smarty->assign('hide_race_banner',1);
			$smarty->assign('is_content',1);
			$smarty->assign('sidebar',0);
			$smarty -> assign('sidebar_bg', '0');
		break;
	case 'terms':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Terms of use');
		$smarty -> assign('keywords','Terms of use');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Terms of use'));
		$req 	= $socObj -> displayPageFromCMS(55);
		$smarty -> assign('req', $req);
		$_SESSION['logo_new'] = true;
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty->assign('page_group', "fanpromo");
		$smarty->assign('hide_responsive', true);
		$content =	$smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		break;
	case 'raceterms':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - The Ultimate SOC Race Terms & Conditions');
		$smarty -> assign('keywords','The Ultimate SOC Race Terms & Conditions');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('The Ultimate SOC Race Terms & Conditions'));
		$req 	= $socObj -> displayPageFromCMS(117);
		$smarty -> assign('req', $req);
		$_SESSION['logo_new'] = true;
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$content =	$smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		break;

	case 'privacy':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Privacy Policy');
		$smarty -> assign('keywords','Privacy Policy');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Privacy Policy'));
		$req 	= $socObj -> displayPageFromCMS(56);
		$smarty -> assign('req', $req);
		$_SESSION['logo_new'] = true;
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'fees':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Selling Fees');
		$smarty -> assign('keywords','Selling Fees');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Fee Schedule for all Food Marketplace Markets'));
		$req 	= $socObj -> displayPageFromCMS(109);
		$smarty -> assign('req', $req);
		$_SESSION['logo_new'] = true;
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'ads':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Advertisers');
		$smarty -> assign('keywords','Advertisers');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Advertisers'));
		$req 	= $socObj -> displayPageFromCMS(10);
		$smarty -> assign('req', $req);
		$_SESSION['logo_new'] = true;
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'statelink':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Advertisers');
		$smarty -> assign('keywords','Advertisers');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Advertisers'));
		$req 	= $socObj -> displayPageFromCMS(48);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		break;

	case 'thanks':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Acknowledgement');
		$smarty -> assign('keywords','Acknowledgement');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Acknowledgement'));
		$req 	= $socObj -> displayPageFromCMS(12);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		break;

	case 'police':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Policy');
		$smarty -> assign('keywords','Policy');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Policy'));
		$req 	= $socObj -> displayPageFromCMS(47);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'payments':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Payment Methods');
		$smarty -> assign('keywords','Payment Methods');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Payment Methods'));
		$req 	= $socObj -> displayPageFromCMS(37);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('cms_payment.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		//		$smarty -> assign('sidebar', 0);
		break;

	case 'features':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Features');
		$smarty -> assign('keywords','Features');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Features'));
		$req 	= $socObj -> displayPageFromCMS(38);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'flat':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Flat Rate Freedom');
		$smarty -> assign('keywords','Flat Rate Freedom');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Flat Rate Freedom'));
		$req 	= $socObj -> displayPageFromCMS(39);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'ourmis':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Our Missions');
		$smarty -> assign('keywords','Our Missions');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Our Missions'));
		$req 	= $socObj -> displayPageFromCMS(40);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'express':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Express');
		$smarty -> assign('keywords','Express');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Express'));
		$req 	= $socObj -> displayPageFromCMS(41);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'sample':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Sample Websites');
		$smarty -> assign('keywords','Sample Websites');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Sample Websites'));
		$req 	= $socObj -> displayPageFromCMS(42);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'buyer':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Buyer Beware');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Buyer Beware'));
		$req 	= $socObj -> displayPageFromCMS(44);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;
		/*	*/
	case 'faq':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - FAQ');
		$smarty -> assign('keywords','FAQ');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('FAQ'));
		$req 	= $socObj -> displayPageFromCMS(45);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'newfaq':
		$search_type	=	empty($_REQUEST['search_type']) ? 'store' : $_REQUEST['search_type'];
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Help');
		$smarty -> assign('keywords','FAQ');
		$objAdminHelp = new adminHelp();
		$keywords = $_REQUEST['helpkeywords'];
		if($keywords!=""&&strlen($keywords)<3){
			$req['helplist'] = null;
		}else{
			if (strlen(trim($keywords))>100){
				$keywords = substr($keywords,0,100);
			}
			$req['helplist'] =	$objAdminHelp -> getHelpItemlist($keywords);
		}
		if(get_magic_quotes_gpc()){
			$req['helpkeywords'] = stripslashes($keywords);
		}else{
			$req['helpkeywords'] = $keywords;
		}
		$req['urlkeyword'] = urlencode($req['helpkeywords']);
		$req['helpkeywords'] = htmlspecialchars($req['helpkeywords']);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('faq_form.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;
	case 'faqinfo':
		$smarty -> assign('keywords','FAQ');
		$objAdminHelp = new adminHelp();
		$keywords = $_REQUEST['helpkeywords'];
		if(strlen($keywords)<3){
			$keywords = "";
		}
		$req['helpinfo'] =	$objAdminHelp -> getHelpItemInfo($_REQUEST['id'],$keywords);
		$helpinfo =	$objAdminHelp -> getHelpItemInfo($_REQUEST['id']);
		$smarty -> assign('pageTitle','SOC Help - '.$helpinfo['title']);
		if(get_magic_quotes_gpc()){
			$req['helpkeywords'] = stripslashes($keywords);
		}else{
			$req['helpkeywords'] = $keywords;
		}
		$req['helpkeywords'] = htmlspecialchars($req['helpkeywords']);
		$smarty -> assign('req', $req);
		$smarty -> assign('isinfo', 1);
		$content =	$smarty -> fetch('faq_form.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
		break;
	case 'protection':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Protection');
		$smarty -> assign('keywords','Protection');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Protection'));
		$req 	= $socObj -> displayPageFromCMS(46);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
		$smarty->assign('hide_race_banner',1);
		break;

	case 'rumble':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Rumble');
		$smarty -> assign('keywords','Rumble');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Rumble In The Jungle'));
		$req 	= $socObj -> displayPageFromCMS(49);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'postit':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Post It');
		$smarty -> assign('keywords','Post It');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Be One of the First XV'));
		$req 	= $socObj -> displayPageFromCMS(50);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'yourhome':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Your Home');
		$smarty -> assign('keywords','Your Home');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Your Home - Always'));
		$req 	= $socObj -> displayPageFromCMS(51);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'arrows':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Arrows');
		$smarty -> assign('keywords','Arrows');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Connect with Your Local Community'));
		$req 	= $socObj -> displayPageFromCMS(52);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'boxer':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Boxer');
		$smarty -> assign('keywords','Boxer');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Win By Knockout'));
		$req 	= $socObj -> displayPageFromCMS(53);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;

	case 'auction':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Auction - Learn More');
		$smarty -> assign('keywords','Auction - Learn More');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Auction - Learn More'));
		$req 	= $socObj -> displayPageFromCMS(91);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('content', $content);
		break;
	case 'auctioninfo':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Auction Info');
		$smarty -> assign('keywords','Auction Info');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Auction Info'));
		$req 	= $socObj -> displayPageFromCMS(93);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
		break;

	case 'auctionmore':
    		$search_type = 'store';
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Welcome to SOC Auction System');
		$smarty -> assign('keywords','Welcome to SOC Auction System');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Welcome to SOC Auction System'));
		$req 	= $socObj -> displayPageFromCMS(92);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
		break;

	case 'nascar':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - NASCAR Auction Coming Soon');
		$smarty -> assign('keywords','NASCAR Auction Coming Soon');
		//$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Auction Coming Soon'));
		$req 	= $socObj -> displayPageFromCMS(94);
		$smarty -> assign('req', $req);
		$smarty->assign('nascar',1);
		$content =	$smarty -> fetch('nascar.tpl');
		$smarty->assign('is_content',1);
		$smarty->assign('sidebar',0);
		$smarty -> assign('content', $content);
		break;
		//display all category list
	case "category":
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Categories');
		$smarty -> assign('keywords','Categories');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Categories', 2));
		$req =	$socObj -> displayCategoryList(3);
		$smarty -> assign('req',	$req);
		$content	=	$smarty -> fetch('category_new.tpl');
		$smarty -> assign('pageTitle', 'Sell Goods Online - Sell Items Online - Flat Rate Selling');
		$smarty -> assign('keywords', $keywordsList);
		$smarty -> assign('description', 'FoodMarketplace lets you perform flat rate selling online. You can sell all your products online and reach the world.');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		//$smarty->assign('is_content',1);
		break;

	case "sitemap":
		$search_type	=	empty($_REQUEST['search_type']) ? 'store' : $_REQUEST['search_type'];
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Categories');
		$smarty -> assign('keywords','Sitemap');
		$req['sitemap'] = getSitemap();
		$smarty -> assign('req',	$req);
		$content	=	$smarty -> fetch('sitemap.tpl');
		$smarty -> assign('pageTitle', 'Site Map - Sell Goods Online - Sell Items Online - Flat Rate Selling');
		$smarty -> assign('keywords', $keywordsList);
		$smarty -> assign('description', 'FoodMarketplace lets you perform flat rate selling online. You can sell all your products online and reach the world.');
		$smarty -> assign('content', $content);
		$smarty->assign('is_home',1);
		$smarty->assign('sidebar',0);
		$smarty->assign('div','middle');
		$smarty->assign('hideLeftMenu',1);
		$smarty->assign('hidefootercat',1);
		break;

	case "error404":
		$search_type	=	empty($_REQUEST['search_type']) ? 'store' : $_REQUEST['search_type'];
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Categories');
		$smarty -> assign('keywords','Sitemap');
		$req['sitemap'] = getSitemap();
		$smarty -> assign('req',	$req);
		$content	=	$smarty -> fetch('error404.tpl');
		$smarty -> assign('pageTitle', 'Sell Goods Online - Sell Items Online - Flat Rate Selling');
		$smarty -> assign('keywords', $keywordsList);
		$smarty -> assign('description', 'FoodMarketplace lets you perform flat rate selling online. You can sell all your products online and reach the world.');
		$smarty -> assign('content', $content);
		$smarty->assign('is_home',1);
		$smarty->assign('sidebar',0);
		$smarty->assign('div','middle');
		$smarty->assign('hideLeftMenu',1);
		$smarty->assign('hidefootercat',1);
		break;

		//display product list
	case "prolist":
		if(isset($_REQUEST['sub_category'])&&$_REQUEST['sub_category']){
			$req			=	$socObj	-> displayCategoryProFilter($_REQUEST['sub_category']);
		}else{
			$req 			=	$socObj -> displayCategoryProduct();
		}
		$req['cid']		= 	$_REQUEST['id'];
		$req['sort']	= 	$_REQUEST['sort'];
		$req['pageno']	= 	isset($_REQUEST['pageno'])?$_REQUEST['pageno']:1;
		$req['select_subcategory'] = $_REQUEST['sub_category'];
		$req['subcat'] = $socObj->getsubCategoryById($_REQUEST['id']);
		$req['ad'] 		=	$socObj -> getProCategoryAd();
		$req['article'] =	$socObj -> getProCategoryArticleHead();
		$timelefts  = isset($_REQUEST['timelefts'])?$_REQUEST['timelefts']:"0";
		$buytypeState = isset($_REQUEST['buytypeState'])&&$_REQUEST['buytypeState']?$_REQUEST['buytypeState']:"'yes','no'";
		if(get_magic_quotes_gpc()){	$buytypeState = stripslashes($buytypeState);}
		$req['timelefts'] = $timelefts;
		$req['buytypeState'] = $buytypeState;

		$pros = $req['pro_nums']?"&nbsp;(".number_format($req['pro_nums'],0,'.',',').")":"";
		// rss of category
		if ($req['ad']['categoryCMS']){
			$req['titles'] =	$socObj -> getTextItemTitle($req[categoryName].$pros,5);
			$smarty -> assign('req',	$req);
			$content	=	$smarty -> fetch('categorysearch_adv.tpl');
			$smarty->assign('div','list_adv');
			$smarty -> assign('pageTitle',$req[categoryName]);
		}
		else
		{
			$req['titles'] =	$socObj -> getTextItemTitle($req[categoryName].$pros,6);
			$smarty -> assign('req',	$req);
			$content	=	$smarty -> fetch('categorysearch.tpl');
			$smarty->assign('div','list');
			$smarty -> assign('pageTitle',$req[categoryName]);
		}
		$smarty -> assign('pageTitle', 'Buy '.$req[categoryName].' Products Online: Food Marketplace Australia');
		$smarty -> assign('keywords', $keywordsList);
		$smarty -> assign('description', 'Looking for a '.$req[categoryName].' product? Food Marketplace Australia lists a wide range of '.$req[categoryName].' products.');
		$is_set_desc = true;
		$smarty -> assign('content', $content);
		$smarty->assign('sidebar',0);
		$smarty->assign('is_category',1);
		$smarty->assign('category',$_REQUEST['id']);
		break;

	case 'artlist':
		$req['article'] =	$socObj -> getProCategoryArticleHead($_REQUEST['cgid'],50);
		$req['categoryId']	=	$req['article']['categoryFID'];
		$req['categoryImage']	=	'yes';
		$smarty -> assign('req',	$req);
		$content	=	$smarty -> fetch('categorysearch_artlist.tpl');
		$smarty -> assign('content', $content);
		break;

	case 'article':
		$req['article'] =	$socObj -> getProCategoryArticle();
		$req['categoryId']	=	$req['article']['categoryFID'];
		$req['categoryImage']	=	'yes';
		$smarty -> assign('req',	$req);
		$content	=	$smarty -> fetch('categorysearch_article.tpl');
		$smarty -> assign('content', $content);

		break;

		//display product information
	case "dispro":
                require_once(SOC_INCLUDE_PATH.'/class.db.product.php');
                $dbProduct=new Product();
                $itemID = $dbProduct->CheckProduct($_GET['StoreID'], $_GET['proid']);
                if (!$itemID) {
                    exit("<script>alert('Product name does not exist.');location.href='/soc.php?cp=home';</script>");
                }
		$req = $socObj->displayStoreProduct(true);
		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('is_website', 1);
		//start shopper header
		$smarty->assign('headerInfo', $req['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header
		$aveRating	=	$socObj->getAveRating($_REQUEST['StoreID']);
		$req['aveRating'] = $aveRating;
		if($req['items']['product'][0]['isattachment']==1){
			if(isuserbuy($req['items']['product'][0]['pid'],$_SESSION['ShopID'])){
				$req['isuserbuy'] = 'yes';
			}
		}elseif ($req['items']['product'][0]['is_auction']=='yes'){
			$socbidObj = new socbidClass();
			$req	=	$socbidObj -> displayAuction();
			$req['audio'] = $socbidObj->getAudioList();
		}
		$prourl ="{$req['info']['bu_urlstring']}/{$req['items']['product'][0]['url_item_name']}";
		$imgulr =$req['items']['product'][0]['images']['mainImage'][0]['sname']['text'];
		$wg_price = "$ ".$req['items']['product'][0]['price'];
		if($req['info']['attribute']==3){$imgulr="/skin/red/images/logo-main.png";

		$languagePath	=	LANGPATH . '/job/index.php';
		if (file_exists($languagePath)) {
			require_once($languagePath);
		}
		$wg_price = $_LANG['val']['min_salary'][$req['items']['product'][0]['salaryMin']]. " - ";
		$wg_price .= $_LANG['val']['max_salary'][$req['items']['product'][0]['salaryMax']];
		}
		$rooturl = $normal_url;
		$homeurl = "$rooturl{$prourl}";
		$req['widgetHTML']="<div align=\"center\" style=\"width:230px;padding:10px;border:1px solid #ccc;background:#FFF;\"><a href=\"$homeurl\" target=\"_blank\" style=\"text-decoration:none\"><img src=\"$rooturl{$imgulr}\" width=\"100%\" border=\"0\"/></a>";
		$req['widgetHTML'].="<div align=\"left\" style=\"padding:5px;\" ><a href=\"$homeurl\" target=\"_blank\" style=\"text-decoration:none\"><strong style=\"color:#fcab26;font:12px arial, sans-serif;\">{$req['items']['product'][0]['item_name']}</strong></a></div>";
		$req['widgetHTML'].="<div align=\"left\" style=\"padding:5px;\"><strong style=\"font:12px arial, sans-serif;color:#777777;\">Price:</strong> <strong style=\"font:12px arial, sans-serif;color:#fe0002;\">".$wg_price."</strong></div></div>";
		$smarty -> assign('pageTitle',$req['items']['product'][0]['item_name']);
		$smarty -> assign('req',	$req);
		$smarty -> assign('titlesName', $socObj->getItemTitle('Items','txt',true));
		$smarty->assign('HostName',$_SERVER['HTTP_HOST']);
		//var_dump($_SESSION);
		$smarty->assign('UserID', $_SESSION['ShopID']);
		if(in_array($_REQUEST['StoreID'],$samplesiteid)){
			$smarty -> assign('is_samplestie',1);
		}

		$smarty -> assign('search_type', $req['info']['sellerType']);
		$search_type = $req['info']['sellerType'];

		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);

		if ($req['info']['attribute'] == 1) {
			$smarty -> loadLangFile('estate/index');
			$smarty -> assign('search_type','estate');
			$product_content = $smarty -> fetch('estate/template/estate-display-product.tpl');
			$smarty -> assign('content', $product_content);
		}elseif ($req['info']['attribute'] == 2) {
			$smarty -> loadLangFile('auto/index');
			$smarty -> assign('search_type','auto');
			$product_content = $smarty -> fetch('auto/template/auto-display-product.tpl');
			$smarty -> assign('content', $product_content);
		}elseif ($req['info']['attribute'] == 3) {
			$smarty -> loadLangFile('job/index');
			$smarty -> assign('search_type','job');
			$product_content = $smarty -> fetch('job/template/job-display-product.tpl');
			$smarty -> assign('content', $product_content);
		}else{

			if($req['items']['product'][0]['is_auction']=='yes'){

				$product_content = $smarty -> fetch('template/display_auction.tpl');
				$smarty -> assign('product_content', $product_content);
				$shop_summary = $smarty->fetch('template/auction_summary.tpl');
				$smarty->assign('content',$shop_summary);

			}else{
				$product_content = $smarty -> fetch('template/display_product.tpl');
				$smarty -> assign('product_content', $product_content);
				$shop_summary = $smarty->fetch('template/tmp-shop-summary.tpl');
				$smarty->assign('content',$shop_summary);
			}

			//$smarty->assign('div','shop');
		}
		//$smarty -> assign('is_content','1');
		$smarty -> assign('sidebar', 0);
		$smarty->assign('is_website',1);
		break;

		/**
	 * display auction detail information
	 */
	case "disauction":

		require_once(SOC_INCLUDE_PATH.'/xajax/xajax_core/xajax.inc.php');
		require_once(SOC_INCLUDE_PATH.'/functions.xajax.php');

		$xajax = new xajax();
		//		$xajax->configure('debug', true);
		$xajax->configure('javascript URI', './include/xajax/');

		$verifyBid =& $xajax->registerFunction('verifyBid');
		$verifyBid->setParameter(0, XAJAX_FORM_VALUES, 'bidform');

		$xajax->processRequest();
		$xajaxjs =  $xajax->getJavascript();

		$isLogin = isset($_SESSION['UserID']) ? 1 : 0;

		$req	=	$socbidObj -> displayAuction();
		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$pid		=	$_REQUEST['proid'] ? $_REQUEST['proid'] : '0' ;
		$winnerID	=	$socbidObj->getWinnerID($pid);
		$userinfo 	= 	$socbidObj->getUser($winnerID);

		$smarty ->assign('xajaxjs', $xajaxjs);
		$smarty ->assign('xajaxsubmit', $verifyBid->getScript());

		$smarty ->assign('isLogin', $isLogin);
		if (isset($_SESSION['level'])) {
			$smarty ->assign('userlevel', $_SESSION['level']);
		}

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('is_website', 1);
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$smarty -> assign('req',	$req);

		if ($winnerID == 0) {
			$smarty -> assign('nickname', ' Initial price');
		} else {
			$smarty -> assign('nickname', ' by '.$userinfo['bu_nickname']);
		}

		$smarty -> assign('titlesName', $socObj->getItemTitle('Items','txt',true));
		/*$content	=	$smarty -> fetch('template/style.tpl');
		$content	.=	$smarty -> fetch('template/display_auction.tpl');
		$smarty -> assign('content', $content);*/

		$product_content = $smarty -> fetch('template/display_auction.tpl');
		$smarty -> assign('product_content', $product_content);

		$shop_summary = $smarty->fetch('template/auction_summary.tpl');
		$smarty->assign('content',$shop_summary);
		$smarty -> assign('sidebar', 0);
		break;

		/**
	 * bid an auction
	 */
	case 'bid':
		$socbidObj->bid();
		exit();
		break;

		/**
	 * new review
	 */
	case 'newreview':
		if (!empty($_POST)) {
			$socbidObj->saveReview($_POST);
		} else {
			$req = $socbidObj->newReview();
			$smarty -> assign('req', $req);
			$smarty -> assign('titlesName', $socObj->getItemTitle('Items','txt',true));
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Reviews', 2));
			$content	=	$smarty -> fetch('template/style.tpl');
			$content	.=	$smarty -> fetch('template/new_review.tpl');
			$smarty -> assign('content', $content);
		}
		$smarty -> assign('sidebar', 0);
		break;

		/**
	 * dispaly reviews of store
	 */
	case 'disreview':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Reviews');
		$StoreID = intval(isset($_REQUEST['StoreID']) ? $_REQUEST['StoreID'] : 0);
		$pid = isset($_REQUEST['pid'])?$_REQUEST['pid']:0;

		$req = $socbidObj->displayReview($StoreID,$pid);

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Reviews', 4, $templateInfo['bgcolor']));
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('is_website', 1);
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);

                if($headerInfo['info']['attribute'] == 5) {
                    $smarty->assign('show_join_banner', 1);
                    $smarty->assign('show_season_banner', 1);
                }
                
		$req['template'] = $headerInfo['template'];
		$req['info']['subAttrib'] = $headerInfo['info']['subAttrib'];
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		if ($req['ownerType']=='user'){
			$tmp_header = '';
		}
		$search_type = $headerInfo['info']['attribute'] == 5 ? 'foodwine' : $search_type;

		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$smarty -> assign('req', $req);
		$content .=	$smarty -> fetch('template/display_review.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty->assign('isstorepage',1);
		
		break;
	case 'comment':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Reviews');
		if (empty($_SESSION['LOGIN'])){
			echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$socbidObj->addComment();
		} else {
			$req = array(
			'StoreID' => $_REQUEST['StoreID'],
			'rid' => $_REQUEST['rid'],
			'type' => $_REQUEST['type'],
			'storename' => getStoreByName($_REQUEST['StoreID']),
			'user_id' => $_SESSION['UserID']
			);

			$templateInfo = $socObj -> getTemplateInfo();
			$smarty -> assign('templateInfo', $templateInfo);
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Reviews', 7, $templateInfo['bgcolor'], '/soc.php?cp=lookupreview'));
			$req['info']['bu_name'] = getStoreByName($StoreID);
			$smarty -> assign('is_website', 1);
			//start shopper header
			$headerInfo = $socObj -> displayStoreWebside(true);
			$req['template'] = $headerInfo['template'];
			$smarty->assign('headerInfo', $headerInfo['info']);
			$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
			$smarty->assign('tmp_header', $tmp_header);
			//end shopper header

			$smarty -> assign('req', $req);
			$smarty -> assign('titlesName', $socObj->getItemTitle('Comment','txt',true));
			//$content	=	$smarty -> fetch('template/style.tpl');
			$content	.=	$smarty -> fetch('template/comment.tpl');
			$smarty -> assign('content', $content);
		}
		$smarty -> assign('sidebar', 0);
		$smarty->assign('isstorepage',1);
		break;
		/**
	 * lookup buyer's review
	 */
	case 'delreview':

		if (empty($_SESSION['LOGIN'])){
			echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
			exit;
		}

		$rid = isset($_REQUEST['rid']) ? intval($_REQUEST['rid']) : 0;
		$StoreID = isset($_REQUEST['StoreID']) ? intval($_REQUEST['StoreID']) : 0;

		if ($_SESSION['StoreID'] != $StoreID) {
			echo "<script>alert('Please come from the currect url.');history.go(-1);</script>";
			exit();
		}
		$res = $socreviewObj->delReview($rid);
		if ($res) {
			echo "<script>alert('Review delete successfully.');location.href='soc.php?cp=disreview&StoreID=$StoreID'</script>";
		} else {
			echo "<script>alert('Review delete failed.');history.go(-1);</script>";
		}

		exit();
		break;
	case 'lookupreview':

		$req = $socbidObj->lookupReview();
		$action = 'lookup';
		if (!empty($_POST) || !empty($_REQUEST)) {
			$buyername = isset($_POST['buyername']) ? $_POST['buyername'] : $_REQUEST['buyername'];
			$buyername = $socbidObj -> __StrReplace($buyername, false);
			$smarty->assign('buyername', $buyername);
		}
		if (!empty($_POST) || !empty($buyername)) {
			$smarty->assign('action', $action);
		}

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Lookup Buyers Review');
		$smarty -> assign('keywords','Lookup Buyers Review');
		$smarty -> assign('req', $req);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Lookup Buyers Review', 7, '', '/soc.php?act=offer&cp=offerlist'));
		$content	=	$smarty -> fetch('template/style.tpl');
		$content	.=	$smarty -> fetch('template/lookup_review.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		break;

		/**
	 * product review system
	 */
	case 'oreview':
		if (!empty($_POST)) {
			$socreviewObj->saveReview($_POST);
		} else {
			$req = $socreviewObj->newReview();
			$search_type = $req['is_foodwine'] ? 'foodwine' : $search_type;
			$headerInfo = $socObj -> displayStoreWebside(true, false, $req['info']['StoreID']);

                        if($headerInfo['info']['attribute'] == 5) {
                            $smarty->assign('show_join_banner', 1);
                            $smarty->assign('show_season_banner', 1);
                        }
                        
			$req['template'] = $headerInfo['template'];
			$smarty -> assign('req', $req);
                        $smarty->assign('pageTitle', 'Sell Goods Online - Write A Review');
			$smarty -> assign('titlesName', $socObj->getItemTitle('Items','txt',true));
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Write A Review',2));
			$content	=	$smarty -> fetch('template/style.tpl');
			$content	.=	$smarty -> fetch('template/new_oreview.tpl');
			$smarty -> assign('content', $content);
			$smarty -> assign('sidebar', 0);
			$smarty->assign('isstorepage',1);
		}
		break;

	case 'oreviewstore':
		break;

	case 'cgeton':
		header('Location:soc.php?cp=customers_geton&ctm=1');
		exit;
		break;

	case 'contact':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Contact Us');
		$smarty -> assign('keywords','Contact Us');
		$smarty->assign('itemTitle', $socObj->getTextItemTitle('Contact Us', 2));
		$smarty->assign('sidebar', 0);

                $type = array('Advertising', 'Report Seller', 'General Enquiry', 'Suggestion', 'Technical Support', 'Admin');
		if (!empty($_POST)){
                        if (strtolower($_POST['validation'])!=strtolower($_SESSION['authnum'])){
				$smarty->assign('msg',"Validation Code is invalid.");
                                if (get_magic_quotes_gpc()) {
                                    $_POST = stripslashes_deep($_POST);
                                }
                                array_walk($_POST,'html_decode');
                                $smarty->assign('tmpval',$_POST);
                                $smarty->assign('req', $req);

                                $smarty->assign('type', $type);
                                $content = $smarty->fetch('contact.tpl');
                                $smarty->assign('content', $content);
			}
                        else {
                            $smarty->assign('content', $socObj->mailFunContact());
                        }

		}else{
			if (get_magic_quotes_gpc()) {
				$_POST = stripslashes_deep($_POST);
			}
			array_walk($_POST,'html_decode');
			$smarty->assign('tmpval',$_POST);
			$smarty->assign('req', $req);

                        $smarty->assign('type', $type);
			$content = $smarty->fetch('contact.tpl');
			$smarty->assign('content', $content);
		}
		break;

    case 'reportseller':
        if(!isset($_SESSION['StoreID'])) {
            echo '<script type="text/javascript">';
            echo 'alert("You need to be a member of \"Food Marketplace\" to use this service. Register now, it\'s FREE.");';
            echo 'location.href="/soc.php?cp=customers_geton&ctm=1";';
            exit('</script>');
        }
        $storeInfo = $socObj->getStoreInfo($_REQUEST['StoreID']);
        $storeInfo['subAttribName']	= ($storeInfo['attribute'] == 1 || $storeInfo['attribute'] == 2) ?
                                      $GLOBALS['_LANG']['seller']['attribute'][$storeInfo['attribute']]['subattrib'][$storeInfo['subAttrib']]:($storeInfo['attribute']==3 ? 'Advertiser' : ($storeInfo['attribute']==5 ? 'Retailer' : 'Seller'));

		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$req['template'] = $headerInfo['template'];
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

                if($headerInfo['info']['attribute'] == 5) {
                    $smarty->assign('show_join_banner', 1);
                    $smarty->assign('show_season_banner', 1);
                }

		$search_type = $headerInfo['info']['sellerType'];
		$smarty->assign('pageTitle', 'Report this '.$storeInfo['subAttribName']);
		$smarty->assign('itemTitle', $socObj->getTextItemTitle('Report this '.$storeInfo['subAttribName'], 2));
		$smarty->assign('sidebar', 0);
        $smarty->assign('is_content', 1);
		$smarty->assign('isstorepage',1);
        $smarty->assign('keywords', 'Report this '.$storeInfo['subAttribName']);
        $req['store'] = $storeInfo;
		if (!empty($_POST) && strtolower($_POST['validation'])==strtolower($_SESSION['authnum'])){
            $data = array(
                'sellerName'=>$storeInfo['bu_name'],
                'sellerEmail'=>$storeInfo['bu_email'],
                'sellerPhone'=>$storeInfo['bu_area'].' '.$storeInfo['bu_phone'],
                'sellerUrl'=>'http://'.$_SERVER['HTTP_HOST'].'/'.$storeInfo['bu_urlstring'],
                'complainantName'=>$_POST['your_name'],
                'complainantEmail'=>$_POST['Email'],
                'complainantComment'=>$_POST['Comments'],
                'to'=>'ronald.xian@infinitytesting.com.au',
                'storeType'=>$storeInfo['subAttribName']
            );
			$smarty->assign('content', $socObj->sendReportSellerMail($data));
			$smarty->assign('req', $req);
			$smarty->assign('isstorepage',1);
		}else{
			if (!empty($_POST) && $_POST['validation']!=$_SESSION['authnum']){
				$smarty->assign('msg',"Validation Code is invalid.");
			}

			if (get_magic_quotes_gpc()) {
				$_POST = stripslashes_deep($_POST);
			}

            if(!isset($_POST['Email']) && isset($_SESSION['email'])){
                $_POST['Email'] = $_SESSION['email'];
            }

            if(!isset($_POST['your_name']) && isset($_SESSION['NickName'])){
                $_POST['your_name'] = $_SESSION['NickName'];
            }

			array_walk($_POST, 'html_decode');
			$smarty->assign('tmpval',$_POST);
			$smarty->assign('req', $req);
			$content = $smarty->fetch('reportseller.tpl');
			$smarty->assign('content', $content);
		}
		break;

	case 'protectionform':
		$smarty->assign('itemTitle', $socObj->getTextItemTitle('Protection Form', 2));
		$smarty->assign('sidebar', 0);
		if (!empty($_POST))
		{
			if (strtolower($_POST['validation'])!=strtolower($_SESSION['authnum'])){
				$smarty->assign('msg',"Validation Code is invalid.");
                if (get_magic_quotes_gpc()) {
                    $_POST = stripslashes_deep($_POST);
                }
                array_walk($_POST,'html_decode');
                $smarty->assign('tmpval',$_POST);
                $smarty->assign('req', $req);

                $smarty->assign('type', $type);
                $content = $smarty->fetch('protection_form.tpl');
                $smarty->assign('content', $content);
			} else {
				$smarty->assign('content', $socObj->mailFun('contact'));
			}			
		}
		else
		{
			$smarty->assign('req', $req);
			$content = $smarty->fetch('protection_form.tpl');
			$smarty->assign('content', $content);
		}
		break;

	case 'shopdes':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - About Seller');
		$tmp['TemplateName'] = 'tmp-n-a';
		if (checkFreeSeller($_REQUEST['StoreID'])){
			echo "<script>alert('Sorry. You don\'t have permission to access this page.'); location.href='soc.php?cp=home';</script>";
			exit;
		}
		$req = $socObj->_displayStoreInfo($tmp,$_REQUEST['StoreID']);
		$req['info']['bu_name'] = $req['bu_name'];
		
		if($req['attribute'] == 5) {
			$smarty->assign('show_join_banner', 1);
			$smarty->assign('show_season_banner', 1);
		}

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle($req['bu_name'], 4, $templateInfo['bgcolor']));
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$search_type = $headerInfo['info']['sellerType'];
		
		$req['template'] = $headerInfo['template'];
		$req['info']['StoreID'] = $_REQUEST['StoreID'];
		
		$smarty->assign('req', $req);
		$content = $smarty -> fetch('website_detail.tpl');
		$smarty->assign('content', $content);
		$smarty->assign('sidebar',0);
		$smarty->assign('is_website',1);
		$smarty->assign('isstorepage',1);
		break;

	case 'map':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Sellers Location');
		$tmp['TemplateName'] = 'tmp-n-a';
		if (checkFreeSeller($_REQUEST['StoreID'])){
			echo "<script>alert('Sorry. You don\'t have permission to access this page.'); location.href='soc.php?cp=home';</script>";
			exit;
		}
		$req = $socObj->_displayStoreInfo($tmp,$_REQUEST['StoreID']);

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Location Map', 4, $templateInfo['bgcolor']));
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('is_website', 1);
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);

                if($headerInfo['info']['attribute'] == 5) {
                    $smarty->assign('show_join_banner', 1);
                    $smarty->assign('show_season_banner', 1);
                }

                $req['template'] = $headerInfo['template'];
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header
		$search_type = $headerInfo['info']['sellerType'];

		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('location_map.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('sidebar',0);
		$smarty->assign('isstorepage',1);
		$smarty->assign('onLoad',"load();showAddress('".$_REQUEST['key']."')");
		break;
		//display product informations of store
	case "disprolist":
		$headerInfo = $socObj -> displayStoreWebside(true);
		$req	=	$socObj -> displayStoreProductMore();
		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('pageTitle',$req['info']['bu_name'].' @ Food Marketplace');
		$timelefts  = isset($_REQUEST['timelefts'])?$_REQUEST['timelefts']:"0";
		$buytypeState = isset($_REQUEST['buytypeState'])&&$_REQUEST['buytypeState']?$_REQUEST['buytypeState']:"'yes','no'";
		if(get_magic_quotes_gpc()){	$buytypeState = stripslashes($buytypeState);}
		$req['timelefts'] = $timelefts;
		$req['buytypeState'] = $buytypeState;
		//$smarty -> assign('is_website', 1);
		//start shopper header
		//$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $req['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		$req['template'] = $headerInfo['template'];
		$smarty -> assign('req',	$req);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle($req['info']['bu_name'], 4,$req['bgcolor']));

		if ($req['tpl_type'] > 1) {
			$smarty -> loadLangFile($req['info']['sellerType'] . '/index');
		}else {
			$strTplPath		=	'';
		}
		$search_type = $req['info']['sellerType'];
		$smarty->assign('regetStoreID',$_REQUEST['StoreID']);
		$content = $smarty -> fetch('template/display_product_list.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('div','rightCol');
		$smarty->assign('sidebar',0);
		$smarty->assign('is_website',1);
		$smarty->assign('isstorepage',1);
        $smarty->assign('noShowGalleryBanner', true);
		break;

		//display product's descripation
	case "disprodes":
		$req	=	$socObj -> displayStoreProduct();

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle($req['info']['bu_name'], 4, $templateInfo['bgcolor']));
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('is_website', 1);

		//start shopper header
		//		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $req['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$search_type = $req['info']['sellerType'];

		$smarty -> assign('req',	$req);
		$content = $smarty -> fetch('template/display_product_des.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('sidebar',0);
		$smarty->assign('is_website',1);
		break;

	case 'friend':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Email to a friend');
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!$StoreID){
			header('Location:soc.php?cp=home');
			exit;
		}
		$smarty->assign('sidebar', 0);
		$req 	= $socObj->emailToFriend($StoreID,($_REQUEST['pid']?$_REQUEST['pid']:0));

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty->assign('itemTitle', $socObj->getTextItemTitle('Email to a friend', 4, $templateInfo['bgcolor']));
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('is_website', 1);

		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);

                if($headerInfo['info']['attribute'] == 5) {
                    $smarty->assign('show_join_banner', 1);
                    $smarty->assign('show_season_banner', 1);
                }

		$req['template'] = $headerInfo['template'];
		$smarty->assign('headerInfo', $headerInfo['info']);
		$smarty -> assign('req', $req);

		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header
		$search_type = $headerInfo['info']['sellerType'];


		//$smarty -> assign('itemTitle', $socObj->getItemTitle('Email to a Friend','txt',true));
		$content =	$smarty -> fetch('email_friend.tpl');
		//$smarty->display('email_friend.tpl');
		//exit;
		$smarty -> assign('content', $content);
		$smarty->assign('isstorepage',1);
		break;

		//for blog
	case 'blog':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Blog');
		if (!$StoreID){
			header('Location:soc.php?cp=home');
			exit;
		}
		if (checkFreeSeller($_REQUEST['StoreID'])){
			echo "<script>alert('Sorry. You don\'t have permission to access this page.'); location.href='soc.php?cp=home';</script>";
			exit;
		}
		$pageid = (isset($_REQUEST['pageid']) && $_REQUEST['pageid'] != '') ? $_REQUEST['pageid'] : '1';
		$req 	= $socObj -> getBlogArticleList($pageid);

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$req['info']['bu_name'] = getStoreByName($StoreID);
		
		$smarty -> assign('is_website', 1);
                
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$req['info']['subAttrib'] = $headerInfo['info']['subAttrib'];
		
                if($headerInfo['info']['attribute'] == 5) {
                    $smarty->assign('show_join_banner', 1);
                    $smarty->assign('show_season_banner', 1);
                }
                
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$search_type = $headerInfo['info']['sellerType'];

		$req['template'] = $headerInfo['template'];
		$smarty->assign('req', $req);
		$bloginfo = $socObj -> getBlogInfo($StoreID);
		$smarty->assign('itemTitle', $socObj->getTextItemTitle($bloginfo['name'],  4, $templateInfo['bgcolor']));
		if (count($req['blogitem'])>0){
			$smarty->assign('hasRss','blog');
		}
		$content = $smarty->fetch('blog.tpl');
		$smarty->assign('content', $content);
		$smarty->assign('sidebar', 0);
		$smarty->assign('isstorepage',1);
		
		break;

	case 'blogpage':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Blog');
		if (!$StoreID){
			header('Location:soc.php?cp=home');
			exit;
		}

		$ID     = $_REQUEST['pageid'] ? $_REQUEST['pageid'] : '1';
		$blogid = $_REQUEST['bid'] ? $_REQUEST['bid'] : '';

		if (!$blogid){
			header("Location: soc.php?cp=bloglist&StoreID=$StoreID");
			exit;
		}

		$bloginfo = $socObj->getBlogInfo();
		$req = $socObj -> dispBlogArticle($blogid, $ID);
		$req['login']   = ($_SESSION['LOGIN'] == 'login') ? 'true' : 'false';
		$req['StoreID'] = $_REQUEST['StoreID'];

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty->assign('itemTitle', $socObj->getTextItemTitle($bloginfo['name'], 4, $templateInfo['bgcolor']));
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('is_website', 1);
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
                if($headerInfo['info']['attribute'] == 5) {
                    $smarty->assign('show_join_banner', 1);
                    $smarty->assign('show_season_banner', 1);
                }
		$req['template'] = $headerInfo['template'];
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$search_type = $headerInfo['info']['sellerType'];

		$smarty->assign('req', $req);
		$smarty->assign('hasRss','blog');
		$content = $smarty->fetch('blog_page.tpl');
		$smarty->assign('content', $content);
		$smarty->assign('sidebar', 0);
		$smarty->assign('isstorepage',1);
		break;

	case 'bcomment':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Blog');
		if ($_SESSION['UserID']==''){
			header('Location:soc.php?cp=home');
			exit;
		}

		$blogid = $_REQUEST['bid'] ? $_REQUEST['bid'] : '';

		if ($blogid){

			$socObj -> blogCommentAddOrEditOperate();
			$socObj -> destroyFormInputVar();
			if ($_REQUEST['act']=='appr'){
				header("Location:soc.php?cp=blogpage&StoreID=".$_REQUEST['StoreID']."&bid=$blogid&pageid=1");
			}elseif($_REQUEST['act']=='del'){
				echo "<script>alert('Comment deleted successfully. ');location.href='soc.php?cp=blogpage&StoreID=".$_REQUEST['StoreID']."&bid=$blogid&pageid=1';</script>";
			}else{
				$bu_name = getStoreByName($_REQUEST['StoreID']);
				echo "<script>alert(\"Your comment has been submitted for approval by $bu_name.\");location.href='soc.php?cp=blogpage&StoreID=".$_REQUEST['StoreID']."&bid=$blogid&pageid=1';</script>";
			}
			exit;

		}else{

			header("Location: soc.php?act=admin");
			exit;

		}
		break;

	case 'blogedit':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Blog');
		if ($_SESSION['UserID'] == '' && $_SESSION['level'] != 1){
			header('Location:index.php');
			exit;
		}
		if (!empty($_REQUEST['act'])){
			switch ($_REQUEST['act']){
				case 'del':
					if($socObj->delbolgbyid($_REQUEST['bid'])){
						echo "<script>
								alert('Bolg deleted successfully.');
							 	location.href='/soc.php?cp=blog&pageid=1';
							 </script>";
						exit();
					}else{
						header('Location:/soc.php?cp=blog&pageid=1');
						exit();
					}
					break;
				default:
					if ($socObj->blogArticleAddOrEditOperate()){
						$socObj->destroyFormInputVar();
						header('Location:soc.php?cp=blog&pageid=1');
						exit;
					}
					break;
			}
		}
		$bloginfo = $socObj->getBlogInfo($StoreID);
		$req 	= $socObj->blogArticleAddOrEdit();

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty->assign('itemTitle', $socObj->getTextItemTitle($bloginfo['name'], 4, $templateInfo['bgcolor']));
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('is_website', 1);
		
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$search_type = $req['info']['sellerType'];

		$smarty-> assign('req', $req);
		$content =	$smarty->fetch('blog_edit.tpl');
		$smarty->assign('content', $content);
		$smarty->assign('sidebar', 0);
		break;

	case "reply":
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Blog');
		if ($_SESSION['UserID']=='' && $_SESSION['level'] !=1){
			header('Location: index.php');
			exit;
		}
		$blogid = $_REQUEST['bid'] ? $_REQUEST['bid'] : '';
		$act = $_REQUEST['act'];
		if (!empty($act)) {
			if ($socObj -> ownerReplyOperate()) {
				$socObj -> destroyFormInputVar();
				if ($act=='del'){
					echo "<script>alert('Reply deleted successfully. ');location.href='soc.php?cp=blogpage&StoreID=".$_REQUEST['StoreID']."&bid=$blogid&pageid=1';</script>";
				}else{
					echo "<script>alert('Your reply added successfully. ');location.href='soc.php?cp=blogpage&StoreID=".$_REQUEST['StoreID']."&bid=$blogid&pageid=1';</script>";
				}
				exit;
			}
		}

		$bloginfo = $socObj -> getBlogInfo($StoreID);
		$req 	= $socObj -> blogReply();

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty->assign('itemTitle', $socObj->getTextItemTitle($bloginfo['name'], 4, $templateInfo['bgcolor']));
		$req['info']['bu_name'] = getStoreByName($StoreID);
		$smarty -> assign('is_website', 1);
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header
		$search_type = $headerInfo['info']['sellerType'];

		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('blog_reply.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('sidebar',0);
		break;

	case "blogrss":
		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!$StoreID){
			header('Location:soc.php?cp=home');
			exit;
		}
		if (checkFreeSeller($_REQUEST['StoreID'])){
			echo "<script>alert('Sorry. You don\'t have permission to access this page.'); location.href='soc.php?cp=home';</script>";
			exit;
		}
		header("Content-Type: text/xml; charset=utf-8");

		$arrBlogRss = $socObj -> getBlogRssArray(100);
		$smarty -> assign('req', 	$arrBlogRss);
		$smarty -> display('rss_blogandproduct.tpl');
		unset($socObj);
		exit;
		break;

	case "productrss":
		$StoreID	=	$_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if(!$socObj->checkwebsiteinvaild($StoreID)){
			echo "<script>alert('Website name does not exist.');location.href='/soc.php?cp=home';</script>";
			exit;
		}
		if (!$StoreID){
			header('Location:soc.php?cp=home');
			exit;
		}
		if (checkFreeSeller($_REQUEST['StoreID'])){
			echo "<script>alert('Sorry. You don\'t have permission to access this page.'); location.href='soc.php?cp=home';</script>";
			exit;
		}

		$arrStoreInfo	=	$socObj -> _displayStoreInfo( $arrTemp, $StoreID );
		if ($arrStoreInfo['attribute'] == 1) {
			header("location:/estate/?cp=productrss&StoreID=$StoreID");
		}elseif ($arrStoreInfo['attribute'] == 2) {
			header("location:/auto/?cp=productrss&StoreID=$StoreID");
		}elseif ($arrStoreInfo['attribute'] == 3) {
			header("location:/job/?cp=productrss&StoreID=$StoreID");
		}else{
			header("Content-Type: text/xml; charset=utf-8");
			$arrProductRss = $socObj->getProductRssData(100);
			$smarty -> assign('req', 	$arrProductRss);
			$smarty -> display('rss_blogandproduct.tpl');
			unset($socObj);
			exit;
		}
		break;

	case "recipes":
		$rid = $_REQUEST['rid'];
		$preview = $_REQUEST['preview'];
		$get_last_one = $rid ? false : true;
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');

		if (!$preview) {
			include_once(SOC_INCLUDE_PATH.'/class.FoodWine.php');
			$foodWine = new FoodWine();
			$req['info'] = $foodWine->getRecipeInfo($rid, $StoreID, $get_last_one, false, false, true);
			$rid = $rid ? $rid : $req['info']['id'];
			$req['pre_info'] = $foodWine->getRecipeInfo($rid, $StoreID, false, false, true, true);
			$req['next_info'] = $foodWine->getRecipeInfo($rid, $StoreID, false, true, false, true);
			if(empty($req['info'])){
				echo "<script>alert('No recipes have been posted.');history.go('-1');</script>";
				exit;
			}
		} else {
			$req['info'] = $_SESSION['recipe_info'];
		}
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);

		$req['preview'] = $preview;
		$req['template'] = $headerInfo['template'];
		$req['info']['bu_name'] = $headerInfo['info']['bu_name'];
		$smarty->assign('req', $req);

		$search_type = $headerInfo['info']['sellerType'];
		$smarty->assign('sidebar',0);
		$smarty->assign('noShowGalleryBanner', true);
		$smarty->assign('noShowTvBanner', true);
		$pageTitle = 'Recipes';
		$itemTitle = $headerInfo['info']['bu_name'].'\'s Recipes';
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle($itemTitle, 4, $templateInfo['bgcolor']));
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Recipes');
		$smarty -> assign('keywords','Recipes');
                $smarty->assign('is_content', 1);
                $smarty->assign('is_website', 1);
                $smarty->assign('isstorepage',1);
                $smarty->assign('show_join_banner', 1);
                $smarty->assign('show_season_banner', 1);
		$content = $smarty->fetch('foodwine/seller_recipe.tpl');
		$smarty -> assign('content', $content);

		break;

	case "categoryrss" :
		$req = $socObj->displayCategoryProduct(true);
		$req['pubDate'] =  time() + $socObj->time_zone_offset;
		$req['lastBuildDate'] =  time() + $socObj->time_zone_offset;

		header("Content-Type: text/xml; charset=utf-8");
		$smarty -> assign('req', 		$req);
		$smarty -> display('rss_category_list.tpl');
		unset($socObj);
		exit;
		break;

	case 'buy':
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if(!$socObj->checkwebsiteinvaild($StoreID)){
			echo "<script>alert('Website name does not exist.');location.href='/soc.php?cp=home';</script>";
			exit;
		}
		$_SESSION['StoreID'] = $StoreID;
		
		
		$free_signup_query = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$StoreID."'";
		$dbcon->execute_query($free_signup_query);
		$free_signup_result = $dbcon->fetch_records();
		$free_signup = false;
		if (isset($free_signup_result[0])) {
			$free_signup_data = $free_signup_result[0];
			if ($free_signup_data['free_signup'] == 1) {
				$free_signup = true;
				$payment_options_query = "SELECT * FROM aus_soc_payment_options op INNER JOIN aus_soc_payment_store_options sop ON sop.payment_option = op.option_id AND store_id = '".$StoreID."'";
				$dbcon->execute_query($payment_options_query);
				$payment_option_result = $dbcon->fetch_records();
				$payment_options = array();
				if (!empty($free_signup_data['bu_paypal'])) {
					$payment_options[] = array('name' => 'Paypal', 'value' => 'paypal', 'image' => '/skin/red/images/payment/paypal.png');
				}
				foreach($payment_option_result as $option_data) {
					$payment_options[] = array('id' => $option_data['option_id'], 'name' => $option_data['option_name'], 'value' => $option_data['option_value'], 'image' => $option_data['option_image']);
				}
				$smarty->assign('payment_options', $payment_options);
			}
		}
		$smarty->assign('free_signup', $free_signup);
		
		if (!$_SESSION['ShopID']){
			echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		if(isset($_REQUEST['coupon_op'])&&isset($_REQUEST['coupon_code'])){
			$pid  = $_REQUEST['pid'];
			if($_REQUEST['coupon_op']=="add"){
				if($couponInfo = $socstoreObj->getCodeProduct($_SESSION['ShopID'],$StoreID,$pid,$_REQUEST['coupon_code'])){
					unset($_SESSION['couponInfo']);
					$_SESSION['couponInfo']['StoreID'] 	 		= $couponInfo['StoreID'];
					$_SESSION['couponInfo']['UserID']  	 		= $couponInfo['UserID'];
					$_SESSION['couponInfo']['pid']  	 		= $couponInfo['pid'];
					$_SESSION['couponInfo']['offer']  	 		= $couponInfo['offer'];
					$_SESSION['couponInfo']['quantity']  		= $couponInfo['quantity'];
					$_SESSION['couponInfo']['coupon_code']  	= $couponInfo['coupon_code'];
					$_SESSION['couponInfo']['postage']  		= $couponInfo['postage'];
					$_SESSION['couponInfo']['shipping_method']  = $couponInfo['shipping_method'];
					$_SESSION['couponInfo']['isoversea'] 		= $couponInfo['isoversea'];
					$_SESSION['couponInfo']['shipping']  		= $couponInfo['postage']*$couponInfo['quantity'];
				}else{
					unset($_SESSION['couponInfo']);
					$_SESSION['couponMsg'] = "Coupon code \"{$_REQUEST['coupon_code']}\" is not valid.";
				}
			}elseif ($_REQUEST['coupon_op']=="delete"){
				unset($_SESSION['couponInfo']);
			}
			header('Location: /soc.php?cp=buy&StoreID='.$StoreID.'&pid='.$pid);
			exit();
		}

		if($_SESSION['couponMsg']){
			$smarty->assign('couponMsg',$_SESSION['couponMsg']);
			unset($_SESSION['couponMsg']);
		}
		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('is_website', 1);
		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$req 	= $socObj->selectPayment();
    	$req['template'] = $headerInfo['template'];
		$req['info'] = $socObj -> _displayStoreInfo($temp,$StoreID);
		if(isset($_SESSION['couponInfo'])){
			$smarty->assign('couponInfo',$_SESSION['couponInfo']);
			$req['price'] 	= $_SESSION['couponInfo']['offer'];
			$req['postage'] = $_SESSION['couponInfo']['postage'];
			$req['total'] 	= $_SESSION['couponInfo']['quantity']*$_SESSION['couponInfo']['postage']+$_SESSION['couponInfo']['quantity']*$_SESSION['couponInfo']['offer'];
		}
		
		$smarty -> assign('pageTitle',$req['item_name']);
		$req['info']['bu_name'] = getStoreByName($StoreID);
		//var_dump($req);
		$smarty->assign('itemTitle', $socObj->getTextItemTitle('Buy this item', 4, $templateInfo['bgcolor']));
		$smarty -> assign('req', $req);
		$content =	$smarty->fetch('buy.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('div','list');
		$smarty->assign('sidebar',0);
		$smarty->assign('isstorepage',1);
		break;

	case 'payment':

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Payment');
		$smarty -> assign('keywords','Payment');
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!$StoreID){
			echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		$pid = $_REQUEST['pid'];
		$isbid = $_REQUEST['isbid'];
		$ref_id = $_REQUEST['refid'];

		$productlink = 'soc.php?cp=dispro&StoreID='.$StoreID.'&proid='.$pid;
		$store_info = $socObj->_displayStoreInfo($temp,$StoreID);

		if ($isbid != '1') {
			$retval = 1;
			$socObj->dbcon->beginTrans();

			$intQuantityAtSys = $socObj->getStockQuantityByPid($pid);
			if (!$intQuantityAtSys || $intQuantityAtSys < $_REQUEST['quantity']){
				$retval = 0;
			}elseif (!$socObj->setStockQuantityByPid($pid, $_REQUEST['quantity'])) {
				$retval = 0;
			}

			if ($retval == 0) {
				$socObj->dbcon->rollbackTrans();
				$socObj->dbcon->endTrans();
				$intQuantityAtSys = $socObj->getStockQuantityByPid($pid);
				if ($intQuantityAtSys>0){
					echo "<script>alert('The quantity should not be larger than ".$intQuantityAtSys.". Please try again.');location.href='".$productlink."'</script>";
				}else{
					echo "<script>alert('Items out of stock.');location.href='".$productlink."'</script>";
				}
				exit;
			} else {
				$socObj->dbcon->commitTrans();
				$socObj->dbcon->endTrans();
			}
		}

		// send confirm email
		$sql = "select * from ".$socObj->table."product where pid=$pid";
		$dbcon->execute_query($sql);
		$productInfo = $dbcon->fetch_records();
		$productInfo = $productInfo[0];
		$reviewKey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
		$arrPayment = array('check'=>'Check','mo'=>'Other','bank_transfer'=>'Bank Transfer','cash'=>'Cash', 'cod'=>'COD', 'cash_on_pickup'=>'Cash on Pickup');
		$arrSetting = array(
		'pid'		=> $_REQUEST['pid'],
		'buyer_id'	=> $_SESSION['ShopID'],
		'StoreID'	=> $StoreID,
		'reviewkey'	=> $reviewKey,
		'p_status'	=> 'order',
		'description'=>$arrPayment[$_REQUEST['payment']],
		'order_date' =>time(),
		'type'		=> 'purchasing',
		'amount'	=> number_format(($_REQUEST['price'] +$_REQUEST['postage']) * $_REQUEST['quantity'],2,'.',''),
		'month'		=> $_REQUEST['quantity']
		);
		if(isset($_REQUEST['dest'])){
			if($_REQUEST['overcountry']==1){
				/***/
				$shipping_method = $store_info['bu_delivery_text'][$_REQUEST['deliveryMethod']]."(Overseas)";
			}else{
				$shipping_method = $store_info['bu_delivery_text'][$_REQUEST['deliveryMethod']];
			}
			$shipping_cost = $_REQUEST['shipping'];
		}else{
			$shipping_method = $store_info['bu_delivery_text'][$_REQUEST['deliveryMethod']];
			$shipping_cost = $_REQUEST['shipping'];
		}

		$arrSetting['product_code'] = $_REQUEST['product_code'];
		$arrSetting['shipping_method'] = $shipping_method;
		$arrSetting['shipping_cost'] = number_format($shipping_cost,2,'.','');
		$arrSetting['item_name'] = $_REQUEST['item_name'];
		$arrSetting['price'] = number_format($_REQUEST['price'],2);
		if ($isbid != '1'){
			$socObj->dbcon->insert_record($socObj->table.'order_reviewref',$arrSetting);
			$ref_id = $socObj->dbcon->lastInsertId();
			$socObj->orderSendMail($pid,$_SESSION['ShopID'],$StoreID,$reviewKey,$productInfo['item_name'],$_REQUEST['price'],$_REQUEST['quantity'],$_REQUEST['shipping'],$productInfo['p_code'],$_REQUEST['payment']);
		}else{
			// processing of auction item
			$price = $socObj->dbcon->execute_query("select price from {$socObj->table}order_reviewref where ref_id=$ref_id");
			$price = $socObj->dbcon->fetch_records();
			$price = $price[0];
			$arrShippingCost = array(
				'amount'			=> number_format($price['price']+$arrSetting['shipping_cost'],2,'.',''),
				'shipping_cost'		=> $arrSetting['shipping_cost'],
				'description'		=> $arrPayment[$_REQUEST['payment']],
				'shipping_method'	=> $arrSetting['shipping_method']
			);
			$socObj->dbcon->update_record($socObj->table."order_reviewref",$arrShippingCost,"where ref_id='$ref_id'");
			$socObj->orderSendMail($pid,$_SESSION['ShopID'],$StoreID,'temp',$productInfo['item_name'],$_REQUEST['price'],$_REQUEST['quantity'],$_REQUEST['shipping'],$productInfo['p_code'],$_REQUEST['payment']);
		}
		
		if ($_REQUEST['payment'] == 'cheque' ||  $_REQUEST['payment'] == 'bank_transfer' || $_REQUEST['payment'] == 'visa' || $_REQUEST['payment'] == 'mastercard') {
			$socstoreObj->updateCouponCode($_SESSION['couponInfo']['UserID'],$_SESSION['couponInfo']['StoreID'],$_SESSION['couponInfo']['pid'],$_SESSION['couponInfo']['coupon_code']);
			unset($_SESSION['couponInfo']);
			header("Location: soc.php?cp=message&StoreID=$StoreID&msg=Please contact this seller directly.".$link);
			exit;
		}

		// if ($_REQUEST['payment']=='check' or $_REQUEST['payment']=='mo' or $_REQUEST['payment']=='cash' or $_REQUEST['payment']=='bank_transfer' or 'cod' == $_REQUEST['payment'] or 'cash_on_pickup' == $_REQUEST['payment']){
			// $socstoreObj->updateCouponCode($_SESSION['couponInfo']['UserID'],$_SESSION['couponInfo']['StoreID'],$_SESSION['couponInfo']['pid'],$_SESSION['couponInfo']['coupon_code']);
			// unset($_SESSION['couponInfo']);
			// header("Location: soc.php?cp=message&StoreID=$StoreID&msg=Please contact this seller directly.".$link);
			// exit;
		// }
		// credit card payment method is removed, if add again, plz check the following code for auction
		// $month	=	getExpMonth($_REQUEST['month']);
		// $year	=	getExpYear($_REQUEST['year']);
		// $req 	= array(
		// 'pid' => $_REQUEST['pid'],
		// 'StoreID' => $_REQUEST['StoreID'],
		// 'price' => $_REQUEST['price'],
		// 'postage' => $_REQUEST['postage'],
		// 'shipping' => $_REQUEST['shipping'],
		// 'quantity' => $_REQUEST['quantity'],
		// 'credit' => $_REQUEST['payment'],
		// 'pid' => $_REQUEST['pid'],
		// 'amount' => $_REQUEST['quantity']*$_REQUEST['price']+$_REQUEST['quantity']*$_REQUEST['postage'],
		// 'payment' => $_REQUEST['payment'],
		// 'month' => $month,
		// 'year' => $year,
		// 'ref_id' => $ref_id
		// );

		// $templateInfo = $socObj -> getTemplateInfo();
		// $smarty -> assign('templateInfo', $templateInfo);
		// $smarty -> assign('is_website', 1);

		// //start shopper header
		// $headerInfo = $socObj -> displayStoreWebside(true);
		// $smarty->assign('headerInfo', $headerInfo['info']);
		// $tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		// $smarty->assign('tmp_header', $tmp_header);
		// //end shopper header

		// $smarty -> assign('req', $req);
		// $smarty -> assign('itemTitle', $socObj->getTextItemTitle('Payment', 4, $templateInfo['bgcolor']));
		// $content =	$smarty->fetch('payment_credit.tpl');
		// $smarty -> assign('content', $content);
		// $smarty -> assign('sidebar', 0);
		break;

	case 'paypal':
		include_once(SOC_INCLUDE_PATH.'/class.adminpayment.php');
		include_once(SOC_INCLUDE_PATH.'/class.paymentadaptive.php');
		$adaptive = new PaymentAdaptive();
		$adminpayment = new adminPayment();

		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$pid = $_REQUEST['item_number'];
		$isbid = $_REQUEST['isbid'];
		$ref_id = $_REQUEST['refid'];

		$productlink = 'soc.php?cp=dispro&StoreID='.$StoreID.'&proid='.$pid;
		$store_info = $socObj->_displayStoreInfo($temp,$StoreID);

		if ($isbid != '1'){
			$retval = 1;
			$socObj->dbcon->beginTrans();

			$intQuantityAtSys = $socObj->getStockQuantityByPid($pid);
			if (!$intQuantityAtSys || $intQuantityAtSys < $_REQUEST['quantity']){
				if (TRANSACTION_DEBUG > 1){
					echo "Quantiy is greater than the stock quantity.";
				}
				$retval = 0;
			}elseif (!$socObj->setStockQuantityByPid($pid, $_REQUEST['quantity'])) {
				if (TRANSACTION_DEBUG > 0){
					echo "Failed to set stock quantiy.".$pid.'='.$_REQUEST['quantity'];
				}
				$retval = 0;
			}

			if ($retval == 0) {
				$socObj->dbcon->rollbackTrans();
				$socObj->dbcon->endTrans();
				$intQuantityAtSys = $socObj->getStockQuantityByPid($pid);
				if ($intQuantityAtSys>0){
					echo "<script>alert('The quantity should not be larger than ".$intQuantityAtSys.". Please try again.');location.href='".$productlink."'</script>";
				}else{
					echo "<script>alert('Items out of stock.');location.href='".$productlink."'</script>";
				}
				exit;

			} else {
				$socObj->dbcon->commitTrans();
				$socObj->dbcon->endTrans();
			}
		}

		$sql = "select * from ".$socObj->table."product where pid=$pid";
		$dbcon->execute_query($sql);
		$productInfo = $dbcon->fetch_records();
		$productInfo = $productInfo[0];
		if($productInfo['isattachment'] == '1'){
			if(isuserbuy($pid,$_SESSION['ShopID'])){
			echo "<script>alert('You have bought this item.');location.href='".$productlink."'</script>";
			exit();
			}
		}
		$reviewKey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
		$arrSetting = array(
		'pid'		=> $_REQUEST['pid'],
		'buyer_id'	=> $_SESSION['ShopID'],
		'StoreID'	=> $StoreID,
		'reviewkey'	=> $reviewKey,
		'p_status'	=> 'order',
		'description'=>'PayPal',
		'order_date' =>time(),
		'type'		=> 'purchasing',
		'amount'	=> number_format(($_REQUEST['price'] * $_REQUEST['quantity'] + $_REQUEST['postage']*$_REQUEST['quantity']),2,'.',''),
		'month'		=> $_REQUEST['quantity']
		);
		if(isset($_REQUEST['dest'])){
			if($_REQUEST['overcountry']==1){
				/***/
				$shipping_method = $store_info['bu_delivery_text'][$_REQUEST['deliveryMethod']]."(Overseas)";
			}else{
				$shipping_method = $store_info['bu_delivery_text'][$_REQUEST['deliveryMethod']];
			}
			$shipping_cost = $_REQUEST['shipping'];
		}else{
			$shipping_method = $store_info['bu_delivery_text'][$_REQUEST['deliveryMethod']];
			$shipping_cost = $_REQUEST['shipping'];
		}
		$arrSetting['product_code'] = $_REQUEST['product_code'];
		$arrSetting['shipping_method'] = addslashes($shipping_method);
		$arrSetting['shipping_cost'] = number_format($shipping_cost,2,'.','');
		$arrSetting['item_name'] = $_REQUEST['item_name'];
		$arrSetting['price'] = number_format($_REQUEST['price'],2);
		$arrSetting['commission_type'] = '0';
		$arrSetting['commission'] = $adaptive->getCommission($_REQUEST);
		if ($isbid != '1'){
			$socObj->dbcon->insert_record($socObj->table.'order_reviewref',$arrSetting);
			$ref_id = $socObj->dbcon->lastInsertId();
			$socObj->orderSendMail($pid,$_SESSION['ShopID'],$StoreID,$reviewKey,$productInfo['item_name'],$_REQUEST['price'],$_REQUEST['quantity'],$_REQUEST['shipping'],$productInfo['p_code'],'paypal');
			$socstoreObj->updateCouponCode($_SESSION['couponInfo']['UserID'],$_SESSION['couponInfo']['StoreID'],$_SESSION['couponInfo']['pid'],$_SESSION['couponInfo']['coupon_code']);
			unset($_SESSION['couponInfo']);
		}else{
			$price = $socObj->dbcon->execute_query("select price from {$socObj->table}order_reviewref where ref_id=$ref_id");
			$price = $socObj->dbcon->fetch_records();
			$price = $price[0];
			$arrShippingCost = array(
				'amount'			=> number_format($price['price']+$arrSetting['shipping_cost'],2,'.',''),
				'shipping_cost'		=> $arrSetting['shipping_cost'],
				'description'		=> 'PayPal',
				'shipping_method'	=> $arrSetting['shipping_method']
			);
//			var_dump($arrShippingCost);
//			exit;
			$socObj->dbcon->update_record($socObj->table."order_reviewref",$arrShippingCost,"where ref_id='$ref_id'");
			$socObj->orderSendMail($pid,$_SESSION['ShopID'],$StoreID,'temp',$productInfo['item_name'],$_REQUEST['price'],$_REQUEST['quantity'],$_REQUEST['shipping'],$productInfo['p_code'],'paypal');
		}
		//ini_set("display_errors", "1");
		//error_reporting(E_ALL);

		$commission_info = $adminpayment->getCommission();
		if ($commission_info['commission_type'] == 1) {
			$_REQUEST['ref_id'] = $ref_id;
			$adaptive->submit($_REQUEST);
		} else {
			$paypal_info = getPaypalInfo();
			$_REQUEST['business'] = $paypal_info['paypal_email'];
			echo $socObj->paypalForm($ref_id);
		}

		//echo "alert($pid,".$_SESSION['ShopID'].",$StoreID,$reviewKey,".$productInfo['item_name'].','.$_REQUEST['price'].','.$_REQUEST['quantity'].','.$_REQUEST['postage'].")";
		exit();
		break;

	case 'adaptivepayment':
		ini_set("display_errors", "1");
		error_reporting(E_ALL);

		include_once(SOC_INCLUDE_PATH.'/class.paymentadaptive.php');

		$adaptive = new PaymentAdaptive();
		$adaptive->submit();
		exit();

	case 'googlecheckout':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Payment');
		$smarty -> assign('keywords','Payment');
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!$StoreID){
			echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		$pid = $_REQUEST['pid'];
		$isbid = $_REQUEST['isbid'];
		$ref_id = $_REQUEST['refid'];
		$productlink = 'soc.php?cp=dispro&StoreID='.$StoreID.'&proid='.$pid;

		$sql = "select * from ".$socObj->table."product where pid=$pid";
		$dbcon->execute_query($sql);
		$productInfo = $dbcon->fetch_records();
		$productInfo = $productInfo[0];
		if($productInfo['isattachment'] == '1'){
			if(isuserbuy($pid,$_SESSION['ShopID'])){
			echo "<script>alert('You have bought this item.');location.href='".$productlink."'</script>";
			exit();
			}
		}
		$productlink = 'soc.php?cp=dispro&StoreID='.$StoreID.'&proid='.$pid;
		$store_info = $socObj->_displayStoreInfo($temp,$StoreID);

		if ($isbid != '1'){
			$retval = 1;
			$socObj->dbcon->beginTrans();

			$intQuantityAtSys = $socObj->getStockQuantityByPid($pid);
			if (!$intQuantityAtSys || $intQuantityAtSys < $_REQUEST['quantity']){
				$retval = 0;
			}elseif (!$socObj->setStockQuantityByPid($pid, $_REQUEST['quantity'])) {
				$retval = 0;
			}

			if ($retval == 0) {
				$socObj->dbcon->rollbackTrans();
				$socObj->dbcon->endTrans();
				$intQuantityAtSys = $socObj->getStockQuantityByPid($pid);
				if ($intQuantityAtSys>0){
					echo "<script>alert('The quantity should not be larger than ".$intQuantityAtSys.". Please try again.');location.href='".$productlink."'</script>";
				}else{
					echo "<script>alert('Items out of stock.');location.href='".$productlink."'</script>";
				}
				exit;

			} else {
				$socObj->dbcon->commitTrans();
				$socObj->dbcon->endTrans();
			}
		}

		$sql = "select * from ".$socObj->table."product where pid=$pid";
		$dbcon->execute_query($sql);
		$productInfo = $dbcon->fetch_records();
		$productInfo = $productInfo[0];
		$reviewKey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
		$arrSetting = array(
		'pid'		=> $_REQUEST['pid'],
		'buyer_id'	=> $_SESSION['ShopID'],
		'StoreID'	=> $StoreID,
		'reviewkey'	=> $reviewKey,
		'p_status'	=> 'order',
		'description'=>'GoogleCheckout',
		'order_date' =>time(),
		'type'		=> 'purchasing',
		'amount'	=> number_format(($_REQUEST['price'] * $_REQUEST['quantity'] + $_REQUEST['postage'] * $_REQUEST['quantity']),2,'.',''),
		'month'		=> $_REQUEST['quantity']
		);
		if(isset($_REQUEST['dest'])){
			if($_REQUEST['overcountry']==1){
				/***/
				$shipping_method = $store_info['bu_delivery_text'][$_REQUEST['deliveryMethod']]."(Overseas)";
			}else{
				$shipping_method = $store_info['bu_delivery_text'][$_REQUEST['deliveryMethod']];
			}
			$shipping_cost = $_REQUEST['shipping'];
		}else{
			$shipping_method = $store_info['bu_delivery_text'][$_REQUEST['deliveryMethod']];
			$shipping_cost = $_REQUEST['shipping'];
		}
		$arrSetting['product_code'] = $_REQUEST['product_code'];
		$arrSetting['shipping_method'] = $shipping_method;
		$arrSetting['shipping_cost'] = number_format($shipping_cost,2,'.','');
		$arrSetting['item_name'] = $_REQUEST['item_name'];
		$arrSetting['price'] = number_format($_REQUEST['price'],2);
		if ($isbid != 1){
			$socObj->dbcon->insert_record($socObj->table.'order_reviewref',$arrSetting);
			$ref_id = $socObj->dbcon->lastInsertId();
			$socObj->orderSendMail($pid,$_SESSION['ShopID'],$StoreID,$reviewKey,$productInfo['item_name'],$_REQUEST['price'],$_REQUEST['quantity'],$_REQUEST['shipping'],$productInfo['p_code'],'googlecheckout');
		}else{
			$price = $socObj->dbcon->execute_query("select price from {$socObj->table}order_reviewref where ref_id=$ref_id");
			$price = $socObj->dbcon->fetch_records();
			$price = $price[0];
			$arrShippingCost = array(
				'amount'			=> number_format($price['price']+$arrSetting['shipping_cost'],2,'.',''),
				'shipping_cost'		=> $arrSetting['shipping_cost'],
				'description'		=> 'GoogleCheckout',
				'shipping_method'	=> $arrSetting['shipping_method']
			);
			$socObj->dbcon->update_record($socObj->table."order_reviewref",$arrShippingCost,"where ref_id='$ref_id'");
			$socObj->orderSendMail($pid,$_SESSION['ShopID'],$StoreID,'temp',$productInfo['item_name'],$_REQUEST['price'],$_REQUEST['quantity'],$_REQUEST['shipping'],$productInfo['p_code'],'googlecheckout');
		}
		$socObj->payProductByGoogle($ref_id);

		exit;
		break;

	case 'credit':

		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		if (!$StoreID){
			echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		$message = $socObj->creditPayment();
		//var_dump($req);
		$req 	= array('aboutPage'=>"<br><div align=center>".$message);

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Payment', 4, $templateInfo['bgcolor']));
		$smarty -> assign('is_website', 1);

		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);
		//end shopper header

		$smarty -> assign('req', $req);
		$content =	$smarty->fetch('about.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		break;
	
	case 'account_payment':
		$cart = new Cart();
		$order = new Order();
		$StoreID = $_SESSION['ShopID'];
		$arrSettingStore = array(
			'bu_address'	=>	$_POST['bu_address'],
			'bu_suburb'		=>	$_POST['bu_suburb'],
			'bu_state'		=>	$_POST['bu_state'],
			'bu_postcode'	=>	$_POST['bu_postcode']
		);
		
		$message = "Order Failed";
		if ($socObj->dbcon->update_record("aus_soc_bu_detail", $arrSettingStore, "WHERE StoreID = '".$StoreID."'")) {
			
			$detail_query = "SELECT detail.contact_name, detail.bu_address, detail.bu_suburb, detail.bu_postcode, detail.bu_email, state.description As state_name FROM aus_soc_bu_detail detail INNER JOIN aus_soc_state state ON detail.bu_state = state.id WHERE StoreID = '".$StoreID."'";
			$detail_result = $dbcon->getOne($detail_query);
			
			$arrSetting = array(
				'firstName' => $detail_result['contact_name'],
				'address1' => $detail_result['bu_address'],
				'city' => $detail_result['bu_suburb'],
				'state' => $detail_result['state_name'],
				'emailAddr' => $detail_result['bu_email'],
				'postcode' => $detail_result['bu_postcode'],
				'shipping_cost' => $_POST['shipping'],
				'total_money' => $_POST['total_money'],
				'description' => "Payment Offline",
				'status' => 1
			);
			
			if ($socObj->dbcon->update_record("aus_soc_order_foodwine", $arrSetting, " WHERE OrderID = '".$_POST['OrderID']."'")) {
				$order->orderOfflineSendMail($_POST['OrderID']);
				$message = "THANK YOU. Your order has been sent. Please check your email for order details. Thank you for shopping at FoodMarketplace.";
			}			
		}
		$cart->delCart();
		
		$req = array('aboutPage'=>"<br><div align=center>".$message);

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Payment', 4, $templateInfo['bgcolor']));
		$smarty -> assign('is_website', 1);

		$headerInfo = $socObj -> displayStoreWebside(true);
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);

		$smarty -> assign('req', $req);
		$content =	$smarty->fetch('about.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
	
		break;
	case 'message':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Payment');
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : $_SESSION['StoreID'];
		$req 	= array('msg'=>$_REQUEST['msg'],'StoreID'=>$StoreID);

		$templateInfo = $socObj -> getTemplateInfo();
		$smarty -> assign('templateInfo', $templateInfo);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Payment', 4, $templateInfo['bgcolor']));
		$smarty -> assign('is_website', 1);
		
		if (isset($_GET['payment'])) {
			$smarty->assign('payment', true);
		}

		//start shopper header
		$headerInfo = $socObj -> displayStoreWebside(true);
                if($headerInfo['info']['attribute'] == 5) {
                    $smarty->assign('show_join_banner', 1);
                    $smarty->assign('show_season_banner', 1);
                }
                
                $req['template'] = $headerInfo['template'];
		$smarty->assign('headerInfo', $headerInfo['info']);
		$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
		$smarty->assign('tmp_header', $tmp_header);

		$search_type = $headerInfo['info']['sellerType'];
		if ($search_type == 'foodwine') {
			$smarty->assign('noShowGalleryBanner', true);
			$smarty->assign('noShowTvBanner', true);
		}

		//end shopper header
		$req['download'] = $_REQUEST['downable'];
        	$req['hide_contact'] = $_REQUEST['hc'];
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('message.tpl');
		$smarty->assign('is_content',1);
        	$smarty->assign('is_website', '');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty->assign('isstorepage',1);
		break;

	case 'test':
		//		$result = $socObj->getCollegesByState('18', 21320);
		//		print_r($result);
		$test = $socObj->initEditor('bu_desc', 'test');
		echo $test;
		break;
		
	case 'searchpage':
		$product_name = (isset($_REQUEST['product_name']) ? $_REQUEST['product_name'] : '');
		$business_name = (isset($_REQUEST['business_name']) ? $_REQUEST['business_name'] : '');
		$postcode = (isset($_REQUEST['bu_postcode']) ? $_REQUEST['bu_postcode'] : '');
		$category = (isset($_REQUEST['bcategory']) ? $_REQUEST['bcategory'] : '');
		$subcategory = (isset($_REQUEST['bsubcategory']) ? $_REQUEST['bsubcategory'] : '');
		$price_min = (isset($_REQUEST['price_min']) ? $_REQUEST['price_min'] : '');
		$price_max = (isset($_REQUEST['price_max']) ? $_REQUEST['price_max'] : '');
		$state_name = (isset($_REQUEST['state_name']) ? $_REQUEST['state_name'] : '');
		$suburb = (isset($_REQUEST['selectSubburb']) ? $_REQUEST['selectSubburb'] : '');
		$distance = (isset($_REQUEST['selectDistance']) ? $_REQUEST['selectDistance'] : '');
		
		$buytype = (isset($_REQUEST['buytype'])? $_REQUEST['buytype'] : '');
		$auctions_type = (isset($_REQUEST['auctions_type']));
		$buynow_type = (isset($_REQUEST['buynow_type']));
		
		$issold = (isset($_REQUEST['issold'])? $_REQUEST['issold'] : '0');
		$sort  = (isset($_REQUEST['sort']) ? $_REQUEST['sort'] : '');
		
		
		$arr_tmp = array();
		
		if (!empty($product_name)) {
			$arr_tmp['product_name'] = $product_name;
		}
		if (!empty($business_name)) {
			$arr_tmp['business_name'] = $business_name;
		}
		if (!empty($postcode)) {
			$arr_tmp['postcode'] = $postcode;
		}
		if (!empty($category)) {
			$arr_tmp['category'] = $category;
		}
		if (!empty($subcategory)) {
			$arr_tmp['subcategory'] = $subcategory;
		}
		if (!empty($price_min)) {
			$arr_tmp['price_min'] = round(floatval($price_min),2);
		}
		if (!empty($price_max)) {
			$arr_tmp['price_max'] = round(floatval($price_max),2);
		}
		if (!empty($state_name)) {
			$arr_tmp['state'] = $state_name;
		}
		if (!empty($suburb)) {
			$arr_tmp['suburb'] = $suburb;
		}
		if (!empty($distance)) {
			$arr_tmp['distance'] = $distance;
		}
		$arr_tmp['auctions_type'] = $auctions_type;
		$arr_tmp['buynow_type'] = $buynow_type;
		
		$arr_tmp['issold'] = $issold;
		$arr_tmp['buytype'] = $buytype;
		$arr_tmp['sort'] = $sort;
		
		//echo var_export($arr_tmp);
		//exit;
		$req = $socObj->searchAuction($arr_tmp);
		
		$smarty->assign('req', $req);
		$smarty->assign('selectedary',$arr_tmp);
		$content = $smarty ->fetch('searchauctions.tpl');
		$smarty->assign('content', $content);
		$smarty->assign('sidebar', 0);
		
		break;
	case 'statepage':
		$product_name = isset($_REQUEST['product_name']) ? $_REQUEST['product_name'] : $_POST['product_name'];
		$business_name = isset($_REQUEST['business_name']) ? $_REQUEST['business_name'] : $_POST['business_name'];
		$state_name = isset($_REQUEST['state_name']) ? $_REQUEST['state_name'] : $_POST['state_name'];
		$distance = isset($_REQUEST['selectDistance']) ? $_REQUEST['selectDistance'] : $_POST['selectDistance'];
		$suburb = isset($_REQUEST['selectSubburb']) ? $_REQUEST['selectSubburb'] : '';
		$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';

		$bcategory      = isset($_REQUEST['bcategory']) ? $_REQUEST['bcategory'] : '-2';
		$bsubcategorys = isset($_REQUEST['bsubcategory'])?$_REQUEST['bsubcategory']:"";
		$states = isset($_REQUEST['sstate_name'])?$_REQUEST['sstate_name']:"";
		$price_min = isset($_REQUEST['price_min'])?$_REQUEST['price_min']:"";
		$price_max = isset($_REQUEST['price_max'])?$_REQUEST['price_max']:"";
		$issold = isset($_REQUEST['issold'])?$_REQUEST['issold']:"0";
		$sort  = isset($_REQUEST['sort'])?$_REQUEST['sort']:"";
		$buytype  = isset($_REQUEST['buytype'])&&$_REQUEST['buytype']?$_REQUEST['buytype']:"'yes','no'";
		$timelefts  = isset($_REQUEST['timelefts'])?$_REQUEST['timelefts']:"0";
		$buytypeState = isset($_REQUEST['buytypeState'])&&$_REQUEST['buytypeState']?$_REQUEST['buytypeState']:"'yes','no'";
		if(get_magic_quotes_gpc()){
			$buytype = stripslashes($buytype);
			$buytypeState = stripslashes($buytypeState);
		}

		$selectedary['product_name'] = $product_name;
		$selectedary['business_name'] = $business_name;
		$selectedary['state_name'] = $state_name;
		$selectedary['selectDistance'] = $distance;
		$selectedary['selectSubburb'] = $suburb;
		$selectedary['category'] = $category;
		$selectedary['bcategory'] = $bcategory;
		$selectedary['bsubcategory'] = $bsubcategorys;
		$selectedary['sstate_name'] = $states;
		$selectedary['price_min'] = $price_min;
		$selectedary['price_max'] = $price_max;
		$selectedary['issold'] = $issold;
		$selectedary['sort'] =  $sort;
		$selectedary['buytype'] =  $buytype;
		$selectedary['buytypeState'] =  $buytypeState;
		$selectedary['timelefts'] =  $timelefts;
		$selectedary['pageId'] =  isset($_REQUEST['pageId'])?$_REQUEST['pageId']:1;
		$selectedary['p'] =  isset($_REQUEST['p'])?$_REQUEST['p']:1;
		$smarty->assign('selectedary',$selectedary);

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - State Page');
		$smarty -> assign('keywords','State Page');
		if(strlen(trim($business_name))==0&&strlen(trim($product_name))==0&&$bcategory=="-2"&&$bsubcategorys==""&&strlen(trim($states))==0&&strlen(trim($price_max))==0&&strlen(trim($price_min))==0&&$state_name==""){
			$arr_tmp = array();
			$arr_tmp['issold'] = $issold;
			$arr_tmp['sort'] = $sort;
			$arr_tmp['buytype'] = $buytype;
			$arr_tmp['timelefts'] = $timelefts;
			$arr_tmp['bu_postcode'] = $_REQUEST['bu_postcode'];
			$req = $socObj->searchProductsBycon($arr_tmp);
			$smarty -> assign('req', $req);
			$content = $smarty ->fetch('searchbyproduct.tpl');
			$smarty -> assign('content', $content);
			$smarty -> assign('sidebar', 0);
		}else{
			if (strlen(trim($business_name))>0&&(strlen(trim($product_name))==0)&&$bcategory=="-2"&&$bsubcategorys==""&&strlen(trim($states))==0&&strlen(trim($price_max))==0&&strlen(trim($price_min))==0&&$issold!=1){
				$req = $socObj->searchByBusinessName($business_name);
				$smarty -> assign('req', $req);
				$content = $smarty ->fetch('searchbybusiness.tpl');
				$smarty -> assign('content', $content);
				$smarty -> assign('sidebar', 0);
			}elseif (strlen(trim($product_name))>0||$bcategory!="-2"||$bsubcategorys!=""||strlen(trim($states))>0||strlen(trim($price_max))>0||strlen(trim($price_min))>0||$issold=='1'){
				$arr_tmp = array();
				if(strlen(trim($price_min))>0){
					$arr_tmp['price_min'] = round(floatval($price_min),2);
				}
				if(strlen(trim($price_max))>0){
					$arr_tmp['price_max'] = round(floatval($price_max),2);
				}
				if($bcategory!="-2"){
					$arr_tmp['category'] = $bcategory;
				}
				if($bsubcategorys!=""){
					$arr_tmp['subcategory'] = $bsubcategorys;
				}
				if(strlen(trim($states))>0){
					$arr_tmp['state'] = $states;
				}
				if(strlen(trim($product_name))>0){
					$arr_tmp['product_name'] = $product_name;
				}
				if(strlen(trim($business_name))>0){
					$arr_tmp['business_name'] = $business_name;
				}
				$arr_tmp['issold'] = $issold;
				$arr_tmp['sort'] = $sort;
				$arr_tmp['buytype'] = $buytype;
				$arr_tmp['timelefts'] = $timelefts;
				$arr_tmp['bu_postcode'] = $_REQUEST['bu_postcode'];
				$req = $socObj->searchProductsBycon($arr_tmp);
				$smarty -> assign('req', $req);

				$content = $smarty ->fetch('searchbyproduct.tpl');
				$smarty -> assign('content', $content);
				$smarty -> assign('sidebar', 0);
			}elseif (!empty($distance) || !isset($category)){
				$req = $socObj->searchByProduct($state_name);
				$smarty->assign('state_name', $state_name);
				$smarty -> assign('state_fullname', getStateDescByName($state_name));
				$smarty -> assign('category_id', $category);
				$smarty -> assign('selectDistance', $distance);
				$smarty -> assign('selectSubburb', $suburb);

				$smarty->assign('req', $req);
				$content = $smarty->fetch('searchbystatelist.tpl');
				$smarty->assign('content', $content);
				$smarty->assign('sidebar', 0);
			} else {
                include_once(SOC_INCLUDE_PATH . '/class.banner.php');
				if (empty($state_name)){
					$state_name = 'NSW';
				}
				$banner = new Banner();
				$banners = $banner->StatePageRandomBanner($state_name);
				$bannerIDArray = array();
				foreach($banners as $bn) {
					$bannerIDArray[] = $bn['banner_id'];
				}
				$banner->addStatePageRandomBannerViews($bannerIDArray);
				$smarty->assign('state_name', $state_name);
				$smarty->assign('selectDistance', $_REQUEST['selectDistance']);
				//$smarty -> assign('selectDistance', 300);
				$smarty -> assign('state_fullname', getStateDescByName($state_name));
				$req = $socObj->statePage($state_name, 'State');
				$smarty->assign('showRandomBanner',true);
				$smarty->assign('statePageBanners', $banners);
				$smarty->assign('req', $req);
				$browse = $socObj->GetClinetBrowser();
				$isSafari = $browse[0] == 6 ? true : false;
				$smarty->assign('isSafari', $isSafari);
				$content = $smarty->fetch('searchbystate.tpl');
				$smarty->assign('content', $content);
				$smarty->assign('sidebar_bg', 0);
			}
		}
		$smarty->assign('is_content',1);
		$search_type = 'store';
		break;
	case 'searchTag':
		$arr_tmp = array('pro_tags'=>$_REQUEST['pro_tags']);
		$req = $socObj->searchProductsBycon($arr_tmp);
		$smarty -> assign('req', $req);
		$content = $smarty ->fetch('searchbyproduct.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty->assign('is_content',1);
		$search_type	=	'store';
		break;

	case 'collegepage':

    	$search_type = 'store';
        $smarty->assign('pageTitle', 'Sell Goods Online - Selling Online - College Page');
        $smarty->assign('keywords', 'College Page');

                include_once(SOC_INCLUDE_PATH . '/class.banner.php');

                $state_name = $_GET['statename'];
                if (empty($state_name)){
                        $state_name = DEFAULT_STATE;
                }

                //random banner
                $banner = new Banner();
                $banners = $banner->StatePageRandomBanner($state_name);
                $bannerIDArray = array();
                foreach($banners as $bn) {
                    $bannerIDArray[] = $bn['banner_id'];
                }
                $banner->addStatePageRandomBannerViews($bannerIDArray);
                $smarty->assign('showRandomBanner',true);
                $smarty->assign('statePageBanners', $banners);


		$statename = addslashes($_REQUEST['statename']);
		$collegeid =  isset($_REQUEST['collegeid']) ? $_REQUEST['collegeid'] : $_POST['collegeid'];
		$smarty->assign('statename', $statename);


		$req = $socObj->statePage($statename, 'College', $collegeid);
		$smarty->assign('req', $req);

                /**
                 * added by YangBall, 2011-04-08
                 */
                 $browse = $socObj->GetClinetBrowser();
                 $isSafari = $browse[0] == 6 ? true : false;
                 $smarty->assign('isSafari', $isSafari);
                //END-YangBall

		$collegeName = $socObj->getCollegeNameBybizID($collegeid);
		$smarty	-> assign('collegeName', $collegeName);
		$smarty->assign('locations', $req['state']);
		$content = $smarty->fetch('searchbycollege.tpl');
		$smarty->assign('content', $content);
		$smarty->assign('sidebar_bg', 0);
		$smarty->assign('is_content',1);
		$smarty -> assign('stateOnLoad', 	"selectCollegebyName('collegeobj', '". $statename ."&collegeid=".$_REQUEST['collegeid']."');");
		break;

	case 'collegeproducts':

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - College Page');
		$smarty -> assign('keywords','College Page');

		$statename    = isset($_REQUEST['statename']) ? $_REQUEST['statename'] : $_POST['statename'];
		$collegeid =  isset($_REQUEST['collegeid2']) ? $_REQUEST['collegeid2'] : $_POST['collegeid2'];
		$category      = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';
		$timelefts  = isset($_REQUEST['timelefts'])?$_REQUEST['timelefts']:"0";
		$buytypeState = isset($_REQUEST['buytypeState'])&&$_REQUEST['buytypeState']?$_REQUEST['buytypeState']:"'yes','no'";
		if(get_magic_quotes_gpc()){	$buytypeState = stripslashes($buytypeState);}

		$selectedary = array();
		$selectedary['buytypeState'] =  $buytypeState;
		$selectedary['timelefts'] =  $timelefts;
		$smarty->assign('selectedary',$selectedary);
		$sort = isset($_REQUEST['sort'])?$_REQUEST['sort']:'';
		$req = $socObj->searchProductsbyCollege();
		$smarty->assign('statename', $statename);
		$smarty->assign('collegeid', $collegeid);
		$smarty->assign('sort', $sort);
		$smarty->assign('p', $_REQUEST['p']);

		$collegeName = $socObj->getCollegeNameBybizID($collegeid);
		$smarty	-> assign('collegeName', $collegeName);
		$smarty -> assign('category_id', $category);

		$smarty->assign('req', $req);
		$content = $smarty->fetch('searchbycollegelist.tpl');
		$smarty -> assign('stateOnLoad', 	"selectCollegebyName('collegeobj', '". $statename ."&collegeid=".$_REQUEST['collegeid2']."');");
		$smarty->assign('content', $content);
		$smarty->assign('sidebar', 0);
		$smarty->assign('is_content',1);

		break;

	case 'business_get_step_home':
		include_once('class.adminHomeCommon.php');

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Admin System');
		$smarty -> assign('keywords','Admin System');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Welcome to Your Admin', 2));
		if($_SESSION['UserID']=='' AND $_SESSION['level']!=1){
			header("Location:index.php");
		}
		//Initial template and StoreID
		getStoreID($_SESSION['UserID']);
		//Gets current storeid
		$sid = $_SESSION['StoreID'];

		$req = $socObj->getBusinessHome($sid);

		$objAjax	=	new ajax();
		$req['element']['jsSaveOuterEmail']	=	$objAjax -> regFun('saveOuterEmail',array(array('this.value',5),array('this.id',5)));
		$objAjax -> processRequest();


		if (!empty($_SESSION)) {
			$smarty -> assign('session', $_SESSION);
		}
		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('business_get_step_home.tpl');

		$smarty -> assign('xajax_Javascript', $objAjax -> getJSInit());
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty	-> assign('is_content',1);
		break;
	case 'cancel_subscription':
		$query_existing = "SELECT *, DATEDIFF(end_date, NOW()) AS days_remaining FROM aus_soc_subscriber WHERE store_id = '".$_SESSION['StoreID']."' AND status = 2";
		$result_existing = mysql_query($query_existing);
		if (mysql_num_rows($result_existing) == 1) {
			$row_existing = mysql_fetch_assoc($result_existing);
			if ($row_existing['days_remaining'] > 0) {
				$days_remaining = $row_existing['days_remaining'];
				$client = ewayAPI();
				$requestbody = array(
					'man:RebillCustomerID' => $row_existing['customer_id'],
					'man:RebillID' => $row_existing['rebill_id']
				);
				$soapaction = 'http://www.eway.com.au/gateway/rebill/manageRebill/DeleteRebillEvent';
				$result = $client->call('man:DeleteRebillEvent', $requestbody, '', $soapaction);
				if ($result['Result'] == 'Success') {
					$requestbody = array(
						'man:RebillCustomerID' => $row_existing['customer_id']
					);
					$soapaction = 'http://www.eway.com.au/gateway/rebill/manageRebill/DeleteRebillCustomer';
					$result = $client->call('man:DeleteRebillCustomer', $requestbody, '', $soapaction);
					if ($result['Result'] == 'Success') {
						$start_month = date('m', strtotime($row_existing['initial_payment_date']));
						$current_month = date('m');
						$month_difference = ($current_month - $start_month) + 1;
						$end_date = date('Y-m-d', strtotime($row_existing['initial_payment_date'] . " +$month_difference month"));
						$started_date = date('Y-m-d', strtotime($row_existing['initial_payment_date'] . " -$days_remaining days"));
						
						$query = "UPDATE aus_soc_subscriber SET customer_id = 0, rebill_id = 0, initial_payment_date = '".$started_date."', end_date = '".$end_date."', status = 3 WHERE store_id = '".$_SESSION['StoreID']."'";
						mysql_query($query);
						header("Location:/soc.php?cp=subscriber");
					}
				}
			}
		}
		break;		
	case 'subscription':
		if (isset($_SESSION['UserID'])) {
			$update_record = false;
			$query_existing = "SELECT *, DATEDIFF(end_date, NOW()) AS days_remaining FROM aus_soc_subscriber WHERE store_id = ".$_SESSION['StoreID'];
			$result_existing = mysql_query($query_existing);
			
			if (mysql_num_rows($result_existing) == 1) {
				$row_existing = mysql_fetch_assoc($result_existing);
				if ($row_existing['status'] == 2) {
					header("Location:/soc.php?cp=subscriber");
				}
				
				if ($row_existing['days_remaining'] > 0) {
					$days_remaining = $row_existing['days_remaining'];
				}
				
				$update_record = true;
				
				$first_name = $row_existing['first_name'];
				$last_name = $row_existing['last_name'];
				$state = $row_existing['state'];
				$postcode = $row_existing['postcode'];
				$cardholder = $row_existing['cardholder'];
			}
			
			if (isset($_POST['submit'])) {				
				if ((!empty($_POST['first_name'])) && 
					(!empty($_POST['last_name'])) && 
					(!empty($_POST['state'])) && 
					(!empty($_POST['postcode'])) && 
					(!empty($_POST['cardholder'])) &&
					(!empty($_POST['cardnumber'])) &&
					(!empty($_POST['cvc'])) &&
					(!empty($_POST['expiry_month'])) &&
					(!empty($_POST['expiry_year']))) {
					
					$first_name = mysql_real_escape_string($_POST['first_name']);
					$last_name = mysql_real_escape_string($_POST['last_name']);
					$state = mysql_real_escape_string($_POST['state']);
					$postcode = mysql_real_escape_string($_POST['postcode']);
					$country = 'Australia';
					$cardholder = mysql_real_escape_string($_POST['cardholder']);
					$cardnumber = $_POST['cardnumber'];
					$cvc = $_POST['cvc'];
					$expiry_month = $_POST['expiry_month'];
					$expiry_year = $_POST['expiry_year'];
					
					if (isset($days_remaining) && $days_remaining > 0) {
						$today = date("Y-m-d", strtotime("+$days_remaining days"));
					} else {
						$today = date("Y-m-d");
					}
					
					$initial_amount = 11000;
					$initial_payment_date = $today;
					$recurring_amount = 22000;
					
					if ($row_existing['status'] == 3) {
						$initial_amount = $recurring_amount;
					}
					
					$recurring_start_date = date('Y-m-d', strtotime(date("Y-m-d", strtotime($today)) . " +1 month"));
					$rebill_interval = 1;
					$interval_type = 3;
					$end_date = date('Y-m-d', strtotime(date("Y-m-d", strtotime($today)) . " +12 month"));
					$query_fields = "first_name = '".$first_name."', 
									last_name = '".$last_name."',
									state = '".$state."',
									postcode = '".$postcode."',
									country = '".$country."',
									cardholder = '".$cardholder."',
									initial_amount = '".$initial_amount."',
									initial_payment_date = '".$initial_payment_date."',
									recurring_amount = '".$recurring_amount."',
									recurring_start_date = '".$recurring_start_date."',
									rebill_interval = '".$rebill_interval."',
									interval_type = '".$interval_type."',
									end_date = '".$end_date."'";		
					if ($update_record) {
						$query = "UPDATE aus_soc_subscriber SET ".$query_fields." WHERE store_id = '".$_SESSION['StoreID']."'";
					} else {
						$query = "INSERT INTO aus_soc_subscriber SET store_id = '".$_SESSION['StoreID']."', ".$query_fields;
					}
					mysql_query($query);
					$subscriber_query = "SELECT * FROM aus_soc_subscriber WHERE store_id = '".$_SESSION['StoreID']."'";
					$result_subscriber = mysql_query($subscriber_query);
					if (mysql_num_rows($result_subscriber) == 1) {
						$row_subscriber = mysql_fetch_assoc($result_subscriber);
						$store_id = $row_subscriber['store_id'];
						$first_name = $row_subscriber['first_name'];
						$last_name = $row_subscriber['last_name'];
						$state = $row_subscriber['state'];
						$postcode = $row_subscriber['postcode'];
						$country = $row_subscriber['country'];
						$initial_amount = $row_subscriber['initial_amount'];
						$initial_payment_date = date('d/m/Y', strtotime($row_subscriber['initial_payment_date']));
						$recurring_amount = $row_subscriber['recurring_amount'];
						$recurring_start_date = date('d/m/Y', strtotime($row_subscriber['recurring_start_date']));
						$rebill_interval = $row_subscriber['rebill_interval'];
						$interval_type = $row_subscriber['interval_type'];
						$end_date = date('d/m/Y', strtotime($row_subscriber['end_date']));
						$cardholder = $row_subscriber['cardholder'];
						$client = ewayAPI();
						$requestbody = array(
							'man:customerTitle' => '',
							'man:customerFirstName' => $first_name,
							'man:customerLastName' => $last_name,
							'man:customerAddress' => '',
							'man:customerSuburb' => '',
							'man:customerState' => $state,
							'man:customerCompany' => $_SESSION['UserName'],
							'man:customerPostCode' => $postcode,
							'man:customerCountry' => $country,
							'man:customerEmail' => "programming@webstuff.biz",
							'man:customerFax' => '',
							'man:customerPhone1' => '',
							'man:customerPhone2' => '',
							'man:customerRef' => $store_id,
							'man:customerJobDesc' => '',
							'man:customerComments' => '',
							'man:customerURL' => ''
						);
						$soapaction = 'http://www.eway.com.au/gateway/rebill/manageRebill/CreateRebillCustomer';
						$result = $client->call('man:CreateRebillCustomer', $requestbody, '', $soapaction);
						if ($result['Result'] == 'Success') {
							$customerID = $result['RebillCustomerID'];
							$update_customer_id = "UPDATE aus_soc_subscriber SET customer_id = '".$customerID."' WHERE store_id = '".$_SESSION['StoreID']."'";
							mysql_query($update_customer_id);
							$requestbody = array(
								'man:RebillCustomerID' => $customerID,
								'man:RebillInvRef' => $store_id,
								'man:RebillInvDes' => '',
								'man:RebillCCName' => $cardholder,
								'man:RebillCCNumber' => $cardnumber,
								'man:RebillCCExpMonth' => $expiry_month,
								'man:RebillCCExpYear' => $expiry_year,
								'man:RebillInitAmt' => $initial_amount,
								'man:RebillInitDate' => $initial_payment_date,
								'man:RebillRecurAmt' => $recurring_amount,
								'man:RebillStartDate' => $recurring_start_date,
								'man:RebillInterval' => $rebill_interval,
								'man:RebillIntervalType' => $interval_type,
								'man:RebillEndDate' => $end_date
							);
							$soapaction = 'http://www.eway.com.au/gateway/rebill/manageRebill/CreateRebillEvent';
							$result = $client->call('man:CreateRebillEvent', $requestbody, '', $soapaction);
							if ($result['Result'] == 'Success') {
								$rebill_id = $result['RebillID'];
								$update_status = "UPDATE aus_soc_subscriber SET rebill_id = '".$rebill_id."', status = 2 WHERE store_id = '".$_SESSION['StoreID']."'";
								if (mysql_query($update_status)) {
									header("Location:soc.php?cp=subscriber");
								}
							} else {
								$smarty->assign('eway_error_severity', $result['ErrorSeverity']);
								$smarty->assign('eway_error', $result['ErrorDetails']);
							}
						} else {
							$smarty->assign('eway_error_severity', $result['ErrorSeverity']);
							$smarty->assign('eway_error', $result['ErrorDetails']);
						}
					}
				} else {
					$smarty->assign('eway_error', "Please ensure the form is filled out correctly.");
				}
			}
			
			$smarty->assign('first_name', (isset($_POST['first_name']) ? $_POST['first_name'] : (isset($first_name) ? $first_name : '')));
			$smarty->assign('last_name', (isset($_POST['last_name']) ? $_POST['last_name'] : (isset($last_name) ? $last_name : '')));
			$smarty->assign('state', (isset($_POST['state']) ? $_POST['state'] : (isset($state) ? $state : '')));
			$smarty->assign('postcode', (isset($_POST['postcode']) ? $_POST['postcode'] : (isset($postcode) ? $postcode : '')));
			$smarty->assign('cardholder', (isset($_POST['cardholder']) ? $_POST['cardholder'] : (isset($cardholder) ? $cardholder : '')));
			
			$content = $smarty->fetch('subscriber_form.tpl');
			$smarty->assign('content', $content);
			$smarty->assign('sidebar', 0);
			$smarty->assign('is_content',2);
		}
		break;
	case 'subscribe':
		if (isset($_SESSION['UserID'])) {			
			$query_subscriber = "SELECT *, DATEDIFF(end_date, NOW()) AS days_remaining FROM aus_soc_subscriber WHERE store_id = '".$_SESSION['StoreID']."' AND status != 1";
			$result_subscriber = mysql_query($query_subscriber);
			if (mysql_num_rows($result_subscriber) == 1) {
				$row_subscriber = mysql_fetch_assoc($result_subscriber);
				if ($row_subscriber['days_remaining'] > 0) {
					header("Location:/soc.php?cp=subscriber");
				}
			}
			
			$smarty->assign('pageTitle','Sell Goods Online - Selling Online - Buyer Admin System');
			$smarty->assign('keywords','Buyer Admin System');

			$content = $smarty->fetch('subscribe.tpl');
			$smarty->assign('content', $content);
			$smarty->assign('sidebar', 0);
			$smarty->assign('is_content',2);
		}
		break;
	case 'subscriber':
	
		//error_reporting(E_ALL);
		//ini_set('display_errors', 1);
		if (isset($_SESSION['UserID'])) {
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Buyer Admin System');
			$smarty -> assign('keywords','Buyer Admin System');
			
			$subscriber = false;
			$query_subscriber = "SELECT *, DATEDIFF(end_date, NOW()) AS days_remaining FROM aus_soc_subscriber WHERE store_id = '".$_SESSION['StoreID']."' AND status != 1";
			$result_subscriber = mysql_query($query_subscriber);
			$rebill = true;
			if (mysql_num_rows($result_subscriber) == 1) {
				$row_subscriber = mysql_fetch_assoc($result_subscriber);
				if ($row_subscriber['days_remaining'] > 0) {
					$subscriber = true;
					$subscription_begin = date('d-m-Y', strtotime($row_subscriber['initial_payment_date']));
					$subscription_end = date('d-m-Y', strtotime($row_subscriber['end_date']));
					$days_remaining = $row_subscriber['days_remaining'];
					if ($row_subscriber['rebill_id'] == 0) {
						$rebill = false;
					}
				}
			}
			
			if ($subscriber) {
				$smarty -> assign('subscription_begin', $subscription_begin);
				$smarty -> assign('subscription_end', $subscription_end);
				$smarty -> assign('days_remaining', $days_remaining);			
				$smarty -> assign('rebill', $rebill);
				
				if (isset($_POST['submit'])) {
					$files = array('logo', 'headline');
					$logo_image = '';
					$headline_image = '';
					
					$file_path = 'upload/website_info';
					
					$query_website_info = "SELECT * FROM aus_soc_website_information WHERE store_id = " . $_SESSION['StoreID'] . " LIMIT 1";
					$result_website_info = mysql_query($query_website_info);
					if (mysql_num_rows($result_website_info) == 1) {
						$row_website_info = mysql_fetch_assoc($result_website_info);
						$logo_image = $row_website_info['logo_image'];
						$headline_image = $row_website_info['headline_image'];
					}
					
					if (is_dir($file_path)) {
						foreach ($files as $file) {
							if (isset($_FILES[$file]['name'])) {
								$extension = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);
								$file_name = sha1(uniqid(mt_rand(), true)) . '.' . $extension;
								$target = $file_path . '/' . $file_name;
								if (move_uploaded_file($_FILES[$file]['tmp_name'], $target)) {
									if ($file == 'logo') {
										if (trim($logo_image)) {
											unlink($file_path . '/' . $logo_image);
										}
										$logo_image = $file_name;
									} else if ($file == 'headline') {
										if (trim($headline_image)) {
											unlink($file_path . '/' . $headline_image);
										}
										$headline_image = $file_name;
									}
								}
							}
						}
					}
					
					$colour = mysql_real_escape_string($_POST['colour']);
					$about_retailer = mysql_real_escape_string($_POST['about_retailer']);
					
					if ((!empty($colour)) && (!empty($about_retailer))) {					
						if (mysql_num_rows($result_website_info) == 0) {
							$insert_website_info = "INSERT INTO aus_soc_website_information SET store_id = " . $_SESSION['StoreID'] . ", logo_image = '".$logo_image."', headline_image = '".$headline_image."', colour = '".$colour."', about_retailer = '".$about_retailer."'";
							mysql_query($insert_website_info);
						} else {
							$update_website_info = "UPDATE aus_soc_website_information SET logo_image = '".$logo_image."', headline_image = '".$headline_image."', colour = '".$colour."', about_retailer = '".$about_retailer."' WHERE store_id = " . $_SESSION['StoreID'];
							mysql_query($update_website_info);
						}
						
						$clear_items = "DELETE FROM aus_soc_website_information_items WHERE store_id = '" .$_SESSION['StoreID']. "'";
						mysql_query($clear_items);
						
						if (isset($_POST['specials'])) {
							foreach ($_POST['specials'] as $special) {
								$store_id = $_SESSION['StoreID'];
								$item_type = 1;
								$category = mysql_real_escape_string($special['category']);
								$item = mysql_real_escape_string($special['item']);
								$price = mysql_real_escape_string($special['price']);
								
								$specials_insert = "INSERT INTO aus_soc_website_information_items SET store_id = '" .$store_id. "', item_type = '" .$item_type. "', category = '" . $category . "', item = '" . $item . "', price = '" .$price. "'";
								mysql_query($specials_insert);
							}
						}
						
						if (isset($_POST['stock'])) {
							foreach ($_POST['stock'] as $stock) {
								$store_id = $_SESSION['StoreID'];
								$item_type = 2;
								$category = mysql_real_escape_string($special['category']);
								$item = mysql_real_escape_string($special['item']);
								$price = mysql_real_escape_string($special['price']);
								
								$stock_insert = "INSERT INTO aus_soc_website_information_items SET store_id = '" .$store_id. "', item_type = '" .$item_type. "', category = '" . $category . "', item = '" . $item . "', price = '" .$price. "'";
								mysql_query($stock_insert);
							}		
						}
												
						$query_special_items = "SELECT * FROM aus_soc_website_information_items WHERE store_id = " . $_SESSION['StoreID'] . " AND item_type = 1";
						$result_special_items = mysql_query($query_special_items);
						$specials_table = '';
						if (mysql_num_rows($result_special_items) > 0) {
							$specials_table = '<table>';
							while ($row_special_items = mysql_fetch_assoc($result_special_items)) {
								$specials_table .= '<tr>';
								$specials_table .= '<td>';
								$specials_table .= $row_special_items['category'];
								$specials_table .= '</td>';
								$specials_table .= '<td>';
								$specials_table .= $row_special_items['item'];
								$specials_table .= '</td>';
								$specials_table .= '<td>';
								$specials_table .= $row_special_items['price'];
								$specials_table .= '</td>';
								$specials_table .= '</tr>';
							}
							$specials_table .= '</table>';
						}

						$stock_table = '';
						$query_stock_items = "SELECT * FROM aus_soc_website_information_items WHERE store_id = " . $_SESSION['StoreID'] . " AND item_type = 2";
						$result_stock_items = mysql_query($query_stock_items);
						if (mysql_num_rows($result_stock_items) > 0) {
							$stock_table = '<table>';
							while ($row_stock_items = mysql_fetch_assoc($result_stock_items)) {
								$stock_table .= '<tr>';
								$stock_table .= '<td>';
								$stock_table .= $row_stock_items['category'];
								$stock_table .= '</td>';
								$stock_table .= '<td>';
								$stock_table .= $row_stock_items['item'];
								$stock_table .= '</td>';
								$stock_table .= '<td>';
								$stock_table .= $row_stock_items['price'];
								$stock_table .= '</td>';
								$stock_table .= '</tr>';
							}
							$stock_table .= '</table>';
						}
						
						$mail_info = new PHPMailer();
						$mail_info->AddAddress("programming@webstuff.biz");
						//$mail_info->AddAddress("info@socexchange.com");
						$mail_info->AddReplyTo("noreply@".EMAIL_DOMAIN,"FoodMarketplace");
						$mail_info->SetFrom("noreply@".EMAIL_DOMAIN, "FoodMarketplace");
						$mail_info->Subject = "Website Information Update - " .$_SESSION['UserName'];
						
						$mail_body = "
							<style>
								table {
									width: 100%;
									font-family: 'Arial';
									border:none;
									border-spacing: 0;
									border-collapse: collapse;
								}
								
								table th {
									width: 30%;
									text-align:left;
									border:none;
									padding:5px;
									font-family:'Rockwell';
									background:#CC021F;
									border-radius:5px 5px 0px 0px;
									border-bottom:1px solid #FFC324;
									color:#fff;
								}

								table td {
									width: 70%;
									text-align: left;
									border:none;
									border-bottom:1px solid #CC021F;
									padding: 10px;
									margin: 0px;
								}
								
								.products th {
									width: 70%;
								}
								
								.products td {
									width: 30%;
								}
							
							</style>
							<h1>FoodMarketplace Website Update</h1>
							<h2>Website Details</h2>
							<table>
								<tr> 
									<th>Store ID</th>
									<td>".$_SESSION['StoreID']."</td>
								</tr>
								<tr> 
									<th>User Name</th>
									<td>".$_SESSION['UserName']."</td>
								</tr>
								<tr> 
									<th>Email</th>
									<td>".$_SESSION['email']."</td>
								</tr>
								<tr> 
									<th>Store Site</th>
									<td>".SOC_HTTPS_HOST.$_SESSION['urlstring']."</td>
								</tr>
							</table>
							
							<h2>Update Details</h2>
							<table>
								<tr> 
									<th>Border Colour</th>
									<td>".$_POST['colour']."</td>
								</tr>

								<tr> 
									<th>About Retailer</th>
									<td>".$_POST['about_retailer']."</td>
								</tr>

								<tr>
									<th>Specials</th>
									<td>".$specials_table."</td>
								</tr>
						";
						if (trim($stock_table)) {
							$mail_body .= "
									<tr>
										<th>Stock Items</th>
										<td>".$stock_table."</td>
									</tr>";
						}
						$mail_body .= "	
							</table>";
						
						$message_html_body = "<html><body>".$mail_body."</body></html>";
						
						if (trim($logo_image)) {
							$attachment_logo = $file_path . '/' . $logo_image;
							$extension = pathinfo($logo_image, PATHINFO_EXTENSION);
							$mail_info->AddAttachment($attachment_logo, "logo_image.".$extension);
						}
						
						if (trim($headline_image)) {
							$attachment_headline = $file_path . '/' . $headline_image;
							$extension = pathinfo($headline_image, PATHINFO_EXTENSION);
							$mail_info->AddAttachment($attachment_headline, "headline_image.".$extension);
						}
						
						$mail_info->MsgHTML($message_html_body);
						
						if ($mail_info->Send()) {
							$smarty -> assign('success', 'Website Update Submitted');
						}
						
					} else {
						$smarty -> assign('error', 'Please ensure the form is correctly filled out.');
					}
					
					if (isset($_POST['specials'])) {
						$smarty -> assign('special_items', $_POST['specials']);
					}
					
					if (isset($_POST['stock'])) {
						$smarty -> assign('stock_items', $_POST['stock']);
					}
					
					$smarty -> assign('logo_image', $logo_image);
					$smarty -> assign('headline_image', $headline_image);
					$smarty -> assign('colour', $_POST['colour']);
					$smarty -> assign('about_retailer', $_POST['about_retailer']);
				} else {
					$query_website_info = "SELECT * FROM aus_soc_website_information WHERE store_id = " . $_SESSION['StoreID'] . " LIMIT 1";
					$result_website_info = mysql_query($query_website_info);
					if (mysql_num_rows($result_website_info) > 0) {
						$row_website_info = mysql_fetch_assoc($result_website_info);
						$colour = $row_website_info['colour'];
						$about_retailer = $row_website_info['about_retailer'];
						$logo_image = $row_website_info['logo_image'];
						$headline_image = $row_website_info['headline_image'];
						
						$special_items = array();
						$query_special_items = "SELECT * FROM aus_soc_website_information_items WHERE store_id = " . $_SESSION['StoreID'] . " AND item_type = 1";
						$result_special_items = mysql_query($query_special_items);
						if (mysql_num_rows($result_special_items) > 0) {
							while ($row_special_items = mysql_fetch_assoc($result_special_items)) {
								$category = $row_special_items['category'];
								$item = $row_special_items['item'];
								$price = $row_special_items['price'];
								$special_items[] = array('category' => $category, 'item' => $item, 'price' => $price);
							}
						}
						$smarty -> assign('special_items', $special_items);
						
						$stock_items = array();
						$query_stock_items = "SELECT * FROM aus_soc_website_information_items WHERE store_id = " . $_SESSION['StoreID'] . " AND item_type = 2";
						$result_stock_items = mysql_query($query_stock_items);
						if (mysql_num_rows($result_stock_items) > 0) {
							while ($row_stock_items = mysql_fetch_assoc($row_stock_items)) {
								$category = $row_stock_items['category'];
								$item = $row_stock_items['item'];
								$price = $row_stock_items['price'];
								$stock_items[] = array('category' => $category, 'item' => $item, 'price' => $price);
							}
						}
						$smarty -> assign('stock_items', $stock_items);
						
						$smarty -> assign('logo_image', $logo_image);
						$smarty -> assign('headline_image', $headline_image);
						$smarty -> assign('colour', $colour);
						$smarty -> assign('about_retailer', $about_retailer);
						//$smarty -> assign('specials', $specials);
						//$smarty -> assign('stock_items', $stock_items);					
					}			
				}
				$content = $smarty -> fetch('subscriber.tpl');
				$smarty -> assign('content', $content);
				$smarty -> assign('sidebar', 0);
				$smarty -> assign('is_content',2);
			} else {
				header("Location:/soc.php?cp=subscribe");
			}
		}
		break;
	
	case 'listinghome':
		include_once('class.adminHomeCommon.php');
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Admin System');
		$smarty -> assign('keywords','Admin System');
		
		if ($_SESSION['level'] == 2) {
			header("Location:/soc.php?cp=buyerhome");
		}
		
		if ($_SESSION['level'] == 1) {
			header("Location:/soc.php?cp=sellerhome");
		}
		
		$show_activate = true;
		if (isset($_SESSION['StoreID'])) {
			$fetch_user = "SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_SESSION['StoreID']."'";
			$dbcon->execute_query($fetch_user);
			$user_result = $dbcon->fetch_records();
			if (isset($user_result[0])) {
				$user_data = $user_result[0];
				$show_activate = ($user_data['status'] == 0);
			}
		}
		
		$smarty->assign('show_activate', $show_activate);

		getStoreID($_SESSION['UserID']);
		$sid = $_SESSION['StoreID'];
		
		$req = $socObj->getBusinessHome($sid);
		$req['regsuc'] = $_REQUEST['regsuc'];
		$req['randnum'] = time();
		
		$objAjax = new ajax();

        if (!empty($_SESSION)) {
            $smarty->assign('session', $_SESSION);
        }
		
		$storeWebside = $socObj->displayStoreWebside(true);
		$req['info'] = $storeWebside['info'];
        $smarty->assign('req', $req);
		
		$content = $smarty->fetch('listing_home.tpl');

		$smarty -> assign('xajax_Javascript', $objAjax -> getJSInit());
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty	-> assign('is_content',1);
		break;
		
	case 'sellerhome':
        if (isset($_POST['action']) && $_POST['action'] == 'list') {    
            if(isset($_POST['page_type']) && ($_POST['page_type'] == 1 || $_POST['page_type'] == '1') ){      
                view_photos_seller(true);    
            }else{       
                view_photos_seller(false);
            }
            exit;
        }
		if($_SESSION['UserID']=='' AND $_SESSION['level']!=1){
			header("Location:index.html");
		}
		
		if ($_SESSION['level'] == 3) {
			header("Location:/soc.php?cp=listinghome");
		}
		
		if (isset($_GET['deactivation'])) {
			$account_status_value = (int)$_GET['deactivation'];
			$update_status_value = "UPDATE aus_soc_bu_detail SET status = '".$account_status_value."' WHERE StoreID = '".$_SESSION['StoreID']."'";
			$dbcon->execute_query($update_status_value);
		}
		
		$free_signup = false;
		$verified = false;
		$account_status = false;
		$main_image_none = false;
		$listing = false;
		
		$details_query = "SELECT tmp.MainImg, detail.status As account_status, detail.free_signup, login.status FROM aus_soc_bu_detail detail INNER JOIN aus_soc_login login ON login.StoreID = detail.StoreID INNER JOIN aus_soc_template_details tmp ON tmp.StoreID = detail.StoreID WHERE detail.StoreID = '".$_SESSION['StoreID']."'";
		$dbcon->execute_query($details_query);
		$details_result = $dbcon->fetch_records();
		if (isset($details_result[0])) {
			$details_data = $details_result[0];
			if ($details_data['free_signup'] == 1) {
				$free_signup = true;
			}
			if ($details_data['status'] == 2) {
				$verified = true;
			}
			if ($details_data['account_status'] == 1) {
				$account_status = true;
			}
			if (empty($details_data['MainImg'])) {
				$main_image_none = true;
			}
		}
	
		include_once('class.adminHomeCommon.php');
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Admin System');
		$smarty -> assign('keywords','Admin System');
		if ($_SESSION['attribute']!=0 && $_SESSION['attribute']!=5&& $_SESSION['attribute']!=2&& $_SESSION['attribute']!=1&& $_SESSION['attribute']!=3){
//			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Welcome to Your Admin', 2));
		}
		if($_SESSION['level'] == 2){
			header("Location:/soc.php?cp=buyerhome");
		}

		getStoreID($_SESSION['UserID']);
		$sid = $_SESSION['StoreID'];
		
		$subscriber = false;
		if ($free_signup) {
			$alert = $socObj->getBuyerHome($_SESSION['StoreID']);
			$smarty->assign('alert', $alert);
		} else {
			$query_subscriber = "SELECT *, DATEDIFF(end_date, NOW()) AS days_remaining FROM aus_soc_subscriber WHERE store_id = '".$_SESSION['StoreID']."' AND status != 1";
			$result_subscriber = mysql_query($query_subscriber);
			if (mysql_num_rows($result_subscriber) == 1) {
				$row_subscriber = mysql_fetch_assoc($result_subscriber);
				if ($row_subscriber['days_remaining'] > 0) {
					$subscriber = true;
				}
			}
		}
		
		$fan_count = 0;
		$fan_query = "SELECT COUNT(consumer_id) as fan_count, store_id FROM aus_soc_fans WHERE store_id = '".$_SESSION['StoreID']."'";
		$dbcon->execute_query($fan_query);
		$fan_result = $dbcon->fetch_records();
		if (isset($fan_result[0])) {
			$fan_count = $fan_result[0]['fan_count']; 
		}
		
		$smarty->assign('fan_count', $fan_count);
		$smarty->assign('account_status', $account_status);
		$smarty->assign('verified', $verified);
		$smarty->assign('free_signup', $free_signup);
		$smarty->assign('subscriber', $subscriber);
		$smarty->assign('main_image_none', $main_image_none);
		
		$req = $socObj->getBusinessHome($sid);
		$req['regsuc'] = $_REQUEST['regsuc'];
		$req['randnum'] = time();
		$rooturl = $securt_url;
		$refid = $socObj->getdetailinfo($sid);
		$smarty->assign('refid',$refid);
		$smarty->assign('adminNotices', $socObj -> displayPageFromCMS(94));
		$req['widgetHTML']="<div align=\"center\" style=\"width:539px;\"><a target=\"_blank\" href=\"{$rooturl}soc.php?act=signon&referr={$refid}\"><img src=\"{$rooturl}skin/red/images/refer_user_banner.jpg\" width=\"539\" border=\"0\"/></a></div>";
		$objAjax	=	new ajax();
		$req['element']['jsSaveOuterEmail']	=	$objAjax -> regFun('saveOuterEmail',array(array('this.value',5),array('this.id',5)));
		$objAjax -> processRequest();

        if (!empty($_SESSION)) {
            $smarty->assign('session', $_SESSION);
        }
		$storeWebside = $socObj->displayStoreWebside(true);
		$req['info'] = $storeWebside['info'];
		
		$smarty->assign('renewal_date', date('dS F Y', strtotime(str_replace('/', '-', $req['detail']['renewalDate']))));
		
        //GET FANFRENZY
        include_once ('fanpromo/functions.inc.php');      
        $smarty -> assign('fanfrenzy_text', tab_content($dbcon, 4));
        $smarty->assign('req', $req);
        
        $res_owner = $dbcon->getOne("SELECT COUNT(*) As count FROM photo_promo WHERE consumer_id = '".$_SESSION['StoreID']."'"); 
        $res_retailer = $dbcon->getOne("SELECT COUNT(*) As count FROM photo_promo WHERE store_id = '".$_SESSION['StoreID']."'"); 
        if($res_owner['count'] > 0 || $res_retailer['count'] > 0){
            $smarty->assign('display', true);   
        }else{
            $smarty->assign('display', false);    
        }
		$content = $smarty->fetch('seller_home.tpl');
		if ($_SESSION['attribute'] == 5) {
			$smarty->assign('noShowGalleryBanner', true);
		}  
    
        
		$browser = $socObj->GetClinetBrowser();
		$smarty->assign('isIE8', (5 == $browser[0] and '8.0' == $browser[1]) ? 'yes' : 'no');
		$smarty->assign('isIE7', (5 == $browser[0] and '7.0' == $browser[1]) ? 'yes' : 'no');

		$smarty -> assign('xajax_Javascript', $objAjax -> getJSInit());
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty	-> assign('is_content',1);

		break;

	case 'buyerhome':

        if (isset($_POST['action']) && $_POST['action'] == 'list') {
            view_photos_customer();
            exit;
        }
    
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Buyer Admin System');
		$smarty -> assign('keywords','Buyer Admin System');
//		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Welcome to Your Admin', 2));
		if ($_SESSION['UserID']=='' AND $_SESSION['level'] != 2){
			header("Location:index.php");
		}
		if ($_SESSION['level'] == 1){
				header("Location:/soc.php?cp=sellerhome");
		}
		
		
		$detail_result = $dbcon->getOne("SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_SESSION['StoreID']."'");
		if (isset($detail_result)) {
			$ref_name = $detail_result['ref_name'];
			$smarty->assign('ref_name', $ref_name);
		}
		
		$account_query = "SELECT * FROM bank_details WHERE user_id = '".$_SESSION['StoreID']."'";
		$result = $dbcon->getOne($account_query);
		
		if (isset($result)) {
			$smarty->assign('has_bankaccount', true);
		}
		
		if (isset($_POST['account_submit'])) {
			if (isset($result)) {
				$strCondition = "WHERE user_id = '".$_SESSION['StoreID']."'";
				$account_data = array(
					'account_name'		=>	$_POST['account_name'],
					'account_bsb'  		=> 	$_POST['account_bsb'],
					'account_number'	=>	$_POST['account_number']
				);
				if ($dbcon->update_record('bank_details', $account_data, $strCondition)) {
					$result = $dbcon->getOne($account_query);
				}
			} else {
				$account_data = array(
					'user_id' 			=> 	$_SESSION['StoreID'],
					'account_name'		=>	$_POST['account_name'],
					'account_bsb'  		=> 	$_POST['account_bsb'],
					'account_number'	=>	$_POST['account_number']
				);
				if ($dbcon->insert_record('bank_details', $account_data)) {
					$result = $dbcon->getOne($account_query);
					$point_query = "SELECT * FROM aus_soc_point_records WHERE StoreID = '".$_SESSION['StoreID']."' AND point = '200'";
					$point_result = $dbcon->getOne($point_query);
					if (!isset($point_result)) {
						$point_records = array(
							'StoreID' => $_SESSION['StoreID'],
							'point' => 200
						);
						$dbcon->insert_query('aus_soc_point_records', $point_records);
					}
				}
			}
		}
		
		if (isset($result)) {
			$smarty->assign('account_name', $result['account_name']);
			$smarty->assign('account_bsb', $result['account_bsb']);
			$smarty->assign('account_number', $result['account_number']);
		}
		
		$sid = $_SESSION['ShopID'];
		$req = $socObj->getBuyerHome($sid);
		
		if (isset($_GET['testing'])) {
			$smarty->assign('test_mode', true);
		}
		
		$smarty->assign('user_id', $_SESSION['StoreID']);
		$profile_pic = SITE_ROOT.DIRECTORY_SEPARATOR.'profile_images'.DIRECTORY_SEPARATOR.$_SESSION['ShopID'].'.jpg';
		if (file_exists($profile_pic)) { $smarty->assign('image_uploaded',1); $smarty->assign('profile_image',$profile_pic); }
		$smarty  -> assign('req', $req);
		
	    $res_owner = $dbcon->getOne("SELECT COUNT(*) As count FROM photo_promo WHERE consumer_id = '".$_SESSION['StoreID']."'"); 
        if($res_owner['count'] > 0){
            $smarty->assign('display', true);   
        }else{
            $smarty->assign('display', false);    
        }
		include_once ('fanpromo/functions.inc.php');
		$smarty -> assign('fanfrenzy_text', tab_content($dbcon, 4));
	
		$content = $smarty -> fetch('buyer_home.tpl');

		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('is_content',2);
	

		break;
	case 'buyrefer':
		if($_SESSION['UserID']=='' AND $_SESSION['level']!=2){
			header("Location:index.php");
		}
		include_once ('xajax/xajax_core/xajax.inc.php');
		$xajax 		= new xajax();
		$xajax -> registerFunction('Referpage');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Referrer Payment Request');
		$smarty -> assign('keywords','Buyer Admin System');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Friends I have referred', 3));

		$sid = $_SESSION['ShopID'];
		if(isset($_REQUEST['request_send'])&&$_REQUEST['request_send']=="send"){
			$msg = $socObj->sendrequest($sid);
		}
        /**
         * added by YangBall, 2011-06-30
         */
//        $req['ref'] = $socObj->getBuyerRefer($sid);

        require_once(dirname(dirname(__FILE__)) . '/include/class.referrer.php');
        $referrer = new Referrer();
        $req['ref'] = $referrer->getReferByStoreIDPage($_SESSION['StoreID'], $_GET['page'], 18);
//echo '<pre>';var_dump($req['ref']);exit;
        //END-YangBall
		$refid = $socObj->getdetailinfo($sid);
		$rooturl = $securt_url;
		$req['widgetHTML']="<div align=\"center\" style=\"width:539px;\"><a target=\"_blank\" href=\"{$rooturl}soc.php?act=signon&referr={$refid}\"><img src=\"{$rooturl}skin/red/images/refer_user_banner.jpg\" width=\"539\" border=\"0\"/></a></div>";
		$req['msg'] = $msg;
		$req['checktype'] = $_REQUEST['checktype'];
		if(get_magic_quotes_gpc()){
			$req['name'] = htmlspecialchars(stripslashes($_REQUEST['name']));
			$req['address'] = htmlspecialchars(stripslashes($_REQUEST['address']));
			$req['pname'] = htmlspecialchars(stripslashes($_REQUEST['pname']));
		}else{
			$req['name'] = htmlspecialchars($_REQUEST['name']);
			$req['address'] = htmlspecialchars($_REQUEST['address']);
			$req['pname'] = htmlspecialchars($_REQUEST['pname']);
		}
		$req['refid'] = $socObj->getdetailinfo($sid);
		$req['minamount'] = $socObj->getRefconfig($sid);
		$smarty  -> assign('req', $req);
		$content = $smarty -> fetch('referrer_list.tpl');

		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('is_content',2);
		
		break;
	
	case 'bank_account':
	
		if (isset($_GET['remove'])) {
			$dbcon->execute_query("DELETE FROM bank_details WHERE user_id = '".$_SESSION['StoreID']."'");
		}
		
		$account_query = "SELECT * FROM bank_details WHERE user_id = '".$_SESSION['StoreID']."'";
		$result = $dbcon->getOne($account_query);
		
		//echo var_export($result);
		
		if (isset($_POST['account_submit'])) {
			if (isset($result)) {
				$strCondition = "WHERE user_id = '".$_SESSION['StoreID']."'";
				$account_data = array(
					'account_name'		=>	$_POST['account_name'],
					'account_bsb'  		=> 	$_POST['account_bsb'],
					'account_number'	=>	$_POST['account_number']
				);
				if ($dbcon->update_record('bank_details', $account_data, $strCondition)) {
					$result = $dbcon->getOne($account_query);
				}
			} else {
				$account_data = array(
					'user_id' 			=> 	$_SESSION['StoreID'],
					'account_name'		=>	$_POST['account_name'],
					'account_bsb'  		=> 	$_POST['account_bsb'],
					'account_number'	=>	$_POST['account_number']
				);
				if ($dbcon->insert_record('bank_details', $account_data)) {
					$result = $dbcon->getOne($account_query);
				}
			}
		}
		
		if (isset($result)) {
			$smarty->assign('account_name', $result['account_name']);
			$smarty->assign('account_bsb', $result['account_bsb']);
			$smarty->assign('account_number', $result['account_number']);
		}
	
		$smarty -> assign('pageTitle','Bank Account');
		$smarty -> assign('keywords','Buyer Admin System');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Bank Account', 8,'','/myrace.php?tab=tabrefer'));
	
		$smarty -> assign('req',$req);
		$content = $smarty -> fetch('bank_account.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('is_content',2);
		
		break;
	
	case 'ref_email':
                /**
                 * added by YangBall, 2011-01-07
                 */
                if(!isset($_SESSION['StoreID']) or '' == $_SESSION['StoreID']) {
                    echo '<script type="text/javascript">';
                    echo 'alert("Please login first.");';
                    echo 'location.href="/index.php";';
                    exit('</script>');
                }
                //END-YangBall
		include_once ("class.processcsv.php");
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Refer friends');
		$smarty -> assign('keywords','Buyer Admin System');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Refer friends', 8,'','/soc.php?cp=sellerhome'));
		$sid = $_SESSION['ShopID'];
		$req['own_name'] = str_replace("\"","&quot;",$_SESSION['NickName']);
		$req['own_email'] = $_SESSION['email'];
		$refID = $socObj->getdetailinfo($sid);
                /**
                 * added by YangBall, 2011-01-25
                 * add referer email preview
                 */
                if(isset($_REQUEST['optval'])&&$_REQUEST['optval']=="preview"){
                    $inscription = strip_tags($_POST['inscription']);
					$personal_note = strip_tags($_POST['personal_note']);
                    $own_name = $_REQUEST['own_name']!=""?$_REQUEST['own_name']:$_SESSION['NickName'];
                    $own_email = $_REQUEST['own_email']!=""?$_REQUEST['own_email']:$_SESSION['email'];

                    $arrParams = array('reftype'=>'referrs',
                                       'To'=>'',
                                       'Subject'=>'Your friend invites you to join Food Marketplace',
                                       'fromName'=>$own_name,
                                       'From'=>$own_email,
                                       'nickname'=>'[UserName]',
                                       'refID'=>$refID,
                                       'webside_link'=>	SOC_HTTP_HOST,
                                       'inscription'=>stripslashes($inscription),
									   'personal_note'=>stripslashes($personal_note),
                                       'preview'    =>  true
                        );
                    $smarty -> assign('req', $arrParams);
                    $message =	$smarty -> fetch('email_template/email_referer.tpl');
                    exit($message);
                }
                //END-YangBall
		if(isset($_REQUEST['optval'])&&$_REQUEST['optval']=="send"){

                    /**
                     * added by YangBall, 2011-01-20
                     * change email regards contents
                     */
                     $inscription = strip_tags($_POST['inscription']);
					 $personal_note = strip_tags($_POST['personal_note']);

                    //END-YangBall
			$own_name = $_REQUEST['own_name']!=""?$_REQUEST['own_name']:$_SESSION['NickName'];
			$own_email = $_REQUEST['own_email']!=""?$_REQUEST['own_email']:$_SESSION['email'];
			if($_POST['is_choose_upload']==1){
				$procsv = new processcsv();
				$csvAry =$procsv->showCSVEmail($_SESSION['ShopID'],'referrer');
				$k =0;
				if($csvAry){
					foreach ($csvAry as $pass){
						$arrParams = array('reftype'=>'referrs',
										   'To'=>$pass['emailAddress'],
										   'Subject'=>'Your friend invites you to join Food Marketplace',
										   'fromname'=>$own_name,
										   'From'=>$own_email,
										   'nickname'=>stripslashes($pass['emailName']),
										   'refID'=>$refID,
										   'webside_link'=>	'http://'.$_SERVER['HTTP_HOST'],
										   'inscription'=>stripslashes($inscription),
										   'personal_note'=>stripslashes($personal_note),
                                           'is_in_website' => true,
                                           'hide_padtop' => true
                        );
						$arraySeting = array('StoreID'=>$sid,'nickname'=>$pass['emailName'],'email'=>$pass['emailAddress'],'addtime '=>time(),'inscription'=>$inscription,'personal_note'=>$personal_note);
						$objEmail	=	new emailClass();
						//if(@$objEmail -> send($arrParams,'referrer_email.tpl')){
                                                if(@$objEmail -> send($arrParams,'email_template/email_referer.tpl')){
							$k++;
							$socObj->sendemailintoDB($arraySeting);
						}
						unset($arraySeting);
						unset($objEmail);
					}
				}
			}else{
				for ($i=0,$k=0;$i<10;$i++){
					if (filter_var($_REQUEST['emailaddress'][$i], FILTER_VALIDATE_EMAIL)) { 
						$arrParams = array('reftype'=>'referrs',
										   'To'=>$_REQUEST['emailaddress'][$i],
										   'Subject'=>'Your friend invites you to join Food Marketplace',
										   'fromname'=>$own_name,
										   'From'=>$own_email,
										   'nickname'=>stripslashes($_REQUEST['nickname'][$i]),
										   'refID'=>$refID,
										   'webside_link'=>	'http://'.$_SERVER['HTTP_HOST'],
										   'inscription'=>stripslashes($inscription),
										   'personal_note'=>stripslashes($personal_note),
                                           'is_in_website' => true,
                                           'hide_padtop' => true
                        );
						$arraySeting = array('StoreID'=>$sid,'nickname'=>$_REQUEST['nickname'][$i],'email'=>$_REQUEST['emailaddress'][$i],'addtime '=>time(),'inscription'=>$inscription,'personal_note'=>$personal_note);
						$smarty->assign('req', $arrParams);
						$headers  = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type: text/html; charset=".PB_CHARSET."" . "\r\n";
						$headers .= "To: ".$arrParams['nickname']." <".$arrParams['To'].">" . "\r\n";
						$headers .= "From: ".$arrParams['fromname']." <".$arrParams['From'].">" . "\r\n";
						$message = $smarty->fetch('email_template/email_referer.tpl'); //getEmailTemplate($smarty->fetch('email_template/email_referer.tpl'), $arrParams);
						$mail_sent = mail($arrParams['To'], $arrParams['Subject'], $message, $headers);
						if ($mail_sent) {
							$k++;
							$socObj->sendemailintoDB($arraySeting);
						}
						unset($arraySeting);
					}
				}
			}

			if($k>1){
				$req['msg'] = "$k emails have been sent.";
			}else{
				$req['msg'] = "$k email has been sent.";
			}
                        $url = '/soc.php?cp=ref_email&msg=' . urlencode($req['msg']);
                        header('Location: ' . $url);
                        exit;
//			$req['nickname'] = $_REQUEST['nickname'];
//			$req['emailaddress'] = $_REQUEST['emailaddress'];
//			$req['own_name'] = $_REQUEST['own_name'];
//			$req['own_email'] = $_REQUEST['own_email'];
		}
		$smarty -> assign('req',$req);
		$content = $smarty -> fetch('referrer_contact.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('is_content',2);
		
		break;
	case 'reminderpreview':
        $subject = trim($_POST['subject']);
		$message = trim($_POST['message']);

        $arrParams = array(
                           'Subject'=>$subject,
                           'webside_link'=>	SOC_HTTP_HOST,
                           'message'=>stripslashes($message),
                           'preview'    =>  true
            );
        $smarty -> assign('req', $arrParams);
        $message =	$smarty -> fetch('email_template/email_reminder.tpl');
        //$message = getEmailTemplate($message);
        
        exit($message);
		break;
	case 'refemaillist':
		if($_SESSION['UserID']=='' AND $_SESSION['level']!=2){
			header("Location:index.php");
			exit();
		}
		include_once ('xajax/xajax_core/xajax.inc.php');
		$xajax 		= new xajax();
		$xajax -> registerFunction('Referemail');
		$xajax -> processRequest();
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Refer friends list');
		$smarty -> assign('keywords','Buyer Admin System');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Refer friends list', 8,'','/soc.php?cp=ref_email'));
		$sid = $_SESSION['ShopID'];
		$req['ref'] = $socObj->getEmailList($sid,1);
		$smarty -> assign('req',$req);
		$content = $smarty -> fetch('referrer_emailist_form.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('is_content',2);
		
		break;
	case 'refermessage':
		if($_SESSION['UserID']=='' AND $_SESSION['level']!=2){
			header("Location:index.php");
			exit();
		}
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Refer friends');
		$smarty -> assign('keywords','Buyer Admin System');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Refer friends message', 6));
		if(isset($_REQUEST['msgid'])&&$_REQUEST['msgid']!=""){
			$emailinfo = $socObj->getrefermailinfo($_REQUEST['msgid']);
			$sid = $_SESSION['ShopID'];
			$own_name = $_SESSION['NickName'];
			$own_email = $_SESSION['email'];
			$refID = $socObj->getdetailinfo($sid);
			$arrParams = array('reftype'=>'referrs',
							   'To'=>$emailinfo['email'],
							   'Subject'=>'Your friend invites you to join Food Marketplace',
							   'fromName'=>$own_name,
							   'From'=>$own_email,
							   'nickname'=>$emailinfo['nickname'],
							   'refID'=>$refID,
							   'webside_link'=>	'http://'.$_SERVER['HTTP_HOST'],
							   'inscription'=>$emailinfo['inscription'],
                                                            'is_in_website' =>  true);
			$smarty->assign('req',$arrParams);
			//$content = $smarty->fetch('referrer_email.tpl');  //email_template/email_referer.tpl
            $content = $smarty->fetch('email_template/email_referer.tpl');
			$smarty -> assign('content', $content);
			$smarty -> assign('sidebar', 0);
			$smarty -> assign('is_content',2);
		}
		break;

	case 'whysoc':

		$req = $socObj -> displayPageFromCMS(59);

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Why SOC');
		$smarty -> assign('keywords','Why SOC');

		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Why SOC?'));
		$req['display']	=	'whysoc';
		$smarty -> assign('req', $req);

		$search_type	=	empty($_REQUEST['search_type']) ? 'store' : $_REQUEST['search_type'];
		$smarty -> assign('search_type', $search_type);

		$sidebarContent = $smarty -> fetch('whysoc_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('whysoc.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
		$smarty -> assign('sidebar_bg', '0');
		break;


            /*
             * YangBall
             * 2010-10-22
             * Bug #6567
             */
            case 'media0':
		$req = $socObj -> displayPageFromCMS(99);

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Media');
		$smarty -> assign('keywords','Why SOC');
		$req['mediasider'] = "marketwatch";
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('media_new_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('whysoc.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('sidebar_bg', '0');
		$smarty -> assign('is_media', '1');
		break;

	case 'media1':
		$req = $socObj -> displayPageFromCMS(72);

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Media');
		$smarty -> assign('keywords','Why SOC');
		$req['mediasider'] = "washington";
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('media_new_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('whysoc.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('sidebar_bg', '0');
		$smarty -> assign('is_media', '1');
		break;
	case 'media3':
		$req = $socObj -> displayPageFromCMS(60);

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Media');
		$smarty -> assign('keywords','Why SOC');
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('media_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('whysoc.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('sidebar_bg', '0');
		$smarty -> assign('is_media', '1');
		break;
	case 'media2':
		$req = $socObj -> displayPageFromCMS(65);

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Media');
		$smarty -> assign('keywords','Why SOC');
		$req['mediasider'] = 'meida3';
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('media_new_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('media_new.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('sidebar_bg', '0');
		$smarty -> assign('is_media', '1');
		break;
	case 'media4':
		$req = $socObj -> displayPageFromCMS(74);

		$req['mediasider'] = 'meida4';
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Media');
		$smarty -> assign('keywords','Why SOC');
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('media_new_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('whysoc.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('sidebar_bg', '0');
		$smarty -> assign('is_media', '1');
		break;
	case 'media5':
		$req = $socObj -> displayPageFromCMS(75);

		$req['mediasider'] = 'meida5';
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Media');
		$smarty -> assign('keywords','Why SOC');
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('media_new_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('whysoc.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
        $smarty->assign('hide_race_banner',1);
		$smarty -> assign('sidebar_bg', '0');
		$smarty -> assign('is_media', '1');
		break;
	case 'media6':
		$req = $socObj -> displayPageFromCMS(88);

		$req['mediasider'] = 'meida6';
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Media');
		$smarty -> assign('keywords','Why SOC');
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('media_new_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('whysoc.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
		$smarty -> assign('sidebar_bg', '0');
		$smarty -> assign('is_media', '1');
		break;
	case 'media':
		$search_type	=	empty($_REQUEST['search_type']) ? 'store' : $_REQUEST['search_type'];
		$smarty -> assign('search_type', $search_type);
		$req = $socObj -> displayPageFromCMS(71);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Media');
		$smarty -> assign('keywords','Media');
		$req['mediasider'] = "landingpage";
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('media_new_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('whysoc.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
		$smarty -> assign('sidebar_bg', '0');
		$smarty -> assign('is_media', '1');
		break;

	case 'certified-bidder-auctions':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Certified Bidder Auctions');
		$smarty -> assign('keywords','Certified Bidder Auctions');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Certified Bidder Auctions'));
		$req 	= $socObj -> displayPageFromCMS(95);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
		break;

	case 'mediaplay':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Media');
		$smarty -> assign('keywords','Media Play');

		$smarty -> assign('browser',$socObj->GetClinetBrowser());
		$content = $smarty -> fetch('media_play.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);

		break;

	case 'business_get_step_stat':
		echo 'test';
		include_once ('xajax/xajax_core/xajax.inc.php');
		$xajax 		= new xajax();
		$xajax -> registerFunction('getpreItemStat');
		$xajax -> processRequest();
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Website Statistic');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Website Statistic', 8,'',"/soc.php?cp=sellerhome"));
		//Initial template and StoreID
		getStoreID($_SESSION['UserID']);
		//Gets current storeid
		$sid = $_SESSION['StoreID'];

		$req = $socObj->getBusinessStat($sid);
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('business_get_step_stat.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		
		break;
	case 'wishlist_get_step_stat':

		include_once ('xajax/xajax_core/xajax.inc.php');
		$xajax 		= new xajax();
		$xajax -> registerFunction('getpreItemStat');
		$xajax -> processRequest();
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Website Statistic');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Wishlist Statistic', 8,'',"/soc.php?act=wishlist&step=2"));
		//Initial template and StoreID
		getStoreID($_SESSION['UserID']);
		//Gets current storeid
		$sid = $_SESSION['StoreID'];

		$req = $socObj->getBusinessStat($sid,'WISHLIST');
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('business_get_step_stat.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		break;

	case 'changepass':

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Change Password');
		$smarty -> assign('keywords','Change Password');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Change Password', 3));
		$req = array();
		if (!empty($_POST)) {
			$req['msg'] = $socObj->changePass();
		}

		$req['Password'] = $_SESSION['Password'];
		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('change_pass.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		
		break;

	case 'payreports':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Pay Reports');
		$smarty -> assign('keywords','Pay Reports');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Pay Reports', 3));

		$req = $socObj->payReports();

//		$paypalInfo = SIGNON_PAYMENT == 'ipg' ?$socObj->getIPGInfo() : $socObj->getPaypalInfo();
        //$paypalInfo['paypal_url'] = SIGNON_PAYMENT == 'ipg' ?$paypalInfo['paypal_url']:SOC_HTTPS_HOST."soc.php?act=signon&step=payment_paypal";
                /**
                 * added by YangBall, 2011-02-16
                 * add NR payment moethod
                 */
                 if('ipg' == SIGNON_PAYMENT) {
                     $req['paymentMethod'] = 'ipg';
                     $paypalInfo = $socObj->getIPGInfo();
                 }
                 elseif('nr' == SIGNON_PAYMENT) {
                     $req['paymentMethod'] = 'nr';
                     require_once(dirname(__FILE__) . '/../include/class.paymentNR.php');

                     $NR = new PaymentNR();
                     $paypalInfo = $NR->getPaymentInfo();
                 }
                 else {
                     $req['paymentMethod'] = 'paypal';
                     $paypalInfo = $socObj->getPaypalInfo();
                 }

                //END-YangBall
		
		$req['paymentMethod'] = '';
		$paypalInfo['paypal_url'] = "/soc.php?cp=payreports_payment";

		$smarty -> assign('paypalInfo', $paypalInfo);

		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('payreports.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		break;
	case 'payreports_payment':
		if(isset($_POST)){
			$req['paypal_info'] = $_POST;

			$_var 		= 	$socObj->setFormInuptVar();
			extract($_var);

			$query	="select count(*) from  ".$socObj->table."login as t1 where t1.StoreID != '$_SESSION[ShopID]' and t1.user='".$bu_user."' and t1.attribute = '{$_SESSION['attribute']}'";
			$result	=	$dbcon->execute_query($query) ;
			$arrCheck = $dbcon->fetch_records() ;

			if($attribute==0){
				$query	="select count(*) as num from  ".$socObj->table."bu_detail as t1
				 where t1.StoreID != '$_SESSION[ShopID]' and t1.bu_name='".$bu_name."'  and t1.attribute=0"
				 . " AND t1.renewalDate > " .time();
				$result	=	$dbcon->execute_query($query) ;
				$arrCheckBuname = $dbcon->fetch_records(true);
			}else{
				$arrCheckBuname[0]['num'] = 0;
			}

			$url_store_name = clean_url_name($bu_urlstring);
			//$QUERY = "select count(*) as num from ".$this->table."login where store_name='".$url_store_name."' and StoreID!='$_SESSION[ShopID]'";
			$QUERY = "select count(*) as num from ".$socObj->table."login as t1 left join "
					.$socObj->table."bu_detail as t2 on t1.StoreID=t2.StoreID "
					."where t1.store_name='".$url_store_name."' AND ((t2.renewalDate > ".time().") or (t2.`attribute`=3 && t2.`subAttrib`=3))"." and t1.StoreID!='$_SESSION[ShopID]'";

			$dbcon->execute_query($QUERY);
			$store_name_check = $dbcon->fetch_records();
			$store_name_check = $store_name_check[0]['num'];



			if ($attribute == 0 && $arrCheckBuname[0]['num'] > 0 || checkTmpcustomExist(array('bu_name'=> $bu_name) )) {
				$msg	= "Web Name exists. Please try again.";
				header('Location: /soc.php?cp=payreports&msg='.$msg);
				exit;
			}elseif ($store_name_check>0 || checkTmpcustomExist(array('bu_urlstring'=> $url_store_name) )){
				$msg	= "This URL string has already been used.\n Please create another.";
				header('Location: /soc.php?cp=payreports&msg='.$msg);
				exit;
			}else{
				$arrSetting = array(
					'pid'		=> 0,
					'buyer_id'	=> $_POST['StoreID'],
					'StoreID'	=> $_POST['StoreID'],
					'p_status'	=> 'order',
					'description'=>'PayPal',
					'order_date' =>time(),
					'type'		=> 'renew',
					// comment by jessee, all renew is limited to 1 year now, 20100506
//					'amount'	=> $_POST['amount'],
//					'month'		=> $_POST['amount']>9?12:$_POST['amount'],
					'amount'	=> 10,
					'month'		=> 12,
				);

				$dbcon->insert_record($table.'order_reviewref',$arrSetting);
				$ref_id = $dbcon->lastInsertId();

				$arrSetting['bu_name']		= $_POST['bu_name'];
				$arrSetting['bu_urlstring']	= $_POST['bu_urlstring'];
				$arrSetting['ref_id']		= $ref_id;

				foreach ($arrSetting as $key=>$values){
					$tmpstr .= $tmpstr==""?"$key=".urlencode($values):"&$key=".urlencode($values);
				}
				$_POST['custom'] = addtmpcustom($tmpstr);

				$req['paypal_info']['custom']=$req['paypal_info']['custom']."&tmp_id={$_POST['custom']}";
				$paypal_setting = getPaypalInfo();
				if ($paypal_setting['paypal_mode'] == 0) {
					$req['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr/';
				}else{
					$req['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr/';
				}
				$req['paypal_title'] = "Paypal Information";
				$smarty->assign('req',$req);
				$smarty->display('paypal.tpl');
				exit();
			}
		}else{
			header('Location: /soc.php?cp=payreports');
			exit;
		}
		break;
	case 'inbox':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Alert Inbox');
		$smarty -> assign('keywords','Alert Inbox');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Email Messages', 7, '', '/soc.php?cp=sellerhome'));

		$req = $socObj->inbox();
		if ($_REQUEST['msg']) $req['msg'] = $_REQUEST['msg'];
		$smarty -> assign('req', $req);
		if(isset($_REQUEST['appnew'])&&$_REQUEST['appnew']==1){
			$content = $smarty -> fetch('newinbox.tpl');
		}else{
			$content = $smarty -> fetch('inbox.tpl');
		}
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		
		break;

	case 'customers_geton_alerts':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - My Email Alerts');
		$smarty -> assign('keywords','My Email Alerts');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('My Email Alerts', 3));
		$req = $socObj->customersGetonAlerts();
		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('customers_geton_alerts.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		
		break;

	case 'bookads':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Book Your Advertising On FoodMarketplace');
		$smarty -> assign('keywords','Book Your Advertising On FoodMarketplace');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Book Your Advertising On FoodMarketplace', 3));
		if (!empty($_POST)) {
			$req = $socObj->adStatus();
			$req['task'] = 'book';
		} else {
			$req = $socObj->bookAds();
		}
		$storeinfo = $socObj->getStoreInfo($_SESSION['ShopID']);
		$cmsid = ($storeinfo['attribute'] == 5) ? 120 : 18;
		if ($storeinfo['attribute'] == 5) {
			$foodWine = new FoodWine();
			$foodWineType = getFoodWineType();
	        $productinfo = $foodWine->getProductsList($_SESSION['StoreID'], $foodWineType);
	        $req['products'] = $productinfo['items'];
		}
		$req['council'] 	= getCouncilBySuburb($storeinfo['bu_suburb']);
		$req['content'] 	= $socObj -> displayPageFromCMS($cmsid);
		$req['companyline'] = htmlspecialchars($req['companyline']);
		$req['addressline'] = htmlspecialchars($req['addressline']);
		$req['phoneline']   = htmlspecialchars($req['phoneline']);
		$req['statename']	= htmlspecialchars($req['statename']);
		$req['collegename'] = htmlspecialchars($req['collegename']);
		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('bookads.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		
		break;

	case 'signup':	
		$query	= "SELECT stateName, description FROM aus_soc_state ORDER BY description";
		$result	= $dbcon->execute_query($query);
		$state_list = $dbcon->fetch_records();
		$smarty -> assign('state_list', $state_list);
		
		$smarty -> assign('req', $state_list);
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Buying on Food Marketplace');
		$smarty -> assign('keywords','Buying on Food Marketplace');
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('cgeton_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('signup.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
		break;
		
	case 'customers_geton':

		if ($_SESSION['level'] == 1) {
			//header('Location:soc.php?cp=business_get_step_home');
			header('Location:soc.php?cp=sellerhome');
			exit();
		} elseif ($_SESSION['level'] == 2) {
			header('Location:soc.php?cp=home');
			exit();
		}

		if (!empty($_POST)) {
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Congratulations', 2));
			$req = $socObj->customersGetonSubmit();
			$req['task'] = 'submit';
			$smarty -> assign('sidebar', 0);
		} else {
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Buying on Food Marketplace'));
			$req = $socObj->customersGeton();
			$smarty -> assign('sidebar_bg', '0');
		}
		
		$req['userNameC'] = $req['userNameC'] ? $req['userNameC'] : $_REQUEST['email'];
		$req['userNameC'] = $req['userNameC'] ? $req['userNameC'] : $_REQUEST['email'];
		$req['msg'] = $req['msg'] ? $req['msg'] : $_REQUEST['msg'];

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Buying on Food Marketplace');
		$smarty -> assign('keywords','Buying on Food Marketplace');
		$smarty -> assign('req', $req);
		$sidebarContent = $smarty -> fetch('cgeton_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('customers_geton.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_content',1);
		break;

	case 'edit_customers_geton':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Free Member Update');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Free Member Update', 3));
		if($_REQUEST['op']=='suburb') {
			$Subburb	= '<select name="bu_suburb" id="bu_suburb" class="inputB">';
			$stateid = $_REQUEST['SID'];
			$_REQUEST['selectSubburb'] = $_REQUEST['sname'];
			$cities = getSuburbArray($stateid ? $stateid : 6);
			$Subburb.= '<option value="">Select City</option>';
			foreach ($cities as $row){
				$Subburb.= '<option value="'.$row['bu_suburb'].'" ';
				$Subburb.= $row['selected'];
				$Subburb.= '>'.$row['bu_suburb'].'</option>';
			}
			$Subburb	.= "</select>";
			echo $Subburb;
			exit;
		} else {
			$req = $socObj->editCustomersGeton();
		}

		$req['msg'] = $_REQUEST['msg'];
		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('edit_customers_geton.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('is_content',1);
		break;

	case 'finalstep':
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Final Step', 2));
		$req = $socObj->finalstep();

		$req['paymentdebug'] = PAYPAL_DEBUG;
		$smarty -> assign('req', $req);
		$content = $smarty -> fetch('finalstep.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		break;

	case 'loadicon':
		$categoryid = intval(isset($_REQUEST['websiteicon']) ? $_REQUEST['websiteicon'] : 0);
		if ($categoryid > 0) {
			$iconimg = $socObj -> loadIcon($categoryid);
			echo '<img src="'.$iconimg.'" alt="" border="0">';
		}
		exit;
		break;

	case 'slidingshow':
		$smarty -> assign('Title', 'Product Images');
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$pid = $_REQUEST['pid'];
		$url = $_REQUEST['url'];
		if (!is_numeric($StoreID) or !is_numeric($pid)){
			echo "<script>alert('Invalid parameter.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		$req = $socObj->getSlidingImages($StoreID,$pid,$url);
		if(is_array($req['big'])&&is_array($req['small'])){
			$tmpselect = 1;
			$i = 0;
			$bigtmpary = array();
			foreach ($req['big'] as $pass){
				if($pass['text']!="/images/700x525.jpg"){
					$bigtmpary[$i] = $pass;
					$i++;
				}
			}
			$req['big'] = $bigtmpary;
			$i = 0;
			$smalltmpary = array();
			foreach ($req['small'] as $key=>$pass){
				if($key == ($req['select']-1)){
					$tmpselect = $i;
				}
				if($pass['text']!="/images/243x212.jpg"){
					$smalltmpary[$i] = $pass;
					$i++;
				}
			}
			$req['small'] = $smalltmpary;
			$req['select'] = $tmpselect+1;
		}
		$smarty -> assign('req', $req);
		if(getonlieType($StoreID)=='estate'){
			$smarty -> assign('adrdis',1);
		}else{
			$smarty -> assign('adrdis',0);
		}
		$smarty -> display('slideShow.tpl');
		exit;
		break;
	case 'sliding2show':
		$smarty -> assign('Title', 'Product Images');
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$pid = $_REQUEST['pid'];
		$url = $_REQUEST['url'];
		if (!is_numeric($StoreID) or !is_numeric($pid)){
			echo "<script>alert('Invalid parameter.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		$req = $socObj->getSlidingWishImages($StoreID,$pid,$url);
		if(is_array($req['big'])&&is_array($req['small'])){
			$tmpselect = 1;
			$i = 0;
			$bigtmpary = array();
			foreach ($req['big'] as $pass){
				if($pass['text']!="/images/700x525.jpg"){
					$bigtmpary[$i] = $pass;
					$i++;
				}
			}
			$req['big'] = $bigtmpary;
			$i = 0;
			$smalltmpary = array();
			foreach ($req['small'] as $key=>$pass){
				if($key == ($req['select']-1)){
					$tmpselect = $i;
				}
				if($pass['text']!="/images/243x212.jpg"){
					$smalltmpary[$i] = $pass;
					$i++;
				}
			}
			$req['small'] = $smalltmpary;
			$req['select'] = $tmpselect+1;
		}
		$smarty -> assign('req', $req);
		if(getonlieType($StoreID)=='estate'){
			$smarty -> assign('adrdis',1);
		}else{
			$smarty -> assign('adrdis',0);
		}
		$smarty -> display('slideShow.tpl');
		exit;
		break;

		//add by roy 20090112 login form
	case 'login':
		$smarty -> assign('pageTitle', 'Sell Goods Online - Selling Online - Login');
		switch ($_GET['type']){
			case 1:
				$req['buydisp'] = "";
				$req['sllerdisp'] = "none";
				break;
			case 2:
				$req['buydisp'] = "none";
				$req['sllerdisp'] = "";
				break;
			default:
				$req['buydisp'] = "none";
				$req['sllerdisp'] = "none";
				break;
		}
		$reurl = $_GET['reurl'];
		$req['reurl'] = $reurl;
		
		$lang = $_LANG;
		krsort($lang['seller']['attribute']);
		$smarty	-> assign('lang', $lang);
		$smarty -> assign('req',$req);
		if ($_SESSION['UserID'] != "") {
			if ($_SESSION['level'] == "2") {
				$reurl = $reurl ? urldecode($reurl) : 'soc.php?cp=buyerhome';
				//header("Location: soc.php?cp=home");
				header("Location: " . $reurl);
			} else {
				$reurl = $reurl ? urldecode($reurl) : 'soc.php?cp=sellerhome';
				//header("Location: soc.php?cp=business_get_step_home");
			}
			header("Location: " . $reurl);
			exit();
		} else {
			if (isset($_REQUEST['reurl'])) {
				$smarty->assign('reurl', $_REQUEST['reurl']);
			}
			
			if ($_REQUEST['from'] == 'buy') {					
				$lang = $_LANG;
				krsort($lang['seller']['attribute']);
				$smarty	-> assign('lang', $lang);
				$content = $smarty -> fetch('login.tpl');
				$smarty->assign('sidebar',0);
				$smarty->assign('hideTopTypeMenu',1);
				$smarty->assign('div','middle');
				$smarty->assign('hideLeftMenu',1);
			} else {
				if (isset($_GET['error'])) {
					$smarty	-> assign('error', 'Invalid login account');
				}
				$content = $smarty -> fetch('login.tpl');
			}
			
			$smarty -> assign('content', $content);
			$smarty -> assign('is_home',1);
		}
		break;
	case 'register':
		$smarty -> assign('pageTitle', 'Sell Goods Online - Selling Online - Register');
		if (isset($_GET['team'])) {
			$smarty->assign('team', $_GET['team']);
		}
		
		if (isset($_REQUEST['referr']) && (!empty($_REQUEST['referr']))) {
			setcookie('cookieRefer', $_REQUEST['referr'], time()+604800);
			header("Location: /soc.php?cp=register");
			exit();
		}
		
		
		$content = $smarty -> fetch('register.tpl');
		$smarty -> assign('content', $content);
		//$smarty->assign('is_home',1);
		break;
		//add by roy 20090205 sample website
	case "samplesite":
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Sample Websites');
		$smarty -> assign('sampsitelist',getSamplesitebyNames($samplesiteid));
		$content	=	$smarty -> fetch('sample_site.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty->assign('is_content',1);
		break;
	case 'youtube':
		$smarty -> assign('pageTitle','TV Advertisements');
		$content = $smarty -> fetch('yutube.tpl');
		$smarty -> assign('content',$content);
		$smarty->assign('is_category',1);
		$req['categoryName'] = 'TV Advertisements';
		$smarty->assign('req',$req);
		break;
	case 'advtv':
		$content = $smarty -> fetch('adv_tv.tpl');
		$smarty -> assign('content',$content);
		$smarty->assign('is_home',1);
		break;
		//display store
	case 'lite':
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Lite'));
		$req 	= $socObj -> displayPageFromCMS(63);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty -> assign('content',$content);
		$smarty->assign('is_home',1);
		break;
		//display store
	case 'z100':
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Z100'));
		$req 	= $socObj -> displayPageFromCMS(64);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('about.tpl');
		$smarty -> assign('content',$content);
		$smarty->assign('is_home',1);
		break;
		//display store
	case 'membership':
		$req = $socObj -> displayPageFromCMS(67);

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Membership');
		$smarty -> assign('keywords','Membership');
		$smarty -> assign('req', $req);

		$sidebarContent = $smarty -> fetch('membership_sidebar.tpl');
		$smarty -> assign('sidebarContent', $sidebarContent);
		$content = $smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('hid_left_banner',0);
		$smarty->assign('is_content',1);
		$smarty->assign('sidebar',0);
		$smarty -> assign('sidebar_bg', '0');
		break;
	case 'estatepage':
		$smarty -> assign('pageTitle','REAL ESTATE @ Food Marketplace');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('REAL ESTATE @ Food Marketplace'));
		$search_type	=	'estate';
		$smarty -> assign('search_type', $search_type);

		$sidebarContent = $smarty -> fetch('newseller_sidebar.tpl');
		$smarty -> assign('sidebarContent',$sidebarContent);
		$content = $smarty -> fetch('EstatePage.tpl');
		$smarty -> assign('content', $content);
		//$smarty->assign('hid_left_banner',1);
		$smarty->assign('is_content',1);
		$smarty -> assign('sidebar_bg', '0');
		break;
	case 'jobpage2':
		$smarty -> assign('pageTitle','JOBS @ Food Marketplace');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('JOBS @ Food Marketplace'));
		$search_type	=	'job';
		$smarty -> assign('search_type', $search_type);
		$smarty -> assign('newrightJob',1);
		$sidebarContent = $smarty -> fetch('newseller_sidebar.tpl');
		$smarty -> assign('sidebarContent',$sidebarContent);
		$content = $smarty -> fetch('JobPage.tpl');
		$smarty -> assign('content', $content);
		//$smarty->assign('hid_left_banner',1);
		$smarty->assign('is_content',1);
		$smarty -> assign('sidebar_bg', '0');
		break;
	case 'jobpage':
		$smarty -> assign('pageTitle','JOBS @ Food Marketplace');
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('JOBS @ Food Marketplace'));
		$search_type	=	'job';
		$smarty -> assign('search_type', $search_type);

		$sidebarContent = $smarty -> fetch('newseller_sidebar.tpl');
		$smarty -> assign('sidebarContent',$sidebarContent);
		$content = $smarty -> fetch('jobpage2.tpl');
		$smarty -> assign('content', $content);
		//$smarty->assign('hid_left_banner',1);
		$smarty->assign('is_content',1);
		$smarty -> assign('sidebar_bg', '0');
		break;
	case 'jokes':
		$smarty -> assign('pageTitle','SOC Stars');
		$smarty -> assign('keywords','Fun');
		$objAdminJokes = new adminJokes();
		$keywords = $_REQUEST['jokekeywords'];
		if($keywords!=""&&strlen($keywords)<3){
			$req['jokelist'] = null;
		}else{
			if (strlen(trim($keywords))>100){
				$keywords = substr($keywords,0,100);
			}
			$req['jokelist'] =	$objAdminJokes -> getJokesItemlist($keywords);
		}
		if(get_magic_quotes_gpc()){
			$req['jokekeywords'] = stripslashes($keywords);
		}else{
			$req['jokekeywords'] = $keywords;
		}
		$req['urlkeyword'] = urlencode($req['jokekeywords']);
		$req['jokekeywords'] = htmlspecialchars($req['jokekeywords']);
		$smarty -> assign('req', $req);
		$content =	$smarty -> fetch('jokes_form.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
		break;
	case 'jokesinfo':
		$smarty -> assign('keywords','FUN');
		$objAdminJokes = new adminJokes();
		$keywords = $_REQUEST['jokekeywords'];
		if(strlen($keywords)<3){
			$keywords = "";
		}
		$req['jokeinfo'] =	$objAdminJokes -> getJokesItemInfo($_REQUEST['id'],$keywords);
		$helpinfo =	$objAdminJokes -> getJokesItemInfo($_REQUEST['id']);
		$smarty -> assign('pageTitle','SOC Stars - '.$helpinfo['title']);
		if(get_magic_quotes_gpc()){
			$req['jokekeywords'] = stripslashes($keywords);
		}else{
			$req['jokekeywords'] = $keywords;
		}
		$req['jokekeywords'] = htmlspecialchars($req['jokekeywords']);

		if(isset($_POST['sendemail'])&&$_POST['sendemail']==1){
			if(trim($_REQUEST['nickname'])!=""){
				if(strtolower($_POST['vc_code'])!=strtolower($_SESSION['authnum'])){
					$req['msg'] = "Validation Code is invalid.";
				}else{
					for ($i=0,$k=0;$i<5;$i++){
						if (eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$_REQUEST['Email'][$i])) {
							$arrParams = array('To'=>$_REQUEST['Email'][$i],
											   'Subject'=>'Your friend '.$_REQUEST['nickname'].' thought you should see this...',
											   'nickname'=>$_REQUEST['nickname'],
											   'webside_link'=>	'http://'.$_SERVER['HTTP_HOST']."/soc.php?cp=jokesinfo&id=".$_REQUEST['id'],
											   'email_regards'=>$email_regards);
							$objEmail	=	new emailClass();
							if(@$objEmail -> send($arrParams,'jokes_email.tpl')){
								$k++;
							}
							unset($arrParams);
							unset($objEmail);
						}
					}
					if($k>1){
						$req['msg'] = "$k emails have been sent.";
					}else{
						$req['msg'] = "$k email has been sent.";
					}
				}
			}else{
				$req['msg'] = "Your Name is required.";
			}
		}

		$smarty -> assign('req', $req);
		$smarty -> assign('isinfo', 1);
		$content =	$smarty -> fetch('jokes_form.tpl');
		$smarty->assign('is_content',1);
		$smarty -> assign('content', $content);
		break;
	case 'tellafriend':
		$req = $socObj -> displayPageFromCMS(69);

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Tell a friend');
		$smarty -> assign('keywords','Tell a friend');
		$smarty -> assign('req', $req);

		$content = $smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_home',1);
		$smarty -> assign('sidebar_bg', '0');
		break;
	case 'socfees':
		$req = $socObj -> displayPageFromCMS(70);

		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Collecting your SOC fees');
		$smarty -> assign('keywords','SOC fees');
		$smarty -> assign('req', $req);

		$content = $smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_home',1);
		$smarty -> assign('sidebar_bg', '0');
		break;
	case 'saleshistory':
		if (empty($_SESSION['LOGIN'])){
			echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
			exit;
		}
		include_once ('xajax/xajax_core/xajax.inc.php');
		$xajax 		= new xajax();
		$xajax -> registerFunction('getsalelogX');
		$xajax -> processRequest();
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Sales History');
		$smarty -> assign('keywords','Sales History');
		$pageTitle = 'Transaction History';//($_SESSION['attribute'] == 0)?'Transaction History':'Purchase History';
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle($pageTitle,8,'','/soc.php?cp=sellerhome'));
		$req = $socObj->getsalelog($StoreID);
		$req['xajax_Javascript']   = $xajax -> getJavascript('/include/xajax');
		$smarty->assign('req',$req);
		$smarty->assign('StoreID',$StoreID);
		$smarty->assign('page',1);
		$content = $smarty -> fetch('salelog.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);
		$smarty -> assign('is_content',2);
		
		break;
	case 'sendmail':
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Sales History');
		$smarty -> assign('keywords','Sales History');

		if(isset($_REQUEST['buyer'])&&trim($_REQUEST['buyer'])!=""){
			$query = "SELECT * FROM {$table}bu_detail WHERE StoreID='".base64_decode($_REQUEST['buyer'])."' limit 1";
			$dbcon ->execute_query($query);
			$result = $dbcon->fetch_records(true);
			if($result){
				$_SESSION['NickName'] = $result[0]['bu_nickname'];
				$_SESSION["UserName"] = $result[0]['bu_name'];
				$_SESSION['email']  = $result[0]['bu_email'];
				$_SESSION['LOGIN'] = "login";
			}
		}
		$subject  = $_REQUEST['title'];
		$req['einfo']['LOGIN'] = trim($_SESSION['LOGIN']);
		$req['einfo']['StoreName'] = stripslashes(getStoreByName($_REQUEST['StoreID']));
		$req['einfo']['StoreID'] = $_REQUEST['StoreID'];
		$req['einfo']['NickName'] = $_SESSION['NickName'];
		$req['einfo']['UserName'] = $_SESSION['UserName'];
		$req['einfo']['subject'] = $subject;
		$req['einfo']['emailaddress'] = $_SESSION['email'];
		if($_POST['sendemail']){

			$objCommon	=	new common();
			$_var 		= 	$objCommon -> setFormInuptVar();
			extract($_var);
			$message		=	$body;
			$emailaddress	=	$fromEmail;
			$date		=	time();

			$_query	=	"select attribute,subAttrib,bu_email, bu_nickname, outerEmail from ".$GLOBALS['table']."bu_detail where StoreID='$StoreID'" ;
			$dbcon->execute_query($_query);
			$arrTemp = $dbcon->fetch_records(true);
			if (is_array($arrTemp)){
				$arrSetting	=	array(
				"subject"		=>	$subject,
				"message"		=>	$message,
				"phone"			=>	"",
				"StoreID"		=>	$StoreID,
				"date"			=>	$date,
				"emailaddress"	=>	$emailaddress,
				"fromtoname"	=>	$fromName,
				"pid"			=>  0
				);
				if($arrTemp[0]['attribute']!=4&&$arrTemp[0]['subAttrib ']!=3){
					if($dbcon-> insert_record($GLOBALS['table']."message", $arrSetting)){
						$arrTemp	=	$arrTemp[0];
						if ($arrTemp['outerEmail'] >0 ) {
							$arrParams	=	array(
							'display'			=>	'contact',
							'To'				=>	$arrTemp['bu_email'],
							'Subject'			=>	$subject,
							'seller_nickname'	=>	$_REQUEST['fromName'],
							'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST'],
							'email_regards'		=>	$email_regards
							);
							$arrParams['cusType']   =   'seller';
							$arrParams['fromName']	=	"";
							$arrParams['fromEmail']	=	"";
							$arrParams['StoreID'] = base64_decode($_REQUEST['buyer']);
							$arrParams['buyer'] = base64_encode($_REQUEST['StoreID']);
							$arrParams['message']	=	str_replace("''","'",$message);
							$objEmail	=	new emailClass();
							$objEmail -> send($arrParams,'email_contact_seller.tpl');
							unset($objEmail);
						}
						$msg = 'Your email has been sent. ' ;
					}else{
						$msg = $dbcon->_errorstr;
					}
				}else{
						$arrTemp	=	$arrTemp[0];
						$arrParams	=	array(
						'display'			=>	'contact',
						'To'				=>	$arrTemp['bu_email'],
						'Subject'			=>	$subject,
						'seller_nickname'	=>	$_REQUEST['fromName'],
						'webside_link'		=>	'http://'.$_SERVER['HTTP_HOST'],
						'email_regards'		=>	$email_regards
						);
						$arrParams['cusType']   =   'buyer';
						$arrParams['fromName']	=	"";
						$arrParams['fromEmail']	=	"";
						$arrParams['StoreID'] = base64_decode($_REQUEST['buyer']);
						$arrParams['buyer'] = base64_encode($_REQUEST['StoreID']);
						$arrParams['message']	=	str_replace("''","'",$message);
						$objEmail	=	new emailClass();
						$objEmail -> send($arrParams,'email_contact_seller.tpl');
						unset($objEmail);
						$msg = 'Your email has been sent. ' ;
				}
			}else{
				$msg = 'Your email faild to send. ' ;
			}
			$req['einfo']['msg'] = $msg;

		}
		$smarty->assign('req',$req);
		$content = $smarty -> fetch('sendmail.tpl');
		$smarty -> assign('content', $content);
		break;
		/**
		 * for some instruction
		 */
		case 'youtubeinstruction':
			$req = $socObj -> displayPageFromCMS(77);
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Youtube Video Instruction'));
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Youtube Video Instruction');
			$smarty -> assign('keywords','Youtube Video Instruction');
			$smarty -> assign('req', $req);
			$content = $smarty -> fetch('about.tpl');
			$smarty -> assign('content', $content);
			$smarty->assign('hid_left_banner',0);
        $smarty->assign('hide_race_banner',1);
			$smarty->assign('is_content',1);
			$smarty->assign('sidebar',0);
			$smarty -> assign('sidebar_bg', '0');
		break;
		case 'bulkinstruction':
			$req = $socObj -> displayPageFromCMS(78);
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Buy&Sell Bulk Upload Instruction'));
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Buy&Sell Bulk Upload Instruction');
			$smarty -> assign('keywords','Bulk Upload Instruction');
			$smarty -> assign('req', $req);
			$content = $smarty -> fetch('about.tpl');
			$smarty -> assign('content', $content);
			$smarty->assign('hid_left_banner',0);
			$smarty->assign('is_content',1);
			$smarty->assign('sidebar',0);
			$smarty -> assign('sidebar_bg', '0');
		break;
		case 'propertiesinstru':
			$req = $socObj -> displayPageFromCMS(79);
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Properties Bulk Upload Instruction'));
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Properties Bulk Upload Instruction');
			$smarty -> assign('keywords','Bulk Upload Instruction');
			$smarty -> assign('req', $req);
			$content = $smarty -> fetch('about.tpl');
			$smarty -> assign('content', $content);
			$smarty->assign('hid_left_banner',0);
			$smarty->assign('is_content',1);
			$smarty->assign('sidebar',0);
			$smarty -> assign('sidebar_bg', '0');
		break;
		case 'reserve_price':
		$req = $socObj -> displayPageFromCMS(90);
		$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Reserve price'));
		$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Reserve price');
		$smarty -> assign('keywords','Reserve price');
		$smarty -> assign('req', $req);

		$content = $smarty -> fetch('about.tpl');
		$smarty -> assign('content', $content);
		$smarty->assign('is_home',1);
		$smarty -> assign('sidebar_bg', '0');
		break;
		/**
		 * end instruction
		 */
		case 'purchase':
			if (empty($_SESSION['LOGIN'])){
				echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
				exit;
			}
			$StoreID = $_SESSION['ShopID'];
			$p = $_REQUEST['pageno']>0?$_REQUEST['pageno']:1;
			if($_SESSION['attribute']==4){$sellhomeurl="/soc.php?cp=buyerhome";}else{$sellhomeurl="/soc.php?cp=sellerhome";}
			$pageTitle = 'Transaction History';//($_SESSION['attribute'] == 0)?'Transaction History':'Purchase History';
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle($pageTitle, 8,'',$sellhomeurl));
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Properties Bulk Upload Instruction');
			$smarty -> assign('keywords','Bulk Upload Instruction');
			$smarty->assign('req',$socObj->getpurchaseHis($StoreID,$p));
			$content = $smarty -> fetch('purchase.tpl');
			$smarty->assign('content',$content);
			$smarty->assign('is_content',1);
			$smarty->assign('sidebar',0);
			
			break;
		case 'watchitemlist':
			if (empty($_SESSION['LOGIN'])){
				echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
				exit;
			}
			$socbid = new socbidClass();
			$curpage = isset($_REQUEST['p'])?$_REQUEST['p']:1;
			if(isset($_REQUEST['act'])&&$_REQUEST['act']=='del'){
				if(isset($_REQUEST['wid'])&&$_REQUEST['wid']){
					if($socbid->delwatchItme($_REQUEST['wid'])){
						$_SESSION['w_msg'] = "Deleted itme successfully.";
					}else{
						$_SESSION['w_msg'] = "Deleted itme unsuccessfully.";
					}
				}
				header('Location: /soc.php?cp=watchitemlist&p='.$curpage);
			}
			if(isset($_SESSION['w_msg'])){
				$smarty->assign('w_msg',$_SESSION['w_msg']);
				unset($_SESSION['w_msg']);
			}
			if($_SESSION['attribute']==4){$sellhomeurl="/soc.php?cp=buyerhome";}else{$sellhomeurl="/soc.php?cp=sellerhome";}
			$smarty -> assign('itemTitle', $socObj->getTextItemTitle('Watch Item List', 8,'',$sellhomeurl));

			$req = $socbid->watchItemList($_SESSION['ShopID'],$curpage);
			$smarty->assign('req',$req);
			$content = $smarty -> fetch('watchitem_list.tpl');
			$smarty->assign('content',$content);
			$smarty->assign('is_content',1);
			$smarty->assign('sidebar',0);
			
			break;

		//case 'race':
		case 'raceleaderboard':
			//header('Location:soc.php?cp=home');
			//exit;
				
			$full = $_REQUEST['full'];
			include_once(SOC_INCLUDE_PATH . '/class.point.php');
	         $objPoint = new Point();

			$req = $objPoint->getRecordLists();
	        $req['full'] = $full;

	        $req['info']['facelike_image'] = SOC_HTTP_HOST.'skin/red/images/race/soc_fb_like_race.jpg';
	        $req['info']['facelike_title'] = 'SOC Race - Food Marketplace';
	        $req['info']['facelike_desc'] = 'Compete in the SOC Race, refer as many people as you can to win big!';
	        $req['info']['like_type'] = 'other';

			$req['cms_promotion'] = $socObj -> displayPageFromCMS(111);
			$req['cms_content_title'] = $socObj -> displayPageFromCMS(112);
			$req['cms_rules'] = $socObj -> displayPageFromCMS(113);
			$req['cms_earn_points'] = $socObj -> displayPageFromCMS(115);
			$smarty->assign('req',$req);

			if ($full) {
				$smarty -> display('race.tpl');
				exit();
			}

			$pageTitle = 'SOC Race';
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - SOC Race');
			$smarty -> assign('keywords','SOC Race');
			$smarty->assign('PBDateFormat',DATAFORMAT_DB);
			$content = $smarty -> fetch('race.tpl');
			$smarty->assign('content',$content);
			$smarty->assign('is_home',1);
			$smarty->assign('sidebar',0);
			$smarty->assign('hideTopTypeMenu',1);
			$smarty->assign('div','middle');
			$smarty->assign('hideLeftMenu',1);
			break;

		case 'facelikerace':

			$full = $_REQUEST['full'];
			include_once(SOC_INCLUDE_PATH . '/class.facelike.php');
	         $objFacelike = new Facelike();

			$req = $objFacelike->getCurRaceRecords();
	        $req['full'] = $full;
	        $req['p'] = $_REQUEST['p'];

	        $req['info']['facelike_image'] = SOC_HTTP_HOST.'skin/red/images/race/soc_fb_like_sprints.jpg';
	        $req['info']['facelike_title'] = 'SOC Facebook Sprints - Food Marketplace';
	        $req['info']['facelike_desc'] = "Gain the most 'likes' on an item you have listed with Food Marketplace to win big prizes!";
	        $req['info']['like_type'] = 'other';

			$req['cms_facelikerace_about'] = $socObj -> displayPageFromCMS(116);
			
			$smarty->assign('req',$req);

			if ($full) {
				$smarty -> display('facelikerace.tpl');
				exit();
			}

			$pageTitle = 'SOC FACEBOOK SPRINTS';
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - SOC FACEBOOK SPRINTS');
			$smarty -> assign('keywords','SOC FACEBOOK SPRINTS');
			$smarty->assign('PBDateFormat',DATAFORMAT_DB);
			$content = $smarty -> fetch('facelikerace.tpl');
			$smarty->assign('content',$content);
			$smarty->assign('is_home',1);
			$smarty->assign('sidebar',0);
			$smarty->assign('hideTopTypeMenu',1);
			$smarty->assign('div','middle');
			$smarty->assign('hideLeftMenu',1);
			break;

		case 'pre_facelikerace':

			include_once(SOC_INCLUDE_PATH . '/class.facelike.php');
	         $objFacelike = new Facelike();

	        $req['cms_pre_winners'] = $socObj -> displayPageFromCMS(114);
			$req['race_info'] = $objFacelike->getCurRaceInfo(1);
			$req['race_info']['description'] = nl2br($req['race_info']['description']);

	        $req['info']['facelike_image'] = SOC_HTTP_HOST.'skin/red/images/logo-main.png';
	        $req['info']['facelike_title'] = 'SOC Facebook Sprints - Previous Winners';
	        $req['info']['facelike_desc'] = addslashes(strip_tags($req['race_info']['description']));
	        $req['info']['like_type'] = 'other';

			$smarty->assign('req',$req);
			$pageTitle = 'SOC FACEBOOK SPRINTS';
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - SOC FACEBOOK SPRINTS');
			$smarty -> assign('keywords','SOC FACEBOOK SPRINTS');
			$smarty->assign('PBDateFormat',DATAFORMAT_DB);
			$content = $smarty -> fetch('pre_facelikerace.tpl');
			$smarty->assign('content',$content);
			$smarty->assign('is_home',1);
			$smarty->assign('sidebar',0);
			$smarty->assign('hideTopTypeMenu',1);
			$smarty->assign('div','middle');
			$smarty->assign('hideLeftMenu',1);
			break;

		//case 'listpoints':
		case 'racescoreboard':
			//ini_set('display_errors', 1);
			//error_reporting(E_ALL);
			
			//header('Location:soc.php?cp=home');
			//exit;

			if (empty($_SESSION['LOGIN'])){
				echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
				exit;
			}
			$StoreID = $_SESSION['ShopID'];


			include_once(SOC_INCLUDE_PATH . '/class.point.php');
	         $objPoint = new Point();

			$req['pointinfo'] = $objPoint->getPointInfo($StoreID);
			//$req['list'] = $objPoint->getRecordLists();

			$p = $_REQUEST['pageno']>0?$_REQUEST['pageno']:1;
			if($_SESSION['attribute']==4){$sellhomeurl="/soc.php?cp=buyerhome";}else{$sellhomeurl="/soc.php?cp=sellerhome";}
			$pageTitle = 'My Soc Race Scoreboard';
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - My Soc Race Scoreboard');
			$smarty -> assign('keywords','My Soc Race Scoreboard');
			$smarty->assign('req',$req);
			$content = $smarty -> fetch('listpoints.tpl');
			$smarty->assign('content',$content);
			$smarty->assign('is_home',1);
			$smarty->assign('sidebar',0);
			$smarty->assign('hideTopTypeMenu',1);
			$smarty->assign('div','middle');
			$smarty->assign('hideLeftMenu',1);
			break;

		case 'sprintboard':
			//ini_set('display_errors', 1);
			//error_reporting(E_ALL);

			if (empty($_SESSION['LOGIN'])){
				echo "<script>alert('Please login first.');location.href='soc.php?cp=home'</script>";
				exit;
			}
			$StoreID = $_SESSION['ShopID'];

			$full = $_REQUEST['full'];
			include_once(SOC_INCLUDE_PATH . '/class.facelike.php');
	        $objFacelike = new Facelike();

			$req = $objFacelike->getCurRaceRecords();
			$req['rank_info'] = $objFacelike->getCurRaceRankInfo($StoreID);
			$req['item_info'] = $objFacelike->getCurRaceItems($StoreID);
	        $req['full'] = $full;
	        $req['p'] = $_REQUEST['p'];
	        //$req['cms_sprintboard'] = $socObj -> displayPageFromCMS(118);
	        
			if ($full) {
				$smarty -> display('facelikerace.tpl');
				exit();
			}

			$pageTitle = 'My Soc Sprint Scoreboard';
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - My Soc Sprint Scoreboard');
			$smarty -> assign('keywords','My Soc Sprint Scoreboard');
			$smarty->assign('req',$req);
			$content = $smarty -> fetch('sprintboard.tpl');
			$smarty->assign('content',$content);
			$smarty->assign('is_home',1);
			$smarty->assign('sidebar',0);
			$smarty->assign('hideTopTypeMenu',1);
			$smarty->assign('div','middle');
			$smarty->assign('hideLeftMenu',1);
			break;

		case 'qpreview':
			//ini_set('display_errors', 1);
			//error_reporting(E_ALL);
			include_once(SOC_INCLUDE_PATH.'/class.login.php');
			$objLogin = new login();
			if (!$objLogin -> checkLogin()) {
				unset($objLogin);
				header('Location: /admin/');
				exit;
			}
			
			$sid = $_GET['sid'];
			$qid = $_GET['qid'];

			include_once(SOC_INCLUDE_PATH . '/class.point.php');
	         $objPoint = new Point();
	         
			$req['site_info'] = $objPoint->getSiteInfo('', $sid);
			$req['question_info'] = $objPoint->getRandQuestion('', $req['site_info']['domain'], $qid);
			$req['preview'] = true;

			$pageTitle = 'SOC Race Bonus Question';
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - SOC Race Bonus Question');
			$smarty -> assign('keywords','SOC Race Bonus Question');
			$smarty->assign('req',$req);
			$smarty->assign('content',$content);
			$smarty->assign('is_home',1);
			$smarty->assign('sidebar',0);
			$smarty->assign('hideTopTypeMenu',1);
			$smarty->assign('div','middle');
			$smarty->assign('hideLeftMenu',1);
			$smarty->assign('footer',  footer());
			$smarty -> display('bp_question.tpl');
			exit();
        break;

        case 'certified' :
                include_once(dirname(__FILE__).'/certified.php');
                break;

        case 'cms' :
                include_once(dirname(__FILE__).'/cms.php');
                break;
		case 'bookonline':
		
			if(isset($_REQUEST['buyer'])&&trim($_REQUEST['buyer'])!=""){
				$query = "SELECT * FROM {$table}bu_detail WHERE StoreID='".base64_decode($_REQUEST['buyer'])."' limit 1";
				$dbcon ->execute_query($query);
				$result = $dbcon->fetch_records(true);
				if($result){
					$_SESSION['NickName'] = Input::StripString($result[0]['bu_nickname']);
					$_SESSION["UserName"] = $result[0]['bu_name'];
					$_SESSION['email']  = $result[0]['bu_email'];
					$_SESSION['LOGIN'] = "login";
				}
			}

			$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$email = $_POST['email'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$phone = $_POST['phone'];
				$quantity = (int)$_POST['quantity'];
				$reservation_date = $_POST['reservation_date'];
				$am = $_POST['am'];
				if (empty($StoreID)) {
					$msg = '-  Please come from the currect url.<br/>';
				}
				if (empty($firstname)) {
					$msg .= '-  First Name is required.<br/>';
				}
				if (empty($lastname)) {
					$msg .= '-  Last Name is required.<br/>';
				}
				if (empty($email)) {
					$msg .= '-  Email is required.<br/>';
				}
				if (empty($phone)) {
					$msg .= '-  Phone is required.<br/>';
				}
				if (empty($quantity)) {
					$msg .= '-  No. People is required.<br/>';
				}
				if (!is_int($quantity) || $quantity < 1) {
					$msg .= '-  No. People must be numberal.<br/>';
				}
				if (empty($reservation_date)) {
					$msg .= '-  Date is required.<br/>';
				}

				$date_info = explode('/', $reservation_date);
				list($hour, $minute) = explode(':', $_POST['start_hour']);
				$reservation_day = $date_info[2].'-'.$date_info[1].'-'.$date_info[0];

				if (empty($msg)){
					$arrSetting['StoreID'] 			= 	$StoreID;
					$arrSetting['booker_id'] 		= 	$_SESSION['StoreID'] ? $_SESSION['StoreID'] : 0;
					$arrSetting['email'] 			= 	$email;
					$arrSetting['firstname'] 		= 	$firstname;
					$arrSetting['lastname'] 		= 	$lastname;
					$arrSetting['phone'] 			= 	$phone;
					$arrSetting['quantity'] 		= 	$quantity;
					$arrSetting['reservation_day'] 	= 	$reservation_day;
					$arrSetting['reservation_hour'] = 	$_POST['start_hour'];
					$arrSetting['reservation_am'] 	= 	$am;
					$arrSetting['comments'] 		= 	$_POST['comments'];
					$arrSetting['book_date'] 		= 	time();

					if ($dbcon-> insert_record($GLOBALS['table']."book", $arrSetting)) {
						$msg = 'Book Successfully.';
						
						
						$query = "SELECT * FROM {$table}bu_detail WHERE StoreID='".$StoreID."' LIMIT 1";
						$dbcon ->execute_query($query);
						$result = $dbcon->fetch_records(true);
						if ($result) {
							
							$message = '
								<html>
								<body>
								Hi <br /><br />
								A online booking for '.$result[0]['bu_name'].' has been made. <br />
								The reservation is for '.$arrSetting['firstname'].' '.$arrSetting['lastname'].'.<br />
								Time: '.$arrSetting['reservation_day'].' '.$arrSetting['reservation_hour'].' '.$arrSetting['reservation_am'].'<br />
								Quantity: '.$arrSetting['quantity'].' <br />
								Phone Number: '.$arrSetting['phone'].' <br />
								Comments: '.$arrSetting['comments'].' <br /><br />
								Thank you. Kind regards, <br />
								FoodMarketplace.
								</body>
								</html>';
								
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
								
							$headers .= 'From: no-reply@'.EMAIL_DOMAIN . "\r\n" .
							'Reply-To: '.$result[0]['bu_email'] . "\r\n" .
							'X-Mailer: PHP/' . phpversion();

							mail($arrSetting['email'].', '.$result[0]['bu_email'], "Online Booking", $message, $headers);
						
						}
					} else {
						$msg = $dbcon -> _errorstr;
					}
				}
			}

			$booker_email = $_SESSION['email'];
			$smarty->assign('booker_email', $booker_email);
			for($i=1;$i<=12;$i++) {
                $arr_hour[]=$i.':00';
                $arr_hour[]=$i.':30';
            }
            $smarty->assign('arr_hour',$arr_hour);
			$smarty -> assign('pageTitle','Sell Goods Online - Selling Online - Book Online');
			if (!$StoreID){
				header('Location:soc.php?cp=home');
				exit;
			}
			$smarty->assign('sidebar', 0);
			
			//echo var_export($req);
			$req = array();
			
			//start shopper header
			$headerInfo = $socObj -> displayStoreWebside(true);
			$req = $headerInfo;
			$req['StoreID'] = $StoreID;
			$req['info']['bu_name'] = getStoreByName($StoreID);
			$req['msg'] = $msg;

			$templateInfo = $socObj -> getTemplateInfo();
			$smarty -> assign('templateInfo', $templateInfo);
			$smarty->assign('itemTitle', $socObj->getTextItemTitle($req['info']['bu_name'].' - Make a Booking', 4, $templateInfo['bgcolor']));
			$smarty -> assign('is_website', 1);
			$smarty->assign('isstorepage',1);

			
			$smarty->assign('headerInfo', $headerInfo['info']);
			$req['template'] = $headerInfo['template'];
			$smarty -> assign('req', $req);

			$tmp_header = $smarty->fetch('template/tmp-header-include.tpl');
			$smarty->assign('tmp_header', $tmp_header);
			//end shopper header
			$search_type = $headerInfo['info']['sellerType'];

			//$smarty -> assign('itemTitle', $socObj->getItemTitle('Email to a Friend','txt',true));
			$content =	$smarty -> fetch('bookonline.tpl');
			//$smarty->display('email_friend.tpl');
			//exit;
			$smarty -> assign('content', $content);
			break;

        case 'fbbundle':

            $socObj ->facebookSaveKeyToDb();

            header('Location:soc.php?cp=sellerhome');
            exit;
            break;

        case 'fbunbundle':

            $socObj ->facebookDeleteKeyFromDb();

            header('Location:soc.php?cp=sellerhome');
            exit;
            break;

	default:

		//$smarty -> assign('itemTitle', $socObj->getItemTitle());
		$StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID']:'');
		$req = $socObj->_displayStoreInfo($template, $StoreID);
		$url_store_name = clean_url_name($req['url_bu_name']);
		if($url_store_name){
			header("Location: $url_store_name");
		}else{
			header("Location: ".RDURL);
		}
		exit;

		$req['info']['bu_email'] = $_SESSION['email'];
		$smarty -> assign('req',	$req);
		$content	=	$smarty -> fetch('template/style.tpl');
		$content	.=	$smarty -> fetch('template/tmp-header-include.tpl');
		$content	.=	$smarty -> fetch('template/'. $req['template']['TemplateName'] . '.tpl');
		$smarty -> assign('content', $content);
		$smarty -> assign('sidebar', 0);

		$tpl_tag = strtolower(substr($req['template']['TemplateName'], -1));

		if ( $tpl_tag == 'a'
		|| $tpl_tag == 'b'
		|| $tpl_tag == 'e'){
			//show old logo
			$_SESSION['logo_old'] = true;
		}elseif ( $tpl_tag == 'c'
		|| $tpl_tag == 'd' ){
			//show new logo
			$_SESSION['logo_new'] = true;
		}
		break;
}


if ( $_SESSION['logo_new'] === true ) {

	$is_logo = 'true';
	$menu_bgcolor = ' bgcolor="#65BFF3"';  //show the new left banner logo, set background color.
	$menu_bottom =  ' bgcolor="#65BFF3"';

}elseif ($_SESSION['logo_old'] === true){

	$menu_bottom = ' class="ltpanel_bot_slogo"';  //show old logo
	$menu_bgcolor = ' background="images/ltpanel_bgrep.jpg"';  //show old logo
	$is_logo = 'true';

}else{
	$menu_bgcolor = ' class="ltpanel_bot"';	//other set
	$menu_bottom = ' class="ltpanel_bot"';	//other set
}

//active the menu of top navigation
$smarty->assign('cp', $_REQUEST["cp"]);

$requesturi = $_SERVER['REQUEST_URI'];
$smarty->assign('requesturi', $requesturi);

if (!empty($_SESSION)) {
	$userData = $_SESSION;
	$storeName = getStoreURLNameById($userData['StoreID']);
	if (!empty($storeName)) {
		$userData['website'] = clean_url_name($storeName);
	}
	$smarty -> assign('session', $userData);
}

//left menu
include_once('leftmenu.php');

$setSearchType	= !empty($_REQUEST['attribute'])? $_REQUEST['attribute'] : $_SESSION['attribute'];

if(isset($_REQUEST['StoreID'])){
	if($setCP!='home'&&getonlieType($StoreID)!="onlinestore"){
		$smarty-> assign('is_content',1);
	}
}else{
	$tmpary = array(0=>'contact',1=>'customers_geton_alerts',2=>'blog',3=>'blogedit',4=>'changepass',5=>'bookads',6=>'inbox',7=>'payreports',8=>'business_get_step_stat',9=>'offerlist',10=>'lookupreview',11=>'reply',12=>'category');
	if(in_array($setCP,$tmpary)){
		if($setCP=='blogedit'||$setCP=='reply'||$setCP=='blog'){
			if($setSearchType!='0'){
				$smarty-> assign('is_content',1);
			}
		}else{
			$smarty-> assign('is_content',1);
		}
	}
}

$smarty -> assign('menu_bgcolor', $menu_bgcolor);
$smarty -> assign('menu_bottom', $menu_bottom);
$smarty -> assign('is_logo', $is_logo);
//end of display logo or not

include_once('soc/seo.php');

unset($socObj);
$smarty -> assign('menu_bgcolor', $menu_bgcolor);
// Modify by Haydn.H By 20120306 ========= Begin =========
// add control to the js special for head
$smarty -> assign('ocp', $setCP);
// Modify by Haydn.H By 20120306 ========= End =========
$smarty -> display($template_tpl);
unset($smarty);
exit;
