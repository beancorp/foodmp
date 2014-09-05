<?php
/**
 * Mon Oct 9 13:33:03 GMT+08:00 2008 13:33:03
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * login function and class
 * ------------------------------------------------------------
 * \include\class.login.php
 */

class login extends common  {
	var $dbcon 	= 	null;
	var $table	=	'';
	var $smarty = 	null;
	var $lang	=	null;

	/**
	 * @return void 
	 */
	function __construct()
	{
		$this -> dbcon  = &$GLOBALS['dbcon'];
		$this -> table	= &$GLOBALS['table'];
		$this -> smarty = &$GLOBALS['smarty'];
		$this -> lang	= &$GLOBALS['_LANG'];
	}

	/**
    * @return void 
    */
	function __destruct(){
		unset($this->dbcon,$this -> table,$this->smarty,$this -> lang);
	}

	/**
	 * check login of admin
	 *
	 * @return boolean
	 */
	function checkLogin(){
		$booleanResult = false;
		
		//return (isset($_COOKIE['socaduser']));
		
		//echo var_export($_SESSION['u']);
		
		//$user = $_SESSION['u'];
		//$pass = $_SESSION['p'];
		if (isset($_COOKIE['socaduser']) && isset($_COOKIE['socadpass'])) {
			$user = $_COOKIE['socaduser'];
			$pass = $_COOKIE['socadpass'];
		}	
		
		if (isset($user) && isset($pass)) {	
			$_query = "select level from ".$this->table . "login where user = '$user' and password = '$pass' " ;
			//echo $_query;
			$this -> dbcon -> execute_query($_query) ;
			$rows = $this -> dbcon ->count_records() ;
			$grid = $this -> dbcon ->fetch_records() ;
			
			$_SESSION['l'] = $grid[0]['l'];

			if ($rows > 0 and $_SESSION['l'] == 0) {
				$booleanResult	=	true;

			}
		}
		
		//return ($user && $pass);

		return $booleanResult;
	}

	function userLoginPage(){
		$arrResult = null;
		if (isset($_COOKIE['socaduser'])) {
			$arrResult['username'] = $_COOKIE['socaduser'];
		}
		$arrResult['pageTitle'] = $this->lang['pagetitle']['login'];

		return $arrResult;
	}

	function userLoginOperation($user,$pass){
		$booleanResult	= false;

		$_query = "select id,level from ".$this->table . "login where user = '$user' and password = '$pass' " ;

		$this -> dbcon -> execute_query($_query);
		$rows = $this -> dbcon ->count_records();
		$grid = $this -> dbcon ->fetch_records();
		$_SESSION['l'] = $grid[0]['level'];

		if ($rows > 0 and $_SESSION['l'] == 0) {
			$exDate		=	$this->dateAdd('m',1,time());
			$booleanResult	=	true;
			$_SESSION['u']	=	$user;
			$_SESSION['p']	=	$pass;
            $_SESSION['uid']=    $grid[0]['id'];
			$_SESSION['isAdmin']=	true;

			setcookie('socaduser', $user);
			setcookie('socadpass', $pass);
			setcookie('socadlevel', $_SESSION['l']);
		}
		return $booleanResult;
	}

	function logout(){
		unset($_SESSION['u']);
		unset($_SESSION['p']);
		unset($_SESSION['l']);
		unset($_SESSION['msglogin']);
		unset($_SESSION['isAdmin']);
		setcookie('socaduser', '');
		setcookie('socadpass', '');
	}
}



/*********************
* xajax function
**********************/
function userLogin($objForm){
	$dbcon 			= &$GLOBALS['dbcon'];
	$table_prefix 	= &$GLOBALS['table'];
	$lang 			= &$GLOBALS['_LANG'];
	$objLogin 		= &$GLOBALS['objLogin'];
	$messages		=	'';
	$objResponse = new xajaxResponse();

	//	$messages = $objLogin-> userLoginOperation($objForm['user'],$objForm['pass']);

	$objResponse -> assign('submitButton','disabled',"disabled");
	if (empty($objForm['user'])) {
		$messages = $lang['login']['nousername'];
	}elseif (empty($objForm['pass'])){
		$messages = $lang['login']['nopassword'];
	}elseif (empty($objForm['vdcode'])){
		$messages = $lang['login']['vdcode'];
	}else {
		if(strtolower($objForm['vdcode'])!=strtolower($_SESSION['authnum'])){
			$messages = $lang['login']['vdcode'];
		}else{
			$messages = $lang['login']['logining'];
			if($objLogin-> userLoginOperation($objForm['user'],$objForm['pass'])){
				$objResponse -> script('location.href=\'./?act=main\'');
			}else {
				$messages = $lang['login']['faild'];
			}
		}
	}
	$objResponse -> clear('submitButton','disabled');

	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	return $objResponse;
}
?>