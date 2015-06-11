<?php
//ob_start();
//@session_start();
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once 'include/fbconfig.php';
include_once "include/maininc.php" ;
include_once "include/functions.php";
$success = false;
$from = $_REQUEST['from'];
$reurl = $_REQUEST['reurl'];
$search_type = $_REQUEST['search_type'];
$use = (isset($_REQUEST['use']) ? $_REQUEST['use'] : '');

function checkLoginByFacebook($userDetail){

    $emaildomain = substr(SOC_HTTP_HOST,strpos(SOC_HTTP_HOST,':')+3,-1);
    $email_verification_code = sha1(uniqid(mt_rand(), true));
    $dbcon = $GLOBALS['dbcon'];
    $referral_enabled = false;
    $now = time();
    $renewal_date = strtotime('+5 year', date());
    $insert_store_query = "INSERT INTO aus_soc_bu_detail SET bu_abn = '', contact_name = '".$userDetail['name']."',
					bu_name = '".$userDetail['first_name'].$userDetail['last_name']."',
					product_feetype = 'product',
					bu_nickname = '".$userDetail['first_name'].$userDetail['last_name']."', bu_college = '',
					PayDate = '".$now."', renewalDate = '".$renewal_date."', launch_date = '".$now."', attribute = 4, subAttrib = 0, ispayfee = 0, sold_status = 0,
					bu_colleges_ACN = '', bu_address = '',
					bu_country = 13,
					CustomerType = 'buyer', bu_email = '".$userDetail['email']."', bu_username = '".$userDetail['email']."', referrer = '".(($referral_enabled) ? $_POST['referrer'] : '')."', ref_name = '".getrefname()."'";
    if ($dbcon->execute_query($insert_store_query)) {

        $storeID = $dbcon->lastInsertId();


        // Sign up with Facebook - save the facebook ID.
        if (!empty($userDetail['id'])) {
            $insert_fb_id_sql = "INSERT INTO aus_soc_facebook SET `fb_id` = '".$userDetail['id']."', `storeId` = '$storeID', attribute='4';";
            $dbcon->execute_query($insert_fb_id_sql) or die($insert_fb_id_sql);
            $facebookId =  $dbcon->lastInsertId();
        }

        if ($referral_enabled) {
            if (!empty($_POST['referrer'])) {
                require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
                $referrer = new Referrer();
                $referrer->addReferrerRecord('reg', $storeID);
            }
        }

        $insert_login_query = "INSERT INTO aus_soc_login SET StoreID = '".$storeID."', user = '".$userDetail['email']."', password = '".$email_verification_code."', level = 2, attribute = 4, suspend = 0, email_verification = '', status = 1";

        if ($dbcon->execute_query($insert_login_query)) {

            $success = true;
            $date = time();
            $_SESSION['LOGIN'] = "login";
            $_SESSION['level'] = 2;
            $_SESSION['Password'] =	$email_verification_code;
            $_SESSION['UserID']	= $facebookId;
            $_SESSION['ShopID']	=$storeID;
            $_SESSION['StoreID'] = $storeID;
            $_SESSION['email']	= $userDetail['email'];

            //=======================================================================//
            $detail_query = "SELECT detail.*, country.country_name FROM aus_soc_detail detail left join aus_soc_country country on detail.bu_country=country.country_id WHERE StoreID = '$storeID'";
            $detail_result = mysql_query($detail_query);
            if (mysql_num_rows($detail_result) == 1) {
                $detail_row = mysql_fetch_assoc($detail_result);
                $_SESSION['attribute']	=	$detail_row['attribute'];
                $_SESSION['subAttrib']	=	$detail_row['subAttrib'];
                $_SESSION['ispayfee']	= 	$detail_row['ispayfee'];
                $_SESSION['outerEmail']	=	$detail_row['outerEmail'];
                $_SESSION['UserName']	=	$detail_row['bu_name'];
                $_SESSION['urlstring']  =   $detail_row['bu_urlstring'];
                $_SESSION['PostCode']	=	$detail_row['bu_postcode'];
                $_SESSION['State']		=	getStateByName($detail_row['bu_state']);
                $_SESSION['NickName']	=	Input::StripString($detail_row['bu_nickname']);
                $_SESSION['Suburb']		=	$detail_row['bu_suburb'];
                $_SESSION['Country']    =    $detail_row['bu_country'];
                $_SESSION['CountryName']	=	$detail_row['country_name'];
                $_SESSION['TemplateID']	=	'';
                $_SESSION['TemplateName']	= '';
                $remote_address = $_SERVER['REMOTE_ADDR'];
                $forwarded_for = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $access_query = "INSERT INTO aus_soc_access_log SET user_id = '".$_SESSION['UserID']."', store_id = '".$storeID."', remote_addr = '".$remote_address."', forwarded_for = '".$forwarded_for."'";
                mysql_query($access_query);

                $query_update = "UPDATE aus_soc_bu_detail SET lastLogin = '$date' WHERE StoreID = '$storeID'";
                mysql_query($query_update);
            }
        }
    }

    $reurl = (!empty($reurl)) ? $reurl : SOC_HTTPS_HOST."soc.php?cp=buyerhome";
    header("Location: ".$reurl);
}

