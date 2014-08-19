<?php
@session_start();
include_once('../include/smartyconfig.php');
include_once('class.login.php');
include_once('maininc.php');
include_once('functions.php');
include_once('class.soc.php');

$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

if (isset($_POST['content_page']) && isset($_POST['text_id'])) {
	$content_page = htmlentities($_POST['content_page']);
	$text_sql = "UPDATE promo_email_template SET content = \"".$content_page."\" WHERE id = '".$_POST['text_id']."' LIMIT 1";
	$dbcon->execute_query($text_sql);
	
	
	//template_path
	$email_template_path = getcwd(). "/../skin/email_template/template_mail_".$_POST['text_id'].".txt";
	//put in file
	file_put_contents($email_template_path, $_POST['content_page']);
	
	echo json_encode(array('message' => 'The content has been updated.'));
	exit;
}

if (isset($_POST['text_id'])) {
	
	$text_sql = "SELECT * FROM promo_email_template WHERE id = '".$_POST['text_id']."' LIMIT 1";
	$text_result = $dbcon->getOne($text_sql);
	
	//$text_result['text_content']
	
	echo json_encode(array('content' => html_entity_decode($text_result['content']), "variables" => $text_result["variables"]));
	exit;
}

$sql = "SELECT id, name, key_name FROM promo_email_template ORDER BY name ASC";

$dbcon->execute_query($sql);
$result = $dbcon->fetch_records(true);
	
	$select_options = '';
	if (is_array($result)) {
		foreach($result as $text) {
			$select_options .= '<option value="'.$text['id'].'">'.$text['name'].'</option>';
		}
	}
	
	
	
$smarty->assign('text_result', $result);	
$smarty->assign('select_options', $select_options);
$content = $smarty->fetch('admin_fanfrenzy_email_template.tpl');
$smarty->assign('content', $content);
$smarty->display('index.tpl');
unset($smarty);
exit;
?>