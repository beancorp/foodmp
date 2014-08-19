<?php

ob_start();
include_once "./include/session.php" ;
include_once "./include/config.php" ;
include_once "./include/maininc.php" ;  
//include_once("validlogin.php");
//include_once "editor/fckeditor.php" ;

$msg = !empty($_REQUEST['msg'])? $_REQUEST['msg'] : 'Your reply email has been sent.' ; 

?>

<html>
<head>
<title>Email Sent</title>
<LINK href="/css/admin.css" type=text/css rel=stylesheet> 

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
</head>
<body>


  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td></td>
    </tr>
    <tr> 
      <td width="79%" align="center" valign="top"><div align="center"> 
      <p class="txt"><font color="#FF0000">
        <?php echo $msg ; $msg=""?>
    </font></p> </tr>
</table>
<script>
window.opener.location.reload();
</script>
</body>

</html>