if (isset($_POST['submit']) || ($use == 'fb_login')) {
	if (((!empty($_POST['username'])) && (!empty($_POST['password']))) || ($use == 'fb_login')) {
		if ($use == 'fb_login') {
			$_user = $facebook->getUser();
			if ($_user) {
				$query = "SELECT t2.* FROM " . $table. "facebook t1, ".$table."login t2 WHERE t1.StoreID = t2.StoreID and t1.fb_id = '$_user'";
                $login_result = mysql_query($query);

                if (mysql_num_rows($login_result) <= 0)
                {
                    checkLoginByFacebook($facebook->getUserDetailsFromAccessToken());
                }
			}
		} else {
			$username = mysql_escape_string($_POST['username']);
			$password = mysql_escape_string($_POST['password']);
			$query = "SELECT * FROM ".$table."login WHERE (username = '$username') OR (user = '$username')";
		}
		if (!empty($query)) {
			$login_result = mysql_query($query);
			if (mysql_num_rows($login_result) > 0)
			{
				while ($row = mysql_fetch_assoc($login_result))
				{
					if(crypt($password,$row['password'])==$row['password'] || isset($_user)) 
					{
						$success = true;		
						$date = time();
						$_SESSION['LOGIN'] = "login";
						$_SESSION['level'] = $row['level'];
						$_SESSION['Password'] =	$row['password'];
						$_SESSION['UserID']	= $row['id'];
						$_SESSION['ShopID']	= $row['StoreID'];
						$_SESSION['StoreID'] = $row['StoreID'];
						$_SESSION['email']	= $row['user'];
						
						$detail_query = "SELECT detail.*, country.country_name FROM ".$table."bu_detail detail left join ".$table."country country on detail.bu_country=country.country_id WHERE StoreID = '$_SESSION[StoreID]'";
						$detail_result = mysql_query($detail_query);
						if (mysql_num_rows($detail_result) == 1) {
							
							$detail_row = mysql_fetch_assoc($detail_result);   
							$_SESSION['attribute']	=	$detail_row['attribute'];
							$_SESSION['subAttrib']	=	$detail_row['subAttrib'];
							$_SESSION['ispayfee']	= 	$detail_row['ispayfee'];
							$_SESSION['outerEmail']	=	$detail_row['outerEmail'];
							$_SESSION['UserName']	=	$detail_row['bu_name'];
							$_SESSION['urlstring']  =   $detail_row['bu_urlstring'];
							$_SESSION['PostCode']	=	$detail_row['bu_postcode'];
							$_SESSION['State']		=	getStateByName($detail_row['bu_state']);
							$_SESSION['NickName']	=	Input::StripString($detail_row['bu_nickname']);
							$_SESSION['Suburb']		=	$detail_row['bu_suburb'];
                            $_SESSION['Country']    =    $detail_row['bu_country'];
							$_SESSION['CountryName']	=	$detail_row['country_name'];
							$_SESSION['TemplateID']	=	'';
							$_SESSION['TemplateName']	= '';
							
							//if ($detail_row['CustomerType'] == 'listing') {
								// $_SESSION['ListingID'] = $row['StoreID'];
							//}
							
							
							$remote_address = $_SERVER['REMOTE_ADDR'];
							$forwarded_for = $_SERVER['HTTP_X_FORWARDED_FOR'];
							$access_query = "INSERT INTO aus_soc_access_log SET user_id = '".$_SESSION['UserID']."', store_id = '".$_SESSION['StoreID']."', remote_addr = '".$remote_address."', forwarded_for = '".$forwarded_for."'";
							mysql_query($access_query);
							
							$query_update = "UPDATE ".$table."bu_detail SET lastLogin = '$date' WHERE StoreID = '$_SESSION[StoreID]'";
							mysql_query($query_update);
						}
						
						if ($_SESSION['level'] == "3") {
						
							header('Location: soc.php?cp=listinghome');
							
						} else {
							if($_SESSION['level'] == "2") {
								if ($use == 'bp_login') {
									$msg = "The Ultimate SOC Race is open to those who list items on 'SOC exchange'. It's FREE to list, so join the race for the cash today!";
									header("Location:showmessage.php?msg=".urlencode($msg));
									exit;
								}	
								$reurl = (!empty($reurl)) ? $reurl : SOC_HTTPS_HOST."soc.php?cp=buyerhome";
								header("Location: ".$reurl);
							} else {
								$template_query = "SELECT * FROM ".$table."template_details WHERE StoreID = '$_SESSION[StoreID]'";
								$template_result = mysql_query($template_query);
								
								if (mysql_num_rows($template_result) == 1) {
									$template_result = mysql_fetch_assoc($template_result);
									$_SESSION['TemplateID']	= $template_result['TemplateID'];
									$_SESSION['TemplateName'] = $template_result['TemplateName'];
									
									$reurl = (!empty($reurl)) ? $reurl : SOC_HTTPS_HOST."soc.php?cp=sellerhome";
									if ($use == 'bp_login') {
										if ($_SESSION['attribute'] == 3 && $_SESSION['subAttrib'] == 3) {
											$msg = "The Ultimate SOC Race is open to those who list items on 'SOC exchange'. It's FREE to list, so join the race for the cash today!";
											header("Location:showmessage.php?msg=".urlencode($msg));
											exit;
										}
										$reurl = 'bp_question.php';
									}
									header("Location: ".$reurl);
								} else {
									$reurl = (!empty($reurl)) ? $reurl : SOC_HTTPS_HOST."soc.php?cp=sellerhome";
									header("Location: ".$reurl);
								}
							}
						}
					}
				}
			}
		}
	}
}
if (!$success) {
	header("Location: soc.php?cp=login".((!empty($search_type)) ? '&search_type='.$search_type : '')."&error=1".((!empty($reurl)) ? '&reurl='.$reurl : ''));
}
?>