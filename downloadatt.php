<?php
ob_start();
@session_start();
include_once ('include/config.php');
include_once ('include/maininc.php');
include_once ('include/functions.php');
include_once ('include/class/download.php');
include_once ('include/smartyconfig.php');
ob_end_clean();
	
	$downkey = isset($_REQUEST['keys'])?$_REQUEST['keys']:'';
	$bid  = isset($_SESSION['ShopID'])?$_SESSION['ShopID']:0;
	$pid  = isset($_REQUEST['pid'])?$_REQUEST['pid']:0;
	if($downkey!=""){
		$downkey = base64_decode($downkey);
		$query = "SELECT pa.*,pd.downkey,pd.isdownload,pd.bid,pd.id as pdid FROM {$table}product_download pd left join {$table}product_attachment pa on pd.pid=pa.pid where pd.downkey='{$downkey}' and pd.isdownload=0 and pd.enable=1 and pd.lastdowndate >=".time();
		$dbcon->execute_query($query);
		$result = $dbcon->fetch_records(true);
		if(is_array($result)){
			ob_start();
			$download=new download('php,html',false);
			$download->setfilename($result[0]['filename']);
			$query = "update {$table}product_download set  isdownload=1 where downkey='{$downkey}' ";
			$dbcon->execute_query($query);
			$query = "insert into {$table}download_log(`pid`,`bid`,`downloadid`,`lastdown`)values('{$result[0]['pid']}','{$result[0]['bid']}','{$result[0]['pdid']}','".time()."')";
			$dbcon->execute_query($query);
			$logid = $dbcon->lastInsertId();
			ob_end_clean();
			if(!$download->downloadfile(ROOT_PATH.$result[0]['fileurl'])){
				$query = "update {$table}product_download set  isdownload=0 where downkey='{$downkey}' ";
				$dbcon->execute_query($query);
				$query = "update {$table}download_log set  isdown=0 where id='$logid' ";
				$dbcon->execute_query($query);
				echo "<script>alert('Faild to download.');window.close();</script>";
			}    	
		}else{
			echo "<script>alert('You don\'t have permission to download.');window.close();</script>";
		}
	}elseif ($bid!=0&&$pid!=0){
		if(isuserbuy($pid,$bid)){
			$query = "SELECT * FROM {$table}product_attachment pa left join {$table}product_download pd ON pd.pid = pa.pid WHERE pd.pid='$pid' and pd.bid='$bid' and pd.isdownload=0 and pd.enable=1 and pd.lastdowndate >=".time();
			$dbcon->execute_query($query);
			$result = $dbcon->fetch_records(true);
			if(is_array($result)){
				ob_start();
				$downkey = $result[0]['downkey'];
				$download=new download('php,html',false);
				$query = "update {$table}product_download set  isdownload=1 where downkey='{$downkey}' ";
				$dbcon->execute_query($query);
				$query = "insert into {$table}download_log(`pid`,`bid`,`downloadid`,`lastdown`)values('{$result[0]['pid']}','{$result[0]['bid']}','{$result[0]['pdid']}','".time()."')";
				$dbcon->execute_query($query);
				$logid = $dbcon->lastInsertId();
				ob_end_clean();
				if(!$download->downloadfile(ROOT_PATH.$result[0]['fileurl'])){
					$query = "update {$table}product_download set  isdownload=0 where downkey='{$downkey}' ";
					$dbcon->execute_query($query);
					$query = "update {$table}download_log set  isdown=0 where id='$logid' ";
					$dbcon->execute_query($query);
					echo "<script>alert('Faild to download.');history.go(-1);</script>";
				}   	
			}else{
				echo "<script>alert('You don\'t have permission to download.');history.go(-1);</script>";
			}
		}else{
			echo "<script>alert('You don\'t have permission to download.');history.go(-1);</script>";
		}
	}else{
		echo "<script>alert('Please login first.');location.href='soc.php?cp=login';</script>";
	}
	exit();
?>