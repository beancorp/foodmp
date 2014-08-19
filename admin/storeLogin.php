<?php
@session_start();
include_once ('../include/smartyconfig.php');
include_once "../include/config.php" ;
include_once "../include/fbconfig.php";
include_once "../include/maininc.php" ;
//include_once "../include/session.php" ;
include_once "../include/functions.php" ;
include_once ("class.login.php");

/*
 *  @Author:Yang Ball   2010-08-04
 *  Bug #6007
 */

$objLogin = new login(); 
 
if (!$objLogin->checkLogin()) {      // Not Login Admin,Exit
    exit('<script type="text/javascript">alert("You don\'t have permission to access this url because you are not an administrator.");location.href="/index.php";</script>');
}

include_once("validlogin.php");

$GLOBALS['table'];
$rows	=	mysql_fetch_object(mysql_query("select * from ".$GLOBALS['table']."bu_detail where StoreID='$_REQUEST[StoreID]'"));
$rows1	=	mysql_fetch_object(mysql_query("select * from ".$GLOBALS['table']."login where StoreID='$_REQUEST[StoreID]'"));

$_SESSION['level']		=	$rows1->level;
$_SESSION['Password']	=	$rows1->password;
$_SESSION['UserID']		=	$rows1->id;
$_SESSION['ShopID']		=	$rows1->StoreID;
$_SESSION['StoreID']	=	$rows1->StoreID;
$_SESSION['email']		=	$rows1->user;

$_SESSION['UserName']	=	$rows->bu_name;
$_SESSION['NickName']	=	Input::StripString($rows->bu_nickname);
$_SESSION['PostCode']	=	$rows->bu_postcode;
//Add attribute and subAttrib by Jessee at 20081223
$_SESSION['attribute']	=	$rows->attribute;
$_SESSION['subAttrib']	=	$rows->subAttrib;
$_SESSION['ispayfee']	=	$rows->ispayfee;

$_SESSION['urlstring']  =   $rows -> bu_urlstring;
$_SESSION['State']		=	getStateByName($rows -> bu_state);
$_SESSION['Suburb']		=	$rows->bu_suburb;
$_SESSION['outerEmail']	=	$rows->outerEmail;

// Modify by Haydn.H By 20120228 ========= Begin =========
//facebock key
$query = "select * from " . $table. "facebook WHERE `StoreID`='$_SESSION[StoreID]' and `attribute`=$_SESSION[attribute]";
$dbcon-> execute_query($query);
$arrTemp = $dbcon->fetch_records();
$_SESSION['fb']['id'] = $arrTemp[0]['fb_id'];
$_SESSION['fb']['can'] = empty($_SESSION['fb']['id']) ? true : false;
// Modify by Haydn.H By 20120228 ========= End =========

if($_SESSION['level'] == "2"){
	header("Location:/soc.php?cp=buyerhome&sessionid=".session_id());
}else{
	$QUERY	=	"SELECT * FROM ".$GLOBALS['table']."template_details WHERE  StoreID  ='$_SESSION[StoreID]'";
	$result		=	$dbcon->execute_query($QUERY) ;
	$grid = $dbcon->fetch_records() ;
	$_SESSION['TemplateID']	=	$grid[0]['TemplateID'];
	$_SESSION['TemplateName']	=	$grid[0]['TemplateName'];
	$_SESSION['LOGIN']	=	"login";
	header("Location:/soc.php?cp=sellerhome&sessionid=".session_id());
}

?>
