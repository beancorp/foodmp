<?php
//ob_start();

@session_start();
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php" ;

if($_REQUEST['uname']!='' AND $_REQUEST['password'] != '' AND strlen($_REQUEST['user_type'])>0){
	unset($_SESSION['hascount']);		//google star. control.
	
	$_REQUEST['uname'] = mysql_escape_string($_REQUEST['uname']);
	$_REQUEST['password'] = mysql_escape_string($_REQUEST['password']);
	$_REQUEST['user_type'] = mysql_escape_string($_REQUEST['user_type']);
	$QUERY	=	"SELECT * FROM ".$table."login WHERE ((attribute<>5 AND user='$_REQUEST[uname]') OR (attribute=5 AND username='$_REQUEST[uname]')) AND password ='$_REQUEST[password]' and attribute='$_REQUEST[user_type]'";
	$result		=	$dbcon->execute_query($QUERY) ;

	$grid = $dbcon->fetch_records() ;//exit;

	if(!empty($grid)){
		$userName	=	$_REQUEST['uname'];
		$Password	=	$_REQUEST['password'];
		$_SESSION['LOGIN']	=	"login";
		$date	=	time();
		$_SESSION['level']		=	$grid[0]['level'];
		$_SESSION['Password']	=	$grid[0]['password'];
		$_SESSION['UserID']		=	$grid[0]['id'];
		//purview, you don't modify it in public
		$_SESSION['ShopID']		=	$grid[0]['StoreID'];
		$_SESSION['StoreID']	=	$grid[0]['StoreID'];
		$_SESSION['email']		=	$grid[0]['user'];
		
		$QUERY	=	"SELECT * FROM ".$table."bu_detail WHERE  StoreID  ='$_SESSION[StoreID]'";
		$result		=	$dbcon->execute_query($QUERY) ;
		$grid1=$grid = $dbcon->fetch_records() ;
		//Add attribute and subAttrib by ping.hu at 20081204
		$_SESSION['attribute']	=	$grid[0]['attribute'];
		$_SESSION['subAttrib']	=	$grid[0]['subAttrib'];
		
		//Add payInfo by kevin.liu at 20120105
		$_SESSION['ispayfee']	= $grid[0]['ispayfee'];
		
		//Add outerEmail by ping.hu at 20090106
		$_SESSION['outerEmail']	=	$grid[0]['outerEmail'];
		
		$_SESSION['UserName']	=	$grid[0]['bu_name'];
		$_SESSION['urlstring']  =   $grid[0]['bu_urlstring'];
		$_SESSION['PostCode']	=	$grid[0]['bu_postcode'];
		$_SESSION['State']		=	getStateByName($grid[0]['bu_state']);
		$_SESSION['NickName']	=	Input::StripString($grid[0]['bu_nickname']);
		// Add suburb by ping.hu at 20080115
		$_SESSION['Suburb']		=	$grid[0]['bu_suburb'];
		// add Country ID by Jessee 20081203
		$_SESSION['Country']	=	$grid[0]['bu_country'];
		$_SESSION['TemplateID']	=	'';
		$_SESSION['TemplateName']	= '';
		$QUERY1	=	"UPDATE ".$table."bu_detail set lastLogin ='$date' WHERE  StoreID  ='$_SESSION[StoreID]'";
		$result		=	$dbcon->execute_query($QUERY1) ;

                // Modify by Haydn.H By 20120309 ========= Begin =========
                //facebock key
                $query = "select * from " . $table. "facebook WHERE `StoreID`='$_SESSION[StoreID]' and `attribute`=$_SESSION[attribute]";
                $dbcon-> execute_query($query);
                $arrTemp = $dbcon->fetch_records();
                $_SESSION['fb']['id'] = $arrTemp[0]['fb_id'];
                $_SESSION['fb']['can'] = empty($_SESSION['fb']['id']) ? true : false;
                // Modify by Haydn.H By 20120309 ========= End =========

		if (isset($_POST['return'])) {
			header('Location:'.$_POST['return']);
			exit();
		}
		
		if($_SESSION['level'] == "2"){
			//header("Location: soc.php?cp=home");
			header("Location: soc.php?cp=buyerhome");
		}else{
			$QUERY	=	"SELECT * FROM ".$table."template_details WHERE  StoreID  ='$_SESSION[StoreID]'";
			$result		=	$dbcon->execute_query($QUERY) ;
			$grid = $dbcon->fetch_records();
			$_SESSION['TemplateID']	=	$grid[0]['TemplateID'];
			$_SESSION['TemplateName']	=	$grid[0]['TemplateName'];
			/*if ($_SESSION['attribute'] == 3 && $_SESSION['subAttrib'] == 3 ) {
				header("Location: " . SOC_HTTPS_HOST . 'soc.php?act=signon&step=4');
			}else{
				header("Location: " . SOC_HTTPS_HOST . 'soc.php?act=signon&step=2');
			}*/
			if ($_REQUEST['regsuc']) {
				if ($_SESSION['attribute'] == 3 && $_SESSION['subAttrib'] == 3 ) {
					header("Location: " . SOC_HTTPS_HOST . 'soc.php?act=signon&step=4');
				}else{
					header("Location: " . SOC_HTTPS_HOST . 'soc.php?act=signon&step=2');
				}
			} else {
				header("Location: " . SOC_HTTPS_HOST.'soc.php?cp=sellerhome'.($_REQUEST['regsuc'] ? '&regsuc='.$_REQUEST['regsuc'] : ''));
			}
			/*if ($_SESSION['attribute'] == 0) {
				header("Location: " . SOC_HTTPS_HOST . 'soc.php?act=signon&step=frs');
			} else {
				header("Location: " . SOC_HTTPS_HOST.'soc.php?cp=sellerhome'.($_REQUEST['regsuc'] ? '&regsuc='.$_REQUEST['regsuc'] : ''));
			}*/
			exit();
		}
	}else{
		header('Location: '.RDURL);
		exit();
	}
}else{
	header('Location: '.RDURL);
	exit();
}
?>
