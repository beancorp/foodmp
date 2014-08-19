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
if (isset($_POST['submit']) || ($use == 'fb_login')) {
	if (((!empty($_POST['username'])) && (!empty($_POST['password']))) || ($use == 'fb_login')) {
		if ($use == 'fb_login') {
			$_user = $facebook->getUser();
			if ($_user) {
				$query = "SELECT t2.* FROM " . $table. "facebook t1, ".$table."login t2 WHERE t1.StoreID = t2.StoreID and t1.fb_id = '$_user'";
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