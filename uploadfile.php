<?php
ini_set("max_execution_time", 3600);
//include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/smartyconfig.php" ;
include_once "include/class.upload.php";

if (empty($_REQUEST["op"]) || $_REQUEST["op"]==2){
	$IDFN	=	$_REQUEST['idfn'];
	$IDHN	=	$_REQUEST['idhn'];
	$IDUN	=	$_REQUEST['idun'];
	$UT		=	$_REQUEST["ut"];
	$msg	=	$_REQUEST["msg"];

}elseif($_REQUEST["op"]==1 || $_REQUEST["op"]==3){
	$IDFN	=	$_REQUEST['idfn'];
	$IDHN	=	$_REQUEST['idhn'];
	$IDUN	=	$_REQUEST['idun'];
	$UT		=	$_REQUEST["ut"];
	
	if ($UT == 5){
		$PW		=	empty($_REQUEST['pw']) ? 410 : $_REQUEST['pw'] ;
		$PH		=	empty($_REQUEST['ph']) ? 300 : $_REQUEST['ph'] ;
	}else{
		$PW		=	empty($_REQUEST['pw']) ? 397 : $_REQUEST['pw'] ;
		$PH		=	empty($_REQUEST['ph']) ? 282 : $_REQUEST['ph'] ;
	}
	if(($_REQUEST['res']=='tmp-n-a')){
		$PW = '497';
		$PH = '195';
	}
	$imageName 		= 	"";
	$imageHeight	=	"";
	$imageUpload	=	'NO';
	
	
	$objUpload		= new uploadFile($PW, $PH ,10240 ,$UT , $_FILES["upfiles"], "SOCExchange.com.au");
	$status = $objUpload->upload();
	
	if($status || $_REQUEST['edit']==1){
		if ($status){
			$imageUpload	=	'YES';
			$disImageName		=	$objUpload -> newFileFullName;
			$saveImageName		=	$objUpload -> newFileFullName;
			$msg	=	$objUpload -> strMessage;
		}
		$message = $objUpload-> strMessage;
		if ($_REQUEST['ut']==5 || $_REQUEST['ut']==9){
			$moreImages = array();
			for($i=1;$i<(($UT==5)?7:4);$i++){
				//echo '<pre>'.$i;
				//var_dump($_FILES['moreFile'.$i]);
				//echo '</pre>';
				$moreImages['more'.$i]['size'] = $_FILES['moreFile'.$i]['size'];
				if ($_FILES['moreFile'.$i]['size']>0){
					$objMore = new uploadFile($PW, $PH ,10240 ,$UT , $_FILES['moreFile'.$i], "SOCExchange.com.au");
					if ($objMore->upload()){
						$moreImages['more'.$i]['display'] = $objMore->newFileFullName;
						$moreImages['more'.$i]['upload'] = 'YES';
					}else{
						$moreImages['more'.$i]['upload'] = 'NO';
						$message = $objMore-> strMessage . "<br>";
					}
					unset($objMore);
				}
				
			}
		}
	}else{
		$msg	=	$objUpload -> strMessage;
		if ($_REQUEST['ut']==5 || $_REQUEST['ut']==9){
			$msg = "Main image is required.";
		}
		header("Location:uploadfile.php?op=".($_REQUEST["op"]-1)."&pw=$PW&ph=$PH&idfn=$IDFN&idhn=$IDHN&idun=$IDUN&ut=$UT&msg=$msg");
	}
	
	unset($objUpload);
	
	
}elseif($_REQUEST["op"]==4) {
	

}elseif($_REQUEST["op"]==5) {


}

?>


