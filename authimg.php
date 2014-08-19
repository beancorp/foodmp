<?php
//create a image
include_once("include/functions.php");

Header("Content-type: image/PNG");

session_start();//将随机数存入session中
$_SESSION['authnum']="";
$im = imagecreate(62,20); //制定图片背景大小

$black = ImageColorAllocate($im, 0,0,0); //设定三种颜色
$white = ImageColorAllocate($im, 255,255,255);
$gray = ImageColorAllocate($im, 200,200,200);

imagefill($im,0,0,$gray); //采用区域填充法，设定（0,0）

$authnum=randStr(4);
//draw 4 chars to image
$_SESSION['authnum']=$authnum;
imagestring($im, 5, 10, 3, $authnum, $black);

for($i=0;$i<200;$i++){
$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
imagesetpixel($im, rand()%70 , rand()%30 , $randcolor);
}

ImagePNG($im);
ImageDestroy($im);
?>