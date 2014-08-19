<?php
if (isset($_GET['sessionid']) and $_GET['sessionid']!=''){
	SetCookie('PHPSESSID', $_GET['sessionid'], 0, '/', ''); 
}
?>