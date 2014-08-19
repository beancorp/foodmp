<?php
ini_set("max_execution_time", 3600);
include_once "include/config.php" ;
include_once "include/smartyconfig.php" ;
include_once "include/class.upload.php";

?>
<?php 
if(empty($_REQUEST['op'])){
?>
<html>
<head>
<title>Image Upload</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="css/style.css" type="text/css" rel="stylesheet">
</head>
<body>
<center>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<form method="post" enctype="multipart/form-data" name="fileForm1" id="fileForm1">
          	<tr>
           	  <td align="left" width="50%"><input name="upfiles" type="file" class="inputB" id="upfiles" size="13" style="+width:230px; +font-size:11px"/></td>
				<input name="op" type="hidden" id="op" value="sumbit" />
          	</tr>
          	<tr>
          		<td align="left" style=" font-family:Arial, sans-serif;color:#777;font-size:12px;">
					For perfect fit, the image size is 749 x 216 pixels 
				</td>
          	</tr>
          	<tr>
          		<td width="80" align="left"><input name="Submit" type="image" src="skin/red/images/bu-upload-sm.jpg" class="greenButt" value="upload" /></td>
          	</tr>
			<tr id="alert2" >
           		<td style="font-size:12px; color:#777;" align="left"><?php echo $msg?></td>
           	</tr>
		</form>
	</table>
</center>
</body>
</html>

<?php
}else{
	if(isset($_FILES['upfiles'])&&$_FILES['upfiles']['size']>0){
		if(in_array($_FILES['upfiles']['type'],array('image/pjpeg','image/jpeg','image/gif','image/x-png'))){
			$objUpload		= new uploadFile(749, 216 ,10240 ,14 , $_FILES["upfiles"], "SOCExchange.com.au");
			$objUpload->_maxSize = 1024*8;
			$status = $objUpload->upload();
			$disImageName		=	$objUpload -> newFileFullName;
			$msg	=	$objUpload -> strMessage;
			if($status){
				$imageUpload = "YES";
			}
		}else{
			$msg = "File type is not compatible.";
		}
	}else{
		$msg = "Please choose a file to upload.";
	}
?>
<html>
<head>
	<?php
		if ($imageUpload == "YES") {
	?>
	<script language="javascript">
	try{
		parent.document.getElementById('disimg').src = '/<?php echo $disImageName?>';
		parent.document.getElementById('usertpl_img').value = '<?php echo $disImageName?>';
		
	}catch(ex){
		alert(ex);
	}</script>
	<?php
		}
	?>
	<script language="javascript">
	window.location.href="/invationupload.php";
	</script>
</head>
<body></body>
</html>
<?php
}
?>