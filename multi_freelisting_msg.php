<?php
include_once ('include/config.php');
@session_start();
include_once ('include/smartyconfig.php');
include_once ('maininc.php');

	if(isset($_SESSION['multi_fl_import'])&&!empty($_SESSION['multi_fl_import'])){
		$error_list = array();
		foreach ($_SESSION['multi_fl_import'] as $pass){
			if(isset($pass['msg'])&&!empty($pass['msg'])){
				$error_list[]=$pass;
			}
		}
		$smarty->assign('errlist',$error_list);
		$smarty->assign('error_all',$_SESSION['multi_fl_import']['all']);
		$smarty->display('error_list.tpl');
		unset($_SESSION['multi_fl_import']);
		unset($error_list);
		unset($smarty);
		exit;
	}else{
		echo "<script>
			alert('No error message.');
			//window.opener.location.href = '/soc.php?act=signon&step=4';
			window.close();
		</script>";
	}
?>