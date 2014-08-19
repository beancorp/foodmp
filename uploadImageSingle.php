<?php
ini_set("max_execution_time", 3600);
@session_start();
include_once ('include/smartyconfig.php');
include_once ("class.upload.php");
include_once ("class.uploadImages.php");
include_once ('maininc.php');
include_once ('functions.php');

$objUpImg	=	new uploadImages();
switch($_REQUEST["cp"]){
	
	case 'delete':
		
		echo $objUpImg	-> deleteImage();
		
		unset($smarty, $objUpImg);
		exit;
		break;
		
	case 'save':
		$req	=	$objUpImg	-> upload();
		$msg = $req['msg'];
		if($req['msg']==''){
			$req['display']	=	'save';
		}else{
			$req = $objUpImg -> uploadInterface();
			$req['msg'] = $msg;
		}
		$smarty -> assign('req', $req);
		$smarty->assign('pageTitle','Upload Images');
		$content	=	$smarty -> fetch('upload_file.tpl');
		
		break;
	case 'attachment':
		if(isset($_REQUEST['opt'])&&$_REQUEST['opt']=='save'){
			if($_FILES['upfiles']['size']>0){
				$ext = substr($_FILES['upfiles']['name'],strrpos($_FILES['upfiles']['name'],"."));
				$filetype_ary =array('.mp3','.mov','.avi','.jpg','.png','.gif','.pdf','.exe','.zip','.rar','.wma','.doc','.xls','.txt','.ppt','.swf','.fla');
				if(in_array($ext,$filetype_ary)){
				for(;;){
					if(!file_exists(ROOT_PATH."upload/temp/".time().randStr(6).$ext)){
						$fileurl = "/upload/temp/";
						$filenewname = time().randStr(6).$ext;
						$dsfile =  ROOT_PATH.$fileurl.$filenewname;
					}
					break;
				}
				if(move_uploaded_file($_FILES['upfiles']['tmp_name'],$dsfile)){
					$req['filelinks']	=	$_FILES['upfiles']['name'] . "&nbsp;|&nbsp;<a href=\"javascript:deletefile(\'".base64_encode($fileurl.$filenewname)."\');void(0);\"><img align=\"absmiddle\" title=\"Delete\" alt=\"Delete\" src=\"/skin/red/images/icon-deletes.gif\"/></a>";
					$req['filename'] = $_FILES['upfiles']['name'];
					$req['fileurl'] = $fileurl.$filenewname;
					$req['filenewname'] = $filenewname;
					$req['display']	=	'save';
				}else{
					$req['msg'] = "Upload file error.";
				}
				}else{
					$req['msg'] = "The file type is not supported. Please try again.";
				}
			}else{
				$req['msg'] = "please select a file to upload.";
			}
		}elseif ($_REQUEST['opt']&&$_REQUEST['opt']=="del"){
			if(@unlink(ROOT_PATH.base64_decode($_REQUEST['url']))){
				echo 1;
			}else{
				echo 0;
			}
			exit();
		}
		$smarty -> assign('req', $req);
		$smarty->assign('pageTitle','Upload File');
		$content	=	$smarty -> fetch('upload_file_att.tpl');
		break;
	default:
		$req	=	$objUpImg -> uploadInterface();
		$smarty -> assign('req', $req);
		$smarty->assign('pageTitle','Upload Images');
		$content	=	$smarty -> fetch('upload_file.tpl');
		break;
}

$smarty -> assign('content', $content);
$smarty -> display('index_blank.tpl');
unset($smarty,$objUpImg);

exit;
?>