<?php
if(empty($_REQUEST["op"])){
?>
<html>
<head>
<title>Image Upload</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
<!--
* 			{padding:0; margin:0;}
html 		{font-size:1.25em;}
html > body {font: 12px arial, sans-serif;color:#777;}
h1 			{font-size:1.3em;}
h2 			{font-size:1.2em;}
h3 			{font-size:1em;}
h4 			{font-size:1em;}
h5 			{font-size:0.9em;}
h6 			{font-size:0.8em;}
table, td, input, textarea, select {
border:1px solid #CCCCCC; line-height:14px; color:#777777; font:11px Verdana; padding:5px;
}
.or_reply 	{ background:url(skin/red/images/buttons/or-reply.gif); width:71px; height:29px; border:none;}
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #777777;
	font-size: 12px;
}
.style4 {font-family: Arial, Helvetica, sans-serif; color: #777777; }
.style5 {color: #777777}
.style6 {font-size: 12px}
-->
</style>
<script language="javascript" type="text/javascript">
function popClickLoad(){
	var msgBox = document.getElementById('msgBox');
	msgBox.innerHTML="Your image is loading. Please wait until the image appears as it may take a few minutes.";
}

</script>
</head>

<body>
<center>
	<table width="96%" border="0" align="center" cellpadding="2"  cellspacing="1" bgcolor="#D4D0C8">
		<form method="post" enctype="multipart/form-data" name="fileForm1" id="fileForm1">
        	<tr bgcolor="#A3C0F8">
            	<td colspan="3" height="31" class="txt"><span class="style1">Upload  Images</span>&nbsp;&nbsp;(Supported image type are jpg, gif, png)</td>
       	  </tr>
          	<tr bgColor="#f5f8fe" >
				<td height="22" align="right"></td>
				<td><!--Max Resolution = 2800 px * 2800px-->
				  <input name="op" type="hidden" id="op" value="1" />
				<input type="hidden" name="edit" value="<?=($_REQUEST['edit']=='')?'0':'1'?>">
				<input type="hidden" name="idfn" value="<?=$IDFN?>">
				<input type="hidden" name="idhn" value="<?=$IDHN?>">
			    <input type="hidden" name="idun" value="<?=$IDUN?>">
			    <input type="hidden" name="ut" value="<?=$UT?>">			    </td>
				<td>&nbsp;</td>
          	</tr>
          	<tr bgColor="#f5f8fe" class="txt" >
            	<td width="100" height="22" align="right" class="text12"><?=($_REQUEST['ut']==5 || $_REQUEST['ut']==9)?'Main':''?>
           	    <span class="style4 style6"> Image* :</span></td>
           	  <td align="center"><input name="upfiles" type="file" class="clsTextField" id="upfiles" size="60"/></td>
            	<td width="115" rowspan="<?php echo ($UT==9)?4:7;?>"><input name="Submit" type="image" src="skin/red/images/buttons/or-upload.gif" class="hbutton" value="upload" onClick="popClickLoad();"/></td>
          	</tr>
          	<?php
          	if ($_REQUEST['ut']==5 || $_REQUEST['ut']==9){
          	?>
          	<tr bgColor="#f5f8fe" class="txt" >
            	<td align="right" class="style4 style6"> Image 2 :</td>
           	  <td align="center"><input name="moreFile1" type="file" class="clsTextField" id="morefiles1" size="60"/></td>
           	</tr>
          	<tr bgColor="#f5f8fe" class="txt" >
            	<td align="right" class="style4 style6"> Image 3 :</td>
           	  <td align="center"><input name="moreFile2" type="file" class="clsTextField" id="morefiles2" size="60"/></td>
           	</tr>          	<tr bgColor="#f5f8fe" class="txt" >
            	<td align="right" class="style4 style6"> Image 4 :</td>
           	  <td align="center"><input name="moreFile3" type="file" class="clsTextField" id="morefiles3" size="60"/></td>
            	</tr>
          	<?php
          	}
          	if ($_REQUEST['ut']==5){
          	?>
          	<tr bgColor="#f5f8fe" class="txt" >
            	<td align="right" class="style4 style6"> Image 5 :</td>
           	  <td align="center"><input name="moreFile4" type="file" class="clsTextField" id="morefiles1" size="60"/></td>
           	</tr>
          	<tr bgColor="#f5f8fe" class="txt" >
            	<td align="right" class="style4 style6"> Image 6 :</td>
           	  <td align="center"><input name="moreFile5" type="file" class="clsTextField" id="morefiles2" size="60"/></td>
           	</tr>          	<tr bgColor="#f5f8fe" class="txt" >
            	<td align="right" class="style4 style6"> Image 7 :</td>
           	  <td align="center"><input name="moreFile6" type="file" class="clsTextField" id="morefiles3" size="60"/></td>
            	</tr>
          	<?php
          	}
          	?>
        	<tr bgColor="#f5f8fe" >
            	<td height="22" colspan="3" align="center">&nbsp;<font id="msgBox"><?=$msg?>
            	</font></td>
           	</tr>
		</form>
	</table>
</center>
</body>
</html>

<?php
} elseif($_REQUEST["op"]==1) {
?>

<html>
<head>
	<?php
		if ($imageUpload == "YES" or $_REQUEST['edit']==1) {
			$file_count = 0;
	?>
	<script language="javascript">

	try{
		<?php
		if ($imageUpload == 'YES'){
			$file_count +=1;
		?>
		window.opener.document.getElementById('<?=$IDFN?>').src = '<?=$disImageName?>';
		window.opener.document.getElementById('<?=$IDHN?>').value = '<?=$saveImageName?>';
		window.opener.document.getElementById('<?=$IDUN?>').value = '<?=$imageUpload?>';
		<?php
		}
		if ($_REQUEST['ut']==5 || $_REQUEST['ut']==9){
			for($i=1;$i<(($UT==5)?7:4);$i++){
				if ($moreImages['more'.$i]['size']>0){
					if($moreImages['more'.$i]['upload']=='YES'){
					$file_count+=1;
		?>
		window.opener.document.getElementById('moreIMG<?=$i?>').src = '<?=$moreImages['more'.$i]['display']?>';
		window.opener.document.getElementById('moreImage<?=$i?>').value = '<?=$moreImages['more'.$i]['display']?>';
		window.opener.document.getElementById('mImg<?=$i?>').value = '<?=$moreImages['more'.$i]['upload']?>';
		<?php
					}
				}
			}
		}
		if ($file_count!=0){
		$msg = 'close';
		?>
		window.close();
		<?php
		}
		?>
	}catch(ex){
		//alert(ex);
	}
	</script>
	
	<?php
		}
	//if ($file_count==0){
		//$msg = "Please select at least one image to upload.";
		//$msg	=	$objUpload -> strMessage;
		$msg 	=	$message;
	//}
	?>
<link href="admin/css/style.css" type="text/css" rel="stylesheet">
</head>

<body>
<center>
	<table width="100%" border="0" align="center" cellpadding="2"  cellspacing="1" bgcolor="#D4D0C8">
		<form method="post" enctype="multipart/form-data" name="fileForm1" id="fileForm1">
        	<tr bgcolor="#A3C0F8">
            	<td background="images/heading_bg.gif" colspan="3" height="31" class="txt"><strong>Upload  Image </strong>&nbsp;&nbsp;(Supported image type are jpg, gif, png)</td>
        	</tr>
        	<tr bgColor="#f5f8fe" >
            	<td width="86" height="22">&nbsp;</td>
            	<td>&nbsp;</td>
            	<td width="133">&nbsp;</td>
          	</tr>
          	<tr bgColor="#f5f8fe" class="txt" >
            	<td width="86" height="22" align="right" class="text12"> prompt :</td>
           	  <td align="center"><font id="msgBox"><?=$msg?></font></td>
            	<td width="133"><input name="Submit" type="submit" class="hbutton" value="back" /></td>
          	</tr>
          	<tr bgColor="#f5f8fe" >
				<td height="22" align="right"></td>
				<td><input name="op" type="hidden" id="op" value="0" />
				<input type="hidden" name="IDFN" value="<?=$IDFN?>">
				<input type="hidden" name="IDHN" value="<?=$IDHN?>">
			    <input type="hidden" name="IDUN" value="<?=$IDUN?>"></td>
				<td>&nbsp;</td>
          	</tr>
		</form>
	</table>
</center>
</body>
</html>
<?php 
}elseif($_REQUEST["op"] == 2){
?>
<html>
<head>
<title>Image Upload</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/style.css" type="text/css" rel="stylesheet">
<script language="javascript" type="text/javascript">
function popClickLoad(){
	var msgBox = document.getElementById('msgBox');
	msgBox.innerHTML="Your image is loading. Please wait until the image appears as it may take a few minutes.";
}

</script>
</head>

<body>
<center>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<form method="post" enctype="multipart/form-data" name="fileForm1" id="fileForm1">
          	<tr>
           	  <td align="left" width="50%"><input name="upfiles" type="file" class="inputB" id="upfiles" size="13" style="+width:230px; +font-size:11px"/></td>
				<input name="op" type="hidden" id="op" value="3" />
				<input type="hidden" name="idfn" value="<?=$IDFN?>">
				<input type="hidden" name="idhn" value="<?=$IDHN?>">
			    <input type="hidden" name="idun" value="<?=$IDUN?>">
			    <input type="hidden" name="res" value="<?=$_REQUEST['res']?>">
			    <input type="hidden" name="ut" value="<?=$UT?>">
          	</tr>
          	<tr>
          		<td align="left" style=" font-family:Arial, sans-serif;color:#777;font-size:12px;">
			  <?php
			  	if ($_REQUEST['res'] != 'tmp-n-e'){
					$str = "For perfect fit, the image size is ";
					$str .= ($_REQUEST['res']=='tmp-n-a')?'497 x 195':'243 x 210';
					$str .= " pixels";
					echo $str;
				}
			  ?>
				</td>
          	</tr>
          	<tr>
          		<td width="80" align="left"><input name="Submit" type="image" src="skin/red/images/bu-upload-sm.jpg" class="greenButt" value="upload" /></td>
          	</tr>
			<tr id="alert2" >
           		<td style="font-size:12px; color:#777;" align="left"><?=$msg?></td>
           	</tr>
		</form>
	</table>
</center>
</body>
</html>
<?PHP
}elseif($_REQUEST["op"] == 3){
?>

<html>
<head>
	<?php
		if ($imageUpload == "YES") {
	?>
	<script language="javascript">
	try{
		parent.document.getElementById('<?=$IDFN?>').src = '<?=$disImageName?>';
		parent.document.getElementById('<?=$IDHN?>').value = '<?=$saveImageName?>';
		window.location.href="uploadfile.php?op=2<?="&idfn=$IDFN&idhn=$IDHN&idun=$IDUN&ut=$UT&msg=$msg&res={$_REQUEST['res']}"?>";
	}catch(ex){
		alert(ex);
	}
	</script>
	
	<?php
	}
	?>
	<title>Image Upload</title>
<link href="css/style.css" type="text/css" rel="stylesheet">
</head>

<body>
<center>
	<table width="98%" border="0" align="center" cellpadding="2">
		<form method="post" enctype="multipart/form-data" name="fileForm1" id="fileForm1">
          	<tr bgColor="#f5f8fe"  id="alert2" >
           		<td align="center" width="85%" class="star txt" style="font-size:12px" align="center"><?=$msg?></td>
            	<td width="80" align="center"><input name="Submit" type="submit" class="greenButt" value="Back" /></td>
          	</tr><input name="op" type="hidden" id="op" value="2" />
				<input type="hidden" name="IDFN" value="<?=$IDFN?>">
				<input type="hidden" name="IDHN" value="<?=$IDHN?>">
			    <input type="hidden" name="IDUN" value="<?=$IDUN?>">

		</form>
	</table>
</center>
</body>
</html>
<?php
}
?>