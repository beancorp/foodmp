<?php
ini_set("max_execution_time", 3600);
include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/smartyconfig.php";
include_once "include/maininc.php" ;
include_once "include/functions.php";
include_once "include/class/common.php";
include_once "include/class.wishlist.php";
?>
<html>
<head>
<title>Image Upload</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" type="text/css" rel="stylesheet">
<script language="javascript">
	<?php 
	$wishlist = new wishlist();
	if(isset($_POST['cp'])&&$_POST['cp']=='upload'){
		$aryinfo = $wishlist->uploadMusic();
		if($aryinfo['msg']){
	?>
		alert('<?php echo  $aryinfo['msg'];?>');
	<?php 	
		}else{
	?>
		window.parent.document.getElementById('filelist').innerHTML= "<a target=\"_blank\" href=\"/<?php echo $aryinfo['music']?>\" style=\"text-decoration:none\"><span style=\" float:left;margin-left:10px; line-height:24px; height:24px;\"><?php echo $aryinfo['music_name']?></span></a> <a href=\"javascript:deletefile('<?php echo $aryinfo['music']?>','<?php echo $GET['StoreID']?>');\"><img src=\"/skin/red/images/icon-deletes.gif\"/></a>";
		window.parent.document.getElementById('file_music').value = '<?php echo $aryinfo['music']?>';
		window.parent.document.getElementById('music_name').value = '<?php echo $aryinfo['music_name']?>';
		
    <?php 			
		}
	}
	?>
	
</script>
<style type="text/css">
.inputB {
-x-system-font:none;
border:1px solid #CCCCCC;
color:#777777;
font-family:Arial;
font-size:12px;
font-size-adjust:none;
font-stretch:normal;
font-style:normal;
font-variant:normal;
font-weight:normal;
line-height:normal;
padding:5px;
}
</style>
</head>

<body style="margin:0;">
<center>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<form method="post" enctype="multipart/form-data" name="fileForm1" id="fileForm1">
          	<tr>
           	  <td align="left" width="1%">
           	  <input name="music" type="file" class="inputB" id="upfiles" style="width:208px;"/></td>
           	  <td align="left" style="padding-left:5px;"><input name="Submit" type="image" src="skin/red/images/bu-upload-sm.jpg" class="greenButt" value="upload" /></td>
           	  <input type="hidden" name="cp" value="upload"/>
          	</tr>
		</form>
	</table>
</center>
</body>
</html>
